

<div id="soundsAllNWP"> </div>
    
            
                        <!-- newOrder Pusher -->
                        <script>
                          var intervalId = window.setInterval(function(){
                        
                            $.ajax({
                              url: '{{ route("notify.checkUnrespondetWaiter") }}',
                              method: 'post',
                              data: {
                                _token: '{{csrf_token()}}'
                              },
                              success: (res) => {
                                res = $.trim(res);
                                if(res != 'empty'){

                                  var arrNotifyAdm = res.split('-8-');
                                  $.each( arrNotifyAdm, function( index, value ) {

                                    var pageURL = $(location).attr("href");
                                    var pageURLEndPre = pageURL.split('?')[0];
                                    var pageURLEnd = pageURLEndPre.split('/')[3];
                                    var res2D = value.split('||');

                                    // ADMINPANEL Update 
                                    if(res2D[0] == 'AdminUpdateOrdersP'){
                                      if(pageURLEnd == 'admWoMngIndexWaiter' || pageURLEnd == 'admWoMngIndexWaiter?tabs' || pageURL.split('/')[4] == 'admWoMngIndexWaiter?tabs'){
                                        // test01082022
                                        $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')
                                        reloadTablePageTabOrders(res2D[1])
                                      }else{
                                        // $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                      }

                                    }else if(value == 'AdminUpdateOrdersPT'){
                                      if(pageURLEnd == 'admWoMngTakeawayWaiter'){
                                        // test01082022
                                        $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')
                                        // location.reload();
                                        $("#phoneTableAll").load(location.href+" #phoneTableAll"+">*","");
                                        $("#desktopTableAll").load(location.href+" #desktopTableAll"+">*","");
                                        $("#ChStatAll").load(location.href+" #ChStatAll"+">*","");
                                        $("#openOrderAllOther").load(location.href+" #openOrderAllOther"+">*","");
                                        $("#openOrderAllDone").load(location.href+" #openOrderAllDone"+">*","");

                                      }else{
                                        // $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                        $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                        $("#showModalServiceTel").load(location.href+" #showModalServiceTel>*","");
                                      }

                                    }else if(res2D[0] == 'Taborder' || res2D[0] == 'Order'){
                                      // Play the beep sound
                                      // new Audio('storage/sound/swiftBeep.m4r').play();
                                      // test01082022
                                      $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')

                                      if(pageURLEnd == 'admWoMngIndexWaiter' || pageURLEnd == 'admWoMngIndexWaiter?tabs' || pageURL.split('/')[4] == 'admWoMngIndexWaiter?tabs'){
                                        if(res2D[0] == 'Taborder'){
                                          reloadTablePageTabOrders(res2D[1]);
                                        }else{
                                          reloadTablePageTabOrders(res2D[1]);
                                        }
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $('#adminPAlertWindowTel').show(500);
                                      } 
                                  
                                    }else if(value == 'OrderTakeaway'){
                                      if(pageURLEnd == 'admWoMngTakeawayWaiter'){
                                        // test01082022
                                        $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')

                                        location.reload();
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                        $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                        $("#showModalServiceTel").load(location.href+" #showModalServiceTel>*","");
                                      }

                                    }else if(value == 'OrderDelivery'){
                                      if(pageURLEnd == 'admWoMngDeliveryWaiter'){
                                        // test01082022
                                        $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')

                                        location.reload();
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                        $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                        $("#showModalServiceTel").load(location.href+" #showModalServiceTel>*","");
                                      }

                                    }else if(value == 'AdminUpdateOrdersPD'){
                                      if(pageURLEnd == 'admWoMngDeliveryWaiter'){
                                        // test01082022
                                        $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')
                                        location.reload();
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                        $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                        $("#showModalServiceTel").load(location.href+" #showModalServiceTel>*","");
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
                                        $("#showBtnModalServiceTel").load(location.href+" #showBtnModalServiceTel>*","");
                                        $("#showRedBtnNewMsgTel").load(location.href+" #showRedBtnNewMsgTel>*","");
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
                                      // test01082022
                                      $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')

                                      if(pageURLEnd == 'adminWoWaiterCallsWaiter'){
                                        location.reload();
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                      }

                                    }else if(value == 'AdminUpdateTableCh'){
                                      if(pageURLEnd == 'adminWoTableChngReqWaiter'){
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
                                      reloadTablePageTabOrders(res2D[4]);
                                      
                                    }else if(res2D[0] == 'newGhostForAdm'){
                                      // 'newGhostForAdm||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                                      reloadTablePageTabOrders(res2D[2]);

                                    }else if(res2D[0] == 'productIsReadyRes'){
                                      // test01082022
                                      $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')

                                      reloadTablePageTabOrders(res2D[3]);

                                    }else if(res2D[0] == 'productIsReadyTa'){
                                      if(pageURLEnd == 'admWoMngTakeawayWaiter'){
                                        // test01082022
                                        $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')
                                        $("#taOrderRow"+res2D[1]).load(location.href+" #taOrderRow"+res2D[1]+">*","");
                                        $("#openOrderAllOther").load(location.href+" #openOrderAllOther"+">*","");
                                      }

                                    }else if(res2D[0] == 'productIsReadyDe'){
                                      if(pageURLEnd == 'admWoMngDeliveryWaiter'){
                                        // test01082022
                                        $("#soundsAllNWP").html('<audio id="soundsAllNWPAudio" src="storage/sound/swiftBeep.m4r" autoplay="true"></audio>')
                                        $("#deOrderRow"+res2D[1]).load(location.href+" #deOrderRow"+res2D[1]+">*","");
                                      }
                                      
                                    }else if(res2D[0] == 'clPayUnconfirmedAlert'){
                                      if($('#alertClWantsToPayTel').is(':hidden')){ 
                                        $('#alertClWantsToPayTNrTel').html(res2D[2]); 
                                        $('#alertClWantsToPayTel').show(50); 
                                      }

                                    }else if(res2D[0] == 'OpenCloseRestaurant'){
                                      $('#openCloseResModalBodyDiv1').html('<img src="storage/gifs/loading2.gif" style="width: 30%; margin-left:35%;" alt="">');
                                      $("#openCloseResModalBody").load(location.href+" #openCloseResModalBody>*","");
                                      
                                    }/*end tipet e njoftimeve*/
                                    
                                  });/*end foreach ne njoftime*/
                                }
                              },
                              error: (error) => { console.log(error);}
                            }); //End AJAX form 
                          }, 2500);
    
    
    
    
    
                          function reloadTablePageTabOrders(tNr){
                            if($('#tabOrder'+tNr).hasClass('show')){
                                $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                                $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                                $("#tabOrderTotPriceDiv"+tNr).load(location.href+" #tabOrderTotPriceDiv"+tNr+">*","");
                                $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");
                                $("#extraServicesOnTable"+tNr).load(location.href+" #extraServicesOnTable"+tNr+">*","");
                            }else{
                                $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                                $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");
                                $('#tableNeedsToReset'+tNr).val(1);
                            }
                            
                          }
    
    
    
                          function reloadPage01(tNr){
                            $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                            $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                            $("#tabOrderTotPriceDiv"+tNr).load(location.href+" #tabOrderTotPriceDiv"+tNr+">*","");
                            $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");
                            $("#extraServicesOnTable"+tNr).load(location.href+" #extraServicesOnTable"+tNr+">*","");
                          }
    
                          function reloadPage02(tNr){
                            $('.modal').modal('hide');
                            $('#tableIconGreen'+tNr).attr('src','storage/gifs/loading2.gif');
                            $("#tableStatDiv").load(location.href+" #tableStatDiv>*","");
                         
                            if($('#tabOrder'+tNr).length ){ 
                              $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*",""); 
                              $("#activeClientsOnTable"+tNr).load(location.href+" #activeClientsOnTable"+tNr+">*","");
                              $("#newProductOrPage2ModalActiveNumbers"+tNr).load(location.href+" #newProductOrPage2ModalActiveNumbers"+tNr+">*","");
                            }else{ $("#allTabOrderTNon").load(location.href+" #allTabOrderTNon>*",""); }
    
                            $("#topNavbarUlId1").load(location.href+" #topNavbarUlId1>*","");
    
                            if($('#tableModWStreetRaucher'+tNr).length ){ 
                              $("#tableModWStreetRaucher"+tNr).load(location.href+" #tableModWStreetRaucher"+tNr+">*","");
                            }else{
                              $("#tableModWStreetRaucherAll").load(location.href+" #tableModWStreetRaucherAll>*","");
                            }
                          }
    
                          function reloadPage03(tNr){
                            // tavolinat - ikonat D
                            $('#tableIconGreen'+tNr).attr('src','storage/gifs/loading2.gif');
                            $("#tableStatDiv").load(location.href+" #tableStatDiv>*","");
                            $("#topNavbarUlId1").load(location.href+" #topNavbarUlId1>*","");
                            
                            // modalet e porosive D
                            if($('#tabOrder'+tNr).length ){ 
                              $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*",""); 
                              $("#activeClientsOnTable"+tNr).load(location.href+" #activeClientsOnTable"+tNr+">*","");
                              $("#newProductOrPage2ModalActiveNumbers"+tNr).load(location.href+" #newProductOrPage2ModalActiveNumbers"+tNr+">*","");
                            }else{ $("#allTabOrderTNon").load(location.href+" #allTabOrderTNon>*",""); }
    
                            if($('#tableModWStreetRaucher'+tNr).length ){ 
                              $("#tableModWStreetRaucher"+tNr).load(location.href+" #tableModWStreetRaucher"+tNr+">*","");
                            }else{
                              $("#tableModWStreetRaucherAll").load(location.href+" #tableModWStreetRaucherAll>*","");
                            }
                          }
</script>