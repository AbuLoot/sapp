<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Ревизия {{ $docNo }}</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto" style="position: relative;">
        <input wire:model="search" type="search" list="datalistOptions" class="form-control" placeholder="Поиск..." aria-label="Search">
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
        <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="journals"><i class="bi bi-journals"></i></a></li>
        <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="file-text"><i class="bi bi-file-text-fill"></i></a></li>
        <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="file-earmark"><i class="bi bi-file-earmark-plus-fill"></i></a></li>
        <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="file-earmark"><i class="bi bi-file-earmark-text-fill"></i></a></li>
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

    <div class="text-end">
      @foreach($company->stores as $index => $store)
        <div class="form-check form-check-inline">
          <input class="form-check-input" wire:model="store_id" type="radio" name="inlineRadioOptions" id="store{{ $store->id }}" value="{{ $store->id }}" @if($index == 0) checked @endif>
          <label class="form-check-label" for="store{{ $store->id }}">{{ $store->title }}</label>
        </div>
      @endforeach
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
              <td><a href="/{{ $lang }}/storage/edit-product/{{ $revisionProduct->id }}">{{ $revisionProduct->title }}</a></td>
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
                $countInStore = (isset($countInStores[$store_id])) ? $countInStores[$store_id] : 0;
                $difference = 0;

                if (isset($actualCount[$revisionProduct->id][$store_id])) {
                  $difference = $actualCount[$revisionProduct->id][$store_id] - $countInStore;
                }
              ?>
              <td>{{ $revisionProduct->count + $difference . $unit }}</td>
              <td>{{ $countInStore + $difference . $unit }}</td>
              <td class="col-2">
                <div class="input-group">
                  <input type="number" wire:model="actualCount.{{ $revisionProduct->id }}.{{ $store_id }}" class="form-control @error('actualCount.'.$revisionProduct->id.'.'.$store_id) is-invalid @enderror" required>
                  <span class="input-group-text">{{ $unit }}</span>
                </div>
                @error('actualCount.'.$revisionProduct->id.'.'.$store_id)<div class="text-danger">{{ $message }}</div>@enderror
              </td>
              <td class="text-end"><a wire:click="deleteFromRevision({{ $revisionProduct->id }})" href="#" class="fs-4"><i class="bi bi-file-x-fill"></i></a></td>
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
          <textarea wire:model="barcodeAndCount" class="form-control @error('barcodeAndCount') is-invalid @enderror" id="barcode-and-count" style="height: 120px" placeholder="Причина списания"></textarea>
          @error('barcodeAndCount')<div class="text-danger">{{ $message }}</div>@enderror
          <label for="barcode-and-count">Штрихкод и количество</label>
        </div>
      </div>
      <div class="col-auto">
        <select wire:model="store_id" class="form-control @error('store_id') is-invalid @enderror" id="store_id">
          <option value="">Выберите склад...</option>
          @foreach ($company->stores as $store)
            <option value="{{ $store->id }}"> {{ $store->title }}</option>
          @endforeach
        </select>
        @error('store_id')<div class="text-danger">{{ $message }}</div>@enderror
      </div>
      <div class="col-auto">    
        @if($revisionProducts)
          <a href="#" wire:click="makeDoc" class="btn btn-primary"><i class="bi bi-file-earmark-ruled-fill me-2"></i> Провести документ</a>
        @endif
      </div>
    </div>
  </div>
</div>
