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

        <form class="mb-3">
          <div class="mb-3 row align-items-center">
            <div class="col-5 col-lg-5">
              <label class="form-label">Наличными</label>
            </div>
            <div class="col-sm-7">
              <div class="input-group">
                <input wire:model.debounce.400ms="cash" onclick="setFocus(this)" type="number" class="form-control form-control-lg" minlength="2" placeholder="Введите сумму">
                <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
              </div>
            </div>
          </div>
          <div class="mb-3 row align-items-center">
            <div class="col-5 col-lg-5">
              <label class="form-label">Сдача</label>
            </div>
            <div class="col-sm-7">
              <input wire:model="change" type="number" class="form-control form-control-lg" id="title" minlength="2" placeholder="Введите сумму">
            </div>
          </div>
          <button wire:click="makeDocs" type="button" class="btn btn-success btn-lg" @if(!$payButton) disabled @endif>Оплатить</button>
        </form>

        <div class="d-flex">
          <h4>Сдача</h4>
          <h4 class="ms-auto">{{ $change }}</h4>
        </div>
        <div class="d-flex">
          <h4>Итого</h4>
          <h4 class="ms-auto">{{ $sumOfCart['sumDiscounted'] }}</h4>
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

    function setFocus(el) {
      activeEl = el;
      activeEl.focus();
    }

    function display(val) {
      activeEl.value += val;
      @this.set('cash', activeEl.value);
    }

    function clearDisplay() {
      activeEl.value = activeEl.value.substr(0, activeEl.value.length - 1);
      @this.set('cash', activeEl.value);
    }
  </script>
@endsection