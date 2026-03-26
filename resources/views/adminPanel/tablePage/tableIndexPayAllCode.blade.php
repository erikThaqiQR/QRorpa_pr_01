<?php
    use App\rechnungClient;
    use App\UserNotiDtlSet;
    use App\userSoundsList;
    use Illuminate\Support\Facades\Auth;
    
    $sndNotiActv = explode('--||--',Auth::user()->notifySet)[1];
    $usrNotiReg31 = UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',31]])->first();
    if($usrNotiReg31 != Null){
        $usrNotiReg31Sound = userSoundsList::find($usrNotiReg31->setValue);
    }else{
        $usrNotiReg31Sound = userSoundsList::find(1);
    }
?>
@if ($usrNotiReg31 != Null) 
    <script> var hasPayOrdSoundSelected = true; </script>
@else
    <script> var hasPayOrdSoundSelected = false; </script>
@endif
@if ($sndNotiActv == 1) 
    <script> var hasSndNotiActv = true; </script>
@else
    <script> var hasSndNotiActv = false; </script>
@endif
<!-- pay All Modal_phase_01 -->
<div class="modal" id="payAllPhaseOne" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" aria-label="Close" onclick="closePayAllPhaseOne()">
                    <span aria-hidden="false"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body p-1" id="payAllPhaseOneBody">
                <div class="d-flex flex-wrap justify-content-between" id="payAllPhaseOneDiv1">
                    <p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>
                </div>
                <hr>
                <div class="d-flex flex-wrap justify-content-between" id="payAllPhaseOneDiv2">
                    <button id="pAPhOneD2Btn1" style="width:49%; margin:0px;" class="btn btn-success shadow-none" onclick="selectDiv2('Res')"><strong>Im Restaurant</strong></button>
                    <button id="pAPhOneD2Btn2" style="width:49%; margin:0px;" class="btn btn-dark shadow-none" onclick="selectDiv2('House')"><strong>Ausser Haus</strong></button>
                </div>

                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv3">
                    <div style="width: 100%;" class="input-group mb-1">
                        <div style="width: 33%;" class="input-group-prepend text-center">
                            <span style="width: 100%; text-align:center !important;" class="input-group-text" id="basic-addon1">Gutschein<br>Bezeichnung</span>
                        </div>
                        <textarea id="discReasonInp" style="width: 67%; height:60px;" type="text" class="form-control shadow-none" placeholder="Rabattgrund"></textarea>
                    </div>
                    <button id="div3inCashBtn" style="width: 49%; margin:0px;" class="btn btn-outline-dark shadow-none" onclick="callInCash()"><strong>Nach Betrag</strong></button>
                    <button id="div3inPercentageBtn" style="width: 49%; margin:0px;" class="btn btn-outline-dark shadow-none"  onclick="callInPercentage()"><strong>Prozentual</strong></button>

                    <div class="input-group" id="div3InCash" style="display:none; width:100%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"> CHF </span>
                        </div>
                        <input id="div3InCashInp" type="text" class="form-control shadow-none" onkeyup="procesInCah()">
                        <div class="input-group-append">
                            <button class="btn btn-danger shadow-none" style="margin:0px;" type="button" onclick="cancelInCash()">Stornieren</button>
                        </div>
                    </div>
                    <div id="div3InCashError1" class="alert alert-danger text-center" style="display: none; width:100%;">
                        Schreiben Sie einen gültigen Betrag für den Rabatt!
                    </div>

                    <div class="input-group" id="div3InPercentage" style="display:none; width:100%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"> % </span>
                        </div>
                        <input id="div3InPercentageInp" type="text" class="form-control shadow-none" onkeyup="procesInPercentage()">
                        <div class="input-group-append">
                            <button class="btn btn-danger shadow-none" style="margin:0px;" type="button" onclick="cancelInPercentage()">Stornieren</button>
                        </div>
                    </div>
                    <div id="div3InPercentageError1" class="alert alert-danger text-center" style="display: none; width:100%;">
                        Schreiben Sie einen gültigen Betrag für den Rabatt!
                    </div>
                </div>

                <hr style="width: 100%;">
             
                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv6">
                    <p class="text-center" style="width:100%; color:rgb(72,81,87); margin:0;"><strong>Tipp </strong></p>
                    <button onclick="setTipStaf(this.id,'0.5')" id="tipWaiter50" style="width:19.2%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>50</strong> <sup>{{__('global.currencyShowCent')}}</sup></button>
                    <button onclick="setTipStaf(this.id,'1')" id="tipWaiter1" style="width:19%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>1</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button onclick="setTipStaf(this.id,'2')" id="tipWaiter2" style="width:19%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>2</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button onclick="setTipStaf(this.id,'5')" id="tipWaiter5" style="width:19%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>5</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button onclick="setTipStaf(this.id,'10')" id="tipWaiter10" style="width:19.2%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>10</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button id="tipWaiterCancel" onclick="cancelTipStaf(this.id)" style="width:48%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"> Stornieren </button>
                    <button id="tipWaiterCos" style="width:48%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2">
                        <input step="0.5" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostumeTipStaf(this.value)" style="width:70%; border:none;" placeholder="Gesamt mit Tipp"> <sup>{{__('global.currencyShow')}}</sup>
                    </button>
                    <input type="hidden" id="tipForOrderClosePhOne" value="0">
                </div>
                <div id="payAllPhaseOneError4" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Schreiben Sie einen Wert, der größer als die Summe ist!</strong>
                </div>

                <hr style="width: 100%;">

                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv5">
                    <p class="text-center" style="width:100%; color:rgb(72,81,87); margin:0;"><strong>Geschenkkarte beantragen</strong></p>
                    <div style="width:100%;" class="input-group">
                        <input type="text" class="form-control shadow-none" id="payAllgcValidationCodeInput" placeholder="Geschenkkartencode" aria-label="Geschenkkartencode">
                        <div class="input-group-append">
                            <button id="payAllValidateGCBtn" style="margin:0px;" class="btn btn-outline-dark shadow-none" type="button" onclick="payAllValidateGC()"><strong>Bestätigen</strong></button>
                        </div>
                        <div class="input-group-append">
                            <button id="payAllOpenCameraModalBtn" style="margin:0px;" class="btn btn-outline-dark shadow-none" type="button"
                            data-toggle="modal" data-target="#payAllCameraModal">
                                <i class="fa-solid fa-camera"></i>
                            </button>
                        </div>
                    </div>

                    <label id="payAllPhaseOneDiv5_3" style="display:none;" for="basic-url" class="mt-2">Restbetrag <span id="payAllamountLeftChf"></span> CHF</label>
                    <div id="payAllPhaseOneDiv5_4" style="display:none;" class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rabattbetrag</span>
                        </div>
                        <input type="text" id="payAllApplyDiscFromGcInput" class="form-control shadow-none" id="basic-url" aria-describedby="basic-addon3">
                    </div>
                    <button id="payAllPhaseOneDiv5_5" style="width: 49.5%; display:none; margin:4px 0px 4px 0px;" onclick="payAllApplyGCDiscount()" class="btn btn-outline-success shadow-none">Anwenden</button>
                    <button id="payAllPhaseOneDiv5_6" style="width: 49.5%; display:none; margin:4px 0px 4px 0px;" onclick="payAllApplyGCDiscountMax()" class="btn btn-outline-success shadow-none">Maximal anwenden</button>

                    <input type="hidden" id="payAllGCAppliedId" value="0">
                    <input type="hidden" id="payAllGCAppliedCHFVal" value="0">
                </div>

                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv7">
                    <p class="text-center" style="width:100%; color:rgb(72,81,87); margin:0;"><strong>Kundenangaben:</strong></p>
                    <textarea class="form-control shadow-none" id="payAllPhaseOne400PComment" placeholder="Name Vorname&#10;Adresse&#10;PLZ Ort" rows="3"></textarea>
                </div>

                <div id="payAllPhaseOneError51" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Schreiben Sie zuerst den Code!</strong>
                </div>
                <div id="payAllPhaseOneError52" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Ihre Geschenkkarte wurde nicht gefunden!</strong>
                </div>
                <div id="payAllPhaseOneError53" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Diese Geschenkkarte ist nicht mehr gültig/Ausgeben!</strong>
                </div>
                <div id="payAllPhaseOneError54" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Tragen Sie zunächst einen gültigen Anwendungswert ein!</strong>
                </div>
                <div id="payAllPhaseOneError55" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Etwas ist schiefgelaufen. Bitte neu laden und erneut versuchen!</strong>
                </div>
                <div id="payAllPhaseOneError56" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Dieser Betrag ist zu hoch für diese Geschenkkarte!</strong>
                </div>
                <div id="payAllPhaseOneError57" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Versuchen Sie es mit einem kleineren Wert, dieser ist zu viel für die aktuelle Rechnung!</strong>
                </div>
                <div id="payAllPhaseOneError58" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Diese Geschenkkarte ist noch nicht bezahlt. Bezahlen Sie sie, bevor Sie sie verwenden!</strong>
                </div>
                <div id="payAllPhaseOneError59" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Diese Geschenkkarte ist abgelaufen!</strong>
                </div>
                <div id="payAllPhaseOneError510" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Diese Geschenkkarte ist nicht von diesem Restaurant und kann hier nicht eingelöst werden!</strong>
                </div>
                <div id="payAllPhaseOneError511" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Die Geschenkkarte, die Sie verwenden wollten, ist ungültig</strong>
                </div>
                <div id="payAllPhaseOneError512" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Die Geschenkkarte, die Sie anwenden wollten, ist noch nicht verkauft (aktiv)!</strong>
                </div>

                <hr style="width: 100%;">

                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv4">
                    <p style="width: 100%;" class="text-center"><strong>Zahlungeart</strong></p>
                    <button id="payAllBtn1" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPayAllProdsCash()"><strong>Bar</strong></button>
                    <button id="payAllBtn2" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="payAllProds('none')"><strong>Karte</strong></button>
                    @if (Auth::user()->sFor == 40)
                    <button id="payAllBtn3" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" disabled><strong>Online</strong></button>
                    @else
                    <button id="payAllBtn3" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepOnlinePayPayAll()"><strong>Online</strong></button>
                    @endif
                    
                    <button id="payAllBtn4" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPayAllProdsRechnung()"><strong>Auf Rechnung</strong></button>
                </div>
                <div id="payAllPhaseOneError1" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Wählen Sie zuerst den Diensttyp (Im Restaurant oder Ausser Haus)</strong>
                </div>
                <div id="payAllPhaseOneError2" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Schreiben Sie einen Grund für diesen Rabatt!</strong>
                </div>
                <div id="payAllPhaseOneError3" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Aktualisieren Sie die Seite, etwas stimmt nicht mit dem Programm!</strong>
                </div>
                <div id="payAllPhaseOnePayAtPOS" class="alert alert-info text-center mt-1" style="display:none; width:100%;">
                    <strong>Schließen Sie die Zahlung am POS-Terminal ab!</strong>
                    <br>
                    <strong style="color:red">Schliessed Sie das Fänschter nöd und laded Sie d'Site nöd neu, susch wird d'POS-Transaktion abbroche.</strong>
                </div>
                
                <input type="hidden" id="payAllTableNr" value="0">

                <input type="hidden" id="cashDiscountInp" value="0">
                <input type="hidden" id="percentageDiscountInp" value="0">
                
            </div>

        </div>
    </div>
