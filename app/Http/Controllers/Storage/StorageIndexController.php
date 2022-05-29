<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StorageIndexController extends Controller
{
    public function index()
    {
        return view('storage.index');
    }

    public function income()
    {
        return view('storage.income');
    }
}
