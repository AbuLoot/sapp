<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Unit;
use App\Models\Product;
use App\Models\IncomingDoc;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $search;
    public $units;
    public $type;
    public $company;
    public $companyId;
    public $categoryId;
    public $productsId = [];
    public $toggleMode = false;
    public $printMode = false;
    public $deleteMode = false;

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->units = Unit::get();
        $this->company = auth()->user()->profile->company;
    }

    public function resetFilter()
    {
        $this->type = null;
        $this->categoryId = null;
        $this->companyId = null;
    }

    public function activatePrintMode()
    {
        $this->deleteMode = false;
        $this->printMode = true;
    }

    public function activateDeleteMode()
    {
        $this->printMode = false;
        $this->deleteMode = true;
    }

    public function deactivateMode()
    {
        $this->printMode = false;
        $this->deleteMode = false;
    }

    public function toggleCheckInputs()
    {
        if (!$this->toggleMode AND $this->productsId) {
            $this->productsId = [];
            $this->toggleMode = false;
        } else {
            $this->toggleMode = true;
        }
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
        $this->productsId = [];
    }   

    public function render()
    {
        $products = Product::orderBy('id', 'desc')
            ->when(strlen($this->search) >= 2, function($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('barcodes', 'like', '%'.$this->search.'%')
                    ->orWhere('id_code', 'like', '%'.$this->search.'%');
            })
            ->when($this->type, function($query) {
                $query->where('type', $this->type);
            })
            ->when($this->categoryId, function($query) {
                $query->where('category_id', $this->categoryId);
            })
            ->when($this->companyId, function($query) {
                $query->where('company_id', $this->companyId);
            })
            // ->where('status', 33)
            ->paginate(30);

        if ($this->toggleMode) {
            $this->productsId = $products->pluck('id')->toArray();
            $this->toggleMode = false;
        }

        return view('livewire.store.index', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}
