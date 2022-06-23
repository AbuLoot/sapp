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

class Income extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $lang;
    public $units;
    public $store_id;
    public $search = '';
    public $incomeProducts = [];
    public $count = [];

    protected $rules = [
        // 'count.*' => 'require|numeric'
    ];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->units = Unit::get();
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        if (count($parts) == 2 && $parts[0] == 'count') {

            $incomeProducts = session()->get('incomeProducts');

            if ($value == 0 || !is_numeric($value)) {
                $this->addError($key, 'Неверные данные');
                $incomeProducts[$parts[1]]['income_count'] = 0;
                return false;
            } else {
                $this->resetErrorBag($key);
            }

            $incomeProducts[$parts[1]]['income_count'] = $value;
            session()->put('incomeProducts', $incomeProducts);
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
        $count_in_stores = [];
        $incomeAmountCount = 0;
        $incomeAmountPrice = 0;

        foreach($this->incomeProducts as $productId => $incomeProduct) {

            $product = Product::findOrFail($productId);

            $products_data[$productId]['count'] = $incomeProduct['income_count'];
            $products_data[$productId]['unit'] = $product->unit;
            $products_data[$productId]['title'] = $product->title;
            $products_data[$productId]['barcodes'] = json_decode($product->barcodes, true);

            $incomeAmountCount = $incomeAmountCount + $incomeProduct['income_count'];
            $incomeAmountPrice = $incomeAmountPrice + ($product->purchase_price * $incomeProduct['income_count']);

            if (is_object(json_decode($product->count_in_stores))) {
                $count_in_stores = json_decode($product->count_in_stores, true);
                $count_in_stores[$this->store_id] += $incomeProduct['income_count'];
            }
            else {
                $count_in_stores[$this->store_id] = $incomeProduct['income_count'];
            }

            $product->count_in_stores = json_encode($count_in_stores);
            $product->count += $incomeProduct['income_count'];
            $product->save();
        }

        $company = auth()->user()->profile->company;
        $docCount = IncomingDoc::count();
        $docType = DocType::where('slug', 'forma-z-1')->first();

        $incomingDoc = new IncomingDoc;
        $incomingDoc->store_id = $company->stores->first()->id;
        $incomingDoc->company_id = $company->id;
        $incomingDoc->user_id = auth()->user()->id;
        $incomingDoc->username = auth()->user()->name;
        $incomingDoc->doc_no = $company->stores->first()->id . $docCount++;
        $incomingDoc->doc_type_id = $docType->id;
        $incomingDoc->products_data = json_encode($products_data);
        $incomingDoc->from_contractor = '';
        $incomingDoc->sum = $incomeAmountPrice;
        $incomingDoc->currency = $company->currency->code;
        $incomingDoc->count = $incomeAmountCount;
        // $incomingDoc->unit = $this->unit;
        $incomingDoc->comment = '';
        $incomingDoc->save();

        $storeDoc = new StoreDoc;
        $storeDoc->store_id = $company->stores->first()->id;
        $storeDoc->company_id = $company->id;
        $storeDoc->user_id = auth()->user()->id;
        $storeDoc->doc_id = $incomingDoc->id;
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

        session()->forget('incomeProducts');
        $this->incomeProducts = [];
        // dd($product, $products_data, $incomingDoc, $storeDoc);
    }

    public function addToIncome($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('incomeProducts')) {

            $incomeProducts = session()->get('incomeProducts');
            $incomeProducts[$id] = $product;
            $incomeProducts[$id]['income_count'] = 0;

            session()->put('incomeProducts', $incomeProducts);
            $this->search = '';

            return true;
        }

        $incomeProducts[$id] = $product;
        $incomeProducts[$id]['income_count'] = 0;

        session()->put('incomeProducts', $incomeProducts);
        $this->search = '';
    }

    public function deleteFromIncome($id)
    {
        $incomeProducts = session()->get('incomeProducts');

        if (count($incomeProducts) >= 1) {
            unset($incomeProducts[$id]);
            session()->put('incomeProducts', $incomeProducts);
            return true;
        }

        session()->forget('incomeProducts');
        $this->incomeProducts = [];
    }

    public function render()
    {
        if (strlen($this->search) >= 2) {
            $products = Product::search($this->search)->orderBy('id', 'desc')->paginate(50);
        } else {
            $products = [];
        }

        $this->incomeProducts = session()->get('incomeProducts') ?? [];
        $company = auth()->user()->profile->company;

        return view('livewire.store.income', ['products' => $products, 'company' => $company])
            ->layout('store.layout');
    }
}
