@extends('pos.layout')

@section('content')

  <h2 class="page-header">Статистика по базе</h2>

  @include('components.alerts')

  <?php 
    $company = auth()->user()->profile->company;
    $currency = $company->currency->symbol;

    // Dates
    $yesterday = now()->subDay(1);
    $today = now()->format('Y-m-d');
    $previousWeek = now()->startOfWeek()->subWeek(1);
    $startWeek = now()->startOfWeek();
    $previousMonth = now()->subMonth()->format('Y-m').'-01';
    $startMonth = now()->format('Y-m').'-01';
    $previousYear = now()->subYear()->format('Y').'-01-01';
    $startYear = now()->format('Y').'-01-01';

    // Revenues info
    $revenueForYesterday = number_format($incomes->where('created_at', '>', $yesterday)->where('created_at', '<', $today)->sum('sum'), 0, '.', ' ');
    $revenueForWeek      = number_format($incomes->where('created_at', '>', $startWeek)->where('created_at', '<=', $today)->sum('sum'), 0, '.', ' ');
    $revenueForMonth     = number_format($incomes->where('created_at', '>', $startMonth)->where('created_at', '<=', $today)->sum('sum'), 0, '.', ' ');
    $revenueForPrevMonth = number_format($incomes->where('created_at', '>', $previousMonth)->where('created_at', '<', $startMonth)->sum('sum'), 0, '.', ' ');
    $revenueForYear      = number_format($incomes->where('created_at', '>', $startYear)->where('created_at', '<=', $today)->sum('sum'), 0, '.', ' ');
    $revenueForPrevYear  = number_format($incomes->where('created_at', '>', $previousYear)->where('created_at', '<', $startYear)->sum('sum'), 0, '.', ' ');

    // Products info
    $countProducts = $products->count();
    $sumPurchasePrice = number_format($products->sum('purchase_price'), 0, '.', ' ');
    $sumWholesalePrice = number_format($products->sum('wholesale_price'), 0, '.', ' ');
    $sumPrice = number_format($products->sum('price'), 0, '.', ' ');
  ?>

  <div class="row">
    <div class="col-md-4">
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
              <th>Выручка за вчера</th>
              <td class="text-right">{{ $revenueForYesterday . $currency }}</td>
            </tr>
            <tr>
              <th>Выручка за неделю</th>
              <td class="text-right">{{ $revenueForWeek . $currency }}</td>
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
    <div class="col-md-3">
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
    <div class="col-md-5">
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

@endsection
