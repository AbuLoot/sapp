<div>

  <!-- Modal Return Products -->
  <div wire:ignore.self class="modal fade" id="returnProducts" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Детали накладной</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="min-height:270px;">

          <form class="mb-3" style="position: relative;">
            <input wire:model="search" type="search" class="form-control form-control-lg" placeholder="Поиск чеков..." aria-label="Search" minlength="2" required>
            @if($incomingOrders)
              <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
                <ul class="list-unstyled mb-0">
                  @forelse($incomingOrders as $incomingOrder)
                    <li>
                      <a wire:click="check({{ $incomingOrder->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">№{{ $incomingOrder->doc_no }} - Сумма: {{ $incomingOrder->sum }}</a>
                    </li>
                  @empty
                    <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
                  @endforelse
                </ul>
              </div>
            @endif
          </form>

          @if($incomingOrder)
            <h5>Чек №{{ $incomingOrder->doc_no }}</h5>
            <?php
              $currency = $company->currency->symbol;
              $sumDiscounted = 0;
            ?>
            <table class="table">
              <thead>
                <tr>
                  <th>Наименование</th>
                  <th>Цена</th>
                  <th>Кол.</th>
                  <th>Скидка</th>
                  <th>Итого</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                  <tr>
                    <th scope="row">{{ $product->title }}</th>
                    <td>{{ $productsData[$product->id]['price'] . $currency }}</td>
                    <td class="text-nowrap" style="width:10%;">
                      <input type="number" wire:model="productsDataCopy.{{ $product->id }}.outgoing_count" class="form-control @error('productsDataCopy.'.$product->id.'.outgoing_count') is-invalid @enderror" required>
                    </td>
                    <td class="text-nowrap" style="width:10%;">
                      <input type="number" wire:model="productsData.{{ $product->id }}.discount" class="form-control @error('productsData.'.$product->id.'.discount') is-invalid @enderror" required>
                    </td>
                    <?php
                      $percentage = $productsData[$product->id]['price'] / 100;
                      $amount = $productsData[$product->id]['price'] - ($percentage * $productsData[$product->id]['discount']);
                      $amountDiscounted = $productsDataCopy[$product->id]['outgoing_count'] * $amount;
                      $sumDiscounted += $productsDataCopy[$product->id]['outgoing_count'] * $amount;
                    ?>
                    <td>{{ $amountDiscounted . $currency }}</td>
                    <td class="text-end"><button wire:click="return({{ $product->id }})" class="btn btn-outline-success">Возврат</button></td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <div class="d-flex">
              <h5>Общая сумма</h5>
              <h5 class="ms-auto">{{ $sumDiscounted . $currency }}</h5>
              <h5 class="ms-auto">{{ $incomingOrder->sum . $currency }}</h5>
            </div>

            <div class="text-end">
              <button type="button" class="btn btn-primary btn-lg text-end"><i class="be bi-hdd-fill me-2"></i> Сохранить</button>
              <button type="button" class="btn btn-dark btn-lg text-end"><i class="be bi-printer-fill me-2"></i> Печать</button>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

</div>
