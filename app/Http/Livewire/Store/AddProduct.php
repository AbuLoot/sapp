<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Component;

use App\Models\Unit;
use App\Models\Store;
use App\Models\Company;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreDoc;
use App\Models\IncomingDoc;
use App\Models\DocType;

use App\Traits\GenerateDocNo;

class AddProduct extends Component
{
    use GenerateDocNo;

    public $lang;
    public $docNo;
    public $product;
    public $company;
    public $companies;
    public $categories;
    public $barcodes = [''];
    public $idCode;
    public $wholesalePrice;
    public $wholesalePriceMarkup;
    public $priceMarkup;
    public $productBarcodes = [];
    public $countInStores = [];

    protected $listeners = [
        'newData' => '$refresh',
    ];

    protected $rules = [
        'product.title' => 'required|min:2',
        'product.company_id' => 'required|numeric',
        'product.category_id' => 'required|numeric',
        'product.type' => 'required|numeric',
        'docNo' => 'required',
        'productBarcodes.*' => 'required',
        'product.purchase_price' => 'required|numeric',
        'product.price' => 'required|numeric',
        'product.unit' => 'numeric',
    ];

    public function mount()
    {
        if (! Gate::allows('add-product', auth()->user())) {
            abort(403);
        }

        $this->company = auth()->user()->profile->company;
        $this->store = session()->get('storage');
        $this->companies = Company::where('is_supplier', 1)->get();
        $this->product = new Product;
        $this->product->type = 1;
    }

    public function updated($key)
    {
        if (! is_numeric($this->wholesalePriceMarkup)) {
            $this->wholesalePriceMarkup = null;
        }

        if (! is_numeric($this->priceMarkup)) {
            $this->priceMarkup = null;
        }

        if ($key == 'wholesalePriceMarkup' && $this->wholesalePriceMarkup >= 1) {
            $this->wholesalePrice = $this->product->purchase_price * $this->wholesalePriceMarkup;
        }

        if ($key == 'priceMarkup' && $this->priceMarkup >= 1) {
            $this->product->price = $this->product->purchase_price * $this->priceMarkup;
        }
    }

    public function addBarcodeField()
    {
        $this->barcodes[] = '';
    }

    public function deleteBarcodeField($index)
    {
        unset($this->barcodes[$index]);
        array_values($this->barcodes);
    }

    public function generateBarcode($index)
    {
        $firstCode = '200'; // 200-299

        $companyId = (is_numeric($this->product->company_id)) ? $this->product->company_id : '000000';
        $secondCode = substr(sprintf("%'.06d", $companyId), -6);

        $lastSeconds = substr(intval(microtime(true)), -2);
        $thirdCode = $lastSeconds.$index;

        $arrCode = str_split($firstCode.$secondCode.$thirdCode);

        $number = 1;
        $evenSum = 0;
        $oddSum = 0;

        foreach ($arrCode as $key => $value) {
            if ($number % 2 == 0) {
                $evenSum += $value;
            } else {
                $oddSum += $value;
            }
            $number++;
        }

        $evenSum = $evenSum * 3;
        $bothSum = $evenSum + $oddSum;
        $lastNum = substr($bothSum, -1);

        $barcode = $firstCode.$secondCode.$thirdCode.$lastNum;
        $sameProduct = Product::whereJsonContains('barcodes', $barcode)->first();

        if (in_array($barcode, $this->productBarcodes) || $sameProduct) {
            $firstCode += ($firstCode == '299') ? -98 : 1;
            $thirdCode + 1;
            $barcode = $firstCode.$secondCode.$thirdCode.$lastNum;
        }

        $this->productBarcodes[$index] = $barcode;

        return $barcode;
    }

    public function saveProduct()
    {
        $this->validate();

        $lastProduct = Product::orderByDesc('id')->first();
        $totalCount = collect($this->countInStores)->sum();

        $product = Product::create([
            'sort_id' => $lastProduct->id + 1,
            'user_id' => auth()->user()->id,
            'company_id' => $this->product->company_id ?? 0,
            'category_id' => $this->product->category_id,
            'slug' => Str::slug($this->product->title),
            'title' => $this->product->title,
            'barcodes' => json_encode($this->productBarcodes),
            'id_code' => $idCode ?? NULL,
            'purchase_price' => $this->product->purchase_price ?? 0,
            'wholesale_price' => $this->wholesalePrice ?? 0,
            'price' => $this->product->price,
            'count_in_stores' => json_encode($this->countInStores),
            'count' => $totalCount ?? 0,
            'type' => $this->product->type,
            'unit' => $this->product->unit ?? 0,
            'image' => 'no-image-middle.png',
            'lang' => 'ru',
            'status' => 1,
        ]);

        // Getting Incoming Doc
        $docType = DocType::where('slug', 'forma-z-1')->first();

        if ($product->type == 1) {

            $productData = [];

            foreach ($this->countInStores as $storeId => $countInStore) {

                $docNo = $this->generateIncomingStoreDocNo($storeId);

                $productData[$product->id]['purchase_price'] = $product->purchase_price;
                $productData[$product->id]['count'] = $countInStore;
                $productData[$product->id]['unit'] = $product->unit;
                $productData[$product->id]['barcodes'] = $product->barcodes;

                $incomingDoc = new IncomingDoc;
                $incomingDoc->store_id = $storeId;
                $incomingDoc->company_id = $this->company->id;
                $incomingDoc->workplace_id = session()->get('storageWorkplace');
                $incomingDoc->user_id = auth()->user()->id;
                $incomingDoc->doc_no = $docNo;
                $incomingDoc->doc_type_id = $docType->id;
                $incomingDoc->products_data = json_encode($productData);
                $incomingDoc->contractor_type = 'App\Models\Company';
                $incomingDoc->contractor_id = $this->product->company_id;
                $incomingDoc->operation_code = 'incoming-products';
                $incomingDoc->sum = $countInStore * $product->purchase_price;
                $incomingDoc->currency = $this->company->currency->code;
                $incomingDoc->count = $countInStore;
                $incomingDoc->unit = $product->unit;
                $incomingDoc->save();

                $storeDoc = new StoreDoc;
                $storeDoc->store_id = $storeId;
                $storeDoc->company_id = $this->company->id;
                $storeDoc->user_id = auth()->user()->id;
                $storeDoc->doc_type = 'App\Models\IncomingDoc';
                $storeDoc->doc_id = $incomingDoc->id;
                $storeDoc->products_data = json_encode($productData);
                $storeDoc->contractor_type = 'App\Models\Company';
                $storeDoc->contractor_id = $this->product->company_id;
                $storeDoc->incoming_amount = 0;
                $storeDoc->outgoing_amount = $countInStore * $product->purchase_price;
                $storeDoc->count = $countInStore;
                $storeDoc->sum = $countInStore * $product->purchase_price;
                $storeDoc->save();
            }
        }

        session()->flash('message', 'Запись добавлена');

        return redirect($this->lang.'/storage/edit-product/'.$product->id);
    }

    public function render()
    {
        $currency = $this->company->currency->symbol;
        $stores = $this->company->stores;
        $units = Unit::all();

        $this->docNo = $this->generateIncomingStoreDocNo($this->store->id);

        return view('livewire.store.add-product', ['units' => $units, 'stores' => $stores, 'currency' => $currency])
            ->layout('livewire.store.layout');
    }
}
