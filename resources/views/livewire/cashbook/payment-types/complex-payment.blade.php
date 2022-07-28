<div>
  <div class="d-flex">
    <h2>Оплата</h2>
    <button type="button" class="btn btn-outline-dark btn-lg ms-auto">Закрыть</button>
  </div>
  <br>
  <p class="text-muted">Выберите 2 метода оплаты из вариантов</p>
  <form class="mb-3">
    <div class="mb-3 row align-items-center">
      <div class="col-lg-5 form-check">
        <input class="form-check-input" type="checkbox" name="payment_1" value="1" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">Наличными</label>
      </div>
      <div class="col-sm-7">
        <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" placeholder="Введите сумму">
      </div>
    </div>
    <div class="mb-3 row align-items-center">
      <div class="col-lg-5 form-check">
        <input class="form-check-input" type="checkbox" name="payment_2" value="2" id="flexCheckDefault2">
        <label class="form-check-label" for="flexCheckDefault2">Банковской картой</label>
      </div>
      <div class="col-sm-7">
        <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" placeholder="Введите сумму">
      </div>
    </div>
    <div class="mb-3 row align-items-center">
      <div class="col-lg-5 form-check">
        <input class="form-check-input" type="checkbox" name="payment_3" value="3" id="flexCheckDefault3">
        <label class="form-check-label" for="flexCheckDefault3">Перевод на Каспи</label>
      </div>
      <div class="col-sm-7">
        <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" placeholder="Введите сумму">
      </div>
    </div>

    <button type="submit" class="btn btn-success btn-lg">Оплатить</button>
  </form>

  <div class="d-flex">
    <h4>Итого</h4>
    <h4 class="ms-auto">10 000 000KZT</h4>
  </div>
</div>