@extends('joystick.layout')

@section('content')
  <h2 class="page-header">Пользователи</h2>

  @include('components.alerts')

  <div class="table-responsive">
    <table class="table table-striped table-condensed">
      <thead>
        <tr class="active">
          <td>№</td>
          <td>Имя</td>
          <td>Email</td>
          <td>Номер телефона</td>
          <td>Регион</td>
          <td>Клиент</td>
          <td>Сотрудник</td>
          <td>Роль</td>
          <td class="text-right">Функции</td>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        @foreach($users as $user)
          <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ($user->profile) ? $user->profile->tel : '' }}</td>
            <td><?php if ($user->profile) echo $regions->firstWhere('id', $user->profile->region_id)->title; ?></td>
            <td class="text-info">{{ trans('statuses.data.'.$user->is_customer.'.title') }}</td>
            <td class="text-info">{{ trans('statuses.data.'.$user->is_worker.'.title') }}</td>
            <td>
              @foreach($user->roles as $role)
                {{ $role->name }}<br>
              @endforeach
            </td>
            <td class="text-right text-nowrap">
              <a class="btn btn-link btn-xs" href="{{ route('users.edit', [$lang, $user->id]) }}" title="Редактировать"><i class="material-icons md-18">mode_edit</i></a>
              <form method="POST" action="{{ route('users.destroy', [$lang, $user->id]) }}" accept-charset="UTF-8" class="btn-delete">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-link btn-xs" onclick="return confirm('Удалить запись?')"><i class="material-icons md-18">clear</i></button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{ $users->links() }}

@endsection
