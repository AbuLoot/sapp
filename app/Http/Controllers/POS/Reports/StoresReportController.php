<?php

namespace App\Http\Controllers\POS\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;

class StoresReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? '2022-01-01';
        $endDate = $request->end_date ?? now();

        $products = Product::query()
            ->where('count', '=', 0)
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('pos.reports.stores', [
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'products' => $products,
        ]);
    }
}
