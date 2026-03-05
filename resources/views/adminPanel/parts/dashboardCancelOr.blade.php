<?php

use App\OrdersPassive;
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Statistiken']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Orders;
    use App\User;
    use App\Restorant;
    use Carbon\Carbon;

    use Jenssegers\Agent\Agent;
    $agent = new Agent();

     $thisRestaurantId = Auth::user()->sFor;
     $nowDate = date('Y-m-d');
     $nowMonth = date('m');
?>
 <style>
    .openOrderRow{
        border-bottom:1px solid lightgray;

    }
    .openOrderRow:hover{
       cursor:pointer;
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

    .anchorHover:hover{
        color: whitesmoke;
        text-decoration: none;
    }
</style>
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <div class="col mr-2">
        @if(isset($_GET['mo']))
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{__('adminP.sales')}} (
                <?php switch($_GET['mo']){
                    case 1: echo __('adminP.jan'); break;
                    case 2: echo __('adminP.feb'); break;
                    case 3: echo __('adminP.march'); break;
                    case 4: echo __('adminP.apr'); break;
                    case 5: echo __('adminP.May'); break;
                    case 6: echo __('adminP.june'); break;
                    case 7: echo __('adminP.july'); break;
                    case 8: echo __('adminP.aug'); break;
                    case 9: echo __('adminP.sept'); break;
                    case 10: echo __('adminP.oct'); break;
                    case 11: echo __('adminP.nov'); break;
                    case 12: echo __('adminP.dec'); break;   
                } ?>
            )</div>
        @else
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{__('adminP.salesThisMonth')}}</div>
        @endif
    </div>
                        
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






<div class="p-1 pb-4">
    @if($agent->isTablet())
    <table class="table table-hover" id="tabletTable">
    <button data-toggle="modal" data-target="#otherMonthsStatsMonth" class="btn btn-block otherMonthsBtn" id="tabletTableBtn" style="display:none;"> {{__('adminP.previousMonths')}} </button>
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
            @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','2']])->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)
            ->get()->sortByDesc('created_at') as $order)
                <?php
                    $orderDate2D= explode('-',explode(' ',$order->created_at)[0]); 
                ?>
                    <tr class="openOrderRow">
                        <td data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            {{explode(' ',$order->created_at)[1]}}<br>
                            <span style="font-size:12px; opacity:60%;">{{explode(' ',$order->created_at)[0]}}</span><br>
                        </td>
                        <td data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            @if ($order->nrTable == 500 )
                                <p><strong class="pl-2">Takeaway</strong></p>
                            @elseif ($order->nrTable == 9000 )
                                <p><strong class="pl-2">Delivery</strong></p>
                            @else
                                <p><strong class="pl-2">{{$order->nrTable}}</strong></p>
                            @endif
                        </td>
                        <?php
                            $thisOrder = explode('---8---',$order->porosia)[0];
                            $thisProd = explode('-8-',$thisOrder);
                        ?>
                        <td data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            <p>{{$thisProd[0]}}</p>
                            @if(!empty( explode('---8---',$order->porosia)[1]))
                                <p style=" margin-top:-20px;">...</p>
                            @endif
                        </td>
                        <td>

                            <button class="btn btn-danger btn-block shadow-none" >
                                {{__('adminP.canceled')}}
                                @if($order->StatusBy!= 999999)
                                    @if(User::find($order->StatusBy) !=null)
                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                    @endif
                                @endif
                            </button>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                {{ csrf_field()}}
                                <input type="hidden" value="{{$order->id}}" name="orId">
                                <button type="submit" class="btn"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                            </form>
                        </td>
                    </tr>
               
            @endforeach
            
        </tbody>
    </table>
    @endif

















