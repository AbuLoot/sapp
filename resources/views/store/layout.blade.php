<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="DevPlay">
  <meta name="generator" content="Hugo 0.88.1">
  <title>Sanapp Storage</title>

  <link rel="canonical" href="/">

  <!-- Bootstrap core CSS -->
  <!-- <link href="/store/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="/img/icon.svg" sizes="180x180">
  <link rel="icon" href="/img/icon.svg" sizes="32x32" type="image/png">
  <link rel="icon" href="/img/icon.svg" sizes="16x16" type="image/png">
  <link rel="mask-icon" href="/img/icon.svg" color="#7952b3">
  <link rel="icon" href="/img/icon.svg">
  <meta name="theme-color" content="#7952b3">

  <!-- Custom styles for this template -->
  <link href="/store/offcanvas.css" rel="stylesheet">
  <link href="/store/custom.css" rel="stylesheet">
  <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">

  @livewireStyles

  @yield('head')
</head>
<body class="bg-light">
  <?php $lang = app()->getLocale(); ?>
  <nav class="navbar navbar-expand-lg navbar-dark bg-brand" aria-label="Main navigation">
    <div class="container-xl">
      <a href="#">
        <img src="/img/logo.svg" width="auto" height="40">
      </a>
      <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav py-2 mx-auto">
          <li class="nav-item">
            <a class="nav-link px-3 @if(Request::is($lang.'/store')) active @endif" aria-current="page" href="/{{ $lang }}/store">Главная</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 @if(Request::is($lang.'/store/docs*')) active @endif" href="/{{ $lang }}/store/docs">Накладные</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 @if(Request::is($lang.'/store/revision*')) active @endif" href="/{{ $lang }}/store/revision">Ревизия</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 @if(Request::is($lang.'/store/income*')) active @endif" href="/{{ $lang }}/store/income">Новый приход</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 @if(Request::is($lang.'/store/writeoff*')) active @endif" href="/{{ $lang }}/store/writeoff">Списание</a>
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

  <main class="mb-5">
    {{ $slot }}
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
  <script type="text/javascript">
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  </script>
  <!-- <script src="/store/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script src="/store/offcanvas.js"></script>

  @livewireScripts

  @yield('scripts')
</body>
</html>