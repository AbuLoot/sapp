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

class Revision extends Component
{
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $lang;
    public $units;
    public $store_id;
    public $search = '';
    public $revisionProducts = [];
    public $count = [];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->units = Unit::get();
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        if (count($parts) == 2 && $parts[0] == 'count') {

            $revisionProducts = session()->get('revisionProducts');

            if ($value == 0 || !is_numeric($value)) {
                $this->addError($key, 'Неверные данные');
                $revisionProducts[$parts[1]]['revision_count'] = 0;
                return false;
            } else {
                $this->resetErrorBag($key);
            }

            $revisionProducts[$parts[1]]['revision_count'] =- $value;
            session()->put('revisionProducts', $revisionProducts);
        }
    }

    public function makeDoc()
    {
        if (empty($this->store_id) || !is_numeric($this->store_id)) {
            $this->addError('store_id', 'Выберите склад');
            return false;
        } else {
            $this->resetErrorBag('store_id');
        }

        $products_data = [];
        $incomeAmountCount = 0;
        $incomeAmountPrice = 0;

        foreach($this->revisionProducts as $productId => $incomeProduct) {

            $product = Product::findOrFail($productId);
            $product->count += $incomeProduct['revision_count'];

            $products_data[$productId]['count'] = $incomeProduct['revision_count'];
            $products_data[$productId]['unit'] = $product->unit;
            $products_data[$productId]['title'] = $product->title;
            $products_data[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomeAmountCount = $incomeAmountCount + $incomeProduct['revision_count'];
            $incomeAmountPrice = $incomeAmountPrice + ($product->purchase_price * $incomeProduct['revision_count']);

            $product->save();
        }

        $company = auth()->user()->profile->company;
        $lastDoc = OutgoingDoc::orderBy('id')->first();
        $docType = DocType::where('slug', 'forma-z-1')->first();

        $outgoingDoc = new OutgoingDoc;
        $outgoingDoc->store_id = $company->stores->first()->id;
        $outgoingDoc->company_id = $company->id;
        $outgoingDoc->user_id = auth()->user()->id;
        $outgoingDoc->username = auth()->user()->name;
        $outgoingDoc->doc_no = $company->stores->first()->id . ($lastDoc) ? $lastDoc->id++ : 1;
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
        $storeDoc->products_data = json_encode($products_data);
        $storeDoc->from_contractor = '';
        $storeDoc->to_contractor = $company->title;
        $storeDoc->incoming_price = 0;
        $storeDoc->outgoing_price = $incomeAmountPrice;
        $storeDoc->amount = $incomeAmountCount;
        // $storeDoc->unit = $this->unit;
        $storeDoc->comment = '';
        $storeDoc->save();

        session()->forget('revisionProducts');
        $this->revisionProducts = [];
        // dd($product, $products_data, $outgoingDoc, $storeDoc);
    }

    public function addToRevision($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('revisionProducts')) {

            $revisionProducts = session()->get('revisionProducts');
            $revisionProducts[$id] = $product;
            $revisionProducts[$id]['revision_count'] = 0;

            session()->put('revisionProducts', $revisionProducts);
            $this->search = '';

            return true;
        }

        $revisionProducts[$id] = $product;
        $revisionProducts[$id]['revision_count'] = 0;

        session()->put('revisionProducts', $revisionProducts);
        $this->search = '';
    }

    public function deleteFromRevision($id)
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
        if (strlen($this->search) >= 2) {
            $products = Product::search($this->search)->orderBy('id', 'desc')->paginate(5);
        } else {
            $products = [];
        }

        $this->revisionProducts = session()->get('revisionProducts') ?? [];
        $company = auth()->user()->profile->company;

        return view('livewire.store.revision', ['products' => $products, 'company' => $company])
            ->layout('store.layout');
    }
}
