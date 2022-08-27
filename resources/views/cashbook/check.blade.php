
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
          <td class="text-end">{{ $product['price'] . $currency }}</td>
        </tr>
        <?php
          $percentage = $product['price'] / 100;
          $sumDiscounted += $product['count'] * ($product['price'] - $percentage * $product['discount']);
          $sumUndiscounted += $product['count'] * $product['price'];
        ?>
      @endforeach
      <tr>
        <td>Сумма без скидки</td>
        <td class="text-end">{{ $sumUndiscounted . $currency }}</td>
      </tr>
      <tr>
        <td>Итоговая сумма</td>
        <td class="text-end">{{ $sumDiscounted . $currency }}</td>
      </tr>
    </tbody>
  </table>

  <hr>
  <p>Метод оплаты: {{ $paymentType }}</p>
  <p>Дата: {{ $date }}</p>
  <p>Кассир: {{ $cashierName }}</p>
</div>
