<?php

use App\User;
use App\Orders;
use App\resPlates;
use Carbon\Carbon;
use App\TableQrcode;
use App\waiterActivityLog;
use App\newOrdersAdminAlert;

    use App\TabOrder;
    use App\billTabletsReg;
    use Illuminate\Support\Facades\Auth;
    
    $allBillT = billTabletsReg::where('toRes',Auth::user()->sFor)->get();
?>
<style>
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
    <input type="hidden" id="tableNeedsToReset{{$tabelOne->tableNr}}" value="0">

    <div class="modal" id="tabOrder{{$tabelOne->tableNr}}" tabindex="-1" role="dialog" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header d-flex justify-content-between" style="width:100%;">
                    <span style="font-size:30px; width: 20%;"><strong>Tisch: {{$tabelOne->tableNr}}</strong></span>
                    <div id="tabOrderTotPriceDiv{{$tabelOne->tableNr}}">
                        @if ($tabelOne->kaTab != 0)
                            <span style="font-size:23px; width: 30%; " class="pl-2"> {{__('adminP.total')}} : <strong><span id="tabOrderTotPriceDiv{{$tabelOne->tableNr}}Value">{{ number_format(TabOrder::where([['tabCode',$tabelOne->kaTab],['status','<',2]])->sum('OrderQmimi'),2,'.','') }}</span><sup>{{__('adminP.currencyShow')}}</sup></strong></span>
                        @else
                            <span style="font-size:23px; width: 30%; " class="pl-2"><strong> <span id="tabOrderTotPriceDiv{{$tabelOne->tableNr}}Value">0.00</span> <sup>{{__('adminP.currencyShow')}}</sup></strong></span>
                        @endif
                    </div>
                    <span style="width:8%;"></span>

                    <button style="width: 8%; margin:0px; padding:0px;" type="button" class="btn shadow-none" onclick="printActiveOrdersOnTable('{{$tabelOne->tableNr}}')">
                        <i class="pt-3 fa-2x fa-solid fa-receipt"></i>
                    </button>
                  
                    @if (count($allBillT) > 0)
                    <button style="width: 8%; margin:0px; padding:0px;" type="button" class="btn shadow-none" data-toggle="modal" data-target="#billTabletsChooseModal" onclick="openBillTabletsChoose('{{$tabelOne->tableNr}}')">
                        <img src="storage/icons/billTabletIconSmall.png" style="width:100%;" alt="">
                    </button>
                    @else
                    <span style="width:8%;"></span>
                    @endif
                    <span style="width:16%;"></span>
                    <button style="width: 10%;" type="button" class="btn btn-outline-dark shadow-none" data-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <!-- extra services Div -->
                <div class="d-flex justify-content-between" style="width:100%;" id="extraServicesOnTable{{$tabelOne->tableNr}}">
                    <button id="payAllProd{{$tabelOne->tableNr}}" class="btn btn-dark"  onclick="prepPayAllProds('{{$tabelOne->tableNr}}','{{Auth::user()->sFor}}')"
                        style="width:24.5%;" data-toggle="modal" data-target="#payAllPhaseOne">
                        {{__('adminP.payForAllProducts')}}
                    </button>
                    <button id="paySelProd{{$tabelOne->tableNr}}" class="btn btn-dark" onclick="prepPaySelProds('{{$tabelOne->tableNr}}','{{Auth::user()->sFor}}')"
                        style="width:24.5%; display:none;" data-toggle="modal" data-target="#payAllPhaseOneSel"> {{__('adminP.payForSelectedProducts')}}
                    </button>

                    <button style="width:24.5%;" class="btn btn-outline-success shadow-none" data-toggle="modal" data-target="#newTabOrderModal" onclick="openNewTabOrderModalFromATOM('{{$tabelOne->tableNr}}')">
                        <strong>{{__('adminP.newProduct')}}</strong>
                    </button>

                    <button style="width:24.5%;" class="btn btn-outline-success shadow-none" data-toggle="modal" data-target="#adminChangeTableModal"
                    onclick="prepTableChangeModal('{{$tabelOne->id}}','{{$tabelOne->tableNr}}','{{$tabelOne->kaTab}}')">
                        <strong>{{__('adminP.tableNoToChange')}}</strong>
                    </button>

                    <button style="width:24.5%;" id="confTabOrdersBtn0O{{$tabelOne->tableNr}}O{{$tabelOne->kaTab}}" class="btn btn-dark shadow-none" onclick="confTabOrders('0','{{$tabelOne->tableNr}}','{{$tabelOne->kaTab}}')">
                        <strong>Bestätigen Sie alle Bestellungen</strong>
                    </button>
                </div>
                <!-- extra services Div End -->

                <!-- Mesage & plates Div -->
                <div class="d-flex flex-wrap justify-content-between" id="mesageDivAdTCl{{$tabelOne->id}}">
                    <div style="padding:16px; width:50%;" class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Rechnung teilen</span>
                        </div>
                        <input style="padding-bottom: 6px; height:100%;" id="splitTheBillClNr{{$tabelOne->tableNr}}" type="number" class="form-control shadow-none" placeholder="Anzahl der Kunden">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary shadow-none" type="button" onclick="splitTheBillInitiate('{{$tabelOne->tableNr}}')">
                                Fortfahren
                            </button>
                        </div>
                    </div>

                    <div style="padding:16px; width:50%;" class="d-flex justify-content-between">
                        <input type="text" style="width:49.5%; margin-top:8px; border-radius:5px;" id="tableMessageIn{{$tabelOne->id}}" 
                            class="form-control shadow-none" placeholder="die Meldung">
                        <button class="btn btn-info" style="width:49.5%; margin:0px; margin-top:8px;" onclick="sendMsgToCl('{{$tabelOne->tableNr}}','{{$tabelOne->Restaurant}}','{{$tabelOne->id}}')">
                            <strong>Senden<i class="fas fa-paper-plane"></i></strong>
                        </button>
                    </div>

                    <div style="width:100%; display:none;" class="mt-1 text-center alert alert-danger" id="splitTheBillError01{{$tabelOne->tableNr}}">
                        <strong>Geben Sie eine gültige Kundenanzahl an!</strong>
                    </div>

                    <div class="d-flex justify-content-between pl-2 pr-2" id="activeClientsOnTable{{$tabelOne->tableNr}}">
                        <?php $activeClientsPNr = array(); ?> 
                        <input type="hidden" id="mesageDivAdTClSelectedClient{{$tabelOne->tableNr}}" value="0">
                        @if ($tabelOne->kaTab != 0)
                            @foreach(TabOrder::where([['tab_orders.tabCode',$tabelOne->kaTab]])
                            ->join('tab_verification_p_numbers', 'tab_orders.id', '=', 'tab_verification_p_numbers.tabOrderId')
                            ->select('tab_verification_p_numbers.phoneNr as phoneNr')
                            ->orderByDesc('tab_orders.created_at')->get() as $oneTOrder)

                            
                                <?php 
                                    if ($oneTOrder!= NULL && !in_array($oneTOrder->phoneNr, $activeClientsPNr)){
                                        array_push($activeClientsPNr, $oneTOrder->phoneNr);
                                        if($oneTOrder->phoneNr != '0770000000'){
                                            if(str_contains($oneTOrder->phoneNr,'|')){
                                                echo '<button style="font-weight:bold;" class="btn btn-outline-success ml-1 mr-1 allActiveClientsOnTable" id="activeClientsOnTable'.$tabelOne->tableNr.'O'.$oneTOrder->phoneNr.'">Ghost: '.explode('|',$oneTOrder->phoneNr)[1].'</button>';
                                            }else{
                                                echo '<button onclick="selectClToSendMsg(\''.$tabelOne->tableNr.'\',\''.$oneTOrder->phoneNr.'\')" style="font-weight:bold;" class="btn btn-outline-success ml-1 mr-1 allActiveClientsOnTable" id="activeClientsOnTable'.$tabelOne->tableNr.'O'.$oneTOrder->phoneNr.'">+41 *** *'.substr($oneTOrder->phoneNr, -6).'</button>';
                                            }
                                        }
                                    }
                                ?> 
                            @endforeach 
                        @endif
                    </div>

                    <div style="width:100%;" class="d-flex flex-wrap justify-content-between pl-2 pr-2">
                        <p style="width:100%; font-size:1.3rem;" class="text-center mb-1"><strong>Nennen Sie die Produkte des Kochs beim Teller</strong></p>
                        @foreach (resPlates::where([['toRes',Auth::user()->sFor],['isActive',1]])->get() as $plateOne)
                            <button class="btn btn-info shadow-none mb-1" style="width:24.5%; margin:0px; font-size:1.2rem;" id="AbrufenByPlateBtn{{$plateOne->id}}O{{$tabelOne->tableNr}}" onclick="AbrufenByPlate('{{$plateOne->id}}','{{$plateOne->nameTitle}}','{{$tabelOne->tableNr}}')">
                                <strong>{{$plateOne->nameTitle}}</strong>
                            </button>
                        @endforeach
                    </div>
                    <!-- <p style="width:20%;"></p> -->

                    <p id="sendMsgAlertS{{$tabelOne->id}}" class=" alert alert-success textcenter" style="width:100%;; display:none;">{{__('adminP.messageSent')}}</p>
                    <p id="sendMsgAlertE{{$tabelOne->id}}" class=" alert alert-danger textcenter" style=" width:100%; display:none;">{{__('adminP.writeValidMessage')}}</p>
                    <p id="sendMsgAlertE2{{$tabelOne->id}}" class=" alert alert-danger textcenter" style=" width:100%; display:none;">{{__('adminP.selectAClForMsg')}}</p>
                    <p style="width:100%;" id="sendMsgAlert{{$tabelOne->id}}"></p>
                </div>
                <!-- Mesage & plates Div End-->

                <input type="hidden" id="closeOrSelected{{$tabelOne->tableNr}}" val="0">

                <div style="width:100%;" id="tabOrderBody{{$tabelOne->tableNr}}">
                    <div id="tabOrderBody{{$tabelOne->tableNr}}ActiveOrders">
                        @if ($tabelOne->kaTab != 0)

                            @foreach ($activeClientsPNr as $activeClientsPNrOneNew)
                                <div class="card mb-2" style="width: 100%;">
                                    <div class="card-header d-flex">
                                        @if($activeClientsPNrOneNew == '0770000000')
                                            <span style="width: 50%;"><strong>{{__('adminP.administrator')}}</strong></span>
                                            <button style="width: 50%; margin:0px;" class="btn btn-dark shadow-none" id="confTabOrdersBtn{{$activeClientsPNrOneNew}}O{{$tabelOne->tableNr}}O{{$tabelOne->kaTab}}" onclick="confTabOrders('{{$activeClientsPNrOneNew}}','{{$tabelOne->tableNr}}','{{$tabelOne->kaTab}}')">Bestätigen Sie alles für diesen Kunden</button>
                                        @elseif(str_contains($activeClientsPNrOneNew, '|'))
                                            <span style="width: 100%;"><strong>{{__('adminP.ghost')}} : {{explode('|',$activeClientsPNrOneNew)[1]}}</strong></span>
                                        @else
                                            <span style="width: 50%;"><strong>+41 *** *{{substr($activeClientsPNrOneNew, -6)}}</strong></span>
                                            <button style="width: 50%; margin:0px;" class="btn btn-dark shadow-none" id="confTabOrdersBtn{{$activeClientsPNrOneNew}}O{{$tabelOne->tableNr}}O{{$tabelOne->kaTab}}" onclick="confTabOrders('{{$activeClientsPNrOneNew}}','{{$tabelOne->tableNr}}','{{$tabelOne->kaTab}}')">Bestätigen Sie alles für diesen Kunden</button>
                                        @endif
                                    </div>
                                    <ul class="list-group list-group-flush" id="adminOrdersShowUl{{$tabelOne->tableNr}}">
                                        @foreach(TabOrder::where('tab_orders.tabCode',$tabelOne->kaTab)
                                        ->join('tab_verification_p_numbers', 'tab_orders.id', '=', 'tab_verification_p_numbers.tabOrderId')
                                        ->select('tab_orders.status as TOstatus', 'tab_orders.*', 'tab_verification_p_numbers.phoneNr as phoneNr')
                                        ->where('tab_verification_p_numbers.phoneNr',$activeClientsPNrOneNew)
                                        ->orderByDesc('tab_orders.created_at')->get() as $oneTOrder)
                                            <?php
                                                if($notiActi[0] == 1){
                                                    if(newOrdersAdminAlert::where([['adminId',Auth::User()->id],['tableNr',$oneTOrder->tableNr],['tabOrderId',$oneTOrder->id],['statActive','1']])->first() != NULL){ $hasAlertOrderList = True; 
                                                    }else{ $hasAlertOrderList = False; }
                                                }else{ $hasAlertOrderList = False; }
                                                $thePlateOfTO = resPlates::find($oneTOrder->toPlate);
                                            ?>
                                            <li class="list-group-item" style="padding:2px;" id="tabOrderTabInsLi{{$oneTOrder->id}}"
                                            onclick="checkAdminAlertOrder('{{Auth::User()->id}}', '{{$oneTOrder->tableNr}}', '{{$oneTOrder->id}}')">
                                                @if($oneTOrder->TOstatus == 0)
                                                <div class="d-flex flex-wrap justify-content-between p-2 mb-1 {{$hasAlertOrderList ? 'table-glow-adminAlert' : ''}}"
                                                style="border:1px solid gray; border-radius:15px; background-color:rgb(196, 245, 243)" id="prodListinOr{{$oneTOrder->id}}">
                                                @elseif($oneTOrder->TOstatus == 1)
                                                <div class="d-flex flex-wrap justify-content-between p-2 mb-1 {{$hasAlertOrderList ? 'table-glow-adminAlert' : ''}}" 
                                                style="border:1px solid gray; border-radius:15px; background-color:rgb(159, 245, 188)" id="prodListinOr{{$oneTOrder->id}}">
                                                @elseif($oneTOrder->TOstatus == 9)
                                                <div class="d-flex flex-wrap justify-content-between p-2 mb-1 {{$hasAlertOrderList ? 'table-glow-adminAlert' : ''}}" 
                                                style="border:1px solid gray; border-radius:15px; background-color:rgb(252, 134, 134)" id="prodListinOr{{$oneTOrder->id}}">
                                                @endif
                                                    <div style="width:15%; font-size:18px;">
                                                    
                                                        @if($oneTOrder->OrderSasia == $oneTOrder->OrderSasiaDone)
                                                        <p style="margin:0px; font-size:1.6rem; color:darkgreen;"><strong>Bereit</strong></p>
                                                        @else
                                                        <p style="margin:0px; font-size:1.4rem; color:red;"><strong>Nicht bereit</strong></p>

                                                            @if($oneTOrder->abrufenStat == 0)
                                                                <button id="AbrufenProductBtn{{$oneTOrder->id}}O{{$oneTOrder->tableNr}}" style="width:100%; margin:0px;" class="btn btn-info" onclick="AbrufenProduct('{{$oneTOrder->id}}','{{$oneTOrder->tableNr}}')">
                                                                    <strong>Abrufen</strong>
                                                                </button>
                                                            @elseif ($oneTOrder->abrufenStat == 1)
                                                                <p style="margin:0px; font-size:1.6rem; color:darkgreen;">
                                                                    <strong> <i style="color:darkgreen;" class="fa-solid fa-check"></i> Abrufen</strong>
                                                                </p>
                                                            @endif
                                                        @endif

                                                        <p class="pb-1 pt-1">{{__('adminP.time')}}: <strong>{{substr(explode(' ',$oneTOrder->created_at)[1], 0, 5)}}</strong></p>
                                                        <p style="margin-top:-15px;">{{__('adminP.total')}}: <strong>{{$oneTOrder->OrderQmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong> </p>
                                                        @if($oneTOrder->phoneNr == '0770000000')
                                                            <?php
                                                                $waLog = waiterActivityLog::where([['actType','newProdWa'],['actId',$oneTOrder->id]])->first();
                                                                if($waLog != Null ){ $waiterData = User::find($waLog->waiterId); }else{ $waiterData = Null; }
                                                            ?>
                                                            @if ($waiterData == Null)
                                                                <p style="margin-top:-15px;"><strong>Administrator</strong></p>
                                                            @else
                                                                <p style="margin-top:-15px;"><strong>{{$waiterData->name}}</strong></p>
                                                            @endif
                                                            
                                                        @elseif(str_contains($oneTOrder->phoneNr, '|'))
                                                            <p style="margin-top:-15px;"><strong>{{__('adminP.ghost')}} : {{explode('|',$oneTOrder->phoneNr)[1]}}</strong></p>
                                                        @else
                                                            <p style="margin-top:-15px;"><strong>*** *{{substr($oneTOrder->phoneNr, -6)}}</strong></p>
                                                        @endif
                                                    </div>
                                                    <div style="width:45%;" class="pl-3">
                                                        @if ($thePlateOfTO != Null)
                                                            <p style="margin:0px; font-size:1.4rem; color:rgb(72,81,87);"><strong><i class="fa-solid fa-utensils"></i> {{$thePlateOfTO->nameTitle}}</strong></p>
                                                        @endif
                                                        <h3><strong><ins>{{$oneTOrder->OrderSasia}}X</ins> {{$oneTOrder->OrderEmri}}</strong></h3>
                                                        <p>{{$oneTOrder->OrderPershkrimi}}</p>
                                                        @if($oneTOrder->OrderType != 'empty')
                                                            <p style="font-size:18px; margin-top:-10px;"><strong>{{__('adminP.type')}}:</strong> {{explode('||',$oneTOrder->OrderType)[0]}} </p>
                                                        @endif
                                                        @if($oneTOrder->OrderKomenti)
                                                            <p style="font-size:15px; margin-top:-10px;"><strong>{{__('adminP.comment')}}:</strong> {{$oneTOrder->OrderKomenti}} </p>
                                                        @endif
                                                    </div>
                                                    <div style="width:25%;" class="d-flex flex-wrap">
                                                        <?php $countEx = 1;?>
                                                        @if($oneTOrder->OrderExtra != null)
                                                            @foreach(explode('--0--',$oneTOrder->OrderExtra) as $oneOExt)
                                                                @if($oneOExt != '' && $oneOExt != 'empty')
                                                                    @if($countEx++ == 1)
                                                                        <p style="width:70%;">{{explode('||',$oneOExt)[0]}}</p>
                                                                        <p style="width:30%;">{{explode('||',$oneOExt)[1]}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                                                    @else
                                                                        <p style="width:70%; margin-top:-20px;">{{explode('||',$oneOExt)[0]}}</p>
                                                                        <p style="width:30%; margin-top:-20px;">{{explode('||',$oneOExt)[1]}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div style="width:15%">
                                                        @if(!str_contains($oneTOrder->phoneNr, '|'))
                                                            @if($oneTOrder->TOstatus == 0)
                                                                <button style="margin: 0px;" class="mt-2 btn btn-block btn-success shadow-none" id="AnnehmenBtn{{$oneTOrder->id}}" onclick="chStatusTabO('{{$tabelOne->tableNr}}','{{$oneTOrder->id}}','{{$oneTOrder->phoneNr}}')">Bestätigen </button>
                                                                <button style="margin: 0px;" class="mt-2 btn btn-outline-danger btn-block shadow-none" data-toggle="modal" data-target="#sendOrderForDeleteConfModal" onclick="prepDeleteTabOr('{{$oneTOrder->id}}','{{$tabelOne->tableNr}}')"> <i class="fas fa-trash-alt"></i> <strong>{{__('adminP.extinguish')}}</strong> </button>
                                                            @elseif($oneTOrder->TOstatus == 1)
                                                                <button class="mt-2 btn btn-dark btn-block shadow-none" style="margin-top: 10px; margin-right:0px; margin-left:0px;" id="AnnehmenBtn{{$oneTOrder->id}}" onclick="chStatusTabODeConf('{{$tabelOne->tableNr}}','{{$oneTOrder->id}}','{{$oneTOrder->phoneNr}}')"> Unbestätigt </button>
                                                                @if($oneTOrder->phoneNr == '0770000000')
                                                                <button class="mt-2 btn btn-outline-danger btn-block shadow-none" data-toggle="modal" data-target="#sendOrderForDeleteConfModal" onclick="prepDeleteTabOr('{{$oneTOrder->id}}','{{$tabelOne->tableNr}}')" style="margin-top:10px; margin-right:0px; margin-left:0px;"> <i class="fas fa-trash-alt"></i> <strong>{{__('adminP.extinguish')}}</strong> </button>
                                                                @endif    
                                                            @elseif($oneTOrder->TOstatus == 9)
                                                                <button class="mt-2 btn btn-block" style="margin-top: 10px; margin-right:0px; margin-left:0px;"> {{__('adminP.orderCancelled')}} </button>
                                                            @endif
                                                        @endif
                                                        <button class="mt-2 btn-outline-dark btn btn-block shadow-none" id="closeOrBtn{{$oneTOrder->id}}" style="width:100%; margin:0px;"
                                                            onclick="closeOrSelect('{{$tabelOne->tableNr}}','{{$oneTOrder->id}}','{{$oneTOrder->OrderSasia}}')">Auswählen</button> 
                                                        <input type="hidden" id="closeOrMaxSasiaProd{{$oneTOrder->id}}" value="{{$oneTOrder->OrderSasia}}"> 
                                                    </div>

                                                    
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="d-flex justify-content-between flex-wrap p-1" id="tabOrderBody{{$tabelOne->tableNr}}FinishedOrders">
                        @foreach(Orders::whereDate('created_at', Carbon::today())->where([['nrTable', $tabelOne->tableNr],['Restaurant',Auth::user()->sFor],['statusi','<',2]])->get() as $allOrds)
                            @if ($loop->first)
                                <hr style="width: 100%; margin-top:2px; margin-bottom:2px;">
                                <p class="text-center" style="font-size:1.4rem; margin-bottom:4px; width:100%;"><strong>Bestellungen, die auf eine Zahlungsbestätigung warten</strong></p>
                            @endif
                            <?php  $allOrderDate2D = explode(' ', $allOrds->created_at)[1]; ?>
                            <div class="countOrdersToConf" style="width:20%;" >
                                <p class="pt-2"><strong>{{__('adminP.time')}} :</strong>{{explode(':',$allOrderDate2D)[0]}}:{{explode(':',$allOrderDate2D)[1]}}</p>
                                <p style="margin-top:-10px; font-size:18px;"><strong>
                                    @if($allOrds->payM == 'Online') 
                                        {{$allOrds->payM}}
                                    @elseif($allOrds->payM == 'Kartenzahlung') 
                                        Karte
                                    @else
                                        <!-- Cash  -->
                                        Bar
                                    @endif
                                </strong> 
                                </p>

                                <?php
                                    $shumaBefB = number_format($allOrds->shuma - $allOrds->tipPer,2,'.','');
                                    if($allOrds->inCashDiscount > 0){
                                        $disc =  number_format($allOrds->inCashDiscount,2,'.','');
                                        $totWZ = number_format($shumaBefB - $allOrds->inCashDiscount,2,'.','');
                                    }else if($allOrds->inPercentageDiscount > 0){
                                        $disc =  number_format($shumaBefB * ($allOrds->inPercentageDiscount/100),2,'.','');
                                        $totWZ = number_format($shumaBefB - $disc,2,'.','');
                                    }else{
                                        $disc =  number_format(0,2,'.','');
                                        $totWZ = number_format($shumaBefB,2,'.','');
                                    }
                                    $tot = number_format($totWZ + $allOrds->tipPer,2,'.','');
                                ?>
                                <p style="margin-top:-10px; font-size:18px;"><strong>Bezahlt: {{$tot}} {{__('adminP.currencyShow')}} </strong> </p>
                                @if ($disc > 0)
                                <p style="margin-top:-10px; font-size:18px;"><strong>Personalrabatt: {{$disc}} {{__('adminP.currencyShow')}} </strong> </p>
                                @endif
                            </div>
                            <div style="border:1px solid gray; width:80%;" class="p-2 mt-2 mb-2  d-flex justify-content-between flex-wrap">
                                <div style="border-bottom:1px solid gray; width:100%;" class="d-flex justify-content-between flex-wrap mb-4">
                                    <p style="width:33%; font-size:20px;" class="text-left">{{__('adminP.waiterTip')}}: <strong>{{$allOrds->tipPer}} {{__('adminP.currencyShow')}}</strong></p>
                                    @if($allOrds->cuponOffVal != 0)
                                        <p style="width:33%; font-size:20px;" class="text-center">{{__('adminP.coupon')}}: <strong>- {{$allOrds->cuponOffVal}} {{__('adminP.currencyShow')}}</strong></p>
                                    @endif
                                    @if($allOrds->cuponProduct != 'empty')
                                        <p style="width:25%; font-size:20px;" class="text-center">{{__('adminP.forFree')}}: <strong>{{$allOrds->cuponProduct}}</strong></p>
                                    @endif
                                </div>

                                @if($allOrds->porosia != '')
                                    @foreach(explode('---8---',$allOrds->porosia) as $produkti)
                                        <?php $prod = explode('-8-', $produkti); ?>
                                        <div style="width:59.5%; border-bottom:1px solid lightgray;">
                                            <p style="font-size:21px;">{{$prod[3]}}X <strong>{{$prod[0]}}</strong>
                                                @if($prod[5] != "" && $prod[5] != "empty")
                                                    ( {{$prod[5]}} )
                                                @endif
                                            </p> 
                                            <p style="margin-top:-20px;">{{$prod[1]}}</p> 
                                            @if($prod[6] != '')
                                            <p  style="margin-top:-20px; opacity:0.75">{{__('adminP.comment')}} : {{$prod[6]}}</p>
                                            @endif
                                        </div>
                                        <div style="width:25%; border-bottom:1px solid lightgray;">
                                            <?php
                                                $eStep = 1;
                                            ?>
                                            @if($prod[2] != 'empty')
                                                @foreach(explode('--0--', $prod[2]) as $ex)
                                                        @if(!empty($ex) ||$ex != "")
                                                            @if($eStep++ == 1)
                                                                <p>{{explode('||', $ex)[0]}}</p>
                                                            @else
                                                                <p style="margin-top:-15px;">{{explode('||', $ex)[0]}}</p>
                                                            @endif
                                                        @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div style="width:15%; border-bottom:1px solid lightgray;">
                                            <p class="pl-3">{{$prod[4]/$prod[3]}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                            @if($prod[3] > 1)
                                            <p class="pl-3" style="margin-top:-13px;" >{{$prod[4]}} <sup>{{__('adminP.currencyShow')}}</sup>  ({{$prod[3]}}x) </p>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                                <div style="width: 100%;" class="d-flex justify-content-between">
                                    @if ($allOrds->statusi == 0)
                                    <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                        <button onclick="ChangeStatusAjaxF('0','{{$allOrds->id}}','999999','{{$allOrds->nrTable}}')" style="border:4px solid green;"
                                        class="form-control btn btn-warning btn-block statusBTNCl{{$allOrds->id}}"">{{__('adminP.waitingLine2')}}</button>
                                    </div>
                                    @else
                                    <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                        <button onclick="ChangeStatusAjaxF('0','{{$allOrds->id}}','999999','{{$allOrds->nrTable}}')" 
                                        class="form-control btn btn-warning btn-block statusBTNCl{{$allOrds->id}}"">{{__('adminP.waitingLine2')}}</button>
                                    </div>
                                    @endif
                                    @if ($allOrds->statusi == 1)
                                    <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                        <button onclick="ChangeStatusAjaxF('1','{{$allOrds->id}}','999999','{{$allOrds->nrTable}}')" style="border:4px solid green;"
                                        class="form-control btn btn-info btn-block statusBTNCl{{$allOrds->id}}"">{{__('adminP.confirmed')}}</button>
                                    </div>
                                    @else
                                    <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                        <button onclick="ChangeStatusAjaxF('1','{{$allOrds->id}}','999999','{{$allOrds->nrTable}}')" 
                                        class="form-control btn btn-info btn-block statusBTNCl{{$allOrds->id}}"">{{__('adminP.confirmed')}}</button>
                                    </div>
                                    @endif
                                    <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                        <button onclick="showCommOrCancel('{{$allOrds->id}}')" 
                                        class="form-control btn btn-danger btn-block statusBTNCl{{$allOrds->id}}"">{{__('adminP.canceled')}}</button>
                                    </div>
                                    <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                        <button onclick="ChangeStatusAjaxF('3','{{$allOrds->id}}','999999','{{$allOrds->nrTable}}')" 
                                        class="form-control btn btn-success btn-block statusBTNCl{{$allOrds->id}}"">{{__('adminP.finished')}}</button>
                                    </div>
                                </div>

                                            
                                <div style="display: none; width:100%;" id="cancelCommentDiv{{$allOrds->id}}" class="form-group mb-2 mt-2">
                                    <label for="exampleFormControlTextarea1"><strong>Kommentar zur Stornierung</strong></label>
                                    <textarea id="cancelCommentInp{{$allOrds->id}}" class="form-control shadow-none mb-1" rows="2"></textarea>
                                    <button onclick="sendCancelRequest('2','{{$allOrds->id}}','999999','{{$allOrds->nrTable}}')" style="margin:0px;" class="mb-1 btn-block btn btn-dark shadow-none" type="button">
                                        <strong>Bestätigen</strong>
                                    </button>
                                    <div class="alert alert-danger text-center mt-1" style="display:none;" id="cancelCommentErr01{{$allOrds->id}}">
                                        <strong>Bitte schreiben Sie zuerst einen Kommentar</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div> <!-- tabOrderBody End -->
            </div>
        </div>
    </div>

@endforeach


@include('adminPanel.tablePage.splitTheBill')
@include('adminPanel.tablePage.splitTheBillRechnung')



<div class="modal" id="sendOrderForDeleteConfModal" tabindex="-1" role="dialog" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:4%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body d-flex flex-wrap justify-content-between">
                <p class="text-center mb-3" style="color:rgb(39,190,175); width:100%;">
                    <strong>Sind Sie sicher, dass Sie diese Bestellung löschen möchten #<span id="sendOrderForDeleteConfTabOrShow"></span></strong>
                </p>
                <button class="btn btn-outline-dark shadow-none" onclick="closesendOrderForDeleteConfModal()" style="width:49%; margin:0px;"><strong>Nein</strong></button>
                <button class="btn btn-outline-dark shadow-none" onclick="sendCodeForTabOrderRemove()" style="width:49%; margin:0px;"><strong>Ja</strong></button>
            
                <p class="text-center mt-5 mb-2" style="color:rgb(72,81,87);"><strong>Der Grund für diese Aktion (optional)</strong></p>
                <input type="text" id="sendOrderForDeleteConfModalKom" value="" class="form-control shadow-none">
                
                <div class="alert alert-danger mt-1" id="sendOrderForDeleteConfModalErr01" style="display:none; width:100%;" class="shadow-none text-center">
                    <strong>Bitte geben Sie zunächst einen triftigen Grund ein!</strong>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="sendOrderForDeleteConfTabOrId" value="0">
<input type="hidden" id="sendOrderForDeleteConfTableNr" value="0">







<div class="modal" id="selectToPayProds2UpSasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:20%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body d-flex flex-wrap justify-content-between">
                 <p class="text-center mb-3" style="color:rgb(39,190,175); width:100%; font-size:1.2rem;">
                    <strong>Wie viele Produkte möchen Sie bezahlen?</strong>
                </p>
                <div style="width:100%" class="d-flex flex-wrap justify-content-start" id="selectToPayProds2UpSasiBtns">
                    
                </div>

                <button class="btn btn-outline-danger shadow-none mt-4" onclick="selectToPayProds2UpSasiCancel()" style="width:100%; margin:0px;"><strong>Stornieren</strong></button>
            </div>
        </div>
    </div>
</div> 



<div class="modal" id="adminChangeTableModal" tabindex="-1" role="dialog" data-backdrop="false" 
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
                <h3 style="width: 90%;" class="modal-title"><strong>{{__('adminP.moveCustomersFromTable')}} <span id="adminChangeTableModalTableNrShow">---</span></strong></h3>
                <button style="width: 10%;" type="button" class="btn btn-outline-dark" onclick="resetAdminChangeTableModal()"> ✖ </button>
            </div>
            <div class="modal-body" id="adminChangeTableModalBody">
                
            </div>

            <div class="d-flex flex-wrap justify-content-start">
                <p class="pl-2" style="width: 100%; color:rgb(39,190,175);">
                    <strong style="width: 100%;"><i class="fas fa-table"></i> {{__('adminP.selectTableToMoveClient')}}</strong>
                    <div style="width: 100%;" class="d-flex justify-content-around">
                        <div style="width:27%;" class="d-flex justify-content-betweeen">
                            <i style="color:rgba(5,221,34,255); width:20%;" class="fas fa-stop"></i>
                            <!-- <img style="width:15%; height:auto;" src="storage/images/tableSt_green.PNG" alt="NotFound"> -->
                            <p class="pl-3" style="width:80%; font-weight:bold;">Gleicher Tisch</p>
                        </div>
                        <div style="width:27%;" class="d-flex justify-content-betweeen">
                            <i style="color: rgba(232,237,73,255); width:20%;" class="fas fa-stop"></i>
                            <!-- <img style="width:15%; height:auto;" src="storage/images/tableSt_yellow.PNG" alt="NotFound"> -->
                            <p class="pl-3" style="width:80%; font-weight:bold;">{{__('adminP.activeTable')}}</p>
                        </div>
                        <div style="width:27%;" class="d-flex justify-content-betweeen">
                            <i style="color:rgba(39,190,175,140); width:20%;" class="fas fa-stop"></i>
                            <!-- <img style="width:15%; height:auto;" src="storage/images/tableSt_qrorpa.PNG" alt="NotFound"> -->
                            <p class="pl-3" style="width:80%; font-weight:bold;">{{__('adminP.emptyTable')}}</p>
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




@include('adminPanel.tablePage.tableIndexActiveOrMdScript')
@include('adminPanel.tablePage.tableIndexPayAllCode')
@include('adminPanel.tablePage.tableIndexPaySelectedCode')
@include('adminPanel.tablePage.billTabletModal')