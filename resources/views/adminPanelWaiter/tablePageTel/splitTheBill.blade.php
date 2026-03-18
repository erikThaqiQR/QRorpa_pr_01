<?php
    use App\Restorant;
?>
<script type="module">
    
    import QrScanner from "/js/FP-qr-scanner.min.js";
    function splitBillOpenCameraGC(){
        const video = document.getElementById('splitBill-qr-video');

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
                            $('#splitBillgcValidationCodeInput').val(respo);
                            $('#splitBillCameraModal').modal('hide');
                            $('body').addClass('modal-open');
                            scanner.stop();
                            splitBillValidateGC($('#splitBillCrrGCClientNr').val(),$('#splitBillCrrGCTableNr').val(),$('#splitBillCrrGCClientsNr').val());
                        }else if(respo == 'invalideQRCode'){
                            if( $('#splitBillPhaseOneError511'+$('#splitBillCrrGCClientNr').val()).is(':hidden') ){ $('#splitBillPhaseOneError511'+$('#splitBillCrrGCClientNr').val()).show(100).delay(4500).hide(100); }
                            $('#splitBillCameraModal').modal('hide');
                            $('body').addClass('modal-open');
                            scanner.stop();
                        }else if(respo == 'gcNotSoldYet'){
                            if( $('#splitBillPhaseOneError512'+$('#splitBillCrrGCClientNr').val()).is(':hidden') ){ $('#splitBillPhaseOneError512'+$('#splitBillCrrGCClientNr').val()).show(100).delay(4500).hide(100); }
                            $('#splitBillCameraModal').modal('hide');
                            $('body').addClass('modal-open');
                            scanner.stop();
                        }
					},
					error: (error) => { console.log(error); }
				});
            }
        }
    }
    var btn = document.getElementById("splitBillOpenCameraModalBtn");
    // Assigning event listeners to the button
    btn.addEventListener("click", splitBillOpenCameraGC);
    
</script>

<!-- Modal -->
<div class="modal" id="splitTheBillInitiateModal" tabindex="-1" role="dialog" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:4%;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="splitTheBillInitiateModalHead">
                    <strong>Teilen Sie die Rechnung, <span id="splitTheBillInitiateModalClientsNr">x</span> Kunden, Tisch <span id="splitTheBillInitiateModalTableNr">x</span></strong>
                </h5>
                <button id="splitTheBillInitiateModalCancelBtn" onclick="splitTheBillInitiateModalCancel()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><strong>Stornieren</strong></span>
                </button>
            </div>
            <div class="modal-body" id="splitTheBillInitiateModalBody">
                
           
            </div>
            <div class="d-flex justify-content-between">
                <p style="width: 50%; font-size:1.2rem;" class="text-center"><strong>Ursprünglicher Preis: <span id="splitTheBillInitiateModalOriginalPriceShow"></span> CHF</strong></p>
                <p style="width: 50%; font-size:1.2rem;" class="text-center"><strong>Endpreis (ohne tipp): <span id="splitTheBillInitiateModalPayPriceShow"></span> CHF</strong></p>
            </div>
        </div>
    </div>
</div>






