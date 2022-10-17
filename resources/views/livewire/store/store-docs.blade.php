<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Карточка учета</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
        <div class="input-group">
          <input wire:model="search" type="search" class="form-control" id="search" onclick="setFocus('search')" placeholder="Поиск по контрагенту..." aria-label="Search">
          <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
        </div>
      </form>

      <form class="col-5 col-lg-auto mb-2 mb-lg-0 ms-lg-auto">
        <div class="input-group">
          <span class="input-group-text">От</span>
          <input type="date" wire:model="startDate" class="form-control" value="{{ $startDate }}" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd">
          <span class="input-group-text">До</span>
          <input type="date" wire:model="endDate" class="form-control" value="{{ $endDate }}" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd">
        </div>
      </form>
    </div>
  </div>

  <!-- Content -->
  <div class="container">
    <div class="table-responsive">
      <table class="table table-sm table-striped align-middle">
        <thead>
          <tr>
            <th scope="col">Тип документа</th>
            <th scope="col">Номер документа</th>
            <th scope="col">Склад</th>
            <th scope="col">Автор</th>
            <th scope="col">Контрагент</th>
            <th scope="col">Сумма прихода</th>
            <th scope="col">Сумма расхода</th>
            <th scope="col">Итоговая сумма</th>
            <th scope="col">Количество позиции</th>
            <th scope="col">Дата и время</th>
            <th class="text-end" scope="col">Детали</th>
          </tr>
        </thead>
        <tbody>
          @forelse($storeDocs as $index => $storeDoc)
            <tr>
              <td>{{ $storeDoc->doc->docType->title ?? null }}</td>
              <td>{{ $storeDoc->doc->doc_no }}</td>
              <td>{{ $storeDoc->store->title }}</td>
              <td>{{ $storeDoc->user->name }}</td>
              <td>
                @switch($storeDoc->contractor_type)
                  @case('App\Models\Company')
                    {{ $storeDoc->contractor->title }}
                    @break
                  @case('App\Models\User')
                    {{ $storeDoc->contractor->name.' '.$storeDoc->contractor->lastname }}
                    @break
                @endswitch
              </td>
              <td>{{ $storeDoc->incoming_amount }}</td>
              <td>{{ $storeDoc->outgoing_amount }}</td>
              <td>{{ $storeDoc->sum }}</td>
              <td>{{ $storeDoc->count }}</td>
              <td>{{ $storeDoc->created_at }}</td>
              <td class="text-end"><button wire:click="docDetail({{ $storeDoc->id }})" class="btn btn-outline-primary btn-sm">Посмотреть</button></td>
            </tr>
          @empty
            <tr>
              <td colspan="11">No docs</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $storeDocs->links() }}

    <!-- Keyboard -->
    <livewire:keyboard>

  </div>

  <!-- Modal -->
  <div class="modal fade" id="docDetails" tabindex="-1" aria-labelledby="docDetail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="docDetail">Детали документа</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @if($docDetail)
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Инфо о документе</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Список продуктов</button>
              </li>
            </ul>
            <div class="tab-content pt-2" id="myTabContent">
              <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <table class="table">
                  <tbody>
                    <tr>
                      <th scope="row">Тип документа</th>
                      <td>{{ $docDetail->doc->docType->type }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Название документа</th>
                      <td>{{ $docDetail->doc->docType->title }}</td>
                    </tr>
                    <tr>
                      <th scope="row">ID документа</th>
                      <td>{{ $docDetail->doc_id }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Тип операции</th>
                      @if($docDetail->doc->docType->slug == 'forma-inv-10')
                        <td>Инвентаризация продуктов</td>
                      @else
                        <td>{{ __('operation-codes.'.$docDetail->doc->operation_code) }}</td>
                      @endif
                    </tr>
                    <tr>
                      <th scope="row">Автор</th>
                      <td>{{ $docDetail->user->name }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Контрагент</th>
                      <td>{{ $docDetail->contractor->name ?? $docDetail->contractor->title ?? 'No name' }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Сумма прихода</th>
                      <td>{{ $docDetail->incoming_amount }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Сумма расхода</th>
                      <td>{{ $docDetail->outgoing_amount }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Количество</th>
                      <td>{{ $docDetail->count }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Дата и время</th>
                      <td>{{ $docDetail->created_at }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Комментарии</th>
                      <td>{{ $docDetail->comment }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <table class="table table-sm table-striped">
                  <thead>
                    <tr  class="align-items-start">
                      <th scope="col">Наименование товара</th>
                      <th scope="col">Штрихкод</th>
                      <th scope="col">Категория</th>
                      <th scope="col">Цена закупки</th>
                      <th scope="col">Цена продажи</th>
                      <th scope="col">Общее Кол.</th>
                      <th scope="col">Поставщик</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($docProducts as $index => $product)
                      <tr>
                        <td>{{ $product->title }}</td>
                        <td>
                          <?php $barcodes = json_decode($product->barcodes, true) ?? ['']; ?>
                          @foreach($barcodes as $barcode)
                            {{ $barcode }}<br>
                          @endforeach
                        </td>
                        <td>{{ $product->category->title }}</td>
                        <td>{{ $product->purchase_price }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->count }}</td>
                        <td>{{ $product->company->title }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7">No products</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <script>
    window.addEventListener('open-modal', event => {
      var docModal = new bootstrap.Modal(document.getElementById("docDetails"), {});
      docModal.show();
    })
  </script>
</div>

@section('scripts')
<script type="text/javascript">
  // Offcanvas
  const offcanvas = new bootstrap.Offcanvas('#offcanvas', { backdrop: false, scroll: true })

  // Offcanvas - Changing Placement
  function changePLacement(val) {

    let placement = 'offcanvas-bottom';
    let element = document.getElementById("offcanvas");

    placement = (val == 'offcanvas-bottom') ? 'offcanvas-top' : 'offcanvas-bottom';

    element.classList.add(val);
    element.classList.remove(placement);
  }

  // Keyboard Input
  let inputElId;

  // Setting Input Focus
  function setFocus(elId) {
    inputElId = elId;
    document.getElementById(elId).focus();
  }

  // Displaying values
  function display(val) {
    let input = document.getElementById(inputElId);

    input.value += val;
    @this.set(inputElId, input.value);
  }

  // Clearing the display
  function clearDisplay() {
    let inputSearch = document.getElementById(inputElId);
    inputSearch.value = inputSearch.value.substr(0, inputSearch.value.length - 1);
    @this.set(inputElId, inputSearch.value);
  }
</script>
@endsection