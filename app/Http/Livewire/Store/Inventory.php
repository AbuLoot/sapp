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
    public $store_id;
    public $search = '';
    public $revisionProducts = [];
    public $actualCount = [];
    public $barcodeAndCount = [];

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

            if (empty($value) || !is_numeric($value)) {
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

    public function makeDoc()
    {
        if (empty($this->store_id) || !is_numeric($this->store_id)) {
            $this->addError('store_id', 'Выберите склад');
            return false;
        } else {
            $this->resetErrorBag('store_id');
        }

        $products_data = [];
        $countInStores = [];
        $revisionAmountCount = 0;
        $revisionAmountPrice = 0;

        foreach($this->revisionProducts as $productId => $revisionProduct) {

            $product = Product::findOrFail($productId);

            if (is_object($product->count_in_stores)) {
                $countInStores = json_decode($product->count_in_stores, true) ?? [];
            }

            // $countInStores[$this->store_id] = $this->actualCount[$productId][$this->store_id];
            $countInStore = $countInStores[$this->store_id];

            if ($companyStore > $this->actualCount[$productId][$this->store_id]) {
                $minus = $countInStore - $this->actualCount[$productId][$this->store_id];
            } else {
                $plus = $this->actualCount[$productId][$this->store_id] - $countInStore;
            }

            $products_data[$productId]['count'] = $revisionProduct['revision_count'];
            $products_data[$productId]['unit'] = $product->unit;
            $products_data[$productId]['title'] = $product->title;
            $products_data[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomeAmountCount = $incomeAmountCount + $revisionProduct['revision_count'];
            $incomeAmountPrice = $incomeAmountPrice + ($product->purchase_price * $revisionProduct['revision_count']);

            $product->count += $revisionProduct['revision_count'];
            // $product->save();
        }

        $lastDoc = Revision::orderByDesc('id')->first();
        $docNo = $this->store_id . '/' . $lastDoc->id++;
        $existDoc = Revision::where('doc_no', $docNo)->first();

        if ($existDoc) {
            $docNo = $this->store_id . '/' . $lastDoc->id + 2;
        }

        $revision = new Revision;
        $revision->store_id = $this->company->stores->first()->id;
        $revision->company_id = $this->company->id;
        $revision->user_id = auth()->user()->id;
        $revision->doc_no = $docNo;
        $revision->products_ids = json_encode($products_ids);
        $revision->products_count = $products_count;
        $revision->sum = $incomeAmountPrice;
        $revision->title = $title;
        $revision->actual_count = $actual_count;
        $revision->difference = $difference;
        $revision->surplus_sum = $surplus_sum;
        $revision->shortage_sum = $shortage_sum;
        $revision->currency = $this->company->currency->code;
        $revision->comment = $comment;
        $revision->save();

        $revisionProduct = new RevisionProduct;
        $revisionProduct->revision_id = $revision->id;
        $revisionProduct->product_id = $product->id;
        $revisionProduct->category_id = $product->category_id;
        $revisionProduct->doc_id = $revision->id;
        $revisionProduct->doc_type_id = $docType->id;
        $revisionProduct->products_data = json_encode($products_data);
        $revisionProduct->purchase_price = $purchase_price;
        $revisionProduct->selling_price = $selling_price;
        $revisionProduct->estimated_count = $estimated_count;
        $revisionProduct->actual_count = $actual_count;
        $revisionProduct->difference = $difference;
        // $revisionProduct->unit = $this->unit;
        $revisionProduct->comment = '';
        $revisionProduct->save();

        session()->forget('revisionProducts');
        $this->revisionProducts = [];
        // dd($product, $products_data, $revision, $revisionProduct);
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

        return view('livewire.store.inventory', ['products' => $products])
            ->layout('store.layout');
    }
}
