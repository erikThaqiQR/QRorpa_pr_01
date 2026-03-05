
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
    <!-- <script src="https://kit.fontawesome.com/d40577511e.js" crossorigin="anonymous"></script> -->
    @include('fontawesome')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>

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

    .waiterCallWa::hover{
        cursor: pointer;
    }

    </style>


</head>

<script>
    var waSelected = 0;
</script>

<body style="background-color:rgb(39,190,174);">


<?php 
    use App\User;
    use App\tablesAccessToWaiters;
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
 ?>
        @if(isset($_SESSION['Res']) && isset($_SESSION['t']))
            <input type="hidden" id="theRes" value="{{$_SESSION['Res']}}">
            <input type="hidden" id="theTable" value="{{$_SESSION['t']}}">
        @endif
        @if(isset($_SESSION['Res']))
            <input type="hidden" id="theRes" value="{{$_SESSION['Res']}}">
        @endif

        <?php
            if(isset($_SESSION['Res'])){
                $theRestaurnatAppOrder = $_SESSION['Res'];
            }
        ?>


        <nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-left">
                        <?php
                            if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
                                echo '<a class="navbar-brand" href="/?Res='.$theRestaurnatAppOrder.'&t='.$_SESSION["t"].'">';
                            }else if(isset($_SESSION['Res'])){
                                echo '<a class="navbar-brand" href="/public/Delivery?Res='.$theRestaurnatAppOrder.'">';
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







<!-- Modal -->
<div class="modal" id="adminPayGetReceipt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding-top: 70px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">
                    <strong>{{__('layouts.adminPayGetReceiptTxt01')}} <br></strong>
                </h5>
            </div>
            <div class="modal-body d-flex justify-content-between" >
                <button type="button" style="width: 49%;" class="btn btn-secondary" data-dismiss="modal">{{__('layouts.close')}}</button>
                <form style="width: 49%;" method="POST" action="{{ route('receipt.getReceipt') }}">
                    {{ csrf_field()}}
                    <input type="hidden" value="1" name="orId" id="adminPayGetReceiptOrIdInput">
                    <button type="submit" style="width: 100%;" class="text-center btn btn-outline-success"> {{__('layouts.download')}} .pdf </button>
                </form>
            </div>
        </div>
    </div>
</div>






@if(isset($_SESSION['Res']) && isset($_SESSION['t']))
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
                        <?php
                           
                                $theResIdnn = $_SESSION['Res'];
                                $theTabNrnn = $_SESSION['t'];                
                        ?>
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




    @if(isset($_SESSION['Res']) && isset($_SESSION['t']))
        <input type="hidden" value="{{$_SESSION['Res']}}" id="RestoIdId">
        <input type="hidden" value="{{$_SESSION['t']}}" id="TableIdId">
    @endif

    @if(isset($_SESSION["phoneNrVerified"]))
        <input type="hidden" value="{{$_SESSION['phoneNrVerified']}}" id="verifiedNr007">
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
                            onclick="logoutUserQRorpa(); event.preventDefault(); document.getElementById('logout-form').submit();">
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
                <script>
                    function logoutUserQRorpa(){
                        $.ajax({
                            url: '{{ route("produktet.logoutPNrSessionRemove") }}',
                            method: 'post',
                            data: {
                                _token: '{{csrf_token()}}'
                            },
                            success: () => {
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
                            @else 
                                <a onclick="profileOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" 
                                    href="{{ route('profile.index') }}"><i class="far fa-user-circle"></i> {{__('layouts.goToProfile')}}</a>     
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
                                <a onclick="profileOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" 
                                    href="{{ route('profile.index') }}"><i class="far fa-user-circle"></i> {{__('layouts.goToProfile')}}</a>
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



















    
    @yield('content')

    @include('cartComp.appOrdersNotiCheck')

    @yield('extra-js')












    @include('inc.footer')
    
                <script>
                   
                </script>

                <div id="confirmTCReq01" class="alert alert-success p-4" style="position:absolute; top:100px; width:100%; font-weight:bold; font-size:large; display:none;">
                    {{__('layouts.thanksRequestNotifySoon')}}
                </div>
                <div id="responseTCReqError" class="alert alert-danger p-4" style="position:absolute; top:100px; width:100%; font-weight:bold; font-size:large; display:none;"></div>
                <div id="responseTCReqSuccess" class="alert alert-success p-4" style="position:absolute; top:100px; width:100%; font-weight:bold; font-size:large; display:none;"></div>
                
                <div id="adminToClientMsg" class="alert alert-info p-4 " 
                    style="position:fixed; top:100px; width:100%; border-radius:15px; font-weight:bold; z-index:9999999;
                         font-size:large; display:none; border:2px solid rgb(72,81,87,0.6)">
                    <p style="font-weight:bold; color:rgb(39, 190, 175);" class="text-center">
                        {{__('layouts.messageFromStaff')}}</p>
                    <p style="color:rgb(72, 81, 87);" id="adminToClientMsgText"></p>
                    <input type="hidden" id="adminToClientMsgAdmin">
                    <div class="d-flex justify-content-between">
                        <button onclick="closeMSGAC()" style="width:48%;" class="btn btn-danger">{{__('layouts.close')}}</button>
                        <button onclick="MSGACsendAnswer01()" style="width:48%;" class="btn btn-info">{{__('layouts.respond')}}</button>
                    </div>
                    <div class="mt-1 mb-1" style="display:none;" id="sendAntwortenToAdmin">
                        <label style="color:rgb(39, 190, 175); width:100%" class="text-center" for="">{{__('layouts.respond')}}</label>
                        <textarea name="" id="MSGACsendAnswerText" style="width:100%;" rows="2"></textarea>
                        <div style="display:none;"  id="MSGACsendAnswerTextError01"  class="alert alert-danger text-center">{{__('layouts.writeAnswer')}}</div>
                        <button onclick="MSGACsendAnswer02()" class="btn btn-block mt-2 btn-success">{{__('layouts.send')}}</button>
                    </div>
                </div>
</body>
</html>









