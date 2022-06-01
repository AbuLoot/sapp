@extends('store.layout')

@section('nav-functions')    
  <div class="container d-flex flex-wrap justify-content-between align-items-center">

    <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Списание</h4>

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
  </div>
@endsection

@section('content')
  <table class="table align-middle table-sm table-striped">
    <thead>
      <tr>
        <th scope="col">Наименование товара</th>
        <th scope="col">Штрихкод</th>
        <th scope="col">Категория</th>
        <th scope="col">Цена закупки</th>
        <th scope="col">Цена оптовая</th>
        <th scope="col">Цена продажи</th>
        <th scope="col">Количество</th>
        <th scope="col">Количество</th>
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
        <td>Otto</td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td class="text-end"><a href="#" class="fs-5"><i class="bi bi-file-x"></i></a></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td>Otto</td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td class="text-end"><a href="#" class="fs-5"><i class="bi bi-file-x"></i></a></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td>Otto</td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td class="text-end"><a href="#" class="fs-5"><i class="bi bi-file-x"></i></a></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td>Otto</td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td class="text-end"><a href="#" class="fs-5"><i class="bi bi-file-x"></i></a></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td>Otto</td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td class="text-end"><a href="#" class="fs-5"><i class="bi bi-file-x"></i></a></td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
        <td>Jacob</td>
        <td>Otto</td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td>
          <input type="text" class="form-control form-control-sm" aria-label="input" aria-describedby="inputGroup-sizing-sm" value="10">
        </td>
        <td class="text-end"><a href="#" class="fs-5"><i class="bi bi-file-x"></i></a></td>
      </tr>
      <tr>
        <th scope="row">3</th>
        <td colspan="7">Larry the Bird</td>
        <td class="text-end">@twitter</td>
      </tr>
    </tbody>
  </table>
@endsection