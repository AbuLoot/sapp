@extends('pos.layout')

@section('content')
  <h2 class="page-header">Редактирование</h2>

  @include('components.alerts')

  <p class="text-right">
    <a href="/{{ $lang }}/pos/bank_accounts" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
  </p>
  <div class="row">
    <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-body">
          <form action="{{ route('bank_accounts.update', [$lang, $bank_account->id]) }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
            {!! csrf_field() !!}

            <div class="form-group">
              <label for="company_id">Компания</label>
              <input type="text" class="form-control" id="company_id" name="company_id" value="{{ (old('company_id')) ? old('company_id') : Auth::user()->company->title }}" disabled>
            </div>
            <div class="form-group">
              <label for="title">Название</label>
              <input type="text" class="form-control" id="title" name="title" minlength="2" maxlength="80" value="{{ (old('title')) ? old('title') : $bank_account->title }}" required>
            </div>
            <div class="form-group">
              <label for="slug">Slug</label>
              <input type="text" class="form-control" id="slug" name="slug" minlength="2" maxlength="80" value="{{ (old('slug')) ? old('slug') : $bank_account->slug }}">
            </div>
            <div class="form-group">
              <label for="account_number">Номер счета</label>
              <div class="input-group">
                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ (old('account_number')) ? old('account_number') : $bank_account->account_number }}">
                <span class="input-group-btn" style="min-width: 90px;">
                  <select class="form-control" name="currency">
                    @foreach($currencies as $currency)
                      @if($bank_account->currency == $currency->code)
                        <option value="{{ $currency->code }}" selected>{{ $currency->code }}</option>
                      @else
                        <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                      @endif
                    @endforeach
                  </select>
                </span>
              </div>
            </div>
            <div class="form-group">
              <label for="bic">БИК</label>
              <input type="text" class="form-control" id="bic" name="bic" value="{{ (old('bic')) ? old('bic') : $bank_account->bic }}">
            </div>
            <div class="form-group">
              <label>Баланс</label>
              <input type="text" class="form-control" name="balance" maxlength="30" placeholder="Баланс" value="{{ (old('balance')) ? old('balance') : $bank_account->balance }}">
            </div>
            <div class="form-group">
              <label for="comment">Примечание</label>
              <textarea class="form-control" id="comment" name="comment" rows="5">{{ (old('comment')) ? old('comment') : $bank_account->comment }}</textarea>
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
