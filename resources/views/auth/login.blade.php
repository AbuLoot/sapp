<x-app-layout>
  <x-slot name="title">
      Вход в систему
  </x-slot>

  <div class="col-lg-5 col-md-7 col-sm-9 mx-auto">

    <x-auth-validation-errors :errors="$errors" />

    <form method="POST" action="{{ route('login') }}" class="p-4 p-md-5 bg-light border rounded-3 bg-light">
      @csrf

      <h2 class="fw-bold mb-0">Войти</h2>
      <br>

      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control rounded-3" id="emailAddress" value="{{ old('email') }}" placeholder="name@example.com">
        <label for="emailAddress">Email адрес</label>
      </div>
      <!-- <div class="form-floating mb-3">
        <input type="tel" class="form-control rounded-3" id="telNumber" placeholder="Номер телефона">
        <label for="telNumber">Номер телефона</label>
      </div> -->
      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control rounded-3" id="floatingPassword" placeholder="Введите пароль">
        <label for="floatingPassword">Введите пароль</label>
      </div>

      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" name="remember" value="remember-me"> Запомнить меня
        </label>
      </div>
      <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Войти</button>
    </form>
  </div>
</x-app-layout>