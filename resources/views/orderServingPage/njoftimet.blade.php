<div id="soundsAllOrSerDevice">
    <!-- <audio id="soundsAllCookDesBeep" src="{{ asset('storage/sound/cookPanelNoty.mp3')}}" type="audio/mpeg" autoplay="true"></audio> -->
    <!-- <source id="newRing" src="{{ asset('storage/sound/swiftBeep.mp3')}}"> -->
</div>
<script>
    var intervalId = window.setInterval(function(){
        $.ajax({
			url: '{{ route("orServing.orderServingDevicesCheckNotify") }}',
			method: 'post',
			data: {
				deviId: $('#deviceIdInput').val(),
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				respo = $.trim(respo);
                respo2D = respo.split('--8--');
                $.each( respo2D, function( index, value ) {
					var noty2D = value.split('||');
					if(noty2D[0] == 'cookedByCookPanel'){
						$("#orderServingDivAll").load(location.href+" #orderServingDivAll>*","");
						// sound noty
						$('#soundsAllOrSerDevice').html('<audio id="soundsAllOrSerDeviceBeep" src="https://demo.qrorpa.ch/storage/sound/ting_3.mp3" type="audio/mpeg" autoplay="true"></audio>');            
						var selAudio = $("#soundsAllOrSerDeviceBeep")[0];
						selAudio.play();

					}else if(noty2D[0] == 'changesFromOtherDevice'){
						$("#orderServingDivAll").load(location.href+" #orderServingDivAll>*","");
						// sound noty
						$('#soundsAllOrSerDevice').html('<audio id="soundsAllOrSerDeviceBeep" src="https://demo.qrorpa.ch/storage/sound/ting_3.mp3" type="audio/mpeg" autoplay="true"></audio>');            
						var selAudio = $("#soundsAllOrSerDeviceBeep")[0];
						selAudio.play();

					}else if(noty2D[0] == 'tabOrderDeleted'){
						$("#orderServingDivAll").load(location.href+" #orderServingDivAll>*","");
						// sound noty
						$('#soundsAllOrSerDevice').html('<audio id="soundsAllOrSerDeviceBeep" src="https://demo.qrorpa.ch/storage/sound/ting_3.mp3" type="audio/mpeg" autoplay="true"></audio>');            
						var selAudio = $("#soundsAllOrSerDeviceBeep")[0];
						selAudio.play();

					}else if(noty2D[0] == 'tabOrderPayed'){
						$("#orderServingDivAll").load(location.href+" #orderServingDivAll>*","");
						// sound noty
						$('#soundsAllOrSerDevice').html('<audio id="soundsAllOrSerDeviceBeep" src="https://demo.qrorpa.ch/storage/sound/ting_3.mp3" type="audio/mpeg" autoplay="true"></audio>');            
						var selAudio = $("#soundsAllOrSerDeviceBeep")[0];
						selAudio.play();
					}	
									
                });

				
			},
			error: (error) => { console.log(error); }
		});		
	}, 1500);

	// cookPanelNoty.mp3
</script>