<?php

namespace App\Http\Controllers\POS\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company;

class ContractorsReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? '2022-01-01';
        $endDate = $request->end_date ?? now();

        $suppliers = Company::query()
            ->where('is_supplier', 1)
            ->join('incoming_docs', function ($join) use ($startDate, $endDate) {
                $join->on('companies.id', '=', 'incoming_docs.contractor_id')
                    ->where('incoming_docs.contractor_type', '=', 'App\Models\Company')
                    ->where('incoming_docs.created_at', '>', $startDate)
                    ->where('incoming_docs.created_at', '<=', $endDate.' 23:59:59');
            })
            ->select('companies.*', 'incoming_docs.sum', 'incoming_docs.count')
            ->get();

        return view('pos.reports.contractors', [
            'suppliers' => $suppliers,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
}
