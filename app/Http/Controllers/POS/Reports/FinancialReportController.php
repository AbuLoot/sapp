<?php

namespace App\Http\Controllers\POS\Reports;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;

class FinancialReportController extends Controller
{
    public $companyId;

    public function index(Request $request)
    {
        $startDate = $request->start_date ?? '2022-01-01';
        $endDate = $request->end_date ?? now();

        $incomes = IncomingOrder::query()
            ->where('company_id', $this->companyId)
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<=', $endDate.' 23:59:59')
            ->get();

        $outflow = OutgoingOrder::query()
            ->where('company_id', $this->companyId)
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<=', $endDate.' 23:59:59')
            ->get();

        return view('pos.reports.financial', [
            'incomes'   => $incomes,
            'outflow'   => $outflow,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
}
