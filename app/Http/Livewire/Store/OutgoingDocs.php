<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OutgoingDoc;

class OutgoingDocs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];

    public $lang;
    public $search = [];
    public $docDetail;
    public $docProducts = [];
    public $startDate = [];
    public $endDate = [];

    public function mount()
    {
        $this->lang = app()->getLocale();
    }

    public function docDetail($id)
    {
        $this->docDetail = OutgoingDoc::findOrFail($id);
        $this->docProducts = Product::whereIn('id', [$this->docDetail->products_ids])->get();
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
            ->layout('store.layout');
    }
}
