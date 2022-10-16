@extends('pos.layout')

@section('content')

  <h2 class="page-header">Кассовая книга</h2>

  @include('components.alerts')

  <?php 

    $cashModes = [
      'open' => 'Открытие кассы',
      'close' => 'Закрытие кассы'
    ];

  ?>
  <div class="table-responsive">
    <div class="table-responsive">
      <table class="table table-striped table-condensed">
        <thead>
          <tr>
            <th scope="col">Тип документа</th>
            <th scope="col">Номер документа</th>
            <th scope="col">Касса</th>
            <th scope="col">Автор</th>
            <th scope="col">Контрагент</th>
            <th scope="col">Сумма прихода</th>
            <th scope="col">Сумма расхода</th>
            <th scope="col">Итоговая сумма</th>
            <th scope="col">Количество позиции</th>
            <th scope="col">Дата и время</th>
            <th class="text-end" scope="col">Детали</th>
          </tr>
        </thead>
        <tbody>
          @forelse($cashdocs as $index => $cashdoc)
            <tr>
              @if(isset($cashdoc->order->mode))
                <td>{{ $cashModes[$cashdoc->order->mode] }}</td>
              @else
                <td>{{ $cashdoc->order->docType->title ?? null }}</td>
              @endif
              <td>{{ $cashdoc->order->doc_no }}</td>
              <td>{{ $cashdoc->cashbook->title }}</td>
              <td>{{ $cashdoc->user->name }}</td>
              <td>
                @switch($cashdoc->contractor_type)
                  @case('App\Models\Company')
                    {{ $cashdoc->contractor->title }}
                    @break
                  @case('App\Models\User')
                    {{ $cashdoc->contractor->name.' '.$cashdoc->contractor->lastname }}
                    @break
                @endswitch
              </td>
              <td>{{ $cashdoc->incoming_amount }}</td>
              <td>{{ $cashdoc->outgoing_amount }}</td>
              <td>{{ $cashdoc->sum }}</td>
              <td>{{ $cashdoc->count }}</td>
              <td>{{ $cashdoc->created_at }}</td>
              @if(isset($cashdoc->order->mode))
                <td></td>
                @continue
              @endif
              <td class="text-right">
                <a class="btn btn-link btn-xs" href="{{ route('cashdocs.show', [$lang, $cashdoc->id]) }}" title="Редактировать"><i class="material-icons md-18">file_open</i></a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9">No docs</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{ $cashdocs->links() }}

@endsection
