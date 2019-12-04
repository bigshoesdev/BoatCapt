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

        <!-- stylesheets -->
        <link href="/public{{ elixir('css/app.css') }}" rel=stylesheet >
        @yield('styles')        
        <script>
            var URL = {
                'base' : '{{ url('/') }}',
                'current' : '{{ url()->current() }}',
                'full' : '{{ url()->full() }}',
                'previous': '{{ url()->previous() }}',
            };
        </script>
    </head>
    <body>
        <div id="app">
            @yield('content')
        </div>
        @yield('scripts')
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
        <script src="/public{{ elixir('js/app.js') }}"></script>
    </body>
</html>