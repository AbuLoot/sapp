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

    public $lang;
    public $search;
    public $type;
    public $categoryId;
    public $companyId;
    public $productsId = [];
    public $deleteMode = false;

    public function mount()
    {
        $this->lang = app()->getLocale();
    }

    public function resetFilter()
    {
        $this->type = null;
        $this->categoryId = null;
        $this->companyId = null;
    }

    public function activateDeleteMode()
    {
        $this->deleteMode = true;
    }   

    public function deleteProducts()
    {
        if (count($this->productsId) >= 1) {

            $products = Product::whereIn('id', $this->productsId)->get();

            // $this->authorize('delete', $products->first());

            foreach($products as $product) {

                $images = unserialize($product->images);

                if (!empty($images) AND $product->image != 'no-image-middle.png') {
                    Storage::deleteDirectory('img/products/'.$product->path);
                }
            }

            Product::destroy($products->pluck('id'));

            session()->flash('message', 'Записи удалены');
        }

        $this->deleteMode = false;
    }   

    public function render()
    {
        $query = Product::orderBy('id', 'desc');
        $appends = [];

        if (strlen($this->search) >= 2) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        if ($this->type) {
            $query->where('type', $this->type);
            $appends['type'] = $this->type;
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
            $appends['categoryId'] = $this->categoryId;
        }

        if ($this->companyId) {
            $query->where('company_id', $this->companyId);
            $appends['companyId'] = $this->companyId;
        }

        $products = $query->paginate(30);
        $products->appends($appends);


        return view('livewire.store.index', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}
