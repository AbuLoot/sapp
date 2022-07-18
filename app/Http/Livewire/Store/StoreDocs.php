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
        // $products_data = json_decode($this->docDetail->products_data, true);
        // $products_keys = collect($products_data)->keys();
        // $this->docProducts = Product::whereIn('id', $products_keys->all())->get();
        // dd($this->docDetail, $this->docType, $products_data, $products_keys,$this->docProducts);
    }

    public function render()
    {
        if (strlen($this->search) >= 2) {
            $storeDocs = StoreDoc::where('to_contractor', 'like', '%'.$this->search.'%')
                ->orderByDesc('id')
                ->paginate(30);
        }
        elseif ($this->startDate || $this->endDate) {
            $storeDocs = StoreDoc::where('created_at', '>=', $this->startDate)
                ->where('created_at', '<=', $this->endDate)
                ->orderBy('id', 'desc')
                ->paginate(30);
        }
        else {
            $storeDocs = StoreDoc::orderByDesc('id')->paginate(30);
        }

        return view('livewire.store.store-docs', ['storeDocs' => $storeDocs])
            ->layout('store.layout');
    }
}
