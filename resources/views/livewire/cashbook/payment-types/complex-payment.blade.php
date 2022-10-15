<div>

  <main class="container my-4 mb-25">
    <div class="row">
      <div class="col-lg-5">

        <livewire:cashbook.payment-types.cart-order>

      </div>
      <div class="col-lg-6 offset-1">

        <div class="d-flex">
          <h2>Оплата</h2>
          <a href="/{{ $lang }}/cashdesk/payment-types" class="btn btn-outline-dark btn-lg ms-auto">Закрыть</a>
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
                <div class="input-group">
                  <input wire:model="{{ $paymentTypeSlug }}" onclick="setFocus(this, '{{ $paymentTypeSlug }}')" type="number" class="form-control form-control-lg" minlength="2" @if(!in_array($paymentTypeSlug, $complexPayments)) disabled @endif placeholder="Введите сумму">
                  <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
                </div>
              </div>
            </div>
          @endforeach
          <button wire:click="makeDocs" type="button" class="btn btn-success btn-lg" @if(!$payButton) disabled @endif>Оплатить</button>
        </form>

        <div class="d-flex">
          <h4>Итого</h4>
          <h4 class="ms-auto">{{ $sumOfCart['sumDiscounted'] . $company->currency->symbol }}</h4>
        </div>

      </div>
    </div>
  </main>

  <!-- Keyboard -->
  <livewire:keyboard>

</div>

@section('scripts')
  <script type="text/javascript">
    // Offcanvas
    const offcanvas = new bootstrap.Offcanvas('#offcanvas', { backdrop: false, scroll: true })

    // Offcanvas - Changing Placement
    function changePLacement(val) {

      let placement = 'offcanvas-bottom';
      let element = document.getElementById("offcanvas");

      if (val == 'offcanvas-bottom') {
        placement = 'offcanvas-top';
      } else {
        placement = 'offcanvas-bottom';
      }

      element.classList.add(val);
      element.classList.remove(placement);
    }

    // Keyboard Input
    let activeEl;
    let model;

    function setFocus(el, modelName) {
      model = modelName;
      activeEl = el;
      activeEl.focus();
    }

    function display(val) {
      activeEl.value += val;
      @this.set(model, activeEl.value);
    }

    function clearDisplay() {
      activeEl.value = activeEl.value.substr(0, activeEl.value.length - 1);
      @this.set(model, activeEl.value);
    }
  </script>
@endsection