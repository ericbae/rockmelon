@extends('shared/layout')

@section('meta')
  <title>{{ env('APP_NAME') }} - Done</title>
  <meta name="keywords" content="" />
  <meta property="og:title" content="{{ env('APP_NAME') }}" />
  <meta property="og:locale" content="en_US" />
  <meta name="description" content="{{ env('APP_NAME') }}" />
  <meta property="og:description" content="{{ env('APP_NAME') }}" />
@endsection

@section('custom-header')

@endsection

@section('main')
  <div class="text-center text-base font-semibold mt-20">
    <img src="https://img.icons8.com/color/96/000000/bodyguard-male.png" class="w-16 inline" />
    Cool.
  </div>
@endsection