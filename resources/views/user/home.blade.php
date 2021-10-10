@extends('shared/layout')

@section('meta')
  <title>Home - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('main')
  <div id="app">
    <user-home />
  </div>

  <script src="{{ mix('js/user/home/app.js') }}" type="text/javascript"></script>
@endsection