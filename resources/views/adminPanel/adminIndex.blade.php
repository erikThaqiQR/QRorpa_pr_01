<?php
  use App\Orders;
  use Carbon\Carbon;
  use App\accessControllForAdmins;
  use App\displayAddForUser;

  use Jenssegers\Agent\Agent;
  $agent = new Agent();
?>
@if(!Auth::check() || (Auth::user()->role != 5 && Auth::user()->role != 15))
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

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet">
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js" ></script>
  <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js" ></script>

  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="{{ asset('js/jquery.signature.js') }}" ></script>
  <script src="{{ asset('js/jquery.ui.touch-punch.js') }}" ></script>
  <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}" ></script>
  <link href="{{ asset('css/jquery.signature.css') }}" rel="stylesheet" >

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
  <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
  
<style>
    html, body{
        height:100% !important; 
    }
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

    .hoverPointer:hover{
      cursor: pointer;
    }

    .btn-outline-dark:focus{
      background-color: transparent !important;
      color: rgb(72,81,87);
    }
</style>

  <!-- Matomo -->

  <!-- End Matomo Code -->

</head>


  





  <!-- check and send the monthly rechnung(Bills) to the clients -->
  @include('adminPanel.indexParts.checkForMonthlyRechnung')

  <!-- modalet -->
  @if($agent->isMobile())
    @include('adminPanel.indexParts.optionsModalTel')
    <input type="hidden" id="isDeviceMobile" value="1">
  @else 
    @include('adminPanel.indexParts.optionsModal')
    <input type="hidden" id="isDeviceMobile" value="0">
  @endif

  @include('adminPanel.indexParts.orderQRCodeTel')

  <!-- Check for non valid TAB orders -->
  @include('adminPanel.indexParts.checkNonValidTABOrds')




  
  @if(Auth::User()->passChngRequ == 1)
  <body id="page-top" class="modal-open">
      @include('adminPanel.indexParts.changePass')
  @else
  <body id="page-top">
  @endif



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



    @if(!$agent->isMobile())
    
      <!-- BarbershopAdmin -->
      @if(Auth::user()->role == 15)
        <div id="all">  
          <div class="DashSideMenu">
            @include('adminPanel.Barbershop.parts.side')
          </div>
          <div id="content" >
            @include('adminPanel.Barbershop.parts.topNavbar')

            @if(Request::is('dashboard') || Request::is('dashboardBar'))
              @include('adminPanel.Barbershop.parts.terminetDash')
            @elseif(Request::is('barAdminStat'))
              @include('adminPanel.Barbershop.parts.statisticsBar')
            @elseif(Request::is('barAdmShowReservationsByMonth'))
              @include('adminPanel.Barbershop.parts.statisticsBarByMonth')
            @elseif(Request::is('barAdminWorker'))
              @include('adminPanel.Barbershop.parts.workers')
            @elseif(Request::is('barAdmIndexAllConfirmedRez'))
              @include('adminPanel.Barbershop.parts.confirmedRez')
            @elseif(Request::is('barAdmRecomendetSer'))
              @include('adminPanel.Barbershop.parts.recomendetBarSer')
            @elseif(Request::is('barAdmAddRezervationAdminPage'))
              @include('adminPanel.Barbershop.parts.addAdmRezervation')
            @elseif(Request::is('barAdmCuponsMng'))
              @include('adminPanel.Barbershop.parts.cupons')
            @endif      
          </div>
        </div>

      <!-- Restaurant Admin -->
      @elseif(Auth::user()->role == 5)
        @if(!$agent->isMobile())
          <div id="all" style="height:110% !important;">
            <div class="DashSideMenu" id="DashSideMenuDesktop" style="height:110% !important;">
              @include('adminPanel.parts.side')
            </div>
            @include('adminPanel.parts.topNavbar')
            <div id="content" style="margin-left:3%; width:97%; height:110% !important;">
                @if(Auth::user()->sFor != 0 && (Auth::user()->role == 5 || Auth::user()->role == 4))
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
                            <a href="{{ route('dash.index') }}" class="btn btn-light" style="width: 45%;">{{__('adminP.goToOrders')}}</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                
                  <script>
                    function closeAdminPAlertWindow(){ $('#adminPAlertWindow').hide(500);}
                  </script>
                  @if(Request::is('dashboard') || Request::is('dashboard2') || Request::is('dashboard3')) <!-- Porosite -->
                    @include('adminPanel.tablePage.tablePageIndex')

                  @elseif(Request::is('dashboardaddNewProductOrPage')) 
                    @include('adminPanel.parts.newProductOrPage')

                  @elseif(Request::is('categorizeReport')) <!-- categorize the report -->
                    @include('adminPanel.parts.categorizeReportPage')

                  @elseif(Request::is('dashboardList'))
                    @include('adminPanel.parts.porositDashList')

                  @elseif(Request::is('statusWorker'))
                    @include('adminPanel.parts.statusWorker')

                  @elseif(Request::is('dashboardStatistics'))
                    @include('adminPanel.parts.porositStat')
                  @elseif(Request::is('statsDash01'))
                    @include('adminPanel.parts.dashboard01')
                  @elseif(Request::is('statsDash02'))
                    @include('adminPanel.parts.dashboard02')
                  @elseif(Request::is('canceledOrders'))
                    @include('adminPanel.parts.dashboardCancelOr')

                  @elseif(Request::is('AdminStatsDeletedTAProdsPage'))
                    @include('adminPanel.parts.deletedTAIns')

                  @elseif(Request::is('AdminchngPayMethodForOrdersPage'))
                    @include('adminPanel.parts.payMChngInsPage')

                  @elseif(Request::is('statsBillsRecs'))
                    @include('adminPanel.parts.billRecords')

                  @elseif(Request::is('statsDashSalesStatistics')) <!-- Report / excel sales -->
                    @include('adminPanel.parts.salesStatistics') 

                  @elseif(Request::is('recomendet'))
                    @include('adminPanel.parts.recomendet')

                  @elseif(Request::is('Rechnungsverwaltung')) <!-- Rechnung mng page -->
                    @include('adminPanel.parts.emailBillsMng')
                              
                  @elseif(Request::is('dashboardContentMng'))  <!-- Content management -->
                    @include('adminPanel.manageContent.firstPage')
                  @elseif(Request::is('dashboardContentMng/Order'))
                    @include('adminPanel.manageContent.firstPageOrder')

                  @elseif(Request::is('ClTableChangeIndexAP')) <!-- Change Table -->
                    @include('adminPanel.parts.tableCHReq')

                  @elseif(Request::is('restrictedProducts')) <!--Restricted Products  -->
                    @include('adminPanel.parts.restrictProducts')

                  @elseif(Request::is('Takeaway')) <!--Takeaway -->
                    @include('adminPanel.parts.takeawayPage')
                  @elseif(Request::is('dashboardTakeaway'))
                    @include('adminPanel.parts.dashboardTakeaway')

                  @elseif(Request::is('DeliveryAP')) <!--Delivery -->
                    @include('adminPanel.parts.deliveryPage')
                  @elseif(Request::is('dashboardDelivery'))
                    @include('adminPanel.parts.dashboardDelivery')

                  @elseif(Request::is('giftCardMngAdmin')) <!-- Gift Card -->
                    @include('adminPanel.giftCard.gcIndex')

                  @elseif(Request::is('showTips')) <!-- Tips -->
                    @include('adminPanel.parts.tipsPage')
                  @elseif(Request::is('showTipsMonth'))
                    @include('adminPanel.parts.tipsPageMonth')

                  @elseif(Request::is('waitersDailySales'))
                    @include('adminPanel.parts.waitersSales') <!-- Waiters sales report -->

                  @elseif(Request::is('cupons')) <!-- Cupon -->
                    @include('adminPanel.Cupon.cuponIndex')
                  
                  @elseif(Request::is('tablesCapacity')) <!-- Table Capacity  -->
                    @include('adminPanel.parts.tableCapacity')

                  @elseif(Request::is('tabReserAdminIndex')) <!-- table reservation -->
                    @include('adminPanel.parts.tableReservation')
                  @elseif(Request::is('tabReserAdminIndexRezList'))
                    @include('adminPanel.parts.tableReservationReq')
                  
                  @elseif(Request::is('ServiceReqAP')) <!--Service request  -->
                    @include('adminPanel.parts.serviceReq')

                  @elseif(Request::is('covidsAdmin'))
                    @include('adminPanel.parts.covidsAdmin')

                  @elseif(Request::is('dashboardFreieTische')) <!-- Free Tabels -->
                    @include('adminPanel.parts.freeTables')

                  @elseif(Request::is('AdminSaMSG')) <!-- Talk to QRorpa -->
                    @include('adminPanel.parts.talkToQRorpa')

                  @elseif(Request::is('admWoMngIndex')) <!-- Manage workers -->
                    @include('adminPanel.mngWorkersDes.index')
                  @elseif(Request::is('admWoMngWaiterStatistics'))
                    @include('adminPanel.mngWorkersDes.waiterStatistics')
                  @elseif(Request::is('admWoMngWaiterStatisticsT'))
                    @include('adminPanel.mngWorkersDes.waiterStatisticsT')
                  @elseif(Request::is('admWoMngWaiterStatisticsD'))
                    @include('adminPanel.mngWorkersDes.waiterStatisticsD')

                  @elseif(Request::is('taglicheZiehung')) <!-- Barazimi ditor -->
                    @include('adminPanel.barazimiDitor.index')

                  @elseif(Request::is('StockMngPage')) <!-- Menaxhimi i stokut -->
                    @include('adminPanel.stockMng.stockMngIndex')

                  @elseif(Request::is('NotificationsAct')) <!-- Menaxhimi Njoftimeve (Aktivizim / deaktivizim) -->
                    @include('adminPanel.notifyActive.firstPage')

                  @elseif(Request::is('openCheckInOutReports')) <!-- check in/out reports -->
                    @include('adminPanel.parts.checkinoutReports')

                  @elseif(Request::is('BillTablets')) <!-- bill tablets management -->
                    @include('adminPanel.parts.billTabletsMng')

                  @elseif(Request::is('orderServingDevicesPage')) <!-- order serving Devices page -->
                    @include('adminPanel.orderServingDevices.indexPage')

                  @endif
                
                <!-- Entry for KUZHINIER -->
                @elseif(Auth::user()->sFor != 0 && Auth::user()->role == 3)
                  @if(Request::is('dashboard'))
                    @include('adminPanel.partsKuzhinier.orders')
                  @endif
                @endif
            
            </div>
          </div>
        @endif
      @endif
    @else
        <!-- MMobile & Tablet -->
        <div class="modal" id="showNewsVideoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog .modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="registerVideoNotShow()">
                <span aria-hidden="true" style="color: red;"><strong>Mach s'Video zue</strong></span>
              </button>
            </div>
            <div class="modal-body">
              <img style="width:100%; height:auto;" src="storage/gifs/addReRegisterGifOptimize.gif" alt="">
            </div>
          
          </div>
        </div>
      </div>
      <script>
        function registerVideoNotShow(){
          $.ajax({
            method: 'post',
            url: '{{ route("dash.regNotShowVideoAd") }}',
            data: {
              _token: '{{csrf_token()}}'
            },
            success: (response) => {
              // console.log(response);
            },
            error: (error) => { console.log(error); }
          });
        }
      </script>
      @if(displayAddForUser::where('toUser',Auth::user()->id)->first() == Null)
        <script>
          $(document).ready(function(){
            $('#showNewsVideoModal').modal('show');
          });
        </script>
      @endif
    
    
    @endif
















    @if($agent->isTablet())
      @include('adminPanel.partsTel.njoftimet')
       <!-- BarbershopAdmin -->
      @if(Auth::user()->role == 15)
        <div id="all">  
          <div class="DashSideMenu">
            @include('adminPanel.Barbershop.parts.side')
          </div>
          <div id="content">
            @include('adminPanel.Barbershop.parts.topNavbar')

            @if(Request::is('dashboard') || Request::is('dashboardBar'))
              @include('adminPanel.Barbershop.parts.terminetDash')
            @elseif(Request::is('barAdminStat'))
              @include('adminPanel.Barbershop.parts.statisticsBar')
            @elseif(Request::is('barAdmShowReservationsByMonth'))
              @include('adminPanel.Barbershop.parts.statisticsBarByMonth')
            @elseif(Request::is('barAdminWorker'))
              @include('adminPanel.Barbershop.parts.workers')
            @elseif(Request::is('barAdmIndexAllConfirmedRez'))
              @include('adminPanel.Barbershop.parts.confirmedRez')
            @elseif(Request::is('barAdmRecomendetSer'))
              @include('adminPanel.Barbershop.parts.recomendetBarSer')
            @elseif(Request::is('barAdmAddRezervationAdminPage'))
              @include('adminPanel.Barbershop.parts.addAdmRezervation')
            @elseif(Request::is('barAdmCuponsMng'))
              @include('adminPanel.Barbershop.parts.cupons')
            @endif      
          </div>
        </div>

      <!-- Restaurant Admin -->
      @elseif(Auth::user()->role == 5)
     
          <div id="all" style="height:100% !important;">
            <div class="DashSideMenu" id="DashSideMenuDesktop" style="height:100% !important;">
              @include('adminPanel.parts.side')
            </div>
            @include('adminPanel.parts.topNavbar')
            <div id="content" style="margin-left:3%; width:97%; height:100% !important;">
                @if(Auth::user()->sFor != 0 && (Auth::user()->role == 5 || Auth::user()->role == 4))
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
                            <a href="{{ route('dash.index') }}" class="btn btn-light" style="width: 45%;">{{__('adminP.goToOrders')}}</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                
                  <script>
                    function closeAdminPAlertWindow(){ $('#adminPAlertWindow').hide(500);}
                  </script>
                  @if(Request::is('dashboard') || Request::is('dashboard2') || Request::is('dashboard3'))
                    @include('adminPanel.tablePage.tablePageIndex')

                  @elseif(Request::is('categorizeReport')) <!-- categorize the report -->
                    @include('adminPanel.parts.categorizeReportPage')

                  @elseif(Request::is('dashboardList'))
                    @include('adminPanel.parts.porositDashList')

                  @elseif(Request::is('dashboardaddNewProductOrPage')) <!-- usage Not Found -->
                    @include('adminPanel.parts.newProductOrPage')

                  @elseif(Request::is('statusWorker'))
                    @include('adminPanel.parts.statusWorker')

                  @elseif(Request::is('dashboardStatistics'))
                    @include('adminPanel.parts.porositStat')
                  @elseif(Request::is('statsDash01'))
                    @include('adminPanel.parts.dashboard01')
                  @elseif(Request::is('statsDash02'))
                    @include('adminPanel.parts.dashboard02')
                  @elseif(Request::is('canceledOrders'))
                    @include('adminPanel.parts.dashboardCancelOr')

                  @elseif(Request::is('AdminStatsDeletedTAProdsPage'))
                    @include('adminPanel.parts.deletedTAIns')

                  @elseif(Request::is('AdminchngPayMethodForOrdersPage'))
                    @include('adminPanel.parts.payMChngInsPage')

                  @elseif(Request::is('statsBillsRecs'))
                    @include('adminPanel.parts.billRecords')

                  @elseif(Request::is('statsDashSalesStatistics')) <!-- usage Not Found -->
                    @include('adminPanel.parts.salesStatistics') 

                  @elseif(Request::is('recomendet'))
                    @include('adminPanel.parts.recomendet')
                              
                  @elseif(Request::is('dashboardContentMng'))  <!-- Content management -->
                    @include('adminPanel.manageContent.firstPage')
                  @elseif(Request::is('dashboardContentMng/Order'))
                    @include('adminPanel.manageContent.firstPageOrder')

                  @elseif(Request::is('Rechnungsverwaltung')) <!-- Rechnung mng page -->
                    @include('adminPanel.parts.emailBillsMng')
                
                 

                  @elseif(Request::is('waitersDailySales'))
                    @include('adminPanel.parts.waitersSales') <!-- Waiters sales report -->

                  @elseif(Request::is('ClTableChangeIndexAP')) <!-- Change Table -->
                    @include('adminPanel.parts.tableCHReq')

                  @elseif(Request::is('restrictedProducts')) <!--Restricted Products  -->
                    @include('adminPanel.parts.restrictProducts')

                  @elseif(Request::is('Takeaway')) <!--Takeaway -->
                    @include('adminPanel.parts.takeawayPage')
                  @elseif(Request::is('dashboardTakeaway'))
                    @include('adminPanel.parts.dashboardTakeaway')

                  @elseif(Request::is('DeliveryAP')) <!--Delivery -->
                    @include('adminPanel.parts.deliveryPage')
                  @elseif(Request::is('dashboardDelivery'))
                    @include('adminPanel.parts.dashboardDelivery')

                  @elseif(Request::is('giftCardMngAdmin')) <!-- Gift Card -->
                    @include('adminPanel.giftCard.gcIndex')

                  @elseif(Request::is('showTips')) <!-- Tips -->
                    @include('adminPanel.parts.tipsPage')
                  @elseif(Request::is('showTipsMonth'))
                    @include('adminPanel.parts.tipsPageMonth')

                  @elseif(Request::is('cupons')) <!-- Cupon -->
                    @include('adminPanel.Cupon.cuponIndex')
                  
                  @elseif(Request::is('tablesCapacity')) <!-- Table Capacity  -->
                    @include('adminPanel.parts.tableCapacity')

                  @elseif(Request::is('tabReserAdminIndex')) <!-- table reservation -->
                    @include('adminPanel.parts.tableReservation')
                  @elseif(Request::is('tabReserAdminIndexRezList'))
                    @include('adminPanel.parts.tableReservationReq')
                  
                  @elseif(Request::is('ServiceReqAP')) <!--Service request  -->
                    @include('adminPanel.parts.serviceReq')

                  @elseif(Request::is('covidsAdmin'))
                    @include('adminPanel.parts.covidsAdmin')

                  @elseif(Request::is('dashboardFreieTische')) <!-- Free Tabels -->
                    @include('adminPanel.parts.freeTables')

                  @elseif(Request::is('AdminSaMSG')) <!-- Talk to QRorpa -->
                    @include('adminPanel.parts.talkToQRorpa')

                  @elseif(Request::is('admWoMngIndex')) <!-- Manage workers -->
                    @include('adminPanel.mngWorkersDes.index')
                  @elseif(Request::is('admWoMngWaiterStatistics'))
                    @include('adminPanel.mngWorkersDes.waiterStatistics')
                  @elseif(Request::is('admWoMngWaiterStatisticsT'))
                    @include('adminPanel.mngWorkersDes.waiterStatisticsT')
                  @elseif(Request::is('admWoMngWaiterStatisticsD'))
                    @include('adminPanel.mngWorkersDes.waiterStatisticsD')

                  @elseif(Request::is('NotificationsAct')) <!-- Menaxhimi Njoftimeve (Aktivizim / deaktivizim) -->
                    @include('adminPanel.notifyActive.firstPage')
                  
                  @elseif(Request::is('openCheckInOutReports')) <!-- check in/out reports -->
                    @include('adminPanel.parts.checkinoutReports')

                  @elseif(Request::is('BillTablets')) <!-- bill tablets management -->
                    @include('adminPanel.parts.billTabletsMng')

                  @elseif(Request::is('orderServingDevicesPage')) <!-- order serving Devices page -->
                    @include('adminPanel.orderServingDevices.indexPage')

                  @endif
                

                <!-- Entry for KUZHINIER -->
                @elseif(Auth::user()->sFor != 0 && Auth::user()->role == 3)
                  @if(Request::is('dashboard'))
                    @include('adminPanel.partsKuzhinier.orders')
                  @endif
                @endif
            
            </div>
          </div>
      
      @endif
    @endif


    
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
                  <a href="{{ route('dash.index',['tabs']) }}" class="btn btn-light" style="width: 100%;">{{__('adminP.goToOrders')}}</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <script>
          function closeAdminPAlertWindowTel(){ $('#adminPAlertWindowTel').hide(500);}
        </script>

        @if($agent->isMobile() && !$agent->isTablet())
          @if(Auth::user()->role == 15)
            @include('adminPanel.indexParts.barTelNavbar')
          @elseif(Auth::user()->role == 5)
            @include('adminPanel.indexParts.resTelNavbar')
          @endif
        @endif

        <div id="soundsAll">
          <audio id="beepN" src="{{ asset('storage/sound/swiftBeep.mp3')}}" type="audio/mpeg" autoplay="true"></audio>
          <source id="newRing" src="{{ asset('storage/sound/swiftBeep.mp3')}}">
        </div>

        @if($agent->isMobile() && !$agent->isTablet())
          <!-- adminPanel.partsTel.njoftimet -->

          @if(Auth::user()->role == 5 || Auth::user()->role == 4)
            @if(Auth::user()->sFor != 0)
                
              @if(Request::is('dashboard'))
                @include('adminPanel.tablePageTel.tablePageIndex')
              @elseif(Request::is('dashboardList'))
                @include('adminPanel.tablePageTel.porositDashList')

              @elseif(Request::is('statusWorker'))
                @include('adminPanel.partsTel.statusWorker')

              @elseif(Request::is('dashboardStatistics'))
                @include('adminPanel.partsTel.porositStatTel')
              @elseif(Request::is('statsDashSalesStatistics')) <!-- Statistikat ne baza ditore/javore/mujore/vjetore -->
                @include('adminPanel.partsTel.salesStatistics')

              @elseif(Request::is('categorizeReport')) <!-- categorize the report -->
                @include('adminPanel.partsTel.categorizeReportPage')

              @elseif(Request::is('dashboardaddNewProductOrPage'))<!-- usage Not Found -->
                @include('adminPanel.partsTel.newProductOrPageTel')

              @elseif(Request::is('recomendet'))
                @include('adminPanel.partsTel.recomendetTel')

              @elseif(Request::is('Rechnungsverwaltung')) <!-- Rechnung mng page -->
                @include('adminPanel.partsTel.emailBillsMng')
              
              @elseif(Request::is('statsDash01'))
                @include('adminPanel.partsTel.dashboard01tel')
              @elseif(Request::is('statsDash02'))
                @include('adminPanel.partsTel.dashboard02tel')
              @elseif(Request::is('canceledOrders'))
                @include('adminPanel.partsTel.dashboardCancelOr')

              @elseif(Request::is('AdminStatsDeletedTAProdsPage'))
                @include('adminPanel.partsTel.deletedTAIns')

              @elseif(Request::is('AdminchngPayMethodForOrdersPage'))
                @include('adminPanel.partsTel.payMChngInsPage')

              @elseif(Request::is('statsBillsRecs'))
                @include('adminPanel.partsTel.billRecords')
              
              @elseif(Request::is('dashboardContentMng')) <!-- Content management -->
                @include('adminPanel.manageContentTel.firstPage')
              @elseif(Request::is('dashboardContentMng/Order'))
                @include('adminPanel.manageContentTel.firstPageOrder')

              @elseif(Request::is('restrictedProducts')) <!--Restricted Products  -->
                @include('adminPanel.partsTel.restrictProductsTel')

              @elseif(Request::is('cupons')) <!-- Coupons -->
                @include('adminPanel.Cupon.cuponIndexTel')

              @elseif(Request::is('Takeaway'))  <!-- Takeaway Products -->
                @include('adminPanel.partsTel.takeawayPageTel')
              @elseif(Request::is('dashboardTakeaway'))
                @include('adminPanel.partsTel.dashboardTakeaway')
              @elseif(Request::is('TakeawayOpenSortingTel'))
                @include('adminPanel.partsTel.takeawaySorting')

              @elseif(Request::is('DeliveryAP')) <!--Delivery -->
                @include('adminPanel.partsTel.deliveryPageTel')
              @elseif(Request::is('dashboardDelivery'))
                @include('adminPanel.partsTel.dashboardDelivery')
              @elseif(Request::is('DeliveryOpenSortingTel'))
                @include('adminPanel.partsTel.deliverySorting')

              @elseif(Request::is('giftCardMngAdmin')) <!-- Gift Card -->
                @include('adminPanel.giftCardTel.gcIndex')

              @elseif(Request::is('tablesCapacity')) <!-- Table Capacity  -->
                @include('adminPanel.partsTel.tableCapacityTel')

              @elseif(Request::is('ClTableChangeIndexAP')) <!-- Table change -->
                @include('adminPanel.partsTel.tableChangeReq')

              @elseif(Request::is('tabReserAdminIndex')) <!-- Table reservation -->
                @include('adminPanel.partsTel.tableReservation')
              @elseif(Request::is('tabReserAdminIndexRezList'))
                @include('adminPanel.partsTel.tableReservationReq')

              @elseif(Request::is('ServiceReqAP')) <!--Service request  -->
                @include('adminPanel.partsTel.serviceReqTel')
                
              @elseif(Request::is('showTips')) <!-- Tips -->
                @include('adminPanel.partsTel.tipsPageTel')
              @elseif(Request::is('showTipsMonth'))
                @include('adminPanel.partsTel.tipsPageMonthTel')

              @elseif(Request::is('waitersDailySales'))
                @include('adminPanel.partsTel.waitersSales') <!-- Waiters sales report -->

              @elseif(Request::is('covidsTel')) <!-- covid-19 -->
                @include('adminPanel.partsTel.covidsTel')

              @elseif(Request::is('dashboardFreieTische'))
                @include('adminPanel.partsTel.freeTablesTel')

              @elseif(Request::is('AdminSaMSG')) <!-- Talk to QRorpa -->
                @include('adminPanel.partsTel.talkToQRorpa')

              @elseif(Request::is('admWoMngIndex')) <!-- Manage workers -->
                @include('adminPanel.mngWorkersTel.index')
              @elseif(Request::is('admWoMngWaiterStatistics'))
                @include('adminPanel.mngWorkersTel.waiterStatistics')
              @elseif(Request::is('admWoMngWaiterStatisticsT'))
                @include('adminPanel.mngWorkersTel.waiterStatisticsT')
              @elseif(Request::is('admWoMngWaiterStatisticsD'))
                @include('adminPanel.mngWorkersTel.waiterStatisticsD')

              @elseif(Request::is('NotificationsAct')) <!-- Menaxhimi Njoftimeve (Aktivizim / deaktivizim) -->
                @include('adminPanel.notifyActiveTel.firstPage')

              @elseif(Request::is('openCheckInOutReports')) <!-- check in/out reports -->
                @include('adminPanel.partsTel.checkinoutReports')

              @elseif(Request::is('BillTablets')) <!-- bill tablets management -->
                @include('adminPanel.partsTel.billTabletsMng')

              @elseif(Request::is('orderServingDevicesPage')) <!-- order serving Devices page -->
                @include('adminPanel.orderServingDevicesTel.indexPage')

              @endif
            @endif
       
          <!-- Kuzhinieri 3 -->
          @elseif(Auth::user()->role == 3)
            @if(Request::is('dashboard'))
              @include('adminPanel.partsKuzhinierTel.orders')
            @endif

          <!-- Barbershop Start -->
          @elseif(Auth::user()->role ==15)
            @if(Request::is('dashboard') || Request::is('dashboardBar'))
              @include('adminPanel.Barbershop.partsTel.terminetDash')
            @elseif(Request::is('barAdminStat'))
              @include('adminPanel.Barbershop.partsTel.statisticsBar')
            @elseif(Request::is('barAdmShowReservationsByMonth'))
              @include('adminPanel.Barbershop.partsTel.statisticsBarByMonth')
            @elseif(Request::is('barAdminWorker'))
              @include('adminPanel.Barbershop.partsTel.workers')
            @elseif(Request::is('barAdmIndexAllConfirmedRez'))
              @include('adminPanel.Barbershop.partsTel.confirmedRez')
            @elseif(Request::is('barAdmRecomendetSer'))
              @include('adminPanel.Barbershop.partsTel.recomendetBarSer')
            @elseif(Request::is('barAdmAddRezervationAdminPage'))
              @include('adminPanel.Barbershop.partsTel.addAdmRezervation')
            @elseif(Request::is('barAdmCuponsMng'))
              @include('adminPanel.Barbershop.partsTel.coupon')
            @endif
          @else
              <?php
                 header("Location: ".route('home'));
                 exit();
              ?>
          @endif
        @endif
      </div> <!-- close tel tab -->

      <script src="{{ asset('js/app.js') }}" defer></script>
      
      <input type="hidden" value="{{Auth::user()->sFor}}" id="theResId">


       

        <!-- dash.sendActiveMSG CODE HERE-->

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