<?php

use App\giftCard;
use App\OrdersPassive;
use App\OPSaferpayReference;
use Illuminate\Support\Facades\Auth;
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Statistiken']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Orders;
    use Carbon\Carbon;
    use App\Restorant;
    use App\User;

    $thisRestaurantId = Auth::user()->sFor;
    $thisRestaurant = Restorant::find(Auth::user()->sFor);
    
    $nowDate = date('Y-m-d');
    $nowDate2D = explode('-',$nowDate);
    $nowMonth = date('m');

    $resClock = explode('->',$thisRestaurant->reportTimeArc);
    $resClock1_2D = explode(':',$resClock[0]);
    $resClock2_2D = explode(':',$resClock[1]);

    $today_str = Carbon::create($nowDate2D[0], $nowDate2D[1], $nowDate2D[2], $resClock1_2D[0], $resClock1_2D[1], 00);
    $today_end = Carbon::create($nowDate2D[0], $nowDate2D[1], $nowDate2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
    if($thisRestaurant->reportTimeOtherDay == 1){
        // diff day
        $today_end->addDays(1); 
    }

    $ordsForTd = OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])->whereBetween('created_at', [$today_str, $today_end])->orderByDesc('created_at')->get();
?>
 <style>
    .openOrderRow{
        border-bottom:1px solid lightgray;

    }
    .openOrderRow:hover{
       cursor:pointer;
    }
 </style>


<div class="col mr-2">
    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{__('adminP.salesToday')}}</div>
</div>

