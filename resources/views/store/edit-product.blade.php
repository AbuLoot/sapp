@extends('store.layout')

@section('styles')
  @livewireStyles
@endsection

@section('nav-functions')
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Редактирование товара</h4>
  </div>
@endsection

@section('content')

  <livewire:store.edit-product>

@endsection

@section('scripts')
  @livewireScripts
@endsection