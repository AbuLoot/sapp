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
  <script src="/bower_components/jquery/dist/jquery.min.js"></script>
  <script>
    function addBarcodeInput(i) {
      var barcodeInput =
          '<div class="mb-3">' +
            '<div class="input-group">' +
              '<input type="number" class="form-control" id="barcode" name="barcode" value="">' +
              '<button type="button" onclick="removeBarcodeInput(this)" class="input-group-text bg-dark text-white"><i class="bi bi-x-lg"></i></button>' +
            '</div>' +
            '<div class="form-text"><a href="#"><i class="bi bi-upc"></i> Сгенерировать штрихкод</a></div>' +
          '</div>';

      $('#barcodes').append(barcodeInput);
    }

    function removeBarcodeInput(i) {
      $(i).parent().parent().remove();
    }
  </script>
@endsection