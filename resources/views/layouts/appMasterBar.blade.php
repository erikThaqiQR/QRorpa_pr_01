<?php
    use App\Restorant;
    use App\RestaurantWH;
    use App\RestaurantRating;
    use App\resdemoalfa;
    use App\RestaurantCover;

    use App\Barbershop;
    use App\BarbershopBanner;
    use App\BarbershopRating;
    use App\barbershopWorkingH;
    use App\BarbershoServiceRecomendet;
    use App\BarbershopService;

    if(isset($_GET['Bar'])){
        $theBarID = $_GET['Bar'];
    }
    // use Cart;
?>
<input type="hidden" id="theBarIDInput" value="{{$theBarID}}">

<!DOCTYPE html>
<html lang="de" translate="no">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <meta name="csrf-token" content="{{ csrf_token() }}">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link href="{{ asset('css/ratings.css') }}" rel="stylesheet">

    <!-- swiper library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Lazy Load Library  -->
    <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>

  <!-- <link rel="icon" href="http://example.com/favicon.png"> -->



    <title>{{__('layouts.barber')}}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="https://js.pusher.com/6.0/pusher.min.js"></script>

     <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
     @include('fontawesome')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    @yield('extra-css')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/rightModal.css') }}" rel="stylesheet">


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-173569880-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-173569880-1');
    </script>


<style>
    .optionsAnchor{
        color:black;
        text-decoration:none;
        opacity:0.65;
        font-weight: bold;
        font-size:17px;
    }
    .optionsAnchor:hover{
        opacity:0.95;
        text-decoration:none;
        color:black;
        
    }
    .optionsAnchorPh{
        color:black;
        text-decoration:none;
        opacity:0.85;
        font-weight: bold;
        font-size:17px;
        width: 100%;
        display: block;

        padding: 15px 0px 15px 50px;
        background-color: white;
        background-size: cover;
        margin-bottom: 10px;

       
    }
    .optionsAnchorPh:hover{
        opacity:0.100;
        text-decoration:none;
        color:black;
        
    }

    a.disabled {
        
        cursor: not-allowed;
        pointer-events: none;
    }

body { font-size: 16px; }
input, select, textarea { font-size: 100%; }


/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}

/* Style the close button */
.topright {
  float: right;
  cursor: pointer;
  font-size: 28px;
}

.topright:hover {color: red;}

/* Stars Style*/

/****** Style Star Rating Widget *****/

.rating-stars { 
  border: none;
  float: left;
}

.rating-stars > input { display: none; } 
.rating-stars > label:before { 
  margin-top: 5px;
  font-size: 1em;
  font-family: FontAwesome;
  display: inline-block;
  content: "\f005";
}

.rating-stars > .half:before { 
  content: "\f089";
  position: absolute;
}

.rating-stars > label { 
  color: #FFD700; 
 float: right; 
}

/***** CSS Magic to Highlight Stars on Hover *****/


a, a:hover, a:active, a:visited, a:focus{
 text-decoration: none !important;
}



.qrorpaButton{
    background-color: rgb(39,190,175);
    border-radius: 8px;
    border:1px solid rgb(39,190,175);
    color: white;
    font-weight: bold;
}







</style>












































<!--Duhet me shtu edhe pjesen e hashit apo nenshkrimit digjital ne Production-->
<?php 
       if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION["change"] = true;
?>

</head>
<body>
    @if(isset($_GET['Bar']))
        <?php
            $sendBarLogin = $_GET['Bar'];

            // $GLOBALS["foo"]
            $_SESSION["Bar"] = $_GET['Bar'];
            unset($_SESSION['Res']);
            unset($_SESSION['t']);
        ?>
    @endif
















<!-- The phone options Modal -->
<div class="modal fade" id="optionsModal">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" style="border-radius:30px;">

            <!-- Modal body -->
            <div  style="background-color:whitesmoke; width:100%;">
               
                <div class="text-center pt-4 pb-4" style="background-color:rgb(39, 190, 175); height:300px; transform: skewY(-6deg); margin-top:-230px; margin-bottom:-20px;">
                    @if(Auth::check())
                    <a onclick="profileOpenClick()" clas="profileLine" href="{{ route('profile.index') }}">
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
                                {{__('layouts.myAccount')}}
                            </p>
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-between text-center" style="margin-top:40px;">
                    @if(Auth::check())
                        <a class=" btn btn-block {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('logout') }}" 
                            style="border:2px solid lightgray; color:black; width:50%; margin-left:25%;"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('layouts.logout') }}
                        </a>
                        <form class="optionsAnchorPh" id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a onclick="countEinloggenClick()" class=" btn {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:2px solid lightgray; color:black; width:48%;"
                         href="{{ route('login') }}">{{__('layouts.login')}}</a>
                                   
                        <a onclick="countRegisterClick()" class="btn  {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:4px solid rgb(39, 190, 175); color:black; width:48%;"
                         href="{{ route('register') }}">{{__('layouts.toRegister')}}</a>
                      
                    @endif
                </div>

                <div style="background-color:whitesmoke; width:100%;" class="mt-2">
                    @if(isset($_GET["Bar"]))
                        @if(Auth::check())<!--Restorant(Yes)  User(Yes) -->
                            @if(auth()->user()->role == 9)
                                <a onclick="SAPageOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('manageProduktet.index') }}"><i class="fas fa-columns"></i> {{__('layouts.restaurantManagement')}}</a>   
                            @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3)
                                <a onclick="APageOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('dash.index')}}"><i class="fas fa-columns"></i> {{__('layouts.adminMenu')}}</a>       
                            @endif
                            @if(Auth::user()->role != 9)  
                                <a onclick="CartOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('cart')}}"><i class="fas fa-shopping-cart"></i> {{__('layouts.cart')}}
                                    @if(count(Cart::content()) > 0)
                                        <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;">
                                        {{ count(Cart::content()) }}</span>
                                    @endif
                                </a>
                            @endif
                        @else<!--Restorant(Yes)  User(No) -->
                            <a onclick="CartOpenClick()" class="optionsAnchorPh  {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('cart') }}"><i class="fas fa-shopping-cart"></i> {{__('layouts.cart')}}
                                @if(  count(Cart::content()) > 0)
                                    <sup>  <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;"> {{ count(Cart::content()) }}</span></sup>
                                @endif
                            </a>   
                        @endif
                    @else
                        @if(Auth::check())<!--Restorant(No)  User(Yes) -->
                            @if(auth()->user()->role == 9)
                                <a onclick="SAPageOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('manageProduktet.index') }}"><i class="fas fa-columns"></i> {{__('layouts.restaurantManagement')}}</a>
                            @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3 || auth()->user()->role == 15)
                                <a onclick="APageOpenClick()" class="optionsAnchorPh  {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('dash.index')}}"><i class="fas fa-columns"></i> {{__('layouts.adminMenu')}}</a>
                            @elseif(auth()->user()->role == 1)
                            @endif
                        @endif
                    @endif
                </div>
                <div class="text-center mt-3" >
                    <div class="text-center">
                        <button type="button" class="close text-center pb-3 pr-4" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







































