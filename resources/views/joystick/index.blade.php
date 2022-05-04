@extends('joystick.layout')

@section('content')

  @include('joystick.partials.alerts')

  <br>
  <h1 class="text-center">Salam alaikum<br> {{ Auth::user()->name }}!</h1>

  <div class="row">
    <img src="/file-manager/bg-joystick-2.png" class="img-responsive center-block">
  </div>

@endsection