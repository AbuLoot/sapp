<div>

  <div wire:ignore class="modal fade" id="listOfDebtors" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Список должников</h5>
          <button type="button" id="closeListOfDebtors" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                      №{{ $debtOrder['docNo'] ?? null }},
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
          <button type="button" class="btn btn-dark btn-lg text-end" disabled><i class="be bi-printer-fill me-2"></i> Печать</button>
        </div>
      </div>
    </div>
  </div>

  @if(session()->has('message'))
    <div class="toast-container position-fixed -bottom-0 end-0 p-4">
      <div class="toast align-items-center text-bg-info border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body text-white">{{ session('message') }}</div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
  @endif

  <!-- Modal Repayment Of Dept -->
  <div wire:ignore class="modal fade" id="repaymentOfDebt" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable cashOperation">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Погашение долга</h5>
          <button type="button" id="closeRepaymentOfDebt" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form wire:submit.prevent="repay">
            <label for="title" class="form-label">Сумма погашение долга</label>
            <div class="input-group mb-3">
              <select wire:model="paymentTypeId" class="form-select w-25">
                @foreach($paymentTypes as $paymentType)
                  <option value="{{ $paymentType->id }}">{{ $paymentType->title }}</option>
                @endforeach
              </select>
              <input wire:model="repaymentAmount" onclick="setFocus(this, 'listOfDebtorsInput-repaymentAmount')" type="number" class="form-control form-control-lg w-50 @error('message') is-invalid @enderror" required>
              <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-success btn-lg">Погасить</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    window.addEventListener('show-modal', event => {
      var modalRepayment = new bootstrap.Modal(document.getElementById("repaymentOfDebt"), {});
      modalRepayment.show();
    })
  </script>
</div>
