<div>
  <!-- Modal Add Company -->
  <div wire:ignore.self class="modal fade" id="addCompany" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <form wire:submit.prevent="saveCompany">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Добавить поставщика</h5>
            <button type="button" id="closeAddCompany" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="title" class="form-label">Название компании</label>
              <input type="text" wire:model.defer="company.title" class="form-control @error('company.title') is-invalid @enderror" id="title">
              @error('company.title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="tel" class="form-label">Номер телефона</label>
              <input type="tel" wire:model.defer="company.phones" class="form-control @error('company.phones') is-invalid @enderror" id="tel">
              @error('company.phones')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Адрес</label>
              <input type="text" wire:model.defer="company.legal_address" class="form-control @error('company.legal_address') is-invalid @enderror" id="address">
              @error('company.legal_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-primary text-center"><i class="bi bi-hdd-fill me-2"></i> Сохранить</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
