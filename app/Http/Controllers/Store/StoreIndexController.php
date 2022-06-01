<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreIndexController extends Controller
{
    public function index()
    {
        return view('store.index');
    }

    public function income()
    {
        return view('store.income');
    }
}
