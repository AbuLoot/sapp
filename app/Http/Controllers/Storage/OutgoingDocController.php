<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Image;
use Storage;

use App\Models\Unit;
use App\Models\Company;
use App\Models\Project;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Traits\ImageTrait;

class OutgoingDocController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Product::class);

        if (auth()->user()->roles()->firstWhere('name', 'admin')) {
            $products = Product::orderBy('updated_at','desc')->paginate(50);
        }
        else {
            $products = Product::where('user_id', auth()->user()->id)->orderBy('updated_at','desc')->paginate(50);
        }

        $categories = Category::get()->toTree();

        return view('storage.docs-outgoing', ['categories' => $categories, 'products' => $products]);
    }
}
