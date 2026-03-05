<?php
    use App\Restorant;
?>
<script type="module">
    import QrScanner from "/js/FP-qr-scanner.min.js";
    function payAllOpenCameraGC(){
        const video = document.getElementById('activateGC-qr-video');

        function setResult(label, qrCodeData) {
            label.textContent = qrCodeData;
            camQrResultTimestamp.textContent = new Date().toString();
            label.style.color = 'teal';
            clearTimeout(label.highlightTimeout);
            label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
        }
        QrScanner.hasCamera()
        .then(hasCamera => camHasCamera.textContent = hasCamera)
        .catch((error) => {
            console.error(error);
        });
        const scanner = new QrScanner(video, qrCodeData => redirect(qrCodeData));
        scanner.start();
        function redirect(qrCodeData){
            if (qrCodeData != null) {
                // qrCodeData2D = qrCodeData.split('|||');
                // alert(qrCodeData.split('|||')[0]);
                // $('#payAllgcValidationCodeInput').val(qrCodeData.split('|||')[0]);

                $.ajax({
					url: '{{ route("giftCard.validateGiftCardToSellKartel") }}',
					method: 'post',
					data: {
						gcIndCode: qrCodeData.split('|||')[0],
						gcHash: qrCodeData.split('|||')[1],
						_token: '{{csrf_token()}}'
					},
					success: (respo) => {
                        respo = $.trim(respo);
                        if(respo != 'gcToSellNotFound' && respo != 'gcToSellAlreadySold'){
                            $('#gcToSellConect').val(respo);
                            $('#payRechnungGCToSellCon').val(respo);
                            
                            $('#addSellAGCToKartelConAlert').attr('style','width:100%; border-radius:5px; display:flex;');
                            $('#addSellAGCModal').modal('show');

                            $('#activateGCCameraScanModal').modal('hide');
                            $('body').attr('class','modal-open');
                            scanner.stop();
                        }else if(respo == 'gcToSellNotFound'){
                            $('#addSellAGCModal').modal('show');
                            $('#addSellAGCModalError').attr('style','width:100%; border-radius:5px; display:flex;');
                            $('#addSellAGCModalError').html('<p style="width:100%; margin:0; " class="text-center"><strong>Die angegebenen Daten sind nicht gültig. Bitte überprüfen Sie die Karte.</strong></p>');
                            $('#activateGCCameraScanModal').modal('hide');
                            $('body').attr('class','modal-open');
                            setTimeout( function(){ 
                                $('#addSellAGCModalError').attr('style','width:100%; border-radius:5px; display:none;');
                            }  , 5000 );
                        }else if(respo == 'gcToSellAlreadySold'){
                            $('#addSellAGCModal').modal('show');
                            $('#addSellAGCModalError').attr('style','width:100%; border-radius:5px; display:flex;');
                            $('#addSellAGCModalError').html('<p style="width:100%; margin:0; " class="text-center"><strong>Diese Geschenkkarte ist bereits verkauft!</strong></p>');
                            $('#activateGCCameraScanModal').modal('hide');
                            $('body').attr('class','modal-open');
                            setTimeout( function(){ 
                                $('#addSellAGCModalError').attr('style','width:100%; border-radius:5px; display:none;');
                            }  , 5000 );
                        }
						// $("#freeProElements").load(location.href+" #freeProElements>*","");
					},
					error: (error) => { console.log(error); }
				});
            }
        }
    }
    var btn = document.getElementById("activateGCOpenCameraModalBtn");
    // Assigning event listeners to the button
    btn.addEventListener("click", payAllOpenCameraGC);

    function searchGCByScanCamera(){
        const video = document.getElementById('searchGC-qr-video');

        function setResult(label, qrCodeData) {
            label.textContent = qrCodeData;
            camQrResultTimestamp.textContent = new Date().toString();
            label.style.color = 'teal';
            clearTimeout(label.highlightTimeout);
            label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
        }
        QrScanner.hasCamera()
        .then(hasCamera => camHasCamera.textContent = hasCamera)
        .catch((error) => {
            console.error(error);
        });
        const scanner = new QrScanner(video, qrCodeData => redirect(qrCodeData));
        scanner.start();
        function redirect(qrCodeData){
            if (qrCodeData != null) {
                $('#gCSearchByScanModal').modal('toggle');
                scanner.stop();

                $.ajax({
					url: '{{ route("giftCard.searchGCByCode") }}',
					method: 'post',
					data: {
						gcId: qrCodeData.split('|||')[0],
						_token: '{{csrf_token()}}'
					},
					success: (respo) => {
                        if(respo == 'giftCardNotFound'){
                            if($('#searchForAGCErr03').is(":hidden")){ $('#searchForAGCErr03').show(10).delay(4000).hide(10); }
                        }else{
                            $("#gcInstancesDiv").html("");
                            var gcShow = '';
                            if(respo['onlinePayStat'] == 0){
                                gcShow += '<div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px; background-color:rgba(252,228,214,255);" class="d-flex flex-wrap justify-content-between p-1">';
                            }else{
                                gcShow += '<div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px;" class="d-flex flex-wrap justify-content-between p-1">';
                            }
                            if (respo['gcType'] == 'chf'){
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">CHF</p>';
                                if (respo['payM'] == 'Online'){
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["payM"]+
                                        '<i style="width:20%; color:rgb(72,81,87);" class="fa-solid fa-qrcode ml-2" onclick="openOnlinePayGCQrCode(\''+respo["id"]+'\')"></i>'+
                                    '</p>';
                                }else{
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["payM"]+'</p>';
                                }
                                if (respo["clName"] != 'empty'){
                                    gcShow += '<p style="width:100%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["clName"];
                                        if (respo["clLastname"] != 'empty'){
                                            gcShow += '<span class="ml-2">'+respo["clLastname"]+'</span>';
                                        }
                                    gcShow += '</p>';
                                }

                                if (respo["clEmail"] != 'empty'){
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["clEmail"]+'</p>';
                                }else{
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>';
                                }
                                if (respo["clPhNr"] != 'empty'){
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["clPhNr"]+'</p>';
                                }else{
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>';
                                }
                                gcShow += '<hr style="width:100%; margin:0px 0 6px 0;">';

                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Summe :</p>';
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Übrig :</p>';
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">'+parseFloat(respo["gcSumInChf"]).toFixed(2)+' CHF</p>';
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">'+parseFloat(parseFloat(respo["gcSumInChf"]) - parseFloat(respo["gcSumInChfUsed"])).toFixed(2)+' CHF</p>';
                                
                                gcShow += '<hr style="width:100%; margin:6px 0 6px 0;">';

                                gcShow += '<form style="width:30%;" method="POST" action="https://qrorpa.ch/giftCardGetReceipt">'+
                                    '{{ csrf_field()}}'+
                                    '<button type="submit" style="width:100%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-1 shadow-none">'+
                                        '<i class="fa-solid fa-xl fa-file-pdf"></i>'+
                                    '</button>'+
                                    '<input id="giftCardId" type="hidden" value="'+respo["id"]+'" name="giftCardId">'+
                                '</form>'+
                                '<p onclick="showGCBillQrCode(\''+respo["id"]+'\')" style="width:30%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center pt-1">'+
                                    '<i class="fa-solid fa-xl fa-qrcode"></i>'+
                                '</p>'+
                                '<p onclick="deleteGCPrep(\''+respo["id"]+'\',\''+respo["idnShortCode"]+'\',\''+respo["refId"]+'\')" style="width:30%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-1">'+
                                    '<strong><i class="fa-solid fa-xl fa-trash-can"></i> </strong>'+
                                '</p>'+
                                '<p onclick="showGCBalanceStatus(\''+respo["id"]+'\',\'0\')" style="width:50%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-2">'+
                                    '<strong><i class="fa-regular fa-rectangle-list"></i> Bilanz</strong>'+
                                '</p>'+
                                '<p style="width:50%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-2" onclick="openGCQrCode(\''+respo["id"]+'\')">'+
                                    '<i style="color:rgb(72,81,87);" class="fa-solid fa-qrcode mr-2"></i>'+respo["idnShortCode"]+
                                '</p>'+

                                '<hr style="width:100%; margin:6px 0 6px 0;">';

                                var buyDt = respo["created_at"];
                                var expDt = respo["expirationDate"];
                                buyDt = buyDt.split(' ')[0];
                                buyDt = buyDt.split('-');
                                expDt = expDt.split(' ')[0];
                                expDt = expDt.split('-');
                              
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verkaufsdatum</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verfallsdatum</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">'+buyDt[2]+'.'+buyDt[1]+'.'+buyDt[0]+'</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">'+expDt[2]+'.'+expDt[1]+'.'+expDt[0]+'</p>';
                    

                            }

                            gcShow += '</div>';
                            $("#gcInstancesDiv").append(gcShow);

                            $('#searchGCByCodeBtn').attr('onclick','cancelSearchGCByCode()');
                            $('#searchGCByCodeBtn').attr('class','btn btn-danger shadow-none');
                            $('#searchGCByCodeBtn').html('<i class="fa-solid fa-x"></i>');

                            $('#searchGCByCodeInp').attr('style','font-size:1rem; background-color: rgba(40,167,69,0.22);');

                            $('#searchGCByCodeInp').val(respo["idnShortCode"]);
                        }

						
					},
					error: (error) => { console.log(error); }
				});
            }

          
        }

    }
    var btn = document.getElementById("gCSearchByScanModalBtn");
    // Assigning event listeners to the button
    btn.addEventListener("click", searchGCByScanCamera);
