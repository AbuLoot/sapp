<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.88.1">
  <title>Sanapp Cashbook</title>

  <link rel="canonical" href="">

  <!-- Bootstrap core CSS -->
  <!-- <link href="/cashbook/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="apple-touch-icon.png" sizes="180x180">
  <link rel="icon" href="favicon-32x32.png" sizes="32x32" type="image/png">
  <link rel="icon" href="favicon-16x16.png" sizes="16x16" type="image/png">
  <link rel="manifest" href="manifest.json">
  <link rel="mask-icon" href="safari-pinned-tab.svg" color="#7952b3">
  <link rel="icon" href="favicon.ico">
  <meta name="theme-color" content="#7952b3">

  <!-- Custom styles for this template -->
  <link href="/cashbook/offcanvas.css" rel="stylesheet">
  <link rel="stylesheet" href="/cashbook/custom.css">
  <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">
  <header class="p-3 bg-brand bg-brand-border">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="#" class="me-4">
          <img src="/img/logo.svg" width="auto" height="40">
        </a>

        <button class="btn btn-warning rounded-circle me-auto"><i class="bi bi-arrow-clockwise"></i></button>

        <div class="text-end me-4">
          <button type="button" class="btn btn-outline-light btn-lg me-2" data-bs-toggle="modal" data-bs-target="#fastProducts"><i class="bi bi-cart-check-fill"></i> Быстрые товары</button>
          <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addClient"><i class="bi bi-person-plus-fill"></i> Добавить клиетнта</button>
        </div>

        <div class="dropdown d-flex text-end">
          <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu dropdown-menu-lg-end text-small" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <div class="px-3 py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap">
      <form class="col-4 col-lg-4 me-4">
        <input type="search" class="form-control form-control-lg" placeholder="Поиск по названию, штрихкоду..." aria-label="Search">
      </form>

      <form class="col-4 col-lg-4 me-4">
        <input type="search" class="form-control form-control-lg" placeholder="Поиск клиентов..." aria-label="Search">
      </form>

      <button class="btn btn-dark btn-lg ms-auto" type="button" data-bs-toggle="modal" data-bs-target="#closeTheCash">Закрыть смену</button>
    </div>
  </div>

  <main class="container" style="margin-bottom: 170px;">
    <table class="table table-sm- table-striped table-borderless border">
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
  </main>

  <footer class="d-flex flex-wrap fixed-bottom bg-light justify-content-between align-items-center py-2 border-top">
    <div class="container">
      <div class="row gx-2 pb-2">
        <div class="col-8 cash-operations">
          <div class="row gx-lg-2 gx-sm-1 gy-sm-1">
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#return">Оформить<br> возврат</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#depositMoney">Внести</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button">Оптовые<br> цены</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#listOfDeptors">Список<br> должников</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#expense">Расход</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button">Отложить<br> данный чек</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button">Повторная<br> печать</button>
              </div>
            </div>
            <div class="col-3 gy-2">
              <div class="d-grid gap-2 h-100">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#defferedChecks">Отложенные<br> чеки</button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4 gy-2">

          <table class="table table-sm table-bordered mb-2">
            <tbody>
              <tr>
                <td>Скидка:</td>
                <td>10%</td>
                <td class="text-center text-bg-success" rowspan="2"><h5>Сумма</h5> <b>1 950 000〒</b></td>
              </tr>
              <tr>
                <td>Без скидки:</td>
                <td>1 990 000〒</td>
              </tr>
            </tbody>
          </table>

          <div class="d-grid">
            <button class="btn btn-success btn-lg" type="button">Продать</button>
          </div>

        </div>
      </div>
    </div>
  </footer>

  <!-- Modal Close The Cash -->
  <div class="modal fade" id="closeTheCash" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light py-2">
        <div class="modal-body">
          <div class="text-center">
            <div class="display-1">
              <i class="bi bi-x-octagon-fill"></i>
            </div>
            <h4>Вы действительно хотите закрыть смену?</h4>
            <button type="button" class="btn btn-outline-dark btn-lg me-1 mb-2">Отмена</button>
            <button type="button" class="btn btn-dark btn-lg me-1 mb-2">Закрыть смену</button>
            <br>
            <br>
            <p>Количество банкнот и монет номиналами:</p>

            <div>
              <button type="button" class="btn btn-warning btn-lg me-1 mb-2">20000〒</button>
              <button type="button" class="btn btn-warning btn-lg me-1 mb-2">10000〒</button>
              <button type="button" class="btn btn-warning btn-lg me-1 mb-2">5000〒</button>
              <button type="button" class="btn btn-warning btn-lg me-1 mb-2">2000〒</button>
              <button type="button" class="btn btn-warning btn-lg me-1 mb-2">1000〒</button>
              <button type="button" class="btn btn-warning btn-lg me-1 mb-2">500〒</button>
              <br>
              <button type="button" class="btn btn-secondary btn-lg me-1 mb-2">200〒</button>
              <button type="button" class="btn btn-secondary btn-lg me-1 mb-2">100〒</button>
              <button type="button" class="btn btn-secondary btn-lg me-1 mb-2">50〒</button>
              <button type="button" class="btn btn-secondary btn-lg me-1 mb-2">20〒</button>
              <button type="button" class="btn btn-secondary btn-lg me-1 mb-2">10〒</button>
              <button type="button" class="btn btn-secondary btn-lg me-1 mb-2">5〒</button>
            </div>

            <div class="row mt-2 gx-2">
              <div class="col-6 col-lg-4 offset-lg-2 mb-3">
                <input type="text" name="nominal" class="form-control form-control-lg" placeholder="Количество номиналов">
              </div>
              <div class="col-6 col-lg-4 mb-3">
                <div class="d-grid" role="group" aria-label="Basic example">
                  <button type="button" class="btn btn-success btn-lg">Сохранить</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Fast Products -->
  <div class="modal fade" id="fastProducts" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Быстрые товары</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form>
            <div class="mb-3">
              <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" required placeholder="Поиск товаров...">
            </div>
          </form>

          <div class="row">
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-file-x"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-file-x"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
          </div>

          <nav aria-label="Page navigation example">
            <ul class="pagination pagination-lg">
              <li class="page-item"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal Add Client -->
  <div class="modal fade" id="addClient" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Добавить клиента</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-6">
                <div class="mb-3">
                  <label for="name" class="form-label">Имя</label>
                  <input type="text" class="form-control form-control-lg" id="name" name="name" minlength="2" value="" required>
                </div>
              </div>
              <div class="col-6">
                <div class="mb-3">
                  <label for="lastname" class="form-label">Фамилия</label>
                  <input type="text" class="form-control form-control-lg" id="lastname" name="lastname" minlength="2" value="" required>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="tel" class="form-label">Номер телефона</label>
              <input type="tel" class="form-control form-control-lg" id="tel" name="tel" minlength="2" value="" required>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Адрес</label>
              <input type="text" class="form-control form-control-lg" id="address" name="address" minlength="2" value="" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-lg text-center">Добавить</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Return -->
  <div class="modal fade" id="return" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Детали накладной</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form>
            <div class="mb-3">
              <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" required placeholder="Поиск...">
            </div>
          </form>

          <h5>Чек №1</h5>

          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Возврат</button></td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Возврат</button></td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Возврат</button></td>
              </tr>
            </tbody>
          </table>

          <div class="d-flex">
            <h5>Общая сумма долгов</h5>
            <h5 class="ms-auto">100 000 000KZT</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-lg text-end"><i class="be bi-hdd-fill me-2"></i> Сохранить</button>
          <button type="button" class="btn btn-dark btn-lg text-end"><i class="be bi-printer-fill me-2"></i> Печать</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal List Of Deptors -->
  <div class="modal fade" id="listOfDeptors" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Детали накладной</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Погасить</button></td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Погасить</button></td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Погасить</button></td>
              </tr>
            </tbody>
          </table>

          <nav aria-label="Page navigation example">
            <ul class="pagination pagination-lg">
              <li class="page-item"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav>

          <div class="d-flex">
            <h5>Общая сумма долгов</h5>
            <h5 class="ms-auto">100 000 000KZT</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark btn-lg text-end"><i class="be bi-printer-fill me-2"></i> Печать</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Repayment Or Dept -->
  <div class="modal fade" id="repaymentOfDept" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Погашение долга</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="title" class="form-label">Сумма погашение долга</label>
              <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" required>
            </div>
          </form>

          <div class="d-flex">
            <h5>Общая сумма долгов</h5>
            <h5 class="ms-auto">100 000 000KZT</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-lg text-center">Погасить</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Deposit Money -->
  <div class="modal fade" id="depositMoney" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Внести деньги в кассу</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="title" class="form-label">Сумма к внесению</label>
              <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" required>
            </div>
            <div class="mb-3">
              <label for="comment">Комментарий</label>
              <textarea class="form-control" name="comment" rows="2" maxlength="2000"></textarea>
            </div>
          </form>

          <div class="d-flex">
            <h5>Сумма</h5>
            <h5 class="ms-auto">100 000 000KZT</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-lg text-center">Внести</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Expense -->
  <div class="modal fade" id="expense" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Оформить расход</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="title" class="form-label">Сумма к расходу</label>
              <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" required>
            </div>
            <div class="mb-3">
              <label for="comment">Комментарий</label>
              <textarea class="form-control" name="comment" rows="2" maxlength="2000"></textarea>
            </div>
          </form>

          <div class="d-flex">
            <h5>Сумма</h5>
            <h5 class="ms-auto">100 000 000KZT</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-lg text-center">Оформить</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Deffered Checks -->
  <div class="modal fade" id="defferedChecks" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Отложенные чеки</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form>
            <div class="mb-3">
              <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" required placeholder="Поиск по названию, штрихкоду...">
            </div>
          </form>

          <div class="row">
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-file-x"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-file-x"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
            <div class="col-3 mb-3">
              <div class="card bg-dark text-white">
                <img src="..." class="card-img" alt="..." style="min-height: 100px;">
                <div class="card-img-overlay">
                  <div class="text-end">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <h6 class="card-title">Card title</h6>
                  <p class="card-text">10 000 000KZT</p>
                </div>
              </div>
            </div>
          </div>

          <nav aria-label="Page navigation example">
            <ul class="pagination pagination-lg">
              <li class="page-item"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-lg text-end">Вернуться к чеку</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
  <script type="text/javascript">
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  </script>
  <!-- <script src="/cashbook/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script src="/cashbook/offcanvas.js"></script>
</body>
</html>