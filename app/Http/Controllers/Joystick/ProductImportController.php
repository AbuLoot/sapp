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
use App\Traits\GenerateDocNo;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductImportController extends Controller
{
    use GenerateDocNo;

    public $companyId;
    private $company;
    private $companies;
    private $store;
    private $storeNum;
    private $categories;
    private $products;

    public function importView()
    {
        if (! Gate::allows('import', \Auth::user())) {
            abort(403);
        }

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {

            if (isset($_GET['company_id'])) {

                $companyId = trim(strip_tags($_GET['company_id']));

                $this->company = Company::query()
                        ->where('id', $companyId)
                        ->where('sn_client', true)
                        ->first();

                $stores = $this->company->stores;

                return view('joystick.products.import', ['company' => $this->company, 'stores' => $stores]);
            }

            $companies = Company::where('sn_client', true)->select('id', 'slug', 'title')->get();
            return view('joystick.products.import', ['companies' => $companies]);
        }

        $this->company = Company::query()
                ->where('id', $this->companyId)
                ->where('sn_client', true)
                ->first();

        $stores = $this->company->stores;

        return view('joystick.products.import', ['company' => $this->company, 'stores' => $stores]);
    }

    public function selectCompany(Request $request, $lang)
    {
        if (! Gate::allows('import', \Auth::user())) {
            abort(403);
        }

        $request->validate([
                'company_id' => ['required', 'integer'],
            ]);

        return redirect(app()->getLocale().'/admin/products-import?company_id='.$request->company_id);
    }

    public function fastImport(Request $request, $lang)
    {
        if (! Gate::allows('import', \Auth::user())) {
            abort(403);
        }

        $request->validate([
                'store_id' => 'required|integer',
                'file' => 'required|mimetypes:application/vnd.oasis.opendocument.spreadsheet,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel',
            ]);

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {
            $this->companyId = $request->company_id;
        }

        $this->company = Company::where('id', $this->companyId)->where('sn_client', true)->first();
        $this->companies = Company::where('company_id', $this->companyId)->select('id', 'slug', 'title')->get();
        $this->categories = Category::where('company_id', $this->companyId)->select('id', 'slug', 'title')->get()->toTree();
        $this->products = Product::where('in_company_id', $this->companyId)->get();
        $this->store = $this->company->stores->firstWhere('id', $request->store_id);
        $this->storeNum = $this->store->num_id;

        $userId = auth()->user()->id;
        $productsData = [];
        $productsCount = $this->products->count();
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

            $productCount = (int) $product['Count'];
            $purchasePrice = (int) str_replace(" ", "", $product['Purchase price']) ?? 0;
            $wholesalePrice = (int) str_replace(" ", "", $product['Wholesale price']) ?? 0;
            $price =  (int) str_replace(" ", "", $product['Price']) ?? 0;

            $incomingProduct = $this->products->firstWhere('title', $product['Title']);

            if ($incomingProduct) {

                $countInStores = json_decode($incomingProduct->count_in_stores, true) ?? [''];
                $countInStores[$this->storeNum] = $productCount;

                $incomingProduct->count_in_stores = json_encode($countInStores);
                $incomingProduct->count += $productCount;
                $incomingProduct->save();
            }
            else {
                $incomingProduct = Product::create([
                    'sort_id' => ++$productsCount,
                    'user_id' => $userId,
                    'category_id' => $category->id,
                    'in_company_id' => $this->companyId,
                    'company_id' => $company->id ?? 0,
                    'title' => $product['Title'],
                    'slug' => Str::slug($product['Title']),
                    'meta_title' => $product['Title'],
                    'meta_description' => $product['Title'].' - '.$category->title,
                    'barcodes' => json_encode([$product['Code']]),
                    'id_code' => $product['Code'] ?? NULL,
                    'purchase_price' => $purchasePrice,
                    'wholesale_price' => $wholesalePrice,
                    'price' => $price,
                    'count_in_stores' => json_encode([$this->storeNum => $productCount ?? 0]),
                    'count' => $productCount ?? 0,
                    'type' => (empty($product['Type']) OR $product['Type'] == 'Товар') ? 1 : 2,
                    'image' => 'no-image-middle.png',
                    'lang' => 'ru',
                    'status' => 1
                ]);
            }

            $productsData[$incomingProduct->id]['purchase_price'] = $purchasePrice;
            $productsData[$incomingProduct->id]['count'] = $productCount;
            $productsData[$incomingProduct->id]['unit'] = null;
            $productsData[$incomingProduct->id]['barcodes'] = $product['Code'];

            $incomeTotalCount = $incomeTotalCount + $productCount;
            $incomeTotalAmount = $incomeTotalAmount + ($purchasePrice * $productCount);
        }

        // $products = Product::where('status', '<>', '0')->get();
        // $products->searchable();

        $this->makeDoc($productsData, $incomeTotalCount, $incomeTotalAmount);

        return redirect($lang.'/admin/products')->with('status', 'Данные добавлены.');
    }

    public function makeDoc($productsData, $incomeTotalCount, $incomeTotalAmount)
    {
        // Incoming Doc
        $docType = DocType::where('slug', 'forma-z-1')->first();
        $docNo = $this->generateIncomingStoreDocNo($this->storeNum);

        $incomingDoc = new IncomingDoc;
        $incomingDoc->store_id = $this->store->id;
        $incomingDoc->company_id = $this->companyId;
        $incomingDoc->workplace_id = null;
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
        $storeDoc->store_id = $this->store->id;
        $storeDoc->company_id = $this->companyId;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_type = 'App\Models\IncomingDoc';
        $storeDoc->doc_id = $incomingDoc->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = $incomeTotalAmount;
        $storeDoc->count = $incomeTotalCount;
        $storeDoc->sum = $incomeTotalAmount;
        $storeDoc->save();
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