<nav class="navbar navbar-expand-sm b-white"  width="100%" id="navDesktop" style="display:none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-left">
                <a class="navbar-brand" href="#"><img style="width:120px" src="/storage/images/logo_QRorpa.png" alt=""></a>
            </div>
          
        </div>
        
            @if(isset($_SESSION["Bar"]))
                 <div class="row">

                    @if (Route::has('login'))
                        @auth
                            <div class="col-12 text-right">
                                @if(auth()->user()->role != 9)
                                
                                    <a onclick="CartOpenClick()" class="optionsAnchor pl-5 {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('cart') }}" >{{__('layouts.cart')}}
                                        @if(  count(Cart::content()) > 0)
                                            <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;">
                                            {{ count(Cart::content()) }}</span>
                                        @endif
                                    </a>
                                
                                @endif
                                @if(auth()->user()->role == 9)
                                    <a onclick="SAPageOpenClick()" class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{ route('manageProduktet.index') }}">{{__('layouts.restaurantManagement')}}</a>
                                @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3 || auth()->user()->role == 15)
                                    <a onclick="APageOpenClick()" class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{route('dash.index')}}">{{__('layouts.adminMenu')}}</a>
                                @endif

                                <a onclick="ProfileOpenClick()" class="optionsAnchor pl-5" href="{{ route('profile.index') }}">
                                    @if(Auth::user()->profilePic != 'empty')
                                        <img style="width:35px; border-radius:50%;"  src="/storage/profilePics/{{Auth::user()->profilePic}}" alt="img">
                                    @else
                                        <img style="width:35px;"  src="/storage/images/ProfileIcon.png" alt="img">
                                    @endif
                                </a>
                               
                                <a class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    {{ __('layouts.logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>             
                            </div>
                        @else
                            <div class="col-12 text-right" >
                       
                                <a onclick="CartOpenClick()" class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{ route('cart') }}">{{__('layouts.cart')}} 
                                    @if(  count(Cart::content()) > 0)
                                        <sup>  <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;"> {{count(Cart::content()) }}</span></sup>
                                    @endif
                                </a>
                                <a onclick="countEinloggenClick()" class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}"   href="{{ route('login') }}">{{__('layouts.login')}}</a>
                                <a onclick="countRegisterClick()" class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}"  href="{{ route('register') }}">{{__('layouts.toRegister')}}</a>
                              
                            </div>
                        @endauth
                    @endif
                </div>
            @elseif(Auth::check())
                @if(auth()->user()->role == 9)
                    <div class="row">
                        <div class="col-12">
                            <a onclick="SAPageOpenClick()" class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{ route('manageProduktet.index') }}">{{__('layouts.restaurantManagement')}}</a>
                          
                            <a onclick="ProfileOpenClick()" class="optionsAnchor pl-5" href="{{ route('profile.index') }}">
                                   @if(Auth::user()->profilePic != 'empty')
                                        <img style="width:35px; border-radius:50%;"  src="/storage/profilePics/{{Auth::user()->profilePic}}" alt="img">
                                    @else
                                        <img style="width:35px;"  src="/storage/images/ProfileIcon.png" alt="img">
                                    @endif
                            </a>
                       
                            <a class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('layouts.logout') }}
                            </a>
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3 || auth()->user()->role == 15)
                    <div class="row">
                        <div class="col-12">
                            <a onclick="APageOpenClick()" class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{route('dash.index')}}">{{__('layouts.adminMenu')}}</a>
                            <a onclick="ProfileOpenClick()" class="optionsAnchor pl-5" href="{{ route('profile.index') }}">
                                @if(Auth::user()->profilePic != 'empty')
                                        <img style="width:35px; border-radius:50%;"  src="/storage/profilePics/{{Auth::user()->profilePic}}" alt="img">
                                    @else
                                        <img style="width:35px;"  src="/storage/images/ProfileIcon.png" alt="img">
                                    @endif
                            </a>
                            <a class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('layouts.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @elseif(auth()->user()->role == 1)
                    <div class="row">
                        <div class="col-12">
                           
                            <a onclick="ProfileOpenClick()" class="optionsAnchor pl-5" href="{{ route('profile.index') }}">
                                    @if(Auth::user()->profilePic != 'empty')
                                        <img style="width:35px; border-radius:50%;"  src="/storage/profilePics/{{Auth::user()->profilePic}}" alt="img">
                                    @else
                                        <img style="width:35px;"  src="/storage/images/ProfileIcon.png" alt="img">
                                    @endif
                            </a>
                            <a class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('layouts.logout') }}
                            </a>
                            <form class="optionsAnchor pl-5" id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @endif
                
            @else
                <div class="row">
                   
                    <a onclick="countEinloggenClick()" class="optionsAnchor pl-5 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{ route('login') }}">{{__('layouts.login')}}</a>
                    <a onclick="countRegisterClick()" class="optionsAnchor pl-5 pr-3 {{(isset($_GET['demo'])?'disabled' : '')}}" href="{{ route('register') }}">{{__('layouts.toRegister')}}</a>
                 
                </div>
            @endif
            <!-- end Options -->
        
    </div>
  
</nav>
   


<nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone" style="display:none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-left">
                <a class="navbar-brand" href="#"><img style="width:120px" src="/storage/images/logo_QRorpa.png" alt=""></a>
            </div>
        </div>

        
        <div class="row">
            <div class="col-12" >
               <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img src="storage/icons/listDown.PNG"/></button> 
            </div>
        </div>
    </div>
</nav>



