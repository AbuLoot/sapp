<div>

  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <h4 class="mb-md-2 mb-lg-0">Редактирование товара</h4>
    </div>
  </div>

  <div class="container">
    @if(session()->has('message'))
      <div class="toast-container position-fixed bottom-0 end-0 p-4">
        <div class="toast align-items-center text-bg-info border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body text-white-">{{ session('message') }}</div>
            <button type="button" class="btn-close btn-close-white- me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
                  <label class="form-check-label" for="product">Товар</label>
                </div>
                <div class="form-check">
                  <input type="radio" wire:model="product.type" class="form-check-input" id="service" name="type" value="2">
                  <label class="form-check-label" for="service">Услуга</label>
                </div>
              </div>

              <div class="row">
                <div class="col-6 mb-3">
                  <label for="category_id">Категории</label>
                  <select wire:model="product.category_id" class="form-control @error('product.category_id') is-invalid @enderror" id="category_id" >
                    <option value="">Выберите категорию...</option>
                    <?php $traverse = function ($nodes, $prefix = null) use (&$traverse) { ?>
                      <?php foreach ($nodes as $node) : ?>
                        <option value="{{ $node->id }}"> {{ PHP_EOL.$prefix.' '.$node->title }}</option>
                        <?php $traverse($node->children, $prefix.'__'); ?>
                      <?php endforeach; ?>
                    <?php }; ?>
                    <?php $categories = \App\Models\Category::get()->toTree(); ?>
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
                        <input type="number" wire:model.defer="productBarcodes.{{ $index }}" class="form-control @error('productBarcodes.{{ $index }}') is-invalid @enderror">
                        <button type="button" wire:click.prevent="deleteBarcodeField({{ $index }})" class="btn btn-dark"><i class="bi bi-x-lg"></i></button>
                      </div>  
                      <div class="form-text"><button type="button" wire:click="generateBarcode({{ $index }})" class="btn btn-link p-0"><i class="bi bi-upc"></i> Сгенерировать штрихкод</button></div>
                      @error('productBarcodes.{{ $index }}')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  @endforeach
                </div>

                @if($product->type == 1)
                  <div class="mb-3">
                    <label for="id_code">Код товара</label>
                    <input type="text" wire:model.defer="id_code" class="form-control @error('id_code') is-invalid @enderror" id="id_code">
                    @error('id_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="mb-3">
                    <label for="count">Количество</label>
                    <div class="input-group">
                      <button class="btn btn-outline-secondary w-25 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Склад 1</button>
                      <ul class="dropdown-menu">
                        @foreach($stores as $store)
                          <li><a class="dropdown-item" href="{{ $store->id }}#">{{ $store->title }}</a></li>
                        @endforeach
                      </ul>
                      <input type="number" wire:model.defer="product.count" class="form-control @error('product.count') is-invalid @enderror" id="count" required>
                      <select class="form-control @error('unit') is-invalid @enderror" wire:model.defer="unit" id="unit">
                        @foreach($units as $unit)
                          <option value="{{ $unit->title }}">{{ $unit->title }}</option>
                        @endforeach
                      </select>
                      @error('product.count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                  <div class="col-lg-6 mb-3">
                    <label for="purchase_price">Закупочная цена</label>
                    <div class="input-group">
                      <input type="text" wire:model="purchase_price" class="form-control" id="purchase_price" required>
                      <div class="input-group-text">{{ $currency }}</div>
                    </div>
                  </div>
                  <div class="w-100"></div>
                  <div class="col-lg-6 mb-3">
                    <label for="wholesale_price">Оптовая цена</label>
                    <div class="input-group">
                      <input type="text" wire:model="wholesale_price" class="form-control" id="wholesale_price" required>
                      <div class="input-group-text">{{ $currency }}</div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-3">
                    <label for="wholesale_price_markup">Наценка</label>
                    <div class="input-group">
                      <input type="text" wire:model="wholesale_price_markup" class="form-control" id="wholesale_price_markup">
                      <div class="input-group-text">%</div>
                    </div>
                  </div>
                @endif

                <div class="col-lg-6 mb-3">
                  <label for="price">Розничная цена</label>
                  <div class="input-group">
                    <input type="text" wire:model="product.price" class="form-control @error('product.price') is-invalid @enderror" id="price" required>
                    <div class="input-group-text">{{ $currency }}</div>
                  </div>
                </div>
                <div class="col-lg-6 mb-3">
                  <label for="price_markup">Наценка</label>
                  <div class="input-group">
                    <input type="text" wire:model="price_markup" class="form-control @error('price_markup') is-invalid @enderror" id="price_markup">
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
                    <button type="button" class="btn btn-dark"><i class="be bi-printer-fill me-2"></i> Печать</button>
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
              <a href="/{{ $lang }}/store" class="btn btn-secondary"><i class="be bi-arrow-left-circle-fill me-2"></i> Назад</a>
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
