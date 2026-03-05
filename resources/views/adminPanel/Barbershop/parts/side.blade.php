<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    use App\Orders;
    use App\Waiter;
    use Carbon\Carbon;
    use App\BarbershopServiceOrder;

    if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
        echo '<a href="/?Res='.$_SESSION["Res"].'&t='.$_SESSION["t"].'">';
    }else{
        echo '<a href="{{ url("/")}}">';
    }

    $theR = Auth::user()->sFor;

?>



<img style="width:12%;" class="mt-4 pl-4" src="/storage/images/logo_QRorpa_wh.png" alt="">
</a>

<style>
    .sideIconAP{
        padding: 5px;
    }
    .newSideElAdmin{
        margin-left:-45px;
    }
    .newSideElAdmin:hover{
        text-decoration: none;
    }



    .glow {
        color: #fff;
        -webkit-animation: glow 1s ease-in-out infinite alternate;
        -moz-animation: glow 1s ease-in-out infinite alternate;
        animation: glow 1s ease-in-out infinite alternate;
    }

    @-webkit-keyframes glow {
        from {
            text-shadow: 0 0 10px #fff, 0 0 12px #fff, 0 0 14px #e60073, 0 0 16px #e60073, 0 0 18px #e60073, 0 0 20px #e60073, 0 0 22px #e60073;
        }
        to {
            text-shadow: 0 0 12px #fff, 0 0 14px #ff4da6, 0 0 16px #ff4da6, 0 0 18px #ff4da6, 0 0 20px #ff4da6, 0 0 22px #ff4da6, 0 0 24px #ff4da6;
        }
    }
</style>


<div id="sideExtButtons" style="margin-top:70px;">
 
        <a href="{{route('barAdmin.indexStatistics')}}" class="newSideElAdmin">
            @if(Request::is('barAdminStat') || Request::is('barAdmShowReservationsByMonth'))
                <img class="sideIconAP" src="/storage/images/APstatistika02.PNG" alt=""> 
                    <span class="glow" style="color:white;"><strong>{{__('adminP.statistics')}}</strong></span>
            @else
                <img class="sideIconAP" src="/storage/images/APstatistika01.PNG" alt=""> 
                    <span style="color:white;"><strong>{{__('adminP.statistics')}}</strong></span>
            @endif
        </a>
        <br><br>
        

        <a class="allowNotiOrders newSideElAdmin" href="{{route('barAdmin.indexReservierung')}}">
            @if(BarbershopServiceOrder::where([['status','0'],['toBar', $theR]])->get()->count() == 0)
                @if(Request::is('dashboard') || Request::is('dashboardBar'))
                    <img class="sideIconAP" src="storage/images/barService02.png" alt="">
                    <span class="glow" style="color:white;"><strong>{{__('adminP.assignments')}}</strong></span>
                @else
                    <img class="sideIconAP" src="storage/images/barService01.png" alt="">
                    <span style="color:white;"><strong>{{__('adminP.assignments')}}</strong></span>
                @endif
            @else
                <img class="sideIconAP" src="storage/images/barService03.png" alt="">
                <span style="color:white;"><strong>{{__('adminP.assignments')}}</strong></span>
            @endif
        </a>
        <br><br>


        <a class="allowNotiOrders newSideElAdmin" href="{{route('barAdmin.addReservationAdminPage')}}">
            @if(Request::is('barAdmAddRezervationAdminPage'))
                <img class="sideIconAP" src="storage/images/barServicePlus02.png" alt="">
                <span class="glow" style="color:white;"><strong>{{__('adminP.registerReservation')}}</strong></span>
            @else
                <img class="sideIconAP" src="storage/images/barServicePlus01.png" alt="">
                <span style="color:white;"><strong>{{__('adminP.registerReservation')}}</strong></span>
            @endif
        </a>
        <br><br>



        <a class="allowNotiOrders newSideElAdmin" href="{{route('barAdmin.indexAllConfirmedRez')}}">
            @if(Request::is('barAdmIndexAllConfirmedRez') )
                <img class="sideIconAP" src="storage/images/barServiceConfirmed02.png" alt="">
                <span class="glow" style="color:white;"><strong>{{__('adminP.reservation')}}</strong></span>
            @else
                <img class="sideIconAP" src="storage/images/barServiceConfirmed01.png" alt="">
                <span style="color:white;"><strong>{{__('adminP.reservation')}}</strong></span>
            @endif
        </a>
        <br><br>

        <a class="allowNotiOrders newSideElAdmin" href="{{route('barAdmin.indexRecomendetSer')}}">
            @if(Request::is('barAdmRecomendetSer') )
                <img class="sideIconAP" src="storage/images/barServiceRecomendet02.png" alt="">
                <span class="glow" style="color:white;"><strong>{{__('adminP.recommended')}}</strong></span>
            @else
                <img class="sideIconAP" src="storage/images/barServiceRecomendet01.png" alt="">
                <span style="color:white;"><strong>{{__('adminP.recommended')}}</strong></span>
            @endif
        </a>
        <br><br>

        
        <a class="allowNotiOrders newSideElAdmin" href="{{route('barAdmin.indexCuponMng')}}">
            @if(Request::is('barAdmCuponsMng') )
                <img class="sideIconAP" src="storage/images/CuponsC.PNG" alt="">
                <span class="glow" style="color:white;"><strong>{{__('adminP.coupons')}}</strong></span>
            @else
                <img class="sideIconAP" src="storage/images/CuponsW.PNG" alt="">
                <span style="color:white;"><strong>{{__('adminP.coupons')}}</strong></span>
            @endif
        </a>
        <br><br>



        <a href="{{route('barAdmin.indexWorker')}}" class="newSideElAdmin">
            @if(Request::is('barAdminWorker'))
                <img class="sideIconAP " src="/storage/images/Waiter02.PNG" alt="">
                <span class="glow" style="color:white;"><strong>{{__('adminP.worker')}}</strong></span>
            @else
                <img class="sideIconAP" src="/storage/images/Waiter01.PNG" alt="">
                <span style="color:white;"><strong>{{__('adminP.worker')}}</strong></span>
            @endif
        </a>
   
</div>






