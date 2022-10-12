@extends('pos.layout')

@section('content')

  <h2 class="page-header">Склады</h2>

  @include('components.alerts')

  <div class="text-right">
    <a href="/{{ $lang }}/pos/stores/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
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
        @foreach ($stores as $store)
          <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $store->title }}</td>
            <td>{{ $store->company->title }}</td>
            <td>{{ $store->region->title ?? null }}</td>
            <td>{{ $store->address }}</td>
            <td>{{ $store->ip_address }}</td>
            <td class="text-right">
              <a class="btn btn-link btn-xs" href="{{ route('stores.edit', [$lang, $store->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form method="POST" action="{{ route('stores.destroy', [$lang, $store->id]) }}" accept-charset="UTF-8" class="btn-delete">
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

  {{ $stores->links() }}

@endsection
