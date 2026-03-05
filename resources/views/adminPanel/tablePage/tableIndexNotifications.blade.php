

<div id="soundsAllNBAP"> </div>
    
            
                        <!-- newOrder Pusher -->
                        <script>
                          var intervalId = window.setInterval(function(){
    
                            $.ajax({
                              url: '{{ route("notify.checkUnrespondet") }}',
                              method: 'post',
                              data: {
                                resId: '{{Auth::user()->sFor}}',
                                _token: '{{csrf_token()}}'
                              },
                              success: (res) => {
                                res = $.trim(res);
                                if(res != 'empty'){
                                
                                  var arrNotifyAdm = res.split('-8-');
                                  $.each( arrNotifyAdm, function( index, value ) {
                                     
                                    // console.log(value);
                                    var pageURL = $(location).attr("href");
                                    var pageURLEndPre = pageURL.split('?')[0];
                                    var pageURLEnd = pageURLEndPre.split('/')[3];
                                    var res2D = value.split('||');
    
                                    if(res2D[0] == 'Taborder' || res2D[0] == 'Order'){
                                      $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
                                      if(pageURLEnd == 'dashboard' || pageURLEnd == 'dashboard?tabs' || pageURL.split('/')[4] == 'dashboard?tabs'){
                                          reloadTablePageTabOrders(res2D[1]);
                                        
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $('#adminPAlertWindowTel').show(500);
                                      } 
                                      
                                  
                                    }else if(value == 'OrderTakeaway'){
                                      console.log('OrderTakeaway');
                                      $("#ordersTAWaiting").load(location.href+" #ordersTAWaiting>*","");
                                      if(pageURLEnd == 'dashboardTakeaway'){
                                        // test01082022
                                        $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
    
                                        location.reload();
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                      }
    
                                    }else if(value == 'OrderDelivery'){
                                      if(pageURLEnd == 'dashboardDelivery'){
                                        // test01082022
                                        $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
                                        location.reload();
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                      }
    
                                    // ADMINPANEL Update 
                                    }else if(res2D[0] == 'AdminUpdateOrdersP'){
                                        $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
                                        if(pageURLEnd == 'dashboard' || pageURLEnd == 'dashboard?tabs' || pageURL.split('/')[4] == 'dashboard?tabs'){
                                            reloadTablePageTabOrders(res2D[1]);
                                        }else{
                                            $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        }
                                      
    
                                    }else if(value == 'AdminUpdateOrdersPT'){
                                      // $("#ordersTAWaiting").load(location.href+" #ordersTAWaiting>*","");
                                      if(pageURLEnd == 'dashboardTakeaway'){
                                        // test01082022
                                        $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
                                        // location.reload();
                                        if($("#desktopTable").length){
                                          $("#desktopTable").load(location.href+" #desktopTable"+">*","");
                                          $("#desktopTable2").load(location.href+" #desktopTable2"+">*","");
                                        }else{
                                          $("#desktopTableAll").load(location.href+" #desktopTableAll"+">*","");
                                        }
                                        $("#ChStatAll").load(location.href+" #ChStatAll"+">*","");
                                        $("#openOrderAllOther").load(location.href+" #openOrderAllOther"+">*","");
                                        $("#openOrderAllDone").load(location.href+" #openOrderAllDone"+">*","");
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                      }
                                      
    
                                    }else if(value == 'AdminUpdateOrdersPD'){
                                      if(pageURLEnd == 'dashboardDelivery'){
                                        // test01082022
                                        $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
                                        location.reload();
                                      }else{
                                        $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                                        $("#resExtraServices").load(location.href+" #resExtraServices>*","");
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
                                        $("#atsMsgOpenPageAdminPanel").load(location.href+" #atsMsgOpenPageAdminPanel>*","");
                                        // $("#resExtraServices").load(location.href+" #resExtraServices>*","");
                                      }
                                    }else if(value == 'newMsgFromAdminsAddAV' || value == 'newMsgFromAdminsDelAV'){
                                      if(pageURLEnd == 'AdminSaMSG'){
                                        location.reload();
                                      }
                                    }else if(res2D[0] == 'SaMsgReadForAdmin'){
                                      if(pageURLEnd == 'AdminSaMSG'){
                                        $("#messagesDiv"+res2D[1]).load(location.href+" #messagesDiv"+res2D[1]+">*","");
                                      }
                                      
    
                                    }else if(value == 'AdminUpdateTableCh'){
                                      if(pageURLEnd == 'ClTableChangeIndexAP'){
                                      // test01082022
                                      $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
    
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
    
                                        // Pergjigja , reMessage per DESKTOP
                                        $('#adminToClientMsgTableNr').html(res2D[1]);
                                        $('#adminToClientMsgQuestion').html(res2D[2]);
                                        $('#adminToClientMsganswer').html(res2D[3]);
                                        $('#adminToClientMsgClPhNr').html(res2D[4]);
                                        $("#clientToAdminMsgANS").attr('style','position:fixed; top:50px; width:75%; margin-left:12.5%; border-radius:15px; font-weight:bold; z-index:8; font-size:large; border:2px solid rgba(72,81,87,0.6); display:block !important;');
                                        $('#clientToAdminMsgANSTableNr').val(res2D[1]);
                                        $('#clientToAdminMsgANSClientPhNr').val(res2D[4]);
                                
                                    }else if(res2D[0] == 'tableChngReqAdminExecuted'){
                                      $('#tableChngReqAdminExecutedModalTitle').html( $('#yourRequestClient').val()+ ' '+res2D[3]+' ' +$('#fromtable').val()+ ' ' +res2D[1]+' '+$('#dessert').val()+ ' ' +res2D[2]+' ' +$('#moveWasExecuted').val());
                                      $('#tableChngReqAdminExecutedModal').modal('toggle');
    
                                      $('#tableChngReqAdminExecutedModalTitleTel').html($('#yourRequestClient').val()+ ' '+res2D[3]+' ' +$('#fromtable').val()+ ' '+res2D[1]+' '+$('#dessert').val()+ ' '+res2D[2]+' ' +$('#moveWasExecuted').val());
                                      $('#tableChngReqAdminExecutedModalTel').modal('toggle');
    
                                      reloadTablePageTabOrders(res2D[4]);
    
                                    }else if(res2D[0] == 'newGhostForAdm'){
                                      // 'newGhostForAdm||'.$oneNoti->data['id'].'||'.$oneNoti->data['tableNr'];
                                      reloadTablePageTabOrders(res2D[2]);
    
                                    }else if(res2D[0] == 'productIsReadyRes'){
                                      // test01082022
                                      $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
                                      reloadTablePageTabOrders(res2D[3]);
                                      $("#ordersTAWaiting").load(location.href+" #ordersTAWaiting>*","")
                                    
                                    }else if(res2D[0] == 'productIsReadyTa'){
                                      if(pageURLEnd == 'dashboardTakeaway'){
                                        // test01082022
                                        $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
                                        $("#taOrderRow"+res2D[1]).load(location.href+" #taOrderRow"+res2D[1]+">*","");
                                        $("#openOrderAllOther").load(location.href+" #openOrderAllOther"+">*","");
                                      }
                                      $("#ordersTAWaiting").load(location.href+" #ordersTAWaiting>*","")
                                    
                                    }else if(res2D[0] == 'productIsReadyDe'){
                                      if(pageURLEnd == 'dashboardDelivery'){
                                             // test01082022
                                          $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
    
                                        $("#deOrderRow"+res2D[1]).load(location.href+" #deOrderRow"+res2D[1]+">*","");
                                      }
                                      
                                    }else if(res2D[0] == 'clPayUnconfirmedAlert'){
                                      if($('#alertClWantsToPay').is(':hidden')){ 
                                        // test01082022
                                        $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>');
    
                                        $('#alertClWantsToPayTNr').html(res2D[2]); 
                                        $('#alertClWantsToPay').show(50); 
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