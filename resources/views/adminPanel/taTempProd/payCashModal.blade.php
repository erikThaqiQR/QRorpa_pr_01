<!-- payAllProdsCashModal Modal -->

<div class="modal fade" id="payAllProdsCashModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Barzahlung</h5>
                <button type="button" class="close" aria-label="Close" onclick="closePayAllProdsCashModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="payAllProdsCashBody">
                <button style="width:100%; margin:0px;" class="btn btn-danger mb-2" onclick="payAllProdsCashDel()">Löschen</button>
                <div id="payAllPCMDiv1" class="d-flex flex-wrap justify-content-between">
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(204,153,0,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtn" onclick="addClPay('0.05')"><strong>5 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtn" onclick="addClPay('0.1')"><strong>10 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtn" onclick="addClPay('0.2')"><strong>20 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtn" onclick="addClPay('0.5')"><strong>50 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtn" onclick="addClPay('1')"><strong>CHF <br> 1</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtn" onclick="addClPay('2')"><strong>CHF <br> 2</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtn" onclick="addClPay('5')"><strong>CHF <br> 5</strong></button>
                </div>
                <div id="payAllPCMDiv2" class="d-flex flex-wrap justify-content-between mt-2">
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(255,192,0,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtn" onclick="addClPay('10')"><strong>CHF 10</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(254,0,0,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtn" onclick="addClPay('20')"><strong>CHF 20</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(0,175,80,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtn" onclick="addClPay('50')"><strong>CHF 50</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(45,117,182,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtn" onclick="addClPay('100')"><strong>CHF 100</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(204,153,0,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtn" onclick="addClPay('200')"><strong>CHF 200</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(112,48,160,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtn" onclick="addClPay('1000')"><strong>CHF 1000</strong></button>
                </div>
                <button style="width:100%; margin:0px;" class="btn btn-info mt-2 mb-2" id="showPayAllPCMDiv3Btn" onclick="showPayAllPCMDiv3()">Betrag eingeben</button>
                <div id="payAllPCMDiv3" class="flex-wrap justify-content-between mt-2" style="display:none;">
                    <div style="width: 100%;" class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">CHF</span>
                        </div>
                        <input type="text" class="form-control shadow-none" id="payAllPCMDiv3Inp" onkeyup="newValGivByCl()">
                        <div class="input-group-append">
                            <button onclick="cancelPayAllPCMDiv3()" class="btn btn-danger" style="margin:0px;" type="button">Absagen</button>
                        </div>
                    </div>
                    <div style="display:none; width:100%" class="alert alert-danger text-center mt-1" id="payAllPCMDiv3Err1">
                        Schreiben Sie einen gültigen Betrag!
                    </div>
                </div>
                <div id="payAllPCMDiv4" class="d-flex flex-wrap justify-content-between mt-2">
                    <div style="width:49%">
                        <p style="margin-bottom: 5px;">Zu bezahlen: CHF <span id="amToPay"></span></p>
                        <p style="margin-bottom: 5px;">Erhalten: CHF <span id="amByClient">0.00</span></p>
                        <p style="margin-bottom: 5px;"><strong>Rückgeld: CHF <span id="amClientReturn">--.--</span></strong></p>
                    </div>
                    <button style="width:49%; margin:0px;" class="btn btn-success" id="payAllProdsCashBtn" onclick="payAllProdsCash()"><strong>Abschliessen</strong></button>
                </div>
                <div id="payAllPhaseOneCashError1" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Wählen Sie zuerst den Diensttyp (Im Restaurant oder Ausser Haus)</strong>
                </div>
                <div id="payAllPhaseOneCashError2" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Schreiben Sie einen Grund für diesen Rabatt!</strong>
                </div>
                <div id="payAllPhaseOneCashError3" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Aktualisieren Sie die Seite, etwas stimmt nicht mit dem Programm!</strong>
                </div>

                <input type="hidden" id="discReasonCash" value="empty">

                <input type="hidden" id="cashDiscountInpCash" value="0">
                <input type="hidden" id="percentageDiscountInpCash" value="0">

                <input type="hidden" id="tipForOrderCash" value="0">
                
            </div>
        </div>
    </div>
</div>


<script>
    function closePayAllProdsCashModal(){
        $('#payAllProdsCashModal').modal('toggle');
        $("#payAllProdsCashBody").load(location.href+" #payAllProdsCashBody>*","");

        $('body').attr('class','modal-open');
    }

    function prepPayAllProdsCash(){
        if(($('#cashDiscountInp').val() > 0 || $('#percentageDiscountInp').val() > 0) && !$('#discReasonInp2').val()){
            if($('#payAllPhaseOneError1').is(':hidden')){ $('#payAllPhaseOneError1').show(100).delay(4000).hide(100); }
        }else if($('.productShowTATemp').length <= 0){
            if($('#payAllPhaseOneError2').is(':hidden')){ $('#payAllPhaseOneError2').show(100).delay(4000).hide(100); }
        }else{
        
            $('#discReasonCash').val($('#discReasonInp2').val());
            $('#cashDiscountInpCash').val($('#cashDiscountInp').val());
            $('#percentageDiscountInpCash').val($('#percentageDiscountInp').val());

            $('#tipForOrderCash').val($('#tipForOrder').val());
            
            $('#addOrForTAModal').modal('toggle');
            $('#payAllProdsCashModal').modal('toggle');

            $('body').attr('class','modal-open');

            $('#amToPay').html(parseFloat($('#finalPaySpan').html()).toFixed(2));
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
        if(hasSndNotiActv == 1){
            if(hasPayOrdSoundSelected == 1){
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg31Sound->soundTitle}}.{{$usrNotiReg31Sound->soundExt}}" autoplay="true"></audio>');
            }else{
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
            }
        }
        $('#payAllProdsCashBtn').prop('disabled', true);
        $.ajax({
            url: '{{ route("tempTAProds.payTempTaWithCash") }}',
            method: 'post',
            data: {
                resId: '{{Auth::user()->sFor}}',
                disReason: $('#discReasonCash').val(),
                cashDis: $('#cashDiscountInpCash').val(),
                percDis: $('#percentageDiscountInpCash').val(),
                thePayM: 'Barzahlungen',
                tipp: $('#tipForOrderCash').val(),
                discGCId: $('#payAllGCAppliedId').val(),
                discGCAmnt: $('#payAllGCAppliedCHFVal').val(),
                _token: '{{csrf_token()}}'
            },
            success: (respo) => { 
                respo = $.trim(respo);
                respo2D = respo.split('||');
                $("#desktopTableAll").load(location.href+" #desktopTableAll"+">*","");
                $("#addOrForTAModalBody2Right").load(location.href+" #addOrForTAModalBody2Right"+">*","");

                $("#readyToPayOrdsDiv").load(location.href+" #readyToPayOrdsDiv>*","");

                $('#payAllProdsCashModal').modal('toggle');
                // $('#addOrForTAModal').modal('toggle');

                $("#ChStatAll").load(location.href+" #ChStatAll"+">*","");
                $("#openOrderAllOther").load(location.href+" #openOrderAllOther"+">*","");

                $("#payAllProdsCashBody").load(location.href+" #payAllProdsCashBody>*","");
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
                $('#payAllProdsCashBtn').prop('disabled', false);
            },
            error: (error) => { console.log(error); }
        });
    }
</script>