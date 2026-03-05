<?php
    if(isset($_GET['Reservierung']) && isset($_GET['Res'])){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['ResRez'] = $_GET['Res'];
        $ress = $_GET['Res'];

        header("Location: ".route('TableRez.index', ['Res' => $ress]));
        exit();

    }else if(Auth::check() && Auth::user()->role == 5){
        header("Location: ".route('dash.index'));
        exit();

    }else if( Auth::check() && Auth::user()->role == 15){
        header("Location: ".route('barAdmin.indexStatistics'));
        exit();

    }else if( Auth::check() && Auth::user()->role == 9 ){
        header("Location: ".route('manageProduktet.index'));
        exit();

    }else if( Auth::check() && Auth::user()->role == 55 ){
        // KAMARIER
        header("Location: ".route('admWoMng.indexAdmMngPageWaiter'));
        exit();

    }else if( Auth::check() && Auth::user()->role == 54 ){
        // KUZHINIER
        header("Location: ".route('cookPnl.cookPanelIndexCook'));
        exit();

    }else if( Auth::check() && Auth::user()->role == 53 ){
        // KONTABILISTI
        header("Location: ".route('admWoMng.AccountantStatistics'));
        exit();

    }else if( Auth::check() && Auth::user()->role == 33 ){
        // MENAGJER TE KONTRATAVE 
        header("Location: ".route('saContracts.index'));
        exit();
    }
?>
<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="author" content="KreativeIdee">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>QRorpa</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>



    <!-- Additional CSS Files -->
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"> -->

    <!-- <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css"> -->

    <!-- <link rel="stylesheet" href="assets/css/templatemo-lava.css"> -->

    <!-- <link rel="stylesheet" href="assets/css/owl-carousel.css">
    <link href="_css/Icomoon/style.css" rel="stylesheet" type="text/css" />
    <link href="_css/responsive-layered-slider.css" rel="stylesheet" type="text/css" /> -->



    <link href="css/FP-font-awesome.css" rel="stylesheet">
    <link href="css/FP-templatemo-lava.css" rel="stylesheet">
    <link href="css/FP-owl-carousel.css" rel="stylesheet">
    <link href="css/FP-bootstrap.min.css" rel="stylesheet">


    <link href="css/FP-style.css" rel="stylesheet">
    <link href="css/FP-responsive-layered-slider.css" rel="stylesheet">

  <!-- swiper library -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


<style>

.wave {
  width: 1000px;
  height: 1250px;
  position: absolute;
  top: 0;
  left:90%;
  margin-left: -500px;
  margin-top: -500px;
  border-radius: 35%;
  background: #02beaf;
  animation: wave 40s infinite linear;
}

@keyframes wave {
  from { transform: rotate(0deg);}
  from { transform: rotate(360deg);}
}
.wave2 {
    width: 1000px;
    height: 694px;
    position: absolute;
    top: 78%;
    left: 0;
    margin-left: -500px;
    margin-top: -620px;
    border-radius: 35%;
    background: #27beaf;
    animation: wave 40s infinite linear;
}

@keyframes wave2 {
  from { transform: rotate(0deg);}
  from { transform: rotate(360deg);}
}
.wave3 {
    width: 1000px;
    height: 1000px;
    position: absolute;
    top: 50%;
    left: 90%;
    margin-left: -500px;
    margin-top: -500px;
    border-radius: 35%;
    background: #e5f5f8;
    animation: wave 40s infinite linear;

}

@keyframes wave3 {
  from { transform: rotate(0deg);}
  from { transform: rotate(360deg);}
}
.slider-bg {
  width: 100%;
  height: 200%;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  overflow: hidden;
}
.slider-bg-first{
  width: 100%;
  height: 100%;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  overflow: hidden;
}
li#register-button a {
    border-bottom:3px solid #02beaf;
}
@media (max-width: 991px){
    li#register-button a {
        background: #02beaf !important;
        color: #fff !important;
    }
    .logo{
      text-align: left !important;
    }
    .header-area .main-nav {
      display: grid;
    }
}

