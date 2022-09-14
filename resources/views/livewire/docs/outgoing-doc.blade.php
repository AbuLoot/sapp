<div>
  <div class="doc" id="doc">
    <table border="0">
      <tbody>
        <tr>
          <td>
            <p>Организация {{ $companyName }}</p>
          </td>
          <td>
            <p>ИИН/БИН: {{ $companyBin }}</p>
          </td>
        </tr>
      </tbody>
    </table>
    <p></p>
    <p><br></p>
    <p></p>
    <table class="table-bordered margin-left-auto">
      <tbody>
        <tr>
          <td>
            <p>Номер документа</p>
          </td>
          <td>
            <p>Дата составления</p>
          </td>
        </tr>
        <tr>
          <td>№{{ $docNo }}</td>
          <td>{{ $createdAt }}</td>
        </tr>
      </tbody>
    </table>
    <p></p>
    <h3 class="text-center">Накладная на отпуск запасов на сторону</h3>
    <p></p>
    <table class="table-bordered">
      <tbody>
        <tr>
          <td>
            <p>Организация (индивидуальный предприниматель) - отправитель</p>
          </td>
          <td>
            <p>Организация (индивидуальный предприниматель)- получатель</p>
          </td>
          <td>
            <p>Ответственный за поставку (Ф.И.О.)</p>
          </td>
          <td>
            <p>Транспортная организация</p>
          </td>
          <td>
            <p>Товарно-транспортная накладная (номер, дата)</p>
          </td>
        </tr>
        <tr>
          <td><br></td>
          <td><br></td>
          <td><br></td>
          <td><br></td>
          <td><br></td>
        </tr>
      </tbody>
    </table>
    <p></p>
    <p><br></p>
    <p></p>
    <table class="table-bordered">
      <tbody>
        <tr>
          <td rowspan="2">
            <p>Номер по порядку</p>
          </td>
          <td rowspan="2">
            <p>Наименование, характеристика</p>
          </td>
          <td rowspan="2">
            <p>Номенкла-</p>
            <p>турный номер</p>
          </td>
          <td colspan="2">
            <p>Количество</p>
          </td>
          <td rowspan="2">
            <p>Цена за единицу, в тенге</p>
          </td>
          <td rowspan="2">
            <p>Сумма с НДС, в тенге</p>
          </td>
          <td rowspan="2">
            <p>Сумма НДС, в тенге</p>
          </td>
        </tr>
        <tr>
          <td>
            <p>подлежит отпуску</p>
          </td>
          <td>
            <p>отпущено</p>
          </td>
        </tr>
        <tr>
          <td>
            <p>1</p>
          </td>
          <td>
            <p>2</p>
          </td>
          <td>
            <p>3</p>
          </td>
          <td>
            <p>4</p>
          </td>
          <td>
            <p>5</p>
          </td>
          <td>
            <p>6</p>
          </td>
          <td>
            <p>7</p>
          </td>
          <td>
            <p>8</p>
          </td>
        </tr>
        <?php 
          $totalAmount = 0;
        ?>
        @foreach($productsList as $key => $product)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $product['title'] }}</td>
            <td></td>
            <td>{{ $product['count'] . $product['unit'] }}</td>
            <td>{{ $product['price'] }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <?php
            $totalAmount += $product['count'] * $product['price'];
          ?>
        @endforeach
        <tr>
          <td colspan="4">
            <p>Итого</p>
          </td>
          <td><br></td>
          <td><br></td>
          <td>
            <p>х</p>
          </td>
          <td><br></td>
          <td><br></td>
        </tr>
      </tbody>
    </table>
    <p></p>
    <p><br></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Всего отпущено количество запасов (прописью)___________ на сумму</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (прописью), в тенге___________________________</p>
    <p></p>
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