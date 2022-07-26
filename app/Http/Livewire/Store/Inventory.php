<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Unit;
use App\Models\Store;
use App\Models\StoreDoc;
use App\Models\Revision;
use App\Models\RevisionProduct;
use App\Models\DocType;
use App\Models\Product;

class Inventory extends Component
{
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $lang;
    public $units;
    public $docNo;
    public $store_id;
    public $search = '';
    public $revisionProducts = [];
    public $actualCount = [];
    public $barcodesCount = [];
    public $products_data = [];
    public $revisionModal = false;

    protected $listeners = ['checkData' => '$refresh'];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->units = Unit::get();
        $this->company = auth()->user()->profile->company;
        $this->store_id = $this->company->first()->id;
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        if (count($parts) == 3 && $parts[0] == 'actualCount') {

            $revisionProducts = session()->get('revisionProducts');

            if (!is_numeric($value) || $value < 0) {
                $revisionProducts[$parts[1]]['actualCount'] = [$this->store_id => null];
                $this->actualCount[$parts[1]][$this->store_id] = null;
                session()->put('revisionProducts', $revisionProducts);
                return false;
            }

            $revisionProducts[$parts[1]]['actualCount'] = [$this->store_id => $value];
            $this->actualCount[$parts[1]][$this->store_id] = $value;
            session()->put('revisionProducts', $revisionProducts);
        }
    }

    public function generateDocNo($store_id, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = Revision::where('doc_no', 'like', $store_id.'/_')->orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.++$second;
            } else {
                $docNo = $store_id.'/1';
            }
        }

        $existDoc = Revision::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            $this->generateDocNo($store_id, $docNo);
        }

        return $docNo;
    }

    public function checkBarcodesCount()
    {
        // If store id empty, return wrong
        if (empty($this->barcodesCount) || strlen($this->barcodesCount) < 14) {
            $this->addError('barcodesCount', 'Некорректное значение');
            return false;
        } else {
            $this->resetErrorBag('barcodesCount');
        }

        $barcodesCount = explode("\n", trim($this->barcodesCount));

        $products_data['products_count'] = 0;
        $products_data['shortage_count'] = 0;
        $products_data['surplus_count'] = 0;

        foreach ($barcodesCount as $barcodeCount) {

            list($barcode) = explode(' ', $barcodeCount);
            $count = trim(strstr($barcodeCount, ' '));

            if (empty($barcodeCount) || strlen($barcodeCount) < 14) {
                continue;
            }

            // If count empty, return wrong
            if (!isset($count) || strlen($barcodeCount) > 24) {
                $this->addError('barcodesCount', 'Некорректное значение');
                return false;
            }

            $product = Product::whereJsonContains('barcodes', $barcode)->first();

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            unset($countInStores[0]);
            $countInStore = $countInStores[$this->store_id] ?? 0;

            $difference = (int) $count - (int) $countInStore;

            $products_data['products_count'] += 1;

            if ($difference < 0) {
                $products_data['shortage_count'] += abs($difference);
            } elseif($difference > 0) {
                $products_data['surplus_count'] += $difference;
            }
        }

        $this->products_data = $products_data;
        $this->revisionModal = true;
    }

    public function makeDoc()
    {
        // If store id empty, return wrong
        if (empty($this->store_id) || !is_numeric($this->store_id)) {
            $this->addError('store_id', 'Выберите склад');
            return false;
        } else {
            $this->resetErrorBag('store_id');
        }

        // If revision count empty, return wrong
        foreach($this->revisionProducts as $productId => $revisionProduct) {

            if (empty($this->actualCount[$productId][$this->store_id])
                    || $this->actualCount[$productId][$this->store_id] < 0) {
                $this->addError('actualCount.'.$productId.'.'.$this->store_id, 'Wrong');
                return false;
            }
        }

        $products_data = [];
        $countInStores = [];
        $shortageTotalCount = null;
        $surplusTotalCount = null;
        $shortageTotalAmount = 0;
        $surplusTotalAmount = 0;

        // Store revision products
        foreach($this->revisionProducts as $productId => $revisionProduct) {

            $product = Product::findOrFail($productId);

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            unset($countInStores[0]);
            $countInStore = $countInStores[$this->store_id] ?? 0;

            // If count in store and revision count equal, return error
            if ($countInStore == $this->actualCount[$productId][$this->store_id]) {
                $this->addError('actualCount.'.$productId.'.'.$this->store_id, 'No difference');
                return false;
            }

            $countInStores[$this->store_id] = $this->actualCount[$productId][$this->store_id];
            $difference = $this->actualCount[$productId][$this->store_id] - $countInStore;

            $barcodes = json_decode($product->barcodes, true) ?? [];
            $barcode = (isset($barcodes[0])) ? $barcodes[0] : '';

            $products_data[$productId]['barcode'] = $barcode;
            $products_data[$productId]['purchase_price'] = $product->purchase_price;
            $products_data[$productId]['selling_price'] = $product->price;
            $products_data[$productId]['estimated_count'] = $countInStore;
            $products_data[$productId]['actualCount'] = $this->actualCount[$productId][$this->store_id];
            $products_data[$productId]['difference'] = $difference;

            if ($difference < 0) {
                $products_data[$productId]['shortage_count'] = abs($difference);
                $products_data[$productId]['shortage_sum'] = $product->purchase_price * abs($difference);
                $products_data[$productId]['surplus_count'] = null;
                $products_data[$productId]['surplus_sum'] = 0;

                $shortageTotalCount = $shortageTotalCount + abs($difference);
                $shortageTotalAmount = $shortageTotalAmount + ($product->purchase_price * abs($difference));

            } else {
                $products_data[$productId]['surplus_count'] = $difference;
                $products_data[$productId]['surplus_sum'] = $product->purchase_price * $difference;
                $products_data[$productId]['shortage_count'] = null;
                $products_data[$productId]['shortage_sum'] = 0;

                $surplusTotalCount = $surplusTotalCount + $difference;
                $surplusTotalAmount = $surplusTotalAmount + ($product->purchase_price * $difference);
            }

            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();

            $this->revisionProducts[$productId] = $product;
        }

        $docNo = $this->generateDocNo($this->store_id, $this->docNo);

        $revision = new Revision;
        $revision->store_id = $this->store_id;
        $revision->company_id = $this->company->id;
        $revision->user_id = auth()->user()->id;
        $revision->doc_no = $docNo;
        $revision->products_data = json_encode($products_data);
        $revision->surplus_count = $surplusTotalCount;
        $revision->shortage_count = $shortageTotalCount;
        $revision->surplus_sum = $surplusTotalAmount;
        $revision->shortage_sum = $shortageTotalAmount;
        $revision->currency = $this->company->currency->code;
        // $revision->comment = '';
        $revision->save();

        session()->put('revisionProducts', $this->revisionProducts);

        $revisionProduct = new RevisionProduct;
        $revisionProduct->revision_id = $revision->id;
        $revisionProduct->product_id = $product->id;
        $revisionProduct->category_id = $product->category_id;
        $revisionProduct->barcode = $barcodes;
        $revisionProduct->purchase_price = $purchase_price;
        $revisionProduct->selling_price = $selling_price;
        $revisionProduct->currency = $this->company->currency->code;

        $revisionProduct->estimated_count = $estimated_count;
        $revisionProduct->actual_count = $actual_count;
        $revisionProduct->difference = $difference;
        $revisionProduct->surplus_count = $surplus_count;
        $revisionProduct->shortage_count = $shortage_count;
        $revisionProduct->save();

        // Inventory Doc
        $docType = DocType::where('slug', 'forma-inv-6')->first();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->store_id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $revision->id;
        $storeDoc->doc_type_id = $docType->id;
        $storeDoc->products_data = json_encode($products_data);
        $storeDoc->from_contractor = $this->company->title;
        $storeDoc->to_contractor = '';
        $storeDoc->incoming_amount = $surplusTotalAmount;
        $storeDoc->outgoing_amount = $shortageTotalAmount;
        $storeDoc->sum = $amountCount;
        $storeDoc->comment = '';
        $storeDoc->save();

        // session()->forget('revisionProducts');
        // $this->revisionProducts = [];
        session()->flash('message', 'Запись изменена');
        // dd($product, $products_data, $revision, $revisionProduct);
    }

    public function addToRevision($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('revisionProducts')) {

            $revisionProducts = session()->get('revisionProducts');
            $revisionProducts[$id] = $product;

            session()->put('revisionProducts', $revisionProducts);
            $this->search = '';

            return true;
        }

        $revisionProducts[$id] = $product;

        session()->put('revisionProducts', $revisionProducts);
        $this->search = '';
    }

    public function removeFromRevision($id)
    {
        $revisionProducts = session()->get('revisionProducts');

        if (count($revisionProducts) >= 1) {
            unset($revisionProducts[$id]);
            session()->put('revisionProducts', $revisionProducts);
            return true;
        }

        session()->forget('revisionProducts');
        $this->revisionProducts = [];
    }

    public function render()
    {
        $products = (strlen($this->search) >= 2)
            ? Product::search($this->search)->get()->take(7)
            : [];

        $this->revisionProducts = session()->get('revisionProducts') ?? [];
        $this->docNo = $this->generateDocNo($this->store_id);

        return view('livewire.store.inventory', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}