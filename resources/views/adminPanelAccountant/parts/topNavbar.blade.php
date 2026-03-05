<?php
    use App\Restorant;
    use App\Orders;
    use Carbon\Carbon;

    $thisRestaurantId = Auth::user()->sFor;

    echo '<input type="hidden" id="thisResId" value="'.Auth::user()->sFor.'">';

    $nowDate = date('Y-m-d');

    $ordersLi = Orders::where('Restaurant', '=', $thisRestaurantId)->where([['statusi', '=', 0],['nrTable','!=',500],['nrTable','!=',9000]])
    ->whereDate('created_at', Carbon::today())->whereIn('nrTable',$myTablesWaiter)->get()->count();
?>
<input type="hidden" id="theResAdminId" value="{{$thisRestaurantId}}">
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

<div id="addTypePlease" style="top: 12%; width: 100%; position: fixed; z-index: 100000; display: none;">
    <div class="text-center" style="background-color:rgb(39, 190, 175); color:white;  padding-top:15px; padding-bottom:15px; border-radius:9px;">
        Bitte Typ auswählen!
    </div>
</div>


<nav class="navbar navbar-expand navbar-light bg-white topbar " id="DashNavbar">

<!-- The Modal -->
<div class="modal" id="notifications" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('adminP.orderOnHold')}}...</h4>
                <button type="button" class="close" data-dismiss="modal">X</button>
            </div>
 
      <!-- Modal body -->
      <div class="modal-body">
        @foreach(Orders::where('Restaurant', '=', $thisRestaurantId)->where('statusi', '=', 0)->whereIn('nrTable',$myTablesWaiter)->get()->sortByDesc('created_at') as $order)
          @if($nowDate == explode(' ', $order->created_at)[0])
            <p class="p-3" style="border-bottom:1px solid lightgray; font-size:21px;">-> {{count(explode('---8---',$order->porosia))}} {{__('adminP.productsTable')}}: <strong>{{$order->nrTable}} </strong></p>
          @endif
        @endforeach
      </div>


    </div>
  </div>
