
<?php
    use App\Orders;
    use App\PiketLog;
    use App\StatusWorker;
    use Carbon\Carbon;
    use App\TabOrder;
    use App\TableQrcode;
    use App\FreeProducts;
    use App\tabVerificationPNumbers;

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
<div class="modal" id="tableModWImperial{{$ords->nrTable}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
                        <p style="margin-top:-20px; width:100%; font-size:19px;" class="p-1"><strong>{{__('adminP.coupon')}} :</strong>
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
                                        <p style="width:33%" class="text-left">{{__('adminP.waiterTip')}}: <strong>{{$allOrds->tipPer}} {{__('adminP.currencyShow')}}</strong></p>
                                        @if($allOrds->cuponOffVal != 0)
                                            <p style="width:33%" class="text-center">{{__('adminP.coupon')}}: <strong>- {{$allOrds->cuponOffVal}} {{__('adminP.currencyShow')}}F</strong></p>
                                        @endif
                                        @if($allOrds->cuponProduct != 'empty')
                                            <p style="width:25%; font-size:20px;" class="text-center">{{__('adminP.forFree')}}: <strong>{{$allOrds->cuponProduct}}</strong></p>
                                        @endif
                                        @if(PiketLog::where('order_u', $allOrds->id)->first() != null)
                                            @if(PiketLog::where('order_u', $allOrds->id)->first()->piket < 0)
                                                <p style="width:33%" class="text-right">{{__('adminP.usedPoints')}} : {{(PiketLog::where('order_u', $allOrds->id)->first()->piket) * -1}} ( <strong>{{(PiketLog::where('order_u', $allOrds->id)->first()->piket)*0.01}} {{__('adminP.currencyShow')}} </strong> )</p>
                                            @endif
                                        @endif
                                    </div>


                                    
                                    @foreach(explode('---8---',$allOrds->porosia) as $produkti)
                                    
                                        <?php $prod = explode('-8-', $produkti); ?>
                                        
                                        <div style="width:59.5%; border-bottom:1px solid lightgray;">
                                            <p style="font-size:21px;"><strong>{{$prod[0]}}</strong>
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


                                    @if($allOrds->statusi == 0)
                                        <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#ChStatImperial{{$allOrds->id}}">
                                            {{__('adminP.waitingLine')}}
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 1)
                                        <button class="btn btn-info btn-block" data-toggle="modal"  data-target="#ChStatImperial{{$allOrds->id}}">
                                            Bestätigt
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 2)
                                        <button class="btn btn-danger btn-block" data-toggle="modal"  data-target="#ChStatImperial{{$allOrds->id}}">
                                            Annulliert
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 3)
                                        <button class="btn btn-success btn-block" data-toggle="modal"  data-target="#ChStatImperial{{$allOrds->id}}">
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
                            
                            <span style="font-size:30px;"><strong> {{__('adminP.tables')}} : {{$tablesAll->tableNr}}</strong></span>
                            <span class="ml-5"> {{__('adminP.total')}}: {{TabOrder::where([['tabCode',$tablesAll->kaTab],['status','<',2]])->get()->sum('OrderQmimi')}} <sup>{{__('adminP.currencyShow')}}</sup></span>
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
                    <div class="modal-body" id="tabOrderBody{{$tablesAll->tableNr}}">
                    <?php $allTabOrder = TabOrder::where([['tabCode',$tablesAll->kaTab],['status','<',2]])->get()->sortByDesc('created_at'); ?>
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
                                                    <p style="width:30%; margin-top:-20px;">{{explode('||',$oneOExt)[1]}} <sup>{{__('adminP.CurrencyShow')}}</sup></p>
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
                                        <button class=" ml-1 mr-1 btn btn-block" style="margin-top:-10px;"> {{__('adminP.sendOrder')}} </button>
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
			    $("#Imperial01layer1").load(location.href+" #Street03layer1>*","");
                
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
        <div class="modal" id="ChStatImperial{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
                            <button onclick="setChByImperial('{{$sWor->id}}')" id="statusWorkerButton{{$sWor->id}}" class="statusWorkerButtonAll btn backQr mr-1 ml-1"
                            style="width:24%;"> {{$sWor->emri}}</button>
                        @endforeach
                    
                    
                    </div>

                    <div class="d-flex mb-3" id="sWPhase2" style="display:none;">
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 0 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::submit(__('adminP.waitingLine'), ['class' => 'form-control btn btn-warning btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::submit(__('adminP.confirmed'), ['class' => 'form-control btn btn-info btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 2 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
                                {{ Form::submit(__('adminP.canceled'), ['class' => 'form-control btn btn-danger btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%; display:none;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 0 , ['class' => 'form-control chByInput']) }}
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
                                {{ Form::submit(__('adminP.waitingLine'), ['class' => 'form-control btn btn-warning btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 1 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                {{ Form::submit(__('adminP.confirmed'), ['class' => 'form-control btn btn-info btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 2 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
                                {{ Form::submit(__('adminP.canceled'), ['class' => 'form-control btn btn-danger btn-block']) }}
                            {{Form::close() }}
                        </div>
                        <div style="width:24%;" class="mr-1 ml-1 statBtn">
                            {{Form::open(['action' => 'OrdersController@ChangeStatus', 'method' => 'post']) }}

                                {{ Form::hidden('orderId',$order->id, ['class' => 'form-control']) }}
                                {{ Form::hidden('orderStat', 3 , ['class' => 'form-control']) }}
                                {{ Form::hidden('chBy', 999999 , ['class' => 'form-control chByInput']) }}
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
    function setChByImperial(wId){
    

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
   width="1620px"
   height="920px"
   viewBox="0 0 1920 920"
   version="1.1"
   id="SVGRoot"
   sodipodi:docname="imperial.svg"
   inkscape:version="1.0.1 (3bc2e813f5, 2020-09-07)">
  <defs
     id="defs1402" />
  <sodipodi:namedview
     id="base"
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1.0"
     inkscape:pageopacity="0.0"
     inkscape:pageshadow="2"
     inkscape:zoom="0.62"
     inkscape:cx="957.81861"
     inkscape:cy="472.05502"
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
     id="metadata1405">
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
     id="Imperial01layer1">






     @if(TableQrcode::where([['tableNr',2],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',2],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.787956"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.787956"';}?>
            id="table2"
            width="109.67742"
            height="70.967743"
            x="276.61288"
            y="16.935482"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder2" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 2)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.787956"
            id="table2"
            width="109.67742"
            height="70.967743"
            x="276.61288"
            y="16.935482"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial2" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('2')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.787956"
       id="table2"
       width="109.67742"
       height="70.967743"
       x="276.61288"
       y="16.935482" />
    @endif








    @if(TableQrcode::where([['tableNr',5],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',5],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.17242"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.17242"';}?>
            id="table5"
            width="222.58064"
            height="77.419357"
            x="1029.8386"
            y="95.967735" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder5" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 5)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.17242"
            id="table5"
            width="222.58064"
            height="77.419357"
            x="1029.8386"
            y="95.967735" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial5" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('5')"
       style="fill:#1ebeaf;fill-opacity:0.5;stroke-width:1.17242"
       id="table5"
       width="222.58064"
       height="77.419357"
       x="1029.8386"
       y="95.967735" />
    @endif







    @if(TableQrcode::where([['tableNr',6],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',6],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; ;stroke-width:0.902952"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; ;stroke-width:0.902952"';}?>
            id="table6"
            width="103.54839"
            height="98.709679"
            x="1421.7743"
            y="89.516121"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder6" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 6)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; ;stroke-width:0.902952"
            id="table6"
            width="103.54839"
            height="98.709679"
            x="1421.7743"
            y="89.516121" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial6" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('6')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.902952"
       id="table6"
       width="103.54839"
       height="98.709679"
       x="1421.7743"
       y="89.516121" />
    @endif








    @if(TableQrcode::where([['tableNr',7],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',7],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7"';}?>
            id="table7"
            cx="1762.0968"
            cy="137.09677"
            rx="62.096775"
            ry="53.225807"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder7" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 7)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7"
            id="table7"
            cx="1762.0968"
            cy="137.09677"
            rx="62.096775"
            ry="53.225807" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial7" />
    @else
    <ellipse
        onclick="sendToAddOrderAdmin('7')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table7"
       cx="1762.0968"
       cy="137.09677"
       rx="62.096775"
       ry="53.225807" />
    @endif







    
    @if(TableQrcode::where([['tableNr',10],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',10],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.970512"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.970512"';}?>
            id="table10"
            width="82.258072"
            height="143.54839"
            x="1139.5162"
            y="254.03226"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder10" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 10)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.970512"
            id="table10"
            width="82.258072"
            height="143.54839"
            x="1139.5162"
            y="254.03226" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial10" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('10')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.970512"
       id="table10"
       width="82.258072"
       height="143.54839"
       x="1139.5162"
       y="254.03226" />
    @endif






    @if(TableQrcode::where([['tableNr',12],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',12],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.8901"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.8901"';}?>
            id="table12"
            width="74.193558"
            height="133.87097"
            x="1670.1614"
            y="349.19354"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder12" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 12)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.8901"
            id="table12"
            width="74.193558"
            height="133.87097"
            x="1670.1614"
            y="349.19354"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial12" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('12')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.8901"
       id="table12"
       width="74.193558"
       height="133.87097"
       x="1670.1614"
       y="349.19354" />
    @endif







    @if(TableQrcode::where([['tableNr',11],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',11],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.941535"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.941535"';}?>
            id="table11"
            width="77.419357"
            height="143.54839"
            x="1407.2581"
            y="295.96774"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder11" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 11)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.941535"
            id="table11"
            width="77.419357"
            height="143.54839"
            x="1407.2581"
            y="295.96774"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial11" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('11')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.941535"
       id="table11"
       width="77.419357"
       height="143.54839"
       x="1407.2581"
       y="295.96774" />
    @endif






    @if(TableQrcode::where([['tableNr',4],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',4],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.906042"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.906042"';}?>
            id="table4"
            width="74.193558"
            height="138.70967"
            x="747.58063"
            y="21.774191"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder4" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 4)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.906042"
            id="table4"
            width="74.193558"
            height="138.70967"
            x="747.58063"
            y="21.774191"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial4" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('4')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.906042"
       id="table4"
       width="74.193558"
       height="138.70967"
       x="747.58063"
       y="21.774191" />
    @endif





    @if(TableQrcode::where([['tableNr',3],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',3],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.965043"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.965043"';}?>
            id="table3"
            width="82.258072"
            height="141.93549"
            x="602.41937"
            y="21.774191"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder3" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 3)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.965043"
            id="table3"
            width="82.258072"
            height="141.93549"
            x="602.41937"
            y="21.774191"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial3" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('3')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.965043"
       id="table3"
       width="82.258072"
       height="141.93549"
       x="602.41937"
       y="21.774191" />
    @endif





    @if(TableQrcode::where([['tableNr',1],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',1],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.786639"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.786639"';}?>
            id="table1"
            width="114.51613"
            height="67.741936"
            x="97.580627"
            y="16.935484" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder1" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 1)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.786639"
            id="table1"
            width="114.51613"
            height="67.741936"
            x="97.580627"
            y="16.935484" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial1" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('1')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.786639"
       id="table1"
       width="114.51613"
       height="67.741936"
       x="97.580627"
       y="16.935484" />
    @endif





    @if(TableQrcode::where([['tableNr',9],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',9],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.29295"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.29295"';}?>
            id="table9"
            width="245.16129"
            height="85.483871"
            x="601.61298"
            y="278.2258"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder9" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 9)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.29295"
            id="table9"
            width="245.16129"
            height="85.483871"
            x="601.61298"
            y="278.2258"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial9" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('9')"
       style="fill:#1ebeaf;fill-opacity:0.5;stroke-width:1.29295"
       id="table9"
       width="245.16129"
       height="85.483871"
       x="601.61298"
       y="278.2258" />
    @endif




    @if(TableQrcode::where([['tableNr',8],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',8],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;"';}?>
            id="table8"
            cx="174.1936"
            cy="301.61288"
            rx="62.096775"
            ry="53.225807"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder8" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 8)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7;"
            id="table8"
            cx="174.1936"
            cy="301.61288"
            rx="62.096775"
            ry="53.225807"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial8" />
    @else
    <ellipse
        onclick="sendToAddOrderAdmin('8')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table8"
       cx="174.1936"
       cy="301.61288"
       rx="62.096775"
       ry="53.225807" />
    @endif






  @if(TableQrcode::where([['tableNr',26],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',26],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.982439"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.982439"';}?>
            id="table26"
            width="110"
            height="110"
            x="1767.5806"
            y="780.48389" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder26" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 26)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.982439"
            id="table26"
            width="110"
            height="110"
            x="1767.5806"
            y="780.48389" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial26" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('26')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.982439"
       id="table26"
       width="110"
       height="110"
       x="1767.5806"
       y="780.48389" />
    @endif






    @if(TableQrcode::where([['tableNr',24],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',24],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.982439"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.982439"';}?>
            id="table24"
            width="110"
            height="110"
            x="1767.5806"
            y="615.96777" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder24" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 24)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.982439"
            id="table24"
            width="110"
            height="110"
            x="1767.5806"
            y="615.96777" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial24" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('24')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.982439"
       id="table24"
       width="110"
       height="110"
       x="1767.5806"
       y="615.96777" />
    @endif






    @if(TableQrcode::where([['tableNr',23],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',23],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.982439"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.982439"';}?>
            id="table23"
            width="110"
            height="110"
            x="1607.9032"
            y="615.96777"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder23" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 23)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.982439"
            id="table23"
            width="110"
            height="110"
            x="1607.9032" 
            y="615.96777" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial23" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('23')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.982439"
       id="table23"
       width="110"
       height="110"
       x="1607.9032"
       y="615.96777" />
    @endif






    @if(TableQrcode::where([['tableNr',25],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',25],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.982439"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.982439"';}?>
            id="table25"
            width="110"
            height="110"
            x="1604.6774"
            y="774.03223"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder25" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 25)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.982439"
            id="table25"
            width="110"
            height="110"
            x="1604.6774"
            y="774.03223"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial25" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('25')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.982439"
       id="table25"
       width="110"
       height="110"
       x="1604.6774"
       y="774.03223" />
    @endif






    @if(TableQrcode::where([['tableNr',22],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',22],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.21969"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.21969"';}?>
            id="table22"
            width="108.06452"
            height="172.58064"
            x="1216.9354"
            y="700.80646" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder22" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 22)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.21969"
            id="table22"
            width="108.06452"
            height="172.58064"
            x="1216.9354"
            y="700.80646" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial22" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('22')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.21969"
       id="table22"
       width="108.06452"
       height="172.58064"
       x="1216.9354"
       y="700.80646" />
    @endif






    @if(TableQrcode::where([['tableNr',19],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',19],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.29295"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.29295"';}?>
            id="table19"
            width="245.16129"
            height="85.483871"
            x="106.45161"
            y="807.25806"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder19" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 19)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.29295"
            id="table19"
            width="245.16129"
            height="85.483871"
            x="106.45161"
            y="807.25806"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial19" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('19')"
       style="fill:#1ebeaf;fill-opacity:0.5;stroke-width:1.29295"
       id="table19"
       width="245.16129"
       height="85.483871"
       x="106.45161"
       y="807.25806" />
    @endif







    @if(TableQrcode::where([['tableNr',18],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',18],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.29295"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.29295"';}?>
            id="table18"
            width="245.16129"
            height="85.483871"
            x="108.06451"
            y="683.06451"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder18" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 18)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.29295"
            id="table18"
            width="245.16129"
            height="85.483871"
            x="108.06451"
            y="683.06451"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial18" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('18')"
       style="fill:#1ebeaf;fill-opacity:0.5;stroke-width:1.29295"
       id="table18"
       width="245.16129"
       height="85.483871"
       x="108.06451"
       y="683.06451" />
    @endif







    @if(TableQrcode::where([['tableNr',17],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',17],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.08714"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.08714"';}?>
            id="table17"
            cx="1413.3065"
            cy="567.33868"
            r="62.5"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder17" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 17)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.08714"
            id="table17"
            cx="1413.3065"
            cy="567.33868"
            r="62.5"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial17" />
    @else
    <circle
        onclick="sendToAddOrderAdmin('17')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.08714"
       id="table17"
       cx="1413.3065"
       cy="567.33868"
       r="62.5" />
    @endif








    @if(TableQrcode::where([['tableNr',16],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',16],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.08714"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.08714"';}?>
            id="table16"
            cx="1111.6935"
            cy="567.33868"
            r="62.5" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder16" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 16)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.08714"
            id="table16"
            cx="1111.6935"
            cy="567.33868"
            r="62.5" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial16" />

    @else
    <circle
        onclick="sendToAddOrderAdmin('16')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.08714"
       id="table16"
       cx="1111.6935"
       cy="567.33868"
       r="62.5" />
    @endif






    @if(TableQrcode::where([['tableNr',15],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',15],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:0.902952"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:0.902952"';}?>
            id="table15"
            width="103.54839"
            height="98.709679"
            x="790.16125"
            y="539.35486" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder15" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 15)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.902952"
            id="table15"
            width="103.54839"
            height="98.709679"
            x="790.16125"
            y="539.35486" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial15" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('15')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.902952"
       id="table15"
       width="103.54839"
       height="98.709679"
       x="790.16125"
       y="539.35486" />
    @endif







    @if(TableQrcode::where([['tableNr',21],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',21],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.21969"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.21969"';}?>
            id="table21"
            width="108.06452"
            height="172.58064"
            x="952.41931"
            y="695.96771" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder21" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 21)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.21969"
            id="table21"
            width="108.06452"
            height="172.58064"
            x="952.41931"
            y="695.96771"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial21" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('21')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.21969"
       id="table21"
       width="108.06452"
       height="172.58064"
       x="952.41931"
       y="695.96771" />
    @endif







    @if(TableQrcode::where([['tableNr',20],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',20],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.21969"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.21969"';}?>
             id="table20"
            width="108.06452"
            height="172.58064"
            x="670.16132"
            y="702.41931"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder20" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 20)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.21969"
            id="table20"
            width="108.06452"
            height="172.58064"
            x="670.16132"
            y="702.41931"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial20" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('20')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.21969"
       id="table20"
       width="108.06452"
       height="172.58064"
       x="670.16132"
       y="702.41931" />
    @endif






    @if(TableQrcode::where([['tableNr',13],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',13],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.21969"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.21969"';}?>
            id="table13"
            width="108.06452"
            height="172.58064"
            x="104.03227"
            y="434.6774"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder13" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 13)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.21969"
            id="table13"
            width="108.06452"
            height="172.58064"
            x="104.03227"
            y="434.6774"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial13" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('13')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.21969"
       id="table13"
       width="108.06452"
       height="172.58064"
       x="104.03227"
       y="434.6774" />
    @endif






    @if(TableQrcode::where([['tableNr',14],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',14],['Restaurant',$resFor]])->first()->kaTab; ?>
        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7; stroke-width:1.21969"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7; stroke-width:1.21969"';}?>
            id="table14"
            width="108.06452"
            height="172.58064"
            x="276.61292"
            y="470.16129"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder14" />
    @elseif(Orders::where('Restaurant',$resFor)->whereDate('created_at', Carbon::today())->where('nrTable', 14)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.21969"
            id="table14"
            width="108.06452"
            height="172.58064"
            x="276.61292"
            y="470.16129"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWImperial14" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('14')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.21969"
       id="table14"
       width="108.06452"
       height="172.58064"
       x="276.61292"
       y="470.16129" />
    @endif










       
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="141.93549"
       y="62.903229"
       id="tableTxt1"><tspan
         sodipodi:role="line"
         id="tspan2985"
         x="141.93549"
         y="62.903229">1</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="316.12903"
       y="64.516129"
       id="tableTxt2"><tspan
         sodipodi:role="line"
         id="tspan2989"
         x="316.12903"
         y="64.516129">2</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="630.64514"
       y="112.90323"
       id="tableTxt3"><tspan
         sodipodi:role="line"
         id="tspan2993"
         x="630.64514"
         y="112.90323">3</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="772.58069"
       y="111.29032"
       id="tableTxt4"><tspan
         sodipodi:role="line"
         id="tspan2997"
         x="772.58069"
         y="111.29032">4</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1129.0322"
       y="148.38708"
       id="tableTxt5"><tspan
         sodipodi:role="line"
         id="tspan3001"
         x="1129.0322"
         y="148.38708">5</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1459.6775"
       y="153.22581"
       id="tableTxt6"><tspan
         sodipodi:role="line"
         id="tspan3005"
         x="1459.6775"
         y="153.22581">6</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1748.3871"
       y="154.8387"
       id="tableTxt7"><tspan
         sodipodi:role="line"
         id="tspan3009"
         x="1748.3871"
         y="154.8387">7</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="162.90323"
       y="314.51611"
       id="tableTxt8"><tspan
         sodipodi:role="line"
         id="tspan3013"
         x="162.90323"
         y="314.51611">8</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="711.29034"
       y="329.03226"
       id="tableTxt9"><tspan
         sodipodi:role="line"
         id="tspan3017"
         x="711.29034"
         y="329.03226">9</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1156.4517"
       y="345.16132"
       id="tableTxt10"><tspan
         sodipodi:role="line"
         id="tspan3021"
         x="1156.4517"
         y="345.16132">10</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1422.5807"
       y="388.70966"
       id="tableTxt11"><tspan
         sodipodi:role="line"
         id="tspan3025"
         x="1422.5807"
         y="388.70966">11</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1682.2581"
       y="433.87097"
       id="tableTxt12"><tspan
         sodipodi:role="line"
         id="tspan3029"
         x="1682.2581"
         y="433.87097">12</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="133.87097"
       y="538.70966"
       id="tableTxt13"><tspan
         sodipodi:role="line"
         id="tspan3033"
         x="133.87097"
         y="538.70966">13</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="304.83871"
       y="567.74194"
       id="tableTxt14"><tspan
         sodipodi:role="line"
         id="tspan3037"
         x="304.83871"
         y="567.74194">14</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="820.96777"
       y="604.83875"
       id="tableTxt15"><tspan
         sodipodi:role="line"
         id="tspan3041"
         x="820.96777"
         y="604.83875">15</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1088.7096"
       y="580.64514"
       id="tableTxt16"><tspan
         sodipodi:role="line"
         id="tspan3045"
         x="1088.7096"
         y="580.64514">16</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1387.0968"
       y="583.87097"
       id="tableTxt17"><tspan
         sodipodi:role="line"
         id="tspan3049"
         x="1387.0968"
         y="583.87097">17</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="203.22581"
       y="735.48383"
       id="tableTxt18"><tspan
         sodipodi:role="line"
         id="tspan3053"
         x="203.22581"
         y="735.48383">18</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="206.45161"
       y="859.67737"
       id="tableTxt19"><tspan
         sodipodi:role="line"
         id="tspan3057"
         x="206.45161"
         y="859.67737">19</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="701.61292"
       y="803.22583"
       id="tableTxt20"><tspan
         sodipodi:role="line"
         id="tspan3061"
         x="701.61292"
         y="803.22583">20</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="983.87097"
       y="801.61285"
       id="tableTxt21"><tspan
         sodipodi:role="line"
         id="tspan3065"
         x="983.87097"
         y="801.61285">21</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1246.774"
       y="801.61292"
       id="tableTxt22"><tspan
         sodipodi:role="line"
         id="tspan3069"
         x="1246.774"
         y="801.61292">22</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1640.3225"
       y="688.70966"
       id="tableTxt23"><tspan
         sodipodi:role="line"
         id="tspan3073"
         x="1640.3225"
         y="688.70966">23</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1798.3872"
       y="685.48383"
       id="tableTxt24"><tspan
         sodipodi:role="line"
         id="tspan3077"
         x="1798.3872"
         y="685.48383">24</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1637.0967"
       y="843.54834"
       id="tableTxt25"><tspan
         sodipodi:role="line"
         id="tspan3081"
         x="1637.0967"
         y="843.54834">25</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1803.2258"
       y="850"
       id="tableTxt26"><tspan
         sodipodi:role="line"
         id="tspan3085"
         x="1803.2258"
         y="850">26</tspan></text>
  </g>
</svg>
