<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="title" content="{{ config('app.name') }}">
        <meta name="description" content="Первый в мире сайт с конструктором сетов CS:GO">
        <meta name="keywords" content="скины, csgo, ксго, конструктор сетов, конструктор, сборка скинов ксго">
        <title>{{ config('app.name') }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app"></div>
        <script>
            window.config = @json([
                'appName' => config('app.name'),
            ])
        </script>
        <script src="{{ asset("js/app.js") }}" charset="utf-8"></script>
    </body>
</html>
