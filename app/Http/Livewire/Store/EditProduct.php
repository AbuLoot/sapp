<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Str;
use Livewire\Component;

use App\Models\Unit;
use App\Models\Store;
use App\Models\Company;
use App\Models\Category;
use App\Models\Product;
use App\Models\IncomingDoc;

class EditProduct extends Component
{
    public $product;
    public $productBarcodes = [];
    public $companies;
    public $categories;
    public $barcodes = [''];
    public $doc_no;
    public $id_code;
    public $unit;
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
        'product.count' => 'required|numeric',
        'doc_no' => 'required',
        'productBarcodes.*' => 'required',
    ];

    public function mount()
    {
        $this->product = new Product;
        $this->product->type = 1;
        $this->companies = Company::where('is_supplier', 1)->get();
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
        $sameProduct = Product::where('barcode', $barcode)->first();

        if (in_array($barcode, $this->productBarcodes) || $sameProduct) {
            $firstCode += ($firstCode == '299') ? -98 : 1;
            $thirdCode + 1;
            $fourthCode = substr(sprintf("%'.02d", $fourthCode + 1), -2);
            $barcode = $firstCode.$secondCode.$thirdCode.$fourthCode;
        }

        $this->productBarcodes[$index] = $barcode;

        return $barcode;
    }

    public function saveProduct()
    {
        $this->validate();

        $lastProduct = Product::orderByDesc('id')->first();

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
            'wholesale_price' => $this->wholesale_price,
            'price' => $this->product->price,
            'count' => $this->product->count,
            'type' => $this->product->type,
            'image' => 'no-image-middle.png',
            'lang' => 'ru',
            'status' => 1,
        ]);

        // Store
        // $companyStore = auth()->user()->profile->company;

        $incomingDoc = new IncomingDoc;
        $incomingDoc->store_id = 1;
        $incomingDoc->company_id = $this->product->company_id;
        $incomingDoc->user_id = auth()->user()->id;
        $incomingDoc->username = auth()->user()->name;
        $incomingDoc->doc_no = $this->doc_no;
        $incomingDoc->doc_type_id = 3;
        $incomingDoc->products_ids = json_encode($product->id);
        $incomingDoc->from_contractor = Company::find($this->product->company_id)->title;
        $incomingDoc->sum = $this->purchase_price * $this->product->count;
        $incomingDoc->currency = auth()->user()->profile->company->currency->code;
        $incomingDoc->count = $this->product->count;
        $incomingDoc->unit = $this->unit;
        // $incomingDoc->comment = '';
        $incomingDoc->save();

        // $this->reset();
        // $this->product = new Product;
        // $this->product->type = 1;

        $this->reset('doc_no', 'productBarcodes', 'id_code', 'purchase_price', 'wholesale_price', 'wholesale_price_markup', 'price_markup');
        $this->product->title = null;
        $this->product->price = null;
        $this->product->count = null;
        $this->barcodes = [''];

        session()->flash('message', 'Запись добавлена.');
    }

    public function render()
    {
        $companyStore = auth()->user()->profile->company;
        $currency = $companyStore->currency->symbol;
        $stores = Store::where('company_id', $companyStore->id)->get();
        $units = Unit::all();

        return view('livewire.store.edit-product', ['units' => $units, 'stores' => $stores, 'currency' => $currency]);
    }
}