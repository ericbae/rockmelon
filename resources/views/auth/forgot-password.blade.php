@extends('shared/layout')

@section('meta')
  <title>Forgot password - {{ env('APP_NAME') }}</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/medium-editor.min.css" integrity="sha512-zYqhQjtcNMt8/h4RJallhYRev/et7+k/HDyry20li5fWSJYSExP9O07Ung28MUuXDneIFg0f2/U3HJZWsTNAiw==" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/css/themes/default.css" integrity="sha512-LqCMlwE9OklwFxyzfvyDA/9Q76QN8dyEK+zzBECekano/JQ0qtioP4I3voL6Njm2peezAxolHLKVxFNjTjCHIA==" crossorigin="anonymous" />
@endsection

@section('main')
	<div id="app">
		<auth-forgot-password />
	</div>

	<script src="{{ mix('js/auth/app.js') }}" type="text/javascript"></script>
@endsection