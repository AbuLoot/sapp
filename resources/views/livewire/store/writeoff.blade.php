<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Списание</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto" style="position: relative;">
        <input wire:model="search" type="search" list="datalistOptions" class="form-control" placeholder="Поиск..." aria-label="Search">
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

      <!-- <ul class="nav col-lg-auto text-end me-lg-2 text-small">
        <li>
          <a href="#" class="nav-link text-primary"><i class="bi bi-journals"></i></a>
        </li>
        <li>
          <a href="#" class="nav-link text-primary"><i class="bi bi-file-text-fill"></i></a>
        </li>
        <li>
          <a href="#" class="nav-link text-primary"><i class="bi bi-file-earmark-plus-fill"></i></a>
        </li>
        <li>
          <a href="#" class="nav-link text-primary"><i class="bi bi-file-earmark-text-fill"></i></a>
        </li>
      </ul>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="#" wire:click="makeDoc" class="btn btn-primary"><i class="bi bi-file-earmark-ruled-fill me-2"></i> Провести документ</a>
      </div> -->
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
          <input class="form-check-input" type="radio" wire:model="store_id" id="store{{ $store->id }}" value="{{ $store->id }}" @if($store_id == $store->id) checked @endif>
          <label class="form-check-label" for="store{{ $store->id }}">{{ $store->title }}</label>
        </div>
      @endforeach
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
            <th scope="col">{{ $company->stores->where('id', $store_id)->first()->title }}</th>
            <th scope="col">Общее кол-во</th>
            <th scope="col">Кол-во</th>
            <th scope="col">Поставщик</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($writeoffProducts as $index => $writeoffProduct)
            <tr>
              <td><a href="/{{ $lang }}/store/edit-product/{{ $writeoffProduct->id }}">{{ $writeoffProduct->title }}</a></td>
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
                $countInStore = (isset($countInStores[$store_id])) ? $countInStores[$store_id] : 0;

                $writeoffCountProduct = 0;

                if (isset($writeoff_count[$writeoffProduct->id][$store_id])) {
                  if ($countInStore >= 1 && $writeoff_count[$writeoffProduct->id][$store_id] <= $countInStore) {
                    $writeoffCountProduct = $writeoff_count[$writeoffProduct->id][$store_id];
                  } elseif ($countInStore < $writeoff_count[$writeoffProduct->id][$store_id]) {
                    $writeoffCountProduct = $countInStore;
                  }
                }
              ?>
              <td>{{ $countInStore - $writeoffCountProduct . $unit }}</td>
              <td>{{ $writeoffProduct->count - $writeoffCountProduct . $unit }}</td>
              <td class="col-2">
                <div class="input-group">
                  <input type="number" wire:model="writeoff_count.{{ $writeoffProduct->id }}.{{ $store_id }}" class="form-control @error('writeoff_count.'.$writeoffProduct->id.'.'.$store_id) is-invalid @enderror" required>
                  <span class="input-group-text">{{ $unit }}</span>
                </div>
              </td>
              <td>{{ $writeoffProduct->company->title }}</td>
              <td class="text-end"><a wire:click="deleteFromWriteoff({{ $writeoffProduct->id }})" href="#" class="fs-4"><i class="bi bi-file-x-fill"></i></a></td>
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
          <textarea wire:model="comment" class="form-control @error('comment') is-invalid @enderror" id="comment" style="height: 100px" placeholder="Причина списания"></textarea>
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
  </div>
</div>
