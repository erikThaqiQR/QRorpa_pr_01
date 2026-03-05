<?php

   use App\kategori;
   use App\Produktet;
   use App\Takeaway;
   use App\LlojetPro;
   use App\ekstra;
use App\resPlates;
use App\Restorant;
use Carbon\Carbon;
use App\billTabletsReg;
use App\UserNotiDtlSet;
use App\userSoundsList;
use App\ordersTempForTA;
use Illuminate\Support\Facades\Auth;

    $sndNotiActv = explode('--||--',Auth::user()->notifySet)[1];
    $usrNotiReg21 = UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',21]])->first();
    if($usrNotiReg21 != Null){
        $usrNotiReg21Sound = userSoundsList::find($usrNotiReg21->setValue);
    }else{
        $usrNotiReg21Sound = userSoundsList::find(1); 
    }

    $usrNotiReg31 = UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',31]])->first();
    if($usrNotiReg31 != Null){
        $usrNotiReg31Sound = userSoundsList::find($usrNotiReg31->setValue);
    }else{
        $usrNotiReg31Sound = userSoundsList::find(1); 
    }

    $allBillT = billTabletsReg::where('toRes',Auth::user()->sFor)->get();
?>

@if ($sndNotiActv == 1) 
    <script> var hasSndNotiActv = 1; </script>
@else
    <script> var hasSndNotiActv = 0; </script>
@endif

@if ($usrNotiReg21 != Null) 
    <script> var hasAddSoundSelected= 1; </script>
@else
    <script> var hasAddSoundSelected= 0; </script>
@endif

@if ($usrNotiReg31 != Null) 
    <script> var hasPayOrdSoundSelected = 1; </script>
@else
    <script> var hasPayOrdSoundSelected = 0; </script>
@endif
<style>
    .teksti{
        justify-content:space-between;
        margin-top:-50px;
        color:#FFF;
        font-weight:bold;
        font-size:23px;
        margin-bottom:10px;
    }
 
    .prod-name{
        line-height: 2;
    }
    .add-plus-section{
        text-align: right;
        padding: 0px;
    }
    .product-section{
        border-bottom: 1px solid #dcd9d9;
        padding-bottom: 15px;
    }
    .recommended-title{
        margin-left: 0px !important;
    }
    .teksti strong{
        margin-left:20px;
    }
    .teksti i{
        margin-right:20px
    }
    .phoneNrSelected{
        color: white;
        border: 1px solid rgb(39,190,175);
        border-radius: 10px;
        background-color: rgb(39,190,175);
    }
    .phoneNrNotSelected{
        color: rgb(39,190,175);
        border: 1px solid rgb(39,190,175);
        border-radius: 10px;

    }

    .pointerClicker:hover{
        cursor: pointer;
    }

    .modal-xl {
        max-width: 100% !important;
    }

    @keyframes glowing {
        0% { box-shadow: 0 0 -10px black; }
        40% { box-shadow: 0 0 20px black; }
        60% { box-shadow: 0 0 20px black; }
        100% { box-shadow: 0 0 -10px black; }
    }

    .taNewProCatSel {
        animation: glowing 800ms infinite;
    }

    .minHeight295{ min-height: 295px; }
    .minHeight370{ min-height: 370px; }
    .minHeight470{ min-height: 470px; }
    .minHeight570{ min-height: 570px; }
    .minHeight670{ min-height: 670px; }
    .minHeight770{ min-height: 770px; }
    .minHeight870{ min-height: 870px; }

    .maxHeight25{ max-height:25px; }
    .maxHeight100{ max-height:100px; }
    .maxHeight165{ max-height:165px; }
    .maxHeight245{ max-height:245px; }
    .maxHeight345{ max-height:345px; }
    .maxHeight425{ max-height:425px; }
    .maxHeight525{ max-height:525px; }
    .maxHeight625{ max-height:625px; }
</style>
<script>
    var crrOpenCat = 0;

    var last = "";
    var lastV = 0;
</script>

<div id="addTypePlease" style="top: 12%; width: 100%; position: fixed; z-index: 100000; display: none;">
    <div class="text-center" style="background-color:rgb(39, 190, 175); color:white;  padding-top:15px; padding-bottom:15px; border-radius:9px;">
        Bitte Typ auswählen!
    </div>
</div>

