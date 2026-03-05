<?php

    use Illuminate\Support\Facades\Auth;
    use App\Orders;
    use Carbon\Carbon;
    use App\accessControllForAdmins;
    use App\tablesAccessToWaiters;
    
    use Jenssegers\Agent\Agent;
    $agent = new Agent();


?>
@if(!Auth::check() || Auth::user()->role != 54)
  <?php
    header("Location: ".route('login'));
    exit();
  ?>
@endif

<!DOCTYPE html>
<html lang="de" translate="no">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="description" content="">
        <meta name="author" content="">

        <title> {{__('adminP.dashboard')}}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Custom fonts for this template-->
        
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

         <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
         @include('fontawesome')


        <script src="https://js.pusher.com/6.0/pusher.min.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

        <script src="{{ asset('js/app.js') }}" defer></script>

        <?php session_start();?>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

        <!-- swiper library -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

        <style>
        .optionsAnchorPh{
            color:black;
            text-decoration:none;
            opacity:0.85;
            font-weight: bold;
            font-size:17px;
            width: 100%;
            display: block;

            padding: 10px 0px 10px 50px;
            background-color: white;
            background-size: cover;
            margin-bottom: 10px;
        }
        .optionsAnchorPh:hover{
            opacity:0.100;
            text-decoration:none;
            color:black;
            
        }
    </style>

        <!-- Matomo -->
      
        <!-- End Matomo Code -->
    </head>

    <body style="overflow-x:hidden;">

        <!-- cook 1 / later automatic selection -->
        @if (Auth::user()->cookPanV == 1)
            <!-- Version 1 -->
                    
            @if($agent->isMobile() && !$agent->isTablet())
                <div id="allTelCook">
                    @include('adminPanelCook.v1.partsTel.topNavbar')
                    @include('adminPanelCook.v1.partsTel.njoftimetCook')
                    <div id="contentCook">
                        @if(Request::is('cookPanelIndexCook'))
                            @include('adminPanelCook.v1.partsTel.firstPage')
                        @elseif(Request::is('cookPanelIndexCookT')) 
                            @include('adminPanelCook.v1.partsTel.takeawayP')
                        @elseif(Request::is('cookPanelIndexCookD'))
                            @include('adminPanelCook.v1.partsTel.deliveryP')
                        @endif
                    </div>
                </div>
            @elseif($agent->isTablet())
                <div id="allDesktopCook">
                    @include('adminPanelCook.v1.partsTablet.topNavbar')
                    @include('adminPanelCook.v1.partsTablet.njoftimetCook')
                    <div id="contentCook">
                        @if(Request::is('cookPanelIndexCook')) 
                            @include('adminPanelCook.v1.partsTablet.firstPage')
                        @elseif(Request::is('cookPanelIndexCookT'))
                            @include('adminPanelCook.v1.partsTablet.takeawayP')
                        @elseif(Request::is('cookPanelIndexCookD'))
                            @include('adminPanelCook.v1.partsTablet.deliveryP')
                        @endif
                    </div>
                </div>
            @else
                <div id="allDesktopCook">
                    @include('adminPanelCook.v1.parts.topNavbar')
                    @include('adminPanelCook.v1.parts.njoftimetCook')
                    <div id="contentCook">
                        @if(Request::is('cookPanelIndexCook'))
                            @include('adminPanelCook.v1.parts.firstPage')
                        @elseif(Request::is('cookPanelIndexCookT'))
                            @include('adminPanelCook.v1.parts.takeawayP')
                        @elseif(Request::is('cookPanelIndexCookD'))
                            @include('adminPanelCook.v1.parts.deliveryP')
                        @endif
                    </div>
                </div>
            @endif




            
        @elseif(Auth::user()->cookPanV == 2)

            @if(TRUE)
                <!-- Version 2 32 inch -->
                <div id="allDesktopCook">
                    @if($agent->isMobile())
                    @include('adminPanelCook.v2_32inch.partsTel.topNavbar')
                    @else
                    @include('adminPanelCook.v2_32inch.parts.topNavbar')
                    @endif
                    @include('adminPanelCook.v2_32inch.parts.njoftimetCook')
                    <div id="contentCook">
                        @if(Request::is('cookPanelIndexCook'))
                            @include('adminPanelCook.v2_32inch.parts.firstPage')
                        @elseif(Request::is('cookPanelIndexCookNotConf'))
                            @include('adminPanelCook.v2_32inch.parts.firstPageNotConf')

                        @elseif(Request::is('cookPanelIndexCookT'))
                            @include('adminPanelCook.v2_32inch.parts.takeawayP')
                        @elseif(Request::is('cookPanelIndexCookTNotConf'))
                            @include('adminPanelCook.v2_32inch.parts.takeawayPNotConf')

                        @elseif(Request::is('cookPanelIndexCookD'))
                            @include('adminPanelCook.v2_32inch.parts.deliveryP')
                        @endif
                    </div>
                </div>
              








                
            @else
                <!-- Version 2 -->
                @if($agent->isMobile() && !$agent->isTablet())
                    <div id="allTelCook">
                        @include('adminPanelCook.v2.partsTel.topNavbar')
                        @include('adminPanelCook.v2.partsTel.njoftimetCook')
                        <div id="contentCook">
                            @if(Request::is('cookPanelIndexCook'))
                                @include('adminPanelCook.v2.partsTel.firstPage')
                            @elseif(Request::is('cookPanelIndexCookNotConf'))
                                @include('adminPanelCook.v2.partsTel.firstPageNotConf')

                            @elseif(Request::is('cookPanelIndexCookT'))
                                @include('adminPanelCook.v2.partsTel.takeawayP')
                            @elseif(Request::is('cookPanelIndexCookTNotConf'))
                                @include('adminPanelCook.v2.partsTel.takeawayPNotConf')

                            @elseif(Request::is('cookPanelIndexCookD'))
                                @include('adminPanelCook.v2.partsTel.deliveryP')
                            @endif
                        </div>
                    </div>
                @elseif($agent->isTablet())
                    <div id="allDesktopCook">
                        @include('adminPanelCook.v2.partsTablet.topNavbar')
                        @include('adminPanelCook.v2.partsTablet.njoftimetCook')
                        <div id="contentCook">
                            @if(Request::is('cookPanelIndexCook'))
                                @include('adminPanelCook.v2.partsTablet.firstPage')
                            @elseif(Request::is('cookPanelIndexCookNotConf'))
                                @include('adminPanelCook.v2.partsTablet.firstPageNotConf')

                            @elseif(Request::is('cookPanelIndexCookT'))
                                @include('adminPanelCook.v2.partsTablet.takeawayP')
                            @elseif(Request::is('cookPanelIndexCookTNotConf'))
                                @include('adminPanelCook.v2.partsTablet.takeawayPNotConf')

                            @elseif(Request::is('cookPanelIndexCookD'))
                                @include('adminPanelCook.v2.partsTablet.deliveryP')
                            @endif
                        </div>
                    </div>
                @else
                    <div id="allDesktopCook">
                        @include('adminPanelCook.v2.parts.topNavbar')
                        @include('adminPanelCook.v2.parts.njoftimetCook')
                        <div id="contentCook">
                            @if(Request::is('cookPanelIndexCook'))
                                @include('adminPanelCook.v2.parts.firstPage')
                            @elseif(Request::is('cookPanelIndexCookNotConf'))
                                @include('adminPanelCook.v2.parts.firstPageNotConf')

                            @elseif(Request::is('cookPanelIndexCookT'))
                                @include('adminPanelCook.v2.parts.takeawayP')
                            @elseif(Request::is('cookPanelIndexCookTNotConf'))
                                @include('adminPanelCook.v2.parts.takeawayPNotConf')

                            @elseif(Request::is('cookPanelIndexCookD'))
                                @include('adminPanelCook.v2.parts.deliveryP')
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        @endif

        <script src="{{ asset('js/app.js') }}" defer></script>
        <input type="hidden" value="{{Auth::user()->sFor}}" id="theResId">

    </body>
</html>