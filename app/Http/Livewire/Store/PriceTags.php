<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;

use App\Models\Product;
use App\Models\Unit;

class PriceTags extends Component
{
    public $lang;
    public $product;
    public $company;
    public $units;
    public $companyName = false;
    public $productName = true;
    public $priceUnit = true;
    public $barcode = true;
    public $size = 70;
    public $count = 1;
    public $mmInPX = 3.78;

    public function mount($id)
    {
        $this->lang = app()->getLocale();
        $this->product = Product::find($id);
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

    public function render()
    {
        return view('livewire.store.price-tags')
            ->layout('livewire.store.layout');
    }
}
