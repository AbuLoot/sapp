<!DOCTYPE html>
<!-- saved from url=(0060) -->
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.88.1">
  <title>Offcanvas navbar template · Bootstrap v5.1</title>

  <link rel="canonical" href="">

  <!-- Bootstrap core CSS -->
  <!-- <link href="template/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="apple-touch-icon.png" sizes="180x180">
  <link rel="icon" href="favicon-32x32.png" sizes="32x32" type="image/png">
  <link rel="icon" href="favicon-16x16.png" sizes="16x16" type="image/png">
  <link rel="manifest" href="manifest.json">
  <link rel="mask-icon" href="safari-pinned-tab.svg" color="#7952b3">
  <link rel="icon" href="favicon.ico">
  <meta name="theme-color" content="#7952b3">
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>

  <!-- Custom styles for this template -->
  <link href="template/offcanvas.css" rel="stylesheet">
  <link href="template/custom.css" rel="stylesheet">
  <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-brand" aria-label="Main navigation">
    <div class="container-xl">
      <a href="#">
        <img src="img/logo.svg" width="auto" height="40">
      </a>
      <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav py-2 mx-auto">
          <li class="nav-item">
            <a class="nav-link px-3" aria-current="page" href="main.html">Главная</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3" href="docs.html">Накладные</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 active" href="revision.html">Ревизия</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3" href="income.html">Новый приход</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3" href="writeoff.html">Списание</a>
          </li>
        </ul>

        <div class="dropdown d-flex text-end">
          <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu dropdown-menu-lg-end text-small" aria-labelledby="dropdownUser1" style="">
            <li><a class="dropdown-item" href="#">New project...</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- <div class="nav-scroller bg-body shadow-sm">
    <nav class="nav nav-underline" aria-label="Secondary navigation">
      <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
      <a class="nav-link" href="#">
        Friends
        <span class="badge bg-light text-dark rounded-pill align-text-bottom">27</span>
      </a>
      <a class="nav-link" href="#">Explore</a>
      <a class="nav-link" href="#">Suggestions</a>
      <a class="nav-link" href="#">Link</a>
      <a class="nav-link" href="#">Link</a>
      <a class="nav-link" href="#">Link</a>
      <a class="nav-link" href="#">Link</a>
      <a class="nav-link" href="#">Link</a>
    </nav>
  </div> -->

  <div class="px-3 py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Ревизия №1</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
        <input type="search" class="form-control" placeholder="Поиск..." aria-label="Search">
      </form>

      <ul class="nav col-lg-auto text-end me-lg-2 text-small">
        <li>
          <a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="journals"><i class="bi bi-journals"></i></a>
        </li>
        <li>
          <a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="file-text"><i class="bi bi-file-text-fill"></i></a>
        </li>
        <li>
          <a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="file-earmark"><i class="bi bi-file-earmark-plus-fill"></i></a>
        </li>
        <li>
          <a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="file-earmark"><i class="bi bi-file-earmark-text-fill"></i></a>
        </li>
      </ul>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="revision-history.html" class="btn btn-primary"><i class="bi bi-clock-history me-2"></i> История ревизий</a>
      </div>
    </div>
  </div>

  <main class="container">

    <table class="table align-middle table-sm table-striped">
      <thead>
        <tr>
          <th scope="col">Номер ревизии</th>
          <th scope="col">Количество позиции</th>
          <th scope="col">Автор</th>
          <th scope="col">Дата</th>
          <th scope="col">Время</th>
          <th scope="col">Количество недостачи</th>
          <th scope="col">Количество излишки</th>
          <th scope="col">Сумма недостачи</th>
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
          <td class="text-end"><i class="bi bi-archive-fill"></i></td>
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
          <td class="text-end"><i class="bi bi-archive-fill"></i></td>
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
          <td class="text-end"><i class="bi bi-archive-fill"></i></td>
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
          <td class="text-end"><i class="bi bi-archive-fill"></i></td>
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
          <td class="text-end"><i class="bi bi-archive-fill"></i></td>
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
          <td class="text-end"><i class="bi bi-archive-fill"></i></td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td colspan="7">Larry the Bird</td>
          <td class="text-end">@twitter</td>
        </tr>
      </tbody>
    </table>

    <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#revisionDetails">Посмотреть детали</button>

    <!-- Modal -->
    <div class="modal fade" id="revisionDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Детали ревизий</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Инфо о ревизий</button>
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
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
  <script type="text/javascript">
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  </script>
  <!-- <script src="template/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script src="template/offcanvas.js"></script>
</body>
</html>