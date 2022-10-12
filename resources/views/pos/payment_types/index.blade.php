@extends('pos.layout')

@section('content')

  <h2 class="page-header">Виды оплаты</h2>

  @include('components.alerts')

  <div class="text-right">
    <a href="/{{ $lang }}/pos/payment_types/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
  </div><br>
  <div class="table-responsive">
    <table class="table table-striped table-condensed">
      <thead>
        <tr class="active">
          <td>№</td>
          <td>Картинка</td>
          <td>Slug</td>
          <td>Название</td>
          <td>Язык</td>
          <td class="text-right">Функции</td>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        @foreach ($payment_types as $payment_type)
          <tr>
            <td>{{ $i++ }}</td>
            <!-- <td><img src="/" class="img-responsive" style="width:80px;height:auto;"></td> -->
            <td>{{ $payment_type->image }}</td>
            <td>{{ $payment_type->slug }}</td>
            <td>{{ $payment_type->title }}</td>
            <td>{{ $payment_type->lang }}</td>
            <td class="text-right">
              <a class="btn btn-link btn-xs" href="{{ route('payment_types.edit', [$lang, $payment_type->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form method="POST" action="{{ route('payment_types.destroy', [$lang, $payment_type->id]) }}" accept-charset="UTF-8" class="btn-delete">
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

  {{ $payment_types->links() }}

@endsection
