<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\StoreDoc;
use App\Models\DocType;
use App\Models\Product;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class StoreDocs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $lang;
    public $search;
    public $docDetail;
    public $docProducts = [];
    public $startDate;
    public $endDate;

    public function mount()
    {
        if (! Gate::allows('storedocs', auth()->user())) {
            abort(403);
        }

        $this->lang = app()->getLocale();
    }

    public function docDetail($id)
    {
        $this->docDetail = StoreDoc::findOrFail($id);
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
            $query->whereHasMorph('contractor', [Company::class, User::class], function (Builder $query, $type) {
                $column = $type === Company::class ? 'title' : 'name';
                $query->where($column, 'like', $this->search.'%');
            });
        }

        if ($this->startDate || $this->endDate) {
            $startDate = $this->startDate ?? '2022-01-01';
            $endDate = $this->endDate ?? now();

            $query->where('created_at', '>', $startDate)
                ->where('created_at', '<=', $endDate.' 23:59:59');

            $appends['startDate'] = $startDate;            
            $appends['endDate'] = $endDate;
        }

        $storeDocs = $query->paginate(50);
        $storeDocs->appends($appends);

        return view('livewire.store.store-docs', ['storeDocs' => $storeDocs])
            ->layout('livewire.store.layout');
    }
}
