<div>
  <?php
    $incomingOrderId = null;
    $outgoingDocId = null;
    $btnCartOrder = 'disabled';

    if (session()->get('docs')) {
      $incomingOrderDocNo = session()->get('docs')['incomingOrderDocNo'];
      $incomingOrderId = session()->get('docs')['incomingOrderId'];
      $outgoingDocId = session()->get('docs')['outgoingDocId'];
      $btnCartOrder = null;
    }
  ?>
  <h2>Чек №{{ $incomingOrderDocNo }}</h2><br>
  <table class="table table-sm- table-striped table-borderless border">
    <thead>
      <tr>
        <th scope="col">Наименование товара</th>
        <th scope="col">Цена</th>
        <th scope="col">Кол-во</th>
        <th scope="col" class="text-end">Итого</th>
      </tr>
    </thead>
    <tbody>
      @forelse($cartProducts as $index => $cartProduct)
        <tr>
          <th>{{ $cartProduct->title }}</th>
          <?php
            $price = (session()->get('priceMode') == 'retail')
              ? $cartProduct->price
              : $cartProduct->wholesale_price;
          ?>
          <td>{{ $price }}</td>
          <td>{{ $cartProduct->countInCart }}</td>
          <td class="text-end">{{ $cartProduct->countInCart * $price }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4">No data</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div class="d-flex">
    <h5>Количество</h5>
    <h5 class="ms-auto">{{ $sumOfCart['totalCount'] }}</h5>
  </div>
  <div class="d-flex">
    <h5>Без скидки</h5>
    <h5 class="ms-auto">{{ $sumOfCart['sumUndiscounted'] . $currency }}</h5>
  </div>
  <div class="d-flex">
    <h4>Итого</h4>
    <h4 class="ms-auto">{{ $sumOfCart['sumDiscounted'] . $currency }}</h4>
  </div>
  <div class="row gx-2">
    <div class="col-lg-6">
      <div class="d-grid" role="group" aria-label="Basic example">
        <a href="/{{ $lang }}/cashdesk/docsprint/outgoing-doc/{{ $outgoingDocId }}" class="btn btn-primary btn-lg {{ $btnCartOrder }}"><i class="bi bi-file-text-fill"></i>&nbsp;Оформить накладную</a>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="d-grid" role="group" aria-label="Basic example">
        <a href="/{{ $lang }}/cashdesk/docsprint/incoming-check/{{ $incomingOrderId }}" class="btn btn-dark btn-lg {{ $btnCartOrder }}"><i class="be bi-printer-fill"></i>&nbsp;Печать<br> чека</a>
      </div>
    </div>
  </div>

</div>