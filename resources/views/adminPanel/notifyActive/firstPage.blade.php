<?php

    use App\UserNotiDtlSet;
use App\userSoundsList;
use Illuminate\Support\Facades\Auth;
    $notifyAc2D = explode('--||--',Auth::user()->notifySet);
    


?>

<div id="serverVariablesReset">
    <?php
        $usrNotiReg1 = UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',1]])->first();
        $usrNotiReg21 = UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',21]])->first();
        if($usrNotiReg21 != Null){
            $usrNotiReg21Sound = userSoundsList::find($usrNotiReg21->setValue);
        }
    
        $usrNotiReg31 = UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',31]])->first();
        if($usrNotiReg31 != Null){
            $usrNotiReg31Sound = userSoundsList::find($usrNotiReg31->setValue);
        }
    ?>
</div>
<div id="soundPlayDiv"></div>

<div class="pt-2 pr-2 pl-2 pb-4">
    <p style="color:rgb(39,190,175); font-size:1.5rem; margin-bottom:5px;"><strong>Benachrichtigungsverwaltung</strong></p>
    <hr style="margin-top:10px; margin-bottom: 10px;">

    <div class="d-flex justify-content-between flex-wrap">
        <div style="width: 32%; border: 1px solid rgb(0,0,0,0.5); border-radius:10px;" class="d-flex justify-content-between flex-wrap mb-2" id="redGlowNotifyChng">
            @if($usrNotiReg1 != Null)
            <style>  
                @keyframes glowing {
                    0% { box-shadow: 0 0 -10px <?php echo $usrNotiReg1->setValue; ?>; background-position: 0 0;} 
                    40% { box-shadow: 0 0 20px <?php echo $usrNotiReg1->setValue; ?>; } 
                    60% { box-shadow: 0 0 20px <?php echo $usrNotiReg1->setValue; ?>; }
                    100% { box-shadow: 0 0 -10px <?php echo $usrNotiReg1->setValue; ?>; background-position: 1280px 0;}
                }
                .table-glow-adminAlert {animation: glowing 1000ms linear infinite; border-radius: 6px; cursor: pointer; }
            </style>
            @else
            <style>  
                @keyframes glowing {
                    0% { box-shadow: 0 0 -10px red; background-position: 0 0;} 
                    40% { box-shadow: 0 0 20px red; } 
                    60% { box-shadow: 0 0 20px red; }
                    100% { box-shadow: 0 0 -10px red; background-position: 1280px 0;}
                }
                .table-glow-adminAlert {animation: glowing 1000ms linear infinite; border-radius: 6px; cursor: pointer; }
            </style>
            @endif
            <div style="width: 100%; height:fit-content;" class="d-flex justify-content-between flex-wrap">
                <img style="width:24%; height:auto; margin:20px 13% 20px 13%;" class="pointTable" src="storage/images/tableSt_yellow.PNG" >
                <img style="width:24%; height:auto; margin:20px 13% 20px 13%;" class="pointTable table-glow-adminAlert" src="storage/images/tableSt_yellow.PNG" >
            </div>
    
            <hr style="width: 100%; margin-top:3px; margin-bottom:3px;">
            <p style="width:100%; " class="text-center"><strong>Rot leuchtende Animation</strong></p>
            @if($notifyAc2D[0] == 1)
            <button style="width: 49.7%;" class="btn btn-success shadow-none" onclick="changeRedGlowAnim('0')"><strong>Aktiv</strong></button>
            @else
            <button style="width: 49.7%;" class="btn btn-danger shadow-none" onclick="changeRedGlowAnim('1')"><strong>Nicht aktiv</strong></button>
            @endif
            <button style="width: 49.7%;" class="btn btn-outline-dark shadow-none">
                <strong>
                    @if($usrNotiReg1 != Null)
                    <input onchange="setNewGlowTableColor()" id="glowTableColor" style="border:none; padding:0px;" value="{{$usrNotiReg1->setValue}}" type= "color"/> Die Farbe
                    @else
                    <input onchange="setNewGlowTableColor()" id="glowTableColor" style="border:none; padding:0px;" value="#ff0000" type= "color"/> Die Farbe
                    @endif
                </strong>
     
            </button>
        </div>

        <div style="width: 32%; border: 1px solid rgb(0,0,0,0.5); border-radius:10px;" class="d-flex justify-content-between flex-wrap mb-2" id="addProdSound">
            <div style="width: 100%; height:fit-content;" class="d-flex justify-content-between flex-wrap">
                <img style="width:100%; height:auto; margin-top:35px; margin-bottom:35px;" class="pointTable" src="storage/images/productRegPic001.PNG" >
            </div>
    
            <hr style="width: 100%; margin-top:3px; margin-bottom:3px;">
            <p style="width:100%; " class="text-center"><strong>Produkt zur Bestellbenachrichtigung hinzufügen</strong></p>
            @if($notifyAc2D[1] == 1)
            <button style="width: 49.7%;" class="btn btn-success shadow-none" onclick="changeAddProdSound('0')"><strong>Aktiv</strong></button>
            @else
            <button style="width: 49.7%;" class="btn btn-danger shadow-none" onclick="changeAddProdSound('1')"><strong>Nicht aktiv</strong></button>
            @endif
            <button style="width: 49.7%;" class="btn btn-outline-dark shadow-none"><strong>
                @if($usrNotiReg21 != Null)
                <span class="mr-2" onclick="playTestSoundAddProd('{{$usrNotiReg21Sound->soundTitle}}','{{$usrNotiReg21Sound->soundExt}}')"><i id="playIcon21" class="fa-solid fa-play"></i></span> | 
                @else
                <span class="mr-2" onclick="playTestSoundAddProd('default','default')"><i id="playIcon21" class="fa-solid fa-play"></i></span> | 
                @endif

                <span class="ml-2" data-toggle="modal" data-target="#soundList21Modal"><i class="fa-solid fa-pen"></i></span> 
                Der Klang
            </strong></button>
        </div>

        <div style="width: 32%; border: 1px solid rgb(0,0,0,0.5); border-radius:10px;" class="d-flex justify-content-between flex-wrap mb-2" id="payCloseOrderSound">
            <div style="width: 100%; height:fit-content;" class="d-flex justify-content-between flex-wrap">
                <img style="width:100%; height:auto; margin-top:40px; margin-bottom:40px;" class="pointTable" src="storage/images/payMethodsPic001.PNG" >
            </div>
    
            <hr style="width: 100%; margin-top:3px; margin-bottom:3px;">
            <p style="width:100%; " class="text-center"><strong>Schließen/bezahlen Sie eine Bestellbenachrichtigung</strong></p>
            @if($notifyAc2D[2] == 1)
            <button style="width: 49.7%;" class="btn btn-success shadow-none" onclick="changePayCloseOrderSound('0')"><strong>Aktiv</strong></button>
            @else
            <button style="width: 49.7%;" class="btn btn-danger shadow-none" onclick="changePayCloseOrderSound('1')"><strong>Nicht aktiv</strong></button>
            @endif
            <button style="width: 49.7%;" class="btn btn-outline-dark shadow-none"><strong>
                @if($usrNotiReg31 != Null)
                <span class="mr-2" onclick="playTestSoundPayOrder('{{$usrNotiReg31Sound->soundTitle}}','{{$usrNotiReg31Sound->soundExt}}')"><i id="playIcon31" class="fa-solid fa-play"></i></span> | 
                @else
                <span class="mr-2" onclick="playTestSoundPayOrder('default','default')"><i id="playIcon31" class="fa-solid fa-play"></i></span> | 
                @endif
                <span class="ml-2" data-toggle="modal" data-target="#soundList31Modal"><i class="fa-solid fa-pen"></i></span> 
                Der Klang
            </strong></button>
        </div>

        
    </div>
