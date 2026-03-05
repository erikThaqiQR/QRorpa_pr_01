
<!DOCTYPE html>
<html lang="de" translate="no">
<head>
    <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <link rel="icon" href="/storage/images/icon01.png">


    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Orders') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="https://js.pusher.com/6.0/pusher.min.js"></script>

    <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
    @include('fontawesome')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    @yield('extra-css')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ratings.css') }}" rel="stylesheet">


    <style>
     .optionsAnchorPh{
        color:black;
        text-decoration:none;
        opacity:0.85;
        font-weight: bold;
        font-size:17px;
        width: 100%;
        display: block;

        padding: 15px 0px 15px 50px;
        background-color: white;
        background-size: cover;
        margin-bottom: 10px;

       
    }
    .optionsAnchorPh:hover{
        opacity:0.100;
        text-decoration:none;
        color:black;
        
    }

    a, a:hover, a:active, a:visited, a:focus{
 text-decoration: none !important;
}

    </style>


</head>
<body style="background-color:rgb(39,190,174);">


<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
 ?>
        @if(isset($_SESSION['Res']) && isset($_SESSION['t']))
            <input type="hidden" id="theRes" value="{{$_SESSION['Res']}}">
            <input type="hidden" id="theTable" value="{{$_SESSION['t']}}">
        @endif


        <nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-left">
                        <?php
                            if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
                                echo '<a class="navbar-brand" href="/?Res='.$_SESSION["Res"].'&t='.$_SESSION["t"].'">';
                            }else if(isset($_SESSION['Bar'])){
                                echo '<a class="navbar-brand" href="/?Bar='.$_SESSION["Bar"].'">';
                            }else{
                                echo '<a class="navbar-brand" href="/">';
                            }
                        ?>
                            <img style="width:130px" src="/storage/images/logo_QRorpa.png" alt="">
                        </a>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-12" >
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img class="mr-4" src="storage/icons/listDown.PNG"/></button> 
                    </div>
                </div>
            </div>
        </nav>


















    @if(isset($_SESSION['Bar']))
        <input type="hidden" value="{{$_SESSION['Bar']}}" id="BarId">
    @endif



















<!-- The phone options Modal -->
<div class="modal fade" id="optionsModal">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" style="border-radius:30px;">

            <!-- Modal body -->
            <div  style="background-color:whitesmoke; width:100%;">
               
                <div class="text-center pt-4 pb-4" style="background-color:rgb(39, 190, 175); height:300px; transform: skewY(-6deg); margin-top:-230px; margin-bottom:-20px;">
                    @if(Auth::check())
                    <a onclick="profileOpenClick()" clas="profileLine" href="{{ route('profile.index') }}">
                        <div class="d-flex justify-content-center" style="margin-top:220px; transform: skewY(6deg);">                           
                            @if(Auth::user()->profilePic == 'empty')
                                <i class="far fa-3x fa-user" style="color:white;"></i>
                            @else
                                <img style="width:40px; height:40px; border-radius:50%;"  src="/storage/profilePics/{{Auth::user()->profilePic}}" alt="img">
                            @endif
                            <p style="font-size:24px; font-weight:bolder; color:white; " class="ml-2">
                                {{Auth::user()->name}}
                            </p>
                        </div>
                    </a>
                    @else
                        <div class="d-flex justify-content-center" style="margin-top:220px; transform: skewY(6deg);">
                 
                            <i class="far fa-3x fa-user" style="color:white;"></i>
                            <p style="font-size:24px; font-weight:bolder; color:white; " class="ml-2">
                                {{__('layouts.myAccount')}}
                            </p>
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-between text-center" style="margin-top:40px;">
                    @if(Auth::check())
                        <a class=" btn btn-block {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ route('logout') }}" 
                            style="border:2px solid lightgray; color:black; width:50%; margin-left:25%;"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('layouts.logout') }}
                        </a>
                        <form class="optionsAnchorPh" id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a onclick="countEinloggenClick()" class=" btn {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" style="border:2px solid lightgray; color:black; width:48%;"
                         href="{{ route('login') }}">{{__('layouts.login')}}</a>
                                   
                        <a onclick="countRegisterClick()" class="btn  {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" style="border:4px solid rgb(39, 190, 175); color:black; width:48%;"
                         href="{{ route('register') }}">{{__('layouts.toRegister')}}</a>
                      
                    @endif
                </div>

                <div style="background-color:whitesmoke; width:100%;" class="mt-2">
                    @if(isset($_SESSION["Res"]) && isset($_SESSION["t"]))
                        @if(Auth::check())<!--Restorant(Yes)  User(Yes) -->
                            @if(auth()->user()->role == 9)
                                <a onclick="SAPageOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ route('manageProduktet.index') }}"><i class="fas fa-columns"></i> {{__('layouts.restaurantManagement')}}</a>   
                            @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3)
                                <a onclick="APageOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('dash.index')}}"><i class="fas fa-columns"></i> {{__('layouts.adminMenu')}}</a>       
                            @endif
                            @if(Auth::user()->role != 9)
                                <a onclick="WaiterCallsOpenClick()" style="width:100%;" class="optionsAnchorPh" data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>   
                                <a onclick="CartOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('cart')}}"><i class="fas fa-shopping-cart"></i> {{__('layouts.cart')}}
                                    @if(count(Cart::content()) > 0)
                                        <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;">
                                        {{ count(Cart::content()) }}</span>
                                    @endif
                                </a>
                                <a onclick="MyOrdersOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ url('/home') }}"><i class="fas fa-utensils"></i> {{__('layouts.orders')}}</a>
                            @endif
                            <a class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="/tableRezIndex?Res={{$_SESSION['Res']}}"><i class="fas fa-table"></i> {{__('layouts.tableReservation')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>

                        @else<!--Restorant(Yes)  User(No) -->
                            <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh " data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                            <a onclick="CartOpenClick()" class="optionsAnchorPh  {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ route('cart') }}"><i class="fas fa-shopping-cart"></i> {{__('layouts.cart')}}
                                @if(  count(Cart::content()) > 0)
                                    <sup>  <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;"> {{ count(Cart::content()) }}</span></sup>
                                @endif
                            </a>   
                            <a onclick="TrackOrderOpenClick()" class="optionsAnchorPh  {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ route('trackOrder.Home') }}"><i class="fas fa-file-contract"></i> {{__('layouts.trackOrder')}}</a>
                            <a class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="/tableRezIndex?Res={{$_SESSION['Res']}}"><i class="fas fa-table"></i> {{__('layouts.tableReservation')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                           
                        @endif
                    @else
                        @if(Auth::check())<!--Restorant(No)  User(Yes) -->
                            @if(auth()->user()->role == 9)
                                <a onclick="SAPageOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ route('manageProduktet.index') }}"><i class="fas fa-columns"></i> {{__('layouts.restaurantManagement')}}</a>
                            @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3 || auth()->user()->role == 15)
                                <a onclick="APageOpenClick()" class="optionsAnchorPh  {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('dash.index')}}"><i class="fas fa-columns"></i> {{__('layouts.adminMenu')}}</a>
                                <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh" data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                            @elseif(auth()->user()->role == 1)
                                <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh " data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                            @endif
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                        @else<!--Restorant(No)  User(No) -->
                            <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh" data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                        @endif
                    @endif
                </div>
                
              
                   

                <div class="text-center mt-3" >
                    <div class="text-center">
                        <button type="button" class="close text-center pb-3 pr-4" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





























    
    @yield('content')


    @yield('extra-js')

    @include('inc.footer')










    
</body>
</html>









