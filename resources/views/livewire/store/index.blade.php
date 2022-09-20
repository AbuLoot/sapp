<div>
  <div class="py-3 border-bottom mb-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Главная</h4>

      <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
        <input wire:model="search" type="search" class="form-control" placeholder="Поиск..." aria-label="Search">
      </form>

      <ul class="nav col-lg-auto text-end me-lg-2 text-small">
        <li><a href="#" class="nav-link text-primary" data-bs-toggle="modal" data-bs-target="#filter" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Фильтр"><i class="bi bi-funnel-fill"></i></a></li>
        <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Печать"><i class="bi bi-printer-fill"></i></a></li>
        <li>
          @if($deleteMode)
            <a href="#" wire:click="deleteProducts()" class="nav-link position-relative text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Удаление записей">
              <i class="bi bi-x-square-fill"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ count($productsId) }} <span class="visually-hidden">unread messages</span>
              </span>
            </a>
          @else
            <a href="#" wire:click="activateDeleteMode()" class="nav-link position-relative text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Удаление записей"><i class="bi bi-x-square-fill"></i></a>
          @endif
        </li>
        <!-- <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="plus"><i class="bi bi-plus-square-fill"></i></a></li> -->
        <!-- <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="save"><i class="bi bi-save-fill"></i></a></li> -->
      </ul>

      <div class="text-end ms-md-auto ms-lg-0">
        <a href="/{{ $lang }}/storage/add-product" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i> Добавить товар</a>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="container">

    @if(session()->has('message'))
      <div class="toast-container position-fixed bottom-0 end-0 p-4">
        <div class="toast align-items-center text-bg-info border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body text-white">{{ session('message') }}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
    @endif

    <div class="table-responsive">
      <table class="table table-sm table-striped">
        <thead>
          <tr>
            @if($deleteMode)
              <th></th>
            @endif
            <th >Наименование<br> товара</th>
            <th>Штрихкод</th>
            <th>Категория</th>
            <th>Цена закупки</th>
            <th>Цена оптовая</th>
            <th>Цена продажи</th>
            <th>Кол.</th>
            <!-- <th>Ед. измерения</th> -->
            <th class="text-end">Поставщик</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $index => $product)
            <tr>
              @if($deleteMode)
                <td><input type="checkbox" wire:model="productsId" value="{{ $product->id }}" class="form-check-input"></td>
              @endif
              <td><a href="/{{ $lang }}/storage/edit-product/{{ $product->id }}">{{ $product->title }}</a></td>
              <td>
                <?php $barcodes = json_decode($product->barcodes, true) ?? ['']; ?>
                @foreach($barcodes as $barcode)
                  {{ $barcode }}<br>
                @endforeach
              </td>
              <td>{{ $product->category->title }}</td>
              <td>{{ $product->purchase_price }}</td>
              <td>{{ $product->wholesale_price }}</td>
              <td>{{ $product->price }}</td>
              <td>{{ $product->count }}</td>
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

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="filter" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="filterModalLabel">Фильтр</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <div class="mb-3">
              <label for="type">Тип</label><br>
              <div class="form-check">
                <input type="radio" wire:model="type" class="form-check-input" id="product" value="1">
                <label class="form-check-label" for="product">Товар</label>
              </div>
              <div class="form-check">
                <input type="radio" wire:model="type" class="form-check-input" id="service" value="2">
                <label class="form-check-label" for="service">Услуга</label>
              </div>
            </div>

            <div class="mb-3">
              <label for="category_id">Категории</label>
              <select wire:model="category_id" class="form-control @error('category_id') is-invalid @enderror" id="category_id" >
                <option value="">Выберите категорию...</option>
                <?php $traverse = function ($nodes, $prefix = null) use (&$traverse) { ?>
                  <?php foreach ($nodes as $node) : ?>
                    <option value="{{ $node->id }}"> {{ PHP_EOL.$prefix.' '.$node->title }}</option>
                    <?php $traverse($node->children, $prefix.'__'); ?>
                  <?php endforeach; ?>
                <?php }; ?>
                <?php $categories = \App\Models\Category::orderBy('sort_id')->get()->toTree(); ?>
                <?php $traverse($categories); ?>
              </select>
              @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <?php $companies = \App\Models\Company::where('is_supplier', 1)->get(); ?>
              <label for="company_id">Поставщик</label>
              <select wire:model="company_id" class="form-control @error('company_id') is-invalid @enderror" id="company_id">
                <option value="">Выберите поставщика...</option>
                @foreach($companies as $company)
                  <option value="{{ $company->id }}">{{ $company->title }}</option>
                @endforeach
              </select>
              @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

          </div>
          <div class="modal-footer">
            <div class="col d-grid" role="group" aria-label="Basic example">
              <button type="reset" class="btn btn-dark">Сбросить</button>
            </div>
            <div class="col d-grid" role="group" aria-label="Basic example">
              <button wire:click="apply" type="button" class="btn btn-primary">Применить</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@section('scripts')
  <script type="text/javascript">
    function toggleCheckbox(source) {
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
          checkboxes[i].checked = source.checked;
      }
    }
  </script>
@endsection