</script>


<script>

    function showNewDatePicked(){
        var newDt = $('#pickExpirationDate01').val();
        var newDt2D = newDt.split('-');
        $('#showDateExpr').html(newDt2D[2]+'.'+newDt2D[1]+'.'+newDt2D[0]);
    }

    // Show Product GC
    function showProductGC(){
        if($('#gcFinalTypeSelected').val() == 'chf'){
            $('#gcInChfBtn').attr('class','btn btn-outline-dark shadow-none');
            $('#gcInChfDiv').attr('style','width:100%; display:none;');
        }
        $('#gcFinalTypeSelected').val('product');
        $('#gcInProductBtn').attr('class','btn btn-dark shadow-none');
        $('#gcInProductDiv').attr('style','width:100%; display:flex;');

       
        if($('#gcFinalInChf').val() != 0){
            $('#setChfGC'+$('#gcFinalInChf').val()+'Btn').attr('class','btn btn-outline-success shadow-none mb-1');
            $('#gcFinalInChf').val(0);
        }
    }
    // Show CHF GC
    function showChfGC(){
        if($('#gcFinalTypeSelected').val() == 'product'){
            $('#gcInProductBtn').attr('class','btn btn-outline-dark shadow-none');
            $('#gcInProductDiv').attr('style','width:100%; display:none;');
        }
        $('#gcFinalTypeSelected').val('chf');
        $('#gcInChfBtn').attr('class','btn btn-dark shadow-none');
        $('#gcInChfDiv').attr('style','width:100%; display:flex;');
    }

//----------------------------------------------------------------------------------------------------------------------------

    // Set GC in CHF static amount
    function setChfGCBtn(chfAmount){
        if($('#gcFinalInChf').val() != 0){
            $('#setChfGC'+$('#gcFinalInChf').val()+'Btn').attr('class','btn btn-outline-success shadow-none mb-1');
        }
        $('#setChfGC'+chfAmount+'Btn').attr('class','btn btn-success shadow-none mb-1');

        $('#gcFinalInChf').val(chfAmount);
        $('#gcChfInputId').val('');
        $('#gcChfInputId').attr('style','width:100%; border-top:none; border-left:none; border-right:none;');
    }
    // Set GC in CHF variable amount
    function setChfGCInput(){
        if($('#gcChfInputId').val()){
            if($('#gcFinalInChf').val() != 0){
                $('#setChfGC'+$('#gcFinalInChf').val()+'Btn').attr('class','btn btn-outline-success shadow-none mb-1');
            }
            $('#gcChfInputId').attr('style','width:100%; border-top:none; border-left:none; border-right:none; background-color: rgba(33,136,56,255); color:white;');
            var chfInpAmount = $('#gcChfInputId').val();
            $('#gcFinalInChf').val(chfInpAmount);
        }else{
            $('#gcChfInputId').attr('style','width:100%; border-top:none; border-left:none; border-right:none;');
        }
    }

//----------------------------------------------------------------------------------------------------------------------------

    // Select Product in Product GC
    function selectThisProductForGC(prodId){
        
        $('#gcFinalProdSelId').val(prodId);
    }

//----------------------------------------------------------------------------------------------------------------------------

    // show Gift card 
    function openGCQrCode(gcId){
        $('#giftCardUseQRCodePic').attr('src','storage/giftcardQRCode/GC'+gcId+'.png');
        $('#giftCardUseQRCodeModal').modal('show');
    }

    function closeGiftCardUseQRCodeModal(){
        $('#giftCardUseQRCodePic').attr('src','#');
        $('#giftCardUseQRCodeModal').modal('hide');
    }

    function showGCBillQrCode(gcId){
        $('#giftCardBillQRCodePic').attr('src','storage/giftCardBillQRCode/GCBillQrC'+gcId+'.png');
        $('#giftCardBillQRCodeModal').modal('show');
    }
    function closeGiftCardBillQRCodeModal(gcId){
        $('#giftCardBillQRCodePic').attr('src','#');
        $('#giftCardBillQRCodeModal').modal('hide');
    }

