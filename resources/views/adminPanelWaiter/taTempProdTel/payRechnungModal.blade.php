<?php
use App\rechnungClient;
?>

<!-- payAllProdsRechnungModal Modal (payment through email)-->
<div class="modal" id="payAllProdsRechnungModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><strong>Kauf auf Rechnung</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePayAllProdsRechnungModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
           
            <div id="clientsRechnungAllList" style="max-height: 200px; width:100%; overflow-y: scroll;">
                @foreach (rechnungClient::where('toRes',Auth::user()->sFor)->get() as $clOne)
                    <button class="btn btn-outline-dark shadow-none" onclick="selectClRechnungAll('{{$clOne->id}}')"
                    style="text-align:left; width:96%; margin:3px 0px 3px 4%;" id="clientOneRechnungAll{{$clOne->id}}">
                        <strong>{{$clOne->name}} {{$clOne->lastname}} - "{{$clOne->firmaName}}" - {{$clOne->street}} {{$clOne->plzort}}</strong>
                    </button>
                @endforeach
            </div>
           
            <div class="modal-body" id="payAllProdsRechnungBody">

                <form action="{{ route('tempTAProds.payTempTaByBillWithData') }}" id="payAllProdsRechnungForm" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div id="payAllPRechnungMDiv1" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <input style="width:100%" name="RechnungFirmaInp" id="RechnungFirmaInp" class="form-control shadow-none mb-1" type="text" placeholder="Firma">
                        <div id="payAllPRMDiv1TelVer01" style="width:100%;" class="input-group mb-1">
                            <input name="RechnungTelInp" id="RechnungTelInp" class="form-control shadow-none" type="text" placeholder="Tel. Nummer">
                            <div class="input-group-append">
                                <button id="RechnungTelVerBtn1" style="margin:0px;" class="btn btn-success shadow-none" onclick="payAllVerifyTelSendNr()" type="button">Bestätigen</button>
                            </div>
                        </div>
                        <input type="hidden" name="telVerCodeFromSer" id="telVerCodeFromSer" value="0">
                        <input type="hidden" name="telVerValidationStatus" id="telVerValidationStatus" value="0">
                        <div class="alert alert-success text-center mb-1" style="display:none; width:100%; margin:0px;" id="payAllProdsRechnungModalSucc01">
                            Telefonnummer erfolgreich verifiziert
                        </div>
                        <p id="telVerCodeDemoShow" style="display:none;"></p>
                        <div id="payAllPRMDiv1TelVer02" style="width:100%; display:none;" class="input-group mb-1">
                            <input name="RechnungTelCodeInp" id="RechnungTelCodeInp" class="form-control shadow-none" type="text" placeholder="Verifizierungs-Schlüssel">
                            <div class="input-group-append">
                                <button id="RechnungTelVerBtn2" style="margin:0px;" class="btn btn-success shadow-none" onclick="payAllVerifyTelSendCode()" type="button">Senden</button>
                            </div>
                        </div>
                        <input style="width:49%" name="RechnungNameInp" id="RechnungNameInp" class="form-control shadow-none mb-1" type="text" placeholder="Name">
                        <input style="width:49%" name="RechnungVornameInp" id="RechnungVornameInp" class="form-control shadow-none mb-1" type="text" placeholder="Vorname">

                        <input style="width:49%" name="RechnungStrNrInp" id="RechnungStrNrInp" class="form-control shadow-none mb-1" type="text" placeholder="Strasse/Nr.">
                        <input style="width:49%" name="RechnungPlzOrtInp" id="RechnungPlzOrtInp" class="form-control shadow-none mb-1" type="text" placeholder="PLZ/ORT">

                        <input style="width:49%" name="RechnungLandInp" id="RechnungLandInp" class="form-control shadow-none mb-1" type="text" placeholder="Land">
                        <input style="width:49%" name="RechnungEmailInp" id="RechnungEmailInp" class="form-control shadow-none mb-1" type="text" placeholder="E-mail">
                    </div>

                    <div id="payAllPRechnungMDiv2" style="display:flex;" class="input-group mt-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Zalungsfrist: </span>
                        </div>
                        <input type="text" name="RechnungDaysToPayInp" id="RechnungDaysToPayInp" class="form-control shadow-none">
                        <div class="input-group-append">
                            <span class="input-group-text">Tage</span>
                        </div>
                    </div>

                    <div id="payAllPRechnungMDiv3" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <div style="width:100%">
                            <p style="margin-bottom: 5px; font-size:1.3rem;"><strong>Zu Bezahlen: CHF <span id="amToPayRechnung"></span></strong></p>
                        </div>
                        <div class="input-group" style="width:100%">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><strong>Kommentar:</strong></span>
                            </div>
                            <textarea id="payAllRechnungComment" name="payAllRechnungComment" class="form-control shadow-none" rows="2" aria-label="Kommentar"></textarea>
                        </div>
                    </div>

                    <div id="payAllPRechnungMDiv4" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <img src="" id="signaturePrew" alt="Unterschreiben und speichern" style="width: 100%; height: 160px;">
                        <textarea id="signature64" name="signed" style="display: none;" required></textarea>
                        <div style="width:100%;" id="payAllPRechnungMDiv4BTNs" class="d-flex flex-wrap justify-content-between">
                            <button type="button" style="width:100%; margin:0px;" class="btn btn-info p-4 shadow-none" data-toggle="modal" data-target="#payAllRechnungsignatureModal"><strong>Unterschrift</strong></button>
                            
                            <div id="payAllPRechnungMDiv41" style="width:100%;" class="mt-3 mb-3" >
                                <div class="form-check">
                                    <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="asveClientForAufRechnungAll()" id="saveClForARechnungAll">
                                    <label class="form-check-label pl-2 pt-1" for="saveClForARechnungAll"><strong>Speichern Sie diesen Kunden als Stammkunden</strong></label>
                                </div>
                            </div>

                            <button type="button" style="width:100%; margin:0px;" class="mb-2 btn btn-success p-4 shadow-none" onclick="finishPayRechnungAll()"><strong>Abschliessen und per E-mail senden</strong></button>
                        </div>
                    </div>

                    <button type="button" style="width:100%; margin:0px; display:none;" class="mb-1 mt-2 btn btn-danger p-1 shadow-none" id="cancelClSelectRechnungAllBtn"
                    onclick="cancelClSelectRechnungAll()"><strong>Deaktivieren Sie den aktuellen Client</strong></button>

                    <div class="input-group" style="width:100%; display:none;" id="payAllRechnungCommentClientDiv">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Kommentar:</strong></span>
                        </div>
                        <textarea id="payAllRechnungCommentClient" name="payAllRechnungCommentClient" class="form-control shadow-none" rows="2" aria-label="Kommentar"></textarea>
                    </div>
                    
                    <button type="button" style="width:100%; margin:0px; display:none;" class="mb-2 mt-4 btn btn-success p-4 shadow-none" id="finishPayRechnungAllCLBtn"
                    onclick="finishPayRechnungAllCL()"><strong>Abschliessen und per E-mail senden</strong></button>

                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr01">
                        Schreiben Sie bitte den Firmennamen!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr02">
                        Überprüfen Sie die Telefonnummer, bevor Sie fortfahren
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr03">
                        Schreiben Sie zuerst den Namen des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr04">
                        Schreiben Sie zuerst den Nachnamen des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr05">
                        Schreiben Sie zuerst die Straße, PLZ, ORT und Nummer des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr06">
                        Schreiben Sie zuerst das Land des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr07">
                        Schreiben Sie zuerst die E-Mail des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr08">
                        Schreiben Sie zuerst die Telefonnummer des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr09">
                        Wir akzeptieren diese Telefonnummer nicht
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr10">
                        Auf dem Server ist etwas schief gelaufen, bitte versuchen Sie es erneut!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr11">
                        Schreiben Sie bitte den Bestätigungscode
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr12">
                        Dieser Bestätigungscode ist nicht korrekt, versuchen Sie es erneut!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr13">
                        Schreiben Sie die Anzahl der Tage für die Zahlungsfrist
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr14">
                        Die Anzahl der Tage für das Zahlungsziel ist ungültig
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="payAllProdsRechnungModalErr15">
                        Der Kunde muss seine Unterschrift leisten!
                    </div>
                    
                    <input type="hidden" name="payAllTableNrRechnung" id="payAllTableNrRechnung" value="0">
                    <input type="hidden" name="discReasonRechnung" id="discReasonRechnung" value="empty">

                    <input type="hidden" name="cashDiscountInpRechnung" id="cashDiscountInpRechnung" value="0">
                    <input type="hidden" name="percentageDiscountInpRechnung" id="percentageDiscountInpRechnung" value="0">
                    <input type="hidden" name="tvshAmProdsPerventageRechnung" id="tvshAmProdsPerventageRechnung" value="0">

                    <input type="hidden" id="saveClForARechnungAllVal" name="saveClForARechnungAllVal" value="0">
                    <input type="hidden" id="usedAndExistingClientAll" name="usedAndExistingClientAll" value="0">
                    <input type="hidden" id="usedAndExistingClientIdAll" name="usedAndExistingClientIdAll" value="0">

                    <input type="hidden" id="tipForOrderRechnung" name="tipForOrderRechnung" value="0">

                    <input type="hidden" id="payAllRechnungGiftCardId" name="payAllRechnungGiftCardId" value="0">
                    <input type="hidden" id="payAllRechnungGiftCardAmount" name="payAllRechnungGiftCardAmount" value="0">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- signature Modal  -->
