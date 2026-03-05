<?php

use Carbon\Carbon;
use App\giftCardDeleteLogs;

    use Illuminate\Support\Facades\Auth;
    use App\Produktet;
    use App\kategori;
    use App\giftCard;
?>
<div class="mt-2 mb-4" id="gcDiv">

    <div class="d-flex flex-wrap justify-content-between">

        <p style="width:59%; font-size:1.5rem; color:rgb(39,190,175); margin:0px;" class="pl-2"><strong>Geschenkkarte</strong></p>

        <button style="width:20%;" class="btn btn-success shadow-none" data-toggle="modal" data-target="#addSellAGCModal">
            <strong><i class="fa-solid fa-circle-plus"></i></strong>
        </button>
        <button style="width:20%;" id="activateGCOpenCameraModalBtn" class="btn btn-success shadow-none" data-toggle="modal" data-target="#activateGCCameraScanModal">
            <strong><i class="fa-solid fa-camera"></i></strong>
        </button>

        <button style="width:33%;" class="btn btn-info shadow-none mt-1" data-toggle="modal" data-target="#expiredAndFullyUsedGCModal" onclick="showExpAUsedGC()">
            <strong><i class="fa-regular fa-rectangle-list"></i> Abgelaufen</strong>
        </button>
        <button style="width:33%;" class="btn btn-info shadow-none mt-1" data-toggle="modal" data-target="#deletedGCModal" onclick="showDeletedGC()">
            <strong><i class="fa-solid fa-trash-can"></i> Gelöscht</strong>
        </button>

        <button style="width:33%;" class="btn btn-info shadow-none mt-1" data-toggle="modal" data-target="#statisticsGCModal" onclick="reloadStatistikenModal()">
            <strong><i class="fa-solid fa-chart-column"></i> Statistiken</strong>
        </button>
    </div>
    <hr style="margin:6px 0 6px 0;">

    <div class="d-flex flex-wrap justify-content-between">
        <div style="width:49.5%;" class="input-group">
            <input type="text" style="font-size:0.8rem;" class="form-control shadow-none" placeholder="Geschenkkartencode" id="searchGCByCodeInp">
            <div class="input-group-append">
                <button class="btn btn-outline-dark shadow-none" style="font-size:0.8rem;" id="searchGCByCodeBtn" onclick="searchGCByCode()" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </div>
        <button style="width:49.5%; font-size:0.8rem;" class="btn btn-outline-dark shadow-none" id="gCSearchByScanModalBtn" data-toggle="modal" data-target="#gCSearchByScanModal">
            Suche per QR-Code <i class="fa-solid fa-camera"></i>
        </button>

        <div class="alert alert-danger text-center mt-1 mb-1" style="display:none; width:100%; font-weight:bold;" id="searchForAGCErr01">
            Geben Sie zuerst den Geschenkkartencode (XXXXXXXX) ein!
        </div>
        <div class="alert alert-danger text-center mt-1 mb-1" style="display:none; width:100%; font-weight:bold;" id="searchForAGCErr02">
            Geben Sie den Geschenkkartencode (XXXXXXXX) nicht korrekt ein. Überprüfen Sie ihn noch einmal!
        </div>
        <div class="alert alert-danger text-center mt-1 mb-1" style="display:none; width:100%; font-weight:bold;" id="searchForAGCErr03">
            Geben Sie den Geschenkkartencode (XXXXXXXX) nicht korrekt ein. Überprüfen Sie ihn noch einmal!
        </div>
    </div>

    <hr style="margin:6px 0 6px 0;">

    <div id="gcInstancesDiv" class="d-flex flex-wrap justify-content-start">
        <?php $nowDt = Carbon::now(); ?>
        @foreach (giftCard::where([['toRes',Auth::user()->sFor],['statActive','1'],['expirationDate','>=',$nowDt]])->orderByDesc('created_at')->get() as $oneGC)
            @if ($oneGC->gcSumInChf != $oneGC->gcSumInChfUsed)
                @if ($oneGC->onlinePayStat == 0)
                    <div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px; background-color:rgba(252,228,214,255);" 
                    class="d-flex flex-wrap justify-content-between p-1">
                @else
                    <div style="width:100%; margin:5px 0 5px 0; border:1px solid rgb(72,81,87); border-radius:7px;" class="d-flex flex-wrap justify-content-between p-1">
                @endif
                    @if ($oneGC->gcType = 'chf')
                        <!-- CHF giftCard -->
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">CHF</p>
                        @if ($oneGC->payM == 'Online')
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">
                            {{$oneGC->payM}}
                            <i style="width:20%; color:rgb(72,81,87);" class="fa-solid fa-qrcode ml-2" onclick="openOnlinePayGCQrCode('{{$oneGC->id}}')"></i>
                        </p>
                        @else
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">{{$oneGC->payM}}</p>
                        @endif
                    
                        @if ($oneGC->clName != 'empty')
                            <p style="width:100%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">
                                {{$oneGC->clName}}
                                @if ($oneGC->clLastname != 'empty')
                                <span class="ml-2">{{$oneGC->clLastname}}</span>
                                @endif
                            </p>
                        @endif

                        @if ($oneGC->clEmail != 'empty')
                            <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">{{$oneGC->clEmail}}</p>
                        @else
                            <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>
                        @endif
                        @if ($oneGC->clPhNr != 'empty')
                            <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">{{$oneGC->clPhNr}}</p>
                        @else
                            <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center">---</p>
                        @endif
                        <hr style="width:100%; margin:0px 0 6px 0;">

                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Summe :</p>
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Übrig :</p>
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">{{number_format($oneGC->gcSumInChf,2,'.','')}} CHF</p>
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:0px; line-height:1;" class="text-center">{{number_format($oneGC->gcSumInChf-$oneGC->gcSumInChfUsed,2,'.','')}} CHF</p>
                        
                        <hr style="width:100%; margin:6px 0 6px 0;">
                        
                        
                        <form style="width:30%;" method="POST" action="{{ route('giftCard.giftCardGetReceipt') }}">
                            {{ csrf_field()}}
                            <button type="submit" style="width:100%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-1 shadow-none">
                                <i class="fa-solid fa-xl fa-file-pdf"></i>
                            </button>
                            <input id="giftCardId" type="hidden" value="{{$oneGC->id}}" name="giftCardId">
                        </form>
                        <p onclick="showGCBillQrCode('{{$oneGC->id}}')" style="width:30%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center pt-1">
                            <i class="fa-solid fa-xl fa-qrcode"></i>
                        </p>
                        <p onclick="deleteGCPrep('{{$oneGC->id}}','{{$oneGC->idnShortCode}}','{{$oneGC->refId}}')" style="width:30%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-1">
                            <strong><i class="fa-solid fa-xl fa-trash-can"></i> </strong>
                        </p>

                        <p onclick="showGCBalanceStatus('{{$oneGC->id}}','0')" style="width:50%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="btn text-center pt-2">
                            <strong><i class="fa-regular fa-rectangle-list"></i> Bilanz / Historie</strong>
                        </p>
                        <p style="width:50%; font-weight:bold; font-size:1.3rem; color:rgb(72,81,87); margin-bottom:6px; line-height:1;" class="text-center pt-2"
                        onclick="openGCQrCode('{{$oneGC->id}}')">
                            <i style="color:rgb(72,81,87);" class="fa-solid fa-qrcode mr-2"></i>{{$oneGC->idnShortCode}}
                        </p>

                        <hr style="width:100%; margin:6px 0 6px 0;">
                        <?php
                            $buyDt = explode('-',explode(' ',$oneGC->created_at)[0]);
                            $expDt = explode('-',explode(' ',$oneGC->expirationDate)[0]);
                        ?>
                        @if($oneGC->oldGCtransfer == 0)
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verkaufsdatum</p>
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verfallsdatum</p>
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">{{$buyDt[2]}}.{{$buyDt[1]}}.{{$buyDt[0]}}</p>
                        <p style="width:50%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">{{$expDt[2]}}.{{$expDt[1]}}.{{$expDt[0]}}</p>
                        @else
                        <p style="width:33%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verkaufsdatum</p>
                        <p style="width:33%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">Verfallsdatum</p>
                        <p style="width:33%; font-weight:bold; color:rgb(72,81,87); padding-bottom:3px; margin-bottom:0; line-height:1; background-color:yellow;" class="text-center">Übertragen</p>
                        <p style="width:33%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">{{$buyDt[2]}}.{{$buyDt[1]}}.{{$buyDt[0]}}</p>
                        <p style="width:33%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1;" class="text-center">{{$expDt[2]}}.{{$expDt[1]}}.{{$expDt[0]}}</p>
                        <p style="width:33%; font-weight:bold; color:rgb(72,81,87); margin-bottom:3px; line-height:1; background-color:yellow;" class="text-center">Wert</p>
                        @endif
                    @else
                        
                    @endif
                </div>
            @endif
        @endforeach
    </div>

