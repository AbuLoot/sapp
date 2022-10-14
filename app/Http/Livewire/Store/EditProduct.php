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
    public $product;
    public $productBarcodes = [];
    public $companies;
    public $barcodes = [];
    public $idCode;
    public $countInStores = [];
    public $purchasePrice;
    public $wholesalePrice;
    public $wholesalePriceMarkup;
    public $priceMarkup;

    protected $listeners = ['newData' => '$refresh'];

    protected $rules = [
        'product.title' => 'required|min:2',
        'product.company_id' => 'required|numeric',
        'product.category_id' => 'required|numeric',
        'product.type' => 'required|numeric',
        'product.price' => 'required',
        'product.unit' => 'numeric',
        // 'product.count.*' => 'required|numeric',
        // 'productBarcodes.*' => 'required',
    ];

    public function mount($id)
    {
        if (! Gate::allows('edit-product', auth()->user())) {
            abort(403);
        }

        $this->company = auth()->user()->profile->company;
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

        $this->purchasePrice = $this->product->purchase_price;
        $this->wholesalePrice = $this->product->wholesale_price;
        $this->price = $this->product->price;
        $this->companies = Company::where('is_supplier', 1)->get();
    }

    public function updated($key)
    {
        if ($key == 'wholesalePriceMarkup' && $this->wholesalePriceMarkup >= 1) {
            // $amount_price = $this->purchasePrice * $this->wholesalePriceMarkup;
            // $this->wholesalePrice = number_format($amount_price, 0, '.', ' ');
            $this->wholesalePrice = $this->purchasePrice * $this->wholesalePriceMarkup;
        }

        if ($key == 'priceMarkup' && $this->priceMarkup >= 1) {
            $this->product->price = $this->purchasePrice * $this->priceMarkup;
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

    public function generateOldBarcode($index)
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

        $amountCount = collect($this->countInStores)->sum();

        Product::where('id', $this->product->id)->update([
            // 'sort_id' => $lastProduct->id + 1,
            'user_id' => auth()->user()->id,
            'company_id' => $this->product->company_id ?? 0,
            'category_id' => $this->product->category_id,
            'slug' => Str::slug($this->product->title),
            'title' => $this->product->title,
            'barcodes' => json_encode($this->productBarcodes),
            'id_code' => $this->idCode ?? NULL,
            'purchase_price' => $this->purchasePrice ?? 0,
            'wholesale_price' => $this->wholesalePrice ?? 0,
            'price' => $this->product->price,
            // 'count_in_stores' => json_encode($this->countInStores),
            // 'count' => $amountCount,
            'unit' => $this->product->unit,
            'type' => $this->product->type,
        ]);

        $this->dispatchBrowserEvent('show-toast', ['message' => 'Запись изменена']);
    }

    public function render()
    {
        $currency = $this->company->currency->symbol;
        $units = Unit::all();

        return view('livewire.store.edit-product', ['units' => $units, 'currency' => $currency])
            ->layout('livewire.store.layout');
    }
}