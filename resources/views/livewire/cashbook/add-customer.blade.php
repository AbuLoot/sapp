<div>
  <div wire:ignore.self class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Добавить клиента</h5>
          <button type="button" id="closeAddCustomer" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-6">
              <div class="mb-3">
                <label for="name" class="form-label">Имя</label>
                <input wire:model="name" onclick="setFocus(this, 'addCustomerInput-name')" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                <label for="lastname" class="form-label">Фамилия</label>
                <input wire:model="lastname" onclick="setFocus(this, 'addCustomerInput-lastname')" type="text" class="form-control form-control-lg @error('lastname') is-invalid @enderror">
                @error('lastname')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                <label for="tel" class="form-label">Номер телефона</label>
                <input wire:model="tel" onclick="setFocus(this, 'addCustomerInput-tel')" type="tel" class="form-control form-control-lg @error('tel') is-invalid @enderror">
                @error('tel')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input wire:model="email" onclick="setFocus(this, 'addCustomerInput-email')" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Адрес</label>
              <input wire:model="address" onclick="setFocus(this, 'addCustomerInput-address')" type="text" class="form-control form-control-lg @error('address') is-invalid @enderror">
              @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="text-end">
            <button wire:click="save" class="btn btn-success btn-lg">Добавить</button>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
