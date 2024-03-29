<?php

namespace App\Http\Controllers\POS\Reports;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\CashDoc;
use App\Models\CashShiftJournal;
use App\Models\IncomingOrder;

class CashReconciliationController extends Controller
{
    public $companyId;

    public function index(Request $request)
    {
        $startDate = $request->start_date ?? '2022-01-01';
        $endDate = $request->end_date ?? now();

        $cashierObj = [];
        $cashierId = $request->cashier_id ?? null;
        $cashShiftJournal = [];
        $incomes = [];

        $cashiers = User::query()
            ->where('company_id', $this->companyId)
            ->where('is_worker', true)
            ->join('role_user', function ($join) {
                $join->on('users.id', '=', 'role_user.user_id')
                    ->whereIn('role_user.role_id', [1, 2, 4]);
            })
            ->get();

        if ($cashierId) {

            $cashierObj = User::find($cashierId);

            $cashShiftJournal = CashShiftJournal::query()
                ->where('company_id', $this->companyId)
                ->where('from_user_id', $cashierId)
                ->orWhere('to_user_id', $cashierId)
                ->where('mode', 'close')
                ->orderBy('id', 'desc')
                ->get();

            $incomes = IncomingOrder::query()
                ->where('company_id', $this->companyId)
                ->where('user_id', $cashierId)
                ->where('operation_code', 'payment-products')
                ->get();
        }

        return view('pos.reports.cash-reconciliation', [
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'cashiers'  => $cashiers,
            'cashierObj'  => $cashierObj,
            'cashShiftJournal' => $cashShiftJournal,
            'incomes' => $incomes,
        ]);
    }
}
