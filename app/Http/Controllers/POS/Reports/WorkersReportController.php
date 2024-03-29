<?php

namespace App\Http\Controllers\POS\Reports;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class WorkersReportController extends Controller
{
    public $companyId;

    public function index(Request $request)
    {
        $startDate = $request->start_date ?? '2022-01-01';
        $endDate = $request->end_date ?? now();

        $workers = User::query()
            ->where('users.company_id', $this->companyId)
            ->where('is_worker', true)
            ->join('incoming_orders', function ($join) use ($startDate, $endDate) {
                $join->on('users.id', '=', 'incoming_orders.user_id')
                    ->where('incoming_orders.created_at', '>', $startDate)
                    ->where('incoming_orders.created_at', '<=', $endDate.' 23:59:59');
            })
            ->select('users.*', 'incoming_orders.sum')
            ->get();

        return view('pos.reports.workers', [
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'workers'   => $workers
        ]);
    }
}
