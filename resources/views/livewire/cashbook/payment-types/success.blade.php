<div>

  <main class="container my-4">
    <div class="row">
      <div class="col-lg-5">

        <livewire:cashbook.payment-types.cart-order>

      </div>
      <div class="col-lg-6 offset-1">

        <div class="text-center">
          <h2>Продажа прошла успешно</h2>
          <br>
          <div class="display-1 text-success"><i class="be bi-patch-check-fill"></i></div>
          <br>
          <button wire:click="backToCash" type="button" class="btn btn-outline-dark btn-lg ms-auto">Закрыть</button>
        </div>
      </div>
    </div>
  </main>

</div>
