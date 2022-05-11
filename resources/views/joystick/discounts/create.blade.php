@extends('joystick.layout')

@section('content')
  <h2 class="page-header">Создание</h2>

  @include('joystick.partials.alerts')

  <p class="text-right">
    <a href="/{{ $lang }}/admin/discounts" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
  </p>
  <div class="row">
    <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-body">
          <form action="{{ route('discounts.store', $lang) }}" method="post">
            {!! csrf_field() !!}
            <div class="form-group">
              <label for="category_id">Категория</label>
              <select id="category_id" name="category_id" class="form-control" required>
                <?php $traverse = function ($nodes, $prefix = null) use (&$traverse) { ?>
                  <?php foreach ($nodes as $node) : ?>
                    <option value="{{ $node->id }}">{{ PHP_EOL.$prefix.' '.$node->title }}</option>
                    <?php $traverse($node->children, $prefix.'___'); ?>
                  <?php endforeach; ?>
                <?php }; ?>
                <?php $traverse($categories); ?>
              </select>
            </div>
            <div class="form-group">
              <label for="percent">Процент</label>
              <input type="text" class="form-control" id="percent" name="percent" value="{{ (old('percent')) ? old('percent') : NULL }}">
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="start_date">Начало срока</label>
                  <input type="date" class="form-control" id="start_date" name="start_date" value="{{ (old('start_date')) ? old('start_date') : '' }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="end_date">Конец срока</label>
                  <input type="date" class="form-control" id="end_date" name="end_date" value="{{ (old('end_date')) ? old('end_date') : '' }}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="sum">Сумма</label>
              <input type="text" class="form-control" id="sum" name="sum" value="{{ (old('sum')) ? old('sum') : '' }}">
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
