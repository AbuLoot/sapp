@extends('pos.layout')

@section('content')

  <h2 class="page-header">Статистика по базе</h2>

  @include('components.alerts')

  <?php 
    $company = auth()->user()->company;
    $currency = $company->currency->symbol ?? null;

    // Dates
    $yesterday = now()->subDay(1)->format('Y-m-d');
    $today = now();
    $previousWeek = now()->startOfWeek()->subWeek(1);
    $startOfWeek = now()->startOfWeek();
    $endOfWeek = now()->endOfWeek();
    $previousMonth = now()->subMonth()->format('Y-m').'-01';
    $startOfMonth = now()->format('Y-m').'-01';
    $previousYear = now()->subYear()->format('Y').'-01-01';
    $startOfYear = now()->format('Y').'-01-01';

    // Revenues info
    $revenueForToday     = number_format($incomes->where('created_at', '>', $yesterday.' 23:59:59')->where('created_at', '<=', now())->sum('sum'), 0, '.', ' ');
    $revenueForYesterday = number_format($incomes->where('created_at', '>', $yesterday)->where('created_at', '<', $today)->sum('sum'), 0, '.', ' ');
    $revenueForPrevWeek  = number_format($incomes->where('created_at', '>', $previousWeek)->where('created_at', '<=', $startOfWeek)->sum('sum'), 0, '.', ' ');
    $revenueForWeek      = number_format($incomes->where('created_at', '>', $startOfWeek)->where('created_at', '<=', $endOfWeek)->sum('sum'), 0, '.', ' ');
    $revenueForMonth     = number_format($incomes->where('created_at', '>', $startOfMonth)->where('created_at', '<=', $today)->sum('sum'), 0, '.', ' ');
    $revenueForPrevMonth = number_format($incomes->where('created_at', '>', $previousMonth)->where('created_at', '<', $startOfMonth)->sum('sum'), 0, '.', ' ');
    $revenueForYear      = number_format($incomes->where('created_at', '>', $startOfYear)->where('created_at', '<=', $today)->sum('sum'), 0, '.', ' ');
    $revenueForPrevYear  = number_format($incomes->where('created_at', '>', $previousYear)->where('created_at', '<', $startOfYear)->sum('sum'), 0, '.', ' ');

    // Products info
    $countProducts = $products->count();
    $sumPurchasePrice = number_format($products->sum('purchase_price'), 0, '.', ' ');
    $sumWholesalePrice = number_format($products->sum('wholesale_price'), 0, '.', ' ');
    $sumPrice = number_format($products->sum('price'), 0, '.', ' ');
  ?>

  <div class="row">
    <div class="col-md-6">
      <div class="well">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Время</th>
              <th class="text-right">Сумма</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>Выручка за сегодня</th>
              <td class="text-right">{{ $revenueForToday . $currency }}</td>
            </tr>
            <tr>
              <th>Выручка за вчера</th>
              <td class="text-right">{{ $revenueForYesterday . $currency }}</td>
            </tr>
            <tr>
              <th>Выручка за неделю</th>
              <td class="text-right">{{ $revenueForWeek . $currency }}</td>
            </tr>
            <tr>
              <th>Выручка за прошлую неделю</th>
              <td class="text-right">{{ $revenueForPrevWeek . $currency }}</td>
            </tr>
            <tr>
              <th>Выручка за месяц</th>
              <td class="text-right">{{ $revenueForMonth . $currency }}</td>
            </tr>
            <tr>
              <th>Выручка за прошлый месяц</th>
              <td class="text-right">{{ $revenueForPrevMonth . $currency }}</td>
            </tr>
            <tr>
              <th>Выручка за год</th>
              <td class="text-right">{{ $revenueForYear . $currency }}</td>
            </tr>
            <tr>
              <th>Выручка за прошлый год</th>
              <td class="text-right">{{ $revenueForPrevYear . $currency }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-6">
      <div class="well">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Склад</th>
              <th class="text-right">Сумма</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>Сумма продуктов по розничной цене</th>
              <td class="text-right">{{ $sumPrice . $currency }}</td>
            </tr>
            <tr>
              <th>Сумма продуктов по оптовой цене</th>
              <td class="text-right">{{ $sumWholesalePrice . $currency }}</td>
            </tr>
            <tr>
              <th>Сумма продуктов по закупочной цене</th>
              <td class="text-right">{{ $sumPurchasePrice . $currency }}</td>
            </tr>
          </tbody>
        </table>
      </div> 
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="well">
        <table class="table table-striped">
          <thead>
            <tr>
              <th></th>
              <th class="text-center">Кол-во</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>Пользователей</th>
              <td class="text-center">{{ $countUsers }}</td>
            </tr>
            <tr>
              <th>Складов</th>
              <td class="text-center">{{ $countStores }}</td>
            </tr>
            <tr>
              <th>Продуктов</th>
              <td class="text-center">{{ $countProducts }}</td>
            </tr>
          </tbody>
        </table>
      </div> 
    </div>
  </div>

@endsection
