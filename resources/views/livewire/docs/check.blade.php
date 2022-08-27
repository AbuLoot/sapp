<div>  
  <div class="doc" id="doc">
    <br>
    <div class="text-center">
      <p>{{ $companyName }}</p>
      <h5>Квитанция к приходному кассовому ордеру</h5>
      <p>№{{ $docNo }}</p>
    </div>
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
            <td class="text-end">{{ $product['count'] . ' x ' . $product['price'] }}</td>
            <td class="text-end">={{ $product['count'] * $product['price'] }}</td>
          </tr>
          <?php
            $percentage = $product['price'] / 100;
            $sumDiscounted += $product['count'] * ($product['price'] - $percentage * $product['discount']);
            $sumUndiscounted += $product['count'] * $product['price'];
          ?>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">Сумма без скидки</td>
          <td colspan="2" class="text-end">{{ $sumUndiscounted }}</td>
        </tr>
        <tr>
          <td colspan="2">Итоговая сумма</td>
          <td colspan="2" class="text-end"><b>{{ $sumDiscounted }}</b></td>
        </tr>
      </tfoot>
    </table>

    <hr>
    <p>Метод оплаты: {{ $paymentType }}<br>
    Дата: {{ $date }}<br>
    Кассир: {{ $cashierName }}</p>    
  </div>
  <div class="functions d-grid gap-2">
    <button type="button" onclick="printPage()" class="btn btn-success rounded-0 btn-lg">Печать чека</button>
    <a href="{{ $prevPage }}" class="btn btn-primary rounded-0 btn-lg">Назад</a>
  </div>
  
  <script>
    function printPage() {
      var printPage = window.open("/");
      printPage.document.write(document.getElementsById("doc").innerHTML);
      printPage.print();
    }
  </script>
</div>
