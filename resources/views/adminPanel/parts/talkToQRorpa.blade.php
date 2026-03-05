<?php

use App\User;
    use App\admMsgSaav;
    use App\admMsgSaavchats;
?>
<style>
    .rightMsg {
        position: relative;
        background: aqua;
        text-align: left;
        min-width: 45%;
        padding: 10px 15px;
        border-radius: 6px;
        border: 1px solid rgba(0,92,75,255);
        float: right;
        right: 10px;
    }

    .rightMsg::before {
        content: '';
        position: absolute;
        visibility: visible;
        top: -1px;
        right: -10px;
        border: 10px solid transparent;
        border-top: 10px solid rgba(0,92,75,255);
    }

    .rightMsg::after {
        content: '';
        position: absolute;
        visibility: visible;
        top: 0px;
        right: -8px;
        border: 10px solid transparent;
        border-top: 10px solid rgba(0,92,75,255);
        clear: both;
    }

    .leftMsg {
        position: relative;
        background: aqua;
        text-align: left;
        min-width: 45%;
        padding: 10px 15px;
        border-radius: 6px;
        border: 1px solid rgba(32,44,51,255);
        float: left;
        left: 10px;
    }

    .leftMsg::before {
        content: '';
        position: absolute;
        visibility: visible;
        top: -1px;
        left: -10px;
        border: 10px solid transparent;
        border-top: 10px solid rgba(32,44,51,255);
    }

    .leftMsg::after {
        content: '';
        position: absolute;
        visibility: visible;
        top: 0px;
        left: -8px;
        border: 10px solid transparent;
        border-top: 10px solid rgba(32,44,51,255);
        clear: both;
    }
</style>


