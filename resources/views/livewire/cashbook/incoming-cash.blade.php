<div>
  <div wire:ignore.self class="modal fade" id="incomingCash" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable cashOperation">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Внести деньги в кассу</h5>
          <button type="button" id="closeIncomingCash" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="amount" class="form-label">Сумма к внесению</label>
            <div class="input-group">
              <input wire:model="amount" onclick="setFocus(this, 'incomingCashInput-amount')" type="number" class="form-control form-control-lg @error('amount') is-invalid @enderror">
              <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
            </div>
            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="comment" class="form-label">Комментарий</label>
            <textarea wire:model="comment" onclick="setFocus(this, 'incomingCashInput-comment')" class="form-control @error('comment') is-invalid @enderror" rows="2" maxlength="2000"></textarea>
            @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="text-end">
            <button wire:click="credit" class="btn btn-success btn-lg">Внести</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
