@extends('store.layout')

@section('nav-tools')    
  <div class="container d-flex flex-wrap justify-content-between align-items-center">

    <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Новый приход</h4>

    <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
      <input type="search" class="form-control" placeholder="Поиск..." aria-label="Search">
    </form>

    <ul class="nav col-lg-auto text-end me-lg-2 text-small">
      <li>
        <a href="#" class="nav-link text-primary"><i class="bi bi-journals"></i></a>
      </li>
      <li>
        <a href="#" class="nav-link text-primary"><i class="bi bi-file-text-fill"></i></a>
      </li>
      <li>
        <a href="#" class="nav-link text-primary"><i class="bi bi-file-earmark-plus-fill"></i></a>
      </li>
      <li>
        <a href="#" class="nav-link text-primary"><i class="bi bi-file-earmark-text-fill"></i></a>
      </li>
    </ul>

    <div class="text-end ms-md-auto ms-lg-0">
      <a href="/{{ $lang }}/store/add-product" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i> Добавить товар</a>
    </div>
  </div>
@endsection

@section('content')
  <table class="table table-success- table-sm table-striped">
    <thead>
      <tr>
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
@endsection
