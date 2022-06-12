<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">

      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Новый приход</h4>

        <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto" style="position: relative;">
          <input wire:model="search" type="search" list="datalistOptions" class="form-control" placeholder="Поиск..." aria-label="Search">
          @if($products)
            <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
              <ul class="list-unstyled mb-0">
                @forelse($products as $product)
                  <li>
                    <a wire:click="addToIncome({{ $product->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">{{ $product->title }}</a>
                  </li>
                @empty
                  <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
                @endforelse
              </ul>
            </div>
          @endif
        </form>

      <ul class="nav col-lg-auto text-end me-lg-2 text-small">
        <li><a href="#" class="nav-link text-primary"><i class="bi bi-journals"></i></a></li>
        <li><a href="#" class="nav-link text-primary"><i class="bi bi-file-text-fill"></i></a></li>
        <li><a href="#" class="nav-link text-primary"><i class="bi bi-file-earmark-plus-fill"></i></a></li>
        <li><a href="#" class="nav-link text-primary"><i class="bi bi-file-earmark-text-fill"></i></a></li>
      </ul>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="/{{ $lang }}/store/add-product" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i> Добавить товар</a>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="container">
    <div class="table-responsive">
      <table class="table table-sm align-middle table-striped">
        <thead>
          <tr>
            <th scope="col">Наименование<br> товара</th>
            <th scope="col">Штрихкод</th>
            <th scope="col">Категория</th>
            <th scope="col">Цена закупки</th>
            <th scope="col">Цена оптовая</th>
            <th scope="col">Цена продажи</th>
            <th scope="col">Кол.</th>
            <!-- <th scope="col">Ед. измерения</th> -->
            <th scope="col">Поставщик</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($incomeProducts as $index => $incomeProduct)
            <tr>
              <td><a href="/{{ $lang }}/store/edit-incomeProduct/{{ $incomeProduct->id }}">{{ $incomeProduct->title }}</a></td>
              <td>
                @foreach(json_decode($incomeProduct->barcodes, true) as $barcode)
                  {{ $barcode }}<br>
                @endforeach
              </td>
              <td>{{ $incomeProduct->category->title }}</td>
              <td>{{ $incomeProduct->purchase_price }}</td>
              <td>{{ $incomeProduct->wholesale_price }}</td>
              <td>{{ $incomeProduct->price }}</td>
              <td class="col-2">
                <input type="text" wire:model="incomeProducts.{{ $incomeProduct->id }}.count" class="form-control form-control-sm px-1">
                @error('products.'.$incomeProduct->id.'.count')<div class="text-danger">{{ $message }}</div>@enderror
              </td>
              <!-- <td></td> -->
              <td>{{ $incomeProduct->company->title }}</td>
              <td class="text-end"><a wire:click="deleteFromIncome({{ $incomeProduct->id }})" href="#" class="fs-5"><i class="bi bi-file-x-fill"></i></a></td>
            </tr>
          @empty
            <tr>
              <td colspan="9">No data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($incomeProducts)
      <a href="#" wire:click="makeDoc" class="btn btn-primary"><i class="bi bi-file-earmark-ruled-fill me-2"></i> Провести документ</a>
    @endif
  </div>
</div>
