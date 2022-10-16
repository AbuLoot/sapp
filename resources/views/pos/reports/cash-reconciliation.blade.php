@extends('pos.layout')

@section('content')

  <h2 class="page-header">Сверка кассы</h2>

  @include('components.alerts')

  <form action="/{{ $lang }}/pos/cash-reconciliation" method="get">
    {!! csrf_field() !!}
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="cashier_id">Кассиры</label>
          <select id="cashier_id" name="cashier_id" class="form-control">
            <option>Выберите сотрудника</option>
            @foreach($cashiers as $cashier)
              <option value="{{ $cashier->id }}">{{ $cashier->name }} {{ $cashier->lastname }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Поиск</button>
        </div>
      </div>
    </div>
  </form>

  <?php 
    $company = auth()->user()->profile->company;
    $currency = $company->currency->symbol ?? null;
  ?>

  <div class="row">
    <div class="col-md-8">
      <div class="well">
        @if($cashierObj)
          <table class="table table-striped">
            <thead>
              <tr>
                <th>{{ $cashier->name }} {{ $cashier->lastname }}</th>
                <td class="text-right"></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th>Зафиксированная сумма продажи</th>
                <td class="text-right">{{ $incomes->sum('sum') . $currency }}</td>
              </tr>
              <tr>
                <th>Расчетная сумма продажи</th>
                <td class="text-right">{{ $cashShiftJournal->sum('sum') . $currency }}</td>
              </tr>
              <tr>
                <th>Фактическая сумма продажи</th>
                <td class="text-right"></td>
              </tr>
            </tbody>
          </table>
        @else
          <p>No user</p>
        @endif
      </div>
    </div>

  </div>

@endsection
