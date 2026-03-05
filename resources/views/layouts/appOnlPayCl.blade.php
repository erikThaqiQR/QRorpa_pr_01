<?php 

use App\User;
use App\tablesAccessToWaiters;
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    } 
 ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

      <link rel="icon" href="/storage/images/qrorpaIcon.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>QRorpa Online-Bezahlung</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

     <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
     <!-- <script src="https://kit.fontawesome.com/d40577511e.js" crossorigin="anonymous"></script> -->
     @include('fontawesome')
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    @yield('extra-css')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
























    
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
    .waiterCallWa{
        cursor: pointer;
    }

    a, a:hover, a:active, a:visited, a:focus{
        text-decoration: none !important;
    }

    </style>
</head>
<script>
    var waSelected = 0;
</script>
<body class="LogInPage">

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
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img class="mr-1" src="storage/icons/listDown.PNG"/></button> 
                    </div>
                </div>
            </div>
        </nav>
    
 
    
        <main>
            @yield('content')
            @yield('extra-js')
        </main>

  



















    @if (isset($_SESSION['Res']) && isset($_SESSION['t']))
        <?php
            $theResIdnn = $_SESSION['Res'];
            $theTabNrnn = $_SESSION['t'];                
        ?>
 
    <!-- The Modal -->
    <div class="modal" id="callWaiter">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:25px;">

                <!-- Modal Header -->
                <div class="d-flex">
                    <h4 style="width:60%;" class="modal-title text-left color-qrorpa pt-2 pl-3">{{__('layouts.callService')}}</h4>
                    @if(isset($_GET['t']))
                        <h4 style="width:40%;" class="modal-title text-right color-qrorpa pr-3 pt-2">{{__('layouts.table')}} {{$_GET['t']}}</h4>
                    @endif
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-start">
                        <p class="text-center" style="width:100%;"><strong>Sie haben die Möglichkeit, einen bestimmten Kellner auszuwählen</strong></p>
                       
                        @foreach (User::where([['role','55'],['sFor',$theResIdnn]])->get() as $waOne)
                            @if (tablesAccessToWaiters::where([['waiterId',$waOne->id],['toRes',$theResIdnn],['tableNr',$theTabNrnn],['statusAct','1']])->first() != NULL)
                                <div style="width:49%; margin-right:1%; border:1px solid black; border-radius:4px;" 
                                class="text-center p-1 waiterCallWa" onclick="selWaOnWaCall('{{$waOne->id}}')" id="waiterSelDiv{{$waOne->id}}">
                                    <img src="storage/icons/Asset 24800.png" style="width:20%;" alt="">
                                    <p style="margin:0px;"><strong>{{$waOne->name}}</strong></p>
                                </div>
                            @endif
                        @endforeach

                        <div style="width: 100%;" class="form-group mt-1">
                            <textarea style="font-size:16px;" placeholder="{{__('layouts.comment')}}..." id="commentCW" class="form-control shadow-none" rows="2"></textarea>
                        </div>

                        @if(isset($_SESSION['Res']) && isset($_SESSION['t']))
                            <input type="hidden" value="{{$_SESSION['Res']}}" id="restaurantCW">
                            <input type="hidden" value="{{$_SESSION['t']}}" id="tableCW">
                        @endif
                            <input type="hidden" value="0" id="waiterSelectedWCall">

                        <div style="width: 49%; margin-right:2%;">
                            <button data-dismiss="modal" class="buttonLogIn btn btn-block shadow-none" 
                            style="background-color: red; color: white; padding: 5px;">
                                <strong>{{__('layouts.cancel')}}</strong>
                            </button>
                        </div>
                        <div style="width: 49%;">
                            <button data-dismiss="modal" onclick="callWaiterF()" class="buttonLogIn btn btn-block shadow-none" 
                            style="background-color: rgb(39, 190, 175); color: white; padding: 5px;">
                                <strong>{{__('layouts.call')}}</strong>
                            </button>
                        </div>
                    </div>
                </div>
      
            </div>
        </div>
    </div>

    @endif



<script>
    function callWaiterF(){
        $.ajax({
            url: '{{ route("waiter.call") }}',
            method: 'post',
            data: {
                res: $('#restaurantCW').val(),
                table: $('#tableCW').val(),
                comment: $('#commentCW').val(),
                waSel: $('#waiterSelectedWCall').val(),
                _token: '{{csrf_token()}}'
            },
            success: (response) => {
                $('#waiterIsComming').show(500).delay(3500).hide(500);
            },
            error: (error) => {
                console.log(error);
                // alert({{__('adminP.oops_wrong')}});
                $('#waiterIsNotComming').show(500).delay(3500).hide(500);
            }
        })
    }

    function selWaOnWaCall(waId){
        if(waSelected != 0){
            $('#waiterSelDiv'+waSelected).attr('style','width:49%; margin-right:1%; border:1px solid black; border-radius:4px;');
        }
        $('#waiterSelDiv'+waId).attr('style','width:49%; margin-right:1%; border:1px solid black; border-radius:4px; background-color:rgb(210, 247, 220);');
        waSelected = waId

        $('#waiterSelectedWCall').val(waId);
    }
</script>



















