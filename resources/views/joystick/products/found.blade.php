@extends('joystick.layout')

@section('content')
  <h2 class="page-header">Продукты</h2>

  <h3>Поиск по запросу <b>"{{ $text }}"</b></h3>

  @include('joystick.partials.alerts')

  <div class="row">
    <div class="col-md-5">
      <form action="/{{ $lang }}/admin/products-search" method="get">
        <div class="input-group input-search">
          <input type="search" class="form-control input-xs typeahead-goods" name="text" placeholder="Поиск...">

          <div class="input-group-btn">
            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Категории <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right dropdown-menu-category">
              <li><a href="/{{ $lang }}/admin/products"><b>Все продукты</b></a></li>
              <?php $traverse = function ($nodes, $prefix = null) use (&$traverse, $lang) { ?>
                <?php foreach ($nodes as $node) : ?>
                  <li><a href="/{{ $lang }}/admin/products-category/{{ $node->id }}">{{ PHP_EOL.$prefix.' '.$node->title }}</a></li>
                  <?php $traverse($node->children, $prefix.'___'); ?>
                <?php endforeach; ?>
              <?php }; ?>
              <?php $traverse($categories); ?>
            </ul>
          </div>
        </div>
      </form><br>
    </div>

    <div class="col-md-7 text-right">
      <a href="/{{ $lang }}/admin/joytable" class="btn btn-default text-uppercase"><i class="material-icons md-18">table_rows</i> Joytable</a>
      <a href="/{{ $lang }}/admin/products-export" class="btn btn-default"><i class="material-icons md-18">import_export</i> Экспорт</a>
      <a href="/{{ $lang }}/admin/products-import" class="btn btn-default"><i class="material-icons md-18">import_export</i> Импорт</a>
      <a href="/{{ $lang }}/admin/products-price/edit" class="btn btn-default"><i class="material-icons md-18">calculate</i> Цены</a>
      <div class="btn-group">
        <button type="button" id="submit" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Функции <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" id="actions">
          @foreach(trans('statuses.product') as $num => $status)
            <li><a data-action="{{ $num }}" href="#">Статус {{ $status['title'] }}</a></li>
          @endforeach
          <li role="separator" class="divider"></li>
          @foreach($modes as $mode)
            <?php $titles = unserialize($mode->title); ?>
            <li><a data-action="{{ $mode->slug }}" href="#">Режим {{ $titles[$lang]['title'] }}</a></li>
          @endforeach
          <li role="separator" class="divider"></li>
          <li><a data-action="destroy" href="#" onclick="return confirm('Удалить записи?')">Удалить</a></li>
        </ul>
      </div>
      <a href="/{{ $lang }}/admin/products/create" class="btn btn-success"><i class="material-icons md-18">add</i></a>
    </div><br>
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-condensed table-hover">
      <thead>
        <tr class="active">
          <td><input type="checkbox" onclick="toggleCheckbox(this)" class="checkbox-ids"></td>
          <td>Картинка</td>
          <td>Название</td>
          <td>Категории</td>
          <td>Компании</td>
          <td>Проекты</td>
          <td>Товар</td>
          <td><i class="material-icons md-18">face</i></td>
          <td>Язык</td>
          <td>Режим</td>
          <td>Статус</td>
          <td class="text-right">Функции</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($products as $product)
          <tr>
            <td><input type="checkbox" name="products_id[]" value="{{ $product->id }}" class="checkbox-ids"></td>
            <td><img src="/img/products/{{ $product->path.'/'.$product->image }}" class="img-responsive" style="width:80px;height:auto;"></td>
            <td class="cell-size">{{ $product->title }}</td>
            <td class="text-nowrap">{{ $product->category->title }}</td>
            <td>{{ ($product->company) ? $product->company->title : '' }}</td>
            <td>{{ ($product->project) ? $product->project->title : '' }}</td>
            <td>{{ ($product->type == 1) ? 'Новый' : 'Б/у' }}</td>
            <td>{{ $product->views }}</td>
            <td>{{ $product->lang }}</td>
            <td class="text-nowrap">
              @foreach ($product->modes as $mode)
                <?php $mode = unserialize($mode->title); ?>
                {{ $mode[$lang]['title'] }}<br>
              @endforeach
            </td>
            <td class="text-{{ trans('statuses.product.'.$product->status.'.style') }}">{{ trans('statuses.product.'.$product->status.'.title') }}</td>
            <td class="text-right text-nowrap">
              <!-- <a class="btn btn-link btn-xs" href="/p/{{ $product->slug }}" title="Просмотр товара" target="_blank"><i class="material-icons md-18">link</i></a> -->
              <a class="btn btn-link btn-xs" href="{{ route('products.edit', [$lang, $product->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form class="btn-delete" method="POST" action="{{ route('products.destroy', [$lang, $product->id]) }}" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-link btn-xs" onclick="return confirm('Удалить запись?')"><i class="material-icons md-18">clear</i></button>
              </form>
            </td>
            <th class="fix-col">
              <a class="btn btn-link btn-xs btn-fix-col" href="{{ route('products.edit', [$lang, $product->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form class="btn-delete btn-fix-col" method="POST" action="{{ route('products.destroy', [$lang, $product->id]) }}" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-link btn-xs" onclick="return confirm('Удалить запись?')"><i class="material-icons md-18">clear</i></button>
              </form>
            </th>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{ $products->links() }}

  <!-- Modal Progress Bar -->
  <div class="modal fade" id="modal-progress" tabindex="-1" role="dialog" aria-labelledby="modalProgress">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content-">
        <br>
        <div class="progress">
          <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            <span class="sr-only">100% Complete</span>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('head')
  <link href="/bower_components/typeahead.js/dist/typeahead.bootstrap.css" rel="stylesheet">
