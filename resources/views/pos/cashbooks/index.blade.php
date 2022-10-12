@extends('pos.layout')

@section('content')

  <h2 class="page-header">Кассы</h2>

  @include('components.alerts')

  <div class="text-right">
    <a href="/{{ $lang }}/pos/cashbooks/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
  </div><br>
  <div class="table-responsive">
    <table class="table table-striped table-condensed">
      <thead>
        <tr class="active">
          <td>№</td>
          <td>Название</td>
          <td>Компания</td>
          <td>Регион</td>
          <td>Адрес</td>
          <td>IP address</td>
          <td class="text-right">Функции</td>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        @foreach ($cashbooks as $cashbook)
          <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $cashbook->title }}</td>
            <td>{{ $cashbook->company->title }}</td>
            <td>{{ $cashbook->region->title ?? null }}</td>
            <td>{{ $cashbook->address }}</td>
            <td>{{ $cashbook->ip_address }}</td>
            <td class="text-right">
              <a class="btn btn-link btn-xs" href="{{ route('cashbooks.edit', [$lang, $cashbook->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form method="POST" action="{{ route('cashbooks.destroy', [$lang, $cashbook->id]) }}" accept-charset="UTF-8" class="btn-delete">
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

  {{ $cashbooks->links() }}

@endsection
