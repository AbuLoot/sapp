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

class Writeoff extends Component
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
    public $writeoff_count = [];

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

        if (count($parts) == 3 && $parts[0] == 'writeoff_count') {

            $writeoffProducts = session()->get('writeoffProducts');

            if (empty($value) || !is_numeric($value)) {
                $writeoffProducts[$parts[1]]['writeoff_count'] = [$this->store_id => null];
                $this->writeoff_count[$parts[1]][$this->store_id] = null;
                session()->put('writeoffProducts', $writeoffProducts);
                return false;
            }

            $writeoffProducts[$parts[1]]['writeoff_count'] = [$this->store_id => $value];
            $this->writeoff_count[$parts[1]][$this->store_id] = $value;
            session()->put('writeoffProducts', $writeoffProducts);
        }
    }

    public function makeDoc()
    {
        if (empty($this->comment)) {
            $this->addError('comment', 'Напишите комментарий.');
            return false;
        } else {
            $this->resetErrorBag('comment');
        }

        $products_data = [];
        $countInStores = [];
        $writeoffAmountCount = 0;
        $writeoffAmountPrice = 0;

        // $this->writeoffProducts[962]['count_in_stores'] = json_encode(['1' => 5]);
        // $this->writeoffProducts[963]['count_in_stores'] = json_encode(['1' => 5, '2' => 6]);

        $this->writeoffProducts = session()->get('writeoffProducts') ?? [];

        foreach($this->writeoffProducts as $productId => $writeoffProduct) {

            $product = Product::findOrFail($productId);

            $countInStores = json_decode($writeoffProduct->count_in_stores, true) ?? [];

            if (isset($countInStores[$this->store_id])) {
                $countInStore = $countInStores[$this->store_id];
            } else {
                session()->flash('message', 'Произошла ошибка.');
                return false;
            }

            $writeoffCountProduct = 0;

            if (isset($this->writeoff_count[$productId][$this->store_id])) {
                if ($countInStore >= 1 && $this->writeoff_count[$productId][$this->store_id] <= $countInStore) {
                    $writeoffCountProduct = $this->writeoff_count[$productId][$this->store_id];
                } elseif ($countInStore < $this->writeoff_count[$productId][$this->store_id]) {
                    $writeoffCountProduct = $countInStore;
                }
            }

            $finalCount = ($product->count <= $writeoffCountProduct)
                ? 0
                : $countInStore - $writeoffCountProduct;

            $products_data[$productId]['outgoing_count'] = $writeoffCountProduct;
            $products_data[$productId]['count'] = $finalCount;
            $products_data[$productId]['unit'] = $product->unit;
            $products_data[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $writeoffAmountCount = $writeoffAmountCount + $writeoffCountProduct;
            $writeoffAmountPrice = $writeoffAmountPrice + ($product->purchase_price * $writeoffCountProduct);

            $countInStores[$this->store_id] = $finalCount;

            $product->count_in_stores = json_encode($countInStores);
            $product->count = $finalCount;
            $product->save();
        }

        $company = auth()->user()->profile->company;
        $lastDoc = OutgoingDoc::orderByDesc('id')->first();
        $docNo = $this->store_id . '/' . $lastDoc->id++;
        $existDoc = OutgoingDoc::where('doc_no', $docNo)->first();

        if ($existDoc) {
            $docNo = $this->store_id . '/' . $lastDoc->id + 2;
        }

        // Writeoff Doc
        $docType = DocType::where('slug', 'forma-z-6')->first();

        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = $this->store_id;
        $outgoingDoc->company_id = $company->id;
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->username = auth()->user()->name;
        $outgoingDoc->doc_no = $docNo;
        $outgoingDoc->doc_type_id = $docType->id;
        $outgoingDoc->products_data = json_encode($products_data);
        $outgoingDoc->to_contractor = '';
        $outgoingDoc->sum = $outgoingDoc->sum - $writeoffAmountPrice;
        $outgoingDoc->currency = $company->currency->code;
        $outgoingDoc->count = $writeoffAmountCount;
        // $outgoingDoc->unit = $this->unit;
        $outgoingDoc->comment = $this->comment;
        $outgoingDoc->save();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->store_id;
        $storeDoc->company_id = $company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $outgoingDoc->id;
        $storeDoc->doc_type_id = $docType->id;
        $storeDoc->products_data = json_encode($products_data);
        $storeDoc->from_contractor = $company->title;
        $storeDoc->to_contractor = '';
        $storeDoc->incoming_price = 0;
        $storeDoc->outgoing_price = $writeoffAmountPrice;
        $storeDoc->amount = $writeoffAmountCount;
        // $storeDoc->unit = $this->unit;
        $storeDoc->comment = $this->comment;
        $storeDoc->save();

        session()->forget('writeoffProducts');
        $this->writeoffProducts = [];

        session()->flash('message', 'Запись изменена.');
    }

    public function addToWriteoff($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('writeoffProducts')) {

            $writeoffProducts = session()->get('writeoffProducts');
            $writeoffProducts[$id] = $product;
            $writeoffProducts[$id]['writeoff_count'] = [$this->store_id => 0];

            session()->put('writeoffProducts', $writeoffProducts);
            $this->search = '';

            return true;
        }

        $writeoffProducts[$id] = $product;
        $writeoffProducts[$id]['writeoff_count'] = [$this->store_id => 0];

        session()->put('writeoffProducts', $writeoffProducts);
        $this->search = '';
    }

    public function deleteFromWriteoff($id)
    {
        $writeoffProducts = session()->get('writeoffProducts');

        if (count($writeoffProducts) >= 1) {
            unset($writeoffProducts[$id]);
            unset($this->writeoff_count[$id]);
            session()->put('writeoffProducts', $writeoffProducts);
            return true;
        }

        session()->forget('writeoffProducts');
        $this->writeoffProducts = [];
        $this->writeoff_count = [];
    }

    public function render()
    {
        if (strlen($this->search) >= 2) {
            $products = Product::search($this->search)->orderBy('id', 'desc')->paginate(5);
        } else {
            $products = [];
        }

        $this->writeoffProducts = session()->get('writeoffProducts') ?? [];

        return view('livewire.store.writeoff', ['products' => $products])
            ->layout('store.layout');
    }
}
