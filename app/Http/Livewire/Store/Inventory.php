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

    public $lang;
    public $units;
    public $docNo;
    public $storeId;
    public $search = '';
    public $revisionProducts = [];
    public $actualCount = [];
    public $barcodesCount = [];
    public $productsData = [];
    public $revisionModal = false;

    protected $listeners = ['checkData' => '$refresh'];

    protected $rules = [
        'storeId' => 'required|numeric',
        'actualCount.*.*' => 'required|numeric',
    ];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->units = Unit::get();
        $this->company = auth()->user()->profile->company;
        $this->storeId = $this->company->first()->id;
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        if (count($parts) == 3 && $parts[0] == 'actualCount') {

            $revisionProducts = session()->get('revisionProducts');

            if (!is_numeric($value) || $value < 0) {
                $revisionProducts[$parts[1]]['actualCount'] = [$this->storeId => null];
                $this->actualCount[$parts[1]][$this->storeId] = null;
                session()->put('revisionProducts', $revisionProducts);
                return false;
            }

            $revisionProducts[$parts[1]]['actualCount'] = [$this->storeId => $value];
            $this->actualCount[$parts[1]][$this->storeId] = $value;
            session()->put('revisionProducts', $revisionProducts);
        }
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

        $productsData['products_count'] = 0;
        $productsData['shortage_count'] = 0;
        $productsData['surplus_count'] = 0;

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
            $countInStore = $countInStores[$this->storeId] ?? 0;

            $difference = (int) $count - (int) $countInStore;

            $productsData['products_count'] += 1;

            if ($difference < 0) {
                $productsData['shortage_count'] += abs($difference);
            } elseif($difference > 0) {
                $productsData['surplus_count'] += $difference;
            }
        }

        $this->productsData = $productsData;
        $this->revisionModal = true;
    }

    public function makeDoc()
    {
        // If store id empty, return wrong
        if (empty($this->storeId) || !is_numeric($this->storeId)) {
            $this->addError('storeId', 'Выберите склад');
            return false;
        } else {
            $this->resetErrorBag('storeId');
        }

        // If revision count empty, return wrong
        foreach($this->revisionProducts as $productId => $revisionProduct) {

            if (empty($this->actualCount[$productId][$this->storeId])
                    || $this->actualCount[$productId][$this->storeId] < 0) {
                $this->addError('actualCount.'.$productId.'.'.$this->storeId, 'Wrong');
                return false;
            }
        }

        $productsData = [];
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
            $countInStore = $countInStores[$this->storeId] ?? 0;

            // If count in store and revision count equal, return error
            if ($countInStore == $this->actualCount[$productId][$this->storeId]) {
                $this->addError('actualCount.'.$productId.'.'.$this->storeId, 'No difference');
                return false;
            }

            $countInStores[$this->storeId] = $this->actualCount[$productId][$this->storeId];
            $difference = $this->actualCount[$productId][$this->storeId] - $countInStore;

            $barcodes = json_decode($product->barcodes, true) ?? [];
            $barcode = (isset($barcodes[0])) ? $barcodes[0] : '';

            $productsData[$productId]['barcode'] = $barcode;
            $productsData[$productId]['purchase_price'] = $product->purchase_price;
            $productsData[$productId]['selling_price'] = $product->price;
            $productsData[$productId]['estimated_count'] = $countInStore;
            $productsData[$productId]['actualCount'] = $this->actualCount[$productId][$this->storeId];
            $productsData[$productId]['difference'] = $difference;

            if ($difference < 0) {
                $productsData[$productId]['shortage_count'] = abs($difference);
                $productsData[$productId]['shortage_sum'] = $product->purchase_price * abs($difference);
                $productsData[$productId]['surplus_count'] = null;
                $productsData[$productId]['surplus_sum'] = 0;

                $shortageTotalCount = $shortageTotalCount + abs($difference);
                $shortageTotalAmount = $shortageTotalAmount + ($product->purchase_price * abs($difference));

            } else {
                $productsData[$productId]['surplus_count'] = $difference;
                $productsData[$productId]['surplus_sum'] = $product->purchase_price * $difference;
                $productsData[$productId]['shortage_count'] = null;
                $productsData[$productId]['shortage_sum'] = 0;

                $surplusTotalCount = $surplusTotalCount + $difference;
                $surplusTotalAmount = $surplusTotalAmount + ($product->purchase_price * $difference);
            }

            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();

            $this->revisionProducts[$productId] = $product;
        }

        $docNo = $this->generateDocNo($this->storeId, $this->docNo);

        $revision = new Revision;
        $revision->store_id = $this->storeId;
        $revision->company_id = $this->company->id;
        $revision->user_id = auth()->user()->id;
        $revision->doc_no = $docNo;
        $revision->products_data = json_encode($productsData);
        $revision->surplus_count = $surplusTotalCount;
        $revision->shortage_count = $shortageTotalCount;
        $revision->surplus_sum = $surplusTotalAmount;
        $revision->shortage_sum = $shortageTotalAmount;
        $revision->currency = $this->company->currency->code;
        // $revision->comment = '';
        $revision->save();

        session()->put('revisionProducts', $this->revisionProducts);

        // $revisionProduct = new RevisionProduct;
        // $revisionProduct->revision_id = $revision->id;
        // $revisionProduct->product_id = $product->id;
        // $revisionProduct->category_id = $product->category_id;
        // $revisionProduct->barcode = $barcodes;
        // $revisionProduct->purchase_price = $purchase_price;
        // $revisionProduct->selling_price = $selling_price;
        // $revisionProduct->currency = $this->company->currency->code;

        // $revisionProduct->estimated_count = $estimated_count;
        // $revisionProduct->actual_count = $actual_count;
        // $revisionProduct->difference = $difference;
        // $revisionProduct->surplus_count = $surplus_count;
        // $revisionProduct->shortage_count = $shortage_count;
        // $revisionProduct->save();

        // Inventory Doc
        $docType = DocType::where('slug', 'forma-inv-10')->first();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->storeId;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $revision->id;
        $storeDoc->doc_type_id = $docType->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->from_contractor = $this->company->title;
        $storeDoc->to_contractor = '';
        $storeDoc->incoming_amount = $surplusTotalAmount;
        $storeDoc->outgoing_amount = $shortageTotalAmount;
        $storeDoc->sum = $amountCount;
        $storeDoc->comment = '';
        $storeDoc->save();

        session()->flash('message', 'Запись изменена');
    }

    public function generateDocNo($storeId, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = Revision::where('doc_no', 'like', $storeId.'/%')->orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } else {
                $docNo = $storeId.'/1';
            }
        }

        $existDoc = Revision::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return $this->generateDocNo($storeId, $docNo);
        }

        return $docNo;
    }

    public function addToRevision($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('revisionProducts')) {
            $revisionProducts = session()->get('revisionProducts');
        }

        $revisionProducts[$id] = $product;
        $this->actualCounts[$id][$this->storeId] = null;

        session()->put('revisionProducts', $revisionProducts);
        $this->search = '';
    }

    public function removeFromRevision($id)
    {
        $revisionProducts = session()->get('revisionProducts');

        if (count($revisionProducts) == 0) {
            session()->forget('revisionProducts');
        }

        unset($revisionProducts[$id]);
        session()->put('revisionProducts', $revisionProducts);
    }

    public function render()
    {
        $products = (strlen($this->search) >= 2)
            ? Product::search($this->search)->get()->take(7)
            : [];

        $this->revisionProducts = session()->get('revisionProducts') ?? [];
        $this->docNo = $this->generateDocNo($this->storeId);

        return view('livewire.store.inventory', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}