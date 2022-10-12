<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Sanapp</title>

  <!-- Bootstrap core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
 
  <!-- Favicons -->
  <link rel="mask-icon" href="/img/icon.svg" color="#6559d4">
  <link rel="icon" href="/img/icon.svg">
  <meta name="theme-color" content="#6559d4">

  <!-- Custom styles for this template -->
  <link href="/css/custom.css" rel="stylesheet">
  <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-brand bg-brand-border py-3" aria-label="Main navigation">
		<div class="container-xl">
		  <a href="/apps" class="navbar-brand p-0">
				<img src="/img/logo.svg" width="auto" height="40">
		  </a>

		  @auth
			  <div class="flex-shrink-0 dropdown">
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
		  @endauth
		</div>
  </nav>

  <div class="px-3 py-3 border-bottom mb-3">
		<div class="container d-flex flex-wrap justify-content-between align-items-center">
		  <h2>{{ $title }}</h2>
		</div>
  </div>

  <main class="container my-4">
		{{ $slot }}
  </main>

  <div class="container">
		<footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
		  <p class="col-md-4 mb-0 text-muted">&copy; {{ date('Y') }} Sanapp Company</p>

		  <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
				<img src="/img/logo-dark.svg" width="auto" height="40">
		  </a>

		  <ul class="nav col-md-4 justify-content-end">
				@if(Auth::check())
					<li class="nav-item"><a href="/apps" class="nav-link px-2 text-muted">Главная</a></li>
					<li class="nav-item">
						<form method="POST" action="{{ route('logout') }}">
		          @csrf
							<a href="#" class="nav-link px-2 text-muted" onclick="event.preventDefault(); this.closest('form').submit();">Выйти</a>
		        </form>
		      </li>
				@else
					<li class="nav-item"><a href="/login" class="nav-link px-2 text-muted">Вход</a></li>
					<li class="nav-item"><a href="/register" class="nav-link px-2 text-muted">Регистрация</a></li>
				@endif
		  </ul>
		</footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
</body>
</html>