<script>
    if ((screen.width>580)) {
                    // if screen size is 1025px wide or larger
                    $(".cartBottom").hide(); 
                    $("#menuList").css('margin-left','45%')

                    $('#navPhone').hide();
                    $('#navDesktop').show();

                 
                }
                else if ((screen.width<=580))  {
                    // if screen size width is less than 1024px
                    $(".cartBottom").show();
                    $("#menuList").css('margin-left',':2%');

                    $('#navPhone').show();
                    $('#navDesktop').hide();
                }
</script>
















<style>





.search {
  width: 96%;
  margin-left:2%;
  border-top-left-radius:20px;
  position: relative;
  display: flex;
}

.searchTerm {
  width: 100%;
  border: 1px solid rgb(245, 248, 250);
  border-right: none;
 
  padding: 5px;
  height: 35px;
  border-radius: 20px 0 0 20px;
  outline: none;
  color: #212529;
}



.searchButton {
  width: 40px;
  height: 35px;
  border: 1px solid rgb(245, 248, 250);
  background: rgb(39,190,175);
  text-align: center;
  color: #fff;
  border-radius: 0 20px 20px 0;
  cursor: pointer;
  font-size: 20px;
}

/*Resize the wrap to see the search bar change!*/
.wrap{
  width: 100%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.info-table tr{
    border-bottom: 1px solid #dedede;
}

/*Restaurant Cover Image Style*/
/*.col-lg-12.col-sm-12.text-center.mt-2 {
    background-image: url(storage/images/restaurant-cover-image.png);
    padding: 15px;
    background-size: cover;
    background-position: center;
    margin-top: 0rem !important;
}*/
/*stars style*/


.color-white{
    line-height: 0.2;
}
.color-white span{
    font-size: 14px;
}
#bewer{
    color: orange;
    font-size: 13px;
}
.send-button{
    background-color:rgb(39,190,175);
    color:white;
    border-radius:30px;
    border-color: none !important;
    height:auto;
    width:150px;
}


.star-ratings-css {
  unicode-bidi: bidi-override;
  color: #c5c5c5;
  font-size: 25px;
  margin: 0 auto;
  position: relative;
  /*text-shadow: 0 1px 0 #a2a2a2;*/
  display: inline-table;
} 
.star-ratings-css::before { 
  content: '★★★★★';
  opacity: .3;
  display: flex;
}

[title=".00"]::after {
  width: 0%;
}
[title=".50"]::after {
  width: 10%;
}
[title=".100"]::after {
  width: 20%;
}
[title=".150"]::after {
  width: 30%;
}
[title=".200"]::after {
  width: 40%;
}
[title=".250"]::after {
  width: 50%;
}
[title=".300"]::after {
  width: 60%;
}
[title=".350"]::after {
  width: 70%;
}
[title=".400"]::after {
  width: 80%;
}
[title=".450"]::after {
  width: 90%;
}
[title=".500"]::after {
  width: 100%;
}
.star-ratings-css::after {
  color: #ffb101;
  content: '★★★★★';
  /*text-shadow: 0 1px 0 #ab5414;*/
  position: absolute;
  z-index: 1;
  display: block;
  left: 0;
  top:0;
  width: attr(rating);
  overflow: hidden;
}
.profilepic-area{
    width: 30%;
    margin-left: 35%;
    text-align: center;
    margin-top:-50px;
    z-index: 1;
}



@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,600,700);


.mySlides {
    display: none;
}
img {
    vertical-align: middle;
}

.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
  margin-top:10px;
}
.dot {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 10s ease;
}

.active {
  background-color: #717171;
  border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px
}
.fadeSlide {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 10s;
  animation-name: fade;
  animation-duration: 10s;
}

@-webkit-keyframes fade {
  from {opacity: 1} 
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: 1} 
  to {opacity: 1}
}
.mySlides img{
    width: 100%;
    border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px
}

.link-area{
    font-weight: 400;
    color: #666;
    line-height: 1.71;
    letter-spacing: normal;
    background-color: #f8f5f2;
    padding: 16px 24px;
    font-size: 14px;
    margin-bottom: 20px;
}
.text-info-area{
    font-weight: 400;
    color: #666;
    line-height: 1.71;
    letter-spacing: normal;
    background-color: #f8f5f2;
    padding: 16px 24px;
    font-size: 14px;
    margin-bottom: 20px;
}

</style>




<!-- Extra stuf / on or off depending on the variables -->












































































<style>
    .mySlides {
    display: none;
}
img {
    vertical-align: middle;
}

.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
  margin-top:10px;
}
.dot {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 10s ease;
}

.active {
  background-color: #717171;
  border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px
}
.fadeSlide {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 10s;
  animation-name: fade;
  animation-duration: 10s;
}

@-webkit-keyframes fade {
  from {opacity: 1} 
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: 1} 
  to {opacity: 1}
}
.mySlides img{
    width: 100%;
    border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px
}

.link-area{
    font-weight: 400;
    color: #666;
    line-height: 1.71;
    letter-spacing: normal;
    background-color: #f8f5f2;
    padding: 16px 24px;
    font-size: 14px;
    margin-bottom: 20px;
}
.text-info-area{
    font-weight: 400;
    color: #666;
    line-height: 1.71;
    letter-spacing: normal;
    background-color: #f8f5f2;
    padding: 16px 24px;
    font-size: 14px;
    margin-bottom: 20px;
}

</style>
</style>









