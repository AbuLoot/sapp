@extends('pos.layout')

@section('content')

  <h2 class="page-header">Кассовая книга</h2>

  @include('components.alerts')

  <?php

    $cashModes = [
      'open' => 'Открытие кассы',
      'close' => 'Закрытие кассы',
    ];

  ?>
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
        @forelse($cashDocs as $index => $cashDoc)
          @if(isset($cashDoc->order->mode))
            <tr>
              <th colspan="2">{{ $cashDocType->title }}</th>
              <td>{{ $cashDoc->cashbook->title }}</td>
              <td>{{ $cashDoc->user->name }}</td>
              <td>
              </td>
              <td>{{ $cashDoc->incoming_amount . $currency }}</td>
              <td>{{ $cashDoc->outgoing_amount . $currency }}</td>
              <td>{{ $cashDoc->sum . $currency }}</td>
              <td class="text-center" colspan="3">
                <?php $createdAt = now()->parse($cashDoc->order->created_at); ?>
                <?php $updatedAt = now()->parse($cashDoc->order->updated_at); ?>
                <b>{{ $cashModes[$cashDoc->order->mode] }}:</b> {{ $createdAt->format('Y-m-d').' '.$cashDoc->order->closing_time }}<br>
                <b>Открытие кассы:</b> {{ $updatedAt->format('Y-m-d').' '.$cashDoc->order->opening_time }}
              </td>
            </tr>
          @else
            <tr>
              <td>{{ $cashDoc->order->docType->title ?? null }}</td>
              <td>{{ $cashDoc->order->doc_no }}</td>
              <td>{{ $cashDoc->cashbook->title }}</td>
              <td>{{ $cashDoc->user->name }}</td>
              <td>
                @switch($cashDoc->contractor_type)
                  @case('App\Models\Company')
                    {{ $cashDoc->contractor->title }}
                    @break
                  @case('App\Models\User')
                    {{ $cashDoc->contractor->name.' '.$cashDoc->contractor->lastname }}
                    @break
                @endswitch
              </td>
              <td>{{ $cashDoc->incoming_amount . $currency }}</td>
              <td>{{ $cashDoc->outgoing_amount . $currency }}</td>
              <td>{{ $cashDoc->sum . $currency }}</td>
              <td>{{ $cashDoc->count }}</td>
              <td>{{ $cashDoc->created_at }}</td>
              <td class="text-right">
                <a class="btn btn-link btn-xs" href="{{ route('cashdocs.show', [$lang, $cashDoc->id]) }}" title="Просмотр"><i class="material-icons md-18">file_open</i></a>
              </td>
            </tr>
          @endif
        @empty
          <tr>
            <td colspan="9">No docs</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $cashDocs->links() }}

@endsection
