<div>
  <!-- Modal Deffered Checks -->
  <div class="modal fade" id="defferedChecks" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Отложенные чеки</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="min-height:270px;">

          <form class="mb-3" style="position: relative;">
            <input wire:model="search" type="search" class="form-control form-control-lg" placeholder="Поиск чеков..." aria-label="Search" minlength="2" required>
            @if($checks)
              <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
                <ul class="list-unstyled mb-0">
                  @forelse($checks as $check)
                    <li>
                      <a wire:click="toggleFastMode({{ $check->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $check->title }}</a>
                    </li>
                  @empty
                    <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
                  @endforelse
                </ul>
              </div>
            @endif
          </form>

          <div class="row">
            @forelse($deferredChecks as $index => $check)
              <div class="col-3 mb-3 position-relative">
                <a wire:click="removeFromDeferred('{{ $index }}')" href="#" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
                  <i class="h6 bi bi-x"></i> <span class="visually-hidden">unread messages</span>
                </a>
                <div wire:click="returnCheck('{{ $index }}')" class="card bg-dark text-white" style="height: 100px; cursor: pointer;">
                  <div class="card-img-overlay">
                    <h6 class="card-title">{{ $index }}</h6>
                    <p class="card-text">{{ $check['sumDiscounted'] }}</p>
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
