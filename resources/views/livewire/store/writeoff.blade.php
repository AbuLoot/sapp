<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Списание</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto" style="position: relative;">
        <div class="input-group">
          <input wire:model="search" type="search" class="form-control" id="search" onclick="setFocus('search')" placeholder="Поиск..." aria-label="Search">
          <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
        </div>
        @if($products)
          <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
            <ul class="list-unstyled mb-0">
              @forelse($products as $product)
                <li>
                  <a wire:click="addToWriteoff({{ $product->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $product->title }}</a>
                </li>
              @empty
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
              @endforelse
            </ul>
          </div>
        @endif
      </form>

      <ul class="nav col-lg-auto text-end me-lg-2 text-small">
        @if($writeoffProducts)
          <li><a href="#" wire:click="makeDoc" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Провести документ"><i class="bi bi-file-earmark-ruled-fill"></i></a></li>
          <li><a href="#" wire:click="removeWriteoff" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Очистить все"><i class="bi bi-file-x-fill"></i></a></li>
        @endif
      </ul>

    </div>
  </div>

  <!-- Content -->
  <div class="container">

    @if(session()->has('message'))
      <div class="toast-container position-fixed bottom-0 end-0 p-4">
        <div class="toast align-items-center text-bg-info border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body text-white">{{ session('message') }}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
    @endif

    <div class="row justify-content-end mb-3">
      <div class="col-3">
        <select wire:model="storeId" class="form-control @error('storeId') is-invalid @enderror" id="storeId">
          @foreach ($company->stores as $store)
            <option value="{{ $store->id }}"> {{ $store->title }}</option>
          @endforeach
        </select>
        @error('storeId')<div class="text-danger">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-sm align-middle table-striped">
        <thead>
          <tr>
            <th scope="col">Наименование<br>товара</th>
            <th scope="col">Штрихкод</th>
            <th scope="col">Категория</th>
            <th scope="col">Цена закупки</th>
            <th scope="col">Цена продажи</th>
            <th scope="col">{{ $company->stores->where('id', $storeId)->first()->title }}</th>
            <th scope="col">Общее кол-во</th>
            <th scope="col">Кол-во</th>
            <th scope="col">Поставщик</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($writeoffProducts as $index => $writeoffProduct)
            <tr>
              <td>{{ $writeoffProduct->title }}</td>
              <td>
                <?php $barcodes = json_decode($writeoffProduct->barcodes, true) ?? []; ?>
                @foreach($barcodes as $barcode)
                  {{ $barcode }}<br>
                @endforeach
              </td>
              <td>{{ $writeoffProduct->category->title }}</td>
              <td>{{ $writeoffProduct->purchase_price }}</td>
              <td>{{ $writeoffProduct->price }}</td>
              <?php
                $unit = $units->where('id', $writeoffProduct->unit)->first()->title ?? '?';

                $countInStores = json_decode($writeoffProduct->count_in_stores, true) ?? [];
                $countInStore = (isset($countInStores[$storeId])) ? $countInStores[$storeId] : 0;
                $writeoffCountProduct = 0;

                if (isset($writeoffCounts[$writeoffProduct->id][$storeId])) {
                  $writeoffCountProduct = $writeoffCounts[$writeoffProduct->id][$storeId];
                }
              ?>
              <td>{{ $countInStore - $writeoffCountProduct . $unit }}</td>
              <td>{{ $writeoffProduct->count - $writeoffCountProduct . $unit }}</td>
              <td class="col-2">
                <div class="input-group">
                  <input wire:model="writeoffCounts.{{ $writeoffProduct->id.'.'.$storeId }}" type="number" id="writeoffCounts.{{ $writeoffProduct->id.'.'.$storeId }}" onclick="setFocus('writeoffCounts.{{ $writeoffProduct->id.'.'.$storeId }}')" class="form-control @error('writeoffCounts.'.$writeoffProduct->id.'.'.$storeId) is-invalid @enderror" required>
                  <span class="input-group-text">{{ $unit }}</span>
                  @error('writeoffCounts.'.$writeoffProduct->id.'.'.$storeId)<div class="text-danger">{{ $message }}</div>@enderror
                </div>
              </td>
              <td>{{ $writeoffProduct->company->title }}</td>
              <td class="text-end"><a wire:click="removeFromWriteoff({{ $writeoffProduct->id }})" href="#" class="fs-4"><i class="bi bi-file-x-fill"></i></a></td>
            </tr>
          @empty
            <tr>
              <td colspan="10">No data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="row">
      <div class="col-auto">
        <div class="form-floating">
          <textarea wire:model="comment" id="comment" onclick="setFocus('comment')" class="form-control @error('comment') is-invalid @enderror" id="comment" style="height: 100px" placeholder="Причина списания"></textarea>
          @error('comment')<div class="text-danger">{{ $message }}</div>@enderror
          <label for="comment">Причина списания</label>
        </div>
      </div>
      <div class="col-auto">
        @if($writeoffProducts)
          <a href="#" wire:click="makeDoc" class="btn btn-primary"><i class="bi bi-file-earmark-ruled-fill me-2"></i> Провести документ</a>
        @endif
      </div>
    </div>

    <!-- Keyboard -->
    <livewire:keyboard>

  </div>
</div>

@section('scripts')
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
  let inputElId;

  // Setting Input Focus
  function setFocus(elId) {
    inputElId = elId;
    document.getElementById(elId).focus();
  }

  // Displaying values
  function display(val) {
    let input = document.getElementById(inputElId);

    input.value += val;
    @this.set(inputElId, input.value);
  }

  // Clearing the display
  function clearDisplay() {
    let inputSearch = document.getElementById(inputElId);
    inputSearch.value = inputSearch.value.substr(0, inputSearch.value.length - 1);
    @this.set(inputElId, inputSearch.value);
  }
</script>
@endsection