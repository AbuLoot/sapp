@extends('joystick.layout')

@section('content')
  <h2 class="page-header">Импорт данных</h2>

  @include('components.alerts')

  <p class="text-right">
    <a href="/{{ $lang }}/admin/products" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
  </p>

  <div class="row">
    @if(auth()->user()->roles()->firstWhere('name', 'admin'))
      <div class="col-md-4">
        <form action="/{{ $lang }}/admin/products-select-company" method="GET">
          {!! csrf_field() !!}
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="form-group">
                <label for="company_id">Компании</label>
                <select id="company_id" name="company_id" class="form-control">
                  <option value="">Выбор компании</option>
                  @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->title }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" id="select" class="btn btn-primary">Выбрать</button>
          </div>
        </form>
      </div>
    @else
      <div class="col-md-4">
        <form action="/{{ $lang }}/admin/products" method="POST" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <input type="hidden" name="company_id" value="{{ $company->id }}">
          <div class="panel panel-default">
            <div class="panel-heading">{{ $company->title }}</div>
            <div class="panel-body">
              <div class="form-group">
                <label for="store_id">Склады</label>
                <select id="store_id" name="store_id" class="form-control">
                  <option value="">Выбор складов</option>
                  @foreach($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->title }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="file">Файл</label>
                <input type="file" id="file" name="file" accept=".xlsx, .csv, .ods" required>
                <p class="help-block">Поддерживаемые форматы файлов: xlsx, csv, ods</p>
              </div>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" id="import" class="btn btn-primary">Загрузить</button>
          </div>
        </form>
      </div>
    @endif
  </div>

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

@section('scripts')
  <script src="/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
  <!-- Typeahead Initialization -->
  <script>
    // submit button click
    $("#import").click(function() {
      $('#modal-progress').modal('show');
    });
  </script>
@endsection
