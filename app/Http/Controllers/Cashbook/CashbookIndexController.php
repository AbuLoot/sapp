<?php

namespace App\Http\Controllers\Cashbook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashbookIndexController extends Controller
{
    public function index()
    {
        return view('cashbook.index');
    }
}
