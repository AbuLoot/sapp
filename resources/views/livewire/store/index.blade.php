<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Главная</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
        <input wire:model="search" type="search" class="form-control" placeholder="Поиск..." aria-label="Search">
      </form>

      <ul class="nav col-lg-auto text-end me-lg-2 text-small">
        <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="funnel"><i class="bi bi-funnel-fill"></i></a></li>
        <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="printer"><i class="bi bi-printer-fill"></i></a></li>
        <li><a href="#" wire:click.prevent="deleteProducts()" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="x"><i class="bi bi-x-square-fill"></i></a></li>
        <!-- <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="plus"><i class="bi bi-plus-square-fill"></i></a></li> -->
        <!-- <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="save"><i class="bi bi-save-fill"></i></a></li> -->
      </ul>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="/{{ $lang }}/store/add-product" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i> Добавить товар</a>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="container">
    <div class="table-responsive">
      <table class="table table-sm table-striped">
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
            <th class="text-end" scope="col">Поставщик</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $index => $product)
            <tr>
              <td><a href="/{{ $lang ?? '' }}/store/edit-product/{{ $product->id }}">{{ $product->title }}</a></td>
              <td>
                @foreach(json_decode($product->barcodes, true) as $barcode)
                  {{ $barcode }}<br>
                @endforeach
              </td>
              <td>{{ $product->category->title }}</td>
              <td>{{ $product->purchase_price }}</td>
              <td>{{ $product->wholesale_price }}</td>
              <td>{{ $product->price }}</td>
              <td class="text-nowrap text-end">
                {{ $product->count }} <a href="#"><i class="bi bi-pencil-square text-primary"></i></a>
              </td>
              <!-- <td></td> -->
              <td class="text-end">{{ $product->company->title }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="8">No docs</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $products->links() }}
  </div>
</div>
