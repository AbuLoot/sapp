<div>
  <div class="doc" id="doc">
    <br>
    <p class="text-center">Организация {{ $companyName }}</p>
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
          <td>№{{ $outgoingOrder->doc_no }}</td>
          <td>{{ $outgoingOrder->created_at }}</td>
        </tr>
      </tbody>
    </table>
    <br>

    <h3 class="text-center">Расходной кассовый ордер</h3>

    <table class="table-bordered w-full">
      <tbody>
        <tr>
          <td>Дебет</td>
          <td rowspan="2">Кредит</td>
          <td rowspan="2">Сумма, в тенге</td>
          <td rowspan="2">Код целевого назначения</td>
        </tr>
        <tr>
          <td>корреспондирующий счет</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td>{{ $outgoingOrder->sum . $currency }}</td>
          <td></td>
        </tr>
      </tbody>
    </table>
    <br><br>
    <p>Тип операции: {{ __('operation-codes.'.$outgoingOrder->operation_code) }}</p>
    <p>Выдать: {{ $customerName }}</p>
    <p>Основание: {{ $outgoingOrder->comment }}</p>
    <p>________________________________________________________________________________</p>
    <p>Прилагаемые документы___________________________________________________________</p>
    <p>________________________________________________________________________________</p>
    <p>Сумма __________________________________________________________________________</p>
    <p>прописью </p>
    <p>______________________________________________________________________________тенге</p>
    <p>Руководитель_________________/_________________/____________________________________</p>
    <p>должность           подпись               расшифровка подписи</p>
    <p>Главный бухгалтер или уполномоченное лицо _______________/__________________________</p>
    <p>подпись        расшифровка подписи</p>
    <p>Получил {{ $outgoingOrder->created_at }} / _______________________</p>
    <p>подпись        фамилия, имя, отчество</p>
    <p>по___________________________________________________________________________________</p>
    <p>наименование, номер, дата и место выдачи документа удостоверяющего личность</p>
    <p>получателя </p>
    <p>Выдал кассир {{ $cashierName }} ____________/___________________________</p>
    <p>подпись расшифровка подписи</p>
    <br>
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
      padding: 10px 37px;
    }
  </style>
  <div class="functions row">
    <div class="col d-grid gap-2">
      <button type="button" onclick="printPage()" class="btn btn-success rounded-0 btn-lg">Печать документа</button>
    </div>
    <div class="col d-grid gap-2">
      <a href="{{ $prevPage }}" class="btn btn-primary rounded-0 btn-lg">Назад</a>
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