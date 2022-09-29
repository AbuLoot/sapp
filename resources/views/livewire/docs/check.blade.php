<div>  
  <div class="doc" id="doc">
    <br>
    <div class="text-center">
      <p>Организация (индивидуальный предприниматель) {{ $companyName }}</p>
      <h5>Квитанция к приходному кассовому ордеру</h5>
      <p>№{{ $docNo }}</p>
    </div>
    <p>Принято от {{ $customerName }}</p>
    <hr>
    <table>
      <tbody>
        <?php 
          $sumUndiscounted = 0;
          $sumDiscounted = 0;
        ?>
        @foreach($productsData as $product)
          <tr>
            <td>{{ $product['title'] }}</td>
            <td class="text-end">{{ $product['outgoingCount'] . ' x ' . $product['price'] }}</td>
            <td class="text-end">={{ $product['outgoingCount'] * $product['price'] }}</td>
          </tr>
          <?php
            $percentage = $product['price'] / 100;
            $sumDiscounted += $product['outgoingCount'] * ($product['price'] - $percentage * $product['discount']);
            $sumUndiscounted += $product['outgoingCount'] * $product['price'];
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
    Дата: {{ $createdAt }}<br>
    Кассир: {{ $cashierName }}</p>
    <style type="text/css">
      body {
        max-width: 300px;
        margin: 0 auto;
      }
      table {
        width: 100%;
        border-collapse: collapse;
      }
      .doc {
        background-color: #fff;
        max-width: 300px;
        padding: 10px 5px 20px;
      }
    </style>
  </div>

  <div class="functions d-grid gap-2">
    <button type="button" onclick="printPage()" class="btn btn-success btn-lg">Печать чека</button>
    <a href="{{ $prevPage }}" class="btn btn-primary btn-lg">Назад</a>
  </div>

  <script>
    function printPage() {
      var printContents = document.getElementById('doc').innerHTML;
      var originalContents = document.body.innerHTML;

      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
    }
  </script>
</div>
