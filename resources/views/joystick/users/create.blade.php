@extends('joystick.layout')

@section('content')
  <h2 class="page-header">Редактирование</h2>

  @include('components.alerts')

  <p class="text-right">
    <a href="/{{ $lang }}/admin/users" class="btn btn-primary"><i class="material-icons md-18">arrow_back</i></a>
  </p>
  <form action="{{ route('users.update', [$lang, $user->id]) }}" method="post" enctype="multipart/form-data">
    <input name="_method" type="hidden" value="PUT">
    {!! csrf_field() !!}

    <div class="row">
      <div class="col-md-7">
        <div class="panel panel-default">
          <div class="panel-heading">Основная информация</div>
          <div class="panel-body">
            <div class="row">
              <div class="col-6 col-md-6">
                <div class="form-group">
                  <label>Имя</label>
                  <input type="text" class="form-control" minlength="2" maxlength="40" name="name" placeholder="Имя*" value="{{ (old('name')) ? old('name') : $user->name }}" required>
                </div>
              </div>
              <div class="col-6 col-md-6">
                <div class="form-group">
                  <label>Отчество</label>
                  <input type="text" class="form-control" minlength="2" maxlength="60" name="lastname" placeholder="Отчество*" value="{{ (old('lastname')) ? old('lastname') : NULL }}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" class="form-control" name="email" id="email" minlength="8" maxlength="60" value="{{ $user->email }}">
            </div>
            <div class="form-group">
              <label>Номер телефона</label>
              <input type="tel" pattern="(\+?\d[- .]*){7,13}" class="form-control" name="tel" placeholder="Номер телефона*" value="{{ (old('tel')) ? old('tel') : '' }}">
            </div>
            <div class="form-group">
              <label>Адрес</label>
              <input type="text" class="form-control" name="address" maxlength="30" placeholder="Адрес" value="{{ (old('address')) ? old('address') : NULL }}">
            </div>
            <div class="form-group">
              <label>Баланс</label>
              <input type="text" class="form-control" name="balance" maxlength="30" placeholder="Баланс" value="{{ (old('balance')) ? old('balance') : 0 }}">
            </div>
            <div class="form-group">
              <label for="role_id">Роли:</label>
              <select class="form-control" name="role_id" id="role_id">
                <option value=""></option>
                @foreach($roles as $role)
                  @if ($user->roles->contains($role->id)))
                    <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
                  @else
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="is_customer">Клиент:</label>
              <label>
                <input type="checkbox" id="is_customer" name="is_customer"> Активен
              </label>
            </div>
            <div class="form-group">
              <label for="is_worker">Сотрудник:</label>
              <label>
                <input type="checkbox" id="is_worker" name="is_worker"> Активен
              </label>
            </div>
            <div class="form-group">
              <label for="status">Статус:</label>
              <label>
                <input type="checkbox" id="status" name="status" checked> Активен
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">Профиль</div>
          <div class="panel-body">
            <div class="form-group">
              <label>Регион</label>
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
              <label>Компании</label>
              <select id="company_id" name="company_id" class="form-control">
                <option value=""></option>
                <?php foreach ($companies as $company) : ?>
                  <option value="{{ $company->id }}">{{ $company->title }}</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Дата рождения</label>
              <input type="date" class="form-control" name="birthday" minlength="3" maxlength="30" placeholder="Дата рождения" value="{{ (old('birthday')) ? old('birthday') : '' }}" >
            </div>
            <div class="form-group">
              <div><label>Пол</label></div>
              @foreach(trans('data.gender') as $key => $value)
                <label>
                  <input type="radio" name="gender" value="{{ $key }}"> {{ $value }}
                </label>
              @endforeach
            </div>
            <div class="form-group">
              <label for="about">О себе</label>
              <textarea class="form-control" id="about" name="about" rows="5">{{ (old('about')) ? old('about') : '' }}</textarea>
            </div>
            <div class="form-group">
              <label for="is_debtor">Должник:</label>
              <input type="checkbox" id="is_debtor" name="is_debtor" disabled> Активен
            </div>
            <div class="form-group">
              <label>Сумма долга</label>
              <input type="number" class="form-control" name="debt_sum" maxlength="30" placeholder="Сумма долга" value="{{ (old('debt_sum')) ? old('debt_sum') : 0 }}">
            </div>
            <div class="form-group">
              <label>Бонус</label>
              <input type="number" class="form-control" name="bonus" maxlength="30" placeholder="Бонус" value="{{ (old('bonus')) ? old('bonus') : 0 }}">
            </div>
            <div class="form-group">
              <label>Скидка</label>
              <input type="number" class="form-control" name="discount" minlength="0" maxlength="10" placeholder="Скидка" value="{{ (old('discount')) ? old('discount') : 0 }}">
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
