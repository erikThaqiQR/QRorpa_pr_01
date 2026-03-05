<?php

use App\admMsgSaavchats;
use Illuminate\Support\Facades\Auth;
    use App\accessControllForAdmins;
    use Carbon\Carbon;
    use App\Orders;
?>
<style>
    @keyframes glowingRED {
        0% { box-shadow: 0 0 -5px red; }
        40% { box-shadow: 0 0 30px red; }
        60% { box-shadow: 0 0 30px red; }
        100% { box-shadow: 0 0 -5px red; }
    }

    .button-glow-red {
        animation: glowingRED 700ms infinite;
    }
</style>
<!-- OptionsModal Phone -->
        <!-- The phone options Modal -->
        <div class="modal" id="optionsModal">
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
                                          {{__('adminP.myAccount')}}
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
                                      {{ __('adminP.logOut') }}
                                  </a>
                                  <form class="optionsAnchorPh" id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                      @csrf
                                  </form>
                              @else
                                  <a class=" btn {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:2px solid lightgray; color:black; width:48%;"
                                  href="{{ route('login') }}">{{__('adminP.login')}}</a>
                                            
                                  <a class="btn  {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:4px solid rgb(39, 190, 175); color:black; width:48%;"
                                  href="{{ route('register') }}">{{__('adminP.register')}}</a>
                                
                              @endif
                          </div>
                          <?php
                             $usersAccessTel = accessControllForAdmins::where([['forRes',Auth::user()->sFor],['userId',Auth::user()->id]])->get();
                          ?>

                          <div style="background-color:whitesmoke; width:100%;" class="mt-2">
                            <div>
                              @if(Auth::user()->role == 53)
                                @if($usersAccessTel->where('accessDsc','Statistiken')->first() != NULL && $usersAccessTel->where('accessDsc','Statistiken')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('admWoMng.AccountantStatistics')}}">
                                    <i class="fas fa-chart-line"></i>  {{__('adminP.statistics')}}      
                                  </a>
                                @endif

                                @if($usersAccessTel->where('accessDsc','Products')->first() != NULL && $usersAccessTel->where('accessDsc','Products')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('admWoMng.AccountantProducts')}}">
                                    <i class="fas fa-sitemap"></i> {{__('adminP.products')}}
                                  </a>
                                @endif
             
                              @endif
                                                   
                            </div>
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