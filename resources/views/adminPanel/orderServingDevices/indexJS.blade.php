<script>
    function closeAddOSDevice(){
        $('#addOSDevice').modal('hide');
        $('#addOSDeviceInput').val('');
    }

    function saveNewDevice(){
        if(!$('#addOSDeviceInput').val()){
            if($('#addOSDeviceError01').is(':hidden')){ $('#addOSDeviceError01').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
				url: '{{ route("orServing.orderServingDevicesSave") }}',
				method: 'post',
				data: {
					devName: $('#addOSDeviceInput').val(),
					_token: '{{csrf_token()}}'
				},
				success: () => {
					$("#showDevicesDiv").load(location.href+" #showDevicesDiv>*","");
                    $('#addOSDeviceInput').val('');
                    if($('#addOSDeviceSucc01').is(':hidden')){ $('#addOSDeviceSucc01').show(100).delay(6000).hide(100); }
				},
				error: (error) => { console.log(error); }
			});
					
        }
    }

    function deleteThisDevicePrep(deviId,deviName){
        $('#deleteDeviceId').val(deviId);
        $('#deleteDeviceConfName').html(deviName);
    }

    function deleteThisDevice(deviId){
        if(!$('#deleteDeviceId').val()){

        }else{
            $.ajax({
                url: '{{ route("orServing.orderServingDevicesDelete") }}',
                method: 'post',
                data: {
                    devId: $('#deleteDeviceId').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#showDevicesDiv").load(location.href+" #showDevicesDiv>*","");
                    $('#deleteDeviceConfModal').modal('hide');
                    $('#deleteDeviceId').val('0');
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    function CopyTheLink(deviId,deviHS){

        navigator.clipboard.writeText("qrorpa.ch/orServingPage?hs="+deviHS).then(() => {
            
            $('#orderServingBtnLink'+deviId).attr('class','btn btn-success shadow-none');
            setTimeout( function(){ 
                $('#orderServingBtnLink'+deviId).attr('class','btn btn-info shadow-none');
			}  , 1000 );

        }, () => {
            /* clipboard write failed */
            $('#orderServingBtnLink'+deviId).attr('class','btn btn-danger shadow-none');
            setTimeout( function(){ 
                $('#orderServingBtnLink'+deviId).attr('class','btn btn-info shadow-none');
			}  , 1000 );
        });
    }

    function showQrCodeOrSer(deviQrCodeName){
        $('#orderServingPageQrCodeImg').attr('src','storage/orderServingQRCode/'+deviQrCodeName);
    }
    function closeorderServingPageQrCode(){
        $('#orderServingPageQrCodeModal').modal('hide');
        $('#orderServingPageQrCodeImg').attr('src','');
    }



    function showProKat(kId,deviId) {
        if ($('#state'+kId+'O'+deviId).val() == 0) {
            $('#prodsKatFoto'+kId+'O'+deviId).show(50);
            $('#state'+kId+'O'+deviId).val(1);

        } else {
            $('#prodsKatFoto'+kId+'O'+deviId).hide(50);
            $('#state'+kId+'O'+deviId).val(0)
        }
    }

    function addCategoryForDevice(deviId, katId){
        $.ajax({
            url: '{{ route("orServing.orderServingDevicesAddKatAccss") }}',
            method: 'post',
            data: {
                devId: deviId,
                kateId: katId,
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {
                respo = $.trim(respo);
                $('#KategoriPlus'+katId+'O'+deviId).html('<i style="color:rgb(39,190,175);" class="fas fa-plus-circle ml-2" onclick="removeCategoryForDevice(\''+deviId+'\',\''+katId+'\',\''+respo+'\')"></i>');
            },
            error: (error) => { console.log(error); }
        });
    }
    function removeCategoryForDevice(deviId, katId, katAccessId){
        $.ajax({
            url: '{{ route("orServing.orderServingDevicesRemoveKatAccss") }}',
            method: 'post',
            data: {
                katAccId: katAccessId,
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $('#KategoriPlus'+katId+'O'+deviId).html('<i style="color:white;" class="fas fa-plus-circle ml-2" onclick="addCategoryForDevice(\''+deviId+'\',\''+katId+'\')"></i> ');
            },
            error: (error) => { console.log(error); }
        });
    }

    function addProductForDevice(deviId, prodId, katId){
        $.ajax({
            url: '{{ route("orServing.orderServingDevicesAddProdAccss") }}',
            method: 'post',
            data: {
                devId: deviId,
                productId: prodId,
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {
                respo = $.trim(respo);
                $('#addProductForCookBtn'+deviId+'O'+prodId).attr('onclick','removeProductForDevice(\''+respo+'\',\''+deviId+'\',\''+prodId+'\',\''+katId+'\')')
                $('#addProductForCookBtn'+deviId+'O'+prodId).attr('class','btn-block btn btn-success shadow-none');
            },
            error: (error) => { console.log(error); }
        });
    }
    function removeProductForDevice(prodAccessId, deviId, prodId, katId){
        $.ajax({
            url: '{{ route("orServing.orderServingDevicesRemoveProdAccss") }}',
            method: 'post',
            data: {
                prodAccId: prodAccessId,
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {
                $('#addProductForCookBtn'+deviId+'O'+prodId).attr('onclick','addProductForDevice(\''+deviId+'\',\''+prodId+'\',\''+katId+'\')');
                $('#addProductForCookBtn'+deviId+'O'+prodId).attr('class','btn-block btn btn-outline-success shadow-none');
            },
            error: (error) => { console.log(error); }
        });
    }

</script>