@extends('store.layout')

@section('nav-functions')    
  <div class="container d-flex flex-wrap justify-content-between align-items-center">

    <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Накладные</h4>

    <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
      <input type="search" class="form-control" placeholder="Поиск..." aria-label="Search">
    </form>

    <form class="col-12 col-lg-auto mb-2 mb-lg-0 ms-lg-auto">
      <div class="input-group">
        <span class="input-group-text">От</span>
        <input type="date" class="form-control" placeholder="От..." data-date-format="dd/mm/yyyy">
        <span class="input-group-text">До</span>
        <input type="date" class="form-control" placeholder="До...">
      </div>
    </form>
  </div>
@endsection

@section('content')
  <div class="row align-items-center">
    <div class="col-6">
      <ul class="nav nav-pills gap-2 small mb-3">
        <li class="nav-item">
          <a class="nav-link" href="/{{ $lang }}/store/docs">Приходные</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Расходные</a>
        </li>
      </ul>
    </div>
    <div class="col-6 text-end">
      <h6>Обшая сумма накладных: 1000000KZT</h6>
    </div>
  </div>

  <table class="table align-middle table-sm table-striped">
    <thead>
      <tr>
        <th scope="col">Номер накладной</th>
        <th scope="col">Сумма</th>
        <th scope="col">Автор</th>
        <th scope="col">Дата</th>
        <th scope="col">Время</th>
        <th class="text-end" scope="col">Функции</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td class="text-end"><button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#docDetails">Посмотреть</button></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td class="text-end"><button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#docDetails">Посмотреть</button></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td class="text-end"><button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#docDetails">Посмотреть</button></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td class="text-end"><button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#docDetails">Посмотреть</button></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td class="text-end"><button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#docDetails">Посмотреть</button></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td class="text-end"><button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#docDetails">Посмотреть</button></td>
      </tr>
      <tr>
        <th scope="row">3</th>
        <td colspan="4">Larry the Bird</td>
        <td class="text-end">@twitter</td>
      </tr>
    </tbody>
  </table>

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
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td colspan="2">Larry the Bird</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
              <table class="table table-sm table-striped">
                <thead>
                  <tr  class="align-items-start">
                    <th scope="col">#</th>
                    <th scope="col">Наименование товара</th>
                    <th scope="col">Штрихкод</th>
                    <th scope="col">Категория</th>
                    <th scope="col">Цена закупки</th>
                    <th scope="col">Цена оптовая</th>
                    <th scope="col">Цена продажи</th>
                    <th scope="col">Кол-во</th>
                    <th scope="col">Ед. измерения</th>
                    <th scope="col">Поставщик</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td colspan="8">Larry the Bird</td>
                    <td>@twitter</td>
                  </tr>
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
@endsection