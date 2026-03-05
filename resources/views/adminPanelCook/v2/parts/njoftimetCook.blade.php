
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
                    $.each( arrNotifyAdm, function( index, value ) {
                        respo2D = value.split('||');

                        if (pageURLEnd.indexOf("#") >= 0){
                            pageURLEnd = pageURLEnd.split('#')[0];
                        }

                        if(pageURLEnd == 'cookPanelIndexCook'){
                            if(respo2D[0] == 'AdminUpdateOrdersP'){
                                // is this PRODUCT present
                                // if($('#productListingCookTO'+respo2D[1]).length){
                                //     $("#productListingCookTO"+respo2D[1]).load(location.href+" #productListingCookTO"+respo2D[1]+">*","");
                                //     $('#productListingCookTO'+respo2D[1]).attr('style','width:100%;');
                                // is this CATEGORY present
                                // }else{
                                reload();
                                // }
                            }else if(value == 'AdminUpdateOrdersPForPayed'){
                                reloadNoSound();
                            }else if(respo2D[0] == 'Order'){
                                reload();
                            }else if(respo2D[0] == 'cookPanelUpdate'){
                                reloadNoSound();
                            }else if(respo2D[0] == 'cookPanelUpdateTaNewOr' || respo2D[0] == 'cookPanelUpdateTaCookUpdate' || respo2D[0] == 'cookPanelUpdateTaToPay' || respo2D[0] == 'AdminUpdateOrdersPForPayed'){
                                $("#cookPanelNavBar").load(location.href+" #cookPanelNavBar>*","");
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
                                reloadNoSound();
                            }else if(respo2D[0] == 'cookPanelUpdateTaToPay'){
                                reloadNoSound();
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
</script>