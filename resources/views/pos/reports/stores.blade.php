@extends('pos.layout')

@section('content')

  <h2 class="page-header">Отчет о нулевых остатках продуктов</h2>

  @include('components.alerts')

  <?php 
    $company = auth()->user()->profile->company;
    $currency = $company->currency->symbol ?? null;
  ?>

  <h4>Продуктов с нулевым остатком: {{ $products->count() }}</h4>

  <div class="table-responsive table-products">
    <table class="table data table-striped table-condensed table-hover">
      <thead>
        <tr class="active">
          <td>ID</td>
          <td>Название</td>
          <td>Категории</td>
          <td>Компания</td>
          <td>Штрихкод</td>
          <td class="text-right text-nowrap">Кол-во</td>
          <td class="text-right">Цена</td>
          <td class="text-right">Функции</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($products as $product)
          <tr>
            <td>{{ $product->id }}</td>
            <td class="cell-size">{{ $product->title }}</td>
            <td>{{ $product->category->title }}</td>
            <td>{{ ($product->company) ? $product->company->title : '' }}</td>
            <td>{{ $product->barcodes }}</td>
            <td class="text-right">{{ $product->count }}</td>
            <td class="text-right">{{ $product->price . $currency }}</td>
            <td class="text-right text-nowrap">
              <!-- <a class="btn btn-link btn-xs" href="/p/{{ $product->slug }}" title="Просмотр товара" target="_blank"><i class="material-icons md-18">link</i></a> -->
              <a class="btn btn-link btn-xs" href="{{ route('products.edit', [$lang, $product->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form class="btn-delete" method="POST" action="{{ route('products.destroy', [$lang, $product->id]) }}" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-link btn-xs" onclick="return confirm('Удалить запись?')"><i class="material-icons md-18">clear</i></button>
              </form>
            </td>
            <th class="fix-col">
              <a class="btn btn-link btn-xs btn-fix-col" href="{{ route('products.edit', [$lang, $product->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form class="btn-delete btn-fix-col" method="POST" action="{{ route('products.destroy', [$lang, $product->id]) }}" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-link btn-xs" onclick="return confirm('Удалить запись?')"><i class="material-icons md-18">clear</i></button>
              </form>
            </th>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{ $products->links() }}

@endsection
