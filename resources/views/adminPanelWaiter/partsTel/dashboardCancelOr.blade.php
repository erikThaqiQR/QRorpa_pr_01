<?php

use App\OrdersPassive;
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
    $nowDate = date('Y-m-d');
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

    .otherMonthsBtn:hover{
        color:white;
        background-color: rgb(39,190,175);
        font-size: 20px;
    }
    .otherMonthsBtn{
        color:rgb(39,190,175);     
        border:1px solid rgb(39,195,175);
        font-size: 20px;
    }
 </style>
 
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>



<!-- Orders -->

          

@if(isset($_GET['mo']) && isset($_GET['ye']))
    <?php
        $getFromM = $_GET['mo'];
        $getFromY = $_GET['ye'];
    ?>
@else
    <?php
        $getFromM = Carbon::today()->month;
        $getFromY = Carbon::today()->year;
    ?>
@endif








<!-- otherMonthsStatsMonth Modal -->
<div class="modal" id="otherMonthsStatsMonth" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="background-color:rgb(39,190,175);">
        <h4 style="color:white;" class="modal-title">{{__('adminP.recoverMonths')}}</h4>
        <button style="color:white;" type="button" class="close" data-dismiss="modal"> X </button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div  class="d-flex flex-wrap justify-content-between pr-2 pl-2 pb-4">
            <?php
                $monthCount = Carbon::now()->month;
                $yearCount = Carbon::now()->year;

                $resCreated =explode(' ',Restorant::find(Auth::user()->sFor)->created_at)[0];
                $resCreatedM = explode('-', $resCreated)[1];
                $resCreatedY = explode('-', $resCreated)[0];

                // echo ''.$monthCount.' >= '. $resCreatedM.'  '. $yearCount.'>='.$resCreatedY;
                while(true){
                    if(($monthCount >= $resCreatedM && $yearCount == $resCreatedY) || $yearCount > $resCreatedY ){
                        echo "
                            <div class='b-qrorpa color-white text-center p-1 m-1' style='border-radius:10px; width:100%; font-size:16px; font-weight:bold;'>
                                <a class='color-white anchorHover' href='admWoMngCanceledOrdersWaiter?mo=".$monthCount."&ye=".$yearCount."'>
                                    ( ".$monthCount." )";
                                    switch($monthCount){
                                        case 1: echo   __('adminP.jan').".".$yearCount.""; break;
                                        case 2: echo __('adminP.feb').".".$yearCount.""; break;
                                        case 3: echo __('adminP.march').".".$yearCount.""; break;
                                        case 4: echo __('adminP.apr').".".$yearCount.""; break;
                                        case 5: echo __('adminP.May').".".$yearCount.""; break;
                                        case 6: echo __('adminP.june').".".$yearCount.""; break;
                                        case 7: echo __('adminP.july').".".$yearCount.""; break;
                                        case 8: echo __('adminP.aug').".".$yearCount.""; break;
                                        case 9: echo __('adminP.sept').".".$yearCount.""; break;
                                        case 10: echo __('adminP.oct').".".$yearCount.""; break;
                                        case 11: echo __('adminP.nov').".".$yearCount.""; break;
                                        case 12: echo __('adminP.dec').".".$yearCount.""; break;   
                                    }
                        echo "    
                                </a>
                            </div>
                        ";
                        // Pjesa per vitin 
                        if($monthCount == 1){
                            $yearCount--;
                            $monthCount=12;
                        }else{
                            $monthCount--;
                        }

                    }else{
                        // echo 'nuk po merr asnje';
                        break;
                    }   
                }
            ?>                    
        </div>
      </div>
    </div>
  </div>
</div>





