<?php
    if(!Auth::check()){
        header("Location: ".route('login'));
        exit();
    }
    if(Auth::User()->role != 33){
        header("Location: ".route('menu'));
        exit();
    }
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title> Dashboard</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Custom fonts for this template-->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

        <!-- <script src="https://kit.fontawesome.com/4686ab5ef0.js" crossorigin="anonymous"></script> -->
        @include('fontawesome')
    
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

        <script src="{{ asset('js/app.js') }}" defer></script>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>

        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('js/jquery.signature.js') }}"></script>
        <script src="{{ asset('js/jquery.ui.touch-punch.js') }}"></script>
        <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
        <link href="{{ asset('css/jquery.signature.css') }}" rel="stylesheet">

        <style>
            html, body {
                height: 100%;
                margin: 0;
            }

            /* Chrome, Safari, Edge, Opera */
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }

            /* Firefox */
            input[type=number] {
            -moz-appearance: textfield;
            }
        </style>
    </head>
  
    <body id="page-top">
        @if($agent->isMobile())
            @include('sa.saContract.partsTel.saConNavbarTel')
        @else
            @include('sa.saContract.saConNavbar')
        @endif

        <div class="d-flex">
            @if($agent->isMobile())
                <div style="width:100%;">
                    @if(Request::is('saContractIndex'))
                        @include('sa.saContract.partsTel.saConFirstPTel')
                    @endif
                </div>
            @else
            <div style="width:18%;">
                @include('sa.saContract.saConSidebar')
            </div>
            <div style="width:87%; margin-left:-5%; border-top-left-radius:30px; background-color:white;">
                @if(Request::is('saContractIndex'))
                    @include('sa.saContract.saConFirstP')
                @endif

            </div>
            @endif
        </div>
        <script src="{{ asset('js/app.js') }}" defer></script>
    </body>

 </html>