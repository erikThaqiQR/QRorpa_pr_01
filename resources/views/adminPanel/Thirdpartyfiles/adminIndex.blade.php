@if(!Auth::check())
  <?php
    header("Location: ".route('home'));
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







  

</head>

<body id="page-top">



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

        @elseif(Auth::user()->role == 33) 
            <div id="all">
                   <div class="DashSideMenu">
                   @include('adminPanel.Contracts.parts.side')
                </div>
        
                @include('adminPanel.Contracts.parts.topNavbar')
                 <div id="content">
                @if(Request::is('contracts'))
                  @include('adminPanel.Contracts.parts.contracts')
                  @endif
                </div>
              </div>


    @elseif(Auth::user()->role == 5)
      <div id="all">
        <div class="DashSideMenu" id="DashSideMenuDesktop">
          @include('adminPanel.parts.side')
        </div>
        @include('adminPanel.parts.topNavbar')

        <div id="content">
          
            @if((Auth::user()->role != 5 && Auth::user()->role != 3 && Auth::user()->role != 4 ) || Auth::user()->sFor === 0 )
              <?php
                header("Location: ".URL::previous());
                exit();
              ?>
            @elseif(Auth::user()->sFor != 0 && (Auth::user()->role == 5 || Auth::user()->role == 4))

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
              @if(Request::is('dashboard') || Request::is('dashboard2') || Request::is('dashboard3'))
                @include('adminPanel.parts.porositDash')
              @elseif(Request::is('dashboardList'))
                @include('adminPanel.parts.porositDashList')

              @elseif(Request::is('dashboardaddNewProductOrPage'))
                @include('adminPanel.parts.newProductOrPage')
                
              @elseif(Request::is('statusWorker'))
                @include('adminPanel.parts.statusWorker')
                
              @elseif(Request::is('dashboardStatistics'))
                @include('adminPanel.parts.porositStat')
              @elseif(Request::is('statsDashSalesStatistics'))
                @include('adminPanel.parts.salesStatistics')
              @elseif(Request::is('recomendet'))
                @include('adminPanel.parts.recomendet')
              
              @elseif(Request::is('statsDash01'))
                @include('adminPanel.parts.dashboard01')
              @elseif(Request::is('statsDash02'))
                @include('adminPanel.parts.dashboard02')

              <!-- Content management -->
              @elseif(Request::is('dashboardContentMng'))
                @include('adminPanel.manageContent.firstPage')
              @elseif(Request::is('dashboardContentMng/Order'))
                @include('adminPanel.manageContent.firstPageOrder')
             

              <!-- Waiter -->
              @elseif(Request::is('callWaiterIndex'))
                @include('adminPanel.partsKamarier.firstPage')

              <!-- Change Table -->
              @elseif(Request::is('ClTableChangeIndexAP'))
                @include('adminPanel.parts.tableCHReq')


              <!--Free Products  -->
              @elseif(Request::is('freeProducts'))
                @include('adminPanel.parts.freeProducts')

              <!--Restricted Products  -->
              @elseif(Request::is('restrictedProducts'))
                @include('adminPanel.parts.restrictProducts')

              <!--Takeaway -->
              @elseif(Request::is('Takeaway'))
                @include('adminPanel.parts.takeawayPage')
              @elseif(Request::is('dashboardTakeaway'))
                @include('adminPanel.parts.dashboardTakeaway')

              <!--Delivery -->
              @elseif(Request::is('DeliveryAP'))
                @include('adminPanel.parts.deliveryPage')
              @elseif(Request::is('dashboardDelivery'))
                @include('adminPanel.parts.dashboardDelivery')

              <!-- Tips -->
              @elseif(Request::is('showTips'))
                @include('adminPanel.parts.tipsPage')
              @elseif(Request::is('showTipsMonth'))
                @include('adminPanel.parts.tipsPageMonth')

              <!-- Cupon -->
              @elseif(Request::is('cupons'))
                @include('adminPanel.Cupon.cuponIndex')

              <!-- Table Capacity  -->
              @elseif(Request::is('tablesCapacity'))
                @include('adminPanel.parts.tableCapacity')

              <!-- table reservation -->
              @elseif(Request::is('tabReserAdminIndex'))
                @include('adminPanel.parts.tableReservation')
              @elseif(Request::is('tabReserAdminIndexRezList'))
                @include('adminPanel.parts.tableReservationReq')
              
              <!--Service request  -->
              @elseif(Request::is('ServiceReqAP'))
                @include('adminPanel.parts.serviceReq')

              @elseif(Request::is('covidsAdmin'))
                @include('adminPanel.parts.covidsAdmin')

              <!-- Free Tabels -->
              @elseif(Request::is('dashboardFreieTische'))
                @include('adminPanel.parts.freeTables')



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

































      <div id="allTel">

              <!-- New order alert for other pages TEL -->
              <div class="alert alert-success p-2 mt-4" role="alert" style="position:fixed; width:96%; left:2%; z-index:100; border: 2px solid rgb(39,190,175); display:none;" 
                  id="adminPAlertWindowTel">
                <h4 class="alert-heading d-flex">
                  <span style="width:90%;">{{__('adminP.notificationForYou')}}</span>
                  <span onclick="closeAdminPAlertWindowTel()"style="width:10%; font-weight:bold;" class="text-right">X</span>
                </h4> 
                <hr>
                <div >
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
      
                  <!-- OptionsModal Phone -->
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
                                          {{__('adminP.myAccount')}}
                                      </p>
                                  </div>
                              @endif
                          </div>
                          <div class="d-flex justify-content-between text-center" style="margin-top:40px;">
                              @if(Auth::check())
                                  <a class=" btn btn-block {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('logout') }}" 
                                      style="border:2px solid lightgray; color:black; width:50%; margin-left:25%;"
                                      onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                                      {{ __('adminP.logOut') }}
                                  </a>
                                  <form class="optionsAnchorPh" id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                      @csrf
                                  </form>
                              @else
                                  <a class=" btn {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:2px solid lightgray; color:black; width:48%;"
                                  href="{{ route('login') }}">{{__('adminP.login')}}</a>
                                            
                                  <a class="btn  {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:4px solid rgb(39, 190, 175); color:black; width:48%;"
                                  href="{{ route('register') }}">{{__('adminP.register')}}</a>
                                
                              @endif
                          </div>

                          <div style="background-color:whitesmoke; width:100%;" class="mt-2">
                      
                    
                                                    <div>

                                                      @if(Auth::user()->ehcchurworker == 0)
                                                      <a class="optionsAnchorPh" href="{{route('dash.statistics')}}">
                                                        <i class="fas fa-chart-line"></i>  {{__('adminP.statistics')}}      
                                                      </a>
                                                      @endif
                                                      @if(Auth::user()->role == 5 || Auth::user()->role == 4 )
                                                        @if(Auth::user()->ehcchurworker == 0)
                                                        <a class="optionsAnchorPh" href="{{route('dash.index')}}">
                                                          <i class="fas fa-border-all"></i>  {{__('adminP.orders')}}
                                                        </a>
                                                        @endif

                                                        @if(Auth::user()->sFor != 22 && Auth::user()->sFor != 23)
                                                        <a class="optionsAnchorPh" onclick="location.href = '/dashboardTakeaway';">
                                                          <i class="fas fa-border-all"></i> {{__('adminP.takeawayOrders')}}
                                                        </a>
                                                        @endif
                                                        @if(Auth::user()->sFor != 22 && Auth::user()->sFor != 23)
                                                        <a class="optionsAnchorPh" onclick="location.href = '/dashboardDelivery';">
                                                          <i class="fas fa-border-all"></i> {{__('adminP.deliveryOrders')}}
                                                        </a>
                                                        @endif
                                                        
                                                        @if(Auth::user()->sFor != 22 && Auth::user()->sFor != 23)
                                                        <a class="optionsAnchorPh" href="{{route('dash.recom')}}">
                                                          <i class="fas fa-band-aid"></i>  {{__('adminP.suggestedProducts')}}
                                                        </a>
                                                        @endif
                                                        @if(Auth::user()->ehcchurworker == 0)
                                                        <a class="optionsAnchorPh" href="{{route('dash.indexConMng')}}">
                                                          <i class="fas fa-sitemap"></i> {{__('adminP.products')}}
                                                        </a>
                                                          @if(Auth::user()->sFor != 22 && Auth::user()->sFor != 23)
                                                          <a class="optionsAnchorPh" href="{{route('TabChngCli.indexAP')}}">
                                                            <i class="fas fa-random"></i> {{__('adminP.changeTable')}}
                                                          </a>
                                                          @endif
                                                        @endif

                                                        @if(Auth::user()->sFor != 22 && Auth::user()->sFor != 23)
                                                        <a class="optionsAnchorPh" href="{{route('dash.indexFreeTables')}}">
                                                        <i class="fas fa-exclamation"></i> {{__('adminP.tables')}}
                                                        </a>
                                                      
                                                        <a class="optionsAnchorPh" href="{{route('waiter.index')}}">
                                                          <i class="fas fa-user-edit"></i>  {{__('adminP.customerCalls')}}
                                                        </a>
                                                        @endif

                                                        @if(Auth::user()->ehcchurworker == 0)
                                                        <a class="optionsAnchorPh" href="{{route('tips.index')}}">
                                                          <i class="fas fa-coins"></i>  {{__('adminP.tip')}}
                                                        </a>
                                                        @endif
                                                    
                                                        @if(Auth::user()->sFor != 22 && Auth::user()->sFor != 23)
                                                        <a class="optionsAnchorPh" href="{{route('dash.statusWorker')}}">
                                                            <i class="far fa-address-card"></i>  {{__('adminP.employees')}}
                                                        </a>
                                                        @endif

                                                        @if(Auth::user()->ehcchurworker == 0)
                                                        <a class="optionsAnchorPh" href="{{route('cupons.index')}}">
                                                          <i class="fas fa-ticket-alt"></i>  {{__('adminP.couponCode')}}
                                                        </a>
                                                        
                                                        <a class="optionsAnchorPh" href="{{route('takeaway.index')}}">
                                                          <i class="fas fa-hotdog"></i>  {{__('adminP.takeaway')}}
                                                        </a>
                                                        @endif

                                                        @if(Auth::user()->sFor != 22 && Auth::user()->sFor != 23)
                                                        <a class="optionsAnchorPh" href="{{route('delivery.index')}}">
                                                          <i class="fas fa-truck"></i>  {{__('adminP.delivery')}}
                                                        </a>

                                                          @if(Auth::user()->ehcchurworker == 0)
                                                          <a class="optionsAnchorPh" href="{{route('TableReservation.ADIndex')}}">
                                                            <i class="fas fa-receipt"></i>  {{__('adminP.tableReservations')}}
                                                          </a>
                                                          
                                                          <a class="optionsAnchorPh" href="{{route('freeProd.index')}}">
                                                            <i class="fas fa-align-left"></i>  {{__('adminP.free')}}
                                                          </a>
                                                          @endif
                                                        @endif

                                                        @if(Auth::user()->sFor != 22 && Auth::user()->sFor != 23)
                                                        <a class="optionsAnchorPh" href="{{route('restrictProd.index')}}">
                                                            <i class="fas fa-ban"></i>  {{__('adminP.ageRestricion')}}
                                                        </a>
                                                      
                                                        <a class="optionsAnchorPh" href="{{route('dash.covidsTel')}}">
                                                            <i class="fas fa-virus"></i>  Covid-19 {{__('adminP.contactForm')}}
                                                        </a>
                                                        @endif
                                                      
                                                      
                                                      @endif
                                                    </div>
                                                </div>
                          </div>
                          
                        
                            

                          <div class="text-center mt-3" >
                              <div class="text-center">
                                  <button type="button" class="close text-center pb-3 pr-4" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>






              <!-- The Modal -->
              <div class="modal" id="optionsModalBar">
                <div class="modal-dialog">
                  <div class="modal-content" style="border-radius:30px;">

                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color:rgb(39, 190, 175);">
                      <h4 style="color:white;" class="modal-title"><strong>{{__('adminP.administrationMenu')}}</strong></h4>
                      <button style="color:white;" type="button" class="close" data-dismiss="modal"><strong>X</strong></button>
                    </div>

                    <!-- Modal body -->
                    <div style="background-color:whitesmoke; width:100%;">
                      <div style="background-color:whitesmoke; width:100%;" class="mt-2">
                        <div>

                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexStatistics')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-chart-line pr-2"></i>  {{__('adminP.statistics')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexReservierung')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-hand-holding-medical pr-2"></i> {{__('adminP.assignments')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.addReservationAdminPage')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-plus pr-2"></i> {{__('adminP.registerReservation')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexAllConfirmedRez')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-hand-holding pr-2"></i> {{__('adminP.reservation')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexRecomendetSer')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-hand-holding-heart pr-2"></i> {{__('adminP.recommended')}}      
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexCuponMng')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-barcode pr-2"></i> {{__('adminP.coupons')}}     
                          </a>
                          <a class="optionsAnchorPh" href="{{route('barAdmin.indexWorker')}}" style="color:rgb(72,81,87);">
                            <i class="fas fa-lg fa-users-cog pr-2"></i> {{__('adminP.worker')}}      
                          </a>

                        </div>
                      </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-block btn-outline-dark" data-dismiss="modal"><strong>{{__('adminP.conclude')}}</strong></button>
                    </div>

                  </div>
                </div>
              </div>
      </div>






















      @if(Auth::user()->role == 15)
        <!-- Barbershop Smartphone Nav -->
        <nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-12 text-left">
                          <?php
                              if(isset($_SESSION['Bar'])){
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
                         <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModalBar"><img src="/storage/icons/listDown.PNG"/></button> 
                      </div>
                  </div>
              </div>
          </nav>
      @elseif(Auth::user()->role == 33)
        <!-- Barbershop Smartphone Nav -->
        <nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-12 text-left">
                          <?php
                              if(isset($_SESSION['contracts'])){
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
                         <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModalContract"><img src="/storage/icons/listDown.PNG"/></button> 
                      </div>
                  </div>
              </div>
          </nav>


      @elseif(Auth::user()->role == 5)
        <!-- Restorant Smartphone Nav -->
        <nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-12 text-left">
                          <?php
                              if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
                                  echo '<a class="navbar-brand" href="/?Res='.$_SESSION["Res"].'&t='.$_SESSION["t"].'">';
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
                        @if(isset($_GET['tabs']))
                          <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img src="../storage/icons/listDown.PNG"/></button> 
                        @else
                         <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img src="/storage/icons/listDown.PNG"/></button> 
                         @endif
                      </div>
                  </div>
              </div>
          </nav>
      @endif










          <div id="soundsAll">
          <audio id="beepN" src="{{ asset('storage/sound/swiftBeep.mp3')}}" type="audio/mpeg" autoplay="true"></audio>
          <source id="newRing" src="{{ asset('storage/sound/swiftBeep.mp3')}}">
          </div>




          @if(Auth::user()->role == 5 || Auth::user()->role == 4)
            @if(Auth::user()->sFor != 0)
                
              @if(Request::is('dashboard'))
                @include('adminPanel.partsTel.porositDashTel')

              @elseif(Request::is('statusWorker'))
                @include('adminPanel.partsTel.statusWorker')

              @elseif(Request::is('dashboardStatistics'))
                @include('adminPanel.partsTel.porositStatTel')
              @elseif(Request::is('statsDashSalesStatistics'))
                @include('adminPanel.partsTel.salesStatistics')

              @elseif(Request::is('dashboardaddNewProductOrPage'))
                @include('adminPanel.partsTel.newProductOrPageTel')

              @elseif(Request::is('recomendet'))
                @include('adminPanel.partsTel.recomendetTel')
              
              @elseif(Request::is('statsDash01'))
                @include('adminPanel.partsTel.dashboard01tel')
              @elseif(Request::is('statsDash02'))
                @include('adminPanel.partsTel.dashboard02tel')
              
              <!-- Content management -->
              @elseif(Request::is('dashboardContentMng'))
                @include('adminPanel.manageContentTel.firstPage')
              @elseif(Request::is('dashboardContentMng/Order'))
                @include('adminPanel.manageContentTel.firstPageOrder')


              <!--Free Products  -->
              @elseif(Request::is('freeProducts'))
                @include('adminPanel.partsTel.freeProductsTel')

                 <!--Restricted Products  -->
              @elseif(Request::is('restrictedProducts'))
                @include('adminPanel.partsTel.restrictProductsTel')

                <!-- Coupons -->
              @elseif(Request::is('cupons'))
                @include('adminPanel.Cupon.cuponIndexTel')

              <!-- Takeaway Products -->
              @elseif(Request::is('Takeaway'))
                @include('adminPanel.partsTel.takeawayPageTel')
              @elseif(Request::is('dashboardTakeaway'))
                @include('adminPanel.partsTel.dashboardTakeaway')
              @elseif(Request::is('TakeawayOpenSortingTel'))
                @include('adminPanel.partsTel.takeawaySorting')

              <!--Delivery -->
              @elseif(Request::is('DeliveryAP'))
                @include('adminPanel.partsTel.deliveryPageTel')
              @elseif(Request::is('dashboardDelivery'))
                @include('adminPanel.partsTel.dashboardDelivery')
              @elseif(Request::is('DeliveryOpenSortingTel'))
                @include('adminPanel.partsTel.deliverySorting')

              <!-- Waiter calls -->
              @elseif(Request::is('callWaiterIndex'))
                @include('adminPanel.partsKamarierTel.firstPage')

              <!-- Table change -->
              @elseif(Request::is('ClTableChangeIndexAP'))
                @include('adminPanel.partsTel.tableChangeReq')

              <!-- Table reservation -->
              @elseif(Request::is('tabReserAdminIndex'))
                @include('adminPanel.partsTel.tableReservation')
              @elseif(Request::is('tabReserAdminIndexRezList'))
                @include('adminPanel.partsTel.tableReservationReq')
                
              @elseif(Request::is('showTips'))
                @include('adminPanel.partsTel.tipsPageTel')
              @elseif(Request::is('showTipsMonth'))
                @include('adminPanel.partsTel.tipsPageMonthTel')
              @elseif(Request::is('covidsTel'))
                @include('adminPanel.partsTel.covidsTel')



              @elseif(Request::is('dashboardFreieTische'))
                @include('adminPanel.partsTel.freeTablesTel')

              @endif
            

            @endif
       
          <!-- Kuzhinieri 3 -->
          @elseif(Auth::user()->role == 3)
            @if(Request::is('dashboard'))
              @include('adminPanel.partsKuzhinierTel.orders')
            @endif
           
          @elseif(Auth::user()->role == 33) 
                @if(Request::is('contracts'))
                  @include('adminPanel.Contracts.partsTel.contracts')
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
      </div> <!-- close tel tab -->





     
  <script src="{{ asset('js/app.js') }}" defer></script>
   
  <script>
    if(screen.width <= 580){
      $('#all').hide();
    }else{
      $('#allTel').hide();
    }
  </script>





<input type="hidden" value="{{Auth::user()->sFor}}" id="theResId">



@if(Auth::user()->role == 5 || Auth::user()->role == 4)
  <script>
    
          function sendIMActiveAP(){
            $.ajax({
              url: '{{ route("dash.sendActiveMSG") }}',
              method: 'post',
              data: {
                resId: $('#theResId').val(),
                _token: '{{csrf_token()}}'
              },
              success: () => {
                $("#freeProElements").load(location.href+" #freeProElements>*","");
              },
              error: (error) => {
                console.log(error);
              }
            });
          }
          
          setInterval(sendIMActiveAP,5000);


  </script>
@endif

</body>

</html>