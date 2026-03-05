
<?php
    use App\Orders;
    use App\PiketLog;
    use App\StatusWorker;
    use Carbon\Carbon;
    use App\TabOrder;
    use App\TableQrcode;
    use App\FreeProducts;
    use App\tabVerificationPNumbers;

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



























@foreach(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('statusi','<',2)->get() as $ords )
<!-- The Modal -->
<div class="modal" id="tableModWStreetNichtRaucher{{$ords->nrTable}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
                                <span style="font-size:30px;"><strong> {{__('adminP.table'')}}: {{$ords->nrTable}}</strong></span>
                            @else
                                <span style="font-size:30px;" ><strong>{{__('adminP.bringAway')}}</strong></span>
                            @endif

                            @if(PiketLog::where('order_u', $ords->id)->first() != null)
                                @if(PiketLog::where('order_u', $ords->id)->first()->piket < 0)
                                <br>
                                    <span> {{__('adminP.usedPoints')}} : {{(PiketLog::where('order_u', $ords->id)->first()->piket) * -1}} ( <strong>{{(PiketLog::where('order_u', $ords->id)->first()->piket)*0.01}} {{__('adminP.currencyShow')}} </strong> )</span>         
                                @endif
                            @endif
                            @if($ords->tipPer != 0)
                                   <br> <span> {{__('adminP.waiterTip')}}: <strong>{{$ords->tipPer}} {{__('adminP.currencyShow')}}</strong> </span>
                            @endif
                            <hr>
                           
                        
                           
                            <span> {{__('adminP.total')}} : {{Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', $ords->nrTable)->where('statusi','<',2)->get()->sum('shuma')}} <sup>CHF</sup></span>
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
                         @foreach(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', $ords->nrTable)->where('statusi','<',2)->get() as $allOrds)
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
                                                <p style="font-size:21px;">{{$prod[3]}}X <strong>{{$prod[0]}}</strong>
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
                                                <p class="pl-3" style="margin-top:-13px;" >{{$prod[4]}} <sup>{{__('adminP.currencyShow')}}</sup>  ({{$prod[3]}}x) </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif


                                    @if($allOrds->statusi == 0)
                                        <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#ChStatStreetNichtRaucher{{$allOrds->id}}">
                                            {{__('adminP.waitingLine')}} 
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 1)
                                        <button class="btn btn-info btn-block" data-toggle="modal"  data-target="#ChStatStreetNichtRaucher{{$allOrds->id}}">
                                            {{__('adminP.confirmed')}}
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 2)
                                        <button class="btn btn-danger btn-block" data-toggle="modal"  data-target="#ChStatStreetNichtRaucher{{$allOrds->id}}">
                                            {{__('adminP.canceled')}}
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 3)
                                        <button class="btn btn-success btn-block" data-toggle="modal"  data-target="#ChStatStreetNichtRaucher{{$allOrds->id}}">
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

























@foreach(TableQrcode::where('Restaurant',Auth::user()->sFor)->get() as $tablesAll)
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
                    ->whereIn('tableNr', [21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34])->get()->sortByDesc('created_at'); ?>

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
                                            {{__('adminP.waitingLine')}}</button>
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
				alert($('#pleaseUpdateAndTryAgain').val());
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
			    $("#Street02layer1").load(location.href+" #Street02layer1>*","");
			    $(".otherMapsTop").load(location.href+" .otherMapsTop>*","");

                
                
			},
			error: (error) => {
				console.log(error);
				alert($('#oopsSomethingWrong').val())
			}
		});
    }
</script>































