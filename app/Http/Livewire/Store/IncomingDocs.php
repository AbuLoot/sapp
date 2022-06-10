<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IncomingDoc;

class IncomingDocs extends Component
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
        if (is_numeric($this->search)) {
            $incomingDocs = IncomingDoc::where('id', $this->search)->orderBy('id', 'desc')->paginate(10);
        }
        else {
            $incomingDocs = IncomingDoc::orderBy('id', 'desc')->paginate(10);
        }

        return view('livewire.store.incoming-docs', ['incomingDocs' => $incomingDocs])
            ->layout('store.layout');
    }
}
