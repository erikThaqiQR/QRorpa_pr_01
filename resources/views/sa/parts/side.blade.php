

<?php
     if(!isset($_COOKIE["PHPSESSID"])){
        session_start();
    }
  
    use App\admMsgSaavchats;
    use Illuminate\Support\Facades\Auth;
?>
    <style>
        
        @keyframes glowing {
        0% { box-shadow: 0 0 -10px red; background-position: 0 0;}
        40% { box-shadow: 0 0 20px red; }
        60% { box-shadow: 0 0 20px red; }
        100% { box-shadow: 0 0 -10px red; background-position: 1280px 0;}
        }

        @keyframes wiggle {
            0%, 7% {transform: rotateZ(0);}
            15% { transform: rotateZ(-15deg);}
            20% {transform: rotateZ(10deg);}
            25% {transform: rotateZ(-10deg);}
            30% {transform: rotateZ(6deg);}
            35% {transform: rotateZ(-4deg);}
            40%, 100% {transform: rotateZ(0);}
        }

        .side-glow-el{
            animation: glowing 1000ms linear infinite;
            border-radius: 6px;
            cursor: pointer;
        }
        .side-wiggle-el{
            animation: wiggle 1000ms linear infinite;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>

    <img width="10%;" class="mt-4 ml-4" src="/storage/images/logo_QRorpa_wh.png" alt="">
 



<!-- Pjesen posht / Kushtet / duhet me i konvertu ne Laravel  -->
<!-- -->
<!-- -->
<!-- -->

<div class="d-flex mt-2 mb-2"> 
    <a href="produktet1"><button style="width:140px;" class="btn btn-dark mr-1">Restaurant</button></a>
    <a href="produktet1?barbershop"><button style="width:140px;" class="btn btn-outline-dark">Barbershop</button></a>
</div>




<div id="sideExtButtons" style="margin-top:10px;">
    <a href="{{route('manageProduktet.index')}}" class="mb-3">
        @if(Request::is('produktet1'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAI01C.png" alt="1">
                </div>  
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Statistiken</ins></strong></p>
            </div>
            
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/SAI01W.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Statistiken</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('atsMsg.openPageSuperadminPanel')}}" id="atsMsgOpenPageSuperadminPanel" class="mb-3">
        @if(Request::is('SaAdminMSG'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/talkToAdmins01C.png" alt="1">
                </div>  
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Reden mit ...</ins></strong></p>
            </div>
        @else
            @if (admMsgSaavchats::where([['msgForId',Auth::User()->id],['readStatus',0]])->count() > 0)
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" class=" side-glow-el" src="/storage/icons/talkToAdmins01R.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Reden mit ...</strong></p>
            </div>
            @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/talkToAdmins01W.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Reden mit ...</strong></p>
            </div>
            @endif
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('oPayMng.onlinePayIndex')}}" class="mb-3">
        @if(Request::is('oPayMngIndex'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAI01OPayC.png" alt="1">
                </div>  
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Online-Zahlung</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/SAI01OPayW.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Online-Zahlung</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('restorantet.index')}}" class="mb-4"">
        @if(Request::is('Restorantet') || Request::is('RestorantetWH') || Request::is('SuperAdminRestorantOne'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/RES01C.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Restaurants</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/RES01W.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Restaurants</strong></p>
            </div>
        @endif
    </a>

    <a href="{{route('SAaccessMng.index')}}" class="mb-4"">
        @if(Request::is('SAaccessMngOpenPage'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/accessMng01C.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Zugangskontrolle </ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/accessMng01W.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Zugangskontrolle</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->
    
    <a href="{{route('piket.index')}}" class="mb-4">
        @if(Request::is('Piket') || Request::is('PiketRes') || Request::is('PiketCli') || Request::is('PiketResOne') || Request::is('PiketCliOne') || Request::is('PiketResOneMY'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/pointsIconQ.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Punkte</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/pointsIconW.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Punkte</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->
    
    <a href="{{route('table.index')}}" class="mb-4">
        @if(Request::is('tables'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAI01TblC.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Tischen</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/SAI01TblW.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Tischen</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('adsModuleSa.index')}}" class="mb-4">
        @if(Request::is('adsModuleSaIndex'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/saAdIconQrorpa.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Anzeigen</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/saAdIconWhite.png" alt="">
                </div>
                 <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Anzeigen</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('ordersSa.index')}}" class="mb-4">
        @if(Request::is('OrdersSa'))
            <div class="d-flex" style="margin-left:-40px;">
                <div class="" style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAI01OrC.png" alt="">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Aufträge</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:36px; height:36px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/SAI01OrW.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Aufträge</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('SAProduktet.index')}}" class="mb-4">
        @if(Request::is('produktet5'))
            <div class="d-flex" style="margin-left:-40px;">
                <div class="" style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAI01ProC.png" alt="">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Restaurantprodukte</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:36px; height:36px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/SAI01ProW.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Restaurantprodukte</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('userAd.index')}}" class="mb-4">
        @if(Request::is('userAdmin') || Request::is('userKuzhinier') || Request::is('userKamarier'))
            <div class="d-flex" style="margin-left:-40px;">
                <div class="" style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/usersC.png" alt="">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Benutzer</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:36px; height:36px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/usersW.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Benutzer</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('saStatistics.index')}}" class="mb-4">
        @if(Request::is('SAStatistics') || Request::is('SAStatisticsRes'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/saStatIconC.png" alt="">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Restaurantklicks</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/saStatIconW.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Restaurantklicks</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('PicLibrary.indexSA')}}" class="mb-4">
        @if(Request::is('PictureLibSAIndex'))
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAIconPicC.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Bildbibliothek</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/SAIconPicW.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Bildbibliothek</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('ratings.index')}}" class="mb-4">
        @if(Request::is('ratings'))
            <div class="d-flex" style="margin-left:-40px;">
                <div class="" style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/star-green.png" alt="">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Bewertungen</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/star-white.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Bewertungen</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('restaurantsRatings.index')}}" class="mb-4">
        @if(Request::is('restaurantsRatings'))
            <div class="d-flex" style="margin-left:-40px;">
                <div class="" style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/star-green.png" alt="">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Bewertungen</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/star-white.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Bewertungen</strong></p>
            </div>
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('restaurantCovers.index')}}" class="mb-4">
        @if(Request::is('restaurantCovers'))
            <div class="d-flex" style="margin-left:-40px;">
                <div class="" style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/marketing-green-icon.png" alt="">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Abdeckungen</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/marketing-white-icon.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Abdeckungen</strong></p>
            </div>
        @endif
    </a>
   
    <a href="{{route('restaurantOffers.SAindex')}}" class="mb-4">
        @if(Request::is('restaurantOffers'))
            <div class="d-flex" style="margin-left:-40px;">
                <div class="" style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAI01ProC.png" alt="">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Verträge</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/SAI01ProW.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Verträge</strong></p>
            </div>
        @endif
    </a>

   <a href="{{route('invoices.SAindex')}}" class="mb-4">
        @if(Request::is('invoices'))
            <div class="d-flex" style="margin-left:-40px;">
                <div class="" style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAI01ProC.png" alt="">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Rechnungen</ins></strong></p>
            </div>
        @else
            <div class="d-flex" style="margin-left:-40px;">
                <div style="width:45px; height:45px;">
                    <img style="width:45px; height:45px; padding:10px;" src="/storage/icons/SAI01ProW.png" alt="">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Rechnungen</strong></p>
            </div>
        @endif
    </a>
    <br><br>
   

    
</div>


