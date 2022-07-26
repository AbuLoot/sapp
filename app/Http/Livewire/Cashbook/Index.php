<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\Store;
use App\Models\Cashbook;
use App\Models\Unit;
use App\Models\User;
use App\Models\Product;

class Index extends Component
{
    protected $queryString = ['searchProduct', 'searchClient'];

    public $lang;
    public $units;
    public $company;
    public $store;
    public $searchProduct = '';
    public $searchClient = '';
    public $cartProducts = [];
    public $priceMode = 'retail';

    protected $listeners = ['newData', 'addToCart'];

    protected $rules = [
        'store' => 'required|numeric',
        'cartProducts.*.countInCart' => 'required|numeric',
    ];

    public function mount()
    {
        $this->units = Unit::get();
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;

        if (session()->has('store') == false) {
            session()->put('store', $this->company->stores->first());
        }
    }

    public function updated($key, $value)
    {
        $parts = explode('.', $key);

        if ($key == 'store') {
            session()->put('store', $this->company->stores->where('id', $value)->first());
            $this->store = session()->get('store');
            // $this->reset();
            $this->emit('$refresh');
        }

        if (count($parts) == 3 && $parts[2] == 'countInCart') {

            $cartProducts = session()->get('cartProducts');

            if ($value <= 0 || !is_numeric($value)) {
                $cartProducts[$parts[1]]['countInCart'] = 1;
                session()->put('cartProducts', $cartProducts);
                return false;
            }

            $countInStores = json_decode($cartProducts[$parts[1]]->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$this->store->id] ?? 0;

            if ($countInStore == 0) {
                $this->addError('cartProducts.'.$parts[1].'.countInCart', 'Нет в наличии');
            } elseif ($countInStore < $value) {
                $cartProducts[$parts[1]]['countInCart'] = $countInStore;
            } else {
                $cartProducts[$parts[1]]['countInCart'] = $value;
            }

            session()->put('cartProducts', $cartProducts);
        }
    }

    public function newData()
    {
        session()->flash('message', 'Операция выполнена');
    }

    public function switchPriceMode()
    {
        if (session()->get('priceMode') == 'retail') {
            session()->put('priceMode', 'wholesale');
        } else {
            session()->put('priceMode', 'retail');
        }
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('cartProducts')) {

            $cartProducts = session()->get('cartProducts');
            $cartProducts[$id] = $product;

            $countInStores = json_decode($product->count_in_stores, true) ?? [];
            $countInStore = $countInStores[$this->store->id] ?? 0;

            if ($countInStore == 0) {
                $this->addError('cartProducts.'.$id.'.countInCart', 'Нет в наличии');
                return false;
            }

            $cartProducts[$id]['countInCart'] = 1;

            session()->put('cartProducts', $cartProducts);
            $this->searchProduct = '';

            return true;
        }

        $cartProducts[$id] = $product;
        $cartProducts[$id]['countInCart'] = 1;

        session()->put('cartProducts', $cartProducts);
        $this->searchProduct = '';
    }

    public function removeFromCart($id)
    {
        $cartProducts = session()->get('cartProducts');

        if (count($cartProducts) >= 1) {
            unset($cartProducts[$id]);
            session()->put('cartProducts', $cartProducts);
            return true;
        }

        session()->forget('cartProducts');
        $this->cartProducts = [];
    }

    public function clearCart()
    {
        session()->forget('cartProducts');
        $this->cartProducts = [];
    }

    public function render()
    {
        $products = [];
        $clients = [];

        if (strlen($this->searchProduct) >= 2) {
            $products = Product::search($this->searchProduct)->get()->take(7);
        }

        if (strlen($this->searchClient) >= 2) {
            $clients = User::where('name', 'like', $this->searchClient.'%')
                ->orWhere('lastname', 'like', $this->searchClient.'%')
                ->orWhere('tel', 'like', $this->searchClient.'%')
                ->get()->take(7);
        }

        $this->priceMode = session()->get('priceMode');
        $this->cartProducts = session()->get('cartProducts') ?? [];
        $this->store = session()->get('store');

        return view('livewire.cashbook.index', ['products' => $products, 'clients' => $clients])
            ->layout('livewire.cashbook.layout');
    }
}
