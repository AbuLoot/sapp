@extends('pos.layout')

@section('content')

  <h2 class="page-header">Финансовый отчет</h2>

  @include('components.alerts')

  <?php 
    $company = auth()->user()->profile->company;
    $currency = $company->currency->symbol ?? null;

    // Cash Operations Info
    $sumOfPayments = number_format($incomes->where('operation_code', 'payment-products')->sum('sum'), 0, '.', ' ');
    $sumOfIncoming = number_format($incomes->where('operation_code', 'incoming-cash')->sum('sum'), 0, '.', ' ');

    $sumOfOutgoing = number_format($outflow->where('operation_code', 'outgoing-cash')->sum('sum'), 0, '.', ' ');
    $sumOfReturned = number_format($outflow->where('operation_code', 'returned-products')->sum('sum'), 0, '.', ' ');

    $sumOfCost = 0;
    $sumOfProfit = 0;

    foreach ($incomes as $income) {

      $productsData = json_decode($income->products_data, true);
      $productsKeys = collect($productsData)->keys();

      $sumOfProfit += collect($productsData)->sum('price');

      $productsBase = App\Models\Product::query()
        ->whereIn('id', $productsKeys->all())
        ->select('id', 'purchase_price', 'wholesale_price', 'price')
        ->get();

      $sumOfCost += collect($productsBase)->sum('purchase_price');
    }
  ?>

  <form action="/{{ $lang }}/pos/report-financial" method="get">
    {!! csrf_field() !!}
    <div class="row">
      <div class="col-md-3">
        <div class="form-group input-group">
          <span class="input-group-addon" id="basic-addon1">От</span>
          <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" data-date-format="yyyy-mm-dd">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group input-group">
          <span class="input-group-addon" id="basic-addon1">До</span>
          <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" data-date-format="yyyy-mm-dd">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <button type="reset" class="btn btn-default">Сбросить</button>
          <button type="submit" class="btn btn-primary">Поиск</button>
        </div>
      </div>
    </div>
  </form>

  <h4>Статистика от {{ $startDate }} до {{ $endDate }}</h4>
  <div class="row">
    <div class="col-md-6">
      <div class="well">
        <table class="table table-striped">
          <thead>
            <tr>
              <th></th>
              <th class="text-right">Сумма</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>Продажы</th>
              <td class="text-right">{{ $sumOfPayments . $currency }}</td>
            </tr>
            <tr>
              <th>Внесения</th>
              <td class="text-right">{{ $sumOfIncoming . $currency }}</td>
            </tr>
            <tr>
              <th>Расходы</th>
              <td class="text-right">{{ $sumOfOutgoing . $currency }}</td>
            </tr>
            <tr>
              <th>Возвраты</th>
              <td class="text-right">{{ $sumOfReturned . $currency }}</td>
            </tr>
            <tr>
              <th>Себестоимость</th>
              <td class="text-right">{{ $sumOfCost . $currency }}</td>
            </tr>
            <tr>
              <th>Прибыль</th>
              <td class="text-right">{{ $sumOfProfit . $currency }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>

@endsection
