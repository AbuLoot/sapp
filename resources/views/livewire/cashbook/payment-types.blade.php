<div>
  <?php $currency = $company->currency->symbol; ?>
  <main class="container my-4">
    <div class="row">
      <div class="col-lg-5">
        <h2>Чек №{{ $docNo }}</h2><br>
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
                  if (session()->get('priceMode') == 'retail') {
                    $price = $cartProduct->price;
                  } else {
                    $price = $cartProduct->wholesale_price;
                  }
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
              <button type="button" class="btn btn-primary btn-lg"><i class="bi bi-file-text-fill"></i>&nbsp;Оформить накладную</button>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-grid" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-dark btn-lg"><i class="be bi-printer-fill"></i>&nbsp;Печать<br> чека</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 offset-1">
        @if($view == false)
          <div class="d-flex">
            <h2>Виды оплаты</h2>
            <button wire:click="backToCash" type="button" class="btn btn-outline-dark btn-lg ms-auto">Закрыть</button>
          </div>
          <br>
          <div class="d-flex">
            <h4>Итого</h4>
            <h4 class="ms-auto">{{ $sumOfCart['sumDiscounted'] . $company->currency->symbol }}</h4>
          </div>
          <div class="row gx-2 gy-2">
            @foreach($paymentTypes as $paymentType)
              <div class="col-lg-6 d-grid">
                <button wire:click="paymentType('{{ $paymentType->slug }}')" type="button" class="btn btn-primary btn-lg text-start"><i class="bi bi-file-text-fill me-2"></i> <span>{{ $paymentType->title }}</span></button>
              </div>
            @endforeach
          </div>
        @else

          @switch($view)
            @case('cash-payment')
                <livewire:cashbook.payment-types.cash-payment>
                @break
         
            @case('bank-card')
                <livewire:cashbook.payment-types.bank-card>
                @break

            @case('complex-payment')
                <livewire:cashbook.payment-types.complex-payment>
                @break

            @case('sale-on-credit')
                <livewire:cashbook.payment-types.sale-on-credit>
                @break
          @endswitch

        @endif
      </div>
    </div>
  </main>

</div>
