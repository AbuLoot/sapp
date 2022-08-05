<div>

  <main class="container my-4">
    <div class="row">
      <div class="col-lg-5">

        <livewire:cashbook.payment-types.cart-order>

      </div>
      <div class="col-lg-6 offset-1">

        <div class="d-flex">
          <h2>Продажа в долг</h2>
          <a href="/{{ app()->getLocale() }}/cashdesk/payment-types" class="btn btn-outline-dark btn-lg ms-auto">Закрыть</a>
        </div>
        <br>

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
        <div class="modal fade" id="addClient" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content  bg-light">
              <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Добавить клиента</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">

                <!-- <-livewire:cashbook.add-client> -->
                <form wire:click.prevent="save">

                  <div class="row">
                    <div class="col-6">
                      <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input wire:model.defer="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="mb-3">
                        <label for="lastname" class="form-label">Фамилия</label>
                        <input wire:model.defer="lastname" type="text" class="form-control form-control-lg @error('lastname') is-invalid @enderror" id="lastname" name="lastname">
                        @error('lastname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="mb-3">
                        <label for="tel" class="form-label">Номер телефона</label>
                        <input wire:model.defer="tel" type="tel" class="form-control form-control-lg @error('tel') is-invalid @enderror" id="tel" name="tel">
                        @error('tel')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input wire:model.defer="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="address" class="form-label">Адрес</label>
                    <input wire:model.defer="address" type="text" class="form-control form-control-lg @error('address') is-invalid @enderror" id="address" name="address">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="text-end">
                    <button type="submit" class="btn btn-success btn-lg">Добавить</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>

</div>

@section('scripts')

  <script type="text/javascript">
    const myModalEl = document.querySelector('#addClient')
    const modal = new bootstrap.Modal(myModalEl) // initialized with defaults

    // modal.show();
    window.addEventListener('close-modal', event => {
      // const incomingCash = document.getElementById('incomingCash')
      // incomingCash.hide() // it is asynchronous
      const myModalEl = document.querySelector('#addClient')
      const modal = new bootstrap.Modal(myModalEl) // initialized with defaults

      modal.hide();
    })
  </script>

@endsection
