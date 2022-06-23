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
    public $count = [];

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

        if (count($parts) == 2 && $parts[0] == 'count') {

            $writeoffProducts = session()->get('writeoffProducts');

            if ($value == 0 || !is_numeric($value)) {
                $this->addError($key, 'Неверные данные');
                $writeoffProducts[$parts[1]]['writeoff_count'] = 0;
                return false;
            } else {
                $this->resetErrorBag($key);
            }

            $writeoffProducts[$parts[1]]['writeoff_count'] =- $value;
            session()->put('writeoffProducts', $writeoffProducts);
        }
    }

    public function makeDoc()
    {
        if (empty($this->comment) || !is_numeric($this->comment)) {
            $this->addError('comment', 'Напишите комментарий.');
            return false;
        } else {
            $this->resetErrorBag('comment');
        }

        $products_data = [];
        $count_in_stores = [];
        $incomeAmountCount = 0;
        $incomeAmountPrice = 0;

        foreach($this->writeoffProducts as $productId => $incomeProduct) {

            $product = Product::findOrFail($productId);

            $products_data[$productId]['count'] = $incomeProduct['writeoff_count'];
            $products_data[$productId]['unit'] = $product->unit;
            $products_data[$productId]['title'] = $product->title;
            $products_data[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomeAmountCount = $incomeAmountCount + $incomeProduct['writeoff_count'];
            $incomeAmountPrice = $incomeAmountPrice + ($product->purchase_price * $incomeProduct['writeoff_count']);

            if (is_object(json_decode($product->count_in_stores))) {
                $count_in_stores = json_decode($product->count_in_stores, true);
                $count_in_stores[$this->store_id] -= $incomeProduct['writeoff_count'];
            }
            else {
                $count_in_stores[$this->store_id] = $incomeProduct['writeoff_count'];
            }

            $product->count_in_stores = json_encode($count_in_stores);
            $product->count += $incomeProduct['writeoff_count'];
            $product->save();
        }

        $company = auth()->user()->profile->company;
        $docCount = OutgoingDoc::count();
        $docType = DocType::where('slug', 'forma-z-6')->first();

        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = $company->stores->first()->id;
        $outgoingDoc->company_id = $company->id;
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->username = auth()->user()->name;
        $outgoingDoc->doc_no = $company->stores->first()->id . $docCount++;
        $outgoingDoc->doc_type_id = $docType->id;
        $outgoingDoc->products_data = json_encode($products_data);
        $outgoingDoc->from_contractor = '';
        $outgoingDoc->sum = $incomeAmountPrice;
        $outgoingDoc->currency = $company->currency->code;
        $outgoingDoc->count = $incomeAmountCount;
        // $outgoingDoc->unit = $this->unit;
        $outgoingDoc->comment = '';
        $outgoingDoc->save();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $company->stores->first()->id;
        $storeDoc->company_id = $company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $outgoingDoc->id;
        $storeDoc->doc_type_id = $docType->id;
        $storeDoc->title = $docType->title;
        $storeDoc->products_data = json_encode($products_data);
        $storeDoc->from_contractor = '';
        $storeDoc->to_contractor = $company->title;
        $storeDoc->incoming_price = 0;
        $storeDoc->outgoing_price = $incomeAmountPrice;
        $storeDoc->amount = $incomeAmountCount;
        // $storeDoc->unit = $this->unit;
        $storeDoc->comment = '';
        $storeDoc->save();

        session()->forget('writeoffProducts');
        $this->writeoffProducts = [];
        // dd($product, $products_data, $outgoingDoc, $storeDoc);
    }

    public function addToWriteoff($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('writeoffProducts')) {

            $writeoffProducts = session()->get('writeoffProducts');
            $writeoffProducts[$id] = $product;
            $writeoffProducts[$id]['writeoff_count'] = 0;

            session()->put('writeoffProducts', $writeoffProducts);
            $this->search = '';

            return true;
        }

        $writeoffProducts[$id] = $product;
        $writeoffProducts[$id]['writeoff_count'] = 0;

        session()->put('writeoffProducts', $writeoffProducts);
        $this->search = '';
    }

    public function deleteFromWriteoff($id)
    {
        $writeoffProducts = session()->get('writeoffProducts');

        if (count($writeoffProducts) >= 1) {
            unset($writeoffProducts[$id]);
            session()->put('writeoffProducts', $writeoffProducts);
            return true;
        }

        session()->forget('writeoffProducts');
        $this->writeoffProducts = [];
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