</div>



<!-- Modal -->
<div class="modal" id="addSellAGCModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>eine Geschenkkarte verkaufen</strong></h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body d-flex flex-wrap justify-content-between" id="addSellAGCModalBody">
                <!-- <button style="width:49%; font-weight:bold;" class="btn btn-outline-dark shadow-none" id="gcInProductBtn" onclick="showProductGC()" disabled>Produkt-Geschenkkarte</button>
                <button style="width:49%; font-weight:bold;" class="btn btn-dark shadow-none" id="gcInChfBtn" onclick="showChfGC()" disabled>Geschenkkarte in CHF</button> -->

                <div id="addSellAGCToKartelConAlert" class="mt-1 justify-content-between alert alert-success" style="width:100%; border-radius:5px; display:none;">
                    <p style="width:92%; margin:0; " class="text-center"><strong>Dieser Verkauf ist mit der gescannten Geschenkkarte verbunden</strong></p>
                    <button style="width:7%; padding:0;" class="btn btn-outline-danger shadow-none" onclick="cancelGCToKartelCon()"><i class="fa-solid fa-x"></i></button>
                </div>

                <div id="addSellAGCModalError" class="mt-1 justify-content-between alert alert-danger" style="width:100%; border-radius:5px; display:none;">
                    
                </div>

                <div style="width:100%; display:none;" id="gcInProductDiv" class="flex-wrap justify-content-start mt-2">
                    <!-- <div id="gcInProductDivProdsDiv" class="d-flex flex-wrap justify-content-start mt-2">
                    @foreach (kategori::where('toRes',Auth::user()->sFor)->get() as $kategoriOne)
                        <div style="width:100%;" class="d-flex flex-wrap justify-content-start">
                            <p style="width:39.8%; margin:4px 0.1% 4px 0.1%; font-size:1.3rem; color:rgb(39,190,175);" class="text-center"><strong>{{$kategoriOne->emri}}</strong></p>
                            @foreach (Produktet::where([['toRes',Auth::user()->sFor],['kategoria',$kategoriOne->id]])->get() as $prodOne)
                            <div style="width:19.8%; margin:4px 0.1% 4px 0.1%;" class="d-flex justify-content-start" 
                            onclick="selectThisProductForGC('{{$prodOne->id}}','{{$prodOne->emri}}')">
                                <button style="width:100%; font-size:0.8rem;" class="btn btn-outline-dark shadow-none"><strong>{{$prodOne->emri}}</strong></button>
                            </div>
                            @endforeach
                        </div>
                        <hr style="width:100%; margin:4px 0 4px 0;">
                    @endforeach
                    </div>
                    <div id="gcInProductDivProdSelectedDiv" class="d-flex flex-wrap justify-content-start mt-2">

                    </div> -->
                </div>

                <div style="width:100%; display:flex;" id="gcInChfDiv" class="flex-wrap justify-content-between mt-2">
                    <button id="setChfGC50Btn" style="width:49.5%;" class="btn btn-outline-success shadow-none mb-1" onclick="setChfGCBtn('50')">50.00 CHF</button>
                    <button id="setChfGC100Btn" style="width:49.5%;" class="btn btn-outline-success shadow-none mb-1" onclick="setChfGCBtn('100')">100.00 CHF</button>
                    <button id="setChfGC150Btn" style="width:49.5%;" class="btn btn-outline-success shadow-none mb-1" onclick="setChfGCBtn('150')">150.00 CHF</button>
                    <button id="setChfGC200Btn" style="width:49.5%;" class="btn btn-outline-success shadow-none mb-1" onclick="setChfGCBtn('200')">200.00 CHF</button>
                    <input class="form-control shadow-none mb-1" placeholder="Betrag eingeben" id="gcChfInputId" style="width:100%; border-top:none; border-left:none; border-right:none;" type="number" onkeyup="setChfGCInput()">
                </div>

                <hr style="width:100%;">

                <p style="width:100%; margin:0;" class="pt-3 text-center"><strong>Ablaufdatum der Geschenkkarte:</strong></p>
                <div style="width: 100%;" class="form-group d-flex justify-content-between">
                    <input style="width: 88%;" onchange="removeYearsSelected()" class="form-control shadow-none" type="date" id="pickExpirationDate01" name="pickExpirationDate01" />
                    <label style="width: 10%; color:rgb(39,190,175);" for="startdate"><span class="fa fa-2x fa-calendar"><abbr title="Four digits year, dash, two digits month, dash, two digits day"></abbr></span></label>
                </div>
                <input type="hidden" value="0" id="gcExprDateByYearsTheYrSelected">
                <div style="width: 100%;" class="form-group d-flex flex-wrap justify-content-between" id="yearsExpDateForGCDiv">
                    <button class="btn btn-info shadow-none mb-1" id="gcExprDateByYears1" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(1)>1 Jahr</button>
                    <button class="btn btn-info shadow-none mb-1" id="gcExprDateByYears2" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(2)>2 Jahr</button>
                    <button class="btn btn-info shadow-none mb-1" id="gcExprDateByYears3" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(3)>3 Jahr</button>
                    <button class="btn btn-info shadow-none mb-1" id="gcExprDateByYears4" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(4)>4 Jahr</button>
                    <button class="btn btn-info shadow-none mb-1" id="gcExprDateByYears5" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(5)>5 Jahr</button>
                    <button class="btn btn-info shadow-none" id="gcExprDateByYears6" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(6)>6 Jahr</button>
                    <button class="btn btn-info shadow-none" id="gcExprDateByYears7" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(7)>7 Jahr</button>
                    <button class="btn btn-info shadow-none" id="gcExprDateByYears8" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(8)>8 Jahr</button>
                    <button class="btn btn-info shadow-none" id="gcExprDateByYears9" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(9)>9 Jahr</button>
                    <button class="btn btn-info shadow-none" id="gcExprDateByYears10" style="width:19.9%; font-size:0.8rem; font-weight:bold;" onclick=gcExprDateByYears(10)>10 Jahr</button>
                </div>

                <div class="modal-body d-flex flex-wrap justify-content-between" style="width:100%;" id="addSellAGCModalBodySubPart3">
                    <hr style="width:100%;">

                    <p style="width:100%; color:rgb(39,190,175); font-size:1.4rem; margin:0px;" class="text-center"><strong>Kundendaten (optional)</strong></p>
                    <div style="width:49.5%;" class="form-group mb-2">
                        <label for="exampleFormControlInput1">Name</label>
                        <input type="text" class="form-control shadow-none" id="clientDataName">
                    </div>

                    <div style="width:49.5%;" class="form-group mb-2">
                        <label for="exampleFormControlInput1">Nachname</label>
                        <input type="text" class="form-control shadow-none" id="clientDataLastname">
                    </div>

                    <div style="width:49.5%;" class="form-group mb-2">
                        <label for="exampleFormControlInput1">Email</label>
                        <input type="text" class="form-control shadow-none" id="clientDataEmail">
                    </div>

                    <div style="width:49.5%;" class="form-group mb-2">
                        <label for="exampleFormControlInput1">Telefonnummer</label>
                        <input type="text" class="form-control shadow-none" id="clientDataPhoneNr">
                    </div>

                    <hr style="width:100%;">

                    <button id="payGCBtn1" style="width:49.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="payGCCashPayPrep()">
                        <strong>Bar</strong>
                    </button>
                    <button id="payGCBtn2" style="width:49.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="payGCCardPay()">
                        <strong>Karte</strong>
                    </button>
                    @if (Auth::user()->sFor == 40)
                    <button id="payGCBtn3" style="width:49.5%; margin:0px;" class="btn btn-warning text-center shadow-none" disabled>
                        <strong>Online</strong>
                    </button>
                    @else
                    <button id="payGCBtn3" style="width:49.5%; margin:0px;" class="btn btn-warning text-center shadow-none" onclick="payGCOnlinePay()">
                        <strong>Online</strong>
                    </button>
                    @endif
                    <button id="payGCBtn4" style="width:49.5%; margin:0px;" class="mt-1 btn btn-warning text-center shadow-none" onclick="payGCFaturePayPrep()">
                        <strong>Auf Rechnung</strong>
                    </button>

                    <div class="alert alert-danger text-center mt-1" style="width:100%; display:none; font-weight:bold;" id="addSellAGCModalErr01">
                        Legen Sie vorab den Betrag in CHF für die Geschenkkarte fest!
                    </div>

                    <div id="payGCPayAtPOSAlert" class="alert alert-info text-center mt-1" style="display:none; width:100%;">
                        <strong>Schließen Sie die Zahlung am POS-Terminal ab!</strong>
                    </div>

                    <input type="hidden" value="chf " id="gcFinalTypeSelected">
                    <input type="hidden" value="0" id="gcFinalInChf">
                    <input type="hidden" value="0" id="gcFinalProdSelId">

                    <input type="hidden" value="0" id="gcToSellConect"> font-size:0.8rem;
                </div>
            </div>
        </div>
    </div>
