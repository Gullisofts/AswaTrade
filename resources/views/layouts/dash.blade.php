<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dashboard</title>

        @yield('styles')

    </head>
    <div class="spinner_cont" id="spinner_cont">
        <div class="spinner_cont_inner">
            <div class="spinner"></div>
        </div>
    </div>
    <body class="antialiased">

        <div id="app_dash">
            @yield('content')
            <router-view></router-view>
        </div>

        @yield('scripts')

    </body>
</html>
