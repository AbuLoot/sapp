<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Product;
use App\Models\IncomingDoc;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $products = Product::orderByDesc('id')->paginate(5);

        return view('livewire.store.index', ['products' => $products]);
    }
}