.add-footer-side{
    position: relative;
        background: #27beaf;
    width: 100%;
    height: 300px;
    margin-top: -83px;
}
.sub-footer{
    position: absolute;
    bottom: 25px;
    width: 100%;
    text-align: center !important;
    color: #fff !important;
}
.service-box{
    display: flex;
    margin-bottom: 30px;
}
.features-item{
    width: 100%;
}


.item-1, 
.item-2, 
.item-3,
 .item-4,
 .item-5{
    position: absolute;
  display: block;
    top: 2em;
  
  width: 60%;
  
  font-size: 3em;

    animation-duration: 20s;
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
}

.item-1{
    animation-name: anim-1;
}

.item-2{
    animation-name: anim-2;
}

.item-3{
    animation-name: anim-3;
}
.item-4{
    animation-name: anim-4;
}
.item-5{
    animation-name: anim-5;
}

@keyframes anim-1 {
    0%, 8.3% { left: -100%; opacity: 0; }
  8.3%,20% { left: 50%; opacity: 1; }
  28.33%, 100% { left: 110%; opacity: 0; }
}

@keyframes anim-2 {
    0%, 28.33% { left: -100%; opacity: 0; }
  36.33%, 40% { left: 50%; opacity: 1; }
  46.33%, 100% { left: 110%; opacity: 0; }
}

@keyframes anim-3 {
    0%, 46.33% { left: -100%; opacity: 0; }
  54.33%, 60% { left: 50%; opacity: 1; }
   64.33%, 100% { left: 110%; opacity: 0; }
}
@keyframes anim-4 {
    0%, 64.33% { left: -100%; opacity: 0; }
  72.33%, 80% { left: 50%; opacity: 1; }
   82.33%, 100% { left: 110%; opacity: 0; }
}
@keyframes anim-5 {
    0%, 82.33% { left: -100%; opacity: 0; }
  90.33%, 100% { left: 50%; opacity: 1; }
   100% { left: 110%; opacity: 0; }
}
.slider-fade {
  width: 50%;
  padding-top: 150px;
  float: right;
  text-align: center;
}
.slider-fade h4{
  font-size: 46px;
}
.slides {
  font-size: 300%;
  font-family: 'Source Sans Pro', sans-serif;
  margin: auto;
  height: 0;
  text-align: center;
  color: #191a20;

}
.search-button{
    background-color: #02beaf;
    border:none;
    border-radius: 0em;
    font-size: 0.84em;
}
.search-button:hover{
 background-color: #02beaf;
}
.card{
    border: none;
}
.form-control-lg{
    border-radius: 0em;
    margin-left: 5px;
    font-size: 0.8em;
}
#about{
    margin-top:0px;
}
.header-text p{
    color: #1f1f1f !important;
    font-family: 'Lora', serif;
}
.card-sm{
    margin-top: -110%;
}
.slider-area .header-text .left-text{
    margin-top:20%;
}
.sim-slider-slide{
        margin-top:10%;
}

.custom-checkbox {
  position: relative;
  display: block;
  margin-top: 10px;
  margin-bottom: 10px;
  line-height: 20px;
}

.custom-checkbox span {
  display: block;
  margin-left: 20px;
  padding-left: 7px;
  line-height: 20px;
  text-align: left;
}

.custom-checkbox span::before {
  content: "";
  display: block;
  position: absolute;
  width: 20px;
  height: 20px;
  top: 0;
  left: 0;
  background: #fdfdfd;
  border: 1px solid #a2a3a5;

}

.custom-checkbox span::after {
  display: block;
  position: absolute;
  width: 20px;
  height: 20px;
  top: 0;
  left: 0;
  font-size: 18px;
  color: #0087b7;
  line-height: 20px;
  text-align: center;
}

.custom-checkbox input[type="checkbox"] {
  opacity: 0;
  z-index: -1;
  position: absolute;
}

