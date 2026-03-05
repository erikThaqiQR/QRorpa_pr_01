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

<!-- pay Selected Modal_phase_01 -->
<div class="modal" id="payAllPhaseOneSel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelSel"></h5>
                <button type="button" class="close" aria-label="Close" onclick="closePaySelPhaseOneSel()">
                    <span aria-hidden="false"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body p-1" id="payAllPhaseOneBodySel">
                <div class="d-flex flex-wrap justify-content-between" id="payAllPhaseOneDiv1Sel">
                    <p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>
                </div>
                <hr>
                <div class="d-flex flex-wrap justify-content-between" id="payAllPhaseOneDiv2Sel">
                    <button id="pAPhOneD2Btn1Sel" style="width:49%; margin:0px;" class="btn btn-success shadow-none" onclick="selectDiv2Sel('Res')"><strong>Im Restaurant</strong></button>
                    <button id="pAPhOneD2Btn2Sel" style="width:49%; margin:0px;" class="btn btn-dark shadow-none" onclick="selectDiv2Sel('House')"><strong>Ausser Haus</strong></button>
                </div>
                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv3Sel">
                    <div style="width: 100%;" class="input-group mb-1">
                        <div style="width: 33%;" class="input-group-prepend text-center">
                            <span style="width: 100%; text-align:center !important;" class="input-group-text" id="basic-addon1Sel">Gutschein<br>Bezeichnung</span>
                        </div>
                        <textarea id="discReasonInpSel" style="width: 67%; height:60px;" type="text" class="form-control shadow-none" placeholder="Rabattgrund"></textarea>
                    </div>
                    <button id="div3inCashBtnSel" style="width: 49%; margin:0px;" class="btn btn-outline-dark shadow-none" onclick="callInCashSel()"><strong>Nach Betrag</strong></button>
                    <button id="div3inPercentageBtnSel" style="width: 49%; margin:0px;" class="btn btn-outline-dark shadow-none"  onclick="callInPercentageSel()"><strong>Prozentual</strong></button>

                    <div class="input-group" id="div3InCashSel" style="display:none; width:100%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1Sel"> CHF </span>
                        </div>
                        <input id="div3InCashInpSel" type="text" class="form-control shadow-none" onkeyup="procesInCahSel()">
                        <div class="input-group-append">
                            <button class="btn btn-danger shadow-none" style="margin:0px;" type="button" onclick="cancelInCashSel()">Stornieren</button>
                        </div>
                    </div>
                    <div id="div3InCashError1Sel" class="alert alert-danger text-center" style="display: none; width:100%;">
                        Schreiben Sie einen gültigen Betrag für den Rabatt!
                    </div>

                    <div class="input-group" id="div3InPercentageSel" style="display:none; width:100%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1Sel"> % </span>
                        </div>
                        <input id="div3InPercentageInpSel" type="text" class="form-control shadow-none" onkeyup="procesInPercentageSel()">
                        <div class="input-group-append">
                            <button class="btn btn-danger shadow-none" style="margin:0px;" type="button" onclick="cancelInPercentageSel()">Stornieren</button>
                        </div>
                    </div>
                    <div id="div3InPercentageError1Sel" class="alert alert-danger text-center" style="display: none; width:100%;">
                        Schreiben Sie einen gültigen Betrag für den Rabatt!
                    </div>
                </div>

                <hr style="width: 100%;">

                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv6Sel">
                    <p class="text-center" style="width:100%; color:rgb(72,81,87); margin:0;"><strong>Tipp</strong></p>
                    <button onclick="setTipStafSel(this.id,'0.5')" id="tipWaiter50Sel" style="width:19.2%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>50</strong> <sup>{{__('global.currencyShowCent')}}</sup></button>
                    <button onclick="setTipStafSel(this.id,'1')" id="tipWaiter1Sel" style="width:19%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>1</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button onclick="setTipStafSel(this.id,'2')" id="tipWaiter2Sel" style="width:19%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>2</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button onclick="setTipStafSel(this.id,'5')" id="tipWaiter5Sel" style="width:19%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>5</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button onclick="setTipStafSel(this.id,'10')" id="tipWaiter10Sel" style="width:19.2%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"><strong>10</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button id="tipWaiterCancelSel" onclick="cancelTipStafSel(this.id)" style="width:48%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2"> Stornieren </button>
                    <button id="tipWaiterCosSel" style="width:48%; margin:0px;" type="button" class="btn btn-outline-dark shadow-none mt-2">
                        <input step="0.5" min="0" id="tipWaiterCosValSel" type="number" onkeyup="setCostumeTipStafSel(this.value)" style="width:70%; border:none;" placeholder="Gesamt mit Tipp"> <sup>{{__('global.currencyShow')}}</sup>
                    </button>
                    <input type="hidden" id="tipForOrderClosePhOneSel" value="0">
                </div>
                <div id="payAllPhaseOneError4Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Schreiben Sie einen Wert, der größer als die Summe ist!</strong>
                </div>

                <hr style="width: 100%;">

                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv5Sel">
                    <p class="text-center" style="width:100%; color:rgb(72,81,87); margin:0;"><strong>Geschenkkarte beantragen</strong></p>
                    <div style="width:100%;" class="input-group">
                        <input type="text" class="form-control shadow-none" id="payAllgcValidationCodeInputSel" placeholder="Geschenkkartencode" aria-label="Geschenkkartencode">
                        <div class="input-group-append">
                            <button id="payAllValidateGCBtnSel" style="margin:0px;" class="btn btn-outline-dark shadow-none" type="button" onclick="payAllValidateGCSel()"><strong>Bestätigen</strong></button>
                        </div>
                        <div class="input-group-append">
                            <button id="payAllOpenCameraModalBtnSel" style="margin:0px;" class="btn btn-outline-dark shadow-none" type="button"
                            data-toggle="modal" data-target="#payAllCameraModalSel">
                                <i class="fa-solid fa-camera"></i>
                            </button>
                        </div>
                    </div>

                    <label id="payAllPhaseOneDivSel5_3" style="display:none;" for="basic-url" class="mt-2">Restbetrag <span id="payAllamountLeftChfSel"></span> CHF</label>
                    <div id="payAllPhaseOneDivSel5_4" style="display:none;" class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rabattbetrag</span>
                        </div>
                        <input type="text" id="payAllApplyDiscFromGcInputSel" class="form-control shadow-none" id="basic-url" aria-describedby="basic-addon3">
                    </div>
                    <button id="payAllPhaseOneDivSel5_5" style="width: 49.5%; display:none; margin:4px 0px 4px 0px;" onclick="payAllApplyGCDiscountSel()" class="btn btn-outline-success shadow-none">Anwenden</button>
                    <button id="payAllPhaseOneDivSel5_6" style="width: 49.5%; display:none; margin:4px 0px 4px 0px;" onclick="payAllApplyGCDiscountSelMax()" class="btn btn-outline-success shadow-none">Maximal anwenden</button>

                    <input type="hidden" id="payAllGCAppliedIdSel" value="0">
                    <input type="hidden" id="payAllGCAppliedCHFValSel" value="0">
                </div>

                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv7Sel">
                    <p class="text-center" style="width:100%; color:rgb(72,81,87); margin:0;"><strong>Kundenangaben:</strong></p>
                    <textarea class="form-control shadow-none" id="payAllPhaseOne400PCommentSel" placeholder="Name Vorname&#10;Adresse&#10;PLZ Ort" rows="3"></textarea>
                </div>

                <div id="payAllPhaseOneError51Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Schreiben Sie zuerst den Code!</strong>
                </div>
                <div id="payAllPhaseOneError52Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Ihre Geschenkkarte wurde nicht gefunden!</strong>
                </div>
                <div id="payAllPhaseOneError53Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Diese Geschenkkarte ist nicht mehr gültig/Ausgeben!</strong>
                </div>
                <div id="payAllPhaseOneError54Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Tragen Sie zunächst einen gültigen Anwendungswert ein!</strong>
                </div>
                <div id="payAllPhaseOneError55Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Etwas ist schiefgelaufen. Bitte neu laden und erneut versuchen!</strong>
                </div>
                <div id="payAllPhaseOneError56Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Dieser Betrag ist zu hoch für diese Geschenkkarte!</strong>
                </div>
                <div id="payAllPhaseOneError57Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Versuchen Sie es mit einem kleineren Wert, dieser ist zu viel für die aktuelle Rechnung!</strong>
                </div>
                <div id="payAllPhaseOneError58Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Diese Geschenkkarte ist noch nicht bezahlt. Bezahlen Sie sie, bevor Sie sie verwenden!</strong>
                </div>
                <div id="payAllPhaseOneError59Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Diese Geschenkkarte ist abgelaufen!</strong>
                </div>
                <div id="payAllPhaseOneError510Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Diese Geschenkkarte ist nicht von diesem Restaurant und kann hier nicht eingelöst werden!</strong>
                </div>
                <div id="payAllPhaseOneError511Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Die Geschenkkarte, die Sie verwenden wollten, ist ungültig</strong>
                </div>
                <div id="payAllPhaseOneError512Sel" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                    <strong>Die Geschenkkarte, die Sie anwenden wollten, ist noch nicht verkauft (aktiv)!</strong>
                </div>

                <hr style="width: 100%;">

                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv4Sel">
                    <p style="width: 100%;" class="text-center"><strong>Zahlungeart</strong></p>
                    <button id="payAllBtn1Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPaySelProdsCash()"><strong>Bar</strong></button>
                    <button id="payAllBtn2Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="paySelProdsSel('none')"><strong>Karte</strong></button>
                    @if (Auth::user()->sFor == 40)
                    <button id="payAllBtn3Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" disabled><strong>Online</strong></button>
                    @else
                    <button id="payAllBtn3Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepOnlinePayPaySel()"><strong>Online</strong></button>
                    @endif
                    <button id="payAllBtn4Sel" type="button" style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPaySelProdsRechnung()"><strong>Auf Rechnung</strong></button>
                </div>
                <div id="payAllPhaseOneError1Sel" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Wählen Sie zuerst den Diensttyp (Im Restaurant oder Ausser Haus)</strong>
                </div>
                <div id="payAllPhaseOneError2Sel" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Schreiben Sie einen Grund für diesen Rabatt!</strong>
                </div>
                <div id="payAllPhaseOneError3Sel" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Aktualisieren Sie die Seite, etwas stimmt nicht mit dem Programm!</strong>
                </div>
                <div id="payAllPhaseOnePayAtPOSSel" class="alert alert-info text-center mt-1" style="display:none; width:100%;">
                    <strong>Schließen Sie die Zahlung am POS-Terminal ab!</strong>
                </div>
                
                <input type="hidden" id="payAllTableNrSel" value="0">

                <input type="hidden" id="cashDiscountInpSel" value="0">
                <input type="hidden" id="percentageDiscountInpSel" value="0">

                <input type="hidden" id="prodsSelPaySel" value="0">

            </div>
        </div>
    </div>
