<div>

  @if (session()->has('message'))
    <div class="toast-container position-fixed bottom-0 end-0 p-4">
      <div class="toast align-items-center text-bg-info border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body text-white">{{ session('message') }}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
  @endif

  <div class="col-lg-6">
    <div class="card bg-light">
      <div class="card-body">
        <form wire:submit.prevent="saveProduct()">
          <div class="mb-3">
            <label for="title" class="form-label">Название</label>
            <input type="text" class="form-control @error('product.title') is-invalid @enderror" id="title" wire:model.defer="product.title" minlength="2" required>
            @error('product.title')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label for="doc_no" class="form-label">Номер накладной</label>
            <input type="number" class="form-control @error('product.doc_no') is-invalid @enderror" id="doc_no" wire:model.defer="product.doc_no" required>
            @error('product.doc_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="row">
            <div class="col-lg-6 mb-3">
              <label for="company_id">Поставщик</label>
              <select wire:model.defer="product.company_id" class="form-control @error('product.company_id') is-invalid @enderror" id="company_id">
                <option>Выберите поставщика...</option>
                @foreach($companies as $company)
                  <option value="{{ $company->id }}">{{ $company->title }}</option>
                @endforeach
              </select>
              @error('product.company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-lg-6 mb-3">
              <div class="d-grid" role="group" aria-label="Basic example">
                <br>
                <button type="button" class="btn btn-success" wire:click="addCompany" data-bs-toggle="modal" data-bs-target="#addCompany"><i class="bi bi-plus-circle-fill me-2"></i> Добавить поставщика</button>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="type">Тип</label><br>
            <div class="form-check">
              <input class="form-check-input @error('product.product') is-invalid @enderror" id="product" name="type" type="radio" wire:model.defer="type" value="1">
              <label class="form-check-label" for="product">Товар</label>
            </div>
            <div class="form-check">
              <input class="form-check-input @error('product.service') is-invalid @enderror" id="service" name="type" type="radio" wire:model.defer="type" value="2">
              <label class="form-check-label" for="service">Услуга</label>
            </div>
          </div>

          <div class="row">
            <div class="col-6 mb-3">
              <label for="category_id">Категории</label>
              <select wire:model.defer="product.category_id" class="form-control @error('product.category_id') is-invalid @enderror" id="category_id" >
                <option>Выберите категорию...</option>
                <?php $traverse = function ($nodes, $prefix = null) use (&$traverse) { ?>
                  <?php foreach ($nodes as $node) : ?>
                    <option value="{{ $node->id }}"> {{ PHP_EOL.$prefix.' '.$node->title }}</option>
                    <?php $traverse($node->children, $prefix.'__'); ?>
                  <?php endforeach; ?>
                <?php }; ?>
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
              <div class="mb-3">
                <label class="form-label" for="barcode">Штрихкод</label>
                <label class="form-label float-end"><button type="button" class="btn btn-link btn-xs m-0 p-0" onclick="addBarcodeInput(this)"><i class="bi bi-plus-circle"></i> Дополнительный штрихкод</button></label>
                <input type="number" class="form-control @error('product.barcode') is-invalid @enderror" id="barcode" wire:model.defer="product.barcode" value="">
                <div class="form-text"><a href="#"><i class="bi bi-upc"></i> Сгенерировать штрихкод</a></div>
                @error('product.barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="mb-3">
              <label for="id_code">Код товара</label>
              <input type="text" class="form-control @error('id_code') is-invalid @enderror" id="id_code" wire:model.defer="id_code" value="">
              @error('id_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label for="count">Количество</label>
              <div class="input-group">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Склад 1</button>
                <ul class="dropdown-menu">
                  @foreach($stores as $store)
                    <li><a class="dropdown-item" href="{{ $store->id }}#">{{ $store->title }}</a></li>
                  @endforeach
                </ul>
                <input type="number" class="form-control @error('product.count') is-invalid @enderror" id="count" wire:model.defer="product.count" value="1" required>
                <select class="form-control @error('product.unit_id') is-invalid @enderror" id="unit_id" wire:model.defer="product.unit_id">
                  @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                  @endforeach
                </select>
                @error('product.unit_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="col-lg-6 mb-3">
              <label for="wholesale_price">Оптовая цена</label>
              <div class="input-group">
                <input type="text" class="form-control @error('wholesale_price') is-invalid @enderror" id="wholesale_price" wire:model.defer="wholesale_price" required>
                <div class="input-group-text">₸</div>
                @error('wholesale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="col-lg-6 mb-3">
              <label for="price">Розничная цена</label>
              <div class="input-group">
                <input type="text" class="form-control @error('product.price') is-invalid @enderror" id="price" wire:model.defer="product.price" required>
                <div class="input-group-text">₸</div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="d-grid" role="group" aria-label="Basic example">
                <button type="submit" class="btn btn-primary"><i class="bi bi-hdd-fill me-2"></i> Сохранить</button>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="d-grid" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-dark"><i class="be bi-printer-fill me-2"></i> Печать</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div>
    <livewire:store.company-form>
  </div>

  <!-- Modal Add New Category -->
  <div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Добавить категорию</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="title" class="form-label">Название</label>
              <input type="text" class="form-control" id="title" name="title" minlength="2" value="" required>
            </div>
            <div class="mb-3">
              <label for="category_id">Категории</label>
              <select id="category_id" name="category_id" class="form-control">
                <?php $traverse = function ($nodes, $prefix = null) use (&$traverse) { ?>
                  <?php foreach ($nodes as $node) : ?>
                    <option value="{{ $node->id }}"> {{ PHP_EOL.$prefix.' '.$node->title }}</option>
                    <?php $traverse($node->children, $prefix.'__'); ?>
                  <?php endforeach; ?>
                <?php }; ?>
                <?php $traverse($categories); ?>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary text-center"><i class="bi bi-hdd-fill me-2"></i> Сохранить</button>
        </div>
      </div>
    </div>
  </div>
</div>
