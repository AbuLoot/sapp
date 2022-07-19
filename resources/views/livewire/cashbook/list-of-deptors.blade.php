<div>

  <!-- Modal List Of Deptors -->
  <div class="modal fade" id="listOfDeptors" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
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
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Погасить</button></td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Погасить</button></td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td class="text-end"><button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#repaymentOfDept">Погасить</button></td>
              </tr>
            </tbody>
          </table>

          <nav aria-label="Page navigation example">
            <ul class="pagination pagination-lg">
              <li class="page-item"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav>

          <div class="d-flex">
            <h5>Общая сумма долгов</h5>
            <h5 class="ms-auto">100 000 000KZT</h5>
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
