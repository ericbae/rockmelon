@extends('shared/layout')

@section('meta')
  <title>Reset your password - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('main')
	<div id="app">
		<auth-reset-password :user-id="{{ $userId }}" :reset-key="'{{ $key }}'"></reset-password>
	</div>

	<script src="{{ mix('js/auth/app.js') }}" type="text/javascript"></script>
@endsection