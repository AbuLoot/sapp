<div>
  <div class="doc" id="doc">
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
          <td>№{{ $outgoingDoc->doc_no }}</td>
          <td>{{ $outgoingDoc->created_at }}</td>
        </tr>
      </tbody>
    </table>
    <p></p>
    <h3 class="text-center">Накладная на отпуск запасов на сторону</h3>
    <p></p>
    <table class="table-bordered">
      <tbody>
        <tr>
          <td>Организация (индивидуальный предприниматель) - отправитель</td>
          <td>Организация (индивидуальный предприниматель) - получатель</td>
          <td>Ответственный за поставку (Ф.И.О.)</td>
          <td>Транспортная организация</td>
          <td>Товарно-транспортная накладная (номер, дата)</td>
        </tr>
        <tr>
          <td>{{ $companyName }}</td>
          <td>{{ $customerName }}</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
    <br>

    <table class="table-bordered w-full">
      <tbody>
        <tr>
          <td rowspan="2">Номер по порядку</td>
          <td rowspan="2">Наименование, характеристика</td>
          <td rowspan="2">Номенкла-турный номер</td>
          <td colspan="2">Количество</td>
          <td rowspan="2">Цена за единицу, в тенге</td>
        </tr>
        <tr>
          <td>подлежит отпуску</td>
          <td>отпущено</td>
        </tr>
        <!-- <tr>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
          <td>6</td>
        </tr> -->
        <?php
          $sumUndiscounted = 0;
          $sumDiscounted = 0;
          $n = 1;
        ?>
        @foreach($productsData as $key => $product)
          <?php
            $unit = $units->where('id', $product['unit'])->first()->title ?? null;

            if ($outgoingDoc->operation_code != 'sale-on-credit') {
              $percentage = $product['price'] / 100;
              $sumDiscounted += $product['outgoingCount'] * ($product['price'] - $percentage * $product['discount']);
              $sumUndiscounted += $product['outgoingCount'] * $product['price'];
            }
          ?>
          <tr>
            <td>{{ $n++ }}</td>
            <td>{{ $product['title'] }}</td>
            <td>
              @foreach($product['barcodes'] as $barcode)
                {{ $barcode }}
              @endforeach
            </td>
            <td>{{ $product['outgoingCount'] . $unit }}</td>
            <td>{{ $product['outgoingCount'] . $unit }}</td>
            <td>{{ $product['price'] . $currency }}</td>
          </tr>
        @endforeach
        <tr>
          <th class="text-end" colspan="5">Итого</th>
          <th>{{ $sumDiscounted . $currency }}</th>
        </tr>
      </tbody>
    </table>
    <br>

    <p>Тип операции: {{ __('operation-codes.'.$outgoingDoc->operation_code) }}</p>
    <p>Всего отпущено количество запасов (прописью)___________ на сумму</p>
    <p>(прописью), в тенге___________________________</p>
    <br>

    <table border="0">
      <tbody>
        <tr>
          <td>
            <p>Отпуск разрешил ________/___________/____________</p>
            <p>должность подпись расшифровка подписи</p>
            <p>Главный бухгалтер________/________</p>
            <p>М.П. подпись расшифровка подписи</p>
            <p>Отпустил ___________/_____________</p>
            <p>подпись расшифровка подписи</p>
          </td>
          <td>
            <p>По доверенности №___________ от "____"____________ 20 _____ года</p>
            <p>выданной _______________________</p>
            <p>________________________________</p>
            <p>Запасы получил ______/__________</p>
            <p>подпись расшифровка подписи</p>
            <div><br></div>
          </td>
        </tr>
      </tbody>
    </table>

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