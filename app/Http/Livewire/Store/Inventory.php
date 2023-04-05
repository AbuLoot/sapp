<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Unit;
use App\Models\Store;
use App\Models\StoreDoc;
use App\Models\Revision;
use App\Models\ProductDraft;
use App\Models\DocType;
use App\Models\Product;

class Inventory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $search;
    public $company;
    public $units;
    public $docNo;
    public $storeId;
    public $storeNum;
    public $revisionProducts = [];
    public $draftProducts = [];
    public $actualCount = [];
    public $barcodesCount = [];
    public $productsData = [];
    public $revisionModal = false;

    protected $listeners = ['checkData' => '$refresh'];

    protected $rules = [
        'storeId' => 'required|numeric'
    ];

    public function mount()
    {
        if (! Gate::allows('inventory', auth()->user())) {
            abort(403);
        }

        $this->lang = app()->getLocale();
        $this->units = Unit::get();
        $this->company = auth()->user()->company;
        $this->storeId = session()->get('storage')->id;
        $this->storeNum = session()->get('storage')->num_id;
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        if (count($parts) == 3 && $parts[0] == 'actualCount') {

            if ($value < 0 || !is_numeric($value)) {
                $this->actualCount[$parts[1]][$this->storeNum] = null;
                return;
            }

            $this->actualCount[$parts[1]][$this->storeNum] = $value;
        }
    }

    public function inventoryBarcodesCount()
    {
        $this->validate();

        // If store id empty, return wrong
        if (empty($this->barcodesCount) || strlen($this->barcodesCount) < 14) {
            $this->addError('barcodesCount', 'Некорректное значение');
            return;
        } else {
            $this->resetErrorBag('barcodesCount');
        }

        $barcodesCount = explode("\n", trim($this->barcodesCount));

        $productsData = [];
        $inventoryData = [];
        $shortageTotalCount = 0;
        $surplusTotalCount = 0;
        $shortageTotalAmount = 0;
        $surplusTotalAmount = 0;

        foreach ($barcodesCount as $barcodeCount) {

            if (empty($barcodeCount) || strlen($barcodeCount) < 14) {
                continue;
            }

            list($barcode) = explode(' ', $barcodeCount);
            $count = trim(strstr($barcodeCount, ' '));

            // If count empty or too big return wrong
            if (!isset($count) || strlen($barcodeCount) > 24) {
                $this->addError('barcodesCount', 'Некорректное значение');
                return;
            }

            $product = Product::where('in_company_id', $this->company->id)->whereJsonContains('barcodes', $barcode)->first();

            $this->addToRevision($product->id, $count);

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            unset($countInStores[0]);
            $countInStore = $countInStores[$this->storeNum] ?? 0;

            $countInStores[$this->storeNum] = $this->actualCount[$product->id][$this->storeNum];
            $difference = (int) $count - (int) $countInStore;

            $productsData[$product->id]['estimatedCount'] = $countInStore;
            $productsData[$product->id]['actualCount'] = $this->actualCount[$product->id][$this->storeNum];
            $productsData[$product->id]['difference'] = $difference;

            if ($difference < 0) {
                $productsData[$product->id]['shortageCount'] = abs($difference);
                $productsData[$product->id]['surplusCount'] = 0;
                $productsData[$product->id]['shortageSum'] = $product->purchase_price * abs($difference);
                $productsData[$product->id]['surplusSum'] = 0;

                $shortageTotalCount = $shortageTotalCount + abs($difference);
                $shortageTotalAmount = $shortageTotalAmount + ($product->purchase_price * abs($difference));
            } else {
                $productsData[$product->id]['shortageCount'] = 0;
                $productsData[$product->id]['surplusCount'] = $difference;
                $productsData[$product->id]['shortageSum'] = 0;
                $productsData[$product->id]['surplusSum'] = $product->purchase_price * $difference;

                $surplusTotalCount = $surplusTotalCount + $difference;
                $surplusTotalAmount = $surplusTotalAmount + ($product->purchase_price * $difference);
            }

            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();

            $this->revisionProducts[$product->id] = $product;
        }

        $inventoryData['productsCount'] = count($productsData);
        $inventoryData['shortageTotalCount'] = $shortageTotalCount;
        $inventoryData['surplusTotalCount'] = $surplusTotalCount;
        $inventoryData['shortageTotalAmount'] = $shortageTotalAmount;
        $inventoryData['surplusTotalAmount'] = $surplusTotalAmount;

        $this->makeInventoryDocs($inventoryData, $productsData);

        $this->inventoryData = $inventoryData;
        $this->revisionModal = true;
    }

    public function inventoryListCount()
    {
        $this->validate();

        foreach($this->revisionProducts as $productId => $revisionProduct) {

            // If revision count empty, return wrong
            if (empty($this->actualCount[$productId][$this->storeNum])
                    || $this->actualCount[$productId][$this->storeNum] < 0) {
                $this->addError('actualCount.'.$productId.'.'.$this->storeNum, 'Wrong');
                return;
            }
        }

        $productsData = [];
        $inventoryData = [];
        $shortageTotalCount = 0;
        $surplusTotalCount = 0;
        $shortageTotalAmount = 0;
        $surplusTotalAmount = 0;

        // Store revision products
        foreach($this->revisionProducts as $productId => $revisionProduct) {

            $product = Product::where('in_company_id', $this->company->id)->findOrFail($productId);

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            unset($countInStores[0]);
            $countInStore = $countInStores[$this->storeNum] ?? 0;

            // If count in store and revision count equal, return error
            if ($countInStore == $this->actualCount[$productId][$this->storeNum]) {
                $this->addError('actualCount.'.$productId.'.'.$this->storeNum, 'No difference');
                return;
            }

            $countInStores[$this->storeNum] = $this->actualCount[$productId][$this->storeNum];
            $difference = $this->actualCount[$productId][$this->storeNum] - $countInStore;

            $barcodes = json_decode($product->barcodes, true) ?? [];
            $barcode = (isset($barcodes[0])) ? $barcodes[0] : '';

            $productsData[$productId]['estimatedCount'] = $countInStore;
            $productsData[$productId]['actualCount'] = $this->actualCount[$productId][$this->storeNum];
            $productsData[$productId]['difference'] = $difference;

            if ($difference < 0) {
                $productsData[$productId]['shortageCount'] = abs($difference);
                $productsData[$productId]['surplusCount'] = 0;
                $productsData[$productId]['shortageSum'] = $product->purchase_price * abs($difference);
                $productsData[$productId]['surplusSum'] = 0;

                $shortageTotalCount = $shortageTotalCount + abs($difference);
                $shortageTotalAmount = $shortageTotalAmount + ($product->purchase_price * abs($difference));
            } else {
                $productsData[$productId]['shortageCount'] = 0;
                $productsData[$productId]['surplusCount'] = $difference;
                $productsData[$productId]['shortageSum'] = 0;
                $productsData[$productId]['surplusSum'] = $product->purchase_price * $difference;

                $surplusTotalCount = $surplusTotalCount + $difference;
                $surplusTotalAmount = $surplusTotalAmount + ($product->purchase_price * $difference);
            }

            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();

            $this->revisionProducts[$productId] = $product;
        }

        $inventoryData['productsCount'] = count($productsData);
        $inventoryData['shortageTotalCount'] = $shortageTotalCount;
        $inventoryData['surplusTotalCount'] = $surplusTotalCount;
        $inventoryData['shortageTotalAmount'] = $shortageTotalAmount;
        $inventoryData['surplusTotalAmount'] = $surplusTotalAmount;

        $this->makeInventoryDocs($inventoryData, $productsData);
    }

    public function makeInventoryDocs($inventoryData, $productsData)
    {
        $store = $this->company->stores->firstWhere('id', $this->storeId);
        $docNo = $this->generateDocNo($store->num_id, $this->docNo);

        // Inventory Doc
        $docType = DocType::where('slug', 'forma-inv-10')->first();

        $revision = new Revision;
        $revision->store_id = $this->storeId;
        $revision->company_id = $this->company->id;
        $revision->user_id = auth()->user()->id;
        $revision->doc_no = $docNo;
        $revision->doc_type_id = $docType->id;
        $revision->products_data = json_encode($productsData);
        $revision->surplus_count = $inventoryData['surplusTotalCount'];
        $revision->shortage_count = $inventoryData['shortageTotalCount'];
        $revision->surplus_sum = $inventoryData['surplusTotalAmount'];
        $revision->shortage_sum = $inventoryData['shortageTotalAmount'];
        $revision->currency = $this->company->currency->code;
        // $revision->comment = '';
        $revision->save();

        session()->put('revisionProducts', $this->revisionProducts);

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->storeId;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_type = 'App\Models\Revision';
        $storeDoc->doc_id = $revision->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->contractor_type = 'App\Models\Company';
        $storeDoc->contractor_id = $this->company->id;
        $storeDoc->incoming_amount = $inventoryData['surplusTotalAmount'];
        $storeDoc->outgoing_amount = $inventoryData['shortageTotalAmount'];
        $storeDoc->count = $inventoryData['productsCount'];
        $storeDoc->sum = 0;
        // $storeDoc->comment = '';
        $storeDoc->save();

        session()->flash('message', 'Запись изменена');
    }

    public function generateDocNo($storeNum, $docNo = null)
    {
        if (is_null($docNo)) {

            $lastDoc = Revision::where('company_id', $this->company->id)->where('doc_no', 'like', $storeNum.'/%')->orderByDesc('id')->first();

            if ($lastDoc) {
                list($first, $second) = explode('/', $lastDoc->doc_no);
                $docNo = $first.'/'.$second++;
            } else {
                $docNo = $storeNum.'/1';
            }
        }

        $existDoc = Revision::where('company_id', $this->company->id)->where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            return $this->generateDocNo($storeNum, $docNo);
        }

        return $docNo;
    }

    public function saveAsDraft()
    {
        if (empty($this->revisionProducts)) {
            session()->flash('message', 'Записи не найдены');
            return;
        }

        $coincidence = 0;

        foreach($this->revisionProducts as $productId => $revisionProduct) {

            if (array_key_exists($productId, $this->draftProducts)) {
                $coincidence++;
            }

            $this->draftProducts[$productId]['title'] = $revisionProduct['title'];
            $this->draftProducts[$productId]['barcodes'] = $revisionProduct['barcodes'];
        }

        if ($coincidence == count($this->revisionProducts)) {
            session()->flash('message', 'Черновик существует');
            return;
        }

        $draftsCount = ProductDraft::where('type', 'revision')->count();

        $draft = new ProductDraft;
        $draft->company_id = $this->company->id;
        $draft->user_id = auth()->user()->id;
        $draft->type = 'revision';
        $draft->title = 'Revision '.($draftsCount + 1);
        $draft->products_data = json_encode($this->draftProducts);
        $draft->count = count($this->draftProducts);
        $draft->comment = null;
        $draft->save();

        session()->flash('message', 'Запись добавлена');
    }

    public function addToRevision($id, $count = null)
    {
        $product = Product::where('in_company_id', $this->company->id)->findOrFail($id);

        if (session()->has('revisionProducts')) {
            $revisionProducts = session()->get('revisionProducts');
        }

        $revisionProducts[$id] = $product;
        $this->actualCount[$id][$this->storeNum] = $count;

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

    public function removeRevision()
    {
        session()->forget('revisionProducts');
    }

    public function render()
    {
        $products = (strlen($this->search) > 1)
            ? Product::search($this->search)->where('in_company_id', $this->company->id)->get()->take(7)
            : [];

        $this->revisionProducts = session()->get('revisionProducts') ?? [];
        $store = $this->company->stores->firstWhere('id', $this->storeId);
        $this->docNo = $this->generateDocNo($store->num_id);

        return view('livewire.store.inventory', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}