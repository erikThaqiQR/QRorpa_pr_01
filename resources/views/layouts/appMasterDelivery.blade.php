<?php
    use App\Restorant;
    use App\RestaurantWH;
    use App\RestaurantRating;
    use App\resdemoalfa;
    use App\RestaurantCover;

    use App\DeliverySchedule;

    if(isset($_GET['Res'])){


        
        $RWHT = DeliverySchedule::where('toRes', $_GET['Res'])->first();

        $thisRestaurantRatings = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->orderBy('updated_at','DESC')->get();
        $thisRestaurantRatingsSum = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->sum('stars');
        $thisRestaurantRaringAverage = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->avg('stars');

        $theResId = $_GET['Res'];

        $thisRestaurantCoverImage = RestaurantCover::where([['res_id', '=', $theResId],['status', '=', '1']])->orWhere('res_id', 0)->orderBy('position')->get();
        $thisRestaurantCover = RestaurantCover::where('res_id' , $theResId)->orWhere('res_id' ,0)->first();

    }
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // use Cart;
?>
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



    @if(isset($_GET['Res']))
        <title>{{Restorant::find($_GET['Res'])->emri}} {{__('layouts.qrorpaSystem')}}</title>
        <link rel="icon" href="../storage/ResProfilePic/{{Restorant::find($_GET['Res'])->profilePic}}">
    @else
        <title>{{__('layouts.qrorpaSystem')}}</title>
    @endif

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











</style>










<!--Duhet me shtu edhe pjesen e hashit apo nenshkrimit digjital ne Production-->
<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION["change"] = true;
?>

</head>














































@if(isset($_GET['Res']))
    <input type="hidden" value="{{$_GET['Res']}}" id="RestoIdId">
@endif









<script>
    var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {
    cluster: 'eu'
    });
    var channel = pusher.subscribe('CartChanel');
    channel.bind('App\\Events\\CartMsg', function(data) {
    
        var dataJ =  JSON.stringify(data);
        var dataJ2 =  JSON.parse(dataJ);
    
        var FromCart = dataJ2.text.split("-0-");
        if($('#RestoIdId').val() == FromCart[0] && $('#TableIdId').val() == FromCart[1]){
            if(FromCart[2] == 1){
                location.reload();
            }else if(FromCart[2] == 0){
                // alert('we did it');
                $.ajax({
                    url: '{{ route("Res.DeleteTheCart") }}',
                    method: 'post',
                    data: {
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        location.reload();
                    },
                    error: (error) => {
                        console.log(error);
                        // alert({{__('adminP.oops_wrong')}})
                    }
                });
            }
        }
    });
</script>














































