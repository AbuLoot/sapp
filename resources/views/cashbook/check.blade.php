<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Document">
  <meta name="author" content="Sanapp">
  <title>Sanapp Cashbook</title>
</head>
<body>

  <div>
    {{ $slot }}
  </div>
  <div style="max-width: 300px;">
  <br>
  <p class="text-center">Организация (индивидуальный предприниматель)</p>
  <h3 class="text-center">Квитанция к приходному кассовому ордеру</h3>
  <p class="text-center">№{{ $docNo }}</p>
  <p>Принято от {{ $clientName }}</p>
  <hr>
  <table>
    <tbody>
      <?php 
        $sumUndiscounted = 0;
        $sumDiscounted = 0;
      ?>
      @foreach($productsList as $product)
        <tr>
          <td>{{ $product['title'] }}</td>
          <td>{{ $product['count'] }}</td>
          <td>{{ $product['price'] }}</td>
        </tr>
        <?php
          $percentage = $product['price'] / 100;
          $sumDiscounted += $product['count'] * ($product['price'] - $percentage * $product['discount']);
          $sumUndiscounted += $product['count'] * $product['price'];
        ?>
      @endforeach
      <tr>
        <td>Сумма без скидки</td>
        <td>{{ $sumUndiscounted }}</td>
      </tr>
      <tr>
        <td>Итоговая сумма</td>
        <td>{{ $sumDiscounted }}</td>
      </tr>
    </tbody>
  </table>

  <hr>
  <p>Метод оплаты: {{ $paymentType }}</p>
  <p>Дата: {{ $date }}</p>
  <p>Кассир: {{ $cashierName }}</p>
</div>

<style type="text/css">
  table {
    border-collapse: collapse;
  }
  .table-bordered,
  .table-bordered tr,
  .table-bordered tr td {
    border: 1px solid;
  }
  .text-center {
    text-align: center;
  }
  p {
    display: flex;
    align-items: center;
  }
</style>

</body>
</html>
