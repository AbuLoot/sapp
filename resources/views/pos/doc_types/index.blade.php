@extends('pos.layout')

@section('content')

  <h2 class="page-header">Виды документов</h2>

  @include('components.alerts')

  <div class="text-right">
    <a href="/{{ $lang }}/pos/doc_types/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
  </div><br>
  <div class="table-responsive">
    <table class="table table-striped table-condensed">
      <thead>
        <tr class="active">
          <td>№</td>
          <td>Тип</td>
          <td>Slug</td>
          <td>Название</td>
          <td>Язык</td>
          <td class="text-right">Функции</td>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        @foreach ($doc_types as $doc_type)
          <tr>
            <td>{{ $i++ }}</td>
            <td class="text-nowrap">{{ $doc_type->type }}</td>
            <td>{{ $doc_type->slug }}</td>
            <td>{{ $doc_type->title }}</td>
            <td>{{ $doc_type->lang }}</td>
            <td class="text-right">
              <a class="btn btn-link btn-xs" href="{{ route('doc_types.edit', [$lang, $doc_type->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form method="POST" action="{{ route('doc_types.destroy', [$lang, $doc_type->id]) }}" accept-charset="UTF-8" class="btn-delete">
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

  {{ $doc_types->links() }}

@endsection
