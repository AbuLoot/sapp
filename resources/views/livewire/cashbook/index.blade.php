<div>

  <header class="p-3 bg-brand bg-brand-border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/{{ $lang }}/cashdesk" class="navbar-brand me-4">
          <img src="/img/logo.svg" width="auto" height="40"> <span class="text-white">{{ $cashbook->title }}</span>
        </a>

        <button class="btn btn-warning rounded-circle me-auto" onclick="document.location.reload()"><i class="bi bi-arrow-clockwise"></i></button>

        <div class="text-end me-4">
          <button type="button" wire:click="$emit('getFastProducts')" class="btn btn-outline-light btn-lg me-2" data-bs-toggle="modal" data-bs-target="#fastProducts"><i class="bi bi-cart-check-fill"></i> Быстрые товары</button>
          <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addCustomer"><i class="bi bi-person-plus-fill"></i> Добавить клиента</button>
        </div>

        <div class="dropdown d-flex text-end">
          <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-4 text-white"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <div class="text-muted px-3 py-1">
              {{ Auth()->user()->name.' '.Auth()->user()->lastname }}<br>
              {{ $company->title }}
            </div>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">Выйти</a>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  @if(! Cache::has('openedCash') OR Cache::get('openedCash')['companyId'] !== $company->id)

    <!-- Opening Cash -->
    <livewire:cashbook.opening-cash>

  @elseif(Cache::get('openedCash')['companyId'] === $company->id)

    <!-- Cash Interface -->
    <div class="px-3 py-3 border-bottom-mb-3">
      <div class="container d-flex flex-wrap">

        <!-- Search Products -->
        <form class="col-4 col-lg-4 me-4" style="position: relative;" wire:submit.prevent="getByBarcode">
          <div class="input-group">
            <input wire:model="search" onclick="setFocus(this, 'indexInput-search')" type="search" id="search" class="form-control form-control-lg" placeholder="Поиск по названию, штрихкоду..." aria-label="Search">
            <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
          </div>
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

        <!-- Search Customers -->
        <form class="col-3 col-lg-3 me-4" style="position: relative;">
          <div class="input-group">
            <input wire:model="searchCustomer" id="searchCustomer" onclick="setFocus(this, 'indexInput-searchCustomer')" type="search" class="form-control form-control-lg" placeholder="Поиск клиентов..." aria-label="Search">
            @if($customer)
              <button class="btn btn-outline-secondary dropdown-toggle fs-5" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-fill"></i></button>
              <div class="dropdown-menu dropdown-menu-end p-3" style="max-width: 200px;">
                <p>
                  {{ $customer->name.' '.$customer->lastname }}<br>
                  Скидка: {{ $customer->profile->discount ?? 0 }}%
                </p>
                <div class="d-grid gap-2">
                  <button wire:click="removeCustomer" class="btn btn-outline-dark" type="button">Удалить из кассы</button>
                </div>
              </div>
            @endif
          </div>
          @if($customers)
            <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute; top: 50px;">
              <ul class="list-unstyled mb-0">
                @forelse($customers as $customerObj)
                  <li>
                    <a wire:click="checkCustomer({{ $customerObj->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $customerObj->name.' '.$customerObj->lastname.' '.$customerObj->tel }}</a>
                  </li>
                @empty
                  <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled-">No data</a></li>
                @endforelse
              </ul>
            </div>
          @endif
        </form>

        <!-- Storages List -->
        <div class="col-auto ms-auto">
          <select wire:model="storeId" class="form-control form-control-lg">
            @foreach ($company->stores as $storeObj)
              <option value="{{ $storeObj->id }}">{{ $storeObj->title }}</option>
            @endforeach
          </select>
        </div>

        <!-- Closing Cash -->
        <button class="btn btn-dark btn-lg ms-4" type="button" data-bs-toggle="modal" data-bs-target="#closingCash">Закрыть смену</button>
      </div>
    </div>

    <!-- Toast notification -->
    <div class="toast-container position-fixed end-0 p-4">
      <div class="toast align-items-center text-bg-info border-0" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body text-white" id="toastBody"></div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>

    <!-- Cart Of Products -->
    <main class="container" style="margin-bottom: 425px;">
      <div class="table-responsive">
        <table class="table table-striped table-borderless border align-middle">
          <thead>
            <tr>
              <th>Наименование товара</th>
              <th>Штрихкод</th>
              <th>Категория</th>
              <th>Цена {{ $priceMode == 'retail' ? 'продажи' : 'оптовая' }}</th>
              <th class="text-center">В базе</th>
              <th class="text-center">{{ $store->title }}</th>
              <th>Кол-во</th>
              @can('set-discount', Auth::user())
                <th>Скидка</th>
              @endcan
              <th>Поставщик</th>
              <th><button wire:click="$emit('showStornoModal', '0')" type="button" class="btn btn-link p-0">Очистить</button></th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sortedCartProducts = [];

              foreach($cartProducts as $cartProduct) {
                  $sortedCartProducts[$cartProduct->time] = $cartProduct;
              }

              krsort($sortedCartProducts);
            ?>
            @forelse($sortedCartProducts as $index => $cartProduct)
              <?php
                $barcodes = json_decode($cartProduct->barcodes, true) ?? [];
                $countInStores = json_decode($cartProduct->count_in_stores, true) ?? [];
                $countInStore = $countInStores[$storeNum] ?? 0;
              ?>
              <tr>
                <td>{{ $cartProduct->sortId.' '.$cartProduct->title }}</td>
                <td>
                  @foreach($barcodes as $barcode)
                    {{ $barcode }}<br>
                  @endforeach
                </td>
                <td>{{ $cartProduct->category->title }}</td>
                <td>{{ $priceMode == 'retail' ? $cartProduct->price : $cartProduct->wholesale_price }}</td>
                <td class="text-center">{{ $cartProduct->count }}</td>
                <td class="text-center"><div class="@if($countInStore == 0) text-danger @endif">{{ $countInStore }}</div></td>
                <td style="width:10%;">
                  <input wire:model="cartProducts.{{ $cartProduct->id.'.countInCart' }}" onclick="setFocus(this, 'indexInput-cartProducts.{{ $cartProduct->id }}.countInCart')" type="number" class="form-control @error('cartProducts.'.$cartProduct->id.'.countInCart') is-invalid @enderror" required>
                  @error('cartProducts.'.$cartProduct->id.'.countInCart')<div class="text-danger">{{ $message }}</div>@enderror
                </td>
                @can('set-discount', Auth::user())
                  <td class="text-nowrap" style="width:10%;">
                    @if($cartProduct->input)
                      <div class="input-group input-group-sm">
                        <input wire:model="cartProducts.{{ $cartProduct->id.'.discount' }}" onclick="setFocus(this, 'indexInput-cartProducts.{{ $cartProduct->id }}.discount')" type="number" class="form-control @error('cartProducts.'.$cartProduct->id.'.discount') is-invalid @enderror" required>
                        <button wire:click="switchDiscountView({{ $cartProduct->id }})" class="btn btn-success" type="button"><i class="bi bi-check"></i></button>
                      </div>
                    @else
                      <div class="input-group input-group-sm">
                        <span class="input-group-text">{{ $cartProduct['discount'] ?? 0 }}%</span>
                        <button wire:click="switchDiscountView({{ $cartProduct->id }})" class="btn btn-primary" type="button"><i class="bi bi-pencil-square"></i></button>
                      </div>
                    @endif
                  </td>
                @endcan
                <td>{{ $cartProduct->company->title }}</td>
                <td class="text-end"><a href="#" wire:click="$emit('showStornoModal', '{{ $cartProduct->id }}')" class="fs-4"><i class="bi bi-file-x-fill"></i></a></td>
              </tr>
            @empty
              <tr>
                <td colspan="10">No data</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </main>

    <!-- Cash Operations -->
    <footer class="d-flex flex-wrap fixed-bottom bg-light justify-content-between align-items-center py-2 border-top border-2 shadow">
      <div class="container">
        <div class="row gx-2 pb-2">
          <div class="col-8 cash-operations">
            <div class="row gx-lg-2 gx-sm-1 gy-sm-1">
              <div class="col-3 gy-2">
                <div class="d-grid gap-2 h-100">
                  <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#returnProducts">Оформить<br> возврат</button>
                </div>
              </div>
              <div class="col-3 gy-2">
                <div class="d-grid gap-2 h-100">
                  <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#incomingCash">Внести</button>
                </div>
              </div>
              <div class="col-3 gy-2">
                <div class="d-grid gap-2 h-100">
                  @if($priceMode == 'retail')
                    <button class="btn btn-dark" @can('switch-price-mode', Auth::user()) wire:click="switchPriceMode" @endcan type="button">Оптовые<br> цены</button>
                  @else
                    <button class="btn btn-dark" wire:click="switchPriceMode" type="button">Розничные<br> цены</button>
                  @endif
                </div>
              </div>
              <div class="col-3 gy-2">
                <div class="d-grid gap-2 h-100">
                  <button wire:click="deferCheck" class="btn btn-dark" type="button">Отложить<br> данный чек</button>
                </div>
              </div>
              <div class="col-3 gy-2">
                <div class="d-grid gap-2 h-100">
                  <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#outgoingCash">Расход</button>
                </div>
              </div>
              <div class="col-3 gy-2">
                <div class="d-grid gap-2 h-100">
                  <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#listOfDebtors">Список<br> должников</button>
                </div>
              </div>
              <div class="col-3 gy-2">
                <div class="d-grid gap-2 h-100">
                  <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#reprint">Повторная<br> печать</button>
                </div>
              </div>
              <div class="col-3 gy-2">
                <div class="d-grid gap-2 h-100">
                  <button class="btn btn-dark" type="button" data-bs-toggle="modal" data-bs-target="#defferedChecks">Отложенные<br> чеки</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-4 gy-2">

            <table class="table table-sm table-bordered mb-2">
              <tbody>
                <tr>
                  <td>Скидка:</td>
                  <td>
                    @if($totalDiscountView)
                      <div class="input-group input-group-sm" style="width:90px;">
                        <input wire:model="totalDiscount" onclick="setFocus(this, 'indexInput-totalDiscount')" type="number" class="form-control" required>
                        <button wire:click="switchTotalDiscountView()" class="btn btn-success" type="button"><i class="bi bi-check"></i></button>
                      </div>
                    @else
                      @can('set-discount', Auth::user())
                        <a wire:click="switchTotalDiscountView()" href="#">{{ $totalDiscount ?? 0 }}% <i class="bi bi-pencil-square"></i></a>
                      @else
                        <a href="#">{{ $totalDiscount ?? 0 }}% <i class="bi bi-pencil-square"></i></a>
                      @endcan
                    @endif
                  </td>
                  <td class="text-center text-bg-success" rowspan="2">
                    <h5>Сумма</h5>
                    <b>{{ $sumOfCart['sumDiscounted'] . $company->currency->symbol }}</b><br>
                  </td>
                </tr>
                <tr>
                  <td>Без скидки:</td>
                  <td>{{ $sumOfCart['sumUndiscounted'] . $company->currency->symbol }}</td>
                </tr>
              </tbody>
            </table>

            <div class="d-grid">
              <button type="button" onclick="location.href='/{{ $lang }}/cashdesk/payment-types'" class="btn btn-success btn-lg" @if(!$cartProducts) disabled @endif>Продать</button>
            </div>

          </div>
        </div>
      </div>
    </footer>

    <!-- Modal Fast Products -->
    <livewire:cashbook.fast-products>

    <!-- Modal Confirm Storno -->
    <livewire:cashbook.confirm-storno>

    <!-- Modal Closing Cash -->
    @can('closing-cash', Auth::user())
      <livewire:cashbook.closing-cash>
    @endcan

    <!-- Modal Add Customer -->
    <livewire:cashbook.add-customer>

    <!-- Modal Return Products -->
    @can('return-products', Auth::user())
      <livewire:cashbook.return-products>
    @endcan

    <!-- Modal List Of Debtors -->
    @can('list-of-debtors', Auth::user())
      <livewire:cashbook.list-of-debtors>
    @endcan

    <!-- Modal Incoming Cash -->
    @can('incoming-cash', Auth::user())
      <livewire:cashbook.incoming-cash>
    @endcan

    <!-- Modal Outgoing Cash -->
    @can('outgoing-cash', Auth::user())
      <livewire:cashbook.outgoing-cash>
    @endcan

    <!-- Modal Reprint -->
    <livewire:cashbook.reprint>

    <!-- Modal Deferred Checks -->
    <livewire:cashbook.deferred-checks :company="$company">

    <!-- Keyboard -->
    <livewire:keyboard>

  @endif

