<div>
  <div class="mb-3">
    <label for="title" class="form-label">Сумма к расходу</label>
    <input wire:model="amount" type="number" class="form-control form-control-lg @error('amount') is-invalid @enderror" id="amount">
    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label for="comment" class="form-label">Комментарий</label>
    <textarea wire:model="comment" class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="2" maxlength="2000"></textarea>
    @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="text-end">
    <button wire:click="debit" class="btn btn-success btn-lg text-center">Оформить</button>
  </div>
</div>
