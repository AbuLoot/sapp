<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\OutgoingDoc;
use App\Models\Product;

class OutgoingDocs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $lang;
    public $company;
    public $search = [];
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
        $this->docDetail = OutgoingDoc::findOrFail($id);
        $products_data = json_decode($this->docDetail->products_data, true);
        $products_keys = collect($products_data)->keys();
        $this->docProducts = Product::whereIn('id', $products_keys->all())->get();
    }

    public function render()
    {
        if (is_numeric($this->search)) {
            $outgoingDocs = OutgoingDoc::where('doc_no', 'like', '%'.$this->search)->orderBy('id', 'desc')->paginate(50);
        }
        elseif ($this->startDate || $this->endDate) {
            $outgoingDocs = OutgoingDoc::where('created_at', '>=', $this->startDate)
                ->where('created_at', '<=', $this->endDate)
                ->orderBy('id', 'desc')
                ->paginate(50);
        }
        else {
            $outgoingDocs = OutgoingDoc::orderBy('id', 'desc')->paginate(50);
        }

        return view('livewire.store.outgoing-docs', ['outgoingDocs' => $outgoingDocs])
            ->layout('livewire.store.layout');
    }
}
