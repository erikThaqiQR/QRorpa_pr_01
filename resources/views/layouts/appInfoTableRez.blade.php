<?php
    use App\Restorant;
    use App\TableReservation;
?>
<!DOCTYPE html>
<html lang="en">
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


    @if(isset($_GET['Res']))
        <title>{{Restorant::find($_GET['Res'])->emri}} {{__('adminP.qrorpa_system')}}</title>
        <link rel="icon" href="storage/ResProfilePic/{{Restorant::find($_GET['Res'])->profilePic}}">
    @else
        <title>{{__('adminP.qrorpa_system')}}</title>
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
    @if(isset($_GET['Res']) && $_GET['Res']==13)
            <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-35PRJMWDZ2"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-35PRJMWDZ2');
        </script>
    @else
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-173569880-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-173569880-1');
        </script>
    @endif

</head>
<body style="background-color: rgb(39,190,175);">
    <nav class="navbar navbar-expand-sm b-white"  width="100%">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-left">
                    <a class="navbar-brand" href="#"><img style="width:120px" src="/storage/images/logo_QRorpa.png" alt=""></a>
                </div>
            </div>

        
            <div class="row">
                <div class="col-12" >
                    <!-- <a onclick="CartOpenClick()" type="button" href="{{route('cart')}}" class="btn btn-default" ><img src="storage/icons/Cart.PNG"/></a>  -->
                <button type="button" class="btn btn-default"><img src="storage/icons/listDown.PNG"/></button> 
                </div>
            </div>
        </div>
    </nav>


    <div class="container-fluid" style="padding:0;">
         @yield('content')

         @include('inc.footer')
    </div>

   

</body>
</html>