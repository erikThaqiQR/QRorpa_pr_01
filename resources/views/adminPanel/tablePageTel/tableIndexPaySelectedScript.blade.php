<?php
    use App\Restorant;
?>
<script type="module">
    
    import QrScanner from "/js/FP-qr-scanner.min.js";
    function payAllOpenCameraGCSel(){
        const video = document.getElementById('payALL-qr-video-sel');

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
                $.ajax({
					url: '{{ route("giftCard.validateGiftCardOnScanToApply") }}',
					method: 'post',
					data: {
						qrCodeD: qrCodeData,
						_token: '{{csrf_token()}}'
					},
					success: (respo) => {
                        respo = $.trim(respo);
                        if(respo != 'invalideQRCode' && respo != 'gcNotSoldYet'){
                            // qrCodeData2D = qrCodeData.split('|||');
                            // alert(qrCodeData.split('|||')[0]);
                            $('#payAllgcValidationCodeInputSel').val(qrCodeData.split('|||')[0]);
                            $('#payAllCameraModalSel').modal('hide');
                            $('body').attr('class','modal-open');
                            scanner.stop();
                            payAllValidateGCSel();
                        }else if(respo == 'invalideQRCode'){
                            if( $('#payAllPhaseOneError511Sel').is(':hidden') ){ $('#payAllPhaseOneError511Sel').show(100).delay(4500).hide(100); }
                            $('#payAllCameraModalSel').modal('hide');
                            $('body').attr('class','modal-open');
                            scanner.stop();
                        }else if(respo == 'gcNotSoldYet'){
                            if( $('#payAllPhaseOneError512Sel').is(':hidden') ){ $('#payAllPhaseOneError512Sel').show(100).delay(4500).hide(100); }
                            $('#payAllCameraModalSel').modal('hide');
                            $('body').attr('class','modal-open');
                            scanner.stop();
                        }
					},
					error: (error) => { console.log(error); }
				});
            }
        }
    }
    var btn = document.getElementById("payAllOpenCameraModalBtnSel");
    // Assigning event listeners to the button
    btn.addEventListener("click", payAllOpenCameraGCSel);
    
</script>

