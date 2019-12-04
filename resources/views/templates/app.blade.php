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
        <link href="{{ url('/public/css/app.css') }}" rel=stylesheet >
        @yield('styles')  

        @if ( App::environment() === 'local' )
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places"></script>  
        @endif  
        
        <script>
            var URL = {
                'base' : '{{ url('/') }}',
                'current' : '{{ url()->current() }}',
                'full' : '{{ url()->full() }}',
                'previous': '{{ url()->previous() }}'
            };
            var token = '{{ csrf_token() }}';
        </script>
    </head>
    <body>
        <div id="app">   
            @yield('header')               
            <div class="aside-overlay"></div>
            @if(!Auth::check())
            <page-menu></page-menu>   
            @elseif(Auth::user()->role == '1001')  
            <admin-menu></admin-menu>   
            @elseif(Auth::user()->role == '1002')  
            <owner-menu></owner-menu>   
            @elseif(Auth::user()->role == '1003')  
            <captain-menu></captain-menu>        
            @endif
            @yield('content')
            <page-footer></page-footer> 
        </div>
        @yield('scripts')
        <script src="{{ url('/public/js/app.js') }}"></script>
    </body>
</html>