@extends('pos.layout')

@section('content')
  <h2 class="page-header">Редактирование</h2>

  @include('components.alerts')

  <p class="text-right">
    <a href="/{{ $lang }}/pos/workplaces" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
  </p>
  <div class="row">
    <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-body">
          <form action="{{ route('workplaces.update', [$lang, $workplace->id]) }}" method="post">
            <input type="hidden" name="_method" value="PUT">
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

            <?php $workplace_type = explode('App\\Models\\', $workplace->workplace_type); ?>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="workplace_id">Склады</label><br>
                  <div style="display: inline-block; margin-right: 15px;">
                    @foreach($stores as $store)
                      <label><input type="radio" name="workplace_id" value="store-{{ $store->id }}" @if($workplace->workplace_id == $store->id && $workplace_type[1] == 'Store') checked @endif> {{ $store->title }}</label><br>
                    @endforeach<br>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="workplace_id">Кассы</label><br>
                  <div style="display: inline-block; margin-right: 15px;">
                    @foreach($cashbooks as $cashbook)
                      <label><input type="radio" name="workplace_id" value="cashbook-{{ $cashbook->id }}" @if($workplace->workplace_id == $cashbook->id && $workplace_type[1] == 'Cashbook') checked @endif> {{ $cashbook->title }}</label><br>
                    @endforeach<br>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="code">Код</label>
              <input type="password" class="form-control" id="code" name="code" minlength="4" maxlength="4" value="{{ (old('code')) ? old('code') : $workplace->code }}" required>
            </div>
            <div class="form-group">
              <label for="comment">Примечание</label>
              <textarea class="form-control" id="comment" name="comment" rows="5">{{ (old('comment')) ? old('comment') : $workplace->comment }}</textarea>
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