</div>







<!-- Modal -->
<div class="modal" id="soundList21Modal" abindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Wählen Sie Ihren bevorzugten Sound aus, wenn Sie der Bestellung ein Produkt hinzufügen</strong></h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body" id="soundList21ModalBody">
                <div class="d-flex flex-wrap justify-content-between">
                        <div style="width:49.5%" class="d-flex flex-wrap justify-content-between mb-2">
                            <p onclick="playTestSoundAddProdModal('default','mp3','1')" style="width:80%; margin:0px;" class="btn btn-outline-dark"><i id="playIconModal21O1" class="fa-solid fa-play mr-2"></i> Standardton</p>
                            @if($usrNotiReg21 == Null || ($usrNotiReg21 != Null && $usrNotiReg21->setValue == 1))
                            <button style="width:20%; color:#28a745" class="btn btn-success shadow-none">.</button>
                            @else
                            <button onclick="selectSoundForAddProdOr21('1')" style="width:20%; color:white" class="btn btn-outline-dark shadow-none">.</button>
                            @endif
                        </div>
                    @foreach (userSoundsList::where('id','>','1')->get() as $soundOne)
                        <div style="width:49.5%" class="d-flex flex-wrap justify-content-between mb-2">
                            <p onclick="playTestSoundAddProdModal('{{$soundOne->soundTitle}}','{{$soundOne->soundExt}}','{{$soundOne->id}}')" style="width:80%; margin:0px;" class="btn btn-outline-dark"><i id="playIconModal21O{{$soundOne->id}}" class="fa-solid fa-play mr-2"></i> {{$soundOne->soundTitle}}</p>
                            @if($usrNotiReg21 != Null && $usrNotiReg21->setValue == $soundOne->id)
                            <button style="width:20%; color:#28a745" class="btn btn-success shadow-none">.</button>
                            @else
                            <button onclick="selectSoundForAddProdOr21('{{$soundOne->id}}')" style="width:20%; color:white" class="btn btn-outline-dark shadow-none">.</button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal" id="soundList31Modal" abindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Wählen Sie beim Abschluss einer Bestellung/Zahlung Ihren bevorzugten Ton aus</strong></h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body" id="soundList31ModalBody">
                <div class="d-flex flex-wrap justify-content-between">
                        <div style="width:49.5%" class="d-flex flex-wrap justify-content-between mb-2">
                            <p onclick="playTestSoundPayOrderModal('default','mp3','1')" style="width:80%; margin:0px;" class="btn btn-outline-dark"><i id="playIconModal31O1" class="fa-solid fa-play mr-2"></i> Standardton</p>
                            @if($usrNotiReg31 == Null || ($usrNotiReg31 != Null && $usrNotiReg31->setValue == 1))
                            <button style="width:20%; color:#28a745" class="btn btn-success shadow-none">.</button>
                            @else
                            <button onclick="selectSoundForPayOrderOr31('1')" style="width:20%; color:white" class="btn btn-outline-dark shadow-none">.</button>
                            @endif
                        </div>
                    @foreach (userSoundsList::where('id','>','1')->get() as $soundOne)
                        <div style="width:49.5%" class="d-flex flex-wrap justify-content-between mb-2">
                            <p onclick="playTestSoundPayOrderModal('{{$soundOne->soundTitle}}','{{$soundOne->soundExt}}','{{$soundOne->id}}')" style="width:80%; margin:0px;" class="btn btn-outline-dark"><i id="playIconModal31O{{$soundOne->id}}" class="fa-solid fa-play mr-2"></i> {{$soundOne->soundTitle}}</p>
                            @if($usrNotiReg31 != Null && $usrNotiReg31->setValue == $soundOne->id)
                            <button style="width:20%; color:#28a745" class="btn btn-success shadow-none">.</button>
                            @else
                            <button onclick="selectSoundForPayOrderOr31('{{$soundOne->id}}')" style="width:20%; color:white" class="btn btn-outline-dark shadow-none">.</button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    
    function changeRedGlowAnim(setVal){
        $.ajax({
			url: '{{ route("admin.notificationsActChng") }}',
			method: 'post',
			data: {
				notifyNr: 0,
				notifySetVal: setVal,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#redGlowNotifyChng").load(location.href+" #redGlowNotifyChng>*","");
			},error: (error) => {console.log(error); }
		});			
    }
    function setNewGlowTableColor(){
        $.ajax({
			url: '{{ route("admin.notificationsActSetNewGlowTbColor") }}',
			method: 'post',
			data: {
				newColor: $('#glowTableColor').val(),
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#redGlowNotifyChng").load(location.href+" #redGlowNotifyChng>*","");
			},error: (error) => {console.log(error); }
		});	
    }





    function playTestSoundAddProd(snd,ext){
        $('#playIcon21').attr('class','fa-solid fa-stop');
        if(snd == 'default'){
            $("#soundPlayDiv").html('<audio src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
        }else{
            $("#soundPlayDiv").html('<audio src="storage/sound/'+snd+'.'+ext+'" autoplay="true"></audio>');
        }
        setTimeout( function(){ $('#playIcon21').attr('class','fa-solid fa-play'); }  , 350 );
    }
    function playTestSoundAddProdModal(snd,sndExt,id){
        $('#playIconModal21O'+id).attr('class','fa-solid fa-stop mr-2');
        if(snd == 'default'){
            $("#soundPlayDiv").html('<audio src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
        }else{
            $("#soundPlayDiv").html('<audio src="storage/sound/'+snd+'.'+sndExt+'" autoplay="true"></audio>');
        }
        setTimeout( function(){ $('#playIconModal21O'+id).attr('class','fa-solid fa-play mr-2'); }  , 350 );
    }

    function selectSoundForAddProdOr21(sndId){
        $.ajax({
			url: '{{ route("admin.notificationsActSetNewSound21") }}',
			method: 'post',
			data: {
				newSndId: sndId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#serverVariablesReset").load(location.href+" #serverVariablesReset>*","");
				$("#addProdSound").load(location.href+" #addProdSound>*","");
				$("#soundList21ModalBody").load(location.href+" #soundList21ModalBody>*","");
			},error: (error) => {console.log(error); }
		});
    }
    function changeAddProdSound(setVal){
        $.ajax({
			url: '{{ route("admin.notificationsActChng") }}',
			method: 'post',
			data: {
				notifyNr: 1,
				notifySetVal: setVal,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#addProdSound").load(location.href+" #addProdSound>*","");
			},error: (error) => {console.log(error); }
		});			
    }











    



    function playTestSoundPayOrder(snd,ext){
        $('#playIcon31').attr('class','fa-solid fa-stop');
        if(snd == 'default'){
            $("#soundPlayDiv").html('<audio src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
        }else{
            $("#soundPlayDiv").html('<audio src="storage/sound/'+snd+'.'+ext+'" autoplay="true"></audio>');
        }
        setTimeout( function(){ $('#playIcon31').attr('class','fa-solid fa-play'); }  , 350 );
    }
    function playTestSoundPayOrderModal(snd,sndExt,id){
        $('#playIconModal31O'+id).attr('class','fa-solid fa-stop mr-2');
        if(snd == 'default'){
            $("#soundPlayDiv").html('<audio src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
        }else{
            $("#soundPlayDiv").html('<audio src="storage/sound/'+snd+'.'+sndExt+'" autoplay="true"></audio>');
        }
        setTimeout( function(){ $('#playIconModal31O'+id).attr('class','fa-solid fa-play mr-2'); }  , 350 );
    }
    function selectSoundForPayOrderOr31(sndId){
        $.ajax({
			url: '{{ route("admin.notificationsActSetNewSound31") }}',
			method: 'post',
			data: {
				newSndId: sndId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#serverVariablesReset").load(location.href+" #serverVariablesReset>*","");
				$("#addProdSound").load(location.href+" #addProdSound>*","");
				$("#soundList31ModalBody").load(location.href+" #soundList31ModalBody>*","");
			},error: (error) => {console.log(error); }
		});
    }
    function changePayCloseOrderSound(setVal){
        $.ajax({
			url: '{{ route("admin.notificationsActChng") }}',
			method: 'post',
			data: {
				notifyNr: 2,
				notifySetVal: setVal,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#payCloseOrderSound").load(location.href+" #payCloseOrderSound>*","");
			},error: (error) => {console.log(error); }
		});			
    }
    
</script>