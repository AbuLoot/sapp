@extends('pos.layout')

@section('content')
  <h2 class="page-header">Создание</h2>

  @include('components.alerts')

  <p class="text-right">
    <a href="/{{ $lang }}/pos/cashbooks" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
  </p>
  <div class="row">
    <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-body">
          <form action="{{ route('cashbooks.store', $lang) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
              <label for="title">Название</label>
              <input type="text" class="form-control" id="title" name="title" minlength="2" maxlength="80" value="{{ (old('title')) ? old('title') : '' }}" required>
            </div>
            <div class="form-group">
              <label for="slug">Slug</label>
              <input type="text" class="form-control" id="slug" name="slug" minlength="2" maxlength="80" value="{{ (old('slug')) ? old('slug') : '' }}">
            </div>
            <div class="form-group">
              <label for="ip_address">IP address</label>
              <input type="text" class="form-control" id="ip_address" name="ip_address" value="{{ (old('ip_address')) ? old('ip_address') : NULL }}">
            </div>
            <div class="form-group">
              <label for="address">Адрес</label>
              <input type="text" class="form-control" id="address" name="address" value="{{ (old('address')) ? old('address') : NULL }}">
            </div>
            <div class="form-group">
              <label for="company_id">Компания</label>
              <input type="text" class="form-control" id="company_id" name="company_id" value="{{ (old('company_id')) ? old('company_id') : Auth::user()->profile->company->title }}" disabled>
            </div>
            <div class="form-group">
              <label for="region_id">Регионы</label>
              <select id="region_id" name="region_id" class="form-control">
                <option value=""></option>
                <?php $traverse = function ($nodes, $prefix = null) use (&$traverse) { ?>
                  <?php foreach ($nodes as $node) : ?>
                    <option value="{{ $node->id }}">{{ PHP_EOL.$prefix.' '.$node->title }}</option>
                    <?php $traverse($node->children, $prefix.'___'); ?>
                  <?php endforeach; ?>
                <?php }; ?>
                <?php $traverse($regions); ?>
              </select>
            </div>
            <div class="form-group">
              <label for="description">Описание</label>
              <textarea class="form-control" id="description" name="description" rows="5">{{ (old('description')) ? old('description') : '' }}</textarea>
            </div>
            <div class="form-group">
              <label for="status">Статус:</label>
              <label>
                <input type="checkbox" id="status" name="status" checked> Активен
              </label>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success"><i class="material-icons">save</i></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('head')
  <link href="/joystick/css/jasny-bootstrap.min.css" rel="stylesheet">
@endsection

@section('scripts')
  <script src="/joystick/js/jasny-bootstrap.js"></script>
@endsection
