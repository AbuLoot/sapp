@extends('pos.layout')

@section('content')

  <h2 class="page-header">{{ $cashDoc->order->docType->title.' - '.$cashDoc->order->doc_no }} </h2>

  <div class="row">
    <div class="col-md-6">
    @switch($cashDoc->order_type)
      @case('App\Models\IncomingOrder')
        <a href="/{{ $lang }}/cashdesk/docsprint/incoming-order/{{ $cashDoc->order_id }}" class="btn btn-primary"><i class="material-icons md-18">print</i> Печать документа</a>
        @break
      @case('App\Models\OutgoingOrder')
        <a href="/{{ $lang }}/cashdesk/docsprint/outgoing-order/{{ $cashDoc->order_id }}" class="btn btn-primary"><i class="material-icons md-18">print</i> Печать документа</a>
        @break
    @endswitch
    </div>
    <div class="col-md-6 text-right">
      <a href="/{{ $lang }}/pos/cashdocs" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
    </div>
  </div>
  <br>

  <div class="table-responsive">
    <table class="table table-striped">
      <tbody>
        <tr>
          <th scope="row">Тип документа</th>
          <td>{{ $cashDoc->order->docType->type }}</td>
        </tr>
        <tr>
          <th scope="row">Название документа</th>
          <td>{{ $cashDoc->order->docType->title }}</td>
        </tr>
        <tr>
          <th scope="row">ID документа</th>
          <td>{{ $cashDoc->doc_id }}</td>
        </tr>
        <tr>
          <th scope="row">Тип операции</th>
          <td>{{ __('operation-codes.'.$cashDoc->order->operation_code) }}</td>
        </tr>
        <tr>
          <th scope="row">Автор</th>
          <td>{{ $cashDoc->user->name }}</td>
        </tr>
        <tr>
          <th scope="row">Контрагент</th>
          <td>{{ $cashDoc->contractor->name ?? $cashDoc->contractor->title ?? 'No name' }}</td>
        </tr>
        <tr>
          <th scope="row">Сумма прихода</th>
          <td>{{ $cashDoc->incoming_amount }}</td>
        </tr>
        <tr>
          <th scope="row">Сумма расхода</th>
          <td>{{ $cashDoc->outgoing_amount }}</td>
        </tr>
        <tr>
          <th scope="row">Количество</th>
          <td>{{ $cashDoc->count }}</td>
        </tr>
        <tr>
          <th scope="row">Дата и время</th>
          <td>{{ $cashDoc->created_at }}</td>
        </tr>
        <tr>
          <th scope="row">Комментарии</th>
          <td>{{ $cashDoc->comment }}</td>
        </tr>
      </tbody>
    </table>

    <?php 

    if ($cashDoc->order_type == 'App\Models\IncomingOrder' AND $cashDoc->doc_id) :
      $productsData = json_decode($cashDoc->order->products_data, true);
      $productsKeys = collect($productsData)->keys();
      $docProducts = App\Models\Product::whereIn('id', $productsKeys->all())->get();
    ?>
      <table class="table table-striped">
        <thead>
          <tr  class="align-items-start">
            <th scope="col">Наименование товара</th>
            <th scope="col">Штрихкод</th>
            <th scope="col">Категория</th>
            <th scope="col">Цена закупки</th>
            <th scope="col">Цена продажи</th>
            <th scope="col">Общее Кол.</th>
            <th scope="col">Поставщик</th>
          </tr>
        </thead>
        <tbody>
          @forelse($docProducts as $index => $product)
            <tr>
              <td><a href="/{{ $lang }}/storage/edit-product/{{ $product->id }}">{{ $product->title }}</a></td>
              <td>
                <?php $barcodes = json_decode($product->barcodes, true) ?? ['']; ?>
                @foreach($barcodes as $barcode)
                  {{ $barcode }}<br>
                @endforeach
              </td>
              <td>{{ $product->category->title }}</td>
              <td>{{ $product->purchase_price }}</td>
              <td>{{ $product->price }}</td>
              <td>{{ $product->count }}</td>
              <td>{{ $product->company->title }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="9">No products</td>
            </tr>
          @endforelse
        </tbody>
      </table>

    <?php endif; ?>
  </div>
@endsection
