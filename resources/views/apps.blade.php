<x-app-layout>
  <x-slot name="title">
    Приложения
  </x-slot>

  <div class="row align-items-md-stretch">
    <div class="col-md-6 mb-3">
      <div class="h-100 p-5 bg-light border rounded-3">
        <h2 class="mb-3">Storage</h2>
        <h4 class="mb-3">Умный склад</h4>
        <a class="btn btn-lg btn-primary" href="/{{ app()->getLocale() }}/storage">Перейти</a>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="h-100 p-5 bg-light border rounded-3">
        <h2 class="mb-3">Cashdesk</h2>
        <h4 class="mb-3">Умная касса</h4>
        <a class="btn btn-lg btn-primary" href="/{{ app()->getLocale() }}/cashdesk">Перейти</a>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="h-100 p-5 bg-light border rounded-3">
        <h2 class="mb-3">POS System</h2>
        <h4 class="mb-3">Панель управления</h4>
        <a class="btn btn-lg btn-primary" href="/{{ app()->getLocale() }}/pos">Перейти</a>
      </div>
    </div>
  </div>

  <!-- <div class="row align-items-md-stretch">
    <div class="col-md-6 mb-3">
      <div class="position-relative">
        <div class="position-absolute top-0 start-50 p-0">
          <i class="bi bi-shop-window text-brand-dark icon-size-1"></i>
        </div>
      </div>
      <div class="h-100 p-5 bg-brand text-white border rounded-3">
        <h2 class="mb-3">Storage</h2>
        <h5 class="mb-3">Умный Склад</h5>
        <a class="btn btn-lg btn-outline-light" href="/{{ app()->getLocale() }}/storage">Перейти</a>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="position-relative">
        <div class="position-absolute top-0 start-50 p-0">
          <i class="bi bi-bank2 text-brand-dark icon-size-1"></i>
        </div>
      </div>
      <div class="h-100 p-5 bg-brand text-white border rounded-3">
        <h2 class="mb-3">Cashdesk</h2>
        <h5 class="mb-3">Умная Касса</h5>
        <a class="btn btn-lg btn-outline-light" href="/{{ app()->getLocale() }}/cashdesk">Перейти</a>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="position-relative">
        <div class="position-absolute top-0 start-50 p-0">
          <i class="bi bi-keyboard text-brand-dark icon-size-1"></i>
        </div>
      </div>
      <div class="h-100 p-5 bg-brand text-white border rounded-3">
        <h2 class="mb-3">Administration</h2>
        <h5 class="mb-3">Панель управления</h5>
        <a class="btn btn-lg btn-outline-light" href="/{{ app()->getLocale() }}/admin">Перейти</a>
      </div>
    </div>
  </div> -->

</x-app-layout>