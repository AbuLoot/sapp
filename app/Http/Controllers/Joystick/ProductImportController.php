<?php

namespace App\Http\Controllers\Joystick;

use App\Http\Controllers\Joystick\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use App\Models\Store;
use App\Models\StoreDoc;
use App\Models\IncomingDoc;
use App\Models\DocType;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductImportController extends Controller
{
    public $companyId;
    private $userId;
    private $company;
    private $categories;
    private $products;
    private $companies;

    public function importView()
    {
        if (! Gate::allows('import', \Auth::user())) {
            abort(403);
        }

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {
            $companies = Company::where('sn_client', true)->select('id', 'slug', 'title')->get();
        }

        $stores = Store::where('company_id', $this->companyId)->get();

        return view('joystick.products.import', ['companies' => $companies, 'stores' => $stores]);
    }

    public function selectCompany(Request $request, $id, $lang)
    {
        if (! Gate::allows('import', \Auth::user())) {
            abort(403);
        }

        dd($request->all(), $id);

        $request->validate([
                'company_id' => ['required', 'integer'],
            ]);

        $this->company = Company::query()
                ->where('id', $this->companyId)
                ->where('sn_client', true)
                ->first();

        $stores = $this->company->stores;

        dd($this->company, $stores);

        return view('joystick.products.import', ['companies' => $companies, 'stores' => $stores]);
    }

    public function fastImport(Request $request, $lang)
    {
        if (! Gate::allows('import', \Auth::user())) {
            abort(403);
        }

        $this->userId = auth()->user()->id;
        $this->firstStore = auth()->user()->company->stores->first();
        $this->companies = Company::where('company_id', $this->companyId)->select('id', 'slug', 'title')->get();
        $this->categories = Category::where('company_id', $this->companyId)->select('id', 'slug', 'title')->get()->toTree();
        $this->products = Product::where('in_company_id', $this->companyId)->get();
        $productsCount = $this->products->count();

        $productsData = [];
        $incomeTotalCount = 0;
        $incomeTotalAmount = 0;

        $importProducts = (new FastExcel)->import($request->file('file'));

        foreach ($importProducts as $key => $product) {

            $company = $this->companies->firstWhere('title', $product['Company']);
            $category = $this->categories->firstWhere('title', $product['Category']);

            if (is_null($company)) {
                $company = $this->createCompany($product['Company']);
                $this->companies = Company::where('company_id', $this->companyId)->select('id', 'slug', 'title')->get();
            }

            if (is_null($category)) {
                $category = $this->createCategory($product['Category']);
                $this->categories = Category::where('company_id', $this->companyId)->select('id', 'slug', 'title')->get();
            }

            $existProduct = $this->products->firstWhere('title', $product['Title']);

            if ($existProduct) {
                continue;
            }

            Product::create([
                'sort_id' => ++$productsCount,
                'user_id' => $this->userId,
                'category_id' => $category->id,
                'in_company_id' => $this->companyId,
                'company_id' => $company->id ?? 0,
                'title' => $product['Title'],
                'slug' => Str::slug($product['Title']),
                'meta_title' => $product['Title'],
                'meta_description' => $product['Title'].' - '.$category->title,
                'barcodes' => json_encode([$product['Code']]),
                'id_code' => $product['Code'] ?? NULL,
                'purchase_price' => (int) str_replace(" ", "", $product['Purchase price']) ?? 0,
                'wholesale_price' => (int) str_replace(" ", "", $product['Wholesale price']) ?? 0,
                'price' => (int) str_replace(" ", "", $product['Price']) ?? 0,
                'count_in_stores' => json_encode([$this->firstStore->num_id => $product['Count'] ?? 0]),
                'count' => $product['Count'] ?? 0,
                'type' => ($product['Type'] == 'Товар') ? 1 : 2,
                'image' => 'no-image-middle.png',
                'lang' => 'ru',
                'status' => 1
            ]);

            $productsData[$product->id]['purchase_price'] = $product['Purchase price'];
            $productsData[$product->id]['count'] = $product['Count'];
            $productsData[$product->id]['unit'] = null;
            $productsData[$product->id]['barcodes'] = $product['Code'];

            $incomeTotalCount = $incomeTotalCount + $product['Count'];
            $incomeTotalAmount = $incomeTotalAmount + ($product['Purchase price'] * $product['Count']);
        }

        // $products = Product::where('status', '<>', '0')->get();
        // $products->searchable();

        return redirect($lang.'/admin/products')->with('status', 'Данные добавлены.');
    }

    public function makeDoc()
    {
        // Incoming Doc
        $docType = DocType::where('slug', 'forma-z-1')->first();
        $docNo = $this->generateIncomingStoreDocNo($this->storeNum);

        $incomingDoc = new IncomingDoc;
        $incomingDoc->store_id = $this->storeId;
        $incomingDoc->company_id = $this->company->id;
        $incomingDoc->workplace_id = $workplaceId;
        $incomingDoc->user_id = auth()->user()->id;
        $incomingDoc->doc_no = $docNo;
        $incomingDoc->doc_type_id = $docType->id;
        $incomingDoc->products_data = json_encode($productsData);
        $incomingDoc->operation_code = 'incoming-products';
        $incomingDoc->sum = $incomeTotalAmount;
        $incomingDoc->currency = $this->company->currency->code;
        $incomingDoc->count = $incomeTotalCount;
        $incomingDoc->save();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->storeId;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_type = 'App\Models\IncomingDoc';
        $storeDoc->doc_id = $incomingDoc->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = $incomeTotalAmount;
        $storeDoc->sum = $incomeTotalAmount;
        $storeDoc->save();

        $this->incomeProducts = [];
        $this->incomeProductsCount = [];
    }

    public function createCompany($companyTitle)
    {
        $company = new Company;
        $company->sort_id = $this->companies->count() + 1;
        $company->company_id = $this->companyId;
        $company->region_id = 0;
        $company->slug = Str::slug($companyTitle);
        $company->title = $companyTitle;
        $company->image = 'no-image-mini.png';
        $company->is_supplier = 1;
        $company->is_customer = 0;
        $company->status = 1;
        $company->save();

        return $company;
    }

    public function createCategory($categoryTitle)
    {
        $category = new Category;
        $category->sort_id = $this->categories->count() + 1;
        $category->company_id = $this->companyId;
        $category->slug = Str::slug($categoryTitle);
        $category->title = $categoryTitle;
        $category->image = 'no-image-middle.png';
        $category->saveAsRoot();
        $category->lang = 'ru';
        $category->status = 1;
        $category->save();

        return $category;
    }
}