<!-- The Modal -->
<div class="modal" id="otherMonthsStatsMonth" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
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
                                    <div class='b-qrorpa color-white text-center p-2 m-2' style='border-radius:20px; width:25%; font-size:19px; font-weight:bold;'>
                                        <a class='color-white anchorHover' href='canceledOrders?mo=".$monthCount."&ye=".$yearCount."'>
                                            ( ".$monthCount." )";
                                            switch($monthCount){
                                                case 1: echo   __('adminP.jan')."/".$yearCount.""; break;
                                                case 2: echo __('adminP.feb')."/".$yearCount.""; break;
                                                case 3: echo __('adminP.march')."/".$yearCount.""; break;
                                                case 4: echo __('adminP.apr')."/".$yearCount.""; break;
                                                case 5: echo __('adminP.May')."/".$yearCount.""; break;
                                                case 6: echo __('adminP.june')."/".$yearCount.""; break;
                                                case 7: echo __('adminP.july')."/".$yearCount.""; break;
                                                case 8: echo __('adminP.aug')."/".$yearCount.""; break;
                                                case 9: echo __('adminP.sept')."/".$yearCount.""; break;
                                                case 10: echo __('adminP.oct')."/".$yearCount.""; break;
                                                case 11: echo __('adminP.nov')."/".$yearCount.""; break;
                                                case 12: echo __('adminP.dec')."/".$yearCount.""; break;   
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














    @if(!$agent->isTablet())
    <table class="table table-hover" id="desktopTable">
    <button data-toggle="modal" data-target="#otherMonthsStatsMonth" class="btn btn-block otherMonthsBtn shadow-none" > {{__('adminP.previousMonths')}} </button>
        <thead>
        <tr>
            <th style="opacity:50%;">{{__('adminP.time')}}</th>
            <th style="opacity:50%;">{{__('adminP.table')}}</th>
            <th style="opacity:50%;">{{__('adminP.products')}}</th>
            <th style="opacity:50%;">{{__('adminP.total')}}</th>
            <th style="opacity:50%;">{{__('adminP.payemntMethod')}}</th>
            <th style="opacity:50%;">{{__('adminP.status')}}</th>
            <th style="opacity:50%;">{{__('adminP.press')}}</th>

            <!-- <th style="opacity:50%;">e-bank</th> -->
        </tr>
        </thead>
        <tbody>
            @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','2']])->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->get()->sortByDesc('created_at') as $order)
                <?php
                    $orderDate2D= explode('-',explode(' ',$order->created_at)[0]); 
                ?>
               
                    <tr class="openOrderRow">
                        <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
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
                        <td>    
                            @if ($order->inCashDiscount > 0)
                                {{number_format($order->shuma - $order->inCashDiscount, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup>
                                <br>
                                -{{number_format($order->inCashDiscount, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup>
                            @elseif($order->inPercentageDiscount > 0)
                                <?php
                                    $theDi = number_format($order->shuma * ($order->inPercentageDiscount * 0.01), 2, '.', ' ');
                                ?>
                                {{number_format($order->shuma - $theDi, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup>
                                <br>
                                -{{number_format($order->inPercentageDiscount, 2, '.', ' ')}} %
                            @else
                                <p>{{$order->shuma}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                            @endif
                        </td>
                        <td>
                            <p>{{$order->payM}}</p>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-block shadow-none" disabled>
                                {{__('adminP.canceled')}}
                                @if($order->StatusBy!= 999999)
                                    @if(User::find($order->StatusBy) !=null)
                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                    @endif
                                @endif
                            </button>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                {{ csrf_field()}}
                                <input type="hidden" value="{{$order->id}}" name="orId">
                                <button type="submit" class="btn shadow-none"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                            </form>
                        </td>
                        <!-- <td>
                            <button type="button" onclick="genEBankQRC('{{$order->id}}')" class="btn"> <i class="fas fa-qrcode fa-2x"></i> </button>
                        </td> -->
                    </tr>
                

            @endforeach
            
        </tbody>
    </table>
    @endif
</div>
<script>
    function genEBankQRC(oId){
        $.ajax({
			url: '{{ route("testAdm.genEBankingQrCode") }}',
			method: 'post',
			data: {
				orId: oId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
				console.log(respo);
			},
			error: (error) => {
				console.log(error);
				alert('bitte aktualisieren und erneut versuchen!');
			}
		});
    }
</script>







    @foreach(OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','2']])->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->get() as $order)
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
                                <span class="ml-5"> {{__('adminP.total')}} mit rabatt : {{number_format($order->shuma - $order->inCashDiscount, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                            @elseif($order->inPercentageDiscount > 0)
                                <?php
                                    $theDi = number_format($order->shuma * ($order->inPercentageDiscount * 0.01), 2, '.', ' ');
                                ?>
                                <span class="ml-5"> {{__('adminP.total')}} mit rabatt : {{number_format($order->shuma - $theDi, 2, '.', ' ')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
                            @else
                                <span class="ml-5"> {{__('adminP.total')}} : {{number_format($order->shuma, 2, '.', '')}}<sup>{{__('adminP.currencyShow')}}</sup></span>
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
    @endforeach

 