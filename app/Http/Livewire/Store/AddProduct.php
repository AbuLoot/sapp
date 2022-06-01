<?php

namespace App\Http\Livewire\Store;

use Illuminate\Support\Str;
use Livewire\Component;

use App\Models\Unit;
use App\Models\Store;
use App\Models\Company;
use App\Models\Category;
use App\Models\Product;
use App\Models\IncomingDoc;

class AddProduct extends Component
{
    // public $doc_no, $category_id, $company_id, $slug, $title, $barcode, $id_code, $wholesale_price, $price, $count, $type;
    public $product;
    public $doc_no;
    public $type = 1;
    public $id_code;
    public $wholesale_price;
    public $alert = false;
    public $modal = false;

    protected $rules = [
        'product.title' => 'required|string|min:2',
        'product.doc_no' => 'required',
        'product.company_id' => 'required|numeric',
        'product.category_id' => 'required|numeric',
        'product.barcode' => 'required',
        'product.count' => 'required|numeric',
        'product.price' => 'required',
    ];

    public function mount()
    {
        $this->product = new Product;
    }

    public function saveProduct()
    {
        $data = $this->validate();

        $lastProduct = Product::orderByDesc('id')->first();

        Product::create([
            'sort_id' => $lastProduct->id + 1,
            'user_id' => auth()->user()->id,
            // 'doc_no' => $this->product->doc_no,
            'company_id' => $this->product->company_id,
            'category_id' => $this->product->category_id,
            'slug' => Str::slug($this->product->title),
            'title' => $this->product->title,
            'barcode' => $this->product->barcode,
            'id_code' => $this->id_code ?? NULL,
            'wholesale_price' => $this->wholesale_price ?? 0,
            'price' => $this->product->price,
            'count' => $this->product->count,
            'type' => $this->type,
            'image' => 'no-image-middle.png',
            'lang' => 'ru',
            'status' => 1,
        ]);

        // session()->flash('alert', 'Запись добавлена.');
        $alert = true;

        // $this->reset();
    }

    public function addCompany()
    {
        $this->modal = true;
        // $data = $this->validate();

        // // $this->company = new Company;
        // $this->company->title = $data->title;
        // $this->company->tel = $data->tel;
        // $this->company->address = $data->address;
        // $this->company->save();
    }

    public function render()
    {
        $units = Unit::all();
        $stores = Store::all();

        return view('livewire.store.add-product', ['units' => $units, 'stores' => $stores]);
    }
}
