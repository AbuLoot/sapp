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
    public $selectMode = false;
    public $productsIds = [];
    public $deleteMode = false;

    public function mount()
    {
        // $this->lang = app()->getLocale();
    }

    public function updated($key)
    {
        if ($key == 'selectMode') {
            
        }
    }

    public function activateDeleteMode()
    {
        $this->deleteMode = true;
        // $this->lang = app()->getLocale();
    }   

    public function deleteProducts()
    {
        $this->deleteMode = false;
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

        if ($this->selectAll) {
            $this->productsIds = $products->pluck('id')->toArray();
        } else {
            $this->productsIds = [];
        }

        return view('livewire.store.index', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}
