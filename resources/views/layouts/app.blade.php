<!doctype html>
<html lang="ka">
<head>
    <meta charset="utf-8" style="background-color: #28a745">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Joker') }}</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon" sizes="16x16">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        window.App = {!! json_encode([
            'user' => Auth::user(),
            'url' => env('APP_URL'),
            'bot_timer' => env('BOT_TIMER'),
            'bot_disabled' => env('BOT_DISABLED')
        ]) !!};
    </script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('style')
</head>
<body class="bg-success" style="display: none">
    <div id="app">
        @yield('nav')


        @yield('content')
    </div>
</body>
</html>
