                    <div id="soundsAllNAP">

                    </div>
                  
                    <!-- newOrder Pusher -->
                    <script>


                      var intervalId = window.setInterval(function(){

                        $.ajax({
                          url: '{{ route("notify.checkUnrespondet") }}',
                          method: 'post',
                          data: {
                            resId: 'Auth::User()->sFor',
                            _token: '{{csrf_token()}}'
                          },
                          success: (res) => {
                            res = res.replace(/\s/g, '');
                            if(res != 'empty'){

                              var arrNotifyAdm = res.split('-8-');
                              $.each( arrNotifyAdm, function( index, value ) {
                                var pageURL = $(location).attr("href");
                                var pageURLEnd = pageURL.split('/')[3];
                                var res2D = value.split('||');

                                if(res2D[0] == 'Taborder' || res2D[0] == 'Order'){
                               
                                  // test01082022
                                  $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              
                                  
                                  if(pageURLEnd == 'dashboard' || pageURLEnd == 'dashboard?tabs' || pageURL.split('/')[4] == 'dashboard?tabs'){
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
                                  if(pageURLEnd == 'dashboardTakeaway'){
                                    // test01082022
                                      $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                    $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                    $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                    $("#showModalServiceTel").load(location.href+" #showModalServiceTel>*","");
                                  }

                                }else if(value == 'OrderDelivery'){
                                  if(pageURLEnd == 'dashboardDelivery'){
                                    // test01082022
                                      $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                    $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                    $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                    $("#showModalServiceTel").load(location.href+" #showModalServiceTel>*","");
                                  }

                                // ADMINPANEL Update 
                                }else if(value == 'AdminUpdateOrdersP'){
                                  if(pageURLEnd == 'dashboard' || pageURLEnd == 'dashboard?tabs' || pageURL.split('/')[4] == 'dashboard?tabs'){
                                    // test01082022
                                    $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              
                                    reloadPage03(res2D[1]);
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                  }

                                }else if(value == 'AdminUpdateOrdersPT'){

                                  if(pageURLEnd == 'dashboardTakeaway'){
                                    // test01082022
                                      $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                    $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                    $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                    $("#showModalServiceTel").load(location.href+" #showModalServiceTel>*","");
                                  }
                                }else if(value == 'AdminUpdateOrdersPD'){
                                  

                                  if(pageURLEnd == 'dashboardDelivery'){
                                    // test01082022
                                      $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                    $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                    $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                    $("#showModalServiceTel").load(location.href+" #showModalServiceTel>*","");
                                  }

                                }else if(res2D[0] == 'newMsgFromQRorpa'){
                                  if(pageURLEnd == 'AdminSaMSG'){
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
                                    $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                    $("#showRedBtnNewMsgTel").load(location.href+" #showRedBtnNewMsgTel>*","");
                                  }
                                }else if(value == 'newMsgFromAdminsAddAV' || value == 'newMsgFromAdminsDelAV'){
                                  if(pageURLEnd == 'AdminSaMSG'){
                                    location.reload();
                                  }
                                }else if(res2D[0] == 'SaMsgReadForAdmin'){
                                  if(pageURLEnd == 'AdminSaMSG'){
                                    $("#messagesDiv"+res2D[1]).load(location.href+" #messagesDiv"+res2D[1]+">*","");
                                  }

                                }else if(value == 'AdminUpdateWaiterCall'){
                                  // test01082022
                                    $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              

                                  if(pageURLEnd == 'callWaiterIndex'){
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                  }

                                }else if(value == 'AdminUpdateTableCh'){
                                  if(pageURLEnd == 'ClTableChangeIndexAP'){
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                  }
                                }else if(value == 'AdminUpdateTableRez'){
                                  if(pageURLEnd == 'tabReserAdminIndex'){
                                    location.reload();
                                  }else{
                                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                  }
                                
                                // ADMIN PANEL MESSAGE
                                }else if(res2D[0] == 'ClientToAdminMessage'){
                                    $('.modal').modal('hide');
                             
                                    // Pergjigja , reMessage per SMARTPHONE
                                    $('#adminToClientMsgTableNrTel').html(res2D[1]);
                                    $('#adminToClientMsgQuestionTel').html(res2D[2]);
                                    $('#adminToClientMsganswerTel').html(res2D[3]);
                                    $('#adminToClientMsgClPhNrTel').html(res2D[4]);
                                    $("#clientToAdminMsgANSTel").attr('style','position:fixed; top:50px; width:85%; margin-left:7.5%; border-radius:15px; font-weight:bold; z-index:8; font-size:large; border:1px solid rgba(72,81,87,0.6); display:block !important;');
                                    $('#clientToAdminMsgANSTableNrTel').val(res2D[1]);
                                    $('#clientToAdminMsgANSClientPhNrTel').val(res2D[4]);
                                    
                                }else if(res2D[0] == 'tableChngReqAdminExecuted'){
                                  $('#tableChngReqAdminExecutedModalTitleTel').html($('#yourRequestClient').val()+ ' '+res2D[3]+' ' +$('#fromtable').val()+ ' '+res2D[1]+' '+$('#dessert').val()+ ' '+res2D[2]+' ' +$('#moveWasExecuted').val());
                                  $('#tableChngReqAdminExecutedModalTel').modal('toggle');
                                }else if(res2D[0] == 'newGhostForAdm'){
                                  // 'newGhostForAdm||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                                  reloadPage01(res2D[2]);

                                }else if(res2D[0] == 'productIsReadyRes'){
                                  // test01082022
                                    $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              

                                  reloadPage01(res2D[3]);

                                }else if(res2D[0] == 'productIsReadyTa'){
                                  if(pageURLEnd == 'dashboardTakeaway'){
                                    // test01082022
                                      $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              
                                    $("#taOrderRow"+res2D[1]).load(location.href+" #taOrderRow"+res2D[1]+">*","");
                                  }

                                }else if(res2D[0] == 'productIsReadyDe'){
                                  if(pageURLEnd == 'dashboardDelivery'){
                                    // test01082022
                                      $("#soundsAllNAP").html('<audio id="soundsAllNAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   ');
              

                                    $("#deOrderRow"+res2D[1]).load(location.href+" #deOrderRow"+res2D[1]+">*","");
                                  }
                                  
                                }else if(res2D[0] == 'clPayUnconfirmedAlert'){
                                  if($('#alertClWantsToPayTel').is(':hidden')){ 
                                    $('#alertClWantsToPayTNrTel').html(res2D[2]); 
                                    $('#alertClWantsToPayTel').show(50); 
                                  }

                                }/*end tipet e njoftimeve*/

                              });/*end foreach ne njoftime*/
                            }
                          },
                          error: (error) => { console.log(error);}
                        }); //End AJAX form 
                      }, 2000);














                      







                      function reloadPage01(tNr){
    
                        // SMARTPHONE
                        $('#tableO'+tNr).attr('style',"background-image: url('../storage/gifs/loading2.gif');  background-repeat: no-repeat; background-size:contain; width:17.5%;");
                        // modalet e porosive S
                        if($('#tabOrderTel'+tNr).length){
                          $("#tabOrderBodyTel"+tNr).load(location.href+" #tabOrderBodyTel"+tNr+">*","");
                          $("#activeClientsOnTableTel"+tNr).load(location.href+" #activeClientsOnTableTel"+tNr+">*","");
                          $("#newProductOrPage2ModalActiveNumbersTel"+tNr).load(location.href+" #newProductOrPage2ModalActiveNumbersTel"+tNr+">*","");
                        }else{ $("#allTabOrderTNonTel").load(location.href+" #allTabOrderTNonTel>*",""); }
                        // tavolinat - ikonat S
                        $("#tableCapRezClTel").load(location.href+" #tableCapRezClTel>*","");

                        $('#oraTel').html('neue Bestellung');
                        $('#dataTel').html('T: '+tNr);
                        clearInterval(setTimeTel);
                        setTimeout( function(){ setInterval(showTime, 1000); }  , 3000 );
                      }

                      function reloadPage02(tNr){

                        // Smartphone
                        $('.modal').modal('hide');
                        $("#allTabOrderTNonTel").load(location.href+" #allTabOrderTNonTel>*","");
                        $("#tableCapRezClTel").load(location.href+" #tableCapRezClTel>*","");
                        $("#ChStatStreetRaucherAllTel").load(location.href+" #ChStatStreetRaucherAllTel>*","");
                        $("#tabOrderTel"+tNr).load(location.href+" #tabOrderTel"+tNr+">*","");
                        $('#tableO'+tNr).attr('style',"background-image: url('../storage/gifs/loading2.gif');  background-repeat: no-repeat; background-size:contain; width:17.5%;");
                        $("#ChStatStreetRaucherAllTel2").load(location.href+" #ChStatStreetRaucherAllTel2>*","");
                        
                      }

                      function reloadPage03(tNr){

                        // SMARTPHONE
                        $("#ChStatStreetRaucherAllTel2").load(location.href+" #ChStatStreetRaucherAllTel2>*","");
                        $("#ChStatStreetRaucherAllTel").load(location.href+" #ChStatStreetRaucherAllTel>*","");
                        $('#tableO'+tNr).attr('style',"background-image: url('../storage/gifs/loading2.gif');  background-repeat: no-repeat; background-size:contain; width:17.5%;");
                        // modalet e porosive S
                        if($('#tabOrderTel'+tNr).length){
                          $("#tabOrderBodyTel"+tNr).load(location.href+" #tabOrderBodyTel"+tNr+">*","");
                          $("#activeClientsOnTableTel"+tNr).load(location.href+" #activeClientsOnTableTel"+tNr+">*","");
                          $("#newProductOrPage2ModalActiveNumbersTel"+tNr).load(location.href+" #newProductOrPage2ModalActiveNumbersTel"+tNr+">*","");
                        }else{ $("#allTabOrderTNonTel").load(location.href+" #allTabOrderTNonTel>*",""); }
                        // tavolinat - ikonat S
                        $("#tableCapRezClTel").load(location.href+" #tableCapRezClTel>*","");
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
                                          ' <i style="width:15%; color:rgb(39,190,175);" class="fas text-center pt-1 fa-check-double"></i> <!-- read -->'+
                                        '</div>'+
                                      '</div>';

                        $("#messagesDiv"+avID).append(newMsg);
                        $('#messageInputFor'+avID).val('');
                        objDiv = document.getElementById("messagesDiv"+avID);
                        objDiv.scrollTop = objDiv.scrollHeight;
                      }


                      $('#btnBeepN').click(function(){
                        var audio = new Audio("{{ asset('storage/sound/swiftBeep.mp3')}}");
                        audio.play();
                      });

                      
</script>