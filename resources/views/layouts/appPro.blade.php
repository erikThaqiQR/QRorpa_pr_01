<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Restaurant') }}</title>


    @yield('extra-css')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <!-- {{ config('app.name', 'Laravel') }} -->
                    {{__('layouts.restaurant')}}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>






                @if(Request::is('produktet1'))
                    <div class="top-right links">
                        <a href="{{ route('manageProduktet.index') }}">
                                {{__('layouts.productManagement')}}
                        </a>
                    </div>
                @endif

                @if(Request::is('Kategorite'))
                    <div class="top-right links">
                        <a href="{{ route('kategorite.index') }}">
                                {{__('layouts.categories')}}
                        </a>
                    </div>
                @endif
                @if(Request::is('Ekstras'))
                    <div class="top-right links">
                        <a href="{{ route('ekstras.index') }}">
                            {{__('layouts.ekstras')}}
                        </a>
                    </div>
                @endif
                @if(Request::is('produktet'))
                    <div class="top-right links">
                        <a href="{{ route('produktet.index') }}">
                            {{('layouts.products')}}
                        </a>
                    </div>
                @endif

            

                
               
            </div>
        </nav>

        <main class="py-8">
            @yield('content')
            @include('inc.footer')
        </main>
    </div>


    <script src="js/app.js">
    </script>

    @yield('extra-js')
</body>
</html>
