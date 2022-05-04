@extends('joystick.layout')

@section('content')

  <h2 class="page-header">Склады</h2>

  @include('joystick.partials.alerts')

  <div class="text-right">
    <a href="/{{ $lang }}/admin/storages/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
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
        @foreach ($storages as $storage)
          <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $storage->title }}</td>
            <td>{{ $storage->company->title }}</td>
            <td>{{ $storage->region->title ?? null }}</td>
            <td>{{ $storage->address }}</td>
            <td>{{ $storage->ip_address }}</td>
            <td class="text-right">
              <a class="btn btn-link btn-xs" href="{{ route('storages.edit', [$lang, $storage->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form method="POST" action="{{ route('storages.destroy', [$lang, $storage->id]) }}" accept-charset="UTF-8" class="btn-delete">
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

  {{ $storages->links() }}

@endsection