.custom-checkbox input[type="checkbox"]:checked + span::after {
  font-family: "FontAwesome";
  content: "\f00c";
  background:#27beaf;
  color:#fff;
}
#submit{
    margin-top:30px;
}
@media(max-width: 1690px){
    .card-sm{
        margin-top: -85%;
    }
    .left-text{
        margin-top:-10% !important;
    }
    .sim-slider-slide{
        margin-top:20%;
    }
}
@media(max-width: 1440px){
    .left-text{
        margin-top:-50%;
    }
  
    #about{
        margin-top:100px;
    }
  
}
@media(max-width: 1140px){
    .left-text{
        margin-top:-30% !important;
    }
  
}
@media(max-width: 991px){
    .card-sm{
        margin-top: -90%;
    }
    .left-text{
        margin-top:-60% !important;
    }
  
}
@media(max-width: 810px){
    .slider-fade {
        float: none;
        width: 100%;
    }
    .header-text h1{
        z-index: 3;
    }
    .header-text em{
        color: #191a20 !important;
    }
}
@media(max-width: 767px){
    .card-sm{
        margin-top: -110%;
    }
    .wave2{
        width: 693px;
        height: 694px;
        top: 88%;
    }
    .carousel-item img{
      display: block;
      text-align: -webkit-center;
    }
    .w-100{
      text-align: -webkit-center;
    }
    
  
}
@media(max-width: 560px){
    .card-sm{
        margin-top: -100%;
    }
    .sim-slider{
        min-height: 650px
    }
}
@media(max-width: 480px){
    .card-sm{
        margin-top: -155%;
    }
}

@media(min-width: 768px){
    .owl-carousel .owl-item img{
    width: 70%;
    }


}

</style>
</head>

<body>

