
<?php
    use App\Orders;
    use App\PiketLog;
    use App\StatusWorker;
    use Carbon\Carbon;
    use App\TabOrder;
    use App\TableQrcode;
    use App\tabVerificationPNumbers;

    use App\FreeProducts;

    use App\Produktet;

    $currRes = Auth::user()->sFor;
    $resFor = Auth::user()->sFor;
?>



<style>
    .hasOrder:hover{
        cursor: pointer;
    }

     

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








































@foreach(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('statusi','<',2)->get() as $ords )
<!-- The Modal -->
<div class="modal" id="tableModWStreetTerrasse{{$ords->nrTable}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <?php
            $orderDate2D = explode(' ', $ords->created_at);
        ?>

       <!-- Modal Header -->
       <div class="modal-header" style="width:100%;">
                        <h4 class="modal-title">
                            
                            @if($ords->nrTable != 500)
                                <span style="font-size:30px;"><strong> {{__('adminP.table')}} : {{$ords->nrTable}}</strong></span>
                            @else
                                <span style="font-size:30px;" ><strong>{{__('adminP.bringAway')}}</strong></span>
                            @endif

                            @if(PiketLog::where('order_u', $ords->id)->first() != null)
                                @if(PiketLog::where('order_u', $ords->id)->first()->piket < 0)
                                <br>
                                    <span> {{__('adminP.usedPoints')}} : {{(PiketLog::where('order_u', $ords->id)->first()->piket) * -1}} ( <strong>{{(PiketLog::where('order_u', $ords->id)->first()->piket)*0.01}} {{__('adminP.currencyShow')}}</strong> )</span>         
                                @endif
                            @endif
                            @if($ords->tipPer != 0)
                                   <br> <span> {{__('adminP.waiterTip')}}: <strong>{{$ords->tipPer}} {{__('adminP.currencyShow')}}</strong> </span>
                            @endif
                            <hr>
                           
                        
                           
                            <span> {{__('adminP.total')}} : {{Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', $ords->nrTable)->where('statusi','<',2)->get()->sum('shuma')}} <sup>{{__('adminP.currencyShow')}}</sup></span>
                            @if($ords->userEmri != "empty")
                                <span class="ml-5" >{{$ords->userEmri}}</span>
                            @endif
                            @if($ords->userEmail != "empty")
                                <span class="ml-5">{{$ords->userEmail}}</span>
                            @endif

                            @if($ords->TAemri != 'empty')
                                <br>
                                <p class="mt-3 p-2" style="border-top:1px solid lightgray;">
                                <span ><strong>{{__('adminP.extraInformation')}}</strong></span>
                                <span class="ml-5">{{$ords->TAemri}}</span>
                                <span class="ml-5">{{$ords->TAmbiemri}}</span>
                                <span class="ml-5">{{__('adminP.collect')}}: {{$ords->TAtime}}</span>
                                </p>
                            @endif
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">✖</button>
                    </div>












      <!-- Modal body -->
        <div class="modal-body d-flex justify-content-between flex-wrap">
                    @if($ords->freeProdId != 0)
                        <p style="margin-top:-20px; width:100%; font-size:19px;" class="p-1"><strong>{{__('adminP.coupon')}}:</strong>
                        @if(FreeProducts::find($ords->freeProdId)->nameExt != 'none')
                            {{FreeProducts::find($ords->freeProdId)->nameExt}}
                        @else
                            {{Produktet::find(FreeProducts::find($ords->freeProdId)->prod_id)->emri}}
                        @endif
                        </p>
                        <hr style="width:100%; margin-top:-10px;">
                    @endif
                         @foreach(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', $ords->nrTable)->where('statusi','<',2)->get() as $allOrds)
                            <?php  $allOrderDate2D = explode(' ', $allOrds->created_at)[1]; ?>
                            <div style="width:10%;" >
                                      <p class="pt-2"><strong>{{__('adminP.time')}} :</strong>{{explode(':',$allOrderDate2D)[0]}}:{{explode(':',$allOrderDate2D)[1]}}</p>
                                      <p style="margin-top:-10px;"><strong>{{$allOrds->shuma}} {{__('adminP.currencyShow')}} </strong> </p>
                            </div>
                            <div style="border:1px solid gray; width:90%;" class="p-2 mt-2 mb-2  d-flex justify-content-between flex-wrap">
                                    <div style="border-bottom:1px solid gray; width:100%;" class="d-flex justify-content-between flex-wrap mb-4">
                                        <p style="width:33%; font-size:20px;" class="text-left">{{__('adminP.waiterTip')}}: <strong>{{$allOrds->tipPer}} {{__('adminP.currencyShow')}}</strong></p>
                                        @if($allOrds->cuponOffVal != 0)
                                            <p style="width:33%; font-size:20px;" class="text-center">{{__('adminP.coupon')}}: <strong>- {{$allOrds->cuponOffVal}} {{__('adminP.currencyShow')}}</strong></p>
                                        @endif
                                        @if($allOrds->cuponProduct != 'empty')
                                            <p style="width:25%; font-size:20px;" class="text-center">{{__('adminP.forFree')}}: <strong>{{$allOrds->cuponProduct}}</strong></p>
                                        @endif
                                        @if(PiketLog::where('order_u', $allOrds->id)->first() != null)
                                            @if(PiketLog::where('order_u', $allOrds->id)->first()->piket < 0)
                                                <p style="width:33%; font-size:20px;" class="text-right">{{__('adminP.points')}} : {{(PiketLog::where('order_u', $allOrds->id)->first()->piket) * -1}} ( <strong>{{(PiketLog::where('order_u', $allOrds->id)->first()->piket)*0.01}} {{__('adminP.currencyShow')}} </strong> )</p>
                                            @endif
                                        @endif
                                    </div>


                                    @if($allOrds->porosia != '')
                                        @foreach(explode('---8---',$allOrds->porosia) as $produkti)
                                        
                                            <?php $prod = explode('-8-', $produkti); ?>
                                            
                                            <div style="width:59.5%; border-bottom:1px solid lightgray;">
                                                <p style="font-size:21px;"> {{$prod[3]}}X <strong>{{$prod[0]}}</strong>
                                                    @if($prod[5] != "" && $prod[5] != "empty")
                                                        ( {{$prod[5]}} )
                                                    @endif
                                                </p> 
                                                <p style="margin-top:-20px;">{{$prod[1]}}</p> 
                                                @if($prod[6] != '')
                                                <p  style="margin-top:-20px; opacity:0.75">{{__('adminP.comment')}} : {{$prod[6]}}</p>
                                                @endif
                                            </div>
                                            <div style="width:25%; border-bottom:1px solid lightgray;">
                                                <?php
                                                    $eStep = 1;
                                                ?>
                                                @if($prod[2] != 'empty')
                                                    @foreach(explode('--0--', $prod[2]) as $ex)
                                                            @if(!empty($ex) ||$ex != "")
                                                                @if($eStep++ == 1)
                                                                    <p>{{explode('||', $ex)[0]}}</p>
                                                                @else
                                                                    <p style="margin-top:-15px;">{{explode('||', $ex)[0]}}</p>
                                                                @endif
                                                            @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div style="width:15%; border-bottom:1px solid lightgray;">
                                                <p class="pl-3">{{$prod[4]/$prod[3]}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                                @if($prod[3] > 1)
                                                <p class="pl-3" style="margin-top:-13px;" >{{$prod[4]}} <sup>{{__('adminP.currencyShow')}</sup>  ({{$prod[3]}}x) </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif


                                    @if($allOrds->statusi == 0)
                                        <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#ChStatStreetTerrasse{{$allOrds->id}}">
                                            {{__('adminP.waitingLine')}} 
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 1)
                                        <button class="btn btn-info btn-block" data-toggle="modal"  data-target="#ChStatStreetTerrasse{{$allOrds->id}}">
                                            {{__('adminP.confirmed')}}
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 2)
                                        <button class="btn btn-danger btn-block" data-toggle="modal"  data-target="#ChStatStreetTerrasse{{$allOrds->id}}">
                                            {{__('adminP.canceled')}}
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 3)
                                        <button class="btn btn-success btn-block" data-toggle="modal"  data-target="#ChStatStreetTerrasse{{$allOrds->id}}">
                                            {{__('adminP.finished')}}
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @endif

                                </div>
                            @endforeach
                        
                    </div>















      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('adminP.close')}}</button>
      </div>

    </div>
  </div>
