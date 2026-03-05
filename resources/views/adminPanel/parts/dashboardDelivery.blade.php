<?php

use App\DeliveryPLZ;
use Illuminate\Support\Facades\Auth;
    use App\taDeForCookOr;
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Delivery']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Orders;
    use App\StatusWorker;
    use App\User;
    use Carbon\Carbon;
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
</style>

<section class="pl-4 pr-4 pb-5">
    <hr>
    <div class="d-flex justify-content-between">
        <h2 class="text-center color-qrorpa" style="width:100%;">
            <strong>{{__('adminP.deliveryB')}}</strong>  
        </h2>
    </div>

    @include('adminPanel.tablePage.tableIndexNotifications')



    @if(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',9000]])->where('created_at', '>',Carbon::now()->subDay())->get()->count() >0)
    <table class="table table-hover" id="desktopTable">
        <thead>
            <tr>
                <th style="opacity:50%;">{{__('adminP.time')}}</th>
                <th style="opacity:50%;">{{__('adminP.products')}}</th>
                <th style="opacity:50%;">{{__('adminP.total')}}</th>
                <th class="text-center" style="opacity:50%;">{{__('adminP.payemntMethod')}}</th>
                <th class="text-center" style="opacity:50%;">{{__('adminP.status')}}</th>
                <th class="text-center" style="opacity:50%;">{{__('adminP.press')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',9000]])->where('created_at', '>',Carbon::now()->subDay())
            ->get()->sortByDesc('created_at') as $order)
                <?php
                    $orderDate2D= explode('-',explode(' ',$order->created_at)[0]); 
                ?>
                <tr class="openOrderRow" id="deOrderRow{{$order->id}}">
                    <td  data-toggle="modal" data-target="#openOrder{{$order->id}}">
                        <?php
                            $isReady = 1;
                            foreach(taDeForCookOr::where('orderId',$order->id)->get() as $orOneP){
                                if((int)$orOneP->prodSasia != (int)$orOneP->prodSasiaDone){ $isReady = 0; }
                            }
                        ?>
                        @if ($isReady == 1)
                            <button class="btn btn-block btn-success"><strong>Bereit</strong></button>
                        @else
                            <button class="btn btn-block btn-danger"><strong>Nicht bereit</strong></button>
                        @endif
                        <?php
                            $time2D = explode(':',explode(' ',$order->created_at)[1]);
                            $date2D = explode('-',explode(' ',$order->created_at)[0]);
                        ?>
                        <span style="font-size:12px; opacity:90%;">{{$time2D[0]}}:{{$time2D[1]}}</span><br>
                        <span style="font-size:12px; opacity:90%;">{{$date2D[2]}}.{{$date2D[1]}}.{{$date2D[0]}}</span>
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
                        <p style="margin-bottom: 3px;">{{$thisProd1[0]}} 
                            @if ($thisProd2[1] != '')
                                <span style="opacity:0.8;">( {{$thisProd1[1]}} )</span>
                            @endif
                        </p>
                        @if(!empty($thisOrder[1]))
                            <p style="margin-bottom: 3px;">{{$thisProd2[0]}} 
                                @if ($thisProd2[1] != '')
                                    <span style="opacity:0.8;">( {{$thisProd2[1]}} )</span>
                                @endif
                            </p>
                        @endif
                        @if(!empty($thisOrder[2]))
                            <p style="margin-bottom: 3px;">{{$thisProd3[0]}} 
                                @if ($thisProd2[1] != '')
                                    <span style="opacity:0.8;">( {{$thisProd3[1]}} )</span>
                                @endif
                            </p>
                        @endif
                        @if(!empty($thisOrder[3]))
                            <p style="margin-bottom: 3px;">...</p>
                        @endif
                    </td>
                    <td data-toggle="modal" data-target="#openOrder{{$order->id}}">
                        <p>{{number_format($order->shuma, 2, '.', ' ')}} {{__('adminP.currencyShow')}}</p>
                    </td>
                    <td data-toggle="modal" class="text-center" data-target="#openOrder{{$order->id}}">
                        <p>{{$order->payM}}</p>
                    </td>
                    <td>
                        @if($order->statusi == 0)                 
                            <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">
                                 {{__('adminP.wait')}}
                            </button>           
                        @elseif($order->statusi == 1)
                            <button class="btn btn-info btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">
                               {{__('adminP.confirmed')}}
                            </button>
                        @elseif($order->statusi == 2)
                            <button class="btn btn-danger btn-block" disabled>{{__('adminP.canceled')}} <br>
                                @if($order->StatusBy!= 999999)
                                    @if(User::find($order->StatusBy) !=null)
                                        {{__('adminP.from')}} {{User::find($order->StatusBy)->name}}
                                    @endif
                                @endif
                            </button>
                        @elseif($order->statusi == 3)
                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#ChStat{{$order->id}}">
                               {{__('adminP.finished')}}
                            </button>
                        @endif
                        <p style="color:rgb(39,190,175);" class="text-center mt-2"><strong>{{explode('|',$order->shifra)[1]}}</strong></p>
                    </td>
                    <!-- <td class="text-center"><a href="generatePDF/{{$order->id}}"><i class="fa fa-file-pdf-o fa-2x"></i></a></td> -->
                    <td class="text-center">
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

    @else
        <p class="text-center color-qrorpa"><strong>{{__('adminP.noDeliveryOrdersToday')}}</strong></p>
    @endif



















    @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',9000]])->where('created_at', '>',Carbon::now()->subDay())
            ->get() as $order)
        <?php
            $orderDate2D = explode(' ', $order->created_at);
            $orderDate2DM= explode('-',explode(' ',$order->created_at)[0]); 
            
        ?>
            <!-- The Modal -->
            <div class="modal" id="openOrder{{$order->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:2%;">
                <div class="modal-dialog modal-xl" >
                    <div class="modal-content" style="border-radius:30px; border:3px solid rgb(39,190,175);">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title color-qrorpa">
                            <strong>{{__('adminP.deliveryFor')}} : {{$order->TAemri}} {{$order->TAmbiemri}}</strong> 
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">X</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body d-flex justify-content-between flex-wrap">
                        <div style="width:25%;" style="font-size:17px;">
                            <p class="color-qrorpa"><strong> {{__('adminP.deliveryOrderAt')}} : </strong> </p>
                            <p style="margin-top:-15px;"><strong>{{explode(':',$orderDate2D[1])[0]}}:{{explode(':',$orderDate2D[1])[1]}}</strong></p>
                            <p style="margin-top:-15px;"><strong>{{explode('-',$orderDate2D[0])[2]}}.{{explode('-',$orderDate2D[0])[1]}}.{{explode('-',$orderDate2D[0])[0]}}</strong></p>
                        </div>
                        <div style="width:40%;">
                            <p class="color-qrorpa" style="font-size:17px;"><strong>{{__('adminP.otherInformation')}} : </strong> </p>
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">{{__('adminP.phoneNumber')}} :</span> {{$order->userPhoneNr}}</strong></p>
                            @if($order->userEmail != 'empty')
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">{{__('adminP.email')}} :</span> {{$order->userEmail}}</strong></p>
                            @endif
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">{{__('adminP.address')}} :</span> {{$order->TAaddress}}</strong></p>
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">{{__('adminP.zipCity')}} :</span> {{explode('|||',$order->TAplz)[0]}} / {{$order->TAort}}</strong></p>
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">Kundenzeit :</span> {{$order->TAtime}}</strong></p>
                            <?php
                                $thePLZIns = DeliveryPLZ::where([['toRes',Auth::user()->sFor],['plz',explode('|||',$order->TAplz)[0]]])->first();
                            ?>
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">{{__('adminP.deliveryTime')}} :</span>{{$thePLZIns->takesTime}}-{{$thePLZIns->takesTimeEnd}} min</strong></p>
                            @if ($order->TAkoment != '')
                                <p style="margin-top:-15px; text-decoration:underline;">
                                    <strong><span class="color-qrorpa">{{__('adminP.comment')}} :</span>{{$order->TAkoment}}</strong>
                                </p>
                            @endif
                            
                        </div>
                        <div style="width:35%;">
                            <p class="color-qrorpa" style="font-size:17px;"><strong> {{__('adminP.orderingInformation')}} : </strong> </p>
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">{{__('adminP.voucherOff')}} :</span> {{number_format($order->cuponOffVal, 2, '.', ' ')}} {{__('adminP.currencyShow')}}</strong></p>
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">{{__('adminP.tip')}}:</span> {{number_format($order->tipPer, 2, '.', ' ')}} {{__('adminP.currencyShow')}}</strong></p>    
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">Liefergebühr:</span> {{number_format(explode('|||',$order->TAplz)[1], 2, '.', ' ')}}</strong></p>
                            <p style="margin-top:-15px;"><strong><span class="color-qrorpa">{{__('adminP.payemntMethod')}}:</span> {{$order->payM}}</strong></p>
                            <p style="margin-top:-15px; font-size:24px;"><strong><span class="color-qrorpa">{{__('adminP.total')}}:</span> {{number_format($order->shuma, 2, '.', ' ')}} {{__('adminP.currencyShow')}}</strong></p>
                            
                        </div>
                     
                        

                        <hr style="width:100%;">
                         
                        @foreach(explode('---8---',$order->porosia) as $produkti)
                            <?php $prod = explode('-8-', $produkti); ?>
                            <div style="width:60%;">
                                <p style="font-size:21px;"><strong>
                            
                                    <strong>{{$prod[3]}}x </strong>
                           
                                    {{$prod[0]}}</strong>
                                    @if($prod[5] != "")
                                        ( {{$prod[5]}} )
                                    @endif
                                </p> 
                                <p style="margin-top:-20px;">{{$prod[1]}}</p> 
                            </div>
                            <div style="width:25%;">
                                <?php
                                    $eStep = 1;
                                ?>
                            @foreach(explode('--0--', $prod[2]) as $ex)
                                    @if($ex != 'empty' && $ex != "")
                                        @if($eStep++ == 1)
                                            <p>{{explode('||', $ex)[0]}} ({{explode('||', $ex)[1]}} {{__('adminP.currencyShow')}})</p>
                                        @else
                                            <p style="margin-top:-15px;">{{explode('||', $ex)[0]}} ({{explode('||', $ex)[1]}} {{__('adminP.currencyShow')}})</p>
                                        @endif
                                    @endif
                            @endforeach
                            </div>
                            <div style="width:15%;">
                                <p>{{number_format($prod[4], 2, '.', ' ')}} {{__('adminP.currencyShow')}}</p>
                                @if($prod[3] > 1)
                                    <p style="margin-top:-10px;"> {{number_format($prod[4] * $prod[3], 2, '.', ' ')}} {{__('adminP.currencyShow')}} ({{$prod[3]}}x)</p>
                                @endif
                            </div>
                            <hr style="width:100%; margin-top:-10px; margin-bottom:-10px;" >    
                        @endforeach
                        
                    </div>

                    </div>
                </div>
            </div>
      
    @endforeach










    @foreach(Orders::where([['Restaurant', Auth::user()->sFor],['nrTable',9000]])->where('created_at', '>',Carbon::now()->subDay())->get()->sortByDesc('created_at') as $order)
    @if(!empty($order))
        <div class="modal fade" id="ChStat{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        @if($order->userEmri != "empty" && $order->userEmri != "admin")
                        <h4 class="modal-title">{{$order->userEmri}} <span style="color:gray">{ {{$order->userEmail}} }</span></h4>
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
                                    {{ Form::hidden('backToMap', 5 , ['class' => 'form-control ']) }}
                                    {{ Form::submit(__('adminP.waitingLine'), ['class' => 'statusBTNCl'.$order->id.' form-control btn btn-warning btn-block']) }}
                                {{Form::close() }}
                            </div>
                            <div style="width:24%;" class="mr-1 ml-1 statBtn">
                                {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}
                                    {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                    {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                    {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                    {{ Form::hidden('backToMap', 5 , ['class' => 'form-control ']) }}
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
                                    {{ Form::hidden('backToMap', 5 , ['class' => 'form-control ']) }}
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
                        
                    </div>
                </div>
            </div>
        </div>
    @endif
 @endforeach
 



<script>
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

                    $("#desktopTable").load(location.href+" #desktopTable"+">*","");
		    	},
		    	error: (error) => { console.log(error); }
		    });
        }
    }

    function reloadChStat(orId){
        $("#ChStat"+orId).load(location.href+" #ChStat"+orId+">*","");
    }
</script>

</section>