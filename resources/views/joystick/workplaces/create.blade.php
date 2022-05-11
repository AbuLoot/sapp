@extends('joystick.layout')

@section('content')
  <h2 class="page-header">Создание</h2>

  @include('joystick.partials.alerts')

  <p class="text-right">
    <a href="/{{ $lang }}/admin/workplaces" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
  </p>
  <div class="row">
    <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-body">
          <form action="{{ route('workplaces.store', $lang) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
              <label for="company_id">Компания</label>
              <input type="text" class="form-control" id="company_id" name="company_id" value="{{ (old('company_id')) ? old('company_id') : Auth::user()->profile->company->title }}" disabled>
            </div>

            <div class="form-group">
              <label for="user_id">Пользователи</label>
              <select id="user_id" name="user_id" class="form-control">
                @foreach($users as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="workplace_id">Склады</label><br>
                  <div style="display: inline-block; margin-right: 15px;">
                    @foreach($storages as $storage)
                      <label><input type="radio" name="workplace_id" value="{{ $storage->id }}"> {{ $storage->title }}</label><br>
                    @endforeach<br>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="workplace_id">Кассы</label><br>
                  <div style="display: inline-block; margin-right: 15px;">
                    @foreach($cashbooks as $cashbook)
                      <label><input type="radio" name="workplace_id" value="{{ $cashbook->id }}"> {{ $cashbook->title }}</label><br>
                    @endforeach<br>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="code">Код</label>
              <input type="text" class="form-control" id="code" name="code" value="{{ (old('code')) ? old('code') : NULL }}">
            </div>
            <div class="form-group">
              <label for="comment">Примечание</label>
              <textarea class="form-control" id="comment" name="comment" rows="5">{{ (old('comment')) ? old('comment') : '' }}</textarea>
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
