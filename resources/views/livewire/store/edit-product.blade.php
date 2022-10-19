<div>

  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <h4 class="mb-md-2 mb-lg-0">Редактирование продукта</h4>

      @can('add-product', Auth::user())
        <div class="text-end ms-md-auto ms-lg-0">
          <a href="/{{ $lang }}/storage/add-product" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i> Добавить продукт</a>
        </div>
      @endcan
    </div>
  </div>

  <div class="container">

    <!-- Toast notification -->
    <div class="toast-container position-fixed end-0 p-4">
      <div class="toast align-items-center text-bg-info border-0" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body text-white" id="toastBody"></div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>

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

    <div class="row">
      <div class="col-lg-6">
        <div class="card bg-light">
          <div class="card-body">
            <form wire:submit.prevent="saveProduct">
              <div class="mb-3">
                <label for="title" class="form-label">Название</label>
                <input type="text" wire:model.defer="product.title" class="form-control @error('product.title') is-invalid @enderror" id="title" minlength="2" required>
                @error('product.title')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="row">
                <div class="col-lg-6 mb-3">
                  <?php $companies = \App\Models\Company::where('is_supplier', 1)->get(); ?>
                  <label for="company_id">Поставщик</label>
                  <select wire:model.defer="product.company_id" class="form-control @error('product.company_id') is-invalid @enderror" id="company_id">
                    <option value="">Выберите поставщика...</option>
                    @foreach($companies as $company)
                      <option value="{{ $company->id }}">{{ $company->title }}</option>
                    @endforeach
                  </select>
                  @error('product.company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-lg-6 mb-3">
                  <div class="d-grid" role="group" aria-label="Basic example">
                    <br>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCompany"><i class="bi bi-plus-circle-fill me-2"></i> Добавить поставщика</button>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="type">Тип</label><br>
                <div class="form-check">
                  <input type="radio" wire:model="product.type" class="form-check-input" id="product" name="type" value="1">
                  <label class="form-check-label" for="product">Продукт</label>
                </div>
                <div class="form-check">
                  <input type="radio" wire:model="product.type" class="form-check-input" id="service" name="type" value="2">
                  <label class="form-check-label" for="service">Услуга</label>
                </div>
              </div>

              <div class="row">
                <div class="col-6 mb-3">
                  <label for="categoryId">Категории</label>
                  <select wire:model="product.category_id" class="form-control @error('product.category_id') is-invalid @enderror" id="categoryId" >
                    <option value="">Выберите категорию...</option>
                    <?php $traverse = function ($nodes, $prefix = null) use (&$traverse) { ?>
                      <?php foreach ($nodes as $node) : ?>
                        <option value="{{ $node->id }}"> {{ PHP_EOL.$prefix.' '.$node->title }}</option>
                        <?php $traverse($node->children, $prefix.'__'); ?>
                      <?php endforeach; ?>
                    <?php }; ?>
                    <?php $categories = \App\Models\Category::orderBy('sort_id')->get()->toTree(); ?>
                    <?php $traverse($categories); ?>
                  </select>
                  @error('product.category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6 mb-3">
                  <div class="d-grid" role="group" aria-label="Basic example">
                    <br>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategory"><i class="bi bi-plus-circle-fill me-2"></i> Добавить категорию</button>
                  </div>
                </div>
              </div>

              <div class="row">
                <div id="barcodes">
                  <div>
                    <label class="form-label" for="barcode">Штрихкод</label>
                    <label class="form-label float-end">
                      <button type="button" class="btn btn-link btn-xs m-0 p-0" wire:click.prevent="addBarcodeField"><i class="bi bi-plus-circle"></i> Дополнительный штрихкод</button>
                    </label>
                  </div>
                  @foreach($barcodes as $index => $barcode)
                    <div class="mb-3">
                      <div class="input-group">
                        <input type="number" wire:model.defer="productBarcodes.{{ $index }}" class="form-control @error('productBarcodes.'.$index) is-invalid @enderror">
                        <button type="button" wire:click.prevent="deleteBarcodeField({{ $index }})" class="btn btn-dark"><i class="bi bi-x-lg"></i></button>
                      </div>  
                      <div class="form-text"><button type="button" wire:click="generateBarcode({{ $index }})" class="btn btn-link p-0"><i class="bi bi-upc"></i> Сгенерировать штрихкод</button></div>
                      @error('productBarcodes.'.$index)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  @endforeach
                </div>

                <div class="mb-3">
                  <label for="idCode">Код продукта</label>
                  <input type="text" wire:model.defer="idCode" class="form-control" id="idCode">
                </div>
                <div class="mb-3">
                  <label for="count">Количество</label>
                  <div style="max-height: 210px; overflow-y: auto;" class="py-1">
                    @foreach($stores as $index => $store)
                      <div class="input-group mb-1">
                        <span class="input-group-text" id="{{ $store->id }}">{{ $store->title }}</span>
                        <input type="number" value="{{ $countInStores[$store->id] }}" class="form-control" disabled>
                        <select class="form-control @error('product.unit') is-invalid @enderror" wire:model="product.unit" id="unit">
                          <option value="">Ед. измерения</option>
                          @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                          @endforeach
                        </select>
                        @error('product.unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    @endforeach
                  </div>
                </div>
                <div class="col-lg-6 mb-3">
                  <label for="purchasePrice">Закупочная цена</label>
                  <div class="input-group">
                    <input type="text" wire:model="product.purchase_price" class="form-control" id="purchasePrice">
                    <div class="input-group-text">{{ $currency }}</div>
                  </div>
                </div>
                <div class="w-100"></div>
                <div class="col-lg-6 mb-3">
                  <label for="wholesalePrice">Оптовая цена</label>
                  <div class="input-group">
                    <input type="text" wire:model="wholesalePrice" class="form-control" id="wholesalePrice">
                    <div class="input-group-text">{{ $currency }}</div>
                  </div>
                </div>
                <div class="col-lg-6 mb-3">
                  <label for="wholesalePriceMarkup">Наценка</label>
                  <div class="input-group">
                    <input type="text" wire:model="wholesalePriceMarkup" class="form-control" id="wholesalePriceMarkup" placeholder="0.0">
                    <div class="input-group-text">%</div>
                  </div>
                </div>

                <div class="col-lg-6 mb-3">
                  <label for="price">Розничная цена</label>
                  <div class="input-group">
                    <input type="text" wire:model="product.price" class="form-control @error('product.price') is-invalid @enderror" id="price" required>
                    <div class="input-group-text">{{ $currency }}</div>
                  </div>
                </div>
                <div class="col-lg-6 mb-3">
                  <label for="priceMarkup">Наценка</label>
                  <div class="input-group">
                    <input type="text" wire:model="priceMarkup" class="form-control @error('priceMarkup') is-invalid @enderror" id="priceMarkup" placeholder="0.0">
                    <div class="input-group-text">%</div>
                  </div>
                </div>
                <div class="w-100"></div>
                <div class="col-lg-6 mb-3">
                  <div class="d-grid" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-hdd-fill me-2"></i> Сохранить</button>
                  </div>
                </div>
                <div class="col-lg-6 mb-3">
                  <div class="d-grid" role="group" aria-label="Basic example">
                    <a href="/{{ $lang }}/storage/pricetags/{{ http_build_query([$product->id]) }}" class="btn btn-dark"><i class="be bi-printer-fill me-2"></i> Печать</a>
                    <!-- <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#priceTags"><i class="be bi-printer-fill me-2"></i> Печать</button> -->
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="row">
          <div class="offset-lg-6 col-lg-6">
            <div class="d-grid" role="group" aria-label="Basic example">
              <a href="/{{ $lang }}/storage" class="btn btn-secondary"><i class="be bi-arrow-left-circle-fill me-2"></i> Назад</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Add Company -->
  <livewire:store.add-company>

  <!-- Modal Add Category -->
  <livewire:store.add-category>

</div>

@section('scripts')
  <script type="text/javascript">
    window.addEventListener('show-toast', event => {
      if (event.detail.selector) {
        const btnCloseModal = document.getElementById(event.detail.selector)
        btnCloseModal.click()
      }

      const toast = new bootstrap.Toast(document.getElementById('liveToast'))
      toast.show()

      const toastBody = document.getElementById('toastBody')
      toastBody.innerHTML = event.detail.message
    })
  </script>
@endsection