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
    public $search = '';

    public function mount()
    {
        $this->lang = app()->getLocale();
    }

    public function render()
    {
        $outgoingDocs = OutgoingDoc::orderBy('id', 'desc')->paginate(10);

        return view('livewire.store.outgoing-docs', ['outgoingDocs' => $outgoingDocs])
            ->layout('store.layout');
    }
}