<div class="p-3 pb-5">

    <div class="d-flex justify-content-between mt-2 mb-2">
        <h3 style="color:rgb(39,190,175); width:49%;"><strong>Sprich mit QRorpa</strong></h3>
        <button class="btn btn-outline-dark" style="width:49%;" data-toggle="modal" data-target="#addNewConvCard">
            <strong><i class="fas fa-plus"></i> Fügen Sie eine neue Konversationskarte hinzu</strong>
        </button>
    </div>
    <hr>

    <div id="myConvCarts" class="d-flex flex-wrap justify-content-start">
        <h4 style="color:rgb(72,81,87); width:100%;"><strong>Ihre Gesprächskarten</strong></h4>
        @if(admMsgSaav::where('avById',Auth::User()->id)->count() <= 0 )
            <p style="color:red">Null-Gesprächskarten</p>
        @else
            @foreach (admMsgSaav::where('avById',Auth::User()->id)->get() as $oneAv)
            <div class="card mb-2" style="width: 33%; margin-right:0.33%; background-color:rgb(39,190,175);">
                <div class="card-body p-1">
                    <div class="d-flex flex-wrap">
                        <button style="width:5%; padding:0px; height: 1.5em;" class="btn btn-danger" data-toggle="modal" data-target="#deleteThisAV{{$oneAv->id}}"><i class="far fa-trash-alt"></i></button>
                        <h5 class="text-center" style="width:95%; color:white; line-height: 1.5em; height: 3em; overflow-y: auto;"><strong>{{$oneAv->avTittle}}</strong></h5>
                        <?php
                            $data2D = explode('-',explode(' ',$oneAv->created_at)[0]);
                            $koha2D = explode(':',explode(' ',$oneAv->created_at)[1]);
                        ?>
                        <h5 style="width:100%; color:white; margin-top:-5px;" class="card-title d-flex text-center">
                            <span style="width: 49%;">{{$koha2D[0]}}:{{$koha2D[1]}}</span> 
                            <span style="width: 49%;">{{$data2D[2]}}/{{$data2D[1]}}/{{$data2D[0]}}</span>
                        </h5>
                    </div>
                 
                    <div class="p-1 d-flex flex-wrap messagesDivAll" id="messagesDiv{{$oneAv->id}}" style="height:35vh; width:100%; overflow-y: auto; border:1px solid rgba(72,81,87,0.1); border-radius:7px; background-color:white;">
                        @if (admMsgSaavchats::where('avId',$oneAv->id)->count() <= 0)
                            <p style="color:rgba(72,81,87,0.5); width:100%;">Noch keine Nachrichten</p>
                        @else
                            @foreach (admMsgSaavchats::where('avId',$oneAv->id)->get()->sortBy('created_at') as $oseMsg )
                                <?php
                                    $data2DMsg = explode('-',explode(' ',$oseMsg->created_at)[0]);
                                    $koha2DMsg = explode(':',explode(' ',$oseMsg->created_at)[1]);
                                ?>
                                @if($oseMsg->msgById == Auth::User()->id)
                                    <!-- My MSG -->
                                    <div class="p-1 mb-2 rightMsg" style="width:70%; height:fit-content; margin-left:30%; color:white; background-color:rgba(0,92,75,255);">
                                        <p class="mb-1">{{$oseMsg->msgContent}}</p>
                                        <div class="d-flex">
                                            <p class="mb-1" style="color:rgba(255, 255, 255, 0.45); width:45%;">{{$koha2DMsg[0]}}:{{$koha2DMsg[1]}}:{{$koha2DMsg[2]}}</p>
                                            <p class="mb-1 text-center" style="color:rgba(255, 255, 255, 0.45); width:40%;">{{$data2DMsg[2]}}/{{$data2DMsg[1]}}</p>
                                            @if ( $oseMsg->readStatus == 1 )
                                                <i style="width:15%; color:rgb(39,190,175);" class="fas text-center pt-1 fa-check-double"></i> <!-- read -->
                                            @else
                                                <i style="width:15%; color:white;" class="fas text-center pt-1 fa-check"></i> <!-- not read -->
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <!-- His/Her MSG -->
                                    <div class="p-1 mb-2 leftMsg" style="width:70%; height:fit-content; margin-right:30%; color:white; background-color:rgba(32,44,51,255);">
                                        <p class="mb-1">{{$oseMsg->msgContent}}</p>
                                        <div class="d-flex">
                                            <p class="mb-1" style="color:rgba(255, 255, 255, 0.45); width:50%;">{{$koha2DMsg[0]}}:{{$koha2DMsg[1]}}:{{$koha2DMsg[2]}}</p>
                                            <p class="mb-1 text-right" style="color:rgba(255, 255, 255, 0.45); width:50%;">{{$data2DMsg[2]}}/{{$data2DMsg[1]}}</p>
                                            @if ( $oseMsg->readStatus == 0 )
                                                <script>
                                                    $.ajax({
                                                        url: '{{ route("atsMsg.AdmIReadTheMsg") }}',
                                                        method: 'post',
                                                        data: {
                                                            msgId: '{{$oseMsg->id}}',
                                                            _token: '{{csrf_token()}}'
                                                        },
                                                        success: () => {},
                                                        error: (error) => {console.log(error);}
                                                    });
                                                </script>
                                            @endif
                                        </div>
                                    </div>
                                @endif                                
                            @endforeach
                        @endif
                    </div>
                    <div class="input-group">
                        <input type="text" onkeydown="sendMsgEnter(event,'{{$oneAv->id}}','{{Auth::User()->id}}')" class="form-control shadow-none" placeholder="Nachricht" id="messageInputFor{{$oneAv->id}}">
                        <div class="input-group-append">
                            <button class="btn" style="padding:3px 5px 3px 5px; background-color:rgba(32,44,51,255);" type="button" onclick="sendMsg('{{$oneAv->id}}','{{Auth::User()->id}}')">
                                <i style="color:white;" class="far fa-2x fa-arrow-alt-circle-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="messagesDivError01">Schreiben Sie zuerst die Nachricht!</div>
                </div>
            </div>



            <!-- delete Modal -->
            <div class="modal fade" id="deleteThisAV{{$oneAv->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color:rgb(72,81,87);"><strong>Möchten Sie diese Karte wirklich löschen?</strong></h5>
                        </div>
                        <div class="modal-body d-flex flex-wrap justify-content-between">
                            <h5 style="width:100%;" class="mb-2 text-center"><strong>{{$oneAv->avTittle}}</strong></h5>

                            <button style="width: 49%;" onclick="closeDeleteThisAVModal('{{$oneAv->id}}')" data-dismiss="modal" class="btn btn-dark"><strong>Nein</strong></button>
                            <button style="width: 49%;" onclick="deleteThisAV('{{$oneAv->id}}')" class="btn btn-danger"><strong>Ja</strong></button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <hr>
    
    <div id="convCartsForMe" class="d-flex flex-wrap justify-content-start">
        <h4 style="color:rgb(72,81,87); width:100%;"><strong>Gesprächskarten für Sie</strong></h4>
        @if(admMsgSaav::where('avForId',Auth::User()->id)->count() <= 0 )
            <p style="color:red; width:100%;">Null-Gesprächskarten</p>
        @else
            @foreach (admMsgSaav::where('avForId',Auth::User()->id)->get() as $oneAv)
            <div class="card mb-2" style="width: 33%; margin-right:0.33%; background-color:rgb(39,190,175);">
                <div class="card-body p-1">
                    <div class="d-flex flex-wrap">
                        <h5 class="text-center" style="width:100%; color:white; line-height: 1.5em; height: 3em; overflow-y: auto;"><strong>{{$oneAv->avTittle}}</strong></h5>
                        <?php
                            $data2D = explode('-',explode(' ',$oneAv->created_at)[0]);
                            $koha2D = explode(':',explode(' ',$oneAv->created_at)[1]);
                        ?>
                        <h5 style="width:100%; color:white; margin-top:-5px;" class="card-title d-flex text-center">
                            <span style="width: 49%;">{{$koha2D[0]}}:{{$koha2D[1]}}</span> 
                            <span style="width: 49%;">{{$data2D[2]}}/{{$data2D[1]}}/{{$data2D[0]}}</span>
                            
                        </h5>
                    </div>
                 
                    <div class="p-1 d-flex flex-wrap messagesDivAll" id="messagesDiv{{$oneAv->id}}" style="height:35vh; width:100%; overflow-y: auto; border:1px solid rgba(72,81,87,0.1); border-radius:7px; background-color:white;">
                        @if (admMsgSaavchats::where('avId',$oneAv->id)->count() <= 0)
                            <p style="color:rgba(72,81,87,0.5); width:100%;">Noch keine Nachrichten</p>
                        @else
                            @foreach (admMsgSaavchats::where('avId',$oneAv->id)->get()->sortBy('created_at') as $oseMsg )
                                <?php
                                    $data2DMsg = explode('-',explode(' ',$oseMsg->created_at)[0]);
                                    $koha2DMsg = explode(':',explode(' ',$oseMsg->created_at)[1]);
                                ?>
                                @if($oseMsg->msgById == Auth::User()->id)
                                    <!-- My MSG -->
                                    <div class="p-1 mb-2 rightMsg" style="width:70%; height:fit-content; margin-left:30%; color:white; background-color:rgba(0,92,75,255);">
                                        <p class="mb-1">{{$oseMsg->msgContent}}</p>
                                        <div class="d-flex">
                                            <p class="mb-1" style="color:rgba(255, 255, 255, 0.45); width:45%;">{{$koha2DMsg[0]}}:{{$koha2DMsg[1]}}:{{$koha2DMsg[2]}}</p>
                                            <p class="mb-1 text-center" style="color:rgba(255, 255, 255, 0.45); width:40%;">{{$data2DMsg[2]}}/{{$data2DMsg[1]}}</p>
                                            @if ( $oseMsg->readStatus == 1 )
                                                <i style="width:15%; color:rgb(39,190,175);" class="fas text-center pt-1 fa-check-double"></i> <!-- read -->
                                            @else
                                                <i style="width:15%; color:white;" class="fas text-center pt-1 fa-check"></i> <!-- not read -->
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <!-- His/Her MSG -->
                                    <div class="p-1 mb-2 leftMsg" style="width:70%; height:fit-content; margin-right:30%; color:white; background-color:rgba(32,44,51,255);">
                                        <p class="mb-1">{{$oseMsg->msgContent}}</p>
                                        <div class="d-flex">
                                            <p class="mb-1" style="color:rgba(255, 255, 255, 0.45); width:50%;">{{$koha2DMsg[0]}}:{{$koha2DMsg[1]}}:{{$koha2DMsg[2]}}</p>
                                            <p class="mb-1 text-right" style="color:rgba(255, 255, 255, 0.45); width:50%;">{{$data2DMsg[2]}}/{{$data2DMsg[1]}}</p>
                                            @if ( $oseMsg->readStatus == 0 )
                                                <script>
                                                    $.ajax({
                                                        url: '{{ route("atsMsg.AdmIReadTheMsg") }}',
                                                        method: 'post',
                                                        data: {
                                                            msgId: '{{$oseMsg->id}}',
                                                            _token: '{{csrf_token()}}'
                                                        },
                                                        success: () => {},
                                                        error: (error) => {console.log(error);}
                                                    });
                                                </script>
                                            @endif
                                        </div>
                                    </div>
                                @endif                                
                            @endforeach
                        @endif
                    </div>
                    <div class="input-group">
                        <input type="text" onkeydown="sendMsg2Enter(event,'{{$oneAv->id}}','{{Auth::User()->id}}')" class="form-control shadow-none" placeholder="Nachricht" id="messageInputFor{{$oneAv->id}}">
                        <div class="input-group-append">
                            <button class="btn" style="padding:3px 5px 3px 5px; background-color:rgba(32,44,51,255);" type="button" onclick="sendMsg2('{{$oneAv->id}}','{{Auth::User()->id}}')">
                                <i style="color:white;" class="far fa-2x fa-arrow-alt-circle-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="messagesDivError01">Schreiben Sie zuerst die Nachricht!</div>
                </div>
            </div>



            <!-- delete Modal -->
            <div class="modal fade" id="deleteThisAV{{$oneAv->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color:rgb(72,81,87);"><strong>Möchten Sie diese Karte wirklich löschen?</strong></h5>
                        </div>
                        <div class="modal-body d-flex flex-wrap justify-content-between">
                            <h5 style="width:100%;" class="mb-2 text-center"><strong>{{$oneAv->avTittle}}</strong></h5>

                            <button style="width: 49%;" onclick="closeDeleteThisAVModal('{{$oneAv->id}}')" data-dismiss="modal" class="btn btn-dark"><strong>Nein</strong></button>
                            <button style="width: 49%;" onclick="deleteThisAV('{{$oneAv->id}}')" class="btn btn-danger"><strong>Ja</strong></button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>