<div class="modal" id="splitBillCashModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Barzahlung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeSplitBillCashModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="splitBillCashBody">
                <button style="width:100%; margin:0px;" class="btn btn-danger mb-2" onclick="splitBillCashDel()">Löschen</button>
                <div id="splitBillpayAllPCMDiv1" class="d-flex flex-wrap justify-content-between">
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(204,153,0,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 splitBillcurrBtn" onclick="splitBilladdClPay('0.05')"><strong>5 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 splitBillcurrBtn" onclick="splitBilladdClPay('0.1')"><strong>10 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 splitBillcurrBtn" onclick="splitBilladdClPay('0.2')"><strong>20 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 splitBillcurrBtn" onclick="splitBilladdClPay('0.5')"><strong>50 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 splitBillcurrBtn" onclick="splitBilladdClPay('1')"><strong>CHF <br> 1</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 splitBillcurrBtn" onclick="splitBilladdClPay('2')"><strong>CHF <br> 2</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 splitBillcurrBtn" onclick="splitBilladdClPay('5')"><strong>CHF <br> 5</strong></button>
                </div>
                <div id="splitBillpayAllPCMDiv2" class="d-flex flex-wrap justify-content-between mt-2">
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(255,192,0,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 splitBillcurrBtn" onclick="splitBilladdClPay('10')"><strong>CHF 10</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(254,0,0,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 splitBillcurrBtn" onclick="splitBilladdClPay('20')"><strong>CHF 20</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(0,175,80,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 splitBillcurrBtn" onclick="splitBilladdClPay('50')"><strong>CHF 50</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(45,117,182,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 splitBillcurrBtn" onclick="splitBilladdClPay('100')"><strong>CHF 100</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(204,153,0,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 splitBillcurrBtn" onclick="splitBilladdClPay('200')"><strong>CHF 200</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(112,48,160,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 splitBillcurrBtn" onclick="splitBilladdClPay('1000')"><strong>CHF 1000</strong></button>
                </div>
                <button style="width:100%; margin:0px;" class="btn btn-info mt-2 mb-2" id="splitBillshowPayAllPCMDiv3Btn" onclick="splitBillshowPayAllPCMDiv3()">Betrag eingeben</button>
                <div id="splitBillpayAllPCMDiv3" class="flex-wrap justify-content-between mt-2" style="display:none;">
                    <div style="width: 100%;" class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">CHF</span>
                        </div>
                        <input type="text" class="form-control shadow-none" id="payAllPCMDiv3Inp" onkeyup="splitBillnewValGivByCl()">
                        <div class="input-group-append">
                            <button onclick="splitBillcancelPayAllPCMDiv3()" class="btn btn-danger" style="margin:0px;" type="button">Absagen</button>
                        </div>
                    </div>
                    <div style="display:none; width:100%" class="alert alert-danger text-center mt-1" id="splitBillpayAllPCMDiv3Err1">
                        Schreiben Sie einen gültigen Betrag!
                    </div>
                </div>

                <div id="splitBillpayAllPCMDiv4" class="d-flex flex-wrap justify-content-between mt-2">
                    <div style="width:49%">
                        <p style="margin-bottom: 5px;">Zu bezahlen: CHF <span id="splitBillamToPay"></span></p>
                        <p style="margin-bottom: 5px;">Erhalten: CHF <span id="splitBillamByClient">0.00</span></p>
                        <p style="margin-bottom: 5px;"><strong>Rückgeld: CHF <span id="splitBillamClientReturn">--.--</span></strong></p>
                    </div>
                    <button id="splitBillpayAllProdsCashBtn" style="width:49%; margin:0px;" class="btn btn-success" onclick="splitBillpayAllProdsCash()"><strong>Abschliessen</strong></button>
                </div>
                <div id="splitBillpayAllPhaseOneCashError1" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Wählen Sie zuerst den Diensttyp (Im Restaurant oder Ausser Haus)</strong>
                </div>
                <div id="splitBillpayAllPhaseOneCashError2" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Schreiben Sie einen Grund für diesen Rabatt!</strong>
                </div>
                <div id="splitBillpayAllPhaseOneCashError3" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Aktualisieren Sie die Seite, etwas stimmt nicht mit dem Programm!</strong>
                </div>

                <input type="hidden" id="splitBillpayAllTableNrCash" value="0">
                <input type="hidden" id="splitBillpayAllClientsNrCash" value="0">
                <input type="hidden" id="splitBillCrrClientNrCash" value="0">
                <input type="hidden" id="splitBilltipForOrderCloseCash" value="0">
                <input type="hidden" id="splitBillGCIdCloseCash" value="0">
                <input type="hidden" id="splitBillGCAmtCloseCash" value="0">
            </div>
        </div>
    </div>
</div>



<div id="splitBillCameraModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scannen Sie den QR-Code der Geschenkkarte</h5>
                <button type="button" class="close"onclick="closeSplitBillCameraModal()">
                  <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="video">
                  <video muted playsinline id="splitBill-qr-video" width="100%"></video>
                </div>
            </div>  
        </div>
    </div>
</div>















<script>
    function closeSplitBillCameraModal(){
        $('#splitBillCameraModal').modal('toggle');
        $('body').addClass('modal-open');
    }

    function splitTheBillInitiateModalCancel(sbiId){
		$.ajax({
			url: '{{ route("splitBill.splitBillCancelTheInitiate") }}',
			method: 'post',
			data: {
				sbInitiateId: sbiId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
			},
			error: (error) => { console.log(error); }
		});
    }

    function splitTheBillInitiate(tNr){
        if(!$('#splitTheBillClNr'+tNr).val()){
            if($('#splitTheBillError01'+tNr).is(':hidden')){ $('#splitTheBillError01'+tNr).show(100).delay(3500).hide(100);}
        }else{
            var splitBillClNr = parseInt($('#splitTheBillClNr'+tNr).val());
            if(splitBillClNr <= 1 || splitBillClNr >= 1000){
                if($('#splitTheBillError01'+tNr).is(':hidden')){ $('#splitTheBillError01'+tNr).show(100).delay(3500).hide(100);}
            }else{

                console.log(splitBillClNr);

                $.ajax({
					url: '{{ route("splitBill.displayFirstSplit") }}',
					method: 'post',
					data: {
                        tableNr: tNr,
                        splitNr: splitBillClNr,
						_token: '{{csrf_token()}}'
					},
					success: (response) => {
                        response = $.trim(response);
                        if(response == 'invalideTable'){

                        }else{
                            respo2D = response.split('|||');
                            $('#tabOrder'+respo2D[1]).modal('hide');
                            $('#splitTheBillInitiateModal').modal('show');
                            $('#splitTheBillInitiateModalTableNr').html(respo2D[1]);
                            $('#splitTheBillInitiateModalClientsNr').html(respo2D[2]);

                            $('#splitTheBillInitiateModalCancelBtn').attr('onclick','splitTheBillInitiateModalCancel(\''+respo2D[5]+'\')');

                            $('#splitTheBillInitiateModalBody').html('');
                            var newClientLineShow = "";
                            for(var i = 0; i < respo2D[2]; i++) {
                                newClientLineShow = '<div id="splitBillClientDiv1'+i+'" class="d-flex flex-wrap justify-content-between pt-2">'+
                                                        '<div style="width:100%;">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Kunde '+(i + 1)+'</strong></p>'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong>Bezahlen <span id="splitBillModalPayValueClient'+i+'">'+parseFloat(respo2D[0]).toFixed(2)+'</span> CHF</strong></p>'+
                                                        '</div>'+
                                                        '<div id="splitBillClientDiv1_2'+i+'" style="width:100%;" class="d-flex flex-wrap justify-content-between">'+
                                                            '<button id="splitBillBtn1'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Bar</button>'+
                                                            '<button id="splitBillBtn2'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\',\'none\')">Karte</button>'+
                                                            '<button id="splitBillBtn3'+i+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')" disabled>Online</button>'+
                                                            '<button id="splitBillBtn4'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Auf Rechnung</button>'+
                                                            '<button id="splitBillBtn5'+i+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+i+'\',\''+respo2D[1]+'\',\''+respo2D[2]+'\')">Geschenkkarte</button>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div id="splitBillClientDiv2'+i+'" style="border-bottom:2px solid black;" class="d-flex flex-wrap justify-content-between pt-1 pb-2">'+
                                                        '<div style="width:100%;" id="splitBillClientDiv2_1'+i+'">'+
                                                            '<p style="margin-bottom: 3px; font-size:1.2rem;"><strong><i style="color:red;" class="fa-solid fa-circle-xmark" id="cancelTippApplyBtn'+i+'" onclick="cancelTippApply(\''+i+'\')"></i> Tipp: <span id="splitBillModalTippValueClient'+i+'">'+parseFloat(0).toFixed(2)+'</span> CHF</strong></p>'+
                                                        '</div>'+
                                                        '<div style="width:100%;" class="d-flex flex-wrap justify-content-between">'+
                                                            '<button id="splitBillTippBtn1'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+0.5+'\')">'+parseFloat(0.5).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn2'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+1+'\')">'+parseFloat(1).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn3'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+2+'\')">'+parseFloat(2).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn4'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+5+'\')">'+parseFloat(5).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtn5'+i+'" style="width:24.8%;" class="btn btn-outline-dark shadow-none" onclick="setTipStafSplitBill(\''+i+'\',\''+10+'\')">'+parseFloat(10).toFixed(2)+'</button>'+
                                                            '<button id="splitBillTippBtnCostume'+i+'" style="width:74.4%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none">'+
                                                                '<input id="splitBillTippInputCostume'+i+'" step="0.05" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostumeTipStafSplitBill(\''+i+'\',this.value)" style="width:95%; border:none;" placeholder="Gesamt mit Tipp">'+
                                                            '</button>'+
                                                            '<input type="hidden" id="splitBillTippType'+i+'" value="0">'+
                                                            '<input type="hidden" id="splitBillGCAppliedId'+i+'" value="0">'+
                                                            '<input type="hidden" id="splitBillGCAppliedCHFVal'+i+'" value="0">'+
                                                        '</div>'+
                                                        '<div style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center" id="splitTheBillError01'+i+'">'+
                                                            '<strong>Geben Sie zuerst einen gültigen Wert ein!, Gesamtpreis nach dem Trinkgeld</strong>'+
                                                        '</div>'+
                                                        '<div id="splitTheBillPayAtPOS'+i+'" class="alert alert-info text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Schließen Sie die Zahlung am POS-Terminal ab!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError51'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Schreiben Sie zuerst den Code!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError52'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Ihre Geschenkkarte wurde nicht gefunden!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError53'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Diese Geschenkkarte ist nicht mehr gültig/Ausgeben!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError54'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Tragen Sie zunächst einen gültigen Anwendungswert ein!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError55'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Etwas ist schiefgelaufen. Bitte neu laden und erneut versuchen!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError56'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Dieser Betrag ist zu hoch für diese Geschenkkarte!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError57'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Versuchen Sie es mit einem kleineren Wert, dieser ist zu viel für die aktuelle Rechnung!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError58'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Diese Geschenkkarte ist noch nicht bezahlt. Bezahlen Sie sie, bevor Sie sie verwenden!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError59'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Diese Geschenkkarte ist abgelaufen!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError510'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Diese Geschenkkarte ist nicht von diesem Restaurant und kann hier nicht eingelöst werden!</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError511'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Die Geschenkkarte, die Sie verwenden wollten, ist ungültig</strong>'+
                                                        '</div>'+
                                                        '<div id="splitBillPhaseOneError512'+i+'" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">'+
                                                            '<strong>Die Geschenkkarte, die Sie anwenden wollten, ist noch nicht verkauft (aktiv)!</strong>'+
                                                        '</div>'+
                                                        '<input type="hidden" id="splitBillnitiateId'+i+'" value="'+respo2D[5]+'">'+
                                                    '</div>';
                                $('#splitTheBillInitiateModalBody').append(newClientLineShow);
                            }

                            $('#splitTheBillInitiateModalOriginalPriceShow').html(parseFloat(respo2D[3]).toFixed(2));
                            $('#splitTheBillInitiateModalPayPriceShow').html(parseFloat(respo2D[4]).toFixed(2));

                        }
						// location.reload();
					},
					error: (error) => { console.log(error); }
				});
            }

        }
    }



    function setTipStafSplitBill(clientNr,tipVal){

        var billSplitTippType = $('#splitBillTippType'+clientNr).val();

        if(parseFloat($('#splitBillModalTippValueClient'+clientNr).html()).toFixed(2) != parseFloat(0.00).toFixed(2)){
            var tipValCurr = parseFloat($('#splitBillModalTippValueClient'+clientNr).html()).toFixed(2);

            var toPayCHF = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);
            var crrGCAmtCHF = parseFloat($('#splitBillGCAppliedCHFVal'+clientNr).val()).toFixed(2);
            var newToPayCHF = parseFloat(parseFloat(toPayCHF) - parseFloat(tipValCurr) + parseFloat(crrGCAmtCHF)).toFixed(2);

            $('#splitBillModalPayValueClient'+clientNr).html(parseFloat(newToPayCHF).toFixed(2));

            if(billSplitTippType == 1){
                if(parseFloat(tipValCurr).toFixed(2) == parseFloat(0.50).toFixed(2)){
                    $('#splitBillTippBtn1'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(1).toFixed(2)){
                    $('#splitBillTippBtn2'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(2).toFixed(2)){
                    $('#splitBillTippBtn3'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(5).toFixed(2)){
                    $('#splitBillTippBtn4'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(10).toFixed(2)){
                    $('#splitBillTippBtn5'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }
            }else{
                $('#splitBillTippBtnCostume'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                $('#splitBillTippInputCostume'+clientNr).val('');
            }
            $('#splitBillTippType'+clientNr).val(0);
        }

        if(parseFloat(tipVal).toFixed(2) == parseFloat(0.50).toFixed(2)){
            $('#splitBillTippBtn1'+clientNr).attr('class','btn btn-dark shadow-none');
        }else if(parseFloat(tipVal).toFixed(2) == parseFloat(1).toFixed(2)){
            $('#splitBillTippBtn2'+clientNr).attr('class','btn btn-dark shadow-none');
        }else if(parseFloat(tipVal).toFixed(2) == parseFloat(2).toFixed(2)){
            $('#splitBillTippBtn3'+clientNr).attr('class','btn btn-dark shadow-none');
        }else if(parseFloat(tipVal).toFixed(2) == parseFloat(5).toFixed(2)){
            $('#splitBillTippBtn4'+clientNr).attr('class','btn btn-dark shadow-none');
        }else if(parseFloat(tipVal).toFixed(2) == parseFloat(10).toFixed(2)){
            $('#splitBillTippBtn5'+clientNr).attr('class','btn btn-dark shadow-none');
        }
        $('#splitBillModalTippValueClient'+clientNr).html(parseFloat(tipVal).toFixed(2));

        var toPayCHF = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);
        var crrGCAmtCHF = parseFloat($('#splitBillGCAppliedCHFVal'+clientNr).val()).toFixed(2);
        var newToPayCHF = parseFloat(parseFloat(toPayCHF) + parseFloat(tipVal) - parseFloat(crrGCAmtCHF)).toFixed(2);

        $('#splitBillModalPayValueClient'+clientNr).html(parseFloat(newToPayCHF).toFixed(2));

        $('#splitBillTippType'+clientNr).val(1);
        
    }

    function setCostumeTipStafSplitBill(clientNr,tipVal){
        var billSplitTippType = $('#splitBillTippType'+clientNr).val();

        if(parseFloat($('#splitBillModalTippValueClient'+clientNr).html()).toFixed(2) != parseFloat(0.00).toFixed(2)){
            var tipValCurr = parseFloat($('#splitBillModalTippValueClient'+clientNr).html()).toFixed(2);

            var toPayCHF = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);
            var crrGCAmtCHF = parseFloat($('#splitBillGCAppliedCHFVal'+clientNr).val()).toFixed(2);
            var newToPayCHF = parseFloat(parseFloat(toPayCHF) - parseFloat(tipValCurr) + parseFloat(crrGCAmtCHF)).toFixed(2);

            $('#splitBillModalPayValueClient'+clientNr).html(parseFloat(newToPayCHF).toFixed(2));

            if(billSplitTippType == 1){
                if(parseFloat(tipValCurr).toFixed(2) == parseFloat(0.50).toFixed(2)){
                    $('#splitBillTippBtn1'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(1).toFixed(2)){
                    $('#splitBillTippBtn2'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(2).toFixed(2)){
                    $('#splitBillTippBtn3'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(5).toFixed(2)){
                    $('#splitBillTippBtn4'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(10).toFixed(2)){
                    $('#splitBillTippBtn5'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }
            }else{
                $('#splitBillTippBtnCostume'+clientNr).attr('class','btn btn-outline-dark shadow-none');
            }
            $('#splitBillTippType'+clientNr).val(0);
        }

        var toPayCHF = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);
        var crrGCAmtCHF = parseFloat($('#splitBillGCAppliedCHFVal'+clientNr).val()).toFixed(2);
    
        if(tipVal == '' || tipVal == ' ' || (parseFloat(toPayCHF) >= parseFloat(tipVal))){
            // invalide 
            $('#splitBillTippBtnCostume'+clientNr).attr('class','btn btn-dark shadow-none');

            $('#splitBillModalTippValueClient'+clientNr).html(parseFloat(0).toFixed(2));  
            if($('#splitTheBillError01'+clientNr).is(':hidden')){ $('#splitTheBillError01'+clientNr).show(50).delay(4500).hide(50); }         
        }else{
            var newTipValue = parseFloat(parseFloat(tipVal).toFixed(2) - parseFloat(toPayCHF).toFixed(2)).toFixed(2);

            $('#splitBillTippBtnCostume'+clientNr).attr('class','btn btn-dark shadow-none');
            $('#splitBillModalTippValueClient'+clientNr).html(parseFloat(newTipValue).toFixed(2));
            var toPayCHF = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);
            var newToPayCHF = parseFloat(parseFloat(toPayCHF) + parseFloat(newTipValue) - parseFloat(crrGCAmtCHF)).toFixed(2);
            $('#splitBillModalPayValueClient'+clientNr).html(parseFloat(newToPayCHF).toFixed(2));
            $('#splitBillTippType'+clientNr).val(2);
        }
    }

    function cancelTippApply(clientNr){
        var billSplitTippType = $('#splitBillTippType'+clientNr).val();

        if(parseFloat($('#splitBillModalTippValueClient'+clientNr).html()).toFixed(2) != parseFloat(0.00).toFixed(2)){
            var tipValCurr = parseFloat($('#splitBillModalTippValueClient'+clientNr).html()).toFixed(2);

            var toPayCHF = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);
            var crrGCAmtCHF = parseFloat($('#splitBillGCAppliedCHFVal'+clientNr).val()).toFixed(2);
            var newToPayCHF = parseFloat(parseFloat(toPayCHF) - parseFloat(tipValCurr) + parseFloat(crrGCAmtCHF)).toFixed(2);

            $('#splitBillModalPayValueClient'+clientNr).html(parseFloat(newToPayCHF).toFixed(2));

            if(billSplitTippType == 1){
                if(parseFloat(tipValCurr).toFixed(2) == parseFloat(0.50).toFixed(2)){
                    $('#splitBillTippBtn1'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(1).toFixed(2)){
                    $('#splitBillTippBtn2'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(2).toFixed(2)){
                    $('#splitBillTippBtn3'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(5).toFixed(2)){
                    $('#splitBillTippBtn4'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }else if(parseFloat(tipValCurr).toFixed(2) == parseFloat(10).toFixed(2)){
                    $('#splitBillTippBtn5'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                }
            }else{
                $('#splitBillTippBtnCostume'+clientNr).attr('class','btn btn-outline-dark shadow-none');
                $('#splitBillTippInputCostume'+clientNr).val('');
            }
            $('#splitBillTippType'+clientNr).val(0);

            $('#splitBillModalTippValueClient'+clientNr).html(parseFloat(0).toFixed(2));
        }
    }





    function splitBillPayBarInitiate(clientNr, tableNr, clientsNr){
        var toPayCHF = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);
        $('#splitBillCashModal').modal('show');
        $('#splitBillamToPay').html(toPayCHF);

        $('#splitBilltipForOrderCloseCash').val(parseFloat($('#splitBillModalTippValueClient'+clientNr).html()).toFixed(2));
        
        $('#splitBillpayAllTableNrCash').val(tableNr);
        $('#splitBillpayAllClientsNrCash').val(clientsNr);
        $('#splitBillCrrClientNrCash').val(clientNr);
        $('#splitBillGCIdCloseCash').val($('#splitBillGCAppliedId'+clientNr).val());
        $('#splitBillGCAmtCloseCash').val($('#splitBillGCAppliedCHFVal'+clientNr).val());
    }
    function closeSplitBillCashModal(){
        $("#splitBillCashBody").load(location.href+" #splitBillCashBody>*","");
        $('body').addClass('modal-open');
    }
    function splitBillCashDel(){
        $('#splitBillamByClient').html('0.00');
        $('#splitBillamClientReturn').html('--.--');
    }
    function splitBilladdClPay(addVal){
        addVal = parseFloat(addVal);
        var prevVal = parseFloat($('#splitBillamByClient').html());

        var newVal = parseFloat(addVal + prevVal);
        $('#splitBillamByClient').html(parseFloat(newVal).toFixed(2));

        var totToPay = parseFloat($('#splitBillamToPay').html());
        var toRet = parseFloat(newVal - totToPay);
        if(toRet >= 0){
            $('#splitBillamClientReturn').html(parseFloat(toRet).toFixed(2))
        }
    }
    function splitBillshowPayAllPCMDiv3(){
        splitBillCashDel();
        $('#splitBillshowPayAllPCMDiv3Btn').hide(100);
        $('#splitBillpayAllPCMDiv3').css('display','flex');
        $('.splitBillcurrBtn').prop('disabled', true);
    }

    function splitBillcancelPayAllPCMDiv3(){
        $('#splitBillamByClient').html('0.00');
        $('#splitBillamClientReturn').html('--.--');
        $('#splitBillpayAllPCMDiv3').css('display','none');
        $('#splitBillshowPayAllPCMDiv3Btn').show(100);
        $('#splitBillpayAllPCMDiv3Inp').val('');

        $('.splitBillcurrBtn').prop('disabled', false);
    }

    function splitBillnewValGivByCl(){
        if(!$('#splitBillpayAllPCMDiv3Inp').val()){
            $('#splitBillamByClient').html('0.00');
            $('#splitBillamClientReturn').html('--.--');
        }else{
            var newVal = parseFloat($('#splitBillpayAllPCMDiv3Inp').val());
            if($.isNumeric(newVal) && newVal >= 0){
                var totToPay = parseFloat($('#splitBillamToPay').html());
                var toRet = parseFloat(newVal - totToPay);
                if(toRet >= 0){
                    $('#splitBillamByClient').html(parseFloat(newVal).toFixed(2));
                    $('#splitBillamClientReturn').html(parseFloat(toRet).toFixed(2))
                }else{
                    $('#splitBillamByClient').html('0.00');
                    $('#splitBillamClientReturn').html('--.--');
                }
            }else{
                if($('#splitBillpayAllPCMDiv3Err1').is(':hidden')){ $('#splitBillpayAllPCMDiv3Err1').show(100).delay(4000).hide(100); }
            }
        }
    }
    function splitBillpayAllProdsCash(){
        $.ajax({
			url: '{{ route("splitBill.payCashCard") }}',
			method: 'post',
			data: {
                tableNr : $('#splitBillpayAllTableNrCash').val(),
                clientsNr : $('#splitBillpayAllClientsNrCash').val(),
                bakshoshChf : $('#splitBilltipForOrderCloseCash').val(),
                crrClNr : $('#splitBillCrrClientNrCash').val(),
                initiateId : $('#splitBillnitiateId'+$('#splitBillCrrClientNrCash').val()).val(),
                thePayM: 'Barzahlungen',
                GCId: $('#splitBillGCIdCloseCash').val(),
                GCAmt: $('#splitBillGCAmtCloseCash').val(),
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                respo2D = respo.split('|||');

                $('#splitBillClientDiv1'+respo2D[1]).attr('class','d-flex flex-wrap justify-content-between pt-2 alert-success');
                $('#splitBillClientDiv2'+respo2D[1]).attr('class','d-flex flex-wrap justify-content-between pt-1 pb-2 alert-success');

                $('#splitBillCashModal').modal('hide');
                closeSplitBillCashModal();

                $('#splitBillTippBtn1'+respo2D[1]).remove();
                $('#splitBillTippBtn2'+respo2D[1]).remove();
                $('#splitBillTippBtn3'+respo2D[1]).remove();
                $('#splitBillTippBtn4'+respo2D[1]).remove();
                $('#splitBillTippBtn5'+respo2D[1]).remove();
                $('#splitBillTippBtnCostume'+respo2D[1]).remove();
                $('#splitBillBtn1'+respo2D[1]).removeClass('btn-outline-dark');
                $('#splitBillBtn1'+respo2D[1]).addClass('btn-dark');
                $('#splitBillBtn1'+respo2D[1]).prop('disabled', true);
                $('#splitBillBtn2'+respo2D[1]).prop('disabled', true);
                $('#splitBillBtn3'+respo2D[1]).prop('disabled', true);
                $('#splitBillBtn4'+respo2D[1]).prop('disabled', true);
                $('#splitBillBtn5'+respo2D[1]).prop('disabled', true);

                $('#cancelTippApplyBtn'+respo2D[1]).remove();
                $('#cancelGCApplyBtn'+respo2D[1]).remove();
                
                if(respo2D[2] == 1){
                    // procedure start
                    $('#splitTheBillInitiateModalCancelBtn').remove();
                }else if(respo2D[2] == 2){
                    $('#splitTheBillInitiateModal').modal('hide');

                    var tNr = respo2D[3];
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
                }

                $.ajax({
                    url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                    method: 'post',
                    data: {
                        id: respo2D[0],
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = $.trim(res);
                        res2D = res.split('-8-8-');
                        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                        $('#orderQRCodePicDownloadOI').val(respo2D[0]);
                        $('#orderQRCodePicTel').modal('show');

                        if(respo2D[2] == 1 || respo2D[2] == 0){
                            $('#OrQRCodeModalStillOpen').val(1);
                        }
                    },
                    error: (error) => { console.log(error); }
                });


			},
			error: (error) => { console.log(error); }
		});
    }





    function splitBillPayKarteInitiate(clientNr, tableNr, clientsNr, extInfo){
        if('{{Restorant::find(Auth::user()->sFor)->hasPOS}}' == 1 && extInfo == 'none'){
          
            $.ajax({
                url: '{{ route("payTec.Connect") }}',
                method: 'post',
                data: {_token: '{{csrf_token()}}'},
                success: (res) => {

                    if($('#splitTheBillPayAtPOS'+clientNr).is(':hidden')){ $('#splitTheBillPayAtPOS'+clientNr).show(50).delay(6000).hide(50); }
                        
                        $.ajax({
                            url: '{{ route("payTec.Transact") }}',
                            method: 'post',
                            timeout: 600000, // Sets a 10-minute timeout (milliseconds)
                            data: {
                                totalChf : parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2),
                                _token: '{{csrf_token()}}'
                            },
                            success: (resTransact) => {
                                var resJSON = $.parseJSON(resTransact);
                                if(resJSON.TrxResult == 0){
                                    
                                    splitBillPayKarteRegister(resTransact,tableNr,clientsNr,clientNr);

                                }else{
                                    registerPayTecErrorData(resTransact);
                                    alert('fail register  -- '+resJSON.CardholderText+' '+resJSON.AttendantText);
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
                splitBillPayKarteRegister('none',tableNr,clientsNr,clientNr);
            }

                    
    }
    function splitBillPayKarteRegister(extInfo, taNr, clisNr, cliNr){
        $.ajax({
			url: '{{ route("splitBill.payCashCard") }}',
			method: 'post',
			data: {
                tableNr : taNr,
                clientsNr : clisNr,
                bakshoshChf : parseFloat($('#splitBillModalTippValueClient'+cliNr).html()).toFixed(2),
                crrClNr : cliNr,
                initiateId : $('#splitBillnitiateId'+cliNr).val(),
                thePayM: 'Kartenzahlung',
                payTecTrx : extInfo,
                GCId: $('#splitBillGCAppliedId'+cliNr).val(),
                GCAmt: $('#splitBillGCAppliedCHFVal'+cliNr).val(),
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                respo2D = respo.split('|||');

                $('#splitBillClientDiv1'+respo2D[1]).attr('class','d-flex flex-wrap justify-content-between pt-2 alert-success');
                $('#splitBillClientDiv2'+respo2D[1]).attr('class','d-flex flex-wrap justify-content-between pt-1 pb-2 alert-success');
                
                $('#splitBillTippBtn1'+respo2D[1]).remove();
                $('#splitBillTippBtn2'+respo2D[1]).remove();
                $('#splitBillTippBtn3'+respo2D[1]).remove();
                $('#splitBillTippBtn4'+respo2D[1]).remove();
                $('#splitBillTippBtn5'+respo2D[1]).remove();
                $('#splitBillTippBtnCostume'+respo2D[1]).remove();
                $('#splitBillBtn1'+respo2D[1]).prop('disabled', true);
                $('#splitBillBtn2'+respo2D[1]).prop('disabled', true);
                $('#splitBillBtn2'+respo2D[1]).removeClass('btn-outline-dark');
                $('#splitBillBtn2'+respo2D[1]).addClass('btn-dark');
                $('#splitBillBtn3'+respo2D[1]).prop('disabled', true);
                $('#splitBillBtn4'+respo2D[1]).prop('disabled', true);
                $('#splitBillBtn5'+respo2D[1]).prop('disabled', true);

                $('#cancelTippApplyBtn'+respo2D[1]).remove();
                $('#cancelGCApplyBtn'+respo2D[1]).remove();

                if(respo2D[2] == 1){
                    // procedure start
                    $('#splitTheBillInitiateModalCancelBtn').remove();
                }else if(respo2D[2] == 2){
                    $('#splitTheBillInitiateModal').modal('hide');

                    var tNr = respo2D[3];
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
                }

                $.ajax({
                    url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                    method: 'post',
                    data: {
                        id: respo2D[0],
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = $.trim(res);
                        res2D = res.split('-8-8-');
                        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                        $('#orderQRCodePicDownloadOI').val(respo2D[0]);
                        $('#orderQRCodePicTel').modal('show');

                        if(respo2D[2] == 1 || respo2D[2] == 0){
                            $('#OrQRCodeModalStillOpen').val(1);
                        }
                    },
                    error: (error) => { console.log(error); }
                });

			},
			error: (error) => { console.log(error); }
		});
    }





    function splitBillPrepPayOnline(clientNr, tableNr, clientsNr){

    }




















    function splitBillOpenGCPay(clientNr, tableNr, clientsNr){
        var newDiv1_2 = '<button class="btn btn-danger shadow-none" style="width:5%; padding:0;" onclick="splitBillCloseGCPay(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\')"><strong><i class="fa-solid fa-arrow-left"></i></strong></button>'+
                        '<div style="width:94%;" class="input-group">'+
                            '<input type="text" style="height:100%;" class="form-control shadow-none" id="splitBillgcValidationCodeInput" placeholder="Geschenkkartencode" aria-label="Geschenkkartencode">'+
                            '<div class="input-group-append">'+
                                '<button id="splitBillValidateGCBtn" style="margin:0px;" class="btn btn-outline-dark shadow-none" type="button" onclick="splitBillValidateGC(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\')"><strong>Bestätigen</strong></button>'+
                            '</div>'+
                            '<div class="input-group-append">'+
                                '<button id="splitBillOpenCameraModalBtn" style="margin:0px;" class="btn btn-outline-dark shadow-none" type="button"'+
                                'data-toggle="modal" data-target="#splitBillCameraModal">'+
                                    '<i class="fa-solid fa-camera"></i>'+
                                '</button>'+
                            '</div>'+
                            '<input type="hidden" id="splitBillCrrGCClientNr" value="'+clientNr+'">'+
                            '<input type="hidden" id="splitBillCrrGCTableNr" value="'+tableNr+'">'+
                            '<input type="hidden" id="splitBillCrrGCClientsNr" value="'+clientsNr+'">'+
                        '</div>';
        $('#splitBillClientDiv1_2'+clientNr).html(newDiv1_2);

        
    }
    function splitBillCloseGCPay(clientNr, tableNr, clientsNr){
        var newDiv1_2 = '<button id="splitBillBtn1'+clientNr+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')">Bar</button>'+
                        '<button id="splitBillBtn2'+clientNr+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\',\'none\')">Karte</button>'+
                        '<button id="splitBillBtn3'+clientNr+'" style="width:33%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')" disabled>Online</button>'+
                        '<button id="splitBillBtn4'+clientNr+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')">Auf Rechnung</button>'+
                        '<button id="splitBillBtn5'+clientNr+'" style="width:49.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')">Geschenkkarte</button>';
        $('#splitBillClientDiv1_2'+clientNr).html(newDiv1_2);
    }


    function splitBillValidateGC(clientNr, tableNr, clientsNr){
        if(!$('#splitBillgcValidationCodeInput').val()){
            if( $('#splitBillPhaseOneError51'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError51'+clientNr).show(100).delay(4500).hide(100); }
        }else{
            $('#splitBillValidateGCBtn').prop('disabled', true);
            $('#splitBillgcValidationCodeInput').prop('disabled', true);
            $.ajax({
				url: '{{ route("splitBill.splitBillGiftCardValidateTheIdnCode") }}',
				method: 'post',
				data: {
					gcIdnCode: $('#splitBillgcValidationCodeInput').val(),
					_token: '{{csrf_token()}}'
				},
				success: (response) => {
					response = $.trim(response);
                    if(response == 'gcNotFound'){
                        if( $('#splitBillPhaseOneError52'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError52'+clientNr).show(100).delay(4500).hide(100); }
                        $('#splitBillValidateGCBtn').prop('disabled', false);
                        $('#splitBillgcValidationCodeInput').prop('disabled', false);
                    }else if(response == 'gcIsAllSpend'){
                        if( $('#splitBillPhaseOneError53'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError53'+clientNr).show(100).delay(4500).hide(100); }
                        $('#splitBillValidateGCBtn').prop('disabled', false);
                        $('#splitBillgcValidationCodeInput').prop('disabled', false);
                    }else if(response == 'gcIsNotPaid'){
                        if( $('#splitBillPhaseOneError58'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError58'+clientNr).show(100).delay(4500).hide(100); }
                        $('#splitBillValidateGCBtn').prop('disabled', false);
                        $('#splitBillgcValidationCodeInput').prop('disabled', false);
                    }else if(response == 'gcExpired'){
                        if( $('#splitBillPhaseOneError59'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError59'+clientNr).show(100).delay(4500).hide(100); }
                        $('#splitBillValidateGCBtn').prop('disabled', false);
                        $('#splitBillgcValidationCodeInput').prop('disabled', false);
                    }else if(response == 'gcNotOfThisRes'){
                        if( $('#splitBillPhaseOneError510'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError510'+clientNr).show(100).delay(4500).hide(100); }
                        $('#splitBillValidateGCBtn').prop('disabled', false);
                        $('#splitBillgcValidationCodeInput').prop('disabled', false);
                    }else{
                        respo2D = response.split('|||');
                    
                        $('#splitBillGCAppliedId'+clientNr).val(respo2D[0]);

                        var newDiv1_2 = '<button class="btn btn-danger shadow-none" style="width:5%; padding:0;" onclick="splitBillCloseGCPay(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\')"><strong><i class="fa-solid fa-arrow-left"></i></strong></button>'+
                                        '<div style="width:94%;" class="input-group">'+
                                            '<div class="input-group-prepend">'+
                                                '<span class="input-group-text" id="basic-addon1">'+parseFloat(respo2D[1]).toFixed(2)+' CHF</span>'+
                                            '</div>'+
                                            '<input type="text" style="height:100%;" class="form-control shadow-none" id="splitBillgcAmtToApplyInput'+clientNr+'" placeholder="Rabattbetrag" aria-label="Rabattbetrag">'+
                                            '<div class="input-group-append">'+
                                                '<button id="splitBillApplyAmtGCBtn'+clientNr+'" style="margin:0px;" class="btn btn-outline-dark shadow-none" type="button" onclick="splitBillApplyAmtGC(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\')"><strong>Anwenden</strong></button>'+
                                            '</div>'+
                                        '</div>'+
                                        '<button class="btn btn-outline-success shadow-none mt-1" style="width:100%;" onclick="splitBillApplyAmtMaxGC(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\')"><strong>Maximal anwenden</strong></button>';
                        $('#splitBillClientDiv1_2'+clientNr).html(newDiv1_2);
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    }

    function splitBillApplyAmtGC(clientNr, tableNr, clientsNr){
        if(!$('#splitBillgcAmtToApplyInput'+clientNr).val()){
            if( $('#splitBillPhaseOneError54'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError54'+clientNr).show(100).delay(4500).hide(100); }
        }else{
            var dicsValFromGC = parseFloat($('#splitBillgcAmtToApplyInput'+clientNr).val()).toFixed(2);
            if(dicsValFromGC <= 0){
                if( $('#splitBillPhaseOneError54'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError54'+clientNr).show(100).delay(4500).hide(100); }
            }else if($('#splitBillGCAppliedId'+clientNr).val() == 0){
                if( $('#splitBillPhaseOneError55'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError55'+clientNr).show(100).delay(4500).hide(100); }
            }else{
                $.ajax({
                    url: '{{ route("splitBill.splitBillGiftCardApplyAmount") }}',
                    method: 'post',
                    data: {
                        gcId: $('#splitBillGCAppliedId'+clientNr).val(),
                        gcDiscAmnt: dicsValFromGC,
                        _token: '{{csrf_token()}}'
                    },
                    success: (response) => {
                        response = $.trim(response);
                        if(response == 'gcNotFound'){
                            if( $('#splitBillPhaseOneError55'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError55'+clientNr).show(100).delay(4500).hide(100); }
                        }else if(response == 'gcAmountNotAvailable'){
                            if( $('#splitBillPhaseOneError56'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError56'+clientNr).show(100).delay(4500).hide(100); }
                        }else{
                            response = parseFloat(response).toFixed(2);
                            var tot = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);

                            if(Number(tot) > Number(response)){
                             
                                $('#splitBillGCAppliedCHFVal'+clientNr).val(parseFloat(response).toFixed(2));
                            
                                var showGCDisc ='<p id="showGCApplyP'+clientNr+'" style="margin-bottom: 3px; font-size:1.2rem;"><strong><i style="color:red;" class="fa-solid fa-circle-xmark" id="cancelGCApplyBtn'+clientNr+'" onclick="cancelGCApply(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\')"></i> Geschenkkarte: <span id="splitBillModalGCValueClientShow'+clientNr+'">'+parseFloat(response).toFixed(2)+'</span> CHF</strong></p>';
                                $('#splitBillClientDiv2_1'+clientNr).append(showGCDisc);

                                $('#splitBillModalPayValueClient'+clientNr).html( parseFloat(parseFloat(tot) - parseFloat(response)).toFixed(2));

                                var newDiv1_2 = '<button id="splitBillBtn1'+clientNr+'" style="width:24.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')">Bar</button>'+
                                                '<button id="splitBillBtn2'+clientNr+'" style="width:24.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\',\'none\')">Karte</button>'+
                                                '<button id="splitBillBtn3'+clientNr+'" style="width:24.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')" disabled>Online</button>'+
                                                '<button id="splitBillBtn4'+clientNr+'" style="width:24.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')">Auf Rechnung</button>';
                                $('#splitBillClientDiv1_2'+clientNr).html(newDiv1_2);

                            }else if(Number(tot) == Number(response)){

                                $('#splitBillGCAppliedCHFVal'+clientNr).val(parseFloat(response).toFixed(2));

                                var showGCDisc ='<p id="showGCApplyP'+clientNr+'" style="margin-bottom: 3px; font-size:1.2rem;"><strong><i style="color:red;" class="fa-solid fa-circle-xmark" id="cancelGCApplyBtn'+clientNr+'" onclick="cancelGCApply(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\')"></i> Geschenkkarte: <span id="splitBillModalGCValueClientShow'+clientNr+'">'+parseFloat(response).toFixed(2)+'</span> CHF</strong></p>';
                                $('#splitBillClientDiv2_1'+clientNr).append(showGCDisc);

                                $('#splitBillModalPayValueClient'+clientNr).html( parseFloat(parseFloat(tot) - parseFloat(response)).toFixed(2));

                                var newDiv1_2 = '<button class="btn btn-outline-success shadow-none mt-1" style="width:100%;" onclick="splitBillPayKarteInitiate(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\',\'GCAllPay\')"><strong>Zahlung mit Geschenkkarte</strong></button>';
                                $('#splitBillClientDiv1_2'+clientNr).html(newDiv1_2);

                            }else{
                                // to much discount
                                if( $('#splitBillPhaseOneError57'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError57'+clientNr).show(100).delay(4500).hide(100); }
                            }
                        }
                       
                    },
                    error: (error) => { console.log(error); }
                });
            }
        }
    }

    function splitBillApplyAmtMaxGC(clientNr, tableNr, clientsNr){
        if($('#splitBillGCAppliedId'+clientNr).val() == 0){
            if( $('#splitBillPhaseOneError55'+clientNr).is(':hidden') ){ $('#splitBillPhaseOneError55'+clientNr).show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
                url: '{{ route("splitBill.splitBillGiftCardApplyAmountMax") }}',
                method: 'post',
                data: {
                    gcId: $('#splitBillGCAppliedId'+clientNr).val(),
                    toPayAmnt: parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    response = $.trim(response);
                    if(response == 'gcNotFound'){
                        if( $('#payAllPhaseOneError55'+clientNr).is(':hidden') ){ $('#payAllPhaseOneError55'+clientNr).show(100).delay(4500).hide(100); }
                    }else{
                        var tot = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);

                        $('#splitBillGCAppliedCHFVal'+clientNr).val(parseFloat(response).toFixed(2));

                        var showGCDisc ='<p id="showGCApplyP'+clientNr+'" style="margin-bottom: 3px; font-size:1.2rem;"><strong><i style="color:red;" class="fa-solid fa-circle-xmark" id="cancelGCApplyBtn'+clientNr+'" onclick="cancelGCApply(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\')"></i> Geschenkkarte: <span id="splitBillModalGCValueClientShow'+clientNr+'">'+parseFloat(response).toFixed(2)+'</span> CHF</strong></p>';
                        $('#splitBillClientDiv2_1'+clientNr).append(showGCDisc);

                        $('#splitBillModalPayValueClient'+clientNr).html( parseFloat(parseFloat(tot) - parseFloat(response)).toFixed(2));

                        var newDiv1_2 = '<button class="btn btn-outline-success shadow-none mt-1" style="width:100%;" onclick="splitBillPayKarteInitiate(\''+clientNr+'\', \''+tableNr+'\', \''+clientsNr+'\',\'GCAllPay\')"><strong>Zahlung mit Geschenkkarte</strong></button>';
                        $('#splitBillClientDiv1_2'+clientNr).html(newDiv1_2);
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    function cancelGCApply(clientNr, tableNr, clientsNr){
        var tot = parseFloat($('#splitBillModalPayValueClient'+clientNr).html()).toFixed(2);
        var crrGCAmnt = parseFloat($('#splitBillGCAppliedCHFVal'+clientNr).val()).toFixed(2);

        $('#showGCApplyP'+clientNr).remove();
        $('#splitBillGCAppliedId'+clientNr).val(0);
        $('#splitBillGCAppliedCHFVal'+clientNr).val(0);

        $('#splitBillModalPayValueClient'+clientNr).html( parseFloat(parseFloat(tot) + parseFloat(crrGCAmnt)).toFixed(2));

        var newDiv1_2 = '<button id="splitBillBtn1'+clientNr+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayBarInitiate(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')">Bar</button>'+
                        '<button id="splitBillBtn2'+clientNr+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPayKarteInitiate(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\',\'none\')">Karte</button>'+
                        '<button id="splitBillBtn3'+clientNr+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayOnline(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')" disabled>Online</button>'+
                        '<button id="splitBillBtn4'+clientNr+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillPrepPayAufRechnung(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')">Auf Rechnung</button>'+
                        '<button id="splitBillBtn5'+clientNr+'" style="width:19.5%;" class="btn btn-outline-dark shadow-none" onclick="splitBillOpenGCPay(\''+clientNr+'\',\''+tableNr+'\',\''+clientsNr+'\')">Geschenkkarte</button>';
        
        $('#splitBillClientDiv1_2'+clientNr).html(newDiv1_2);
    }



</script>