<!-- The phone options Modal -->
<div class="modal fade" id="optionsModal">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" style="border-radius:30px;">

            <!-- Modal body -->
            <div  style="background-color:whitesmoke; width:100%;">
               
                <div class="text-center pt-4 pb-4" style="background-color:rgb(39, 190, 175); height:300px; transform: skewY(-6deg); margin-top:-230px; margin-bottom:-20px;">
                    @if(Auth::check())
                    <a clas="profileLine" href="{{ route('profile.index') }}">
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
                        <a class=" btn btn-block {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('logout') }}" 
                            style="border:2px solid lightgray; color:black; width:50%; margin-left:25%;"
                            onclick="logoutUserQRorpa();">
                            {{ __('layouts.exit') }}
                        </a>
                        <form class="optionsAnchorPh" id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a class=" btn {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:2px solid lightgray; color:black; width:48%;"
                         href="{{ route('login') }}">{{__('layouts.login')}}</a>
                                   
                        <a class="btn  {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:4px solid rgb(39, 190, 175); color:black; width:48%;"
                         href="{{ route('register') }}">{{__('layouts.toRegister')}}</a>
                      
                    @endif
                </div>
                <script>
                    function logoutUserQRorpa(){
                        $.ajax({
                            url: '{{ route("produktet.logoutPNrSessionRemove") }}',
                            method: 'post',
                            data: {
                                _token: '{{csrf_token()}}'
                            },
                            success: () => {
                                event.preventDefault();
                                document.getElementById('logout-form').submit();
                            },
                            error: (error) => { console.log(error); }
                        });
                    }
                </script>




                <div style="background-color:whitesmoke; width:100%;" class="mt-2">
                    @if(isset($_SESSION["Res"]) && isset($_SESSION["t"]))
                        @if(Auth::check())<!--Restorant(Yes)  User(Yes) -->
                            @if(auth()->user()->role == 9)
                                <a onclick="SAPageOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ route('manageProduktet.index') }}"><i class="fas fa-columns"></i> {{__('layouts.restaurantManagement')}}</a>   
                            @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3)
                                <a onclick="APageOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('dash.index')}}"><i class="fas fa-columns"></i> {{__('layouts.adminMenu')}}</a>       
                            @endif
                            @if(Auth::user()->role != 9)
                                @if(isset($_SESSION["Res"]) && $_SESSION["Res"] != 22 && $_SESSION["Res"] != 23)
                                <a onclick="WaiterCallsOpenClick()" style="width:100%;" class="optionsAnchorPh" data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>   
                                @endif
                                <a onclick="CartOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('cart')}}"><i class="fas fa-shopping-cart"></i> {{__('layouts.cart')}}
                                    @if(count(Cart::content()) > 0)
                                        <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;">
                                        {{ count(Cart::content()) }}</span>
                                    @endif
                                </a>
                                <a onclick="MyOrdersOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ url('/home') }}"><i class="fas fa-utensils"></i> {{__('layouts.orders')}}</a>
                            @endif
                            @if(isset($_SESSION["Res"]) && $_SESSION["Res"] != 22 && $_SESSION["Res"] != 23)
                            <a class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="/tableRezIndex?Res={{$_SESSION['Res']}}"><i class="fas fa-table"></i> {{__('layouts.tableReservation')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                            @endif
                        @else<!--Restorant(Yes)  User(No) -->
                            @if(isset($_SESSION["Res"]) && $_SESSION["Res"] != 22 && $_SESSION["Res"] != 23)
                            <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh " data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                            @endif
                            <a onclick="CartOpenClick()" class="optionsAnchorPh  {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ route('cart') }}"><i class="fas fa-shopping-cart"></i> {{__('layouts.cart')}}
                                @if(  count(Cart::content()) > 0)
                                    <sup>  <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;"> {{ count(Cart::content()) }}</span></sup>
                                @endif
                            </a>   
                            <a onclick="TrackOrderOpenClick()" class="optionsAnchorPh  {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ route('trackOrder.Home') }}"><i class="fas fa-file-contract"></i> {{__('layouts.trackOrder')}}</a>
                            @if(isset($_SESSION["Res"]) && $_SESSION["Res"] != 22 && $_SESSION["Res"] != 23)
                            <a class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="/tableRezIndex?Res={{$_SESSION['Res']}}"><i class="fas fa-table"></i> {{__('layouts.tableReservation')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                            @endif
                        @endif
                    @else
                        @if(Auth::check())<!--Restorant(No)  User(Yes) -->
                            @if(auth()->user()->role == 9)
                                <a onclick="SAPageOpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{ route('manageProduktet.index') }}"><i class="fas fa-columns"></i> {{__('layouts.restaurantManagement')}}</a>
                            @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3 || auth()->user()->role == 15)
                                <a onclick="APageOpenClick()" class="optionsAnchorPh  {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('dash.index')}}"><i class="fas fa-columns"></i> {{__('layouts.adminMenu')}}</a>
                                @if(isset($_SESSION["Res"]) && $_SESSION["Res"] != 22 && $_SESSION["Res"] != 23)
                                <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh" data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                                @endif
                            @elseif(auth()->user()->role == 1)
                                @if(isset($_SESSION["Res"]) && $_SESSION["Res"] != 22 && $_SESSION["Res"] != 23)
                                <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh " data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                                @endif
                            @endif
                            @if(isset($_SESSION["Res"]) && $_SESSION["Res"] != 22 && $_SESSION["Res"] != 23)
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                            @endif
                        @else<!--Restorant(No)  User(No) -->
                            @if(isset($_SESSION["Res"]) && $_SESSION["Res"] != 22 && $_SESSION["Res"] != 23)
                            <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh" data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_SESSION['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                            @endif
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


</body>
