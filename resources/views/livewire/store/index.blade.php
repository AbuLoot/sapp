<div>
  <table class="table table-sm table-striped small">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Наименование</th>
        <th scope="col">Штрихкод</th>
        <th scope="col">Категория</th>
        <th scope="col">Цена закупки</th>
        <th scope="col">Цена оптовая</th>
        <th scope="col">Цена продажи</th>
        <th scope="col">Кол.</th>
        <!-- <th scope="col">Ед. измерения</th> -->
        <th scope="col">Поставщик</th>
      </tr>
    </thead>
    <tbody>
      @forelse($products as $index => $product)
        <tr>
          <th>{{ $index }}</th>
          <td>{{ $product->title }}</td>
          <td>
            @foreach(json_decode($product->barcodes, true) as $barcode)
              {{ $barcode }}<br>
            @endforeach
          </td>
          <td>{{ $product->category->title }}</td>
          <td>{{ $product->purchase_price }}</td>
          <td>{{ $product->wholesale_price }}</td>
          <td>{{ $product->price }}</td>
          <td>{{ $product->count }}</td>
          <!-- <td></td> -->
          <td>{{ $product->company_id }}</td>
        </tr>
      @empty
        <p>No docs</p>
      @endforelse
    </tbody>
  </table>

  {{ $products->links() }}
</div>