<script type="text/javascript">
// var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
// (function(){
// var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
// s1.async=true;
// s1.src='https://embed.tawk.to/5f32635ef87ad20c6d7cd560/default';
// s1.charset='UTF-8';
// s1.setAttribute('crossorigin','*');
// s0.parentNode.insertBefore(s1,s0);
// })();
</script>

    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="/" class="logo">
                            <img src="storage/images/logo_QRorpa.png" alt="" style="width: 140px; height: auto;">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="/" class="menu-item">Home</a></li>
                            <li class="scroll-to-section"><a href="#about" class="menu-item">Eigenschaften</a></li>
                            <li class="scroll-to-section"><a href="#kontakt" class="menu-item">Kontakt</a></li>
                            @if(Auth::check())
                                @if(auth()->user()->role == 9)
                                    <li>
                                        <a onclick="SAPageOpenClick()" class="optionsAnchorPh"
                                            href="{{ route('manageProduktet.index') }}"><i class="fa fa-columns"></i> Restaurantleitung</a>
                                    </li>
                                @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3)
                                    <li>
                                        <a onclick="APageOpenClick()" class="optionsAnchorPh" 
                                            href="{{route('dash.index')}}"><i class="fa fa-columns"></i> Administrationsmenü</a> 
                                    </li>
                                @endif

                                <!-- <li class="scroll-to-section" id="login-button"><a href="{{ route('login') }}"><i class="fa fa-user" aria-hidden="true"></i> Ausloggen </a></li>  -->
                                <li>
                                    <a href="{{ route('logout') }}" 
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out"></i> {{ __('Ausloggen') }}
                                    </a>
                                    <form class="optionsAnchorPh" id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>

                            @else
                                <li id="login-button"><a href="{{ route('login') }}"><i class="fa fa-user" aria-hidden="true"></i> Einloggen</a></li>       
                                <li id="register-button"><a href="{{ route('register') }}"><i class="fa fa-sign-in"></i> Registrieren</a></li>
                            @endif
                            <li class="scroll-to-section"><button id="scan" type="button" class="main-button" data-toggle="modal" data-target="#myModal" ><i class="fa fa-qrcode" aria-hidden="true"></i> QR-Code scannen</button></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->


    <!-- ***** Welcome Area Start ***** -->
    <div class="col-md-12 slider-area">
        <!-- <div class="wrapper" style="position:absolute; z-index:-1;">
            <div class="slider-bg-first">
                <div class="wave"></div>
            </div>

            <div class="sim-slider" data-width="1900" data-height="900" data-animation="100" data-current="true" data-progress="true">
                <div class="sim-slider-inner">
                    <div class="sim-slider-slide">
                    </div>
                </div>  
             </div>
        </div> -->

        <!-- ***** Header Text Start ***** -->
        @yield('content')


        <!-- ***** Header Text End ***** -->
    </div>
    <!-- ***** Welcome Area End ***** -->

    <!-- ***** Features Big Item Start ***** -->
    <section class="section" id="about">
            <div class="row">
                
                <div class="col-lg-8 offset-lg-2">
                    <div class="center-heading">
                        <h2>Alle Funktionen in <em>einem System!</em></h2>
                        <p>Die neuste Technologie für <strong>Gastronomie und Hotellerie {{date('Y')}}</strong></p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 service-box"
                    data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                      <a href="{{route('firstPage.qrCodeScanner')}}" style="display: contents;">
                        <div class="features-item">
                          <div class="features-icon">
                            <h2>01</h2>
                            <img src="storage/FP-images/features-icon-1.png" alt="">
                            <h4>QR-Code-Scanner inbegriffen</h4>
                            <p>Scannen Sie den QR-Code ein, welchen Sie auf dem Flyer vorfinden, der sich direkt auf ihrem Tisch befindet.</p>
                          </div>
                        </div>
                      </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 service-box"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                      <a href="{{route('firstPage.wieBenutztMan')}}" style="display: contents;">
                        <div class="features-item">
                          <div class="features-icon">
                            <h2>02</h2>
                            <img src="storage/FP-images/features-icon-2.png" alt="">
                            <h4>Einfach zu nutzen</h4>
                            <p>Erreichen Sie Ihren erfolgreichen Bestellabschluss mit nur wenigen Klicks auf unserer Website.</p>
                          </div>
                        </div>
                      </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 service-box"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                      <a href="{{route('firstPage.tischeReservieren')}}" style="display: contents;">
                        <div class="features-item">
                          <div class="features-icon">
                            <h2>03</h2>
                            <img src="storage/FP-images/reservations-icon.png" alt="">
                            <h4>Tische reservieren</h4>
                            <p>Reduzieren Sie unnötige Wartezeiten und fördern Sie die Zufriedenheit Ihrer Gäste, indem Sie Tischreservierungen im Voraus oder in Echtzeit ermöglichen.</p>
                           
                          </div>
                       </div>
                      </a>
                </div>
              <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 service-box"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                      <a href="{{route('firstPage.delivery')}}" style="display: contents;">
                        <div class="features-item">
                          <div class="features-icon">
                            <h2>04</h2>
                            <img src="storage/FP-images/delivery-icon.png" alt="">
                            <h4>Delivery</h4>
                            <p>Liefern Sie Bestellungen direkt nach Hause und erfüllen Sie den Wunsch Ihrer Gäste nach bequemer Essenslieferung.</p>
                           
                          </div>
                        </div>
                      </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 service-box"
                    data-scroll-reveal="enter bottom move 30px over 0.6s after 0.4s">
                      <a href="{{route('firstPage.takeaway')}}"  style="display: contents;">
                        <div class="features-item">
                          <div class="features-icon">
                              <h2>05</h2>
                              <img src="storage/FP-images/takeaway-icon.png" alt="">
                              <h4>Takeaway</h4>
                              <p>Bieten Sie Takeaway an, um den Bedürfnissen Ihrer Gäste gerecht zu werden. Viele Gäste bevorzugen es, ihr Essen entweder mit nach Hause zu nehmen oder im Freien zu genießen. Dies bietet Ihnen die Möglichkeit, Ihre Umsätze zu steigern und die Zufriedenheit Ihrer Gäste zu erhöhen.</p>
                             
                          </div>
                        </div>
                      </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 service-box"
                    data-scroll-reveal="enter right move 30px over 0.6s after 0.4s">
                      <a href="{{route('firstPage.kartenzahlung')}}" style="display: contents;">
                        <div class="features-item">
                          <div class="features-icon">
                            <h2>06</h2>
                            <img src="storage/FP-images/features-icon-3.png" alt="">
                            <h4>Kartenzahlung</h4>
                            <p>Profitieren Sie von unseren vielfältigen Zahlungsoptionen.</p>
                           
                          </div>
                        </div>
                      </a>
                </div>
            </div>
    </section>
    <!-- ***** Features Big Item End ***** -->

            <div class="left-image-decor">
                <div class="slider-bg">
                  <div class="wave2" ></div>
                </div>
            </div>

    <!-- ***** Features Big Item Start ***** -->
    <section class="section" id="promotion">
        <div class="container">
            <div class="row">
                <div class="left-image col-lg-5 col-md-12 col-sm-12 mobile-bottom-fix-big"
                    data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                    <img src="storage/FP-images/left-image.png" class="rounded img-fluid d-block mx-auto" alt="App">
                </div>
                <div class="right-text offset-lg-1 col-lg-6 col-md-12 col-sm-12 mobile-bottom-fix" style="z-index: 2">
                     <ul>
                        <li data-scroll-reveal="enter right move 30px over 0.6s after 0.4s">
                            <img src="storage/FP-images/about-icon-01.png" alt="">
                            <div class="text">
                                <h4>Attraktiv</h4>
                                <p>Einfach zu bedienen und mit leicht zugänglichen Funktionen - Ihre Benutzererfahrung steht im Mittelpunkt.</p>
                            </div>
                        </li>
                        <li data-scroll-reveal="enter right move 30px over 0.6s after 0.5s">
                            <img src="storage/FP-images/about-icon-02.png" alt="">
                            <div class="text">
                                <h4>Responsive Design</h4>
                                <p>Optimiert für eine Nutzung auf Desktop-Computern, Laptops, Tablets und Mobilgeräten.</p>
                            </div>
                        </li>
                        <li data-scroll-reveal="enter right move 30px over 0.6s after 0.6s">
                            <img src="storage/FP-images/about-icon-03.png" alt="">
                            <div class="text">
                                <h4>Datensicherheit</h4>
                                <p>Ihre Daten sind bei uns sicher und werden nicht an Dritte weitergegeben.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

   
    <!-- ***** Features Big Item End ***** -->

    <div class="right-image-decor"></div>

    <section class="section" id="testimonials">
        <!-- <div class="slider-bg">
            <div class="wave3"></div>
        </div> -->
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="center-heading">
                        <h2>Blicke von der <em>QRorpa App</em></h2>
                        <p>Mit einem schlichten und ansprechenden Design sowie einer Vielzahl nützlicher Funktionen ausgestattet, erfüllt QRorpa all Ihre Anforderungen für die bequeme Bestellung von Speisen in verschiedenen gastronomischen Bereichen. Ob in Restaurants, Bars oder anderen gastronomischen Einrichtungen - QRorpa wird schnell zu Ihrer Lieblings-App, die Sie nie mehr von Ihrem Smartphone löschen möchten.</p>
                    </div>
                </div>
                <div class="col-lg-10 col-md-12 col-sm-12 mobile-bottom-fix-big"
                    data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                    <div class="owl-carousel owl-theme">
                        <div class="item service-item">                           
                                <img src="storage/FP-images/sc-h-1-min.png" alt="">                            
                        </div>
                        <div class="item service-item" >
                                <img src="storage/FP-images/sc-h-2-min.png" alt="">                         
                        </div>
                        <div class="item service-item ">
                               <img src="storage/FP-images/sc-h-3-min.png" alt="">                         
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-4-min.png" alt="">                         
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-5-min.png" alt="">                           
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-6-min.png" alt="">                          
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-7-min.png" alt="">                          
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-8-min.png" alt="">                          
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-9-min.png" alt="">                          
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-10-min.png" alt="">                          
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-11-min.png" alt="">                          
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-12-min.png" alt="">                          
                        </div>
                        <div class="item service-item">
                                <img src="storage/FP-images/sc-h-13-min.png" alt="">                          
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </section>








    <!-- ***** Testimonials Ends ***** -->
