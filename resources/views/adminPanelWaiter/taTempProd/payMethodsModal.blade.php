<?php
    use App\rechnungClient;
?>

<!-- pay All Modal_phase_01 -->
<div class="modal" id="payAllPhaseOne" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" aria-label="Close" onclick="closePayAllPhaseOne()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body p-1" id="payAllPhaseOneBody">
                <div class="d-flex flex-wrap justify-content-between" id="payAllPhaseOneDiv1">
                    <p style="width: 100%;" class="text-center"><strong>Warenkorb</strong></p>
                </div>
                <hr>

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

                <div class="d-flex flex-wrap justify-content-between mt-2" id="payAllPhaseOneDiv4">
                    <p style="width: 100%;" class="text-center"><strong>Zahlungeart</strong></p>
                    <button style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPayAllProdsCash()"><strong>Bar</strong></button>
                    <button style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="payAllProdsCard()"><strong>Karte</strong></button>
                    <button style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" disabled><strong>Online</strong></button>
                    <button style="width:24.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="prepPayAllProdsRechnung()"><strong>Auf Rechnung</strong></button>
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
                
                <input type="hidden" id="payAllTableNr" value="0">

                <input type="hidden" id="cashDiscountInp" value="0">
                <input type="hidden" id="percentageDiscountInp" value="0">                
            </div>

        </div>
    </div>
</div>





