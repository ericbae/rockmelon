@extends('shared/layout')

@section('meta')
  <title>Confirmed - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('main')
	<div id="app">
		<Confirmed />
	</div>

	<script src="{{ mix('js/auth/app.js') }}" type="text/javascript"></script>
@endsection