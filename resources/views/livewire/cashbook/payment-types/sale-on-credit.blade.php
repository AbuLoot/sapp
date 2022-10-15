<div>

  @if(session()->has('message'))
    <div class="toast-container position-fixed -bottom-0 end-0 p-4">
      <div class="toast align-items-center text-bg-info border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body text-white">{{ session('message') }}</div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
  @endif

  <main class="container my-4 mb-25">
    <div class="row">
      <div class="col-lg-5">

        <livewire:cashbook.payment-types.cart-order>

      </div>
      <div class="col-lg-6 offset-1">

        <div class="d-flex">
          <h2>Продажа в долг</h2>
          <a href="/{{ $lang }}/cashdesk/payment-types" class="btn btn-outline-dark btn-lg ms-auto">Закрыть</a>
        </div>
        <br>

        <p class="text-muted">Выберите из поля поиска человека, которому хотите оформить долг</p>

        <form class="mb-3">

          <div class="row">
            <div class="col-lg-7 mb-3">
              <div class="input-group">
                <input wire:model="search" onclick="setFocus(this, 'saleOnCreditInput-search')" type="search" class="form-control form-control-lg" placeholder="Поиск клиентов..." aria-label="Search">
                <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
              </div>
            </div>
            <div class="col-lg-5 mb-3">
              <div class="d-grid" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addCustomer"><i class="bi bi-person-plus-fill me-2"></i> Новый клиент</button>
                <!--  wire:click="openModal"  -->
              </div>
            </div>
          </div>

            @forelse($customers as $customer)
              <div wire:click="makeDebtDocs({{ $customer->id }})" class="d-flex position-relative border-bottom p-1 mb-3">
                <div class="flex-shrink-0 display-6">
                  <i class="bi bi-person-circle"></i> 
                </div>
                <div class="ms-3">
                  <h6 class="mt-1 mb-0">{{ $customer->name.' '.$customer->lastname }}</h6>
                  <a href="#" class="stretched-link">{{ $customer->tel }}</a>
                </div>
              </div>
            @empty
              <h6>No data</h6>
            @endforelse

        </form>

        <!-- Modal Add Customer -->
        <livewire:cashbook.add-customer>

      </div>
    </div>
  </main>

  <!-- Keyboard -->
  <livewire:keyboard>

</div>

@section('scripts')
  <script type="text/javascript">
    // Toast Notifications
    window.addEventListener('show-toast', event => {
      if (event.detail.reload) {
        document.location.reload()
      }

      if (event.detail.selector) {
        const btnCloseModal = document.getElementById(event.detail.selector)
        btnCloseModal.click()
      }

      const toast = new bootstrap.Toast(document.getElementById('liveToast'))
      toast.show()

      const toastBody = document.getElementById('toastBody')
      toastBody.innerHTML = event.detail.message
    })
  </script>

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
    let input = ['saleOnCreditInput'];
    let activeEl;

    function setFocus(el, attrNames) {
      input = attrNames.split('-');
      activeEl = el;
      activeEl.focus();
    }

    function display(val) {
      if (input[0] == 'saleOnCreditInput') {
        activeEl.value += val;
        @this.set(input[1], activeEl.value);
      } else {
        activeEl.value += val;
        Livewire.emit(input[0], [activeEl.value, input[1]]);
      }
    }

    function clearDisplay() {
      activeEl.value = activeEl.value.substr(0, activeEl.value.length - 1);

      if (input[0] == 'saleOnCreditInput') {
        @this.set(input[1], activeEl.value);
      } else {
        Livewire.emit(input[0], [activeEl.value, input[1]]);
      }
    }

    // Keyboard Input
    // let activeEl;
    // let model;

    // function setFocus(el, modelName) {
    //   model = modelName;
    //   activeEl = el;
    //   activeEl.focus();
    // }

    // function display(val) {
    //   activeEl.value += val;
    //   @this.set(model, activeEl.value);
    // }

    // function clearDisplay() {
    //   activeEl.value = activeEl.value.substr(0, activeEl.value.length - 1);
    //   @this.set(model, activeEl.value);
    // }
  </script>
@endsection
