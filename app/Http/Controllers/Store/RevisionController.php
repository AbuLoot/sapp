<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Store\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Unit;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Revision;

class RevisionController extends Controller
{
    public function index()
    {
        $revision = Revision::get();

        return view('store.revision', ['revision' => $revision]);
    }

    public function create($lang)
    {
        $this->authorize('create', Product::class);

        $units = Unit::all();
        $currency = Currency::where('lang', (($lang == 'ru') ? 'kz' : $lang))->first();
        $categories = Category::get()->toTree();
        $companies = Company::orderBy('sort_id')->get();
        $projects = Project::get()->toTree();

        return view('store.add-product', ['units' => $units, 'currency' => $currency, 'categories' => $categories, 'companies' => $companies, 'projects' => $projects]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $this->validate($request, [
            'title' => 'required|min:2',
            'category_id' => 'required|numeric',
            // 'barcode' => 'required',
            // 'images' => 'mimes:jpeg,jpg,png,svg,svgs,bmp,gif',
        ]);

        $introImage = 'no-image-middle.png';
        $images = [];
        $dirName = null;

        if ($request->hasFile('images')) {

            $dirName = $request->category_id.'/'.time();
            Storage::makeDirectory('img/products/'.$dirName);

            $images = $this->saveImages($request, $dirName);
            $introImage = current($images)['present_image'];
        }

        $product = new Product;
        $product->sort_id = ($request->sort_id > 0) ? $request->sort_id : $product->count() + 1;
        $product->user_id = auth()->user()->id;
        $product->category_id = $request->category_id;
        $product->project_id = $request->project_id;
        $product->company_id = $request->company_id;
        $product->slug = Str::slug($request->title);
        $product->title = $request->title;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->barcode = $request->barcode;
        $product->id_code = $request->id_code;
        $product->wholesale_price = $request->wholesale_price;
        $product->price = $request->price;
        $product->count = $request->count;
        $product->type = $request->type;
        $product->description = $request->description;
        $product->characteristic = $request->characteristic;
        $product->parameters = $request->parameters;
        $product->path = $dirName;
        $product->image = $introImage;
        $product->images = serialize($images);
        $product->lang = $request->lang;
        $product->status = $request->status;
        $product->save();

        if ( ! is_null($request->modes_id)) {
            $product->modes()->attach($request->modes_id);
        }

        if ( ! is_null($request->options_id)) {
            $product->options()->attach($request->options_id);
        }

        $product->searchable();

        return redirect($request->lang.'/admin/products')->with('status', 'Товар добавлен!');
    }

    public function edit($lang, $id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('update', $product);

        $categories = Category::get()->toTree();
        $currency = Currency::where('lang', (($lang == 'ru') ? 'kz' : $lang))->first();
        $companies = Company::orderBy('sort_id')->get();
        $projects = Project::get()->toTree();
        $regions = Region::orderBy('sort_id')->get()->toTree();
        $options = Option::orderBy('sort_id')->get();
        $grouped = $options->groupBy('data');
        $modes = Mode::all();

        return view('joystick.products.edit', ['modes' => $modes, 'regions' => $regions, 'product' => $product, 'currency' => $currency, 'categories' => $categories, 'companies' => $companies, 'projects' => $projects, 'options' => $options, 'grouped' => $grouped]);
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2',
            'category_id' => 'required|numeric',
            // 'barcode' => 'required',
        ]);

        $product = Product::findOrFail($id);

        $this->authorize('update', $product);

        $dirName = $product->path;
        $images = unserialize($product->images);

        // Remove images
        if (isset($request->remove_images)) {
            $images = $this->removeImages($request, $images, $product);
            $introImage = (isset($images[0]['present_image'])) ? $images[0]['present_image'] : 'no-image-middle.png';
            $product->image = $introImage;
            $product->images = serialize($images);
        }

        // Adding new images
        if ($request->hasFile('images')) {

            if ( ! file_exists('img/products/'.$dirName) OR empty($dirName)) {
                $dirName = $product->category_id.'/'.time();
                Storage::makeDirectory('img/products/'.$dirName);
                $product->path = $dirName;
            }

            $images = $this->uploadImages($request, $dirName, $images, $product);
            $introImage = current($images)['present_image'];
            $product->image = $introImage;
            $product->images = serialize($images);
        }

        // Change directory for new category
        if ($product->category_id != $request->category_id AND file_exists('img/products/'.$product->path) AND  $product->path != '') {
            $dirName = $request->category_id.'/'.time();
            Storage::move('img/products/'.$product->path, 'img/products/'.$dirName);
            $product->path = $dirName;
        }

        $product->sort_id = ($request->sort_id > 0) ? $request->sort_id : $product->count() + 1;
        $product->category_id = $request->category_id;
        $product->project_id = $request->project_id;
        $product->company_id = $request->company_id;
        $product->slug = Str::slug($request->title);
        $product->title = $request->title;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->barcode = $request->barcode;
        $product->id_code = $request->id_code;
        $product->wholesale_price = $request->wholesale_price;
        $product->price = $request->price;
        $product->count = $request->count;
        $product->type = $request->type;
        $product->description = $request->description;
        $product->characteristic = $request->characteristic;
        $product->parameters = $request->parameters;
        $product->lang = $request->lang;
        $product->status = $request->status;
        $product->save();

        $product->modes()->sync($request->modes_id);

        $product->options()->sync($request->options_id);

        // Add new options if exist
        // $options_new = collect($request->options_id)->diff($product->options->pluck('id'));
        // $product->options()->attach($request->options_id);

        // // Delete options
        // if (is_null($request->options_id) OR count($request->options_id) < $product->options->count()) {
        //     $options_del = $product->options->except($request->options_id);
        //     $product->options()->detach($options_del);
        // }

        $product->searchable();

        return redirect($lang.'/admin/products')->with('status', 'Товар обновлен!');
    }
}