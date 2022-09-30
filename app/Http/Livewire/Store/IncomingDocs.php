<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\IncomingDoc;
use App\Models\Product;

class IncomingDocs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $company;
    public $search;
    public $docDetail;
    public $docProducts = [];
    public $startDate = [];
    public $endDate = [];

    public function mount()
    {
        $this->lang = app()->getLocale();
        $this->company = auth()->user()->profile->company;
    }

    public function docDetail($id)
    {
        $this->docDetail = IncomingDoc::findOrFail($id);
        $products_data = json_decode($this->docDetail->products_data, true);
        $products_keys = collect($products_data)->keys();
        $this->docProducts = Product::whereIn('id', $products_keys->all())->get();
    }

    public function render()
    {
        $query = IncomingDoc::orderBy('id', 'desc');
        $appends = [];

        if (strlen($this->search) >= 2) {
            $query->where('doc_no', 'like', '%'.$this->search.'%');
        }

        if ($this->startDate || $this->endDate) {

            $startDate = $this->startDate ?? '2020-01-01';
            $endDate = $this->endDate ?? '3030-12-31';

            $query->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate);

            $appends['startDate'] = $startDate;            
            $appends['endDate'] = $endDate;
        }

        $incomingDocs = $query->paginate(50);
        $incomingDocs->appends($appends);

        return view('livewire.store.incoming-docs', ['incomingDocs' => $incomingDocs])
            ->layout('livewire.store.layout');
    }
}
