<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Unit;
use App\Models\Store;
use App\Models\StoreDoc;
use App\Models\OutgoingDoc;
use App\Models\DocType;
use App\Models\Product;

use App\Traits\GenerateDocNo;

class Writeoff extends Component
{
    use GenerateDocNo;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $units;
    public $search;
    public $company;
    public $storeId;
    public $storeNum;
    public $writeoffProducts = [];
    public $writeoffCounts = [];
    public $comment;

    protected $rules = [
        'writeoffCounts.*.*' => 'required|numeric',
        'comment' => 'required',
    ];

    public function mount()
    {
        if (! Gate::allows('writeoff', auth()->user())) {
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

        // Validating Writeoff Counts
        if (count($parts) == 3 && $parts[0] == 'writeoffCounts') {

            $product = Product::where('in_company_id', $this->company->id)->findOrFail($parts[1]);
            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$this->storeNum] ?? 0;

            if ($value <= 0 || !is_numeric($value)) {
                $this->writeoffCounts[$parts[1]][$this->storeNum] = null;
                return;
            }

            $this->writeoffCounts[$parts[1]][$this->storeNum] = ($countInStore < $value) ? $countInStore : $value;
        }
    }

    public function makeDoc()
    {
        // $this->validate();

        foreach($this->writeoffProducts as $productId => $writeoffProduct) {

            // If incoming count empty, return wrong
            if (empty($this->writeoffCounts[$productId][$this->storeNum])
                    || $this->writeoffCounts[$productId][$this->storeNum] < 0) {
                $this->addError('writeoffCounts.'.$productId.'.'.$this->storeNum, 'Wrong');
                return;
            }
        }

        $productsData = [];
        $countInStores = [];
        $writeoffTotalCount = 0;
        $writeoffTotalAmount = 0;

        $this->writeoffProducts = session()->get('writeoffProducts') ?? [];

        foreach($this->writeoffProducts as $productId => $writeoffProduct) {

            $product = Product::where('in_company_id', $this->company->id)->findOrFail($productId);

            $countInStores = json_decode($writeoffProduct->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$this->storeNum] ?? 0;
            $writeoffCount = $this->writeoffCounts[$productId][$this->storeNum];

            unset($this->writeoffCounts[$productId][$this->storeNum]);

            $stockCount = $countInStore - $writeoffCount;

            $productsData[$productId]['price'] = $product->purchase_price;
            $productsData[$productId]['outgoingCount'] = $writeoffCount;
            $productsData[$productId]['count'] = $stockCount;
            $productsData[$productId]['unit'] = $product->unit;
            $productsData[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $writeoffTotalCount = $writeoffTotalCount + $writeoffCount;
            $writeoffTotalAmount = $writeoffTotalAmount + ($product->purchase_price * $writeoffCount);

            $countInStores[$this->storeNum] = $stockCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();

            $this->writeoffProducts[$productId]['countInStores'] = json_encode($countInStores);
            $this->writeoffProducts[$productId]['count'] = $amountCount;
        }

        // Writeoff Doc
        $docType = DocType::where('slug', 'forma-z-6')->first();

        $docNo = $this->generateOutgoingStoreDocNo($this->storeNum);

        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = $this->storeId;
        $outgoingDoc->company_id = $this->company->id;
        $outgoingDoc->workplace_id = session()->get('storageWorkplace');
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->doc_no = $docNo;
        $outgoingDoc->doc_type_id = $docType->id;
        $outgoingDoc->products_data = json_encode($productsData);
        $outgoingDoc->operation_code = 'writeoff-products';
        $outgoingDoc->sum = $writeoffTotalAmount;
        $outgoingDoc->currency = $this->company->currency->code;
        $outgoingDoc->count = $writeoffTotalCount;
        $outgoingDoc->comment = $this->comment;
        $outgoingDoc->save();

        session()->put('writeoffProducts', $this->writeoffProducts);

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->storeId;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_type = 'App\Models\OutgoingDoc';
        $storeDoc->doc_id = $outgoingDoc->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = $writeoffTotalAmount;
        $storeDoc->sum = $writeoffTotalAmount;
        $storeDoc->count = $writeoffTotalCount;
        $storeDoc->comment = $this->comment;
        $storeDoc->save();

        $this->comment = null;

        session()->flash('message', 'Запись изменена');
    }

    public function addToWriteoff($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('writeoffProducts')) {
            $writeoffProducts = session()->get('writeoffProducts');
        }

        $writeoffProducts[$id] = $product;
        $this->writeoffCounts[$id][$this->storeNum] = null;

        session()->put('writeoffProducts', $writeoffProducts);
        $this->search = '';
    }

    public function removeFromWriteoff($id)
    {
        $writeoffProducts = session()->get('writeoffProducts');

        if (count($writeoffProducts) == 0) {
            session()->forget('writeoffProducts');
        }

        unset($writeoffProducts[$id]);
        session()->put('writeoffProducts', $writeoffProducts);
    }

    public function removeWriteoff()
    {
        session()->forget('writeoffProducts');
    }

    public function render()
    {
        $products = [];

        if (strlen($this->search) >= 2) {
            $products = Product::search($this->search)
                ->where('in_company_id', $this->company->id)
                ->orderBy('id', 'desc')
                ->paginate(5);
        }

        $this->writeoffProducts = session()->get('writeoffProducts') ?? [];

        return view('livewire.store.writeoff', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}
