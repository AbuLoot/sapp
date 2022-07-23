<div>

  <div class="modal fade" id="incomingCash" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <form wire:submit.prevent="credit">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Внести деньги в кассу</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="amount" class="form-label">Сумма к внесению</label>
              <input wire:model.defer="amount" type="text" class="form-control form-control-lg @error('amount') is-invalid @enderror" id="amount" name="amount" minlength="2" required>
              @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="comment">Комментарий</label>
              <textarea wire:model.defer="comment" class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="2" maxlength="2000" minlength="2" required></textarea>
              @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success btn-lg text-center">Внести</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if($errors->any())
    <script type="text/javascript">
      var myModal = new bootstrap.Modal(document.getElementById("incomingCash"), {});
      myModal.show();
    </script>
  @endif

  @if(!$errors->any())
    <script type="text/javascript">
      const incomingCash = document.getElementById("incomingCash");
      incomingCash.hide();
    </script>
  @endif
</div>
