<div style="max-width: 300px;">
  <br>
  <p class="text-center">Организация (индивидуальный предприниматель)</p>
  <h3 class="text-center">Квитанция к приходному кассовому ордеру</h3>
  <p class="text-center">№{{ $docNo }}</p>
  <p>Принято от {{ $name }}</p>
  <hr>
  <table>
    <tbody>
      <tr>
        <td>Product title</td>
        <td>10шт.</td>
        <td>100000tg</td>
      </tr>
      <tr>
        <td>Product title</td>
        <td>10шт.</td>
        <td>100000tg</td>
      </tr>
      <tr>
        <td>Product title</td>
        <td>10шт.</td>
        <td>100000tg</td>
      </tr>
      <tr>
        <td>Product title</td>
        <td>10шт.</td>
        <td>100000tg</td>
      </tr>

      <tr>
        <td>Сумма без скидки</td>
        <td>300000tg</td>
      </tr>
      <tr>
        <td>Скидка</td>
        <td>10%</td>
      </tr>
      <tr>
        <td>Итоговая сумма</td>
        <td>270000tg</td>
      </tr>
    </tbody>
  </table>

  <hr>
  <p>Метод оплаты: {{ $paymentType }}</p>
  <p>Дата: {{ $date }}</p>
  <p>Кассир: {{ $name }}</p>
</div>

<style type="text/css">
  table {
    border-collapse: collapse;
  }
  .table-bordered,
  .table-bordered tr,
  .table-bordered tr td {
    border: 1px solid;
  }
  .text-center {
    text-align: center;
  }
  p {
    display: flex;
    align-items: center;
    /*font-size: 1.5rem;*/
  }

  /*p:after {
    content: "";
    flex: 1;
    border: 1px solid #c4c4c4;
    margin-inline-start: 0.5rem;
  }*/
</style>