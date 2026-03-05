<?php

use App\User;
use App\giftCard;
    use App\accessControllForAdmins;
use App\OrdersPassive;
use App\OPSaferpayReference;

    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Statistiken']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Orders;
    use App\Restorant;
    use Carbon\Carbon;

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
 
 <link rel="stylesheet" type="text/css" href=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

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

                        <?php
                            $OrderAllCount = OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])
                            ->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->orderByDesc('created_at')->get()->count();

                            if(isset($_GET['orSk']) && $_GET['orSk'] > 0){
                                $showOr = OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])
                                ->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->orderByDesc('created_at')->skip($_GET['orSk'])->take(1000)->get();
                                $showGCSales = Null;
                            }else{
                                $showOr = OrdersPassive::where([['Restaurant',$thisRestaurantId],['statusi','!=','2']])
                                ->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->orderByDesc('created_at')->take(1000)->get();
                                $showGCSales = giftCard::where([['toRes',$thisRestaurantId],['oldGCtransfer','0']])->whereMonth('created_at', $getFromM)->whereYear('created_at', $getFromY)->get();
                            }
                            
                            // ->skip(500)
                        ?>






<div class="p-1 pb-4">
  
















<!-- The Modal -->
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
                                        <div class='b-qrorpa color-white text-center p-2 m-2' style='border-radius:20px; width:25%; font-size:19px; font-weight:bold;'>
                                            <a class='color-white anchorHover' href='statsDash01?mo=".$monthCount."&ye=".$yearCount."'>
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










<div class="d-flex justify-content-between">
    <?php 
        if(count( $showOr) < 1000 ){ $firstMax = count( $showOr);
        }else{ $firstMax = 1000; }
    ?>
    @if (!isset($_GET['orSk']) || (isset($_GET['orSk']) && $_GET['orSk'] == 0))
        <button class="btn btn-dark mb-2 shadow-none" style="width:10%;"><strong>0-{{$firstMax}}</strong></button>
    @else
        @if(isset($_GET['mo']) && isset($_GET['ye']))
        <?php $mo=$_GET['mo']; $ye=$_GET['ye']; ?>
        <button onclick="location.href = 'statsDash01?orSk=0&mo={{$mo}}&ye={{$ye}}';" class="btn btn-outline-dark mb-2 shadow-none" style="width:10%;"><strong>0-{{$firstMax}}</strong></button>
        @else
        <button onclick="location.href = 'statsDash01?orSk=0';" class="btn btn-outline-dark mb-2 shadow-none" style="width:10%;"><strong>0-{{$firstMax}}</strong></button>
        @endif
    @endif

    @foreach (range(1, 8) as $nrRend) 
        <?php
            $rngSt = $nrRend*1000;
            $rngEd = $rngSt+1000;
        ?>
        @if ($OrderAllCount > $rngSt && $OrderAllCount >= $rngEd)
            @if (isset($_GET['orSk']) && $_GET['orSk'] == $rngSt)
                <button class="btn btn-dark shadow-none mb-2" style="width:10%;"><strong>{{$rngSt+1}}-{{$rngEd}}</strong></button>
            @else
                @if(isset($_GET['mo']) && isset($_GET['ye']))
                <?php $mo=$_GET['mo']; $ye=$_GET['ye']; ?>
                <button onclick="location.href = 'statsDash01?orSk={{$rngSt}}&mo={{$mo}}&ye={{$ye}}';" class="btn btn-outline-dark shadow-none mb-2" style="width:10%;"><strong>{{$rngSt+1}}-{{$rngEd}}</strong></button>
                @else
                <button onclick="location.href = 'statsDash01?orSk={{$rngSt}}';" class="btn btn-outline-dark shadow-none mb-2" style="width:10%;"><strong>{{$rngSt+1}}-{{$rngEd}}</strong></button>
                @endif
            @endif
        @elseif ($OrderAllCount > $rngSt)
            @if (isset($_GET['orSk']) && $_GET['orSk'] == $rngSt)
                <button class="btn btn-dark shadow-none mb-2" style="width:10%;"><strong>{{$rngSt+1}}-{{$OrderAllCount}}</strong></button>
            @else
                @if(isset($_GET['mo']) && isset($_GET['ye']))
                <?php $mo=$_GET['mo']; $ye=$_GET['ye']; ?>
                <button onclick="location.href = 'statsDash01?orSk={{$rngSt}}&mo={{$mo}}&ye={{$ye}}';" class="btn btn-outline-dark shadow-none mb-2" style="width:10%;"><strong>{{$rngSt+1}}-{{$OrderAllCount}}</strong></button>
                @else
                <button onclick="location.href = 'statsDash01?orSk={{$rngSt}}';" class="btn btn-outline-dark shadow-none mb-2" style="width:10%;"><strong>{{$rngSt+1}}-{{$OrderAllCount}}</strong></button>
                @endif
            @endif
        @else

            <button class="btn btn-default shadow-none mb-2" style="width:10%; color:white;">.</button>
        @endif
    @endforeach

    <button data-toggle="modal" data-target="#otherMonthsStatsMonth" class="btn otherMonthsBtn mb-2" id="othMnthBtn" style="width:18%;"> {{__('adminP.previousMonths')}} </button>
