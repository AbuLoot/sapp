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
use App\Models\Writeoff;

class WriteoffController extends Controller
{
    public function index()
    {
        $writeoffs = Writeoff::get();

        return view('store.writeoff', ['writeoffs' => $writeoffs]);
    }
}
