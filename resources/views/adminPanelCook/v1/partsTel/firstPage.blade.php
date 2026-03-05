<?php

    use App\cooksProductSelection;
    use App\ekstra;
    use App\Produktet;
    use App\TabOrder;
    use App\kategori;
    use App\LlojetPro;
    use Illuminate\Support\Facades\Auth;

    if(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1']])->count() > 0 ){
        $allActConfOrders = array();
        $cookHasExtrasCount = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra']])->count();
        $allActConfOrdersrefuse01 = array();
        $cookHasTypesCount = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type']])->count();
        $allActConfOrdersrefuse02 = array();

        if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category']])->count() > 0){
            $toFilterByPTE = array();
            foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1']])->get()->sortByDesc('created_at') as $onePCatFilter){
                $theProdreCatF = Produktet::findOrFail($onePCatFilter->prodId);
                if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$theProdreCatF->kategoria]])->first() != NULL){
                    array_push($allActConfOrders,$onePCatFilter);
                }else{
                    array_push($toFilterByPTE,$onePCatFilter);
                }
            }
        }else{
            $toFilterByPTE = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1']])->get()->sortByDesc('created_at');
        }

        if(count($toFilterByPTE) > 0){
            // product access
            foreach($toFilterByPTE as $toF01){
                $acsToThisProd = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$toF01->prodId]])->first();
                if($acsToThisProd != NULL){
                    array_push($allActConfOrders,$toF01);
                }else if($toF01->OrderExtra != 'empty'){
                    array_push($allActConfOrdersrefuse01,$toF01);
                }else if($toF01->OrderType != 'empty'){
                    array_push($allActConfOrdersrefuse02,$toF01);
                }
            }
            // Extra access
            if($cookHasExtrasCount > 0 && count($allActConfOrdersrefuse01) > 0){
                foreach($allActConfOrdersrefuse01 as $oneOrRef01){
                    $theProdref01 = Produktet::findOrFail($oneOrRef01->prodId);
                    $hasAnExtra = 0;
                    foreach(explode('--0--',$oneOrRef01->OrderExtra) as $oneExtraref01){
                        $oEf012D = explode('||',$oneExtraref01);

                        $oneExtraref01ID = ekstra::where([['toCat',$theProdref01->kategoria],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                        if($oneExtraref01ID != NULL){
                            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                $hasAnExtra = 1;
                                break;
                            }
                        }
                    }
                    if( $hasAnExtra == 1){
                        array_push($allActConfOrders,$oneOrRef01);
                    }
                }
            }
            // Type access
            if($cookHasTypesCount > 0 && count($allActConfOrdersrefuse02) > 0){
                foreach($allActConfOrdersrefuse02 as $oneOrRef02){
                    $theProdref02 = Produktet::findOrFail($oneOrRef02->prodId);
                    $oTf012D = explode('||',$oneOrRef02->OrderType);
                    $oneTyperef01ID = LlojetPro::where([['kategoria',$theProdref02->kategoria],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                    if($oneTyperef01ID != NULL){
                        if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                            array_push($allActConfOrders,$oneOrRef02);
                        }
                    }
                }
            }
        }

        $catsAll = array();
        $productsBllock = array();

        foreach($allActConfOrders as $oneTP){
            $catId = Produktet::findOrFail($oneTP->prodId)->kategoria;
            if(!in_array($catId,$catsAll)){
                array_push($catsAll,$catId);
            }
        }
    }else{
        $noOrdersYet = 1;
    }
?>




@if(isset($noOrdersYet))


    <div style="background-color: rgb(39,190,175); min-height: 50cm;" class="pr-2 pl-2 pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Restaurant</strong></p>
        <div style="background-color: white; border-radius:5px;" class="p-2">
            <p style="font-size:1.8rem; color:rgb(39,190,175);" class="text-center"><strong>Es gibt noch keine ausstehenden Bestellungen für Sie!</strong></p>

        </div>

    </div>












