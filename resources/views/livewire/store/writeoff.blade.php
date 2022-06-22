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

      <ul class="nav col-lg-auto text-end me-lg-2 text-small">
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
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="container">

    <div class="text-end">
      @foreach($company->stores as $index => $store)
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="inlineRadioOptions" id="store{{ $store->id }}" value="{{ $store->id }}" @if($index == 0) checked @endif>
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
            <th scope="col">Цена оптовая</th>
            <th scope="col">Цена продажи</th>
            <th scope="col">В&nbsp;базе</th>
            <th scope="col">Количество</th>
            <th class="text-end" scope="col">Функции</th>
          </tr>
        </thead>
        <tbody>
          @forelse($writeoffProducts as $index => $writeoffProduct)
            <tr>
              <td><a href="/{{ $lang }}/store/edit-product/{{ $writeoffProduct->id }}">{{ $writeoffProduct->title }}</a></td>
              <td>
                <?php $barcodes = json_decode($writeoffProduct->barcodes, true) ?? ['']; ?>
                @foreach($barcodes as $barcode)
                  {{ $barcode }}<br>
                @endforeach
              </td>
              <td>{{ $writeoffProduct->category->title }}</td>
              <td>{{ $writeoffProduct->purchase_price }}</td>
              <td>{{ $writeoffProduct->wholesale_price }}</td>
              <td>{{ $writeoffProduct->price }}</td>
              <?php $unit = $units->where('id', $writeoffProduct->unit)->first()->title ?? '?'; ?>
              <td>{{ $writeoffProduct->count + $writeoffProduct->writeoff_count . $unit }}</td>
              <td class="col-2">
                <div class="input-group input-group-sm">
                  <input type="number" wire:model="count.{{ $writeoffProduct->id }}" class="form-control @error('count.'.$writeoffProduct->id) is-invalid @enderror" required>
                  <span class="input-group-text px-1-">{{ $unit }}</span>
                  @error('count.'.$writeoffProduct->id)<div class="text-danger">{{ $message }}</div>@enderror
                </div>
              </td>
              <!-- <td></td> -->
              <td>{{ $writeoffProduct->company->title }}</td>
              <td class="text-end"><a wire:click="deleteFromWriteoff({{ $writeoffProduct->id }})" href="#" class="fs-5"><i class="bi bi-file-x-fill"></i></a></td>
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
      <div class="col-auto">
        <div class="mb-3">
          <label for="comment" class="form-label">Комментарии</label>
          <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" rows="3"></textarea>
          @error('comment')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>
  </div>
</div>