<!-- Modal -->
<div class="modal" id="addOrForTAModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding:0px;">
    <div class="modal-dialog modal-xl" role="document" style="margin-top: 5px;">
        <div class="modal-content">
            <div class="modal-body">

                <div class="pt-2 pb-5 pr-1 pl-1">
                    <div class="d-flex justify-content-between">
                        <h3 class="pl-2" style="width:70%; color:rgb(39,190,175);">
                            <strong>
                                Registrieren Sie eine neue Takeaway-Bestellung
                            </strong>
                        </h3>
                        <span style="width:20%;"></span>
                        <button style="width:10%;" type="button" class="close" aria-label="Close" onclick="closeAddOrForTAModal()">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                   
                    <div id="addOrForTAModalBody2" class="d-flex justify-content-between">
                        <div id="addOrForTAModalBody2Left" style="width:69%">

                            <div class="swiper-container p-0" style="width:100%; border:1px solid rgb(39,190,175); border-radius:6px; overflow: hidden; " id="taRecomendetPro">
                                <div id="addOrForTAModalBody2LeftCats" class="swiper-wrapper p-2"> 
                                    <?php 
                                        $catShowFirst = 0; 
                                        $taCats = array();
                                        if(Restorant::find(Auth::user()->sFor)->sortingType == 1){
                                            $catsCall = Kategori::where('toRes', '=', Auth::user()->sFor)->orderByDesc('visits')->get();
                                        }else{
                                            $catsCall = Kategori::where('toRes', '=', Auth::user()->sFor)->orderBy('position')->get();
                                        }
                                    ?>
                                    <script>
                                        var openCatTaNewPro = 0;
                                    </script>
                                    @foreach($catsCall  as $kat)
                                        @if(Takeaway::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$kat->id)->count() > 0)
                                            <img class="mr-1 swiper-slide {{ $catShowFirst == 0 ? 'taNewProCatSel' : '' }}" id="taNewProdCatImg{{$kat->id}}"
                                            style="border-radius:5px; width:64px; height:40px;" src="storage/kategoriaUpload/{{$kat->foto}}"alt=""
                                            onclick="selectShowCatTaNewProds('{{$kat->id}}')">
                                            <?php 
                                                if($catShowFirst == 0){ $catShowFirst = $kat->id; }
                                                if(!in_array($kat->id,$taCats)){ array_push($taCats,$kat->id); }    
                                            ?>
                                            <script>
                                                if(openCatTaNewPro == 0){ openCatTaNewPro = '{{$kat->id}}'; }
                                            </script>
                                        @endif
                                    @endforeach

                                    <img class="mr-1 swiper-slide " id="taNewProdCatImg000"
                                    style="border-radius:5px; width:64px; height:40px;" src="storage/kategoriaUpload/whiteCat.JPG" alt="">
                                    <img class="mr-1 swiper-slide " id="taNewProdCatImg000"
                                    style="border-radius:5px; width:64px; height:40px;" src="storage/kategoriaUpload/whiteCat.JPG" alt="">
                                </div>
                            </div>
                            @foreach ($taCats as $taCatsOneId)
                                @if ($taCatsOneId == $catShowFirst)
                                <div class="flex-wrap justify-content-start" style="width:100%; max-height:534px; overflow-y:scroll; display:flex;" id="taNewProdCat{{$taCatsOneId}}Prods">
                                @else
                                <div class="flex-wrap justify-content-start" style="width:100%; max-height:534px; overflow-y:scroll; display:none;" id="taNewProdCat{{$taCatsOneId}}Prods">
                                @endif
                                    <p style="width:100%; color:rgb(39,190,175); font-size:1.6rem;" class="mt-1 mb-1">
                                        <strong>{{Kategori::find($taCatsOneId)->emri}}</strong>
                                    </p>
                                    @foreach(Takeaway::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$taCatsOneId)->orderByDesc('created_at')->get() as $ketoProd)
                                        <div class="d-flex justify-content-between" style="width:24.4%; margin: 4px 0.05% 4px 0.05%; border:1px solid rgb(39,190,175); border-radius:0.25rem;">
                                            <div style="width:80%;" class="p-1"  data-toggle="modal" data-target="#ProdNewTATemp{{$ketoProd->id}}">
                                                <p style="margin:0px; padding:0px; color:rgb(72,81,87); overflow:hidden; white-space: nowrap;">
                                                    <strong>{{$ketoProd->emri}}</strong>
                                                </p>
                                                <p style="margin:0px; padding:0px; color:rgb(72,81,87); overflow:hidden; white-space: nowrap;">
                                                    <strong>CHF {{number_format($ketoProd->qmimi,2,'.','')}}</strong>
                                                </p>
                                            </div>

                                            @if($ketoProd->type == NULL)
                                            <button onclick="addToCartExpressTATemp('{{$ketoProd->id}}','{{$sndNotiActv}}')" id="addToCartExpressBtn{{$ketoProd->id}}" class="btn btn-outline-dark shadow-none" style="width:20%; height:100%; margin:0px; padding:1px;">
                                            @else
                                            <button data-toggle="modal" data-target="#openTypeSelect{{$ketoProd->id}}" class="btn btn-outline-dark shadow-none" style="width:20%; height:100%; margin:0px; padding:1px;">
                                            @endif
                                                <i class="fas fa-2x fa-cart-plus" style="color: green;"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div id="addOrForTAModalBody2Right" style="width:30%; min-height: 590px; border:1px solid rgb(39,190,175); border-radius:6px;  position: relative;" 
                        class="p-1 d-flex flex-wrap justify-content-between">
                            <p style="width:100%; color:rgb(39,190,175);" class="text-center"><strong>Aktueller Auftrag</strong></p>
                            <div class="d-flex flex-wrap justify-content-between mb-1" id="addOrForTAModalBody2RightPayments" 
                            style="position: absolute; top: 24px; left:2px; right:2px; max-height:310px; overflow-y:scroll;">
                                <?php
                                    $totalInChf = number_format(0, 9, '.','');        
                                    $total77mwst = number_format(0, 9, '.','');        
                                    $total25mwst = number_format(0, 9, '.','');
                                ?>
                                @foreach(ordersTempForTA::where([['toRes',Auth::user()->sFor],['fromWo',Auth::user()->id],['theStatus','0']])->get() as $orTATOne)
                                    <button class="btn btn-outline-danger mb-1" style="margin:0px; padding:1px; width:12%;"
                                    onclick="deleteTempTAProd('{{$orTATOne->id}}')" id="deleteTempTAProdBtn{{$orTATOne->id}}">
                                        <i class="fa-solid fa-delete-left"></i>
                                    </button>
                                    <p class="mb-1 productShowTATemp" style="margin:0px; padding:1px; width:62%; overflow:hidden; white-space: nowrap;">
                                        <span>{{$orTATOne->proSasia}} X</span>
                                        <strong>{{$orTATOne->taProdName}}</strong>
                                        @if ($orTATOne->proType != 'empty')
                                        <br><span><strong>Typ: " {{explode('||',$orTATOne->proType)[0]}} "</strong></span>
                                        @endif
                                    </p>
                                    <p class="mb-1 text-center" style="margin:0px; padding:1px; width:25%;">
                                        <strong>{{number_format($orTATOne->proQmimi * $orTATOne->proSasia, 2, '.','')}} <span style="font-size: 0.4rem;;">CHF</span></strong>
                                    </p>
                                
                                    <?php
                                        $totalInChf += number_format($orTATOne->proQmimi*$orTATOne->proSasia, 9, '.','');
                                        $fndTAPr = Takeaway::find($orTATOne->taProdId);
                                        if($fndTAPr != NULL && $fndTAPr->mwstForPro == 8.10){
                                            $total77mwst = number_format($orTATOne->proQmimi*$orTATOne->proSasia * 0.074930619, 9, '.','');
                                        }else if($fndTAPr != NULL && $fndTAPr->mwstForPro == 2.60){
                                            $total25mwst += number_format($orTATOne->proQmimi*$orTATOne->proSasia * 0.025341130, 9, '.','');
                                        }else{
                                            $total77mwst = number_format($orTATOne->proQmimi*$orTATOne->proSasia * 0.074930619, 9, '.','');
                                        }
                                    ?>
                                @endforeach
                            </div>

                            <div class="d-flex flex-wrap justify-content-between mb-1" id="addOrForTAModalBody2RightPayments2" 
                            style="position: absolute; bottom: 2px; left:2px; right:2px;">

                                <hr style="width:100%; margin-top:2px; margin-bottom:2px;">

                                <div id="tipAndDealDiv" style="width:100%">
                                    <div id="tipAndDealDivButtons" class="d-flex justify-content-between">
                                        <button onclick="showTippToOrd()" class="btn btn-info" style="width:33%;"><strong>Tipp</strong></button>
                                        <button onclick="showRabattToOrd()" class="btn btn-info" style="width:33%;"><strong>Rabatt</strong></button>
                                        <button onclick="showGiftCardToOrd()" class="btn btn-info" style="width:33%;"><strong>Geschenkkarte</strong></button>
                                    </div>   
            
                                </div>

                                <div id="GCDivShowForm" class="flex-wrap justify-content-between" style="width:100%; display:none;">
                                    <p class="text-center" style="width:90%; color:rgb(72,81,87); margin:0;"><strong>Geschenkkarte beantragen</strong></p>
                                    <button class="btn btn-danger shadow-none" onclick="removeTippRabattForm()" style="margin:0px; width:10%; padding:2px;">X</button>
                                    <div style="width:100%;" class="input-group mt-1">
                                        <input type="text" class="form-control shadow-none" id="payAllgcValidationCodeInput" placeholder="Geschenkkartencode" aria-label="Geschenkkartencode">
                                        <div class="input-group-append">
                                            <button id="payAllValidateGCBtn" style="margin:0px;" class="btn btn-outline-dark shadow-none" type="button" onclick="payAllValidateGC()"><strong>Bestätigen</strong></button>
                                        </div>
                                        <div class="input-group-append">
                                            <button id="payAllOpenCameraModalBtn" style="margin:0px;" class="btn btn-outline-dark shadow-none" type="button" data-toggle="modal" data-target="#payAllCameraModal">
                                                <i class="fa-solid fa-camera"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <label id="payAllPhaseOneDiv5_3" style="display:none;" for="basic-url" class="mt-2">Restbetrag <span id="payAllamountLeftChf"></span> CHF</label>
                                    <div id="payAllPhaseOneDiv5_4" style="display:none;" class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rabattbetrag</span>
                                        </div>
                                    </div>
                                    <input type="text" id="payAllApplyDiscFromGcInput" class="form-control shadow-none" id="basic-url" aria-describedby="basic-addon3">
                              
                                    <button id="payAllPhaseOneDiv5_5" style="width: 49.5%; display:none; margin:4px 0px 4px 0px;" onclick="payAllApplyGCDiscount()" class="btn btn-outline-success shadow-none">Anwenden</button>
                                    <button id="payAllPhaseOneDiv5_6" style="width: 49.5%; display:none; margin:4px 0px 4px 0px;" onclick="payAllApplyGCDiscountMax()" class="btn btn-outline-success shadow-none">Maximal anwenden</button>
                        
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
                                </div>

                                <input type="hidden" id="tipForOrder" value="0">

                                <hr style="width:100%; margin-top:2px; margin-bottom:2px;">

                                <div id="addOrForTAModalBody2RightPayments2Totals" class="d-flex flex-wrap justify-content-between" style="width:100%">
                                    <p id="payAllPhaseOneDiv1TippP11" style="width: 50%; margin:0px; padding:0px;"><strong>Tipp: </strong></p>
                                    <p id="payAllPhaseOneDiv1TippP12" style="width: 50%; margin:0px; padding:0px;" class="text-right">
                                        <strong><span id="tippAmProds">{{number_format(0, 2, '.','')}}</span> <span style="font-size: 0.4rem;;">CHF</span></strong>
                                    </p>

                                    <p id="payAllPhaseOneDiv1RabattP11" style="width: 50%; margin:0px; padding:0px;" class="text-left"><strong>Rabatt.</strong></p>
                                    <p id="payAllPhaseOneDiv1RabattP12" style="width: 50%; margin:0px; padding:0px;" class="text-right">
                                        <strong><span id="rabatAmProds">{{number_format(0, 2, '.','')}}</span> <span style="font-size: 0.4rem;;">CHF</span></strong>
                                    </p>
                                    
                                    <p id="payAllPhaseOneDiv1GiftcardP11" style="width: 50%; margin:0px; padding:0px;"><strong>Geschenkkarte: </strong></p>
                                    <p id="payAllPhaseOneDiv1GiftcardP12" style="width: 50%; margin:0px; padding:0px;" class="text-right">
                                        <strong><span id="giftcardAmProds">{{number_format(0, 2, '.','')}}</span> <span style="font-size: 0.4rem;;">CHF</span></strong>
                                    </p>
                                    <p style="width: 50%; margin:0px; padding:0px;"><strong>Gesamt: </strong></p>
                                    <p style="width: 50%; margin:0px; padding:0px;" class="text-right">
                                        <strong><span id="totAmProds">{{number_format($totalInChf, 2, '.','')}}</span> <span style="font-size: 0.4rem;;">CHF</span></strong>
                                    </p>

                                    <p style="width: 50%; margin:0px; padding:0px;"><strong>Gesamtbetrag: </strong></p>
                                    <p style="width: 50%; margin:0px; padding:0px;" class="text-right">
                                        <strong><span id="finalPaySpan">{{number_format($totalInChf, 2, '.','')}}</span> <span style="font-size: 0.4rem;;">CHF</span></strong>
                                    </p>
                                
                                </div>
                                
                                <input type="hidden" id="payAllGCAppliedId" value="0">
                                <input type="hidden" id="payAllGCAppliedCHFVal" value="0">
                                

                                <button id="payTaProdBtn1" class="btn btn-success shadow-none mb-1" style="width:49%" onclick="prepPayAllProdsCash()"><strong>Bar</strong></button>
                                <button id="payTaProdBtn2" class="btn btn-success shadow-none mb-1" style="width:49%" onclick="payAllProdsCard()"><strong>Karte</strong></button>
                                @if (Auth::user()->sFor == 40)
                                <button id="payTaProdBtn3" class="btn btn-success shadow-none" style="width:49%" disabled><strong>Online</strong></button>
                                @else
                                <button id="payTaProdBtn3" class="btn btn-success shadow-none" style="width:49%" onclick="payAllProdsOnline()"><strong>Online</strong></button>
                                @endif
                                <button id="payTaProdBtn4" class="btn btn-success shadow-none" style="width:49%" onclick="prepPayAllProdsRechnung()"><strong>Auf Rechnung</strong></button>

                                <div id="payAllPhaseOneError1" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                                    <strong>Schreiben Sie einen Grund für diesen Rabatt!</strong>
                                </div>
                                <div id="payAllPhaseOneError2" class="alert alert-danger text-center mt-1" style="display:none; width:100%;">
                                    <strong>Sie haben noch keine Bestellung registriert!</strong>
                                </div>
                                <div id="payTAPayAtPOSAlert" class="alert alert-info text-center mt-1" style="display:none; width:100%;">
                                    <strong>Schließen Sie die Zahlung am POS-Terminal ab!</strong>
                                </div>
                            </div>

                            <input type="hidden" id="cashDiscountInp" value="0">
                            <input type="hidden" id="percentageDiscountInp" value="0">  
                            <input type="hidden" id="discReasonInp2" value="0">  
                            
                        </div>
                  
                    </div>
                </div>
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
                  <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
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