<div class="text-center pt-4 pb-4" style="background-color:rgb(39, 190, 175); height:100%;margin-top:200px; transform: skewY(-5deg);">
                                            <div class="d-flex justify-content-center" style=" transform: skewY(5deg); margin-top: -150px;">
                       

































    <!-- ***** Footer Start ***** -->
    <footer id="kontakt">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <!-- ***** Contact Form Start ***** -->
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="contact-form">
                       
                    
                              
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <fieldset>
                                            <input name="Name" id="nameCF" type="text" placeholder="Name/Vorname*" required
                                                  style="background-color: rgba(250,250,250,0.3);">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <fieldset>
                                            <input name="Email" id="emailCF" type="email" placeholder="Email Adresse*" required
                                                style="background-color: rgba(250,250,250,0.3);">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                               
                                            <input name="Subject" id="subjectCF" type="text" placeholder="Betreff"
                                                style="background-color: rgba(250,250,250,0.3);">
                                     
                                    </div>
                                    <div class="col-lg-12">
                                        <fieldset>
                                            <textarea name="Message" id="messageCF" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Kommentar'" placeholder=" Kommentar" style="background-color: rgba(250,250,250,0.3);"></textarea>
                                        </fieldset>
                                    </div>

                                    <div id="DatenschutzbestimmungenConFormError01" class="col-12 alert alert-danger" style="display:none;">
                                        Bitte akzeptieren Sie zuerst die Datenrichtlinie
                                    </div>
                                    <div id="nameCFError01" class="col-12 alert alert-danger" style="display:none;">
                                        Schreiben Sie Ihren Vor- und Nachnamen!
                                    </div>
                                    <div id="emailCFError01" class="col-12 alert alert-danger" style="display:none;">
                                        Schreiben Sie Ihre E-Mail!
                                    </div>
                                    <div id="subjectCFError01" class="col-12 alert alert-danger" style="display:none;">
                                        Schreiben Sie das Thema!
                                    </div>
                                    <div id="messageCFError01" class="col-12 alert alert-danger" style="display:none;">
                                        Schreiben Sie bitte Ihre Nachricht!
                                    </div>
                                    <div id="CFsuccess01" class="col-12 alert alert-success" style="display:none;">
                                        Danke, dass Sie uns kontaktiert haben
                                    </div>
                                     <div class="col-lg-12" style="text-align: left; margin-bottom: 20px;">
                                            <table>
                                                <tr>
                                                    <td> 
                                                        <label class="custom-checkbox">
                                                          <input type="checkbox" id="DatenschutzbestimmungenConForm">
                                                          <span>Ich habe die <a href="{{route('firstPage.datenschutz')}}">Datenschutzbestimmungen</a> zur Kenntnis genommen*</span>
                                                        </label>
                                                        <!-- <input type="checkbox" id="agb" name="Agb" value="Akzeptiert" required=""></td>
                                                    <td> <label for="agb" style="font-size: 15px;"> Ich habe die <a href="https://qrorpa.ch/datenschutz">Datenschutzbestimmungen</a> zur Kenntnis genommen.</label> -->
                                                    </td>
                                                </tr>
                                            </table>
                                    </div>

                                    <div class="g-recaptcha" data-sitekey="6Lcre88ZAAAAAFSKo8glDfXDtVfVQKVO9v8NSAM3"></div>
                                    <style>
                                        .g-recaptcha > div {
                                            overflow: hidden;
                                            position: relative;
                                            width: 100%;
                                            text-align: center;
                                            top: 9px;
                                        }
                                    </style>
                                  
                                    <div class="col-lg-12 mt-3">
                                       
                                            <button onclick="sendConForQ()" id="submitConForm" name="Submit" class="btn-block main-button">Senden</button>
                                
                                    </div>
                                </div>
                            


                            <script>
                                function sendConForQ(){
                                    if($("#DatenschutzbestimmungenConForm").is(':checked')){
                                        if($('#nameCF').val() == ''){
                                            $('#nameCFError01').show(200).delay(2000).hide(200);
                                        }else{
                                            if($('#emailCF').val() == ''){
                                                $('#emailCFError01').show(200).delay(2000).hide(200);
                                            }else{
                                                if($('#subjectCF').val() == ''){
                                                    $('#subjectCFError01').show(200).delay(2000).hide(200);
                                                }else{
                                                    if($('#messageCF').val() == ''){
                                                        $('#messageCFError01').show(200).delay(2000).hide(200);
                                                    }else{
                                                        $.ajax({
                                                            url: '{{ route("firstPage.SendConFor") }}',
                                                            method: 'post',
                                                            data: {
                                                                name: $('#nameCF').val(),
                                                                email: $('#emailCF').val(),
                                                                subject: $('#subjectCF').val(),
                                                                message: $('#messageCF').val(),
                                                                _token: '{{csrf_token()}}'
                                                            },
                                                            success: () => {
                                                                $('#CFsuccess01').show(200).delay(2000).hide(200);
                                                                $("#nameCF").val(' ');
                                                                $("#emailCF").val(' ');
                                                                $("#subjectCF").val(' ');
                                                                $("#messageCF").val(' ');
                                                            },
                                                            error: (error) => {
                                                                console.log(error);
                                                                alert('bitte aktualisieren und erneut versuchen!');
                                                            }
                                                        });
                                                    }
                                                }
                                            }
                                        }
                                    }else{
                                        // unchecked
                                        $('#DatenschutzbestimmungenConFormError01').show(200).delay(2000).hide(200);
                                    }
                                }


                                function dataAccConForm(){
                                    if($("#DatenschutzbestimmungenConForm").is(':checked'))
                                        $("#submitConForm").prop('disabled', false);  // checked
                                    else
                                        $("#submitConForm").prop('disabled', true);  // unchecked
                                }
                            </script>
                           
                        </div>
                    </div>
                    <!-- ***** Contact Form End ***** -->
                    <div class="right-content col-lg-6 col-md-12 col-sm-12">
                        <h2>Kontaktieren Sie uns!</h2>
                        <p style="font-size: 18px;"><i class="fa fa-mobile fa-2x" aria-hidden="true" style="margin-right: 10px;"></i> <strong style="display: contents;">076 580 65 43</strong><br>
                            Mo bis Fr 9:00 bis 18:00 Uhr
                           </p><br>
                        <p style="font-size: 18px; margin-top:30px;"><i class="fa fa-envelope" aria-hidden="true" style="margin-right: 10px;"></i><strong style="display: contents;">info@qrorpa.ch/</strong><br>
                            Senden Sie uns Ihre Anfrage jederzeit!
                           </p>
                        <ul class="social">
                            <li><a href="https://facebook.com/qrorpa"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://instagram.com/qrorpa"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
             
    </footer>

 </div>
