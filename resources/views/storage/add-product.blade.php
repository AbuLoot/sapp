@extends('storage.layout')

@section('nav-functions')    
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Добавление товара</h4>
    </div>
@endsection

@section('content')
  <div class="col-lg-6">
    <div class="card bg-light">
      <div class="card-body">
        <div class="mb-3">
          <label for="title" class="form-label">Название</label>
          <input type="text" class="form-control" id="title" name="title" minlength="2" value="{{ (old('title')) ? old('title') : '' }}" required>
        </div>

        <div class="mb-3">
          <label for="doc_id" class="form-label">Номер накладной</label>
          <input type="number" class="form-control" id="doc_id" name="doc_id" value="" required>
        </div>

        <div class="row">
          <div class="col-lg-6 mb-3">
            <label for="company_id">Поставщик</label>
            <select id="company_id" name="company_id" class="form-control">
              @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->title }}</option>
              @endforeach
            </select>
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
            <input class="form-check-input" type="radio" name="type" id="product" checked>
            <label class="form-check-label" for="product">Товар</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="type" id="service">
            <label class="form-check-label" for="service">Услуга</label>
          </div>
        </div>

        <div class="row">
          <div class="col-6 mb-3">
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
          <div class="col-6 mb-3">
            <div class="d-grid" role="group" aria-label="Basic example">
              <br>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategory"><i class="bi bi-plus-circle-fill me-2"></i> Добавить категорию</button>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="mb-3">
            <label class="form-label" for="barcode">Штрихкод</label>
            <label class="form-label float-end"><a href="#"><i class="bi bi-plus-circle"></i> Дополнительный штрихкод</a></label>
            <input type="number" class="form-control" id="barcode" name="barcode" value="">
            <div class="form-text"><a href="#"><i class="bi bi-upc"></i> Сгенерировать штрихкод</a></div>
          </div>

          <div class="mb-3">
            <div class="input-group">
              <input type="number" class="form-control" id="barcode" name="barcode" value="">
              <a class="input-group-text bg-dark text-white"><i class="bi bi-x-lg"></i></a>
            </div>
            <div class="form-text"><a href="#"><i class="bi bi-upc"></i> Сгенерировать штрихкод</a></div>
          </div>
          <div class="mb-3">
            <label for="id_code">Код товара</label>
            <input type="text" class="form-control" id="id_code" name="id_code" value="">
          </div>

          <div class="mb-3">
            <label for="count">Количество</label>
            <div class="input-group">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Склад 1</button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Склад 2</a></li>
                <li><a class="dropdown-item" href="#">Склад 3</a></li>
                <li><a class="dropdown-item" href="#">Склад 4</a></li>
              </ul>
              <input type="number" class="form-control" id="count" name="count" value="1" required>
              <select id="unit_id" name="unit_id" class="form-control">
                @foreach($units as $unit)
                  <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-lg-6 mb-3">
            <label for="wholesale_price">Оптовая цена</label>
            <div class="input-group">
              <input type="text" class="form-control" id="wholesale_price" name="wholesale_price" maxlength="10" value="" required>
              <div class="input-group-text">symbol</div>
            </div>
          </div>
          <div class="col-lg-6 mb-3">
            <label for="price">Розничная цена</label>
            <div class="input-group">
              <input type="text" class="form-control" id="price" name="price" maxlength="10" value="" required>
              <div class="input-group-text">symbol</div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-grid" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-primary"><i class="bi bi-hdd-fill me-2"></i> Сохранить</button>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-grid" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-dark"><i class="be bi-printer-fill me-2"></i> Печать</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Add New Company -->
  <div class="modal fade" id="addCompany" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Добавить поставщика</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="title" class="form-label">Название компании</label>
              <input type="text" class="form-control" id="title" name="title" minlength="2" value="" required>
            </div>
            <div class="mb-3">
              <label for="tel" class="form-label">Номер телефона</label>
              <input type="tel" class="form-control" id="tel" name="tel" minlength="2" value="" required>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Адрес</label>
              <input type="text" class="form-control" id="address" name="address" minlength="2" value="" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary text-center"><i class="bi bi-hdd-fill me-2"></i> Сохранить</button>
        </div>
      </div>
    </div>
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
@endsection
