<?php
    use App\Restorant;
    use App\Orders;
    use App\Barbershop;

    $barId = Auth::user()->sFor;

    echo '<input type="hidden" id="thisResId" value="'.Auth::user()->sFor.'">';

    $nowDate = date('Y-m-d');
?>

<style>
  .openNotifications:hover{
    cursor:pointer;
  }
</style>
<input type="hidden" value="{{$barId}}" id="theBarID">


 

<nav class="navbar navbar-expand navbar-light bg-white topbar static-top " id="DashNavbar">


          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

         
          <ul class="navbar-nav mr-auto">
            <li class="mt-3 ml-3">
                <p style="font-size:21px;" class="color-qrorpa"><strong>{{Barbershop::find($barId)->emri}}</strong></p>
              </li>
          </ul>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
       


           

            <div class="topbar-divider d-none d-sm-block"></div>

       

            <!-- Nav Item - User Information -->
            <li class="nav-item pt-3 mr-5 ml-3">
                 <a class="optionsAnchorPh pt-3 {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('adminP.logOut') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
            </li>

          </ul>

        </nav>








        <script>
        
           // Enable pusher logging - don't include this in production
						// Pusher.logToConsole = true;

						var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {cluster: 'eu'});
            var channel = pusher.subscribe('barNewRez');

            channel.bind('App\\Events\\barbershopNewRez', function(data) {
             
              var dataJ =  JSON.stringify(data);
              var dataJ2 =  JSON.parse(dataJ);
              // console.log(dataJ);
              // console.log(dataJ2);
              
              if($('#theBarID').val() == dataJ2.text){
                // document.getElementById("beepN").play();
            

                // test01082022
                $("#soundsAll").load(location.href+" #soundsAll>*","");









                setTimeout(function () {
                  location.reload(true);
                }, 800);
              }
						
						});
        </script>



   