<div class="pl-3 pr-3 pt-3 pb-3 d-flex flex-wrap justify-content-between" id="salesTypeStats">
    <?php
        $cashTot = number_format(0 ,4 ,'.' ,'' );
        $cashTips = number_format(0 ,4 ,'.' ,'' );
        $cardTot = number_format(0 ,4 ,'.' ,'' );
        $cardTips = number_format(0 ,4 ,'.' ,'' );
        $onlineTot = number_format(0 ,4 ,'.' ,'' );
        $onlineTips = number_format(0 ,4 ,'.' ,'' );
        $billsTot = number_format(0 ,4 ,'.' ,'' );
        $billsTips = number_format(0 ,4 ,'.' ,'' );

        $gcSaleCash = number_format(0 ,4 ,'.' ,'' );
        $gcSaleCard = number_format(0 ,4 ,'.' ,'' );
        $gcSaleOnline = number_format(0 ,4 ,'.' ,'' );
        $gcSaleRechnung = number_format(0 ,4 ,'.' ,'' );
        $totGCSalesThisWa = number_format(0 ,4 ,'.' ,'' );

        $discFromGCTot = number_format(0 ,4 ,'.' ,'' );
    ?>

    @foreach (giftCard::where([['toRes',Auth::user()->sFor],['oldGCtransfer','0']])->whereBetween('created_at', [$today_str, $today_end])->get() as $gcPMOne)
        @if ($gcPMOne->payM == 'Cash')
            <?php $gcSaleCash += number_format($gcPMOne->gcSumInChf ,4 ,'.' ,'' ); ?>
        @elseif($gcPMOne->payM == 'Card')
            <?php $gcSaleCard += number_format($gcPMOne->gcSumInChf ,4 ,'.' ,'' ); ?>
        @elseif($gcPMOne->payM == 'Online')
            <?php $gcSaleOnline += number_format($gcPMOne->gcSumInChf ,4 ,'.' ,'' ); ?>
        @elseif($gcPMOne->payM == 'Rechnung')
            <?php $gcSaleRechnung += number_format($gcPMOne->gcSumInChf ,4 ,'.' ,'' ); ?>
        @endif
        <?php $totGCSalesThisWa += number_format($gcPMOne->gcSumInChf ,4 ,'.' ,'' ); ?>
    @endforeach
    
    @foreach ($ordsForTd as $orCash)
        @if ($orCash->payM == 'Barzahlungen' || $orCash->payM == 'Cash')
            <?php 
                if ($orCash->inCashDiscount > 0){
                    $cashTot += number_format($orCash->shuma - $orCash->inCashDiscount - $orCash->dicsountGcAmnt,4 ,'.' ,'' ); 
                }else if($orCash->inPercentageDiscount > 0){
                    $shumaBef = number_format($orCash->shuma - $orCash->tipPer, 4, '.', '');
                    $theDi = number_format($shumaBef * ($orCash->inPercentageDiscount * 0.01), 4, '.', '');
                    $cashTot += number_format($orCash->shuma - $theDi - $orCash->dicsountGcAmnt,4 ,'.' ,'' ); 
                }else{
                    $cashTot += number_format($orCash->shuma - $orCash->dicsountGcAmnt,4 ,'.' ,'' );
                }
                $cashTips += number_format($orCash->tipPer ,4 ,'.' ,'' );

                $discFromGCTot += number_format($orCash->dicsountGcAmnt ,4 ,'.' ,'' );
            ?>
        @endif
    @endforeach

    @foreach ($ordsForTd as $orCash)
        @if ($orCash->payM == 'Kartenzahlung')
            <?php 
                if ($orCash->inCashDiscount > 0){
                    $cardTot += number_format($orCash->shuma - $orCash->inCashDiscount - $orCash->dicsountGcAmnt,4 ,'.' ,'' ); 
                }else if($orCash->inPercentageDiscount > 0){
                    $shumaBef = number_format($orCash->shuma - $orCash->tipPer, 4, '.', '');
                    $theDi = number_format($shumaBef * ($orCash->inPercentageDiscount * 0.01), 4, '.', '');
                    $cardTot += number_format($orCash->shuma - $theDi - $orCash->dicsountGcAmnt,4 ,'.' ,'' ); 
                }else{
                    $cardTot += number_format($orCash->shuma - $orCash->dicsountGcAmnt,4 ,'.' ,'' );
                } 
                $cardTips += number_format($orCash->tipPer ,4 ,'.' ,'' );

                $discFromGCTot += number_format($orCash->dicsountGcAmnt ,4 ,'.' ,'' );
            ?>
        @endif
    @endforeach
   
    @foreach ($ordsForTd as $orCash)
        @if ($orCash->payM == 'Online')
            <?php  
                if ($orCash->inCashDiscount > 0){
                    $onlineTot += number_format($orCash->shuma - $orCash->inCashDiscount - $orCash->dicsountGcAmnt,4 ,'.' ,'' ); 
                }else if($orCash->inPercentageDiscount > 0){
                    $shumaBef = number_format($orCash->shuma - $orCash->tipPer, 4, '.', '');
                    $theDi = number_format($shumaBef * ($orCash->inPercentageDiscount * 0.01), 4, '.', '');
                    $onlineTot += number_format($orCash->shuma - $theDi - $orCash->dicsountGcAmnt,4 ,'.' ,'' ); 
                }else{
                    $onlineTot += number_format($orCash->shuma - $orCash->dicsountGcAmnt,4 ,'.' ,'' );
                }
                $onlineTips += number_format($orCash->tipPer ,4 ,'.' ,'' );

                $discFromGCTot += number_format($orCash->dicsountGcAmnt ,4 ,'.' ,'' );
            ?>
        @endif
    @endforeach

    @foreach ($ordsForTd as $orCash)
        @if ($orCash->payM == 'Rechnung')
            <?php 
                if ($orCash->inCashDiscount > 0){
                    $billsTot += number_format($orCash->shuma - $orCash->inCashDiscount - $orCash->dicsountGcAmnt,4 ,'.' ,'' ); 
                }else if($orCash->inPercentageDiscount > 0){
                    $shumaBef = number_format($orCash->shuma - $orCash->tipPer, 4, '.', '');
                    $theDi = number_format($shumaBef * ($orCash->inPercentageDiscount * 0.01), 4, '.', '');
                    $billsTot += number_format($orCash->shuma - $theDi - $orCash->dicsountGcAmnt,4 ,'.' ,'' ); 
                }else{
                    $billsTot += number_format($orCash->shuma - $orCash->dicsountGcAmnt,4 ,'.' ,'' );
                }
                $billsTips += number_format($orCash->tipPer ,4 ,'.' ,'' );

                $discFromGCTot += number_format($orCash->dicsountGcAmnt ,4 ,'.' ,'' );
            ?>
        @endif
    @endforeach

    <h4 style="width: 33%; color:rgb(72,81,87);"><strong>Bezahlverfahren</strong></h4>
    <h4 style="width: 33%; color:rgb(72,81,87);"><strong>Gesamt</strong></h4>
    <h4 style="width: 33%; color:rgb(72,81,87);"><strong>Tipps</strong></h4>
    <hr style="width:100%;">

    <p style="width: 33%; color:rgb(72,81,87);"><strong>Barzahlungen</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($cashTot + $gcSaleCash,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($cashTips ,2 ,'.' ,'' )}} CHF</strong></p>

    <p style="width: 33%; color:rgb(72,81,87);"><strong>Abzüglich Trinkgeld</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($cashTot + $gcSaleCash - $cashTips - $cardTips - $onlineTips - $billsTips,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>---</strong></p>

    <p style="width: 33%; color:rgb(72,81,87);"><strong>Kartenzahlung</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($cardTot + $gcSaleCard ,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($cardTips ,2 ,'.' ,'' )}} CHF</strong></p>

    <p style="width: 33%; color:rgb(72,81,87);"><strong>Online</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($onlineTot + $gcSaleOnline,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($onlineTips ,2 ,'.' ,'' )}} CHF</strong></p>

    <p style="width: 33%; color:rgb(72,81,87);"><strong>Auf Rechnung</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($billsTot + $gcSaleRechnung,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($billsTips ,2 ,'.' ,'' )}} CHF</strong></p>

    <!-- <p style="width: 33%; color:rgb(72,81,87);"><strong>Verkaufte GK</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($totGCSalesThisWa ,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>---</strong></p> -->

    <p style="width: 33%; color:red;"><strong>Benutzte GK</strong></p>
    <p style="width: 33%; color:red;"><strong>{{ number_format($discFromGCTot ,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 33%; color:red;"><strong>---</strong></p>

    <p style="width: 33%; color:rgb(72,81,87);"><strong>Total</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($cashTot+$cardTot+$onlineTot+$billsTot+$totGCSalesThisWa+$discFromGCTot - $cashTips - $cardTips - $onlineTips - $billsTips,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 33%; color:rgb(72,81,87);"><strong>{{ number_format($cashTips+$cardTips+$onlineTips+$billsTips ,2 ,'.' ,'' )}} CHF</strong></p>
    
</div>

<div class="p-1 pb-4">
    <table class="table table-hover" id="tabletTable" style="display:none;">
        <thead>
        <tr>
            <th style="opacity:50%;">{{__('adminP.dateTime')}}</th>
            <th style="opacity:50%;">{{__('adminP.tableNumber')}}</th>
            <th style="opacity:50%;">{{__('adminP.article')}}</th>
            <th style="opacity:50%;">{{__('adminP.status')}}</th>
            <th style="opacity:50%;">{{__('adminP.press')}}</th>
        </tr>
        </thead>
        <tbody>

            @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])->whereDate('created_at', Carbon::today())->get()->sortByDesc('created_at') as $order)
                <?php
                    $dayOrder= explode(' ',$order->created_at)[0]; 
                ?>
                @if($nowDate == $dayOrder)
                    <tr class="openOrderRow">
                        <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            {{explode(' ',$order->created_at)[1]}}<br>
                            <span style="font-size:12px; opacity:60%;">{{explode(' ',$order->created_at)[0]}}</span><br>
                        </td>
                        <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            <p><strong class="pl-2">{{$order->nrTable}}</strong></p>
                        </td>
                        <?php
                            $thisOrder = explode('---8---',$order->porosia)[0];
                            $thisProd = explode('-8-',$thisOrder);
                        ?>
                        <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            <p>{{$thisProd[0]}}</p>
                            @if(!empty( explode('---8---',$order->porosia)[1]))
                                <p style=" margin-top:-20px;">...</p>
                            @endif
                        </td>
                        <td>
                            @if($order->statusi == 0)                 
                                <button class="btn btn-warning btn-block" >
                                     {{__('adminP.waitingLine')}}
                                </button>           
                            @elseif($order->statusi == 1)
                                <button class="btn btn-info btn-block" >
                                    {{__('adminP.confirmed')}}
                                </button>
                            @elseif($order->statusi == 2)
                                <button class="btn btn-danger btn-block" >
                                    {{__('adminP.canceled')}}

                                </button>
                            @elseif($order->statusi == 3)
                                <button class="btn btn-success btn-block" >
                                   {{__('adminP.finished')}}
                                </button>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                {{ csrf_field()}}
                                <input type="hidden" value="{{$order->id}}" name="orId">
                                <button type="submit" class="btn"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                            </form>
                        </td>
                    </tr>
                @endif

            @endforeach
            
        </tbody>
    </table>

    <table class="table table-hover" id="desktopTable" style="display:none;">
        <thead>
        <tr>
            <th style="opacity:50%;">{{__('adminP.dateTime')}}</th>
            <th style="opacity:50%;">{{__('adminP.table')}}</th>
            <th style="opacity:50%;">{{__('adminP.article')}}</th>
            <th style="opacity:50%;">{{__('adminP.totalB')}}</th>
            <th style="opacity:50%;">Kellner</th>
            <th style="opacity:50%;">{{__('adminP.paymentMethod')}}</th>
            <th style="opacity:50%;">{{__('adminP.status')}}</th>
            <th style="opacity:50%;">{{__('adminP.press')}}</th>
        </tr>
        </thead>
        <tbody>
            @foreach (giftCard::where([['toRes',$thisRestaurantId],['oldGCtransfer','0']])->whereDate('created_at', Carbon::today())->get() as $gcSaleOne)
                <tr class="openOrderRow">
                    <td>
                        <?php
                            $or2D = explode(':',explode(' ',$gcSaleOne->created_at)[1]);
                            $dt2D = explode('-',explode(' ',$gcSaleOne->created_at)[0]);
                        ?>
                        <span>{{$dt2D[2]}}.{{$dt2D[1]}}.{{$dt2D[0]}}</span><br>
                        <span>{{$or2D[0]}}:{{$or2D[1]}}</span>
                    </td>
                    <td>
                        <p><strong class="pl-2">---</strong></p>
                    </td>
                    <td>
                        <p><strong class="pl-2">CHF Geschenkkarte</strong></p>
                    </td>
                    <td>
                        <p><strong class="pl-2">{{number_format($gcSaleOne->gcSumInChf, 2, '.', ' ')}}</strong></p>
                    </td>
                    <td>---</td>
                    <td>
                        <p class="mb-1"><strong>{{$gcSaleOne->payM}}</strong></p>
                    </td>
                    <td>---</td>
                    <td>
                        <form method="POST" action="{{ route('giftCard.giftCardGetReceipt') }}">
                            {{ csrf_field()}}
                            <input id="giftCardId" type="hidden" value="{{$gcSaleOne->id}}" name="giftCardId">
                            <button type="submit" class="btn shadow-none"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                        </form>
                    </td>
                </tr>

            @endforeach
            @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])->whereDate('created_at', Carbon::today())->orderByDesc('orForWaiter')->get() as $order)
                <?php
                    $dayOrder= explode(' ',$order->created_at)[0]; 
                    $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                ?>
                 @if($nowDate == $dayOrder)
                    <tr class="openOrderRow">
                        <td data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            {{explode(' ',$order->created_at)[1]}}<br>
                            <span style="font-size:12px; opacity:60%;">{{explode(' ',$order->created_at)[0]}}</span><br>
                        </td>
                        <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            @if ($order->nrTable == 500 )
                                <p><strong class="pl-2">Takeaway</strong></p>
                            @elseif ($order->nrTable == 9000 )
                                <p><strong class="pl-2">Delivery</strong></p>
                            @else
                                <p><strong class="pl-2">{{$order->nrTable}}</strong></p>
                            @endif
                        </td>
                        <?php
                            $thisOrder = explode('---8---',$order->porosia);
                            $thisProd1 = explode('-8-',$thisOrder[0]);
                            if(!empty($thisOrder[1])){
                            $thisProd2 = explode('-8-',$thisOrder[1]);
                            }
                            if(!empty($thisOrder[2])){
                            $thisProd3 = explode('-8-',$thisOrder[2]);
                            }
                        ?>
                        <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            <p>{{$thisProd1[0]}} <span style="opacity:0.7;">( {{$thisProd1[1]}} )</span></p>
                            @if(!empty($thisOrder[1]))
                                <p style="margin-top:-20px;">{{$thisProd2[0]}} <span style="opacity:0.7;">( {{$thisProd2[1]}} )</span></p>
                            @endif
                            @if(!empty($thisOrder[2]))
                                <p style="margin-top:-20px;">{{$thisProd3[0]}} <span style="opacity:0.7;">( {{$thisProd3[1]}} )</span></p>
                            @endif
                            @if(!empty($thisOrder[3]))
                                <p style="margin-top:-20px;">...</p>
                            @endif
                        </td>
                        <td data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            @if ($order->inCashDiscount > 0)
                                {{number_format($order->shuma - $order->inCashDiscount - $order->dicsountGcAmnt, 2, '.', ' ')}}
                                <br>
                                -{{number_format($order->inCashDiscount, 2, '.', ' ')}}
                            @elseif($order->inPercentageDiscount > 0)
                                <?php
                                    $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                                    $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                                ?>
                                {{number_format($order->shuma - $theDi - $order->dicsountGcAmnt, 2, '.', ' ')}}
                                <br>
                                -{{number_format($order->inPercentageDiscount, 2, '.', ' ')}} %
                            @else
                                <p>{{number_format($order->shuma - $order->dicsountGcAmnt, 2, '.', ' ')}}</p>
                            @endif
                        </td>
                        <td data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            @if ($order->orForWaiter != 0)
                            <?php $thWaN = User::find($order->orForWaiter);?>
                                @if ($thWaN != NULL)
                                    <p># {{$thWaN->id}} <br> {{$thWaN->name}}</p>
                                @else
                                    <p>---</p>
                                @endif
                            @endif
                        </td>
                        <td data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            @if ($order->payM == 'Barzahlungen' || $order->payM == 'Cash')
                                <p class="mb-1"><strong>Barzahlungen</strong></p>
                                @if ($theRefIns != Null)
                                    <strong>Saferpay: {{$theRefIns->refPh}}</strong><br>
                                @endif
                            @elseif ($order->payM == 'Kartenzahlung' || $order->payM == 'Online')
                                <p class="mb-1"><strong>Kartenzahlung</strong></p>
                                @if ($theRefIns != Null)
                                    <strong>Saferpay: {{$theRefIns->refPh}}</strong><br>
                                @endif
                            @elseif ($order->payM == 'Rechnung')
                                <p class="mb-1"><strong>Auf Rechnung</strong></p>
                                @if ($theRefIns != Null)
                                    <strong>Saferpay: {{$theRefIns->refPh}}</strong><br>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($order->statusi == 0)                 
                                <button class="btn btn-warning btn-block" >
                                    {{__('adminP.waitingLine')}}
                                </button>           
                            @elseif($order->statusi == 1)
                                <button class="btn btn-info btn-block" >
                                    {{__('adminP.confirmed')}}
                                </button>
                            @elseif($order->statusi == 2)
                                <button class="btn btn-danger btn-block" >
                                   {{__('adminP.canceled')}}
                                </button>
                            @elseif($order->statusi == 3)
                                <button class="btn btn-success btn-block" >
                                    {{__('adminP.finished')}}
                                </button>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                {{ csrf_field()}}
                                <input type="hidden" value="{{$order->id}}" name="orId">
                                <button type="submit" class="btn"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                            </form>    
                        </td>
                    </tr>
                @endif

            @endforeach
            
        </tbody>
    </table>