<body>
    @if(isset($_GET['Res']))
        <?php
            $sendResLogin = $_GET['Res'];
           
            // $GLOBALS["foo"]
            $_SESSION["Res"] = $_GET['Res'];

            unset($_SESSION['Bar']);

        ?>
    @endif

    @if(isset($_SESSION["t"]))
        <?php
            unset ($_SESSION["t"]);
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
                                <img style="width:40px; height:40px; border-radius:50%;"  src="../storage/profilePics/{{Auth::user()->profilePic}}" alt="img">
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
                    @if(isset($_GET["Res"]) )
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
                                <a onclick="MyOrdersOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ url('/home') }}"><i class="fas fa-utensils"></i> {{__('layouts.orders')}}</a>
                            @endif
                            <a class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="/tableRezIndex?Res={{$_GET['Res']}}"><i class="fas fa-table"></i> {{__('layouts.tableReservation')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>

                        @else<!--Restorant(Yes)  User(No) -->
                            <a onclick="CartOpenClick()" class="optionsAnchorPh  {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('cart') }}"><i class="fas fa-shopping-cart"></i> {{__('layouts.cart')}}
                                @if(  count(Cart::content()) > 0)
                                    <sup>  <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;"> {{ count(Cart::content()) }}</span></sup>
                                @endif
                            </a>   
                            <a onclick="TrackOrderOpenClick()" class="optionsAnchorPh  {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('trackOrder.Home') }}"><i class="fas fa-file-contract"></i> {{__('layouts.trackOrder')}}</a>
                            <a class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="/tableRezIndex?Res={{$_GET['Res']}}"><i class="fas fa-table"></i> {{__('layouts.tableReservation')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                           
                        @endif
                    @else
                        @if(Auth::check())<!--Restorant(No)  User(Yes) -->
                            @if(auth()->user()->role == 9)
                                <a onclick="SAPageOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('manageProduktet.index') }}"><i class="fas fa-columns"></i> {{__('layouts.restaurantManagement')}}</a>
                            @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3 || auth()->user()->role == 15)
                                <a onclick="APageOpenClick()" class="optionsAnchorPh  {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('dash.index')}}"><i class="fas fa-columns"></i> {{__('layouts.adminMenu')}}</a>
                            @elseif(auth()->user()->role == 1)
                        
                            @endif
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                        @else<!--Restorant(No)  User(No) -->
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
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

       
        <div class="row">
            <div class="col-12" >
                <a onclick="CartOpenClick()" type="button" href="{{route('cart')}}" class="btn btn-default" ><img src="/storage/icons/Cart.PNG"/></a> 
               <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img src="/storage/icons/listDown.PNG"/></button> 
            </div>
        </div>
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
                <a onclick="CartOpenClick()" type="button" href="{{route('cart')}}" class="btn btn-default" ><img src="/storage/icons/Cart.PNG"/></a> 
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img src="/storage/icons/listDown.PNG"/></button> 
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
    width: 100%;
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


<!-- Start Restorant  -->
    @if(isset($_GET['Res']) )
    <?php $theR = $_GET['Res']; ?>
      
        <div class="container cover-container">
     
            <div class="row" >

                @if($thisRestaurantCoverImage)
                    <div class="slideshow-container">
                        @foreach($thisRestaurantCoverImage as $thisResCover)
                        <div class="mySlides fadeSlide" onclick="bannerClickCount('{{$theR}}')">
                            <a style="cursor: pointer;" data-toggle="modal" data-target="#coverInfo{{$thisResCover->id}}">
                            <img style="width:100%; height:180px; object-fit: cover;" src="../storage/ResBackgroundPic/{{$thisResCover->image}}" alt="noimg"></a>
                        </div>
                        @endforeach                    
                        </div>
                        <br>
                        <div style="text-align:center">                
                        @foreach($thisRestaurantCoverImage as $thisResCover)
                        <span class="dot" style="display: none;"></span> 
                        @endforeach
                    </div>
                @endif

                <div style="width:36%; z-index: 1;"></div>
                <div class="profilepic-area" style="width:28%;">
                  @if(Restorant::find($_GET['Res']) != null && Restorant::find($_GET['Res'])->profilePic == 'none')
                        <img style="width:110px; height:110px; border-radius:50%; background-color:white; border: 1px solid #d7d7d7;" src="../storage/images/showcase03.png" alt="">
                    @else
                        <img style="width:110px; height:110px; border-radius:50%;border: 1px solid #d7d7d7;" src="../storage/ResProfilePic/{{Restorant::find($_GET['Res'])->profilePic}}" alt="">
                    @endif
                </div>
                
               

        

      
              
            </div>
            <div class="row">
                <div class="col-md-12 restaurant-title">
                    @if(isset($_GET['Res']) )
                        @if(isset($_GET['demo']))
                            <p style="font-size:19px;margin-bottom: 0rem; color:rgb(72, 81, 87);"><strong>{{(resdemoalfa::where('forThis','=',$_GET['Res'])->first())->emri}}</strong></p>
                        @else
                      
                            <p style="font-size:19px; margin-bottom: 0rem; margin-bottom:-12px; color:rgb(72, 81, 87);"><strong>{{Restorant::find($_GET['Res'])->emri}}</strong> 
                                @if($RWHT != null)
                                    <a style="cursor: pointer;" data-toggle="modal" data-target="#resInfo{{$_GET['Res']}}">
                                    <i class="fa fa-info-circle infoButton"  aria-hidden="true" style="float: right;"></i></a>
                                @endif
                            </p>
                      
                            <style>
                                .yjetMenuRat{
                                    font-size:24px;
                                }
                            </style>
                                
                                @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                                    <a style="cursor: pointer; " data-toggle="modal" class="ratingModal" data-target="#ratingModal{{$_GET['Res']}}">
                                    @if($thisRestaurantRatings != null)
                                        @if(number_format($thisRestaurantRaringAverage,1) < 0.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".00"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 0.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".50"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 1)
                                            <div class="star-ratings-css yjetMenuRat" title=".100"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 1.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".150"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) < 2)
                                            <div class="star-ratings-css yjetMenuRat" title=".200"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 2)
                                            <div class="star-ratings-css yjetMenuRat" title=".200"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 2.5 )
                                            <div class="star-ratings-css yjetMenuRat" title=".250"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) < 3 )
                                            <div class="star-ratings-css yjetMenuRat" title=".300"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 3)
                                            <div class="star-ratings-css yjetMenuRat" title=".300"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 3.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".350"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) < 4)
                                            <div class="star-ratings-css yjetMenuRat" title=".400"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 4)
                                            <div class="star-ratings-css yjetMenuRat" title=".400"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 4.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".450"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) < 5)
                                            <div class="star-ratings-css yjetMenuRat" title=".500"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 5)
                                            <div class="star-ratings-css yjetMenuRat" title=".500"></div>
                                        @endif
                                        <span id="bewer">( {{count($thisRestaurantRatings)}} {{__('layouts.reviews')}})</span>
                                    @else
                                        <div class="star-ratings-css" title=".00"></div>
                                        <span id="bewer">( 0 {{__('layouts.reviews')}})</span>
                                    @endif
                                    </a>
                                @endif
                                
                        @endif
                    @endif

                    <div class="col-md-12 search-area">
                        <div class="wrap text-center">
                            <div class="search">
                                <input id="input_search" type="text" class="searchTerm" placeholder="{{__('layouts.whatAreYouLookingFor')}}">
                                <button type="submit" class="searchButton text-center">
                                    <i class="fa fa-search" style="padding: 0px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
                    @if($RWHT != null)
                    <!-- Info Modal -->
                        <div id="resInfo{{$_GET['Res']}}" class="modal fade" role="dialog">
                          <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header" style="position: sticky; z-index: 10; top:0">
                                <h4 class="modal-title">{{__('layouts.aboutRestaurant')}}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">


                            
                                    @if(Restorant::find($_GET['Res'])->resDesc != 'none')
                                        <p> <strong>{{__('layouts.description')}}:</strong>{{Restorant::find($_GET['Res'])->resDesc}}</p> 
                                    @endif
                                <h5> <i class="fa fa-clock-o" aria-hidden="true"></i> <strong>{{__('layouts.openTime')}}:</strong></h5>

                                   
                                    <table class="table info-table" style="width: 100%; background-color: #fafafa;">
                                        <tbody>
                                            <tr>
                                                <td valign="top">{{__('layouts.monday')}} </td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->day11S != "00:00" && $RWHT->day11E != "00:00" && $RWHT->day21S != "00:00" && $RWHT->day21E != "00:00")
                                                        <strong>{{$RWHT->day11S}} - {{$RWHT->day11E}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->day21S}} - {{$RWHT->day21E}}</strong>
                                                    @elseif($RWHT->day11S != "00:00" && $RWHT->day11E != "00:00")
                                                        <strong>{{$RWHT->day11S}} - {{$RWHT->day11E}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.tuesday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->day12S != "00:00" && $RWHT->day12S != "00:00" && $RWHT->day22S != "00:00" && $RWHT->day22E != "00:00")
                                                        <strong>{{$RWHT->day12S}} - {{$RWHT->day12S}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->day22S}} - {{$RWHT->day22E}}</strong>
                                                    @elseif($RWHT->day12S != "00:00" && $RWHT->day12S != "00:00")
                                                        <strong>{{$RWHT->day12S}} - {{$RWHT->day12S}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.wednesday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->day13S != "00:00" && $RWHT->day13S != "00:00" && $RWHT->day23S != "00:00" && $RWHT->day23E != "00:00")
                                                        <span> <strong>{{$RWHT->day13S}} - {{$RWHT->day13S}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->day23S}} - {{$RWHT->day23E}}</strong></span>
                                                    @elseif($RWHT->day13S != "00:00" && $RWHT->day13S != "00:00")
                                                        <strong>{{$RWHT->day13S}} - {{$RWHT->day13S}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.thursday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->day14S != "00:00" && $RWHT->day14S != "00:00" && $RWHT->day24S != "00:00" && $RWHT->day24E != "00:00")
                                                        <strong>{{$RWHT->day14S}} - {{$RWHT->day14S}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->day24S}} - {{$RWHT->day24E}}</strong>
                                                    @elseif($RWHT->day14S != "00:00" && $RWHT->day14S != "00:00")
                                                        <strong>{{$RWHT->day14S}} - {{$RWHT->day14S}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.friday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->day15S != "00:00" && $RWHT->day15S != "00:00" && $RWHT->day25S != "00:00" && $RWHT->day25E != "00:00")
                                                        <strong>{{$RWHT->day15S}} - {{$RWHT->day15S}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->day25S}} - {{$RWHT->day25E}}</strong>
                                                    @elseif($RWHT->day15S != "00:00" && $RWHT->day15S != "00:00")
                                                       <strong> {{$RWHT->day15S}} - {{$RWHT->day15S}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.saturday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->day16S != "00:00" && $RWHT->day16S != "00:00" && $RWHT->day26S != "00:00" && $RWHT->day26E != "00:00")
                                                        <strong>{{$RWHT->day16S}} - {{$RWHT->day16S}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->day26S}} - {{$RWHT->day26E}}</strong>
                                                    @elseif($RWHT->day16S != "00:00" && $RWHT->day16S != "00:00")
                                                        <strong>{{$RWHT->day16S}} - {{$RWHT->day16S}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.sunday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->day10S != "00:00" && $RWHT->day10S != "00:00" && $RWHT->day20S != "00:00" && $RWHT->day20E != "00:00")
                                                        <strong>{{$RWHT->day10S}} - {{$RWHT->day10S}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->day20S}} - {{$RWHT->day20E}}</strong>
                                                    @elseif($RWHT->day10S != "00:00" && $RWHT->day10S != "00:00")
                                                       <strong> {{$RWHT->day10S}} - {{$RWHT->day10S}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                
                                <h5 style="margin-top:40px;"> <i class="fa fa-location-arrow" aria-hidden="true"></i> <strong>{{__('layouts.address')}}:</strong></h5>
                                
                                <p style=" padding: 5px;">
                                    @if(Restorant::find($_GET['Res'])->map != 'none')
                                    <iframe src="{{Restorant::find($_GET['Res'])->map}}"
                                        width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                    @else
                                     <div id="map"> <iframe width='100%' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'
                                      src='https://maps.google.com/maps?&amp;q="+ {{Restorant::find($_GET['Res'])->adresa}} + "&amp;hl=de&amp;output=embed'></iframe></div>
                                    @endif

                                    {{Restorant::find($_GET['Res'])->emri}}<br>
                                    {{Restorant::find($_GET['Res'])->adresa}}</p>
                  
                         
                                 
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('layouts.close')}}</button>
                              </div>
                            </div>

                          </div>
                        </div>
                    @endif
                  












                      <!-- Ratings Modal -->
                        <div id="ratingModal{{$_GET['Res']}}" class="modal fade" role="dialog">
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
                                        
                                                <form id="ajaxform" >
                                                    {{csrf_field()}}
                                                    <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
                                                    <div class="form-group text-center" style="display: table-row;">
                                                        <div class="rating-stars text-center">
                                                        
                                                            <fieldset class="rating text-center" >
                                                                <strong>{{__('layouts.overallRating')}}*</strong><br>
                                                                <input type="radio" id="star5" name="stars" value="5" required="" /><label class = "full stars" for="star5" title="{{__('layouts.awesome5Stars')}}"></label>   
                                                                <input type="radio" id="star4" name="stars" value="4" /><label class = "full stars" for="star4" title="{{__('layouts.prettyGood4Stars')}}"></label>
                                                                <input type="radio" id="star3" name="stars" value="3" /><label class = "full stars" for="star3" title="{{__('layouts.meh3Stars')}}"></label>
                                                                <input type="radio" id="star2" name="stars" value="2" /><label class = "full stars" for="star2" title="{{__('layouts.kindaBad2Stars')}}"></label>
                                                                <input type="radio" id="star1" name="stars" value="1" /><label class = "full stars" for="star1" title="{{__('layouts.sucksBigTime1Star')}}"></label>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <label><strong>{{__('layouts.nickname')}}*</strong></label>
                                                        <input type="text" name="nickname" class="form-control" placeholder="" required="" id="nickname">
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <label>{{__('layouts.title')}}</label>
                                                        <input type="text" name="title" class="form-control" placeholder="" id="titel">
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <label>{{__('layouts.email')}}</label>
                                                        <input type="email" name="email" class="form-control" placeholder="" id="email">
                                                    </div>

                                                    @if(Auth::check())
                                                        <input type="hidden" name="klientiRat" value="{{Auth::user()->id}}">
                                                    @else
                                                        <input type="hidden" name="klientiRat" value="0">
                                                    @endif

                                                

                                                    <div class="form-group">
                                                        <label>{{__('layouts.review')}}</label>
                                                        <textarea class="form-control"  name="comment" rows="4" cols="50"></textarea>
                                                        <input type="hidden" name="restaurant_id" class="form-control" value="{{Restorant::find($_SESSION["Res"])->id}}" id="restaurant_id">
                                                    </div>
                                                    <input type="hidden" name="verified" class="form-control" placeholder="" value="0" id="verified">
                                                
                                                    <div class="form-group d-flex justify-content-between">
                                                        <button class="btn btn-success send-button" id="submit" style="margin-top: 20px;margin-bottom: 20px; width:45%;">{{__('layouts.send')}}</button>
                                                        <button style="width:45%; margin-top: 20px;margin-bottom: 20px; background-color:red; color:white; border-radius:30px;" type="button" class="btn" data-dismiss="modal">{{__('layouts.cancel')}}</button>
                                                    </div>
                                                </form>
                                                    
                                                    <table class="table">
                                                    
                                                    
                                                    
                                                            <tbody>
                                                                @foreach($thisRestaurantRatings as $thisres)
                                                                        <tr>
                                                                            <td style="font-weight:bold;">{{$thisres->nickname}} &nbsp&nbsp&nbsp @if($thisres->stars == 5)
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























                        <!-- Cover Info Modal -->
                        @foreach($thisRestaurantCoverImage as $thisResCover)
                        <div id="coverInfo{{$thisResCover->id}}" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header" style="position: sticky; z-index: 10; top:0">
                                <h4 class="modal-title"><i class="fa fa-info-circle" aria-hidden="true"></i> {{__('layouts.aboutCover')}}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <div class="link-area">
                                   <a onclick="bannerLinkClick('{{$thisResCover->id}}', '{{$theResId}}')" target="_blank" href="{{ url($thisResCover->link) }}"><i class="fa fa-external-link" aria-hidden="true"></i> {{$thisResCover->link}}</a>
                               </div>
                                <div class="text-info-area">
                                   {{$thisResCover->text}}
                               </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('layouts.close')}}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                       @endforeach

                    


        <!-- <div class="text-center mt-1 mb-4">
            <img style="width:20px;" src="https://img.icons8.com/ultraviolet/40/000000/star.png"/><span style="color:rgb(39,190,175)"><strong>4.5</strong></span>
        </div> -->



        <br>
