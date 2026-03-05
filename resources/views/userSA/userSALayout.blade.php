<?php
    if(Auth::check() && Auth::user()->role != 54439){
        header("Location: /");
        exit(); 
    }
?>
<!DOCTYPE html>
<html lang="de" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

     <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
     @include('fontawesome')

</head>
<body style="background-color:rgb(39,190,175);">
    <nav class="navbar navbar-expand-sm b-white"  width="100%" id="navDesktop"">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-left">
                    <a class="navbar-brand" href="#"><img style="width:120px"  src="/storage/images/logo_QRorpa.png" alt=""></a>
                </div>
            </div>

            
            <div class="row">
                <div class="col-12" >
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img src="storage/icons/listDown.PNG"/></button> 
                </div>
            </div>
        </div>
    </nav>




    @yield('content')
























    
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
                                Mein Konto
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
                            {{ __('Ausgang') }}
                        </a>
                        <form class="optionsAnchorPh" id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a class=" btn {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:2px solid lightgray; color:black; width:48%;"
                         href="{{ route('login') }}">Einloggen</a>
                                   
                        <a class="btn  {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:4px solid rgb(39, 190, 175); color:black; width:48%;"
                         href="{{ route('register') }}">Registrieren</a>
                      
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







</body>
</html>