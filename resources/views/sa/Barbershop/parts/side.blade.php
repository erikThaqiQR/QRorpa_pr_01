

<?php
     if(!isset($_COOKIE["PHPSESSID"])){
        session_start();
    }

?>

    <img width="10%;" class="mt-4 ml-4" src="/storage/images/logo_QRorpa_wh.png" alt="">



<!-- Pjesen posht / Kushtet / duhet me i konvertu ne Laravel  -->
<!-- -->
<!-- -->
<!-- -->

<div class="d-flex mt-2 mb-2"> 
    <a href="produktet1"><button style="width:140px;" class="btn btn-outline-dark mr-1">Restaurant</button></a>
    <a href="produktet1?barbershop"><button style="width:140px;" class="btn btn-dark">Barbershop</button></a>
</div>




<div id="sideExtButtons" style="margin-top:10px;">
    <a href="{{route('manageProduktet.index', ['barbershop'])}}" class="mb-4">
        @if(Request::is('produktet1'))
            <div class="d-flex" style="margin-left:-50px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAI01C.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Statistiken</ins></strong></p>
            </div> 
        @else
            <div class="d-flex" style="margin-left:-50px;">
                <div style="width:45px; height:45px;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/SAI01W.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Statistiken</strong></p>
            </div> 
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('barbershops.indexBarbershops', ['barbershop'])}}" class="mb-4">
        @if(Request::is('barbershopBarbershops') || Request::is('barbershopBarbershopsOne'))
            <div class="d-flex" style="margin-left:-50px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/barbershop/icons/barbershops02.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Friseurläden registrieren</ins></strong></p>
            </div>  
        @else
            <div class="d-flex" style="margin-left:-50px;">
                <div style="width:45px; height:45px;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/barbershop/icons/barbershops01.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Friseurläden registrieren</strong></p>
            </div> 
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('barbershops.servicesIndex', ['barbershop'])}}" class="mb-4">
        @if(Request::is('barbershopServicesIndex') || Request::is('barbershopServicesSelBar') || Request::is('barbershopServicesCategory') || 
            Request::is('barbershopServicesType') || Request::is('barbershopServicesExtra') || Request::is('barbershopServicesService'))
            <div class="d-flex" style="margin-left:-50px;"> 
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/barbershop/icons/services02.png" alt="1">
                </div>  
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Friseurläden verwalten</ins></strong></p>
            </div> 
        @else
            <div class="d-flex" style="margin-left:-50px;"> 
                <div style="width:45px; height:45px;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/barbershop/icons/services01.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Friseurläden verwalten</strong></p>
            </div> 
        @endif
    </a>
    <!-- <br> -->
    
    <a href="{{route('barbershopsUser.index', ['barbershop'])}}" class="mb-4">
        @if(Request::is('barUsers')) 
            <div class="d-flex" style="margin-left:-50px;"> 
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/usersC.png" alt="1">
                </div>  
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Benutzer</ins></strong></p>
            </div> 
        @else
            <div class="d-flex" style="margin-left:-50px;"> 
                <div style="width:45px; height:45px;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/usersW.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Benutzer</strong></p>
            </div> 
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('barbershops.bannerSAPage', ['barbershop'])}}" class="mb-4">
        @if(Request::is('barbershopbannerSAPage')) 
            <div class="d-flex" style="margin-left:-50px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/BarSABannerC.png" alt="1">
                </div>  
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>Banner-Kontrollseite</ins></strong></p>
            </div> 
        @else
            <div class="d-flex" style="margin-left:-50px;">
                <div style="width:45px; height:45px;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/BarSABannerW.png" alt="1">
                </div> 
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>Banner-Kontrollseite</strong></p>
            </div> 
        @endif
    </a>
    <!-- <br> -->

    <a href="{{route('restaurantRatings.RatingSA', ['barbershop'])}}" class="mb-4">
        @if(Request::is('barbershopRatingsSA')) 
            <div class="d-flex" style="margin-left:-50px;">
                <div style="width:45px; height:45px; background-color:white; border-radius:50%;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/star-green.png" alt="1">
                </div>  
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong><ins>die Wertung</ins></strong></p>
            </div> 
        @else
            <div class="d-flex" style="margin-left:-50px;">
                <div style="width:45px; height:45px;">
                    <img style="width:100%; height:100%; padding:10px;" src="/storage/icons/star-white.png" alt="1">
                </div>
                <p style="width:80%; color:white; padding-top:8px;" class="pl-2"><strong>die Wertung</strong></p>
            </div> 
        @endif
    </a>
    <br>
  

    <br><br>
   

    
</div>


