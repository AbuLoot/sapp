@extends('joystick.layout')

@section('content')
  <h2 class="page-header">Редактирование</h2>

  @include('joystick.partials.alerts')

  <div class="row">
    <div class="col-md-6">
      
    </div>
    <div class="col-md-6">
      <p class="text-right">
        <a href="/{{ $lang }}/admin/products" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
      </p>
    </div>
  </div><br>

  <form action="/{{ $lang }}/admin/products/{{ $product->id }}" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    {!! csrf_field() !!}
    <div class="row">
      <div class="col-md-7">
        <div class="panel panel-default">
          <div class="panel-heading">Основная информация</div>
          <div class="panel-body">
            <div class="form-group">
              <label for="title">Название</label>
              <input type="text" class="form-control" id="title" name="title" minlength="2" value="{{ (old('title')) ? old('title') : $product->title }}" required>
            </div>
            <div class="form-group">
              <label for="slug">Slug</label>
              <input type="text" class="form-control" id="slug" name="slug" minlength="2" value="{{ (old('slug')) ? old('slug') : $product->slug }}">
            </div>
            <div class="form-group">
              <label for="sort_id">Порядковый номер</label>
              <input type="text" class="form-control" id="sort_id" name="sort_id" maxlength="5" value="{{ (old('sort_id')) ? old('sort_id') : $product->sort_id }}">
            </div>
            <div class="form-group">
              <label for="meta_title">Мета заголовок</label>
              <input type="text" class="form-control" id="meta_title" name="meta_title" maxlength="255" value="{{ (old('meta_title')) ? old('meta_title') : $product->meta_title }}" required>
            </div>
            <div class="form-group">
              <label for="meta_description">Мета описание</label>
              <input type="text" class="form-control" id="meta_description" name="meta_description" maxlength="255" value="{{ (old('meta_description')) ? old('meta_description') : $product->meta_description }}">
            </div>
            <div class="form-group">
              <label for="description">Описание</label>
              <textarea class="form-control" id="summernote" name="description" rows="5" maxlength="2000">{{ (old('description')) ? old('description') : $product->description }}</textarea>
            </div>
            <div class="form-group">
              <label for="characteristic">Характеристика</label>
              <input type="text" class="form-control" id="characteristic" name="characteristic" minlength="2" value="{{ (old('characteristic')) ? old('characteristic') : $product->characteristic }}">
            </div>
            <div class="row">
              <div class="col-md-4 col-xs-6">
                <div class="form-group">
                  <label for="unit">Ед. измерения</label>
                  <select id="unit" name="unit" class="form-control" required>
                    @foreach($units as $unit)
                      <option value="{{ $unit->id }}" @if($unit->id == $product->unit) selected @endif>{{ $unit->title }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <?php $parameters = json_decode($product->parameters); ?>
              <div class="col-md-2 col-xs-6">
                <div class="form-group">
                  <label for="weight">Вес</label>
                  <input type="text" class="form-control" id="weight" name="weight" value="{{ (old('weight')) ? old('weight') : $parameters->weight ?? '' }}">
                </div>
              </div>
              <div class="col-md-2 col-xs-4">
                <div class="form-group">
                  <label for="length">Длина</label>
                  <input type="text" class="form-control" id="length" name="length" value="{{ (old('length')) ? old('length') : $parameters->length ?? '' }}">
                </div>
              </div>
              <div class="col-md-2 col-xs-4">
                <div class="form-group">
                  <label for="width">Ширина</label>
                  <input type="text" class="form-control" id="width" name="width" value="{{ (old('width')) ? old('width') : $parameters->width ?? '' }}">
                </div>
              </div>
              <div class="col-md-2 col-xs-4">
                <div class="form-group">
                  <label for="height">Высота</label>
                  <input type="text" class="form-control" id="height" name="height" value="{{ (old('height')) ? old('height') : $parameters->height ?? '' }}">
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <?php $barcodes = json_decode($product->barcodes, true) ?? []; ?>
              @foreach($barcodes as $key => $barcode)
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="barcode">Штрихкод {{ ++$key }}</label>
                    <input type="text" class="form-control" id="barcode" name="barcodes[]" value="{{ $barcode }}">
                  </div>
                </div>
              @endforeach
              <div class="col-md-6">
                <div class="form-group">
                  <label for="id_code">Код товара</label>
                  <input type="text" class="form-control" id="id_code" name="id_code" value="{{ (old('id_code')) ? old('id_code') : $product->id_code }}">
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="purchase_price">Закупочная цена</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="purchase_price" name="purchase_price" value="{{ (old('purchase_price')) ? old('purchase_price') : $product->purchase_price }}">
                    <div class="input-group-addon">{{ $currency->symbol }}</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="count">Количество</label>
                  <input type="number" class="form-control" id="count" name="count" value="{{ (old('count')) ? old('count') : $product->count }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="wholesale_price">Оптовая цена</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="wholesale_price" name="wholesale_price" value="{{ (old('wholesale_price')) ? old('wholesale_price') : $product->wholesale_price }}">
                    <div class="input-group-addon">{{ $currency->symbol }}</div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="price">Розничная цена</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="price" name="price" value="{{ (old('price')) ? old('price') : $product->price }}" required>
                    <div class="input-group-addon">{{ $currency->symbol }}</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="type">Тип</label><br>
              <label class="radio-inline">
                <input type="radio" name="type" value="1" @if ($product->type == '1') checked @endif> Товар
              </label>
              <label class="radio-inline">
                <input type="radio" name="type" value="2" @if ($product->type == '2') checked @endif> Услуга
              </label>
            </div>
            <div class="row" id="gallery">
              <div class="col-md-12">
                <label>Галерея</label><br>
              </div>
              <?php $images = ($product->images) ? unserialize($product->images) : []; ?>
              <?php $key_last = array_key_last($images); ?>
              @for ($i = 0; $i <= (($key_last >= 4) ? $key_last : 3); $i++)
                @if(array_key_exists($i, $images))
                  <div class="col-md-6 col-xs-12 fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width:100%;height:200px;">
                      <img src="/img/products/{{ $product->path.'/'.$images[$i]['present_image'] }}">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="width:100%;height:200px;" data-trigger="fileinput"></div>
                    <div>
                      <span class="btn btn-default btn-sm btn-file">
                        <span class="fileinput-new"><i class="glyphicon glyphicon-folder-open"></i>&nbsp; Изменить</span>
                        <span class="fileinput-exists"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;</span>
                        <input type="file" name="images[]" accept="image/*" multiple>
                      </span>
                      <label>
                        <input type="checkbox" name="remove_images[]" value="{{ $i }}"> Удалить
                      </label>
                      <a href="#" class="btn btn-default btn-sm fileinput-exists" data-dismiss="fileinput"><i class="glyphicon glyphicon-trash"></i> Удалить</a>
                    </div>
                  </div>
                @else
                  <div class="col-md-6 col-xs-12 fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-preview thumbnail" style="width:100%;height:200px;" data-trigger="fileinput"></div>
                    <div>
                      <span class="btn btn-default btn-sm btn-file">
                        <span class="fileinput-new"><i class="glyphicon glyphicon-folder-open"></i>&nbsp; Выбрать</span>
                        <span class="fileinput-exists"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;</span>
                        <input type="file" name="images[]" accept="image/*" multiple>
                      </span>
                      <a href="#" class="btn btn-default btn-sm fileinput-exists" data-dismiss="fileinput"><i class="glyphicon glyphicon-trash"></i> Удалить</a>
                    </div>
                  </div>
                @endif
              @endfor
            </div>
            <div class="form-group">
              <button type="button" class="btn btn-success" onclick="addFileinput(this);">Добавить загрузчик</button>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="lang">Язык</label>
                  <select id="lang" name="lang" class="form-control" required>
                    @foreach($languages as $language)
                      @if ($language->slug == $product->lang)
                        <option value="{{ $language->slug }}" selected>{{ $language->title }}</option>
                      @else
                        <option value="{{ $language->slug }}">{{ $language->title }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="status">Статус</label>
                  <select id="status" name="status" class="form-control" required>
                    @foreach(trans('statuses.product') as $num => $status)
                      @if ($num == $product->status)
                        <option value="{{ $num}}" selected>{{ $status['title'] }}</option>
                      @else
                        <option value="{{ $num}}">{{ $status['title'] }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Параметры</div>
          <div class="panel-body">

            <div class="form-group">
              <label for="company_id">Компания</label>
              <select id="company_id" name="company_id" class="form-control">
                <option value=""></option>
                @foreach($companies as $company)
                  @if ($company->id == $product->company_id)
                    <option value="{{ $company->id }}" selected>{{ $company->title }}</option>
                  @else
                    <option value="{{ $company->id }}">{{ $company->title }}</option>
                  @endif
                @endforeach
              </select>
            </div>

            <p><b>Проекты</b></p>
            <select name="project_id" class="form-control" size="15">
              <?php $traverse = function ($nodes, $prefix = null) use (&$traverse, $product) { ?>
                <?php foreach ($nodes as $node) : ?>
                  <option value="{{ $node->id }}" <?php if ($product->project_id == $node->id) echo "selected"; ?>>{{ PHP_EOL.$prefix.' '.$node->title }}</option>
                  <?php $traverse($node->children, $prefix.'___'); ?>
                <?php endforeach; ?>
              <?php }; ?>
              <?php $traverse($projects); ?>
            </select><br>

            <p><b>Категории</b></p>
            <div class="panel panel-default">
              <div class="panel-body" style="max-height: 250px; overflow-y: auto;">
                <?php $traverse = function ($nodes, $prefix = null) use (&$traverse, $product) { ?>
                  <?php foreach ($nodes as $node) : ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="category_id" value="{{ $node->id }}" <?php if ($product->category_id == $node->id) echo "checked"; ?>> {{ PHP_EOL.$prefix.' '.$node->title }}
                      </label>
                    </div>
                  <?php $traverse($node->children, $prefix.'___'); ?>
                  <?php endforeach; ?>
                <?php }; ?>
                <?php $traverse($categories); ?>
              </div>
            </div>

            <p><b>Опции</b></p>
            <div class="panel panel-default">
              <div class="panel-body" style="max-height: 250px; overflow-y: auto;">
                <?php $grouped = $options->groupBy('data'); ?>
                @forelse ($grouped as $data => $group)
                  <?php $data = json_decode($data, true); ?>
                  <p><b>{{ $data[$lang]['data'] }}</b></p>
                  @foreach ($group as $option)
                    <?php $titles = json_decode($option->title, true); ?>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="options_id[]" value="{{ $option->id }}" @if ($product->options->contains($option->id)) checked @endif> {{ $titles[$lang]['title'] }}
                      </label>
                    </div>
                  @endforeach
                @endforeach
              </div>
            </div>

            <p><b>Режимы</b></p>
            <div class="panel panel-default">
              <div class="panel-body" style="max-height: 150px; overflow-y: auto;">
                @foreach($modes as $mode)
                  <?php $titles = unserialize($mode->title); ?>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="modes_id[]" value="{{ $mode->id }}" <?php if ($product->modes->contains($mode->id)) echo "checked"; ?>> {{ $titles[$lang]['title'] }}
                    </label>
                  </div>
                @endforeach
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-success"><i class="material-icons">save</i></button>
    </div>
  </form>
@endsection

@section('head')
  <link href="/joystick/css/jasny-bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('scripts')
  <script src="/joystick/js/jasny-bootstrap.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
  <script>
    /* Summernote */
    $(document).ready(function() {
      $('#summernote').summernote({
        height: 150
      });
    });
  </script>

  <script>
    function addFileinput(i) {
      var fileinput =
        '<div class="col-md-6 col-xs-12 fileinput fileinput-new" data-provides="fileinput">' +
          '<div class="fileinput-preview thumbnail" style="width:100%;height:200px;" data-trigger="fileinput"></div>' +
          '<div>' +
            '<span class="btn btn-default btn-sm btn-file">' +
            '<span class="fileinput-new"><i class="glyphicon glyphicon-folder-open"></i>&nbsp; Выбрать</span>' +
            '<span class="fileinput-exists"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;</span>' +
              '<input type="file" name="images[]" accept="image/*">' +
            '</span>' +
            '<a href="#" class="btn btn-default btn-sm fileinput-exists" data-dismiss="fileinput"><i class="glyphicon glyphicon-trash"></i> Удалить</a>' +
          '</div>' +
        '</div>';

      $('#gallery').append(fileinput);
    }
  </script>
@endsection
