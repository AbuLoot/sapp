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
use App\Models\IncomingDoc;

class EditProduct extends Component
{
    public $lang;
    public $company;
    public $stores;
    public $product;
    public $productBarcodes = [];
    public $barcodes = [''];
    public $idCode;
    public $countInStores = [];
    public $wholesalePrice;
    public $wholesalePriceMarkup;
    public $priceMarkup;

    protected $listeners = ['newData' => '$refresh'];

    protected $rules = [
        'product.title' => 'required|min:2',
        'product.company_id' => 'required|numeric',
        'product.category_id' => 'required|numeric',
        'product.purchase_price' => 'required|numeric',
        'product.price' => 'required|numeric',
        'product.type' => 'required|numeric',
        'product.unit' => 'numeric',
    ];

    public function mount($id)
    {
        if (! Gate::allows('edit-product', auth()->user())) {
            abort(403);
        }

        $this->company = auth()->user()->company;
        $this->stores = $this->company->stores;
        $this->product = Product::findOrFail($id);
        $this->productBarcodes = json_decode($this->product->barcodes) ?? [''];
        $this->barcodes = $this->productBarcodes;
        $this->idCode = $this->product->id_code;

        $this->countInStores = json_decode($this->product->count_in_stores, true) ?? [];

        foreach($this->stores as $index => $store) {
            if (empty($this->countInStores[$store->id]) && $index == 0) {
                $this->countInStores[$store->id] = $this->product->count;
            } elseif (empty($this->countInStores[$store->id])) {
                $this->countInStores[$store->id] = null;
            }
        }

        $this->wholesalePrice = $this->product->wholesale_price;
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
        $this->productBarcodes = $this->barcodes;
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
        $sameProduct = Product::where('in_company_id', $this->company->id)->whereJsonContains('barcodes', $barcode)->first();

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

        $totalCount = collect($this->countInStores)->sum();

        Product::where('id', $this->product->id)->update([
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
            'unit' => $this->product->unit,
            'type' => $this->product->type,
        ]);

        $this->dispatchBrowserEvent('show-toast', ['message' => 'Запись изменена']);
    }

    public function render()
    {
        $companies = Company::where('company_id', $this->company->id)->where('is_supplier', 1)->get();
        $currency = $this->company->currency->symbol;
        $units = Unit::all();

        return view('livewire.store.edit-product', [
                'units' => $units,
                'currency' => $currency,
                'companies' => $companies,
            ])->layout('livewire.store.layout');
    }
}