<div>
  <div wire:ignore.self class="modal fade" id="returnProducts" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable {{ $modalClass }}">
      <div class="modal-content bg-light">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Оформление возврата</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="min-height:270px;">

          <form class="mb-3" style="position: relative;">
            <div class="input-group">
              <input wire:model="search" onclick="setFocus(this, 'returnProductsInput-search')" type="search" class="form-control form-control-lg" placeholder="Поиск чеков..." aria-label="Search">
              <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvasBottom"><i class="bi bi-keyboard-fill"></i></button>
            </div>
            @if($incomingOrders)
              <div class="dropdown-menu d-block pt-0 w-100 shadow overflow-hidden" style="position: absolute;">
                <ul class="list-unstyled mb-0">
                  @forelse($incomingOrders as $incomingOrderObj)
                    <li>
                      <a wire:click="check({{ $incomingOrderObj->id }})" class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">№{{ $incomingOrderObj->doc_no }} - Сумма: {{ $incomingOrderObj->sum }}</a>
                    </li>
                  @empty
                    <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 disabled">No data</a></li>
                  @endforelse
                </ul>
              </div>
            @endif
          </form>

          @if($incomingOrder)
            <?php
              $message = ['corrected' => 'Изменен'];
              $currency = $company->currency->symbol;
              $sumDiscounted = 0;
              $change = 0;
            ?>
            <h5>Чек №{{ $incomingOrder->doc_no }} <span class="text-success">{{ $message[$incomingOrder->comment] ?? null }}</span> | Тип операции: {{ __('operation-codes.'.$incomingOrder->operation_code) }}</h5>
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Наименование</th>
                  <th>Цена</th>
                  <th>Кол-во<br> проданных</th>
                  <th>Кол-во<br> возвратов</th>
                  <th>Итоговое<br> кол-во</th>
                  <th>Скидка</th>
                  <th colspan="2">Итого</th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                  <?php
                    $percentage = $productsData[$product->id]['price'] / 100;
                    $amount = $productsData[$product->id]['price'] - ($percentage * $productsData[$product->id]['discount'] ?? 0);
                    $amountDiscounted = $productsData[$product->id]['outgoingCount'] * $amount;
                    $sumDiscounted += $productsData[$product->id]['outgoingCount'] * $amount;
                    $returnedCount = $productsData[$product->id]['returnedCount'] ?? 0;
                  ?>
                  <tr>
                    <th scope="row">{{ $product->title }}</th>
                    <td>{{ $productsData[$product->id]['price'] . $currency }}</td>
                    <td>{{ $productsData[$product->id]['outgoingCount'] }}</td>
                    <td>{{ $returnedCount }}</td>
                    <td class="text-nowrap" style="width:10%;">
                      <input wire:model="productsData.{{ $product->id }}.returningCount" onclick="setFocus(this, 'returnProductsInput-productsData.{{ $product->id }}.returningCount')" type="number" class="form-control @error('productsData.'.$product->id.'.returningCount') is-invalid @enderror" required>
                    </td>
                    <td class="text-nowrap" style="width:10%;">
                      <input wire:model="productsData.{{ $product->id }}.discount" onclick="setFocus(this, 'returnProductsInput-productsData.{{ $product->id }}.discount')" type="number" class="form-control @error('productsData.'.$product->id.'.discount') is-invalid @enderror" disabled required>
                    </td>
                    <td>{{ $amountDiscounted . $currency }}</td>
                    <td class="text-end">
                      @if(isset($returnedProducts[$product->id]))
                        <?php $change += $amount * $returnedProducts[$product->id]['incomingCount']; ?>
                        <button wire:click="cancel({{ $product->id }})" class="btn btn-dark">Отмена</button>
                      @else
                        <button wire:click="return({{ $product->id }})" class="btn btn-success"
                            @if(empty($productsData[$product->id]['returningCount']) || $productsData[$product->id]['outgoingCount'] == $returnedCount) disabled @endif>Возврат</button>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <div class="d-flex">
              <h5>Возвратов</h5>
              <h5 class="ms-auto"><?php echo collect($returnedProducts)->sum('incomingCount') ?? 0; ?></h5>
            </div>
            <div class="d-flex">
              <h5>Сдача</h5>
              <h5 class="ms-auto">{{ $change . $currency }}</h5>
            </div>
            <div class="d-flex">
              <h5>Общая сумма</h5>
              <h5 class="ms-auto">{{ $incomingOrder->sum . $currency }}</h5>
            </div>
            <div class="d-flex">
              <p><b>Комментарии:</b> {{ $incomingOrder->comment }}</p>
            </div>

            <div class="text-end">
              <button wire:click="makeReturnDocs" type="button" class="btn btn-primary btn-lg" @if($change == 0) disabled @endif><i class="bi bi-file-earmark-ruled-fill me-2"></i> Оформить</button>
              <a href="/{{ app()->getLocale() }}/cashdesk/docsprint/incoming-check/{{ $incomingOrder->id }}" class="btn btn-dark btn-lg"><i class="be bi-printer-fill me-2"></i> Печать</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

</div>