//----------------------------------------------------------------------------------------------------------------------------
    // Pay CASH

    function payGCCashPayPrep(){
        if($('#gcFinalInChf').val() > 0){
            if($('#pickExpirationDate01').val() == ''){
                if($('#addSellAGCModalErr02').is(':hidden')){ $('#addSellAGCModalErr02').show(100).delay(4500).hide(100); }
            }else{
                $('#payGCBtn1').prop('disabled', true);
                $('#amToPay').html(parseFloat($('#gcFinalInChf').val()).toFixed(2));
                $('#payGCCashPrepModal').modal('show');
            }
        }else{
            if($('#addSellAGCModalErr01').is(':hidden')){ $('#addSellAGCModalErr01').show(100).delay(4500).hide(100); }
        }
    }
    function closePayGCCashPrepModal(){
        $("#payGCCashPrepBody").load(location.href+" #payGCCashPrepBody>*","");
        $('#payGCBtn1').prop('disabled', false);
    }
    function addClPay(addVal){
        addVal = parseFloat(addVal);
        var prevVal = parseFloat($('#amByClient').html());

        var newVal = parseFloat(addVal + prevVal);
        $('#amByClient').html(parseFloat(newVal).toFixed(2));

        var totToPay = parseFloat($('#amToPay').html());
        var toRet = parseFloat(newVal - totToPay);
        if(toRet >= 0){
            $('#amClientReturn').html(parseFloat(toRet).toFixed(2))
        }
    }
    function payAllProdsCashDel(){
        $('#amByClient').html('0.00');
        $('#amClientReturn').html('--.--');
    }
    function showPayAllPCMDiv3(){
        payAllProdsCashDel();
        $('#showPayAllPCMDiv3Btn').hide(100);
        $('#payAllPCMDiv3').css('display','flex');
        $('.currBtn').prop('disabled', true);
    }
    function cancelPayAllPCMDiv3(){
        $('#amByClient').html('0.00');
        $('#amClientReturn').html('--.--');
        $('#payAllPCMDiv3').css('display','none');
        $('#showPayAllPCMDiv3Btn').show(100);
        $('#payAllPCMDiv3Inp').val('');

        $('.currBtn').prop('disabled', false);
    }
    function newValGivByCl(){
        if(!$('#payAllPCMDiv3Inp').val()){
            $('#amByClient').html('0.00');
            $('#amClientReturn').html('--.--');
        }else{
            var newVal = parseFloat($('#payAllPCMDiv3Inp').val());
            if($.isNumeric(newVal) && newVal >= 0){
                var totToPay = parseFloat($('#amToPay').html());
                var toRet = parseFloat(newVal - totToPay);
                if(toRet >= 0){
                    $('#amByClient').html(parseFloat(newVal).toFixed(2));
                    $('#amClientReturn').html(parseFloat(toRet).toFixed(2))
                }else{
                    $('#amByClient').html('0.00');
                    $('#amClientReturn').html('--.--');
                }
            }else{
                if($('#payAllPCMDiv3Err1').is(':hidden')){ $('#payAllPCMDiv3Err1').show(100).delay(4000).hide(100); }
            }
        }
    }
    function payGCCashPay(){
        if($('#gcFinalInChf').val() > 0){
            if(!$('#clientDataName').val()){ clNameSend = 'empty'; }
            else{ clNameSend = $('#clientDataName').val(); }

            if(!$('#clientDataLastname').val()){ clLastnameSend = 'empty'; }
            else{ clLastnameSend = $('#clientDataLastname').val(); }

            if(!$('#clientDataEmail').val()){ clEmailSend = 'empty'; }
            else{ clEmailSend = $('#clientDataEmail').val(); }

            if(!$('#clientDataPhoneNr').val()){ clphoneNrSend = 'empty'; }
            else{ clphoneNrSend = $('#clientDataPhoneNr').val(); }

            $.ajax({
                url: '{{ route("giftCard.giftCardRegCashCardPay") }}',
                method: 'post',
                data: {
                    gcType: $('#gcFinalTypeSelected').val(),
                    gcValueInChf: $('#gcFinalInChf').val(),
                    gcClName: clNameSend,
                    gcCllastname: clLastnameSend,
                    gcClEmail: clEmailSend,
                    gcClPhoneNr: clphoneNrSend,
                    gcPayMeth: 'Cash',
                    gcExDate: $('#pickExpirationDate01').val(),
                    gcConToKartel: $('#gcToSellConect').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $('#payGCCashPrepModal').modal('hide');
                    $('#addSellAGCModal').modal('hide');
                    closePayGCCashPrepModal();
                    $("#gcInstancesDiv").load(location.href+" #gcInstancesDiv>*","");
                    $("#gcInChfDiv").load(location.href+" #gcInChfDiv>*","");
                    $('#pickExpirationDate01').val('');
                    $("#addSellAGCModalBodySubPart3").load(location.href+" #addSellAGCModalBodySubPart3>*","");
                    $("#yearsExpDateForGCDiv").load(location.href+" #yearsExpDateForGCDiv>*","");

                    $('#gcToSellConect').val(0);
                    $('#payRechnungGCToSellCon').val(0);
                    $('#addSellAGCToKartelConAlert').attr('style','width:100%; border-radius:5px; display:none;');
                },
                error: (error) => { console.log(error); }
            });
        }else{
            if($('#addSellAGCModalErr01').is(':hidden')){ $('#addSellAGCModalErr01').show(100).delay(4500).hide(100); }
        }
    }

//----------------------------------------------------------------------------------------------------------------------------

    // Pay CARD

    function payGCCardPay(){
        if($('#gcFinalInChf').val() > 0){
            if($('#pickExpirationDate01').val() == ''){
                if($('#addSellAGCModalErr02').is(':hidden')){ $('#addSellAGCModalErr02').show(100).delay(4500).hide(100); }
            }else{
                if(!$('#clientDataName').val()){ clNameSend = 'empty'; }
                else{ clNameSend = $('#clientDataName').val(); }

                if(!$('#clientDataLastname').val()){ clLastnameSend = 'empty'; }
                else{ clLastnameSend = $('#clientDataLastname').val(); }

                if(!$('#clientDataEmail').val()){ clEmailSend = 'empty'; }
                else{ clEmailSend = $('#clientDataEmail').val(); }

                if(!$('#clientDataPhoneNr').val()){ clphoneNrSend = 'empty'; }
                else{ clphoneNrSend = $('#clientDataPhoneNr').val(); }

                $('#payGCBtn1').prop('disabled', true);
                $('#payGCBtn2').prop('disabled', true);
                $('#payGCBtn3').prop('disabled', true);
                $('#payGCBtn4').prop('disabled', true);
                if('{{Restorant::find(Auth::user()->sFor)->hasPOS}}' == 1){
                    $.ajax({
                        url: '{{ route("payTec.Connect") }}',
                        method: 'post',
                        data: {_token: '{{csrf_token()}}'},
                        success: (res) => {
                            if($('#payGCPayAtPOSAlert').is(':hidden')){ $('#payGCPayAtPOSAlert').show(50); }

                            $.ajax({
                                url: '{{ route("payTec.Transact") }}',
                                method: 'post',
                                timeout: 600000, // Sets a 10-minute timeout (milliseconds)
                                data: {
                                    totalChf : parseFloat($('#gcFinalInChf').val()).toFixed(2),
                                    _token: '{{csrf_token()}}'
                                },
                                success: (resTransact) => {
                                    var resJSON = $.parseJSON(resTransact);
                                    // if((resJSON.CardholderText == 'Transaction OK' || resJSON.CardholderText == 'Verarbeitung OK') && (resJSON.AttendantText == 'Transaction OK' || resJSON.AttendantText == 'Verarbeitung OK')){    
                                    if(resJSON.TrxResult == 0){

                                        payGCCardPaySendRegister(clNameSend, clLastnameSend, clEmailSend, clphoneNrSend, resTransact);

                                    }else{
                                        registerPayTecErrorData(resTransact);
                                        alert('fail register  -- '+resJSON.CardholderText+' '+resJSON.AttendantText);
                                        // alert('---------------------------------');
                                    }
                                },error: (error) => {
                                    registerPayTecErrorData(error);
                                    alert(error);
                                }
                            });

                        },error: (error) => {
                            registerPayTecErrorData(error);
                            alert(error);
                        }
                    });
                }else{
                    payGCCardPaySendRegister(clNameSend, clLastnameSend, clEmailSend, clphoneNrSend, 'none');
                }
                $('#payGCBtn1').prop('disabled', false);
                $('#payGCBtn2').prop('disabled', false);
                $('#payGCBtn3').prop('disabled', false);
                $('#payGCBtn4').prop('disabled', false);
                
            }
        }else{
            if($('#addSellAGCModalErr01').is(':hidden')){ $('#addSellAGCModalErr01').show(100).delay(4500).hide(100); }
        }
    }

    function payGCCardPaySendRegister(clNameSend, clLastnameSend, clEmailSend, clphoneNrSend, resTrx){
        $.ajax({
            url: '{{ route("giftCard.giftCardRegCashCardPay") }}',
            method: 'post',
            data: {
                gcType: $('#gcFinalTypeSelected').val(),
                gcValueInChf: $('#gcFinalInChf').val(),
                gcClName: clNameSend,
                gcCllastname: clLastnameSend,
                gcClEmail: clEmailSend,
                gcClPhoneNr: clphoneNrSend,
                gcPayMeth: 'Card',
                gcExDate: $('#pickExpirationDate01').val(),
                gcConToKartel: $('#gcToSellConect').val(),
                payTecTrx: resTrx,
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $('#addSellAGCModal').modal('hide');
                $("#gcInstancesDiv").load(location.href+" #gcInstancesDiv>*","");
                $("#gcInChfDiv").load(location.href+" #gcInChfDiv>*","");
                $('#pickExpirationDate01').val('');
                $("#addSellAGCModalBodySubPart3").load(location.href+" #addSellAGCModalBodySubPart3>*","");
                $("#yearsExpDateForGCDiv").load(location.href+" #yearsExpDateForGCDiv>*","");

                $('#gcToSellConect').val(0);
                $('#payRechnungGCToSellCon').val(0);
                $('#addSellAGCToKartelConAlert').attr('style','width:100%; border-radius:5px; display:none;');
            },
            error: (error) => { console.log(error); }
        });
    }

    function registerPayTecErrorData(resTrx){
        $.ajax({
            url: '{{ route("payTec.collectErrorLog") }}',
            method: 'post',
            data: {
                payTecTrx: resTrx,
                _token: '{{csrf_token()}}'
            },
            success: () => { 
                
            },
            error: (error) => { console.log(error); }
        });
    }

