<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Накладные</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
        <input wire:model="search" type="search" class="form-control" placeholder="Поиск..." aria-label="Search">
      </form>

      <form class="col-5 col-lg-auto mb-2 mb-lg-0 ms-lg-auto">
        <div class="input-group">
          <span class="input-group-text">От</span>
          <input type="date" wire:model="startDate" class="form-control" placeholder="От..." data-date-format="yyyy/mm/dd">
          <span class="input-group-text">До</span>
          <input type="date" wire:model="endDate" class="form-control" placeholder="До..." data-date-format="yyyy/mm/dd">
        </div>
      </form>
    </div>
  </div>

  <!-- Content -->
  <div class="container">
    <div class="row align-items-center">
      <div class="col-6">
        <ul class="nav nav-pills gap-2 small mb-3">
          <li class="nav-item">
            <a class="nav-link" href="/{{ $lang }}/storage/docs">Приходные</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Расходные</a>
          </li>
        </ul>
      </div>
      <div class="col-6 text-end">
        <h6>Обшая сумма накладных: {{ number_format($outgoingDocs->sum('sum'), 0, '.', ',') . $company->currency->code }}</h6>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-sm table-striped align-middle">
        <thead>
          <tr>
            <th scope="col">Номер накладной</th>
            <th scope="col">Сумма</th>
            <th scope="col">Количество</th>
            <th scope="col">Автор</th>
            <th scope="col">Дата и время</th>
            <th class="text-end" scope="col">Функции</th>
          </tr>
        </thead>        <tbody>
          @forelse($outgoingDocs as $index => $outgoingDoc)
            <tr>
              <td><a href="/{{ $lang }}/storage/edit-outgoingDoc/{{ $outgoingDoc->id }}">{{ $outgoingDoc->doc_no }}</a></td>
              <td>{{ $outgoingDoc->sum }}</td>
              <td>{{ $outgoingDoc->count }}</td>
              <td>{{ $outgoingDoc->user->name }}</td>
              <td>{{ $outgoingDoc->created_at }}</td>
              <td class="text-end"><button wire:click="docDetail({{ $outgoingDoc->id }})" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#docDetails">Посмотреть</button></td>
            </tr>
          @empty
            <tr>
              <td colspan="6">No docs</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $outgoingDocs->links() }}
  </div>

  <!-- Modal -->
  <div class="modal fade" id="docDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Детали накладной</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Инфо о накладной</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Список продуктов</button>
            </li>
          </ul>
          <div class="tab-content pt-2" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
              @if($docDetail)
                <?php $docType = \App\Models\DocType::where('id', $docDetail->doc_type_id)->first(); ?>
                <?php $products_data = json_decode($docDetail->products_data, true) ?? []; ?>
                <table class="table">
                  <tbody>
                    <tr>
                      <th scope="row">Тип документа</th>
                      <td>{{ $docType->title }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Номер накладной</th>
                      <td>{{ $docDetail->doc_no }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Сумма</th>
                      <td>{{ $docDetail->sum }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Количество</th>
                      <td>{{ $docDetail->count }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Автор</th>
                      <td>{{ $docDetail->user->name }}</td>
                    </tr>
                    <tr>
                      <th scope="row">Дата и время</th>
                      <td>{{ $docDetail->created_at }}</td>
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
                    <th scope="col">Кол. расхода</th>
                    <th scope="col">Общее Кол.</th>
                    <!-- <th scope="col">Ед. измерения</th> -->
                    <th scope="col">Поставщик</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($docProducts as $index => $product)
                    <tr>
                      <td><a href="/{{ $lang }}/storage/edit-product/{{ $product->id }}">{{ $product->title }}</a></td>
                      <td>
                        <?php $barcodes = json_decode($product->barcodes, true) ?? ['']; ?>
                        @foreach($barcodes as $barcode)
                          {{ $barcode }}<br>
                        @endforeach
                      </td>
                      <td>{{ $product->category->title }}</td>
                      <td>{{ $product->purchase_price }}</td>
                      <td>{{ $product->price }}</td>
                      <td>{{ $products_data[$product->id]['outgoingCount'] }}</td>
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
</div>
