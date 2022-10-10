<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\Mode;
use App\Models\Product;

class FastProducts extends Component
{
    public $search2;
    public $company;
    public $fastMode;
    public $fastProducts = [];
    public $keyboard = false;

    protected $listeners = [
        'getFastProducts',
    ];

    public function mount()
    {
        $this->company = auth()->user()->profile->company;
    }

    public function getFastProducts($keyboard)
    {
        $state = $keyboard ? false : true;
        $this->emitUp('keyboard', $state);
        $this->keyboard = true;
        $this->fastMode = Mode::where('slug', 'fast-products')->first();
        $this->fastProducts = $this->fastMode->products;
    }

    public function toggleFastMode($id)
    {
        $product = Product::findOrFail($id);
        $product->modes()->toggle($this->fastMode->id);

        $this->fastMode = Mode::where('slug', 'fast-products')->first();
        $this->fastProducts = $this->fastMode->products;
        $this->search2 = '';
    }

    public function addToCart($id)
    {
        $this->emitUp('addToCart', $id);
    }

    public function render()
    {
        $products = [];

        if (strlen($this->search2) >= 2) {
            $products = Product::search($this->search2)->get()->take(7);
        }

        return view('livewire.cashbook.fast-products', ['products' => $products]);
    }
}
