<?php

namespace App\Http\Livewire\Joystick;

use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use Image;
use Storage;

use App\Models\Product;
use App\Models\Category;
use App\Traits\ImageTrait;

class Joytable extends Component
{
    use WithPagination, WithFileUploads, ImageTrait;

    public $products = [];
    public $product_index = null;
    public $categories = [];
    public $images = [];

    protected $rules = [
        'products.*.title' => 'required|min:2',
        'products.*.price' => 'numeric',
        'products.*.count' => 'numeric',
        'products.*.category_id' => 'numeric'
    ];

    public function render()
    {
        $productsItems = (auth()->user()->roles()->firstWhere('name', 'admin'))
            ? Product::orderBy('updated_at','desc')->paginate(10)
            : Product::where('user_id', auth()->user()->id)->orderBy('updated_at','desc')->paginate(10);

        $this->products = $productsItems->all();

        return view('livewire.joystick.joytable', ['productsItems' => $productsItems]);
    }

    public function editProduct($product_index)
    {
        $this->product_index = $product_index;
        $this->categories = Category::get()->toTree()->toArray();
    }

    public function saveProduct($product_index)
    {
        $this->validate();

        $product = $this->products[$product_index] ?? null;

        $editedProduct = Product::find($product['id']);

        if ($editedProduct) {
            $editedProduct->category_id = $product['category_id'];
            $editedProduct->title = $product['title'];
            $editedProduct->slug = Str::slug($product['title']);
            $editedProduct->price = $product['price'];
            $editedProduct->count = $product['count'];
            $editedProduct->save();
        }

        $this->product_index = null;
    }

    public function uploadImages($product_index)
    {
        // $this->validate(['images' => 'image|max:12288']);

        $product = $this->products[$product_index] ?? null;

        $editedProduct = Product::find($product['id']);

        // Adding new images
        if ($this->images) {

            $dirName = $product['path'];
            $images = unserialize($product['images']);

            // Create Directory
            if (!file_exists('img/products/'.$dirName) OR empty($dirName)) {
                $dirName = $product['category_id'].'/'.time();
                Storage::makeDirectory('img/products/'.$dirName);
                $editedProduct['path'] = $dirName;
            }

            $order = (!empty($images)) ? count($images) : 1;
            $order = time() + 1;
            $lastKey = array_key_last($images) + 1;

            foreach ($this->images as $key => $image)
            {
                $imageName = 'image-'.$order.'-'.Str::slug($product['title']).'.'.$image->getClientOriginalExtension();

                $watermark = Image::make('img/watermark.png');

                // Creating present images
                $this->resizeOptimalImage($image, 320, 290, '/img/products/'.$dirName.'/present-'.$imageName, 90);

                // Storing original images
                $this->resizeOptimalImage($image, 1024, 768, '/img/products/'.$dirName.'/'.$imageName, 90);

                $images[$key]['image'] = $imageName;
                $images[$key]['present_image'] = 'present-'.$imageName;
                $order++;
            }

            ksort($images);

            $introImage = current($images)['present_image'];
            $editedProduct->image = $introImage;
            $editedProduct->images = serialize($images);
            $editedProduct->save();
        }

        $this->product_index = null;
    }
}
