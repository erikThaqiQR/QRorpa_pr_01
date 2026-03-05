<?php

  use App\checkInOutReg;
  use App\admMsgSaavchats;
  use App\TableReservation;
  use Illuminate\Support\Facades\Auth;
  use App\accessControllForAdmins;
  use Carbon\Carbon;
  use App\Orders;

  $usrChkIn = checkInOutReg::where([['theStat',0],['userId',Auth::user()->id]])->first();
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
                              @if(Auth::user()->role == 5 || Auth::user()->role == 4 )

                                <div style="width:100%;" class="d-flex flex-wrap mb-3 mt-2">
                                  @if($usrChkIn != Null)
                                    <?php
                                      $dt2D = explode('-',explode(' ',$usrChkIn->checkIn)[0]);
                                      $hr2D = explode(':',explode(' ',$usrChkIn->checkIn)[1]);
                                    ?>
                                    <button class="btn btn-outline-dark shadow-none" id="setCheckInBtn" style="width: 49%; background-color:rgba(39,190,175,0.6); border-radius:4px; margin:0.5%; font-weight:bold;">
                                      {{$dt2D[2].'.'.$dt2D[1].'.'.$dt2D[0]}} <span style="margin-right:10px;"></span> {{$hr2D[0].':'.$hr2D[1]}}
                                    </button>
                                    <button class="btn btn-outline-dark shadow-none" id="setCheckOutBtn" onclick="setCheckOut('{{$usrChkIn->id}}')" style="width: 49%; border-radius:4px; margin:0.5%;"><strong>Checkout</strong></button>
                                  
                                  @else
                                    <button class="btn btn-outline-dark shadow-none" id="setCheckInBtn" onclick="setCheckIn()" style="width: 49%; border-radius:4px; margin:0.5%; font-weight:bold;">
                                      Einchecken
                                    </button>
                                    <button class="btn btn-outline-dark shadow-none" id="setCheckOutBtn" style="width: 49%; border-radius:4px; margin:0.5%;"><strong>Checkout</strong></button>
                                  
                                  @endif

                                  <div class="alert alert-success text-center mt-1" id="checkInOutScc01" style="width:100%; display:none; font-weight:bold;"></div>
                                </div>

                                @if($usersAccessTel->where('accessDsc','Statistiken')->first() != NULL && $usersAccessTel->where('accessDsc','Statistiken')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('dash.statistics')}}">
                                    <i class="fas fa-chart-line"></i> Verkäufe      
                                  </a>
                                @endif

                                <div id="showRedBtnNewMsgTel">
                                <?php
                                  $newMsgForMeTel = admMsgSaavchats::where([['msgForId',Auth::User()->id],['readStatus',0]])->count();
                                ?>
                                @if($usersAccessTel->where('accessDsc','talkToQrorpaSA')->first() != NULL && $usersAccessTel->where('accessDsc','talkToQrorpaSA')->first()->accessValid == 1)
                                  @if($newMsgForMeTel > 0)
                                    <a class="optionsAnchorPh button-glow-red" style="color:red !important;" href="{{route('atsMsg.openPageAdminPanel')}}">
                                      <i class="far fa-comments"></i>  {{__('adminP.talkToQRorpa')}}      
                                    </a>
                                  @else
                                    <a class="optionsAnchorPh" href="{{route('atsMsg.openPageAdminPanel')}}">
                                      <i class="far fa-comments"></i>  {{__('adminP.talkToQRorpa')}}      
                                    </a>
                                  @endif
                                @endif
                                </div>


                                <a class="optionsAnchorPh" href="{{route('admin.notificationsActPage')}}">
                                  <i class="fa-regular fa-bell"></i> Aktive Benachrichtigungen     
                                </a>

                                                      
                                @if($usersAccessTel->where('accessDsc','Aufträge')->first() != NULL && $usersAccessTel->where('accessDsc','Aufträge')->first()->accessValid == 1)
                                  
                                  @if(Auth::user()->sFor != 32 && Auth::user()->sFor != 33)
                                  <a class="optionsAnchorPh" href="{{route('dash.index')}}">
                                    <i class="fas fa-border-all"></i>  {{__('adminP.orders')}}
                                  </a>
                                  @endif
                                      
                                  <div id="showModalServiceTel">
                                    <?php
                                        $takeawayOrCntTelMod = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
                                        ->where([['statusi','<',2],['nrTable','500']])->get()->count();
                                        $deliveryOrCntTelMod = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
                                        ->where([['statusi','<',2],['nrTable','9000']])->get()->count();
                                    ?>

                                  @if($takeawayOrCntTelMod > 0)
                                  <a class="optionsAnchorPh button-glow-red" style="color:red !important;" onclick="location.href = '/dashboardTakeaway';">
                                    <i class="fas fa-border-all"></i> {{__('adminP.takeawayOrders')}}
                                  </a>
                                  @else
                                  <a class="optionsAnchorPh" onclick="location.href = '/dashboardTakeaway';">
                                    <i class="fas fa-border-all"></i> {{__('adminP.takeawayOrders')}}
                                  </a>
                                  @endif
                                     
                                  @if($deliveryOrCntTelMod > 0)
                                  <a class="optionsAnchorPh button-glow-red" style="color:red !important;"  onclick="location.href = '/dashboardDelivery';">
                                    <i class="fas fa-border-all"></i> {{__('adminP.deliveryOrders')}}
                                  </a>
                                  @else
                                  <a class="optionsAnchorPh" onclick="location.href = '/dashboardDelivery';">
                                    <i class="fas fa-border-all"></i> {{__('adminP.deliveryOrders')}}
                                  </a>
                                  @endif

                                  </div>

                                  <a class="optionsAnchorPh" href="{{route('giftCard.giftCardMngAdmin')}}">
                                    <i class="fa-regular fa-credit-card"></i> Geschenkkarte 
                                  </a>

                                  <a class="optionsAnchorPh" href="{{route('dash.indexFreeTables')}}">
                                    <i class="fas fa-exclamation"></i> {{__('adminP.tables')}} 
                                  </a>
                                @endif
                  
                                @if($usersAccessTel->where('accessDsc','Empfohlen')->first() != NULL && $usersAccessTel->where('accessDsc','Empfohlen')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('dash.recom')}}">
                                    <i class="fas fa-band-aid"></i>  {{__('adminP.suggestedProducts')}}
                                  </a>
                                @endif

                                @if($usersAccessTel->where('accessDsc','Products')->first() != NULL && $usersAccessTel->where('accessDsc','Products')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('dash.indexConMng')}}">
                                    <i class="fas fa-sitemap"></i> {{__('adminP.products')}}
                                  </a>
                                @endif

                                @if($usersAccessTel->where('accessDsc','workersManagement')->first() != NULL && $usersAccessTel->where('accessDsc','workersManagement')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('admWoMng.indexAdmMngPage')}}">
                                    <i class="fas fa-users"></i> {{__('adminP.workers')}}
                                  </a>
                                @endif

                                @if(1 == 1)
                                  <a class="optionsAnchorPh" href="{{route('orServing.orderServingDevicesPage')}}">
                                    <i class="fa-solid fa-bell-concierge"></i> Servieren bestellen
                                  </a>
                                @endif

                                @if($usersAccessTel->where('accessDsc','Tabellenwechsel')->first() != NULL && $usersAccessTel->where('accessDsc','Tabellenwechsel')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('TabChngCli.indexAP')}}">
                                    <i class="fas fa-random"></i> {{__('adminP.changeTable')}}
                                  </a>
                                @endif

                                @if($usersAccessTel->where('accessDsc','Trinkgeld')->first() != NULL && $usersAccessTel->where('accessDsc','Trinkgeld')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('tips.index')}}">
                                    <i class="fas fa-coins"></i>  {{__('adminP.tip')}}
                                  </a> 
                                @endif   

                                @if($usersAccessTel->where('accessDsc','16+/18+')->first() != NULL && $usersAccessTel->where('accessDsc','16+/18+')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('restrictProd.index')}}">
                                    <i class="fas fa-ban"></i>  {{__('adminP.ageRestricion')}}
                                  </a>
                                @endif

                                @if($usersAccessTel->where('accessDsc','Gutscheincode')->first() != NULL && $usersAccessTel->where('accessDsc','Gutscheincode')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('cupons.index')}}">
                                    <i class="fas fa-ticket-alt"></i>  {{__('adminP.couponCode')}}
                                  </a>
                                @endif

                                @if($usersAccessTel->where('accessDsc','Takeaway')->first() != NULL && $usersAccessTel->where('accessDsc','Takeaway')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('takeaway.index')}}">
                                    <i class="fas fa-hotdog"></i>  {{__('adminP.takeaway')}}
                                  </a>
                                @endif
                                
                                @if($usersAccessTel->where('accessDsc','Delivery')->first() != NULL && $usersAccessTel->where('accessDsc','Delivery')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('delivery.index')}}">
                                    <i class="fas fa-truck"></i>  {{__('adminP.delivery')}}
                                  </a>
                                @endif

                                @if($usersAccessTel->where('accessDsc','Tischkapazität')->first() != NULL && $usersAccessTel->where('accessDsc','Tischkapazität')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('table.capacity')}}">
                                    <i class="fas fa-user-check"></i> {{__('adminP.tableCapacity')}}
                                  </a>
                                @endif

                                <a class="optionsAnchorPh" href="{{route('billTablet.index')}}">
                                  <i class="fa-solid fa-tablet-screen-button"></i> Quittungstablett
                                </a>
                                
                                @if($usersAccessTel->where('accessDsc','Tischreservierungen')->first() != NULL && $usersAccessTel->where('accessDsc','Tischreservierungen')->first()->accessValid == 1)
                                  <?php
                                    $newRezRequestsTel = TableReservation::where([['toRes', Auth::user()->sFor],['tableNr', 999999],['status',0]])->whereDate('dita', '>=', Carbon::today())->count();
                                  ?>
                                  @if($newRezRequestsTel > 0)
                                    <a class="optionsAnchorPh button-glow-red" style="color:red !important;" href="{{route('TableReservation.ADIndex')}}">
                                      <i class="fas fa-receipt"></i>  {{__('adminP.tableReservations')}}
                                    </a>
                                  @else
                                    <a class="optionsAnchorPh" href="{{route('TableReservation.ADIndex')}}">
                                      <i class="fas fa-receipt"></i>  {{__('adminP.tableReservations')}}
                                    </a>
                                  @endif
                                @endif

                                @if($usersAccessTel->where('accessDsc','Dienstleistungen')->first() != NULL && $usersAccessTel->where('accessDsc','Dienstleistungen')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('SerReqCli.APindex')}}">
                                    <i class="fas fa-book-medical"></i> {{__('adminP.services')}}
                                  </a>
                                @endif

                                @if($usersAccessTel->where('accessDsc','Covid-19')->first() != NULL && $usersAccessTel->where('accessDsc','Covid-19')->first()->accessValid == 1)
                                  <a class="optionsAnchorPh" href="{{route('dash.covidsTel')}}">
                                    <i class="fas fa-virus"></i>  Covid-19 {{__('adminP.contactForm')}}
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