</div> 
          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

         
          <ul class="navbar-nav mr-auto">
            <li class="mt-3 ml-3">
                <p style="font-size:21px;" class="color-qrorpa"><strong>{{Restorant::find($thisRestaurantId)->emri}}</strong></p>
              </li>
          </ul>

          <!-- Topbar Navbar -->


          <ul class="navbar-nav ml-auto" id="topNavbarUlId1">
           



          <div class="alert alert-success" id="youHave" style="z-index:500; display:none;">
            {{__('adminP.youHaveNewOrder')}}
          </div>



           
            <!-- Nav Item - Alerts -->
            <li class="nav-item  mx-1 mt-3">
             
                <!-- <i class="fas fa-bell fa-fw"></i> -->
                <!-- Counter - Alerts -->
                
                <span class="badge badge-default badge-counter mt-3 mr-4 openNotifications" data-toggle="modal" data-target="#notifications"
                  style="z-index:2000px; width:45px; height:35px; font-size:20px;">
                 <img class="w-100" src="https://img.icons8.com/carbon-copy/100/000000/bell.png"/>
                    <sup id="nrNot">
                      <?php
                          $waiting = 0;
                          
                          foreach(Orders::where('Restaurant', '=', $thisRestaurantId)->where('statusi', '=', 0)->whereIn('nrTable',$myTablesWaiter)->get() as $order){
                              $orderDate = explode(' ', $order->created_at);
                              if($nowDate == $orderDate[0]){
                                $waiting++;
                              }
                          }
                          echo $waiting;
                      ?>
                    </sup>
                </span>



















              
                <div id="soundsAllNBKP">
                
                </div>

                @if(Auth::check())
                  @if(Auth::user()->role == 53)
                   
                    <!-- newOrder Pusher -->
                    <script>
                      var intervalId = window.setInterval(function(){

                        $.ajax({
                          url: '{{ route("notify.checkUnrespondetWaiter") }}',
                          method: 'post',
                          data: {
                            resId: $('#theResAdminId').val(),
                            _token: '{{csrf_token()}}'
                          },
                          success: (res) => {
                            res = res.replace(/\s/g, '');
                            if(res != 'empty'){

                              var arrNotifyAdm = res.split('-8-');
                              var pageURL = $(location).attr("href");
                              var pageURLEnd = pageURL.split('/')[3];

                              $.each( arrNotifyAdm, function( index, value ) {
                                var res2D = value.split('||');
                                if(res2D[0] == 'Taborder' || res2D[0] == 'Order'){
                                  // Play the beep sound
                                  // new Audio('storage/sound/swiftBeep.mp3').play();

                                  // test01082022
                                  $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')

                                  if(pageURLEnd == 'admWoMngIndexWaiter' || pageURLEnd == 'admWoMngIndexWaiter?tabs' || pageURL.split('/')[4] == 'admWoMngIndexWaiter?tabs'){
                                    if(res2D[0] == 'Taborder'){
                                      reloadPage01(res2D[1]);
                                    }else{
                                      reloadPage02(res2D[1]);
                                    }
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                    $('#adminPAlertWindow').show(500);
                                    $('#adminPAlertWindowTel').show(500);
                                  } 
                              
                                }else if(value == 'OrderTakeaway'){
                                  if(pageURLEnd == 'admWoMngTakeawayWaiter'){
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                    $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                  }

                                }else if(value == 'OrderDelivery'){
                                  if(pageURLEnd == 'admWoMngDeliveryWaiter'){
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                    $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                  }

                                // ADMINPANEL Update 
                                }else if(value == 'AdminUpdateOrdersP'){
                                  if(pageURLEnd == 'admWoMngIndexWaiter' || pageURLEnd == 'admWoMngIndexWaiter?tabs' || pageURL.split('/')[4] == 'admWoMngIndexWaiter?tabs'){
                                    // test01082022
                                    $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')
                                    reloadPage03(res2D[1]);
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                  }

                                }else if(value == 'AdminUpdateOrdersPT'){
                                  if(pageURLEnd == 'admWoMngTakeawayWaiter'){
                                    // test01082022
                                    $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                    $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                  }

                                }else if(value == 'AdminUpdateOrdersPD'){
                                  if(pageURLEnd == 'admWoMngDeliveryWaiter'){
                                    // test01082022
                                    $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                    $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                  }

                                }else if(res2D[0] == 'newMsgFromQRorpa'){
                                  if(pageURLEnd == 'adminWoSaMsgWaiter'){
                                    newMessageFromQRorpa(res2D[1], res2D[2], res2D[3]);

                                    $.ajax({
                                      url: '{{ route("atsMsg.AdmIReadTheMsg") }}',
                                      method: 'post',
                                      data: {
                                        msgId: res2D[4],
                                        _token: '{{csrf_token()}}'
                                      },
                                      success: () => {},
                                      error: (error) => {console.log(error);}
                                    });
                                    
                                  }else{
                                    $("#atsMsgOpenPageAdminPanel").load(location.href+" #atsMsgOpenPageAdminPanel>*","");
                                    // $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                  }
                                }else if(value == 'newMsgFromAdminsAddAV' || value == 'newMsgFromAdminsDelAV'){
                                  if(pageURLEnd == 'adminWoSaMsgWaiter'){
                                    location.reload();
                                  }
                                }else if(res2D[0] == 'SaMsgReadForAdmin'){
                                  if(pageURLEnd == 'adminWoSaMsgWaiter'){
                                    $("#messagesDiv"+res2D[1]).load(location.href+" #messagesDiv"+res2D[1]+">*","");
                                  }

                                }else if(value == 'AdminUpdateWaiterCall'){
                                  if(pageURLEnd == 'adminWoWaiterCallsWaiter'){
                                    // test01082022
                                    $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                  }

                                }else if(value == 'AdminUpdateTableCh'){
                                  if(pageURLEnd == 'adminWoTableChngReqWaiter'){
                                    // test01082022
                                    $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                  }
                                }else if(value == 'AdminUpdateTableRez'){
                                  if(pageURLEnd == 'adminWoTableReservationIndexWaiter'){
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                  }
                                
                                // ADMIN PANEL MESSAGE
                                }else if(res2D[0] == 'ClientToAdminMessage'){
                                    $('.modal').modal('hide');
                                    if(screen.width > 580){
                                      // Pergjigja , reMessage per DESKTOP
                                      $('#adminToClientMsgTableNr').html(res2D[1]);
                                      $('#adminToClientMsgQuestion').html(res2D[2]);
                                      $('#adminToClientMsganswer').html(res2D[3]);
                                      $('#adminToClientMsgClPhNr').html(res2D[4]);
                                      $("#clientToAdminMsgANS").attr('style','position:fixed; top:50px; width:75%; margin-left:12.5%; border-radius:15px; font-weight:bold; z-index:8; font-size:large; border:2px solid rgba(72,81,87,0.6); display:block !important;');
                                      $('#clientToAdminMsgANSTableNr').val(res2D[1]);
                                      $('#clientToAdminMsgANSClientPhNr').val(res2D[4]);
                                    }else{
                                      // Pergjigja , reMessage per SMARTPHONE
                                      $('#adminToClientMsgTableNrTel').html(res2D[1]);
                                      $('#adminToClientMsgQuestionTel').html(res2D[2]);
                                      $('#adminToClientMsganswerTel').html(res2D[3]);
                                      $('#adminToClientMsgClPhNrTel').html(res2D[4]);
                                      $("#clientToAdminMsgANSTel").attr('style','position:fixed; top:50px; width:85%; margin-left:7.5%; border-radius:15px; font-weight:bold; z-index:8; font-size:large; border:1px solid rgba(72,81,87,0.6); display:block !important;');
                                      $('#clientToAdminMsgANSTableNrTel').val(res2D[1]);
                                      $('#clientToAdminMsgANSClientPhNrTel').val(res2D[4]);
                                    }

                                }else if(res2D[0] == 'tableChngReqAdminExecuted'){
                                  $('#tableChngReqAdminExecutedModalTitle').html( $('#yourRequestClient').val()+ ' '+res2D[3]+' ' +$('#fromtable').val()+ ' ' +res2D[1]+' '+$('#dessert').val()+ ' ' +res2D[2]+' ' +$('#moveWasExecuted').val());
                                  $('#tableChngReqAdminExecutedModal').modal('toggle');

                                  $('#tableChngReqAdminExecutedModalTitleTel').html($('#yourRequestClient').val()+ ' '+res2D[3]+' ' +$('#fromtable').val()+ ' '+res2D[1]+' '+$('#dessert').val()+ ' '+res2D[2]+' ' +$('#moveWasExecuted').val());
                                  $('#tableChngReqAdminExecutedModalTel').modal('toggle');

                                }else if(res2D[0] == 'newGhostForAdm'){
                                  // 'newGhostForAdm||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                                  reloadPage01(res2D[2]);
                                }else if(res2D[0] == 'productIsReadyRes'){
                                  // test01082022
                                  $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')
                                  reloadPage01(res2D[3]);

                                }else if(res2D[0] == 'productIsReadyTa'){
                                  if(pageURLEnd == 'admWoMngTakeawayWaiter'){
                                    // test01082022
                                    $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')
                                    $("#taOrderRow"+res2D[1]).load(location.href+" #taOrderRow"+res2D[1]+">*","");
                                  }

                                }else if(res2D[0] == 'productIsReadyDe'){
                                  if(pageURLEnd == 'admWoMngDeliveryWaiter'){
                                    // test01082022
                                    $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')
                                    $("#deOrderRow"+res2D[1]).load(location.href+" #deOrderRow"+res2D[1]+">*","");
                                  }
                                  
                                }else if(res2D[0] == 'clPayUnconfirmedAlert'){
                                  
                                  if($('#alertClWantsToPay').is(':hidden')){ 
                                    // test01082022
                                    $("#soundsAllNBKP").html('<audio id="soundsAllNBKPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>')
                                    
                                    $('#alertClWantsToPayTNr').html(res2D[2]); 
                                    $('#alertClWantsToPay').show(50); 
                                  }

                                }/*end tipet e njoftimeve*/

                              });/*end foreach ne njoftime*/
                            }
                          },
                          error: (error) => { console.log(error);}
                        }); //End AJAX form 
                      }, 2000);








                      function reloadPage01(tNr){
                        // DESKTOP 
                        $('#tableIconGreen'+tNr).attr('src','storage/gifs/loading2.gif');
                        // modalet e porosive D
                        if($('#tabOrder'+tNr).length ){ 
                          $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                          $("#activeClientsOnTable"+tNr).load(location.href+" #activeClientsOnTable"+tNr+">*","");
                          $("#newProductOrPage2ModalActiveNumbers"+tNr).load(location.href+" #newProductOrPage2ModalActiveNumbers"+tNr+">*","");
                        }else{ $("#allTabOrderTNon").load(location.href+" #allTabOrderTNon>*",""); }
                        // tavolinat - ikonat D
                        $("#tableStatDiv").load(location.href+" #tableStatDiv>*","");
                     
                        // SMARTPHONE
                        // $('#tableO'+tNr).attr('style',"background-image: url('../storage/gifs/loading2.gif');  background-repeat: no-repeat; background-size:contain; width:17.5%;");
                        // // modalet e porosive S
                        // if($('#tabOrderTel'+tNr).length){
                        //   $("#tabOrderBodyTel"+tNr).load(location.href+" #tabOrderBodyTel"+tNr+">*","");
                        //   $("#activeClientsOnTableTel"+tNr).load(location.href+" #activeClientsOnTableTel"+tNr+">*","");
                        //   $("#newProductOrPage2ModalActiveNumbersTel"+tNr).load(location.href+" #newProductOrPage2ModalActiveNumbersTel"+tNr+">*","");
                        // }else{ $("#allTabOrderTNonTel").load(location.href+" #allTabOrderTNonTel>*",""); }
                        // // tavolinat - ikonat S
                        // $("#tableCapRezClTel").load(location.href+" #tableCapRezClTel>*","");

                        // $('#oraTel').html('neue Bestellung');
                        // $('#dataTel').html('T: '+tNr);
                        // // clearInterval(setTimeTel);
                        // setTimeout( function(){ setInterval(showTime, 1000); }  , 3000 );
                      }

                      function reloadPage02(tNr){
                        // Desktop
                        $('.modal').modal('hide');
                        $("#allTabOrderTNon").load(location.href+" #allTabOrderTNon>*","");
                        $("#ChStatStreetRaucherAll").load(location.href+" #ChStatStreetRaucherAll>*","");
                        $("#tableModWStreetRaucherAll").load(location.href+" #tableModWStreetRaucherAll>*","");
                        $('#tableIconGreen'+tNr).attr('src','storage/gifs/loading2.gif');
                        $("#tableStatDiv").load(location.href+" #tableStatDiv>*","");
                        $("#topNavbarUlId1").load(location.href+" #topNavbarUlId1>*","");
                        $("#tableModWStreetRaucher"+tNr).load(location.href+" #tableModWStreetRaucher"+tNr+">*","");
                        

                        // Smartphone
                        // $("#tableCapRezClTel").load(location.href+" #tableCapRezClTel>*","");
                        // $("#ChStatStreetRaucherAllTel").load(location.href+" #ChStatStreetRaucherAllTel>*","");
                        // $("#tabOrderTel"+tNr).load(location.href+" #tabOrderTel"+tNr+">*","");
                        // $('#tableO'+tNr).attr('style',"background-image: url('../storage/gifs/loading2.gif');  background-repeat: no-repeat; background-size:contain; width:17.5%;");
                        // $("#ChStatStreetRaucherAllTel2").load(location.href+" #ChStatStreetRaucherAllTel2>*","");
                      }

                      function reloadPage03(tNr){
                        // DESKTOP 
                        $("#ChStatStreetRaucherAll").load(location.href+" #ChStatStreetRaucherAll>*","");
                        $("#tableModWStreetRaucherAll").load(location.href+" #tableModWStreetRaucherAll>*","");
                        $('#tableIconGreen'+tNr).attr('src','storage/gifs/loading2.gif');
                        // modalet e porosive D
                        if($('#tabOrder'+tNr).length ){ 
                          $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*",""); 
                          $("#activeClientsOnTable"+tNr).load(location.href+" #activeClientsOnTable"+tNr+">*","");
                          $("#newProductOrPage2ModalActiveNumbers"+tNr).load(location.href+" #newProductOrPage2ModalActiveNumbers"+tNr+">*","");
                        }else{ $("#allTabOrderTNon").load(location.href+" #allTabOrderTNon>*",""); }
                        // tavolinat - ikonat D
                        $("#tableStatDiv").load(location.href+" #tableStatDiv>*","");
                        $("#topNavbarUlId1").load(location.href+" #topNavbarUlId1>*","");
                        $("#tableModWStreetRaucher"+tNr).load(location.href+" #tableModWStreetRaucher"+tNr+">*","");

                        // SMARTPHONE
                        // $("#ChStatStreetRaucherAllTel2").load(location.href+" #ChStatStreetRaucherAllTel2>*","");
                        // $("#ChStatStreetRaucherAllTel").load(location.href+" #ChStatStreetRaucherAllTel>*","");
                        // $('#tableO'+tNr).attr('style',"background-image: url('../storage/gifs/loading2.gif');  background-repeat: no-repeat; background-size:contain; width:17.5%;");
                        // // modalet e porosive S
                        // if($('#tabOrderTel'+tNr).length){
                        //   $("#tabOrderBodyTel"+tNr).load(location.href+" #tabOrderBodyTel"+tNr+">*","");
                        //   $("#activeClientsOnTableTel"+tNr).load(location.href+" #activeClientsOnTableTel"+tNr+">*","");
                        //   $("#newProductOrPage2ModalActiveNumbersTel"+tNr).load(location.href+" #newProductOrPage2ModalActiveNumbersTel"+tNr+">*","");
                        // }else{ $("#allTabOrderTNonTel").load(location.href+" #allTabOrderTNonTel>*",""); }
                        // // tavolinat - ikonat S
                        // $("#tableCapRezClTel").load(location.href+" #tableCapRezClTel>*","");
                      }

                      function newMessageFromQRorpa(avID, theMsg, msgCreated){

                        var respoTime = msgCreated.split(' ')[1];
                        var respoTime2D = respoTime.split(':');

                        var respoDate = msgCreated.split(' ')[0];
                        var respoDate2D = respoDate.split('-');

                        var newMsg =  '<div class="p-1 mb-2 leftMsg" style="width:70%; height:fit-content; margin-right:30%; color:white; background-color:rgba(32,44,51,255);">'+
                                        '<p class="mb-1">'+theMsg+'</p>'+
                                        '<div class="d-flex">'+
                                          '<p class="mb-1" style="color:rgba(255, 255, 255, 0.45); width:45%;">'+respoTime2D[0]+':'+respoTime2D[1]+':'+respoTime2D[2]+'</p>'+
                                          '<p class="mb-1 text-center" style="color:rgba(255, 255, 255, 0.45); width:40%;">'+respoDate2D[2]+'/'+respoDate2D[1]+'</p>'+
                                          '<i style="width:15%; color:rgb(39,190,175);" class="fas text-center pt-1 fa-check-double"></i> <!-- read -->'+
                                        '</div>'+
                                      '</div>';

                        $("#messagesDiv"+avID).append(newMsg);
                        $('#messageInputFor'+avID).val('');
                        objDiv = document.getElementById("messagesDiv"+avID);
                        objDiv.scrollTop = objDiv.scrollHeight;
                      }
                    </script>
                  @endif
                @endif

                <!-- <div class="modal show" id="tabOrder18" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); padding-top: 5%; padding-right: 17px; display: block; padding-left: 17px;" aria-modal="true"> -->

              

             
























                

              <!-- </a> -->
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  {{__('adminP.notificationCenter')}}
                </h6>
                <ul id="notifications">
                  <?php
                    if($waiting > 0){
                      echo '<li>'.__("adminP.theyHaveNewOrders").'</li>';
                    }else{
                      echo '<li>'.__("adminP.theyHaveNoNewOrders").'</li>';
                    }
                  ?>
                  
                 
                </ul>
              </div>
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



        <!-- PUSHER / call waiter -->
        <!-- <script>
          var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {cluster: 'eu'});
          var channel = pusher.subscribe('WaiterChanel');
          channel.bind('App\\Events\\CallWaiter', function(data) {
              window.location = window.location
          });
        </script> -->







              <script>
                //  clNewTab topNavBar PUSHER

                function closeMSGCA(){
                  $("#clientToAdminMsgANS").load(location.href+" #clientToAdminMsgANS>*","");
                  $("#clientToAdminMsgANS").hide(300);
                }

                function sendMsgToClFresh(res){
                  if($('#clientToAdminMsgANSMsgAgain').val().length != 0){
                    $.ajax({
                      url: '{{ route("TabChngCli.MsgToUser") }}',
                      method: 'post',
                      data: {
                          tableNr: $('#clientToAdminMsgANSTableNr').val(),
                          res: res,
                          msg: $('#clientToAdminMsgANSMsgAgain').val(),
                          clSelected : $('#clientToAdminMsgANSClientPhNr').val(),
                          _token: '{{csrf_token()}}'
                      },
                      success: () => {
                          $('#clientToAdminMsgANSAlertE').show(1).delay(3000).hide(1);

                          $("#clientToAdminMsgANS").load(location.href+" #clientToAdminMsgANS>*","");
                          $("#clientToAdminMsgANS").hide(300);
                      },
                      error: (error) => {
                          console.log(error);
                          alert($('#pleaseUpdateAndTryAgain').val());
                      }
                    });
                  }else{
                    $('#clientToAdminMsgANSAlertE').show(1).delay(3000).hide(1);
                  }
                }

          
              </script>


                  <div id="clientToAdminMsgANS" class="alert alert-info p-4" style="position:fixed; top:70px; width:75%; margin-left:12.5%; border-radius:15px; font-weight:bold; z-index:8; font-size:large; border:2px solid rgba(72,81,87,0.6); display:none !important; " >
                    <p style="font-weight:bold; color:rgb(39, 190, 175);" class="text-center">{{__('adminP.responseFromCustomers')}}</p>

                    <p style="font-weight:bold; color:rgb(72,81,87);" class="text-center"> {{__('adminP.table')}} : <span id="adminToClientMsgTableNr"></span> </p>
                    <p style="font-weight:bold; color:rgb(72,81,87);" class="text-center"> {{__('adminP.table')}} : <span id="adminToClientMsgClPhNr"></span> </p>
                    
                    <p style="font-weight:bold; color:rgb(39, 190, 175);" class="text-center"> Frage : 
                      <span style="color:rgb(72,81,87);" id="adminToClientMsgQuestion"></span> 
                    </p>
                    <p style="font-weight:bold; color:rgb(39, 190, 175);" class="text-center"> {{__('adminP.customersReply')}} : 
                      <span style="color:rgb(72,81,87);" id="adminToClientMsganswer"></span> 
                    </p>
                    <button onclick="closeMSGCA()" style="width:100%;" class="btn btn-danger">{{__('adminP.conclude')}}</button>


                      <p class="mt-3" style="color:rgb(72,81,87)"><strong>{{__('adminP.sendAnotherMessage')}}:</strong></p>
                      <textarea id="clientToAdminMsgANSMsgAgain" name="clientToAdminMsgANSMsgAgain" style="width: 100%;" placeholder="{{__('adminP.newMessage')}}" class="form-control p-3" rows="3"></textarea>
                      <p id="clientToAdminMsgANSAlertS" class="mt-2 alert alert-success textcenter" style="width:100%;; display:none;">{{__('adminP.messageSent')}}</p>
                      <p id="clientToAdminMsgANSAlertE" class="mt-2 alert alert-danger textcenter" style=" width:100%; display:none;">{{__('adminP.writeValidMessage')}}</p>
                      <button style="width:100%;" class="btn btn-success" onclick="sendMsgToClFresh('{{auth()->user()->sFor}}')">{{__('adminP.send')}}</button>
                    
                      <input type="hidden" id="clientToAdminMsgANSTableNr">
                      <input type="hidden" id="clientToAdminMsgANSClientPhNr">
                  </div>
                

   