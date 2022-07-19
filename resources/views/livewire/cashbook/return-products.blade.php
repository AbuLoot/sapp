<div>

  <!-- Modal Return Products -->
  <div class="modal fade" id="returnProducts" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Детали накладной</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form>
            <div class="mb-3">
              <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" required placeholder="Поиск...">
            </div>
          </form>

          <h5>Чек №1</h5>

          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Возврат</button></td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Возврат</button></td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Возврат</button></td>
              </tr>
            </tbody>
          </table>

          <div class="d-flex">
            <h5>Общая сумма долгов</h5>
            <h5 class="ms-auto">100 000 000KZT</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-lg text-end"><i class="be bi-hdd-fill me-2"></i> Сохранить</button>
          <button type="button" class="btn btn-dark btn-lg text-end"><i class="be bi-printer-fill me-2"></i> Печать</button>
        </div>
      </div>
    </div>
  </div>

</div>