<!-- payAllProdsCashModal Modal -->
<div class="modal" id="payTAProdsOnlineModal"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Online (für den Kunden)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="payTAProdsOnlineBody">
                <img id="payTAProdsOnlineQRCode" src="" style="width:100%; height:auto;" alt="">

                <hr>
                <p class="text-center mt-2" style="font-weight: bold;">
                    Der Kunde kann diesen QR-Code scannen und zur Online-Zahlungsseite für diese Bestellung weitergehen
                </p>
            </div>
        </div>
    </div>
</div>


<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 10.5,
        breakpoints: {
            // when window width is >= 320px
            320: {
            slidesPerView: 3.5,
            },
            // when window width is >= 480px
            480: {
            slidesPerView: 5.5,
            },
            // when window width is >= 640px
            640: {
            slidesPerView: 7.5,
            },
            900: {
            slidesPerView: 10.5,
            },
            1200: {
            slidesPerView: 12.5,
            }
        }
    });

    function selectShowCatTaNewProds(kId){
        if(openCatTaNewPro != 0){
            $('#taNewProdCat'+openCatTaNewPro+'Prods').attr('style','width:100%; max-height:534px; overflow-y:scroll; display:none;');
            $('#taNewProdCatImg'+openCatTaNewPro).removeClass('taNewProCatSel');
        }
        openCatTaNewPro = kId;
        $('#taNewProdCat'+kId+'Prods').attr('style','width:100%; max-height:534px; overflow-y:scroll; display:flex;');
        $('#taNewProdCatImg'+kId).addClass('taNewProCatSel');
    }
    </script>






































