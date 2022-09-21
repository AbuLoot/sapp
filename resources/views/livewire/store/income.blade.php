<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Новый приход</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto" style="position: relative;">
        <input wire:model="search" type="search" list="datalistOptions" class="form-control" placeholder="Поиск..." aria-label="Search">
        @if($products)
          <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
            <ul class="list-unstyled mb-0">
              @forelse($products as $product)
                <li>
                  <a wire:click="addToIncome({{ $product->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $product->title }}</a>
                </li>
              @empty
                <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
              @endforelse
            </ul>
          </div>
        @endif
      </form>

      <ul class="nav col-lg-auto text-end me-lg-2 text-small">
        <li><a href="/{{ $lang }}/storage/income/drafts" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Черновики"><i class="bi bi-journals"></i></a></li>
        <li><a href="#" wire:click="saveAsDraft" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Сохранить как черновик"><i class="bi bi-file-earmark-plus-fill"></i></a></li>
        @if($incomeProducts)
          <li><a href="#" wire:click="incomeListCount" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Провести документ"><i class="bi bi-file-earmark-ruled-fill"></i></a></li>
          <li><a href="#" wire:click="removeIncome" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Очистить все"><i class="bi bi-file-x-fill"></i></a></li>
        @endif
      </ul>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="/{{ $lang }}/storage/add-product" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i> Добавить товар</a>
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
          <option value="">Выберите склад...</option>
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
            <th scope="col">Наименование<br> товара</th>
            <th scope="col">Штрихкод</th>
            <th scope="col">Категория</th>
            <th scope="col">Цена закупки</th>
            <th scope="col">Цена продажи</th>
            <th scope="col">{{ $company->stores->where('id', $storeId)->first()->title }}</th>
            <th scope="col">В&nbsp;базе</th>
            <th scope="col">Количество</th>
            <th scope="col">Поставщик</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($incomeProducts as $index => $incomeProduct)
            <tr>
              <td><a href="/{{ $lang }}/storage/edit-product/{{ $incomeProduct->id }}">{{ $incomeProduct->title }}</a></td>
              <td>
                <?php $barcodes = json_decode($incomeProduct->barcodes, true) ?? ['']; ?>
                @foreach($barcodes as $barcode)
                  {{ $barcode }}<br>
                @endforeach
              </td>
              <td>{{ $incomeProduct->category->title }}</td>
              <td>{{ $incomeProduct->purchase_price }}</td>
              <td>{{ $incomeProduct->price }}</td>
              <?php
                $unit = $units->where('id', $incomeProduct->unit)->first()->title ?? '?';

                $countInStores = json_decode($incomeProduct->count_in_stores, true) ?? [];
                $countInStore = (isset($countInStores[$storeId])) ? $countInStores[$storeId] : 0;
              ?>
              <td>{{ $countInStore + $incomeProduct->income_count . $unit }}</td>
              <td>{{ $incomeProduct->count + $incomeProduct->income_count . $unit }}</td>
              <td class="col-2">
                <div class="input-group">
                  <input type="number" wire:model="count.{{ $incomeProduct->id }}" class="form-control @error('count.'.$incomeProduct->id) is-invalid @enderror" required>
                  <span class="input-group-text px-1-">{{ $unit }}</span>
                  @error('count.'.$incomeProduct->id)<div class="text-danger">{{ $message }}</div>@enderror
                </div>
              </td>
              <!-- <td></td> -->  
              <td>{{ $incomeProduct->company->title }}</td>
              <td class="text-end"><a wire:click="removeFromIncome({{ $incomeProduct->id }})" href="#" class="fs-4"><i class="bi bi-file-x-fill"></i></a></td>
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
        @if($incomeProducts)
          <a href="#" wire:click="makeDoc" class="btn btn-primary"><i class="bi bi-file-earmark-ruled-fill me-2"></i> Провести документ</a>
        @endif
      </div>
    </div>
  </div>
</div>
