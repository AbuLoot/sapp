<div>
  <div class="d-flex">
    <h2>Оплата</h2>
    <a href="/{{ app()->getLocale() }}/cashdesk/payment-types" class="btn btn-outline-dark btn-lg ms-auto">Закрыть</a>
  </div>
  <br>

  <form class="mb-3">
    <div class="mb-3 row align-items-center">
      <div class="col-5 col-lg-5">
        <label class="form-label">Наличными</label>
      </div>
      <div class="col-sm-7">
        <input type="text" class="form-control form-control-lg" id="title" name="title" minlength="2" value="" placeholder="Введите сумму">
      </div>
    </div>
    <div class="mb-3 row align-items-center">
      <div class="col-5 col-lg-5">
        <label class="form-label">Сдача</label>
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