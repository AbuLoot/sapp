<?php

namespace App\Http\Controllers\Joystick;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Image;
use Storage;

use App\Http\Controllers\Joystick\Controller;
use App\Models\Unit;
use App\Models\Mode;
use App\Models\Region;
use App\Models\Option;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Project;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;

use App\Models\Store;

class ProductController extends Controller
{
    public $companyId;

    public function index()
    {
        $this->authorize('viewAny', Product::class);

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {
            $products = Product::orderBy('updated_at','desc')->paginate(50);
        }
        else {
            $products = Product::where('in_company_id', $this->companyId)
                ->where('user_id', auth()->user()->id)
                ->orderBy('updated_at','desc')
                ->paginate(50);
        }

        $categories = Category::where('company_id', $this->companyId)->orderBy('sort_id')->get()->toTree();
        $modes = Mode::all();

        return view('joystick.products.index', ['categories' => $categories, 'products' => $products, 'modes' => $modes]);
    }

    public function create($lang)
    {
        $this->authorize('create', Product::class);

        $currency = Currency::where('lang', (($lang == 'ru') ? 'kz' : $lang))->first();
        $categories = Category::where('company_id', $this->companyId)->get()->toTree();
        $companies = Company::where('company_id', $this->companyId)->orderBy('sort_id')->get();
        $projects = Project::get()->toTree();
        $regions = Region::orderBy('sort_id')->get()->toTree();
        $modes = Mode::get();
        $units = Unit::get();

        return view('joystick.products.create', ['modes' => $modes, 'units' => $units, 'regions' => $regions, 'currency' => $currency, 'categories' => $categories, 'companies' => $companies, 'projects' => $projects]);
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

        // Product parameters
        $parameters = [
            'weight'    => $request->weight,
            'length'    => $request->length,
            'width'     => $request->width,
            'height'    => $request->height,
        ];

        $product = new Product;
        $product->sort_id = ($request->sort_id > 0) ? $request->sort_id : $product->count() + 1;
        $product->category_id = $request->category_id;
        $product->user_id = auth()->user()->id;
        $product->in_company_id = $this->companyId;
        $product->company_id = $request->company_id ?? 0;
        $product->project_id = $request->project_id ?? 0;
        $product->slug = Str::slug($request->title);
        $product->title = $request->title;
        $product->barcodes = json_encode($request->barcodes);
        $product->id_code = $request->id_code;
        $product->purchase_price = $request->purchase_price;
        $product->wholesale_price = $request->wholesale_price;
        $product->price = $request->price;
        $product->count = $request->count;
        $product->unit = $request->unit;
        $product->type = $request->type;
        $product->description = $request->description;
        $product->characteristic = $request->characteristic;
        $product->parameters = json_encode($parameters);
        $product->image = $introImage;
        $product->lang = $request->lang;
        $product->status = $request->status;
        $product->save();

        if ( ! is_null($request->modes_id)) {
            $product->modes()->attach($request->modes_id);
        }

        $product->searchable();

        return redirect($request->lang.'/admin/products')->with('status', 'Товар добавлен!');
    }

    public function edit($lang, $id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('update', $product);

        $categories = Category::where('company_id', $this->companyId)->get()->toTree();
        $currency = Currency::where('lang', (($lang == 'ru') ? 'kz' : $lang))->first();
        $companies = Company::where('company_id', $this->companyId)->orderBy('sort_id')->get();
        $projects = Project::get()->toTree();
        $regions = Region::orderBy('sort_id')->get()->toTree();
        $modes = Mode::get();
        $units = Unit::get();

        return view('joystick.products.edit', ['modes' => $modes, 'units' => $units, 'regions' => $regions, 'product' => $product, 'currency' => $currency, 'categories' => $categories, 'companies' => $companies, 'projects' => $projects]);
    }

    public function update(Request $request, $lang, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:2',
            'category_id' => 'required|numeric',
            // 'barcode' => 'required',
        ]);

        $product = Product::where('in_company_id', $this->companyId)->findOrFail($id);

        $this->authorize('update', $product);

        // Product parameters
        $parameters = [
            'weight'    => $request->weight,
            'length'    => $request->length,
            'width'     => $request->width,
            'height'    => $request->height,
        ];

        $product->sort_id = ($request->sort_id > 0) ? $request->sort_id : $product->count() + 1;
        $product->category_id = $request->category_id;
        $product->company_id = $request->company_id ?? 0;
        $product->project_id = $request->project_id ?? 0;
        $product->slug = Str::slug($request->title);
        $product->title = $request->title;
        $product->barcodes = json_encode($request->barcodes);
        $product->id_code = $request->id_code;
        $product->purchase_price = $request->purchase_price;
        $product->wholesale_price = $request->wholesale_price;
        $product->price = $request->price;
        $product->count = $request->count;
        $product->unit = $request->unit;
        $product->type = $request->type;
        $product->description = $request->description;
        $product->characteristic = $request->characteristic;
        $product->parameters = json_encode($parameters);
        $product->lang = $request->lang;
        $product->status = $request->status;
        $product->save();

        $product->modes()->sync($request->modes_id);

        $product->searchable();

        return redirect($lang.'/admin/products')->with('status', 'Товар обновлен!');
    }

    public function destroy($lang, $id)
    {
        $product = Product::where('in_company_id', $this->companyId)->findOrFail($id);

        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->back();
    }
}
