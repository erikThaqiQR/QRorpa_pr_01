<?php
    use Illuminate\Support\Facades\Auth;
	use App\accessControllForAdmins;
    use App\giftCard;
    use Carbon\Carbon;
    use App\tabOrderDelete;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Statistiken']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Restorant;
    use App\Orders;
    use App\OrdersPassive;
    use App\billsRecordRes;

    $thisRestaurantId = Auth::user()->sFor;

    $earningMonth = 0;
    $earningToday = 0;
    $earningCancelMonth = 0;
    $taskCompletePerc = 0;
    $taskPending = 0;
    $taskDone = 0 ;
    $totTasks = 0;

    $nowDate = date('Y-m-d');
    $nowMonth = date('m');
    $nowYear = date('Y');

    $Day_1 = date('Y-m-d',strtotime('-1 days',strtotime($nowDate)));
    $Day_2 = date('Y-m-d',strtotime('-2 days',strtotime($nowDate)));
    $Day_3 = date('Y-m-d',strtotime('-3 days',strtotime($nowDate)));
    $Day_4 = date('Y-m-d',strtotime('-4 days',strtotime($nowDate)));
    $Day_5 = date('Y-m-d',strtotime('-5 days',strtotime($nowDate)));


    $earningDay_1 = 0;
    $earningDay_2 = 0;
    $earningDay_3 = 0;
    $earningDay_4 = 0;
    $earningDay_5 = 0;
?>
 <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
 @include('fontawesome')

 <!-- check for orders to move to passive -->
 <script>
    $.ajax({
		url: '{{ route("cleanOrders.checkForCopyOrdersToOrdersPassive") }}',
		method: 'post',
		data: {
			resId: '{{$thisRestaurantId}}',
			_token: '{{csrf_token()}}'
		},
		success: (respo) => {
            respo = $.trim(respo);
            if(respo == 'changesTrue'){
                location.reload();
            }
		},
		error: (error) => { console.log(error); }
	});		
 </script>

@include('words')
@foreach(OrdersPassive::where([['Restaurant', '=', $thisRestaurantId],['servedBy',Auth::User()->id]])->whereIn('nrTable',$myTablesWaiter)->get() as $order)
@if ($order->statusi != 2)
        <?php
            $orderDate = explode(' ',$order->created_at);

            if($Day_1 == $orderDate[0]){
                if ($order->inCashDiscount > 0){
                    $earningDay_1 += number_format($order->shuma - $order->inCashDiscount, 2, '.', '');
                }else if($order->inPercentageDiscount > 0){
                    $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                    $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                    $earningDay_1 += number_format($order->shuma - $theDi, 2, '.', '');
                }else{
                    $earningDay_1 += $order->shuma;
                }
                // $earningDay_1 = number_format($earningDay_1 - $order->dicsountGcAmnt, 2, '.', '');
            }
            if($Day_2 == $orderDate[0]){
                if ($order->inCashDiscount > 0){
                    $earningDay_2 += number_format($order->shuma - $order->inCashDiscount, 2, '.', '');
                }else if($order->inPercentageDiscount > 0){
                    $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                    $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                    $earningDay_2 += number_format($order->shuma - $theDi, 2, '.', '');
                }else{
                    $earningDay_2 += $order->shuma;
                }
                // $earningDay_2 = number_format($earningDay_2 - $order->dicsountGcAmnt, 2, '.', '');
            }
            if($Day_3 == $orderDate[0]){
                if ($order->inCashDiscount > 0){
                    $earningDay_3 += number_format($order->shuma - $order->inCashDiscount, 2, '.', '');
                }else if($order->inPercentageDiscount > 0){
                    $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                    $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                    $earningDay_3 += number_format($order->shuma - $theDi, 2, '.', '');
                }else{
                    $earningDay_3 += $order->shuma;
                }
                // $earningDay_3 = number_format($earningDay_3 - $order->dicsountGcAmnt, 2, '.', '');
            }
            if($Day_4 == $orderDate[0]){
                if ($order->inCashDiscount > 0){
                    $earningDay_4 += number_format($order->shuma - $order->inCashDiscount, 2, '.', '');
                }else if($order->inPercentageDiscount > 0){
                    $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                    $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                    $earningDay_4 += number_format($order->shuma - $theDi, 2, '.', '');
                }else{
                    $earningDay_4 += $order->shuma;
                }
                // $earningDay_4 = number_format($earningDay_4 - $order->dicsountGcAmnt, 2, '.', '');
            }
            if($Day_5 == $orderDate[0]){
                if ($order->inCashDiscount > 0){
                    $earningDay_5 += number_format($order->shuma - $order->inCashDiscount, 2, '.', '');
                }else if($order->inPercentageDiscount > 0){
                    $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                    $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                    $earningDay_5 += number_format($order->shuma - $theDi, 2, '.', '');
                }else{
                    $earningDay_5 += $order->shuma;
                }
                // $earningDay_5 = number_format($earningDay_5 - $order->dicsountGcAmnt, 2, '.', '');
            }

            if($orderDate[0] == $nowDate){
                if ($order->inCashDiscount > 0){
                    $earningToday += number_format($order->shuma - $order->inCashDiscount, 2, '.', '');
                }else if($order->inPercentageDiscount > 0){
                    $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                    $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                    $earningToday += number_format($order->shuma - $theDi, 2, '.', '');
                }else{
                    $earningToday += $order->shuma;
                }
                // $earningToday = number_format($earningToday - $order->dicsountGcAmnt, 2, '.', '');
            }

            $orderDate2D= explode('-',$orderDate[0]);
            if($nowMonth == $orderDate2D[1] && $nowYear == $orderDate2D[0]){
                if ($order->inCashDiscount > 0){
                    $earningMonth += number_format($order->shuma - $order->inCashDiscount, 2, '.', '');
                }else if($order->inPercentageDiscount > 0){
                    $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                    $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                    $earningMonth += number_format($order->shuma - $theDi, 2, '.', '');
                }else{
                    $earningMonth += $order->shuma;
                }
                // $earningMonth = number_format($earningMonth - $order->dicsountGcAmnt, 2, '.', '');
            }
        ?>
 
    @endif
    @if ($order->statusi == '2')
        <?php
            $orderDate = explode(' ',$order->created_at);
            $orderDate2D= explode('-',$orderDate[0]);
            if($nowMonth == $orderDate2D[1] && $nowYear == $orderDate2D[0]){
                if ($order->inCashDiscount > 0){
                    $earningCancelMonth += number_format($order->shuma - $order->inCashDiscount, 2, '.', '');
                }else if($order->inPercentageDiscount > 0){
                    $shumaBef = number_format($order->shuma - $order->tipPer, 2, '.', '');
                    $theDi = number_format($shumaBef * ($order->inPercentageDiscount * 0.01), 2, '.', '');
                    $earningCancelMonth += number_format($order->shuma - $theDi, 2, '.', '');
                }else{
                    $earningCancelMonth += $order->shuma;
                }
                // $earningCancelMonth = number_format($earningCancelMonth - $order->dicsountGcAmnt, 2, '.', '');
            }
        ?>
    @endif

    <?php
        if($orderDate[0] == $nowDate){
            if($order->statusi == 0){
                $taskPending++;
            }
            if($order->statusi == 3){
                $taskDone++;
            }
            $totTasks++;
        }
    ?>
