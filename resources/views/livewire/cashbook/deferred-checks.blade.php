<div>
  <div wire:ignore class="modal fade" id="defferedChecks" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable cashOperation">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Отложенные чеки</h5>
          <button type="button" id="closeDefferedChecks" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="min-height:270px;">

          <div class="row">
            @foreach($deferredChecks as $index => $check)
              <div class="col-3 mb-3">
                <div class="position-relative">
                  <a wire:click="removeFromDeferred('{{ $index }}')" href="#" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark" style="z-index:1010;">
                    <i class="h6 bi bi-x"></i> <span class="visually-hidden">unread messages</span>
                  </a>
                  <div wire:click="returnCheck('{{ $index }}')" class="card bg-dark text-white" style="height: 100px; cursor: pointer;">
                    <div class="card-img-overlay">
                      <h6 class="card-title">{{ $index }}</h6>
                      <p class="card-text">{{ $check['sumDiscounted'] }}</p>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
