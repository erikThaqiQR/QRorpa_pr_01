<?php

use App\OPSaferpayReference;
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

    <?php
        $OrderAllCount = Orders::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])
        ->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->orderByDesc('created_at')->get()->count();

        if(isset($_GET['orSk']) && $_GET['orSk'] > 0){
            $showOr = Orders::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])
            ->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->orderByDesc('created_at')->skip($_GET['orSk'])->take(1000)->get();
        }else{
            $showOr = Orders::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])
            ->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->orderByDesc('created_at')->take(1000)->get();
        }
        
        // ->skip(500)
    ?>








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
                                <a class='color-white anchorHover' href='AccountantStatisticsDash1?mo=".$monthCount."&ye=".$yearCount."'>
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





<div class="alll">

    <div>
        <div class="d-flex justify-content-space-between">
            <p class="color-black color-white text-center pt-2" style="font-size:23px; width:45%;">{{__('adminP.thisMonth')}}</p>
            <p class="color-black color-white text-right pt-2" style="font-size:23px; width:45%;" id="PageTime1">.</p>
        </div>

        <div class="d-flex flex-wrap justify-content-between b-white p-1">

            @if (!isset($_GET['orSk']) || (isset($_GET['orSk']) && $_GET['orSk'] == 0))
                <button class="btn btn-dark mb-2" style="width:33%;"><strong>0-1000</strong></button>
            @else
                @if(isset($_GET['mo']) && isset($_GET['ye']))
                <?php $mo=$_GET['mo']; $ye=$_GET['ye']; ?>
                <button onclick="location.href = 'AccountantStatisticsDash1?orSk=0&mo={{$mo}}&ye={{$ye}}';" class="btn btn-outline-dark mb-2" style="width:33%;"><strong>0-1000</strong></button>
                @else
                <button onclick="location.href = 'AccountantStatisticsDash1?orSk=0';" class="btn btn-outline-dark mb-2" style="width:33%;"><strong>0-1000</strong></button>
                @endif
            @endif

            @foreach (range(1, 8) as $nrRend) 
                <?php
                    $rngSt = $nrRend*1000;
                    $rngEd = $rngSt+1000;
                ?>
                @if ($OrderAllCount > $rngSt && $OrderAllCount >= $rngEd)
                    @if (isset($_GET['orSk']) && $_GET['orSk'] == $rngSt)
                        <button class="btn btn-dark shadow-none mb-2" style="width:33%;"><strong>{{$rngSt+1}}-{{$rngEd}}</strong></button>
                    @else
                        @if(isset($_GET['mo']) && isset($_GET['ye']))
                        <?php $mo=$_GET['mo']; $ye=$_GET['ye']; ?>
                        <button onclick="location.href = 'AccountantStatisticsDash1?orSk={{$rngSt}}&mo={{$mo}}&ye={{$ye}}';" class="btn btn-outline-dark shadow-none mb-2" style="width:33%;"><strong>{{$rngSt+1}}-{{$rngEd}}</strong></button>
                        @else
                        <button onclick="location.href = 'AccountantStatisticsDash1?orSk={{$rngSt}}';" class="btn btn-outline-dark shadow-none mb-2" style="width:33%;"><strong>{{$rngSt+1}}-{{$rngEd}}</strong></button>
                        @endif
                    @endif
                @elseif ($OrderAllCount > $rngSt)
                    @if (isset($_GET['orSk']) && $_GET['orSk'] == $rngSt)
                        <button class="btn btn-dark shadow-none mb-2" style="width:33%;"><strong>{{$rngSt+1}}-{{$OrderAllCount}}</strong></button>
                    @else
                        @if(isset($_GET['mo']) && isset($_GET['ye']))
                        <?php $mo=$_GET['mo']; $ye=$_GET['ye']; ?>
                        <button onclick="location.href = 'AccountantStatisticsDash1?orSk={{$rngSt}}&mo={{$mo}}&ye={{$ye}}';" class="btn btn-outline-dark shadow-none mb-2" style="width:33%;"><strong>{{$rngSt+1}}-{{$OrderAllCount}}</strong></button>
                        @else
                        <button onclick="location.href = 'AccountantStatisticsDash1?orSk={{$rngSt}}';" class="btn btn-outline-dark shadow-none mb-2" style="width:33%;"><strong>{{$rngSt+1}}-{{$OrderAllCount}}</strong></button>
                        @endif
                    @endif
                @endif
            @endforeach

            <button data-toggle="modal" data-target="#otherMonthsStatsMonth" style="width:100%;" class="btn otherMonthsBtn" > {{__('adminP.previousMonths')}} 
            <strong>(
            <?php
                switch($getFromM){
                    case 1: echo   __('adminP.jan')."/".$getFromY.""; break;
                    case 2: echo __('adminP.feb')."/".$getFromY.""; break;
                    case 3: echo __('adminP.march')."/".$getFromY.""; break;
                    case 4: echo __('adminP.apr')."/".$getFromY.""; break;
                    case 5: echo __('adminP.May')."/".$getFromY.""; break;
                    case 6: echo __('adminP.june')."/".$getFromY.""; break;
                    case 7: echo __('adminP.july')."/".$getFromY.""; break;
                    case 8: echo __('adminP.aug')."/".$getFromY.""; break;
                    case 9: echo __('adminP.sept')."/".$getFromY.""; break;
                    case 10: echo __('adminP.oct')."/".$getFromY.""; break;
                    case 11: echo __('adminP.nov')."/".$getFromY.""; break;
                    case 12: echo __('adminP.dec')."/".$getFromY.""; break;   
                }
            ?>
            )
            </strong>
            </button>

            <p style="width:25%; border-bottom:1px solid gray;" class="text-left pb-2"><strong>{{__('adminP.time')}}</strong></p>
            <p style="width:45%; border-bottom:1px solid gray;" class="text-left pb-2"><strong>{{__('adminP.products')}}</strong></p>
            <p style="width:30%; border-bottom:1px solid gray;" class="text-center pb-2"><strong>{{__('adminP.status')}}</strong></p>






















         
            <table class="table table-sm" cellspacing="0" width="100%" id="desktopTable">

                <thead class="">
                    <tr>
                        <th style="opacity:50%;">{{__('adminP.time')}}</th>
                        <th style="opacity:50%;">{{__('adminP.products')}}</th>
                        <th style="opacity:50%;">CHF</th>
                        <th style="opacity:50%;">{{__('adminP.status')}}</th>

                        <!-- <th style="opacity:50%;">e-bank</th> -->
                    </tr>
                    </thead>
                    <tbody id="desktopTableBody">
                        @foreach($showOr as $order)
                            <?php
                                $orderDate2D = explode(' ', $order->created_at);
                                $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                            ?>
                        
                                <tr class="openOrderRow">
                                    <td  data-toggle="modal" data-target="#open{{$order->id}}">
                                        <?php
                                            $or2D = explode(':',explode(' ',$order->created_at)[1]);
                                            $dt2D = explode('-',explode(' ',$order->created_at)[0]);
                                        ?>
                                        <span>{{$dt2D[2]}}.{{$dt2D[1]}}</span><br>
                                        <span>{{$or2D[0]}}:{{$or2D[1]}}</span>
                                        <!-- {{explode(' ',$order->created_at)[1]}}<br>
                                        <span style="font-size:12px; opacity:60%;">{{explode(' ',$order->created_at)[0]}}</span><br> -->
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
                                    <td  data-toggle="modal" data-target="#open{{$order->id}}">
                                        <p>{{$thisProd1[0]}}</p>
                                        @if(!empty($thisOrder[1]))
                                            <p style="margin-top:-20px;">{{$thisProd2[0]}}</p>
                                        @endif
                                        @if(!empty($thisOrder[2]))
                                            <p style="margin-top:-20px;">{{$thisProd3[0]}}</p>
                                        @endif
                                        @if(!empty($thisOrder[3]))
                                            <p style="margin-top:-20px;">...</p>
                                        @endif
                                    </td>
                                    <td>    
                                        @if ($order->inCashDiscount > 0)
                                            {{number_format($order->shuma - $order->inCashDiscount, 2, '.', ' ')}}
                                            <br>
                                            -{{number_format($order->inCashDiscount, 2, '.', ' ')}}
                                        @elseif($order->inPercentageDiscount > 0)
                                            <?php
                                                $theDi = number_format($order->shuma * ($order->inPercentageDiscount * 0.01), 2, '.', ' ');
                                            ?>
                                            {{number_format($order->shuma - $theDi, 2, '.', ' ')}}
                                            <br>
                                            -{{number_format($order->inPercentageDiscount, 2, '.', ' ')}} %
                                        @else
                                            <p>{{$order->shuma}}</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->statusi == 0)                 
                                            <button class="btn btn-warning btn-block" >
                                                .
                                                @if($order->StatusBy!= 999999)
                                                    @if(User::find($order->StatusBy) !=null)
                                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                                    @endif
                                                @endif
                                            </button>           
                                        @elseif($order->statusi == 1)
                                            <button class="btn btn-info btn-block" >
                                            .
                                            @if($order->StatusBy!= 999999)
                                                    @if(User::find($order->StatusBy) !=null)
                                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                                    @endif
                                                @endif
                                            </button>
                                        @elseif($order->statusi == 2)
                                            <button class="btn btn-danger btn-block" >
                                                .
                                                @if($order->StatusBy!= 999999)
                                                    @if(User::find($order->StatusBy) !=null)
                                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                                    @endif
                                                @endif
                                            </button>
                                        @elseif($order->statusi == 3)
                                            <button class="btn btn-success btn-block" >
                                                .
                                                @if($order->StatusBy!= 999999)
                                                    @if(User::find($order->StatusBy) !=null)
                                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                                    @endif
                                                @endif
                                            </button>
                                        @endif
                                    </td>

                                    <!-- <td>
                                        <button type="button" onclick="genEBankQRC('{{$order->id}}')" class="btn"> <i class="fas fa-qrcode fa-2x"></i> </button>
                                    </td> -->
                                </tr>
                            

                                <div class="modal" id="open{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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







                    @endforeach
                        
                </tbody>
            </table>
            
         
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
    
        $('#desktopTable').DataTable({
            "pagingType": "numbers", // "simple" option for 'Previous' and 'Next' buttons only
            "searching": true, // false to disable search (or any other option)
            "aaSorting": [],columnDefs: [{orderable: false,targets: [1,3]}],
            
        });
        $('.dataTables_length').addClass('bs-select');
    });
   
</script>

