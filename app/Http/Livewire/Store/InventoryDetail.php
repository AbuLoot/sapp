<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Store;
use App\Models\Revision;
use App\Models\RevisionProduct;
use App\Models\DocType;
use App\Models\Product;

class InventoryDetail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $company;
    public $store_id;
    public $search = '';
    public $revision;

    public function mount($id)
    {
        $this->revision = Revision::findOrFail($id);
        $this->company = auth()->user()->profile->company;
        $this->stores = $this->company->stores;
        $this->product = Product::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.store.inventory-detail')
            ->layout('livewire.store.layout');
    }
}
