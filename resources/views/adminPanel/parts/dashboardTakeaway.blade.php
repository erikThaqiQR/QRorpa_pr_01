<?php
    use App\accessControllForAdmins;
use App\User;
use App\Takeaway;
use App\payTecPair;

    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Takeaway']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Orders;
    use App\StatusWorker;
    use App\taDeForCookOr;
    use App\Restorant;
    use App\onlinePayQRCStaf;
    use App\OPSaferpayReference;
    use Carbon\Carbon;
    use App\ordersTaOnlinePayStafTemp;

    use Illuminate\Support\Facades\Auth;
    use SebastianBergmann\CodeCoverage\Report\PHP;

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
        border-bottom:1px solid lightgray;

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

    .table th, .table td {
        padding: 0.25rem !important;
    }
</style>

<section class="pl-4 pr-4 pb-5">
    <hr>
    <div class="d-flex justify-content-between">
        <h2 class="color-qrorpa pl-4" style="width:50%;">
            <strong>{{__('adminP.takeawayB')}}</strong>  
        </h2>
        <button style="width:18%;" data-toggle="modal" data-target="#PosPairModal" class="btn btn-outline-secondary shadow-none" type="button">PayTec POS</button>
        <button class="btn btn-dark shadow-none" style="width:28%;" data-toggle="modal" data-target="#addOrForTAModal">
            <strong><i class="fa-solid fa-plus mr-3"></i> Registrieren Sie eine Bestellung</strong>
        </button>

        @include('adminPanel.taTempProd.dashboardTakeawayNewOrd')
        @include('adminPanel.tablePage.tableIndexNotifications')
    </div>

    <hr>

    <!-- unfinished orders -->
    <div class="alert alert-info text-center" id="taOrderAutoConfirmAler01" style="width:100%; font-size:1.5rem; display:none;">
        <strong>Die in der Bestellung enthaltenen Produkte scheinen nicht gekocht zu werden, daher wird sie automatisch als BEZAHLT bestätigt</strong>
    </div>
    <div id="desktopTableAll">

    @if(onlinePayQRCStaf::where([['resId', Auth::user()->sFor],['tableNr',500],['status',0]])->whereBetween('created_at', [$today_str, $today_end])->count() >0)
        <!-- ONLINE PAY FOR THE TAKEAWAY ORDERS  -->
        <div class="d-flex justify-content-between">
            <h3 style="color:rgb(39,190,175); width:100%;"><strong>Bestellungen zur Online-Zahlung initiiert</strong></h3>
        </div>
        <table class="table table-hover" id="desktopTable">
                <thead>
                <tr>
                    <th style="opacity:50%;">{{__('adminP.time')}}</th>
                    <th style="opacity:50%;">{{__('adminP.products')}}</th>
                    <th class="text-center" style="opacity:50%;">{{__('adminP.total')}}</th>
                    <th class="text-center" style="opacity:50%;"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach(onlinePayQRCStaf::where([['resId', Auth::user()->sFor],['tableNr',500],['status',0]])->whereBetween('created_at', [$today_str, $today_end])
                    ->get()->sortByDesc('created_at') as $opQRCStaf)
                       
                        <tr class="openOrderRow" >
                            <td>
                                @php
                                    $hrDate2d = explode('-',explode(' ',$opQRCStaf->created_at)[0]);
                                    $hrTime2d = explode(':',explode(' ',$opQRCStaf->created_at)[1]);
                                @endphp
                                <span style="font-size:12px; opacity:70%;">{{$hrTime2d[0]}}:{{$hrTime2d[1]}} 
                                <br> {{$hrDate2d[2]}}.{{$hrDate2d[1]}}.{{$hrDate2d[0]}}</span>
                            </td>
                            <td>
                                @foreach (ordersTaOnlinePayStafTemp::where('onlinePayRef',$opQRCStaf->id)->get() as $otaonpayProd)
                                    <p style="margin:2px;"><strong>{{$otaonpayProd->proSasia}}X</strong> {{$otaonpayProd->taProdName}}
                                        @if ($otaonpayProd->proType != '' && $otaonpayProd->proType!= 'empty')
                                            <span style="opacity:0.7;">( {{$otaonpayProd->proType}} )</span>
                                        @endif 
                                    </p>
                                @endforeach
                              
                            </td>
                            <td class="pt-4">
                                @if ($opQRCStaf->cashDis > 0 )
                                <p class="text-center pt-4" style="font-size:1.1rem; margin-bottom:3px;">{{number_format($opQRCStaf->totPay - $opQRCStaf->cashDis, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;">( Rabat : {{number_format($opQRCStaf->cashDis, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup> )</p>
                                @elseif ($opQRCStaf->percDis > 0 )
                                <?php 
                                    $percOf = number_format($opQRCStaf->percDis / 100, 4, '.',''); 
                                    $rab = number_format($opQRCStaf->totPay * $percOf, 4, '.',''); 
                                ?>
                                <p class="text-center pt-4" style="font-size:1.1rem; margin-bottom:3px;">{{number_format($opQRCStaf->totPay - $rab, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;">( Rabat : {{number_format($rab, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup> )</p>
                                @else
                                <p class="text-center pt-4" style="font-size:1.1rem; margin-bottom:3px;">{{number_format($opQRCStaf->totPay, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                                @endif
                            </td>
                            <td><i class="pt-4 fa-solid fa-3x fa-qrcode" onclick="showQRCForPayAllProdsOnline('{{$opQRCStaf->qrCodeTP}}')"></i></td>
                        </tr>
                    @endforeach

                </tbody>
        </table>
    @endif




    @if(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500]])->whereBetween('created_at', [$today_str, $today_end])->limit(10)->count() > 0)
        <hr>
        <div class="d-flex justify-content-between">
            <h3 style="color:rgb(39,190,175); width:43.5%;"><strong>{{__('adminP.uncompletedOrders')}}</strong></h3>
            <button class="btn btn-warning shadow-none" style="width: 14%;" >{{__('adminP.wait')}}</button>
            <button class="btn btn-info shadow-none" style="width: 14%;" >Abholbereit</button>
            <button class="btn btn-danger shadow-none" style="width: 14%;">{{__('adminP.canceled')}}</button>
            <button class="btn btn-success shadow-none" style="width: 14%;">{{__('adminP.finished')}}</button>
        </div>
        
        @if(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi', '<',2]])->whereBetween('created_at', [$today_str, $today_end])->limit(10)->count() > 0)
        <table class="table table-hover" id="desktopTable">
            <thead>
            <tr>
                <th style="opacity:50%;">{{__('adminP.time')}}</th>
                <th style="opacity:50%;">{{__('adminP.products')}}</th>
                <th class="text-center" style="opacity:50%;">{{__('adminP.total')}}/Code</th>
                <th class="text-center" style="opacity:50%;"></th>
                @if ($theRes->takeawayCashPosOrders == 1)
                <th class="text-center" style="opacity:50%;"></th>
                @endif
                @if(Auth::user()->ehcchurworker == 0)
                <th class="text-center" style="opacity:50%;">PDF</th>
                @endif
            </tr>
            </thead>
            <tbody>
                @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi', '<',2]])->whereBetween('created_at', [$today_str, $today_end])
                ->get()->sortByDesc('created_at') as $order)
                    <?php
                        $orderDate2D= explode('-',explode(' ',$order->created_at)[0]); 
                    ?>
                
                        <tr class="openOrderRow" id="taOrderRow{{$order->id}}">
                            <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                                <?php
                                    $isReady = 1;
                                    foreach(taDeForCookOr::where('orderId',$order->id)->get() as $orOneP){
                                        if((int)$orOneP->prodSasia != (int)$orOneP->prodSasiaDone){ $isReady = 0; }
                                    }
                                    $hrDate2d = explode('-',explode(' ',$order->created_at)[0]);
                                    $hrTime2d = explode(':',explode(' ',$order->created_at)[1]);

                                    $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                                ?>
                                @if ($isReady == 1)
                                    <button class="btn btn-block btn-success"><strong>Bereit</strong></button>
                                @else
                                    <button class="btn btn-block btn-danger"><strong>Nicht bereit</strong></button>
                                @endif

                                @if ($theRefIns != Null)
                                    <strong>Saferpay: {{$theRefIns->refPh}}</strong><br>
                                @endif
                                <span style="font-size:12px; opacity:60%;">{{$hrTime2d[0]}}:{{$hrTime2d[1]}} / {{$hrDate2d[2]}}.{{$hrDate2d[1]}}.{{$hrDate2d[0]}}</span><br>
                                @if ($order->TAtime != 'empty' && $order->TAtime != '')
                                <span style="font-size:14px; opacity:95%;"><strong>Abholen: {{$order->TAtime }}</strong></span>
                                @endif
                              
                            </td>

                            <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                                @foreach (explode('---8---',$order->porosia) as $thisProd)
                                    @php
                                        $thisProd2D = explode('-8-',$thisProd);
                                    @endphp
                                    <p style="margin:2px;"><strong>{{$thisProd2D[3]}}X</strong> {{$thisProd2D[0]}} 
                                    @if ($thisProd2D[1] != '' && $thisProd2D[1] != 'empty')
                                        <span style="opacity:0.7;">( {{$thisProd2D[1]}} )</span>
                                    @endif
                                        
                                    </p>
                                    @if ($thisProd2D[6] != '' && $thisProd2D[6] != 'empty')
                                        <p style="color:red; margin:0;"><strong>Kommentar: {{$thisProd2D[6]}}</strong></p>
                                    @endif
                                @endforeach
                                @if ($order->cuponOffVal > 0)
                                    <hr style="margin:2px;">
                                    <p style="margin:3px; font-size:1.2rem;"><strong>Coupon: (- {{number_format($order->cuponOffVal,2,'.','')}} CHF) </strong></p>
                                @elseif ($order->cuponProduct != 'empty')
                                    <hr style="margin:2px;">
                                    <p style="margin:3px; font-size:1.2rem;"><strong>Coupon: <span style="font-size: 0.75rem;">Gratisprodukt</span> {{$order->cuponProduct}}</strong></p>
                                @endif
                              
                            </td>
                            <td data-toggle="modal" data-target="#openOrder{{$order->id}}">
                                @if ($order->inCashDiscount > 0 )
                                <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;"><span id="payShumaShow{{$order->id}}">{{number_format($order->shuma - $order->inCashDiscount - $order->dicsountGcAmnt, 2, '.','')}}</span><sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;">( Rabat:{{number_format($order->inCashDiscount, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup>)</p>
                                @elseif ($order->inPercentageDiscount > 0 )
                                <?php 
                                    $totBe = number_format($order->shuma - $order->tipPer, 4, '.',''); 
                                    $percOf = number_format($order->inPercentageDiscount / 100, 4, '.',''); 
                                    $rab = number_format($totBe * $percOf, 4, '.',''); 
                                ?>

                                <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;"><span id="payShumaShow{{$order->id}}">{{number_format($order->shuma - $rab - $order->dicsountGcAmnt, 2, '.','')}}</span><sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;">( Rabat:{{number_format($rab, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup>)</p>
                                @else
                                <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;"><span id="payShumaShow{{$order->id}}">{{number_format($order->shuma - $order->dicsountGcAmnt, 2, '.','')}}</span><sup>{{__('adminP.currencyShow')}}</sup></p>
                                @endif

                                @if($order->dicsountGcAmnt > 0)
                                <p class="text-center" style="font-size:0.8rem; margin-bottom:3px;">GK:-{{number_format($order->dicsountGcAmnt, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                                @endif

                                <p class="text-center" style="color:rgb(39,190,175); margin:0px; padding:0px 10px 0px 10px; font-size:1.2rem;"><strong>{{explode('|',$order->shifra)[1]}}</strong></p>
                                
                            </td>
                            <td style="width:200px;" class="text-center d-flex flex-wrap justify-content-between">
                                <p class="pt-1" style="width: 69.5%;"  data-toggle="modal" data-target="#openOrder{{$order->id}}"><strong>{{$order->payM}}</strong></p>

                                @if(Auth::user()->ehcchurworker == 1)
                                    @if($order->statusi == 0)
                                        <button style="width: 29.5%;;" class="btn btn-warning btn-block">
                                            <!-- {{__('adminP.wait')}} -->
                                        </button>
                                    @elseif ($order->statusi == 3)
                                        <button style="width: 29.5%;;" class="btn btn-success btn-block">
                                            <!-- {{__('adminP.finished')}} -->
                                        </button>
                                    @endif
                                @else
                                    @if($order->statusi == 0)
                                        <button style="width: 29.5%;;" class="btn btn-warning btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">
                                            <!-- {{__('adminP.wait')}} -->
                                        </button>   
                                    @elseif($order->statusi == 1)
                                        <button style="width: 29.5%;;" class="btn btn-info btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">
                                        <!-- {{__('adminP.confirmed')}} -->
                                        </button>
                                    @endif
                                @endif

                                @if ($order->statusi == 0)
                                <button disabled id="chngStatBnt0{{$order->id}}" style="width: 40px; height:30px;" class="shadow-none btn btn-warning btn-block"></button>
                                @else
                                <button onclick="taProdChngStatus('{{$order->id}}','0')" id="chngStatBnt0{{$order->id}}" style="width: 40px; height:30px;" class="shadow-none btn btn-warning btn-block"></button>
                                @endif
                                
                                @if ($order->statusi == 1)
                                <button disabled id="chngStatBnt1{{$order->id}}" style="width: 40px; height:30px;" class="shadow-none btn btn-info btn-block"></button>
                                @else
                                <button onclick="taProdChngStatus('{{$order->id}}','1')" id="chngStatBnt1{{$order->id}}" style="width: 40px; height:30px;" class="shadow-none btn btn-info btn-block"></button>
                                @endif
                                
                                <button onclick="showCommOrCancel('{{$order->id}}')" id="chngStatBnt2{{$order->id}}" style="width: 40px; height:30px;" class="shadow-none btn btn-danger btn-block"></button>
                                <button onclick="taProdChngStatus('{{$order->id}}','3')" id="chngStatBnt3{{$order->id}}" style="width: 40px; height:30px;" class="shadow-none btn btn-success btn-block"></button>

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
                            </td>
                            @if ($theRes->takeawayCashPosOrders == 1)
                            <td style="width:fit-content;">
                                <button class="btn btn-outline-success shadow-none" style="width: 100%;" id="payTAByPOSBtn{{$order->id}}" onclick="payTAByPOS('{{$order->id}}')">
                                    <strong>Karte</strong>
                                </button>
                            </td>
                            @endif
                            @if(Auth::user()->ehcchurworker == 0)
                            <!-- <td class="text-center"><a href="generatePDF/{{$order->id}}"><i class="fa fa-file-pdf-o fa-2x"></i></a></td> -->
                            <td>
                                <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                    {{ csrf_field()}}
                                    <input type="hidden" value="{{$order->id}}" name="orId">
                                    <button type="submit" class="btn"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                                </form>
                            </td>

                            @endif
                        </tr>
                    

                @endforeach
                
            </tbody>
        </table>
        @else
            <p style="font-size: 23px; color:rgb(39,190,175);">{{__('adminP.noUnfinishedOrders')}}</p>
        @endif





        <button style="background-color: rgb(39,190,175); color:white; width:100%; border-radius:25px; border:none; font-size:22px;" class="p-2" id="desktopTable2Button"
            onclick="showTheFinishedOrders()">
            <strong><i class="fas fa-arrow-down"></i> {{__('adminP.viewCompletedOrders')}} <i class="fas fa-arrow-down"></i></strong>
        </button>
    
        <!-- finished orders -->
        <h3 id="desktopTable2Header" style="display:none; color:rgb(39,190,175);" class="mt-2"><strong>{{__('adminP.finishedOrders')}}</strong></h3>
        @if(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi','>=',2]])->whereBetween('created_at', [$today_str, $today_end])->limit(10)->count() > 0)
        <table class="table table-hover" style="display:none;" id="desktopTable2">
        
            <thead>
            <tr>
                <th style="opacity:50%;">{{__('adminP.time')}}</th>
                <th style="opacity:50%;">{{__('adminP.porducts')}}</th>
                <th style="opacity:50%;">{{__('adminP.total')}}</th>
                <th class="text-center" style="opacity:50%;"><i class="fas fa-cash-register"></i></th>
                <th class="text-center">{{__('adminP.code')}}</th>
                <th class="text-center" style="opacity:50%;">{{__('adminP.status')}}</th>
                @if(Auth::user()->ehcchurworker == 0)
                <th class="text-center" style="opacity:50%;"></th>
                <th class="text-center" style="opacity:50%;"></th>
                @endif
            </tr>
            </thead>
            <tbody>
                @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',500],['statusi','>=',2]])->whereBetween('created_at', [$today_str, $today_end])
                ->limit(10)->get()->sortByDesc('created_at') as $order)
                    <?php
                        $orderDate2D= explode('-',explode(' ',$order->created_at)[0]); 
                        $hrDate2d = explode('-',explode(' ',$order->created_at)[0]);
                        $hrTime2d = explode(':',explode(' ',$order->created_at)[1]);

                        $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                    ?>
                
                        <tr class="openOrderRow">
                            <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                                @if ($theRefIns != Null)
                                    <strong>Saferpay: {{$theRefIns->refPh}}</strong><br>
                                @endif
                                <span style="font-size:12px; opacity:60%;">{{$hrTime2d[0]}}:{{$hrTime2d[1]}} / {{$hrDate2d[2]}}.{{$hrDate2d[1]}}.{{$hrDate2d[0]}}</span><br>
                            </td>

                            <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                                @foreach (explode('---8---',$order->porosia) as $thisProd)
                                    @php
                                        $thisProd2D = explode('-8-',$thisProd);
                                    @endphp
                                    <p style="margin:2px;"><strong>{{$thisProd2D[3]}}X</strong> {{$thisProd2D[0]}} 
                                    @if ($thisProd2D[1] != '' && $thisProd2D[1] != 'empty')
                                        <span style="opacity:0.7;">( {{$thisProd2D[1]}} )</span>
                                    @endif
                                    </p>
                                    @if ($thisProd2D[6] != '' && $thisProd2D[6] != 'empty')
                                    <p style="color:red; margin:0px;"><strong>Kommentar: {{$thisProd2D[6]}}</strong></p>
                                    @endif
                                @endforeach
                                @if ($order->cuponOffVal > 0)
                                    <hr style="margin:2px;">
                                    <p style="margin:3px; font-size:1.2rem;"><strong>Coupon: (- {{number_format($order->cuponOffVal,2,'.','')}} CHF) </strong></p>
                                @elseif ($order->cuponProduct != 'empty')
                                    <hr style="margin:2px;">
                                    <p style="margin:3px; font-size:1.2rem;"><strong>Coupon: <span style="font-size: 0.75rem;">Gratisprodukt</span> {{$order->cuponProduct}}</strong></p>
                                @endif
                               
                            </td>
                            <td data-toggle="modal" data-target="#openOrder{{$order->id}}">

                                @if ($order->inCashDiscount > 0 )
                                <p style="margin-bottom:3px;">{{number_format($order->shuma - $order->inCashDiscount - $order->dicsountGcAmnt, 2, ',','')}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p style="margin-bottom:3px;">( Rabat:{{number_format($order->inCashDiscount, 2, ',','')}} <sup>{{__('adminP.currencyShow')}}</sup>)</p>
                                @elseif ($order->inPercentageDiscount > 0 )
                                <?php 
                                    $totBe = number_format($order->shuma - $order->tipPer, 4, '.',''); 
                                    $percOf = number_format($order->inPercentageDiscount / 100, 4, '.',''); 
                                    $rab = number_format($totBe * $percOf, 4, '.',''); 
                                ?>
                                <p style="margin-bottom:3px;">{{number_format($order->shuma - $rab - $order->dicsountGcAmnt, 2, '.','')}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p style="margin-bottom:3px;">( Rabat:{{number_format($rab, 2, '.','')}} <sup>{{__('adminP.currencyShow')}}</sup>)</p>
                                @else
                                <p style="margin-bottom:3px;">{{number_format($order->shuma - $order->dicsountGcAmnt, 2, ',','')}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                @endif

                                @if($order->dicsountGcAmnt > 0)
                                <p class="text-center" style="font-size:0.8rem; margin-bottom:3px;">GK:-{{number_format($order->dicsountGcAmnt, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                                @endif
                            </td>
                            <td class="text-center" data-toggle="modal" data-target="#openOrder{{$order->id}}">
                                <p>{{$order->payM}}</p>
                            </td>
                            <td class="text-center">
                                <p style="color:rgb(39,190,175); margin-bottom:3px;"><strong>{{explode('|',$order->shifra)[1]}}</strong></p>
                            </td>
                            <td>
                                @if(Auth::user()->ehcchurworker == 1)
                                    @if($order->statusi == 0)
                                        {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                            {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                            {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                            {{ Form::hidden('backToMap', 4 , ['class' => 'form-control']) }}
                                            {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                            {{ Form::submit(__('adminP.wait'), ['class' => 'form-control btn btn-warning btn-block']) }}
                                        
                                        {{Form::close() }}
                                    @elseif ($order->statusi == 3)
                                        <button class="btn btn-success btn-block">
                                            {{__('adminP.finished')}}
                                        </button>
                                    @endif
                                @else
                                    @if($order->statusi == 2)
                                        <button class="btn btn-danger btn-block" disabled>{{__('adminP.canceled')}} <br>
                                            @if($order->StatusBy!= 999999)
                                                @if(User::find($order->StatusBy) !=null)
                                                    {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                                @endif
                                            @endif
                                        </button>
                                    @elseif($order->statusi == 3)
                                        <button class="btn btn-success btn-block">
                                            {{__('adminP.finished')}}
                                        </button>
                                    @endif
                                @endif
                            </td>
                            @if(Auth::user()->ehcchurworker == 0)
                            <!-- <td class="text-center"><a href="generatePDF/{{$order->id}}"><i class="fa fa-file-pdf-o fa-2x"></i></a></td> -->
                            <td>
                                <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                    {{ csrf_field()}}
                                    <input type="hidden" value="{{$order->id}}" name="orId">
                                    <button type="submit" class="btn shadow-none"> <i class="fa fa-file-pdf-o fa-2x"></i> </button>
                                </form>
                            </td>
                                @if($order->statusi == 3)
                                <td>
                                    <button type="button" onclick="showBillQR('{{$order->id}}')" class="btn shadow-none"> <i class="fa-solid fa-qrcode fa-2x"></i> </button>
                                </td>
                                @endif
                            @endif
                        </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="font-size: 23px; color:rgb(39,190,175);"> {{__('adminP.noReadyOrdersForMoment')}}</p>
        @endif

        <script>
            function showTheFinishedOrders(){
                $('#desktopTable2Header').show();
                $('#desktopTable2').show();
                $('#desktopTable2Button').html('<strong><i class="fas fa-arrow-up"></i> {{__("adminP.hideFinishedOrders")}} <i class="fas fa-arrow-up"></i></strong>');
                $('#desktopTable2Button').attr('onclick','hideTheFinishedOrders()');
            }

            function hideTheFinishedOrders(){
                $('#desktopTable2Header').hide();
                $('#desktopTable2').hide();
                $('#desktopTable2Button').html('<strong><i class="fas fa-arrow-down"></i> {{__("adminP.viewCompletedOrders")}} <i class="fas fa-arrow-down"></i></strong>');
                $('#desktopTable2Button').attr('onclick','showTheFinishedOrders()');
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
        <!-- $order->dicsountGcAmnt -->
            <!-- The Modal -->
            <div class="modal" id="openOrder{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" style="border-radius:20px; border:3px solid rgb(39,190,175);">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title color-qrorpa">
                                @if($order->Restaurant == 22 || $order->Restaurant == 23)
                                    <strong>{{__('adminP.toTakeawayFor')}} : {{explode('|',$order->shifra)[1]}}</strong> 
                                @else
                                    @if ($order->TAemri != 'empty')
                                    <strong>{{__('adminP.toTakeawayFor')}} : {{$order->TAemri}} {{$order->TAmbiemri}}</strong> 
                                    @else
                                    <strong>{{__('adminP.toTakeawayFor')}} : das Personal</strong> 
                                    @endif
                                @endif
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">X</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body d-flex flex-wrap justify-content-between" >
                            @if ($order->userPhoneNr == '0770000000')
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;"><strong>Kundentelefonnr: das Personal</strong></p>
                            @else
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;"><strong> Kundentelefonnr: {{$order->userPhoneNr }}</strong></p>
                            @endif
                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                <strong> 
                                    Code: <span style="color: rgb(39,190,175);">{{explode('|',$order->shifra)[1] }}</span> 
                                    @if ($order->TAbowlingLine != Null && $order->TAbowlingLine != -1)
                                        / <i class="fa-solid fa-bowling-ball mr-1"></i>: {{$order->TAbowlingLine}}
                                    @endif
                                </strong>
                            </p>
                            @if($order->inCashDiscount > 0)
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($order->shuma-$order->inCashDiscount - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>

                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:3px;">
                                    <strong>Skonto durch das Personal : {{number_format($order->inCashDiscount,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Rabattkommentar : {{$order->discReason}}</strong>
                                </p>
                            @elseif ($order->inPercentageDiscount > 0)
                                <?php
                                    $totBe = number_format($order->shuma - $order->tipPer, 4, '.',''); 
                                    $prct = number_format($order->inPercentageDiscount/100,4,'.','');
                                    $offVal = number_format($totBe*$prct,4,'.','');
                                    $newTotal = number_format($order->shuma-$offVal,4,'.','');
                                ?>
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($newTotal - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:3px;">
                                    <strong>prozentualer Rabatt durch das Personal : ({{number_format($order->inPercentageDiscount,2,'.','')}} % ) {{number_format($offVal,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Rabattkommentar : {{$order->discReason}}</strong>
                                </p>
                            @else
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($order->shuma - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>
                            @endif

                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                <strong>Tinkergeld: {{number_format($order->tipPer,2,'.','')}} CHF </strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                <strong>Rabatt per Gutschein: {{number_format($order->cuponOffVal,2,'.','')}} CHF </strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                @if ($order->cuponProduct == 'empty')
                                    <strong>Produkt aus Gutschein: ---</strong>
                                @else
                                    <strong>Produkt aus Gutschein: {{$order->cuponProduct}}</strong>
                                @endif
                            </p>

                            <hr style="width: 100%; margin-top: 5px; margin-bottom:5px;">

                            <p class="text-center" style="font-size:1.2rem; width:10%; margin-bottom:5px;">
                                <strong>Menge</strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:48.8%; margin-bottom:5px;">
                                <strong>Produkt</strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:20%; margin-bottom:5px;">
                                <strong>Preis</strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:20%; margin-bottom:5px;">
                                <strong>Status</strong>
                            </p>

                            @foreach(explode('---8---',$order->porosia) as $produkti)
                                <?php
                                    $prod = explode('-8-', $produkti);
                                ?>
                                <p class="text-center" style="font-size:1rem; width:10%; margin-bottom:3px; border-bottom:1px solid rgb(72,81,87)">
                                    <strong>{{$prod[3]}} X</strong>
                                </p>
                                <p class="text-left pl-2" style="font-size:1rem; width:50%; margin-bottom:3px; border-bottom:1px solid rgb(72,81,87)">
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
                                <p class="text-center" style="font-size:1rem; width:20%; margin-bottom:3px; border-bottom:1px solid rgb(72,81,87)">
                                    <strong>{{number_format($prod[4],2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center openOrderStatuss" style="font-size:1rem; width:20%; margin-bottom:3px; border-bottom:1px solid rgb(72,81,87)">
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
                                                <button class="btn btn-success shadow-none btn-block"><strong>Bereit</strong></button>
                                            @else
                                                <button class="btn btn-danger shadow-none btn-block"><strong>Nicht Bereit</strong></button>
                                            @endif
                                        @endif
                                    @endif
                                    
                                </p>
                            @endforeach

                            <hr style="width: 100%; margin-top: 5px; margin-bottom:5px;">

                            <?php
                                $dtFOr = explode('-',explode(' ',$order->created_at)[0]);
                                $hrFOr = explode(':',explode(' ',$order->created_at)[1]);
                            ?>
                            <p class="text-center" style="font-size:1rem; width:49%; margin-bottom:5px;">
                                <strong>{{$dtFOr[2]}}.{{$dtFOr[1]}}.{{$dtFOr[0]}} <span style="opacity: 0.6; margin-left:15px;">{{$hrFOr[0]}}:{{$hrFOr[1]}}</span></strong>
                            </p>
                            <p class="text-center" style="font-size:1rem; width:49%; margin-bottom:5px;">
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
            ->limit(10)->get() as $order)
        <?php
            $orderDate2D = explode(' ', $order->created_at);
            $orderDate2DM= explode('-',explode(' ',$order->created_at)[0]); 
            
        ?>
            <!-- The Modal -->
            <div class="modal" id="openOrder{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" style="border-radius:20px; border:3px solid rgb(39,190,175);">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title color-qrorpa">
                                @if($order->Restaurant == 22 || $order->Restaurant == 23)
                                    <strong>{{__('adminP.toTakeawayFor')}} : {{explode('|',$order->shifra)[1]}}</strong> 
                                @else
                                    @if ($order->TAemri != 'empty')
                                    <strong>{{__('adminP.toTakeawayFor')}} : {{$order->TAemri}} {{$order->TAmbiemri}}</strong> 
                                    @else
                                    <strong>{{__('adminP.toTakeawayFor')}} : das Personal</strong> 
                                    @endif
                                @endif
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">X</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body d-flex flex-wrap justify-content-between" >
                            @if ($order->userPhoneNr == '0770000000')
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;"><strong>Kundentelefonnr: das Personal</strong></p>
                            @else
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;"><strong> Kundentelefonnr: {{$order->userPhoneNr }}</strong></p>
                            @endif
                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                <strong> 
                                    Code: <span style="color: rgb(39,190,175);">{{explode('|',$order->shifra)[1] }}</span>
                                    @if ($order->TAbowlingLine != Null && $order->TAbowlingLine != -1)
                                        / <i class="fa-solid fa-bowling-ball mr-1"></i>: {{$order->TAbowlingLine}}
                                    @endif
                                </strong>
                            </p>
                            @if($order->inCashDiscount > 0)
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($order->shuma-$order->inCashDiscount - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>

                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:3px;">
                                    <strong>Skonto durch das Personal : {{number_format($order->inCashDiscount,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Rabattkommentar : {{$order->discReason}}</strong>
                                </p>
                            @elseif ($order->inPercentageDiscount > 0)
                                <?php
                                    $totBe = number_format($order->shuma - $order->tipPer, 4, '.',''); 
                                    $prct = number_format($order->inPercentageDiscount/100,4,'.','');
                                    $offVal = number_format($totBe*$prct,4,'.','');
                                    $newTotal = number_format($order->shuma-$offVal,4,'.','');
                                ?>
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($newTotal - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:3px;">
                                    <strong>prozentualer Rabatt durch das Personal : ({{number_format($order->inPercentageDiscount,2,'.','')}} % ) {{number_format($offVal,2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                    <strong>Rabattkommentar : {{$order->discReason}}</strong>
                                </p>
                            @else
                                <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                    <strong>Gesamt: {{number_format($order->shuma - $order->dicsountGcAmnt,2,'.','')}} CHF</strong>
                                </p>
                            @endif

                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                <strong>Tinkergeld: {{number_format($order->tipPer,2,'.','')}} CHF </strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                <strong>Rabatt per Gutschein: {{number_format($order->cuponOffVal,2,'.','')}} CHF </strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                @if ($order->cuponProduct == 'empty')
                                    <strong>Produkt aus Gutschein: ---</strong>
                                @else
                                    <strong>Produkt aus Gutschein: {{$order->cuponProduct}}</strong>
                                @endif
                            </p>

                            <hr style="width: 100%; margin-top: 5px; margin-bottom:5px;">

                            <p class="text-center" style="font-size:1.2rem; width:10%; margin-bottom:5px;">
                                <strong>Menge</strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:48.8%; margin-bottom:5px;">
                                <strong>Produkt</strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:20%; margin-bottom:5px;">
                                <strong>Preis</strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:20%; margin-bottom:5px;">
                                <strong>Status</strong>
                            </p>

                            @foreach(explode('---8---',$order->porosia) as $produkti)
                                <?php
                                    $prod = explode('-8-', $produkti);
                                ?>
                                <p class="text-center" style="font-size:1rem; width:10%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                    <strong>{{$prod[3]}} X</strong>
                                </p>
                                <p class="text-left pl-2" style="font-size:1rem; width:50%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
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
                                <p class="text-center" style="font-size:1rem; width:20%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                    <strong>{{number_format($prod[4],2,'.','')}} CHF</strong>
                                </p>
                                <p class="text-center openOrderStatuss" style="font-size:1rem; width:20%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
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
                                                <button class="btn btn-success shadow-none btn-block"><strong>Bereit</strong></button>
                                            @else
                                                <button class="btn btn-danger shadow-none btn-block"><strong>Nicht Bereit</strong></button>
                                            @endif
                                        @endif
                                    @endif
                                    
                                </p>
                            @endforeach

                            <hr style="width: 100%; margin-top: 5px; margin-bottom:5px;">

                            <?php
                                $dtFOr = explode('-',explode(' ',$order->created_at)[0]);
                                $hrFOr = explode(':',explode(' ',$order->created_at)[1]);
                            ?>
                            <p class="text-center" style="font-size:1rem; width:49%; margin-bottom:5px;">
                                <strong>{{$dtFOr[2]}}.{{$dtFOr[1]}}.{{$dtFOr[0]}} <span style="opacity: 0.6; margin-left:15px;">{{$hrFOr[0]}}:{{$hrFOr[1]}}</span></strong>
                            </p>
                            <p class="text-center" style="font-size:1rem; width:49%; margin-bottom:5px;">
                                <strong> # {{$order->refId}}</strong>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
      
    @endforeach
    </div>




    <div class="modal" id="PosPairModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Verbindung zu einem PayTech-POS herstellen</strong></h5>
                    <button type="button" class="close" aria-label="Close" onclick="closePosPairModal()">
                        <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                    </button>
                </div>
                <div class="modal-body p-1" id="PosPairModalBody">
                    <p class="form-control mt-1 mb-1"><strong>Schreiben Sie den vom POS-Terminal bereitgestellten Pairing-Code</strong></p>
                    <div style="width:100%;" class="input-group mb-2">
                        <input type="text" class="form-control shadow-none" placeholder="POS code" id="posCodePair">
                        <div class="input-group-append">
                            <button onclick="ConnectToPaytec()" id="ConnectToPaytecBtn" class="btn btn-outline-secondary shadow-none" type="button"><strong>Paar</strong></button>
                        </div>
                    </div>
                    <div style="width: 100%; display:none;" class="alert alert-success text-center mb-2" id="posPairSuccess01">
                        <strong>Die Verbindung zum POS-Terminal war erfolgreich</strong>
                    </div>
                    <div style="width: 100%; display:none;" class="alert alert-danger text-center mb-2" id="posPairError01">
                        <strong>Die Verbindung zum POS-Terminal war nicht erfolgreich</strong>
                    </div>

                    <hr>

                    <div id="PosPairModalAtivePOS">
                    <?php
                        $payTecPair = payTecPair::where([['userId',Auth::user()->id],['toRes',Auth::user()->sFor]])->first();
                    ?>
                    @if ($payTecPair != Null)
                        <p class="text-center mt-1 mb-1" style="width:100%;"><strong>Es besteht eine aktuelle aktive Verbindung zu einem POS-Terminal</strong></p>
                        <button class="btn btn-outline-dark" style="width:100%;" onclick="DisconnectToPaytec()" id="DisconnectToPaytecBtn">
                            <strong>Beenden Sie die POD-Verbindung</strong>
                        </button>
                    @else
                        <p class="text-center mt-1 mb-1" style="width:100%; color:red;"><strong>Es besteht derzeit keine aktive Verbindung zu einem POS-Terminal</strong></p>
                    @endif
                    </div>


                </div>
            </div>
        </div>
    </div>


 



<script>
    function closePosPairModal(){
        $('#PosPairModal').modal('hide');
    }
    function ConnectToPaytec(){
        $('#ConnectToPaytecBtn').html('Warten');
        $('#ConnectToPaytecBtn').prop('disabled', true);
        $('#posCodePair').prop('disabled', true);
        $.ajax({
            url: '{{ route("payTec.Pair") }}',
            method: 'post',
            data: {
                pairCode: $('#posCodePair').val(),
                _token: '{{csrf_token()}}'
            },
            success: (res) => {
                var resJSON = $.parseJSON(res)
                
                $('#posCodePair').val('');
                console.log(res);
                console.log(resJSON.AccessToken);
                if($('#posPairSuccess01').is(':hidden')){
                    $('#posPairSuccess01').show(50).delay(6000).hide(50);
                }
                $('#ConnectToPaytecBtn').html('Paar');
                $('#ConnectToPaytecBtn').prop('disabled', false);
                $('#posCodePair').prop('disabled', false);

                $("#PosPairModalAtivePOS").load(location.href+" #PosPairModalAtivePOS>*","");
            },
            error: (error) => {
                console.log(error);
                $('#posCodePair').val('');
                $('#ConnectToPaytecBtn').html('Paar');
                $('#ConnectToPaytecBtn').prop('disabled', false);
                $('#posCodePair').prop('disabled', false);
                if($('#posPairError01').is(':hidden')){
                    $('#posPairError01').show(50).delay(6000).hide(50);
                }
            }
        });
    }

    function DisconnectToPaytec(){
        $('#DisconnectToPaytecBtn').html('Warten');
        $('#DisconnectToPaytecBtn').prop('disabled', true);
        $.ajax({
            url: '{{ route("payTec.Disconnect") }}',
            method: 'post',
            data: { _token: '{{csrf_token()}}' },
            success: (res) => {
                var resJSON = $.parseJSON(res)
                $("#PosPairModalBody").load(location.href+" #PosPairModalBody>*","");
            },
            error: (error) => {
                console.log(error);
                $('#DisconnectToPaytecBtn').html('Beenden Sie die POD-Verbindung');
                $('#DisconnectToPaytecBtn').prop('disabled', false);
            }
        });
    }

    
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
            // $('.statusBTNCl'+orId).prop('disabled', true);
        }else{
            $('#cancelCommentDiv'+orId).hide(100);
        }
    }

    function sendCancelRequest(orId){
        if(!$('#cancelCommentInp'+orId).val()){
            if($('#cancelCommentErr01'+orId).is(':hidden')){ $('#cancelCommentErr01'+orId).show(100).delay(3500).hide(100);}
        }else{
            $('#sendCancelRequestBtn'+orId).html('<img style="width: 20%; margin-left:40%; margin-right:40%;" src="storage/gifs/loading2.gif" alt="">');
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
                    // $("#desktopTable").html('<img style="width: 30%; margin-left:35%; margin-right:35%;" src="storage/gifs/loading.gif" alt="">');
                    // $("#desktopTable").load(location.href+" #desktopTable"+">*","");
                    $("#taOrderRow"+orId).remove();
                    $("#desktopTable2").html('<img style="width: 30%; margin-left:35%; margin-right:35%;" src="storage/gifs/loading.gif" alt="">');
                    $("#desktopTable2").load(location.href+" #desktopTable2"+">*","");

		    	},
		    	error: (error) => { console.log(error); }
		    });
        }
    }

    function reloadChStat(orId){
        $("#ChStat"+orId).load(location.href+" #ChStat"+orId+">*","");
    }

    function taProdChngStatus(orId,newSta){
        $("#chngStatBnt0"+orId).html('<img style="width: 95%; margin-left:2.5%; margin-right:2.5%;" src="storage/gifs/loading2.gif" alt="">');
        $("#chngStatBnt0"+orId).prop('disabled', true);
        $("#chngStatBnt1"+orId).html('<img style="width: 95%; margin-left:2.5%; margin-right:2.5%;" src="storage/gifs/loading2.gif" alt="">');
        $("#chngStatBnt1"+orId).prop('disabled', true);
        $("#chngStatBnt2"+orId).html('<img style="width: 95%; margin-left:2.5%; margin-right:2.5%;" src="storage/gifs/loading2.gif" alt="">');
        $("#chngStatBnt2"+orId).prop('disabled', true);
        $("#chngStatBnt3"+orId).html('<img style="width: 95%; margin-left:2.5%; margin-right:2.5%;" src="storage/gifs/loading2.gif" alt="">');
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
                if(newSta == 0 || newSta == 1){
                    $("#taOrderRow"+orId).load(location.href+" #taOrderRow"+orId+">*","");
                }else{
                    // $("#desktopTable").html('<img style="width: 30%; margin-left:35%; margin-right:35%;" src="storage/gifs/loading.gif" alt="">');
                    // $("#desktopTable").load(location.href+" #desktopTable"+">*","");
                    $("#taOrderRow"+orId).remove();
                    $("#desktopTable2").html('<img style="width: 30%; margin-left:35%; margin-right:35%;" src="storage/gifs/loading.gif" alt="">');
                    $("#desktopTable2").load(location.href+" #desktopTable2"+">*","");

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

                

			},
			error: (error) => { console.log(error); }
		});
    }




    function payTAByPOS(orId){
        // payShumaShow
        $('#payTAByPOSBtn'+orId).html('<img src="storage/gifs/loading2.gif" style="width:70px; height:auto" alt="">');
        $.ajax({
            url: '{{ route("payTec.Connect") }}',
            method: 'post',
            data: {_token: '{{csrf_token()}}'},
            success: (res) => {
                
                if($('#payAllPhaseOnePayAtPOS').is(':hidden')){ $('#payAllPhaseOnePayAtPOS').show(50).delay(6000).hide(50); }
                
                $.ajax({
                    url: '{{ route("payTec.Transact") }}',
                    method: 'post',
                    timeout: 600000, // Sets a 10-minute timeout (milliseconds)
                    data: {
                        totalChf : parseFloat($('#payShumaShow'+orId).html()).toFixed(2),
                        _token: '{{csrf_token()}}'
                    },
                    success: (resTransact) => {
                        var resJSON = $.parseJSON(resTransact);
                        // if((resJSON.CardholderText == 'Transaction OK' || resJSON.CardholderText == 'Verarbeitung OK') && (resJSON.AttendantText == 'Transaction OK' || resJSON.AttendantText == 'Verarbeitung OK')){    
                        if(resJSON.TrxResult == 0){
                            
                            $('#payTAByPOSBtn'+orId).html('<strong>Karte</strong>');
                            payTAByPOSRegister(orId,resTransact);

                        }else{
                            $('#payTAByPOSBtn'+orId).html('<strong>Karte</strong>');
                            registerPayTecErrorData(resTransact);
                            alert('fail register  -- '+resJSON.CardholderText+' '+resJSON.AttendantText);
                        }
                    },error: (error) => {
                        $('#payTAByPOSBtn'+orId).html('<strong>Karte</strong>');
                        registerPayTecErrorData(error);
                        alert(error);
                    }
                });
            },error: (error) => {
                $('#payTAByPOSBtn'+orId).html('<strong>Karte</strong>');
                registerPayTecErrorData(error);
                alert(error);
            }
        });
    }

    function payTAByPOSRegister(orId,respoTransact){
        $.ajax({
			url: '{{ route("taDash.payTakeawayPosConfirm") }}',
			method: 'post',
			data: {
				orderId: orId,
				payTecTrx: respoTransact,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#desktopTableAll").load(location.href+" #desktopTableAll>*","");

                resOrId = $.trim(orId);
                
                $('#orderQRCodePicImgTel').attr('src','storage/gifs/loading2.gif');
                $.ajax({
                    url: '{{ route("receipt.getTheReceiptQrCodePic") }}',
                    method: 'post',
                    data: {
                        id: resOrId,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = $.trim(res);
                        res2D = res.split('-8-8-');
                        $('#orderQRCodePicIdTel').html('{{__("adminP.orderId")}}: '+res2D[2]);
                        $('#orderQRCodePicImgTel').attr('src','storage/digitalReceiptQRK/'+res2D[0]); 
                        $('#orderQRCodePicDownloadOI').val(resOrId);
                        $('#orderQRCodePicTel').modal('show');
                    },
                    error: (error) => { console.log(error); }
                });
			},
			error: (error) => { console.log(error); }
		});
    }

</script>

</section>