<?php
    use App\Orders;
    use App\PiketLog;
    use App\StatusWorker;
    use Carbon\Carbon;
    use App\TabOrder;
    use App\TableQrcode;
    use App\tabVerificationPNumbers;

    use App\Produktet;
    use App\FreeProducts;

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
        input:focus, textarea:focus, select:focus{
            outline: none !important;
        }

       
</style>
































@foreach(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('statusi','<',2)->get() as $ords )
<!-- The Modal -->
<div class="modal" id="tableModWStreetRaucher{{$ords->nrTable}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
                                    <span> {{__('adminP.usedPoints')}} : {{(PiketLog::where('order_u', $ords->id)->first()->piket) * -1}} ( <strong>{{(PiketLog::where('order_u', $ords->id)->first()->piket)*0.01}} {{__('adminP.currencyShow')}} </strong> )</span>         
                                @endif
                            @endif
                            @if($ords->tipPer != 0)
                                   <br> <span> {{__('adminP.waiterTip')}}: <strong>{{$ords->tipPer}} {{__('adminP.currencyShow')}}</strong> </span>
                            @endif
                            <hr>
                           
                        
                           
                            <span> {{__('adminP.total')}} : {{Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', $ords->nrTable)->where('statusi','<',2)->get()->sum('shuma')}} <sup>{{__('adminP.currencyShow')}}</sup></span>
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
                         @foreach(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', $ords->nrTable)->where('statusi','<',2)->get() as $allOrds)
                            <?php  $allOrderDate2D = explode(' ', $allOrds->created_at)[1]; ?>
                            <div style="width:10%;" >
                                      <p class="pt-2"><strong>{{__('adminP.time')}} :</strong>{{explode(':',$allOrderDate2D)[0]}}:{{explode(':',$allOrderDate2D)[1]}}</p>
                                      <p style="margin-top:-10px; font-size:18px;"><strong>{{$allOrds->shuma}} {{__('adminP.currencyShow')}} </strong> </p>
                            </div>
                            <div style="border:1px solid gray; width:90%;" class="p-2 mt-2 mb-2  d-flex justify-content-between flex-wrap">
                                    <div style="border-bottom:1px solid gray; width:100%;" class="d-flex justify-content-between flex-wrap mb-4">
                                        <p style="width:25%; font-size:20px;" class="text-left">{{__('adminP.waiterTip')}}: <strong>{{$allOrds->tipPer}} {{__('adminP.currencyShow')}}</strong></p>
                                        @if($allOrds->cuponOffVal != 0)
                                            <p style="width:25%; font-size:20px;" class="text-center">{{__('adminP.coupon')}}: <strong>- {{$allOrds->cuponOffVal}} {{__('adminP.currencyShow')}}</strong></p>
                                        @endif
                                        @if($allOrds->cuponProduct != 'empty')
                                            <p style="width:25%; font-size:20px;" class="text-center">{{__('adminP.forFree')}}: <strong>{{$allOrds->cuponProduct}}</strong></p>
                                        @endif
                                        @if(PiketLog::where('order_u', $allOrds->id)->first() != null)
                                            @if(PiketLog::where('order_u', $allOrds->id)->first()->piket < 0)
                                                <p style="width:25%; font-size:20px;" class="text-right">{{__('adminP.points')}} : {{(PiketLog::where('order_u', $allOrds->id)->first()->piket) * -1}} ( <strong>{{(PiketLog::where('order_u', $allOrds->id)->first()->piket)*0.01}} {{__('adminP.currencyShow')}} </strong> )</p>
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
                                        <button class="btn btn-warning btn-block" data-toggle="modal" data-target="#ChStatStreetRaucher{{$allOrds->id}}">
                                            {{__('adminP.waitingLine')}} 
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 1)
                                        <button class="btn btn-info btn-block" data-toggle="modal"  data-target="#ChStatStreetRaucher{{$allOrds->id}}">
                                            {{__('adminP.confirmed')}}
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 2)
                                        <button class="btn btn-danger btn-block" data-toggle="modal"  data-target="#ChStatStreetRaucher{{$allOrds->id}}">
                                            {{__('adminP.canceled')}}
                                                @if($allOrds->StatusBy!= 999999)
                                                    @if(StatusWorker::find($allOrds->StatusBy) !=null)
                                                        <span class="ml-4">"by " {{StatusWorker::find($allOrds->StatusBy)->emri}}</span> 
                                                    @endif  
                                                @endif                               
                                        </button>
                                    @elseif($allOrds->statusi == 3)
                                        <button class="btn btn-success btn-block" data-toggle="modal"  data-target="#ChStatStreetRaucher{{$allOrds->id}}">
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
                    <div class="modal-body" id="tabOrderBody{{$tablesAll->tableNr}}">
                    <?php $allTabOrder = TabOrder::where([['tabCode',$tablesAll->kaTab]])
                    ->whereIn('tableNr', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20])->get()->sortByDesc('created_at'); ?>

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
                                    @if($tabVer->phoneNr == "0770000000")
                                        <p style="margin-top:-15px;"><strong>{{__('adminP.admin')}}</strong></p>
                                    @else
                                        <p style="margin-top:-15px;"><strong>{{$tabVer->phoneNr}}</strong></p>
                                    @endif
                                </div>

                                <div style="width:45%;" class="pl-1">
                                    <h3>
                                        {{$oneTOrder->OrderSasia}}X <strong>{{$oneTOrder->OrderEmri}}</strong>
                                    </h3>
                                    <p style="margin-top:-10px;">{{$oneTOrder->OrderPershkrimi}}</p>
                                    @if($oneTOrder->OrderType != 'empty')
                                        <p style="font-size:18px; margin-top:-10px;"><strong>{{__('adminP.type')}}:</strong> {{explode('||',$oneTOrder->OrderType)[0]}} </p>
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
                        @endforeach  <!-- End Tab Orders For one table -->
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
			    $("#Street01layer1").load(location.href+" #Street01layer1>*","");
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
        <div class="modal" id="ChStatStreetRaucher{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
                            <button onclick="setChByStreetRaucher('{{$sWor->id}}')" id="statusWorkerButton{{$sWor->id}}" class="statusWorkerButtonAll btn backQr mr-1 ml-1"
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
                                {{ Form::submit({{__('adminP.finished')}}, ['class' => 'form-control btn btn-success btn-block']) }}
                                
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
    function setChByStreetRaucher(wId){
    

        $('.statusWorkerButtonAll').attr('class','statusWorkerButtonAll btn backQr mr-1 ml-1')
        $('#statusWorkerButton'+wId).attr('class','statusWorkerButtonAll btn backQrSelected mr-1 ml-1')
        $('#sWPhase2').show();
        $('.statBtn').show(500);
        $('.chByInput').val(wId);
    }

  
