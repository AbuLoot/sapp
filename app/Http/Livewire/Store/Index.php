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
    public $type = 1;
    public $category_id;
    public $company_id;
    public $productsId = [];
    public $deleteMode = false;

    public function mount()
    {
        $this->lang = app()->getLocale();
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
        $query = Product::where('type', $this->type)->orderBy('id', 'desc');
        $appends = [];

        if (strlen($this->search) >= 2) {
            $query->search($this->search);
        }

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
            $appends['category_id'] = $this->category_id;
        }

        if ($this->company_id) {
            $query->where('company_id', $this->company_id);
            $appends['company_id'] = $this->company_id;
        }

        $products = $query->paginate(30);
        $products->appends($appends);

        return view('livewire.store.index', ['products' => $products])
            ->layout('livewire.store.layout');
    }
}
