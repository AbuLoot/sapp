<div>
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
        <div class="d-flex position-relative mb-3">
          <div class="flex-shrink-0 display-6">
            <i class="bi bi-person-circle"></i> 
          </div>
          <div class="ms-3">
            <h6 class="mb-0">{{ $client->name.' '.$client->lastname }}</h6>
            <a href="#" class="stretched-link">{{ $client->tel }}</a>
          </div>
        </div>
      @empty
        <h6>No data</h6>
      @endforelse
    @endif

    <!-- <div class="d-flex position-relative mb-3">
      <div class="flex-shrink-0 display-6">
        <i class="bi bi-person-circle"></i> 
      </div>
      <div class="ms-3">
        <h6 class="mb-0">User Name</h6>
        <a href="#" class="stretched-link">8 777 999966</a>
      </div>
    </div> -->

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

          <livewire:cashbook.add-client>

        </div>
      </div>
    </div>
  </div>
    <script>
    const myModalEl = document.querySelector('#addClient')
    const modal = new bootstrap.Modal(myModalEl) // initialized with defaults

    modal.show()
    console.log(1)
  </script>
</div>

@section('scripts')

@endsection
