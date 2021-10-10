@extends('shared/layout')

@section('meta')
  <title>My Account - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('main')
  <div id="app">
    <user-account />
  </div>

  <script src="{{ mix('js/user/account/app.js') }}" type="text/javascript"></script>
@endsection