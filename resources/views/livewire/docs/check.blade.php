<div>  
  <div class="doc" id="doc">
    <br>
    <div class="text-center">
      <p>Организация (индивидуальный предприниматель) {{ $companyName }}</p>
      <h5>Квитанция к приходному кассовому ордеру</h5>
      <p>№{{ $incomingOrder->doc_no }}</p>
    </div>
    <p>Принято от {{ $customerName }}</p>
    <hr>
    <table>
      <tbody>
        <?php 
          $sumUndiscounted = 0;
          $sumDiscounted = 0;
        ?>
        @if(in_array($incomingOrder->operation_code, ['incoming-cash', 'repayment-debt']))
          <tr>
            <th>{{ __('operation-codes.'.$incomingOrder->operation_code) }}</th>
            <td></td>
            <td class="text-end">{{ $incomingOrder->sum . $currency }}</td>
          </tr>
          <?php $sumDiscounted = $incomingOrder->sum; ?>
        @else
          @foreach($productsData as $product)
            <tr>
              <td>{{ $product['title'] }}</td>
              <td class="text-end">{{ $product['outgoingCount'] . ' x ' . $product['price'] . $currency }}</td>
              <td class="text-end">={{ $product['outgoingCount'] * $product['price'] . $currency }}</td>
            </tr>
            <?php
              if ($incomingOrder->operation_code != 'sale-on-credit') {
                $percentage = $product['price'] / 100;
                $sumDiscounted += $product['outgoingCount'] * ($product['price'] - $percentage * $product['discount']);
                $sumUndiscounted += $product['outgoingCount'] * $product['price'];
              }
            ?>
          @endforeach
        @endif
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">Сумма без скидки</td>
          <td colspan="2" class="text-end">{{ $sumUndiscounted . $currency }}</td>
        </tr>
        <tr>
          <td colspan="2">Итоговая сумма</td>
          <td colspan="2" class="text-end"><b>{{ $sumDiscounted . $currency }}</b></td>
        </tr>
      </tfoot>
    </table>

    <hr>
    <p>Основание: {{ __('operation-codes.'.$incomingOrder->operation_code) }}</p>
    <p>Метод оплаты: {{ $paymentTypeTitle }}<br>
    Дата: {{ $incomingOrder->created_at }}<br>
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