</div>

<!-- redirectClientToOnlineGCPay Modal -->
<div class="modal" id="redirectClientToOnlineGCPayModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Online-Zahlung mit Geschenkkarte (für den Kunden)</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeRedirectClientToOnlineGCPayModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="redirectClientToOnlineGCPayBody">
                <img id="redirectClientToOnlineGCPayQRCode" src="" style="width:100%; height:auto;" alt="">

                <hr>
                <p class="text-center mt-2" style="font-weight: bold;">
                    Scannen Sie diesen QR-Code, um die Seite zu öffnen, auf der Sie Ihre Geschenkkarte bezahlen können
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Gift card use qr-code Modal -->
<div class="modal" id="giftCardUseQRCodeModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Verwenden Sie diese Geschenkkarte, indem Sie diesen QR-Code scannen</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeGiftCardUseQRCodeModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="giftCardUseQRCodeBody">
                <img id="giftCardUseQRCodePic" src="" style="width:100%; height:auto;" alt="">

                <hr>
                <p class="text-center mt-2" style="font-weight: bold;">
                    Dieser QR-Code muss auf der Bestellzahlungsseite gescannt werden!
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Gift card Bill qr-code Modal -->
<div class="modal" id="giftCardBillQRCodeModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Laden Sie die PDF-Version der Rechnung für diese Geschenkkarte herunter, indem Sie diesen QR-Code scannen</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeGiftCardBillQRCodeModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="giftCardBillQRCodeBody">
                <img id="giftCardBillQRCodePic" src="" style="width:100%; height:auto;" alt="">
                <hr>
            </div>
        </div>
    </div>
