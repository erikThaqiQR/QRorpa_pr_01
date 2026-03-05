<?php
    use App\Restorant;
?>
<script type="module">
    
    import QrScanner from "/js/FP-qr-scanner.min.js";
    function payAllOpenCameraGC(){
        const video = document.getElementById('payALL-qr-video');

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
                            $('#payAllgcValidationCodeInput').val(respo);
                            $('#payAllCameraModal').modal('hide');
                            $('body').attr('class','modal-open');
                            scanner.stop();
                            payAllValidateGC();
                        }else if(respo == 'invalideQRCode'){
                            if( $('#payAllPhaseOneError511').is(':hidden') ){ $('#payAllPhaseOneError511').show(100).delay(4500).hide(100); }
                            $('#payAllCameraModal').modal('hide');
                            $('body').attr('class','modal-open');
                            scanner.stop();
                        }else if(respo == 'gcNotSoldYet'){
                            if( $('#payAllPhaseOneError512').is(':hidden') ){ $('#payAllPhaseOneError512').show(100).delay(4500).hide(100); }
                            $('#payAllCameraModal').modal('hide');
                            $('body').attr('class','modal-open');
                            scanner.stop();
                        }
					},
					error: (error) => { console.log(error); }
				});
            }
        }
    }
    var btn = document.getElementById("payAllOpenCameraModalBtn");
    // Assigning event listeners to the button
    btn.addEventListener("click", payAllOpenCameraGC);
    
</script>

