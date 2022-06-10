@extends('store.layout')

@section('nav-tools')
    <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Главная</h4>

    <form class="col-4 col-lg-4 mb-md-2 mb-lg-0 me-lg-auto">
      <input type="search" class="form-control" placeholder="Поиск..." aria-label="Search">
    </form>

    <ul class="nav col-lg-auto text-end me-lg-2 text-small">
      <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="funnel"><i class="bi bi-funnel-fill"></i></a></li>
      <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="printer"><i class="bi bi-printer-fill"></i></a></li>
      <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="x"><i class="bi bi-x-square-fill"></i></a></li>
      <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="plus"><i class="bi bi-plus-square-fill"></i></a></li>
      <li><a href="#" class="nav-link text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="save"><i class="bi bi-save-fill"></i></a></li>
    </ul>

    <div class="text-end ms-md-auto ms-lg-0">
      <a href="/{{ $lang }}/store/add-product" class="btn btn-primary"><i class="bi bi-plus-circle-fill me-2"></i> Добавить товар</a>
    </div>
@endsection

@section('content')

  <livewire:store.index>

@endsection