<div class="alll">

    @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','2']])->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->whereIn('nrTable',$myTablesWaiter)->get()->sortByDesc('created_at') as $order)
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
            <p class="color-black color-white text-center pt-2" style="font-size:23px; width:45%;">{{__('adminP.thisMonth')}}</p>
            <p class="color-black color-white text-right pt-2" style="font-size:23px; width:45%;" id="PageTime1">.</p>
        </div>


        <div class="d-flex flex-wrap b-white p-2">
            <button data-toggle="modal" data-target="#otherMonthsStatsMonth" class="btn btn-block otherMonthsBtn shadow-none" > {{__('adminP.previousMonths')}} 
            <strong>(
            <?php
                switch($getFromM){
                    case 1: echo   __('adminP.jan').".".$getFromY.""; break;
                    case 2: echo __('adminP.feb').".".$getFromY.""; break;
                    case 3: echo __('adminP.march').".".$getFromY.""; break;
                    case 4: echo __('adminP.apr').".".$getFromY.""; break;
                    case 5: echo __('adminP.May').".".$getFromY.""; break;
                    case 6: echo __('adminP.june').".".$getFromY.""; break;
                    case 7: echo __('adminP.july').".".$getFromY.""; break;
                    case 8: echo __('adminP.aug').".".$getFromY.""; break;
                    case 9: echo __('adminP.sept').".".$getFromY.""; break;
                    case 10: echo __('adminP.oct').".".$getFromY.""; break;
                    case 11: echo __('adminP.nov').".".$getFromY.""; break;
                    case 12: echo __('adminP.dec').".".$getFromY.""; break;   
                }
            ?>
            )
            </strong>
            </button>

            <p style="width:25%; border-bottom:1px solid gray;" class="text-left pb-2"><strong>{{__('adminP.time')}}</strong></p>
            <p style="width:45%; border-bottom:1px solid gray;" class="text-left pb-2"><strong>{{__('adminP.products')}}</strong></p>
            <p style="width:30%; border-bottom:1px solid gray;" class="text-center pb-2"><strong>{{__('adminP.status')}}</strong></p>






















            @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','2']])->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->whereIn('nrTable',$myTablesWaiter)->get()->sortByDesc('created_at') as $order)
                <?php
                    $orderDate2D = explode(' ', $order->created_at);
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
                                            <span style="font-weight:bold;"> {{__('adminP.total')}} mit rabatt : {{number_format($order->shuma - $order->inCashDiscount, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                                        @elseif($order->inPercentageDiscount > 0)
                                            <?php
                                                $theDi = number_format($order->shuma * ($order->inPercentageDiscount * 0.01), 2, '.', ' ');
                                            ?>
                                            <span style="font-weight:bold;"> {{__('adminP.total')}} mit rabatt : {{number_format($order->shuma - $theDi, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                                        @else
                                            <span style="font-weight:bold;"> {{__('adminP.total')}} : {{number_format($order->shuma, 2, '.', '')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                                        @endif
                                    </div>
                                    <div>
                                        <span><strong>{{$order->payM}}</strong></span>
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



                <?php
                    echo '<div style="width:25%" class="text-left pb-2" data-toggle="modal" data-target="#open'.$order->id.'">';
                ?>
                <!-- <p>{{explode(':', $orderDate2D[1])[0]}}:{{explode(':', $orderDate2D[1])[1]}}</p>  -->
                    <p>
                        @if ($order->inCashDiscount > 0)
                            {{number_format($order->shuma - $order->inCashDiscount, 2, '.', ' ')}}<span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span>
                            <br>
                            -{{number_format($order->inCashDiscount, 2, '.', ' ')}}<span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span>
                        @elseif($order->inPercentageDiscount > 0)
                            <?php
                                $theDi = number_format($order->shuma * ($order->inPercentageDiscount * 0.01), 2, '.', ' ');
                            ?>
                            {{number_format($order->shuma - $theDi, 2, '.', ' ')}}<span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span>
                            <br>
                            -{{number_format($order->inPercentageDiscount, 2, '.', ' ')}} %
                        @else
                            {{$order->shuma}}<span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span>
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
                    <button class="btn btn-danger btn-block shadow-none" style="font-size: 10px; font-weight:bold;" disabled>
                        {{__("adminP.canceled")}} 
                        @if($order->StatusBy!= 999999)
                            @if(User::find($order->StatusBy) !=null)
                                <br>{{User::find($order->StatusBy)->name}}
                            @endif
                        @endif
                    </button>
            
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

