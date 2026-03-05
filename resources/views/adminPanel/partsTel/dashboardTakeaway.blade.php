<?php

use App\Takeaway;
use App\Restorant;
use App\taDeForCookOr;
use App\onlinePayQRCStaf;
use App\OPSaferpayReference;
use App\ordersTaOnlinePayStafTemp;
use Illuminate\Support\Facades\Auth;
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Takeaway']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Orders;
    use App\StatusWorker;
    use App\User;
    use Carbon\Carbon;
    
    $thisRestaurantId = Auth::user()->sFor;

    $theRes = Restorant::find(Auth::user()->sFor);

    $nowDate = date('Y-m-d');
    $nowDate2D = explode('-',$nowDate);

    $resClock = explode('->',$theRes->reportTimeArc);
    $resClock1_2D = explode(':',$resClock[0]);
    $resClock2_2D = explode(':',$resClock[1]);

    $today_str = Carbon::create($nowDate2D[0], $nowDate2D[1], $nowDate2D[2], $resClock1_2D[0], $resClock1_2D[1], 00);
    $today_end = Carbon::create($nowDate2D[0], $nowDate2D[1], $nowDate2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
    if($theRes->reportTimeOtherDay == 1){
        // diff day
        $today_end->addDays(1); 
    }
?>
 
 <style>
    .openOrderRow{
        border-bottom:2px solid rgb(72,81,87);

    }
    .openOrderRow:hover{
       cursor:pointer;
    }

    .backQr{
        background-color:white;
        color:rgb(39,190,175);
        border:1px solid rgb(39,190,175);
        font-weight: bold;
          font-size: 18px;
    }
    .backQr:hover{
        background-color:rgb(39,190,175);
        color:white;
        font-weight: bold;
        font-size: 18px;
    }
    .backQrSelected{
        background-color:rgb(39,190,175);
        color:white;
        font-weight: bold;
        font-size: 18px;
    }

    .column-4 {
    -webkit-columns: 4;
    -moz-columns: 4;
    columns: 4;
    }

    .table th, .table td {
        padding: 5px 2px 5px 2px !important;
    }

    @keyframes glowing {
        0% { box-shadow: 0 0 -10px black; }
        40% { box-shadow: 0 0 20px black; }
        60% { box-shadow: 0 0 20px black; }
        100% { box-shadow: 0 0 -10px black; }
    }

    .currStatusOrder {
        animation: glowing 800ms infinite;
    }

</style>

<section class="pl-1 pr-1 pb-5">
    <div class="d-flex flex-wrap justify-content-between">
        <h2 class="color-qrorpa pl-2" style="width:49%;">
            <strong>{{__('adminP.takeawayB')}}</strong>  
        </h2>
        <a class="btn shadow-none" href="{{route('dash.index',['tabs'])}}" style="width:49%; margin:0px; border:1px solid rgb(39,190,175); color:rgb(39,190,175);">
            <strong>Bestellliste</strong>
        </a>
        <button class="btn btn-dark shadow-none mt-1" style="width:100%;" data-toggle="modal" data-target="#addOrForTAModal">
            <strong><i class="fa-solid fa-plus mr-3"></i> Registrieren Sie eine Bestellung</strong>
        </button>

        @include('adminPanel.taTempProdTel.dashboardTakeawayNewOrd')
        @include('adminPanel.tablePageTel.tableIndexNotifications')
    </div>

    <hr style="margin-top:5px; margin-bottom:5px;">




    <div class="alert alert-info text-center" id="taOrderAutoConfirmAler01" style="width:100%; font-size:0.8rem; display:none;">
        <strong>Die in der Bestellung enthaltenen Produkte scheinen nicht gekocht zu werden, daher wird sie automatisch als BEZAHLT bestätigt</strong>
    </div>
    <div id="phoneTableAll">

    @if(onlinePayQRCStaf::where([['resId', Auth::user()->sFor],['tableNr',500],['status',0]])->whereBetween('created_at', [$today_str, $today_end])->count() >0)
        <h5 style="color:rgb(39,190,175); width:100%;"><strong>Bestellungen zur Online-Zahlung initiiert</strong></h5>
        <table class="table table-hover" id="phoneTable">
            <thead>
            <tr>
                <th style="opacity:50%;">{{__('adminP.products')}}</th>
                <th class="text-center" style="opacity:50%;">{{__('adminP.total')}}</th>
                <th class="text-center" style="opacity:50%;"></th>
                
            </tr>
            </thead>
            <tbody>
                @foreach(onlinePayQRCStaf::where([['resId', Auth::user()->sFor],['tableNr',500],['status',0]])->whereBetween('created_at', [$today_str, $today_end])
                ->get()->sortByDesc('created_at') as $opQRCStaf)
                    <tr>
                        <td colspan="1">
                            @foreach (ordersTaOnlinePayStafTemp::where('onlinePayRef',$opQRCStaf->id)->get() as $otaonpayProd)
                                <p style="margin:2px; font-size:12px;"><strong>{{$otaonpayProd->proSasia}}X</strong> {{$otaonpayProd->taProdName}}</p>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @if ($opQRCStaf->cashDis > 0 )
                            <p class="text-center pt-2" style="font-size:1rem; margin-bottom:3px;">{{number_format($opQRCStaf->totPay - $opQRCStaf->cashDis, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                            <p class="text-center" style="font-size:0.7rem; margin-bottom:3px;">( Rabat : {{number_format($opQRCStaf->cashDis, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup> )</p>
                            @elseif ($opQRCStaf->percDis > 0 )
                            <?php 
                                $percOf = number_format($opQRCStaf->percDis / 100, 4, '.',''); 
                                $rab = number_format($opQRCStaf->totPay * $percOf, 4, '.',''); 
                            ?>
                            <p class="text-center pt-2" style="font-size:1rem; margin-bottom:3px;">{{number_format($opQRCStaf->totPay - $rab, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                            <p class="text-center" style="font-size:0.7rem; margin-bottom:3px;">( Rabat : {{number_format($rab, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup> )</p>
                            @else
                            <p class="text-center pt-2" style="font-size:1rem; margin-bottom:3px;">{{number_format($opQRCStaf->totPay, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                            @endif
                        </td>
                        <td><i class="pt-2 fa-solid fa-2x fa-qrcode text-center" onclick="showQRCForPayAllProdsOnline('{{$opQRCStaf->qrCodeTP}}')"></i></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif


    @if(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500]])->whereBetween('created_at', [$today_str, $today_end])->get()->count() >0)
    <hr>
    <div class="d-flex flex-wrap">
        @if(Auth::user()->ehcchurworker == 1)
        <button style="width:15%; height:30px;" class="btn btn-warning"></button>  
        <p style="width: 34%; font-weight:bold;" class="pl-3">{{__('adminP.wait')}}</p>

        <button style="width:15%; height:30px;" class="btn btn-success"></button>  
        <p style="width: 34%; font-weight:bold;" class="pl-3">{{__('adminP.finished')}}</p>
        @else
        <button style="width:15%; height:30px;" class="btn btn-warning"></button>  
        <p style="width: 34%; font-weight:bold;" class="pl-3">{{__('adminP.wait')}}</p>

        <button style="width:15%; height:30px;" class="btn btn-info"></button>  
        <p style="width: 34%; font-weight:bold;" class="pl-3">{{__('adminP.confirmed')}}</p>

        <button style="width:15%; height:30px;" class="btn btn-danger"></button>  
        <p style="width: 34%; font-weight:bold;" class="pl-3">{{__('adminP.canceled')}}</p>

        <button style="width:15%; height:30px;" class="btn btn-success"></button>  
        <p style="width: 34%; font-weight:bold;" class="pl-3">{{__('adminP.finished')}}</p>
        @endif
    </div>
    @if(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi', '<',2]])->whereBetween('created_at', [$today_str, $today_end])->count() >0)
        <table class="table table-hover" id="phoneTable">
            <thead>
            <tr>
                <th style="opacity:50%;">{{__('adminP.products')}}</th>
                <th style="opacity:50%;">{{__('adminP.total')}}</th>
                @if(Auth::user()->ehcchurworker == 0)
                <th class="text-center" style="opacity:50%;"></th>
                @endif
            </tr>
            </thead>
            <tbody>
                @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi', '<',2]])->whereBetween('created_at', [$today_str, $today_end])
                ->get()->sortByDesc('created_at') as $order)
                    <?php
                        $orderDate2D= explode('-',explode(' ',$order->created_at)[0]); 

                        $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                    ?>
                    <tr id="taOrderRow{{$order->id}}">
                        <td colspan="1"  data-toggle="modal" data-target="#openOrderTel{{$order->id}}Tel">
                            <?php
                                $isReady = 1;
                                foreach(taDeForCookOr::where('orderId',$order->id)->get() as $orOneP){
                                    if((int)$orOneP->prodSasia != (int)$orOneP->prodSasiaDone){ $isReady = 0; }
                                }
                            ?>
                            @if ($isReady == 1)
                                <button class="btn btn-block btn-success"><strong>Bereit</strong></button>
                            @else
                                <button class="btn btn-block btn-danger"><strong>Nicht bereit</strong></button>
                            @endif
                            @if ($order->TAtime != 'empty' && $order->TAtime != '')
                            <span style="font-size:18px; opacity:95%;"><strong>Abholen: {{$order->TAtime }}</strong></span>
                            @endif
                            <hr style="margin-top: 4px; margin-bottom:4px;">
                            @foreach (explode('---8---',$order->porosia) as $thisProd)
                                @php
                                    $thisProd2D = explode('-8-',$thisProd);
                                @endphp
                                <p style="margin:2px; font-size:12px;"><strong>{{$thisProd2D[3]}}X</strong> {{$thisProd2D[0]}} </p>
                                @if ($thisProd2D[6] != '' && $thisProd2D[6] != 'empty')
                                    <p style="color:red; font-size:12px; margin:0;"><strong>Kommentar: {{$thisProd2D[6]}}</strong></p>
                                @endif
                            @endforeach
                        </td>
                        <td colspan="1" class="text-center" data-toggle="modal" data-target="#openOrderTel{{$order->id}}Tel">

                            @if ($order->inCashDiscount > 0 )
                            <p>{{number_format($order->shuma - $order->inCashDiscount - $order->dicsountGcAmnt, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                            <p style="font-size:0.9rem; margin:0px; padding:0px;">( Rabat:{{number_format($order->inCashDiscount, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup>)</p>
                            @elseif ($order->inPercentageDiscount > 0 )
                            <?php 
                                $totBe = number_format($order->shuma - $order->tipPer, 4, '.',''); 
                                $percOf = number_format($order->inPercentageDiscount / 100, 4, '.',''); 
                                $rab = number_format($totBe * $percOf, 4, '.',''); 
                            ?>
                            <p>{{number_format($order->shuma - $rab - $order->dicsountGcAmnt, 2, '.','')}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                            <p style="font-size:0.9rem; margin:0px; padding:0px;">( Rabat:{{number_format($rab, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup>)</p>
                            @else
                            <p style="font-size:0.9rem; margin:0px; padding:0px;">{{number_format($order->shuma - $order->dicsountGcAmnt, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                            @endif
                            <p style="margin:0px; padding:0px;" class="mt-2 mb-2"><strong>{{$order->payM}}</strong></p>
                            @if ($theRefIns != Null)
                                <strong>{{$theRefIns->refPh}}</strong><br>
                            @endif

                            @if($order->dicsountGcAmnt > 0)
                                <p class="text-center" style="font-size:0.8rem; margin-bottom:3px;">GK:-{{number_format($order->dicsountGcAmnt, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                            @endif

                            <p style="color:rgb(39,190,175); margin:0px; padding:0px; font-size:1.1rem;"><strong>{{explode('|',$order->shifra)[1]}}</strong></p>
                        </td>
                        
                        @if(Auth::user()->ehcchurworker == 0)
                        <!-- <td><a href="generatePDF/{{$order->id}}"><i class="fa fa-file-pdf-o fa-2x"></i></a></td> -->
                        <td colspan="1">
                            <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                {{ csrf_field()}}
                                <input type="hidden" value="{{$order->id}}" name="orId">
                                <button type="submit" class="btn"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    <tr class="openOrderRow" style="border-top: none;" id="taOrderRow2{{$order->id}}">
                        <td colspan="3" style="border-top: none;">
                            @if ($order->cuponOffVal > 0)
                                <p style="margin:3px; font-size:1.1rem;"><strong>Coupon: (- {{number_format($order->cuponOffVal,2,'.','')}} CHF)</strong></p>
                            @elseif ($order->cuponProduct != 'empty')
                                <p style="margin:3px; font-size:1.1rem;"><strong>Coupon: <span style="font-size: 0.65rem;">Gratisprodukt</span> {{$order->cuponProduct}}</strong></p>
                            @endif
                            <div class="d-flex flex-wrap justify-content-between" style="width:100%;">
                                @if ($order->statusi == 0)
                                <button onclick="taProdChngStatus('{{$order->id}}','0')" id="chngStatBnt0{{$order->id}}" style="width: 24.5%; height:30px; padding-top:0px;" 
                                class="shadow-none btn btn-warning" disabled><i style="color:white;" class="fa-solid fa-2x fa-check"></i></button>
                                @else
                                <button onclick="taProdChngStatus('{{$order->id}}','0')" id="chngStatBnt0{{$order->id}}" style="width: 24.5%; height:30px;" 
                                class="shadow-none btn btn-warning"></button>
                                @endif
                                
                                @if ($order->statusi == 1)
                                <button onclick="taProdChngStatus('{{$order->id}}','1')" id="chngStatBnt1{{$order->id}}" style="width: 24.5%; height:30px; padding-top:0px;" 
                                class="shadow-none btn btn-info" disabled><i style="color:white;" class="fa-solid fa-2x fa-check"></i></button>
                                @else
                                <button onclick="taProdChngStatus('{{$order->id}}','1')" id="chngStatBnt1{{$order->id}}" style="width: 24.5%; height:30px;" 
                                class="shadow-none btn btn-info"></button>
                                @endif
                                
                                <button onclick="showCommOrCancel('{{$order->id}}')" id="chngStatBnt2{{$order->id}}" style="width: 24.5%; height:30px;" 
                                class="shadow-none btn btn-danger"></button>
                                <button onclick="taProdChngStatus('{{$order->id}}','3')" id="chngStatBnt3{{$order->id}}" style="width: 24.5%; height:30px;" 
                                class="shadow-none btn btn-success"></button>

                                <div style="width:100%; display: none;" id="cancelCommentDiv{{$order->id}}" class="form-group mb-2 mt-2">
                                    <label for="exampleFormControlTextarea1"><strong>Kommentar zur Stornierung</strong></label>
                                    <textarea id="cancelCommentInp{{$order->id}}" class="form-control shadow-none mb-1" rows="2"></textarea>
                                    <button onclick="sendCancelRequest('{{$order->id}}')" style="margin:0px;" class="mb-1 btn-block btn btn-dark shadow-none" type="button" id="sendCancelRequestBtn{{$order->id}}">
                                        <strong>Bestätigen</strong>
                                    </button>
                                    <div class="alert alert-danger text-center mt-1" style="display:none;" id="cancelCommentErr01{{$order->id}}">
                                        <strong>Bitte schreiben Sie zuerst einen Kommentar</strong>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    

                @endforeach
                
            </tbody>
        </table>
        @else
            <p class="text-center" style="font-size: 19px; color:rgb(39,190,175);">{{__('adminP.noUnfinishedOrders')}}</p>
        @endif





        
        <button style="background-color: rgb(39,190,175); color:white; width:100%; border-radius:20px; border:none; font-size:18px;" class="p-1" id="desktopTable2ButtonTel"
            onclick="showTheFinishedOrdersTel()">
            <strong><i class="fas fa-arrow-down"></i> {{__('adminP.viewCompletedOrders')}} <i class="fas fa-arrow-down"></i></strong>
        </button>
    
        <!-- finished orders -->
        <h3 id="desktopTable2HeaderTel" style="display:none; color:rgb(39,190,175);" class="mt-2"><strong>{{__('adminP.finishedOrders')}}</strong></h3>
        @if(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi','>=',2]])->whereBetween('created_at', [$today_str, $today_end])->count() > 0)
        <table class="table table-hover" style="display:none;" id="desktopTable2Tel">
            <thead>
            <tr>
                <th style="opacity:50%;">{{__('adminP.products')}}</th>
                <th style="opacity:50%;">{{__('adminP.total')}}</th>
                <th class="text-center" style="opacity:50%;">{{__('adminP.status')}}</th>
                @if(Auth::user()->ehcchurworker == 0)
                <th class="text-center" style="opacity:50%;"></th>
                @endif
            </tr>
            </thead>
            <tbody>
                @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi','>=',2]])->whereBetween('created_at', [$today_str, $today_end])
                ->get()->sortByDesc('created_at') as $order)
                    <?php
                        $orderDate2D= explode('-',explode(' ',$order->created_at)[0]); 
                        $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                    ?>
                
                        <tr class="openOrderRow">
                            <td  data-toggle="modal" data-target="#openOrderTel{{$order->id}}Tel">
                                @foreach (explode('---8---',$order->porosia) as $thisProd)
                                    @php
                                        $thisProd2D = explode('-8-',$thisProd);
                                    @endphp
                                    <p style="margin:2px; font-size:12px;"><strong>{{$thisProd2D[3]}}X</strong> {{$thisProd2D[0]}} <span style="opacity:0.7; font-size:12px;">( {{$thisProd2D[1]}} )</span></p>
                                    @if ($thisProd2D[6] != '' && $thisProd2D[6] != 'empty')
                                        <p style="color:red; font-size:12px; margin:0;"><strong>Kommentar: {{$thisProd2D[6]}}</strong></p>
                                    @endif    
                                @endforeach
                                @if ($order->cuponOffVal > 0)
                                    <p style="margin:3px; font-size:12px;"><strong><i class="fa-solid fa-sm fa-barcode"></i> ( - {{number_format($order->cuponOffVal,2,'.','')}} CHF ) </strong></p>
                                @elseif ($order->cuponProduct != 'empty')
                                    <p style="margin:3px; font-size:12px;"><strong><i class="fa-solid fa-sm fa-barcode"></i> {{$order->cuponProduct}}</strong></p>
                                @endif
                        
                            </td>
                            <td data-toggle="modal" data-target="#openOrderTel{{$order->id}}Tel">
                                @if ($order->inCashDiscount > 0 )
                                <p>{{number_format($order->shuma - $order->inCashDiscount - $order->dicsountGcAmnt, 2, ',','')}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p style="font-size:0.7rem;">( Rabat:{{number_format($order->inCashDiscount, 2, ',','')}} <sup>{{__('adminP.currencyShow')}}</sup>)</p>
                                @elseif ($order->inPercentageDiscount > 0 )
                                <?php 
                                    $totBe = number_format($order->shuma - $order->tipPer, 4, '.',''); 
                                    $percOf = number_format($order->inPercentageDiscount / 100, 4, '.',''); 
                                    $rab = number_format($totBe * $percOf, 4, '.',''); 
                                ?>
                                <p>{{number_format($order->shuma - $rab - $order->dicsountGcAmnt, 2, '.','')}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p style="font-size:0.7rem;">( Rabat:{{number_format($rab, 2, '.','')}} <sup>{{__('adminP.currencyShow')}}</sup>)</p>
                                @else
                                <p>{{number_format($order->shuma - $order->dicsountGcAmnt, 2, ',','')}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                @endif
                                <p style="margin-top:-12px; margin-bottom:0px;"><strong>{{$order->payM}}</strong></p>

                                @if($order->dicsountGcAmnt > 0)
                                    <p class="text-center" style="font-size:0.8rem; margin-bottom:3px;">GK:-{{number_format($order->dicsountGcAmnt, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                                @endif

                                @if ($theRefIns != Null)
                                    <strong>{{$theRefIns->refPh}}</strong><br>
                                @endif
                            </td>
                            <td>
                                @if(Auth::user()->ehcchurworker == 1)
                                    @if($order->statusi == 0) 
                                        {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}
                                            {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                            {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                            {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                            {{ Form::hidden('backToMap', 4 , ['class' => 'form-control']) }}

                                            {{ Form::submit('', ['class' => 'form-control btn btn-warning btn-block', 'style' => 'height:30px;']) }}
                                        {{Form::close() }}
                                    @elseif($order->statusi == 3)
                                        <button class="btn btn-success btn-block" style="height:30px;"></button>
                                    @endif
                                @else
                                    @if($order->statusi == 2)
                                        <button class="btn btn-danger btn-block" style="font-size:8px;" disabled>
                                            @if($order->StatusBy!= 999999)
                                                @if(User::find($order->StatusBy) !=null)
                                                    {{User::find($order->StatusBy)->name}}
                                                @endif
                                            @endif
                                        </button>
                                    @elseif($order->statusi == 3)
                                        <button class="btn btn-success btn-block shadow-none" style="height:30px;"></button>
                                    @endif
                                @endif
                                <p style="color:rgb(39,190,175); margin-bottom:3px;"><strong>{{explode('|',$order->shifra)[1]}}</strong></p>
                            </td>

                            @if(Auth::user()->ehcchurworker == 0)
                            <!-- <td><a href="generatePDF/{{$order->id}}"><i class="fa fa-file-pdf-o fa-2x"></i></a></td> -->
                            <td>
                                <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                    {{ csrf_field()}}
                                    <input type="hidden" value="{{$order->id}}" name="orId">
                                    <button type="submit" class="btn"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                                </form>
                                @if($order->statusi == 3)
                                <button type="button" onclick="showBillQR('{{$order->id}}')" class="btn shadow-none"> <i class="fa-solid fa-qrcode fa-2x"></i> </button>
                                @endif

                            </td>
                            @endif
                        </tr>
                    

                @endforeach
                
            </tbody>
        </table>


        @else
            <p class="text-center" style="font-size: 19px; color:rgb(39,190,175);"> {{__('adminP.noReadyOrdersForMoment')}}</p>
        @endif

        <script>
            function showTheFinishedOrdersTel(){
                $('#desktopTable2HeaderTel').show();
                $('#desktopTable2Tel').show();
                $('#desktopTable2ButtonTel').html('<strong><i class="fas fa-arrow-up"></i> {{__("adminP.hideFinishedOrders")}} <i class="fas fa-arrow-up"></i></strong>');
                $('#desktopTable2ButtonTel').attr('onclick','hideTheFinishedOrdersTel()');
            }

            function hideTheFinishedOrdersTel(){
                $('#desktopTable2HeaderTel').hide();
                $('#desktopTable2Tel').hide();
                $('#desktopTable2ButtonTel').html('<strong><i class="fas fa-arrow-down"></i> {{__("adminP.viewCompletedOrders")}} <i class="fas fa-arrow-down"></i></strong>');
                $('#desktopTable2ButtonTel').attr('onclick','showTheFinishedOrdersTel()');
            }

            function showBillQR(orId){
                $.ajax({
                    url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                    method: 'post',
                    data: {
                        id: orId,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = $.trim(res);
                        res2D = res.split('-8-8-');
                        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                        $('#orderQRCodePicDownloadOI').val(orId);
                        $('#orderQRCodePicTel').modal('show');

                        $("#orderQRCodeTel").load(location.href+" #orderQRCodeTel>*","");
                        $("#orderQRCodeTelBody").html('<img src="storage/gifs/loading.gif" style="width:30%; margin-left:35%;" alt="">');
                    },
                    error: (error) => { console.log(error); }
                });
            }

            $(document).ready( function () {
                // $('#desktopTable').DataTable();
                // $('#desktopTable2').DataTable();
            } );
        </script>







    @else

        <p style="font-size:24px;" class=" color-qrorpa pl-4"><strong>{{__('adminP.noDeliveryOrdersToday')}}</strong></p>

    @endif
    </div>











    @if(old('showBillQR'))
        <script>
            $.ajax({
                url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                method: 'post',
                data: {
                    id: "{{old('orderId')}}",
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    res = $.trim(res);
                    res2D = res.split('-8-8-');
                    $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                    $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                    $('#orderQRCodePicDownloadOI').val("{{old('orderId')}}");
                    $('#orderQRCodePicTel').modal('show');

                    $("#orderQRCodeTel").load(location.href+" #orderQRCodeTel>*","");
                    $("#orderQRCodeTelBody").html('<img src="storage/gifs/loading.gif" style="width:30%; margin-left:35%;" alt="">');
                },
                error: (error) => { console.log(error); }
            });
        </script>
    @endif













    <div id="openOrderAllOther">
    @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi','<=', 1]])->whereBetween('created_at', [$today_str, $today_end])
            ->get() as $order)
        <?php
            $orderDate2D = explode(' ', $order->created_at);
            $orderDate2DM= explode('-',explode(' ',$order->created_at)[0]); 
            
        ?>
     
            <!-- The Modal -->
            <div class="modal" id="openOrderTel{{$order->id}}Tel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" style="border-radius:20px; border:3px solid rgb(39,190,175);">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h6 class="modal-title color-qrorpa">
                                @if( $order->Restaurant == 22 || $order->Restaurant == 23)
                                    <strong>{{__('adminP.toTakeawayFor')}} : {{explode('|',$order->shifra)[1]}}</strong> 
                                @else
                                    @if ($order->TAemri != 'empty')
                                    <strong>{{__('adminP.toTakeawayFor')}} : {{$order->TAemri}} {{$order->TAmbiemri}}</strong> 
                                    @else
                                    <strong>{{__('adminP.toTakeawayFor')}} : das Personal</strong> 
                                    @endif
                                @endif
                            </h6>
                            <button type="button" class="close" data-dismiss="modal">X</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body d-flex flex-wrap justify-content-between" >
                            @if ($order->userPhoneNr == '0770000000')
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;"><strong>Kundentelefonnr: das Personal</strong></p>
                            @else
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;"><strong> Kundentelefonnr: <a href="tel:{{$order->userPhoneNr}}">{{$order->userPhoneNr }}</a></strong></p>
                            @endif
                            <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                <strong> 
                                    Code: <span style="color: rgb(39,190,175);">{{explode('|',$order->shifra)[1] }}</span>
                                    @if ($order->TAbowlingLine != Null && $order->TAbowlingLine != -1)
                                        / <i class="fa-solid fa-bowling-ball mr-1"></i>: {{$order->TAbowlingLine}}
                                    @endif
                                </strong>
                            </p>
                            @if($order->inCashDiscount > 0)
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($order->shuma-$order->inCashDiscount - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>

                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:3px;">
                                    <strong>Skonto durch das Personal : {{number_format($order->inCashDiscount,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                    <strong>Rabattkommentar : {{$order->discReason}}</strong>
                                </p>
                            @elseif ($order->inPercentageDiscount > 0)
                                <?php
                                    $totBe = number_format($order->shuma - $order->tipPer, 4, '.',''); 
                                    $prct = number_format($order->inPercentageDiscount/100,4,'.','');
                                    $offVal = number_format($totBe*$prct,4,'.','');
                                    $newTotal = number_format($order->shuma-$offVal,4,'.','');
                                ?>
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($newTotal - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:3px;">
                                    <strong>prozentualer Rabatt durch das Personal : ({{number_format($order->inPercentageDiscount,2,'.','')}} % ) {{number_format($offVal,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                    <strong>Rabattkommentar : {{$order->discReason}}</strong>
                                </p>
                            @else
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($order->shuma - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>
                            @endif

                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                <strong>Tinkergeld: {{number_format($order->tipPer,2,'.','')}} CHF </strong>
                            </p>
                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                <strong>Rabatt per Gutschein: {{number_format($order->cuponOffVal,2,'.','')}} CHF </strong>
                            </p>
                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                @if ($order->cuponProduct == 'empty')
                                    <strong>Produkt aus Gutschein: ---</strong>
                                @else
                                    <strong>Produkt aus Gutschein: {{$order->cuponProduct}}</strong>
                                @endif
                            </p>

                            <hr style="width: 100%; margin-top: 5px; margin-bottom:5px;">

                            <p class="text-center" style="font-size:1rem; width:10%; margin-bottom:5px;">
                            </p>
                            <p class="text-center" style="font-size:1rem; width:68.8%; margin-bottom:5px;">
                                <strong>Produkt</strong>
                            </p>
                            <p class="text-center" style="font-size:1rem; width:20%; margin-bottom:5px;">
                                <strong>Preis</strong>
                            </p>
                           

                            @foreach(explode('---8---',$order->porosia) as $produkti)
                                <?php
                                    $prod = explode('-8-', $produkti);
                                ?>
                                <p class="text-center" style="font-size:0.8rem; width:10%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                    <strong>{{$prod[3]}} X</strong>
                                    <br>
                                    @if ($order->statusi == 3 || $order->statusi == 2)
                                        <strong>---</strong>
                                    @else
                                        <?php
                                            if($order->payM == 'Online'){
                                                $takeayPr = Takeaway::find($prod[7]);
                                                if($takeayPr != Null){$prodForCo = taDeForCookOr::where([['orderId',$order->id],['prodId',$takeayPr->prod_id],['prodSasia',$prod[3]]])->first();}
                                                else{$prodForCo = Null;}
                                            }else{ $prodForCo = taDeForCookOr::where([['orderId',$order->id],['prodId',$prod[7]],['prodSasia',$prod[3]]])->first(); }
                                        ?>
                                        @if ($prodForCo != Null)
                                            @if ($prodForCo->prodSasia == $prodForCo->prodSasiaDone )
                                                <button class="btn btn-success shadow-none btn-block" style="padding: 3px 0 3px 0;"><strong>B</strong></button>
                                            @else
                                                <button class="btn btn-danger shadow-none btn-block" style="padding: 3px 0 3px 0;"><strong>N.B</strong></button>
                                            @endif
                                        @endif
                                    @endif
                                </p>
                                <p class="text-left pl-1" style="font-size:0.8rem; width:70%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                    <strong>{{$prod[0]}} 
                                        @if ($prod[1] != '' && $prod[1] != 'empty')
                                            <span style="opacity: 0.6;">({{$prod[1]}})</span>
                                        @endif
                                    </strong>
                                    @if ($prod[5] != '' && $prod[5] != 'empty')
                                    <br> Typ: <strong>{{explode('||',$prod[5])[0]}}</strong>
                                    @endif
                                    @if ($prod[2] != '' && $prod[2] != 'empty')
                                    <br> Extra: <strong>
                                        @php
                                            $startExtr = 1;
                                        @endphp
                                        @foreach (explode('--0--',$prod[2]) as $onEx)
                                            @if ($startExtr == 1)
                                                <span>{{explode('||',$onEx)[0]}} </span>
                                            @else
                                                <span>, {{explode('||',$onEx)[0]}} </span>
                                            @endif
                                            @php
                                                $startExtr++;
                                            @endphp
                                        @endforeach
                                    </strong>
                                    @endif
                                    @if ($prod[6] != '' && $prod[6] != 'empty')
                                    <br><span style="color:red;"><strong>Kommentar: {{$prod[6]}}</strong></span>
                                    @endif
                                </p>
                                <p class="text-center" style="font-size:0.8rem; width:20%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                    <strong>{{number_format($prod[4],2,'.','')}}<br>CHF</strong>
                                </p>
                               
                            @endforeach

                            <hr style="width: 100%; margin-top: 5px; margin-bottom:5px;">

                            <?php
                                $dtFOr = explode('-',explode(' ',$order->created_at)[0]);
                                $hrFOr = explode(':',explode(' ',$order->created_at)[1]);
                            ?>
                            <p class="text-center" style="font-size:0.8rem; width:49%; margin-bottom:5px;">
                                <strong>{{$dtFOr[2]}}.{{$dtFOr[1]}}.{{$dtFOr[0]}} <span style="opacity: 0.6; margin-left:15px;">{{$hrFOr[0]}}:{{$hrFOr[1]}}</span></strong>
                            </p>
                            <p class="text-center" style="font-size:0.8rem; width:49%; margin-bottom:5px;">
                                <strong> # {{$order->refId}}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
      
    @endforeach
    </div>




    <div id="openOrderAllDone">
    @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi','>=',2]])->whereBetween('created_at', [$today_str, $today_end])
            ->get() as $order)
        <?php
            $orderDate2D = explode(' ', $order->created_at);
            $orderDate2DM= explode('-',explode(' ',$order->created_at)[0]); 
            
        ?>
     
            <!-- The Modal -->
            <div class="modal" id="openOrderTel{{$order->id}}Tel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" style="border-radius:20px; border:3px solid rgb(39,190,175);">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h6 class="modal-title color-qrorpa">
                                @if( $order->Restaurant == 22 || $order->Restaurant == 23)
                                    <strong>{{__('adminP.toTakeawayFor')}} : {{explode('|',$order->shifra)[1]}}</strong> 
                                @else
                                    @if ($order->TAemri != 'empty')
                                    <strong>{{__('adminP.toTakeawayFor')}} : {{$order->TAemri}} {{$order->TAmbiemri}}</strong> 
                                    @else
                                    <strong>{{__('adminP.toTakeawayFor')}} : das Personal</strong> 
                                    @endif
                                @endif
                            </h6>
                            <button type="button" class="close" data-dismiss="modal">X</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body d-flex flex-wrap justify-content-between" >
                            @if ($order->userPhoneNr == '0770000000')
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;"><strong>Kundentelefonnr: das Personal</strong></p>
                            @else
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;"><strong> Kundentelefonnr: <a href="tel:{{$order->userPhoneNr}}">{{$order->userPhoneNr }}</a></strong></p>
                            @endif
                            <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                <strong> 
                                    Code: <span style="color: rgb(39,190,175);">{{explode('|',$order->shifra)[1] }}</span>
                                    @if ($order->TAbowlingLine != Null && $order->TAbowlingLine != -1)
                                        / <i class="fa-solid fa-bowling-ball mr-1"></i>: {{$order->TAbowlingLine}}
                                    @endif
                                </strong>
                            </p>
                            @if($order->inCashDiscount > 0)
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($order->shuma-$order->inCashDiscount - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>

                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:3px;">
                                    <strong>Skonto durch das Personal : {{number_format($order->inCashDiscount,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                    <strong>Rabattkommentar : {{$order->discReason}}</strong>
                                </p>
                            @elseif ($order->inPercentageDiscount > 0)
                                <?php
                                    $totBe = number_format($order->shuma - $order->tipPer, 4, '.','');
                                    $prct = number_format($order->inPercentageDiscount/100,4,'.','');
                                    $offVal = number_format($totBe*$prct,4,'.','');
                                    $newTotal = number_format($order->shuma-$offVal,4,'.','');
                                ?>
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($newTotal - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:3px;">
                                    <strong>prozentualer Rabatt durch das Personal : ({{number_format($order->inPercentageDiscount,2,'.','')}} % ) {{number_format($offVal,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                    <strong>Rabattkommentar : {{$order->discReason}}</strong>
                                </p>
                            @else
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($order->shuma - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>
                            @endif

                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                <strong>Tinkergeld: {{number_format($order->tipPer,2,'.','')}} CHF </strong>
                            </p>
                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                <strong>Rabatt per Gutschein: {{number_format($order->cuponOffVal,2,'.','')}} CHF </strong>
                            </p>
                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                @if ($order->cuponProduct == 'empty')
                                    <strong>Produkt aus Gutschein: ---</strong>
                                @else
                                    <strong>Produkt aus Gutschein: {{$order->cuponProduct}}</strong>
                                @endif
                            </p>

                            <hr style="width: 100%; margin-top: 5px; margin-bottom:5px;">

                            <p class="text-center" style="font-size:1rem; width:10%; margin-bottom:5px;">
                            </p>
                            <p class="text-center" style="font-size:1rem; width:68.8%; margin-bottom:5px;">
                                <strong>Produkt</strong>
                            </p>
                            <p class="text-center" style="font-size:1rem; width:20%; margin-bottom:5px;">
                                <strong>Preis</strong>
                            </p>
                           

                            @foreach(explode('---8---',$order->porosia) as $produkti)
                                <?php
                                    $prod = explode('-8-', $produkti);
                                ?>
                                <p class="text-center" style="font-size:0.8rem; width:10%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                    <strong>{{$prod[3]}} X</strong>
                                </p>
                                <p class="text-left pl-1" style="font-size:0.8rem; width:70%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                    <strong>{{$prod[0]}} 
                                        @if ($prod[1] != '' && $prod[1] != 'empty')
                                            <span style="opacity: 0.6;">({{$prod[1]}})</span>
                                        @endif
                                    </strong>
                                    @if ($prod[5] != '' && $prod[5] != 'empty')
                                    <br> Typ: <strong>{{explode('||',$prod[5])[0]}}</strong>
                                    @endif
                                    @if ($prod[2] != '' && $prod[2] != 'empty')
                                    <br> Extra: <strong>
                                        @php
                                            $startExtr = 1;
                                        @endphp
                                        @foreach (explode('--0--',$prod[2]) as $onEx)
                                            @if ($startExtr == 1)
                                                <span>{{explode('||',$onEx)[0]}} </span>
                                            @else
                                                <span>, {{explode('||',$onEx)[0]}} </span>
                                            @endif
                                            @php
                                                $startExtr++;
                                            @endphp
                                        @endforeach
                                    </strong>
                                    @endif
                                    @if ($prod[6] != '' && $prod[6] != 'empty')
                                    <br><span style="color:red;"><strong>Kommentar: {{$prod[6]}}</strong></span>
                                    @endif
                                </p>
                                <p class="text-center" style="font-size:0.8rem; width:20%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                    <strong>{{number_format($prod[4],2,'.','')}}<br>CHF</strong>
                                </p>
                               
                            @endforeach

                            <hr style="width: 100%; margin-top: 5px; margin-bottom:5px;">

                            <?php
                                $dtFOr = explode('-',explode(' ',$order->created_at)[0]);
                                $hrFOr = explode(':',explode(' ',$order->created_at)[1]);
                            ?>
                            <p class="text-center" style="font-size:0.8rem; width:49%; margin-bottom:5px;">
                                <strong>{{$dtFOr[2]}}.{{$dtFOr[1]}}.{{$dtFOr[0]}} <span style="opacity: 0.6; margin-left:15px;">{{$hrFOr[0]}}:{{$hrFOr[1]}}</span></strong>
                            </p>
                            <p class="text-center" style="font-size:0.8rem; width:49%; margin-bottom:5px;">
                                <strong> # {{$order->refId}}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
      
    @endforeach
    </div>











<script>
    function setChBy(wId){
        $('.statusWorkerButtonAll').attr('class','statusWorkerButtonAll btn backQr mr-1 ml-1')
        $('#statusWorkerButton'+wId).attr('class','statusWorkerButtonAll btn backQrSelected mr-1 ml-1')
        $('#sWPhase2').show();
        $('.statBtn').show(500);
        $('.chByInput').val(wId);
    }

    function showCommOrCancel(orId){
        if($('#cancelCommentDiv'+orId).is(':hidden')){
            $('#cancelCommentDiv'+orId).show(100);
        }else{
            $('#cancelCommentDiv'+orId).hide(100);
        }
    }

    function sendCancelRequest(orId){
        if(!$('#cancelCommentInp'+orId).val()){
            if($('#cancelCommentErr01'+orId).is(':hidden')){ $('#cancelCommentErr01'+orId).show(100).delay(3500).hide(100);}
        }else{
            $('#sendCancelRequestBtn'+orId).html('<img style="width: 10%; margin-left:45%; margin-right:45%;" src="storage/gifs/loading2.gif" alt="">');
            $('#sendCancelRequestBtn'+orId).prop('disabled', true);
            $.ajax({
		    	url: '{{ route("order.cancelAOrder") }}',
		    	method: 'post',
		    	data: {
		    		oId: orId,
                    theComm: $('#cancelCommentInp'+orId).val(),
		    		_token: '{{csrf_token()}}'
		    	},
		    	success: () => {
                    // $('#ChStat'+orId+'Tel').modal('toggle');
                    // $("#ChStat"+orId+'Tel').load(location.href+" #ChStat"+orId+">*","");

                    $("#taOrderRow"+orId).remove();
                    $("#taOrderRow2"+orId).remove();
                    // $("#phoneTable").load(location.href+" #phoneTable"+">*","");
                    $("#desktopTable2Tel").load(location.href+" #desktopTable2Tel"+">*","");  
		    	},
		    	error: (error) => { console.log(error); }
		    });
        }
    }

    function reloadChStat(orId){
        // $("#ChStat"+orId+"Tel").load(location.href+" #ChStat"+orId+"Tel>*","");
    }

    function taProdChngStatus(orId,newSta){
        $("#chngStatBnt0"+orId).html('<img style="width: 30%; margin-left:35%; margin-right:35%;" src="storage/gifs/loading2.gif" alt="">');
        $("#chngStatBnt0"+orId).prop('disabled', true);
        $("#chngStatBnt1"+orId).html('<img style="width: 30%; margin-left:35%; margin-right:35%;" src="storage/gifs/loading2.gif" alt="">');
        $("#chngStatBnt1"+orId).prop('disabled', true);
        $("#chngStatBnt2"+orId).html('<img style="width: 30%; margin-left:35%; margin-right:35%;" src="storage/gifs/loading2.gif" alt="">');
        $("#chngStatBnt2"+orId).prop('disabled', true);
        $("#chngStatBnt3"+orId).html('<img style="width: 30%; margin-left:35%; margin-right:35%;" src="storage/gifs/loading2.gif" alt="">');
        $("#chngStatBnt3"+orId).prop('disabled', true);
        
        $.ajax({
			url: '{{ route("order.taOrChngStatus") }}',
			method: 'post',
			data: {
				oId: orId,
                newStat: newSta,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                // $("#phoneTable").html('<img style="width: 50%; margin-left:25%; margin-right:25%;" src="storage/gifs/loading.gif" alt="">');
                // $("#phoneTable").load(location.href+" #phoneTable"+">*","");
                if(newSta == 0 || newSta == 1){
                    $("#taOrderRow"+orId).load(location.href+" #taOrderRow"+orId+">*","");
                    $("#taOrderRow2"+orId).load(location.href+" #taOrderRow2"+orId+">*","");
                }else{
                    $("#taOrderRow"+orId).remove();
                    $("#taOrderRow2"+orId).remove();
                    $("#desktopTable2Tel").html('<img style="width: 50%; margin-left:25%; margin-right:25%;" src="storage/gifs/loading.gif" alt="">');
                    $("#desktopTable2Tel").load(location.href+" #desktopTable2Tel"+">*","");

                    if(newSta == 3){
                        $.ajax({
                            url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                            method: 'post',
                            data: {
                                id: orId,
                                _token: '{{csrf_token()}}'
                            },
                            success: (res) => {
                                res = $.trim(res);
                                res2D = res.split('-8-8-');
                                $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                                $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]);
                                $('#orderQRCodePicDownloadOI').val(orId);
                                $('#orderQRCodePicTel').modal('show');

                                $("#orderQRCodeTel").load(location.href+" #orderQRCodeTel>*","");
                                $("#orderQRCodeTelBody").html('<img src="storage/gifs/loading.gif" style="width:30%; margin-left:35%;" alt="">');
                            },
                            error: (error) => { console.log(error); }
                        });
                    }
                }

                // $('#ChStat'+orId+'Tel').modal('toggle');
                // $("#ChStat"+orId+'Tel').load(location.href+" #ChStat"+orId+">*","");
			},
			error: (error) => { console.log(error); }
		});
    }
</script>

</section>