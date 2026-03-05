<?php
    use App\rechnungClient;
?>

<!-- splitBillRechnungModal Modal (payment through email)-->
<div class="modal" id="splitBillRechnungModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Kauf auf Rechnung</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeSplitBillRechnungModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
           
            <div id="splitBillclientsRechnungAllList" style="max-height: 200px; width:100%; overflow-y: scroll;">
                @foreach (rechnungClient::where('toRes',Auth::user()->sFor)->get() as $clOne)
                    <button class="btn btn-outline-dark shadow-none" onclick="splitBillselectClRechnungAll('{{$clOne->id}}')"
                    style="text-align:left; width:96%; margin:3px 0px 3px 4%;" id="splitBillclientOneRechnungAll{{$clOne->id}}">
                        <strong>{{$clOne->name}} {{$clOne->lastname}} - "{{$clOne->firmaName}}" - {{$clOne->street}} {{$clOne->plzort}}</strong>
                    </button>
                @endforeach
            </div>
           
            <div class="modal-body" id="splitBillRechnungBody">

                <form action="{{ route('splitBill.payAufRechnung') }}" id="splitBillRechnungForm" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div id="splitBillRechnungMDiv1" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <input style="width:100%" name="splitBillRechnungFirmaInp" id="splitBillRechnungFirmaInp" class="form-control shadow-none mb-1" type="text" placeholder="Firma">
                        <div id="splitBillPRMDiv1TelVer01" style="width:100%;" class="input-group mb-1">
                            <input name="splitBillRechnungTelInp" id="splitBillRechnungTelInp" class="form-control shadow-none" type="text" placeholder="Tel. Nummer">
                            <div class="input-group-append">
                                <button id="splitBillRechnungTelVerBtn1" style="margin:0px;" class="btn btn-success shadow-none" onclick="splitBillVerifyTelSendNr()" type="button">Bestätigen</button>
                            </div>
                        </div>

                        <input type="hidden" name="splitBilltelVerCodeFromSer" id="splitBilltelVerCodeFromSer" value="0">
                        <input type="hidden" name="splitBilltelVerValidationStatus" id="splitBilltelVerValidationStatus" value="0">

                        <div class="alert alert-success text-center mb-1" style="display:none; width:100%; margin:0px;" id="splitBillProdsRechnungModalSucc01">
                            Telefonnummer erfolgreich verifiziert
                        </div>
                        <p id="splitBilltelVerCodeDemoShow" style="display:none;"></p>
                        <div id="splitBillPRMDiv1TelVer02" style="width:100%; display:none;" class="input-group mb-1">
                            <input name="splitBillRechnungTelCodeInp" id="splitBillRechnungTelCodeInp" class="form-control shadow-none" type="text" placeholder="Verifizierungs-Schlüssel">
                            <div class="input-group-append">
                                <button id="splitBillRechnungTelVerBtn2" style="margin:0px;" class="btn btn-success shadow-none" onclick="splitBillVerifyTelSendCode()" type="button">Senden</button>
                            </div>
                        </div>
                        <input style="width:49%" name="splitBillRechnungNameInp" id="splitBillRechnungNameInp" class="form-control shadow-none mb-1" type="text" placeholder="Name">
                        <input style="width:49%" name="splitBillRechnungVornameInp" id="splitBillRechnungVornameInp" class="form-control shadow-none mb-1" type="text" placeholder="Vorname">

                        <input style="width:49%" name="splitBillRechnungStrNrInp" id="splitBillRechnungStrNrInp" class="form-control shadow-none mb-1" type="text" placeholder="Strasse/Nr.">
                        <input style="width:49%" name="splitBillRechnungPlzOrtInp" id="splitBillRechnungPlzOrtInp" class="form-control shadow-none mb-1" type="text" placeholder="PLZ/ORT">

                        <input style="width:49%" name="splitBillRechnungLandInp" id="splitBillRechnungLandInp" class="form-control shadow-none mb-1" type="text" placeholder="Land">
                        <input style="width:49%" name="splitBillRechnungEmailInp" id="splitBillRechnungEmailInp" class="form-control shadow-none mb-1" type="text" placeholder="E-mail">
                    </div>

                    <div id="splitBillRechnungMDiv2" style="display:flex;" class="input-group mt-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Zalungsfrist: </span>
                        </div>
                        <input type="text" name="splitBillRechnungDaysToPayInp" id="splitBillRechnungDaysToPayInp" class="form-control shadow-none">
                        <div class="input-group-append">
                            <span class="input-group-text">Tage</span>
                        </div>
                    </div>

                    <div id="splitBillRechnungMDiv3" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <div style="width:100%">
                            <p style="margin-bottom: 5px; font-size:1.3rem;"><strong>Zu Bezahlen: CHF <span id="splitBillamToPayRechnung"></span></strong></p>
                        </div>
                        <div class="input-group" style="width:100%">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><strong>Kommentar:</strong></span>
                            </div>
                            <textarea id="splitBillpayAllRechnungComment" name="splitBillpayAllRechnungComment" class="form-control shadow-none" rows="2" aria-label="Kommentar"></textarea>
                        </div>
                    </div>

                    <div id="splitBillRechnungMDiv4" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <img src="" id="splitBillsignaturePrew" alt="Unterschreiben und speichern" style="width: 100%; height: 160px;">
                        <textarea id="splitBillsignature64" name="splitBillsigned" style="display: none;" required></textarea>
                        <div style="width:100%;" id="splitBillpayAllPRechnungMDiv4BTNs" class="d-flex flex-wrap justify-content-between">
                            <button type="button" style="width:100%; margin:0px;" class="btn btn-info p-4 shadow-none" data-toggle="modal" data-target="#splitBillRechnungsignatureModal"><strong>Unterschrift</strong></button>
                            
                            <div id="splitBillRechnungMDiv41" style="width:100%;" class="mt-3 mb-3" >
                                <div class="form-check">
                                    <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="splitBillasveClientForAufRechnungAll()" id="splitBillsaveClForARechnungAll">
                                    <label class="form-check-label pl-2 pt-1" for="splitBillsaveClForARechnungAll"><strong>Speichern Sie diesen Kunden als Stammkunden</strong></label>
                                </div>
                            </div>

                            <button type="button" style="width:100%; margin:0px;" class="mb-2 btn btn-success p-4 shadow-none" id="splitBillfinishPayRechnungAllBtn" onclick="splitBillfinishPayRechnungAll()">
                                <strong>Abschliessen und per E-mail senden</strong>
                            </button>
                        </div>
                    </div>

                    <button type="button" style="width:100%; margin:0px; display:none;" class="mb-1 mt-2 btn btn-danger p-1 shadow-none" id="splitBillcancelClSelectRechnungAllBtn"
                    onclick="splitBillcancelClSelectRechnungAll()"><strong>Deaktivieren Sie den aktuellen Client</strong></button>

                    <div class="input-group" style="width:100%; display:none;" id="splitBillpayAllRechnungCommentClientDiv">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Kommentar:</strong></span>
                        </div>
                        <textarea id="splitBillpayAllRechnungCommentClient" name="splitBillpayAllRechnungCommentClient" class="form-control shadow-none" rows="2" aria-label="Kommentar"></textarea>
                    </div>

                    <button type="button" style="width:100%; margin:0px; display:none;" class="mb-2 mt-4 btn btn-success p-4 shadow-none" id="splitBillfinishPayRechnungAllCLBtn"
                    onclick="splitBillfinishPayRechnungAllCL()"><strong>Abschliessen und per E-mail senden</strong></button>

                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr01">
                        Schreiben Sie bitte den Firmennamen!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr02">
                        Überprüfen Sie die Telefonnummer, bevor Sie fortfahren
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr03">
                        Schreiben Sie zuerst den Namen des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr04">
                        Schreiben Sie zuerst den Nachnamen des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr05">
                        Schreiben Sie zuerst die Straße, PLZ, ORT und Nummer des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr06">
                        Schreiben Sie zuerst das Land des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr07">
                        Schreiben Sie zuerst die E-Mail des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr08">
                        Schreiben Sie zuerst die Telefonnummer des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr09">
                        Wir akzeptieren diese Telefonnummer nicht
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr10">
                        Auf dem Server ist etwas schief gelaufen, bitte versuchen Sie es erneut!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr11">
                        Schreiben Sie bitte den Bestätigungscode
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr12">
                        Dieser Bestätigungscode ist nicht korrekt, versuchen Sie es erneut!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr13">
                        Schreiben Sie die Anzahl der Tage für die Zahlungsfrist
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr14">
                        Die Anzahl der Tage für das Zahlungsziel ist ungültig
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="splitBillProdsRechnungModalErr15">
                        Der Kunde muss seine Unterschrift leisten!
                    </div>
                    
                    <input type="hidden" id="splitBillTableNrRechnung" name="splitBillTableNrRechnung" value="0">

                    <input type="hidden" id="splitBillCrrClientNrRechnung" name="splitBillCrrClientNrRechnung" value="0">
                    <input type="hidden" id="splitBillClientsNrRechnung" name="splitBillClientsNrRechnung" value="0">

                    <input type="hidden" id="splitBillInitiateIdRechnung" name="splitBillInitiateIdRechnung" value="0">

                    <input type="hidden" id="splitBillusedAndExistingClientAll" name="splitBillusedAndExistingClientAll" value="0">
                    <input type="hidden" id="splitBillusedAndExistingClientIdAll" name="splitBillusedAndExistingClientIdAll" value="0">

                    <input type="hidden" id="splitBilltipForOrderCloseRechnung" name="splitBilltipForOrderCloseRechnung" value="0">

                    <input type="hidden" id="splitBillsaveClForARechnungAllVal" name="splitBillsaveClForARechnungAllVal" value="0">

                    <input type="hidden" id="splitBillGCIdARechnungAllVal" name="splitBillGCIdARechnungAllVal" value="0">
                    <input type="hidden" id="splitBillGCAmntARechnungAllVal" name="splitBillGCAmntARechnungAllVal" value="0">
                </form>
            </div>
        </div>
    </div>
