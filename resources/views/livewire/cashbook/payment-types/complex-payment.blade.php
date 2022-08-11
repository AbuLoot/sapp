<div>

  <main class="container my-4">
    <div class="row">
      <div class="col-lg-5">

        <livewire:cashbook.payment-types.cart-order>

      </div>
      <div class="col-lg-6 offset-1">

        <div class="d-flex">
          <h2>Оплата</h2>
          <a href="/{{ app()->getLocale() }}/cashdesk/payment-types" class="btn btn-outline-dark btn-lg ms-auto">Закрыть</a>
        </div>
        <br>
        <p class="text-muted">Выберите 2 метода оплаты из вариантов</p>
        <form class="mb-3">
          @foreach($paymentTypes as $paymentType)
            <?php $paymentTypeSlug = Str::of($paymentType->slug)->camel(); ?>
            <div class="mb-3 row align-items-center">
              <div class="col-lg-5 form-check">
                <input class="form-check-input" type="checkbox" wire:model="complexPayments" value="{{ $paymentTypeSlug }}" id="payment-{{ $paymentType->slug }}" @if(count($complexPayments) == 2 && !in_array($paymentTypeSlug, $complexPayments)) disabled @endif>
                <label class="form-check-label" for="payment-{{ $paymentType->slug }}">{{ $paymentType->title }}</label>
              </div>
              <div class="col-sm-7">
                <input type="number" wire:model="{{ $paymentTypeSlug }}" class="form-control form-control-lg" id="title" name="title" minlength="2" @if(!in_array($paymentTypeSlug, $complexPayments)) disabled @endif placeholder="Введите сумму">
              </div>
            </div>
          @endforeach
          <button wire:click="pay" type="button" class="btn btn-success btn-lg" @if(!$payButton) disabled @endif>Оплатить</button>
        </form>

        <div class="d-flex">
          <h4>Итого</h4>
          <h4 class="ms-auto">{{ $sumOfCart['sumDiscounted'] . $company->currency->symbol }}</h4>
        </div>

      </div>
    </div>
  </main>

</div>
