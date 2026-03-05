 <?php
    use Illuminate\Support\Facades\Auth;
    if(!Auth::check() || Auth::user()->email != 'technik@qrorpa.ch' ){
        header("Location: ".route('firstPage.index'));
        exit();
    }
    use App\Events\ActiveAdminPanel;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title> Dashboard</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
    </head>

<body id="page-top">

    @if(isset($_GET['barbershop']))

        <div id="all">
            <div class="DashSideMenu">
                @include('sa.Barbershop.parts.side')
            </div>

            @include('sa.Barbershop.parts.topNavBar')

            <div id="content">
            
                @if(Request::is('produktet1'))
                    @include('sa.Barbershop.parts.firstPage')
                @elseif(Request::is('barbershopBarbershops'))
                    @include('sa.Barbershop.parts.barbershops')
                @elseif(Request::is('barbershopBarbershopsOne'))
                    @include('sa.Barbershop.parts.barbershopsOne')

                <!-- Services  -->
                @elseif(Request::is('barbershopServicesIndex'))
                    @include('sa.Barbershop.services.servicesIndex')
                @elseif(Request::is('barbershopServicesSelBar'))
                    @include('sa.Barbershop.services.servicesSelBar')
                <!-- Catgory -->
                @elseif(Request::is('barbershopServicesCategory'))
                    @include('sa.Barbershop.services.servicesCategory')
                <!-- Types -->
                @elseif(Request::is('barbershopServicesType'))
                    @include('sa.Barbershop.services.servicesType')
                <!-- Extra -->
                @elseif(Request::is('barbershopServicesExtra'))
                    @include('sa.Barbershop.services.servicesExtra')
                <!-- Service -->
                @elseif(Request::is('barbershopServicesService'))
                    @include('sa.Barbershop.services.servicesService')
                    
                <!-- User MNG -->
                @elseif(Request::is('barUsers'))
                    @include('sa.Barbershop.parts.userMng')

                <!-- Banner-->
                @elseif(Request::is('barbershopbannerSAPage'))
                    @include('sa.Barbershop.parts.bannerMng')

                <!-- Ratings-->
                @elseif(Request::is('barbershopRatingsSA'))
                    @include('sa.Barbershop.parts.ratingsMng')
                @endif
                
            </div>
        </div>

    @else

      <div id="all">
            <div class="DashSideMenu">
                @include('sa.parts.side')
            </div>

            @include('sa.parts.topNavBar')

        <div id="content">
        
            @if(Request::is('produktet1'))
                @include('sa.parts.firstPage')

            @elseif(Request::is('Restorantet'))
                @include('sa.parts.restorantet')

            @elseif(Request::is('SAaccessMngOpenPage'))
                @include('sa.parts.accessMngPage')

            @elseif(Request::is('SuperAdminRestorantOne'))
                @include('sa.parts.restorantetOne')
                
            @elseif(Request::is('RestorantetWH'))
                @include('sa.parts.restorantetWorkingHours')

            @elseif(Request::is('OrdersSa'))
                @include('sa.parts.ordersAll')

            @elseif(Request::is('tables'))
                @include('sa.parts.tables')

            @elseif(Request::is('adsModuleSaIndex'))
                @include('sa.parts.adsSaPage')

            @elseif(Request::is('oPayMngIndex'))
                @include('sa.parts.onlinePayMng')

            <!--Restaurant Content manage  -->
            @elseif(Request::is('produktet5'))
                @include('sa.parts.manageRes')
            @elseif(Request::is('produktet5Boxes'))
                @include('sa.parts.manageBoxes')
            @elseif(Request::is('produktet5Category'))
                @include('sa.parts.manageCategory')
            @elseif(Request::is('produktet5Extra'))
                @include('sa.parts.manageExtra')
            @elseif(Request::is('produktet5Type'))
                @include('sa.parts.manageType')
            @elseif(Request::is('produktet5Product'))
                @include('sa.parts.manageProduct')
            <!-- End -->
            
            @elseif(Request::is('addAllRestaurants'))
                @include('sa.parts.addAllRestaurants')

            @elseif(Request::is('userAdmin'))
                @include('sa.parts.userAdmin')
            @elseif(Request::is('userKuzhinier'))
                @include('sa.parts.userKuzhinier')
            @elseif(Request::is('userKamarier'))
                @include('sa.parts.userKamarier')

            <!-- Pic Library -->
            @elseif(Request::is('PictureLibSAIndex'))
                @include('sa.parts.picLibIndex')

            @elseif(Request::is('ratings'))
                @include('sa.parts.ratings')
            @elseif(Request::is('covids'))
                @include('sa.parts.covids')
            @elseif(Request::is('restaurantsRatings'))
                @include('sa.parts.restaurantsRatings')
            @elseif(Request::is('restaurantCovers'))
                @include('sa.parts.restaurantCovers')

            @elseif(Request::is('Piket'))
                @include('sa.points.index')
            @elseif(Request::is('PiketRes'))
                @include('sa.points.restorants')
            @elseif(Request::is('PiketResOne'))
                @include('sa.points.restorantsOne')
            @elseif(Request::is('PiketResOneMY'))
                @include('sa.points.restorantsOneMY')
            @elseif(Request::is('PiketCli'))
                @include('sa.points.clients')
            @elseif(Request::is('PiketCliOne'))
                @include('sa.points.clientsOne') 
                
            @elseif(Request::is('SAStatistics'))
                @include('sa.stats.index') 
            @elseif(Request::is('SAStatisticsRes'))
                @include('sa.stats.indexRes') 
            
            <!-- Contracts-->
            @elseif(Request::is('restaurantOffers'))
                @include('sa.parts.restaurantOffers')

            <!-- Invoices-->
            @elseif(Request::is('invoices'))
                @include('sa.parts.invoices')

            <!-- talk to qrorpa-->
            @elseif(Request::is('SaAdminMSG'))
                @include('sa.parts.talkToAdmins')

            @endif
            
        </div>
      </div>
    @endif

  <script src="{{ asset('js/app.js') }}" defer></script>
 

</body>

</html>