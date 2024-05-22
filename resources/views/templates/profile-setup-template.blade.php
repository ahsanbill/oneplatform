<!doctype html>

<html lang="en" class="bg-gray-50">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="AR1UIRB4nzeneJoD1RppX4OOJKzdrH3GLDc7O1jix9Q" />
        <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('pagetitle')</title>
        <link href="{{asset('css/style.min.css?v=3.3')}}" rel="stylesheet" type="text/css">
        <link href="/css/app.css?v=3.84" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('fontawesome/css/all.min.css')}}">
        <link rel="icon" href="/favicon.ico?v=1.1" type="image/x-icon" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <script src="/js/jquery.min.js" type="text/javascript"></script>
        @yield('page-level-css','')
        @yield('page-level-js','')
    </head>

    <body>

        <div class="w-full px-4 mx-auto lg:px-8">
            <div class="max-w-4xl mx-auto mb-72">
                @yield('page-content','')
            </div>
        </div>
        @yield('miscellaneous-html','')
        <div id="body-overlay"></div>
    </body>

</html>