@foreach(Takeaway::where('toRes', '=', Auth::user()->sFor)->get() as $prod)
    <div class="modal modalProd" id="ProdNewTATemp{{$prod->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="border-radius:30px;">
                <div class="modal-body" id="ProdNewTATemp{{$prod->id}}Body">

                    <?php
                        if(Carbon::now()->format('H:i') >= '20:00' || Carbon::now()->format('H:i') <= '03:00'){
                            if($prod->qmimi2 != 999999){
                                $starterPrice = sprintf('%01.2f', $prod->qmimi2); 
                            }else{ $starterPrice = sprintf('%01.2f', $prod->qmimi); }
                        }else{ $starterPrice = sprintf('%01.2f', $prod->qmimi); }

                        $plateNr = kategori::find($prod->kategoria)->forPlate;
                        if($plateNr != NULL){
                            $plateData = resPlates::where([['toRes',$prod->toRes],['desc2C',$plateNr]])->first();
                        }
                    ?>

                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-10">
                                <h4 class="modal-title">{{$prod->emri}} <span style="color:lightgray;">({{kategori::find($prod->kategoria)->emri}})</span></h4>
                            </div>
                            <div class="col-2 text-right">
                                <button type="button" class="btn shadow-none">
                                    <i onclick="prodModalCancelMenu('{{$prod->id}}','{{$starterPrice}}','1')" style="width:6px;" class="far fa-2x fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12"><p>{{$prod->pershkrimi}}</p></div>
                        </div>
                        <div class="row" style="border-bottom:1px solid lightgray;">
                            <div class="col-2 text-right mt-3"><span class=" opacity-65">{{__('adminP.currencyShow')}}</span></div>
                            <div class="col-4 text-left" style="margin-left:-20px;">
                                <input class="form-control color-qrorpa" style="border:none; font-size:20px;" onkeyup="setNewPriceProd(this.value, '{{$prod->id}}')"
                                    id="TotPrice{{$prod->id}}" type="number" min="0" step="0.01" value="{{$starterPrice}}">
                            </div>
                            <div class="col-2 text-left mt-2" style="font-size:9px; color:rgb(39,190,175); padding:0px 0px 0px 7px" id="changePriceInfo{{$prod->id}}">
                                 < Sie können den Preis ändern
                            </div>
                            <div class="col-4 text-right" >
                                <div class="text-right d-flex">
                                    <span id="minusSasiaPerProd{{$prod->id}}" style="font-size:30px; display:none;" class="pr-3 plusForOrder" onclick="removeOneToSasiaPro('{{$prod->id}}')" >-</span>
                                    <span id="placeholderSasiaPerProd{{$prod->id}}" style="font-size:30px; color:white;" class="pr-3 plusForOrder">-</span>
                                    <input type="number" min="1" step="1" value="1" class="text-center" id="sasiaPerProd{{$prod->id}}"style="border:none;  width:40px; font-size:28px; height:fit-content;" disabled>
                                    <span style="font-size:30px;" class="pl-3 plusForOrder" onclick="addOneToSasiaPro('{{$prod->id}}')">+</span>
                                </div>
                            </div>

                            <div class="col-12 alert alert-danger text-center mt-1" style="display:none;" id="setNewPriceProdError01{{$prod->id}}">
                                Dieser Betrag ist nicht gültig!
                            </div>
                        </div>
                        
                        <div class="row" style="width:120%; margin-left:-10%;">
                            <?php
                                if(count(explode('--0--', $prod->type)) > 0 && $prod->type != NULL){
                                    foreach(explode('--0--', $prod->type) as $alll){ if($alll != '' && $alll != NULL){ $hasOneT = true; } }
                                    if(isset($hasOneT) && $hasOneT){$hasOneTVal = 1;}else{ $hasOneTVal = 0; }
                                }else{$hasOneTVal = 0;}
                            ?>
                            <input type="hidden" value="{{$hasOneTVal}}" id="hasTypeThisPro{{$prod->id}}">

                            <div class="col-12 text-center" style="width:120%;">
                                @if($prod->type != NULL && count(explode('--0--', $prod->type)) > 0)
                                    @if(count(explode('--0--', $prod->type)) > 5)
                                        @if(isset($_GET['Res']) && $_GET['Res'] == 16)
                                            <p onclick="showTypeMenu('{{$prod->id}}')" class="hover-pointer"><strong>{{__('adminP.taste')}}</strong></p>
                                            <hr>
                                        @else
                                            <p onclick="showTypeMenu('{{$prod->id}}')" class="hover-pointer color-qrorpa mt-2" style="margin-top:-10px; margin-bottom:-3px; font-size:larger;">
                                            <strong>{{__('adminP.type')}}</strong></p>
                                        @endif
                                    @else
                                        <p  class="hover-pointer color-qrorpa mt-2" style="margin-top:-10px; margin-bottom:-3px; font-size:larger;">
                                        <strong>{{__('adminP.type')}}</strong></p>
                                    @endif
                                    <?php $priType = 1?>
                                    @foreach(explode('--0--', $prod->type) as $alll)
                                        @if($alll != '')
                                            
                                            <?php $thisType = LlojetPro::find(explode('||',$alll)[0]); ?>
                                                @if($thisType != null)
                                                <div class="color-text container-fluid mt-2 firstTwoTypes{{$prod->id}}">
                                                    <div class="row ml-1" style="margin-bottom:-15px;">
                                                                    
                                                        <div class="col-3 text-left">
                                                            <label class="switch ">
                                                                    <?php
                                                                        if(Carbon::now()->format('H:i') > '20:00'){
                                                                            if($thisType->id == 106 && ($prod->id == 512 || $prod->id == 514)){
                                                                                $vleraType = number_format(1.4, 2, '.', '') ;
                                                                            }else  if($thisType->id == 107 && ($prod->id == 512 || $prod->id == 514)){
                                                                                $vleraType = number_format(2.6, 2, '.', '') ;
                                                                            }else{
                                                                                $vleraType = $thisType->vlera;
                                                                            }
                                                                        }else{
                                                                            $vleraType = $thisType->vlera;
                                                                        }

                                                                        $tyIdForClick01 = 'llojetPE'.$thisType->id.'O'.$prod->id;
                                                                    ?>
                                                                    <input style="width:5px;" type="checkbox" class="primary allProTypesMenu allProTypes{{$prod->id}}" id="llojetPE{{$thisType->id}}O{{$prod->id}}" 
                                                                                onchange="addThis2('{{$tyIdForClick01}}','{{$prod->id}}','{{$thisType->emri}}','{{$vleraType}}','{{$starterPrice}}','False')">
                                                                            <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                        </label>
                                                        </div>
                                                        <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThis2('{{$tyIdForClick01}}','{{$prod->id}}','{{$thisType->emri}}','{{$vleraType}}','{{$starterPrice}}','True')">
                                                            <p style="width:70%;" class="text-left"><strong>{{$thisType->emri}}</strong></p>
                                                            <p style="width:30%;" class="text-right"> {{sprintf('%01.2f', ($vleraType * $starterPrice)) }}<sup>{{__('adminP.currencyShow')}}</sup></p>
                                                                       
                                                        </div>
                                                                    
                                                    </div> 
                                                </div>
                                                @endif
                                            @if($priType++ == 5)
                                                @break
                                            @endif
                                        @endif
                                    @endforeach
                                    @if(count(explode('--0--', $prod->type)) > 5)
                                        <p onclick="showTypeMenu('{{$prod->id}}')" class="text-left pl-5 hover-pointer threeDotsType{{$prod->id}}"
                                          style="font-size:25px; margin-top:-15px;"><span class="color-qrorpa" style="font-size:16px;">{{__('adminP.more')}} . . .</span> </p>
                                    @endif
                     
                                @endif
                            </div>
                            <div class="col-12 mt-2">
                                <?php
                                    $countNewTypes = 1;
                                    if($prod->type != NULL && count(explode('--0--', $prod->type)) > 0){
                                        foreach(explode('--0--', $prod->type) as $llP){
                                            if($countNewTypes++ > 5){
                                                if(!empty($llP)){
                                                    $thisType = LlojetPro::find(explode('||',$llP)[0]);
                                                    if(!empty($thisType)){

                                                          
                                                        if(Carbon::now()->format('H:i') > '20:00'){
                                                            if($thisType->id == 106 && ($prod->id == 512 || $prod->id == 514)){$vleraType = number_format(1.4, 2, '.', '');
                                                            }else  if($thisType->id == 107 && ($prod->id == 512 || $prod->id == 514)){$vleraType = number_format(2.6, 2, '.', '');
                                                            }else{$vleraType = $thisType->vlera;}
                                                        }else{ $vleraType = $thisType->vlera;}
                                                        $tyIdForClick02 = 'llojetPE'.$thisType->id.'O'.$prod->id;
                                                        echo '
                                                        <div class="color-text container AllTypesToHide IDType'.$prod->id.'">
                                                            <div class="row ml-1">
                                                                <div class="col-3 text-left">
                                                                    <label class="switch ">
                                                                        <input style="width:5px;" type="checkbox" class="primary allProTypesMenu allProTypes'.$prod->id.'" id="llojetPE'.$thisType->id.'O'.$prod->id.'"
                                                                            onchange="addThis2(\''.$tyIdForClick02.'\',\''.$prod->id.'\',\''.$thisType->emri.'\',\''.$vleraType.'\',\''.$starterPrice.'\',\'False\')">
                                                                        <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThis2(\''.$tyIdForClick02.'\',\''.$prod->id.'\',\''.$thisType->emri.'\',\''.$vleraType.'\',\''.$starterPrice.'\',\'True\')">
                                                                    <p style="width:70%;" class="text-left"><strong>'.$thisType->emri.'</strong></p>
                                                                    <p style="width:30%;" class="text-right"> '.sprintf('%01.2f', ($vleraType * $starterPrice)) .'<sup>'.__("adminP.currencyShow"). '</sup></p>
                                                                </div>
                                                            </div> 
                                                        </div>';
                                                    }
                                                }
                                            }
                                        }
                                        echo '<input type="hidden" value="1" id="hasTypeThisPro'.$prod->id.'">';
                                    }else{
                                        echo '<input type="hidden" value="0" id="hasTypeThisPro'.$prod->id.'">';
                                    }
                                ?>
                            </div>

                             <!-- Extras -->
                            <div class="col-12 text-center">
                                @if(count(explode('--0--', $prod->extPro)) > 0)
                                    <?php $priExt = 1?>
                                    @foreach(explode('--0--', $prod->extPro) as $alll)
                                        @if($alll != '')
                                            @if(count(explode('--0--', $prod->extPro)) > 5)
                                                @if($priExt == 1)
                                                <hr>
                                                    <p onclick="showExtraMenu('{{$prod->id}}')" class="hover-pointer color-qrorpa"
                                                     style="margin-top:-10px; margin-bottom:-3px; font-size:larger;" >
                                                        <strong>{{__('adminP.extras')}}</strong></p>
                                                @endif
                                            @else
                                                @if($priExt == 1)
                                                <hr>
                                                    <p class="hover-pointer color-qrorpa" style="margin-top:-10px; margin-bottom:-3px; font-size:larger;" >
                                                        <strong>{{__('adminP.extras')}}</strong>
                                                    </p>
                                                @endif
                                            @endif
                                            <?php $thisExtra = ekstra::find(explode('||',$alll)[0]);
                                            $exIdForClick01 = 'extPE'.$thisExtra->id.'O'.$prod->id;
                                            ?>
                                                @if($thisExtra != null)
                                                    <div class="color-text container ">
                                                        <div class="row ml-1">
                                                            <div class="col-3 text-left">
                                                                <label class="switch ">
                                                                    <input style="width:5px;" type="checkbox" class="primary allProExtrasMenu allProExtras{{$prod->id}}" id="extPE{{$thisExtra->id}}O{{$prod->id}}" 
                                                                        onchange="addThis('{{$exIdForClick01}}','{{$prod->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','False')">
                                                                    <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-9 text-left d-flex" style="margin-left:-35px;" onclick="addThis('{{$exIdForClick01}}','{{$prod->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','True')">
                                                                <p style="width:70%;" class="text-left"><strong>{{$thisExtra->emri}}</strong></p>
                                                              
                                                                <p style="width:30%;" class="text-right"><span class="price{{$prod->id}}"> {{sprintf('%01.2f', $thisExtra->qmimi)}}</span><sup>{{__('adminP.currencyShow')}}</sup></p> 
                                                            </div>
                                                        </div> 
                                                    </div>
                                                @endif
                                            @if($priExt++ == 5)
                                                @break
                                            @endif
                                        @endif
                                    @endforeach
                                    @if(count(explode('--0--', $prod->extPro)) > 5)
                                        <p onclick="showExtraMenu('{{$prod->id}}')" class="text-left pl-5 hover-pointer threeDotsExt{{$prod->id}}"
                                          style="font-size:25px; margin-top:-15px; font-weight:bold;"><span class="color-qrorpa" style="font-size:16px;">{{__('adminP.more')}} . . .</span> </p>
                                    @endif
                                @endif
                            </div>
                            <div class="col-12">
                                <?php
                                    $countNewEtx = 1;
                                    $extras = explode('--0--', $prod->extPro);
                                    foreach($extras as $extP){
                                        if($countNewEtx++ > 5){
                                            if(!empty($extP)){
                                                $thisExtra = ekstra::find(explode('||',$extP)[0]);
                                                if($thisExtra != null){
                                                    $exIdForClick02 = 'extPE'.$thisExtra->id.'O'.$prod->id;
                                                    echo '
                                                    <div class="color-text container AllExtrasToHide IDExtra'.$prod->id.'">
                                                        <div class="row ml-1">
                                                            <div class="col-3 text-left">
                                                                <label class="switch ">
                                                                    <input style="width:5px;" type="checkbox" class="primary allProExtrasMenu allProExtras'.$prod->id.'" id="extPE'.$thisExtra->id.'O'.$prod->id.'" 
                                                                        onchange="addThis(\''.$exIdForClick02.'\',\''.$prod->id.'\',\''.$thisExtra->emri.'\',\''.$thisExtra->qmimi.'\',\'False\')">
                                                                    <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-9 d-flex text-left" style="margin-left:-35px;" onclick="addThis(\''.$exIdForClick02.'\',\''.$prod->id.'\',\''.$thisExtra->emri.'\',\''.$thisExtra->qmimi.'\',\'True\')">
                                                                <p style="width:70%;" class="text-left"><strong>'.$thisExtra->emri.'</strong></p>
                                                                <p style="width:30%;" class="text-right"><span class="price'.$prod->id.'"> '.sprintf('%01.2f', $thisExtra->qmimi).'</span><sup>'.__("adminP.currencyShow").'</sup> </p>
                                                            </div>
                                                                
                                                        </div> 
                                                    </div>';
                                                }
                                            }
                                        }
                                    }
                                ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <?php
                                echo '
                              
                                <div class="form-group mt-2 mb-2">
                                    <textarea placeholder="'.__("adminP.comment").'..." name="koment" style="font-size:16px;"
					                id="komentMenuAjax'.$prod->id.'" class="form-control shadow-none" rows="3"></textarea>
                                </div>
                                    <button type="button" class="btn btn-block" 
                                    ';
                                    if($hasOneTVal == 0){
                                        echo ' data-dismiss="modal" '; 
                                    }
                                    echo '
                                        style="background-color:rgb(39,190,175); color:white; border-radius:30px; position:fixed; height:70px; width:40%; bottom:50px;
                                        right:30%; left:30%; font-size:22px;" id="sendOrderBtn'.$prod->id.'"
                                        onclick="saveNewOrder('.$prod->id.','.$sndNotiActv.')">'.__("adminP.addToOrder").'</button>
                                    ';
                            ?>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between" style="width: 100%; margin-bottom:6rem;" id="allPlates{{$prod->id}}">
                            @if ($plateNr != NULL && $plateNr != 0 && $plateData != NULL)
                                <h4><strong>Standardgericht: {{ $plateData->nameTitle }}</strong></h4>
                            @endif
                            @foreach (resPlates::where('toRes',$prod->toRes)->get() as $plOne)
                                @if($plateNr != NULL && $plateNr != 0 && $plateNr == $plOne->desc2C)
                                    <button style="width:49%; margin:0px;" class="btn btn-dark shadow-none mb-1 setNewPlateBtnAll"
                                    id="setNewPlateBtn{{$prod->id}}O{{$plOne->id}}" onclick="setNewPlate('{{$prod->id}}','{{$plOne->id}}','1')">
                                        <strong><span class="mr-1">P.{{$plOne->desc2C}}</span> {{$plOne->nameTitle}}</strong>
                                    </button>
                                @else
                                    <button style="width:49%; margin:0px;" class="btn btn-outline-dark shadow-none mb-1 setNewPlateBtnAll" 
                                    id="setNewPlateBtn{{$prod->id}}O{{$plOne->id}}" onclick="setNewPlate('{{$prod->id}}','{{$plOne->id}}','2')">
                                        <strong><span class="mr-1">P.{{$plOne->desc2C}}</span> {{$plOne->nameTitle}}</strong>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="ProdNewTATemp{{$prod->id}}Footer">

                    <input type="hidden" name="qmimiBaze" id="ProdAddQmimiBaze{{$prod->id}}" value="{{$starterPrice}}">
                    <input type="hidden" name="id"  value="{{$prod->id}}">
                    <input type="hidden" name="emri" id="ProdAddEmri{{$prod->id}}" value="{{$prod->emri}}">
                    <input type="hidden" name="qmimi" id="ProdAddQmimi{{$prod->id}}" value="{{$starterPrice}}">
                    <input type="hidden" name="pershkrimi" id="ProdAddPershk{{$prod->id}}" value="{{$prod->pershkrimi}}">
                    <input type="hidden" name="extra" id="ProdAddExtra{{$prod->id}}" value="">
                    <input type="hidden" name="llojet" id="ProdAddLlojet{{$prod->id}}" value="">
                    <input type="hidden" name="kategoria" id="ProdAddKategoria{{$prod->id}}" value="{{$prod->kategoria}}">
                    <input type="hidden" name="sasia" id="sasiaProd{{$prod->id}}" value="1">
                    <input type="hidden" name="plateFor" id="plateFor{{$prod->id}}" value="0">

                    <input type="hidden" class="resVal" id="res" name="res" value="{{Auth::user()->sFor}}">
                    <input type="hidden" class="tVal" id="t" name="t" value="1">
                </div>
            </div>
        </div>
    </div>
@endforeach

























@foreach(Takeaway::where('toRes', '=', Auth::user()->sFor)->where('type','!=',NULL)->get() as $ketoProd)
                                                     
        <!--openTypeSelect Modal -->
        <div class="modal" id="openTypeSelect{{$ketoProd->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top: 7%; z-index:9999;" aria-modal="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        @foreach (explode('--0--',$ketoProd->type) as $tPrTypeOne)
                            <?php 
                                $tPrTypeOne2D = explode('||',$tPrTypeOne); 
                                $tPrTheT = LlojetPro::find($tPrTypeOne2D[0]);
                                if($tPrTheT != NULL){
                                $newPrice009 = sprintf("%01.2f", $ketoProd->qmimi*$tPrTheT->vlera);
                                }
                            ?>
                            @if($tPrTheT != NULL)
                            <button style="background-color:rgb(39,190,175); color:white; width:100%; margin:0px; font-size: 1.2rem"
                                class="btn mb-2 d-flex shadow-none" onclick="addToCartExpressWithT('{{$ketoProd->id}}','{{$tPrTypeOne2D[0]}}','{{$newPrice009}}','{{$sndNotiActv}}')">
                                <span style="width:49%;" class="text-center"><strong>{{$tPrTheT->emri}}</strong></span>
                                <span style="width:49%;" class="text-center"><strong>{{$newPrice009}} <sup>CHF</sup></strong></span>
                            </button>
                            @endif
                        @endforeach
                        <button class="btn btn-outline-danger mt-3" style="width:100%; margin:0px;" onclick="openTySelReset('{{$ketoProd->id}}')">
                            <strong>Absagen</strong>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    
@endforeach


















@include('adminPanel.taTempProd.payMethodsScript')
@include('adminPanel.taTempProd.payCashModal')
@include('adminPanel.taTempProd.payRechnungModal')










<script>

    function addToCartExpressTATemp(taProId,notiAct){
        $('#addToCartExpressBtn'+taProId).html('<img style="width:32.4px; height:auto;" src="storage/gifs/loading2.gif">');
        if(notiAct == 1){
            if(hasAddSoundSelected == 1){
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg21Sound->soundTitle}}.{{$usrNotiReg21Sound->soundExt}}" autoplay="true"></audio>');
            }else{
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
            }
        }
        $.ajax({
			url: '{{ route("tempTAProds.storeExpresWOT") }}',
			method: 'post',
			data: {
				tpId: taProId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#addOrForTAModalBody2Right").load(location.href+" #addOrForTAModalBody2Right>*","");
                $('#addToCartExpressBtn'+taProId).html('<i class="fas fa-2x fa-cart-plus" style="color: green;"></i>');
			},
			error: (error) => { console.log(error); }
		});
    }

    function addToCartExpressWithT(taProId,tyId,priceByType,notiAct){
        $('#addToCartExpressBtn'+taProId).html('<img style="width:32.4px; height:auto;" src="storage/gifs/loading2.gif">');
        if(notiAct == 1){
            if(hasAddSoundSelected == 1){
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg21Sound->soundTitle}}.{{$usrNotiReg21Sound->soundExt}}" autoplay="true"></audio>');
            }else{
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
            }
        }
        $('#openTypeSelect'+taProId).modal('hide');
        $('body').addClass('modal-open');
        $.ajax({
			url: '{{ route("tempTAProds.storeExpresWT") }}',
			method: 'post',
			data: {
				pid: taProId,
                typeId: tyId,
                newPrice: priceByType,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#addOrForTAModalBody2Right").load(location.href+" #addOrForTAModalBody2Right>*","");
                $('.AllExtrasToHide').hide();
                $('.AllTypesToHide').hide();
                $('#addToCartExpressBtn'+taProId).html('<i class="fas fa-2x fa-cart-plus" style="color: green;"></i>');
			},
			error: (error) => { console.log(error); }
		});
    }

    function deleteTempTAProd(taTempProdId){
        $('#deleteTempTAProdBtn'+taTempProdId).html('<img style="width:32.4px; height:auto;" src="storage/gifs/loading2.gif">');
        $('#deleteTempTAProdBtn'+taTempProdId).prop('disabled', true);
        $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
        $.ajax({
			url: '{{ route("tempTAProds.deleteTempOrder") }}',
			method: 'post',
			data: {
				insId: taTempProdId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#addOrForTAModalBody2Right").load(location.href+" #addOrForTAModalBody2Right>*","");
                // $('#deleteTempTAProdBtn'+taTempProdId).html('<i class="fa-solid fa-delete-left"></i>');
                $('#deleteTempTAProdBtn'+taTempProdId).prop('disabled', false);
			},
			error: (error) => { console.log(error); }
		});
    }

    function deleteTempTAProdAll(){
        $('#readyToPayBtnCancel').html('<img style="width:32.4px; height:auto;" src="storage/gifs/loading2.gif">');
        $('#readyToPayBtnCancel').prop('disabled', true);
        $('#readyToPayBtnContinue').html('<img style="width:32.4px; height:auto;" src="storage/gifs/loading2.gif">');
        $('#readyToPayBtnContinue').prop('disabled', true);
        $.ajax({
			url: '{{ route("tempTAProds.deleteTempOrderAll") }}',
			method: 'post',
			data: {
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#addOrForTAModalBody2Right").load(location.href+" #addOrForTAModalBody2Right>*","");
			},
			error: (error) => { console.log(error); }
		});
    
    }

    function saveNewOrder(pId,notiAct){
        if($('#hasTypeThisPro'+pId).val() == 1 && !$('#ProdAddLlojet'+pId).val()){
            $('#addTypePlease').show(200).delay(2500).hide(200);
        }else{
            // console.log($('#komentMenuAjax'+pId).val());
            if(notiAct == 1){ 
                if(hasAddSoundSelected == 1){
                    $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg21Sound->soundTitle}}.{{$usrNotiReg21Sound->soundExt}}" autoplay="true"></audio>');
                }else{
                    $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>');
                }
            }
            $.ajax({
                url: '{{ route("tempTAProds.storeTempOrder") }}',
                method: 'post',
                data: {
                    prodId: pId,
                    resN: '{{Auth::user()->sFor}}',
                    name: $('#ProdAddEmri'+pId).val(),
                    persh: $('#ProdAddPershk'+pId).val(),
                    sasia: $('#sasiaProd'+pId).val(),
                    qmimi: $('#ProdAddQmimi'+pId).val(),
                    ekstra: $('#ProdAddExtra'+pId).val(),
                    types: $('#ProdAddLlojet'+pId).val(),
                    komm: $('#komentMenuAjax'+pId).val(),
                    plate: $('#plateFor'+pId).val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#addOrForTAModalBody2Right").load(location.href+" #addOrForTAModalBody2Right>*","");

                    $('#ProdNewTATemp'+pId).modal('hide');
                    $('body').addClass('modal-open');

                    $("#ProdNewTATemp"+pId+"Body").load(location.href+" #ProdNewTATemp"+pId+"Body>*","");
                    $("#ProdNewTATemp"+pId+"Footer").load(location.href+" #ProdNewTATemp"+pId+"Footer>*","");
                },
                error: (error) => { console.log(error); }
            });
        }
    }





































    function prodModalCancelMenu(prodId,prodQ,cancel){
        // console.log($('#canceled').val());
        if(cancel == 1){
            $('#ProdNewTATemp'+prodId).modal('hide');
        }
        $('body').addClass('modal-open');

        $(".allProTypesMenu").prop( "checked", false );
        $(".allProExtrasMenu").prop( "checked", false );

        // var TotPrice = document.getElementById('TotPrice' + prodId);
        var TotPrice = $('#TotPrice' + prodId);
        var QmimiProd = document.getElementById('ProdAddQmimi' + prodId);
        var QmimiBaze = document.getElementById('ProdAddQmimiBaze' + prodId);
        var QmimiBazeValue = parseFloat(QmimiBaze.value);

        if (last != '') {
        document.getElementById(last).checked = false;
        }
        if (lastV != 0) {
            var prices = document.getElementsByClassName("price" + prodId);
            for (var i = 0; i < prices.length; i++) {
                var newV = parseFloat(prices.item(i).innerText) / lastV;
                prices.item(i).innerText = newV.toFixed(2);
            }
            var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
            var newTot = (extPrice / lastV) + parseFloat(prodQ);
            TotPrice.value = newTot.toFixed(2);

            var extrasToSave = TotPrice.value - QmimiBazeValue;

            TotPrice.value = ((QmimiBazeValue / lastV) + extrasToSave).toFixed(2);
            QmimiProd.value = parseFloat(TotPrice.value).toFixed(2);
        }
        $('#TotPrice'+prodId).val((QmimiBazeValue).toFixed(2));
        $('#ProdAddQmimi'+prodId).val((QmimiBazeValue).toFixed(2));

        $('#ProdAddExtra'+prodId).val('');
        $('#ProdAddLlojet'+prodId).val('');
        $('#komentMenuAjax'+prodId).val('');

            last = "";
            lastV = 0;

        $("#ProdNewTATemp"+prodId+"Body").load(location.href+" #ProdNewTATemp"+prodId+"Body>*","");
        $("#ProdNewTATemp"+prodId+"Footer").load(location.href+" #ProdNewTATemp"+prodId+"Footer>*","");
    }

    // -------------------------------------------------------------------------------------------------------------------------------------------

    function setNewPriceProd(newP, pId){
        if(newP != '' && newP != ' '){
            thePr = parseFloat(newP);
            if(thePr > 0){
                if($('#setNewPriceProdError01'+pId).is(':visible')){ $('#setNewPriceProdError01'+pId).hide(50); }
                $('#changePriceInfo'+pId).html('<i class="fas fa-2x fa-undo btn" onclick="returnOriginalPrice(\''+pId+'\')"></i>');
                $('#ProdAddQmimi'+pId).val(thePr);
                $('#sendOrderBtn'+pId).attr('disabled',false);
            }else{
                if($('#setNewPriceProdError01'+pId).is(':hidden')){ $('#setNewPriceProdError01'+pId).show(50); }
                $('#sendOrderBtn'+pId).attr('disabled',true);
            }
        }else{
            if($('#setNewPriceProdError01'+pId).is(':hidden')){ $('#setNewPriceProdError01'+pId).show(50); }
            $('#sendOrderBtn'+pId).attr('disabled',true);
        }
    }
    function returnOriginalPrice(proId){
        if(!$('#ProdAddExtra'+proId).val() && !$('#ProdAddLlojet'+proId).val()){
            $('#ProdAddQmimi'+proId).val(parseFloat($('#ProdAddQmimiBaze'+proId).val()).toFixed(2));
            $('#TotPrice'+proId).val(parseFloat($('#ProdAddQmimiBaze'+proId).val()).toFixed(2));
            $('#sendOrderBtn'+proId).attr('disabled',false);

            $('#changePriceInfo'+proId).html('< Sie können den Preis ändern');
            if($('#setNewPriceProdError01'+proId).is(':visible')){ $('#setNewPriceProdError01'+proId).hide(50); }
        }else{
            // alert('needs more work...');
            var qBazeReset = parseFloat($('#ProdAddQmimiBaze'+proId).val()).toFixed(2);
            var tipiReset = $('#ProdAddLlojet'+proId).val();
            var extraReset = $('#ProdAddExtra'+proId).val();
            var qNewReset = qBazeReset;
            if(tipiReset != '' && tipiReset != ' '){
                var tipiResetVlera = tipiReset.split('||')[1];
                qNewReset = parseFloat(parseFloat(tipiResetVlera).toFixed(2) * parseFloat(qBazeReset).toFixed(2)).toFixed(2);
            }
            if(extraReset != '' && extraReset != ' '){
                var extraResetArrayOne = extraReset.split('--0--');
                $.each(extraResetArrayOne, function (key, val) {
                    extraResetPrice = val.split('||')[1];
                    qNewReset = parseFloat(parseFloat(qNewReset) + parseFloat(extraResetPrice)).toFixed(2);
                });
            }

            $('#ProdAddQmimi'+proId).val(parseFloat(qNewReset).toFixed(2));
            $('#TotPrice'+proId).val(parseFloat(qNewReset).toFixed(2));
            $('#sendOrderBtn'+proId).attr('disabled',false);

            $('#changePriceInfo'+proId).html('< Sie können den Preis ändern');
            if($('#setNewPriceProdError01'+proId).is(':visible')){ $('#setNewPriceProdError01'+proId).hide(50); }
        }
    }

    // -----------------------------------------------------------------------------------------------------------------------------------------------------------------

    function addOneToSasiaPro(pId){
        var cVal = parseInt($('#sasiaPerProd'+pId).val());
        var newVal = cVal + 1;
        $('#sasiaPerProd'+pId).val(newVal);
        $('#sasiaProd'+pId).val(newVal);
        $('#minusSasiaPerProd'+pId).show();
        $('#placeholderSasiaPerProd'+pId).hide();
        
    }
    function removeOneToSasiaPro(pId){
        var cVal = parseInt($('#sasiaPerProd'+pId).val());
        var newVal = cVal - 1;
        $('#sasiaPerProd'+pId).val(newVal);
        $('#sasiaProd'+pId).val(newVal);
        if(newVal == 1){
            $('#minusSasiaPerProd'+pId).hide();
            $('#placeholderSasiaPerProd'+pId).show();
        }else{
            $('#minusSasiaPerProd'+pId).show();
            $('#placeholderSasiaPerProd'+pId).hide();
        }
    }

    // -----------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    function showExtraMenu(prodId) {
        if ($('.IDExtra' + prodId).is(":visible")) {
            $('.IDExtra' + prodId).hide();
            $('.threeDotsExt' + prodId).show();
        } else {
            $('.IDExtra' + prodId).show();
            $('.threeDotsExt' + prodId).hide();
        }
    }

    function showTypeMenu(prodId) {
        if ($('.IDType' + prodId).is(":visible")) {
            $('.IDType' + prodId).hide();
            $('.threeDotsType'+prodId).show();
        } else {
            $('.IDType' + prodId).show();
            $('.threeDotsType'+prodId).hide();
        }
    }

    // -----------------------------------------------------------------------------------------------------------------------------------------------------------------

    function addThis2(theId, prodId, name, value, prodQ, nameClick) {
        var checkBox = document.getElementById(theId);
        var LlojetPro = document.getElementById('ProdAddLlojet' + prodId);
        var TotPrice = document.getElementById('TotPrice' + prodId);
        var ExtraCart = document.getElementById('ProdAddExtra' + prodId);
        var ExtraCartV = document.getElementById('ProdAddExtra' + prodId).value;
        var QmimiProd = document.getElementById('ProdAddQmimi' + prodId);

        var QmimiBaze = document.getElementById('ProdAddQmimiBaze' + prodId);
        var QmimiBazeValue = parseFloat(QmimiBaze.value);

        var type = name + '||' + value;

        if (last != '') {
            document.getElementById(last).checked = false;
        }
        if (lastV != '') {
            
            var prices = document.getElementsByClassName("price" + prodId);
            for (var i = 0; i < prices.length; i++) {
                var newV = parseFloat(prices.item(i).innerText);
                prices.item(i).innerText = newV.toFixed(2);
            }

            var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
            var newTot = (extPrice / lastV) + parseFloat(prodQ);
            TotPrice.value = newTot.toFixed(2);

            var extrasToSave = TotPrice.value - QmimiBazeValue;

            TotPrice.value = ((QmimiBazeValue / lastV) + extrasToSave).toFixed(2);
            QmimiProd.value = parseFloat(TotPrice.value).toFixed(2);
        }

        if(nameClick != 'False'){
            if(!$('#'+theId).is(":checked")){
                $('#'+theId).prop('checked', true);
            }else{
                $('#'+theId).prop('checked', false);
            }
        }

        if (checkBox.checked == true) {
            LlojetPro.value = type;

            $('#sendOrderBtn'+prodId).attr("data-dismiss","modal");

            var prices = document.getElementsByClassName("price" + prodId);
            for (var i = 0; i < prices.length; i++) {
                var newV = parseFloat(prices.item(i).innerText);

                prices.item(i).innerText = newV.toFixed(2);
                prices.item(i).disabled = true;
            }

            var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
            var newTot = (extPrice * value) + parseFloat(prodQ);
            TotPrice.value = newTot.toFixed(2);

            var stepRe = 1;
            var plusExt = 0;

            var plusExt = parseFloat(0);
            if (ExtraCartV != '') {
                var extras = ExtraCartV.split('--0--');

                for (var i = 0; i < extras.length; i++) {
                    if(extras[i] != ''){
                        var extras2D = extras[i].split('||');

                        var newQ = parseFloat(extras2D[1]).toFixed(2);
                        
                        if (stepRe++ == 1) {
                            ExtraCart.value = extras2D[0] + '||' + newQ;
                        } else {
                            ExtraCart.value = ExtraCart.value + '--0--' + extras2D[0] + '||' + newQ;
                        }
                        plusExt = parseFloat(parseFloat(newQ)+parseFloat(plusExt));
                    }

                }
                QmimiProd.value = document.getElementById('TotPrice' + prodId).value;
                // Qikj spo shkon numer 
            }

            TotPrice.value = parseFloat((QmimiBazeValue * value) + plusExt).toFixed(2);
            var tot = TotPrice.value;
            QmimiProd.value = parseFloat(tot).toFixed(2);

            last = theId;
            lastV = value;
        } else {
            $('#sendOrderBtn'+prodId).removeAttr("data-dismiss");

            LlojetPro.value = '';
            lastV = 0;
            last = "";

        }

        // alert($('#ProdAddLlojet'+prodId).val())
    }


    function addThis(theId, prodId, name, price, nameClick) {

        // 3

        var qmimiShow = document.getElementById('TotPrice' + prodId);
        var checkBox = document.getElementById(theId);

        var AddProdQmimi = document.getElementById('ProdAddQmimi' + prodId);
        var AddProdExtra = document.getElementById('ProdAddExtra' + prodId);
        var AddProdExtraValue = document.getElementById('ProdAddExtra' + prodId).value;


        var extras = name + '||' + parseFloat(price).toFixed(2);

        if(nameClick != 'False'){
            if(!$('#'+theId).is(":checked")){
                $('#'+theId).prop('checked', true);
            }else{
                $('#'+theId).prop('checked', false);
            }
        }

        if (checkBox.checked == true) {
            var newValue = parseFloat(qmimiShow.value) + parseFloat(price);
            newValue = newValue.toFixed(2);
            qmimiShow.value = newValue;
            AddProdQmimi.value = newValue;
            if (AddProdExtraValue == "") {
                AddProdExtra.value = extras;
            } else {
                AddProdExtra.value = AddProdExtraValue + '--0--' + extras;
            }


        } else {
            var newValue = parseFloat(qmimiShow.value) - parseFloat(price);
            newValue = newValue.toFixed(2);
            qmimiShow.value = newValue;
            AddProdQmimi.value = newValue;

            var DeletedVal = AddProdExtraValue.replace(extras, '');
            AddProdExtra.value = DeletedVal;

        }
    }
    // -----------------------------------------------------------------------------------------------------------------------------------------------------------------
























    function closeAddOrForTAModal(){
        $('#addOrForTAModal').modal('toggle');
    }


    // -------------------------------------------------------------------------------------------------------------------------------------------
    function newProductOrPageSearchProd(){
        var res= '{{Auth::user()->sFor}}';
        if(!$('#newProductOrPageSearchProdInput').val()){
            if($('#newProductOrPageSearchProdError01').is(':hidden')){ $('#newProductOrPageSearchProdError01').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
				url: '{{ route("tempTAProds.searchProdsForTaTemp") }}',
				method: 'post',
                dataType: 'json',
				data: {
					resId: res,
                    phraseS: String($('#newProductOrPageSearchProdInput').val()),
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    var listings = "";
                    $('#newProductOrPage2ModalBody2KatShow').html('');

                    $('#newProductOrPage2ModalBody2KatShow').append('<div style="width:60%; margin-left:20%;" class="container-fluid">'); 
                    $('#newProductOrPage2ModalBody2KatShow').append('<h4 style="width:100%;" class="text-center">'+Object.keys(respo).length+' Produkte gefunden </h4>');
                    $.each(respo, function(index, prod){
                        var pershkrimi = prod.pershkrimi;
                        var dt = new Date();
                        var time = dt.getHours() + ":" + dt.getMinutes();  
                        listings =  '<div  style="width:60%; margin-left:20%;"  class="row p-2">'+
                                        '<div class="container-fluid">'+
                                            '<div class="row">'+
                                                '<div class="col-12 product-section">'+
                                                    '<div class="row">'+
                                                        '<div class="col-10" data-toggle="modal" data-target="#ProdNewTATemp'+prod.id+'">'+
                                                            '<h4 class="pull-right prod-name prodsFont color-text" style="font-weight:bold; font-size: 1.20rem ">'+prod.emri;
                                                                if(prod.restrictPro != 0){
                                                                    if(prod.restrictPro == 16){
                        listings +=                                 '<span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R16.jpeg" alt="noImg"></span>';
                                                                    }else if(prod.restrictPro == 18){
                        listings +=                                 '<span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R18.jpeg" alt="noImg"></span>';
                                                                    }
                                                                }
                        listings +=                         '</h4>';
                                                            if(pershkrimi != null){
                        listings +=                         '<p style=" margin-top:-10px; font-size:13px;">'+pershkrimi.substring(0,35);
                                                                if(pershkrimi.length>35){
                        listings +=                             '<span onclick="showTypeMenu(\''+prod.id+'\')" class="hover-pointer" style="font-size:16px;"> . . .</span>';
                                                                }
                        listings +=                         '</p>';
                                                            }
                        listings +=                         '<h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;"> {{__("adminP.currencyShow")}}  </span>';
                                                            if(time >= '20:00' || time <= '03:00'){
                                                                if(prod.qmimi2 != 999999){
                        listings +=                                 parseFloat(prod.qmimi2).toFixed(2);
                                                                }else{
                        listings +=                                 parseFloat(prod.qmimi).toFixed(2);
                                                                }
                                                            }else{
                                                                if(prod.qmimi2 != 999999){
                        listings +=                                 parseFloat(prod.qmimi).toFixed(2);
                                                                    if(time > '19:40' && time < '20:00'){
                        listings +=                                     '<span class="ml-4" style="font-size:14px;">{{__("adminP.from8Pm")}} <span style="color:gray;">{{__("adminP.currencyShow")}}</span>'+parseFloat(prod.qmimi2).toFixed(2)+' </span>';
                                                                    }
                                                                }else{
                        listings +=                                 parseFloat(prod.qmimi).toFixed(2);
                                                                }
                                                            }
                        listings +=                         '</h5>'+        
                                                        '</div>'+
                                                        '<div class="col-2 add-plus-section">'+
                                                            '<button style="margin:0px ;" class="btn mt-2 noBorder" type="button" data-toggle="modal" data-target="#ProdNewTATemp'+prod.id+'" >'+
                                                                '<i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i>'+
                                                            '</button>';
                                                            if(prod.type == null){
                        listings +=                         '<button onclick="addToCartExpressTATemp(\''+prod.id+'\')" id="addToCartExpressBtn'+prod.id+'" style="margin:0px ;" class="btn noBorder shadow-none" type="button" >';
                                                            }else{
                        listings +=                         '<button data-toggle="modal" data-target="#openTypeSelect'+prod.id+'" style="margin:0px ;" class="btn noBorder shadow-none" type="button" >';
                                                            }
                        listings +=                             '<i class="fas fa-2x fa-cart-plus" style="color: green;"></i>'+
                                                            '</button>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';

                        $('#newProductOrPage2ModalBody2KatShow').append(listings); 
                    });
                    $('#newProductOrPage2ModalBody2KatShow').append('</div>'); 

                    $('#newProductOrPageSearchProdBtn').attr('class','btn btn-outline-danger shadow-none');
                    $('#newProductOrPageSearchProdBtn').html('<i class="fas fa-backspace"></i>');
                    $('#newProductOrPageSearchProdBtn').attr('onclick','newProductOrPageSearchProdCancel()');
				},
				error: (error) => { console.log(error); }
			});
        }
    }
    function newProductOrPageSearchProdCancel(){
        $('#newProductOrPageSearchProdBtn').attr('disabled',true);
        $('#newProductOrPage2ModalBody2KatShow').html('<img style="width:30%; height:auto; margin-left:35%;" src="storage/gifs/loading.gif" alt="">'); 
        $("#addOrForTAModalBody2").load(location.href+" #addOrForTAModalBody2>*","");
    }










    function setNewPriceProd(newP, pId){
        if(newP != '' && newP != ' '){
            thePr = parseFloat(newP);
            if(thePr > 0){
                if($('#setNewPriceProdError01'+pId).is(':visible')){ $('#setNewPriceProdError01'+pId).hide(50); }
                $('#changePriceInfo'+pId).html('<i class="fas fa-2x fa-undo btn" onclick="returnOriginalPrice(\''+pId+'\')"></i>');
                $('#ProdAddQmimi'+pId).val(thePr);
                $('#sendOrderBtn'+pId).attr('disabled',false);
            }else{
                if($('#setNewPriceProdError01'+pId).is(':hidden')){ $('#setNewPriceProdError01'+pId).show(50); }
                $('#sendOrderBtn'+pId).attr('disabled',true);
            }
        }else{
            if($('#setNewPriceProdError01'+pId).is(':hidden')){ $('#setNewPriceProdError01'+pId).show(50); }
            $('#sendOrderBtn'+pId).attr('disabled',true);
        }
    }
    function returnOriginalPrice(proId){
        if(!$('#ProdAddExtra'+proId).val() && !$('#ProdAddLlojet'+proId).val()){
            $('#ProdAddQmimi'+proId).val(parseFloat($('#ProdAddQmimiBaze'+proId).val()).toFixed(2));
            $('#TotPrice'+proId).val(parseFloat($('#ProdAddQmimiBaze'+proId).val()).toFixed(2));
            $('#sendOrderBtn'+proId).attr('disabled',false);

            $('#changePriceInfo'+proId).html('< Sie können den Preis ändern');
            if($('#setNewPriceProdError01'+proId).is(':visible')){ $('#setNewPriceProdError01'+proId).hide(50); }
        }else{
            // alert('needs more work...');
            var qBazeReset = parseFloat($('#ProdAddQmimiBaze'+proId).val()).toFixed(2);
            var tipiReset = $('#ProdAddLlojet'+proId).val();
            var extraReset = $('#ProdAddExtra'+proId).val();
            var qNewReset = qBazeReset;
            if(tipiReset != '' && tipiReset != ' '){
                var tipiResetVlera = tipiReset.split('||')[1];
                qNewReset = parseFloat(parseFloat(tipiResetVlera).toFixed(2) * parseFloat(qBazeReset).toFixed(2)).toFixed(2);
            }
            if(extraReset != '' && extraReset != ' '){
                var extraResetArrayOne = extraReset.split('--0--');
                $.each(extraResetArrayOne, function (key, val) {
                    extraResetPrice = val.split('||')[1];
                    qNewReset = parseFloat(parseFloat(qNewReset) + parseFloat(extraResetPrice)).toFixed(2);
                });
            }

            $('#ProdAddQmimi'+proId).val(parseFloat(qNewReset).toFixed(2));
            $('#TotPrice'+proId).val(parseFloat(qNewReset).toFixed(2));
            $('#sendOrderBtn'+proId).attr('disabled',false);

            $('#changePriceInfo'+proId).html('< Sie können den Preis ändern');
            if($('#setNewPriceProdError01'+proId).is(':visible')){ $('#setNewPriceProdError01'+proId).hide(50); }
        }
    }

    function showProKat(kId) {
        if(crrOpenCat != 0 && crrOpenCat != kId){
            $('#prodsKatFoto' + crrOpenCat).hide(100);
            $('#state' + crrOpenCat).val(0);
        }

        if ($('#state' + kId).val() == 0) {
            $('#prodsKatFoto' + kId).show(100);
            $('#state' + kId).val(1);
            crrOpenCat = kId;
        } else {
            $('#prodsKatFoto' + kId).hide(100);
            $('#state' + kId).val(0);
            crrOpenCat = 0;
        }
    }
    function openTySelReset(pId){
        $('#openTypeSelect'+pId).modal('hide');
        $('body').addClass('modal-open');
    }


</script>