@extends('pos.layout')

@section('content')

  <h2 class="page-header">Счета</h2>

  @include('components.alerts')

  <div class="text-right">
    <a href="/{{ $lang }}/pos/bank_accounts/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
  </div><br>
  <div class="table-responsive">
    <table class="table table-striped table-condensed">
      <thead>
        <tr class="active">
          <td>№</td>
          <td>Название банка</td>
          <td>Компания</td>
          <td>Номер счета</td>
          <td>БИК</td>
          <td>Баланс</td>
          <td>Валюта</td>
          <td class="text-right">Функции</td>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        @foreach ($bank_accounts as $bank_account)
          <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $bank_account->title }}</td>
            <td>{{ $bank_account->company->title }}</td>
            <td>{{ $bank_account->account_number }}</td>
            <td>{{ $bank_account->bic }}</td>
            <td>{{ $bank_account->balance }}</td>
            <td>{{ $bank_account->currency }}</td>
            <td class="text-right">
              <a class="btn btn-link btn-xs" href="{{ route('bank_accounts.edit', [$lang, $bank_account->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form method="POST" action="{{ route('bank_accounts.destroy', [$lang, $bank_account->id]) }}" accept-charset="UTF-8" class="btn-delete">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-link btn-xs" onclick="return confirm('Удалить запись?')"><i class="material-icons md-18">clear</i></button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{ $bank_accounts->links() }}

@endsection
