<div>

  <!-- Modal List Of Debtors -->
  <div wire:ignore.self class="modal fade" id="listOfDebtors" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
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
              <?php $currency = $company->currency->symbol; ?>
              @foreach($debtors as $debtor)
                <?php $debtOrders = json_decode($debtor->debt_orders, true) ?? []; ?>
                <tr>
                  <th scope="row">{{ $debtor->user->name . $debtor->user->username }}</th>
                  <td>{{ $debtor->debt_sum . $currency }}</td>
                  <td>
                    @foreach($debtOrders as $debtOrder)
                      №{{ $debtOrder['docNo'] }},
                    @endforeach
                  </td>
                  <td class="text-end">
                    <button wire:click="repayFor({{ $debtor->id }})" class="btn btn-outline-success" data-bs-dismiss="modal">Погасить</button>
                  </td>
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
  <div wire:ignore.self class="modal fade" id="repaymentOfDept" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Погашение долга</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form wire:submit.prevent="repay">
            <!-- <input id="debtor" type="hidden"> -->
            <div class="mb-3">
              <label for="title" class="form-label">Сумма погашение долга</label>
              <input wire:model="repaymentAmount" type="number" class="form-control form-control-lg @error('message') is-invalid @enderror" id="title" name="title" minlength="2" required>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-success btn-lg">Погасить</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>

@section('scripts')
  <script>
    window.addEventListener('toggle-modal', event => {
      // const listOfDebtors = new bootstrap.Modal('listOfDebtors')
      // listOfDebtors.hide() // it is asynchronous
      // const modalList = new bootstrap.Modal("#listOfDebtors")
      // modalList.hide()

      var modalRepayment = new bootstrap.Modal(document.getElementById("repaymentOfDept"), {});
      // document.getElementById('debtor').value = event.detail.debtorId;
      modalRepayment.show();

    })
  </script>
@endsection