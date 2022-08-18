<div>

  <form class="mb-3">
    <input wire:model="search" type="search" class="form-control form-control-lg" placeholder="Поиск чеков..." aria-label="Search" minlength="2" required>
  </form>

  <div class="row">
    @forelse($incomingChecks as $index => $check)
      <div class="col-3 mb-3 position-relative">
        <div wire:click="getCheck('{{ $index }}')" class="card bg-dark text-white" style="height: 100px; cursor: pointer;">
          <div class="card-img-overlay">
            <h6 class="card-title">№{{ $check->doc_no }}</h6>
            <p class="card-text">{{ $check['sumDiscounted'] }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

</div>
