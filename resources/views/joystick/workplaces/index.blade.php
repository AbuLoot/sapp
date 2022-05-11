@extends('joystick.layout')

@section('content')

  <h2 class="page-header">Рабочие места</h2>

  @include('joystick.partials.alerts')

  <div class="text-right">
    <a href="/{{ $lang }}/admin/workplaces/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
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
        @foreach ($workplaces as $workplace)
          <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $workplace->title }}</td>
            <td>{{ $workplace->company->title }}</td>
            <td>{{ $workplace->account_number }}</td>
            <td>{{ $workplace->bic }}</td>
            <td>{{ $workplace->balance }}</td>
            <td>{{ $workplace->currency }}</td>
            <td class="text-right">
              <a class="btn btn-link btn-xs" href="{{ route('workplaces.edit', [$lang, $workplace->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form method="POST" action="{{ route('workplaces.destroy', [$lang, $workplace->id]) }}" accept-charset="UTF-8" class="btn-delete">
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

  {{ $workplaces->links() }}

@endsection
