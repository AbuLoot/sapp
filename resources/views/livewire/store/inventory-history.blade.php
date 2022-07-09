<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">История ревизий</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
        <input wire:model="search" type="search" class="form-control" placeholder="Поиск..." aria-label="Search">
      </form>

    </div>
  </div>

  <!-- Content -->
  <div class="container">

    @if(session()->has('message'))
      <div class="toast-container position-fixed bottom-0 end-0 p-4">
        <div class="toast align-items-center text-bg-info border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body text-white">{{ session('message') }}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
    @endif

    <div class="text-end">
      @foreach($company->stores as $index => $store)
        <div class="form-check form-check-inline">
          <input class="form-check-input" wire:model="store_id" type="radio" name="inlineRadioOptions" id="store{{ $store->id }}" value="{{ $store->id }}" @if($index == 0) checked @endif>
          <label class="form-check-label" for="store{{ $store->id }}">{{ $store->title }}</label>
        </div>
      @endforeach
    </div>

    <div class="table-responsive">
      <table class="table align-middle table-sm table-striped">
        <thead>
          <tr>
            <th scope="col">Номер ревизии</th>
            <th scope="col">Количество позиции</th>
            <th scope="col">Автор</th>
            <th scope="col">Дата и время</th>
            <th scope="col">Количество недостачи</th>
            <th scope="col">Количество излишки</th>
            <th scope="col">Сумма недостачи</th>
            <th class="text-end" scope="col">Функции</th>
          </tr>
        </thead>
        <tbody>
          @forelse($revisions as $index => $revision)
            <tr>
              <td>{{ $revision->doc_no }}</td>
              <?php
                $products_data = json_decode($revision->products_data, true) ?? [];
                $products_count = count($products_data);
              ?>
              <td>{{ $products_count }}</td>
              <td>{{ $revision->user->name }}</td>
              <td>{{ $revision->created_at }}</td>
              <td>{{ $revision->surplus_count }}</td>
              <td>{{ $revision->shortage_count }}</td>
              <td>{{ $revision->shortage_sum }}</td>
              <td class="text-end"><a href="/{{ $lang }}/storage/inventory-detail/{{ $revision->id }}" class="btn btn-outline-primary btn-sm">Посмотреть</a></td>
            </tr>
          @empty
            <tr>
              <td colspan="9">No data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="row mb-3">
      <div class="col-5">
        <div class="mb-3">
          <label for="storages" class="form-label">Склады</label>
          <select class="form-select" aria-label="Default select example">
            <option selected>Выберите склад</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="barcode-count" class="form-label">Example textarea</label>
          <textarea class="form-control" id="barcode-count" rows="3" placeholder="Штрихкод и количество"></textarea>
          <p>@Введите штрихкод товара и количество товара через пробел</p>
        </div>

        <button type="submit" class="btn btn-primary">Проверить</button>
      </div>
    </div>

  </div>
</div>