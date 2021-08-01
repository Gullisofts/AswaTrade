<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{config('app.name')}}</title>

        @yield('styles')
        <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
    </head>
    <body class="antialiased">

        <div class="spinner_cont" id="spinner_cont">
            <div class="spinner_cont_inner">
                <div class="spinner"></div>
            </div>
        </div>
        <div class="success_msg_main" id="success_msg_main">
            <div class="success_cont_inner">
                <div class="msg_inner img-thumbnail">
                    <i class="fas fa-thumbs-up" data-aos="fade-left" data-aos-delay="1000"></i>
                    <p class="mt-4" data-aos="fade-up" data-aos-delay="1500">تم تعديل البيانات بنجاح</p>
                </div>
            </div>
        </div>

        <div id="app">

            @yield('content')
            <router-view :key="$route.fullPath"></router-view>
        </div>

        @yield('scripts')

    </body>
</html>
