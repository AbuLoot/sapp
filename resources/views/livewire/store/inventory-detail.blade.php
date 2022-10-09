<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Ревизия {{ $revision->doc_no }}</h4>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="/{{ $lang }}/storage/inventory-history" class="btn btn-primary"><i class="bi bi-clock-history me-2"></i> История ревизий</a>
      </div>
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

    <h5 class="text-end mb-3">{{ $company->stores->where('id', $storeId)->first()->title }}</h5>

    <div class="table-responsive">
      <table class="table table-sm align-middle table-striped">
        <thead>
          <tr>
            <th scope="col">Наименование<br> товара</th>
            <th scope="col">Штрихкод</th>
            <th scope="col">Категория</th>
            <th scope="col">Цена закупки</th>
            <th scope="col">Цена продажи</th>
            <th scope="col">Расчетное<br> кол-во</th>
            <th scope="col">Фактическое<br> кол-во</th>
            <th scope="col">Кол-во недостачи</th>
            <th scope="col">Кол-во излишек</th>
            <th scope="col">Сумма недостачи</th>
            <th scope="col">Сумма излишек</th>
          </tr>
        </thead>
        <tbody>
          @forelse($revisionProducts as $index => $revisionProduct)

            <tr>
              <td>{{ $revisionProduct->title }}</td>
              <td>
                <?php $barcodes = json_decode($revisionProduct->barcodes, true) ?? []; ?>
                @foreach($barcodes as $barcode)
                  {{ $barcode }}<br>
                @endforeach
              </td>
              <td>{{ $revisionProduct->category->title }}</td>
              <td>{{ $revisionProduct->purchase_price }}</td>
              <td>{{ $revisionProduct->price }}</td>
              <?php $unit = $units->where('id', $revisionProduct->unit)->first()->title ?? '?'; ?>
              <td>{{ $revisionProduct->estimatedCount . $unit }}</td>
              <td>{{ $revisionProduct->actualCount . $unit }}</td>
              <td>{{ $revisionProduct->shortageCount . $unit }}</td>
              <td>{{ $revisionProduct->surplusCount . $unit }}</td>
              <td>{{ $revisionProduct->shortageSum . $company->currency->symbol }}</td>
              <td>{{ $revisionProduct->surplusSum . $company->currency->symbol }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="12">No data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Keyboard -->
    <div wire:ignore.self class="offcanvas offcanvas-bottom shadow bg-dark" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel" style="z-index: 1065;">
      <div class="position-relative">
        <div class="position-absolute" style="top: -30px !important; right: 15px !important;">
          <button type="button" class="btn-close " data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
      </div>
      <div class="offcanvas-body small">
        <livewire:keyboard>
      </div>
    </div>

  </div>

</div>
