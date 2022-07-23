<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="DevPlay">
  <title>Sanapp Cashbook</title>

  <link rel="canonical" href="">

  <!-- Bootstrap core CSS -->
  <!-- <link href="/cashbook/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="/img/icon.svg" sizes="180x180">
  <link rel="icon" href="/img/icon.svg" sizes="32x32" type="image/png">
  <link rel="icon" href="/img/icon.svg" sizes="16x16" type="image/png">
  <link rel="mask-icon" href="/img/icon.svg" color="#7952b3">
  <link rel="icon" href="/img/icon.svg">
  <meta name="theme-color" content="#7952b3">

  <!-- Custom styles for this template -->
  <link href="/cashbook/custom.css" rel="stylesheet">
  <link href="/node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  @livewireStyles

  @yield('head')
</head>
<body class="bg-light">

  {{ $slot }}

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>

  @livewireScripts

  @yield('scripts')
</body>
</html>