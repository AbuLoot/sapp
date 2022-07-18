<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Ревизия {{ $revision->doc_no }}</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
        <input wire:model="search" type="search" class="form-control" placeholder="Поиск..." aria-label="Search">
      </form>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="/{{ $lang }}/inventory-history" class="btn btn-primary"><i class="bi bi-clock-history me-2"></i> История ревизий</a>
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
          @forelse($revisions as $index => $revision)
            <tr>
              <td><a href="/{{ $lang }}/storage/edit-product/{{ $revision->id }}">{{ $revision->title }}</a></td>
              <td>
                <?php $products_data = json_decode($revision->products_data, true) ?? ['']; ?>
                @foreach($products_data as $product_id => $product_data)
                  {{ $product_id }}<br>
                @endforeach
              </td>
              <td>{{ $revision->company->title }}</td>
              <td>{{ $revision->purchase_price }}</td>
              <td>{{ $revision->price }}</td>
              <?php
                // $unit = $units->where('id', $revision->unit)->first()->title ?? '?';

                // $countInStores = json_decode($revision->count_in_stores, true) ?? [];
                // $countInStore = (isset($countInStores[$store_id])) ? $countInStores[$store_id] : 0;
                // $difference = 0;

                // if (isset($actualCount[$revision->id][$store_id])) {
                //   $difference = $actualCount[$revision->id][$store_id] - $countInStore;
                // }
              ?>
              <td>{{ $revision->count + $difference . $unit }}</td>
              <td>{{ $countInStore + $difference . $unit }}</td>
              <td class="col-2">
                <div class="input-group">
                  <input type="number" wire:model="actualCount.{{ $revision->id }}.{{ $store_id }}" class="form-control @error('actualCount.'.$revision->id.'.'.$store_id) is-invalid @enderror" required>
                  <span class="input-group-text">{{ $unit }}</span>
                </div>
                @error('actualCount.'.$revision->id.'.'.$store_id)<div class="text-danger">{{ $message }}</div>@enderror
              </td>
              <td class="text-end"><a wire:click="deleteFromRevision({{ $revision->id }})" href="#" class="fs-4"><i class="bi bi-file-x-fill"></i></a></td>
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
        @if($revisions)
          <a href="#" wire:click="makeDoc" class="btn btn-primary"><i class="bi bi-file-earmark-ruled-fill me-2"></i> Провести документ</a>
        @endif
      </div>
    </div>
  </div>

  <!-- Modal Barcodes Count Result -->
  <div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</div>
