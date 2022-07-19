<div>
  <header class="p-3 bg-brand bg-brand-border">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="#" class="me-4">
          <img src="/img/logo.svg" width="auto" height="40">
        </a>

        <button class="btn btn-warning rounded-circle me-auto"><i class="bi bi-arrow-clockwise"></i></button>

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

      <form class="col-4 col-lg-4 me-4">
        <input wire:model="searchClient" type="search" class="form-control form-control-lg" placeholder="Поиск клиентов..." aria-label="Search">
      </form>

      <button class="btn btn-dark btn-lg ms-auto" type="button" data-bs-toggle="modal" data-bs-target="#closingCash">Закрыть смену</button>
    </div>
  </div>

  <main class="container" style="margin-bottom: 170px;">
    <table class="table table-striped table-borderless border">
      <thead>
        <tr>
          <th>Наименование товара</th>
          <th>Штрихкод</th>
          <th>Категория</th>
          <th>Цена оптовая</th>
          <th>Цена продажи</th>
          <th>Кол-во</th>
          <th>Поставщик</th>
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
              <td>{{ $cartProduct->wholesale_price }}</td>
              <td>{{ $cartProduct->price }}</td>
              <?php
                $unit = $units->where('id', $cartProduct->unit)->first()->title ?? '?';
              ?>
              <td class="text-nowrap text-end">
                {{ $cartProduct->count . $unit }} <a href="#"><i class="bi bi-pencil-square text-primary"></i></a>
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
                <button class="btn btn-secondary" type="button">Оптовые<br> цены</button>
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
  <livewire:cashbook.fast-products>

  <!-- Modal Add Client -->
  <livewire:cashbook.add-client>

  <!-- Modal Closing Cash -->
  <livewire:cashbook.closing-cash>

  <!-- Modal Return Products -->
  <livewire:cashbook.return-products>

  <!-- Modal List Of Deptors -->
  <livewire:cashbook.list-of-deptors>

  <!-- Modal Incoming Cash -->
  <livewire:cashbook.incoming-cash>

  <!-- Modal Outgoing Cash -->
  <livewire:cashbook.outgoing-cash>

  <!-- Modal Deferred Checks -->
  <livewire:cashbook.deferred-checks>
</div>