</div>

@section('scripts')
  <script type="text/javascript">
    // Toast Script
    window.addEventListener('area-focus', event => {

      var areaEl = document.getElementById('search');
      areaEl.value = '';
      areaEl.focus();
    })
  </script>

  <script type="text/javascript">
    // Offcanvas
    const offcanvas = new bootstrap.Offcanvas('#offcanvas', { backdrop: false, scroll: true })

    // Offcanvas - Changing Placement
    function changePLacement(val) {

      let placement = 'offcanvas-bottom';
      let element = document.getElementById("offcanvas");

      placement = (val == 'offcanvas-bottom') ? 'offcanvas-top' : 'offcanvas-bottom';

      element.classList.add(val);
      element.classList.remove(placement);
    }

    // Keyboard Input
    let input = ['indexInput'];
    let activeEl;

    function setFocus(el, attrNames) {
      input = attrNames.split('-');
      activeEl = el;
      activeEl.focus();
      // activeEl.value = null;
    }

    function display(val) {
      if (input[0] == 'indexInput') {
        activeEl.value += val;
        @this.set(input[1], activeEl.value);
      } else {
        activeEl.value += val;
        Livewire.emit(input[0], [activeEl.value, input[1]]);
      }
    }

    function clearDisplay() {
      activeEl.value = activeEl.value.substr(0, activeEl.value.length - 1);

      if (input[0] == 'indexInput') {
        @this.set(input[1], activeEl.value);
      } else {
        Livewire.emit(input[0], [activeEl.value, input[1]]);
      }
    }
  </script>
@endsection