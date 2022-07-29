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
          <h5>Count</h5>
          <h5 class="ms-auto">{{ $sumOfCart['countProduct'] }}</h5>
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

        @switch($view)

          @case('success')
            <livewire:cashbook.payment-types.success>
            @break

          @case('cash-payment')
            <livewire:cashbook.payment-types.cash-payment>
            @break

          @case('complex-payment')
            <livewire:cashbook.payment-types.complex-payment>
            @break

          @case('sale-on-credit')
            <livewire:cashbook.payment-types.sale-on-credit>
            @break

          @default
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
        @endswitch

        <br>

        <div class="col-lg-0 col-lg-10 offset-lg-1">

          <div class="row gx-2 gy-2 h-100">
            <div class="col-4 d-grid">
              <button type="button" value="7" class="btn btn-secondary btn-lg fs-2">7</button>
            </div>
            <div class="col-4 d-grid">
              <button type="button" value="8" class="btn btn-secondary btn-lg fs-2">8</button>
            </div>
            <div class="col-4 d-grid">
              <button type="button" value="9" class="btn btn-secondary btn-lg fs-2">9</button>
            </div>

            <div class="col-4 d-grid">
              <button type="button" value="4" class="btn btn-secondary btn-lg fs-2">4</button>
            </div>
            <div class="col-4 d-grid">
              <button type="button" value="5" class="btn btn-secondary btn-lg fs-2">5</button>
            </div>
            <div class="col-4 d-grid">
              <button type="button" value="6" class="btn btn-secondary btn-lg fs-2">6</button>
            </div>

            <div class="col-4 d-grid">
              <button type="button" value="1" class="btn btn-secondary btn-lg fs-2">1</button>
            </div>
            <div class="col-4 d-grid">
              <button type="button" value="2" class="btn btn-secondary btn-lg fs-2">2</button>
            </div>
            <div class="col-4 d-grid">
              <button type="button" value="3" class="btn btn-secondary btn-lg fs-2">3</button>
            </div>

            <div class="col-4 d-grid">
              <button type="button" class="btn btn-secondary btn-lg fs-2" value=""><i class="bi bi-x-square"></i></button>
            </div>
            <div class="col-4 d-grid">
              <button type="button" value="0" class="btn btn-secondary btn-lg fs-2">0</button>
            </div>
            <div class="col-4 d-grid">
              <button type="button" class="btn btn-secondary btn-lg fs-2" value=""><i class="bi bi-arrow-return-left"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

</div>
