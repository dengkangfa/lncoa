<!DOCTYPE html>
<html lang="zh">
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

    <style>
        [v-cloak] { display: none; }
    </style>

    <script>
        // window.Laravel = {
        //     csrfToken: "{{ csrf_token() }}"
        // }

        window.Language = "{{ config('app.locale') }}"
    </script>
</head>
<body>
    <div id="app">
        请耐心等待加载
          <!-- <img src="images/timg.gif" alt=""> -->
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