</div>


<!-- Gift card Balance & history Modal -->
<div class="modal" id="giftCardBalanceModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Laden Sie die PDF-Version der Rechnung für diese Geschenkkarte herunter, indem Sie diesen QR-Code scannen</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeGiftCardBalanceModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="giftCardBalanceBody">
                
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="0" id="reShowExpiredGC">




<!-- Gift Statistics Modal -->
<div class="modal" id="statisticsGCModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Statistiken über Verkäufe und Verwendung der Geschenkkarte</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="CloseStatisticsGCModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body p-4 d-flex flex-wrap justify-content-between" id="statisticsGCBody">
                <?php
                    $nowDt = Carbon::now();
                    $totalGCSale = number_format(0, 2, '.', ''); 
                    $totalGCAmntUsed = number_format(0, 2, '.', ''); 
                    $totalGCAmntExpired = number_format(0, 2, '.', ''); 
                    $totalGCAmntDeleted = number_format(0, 2, '.', ''); 

                    foreach( giftCard::where('toRes',Auth::user()->sFor)->get() as $oneGC ){
                        $totalGCSale += number_format($oneGC->gcSumInChf, 2, '.', ''); 
                        $totalGCAmntUsed += number_format($oneGC->gcSumInChfUsed, 2, '.', ''); 
                    }
                    foreach( giftCardDeleteLogs::where('toRes',Auth::user()->sFor)->get() as $oneGCDeleted ){
                        $totalGCSale += number_format($oneGCDeleted->gcSumInChf, 2, '.', ''); 
                        $totalGCAmntUsed += number_format($oneGCDeleted->gcSumInChfUsed, 2, '.', '');
                        $totalGCAmntDeleted += number_format($oneGCDeleted->gcSumInChf - $oneGCDeleted->gcSumInChfUsed, 2, '.', ''); 
                    }

                    foreach( giftCard::where('toRes',Auth::user()->sFor)->where('expirationDate','<',$nowDt)->orWhereColumn('gcSumInChf', '=', 'gcSumInChfUsed')->get() as $oneGCExpired){
                        $totalGCAmntExpired += number_format($oneGCExpired->gcSumInChf - $oneGCExpired->gcSumInChfUsed, 2, '.', '');
                    }
                ?>
                <p style="width:70%;"><strong>Gesamtverkaufte Geschenkkarten:</strong></p>
                <p style="width:30%; text-align:right;"><strong>{{number_format($totalGCSale, 2, '.', '')}} CHF</strong></p>
                <p style="width:70%;"><strong>Eingelöster Betrag Geschenkkarte:</strong></p>
                <p style="width:30%; text-align:right;"><strong>{{number_format($totalGCAmntUsed, 2, '.', '')}} CHF</strong></p>
                <p style="width:70%;"><strong>Geschenkkarte abgelaufen Betrag:</strong></p>
                <p style="width:30%; text-align:right;"><strong>{{number_format($totalGCAmntExpired, 2, '.', '')}} CHF</strong></p>
                <p style="width:70%;"><strong>Geschenkkarte gelöscht Betrag:</strong></p>
                <p style="width:30%; text-align:right;"><strong>{{number_format($totalGCAmntDeleted, 2, '.', '')}} CHF</strong></p>
                <p style="width:70%;"><strong>Verbleibender Betrag Geschenkkarte:</strong></p>
                <p style="width:30%; text-align:right;"><strong>{{number_format($totalGCSale - $totalGCAmntUsed - $totalGCAmntExpired - $totalGCAmntDeleted, 2, '.', '')}} CHF</strong></p>
            </div>
        </div>
    </div>