</div>
<div class="add-footer-side">
       <div class="sub-footer">
        <div class="container">
        <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 links">
            <a href="https://qrorpa.ch/storage/Impressum_AGB_Gast_Datenschutz.pdf" download>Impressum</a>  |  <a href="https://qrorpa.ch/storage/Impressum_AGB_Gast_Datenschutz.pdf" download>Datenschutz</a> | <a href="https://qrorpa.ch/storage/Impressum_AGB_Gast_Datenschutz.pdf" download>Geschäftsbedingungen</a>
         </div>
         <div class="col-lg-12 col-md-12 col-sm-12">
            <p style="color:#fff">  Copyright ©<script>document.write(new Date().getFullYear());</script> Alle Rechte vorbehalten | mit <i class="fa fa-heart-o" aria-hidden="true"></i> gemacht von <a href="https://kreativeidee.ch" target="_blank" style="color:#fff;">Kreative Idee</a></p>
         </div>
        </div>
    </div>
</div>
</div>
  <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">QR-Code scannen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <button class="main-button" id="scanbutton">Scan</button>
                              <div class="video">
                                <video muted playsinline id="qr-video" width="100%"></video>
                              </div>
                  </div>
                 
                </div>
              </div>
            </div>

   <!-- jQuery  -->
    <!-- <script src="assets/js/jquery-2.1.0.min.js"></script> -->

    <!-- Bootstrap -->
    <!-- <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script> -->

     <!-- Plugins -->
    <!-- <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/scrollreveal.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/imgfix.min.js"></script>
    <script type="text/javascript" src="_scripts/jquery-ui-1.10.4.min.js"></script>
        <script type="text/javascript" src="_scripts/responsive-layered-slider.js"></script>  -->


    <!-- Global Init -->
    <!-- <script src="assets/js/custom.js"></script>  -->

        <!-- jQuery -->
        <script src="js/FP-jquery-2.1.0.min.js"></script>

        <!-- Bootstrap -->
        <script src="js/FP-popper.js"></script>
        <script src="js/FP-bootstrap.min.js"></script>

        <!-- Plugins -->
        <script src="js/FP-owl-carousel.js"></script>
        <script src="js/FP-scrollreveal.min.js"></script>
        <script src="js/FP-waypoints.min.js"></script>
        <script src="js/FP-jquery.counterup.min.js"></script>
        <script src="js/FP-imgfix.min.js"></script>

        <script type="text/javascript" src="js/FP-jquery-ui-1.10.4.min.js"></script>
            <script type="text/javascript" src="js/FP-responsive-layered-slider.js"></script> 


        <!-- Global Init -->
        <script src="js/FP-custom.js"></script>



        <script>
            
           $("#subscribe-form").submit(function(e) {
             var response = grecaptcha.getResponse();
                  if(response.length == 0) 
                  { 
                    //reCaptcha not verified
                    alert("Bitte überprüfen Sie, ob Sie ein Mensch sind!"); 
                     e.preventDefault();
                  }
                  else{
                    alert('Ihre Nachricht wurde erfolgreich gesendet');
                  }
                   
                });
               
                
         
        </script>
             <script type="module">


             
                import QrScanner from "/js/FP-qr-scanner.min.js";
                function sccc(){

                    const video = document.getElementById('qr-video');
                    const camHasCamera = document.getElementById('cam-has-camera');
                    const camQrResult = document.getElementById('cam-qr-result');
                    const camQrResultTimestamp = document.getElementById('cam-qr-result-timestamp');
                    const fileSelector = document.getElementById('file-selector');
                    const fileQrResult = document.getElementById('file-qr-result');

                    function setResult(label, result) {
                        label.textContent = result;
                        camQrResultTimestamp.textContent = new Date().toString();
                        label.style.color = 'teal';
                        clearTimeout(label.highlightTimeout);
                        label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
                    }

                    // ####### Web Cam Scanning #######

                    QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);

                    const scanner = 
                    new QrScanner(
                            video, result => redirect(result)
                        );
                    scanner.start();
                    function redirect(result){
                        if (result != null) {
                            window.location.href = result;
                            scanner.destroy();
                        }
                    }

}

                var btn = document.getElementById("scan");
     
    // Assigning event listeners to the button
    btn.addEventListener("click", sccc);








            function SAPageOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.SAPageOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function APageOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.APageOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
    </script>
</body>
</html>