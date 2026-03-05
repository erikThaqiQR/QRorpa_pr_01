<?php
    use App\Restorant;
    use App\Orders;
    use Carbon\Carbon;

    $thisRestaurantId = Auth::user()->sFor;

    echo '<input type="hidden" id="thisResId" value="'.Auth::user()->sFor.'">';

    $nowDate = date('Y-m-d');

    $ordersLi = Orders::where('Restaurant', '=', $thisRestaurantId)->where([['statusi', '=', 0],['nrTable','!=',500],['nrTable','!=',9000]])
    ->whereDate('created_at', Carbon::today())->get()->count();
?>

<style>
  .openNotifications:hover{
    cursor:pointer;
  }


  @keyframes glowing {
  0% { box-shadow: 0 0 -10px red; }
  40% { box-shadow: 0 0 20px red; }
  60% { box-shadow: 0 0 20px red; }
  100% { box-shadow: 0 0 -10px red; }
}

.button-glow {
  animation: glowing 1000ms infinite;
}
</style>


 

<nav class="navbar navbar-expand navbar-light bg-white topbar " id="DashNavbar">


          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

         
          <ul class="navbar-nav mr-auto">
            <li class="mt-3 ml-3">
                <p style="font-size:21px;" class="color-qrorpa"><strong></strong></p>
              </li>
          </ul>

          <!-- Topbar Navbar -->


          <ul class="navbar-nav ml-auto">
 

           
            <!-- Nav Item - Alerts -->
            <li class="nav-item  mx-1 mt-3">
             
                <!-- <i class="fas fa-bell fa-fw"></i> -->
                <!-- Counter - Alerts -->
               

           
            </li>
            <style>
             .optionsAnchorPh{
                    color:black;
                    text-decoration:none;
                    opacity:0.65;
                    font-weight: bold;
                    font-size:20px;
                }
                .optionsAnchorPh:hover{
                    opacity:0.95;
                    text-decoration:none;
                    color:black;
                    
                }
            </style>
           

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

   