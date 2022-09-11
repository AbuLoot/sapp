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

use App\Traits\GenerateDocNo;

class Income extends Component
{
    use GenerateDocNo, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $units;
    public $storeId;
    public $search = '';
    public $incomeProducts = [];
    public $count = [];

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

        if (count($parts) == 2 && $parts[0] == 'count') {

            $incomeProducts = session()->get('incomeProducts');

            if ($value == 0 || !is_numeric($value)) {
                $this->addError($key, 'Неверные данные');
                $incomeProducts[$parts[1]]['incomingCount'] = 0;
                return;
            } else {
                $this->resetErrorBag($key);
            }

            $incomeProducts[$parts[1]]['incomingCount'] = $value;
            session()->put('incomeProducts', $incomeProducts);
        }
    }

    public function makeDoc()
    {
        if (empty($this->storeId) || !is_numeric($this->storeId)) {
            $this->addError('storeId', 'Выберите склад');
            return;
        } else {
            $this->resetErrorBag('storeId');
        }

        $products_data = [];
        $count_in_stores = [];
        $incomeTotalCount = 0;
        $incomeTotalAmount = 0;

        foreach($this->incomeProducts as $productId => $incomeProduct) {

            $product = Product::findOrFail($productId);

            $products_data[$productId]['incomingCount'] = $incomeProduct['incomingCount'];
            $products_data[$productId]['unit'] = $product->unit;
            $products_data[$productId]['title'] = $product->title;
            $products_data[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomeTotalCount = $incomeTotalCount + $incomeProduct['incomingCount'];
            $incomeTotalAmount = $incomeTotalAmount + ($product->purchase_price * $incomeProduct['incomingCount']);

            $count_in_stores = json_decode($product->count_in_stores, true) ?? [''];
            $count_in_stores[$this->storeId] = $incomeProduct['incomingCount'];

            $product->count_in_stores = json_encode($count_in_stores);
            $product->count += $incomeProduct['incomingCount'];
            $product->save();
        }

        $company = auth()->user()->profile->company;

        // Incoming Doc
        $docType = DocType::where('slug', 'forma-z-1')->first();

        $docNo = $this->generateIncomingStoreDocNo($this->storeId);

        $incomingDoc = new IncomingDoc;
        $incomingDoc->store_id = $company->stores->first()->id;
        $incomingDoc->company_id = $company->id;
        $incomingDoc->user_id = auth()->user()->id;
        $incomingDoc->username = auth()->user()->name;
        $incomingDoc->doc_no = $docNo;
        $incomingDoc->doc_type_id = $docType->id;
        $incomingDoc->products_data = json_encode($products_data);
        $incomingDoc->from_contractor = '';
        $incomingDoc->sum = $incomeTotalAmount;
        $incomingDoc->currency = $company->currency->code;
        $incomingDoc->count = $incomeTotalCount;
        // $incomingDoc->unit = $this->unit;
        $incomingDoc->comment = '';
        $incomingDoc->save();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $company->stores->first()->id;
        $storeDoc->company_id = $company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $incomingDoc->id;
        $storeDoc->doc_type_id = $docType->id;
        $storeDoc->products_data = json_encode($products_data);
        $storeDoc->from_contractor = '';
        $storeDoc->to_contractor = $company->title;
        $storeDoc->incoming_amount = 0;
        $storeDoc->outgoing_amount = $incomeTotalAmount;
        $storeDoc->sum = $incomeTotalAmount;
        // $storeDoc->unit = $this->unit;
        $storeDoc->comment = '';
        $storeDoc->save();

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
        $incomeProducts[$id]['incomingCount'] = 0;

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

    public function render()
    {
        if (strlen($this->search) >= 2) {
            $products = Product::search($this->search)->orderBy('id', 'desc')->paginate(50);
        } else {
            $products = [];
        }

        $this->incomeProducts = session()->get('incomeProducts') ?? [];

        return view('livewire.store.income', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}