</div>









    <table class="table table-sm" cellspacing="0" width="100%" id="desktopTable">

        <thead class="">
        <tr>
            <th style="opacity:50%;">{{__('adminP.time')}}</th>
            <th style="opacity:50%;">{{__('adminP.table')}}</th>
            <th style="opacity:50%;">{{__('adminP.products')}}</th>
            <th style="opacity:50%;">Bestellt_durch</th>
            <th style="opacity:50%;">{{__('adminP.total')}}CHF</th>
            <th style="opacity:50%;">{{__('adminP.payemntMethod')}}</th>
            <th style="opacity:50%;">{{__('adminP.status')}}</th>
            <th style="opacity:50%;">{{__('adminP.press')}}</th>

            <!-- <th style="opacity:50%;">e-bank</th> -->
        </tr>
        </thead>
        <tbody id="desktopTableBody">
            @if( $showGCSales != Null)
                @foreach ( $showGCSales  as $gcSaleOne)
                    
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
                            @if (User::find($gcSaleOne->soldByStaff) != Null)
                                <p><strong class="pl-2">{{User::find($gcSaleOne->soldByStaff)->name}}</strong></p>
                            @else
                                <p><strong class="pl-2">---</strong></p>
                            @endif
                        </td>
                        <td>
                            <p><strong class="pl-2">{{number_format($gcSaleOne->gcSumInChf, 2, '.', ' ')}}</strong></p>
                        </td>
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
            @endif
            @foreach($showOr as $order)
                <?php
                    $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                ?>
               
                    <tr class="openOrderRow">
                        <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            <?php
                                $or2D = explode(':',explode(' ',$order->created_at)[1]);
                                $dt2D = explode('-',explode(' ',$order->created_at)[0]);
                            ?>
                            <span>{{$dt2D[2]}}.{{$dt2D[1]}}.{{$dt2D[0]}}</span><br>
                            <span>{{$or2D[0]}}:{{$or2D[1]}}</span>
                            <!-- {{explode(' ',$order->created_at)[1]}}<br>
                            <span style="font-size:12px; opacity:60%;">{{explode(' ',$order->created_at)[0]}}</span><br> -->
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
                            @if (User::find($order->servedBy) != Null)
                                <p><strong>{{User::find($order->servedBy)->name}}</strong></p>
                                @if ($order->userPhoneNr != '0770000000')
                                    <strong style="color:darkred;">
                                    Online Bestellung {{$order->userPhoneNr}}
                                    </strong>
                                @endif
                            @else
                                <p><strong>---</strong></p>
                                @if ($order->userPhoneNr != '0770000000')
                                    <strong style="color:darkred;">
                                    Online Bestellung {{$order->userPhoneNr}}
                                    </strong>
                                @endif
                            @endif
                        </td>
                        <td>    
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
                        <td>
                            <p class="mb-1"><strong>{{$order->payM}}</strong></p>
                            @if ($theRefIns != Null)
                                <strong>Saferpay: {{$theRefIns->refPh}}</strong><br>
                            @endif
                        </td>
                        <td>
                            @if($order->statusi == 0)                 
                                <button class="btn btn-warning btn-block" >
                                    {{__('adminP.waut')}}
                                    @if($order->StatusBy!= 999999)
                                        @if(User::find($order->StatusBy) !=null)
                                            {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                        @endif
                                    @endif
                                </button>           
                            @elseif($order->statusi == 1)
                                <button class="btn btn-info btn-block" >
                                   {{__('adminP.confirmed')}}
                                   @if($order->StatusBy!= 999999)
                                        @if(User::find($order->StatusBy) !=null)
                                            {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                        @endif
                                    @endif
                                </button>
                            @elseif($order->statusi == 2)
                                <button class="btn btn-danger btn-block" >
                                    {{__('adminP.canceled')}}
                                    @if($order->StatusBy!= 999999)
                                        @if(User::find($order->StatusBy) !=null)
                                            {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                        @endif
                                    @endif
                                </button>
                            @elseif($order->statusi == 3)
                                <button class="btn btn-success btn-block" >
                                    {{__('adminP.finished')}}
                                    @if($order->StatusBy!= 999999)
                                        @if(User::find($order->StatusBy) !=null)
                                            {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                        @endif
                                    @endif
                                </button>
                            @endif
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
            
        </tbody>
    </table>
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

    $(document).ready(function () {
        $('#desktopTable').DataTable({
            "pagingType": "numbers", // "simple" option for 'Previous' and 'Next' buttons only
            "searching": true, // false to disable search (or any other option)
            "aaSorting": [],columnDefs: [{orderable: false,targets: [1,2,5,6]}],
            
        });
        $('.dataTables_length').addClass('bs-select');
    });

    $("#desktopTable_paginate").on('click', function(event){
        scrollTop: $("#othMnthBtn").offset().top
        alert('yes');
    });

    
  
</script>




















 