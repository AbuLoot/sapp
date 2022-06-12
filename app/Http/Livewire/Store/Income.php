<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Product;
use App\Models\IncomingDoc;

class Income extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $lang;
    public $search = '';
    public $incomeProducts = [];

    protected $rules = [
        // 'incomeProducts.*.price' => 'numeric',
        'incomeProducts.*.count' => 'require|numeric'
    ];

    public function mount()
    {
        $this->lang = app()->getLocale();
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        if (count($parts) == 3 && $parts[0] == 'incomeProducts' && $parts[2] == 'count') {
            $incomeProducts = session()->get('incomeProducts');
            $incomeProducts[$parts[1]]['count'] = $value;
            session()->put('incomeProducts', $incomeProducts);
        }
    }

    public function makeDoc()
    {
        $this->validate();

        $incomeProduct = $this->incomeProducts[$id];
        dd($data, $incomeProduct['count']);
        $product = Product::findOrFail($id);
        $product->count = $incomeProduct->count;
        $product->save();

        $incomingDoc = new IncomingDoc;
        $incomingDoc->store_id = 1;
        $incomingDoc->company_id = $this->product->company_id;
        $incomingDoc->user_id = auth()->user()->id;
        $incomingDoc->username = auth()->user()->name;
        $incomingDoc->doc_no = $this->doc_no;
        $incomingDoc->doc_type_id = 3;
        $incomingDoc->products_ids = json_encode($product->id);
        $incomingDoc->from_contractor = Company::find($this->product->company_id)->title;
        $incomingDoc->sum = $this->purchase_price * $this->product->count;
        $incomingDoc->currency = auth()->user()->profile->company->currency->code;
        $incomingDoc->count = $this->product->count;
        $incomingDoc->unit = $this->unit;
        // $incomingDoc->comment = '';
        $incomingDoc->save();

    }

    public function addToIncome($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('incomeProducts')) {

            $incomeProducts = session()->get('incomeProducts');
            $incomeProducts[$id] = $product;

            session()->put('incomeProducts', $incomeProducts);
            $this->search = '';

            return true;
        }

        $incomeProducts[$id] = $product;

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
            $products = Product::search($this->search)->orderBy('id', 'desc')->paginate(5);
        } else {
            $products = [];
        }

        $this->incomeProducts = session()->get('incomeProducts');

        return view('livewire.store.income', ['products' => $products])
            ->layout('store.layout');
    }
}
