<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="en-us" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{!! csrf_token() !!}" />
    <meta name="googlebot" content="index, follow" />
    <meta name="robots" content="index, follow" />
    <meta name="subject" content="developers, side projects, designers, programmers, hackers, ycombinator" />
    <link rel="shortcut icon" href="{{ config(strtolower(env('APP_NAME')) . '.logo') }}" type="image/x-icon" />
    @yield('meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    @include('shared/js')
    @yield('custom-header')
  </head>
  <body>
      @yield('main')
  </body>
</html>