</div>



<!-- Gift delete Request Modal -->
<div class="modal" id="giftCardDeleteModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Sind Sie sicher, dass Sie diese Geschenkkarte löschen möchten</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeGiftCardDeleteModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
              
            </div>
            <div class="modal-body d-flex flex-wrap justify-content-between" id="giftCardDeleteBody">
                <p style="width:50%; line-height:1.1; font-size:1.3rem; font-weight:bold;" class="text-center mb-1">
                    ref ID: <span id="gcDeleteIdShow"></span>
                </p>
                <p style="width:50%; line-height:1.1; font-size:1.3rem; font-weight:bold;" class="text-center mb-1">
                    <span id="gcDeleteShortCodeShow"></span>
                </p>

                <hr style="width:100%;">

                <button style="width:49%;" class="btn btn-outline-dark" data-dismiss="modal" aria-label="Close" onclick="closeGiftCardDeleteModal()">Nein</button>
                <button style="width:49%;" class="btn btn-danger" onclick="deleteGC()">Ja</button>

                <p style="width:100%; opacity:0.75; line-height:1.1;" class="text-center mt-3">
                    Wenn Sie diese Geschenkkarte löschen, kann der Besitzer sie nicht mehr verwenden und sie ist endgültig.
                </p>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="0" id="gcDeleteId">


<div id="activateGCCameraScanModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scannen Sie den QR-Code der Geschenkkarte</h5>
                <button type="button" class="close"onclick="closeActivateGCCameraScanModal()">
                  <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="video">
                  <video muted playsinline id="activateGC-qr-video" width="100%"></video>
                </div>
            </div>  
        </div>
    </div>
</div>

<div id="gCSearchByScanModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scannen Sie den QR-Code der Geschenkkarte, die Sie finden möchten</h5>
                <button type="button" class="close"onclick="closeGCSearchByScanModal()">
                  <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="video">
                  <video muted playsinline id="searchGC-qr-video" width="100%"></video>
                </div>
            </div>  
        </div>
    </div>
</div>



@include('adminPanelWaiter.giftCardTel.gcModals')
@include('adminPanelWaiter.giftCardTel.gcScript')