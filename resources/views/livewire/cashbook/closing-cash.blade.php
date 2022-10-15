<div>
  <div wire:ignore.self class="modal fade" id="closingCash" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content bg-light py-2">
        <div class="modal-body">
          <div class="text-center">
            <div class="display-1">
              <i class="bi bi-x-octagon-fill"></i>
            </div>
            <h4>Вы действительно хотите закрыть смену?</h4>
            <button type="button" class="btn btn-outline-dark btn-lg me-1 mb-2" data-bs-dismiss="modal">Отмена</button>
            <button wire:click="closeTheCash" type="button" class="btn btn-dark btn-lg me-1 mb-2">Закрыть смену</button>
            <br>
            <br>
            <p>Количество банкнот и монет номиналами:</p>

            <div>
              <button wire:click="setNominal(20000)" type="button" class="btn {{ $attr[20000]['color'] }} btn-lg me-1 mb-2">20000〒{{ $attr[20000]['('].$nominals[20000].$attr[20000][')'] }}</button>
              <button wire:click="setNominal(10000)" type="button" class="btn {{ $attr[10000]['color'] }} btn-lg me-1 mb-2">10000〒{{ $attr[10000]['('].$nominals[10000].$attr[10000][')'] }}</button>
              <button wire:click="setNominal(5000)" type="button" class="btn {{ $attr[5000]['color'] }} btn-lg me-1 mb-2">5000〒{{ $attr[5000]['('].$nominals[5000].$attr[5000][')'] }}</button>
              <button wire:click="setNominal(2000)" type="button" class="btn {{ $attr[2000]['color'] }} btn-lg me-1 mb-2">2000〒{{ $attr[2000]['('].$nominals[2000].$attr[2000][')'] }}</button>
              <button wire:click="setNominal(1000)" type="button" class="btn {{ $attr[1000]['color'] }} btn-lg me-1 mb-2">1000〒{{ $attr[1000]['('].$nominals[1000].$attr[1000][')'] }}</button>
              <button wire:click="setNominal(500)" type="button" class="btn {{ $attr[500]['color'] }} btn-lg me-1 mb-2">500〒{{ $attr[500]['('].$nominals[500].$attr[500][')'] }}</button>
              <br>
              <button wire:click="setNominal(200)" type="button" class="btn {{ $attr[200]['color'] }} btn-lg me-1 mb-2">200〒{{ $attr[200]['('].$nominals[200].$attr[200][')'] }}</button>
              <button wire:click="setNominal(100)" type="button" class="btn {{ $attr[100]['color'] }} btn-lg me-1 mb-2">100〒{{ $attr[100]['('].$nominals[100].$attr[100][')'] }}</button>
              <button wire:click="setNominal(50)" type="button" class="btn {{ $attr[50]['color'] }} btn-lg me-1 mb-2">50〒{{ $attr[50]['('].$nominals[50].$attr[50][')'] }}</button>
              <button wire:click="setNominal(20)" type="button" class="btn {{ $attr[20]['color'] }} btn-lg me-1 mb-2">20〒{{ $attr[20]['('].$nominals[20].$attr[20][')'] }}</button>
              <button wire:click="setNominal(10)" type="button" class="btn {{ $attr[10]['color'] }} btn-lg me-1 mb-2">10〒{{ $attr[10]['('].$nominals[10].$attr[10][')'] }}</button>
              <button wire:click="setNominal(5)" type="button" class="btn {{ $attr[5]['color'] }} btn-lg me-1 mb-2">5〒{{ $attr[5]['('].$nominals[5].$attr[5][')'] }}</button>
            </div>

            @if($key)
              <form wire:submit.prevent="setNumber">
                <div class="row mt-2 gx-2">
                  <div class="col-6 col-lg-4 offset-lg-2 mb-3">
                    <input wire:model="number" onclick="setFocus(this, 'closingCashInput-number')" type="number" class="form-control form-control-lg @error('error') is-invalid @enderror" placeholder="Количество номиналов">
                  </div>
                  <div class="col-6 col-lg-4 mb-3">
                    <div class="d-grid" role="group" aria-label="Basic example">
                      <button type="submit" class="btn btn-success btn-lg">Сохранить</button>
                    </div>
                  </div>
                </div>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
