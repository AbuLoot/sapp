<div>
  <div class="doc" id="doc">
    <br>
    <p class="text-center">Организация (индивидуальный предприниматель) {{ $companyName }}</p>
    <table class="table-bordered margin-left-auto">
      <tbody>
        <tr>
          <td>ИИН/БИН</td>
          <td>{{ $companyBin }}</td>
        </tr>
      </tbody>
    </table>
    <br>
    <table class="table-bordered margin-left-auto">
      <tbody>
        <tr>
          <td>Номер документа</td>
          <td>Дата составления</td>
        </tr>
        <tr>
          <td>№{{ $incomingOrder->doc_no }}</td>
          <td>{{ $incomingOrder->created_at }}</td>
        </tr>
      </tbody>
    </table>
    <br>

    <h3 class="text-center">Приходный кассовый ордер</h3>

    <table width="620" class="table-bordered w-full">
      <tbody>
        <tr>
          <th>№</th>
          <th>Наименование</th>
          <th>Код</th>
          <th>Кол.</th>
          <th>Цена</th>
          <th class="text-end">Сумма</th>
        </tr>
        <?php $totalAmount = 0; ?>
        @if(in_array($incomingOrder->operation_code, ['incoming-cash', 'repayment-debt']))
          <tr>
            <td>1</td>
            <td>{{ __('operation-codes.'.$incomingOrder->operation_code) }}</td>
            <td></td>
            <td></td>
            <td class="text-end">0{{ $currency }}</td>
            <td class="text-end">{{ $incomingOrder->sum . $currency }}</td>
          </tr>
          <?php $totalAmount = $incomingOrder->sum; ?>
        @else
          @foreach($productsData as $key => $product)
            <tr>
              <td>{{ $key + 1 }}</td>
              <td>{{ $product['title'] }}</td>
              <td>
              @foreach($product['barcodes'] as $barcode)
                {{ $barcode }}
              @endforeach
              </td>
              <td>{{ $product['outgoingCount'] }}</td>
              <td>{{ $product['price'] . $currency }}</td>
              <td class="text-end">{{ $product['outgoingCount'] * $product['price'] . $currency }}</td>
            </tr>
            <?php $totalAmount += $product['outgoingCount'] * $product['price']; ?>
          @endforeach
        @endif
        <tr>
          <th class="text-end" colspan="5">Итого</th>
          <th class="text-end">{{ $totalAmount . $currency }}</th>
        </tr>
      </tbody>
    </table>
    <br><br>
    <p>
    Тип операции: {{ __('operation-codes.'.$incomingOrder->operation_code) }}<br>
    Основание: {{ $incomingOrder->comment }}<br>
    Принято от {{ $customerName }}<br>
    Метод оплаты: {{ $paymentTypeTitle }}<br>
    Кассир: {{ $cashierName }}</p>
    <br>
  </div>

  <div class="functions row">
    <div class="col d-grid gap-2">
      <button type="button" onclick="printPage()" class="btn btn-success btn-lg">Печать документа</button>
    </div>
    <div class="col d-grid gap-2">
      <a href="{{ $prevPage }}" class="btn btn-primary btn-lg">Назад</a>
    </div>
  </div>

  <style type="text/css">
    body {
      max-width: 797px;
      margin: 0 auto;
    }
    table {
      border-collapse: collapse;
    }
    .margin-left-auto {
      margin-left: auto;
    }
    .doc {
      background-color: #fff;
      max-width: 797px;
      padding: 10px 38px;
    }
  </style>

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