<div>
  <div wire:ignore.self class="modal fade" id="fastProducts" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Быстрые товары</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="min-height:270px;">

          <form class="mb-3" style="position: relative;">
            <input wire:model="search" type="search" class="form-control form-control-lg" placeholder="Поиск товаров..." aria-label="Search" minlength="2" required>
            @if($products)
              <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
                <ul class="list-unstyled mb-0">
                  @forelse($products as $product)
                    <li>
                      <a wire:click="toggleFastMode({{ $product->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $product->title }}</a>
                    </li>
                  @empty
                    <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
                  @endforelse
                </ul>
              </div>
            @endif
          </form>

          <div class="row">
            <?php $currency = $company->currency->symbol; ?>
            @foreach($fastProducts as $fastProduct)
              <div class="col-lg-3 col-md-4 mb-3">
                <div class="card position-relative">
                  <a wire:click="toggleFastMode({{ $fastProduct->id }})" href="#" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
                    <i class="h6 bi bi-x"></i> <span class="visually-hidden">unread messages</span>
                  </a>
                  <img src="/img/products/{{ $fastProduct->path.'/'.$fastProduct->image }}" class="img-fluid card-img-top mx-auto" style="width: auto; height: 150px;">
                  <div wire:click="addToCart({{ $fastProduct->id }})" data-bs-dismiss="modal" class="card-body rounded-pill d-block bg-white opacity-75 px-2 pt-2 pb-1" style="margin-top: -82px; cursor: pointer; min-height: 85px;">
                    <h6 class="card-title mt-2">
                      <a href="#" class="card-link">{{ $fastProduct->title }}</a>
                    </h6>
                    <h6 class="card-subtitle">{{ $fastProduct->price . $currency }}</h6>
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
