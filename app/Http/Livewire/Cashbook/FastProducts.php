<?php

namespace App\Http\Livewire\Cashbook;

use Livewire\Component;

use App\Models\Mode;
use App\Models\Product;

class FastProducts extends Component
{
    public $search;
    public $company;
    public $fastMode;
    public $fastProducts = [];
    public $keyboard = false;

    protected $listeners = [
        'getFastProducts',
        'fastProductsInput',
    ];

    public function mount()
    {
        $this->company = auth()->user()->company;
    }

    public function getFastProducts()
    {
        $this->fastMode = Mode::where('slug', 'fast-products')->first();
        $this->fastProducts = $this->fastMode->products->where('in_company_id', $this->company->id);
    }

    public function fastProductsInput($value)
    {
        $property = $value[1];
        $this->$property = $value[0];
    }

    public function toggleFastMode($id)
    {
        $product = Product::where('in_company_id', $this->company->id)->findOrFail($id);
        $product->modes()->toggle($this->fastMode->id);

        $this->fastMode = Mode::where('slug', 'fast-products')->first();
        $this->fastProducts = $this->fastMode->products->where('in_company_id', $this->company->id);
        $this->search = '';
    }

    public function addToCart($id)
    {
        $this->emitUp('addToCart', $id);
    }

    public function render()
    {
        $products = [];

        if (strlen($this->search) >= 2) {
            $products = Product::search($this->search)
                ->where('in_company_id', $this->company->id)
                ->get()
                ->take(7);
        }

        return view('livewire.cashbook.fast-products', ['products' => $products]);
    }
}
