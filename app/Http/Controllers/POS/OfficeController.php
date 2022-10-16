<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\POS\Controller;
use Illuminate\Http\Request;

use DB;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\IncomingOrder;
use App\Models\OutgoingOrder;
use App\Models\CashDoc;

class OfficeController extends Controller
{
    public function index()
    {
        $operationCodes = [
            // Storage operations
            'incoming-products' => 'Приход продуктов',
            'writeoff-products' => 'Списание продуктов',

            // Cashdesk operations
            'incoming-cash' => 'Приход в кассу',
            'outgoing-cash' => 'Расход из кассы',
            'returned-products' => 'Возврат продуктов',
            'repayment-debt' => 'Погашение долгов',
            'payment-products' => 'Оплата продуктов',
            'sale-on-credit' => 'Продажа в долг',
        ];

        $previousYear = now()->subYear()->format('Y').'-01-01';

        $incomes = IncomingOrder::query()
            ->where('created_at', '>', $previousYear)
            ->where('created_at', '<=', now())
            ->get();

        return view('pos.office.index', [
            'incomes'   => $incomes,
            'countUsers'    => User::count(),
            'countStores'   => Store::count(),
            'products' => Product::get(),
        ]);
    }
}
