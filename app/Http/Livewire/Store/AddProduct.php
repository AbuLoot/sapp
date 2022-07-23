<?php

namespace App\Http\Livewire\Store;

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

class AddProduct extends Component
{
    public $lang;
    public $product;
    public $productBarcodes = [];
    public $company;
    public $companies;
    public $categories;
    public $barcodes = [''];
    public $doc_no;
    public $id_code;
    public $countInStores = [];
    public $purchase_price;
    public $wholesale_price;
    public $wholesale_price_markup;
    public $price_markup;

    protected $listeners = ['newData' => '$refresh'];

    protected $rules = [
        'product.title' => 'required|string|min:2',
        'product.company_id' => 'required|numeric',
        'product.category_id' => 'required|numeric',
        'product.type' => 'required|numeric',
        'product.price' => 'required',
        'countInStores.*' => 'required|numeric',
        'product.unit' => 'required|numeric',
        'doc_no' => 'required',
        'productBarcodes.*' => 'required',
    ];

    public function mount()
    {
        $this->company = auth()->user()->profile->company;
        $this->companies = Company::where('is_supplier', 1)->get();
        $this->product = new Product;
        $this->product->type = 1;
    }

    public function updated($key)
    {
        if ($key == 'wholesale_price_markup' && $this->wholesale_price_markup >= 1) {
            // $amount_price = $this->purchase_price * $this->wholesale_price_markup;
            // $this->wholesale_price = number_format($amount_price, 0, '.', ' ');
            $this->wholesale_price = $this->purchase_price * $this->wholesale_price_markup;
        }

        if ($key == 'price_markup' && $this->price_markup >= 1) {
            $this->product->price = $this->purchase_price * $this->price_markup;
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

        $companyId = (is_numeric($this->product->company_id)) ? $this->product->company_id : '0000';
        $secondCode = substr(sprintf("%'.04d", $companyId), -4);

        $lastSeconds = substr(intval(microtime(true)), -3);
        $thirdCode = $lastSeconds.$index;

        $fourthCode = substr(sprintf("%'.02d", $index + 1), -2);

        $barcode = $firstCode.$secondCode.$thirdCode.$fourthCode;
        $sameProduct = Product::whereJsonContains('barcodes', $barcode)->first();

        if (in_array($barcode, $this->productBarcodes) || $sameProduct) {
            $firstCode += ($firstCode == '299') ? -98 : 1;
            $thirdCode + 1;
            $fourthCode = substr(sprintf("%'.02d", $fourthCode + 1), -2);
            $barcode = $firstCode.$secondCode.$thirdCode.$fourthCode;
        }

        $this->productBarcodes[$index] = $barcode;

        return $barcode;
    }

    public function generateDocNo($store_id, $docNo = null)
    {
        $lastDoc = IncomingDoc::where('doc_no', 'like', $store_id.'/_')->orderByDesc('id')->first();

        if ($lastDoc && is_null($docNo)) {
            list($first, $second) = explode('/', $lastDoc->doc_no);
            $docNo = $first.'/'.++$second;
        } elseif (is_null($docNo)) {
            $docNo = $store_id.'/1';
        }

        $existDoc = IncomingDoc::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return $this->generateDocNo($store_id, $docNo);
        }

        return $docNo;
    }

    public function saveProduct()
    {
        $this->validate();

        $lastProduct = Product::orderByDesc('id')->first();
        $amountCount = collect($this->countInStores)->sum();

        $product = Product::create([
            'sort_id' => $lastProduct->id + 1,
            'user_id' => auth()->user()->id,
            'company_id' => $this->product->company_id ?? 0,
            'category_id' => $this->product->category_id,
            'slug' => Str::slug($this->product->title),
            'title' => $this->product->title,
            'barcodes' => json_encode($this->productBarcodes),
            'id_code' => $this->id_code ?? NULL,
            'purchase_price' => $this->purchase_price ?? 0,
            'wholesale_price' => $this->wholesale_price ?? 0,
            'price' => $this->product->price,
            'count_in_stores' => json_encode($this->countInStores),
            'count' => $amountCount,
            'type' => $this->product->type,
            'unit' => $this->product->unit,
            'image' => 'no-image-middle.png',
            'lang' => 'ru',
            'status' => 1,
        ]);

        // Getting Incoming Doc
        $docType = DocType::where('slug', 'forma-z-1')->first();
        $contractorCompany = Company::find($this->product->company_id)->title;

        $product_data = [];
        $incomingDocsId = [];

        foreach ($this->countInStores as $storeId => $countInStore) {

            $docNo = $this->generateDocNo($storeId);

            $product_data[$product->id]['count'] = $countInStore;
            $product_data[$product->id]['unit'] = $product->unit;
            $product_data[$product->id]['barcodes'] = $product->barcodes;

            $incomingDoc = new IncomingDoc;
            $incomingDoc->store_id = $storeId;
            $incomingDoc->company_id = $this->company->id;
            $incomingDoc->user_id = auth()->user()->id;
            $incomingDoc->username = auth()->user()->name;
            $incomingDoc->doc_no = $docNo;
            $incomingDoc->doc_type_id = $docType->id;
            $incomingDoc->products_data = json_encode($product_data);
            $incomingDoc->from_contractor = $contractorCompany;
            // $incomingDoc->to_contractor = '';
            $incomingDoc->sum = $countInStore * $product->price;
            $incomingDoc->currency = $this->company->currency->code;
            $incomingDoc->count = $countInStore;
            $incomingDoc->unit = $product->unit;
            $incomingDoc->comment = '';
            $incomingDoc->save();

            $incomingDocsId[] = $incomingDoc->id;
        }

        $product_data[$product->id]['count'] = $amountCount;

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->company->stores->first()->id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        // $storeDoc->doc_id = $incomingDoc; // For One Doc
        $storeDoc->docs_id = json_encode($incomingDocsId);
        $storeDoc->doc_type_id = $docType->id;
        $storeDoc->products_data = json_encode($product_data);
        $storeDoc->from_contractor = $contractorCompany;
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = $amountCount * $product->price;
        $storeDoc->sum = $amountCount;
        $storeDoc->comment = '';
        $storeDoc->save();

        // $this->product = new Product;
        // $this->product->type = 1;

        $this->reset('doc_no', 'productBarcodes', 'id_code', 'purchase_price', 'wholesale_price', 'wholesale_price_markup', 'price_markup');
        $this->product->title = null;
        $this->product->price = null;
        $this->barcodes = [''];
        $this->countInStores = [];

        session()->flash('message', 'Запись добавлена');
    }

    public function render()
    {
        $currency = $this->company->currency->symbol;
        $stores = Store::where('company_id', $this->company->id)->get();
        $units = Unit::all();
        $this->doc_no = $this->generateDocNo($stores->first()->id);

        return view('livewire.store.add-product', ['units' => $units, 'stores' => $stores, 'currency' => $currency])
            ->layout('livewire.store.layout');
    }
}