</div>


<!-- payAllProdsCashModal Modal -->

<div class="modal" id="payAllProdsCashModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Barzahlung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePayAllProdsCashModal()">
                    <span aria-hidden="false"><i class="far fa-times-circle"></i></span>
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
                    <button id="payAllProdsCashBtn" style="width:49%; margin:0px;" class="btn btn-success" onclick="payAllProdsCash()"><strong>Abschliessen</strong></button>
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
                <input type="hidden" id="resCommentOrderCloseCash" value="none">
                
            </div>
        </div>
    </div>
</div>




<!-- payAllProdsCashModal Modal -->
<div class="modal" id="payAllProdsOnlineModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Online (für den Kunden)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePayAllProdsOnlineModal()">
                    <span aria-hidden="false"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="payAllProdsOnlineBody">
                <img id="payAllProdsOnlineQRCode" src="" style="width:100%; height:auto;" alt="">

                <hr>
                <p class="text-center mt-2" style="font-weight: bold;">
                    Der Kunde kann diesen QR-Code scannen und zur Online-Zahlungsseite für diese Bestellung weitergehen
                </p>
            </div>
        </div>
    </div>
</div>




<!-- payAllProdsRechnungModal Modal (payment through email)-->
<div class="modal" id="payAllProdsRechnungModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><strong>Kauf auf Rechnung</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePayAllProdsRechnungModal()">
                    <span aria-hidden="false"><i class="far fa-times-circle"></i></span>
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

                <form action="{{ route('admin.payAlladdRechnungPay') }}" id="payAllProdsRechnungForm" method="post" enctype="multipart/form-data">
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
                    
                    <input type="hidden" name="payAllTableNrRechnung" id="payAllTableNrRechnung" value="0">
                    <input type="hidden" name="discReasonRechnung" id="discReasonRechnung" value="empty">

                    <input type="hidden" name="cashDiscountInpRechnung" id="cashDiscountInpRechnung" value="0">
                    <input type="hidden" name="percentageDiscountInpRechnung" id="percentageDiscountInpRechnung" value="0">
                    <input type="hidden" name="tvshAmProdsPerventageRechnung" id="tvshAmProdsPerventageRechnung" value="0">

                    <input type="hidden" id="saveClForARechnungAllVal" name="saveClForARechnungAllVal" value="0">
                    <input type="hidden" id="usedAndExistingClientAll" name="usedAndExistingClientAll" value="0">
                    <input type="hidden" id="usedAndExistingClientIdAll" name="usedAndExistingClientIdAll" value="0">

                    <input type="hidden" id="tipForOrderCloseRechnung" name="tipForOrderCloseRechnung" value="0">

                    <input type="hidden" id="payAllRechnungGiftCardId" name="payAllRechnungGiftCardId" value="0">
                    <input type="hidden" id="payAllRechnungGiftCardAmount" name="payAllRechnungGiftCardAmount" value="0">

                    <input type="hidden" id="payAllRechnungResComment" name="payAllRechnungResComment" value="none">
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
                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5;" id="signaturePad"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="signaturePad2"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="signaturePad3"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="signaturePad4"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="signaturePad5"></div>


                <button type="button" id="clearSignature" class="btn btn-danger mt-2" style="width:45%; z-index:10;">Abbrechen</button>
                <button type="button" id="saveSignature" onclick="clickSaveSignaturePARechnung()" class="btn btn-info mt-2" style="width:45%; z-index:10;"><i class="fas fa-signature mr-2"></i> Sparen</button>
                                   
            </div>
        </div>
    </div>
</div>

<div id="payAllCameraModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scannen Sie den QR-Code der Geschenkkarte</h5>
                <button type="button" class="close"onclick="closePayAllCameraModal()">
                  <span aria-hidden="false"><i class="fa-regular fa-circle-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="video">
                  <video muted playsinline id="payALL-qr-video" width="100%"></video>
                </div>
            </div>  
        </div>
    </div>
</div>



@include('adminPanel.tablePage.tableIndexPayAllScript')
