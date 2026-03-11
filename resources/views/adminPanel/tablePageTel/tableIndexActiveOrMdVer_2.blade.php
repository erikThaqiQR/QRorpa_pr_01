<?php

use App\User;
use App\Orders;
use App\Produktet;
use App\resPlates;
use Carbon\Carbon;
use App\FreeProducts;
use App\waiterActivityLog;
    use App\TabOrder;
    use App\billTabletsReg;
    use Illuminate\Support\Facades\Auth;
    
    $allBillT = billTabletsReg::where('toRes',Auth::user()->sFor)->get();
?>
<style>
    .tabOrderDivSelected{ background-color: rgb(157, 157, 155) !important; }
    .tabOrderDivConfirmed{ background-color: rgb(208, 250, 248); }
    .tabOrderDivCalled{ background-color: rgb(126, 217, 86); }

    .adminChangeTableNotSelect{
        width:7.1%; 
        margin:4px 0px 4px 0px; 
        opacity:0.55
    }
    .adminChangeTableSelect{
        width:7.1%; 
        margin:4px 0px 4px 0px; 
        opacity:1;
    }
    .adminChangeTableNotSelect:hover{
        opacity: 0.7;
        cursor: pointer;
    }
</style>


@foreach($allTables as $tabelOne)
    <div class="modal" id="tabOrder{{$tabelOne->tableNr}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="tabOrderBody{{$tabelOne->tableNr}}">

                <div class="modal-header d-flex justify-content-between" id="tabOrderTopBar1{{$tabelOne->tableNr}}" style="width:100%;">
                    <span style="font-size:26px; width:fit-content;"><strong>T: {{$tabelOne->tableNr}}</strong></span>
                    <span style="font-size:26px; width:fit-content;" class="pl-1 pr-1"><strong>|</strong></span>
                    @if ($tabelOne->kaTab != 0)
                        <span style="font-size:26px; width: fit-content; " class="pl-2"><strong><span id="tabOrderTotPriceDiv{{$tabelOne->tableNr}}Value">{{ number_format(TabOrder::where([['tabCode',$tabelOne->kaTab],['status','<',2]])->sum('OrderQmimi'),2,'.','') }}</span></strong></span>
                    @else
                        <span style="font-size:26px; width: fit-content; " class="pl-2"><strong> <span id="tabOrderTotPriceDiv{{$tabelOne->tableNr}}Value">0.00</span></strong></span>
                    @endif
                    <span style="font-size:30px; width:fit-content;" class="pl-1 pr-1"><strong>|</strong></span>
                    <button style="width: 8%; margin:0px; padding:0px;" type="button" class="btn shadow-none" onclick="printActiveOrdersOnTable('{{$tabelOne->tableNr}}')">
                        <i class="pt-2 fa-2x fa-solid fa-receipt"></i>
                    </button>
                    <span style="font-size:30px; width:fit-content;" class="pl-1 pr-1"><strong>|</strong></span>
                    
                    <button style="width: 12%; margin:0px; padding:0px;" type="button" class="btn shadow-none" data-toggle="modal" data-target="#billTabletsChooseModal" onclick="openBillTabletsChoose('{{$tabelOne->tableNr}}')">
                        <img src="storage/icons/billTabletIconSmall.png" style="width:100%;" class="pt-2" alt="">
                    </button>
                    <button style="width: 10%;" type="button" class="btn btn-outline-dark shadow-none mt-1" data-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <div class="d-flex flex-wrap justify-content-between mb-2" style="width:100%;" id="extraServicesOnTable{{$tabelOne->tableNr}}">
                    <button id="payAllProd{{$tabelOne->tableNr}}" class="btn btn-dark shadow-none"  onclick="prepPayAllProds('{{$tabelOne->tableNr}}','{{Auth::user()->sFor}}')"
                        style="width:24.6%; font-size:0.65rem;" data-toggle="modal" data-target="#payAllPhaseOne">
                        <strong>Bezahlen</strong>
                    </button>
                    <button id="paySelProd{{$tabelOne->tableNr}}" class="btn btn-dark shadow-none" onclick="prepPaySelProds('{{$tabelOne->tableNr}}','{{Auth::user()->sFor}}')"
                        style="width:24.6%; font-size:0.65rem; display:none;" data-toggle="modal" data-target="#payAllPhaseOneSel"> 
                        <strong>Bezahlen</strong>
                    </button>

                    <button style="width:24.6%;" class="btn btn-success shadow-none" onclick="openNewTabOrderModalFromATOM('{{$tabelOne->tableNr}}')">
                        <strong><i class="fa-solid fa-plus"></i></strong>
                    </button>

                    <button style="width:24.6%; font-size:0.65rem;" class="btn btn-outline-success shadow-none" data-toggle="modal" data-target="#adminChangeTableModal"
                    onclick="prepTableChangeModal('{{$tabelOne->id}}','{{$tabelOne->tableNr}}','{{$tabelOne->kaTab}}')">
                        <strong>Tisch Ändern</strong>
                    </button>

                    <button style="width:24.6%; font-size:0.65rem;" id="confTabOrdersBtn0O{{$tabelOne->tableNr}}O{{$tabelOne->kaTab}}" class="btn btn-dark shadow-none" onclick="confTabOrders('0','{{$tabelOne->tableNr}}','{{$tabelOne->kaTab}}')">
                        <strong>Bestätigen</strong>
                    </button>
                </div>

                <div class="d-flex flex-wrap justify-content-between mb-2" style="width:100%;" id="extraServicesOnTable2{{$tabelOne->tableNr}}">

                    <div style="width:24.6%;" class="input-group">
                        <input style="padding-bottom: 6px; height:100%;" id="splitTheBillClNr{{$tabelOne->tableNr}}" type="number" class="form-control shadow-none" placeholder="Teilen">
                        <div class="input-group-append">
                            <button class="btn btn-secondary shadow-none" type="button" onclick="splitTheBillInitiate('{{$tabelOne->tableNr}}')">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </div>

                    <button style="width:24.6%; font-size:0.65rem;" id="xxxyyy1" class="btn btn-warning shadow-none">
                        <strong>Nachricht</strong>
                    </button>
                    <button style="width:24.6%; font-size:0.65rem;" id="xxxyyy2" class="btn btn-danger shadow-none" onclick="initiateTabOrDelete('{{$tabelOne->tableNr}}')">
                        <strong>Löschen</strong>
                    </button>

                    <button style="width:24.6%; font-size:0.65rem;" id="xxxyyy3" class="btn btn-info shadow-none" onclick="initiateTabOrAbrufen('{{$tabelOne->tableNr}}')">
                        <strong>Abrufen</strong>
                    </button>
                </div>
                <div style="width:100%; display:none;" class="mt-1 text-center alert alert-danger" id="splitTheBillError01{{$tabelOne->tableNr}}">
                    <strong>Geben Sie eine gültige Kundenanzahl an!</strong>
                </div>
                <div style="width:100%; display:none;" class="mt-1 text-center alert alert-danger flex-wrap justify-content-between" id="deleteAllPrompt{{$tabelOne->tableNr}}">
                    <span style="width:100%;"><strong>Sie haben keine Bestellungen ausgewählt!</strong></span>
                    <span style="width:100%;"><strong>Möchtest du sie alle löschen?</strong></span>
                    <input type="text" id="sendOrderForDeleteAllConfModalKom{{$tabelOne->tableNr}}" value="" class="form-control shadow-none mb-1">
                    <input type="hidden" id="sendOrderForDeleteAllConfModalKomRequired{{$tabelOne->tableNr}}" value="none">
                    <span style="width:100%; color:darkred; display:none;" id="sendOrderForDeleteAllConfModalKomRequiredMsg{{$tabelOne->tableNr}}"><strong>In diesem Fall ist ein Löschkommentar erforderlich, der mehr als fünf Zeichen enthält!</strong></span>
                    <button class="btn btn-info shadow-none" style="width:49%;" onclick="cancelTabOrDeleteAll('{{$tabelOne->tableNr}}')">Nein</button>
                    <button class="btn btn-danger shadow-none" style="width:49%;" onclick="confirmTabOrDeleteAll('{{$tabelOne->tableNr}}')">Ja</button>
                </div>
                <div style="width:100%; display:none;" class="mt-1 text-center alert alert-danger flex-wrap justify-content-between" id="deleteSomePrompt{{$tabelOne->tableNr}}">
                    <span style="width:100%;"><strong>Sind Sie sicher, dass Sie die ausgewählten Bestellungen löschen möchten?</strong></span>
                    <input type="text" id="sendOrderForDeleteSomeConfModalKom{{$tabelOne->tableNr}}" value="" class="form-control shadow-none mb-1">
                    <input type="hidden" id="sendOrderForDeleteSomeConfModalKomRequired{{$tabelOne->tableNr}}" value="none">
                    <span style="width:100%; color:darkred; display:none;" id="sendOrderForDeleteSomeConfModalKomRequiredMsg{{$tabelOne->tableNr}}"><strong>In diesem Fall ist ein Löschkommentar erforderlich, der mehr als fünf Zeichen enthält!</strong></span>
                    <button class="btn btn-info shadow-none" style="width:49%;" onclick="cancelTabOrDeleteSome('{{$tabelOne->tableNr}}')">Nein</button>
                    <button class="btn btn-danger shadow-none" style="width:49%;" onclick="confirmTabOrDeleteSome('{{$tabelOne->tableNr}}')">Ja</button>
                </div>

                <?php $activeClientsPNr = array(); 
                    if ($tabelOne->kaTab != 0){
                        foreach(TabOrder::where([['tab_orders.tabCode',$tabelOne->kaTab]])
                        ->join('tab_verification_p_numbers', 'tab_orders.id', '=', 'tab_verification_p_numbers.tabOrderId')
                        ->select('tab_verification_p_numbers.phoneNr as phoneNr')
                        ->orderByDesc('tab_orders.created_at')->get() as $oneTOrder){
                            if ($oneTOrder!= NULL && !in_array($oneTOrder->phoneNr, $activeClientsPNr)){
                                array_push($activeClientsPNr, $oneTOrder->phoneNr);
                            }
                        } 
                    }
                ?>

                <input type="hidden" id="closeOrSelected{{$tabelOne->tableNr}}" value="0">

                <div style="width:100%;" id="tabOrderBody{{$tabelOne->tableNr}}">
                    <div id="tabOrderBody{{$tabelOne->tableNr}}ActiveOrders">
                        @if ($tabelOne->kaTab != 0)
                            @foreach ($activeClientsPNr as $activeClientsPNrOneNew)
                                <div class="card mb-1 pr-1 pl-1" style="width: 100%;" id="tab{{$tabelOne->tableNr}}OrClDiv{{$activeClientsPNrOneNew}}">
                                    
                                    @if($activeClientsPNrOneNew == '0770000000')
                                        <!-- staff - admin / waiter -->
                                    @elseif(str_contains($activeClientsPNrOneNew, '|'))
                                        <div class="card-header">
                                            <span style="width: 100%;"><strong>{{__('adminP.ghost')}} : {{explode('|',$activeClientsPNrOneNew)[1]}}</strong></span>
                                        </div>    
                                    @else
                                        <div class="card-header">
                                            <span style="width: 100%;"><strong>+41 *** *{{substr($activeClientsPNrOneNew, -6)}}</strong></span>
                                        </div>
                                    @endif

                                    <?php
                                        $allPlates = array();
                                        foreach(TabOrder::where('tab_orders.tabCode',$tabelOne->kaTab)
                                        ->join('tab_verification_p_numbers', 'tab_orders.id', '=', 'tab_verification_p_numbers.tabOrderId')
                                        ->select('tab_orders.status as TOstatus', 'tab_orders.*', 'tab_verification_p_numbers.phoneNr as phoneNr')
                                        ->where('tab_verification_p_numbers.phoneNr',$activeClientsPNrOneNew)
                                        ->orderByDesc('tab_orders.created_at')->get() as $oneTOrder){
                                            if(!in_array($oneTOrder->toPlate,$allPlates)){
                                                array_push($allPlates,$oneTOrder->toPlate);
                                            }
                                        }
                                        sort($allPlates);
                                    ?>

                                    @foreach ($allPlates as $onePlateIdThisCl)
                                        <?php
                                            $thePlateIns = resPlates::find($onePlateIdThisCl);
                                        ?>
                                        <div class="card mb-1 mt-2 pl-1 pr-1" style="width: 100%; border:1px solid rgb(72,81,87);" id="tab{{$tabelOne->tableNr}}OrClDiv{{$activeClientsPNrOneNew}}Plate{{$onePlateIdThisCl}}">
                                            <div class="card-header" style="width:fit-content; padding:0px 15px 0px 15px; margin:-10px 0px 5px 15px ; background-color:white; border:none; font-size:1.15rem;">
                                                @if ($thePlateIns != Null)
                                                    <span style="width: 100%;"><strong>{{$thePlateIns->nameTitle}}</strong></span>
                                                @elseif ($onePlateIdThisCl == 0)
                                                    <span style="width: 100%;"><strong>Kein Kennzeichen</strong></span>
                                                @else
                                                    <span style="width: 100%;"><strong>---</strong></span>
                                                @endif
                                            </div>

                                            @foreach(TabOrder::where('tab_orders.tabCode',$tabelOne->kaTab)
                                            ->join('tab_verification_p_numbers', 'tab_orders.id', '=', 'tab_verification_p_numbers.tabOrderId')
                                            ->select('tab_orders.status as TOstatus', 'tab_orders.*', 'tab_verification_p_numbers.phoneNr as phoneNr')
                                            ->where('tab_verification_p_numbers.phoneNr',$activeClientsPNrOneNew)
                                            ->where('tab_orders.toPlate',$onePlateIdThisCl)
                                            ->orderByDesc('tab_orders.created_at')->get() as $oneTOrder)
                                                <?php
                                                    $waLog = waiterActivityLog::where([['actType','newProdWa'],['actId',$oneTOrder->id]])->first();
                                                    if($waLog != Null ){ $waiterData = User::find($waLog->waiterId); }else{ $waiterData = Null; }
                                                    $time2D = explode(':',explode(' ',$oneTOrder->created_at)[1]);
                                                ?>

                                                <div style="border: 1px solid rgb(72,81,87); border-radius:2px; margin-bottom:2px;" id="tabOrderDiv{{$oneTOrder->id}}"
                                                class="d-flex flex-wrap justify-content-between {{ $oneTOrder->abrufenStat == 1 ? 'tabOrderDivCalled' : ($oneTOrder->TOstatus == 1 ? 'tabOrderDivConfirmed' : '') }}" 
                                                onclick="closeOrSelect('{{$tabelOne->tableNr}}','{{$oneTOrder->id}}','{{$oneTOrder->OrderSasia}}')">
                                                    <p class="pl-1" style="width:60%; margin:0; padding-top:2px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height:0.9;">
                                                        <strong>
                                                            
                                                        <span id="tabOrderSasiaSpan{{$oneTOrder->id}}">{{$oneTOrder->OrderSasia}}x</span> {{$oneTOrder->OrderEmri}}
                                                        @if ($oneTOrder->OrderType != 'empty' && $oneTOrder->OrderExtra != 'empty')
                                                            <?php $theTy2D = explode('||',$oneTOrder->OrderType); ?>
                                                            <br>
                                                            <span style="font-size:0.6rem;">Type: {{$theTy2D[0]}} | 
                                                                Extra:
                                                                @foreach (explode('--0--',$oneTOrder->OrderExtra) as $exOne)
                                                                    @if($exOne != '')
                                                                        <?php $theEx2D = explode('||',$exOne); ?>
                                                                        @if ($loop->first)
                                                                            {{$theEx2D[0]}}
                                                                        @else
                                                                            , {{$theEx2D[0]}}
                                                                        @endif
                                                                    @endif    
                                                                @endforeach
                                                                
                                                            </span>
                                                        @elseif ($oneTOrder->OrderType != 'empty')
                                                            <?php $theTy2D = explode('||',$oneTOrder->OrderType); ?>
                                                            <br>
                                                            <span style="font-size:0.6rem;">Type: {{$theTy2D[0]}}</span>
                                                        @elseif ($oneTOrder->OrderExtra != 'empty')
                                                        <span style="font-size:0.6rem;">
                                                                Extra:
                                                                @foreach (explode('--0--',$oneTOrder->OrderExtra) as $exOne)
                                                                    @if($exOne != '')
                                                                        <?php $theEx2D = explode('||',$exOne); ?>
                                                                        @if ($loop->first)
                                                                            {{ $theEx2D[0] }}
                                                                        @else
                                                                            , {{ $theEx2D[0] }}
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                                
                                                            </span>
                                                        @endif
                                                        </strong>
                                                        @if($oneTOrder->OrderKomenti)
                                                            <br>
                                                            <span style="font-size:0.6rem;"><strong>{{__('adminP.comment')}}: </strong><span style="color:red;">{{$oneTOrder->OrderKomenti}}</span></span>
                                                        @endif
                                                    </p>
                                                    
                                                    <p style="width:20%; margin:0; font-size:0.6rem; line-height:1.1; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                        <strong>
                                                        {{$time2D[0]}}:{{$time2D[1]}} Uhr <br>
                                                        @if ($waiterData == Null)
                                                            Administrator
                                                        @else
                                                            {{$waiterData->name}}
                                                        @endif
                                                        </strong>
                                                    </p>
                                                    <p style="width:15%; margin:0; text-align:center;"><strong>{{number_format($oneTOrder->OrderQmimi, 2, '.', '')}}.-</strong></p>
                                                    @if($oneTOrder->OrderSasia == $oneTOrder->OrderSasiaDone)
                                                    <div style="width:5% ; background-color:green;">
                                                        <!-- <div style="width:60%; aspect-ratio: 1 / 1; border-radius:50%; background-color:green; margin:20% 0% 20% 0%;"></div> -->
                                                    </div>
                                                    @else
                                                    <div style="width:5%; background-color:red;">
                                                        <!-- <div style="width:60%; aspect-ratio: 1 / 1; border-radius:50%; background-color:red; margin:20% 0% 20% 0%;"></div> -->
                                                    </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                    @endforeach

                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            

                <div class="d-flex justify-content-between flex-wrap p-1" id="tabOrderBody{{$tabelOne->tableNr}}FinishedOrders">
                    @foreach(Orders::whereDate('created_at', Carbon::today())->where([['nrTable', $tabelOne->tableNr],['Restaurant',Auth::user()->sFor],['statusi','<',2]])->get() as $order)
                        @if ($loop->first)
                            <hr style="width: 100%; margin-top:2px; margin-bottom:2px;">
                            <p class="text-center" style="font-size:0.8rem; margin-bottom:4px; width:100%;"><strong>Bestellungen, die auf eine Zahlungsbestätigung warten</strong></p>
                        @endif
                        <?php $orderDate2D = explode(' ', $order->created_at); ?>
                        <div  class="countOrdersToConf d-flex justify-content-between flex-wrap p-2 mb-2" style="border:1px solid rgb(72,81,87); background-color:rgba(169, 236, 245, 0.4); border-radius:10px;">
                            <h6 class="modal-title text-center" style="width:100%; font-weight:bold;">
                                <div class="title">
                                    @if($order->userEmri != "empty" && $order->userEmri != "none")
                                    <span>{{$order->userEmri}}</span>
                                    @endif
                                </div>

                                <?php
                                    $shumaBefB = number_format($order->shuma - $order->tipPer,2,'.','');
                                    if($order->inCashDiscount > 0){
                                        $disc =  number_format($order->inCashDiscount,2,'.','');
                                        $totWZ = number_format($shumaBefB - $order->inCashDiscount,2,'.','');
                                    }else if($order->inPercentageDiscount > 0){
                                        $disc =  number_format($shumaBefB * ($order->inPercentageDiscount/100),2,'.','');
                                        $totWZ = number_format($shumaBefB - $disc,2,'.','');
                                    }else{
                                        $disc =  number_format(0,2,'.','');
                                        $totWZ = number_format($shumaBefB,2,'.','');
                                    }
                                    $tot = number_format($totWZ + $order->tipPer,2,'.','');
                                ?>


                                <div class="time">
                                    <span>{{__('adminP.time')}} {{explode(':', $orderDate2D[1])[0]}}:{{explode(':', $orderDate2D[1])[1]}}</span>
                                    @if($order->payM == 'Online')
                                    <span> Zahlung: {{$order->payM}} / </span>
                                    @elseif($order->payM == 'Kartenzahlung') 
                                    <span> Zahlung: Karte / </span>
                                    @else
                                    <span> Zahlung: Bar / </span>
                                    @endif
                                    <span> {{$tot}} <sup>{{__('adminP.currencyShow')}}</sup></span>
                                </div>
                                <div>
                                    @if($order->cuponOffVal > 0)
                                        <span> {{__('adminP.coupon')}}: <strong> - {{$order->cuponOffVal}} {{__('adminP.currencyShow')}}</strong> </span>
                                    @elseif($order->cuponProduct != 'empty')
                                        <span> {{__('adminP.forFree')}}: <strong>{{$order->cuponProduct}}</strong> </span>
                                    @endif
                                </div>
                            </h6>       
                            <hr style="width: 100%;">                   

                            @if($order->freeProdId != 0)
                                <p style="margin-top:-20px; width:100%; font-size:19px;" class="p-1"><strong>{{__('adminP.coupon')}} :</strong>
                                    @if(FreeProducts::find($order->freeProdId)->nameExt != 'none')
                                        {{FreeProducts::find($order->freeProdId)->nameExt}}
                                    @else
                                        {{Produktet::find(FreeProducts::find($order->freeProdId)->prod_id)->emri}}
                                    @endif
                                </p>
                                <!-- <hr style="width:100%; margin-top:-10px;"> -->
                            @endif
                                @if($order->tipPer != 0)
                                    <p  style="margin-top:-12px"> {{__('adminP.waiterTip')}}: <strong> {{$order->tipPer}} {{__('adminP.currencyShow')}} </strong></p>
                                @endif
                                @if($disc > 0)
                                    <p  style="margin-top:-12px"> Personalrabatt: <strong>{{$disc}} {{__('adminP.currencyShow')}} </strong></p>
                                @endif
                            
                            @foreach(explode('---8---',$order->porosia) as $produkti)
                                <?php
                                    $prod = explode('-8-', $produkti);
                                ?>
                                <div style="width:60%;">
                                    <p style="font-size:14px;"><strong><span class="mr-1" >{{$prod[3]}}x</span> {{$prod[0]}}   </strong>
                                    <br>
                                    @if($prod[5] != "empty")
                                        <!-- Tipi  -->
                                        <span style="font-size:12px;"><strong>{{$prod[5]}}</strong></span>
                                    @endif
                                    </p> 
                                <!-- Pershkrimi  -->
                                <p style="margin-top:-20px; font-size:10px;">{{$prod[1]}}</p> 
                                @if($prod[6] != '')
                                    <p  style="margin-top:-20px; opacity:0.75; font-size:10px;">{{__('adminP.comment')}} : {{$prod[6]}}</p>
                                @endif
                                </div>
                                <div style="width:25%; font-size:11px;">
                                    <?php $eStep = 1; ?>
                                    @foreach(explode('--0--', $prod[2]) as $ex)
                                            @if(!empty($ex) && $ex != "empty")
                                                @if($eStep++ == 1)
                                                    <p>+ {{explode('||', $ex)[0]}}</p>
                                                @else
                                                    <p style="margin-top:-15px;">+ {{explode('||', $ex)[0]}}</p>
                                                @endif
                                            @endif
                                    @endforeach

                                </div>
                                <div style="width:15%; font-weight:bold;">
                                    <p class="pt-2">{{$prod[4] / $prod[3]}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                    @if($prod[3] > 1)
                                        <p style="margin-top:-13px;" >{{$prod[4]}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                    @endif
                                </div>
                                    
                            @endforeach

                            <div style="width:100%;" class="d-flex flex-wrap justify-content-bentween">
                                @if ($order->statusi == 0)
                                <button onclick="ChangeStatusAjaxF('0','{{$order->id}}','999999','{{$order->nrTable}}')" 
                                    class="form-control btn btn-warning shadow-none statusBTNCl{{$order->id}}" style="width:49.5%; padding:2px; font-size:0.8rem; border:4px solid green;">{{__('adminP.waitingLine2')}}</button> 
                                @else
                                <button onclick="ChangeStatusAjaxF('0','{{$order->id}}','999999','{{$order->nrTable}}')" 
                                    class="form-control btn btn-warning shadow-none statusBTNCl{{$order->id}}" style="width:49.5%; padding:2px; font-size:0.8rem;">{{__('adminP.waitingLine2')}}</button>
                                @endif

                                @if ($order->statusi == 1)
                                <button onclick="ChangeStatusAjaxF('1','{{$order->id}}','999999','{{$order->nrTable}}')" 
                                    class="form-control btn btn-info shadow-none statusBTNCl{{$order->id}}" style="width:49.5%; padding:2px; font-size:0.8rem; border:4px solid green;">{{__('adminP.confirmed')}}</button>
                                @else
                                <button onclick="ChangeStatusAjaxF('1','{{$order->id}}','999999','{{$order->nrTable}}')" 
                                    class="form-control btn btn-info shadow-none statusBTNCl{{$order->id}}" style="width:49.5%; padding:2px; font-size:0.8rem;">{{__('adminP.confirmed')}}</button>
                                @endif
                                    <button onclick="showCommOrCancel('{{$order->id}}')" 
                                    class="form-control btn btn-danger shadow-none mt-1 statusBTNCl{{$order->id}}" style="width:49.5%; padding:2px; font-size:0.8rem;">{{__('adminP.canceled')}}</button>
                                
                                    <button onclick="ChangeStatusAjaxF('3','{{$order->id}}','999999','{{$order->nrTable}}')" 
                                    class="form-control btn btn-success shadow-none mt-1 statusBTNCl{{$order->id}}" style="width:49.5%; padding:2px; font-size:0.8rem;">{{__('adminP.finished')}}</button>
                            </div> 

                            <div style="display: none; width:100%;" id="cancelCommentDiv{{$order->id}}" class="form-group mb-2 mt-2">
                                <label for="exampleFormControlTextarea1"><strong>Kommentar zur Stornierung</strong></label>
                                <textarea id="cancelCommentInp{{$order->id}}" class="form-control shadow-none mb-1" rows="2"></textarea>
                                <button onclick="sendCancelRequest('2','{{$order->id}}','999999','{{$order->nrTable}}')" style="margin:0px;" class="mb-1 btn-block btn btn-dark shadow-none" type="button">
                                    <strong>Bestätigen</strong>
                                </button>
                                <div class="alert alert-danger text-center mt-1" style="display:none;" id="cancelCommentErr012{{$order->id}}">
                                    <strong>Bitte schreiben Sie zuerst einen Kommentar</strong>
                                </div>
                            </div>
                        
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endforeach

<div class="modal" id="selectToPayProds2UpSasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:20%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body d-flex flex-wrap justify-content-between">
                <p class="text-center mb-3" style="color:rgb(39,190,175); width:100%; font-size:1.2rem;">
                   
                </p>
                <div style="width:100%" class="d-flex flex-wrap justify-content-start" id="selectToPayProds2UpSasiBtns">
                    
                </div>

                <button class="btn btn-outline-danger shadow-none mt-4" onclick="selectToPayProds2UpSasiCancel()" style="width:100%; margin:0px;"><strong>Stornieren</strong></button>
            </div>
        </div>
    </div>
</div>  


<div class="modal" id="adminChangeTableModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:2%;">

    <input type="hidden" id="adminChangeTableModalTableSelectedId" value="0">
    <input type="hidden" id="adminChangeTableModalTableSelectedActive" value="2">
    <input type="hidden" id="adminChangeTableModalTableFromId" value="0">
    <input type="hidden" id="adminChangeTableModalTableSelectedTabOrders" value="0">
    <div id="adminChangeTableModalInputs">

    </div>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="d-flex justify-content modal-header">
                <h6 style="width: 90%;" class="modal-title"><strong>{{__('adminP.moveCustomersFromTable')}} <span id="adminChangeTableModalTableNrShow">---</span></strong></h6>
                <button style="width: 10%;" type="button" class="btn btn-outline-dark" onclick="resetAdminChangeTableModal()"> ✖ </button>
            </div>
            <div class="modal-body" id="adminChangeTableModalBody">
                
            </div>

            <div class="d-flex flex-wrap justify-content-start">
                <p class="pl-2" style="width: 100%; color:rgb(39,190,175);">
                    <strong style="width: 95%; margin-right:2.5%; margin-left:2.5%; font-size:0.6rem;"><i class="fas fa-table"></i> {{__('adminP.selectTableToMoveClient')}}</strong>
                    <div style="width: 95%; margin-right:2.5%; margin-left:2.5%;" class="d-flex justify-content-around">
                        <div style="width:33%;" class="d-flex justify-content-betweeen">
                            <i style="color:rgba(5,221,34,255); width:20%;" class="fas fa-stop"></i>
                            <!-- <img style="width:15%; height:auto;" src="storage/images/tableSt_green.PNG" alt="NotFound"> -->
                            <p class="pl-1" style="width:80%; font-weight:bold; font-size:0.6rem;">Gleicher Tisch</p>
                        </div>
                        <div style="width:33%;" class="d-flex justify-content-betweeen">
                            <i style="color: rgba(232,237,73,255); width:20%;" class="fas fa-stop"></i>
                            <!-- <img style="width:15%; height:auto;" src="storage/images/tableSt_yellow.PNG" alt="NotFound"> -->
                            <p class="pl-1" style="width:80%; font-weight:bold; font-size:0.6rem;">{{__('adminP.activeTable')}}</p>
                        </div>
                        <div style="width:33%;" class="d-flex justify-content-betweeen">
                            <i style="color:rgba(39,190,175,140); width:20%;" class="fas fa-stop"></i>
                            <!-- <img style="width:15%; height:auto;" src="storage/images/tableSt_qrorpa.PNG" alt="NotFound"> -->
                            <p class="pl-1" style="width:80%; font-weight:bold; font-size:0.6rem;">{{__('adminP.emptyTable')}}</p>
                        </div>
                    </div>
                </p>
            </div>
            <div class="d-flex flex-wrap justify-content-start" id="adminChangeTableModalTablesShow">
                           
            </div>
            <button class="btn btn-dark btn-block shadow-none" style="display: none;" id="adminChangeTableModalSendBtn"
            onclick="adminChangeTableModalRegister('{{Auth::user()->sFor}}')">
                {{__('adminP.submitTable')}}
            </button>
        </div>
    </div>
</div>

<div class="modal" id="selectPlateForAbrufen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:20%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body d-flex flex-wrap justify-content-between">
                <p class="text-center mb-3" style="color:rgb(39,190,175); width:100%; font-size:1.2rem;">
                </p>
                <div style="width:100%" class="d-flex flex-wrap justify-content-start" id="selectPlateForAbrufenBtns">
                                    
                </div>

                <button class="btn btn-outline-danger shadow-none mt-4" onclick="selectPlateForAbrufenCancel()" style="width:100%; margin:0px;"><strong>Stornieren</strong></button>
            </div>
        </div>
    </div>
</div>  


@include('adminPanel.tablePageTel.splitTheBill')
@include('adminPanel.tablePageTel.splitTheBillRechnung')

@include('adminPanel.tablePageTel.tableIndexActiveOrMdScriptVer_2')
@include('adminPanel.tablePageTel.tableIndexPayAllCode')
@include('adminPanel.tablePageTel.tableIndexPaySelectedCode')
@include('adminPanel.tablePageTel.billTabletModal')