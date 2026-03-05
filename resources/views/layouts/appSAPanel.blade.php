<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Restaurant') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>



    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    @yield('extra-css')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        #SAPBody{
            background-color:rgb(39,190,175) ;
            color:white;
        }

        .BRef{
            background-color:white ;
            color:rgb(39,190,175);
            border-bottom-left-radius:25px;
            border-bottom-right-radius:25px;
        }

        .KatBox{
            background-color:white ;
            border-radius:25px;
        }

        .qrorpaColor{
            color:rgb(39,190,175);
        }
    </style>
</head>










<body id="SAPBody">

                <div class="container-fluid" style="background-color:white">

                    <div class="row pt-1 pb-1" >
                        <div class="col-2">
                            <a href="{{url('/')}}">
                                 <img width="75%;" src="storage/images/logo_QRorpa.png" alt="">
                            </a>
                        </div>

                        <div class="col-6">
                        </div>

                        <div class="col-4 text-right pt-3">
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
                                        {{__('layouts.extras')}}
                                    </a>
                                </div>
                            @endif
                            @if(Request::is('produktet'))
                                <div class="top-right links">
                                    <a href="{{ route('produktet.index') }}">
                                        {{__('layouts.products')}}
                                    </a>
                                </div>
                            @endif
                            @if(Request::is('Llojet'))
                                <div class="top-right links">
                                    <a href="{{ route('llojetPro.index') }}">
                                        {{__('layouts.types')}}
                                    </a>
                                </div>
                            @endif
                            
                        </div>
                        
                       
                            

                            
                      
                    </div>
                </div>


                @yield('content')




                @yield('extra-js')


</body>
</html>
