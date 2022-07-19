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
    protected $queryString = ['search'];

    public $lang;
    public $search = '';

    public function mount()
    {
        // $this->lang = app()->getLocale();
    }

    public function deleteProducts()
    {
        // $this->lang = app()->getLocale();
    }   

    public function render()
    {
        if (strlen($this->search) >= 2) {
            $products = Product::search($this->search)->orderBy('id', 'desc')->paginate(30);
        }
        else {
            $products = Product::orderBy('id', 'desc')->paginate(30);
        }

        return view('livewire.store.index', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}
