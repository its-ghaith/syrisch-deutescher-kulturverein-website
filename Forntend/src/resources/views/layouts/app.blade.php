<!doctype html>
<html lang="{{str_replace('_','-',app()->getLocale())}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>{{config('app.name')}} - @yield('title')</title>

    <script src="{{asset('js/app.js')}}"></script>

    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/theme_1590555558082.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500&display=swap" rel="stylesheet">
</head>
<body>
<div id="app">
    @php
        include_once (app_path().'/Helper/functions.php');
        include_once (app_path().'/Helper/restClient.php');
    @endphp
    @include('_partisals._navbar')
    <main class="container-fluid">
        @yield('content')
    </main>
    @include('_partisals._footer')
</div>
</body>
</html>