<script>

    function closePayAllCameraModalSel(){
        $('#payAllCameraModalSel').modal('toggle');
        $('body').attr('class','modal-open');
    }


    function closePaySelPhaseOneSel(){
        $('#payAllPhaseOneDiv1Sel').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
        $("#payAllPhaseOneBodySel").load(location.href+" #payAllPhaseOneBodySel>*","");

        $('#payAllPhaseOneSel').modal('toggle');
        $('#tabOrder'+$("#payAllTableNrSel").val()).modal('toggle');

        handleExitPOSPaytec();
    }

    function prepPaySelProds(tNr,resId){
       
            $('#payAllPhaseOneSel').modal('toggle');
            $('#payAllBtn1Sel').prop('disabled', true);
            $('#payAllBtn2Sel').prop('disabled', true);
            $('#payAllBtn3Sel').prop('disabled', true);
            $('#payAllBtn4Sel').prop('disabled', true);

            $('#tabOrder'+tNr).modal('toggle');
            $('#tabOrder'+tNr).removeClass('show');
            $('body').attr('class','modal-open');
            $('#payAllTableNrSel').val(tNr);
            $('#prodsSelPaySel').val($('#closeOrSelected'+tNr).val());

            $.ajax({
                url: '{{ route("admin.paySelFetchOrders") }}',
                dataType: 'json',
                method: 'post',
                data: {
                    tNr: tNr,
                    resId: resId,
                    selProds : $('#prodsSelPaySel').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    var totPay = parseFloat(0);
                    var sasiaSelected = 0;
                    var selectedItems = $('#closeOrSelected'+tNr).val();
                    $.each(respo, function(index, value){
                        $.each(selectedItems.split('||'), function( index2, selectedItemsOne ) {
                            var selectedItemsOne2D = selectedItemsOne.split('-8-');
                            if(value.id == selectedItemsOne2D[0]){
                                sasiaSelected = selectedItemsOne2D[1];
                            }
                        });
                        var priceForOne = parseFloat(parseFloat(value.OrderQmimi)/parseFloat(value.OrderSasia)).toFixed(2);
                        var thisOrQmimi = parseFloat(parseFloat(priceForOne)*parseFloat(sasiaSelected)).toFixed(2)

                        $('#payAllPhaseOneDiv1Sel').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left">'+sasiaSelected+'x '+value.OrderEmri+'</p>');
                        $('#payAllPhaseOneDiv1Sel').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right">CHF '+parseFloat(thisOrQmimi).toFixed(2)+'</p>');
                        totPay += parseFloat(thisOrQmimi);
                    });
                    if($('#resTvshInput').val() == 0){
                        var mwst = parseFloat(0);
                    }else{
                        var mwst = parseFloat(totPay * 0.074930619);
                    }
                    $('#payAllPhaseOneDiv1Sel').append('<p style="width: 70%; margin-bottom:8px;" class="text-left"><strong>Total inkl.</strong></p>');
                    $('#payAllPhaseOneDiv1Sel').append('<p style="width: 30%; margin-bottom:8px;" class="text-right"><strong>CHF <span id="totAmProdsSel">'+parseFloat(totPay).toFixed(2)+'</span></strong></p>');
                    $('#payAllPhaseOneDiv1Sel').append('<p id="payAllPhaseOneDiv1TippPSel11" style="width: 70%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>Tipp.</strong></p>');
                    $('#payAllPhaseOneDiv1Sel').append('<p id="payAllPhaseOneDiv1TippPSel12" style="width: 30%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="tippAmProdsSel">0.00</span></strong></p>');
                    $('#payAllPhaseOneDiv1Sel').append('<p id="payAllstaffDiscountShowSel01" style="width: 70%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>Rabatt vom Personal.</strong></p>');
                    $('#payAllPhaseOneDiv1Sel').append('<p id="payAllstaffDiscountShowSel02" style="width: 30%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="staffDiscSpanSel">0.00</span></strong></p>');
                    $('#payAllPhaseOneDiv1Sel').append('<p id="payAllgiftCardDiscountShowSel01" style="width: 70%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>Rabatt von der Geschenkkarte.</strong></p>');
                    $('#payAllPhaseOneDiv1Sel').append('<p id="payAllgiftCardDiscountShowSel02" style="width: 30%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="giftCardDiscSpanSel">0.00</span></strong></p>');
                    $('#payAllPhaseOneDiv1Sel').append('<p style="width: 70%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>MwSt.</strong></p>');
                    if($('#resTvshInput').val() == 0){
                        $('#payAllPhaseOneDiv1Sel').append('<p style="width: 30%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="tvshAmProdsSel">'+parseFloat(0).toFixed(2)+'</span> (<span id="tvshAmProdsPerventageSel">0.00</span> %)</strong></p>');
                    }else{
                        $('#payAllPhaseOneDiv1Sel').append('<p style="width: 30%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="tvshAmProdsSel">'+parseFloat(mwst).toFixed(2)+'</span> (<span id="tvshAmProdsPerventageSel">8.10</span> %)</strong></p>');
                    }
                    $('#payAllPhaseOneDiv1Sel').append('<p id="payAllFinalPayShowSel01" style="width: 70%; margin-top:-8px; margin-bottom:8px; font-size:1.1rem;" class="text-left"><strong>Letzte Bezahlung.</strong></p>');
                    $('#payAllPhaseOneDiv1Sel').append('<p id="payAllFinalPayShowSel02" style="width: 30%; margin-top:-8px; margin-bottom:8px; font-size:1.1rem;" class="text-right"><strong>CHF <span id="finalPaySpanSel">'+parseFloat(totPay).toFixed(2)+'</span></strong></p>');
                    
                    $('#payAllBtn1Sel').prop('disabled', false);
                    $('#payAllBtn2Sel').prop('disabled', false);
                    $('#payAllBtn3Sel').prop('disabled', false);
                    $('#payAllBtn4Sel').prop('disabled', false);
                },
                error: (error) => { console.log(error); }
            });
    
    }

    function selectDiv2Sel(type){
        if(type == 'Res'){
            if($('#pAPhOneD2Btn2Sel').hasClass('btn-success')){
                $('#pAPhOneD2Btn2Sel').removeClass('btn-success');
                $('#pAPhOneD2Btn2Sel').addClass('btn-dark');
            }
            $('#pAPhOneD2Btn1Sel').removeClass('btn-dark');
            $('#pAPhOneD2Btn1Sel').addClass('btn-success');
            if($('#resTvshInput').val() != 0){
                $('#tvshAmProdsPerventageSel').html('8.10');
            }
            var tot = parseFloat($('#finalPaySpanSel').html());
            var mwst = parseFloat(tot * 0.074930619);
            $('#tvshAmProdsSel').html(parseFloat(mwst).toFixed(2));
            if ($('#tvshAmProdsDiscSel').length){
                var totDisc = parseFloat($('#totalDiscSel').html());
                var mwstDisc = parseFloat(totDisc * 0.074930619);
                $('#tvshAmProdsDiscSel').html(parseFloat(mwstDisc).toFixed(2));
                $('#tvshAmProdsPerventageDiscSel').html(parseFloat(8.10).toFixed(2));
            }

        }else if(type == 'House'){
            if($('#pAPhOneD2Btn1Sel').hasClass('btn-success')){
                $('#pAPhOneD2Btn1Sel').removeClass('btn-success');
                $('#pAPhOneD2Btn1Sel').addClass('btn-dark');
            }
            $('#pAPhOneD2Btn2Sel').removeClass('btn-dark');
            $('#pAPhOneD2Btn2Sel').addClass('btn-success');
            if($('#resTvshInput').val() != 0){
                $('#tvshAmProdsPerventageSel').html('2.60');
            }
            var tot = parseFloat($('#finalPaySpanSel').html());
            var mwst = parseFloat(tot * 0.025341130);
            $('#tvshAmProdsSel').html(parseFloat(mwst).toFixed(2));

            if ($('#tvshAmProdsDiscSel').length){
                var totDisc = parseFloat($('#totalDiscSel').html());
                var mwstDisc = parseFloat(totDisc * 0.025341130);
                $('#tvshAmProdsDiscSel').html(parseFloat(mwstDisc).toFixed(2));
                $('#tvshAmProdsPerventageDiscSel').html(parseFloat(2.60).toFixed(2));
            }
        }
        payAllReloadFinalPaySel();
    }









    // Funksionet per bakshish 
    function setTipStafSel(btnId, tipVal){
        var currTipp = parseFloat($('#tippAmProdsSel').html()).toFixed(2);
        var tipAdd = parseFloat(tipVal).toFixed(2);
        if($('#totalDiscSel').length){
            var tot = parseFloat($('#totalDiscSel').html()).toFixed(2);
        }else{
            var tot = parseFloat($('#totAmProdsSel').html()).toFixed(2);
        }
        $('#tippAmProdsSel').html(tipAdd);
        $('#tipForOrderClosePhOneSel').val(tipAdd);
        $('#tipWaiterCosValSel').val(0);

        payAllReloadFinalPaySel();
    }

    function cancelTipStafSel(btnId){
        $('#tippAmProdsSel').html('0.00');
        $('#tipForOrderClosePhOneSel').val(0);
        $('#tipWaiterCosValSel').val(0);

        payAllReloadFinalPaySel();
    }

    function setCostumeTipStafSel(currVal){
        $('#tippAmProdsSel').html("0.00");
        $('#tipForOrderClosePhOneSel').val("0");
        payAllReloadFinalPaySel();
        if(currVal == ''){
            $('#tippAmProdsSel').html("0.00");
            $('#tipForOrderClosePhOneSel').val("0");
            if( !$('#payAllPhaseOneError4Sel').is(':hidden') ){ $('#payAllPhaseOneError4Sel').hide(10); }
        }else{
            var valForTip = parseFloat(currVal).toFixed(2);
            if($('#totalDiscSel').length){
                var tot = parseFloat($('#totalDiscSel').html()).toFixed(2);
            }else{
                var tot = parseFloat($('#totAmProdsSel').html()).toFixed(2);
            }

            if(parseFloat(valForTip) < parseFloat(tot)){
                if( $('#payAllPhaseOneError4Sel').is(':hidden') ){ $('#payAllPhaseOneError4Sel').show(10); }
                $('#tippAmProdsSel').html("0.00");
                $('#tipForOrderClosePhOneSel').val("0");
                
            }else{
                if( !$('#payAllPhaseOneError4Sel').is(':hidden') ){ $('#payAllPhaseOneError4Sel').hide(10); }
                var tipAdd = parseFloat(parseFloat(valForTip) - parseFloat(tot)).toFixed(2);
                var totWTip = parseFloat(parseFloat(tot) + parseFloat(tipAdd)).toFixed(2);
                $('#tippAmProdsSel').html(parseFloat(tipAdd).toFixed(2));
                $('#tipForOrderClosePhOneSel').val(tipAdd);
            }
        }
        payAllReloadFinalPaySel();
    }
    // ---------------------------------------------------------------------------------













    function callInCashSel(){
        $('#div3inCashBtnSel').hide(100);
        $('#div3inPercentageBtnSel').hide(100);

        $('#div3InCashSel').show(100);
    }
    function procesInCahSel(){
        if(!$('#div3InCashInpSel').val()){
            // zerro discount
            $('#cashDiscountInpSel').val(0);
        }else{
            var cashOff = $('#div3InCashInpSel').val();
            if($.isNumeric(cashOff)){
                var tot = parseFloat($('#finalPaySpanSel').html());
                var totAD = parseFloat(tot - parseFloat(cashOff));
                $('#cashDiscountInpSel').val(cashOff);
                $('#staffDiscSpanSel').html(parseFloat(cashOff).toFixed(2));
              
            }else{
                // not a number , display error msg
                $('#cashDiscountInpSel').val(0);
                if($('#div3InCashError1Sel').is(':hidden')){ $('#div3InCashError1Sel').show(100).delay(4000).hide(100); }
            }
        }
        payAllReloadFinalPaySel();
    }
    function cancelInCashSel(){
        $('#div3inCashBtnSel').show(100);
        $('#div3inPercentageBtnSel').show(100);

        $('#div3InCashSel').hide(100);
        $('#cashDiscountInpSel').val(0);
        $('#div3InCashInpSel').val(0);

        $('#staffDiscSpanSel').html(parseFloat(0).toFixed(2));
        payAllReloadFinalPaySel();
    }

    







    function callInPercentageSel(){
        $('#div3inCashBtnSel').hide(100);
        $('#div3inPercentageBtnSel').hide(100);

        $('#div3InPercentageSel').show(100);
    }
    function procesInPercentageSel(){
        if(!$('#div3InPercentageInpSel').val()){
            // zerro discount
            $('#percentageDiscountInpSel').val(0);
        }else{
            var percOff = $('#div3InPercentageInpSel').val();
            if($.isNumeric(percOff) && percOff > 0 && percOff <= 100){
                var tot = parseFloat($('#totAmProdsSel').html());
                var cashDisc = parseFloat(tot * (percOff/100));
                var disChekc = parseFloat(cashDisc*100).toFixed(2);
                if(!(disChekc % parseFloat(5).toFixed(2) == 0)){
                    cashDisc = (Math.ceil(cashDisc*20)/20).toFixed(2);
                    var newPercOff = parseFloat((100*cashDisc)/tot).toFixed(6);
                }else{
                    var newPercOff = parseFloat(percOff).toFixed(2);
                }
                var totAD = parseFloat(tot - parseFloat(cashDisc));
               
                $('#percentageDiscountInpSel').val(newPercOff);
                $('#staffDiscSpanSel').html(parseFloat(cashDisc).toFixed(2));
            }else{
                // not a number , display error msg
                $('#percentageDiscountInpSel').val(0);
                if($('#div3InPercentageError1Sel').is(':hidden')){ $('#div3InPercentageError1Sel').show(100).delay(4000).hide(100); }
            }
        }
        payAllReloadFinalPaySel();
    }
    function cancelInPercentageSel(){
        $('#div3inCashBtnSel').show(100);
        $('#div3inPercentageBtnSel').show(100);
        
        $('#div3InPercentageSel').hide(100);
        $('#percentageDiscountInpSel').val(0);
        $('#div3InPercentageInpSel').val(0);
        $('#staffDiscSpanSel').html(parseFloat(0).toFixed(2));
  
        payAllReloadFinalPaySel();
    }



    function payAllReloadFinalPaySel(){
        var tot = parseFloat($('#totAmProdsSel').html()).toFixed(2);
        var currTipp = parseFloat($('#tippAmProdsSel').html()).toFixed(2);
        var cashDiscStaff = parseFloat($('#cashDiscountInpSel').val()).toFixed(2);
        var discFromGC = parseFloat($('#payAllGCAppliedCHFValSel').val()).toFixed(2);
        if($('#percentageDiscountInpSel').val() > 0){
            var percOff = $('#percentageDiscountInpSel').val();
            var cashDiscFromPerc = parseFloat(tot * (percOff/100));
            var totWithDiscTip = parseFloat( parseFloat(tot) + parseFloat(currTipp) - parseFloat(cashDiscFromPerc) - parseFloat(discFromGC)).toFixed(2);
        }else{
            var totWithDiscTip = parseFloat( parseFloat(tot) + parseFloat(currTipp) - parseFloat(cashDiscStaff) - parseFloat(discFromGC)).toFixed(2);
        }
        $('#finalPaySpanSel').html(parseFloat(totWithDiscTip).toFixed(2));

        if($('#resTvshInput').val() == 0){
            var mwstValCal = parseFloat(0).toFixed(10);
        }else{
            if( $('#tvshAmProdsPerventageSel').html() == '8.10'){
                var mwstValCal = parseFloat(0.074930619).toFixed(10);
            }else if($('#tvshAmProdsPerventageSel').html() == '2.60'){
                var mwstValCal = parseFloat(0.074930619).toFixed(10);
            }
        }

        var tot = parseFloat($('#finalPaySpanSel').html());
        var totWithGC = parseFloat(parseFloat(tot) + parseFloat(discFromGC));
        var mwst = parseFloat(totWithGC * mwstValCal);
        $('#tvshAmProdsSel').html(parseFloat(mwst).toFixed(2));

        if(tot == 0 && discFromGC > 0){
            $('#payAllPhaseOneDiv4Sel').html('<p style="width: 100%;" class="text-center"><strong>Zahlungeart</strong></p>');
            $('#payAllPhaseOneDiv4Sel').append('<button id="payAllBtn2Sel" style="width:100%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="paySelProdsSel(\'GCAllPay\')"><strong>Bestellung bezahlen</strong></button>');
        }else{
            $('#payAllPhaseOneDiv4Sel').html('<p style="width: 100%;" class="text-center"><strong>Zahlungeart</strong></p>');
            $('#payAllPhaseOneDiv4Sel').append('<button id="payAllBtn1Sel" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPaySelProdsCash()"><strong>Bar</strong></button>');
            $('#payAllPhaseOneDiv4Sel').append('<button id="payAllBtn2Sel" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="paySelProdsSel(\'none\')"><strong>Karte</strong></button>');
            $('#payAllPhaseOneDiv4Sel').append('<button id="payAllBtn3Sel" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepOnlinePayPaySel()"><strong>Online</strong></button>');
            $('#payAllPhaseOneDiv4Sel').append('<button id="payAllBtn4Sel" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPaySelProdsRechnung()"><strong>Auf Rechnung</strong></button>');
        }
    }







    function payAllValidateGCSel(){
        if(!$('#payAllgcValidationCodeInputSel').val()){
            if( $('#payAllPhaseOneError51Sel').is(':hidden') ){ $('#payAllPhaseOneError51Sel').show(100).delay(4500).hide(100); }
        }else{
            $('#payAllValidateGCBtnSel').prop('disabled', true);
            $('#payAllgcValidationCodeInputSel').prop('disabled', true);
            $.ajax({
				url: '{{ route("giftCard.giftCardValidateTheIdnCode") }}',
				method: 'post',
				data: {
					gcIdnCode: $('#payAllgcValidationCodeInputSel').val(),
					_token: '{{csrf_token()}}'
				},
				success: (response) => {
					response = $.trim(response);
                    if(response == 'gcNotFound'){
                        if( $('#payAllPhaseOneError52Sel').is(':hidden') ){ $('#payAllPhaseOneError52Sel').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtnSel').prop('disabled', false);
                        $('#payAllgcValidationCodeInputSel').prop('disabled', false);
                    }else if(response == 'gcIsAllSpend'){
                        if( $('#payAllPhaseOneError53Sel').is(':hidden') ){ $('#payAllPhaseOneError53Sel').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtnSel').prop('disabled', false);
                        $('#payAllgcValidationCodeInputSel').prop('disabled', false);
                    }else if(response == 'gcIsNotPaid'){
                        if( $('#payAllPhaseOneError58Sel').is(':hidden') ){ $('#payAllPhaseOneError58Sel').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtnSel').prop('disabled', false);
                        $('#payAllgcValidationCodeInputSel').prop('disabled', false);
                    }else if(response == 'gcExpired'){
                        if( $('#payAllPhaseOneError59Sel').is(':hidden') ){ $('#payAllPhaseOneError59Sel').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtnSel').prop('disabled', false);
                        $('#payAllgcValidationCodeInputSel').prop('disabled', false);
                    }else if(response == 'gcNotOfThisRes'){
                        if( $('#payAllPhaseOneError510Sel').is(':hidden') ){ $('#payAllPhaseOneError510Sel').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtnSel').prop('disabled', false);
                        $('#payAllgcValidationCodeInputSel').prop('disabled', false);
                    }else{
                        respo2D = response.split('|||');
                        $('#payAllPhaseOneDivSel5_3').show(10);
                        $('#payAllPhaseOneDivSel5_4').show(10);
                        $('#payAllPhaseOneDivSel5_5').show(10);
                        $('#payAllPhaseOneDivSel5_6').show(10);
                        $('#payAllamountLeftChfSel').html(respo2D[1]);
                        $('#payAllGCAppliedIdSel').val(respo2D[0]);

                        $('#payAllValidateGCBtnSel').prop('disabled', false);
                        $('#payAllValidateGCBtnSel').attr('class','btn btn-danger shadow-none');
                        $('#payAllValidateGCBtnSel').attr('onclick','payAllCancelGCSel()');
                        $('#payAllValidateGCBtnSel').html('Stornieren');
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    }
    function payAllCancelGCSel(){
        $('#payAllValidateGCBtnSel').attr('class','btn btn-outline-dark shadow-none');
        $('#payAllValidateGCBtnSel').attr('onclick','payAllValidateGCSel()');
        $('#payAllgcValidationCodeInputSel').prop('disabled', false);
        $('#payAllgcValidationCodeInputSel').val('');
        $('#payAllApplyDiscFromGcInputSel').val('');
        $('#payAllGCAppliedIdSel').val(0);
        $('#payAllGCAppliedCHFValSel').val(0);
        $('#payAllValidateGCBtnSel').html('Bestätigen');
        $('#giftCardDiscSpanSel').html(parseFloat(0).toFixed(2));

        $('#payAllPhaseOneDivSel5_3').hide(10);
        $('#payAllPhaseOneDivSel5_4').hide(10);
        $('#payAllPhaseOneDivSel5_5').hide(10);
        $('#payAllPhaseOneDivSel5_6').hide(10);

        payAllReloadFinalPaySel();
    }

    function payAllApplyGCDiscountSel(){
        if(!$('#payAllApplyDiscFromGcInputSel').val()){
            if( $('#payAllPhaseOneError54Sel').is(':hidden') ){ $('#payAllPhaseOneError54Sel').show(100).delay(4500).hide(100); }
        }else{
            var dicsValFromGC = parseFloat($('#payAllApplyDiscFromGcInputSel').val()).toFixed(2);
            if(dicsValFromGC <= 0){
                if( $('#payAllPhaseOneError54Sel').is(':hidden') ){ $('#payAllPhaseOneError54Sel').show(100).delay(4500).hide(100); }
            }else if($('#payAllGCAppliedIdSel').val() == 0){
                if( $('#payAllPhaseOneError55Sel').is(':hidden') ){ $('#payAllPhaseOneError55Sel').show(100).delay(4500).hide(100); }
            }else{
                $.ajax({
                    url: '{{ route("giftCard.giftCardValidateTheSumToApplyDisc") }}',
                    method: 'post',
                    data: {
                        gcId: $('#payAllGCAppliedIdSel').val(),
                        gcDiscAmnt: dicsValFromGC,
                        _token: '{{csrf_token()}}'
                    },
                    success: (response) => {
                        response = $.trim(response);
                        if(response == 'gcNotFound'){
                            if( $('#payAllPhaseOneError55Sel').is(':hidden') ){ $('#payAllPhaseOneError55Sel').show(100).delay(4500).hide(100); }
                        }else if(response == 'gcAmountNotAvailable'){
                            if( $('#payAllPhaseOneError56Sel').is(':hidden') ){ $('#payAllPhaseOneError56Sel').show(100).delay(4500).hide(100); }
                        }else{
                            response = parseFloat(response).toFixed(2);
                            var tot = parseFloat($('#finalPaySpanSel').html()).toFixed(2);

                            if(Number(tot) >= Number(response)){
                                $('#giftCardDiscSpanSel').html(parseFloat(response).toFixed(2));
                                $('#payAllGCAppliedCHFValSel').val(parseFloat(response).toFixed(2));
                                $('#payAllPhaseOneDivSel5_3').hide(10);
                                $('#payAllPhaseOneDivSel5_4').hide(10);
                                $('#payAllPhaseOneDivSel5_5').hide(10);
                                $('#payAllPhaseOneDivSel5_6').hide(10);
                                $('#payAllgcValidationCodeInputSel').val($('#payAllgcValidationCodeInputSel').val()+" / "+parseFloat(response).toFixed(2)+" CHF");

                                payAllReloadFinalPaySel();
                            }else{
                                // to much discount
                                if( $('#payAllPhaseOneError57Sel').is(':hidden') ){ $('#payAllPhaseOneError57Sel').show(100).delay(4500).hide(100); }
                            }
                        }
                       
                    },
                    error: (error) => { console.log(error); }
                });
            }
        }
    }

    function payAllApplyGCDiscountSelMax(){
        if($('#payAllGCAppliedIdSel').val() == 0){
            if( $('#payAllPhaseOneError55Sel').is(':hidden') ){ $('#payAllPhaseOneError55Sel').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
                url: '{{ route("giftCard.giftCardValidateTheSumToApplyDiscMax") }}',
                method: 'post',
                data: {
                    gcId: $('#payAllGCAppliedIdSel').val(),
                    toPayAmnt: $('#finalPaySpanSel').html(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    response = $.trim(response);
                    if(response == 'gcNotFound'){
                        if( $('#payAllPhaseOneError55Sel').is(':hidden') ){ $('#payAllPhaseOneError55Sel').show(100).delay(4500).hide(100); }
                    }else{
                        response = parseFloat(response).toFixed(2);
                        $('#giftCardDiscSpanSel').html(parseFloat(response).toFixed(2));
                        $('#payAllGCAppliedCHFValSel').val(parseFloat(response).toFixed(2));
                        $('#payAllPhaseOneDivSel5_3').hide(10);
                        $('#payAllPhaseOneDivSel5_4').hide(10);
                        $('#payAllPhaseOneDivSel5_5').hide(10);
                        $('#payAllPhaseOneDivSel5_6').hide(10);
                        $('#payAllgcValidationCodeInputSel').val($('#payAllgcValidationCodeInputSel').val()+" / "+parseFloat(response).toFixed(2)+" CHF");

                        payAllReloadFinalPaySel();
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    }




    function paySelProdsSel(extInfo){
        $('#payAllPhaseOneDiv4Sel').html('');
        // $('#paySelProd'+tNr).prop('disabled',true);
        if($('#payAllTableNrSel').val() == 0){
            if($('#payAllPhaseOneError3Sel').is(':hidden')){ $('#payAllPhaseOneError3Sel').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsPerventageSel').html() == '--.--'){
            if($('#payAllPhaseOneError1Sel').is(':hidden')){ $('#payAllPhaseOneError1Sel').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsDiscSel').length && !$('#discReasonInpSel').val()){
            if($('#payAllPhaseOneError2Sel').is(':hidden')){ $('#payAllPhaseOneError2Sel').show(100).delay(4000).hide(100); }
        }else{
            if('{{Restorant::find(Auth::user()->sFor)->hasPOS}}' == 1 && extInfo == 'none'){

                POSStarted = true;
                if($('#payAllPhaseOnePayAtPOSSel').is(':hidden')){ $('#payAllPhaseOnePayAtPOSSel').show(50); }

                $.ajax({
                    url: '{{ route("payTec.Connect") }}',
                    method: 'post',
                    data: {_token: '{{csrf_token()}}'},
                    success: (res) => {

                        $.ajax({
                            url: '{{ route("payTec.Transact") }}',
                            method: 'post',
                            timeout: 600000, // Sets a 10-minute timeout (milliseconds)
                            data: {
                                totalChf : parseFloat($('#finalPaySpanSel').html()).toFixed(2),
                                _token: '{{csrf_token()}}'
                            },
                            success: (resTransact) => {
                                var resJSON = $.parseJSON(resTransact);
                                // if((resJSON.CardholderText == 'Transaction OK' || resJSON.CardholderText == 'Verarbeitung OK') && (resJSON.AttendantText == 'Transaction OK' || resJSON.AttendantText == 'Verarbeitung OK')){    
                                if(resJSON.TrxResult == 0){
                                    
                                    paySelProdsSelFinishByCard(resTransact);
                                    POSStarted = false;

                                }else{
                                    registerPayTecErrorDataSel(resTransact,'{{ Auth::user()->sFor }}');
                                }
                            },error: (error) => {
                                registerPayTecErrorDataSel(error, '{{ Auth::user()->sFor }}');
                            }
                        });
                    },error: (error) => {
                        registerPayTecErrorDataSel(error, '{{ Auth::user()->sFor }}');
                    }
                });
            }else{
                paySelProdsSelFinishByCard('none');
                POSStarted = false;
            }
        }
    }


    function paySelProdsSelFinishByCard(resTrx){
        if(hasSndNotiActv){
            if(hasPayOrdSoundSelected){
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg31Sound->soundTitle}}.{{$usrNotiReg31Sound->soundExt}}" autoplay="true"></audio>');
            }else{
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
            }
        }
        $('#payAllBtn2Sel').prop('disabled', true);
        if(!$('#payAllPhaseOne400PCommentSel').val() || $('#payAllPhaseOne400PCommentSel').val().length < 1){
            sendResCommentOrderSel = 'none';
        }else{
            sendResCommentOrderSel = $('#payAllPhaseOne400PCommentSel').val();
        }
        var tNr = $('#payAllTableNrSel').val();
        var res = '{{Auth::user()->sFor}}';
        $.ajax({
            url: '{{ route("dash.closeSelectedProductsTab") }}',
            method: 'post',
            data: {
                tableNr: $('#payAllTableNrSel').val(),
                resId: '{{Auth::user()->sFor}}',
                disReason: $('#discReasonInpSel').val(),
                cashDis: $('#cashDiscountInpSel').val(),
                percDis: $('#percentageDiscountInpSel').val(),
                tvsh: $('#tvshAmProdsPerventageSel').html(),
                thePayM: 'Kartenzahlung',
                selProds : $('#closeOrSelected'+tNr).val(),
                tipp: $('#tipForOrderClosePhOneSel').val(),
                discGCId: $('#payAllGCAppliedIdSel').val(),
                discGCAmnt: $('#payAllGCAppliedCHFValSel').val(),
                resComment: sendResCommentOrderSel,
                payTecTrx: resTrx,
                _token: '{{csrf_token()}}'
            },
            success: (resOrId) => { 
                // $('#payAllPhaseOneDiv1Sel').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
                resOrId = $.trim(resOrId);
                resOrId2D = resOrId.split('||');
                
                // reload script
                    // tavolinat - ikonat
                    $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                    $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                    $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                    $("#tabOrderTotPriceDiv"+tNr).load(location.href+" #tabOrderTotPriceDiv"+tNr+">*","");

                    $('#closeOrSelected'+tNr).val('0');
                    $('#payAllProd'+tNr).show(1);
                    $('#paySelProd'+tNr).hide(1);
                // -------------------------------------------------------------
                $("#payAllPhaseOneBodySel").load(location.href+" #payAllPhaseOneBodySel>*","");

                $('#prodsSelPaySel').val('');
                $('#closeOrSelected'+tNr).val('0');
                $("#topNavbarUlId1").load(location.href+" #topNavbarUlId1>*","");
              
                // set the variable for payment is Selective
                $('#OrQRCodePayIsSelective').val('1');

                $('#payAllBtn2Sel').prop('disabled', false);
                $('#orderQRCodePicImgTel').attr('src','storage/gifs/loading2.gif');
                $('#payAllPhaseOneSel').modal('toggle');
                $.ajax({
                    url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                    method: 'post',
                    data: {
                        id: resOrId2D[1],
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = $.trim(res);
                        res2D = res.split('-8-8-');
                        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                        $('#orderQRCodePicDownloadOI').val(resOrId2D[1]);
                        $('#orderQRCodePicTel').modal('show');

                        $('#OrQRCodePayIsSelectiveTNr').val(tNr);
                    },
                    error: (error) => { console.log(error); }
                });
                
            },
            error: (error) => { console.log(error); }
        });
    }

    function registerPayTecErrorDataSel(resTrx, resId){

        POSStarted = false;
        $('#payAllPhaseOneDiv4Sel').hide(50);
        var resetPayBtn ='<p style="width: 100%;" class="text-center"><strong>Zahlungeart</strong></p>'+
                    '<button id="payAllBtn1Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPaySelProdsCash()"><strong>Bar</strong></button>'+
                    '<button id="payAllBtn2Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="paySelProdsSel(\'none\')"><strong>Karte</strong></button>';
        if (resId == 40){
        resetPayBtn += '<button id="payAllBtn3Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" disabled><strong>Online</strong></button>';
        }else{
        resetPayBtn += '<button id="payAllBtn3Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepOnlinePayPaySel()"><strong>Online</strong></button>';
        }
        resetPayBtn += '<button id="payAllBtn4Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPaySelProdsRechnung()"><strong>Auf Rechnung</strong></button>';
        $('#payAllPhaseOneDiv4Sel').html(resetPayBtn);

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






















    function closePaySelProdsCashModal(){
        $('#paySelPhaseOneDiv1').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
        $("#paySelPhaseOneBody").load(location.href+" #paySelPhaseOneBody>*","");
        $("#paySelProdsCashBody").load(location.href+" #paySelProdsCashBody>*","");
    }
    function prepPaySelProdsCash(){
        $('#paySelProdsCashBtn').prop('disabled', true);

        if($('#paySelTableNr').val() == 0){
            if($('#payAllPhaseOneError3Sel').is(':hidden')){ $('#payAllPhaseOneError3Sel').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsPerventageSel').html() == '--.--'){
            if($('#payAllPhaseOneError1Sel').is(':hidden')){ $('#payAllPhaseOneError1Sel').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsDiscSel').length && !$('#discReasonInpSel').val()){
            if($('#payAllPhaseOneError2Sel').is(':hidden')){ $('#payAllPhaseOneError2Sel').show(100).delay(4000).hide(100); }
        }else{

            $('#paySelTableNrCash').val($('#payAllTableNrSel').val());
            $('#discReasonCashSel').val($('#discReasonInpSel').val());

            $('#cashDiscountInpCashSel').val($('#cashDiscountInpSel').val());
            $('#percentageDiscountInpCashSel').val($('#percentageDiscountInpSel').val());

            $('#tvshAmProdsPerventageCashSel').val($('#tvshAmProdsPerventageSel').html());

            $('#tipForOrderCloseCashSel').val($('#tipForOrderClosePhOneSel').val());

            $('#resCommentOrderCloseCashSel').val($('#payAllPhaseOne400PCommentSel').val());

            $('#paySelPhaseOne').modal('toggle');
            $('#paySelPhaseOne').removeClass('show');

            $('#paySelProdsCashModal').modal('toggle');

            $('body').attr('class','modal-open');

            $('#amToPaySel').html(parseFloat($('#finalPaySpanSel').html()).toFixed(2));
            
            $('#paySelProdsCashBtn').prop('disabled', false);
        }
    }
    function addClPaySel(addVal){
        addVal = parseFloat(addVal);
        var prevVal = parseFloat($('#amByClientSel').html());

        var newVal = parseFloat(addVal + prevVal);
        $('#amByClientSel').html(parseFloat(newVal).toFixed(2));

        var totToPay = parseFloat($('#amToPaySel').html());
        var toRet = parseFloat(newVal - totToPay);
        if(toRet >= 0){
            $('#amClientReturnSel').html(parseFloat(toRet).toFixed(2))
        }
    }
    function paySelProdsCashDel(){
        $('#amByClientSel').html('0.00');
        $('#amClientReturnSel').html('--.--');
    }

    function showPaySelPCMDiv3(){
        paySelProdsCashDel();
        $('#showPaySelPCMDiv3Btn').hide(100);
        $('#paySelPCMDiv3').css('display','flex');
        $('.currBtnSel').prop('disabled', true);
    }

    function cancelPaySelPCMDiv3(){
        $('#amByClientSel').html('0.00');
        $('#amClientReturnSel').html('--.--');
        $('#paySelPCMDiv3').css('display','none');
        $('#showPaySelPCMDiv3Btn').show(100);
        $('#paySelPCMDiv3Inp').val('');

        $('.currBtnSel').prop('disabled', false);
    }

    function newValGivByClSel(){
        if(!$('#paySelPCMDiv3Inp').val()){
            $('#amByClientSel').html('0.00');
            $('#amClientReturnSel').html('--.--');
        }else{
            var newVal = parseFloat($('#paySelPCMDiv3Inp').val());
            if($.isNumeric(newVal) && newVal >= 0){
                var totToPay = parseFloat($('#amToPaySel').html());
                var toRet = parseFloat(newVal - totToPay);
                if(toRet >= 0){
                    $('#amByClientSel').html(parseFloat(newVal).toFixed(2));
                    $('#amClientReturnSel').html(parseFloat(toRet).toFixed(2))
                }else{
                    $('#amByClientSel').html('0.00');
                    $('#amClientReturnSel').html('--.--');
                }
            }else{
                if($('#paySelPCMDiv3Err1').is(':hidden')){ $('#paySelPCMDiv3Err1').show(100).delay(4000).hide(100); }
            }
        }
    }

    function paySelProdsCash(){
        if(hasSndNotiActv){
            if(hasPayOrdSoundSelected){
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg31Sound->soundTitle}}.{{$usrNotiReg31Sound->soundExt}}" autoplay="true"></audio>');
            }else{
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
            }
        }
        if(!$('#resCommentOrderCloseCashSel').val() || $('#resCommentOrderCloseCashSel').val().length < 1){
            sendResCommentOrderCloseCashSel = 'none';
        }else{
            sendResCommentOrderCloseCashSel = $('#resCommentOrderCloseCashSel').val();
        }
        var tNr = $('#paySelTableNrCash').val();
        var res = '{{Auth::user()->sFor}}';
        $('#paySelProdsCashBtn').prop('disabled', true);
        $.ajax({
            url: '{{ route("dash.closeSelectedProductsTab") }}',
            method: 'post',
            data: {
                tableNr: tNr,
                resId: '{{Auth::user()->sFor}}',
                disReason: $('#discReasonInpSel').val(),
                cashDis: $('#cashDiscountInpSel').val(),
                percDis: $('#percentageDiscountInpSel').val(),
                tvsh: $('#tvshAmProdsPerventageSel').html(),
                thePayM: 'Barzahlungen',
                selProds : $('#closeOrSelected'+tNr).val(),
                tipp: $('#tipForOrderCloseCashSel').val(),
                discGCId: $('#payAllGCAppliedIdSel').val(),
                discGCAmnt: $('#payAllGCAppliedCHFValSel').val(),
                resComment: sendResCommentOrderCloseCashSel,
                _token: '{{csrf_token()}}'
            },
            success: (resOrId) => { 
                // $('#payAllPhaseOneDiv1Sel').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
                resOrId = $.trim(resOrId);
                resOrId2D = resOrId.split('||');

                // reload script
                    // tavolinat - ikonat
                    $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                    $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                    $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                    $("#tabOrderTotPriceDiv"+tNr).load(location.href+" #tabOrderTotPriceDiv"+tNr+">*","");

                    $('#closeOrSelected'+tNr).val('0');
                    $('#payAllProd'+tNr).show(1);
                    $('#paySelProd'+tNr).hide(1);
                // -------------------------------------------------------------
                $("#payAllPhaseOneBodySel").load(location.href+" #payAllPhaseOneBodySel>*","");
                $("#paySelProdsCashBody").load(location.href+" #paySelProdsCashBody>*","");
                
                $("#tabOrderBody"+tNr).html('<img src="storage/gifs/loading.gif" style="width:30%; height:auto; margin-left:35%;"  alt="">');
                $("#tabOrder"+tNr).load(location.href+" #tabOrder"+tNr+">*","");
          
                $('#prodsSelPaySel').val('');
                $('#closeOrSelected'+tNr).val('0');
                $("#topNavbarUlId1").load(location.href+" #topNavbarUlId1>*","");
                
                // set the variable for payment is Selective
                $('#OrQRCodePayIsSelective').val('1');
                
                $('#paySelProdsCashBtn').prop('disabled', false);
                $('#orderQRCodePicImgTel').attr('src','storage/gifs/loading2.gif');
                $('#payAllPhaseOneSel').modal('toggle');
                $('#paySelProdsCashModal').modal('toggle');

                $.ajax({
                    url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                    method: 'post',
                    data: {
                        id: resOrId2D[1],
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = $.trim(res);
                        res2D = res.split('-8-8-');
                        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                        $('#orderQRCodePicDownloadOI').val(resOrId2D[1]);
                        $('#orderQRCodePicTel').modal('show');

                        $('#OrQRCodePayIsSelectiveTNr').val(tNr);
                    },
                    error: (error) => { console.log(error); }
                });
                
            },
            error: (error) => { console.log(error); }
        });
    }













    function prepOnlinePayPaySel (){
        if($('#payAllTableNrSel').val() == 0){
            if($('#payAllPhaseOneError3Sel').is(':hidden')){ $('#payAllPhaseOneError3Sel').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsPerventageSel').html() == '--.--'){
            if($('#payAllPhaseOneError1Sel').is(':hidden')){ $('#payAllPhaseOneError1Sel').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsDiscSel').length && !$('#discReasonInpSel').val()){
            if($('#payAllPhaseOneError2Sel').is(':hidden')){ $('#payAllPhaseOneError2Sel').show(100).delay(4000).hide(100); }
        }else{
            if(hasSndNotiActv){
                if(hasPayOrdSoundSelected){
                    $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg31Sound->soundTitle}}.{{$usrNotiReg31Sound->soundExt}}" autoplay="true"></audio>');
                }else{
                    $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
                }
            }
            var tNr = $('#payAllTableNrSel').val();
            if(!$('#resCommentOrderCloseCashSel').val() || $('#resCommentOrderCloseCashSel').val().length < 1){
                sendResCommentOrderCloseCashSel = 'none';
            }else{
                sendResCommentOrderCloseCashSel = $('#resCommentOrderCloseCashSel').val();
            }
            $.ajax({
                url: '{{ route("oPayFStaf.OnlinePayInitiateSelective") }}',
                method: 'post',
                data: {
                    tableNr: $('#payAllTableNrSel').val(),
                    resId: '{{Auth::user()->sFor}}',
                    disReason: $('#discReasonInpSel').val(),
                    cashDis: $('#cashDiscountInpSel').val(),
                    percDis: $('#percentageDiscountInpSel').val(),
                    tvsh: $('#tvshAmProdsPerventageSel').html(),
                    thePayM: 'Online',
                    selProds : $('#closeOrSelected'+tNr).val(),
                    discGCId: $('#payAllGCAppliedIdSel').val(),
                    discGCAmnt: $('#payAllGCAppliedCHFValSel').val(),
                    resComment: sendResCommentOrderCloseCashSel,
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => { 
                    $('#paySelProdsOnlineQRCode').attr('src','storage/OnlinePayStaf/'+respo);
                    $('#payAllPhaseOneSel').modal('toggle');
                    $('#paySelProdsOnlineModal').modal('show');

                    $("#payAllPhaseOneBodySel").load(location.href+" #payAllPhaseOneBodySel>*","");
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    function closePaySelProdsOnlineModal(){
        $('#payAllPhaseOneDiv1Sel').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
        $("#payAllPhaseOneBodySel").load(location.href+" #payAllPhaseOneBodySel>*","");
    }


    


















    function closePaySelProdsRechnungModal(){
        $('#payAllPhaseOneDiv1').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
        $("#payAllPhaseOneBody").load(location.href+" #payAllPhaseOneBody>*","");
        $("#paySelProdsRechnungModal").load(location.href+" #paySelProdsRechnungModal>*","");
    }

    function prepPaySelProdsRechnung(){
        if($('#paySelTableNr').val() == 0){
            if($('#payAllPhaseOneError3Sel').is(':hidden')){ $('#payAllPhaseOneError3Sel').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsPerventageSel').html() == '--.--'){
            if($('#payAllPhaseOneError1Sel').is(':hidden')){ $('#payAllPhaseOneError1Sel').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsDiscSel').length && !$('#discReasonInpSel').val()){
            if($('#payAllPhaseOneError2Sel').is(':hidden')){ $('#payAllPhaseOneError2Sel').show(100).delay(4000).hide(100); }
        }else{
            var tnr =$('#payAllTableNrSel').val();
            $('#paySelSelectedProdsRechnung').val($('#closeOrSelected'+tnr).val());

            $('#paySelTableNrRechnung').val($('#payAllTableNrSel').val());
            $('#discReasonRechnungSel').val($('#discReasonInpSel').val());

            $('#cashDiscountInpRechnungSel').val($('#cashDiscountInpSel').val());
            $('#percentageDiscountInpRechnungSel').val($('#percentageDiscountInpSel').val());

            $('#tvshAmProdsPerventageRechnungSel').val($('#tvshAmProdsPerventageSel').html());

            $('#tipForOrderCloseRechnungSel').val($('#tipForOrderClosePhOneSel').val());

            $('#payAllRechnungGiftCardIdSel').val($('#payAllGCAppliedIdSel').val());
            $('#payAllRechnungGiftCardAmountSel').val($('#payAllGCAppliedCHFValSel').val());

            if(!$('#payAllPhaseOne400PCommentSel').val() || $('#payAllPhaseOne400PCommentSel').val().length < 1){
                $('#payAllRechnungResCommentSel').val('none');
            }else{
                $('#payAllRechnungResCommentSel').val($('#payAllPhaseOne400PCommentSel').val());
            }

            $('#payAllPhaseOneSel').modal('toggle');
            $('#payAllPhaseOneSel').removeClass('show');

            $('#paySelProdsRechnungModal').modal('toggle');

            $('body').attr('class','modal-open');

            if($('#tvshAmProdsDiscSel').length){
                // discoun Active 
                $('#amToPayRechnungSel').html(parseFloat(parseFloat($('#totalDiscSel').html()) + parseFloat($('#tipForOrderCloseRechnungSel').val())).toFixed(2));
            }else{
                $('#amToPayRechnungSel').html(parseFloat(parseFloat($('#totAmProdsSel').html()) + parseFloat($('#tipForOrderCloseRechnungSel').val())).toFixed(2));
            }
        }
    }

    function paySelVerifyTelSendNr(){
        if(!$('#RechnungTelInpSel').val()){
            if($('#paySelProdsRechnungModalErr08').is(":hidden")){ $('#paySelProdsRechnungModalErr08').show(100).delay(4000).hide(100); }
        }else{
            var pNr = $('#RechnungTelInpSel').val().replace(/ /g,'');
            
            if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                pNr = '0'+pNr.toString().slice(3);
            }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                pNr = '0'+pNr.toString().slice(2);
            }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                pNr = '0'+pNr.toString().slice(4);
            }
         
            if(pNr.length < 9 || pNr.length > 10){
                if($('#paySelProdsRechnungModalErr09').is(":hidden")){ $('#paySelProdsRechnungModalErr09').show(100).delay(4000).hide(100); }
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
                            if($('#paySelProdsRechnungModalErr10').is(":hidden")){ $('#paySelProdsRechnungModalErr10').show(100).delay(4000).hide(100); }
                        }else{
                            $('#telVerCodeFromSerSel').val(respo);
                            $('#paySelPRMDiv1TelVer01').hide(100);
                            $('#paySelPRMDiv1TelVer02').show(100);
                            $('#RechnungTelInpSel').val(pNr);
                            if(pNr == '0763270293' || pNr == '0763251809' || pNr == '0763459941' || pNr == '0763469963' || pNr == '760000000' || pNr == '0760000000'){
                                $('#telVerCodeDemoShowSel').html('<strong>"Testing" Demo code: '+respo+'</strong>');
                                $('#telVerCodeDemoShowSel').show(100);
                                $('#RechnungTelCodeInpSel').val(respo);
                            }
                        }
					},
					error: (error) => { console.log(error); }
				});
            }
        }
    }
    function paySelVerifyTelSendCode(){
        if(!$('#RechnungTelCodeInpSel').val()){
            if($('#paySelProdsRechnungModalErr11').is(":hidden")){ $('#paySelProdsRechnungModalErr11').show(100).delay(4000).hide(100); }
        }else{
            var codeFromCl = $('#RechnungTelCodeInpSel').val();
            if(codeFromCl.length != 6){
                if($('#paySelProdsRechnungModalErr12').is(":hidden")){ $('#paySelProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(codeFromCl != $('#telVerCodeFromSerSel').val()){
                if($('#paySelProdsRechnungModalErr12').is(":hidden")){ $('#paySelProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(codeFromCl == $('#telVerCodeFromSerSel').val()){
                $('#paySelPRMDiv1TelVer02').hide(100);
                $('#paySelProdsRechnungModalSucc01').html('Telefonnummer "'+$('#RechnungTelInp').val()+'" erfolgreich verifiziert');
                $('#paySelProdsRechnungModalSucc01').show(100);
                $('#telVerCodeDemoShowSel').hide(100);
                $('#telVerValidationStatusSel').val("1");
            }
        }
    }

    var padUsedSel = 0;
    var signaturePadSel = $('#signaturePadSel').signature({ syncField: '#signature64Sel', syncFormat: 'PNG', });
    var signaturePad2Sel = $('#signaturePad2Sel').signature({ syncField: '#signature64Sel', syncFormat: 'PNG', });
    var signaturePad3Sel = $('#signaturePad3Sel').signature({ syncField: '#signature64Sel', syncFormat: 'PNG', });
    var signaturePad4Sel = $('#signaturePad4Sel').signature({ syncField: '#signature64Sel', syncFormat: 'PNG', });
    var signaturePad5Sel = $('#signaturePad5Sel').signature({ syncField: '#signature64Sel', syncFormat: 'PNG', });

    $('#clearSignatureSel').click(function(esel) {
        esel.preventDefault();
        padUsedSel++;

        // signaturePad.signature('clear');
        $("#signature64Sel").val('');
        $("#signaturePrewSel").attr('src','');

        $('#paySelRechnungsignatureModalAttempt').html('Versuch '+parseInt(padUsedSel + parseInt(1))+'/5')
        if(padUsedSel == 1){
            $('#signaturePadSel').hide(100);
            $('#signaturePad2Sel').show(100);
        }else if(padUsedSel == 2){
            $('#signaturePad2Sel').hide(100);
            $('#signaturePad3Sel').show(100);
        }else if(padUsedSel == 3){
            $('#signaturePad3Sel').hide(100);
            $('#signaturePad4Sel').show(100);
        }else if(padUsedSel == 4){
            $('#signaturePad4Sel').hide(100);
            $('#signaturePad5Sel').show(100);
        }
    });

    function clickSaveSignaturePARechnungSel(){
        padUsedSel++;
        var imageSel = new Image();
        imageSel = document.getElementById("signature64Sel").value;
        document.getElementById('signaturePrewSel').src = imageSel;
        $('#paySelRechnungsignatureModalAttempt').html('Versuch '+parseInt(padUsedSel + parseInt(1))+'/5')
        closepaySelRechnungsignatureModal();

        if(padUsedSel == 1){
            $('#signaturePadSel').hide(100);
            $('#signaturePad2Sel').show(100);
        }else if(padUsedSel == 2){
            $('#signaturePad2Sel').hide(100);
            $('#signaturePad3Sel').show(100);
        }else if(padUsedSel == 3){
            $('#signaturePad3Sel').hide(100);
            $('#signaturePad4Sel').show(100);
        }else if(padUsedSel == 4){
            $('#signaturePad4Sel').hide(100);
            $('#signaturePad5Sel').show(100);
        }
    }
    function closepaySelRechnungsignatureModal(){
        $('#paySelRechnungsignatureModal').modal('hide');
        $('body').addClass('modal-open');
    }

    function finishPayRechnungSel(){
        if(!$('#RechnungFirmaInpSel').val()){
            if($('#paySelProdsRechnungModalErr01').is(":hidden")){ $('#paySelProdsRechnungModalErr01').show(100).delay(4000).hide(100); }
        }else if($('#telVerValidationStatusSel').val() == '0'){
            if($('#paySelProdsRechnungModalErr02').is(":hidden")){ $('#paySelProdsRechnungModalErr02').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungNameInpSel').val()){
            if($('#paySelProdsRechnungModalErr03').is(":hidden")){ $('#paySelProdsRechnungModalErr03').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungVornameInpSel').val()){
            if($('#paySelProdsRechnungModalErr04').is(":hidden")){ $('#paySelProdsRechnungModalErr04').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungStrNrInpSel').val() || !$('#RechnungPlzOrtInpSel').val()){
            if($('#paySelProdsRechnungModalErr05').is(":hidden")){ $('#paySelProdsRechnungModalErr05').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungLandInpSel').val()){
            if($('#paySelProdsRechnungModalErr06').is(":hidden")){ $('#paySelProdsRechnungModalErr06').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungEmailInpSel').val()){
            if($('#paySelProdsRechnungModalErr07').is(":hidden")){ $('#paySelProdsRechnungModalErr07').show(100).delay(4000).hide(100); }
        }else if(!$('#RechnungDaysToPayInpSel').val()){
            if($('#paySelProdsRechnungModalErr13').is(":hidden")){ $('#paySelProdsRechnungModalErr13').show(100).delay(4000).hide(100); }
        }else if($('#signaturePrewSel').attr('src') == ''){
            if($('#paySelProdsRechnungModalErr15').is(":hidden")){ $('#paySelProdsRechnungModalErr15').show(100).delay(4000).hide(100); }
        }else{
            var emailInp = $('#RechnungEmailInpSel').val();
            var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,5}\b$/i
            var dayToPay = $('#RechnungDaysToPayInpSel').val();
            if(!pattern.test(emailInp)){
                if($('#paySelProdsRechnungModalErr12').is(":hidden")){ $('#paySelProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(Math.floor(dayToPay) != dayToPay || !$.isNumeric(dayToPay) || dayToPay < 1) {
                if($('#paySelProdsRechnungModalErr14').is(":hidden")){ $('#paySelProdsRechnungModalErr14').show(100).delay(4000).hide(100); } 
            }else{
                $('#finishPayRechnungSelBtn').prop('disabled', true);
                // success
                // set the variable for payment is Selective
                $('#OrQRCodePayIsSelective').val('1');
                $('#OrQRCodePayIsSelectiveTNr').val($('#paySelTableNrRechnung').val());

                $('#paySelPRechnungMDiv4BTNs').html('<img src="storage/gifs/loading2.gif" style="width:20%; margin-left:40%;" alt="">');
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
                $('#paySelProdsRechnungForm').submit();

            }
        }
    }

    function finishPayRechnungSelCL(){
        // set the variable for payment is Not Selective
        $('#OrQRCodePayIsSelective').val('0');
        $('#cancelClSelectRechnungSelBtn').prop('disabled', true);
        $('#paySelRechnungCommentClientDiv').prop('disabled', true);
        $('#finishPayRechnungSelCLBtn').prop('disabled', true);
        $('#finishPayRechnungSelCLBtn').html('<img src="storage/gifs/loading2.gif" style="width:30px; height:auto;" alt="">');
        $('#paySelProdsRechnungForm').submit();
    }

    function asveClientForAufRechnungSel(){
        if($("#saveClForARechnungSel").is(':checked')){
            $('#saveClForARechnungSelVal').val('1');
        }else{
            $('#saveClForARechnungSelVal').val('0');
        }
    }

    function cancelClSelectRechnungSel(){
        $('#paySelPRechnungMDiv1').attr('style','display:flex;');
        $('#paySelPRechnungMDiv2').attr('style','display:flex;');
        $('#paySelPRechnungMDiv3').attr('style','display:flex;');
        $('#paySelPRechnungMDiv4').attr('style','display:flex;');

        $('#cancelClSelectRechnungSelBtn').hide(10);
        $('#paySelRechnungCommentClientDiv').hide(10);
        $('#finishPayRechnungSelCLBtn').hide(10);

        $('#clientOneRechnungSel'+$('#usedAndExistingClientIdSel').val()).removeClass('btn-dark');
        $('#clientOneRechnungSel'+$('#usedAndExistingClientIdSel').val()).addClass('btn-ouline-dark');

        $('#usedAndExistingClientSel').val('0');
        $('#usedAndExistingClientIdSel').val('0');
    }

    function selectClRechnungSel(clId){
        $('#paySelPRechnungMDiv1').attr('style','display:none;');
        $('#paySelPRechnungMDiv2').attr('style','display:none;');
        $('#paySelPRechnungMDiv3').attr('style','display:none;');
        $('#paySelPRechnungMDiv4').attr('style','display:none;');

        $('#cancelClSelectRechnungSelBtn').show(10);
        $('#paySelRechnungCommentClientDiv').show(10);
        $('#finishPayRechnungSelCLBtn').show(10);
        
        if($('#usedAndExistingClientIdSel').val() != 0){
            $('#clientOneRechnungSel'+$('#usedAndExistingClientIdSel').val()).removeClass('btn-dark');
            $('#clientOneRechnungSel'+$('#usedAndExistingClientIdSel').val()).addClass('btn-outline-dark');
        }
        $('#clientOneRechnungSel'+clId).removeClass('btn-outline-dark');
        $('#clientOneRechnungSel'+clId).addClass('btn-dark');
       
        $('#usedAndExistingClientSel').val('1');
        $('#usedAndExistingClientIdSel').val(clId);
    }

</script>