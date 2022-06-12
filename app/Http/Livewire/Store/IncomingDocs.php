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
        $this->docDetail = IncomingDoc::findOrFail($id);
        $this->docProducts = Product::whereIn('id', [$this->docDetail->products_ids])->get();
    }

    public function render()
    {
        if (is_numeric($this->search)) {
            $incomingDocs = IncomingDoc::where('doc_no', 'like', '%'.$this->search)->orderBy('id', 'desc')->paginate(50);
        }
        elseif ($this->startDate || $this->endDate) {
            $incomingDocs = IncomingDoc::where('created_at', '>=', $this->startDate)
                ->where('created_at', '<=', $this->endDate)
                ->orderBy('id', 'desc')
                ->paginate(50);
        }
        else {
            $incomingDocs = IncomingDoc::orderBy('id', 'desc')->paginate(50);
        }

        return view('livewire.store.incoming-docs', ['incomingDocs' => $incomingDocs])
            ->layout('store.layout');
    }
}
