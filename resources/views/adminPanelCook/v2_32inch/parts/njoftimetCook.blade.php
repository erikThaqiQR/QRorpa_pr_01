
<!-- newOrder Pusher -->
<div id="soundsAllCookDes">
    <!-- <audio id="soundsAllCookDesBeep" src="{{ asset('storage/sound/cookPanelNoty.mp3')}}" type="audio/mpeg" autoplay="true"></audio> -->
    <!-- <source id="newRing" src="{{ asset('storage/sound/swiftBeep.mp3')}}"> -->
</div>

<script>
    var intervalId = window.setInterval(function(){

        $.ajax({
            url: '{{ route("notify.checkUnrespondetCook") }}',
            method: 'post',
            data: {
                _token: '{{csrf_token()}}'
            },success: (respo) => {
               
                respo = $.trim(respo);
              
                if(respo != 'empty'){

                    var arrNotifyAdm = respo.split('-8-');
                    var pageURL = $(location).attr("href");
                    var pageURLEnd = pageURL.split('/')[3];
                    if (pageURLEnd.indexOf("#") >= 0){
                        pageURLEnd = pageURLEnd.split('#')[0];
                    }
                    if(pageURLEnd == 'public'){
                        var pageURLEnd = pageURL.split('/')[4];
                        if (pageURLEnd.indexOf("#") >= 0){
                            pageURLEnd = pageURLEnd.split('#')[0];
                        }
                    }
                   
                    $.each( arrNotifyAdm, function( index, value ) {
                        respo2D = value.split('||');

                        console.log(pageURLEnd);

                        if(pageURLEnd == 'cookPanelIndexCook'){
                            if(respo2D[0] == 'AdminUpdateOrdersP'){
                                if($("#TableColumnCookTO"+respo2D[3]).length){
                                    // if($("#TableColumnCookTO-1").length){

                                    //     console.log(respo2D[4]);
                                    //     console.log(respo2D[5]);

                                    //     if(respo2D[4] == 'removeIt'){
                                    //         $('#TableColumnCookTO'+respo2D[3]).remove();
                                    //     }else{
                                    //         $('#TableColumnCookTO'+respo2D[3]).load(location.href+" #TableColumnCookTO"+respo2D[3]+">*","");
                                    //     }
                                    //     if(respo2D[5] == 'removeIt'){
                                    //         $('#TableColumnCookTO-1').remove();
                                    //     }else{
                                    //         $("#TableColumnCookTO-1").load(location.href+" #TableColumnCookTO-1>*","");
                                    //     }
                                    //     // sound noty
                                    //     $('#soundsAllCookDes').html('<audio id="soundsAllCookDesBeep" src="https://qrorpa.ch/storage/sound/cookPanelNoty.mp3" type="audio/mpeg" autoplay="true"></audio>');
                                    //     var selAudio = $("#soundsAllCookDesBeep")[0];
                                    //     selAudio.play();
                                    // }else{
                                        reload();
                                    // }

                                }else{
                                    showNewTable(respo2D[3]);
                                    // reload();
                                }
                            }else if(respo2D[0] == 'AdminUpdateOrdersPForPayed'){
                                if($("#TableColumnCookTO"+respo2D[1]).length){
                                    if(respo2D[3] == 'removeIt'){
                                        $('#TableColumnCookTO'+respo2D[1]).remove();
                                    }else{
                                        $('#TableColumnCookTO'+respo2D[1]).load(location.href+" #TableColumnCookTO"+respo2D[1]+">*","");
                                    }
                                    if(respo2D[4] == 'removeIt'){
                                        $('#TableColumnCookTO-1').remove();
                                    }else{
                                        $("#TableColumnCookTO-1").load(location.href+" #TableColumnCookTO-1>*","");
                                    }

                                    // $("#TableColumnCookTO"+respo2D[1]).fadeOut(500, function(){ $(this).remove();});
                                    // sound noty
                                    var platesId = $("#prodOneT"+respo2D[2]).parent().parent().attr('id');
                                    $("#prodOneT"+respo2D[2]).fadeOut(100, function(){ $(this).remove();});

                                    var countProdsOnPlate= $("#"+platesId+" .proShowDiv").length;
                                    console.log(countProdsOnPlate);
                                    if(countProdsOnPlate == 1){
                                        $("#"+platesId).fadeOut(100, function(){ $(this).remove();});
                                    }

                                    var countPlatesShown = $("#TableColumnCookTO"+respo2D[1]+" .plateShowDiv").length;
                                    console.log(countPlatesShown);

                                    $('#soundsAllCookDes').html('<audio id="soundsAllCookDesBeep" src="https://qrorpa.ch/storage/sound/cookPanelNoty.mp3" type="audio/mpeg" autoplay="true"></audio>');
                                    var selAudio = $("#soundsAllCookDesBeep")[0];
                                    selAudio.play();
                                }else{
                                    reloadNoSound();
                                }
                            }else if(respo2D[0] == 'Order'){
                                reload();
                            }else if(respo2D[0] == 'cookPanelUpdate'){
                                if($("#TableColumnCookTO"+respo2D[4]).length){
                                    $("#TableColumnCookTO"+respo2D[4]).load(location.href+" #TableColumnCookTO"+respo2D[4]+">*","");
                                    $("#TableColumnCookTO-1").load(location.href+" #TableColumnCookTO-1>*","");
                                    // sound noty
                                    $('#soundsAllCookDes').html('<audio id="soundsAllCookDesBeep" src="https://qrorpa.ch/storage/sound/cookPanelNoty.mp3" type="audio/mpeg" autoplay="true"></audio>');
                                    var selAudio = $("#soundsAllCookDesBeep")[0];
                                    selAudio.play();
                                }else{
                                    reloadNoSound();
                                }
                            }else if(respo2D[0] == 'cookPanelUpdateTaNewOr' || respo2D[0] == 'cookPanelUpdateTaCookUpdate' || respo2D[0] == 'cookPanelUpdateTaToPay' || respo2D[0] == 'AdminUpdateOrdersPForPayed'){
                                $("#cookPanelNavBar").load(location.href+" #cookPanelNavBar>*","");
                                // sound noty
                                $('#soundsAllCookDes').html('<audio id="soundsAllCookDesBeep" src="https://qrorpa.ch/storage/sound/cookPanelNoty.mp3" type="audio/mpeg" autoplay="true"></audio>');
                                var selAudio = $("#soundsAllCookDesBeep")[0];
                                selAudio.play();
                            }

                        }else if(pageURLEnd == 'cookPanelIndexCookNotConf'){
                            if(respo2D[0] == 'AdminUpdateOrdersP' || value == 'AdminUpdateOrdersPForPayed' || respo2D[0] == 'Order' || respo2D[0] == 'cookPanelUpdate'){
                                if($('#newNotificationAlert').is(':hidden')){ $('#newNotificationAlert').show(50); }
                                $('#soundsAllCookDes').html('<audio id="soundsAllCookDesBeep" src="https://qrorpa.ch/storage/sound/cookPanelNoty.mp3" type="audio/mpeg" autoplay="true"></audio>');
                                // $("#soundsAllCookDes").load(location.href+" #soundsAllCookDes>*","");
                                var selAudio = $("#soundsAllCookDesBeep")[0];
                                selAudio.play();
                            }else if(respo2D[0] == 'cookPanelUpdateTaNewOr' || respo2D[0] == 'cookPanelUpdateTaCookUpdate' || respo2D[0] == 'cookPanelUpdateTaToPay' || respo2D[0] == 'AdminUpdateOrdersPForPayed'){
                                $("#cookPanelNavBar").load(location.href+" #cookPanelNavBar>*","");
                            }else if(respo2D[0] == 'OrderServedToTable'){
                                reload();
                            }

                        }else if(pageURLEnd == 'cookPanelIndexCookT'){
                            if(respo2D[0] == 'cookPanelUpdateTaNewOr'){
                                reload();
                            }else if(respo2D[0] == 'cookPanelUpdateTaCookUpdate'){
                                // reloadNoSound();
                                $("#orderColumnCookTO"+respo2D[1]).load(location.href+" #orderColumnCookTO"+respo2D[1]+">*","");
                            }else if(respo2D[0] == 'cookPanelUpdateTaToPay'){
                                reload();
                            }else if(respo2D[0] == 'AdminUpdateOrdersPForPayed'){
                                reload();
                            }else if(respo2D[0] == 'AdminUpdateOrdersP' || value == 'AdminUpdateOrdersPForPayed' || respo2D[0] == 'Order' || respo2D[0] == 'cookPanelUpdate'){
                                $("#cookPanelNavBar").load(location.href+" #cookPanelNavBar>*","");
                            }

                        }else if(pageURLEnd == 'cookPanelIndexCookTNotConf'){
                            if(respo2D[0] == 'cookPanelUpdateTaNewOr' || respo2D[0] == 'cookPanelUpdateTaCookUpdate' || respo2D[0] == 'cookPanelUpdateTaToPay' || respo2D[0] == 'AdminUpdateOrdersPForPayed'){
                                if($('#newNotificationAlert').is(':hidden')){ $('#newNotificationAlert').show(50); }
                                $('#soundsAllCookDes').html('<audio id="soundsAllCookDesBeep" src="https://qrorpa.ch/storage/sound/cookPanelNoty.mp3" type="audio/mpeg" autoplay="true"></audio>');
                                // $("#soundsAllCookDes").load(location.href+" #soundsAllCookDes>*","");
                                var selAudio = $("#soundsAllCookDesBeep")[0];
                                selAudio.play();
                            }else if(respo2D[0] == 'AdminUpdateOrdersP' || value == 'AdminUpdateOrdersPForPayed' || respo2D[0] == 'Order' || respo2D[0] == 'cookPanelUpdate'){
                                $("#cookPanelNavBar").load(location.href+" #cookPanelNavBar>*","");
                            }


                        }else if(pageURLEnd == 'cookPanelIndexCookD'){
                            if(respo2D[0] == 'cookPanelUpdateDe'){
                                reload();
                            }
                        }
                    });
                    
                }
            },error: (error) => { console.log(error);}
        }); //End AJAX form 
    }, 1200);

    $(document).ready(function () {
        if (window.location.hash == '#reloadNjoftime') {
            onReload();
        }
        console.log(window.location.hash);
    });

    function onReload () {
        $('#soundsAllCookDes').html('<audio id="soundsAllCookDesBeep" src="https://qrorpa.ch/storage/sound/cookPanelNoty.mp3" type="audio/mpeg" autoplay="true"></audio>');
        // $("#soundsAllCookDes").load(location.href+" #soundsAllCookDes>*","");
        var selAudio = $("#soundsAllCookDesBeep")[0];
        selAudio.play();
    }

    function reload () {
        window.location.hash = 'reloadNjoftime';
        window.location.reload();
    }
    function reloadNoSound(){
        window.location.hash = 'reload';
        window.location.reload();
    }


    function showNewTable(tableNr){
        // if($('#cookPanelTablesAll').length){
        //     $('#cookPanelTablesAll').append('<div class="d-flex flex-wrap TableColumnCookTOAll" id="TableColumnCookTO'+tableNr+'">');
        //     // $('#cookPanelTablesAll').append('<h2 class="text-center mb-2" style="width:100%; height:fit-content; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px;"><strong>Tisch '+tableNr+'</strong></h2>')
        //     $('#cookPanelTablesAll').append('</div>');

        //     $("#TableColumnCookTO"+tableNr).load(location.href+" #TableColumnCookTO"+tableNr+">*","");
        // }else{
            window.location.hash = 'reloadNjoftime';
            window.location.reload();
        // }
        
    }
</script>