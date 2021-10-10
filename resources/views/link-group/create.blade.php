@extends('shared/layout')

@section('meta')
  <title>Create a {{ env('APP_NAME') }} link - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('main')
  <div id="app">
    <link-group-create />
  </div>

  <script src="{{ mix('js/link-group/create/app.js') }}" type="text/javascript"></script>
@endsection