</div>


<!-- add a new conversation card Modal -->
<div class="modal fade" id="addNewConvCard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color:rgb(72,81,87);"><strong>Fügen Sie eine neue Konversationskarte hinzu</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-center mb-2" style="color:rgb(72,81,87)"><strong>Diese Karte wird an einen Vertreter der QRorpa-Plattform weitergeleitet</strong></h5>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Grund</span>
                    </div>
                    <textarea id="addNewConvCardResonInput" class="form-control shadow-none" rows="2" aria-label="With textarea"></textarea>
                </div>
                <button class="mt-1 mb-1 btn btn-success" style="width:100%;" onclick="saveNewConvCard('{{Auth::User()->id}}')"><strong>Sparen</strong></button>

                <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="addNewConvCardError01">Schreiben Sie zuerst den Grund!</div>
            </div>
        </div>
    </div>
</div>


<script>
    var slides = document.getElementsByClassName("messagesDivAll");
    for (var i = 0; i < slides.length; i++) {
        var theId = slides.item(i).id;
        objDiv = document.getElementById(theId);
        objDiv.scrollTop = objDiv.scrollHeight;
    }


    function saveNewConvCard(admID){
        if(!$('#addNewConvCardResonInput').val()){
            if($('#addNewConvCardError01').is(':hidden')){ $('#addNewConvCardError01').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
				url: '{{ route("atsMsg.ASASaveNewCard") }}',
				method: 'post',
				data: {
					adminId: admID,
                    reasonOfCard: $('#addNewConvCardResonInput').val(),
					_token: '{{csrf_token()}}'
				},
				success: () => {
					$("#myConvCarts").load(location.href+" #myConvCarts>*","");
                    $('#addNewConvCardResonInput').val('');
                    $('#addNewConvCard').modal('hide');
				},
				error: (error) => { console.log(error); }
			});
        }
    }

    function sendMsg(avId, admId){
        if(!$('#messageInputFor'+avId).val()){
            if($('#messagesDivError01').is(':hidden')){ $('#messagesDivError01').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
				url: '{{ route("atsMsg.ASASaveNewMessageOnCard") }}',
				method: 'post',
				data: {
					adminId: admId,
                    avId: avId,
                    theMsg: $('#messageInputFor'+avId).val(),
                    theRes: '{{Auth::User()->sFor}}',
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = respo.replace(/\s/g, '');
                    var respo2D = respo.split('||');

                    var respoTime = respo2D[1].split(' ')[1];
                    var respoTime2D = respoTime.split(':');

                    var respoDate = respo2D[1].split(' ')[0];
                    var respoDate2D = respoDate.split('-');

                    var newMsg =    '<div class="p-1 mb-2 rightMsg" style="width:70%; height:fit-content; margin-left:30%; color:white; background-color:rgba(0,92,75,255);">'+
                                        '<p class="mb-1">'+respo2D[0]+'</p>'+
                                        '<div class="d-flex">'+
                                            '<p class="mb-1" style="color:rgba(255, 255, 255, 0.45); width:45%;">'+respoTime2D[0]+':'+respoTime2D[1]+':'+respoTime2D[2]+'</p>'+
                                            '<p class="mb-1 text-center" style="color:rgba(255, 255, 255, 0.45); width:40%;">'+respoDate2D[2]+'/'+respoDate2D[1]+'</p>'+
                                            '<i style="width:15%; color:white;" class="fas text-center pt-1 fa-check"></i> <!-- not read -->'+
                                        '</div>'+
                                    '</div>';

                    $("#messagesDiv"+avId).append(newMsg);

                    $('#messageInputFor'+avId).val('');

                    objDiv = document.getElementById("messagesDiv"+avId);
                    objDiv.scrollTop = objDiv.scrollHeight;
                   
				},
				error: (error) => { console.log(error); }
			});
        }   
    }
    function sendMsgEnter(ev ,avId, admId){
        if(ev.code == 'Enter'){ sendMsg(avId, admId); }
        // console.log(ev.code);
    }

    function sendMsg2(avId, admId){
        if(!$('#messageInputFor'+avId).val()){
            if($('#messagesDivError01').is(':hidden')){ $('#messagesDivError01').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
				url: '{{ route("atsMsg.ASASaveNewMessageOnCard2") }}',
				method: 'post',
				data: {
					adminId: admId,
                    avId: avId,
                    theMsg: $('#messageInputFor'+avId).val(),
                    theRes: '{{Auth::User()->sFor}}',
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = respo.replace(/\s/g, '');
                    var respo2D = respo.split('||');

                    var respoTime = respo2D[1].split(' ')[1];
                    var respoTime2D = respoTime.split(':');

                    var respoDate = respo2D[1].split(' ')[0];
                    var respoDate2D = respoDate.split('-');

                    var newMsg =    '<div class="p-1 mb-2 rightMsg" style="width:70%; height:fit-content; margin-left:30%; color:white; background-color:rgba(0,92,75,255);">'+
                                        '<p class="mb-1">'+respo2D[0]+'</p>'+
                                        '<div class="d-flex">'+
                                            '<p class="mb-1" style="color:rgba(255, 255, 255, 0.45); width:45%;">'+respoTime2D[0]+':'+respoTime2D[1]+':'+respoTime2D[2]+'</p>'+
                                            '<p class="mb-1 text-center" style="color:rgba(255, 255, 255, 0.45); width:40%;">'+respoDate2D[2]+'/'+respoDate2D[1]+'</p>'+
                                            '<i style="width:15%; color:white;" class="fas text-center pt-1 fa-check"></i> <!-- not read -->'+
                                        '</div>'+
                                    '</div>';

                    $("#messagesDiv"+avId).append(newMsg);
                    $('#messageInputFor'+avId).val('');
                    objDiv = document.getElementById("messagesDiv"+avId);
                    objDiv.scrollTop = objDiv.scrollHeight;
				},
				error: (error) => { console.log(error); }
			});
        } 
    }
    function sendMsg2Enter(ev ,avId, admId){
        if(ev.code == 'Enter'){ sendMsg2(avId, admId); }
        // console.log(ev.code);
    }
   


    function deleteThisAV(avId){
        $.ajax({
			url: '{{ route("atsMsg.ASADeleteCard") }}',
			method: 'post',
			data: {
				avId: avId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#myConvCarts").load(location.href+" #myConvCarts>*","");
                $('#deleteThisAV'+avId).modal('hide');
			},
			error: (error) => { console.log(error); }
		});
    }
</script>