<div class="modal" id="payAllRechnungsignatureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:200px;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color:rgb(39,190,175);" class="modal-title" id="exampleModalLabel">
                    <strong>Schreiben Sie bitte Ihre Unterschrift (<span id="payAllRechnungsignatureModalAttempt"> Versuch 1/5 </span>)</strong>
                </h5>
                <button type="button" class="close" onclick="closepayAllRechnungsignatureModal()"  aria-label="Close"> <i class="far fa-times-circle"></i> </button>
            </div>
            <div class="modal-body text-center d-flex flex-wrap justify-content-between">
                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:57px;" id="signaturePad"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:57px; display:none;" id="signaturePad2"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:57px; display:none;" id="signaturePad3"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:57px; display:none;" id="signaturePad4"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:57px; display:none;" id="signaturePad5"></div>


                <button type="button" id="clearSignature" class="btn btn-danger mt-2" style="width:45%;">Abbrechen</button>
                <button type="button" id="saveSignature" onclick="clickSaveSignaturePARechnung()" class="btn btn-info mt-2" style="width:45%;"><i class="fas fa-signature mr-2"></i> Sparen</button>
                                   
            </div>
        </div>
    </div>
</div>




<script>
    function closePayAllProdsRechnungModal(){
        $("#payAllProdsRechnungModal").load(location.href+" #payAllProdsRechnungModal>*","");
    }

    function prepPayAllProdsRechnung(){
        if(($('#cashDiscountInp').val() > 0 || $('#percentageDiscountInp').val() > 0) && !$('#discReasonInp').val()){
            if($('#payAllPhaseOneError1').is(':hidden')){ $('#payAllPhaseOneError1').show(100).delay(4000).hide(100); }
        }else if($('.productShowTATemp').length <= 0){
            if($('#payAllPhaseOneError2').is(':hidden')){ $('#payAllPhaseOneError2').show(100).delay(4000).hide(100); }
        }else{

            $('#discReasonRechnung').val($('#discReasonInp').val());

            $('#cashDiscountInpRechnung').val($('#cashDiscountInp').val());
            $('#percentageDiscountInpRechnung').val($('#percentageDiscountInp').val());

            $('#tipForOrderRechnung').val($('#tipForOrder').val());

            $('#payAllRechnungGiftCardId').val($('#payAllGCAppliedId').val());
            $('#payAllRechnungGiftCardAmount').val($('#payAllGCAppliedCHFVal').val());

            $('#addOrForTAModal').modal('toggle');
            $('#payAllProdsRechnungModal').modal('toggle');

            $('body').attr('class','modal-open');

            if($('#cashDiscountInp').val() > 0 || $('#percentageDiscountInp').val() > 0){
                // discoun Active 
                $('#amToPayRechnung').html(parseFloat(parseFloat($('#totalDisc').html()) + parseFloat($('#tipForOrderRechnung').val())).toFixed(2));
            }else{
                $('#amToPayRechnung').html(parseFloat(parseFloat($('#totAmProds').html()) + parseFloat($('#tipForOrderRechnung').val())).toFixed(2));
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
            var dayToPay = $('#RechnungDaysToPayInp').val();
            if(!pattern.test(emailInp)){
                if($('#payAllProdsRechnungModalErr12').is(":hidden")){ $('#payAllProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(Math.floor(dayToPay) != dayToPay || !$.isNumeric(dayToPay) || dayToPay < 1) {
                if($('#payAllProdsRechnungModalErr14').is(":hidden")){ $('#payAllProdsRechnungModalErr14').show(100).delay(4000).hide(100); } 
            }else{
                // success

                // set the variable for payment is Not Selective
                $('#OrQRCodePayIsSelective').val('0');

                $('#payAllPRechnungMDiv4BTNs').html('<img src="storage/gifs/loading2.gif" style="width:20%; margin-left:40%;" alt="">');
                $('#payAllProdsRechnungForm').submit();
            }
        }
    }

    function finishPayRechnungAllCL(){
        // set the variable for payment is Not Selective
        $('#OrQRCodePayIsSelective').val('0');
        $('#cancelClSelectRechnungAllBtn').prop('disabled', true);
        $('#finishPayRechnungAllCLBtn').prop('disabled', true);
        $('#finishPayRechnungAllCLBtn').html('<img src="storage/gifs/loading2.gif" style="width:30px; height:auto;" alt="">');
        $('#payAllProdsRechnungForm').submit();
    }
</script>