<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
    {
        return view('promo.index');
    }

    public function contacts()
    {
        return view('promo.contacts');
    }
}
