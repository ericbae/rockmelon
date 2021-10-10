@extends('shared/layout')

@section('meta')
  <title>Login - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
@endsection

@section('custom-header')
  {{-- <script type="text/javascript">
    var hasRedirectUrl = {{ session()->has('redirect-url') ? 1 : 0 }};
  </script>
  
  <script>
    var showMessage = {{ $showMessage ? 'true' : 'false' }};
  </script> --}}
@endsection

@section('main')
	<div id="app">
		<auth-login />
	</div>

	<script src="{{ mix('js/auth/app.js') }}" type="text/javascript"></script>
@endsection