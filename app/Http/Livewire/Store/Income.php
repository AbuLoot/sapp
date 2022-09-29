<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Unit;
use App\Models\Store;
use App\Models\StoreDoc;
use App\Models\IncomingDoc;
use App\Models\DocType;
use App\Models\Product;
use App\Models\ProductDraft;

use App\Traits\GenerateDocNo;

class Income extends Component
{
    use GenerateDocNo, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $units;
    public $storeId;
    public $search;
    public $incomeProducts = [];
    public $incomeProductsCount = [];
    public $draftProducts = [];

    protected $rules = [
        'storeId' => 'required|numeric',
        'incomeProductsCount.*.*' => 'required|numeric',
    ];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->units = Unit::get();
        $this->company = auth()->user()->profile->company;
        $this->storeId = session()->get('storage')->id;
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        if (count($parts) == 3 && $parts[0] == 'incomeProductsCount') {

            $validValue = ($value <= 0 || !is_numeric($value)) ? null : $value;

            $this->incomeProductsCount[$parts[1]][$this->storeId] = $validValue;
        }
    }

    public function makeDoc()
    {
        $this->validate();

        foreach($this->incomeProducts as $productId => $incomeProduct) {

            // If incoming count empty, return wrong
            if (empty($this->incomeProductsCount[$productId][$this->storeId])
                    || $this->incomeProductsCount[$productId][$this->storeId] < 0) {
                $this->addError('incomeProductsCount.'.$productId.'.'.$this->storeId, 'Wrong');
                return;
            }
        }

        $productsData = [];
        $countInStores = [];
        $incomeTotalCount = 0;
        $incomeTotalAmount = 0;
        $workplaceId = session()->get('storageWorkplace');

        foreach($this->incomeProducts as $productId => $incomeProduct) {

            $incomeCount = $this->incomeProductsCount[$productId][$this->storeId];

            $product = Product::findOrFail($productId);

            $productsData[$productId]['purchase_price'] = $product->purchase_price;
            $productsData[$productId]['count'] = $incomeCount;
            $productsData[$productId]['unit'] = $product->unit;
            $productsData[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomeTotalCount = $incomeTotalCount + $incomeCount;
            $incomeTotalAmount = $incomeTotalAmount + ($product->purchase_price * $incomeCount);

            $countInStores = json_decode($product->count_in_stores, true) ?? [''];
            $countInStores[$this->storeId] = $incomeCount;

            $product->count_in_stores = json_encode($countInStores);
            $product->count += $incomeCount;
            $product->save();
        }

        // Incoming Doc
        $docType = DocType::where('slug', 'forma-z-1')->first();
        $docNo = $this->generateIncomingStoreDocNo($this->storeId);

        $incomingDoc = new IncomingDoc;
        $incomingDoc->store_id = $this->storeId;
        $incomingDoc->company_id = $this->company->id;
        $incomingDoc->workplace_id = $workplaceId;
        $incomingDoc->user_id = auth()->user()->id;
        $incomingDoc->doc_no = $docNo;
        $incomingDoc->doc_type_id = $docType->id;
        $incomingDoc->products_data = json_encode($productsData);
        $incomingDoc->sum = $incomeTotalAmount;
        $incomingDoc->currency = $this->company->currency->code;
        $incomingDoc->count = $incomeTotalCount;
        $incomingDoc->save();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $this->storeId;
        $storeDoc->company_id = $this->company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_type = 'App\Models\IncomingDoc';
        $storeDoc->doc_id = $incomingDoc->id;
        $storeDoc->products_data = json_encode($productsData);
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = $incomeTotalAmount;
        $storeDoc->sum = $incomeTotalAmount;
        $storeDoc->save();

        session()->flash('message', 'Запись добавлена');
        session()->forget('incomeProducts');
        $this->incomeProducts = [];
    }

    public function addToIncome($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('incomeProducts')) {
            $incomeProducts = session()->get('incomeProducts');
        }

        $incomeProducts[$id] = $product;
        $this->incomeProductsCount[$id][$this->storeId] = null;

        session()->put('incomeProducts', $incomeProducts);
        $this->search = '';
    }

    public function removeFromIncome($id)
    {
        $incomeProducts = session()->get('incomeProducts');

        if (count($incomeProducts) == 0) {
            session()->forget('incomeProducts');
        }

        unset($incomeProducts[$id]);
        session()->put('incomeProducts', $incomeProducts);
    }

    public function saveAsDraft()
    {
        if (empty($this->incomeProducts)) {
            session()->flash('message', 'Записи не найдены');
            return;
        }

        $coincidence = 0;

        foreach($this->incomeProducts as $productId => $incomeProduct) {

            if (array_key_exists($productId, $this->draftProducts)) {
                $coincidence++;
            }

            $this->draftProducts[$productId]['title'] = $incomeProduct['title'];
            $this->draftProducts[$productId]['barcodes'] = $incomeProduct['barcodes'];
        }

        if ($coincidence == count($this->incomeProducts)) {
            session()->flash('message', 'Черновик существует');
            return;
        }

        $draftsCount = ProductDraft::where('type', 'income')->count();

        $draft = new ProductDraft;
        $draft->user_id = auth()->user()->id;
        $draft->type = 'income';
        $draft->title = 'Income '.($draftsCount + 1);
        $draft->products_data = json_encode($this->draftProducts);
        $draft->count = count($this->draftProducts);
        $draft->comment = null;
        $draft->save();

        session()->flash('message', 'Запись добавлена');
    }

    public function removeIncome()
    {
        session()->forget('incomeProducts');
    }

    public function render()
    {
        $products = [];

        if (strlen($this->search) >= 2) {
            $products = Product::search($this->search)->orderBy('id', 'desc')->paginate(50);
        }

        $this->incomeProducts = session()->get('incomeProducts') ?? [];

        return view('livewire.store.income', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}
