@extends('joystick.layout')

@section('content')

  @include('joystick.partials.alerts')

  <iframe src="<?= url($lang.'/admin/filemanager'); ?>" frameborder="0" style="width:100%; min-height:600px"></iframe>

@endsection
