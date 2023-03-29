<x-app-layout>
  <x-slot name="title">
      Регистрация компании
  </x-slot>

  <div class="row">
    <div class="col-lg-5 col-md-7 col-sm-9 mx-auto">

      <x-auth-validation-errors :errors="$errors" />

      <form method="POST" action="{{ route('register-company') }}" class="p-4 p-md-5 bg-light border rounded-3 bg-light">
        @csrf
        <h2 class="fw-bold mb-0">Регистрация</h2>
        <br>

        <div class="form-floating mb-3">
          <input type="title" name="title" class="form-control rounded-3" id="title" placeholder="Название компании" value="{{ old('title') }}" required>
          <label for="title">Название компании</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" name="bin" class="form-control rounded-3" id="bin" placeholder="БИН / ИИН" value="{{ old('bin') }}" required>
          <label for="bin">БИН / ИИН</label>
        </div>
        <div class="form-floating mb-3">
          <select id="region_id" name="region_id" class="form-control rounded-3" placeholder="Регионы" required>
            <option value="0">Выберите регион</option>
            <?php $traverse = function ($nodes, $prefix = null) use (&$traverse) { ?>
              <?php foreach ($nodes as $node) : ?>
                <option value="{{ $node->id }}">{{ PHP_EOL.$prefix.' '.$node->title }}</option>
                <?php $traverse($node->children, $prefix.'___'); ?>
              <?php endforeach; ?>
            <?php }; ?>
            <?php $traverse($regions); ?>
          </select>
          <label for="region_id">Регионы</label>
        </div>
        <div class="form-floating mb-3">
          <select id="currency_id" name="currency_id" class="form-control rounded-3" placeholder="Валюты" required>
            <?php foreach ($currencies as $currency) : ?>
              <option value="{{ $currency->id }}" <?php if ($currency->code == 'KZT') echo 'selected'; ?>>{{ $currency->symbol }} - {{ $currency->currency }}</option>
            <?php endforeach; ?>
          </select>
          <label for="currency_id">Валюты</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" name="legal_address" class="form-control rounded-3" id="legal_address" placeholder="Юридический адрес" value="{{ old('legal_address') }}" required>
          <label for="legal_address">Юридический адрес</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" name="actual_address" class="form-control rounded-3" id="actual_address" placeholder="Фактический адрес" value="{{ old('actual_address') }}">
          <label for="actual_address">Фактический адрес</label>
        </div>
        <div class="form-floating mb-3">
          <input type="tel" name="phones" pattern="(\+?\d[- .]*){7,13}" class="form-control rounded-3" id="telNumber" placeholder="Номер телефона" value="{{ old('phones') }}" required>
          <label for="telNumber">Номер телефона компании</label>
        </div>
        <div class="form-floating mb-3">
          <input type="email" name="email" class="form-control rounded-3" id="emailCompany" placeholder="Введите email" required>
          <label for="emailCompany">Email компании</label>
        </div>
        <div class="form-floating mb-3">
          <input type="links" name="links" class="form-control rounded-3" id="links" placeholder="Введите адрес сайта">
          <label for="links">Website</label>
        </div>
        <div class="form-floating mb-3">
          <textarea name="about" class="form-control rounded-3" id="about" style="height: 100px" placeholder="О компании" required></textarea>
          <label for="about">О компании</label>
        </div>

        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Зарегистрироваться</button>
        <hr class="my-4">
        <small class="text-muted">Нажимая Зарегистрироваться, вы соглашаетесь с условиями использования.</small>
      </form>
    </div>
  </div>
</x-app-layout>