<script>

    function closePayAllCameraModal(){
        $('#payAllCameraModal').modal('toggle');
        $('body').attr('class','modal-open');
    }


    function closePayAllPhaseOne(){
        $('#payAllPhaseOneDiv1').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
        $("#payAllPhaseOneBody").load(location.href+" #payAllPhaseOneBody>*","");

        $('#payAllPhaseOne').modal('toggle');
        $('#tabOrder'+$("#payAllTableNr").val()).modal('toggle');
    }
    function prepPayAllProds(tNr,resId){
        $('#payAllBtn1').prop('disabled', true);
        $('#payAllBtn2').prop('disabled', true);
        $('#payAllBtn3').prop('disabled', true);
        $('#payAllBtn4').prop('disabled', true);
        $('#tabOrder'+tNr).modal('toggle');
        $('#tabOrder'+tNr).removeClass('show');
        $('body').attr('class','modal-open');
        $('#payAllTableNr').val(tNr);

        $.ajax({
			url: '{{ route("admin.payAllFetchOrders") }}',
            dataType: 'json',
			method: 'post',
			data: {
				tNr: tNr,
				resId: resId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                var totPay = parseFloat(0);
                $.each(respo, function(index, value){
                    $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left">'+value.OrderSasia+'x '+value.OrderEmri+'</p>');
                    $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right">CHF '+parseFloat(value.OrderQmimi).toFixed(2)+'</p>');
                    totPay += parseFloat(value.OrderQmimi);
                });
                if($('#resTvshInput').val() == 0){
                    var mwst = parseFloat(0);
                }else{
                    var mwst = parseFloat(totPay * 0.074930619);
                }
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-bottom:8px;" class="text-left"><strong>Total inkl.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-bottom:8px;" class="text-right"><strong>CHF <span id="totAmProds">'+parseFloat(totPay).toFixed(2)+'</span></strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllPhaseOneDiv1TippP11" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>Tipp.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllPhaseOneDiv1TippP12" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="tippAmProds">0.00</span></strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllstaffDiscountShow01" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>Rabatt vom Personal.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllstaffDiscountShow02" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="staffDiscSpan">0.00</span></strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllgiftCardDiscountShow01" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>Rabatt von der Geschenkkarte.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllgiftCardDiscountShow02" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="giftCardDiscSpan">0.00</span></strong></p>');
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>MwSt.</strong></p>');
                if($('#resTvshInput').val() == 0){
                    $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="tvshAmProds">'+parseFloat(0).toFixed(2)+'</span> (<span id="tvshAmProdsPerventage">0.00</span> %)</strong></p>');
                }else{
                    $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="tvshAmProds">'+parseFloat(mwst).toFixed(2)+'</span> (<span id="tvshAmProdsPerventage">8.10</span> %)</strong></p>');
                }
                $('#payAllPhaseOneDiv1').append('<p id="payAllFinalPayShow01" style="width: 50%; margin-top:-8px; margin-bottom:8px; font-size:1.1rem;" class="text-left"><strong>Letzte Bezahlung.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllFinalPayShow02" style="width: 50%; margin-top:-8px; margin-bottom:8px; font-size:1.1rem;" class="text-right"><strong>CHF <span id="finalPaySpan">'+parseFloat(totPay).toFixed(2)+'</span></strong></p>');
                
                $('#payAllBtn1').prop('disabled', false);
                $('#payAllBtn2').prop('disabled', false);
                $('#payAllBtn3').prop('disabled', false);
                $('#payAllBtn4').prop('disabled', false);
            },
			error: (error) => { console.log(error); }
		});
    }

    function selectDiv2(type){
        if(type == 'Res'){
            if($('#pAPhOneD2Btn2').hasClass('btn-success')){
                $('#pAPhOneD2Btn2').removeClass('btn-success');
                $('#pAPhOneD2Btn2').addClass('btn-dark');
            }
            $('#pAPhOneD2Btn1').removeClass('btn-dark');
            $('#pAPhOneD2Btn1').addClass('btn-success');
            if($('#resTvshInput').val() != 0){
                $('#tvshAmProdsPerventage').html('8.10');
            }
            var tot = parseFloat($('#finalPaySpan').html());
            var mwst = parseFloat(tot * 0.074930619);
            $('#tvshAmProds').html(parseFloat(mwst).toFixed(2));
            if ($('#tvshAmProdsDisc').length){
                var totDisc = parseFloat($('#totalDisc').html());
                var mwstDisc = parseFloat(totDisc * 0.074930619);
                $('#tvshAmProdsDisc').html(parseFloat(mwstDisc).toFixed(2));
                $('#tvshAmProdsPerventageDisc').html(parseFloat(8.10).toFixed(2));
            }

        }else if(type == 'House'){
            if($('#pAPhOneD2Btn1').hasClass('btn-success')){
                $('#pAPhOneD2Btn1').removeClass('btn-success');
                $('#pAPhOneD2Btn1').addClass('btn-dark');
            }
            $('#pAPhOneD2Btn2').removeClass('btn-dark');
            $('#pAPhOneD2Btn2').addClass('btn-success');

            if($('#resTvshInput').val() != 0){
                $('#tvshAmProdsPerventage').html('2.60');
            }
            var tot = parseFloat($('#finalPaySpan').html());
            var mwst = parseFloat(tot * 0.025341130);
            $('#tvshAmProds').html(parseFloat(mwst).toFixed(2));

            if ($('#tvshAmProdsDisc').length){
                var totDisc = parseFloat($('#totalDisc').html());
                var mwstDisc = parseFloat(totDisc * 0.025341130);
                $('#tvshAmProdsDisc').html(parseFloat(mwstDisc).toFixed(2));
                $('#tvshAmProdsPerventageDisc').html(parseFloat(2.60).toFixed(2));
            }
        }
        payAllReloadFinalPay();
    }




    // Funksionet per bakshish 
    function setTipStaf(btnId, tipVal){
        var currTipp = parseFloat($('#tippAmProds').html()).toFixed(2);
        var tipAdd = parseFloat(tipVal).toFixed(2);
        if($('#totalDisc').length){
            var tot = parseFloat($('#totalDisc').html()).toFixed(2);
        }else{
            var tot = parseFloat($('#totAmProds').html()).toFixed(2);
        }
        var totWTip = parseFloat(parseFloat(tot) + parseFloat(tipAdd)).toFixed(2);

        $('#tippAmProds').html(tipAdd);
        $('#tipForOrderClosePhOne').val(tipAdd);
        payAllReloadFinalPay();
       
        $('#tipWaiterCosVal').val(0);
    }


    function cancelTipStaf(btnId){
        $('#tippAmProds').html('0.00');
        $('#tipForOrderClosePhOne').val(0);
        $('#tipWaiterCosVal').val(0);

        payAllReloadFinalPay();
    }

    function setCostumeTipStaf(currVal){
        $('#tippAmProds').html("0.00");
        $('#tipForOrderClosePhOne').val("0");
        payAllReloadFinalPay();
        if(currVal == ''){
            $('#tippAmProds').html("0.00");
            $('#tipForOrderClosePhOne').val("0");
            if( !$('#payAllPhaseOneError4').is(':hidden') ){ $('#payAllPhaseOneError4').hide(10); }
            payAllReloadFinalPay();
        }else{
            var valForTip = parseFloat(currVal).toFixed(2);
            var tot = parseFloat($('#finalPaySpan').html()).toFixed(2);

            if(parseFloat(valForTip) < parseFloat(tot)){
                if( $('#payAllPhaseOneError4').is(':hidden') ){ $('#payAllPhaseOneError4').show(10); }
                $('#tippAmProds').html(+parseFloat(0).toFixed(2));
                $('#tippAmProds').html("0.00");
                $('#tipForOrderClosePhOne').val("0");
                payAllReloadFinalPay();
            }else{
                if( !$('#payAllPhaseOneError4').is(':hidden') ){ $('#payAllPhaseOneError4').hide(10); }
                var tipAdd = parseFloat(parseFloat(valForTip) - parseFloat(tot)).toFixed(2);
                var totWTip = parseFloat(parseFloat(tot) + parseFloat(tipAdd)).toFixed(2);

                $('#tippAmProds').html(parseFloat(tipAdd).toFixed(2));
                $('#tipForOrderClosePhOne').val(tipAdd);

                payAllReloadFinalPay();
            }
        }
    }
    // ---------------------------------------------------------------------------------











    function callInCash(){
        $('#div3inCashBtn').hide(100);
        $('#div3inPercentageBtn').hide(100);
        $('#div3InCash').show(100);

        payAllReloadFinalPay();
    }
    function procesInCah(){
        if(!$('#div3InCashInp').val()){
            // zerro discount
            $('#cashDiscountInp').val(0);
        }else{
            var cashOff = $('#div3InCashInp').val();
            if($.isNumeric(cashOff)){
                var tot = parseFloat($('#finalPaySpan').html());
                var totAD = parseFloat(tot - parseFloat(cashOff));
                $('#cashDiscountInp').val(cashOff);
                $('#staffDiscSpan').html(parseFloat(cashOff).toFixed(2));
              
                payAllReloadFinalPay();
            }else{
                // not a number , display error msg
                $('#cashDiscountInp').val(0);
                if($('#div3InCashError1').is(':hidden')){ $('#div3InCashError1').show(100).delay(4000).hide(100); }
                payAllReloadFinalPay();
            }
        }
    }
    function cancelInCash(){
        $('#div3inCashBtn').show(100);
        $('#div3inPercentageBtn').show(100);

        $('#div3InCash').hide(100);
        $('#cashDiscountInp').val(0);
        $('#div3InCashInp').val(0);

        $('#staffDiscSpan').html(parseFloat(0).toFixed(2));
        payAllReloadFinalPay();
    }








    function callInPercentage(){
        $('#div3inCashBtn').hide(100);
        $('#div3inPercentageBtn').hide(100);
        $('#div3InPercentage').show(100);

        payAllReloadFinalPay();
    }
    function procesInPercentage(){
        if(!$('#div3InPercentageInp').val()){
            // zerro discount
            $('#percentageDiscountInp').val(0);
        }else{
            var percOff = $('#div3InPercentageInp').val();
            if($.isNumeric(percOff) && percOff > 0 && percOff <= 100){
                var tot = parseFloat($('#totAmProds').html());
                var cashDisc = parseFloat(tot * (percOff/100));
                var disChekc = parseFloat(cashDisc*100).toFixed(2);
                if(!(disChekc % parseFloat(5).toFixed(2) == 0)){
                    cashDisc = (Math.ceil(cashDisc*20)/20).toFixed(2);
                    var newPercOff = parseFloat((100*cashDisc)/tot).toFixed(6);
                }else{
                    var newPercOff = parseFloat(percOff).toFixed(2);
                }
                var totAD = parseFloat(tot - parseFloat(cashDisc));
               
                $('#percentageDiscountInp').val(newPercOff);
                $('#staffDiscSpan').html(parseFloat(cashDisc).toFixed(2));
            }else{
                // not a number , display error msg
                $('#percentageDiscountInp').val(0);
                if($('#div3InPercentageError1').is(':hidden')){ $('#div3InPercentageError1').show(100).delay(4000).hide(100); }
            }
        }
        payAllReloadFinalPay();
    }
    function cancelInPercentage(){
        $('#div3inCashBtn').show(100);
        $('#div3inPercentageBtn').show(100);
        
        $('#div3InPercentage').hide(100);
        $('#percentageDiscountInp').val(0);
        $('#div3InPercentageInp').val(0);
        $('#staffDiscSpan').html(parseFloat(0).toFixed(2));
  
        payAllReloadFinalPay();
    }

    function payAllReloadFinalPay(){
        var tot = parseFloat($('#totAmProds').html()).toFixed(2);
        var currTipp = parseFloat($('#tippAmProds').html()).toFixed(2);
        var cashDiscStaff = parseFloat($('#cashDiscountInp').val()).toFixed(2);
        var discFromGC = parseFloat($('#payAllGCAppliedCHFVal').val()).toFixed(2);
        if($('#percentageDiscountInp').val() > 0){
            var percOff = $('#percentageDiscountInp').val();
            var cashDiscFromPerc = parseFloat(tot * (percOff/100));
            var totWithDiscTip = parseFloat( parseFloat(tot) + parseFloat(currTipp) - parseFloat(cashDiscFromPerc) - parseFloat(discFromGC)).toFixed(2);
        }else{
            var totWithDiscTip = parseFloat( parseFloat(tot) + parseFloat(currTipp) - parseFloat(cashDiscStaff) - parseFloat(discFromGC)).toFixed(2);
        }
        $('#finalPaySpan').html(parseFloat(totWithDiscTip).toFixed(2));

        if($('#resTvshInput').val() == 0){
            var mwstValCal = parseFloat(0).toFixed(10);
        }else{
            if( $('#tvshAmProdsPerventage').html() == '8.10'){
                var mwstValCal = parseFloat(0.074930619).toFixed(10);
            }else if($('#tvshAmProdsPerventage').html() == '2.60'){
                var mwstValCal = parseFloat(0.074930619).toFixed(10);
            }
        }

        var tot = parseFloat($('#finalPaySpan').html());
        var totWithGC = parseFloat(parseFloat(tot) + parseFloat(discFromGC));
        var mwst = parseFloat(totWithGC * mwstValCal);
        $('#tvshAmProds').html(parseFloat(mwst).toFixed(2));

        if(tot == 0 && discFromGC > 0){
            $('#payAllPhaseOneDiv4').html('<p style="width: 100%;" class="text-center"><strong>Zahlungeart</strong></p>');
            $('#payAllPhaseOneDiv4').append('<button id="payAllBtn2" style="width:100%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="payAllProds(\'GCAllPay\')"><strong>Bestellung bezahlen</strong></button>');
        }else{
            $('#payAllPhaseOneDiv4').html('<p style="width: 100%;" class="text-center"><strong>Zahlungeart</strong></p>');
            $('#payAllPhaseOneDiv4').append('<button id="payAllBtn1" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPayAllProdsCash()"><strong>Bar</strong></button>');
            $('#payAllPhaseOneDiv4').append('<button id="payAllBtn2" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="payAllProds(\'none\')"><strong>Karte</strong></button>');
            $('#payAllPhaseOneDiv4').append('<button id="payAllBtn3" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepOnlinePayPayAll()"><strong>Online</strong></button>');
            $('#payAllPhaseOneDiv4').append('<button id="payAllBtn4" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPayAllProdsRechnung()"><strong>Auf Rechnung</strong></button>');
        }
    }


    function payAllValidateGC(){
        if(!$('#payAllgcValidationCodeInput').val()){
            if( $('#payAllPhaseOneError51').is(':hidden') ){ $('#payAllPhaseOneError51').show(100).delay(4500).hide(100); }
        }else{
            $('#payAllValidateGCBtn').prop('disabled', true);
            $('#payAllgcValidationCodeInput').prop('disabled', true);
            $.ajax({
				url: '{{ route("giftCard.giftCardValidateTheIdnCode") }}',
				method: 'post',
				data: {
					gcIdnCode: $('#payAllgcValidationCodeInput').val(),
					_token: '{{csrf_token()}}'
				},
				success: (response) => {
					response = $.trim(response);
                    if(response == 'gcNotFound'){
                        if( $('#payAllPhaseOneError52').is(':hidden') ){ $('#payAllPhaseOneError52').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtn').prop('disabled', false);
                        $('#payAllgcValidationCodeInput').prop('disabled', false);
                    }else if(response == 'gcIsAllSpend'){
                        if( $('#payAllPhaseOneError53').is(':hidden') ){ $('#payAllPhaseOneError53').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtn').prop('disabled', false);
                        $('#payAllgcValidationCodeInput').prop('disabled', false);
                    }else if(response == 'gcIsNotPaid'){
                        if( $('#payAllPhaseOneError58').is(':hidden') ){ $('#payAllPhaseOneError58').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtn').prop('disabled', false);
                        $('#payAllgcValidationCodeInput').prop('disabled', false);
                    }else if(response == 'gcExpired'){
                        if( $('#payAllPhaseOneError59').is(':hidden') ){ $('#payAllPhaseOneError59').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtn').prop('disabled', false);
                        $('#payAllgcValidationCodeInput').prop('disabled', false);
                    }else if(response == 'gcNotOfThisRes'){
                        if( $('#payAllPhaseOneError510').is(':hidden') ){ $('#payAllPhaseOneError510').show(100).delay(4500).hide(100); }
                        $('#payAllValidateGCBtn').prop('disabled', false);
                        $('#payAllgcValidationCodeInput').prop('disabled', false);
                    }else{
                        respo2D = response.split('|||');
                        $('#payAllPhaseOneDiv5_3').show(10);
                        $('#payAllPhaseOneDiv5_4').show(10);
                        $('#payAllPhaseOneDiv5_5').show(10);
                        $('#payAllPhaseOneDiv5_6').show(10);
                        $('#payAllamountLeftChf').html(respo2D[1]);
                        $('#payAllGCAppliedId').val(respo2D[0]);

                        $('#payAllValidateGCBtn').prop('disabled', false);
                        $('#payAllValidateGCBtn').attr('class','btn btn-danger shadow-none');
                        $('#payAllValidateGCBtn').attr('onclick','payAllCancelGC()');
                        $('#payAllValidateGCBtn').html('Stornieren');
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    }
    function payAllCancelGC(){
        $('#payAllValidateGCBtn').attr('class','btn btn-outline-dark shadow-none');
        $('#payAllValidateGCBtn').attr('onclick','payAllValidateGC()');
        $('#payAllgcValidationCodeInput').prop('disabled', false);
        $('#payAllgcValidationCodeInput').val('');
        $('#payAllApplyDiscFromGcInput').val('');
        $('#payAllGCAppliedId').val(0);
        $('#payAllGCAppliedCHFVal').val(0);
        $('#payAllValidateGCBtn').html('Bestätigen');
        $('#giftCardDiscSpan').html(parseFloat(0).toFixed(2));

        $('#payAllPhaseOneDiv5_3').hide(10);
        $('#payAllPhaseOneDiv5_4').hide(10);
        $('#payAllPhaseOneDiv5_5').hide(10);
        $('#payAllPhaseOneDiv5_6').hide(10);

        payAllReloadFinalPay();
    }

    function payAllApplyGCDiscount(){
        if(!$('#payAllApplyDiscFromGcInput').val()){
            if( $('#payAllPhaseOneError54').is(':hidden') ){ $('#payAllPhaseOneError54').show(100).delay(4500).hide(100); }
        }else{
            var dicsValFromGC = parseFloat($('#payAllApplyDiscFromGcInput').val()).toFixed(2);
            if(dicsValFromGC <= 0){
                if( $('#payAllPhaseOneError54').is(':hidden') ){ $('#payAllPhaseOneError54').show(100).delay(4500).hide(100); }
            }else if($('#payAllGCAppliedId').val() == 0){
                if( $('#payAllPhaseOneError55').is(':hidden') ){ $('#payAllPhaseOneError55').show(100).delay(4500).hide(100); }
            }else{
                $.ajax({
                    url: '{{ route("giftCard.giftCardValidateTheSumToApplyDisc") }}',
                    method: 'post',
                    data: {
                        gcId: $('#payAllGCAppliedId').val(),
                        gcDiscAmnt: dicsValFromGC,
                        _token: '{{csrf_token()}}'
                    },
                    success: (response) => {
                        response = $.trim(response);
                        if(response == 'gcNotFound'){
                            if( $('#payAllPhaseOneError55').is(':hidden') ){ $('#payAllPhaseOneError55').show(100).delay(4500).hide(100); }
                        }else if(response == 'gcAmountNotAvailable'){
                            if( $('#payAllPhaseOneError56').is(':hidden') ){ $('#payAllPhaseOneError56').show(100).delay(4500).hide(100); }
                        }else{
                            response = parseFloat(response).toFixed(2);
                            var tot = parseFloat($('#finalPaySpan').html()).toFixed(2);

                            if(Number(tot) >= Number(response)){
                                $('#giftCardDiscSpan').html(parseFloat(response).toFixed(2));
                                $('#payAllGCAppliedCHFVal').val(parseFloat(response).toFixed(2));
                                $('#payAllPhaseOneDiv5_3').hide(10);
                                $('#payAllPhaseOneDiv5_4').hide(10);
                                $('#payAllPhaseOneDiv5_5').hide(10);
                                $('#payAllPhaseOneDiv5_6').hide(10);
                                $('#payAllgcValidationCodeInput').val($('#payAllgcValidationCodeInput').val()+" / "+parseFloat(response).toFixed(2)+" CHF");

                                payAllReloadFinalPay();
                            }else{
                                // to much discount
                                if( $('#payAllPhaseOneError57').is(':hidden') ){ $('#payAllPhaseOneError57').show(100).delay(4500).hide(100); }
                            }
                        }
                       
                    },
                    error: (error) => { console.log(error); }
                });
            }
        }
    }

    function payAllApplyGCDiscountMax(){
        if($('#payAllGCAppliedId').val() == 0){
            if( $('#payAllPhaseOneError55').is(':hidden') ){ $('#payAllPhaseOneError55').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
                url: '{{ route("giftCard.giftCardValidateTheSumToApplyDiscMax") }}',
                method: 'post',
                data: {
                    gcId: $('#payAllGCAppliedId').val(),
                    toPayAmnt: $('#finalPaySpan').html(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    response = $.trim(response);
                    if(response == 'gcNotFound'){
                        if( $('#payAllPhaseOneError55').is(':hidden') ){ $('#payAllPhaseOneError55').show(100).delay(4500).hide(100); }
                    }else{
                        response = parseFloat(response).toFixed(2);
                        $('#giftCardDiscSpan').html(parseFloat(response).toFixed(2));
                        $('#payAllGCAppliedCHFVal').val(parseFloat(response).toFixed(2));
                        $('#payAllPhaseOneDiv5_3').hide(10);
                        $('#payAllPhaseOneDiv5_4').hide(10);
                        $('#payAllPhaseOneDiv5_5').hide(10);
                        $('#payAllPhaseOneDiv5_6').hide(10);
                        $('#payAllgcValidationCodeInput').val($('#payAllgcValidationCodeInput').val()+" / "+parseFloat(response).toFixed(2)+" CHF");

                        payAllReloadFinalPay();
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    }



    function payAllProds(extInfo){
        $('#payAllPhaseOneDiv4').remove();
        if($('#payAllTableNr').val() == 0){
            if($('#payAllPhaseOneError3').is(':hidden')){ $('#payAllPhaseOneError3').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsPerventage').html() == '--.--'){
            if($('#payAllPhaseOneError1').is(':hidden')){ $('#payAllPhaseOneError1').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsDisc').length && !$('#discReasonInp').val()){
            if($('#payAllPhaseOneError2').is(':hidden')){ $('#payAllPhaseOneError2').show(100).delay(4000).hide(100); }
        }else{
            if('{{Restorant::find(Auth::user()->sFor)->hasPOS}}' == 1 && extInfo == 'none'){
          
                $.ajax({
                    url: '{{ route("payTec.Connect") }}',
                    method: 'post',
                    data: {_token: '{{csrf_token()}}'},
                    success: (res) => {

                        if($('#payAllPhaseOnePayAtPOS').is(':hidden')){ $('#payAllPhaseOnePayAtPOS').show(50).delay(6000).hide(50); }
                        $.ajax({
                            url: '{{ route("payTec.Transact") }}',
                            method: 'post',
                            timeout: 600000, // Sets a 10-minute timeout (milliseconds)
                            data: {
                                totalChf : parseFloat($('#finalPaySpan').html()).toFixed(2),
                                _token: '{{csrf_token()}}'
                            },
                            success: (resTransact) => {
                                var resJSON = $.parseJSON(resTransact);
                                // if((resJSON.CardholderText == 'Transaction OK' || resJSON.CardholderText == 'Verarbeitung OK') && (resJSON.AttendantText == 'Transaction OK' || resJSON.AttendantText == 'Verarbeitung OK')){    
                                if(resJSON.TrxResult == 0){
                                    
                                    payAllProdsFinishByCard(resTransact);

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
                payAllProdsFinishByCard('none');
            }
        }
    }


    function payAllProdsFinishByCard(resTrx){
        if(hasSndNotiActv){
            if(hasPayOrdSoundSelected){
                $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg31Sound->soundTitle}}.{{$usrNotiReg31Sound->soundExt}}" autoplay="true"></audio>');
            }else{
                $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
            }
        }
        $('#payAllBtn2').prop('disabled', true);
        if(!$('#payAllPhaseOne400PComment').val() || $('#payAllPhaseOne400PComment').val().length < 1){
            sendResCommentOrder = 'none';
        }else{
            sendResCommentOrder = $('#payAllPhaseOne400PComment').val();
        }
        $.ajax({
            url: '{{ route("dash.closeAllProductsTab") }}',
            method: 'post',
            data: {
                tableNr: $('#payAllTableNr').val(),
                resId: '{{Auth::user()->sFor}}',
                disReason: $('#discReasonInp').val(),
                cashDis: $('#cashDiscountInp').val(),
                percDis: $('#percentageDiscountInp').val(),
                tvsh: $('#tvshAmProdsPerventage').html(),
                thePayM: 'Kartenzahlung',
                tipp: $('#tipForOrderClosePhOne').val(),
                discGCId: $('#payAllGCAppliedId').val(),
                discGCAmnt: $('#payAllGCAppliedCHFVal').val(),
                resComment: sendResCommentOrder,
                payTecTrx: resTrx,
                _token: '{{csrf_token()}}'
            },
            success: (resOrId) => { 
                // $('#payAllPhaseOneDiv1').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');

                var tNr = $('#payAllTableNr').val();
            
                // reload script
                    // tavolinat - ikonat
                    $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                    $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                    $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                    $("#tabOrderTotPriceDiv"+tNr).load(location.href+" #tabOrderTotPriceDiv"+tNr+">*","");
                // -------------------------------------------------------------
                $("#topNavbarUlId1").load(location.href+" #topNavbarUlId1>*","");
                $("#payAllPhaseOneBody").load(location.href+" #payAllPhaseOneBody>*","");

                $('.modal').modal('hide');
                // set the variable for payment is Not Selective
                $('#OrQRCodePayIsSelective').val('0');
                $('#payAllBtn2').prop('disabled', false);

                resOrId = $.trim(resOrId);
                
                $('#orderQRCodePicImgTel').attr('src','storage/gifs/loading2.gif');
                $.ajax({
                    url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                    method: 'post',
                    data: {
                        id: resOrId,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = $.trim(res);
                        res2D = res.split('-8-8-');
                        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]); 
                        $('#orderQRCodePicDownloadOI').val(resOrId);
                        $('#orderQRCodePicTel').modal('show');
                    },
                    error: (error) => { console.log(error); }
                });
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

























    function prepOnlinePayPayAll(){
        if($('#payAllTableNr').val() == 0){
            if($('#payAllPhaseOneError3').is(':hidden')){ $('#payAllPhaseOneError3').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsPerventage').html() == '--.--'){
            if($('#payAllPhaseOneError1').is(':hidden')){ $('#payAllPhaseOneError1').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsDisc').length && !$('#discReasonInp').val()){
            if($('#payAllPhaseOneError2').is(':hidden')){ $('#payAllPhaseOneError2').show(100).delay(4000).hide(100); }
        }else{
            if(hasSndNotiActv){
                if(hasPayOrdSoundSelected){
                    $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg31Sound->soundTitle}}.{{$usrNotiReg31Sound->soundExt}}" autoplay="true"></audio>');
                }else{
                    $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
                }
            }
            if(!$('#payAllPhaseOne400PComment').val() || $('#payAllPhaseOne400PComment').val().length < 1){
                sendResCommentOrder = 'none';
            }else{
                sendResCommentOrder = $('#payAllPhaseOne400PComment').val();
            }
            $.ajax({
                url: '{{ route("oPayFStaf.OnlinePayInitiate") }}',
                method: 'post',
                data: {
                    tableNr: $('#payAllTableNr').val(),
                    resId: '{{Auth::user()->sFor}}',
                    disReason: $('#discReasonInp').val(),
                    cashDis: $('#cashDiscountInp').val(),
                    percDis: $('#percentageDiscountInp').val(),
                    tvsh: $('#tvshAmProdsPerventage').html(),
                    thePayM: 'Online',
                    discGCId: $('#payAllGCAppliedId').val(),
                    discGCAmnt: $('#payAllGCAppliedCHFVal').val(),
                    resComment: sendResCommentOrder,
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => { 
                    $('#payAllProdsOnlineQRCode').attr('src','storage/OnlinePayStaf/'+respo);
                    $('#payAllPhaseOne').modal('toggle');
                    $('#payAllProdsOnlineModal').modal('show');

                    $("#payAllPhaseOneBody").load(location.href+" #payAllPhaseOneBody>*","");
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    function closePayAllProdsOnlineModal(){
        $('#payAllPhaseOneDiv1').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
        $("#payAllPhaseOneBody").load(location.href+" #payAllPhaseOneBody>*","");
    }






















    function closePayAllProdsCashModal(){
        $('#payAllPhaseOneDiv1').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
        $("#payAllPhaseOneBody").load(location.href+" #payAllPhaseOneBody>*","");
        $("#payAllProdsCashBody").load(location.href+" #payAllProdsCashBody>*","");
    }

    function prepPayAllProdsCash(){

        $('#payAllProdsCashBtn').prop('disabled', true);

        if($('#payAllTableNr').val() == 0){
            if($('#payAllPhaseOneError3').is(':hidden')){ $('#payAllPhaseOneError3').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsPerventage').html() == '--.--'){
            if($('#payAllPhaseOneError1').is(':hidden')){ $('#payAllPhaseOneError1').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsDisc').length && !$('#discReasonInp').val()){
            if($('#payAllPhaseOneError2').is(':hidden')){ $('#payAllPhaseOneError2').show(100).delay(4000).hide(100); }
        }else{

            $('#payAllTableNrCash').val($('#payAllTableNr').val());
            $('#discReasonCash').val($('#discReasonInp').val());

            $('#cashDiscountInpCash').val($('#cashDiscountInp').val());
            $('#percentageDiscountInpCash').val($('#percentageDiscountInp').val());

            $('#tvshAmProdsPerventageCash').val($('#tvshAmProdsPerventage').html());

            $('#tipForOrderCloseCash').val($('#tipForOrderClosePhOne').val());

            $('#resCommentOrderCloseCash').val($('#payAllPhaseOne400PComment').val());

            $('#payAllPhaseOne').modal('toggle');
            $('#payAllPhaseOne').removeClass('show');

            $('#payAllProdsCashModal').modal('toggle');

            $('body').attr('class','modal-open');

            $('#amToPay').html(parseFloat($('#finalPaySpan').html()).toFixed(2));
            
            $('#payAllProdsCashBtn').prop('disabled', false);
        }
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

    function payAllProdsCash(){
        if(hasSndNotiActv){
            if(hasPayOrdSoundSelected){
                $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg31Sound->soundTitle}}.{{$usrNotiReg31Sound->soundExt}}" autoplay="true"></audio>');
            }else{
                $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
            }
        }
        $('#payAllProdsCashBtn').prop('disabled', true);
        if(!$('#resCommentOrderCloseCash').val() || $('#resCommentOrderCloseCash').val().length < 1){
            sendResCommentOrderCloseCash = 'none';
        }else{
            sendResCommentOrderCloseCash = $('#resCommentOrderCloseCash').val();
        }
        $.ajax({
            url: '{{ route("dash.closeAllProductsTab") }}',
            method: 'post',
            data: {
                tableNr: $('#payAllTableNrCash').val(),
                resId: '{{Auth::user()->sFor}}',
                disReason: $('#discReasonCash').val(),
                cashDis: $('#cashDiscountInpCash').val(),
                percDis: $('#percentageDiscountInpCash').val(),
                tvsh: $('#tvshAmProdsPerventageCash').val(),
                thePayM: 'Barzahlungen',
                tipp: $('#tipForOrderCloseCash').val(),
                discGCId: $('#payAllGCAppliedId').val(),
                discGCAmnt: $('#payAllGCAppliedCHFVal').val(),
                resComment: sendResCommentOrderCloseCash,
                _token: '{{csrf_token()}}'
            },
            success: (resOrId) => { 
                // $('#payAllPhaseOneDiv1').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');

                var tNr = $('#payAllTableNrCash').val();
                var res = '{{Auth::user()->sFor}}';
                resOrId = $.trim(resOrId);

                // reload script
                    // tavolinat - ikonat
                    $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                    $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                    $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                    $("#tabOrderTotPriceDiv"+tNr).load(location.href+" #tabOrderTotPriceDiv"+tNr+">*","");
                // -------------------------------------------------------------
                $("#topNavbarUlId1").load(location.href+" #topNavbarUlId1>*","");
                $("#payAllPhaseOneBody").load(location.href+" #payAllPhaseOneBody>*","");
                $("#payAllProdsCashBody").load(location.href+" #payAllProdsCashBody>*","");
                
                $('.modal').modal('hide');

                // set the variable for payment is Not Selective
                $('#OrQRCodePayIsSelective').val('0');

                $('#payAllProdsCashBtn').prop('disabled', false);
                $('#orderQRCodePicImgTel').attr('src','storage/gifs/loading2.gif');
                $.ajax({
                    url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                    method: 'post',
                    data: {
                        id: resOrId,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = $.trim(res);
                        res2D = res.split('-8-8-');
                        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                        $('#orderQRCodePicDownloadOI').val(resOrId);
                        $('#orderQRCodePicTel').modal('show');
                    },
                    error: (error) => { console.log(error); }
                });
            },
            error: (error) => { console.log(error); }
        });
    }















    function closePayAllProdsRechnungModal(){
        $('#payAllPhaseOneDiv1').html('<p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>');
        $("#payAllPhaseOneBody").load(location.href+" #payAllPhaseOneBody>*","");
        $("#payAllProdsRechnungModal").load(location.href+" #payAllProdsRechnungModal>*","");
    }

    function prepPayAllProdsRechnung(){
        if($('#payAllTableNr').val() == 0){
            if($('#payAllPhaseOneError3').is(':hidden')){ $('#payAllPhaseOneError3').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsPerventage').html() == '--.--'){
            if($('#payAllPhaseOneError1').is(':hidden')){ $('#payAllPhaseOneError1').show(100).delay(4000).hide(100); }
        }else if($('#tvshAmProdsDisc').length && !$('#discReasonInp').val()){
            if($('#payAllPhaseOneError2').is(':hidden')){ $('#payAllPhaseOneError2').show(100).delay(4000).hide(100); }
        }else{

            $('#payAllTableNrRechnung').val($('#payAllTableNr').val());
            $('#discReasonRechnung').val($('#discReasonInp').val());

            $('#cashDiscountInpRechnung').val($('#cashDiscountInp').val());
            $('#percentageDiscountInpRechnung').val($('#percentageDiscountInp').val());
            $('#tvshAmProdsPerventageRechnung').val($('#tvshAmProdsPerventage').html());

            $('#tipForOrderCloseRechnung').val($('#tipForOrderClosePhOne').val());

            $('#payAllRechnungGiftCardId').val($('#payAllGCAppliedId').val());
            $('#payAllRechnungGiftCardAmount').val($('#payAllGCAppliedCHFVal').val());

            if(!$('#payAllPhaseOne400PComment').val() || $('#payAllPhaseOne400PComment').val().length < 1){
                $('#payAllRechnungResComment').val('none');
            }else{
                $('#payAllRechnungResComment').val($('#payAllPhaseOne400PComment').val());
            }

            $('#payAllPhaseOne').modal('toggle');
            $('#payAllPhaseOne').removeClass('show');

            $('#payAllProdsRechnungModal').modal('toggle');

            $('body').attr('class','modal-open');

            if($('#tvshAmProdsDisc').length){
                // discoun Active 
                $('#amToPayRechnung').html(parseFloat(parseFloat($('#totalDisc').html()) + parseFloat($('#tipForOrderCloseRechnung').val())).toFixed(2));
            }else{
                $('#amToPayRechnung').html(parseFloat(parseFloat($('#totAmProds').html()) + parseFloat($('#tipForOrderCloseRechnung').val())).toFixed(2));
            }
        }
    }

    function payAllVerifyTelSendNr(){
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

    function closepayAllRechnungsignatureModal(){
        $('#payAllRechnungsignatureModal').modal('hide');
        $('body').addClass('modal-open');
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
            var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,5}\b$/i
            var dayToPay = $('#RechnungDaysToPayInp').val()
            if(!pattern.test(emailInp)){
                if($('#payAllProdsRechnungModalErr12').is(":hidden")){ $('#payAllProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(Math.floor(dayToPay) != dayToPay || !$.isNumeric(dayToPay) || dayToPay < 1) {
                if($('#payAllProdsRechnungModalErr14').is(":hidden")){ $('#payAllProdsRechnungModalErr14').show(100).delay(4000).hide(100); } 
            }else{
                $('#finishPayRechnungAllBtn').prop('disabled', true);
                // success

                // set the variable for payment is Not Selective
                $('#OrQRCodePayIsSelective').val('0');

                $('#payAllPRechnungMDiv4BTNs').html('<img src="storage/gifs/loading2.gif" style="width:20%; margin-left:40%;" alt="">');
                $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
                $('#payAllProdsRechnungForm').submit();
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
        $('#payAllProdsRechnungForm').submit();
    }

    function asveClientForAufRechnungAll(){
        if($("#saveClForARechnungAll").is(':checked')){
            $('#saveClForARechnungAllVal').val('1');
        }else{
            $('#saveClForARechnungAllVal').val('0');
        }
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

    function selectClRechnungAll(clId){
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
</script>