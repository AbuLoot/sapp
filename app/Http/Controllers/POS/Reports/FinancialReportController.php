<?php

namespace App\Http\Controllers\POS\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? '2022-01-01';
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        $operations = [
            'incoming-products',
            'writeoff-products',
            'incoming-cash',
            'outgoing-cash',
            'returned-products',
            'repayment-debt',
            'payment-products',
            'sale-on-credit',
        ];

        $previousYear = now()->subYear()->format('Y').'-01-01';

        $incomes = IncomingOrder::query()
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get();

        $outflow = OutgoingOrder::query()
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get();

        return view('pos.reports.financial', [
            'incomes'   => $incomes,
            'outflow'   => $outflow,
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'countUsers'    => User::count(),
            'products' => Product::get(),
        ]);
    }
}
