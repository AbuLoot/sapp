<x-app-layout>
  <x-slot name="title">
      Регистрация в системе
  </x-slot>

  <div class="row">
    <div class="col-lg-5 col-md-7 col-sm-9 mx-auto">

      <x-auth-validation-errors :errors="$errors" />

      <form method="POST" action="{{ route('register') }}" class="p-4 p-md-5 bg-light border rounded-3 bg-light">
        @csrf
        <h2 class="fw-bold mb-0">Регистрация</h2>
        <br>

        <div class="row">
          <div class="col">
            <div class="form-floating mb-3">
              <input type="text" name="name" class="form-control rounded-3" id="name" placeholder="Имя" value="{{ old('name') }}" required autofocus>
              <label for="name">Имя</label>
            </div>
          </div>
          <div class="col">
            <div class="form-floating mb-3">
              <input type="text" name="lastname" class="form-control rounded-3" id="lastname" placeholder="Фамилия" value="{{ old('lastname') }}" required>
              <label for="lastname">Фамилия</label>
            </div>
          </div>
        </div>
        <div class="form-floating mb-3">
          <input type="email" name="email" class="form-control rounded-3" id="emailAddress" placeholder="name@example.com" value="{{ old('email') }}" required>
          <label for="emailAddress">Email адрес</label>
        </div>
        <div class="form-floating mb-3">
          <input type="tel" name="tel" pattern="(\+?\d[- .]*){7,13}" class="form-control rounded-3" id="telNumber" placeholder="Номер телефона" value="{{ old('tel') }}" required>
          <label for="telNumber">Номер телефона</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" name="password" class="form-control rounded-3" id="floatingPassword" placeholder="Введите пароль" required>
          <label for="floatingPassword">Введите пароль</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" name="password_confirmation" class="form-control rounded-3" id="repeatPassword" placeholder="Повторно введите пароль" required>
          <label for="repeatPassword">Повторно введите пароль</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" name="code" class="form-control rounded-3" id="code" placeholder="Код партнера" required>
          <label for="code">Код партнера</label>
        </div>

        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Зарегистрироваться</button>
        <hr class="my-4">
        <small class="text-muted">Нажимая Зарегистрироваться, вы соглашаетесь с условиями использования.</small>
      </form>
    </div>
  </div>
</x-app-layout>