<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Ценники</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto" style="position: relative;">
        <div class="input-group">
          <input wire:model="search" type="search" class="form-control" id="search" onclick="setFocus('search')" placeholder="Поиск..." aria-label="Search">
          <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
        </div>
        @if($productsObj)
          <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
            <ul class="list-unstyled mb-0">
              @forelse($productsObj as $productObj)
                <li>
                  <a wire:click="addToPriceTag({{ $productObj->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $productObj->title }}</a>
                </li>
              @empty
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
              @endforelse
            </ul>
          </div>
        @endif
      </form>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="/{{ $lang }}/storage" class="btn btn-secondary"><i class="be bi-arrow-left-circle-fill me-2"></i> Назад</a>
      </div>
    </div>
  </div>

  <!-- Keyboard -->
  <livewire:keyboard>

  <div class="container">
    <div class="row">
      <div class="offset-lg-2 col-lg-8">
        <div class="row">
          <div class="col-6">
            <div class="list-group mb-3">
              <label class="list-group-item">
                <input wire:model="companyName" class="form-check-input me-2" type="checkbox">
                <span>Company Name</span>
              </label>
              <label class="list-group-item">
                <input wire:model="productName" class="form-check-input me-2" type="checkbox">
                <span>Product Name</span>
              </label>
              <label class="list-group-item">
                <input wire:model="priceUnit" class="form-check-input me-2" type="checkbox" disabled>
                <span>Price/Unit</span>
              </label>
              <label class="list-group-item">
                <input wire:model="barcode" class="form-check-input me-2" type="checkbox">
                <span>Barcode</span>
              </label>
            </div>
            <label class="form-label">Ширина ценника</label>
            <div class="input-group mb-3">
              <input wire:model.debounce.500ms="size" type="number" class="form-control" placeholder="00мм">
              <span class="input-group-text">мм</span>
              <select wire:model.debounce.500ms="size" class="form-select">
                <option value=""></option>
                <option value="90">90мм</option>
                <option value="80">80мм</option>
                <option value="70">70мм</option>
                <option value="60">60мм</option>
              </select>
            </div>
            <label class="form-label">Количество ценников</label>
            <div class="input-group mb-3">
              <?php
                $unitTitles = [];
                $countStores = [];
                $all = null;

                foreach ($products as $index => $product) {
                  $unitTitles[$product->id] = $units->where('id', $product->unit)->first()->title ?? 'шт';
                  $countStores[$product->id] = json_decode($product->count_in_stores, true) ?? [];
                  $all += collect($countStores[$product->id])->sum();
                }
              ?>
              <input wire:model.debounce.500ms="count" type="number" class="form-control" placeholder="0">
              <span class="input-group-text">шт</span>
              <select wire:model.debounce.500ms="count" class="form-select">
                <option value=""></option>
                <option value="{{ $all }}">Все {{  $all . 'шт' }}</option>
                @foreach($company->stores as $index => $store)
                  <?php $totalCountStores = 0; ?>
                  @foreach ($products as $index => $product)
                    <?php $totalCountStores += isset($countStores[$product->id][$store->id]) ? $countStores[$product->id][$store->id] : 0; ?>
                  @endforeach
                  <option value="{{ $totalCountStores }}">{{ $store->title . ' - ' . $totalCountStores . 'шт' }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col">
            <div class="card overflow-auto px-1 py-1 mb-3">
              <?php
                $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
                $priceTagContent = '';
                $companyNameEl = '';

                if ($companyName) {
                  $companyNameEl = '<h6>'.$company->title.'</h6>';
                }

                foreach ($products as $index => $product) {

                  $barcodes = json_decode($product->barcodes, true) ?? [];
                  $barcodeEl = '';
                  $titleEl = '';
                  $priceEl = '';

                  if ($barcode AND $barcodes) {
                    $barcodeEl = '<div><img src="data:image/png;base64,'.base64_encode($generator->getBarcode(substr($barcodes[0], 0, -1), $generator::TYPE_EAN_13)).'"></div>';
                    $barcodeEl .= '<div>'.$barcodes[0].'</div>';
                  }

                  if ($productName) {
                    $titleEl = '<h5>'.$product->title.'</h5>';
                  }

                  if ($priceUnit) {
                    $priceEl = '<h3>'.$product->price.'<span style="font-size: 0.7em;">'.$company->currency->symbol.'/'.$unitTitles[$product->id].'</span></h3>';
                  }

                  for ($i=0; $i < $count; $i++) {
                    $priceTagContent .= '<div class="price-tag">';
                      $priceTagContent .= $companyNameEl;
                      $priceTagContent .= $titleEl;
                      $priceTagContent .= $priceEl;
                      $priceTagContent .= $barcodeEl;
                    $priceTagContent .= '</div>';
                  }
                }
              ?>

              <!-- Display Price Tags -->
              <div class="doc" id="doc">

                {!! $priceTagContent !!}

                <style type="text/css">
                  .card {
                    height: 250px;
                  }
                  .price-tag {
                    border: 1px solid #333;
                    font-family: Verdana, Arial, sans-serif;
                    width: <?= $size * $mmInPX ?>px;
                    background-color: #fff;
                    text-align: center;
                    margin: 0 auto 2px auto;
                    padding: 5px 2px 2px;
                  }
                </style>
              </div>
            </div>
            <div class="functions d-grid gap-2">
              <button type="button" onclick="printPage()" class="btn btn-dark">Печать ценника</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    function printPage() {
      let printPriceTags = document.getElementById('doc').innerHTML;
      let originalContents = document.body.innerHTML;

      document.body.innerHTML = printPriceTags;
      window.print();
      document.body.innerHTML = originalContents;
    }
  </script>
</div>

@section('scripts')
<script type="text/javascript">
  // Offcanvas
  const offcanvas = new bootstrap.Offcanvas('#offcanvas', { backdrop: false, scroll: true })

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