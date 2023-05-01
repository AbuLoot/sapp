<?php

namespace App\Http\Controllers\Joystick;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

use DB;
use Storage;

use App\Http\Controllers\Joystick\Controller;
use App\Models\Mode;
use App\Models\Company;
use App\Models\Project;
use App\Models\Product;
use App\Models\Category;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductExtensionController extends Controller
{
    public $companyId;

    public function joytable()
    {
        if (! Gate::allows('joytable', \Auth::user())) {
            abort(403);
        }

        $this->authorize('viewAny', Product::class);

        $products = auth()->user()->roles()->firstWhere('name', 'admin')
            ? Product::orderBy('updated_at','desc')->paginate(50)
            : Product::where('in_company_id', $this->companyId)
                ->where('user_id', auth()->user()->id)
                ->orderBy('updated_at','desc')
                ->paginate(50);

        $categories = Category::where('company_id', $this->companyId)->get()->toTree();
        $modes = Mode::all();

        return view('joystick.products.joytable', ['categories' => $categories, 'modes' => $modes]);
        // return view('joystick.products.joytable', ['categories' => $categories, 'products' => $products, 'modes' => $modes]);
    }

    public function joytableUpdate(Request $request, $lang)
    {
        if (! Gate::allows('joytable', \Auth::user())) {
            abort(403);
        }

        $this->validate($request, [
            'id' => 'required|min:2',
            'title' => 'required',
            'price' => 'required|numeric',
        ]);

        $product = Product::findOrFail($request->id);

        $this->authorize('update', $product);

        $product->slug = Str::slug($request->title);
        $product->title = $request->title;
        $product->price = $request->price;
        $product->count = $request->count;
        $product->save();

        $product->searchable();

        return response()->json(['status', 'Товар обновлен!']);
    }

    public function export()
    {
        if (! Gate::allows('export', \Auth::user())) {
            abort(403);
        }

        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function importView()
    {
        if (! Gate::allows('import', \Auth::user())) {
            abort(403);
        }

        $companies = Company::orderBy('sort_id')->get();
        $categories = Category::get()->toTree();

        return view('joystick.products.import', ['companies' => $companies, 'categories' => $categories]);
    }

    public function import(Request $request, $lang)
    {
        if (! Gate::allows('import', \Auth::user())) {
            abort(403);
        }

        Excel::import(new ProductsImport, $request->file('file'));

        $products = Product::where('status', '<>', '0')->get();
        $products->searchable();

        return redirect($lang.'/admin/products')->with('status', 'Данные добавлены.');
    }

    public function search(Request $request)
    {
        $text = trim(strip_tags($request->text));

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {
            $products = Product::search($text)->where('in_company_id', $this->companyId)->orderBy('updated_at','desc')->paginate(50);
        }
        else {
            $products = Product::search($text)->where('in_company_id', $this->companyId)->where('user_id', auth()->user()->id)->orderBy('updated_at','desc')->paginate(50);
        }

        $categories = Category::where('company_id', $this->companyId)->orderBy('sort_id')->get()->toTree();
        $modes = Mode::all();

        $products->appends([
            'text' => $request->text,
        ]);

        return view('joystick.products.found', compact('categories', 'text', 'modes', 'products'));
    }

    public function searchAjax(Request $request)
    {
        $text = trim(strip_tags($request->text));

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {
            $products = Product::search($text)->where('in_company_id', $this->companyId)->orderBy('updated_at','desc')->take(50)->get();
        }
        else {
            $products = Product::search($text)->where('in_company_id', $this->companyId)->where('user_id', auth()->user()->id)->orderBy('updated_at','desc')->take(50)->get();
        }

        return response()->json($products);
    }

    public function calcForm()
    {
        if (! Gate::allows('allow-calc', \Auth::user())) {
            abort(403);
        }

        $categories = Category::get()->toTree();

        return view('joystick.products.price-calc', ['categories' => $categories]);
    }

    public function priceUpdate(Request $request)
    {
        if (! Gate::allows('allow-calc', \Auth::user())) {
            abort(403);
        }

        $this->validate($request, [
            'category_id' => 'required|numeric',
        ]);

        $category = Category::find($request->category_id);

        if ($category->children && count($category->children) > 0) {
            $ids = $category->descendants->pluck('id')->toArray();
        }

        $ids[] = $category->id;
        $ids = collect($ids)->sort()->implode(',');

        $sql = 'UPDATE products SET price = ';
        $queries = [];

        foreach($request->all() as $key => $input) {
            switch($key) {
                case 'number':
                    $queries[2] = '(price '.$request->operation.' '.$input.')';
                break;
                case 'round':
                    $round = strtoupper($input);
                    $queries[1] = $round.'(';
                    $queries[3] = ', -1) ';
                break;
            }
        }

        $sql .= collect($queries)->sortKeys()->implode('');
        $sql .= 'WHERE category_id IN ('.$ids.')';

        DB::update($sql);

        $products = Product::where('status', '<>', '0')->get();
        $products->searchable();

        return redirect($request->lang.'/admin/products')->with('status', 'Запись обновлена.');
    }

    public function categoryProducts($lang, $id)
    {
        $categories = Category::where('company_id', $this->companyId)->orderBy('sort_id')->get()->toTree();
        $category = Category::find($id);

        if ($category->children && count($category->children) > 0) {
            $ids = $category->descendants->pluck('id');
        }

        $ids[] = $category->id;

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {
            $products = Product::where('in_company_id', $this->companyId)
                ->whereIn('category_id', $ids)
                ->orderBy('updated_at','desc')
                ->paginate(50);
        }
        else {
            $products = Product::where('in_company_id', $this->companyId)
                ->where('user_id', auth()->user()->id)
                ->whereIn('category_id', $ids)
                ->orderBy('updated_at','desc')
                ->paginate(50);
        }

        $modes = Mode::all();

        return view('joystick.products.index', ['category' => $category, 'categories' => $categories, 'products' => $products, 'modes' => $modes]);
    }

    public function actionProducts(Request $request)
    {
        $this->validate($request, [
            'products_id' => 'required'
        ]);

        if (in_array($request->action, ['0', '1', '2', '3'])) {
            Product::whereIn('id', $request->products_id)->update(['status' => $request->action]);
        }
        elseif($request->action == 'destroy') {

            $products = Product::whereIn('id', $request->products_id)->get();

            $this->authorize('delete', $products->first());

            foreach($products as $product) {

                $images = unserialize($product->images);

                if (! empty($images) AND $product->image != 'no-image-middle.png') {
                    Storage::deleteDirectory('img/products/'.$product->path);
                }
            }

            Product::destroy($products->pluck('id'));
        }
        else {
            $mode = Mode::where('slug', $request->action)->first();
            $products = Product::whereIn('id', $request->products_id)->get();

            foreach ($products as $product) {
                $product->modes()->toggle($mode->id);
            }
        }

        return response()->json(['status' => true]);
    }
}