//----------------------------------------------------------------------------------------------------------------------------
   
    // Pay ONLINE
    
    function payGCOnlinePay(){
        if($('#gcFinalInChf').val() > 0){
            if($('#pickExpirationDate01').val() == ''){
                if($('#addSellAGCModalErr02').is(':hidden')){ $('#addSellAGCModalErr02').show(100).delay(4500).hide(100); }
            }else{
                if(!$('#clientDataName').val()){ clNameSend = 'empty'; }
                else{ clNameSend = $('#clientDataName').val(); }

                if(!$('#clientDataLastname').val()){ clLastnameSend = 'empty'; }
                else{ clLastnameSend = $('#clientDataLastname').val(); }

                if(!$('#clientDataEmail').val()){ clEmailSend = 'empty'; }
                else{ clEmailSend = $('#clientDataEmail').val(); }

                if(!$('#clientDataPhoneNr').val()){ clphoneNrSend = 'empty'; }
                else{ clphoneNrSend = $('#clientDataPhoneNr').val(); }

                $.ajax({
                    url: '{{ route("giftCard.giftCardRegOnlinePay") }}',
                    method: 'post',
                    data: {
                        gcType: $('#gcFinalTypeSelected').val(),
                        gcValueInChf: $('#gcFinalInChf').val(),
                        gcClName: clNameSend,
                        gcCllastname: clLastnameSend,
                        gcClEmail: clEmailSend,
                        gcClPhoneNr: clphoneNrSend,
                        gcPayMeth: 'Online',
                        gcExDate: $('#pickExpirationDate01').val(),
                        gcConToKartel: $('#gcToSellConect').val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: (response) => {
                        response = $.trim(response);
                        $('#addSellAGCModal').modal('hide');
                        $("#gcInstancesDiv").load(location.href+" #gcInstancesDiv>*","");
                        $("#gcInChfDiv").load(location.href+" #gcInChfDiv>*","");
                        $('#pickExpirationDate01').val('');
                        $("#addSellAGCModalBodySubPart3").load(location.href+" #addSellAGCModalBodySubPart3>*","");
                        $("#yearsExpDateForGCDiv").load(location.href+" #yearsExpDateForGCDiv>*","");

                        $('#redirectClientToOnlineGCPayQRCode').attr('src','storage/giftcardOnlinePayQRCode/'+response);
                        $('#redirectClientToOnlineGCPayModal').modal('show');

                        $('#gcToSellConect').val(0);
                        $('#payRechnungGCToSellCon').val(0);
                        $('#addSellAGCToKartelConAlert').attr('style','width:100%; border-radius:5px; display:none;');
                    },
                    error: (error) => { console.log(error); }
                });
            }
        }else{
            if($('#addSellAGCModalErr01').is(':hidden')){ $('#addSellAGCModalErr01').show(100).delay(4500).hide(100); }
        }
    }
    function closeRedirectClientToOnlineGCPayModal(){
        $('#redirectClientToOnlineGCPayModal').modal('hide');
        $('#redirectClientToOnlineGCPayQRCode').attr('src','');
    }

    function openOnlinePayGCQrCode(gcId){
        $('#redirectClientToOnlineGCPayQRCode').attr('src','storage/giftcardOnlinePayQRCode/GCOnlinePay'+gcId+'.png');
        $('#redirectClientToOnlineGCPayModal').modal('show');
    }
//----------------------------------------------------------------------------------------------------------------------------

    // Pay AUF RECHNUNG

    function closePayGCAufRechnungModal(){
        $('#payGCAufRechnungModal').modal('hide');
        $("#clientsRechnungList").load(location.href+" #clientsRechnungList>*","");
        $("#payGCAufRechnungModalBody").load(location.href+" #payGCAufRechnungModalBody>*","");
    }

    function payGCFaturePayPrep(){
        if($('#gcFinalInChf').val() > 0){
            if($('#pickExpirationDate01').val() == ''){
                if($('#addSellAGCModalErr02').is(':hidden')){ $('#addSellAGCModalErr02').show(100).delay(4500).hide(100); }
            }else{
                if(!$('#clientDataName').val()){ clNameSend = 'empty'; }
                else{ 
                    clNameSend = $('#clientDataName').val(); 
                    $('#payRechnungClName').val(clNameSend);
                    $('#RechnungNameInp').val(clNameSend);
                }

                if(!$('#clientDataLastname').val()){ clLastnameSend = 'empty'; }
                else{ 
                    clLastnameSend = $('#clientDataLastname').val(); 
                    $('#payRechnungClLastname').val(clLastnameSend);
                    $('#RechnungVornameInp').val(clLastnameSend);
                }

                if(!$('#clientDataEmail').val()){ clEmailSend = 'empty'; }
                else{ 
                    clEmailSend = $('#clientDataEmail').val(); 
                    $('#payRechnungClEmail').val(clEmailSend);
                    $('#RechnungEmailInp').val(clEmailSend);
                }

                if(!$('#clientDataPhoneNr').val()){ clphoneNrSend = 'empty'; }
                else{ 
                    clphoneNrSend = $('#clientDataPhoneNr').val(); 
                    $('#payRechnungClPhoneNr').val(clphoneNrSend);
                }
                $('#payRechnungValueInCHF').val(parseFloat($('#gcFinalInChf').val()).toFixed(2));

                $('#rechnungPayExpiringDate').val($('#pickExpirationDate01').val());

                $('#addSellAGCModal').modal('hide');
                $('#payGCAufRechnungModal').modal('show');
            }
        }else{
            if($('#addSellAGCModalErr01').is(':hidden')){ $('#addSellAGCModalErr01').show(100).delay(4500).hide(100); }
        }
    }

    function closepayAllRechnungsignatureModal(){
        $('#payAllRechnungsignatureModal').modal('hide');
        $('body').addClass('modal-open');
    }

    function selectClRechnung(clId){
        $('#payAllPRechnungMDiv1').attr('style','display:none;');
        $('#payAllPRechnungMDiv2').attr('style','display:none;');
        $('#payAllPRechnungMDiv3').attr('style','display:none;');
        $('#payAllPRechnungMDiv4').attr('style','display:none;');

        $('#cancelClSelectRechnungAllBtn').show(10);
        $('#payAllRechnungCommentClientDiv').show(10);
        $('#finishPayRechnungAllCLBtn').show(10);
        
        if($('#usedAndExistingClientIdAll').val() != 0){
            $('#clientOneRechnungAll'+$('#usedAndExistingClientIdAll').val()).removeClass('btn-dark');
            $('#clientOneRechnungAll'+$('#usedAndExistingClientIdAll').val()).addClass('btn-outline-dark');
        }
        $('#clientOneRechnungAll'+clId).removeClass('btn-outline-dark');
        $('#clientOneRechnungAll'+clId).addClass('btn-dark');
       
        $('#usedAndExistingClientAll').val('1');
        $('#usedAndExistingClientIdAll').val(clId);
    }
    function cancelClSelectRechnungAll(){
        $('#payAllPRechnungMDiv1').attr('style','display:flex;');
        $('#payAllPRechnungMDiv2').attr('style','display:flex;');
        $('#payAllPRechnungMDiv3').attr('style','display:flex;');
        $('#payAllPRechnungMDiv4').attr('style','display:flex;');

        $('#cancelClSelectRechnungAllBtn').hide(10);
        $('#payAllRechnungCommentClientDiv').hide(10);
        $('#finishPayRechnungAllCLBtn').hide(10);

        $('#clientOneRechnungAll'+$('#usedAndExistingClientIdAll').val()).removeClass('btn-dark');
        $('#clientOneRechnungAll'+$('#usedAndExistingClientIdAll').val()).addClass('btn-ouline-dark');

        $('#usedAndExistingClientAll').val('0');
        $('#usedAndExistingClientIdAll').val('0');
    }
    function asveClientForAufRechnungAll(){
        if($("#saveClForARechnungAll").is(':checked')){
            $('#saveClForARechnungAllVal').val('1');
        }else{
            $('#saveClForARechnungAllVal').val('0');
        }
    }

    function payGCRechnungVerifyTelSendNr(){
        if(!$('#RechnungTelInp').val()){
            if($('#payAllProdsRechnungModalErr08').is(":hidden")){ $('#payAllProdsRechnungModalErr08').show(100).delay(4000).hide(100); }
        }else{
            var pNr = $('#RechnungTelInp').val().replace(/ /g,'');
            
            if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                pNr = '0'+pNr.toString().slice(3);
            }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                pNr = '0'+pNr.toString().slice(2);
            }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                pNr = '0'+pNr.toString().slice(4);
            }

            if(pNr.length < 9 || pNr.length > 10){
                if($('#payAllProdsRechnungModalErr09').is(":hidden")){ $('#payAllProdsRechnungModalErr09').show(100).delay(4000).hide(100); }
            }else{    
                $.ajax({
					url: '{{ route("admin.payAllVerifyTelSendNr") }}',
					method: 'post',
					data: {
						phoneNr: pNr,
						_token: '{{csrf_token()}}'
					},
					success: (respo) => {
                        respo = $.trim(respo);
                        if(respo == 'payAllVerifyTelSendNrFail'){
                            if($('#payAllProdsRechnungModalErr10').is(":hidden")){ $('#payAllProdsRechnungModalErr10').show(100).delay(4000).hide(100); }
                        }else{
                            $('#telVerCodeFromSer').val(respo);
                            $('#payAllPRMDiv1TelVer01').hide(100);
                            $('#payAllPRMDiv1TelVer02').show(100);
                            $('#RechnungTelInp').val(pNr);
                            if(pNr == '0763270293' || pNr == '0763251809' || pNr == '0763459941' || pNr == '0763469963' || pNr == '760000000' || pNr == '0760000000'){
                                $('#telVerCodeDemoShow').html('<strong>"Testing" Demo code: '+respo+'</strong>');
                                $('#telVerCodeDemoShow').show(100);
                                $('#RechnungTelCodeInp').val(respo);
                            }
                        }
					},
					error: (error) => { console.log(error); }
				});
            }
        }
    }
    function payAllVerifyTelSendCode(){
        if(!$('#RechnungTelCodeInp').val()){
            if($('#payAllProdsRechnungModalErr11').is(":hidden")){ $('#payAllProdsRechnungModalErr11').show(100).delay(4000).hide(100); }
        }else{
            var codeFromCl = $('#RechnungTelCodeInp').val();
            if(codeFromCl.length != 6){
                if($('#payAllProdsRechnungModalErr12').is(":hidden")){ $('#payAllProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(codeFromCl != $('#telVerCodeFromSer').val()){
                if($('#payAllProdsRechnungModalErr12').is(":hidden")){ $('#payAllProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(codeFromCl == $('#telVerCodeFromSer').val()){
                $('#payAllPRMDiv1TelVer02').hide(100);
                $('#payAllProdsRechnungModalSucc01').html('Telefonnummer "'+$('#RechnungTelInp').val()+'" erfolgreich verifiziert');
                $('#payAllProdsRechnungModalSucc01').show(100);
                $('#telVerCodeDemoShow').hide(100);
                $('#telVerValidationStatus').val("1");
            }
        }
    }

    var padUsed = 0;
    var signaturePad = $('#signaturePad').signature({ syncField: '#signature64', syncFormat: 'PNG', });
    var signaturePad2 = $('#signaturePad2').signature({ syncField: '#signature64', syncFormat: 'PNG', });
    var signaturePad3 = $('#signaturePad3').signature({ syncField: '#signature64', syncFormat: 'PNG', });
    var signaturePad4 = $('#signaturePad4').signature({ syncField: '#signature64', syncFormat: 'PNG', });
    var signaturePad5 = $('#signaturePad5').signature({ syncField: '#signature64', syncFormat: 'PNG', });
    $('#clearSignature').click(function(e) {
        e.preventDefault();
        padUsed++;

        // signaturePad.signature('clear');
        $("#signature64").val('');
        $("#signaturePrew").attr('src','');

        $('#payAllRechnungsignatureModalAttempt').html('Versuch '+parseInt(padUsed + parseInt(1))+'/5')
        if(padUsed == 1){
            $('#signaturePad').hide(100);
            $('#signaturePad2').show(100);
        }else if(padUsed == 2){
            $('#signaturePad2').hide(100);
            $('#signaturePad3').show(100);
        }else if(padUsed == 3){
            $('#signaturePad3').hide(100);
            $('#signaturePad4').show(100);
        }else if(padUsed == 4){
            $('#signaturePad4').hide(100);
            $('#signaturePad5').show(100);
        }
    });
    function clickSaveSignaturePARechnung(){
        padUsed++;
        var image = new Image();
        image = document.getElementById("signature64").value;
        document.getElementById('signaturePrew').src = image;
        $('#payAllRechnungsignatureModalAttempt').html('Versuch '+parseInt(padUsed + parseInt(1))+'/5')
        closepayAllRechnungsignatureModal();

        if(padUsed == 1){
            $('#signaturePad').hide(100);
            $('#signaturePad2').show(100);
        }else if(padUsed == 2){
            $('#signaturePad2').hide(100);
            $('#signaturePad3').show(100);
        }else if(padUsed == 3){
            $('#signaturePad3').hide(100);
            $('#signaturePad4').show(100);
        }else if(padUsed == 4){
            $('#signaturePad4').hide(100);
            $('#signaturePad5').show(100);
        }
    }
    
    function finishPayRechnungAll(){
        if(!$('#RechnungFirmaInp').val()){
            if($('#payAllProdsRechnungModalErr01').is(":hidden")){ $('#payAllProdsRechnungModalErr01').show(100).delay(4000).hide(100); }
        }else if($('#telVerValidationStatus').val() == '0'){
            if($('#payAllProdsRechnungModalErr02').is(":hidden")){ $('#payAllProdsRechnungModalErr02').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungNameInp').val()){
            if($('#payAllProdsRechnungModalErr03').is(":hidden")){ $('#payAllProdsRechnungModalErr03').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungVornameInp').val()){
            if($('#payAllProdsRechnungModalErr04').is(":hidden")){ $('#payAllProdsRechnungModalErr04').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungStrNrInp').val() || !$('#RechnungPlzOrtInp').val()){
            if($('#payAllProdsRechnungModalErr05').is(":hidden")){ $('#payAllProdsRechnungModalErr05').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungLandInp').val()){
            if($('#payAllProdsRechnungModalErr06').is(":hidden")){ $('#payAllProdsRechnungModalErr06').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungEmailInp').val()){
            if($('#payAllProdsRechnungModalErr07').is(":hidden")){ $('#payAllProdsRechnungModalErr07').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungDaysToPayInp').val()){
            if($('#payAllProdsRechnungModalErr13').is(":hidden")){ $('#payAllProdsRechnungModalErr13').show(100).delay(4000).hide(100); }
        }else if($('#signaturePrew').attr('src') == ''){
            if($('#payAllProdsRechnungModalErr15').is(":hidden")){ $('#payAllProdsRechnungModalErr15').show(100).delay(4000).hide(100); }
        }else{
            var emailInp = $('#RechnungEmailInp').val();
            var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
            var dayToPay = $('#RechnungDaysToPayInp').val()
            if(!pattern.test(emailInp)){
                if($('#payAllProdsRechnungModalErr12').is(":hidden")){ $('#payAllProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(Math.floor(dayToPay) != dayToPay || !$.isNumeric(dayToPay) || dayToPay < 1) {
                if($('#payAllProdsRechnungModalErr14').is(":hidden")){ $('#payAllProdsRechnungModalErr14').show(100).delay(4000).hide(100); } 
            }else{
                // success
                $('#finishPayRechnungAllBtn').prop('disabled', true);
                // set the variable for payment is Not Selective
                $('#OrQRCodePayIsSelective').val('0');

                $('#payAllPRechnungMDiv4BTNs').html('<img src="storage/gifs/loading2.gif" style="width:20%; margin-left:40%;" alt="">');
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
                $('#payGCRechnungForm').submit();
            }
        }
    }

    function finishPayRechnungAllCL(){
        // set the variable for payment is Not Selective
        $('#OrQRCodePayIsSelective').val('0');
        $('#cancelClSelectRechnungAllBtn').prop('disabled', true);
        $('#payAllRechnungCommentClientDiv').prop('disabled', true);
        $('#finishPayRechnungAllCLBtn').prop('disabled', true);
        $('#finishPayRechnungAllCLBtn').html('<img src="storage/gifs/loading2.gif" style="width:30px; height:auto;" alt="">');
        $('#payGCRechnungForm').submit();
    }

//----------------------------------------------------------------------------------------------------------------------------

    function closeExpiredAndFullyUsedGCModal(){
        $('#expiredAndFullyUsedGCBody').html('');
    }
    function showExpAUsedGC(){
        $.ajax({
			url: '{{ route("giftCardWa.giftCardShowExpAndUsed") }}',
			method: 'post',
            dataType: 'json',
			data: {
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				$.each(respo, function(index, value){
                    if (value.onlinePayStat == 0){
                        showGc = '<div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px; background-color:rgba(252,228,214,255);" class="d-flex flex-wrap justify-content-between p-1">';
                    }else{
                        showGc = '<div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px;" class="d-flex flex-wrap justify-content-between p-1">';
                    }

                    if (value.gcType == 'chf'){
                        showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">CHF</p>'+
                        '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+value.payM+'</p>';
                        if (value.clName != 'empty'){
                            showGc += '<p style="width:100%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+value.clName;
                            if (value.clLastname != 'empty'){
                                showGc += '<span class="ml-2">'+value.clLastname+'</span>';
                            }
                            showGc += '</p>';
                        }
                        if (value.clEmail != 'empty'){
                            showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+value.clEmail+'</p>';
                        }else{
                            showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>';
                        }
                        if (value.clPhNr != 'empty'){
                            showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+value.clPhNr+'</p>';
                        }else{
                            showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>';
                        }
                        showGc += '<hr style="width:100%; margin:0px 0 6px 0;">';

                        showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Summe :</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Übrig :</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">'+parseFloat(value.gcSumInChf).toFixed(2)+' CHF</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">'+parseFloat(parseFloat(value.gcSumInChf) - parseFloat(value.gcSumInChfUsed)).toFixed(2)+' CHF</p>';

                        showGc += '<hr style="width:100%; margin:6px 0 6px 0;">';

                        showGc += '<p style="width:100%; font-weight:bold; font-size:1.3rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center pt-1">'+
                                    '<i style="color:rgb(72,81,87);" class="fa-solid fa-qrcode mr-2"></i>'+value.idnShortCode+'</p>'+
                                '<p onclick="showGCBalanceStatus(\''+value.id+'\',\'1\')" style="width:100%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-1">'+
                                    '<strong><i class="fa-regular fa-rectangle-list"></i> Bilanz / Historie</strong>'+
                                '</p>';
                         
                        showGc += '<hr style="width:100%; margin:6px 0 6px 0;">';

                        var buyDt1 = value.created_at;
                        var buyDt2 = buyDt1.split(' ')[0];
                        var buyDt3 = buyDt2.split('-');
                        var expDt1 = value.expirationDate;
                        var expDt2 = expDt1.split(' ')[0];
                        var expDt3 = expDt2.split('-');
                        showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verkaufsdatum</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verfallsdatum</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">'+buyDt3[2]+'.'+buyDt3[1]+'.'+buyDt3[0]+'</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">'+expDt3[2]+'.'+expDt3[1]+'.'+expDt3[0]+'</p>';
                    }else{

                    }

                    showGc += '</div>';
                    $('#expiredAndFullyUsedGCBody').append(showGc);
                });// end foreach
			},
			error: (error) => {
				console.log(error);
			}
		});
    }

//----------------------------------------------------------------------------------------------------------------------------




function showDeletedGC(){
    $('#deletedGCBody').html('');
    $.ajax({
		url: '{{ route("giftCardWa.giftCardShowDeletedIns") }}',
		method: 'post',
        dataType: 'json',
		data: {
			_token: '{{csrf_token()}}'
		},
		success: (respo) => {
            $.each(respo, function(index, value){
                if (value.onlinePayStat == 0){
                        showGc = '<div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px; background-color:rgba(252,228,214,255);" class="d-flex flex-wrap justify-content-between p-1">';
                    }else{
                        showGc = '<div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px;" class="d-flex flex-wrap justify-content-between p-1">';
                    }

                    if (value.gcType == 'chf'){
                        showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">CHF</p>'+
                        '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+value.payM+'</p>';
                        if (value.clName != 'empty'){
                            showGc += '<p style="width:100%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+value.clName;
                            if (value.clLastname != 'empty'){
                                showGc += '<span class="ml-2">'+value.clLastname+'</span>';
                            }
                            showGc += '</p>';
                        }
                        if (value.clEmail != 'empty'){
                            showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+value.clEmail+'</p>';
                        }else{
                            showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>';
                        }
                        if (value.clPhNr != 'empty'){
                            showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+value.clPhNr+'</p>';
                        }else{
                            showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>';
                        }
                        showGc += '<hr style="width:100%; margin:0px 0 6px 0;">';

                        showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Summe :</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Übrig :</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">'+parseFloat(value.gcSumInChf).toFixed(2)+' CHF</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">'+parseFloat(parseFloat(value.gcSumInChf) - parseFloat(value.gcSumInChfUsed)).toFixed(2)+' CHF</p>';

                        showGc += '<hr style="width:100%; margin:6px 0 6px 0;">';

                        showGc += '<p style="width:100%; font-weight:bold; font-size:1.3rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center pt-1">'+
                                    '<i style="color:rgb(72,81,87);" class="fa-solid fa-qrcode mr-2"></i>'+value.idnShortCode+'</p>';
                              
                        showGc += '<hr style="width:100%; margin:6px 0 6px 0;">';

                        var buyDt1 = value.created_at;
                        var buyDt2 = buyDt1.split(' ')[0];
                        var buyDt3 = buyDt2.split('-');
                        var expDt1 = value.expirationDate;
                        var expDt2 = expDt1.split(' ')[0];
                        var expDt3 = expDt2.split('-');

                        var delAtDt1 = value.updated_at;
                        var delAtDt2 = delAtDt1.split(' ')[0];
                        var delAtDt3 = delAtDt2.split('-');
                        var delAtHr2 = delAtDt1.split(' ')[1];
                        var delAtHr3 = delAtHr2.split(':');
                        showGc += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verkaufsdatum</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verfallsdatum</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">'+buyDt3[2]+'.'+buyDt3[1]+'.'+buyDt3[0]+'</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">'+expDt3[2]+'.'+expDt3[1]+'.'+expDt3[0]+'</p>';
                    
                        showGc += '<p style="width:100%; font-weight:bold; color:red; margin-bottom:3px; line-height:1;" class="text-center mt-2">'+
                                    'Gelöscht am : '+delAtDt3[2]+'.'+delAtDt3[1]+'.'+delAtDt3[0]+' '+delAtHr3[0]+':'+delAtHr3[1]+'</p>';
                    }else{

                    }

                    showGc += '</div>';
                    $('#deletedGCBody').append(showGc);

            });
        },
		error: (error) => {
			console.log(error);
		}
	});
}

function closeDeletedGCModal(){
    $('#deletedGCBody').html('');
}

//----------------------------------------------------------------------------------------------------------------------------

function showGCBalanceStatus(gcId, insFrom){
        if(insFrom == 0){
            $('#giftCardBalanceModal').modal('show');
            $('#reShowExpiredGC').val('0');
        }else{
            $('#expiredAndFullyUsedGCModal').modal('hide');
            $('#giftCardBalanceModal').modal('show');
            $('#reShowExpiredGC').val('1');
        }
        
        $.ajax({
			url: '{{ route("giftCard.giftCardFetchcheckBalance") }}',
			method: 'post',
			data: {
				gcId: gcId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                if(respo == 'notFound'){

                }else{
                    respo2D = respo.split('|||');
                
                    var newBalanceShow = '<div class="d-flex flex-wrap justify-content-between">'+
                                        '<p style="width: 100%; text-align:center; font-size:1.3rem;"><strong>Geschenkkartendaten (ref ID:'+respo2D[8]+')</strong></p>';
                    if(respo2D[1] == 'empty'){
                        newBalanceShow += '<p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Name: <br><strong>---</strong></p>';
                    }else{
                        newBalanceShow += '<p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Name: <br><strong>'+respo2D[1]+'</strong></p>';
                    }

                    if(respo2D[2] == 'empty'){
                        newBalanceShow += '<p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Nachname: <br><strong>---</strong></p>';
                    }else{
                        newBalanceShow += '<p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Nachname: <br><strong>'+respo2D[2]+'</strong></p>';
                    }

                    if(respo2D[3] == 'empty'){
                        newBalanceShow += '<p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">E-Mail: <br><strong>---</strong></p>';
                    }else{
                        newBalanceShow += '<p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">E-Mail: <br><strong>'+respo2D[3]+'</strong></p>';
                    }

                    if(respo2D[4] == 'empty'){
                        newBalanceShow += '<p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Telefonnummer: <br><strong>---</strong></p>';
                    }else{
                        newBalanceShow += '<p style="width:50%; text-align:center; line-height:1.1; padding:5px 5px 5px 5px; margin-bottom:5px;">Telefonnummer: <br><strong>'+respo2D[4]+'</strong></p>';
                    }

                    newBalanceShow +=   '<hr style="width:100%;">'+
                                        '<p style="width:59%; text-align:right; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px; margin-bottom:5px;">Gesamtwert:</p>'+
                                        '<p style="width:39%; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px; margin-bottom:5px;"><strong>'+parseFloat(respo2D[5]).toFixed(2)+' CHF</strong></p>'+
                                        '<p style="width:59%; text-align:right; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px; margin-bottom:5px;">Gesamtausgaben:</p>'+
                                        '<p style="width:39%; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px; color:red; margin-bottom:5px;"><strong>'+parseFloat(respo2D[6]).toFixed(2)+' CHF</strong></p>'+
                                        '<p style="width:59%; text-align:right; line-height:1.1; font-size:1.2rem; padding:5px 5px 5px 5px; margin-bottom:5px;">insgesamt verfügbar:</p>'+
                                        '<p style="width:39%; line-height:1.1; font-size:1.2rem; padding:5px 5px 5px 5px; margin-bottom:5px;"><strong>'+parseFloat(parseFloat(respo2D[5]) - parseFloat(respo2D[6])).toFixed(2)+' CHF</strong></p>'+
                                        '<hr style="width:100%;">'+
                                        '<p style="width: 100%; text-align:center; font-size:1.1rem;"><strong>Nutzungsverlauf der Geschenkkarte</strong></p>'+
                                        '<p style="width:18%; margin-bottom:8px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;"><strong>#</strong></p>'+
                                        '<p style="width:40%; margin-bottom:8px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;"><strong>GK Rabat</strong></p>'+
                                        '<p style="width:40%; margin-bottom:8px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;"><strong>Datum/Uhrzeit</strong></p>';
                    if( respo2D[7] != 'empty' ){
                        var gcUses = respo2D[7];
                        $.each(gcUses.split('---|---'), function( index, gcUse ) {
                            gcUse2D = gcUse.split('-||-');
                            newBalanceShow +=   '<p style="width:18%; margin-bottom:4px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;">'+gcUse2D[0]+'</p>'+
                                                '<p style="width:40%; margin-bottom:4px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;">'+parseFloat(gcUse2D[1]).toFixed(2)+' CHF</p>'+
                                                '<p style="width:40%; margin-bottom:4px; text-align:center; line-height:1.1; font-size:1rem; padding:5px 5px 5px 5px;">'+gcUse2D[2]+'</p>';
                        });
                    }

                    newBalanceShow +=   '</div>';
                    $('#giftCardBalanceBody').html(newBalanceShow);
                }
			},
			error: (error) => { console.log(error); }
		});
    }



    

    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }
    function gcExprDateByYears(yearsNr){
        if($('#gcExprDateByYearsTheYrSelected').val() != 0){
            $('#gcExprDateByYears'+$('#gcExprDateByYearsTheYrSelected').val()).addClass('btn-info').removeClass('btn-dark');
        }
        $('#gcExprDateByYearsTheYrSelected').val(yearsNr);
        $('#gcExprDateByYears'+yearsNr).addClass('btn-dark').removeClass('btn-info');
        var d = new Date();
        var month = d.getMonth();
        var day = d.getDate();
        $('#pickExpirationDate01').val(d.getFullYear()+yearsNr+'-'+pad(d.getMonth()+1,2)+'-'+pad(d.getDate(),2));
    }
    function removeYearsSelected(){
        if($('#gcExprDateByYearsTheYrSelected').val() != 0){
            $('#gcExprDateByYears'+$('#gcExprDateByYearsTheYrSelected').val()).addClass('btn-info').removeClass('btn-dark');
        }
        $('#gcExprDateByYearsTheYrSelected').val(0);
    }







    function deleteGCPrep(gcId, shortCode,gcRefId){
        $('#giftCardDeleteModal').modal('show');
        $('#gcDeleteId').val(gcId);

        $('#gcDeleteIdShow').html(gcRefId);
        $('#gcDeleteShortCodeShow').html(shortCode);
    }
    function closeGiftCardDeleteModal(){
        $('#gcDeleteId').val(0);
        $('#gcDeleteIdShow').html('-');
        $('#gcDeleteShortCodeShow').html('-');
    }
    function deleteGC(){
        if($('#gcDeleteId').val() != 0){
            
            $.ajax({
				url: '{{ route("giftCard.giftCardDeleteInstance") }}',
				method: 'post',
				data: {
					gcId:  $('#gcDeleteId').val(),
					_token: '{{csrf_token()}}'
				},
				success: (response) => {
                    response = $.trim(response);
					if(response != 'notFound'){

                        $('#giftCardDeleteModal').modal('hide');
                        $("#gcInstancesDiv").load(location.href+" #gcInstancesDiv>*","");
                        $('#gcDeleteId').val(0);
                        $('#gcDeleteIdShow').html('-');
                        $('#gcDeleteShortCodeShow').html('-');
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    }
    


    function reloadStatistikenModal(){
        $("#statisticsGCBody").load(location.href+" #statisticsGCBody>*","");
    }



    function closeGiftCardBalanceModal(){
        if($('#reShowExpiredGC').val() == 0){
            $('#giftCardBalanceBody').html('');
            $('#giftCardBalanceModal').modal('hide');
        }else{
            $('#giftCardBalanceBody').html('');
            $('#giftCardBalanceModal').modal('hide');
            $('#expiredAndFullyUsedGCModal').modal('show');
            $('#reShowExpiredGC').val(0);
        }
    }


    function closeActivateGCCameraScanModal(){
        $('#activateGCCameraScanModal').modal('toggle');

        $('#addSellAGCToKartelConAlert').attr('style','width:100%; border-radius:5px; display:none;');
    }

    function closeGCSearchByScanModal(){
        $('#gCSearchByScanModal').modal('toggle');

    }


    function cancelGCToKartelCon(){
        $('#addSellAGCToKartelConAlert').attr('style','width:100%; border-radius:5px; display:none;');
        $('#gcToSellConect').val(0);
        $('#payRechnungGCToSellCon').val(0);
    }







    function searchGCByCode(){
        if(!$('#searchGCByCodeInp').val()){
            if($('#searchForAGCErr01').is(":hidden")){ $('#searchForAGCErr01').show(10).delay(4000).hide(10); }
        }else{
            var gccode = $('#searchGCByCodeInp').val();
            if(gccode.length != 8){
                if($('#searchForAGCErr02').is(":hidden")){ $('#searchForAGCErr02').show(10).delay(4000).hide(10); }
            }else{
                $.ajax({
					url: '{{ route("giftCard.searchGCByCode") }}',
					method: 'post',
					data: {
						gcId: gccode,
						_token: '{{csrf_token()}}'
					},
					success: (respo) => {
                        if(respo == 'giftCardNotFound'){
                            if($('#searchForAGCErr03').is(":hidden")){ $('#searchForAGCErr03').show(10).delay(4000).hide(10); }
                        }else{
                            $("#gcInstancesDiv").html("");
                            var gcShow = '';
                            if(respo['onlinePayStat'] == 0){
                                gcShow += '<div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px; background-color:rgba(252,228,214,255);" class="d-flex flex-wrap justify-content-between p-1">';
                            }else{
                                gcShow += '<div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px;" class="d-flex flex-wrap justify-content-between p-1">';
                            }
                            if (respo['gcType'] == 'chf'){
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">CHF</p>';
                                if (respo['payM'] == 'Online'){
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["payM"]+
                                        '<i style="width:20%; color:rgb(72,81,87);" class="fa-solid fa-qrcode ml-2" onclick="openOnlinePayGCQrCode(\''+respo["id"]+'\')"></i>'+
                                    '</p>';
                                }else{
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["payM"]+'</p>';
                                }
                                if (respo["clName"] != 'empty'){
                                    gcShow += '<p style="width:100%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["clName"];
                                        if (respo["clLastname"] != 'empty'){
                                            gcShow += '<span class="ml-2">'+respo["clLastname"]+'</span>';
                                        }
                                    gcShow += '</p>';
                                }

                                if (respo["clEmail"] != 'empty'){
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["clEmail"]+'</p>';
                                }else{
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>';
                                }
                                if (respo["clPhNr"] != 'empty'){
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">'+respo["clPhNr"]+'</p>';
                                }else{
                                    gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>';
                                }
                                gcShow += '<hr style="width:100%; margin:0px 0 6px 0;">';

                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Summe :</p>';
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Übrig :</p>';
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">'+parseFloat(respo["gcSumInChf"]).toFixed(2)+' CHF</p>';
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">'+parseFloat(parseFloat(respo["gcSumInChf"]) - parseFloat(respo["gcSumInChfUsed"])).toFixed(2)+' CHF</p>';
                                
                                gcShow += '<hr style="width:100%; margin:6px 0 6px 0;">';

                                gcShow += '<form style="width:30%;" method="POST" action="https://qrorpa.ch/giftCardGetReceipt">'+
                                    '{{ csrf_field()}}'+
                                    '<button type="submit" style="width:100%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-1 shadow-none">'+
                                        '<i class="fa-solid fa-xl fa-file-pdf"></i>'+
                                    '</button>'+
                                    '<input id="giftCardId" type="hidden" value="'+respo["id"]+'" name="giftCardId">'+
                                '</form>'+
                                '<p onclick="showGCBillQrCode(\''+respo["id"]+'\')" style="width:30%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center pt-1">'+
                                    '<i class="fa-solid fa-xl fa-qrcode"></i>'+
                                '</p>'+
                                '<p onclick="deleteGCPrep(\''+respo["id"]+'\',\''+respo["idnShortCode"]+'\',\''+respo["refId"]+'\')" style="width:30%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-1">'+
                                    '<strong><i class="fa-solid fa-xl fa-trash-can"></i> </strong>'+
                                '</p>'+
                                '<p onclick="showGCBalanceStatus(\''+respo["id"]+'\',\'0\')" style="width:50%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-2">'+
                                    '<strong><i class="fa-regular fa-rectangle-list"></i> Bilanz</strong>'+
                                '</p>'+
                                '<p style="width:50%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-2" onclick="openGCQrCode(\''+respo["id"]+'\')">'+
                                    '<i style="color:rgb(72,81,87);" class="fa-solid fa-qrcode mr-2"></i>'+respo["idnShortCode"]+
                                '</p>'+

                                '<hr style="width:100%; margin:6px 0 6px 0;">';

                                var buyDt = respo["created_at"];
                                var expDt = respo["expirationDate"];
                                buyDt = buyDt.split(' ')[0];
                                buyDt = buyDt.split('-');
                                expDt = expDt.split(' ')[0];
                                expDt = expDt.split('-');
                              
                                gcShow += '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verkaufsdatum</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verfallsdatum</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">'+buyDt[2]+'.'+buyDt[1]+'.'+buyDt[0]+'</p>'+
                                '<p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">'+expDt[2]+'.'+expDt[1]+'.'+expDt[0]+'</p>';
                    

                            }

                            gcShow += '</div>';



                            $("#gcInstancesDiv").append(gcShow);

                            $('#searchGCByCodeBtn').attr('onclick','cancelSearchGCByCode()');
                            $('#searchGCByCodeBtn').attr('class','btn btn-danger shadow-none');
                            $('#searchGCByCodeBtn').html('<i class="fa-solid fa-x"></i>');

                            $('#searchGCByCodeInp').attr('style','font-size:1rem; background-color: rgba(40,167,69,0.22);');
                        }

						
					},
					error: (error) => { console.log(error); }
				});
            }
        }
    }

    function cancelSearchGCByCode(){
        $("#gcInstancesDiv").load(location.href+" #gcInstancesDiv>*","");

        $('#searchGCByCodeBtn').attr('onclick','searchGCByCode()');
        $('#searchGCByCodeBtn').attr('class','btn btn-outline-dark shadow-none');
        $('#searchGCByCodeBtn').html('<i class="fa-solid fa-magnifying-glass"></i>');

        $('#searchGCByCodeInp').attr('style','font-size:1rem;');
        $('#searchGCByCodeInp').val('');
    }
</script>