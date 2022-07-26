<div>

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
          <div wire:click="addToCart({{ $fastProduct->id }})" class="card-body d-block bg-white opacity-75 px-2 py-1" style="margin-top: -82px; cursor: pointer;">
            <h6 class="card-title">{{ $fastProduct->title }}</h6>
            <h6 class="card-subtitle mb-2">{{ $fastProduct->price . $currency }}</h6>
            <a wire:click="addToCart({{ $fastProduct->id }})" href="#" class="card-link">В корзину</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>

</div>
