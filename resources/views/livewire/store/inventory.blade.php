<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Ревизия {{ $docNo }}</h4>

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
                  <a wire:click="addToRevision({{ $product->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $product->title }}</a>
                </li>
              @empty
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
              @endforelse
            </ul>
          </div>
        @endif
      </form>

      <ul class="nav col-lg-auto text-end me-lg-2 text-small">
        <li><a href="/{{ $lang }}/storage/inventory/drafts" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Черновики"><i class="bi bi-journals"></i></a></li>
        <li><a href="#" wire:click="saveAsDraft" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Сохранить как черновик"><i class="bi bi-file-earmark-plus-fill"></i></a></li>
        @if($revisionProducts)
          <li><a href="#" wire:click="inventoryListCount" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Провести документ"><i class="bi bi-file-earmark-ruled-fill"></i></a></li>
          <li><a href="#" wire:click="removeRevision" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Очистить все"><i class="bi bi-file-x-fill"></i></a></li>
        @endif
      </ul>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="/{{ $lang }}/storage/inventory-history" class="btn btn-primary"><i class="bi bi-clock-history me-2"></i> История ревизий</a>
      </div>
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

      <!-- @foreach($company->stores as $index => $store)
        <div class="form-check form-check-inline">
          <input class="form-check-input" wire:model="storeId" type="radio" name="inlineRadioOptions" id="store{{ $store->id }}" value="{{ $store->id }}" @if($index == 0) checked @endif>
          <label class="form-check-label" for="store{{ $store->id }}">{{ $store->title }}</label>
        </div>
      @endforeach -->
    </div>

    <div class="table-responsive">
      <table class="table table-sm align-middle table-striped">
        <thead>
          <tr>
            <th scope="col">Наименование<br> товара</th>
            <th scope="col">Штрихкод</th>
            <th scope="col">Категория</th>
            <th scope="col">Цена закупки</th>
            <th scope="col">Цена продажи</th>
            <th scope="col">Общее<br> кол-во</th>
            <th scope="col">Расчетное<br> кол-во</th>
            <th scope="col">Фактическое<br> кол-во</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($revisionProducts as $index => $revisionProduct)
            <tr>
              <td>{{ $revisionProduct->title }}</td>
              <td>
                <?php $barcodes = json_decode($revisionProduct->barcodes, true) ?? ['']; ?>
                @foreach($barcodes as $barcode)
                  {{ $barcode }}<br>
                @endforeach
              </td>
              <td>{{ $revisionProduct->category->title }}</td>
              <td>{{ $revisionProduct->purchase_price }}</td>
              <td>{{ $revisionProduct->price }}</td>
              <?php
                $unit = $units->where('id', $revisionProduct->unit)->first()->title ?? '?';

                $countInStores = json_decode($revisionProduct->count_in_stores, true) ?? [];
                $countInStore = (isset($countInStores[$storeId])) ? $countInStores[$storeId] : 0;
                $difference = 0;

                if (isset($actualCount[$revisionProduct->id][$storeId])) {
                  $difference = $actualCount[$revisionProduct->id][$storeId] - $countInStore;
                }
              ?>
              <td>{{ $revisionProduct->count + $difference . $unit }}</td>
              <td>{{ $countInStore + $difference . $unit }}</td>
              <td class="col-2">
                <div class="input-group">
                  <input type="number" wire:model="actualCount.{{ $revisionProduct->id.'.'.$storeId }}" id="actualCount.{{ $revisionProduct->id.'.'.$storeId }}" onclick="setFocus('actualCount.{{ $revisionProduct->id.'.'.$storeId }}')" class="form-control @error('actualCount.'.$revisionProduct->id.'.'.$storeId) is-invalid @enderror" required>
                  <span class="input-group-text">{{ $unit }}</span>
                </div>
                @error('actualCount.'.$revisionProduct->id.'.'.$storeId)<div class="text-danger">{{ $message }}</div>@enderror
              </td>
              <td class="text-end"><a wire:click="removeFromRevision({{ $revisionProduct->id }})" href="#" class="fs-4"><i class="bi bi-file-x-fill"></i></a></td>
            </tr>
          @empty
            <tr>
              <td colspan="9">No data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="row">
      <div class="col-3">
        <div class="form-floating">
          <textarea wire:model="barcodesCount" id="barcodesCount" onclick="setFocus('barcodesCount')" class="form-control @error('barcodesCount') is-invalid @enderror" id="barcode-and-count" style="height: 120px" placeholder="Причина списания"></textarea>
          @error('barcodesCount')<div class="text-danger">{{ $message }}</div>@enderror
          <label for="barcode-and-count">Штрихкод и количество</label>
        </div>
        <a href="#" wire:click="inventoryBarcodesCount" class="btn btn-primary mt-3"><i class="bi bi-file-check-fill me-2"></i> Проверить</a>
      </div>

      <div class="col-auto">
        @if($revisionProducts)
          <a href="#" wire:click="inventoryListCount" class="btn btn-primary"><i class="bi bi-file-earmark-ruled-fill me-2"></i> Провести документ</a>
        @endif
      </div>
    </div>
  </div>

  @if($revisionModal)
    <script type="text/javascript">
      var myModal = new bootstrap.Modal(document.getElementById("revisionModal"), {});
      myModal.show();
    </script>
  @endif

  <!-- Modal -->
  <div class="modal fade" id="revisionModal" tabindex="-1" aria-labelledby="revisionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="revisionModalLabel">Ревизия cклада {{ $storeId }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @if(!empty($inventoryData))
            <div class="row">
              <div class="col-6">Количество позиции:</div>
              <div class="col-6">{{ $inventoryData['productsCount'] }}</div>
              <div class="col-6">Количество недостач:</div>
              <div class="col-6">{{ $inventoryData['shortageTotalCount'] }}</div>
              <div class="col-6">Количество излишек:</div>
              <div class="col-6">{{ $inventoryData['surplusTotalCount'] }}</div>
              <div class="col-6">Сумма недостачи:</div>
              <div class="col-6">{{ $inventoryData['shortageTotalAmount'] }}</div>
              <div class="col-6">Сумма излишек:</div>
              <div class="col-6">{{ $inventoryData['surplusTotalAmount'] }}</div>
            </div>
          @endif
          <div class="text-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
          </div>
        </div>
      </div>
    </div>
  </div>

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