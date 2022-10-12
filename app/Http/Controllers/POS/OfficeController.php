<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;

use DB;

use App\Models\Company;
use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\CashDoc;

class OfficeController extends Controller
{
    public function index()
    {
        $now = now();
        $today = $now->format('Y-m-d');
        $startMonth = $now->format('Y-m').'-01';
        $previousMonth = $now->subMonth()->format('Y-m').'-30';
        $currentYear = $now->format('Y-m-d');
        $previousYear = $now->subYear()->format('Y').'-01-01';

        echo ' today: '. $today.'<br>';
        echo ' startMonth: '. $startMonth.'<br>';
        echo ' previousMonth: '. $previousMonth.'<br>';
        echo ' currentYear: '. $currentYear.'<br>';
        echo ' previousYear: '. $previousYear.'<br>';

        // $revenueToday = IncomingOrder::
            // selectRaw('SUM(sum) as revenue_today')
            // whereDate('created_at', $startMonth)
            // ->get();

        $orders = DB::table('incoming_orders')
            ->select('created_at', DB::raw('SUM(sum) as revenue_sum'))
            ->groupBy('created_at')
            ->where("created_at", '>', now()->format('Y-m').'-01')
            ->where('created_at', '<=', $today)
            // ->havingRaw('SUM(workplace_id) > 1', [2])
            ->get();

        $revenue = DB::table('incoming_orders')
            ->select('doc_no', 'sum', 'created_at')
            // ->selectRaw("CASE WHEN DATE(created_at) > $startMonth AND DATE(created_at) <= $today THEN sum ELSE 0 END AS revenue_for_today")
            ->when('SUM(sum) as revenue_today', function($query) {
                $query->where("created_at", '>', now()->format('Y-m').'-01')
                    ->where('created_at', '<=', now()->format('Y-m-d'));
            })
            ->when('SUM(sum) as revenue_today', function($query) {
                $query->where("created_at", '>', now()->format('Y-m').'-01')
                    ->where('created_at', '<=', now()->format('Y-m-d'));
            })
            ->get();

        dd($orders, $revenue);
        $report = DB::table('incoming_orders')
            ->select('doc_type_id', 'doc_no as docNO', 'created_at')
            // ->groupBy('doc_type_id')
            // ->havingRaw('SUM(sum) > ?', [1000])
            ->where("created_at", '>=', $previousMonth)
            // ->where('created_at', $startMonth)
            ->get();

        $revenue = DB::table('incoming_orders')
            ->select('doc_no', 'sum', 'created_at')
            ->selectRaw("CASE WHEN DATE(created_at) = $previousMonth THEN sum ELSE 0 END AS revenue_for_today")
            ->get();

        // $revenue = DB::table('incoming_orders')
        // ->raw("
            // SUM(CASE WHEN created_at = $today THEN 'sum' ELSE 0 END) AS revenue_for_today
            // SUM(CASE WHEN created_at = $currentMonth THEN sum ELSE 0 END) AS revenue_for_month,
            // SUM(CASE WHEN created_at = $previousMonth THEN sum ELSE 0 END) AS revenue_for_prev_month,
            // SUM(CASE WHEN created_at = $previousYear THEN sum ELSE 0 END) AS revenue_for_year
            // ");
        // ->get();
        dd($revenue);

        return view('pos.office.index', compact('revenue'));
    }

    public function edit($lang, $id)
    {

    }

    public function destroy($lang, $id)
    {

        return redirect($lang.'/pos/users')->with('status', 'Запись удалена.');
    }
}