</div>

<!-- payAllProdsCashModal Modal -->

<div class="modal" id="paySelProdsOnlineModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Online (für den Kunden)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePaySelProdsOnlineModal()">
                    <span aria-hidden="false"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="paySelProdsOnlineBody">
                <img id="paySelProdsOnlineQRCode" src="" style="width:100%; height:auto;" alt="">

                <hr>
                <p class="text-center mt-2" style="font-weight: bold;">
                    Der Kunde kann diesen QR-Code scannen und zur Online-Zahlungsseite für diese Bestellung weitergehen
                </p>
            </div>
        </div>
    </div>
</div>


<!-- paySelProdsCashModal Modal -->

<div class="modal fade" id="paySelProdsCashModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitleSel">Barzahlung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePaySelProdsCashModal()">
                    <span aria-hidden="false"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="paySelProdsCashBody">
                <button style="width:100%; margin:0px;" class="btn btn-danger mb-2" onclick="paySelProdsCashDel()">Löschen</button>
                <div id="paySelPCMDiv1" class="d-flex flex-wrap justify-content-between">
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(204,153,0,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtnSel" onclick="addClPaySel('0.05')"><strong>5 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtnSel" onclick="addClPaySel('0.1')"><strong>10 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtnSel" onclick="addClPaySel('0.2')"><strong>20 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtnSel" onclick="addClPaySel('0.5')"><strong>50 <br> Rp.</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtnSel" onclick="addClPaySel('1')"><strong>CHF <br> 1</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtnSel" onclick="addClPaySel('2')"><strong>CHF <br> 2</strong></button>
                    <button style="width: 100px; height:100px; border-radius:50%; margin:0px; background-color:rgba(208,206,207,255); font-size:1.2rem; color:white;" class="shadow-none btn mb-2 currBtnSel" onclick="addClPaySel('5')"><strong>CHF <br> 5</strong></button>
                </div>
                <div id="paySelPCMDiv2" class="d-flex flex-wrap justify-content-between mt-2">
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(255,192,0,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtnSel" onclick="addClPaySel('10')"><strong>CHF 10</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(254,0,0,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtnSel" onclick="addClPaySel('20')"><strong>CHF 20</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(0,175,80,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtnSel" onclick="addClPaySel('50')"><strong>CHF 50</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(45,117,182,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtnSel" onclick="addClPaySel('100')"><strong>CHF 100</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(204,153,0,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtnSel" onclick="addClPaySel('200')"><strong>CHF 200</strong></button>
                    <button style="width: 33%; margin:0px; color:white; background-color:rgba(112,48,160,255); font-size:1.2rem;" class="shadow-none btn mb-2 pt-3 pb-3 currBtnSel" onclick="addClPaySel('1000')"><strong>CHF 1000</strong></button>
                </div>
                <button style="width:100%; margin:0px;" class="btn btn-info mt-2 mb-2" id="showPaySelPCMDiv3Btn" onclick="showPaySelPCMDiv3()">Betrag eingeben</button>
                <div id="paySelPCMDiv3" class="flex-wrap justify-content-between mt-2" style="display:none;">
                    <div style="width: 100%;" class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1Sel">CHF</span>
                        </div>
                        <input type="text" class="form-control shadow-none" id="paySelPCMDiv3Inp" onkeyup="newValGivByClSel()">
                        <div class="input-group-append">
                            <button onclick="cancelPaySelPCMDiv3()" class="btn btn-danger" style="margin:0px;" type="button">Absagen</button>
                        </div>
                    </div>
                    <div style="display:none; width:100%" class="alert alert-danger text-center mt-1" id="paySelPCMDiv3Err1">
                        Schreiben Sie einen gültigen Betrag!
                    </div>
                </div>
                <div id="paySelPCMDiv4" class="d-flex flex-wrap justify-content-between mt-2">
                    <div style="width:49%">
                        <p style="margin-bottom: 5px;">Zu bezahlen: CHF <span id="amToPaySel"></span></p>
                        <p style="margin-bottom: 5px;">Erhalten: CHF <span id="amByClientSel">0.00</span></p>
                        <p style="margin-bottom: 5px;"><strong>Rückgeld: CHF <span id="amClientReturnSel">--.--</span></strong></p>
                    </div>
                    <button id="paySelProdsCashBtn" style="width:49%; margin:0px;" class="btn btn-success" onclick="paySelProdsCash()"><strong>Abschliessen</strong></button>
                </div>
                <div id="paySelPhaseOneCashError1" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Wählen Sie zuerst den Diensttyp (Im Restaurant oder Ausser Haus)</strong>
                </div>
                <div id="paySelPhaseOneCashError2" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Schreiben Sie einen Grund für diesen Rabatt!</strong>
                </div>
                <div id="paySelPhaseOneCashError3" class="alert alert-danger text-center mt-1" style="display:none;">
                    <strong>Aktualisieren Sie die Seite, etwas stimmt nicht mit dem Programm!</strong>
                </div>

                <input type="hidden" id="paySelTableNrCash" value="0">
                <input type="hidden" id="discReasonCashSel" value="empty">

                <input type="hidden" id="cashDiscountInpCashSel" value="0">
                <input type="hidden" id="percentageDiscountInpCashSel" value="0">
                <input type="hidden" id="tvshAmProdsPerventageCashSel" value="0">
                <input type="hidden" id="tipForOrderCloseCashSel" value="0">
                <input type="hidden" id="resCommentOrderCloseCashSel" value="none">
                
            </div>
        </div>
    </div>
</div>




<!-- paySelProdsRechnungModal Modal (payment through email)-->
<div class="modal fade" id="paySelProdsRechnungModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitleSel"><strong>Kauf auf Rechnung</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePaySelProdsRechnungModal()">
                    <span aria-hidden="false"><i class="far fa-times-circle"></i></span>
                </button>
            </div>

            <div id="clientsRechnungSelList" style="max-height: 200px; width:100%; overflow-y: scroll;">
                @foreach (rechnungClient::where('toRes',Auth::user()->sFor)->get() as $clOne)
                    <button class="btn btn-outline-dark shadow-none" onclick="selectClRechnungSel('{{$clOne->id}}')"
                    style="text-align:left; width:96%; margin:3px 0px 3px 4%;" id="clientOneRechnungSel{{$clOne->id}}">
                        <strong>{{$clOne->name}} {{$clOne->lastname}} - "{{$clOne->firmaName}}" - {{$clOne->street}} {{$clOne->plzort}}</strong>
                    </button>
                @endforeach
            </div>

            <div class="modal-body" id="paySelProdsRechnungBody">
                <form action="{{ route('admin.paySeladdRechnungPay') }}" id="paySelProdsRechnungForm" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div id="paySelPRechnungMDiv1" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <input style="width:100%" name="RechnungFirmaInpSel" id="RechnungFirmaInpSel" class="form-control shadow-none mb-1" type="text" placeholder="Firma">
                        <div id="paySelPRMDiv1TelVer01" style="width:100%;" class="input-group mb-1">
                            <input name="RechnungTelInpSel" id="RechnungTelInpSel" class="form-control shadow-none" type="text" placeholder="Tel. Nummer">
                            <div class="input-group-append">
                                <button id="RechnungTelVerBtn1Sel" style="margin:0px;" class="btn btn-success shadow-none" onclick="paySelVerifyTelSendNr()" type="button">Bestätigen</button>
                            </div>
                        </div>
                        <input type="hidden" name="telVerCodeFromSerSel" id="telVerCodeFromSerSel" value="0">
                        <input type="hidden" name="telVerValidationStatusSel" id="telVerValidationStatusSel" value="0">
                        <div class="alert alert-success text-center mb-1" style="display:none; width:100%; margin:0px;" id="paySelProdsRechnungModalSucc01">
                            Telefonnummer erfolgreich verifiziert
                        </div>
                        <p id="telVerCodeDemoShowSel" style="display:none;"></p>
                        <div id="paySelPRMDiv1TelVer02" style="width:100%; display:none;" class="input-group mb-1">
                            <input name="RechnungTelCodeInpSel" id="RechnungTelCodeInpSel" class="form-control shadow-none" type="text" placeholder="Verifizierungs-Schlüssel">
                            <div class="input-group-append">
                                <button id="RechnungTelVerBtn2Sel" style="margin:0px;" class="btn btn-success shadow-none" onclick="paySelVerifyTelSendCode()" type="button">Senden</button>
                            </div>
                        </div>

                        <input style="width:49%" name="RechnungNameInpSel" id="RechnungNameInpSel" class="form-control shadow-none mb-1" type="text" placeholder="Name">
                        <input style="width:49%" name="RechnungVornameInpSel" id="RechnungVornameInpSel" class="form-control shadow-none mb-1" type="text" placeholder="Vorname">

                        <input style="width:49%" name="RechnungStrNrInpSel" id="RechnungStrNrInpSel" class="form-control shadow-none mb-1" type="text" placeholder="Strasse/Nr.">
                        <input style="width:49%" name="RechnungPlzOrtInpSel" id="RechnungPlzOrtInpSel" class="form-control shadow-none mb-1" type="text" placeholder="PLZ/ORT">

                        <input style="width:49%" name="RechnungLandInpSel" id="RechnungLandInpSel" class="form-control shadow-none mb-1" type="text" placeholder="Land">
                        <input style="width:49%" name="RechnungEmailInpSel" id="RechnungEmailInpSel" class="form-control shadow-none mb-1" type="text" placeholder="E-mail">
                    
                    </div>

                    <div id="paySelPRechnungMDiv2" style="display:flex;" class="input-group mt-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Zalungsfrist: </span>
                        </div>
                        <input type="text" name="RechnungDaysToPayInpSel" id="RechnungDaysToPayInpSel" class="form-control shadow-none">
                        <div class="input-group-append">
                            <span class="input-group-text">Tage</span>
                        </div>
                    </div>

                    <div id="paySelPRechnungMDiv3" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <div style="width:100%">
                            <p style="margin-bottom: 5px; font-size:1.3rem;"><strong>Zu Bezahlen: CHF <span id="amToPayRechnungSel"></span></strong></p>
                        </div>
                        <div class="input-group" style="width:100%">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><strong>Kommentar:</strong></span>
                            </div>
                            <textarea id="paySelRechnungComment" name="paySelRechnungComment" class="form-control shadow-none" rows="2" aria-label="Kommentar"></textarea>
                        </div>
                    </div>

                    <div id="paySelPRechnungMDiv4" style="display:flex;" class="flex-wrap justify-content-between mt-2">
                        <img src="" id="signaturePrewSel" alt="Unterschreiben und speichern" style="width: 100%; height: 160px;">
                        <textarea id="signature64Sel" name="signedSel" style="display: none;" required></textarea>
                        <div style="width:100%;" id="paySelPRechnungMDiv4BTNs" class="d-flex flex-wrap justify-content-between">
                            <button type="button" style="width:100%; margin:0px;" class="mb-2 btn btn-info p-4 shadow-none" data-toggle="modal" data-target="#paySelRechnungsignatureModal"><strong>Unterschrift</strong></button>
                            
                            <div id="paySelPRechnungMDiv41" style="width:100%;" class="mt-3 mb-3" >
                                <div class="form-check">
                                    <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="asveClientForAufRechnungSel()" id="saveClForARechnungSel">
                                    <label class="form-check-label pl-2 pt-1" for="saveClForARechnungSel"><strong>Speichern Sie diesen Kunden als Stammkunden</strong></label>
                                </div>
                            </div>
                            
                            <button type="button" style="width:100%; margin:0px;" class="mb-2 btn btn-success p-4 shadow-none" id="finishPayRechnungSelBtn" onclick="finishPayRechnungSel()">
                                <strong>Abschliessen und per E-mail senden</strong>
                            </button>
                        </div>
                    </div>

                    <button type="button" style="width:100%; margin:0px; display:none;" class="mb-1 mt-2 btn btn-danger p-1 shadow-none" id="cancelClSelectRechnungSelBtn"
                    onclick="cancelClSelectRechnungSel()"><strong>Deaktivieren Sie den aktuellen Client</strong></button>

                    <div class="input-group" style="width:100%; display:none;" id="paySelRechnungCommentClientDiv">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Kommentar:</strong></span>
                        </div>
                        <textarea id="paySelRechnungCommentClient" name="paySelRechnungCommentClient" class="form-control shadow-none" rows="2" aria-label="Kommentar"></textarea>
                    </div>

                    <button type="button" style="width:100%; margin:0px; display:none;" class="mb-2 mt-4 btn btn-success p-4 shadow-none" id="finishPayRechnungSelCLBtn"
                    onclick="finishPayRechnungSelCL()"><strong>Abschliessen und per E-mail senden</strong></button>

                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr01">
                        Schreiben Sie bitte den Firmennamen!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr02">
                        Überprüfen Sie die Telefonnummer, bevor Sie fortfahren
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr03">
                        Schreiben Sie zuerst den Namen des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr04">
                        Schreiben Sie zuerst den Nachnamen des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr05">
                        Schreiben Sie zuerst die Straße, PLZ, ORT und Nummer des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr06">
                        Schreiben Sie zuerst das Land des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr07">
                        Schreiben Sie zuerst die E-Mail des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr08">
                        Schreiben Sie zuerst die Telefonnummer des Kunden!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr09">
                        Wir akzeptieren diese Telefonnummer nicht
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr10">
                        Auf dem Server ist etwas schief gelaufen, bitte versuchen Sie es erneut!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr11">
                        Schreiben Sie bitte den Bestätigungscode
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr12">
                        Dieser Bestätigungscode ist nicht korrekt, versuchen Sie es erneut!
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr13">
                        Schreiben Sie die Anzahl der Tage für die Zahlungsfrist
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr14">
                        Die Anzahl der Tage für das Zahlungsziel ist ungültig
                    </div>
                    <div class="alert alert-danger text-center" style="display:none; width:100%;" id="paySelProdsRechnungModalErr15">
                        Der Kunde muss seine Unterschrift leisten!
                    </div>
                    
                    <input type="hidden" name="paySelSelectedProdsRechnung" id="paySelSelectedProdsRechnung" value="0">

                    <input type="hidden" name="paySelTableNrRechnung" id="paySelTableNrRechnung" value="0">
                    <input type="hidden" name="discReasonRechnungSel" id="discReasonRechnungSel" value="empty">

                    <input type="hidden" name="cashDiscountInpRechnungSel" id="cashDiscountInpRechnungSel" value="0">
                    <input type="hidden" name="percentageDiscountInpRechnungSel" id="percentageDiscountInpRechnungSel" value="0">
                    <input type="hidden" name="tvshAmProdsPerventageRechnungSel" id="tvshAmProdsPerventageRechnungSel" value="0">

                    <input type="hidden" id="saveClForARechnungSelVal" name="saveClForARechnungSelVal" value="0">
                    <input type="hidden" id="usedAndExistingClientSel" name="usedAndExistingClientSel" value="0">
                    <input type="hidden" id="usedAndExistingClientIdSel" name="usedAndExistingClientIdSel" value="0">

                    <input type="hidden" id="tipForOrderCloseRechnungSel" name="tipForOrderCloseRechnungSel" value="0">

                    <input type="hidden" id="payAllRechnungGiftCardIdSel" name="payAllRechnungGiftCardIdSel" value="0">
                    <input type="hidden" id="payAllRechnungGiftCardAmountSel" name="payAllRechnungGiftCardAmountSel" value="0">

                    <input type="hidden" id="payAllRechnungResCommentSel" name="payAllRechnungResCommentSel" value="none">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- signature Modal  -->
<div class="modal" id="paySelRechnungsignatureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:200px;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color:rgb(39,190,175);" class="modal-title" id="exampleModalLabel">
                    <strong>Schreiben Sie bitte Ihre Unterschrift (<span id="paySelRechnungsignatureModalAttempt"> Versuch 1/5 </span>)</strong>
                </h5>
                <button type="button" class="close" onclick="closepaySelRechnungsignatureModal()"  aria-label="Close"> <i class="far fa-times-circle"></i> </button>
            </div>
            <div class="modal-body text-center d-flex flex-wrap justify-content-between">
                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5;" id="signaturePadSel"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="signaturePad2Sel"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="signaturePad3Sel"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="signaturePad4Sel"></div>

                <div style="border:1px solid rgb(72,81,87); width:652px; height:160px; margin-left:0px; z-index:5; display:none;" id="signaturePad5Sel"></div>


                <button type="button" id="clearSignatureSel" class="btn btn-danger mt-2" style="width:45%; z-index:10;">Abbrechen</button>
                <button type="button" id="saveSignatureSel" onclick="clickSaveSignaturePARechnungSel()" class="btn btn-info mt-2" style="width:45%; z-index:10;"><i class="fas fa-signature mr-2"></i> Sparen</button>
                                   
            </div>
        </div>
    </div>
</div>
<div id="payAllCameraModalSel" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scannen Sie den QR-Code der Geschenkkarte</h5>
                <button type="button" class="close"onclick="closePayAllCameraModalSel()">
                  <span aria-hidden="false"><i class="fa-regular fa-circle-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="video">
                  <video muted playsinline id="payALL-qr-video-sel" width="100%"></video>
                </div>
            </div>  
        </div>
    </div>
</div>

@include('adminPanelWaiter.tablePageTel.tableIndexPaySelectedScript')