</div>
<script>
    if ($(window).width() <= 580) {
       
    }else if($(window).width() > 580 && $(window).width() < 1200){
        $('#tabletTable').show();
    }else if($(window).width() >= 1200){
        $('#desktopTable').show();
    }
</script>




    @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])->whereDate('created_at', Carbon::today())->get()->sortByDesc('created_at') as $order)
        <?php
            $orderDate2D = explode(' ', $order->created_at);
        ?>
  
            <!-- The Modal -->
            <div class="modal" id="openOrder{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span>{{__('adminP.time')}} {{explode(':', $orderDate2D[1])[0]}}:{{explode(':', $orderDate2D[1])[1]}}</span>
                                @if ($order->nrTable == 500)
                                    <span class="ml-5"><strong>Takeaway</strong></span>
                                @elseif ($order->nrTable == 9000)
                                    <span class="ml-5"><strong>Delivery</strong></span>
                                @else
                                    <span class="ml-5"><strong> {{__('adminP.table')}} : {{$order->nrTable}}</strong></span>
                                @endif
                                

                                @if($order->userEmri != "empty" && $order->userEmri != "admin")
                                    <span class="ml-5">{{$order->userEmri}}</span>
                                @endif
                                @if($order->userEmail != "empty" && $order->userEmail != "admin")
                                    <span class="ml-5">{{$order->userEmail}}</span>
                                @endif


                                @if ($order->inCashDiscount > 0)
                                    <span class="ml-5"> {{__('adminP.total')}} mit rabatt : {{number_format($order->shuma - $order->inCashDiscount - $order->dicsountGcAmnt, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                                @elseif($order->inPercentageDiscount > 0)
                                    <?php
                                        $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                                        $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                                    ?>
                                    <span class="ml-5"> {{__('adminP.total')}} mit rabatt : {{number_format($order->shuma - $theDi - $order->dicsountGcAmnt, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                                @else
                                    <span class="ml-5"> {{__('adminP.total')}} : {{number_format($order->shuma - $order->dicsountGcAmnt, 2, '.', '')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                                @endif

                                @if($order->dicsountGcAmnt > 0)
                                    <span class="ml-5">GK: -{{number_format($order->dicsountGcAmnt, 2, '.', '')}}</span>
                                @endif

                                <span class="ml-5"><strong>{{$order->payM}}</strong></span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal"><i class="far fa-times-circle"></i></button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            
                            @foreach(explode('---8---',$order->porosia) as $produkti)
                                <div style="background-color: rgb(191,247,242); border-radius:8px;" class="mb-1 p-1 d-flex justify-content-between flex-wrap">
                                    <?php
                                        $prod = explode('-8-', $produkti);
                                    ?>
                                    <div style="width:70%;">
                                        <p style="font-size:21px; margin:0px;"><strong>({{$prod[3]}}X) {{$prod[0]}}</strong>
                                            @if($prod[5] != "" && $prod[5] != "empty")
                                                ( {{$prod[5]}} )
                                            @endif
                                        </p> 
                                        @if ($prod[1] != '...')
                                            <p style="margin-top:-20px; margin:0px;">{{$prod[1]}}</p> 
                                        @endif
                                    </div>
                                    <div style="width:15%;">
                                        <?php
                                            $eStep = 1;
                                        ?>
                                    @foreach(explode('--0--', $prod[2]) as $ex)
                                            @if(!empty($ex) && $ex != "" && $ex != "empty")
                                                @if($eStep++ == 1)
                                                    <p style="margin:0px; ">{{explode('||', $ex)[0]}}</p>
                                                @else
                                                    <p style="margin:0px; margin-top:-5px;">{{explode('||', $ex)[0]}}</p>
                                                @endif
                                            @endif
                                    @endforeach
                                    </div>
                                    <div style="width:15%;">
                                        <p style="margin:0px;font-size:21px; "><strong>{{$prod[4]}} <sup>{{__('adminP.currencyShow')}}</sup></strong></p>
                                    </div>
                                </div> 
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
    @endforeach

    @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])->whereDate('created_at', Carbon::today())->get()->sortByDesc('created_at') as $order)
        <?php
            $orderDate2D = explode(' ', $order->created_at);
        ?>
        @if($nowDate == $orderDate2D[0])
            @if(!empty($order))
                <div class="modal fade" id="ChStat{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                    style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        @if($order->userEmri != "empty" && $order->userEmri != "admin")
                        <h4 class="modal-title">{{$order->userEmri}} <span style="color:gray"> {{$order->userEmail}} </span></h4>
                        @endif
                        <button type="button" class="close" data-dismiss="modal"><i class="far fa-times-circle"></i></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="container">
                                <div class="row">
                                    <div class="col-3">
                                        {{Form::open(['action' => 'OrdersController@ChangeStatus02', 'method' => 'post']) }}

                                            {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                            {{ Form::hidden('orderStat', 0 , ['class' => 'form-control']) }}
                                            {{ Form::submit(__('adminP.waitingLine'), ['class' => 'form-control btn btn-warning btn-block shadow-none', 'style'=>'font-weight:bold;']) }}
                                        {{Form::close() }}
                                    </div>
                                    <div class="col-3">
                                        {{Form::open(['action' => 'OrdersController@ChangeStatus02', 'method' => 'post']) }}

                                            {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                            {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                            {{ Form::submit(__('adminP.confirmed'), ['class' => 'form-control btn btn-info btn-block shadow-none', 'style'=>'font-weight:bold;']) }}
                                        {{Form::close() }}
                                    </div>
                                    <div class="col-3">
                                        {{Form::open(['action' => 'OrdersController@ChangeStatus02', 'method' => 'post']) }}

                                            {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                            {{ Form::hidden('orderStat', 2 , ['class' => 'form-control']) }}
                                            {{ Form::submit(__('adminP.canceled'), ['class' => 'form-control btn btn-danger btn-block shadow-none', 'style'=>'font-weight:bold;']) }}
                                        {{Form::close() }}
                                    </div>
                                    <div class="col-3">
                                        {{Form::open(['action' => 'OrdersController@ChangeStatus02', 'method' => 'post']) }}

                                            {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                            {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                            {{ Form::submit(__('adminP.finished'), ['class' => 'form-control btn btn-success btn-block shadow-none', 'style'=>'font-weight:bold;']) }}
                                            
                                        {{Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            @endif
        @endif
    @endforeach