<script>
  function setCheckIn(){
    $.ajax({
			url: '{{ route("chkInOut.checkInRegister") }}',
			method: 'post',
			data: {
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
        respo = $.trim(respo);
        respo2D = respo.split('||||');
				$("#setCheckInBtn").html(respo2D[0]);
				$("#setCheckInBtn").attr('style','width: 49%; background-color:rgba(39,190,175,0.6); border-radius:4px; margin:0.5%; font-weight:bold;');

        $('#setCheckOutBtn').attr('onclick','setCheckOut('+respo2D[1]+')');
			},
			error: (error) => { console.log(error); }
		});
  }

  function setCheckOut(chInReg){
    $.ajax({
			url: '{{ route("chkInOut.checkOutRegister") }}',
			method: 'post',
			data: {
        chInIns: chInReg,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
        respo = $.trim(respo);
        $('#checkInOutScc01').html('Sie haben erfolgreich eine Check-In/Out-Sitzung registriert, '+$("#setCheckInBtn").html()+' bis '+respo);
        $('#checkInOutScc01').show(100).delay(8000).hide(100);

        $('#setCheckInBtn').html('Einchecken');
        $("#setCheckInBtn").attr('style','width: 49%; border-radius:4px; margin:0.5%; font-weight:bold;');
        $('#setCheckInBtn').attr('onclick','setCheckIn()');
        $('#setCheckOutBtn').removeAttr('onclick');

        $("#checkInOutRepDiv").load(location.href+" #checkInOutRepDiv>*","");

			},
			error: (error) => { console.log(error); }
		});
  }
</script>