<div>
  <div class="container pt-5">
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
                $unit = $units->where('id', $product->unit)->first()->title;
                $countInStores = json_decode($product->count_in_stores, true) ?? [];
                $all = collect($countInStores)->sum();
              ?>
              <input wire:model.debounce.500ms="count" type="number" class="form-control" placeholder="0">
              <span class="input-group-text">{{ $unit }}</span>
              <select wire:model.debounce.500ms="count" class="form-select">
                <option value=""></option>
                <option value="{{ $all }}">Все {{  $all . $unit; }}</option>
                @foreach($company->stores as $index => $store)
                  <option value="{{ $countInStores[$store->id] }}">{{ $store->title . ' - ' . $countInStores[$store->id] . $unit; }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col">
            <div class="card px-1 py-1 mb-3">
              <div class="doc" id="doc">
                <?php
                  $barcodes = json_decode($product->barcodes, true) ?? [];
                  $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
                ?>
                @if($companyName)
                  <h6>{{ $company->title }}</h6>
                @endif
                @if($productName)
                  <h5>{{ $product->title }}</h5>
                @endif
                @if($priceUnit)
                  <h2>{{ $product->price }} <span style="font-size: 0.7em;">{{ $company->currency->symbol.'/'.$unit }}</span></h2>
                @endif
                @if($barcode)
                  <div>
                    <img src="data:image/png;base64,{{ base64_encode($generator->getBarcode(substr($barcodes[0], 0, -1), $generator::TYPE_EAN_13)) }}">
                  </div>
                  <div>{{ $barcodes[0] }}</div>
                @endif

                <style type="text/css">
                  .doc {
                    border: 1px solid #eee;
                    font-family: Verdana, Arial, sans-serif;
                    width: <?= $size * $mmInPX ?>px;
                    background-color: #fff;
                    text-align: center;
                    margin: 0 auto;
                    padding: 2px;
                  }
                </style>
                <script>
                  function printPage() {
                    let printPriceTags = document.getElementById('doc').innerHTML;
                    let originalContents = document.body.innerHTML;
                    let count = {{ $count }};
                    let finalPriceTags;

                    for (var i = 0; i < count.length; i++) {
                      finalPriceTags = finalPriceTags + printPriceTags;
                    }

                    document.body.innerHTML = finalPriceTags;
                    window.print();
                    document.body.innerHTML = originalContents;
                  }
                </script>
              </div>
            </div>
            <div class="functions d-grid gap-2">
              <button type="button" onclick="printPage()" class="btn btn-success btn-lg">Печать ценника</button>
              <a href="/ru/cashdesk" class="btn btn-primary btn-lg">Назад</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div wire:ignore.self class="modal fade" id="priceTags" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <form wire:submit.prevent="saveCategory">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Добавить категорию</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary text-center"><i class="bi bi-hdd-fill me-2"></i> Сохранить</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
</div>
