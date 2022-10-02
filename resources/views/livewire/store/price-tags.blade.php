<div>
  <div wire:ignore.self class="modal fade" id="priceTags" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <form wire:submit.prevent="saveCategory">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Добавить категорию</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex gap-5 justify-content-center">
              <div class="list-group mx-0 w-auto">
                <label class="list-group-item d-flex gap-2">
                  <input class="form-check-input flex-shrink-0" type="checkbox" value="" checked="">
                  <span>
                    First checkbox
                    <small class="d-block text-muted">With support text underneath to add more detail</small>
                  </span>
                </label>
                <label class="list-group-item d-flex gap-2">
                  <input class="form-check-input flex-shrink-0" type="checkbox" value="">
                  <span>
                    Second checkbox
                    <small class="d-block text-muted">Some other text goes here</small>
                  </span>
                </label>
                <label class="list-group-item d-flex gap-2">
                  <input class="form-check-input flex-shrink-0" type="checkbox" value="">
                  <span>
                    Third checkbox
                    <small class="d-block text-muted">And we end with another snippet of text</small>
                  </span>
                </label>
              </div>

              <div class="list-group mx-0 w-auto">
                <label class="list-group-item d-flex gap-2">
                  <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios1" value="" checked="">
                  <span>
                    First radio
                    <small class="d-block text-muted">With support text underneath to add more detail</small>
                  </span>
                </label>
                <label class="list-group-item d-flex gap-2">
                  <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios2" value="">
                  <span>
                    Second radio
                    <small class="d-block text-muted">Some other text goes here</small>
                  </span>
                </label>
                <label class="list-group-item d-flex gap-2">
                  <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios3" value="">
                  <span>
                    Third radio
                    <small class="d-block text-muted">And we end with another snippet of text</small>
                  </span>
                </label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary text-center"><i class="bi bi-hdd-fill me-2"></i> Сохранить</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
