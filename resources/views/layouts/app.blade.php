<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <!-- Icon -->
    <link rel="shortcut icon" href="{{ config('lnc.default_icon') }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  </head>
  <body>
      <div class="main">
          @yield('content')
      </div>
  </body>
</html>