<!-- End Restorant  -->















































































<!-- Start Barbershop  -->
    @else

        <!-- <div class="container">
            <div class="row b-white">
                
                <div class="col-lg-6 col-sm-12 text-center" style="margin-top:7%;">
                    <p class="color-qrorpa" style="font-size:40px;">Bitte scannen Sie zuerst den QR-Code, um Ihre Bestellung aufzugeben!</p>
                </div>
               
                <div class="col-lg-6 col-sm-12">
                    <img src="storage/gifs/qrScan.gif" alt="" style="width: 100%">
                </div>
            </div>
        </div> -->
        @if(!Auth::check() || !isset($_GET['Res']))
            <?php
                header("Location: ".route('firstPage.index'));
                exit();
            ?>
        @endif
                                            
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


         /*$('#bewer').on('click',function(){
                        
                $.ajax({
                    dataType: 'json',
                    type : 'get',
                    url : '/',

                    data:{ 
                          
                    },
                    success:function(data)
                    {
                        console.log(data);
                       
                             var res='';
                       
                       
                        $.each (data, function (key, value) {
                        res +=


                        '<tr>'+
                        
                             '<td style="font-weight:bold;">'+value.nickname+
                             '<br>'+
                             '<p style="padding:3px; font-size:13px; font-weight:normal;">'+value.comment+'</p>'                   

                             '</td>'
                            
                               


                                    if (value.stars == 5) {
                                        res+= '<td>'+'<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span></td></tr>'
                                    }
                                    else if (value.stars == 4){
                                             res+= '<td>'+'<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '</td></tr>'
                                    }
                                    else if (value.stars == 3){
                                             res+= '<td>'+'<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '</td></tr>'
                                    }
                                    else if (value.stars == 2){
                                             res+= '<td>'+'<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '</td></tr>'
                                    }
                                    else if (value.stars == 1){
                                             res+= '<td>'+'<span class="fa fa-star checked"></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '<span class="fa fa-star "></span>'+
                                                            '</td></tr>'
                                    }

                                   
                        

               });

                        $('tbody').html(res);
                    }


                });
                })
*/



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




</body>
</html>


