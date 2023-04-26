<div>
  <div wire:ignore.self class="modal fade" id="confirmStorno" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable cashOperation">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Подтвердите действие сторно</h5>
          <button type="button" id="closeConfirmStorno" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="code" class="form-label">Введите код</label>
            <div class="input-group">
              <input wire:model="code" onclick="setFocus(this, 'confirmStornoInput-code')" type="password" class="form-control form-control-lg @error('code') is-invalid @enderror">
              <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
            </div>
            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="text-end">
            <button wire:click="confirm" class="btn btn-success btn-lg">Подтвердить</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    window.addEventListener('show-storno-modal', event => {
      var modalStorno = new bootstrap.Modal(document.getElementById("confirmStorno"), {});
      modalStorno.show();
    });

    window.addEventListener('hide-storno-modal', event => {
      const btnCloseModal = document.getElementById('closeConfirmStorno')
      btnCloseModal.click()
    });
  </script>
</div>
