<div>

  <!-- Modal List Of Debtors -->
  <div class="modal fade" id="listOfDebtors" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Детали накладной</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <table class="table">
            <thead>
              <tr>
                <th scope="col">Имя</th>
                <th scope="col">Сумма долга</th>
                <th scope="col">Номера чеков</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($debtors as $debtor)
                <?php
                  $deptData = json_encode($debtor->dept_data, true);
                  print_r($deptData);
                ?>
                <tr>
                  <th scope="row">{{ $debtor->user->name . $debtor->user->username }}</th>
                  <td>{{ $debtor->debt_sum . $company->currency->symbol }}</td>
                  <td>{{ $debtor->user->name }}</td>
                  <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Погасить</button></td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <nav aria-label="Page navigation example">
            {{ $debtors->links() }}
          </nav>

          <div class="d-flex">
            <h5>Общая сумма долгов</h5>
            <h5 class="ms-auto">{{ number_format($debtors->sum('debt_sum'), 0, '.', ',') . $company->currency->code }}</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark btn-lg text-end"><i class="be bi-printer-fill me-2"></i> Печать</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Repayment Of Dept -->
  <div class="modal fade" id="repaymentOfDept" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Погашение долга</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="title" class="form-label">Сумма погашение долга</label>
              <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" required>
            </div>
          </form>

          <div class="d-flex">
            <h5>Общая сумма долгов</h5>
            <h5 class="ms-auto">100 000 000KZT</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-lg text-center">Погасить</button>
        </div>
      </div>
    </div>
  </div>

</div>
