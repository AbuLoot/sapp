@extends('pos.layout')

@section('content')

  <h2 class="page-header">Скидки</h2>

  @include('components.alerts')

  <div class="text-right">
    <a href="/{{ $lang }}/pos/discounts/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
  </div><br>
  <div class="table-responsive">
    <table class="table table-striped table-condensed">
      <thead>
        <tr class="active">
          <td>№</td>
          <td>Процент</td>
          <td>Категория</td>
          <td>Начало срока</td>
          <td>Конец срока</td>
          <td>Сумма</td>
          <td class="text-right">Функции</td>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        @foreach ($discounts as $discount)
          <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $discount->percent }}</td>
            <td>{{ $discount->category->title }}</td>
            <td>{{ $discount->start_date }}</td>
            <td>{{ $discount->end_date }}</td>
            <td>{{ $discount->sum }}</td>
            <td class="text-right">
              <a class="btn btn-link btn-xs" href="{{ route('discounts.edit', [$lang, $discount->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form method="POST" action="{{ route('discounts.destroy', [$lang, $discount->id]) }}" accept-charset="UTF-8" class="btn-delete">
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

  {{ $discounts->links() }}

@endsection
