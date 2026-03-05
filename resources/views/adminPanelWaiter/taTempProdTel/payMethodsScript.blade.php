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

        $('body').addClass('modal-open');

    }
    function prepPayAllProds(){

        $('body').attr('class','modal-open');
        $('#payAllPhaseOneDiv1').html('');
        
        $.ajax({
			url: '{{ route("tempTAProds.fetchOrderPay") }}',
            dataType: 'json',
			method: 'post',
			data: {
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                var totPay = parseFloat(0);
                var mwst77 = parseFloat(0);
                var mwst25 = parseFloat(0);
                $.each(respo, function(index, value){
                    $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left">'+value.proSasia+'x '+value.taProdName+'</p>');
                    $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right">CHF '+parseFloat(parseFloat(value.proSasia)*parseFloat(value.proQmimi)).toFixed(2)+'</p>');
                    totPay += parseFloat(parseFloat(value.proSasia)*parseFloat(value.proQmimi));

                    if(value.taProdMwst == 8.10){
                        mwst77 += parseFloat(totPay * parseFloat(value.taProdMwst/100));
                    }else if(value.taProdMwst == 2.60){
                        mwst25 += parseFloat(totPay * parseFloat(value.taProdMwst/100));
                    }
                });
               
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-bottom:8px;" class="text-left"><strong>Total inkl.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-bottom:8px;" class="text-right"><strong>CHF <span id="totAmProds">'+parseFloat(totPay).toFixed(2)+'</span></strong></p>');
                if(mwst77 > 0){
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:5px;" class="text-left"><strong>MwSt.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:5px;" class="text-right"><strong>CHF <span>'+parseFloat(mwst77).toFixed(2)+' </span> ( <span>8.10</span> %)</strong></p>');
                }
                if(mwst25 > 0){
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:5px;" class="text-left"><strong>MwSt.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:5px;" class="text-right"><strong>CHF <span>'+parseFloat(mwst25).toFixed(2)+' </span> ( <span>2.60</span> %)</strong></p>');
                }
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:0px;" class="text-left"><strong>MwSt.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:0px;" class="text-right"><strong>CHF <span id="tvshAmProds">'+parseFloat(mwst77+mwst25).toFixed(2)+' </span> ( <span id="tvshAmProdsPerventage">Tot.</span> %)</strong></p>');

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
            $('#tvshAmProdsPerventage').html('8.10');
            var tot = parseFloat($('#totAmProds').html());
            var mwst = parseFloat(tot * 0.081);
            $('#tvshAmProds').html(parseFloat(mwst).toFixed(2));
            if ($('#tvshAmProdsDisc').length){
                var totDisc = parseFloat($('#totalDisc').html());
                var mwstDisc = parseFloat(totDisc * 0.081);
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

            $('#tvshAmProdsPerventage').html('2.60');
            var tot = parseFloat($('#totAmProds').html());
            var mwst = parseFloat(tot * 0.026);
            $('#tvshAmProds').html(parseFloat(mwst).toFixed(2));

            if ($('#tvshAmProdsDisc').length){
                var totDisc = parseFloat($('#totalDisc').html());
                var mwstDisc = parseFloat(totDisc * 0.026);
                $('#tvshAmProdsDisc').html(parseFloat(mwstDisc).toFixed(2));
                $('#tvshAmProdsPerventageDisc').html(parseFloat(2.60).toFixed(2));
            }
        }
    }





    function payTakeawayReloadFinalPay(){
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
        $('#tippAmProds').html(tipAdd);
        $('#tipForOrder').val(tipAdd);
        $('#tipWaiterCosVal').val(0);

        payTakeawayReloadFinalPay();

        $.ajax({
			url: '{{ route("billTablet.billTabletSetNewTipp") }}',
			method: 'post',
			data: { staffId: '{{Auth::user()->id}}', tippVal: tipAdd, _token: '{{csrf_token()}}'
			},success: () => {},error: (error) => { console.log(error);}
		});
    }

    function cancelTipStaf(btnId){
        $('#tippAmProds').html('0.00');
        $('#tipForOrder').val(0);
        $('#tipWaiterCosVal').val(0);
        payTakeawayReloadFinalPay();

        $.ajax({
			url: '{{ route("billTablet.billTabletSetNewTipp") }}',
			method: 'post',
			data: {staffId: '{{Auth::user()->id}}', tippVal: 0, _token: '{{csrf_token()}}'
			},success: () => {},error: (error) => { console.log(error);}
		});
    }

    function setCostumeTipStaf(currVal){
        if(currVal == ''){
            $('#tippAmProds').html("0.00");
            $('#tipForOrder').val("0");
            if( !$('#payAllPhaseOneError4').is(':hidden') ){ $('#payAllPhaseOneError4').hide(10); }
            $('#addOrForTAModalBody2RightPayments').attr('style','position: absolute; top: 24px; left:2px; right:2px; max-height:210px; overflow-y:scroll;');

            $.ajax({
                url: '{{ route("billTablet.billTabletSetNewTipp") }}',
                method: 'post',
                data: {staffId: '{{Auth::user()->id}}', tippVal: 0, _token: '{{csrf_token()}}'
                },success: () => {},error: (error) => { console.log(error);}
            });
        }else{
            var valForTip = parseFloat(currVal).toFixed(2);
            if($('#totalDisc').length){
                var tot = parseFloat($('#totalDisc').html()).toFixed(2);
            }else{
                var tot = parseFloat($('#totAmProds').html()).toFixed(2);
            }
            if(parseFloat(valForTip) < parseFloat(tot)){
                $('#addOrForTAModalBody2RightPayments').attr('style','position: absolute; top: 24px; left:2px; right:2px; max-height:118px; overflow-y:scroll;');
                if( $('#payAllPhaseOneError4').is(':hidden') ){ $('#payAllPhaseOneError4').show(10); }
                $('#tippAmProds').html("0.00");
                $('#tipForOrder').val("0");

                $.ajax({
                    url: '{{ route("billTablet.billTabletSetNewTipp") }}',
                    method: 'post',
                    data: { staffId: '{{Auth::user()->id}}', tippVal: 0, _token: '{{csrf_token()}}'
                    },success: () => {},error: (error) => { console.log(error);}
                });
                
            }else{
                $('#addOrForTAModalBody2RightPayments').attr('style','position: absolute; top: 24px; left:2px; right:2px; max-height:210px; overflow-y:scroll;');
                if( !$('#payAllPhaseOneError4').is(':hidden') ){ $('#payAllPhaseOneError4').hide(10); }
                var tipAdd = parseFloat(parseFloat(valForTip) - parseFloat(tot)).toFixed(2);
                var totWTip = parseFloat(parseFloat(tot) + parseFloat(tipAdd)).toFixed(2);

                $('#tippAmProds').html(parseFloat(tipAdd).toFixed(2));
                $('#tipForOrder').val(tipAdd);

                $.ajax({
                    url: '{{ route("billTablet.billTabletSetNewTipp") }}',
                    method: 'post',
                    data: { staffId: '{{Auth::user()->id}}', tippVal: tipAdd, _token: '{{csrf_token()}}'
                    },success: () => {},error: (error) => { console.log(error);}
                });
            }
        }
        payTakeawayReloadFinalPay();
    }
    // ---------------------------------------------------------------------------------










    function payAllValidateGC(){
        $('#addOrForTAModalBody2Right').attr('style','width:100%; min-height: 810px; border:1px solid rgb(39,190,175); border-radius:6px;  position: relative;');
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
                        $('#addOrForTAModalBody2Right').attr('style','width:100%; min-height: 930px; border:1px solid rgb(39,190,175); border-radius:6px;  position: relative;');
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

    function payAllApplyGCDiscount(){
        $.ajax({
            url: '{{ route("billTablet.billTabletSetNewGC") }}',
            method: 'post',
            data: { staffId: '{{Auth::user()->id}}', gcVal: 0, gcId: 0, _token: '{{csrf_token()}}'
            },success: () => {},error: (error) => { console.log(error);}
        });
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
                            $('#addOrForTAModalBody2Right').attr('style','width:100%; min-height: 810px; border:1px solid rgb(39,190,175); border-radius:6px;  position: relative;');
                            response = parseFloat(response).toFixed(2);
                            var tot = parseFloat($('#finalPaySpan').html()).toFixed(2);

                            if(Number(tot) >= Number(response)){
                                $('#giftcardAmProds').html(parseFloat(response).toFixed(2));
                                $('#payAllGCAppliedCHFVal').val(parseFloat(response).toFixed(2));
                                $('#payAllPhaseOneDiv5_3').hide(10);
                                $('#payAllPhaseOneDiv5_4').hide(10);
                                $('#payAllPhaseOneDiv5_5').hide(10);
                                $('#payAllPhaseOneDiv5_6').hide(10);
                                $('#payAllgcValidationCodeInput').val($('#payAllgcValidationCodeInput').val()+" / "+parseFloat(response).toFixed(2)+" CHF");

                                payTakeawayReloadFinalPay();

                                $.ajax({
                                    url: '{{ route("billTablet.billTabletSetNewGC") }}',
                                    method: 'post',
                                    data: { staffId: '{{Auth::user()->id}}', gcVal: response, gcId: $('#payAllGCAppliedId').val(), _token: '{{csrf_token()}}'
                                    },success: () => {},error: (error) => { console.log(error);}
                                });
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
        $.ajax({
            url: '{{ route("billTablet.billTabletSetNewGC") }}',
            method: 'post',
            data: { staffId: '{{Auth::user()->id}}', gcVal: 0, gcId: 0, _token: '{{csrf_token()}}'
            },success: () => {},error: (error) => { console.log(error);}
        });
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
                        $('#addOrForTAModalBody2Right').attr('style','width:100%; min-height: 810px; border:1px solid rgb(39,190,175); border-radius:6px;  position: relative;');
                        response = parseFloat(response).toFixed(2);
                        $('#giftcardAmProds').html(parseFloat(response).toFixed(2));
                        $('#payAllGCAppliedCHFVal').val(parseFloat(response).toFixed(2));
                        $('#payAllPhaseOneDiv5_3').hide(10);
                        $('#payAllPhaseOneDiv5_4').hide(10);
                        $('#payAllPhaseOneDiv5_5').hide(10);
                        $('#payAllPhaseOneDiv5_6').hide(10);
                        $('#payAllgcValidationCodeInput').val($('#payAllgcValidationCodeInput').val()+" / "+parseFloat(response).toFixed(2)+" CHF");

                        payTakeawayReloadFinalPay();

                        $.ajax({
                            url: '{{ route("billTablet.billTabletSetNewGC") }}',
                            method: 'post',
                            data: { staffId: '{{Auth::user()->id}}', gcVal: response, gcId: $('#payAllGCAppliedId').val(), _token: '{{csrf_token()}}'
                            },success: () => {},error: (error) => { console.log(error);}
                        });
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
        $('#giftcardAmProds').html(parseFloat(0).toFixed(2));

        $('#payAllPhaseOneDiv5_3').hide(10);
        $('#payAllPhaseOneDiv5_4').hide(10);
        $('#payAllPhaseOneDiv5_5').hide(10);
        $('#payAllPhaseOneDiv5_6').hide(10);

        payTakeawayReloadFinalPay();

        $.ajax({
            url: '{{ route("billTablet.billTabletSetNewGC") }}',
            method: 'post',
            data: { staffId: '{{Auth::user()->id}}', gcVal: 0, gcId: 0, _token: '{{csrf_token()}}'
            },success: () => {},error: (error) => { console.log(error);}
        });
    }




    function callInCash(){
        $('#div3inCashBtn').hide(100);
        $('#div3inPercentageBtn').hide(100);

        $('#div3InCash').show(100);
    }
    function procesInCah(){
        if(!$('#div3InCashInp').val()){
            // zerro discount
            $('#cashDiscountInp').val(0);
            $('#rabatAmProds').html(parseFloat(0).toFixed(2));

            $.ajax({
                url: '{{ route("billTablet.billTabletSetNewRabatt") }}',
                method: 'post',
                data: {staffId: '{{Auth::user()->id}}', discVal: 0,_token: '{{csrf_token()}}'
                },success: () => {},error: (error) => { console.log(error);}
            });
        }else{
            var cashOff = $('#div3InCashInp').val();
            if($.isNumeric(cashOff) && cashOff != '' && cashOff > 0){
                var tot = parseFloat($('#totAmProds').html());
                var totAD = parseFloat(tot - parseFloat(cashOff));
                $('#cashDiscountInp').val(cashOff);

                $('#rabatAmProds').html(parseFloat(cashOff).toFixed(2));     
                
                $.ajax({
                    url: '{{ route("billTablet.billTabletSetNewRabatt") }}',
                    method: 'post',
                    data: {staffId: '{{Auth::user()->id}}', discVal: cashOff, _token: '{{csrf_token()}}'
                    },success: () => {},error: (error) => { console.log(error);}
                });
            }else{
                // not a number , display error msg
                $('#cashDiscountInp').val(0);
                $('#rabatAmProds').html(parseFloat(0).toFixed(2));
                // if($('#div3InCashError1').is(':hidden')){ $('#div3InCashError1').show(100).delay(4000).hide(100); }

                $.ajax({
                    url: '{{ route("billTablet.billTabletSetNewRabatt") }}',
                    method: 'post',
                    data: {staffId: '{{Auth::user()->id}}', discVal: 0, _token: '{{csrf_token()}}'
                    },success: () => {},error: (error) => { console.log(error);}
                });
            }
        }
        payTakeawayReloadFinalPay();
    }

    function cancelInCash(){
        $('#discountShow1').remove();
        $('#discountShow2').remove();
        $('#discountShow3').remove();
        $('#discountShow4').remove();
        $('#discountShow5').remove();
        $('#discountShow6').remove();
        $('#discountShow7').remove();

        $('#div3inCashBtn').show(100);
        $('#div3inPercentageBtn').show(100);

        $('#div3InCash').hide(100);

        $('#cashDiscountInp').val(0);

        $('#div3InCashInp').val(0);
        $('#rabatAmProds').html(parseFloat(0).toFixed(2));
        payTakeawayReloadFinalPay();

        $.ajax({
            url: '{{ route("billTablet.billTabletSetNewRabatt") }}',
            method: 'post',
            data: { staffId: '{{Auth::user()->id}}', discVal: 0, _token: '{{csrf_token()}}'
            },success: () => {},error: (error) => { console.log(error);}
        });
    }


    function callInPercentage(){
        $('#div3inCashBtn').hide(100);
        $('#div3inPercentageBtn').hide(100);

        $('#div3InPercentage').show(100);
    }
    function procesInPercentage(){
        $('#discountShow1').remove();
        $('#discountShow2').remove();
        $('#discountShow3').remove();
        $('#discountShow4').remove();
        $('#discountShow5').remove();
        $('#discountShow6').remove();
        $('#discountShow7').remove();
      
        if(!$('#div3InPercentageInp').val()){
            // zerro discount
            $('#percentageDiscountInp').val(0);
            $('#rabatAmProds').html(parseFloat(0).toFixed(2));

            $.ajax({
                url: '{{ route("billTablet.billTabletSetNewRabatt") }}',
                method: 'post',
                data: {staffId: '{{Auth::user()->id}}',discVal: 0, _token: '{{csrf_token()}}'
                },success: () => {},error: (error) => { console.log(error);}
            });
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

                $('#rabatAmProds').html(parseFloat(cashDisc).toFixed(2));

                $('#percentageDiscountInp').val(newPercOff);

                $.ajax({
                    url: '{{ route("billTablet.billTabletSetNewRabatt") }}',
                    method: 'post',
                    data: {staffId: '{{Auth::user()->id}}', discVal: cashDisc, _token: '{{csrf_token()}}'
                    },success: () => {},error: (error) => { console.log(error);}
                });
            }else{
                // not a number , display error msg
                $('#percentageDiscountInp').val(0);
                $('#rabatAmProds').html(parseFloat(0).toFixed(2));
                if($('#div3InPercentageError1').is(':hidden')){ $('#div3InPercentageError1').show(100).delay(4000).hide(100); }

                $.ajax({
                    url: '{{ route("billTablet.billTabletSetNewRabatt") }}',
                    method: 'post',
                    data: {staffId: '{{Auth::user()->id}}', discVal: 0, _token: '{{csrf_token()}}'
                    },success: () => {},error: (error) => { console.log(error);}
                });
            }
        }
        payTakeawayReloadFinalPay();
    }
    function cancelInPercentage(){
        $('#discountShow1').remove();
        $('#discountShow2').remove();
        $('#discountShow3').remove();
        $('#discountShow4').remove();
        $('#discountShow5').remove();
        $('#discountShow6').remove();
        $('#discountShow7').remove();
        $('#payAllPhaseOneDiv1MitTippP11').remove();
        $('#payAllPhaseOneDiv1MitTippP12').remove();

        $('#div3inCashBtn').show(100);
        $('#div3inPercentageBtn').show(100);

        $('#div3InPercentage').hide(100);

        $('#percentageDiscountInp').val(0);
        $('#div3InPercentageInp').val(0)

        payTakeawayReloadFinalPay();

        $.ajax({
            url: '{{ route("billTablet.billTabletSetNewRabatt") }}',
            method: 'post',
            data: { staffId: '{{Auth::user()->id}}', discVal: 0, _token: '{{csrf_token()}}'
            },success: () => {},error: (error) => { console.log(error);}
        });
    }


















    function payAllProdsCard(){
        if(($('#cashDiscountInp').val() > 0 || $('#percentageDiscountInp').val() > 0) && !$('#discReasonInp').val()){
            if($('#payAllPhaseOneError1').is(':hidden')){ $('#payAllPhaseOneError1').show(100).delay(4000).hide(100); }
        }else if($('.productShowTATemp').length <= 0){
            if($('#payAllPhaseOneError2').is(':hidden')){ $('#payAllPhaseOneError2').show(100).delay(4000).hide(100); }
        }else{
            $('#payTaProdBtn1').prop('disabled', true);
            $('#payTaProdBtn2').prop('disabled', true);
            $('#payTaProdBtn3').prop('disabled', true);
            $('#payTaProdBtn4').prop('disabled', true);
            if('{{Restorant::find(Auth::user()->sFor)->hasPOS}}' == 1){
                $.ajax({
                    url: '{{ route("payTec.Connect") }}',
                    method: 'post',
                    data: {_token: '{{csrf_token()}}'},
                    success: (res) => {
                        if($('#payTAPayAtPOSAlert').is(':hidden')){ $('#payTAPayAtPOSAlert').show(50).delay(4500).hide(50); }

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

                                    payAllProdsCardRegister(resTransact);

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
                payAllProdsCardRegister('none');
            }
            $('#payTaProdBtn1').prop('disabled', false);
            $('#payTaProdBtn2').prop('disabled', false);
            $('#payTaProdBtn3').prop('disabled', false);
            $('#payTaProdBtn4').prop('disabled', false);
            
        }
    }


    function payAllProdsCardRegister(resTrx){
        if(hasSndNotiActv == 1){
            if(hasPayOrdSoundSelected == 1){
                $("#soundsAllNWP").html('<audio id="soundsAllNAPAudio" src="storage/sound/{{$usrNotiReg31Sound->soundTitle}}.{{$usrNotiReg31Sound->soundExt}}" autoplay="true"></audio>');
            }else{
                $("#soundsAllNWP").html('<audio id="soundsAllNAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
            }
        }
        $.ajax({
            url: '{{ route("tempTAProds.payTempTaWithCard") }}',
            method: 'post',
            data: {
                resId: '{{Auth::user()->sFor}}',
                disReason: $('#discReasonInp').val(),
                cashDis: $('#cashDiscountInp').val(),
                percDis: $('#percentageDiscountInp').val(),
                thePayM: 'Kartenzahlung',
                tipp: $('#tipForOrder').val(),
                discGCId: $('#payAllGCAppliedId').val(),
                discGCAmnt: $('#payAllGCAppliedCHFVal').val(),
                payTecTrx: resTrx,
                _token: '{{csrf_token()}}'
            },
            success: (respo) => { 
                respo = $.trim(respo);
                respo2D = respo.split('||');
                $("#phoneTableAll").load(location.href+" #phoneTableAll"+">*","");
                $("#addOrForTAModalBody2Right").load(location.href+" #addOrForTAModalBody2Right"+">*","");

                $("#readyToPayOrdsDiv").load(location.href+" #readyToPayOrdsDiv>*","");
                $('#addOrForTAModal').modal('toggle');
                
                $("#ChStatAll").load(location.href+" #ChStatAll"+">*","");
                $("#openOrderAllOther").load(location.href+" #openOrderAllOther"+">*","");

                if(respo2D[0] == 'autoConfPayed'){
                    if($('#taOrderAutoConfirmAler01').is(':hidden')){ $('#taOrderAutoConfirmAler01').show(100).delay(4000).hide(100); }
                    $("#openOrderAllDone").load(location.href+" #openOrderAllDone"+">*","");

                    $.ajax({
                        url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                        method: 'post',
                        data: {
                            id: respo2D[1],
                            _token: '{{csrf_token()}}'
                        },
                        success: (res) => {
                            res = $.trim(res);
                            res2D = res.split('-8-8-');
                            $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                            $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                            $('#orderQRCodePicDownloadOI').val(respo2D[1]);
                            $('#orderQRCodePicTel').modal('show');

                            $("#orderQRCodeTel").load(location.href+" #orderQRCodeTel>*","");
                            $("#orderQRCodeTelBody").html('<img src="storage/gifs/loading.gif" style="width:30%; margin-left:35%;" alt="">');
                        },
                        error: (error) => { console.log(error); }
                    });
                }
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













    function payAllProdsOnline(){
        if(($('#cashDiscountInp').val() > 0 || $('#percentageDiscountInp').val() > 0) && !$('#discReasonInp').val()){
            if($('#payAllPhaseOneError1').is(':hidden')){ $('#payAllPhaseOneError1').show(100).delay(4000).hide(100); }
        }else if($('.productShowTATemp').length <= 0){
            if($('#payAllPhaseOneError2').is(':hidden')){ $('#payAllPhaseOneError2').show(100).delay(4000).hide(100); }
        }else{
            if(hasSndNotiActv == 1){
                if(hasPayOrdSoundSelected == 1){
                    $("#soundsAllNWP").html('<audio id="soundsAllNAPAudio" src="storage/sound/{{$usrNotiReg31Sound->soundTitle}}.{{$usrNotiReg31Sound->soundExt}}" autoplay="true"></audio>');
                }else{
                    $("#soundsAllNWP").html('<audio id="soundsAllNAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
                }
            }
            $.ajax({
                url: '{{ route("oPayFStaf.OnlinePayInitiateTA") }}',
                method: 'post',
                data: {
                    resId: '{{Auth::user()->sFor}}',
                    disReason: $('#discReasonInp').val(),
                    cashDis: $('#cashDiscountInp').val(),
                    percDis: $('#percentageDiscountInp').val(),
                    thePayM: 'Online',
                    discGCId: $('#payAllGCAppliedId').val(),
                    discGCAmnt: $('#payAllGCAppliedCHFVal').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => { 
                    $('#payTAProdsOnlineQRCode').attr('src','storage/OnlinePayStaf/'+respo);
                    $('#addOrForTAModal').modal('toggle');
                    $('#payTAProdsOnlineModal').modal('show');

                    $("#phoneTableAll").load(location.href+" #phoneTableAll"+">*","");
                    $("#addOrForTAModalBody2Right").load(location.href+" #addOrForTAModalBody2Right"+">*","");
                    $("#readyToPayOrdsDiv").load(location.href+" #readyToPayOrdsDiv>*","");
                    $("#ChStatAll").load(location.href+" #ChStatAll"+">*","");
                    $("#openOrderAllOther").load(location.href+" #openOrderAllOther"+">*","");
                },
                error: (error) => { console.log(error); }
            });

        }
    }

    function showQRCForPayAllProdsOnline(qrName){
        $('#payTAProdsOnlineQRCode').attr('src','storage/OnlinePayStaf/'+qrName);
        $('#payTAProdsOnlineModal').modal('show');
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