<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;

use App\Models\Unit;
use App\Models\Store;
use App\Models\Revision;
use App\Models\RevisionProduct;
use App\Models\DocType;
use App\Models\Product;

class InventoryHistory extends Component
{
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $lang;
    public $company;
    public $store_id;
    public $search = '';
    // public $revisions = [];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
        $this->store_id = $this->company->first()->id;
    }

    public function render()
    {
        $revisions = ($this->search)
            ? Revision::where('doc_no', 'like', '%'.$this->search.'%')->orderBy('id', 'desc')->paginate(25)
            : Revision::orderBy('id', 'desc')->paginate(25);

        return view('livewire.store.inventory-history', ['revisions' => $revisions])
            ->layout('store.layout');
    }
}
