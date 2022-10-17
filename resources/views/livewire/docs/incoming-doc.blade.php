<div>
  <div class="doc" id="doc">
    <p class="text-center">Организация (индивидуальный предприниматель) {{ $companyName }}</p>
    <p>Склад: {{ $storeTitle }}</p>

    <table class="table-bordered margin-left-auto">
      <tbody>
        <tr>
          <td>ИИН/БИН</td>
          <td>{{ $companyBin }}</td>
        </tr>
      </tbody>
    </table>
    <br>


    <h3 class="text-center">Приходный ордер ЗАПАСОВ</h3>
    <br>
    <table class="table-bordered">
      <tbody>
        <tr>
          <td>Номер документа</td>
          <td>Дата составления</td>
          <td>Вид операции</td>
          <td>Наименование поставщика</td>
          <td>Корреспондирующий счет</td>
          <td>Номер сопроводительного документа</td>
          <td>Номер платежного документа</td>
        </tr>
        <tr>
          <td>№{{ $incomingDoc->doc_no }}</td>
          <td>{{ $incomingDoc->created_at }}</td>
          <td></td>
          <td>{{ $contractorName }}</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
    <br>

    <table class="table-bordered">
      <tbody>
        <tr>
          <td rowspan="2">
            <p>Наименование, сорт, размер, марка</p>
          </td>
          <td rowspan="2">
            <p>Номенклатурный номер</p>
          </td>
          <td colspan="2">
            <p>По документу</p>
          </td>
          <td colspan="2">
            <p>Принято</p>
          </td>
          <td rowspan="2">
            <p>Цена за единицу, в тенге</p>
          </td>
          <td rowspan="2">
            <p>Сумма, в тенге</p>
          </td>
          <td rowspan="2">
            <p>Номер паспорта*</p>
          </td>
          <td rowspan="2">
            <p>Порядковый номер записи по складской картотеке</p>
          </td>
        </tr>
        <tr>
          <td>количество</td>
          <td>масса</td>
          <td>количество</td>
          <td>масса</td>
        </tr>
        <!-- <tr>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
          <td>6</td>
          <td>7</td>
          <td>8</td>
          <td>9</td>
          <td>10</td>
        </tr> -->
        <tr>
        <?php
          $sum = 0;
        ?>
        @foreach($productsData as $key => $product)
          <?php
            $unit = $units->where('id', $product['unit'])->first()->title ?? null;
            $sum += $product['purchase_price'] * $product['count'];
          ?>
          <tr>
            <td>{{ $product['title'] }}</td>
            <td>
              @if($product['barcodes'] != null)
                @foreach($product['barcodes'] as $barcode)
                  {{ $barcode }}
                @endforeach
              @endif
            </td>
            <td>{{ $product['count'] . $unit }}</td>
            <td></td>
            <td>{{ $product['count'] . $unit }}</td>
            <td></td>
            <td>{{ $product['purchase_price'] . $currency }}</td>
            <td>{{ $product['purchase_price'] * $product['count'] . $currency }}</td>
            <td></td>
            <td></td>
          </tr>
        @endforeach
        </tr>
        <tr>
          <th class="text-end" colspan="7">Итого</th>
          <th>{{ $sum . $currency }}</th>
          <td></td>
        </tr>
      </tbody>
    </table>
    <p></p>
    <p><br></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Принял _______/_________________ Сдал ________/__________________</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; подпись расшифровка подписи подпись расшифровка подписи</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *Графа "Номер паспорта" заполняется при оформлении операций по запасам, содержащим драгоценные металлы и камни.</p>

    <style type="text/css">
      @page { 
        size: landscape;
      }
      body {
        max-width: 1127px;
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
        max-width: 1127px;
        padding: 10px 38px;
      }
    </style>

  </div>

  <div class="functions row">
    <div class="col d-grid gap-2">
      <button type="button" onclick="printPage()" class="btn btn-success btn-lg">Печать документа</button>
    </div>
    <div class="col d-grid gap-2">
      <a href="{{ $prevPage }}" class="btn btn-primary btn-lg">Назад</a>
    </div>
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
