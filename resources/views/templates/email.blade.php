<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>@yield('title', 'Lander') | BoatCaptain</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="BoatCaptain">
        @yield('meta')
        <link rel="icon" href="{{ url('/favicon.ico') }}">
        <link href="{{ url('/public/css/app.css') }}" rel=stylesheet >
        @yield('styles')
    </head>
    <body style="background-color: initial;">
        <div id="app">   
            @yield('content')
        </div>
        @yield('scripts')
    </body>
</html>