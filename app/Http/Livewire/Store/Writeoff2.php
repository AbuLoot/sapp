<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Unit;
use App\Models\Store;
use App\Models\StoreDoc;
use App\Models\OutgoingDoc;
use App\Models\DocType;
use App\Models\Product;

class Writeoff2 extends Component
{
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $lang;
    public $units;
    public $company;
    public $store_id;
    public $search = '';
    public $writeoffProducts = [];
    public $comment;
    public $writeoffCount = [];

    protected $rules = [
        'writeoffCount.*.*' => 'required|numeric',
    ];

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

        if (count($parts) == 3 && $parts[0] == 'writeoffCount') {

            dd($parts);

            // $this->setValidCount($parts[1], $value);

            // $writeoffProducts = session()->get('writeoffProducts');

            // if ($value <= 0 || !is_numeric($value)) {
            //     $writeoffProducts[$parts[1]][$this->store_id] = 0;
            // }

            // $writeoffProducts[$parts[1]][$this->store_id] = $value;
            // session()->put('writeoffProducts', $writeoffProducts);
        }
    }

    public function setValidCount($product_id, $value)
    {
        $writeoffProducts = session()->get('writeoffProducts');
        $countInStores = json_decode($writeoffProducts[$product_id]->count_in_stores, true) ?? [];
        $countInStore = $countInStores[$this->store_id] ?? 0;

        if ($value <= 0 || !is_numeric($value)) {
            $validCount = 0;
        } else {
            $validCount = ($countInStore <= $value)
                ? $countInStore
                : $value;
        }

        $writeoffProducts[$product_id][$this->store_id] = $validCount;
        session()->put('writeoffProducts', $writeoffProducts);
    }

    public function generateDocNo($store_id, $docNo = null)
    {
        $lastDoc = OutgoingDoc::where('doc_no', 'like', $store_id.'/_')->orderByDesc('id')->first();

        if ($lastDoc && is_null($docNo)) {
            list($first, $second) = explode('/', $lastDoc->doc_no);
            $docNo = $first.'/'.++$second;
        } elseif (is_null($docNo)) {
            $docNo = $store_id.'/1';
        }

        $existDoc = OutgoingDoc::where('doc_no', $docNo)->first();

        if ($existDoc) {
            list($first, $second) = explode('/', $docNo);
            $docNo = $first.'/'.++$second;
            $this->generateDocNo($store_id, $docNo);
        }

        return $docNo;
    }

    public function makeDoc()
    {
        if (empty($this->comment)) {
            $this->addError('comment', 'Напишите комментарий');
            return false;
        } else {
            $this->resetErrorBag('comment');
        }

        $products_data = [];
        $countInStores = [];
        $writeoffTotalCount = 0;
        $writeoffTotalAmount = 0;

        $this->writeoffProducts = session()->get('writeoffProducts') ?? [];

        foreach($this->writeoffProducts as $productId => $writeoffProduct) {

            $product = Product::findOrFail($productId);

            $countInStores = json_decode($writeoffProduct->count_in_stores, true) ?? [];

            /*
             * If writeoff count or count in store empty, return wrong
             */
            if (empty($writeoffProduct[$this->store_id])
                    || $writeoffProduct[$this->store_id] <= 0
                    || empty($countInStores[$this->store_id])) {
                $this->addError('writeoffProducts.'.$productId.'.'.$this->store_id, 'Wrong');
                continue;
            }

            $countInStore = $countInStores[$this->store_id] ?? 0;
            $writeoffCount = 0;

            /**
             * Prepare writeoff count & If writeoff count greater, assign $countInStore
             */
            if ($countInStore >= 1 && $writeoffProduct[$this->store_id] <= $countInStore) {
                $writeoffCount = $writeoffProduct[$this->store_id];
            } elseif ($countInStore < $writeoffProduct[$this->store_id]) {
                $writeoffCount = $countInStore;
            }

            unset($this->writeoffProducts[$productId][$this->store_id]);

            $finalCount = $countInStore - $writeoffCount;

            $products_data[$productId]['outgoing_count'] = $writeoffCount;
            $products_data[$productId]['count'] = $finalCount;
            $products_data[$productId]['unit'] = $product->unit;
            $products_data[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $writeoffTotalCount = $writeoffTotalCount + $writeoffCount;
            $writeoffTotalAmount = $writeoffTotalAmount + ($product->purchase_price * $writeoffCount);

            $countInStores[$this->store_id] = $finalCount;
            $amountCount = collect($countInStores)->sum();

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $amountCount;
            $product->save();

            $this->writeoffProducts[$productId]['count_in_stores'] = json_encode($countInStores);
            $this->writeoffProducts[$productId]['count'] = $amountCount;
        }

        // Writeoff Doc
        $docType = DocType::where('slug', 'forma-z-6')->first();

        $docNo = $this->generateDocNo($this->store_id);

        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = $this->store_id;
        $outgoingDoc->company_id = $this->company->id;
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->username = auth()->user()->name;
        $outgoingDoc->doc_no = $docNo;
        $outgoingDoc->doc_type_id = $docType->id;
        $outgoingDoc->products_data = json_encode($products_data);
        $outgoingDoc->to_contractor = '';
        $outgoingDoc->sum = $outgoingDoc->sum - $writeoffTotalAmount;
        $outgoingDoc->currency = $this->company->currency->code;
        $outgoingDoc->count = $writeoffTotalCount;
        // $outgoingDoc->unit = $this->unit;
        $outgoingDoc->comment = $this->comment;
        $outgoingDoc->save();

        session()->put('writeoffProducts', $this->writeoffProducts);

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->store_id;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $outgoingDoc->id;
        $storeDoc->doc_type_id = $docType->id;
        $storeDoc->products_data = json_encode($products_data);
        $storeDoc->from_contractor = $this->company->title;
        $storeDoc->to_contractor = '';
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = $writeoffTotalAmount;
        $storeDoc->sum = $writeoffTotalAmount;
        // $storeDoc->unit = $this->unit;
        $storeDoc->comment = $this->comment;
        $storeDoc->save();

        // session()->forget('writeoffProducts');
        // $this->writeoffProducts = [];
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
        $writeoffProducts[$id][$this->store_id] = 0;

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

    public function render()
    {
        $products = [];

        if (strlen($this->search) >= 2) {
            $products = Product::search($this->search)->orderBy('id', 'desc')->paginate(5);
        }

        $this->writeoffProducts = session()->get('writeoffProducts') ?? [];

        return view('livewire.store.writeoff', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}