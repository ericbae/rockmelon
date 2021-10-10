@extends('shared/layout')

@section('meta')
  <title>Try it out! - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('main')
	<div id="app">
		<link-group-try />
	</div>

	<script src="{{ mix('js/link-group/try/app.js') }}" type="text/javascript"></script>
@endsection