@endsection

@section('scripts')
  <script src="/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
  <!-- Typeahead Initialization -->
  <script>
    jQuery(document).ready(function($) {
      // Set the Options for "Bloodhound" suggestion engine
      var engine = new Bloodhound({
        remote: {
          url: '/search-ajax-admin?text=%QUERY%',
          wildcard: '%QUERY%'
        },
        datumTokenizer: Bloodhound.tokenizers.whitespace('text'),
        queryTokenizer: Bloodhound.tokenizers.whitespace
      });

      $(".typeahead-goods").typeahead({
        hint: true,
        highlight: true,
        minLength: 2
      }, {
        limit: 10,
        source: engine.ttAdapter(),
        displayKey: 'title',

        templates: {
          empty: [
            '<li>&nbsp;&nbsp;&nbsp;Ничего не найдено.</li>'
          ],
          suggestion: function (data) {
            let image = (data.path == null) ? 'no-image-middle.png' : data.path + '/' + data.image;
            return '<li><a href="/{{ $lang }}/admin/products/' + data.id + '/edit"><img class="list-img" src="/img/products/' + image + '"> ' + data.title + '<br><span>Код: ' + data.barcode + '</span></a></li>'
          }
        }
      });
    });

    // submit button click
    $("#actions > li > a").click(function() {

      var action = $(this).data("action");
      var productsId = new Array();

      $('input[name="products_id[]"]:checked').each(function() {
        productsId.push($(this).val());
      });

      if (action == 'destroy') {
        $('#modal-progress').modal('show');
      }

      if (productsId.length > 0) {
        $.ajax({
          type: "get",
          url: '/{{ $lang }}/admin/products-actions',
          dataType: "json",
          data: {
            "action": action,
            "products_id": productsId
          },
          success: function(data) {
            console.log(data);
            location.reload();
            $('#modal-progress').modal('toggle');
          }
        });
      }
    });

    function toggleCheckbox(source) {
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
          checkboxes[i].checked = source.checked;
      }
    }
  </script>
@endsection
