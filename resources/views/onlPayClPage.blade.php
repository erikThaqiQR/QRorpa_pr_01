@extends('layouts.appOnlPayCl')
<?php

    use App\Cupon;
    use App\onlinePayQRCStaf;
    use App\ordersTaOnlinePayStafTemp;
    use App\Restorant;
    use App\TableQrcode;
    use App\TabOrder;
    use App\Takeaway;

?>

<style>
    .selectedTip{
        background-color:rgb(39,190,175) !important;
        color:white !important;
    }
    .selectedTip:hover{
        opacity:0.7 !important;
        color:white !important;
    }
</style>
@section('content')

@if (!isset($_GET['ops']) || !isset($_GET['h']))
    <p class="mt-3 p-2 text-center" style="color:white; font-size:1.3rem;"> 
        <strong>Diese Anfrage ist ungültig. Bitte fordern Sie beim Personal den QR-Code an, um mit der Zahlung fortzufahren!</strong>
    </p>
@else
    @php
        $onlPayStaf = onlinePayQRCStaf::where([['id',$_GET['ops']],['qrCodeHash',$_GET['h']]])->first();
    @endphp
    @if ($onlPayStaf == Null)
        <p class="mt-3 p-2 text-center" style="color:white; font-size:1.3rem;"> 
            <strong>Diese Anfrage ist ungültig. Bitte fordern Sie beim Personal den korrekten QR-Code an, um mit der Zahlung fortzufahren!</strong>
        </p>
    @elseif ($onlPayStaf->status == 1)
        <p class="mt-3 p-2 text-center" style="color:white; font-size:1.3rem;"> 
            <strong>Die Online-Zahlung für diese Bestellung wurde bereits verarbeitet!</strong>
        </p>
    @else
        <!-- Restaurant Order  -->
        @if ($onlPayStaf->tableNr != 500)

            @if ($onlPayStaf->payType == 'payAll')
            <!-- Pagesa ALL RESTAURANT Start -->
            <div class="m-1" style="background-color: white; border-radius:7px;">

                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">{{Restorant::find($onlPayStaf->resId)->emri}} Bestellung</p>
                @if ($onlPayStaf->tvsh == 8.10)
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">Tisch: {{$onlPayStaf->tableNr}}</p>
                @else
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">Takeaway</p>
                <?php
                    $taOrTvsh81 = number_format(0,9,'.','');
                    $taOrTvsh26 = number_format(0,9,'.','');
                ?>
                @endif

                @if($onlPayStaf->resComment != 'none' && $onlPayStaf->resComment != Null)
                <pre style="font-size:13px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height:1.01; text-align: center !important; margin:0px; padding:0px 15px 0px 15px; white-space: pre-wrap; word-wrap: break-word;">{{ preg_replace('/^\x{FEFF}|\x{200B}|\x{00A0}|\t/u', '',$onlPayStaf->resComment) }}</pre>
                @endif
                
                <hr style="margin:3px 0 3px 0;">
                @php
                    $tabCode = TableQrcode::where([['Restaurant',$onlPayStaf->resId],['tableNr',$onlPayStaf->tableNr]])->first()->kaTab;
                    $totPay = number_format(0,2,'.','');
                @endphp
                @if ($tabCode == 0)
                    <p class="mt-3 p-2 text-center" style="color:rgb(39,190,175); font-size:1.3rem;"> 
                        <strong>Die Online-Zahlung für diese Bestellung wurde bereits verarbeitet!</strong>
                    </p>
                @else
                    <div class="d-flex flex-wrap justify-content-between pr-2 pl-2">
                    @foreach (TabOrder::where('tabCode',$tabCode)->get() as $orInTab)
                        <p style="width:10%; margin:0px;">{{$orInTab->OrderSasia}} X</p>
                        <div style="width:70%; margin:0px;">
                            {{$orInTab->OrderEmri}}
                            @if ($orInTab->OrderType != 'empty')
                                <span style="opacity:0.7;" class="ml-1">( {{explode('||',$orInTab->OrderType)[0]}} )</span>
                            @endif

                            @if ($orInTab->OrderExtra != 'empty')
                                <br>
                                <div class="d-flex flew-wrap">
                                @foreach (explode('--0--',$orInTab->OrderExtra) as $OneEx)
                                    <span style="width:fit-content; opacity:0.7; font-size:0.7rem;" class="mr-1">
                                    @if ($loop->first)
                                    {{explode('||',$OneEx)[0]}}
                                    @else
                                    , {{explode('||',$OneEx)[0]}}
                                    @endif
                                    </span>
                                @endforeach
                                </div>
                            @endif
                        </div>
                        <?php
                            $totPay += number_format($orInTab->OrderQmimi,2,'.','');
                            if ($onlPayStaf->tvsh == 2.60){
                                $TaProd = Takeaway::where('prod_id',$orInTab->prodId)->first();
                                if($TaProd != Null){
                                    if($TaProd->mwstForPro == 2.60){
                                        $tvshVal = number_format( 0.025341130 , 9, '.', '');
                                        $taOrTvsh26 += number_format($orInTab->OrderQmimi* $tvshVal,9,'.','');
                                    }else if($TaProd->mwstForPro == 8.10){
                                        $tvshVal = number_format( 0.074930619 , 9, '.', '');
                                        $taOrTvsh81 += number_format($orInTab->OrderQmimi* $tvshVal,9,'.','');
                                    }
                                }else{
                                    $tvshVal = number_format( 0.025341130 , 9, '.', '');
                                    $taOrTvsh26 += number_format($orInTab->OrderQmimi* $tvshVal,9,'.',''); 
                                }
                            }
                        ?>
                        <p style="width:20%; margin:0px;">{{number_format($orInTab->OrderQmimi,2,'.','')}} <span style="opacity: 0.7; font-size:0.6rem;">CHF</span></p>
                    @endforeach
                    </div>
                
                    <hr style="margin:3px 0 3px 0;">
                    <h5 class="color-qrorpa">{{__('others.tipForServiceTeam')}}</h5>
                    <div class="d-flex justify-content-between flex-wrap" style="margin-top:-5px;" id="theBakshishDivAll">
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>50</strong> <sup>{{__('global.currencyShowCent')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter1" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>1</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter2" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>2</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter5" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>5</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>10</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button id="tipWaiterCancel" onclick="setTip(this.id,'{{$totPay}}')" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"> Stornieren </button>
                        <button id="tipWaiterCos" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2">
                            <input step="0.5" min="0" value="0" id="tipWaiterCosVal" type="number" onkeyup="setCostume('tipWaiterCos', 'tipWaiterCosVal', this.value,'{{$totPay}}')"style="width:70%; border:none;" placeholder="andere"> CHF
                        </button>

                        <div class="alert alert-danger text-center mt-1" style="font-size: 1.1rem; display:none; width:100%;" id="tipError01">
                            Bitte geben Sie einen gültigen Wert ein
                        </div>

                        <input type="hidden" value="0.00" id="tipValueCHF">
                    </div>

                    <hr style="margin:3px 0 3px 0;">
                    <div class="d-flex flex-wrap justify-content-between pr-2 pl-2">
                        <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Gesamt:</p>
                        <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                            {{number_format($totPay,2,'.','')}} 
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>

                        @if ($onlPayStaf->tvsh == 8.10)
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">MwSt (8.10%):</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{number_format(($totPay*8.10)/108.10,2,'.','')}}
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                            </p>
                        @else
                            @if ($taOrTvsh81 > 0)
                                <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">MwSt (8.10%):</p>
                                <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                    {{number_format($taOrTvsh81,2,'.','')}}
                                    <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                                </p>
                            @endif
                            @if ($taOrTvsh26 > 0)
                                <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">MwSt (2.60%):</p>
                                <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                    {{number_format($taOrTvsh26,2,'.','')}}
                                    <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                                </p>
                            @endif
                        @endif
                        

                        @if ($onlPayStaf->cashDis > 0)
                            @php
                                $valOffSkonto = number_format($onlPayStaf->cashDis,2,'.','');
                            @endphp
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Skonto:</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{number_format($valOffSkonto,2,'.','')}} 
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                            </p>
                            <input type="hidden" value="{{$onlPayStaf->cashDis}}" id="skontoCHF">
                            <input type="hidden" value="0" id="skontoPer">
                        @elseif ($onlPayStaf->percDis > 0)
                            @php
                                $valOffSkonto = number_format($totPay * ($onlPayStaf->percDis/100),2,'.','');
                            @endphp
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Skonto:</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{$valOffSkonto}} 
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                                ( {{number_format($onlPayStaf->percDis,2,'.','')}} % )
                            </p>
                            <input type="hidden" value="{{$valOffSkonto}}" id="skontoCHF">
                            <input type="hidden" value="{{$onlPayStaf->percDis}}" id="skontoPer">
                        @else
                            @php
                                $valOffSkonto = number_format(0,2,'.','');
                            @endphp
                            <input type="hidden" value="0" id="skontoCHF">
                            <input type="hidden" value="0" id="skontoPer">
                        @endif

                        <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Kellner-Tipp:</p>
                        <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                            <span id="waiterTipShow">0.00</span>
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>
                        @if ($onlPayStaf->giftCardDisc > 0)
                        <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Geschenkkartenrabatt:</p>
                        <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                            <span id="giftCardDiscountShow">{{number_format($onlPayStaf->giftCardDisc,2,'.','')}}</span>
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>
                        @endif

                        

                        <hr style="width:100%; margin:3px 0 3px 0;">

                        <p class="mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.4rem; width:50%;">Bezahlen:</p>
                        <p class="mb-1 text-right" style="color:rgb(39,190,175); font-weight:bold; font-size:1.4rem; width:50%;">
                            <span id="toPayShow">{{number_format($totPay - $valOffSkonto - $onlPayStaf->giftCardDisc,2,'.','')}} </span>
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>
                    </div>
                    <button class="btn btn-success" style="width:100%; margin:16px 0 0 0;" id="payOnlClFSBtn1" onclick="payOnlClFS('{{$onlPayStaf->id}}','{{$totPay}}','{{$onlPayStaf->resId}}')">
                        Online-Bezahlung
                    </button>
                @endif

                
            </div>
            <!-- Pagesa ALL RESTAURANT End -->













            @else
            <!-- Pagesa SELECTED RESTAURANT Start -->
            <div class="m-1" style="background-color: white; border-radius:7px;">

                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">{{Restorant::find($onlPayStaf->resId)->emri}} Bestellung</p>
                @if ($onlPayStaf->tvsh == 8.10)
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">Tisch: {{$onlPayStaf->tableNr}} ( Ausgewählt )</p>
                @else
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">Takeaway ( Ausgewählt )</p>
                
                @if($onlPayStaf->resComment != 'none' && $onlPayStaf->resComment != Null)
                <pre style="font-size:13px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height:1.01; text-align: center !important; margin:0px; padding:0px 15px 0px 15px; white-space: pre-wrap; word-wrap: break-word;">{{ preg_replace('/^\x{FEFF}|\x{200B}|\x{00A0}|\t/u', '',$onlPayStaf->resComment) }}</pre>
                @endif
                
                <?php
                    $taOrTvsh81 = number_format(0,9,'.','');
                    $taOrTvsh26 = number_format(0,9,'.','');
                ?>
                @endif
                <hr style="margin:3px 0 3px 0;">
                @php
                    $tabCode = TableQrcode::where([['Restaurant',$onlPayStaf->resId],['tableNr',$onlPayStaf->tableNr]])->first()->kaTab;
                    $totPay = number_format(0,2,'.','');
                    $tabOrSel = array();
                    
                    foreach(explode('||',$onlPayStaf->prodSelected) as $oneTabOrSel){ 
                        $oneTabOrSel2D = explode('-8-',$oneTabOrSel);
                        array_push($tabOrSel, $oneTabOrSel2D[0]); 
                    }
                @endphp
                @if ($tabCode == 0)
                    <p class="mt-3 p-2 text-center" style="color:rgb(39,190,175); font-size:1.3rem;"> 
                        <strong>Die Online-Zahlung für diese Bestellung wurde bereits verarbeitet!</strong>
                    </p>
                @else
                    <div class="d-flex flex-wrap justify-content-between pr-2 pl-2">
                    @foreach (TabOrder::whereIn('id',$tabOrSel )->get() as $orInTab)
                        <?php
                            $thisTabSasisSel = 0;
                            foreach(explode('||',$onlPayStaf->prodSelected) as $oneTabOrSel){ 
                                $oneTabOrSel2D = explode('-8-',$oneTabOrSel);
                                if($oneTabOrSel2D[0] == $orInTab->id){
                                    $thisTabSasisSel = $oneTabOrSel2D[1];
                                }
                            }

                            $priceForOneSelTabProd = number_format($orInTab->OrderQmimi/$orInTab->OrderSasia, 2, '.', '');
                            $qmimiOfSelected = number_format($priceForOneSelTabProd * $thisTabSasisSel, 2, '.', '');
                        ?>
                        <p style="width:10%; margin:0px;">{{$thisTabSasisSel}} X</p>
                        <div style="width:70%; margin:0px;">
                            {{$orInTab->OrderEmri}}
                            @if ($orInTab->OrderType != 'empty')
                                <span style="opacity:0.7;" class="ml-1">( {{explode('||',$orInTab->OrderType)[0]}} )</span>
                            @endif

                            @if ($orInTab->OrderExtra != 'empty')
                                <br>
                                <div class="d-flex flew-wrap">
                                @foreach (explode('--0--',$orInTab->OrderExtra) as $OneEx)
                                    <span style="width:fit-content; opacity:0.7; font-size:0.7rem;" class="mr-1">
                                    @if ($loop->first)
                                    {{explode('||',$OneEx)[0]}}
                                    @else
                                    , {{explode('||',$OneEx)[0]}}
                                    @endif
                                    </span>
                                @endforeach
                                </div>
                            @endif
                        </div>
                        <?php
                            $totPay += number_format($qmimiOfSelected,2,'.','');
                            if ($onlPayStaf->tvsh == 2.60){
                                $TaProd = Takeaway::where('prod_id',$orInTab->prodId)->first();
                                if($TaProd != Null){
                                    if($TaProd->mwstForPro == 2.60){
                                        $tvshVal = number_format( 0.025341130 , 9, '.', '');
                                        $taOrTvsh26 += number_format($qmimiOfSelected* $tvshVal,9,'.','');
                                    }else if($TaProd->mwstForPro == 8.10){
                                        $tvshVal = number_format( 0.074930619 , 9, '.', '');
                                        $taOrTvsh81 += number_format($qmimiOfSelected* $tvshVal,9,'.','');
                                    }
                                }else{
                                    $tvshVal = number_format( 0.025341130 , 9, '.', '');
                                    $taOrTvsh26 += number_format($qmimiOfSelected* $tvshVal,9,'.',''); 
                                }
                            }
                        ?>
                        <p style="width:20%; margin:0px;">{{number_format($qmimiOfSelected,2,'.','')}} <span style="opacity: 0.7; font-size:0.6rem;">CHF</span></p>
                    @endforeach
                    </div>
                
                    <hr style="margin:3px 0 3px 0;">
                    <h5 class="color-qrorpa">{{__('others.tipForServiceTeam')}}</h5>
                    <div class="d-flex justify-content-between flex-wrap" style="margin-top:-5px;" id="theBakshishDivAll">
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>50</strong> <sup>{{__('global.currencyShowCent')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter1" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>1</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter2" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>2</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter5" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>5</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>10</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button id="tipWaiterCancel" onclick="setTip(this.id,'{{$totPay}}')" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"> Stornieren </button>
                        <button id="tipWaiterCos" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2">
                            <input step="0.5" min="0" value="0" id="tipWaiterCosVal" type="number" onkeyup="setCostume('tipWaiterCos', 'tipWaiterCosVal', this.value,'{{$totPay}}')"style="width:70%; border:none;" placeholder="andere"> CHF
                        </button>

                        <div class="alert alert-danger text-center mt-1" style="font-size: 1.1rem; display:none; width:100%;" id="tipError01">
                            Bitte geben Sie einen gültigen Wert ein
                        </div>

                        <input type="hidden" value="0.00" id="tipValueCHF">
                    </div>

                    <hr style="margin:3px 0 3px 0;">
                    <div class="d-flex flex-wrap justify-content-between pr-2 pl-2">
                        <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Gesamt:</p>
                        <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                            {{number_format($totPay,2,'.','')}} 
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>

                        @if ($onlPayStaf->tvsh == 8.10)
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">MwSt (8.10%):</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{number_format(($totPay*8.10)/108.10,2,'.','')}}
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                            </p>
                        @else
                            @if ($taOrTvsh81 > 0)
                                <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">MwSt (8.10%):</p>
                                <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                    {{number_format($taOrTvsh81,2,'.','')}}
                                    <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                                </p>
                            @endif
                            @if ($taOrTvsh26 > 0)
                                <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">MwSt (2.60%):</p>
                                <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                    {{number_format($taOrTvsh26,2,'.','')}}
                                    <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                                </p>
                            @endif
                        @endif

                        @if ($onlPayStaf->cashDis > 0)
                            @php
                                $valOffSkonto = number_format($onlPayStaf->cashDis,2,'.','');
                            @endphp
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Skonto:</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{number_format($valOffSkonto,2,'.','')}} 
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                            </p>
                            <input type="hidden" value="{{$onlPayStaf->cashDis}}" id="skontoCHF">
                            <input type="hidden" value="0" id="skontoPer">
                        @elseif ($onlPayStaf->percDis > 0)
                            @php
                                $valOffSkonto = number_format($totPay * ($onlPayStaf->percDis/100),2,'.','');
                            @endphp
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Skonto:</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{$valOffSkonto}} 
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                                ( {{number_format($onlPayStaf->percDis,2,'.','')}} % )
                            </p>
                            <input type="hidden" value="{{$valOffSkonto}}" id="skontoCHF">
                            <input type="hidden" value="{{$onlPayStaf->percDis}}" id="skontoPer">
                        @else
                            @php
                                $valOffSkonto = number_format(0,2,'.','');
                            @endphp
                            <input type="hidden" value="0" id="skontoCHF">
                            <input type="hidden" value="0" id="skontoPer">
                        @endif

                        <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Kellner-Tipp:</p>
                        <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                            <span id="waiterTipShow">0.00</span>
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>

                        @if ($onlPayStaf->giftCardDisc > 0)
                        <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Geschenkkartenrabatt:</p>
                        <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                            <span id="giftCardDiscountShow">{{number_format($onlPayStaf->giftCardDisc,2,'.','')}}</span>
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>
                        @endif

                        

                        <hr style="width:100%; margin:3px 0 3px 0;">

                        <p class="mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.4rem; width:50%;">Bezahlen:</p>
                        <p class="mb-1 text-right" style="color:rgb(39,190,175); font-weight:bold; font-size:1.4rem; width:50%;">
                            <span id="toPayShow">{{number_format($totPay - $valOffSkonto - $onlPayStaf->giftCardDisc,2,'.','')}} </span>
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>
                    </div>
                    <button class="btn btn-success" style="width:100%; margin:16px 0 0 0;" id="payOnlClFSBtn2" onclick="payOnlClFSSelected('{{$onlPayStaf->id}}','{{$totPay}}','{{$onlPayStaf->resId}}')">
                        Online-Bezahlung
                    </button>
                @endif

                
            </div>
            <!-- Pagesa SELECTED RESTAURANT End -->
            @endif









        
        @else
        <?php
            
        ?>
        <!-- Takeaway Order Start -->
            <div class="m-1" style="background-color: white; border-radius:7px;">

                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">{{Restorant::find($onlPayStaf->resId)->emri}} Bestellung</p>
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">Takeaway</p>
                <hr style="margin:3px 0 3px 0;">

                @php
                    $totPay = number_format(0,2,'.','');
                    $totMwst77 = number_format(0,4,'.','');
                    $totMwst25 = number_format(0,4,'.','');
                @endphp

                <div class="d-flex flex-wrap justify-content-between pr-2 pl-2">
                    @foreach (ordersTaOnlinePayStafTemp::where('onlinePayRef',$onlPayStaf->id)->get() as $orTA)
                        <p style="width:10%; margin:0px;">{{$orTA->proSasia}} X</p>
                        <div style="width:70%; margin:0px;">
                            {{$orTA->taProdName}}
                            @if ($orTA->proType != 'empty')
                                <span style="opacity:0.7;" class="ml-1">( {{explode('||',$orTA->proType)[0]}} )</span>
                            @endif

                            @if ($orTA->proExtra != 'empty')
                                <br>
                                <div class="d-flex flew-wrap">
                                @foreach (explode('--0--',$orTA->proExtra) as $OneEx)
                                    <span style="width:fit-content; opacity:0.7; font-size:0.7rem;" class="mr-1">
                                    @if ($loop->first)
                                    {{explode('||',$OneEx)[0]}}
                                    @else
                                    , {{explode('||',$OneEx)[0]}}
                                    @endif
                                    </span>
                                @endforeach
                                </div>
                            @endif
                        </div>
                        @php
                            $totPayThis = number_format($orTA->proQmimi * $orTA->proSasia,2,'.','');
                            $taProd = Takeaway::find($orTA->taProdId);

                            if($taProd != Null){
                                if($taProd->mwstForPro == 8.10){ $totMwst77 += number_format((8.10*$totPayThis)/108.10,4,'.','');
                                }else if($taProd->mwstForPro == 2.60){ $totMwst25 += number_format((2.60*$totPayThis)/102.60,4,'.',''); }
                            }else{ $totMwst25 += number_format((2.60*$totPayThis)/102.60,4,'.',''); }
                            $totPay += $totPayThis;
                        @endphp
                        <p style="width:20%; margin:0px;">
                            {{number_format($orTA->proQmimi * $orTA->proSasia,2,'.','')}} <span style="opacity: 0.7; font-size:0.6rem;">CHF</span>
                        </p>

                    @endforeach
                </div>

                
                <hr style="margin:3px 0 3px 0;">
                    <h5 class="color-qrorpa">{{__('others.tipForServiceTeam')}}</h5>
                    <div class="d-flex justify-content-between flex-wrap" style="margin-top:-5px;" id="theBakshishDivAll">
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>50</strong> <sup>{{__('global.currencyShowCent')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter1" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>1</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter2" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>2</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter5" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>5</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button onclick="setTip(this.id,'{{$totPay}}')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>10</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                        <button id="tipWaiterCancel" onclick="setTip(this.id,'{{$totPay}}')" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"> Stornieren </button>
                        <button id="tipWaiterCos" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2">
                            <input step="0.5" min="0" value="0" id="tipWaiterCosVal" type="number" onkeyup="setCostume('tipWaiterCos', 'tipWaiterCosVal', this.value,'{{$totPay}}')"style="width:70%; border:none;" placeholder="andere"> CHF
                        </button>

                        <div class="alert alert-danger text-center mt-1" style="font-size: 1.1rem; display:none; width:100%;" id="tipError01">
                            Bitte geben Sie einen gültigen Wert ein
                        </div>

                        <input type="hidden" value="0.00" id="tipValueCHF">
                    </div>

                    <hr style="margin:3px 0 3px 0;">
                    <div class="d-flex flex-wrap justify-content-between pr-2 pl-2">
                        <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Gesamt:</p>
                        <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                            {{number_format($totPay,2,'.','')}} 
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>

                        @if ($totMwst77 > 0)
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">MwSt (8.10%):</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{number_format($totMwst77,2,'.','')}} 
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                            </p>
                        @endif
                        @if ($totMwst25 > 0)
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">MwSt (2.60%):</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{number_format($totMwst25,2,'.','')}} 
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                            </p>
                        @endif

                        @if ($onlPayStaf->cashDis > 0)
                            @php
                                $valOffSkonto = number_format($onlPayStaf->cashDis,2,'.','');
                            @endphp
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Skonto:</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{number_format($valOffSkonto,2,'.','')}} 
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                            </p>
                            <input type="hidden" value="{{$onlPayStaf->cashDis}}" id="skontoCHF">
                            <input type="hidden" value="0" id="skontoPer">
                        @elseif ($onlPayStaf->percDis > 0)
                            @php
                                $valOffSkonto = number_format($totPay * ($onlPayStaf->percDis/100),2,'.','');
                            @endphp
                            <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Skonto:</p>
                            <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                                {{$valOffSkonto}} 
                                <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                                ( {{number_format($onlPayStaf->percDis,2,'.','')}} % )
                            </p>
                            <input type="hidden" value="{{$valOffSkonto}}" id="skontoCHF">
                            <input type="hidden" value="{{$onlPayStaf->percDis}}" id="skontoPer">
                        @else
                            @php
                                $valOffSkonto = number_format(0,2,'.','');
                            @endphp
                            <input type="hidden" value="0" id="skontoCHF">
                            <input type="hidden" value="0" id="skontoPer">
                        @endif

                        <p class="mb-1" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">Kellner-Tipp:</p>
                        <p class="mb-1 text-right" style="color:rgb(72,81,87); font-weight:bold; font-size:1rem; width:50%;">
                            <span id="waiterTipShow">0.00</span>
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>

                        

                        <hr style="width:100%; margin:3px 0 3px 0;">

                        <p class="mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.4rem; width:50%;">Bezahlen:</p>
                        <p class="mb-1 text-right" style="color:rgb(39,190,175); font-weight:bold; font-size:1.4rem; width:50%;">
                            <span id="toPayShow">{{number_format($totPay - $valOffSkonto,2,'.','')}} </span>
                            <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                        </p>
                    </div>
                    <button class="btn btn-success" style="width:100%; margin:16px 0 0 0;" id="payOnlClFSBtn3" onclick="payOnlClFSTakeaway('{{$onlPayStaf->id}}','{{$totPay}}','{{$onlPayStaf->resId}}')">
                        Online-Bezahlung
                    </button>
            </div>
            
        <!-- Takeaway Order End -->
        @endif
    @endif
@endif


@include('onlPayClPageScript')

@endsection