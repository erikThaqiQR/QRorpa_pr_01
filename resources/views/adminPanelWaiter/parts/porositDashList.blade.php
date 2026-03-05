<?php

use App\OPSaferpayReference;

    use Illuminate\Support\Facades\Auth;
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Aufträge']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Restorant;
    use App\Orders;
    use Carbon\Carbon;
    use App\StatusWorker;
    use App\PiketLog;
    use App\Produktet;
    use App\FreeProducts;
    use App\User;
    use Jenssegers\Agent\Agent;
    $agent = new Agent();

    $thisRestaurantId = Auth::user()->sFor;
?>


<input type="hidden" value="{{$thisRestaurantId}}" id="theResId">
<script> 
    var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {
        cluster: 'eu'
    });
    var channel = pusher.subscribe('UpdateAPChanel');
    channel.bind('App\\Events\\updateAP', function(data) {
            
        var dataJ = JSON.stringify(data);
        var dataJ2 = JSON.parse(dataJ);

        console.log(dataJ2);
        if($('#theResId').val() == dataJ2.text){
            location.reload(true);
        }
    });
</script>






 
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
</style>

 @include('inc.messages')


 
 
 
 <!-- Porosit -->
 <div class="col-12 pb-4" id="porositDash">

       
    <table class="table table-hover mt-4">
        <thead>
        @if($agent->isTablet())  
            <tr id="timeTablet">
                <td colspan="2" class="text-left">
                    <p class="color-black fsize-35 opacity-65" id="PageDate1">.</p>
                </td>
                <td colspan="2" class="text-right">
                    <p class="color-black fsize-35" id="PageTime1">.</p>
                </td>
            </tr>
            @else
            <tr id="timeDesktop" >
                <td colspan="4" class="text-left">
                    <p class="color-black  fsize-35 opacity-65" id="PageDate2">.</p>
                </td>
                <td colspan="3" class="text-right">
                    <p class="color-black fsize-35" id="PageTime2">.</p>
                </td>
            </tr>
            @endif

            @if($agent->isTablet())  
            <tr id="topTabletDate1">
                <td colspan="1" style="color:rgb(39,190,175); font-size:25px;">{{__('adminP.orders')}} </td>
                <td colspan="3"></td>
            </tr>
            @else
            <tr id="topDesktopDate1">
                <td colspan="1" style="color:rgb(39,190,175); font-size:25px;">{{__('adminP.orders')}} </td>
                <td colspan="6"></td>
            </tr>
            @endif
             
            @if($agent->isTablet())  
            <tr id="tabletTableHeader" >
                <th style="opacity:50%;">{{__('adminP.dateTime')}}</th>
                <th style="opacity:50%;">{{__('adminP.table')}}</th>
                <th style="opacity:50%;">{{__('adminP.article')}}</th>
                <th style="opacity:50%;">{{__('adminP.status')}}</th>
            </tr>
            @else
            <tr id="desktopTableHeader">
                <th style="opacity:50%;">{{__('adminP.dateTime')}}</th>
                <th style="opacity:50%;">{{__('adminP.table')}}</th>
                <th style="opacity:50%;">{{__('adminP.article')}}</th>
                <th style="opacity:50%;">{{__('adminP.total')}}</th>
                <th style="opacity:50%;">{{__('adminP.paymentMethod')}}</th>
                <th style="opacity:50%;">{{__('adminP.status')}}</th>
                <th style="opacity:50%;"></th>
            </tr>
            @endif

            <?php
                $nowDate = date('Y-m-d');

                $dateTimeNew = Carbon::now()->subHour(24);
            ?>


















    @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['created_at', '>=', $dateTimeNew]])->whereIn('nrTable',$myTablesWaiter)->get() as $order)
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
                        @if ($order->nrTable == 500)
                        <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;"><strong> Code: <span style="color: rgb(39,190,175);">{{explode('|',$order->shifra)[1] }}</span></strong></p>
                        @else
                        <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;"><strong> Code: ----</strong></p>
                        @endif
                        @if($order->inCashDiscount > 0)
                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                <strong>Gesamt: {{number_format($order->shuma-$order->inCashDiscount,2,'.','')}} CHF</strong>
                            </p>

                            <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:3px;">
                                <strong>Skonto durch das Personal : {{number_format($order->inCashDiscount,2,'.','')}} CHF</strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                <strong>Rabattkommentar : {{$order->discReason}}</strong>
                            </p>
                        @elseif ($order->inPercentageDiscount > 0)
                            <?php
                                $prct = number_format($order->inPercentageDiscount*0.01,4,'.','');
                                $totBef = number_format($order->shuma - $order->tipPer ,4,'.','');
                                $offVal = number_format($totBef*$prct,4,'.','');
                                $newTotal = number_format($order->shuma-$offVal,4,'.','');
                            ?>
                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                <strong>Gesamt: {{number_format($newTotal,2,'.','')}} CHF</strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:3px;">
                                <strong>prozentualer Rabatt durch das Personal : ({{number_format($order->inPercentageDiscount,2,'.','')}} % ) {{number_format($offVal,2,'.','')}} CHF</strong>
                            </p>
                            <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                <strong>Rabattkommentar : {{$order->discReason}}</strong>
                            </p>
                        @else
                            <p class="text-center" style="font-size:1.2rem; width:33%; margin-bottom:5px;">
                                <strong>Gesamt: {{number_format($order->shuma,2,'.','')}} CHF</strong>
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
                        <p class="text-center" style="font-size:1.2rem; width:68.8%; margin-bottom:5px;">
                            <strong>Produkt</strong>
                        </p>
                        <p class="text-center" style="font-size:1.2rem; width:20%; margin-bottom:5px;">
                            <strong>Preis</strong>
                        </p>
                     

                        @foreach(explode('---8---',$order->porosia) as $produkti)
                            <?php
                                $prod = explode('-8-', $produkti);
                            ?>
                            <p class="text-center" style="font-size:1rem; width:10%; margin-bottom:3px;">
                                <strong>{{$prod[3]}} X</strong>
                            </p>
                            <p class="text-center" style="font-size:1rem; width:68.8%; margin-bottom:3px;">
                                <strong>{{$prod[0]}} <span style="opacity: 0.6;">({{$prod[1]}})</span></strong>
                                @if ($prod[5] != '' && $prod[5] != 'empty')
                                <br> Typ: <strong>{{explode('||',$prod[5])[0]}}</strong>
                                @endif
                            </p>
                            <p class="text-center" style="font-size:1rem; width:20%; margin-bottom:3px;">
                                <strong>{{number_format($prod[4],2,'.','')}} CHF</strong>
                            </p>
                        
                        @endforeach

                        <hr style="width: 100%; margin-top: 5px; margin-bottom:5px;">

                        <?php
                            $dtFOr = explode('-',explode(' ',$order->created_at)[0]);
                            $hrFOr = explode(':',explode(' ',$order->created_at)[1]);
                            $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                        ?>
                        @if ($theRefIns != Null)
                            <p class="text-center" style="font-size:1rem; width:33%; margin-bottom:5px;">
                                <strong>{{$dtFOr[2]}}.{{$dtFOr[1]}}.{{$dtFOr[0]}} <span style="opacity: 0.6; margin-left:15px;">{{$hrFOr[0]}}:{{$hrFOr[1]}}</span></strong>
                            </p>
                            <p class="text-center" style="font-size:1rem; width:33%; margin-bottom:5px;">
                                <strong> # {{$order->refId}}</strong>
                            </p>
                            <p class="text-center" style="font-size:1rem; width:33%; margin-bottom:5px;">
                                <strong>Saferpay: {{$theRefIns->refPh}}</strong>
                            </p>
                        @else
                            <p class="text-center" style="font-size:1rem; width:49%; margin-bottom:5px;">
                                <strong>{{$dtFOr[2]}}.{{$dtFOr[1]}}.{{$dtFOr[0]}} <span style="opacity: 0.6; margin-left:15px;">{{$hrFOr[0]}}:{{$hrFOr[1]}}</span></strong>
                            </p>
                            <p class="text-center" style="font-size:1rem; width:49%; margin-bottom:5px;">
                                <strong> # {{$order->refId}}</strong>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>







        @if(!empty($order))
            <div class="modal" id="ChStat{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            @if ($order->userEmri != 'empty')
                                @if ($order->userEmri == 'admin')
                                    <h4 class="modal-title"><strong>Bestellung vom Personal geschlossen</strong></h4>
                                @else
                                    <h4 class="modal-title">{{$order->userEmri}} <span style="color:gray">{ {{$order->userEmail}} }</span></h4>
                                @endif
                            @endif
                            <button type="button" class="close" data-dismiss="modal" onclick="reloadChStat('{{$order->id}}')"><i class="far fa-times-circle"></i></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="d-flex mb-3" >
                                <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                    {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}
                                        {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                        {{ Form::hidden('orderStat', 0 , ['class' => 'form-control']) }}
                                        {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                        {{ Form::submit(__('adminP.waitingLine'), ['class' => 'statusBTNCl'.$order->id.' form-control btn btn-warning btn-block']) }}
                                    {{Form::close() }}
                                </div>
                                <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                    {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}
                                        {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                        {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                        {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                        {{ Form::submit(__('adminP.confirmed'), ['class' => 'statusBTNCl'.$order->id.' form-control btn btn-info btn-block']) }}
                                    {{Form::close() }}
                                </div>
                                <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                    <button onclick="showCommOrCancel('{{$order->id}}')" class="form-control btn btn-danger btn-block shadow-none" style="font-weight:bold;">{{__('adminP.canceled')}}</button>
                                </div>
                                <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                    {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}
                                        {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                        {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                        {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                        {{ Form::submit(__('adminP.finished'), ['class' => 'statusBTNCl'.$order->id.' form-control btn btn-success btn-block']) }}
                                    {{Form::close() }}
                                </div>
                            </div>
                            <div style="display: none;" id="cancelCommentDiv{{$order->id}}" class="form-group mb-2 mt-2">
                                <label for="exampleFormControlTextarea1"><strong>Kommentar zur Stornierung</strong></label>
                                <textarea id="cancelCommentInp{{$order->id}}" class="form-control shadow-none mb-1" rows="2"></textarea>
                                <button onclick="sendCancelRequest('{{$order->id}}')" style="margin:0px;" class="mb-1 btn-block btn btn-dark shadow-none" type="button">
                                    <strong>Bestätigen</strong>
                                </button>
                                <div class="alert alert-danger text-center mt-1" style="display:none;" id="cancelCommentErr01{{$order->id}}">
                                    <strong>Bitte schreiben Sie zuerst einen Kommentar</strong>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between" id="chngPayMethodDiv{{$order->id}}">
                                <hr style="width:100%;">
                                <p style="font-size: 1.2rem; color:rgb(72,81,87); width:100%;">
                                    <strong>Aktuelle Zahlungsform: <span id="chngPayMethodCrrPM">{{$order->payM}}</span></strong>
                                </p>
                                @if ($order->payM == 'Barzahlungen')
                                    <button class="btn btn-dark shadow-none" style="width:49%; font-weight:bold; font-size:1.1rem; color:whitesmoke;" disabled>Bar / Barzahlungen</button>
                                    <button onclick="chngPayMTo('{{$order->id}}','Kartenzahlung')" class="btn btn-outline-dark shadow-none" style="width:49%; font-weight:bold; font-size:1.1rem; color:rgb(72,81,87);">Karte / Kartenzahlung</button>

                                @elseif ($order->payM == 'Kartenzahlung')
                                    <button onclick="chngPayMTo('{{$order->id}}','Barzahlungen')" class="btn btn-outline-dark shadow-none" style="width:49%; font-weight:bold; font-size:1.1rem; color:rgb(72,81,87);">Bar / Barzahlungen</button>
                                    <button class="btn btn-dark shadow-none" style="width:49%; font-weight:bold; font-size:1.1rem; color:whitesmoke;" disabled>Karte / Kartenzahlung</button>

                                @else
                                    <button onclick="chngPayMTo('{{$order->id}}','Barzahlungen')" class="btn btn-outline-dark shadow-none" style="width:49%; font-weight:bold; font-size:1.1rem; color:rgb(72,81,87);">Bar / Barzahlungen</button>
                                    <button onclick="chngPayMTo('{{$order->id}}','Kartenzahlung')" class="btn btn-outline-dark shadow-none" style="width:49%; font-weight:bold; font-size:1.1rem; color:rgb(72,81,87);">Karte / Kartenzahlung</button>

                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    @endforeach


  



					

                    <style>
						a{
							text-decoration:none;
							color:black;
						}
					</style>


















            @if($agent->isTablet())  
                <!-- Per Tablet -->
                @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['created_at', '>=', $dateTimeNew]])->whereIn('nrTable',$myTablesWaiter)->orderByDesc('created_at')->get() as $order)
                    <?php
                        $orderDate2D = explode(' ', $order->created_at);
                        $time2D = explode(':', $orderDate2D[1]);
                        $date2D = explode('-', $orderDate2D[0]);
                    ?>
                    <tr class="openOrderRow tabletRows" id="orderListingShow{{$order->id}}">
                        <td>
							<a href="#" data-toggle="modal" data-target="#openOrder{{$order->id}}">{{$time2D[0]}}:{{$time2D[1]}}<br>
                            <span style="font-size:12px; opacity:60%;">{{$date2D[2]}}.{{$date2D[1]}}.{{$date2D[0]}}</span><br>               
                                @if(PiketLog::where('order_u', $order->id)->first() != null)
                                    @if(PiketLog::where('order_u', $order->id)->first()->piket < 0)                        
                                        <span style="margin-top:-12px"> {{__("adminP.usedPoints")}} : {{((PiketLog::where("order_u", $order->id)->first()->piket) * -1)}} 
                                        <strong>( {{(PiketLog::where("order_u", $order->id)->first()->piket)*0.01}} {{__("adminP.currencyShow")}})</strong></span> 
                                    @endif
                                @endif
							</a>
                        </td>
                        <td>
						    <a href="#" data-toggle="modal" data-target="#openOrder{{$order->id}}">
                        		<p><strong class="pl-2">{{( $order->nrTable==500 ? 'Takeaway' : $order->nrTable )}}</strong></p>
						    </a>
                        </td>
                        <?php        
                            $thisOrder = explode('---8---',$order->porosia)[0];
                            $thisProd = explode('-8-',$thisOrder);
                        ?>               
                        <td>
						    <a href="#" data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            	<p>{{$thisProd[0]}}</p>
                            	<p style=" margin-top:-20px;">...</p>
                            </a>
                            @if($order->freeProdId != 0){
                                @if(FreeProducts::find($order->freeProdId)->nameExt != 'none')
                                    <p style="margin-top:-20px;"><strong>{{__("adminP.coupon")}} :</strong>{{FreeProducts::find($order->freeProdId)->nameExt}}</p>
                                @else
                                   <p style="margin-top:-20px;"><strong>{{__("adminP.coupon")}} :</strong>{{Produktet::find(FreeProducts::find($order->freeProdId)->prod_id)->emri}}</p>
                                @endif
                            @endif
                        </td>        
                        <td>
                        @if($order->statusi == 0)
                            <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">{{__("adminP.waitingLine")}}<br>
                                @if($order->StatusBy!= 999999)
                                    @if(User::find($order->StatusBy) !=null)
                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                    @endif
                                @endif
                            </button>
                        @elseif($order->statusi == 1)
                            <button class="btn btn-info btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">{{__("adminP.confirmed")}}<br>
                                @if($order->StatusBy!= 999999)
                                    @if(User::find($order->StatusBy) !=null)
                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                    @endif
                                @endif
                            </button>
                        @elseif($order->statusi == 2)
                            <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}" disabled>{{__("adminP.canceled")}}<br>
                                @if($order->StatusBy!= 999999)
                                    @if(User::find($order->StatusBy) != null)
                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                    @endif
                                @endif
                            </button>
                        @elseif($order->statusi == 3)
                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">{{__("adminP.finished")}}<br>
                                @if($order->StatusBy!= 999999)
                                    @if(User::find($order->StatusBy) !=null)
                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                    @endif
                                @endif
                            </button>
                        @endif
                        <br>
                            <form method="POST" action="{{ route('receipt.getReceipt') }}">
                                {{ csrf_field()}}
                                <input type="hidden" value="{{$order->id}}" name="orId">
                                <button type="submit" class="btn"> {{__("adminP.bill")}} </button>
                            </form>
                        </td>
                    <tr>                   
                @endforeach
            @else
                <!-- Per Desktop -->
                @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['created_at', '>=', $dateTimeNew]])->whereIn('nrTable',$myTablesWaiter)->orderByDesc('created_at')->get() as $order)
                    <?php
                        $orderDate2D = explode(' ', $order->created_at);
                        $time2D = explode(':', $orderDate2D[1]);
                        $date2D = explode('-', $orderDate2D[0]);
                        $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                    ?>
                    <tr class="openOrderRow desktopRows" id="orderListingShow{{$order->id}}">
                        <td data-toggle="modal" data-target="#openOrder{{$order->id}}">{{$time2D[0]}}:{{$time2D[1]}}<br>
                        <span style="font-size:12px; opacity:60%;">{{$date2D[2]}}.{{$date2D[1]}}.{{$date2D[0]}}</span>
                              @if(PiketLog::where('order_u', $order->id)->first() != null)
                                  @if(PiketLog::where('order_u', $order->id)->first()->piket < 0)
                                      <br>
                                      <span style="margin-top:-12px"> {{__("adminP.usedPoints")}} : {{((PiketLog::where("order_u", $order->id)->first()->piket) * -1)}}
                                      <strong>( {{(PiketLog::where("order_u", $order->id)->first()->piket)*0.01}} {{__("adminP.currencyShow")}})</strong></span> 
                                  @endif
                              @endif
                        </td>
                        <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                            <p><strong class="pl-2">{{ ( $order->nrTable==500 ? 'Takeaway' : $order->nrTable )}}</strong></p>
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
                            <p>{{$thisProd1[0]}}<span style="opacity:0.7;">( {{$thisProd1[1]}} )</span></p>
                            @if(!empty($thisOrder[1]))
                                <p style="margin-top:-20px;">{{$thisProd2[0]}}<span style="opacity:0.7;">( {{$thisProd2[1]}} )</span></p>
                            @endif
                            @if(!empty($thisOrder[2]))
                                <p style="margin-top:-20px;">{{$thisProd3[0]}} <span style="opacity:0.7;">( {{$thisProd3[1]}} )</span></p>
                            @endif
                            @if(!empty($thisOrder[3]))
                                <p style="margin-top:-20px;"><strong>...</strong></p>
                            @endif

                            @if($order->freeProdId != 0)
                                @if($order->freeProdId != 0)
                                    @if(FreeProducts::find($order->freeProdId)->nameExt != 'none')
                                        <p style="margin-top:-20px;"><strong>{{__("adminP.coupon")}} :</strong>{{FreeProducts::find($order->freeProdId)->nameExt}}</p>
                                    @else
                                        <p style="margin-top:-20px;"><strong>{{__("adminP.coupon")}} :</strong>{{Produktet::find(FreeProducts::find($order->freeProdId)->prod_id)->emri}}</p>
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td>
                            @if ($order->inCashDiscount > 0 )
                                <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;">{{number_format($order->shuma - $order->inCashDiscount, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p class="text-center" style="font-size:0.7rem; margin-bottom:3px;">(Rabat:{{number_format($order->inCashDiscount, 2, ',','')}}<sup>{{__('adminP.currencyShow')}}</sup>)</p>
                            @elseif ($order->inPercentageDiscount > 0 )
                                <?php 
                                    $percOf = number_format($order->inPercentageDiscount * 0.01, 4, '.','');  
                                    $totBef = number_format($order->shuma - $order->tipPer ,4,'.','');
                                    $rab = number_format($totBef * $percOf, 4, '.',''); 
                                ?>
                                <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;">{{number_format($order->shuma - $rab, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
                                <p class="text-center" style="font-size:0.7rem; margin-bottom:3px;">(Rabat:{{number_format($rab, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup>)</p>
                            @else
                            <p class="text-center" style="font-size:1.1rem; margin-bottom:3px;">{{number_format($order->shuma, 2, '.','')}}<sup>{{__('adminP.currencyShow')}}</sup></p>
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
                            <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">{{__('adminP.waitingLine')}}<br>
                                @if($order->StatusBy!= 999999)
                                    @if(User::find($order->StatusBy) !=null)
                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                    @endif
                                @endif
                            </button>
                        @elseif($order->statusi == 1)
                           <button class="btn btn-info btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">{{__("adminP.confirmed")}}<br>
                            @if($order->StatusBy!= 999999)
                                @if(User::find($order->StatusBy) !=null)
                                    {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                @endif
                            @endif
                           </button>
                        @elseif($order->statusi == 2)
                           <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}" disabled>{{__("adminP.canceled")}}<br>
                            @if($order->StatusBy!= 999999)
                                @if(User::find($order->StatusBy) !=null)
                                    {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                @endif
                            @endif
                           </button>
                        @elseif($order->statusi == 3)
                           <button class="btn btn-success btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">{{__("adminP.finished")}}<br>
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
                                <button type="submit" class="btn"> {{__("adminP.bill")}}</button>
                            </form>
                        </td>
                    <tr>
                @endforeach
            @endif
        </thead>
    </table>
              
                  


                   
                   
               
</div>


<script>
  


    function pad(d) {
        return (d < 10) ? '0' + d.toString() : d.toString();
    }
    function showTime() {
        var today = new Date()
        var time = pad(today.getHours())+':'+pad(today.getMinutes())+':'+pad(today.getSeconds());
        var date = pad(today.getDate())+'/'+pad((today.getMonth()+1))+'/'+today.getFullYear();
        $("#PageTime1").html(time);
        $("#PageTime2").html(time);
        $("#PageDate1").html(date);
        $("#PageDate2").html(date);
    
    }
    setInterval(showTime, 1000);



    function setChBy(wId){
        $('.statusWorkerButtonAll').attr('class','statusWorkerButtonAll btn backQr mr-1 ml-1')
        $('#statusWorkerButton'+wId).attr('class','statusWorkerButtonAll btn backQrSelected mr-1 ml-1')
        $('#sWPhase2').show();
        $('.statBtn').show(500);
        $('.chByInput').val(wId);
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
            $('.statusBTNCl'+orId).prop('disabled', true);
        }
    }

    function sendCancelRequest(orId){
        if(!$('#cancelCommentInp'+orId).val()){
            if($('#cancelCommentErr01'+orId).is(':hidden')){ $('#cancelCommentErr01'+orId).show(100).delay(3500).hide(100);}
        }else{
            $.ajax({
		    	url: '{{ route("order.cancelAOrder") }}',
		    	method: 'post',
		    	data: {
		    		oId: orId,
                    theComm: $('#cancelCommentInp'+orId).val(),
		    		_token: '{{csrf_token()}}'
		    	},
		    	success: () => {
                    $('#ChStat'+orId).modal('toggle');
                    $("#ChStat"+orId).load(location.href+" #ChStat"+orId+">*","");

                    $("#porositDash").load(location.href+" #porositDash"+">*","");

		    	},
		    	error: (error) => { console.log(error); }
		    });
        }
    }

    function reloadChStat(orId){
        $("#ChStat"+orId).load(location.href+" #ChStat"+orId+">*","");
    }


    function chngPayMTo(orId, newPayM){
        $.ajax({
			url: '{{ route("payMChng.payMethodChangeByStaff") }}',
			method: 'post',
			data: {
				orderId: orId,
                newPayM: newPayM,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#chngPayMethodDiv"+orId).load(location.href+" #chngPayMethodDiv"+orId+">*","");
				$("#orderListingShow"+orId).load(location.href+" #orderListingShow"+orId+">*","");
			},
			error: (error) => { console.log(error); }
		});
					
    }

   
</script>



