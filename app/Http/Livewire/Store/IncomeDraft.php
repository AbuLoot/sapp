<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Store;
use App\Models\ProductDraft;
use App\Models\Product;

class IncomeDraft extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $company;
    public $search;
    public $incomeProducts = [];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
        $this->storeId = session()->get('storage')->id;
    }

    public function openTheDraft($id)
    {
        $draft = ProductDraft::find($id);
        $productsData = json_decode($draft->products_data, true) ?? [];

        foreach($productsData as $productId => $productData) {
            $product = Product::find($productId);
            $incomeProducts[$productId] = $product;
        }

        session()->put('incomeProducts', $incomeProducts);

        return redirect('/'.$this->lang.'/storage/income');
    }

    public function removeFromDrafts($id)
    {
        $draft = ProductDraft::find($id);
        $draft->delete();

        session()->flash('message', 'Запись удалена');
    }

    public function render()
    {
        $drafts = $this->search
            ? ProductDraft::where('title', 'like', '%'.$this->search.'%')->where('type', 'income')->orderByDesc('id')->paginate(30)
            : ProductDraft::where('type', 'income')->orderByDesc('id')->paginate(30);

        return view('livewire.store.income-draft', ['drafts' => $drafts])
            ->layout('livewire.store.layout');
    }
}