@else

    <style>
        .swiper-container{
            background-color:#FFF;
            padding-top: 5px !important;
        }
        .swiper-slide{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .swiper-slide img{
            object-fit:cover;
        }
        .swiper-slide p{
            margin-top:5px;
            margin:0;
        }
        p,h4,h5{
            margin:0px;
        }

        .productName{
            width: 65%;
            background-color: rgba(231,229,230,255);

            border-top:2px solid rgb(72,81,87);
            border-left:2px solid rgb(72,81,87);
        
        }
        .productDoneAll{
            width: 35%;
            background-color: rgba(231,229,230,255);

            border-top:2px solid rgb(72,81,87);
            border-right:2px solid rgb(72,81,87);
        }
        .proColDetailsHead{
            width: 33.33334%;
            border-bottom:1px solid rgb(72,81,87);
        }
        .proColDetails{
            width: 33.33334%;
        }
        .proColDetailsBottom{
            width: 33.33334%;
            border-bottom:1px dotted rgb(72,81,87);
            margin-bottom: 5px;
            padding-bottom: 5px;
        }

        .prodListDown{
            border-bottom:2px solid rgb(72,81,87);
            border-left:2px solid rgb(72,81,87);
            border-right:2px solid rgb(72,81,87);
        }
    </style>
    <script>
        function setProductDoneAllDisplay(a,b1,b2){
            $('#prodDoneAll'+a).html(b1+' von '+b2);
        }

    </script>

    <div style="background-color: rgb(39,190,175); min-height: 50cm;" class="pr-4 pl-1 pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Restaurant</strong></p>
        <div class="swiper-container p-0" style="background-color:rgb(39,190,175) ;">
            <div class="swiper-wrapper p-2" style="background-color:rgb(39,190,175) ;">
                @foreach($catsAll as $oneCat)
                
                    <?php $thisCat = kategori::find($oneCat); ?>  
                    <div class="swiper-slide d-flex flex-wrap" style="background-color:white ; border:1px solid rgb(72,81,87); width:100%; flex-direction: row" id="categoryListingCookTO{{$oneCat}}">
                        <h2 class="text-center" style="width:100%; border-bottom:1px solid rgb(72,81,87); background-color:rgb(208,206,207); margin-bottom:0px;"><strong>{{$thisCat->emri}}</strong></h2>
                        <?php  $catStart = 1; ?>

                        @foreach($allActConfOrders as $oneTP)
                            <?php 
                                $theProd = Produktet::findOrFail($oneTP->prodId); 
                                $prodDone = 0;
                                $prodAll = 0;
                            ?>

                            @if($theProd->kategoria == $oneCat && !in_array($oneTP->prodId,$productsBllock) )
                            
                                @if ($catStart == 1)
                                    <p class="text-center productName p-1" style="margin-bottom: 0px; border:2px 2px 0px 2px solid rgb(72,81,87);"><strong>{{$theProd->emri}}</strong></p>
                                    <p id="prodDoneAll{{$oneTP->prodId}}" class="text-center productDoneAll p-1" style="margin-bottom: 0px; border:2px 2px 0px 2px solid rgb(72,81,87); font-weight:bold;"></p>

                                    <div style="width:100%;" class="d-flex flex-wrap prodListDown" id="productListingCookTO{{$oneTP->prodId}}">
                                @else 
                                    <p class="text-center productName p-1 mt-4" style="margin-bottom: 0px; border:2px 2px 0px 2px solid rgb(72,81,87);"><strong>{{$theProd->emri}}</strong></p>
                                    <p id="prodDoneAll{{$oneTP->prodId}}" class="text-center productDoneAll p-1 mt-4" style="margin-bottom: 0px; border:2px 2px 0px 2px solid rgb(72,81,87); font-weight:bold;"></p>

                                    <div style="width:100%;" class="d-flex flex-wrap prodListDown " id="productListingCookTO{{$oneTP->prodId}}">
                                @endif
                    
                                        <?php 
                                            $catStart++; 
                                            $finishedOrThisPr = array();
                                            $notFinishedCount = 0;
                                        ?>
                                        <p class="proColDetailsHead text-center"><strong>Type</strong></p>
                                        <p class="proColDetailsHead text-center"><strong>Extra</strong></p>
                                        <p class="proColDetailsHead"><strong>Kommentar</strong></p>


                                <!-- paraqiten produktet e PA PERFUNDUARA -->
                                @foreach($allActConfOrders as $oneTPIn)
                                    @if($oneTPIn->prodId == $oneTP->prodId && $oneTPIn->OrderSasia > $oneTPIn->OrderSasiaDone)

                                        @if($oneTPIn->OrderType != 'empty')
                                            <?php $theOrType = explode('||',$oneTPIn->OrderType); ?>
                                            <p class="proColDetails text-center">{{$theOrType[0]}}</p>
                                        @else
                                            <p class="proColDetails">---</p>
                                        @endif

                                        @if($oneTPIn->OrderExtra != 'empty')
                                        <p class="proColDetails text-center">
                                            @foreach (explode('--0--',$oneTPIn->OrderExtra) as $oneExThis)
                                                @if (!$loop->first)
                                                <br>
                                                @endif
                                                <?php echo explode('||',$oneExThis)[0]; ?>
                                            @endforeach
                                        </p>
                                        @else
                                        <p class="proColDetails">---</p>
                                        @endif

                                        @if($oneTPIn->OrderKomenti != NULL)
                                        <p class="proColDetails">{{$oneTPIn->OrderKomenti}}</p>
                                        @else
                                        <p class="proColDetails">---</p>
                                        @endif

                                        <p class="proColDetailsBottom d-flex text-center">
                                            <span style="width: 33%;">{{$oneTPIn->OrderSasiaDone}}</span>
                                            <span style="width: 33%;">von</span>
                                            <span style="width: 33%;">{{$oneTPIn->OrderSasia}}</span>
                                            <?php
                                                $prodDone = $prodDone + $oneTPIn->OrderSasiaDone;
                                                $prodAll = $prodAll + $oneTPIn->OrderSasia;
                                            ?>
                                        </p>

                                        <p class="proColDetailsBottom d-flex">
                                            @if($oneTPIn->OrderSasiaDone == 0)
                                            <button class="btn btn-default p-1" style="width: 50%;"><strong></strong></button>
                                            @else
                                            <button onclick="removeOneDone('{{$oneTPIn->id}}','{{$oneTP->prodId}}')" class="btn btn-danger p-1" style="width: 50%;" id="removeOneDoneBtn{{$oneTPIn->id}}">
                                                <strong>-</strong>
                                            </button>
                                            @endif

                                            <button onclick="addOneDone('{{$oneTPIn->id}}','{{$oneTP->prodId}}')" class="btn btn-success p-1" style="width: 50%;" id="addOneDoneBtn{{$oneTPIn->id}}">
                                                <strong>+</strong>
                                            </button>
                                        </p>

                                        <p class="proColDetailsBottom d-flex">
                                            <button class=" btn btn-block btn-warning p-1" style="width: 50%;"
                                            onclick="orderIsFinished('{{$oneTPIn->id}}','{{$oneTP->prodId}}')" id="orderIsFinishedBtn{{$oneTPIn->id}}">
                                                <i class="far fa-circle"></i>
                                            </button>
                                            <span class="pt-1" style="width: 50%;"><strong>T:{{$oneTPIn->tableNr}}</strong></span>
                                        </p>

                                        <?php $notFinishedCount++; ?>
                                    @elseif($oneTPIn->prodId == $oneTP->prodId )
                                        <?php  array_push($finishedOrThisPr,$oneTPIn); ?>
                                    @endif
                                @endforeach 
                                @if($notFinishedCount == 0)
                                    <p style="width: 100%; color:rgb(39,190,175);" class="text-center">
                                        <strong>Für dieses Produkt sind keine ausstehenden Bestellungen vorhanden</strong>
                                    </p>
                                    <script>
                                        $('#productListingCookTO{{$oneTP->prodId}}').attr('style','width:100%; background-color:rgba(0,255,0,0.2);');
                                    </script>
                                @endif

                                <!-- paraqiten produktet e PERFUNDUARA -->

                                @if (count($finishedOrThisPr) > 0)
                                    <hr class="mt-1 mb-1" style="width:100%; color:red;">
                                    @foreach($finishedOrThisPr as $oneTPIn)

                                        @if($oneTPIn->OrderType != 'empty')
                                            <?php $theOrType = explode('||',$oneTPIn->OrderType);?>
                                            <p class="proColDetails text-center">{{$theOrType[0]}}</p>
                                        @else
                                            <p class="proColDetails">---</p>
                                        @endif

                                        @if($oneTPIn->OrderExtra != 'empty')
                                        <p class="proColDetails text-center">
                                            @foreach (explode('--0--',$oneTPIn->OrderExtra) as $oneExThis)
                                                @if (!$loop->first)
                                                <br>
                                                @endif
                                                <?php echo explode('||',$oneExThis)[0]; ?>
                                            @endforeach
                                        </p>
                                        @else
                                        <p class="proColDetails">---</p>
                                        @endif

                                        @if($oneTPIn->OrderKomenti != NULL)
                                        <p class="proColDetails">{{$oneTPIn->OrderKomenti}}</p>
                                        @else
                                        <p class="proColDetails">---</p>
                                        @endif

                                        <p class="proColDetailsBottom d-flex text-center">
                                            <span style="width: 33%;">{{$oneTPIn->OrderSasiaDone}}</span>
                                            <span style="width: 33%;">von</span>
                                            <span style="width: 33%;">{{$oneTPIn->OrderSasia}}</span>
                                            <?php
                                                $prodDone = $prodDone + $oneTPIn->OrderSasiaDone;
                                                $prodAll = $prodAll + $oneTPIn->OrderSasia;
                                            ?>
                                        </p>

                                        <p class="proColDetailsBottom d-flex">
                                            <button onclick="removeOneDone('{{$oneTPIn->id}}','{{$oneTP->prodId}}')" class="btn btn-danger p-1" style="width: 50%;" id="removeOneDoneBtn{{$oneTPIn->id}}">
                                                <strong>-</strong>
                                            </button>

                                            <button class="btn btn-default p-1" style="width: 50%;">
                                            </button>                                
                                        </p>

                                        <p class="proColDetailsBottom d-flex">
                                            <button style="width: 50%;" class="btn btn-block btn-warning p-1" disabled> <i class="far fa-check-circle"></i></i>
                                            </button>
                                            <span class="pt-1" style="width: 50%;"><strong>T:{{$oneTPIn->tableNr}}</strong></span>
                                        </p>
                                    
                                
                                    @endforeach 
                                @endif
                                        <script>
                                            setProductDoneAllDisplay('{{$oneTP->prodId}}','{{$prodDone}}','{{$prodAll}}');
                                        </script>
                                    </div>
                                    
                                <?php array_push($productsBllock, $oneTP->prodId); ?>
                            @endif
                        @endforeach
                        
                    </div>  <!-- end one category  -->
                @endforeach
            </div>
        </div>
    </div>





    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 1.05,
            spaceBetween: 3, 
            breakpoints: {
                601: {slidesPerView: 2.05},
                1001: {slidesPerView: 3.05},
            }   
        });

    
        function addOneDone(toId,prodId){
            $('#addOneDoneBtn'+toId).html('<img style="width:23px; height:23px;" class="gifImg" src="storage/gifs/loading2.gif" alt=""> ')
            $('#addOneDoneBtn'+toId).prop('disabled',true);
            $.ajax({
                url: '{{ route("cookPnl.cookPanelAddOneDoneOrderProd") }}',
                method: 'post',
                data: {
                    tabOrderId: toId,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#productListingCookTO"+prodId).load(location.href+" #productListingCookTO"+prodId+">*","");

                    var statN = $('#prodDoneAll'+prodId).html();
                    var statN2D = statN.split(' ');
                    var aDone = parseInt(statN2D[0], 10),
                        bDone = parseInt(aDone + 1, 10);
                    $('#prodDoneAll'+prodId).html(bDone+' von '+statN2D[2]);
                    if(bDone == statN2D[2]){
                        $('#productListingCookTO'+prodId).attr('style','width:100%; background-color:rgba(0,255,0,0.2);');
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
        function removeOneDone(toId,prodId){
            $('#removeOneDoneBtn'+toId).html('<img style="width:23px; height:23px;" class="gifImg" src="storage/gifs/loading2.gif" alt=""> ')
            $('#removeOneDoneBtn'+toId).prop('disabled',true);
            $.ajax({
                url: '{{ route("cookPnl.cookPanelRemoveOneDoneOrderProd") }}',
                method: 'post',
                data: {
                    tabOrderId: toId,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#productListingCookTO"+prodId).load(location.href+" #productListingCookTO"+prodId+">*","");

                    var statN = $('#prodDoneAll'+prodId).html();
                    var statN2D = statN.split(' ');
                    var aDone = parseInt(statN2D[0], 10),
                        bDone = parseInt(aDone - 1, 10);
                    $('#prodDoneAll'+prodId).html(bDone+' von '+statN2D[2]);

                    $('#productListingCookTO'+prodId).attr('style','width:100%;');
                },
                error: (error) => { console.log(error); }
            });
        }

        function orderIsFinished(toId,prodId){
            $('#orderIsFinishedBtn'+toId).html('<img style="width:23px; height:23px;" class="gifImg" src="storage/gifs/loading2.gif" alt=""> ')
            $('#orderIsFinishedBtn'+toId).prop('disabled',true);
            $.ajax({
                url: '{{ route("cookPnl.cookPanelOrderProdFinished") }}',
                method: 'post',
                data: {
                    tabOrderId: toId,
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    $("#productListingCookTO"+prodId).load(location.href+" #productListingCookTO"+prodId+">*","");
                    var statN = $('#prodDoneAll'+prodId).html();
                    var statN2D = statN.split(' ');
                    var aDone = parseInt(statN2D[0], 10),
                        bDone = parseInt(respo, 10),
                        cDone = parseInt(aDone + bDone, 10);
                    $('#prodDoneAll'+prodId).html(cDone+' von '+statN2D[2]);
                    if(cDone == statN2D[2]){
                        $('#productListingCookTO'+prodId).attr('style','width:100%; background-color:rgba(0,255,0,0.2);');
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    </script>
@endif