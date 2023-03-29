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
    <br>

    <h3 class="text-center">Список должников</h3>

    <table class="table-bordered w-full">
      <thead>
        <tr>
          <th>ФИО</th>
          <th>Телефон/Email</th>
          <th>Номера документов</th>
          <th>Сумма долга, в тенге</th>
          <th>Информация</th>
        </tr>
      </thead>
      <tbody>
        @foreach($debtors as $debtor)
          <?php $debtOrders = json_decode($debtor->debt_orders, true) ?? []; // dd($debtOrders); ?>
          <tr>
            <th scope="row">{{ $debtor->user->name.' '.$debtor->user->username }}</th>
            <td>{{ $debtor->user->tel.' '.$debtor->user->email }}</td>
            <td>
              @foreach($debtOrders[$company->id][$cashbookId] as $docNoLey => $debtOrder)
                №{{ $debtOrder['docNo'] ?? null }},
              @endforeach
            </td>
            <td>{{ $debtor->debt_sum . $currency }}</td>
            <td></td>
          </tr>
        @endforeach
      </tbody>
    </table>
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