<!-- Start Barbershop  -->
    @if(isset($_GET['Bar']))

    <?php 
        $theB = $_GET['Bar']; 
        $BarRW = barbershopWorkingH::where('toBar',$theB)->first();
    ?>


    <div class="container" style="margin-top:-10px;">
        <div class="row" tyle="z-index:5;">
          
            <div class="slideshow-container">
                    @foreach(BarbershopBanner::where('Bar_id',$theB)->orWhere('Bar_id',0)->get() as $thisResCover)
                    <div class="mySlides fadeSlide">
                        <a style="cursor: pointer;" data-toggle="modal" data-target="#coverInfo{{$thisResCover->id}}">
                        <img style="width:100%; height:180px; object-fit: cover;" src="storage/BarBackgroundPic/{{$thisResCover->B_pic}}" alt="noimg"></a>
                    </div>
                    @endforeach                    
                    </div>
                    <br>
                    <div style="text-align:center">                
                    @foreach(BarbershopBanner::where('Bar_id',$theB)->orWhere('Bar_id',0)->get() as $thisResCover)
                    <span class="dot" style="display: none;"></span> 
                @endforeach
            </div>
            <div class="profilepic-area">
                @if(Barbershop::find($theB) != NULL &&  Barbershop::find($theB)->bPic != 'none' )
                <img style="width:110px; height:110px; border-radius:50%; background-color:white; border: 1px solid #d7d7d7;"
                    src="storage/barbershopLogo/{{Barbershop::find($theB)->bPic}}" alt="">
                @else
                    <img style="width:110px; height:110px; border-radius:50%; background-color:white; border: 1px solid #d7d7d7;" src="storage/images/showcase03.png" alt="">
                @endif
            </div>
        </div>

        <!-- Student  -->

        <?php   $serWithStPrice = BarbershopService::where([['toBar',$_GET['Bar']],['qmimiSt','!=','0']])->get()->count();
                $recSerWithStPrice = BarbershoServiceRecomendet::where([['toBar', $theBarID],['newPriceSt','!=','0']])->get()->count();
        ?>
     
            @if( $serWithStPrice > 0 ||  $recSerWithStPrice > 0)
                @if(!isset($_GET['student']))
                    <div class="d-flex mb-3" style="margin-top:-45px; z-index:10;">
                        <p style="width:70%;"></p>
                        <a href="/?Bar={{$_GET['Bar']}}&student" class="btn btn-block qrorpaButton " style="width:30%;" onclick="emptyCart()">{{__('layouts.student')}}</a>
                    </div>
                @else
                    <div class="d-flex mb-3" style="margin-top:-45px;">
                        <p style="width:70%;"></p>
                        <a href="/?Bar={{$_GET['Bar']}}" class="btn btn-block btn-danger " style="width:30%;" onclick="emptyCart()">{{__('layouts.notStudent')}}</a>
                    </div>
                @endif
            @endif

        <script>
            function emptyCart(){
                $.ajax({
                    url: '{{ route("cart.barDestroyTheCart") }}',
                    method: 'post',
                    data: { _token: '{{csrf_token()}}' },
                    success: () => {}
                });
            }
        </script>



        <div class="row">
            <div class="col-12">
                <p style="font-size:19px; margin-bottom: 0rem; margin-bottom:-15px; color:rgb(72, 81, 87);"><strong>{{Barbershop::find($theB)->emri}}</strong> 
                    @if($BarRW != NULL)
                        <a style="cursor: pointer;" data-toggle="modal" data-target="#barInfo{{$theB}}">
                        <i class="fa fa-info-circle"  aria-hidden="true" style="float: right;"></i></a>
                    @endif
                </p>
                <style>
                    .yjetMenuRat{
                        font-size:24px;
                    }
                </style>
                <?php
                    $thisBarRatings= BarbershopRating::where([['bar_id', '=', $theB], ['verified', '=', '1']])->orderByDesc('updated_at')->get();
                    // $thisRestaurantRatingsSum = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->sum('stars');
                    $thisBarRatingsAVG = BarbershopRating::where([['bar_id', '=', $theB], ['verified', '=', '1']])->avg('stars');
                ?>
                   <a style="cursor: pointer; " data-toggle="modal" data-target="#ratingModal{{$theB}}">
                        @if($thisBarRatings != null)
                            @if(number_format($thisBarRatingsAVG,1) < 0.5)
                                <div class="star-ratings-css yjetMenuRat" title=".00"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) <= 0.5)
                                <div class="star-ratings-css yjetMenuRat" title=".50"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) == 1)
                                <div class="star-ratings-css yjetMenuRat" title=".100"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) <= 1.5)
                                <div class="star-ratings-css yjetMenuRat" title=".150"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) < 2)
                                <div class="star-ratings-css yjetMenuRat" title=".200"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) == 2)
                                <div class="star-ratings-css yjetMenuRat" title=".200"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) <= 2.5 )
                                <div class="star-ratings-css yjetMenuRat" title=".250"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) < 3 )
                                <div class="star-ratings-css yjetMenuRat" title=".300"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) == 3)
                                <div class="star-ratings-css yjetMenuRat" title=".300"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) <= 3.5)
                                <div class="star-ratings-css yjetMenuRat" title=".350"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) < 4)
                                <div class="star-ratings-css yjetMenuRat" title=".400"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) == 4)
                                <div class="star-ratings-css yjetMenuRat" title=".400"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) <= 4.5)
                                <div class="star-ratings-css yjetMenuRat" title=".450"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) < 5)
                                <div class="star-ratings-css yjetMenuRat" title=".500"></div>
                            @elseif(number_format($thisBarRatingsAVG,1) == 5)
                                <div class="star-ratings-css yjetMenuRat" title=".500"></div>
                            @endif
                            <span id="bewer">( {{count($thisBarRatings)}} Bewertungen)</span>
                        @else
                            <div class="star-ratings-css" title=".00"></div>
                            <span id="bewer">( 0 Bewertungen)</span>
                        @endif
                    </a>
              
            </div>
        </div>
        <br>



                        <!-- Cover Info Modal -->
                        @foreach(BarbershopBanner::where('Bar_id',$theB)->orWhere('Bar_id',0)->get() as $barBanner)
                            <div id="coverInfo{{$barBanner->id}}" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header" style="position: sticky; z-index: 10; top:0">
                                        <h4 class="modal-title"><i class="fa fa-info-circle" aria-hidden="true"></i> {{__('layouts.aboutCover')}}</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center">
                                            <a target="_blank" href="{{ url($barBanner->B_link) }}"><i class="fa fa-external-link" aria-hidden="true"></i> {{$barBanner->B_link}}</a>
                                        </div>
                                        <div class="text-info-area text-center">
                                            {{$barBanner->B_text}}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-block btn-danger" data-dismiss="modal">{{__('layouts.close')}}</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                       @endforeach

        
    </div>

    

        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-0">

                </div>
                <div class="col-lg-6 col-sm-12 text-center">
        
                        <!-- <input class="form-control ml-2" id="searchBar" type="text" placeholder="Search..." aria-label="Search"
                        style="border:none; border-bottom:1px solid gray;"> -->
                        <div class="wrap text-center">
                            <div class="search">
                                <input id="input_search_bar" type="text" class="searchTerm" onkeyup="searchBarServices(this.value)" placeholder="{{__('layouts.whatAreYouLookingFor')}}">
                                <button type="submit" class="searchButton">
                                    <i class="fa fa-search" style="padding: 0px; margin: 0px;"></i>
                                </button>
                            </div>
                        </div>
                </div>
                <div class="col-lg-3 col-sm-0">

                </div>
            </div>
        </div>
        <br>


        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <script>
            function searchBarServices(searchWord){
                if(searchWord == ''){
                    $('#allBarbershopSer01').show('300');
                    $('#recSerListAllSection').show('300');
                    $('#allBarbershopSer02').hide('300');
                }else{
                    $('#allBarbershopSer01').hide('1');
                    $('#recSerListAllSection').hide('1');
                    $('#allBarbershopSer02').show('1');
                   
                    $.ajax({
                        method: 'post',
                        url: '{{route("barService.searchBarServices")}}',
                        dataType: 'json',
                        data: {
                            searchW: searchWord,
                            theBar: $('#theBarIDInput').val(),
                            '_token': '{{csrf_token()}}'
                        },
                        success: function(res){
                            $('#allBarbershopSer02').html('');
                            var listings = "";

                            $('#allBarbershopSer02').append('<h4 class="text-center">'+Object.keys(res).length+' '+$('#servicesFound').val()+' </h4>');

                            $.each(res, function(index, value){

                                listings =  '<div class="row p-2 serviceList'+value.id+'" data-toggle="modal" data-target="#ServiceModal'+value.id+'" data-backdrop="static" data-keyboard="false">'+
                                                '<div class="container-fluid">'+
                                                    '<div class="row">'+
                                                        '<div class="col-lg-3 col-sm-0 col-md-0"></div>'+
                                                        '<div class="col-lg-6 col-sm-12 col-md-12 product-section">'+
                                                            '<div class="row">'+
                                                                '<div class="col-10">'+
                                                                    '<h4 class="pull-right prod-name prodsFont" style="font-weight:bold; font-size: 1.20rem "> '+value.emri+' </h4>'+
                                                                    '<p style=" margin-top:-10px; font-size:13px;"> '+value.pershkrimi+' </p>'+
                                                                    '<h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">CHF</span> '+parseFloat(value.qmimi).toFixed(2)+' </h5>'+
                                                                '</div>'+
                                                                '<div class="col-2 add-plus-section">'+
                                                                    '<button class="btn mt-2 noBorder" type="button" ><i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i></button>'+
                                                                '</div>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '<div class="col-lg-3 col-sm-0 col-md-0"></div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>';

                                $('#allBarbershopSer02').append(listings); 
                            });   
                        }
                    });
                }
            }
        </script>














            <!-- Recomendet Services  -->
    <section class="rec" id="recSerListAllSection">
    <div class="container p-0">
            <div class="col-lg-6 col-sm-12 teksti">
                <p class="color-qrorpa pb-2" style="margin-bottom:-10px; font-size:20px; color:#6b6b6d;"><strong class="recommended-title">{{__('layouts.recommendedServices')}}</strong></p>
            </div>

            <div class="swiper-container col-lg-12 " style="border-top-left-radius:50px; border-bottom-left-radius:50px; overflow: hidden; ">
                <div class="swiper-wrapper" id="recProdList" style="height:155px;">

                    @foreach(BarbershoServiceRecomendet::where('toBar', $theBarID)->get()->sortBy("position") as $ReSer)
                        @if(isset($_GET['student']) && $ReSer->newPriceSt != 0)
                            <div class="swiper-slide recProElement " style="height:150px;" data-toggle="modal" data-target="#ServiceModal{{$ReSer->serviceid}}"
                                data-backdrop="static" data-keyboard="false" onclick="prepareTheRecService2('{{$ReSer->serviceid}}')">
                        @else
                            <div class="swiper-slide recProElement " style="height:150px;" data-toggle="modal" data-target="#ServiceModal{{$ReSer->serviceid}}"
                                data-backdrop="static" data-keyboard="false" onclick="prepareTheRecService('{{$ReSer->serviceid}}')">
                        @endif
                            <img lazy="loading" style="width:120px; height:120px; border-radius:50%;" src="storage/recomendetServices/{{ $ReSer->servicePic }}" alt="image">

                            @if(strlen(BarbershopService::find($ReSer->serviceid)->emri) > 15)
                                <p class="color-text" style="width:100%; padding-top:2px; padding-bottom:2px; font-size:9px;"><strong>{{BarbershopService::find($ReSer->serviceid)->emri}}</strong></p>
                            @else
                                <p class="color-text" style="width:100%; font-size:13px;"><strong>{{BarbershopService::find($ReSer->serviceid)->emri}}</strong></p>
                            @endif
                            <p style="font-size:14px;"><span style="opacity:0.6; ">{{__('layouts.currencyShow')}} </span>
                                @if(isset($_GET['student']) && $ReSer->newPriceSt != 0)
                                    {{sprintf('%01.2f', $ReSer->newPriceSt)}} 
                                @else
                                    {{sprintf('%01.2f', $ReSer->newPrice)}}
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <script>
            var swiper = new Swiper('.swiper-container', {
                slidesPerView: 3,
                // spaceBetween: 10,
                stagePadding: 10,
                breakpoints: {
                    // when window width is >= 320px
                    320: {
                        slidesPerView: 3,
                        spaceBetween: 5
                    },
                    // when window width is >= 480px
                    480: {
                        slidesPerView: 3,
                        spaceBetween: 5
                    },
                    // when window width is >= 640px
                    640: {
                        slidesPerView: 4,
                        spaceBetween: 5
                    }
                }
            });

            function prepareTheRecService(serID){
                $('#TotPriceBar'+serID).val('Loading...');
                $.ajax({
                    url: '{{ route("barService.recomenderGetSerPrice2") }}',
                    method: 'post',
                    data: {id: serID, std: 0, _token: '{{csrf_token()}}' },
                    success: (res) => { 
                        $('#TotPriceBar'+serID).val(res.qmimi);
                        $('#QmimiBazeBar'+serID).val(res.qmimi);
                        $('#QmimiSendBar'+serID).val(res.qmimi);
                    },
                    error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val());}
                }); 
            }

            function prepareTheRecService2(serID){
                $('#TotPriceBar'+serID).val('Loading...');
                $.ajax({
                    url: '{{ route("barService.recomenderGetSerPrice2") }}',
                    method: 'post',
                    data: {id: serID, std: 1, _token: '{{csrf_token()}}' },
                    success: (res) => { 
                        $('#TotPriceBar'+serID).val(res.qmimi);
                        $('#QmimiBazeBar'+serID).val(res.qmimi);
                        $('#QmimiSendBar'+serID).val(res.qmimi);
                    },
                    error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val());}
                }); 
            }
        </script>
    </section>














































































    <script>
            var slideIndex = 0;
            showSlides();
            function showSlides() {
              var i;
              var slides = document.getElementsByClassName("mySlides");
              var dots = document.getElementsByClassName("dot");
              for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
              }
              slideIndex++;
              if (slideIndex > slides.length) {slideIndex = 1}    
              for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
              }
              slides[slideIndex-1].style.display = "block";  
              dots[slideIndex-1].className += " active";
              setTimeout(showSlides, 5000); // Change image every 5 seconds
            }
            
    </script>







                      <!-- Ratings Modal -->
                      <div id="ratingModal{{$theB}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                <div class="modal-header" style="position: sticky; z-index: 10; top:0">
                                    <h4 class="modal-title"><i style="color:rgb(39, 190, 175);" class="fa fa-star" aria-hidden="true"></i> <strong>{{__('layouts.reviews')}}</strong></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                

                                        <div id="bewertungen">
                                            {{-- Ratings Area --}}
                                                <div class="ratings-area">
                                        
                                                <form id="ajaxformBARrating" >
                                                    {{csrf_field()}}
                                                    <div class="alert alert-success" id="successRating" style="display:none;">
                                                        {{__('layouts.thanksFeedback')}}
                                                    </div>
                                                    <div class="form-group text-center" style="display: table-row;">
                                                        <div class="rating-stars text-center">
                                                        
                                                            <fieldset class="rating text-center" >
                                                                <strong>{{__('layouts.overallRating')}}</strong><br>
                                                                <input type="radio" id="star5" name="stars" value="5" required="" /><label class = "full stars" for="star5" title="{{__('layouts.awesome5Stars')}}"></label>   
                                                                <input type="radio" id="star4" name="stars" value="4" /><label class = "full stars" for="star4" title="{{__('layouts.prettyGood4Stars')}}"></label>
                                                                <input type="radio" id="star3" name="stars" value="3" /><label class = "full stars" for="star3" title="{{__('layouts.meh3Stars')}}"></label>
                                                                <input type="radio" id="star2" name="stars" value="2" /><label class = "full stars" for="star2" title="{{__('layouts.kindaBad2Stars')}}"></label>
                                                                <input type="radio" id="star1" name="stars" value="1" /><label class = "full stars" for="star1" title="{{__('layouts.sucksBigTime1Star')}}"></label>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" placeholder="{{__('layouts.nickname')}}*" name="nickname" class="form-control" placeholder="" required="" id="nickname">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" placeholder="{{__('layouts.title')}}" name="title" class="form-control" placeholder="" id="titel">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="email" placeholder="{{__('layouts.email')}}" name="email" class="form-control" placeholder="" id="email">
                                                    </div>

                                                    @if(Auth::check())
                                                        <input type="hidden" name="klientiRat" value="{{Auth::user()->id}}">
                                                    @else
                                                        <input type="hidden" name="klientiRat" value="0">
                                                    @endif


                                                    <div class="form-group">
                                                        <label>{{__('layouts.review')}}</label>
                                                        <textarea class="form-control"  name="comment" rows="4" cols="50"></textarea>
                                                        <input type="hidden" name="bar_id" class="form-control" value="{{$theB}}" id="bar_id">
                                                    </div>
                                                
                                                    <div class="form-group d-flex justify-content-between">
                                                        <button class="btn btn-success send-button" id="submit" style="margin-top: 20px;margin-bottom: 20px; width:100%;">{{__('layouts.Send')}}</button>
                                                    </div>
                                                </form>
                                                    
                                                    <table class="table">
                                                        <tbody>
                                                            @foreach($thisBarRatings as $thisres)
                                                                <tr>
                                                                    <td style="font-weight:bold;">{{$thisres->nickname}} &nbsp&nbsp&nbsp
                                                                    @if($thisres->stars == 5)
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                    @elseif($thisres->stars == 4)
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star "></span>
                                                                    @elseif($thisres->stars == 3)
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star "></span>
                                                                        <span class="fa fa-star "></span>
                                                                    @elseif($thisres->stars == 2)
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star "></span>
                                                                        <span class="fa fa-star "></span>
                                                                        <span class="fa fa-star "></span>
                                                                    @elseif($thisres->stars == 1)
                                                                        <span class="fa fa-star checked"></span>
                                                                        <span class="fa fa-star "></span>
                                                                        <span class="fa fa-star "></span>
                                                                        <span class="fa fa-star "></span>
                                                                        <span class="fa fa-star "></span>
                                                                    @endif
                                                                    <br>
                                                                    <p style="padding:3px; font-size:13px; font-weight:normal;">{{$thisres->comment}}</p>
                                                                </td>

                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    

                                                </div>
                                        {{-- End Ratings Area --}}
                                        </div>                                
                                    </div>
                            
                                </div>

                            </div>
                        </div>





                        <!-- "#barInfo{{$theB}}" -->
                        @if($BarRW != null)
                            <!-- Info Modal -->
                                <div id="barInfo{{$theB}}" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                    <div class="modal-header" style="position: sticky; z-index: 10; top:0">
                                        <h4 class="modal-title">{{__('layouts.aboutRestaurant')}}</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">


                                    
                                        @if(Barbershop::find($theB)->barDesc != 'none')
                                            <p> <strong>{{__('layouts.description')}} :</strong>{{Barbershop::find($theB)->barDesc}}</p> 
                                        @endif
                                        <h5> <i class="fa fa-clock-o" aria-hidden="true"></i> <strong>{{__('layouts.openTime')}}:</strong></h5>

                                        
                                            <table class="table info-table" style="width: 100%; background-color: #fafafa;">
                                                <tbody>
                                                    <tr>
                                                        <td valign="top">{{__('layouts.monday')}} </td>
                                                        <td valign="center" align="right" style="font-size:13px;">
                                                            @if($BarRW->D1Starts1 != "none" && $BarRW->D1End1 != "none" && $BarRW->D1Starts2 != "none" && $BarRW->D1End2 != "none")
                                                                <strong>{{$BarRW->D1Starts1}} - {{$BarRW->D1End1}}</strong> {{__('layouts.and')}} <strong>{{$BarRW->D1Starts2}} - {{$BarRW->D1End2}}</strong>
                                                            @elseif($BarRW->D1Starts1 != "none" && $BarRW->D1End1 != "none")
                                                                <strong>{{$BarRW->D1Starts1}} - {{$BarRW->D1End1}}</strong>
                                                            @elseif($BarRW->D1Starts2 != "none" && $BarRW->D1End2 != "none")
                                                                <strong>{{$BarRW->D1Starts2}} - {{$BarRW->D1End2}}</strong>
                                                            @else
                                                                <strong>{{__('layouts.restDay')}}</strong>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top">{{__('tuesday.tuesday')}}</td>
                                                        <td valign="center" align="right" style="font-size:13px;">
                                                            @if($BarRW->D2Starts1 != "none" && $BarRW->D2End1 != "none" && $BarRW->D2Starts2 != "none" && $BarRW->D2End2 != "none")
                                                                <strong>{{$BarRW->D2Starts1}} - {{$BarRW->D2End1}}</strong> {{__('layouts.and')}} <strong>{{$BarRW->D2Starts2}} - {{$BarRW->D2End2}}</strong>
                                                            @elseif($BarRW->D2Starts1 != "none" && $BarRW->D2End1 != "none")
                                                                <strong>{{$BarRW->D2Starts1}} - {{$BarRW->D2End1}}</strong>
                                                            @elseif($BarRW->D2Starts2 != "none" && $BarRW->D2End2 != "none")
                                                                <strong>{{$BarRW->D2Starts2}} - {{$BarRW->D2End2}}</strong>
                                                            @else
                                                                <strong>{{__('layouts.restDay')}}</strong>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top">{{__('layouts.wednesday')}}</td>
                                                        <td valign="center" align="right" style="font-size:13px;">
                                                            @if($BarRW->D3Starts1 != "none" && $BarRW->D3End1 != "none" && $BarRW->D3Starts2 != "none" && $BarRW->D3End2 != "none")
                                                                <span> <strong>{{$BarRW->D3Starts1}} - {{$BarRW->D3End1}}</strong> {{__('layouts.and')}} <strong>{{$BarRW->D3Starts2}} - {{$BarRW->D3End2}}</strong></span>
                                                            @elseif($BarRW->D3Starts1 != "none" && $BarRW->D3End1 != "none")
                                                                <strong>{{$BarRW->D3Starts1}} - {{$BarRW->D3End1}}</strong>
                                                            @elseif($BarRW->D3Starts2 != "none" && $BarRW->D3End2 != "none")
                                                                <strong>{{$BarRW->D3Starts2}} - {{$BarRW->D3End2}}</strong>
                                                            @else
                                                                <strong>{{__('layouts.restDay')}}</strong>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top">{{__('layouts.thursday')}}</td>
                                                        <td valign="center" align="right" style="font-size:13px;">
                                                            @if($BarRW->D4Starts1 != "none" && $BarRW->D4End1 != "none" && $BarRW->D4Starts2 != "none" && $BarRW->D4End2 != "none")
                                                                <strong>{{$BarRW->D4Starts1}} - {{$BarRW->D4End1}}</strong> {{__('layouts.and')}} <strong>{{$BarRW->D4Starts2}} - {{$BarRW->D4End2}}</strong>
                                                            @elseif($BarRW->D4Starts1 != "none" && $BarRW->D4End1 != "none")
                                                                <strong>{{$BarRW->D4Starts1}} - {{$BarRW->D4End1}}</strong>
                                                            @elseif($BarRW->D4Starts2 != "none" && $BarRW->D4End2 != "none")
                                                                <strong>{{$BarRW->D4Starts2}} - {{$BarRW->D4End2}}</strong>
                                                            @else
                                                                <strong>{{__('layouts.restDay')}}</strong>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top">{{__('layouts.friday')}}</td>
                                                        <td valign="center" align="right" style="font-size:13px;">
                                                            @if($BarRW->D5Starts1 != "none" && $BarRW->D5End1 != "none" && $BarRW->D5Starts2 != "none" && $BarRW->D5End2 != "none")
                                                                <strong>{{$BarRW->D5Starts1}} - {{$BarRW->D5End1}}</strong> {{__('layouts.and')}} <strong>{{$BarRW->D5Starts2}} - {{$BarRW->D5End2}}</strong>
                                                            @elseif($BarRW->D5Starts1 != "none" && $BarRW->D5End1 != "none")
                                                                <strong> {{$BarRW->D5Starts1}} - {{$BarRW->D5End1}}</strong>
                                                            @elseif($BarRW->D5Starts2 != "none" && $BarRW->D5End2 != "none")
                                                                <strong>{{$BarRW->D5Starts2}} - {{$BarRW->D5End2}}</strong>
                                                            @else
                                                                <strong>{{__('layouts.restDay')}}</strong>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top">{{__('layouts.saturday')}}</td>
                                                        <td valign="center" align="right" style="font-size:13px;">
                                                            @if($BarRW->D6Starts1 != "none" && $BarRW->D6End1 != "none" && $BarRW->D6Starts2 != "none" && $BarRW->D6End2 != "none")
                                                                <strong>{{$BarRW->D6Starts1}} - {{$BarRW->D6End1}}</strong> {{__('layouts.and')}} <strong>{{$BarRW->D6Starts2}} - {{$BarRW->D6End2}}</strong>
                                                            @elseif($BarRW->D6Starts1 != "none" && $BarRW->D6End1 != "none")
                                                                <strong>{{$BarRW->D6Starts1}} - {{$BarRW->D6End1}}</strong>
                                                            @elseif($BarRW->D6Starts2 != "none" && $BarRW->D6End2 != "none")
                                                                <strong>{{$BarRW->D6Starts2}} - {{$BarRW->D6End2}}</strong>
                                                            @else
                                                                <strong>{{__('layouts.restDay')}}</strong>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top">{{__('layouts.sunday')}}</td>
                                                        <td valign="center" align="right" style="font-size:13px;">
                                                            @if($BarRW->D7Starts1 != "none" && $BarRW->D7End1 != "none" && $BarRW->D7Starts2 != "none" && $BarRW->D7End2 != "none")
                                                                <strong>{{$BarRW->D7Starts1}} - {{$BarRW->D7End1}}</strong> {{__('layouts.and')}} <strong>{{$BarRW->D7Starts2}} - {{$BarRW->D7End2}}</strong>
                                                            @elseif($BarRW->D7Starts1 != "none" && $BarRW->D7End1 != "none")
                                                                <strong> {{$BarRW->D7Starts1}} - {{$BarRW->D7End1}}</strong>
                                                            @elseif($BarRW->D7Starts2 != "none" && $BarRW->D7End2 != "none")
                                                                <strong>{{$BarRW->D7Starts2}} - {{$BarRW->D7End2}}</strong>
                                                            @else
                                                                <strong>{{__('layouts.restDay')}}</strong>
                                                            @endif</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        
                                        <h5 style="margin-top:40px;"> <i class="fa fa-location-arrow" aria-hidden="true"></i> <strong>{{__('layouts.address')}}:</strong></h5>
                                        
                                        <p style=" padding: 5px;">
                                            @if(Barbershop::find($theB)->map != 'none')
                                            <iframe src="{{Barbershop::find($theB)->map}}"
                                                width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                            @else
                                            <div id="map"> <iframe width='100%' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'
                                            src='https://maps.google.com/maps?&amp;q="+ {{Barbershop::find($theB)->adresa}} + "&amp;hl=de&amp;output=embed'></iframe></div>
                                            @endif

                                            {{Barbershop::find($theB)->emri}}<br>
                                            {{Barbershop::find($theB)->adresa}}</p>
                        
                                
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('layouts.close')}}</button>
                                    </div>
                                    </div>

                                </div>
                                </div>
                            @endif


                        <script>
                            //Barbershop Rating Post function
                            $('#ajaxformBARrating').on('submit',function(event){
                                event.preventDefault();

                                $.ajax({
                                    url:"{{route('restaurantRatings.storeBarbershopR')}}",
                                    type : 'post',
                                    data:{
                                        "_token": "{{ csrf_token() }}",
                                        bar_id:$("input[name=bar_id]").val(),
                                        nickname: $("input[name=nickname]").val(),
                                        title:$("input[name=title]").val(),
                                        email:$("input[name=email]").val(),
                                        stars:$("input[name=stars]:checked").val(),
                                        comment:$("textarea[name=comment]").val(),
                                        verified:0,
                                        clientBy:$("input[name=klientiRat]").val()
                                    },
                                    success:function(data){
                                        // console.log(data);
                                        if(data) {
                                            $('#successRating').show(200).delay(3000).hide(200);
                                            $("#ajaxformBARrating")[0].reset();
                                        }
                                    },
                                    error: (error) => {
                                        console.log(error);
                                    }
                                });
                            });
                        </script>






















































































    @else

        <div class="container">
            <div class="row b-white">
                
                <div class="col-lg-6 col-sm-12 text-center" style="margin-top:7%;">
                    <p class="color-qrorpa" style="font-size:40px;">{{__('layouts.pleaseScanQrCode')}}</p>
                </div>
               
                <div class="col-lg-6 col-sm-12">
                    <img src="storage/gifs/qrScan.gif" alt="" style="width: 100%">
                </div>
            </div>
        </div>

                                            
    @endif


   

    <div class="container-fluid" style="padding:0;">
         @yield('content')
    </div>











     <script>

 
        //Restaurant Rating Post function
            $('#ajaxform').on('submit',function(event){
                event.preventDefault();

                restaurant_id = $("input[name=restaurant_id]").val();
                    nickname = $("input[name=nickname]").val();
                    stars = $("input[name=stars]:checked").val();
                   title = $("input[name=title]").val();
                   email = $("input[name=email]").val();
                    comment = $("textarea[name=comment]").val();
                    verified = $("input[name=verified]").val();
               

                $.ajax({
                  url:"{{route('restaurantRatings.store')}}",
                  type : 'post',
                
                  data:{
                    "_token": "{{ csrf_token() }}",
                    restaurant_id:restaurant_id,
                    nickname:nickname,
                    title:title,
                    email:email,
                    stars:stars,
                    comment:comment,
                    verified:verified,
                    clientBy:$("input[name=klientiRat]").val()
                  },
                    success:function(data){
                        if(data) {
                            $('.success').text(data.success);
                            $("#ajaxform")[0].reset();
                        }
                    },
                    error: (error) => {
                        console.log(error);
                    }
                 });
            });


        //Tabs script
                function openCity(evt, cityName) {
                      var i, tabcontent, tablinks;
                      tabcontent = document.getElementsByClassName("tabcontent");
                      for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                      }
                      tablinks = document.getElementsByClassName("tablinks");
                      for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                      }
                      document.getElementById(cityName).style.display = "block";
                      evt.currentTarget.className += " active";
                    }




            $(document).ready(function() {
                $(window).scroll(function(){
                    $('.cartBottom').toggleClass('scrolling', $(window).scrollTop() > $('#header').offset());

                    //long-form
                    var scrollPosition, headerOffset, isScrolling;

                    scrollPosition = $(window).scrollTop();
                    headerOffset = $('#header').offset();
                    isScrolling = scrollPosition > headerOffset;
                    $('.cartBottom').toggleClass('scrolling', isScrolling);
                });

            });









            function countEinloggenClick(){
                    $.ajax({
						url: '{{ route("saStatistics.einloggenClicksOne") }}',
						method: 'post',
						data: {
							id: 1,
							_token: '{{csrf_token()}}'
						},
						success: () => {
						},
						error: (error) => {
							console.log(error);
						}
					});
            }
            function countRegisterClick(){
                    $.ajax({
						url: '{{ route("saStatistics.registerClicksOne") }}',
						method: 'post',
						data: {
							id: 1,
							_token: '{{csrf_token()}}'
						},
						success: () => {
						},
						error: (error) => {
							console.log(error);
						}
					});
            }
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
            function WaiterCallsOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.WaiterCallsOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function CartOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.CartOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function MyOrdersOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.MyOrdersOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function TrackOrderOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.TrackOrderOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function Covid19OpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.Covid19OpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function ProfileOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.ProfileOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }

            function bannerClickCount(ResId){
                $.ajax({
					url: '{{ route("saStatistics.BannerClickOne") }}',
					method: 'post',
                    data: {id: ResId, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }

            function bannerLinkClick(ResCoId , ResId){
                $.ajax({
					url: '{{ route("saStatistics.BannerClickLinkOne") }}',
					method: 'post',
                    data: {resId: ResId, resCoId: ResCoId, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }



        </script>
   




</body>
</html>


