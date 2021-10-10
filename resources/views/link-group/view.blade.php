@extends('shared/layout')

@section('meta')
  <title>Try it out! - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('custom-header')
  <script>
    var currLinkGroup = @json($linkGroup)
  </script>
@endsection

@section('main')
  <div id="app">
    <link-group-view />
  </div>

  <script src="{{ mix('js/link-group/view/app.js') }}" type="text/javascript"></script>
@endsection