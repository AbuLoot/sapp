<div>
  <header class="p-3 bg-brand bg-brand-border">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="#" class="me-4">
          <img src="/img/logo.svg" width="auto" height="40">
        </a>

        <button class="btn btn-warning rounded-circle me-auto" onclick="document.location.reload()"><i class="bi bi-arrow-clockwise"></i></button>

        <div class="text-end me-4">
          <button type="button" class="btn btn-outline-light btn-lg me-2" data-bs-toggle="modal" data-bs-target="#fastProducts"><i class="bi bi-cart-check-fill"></i> Быстрые товары</button>
          <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addClient"><i class="bi bi-person-plus-fill"></i> Добавить клиента</button>
        </div>

        <div class="dropdown d-flex text-end">
          <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">  
            <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu dropdown-menu-lg-end text-small" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <div class="px-3 py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap">
      <form class="col-4 col-lg-4 me-4" style="position: relative;">
        <input wire:model="searchProduct" type="search" class="form-control form-control-lg" placeholder="Поиск по названию, штрихкоду..." aria-label="Search">
        @if($products)
          <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
            <ul class="list-unstyled mb-0">
              @forelse($products as $product)
                <li>
                  <a wire:click="addToCart({{ $product->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $product->title }}</a>
                </li>
              @empty
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
              @endforelse
            </ul>
          </div>
        @endif
      </form>

      <form class="col-4 col-lg-4 me-4" style="position: relative;">
        <input wire:model="searchClient" type="search" class="form-control form-control-lg" placeholder="Поиск клиентов..." aria-label="Search">
        @if($clients)
          <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
            <ul class="list-unstyled mb-0">
              @forelse($clients as $client)
                <li>
                  <a wire:click="check({{ $client->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $client->name.' '.$client->lastname.' '.$client->tel }}</a>
                </li>
              @empty
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
              @endforelse
            </ul>
          </div>
        @endif
      </form>

      <button class="btn btn-dark btn-lg ms-auto" type="button" data-bs-toggle="modal" data-bs-target="#closingCash">Закрыть смену</button>
    </div>
  </div>

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

  <main class="container" style="margin-bottom: 170px;">

    <table class="table table-striped table-borderless border">
      <thead>
        <tr>
          <th>Наименование товара</th>
          <th>Штрихкод</th>
          <th>Категория</th>
          @if($priceMode == 'retail')
            <th>Цена продажи</th>
          @else
            <th>Цена оптовая</th>
          @endif
          <th>В&nbsp;базе</th>
          <th class="text-end">Кол-во</th>
          <th class="text-end">Поставщик</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($cartProducts as $index => $cartProduct)
          <tr>
            <td><a href="/{{ $lang }}/storage/edit-product/{{ $cartProduct->id }}">{{ $cartProduct->title }}</a></td>
            <td>
              <?php $barcodes = json_decode($cartProduct->barcodes, true) ?? ['']; ?>
              @foreach($barcodes as $barcode)
                {{ $barcode }}<br>
              @endforeach
            </td>
            <td>{{ $cartProduct->category->title }}</td>
            @if($priceMode == 'retail')
              <td>{{ $cartProduct->price }}</td>
            @else
              <td>{{ $cartProduct->wholesale_price }}</td>
            @endif
            <?php $unit = $units->where('id', $cartProduct->unit)->first()->title ?? '?'; ?>
            <td>{{ $cartProduct->count . $unit }}</td>
            <td class="text-nowrap text-end">
              <div class="input-group">
                <input type="number" wire:model="product.{{ $cartProduct->id }}" class="form-control @error('product.'.$cartProduct->id) is-invalid @enderror" required>
                <span class="input-group-text">{{ $unit }}</span>
                @error('product.'.$cartProduct->id)<div class="text-danger">{{ $message }}</div>@enderror
              </div>
            </td>
            <td class="text-end">{{ $cartProduct->company->title }}</td>
            <td class="text-end"><a wire:click="removeFromCart({{ $cartProduct->id }})" href="#" class="fs-4"><i class="bi bi-file-x-fill"></i></a></td>
          </tr>
        @empty
          <tr>
            <td colspan="9">No data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </main>

  <footer class="d-flex flex-wrap fixed-bottom bg-light justify-content-between align-items-center py-2 border-top border-3">
    <div class="container">
      <div class="row gx-2 pb-2">
        <div class="col-8 cash-operations">
          <div class="row gx-lg-2 gx-sm-1 gy-sm-1">
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#returnProducts">Оформить<br> возврат</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#incomingCash">Внести</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                @if($priceMode == 'retail')
                  <button class="btn btn-secondary" wire:click="switchPriceMode" type="button">Оптовые<br> цены</button>
                @else
                  <button class="btn btn-secondary" wire:click="switchPriceMode" type="button">Розничные<br> цены</button>
                @endif
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button">Отложить<br> данный чек</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#outgoingCash">Расход</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#listOfDeptors">Список<br> должников</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button">Повторная<br> печать</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#defferedChecks">Отложенные<br> чеки</button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4 gy-2">

          <table class="table table-sm table-bordered mb-2">
            <tbody>
              <tr>
                <td>Скидка:</td>
                <td>10%</td>
                <td class="text-center text-bg-success" rowspan="2"><h5>Сумма</h5> <b>1 950 000〒</b></td>
              </tr>
              <tr>
                <td>Без скидки:</td>
                <td>1 990 000〒</td>
              </tr>
            </tbody>
          </table>

          <div class="d-grid">
            <button class="btn btn-success btn-lg" type="button">Продать</button>
          </div>

        </div>
      </div>
    </div>
  </footer>

  <!-- Modal Fast Products -->
  <div class="modal fade" id="fastProducts" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Быстрые товары</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <livewire:cashbook.fast-products>

        </div>
      </div>
    </div>
  </div>

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

  <!-- Modal Closing Cash -->
  <livewire:cashbook.closing-cash>

  <!-- Modal Return Products -->
  <livewire:cashbook.return-products>

  <!-- Modal List Of Deptors -->
  <livewire:cashbook.list-of-deptors>

  <!-- Modal Incoming Cash -->
  <div class="modal fade" id="incomingCash" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Внести деньги в кассу</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <livewire:cashbook.incoming-cash>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal Outgoing Cash -->
  <div class="modal fade" id="outgoingCash" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Оформить расход</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <livewire:cashbook.outgoing-cash>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal Deferred Checks -->
  <livewire:cashbook.deferred-checks>
</div>

@section('scripts')
  <script type="text/javascript">
    window.addEventListener('close-modal', event => {
      // const incomingCash = document.getElementById('incomingCash')
      // incomingCash.hide() // it is asynchronous
      console.log(1);
      var myModal = new bootstrap.Modal(document.getElementById("incomingCash"), {});
      console.log(2);
      myModal.hide();
      console.log(3);
    })
  </script>
@endsection