</div>


<!-- signature Modal  -->
<div class="modal" id="splitBillRechnungsignatureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:200px;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color:rgb(39,190,175);" class="modal-title">
                    <strong>Schreiben Sie bitte Ihre Unterschrift (<span id="splitBillRechnungsignatureModalAttempt"> Versuch 1/5 </span>)</strong>
                </h5>
                <button type="button" class="close" onclick="closesplitBillRechnungsignatureModal()"  aria-label="Close"> <i class="far fa-times-circle"></i> </button>
            </div>
            <div class="modal-body text-center d-flex flex-wrap justify-content-between">
                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5;" id="splitBillsignaturePad"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="splitBillsignaturePad2"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="splitBillsignaturePad3"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="splitBillsignaturePad4"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="splitBillsignaturePad5"></div>


                <button type="button" id="splitBillclearSignature" class="btn btn-danger mt-2" style="width:45%; z-index:10;">Abbrechen</button>
                <button type="button" id="splitBillsaveSignature" onclick="splitBillclickSaveSignaturePARechnung()" class="btn btn-info mt-2" style="width:45%; z-index:10;"><i class="fas fa-signature mr-2"></i> Sparen</button>
                                   
            </div>
        </div>
    </div>
</div>



<script>
    
    function splitBillPrepPayAufRechnung(clientNr, tableNr, clientsNr){
        $('#splitBillTableNrRechnung').val(tableNr);
        $('#splitBilltipForOrderCloseRechnung').val(parseFloat($('#splitBillModalTippValueClient'+clientNr).html()).toFixed(2));

        $('#splitBillCrrClientNrRechnung').val(clientNr);
        $('#splitBillClientsNrRechnung').val(clientsNr);

        $('#splitBillInitiateIdRechnung').val($('#splitBillnitiateId'+clientNr).val());

        $('#splitBillRechnungModal').modal('show');


        $('#splitBillGCIdARechnungAllVal').val($('#splitBillGCAppliedId'+clientNr).val());
        $('#splitBillGCAmntARechnungAllVal').val($('#splitBillGCAppliedCHFVal'+clientNr).val());
    }

    function closeSplitBillRechnungModal(){
        $("#splitBillRechnungModal").load(location.href+" #splitBillRechnungModal>*","");
    }

    function splitBillselectClRechnungAll(clId){
        $('#splitBillRechnungMDiv1').attr('style','display:none;');
        $('#splitBillRechnungMDiv2').attr('style','display:none;');
        $('#splitBillRechnungMDiv3').attr('style','display:none;');
        $('#splitBillRechnungMDiv4').attr('style','display:none;');

        $('#splitBillcancelClSelectRechnungAllBtn').show(10);
        $('#splitBillpayAllRechnungCommentClientDiv').show(10);
        $('#splitBillfinishPayRechnungAllCLBtn').show(10);
        
        if($('#splitBillusedAndExistingClientIdAll').val() != 0){
            $('#splitBillclientOneRechnungAll'+$('#splitBillusedAndExistingClientIdAll').val()).removeClass('btn-dark');
            $('#splitBillclientOneRechnungAll'+$('#splitBillusedAndExistingClientIdAll').val()).addClass('btn-outline-dark');
        }
        $('#splitBillclientOneRechnungAll'+clId).removeClass('btn-outline-dark');
        $('#splitBillclientOneRechnungAll'+clId).addClass('btn-dark');
       
        $('#splitBillusedAndExistingClientAll').val('1');
        $('#splitBillusedAndExistingClientIdAll').val(clId);
    }



    function splitBillVerifyTelSendNr(){
        if(!$('#splitBillRechnungTelInp').val()){
            if($('#splitBillProdsRechnungModalErr08').is(":hidden")){ $('#splitBillProdsRechnungModalErr08').show(100).delay(4000).hide(100); }
        }else{
            var pNr = $('#splitBillRechnungTelInp').val().replace(/ /g,'');
            
            if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                pNr = '0'+pNr.toString().slice(3);
            }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                pNr = '0'+pNr.toString().slice(2);
            }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                pNr = '0'+pNr.toString().slice(4);
            }
         
            if(pNr.length < 9 || pNr.length > 10){
                if($('#splitBillProdsRechnungModalErr09').is(":hidden")){ $('#splitBillProdsRechnungModalErr09').show(100).delay(4000).hide(100); }
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
                            if($('#splitBillProdsRechnungModalErr10').is(":hidden")){ $('#splitBillProdsRechnungModalErr10').show(100).delay(4000).hide(100); }
                        }else{
                            $('#splitBilltelVerCodeFromSer').val(respo);
                            $('#splitBillPRMDiv1TelVer01').hide(100);
                            $('#splitBillPRMDiv1TelVer02').show(100);
                            $('#splitBillRechnungTelInp').val(pNr);
                            if(pNr == '0763270293' || pNr == '0763251809' || pNr == '0763459941' || pNr == '0763469963'){
                                $('#splitBilltelVerCodeDemoShow').html('<strong>"Testing" Demo code: '+respo+'</strong>');
                                $('#splitBilltelVerCodeDemoShow').show(100);
                                $('#splitBillRechnungTelCodeInp').val(respo);
                            }
                        }
					},
					error: (error) => { console.log(error); }
				});
            }
        }
    }

    function splitBillVerifyTelSendCode(){
        if(!$('#splitBillRechnungTelCodeInp').val()){
            if($('#splitBillProdsRechnungModalErr11').is(":hidden")){ $('#splitBillProdsRechnungModalErr11').show(100).delay(4000).hide(100); }
        }else{
            var codeFromCl = $('#splitBillRechnungTelCodeInp').val();
            if(codeFromCl.length != 6){
                if($('#splitBillProdsRechnungModalErr12').is(":hidden")){ $('#splitBillProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(codeFromCl != $('#splitBilltelVerCodeFromSer').val()){
                if($('#splitBillProdsRechnungModalErr12').is(":hidden")){ $('#splitBillProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(codeFromCl == $('#splitBilltelVerCodeFromSer').val()){
                $('#splitBillPRMDiv1TelVer02').hide(100);
                $('#splitBillProdsRechnungModalSucc01').html('Telefonnummer "'+$('#RechnungTelInp').val()+'" erfolgreich verifiziert');
                $('#splitBillProdsRechnungModalSucc01').show(100);
                $('#splitBilltelVerCodeDemoShow').hide(100);
                $('#splitBilltelVerValidationStatus').val("1");
            }
        }
    }

    function splitBillasveClientForAufRechnungAll(){
        if($("#splitBillsaveClForARechnungAll").is(':checked')){
            $('#splitBillsaveClForARechnungAllVal').val('1');
        }else{
            $('#splitBillsaveClForARechnungAllVal').val('0');
        }
    }

    function splitBillfinishPayRechnungAllCL(){
        // set the variable for payment is Not Selective
        $('#OrQRCodePayIsSelective').val('0');
        $('#splitBillcancelClSelectRechnungAllBtn').prop('disabled', true);
        $('#splitBillpayAllRechnungCommentClientDiv').prop('disabled', true);
        $('#splitBillfinishPayRechnungAllCLBtn').prop('disabled', true);
        $('#splitBillfinishPayRechnungAllCLBtn').html('<img src="storage/gifs/loading2.gif" style="width:30px; height:auto;" alt="">');
        $('#splitBillRechnungForm').submit();
    }

    function splitBillfinishPayRechnungAll(){
        if(!$('#splitBillRechnungFirmaInp').val()){
            if($('#splitBillProdsRechnungModalErr01').is(":hidden")){ $('#splitBillProdsRechnungModalErr01').show(100).delay(4000).hide(100); }
        }else if($('#splitBilltelVerValidationStatus').val() == '0'){
            if($('#splitBillProdsRechnungModalErr02').is(":hidden")){ $('#splitBillProdsRechnungModalErr02').show(100).delay(4000).hide(100); }
        }else if(!$('#splitBillRechnungNameInp').val()){
            if($('#splitBillProdsRechnungModalErr03').is(":hidden")){ $('#splitBillProdsRechnungModalErr03').show(100).delay(4000).hide(100); }
        }else if(!$('#splitBillRechnungVornameInp').val()){
            if($('#splitBillProdsRechnungModalErr04').is(":hidden")){ $('#splitBillProdsRechnungModalErr04').show(100).delay(4000).hide(100); }
        }else if(!$('#splitBillRechnungStrNrInp').val() || !$('#splitBillRechnungPlzOrtInp').val()){
            if($('#splitBillProdsRechnungModalErr05').is(":hidden")){ $('#splitBillProdsRechnungModalErr05').show(100).delay(4000).hide(100); }
        }else if(!$('#splitBillRechnungLandInp').val()){
            if($('#splitBillProdsRechnungModalErr06').is(":hidden")){ $('#splitBillProdsRechnungModalErr06').show(100).delay(4000).hide(100); }
        }else if(!$('#splitBillRechnungEmailInp').val()){
            if($('#splitBillProdsRechnungModalErr07').is(":hidden")){ $('#splitBillProdsRechnungModalErr07').show(100).delay(4000).hide(100); }
        }else if(!$('#splitBillRechnungDaysToPayInp').val()){
            if($('#splitBillProdsRechnungModalErr13').is(":hidden")){ $('#splitBillProdsRechnungModalErr13').show(100).delay(4000).hide(100); }
        }else if($('#splitBillsignaturePrew').attr('src') == ''){
            if($('#splitBillProdsRechnungModalErr15').is(":hidden")){ $('#splitBillProdsRechnungModalErr15').show(100).delay(4000).hide(100); }
        }else{
            var emailInp = $('#splitBillRechnungEmailInp').val();
            var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
            var dayToPay = $('#splitBillRechnungDaysToPayInp').val()
            if(!pattern.test(emailInp)){
                if($('#splitBillProdsRechnungModalErr12').is(":hidden")){ $('#splitBillProdsRechnungModalErr12').show(100).delay(4000).hide(100); }
            }else if(Math.floor(dayToPay) != dayToPay || !$.isNumeric(dayToPay) || dayToPay < 1) {
                if($('#splitBillProdsRechnungModalErr14').is(":hidden")){ $('#splitBillProdsRechnungModalErr14').show(100).delay(4000).hide(100); } 
            }else{
                $('#splitBillfinishPayRechnungAllBtn').prop('disabled', true);
                // success

                // set the variable for payment is Not Selective
                $('#OrQRCodePayIsSelective').val('0');

                $('#splitBillpayAllPRechnungMDiv4BTNs').html('<img src="storage/gifs/loading2.gif" style="width:20%; margin-left:40%;" alt="">');
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
                $('#splitBillRechnungForm').submit();
            }
        }
    }

    function splitBillcancelClSelectRechnungAll(){
        $('#splitBillRechnungMDiv1').attr('style','display:flex;');
        $('#splitBillRechnungMDiv2').attr('style','display:flex;');
        $('#splitBillRechnungMDiv3').attr('style','display:flex;');
        $('#splitBillRechnungMDiv4').attr('style','display:flex;');

        $('#splitBillcancelClSelectRechnungAllBtn').hide(10);
        $('#splitBillpayAllRechnungCommentClientDiv').hide(10);
        $('#splitBillfinishPayRechnungAllCLBtn').hide(10);

        $('#splitBillclientOneRechnungAll'+$('#splitBillusedAndExistingClientIdAll').val()).removeClass('btn-dark');
        $('#splitBillclientOneRechnungAll'+$('#splitBillusedAndExistingClientIdAll').val()).addClass('btn-ouline-dark');

        $('#splitBillusedAndExistingClientAll').val('0');
        $('#splitBillusedAndExistingClientIdAll').val('0');
    }

    var splitBillpadUsed = 0;
    var splitBillsignaturePad = $('#splitBillsignaturePad').signature({ syncField: '#splitBillsignature64', syncFormat: 'PNG', });
    var splitBillsignaturePad2 = $('#splitBillsignaturePad2').signature({ syncField: '#splitBillsignature64', syncFormat: 'PNG', });
    var splitBillsignaturePad3 = $('#splitBillsignaturePad3').signature({ syncField: '#splitBillsignature64', syncFormat: 'PNG', });
    var splitBillsignaturePad4 = $('#splitBillsignaturePad4').signature({ syncField: '#splitBillsignature64', syncFormat: 'PNG', });
    var splitBillsignaturePad5 = $('#splitBillsignaturePad5').signature({ syncField: '#splitBillsignature64', syncFormat: 'PNG', });

    $('#splitBillclearSignature').click(function(e) {
        e.preventDefault();
        splitBillpadUsed++;

        // signaturePad.signature('clear');
        $("#splitBillsignature64").val('');
        $("#splitBillsignaturePrew").attr('src','');

        $('#splitBillRechnungsignatureModalAttempt').html('Versuch '+parseInt(splitBillpadUsed + parseInt(1))+'/5')
        if(splitBillpadUsed == 1){
            $('#splitBillsignaturePad').hide(100);
            $('#splitBillsignaturePad2').show(100);
        }else if(splitBillpadUsed == 2){
            $('#splitBillsignaturePad2').hide(100);
            $('#splitBillsignaturePad3').show(100);
        }else if(splitBillpadUsed == 3){
            $('#splitBillsignaturePad3').hide(100);
            $('#splitBillsignaturePad4').show(100);
        }else if(splitBillpadUsed == 4){
            $('#splitBillsignaturePad4').hide(100);
            $('#splitBillsignaturePad5').show(100);
        }
    });

    function closesplitBillRechnungsignatureModal(){
        $('#splitBillRechnungsignatureModal').modal('hide');
        $('body').addClass('modal-open');
    }

    function splitBillclickSaveSignaturePARechnung(){
        padUsed++;
        var image = new Image();
        image = document.getElementById("splitBillsignature64").value;
        document.getElementById('splitBillsignaturePrew').src = image;
        $('#splitBillRechnungsignatureModalAttempt').html('Versuch '+parseInt(padUsed + parseInt(1))+'/5')
        closesplitBillRechnungsignatureModal();

        if(padUsed == 1){
            $('#splitBillsignaturePad').hide(100);
            $('#splitBillsignaturePad2').show(100);
        }else if(padUsed == 2){
            $('#splitBillsignaturePad2').hide(100);
            $('#splitBillsignaturePad3').show(100);
        }else if(padUsed == 3){
            $('#splitBillsignaturePad3').hide(100);
            $('#splitBillsignaturePad4').show(100);
        }else if(padUsed == 4){
            $('#splitBillsignaturePad4').hide(100);
            $('#splitBillsignaturePad5').show(100);
        }
    }

    


</script>

