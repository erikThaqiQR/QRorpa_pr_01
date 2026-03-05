<?php
    use App\rechnungClient;
?>


<!-- pay GC CASH prepare Modal -->

<div class="modal" id="payGCCashPrepModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Barzahlung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePayGCCashPrepModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="payGCCashPrepBody">
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
                    <button id="payAllProdsCashBtn" style="width:49%; margin:0px;" class="btn btn-success" onclick="payGCCashPay()"><strong>Abschliessen</strong></button>
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

                <input type="hidden" id="payAllTableNrCash" value="0">
                <input type="hidden" id="discReasonCash" value="empty">

                <input type="hidden" id="cashDiscountInpCash" value="0">
                <input type="hidden" id="percentageDiscountInpCash" value="0">
                <input type="hidden" id="tvshAmProdsPerventageCash" value="0">
                <input type="hidden" id="tipForOrderCloseCash" value="0">
                
            </div>
        </div>
    </div>
</div>




















<!-- payAllProdsRechnungModal Modal (payment through email)-->
<div class="modal" id="payGCAufRechnungModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Kauf auf Rechnung</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePayGCAufRechnungModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
           
            <div id="clientsRechnungList" style="max-height: 200px; width:100%; overflow-y: scroll;">
                @foreach (rechnungClient::where('toRes',Auth::user()->sFor)->get() as $clOne)
                    <button class="btn btn-outline-dark shadow-none" onclick="selectClRechnung('{{$clOne->id}}')"
                    style="text-align:left; width:96%; margin:3px 0px 3px 4%;" id="clientOneRechnung{{$clOne->id}}">
                        <strong>{{$clOne->name}} {{$clOne->lastname}} - "{{$clOne->firmaName}}" - {{$clOne->street}} {{$clOne->plzort}}</strong>
                    </button>
                @endforeach
            </div>
           
            <div class="modal-body" id="payGCAufRechnungModalBody">

                <form action="{{ route('giftCard.giftCardRegAufRechnungPay') }}" id="payGCRechnungForm" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div id="payAllPRechnungMDiv1" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <input style="width:100%" name="RechnungFirmaInp" id="RechnungFirmaInp" class="form-control shadow-none mb-1" type="text" placeholder="Firma">
                        <div id="payAllPRMDiv1TelVer01" style="width:100%;" class="input-group mb-1">
                            <input name="RechnungTelInp" id="RechnungTelInp" class="form-control shadow-none" type="text" placeholder="Tel. Nummer">
                            <div class="input-group-append">
                                <button id="RechnungTelVerBtn1" style="margin:0px;" class="btn btn-success shadow-none" onclick="payGCRechnungVerifyTelSendNr()" type="button">Bestätigen</button>
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

                            <button type="button" style="width:100%; margin:0px;" class="mb-2 btn btn-success p-4 shadow-none" id="finishPayRechnungAllBtn" onclick="finishPayRechnungAll()">
                                <strong>Abschliessen und per E-mail senden</strong>
                            </button>
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
                    <input type="hidden" id="saveClForARechnungAllVal" name="saveClForARechnungAllVal" value="0">
                    <input type="hidden" id="usedAndExistingClientAll" name="usedAndExistingClientAll" value="0">
                    <input type="hidden" id="usedAndExistingClientIdAll" name="usedAndExistingClientIdAll" value="0">

                    <input type="hidden" id="rechnungPayExpiringDate" name="rechnungPayExpiringDate" value="0">
                    
                    <input type="hidden" id="payRechnungClName" name="payRechnungClName" value="empty">
                    <input type="hidden" id="payRechnungClLastname" name="payRechnungClLastname" value="empty">
                    <input type="hidden" id="payRechnungClEmail" name="payRechnungClEmail" value="empty">
                    <input type="hidden" id="payRechnungClPhoneNr" name="payRechnungClPhoneNr" value="empty">
                    <input type="hidden" id="payRechnungValueInCHF" name="payRechnungValueInCHF" value="0">
                    <input type="hidden" id="payRechnungGCType" name="payRechnungGCType" value="chf">
                    <input type="hidden" id="payRechnungGCToSellCon" name="payRechnungGCToSellCon" value="0">
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



<!-- expired and fully used Gift cards Modals -->
<div class="modal" id="expiredAndFullyUsedGCModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Abgelaufene und vollständig genutzte Geschenkkarten</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeExpiredAndFullyUsedGCModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body text-center d-flex flex-wrap justify-content-start" id="expiredAndFullyUsedGCBody">
             
            </div>
        </div>
    </div>
</div>


<!-- deleted Gift cards Modals -->
<div class="modal" id="deletedGCModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alle gelöschten Geschenkkarten werden hier angezeigt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeDeletedGCModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body text-center d-flex flex-wrap justify-content-start" id="deletedGCBody">
             
            </div>
        </div>
    </div>
</div>
