<x-app-layout>
  <x-slot name="title">
      Список компании
  </x-slot>

  <div class="row">
    <div class="col-lg-5 col-md-7 col-sm-9 mx-auto">

      <x-auth-validation-errors :errors="$errors" />

      <form method="POST" action="{{ route('select-company') }}" class="p-4 p-md-5 bg-light border rounded-3 bg-light">
        @csrf
        <h2 class="fw-bold mb-0">Выбор компании</h2>
        <br>

        <div class="form-floating mb-3">
          <select id="company_id" name="company_id" class="form-control rounded-3" placeholder="Компании" required>
            <option value="">Выберите компанию</option>
            <?php foreach ($companies as $company) : ?>
              <option value="{{ $company->id }}">{{ $company->title.' №: '.$company->bin }}</option>
            <?php endforeach; ?>
          </select>
          <label for="company_id">Компании</label>
        </div>
        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Выбрать</button>
      </form>
    </div>
  </div>
</x-app-layout>