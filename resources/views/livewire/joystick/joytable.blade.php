<div>

  <div class="table-responsive table-products">
    <table class="table data table-striped table-condensed table-hover">
      <thead>
        <tr class="active">
          <td class="hidden-xs">Функции</td>
          <td>Картинка</td>
          <td>Название</td>
          <td>Цена</td>
          <td>Количество</td>
          <td>Категории</td>
          <td>Артикул</td>
          <td>Статус</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($productsItems as $index => $product)
          <tr>
            <td class="text-nowrap hidden-xs">
              <form class="btn-delete" method="POST" action="{{ route('products.destroy', [$lang, $product->id]) }}" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-link btn-xs" onclick="return confirm('Удалить запись?')"><i class="material-icons md-18">clear</i></button>
              </form>
              @if($product_index !== $index)
                <div wire:click.prevent="editProduct({{ $index }})" class="btn btn-link btn-xs"><i class="material-icons md-18">mode_edit</i></div>
              @else
                <div wire:click.prevent="saveProduct({{ $index }})" class="btn btn-link btn-xs"><i class="material-icons md-18">save</i></div>
              @endif
            </td>
            <td>
              @if($product_index !== $index)
                <img src="/img/products/{{ $product->path.'/'.$product->image }}" class="img-responsive" style="width:80px;height:auto;">
              @else
                <form wire:submit.prevent="uploadImages({{ $index }})">
                  <div class="form-group">
                    <input type="file" wire:model.defer="images" accept="image/*" style="max-width:100px;" multiple>
                  </div>
                  <button type="submit">Сохранить</button>
                  @error('images')<div class="text-danger">{{ $message }}</div>@enderror
                </form>
              @endif
            </td>
            <td>
              @if($product_index !== $index)
                {{ $product->title }}
              @else
                <textarea wire:model.defer="products.{{ $index }}.title" class="form-control"></textarea>
                @error('products.'.$index.'.title')<div class="text-danger">{{ $message }}</div>@enderror
              @endif
            </td>
            <td>
              @if($product_index !== $index)
                {{ $product->price }}
              @else
                <input type="text" wire:model.defer="products.{{ $index }}.price" class="form-control">
                @error('products.'.$index.'.price')<div class="text-danger">{{ $message }}</div>@enderror
              @endif
            </td>
            <td>
              @if($product_index !== $index)
                {{ $product->count }}
              @else
                <input type="text" wire:model.defer="products.{{ $index }}.count" class="form-control">
                @error('products.'.$index.'.count')<div class="text-danger">{{ $message }}</div>@enderror
              @endif
            </td>
            <td class="text-nowrap">
              @if($product_index !== $index)
                {{ $product->category->title }}
              @else
                <select wire:model.defer="products.{{ $index }}.category_id" class="form-control">
                  <?php $traverse = function ($nodes, $prefix = null) use (&$traverse, $product) { ?>
                    <?php foreach ($nodes as $node) : ?>
                      <option value="{{ $node['id'] }}" <?php if ($product->category_id == $node['id']) echo "selected"; ?>>{{ PHP_EOL.$prefix.' '.$node['title'] }}</option>
                      <?php $traverse($node['children'], $prefix.'___'); ?>
                    <?php endforeach; ?>
                  <?php }; ?>
                  <?php $traverse($categories); ?>
                </select>
                @error('products.'.$index.'.categories')<div class="text-danger">{{ $message }}</div>@enderror
              @endif
            </td>
            <td>{{ $product->barcode }}</td>
            <td class="text-{{ trans('statuses.product.'.$product->status.'.style') }}">{{ trans('statuses.product.'.$product->status.'.title') }}</td>
            <th class="fix-col">
              @if($product_index !== $index)
                <div wire:click.prevent="editProduct({{ $index }})" class="btn btn-link btn-xs"><i class="material-icons md-18">mode_edit</i></div>
              @else
                <div wire:click.prevent="saveProduct({{ $index }})" class="btn btn-link btn-xs"><i class="material-icons md-18">save</i></div>
              @endif
              <form class="btn-delete btn-fix-col" method="POST" action="{{ route('products.destroy', [$lang, $product->id]) }}" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-link btn-xs" onclick="return confirm('Удалить запись?')"><i class="material-icons md-18">clear</i></button>
              </form>
            </th>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div>
    {{ $productsItems->links() }}
  </div>
  <script type="text/javascript">
    /*$(document).ready(function () {
      $('#sidebarCollapse').on('click', function () {
        $('.sidebar').toggleClass('active');
      });
      $('.main').on('click', function () {
        $('.sidebar').removeClass('active');
      });
    });*/
  </script>
</div>
