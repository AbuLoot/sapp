<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Store;
use App\Models\RevisionDraft;
use App\Models\Product;

class InventoryDraft extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $company;
    public $search = '';
    // public $drafts = [];
    public $revisionProducts = [];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
    }

    public function openTheDraft($id)
    {
        $draft = RevisionDraft::find($id);
        $productsData = json_decode($draft->products_data, true) ?? [];

        foreach($productsData as $productId => $productData) {
            $product = Product::find($productId);
            $revisionProducts[$productId] = $product;
        }

        session()->put('revisionProducts', $revisionProducts);

        return redirect('/'.$this->lang.'/storage/inventory');
    }

    public function removeFromDrafts($id)
    {
        $draft = RevisionDraft::find($id);
        $draft->delete();

        session()->flash('message', 'Запись удалена');
    }

    public function render()
    {
        $drafts = ($this->search)
            ? RevisionDraft::where('title', 'like', '%'.$this->search.'%')->orderByDesc('id')->paginate(30)
            : RevisionDraft::orderByDesc('id')->paginate(30);;

        return view('livewire.store.inventory-draft', ['drafts' => $drafts])
            ->layout('livewire.store.layout');
    }
}