@foreach(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('statusi','<',2)->get() as $order)
    @if(!empty($order))
        <div class="modal" id="ChStatStreetNichtRaucher{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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

                @if(count(StatusWorker::where('toRes', Auth::user()->sFor)->get()) > 0 )
                    <div class="d-flex mb-3 pb-2" id="sWPhase1" >
                    
                        @foreach(StatusWorker::where('toRes', Auth::user()->sFor)->get()->sortByDesc('created_at') as $sWor)
                            <button onclick="setChByStreetNichtRaucher('{{$sWor->id}}')" id="statusWorkerButton{{$sWor->id}}" class="statusWorkerButtonAll btn backQr mr-1 ml-1"
                            style="width:24%;"> {{$sWor->emri}}</button>
                        @endforeach
                    
                    
                    </div>

                    <div class="d-flex mb-3" id="sWPhase2" style="display:none;">
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 0 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 2 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.waitingLine'), ['class' => 'form-control btn btn-warning btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 2 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.confirmed'), ['class' => 'form-control btn btn-info btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 2 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 2 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.canceled'), ['class' => 'form-control btn btn-danger btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 2 , ['class' => 'form-control']) }}
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
                                {{ Form::hidden('backToMap', 2 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.waitingLine'), ['class' => 'form-control btn btn-warning btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 2 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.confirmed'), ['class' => 'form-control btn btn-info btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 2 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 2 , ['class' => 'form-control']) }}
                                {{ Form::submit(__('adminP.canceled'), ['class' => 'form-control btn btn-danger btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                {{ Form::hidden('backToMap', 2 , ['class' => 'form-control']) }}
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
    function setChByStreetNichtRaucher(wId){
    

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
   sodipodi:docname="streetNichtRaucher.svg"
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
     inkscape:zoom="0.76"
     inkscape:cx="793.31549"
     inkscape:cy="463.42521"
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
     id="Street02layer1">




    @if(TableQrcode::where([['tableNr',23],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',23],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.75402"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.75402"';}?>
            id="table23"
            width="188.1579"
            height="164.47368"
            x="1373.6842"
            y="52.631577"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder23" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 23)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.75402"
            id="table23"
            width="188.1579"
            height="164.47368"
            x="1373.6842"
            y="52.631577"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher23" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('23')"
       style="fill:#27beafed;fill-opacity:0.5;stroke-width:1.75402"
       id="table23"
       width="188.1579"
       height="164.47368"
       x="1373.6842"
       y="52.631577" />
    @endif




 @if(TableQrcode::where([['tableNr',21],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',21],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.18685"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.18685"';}?>
            id="table21"
            width="163.1579"
            height="86.842102"
            x="234.86839"
            y="144.07895"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder21" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 21)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.18685"
            id="table21"
            width="163.1579"
            height="86.842102"
            x="234.86839"
            y="144.07895"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher23" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('21')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.18685"
       id="table21"
       width="163.1579"
       height="86.842102"
       x="234.86839"
       y="144.07895" />
    @endif






    @if(TableQrcode::where([['tableNr',22],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',22],['Restaurant',$resFor]])->first()->kaTab; ?>

        <circle
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.0115;"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.0115;"';}?>
             id="table22"
            cx="968.07892"
            cy="281.23685"
            r="86.5" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder22" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 22)->where('statusi','<',2)->get()->count() > 0 ) 
        <circle
            style="fill:green;fill-opacity:1.0115; stroke-width:1.0115"
            id="table22"
            cx="968.07892"
            cy="281.23685"
            r="86.5" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher22" />
    @else
    <circle
        onclick="sendToAddOrderAdmin('22')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.0115"
       id="table22"
       cx="968.07892"
       cy="281.23685"
       r="86.5" />
    @endif






    @if(TableQrcode::where([['tableNr',33],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',33],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.3452"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.3452"';}?>
           id="table33"
            width="97.62822"
            height="186.44258"
            x="1487.8981"
            y="681.97852" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder33" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 33)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.3452"
            id="table33"
            width="97.62822"
            height="186.44258"
            x="1487.8981"
            y="681.97852" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher33" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('33')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.3452"
       id="table33"
       width="97.62822"
       height="186.44258"
       x="1487.8981"
       y="681.97852" />
    @endif







    @if(TableQrcode::where([['tableNr',32],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',32],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.874652"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.874652"';}?>
            id="table32"
            width="110.78612"
            height="69.459549"
            x="1344.6069"
            y="635.60382" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder32" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 32)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.874652"
            id="table32"
            width="110.78612"
            height="69.459549"
            x="1344.6069"
            y="635.60382" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher32" />  
    @else
    <rect
        onclick="sendToAddOrderAdmin('32')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.874652"
       id="table32"
       width="110.78612"
       height="69.459549"
       x="1344.6069"
       y="635.60382" />
    @endif






     @if(TableQrcode::where([['tableNr',31],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',31],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.874652"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.874652"';}?>
            id="table31"
            width="110.78612"
            height="69.459549"
            x="1203.8174"
            y="637.63861"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder31" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 31)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.874652"
            id="table31"
            width="110.78612"
            height="69.459549"
            x="1203.8174"
            y="637.63861" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher31" /> 
    @else
    <rect
        onclick="sendToAddOrderAdmin('31')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.874652"
       id="table31"
       width="110.78612"
       height="69.459549"
       x="1203.8174"
       y="637.63861" />
    @endif






   @if(TableQrcode::where([['tableNr',30],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',30],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.05194"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.05194"';}?>
            id="table30"
            width="70.276512"
            height="158.38849"
            x="1100.9059"
            y="548.70966" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder30" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 30)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.05194"
            id="table30"
            width="70.276512"
            height="158.38849"
            x="1100.9059"
            y="548.70966" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher30" /> 
    @else
    <rect
        onclick="sendToAddOrderAdmin('30')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.05194"
       id="table30"
       width="70.276512"
       height="158.38849"
       x="1100.9059"
       y="548.70966" />
    @endif




    @if(TableQrcode::where([['tableNr',29],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',29],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.07081"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.07081"';}?>
            id="table29"
            width="166.04927"
            height="69.459549"
            x="907.76483"
            y="636.32281" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder29" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 29)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.07081"
            id="table29"
            width="166.04927"
            height="69.459549"
            x="907.76483"
            y="636.32281" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher29" /> 
    @else
    <rect
        onclick="sendToAddOrderAdmin('29')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.07081"
       id="table29"
       width="166.04927"
       height="69.459549"
       x="907.76483"
       y="636.32281" />
    @endif






    @if(TableQrcode::where([['tableNr',34],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',34],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.07531"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.07531"';}?>
            id="table34"
            width="110.78612"
            height="104.98586"
            x="1186.7122"
            y="354.74393"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder34" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 34)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.07531"
            id="table34"
            width="110.78612"
            height="104.98586"
            x="1186.7122"
            y="354.74393" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher34" /> 
    @else
    <rect
        onclick="sendToAddOrderAdmin('34')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.07531"
       id="table34"
       width="110.78612"
       height="104.98586"
       x="1186.7122"
       y="354.74393" />
    @endif





    @if(TableQrcode::where([['tableNr',26],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',26],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.01416"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.01416"';}?>
            id="table26"
            width="148.944"
            height="69.459549"
            x="132.76482"
            y="633.69128"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder26" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 26)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.01416"
            id="table26"
            width="148.944"
            height="69.459549"
            x="132.76482"
            y="633.69128"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher26" /> 
    @else
    <rect
        onclick="sendToAddOrderAdmin('26')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.01416"
       id="table26"
       width="148.944"
       height="69.459549"
       x="132.76482"
       y="633.69128" />
    @endif







    @if(TableQrcode::where([['tableNr',27],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',27],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.05194"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.05194"';}?>
            id="table27"
            width="70.276512"
            height="158.38849"
            x="320.12491"
            y="545.80579"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder27" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 27)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.05194"
            id="table27"
            width="70.276512"
            height="158.38849"
            x="320.12491"
            y="545.80579"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher27" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('27')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.05194"
       id="table27"
       width="70.276512"
       height="158.38849"
       x="320.12491"
       y="545.80579" />
    @endif




    @if(TableQrcode::where([['tableNr',28],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',28],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.13667"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.13667"';}?>
            id="table28"
            width="187.1019"
            height="69.459549"
            x="426.18591"
            y="633.69128"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder28" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 28)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.13667"
            id="table28"
            width="187.1019"
            height="69.459549"
            x="426.18591"
            y="633.69128"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher28" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('28')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.13667"
       id="table28"
       width="187.1019"
       height="69.459549"
       x="426.18591"
       y="633.69128" />
    @endif









    @if(TableQrcode::where([['tableNr',25],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',25],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.701742"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.701742"';}?>
            id="table25"
            width="71.312424"
            height="69.459549"
            x="46.580627"
            y="537.63861"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder25" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 25)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.701742"
            id="table25"
            width="71.312424"
            height="69.459549"
            x="46.580627"
            y="537.63861"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher25" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('25')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.701742"
       id="table25"
       width="71.312424"
       height="69.459549"
       x="46.580627"
       y="537.63861" />
    @endif



    @if(TableQrcode::where([['tableNr',24],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',24],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.701742"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.701742"';}?>
            id="table24"
            width="71.312424"
            height="69.459549"
            x="531.44904"
            y="352.11234"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder24" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 24)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.701742"
            id="table24"
            width="71.312424"
            height="69.459549"
            x="531.44904"
            y="352.11234"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher24" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('24')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.701742"
       id="table24"
       width="71.312424"
       height="69.459549"
       x="531.44904"
       y="352.11234" />
    @endif













    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="290.78949"
       y="200.00002"
       id="tableTxt21"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher21"><tspan
         sodipodi:role="line"
         id="tspan1920"
         x="290.78949"
         y="200.00002">21</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="943.42102"
       y="296.05264"
       id="tableTxt22"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher22"><tspan
         sodipodi:role="line"
         id="tspan1924"
         x="943.42102"
         y="296.05264">22</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1219.7368"
       y="422.36841"
       id="tableTxt34"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher34"><tspan
         sodipodi:role="line"
         id="tspan1928"
         x="1219.7368"
         y="422.36841">34</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1443.421"
       y="148.6842"
       id="tableTxt23"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher23"><tspan
         sodipodi:role="line"
         id="tspan1932"
         x="1443.421"
         y="148.6842">23</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="542.10522"
       y="401.3158"
       id="tableTxt24"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher24"><tspan
         sodipodi:role="line"
         id="tspan1936"
         x="542.10522"
         y="401.3158">24</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="57.894737"
       y="585.52631"
       id="tableTxt25"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher25"><tspan
         sodipodi:role="line"
         id="tspan1940"
         x="57.894737"
         y="585.52631">25</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="177.63158"
       y="682.89478"
       id="tableTxt26"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher26"><tspan
         sodipodi:role="line"
         id="tspan1944"
         x="177.63158"
         y="682.89478">26</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="331.57895"
       y="647.36847"
       id="tableTxt27"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher27"><tspan
         sodipodi:role="line"
         id="tspan1948"
         x="331.57895"
         y="647.36847">27</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="488.1579"
       y="684.21051"
       id="tableTxt28"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher28"><tspan
         sodipodi:role="line"
         id="tspan1952"
         x="488.1579"
         y="684.21051">28</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="968.42102"
       y="685.52631"
       id="tableTxt29"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher29"><tspan
         sodipodi:role="line"
         id="tspan1956"
         x="968.42102"
         y="685.52631">29</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1111.8422"
       y="648.6842"
       id="tableTxt30"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher30"><tspan
         sodipodi:role="line"
         id="tspan1960"
         x="1111.8422"
         y="648.6842">30</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1234.2104"
       y="686.8421"
       id="tableTxt31"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher31"><tspan
         sodipodi:role="line"
         id="tspan1964"
         x="1234.2104"
         y="686.8421">31</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1373.6843"
       y="682.89471"
       id="tableTxt32"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher32"><tspan
         sodipodi:role="line"
         id="tspan1968"
         x="1373.6843"
         y="682.89471">32</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1513.158"
       y="792.10529"
       id="tableTxt33"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetNichtRaucher33"><tspan
         sodipodi:role="line"
         id="tspan1972"
         x="1513.158"
         y="792.10529">33</tspan></text>
  </g>
</svg>

<script>
    $("#SVGRoot").width(screen.width - 400);
</script>