@endforeach

<?php

use App\logOrderPayMChng;
    if($totTasks == 0){
        $done = 0;
    }else{
        $done = sprintf('%01.2f', (($taskDone/$totTasks)*100));
    }
    
    $gcSalesThisDay = number_format(giftCard::where([['soldByStaff',Auth::user()->id],['oldGCtransfer','0']])->whereDate('created_at',$nowDate)->sum('gcSumInChf'), 2, '.', '');
    $gcSalesThisMonth = number_format(giftCard::where([['soldByStaff',Auth::user()->id],['oldGCtransfer','0']])->whereYear('created_at', $nowYear)->whereMonth('created_at', $nowMonth)->sum('gcSumInChf'), 2, '.', '');

    $earningToday = number_format($earningToday + $gcSalesThisDay, 2, '.', '');
    $earningMonth = number_format($earningMonth + $gcSalesThisMonth, 2, '.', '');
?>
<style>
    a.nostyle:link {
    text-decoration: inherit;
    color: inherit;
    cursor: auto;
    }
    a.nostyle:visited {
        text-decoration: inherit;
        color: inherit;
        cursor: auto;
    }
    a.nostyle:hover{
        cursor:pointer;
    }
    .alll{
        background-color:#27beaf;
    }
</style>
<div class="alll">
 <div class="col-12 pb-4" id="porositStat">

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4 pt-3">
                <h1 class="h3 mb-0 text-gray-800 color-white"><strong>{{__('adminP.dashboard')}}</strong></h1>
                <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
            </div>

          <!-- Content Row -->
          <div class="row">

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <a class="nostyle" href="{{route('admWoMng.ordersStatisticsWaiter02')}}">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body text-center">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{__('adminP.salesThisMonth')}}</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45">{{__('adminP.currencyShow')}} </span>{{number_format($earningMonth, 2, '.', '')}}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                

                <!-- Earnings (today) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <a class="nostyle" href="{{route('admWoMng.ordersStatisticsWaiter03')}}">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body text-center">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{__('adminP.salesToday')}}</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45">{{__('adminP.currencyShow')}} </span>{{number_format($earningToday, 2, '.', '')}}</div>
                                    </div>
                                    <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Earnings (Monthly Canceled) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <a class="nostyle" href="{{route('admWoMng.ordersCanceledOrdersWaiter')}}">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body text-center">
                                <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">{{__('adminP.salesCancelMonth')}}</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45">{{__('adminP.currencyShow')}} </span>{{number_format($earningCancelMonth, 2, '.', '')}}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

              
                <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body text-center">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__('adminP.completedToday')}}</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$done}}%</div>
                            </div>
                            <div class="col">
                            <div class="progress progress-sm mr-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{$done}}%"
                                 aria-valuenow="{{$done}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    </div>
                </div>
                </div>

                    <!-- Pending Requests Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body text-center">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{__('adminP.openRequests')}}</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$taskPending}}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <!-- AUSGEBEN - faqja per regjistrimin e faklturave -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <a class="nostyle" href="{{route('admWoMng.statBillsPageWa')}}">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Ausgaben</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45">Aufzeichnungen : </span>
                                                {{billsRecordRes::where([['forRes',Auth::user()->sFor],['fromStaf',Auth::user()->id]])->get()->count()}}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-file-invoice-dollar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-md-12 mb-4">
                        <a class="nostyle" href="{{route('dash.chngPayMethodForOrdersPage')}}">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Zahlungsarten geändert</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45">Instanzen : </span>
                                            {{ logOrderPayMChng::where('toRes',Auth::user()->sFor)->count()}}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa-solid fa-comments-dollar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>


                <div class="row">
                    <!-- Gelöschte Produkte - faqja per paraqitje te instancave te auditimit ne lidhje me fshirjen e produkteve -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <a class="nostyle" href="{{route('admWoMng.statsDeletedTAProdsPage')}}">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Gelöschte Produkte</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45">Instanzen : </span>
                                            @if(Auth::user()->id == 443 || Auth::user()->id == 442)
                                                0
                                            @else
                                                {{tabOrderDelete::where([['toRes',Auth::user()->sFor],['byId',Auth::user()->id]])->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at',Carbon::now()->year)->get()->count()}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa-regular fa-trash-can fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </a>
                    </div>


                </div>

                    @if(accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Heute_Verkaufe']])->first() != NULL 
                    && accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Heute_Verkaufe']])->first()->accessValid == 1)
                    <div class="row ">
                    <!-- Sales statistics -->
                        <div class="col-xl-12 col-md-12 col-12 text-center">
                            <a class="nostyle" href="{{route('waSalesToday.waSalesTodayPage')}}">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                                    <strong>heutige Verkäufe <i class="ml-4 fa-solid fa-hand-holding-dollar"></i></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endif
                    @if(accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','RechnungMngAcce']])->first() != NULL 
                    && accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','RechnungMngAcce']])->first()->accessValid == 1)
                    <div class="row mt-2">
                        <!-- Sales statistics -->
                        <div class="col-xl-12 col-md-12 col-12 text-center">
                            <a class="nostyle" href="{{route('admWoMng.adminWoRechnungPageWaiter')}}">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                                    <strong>Rechnungsverwaltung  <i class="ml-4 fa-solid fa-file-invoice"></i></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endif


                    
                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <a class="nostyle" href="{{route('admWoMng.openCheckInOutReportsWa')}}">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                                <strong>Check-in/out Berichte  <i class="ml-4 fa-solid fa-check-to-slot"></i></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>



                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div style="font-size:16px;" class="font-weight-bold text-dark text-uppercase mb-1">
                                            <strong>
                                                {{Form::open(['action' => 'AdminPanelController@generateMostSalesPDF', 'method' => 'post', 'id' => 'generateMostSalesPDFForm']) }}
                                                <span >Die meisten Verkäufe </span> <br>

                                                <label class="mb-2 mt-2" for="DateStartMostSales">Start:</label>
                                                <input type="date" class="mb-2 mt-2" style="border:1px solid rgb(72,81,87); border-radius:4px;" id="DateStartMostSales" name="DateStartMostSales">
                                                <br>
                                                <label for="DateEndMostSales">Ende:</label>
                                                <input type="date" style="border:1px solid rgb(72,81,87); border-radius:4px;" id="DateEndMostSales" name="DateEndMostSales">
                                                
                                                <button class="pl-2 pr-2 mt-2 btn btn-outline-dark shadow-none" type="button" style="width:100%; height:40px;" onclick="generateMostSales()"><strong>Erzeugen</strong></button>
                                                {{Form::close() }}
                                            </strong>
                                        </div>
                                        <div class="alert alert-danger" id="generateMostSalesError01" style="display:none;">
                                            <strong>Bitte zuerst das Startdatum eingeben!</strong>
                                        </div>
                                        <div class="alert alert-danger" id="generateMostSalesError02" style="display:none;">
                                            <strong>Bitte geben Sie zuerst das Enddatum ein!</strong>
                                        </div>
                                        <div class="alert alert-danger" id="generateMostSalesError03" style="display:none;">
                                            <strong>Das Startdatum sollte vor dem Enddatum liegen, schauen Sie noch einmal vorbei!</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             

               

               
            </div>
            </div>






















                 <div class="mb-4 ml-2 mt-4">

                 <div class="text-center color-white">
                     <h3>{{__('adminP.salesDuring5Days')}}</h3>
                 </div>
                
                    <div class="chart-area">
                        <canvas id="myArea"></canvas>
                    </div>
               
                </div>

                <input type="hidden" id="D0" value="{{$earningToday}}">
                <input type="hidden" id="D1" value="{{$earningDay_1}}">
                <input type="hidden" id="D2" value="{{$earningDay_2}}">
                <input type="hidden" id="D3" value="{{$earningDay_3}}">
                <input type="hidden" id="D4" value="{{$earningDay_4}}">
          

                <input type="hidden" id="Day0" value="{{$nowDate}}">
                <input type="hidden" id="Day1" value="{{$Day_1}}">
                <input type="hidden" id="Day2" value="{{$Day_2}}">
                <input type="hidden" id="Day3" value="{{$Day_3}}">
                <input type="hidden" id="Day4" value="{{$Day_4}}">
                




            

</div>


</div>




<script>

    // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#FFF';

            function number_format(number, decimals, dec_point, thousands_sep) {
                  
                    number = (number + '').replace(',', '').replace(' ', '');
                    var n = !isFinite(+number) ? 0 : +number,
                        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                        s = '',
                        toFixedFix = function(n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                        };
                    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                    if (s[0].length > 3) {
                        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                    }
                    if ((s[1] || '').length < prec) {
                        s[1] = s[1] || '';
                        s[1] += new Array(prec - s[1].length + 1).join('0');
                    }
                    return s.join(dec);
            }

            // Area Chart Example
            var ctx = document.getElementById("myArea");
            var D0 = document.getElementById("D0").value;
            var D1 = document.getElementById("D1").value;
            var D2 = document.getElementById("D2").value;
            var D3 = document.getElementById("D3").value;
            var D4 = document.getElementById("D4").value;
           
            

            var Day0 = document.getElementById("Day0").value;
            var Day1 = document.getElementById("Day1").value;
            var Day2 = document.getElementById("Day2").value;
            var Day3 = document.getElementById("Day3").value;
            var Day4 = document.getElementById("Day4").value;
        
        


            var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [Day4, Day3, Day2, Day1, Day0],
                datasets: [{
                label: $("#earnings").val(),
                lineTension: 0.3,
                backgroundColor: "rgba(0, 0, 0, 0)",
                borderColor: "rgba(255, 255, 255, 255)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(0, 0, 0, 0)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(0, 0, 0, 0)",
                pointHoverBorderColor: "rgba(0, 0, 0, 0)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: [D4, D3, D2, D1, D0],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 25
                }
                },
                scales: {
                xAxes: [{
                    time: {
                    unit: 'text'
                    },
                    gridLines: {
                    display: false,
                    drawBorder: false
                    },
                    ticks: {
                    maxTicksLimit: 10
                    }
                }],
                yAxes: [{
                    ticks: {
                    maxTicksLimit: 4,
                    padding: 10,
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return $("#currencyShow").val()+ ' ' + number_format(value);
                    }
                    },
                    gridLines: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                    }
                }],
                },
                legend: {
                display: false
                },
                tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                    return datasetLabel + ':' +$("#currencyShow").val()+ '' + number_format(tooltipItem.yLabel);
                    }
                }
                }
            }
            });



            function generateMostSales(){
                if(!$('#DateStartMostSales').val()){
                    if($('#generateMostSalesError01').is(':hidden')){ $('#generateMostSalesError01').show(100).delay(4000).hide(100); }
                }else if(!$('#DateEndMostSales').val()){
                    if($('#generateMostSalesError02').is(':hidden')){ $('#generateMostSalesError02').show(100).delay(4000).hide(100); }
                }else if($('#DateStartMostSales').val() >= $('#DateEndMostSales').val()){
                    if($('#generateMostSalesError03').is(':hidden')){ $('#generateMostSalesError03').show(100).delay(4000).hide(100); }
                }else{
                    // $('#generateMostSalesPDFForm').submit();
                    window.location.href = "https://qrorpa.ch/generateMostSalesPDF/?dtS="+$('#DateStartMostSales').val()+"&dtE="+$('#DateEndMostSales').val();
                }
            }

</script>