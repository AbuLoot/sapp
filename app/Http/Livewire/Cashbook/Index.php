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

    public function mount()
    {
        $this->units = Unit::get();
        $this->lang = app()->getLocale();
    }

    public function deleteProducts()
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
        $products = (strlen($this->searchProduct) >= 2)
            ? Product::search($this->searchProduct)->get()->take(7)
            : [];

        $this->cartProducts = session()->get('cartProducts') ?? [];

        return view('livewire.cashbook.index', ['products' => $products])
            ->layout('livewire.cashbook.layout');
    }
}
