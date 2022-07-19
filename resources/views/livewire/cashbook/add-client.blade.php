<div>

  <!-- Modal Add Client -->
  <div class="modal fade" id="addClient" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Добавить клиента</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-6">
                <div class="mb-3">
                  <label for="name" class="form-label">Имя</label>
                  <input type="text" class="form-control form-control-lg" id="name" name="name" minlength="2" value="" required>
                </div>
              </div>
              <div class="col-6">
                <div class="mb-3">
                  <label for="lastname" class="form-label">Фамилия</label>
                  <input type="text" class="form-control form-control-lg" id="lastname" name="lastname" minlength="2" value="" required>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="tel" class="form-label">Номер телефона</label>
              <input type="tel" class="form-control form-control-lg" id="tel" name="tel" minlength="2" value="" required>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Адрес</label>
              <input type="text" class="form-control form-control-lg" id="address" name="address" minlength="2" value="" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-lg text-center">Добавить</button>
        </div>
      </div>
    </div>
  </div>
</div>
