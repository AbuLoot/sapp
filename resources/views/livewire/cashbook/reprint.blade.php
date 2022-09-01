<div>
  <div wire:ignore.self class="modal fade" id="reprint" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Журнал чеков</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="mb-3">
            <input wire:model="search" type="search" class="form-control form-control-lg" placeholder="Поиск чеков..." aria-label="Search" minlength="2" required>
          </form>

          <div class="row">
            @forelse($incomingOrders as $check)
              <div class="col-3 mb-3 position-relative">
                <div>
                  <a href="/{{ $lang }}/cashdesk/cashdocprint/check/{{ $check->id }}" class="card bg-dark text-white" style="height: 100px; cursor: pointer;">
                    <div class="card-img-overlay">
                      <h6 class="card-title">№{{ $check->doc_no }}</h6>
                      <small class="card-text">{{ $check->sum . $company->currency->symbol }}</small>
                    </div>
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
