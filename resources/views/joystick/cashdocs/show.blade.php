@extends('joystick.layout')

@section('content')

  <h2 class="page-header">{{ $cashdoc->order->docType->title.' - '.$cashdoc->order->doc_no }} </h2>

  <p class="text-right">
    <a href="/{{ $lang }}/admin/cashdocs" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
  </p>

  <div class="table-responsive">
    <table class="table table-striped">
      <tbody>
        <tr>
          <th scope="row">Тип документа</th>
          <td>{{ $cashdoc->order->docType->type }}</td>
        </tr>
        <tr>
          <th scope="row">Название документа</th>
          <td>{{ $cashdoc->order->docType->title }}</td>
        </tr>
        <tr>
          <th scope="row">ID документа</th>
          <td>{{ $cashdoc->doc_id }}</td>
        </tr>
        <tr>
          <th scope="row">Автор</th>
          <td>{{ $cashdoc->user->name }}</td>
        </tr>
        <tr>
          <th scope="row">Контрагент</th>
          <td>{{ $cashdoc->contractor->name ?? $cashdoc->contractor->title ?? 'No name' }}</td>
        </tr>
        <tr>
          <th scope="row">Сумма прихода</th>
          <td>{{ $cashdoc->incoming_amount }}</td>
        </tr>
        <tr>
          <th scope="row">Сумма расхода</th>
          <td>{{ $cashdoc->outgoing_amount }}</td>
        </tr>
        <tr>
          <th scope="row">Количество</th>
          <td>{{ $cashdoc->count }}</td>
        </tr>
        <tr>
          <th scope="row">Дата и время</th>
          <td>{{ $cashdoc->created_at }}</td>
        </tr>
        <tr>
          <th scope="row">Комментарии</th>
          <td>{{ $cashdoc->comment }}</td>
        </tr>
      </tbody>
    </table>

    <?php 

    if ($cashdoc->order_type == 'App\Models\IncomingOrder' AND $cashdoc->doc_id) :
      $concomitantDoc = App\Models\OutgoingDoc::find($cashdoc->doc_id);
      $productsData = json_decode($concomitantDoc->products_data, true);
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
