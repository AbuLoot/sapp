<div>
  <div class="d-flex">
    <h2>Продажа в долг</h2>
    <a href="/{{ app()->getLocale() }}/cashdesk/payment-types" class="btn btn-outline-dark btn-lg ms-auto">Закрыть</a>
  </div>
  <br>

  <p class="text-muted">Выберите из поля поиска человека, которому хотите оформить долг</p>

  <form class="mb-3">

    <div class="row">
      <div class="col-lg-7 mb-3">
        <input type="text" class="form-control form-control-lg" id="text" name="text" minlength="2" value="" placeholder="Поиск по имени, названию, штрихкоду" required>
      </div>
      <div class="col-lg-5 mb-3">
        <div class="d-grid" role="group" aria-label="Basic example">
          <button type="button" class="btn btn-success btn-lg"><i class="bi bi-person-plus-fill me-2"></i> Новый клиент</button>
        </div>
      </div>
    </div>

    <div class="d-flex position-relative mb-3">
      <div class="flex-shrink-0 display-6">
        <i class="bi bi-person-circle"></i> 
      </div>
      <div class="ms-3">
        <h6 class="mb-0">User Name</h6>
        <a href="#" class="stretched-link">8 777 999966</a>
      </div>
    </div>

    <div class="d-flex position-relative mb-3">
      <div class="flex-shrink-0 display-6">
        <i class="bi bi-person-circle"></i> 
      </div>
      <div class="ms-3">
        <h6 class="mb-0">User Name</h6>
        <a href="#" class="stretched-link">8 777 999966</a>
      </div>
    </div>

    <div class="d-flex position-relative mb-3">
      <div class="flex-shrink-0 display-6">
        <i class="bi bi-person-circle"></i> 
      </div>
      <div class="ms-3">
        <h6 class="mb-0">User Name</h6>
        <a href="#" class="stretched-link">8 777 999966</a>
      </div>
    </div>

  </form>
</div>