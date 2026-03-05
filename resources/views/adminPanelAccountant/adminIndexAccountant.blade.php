<?php

    use Illuminate\Support\Facades\Auth;
    use App\Orders;
    use Carbon\Carbon;
    use App\accessControllForAdmins;
    use App\tablesAccessToWaiters;
    
    use Jenssegers\Agent\Agent;
    $agent = new Agent();

    $myTablesWaiter = array();
    foreach(tablesAccessToWaiters::where('waiterId',Auth::user()->id)->get() as $oneT){ array_push($myTablesWaiter,$oneT->tableNr); }
?>
@if(!Auth::check() || Auth::user()->role != 53)
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
 
        <!-- <script src="https://kit.fontawesome.com/d40577511e.js" crossorigin="anonymous"></script> -->
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

        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('js/jquery.signature.js') }}"></script>
        <script src="{{ asset('js/jquery.ui.touch-punch.js') }}"></script>
        <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
        <link href="{{ asset('css/jquery.signature.css') }}" rel="stylesheet"/>

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


    </head>

    @if($agent->isMobile())
        @include('adminPanelAccountant.indexParts.optionsModalTel')
    @else 
        @include('adminPanelAccountant.indexParts.optionsModal')
    @endif

    <body>





    <div style="position:absolute; top:20%; left:30%; right:30%; width:40%; z-index:999999; background-color:rgba(212,237,218,255); border-radius:10px;
        font-size:1.4rem; color:rgb(72,81,87); display:none;" class="text-center p-4" id="alertClWantsToPay">

        <div class="text-right">
            <i onclick="exitAlertClWantsToPay()" class="fas fa-times-circle hoverPointer"></i>
        </div>
        <strong>Ein Kunde von Tisch <span id="alertClWantsToPayTNr"></span> hat versucht, seine Bestellungen zu bezahlen, konnte dies jedoch nicht tun, da einige der Bestellungen noch nicht bestätigt wurden</strong>
        <hr>
        <strong>Stellen Sie sicher, dass Sie ihre Bestellungen bestätigen, damit sie fortfahren können</strong>
    </div>
    <script>
        function exitAlertClWantsToPay(){
            $('#alertClWantsToPay').hide(50);
        }
    </script>


    <div style="position:absolute; top:19%; left:5%; right:5%; width:90%; z-index:999999; background-color:rgba(212,237,218,255); border-radius:7px;
        font-size:1rem; color:rgb(72,81,87); display:none;" class="text-center p-3" id="alertClWantsToPayTel">

        <div class="text-right">
            <i onclick="exitAlertClWantsToPayTel()" class="fas fa-times-circle hoverPointer"></i>
        </div>
        <strong>Ein Kunde von Tisch <span id="alertClWantsToPayTNrTel"></span> hat versucht, seine Bestellungen zu bezahlen, konnte dies jedoch nicht tun, da einige der Bestellungen noch nicht bestätigt wurden</strong>
        <hr>
        <strong>Stellen Sie sicher, dass Sie ihre Bestellungen bestätigen, damit sie fortfahren können</strong>
        </div>
    <script>
        function exitAlertClWantsToPayTel(){
            $('#alertClWantsToPayTel').hide(50);
        }
    </script>


        @if(!$agent->isMobile() || $agent->isTablet())
            <div id="allDesktop">
                <div class="DashSideMenu" id="DashSideMenuDesktop">
                    @include('adminPanelAccountant.parts.side')
                </div>
                @include('adminPanelAccountant.parts.topNavbar')
                <div id="content">
                    <div class="alert alert-success p-3" role="alert" style="position:fixed; width:40%; left:30%; z-index:100; border: 2px solid rgb(39,190,175); display:none;" id="adminPAlertWindow">
                        <h4 class="alert-heading d-flex">
                            <span style="width:90%;">{{__('adminP.notificationForYou')}}</span>
                            <span onclick="closeAdminPAlertWindow()"style="width:10%; font-weight:bold;" class="text-right">X</span>
                        </h4> 
                        <hr>
                        <div >
                            <div class="card">
                                <ul class="list-group list-group-flush" id="adminPAlertWindowMessages">
                                <li class="list-group-item d-flex justify-content-between">
                                    <p style="width: 51%;">{{__('adminP.tablesNeedsAttention')}}</p> 
                                    <a href="{{ route('admWoMng.indexAdmMngPageWaiter') }}" class="btn btn-light" style="width: 45%;">{{__('adminP.goToOrders')}}</a>
                                </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        function closeAdminPAlertWindow(){ $('#adminPAlertWindow').hide(500);}
                    </script>

                    @if(Request::is('AccountantStatistics')) <!-- Statistics -->
                        @include('adminPanelAccountant.parts.porositStat')
                    @elseif(Request::is('AccountantStatisticsDash1'))
                        @include('adminPanelAccountant.parts.dashboard01')
                    @elseif(Request::is('AccountantStatisticsDash2'))
                        @include('adminPanelAccountant.parts.dashboard02')
                    @elseif(Request::is('AccountantCanceledOrders'))
                        @include('adminPanelAccountant.parts.dashboardCancelOr')
                    @elseif(Request::is('AccountantstatsBillsRecs'))
                        @include('adminPanelAccountant.parts.billRecords')

                    @elseif(Request::is('AccountantstatsDeletedIns'))
                        @include('adminPanelAccountant.parts.deletedTAIns')
                    @elseif(Request::is('AccountantstatsWaitersSales'))
                        @include('adminPanelAccountant.parts.waitersSales')
                    @elseif(Request::is('AccountantstatsEmailBillsP'))
                        @include('adminPanelAccountant.parts.emailBillsMng')
                    @elseif(Request::is('AccountantstatsReportCatsPage'))
                        @include('adminPanelAccountant.parts.categorizeReportPage')
                        
                    @elseif(Request::is('AccountantStatisticsSales')) <!-- sales Statistics -->
                        @include('adminPanelAccountant.parts.salesStatistics') 
                    @elseif(Request::is('AccountantStatisticsRepCat')) <!-- categorize the report -->
                        @include('adminPanelAccountant.parts.categorizeReportPage')

                    @elseif(Request::is('AccountantProducts'))  <!-- Content management (Products) -->
                        @include('adminPanelAccountant.manageContent.firstPage')
                    @elseif(Request::is('AccountantProducts/Order'))
                        @include('adminPanelAccountant.manageContent.firstPageOrder')
                         
                    @endif
                </div>
            </div>

        @elseif($agent->isMobile() && !$agent->isTablet())
            <div id="allTel">
                <!-- New order alert for other pages TEL -->
                <div class="alert alert-success p-2 mt-4" role="alert" style="position:fixed; width:96%; left:2%; z-index:100; border: 2px solid rgb(39,190,175); display:none;" id="adminPAlertWindowTel">
                    <h4 class="alert-heading d-flex">
                        <span style="width:90%;">{{__('adminP.notificationForYou')}}</span>
                        <span onclick="closeAdminPAlertWindowTel()"style="width:10%; font-weight:bold;" class="text-right">X</span>
                    </h4> 
                    <hr>
                    <div>
                        <div class="card">
                        <ul class="list-group list-group-flush" id="adminPAlertWindowMessagesTel">
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <p style="width: 100%;">{{__('adminP.tablesNeedsAttention')}}</p> 
                            <a href="{{ route('admWoMng.indexAdmMngPageWaiter') }}" class="btn btn-light" style="width: 100%;">{{__('adminP.goToOrders')}}</a>
                            </li>
                        </ul>
                        </div>
                    </div>
                </div>
                <script>
                function closeAdminPAlertWindowTel(){ $('#adminPAlertWindowTel').hide(500);}
                </script>
            
                @include('adminPanelAccountant.partsTel.resTelNavbar')
                @include('adminPanelAccountant.partsTel.njoftimet')


                @if(Request::is('AccountantStatistics')) <!-- Statistics-->
                    @include('adminPanelAccountant.partsTel.porositStatTel')
                @elseif(Request::is('AccountantStatisticsDash1'))
                    @include('adminPanelAccountant.partsTel.dashboard01tel')
                @elseif(Request::is('AccountantStatisticsDash2'))
                    @include('adminPanelAccountant.partsTel.dashboard02tel')
                @elseif(Request::is('AccountantCanceledOrders'))
                    @include('adminPanelAccountant.partsTel.dashboardCancelOr')
                @elseif(Request::is('AccountantstatsBillsRecs'))
                    @include('adminPanelAccountant.partsTel.billRecords')
                @elseif(Request::is('AccountantstatsDeletedIns'))
                    @include('adminPanelAccountant.partsTel.deletedTAIns')
                @elseif(Request::is('AccountantstatsWaitersSales'))
                    @include('adminPanelAccountant.partsTel.waitersSales')
                @elseif(Request::is('AccountantstatsEmailBillsP'))
                    @include('adminPanelAccountant.partsTel.emailBillsMng')

                @elseif(Request::is('AccountantStatisticsSales')) <!-- Sales statistics-->
                    @include('adminPanelAccountant.partsTel.salesStatistics')
                @elseif(Request::is('AccountantStatisticsRepCat')) <!-- categorize the report -->
                    @include('adminPanelAccountant.partsTel.categorizeReportPage')

                @elseif(Request::is('AccountantProducts')) <!-- Content management -->
                    @include('adminPanelAccountant.manageContentTel.firstPage')
                @elseif(Request::is('AccountantProducts/Order'))
                    @include('adminPanelAccountant.manageContentTel.firstPageOrder')

                @endif
            </div>
        @endif


        <script src="{{ asset('js/app.js') }}" defer></script>
        <input type="hidden" value="{{Auth::user()->sFor}}" id="theResId">


        <!-- paraqitet QRkodi per faktur ne rast te RECHNUNG pageses nga admini kamarieri -->
        @if (isset($_GET["orId"]))
            <script>
                $.ajax({
                    url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                    method: 'post',
                    data: {
                        id: '{{$_GET["orId"]}}',
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = $.trim(res);
                        res2D = res.split('-8-8-');
                        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res);
                        $('#orderQRCodePicDownloadOI').val('{{$_GET["orId"]}}');
                        $('#orderQRCodePicTel').modal('show');
                    },
                    error: (error) => { console.log(error); }
                });
            </script>
        @endif

    </body>
</html>