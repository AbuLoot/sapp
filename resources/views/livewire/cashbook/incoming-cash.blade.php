<div>
  <!-- Modal Incoming Cash -->
  <div class="modal fade" id="incomingCash" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content  bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Внести деньги в кассу</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="title" class="form-label">Сумма к внесению</label>
              <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" required>
            </div>
            <div class="mb-3">
              <label for="comment">Комментарий</label>
              <textarea class="form-control" name="comment" rows="2" maxlength="2000"></textarea>
            </div>
          </form>

          <div class="d-flex">
            <h5>Сумма</h5>
            <h5 class="ms-auto">100 000 000KZT</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-lg text-center">Внести</button>
        </div>
      </div>
    </div>
  </div>
</div>
