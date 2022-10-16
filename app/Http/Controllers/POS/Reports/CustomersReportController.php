<?php

namespace App\Http\Controllers\POS\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class CustomersReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? '2022-01-01';
        $endDate = $request->end_date ?? now();

        $customers = User::query()
            ->where('status', true)
            ->join('incoming_orders', function ($join) use ($startDate, $endDate) {
                $join->on('users.id', '=', 'incoming_orders.contractor_id')
                    ->where('incoming_orders.contractor_type', '=', 'App\Models\User')
                    ->where('incoming_orders.created_at', '>', $startDate)
                    ->where('incoming_orders.created_at', '<=', $endDate.' 23:59:59');
            })
            ->select('users.*', 'incoming_orders.sum')
            ->get();

        return view('pos.reports.customers', [
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'customers'    => $customers
        ]);
    }
}
