<x-app-layout>
  <x-slot name="title">
    Системы
  </x-slot>

  <!-- <div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
      <h1 class="display-5 fw-bold">Custom jumbotron</h1>
      <p class="col-md-8 fs-4">Using a series of utilities, you can create this jumbotron, just like the one in previous versions of Bootstrap. Check out the examples below for how you can remix and restyle it to your liking.</p>
      <button class="btn btn-primary btn-lg" type="button">Example button</button>
    </div>
  </div>
  <div class="row align-items-md-stretch">
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
  </div>-->

  <div class="row align-items-md-stretch">
    <div class="col-md-6 mb-3">
      <div class="h-100 p-5 bg-light border rounded-3">
        <h2 class="mb-3">Storage</h2>
        <h5 class="mb-3">Умный Склад</h5>
        <a class="btn btn-lg btn-primary" href="/{{ app()->getLocale() }}/storage">Перейти</a>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="h-100 p-5 bg-light border rounded-3">
        <h2 class="mb-3">Cashdesk</h2>
        <h5 class="mb-3">Умная Касса</h5>
        <a class="btn btn-lg btn-primary" href="/{{ app()->getLocale() }}/cashdesk">Перейти</a>
      </div>
    </div>
  </div>
</x-app-layout>