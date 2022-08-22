<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\StoreDoc;
use App\Models\DocType;
use App\Models\Product;

class StoreDocs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $lang;
    public $search = '';
    public $docDetail;
    public $docType;
    public $docProducts = [];
    public $startDate = [];
    public $endDate = [];

    public function mount()
    {
        $this->lang = app()->getLocale();
    }

    public function docDetail($id)
    {
        $this->docDetail = StoreDoc::findOrFail($id);
        $this->docType = DocType::where('id', $this->docDetail->doc_type_id)->first();
        $productsData = json_decode($this->docDetail->products_data, true);
        $productsKeys = collect($productsData)->keys();
        $this->docProducts = Product::whereIn('id', $productsKeys->all())->get();
        $this->dispatchBrowserEvent('open-modal');
    }

    public function render()
    {
        $query = StoreDoc::orderByDesc('id');
        $appends = [];

        if (strlen($this->search) >= 2) {
            $query->where('to_contractor', 'like', '%'.$this->search.'%');
        }

        if ($this->startDate || $this->endDate) {
            $startDate = $this->startDate ?? '2020-01-01';
            $endDate = $this->endDate ?? '3030-12-31';

            $query->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate);

            $appends['startDate'] = $startDate;            
            $appends['endDate'] = $endDate;
        }

        $storeDocs = $query->paginate(50);
        $storeDocs->appends($appends);

        return view('livewire.store.store-docs', ['storeDocs' => $storeDocs])
            ->layout('livewire.store.layout');
    }
}
