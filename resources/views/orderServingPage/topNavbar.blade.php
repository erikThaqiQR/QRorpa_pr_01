
<style>  
    @keyframes glowing {
        0% { box-shadow: 0 0 -10px red; background-position: 0 0;}
        40% { box-shadow: 0 0 20px red; }
        60% { box-shadow: 0 0 20px red; }
        100% { box-shadow: 0 0 -10px red; background-position: 1280px 0;}
    }

    .table-glow-adminAlert {
        animation: glowing 1000ms linear infinite;
        border-radius: 6px;
        cursor: pointer;
    }

    
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    input[type=number] {
    -moz-appearance: textfield;
    }
</style>


<nav id="cookPanelNavBar" style="background-color: rgb(39,190,175);" class="navbar navbar-expand-lg d-flex justify-content-between">
    <a class="navbar-brand" href="#"><img src="storage/images/logo_QRorpa_wh.png" style="width:130px; height:auto;" alt=""></a>

    <button style="width:100px; background-color:white; color:rgb(39,190,175); font-size:1.3rem;" class="btn btn-default shadow-none" data-toggle="modal" data-target="#howManyTablesShowModal">
        <strong><i class="fa fa-3x-solid fa-table-cells"></i> <span class="ml-2">: {{ $theDevice->showColPerDev }}</span></strong>
    </button>
</nav>




<!-- Modal -->
<div class="modal" id="howManyTablesShowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: lightgray;">
            <div class="modal-body d-flex flex-wrap">
                <p style="font-size:2.5rem; width:100%; font-weight:bold; color:rgb(72,81,87);" class="text-center">Anzahl Tische auf einer Reihe</p>

                <span style="width: 37%;"></span>
                <input style="width: 26%; font-size:2.5rem; border:none; border-bottom:1px solid rgb(72,81,87); padding:0 !important; line-height:1.1 !important; border-radius:0; height:fit-content; background-color:lightgray; color:rgb(72,81,87);" 
                class="form-control shadow-none text-center" type="number" id="newNrShowInp" value="{{$theDevice->showColPerDev}}">
                <span style="width: 37%;"></span>

                <button class="btn btn-success shadow-none" style="width:100%; margin:5px 0px 5px 0px;" onclick="chngNrOfBlShownCPV2('{{$theDevice->id}}')">
                    <strong>Sparen</strong>
                </button>

                <div class="mt-1 alert alert-danger text-center" style="font-weight: bold; display:none; width:100%;" id="newNrShownError01">
                    Bitte legen Sie eine gültige Nummer fest!
                </div>

                <button style="width:100%; color:rgb(72,81,87);" type="button" class="btn close shadow-none mt-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                </button>
                
            </div>
        </div>
    </div>
</div>

<script>
    function chngNrOfBlShownCPV2(deviId){
        var newNr = parseInt($('#newNrShowInp').val());
        if(newNr <= 0){
            if($('#newNrShownError01').is(':hidden')){ $('#newNrShownError01').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
		    	url: '{{ route("orServing.orderServingDevicesChngShowBlocks") }}',
		    	method: 'post',
		    	data: {
		    		newNrShown: newNr,
                    deviceId: deviId,
		    		_token: '{{csrf_token()}}'
		    	},
		    	success: () => {
		    		location.reload();
		    	},
		    	error: (error) => { console.log(error); }
		    });
        }
    }
</script>