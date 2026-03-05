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
    use App\Restorant;
    use App\Orders;
    use App\User;
    use Carbon\Carbon;

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

    .title, .time{
        display:flex;
        justify-content:space-between;
    }

    .alll{
        background-color:#27beaf;
    }


 </style>
 
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>






<!-- Orders -->




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

    <h6 style="width: 45%; color:rgb(72,81,87);"><strong>Bezahlverfahren</strong></h6>
    <h6 style="width: 35%; color:rgb(72,81,87);"><strong>Gesamt</strong></h6>
    <h6 style="width: 20%; color:rgb(72,81,87);"><strong>Tipps</strong></h6>
    <hr style="width:100%;">

    <p style="width: 45%; color:rgb(72,81,87);"><strong>Barzahlungen</strong></p>
    <p style="width: 35%; color:rgb(72,81,87);"><strong>{{ number_format($cashTot + $gcSaleCash ,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 20%; color:rgb(72,81,87);"><strong>{{ number_format($cashTips ,2 ,'.' ,'' )}} CHF</strong></p>

    <p style="width: 45%; color:rgb(72,81,87);"><strong>Abzüglich Trinkgeld</strong></p>
    <p style="width: 35%; color:rgb(72,81,87);"><strong>{{ number_format($cashTot + $gcSaleCash - $cashTips - $cardTips - $onlineTips - $billsTips,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 20%; color:rgb(72,81,87);"><strong>---</strong></p>

    <p style="width: 45%; color:rgb(72,81,87);"><strong>Kartenzahlung</strong></p>
    <p style="width: 35%; color:rgb(72,81,87);"><strong>{{ number_format($cardTot + $gcSaleCard,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 20%; color:rgb(72,81,87);"><strong>{{ number_format($cardTips ,2 ,'.' ,'' )}} CHF</strong></p>

    <p style="width: 45%; color:rgb(72,81,87);"><strong>Online</strong></p>
    <p style="width: 35%; color:rgb(72,81,87);"><strong>{{ number_format($onlineTot + $gcSaleOnline ,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 20%; color:rgb(72,81,87);"><strong>{{ number_format($onlineTips ,2 ,'.' ,'' )}} CHF</strong></p>

    <p style="width: 45%; color:rgb(72,81,87);"><strong>Auf Rechnung</strong></p>
    <p style="width: 35%; color:rgb(72,81,87);"><strong>{{ number_format($billsTot + $gcSaleRechnung ,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 20%; color:rgb(72,81,87);"><strong>{{ number_format($billsTips ,2 ,'.' ,'' )}} CHF</strong></p>

    <!-- <p style="width: 45%; color:rgb(72,81,87);"><strong>Verkaufte GK</strong></p>
    <p style="width: 35%; color:rgb(72,81,87);"><strong>{{ number_format($totGCSalesThisWa ,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 20%; color:rgb(72,81,87);"><strong>---</strong></p> -->

    <p style="width: 45%; color:red;"><strong>Benutzte GK</strong></p>
    <p style="width: 35%; color:red;"><strong>{{ number_format($discFromGCTot ,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 20%; color:red;"><strong>---</strong></p>

    <p style="width: 45%; color:rgb(72,81,87);"><strong>Total</strong></p>
    <p style="width: 35%; color:rgb(72,81,87);"><strong>{{ number_format($cashTot+$cardTot+$onlineTot+$billsTot+$totGCSalesThisWa+$discFromGCTot - $cashTips - $cardTips - $onlineTips - $billsTips,2 ,'.' ,'' )}} CHF</strong></p>
    <p style="width: 20%; color:rgb(72,81,87);"><strong>{{ number_format($cashTips+$cardTips+$onlineTips+$billsTips ,2 ,'.' ,'' )}} CHF</strong></p>

</div>











<div class="alll">

 @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])->whereDate('created_at', Carbon::today())->get()->sortByDesc('created_at') as $order)
    @if(!empty($order))
        <div class="modal fade" id="status{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{$order->userEmri}} <span style="color:gray">{ {{$order->userEmail}} }</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
            <div class="container">
                    <div class="row">
                        <div class="col-12 mt-1">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 0 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.waitingLine'), ['class' => 'form-control btn btn-warning btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div class="col-12 mt-1">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.confirmed'), ['class' => 'form-control btn btn-info btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div class="col-12 mt-1">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 2 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.canceled'), ['class' => 'form-control btn btn-danger btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div class="col-12 mt-1">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.finished'), ['class' => 'form-control btn btn-success btn-block']) }}
                                
                            {{Form::close() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('adminP.back')}}</button>
            </div>

            </div>
        </div>
        </div>
    @endif
 @endforeach












<div>
    <div class="d-flex justify-content-space-between">
        <p class="color-black color-white text-center pt-2" style="font-size:23px; width:45%;">{{__('adminP.today')}}</p>
        <p class="color-black color-white text-right pt-2" style="font-size:23px; width:45%;" id="PageTime1">.</p>
    </div>
    <div class="d-flex flex-wrap b-white p-2">
        <p style="width:25%; border-bottom:1px solid gray;" class="text-left pb-2"><strong>{{__('adminP.time')}}</strong></p>
        <p style="width:45%; border-bottom:1px solid gray;" class="text-left pb-2"><strong>{{__('adminP.products')}}</strong></p>
        <p style="width:30%; border-bottom:1px solid gray;" class="text-center pb-2"><strong>{{__('adminP.status')}}</strong></p>

        @foreach (giftCard::where([['toRes',$thisRestaurantId],['oldGCtransfer','0']])->whereDate('created_at', Carbon::today())->get() as $gcSaleOne)
            <?php
                $or2D = explode(':',explode(' ',$gcSaleOne->created_at)[1]);
                $dt2D = explode('-',explode(' ',$gcSaleOne->created_at)[0]);
            ?>
            <p style="width:25%;" class="text-left pb-2"><strong>  
                <span>{{$dt2D[2]}}.{{$dt2D[1]}}</span><br>
                <span>{{$or2D[0]}}:{{$or2D[1]}}</span></strong>
            </p>
            <p style="width:45%;" class="text-center pb-2"><strong>CHF ({{number_format($gcSaleOne->gcSumInChf, 2, '.', ' ')}}) Geschenkkarte</strong></p>
           
                <form style="width:30%;" method="POST" action="{{ route('giftCard.giftCardGetReceipt') }}">
                    {{ csrf_field()}}
                    <input id="giftCardId" type="hidden" value="{{$gcSaleOne->id}}" name="giftCardId">
                    <button type="submit" class="btn shadow-none"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                </form>
         
           

        @endforeach
        @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])->whereDate('created_at', Carbon::today())->get()->sortByDesc('created_at') as $order)
            <?php
                $orderDate2D = explode(' ', $order->created_at);
                $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
            ?>
                <div class="modal fade" id="open{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title" style="width:100%;">
                                    <div class="title">
                                        @if($order->userEmri != "empty" && $order->userEmri != "admin")
                                        <span>{{$order->userEmri}}</span>
                                        @endif
                                        @if ($order->nrTable == 500)
                                            <span><strong>Takeaway</strong></span>
                                        @elseif ($order->nrTable == 9000)
                                            <span><strong>Delivery</strong></span>
                                        @else
                                            <span><strong> {{__('adminP.table')}} : {{$order->nrTable}}</strong></span>
                                        @endif
                                    </div>

                                    <div>
                                        @if ($order->inCashDiscount > 0)
                                            <span style="font-weight:bold;"> {{__('adminP.total')}} mit rabatt : {{number_format($order->shuma - $order->inCashDiscount - $order->dicsountGcAmnt, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                                        @elseif($order->inPercentageDiscount > 0)
                                            <?php
                                                $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                                                $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                                            ?>
                                            <span style="font-weight:bold;"> {{__('adminP.total')}} mit rabatt : {{number_format($order->shuma - $theDi - $order->dicsountGcAmnt, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                                        @else
                                            <span style="font-weight:bold;"> {{__('adminP.total')}} : {{number_format($order->shuma - $order->dicsountGcAmnt, 2, '.', '')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                                        @endif

                                        @if($order->dicsountGcAmnt > 0)
                                            <span class="ml-5">GK: -{{number_format($order->dicsountGcAmnt, 2, '.', '')}}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="mb-1"><strong>{{$order->payM}}</strong></p>
                                        @if ($theRefIns != Null)
                                            <strong>Saferpay: {{$theRefIns->refPh}}</strong><br>
                                        @endif
                                    </div>

                                    <div class="time">
                                        <span>{{explode(':', $orderDate2D[1])[0]}}:{{explode(':', $orderDate2D[1])[1]}} / {{explode('-', $orderDate2D[0])[2]}}.{{explode('-', $orderDate2D[0])[1] }}.{{explode('-', $orderDate2D[0])[0] }}</span>
                                    </div>
                                </h5>
                                <button type="button" class="close" data-dismiss="modal"><i class="far fa-times-circle"></i></button>
                            </div>

                            <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                {{ csrf_field()}}
                                <input type="hidden" value="{{$order->id}}" name="orId">
                                <button type="submit" style="margin:0px;" class="mt-1 btn btn-outline-dark btn-block shadow-none"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                            </form>

                            <!-- Modal body -->
                            <div class="modal-body" style="padding: 4px;">
                                
                                @foreach(explode('---8---',$order->porosia) as $produkti)
                                    <div style="background-color: rgb(191,247,242); border-radius:8px;" class="mb-1 p-1 d-flex justify-content-between flex-wrap">
                                        <?php
                                            $prod = explode('-8-', $produkti);
                                        ?>
                                        <div style="width:60%;">
                                            <p style="font-size:14px;"><strong>{{$prod[0]}}</strong>
                                                @if($prod[5] != "" && $prod[5] != "empty")
                                                    ( {{$prod[5]}} )
                                                @endif
                                            </p> 
                                            @if ($prod[1] != '...')
                                                <p style="margin-top:-20px;">{{$prod[1]}}</p> 
                                            @endif
                                        </div>
                                        <div style="width:25%;">
                                            <?php
                                                $eStep = 1;
                                            ?>
                                        @foreach(explode('--0--', $prod[2]) as $ex)
                                            @if(!empty($ex) && $ex != "" && $ex != "empty")
                                                @if($eStep++ == 1)
                                                    <p>{{explode('||', $ex)[0]}}</p>
                                                @else
                                                    <p style="margin-top:-15px;">{{explode('||', $ex)[0]}}</p>
                                                @endif
                                            @endif
                                        @endforeach
                                        </div>
                                        <div style="width:15%;">
                                            <p>{{$prod[4]}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>




               <div style="width:25%" class="text-left pb-2" data-toggle="modal" data-target="#open{{$order->id}}">';
                
                <!-- <p>{{explode(':', $orderDate2D[1])[0]}}:{{explode(':', $orderDate2D[1])[1]}}</p>  -->
                    <p>
                        @if ($order->inCashDiscount > 0)
                            {{number_format($order->shuma - $order->inCashDiscount - $order->dicsountGcAmnt, 2, '.', ' ')}}<span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span>
                            <br>
                            -{{number_format($order->inCashDiscount, 2, '.', ' ')}}<span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span>
                        @elseif($order->inPercentageDiscount > 0)
                            <?php
                                $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                                $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                            ?>
                            {{number_format($order->shuma - $theDi - $order->dicsountGcAmnt, 2, '.', ' ')}}<span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span>
                            <br>
                            -{{number_format($order->inPercentageDiscount, 2, '.', ' ')}} %
                        @else
                            {{number_format($order->shuma - $order->dicsountGcAmnt, 2, '.', ' ')}}<span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span>
                        @endif

                        
                        
                    </p>

                    <p style="margin-top:-15px;" >
                        @if ($order->nrTable == 500 )
                            <strong>Takeaway</strong>
                        @elseif ($order->nrTable == 9000 )
                            <strong>Delivery</strong>
                        @else
                            <strong>T :{{$order->nrTable}}</strong> 
                        @endif
                    </p>
                </div>
                <p style="width:45%;" class="text-left" data-toggle="modal" data-target="#open{{$order->id}}"> 
                    <?php
                        echo explode('-8-', explode('---8---',$order->porosia)[0])[0];
                        if(!empty(explode('---8---',$order->porosia)[1])){
                            echo '<br>...';
                        }
                    ?>
                </p>
                <p style="width:30%;" class="text-center"> 
                    <?php
                            if($order->statusi == 0){
                                echo '
                                <button class="btn btn-warning btn-block shadow-none" >
                                    '.__("adminP.waitingLine").'
                                </button>
                                ';
                            }else if($order->statusi == 1){
                                echo '
                                <button class="btn btn-info btn-block shadow-none" >
                                    '.__("adminP.confirmed").'
                                </button>
                                ';
                            }else if($order->statusi == 2){
                                echo '
                                <button class="btn btn-danger btn-block shadow-none" >
                                    '.__("adminP.canceled").'
                                </button>
                                ';
                            }else if($order->statusi == 3){
                                echo '
                                <button class="btn btn-success btn-block shadow-none" >
                                    '.__("adminP.ready").'
                                </button>
                                ';
                            }
                    ?>
                </p>
        @endforeach
    </div>



</div>




</div>












<script>
     function pad(d) {
        return (d < 10) ? '0' + d.toString() : d.toString();
    }
    function showTime() {
        var today = new Date()
        var time = pad(today.getHours())+':'+pad(today.getMinutes())+':'+pad(today.getSeconds());
        $("#PageTime1").html(time);
    }

$( document ).ready(function() {
     setInterval(showTime, 1000);
});
   
</script>

