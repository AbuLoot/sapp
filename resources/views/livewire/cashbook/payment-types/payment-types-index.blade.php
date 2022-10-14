<div>
  <main class="container my-4">
    <div class="row">
      <div class="col-lg-5">

        <livewire:cashbook.payment-types.cart-order>

      </div>
      <div class="col-lg-6 offset-1">

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
            @if($paymentType->slug == 'sale-on-credit')
              @cannot('sale-on-credit', Auth::user())
                @continue
              @endcannot
            @endif
            <div class="col-lg-6 d-grid">
              <a href="/{{ $lang }}/cashdesk/payment-type/{{ $paymentType->slug }}" class="btn btn-primary btn-lg text-start"><i class="{{ $paymentType->image }} me-2"></i> <span>{{ $paymentType->title }}</span></a>
              <!-- <button wire:click="paymentType('{{ $paymentType->slug }}')" type="button" class="btn btn-primary btn-lg text-start"><i class="{{ $paymentType->image }} me-2"></i> <span>{{ $paymentType->title }}</span></button> -->
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </main>
</div>
