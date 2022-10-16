<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="DevPlay">
  <title>Sanapp Storage</title>

  <link rel="canonical" href="/">

  <!-- Bootstrap core CSS -->
  <!-- <link href="/store/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="/img/icon.svg" sizes="180x180">
  <link rel="icon" href="/img/icon.svg" sizes="32x32" type="image/png">
  <link rel="icon" href="/img/icon.svg" sizes="16x16" type="image/png">
  <link rel="mask-icon" href="/img/icon.svg" color="#7952b3">
  <link rel="icon" href="/img/icon.svg">
  <meta name="theme-color" content="#7952b3">

  <!-- Custom styles for this template -->
  <link href="/store/custom.css" rel="stylesheet">
  <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">

  @livewireStyles

  @yield('head')
</head>
<body class="bg-light">
  <?php $lang = app()->getLocale(); ?>
  <nav class="navbar navbar-expand-lg navbar-dark bg-brand" aria-label="Main navigation">
    <div class="container-xl">
      <a href="/{{ app()->getLocale() }}/storage">
        <img src="/img/logo.svg" width="auto" height="40">
      </a>
      <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav py-2 mx-auto">
          <li class="nav-item">
            <a class="nav-link px-3 @if(Request::is($lang.'/storage')) active @endif" aria-current="page" href="/{{ $lang }}/storage">Главная</a>
          </li>
          @can('docs', Auth::user())
            <li class="nav-item">
              <a class="nav-link px-3 @if(Request::is($lang.'/storage/docs*')) active @endif" href="/{{ $lang }}/storage/docs">Накладные</a>
            </li>
          @endcan
          @can('inventory', Auth::user())
            <li class="nav-item">
              <a class="nav-link px-3 @if(Request::is($lang.'/storage/inventory')) active @endif" href="/{{ $lang }}/storage/inventory">Ревизия</a>
            </li>
          @endcan
          @can('income', Auth::user())
            <li class="nav-item">
              <a class="nav-link px-3 @if(Request::is($lang.'/storage/income')) active @endif" href="/{{ $lang }}/storage/income">Новый приход</a>
            </li>
          @endcan
          @can('writeoff', Auth::user())
            <li class="nav-item">
              <a class="nav-link px-3 @if(Request::is($lang.'/storage/writeoff')) active @endif" href="/{{ $lang }}/storage/writeoff">Списание</a>
            </li>
          @endcan
          @can('storedocs', Auth::user())
            <li class="nav-item">
              <a class="nav-link px-3 @if(Request::is($lang.'/storage/storedocs')) active @endif" href="/{{ $lang }}/storage/storedocs">Карточка учета</a>
            </li>
          @endcan
        </ul>

        <div class="dropdown d-flex text-end">
          <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-4 text-white"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end text-small shadow">
            <div class="px-3 py-1">
              {{ Auth()->user()->name.' '.Auth()->user()->lastname }}<br>
              {{ Auth::user()->email }}
            </div>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">Выйти</a>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <main class="mb-5">
    {{ $slot }}
  </main>

  @livewireScripts

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>

  <script type="text/javascript">
    // Tooltip
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    // Toast Script
    window.addEventListener('show-toast', event => {
      if (event.detail.selector) {
        const btnCloseModal = document.getElementById(event.detail.selector)
        btnCloseModal.click()
      }

      const toast = new bootstrap.Toast(document.getElementById('liveToast'))
      toast.show()

      const toastBody = document.getElementById('toastBody')
      toastBody.innerHTML = event.detail.message
    })
  </script>

  @yield('scripts')
</body>
</html>