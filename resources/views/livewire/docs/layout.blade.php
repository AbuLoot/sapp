<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Document">
  <meta name="author" content="Sanapp">
  <title>Sanapp Cashbook</title>
  <!-- Favicons -->
  <link rel="apple-touch-icon" href="/img/icon.svg" sizes="180x180">
  <link rel="icon" href="/img/icon.svg" sizes="32x32" type="image/png">
  <link rel="icon" href="/img/icon.svg" sizes="16x16" type="image/png">
  <link rel="mask-icon" href="/img/icon.svg" color="#7952b3">
  <link rel="icon" href="/img/icon.svg">
  <meta name="theme-color" content="#7952b3">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

</head>
<body>

  <div>
    {{ $slot }}
  </div>

  <style type="text/css">
    body {
      background-color: #000;
      /*font-family: sans-serif;*/
    }
    table {
      border-collapse: collapse;
    }
    .table-bordered,
    .table-bordered tr,
    .table-bordered tr td {
      border: 1px solid;
    }
    .w-full {
      width: 100%;
    }
    .text-center {
      text-align: center;
    }
    .text-end {
      text-align: right;
    }
    .functions {
      margin: auto;
      padding: 12px;
      background-color: #fff;
    }
  </style>
</body>
</html>
