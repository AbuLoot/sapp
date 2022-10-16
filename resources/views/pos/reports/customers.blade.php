@extends('pos.layout')

@section('content')

  <h2 class="page-header">Отчет по клиентам</h2>

  @include('components.alerts')

  <form action="/{{ $lang }}/pos/report-customers" method="get">
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
  <?php 
    $company = auth()->user()->profile->company;
    $currency = $company->currency->symbol ?? null;

    $groupedCustomers = $customers->groupBy('id');

  ?>

  <h4>Статистика от {{ $startDate }} до {{ $endDate }}</h4>
  <div class="row">
    <div class="col-md-8">
      <div class="well">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Имя</th>
              <th class="text-right">Сумма задолжности</th>
              <th class="text-right">Сумма покупок</th>
              <th class="text-right">Процент скидки</th>
              <th class="text-right"></th>
            </tr>
          </thead>
          <tbody>
            @foreach($groupedCustomers as $key => $customer)
              <?php $user = $customer->first(); ?>
              <tr>
                <th>{{ $key }}</th>
                <td>{{ $user->name }} {{ $user->lastname }}</td>
                <td class="text-right">{{ number_format($user->profile->debt_sum, 0, '.', ' ') . $currency }}</td>
                <td class="text-right">{{ number_format($customer->sum('sum'), 0, '.', ' ') . $currency }}</td>
                <td class="text-right">{{ $user->profile->discount }}%</td>
                <td class="text-right"></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>

@endsection
