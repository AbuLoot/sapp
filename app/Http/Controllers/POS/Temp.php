<?php

$report = DB::table('orders')
            ->selectRaw('count(id) as number_of_orders, customer_id')
            ->groupBy('customer_id')
            ->havingBetween('number_of_orders', [5, 15])
            ->get();

$users = DB::table('users')
            ->select(DB::raw('count(*) as user_count, status'))
            ->where('status', '<>', 1)
            ->groupBy('status')
            ->get();

$orders = DB::table('orders')
					->select('department', DB::raw('SUM(price) as total_sales'))
					->groupBy('department')
					->havingRaw('SUM(price) > ?', [2500])
					->get();

$orders = DB::table('orders')
					->selectRaw('price * ? as price_with_tax', [1.0825])
					->get();

$orders = DB::table('orders')
						->whereRaw('price > IF(state = "TX", ?, 100)', [200])
						->get();

$latestPosts = DB::table('posts')
								->select('user_id', DB::raw('MAX(created_at) as last_post_created_at'))
								->where('is_published', true)
								->groupBy('user_id');
 
$users = DB::table('users')
		        ->joinSub($latestPosts, 'latest_posts', function ($join) {
		            $join->on('users.id', '=', 'latest_posts.user_id');
		        })->get();

$role = $request->input('role');
 
$users = DB::table('users')
            ->when($role, function ($query, $role) {
                $query->where('role_id', $role);
            })->get();

$sortByVotes = $request->input('sort_by_votes');
 
$users = DB::table('users')
            ->when($sortByVotes, function ($query, $sortByVotes) {
                $query->orderBy('votes');
            }, function ($query) {
                $query->orderBy('name');
            })->get();

$sql =
"SELECT cu.NIN, cu.first_name, cu.last_name,
	SUM(ct.amount) AS total_revenue_per_customer,
	CASE
	    WHEN SUM(ct.amount) >= 1000000 THEN 'Platinum'
	    WHEN SUM(ct.amount) < 1000000 THEN 'Gold'
	END AS customer_category,
	SUM(CASE WHEN ct.date >= '2019-01-01' AND ct.date < '2020-01-01' THEN ct.amount ELSE 0 END) AS revenue_2019,
	SUM(CASE WHEN ct.date >= '2020-01-01' AND ct.date < '2021-01-01' THEN ct.amount ELSE 0 END) AS revenue_2020
	FROM card_transaction ct
	JOIN card_number cn ON ct.card_number_id = cn.id
	JOIN customer cu ON cn.customer_id = cu.id
	GROUP BY cu.NIN, cu.first_name, cu.last_name
	ORDER BY total_revenue_per_customer DESC;";

$invoice = Invoice::select(\DB::raw('ledgers.id,invoices.invoice_no,invoices.invoice_date,invoices.invoice_due,invoices.invoice_balance,DATEDIFF("'.$request->input('to_date').'", invoices.invoice_date) AS days_past_due'))
			->join('quotations','quotations.id','=','invoices.quotation_id')
			->join('ledgers','ledgers.id','=','quotations.ledger_id')
			->where('invoices.invoice_date','<=',$request->input('to_date'))
			->whereNotIn('invoices.status',[1,2]);

$agingReport =
Ledger::select(
	\DB::raw('a.id,ledgers.name,a.invoice_no,a.invoice_date,a.invoice_due,a.invoice_balance,a.days_past_due,
		CASE WHEN a.days_past_due =0 then a.invoice_balance else 0 end as month,
		CASE WHEN a.days_past_due >0 and a.days_past_due <= 30 then a.invoice_balance else 0 end as aging30days,
		CASE WHEN a.days_past_due >30 and a.days_past_due <= 60 then a.invoice_balance else 0 end as aging60days,
		CASE WHEN a.days_past_due >60 and a.days_past_due <= 90 then a.invoice_balance else 0 end as aging90days,
		CASE WHEN a.days_past_due >90 then a.invoice_balance else 0 end as more30days')
		)
		->from(\DB::raw('('.$invoice->toSql().') as a '))
		->mergeBindings($invoice->getQuery())
		->join('ledgers','ledgers.id','=','a.id')
		->get();

return response()->json($agingReport);

$sql =
"SELECT DISTINCT (ct.date), cty.card_type_name,
    SUM (ct.amount) OVER (PARTITION BY cty.card_type_name ORDER BY ct.date ASC) AS transaction_running_total
	FROM card_transaction ct
	JOIN card_number cn ON ct.card_number_id = cn.id
	JOIN card_type cty ON cn.card_type_id = cty.id
	WHERE date > '2020-11-30' AND date <= '2020-12-31'
	ORDER BY cty.card_type_name ASC";

$sql = 
'SELECT 
  report_categories.employee_id,
  report_categories.first_name,
  report_categories.last_name,
  report_categories.customer_id,
  report_categories.customer_name,
  report_data.calls
FROM
(
  -- report categories
  SELECT
    employee.id AS employee_id,
    employee.first_name,
    employee.last_name,
    customer.id AS customer_id,
    customer.customer_name 
  FROM employee
  CROSS JOIN customer
) report_categories
 
LEFT JOIN
 
(
  -- report data
  SELECT 
    employee.id AS employee_id, 
    customer.id AS customer_id,
    COUNT(call.id) AS calls
  FROM employee
  INNER JOIN call ON call.employee_id = employee.id
  INNER JOIN customer ON call.customer_id = customer.id
  GROUP BY 
    employee.id, 
    customer.id
) report_data ON report_categories.employee_id = report_data.employee_id
	AND report_categories.customer_id = report_data.customer_id';
