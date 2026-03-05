@include('words')
<!DOCTYPE html>
<html lang="de" translate="no">
    <head>
        <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
            <meta name="csrf-token" content="{{ csrf_token() }}">


        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <link href="{{ asset('css/ratings.css') }}" rel="stylesheet">

        <!-- swiper library -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            
            <!-- Lazy Load Library  -->
            <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>

        <!-- <link rel="icon" href="http://example.com/favicon.png"> -->



            <title>{{ config('app.name', "__('layouts.tableReservation')") }}</title>

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
            <link href="{{ asset('css/rightModal.css') }}" rel="stylesheet">


            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-173569880-1"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-173569880-1');
            </script>


            <!--Duhet me shtu edhe pjesen e hashit apo nenshkrimit digjital ne Production-->
            <?php 
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
            ?>

    </head>
    <body>
        <?php
            if(isset($_GET['Res'])){
                $theResId = $_GET['Res'];
            }
            use App\Restorant;
            use App\TableReservation;
            use Carbon\Carbon;
        ?>

        @if(isset($_SESSION['t']))
        <input type="hidden" id="theRes" value="{{ $_GET['Res']}}">
        <input type="hidden" id="theTable" value="{{$_SESSION['t']}}">
        @endif
        
        <nav class="navbar navbar-expand-smc p-3" style="background-color:rgb(39,190,175);">
           
            @if(isset($_SESSION['t']))
                <a class="navbar-brand" href="/?Res={{$theResId}}&t={{$_SESSION['t']}}"> <img style="width:140px" src="/storage/images/logo_QRorpa_wh.png" alt=""></a>
            @else
                <a class="navbar-brand" href="/firstPIndex"> <img style="width:140px" src="/storage/images/logo_QRorpa_wh.png" alt=""></a>
            @endif

            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <!-- <span style="color:white;" class="navbar-toggler-icon"></span> -->
                <i class="fas fa-bars" style="color:#fff; font-size:28px;"></i>
            </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse text-center" id="collapsibleNavbar" >
                <ul  class="navbar-nav text-center">
                    <!-- <li class="nav-item text-right">
                        <button style="color:white; font-size:21px;" class="nav-link btn btn-default" data-toggle="modal" data-target="#newReservationReq">
                            <i class="far fa-caret-square-right"></i> {{__('layouts.tableReservationRequest')}}</button>
                    </li> -->
                    <li class="nav-item text-right">
                        <button style="color:white; font-size:21px;" class="nav-link btn btn-default shadow-none" data-toggle="modal" data-target="#reservationList"> 
                            <i class="fas fa-list-ul"></i> {{__('layouts.reservationList')}}
                        </button>
                    </li>
                    
                </ul>
            </div>
        </nav>








        
        @yield('content')







            <input type="hidden" value="{{$theResId}}" id="theResId">










        <!-- The List Modal -->
        <div class="modal" id="reservationList">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><strong> {{__('layouts.reservationList')}} </strong> <br>  {{Restorant::find($theResId)->emri}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body ">
                        @foreach(TableReservation::where([['toRes',$theResId],['status',1],['dita', '>=', Carbon::today()]])->get()->sortBy('dita') as $tabRezz)
                            <div class="d-flex justify-content-between flex-wrap p-1 mb-1" style="border:1px solid lightgray; border-radius:10px;">
                                <p style="width:20%; ">{{__('layouts.table')}} <strong> {{$tabRezz->tableNr}} </strong></p>
                                <p style="width:40%; font-weight:bold;" class="text-center">{{explode('-',$tabRezz->dita)[2]}} / {{explode('-',$tabRezz->dita)[1]}} / {{explode('-',$tabRezz->dita)[0]}}</p>
                                <p style="width:40%; font-weight:bold;" class="text-center">{{$tabRezz->koha01}} {{__('layouts.bis')}} {{$tabRezz->koha02}}</p>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>










                <script>
                    // Enable pusher logging - don't include this in production
                    // Pusher.logToConsole = true;
                    var thisRestaurant = $('#theRes').val();
                    var thisTable = $('#theTable').val();

                    var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {
                        cluster: 'eu'
                    });
                    var channel = pusher.subscribe('tableChCLChanel');
                    channel.bind('App\\Events\\clNewTab', function(data) {
                        // alert(thisRestaurant+' '+thisTable);
                        var dataJ = JSON.stringify(data);
                        var dataJ2 = JSON.parse(dataJ);

                        var d2d = dataJ2.text.split('||');
                        if (thisRestaurant == d2d[0] && thisTable == d2d[1]) {
                            if(d2d[2] == 'userError'){
                                $('#responseTCReqError').html(d2d[3]);
                                $('#responseTCReqError').show(250).delay(3000).hide(250);
                                $("#chngTableMBody").load(location.href+" #chngTableMBody>*","");
                                
                            }else if(d2d[2] == 'userSuccess'){
                                $('#responseTCReqSuccess').html(d2d[3]);
                                $('#responseTCReqSuccess').show(250).delay(3000).hide(250); 

                                setTimeout(function(){ 
                                    window.location = "/?Res="+d2d[0]+"&t="+d2d[4];
                                 }, 4000);
                            }else if(d2d[2] == 'userMsg'){
                                // $('#responseTCReqSuccess').html(d2d[3]);
                                navigator.vibrate(1000);
                                $('#adminToClientMsg').show(250); 
                                $('#adminToClientMsgText').html(d2d[3]);
                            }
                        }
                    });

                    function closeMSGAC(){
                        $('#adminToClientMsg').hide(250); 
                    }
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
                    <button onclick="closeMSGAC()" class="btn btn-block btn-outline-danger">{{__('layouts.close')}}</button>
                </div>






   

    </body>
</html>
