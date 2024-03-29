<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\IncomingDoc;
use App\Models\Product;

use App\Traits\GenerateDocNo;

class IncomingDocs extends Component
{
    use WithPagination, GenerateDocNo;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $company;
    public $search;
    public $docDetail;
    public $docProducts = [];
    public $docNo;
    public $docComment;
    public $editMode = false;
    public $startDate;
    public $endDate;

    protected $rules = [
        'docNo' => 'required',
    ];

    public function mount()
    {
        if (! Gate::allows('docs', auth()->user())) {
            abort(403);
        }

        $this->lang = app()->getLocale();
        $this->company = auth()->user()->company;
    }

    public function docDetail($id)
    {
        $this->docDetail = IncomingDoc::where('company_id', $this->company->id)->findOrFail($id);
        $products_data = json_decode($this->docDetail->products_data, true);
        $products_keys = collect($products_data)->keys();
        $this->docProducts = Product::whereIn('id', $products_keys->all())->get();
        $this->docNo = $this->docDetail->doc_no;
        $this->docComment = $this->docDetail->comment;
        $this->dispatchBrowserEvent('open-modal');
    }

    public function editDoc()
    {
        $this->editMode = true;
    }

    public function saveDoc()
    {
        if ($this->docNo == $this->docDetail->doc_no AND $this->docComment == $this->docDetail->comment) {
            $this->editMode = false;
            return;
        }

        $existDocNo = IncomingDoc::query()
            ->where('company_id', $this->company->id)
            ->where('doc_no', $this->docNo)
            ->where('doc_no', '!=', $this->docDetail->doc_no)
            ->first();

        if ($existDocNo) {
            $this->addError('docNo', 'Document number: '.$this->docNo.' exists');
            return;
        }

        $this->resetErrorBag('docNo');

        $this->docDetail->doc_no = $this->docNo;
        $this->docDetail->comment = $this->docComment;
        $this->docDetail->save();

        $this->editMode = false;
    }

    public function render()
    {
        $query = IncomingDoc::where('company_id', $this->company->id)->orderBy('id', 'desc');
        $appends = [];

        if (strlen($this->search) >= 2) {
            $query->where('doc_no', 'like', '%'.$this->search.'%');
        }

        if ($this->startDate || $this->endDate) {

            $startDate = $this->startDate ?? '2022-01-01';
            $endDate = $this->endDate ?? now();

            $query->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate.' 23:59:59');

            $appends['startDate'] = $startDate;
            $appends['endDate'] = $endDate;
        }

        $incomingDocs = $query->paginate(50);
        $incomingDocs->appends($appends);

        return view('livewire.store.incoming-docs', ['incomingDocs' => $incomingDocs])
            ->layout('livewire.store.layout');
    }
}
