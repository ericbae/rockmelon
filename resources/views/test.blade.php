@extends('shared/layout')

@section('meta')
  <title></title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('custom-header')
	<script>
		
	</script>
@endsection

@section('main')
	<div id="app">
		<test />
	</div>
	<script src="{{ mix('js/test/app.js') }}" type="text/javascript"></script>
@endsection