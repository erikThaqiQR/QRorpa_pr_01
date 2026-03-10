@if(!Auth::check() || Auth::user()->role != 55)
  <?php
    header("Location: ".route('login'));
    exit();
  ?>
@endif
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
    array_push($myTablesWaiter,500);
?>

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

        <script src="https://kit.fontawesome.com/d40577511e.js" crossorigin="anonymous"></script>
         <!-- @include('fontawesome') -->


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
        <link href="{{ asset('css/jquery.signature.css') }}" rel="stylesheet">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
        <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
        <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
        
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

        .btn-outline-dark:focus{
            background-color: transparent !important;
            color: rgb(72,81,87);
        }
    </style>

    <!-- Matomo -->
     
    <!-- End Matomo Code -->

    </head>

    @if($agent->isMobile())
        @include('adminPanelWaiter.indexParts.optionsModalTel')
        <input type="hidden" id="isDeviceMobile" value="1">
    @else 
        @include('adminPanelWaiter.indexParts.optionsModal')
        <input type="hidden" id="isDeviceMobile" value="0">
    @endif

    @include('adminPanelWaiter.indexParts.orderQRCodeTel')

    <!-- Check for non valid TAB orders -->
    @include('adminPanelWaiter.indexParts.checkNonValidTABOrds')


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

    <div style="position:absolute; top:19%; left:25%; right:25%; width:50%; z-index:999999; background-color:rgba(212,237,218,255); border-radius:7px;
    font-size:1rem; color:rgb(72,81,87); display:none;" class="text-center p-3" id="CheckInFirstToAddOrdersPopUp">

        <div class="text-right">
            <i onclick="exitcheckInFirstToAddOrdersPopUp()" class="fas fa-2x fa-times-circle hoverPointer"></i>
        </div>
        <strong>Bitte checke dich ein, bevor du Produkte zum Warenkorb hinzufügen kannst.</strong>
        <hr>
        <strong>Am Ende deiner Schicht nicht vergessen: Checkout durchführen!</strong>
    </div>


    <script>
        function exitAlertClWantsToPayTel(){
            $('#alertClWantsToPayTel').hide(50);
        }
        function exitcheckInFirstToAddOrdersPopUp(){
            $('#CheckInFirstToAddOrdersPopUp').hide(50);
        }
    </script>


        @if(!$agent->isMobile() || $agent->isTablet())
            <div id="allDesktop">
                <div class="DashSideMenu" id="DashSideMenuDesktop" style="height:100vmax !important;">
                    @include('adminPanelWaiter.parts.side')
                </div>
                @include('adminPanelWaiter.parts.topNavbar')
                <div id="content" style="margin-left:3%; width:97%;">
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

                    @if(Request::is('admWoMngStatistics01Waiter')) <!-- Statistics -->
                        @include('adminPanelWaiter.parts.porositStat')
                    @elseif(Request::is('admWoMngStatistics02Waiter'))
                        @include('adminPanelWaiter.parts.dashboard01')
                    @elseif(Request::is('admWoMngStatistics03Waiter'))
                        @include('adminPanelWaiter.parts.dashboard02')
                    @elseif(Request::is('admWoMngCanceledOrdersWaiter'))
                        @include('adminPanelWaiter.parts.dashboardCancelOr')

                    @elseif(Request::is('WaiterstatsDeletedTAProdsPage'))
                        @include('adminPanelWaiter.parts.deletedTAIns')

                    @elseif(Request::is('AdminchngPayMethodForOrdersPageWa'))
                        @include('adminPanelWaiter.parts.payMChngInsPage')

                    @elseif(Request::is('WaiterstatsBillsRecs'))
                        @include('adminPanelWaiter.parts.billRecords')

                    @elseif(Request::is('admWoMngStatistics04Waiter')) <!-- sales Statistics -->
                        @include('adminPanelWaiter.parts.salesStatistics') 

                    @elseif(Request::is('categorizeReportWaiter')) <!-- categorize the report -->
                        @include('adminPanelWaiter.parts.categorizeReportPage')

                    @elseif(Request::is('adminWoSaMsgWaiter')) <!-- Talk to QRorpa -->
                        @include('adminPanelWaiter.parts.talkToQRorpa')

                    @elseif(Request::is('admWoMngIndexWaiter') || Request::is('dashboard2') || Request::is('dashboard3')) <!-- Orders -->
                        @include('adminPanelWaiter.tablePage.tablePageIndex')
                    @elseif(Request::is('admWoMngOrdersListWaiter'))
                        @include('adminPanelWaiter.parts.porositDashList')





                    @elseif(Request::is('admWoMngTakeawayWaiter'))
                        @include('adminPanelWaiter.parts.dashboardTakeaway')
                    @elseif(Request::is('admWoMngDeliveryWaiter'))
                        @include('adminPanelWaiter.parts.dashboardDelivery')

                    @elseif(Request::is('giftCardMngAdminWa')) <!-- Gift Card -->
                        @include('adminPanelWaiter.giftCard.gcIndex')

                    @elseif(Request::is('admWoMngOrdersFreeTables')) <!-- Free Tabels -->
                        @include('adminPanelWaiter.parts.freeTables')

                    @elseif(Request::is('adminWoRecomendetProdWaiter')) <!-- Recomendet products -->
                        @include('adminPanelWaiter.parts.recomendet')

                    @elseif(Request::is('adminWoRechnungPageWaiter')) <!-- Rechnung mng page -->
                        @include('adminPanelWaiter.parts.emailBillsMng')

                    @elseif(Request::is('adminWoWaiterCallsWaiter'))  <!-- Waiter -->
                        @include('adminPanelWaiter.partsKamarier.firstPage')

                    @elseif(Request::is('adminWoContentMngWaiter'))  <!-- Content management (Products) -->
                        @include('adminPanelWaiter.manageContent.firstPage')
                    @elseif(Request::is('adminWoContentMngWaiter/Order'))
                        @include('adminPanelWaiter.manageContent.firstPageOrder')

                    @elseif(Request::is('adminWoTableChngReqWaiter')) <!-- Change the Table requests -->
                        @include('adminPanelWaiter.parts.tableCHReq')

                    @elseif(Request::is('adminWoTipsWaiter')) <!-- Tips -->
                        @include('adminPanelWaiter.parts.tipsPage')

                    @elseif(Request::is('waitersSalesToday')) <!-- Barazimi nga kamarieri -->
                        @include('adminPanelWaiter.parts.todaySalesWa')
                        
                    @elseif(Request::is('adminWoTipsMonthWaiter'))
                        @include('adminPanelWaiter.parts.tipsPageMonth')

                    @elseif(Request::is('adminWoRestrictProductsWaiter')) <!--Restricted Products  -->
                        @include('adminPanelWaiter.parts.restrictProducts')

                    @elseif(Request::is('adminWoCouponsWaiter')) <!-- Cupon -->
                        @include('adminPanelWaiter.Cupon.cuponIndex')

                    @elseif(Request::is('adminWoTakeawayWaiter')) <!--Takeaway -->
                        @include('adminPanelWaiter.parts.takeawayPage')

                    @elseif(Request::is('adminWoDeliveryWaiter')) <!--Delivery -->
                        @include('adminPanelWaiter.parts.deliveryPage')

                    @elseif(Request::is('adminWoTableCapacityWaiter')) <!-- Table Capacity  -->
                        @include('adminPanelWaiter.parts.tableCapacity')

                    @elseif(Request::is('adminWoTableReservationIndexWaiter')) <!-- table reservation -->
                        @include('adminPanelWaiter.parts.tableReservation')
                    @elseif(Request::is('adminWoTableReservationListWaiter'))
                        @include('adminPanelWaiter.parts.tableReservationReq')
           
                    @elseif(Request::is('adminWoServiceRequestWaiter')) <!--Service request  -->
                        @include('adminPanelWaiter.parts.serviceReq')

                    @elseif(Request::is('adminWoCovid19Waiter')) <!-- Covid - 19 -->
                        @include('adminPanelWaiter.parts.covidsAdmin')    

                    @elseif(Request::is('NotificationsActWaiter')) <!-- Menaxhimi Njoftimeve (Aktivizim / deaktivizim) -->
                        @include('adminPanelWaiter.notifyActive.firstPage')

                    @elseif(Request::is('openCheckInOutReportsWa')) <!-- Check in/out reports -->
                        @include('adminPanelWaiter.parts.checkinoutReports')   

                    @elseif(Request::is('BillTabletsWaiter')) <!-- bill tablets management -->
                        @include('adminPanelWaiter.parts.billTabletsMng')

                    @elseif(Request::is('orderServingDevicesPageWaiter')) <!-- order serving Devices page -->
                        @include('adminPanelWaiter.orderServingDevices.indexPage')

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
            
                @include('adminPanelWaiter.indexParts.resTelNavbar')
                <!-- adminPanelWaiter.partsTel.njoftimet -->

                @if(Request::is('admWoMngStatistics01Waiter')) <!-- Statistics-->
                    @include('adminPanelWaiter.partsTel.porositStatTel')
                @elseif(Request::is('admWoMngStatistics02Waiter'))
                    @include('adminPanelWaiter.partsTel.dashboard01tel')
                @elseif(Request::is('admWoMngStatistics03Waiter'))
                    @include('adminPanelWaiter.partsTel.dashboard02tel')
                @elseif(Request::is('admWoMngCanceledOrdersWaiter'))
                    @include('adminPanelWaiter.partsTel.dashboardCancelOr')

                @elseif(Request::is('WaiterstatsDeletedTAProdsPage'))
                    @include('adminPanelWaiter.partsTel.deletedTAIns')

                @elseif(Request::is('AdminchngPayMethodForOrdersPageWa'))
                    @include('adminPanelWaiter.partsTel.payMChngInsPage')

                @elseif(Request::is('WaiterstatsBillsRecs'))
                    @include('adminPanelWaiter.partsTel.billRecords')

                @elseif(Request::is('admWoMngStatistics04Waiter')) <!-- Sales statistics-->
                    @include('adminPanelWaiter.partsTel.salesStatistics')

                @elseif(Request::is('categorizeReportWaiter')) <!-- categorize the report -->
                    @include('adminPanelWaiter.partsTel.categorizeReportPage')

                @elseif(Request::is('adminWoSaMsgWaiter')) <!-- Talk to QRorpa -->
                    @include('adminPanelWaiter.partsTel.talkToQRorpa')

                @elseif(Request::is('admWoMngIndexWaiter')) <!-- Orders Tables -->
                    @include('adminPanelWaiter.tablePageTel.tablePageIndex')
                @elseif(Request::is('admWoMngOrdersListWaiter')) 
                    @include('adminPanelWaiter.tablePageTel.porositDashList')
                    
             


                @elseif(Request::is('admWoMngTakeawayWaiter'))
                    @include('adminPanelWaiter.partsTel.dashboardTakeaway')
                @elseif(Request::is('admWoMngDeliveryWaiter'))
                    @include('adminPanelWaiter.partsTel.dashboardDelivery')

                @elseif(Request::is('giftCardMngAdminWa')) <!-- Gift Card -->
                    @include('adminPanelWaiter.giftCardTel.gcIndex')

                @elseif(Request::is('admWoMngOrdersFreeTables')) <!-- Free Tabels -->
                    @include('adminPanelWaiter.partsTel.freeTablesTel')

                @elseif(Request::is('adminWoRecomendetProdWaiter'))
                    @include('adminPanelWaiter.partsTel.recomendetTel')
                  
                @elseif(Request::is('adminWoRechnungPageWaiter')) <!-- Rechnung mng page -->
                    @include('adminPanelWaiter.partsTel.emailBillsMng')

                @elseif(Request::is('adminWoWaiterCallsWaiter')) <!-- Waiter calls -->
                    @include('adminPanelWaiter.partsKamarierTel.firstPage')

                @elseif(Request::is('adminWoContentMngWaiter')) <!-- Content management -->
                    @include('adminPanelWaiter.manageContentTel.firstPage')
                @elseif(Request::is('adminWoContentMngWaiter/Order'))
                    @include('adminPanelWaiter.manageContentTel.firstPageOrder')

                @elseif(Request::is('adminWoTableChngReqWaiter')) <!-- Table change -->
                    @include('adminPanelWaiter.partsTel.tableChangeReq')

                @elseif(Request::is('adminWoTipsWaiter')) <!-- Tips -->
                    @include('adminPanelWaiter.partsTel.tipsPageTel')
                @elseif(Request::is('adminWoTipsMonthWaiter'))
                    @include('adminPanelWaiter.partsTel.tipsPageMonthTel')

                @elseif(Request::is('waitersSalesToday')) <!-- Barazimi nga kamarieri -->
                    @include('adminPanelWaiter.partsTel.todaySalesWa')

                @elseif(Request::is('adminWoRestrictProductsWaiter')) <!--Restricted Products  -->
                    @include('adminPanelWaiter.partsTel.restrictProductsTel')

                @elseif(Request::is('adminWoCouponsWaiter')) <!-- Coupons -->
                    @include('adminPanelWaiter.Cupon.cuponIndexTel')

                @elseif(Request::is('adminWoTakeawayWaiter'))  <!-- Takeaway Products -->
                    @include('adminPanelWaiter.partsTel.takeawayPageTel')
                @elseif(Request::is('adminWoTakeawaySortingWaiter'))
                    @include('adminPanelWaiter.partsTel.takeawaySorting')

                @elseif(Request::is('adminWoDeliveryWaiter')) <!--Delivery -->
                    @include('adminPanelWaiter.partsTel.deliveryPageTel')
                @elseif(Request::is('adminWoDeliverySortingWaiter'))
                    @include('adminPanelWaiter.partsTel.deliverySorting')

                @elseif(Request::is('adminWoTableCapacityWaiter')) <!-- Table Capacity  -->
                    @include('adminPanelWaiter.partsTel.tableCapacityTel')
                
                @elseif(Request::is('adminWoTableReservationIndexWaiter')) <!-- Table reservation -->
                    @include('adminPanelWaiter.partsTel.tableReservation')
                @elseif(Request::is('adminWoTableReservationListWaiter'))
                    @include('adminPanelWaiter.partsTel.tableReservationReq')

                @elseif(Request::is('adminWoServiceRequestWaiter')) <!--Service request  -->
                    @include('adminPanelWaiter.partsTel.serviceReqTel')
                
                @elseif(Request::is('adminWoStatusWorkerWaiter')) <!--status worker -->
                    @include('adminPanelWaiter.partsTel.statusWorker')

                @elseif(Request::is('adminWoCovid19Waiter')) <!-- covid-19 -->
                    @include('adminPanelWaiter.partsTel.covidsTel')

                @elseif(Request::is('NotificationsActWaiter')) <!-- Menaxhimi Njoftimeve (Aktivizim / deaktivizim) -->
                    @include('adminPanelWaiter.notifyActiveTel.firstPage')

                @elseif(Request::is('openCheckInOutReportsWa')) <!-- Check in/out reports -->
                    @include('adminPanelWaiter.partsTel.checkinoutReports')   

                @elseif(Request::is('BillTabletsWaiter')) <!-- bill tablets management -->
                    @include('adminPanelWaiter.partsTel.billTabletsMng')

                @elseif(Request::is('orderServingDevicesPageWaiter')) <!-- order serving Devices page -->
                    @include('adminPanelWaiter.orderServingDevicesTel.indexPage')

                @endif
            </div>
        @endif


        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- <input type="hidden" value="{{Auth::user()->sFor}}" id="theResId">

        @if(Auth::user()->role == 55)
            <script>
              function sendIMActiveAP(){
                $.ajax({
                  url: '{{ route("dash.sendActiveMSG") }}',
                  method: 'post',
                  data: {
                    resId: $('#theResId').val(),
                    _token: '{{csrf_token()}}'
                  },
                  success: () => {// $("#freeProElements").load(location.href+" #freeProElements>*","");
                  },
                  error: (error) => {console.log(error);}
                });
              }
              setInterval(sendIMActiveAP,20000);
            </script>
        @endif -->


        <!-- paraqitet QRkodi per faktur ne rast te RECHNUNG pageses nga admini kamarieri -->
        @if (isset($_GET["orId"]) && isset($_GET["splitBillIn"]))
          <!-- splitBillTheFinalPay -->
            @if($agent->isMobile())
            <script>
              $.ajax({
                url: '{{ route("splitBill.displaysplitBillAfterRechnung") }}',
                method: 'post',
                data: {
                  splitBillIn: "{{$_GET['splitBillIn']}}",
                  splitBillPayLog: "{{$_GET['splitBillPayLogId']}}",
                  _token: '{{csrf_token()}}'
                },
                success: (response) => {
                  response = $.trim(response);
                  if(response == 'invalideTable'){

                  }else if(response == 'modalAlreadyShown'){

                  }else{
                    respo2D = response.split('|||');
                    if( respo2D[9] == 2){

                    }else{
                      $('#tabOrder'+respo2D[1]).modal('hide');
                      $('#splitTheBillInitiateModal').modal('show');
                      $('#splitTheBillInitiateModalTableNr').html(respo2D[1]);
                      $('#splitTheBillInitiateModalClientsNr').html(respo2D[2]);

                      $('#splitTheBillInitiateModalCancelBtn').remove();

                      $('#splitTheBillInitiateModalBody').html('');

                      $('body').addClass('modal-open');

                      var rechReturnPayM = respo2D[6];
                      var rechReturnPayM2D = rechReturnPayM.split('--88--');
                      var rechReturntips = respo2D[7];
                      var rechReturntips2D = rechReturntips.split('--88--');
                      var rechReturnGCId = respo2D[10];
                      var rechReturnGCId2D = rechReturnGCId.split('--88--');
                      var rechReturnGCAmt = respo2D[11];
                      var rechReturnGCAmt2D = rechReturnGCAmt.split('--88--');
                      var newClientLineShow = "";
                      console.log(respo2D[10]);
                      console.log(respo2D[11]);
                      for(var i = 0; i < respo2D[2]; i++) {
                        if(rechReturnPayM2D[i] !== undefined) {
                          newClientLineShow = '<div id="splitBillClientDiv1'+i+'" class="d-flex flex-wrap justify-content-between pt-2 alert-success">'+
                                                  '<div style="width:100%;">'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Kunde '+(i + 1)+'</strong></p>'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Bezahlen <span id="splitBillModalPayValueClient'+i+'">'+parseFloat(parseFloat(respo2D[8]) + parseFloat(rechReturntips2D[i])).toFixed(2)+'</span> CHF</strong></p>'+
                                                  '</div>'+
                                                  '<div id="splitBillClientDiv1_2'+i+'" style="width:100%;" class="d-flex flex-wrap justify-content-between">';
                                                    if(rechReturnPayM2D[i] == 'Barzahlungen'){
                          newClientLineShow +=       '<button id="splitBillBtn1'+i+'" style="width:33%;" class="btn btn-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Bar</button>';
                                                    }else{
                          newClientLineShow +=        '<button id="splitBillBtn1'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Bar</button>';
                                                    }
                                                    if(rechReturnPayM2D[i] == 'Kartenzahlung'){
                          newClientLineShow +=       '<button id="splitBillBtn2'+i+'" style="width:33%;" class="btn btn-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')" disabled>Karte</button>';
                                                    }else{
                          newClientLineShow +=        '<button id="splitBillBtn2'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')" disabled>Karte</button>';
                                                    }

                          newClientLineShow +=        '<button id="splitBillBtn3'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Online</button>';
                                                    
                                                    if(rechReturnPayM2D[i] == 'Auf rechnung'){
                          newClientLineShow +=       '<button id="splitBillBtn4'+i+'" style="width:49.5%;" class="btn btn-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Auf Rechnung</button>';
                                                    }else{
                          newClientLineShow +=        '<button id="splitBillBtn4'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Auf Rechnung</button>';
                                                    }
                          newClientLineShow +=        '<button id="splitBillBtn5'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Geschenkkarte</button>'+
                                                  '</div>'+
                                              '</div>'+
                                              '<div id="splitBillClientDiv2'+i+'" style="border-bottom:2px solid black;" class="d-flex flex-wrap justify-content-between pt-1 pb-2 alert-success">'+
                                                  '<div style="width:100%;" id="splitBillClientDiv2_1'+i+'">'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Tipp: <span id="splitBillModalTippValueClient'+i+'">'+parseFloat(parseFloat(rechReturntips2D[i]).toFixed(2)).toFixed(2)+'</span> CHF</strong></p>';
                                                      if(rechReturnGCAmt2D[i] > 0){
                          newClientLineShow +=        '<p id="showGCApplyP'+i+'" style="margin-bottom: 3px; font-size:1.2rem;"><strong>Geschenkkarte: <span id="splitBillModalGCValueClientShow'+i+'">'+parseFloat(rechReturnGCAmt2D[i]).toFixed(2)+'</span> CHF</strong></p>';
                                                      }
                          newClientLineShow +=    '</div>'+
                                                  '<div style="width:100%;" class="d-flex flex-wrap justify-content-between">'+
                                                      '<input type="hidden" id="splitBillTippType'+i+'" value="0">'+
                                                  '</div>'+
                                                  '<input type="hidden" id="splitBillnitiateId'+i+'" value="'+respo2D[5]+'">'+
                                                  '<input type="hidden" id="splitBillGCAppliedId'+i+'" value="'+rechReturnGCId2D[i]+'">'+
                                                  '<input type="hidden" id="splitBillGCAppliedCHFVal'+i+'" value="'+rechReturnGCAmt2D[i]+'">'+
                                              '</div>';
                        }else{
                          newClientLineShow = '<div id="splitBillClientDiv1'+i+'" class="d-flex flex-wrap justify-content-between pt-2">'+
                                                  '<div style="width:100%;">'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Kunde '+(i + 1)+'</strong></p>'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Bezahlen <span id="splitBillModalPayValueClient'+i+'">'+parseFloat(respo2D[0]).toFixed(2)+'</span> CHF</strong></p>'+
                                                  '</div>'+
                                                  '<div id="splitBillClientDiv1_2'+i+'" style="width:100%;" class="d-flex flex-wrap justify-content-between">'+
                                                      '<button id="splitBillBtn1'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Bar</button>'+
                                                      '<button id="splitBillBtn2'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')">Karte</button>'+
                                                      '<button id="splitBillBtn3'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Online</button>'+
                                                      '<button id="splitBillBtn4'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Auf Rechnung</button>'+
                                                      '<button id="splitBillBtn5'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Geschenkkarte</button>'+
                                                  '</div>'+
                                              '</div>'+
                                              '<div id="splitBillClientDiv2'+i+'" style="border-bottom:2px solid black;" class="d-flex flex-wrap justify-content-between pt-1 pb-2">'+
                                                  '<div style="width:100%;" id="splitBillClientDiv2_1'+i+'">'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong><i style="color:red;" class="fa-solid fa-circle-xmark" id="cancelTippApplyBtn'+i+'" onclick="cancelTippApply(\''+i+'\')"></i> Tipp: <span id="splitBillModalTippValueClient'+i+'">'+parseFloat(0).toFixed(2)+'</span> CHF</strong></p>'+
                                                  '</div>'+
                                                  '<div style="width:100%;" class="d-flex flex-wrap justify-content-between">'+
                                                      '<button id="splitBillTippBtn1'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+0.5+'\')">'+parseFloat(0.5).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtn2'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+1+'\')">'+parseFloat(1).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtn3'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+2+'\')">'+parseFloat(2).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtn4'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+5+'\')">'+parseFloat(5).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtn5'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+10+'\')">'+parseFloat(10).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtnCostume'+i+'" style="width:74.4%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none">'+
                                                          '<input id="splitBillTippInputCostume'+i+'" step="0.05" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostumeTipStafSplitBill(\''+i+'\',this.value)" style="width:95%; border:none;" placeholder="Gesamt mit Tipp">'+
                                                      '</button>'+
                                                      '<input type="hidden" id="splitBillTippType'+i+'" value="0">'+
                                                  '</div>'+
                                                  '<div style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center" id="splitTheBillError01'+i+'">'+
                                                      '<strong>Geben Sie zuerst einen gültigen Wert ein!, Gesamtpreis nach dem Trinkgeld</strong>'+
                                                  '</div>'+
                                                  '<div id="splitTheBillPayAtPOS'+i+'" class="alert alert-info text-center mt-1" style="display:none; width:100%;">'+
                                                      '<strong>Schließen Sie die Zahlung am POS-Terminal ab!</strong>'+
                                                  '</div>'+
                                                  '<input type="hidden" id="splitBillnitiateId'+i+'" value="'+respo2D[5]+'">'+
                                                  '<input type="hidden" id="splitBillGCAppliedId'+i+'" value="0">'+
                                                  '<input type="hidden" id="splitBillGCAppliedCHFVal'+i+'" value="0">'+
                                              '</div>';
                        }
                          $('#splitTheBillInitiateModalBody').append(newClientLineShow);
                      }

                      $('#splitTheBillInitiateModalOriginalPriceShow').html(parseFloat(respo2D[3]).toFixed(2));
                      $('#splitTheBillInitiateModalPayPriceShow').html(parseFloat(respo2D[4]).toFixed(2));
                    }



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
                          $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                          $('#orderQRCodePicDownloadOI').val('{{$_GET["orId"]}}');
                          $('#orderQRCodePicTel').modal('show');

                          if(respo2D[9] == 1 || respo2D[9] == 0){
                              $('#OrQRCodeModalStillOpen').val(1);
                          }
                      },
                      error: (error) => { console.log(error); }
                    });




                  }
                },error: (error) => { console.log(error); }
				      });
            </script>


            @else
            <!-- Desktop version -->
            <!-- Desktop version -->
            <!-- Desktop version -->
            <script>
              $.ajax({
                url: '{{ route("splitBill.displaysplitBillAfterRechnung") }}',
                method: 'post',
                data: {
                  splitBillIn: "{{$_GET['splitBillIn']}}",
                  splitBillPayLog: "{{$_GET['splitBillPayLogId']}}",
                  _token: '{{csrf_token()}}'
                },
                success: (response) => {
                  response = $.trim(response);
                  if(response == 'invalideTable'){

                  }else if(response == 'modalAlreadyShown'){

                  }else{
                    respo2D = response.split('|||');
                    if( respo2D[9] == 2){

                    }else{
                      $('#tabOrder'+respo2D[1]).modal('hide');
                      $('#splitTheBillInitiateModal').modal('show');
                      $('#splitTheBillInitiateModalTableNr').html(respo2D[1]);
                      $('#splitTheBillInitiateModalClientsNr').html(respo2D[2]);

                      $('#splitTheBillInitiateModalCancelBtn').remove();

                      $('#splitTheBillInitiateModalBody').html('');

                      $('body').addClass('modal-open');

                      var rechReturnPayM = respo2D[6];
                      var rechReturnPayM2D = rechReturnPayM.split('--88--');
                      var rechReturntips = respo2D[7];
                      var rechReturntips2D = rechReturntips.split('--88--');
                      var rechReturnGCId = respo2D[10];
                      var rechReturnGCId2D = rechReturnGCId.split('--88--');
                      var rechReturnGCAmt = respo2D[11];
                      var rechReturnGCAmt2D = rechReturnGCAmt.split('--88--');
                      var newClientLineShow = "";
                      console.log(respo2D[10]);
                      console.log(respo2D[11]);
                      for(var i = 0; i < respo2D[2]; i++) {
                        if(rechReturnPayM2D[i] !== undefined) {
                          newClientLineShow = '<div id="splitBillClientDiv1'+i+'" class="d-flex justify-content-between pt-2 alert-success">'+
                                                  '<div style="width:30%;">'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Kunde '+(i + 1)+'</strong></p>'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Bezahlen <span id="splitBillModalPayValueClient'+i+'">'+parseFloat(parseFloat(respo2D[8]) + parseFloat(rechReturntips2D[i])).toFixed(2)+'</span> CHF</strong></p>'+
                                                  '</div>'+
                                                  '<div id="splitBillClientDiv1_2'+i+'" style="width:70%;" class="d-flex flex-wrap justify-content-between">';
                                                    if(rechReturnPayM2D[i] == 'Barzahlungen'){
                          newClientLineShow +=       '<button id="splitBillBtn1'+i+'" style="width:19.5%;" class="btn btn-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Bar</button>';
                                                    }else{
                          newClientLineShow +=        '<button id="splitBillBtn1'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Bar</button>';
                                                    }
                                                    if(rechReturnPayM2D[i] == 'Kartenzahlung'){
                          newClientLineShow +=       '<button id="splitBillBtn2'+i+'" style="width:19.5%;" class="btn btn-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')" disabled>Karte</button>';
                                                    }else{
                          newClientLineShow +=        '<button id="splitBillBtn2'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')" disabled>Karte</button>';
                                                    }

                          newClientLineShow +=        '<button id="splitBillBtn3'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Online</button>';
                                                    
                                                    if(rechReturnPayM2D[i] == 'Auf rechnung'){
                          newClientLineShow +=       '<button id="splitBillBtn4'+i+'" style="width:19.5%;" class="btn btn-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Auf Rechnung</button>';
                                                    }else{
                          newClientLineShow +=        '<button id="splitBillBtn4'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Auf Rechnung</button>';
                                                    }
                          newClientLineShow +=        '<button id="splitBillBtn5'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Geschenkkarte</button>'+
                                                  '</div>'+
                                              '</div>'+
                                              '<div id="splitBillClientDiv2'+i+'" style="border-bottom:2px solid black;" class="d-flex flex-wrap justify-content-between pt-1 pb-2 alert-success">'+
                                                  '<div style="width:30%;" id="splitBillClientDiv2_1'+i+'">'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Tipp: <span id="splitBillModalTippValueClient'+i+'">'+parseFloat(parseFloat(rechReturntips2D[i]).toFixed(2)).toFixed(2)+'</span> CHF</strong></p>';
                                                      if(rechReturnGCAmt2D[i] > 0){
                          newClientLineShow +=        '<p id="showGCApplyP'+i+'" style="margin-bottom: 3px; font-size:1.2rem;"><strong>Geschenkkarte: <span id="splitBillModalGCValueClientShow'+i+'">'+parseFloat(rechReturnGCAmt2D[i]).toFixed(2)+'</span> CHF</strong></p>';
                                                      }
                          newClientLineShow +=    '</div>'+
                                                  '<div style="width:70%;" class="d-flex flex-wrap justify-content-between">'+
                                                      '<input type="hidden" id="splitBillTippType'+i+'" value="0">'+
                                                  '</div>'+
                                                  '<input type="hidden" id="splitBillnitiateId'+i+'" value="'+respo2D[5]+'">'+
                                                  '<input type="hidden" id="splitBillGCAppliedId'+i+'" value="'+rechReturnGCId2D[i]+'">'+
                                                  '<input type="hidden" id="splitBillGCAppliedCHFVal'+i+'" value="'+rechReturnGCAmt2D[i]+'">'+
                                              '</div>';
                        }else{
                          newClientLineShow = '<div id="splitBillClientDiv1'+i+'" class="d-flex justify-content-between pt-2">'+
                                                  '<div style="width:30%;">'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Kunde '+(i + 1)+'</strong></p>'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Bezahlen <span id="splitBillModalPayValueClient'+i+'">'+parseFloat(respo2D[0]).toFixed(2)+'</span> CHF</strong></p>'+
                                                  '</div>'+
                                                  '<div id="splitBillClientDiv1_2'+i+'" style="width:70%;" class="d-flex flex-wrap justify-content-between">'+
                                                      '<button id="splitBillBtn1'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Bar</button>'+
                                                      '<button id="splitBillBtn2'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')">Karte</button>'+
                                                      '<button id="splitBillBtn3'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Online</button>'+
                                                      '<button id="splitBillBtn4'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Auf Rechnung</button>'+
                                                      '<button id="splitBillBtn5'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Geschenkkarte</button>'+
                                                  '</div>'+
                                              '</div>'+
                                              '<div id="splitBillClientDiv2'+i+'" style="border-bottom:2px solid black;" class="d-flex flex-wrap justify-content-between pt-1 pb-2">'+
                                                  '<div style="width:30%;" id="splitBillClientDiv2_1'+i+'">'+
                                                      '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong><i style="color:red;" class="fa-solid fa-circle-xmark" id="cancelTippApplyBtn'+i+'" onclick="cancelTippApply(\''+i+'\')"></i> Tipp: <span id="splitBillModalTippValueClient'+i+'">'+parseFloat(0).toFixed(2)+'</span> CHF</strong></p>'+
                                                  '</div>'+
                                                  '<div style="width:70%;" class="d-flex flex-wrap justify-content-between">'+
                                                      '<button id="splitBillTippBtn1'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+0.5+'\')">'+parseFloat(0.5).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtn2'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+1+'\')">'+parseFloat(1).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtn3'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+2+'\')">'+parseFloat(2).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtn4'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+5+'\')">'+parseFloat(5).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtn5'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+10+'\')">'+parseFloat(10).toFixed(2)+'</button>'+
                                                      '<button id="splitBillTippBtnCostume'+i+'" style="width:16.6%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none">'+
                                                          '<input id="splitBillTippInputCostume'+i+'" step="0.05" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostumeTipStafSplitBill(\''+i+'\',this.value)" style="width:95%; border:none;" placeholder="Gesamt mit Tipp">'+
                                                      '</button>'+
                                                      '<input type="hidden" id="splitBillTippType'+i+'" value="0">'+
                                                  '</div>'+
                                                  '<div style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center" id="splitTheBillError01'+i+'">'+
                                                      '<strong>Geben Sie zuerst einen gültigen Wert ein!, Gesamtpreis nach dem Trinkgeld</strong>'+
                                                  '</div>'+
                                                  '<div id="splitTheBillPayAtPOS'+i+'" class="alert alert-info text-center mt-1" style="display:none; width:100%;">'+
                                                      '<strong>Schließen Sie die Zahlung am POS-Terminal ab!</strong>'+
                                                  '</div>'+
                                                  '<input type="hidden" id="splitBillnitiateId'+i+'" value="'+respo2D[5]+'">'+
                                                  '<input type="hidden" id="splitBillGCAppliedId'+i+'" value="0">'+
                                                  '<input type="hidden" id="splitBillGCAppliedCHFVal'+i+'" value="0">'+
                                              '</div>';
                        }
                          $('#splitTheBillInitiateModalBody').append(newClientLineShow);
                      }

                      $('#splitTheBillInitiateModalOriginalPriceShow').html(parseFloat(respo2D[3]).toFixed(2));
                      $('#splitTheBillInitiateModalPayPriceShow').html(parseFloat(respo2D[4]).toFixed(2));
                    }



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
                          $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                          $('#orderQRCodePicDownloadOI').val('{{$_GET["orId"]}}');
                          $('#orderQRCodePicTel').modal('show');

                          if(respo2D[9] == 1 || respo2D[9] == 0){
                              $('#OrQRCodeModalStillOpen').val(1);
                          }
                      },
                      error: (error) => { console.log(error); }
                    });




                  }
                },error: (error) => { console.log(error); }
				      });
            </script>
            @endif
        @else
            @if (isset($_GET["orId"]) && isset($_GET["ptn"]))
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
                            $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                            $('#orderQRCodePicDownloadOI').val('{{$_GET["orId"]}}');
                            $('#orderQRCodePicTel').modal('show');

                            $('#OrQRCodePayIsSelective').val('1');
                            $('#OrQRCodePayIsSelectiveTNr').val('{{$_GET["ptn"]}}');
                        },
                        error: (error) => { console.log(error); }
                    });
                </script>
            @elseif (isset($_GET["orId"]))
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
                            $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                            $('#orderQRCodePicDownloadOI').val('{{$_GET["orId"]}}');
                            $('#orderQRCodePicTel').modal('show');

                            $('#OrQRCodePayIsSelective').val('0');
                            $('#OrQRCodePayIsSelectiveTNr').val('0');
                        },
                        error: (error) => { console.log(error); }
                    });
                </script>
            @endif
        @endif



















        <script>
          
		    $.ajax({
		    	url: '{{ route("splitBill.splitBillCallUnfinishedBill") }}',
		    	method: 'post',
		    	data: {
		    		_token: '{{csrf_token()}}'
		    	},
		    	success: (response) => {
                    response = $.trim(response);
                    if(response == 'InstanceIsCanceled'){
                    }else if(response == 'InstanceNotFound'){
                    }else if(response == 'LastPaymentComplete'){
                    }else{
                        if($('#isDeviceMobile').val() == 1){
                        if(response == 'invalideTable'){

                        }else if(response == 'modalAlreadyShown'){

                        }else{
                            respo2D = response.split('|||');
                            if( respo2D[9] == 2){

                            }else{
                            $('#tabOrder'+respo2D[1]).modal('hide');
                            $('#splitTheBillInitiateModal').modal('show');
                            $('#splitTheBillInitiateModalTableNr').html(respo2D[1]);
                            $('#splitTheBillInitiateModalClientsNr').html(respo2D[2]);

                            if( respo2D[9] == 0){
                                $('#splitTheBillInitiateModalCancelBtn').remove();
                            }else{
                                $('#splitTheBillInitiateModalCancelBtn').attr('onclick','splitTheBillInitiateModalCancel("'+respo2D[5]+'")');
                            }

                            $('#splitTheBillInitiateModalBody').html('');

                            $('body').addClass('modal-open');

                            var rechReturnPayM = respo2D[6];
                            var rechReturnPayM2D = rechReturnPayM.split('--88--');
                            var rechReturntips = respo2D[7];
                            var rechReturntips2D = rechReturntips.split('--88--');
                            var rechReturnGCId = respo2D[10];
                            var rechReturnGCId2D = rechReturnGCId.split('--88--');
                            var rechReturnGCAmt = respo2D[11];
                            var rechReturnGCAmt2D = rechReturnGCAmt.split('--88--');
                            var newClientLineShow = "";
                            console.log(respo2D[10]);
                            console.log(respo2D[11]);
                            for(var i = 0; i < respo2D[2]; i++) {
                                if(respo2D[6] != '' && rechReturnPayM2D[i] !== undefined) {
                                newClientLineShow = '<div id="splitBillClientDiv1'+i+'" class="d-flex flex-wrap justify-content-between pt-2 alert-success">'+
                                                        '<div style="width:100%;">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Kunde '+(i + 1)+'</strong></p>'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Bezahlen <span id="splitBillModalPayValueClient'+i+'">'+parseFloat(parseFloat(respo2D[8]) + parseFloat(rechReturntips2D[i])).toFixed(2)+'</span> CHF</strong></p>'+
                                                        '</div>'+
                                                        '<div id="splitBillClientDiv1_2'+i+'" style="width:100%;" class="d-flex flex-wrap justify-content-between">';
                                                            if(rechReturnPayM2D[i] == 'Barzahlungen'){
                                newClientLineShow +=       '<button id="splitBillBtn1'+i+'" style="width:33%;" class="btn btn-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Bar</button>';
                                                            }else{
                                newClientLineShow +=        '<button id="splitBillBtn1'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Bar</button>';
                                                            }
                                                            if(rechReturnPayM2D[i] == 'Kartenzahlung'){
                                newClientLineShow +=       '<button id="splitBillBtn2'+i+'" style="width:33%;" class="btn btn-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')" disabled>Karte</button>';
                                                            }else{
                                newClientLineShow +=        '<button id="splitBillBtn2'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')" disabled>Karte</button>';
                                                            }

                                newClientLineShow +=        '<button id="splitBillBtn3'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Online</button>';
                                                            
                                                            if(rechReturnPayM2D[i] == 'Auf rechnung'){
                                newClientLineShow +=       '<button id="splitBillBtn4'+i+'" style="width:49.5%;" class="btn btn-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Auf Rechnung</button>';
                                                            }else{
                                newClientLineShow +=        '<button id="splitBillBtn4'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Auf Rechnung</button>';
                                                            }
                                newClientLineShow +=        '<button id="splitBillBtn5'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Geschenkkarte</button>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div id="splitBillClientDiv2'+i+'" style="border-bottom:2px solid black;" class="d-flex flex-wrap justify-content-between pt-1 pb-2 alert-success">'+
                                                        '<div style="width:100%;" id="splitBillClientDiv2_1'+i+'">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Tipp: <span id="splitBillModalTippValueClient'+i+'">'+parseFloat(parseFloat(rechReturntips2D[i]).toFixed(2)).toFixed(2)+'</span> CHF</strong></p>';
                                                            if(rechReturnGCAmt2D[i] > 0){
                                newClientLineShow +=        '<p id="showGCApplyP'+i+'" style="margin-bottom: 3px; font-size:1.2rem;"><strong>Geschenkkarte: <span id="splitBillModalGCValueClientShow'+i+'">'+parseFloat(rechReturnGCAmt2D[i]).toFixed(2)+'</span> CHF</strong></p>';
                                                            }
                                newClientLineShow +=    '</div>'+
                                                        '<div style="width:100%;" class="d-flex flex-wrap justify-content-between">'+
                                                            '<input type="hidden" id="splitBillTippType'+i+'" value="0">'+
                                                        '</div>'+
                                                        '<input type="hidden" id="splitBillnitiateId'+i+'" value="'+respo2D[5]+'">'+
                                                        '<input type="hidden" id="splitBillGCAppliedId'+i+'" value="'+rechReturnGCId2D[i]+'">'+
                                                        '<input type="hidden" id="splitBillGCAppliedCHFVal'+i+'" value="'+rechReturnGCAmt2D[i]+'">'+
                                                    '</div>';
                                }else{
                                newClientLineShow = '<div id="splitBillClientDiv1'+i+'" class="d-flex flex-wrap justify-content-between pt-2">'+
                                                        '<div style="width:100%;">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Kunde '+(i + 1)+'</strong></p>'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Bezahlen <span id="splitBillModalPayValueClient'+i+'">'+parseFloat(respo2D[0]).toFixed(2)+'</span> CHF</strong></p>'+
                                                        '</div>'+
                                                        '<div id="splitBillClientDiv1_2'+i+'" style="width:100%;" class="d-flex flex-wrap justify-content-between">'+
                                                            '<button id="splitBillBtn1'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Bar</button>'+
                                                            '<button id="splitBillBtn2'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')">Karte</button>'+
                                                            '<button id="splitBillBtn3'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Online</button>'+
                                                            '<button id="splitBillBtn4'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Auf Rechnung</button>'+
                                                            '<button id="splitBillBtn5'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Geschenkkarte</button>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div id="splitBillClientDiv2'+i+'" style="border-bottom:2px solid black;" class="d-flex flex-wrap justify-content-between pt-1 pb-2">'+
                                                        '<div style="width:100%;" id="splitBillClientDiv2_1'+i+'">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong><i style="color:red;" class="fa-solid fa-circle-xmark" id="cancelTippApplyBtn'+i+'" onclick="cancelTippApply(\''+i+'\')"></i> Tipp: <span id="splitBillModalTippValueClient'+i+'">'+parseFloat(0).toFixed(2)+'</span> CHF</strong></p>'+
                                                        '</div>'+
                                                        '<div style="width:100%;" class="d-flex flex-wrap justify-content-between">'+
                                                            '<button id="splitBillTippBtn1'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+0.5+'\')">'+parseFloat(0.5).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn2'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+1+'\')">'+parseFloat(1).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn3'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+2+'\')">'+parseFloat(2).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn4'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+5+'\')">'+parseFloat(5).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn5'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+10+'\')">'+parseFloat(10).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtnCostume'+i+'" style="width:74.4%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none">'+
                                                                '<input id="splitBillTippInputCostume'+i+'" step="0.05" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostumeTipStafSplitBill(\''+i+'\',this.value)" style="width:95%; border:none;" placeholder="Gesamt mit Tipp">'+
                                                            '</button>'+
                                                            '<input type="hidden" id="splitBillTippType'+i+'" value="0">'+
                                                        '</div>'+
                                                        '<div style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center" id="splitTheBillError01'+i+'">'+
                                                            '<strong>Geben Sie zuerst einen gültigen Wert ein!, Gesamtpreis nach dem Trinkgeld</strong>'+
                                                        '</div>'+
                                                        '<div id="splitTheBillPayAtPOS'+i+'" class="alert alert-info text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Schließen Sie die Zahlung am POS-Terminal ab!</strong>'+
                                                        '</div>'+
                                                        '<input type="hidden" id="splitBillnitiateId'+i+'" value="'+respo2D[5]+'">'+
                                                        '<input type="hidden" id="splitBillGCAppliedId'+i+'" value="0">'+
                                                        '<input type="hidden" id="splitBillGCAppliedCHFVal'+i+'" value="0">'+
                                                    '</div>';
                                }
                                $('#splitTheBillInitiateModalBody').append(newClientLineShow);
                            }

                            $('#splitTheBillInitiateModalOriginalPriceShow').html(parseFloat(respo2D[3]).toFixed(2));
                            $('#splitTheBillInitiateModalPayPriceShow').html(parseFloat(respo2D[4]).toFixed(2));
                            }
                        }





                        }else{
                        if(response == 'invalideTable'){

                        }else if(response == 'modalAlreadyShown'){

                        }else{
                            respo2D = response.split('|||');
                            if( respo2D[9] == 2){

                            }else{
                            $('#tabOrder'+respo2D[1]).modal('hide');
                            $('#splitTheBillInitiateModal').modal('show');
                            $('#splitTheBillInitiateModalTableNr').html(respo2D[1]);
                            $('#splitTheBillInitiateModalClientsNr').html(respo2D[2]);

                            if( respo2D[9] == 0){
                                $('#splitTheBillInitiateModalCancelBtn').remove();
                            }else{
                                $('#splitTheBillInitiateModalCancelBtn').attr('onclick','splitTheBillInitiateModalCancel("'+respo2D[5]+'")');
                            }

                            $('#splitTheBillInitiateModalBody').html('');

                            $('body').addClass('modal-open');

                            var rechReturnPayM = respo2D[6];
                            var rechReturnPayM2D = rechReturnPayM.split('--88--');
                            var rechReturntips = respo2D[7];
                            var rechReturntips2D = rechReturntips.split('--88--');
                            var rechReturnGCId = respo2D[10];
                            var rechReturnGCId2D = rechReturnGCId.split('--88--');
                            var rechReturnGCAmt = respo2D[11];
                            var rechReturnGCAmt2D = rechReturnGCAmt.split('--88--');
                            var newClientLineShow = "";
                            console.log(respo2D[10]);
                            console.log(respo2D[11]);
                            for(var i = 0; i < respo2D[2]; i++) {
                                if(respo2D[6] != '' && rechReturnPayM2D[i] !== undefined) {
                                newClientLineShow = '<div id="splitBillClientDiv1'+i+'" class="d-flex justify-content-between pt-2 alert-success">'+
                                                        '<div style="width:30%;">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Kunde '+(i + 1)+'</strong></p>'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Bezahlen <span id="splitBillModalPayValueClient'+i+'">'+parseFloat(parseFloat(respo2D[8]) + parseFloat(rechReturntips2D[i])).toFixed(2)+'</span> CHF</strong></p>'+
                                                        '</div>'+
                                                        '<div id="splitBillClientDiv1_2'+i+'" style="width:70%;" class="d-flex flex-wrap justify-content-between">';
                                                            if(rechReturnPayM2D[i] == 'Barzahlungen'){
                                newClientLineShow +=       '<button id="splitBillBtn1'+i+'" style="width:19.5%;" class="btn btn-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Bar</button>';
                                                            }else{
                                newClientLineShow +=        '<button id="splitBillBtn1'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Bar</button>';
                                                            }
                                                            if(rechReturnPayM2D[i] == 'Kartenzahlung'){
                                newClientLineShow +=       '<button id="splitBillBtn2'+i+'" style="width:19.5%;" class="btn btn-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')" disabled>Karte</button>';
                                                            }else{
                                newClientLineShow +=        '<button id="splitBillBtn2'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')" disabled>Karte</button>';
                                                            }

                                newClientLineShow +=        '<button id="splitBillBtn3'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Online</button>';
                                                            
                                                            if(rechReturnPayM2D[i] == 'Auf rechnung'){
                                newClientLineShow +=       '<button id="splitBillBtn4'+i+'" style="width:19.5%;" class="btn btn-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Auf Rechnung</button>';
                                                            }else{
                                newClientLineShow +=        '<button id="splitBillBtn4'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Auf Rechnung</button>';
                                                            }
                                newClientLineShow +=        '<button id="splitBillBtn5'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Geschenkkarte</button>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div id="splitBillClientDiv2'+i+'" style="border-bottom:2px solid black;" class="d-flex flex-wrap justify-content-between pt-1 pb-2 alert-success">'+
                                                        '<div style="width:30%;" id="splitBillClientDiv2_1'+i+'">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Tipp: <span id="splitBillModalTippValueClient'+i+'">'+parseFloat(parseFloat(rechReturntips2D[i]).toFixed(2)).toFixed(2)+'</span> CHF</strong></p>';
                                                            if(rechReturnGCAmt2D[i] > 0){
                                newClientLineShow +=        '<p id="showGCApplyP'+i+'" style="margin-bottom: 3px; font-size:1.2rem;"><strong>Geschenkkarte: <span id="splitBillModalGCValueClientShow'+i+'">'+parseFloat(rechReturnGCAmt2D[i]).toFixed(2)+'</span> CHF</strong></p>';
                                                            }
                                newClientLineShow +=    '</div>'+
                                                        '<div style="width:70%;" class="d-flex flex-wrap justify-content-between">'+
                                                            '<input type="hidden" id="splitBillTippType'+i+'" value="0">'+
                                                        '</div>'+
                                                        '<input type="hidden" id="splitBillnitiateId'+i+'" value="'+respo2D[5]+'">'+
                                                        '<input type="hidden" id="splitBillGCAppliedId'+i+'" value="'+rechReturnGCId2D[i]+'">'+
                                                        '<input type="hidden" id="splitBillGCAppliedCHFVal'+i+'" value="'+rechReturnGCAmt2D[i]+'">'+
                                                    '</div>';
                                }else{
                                newClientLineShow = '<div id="splitBillClientDiv1'+i+'" class="d-flex justify-content-between pt-2">'+
                                                        '<div style="width:30%;">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Kunde '+(i + 1)+'</strong></p>'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Bezahlen <span id="splitBillModalPayValueClient'+i+'">'+parseFloat(respo2D[0]).toFixed(2)+'</span> CHF</strong></p>'+
                                                        '</div>'+
                                                        '<div id="splitBillClientDiv1_2'+i+'" style="width:70%;" class="d-flex flex-wrap justify-content-between">'+
                                                            '<button id="splitBillBtn1'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Bar</button>'+
                                                            '<button id="splitBillBtn2'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')">Karte</button>'+
                                                            '<button id="splitBillBtn3'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Online</button>'+
                                                            '<button id="splitBillBtn4'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Auf Rechnung</button>'+
                                                            '<button id="splitBillBtn5'+i+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Geschenkkarte</button>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div id="splitBillClientDiv2'+i+'" style="border-bottom:2px solid black;" class="d-flex flex-wrap justify-content-between pt-1 pb-2">'+
                                                        '<div style="width:30%;" id="splitBillClientDiv2_1'+i+'">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong><i style="color:red;" class="fa-solid fa-circle-xmark" id="cancelTippApplyBtn'+i+'" onclick="cancelTippApply(\''+i+'\')"></i> Tipp: <span id="splitBillModalTippValueClient'+i+'">'+parseFloat(0).toFixed(2)+'</span> CHF</strong></p>'+
                                                        '</div>'+
                                                        '<div style="width:70%;" class="d-flex flex-wrap justify-content-between">'+
                                                            '<button id="splitBillTippBtn1'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+0.5+'\')">'+parseFloat(0.5).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn2'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+1+'\')">'+parseFloat(1).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn3'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+2+'\')">'+parseFloat(2).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn4'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+5+'\')">'+parseFloat(5).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn5'+i+'" style="width:16.6%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+10+'\')">'+parseFloat(10).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtnCostume'+i+'" style="width:16.6%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none">'+
                                                                '<input id="splitBillTippInputCostume'+i+'" step="0.05" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostumeTipStafSplitBill(\''+i+'\',this.value)" style="width:95%; border:none;" placeholder="Gesamt mit Tipp">'+
                                                            '</button>'+
                                                            '<input type="hidden" id="splitBillTippType'+i+'" value="0">'+
                                                        '</div>'+
                                                        '<div style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center" id="splitTheBillError01'+i+'">'+
                                                            '<strong>Geben Sie zuerst einen gültigen Wert ein!, Gesamtpreis nach dem Trinkgeld</strong>'+
                                                        '</div>'+
                                                        '<div id="splitTheBillPayAtPOS'+i+'" class="alert alert-info text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Schließen Sie die Zahlung am POS-Terminal ab!</strong>'+
                                                        '</div>'+
                                                        '<input type="hidden" id="splitBillnitiateId'+i+'" value="'+respo2D[5]+'">'+
                                                        '<input type="hidden" id="splitBillGCAppliedId'+i+'" value="0">'+
                                                        '<input type="hidden" id="splitBillGCAppliedCHFVal'+i+'" value="0">'+
                                                    '</div>';
                                }
                                $('#splitTheBillInitiateModalBody').append(newClientLineShow);
                            }

                            $('#splitTheBillInitiateModalOriginalPriceShow').html(parseFloat(respo2D[3]).toFixed(2));
                            $('#splitTheBillInitiateModalPayPriceShow').html(parseFloat(respo2D[4]).toFixed(2));
                            }
                        }
                        }
                    }
				},
				error: (error) => { console.log(error); }
			});
        </script>

    </body>
</html>