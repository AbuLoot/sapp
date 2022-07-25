<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\Unit;
use App\Models\User;
use App\Models\Product;

class Index extends Component
{
    protected $queryString = ['searchProduct', 'searchClient'];

    public $lang;
    public $units;
    public $searchProduct = '';
    public $searchClient = '';
    public $cartProducts = [];
    public $priceMode = 'retail';

    protected $listeners = ['newData', 'addToCart'];

    public function mount()
    {
        $this->units = Unit::get();
        $this->lang = app()->getLocale();
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

    public function removeProducts()
    {
        // $this->lang = app()->getLocale();
    }   

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        if (session()->has('cartProducts')) {

            $cartProducts = session()->get('cartProducts');
            $cartProducts[$id] = $product;

            session()->put('cartProducts', $cartProducts);
            $this->searchProduct = '';

            return true;
        }

        $cartProducts[$id] = $product;

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

        return view('livewire.cashbook.index', ['products' => $products, 'clients' => $clients])
            ->layout('livewire.cashbook.layout');
    }
}
