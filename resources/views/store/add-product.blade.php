@extends('store.layout')

@section('nav-functions')
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <h4 class="mb-md-2 mb-lg-0">Добавление товара</h4>
  </div>
@endsection

@section('content')

  <livewire:store.add-product>

@endsection
