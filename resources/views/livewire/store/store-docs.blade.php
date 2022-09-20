<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Карточка учета</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
        <input wire:model="search" type="search" class="form-control" placeholder="Поиск по контрагенту" aria-label="Search">
      </form>

      <form class="col-5 col-lg-auto mb-2 mb-lg-0 ms-lg-auto">
        <div class="input-group">
          <span class="input-group-text">От</span>
          <input type="date" wire:model="startDate" class="form-control" placeholder="От..." data-date-format="dd/mm/yyyy">
          <span class="input-group-text">До</span>
          <input type="date" wire:model="endDate" class="form-control" placeholder="До..." data-date-format="dd/mm/yyyy">
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
            <th scope="col">Склад</th>
            <th scope="col">Автор</th>
            <th scope="col">Контрагент</th>
            <th scope="col">Сумма прихода</th>
            <th scope="col">Сумма расхода</th>
            <th scope="col">Количество позиции</th>
            <th scope="col">Дата и время</th>
            <th class="text-end" scope="col">Детали</th>
          </tr>
        </thead>
        <tbody>
          @forelse($storeDocs as $index => $storeDoc)
            <tr>
              <td>{{ $storeDoc->doc->doc_no }}</td>
              <td>{{ $storeDoc->store->id }}</td>
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
              <td>{{ $storeDoc->created_at }}</td>
              <td class="text-end"><button wire:click="docDetail({{ $storeDoc->id }})" class="btn btn-outline-primary btn-sm">Посмотреть</button></td>
            </tr>
          @empty
            <tr>
              <td colspan="9">No docs</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $storeDocs->links() }}
  </div>

  <!-- Modal -->
  <div class="modal fade" id="docDetails" tabindex="-1" aria-labelledby="docDetail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="docDetail">Детали документа</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
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
              @if($docDetail)
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
                      <td>{{ $docDetail->sum }}</td>
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
              @endif
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
                      <td><a href="/{{ $lang }}/storage/edit-product/{{ $index }}">{{ $product->title }}</a></td>
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
                      <td colspan="9">No products</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="col d-grid" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-primary"><i class="bi bi-pencil-square me-2"></i> Редактировать</button>
          </div>
          <div class="col d-grid" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-dark"><i class="be bi-printer-fill me-2"></i> Печать</button>
          </div>
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
