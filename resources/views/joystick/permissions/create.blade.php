@extends('joystick.layout')

@section('content')
  <h2 class="page-header">Добавление</h2>

  @include('joystick.partials.alerts')

  <p class="text-right">
    <a href="/{{ $lang }}/admin/permissions" class="btn btn-primary btn-sm">Назад</a>
  </p>
  <div class="panel panel-default">
    <div class="panel-body">
      <form action="{{ route('permissions.store', $lang) }}" method="post">
        {!! csrf_field() !!}
        <div class="form-group">
          <label for="name">Название</label>
          <input type="text" class="form-control" id="name" name="name" maxlength="80" value="{{ (old('name')) ? old('name') : '' }}" required>
        </div>
        <div class="form-group">
          <label for="display_name">Метка</label>
          <input type="text" class="form-control" id="display_name" name="display_name" maxlength="80" value="{{ (old('display_name')) ? old('display_name') : '' }}">
        </div>
        <div class="form-group">
          <label for="description">Описание</label>
          <input type="text" class="form-control" id="description" name="description" maxlength="80" value="{{ (old('description')) ? old('description') : '' }}">
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Создать</button>
        </div>
      </form>
    </div>
  </div>
@endsection