</script>
















<style>
    .desktopSVGRaucher{
        width:1620px;
        height:800px;
    }
</style>







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
   sodipodi:docname="streetRaucher.svg"
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
     id="Street01layer1">







    @if(TableQrcode::where([['tableNr',2],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',2],['Restaurant',$resFor]])->first()->kaTab; ?>

        <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7"';}?>
            id="table2"
            width="109.21053"
            height="92.105263"
            x="44.736843"
            y="34.210526"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder2" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 2)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7"
            id="table2"
            width="109.21053"
            height="92.105263"
            x="44.736843"
            y="34.210526"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher2" />
    @else
    <rect
       onclick="sendToAddOrderAdmin('2')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table2"
       width="109.21053"
       height="92.105263"
       x="44.736843"
       y="34.210526" />
    @endif






    @if(TableQrcode::where([['tableNr',3],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',3],['Restaurant',$resFor]])->first()->kaTab; ?>

            <rect
            <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7"';}
                    else{echo ' style="fill:yellow;fill-opacity:0.7"';}?>
            id="table3"
            width="109.21053"
            height="92.105263"
            x="234.86841"
            y="32.894737" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tabOrder3"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 3)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7"
            id="table3"
            width="109.21053"
            height="92.105263"
            x="234.86841"
            y="32.894737" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher3" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('3')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table3"
       width="109.21053"
       height="92.105263"
       x="234.86841"
       y="32.894737" />
    @endif



    @if(TableQrcode::where([['tableNr',4],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',4],['Restaurant',$resFor]])->first()->kaTab; ?>
      
            <rect
                <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7"';}
                    else{echo ' style="fill:yellow;fill-opacity:0.7"';}?>
                id="table4"
                width="109.21053"
                height="92.105263"
                x="415.13156"
                y="32.894737" 
                class="hasOrder"
                data-toggle="modal"
                data-target="#tabOrder4"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 4)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7"
            id="table4"
                width="109.21053"
                height="92.105263"
                x="415.13156"
                y="32.894737" 
                class="hasOrder"
                data-toggle="modal"
            data-target="#tableModWStreetRaucher4" />

    @else
    <rect
        onclick="sendToAddOrderAdmin('4')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table4"
       width="109.21053"
       height="92.105263"
       x="415.13156"
       y="32.894737" />
    @endif






    @if(TableQrcode::where([['tableNr',1],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',1],['Restaurant',$resFor]])->first()->kaTab; ?>
            <rect
                <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7"';}
                    else{echo ' style="fill:yellow;fill-opacity:0.7"';}?>
                id="table1"
                width="109.21053"
                height="92.105263"
                x="44.078934"
                y="200" 
                class="hasOrder"
                data-toggle="modal"
                data-target="#tabOrder1"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 1)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7"
            id="table1"
                width="109.21053"
                height="92.105263"
                x="44.078934"
                y="200" 
                class="hasOrder"
                data-toggle="modal"
            data-target="#tableModWStreetRaucher1" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('3')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table1"
       width="109.21053"
       height="92.105263"
       x="44.078934"
       y="200" />
    @endif





    
    @if(TableQrcode::where([['tableNr',5],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',5],['Restaurant',$resFor]])->first()->kaTab; ?>
            <rect
                <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7"';}?>
                id="table5"
                width="109.21053"
                height="92.105263"
                x="412.5"
                y="203.94737" 
                class="hasOrder"
                data-toggle="modal"
                data-target="#tabOrder5"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 5)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7"
            id="table5"
                width="109.21053"
                height="92.105263"
                x="412.5"
                y="203.94737" 
                class="hasOrder"
                data-toggle="modal"
            data-target="#tableModWStreetRaucher5" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('4')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table5"
       width="109.21053"
       height="92.105263"
       x="412.5"
       y="203.94737" />
    @endif











    @if(TableQrcode::where([['tableNr',6],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',6],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
    <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7"';}?>
       id="table6"
       width="109.21053"
       height="92.105263"
       x="654.60529"
       y="205.26317" 
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder6"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 6)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7"
            id="table6"
            width="109.21053"
            height="92.105263"
            x="654.60529"
            y="205.26317" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher6" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('6')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table6"
       width="109.21053"
       height="92.105263"
       x="654.60529"
       y="205.26317" />
    @endif







    <!--Its 8  -->
    @if(TableQrcode::where([['tableNr',8],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',8],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
    <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7"';}?>
      id="table7" 
       width="109.21053"
       height="92.105263"
       x="1441.4474"
       y="196.05261"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder8" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 8)->where('statusi','<',2)->get()->count() > 0 ) 
    <rect
            style="fill:green;fill-opacity:0.7"
            id="table7"
            width="109.21053"
            height="92.105263"
            x="1441.4474"
            y="196.05261"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher8" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('7')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table7"
       width="109.21053"
       height="92.105263"
       x="1441.4474"
       y="196.05261" />
    @endif






  <!--Its 8  -->
    @if(TableQrcode::where([['tableNr',7],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',7],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
    <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7"';}?>
       id="table8"
       width="109.21053"
       height="92.105263"
       x="1223.0262"
       y="198.6842"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder7" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 7)->where('statusi','<',2)->get()->count() > 0 ) 
    <rect
            style="fill:green;fill-opacity:0.7"
            id="table8"
            width="109.21053"
            height="92.105263"
            x="1223.0262"
            y="198.6842"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher7" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('8')"
       style="fill:#27beaf;fill-opacity:0.5"
       id="table8"
       width="109.21053"
       height="92.105263"
       x="1223.0262"
       y="198.6842" />
    @endif









  
    @if(TableQrcode::where([['tableNr',9],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',9],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
    <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:0.556606"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:0.556606"';}?>
       id="table9"
       width="59.210533"
       height="52.63158"
       x="563.8158"
       y="367.10526" 
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder9"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 9)->where('statusi','<',2)->get()->count() > 0 ) 
    <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.556606"
            id="table9"
            width="59.210533"
            height="52.63158"
            x="563.8158"
            y="367.10526" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher8" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('9')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.556606"
       id="table9"
       width="59.210533"
       height="52.63158"
       x="563.8158"
       y="367.10526" />
    @endif








    @if(TableQrcode::where([['tableNr',10],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',10],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
    <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:0.556606"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:0.556606"';}?>
       id="table10"
       width="59.210533"
       height="52.63158"
       x="1119.079"
       y="363.15787"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder10" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 10)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.556606"
            id="table10"
            width="59.210533"
            height="52.63158"
            x="1119.079"
            y="363.15787"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher10" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('10')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.556606"
       id="table10"
       width="59.210533"
       height="52.63158"
       x="1119.079"
       y="363.15787" />
    @endif







    @if(TableQrcode::where([['tableNr',11],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',11],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
    <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:1.15659"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:1.15659"';}?>
       id="table11"
       width="88.157898"
       height="152.63158"
       x="44.078938"
       y="718.42102"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder11" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 11)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.15659"
            id="table11"
            width="88.157898"
            height="152.63158"
            x="44.078938"
            y="718.42102"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher11" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('11')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.15659"
       id="table11"
       width="88.157898"
       height="152.63158"
       x="44.078938"
       y="718.42102" />
    @endif







  
    @if(TableQrcode::where([['tableNr',12],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',12],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
        <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:0.722969"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:0.722969"';}?>
       id="table12"
       width="98.684219"
       height="53.277542"
       x="166.44734"
       y="670.40668"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder12" />

    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 12)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.722969"
            id="table12"
            width="98.684219"
            height="53.277542"
            x="166.44734"
            y="670.40668"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher12" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('12')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.722969"
       id="table12"
       width="98.684219"
       height="53.277542"
       x="166.44734"
       y="670.40668" />
    @endif
    






    @if(TableQrcode::where([['tableNr',13],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',13],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
        <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:0.737287"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:0.737287"';}?>
       id="table13"
       width="102.63158"
       height="53.277542"
       x="292.76318"
       y="670.72961"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder13" />
    
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 13)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.737287"
            id="table13"
            width="102.63158"
            height="53.277542"
            x="292.76318"
            y="670.72961"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher13" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('13')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.737287"
       id="table13"
       width="102.63158"
       height="53.277542"
       x="292.76318"
       y="670.72961" />
    @endif








    @if(TableQrcode::where([['tableNr',14],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',14],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
        <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:0.722969"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:0.722969"';}?>
       id="table14"
       width="98.684219"
       height="53.277542"
       x="421.71051"
       y="672.04541"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder14" />

    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 14)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.722969"
            id="table14"
            width="98.684219"
            height="53.277542"
            x="421.71051"
            y="672.04541"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher14" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('14')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.722969"
       id="table14"
       width="98.684219"
       height="53.277542"
       x="421.71051"
       y="672.04541" />
    @endif

    




    @if(TableQrcode::where([['tableNr',15],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',15],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
        <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:1.05099"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:1.05099"';}?>
       id="table15"
       width="59.210533"
       height="187.65009"
       x="550.6579"
       y="534.71832" 
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder15"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 15)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.05099"
            id="table15"
            width="59.210533"
            height="187.65009"
            x="550.6579"
            y="534.71832" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher15" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('15')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.05099"
       id="table15"
       width="59.210533"
       height="187.65009"
       x="550.6579"
       y="534.71832" />
    @endif






    
    @if(TableQrcode::where([['tableNr',16],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',16],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
        <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:0.891337"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:0.891337"';}?>
       id="table16"
       width="150"
       height="53.277542"
       x="638.8158"
       y="673.36127" 
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder16"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 16)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.891337"
            id="table16"
            width="150"
            height="53.277542"
            x="638.8158"
            y="673.36127" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher16" />
    @else
    <rect
        onclick="sendToAddOrderAdmin('16')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.891337"
       id="table16"
       width="150"
       height="53.277542"
       x="638.8158"
       y="673.36127" />
    @endif








    @if(TableQrcode::where([['tableNr',18],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',18],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
        <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:1.05099"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:1.05099"';}?>
       id="table18"
       width="59.210533"
       height="187.65009"
       x="1279.6052"
       y="527.22754" 
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder18"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 18)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:1.05099"
            id="table18"
            width="59.210533"
            height="187.65009"
            x="1279.6052"
            y="527.22754" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher18"/>
    
    @else
    <rect
        onclick="sendToAddOrderAdmin('18')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:1.05099"
       id="table18"
       width="59.210533"
       height="187.65009"
       x="1279.6052"
       y="527.22754" />
    @endif







   
    @if(TableQrcode::where([['tableNr',17],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',17],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
    <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:0.891337"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:0.891337"';}?>
       id="table17"
       width="150"
       height="53.277542"
       x="1100"
       y="664.1507" 
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder17"/>
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 17)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.891337"
            id="table17"
            width="150"
            height="53.277542"
            x="1100"
            y="664.1507" 
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher17"/>
    @else
    <rect
        onclick="sendToAddOrderAdmin('17')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.891337"
       id="table17"
       width="150"
       height="53.277542"
       x="1100"
       y="664.1507" />
    @endif


    




    @if(TableQrcode::where([['tableNr',19],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',19],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
        <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:0.722969"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:0.722969"';}?>
       id="table19"
       width="98.684219"
       height="53.277542"
       x="1367.7632"
       y="658.88751"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder19" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 19)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.722969"
            id="table19"
            width="98.684219"
            height="53.277542"
            x="1367.7632"
            y="658.88751"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher19"/>
    @else
    <rect
        onclick="sendToAddOrderAdmin('19')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.722969"
       id="table19"
       width="98.684219"
       height="53.277542"
       x="1367.7632"
       y="658.88751" />
    @endif






    
    @if(TableQrcode::where([['tableNr',20],['Restaurant',$resFor]])->first()->kaTab != 0 )
        <?php $tCode=TableQrcode::where([['tableNr',20],['Restaurant',$resFor]])->first()->kaTab; ?>
    <rect
        <?php if(count(TabOrder::where([['tabCode',$tCode],['status',0]])->get()) > 0){echo 'style="fill:red;fill-opacity:0.7;stroke-width:0.556606"';}
                else{echo ' style="fill:yellow;fill-opacity:0.7;stroke-width:0.556606"';}?>
       id="table20"
       width="59.210533"
       height="52.63158"
       x="1488.8157"
       y="577.63159"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tabOrder20" />
    @elseif(Orders::where('Restaurant',Auth::user()->sFor)->whereDate('created_at', Carbon::today())->where('nrTable', 20)->where('statusi','<',2)->get()->count() > 0 ) 
        <rect
            style="fill:green;fill-opacity:0.7; stroke-width:0.556606"
            id="table20"
            width="59.210533"
            height="52.63158"
            x="1488.8157"
            y="577.63159"
            class="hasOrder"
            data-toggle="modal"
            data-target="#tableModWStreetRaucher20"/>
    @else
    <rect
        onclick="sendToAddOrderAdmin('20')"
       style="fill:#27beaf;fill-opacity:0.5;stroke-width:0.556606"
       id="table20"
       width="59.210533"
       height="52.63158"
       x="1488.8157"
       y="577.63159" />
    @endif









    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="86.842102"
       y="260.52634"
       id="tableTxt1"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher1"><tspan
         sodipodi:role="line"
         id="tspan1658"
         x="86.842102"
         y="260.52634">1</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="85.526321"
       y="96.052628"
       id="tableTxt2"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher2"><tspan
         sodipodi:role="line"
         id="tspan1662"
         x="85.526321"
         y="96.052628">2</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="276.3158"
       y="93.421051"
       id="tableTxt3"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher3"><tspan
         sodipodi:role="line"
         id="tspan1666"
         x="276.3158"
         y="93.421051">3</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="457.89474"
       y="92.105263"
       id="tableTxt4"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher4"><tspan
         sodipodi:role="line"
         id="tspan1670"
         x="457.89474"
         y="92.105263">4</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="453.94736"
       y="263.1579"
       id="tableTxt5"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher5"><tspan
         sodipodi:role="line"
         id="tspan1674"
         x="453.94736"
         y="263.1579">5</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="696.05261"
       y="265.78949"
       id="tableTxt6"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher6"><tspan
         sodipodi:role="line"
         id="tspan1678"
         x="696.05261"
         y="265.78949">6</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1267.1052"
       y="257.89474"
       id="tableTxt7"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher7"><tspan
         sodipodi:role="line"
         id="tspan1682"
         x="1267.1052"
         y="257.89474">7</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1485.5262"
       y="256.57895"
       id="tableTxt8"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher8"><tspan
         sodipodi:role="line"
         id="tspan1686"
         x="1485.5262"
         y="256.57895">8</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="580.26318"
       y="406.57895"
       id="tableTxt9"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher9"><tspan
         sodipodi:role="line"
         id="tspan1690"
         x="580.26318"
         y="406.57895">9</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1123.6842"
       y="401.3158"
       id="tableTxt10"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher10"><tspan
         sodipodi:role="line"
         id="tspan1694"
         x="1123.6842"
         y="401.3158">10</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="64.473679"
       y="811.8421"
       id="tableTxt11"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher11"><tspan
         sodipodi:role="line"
         id="tspan1698"
         x="64.473679"
         y="811.8421">11</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="192.10526"
       y="709.21051"
       id="tableTxt12"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher12"><tspan
         sodipodi:role="line"
         id="tspan1702"
         x="192.10526"
         y="709.21051">12</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="317.10526"
       y="710.52637"
       id="tableTxt13"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher13"><tspan
         sodipodi:role="line"
         id="tspan1706"
         x="317.10526"
         y="710.52637">13</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="444.73685"
       y="714.47369"
       id="tableTxt14"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher14"><tspan
         sodipodi:role="line"
         id="tspan1710"
         x="444.73685"
         y="714.47369">14</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="553.94739"
       y="644.73688"
       id="tableTxt15"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher15"><tspan
         sodipodi:role="line"
         id="tspan1714"
         x="553.94739"
         y="644.73688">15</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="685.52637"
       y="713.1579"
       id="tableTxt16"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher16"><tspan
         sodipodi:role="line"
         id="tspan1718"
         x="685.52637"
         y="713.1579">16</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1148.6842"
       y="703.94739"
       id="tableTxt17"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher17"><tspan
         sodipodi:role="line"
         id="tspan1722"
         x="1148.6842"
         y="703.94739">17</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1281.579"
       y="639.47369"
       id="tableTxt18"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher18"><tspan
         sodipodi:role="line"
         id="tspan1726"
         x="1281.579"
         y="639.47369">18</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1389.4736"
       y="697.36841"
       id="tableTxt19"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher19"><tspan
         sodipodi:role="line"
         id="tspan1730"
         x="1389.4736"
         y="697.36841">19</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-weight:normal;font-size:40px;line-height:1.25;font-family:sans-serif;fill:#000000;fill-opacity:1;stroke:none"
       x="1494.7368"
       y="614.47369"
       id="tableTxt20"
       class="hasOrder"
       data-toggle="modal"
        data-target="#tableModWStreetRaucher20"><tspan
         sodipodi:role="line"
         id="tspan1734"
         x="1494.7368"
         y="614.47369">20</tspan></text>
  </g>
</svg>

<script>
    $("#SVGRoot").width(screen.width - 400);
</script>
