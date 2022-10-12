@extends('pos.layout')

@section('content')

  <h2 class="page-header">Рабочие места</h2>

  @include('components.alerts')

  <div class="text-right">
    <a href="/{{ $lang }}/pos/workplaces/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
  </div><br>
  <div class="table-responsive">
    <table class="table table-striped table-condensed">
      <thead>
        <tr class="active">
          <td>№</td>
          <td>Имя</td>
          <td>Рабочее место</td>
          <td>Примичание</td>
          <td class="text-right">Функции</td>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        @foreach ($workplaces as $workplace)
          <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $workplace->user->name }}</td>
            <td>{{ $workplace->workplace->title }}</td>
            <td>{{ $workplace->comment }}</td>
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