</div>
@endforeach












































@foreach(TableQrcode::where('Restaurant',$resFor)->get() as $tablesAll)
    @if($tablesAll->kaTab != 0)
        
        <div class="modal" id="tabOrder{{$tablesAll->tableNr}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header" style="width:100%;">
                        <h4 class="modal-title">

                            <button id="payAllProd{{$tablesAll->tableNr}}" class="btn btn-success"  onclick="payAllProds('{{$tablesAll->tableNr}}','{{Auth::user()->sFor}}')"
                                style="margin-right:150px;">{{__('adminP.payForAllProducts')}}
                            </button>
                            <button id="paySelProd{{$tablesAll->tableNr}}" class="btn btn-success" onclick="paySelProds('{{$tablesAll->tableNr}}','{{Auth::user()->sFor}}')"
                                style="margin-right:125px; display:none;"> {{__('adminP.payForSelectedProducts')}}
                            </button>
                           
                            <a href="{{ route('dash.addNewProductOrPage',['tableNr' => $tablesAll->tableNr]) }}" class="btn btn-outline-success mr-4" ><strong>{{__('adminP.newProduct')}}</strong></a>
                            
                            <span style="font-size:30px;"><strong> {{__('adminP.table')}} : {{$tablesAll->tableNr}}</strong></span>
                            <span class="ml-5"> {{__('adminP.total')}} : {{TabOrder::where([['tabCode',$tablesAll->kaTab],['status','<',2]])->get()->sum('OrderQmimi')}} <sup>{{__('adminP.currencyShow')}}</sup></span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">✖</button>
                    </div>

                    
                            <div class="d-flex flex-wrap ml-3 mr-3 mt-2 mb-2 justify-content-between" id="mesageDivAdTCl{{$tablesAll->id}}">
                                <p id="sendMsgAlertS{{$tablesAll->id}}" class=" alert alert-success textcenter" style="width:20%;; display:none;">{{__('adminP.messageSent')}}</p>
                                <p id="sendMsgAlertE{{$tablesAll->id}}" class=" alert alert-danger textcenter" style=" width:20%; display:none;">
                                    {{__('adminP.writeValidMessage')}}</p>

                                <p style="width:20%;" id="sendMsgAlert{{$tablesAll->id}}"></p>
                                <input type="text" style="width:45%; border-radius:15px; border:1px solid rgb(72,81,87,0.7);" id="tableMessageIn{{$tablesAll->id}}"
                                    class="pl-3" placeholder="{{__('adminP.theMessage')}}">
                                <button class="btn btn-info" style="width:15%; height:100%;" 
                                    onclick="sendMsgToCl('{{$tablesAll->tableNr}}','{{$tablesAll->Restaurant}}','{{$tablesAll->id}}')">{{__('adminP.send')}}</button>
                                <p style="width:20%;"></p>
                            </div>


                            <input type="hidden" id="closeOrSelected{{$tablesAll->tableNr}}" val="">
                    <!-- Modal body -->
                    <div class="modal-body " id="tabOrderBody{{$tablesAll->tableNr}}">
                    <?php $allTabOrder = TabOrder::where([['tabCode',$tablesAll->kaTab],['status','<',2]])
                    ->whereIn('tableNr', [40, 41, 42, 43, 44, 45, 70, 71, 72, 73, 80, 81, 82, 83, 90, 91, 92, 99, 100, 101, 102, 200])->get()->sortByDesc('created_at'); ?>

                        @foreach($allTabOrder as $oneTOrder)
                            @if($oneTOrder->status == 0)
                                <div class="d-flex flex-wrap justify-content-between p-2 mb-1" style="border:1px solid gray; border-radius:15px; background-color:rgb(196, 245, 243)">
                            @elseif($oneTOrder->status == 1)
                                <div class="d-flex flex-wrap justify-content-between p-2 mb-1" style="border:1px solid gray; border-radius:15px; background-color:rgb(159, 245, 188)">
                            @elseif($oneTOrder->status == 9)
                                <div class="d-flex flex-wrap justify-content-between p-2 mb-1" style="border:1px solid gray; border-radius:15px; background-color:rgb(252, 134, 134)">
                            @endif
                        
                                <div style="width:15%; font-size:18px;">
                                    <p class="pb-1 pt-1">{{__('adminP.time')}}: <strong>{{substr(explode(' ',$oneTOrder->created_at)[1], 0, 5)}}</strong></p>
                                    <p style="margin-top:-15px;">{{__('adminP.total')}}: <strong>{{$oneTOrder->OrderQmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong> </p>
                                    <?php $tabVer  = tabVerificationPNumbers::where('tabOrderId',$oneTOrder->id)->first();?>
                                    <p style="margin-top:-15px;"><strong>{{$tabVer->phoneNr}}</strong></p>
                                </div>

                                <div style="width:45%;" class="pl-1">
                                    <h3>{{$oneTOrder->OrderSasia}}X <strong>{{$oneTOrder->OrderEmri}}</strong></h3>
                                    <p>{{$oneTOrder->OrderPershkrimi}}</p>
                                    @if($oneTOrder->OrderType != 'empty')
                                        <p style="font-size:18px; margin-top:-5px;"><strong>{{__('adminP.type')}}:</strong> {{explode('||',$oneTOrder->OrderType)[0]}} </p>
                                    @endif
                                    @if($oneTOrder->OrderKomenti)
                                        <p style="font-size:15px; margin-top:-10px;"><strong>{{__('adminP.comment')}}:</strong> {{$oneTOrder->OrderKomenti}} </p>
                                    @endif
                                </div>
                                <div style="width:25%;" class="d-flex flex-wrap">
                                    <?php $countEx = 1;?>
                                    @if($oneTOrder->OrderExtra != null)
                                        @foreach(explode('--0--',$oneTOrder->OrderExtra) as $oneOExt)
                                            @if($oneOExt != '' && $oneOExt != 'empty')
                                                @if($countEx++ == 1)
                                                    <p style="width:70%;">{{explode('||',$oneOExt)[0]}}</p>
                                                    <p style="width:30%;">{{explode('||',$oneOExt)[1]}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                                @else
                                                    <p style="width:70%; margin-top:-20px;">{{explode('||',$oneOExt)[0]}}</p>
                                                    <p style="width:30%; margin-top:-20px;">{{explode('||',$oneOExt)[1]}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div style="width:15%">
                                    @if($oneTOrder->status == 0)
                                        <button class=" ml-1 mr-1  btn btn-block btn-dark" onclick="chStatusTabO('{{$tablesAll->tableNr}}','{{$oneTOrder->id}}')">
                                            {{__('adminP.waitingLine')}} </button>
                                        <button class=" ml-1 mr-1  btn btn-block" onclick="showRemoveTO('{{$oneTOrder->id}}')"> 
                                            <i class="fas fa-ban"></i> <strong>{{__('adminP.extinguish')}}</strong> </button>
                                    @elseif($oneTOrder->status == 1)
                                        <button class=" ml-1 mr-1 btn btn-block" style="margin-top:-10px;"> {{__('adminP.sendOrder')}} </button>
                                        <button class=" ml-1 mr-1  btn btn-block" onclick="showRemoveTO('{{$oneTOrder->id}}')" style="margin-top:-10px;"> 
                                            <i class="fas fa-ban"></i> <strong>{{__('adminP.extinguish')}}</strong> </button>
                                    @elseif($oneTOrder->status == 9)
                                        <button class=" ml-1 mr-1 btn btn-block" style="margin-top:-10px;"> {{__('adminP.orderCancelled')}} </button>
                                    @endif
                                    <button class="closeOrderBtnClassNotSelected" id="closeOrBtn{{$oneTOrder->id}}"
                                             onclick="closeOrSelect('{{$tablesAll->tableNr}}','{{$oneTOrder->id}}')">
                                            {{__('adminP.pay')}}
                                    </button> 
                                </div>


                                <div style="width:60%;" id="beforeCodeSpace60{{$oneTOrder->id}}"></div>
                                <div style="width:40%; display:none !important;" class="d-flex justify-content-around codeForTabOrderRemoveDiv"
                                     id="codeForTabOrderRemoveDiv{{$oneTOrder->id}}" >

                                    <input type="text" placeholder="" class="text-center pt-1 pb-1" style="border-radius:20px; width:70%;" id="codeForTabOrderRemove{{$oneTOrder->id}}">
                                    <button style="margin:0px;" class="btn" onclick="sendCodeForTabOrderRemove('{{$oneTOrder->id}}','{{$tablesAll->tableNr}}')">
                                        {{__('adminP.send')}}</button>

                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach


<script>
    
    function closeOrSelect(tNr,tOId){
        if($('#closeOrSelected'+tNr).val() == ''){
            $('#closeOrSelected'+tNr).val(tOId);
            $('#payAllProd'+tNr).hide(1);
            $('#paySelProd'+tNr).show(1);
        }else{
            var allSel = $('#closeOrSelected'+tNr).val();
            $('#closeOrSelected'+tNr).val(allSel+'||'+tOId);
        }
        $('#closeOrBtn'+tOId).attr('class','closeOrderBtnClassSelected');   
        $('#closeOrBtn'+tOId).attr('onclick','closeOrRemove(\''+tNr+'\',\''+tOId+'\')');
    }
    function closeOrRemove(tNr,tOId){
        var allSel = $('#closeOrSelected'+tNr).val();
        var selBuild = '';
        if(allSel.includes('||')){
            $.each(allSel.split('||'), function( index, value ) {
                if(value != tOId){ if(selBuild == ''){ selBuild = value; }else{ selBuild += '||'+value;}}
            });
            $('#closeOrSelected'+tNr).val(selBuild);
        }else{
            $('#closeOrSelected'+tNr).val('');
           
            $('#payAllProd'+tNr).show(1);
            $('#paySelProd'+tNr).hide(1);
        }
        $('#closeOrBtn'+tOId).attr('class','closeOrderBtnClassNotSelected');   
        $('#closeOrBtn'+tOId).attr('onclick','closeOrSelect(\''+tNr+'\',\''+tOId+'\')');
    }

    function payAllProds(tNr,resId){
        $.ajax({
			url: '{{ route("dash.closeAllProductsTab") }}',
			method: 'post',
			data: {
				tableNr: tNr,
				resId: resId,
				_token: '{{csrf_token()}}'
			},
			success: () => { location.reload(); },
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }
    function paySelProds(tNr,resId){
        $('#paySelProd'+tNr).prop('disabled',true);
        $.ajax({
			url: '{{ route("dash.closeSelectedProductsTab") }}',
			method: 'post',
			data: {
				tableNr: tNr,
				resId: resId,
                selProds : $('#closeOrSelected'+tNr).val(),
				_token: '{{csrf_token()}}'
			},
			success: (res) => { 
                res = res.replace(/\s/g, '');
                if(res == 'ref'){
                    location.reload();
                }else{
                    $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                    $('#paySelProd'+tNr).prop('disabled',false);
                    $('#payAllProd'+tNr).show(1);
                    $('#payAllProd'+tNr).prop('disabled',false);
                    $('#paySelProd'+tNr).hide(1);
                    $('#closeOrSelected'+tNr).val('');
                }
            },
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val();
			}
		});
    }



    function sendMsgToCl(tNr,theRes,tId){
        if($('#tableMessageIn'+tId).val() != '' && $('#tableMessageIn'+tId).val() != ' '){
            $.ajax({
                url: '{{ route("TabChngCli.MsgToUser") }}',
                method: 'post',
                data: {
                    tableNr: tNr,
                    res: theRes,
                    msg: $('#tableMessageIn'+tId).val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    
                    $('#sendMsgAlertS'+tId).show(1).delay(3000).hide(1);
                    $('#sendMsgAlert'+tId).hide(1).delay(3000).show(1);
                    setTimeout(function(){ 
                        $("#mesageDivAdTCl"+tId).load(location.href+" #mesageDivAdTCl"+tId+">*","");
                    }, 3000);
                },
                error: (error) => {
                    console.log(error);
                    alert($('#pleaseUpdateAndTryAgain').val());
                }
            });
        }else{
            $('#sendMsgAlertE'+tId).show(1).delay(3000).hide(1);
            $('#sendMsgAlert'+tId).hide(1).delay(3000).show(1);
        }
    }
    
    function showRemoveTO(tabOrId){
        $('.codeForTabOrderRemoveDiv').attr('style', 'width:40%; display:none !important;');
        $('#codeForTabOrderRemoveDiv'+tabOrId).show(300);
    }



    function sendCodeForTabOrderRemove(tOrId,tNr){
        if($('#codeForTabOrderRemove'+tOrId).val() == ''){
            $('#beforeCodeSpace60'+tOrId).html('<div class="alert-warning p-2 text-center" style="width:100%"><strong>'+$('#writeCode').val()+'</strong></div>');
        }else{
            $.ajax({
				url: '{{ route("cart.chStatTabOrderDelete") }}',
				method: 'post',
				data: {
                    id: tOrId,
                    codeFUser:$('#codeForTabOrderRemove'+tOrId).val(),
					_token: '{{csrf_token()}}'
				},
				success: (res) => {
                    res = res.replace(/\s/g, '');
                    if(res == 'wrongCode'){
                        $('#beforeCodeSpace60'+tOrId).html('<div class="alert-warning p-2 text-center" style="width:100%"><strong>'+$('#incorrectCode').val()+'</strong></div>')
                    }else{
                        $("#tabOrder"+tNr).load(location.href+" #tabOrder"+tNr+">*","");
                    }
				},
				error: (error) => {
					console.log(error);
				}
			});
        }
    }









    function chStatusTabO(tNr,toId){
        $.ajax({
			url: '{{ route("cart.chStatTabOrder") }}',
			method: 'post',
			data: {
				tableNr: tNr,
                tabOrId:toId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
			    $("#tabOrder"+tNr).load(location.href+" #tabOrder"+tNr+">*","");
			    $("#Street03layer1").load(location.href+" #Street03layer1>*","");
			    $(".otherMapsTop").load(location.href+" .otherMapsTop>*","");
			},
			error: (error) => {
				console.log(error);
				alert($('#oopsSomethingWrong').val())
			}
		});
    }
</script>



































@foreach(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('statusi','<',2)->get() as $order)
    @if(!empty($order))
        <div class="modal" id="ChStatStreetTerrasse{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{$order->userEmri}} <span style="color:gray">{ {{$order->userEmail}} }</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                @if(count(StatusWorker::where('toRes', $resFor)->get()) > 0 )
                    <div class="d-flex mb-3 pb-2" id="sWPhase1" >
                    
                        @foreach(StatusWorker::where('toRes', $resFor)->get()->sortByDesc('created_at') as $sWor)
                            <button onclick="setChByStreetTerrasse('{{$sWor->id}}')" id="statusWorkerButton{{$sWor->id}}" class="statusWorkerButtonAll btn backQr mr-1 ml-1"
                            style="width:24%;"> {{$sWor->emri}}</button>
                        @endforeach
                    
                    
                    </div>

                    <div class="d-flex mb-3" id="sWPhase2" style="display:none;">
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 0 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 3 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.waitingLine'), ['class' => 'form-control btn btn-warning btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 3 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.confirmed'), ['class' => 'form-control btn btn-info btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 2 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 3 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.canceled'), ['class' => 'form-control btn btn-danger btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 3 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.finished'), ['class' => 'form-control btn btn-success btn-block']) }}
                                
                            {{Form::close() }}
                        </div>
                    </div>
                @else




                    <div class="d-flex mb-3" >
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 0 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 3 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.waitingLine'), ['class' => 'form-control btn btn-warning btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 3 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.confirmed'), ['class' => 'form-control btn btn-info btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 2 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 3 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.canceled'), ['class' => 'form-control btn btn-danger btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 3 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.finished'), ['class' => 'form-control btn btn-success btn-block']) }}
                                
                            {{Form::close() }}
                        </div>
                    </div>
                @endif
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
 



<script>
    function setChByStreetTerrasse(wId){
    

        $('.statusWorkerButtonAll').attr('class','statusWorkerButtonAll btn backQr mr-1 ml-1')
        $('#statusWorkerButton'+wId).attr('class','statusWorkerButtonAll btn backQrSelected mr-1 ml-1')
        $('#sWPhase2').show();
        $('.statBtn').show(500);
        $('.chByInput').val(wId);
    }
</script>



































<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   width="100%"
   viewBox="0 0 1620 900"
   version="1.1"
   id="SVGRoot"
   sodipodi:docname="streetTerrasse.svg"
   inkscape:version="1.0.1 (3bc2e813f5, 2020-09-07)">
  <defs
     id="defs833" />
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="0.81"
     inkscape:cx="786.51311"
     inkscape:cy="465.23571"
     inkscape:document-units="px"
     inkscape:current-layer="layer1"
     inkscape:document-rotation="0"
     showgrid="false"
     inkscape:window-width="1920"
     inkscape:window-height="1017"
     inkscape:window-x="-8"
     inkscape:window-y="32"
     inkscape:window-maximized="1" />
  <metadata
     id="metadata836">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     inkscape:label="Layer 1"
     inkscape:groupmode="layer"
     id="Street03layer1">



    @if(TableQrcode::where([['tableNr',40],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',40],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.66568"';}
                else{echo ' style="fill:yellow;fill-opacity:0.66568"';}?>
             id="table40"
            width="166.01549"
            height="120.27145"
            x="57.341793"
            y="60.526306"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder40" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 40)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.66568"
            id="table40"
            width="166.01549"
            height="120.27145"
            x="57.341793"
            y="60.526306"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher40" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('40')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.66568"
       id="table40"
       width="166.01549"
       height="120.27145"
       x="57.341793"
       y="60.526306" />
    @endif







    @if(TableQrcode::where([['tableNr',41],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',41],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.66568"';}
                else{echo ' style="fill:yellow;fill-opacity:0.66568"';}?>
           id="table41"
            width="166.01549"
            height="120.27145"
            x="271.70245"
            y="61.423862" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder41" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 41)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.66568"
            id="table41"
            width="166.01549"
            height="120.27145"
            x="271.70245"
            y="61.423862" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher41" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('41')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.66568"
       id="table41"
       width="166.01549"
       height="120.27145"
       x="271.70245"
       y="61.423862" />
    @endif








    @if(TableQrcode::where([['tableNr',42],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',42],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.66568"';}
                else{echo ' style="fill:yellow;fill-opacity:0.66568"';}?>
            id="table42"
            width="166.01549"
            height="120.27145"
            x="268.05377"
            y="262.47464"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder42" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 42)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.66568"
            id="table42"
            width="166.01549"
            height="120.27145"
            x="268.05377"
            y="262.47464" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher42" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('42')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.66568"
       id="table42"
       width="166.01549"
       height="120.27145"
       x="268.05377"
       y="262.47464" />
    @endif








    @if(TableQrcode::where([['tableNr',44],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',44],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.66568"';}
                else{echo ' style="fill:yellow;fill-opacity:0.66568"';}?>
            id="table44"
            width="166.01549"
            height="120.27145"
            x="264.40506"
            y="450.95975"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder44" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 44)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.66568"
            id="table44"
            width="166.01549"
            height="120.27145"
            x="264.40506"
            y="450.95975"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher44" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('44')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.66568"
       id="table44"
       width="166.01549"
       height="120.27145"
       x="264.40506"
       y="450.95975" />
    @endif






    @if(TableQrcode::where([['tableNr',200],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',200],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:1.06967"';}
                else{echo ' style="fill:yellow;fill-opacity:1.06967"';}?>
            id="table200"
            width="166.01549"
            height="310.55167"
            x="54.605267"
            y="262.47467"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder200" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 200)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:1.06967"
            id="table200"
            width="166.01549"
            height="310.55167"
            x="54.605267"
            y="262.47467"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher200" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('200')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.06967"
       id="table200"
       width="166.01549"
       height="310.55167"
       x="54.605267"
       y="262.47467" />
    @endif






    @if(TableQrcode::where([['tableNr',43],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',43],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.66568"';}
                else{echo ' style="fill:yellow;fill-opacity:0.66568"';}?>
             id="table43"
            width="166.01549"
            height="120.27145"
            x="483.3266"
            y="258.88446"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder43" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 43)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.66568"
            id="table43"
            width="166.01549"
            height="120.27145"
            x="483.3266"
            y="258.88446"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher43" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('43')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.66568"
       id="table43"
       width="166.01549"
       height="120.27145"
       x="483.3266"
       y="258.88446" />
    @endif






   
    @if(TableQrcode::where([['tableNr',45],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',45],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.66568"';}
                else{echo ' style="fill:yellow;fill-opacity:0.66568"';}?>
            id="table45"
            width="166.01549"
            height="120.27145"
            x="483.3266"
            y="447.36957"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder45" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 45)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.66568"
            id="table45"
            width="166.01549"
            height="120.27145"
            x="483.3266"
            y="447.36957"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher45" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('45')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.66568"
       id="table45"
       width="166.01549"
       height="120.27145"
       x="483.3266"
       y="447.36957" />
    @endif






    @if(TableQrcode::where([['tableNr',70],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',70],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table70"
            width="117.7934"
            height="144.43187"
            x="735.28241"
            y="65.898384"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder70" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 70)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table70"
            width="117.7934"
            height="144.43187"
            x="735.28241"
            y="65.898384"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher70" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('70')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.614472"
       id="table70"
       width="117.7934"
       height="144.43187"
       x="735.28241"
       y="65.898384" />
    @endif







    @if(TableQrcode::where([['tableNr',80],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',80],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table80"
            width="117.7934"
            height="144.43187"
            x="734.48651"
            y="246.01343"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder80" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 80)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table80"
            width="117.7934"
            height="144.43187"
            x="734.48651"
            y="246.01343"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher80" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('80')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.614472"
       id="table80"
       width="117.7934"
       height="144.43187"
       x="734.48651"
       y="246.01343" />
    @endif






    @if(TableQrcode::where([['tableNr',90],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',90],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table90"
            width="117.7934"
            height="144.43187"
            x="732.89478"
            y="427.82764"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder90" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 90)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table90"
            width="117.7934"
            height="144.43187"
            x="732.89478"
            y="427.82764"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher90" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('90')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.614472"
       id="table90"
       width="117.7934"
       height="144.43187"
       x="732.89478"
       y="427.82764" />
    @endif





    @if(TableQrcode::where([['tableNr',71],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',71],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table71"
            width="117.7934"
            height="144.43187"
            x="947.78802"
            y="62.5"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder71" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 71)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table71"
            width="117.7934"
            height="144.43187"
            x="947.78802"
            y="62.5"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher71" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('71')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.614472"
       id="table71"
       width="117.7934"
       height="144.43187"
       x="947.78802"
       y="62.5" />
    @endif






   @if(TableQrcode::where([['tableNr',81],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',81],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table81"
            width="117.7934"
            height="144.43187"
            x="946.19623"
            y="249.4118"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder81" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 81)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table81"
            width="117.7934"
            height="144.43187"
            x="946.19623"
            y="249.4118"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher81" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('81')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.614472"
       id="table81"
       width="117.7934"
       height="144.43187"
       x="946.19623"
       y="249.4118" />
    @endif





    @if(TableQrcode::where([['tableNr',91],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',91],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table91"
            width="117.7934"
            height="144.43187"
            x="946.19629"
            y="431.22601"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder91" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 91)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table91"
            width="117.7934"
            height="144.43187"
            x="946.19629"
            y="431.22601"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher91" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('91')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.614472"
       id="table91"
       width="117.7934"
       height="144.43187"
       x="946.19629"
       y="431.22601" />
    @endif






    @if(TableQrcode::where([['tableNr',72],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',72],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table72"
            width="117.7934"
            height="144.43187"
            x="1165.8652"
            y="64.199181"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder72" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 72)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table72"
            width="117.7934"
            height="144.43187"
            x="1165.8652"
            y="64.199181"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher72" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('72')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.614472"
       id="table72"
       width="117.7934"
       height="144.43187"
       x="1165.8652"
       y="64.199181" />
    @endif






    @if(TableQrcode::where([['tableNr',82],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',82],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table82"
            width="117.7934"
            height="144.43187"
            x="1167.4569"
            y="247.71262"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder82" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 82)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table82"
            width="117.7934"
            height="144.43187"
            x="1167.4569"
            y="247.71262"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher82" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('82')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.614472"
       id="table82"
       width="117.7934"
       height="144.43187"
       x="1167.4569"
       y="247.71262" />
    @endif







    @if(TableQrcode::where([['tableNr',92],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',92],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table92"
            width="117.7934"
            height="144.43187"
            x="1169.0487"
            y="429.52686"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder92" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 92)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table92"
            width="117.7934"
            height="144.43187"
            x="1169.0487"
            y="429.52686"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher92" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('92')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.614472"
       id="table92"
       width="117.7934"
       height="144.43187"
       x="1169.0487"
       y="429.52686" />
    @endif







    @if(TableQrcode::where([['tableNr',73],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',73],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.614472"';}
                else{echo ' style="fill:yellow;fill-opacity:0.614472"';}?>
            id="table73"
            width="115.54388"
            height="155.67972"
            x="1401.3158"
            y="142.76315"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder73" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 73)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.614472"
            id="table73"
            width="115.54388"
            height="155.67972"
            x="1401.3158"
            y="142.76315"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher73" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('73')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.631829"
       id="table73"
       width="115.54388"
       height="155.67972"
       x="1401.3158"
       y="142.76315" />
    @endif







    @if(TableQrcode::where([['tableNr',83],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',83],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.631829"';}
                else{echo ' style="fill:yellow;fill-opacity:0.631829"';}?>
            id="table83"
            width="115.54388"
            height="155.67972"
            x="1402.8771"
            y="351.55713"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder83" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 83)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.631829"
            id="table83"
            width="115.54388"
            height="155.67972"
            x="1402.8771"
            y="351.55713"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher83" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('83')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.631829"
       id="table83"
       width="115.54388"
       height="155.67972"
       x="1402.8771"
       y="351.55713" />
    @endif







    @if(TableQrcode::where([['tableNr',102],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',102],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.679515"';}
                else{echo ' style="fill:yellow;fill-opacity:0.679515"';}?>
            id="table102"
            width="82.649139"
            height="251.73235"
            x="1390.9122"
            y="618.21277"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder102" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 102)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.679515"
            id="table102"
            width="82.649139"
            height="251.73235"
            x="1390.9122"
            y="618.21277"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher102" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('102')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.679515"
       id="table102"
       width="82.649139"
       height="251.73235"
       x="1390.9122"
       y="618.21277" />
    @endif






    @if(TableQrcode::where([['tableNr',101],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',101],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.679515"';}
                else{echo ' style="fill:yellow;fill-opacity:0.679515"';}?>
            id="table101"
            width="82.649139"
            height="251.73235"
            x="1207.3596"
            y="613.60748"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder101" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 101)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.679515"
            id="table101"
            width="82.649139"
            height="251.73235"
            x="1207.3596"
            y="613.60748"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher101" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('101')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.679515"
       id="table101"
       width="82.649139"
       height="251.73235"
       x="1207.3596"
       y="613.60748" />
    @endif





    @if(TableQrcode::where([['tableNr',100],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',100],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.519732"';}
                else{echo ' style="fill:yellow;fill-opacity:0.519732"';}?>
            id="table100"
            width="148.43861"
            height="81.995506"
            x="917.22809"
            y="624.79169"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder100" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 100)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.519732"
            id="table100"
            width="148.43861"
            height="81.995506"
            x="917.22809"
            y="624.79169"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher100" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('100')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.519732"
       id="table100"
       width="148.43861"
       height="81.995506"
       x="917.22809"
       y="624.79169" />
    @endif





    @if(TableQrcode::where([['tableNr',99],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',99],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.528865"';}
                else{echo ' style="fill:yellow;fill-opacity:0.528865"';}?>
            id="table99"
            width="153.70177"
            height="81.995506"
            x="697.49121"
            y="624.79175"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder99" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 99)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.528865"
            id="table99"
            width="153.70177"
            height="81.995506"
            x="697.49121"
            y="624.79175"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher99" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('99')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.528865"
       id="table99"
       width="153.70177"
       height="81.995506"
       x="697.49121"
       y="624.79175" />
    @endif














    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="109.21053"
       y="136.84212"
       id="tableTxt40"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse40"><tspan
         sodipodi:role="line"
         id="tspan2201"
         x="109.21053"
         y="136.84212">40</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="330.91293"
       y="132.48862"
       id="tableTxt41"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse41"><tspan
         sodipodi:role="line"
         id="tspan2205"
         x="330.91293"
         y="132.48862">41</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="322.3522"
       y="338.98636"
       id="tableTxt42"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse42"><tspan
         sodipodi:role="line"
         id="tspan2209"
         x="322.3522"
         y="338.98636">42</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="540.69202"
       y="336.35477"
       id="tableText43"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse43"><tspan
         sodipodi:role="line"
         id="tspan2213"
         x="540.69202"
         y="336.35477">43</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="324.57764"
       y="525.99091"
       id="tableText44"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse44"><tspan
         sodipodi:role="line"
         id="tspan2217"
         x="324.57764"
         y="525.99091">44</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="543.07996"
       y="523.11566"
       id="tableText45"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse45"><tspan
         sodipodi:role="line"
         id="tspan2221"
         x="543.07996"
         y="523.11566">45</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="98.684219"
       y="439.47366"
       id="tableText200"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse200"><tspan
         sodipodi:role="line"
         id="tspan2225"
         x="98.684219"
         y="439.47366">200</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="768.42102"
       y="149.99998"
       id="tableText70"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse70"><tspan
         sodipodi:role="line"
         id="tspan2229"
         x="768.42102"
         y="149.99998">70</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="982.89471"
       y="148.6842"
       id="tableText71"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse71"><tspan
         sodipodi:role="line"
         id="tspan2233"
         x="982.89471"
         y="148.6842">71</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1194.7368"
       y="152.63158"
       id="tableText72"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse72"><tspan
         sodipodi:role="line"
         id="tspan2237"
         x="1194.7368"
         y="152.63158">72</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1432.8948"
       y="239.47368"
       id="tableText73"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse73"><tspan
         sodipodi:role="line"
         id="tspan2241"
         x="1432.8948"
         y="239.47368">73</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="772.36841"
       y="334.21051"
       id="tableText80"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse80"><tspan
         sodipodi:role="line"
         id="tspan2245"
         x="772.36841"
         y="334.21051">80</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="985.52637"
       y="335.52631"
       id="tableText81"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse81"><tspan
         sodipodi:role="line"
         id="tspan2249"
         x="985.52637"
         y="335.52631">81</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1202.6316"
       y="335.52631"
       id="tableText82"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse82"><tspan
         sodipodi:role="line"
         id="tspan2253"
         x="1202.6316"
         y="335.52631">82</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1435.5264"
       y="442.10526"
       id="tableText83"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse83"><tspan
         sodipodi:role="line"
         id="tspan2257"
         x="1435.5264"
         y="442.10526">83</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="768.42108"
       y="515.78949"
       id="tableText90"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse90"><tspan
         sodipodi:role="line"
         id="tspan2261"
         x="768.42108"
         y="515.78949">90</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="978.94739"
       y="519.73682"
       id="tableText91"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse91"><tspan
         sodipodi:role="line"
         id="tspan2265"
         x="978.94739"
         y="519.73682">91</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1205.2632"
       y="518.42108"
       id="tableText92"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse92"><tspan
         sodipodi:role="line"
         id="tspan2269"
         x="1205.2632"
         y="518.42108">92</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="752.63159"
       y="680.26318"
       id="tableText99"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse99"><tspan
         sodipodi:role="line"
         id="tspan2273"
         x="752.63159"
         y="680.26318">99</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="955.26312"
       y="680.26312"
       id="tableText100"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse100"><tspan
         sodipodi:role="line"
         id="tspan2277"
         x="955.26312"
         y="680.26312">100</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1211.8422"
       y="760.52631"
       id="tableText101"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse101"><tspan
         sodipodi:role="line"
         id="tspan2281"
         x="1211.8422"
         y="760.52631">101</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1394.7368"
       y="763.15784"
       id="tableText102"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetTerrasse102"><tspan
         sodipodi:role="line"
         id="tspan2285"
         x="1394.7368"
         y="763.15784">102</tspan></text>
  </g>
</svg>

<script>
    $("#SVGRoot").width(screen.width - 400);
</script>
