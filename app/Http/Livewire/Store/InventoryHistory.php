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

    public $lang;
    public $company;
    public $storeId;
    public $search;
    // public $revisions = [];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->company;
        $this->storeId = $this->company->stores->first()->id;
    }

    public function render()
    {
        $revisions = Revision::query()
            ->orderBy('id', 'desc')
            ->where('company_id', $this->company->id)
            ->where('store_id', $this->storeId)
            ->when($this->search, function($query) {
                $query->where('doc_no', 'like', '%'.$this->search.'%');
            })
            ->paginate(30);

        return view('livewire.store.inventory-history', ['revisions' => $revisions])
            ->layout('livewire.store.layout');
    }
}
