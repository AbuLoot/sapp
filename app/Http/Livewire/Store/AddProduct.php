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

        $this->company = auth()->user()->company;
        $this->stores = $this->company->stores;
        $this->store = session()->get('storage');
        $this->product = new Product;
        $this->product->type = 1;
        $this->docNo = $this->generateIncomingStoreDocNo($this->store->num_id);

        foreach ($this->stores as $store) {
            $this->countInStores[$store->num_id] = 0;
        }
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

        $totalCount = collect($this->countInStores)->sum();

        $product = Product::create([
            'user_id' => auth()->user()->id,
            'in_company_id' => $this->company->id,
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

            foreach ($this->countInStores as $storeNum => $countInStore) {

                if (!$countInStore) {
                    continue;
                }

                $docNoParts = explode('/', $this->docNo);
                $firstDocNo = $docNoParts[0] == $storeNum ? $this->docNo : null;

                $storeId = $this->stores->where('num_id', $storeNum)->first()->id;
                $docNo = $this->generateIncomingStoreDocNo($storeNum, $firstDocNo);

                $productData[$product->id]['purchase_price'] = $product->purchase_price;
                $productData[$product->id]['count'] = $countInStore;
                $productData[$product->id]['unit'] = $product->unit;
                $productData[$product->id]['barcodes'] = $product->barcodes;

                $incomingDoc = IncomingDoc::create([
                    'store_id' => $storeId,
                    'company_id' => $this->company->id,
                    'workplace_id' => session()->get('storageWorkplace'),
                    'user_id' => auth()->user()->id,
                    'doc_no' => $docNo,
                    'doc_type_id' => $docType->id,
                    'products_data' => json_encode($productData),
                    'contractor_type' => 'App\Models\Company',
                    'contractor_id' => $this->product->company_id,
                    'operation_code' => 'incoming-products',
                    'sum' => $countInStore * $product->purchase_price,
                    'currency' => $this->company->currency->code,
                    'count' => $countInStore,
                    'unit' => $product->unit,
                ]);

                StoreDoc::create([
                    'store_id' => $storeId,
                    'company_id' => $this->company->id,
                    'user_id' => auth()->user()->id,
                    'doc_type' => 'App\Models\IncomingDoc',
                    'doc_id' => $incomingDoc->id,
                    'products_data' => json_encode($productData),
                    'contractor_type' => 'App\Models\Company',
                    'contractor_id' => $this->product->company_id,
                    'incoming_amount' => 0,
                    'outgoing_amount' => $countInStore * $product->purchase_price,
                    'count' => $countInStore,
                    'sum' => $countInStore * $product->purchase_price,
                ]);
            }
        }

        session()->flash('message', 'Запись добавлена');

        return redirect($this->lang.'/storage/edit-product/'.$product->id);
    }

    public function render()
    {
        $companies = Company::where('company_id', $this->company->id)->where('is_supplier', 1)->get();
        $currency = $this->company->currency->symbol;
        $units = Unit::all();

        return view('livewire.store.add-product', [
                'units' => $units,
                'currency' => $currency,
                'companies' => $companies
            ])->layout('livewire.store.layout');
    }
}
