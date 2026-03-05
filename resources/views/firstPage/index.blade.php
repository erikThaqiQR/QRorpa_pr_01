@extends('firstPage.appM.kat01')

@section('content')
<style>
    #restaurantsList{
        position: absolute;
        width: 100%;
        margin-top:44px;
    }
    #restaurantsList ul{
        width: 100%
    }
    #restaurantsList ul li{
        border-bottom: 1px solid #dedede;
        margin:15px;
        padding-top:10px;
        padding-bottom:10px;
    }

    #restaurantsList ul li a{
        color: #212529;
        padding: 15px;
        font-size: 14px; 
    }
    form.example input[type=text] {
      padding: 10px;
      font-size: 17px;
      border: 1px solid grey;
      float: left;
      width: 80%;
      background: #fff;
    }
    input[type=text]:focus {
        outline: -webkit-focus-ring-color auto 0px !important;
    }
    form.example button {
      float: left;
      width: 20%;
      padding: 10px;
      background: #02beaf;
      color: white;
      font-size: 17px;
      border: 1px solid grey;
      border-left: none;
      cursor: pointer;
    }
    form.example button:hover {
      background: #02beaf;
    }
    form.example::after {
      content: "";
      clear: both;
      display: table;
    }   




                    
</style>



         <!-- ***** Header Text Start ***** -->
         <div class="header-text" id="indexConDesktop" style="display:none !important;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="center-heading" style="margin-top:120px;">

                          <form class="example" id="searchForm" action="{{ route('searchRestaurants') }}" method="GET"  autocomplete="off" >
                              <!--end of col-->
                              <div class="col">
                                  <input id="emri" name="emri" type="text" style="border-top-left-radius:20px; border-bottom-left-radius:20px;" placeholder="Adresse, z.B. Müllheimerstrasse 100">                              
                                      <button type="submit" style="border-top-right-radius:20px; border-bottom-right-radius:20px;" ><i class="fa fa-search" ></i></button>
                                      <div id="restaurantsList" type="submit"></div>                        
                              </div>   
                            {{ csrf_field() }}
                              <!--end of col-->
                          </form>

                        </div>
                    </div>
                    <div class="left-text col-lg-6 col-md-6 col-sm-12 col-xs-12">
                         <h1>Bestellen und bezahlen Sie mit <em>QRorpa</em></h1>
                        <p>Scannen Sie den QR-Code ein. Den finden Sie im Flyer, der direkt auf dem Tisch liegt.</p>  
                        <div class="col-md-12" style="margin-bottom:20px; padding: 0px;">
                        <video width="100%" muted="" autoplay="1" loop="1" playsinline="" style="outline: none;">
                            <source src="storage/FP-images/Qrorpa-Animation.mp4" type="video/mp4" loop="1">
                          </video>
                      </div>
                        <a href="#about" class="main-button-slider">QRorpa System</a>
                        
                    </div>
                    <div class="left-text col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <img src="storage/FP-images/slider-image.png" class="slider-image" style="max-width: 100%; height: auto;">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- ***** Header Text End ***** -->








        

         <!-- ***** Header Text Start ***** -->
         <div class="header-text" id="indexConDesktop2" style="display:none !important;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="center-heading" style="margin-top:120px;">

                          <form class="example" id="searchForm" action="{{ route('searchRestaurants') }}" method="GET"  autocomplete="off" >
                              <!--end of col-->
                              <div class="col">
                                  <input id="emri" name="emri" type="text" style="border-top-left-radius:20px; border-bottom-left-radius:20px;" placeholder="Adresse, z.B. Müllheimerstrasse 100">                              
                                      <button type="submit" style="border-top-right-radius:20px; border-bottom-right-radius:20px;" ><i class="fa fa-search" ></i></button>
                                      <div id="restaurantsList" type="submit"></div>                        
                              </div>   
                            {{ csrf_field() }}
                              <!--end of col-->
                          </form>

                        </div>
                    </div>
                    <div style="padding-top:400px;" class="left-text col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12">
                         <h1>Bestellen und bezahlen Sie mit <em>QRorpa</em></h1>
                        <p>Scannen Sie den QR-Code ein. Den finden Sie im Flyer, der direkt auf dem Tisch liegt.</p>  
                        <div class="col-md-12" style="margin-bottom:20px; padding: 0px;">
                        <video width="100%" muted="" autoplay="1" loop="1" playsinline="" style="outline: none;">
                            <source src="storage/FP-images/Qrorpa-Animation.mp4" type="video/mp4" loop="1">
                          </video>
                      </div>
                        <a href="#about" class="main-button-slider">QRorpa System</a>
                        
                    </div>
                    <div class="left-text col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <img src="storage/FP-images/slider-image.png" class="slider-image" style="max-width: 100%; height: auto;">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- ***** Header Text End ***** -->





           <!-- ***** Header Text Start ***** -->
           <div class="header-text" id="indexConTel">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="center-heading" style="margin-top:-240px;">

                          <form class="example" id="searchForm" action="{{ route('searchRestaurants') }}" method="GET"  autocomplete="off" >
                       
                                  <input id="emri" name="emri" type="text" style="border-top-left-radius:20px; border-bottom-left-radius:20px;" placeholder="Adresse, z.B. Müllheimerstrasse 100">                              
                                      <button type="submit" style="border-top-right-radius:20px; border-bottom-right-radius:20px;" ><i class="fa fa-search" ></i></button>
                                      <!-- <div id="restaurantsList" type="submit"></div>                         -->
                   
                            {{ csrf_field() }}
                              <!--end of col-->
                          </form>
                        </div>
                    </div>
                    <div class="col-12" style="margin-top:-150px;">
                      <img src="storage/FP-images/slider-image.png" class="slider-image" style="width: 100%; height: auto;">
                    </div>
                    <div class="left-text col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <h2><strong>Bestellen und bezahlen Sie mit <em>QRorpa</em></strong></h2>
                        <p>Scannen Sie den QR-Code ein. Den finden Sie im Flyer, der direkt auf dem Tisch liegt.</p>  
                        <div class="col-md-12" style="margin-bottom:20px; padding: 0px;">
                            <video width="100%" muted="" autoplay="1" loop="1" playsinline="" style="outline: none;">
                                <source src="storage/FP-images/Qrorpa-Animation.mp4" type="video/mp4" loop="1">
                            </video>
                        </div>
                        <a href="#about" class="main-button-slider">QRorpa System</a>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- ***** Header Text End ***** -->
        
        <script>
                if (screen.width >= 1700) {
                    document.getElementById('indexConTel').style.display = "none";
                    document.getElementById('indexConDesktop').style.display = "block";
                }else if (screen.width >520 && screen.width < 1700) {
                    document.getElementById('indexConTel').style.display = "none";
                    document.getElementById('indexConDesktop2').style.display = "block";
                }
            // var loader = setInterval(function () {
            // if(document.readyState !== "complete") return;
            // }, 300);
        </script>
@endsection