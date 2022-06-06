@extends('store.layout')

@section('styles')
  @livewireStyles
@endsection

@section('nav-functions')
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <h4 class="col-3 col-lg-3 mb-md-2 mb-lg-0">Добавление товара</h4>
  </div>
@endsection

@section('content')

  <livewire:store.add-product>

@endsection

@section('scripts')
  @livewireScripts

  <script type="text/javascript">
    // window.addEventListener('hide-modal', event => {
    //   // const modalToggle = document.getElementById('addCategory');
    //   // myModal.hide(modalToggle)

    //   const myModal = document.getElementById('addCategory')

    //   myModal.addEventListener('show.bs.modal', event => {
    //       return event.preventDefault() // stops modal from being shown
    //   })
    // })
  </script>
@endsection