<?php
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
    use App\rechnungClient;
    use Jenssegers\Agent\Agent;
    use App\OPSaferpayReference;
    use Illuminate\Support\Facades\Auth;

    $agent = new Agent();

    $thisRestaurantId = Auth::user()->sFor;

    $nowDate = date('Y-m-d');
    $dateTimeNew = Carbon::now()->subHour(24);
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

    a{
		text-decoration:none;
		color:black;
	}
</style>
 
 
 
 @include('inc.messages')
 
 

 
 
 <!-- Porosit -->
 <div class="col-12 pb-4" id="porositDash">

       
    <table class="table table-hover mt-1 mb-1">
        <thead>
            <tr id="topDesktopDate1">
                <td colspan="2" style="color:rgb(39,190,175); font-size:25px;">{{__('adminP.orders')}} </td>
                <td colspan="2"> 
                    <a class="btn shadow-none" href="{{ route('admWoMng.indexAdmMngPageWaiter') }}" style="width:100%; margin:0px; border:1px solid rgb(39,190,175); color:rgb(39,190,175);">
                        <strong>Tabellen</strong>
                    </a>
                </td>
            </tr>
        </thead>
    </table>
    

    @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['created_at', '>=', $dateTimeNew]])->whereIn('nrTable',$myTablesWaiter)->orderByDesc('created_at')->get() as $order)
        <?php
            $orderDate2D = explode(' ', $order->created_at);
            $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
        ?>
        <!-- The Modal -->
        <div class="modal" id="open{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;" >
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

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
                        @if ($order->nrTable == 500)
                        <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;"><strong> Code: <span style="color: rgb(39,190,175);">{{explode('|',$order->shifra)[1] }}</span></strong></p>
                        @else
                        <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;"><strong> Code: ----</span></strong></p>
                        @endif
                        @if($order->inCashDiscount > 0)
                            <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                <strong>Gesamt: {{number_format($order->shuma-$order->inCashDiscount,2,'.','')}} CHF</strong>
                            </p>

                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:3px;">
                                <strong>Skonto durch das Personal : {{number_format($order->inCashDiscount,2,'.','')}} CHF</strong>
                            </p>
                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                <strong>Rabattkommentar : {{$order->discReason}}</strong>
                            </p>
                        @elseif ($order->inPercentageDiscount > 0)
                            <?php
                                $prct = number_format($order->inPercentageDiscount * 0.01,4,'.','');
                                $totBef = number_format($order->shuma - $order->tipPer ,4,'.','');
                                $offVal = number_format($totBef*$prct,4,'.','');
                                $newTotal = number_format($order->shuma-$offVal,4,'.','');
                            ?>
                            <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                <strong>Gesamt: {{number_format($newTotal,2,'.','')}} CHF</strong>
                            </p>
                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:3px;">
                                <strong>prozentualer Rabatt durch das Personal : ({{number_format($order->inPercentageDiscount,2,'.','')}} % ) {{number_format($offVal,2,'.','')}} CHF</strong>
                            </p>
                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;">
                                <strong>Rabattkommentar : {{$order->discReason}}</strong>
                            </p>
                        @else
                            <p class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:5px;">
                                <strong>Gesamt: {{number_format($order->shuma,2,'.','')}} CHF</strong>
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

                        @if ($order->payM == 'Online')
                            <p class="text-center" style="font-size:0.9rem; width:50%; margin-bottom:5px;"><strong>Online</strong></p>
                            @if ($theRefIns != Null)
                            <p class="text-center" style="font-size:0.9rem; width:50%; margin-bottom:5px;"><strong>{{$theRefIns->refPh}}</strong></p>
                            @endif
                        @else
                            <p class="text-center" style="font-size:0.9rem; width:100%; margin-bottom:5px;"><strong>{{$order->payM}}</strong></p>
                        @endif

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
                            <p class="text-center" style="font-size:0.8rem; width:68.8%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                <strong>{{$prod[0]}} <span style="opacity: 0.6;">({{$prod[1]}})</span></strong>
                                @if ($prod[5] != '' && $prod[5] != 'empty')
                                <br> Typ: <strong>{{explode('||',$prod[5])[0]}}</strong>
                                @endif
                            </p>
                            <p class="text-center" style="font-size:0.8rem; width:20%; margin-bottom:3px; border-bottom:1px solid rgb(39,190,175);">
                                <strong>{{number_format($prod[4],2,'.','')}} CHF</strong>
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



        @if(!empty($order))
            <div class="modal" id="ChStat{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
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
                            <div class="d-flex flex-wrap justify-content-between mb-3" >
                                <div style="width:49%;" class="statBtn">
                                    {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}
                                        {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                        {{ Form::hidden('orderStat', 0 , ['class' => 'form-control']) }}
                                        {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                        {{ Form::submit(__('adminP.waitingLine'), ['class' => 'statusBTNCl'.$order->id.' form-control btn btn-warning btn-block', 'style'=>'font-weight:bold;']) }}
                                    {{Form::close() }}
                                </div>
                                <div style="width:49%;" class="statBtn">
                                    {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}
                                        {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                        {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                        {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                        {{ Form::submit(__('adminP.confirmed'), ['class' => 'statusBTNCl'.$order->id.' form-control btn btn-info btn-block', 'style'=>'font-weight:bold;']) }}
                                    {{Form::close() }}
                                </div>
                                <div style="width:49%;" class="mt-1 statBtn">
                                    <button onclick="showCommOrCancel('{{$order->id}}')" class="form-control btn btn-danger btn-block shadow-none" style="font-weight:bold;">{{__('adminP.canceled')}}</button>
                                </div>
                                <div style="width:49%;" class="mt-1 statBtn">
                                    {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}
                                        {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                        {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                        {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                        {{ Form::submit(__('adminP.finished'), ['class' => 'statusBTNCl'.$order->id.' form-control btn btn-success btn-block', 'style'=>'font-weight:bold;']) }}
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
                                    <button class="btn btn-dark shadow-none mb-1" style="width:100%; font-weight:bold; font-size:1.1rem; color:whitesmoke;" disabled>Bar / Barzahlungen</button>
                                    <button onclick="chngPayMTo('{{$order->id}}','Kartenzahlung')" class="btn btn-outline-dark shadow-none" style="width:100%; font-weight:bold; font-size:1.1rem; color:rgb(72,81,87);">Karte / Kartenzahlung</button>

                                @elseif ($order->payM == 'Kartenzahlung')
                                    <button onclick="chngPayMTo('{{$order->id}}','Barzahlungen')" class="btn btn-outline-dark shadow-none mb-1" style="width:100%; font-weight:bold; font-size:1.1rem; color:rgb(72,81,87);">Bar / Barzahlungen</button>
                                    <button class="btn btn-dark shadow-none" style="width:100%; font-weight:bold; font-size:1.1rem; color:whitesmoke;" disabled>Karte / Kartenzahlung</button>

                                @else
                                    <button onclick="chngPayMTo('{{$order->id}}','Barzahlungen')" class="btn btn-outline-dark shadow-none mb-1" style="width:100%; font-weight:bold; font-size:1.1rem; color:rgb(72,81,87);">Bar / Barzahlungen</button>
                                    <button onclick="chngPayMTo('{{$order->id}}','Kartenzahlung')" class="btn btn-outline-dark shadow-none" style="width:100%; font-weight:bold; font-size:1.1rem; color:rgb(72,81,87);">Karte / Kartenzahlung</button>

                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endif



    @endforeach


  


                                         
  
              


























           
                    
        <div class="d-flex flex-wrap b-white p-2" id="orderListInListingTel">

            <p style="width:25%; border-bottom:1px solid gray;" class="text-left pb-2"><strong>{{__('adminP.time')}}</strong></p>
            <p style="width:45%; border-bottom:1px solid gray;" class="text-left pb-2"><strong>{{__('adminP.product')}}</strong></p>
            <p style="width:30%; border-bottom:1px solid gray;" class="text-center pb-2"><strong>{{__('adminP.status')}}</strong></p>
			
                <!-- Per Desktop -->
                @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['created_at', '>=', $dateTimeNew]])->whereIn('nrTable',$myTablesWaiter)->orderByDesc('created_at')->get() as $order) 
                    <?php
                        $orderDate2D = explode(' ', $order->created_at);
                        $time2D = explode(':', $orderDate2D[1]);
                        $date2D = explode('-', $orderDate2D[0]);
                        $theRefIns = OPSaferpayReference::where('orderId',$order->id)->first();
                    ?>
                    <div style="width:25%" class="text-left pb-2" id="orderListingShow{{$order->id}}">
                        <a style="width:45%; color:rgb(72,81,87);" href="#" data-toggle="modal" data-target="#open{{$order->id}}">
                            <!-- <p>{{explode(':', $orderDate2D[1])[0]}}:{{explode(':', $orderDate2D[1])[1]}}</p>  -->

                            @if ($order->inCashDiscount > 0 )
                                <p>{{number_format($order->shuma - $order->inCashDiscount, 2, ',','')}} <span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span></p>    
                            @elseif ($order->inPercentageDiscount > 0 )
                                <?php 
                                    $percOf = number_format($order->inPercentageDiscount * 0.01, 4, '.',''); 
                                    $totBefo = number_format($order->shuma - $order->tipPer, 4, '.',''); 
                                    $rab = number_format($totBefo * $percOf, 4, '.',''); 
                                ?>
                                <p>{{number_format($order->shuma - $rab, 2, '.','')}}} <span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span></p>    
                            @else
                                <p>{{$order->shuma}} <span style="opacity:0.7;">{{__('adminP.currencyShow')}}</span></p>
                            @endif

                            @if($order->nrTable != 500)
                            <p style="margin-top:-15px;" ><strong>{{__('adminP.table')}} :{{$order->nrTable}}</strong> </p>
                            @else
                            <p style="margin-top:-15px;" ><strong>Takeaway</strong> </p>
                            @endif
                            @if(PiketLog::where('order_u', $order->id)->first() != null)
                                @if(PiketLog::where('order_u', $order->id)->first()->piket < 0)
                                    <p style="margin-top:-15px;"><strong>( {{(PiketLog::where("order_u", $order->id)->first()->piket)*0.01}} {{__('adminP.currencyShow')}})</strong></p>
                                @endif
                            @endif
                        
                        </a>
                    </div>
                    <a style="width:45%; color:rgb(72,81,87);" href="#" data-toggle="modal" data-target="#open{{$order->id}}">
                        <p  class="text-left" > 
                            <?php
                                echo explode('-8-', explode('---8---',$order->porosia)[0])[0];
                                if(!empty(explode('---8---',$order->porosia)[1])){
                                    echo '<br>...';
                                }
                                if($order->freeProdId != 0){
                                    if(FreeProducts::find($order->freeProdId)->nameExt != 'none'){
                                        echo '<p style="margin-top:-20px;"><strong>'.__("adminP.coupon").' :</strong>'.FreeProducts::find($order->freeProdId)->nameExt.'</p>' ;
                                    }else{
                                    echo '<p style="margin-top:-20px;"><strong>'.__("adminP.coupon").' :</strong>'.Produktet::find(FreeProducts::find($order->freeProdId)->prod_id)->emri.'</p>' ;
                                    }
                                }
                            ?>
                        </p>
                    </a>
                
                    <p style="width:30%;" class="text-center"> 
                        
                        @if($order->statusi == 0)
                            <a href="#" class="anchorNoStyle" data-toggle="modal" data-target="#ChStat{{$order->id}}">
                                <button style="font-weight: bold; font-size:0.7rem;" class="btn btn-warning btn-block shadow-none" >{{__("adminP.waitingLine")}}
                                    @if($order->StatusBy!= 999999)
                                        @if(User::find($order->StatusBy) !=null)
                                            {{__("adminP.from")}} {{User::find($order->StatusBy)->name}}
                                        @endif
                                    @endif
                                </button>
                            </a>
                        @elseif($order->statusi == 1)
                            <a href="#" class="anchorNoStyle"  data-toggle="modal" data-target="#ChStat{{$order->id}}" >
                                <button style="font-size:0.7rem;" class="btn btn-info btn-block shadow-none">{{__("adminP.confirmed")}}
                                    @if($order->StatusBy!= 999999)
                                        @if(User::find($order->StatusBy) !=null)
                                            {{__("adminP.from")}} {{User::find($order->StatusBy)->name}}
                                        @endif
                                    @endif
                                </button>
                            </a>
                        @elseif($order->statusi == 2)
                            <a href="#" class="anchorNoStyle">
                                <button style="font-size:0.7rem;" class="btn btn-danger btn-block shadow-none" disabled>{{__("adminP.canceled")}}
                                    @if($order->StatusBy!= 999999)
                                        @if(User::find($order->StatusBy) !=null)
                                            {{__("adminP.from")}} {{User::find($order->StatusBy)->name}}
                                        @endif
                                    @endif
                                </button>
                            </a>
                        @elseif($order->statusi == 3)
                            <a href="#" class="anchorNoStyle" data-toggle="modal" data-target="#ChStat{{$order->id}}">
                                <button style="font-size:0.7rem;" class="btn btn-success btn-block shadow-none" >{{__("adminP.finished")}}
                                    @if($order->StatusBy!= 999999)
                                        @if(User::find($order->StatusBy) !=null)
                                            {{__("adminP.from")}} {{User::find($order->StatusBy)->name}}
                                        @endif
                                    @endif
                                </button>
                            </a> 
                        @endif
                    </p>
                @endforeach
     
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

                   
                   
               
</div>



