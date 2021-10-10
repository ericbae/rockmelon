@extends('shared/layout')

@section('meta')
  <title>Registered - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('custom-header')
	<script>
		var redirectUrl = '{{ $redirectUrl }}';
	</script>
@endsection

@section('main')
	<div id="app">
		<Registered />
	</div>

	<script src="{{ mix('js/auth/app.js') }}" type="text/javascript"></script>
@endsection