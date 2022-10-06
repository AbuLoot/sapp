<?php

namespace App\Http\Livewire\Store;

use Livewire\Component;

use App\Models\Unit;
use App\Models\Revision;
use App\Models\Product;

class InventoryDetail extends Component
{
    public $lang;
    public $company;
    public $storeId;
    public $search;
    public $revision;
    public $revisionProducts = [];

    public function mount($id)
    {
        $this->lang = app()->getLocale();
        $this->units = Unit::get();
        $this->revision = Revision::findOrFail($id);
        $this->company = auth()->user()->profile->company;
        $this->storeId = $this->revision->store_id;

        $productsData = json_decode($this->revision->products_data, true) ?? [];

        foreach($productsData as $productId => $productData) {

            $product = Product::find($productId);

            $this->revisionProducts[$productId] = $product;
            $this->revisionProducts[$productId]['estimatedCount'] = $productData['estimatedCount'];
            $this->revisionProducts[$productId]['actualCount'] = $productData['actualCount'];
            $this->revisionProducts[$productId]['shortageCount'] = $productData['shortageCount'];
            $this->revisionProducts[$productId]['surplusCount'] = $productData['surplusCount'];
            $this->revisionProducts[$productId]['shortageSum'] = $productData['shortageSum'];
            $this->revisionProducts[$productId]['surplusSum'] = $productData['surplusSum'];
        }
    }

    public function render()
    {
        return view('livewire.store.inventory-detail')
            ->layout('livewire.store.layout');
    }
}
