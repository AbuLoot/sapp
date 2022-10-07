<?php

namespace App\Http\Livewire\Store;

use Illuminate\Http\Request;
use Livewire\Component;

use App\Models\Product;
use App\Models\Unit;

class PriceTags extends Component
{
    public $lang;
    public $search;
    public $products;
    public $company;
    public $units;
    public $companyName = false;
    public $productName = true;
    public $priceUnit = true;
    public $barcode = true;
    public $size = 70;
    public $count = 1;
    public $mmInPX = 3.78;

    public function mount(Request $request, $ids)
    {
        parse_str($ids, $arrIds);

        $this->lang = app()->getLocale();
        $this->products = Product::whereIn('id', $arrIds)->get();
        $this->company = auth()->user()->profile->company;
        $this->units = Unit::get();
    }

    public function updatedSize()
    {
        $this->size = $this->size ? $this->size : 70;
    }

    public function updatedCount()
    {
        $this->count = $this->count ? $this->count : 1;
    }

    public function addToPriceTag($id)
    {
        $this->products[] = Product::findOrFail($id);
        $this->search = '';
    }

    public function render()
    {
        $productsObj = [];

        if (strlen($this->search) >= 2) {
            $productsObj = Product::search($this->search)->get()->take(7);
        }

        return view('livewire.store.price-tags', ['productsObj' => $productsObj])
            ->layout('livewire.store.layout');
    }
}
