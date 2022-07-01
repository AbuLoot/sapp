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
    public $lang;
    public $company;
    public $product;
    public $productBarcodes = [];
    public $companies;
    public $barcodes = [''];
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
        'product.count.*' => 'required|numeric',
        'product.unit' => 'required|numeric',
        'productBarcodes.*' => 'required',
    ];

    public function mount($id)
    {
        $this->company = auth()->user()->profile->company;
        $this->stores = $this->company->stores;
        $this->product = Product::findOrFail($id);
        $this->productBarcodes = json_decode($this->product->barcodes) ?? [''];
        $this->barcodes = $this->productBarcodes;
        $this->id_code = $this->product->id_code;

        $this->countInStores = json_decode($this->product->count_in_stores, true) ?? [''];

        foreach($this->stores as $index => $store) {
            if (empty($this->countInStores[$store->id]) && $index == 0) {
                $this->countInStores[$store->id] = $this->product->count;
            } elseif (empty($this->countInStores[$store->id])) {
                $this->countInStores[$store->id] = null;
            }
        }

        $this->product['count'] = $this->countInStores;

        $this->purchase_price = $this->product->purchase_price;
        $this->wholesale_price = $this->product->wholesale_price;
        $this->price = $this->product->price;
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

    public function saveProduct()
    {
        $this->validate();

        Product::where('id', $this->product->id)->update([
            // 'sort_id' => $lastProduct->id + 1,
            // 'user_id' => auth()->user()->id,
            'company_id' => $this->product->company_id ?? 0,
            'category_id' => $this->product->category_id,
            'slug' => Str::slug($this->product->title),
            'title' => $this->product->title,
            'barcodes' => json_encode($this->productBarcodes),
            'id_code' => $this->id_code ?? NULL,
            'purchase_price' => $this->purchase_price ?? 0,
            'wholesale_price' => $this->wholesale_price ?? 0,
            'price' => $this->product->price,
            'count_in_stores' => json_encode([]),
            'count' => $this->product->count,
            'unit' => $this->product->unit,
            'type' => $this->product->type,
        ]);

        // Store
        // $companyStore = auth()->user()->profile->company;

        session()->flash('message', 'Запись изменена.');
    }

    public function render()
    {
        $currency = $this->company->currency->symbol;
        $units = Unit::all();

        return view('livewire.store.edit-product', ['units' => $units, 'currency' => $currency])
            ->layout('store.layout');
    }
}