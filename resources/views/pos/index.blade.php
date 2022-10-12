@extends('pos.layout')

@section('content')

  @include('pos.partials.alerts')

  <br>
  <h1 class="text-center">Salam alaikum<br> {{ Auth::user()->name }}!</h1>

@endsection