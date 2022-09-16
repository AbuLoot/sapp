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

  <main class="container my-4">
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
              <input wire:model="search" type="search" class="form-control form-control-lg" placeholder="Поиск клиентов..." aria-label="Search">
            </div>
            <div class="col-lg-5 mb-3">
              <div class="d-grid" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addClient"><i class="bi bi-person-plus-fill me-2"></i> Новый клиент</button>
                <!--  wire:click="openModal"  -->
              </div>
            </div>
          </div>

          @if($clients)
            @forelse($clients as $client)
              <div wire:click="makeDebtDocs({{ $client->id }})" class="d-flex position-relative border-bottom p-1 mb-3">
                <div class="flex-shrink-0 display-6">
                  <i class="bi bi-person-circle"></i> 
                </div>
                <div class="ms-3">
                  <h6 class="mt-1 mb-0">{{ $client->name.' '.$client->lastname }}</h6>
                  <a href="#" class="stretched-link">{{ $client->tel }}</a>
                </div>
              </div>
            @empty
              <h6>No data</h6>
            @endforelse
          @endif

        </form>

        <!-- Modal Add Client -->
        <livewire:cashbook.add-client>

      </div>
    </div>
  </main>

</div>

@section('scripts')
  <script type="text/javascript">
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
@endsection
