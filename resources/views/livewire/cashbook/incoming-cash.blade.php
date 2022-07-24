<div>
  <div class="mb-3">
    <label for="amount" class="form-label">Сумма к внесению</label>
    <input wire:model="amount" type="number" class="form-control form-control-lg @error('amount') is-invalid @enderror" id="amount" name="amount">
    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label for="comment">Комментарий</label>
    <textarea wire:model="comment" class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="2" maxlength="2000"></textarea>
    @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="text-end">
    <button wire:click="test" class="btn btn-success btn-lg">Test</button>
    <button wire:click="credit" class="btn btn-success btn-lg">Внести</button>
  </div>
</div>
