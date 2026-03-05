<?php

    use App\cooksProductSelection;
    use App\ekstra;
    use App\Produktet;
    use App\kategori;
    use App\LlojetPro;
    use App\taDeForCookOr;
    use Illuminate\Support\Facades\Auth;

    if(taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1']])->count() > 0 ){
        $allActConfOrders = array();
        $cookHasExtrasCount = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra']])->count();
        $allActConfOrdersrefuse01 = array();
        $cookHasTypesCount = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type']])->count();
        $allActConfOrdersrefuse02 = array();

        if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category']])->count() > 0){
            $toFilterByPTE = array();
            foreach(taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1']])->get()->sortByDesc('created_at') as $onePCatFilter){
                if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$onePCatFilter->prodCat]])->first() != NULL){
                    array_push($allActConfOrders,$onePCatFilter);
                }else{
                    array_push($toFilterByPTE,$onePCatFilter);
                }
            }
        }else{
            $toFilterByPTE = taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1']])->get()->sortByDesc('created_at');
        }

        if(count($toFilterByPTE) > 0){
            // product access
            foreach($toFilterByPTE as $toF01){
                $acsToThisProd = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$toF01->prodId]])->first();
                if($acsToThisProd != NULL){
                    array_push($allActConfOrders,$toF01);
                }else if($toF01->prodExtra != 'empty'){
                    array_push($allActConfOrdersrefuse01,$toF01);
                }else if($toF01->prodType != 'empty'){
                    array_push($allActConfOrdersrefuse02,$toF01);
                }
            }
            // Extra access
            if($cookHasExtrasCount > 0 && count($allActConfOrdersrefuse01) > 0){
                foreach($allActConfOrdersrefuse01 as $oneOrRef01){
                    if($oneOrRef01->prodId != 0){
                        $theProdref01 = Produktet::findOrFail($oneOrRef01->prodId);
                        $hasAnExtra = 0;
                        foreach(explode('--0--',$oneOrRef01->prodExtra) as $oneExtraref01){
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
            }
            // Type access
            if($cookHasTypesCount > 0 && count($allActConfOrdersrefuse02) > 0){
                foreach($allActConfOrdersrefuse02 as $oneOrRef02){
                    if($oneOrRef02->prodId != 0){
                        $theProdref02 = Produktet::findOrFail($oneOrRef02->prodId);
                        $oTf022D = explode('||',$oneOrRef02->prodType);
                        if(isset($oTf022D[1])){
                            $oneTyperef01ID = LlojetPro::where([['kategoria',$theProdref02->kategoria],['emri',$oTf022D[0]],['vlera',$oTf022D[1]]])->first();
                        }else{
                            $oneTyperef01ID = LlojetPro::where([['kategoria',$theProdref02->kategoria],['emri',$oTf022D[0]]])->first();
                        }
                        if($oneTyperef01ID != NULL){
                            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                array_push($allActConfOrders,$oneOrRef02);
                            }
                        }
                    }
                }
            }
        }

        $catsAll = array();
        $productsBllock = array();

        foreach($allActConfOrders as $oneTP){
            if(!in_array($oneTP->prodCat,$catsAll)){
                array_push($catsAll,$oneTP->prodCat);
            }
        }
    }else{
        $noOrdersYet = 1;
    }
?>


@if(isset($noOrdersYet))


    <div style="background-color: rgb(39,190,175); min-height: 50cm;" class="pr-2 pl-2 pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Takeaway</strong></p>
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
    <div style="background-color: rgb(39,190,175); min-height: 50cm;" class="pr-4 pl-4 pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Takeaway</strong></p>
        <div class="swiper-container p-0" style="background-color:rgb(39,190,175) ;">
            <div class="swiper-wrapper p-2" style="background-color:rgb(39,190,175) ;">
                @foreach($catsAll as $oneCat)
        
                    <div class="swiper-slide d-flex flex-wrap" style="background-color:white ; border:1px solid rgb(72,81,87); width:100%; flex-direction: row" id="categoryListingCookTO{{$oneCat}}">
                        <?php $thisCat = kategori::find($oneCat); ?>    
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
                                    @if($oneTPIn->prodId == $oneTP->prodId && $oneTPIn->prodSasia > $oneTPIn->prodSasiaDone)

                                        @if($oneTPIn->prodType != 'empty')
                                            <?php $theOrType = explode('||',$oneTPIn->prodType); ?>
                                            <p class="proColDetails text-center">{{$theOrType[0]}}</p>
                                        @else
                                            <p class="proColDetails">---</p>
                                        @endif

                                        @if($oneTPIn->prodExtra != 'empty')
                                        <p class="proColDetails text-center">
                                            @foreach (explode('--0--',$oneTPIn->prodExtra) as $oneExThis)
                                                @if (!$loop->first)
                                                <br>
                                                @endif
                                                <?php echo explode('||',$oneExThis)[0]; ?>
                                            @endforeach
                                        </p>
                                        @else
                                        <p class="proColDetails">---</p>
                                        @endif

                                        @if($oneTPIn->prodComm != NULL)
                                        <p class="proColDetails">{{$oneTPIn->prodComm}}</p>
                                        @else
                                        <p class="proColDetails">---</p>
                                        @endif

                                        <p class="proColDetailsBottom d-flex text-center">
                                            <span style="width: 33%;">{{$oneTPIn->prodSasiaDone}}</span>
                                            <span style="width: 33%;">von</span>
                                            <span style="width: 33%;">{{$oneTPIn->prodSasia}}</span>
                                            <?php
                                                $prodDone = $prodDone + $oneTPIn->prodSasiaDone;
                                                $prodAll = $prodAll + $oneTPIn->prodSasia;
                                            ?>
                                        </p>

                                        <p class="proColDetailsBottom d-flex">
                                            @if($oneTPIn->prodSasiaDone == 0)
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

                                    <p class="proColDetailsBottom">
                                        <button class=" btn btn-block btn-warning p-1" onclick="orderIsFinished('{{$oneTPIn->id}}','{{$oneTP->prodId}}')" id="orderIsFinishedBtn{{$oneTPIn->id}}">
                                                <i class="far fa-circle"></i>
                                            </button>
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

                                        @if($oneTPIn->prodType != 'empty')
                                            <?php $theOrType = explode('||',$oneTPIn->prodType);?>
                                            <p class="proColDetails text-center">{{$theOrType[0]}}</p>
                                        @else
                                            <p class="proColDetails">---</p>
                                        @endif

                                        @if($oneTPIn->prodExtra != 'empty')
                                        <p class="proColDetails text-center">
                                            @foreach (explode('--0--',$oneTPIn->prodExtra) as $oneExThis)
                                                @if (!$loop->first)
                                                <br>
                                                @endif
                                                <?php echo explode('||',$oneExThis)[0]; ?>
                                            @endforeach
                                        </p>
                                        @else
                                        <p class="proColDetails">---</p>
                                        @endif

                                        @if($oneTPIn->prodComm != NULL)
                                        <p class="proColDetails">{{$oneTPIn->prodComm}}</p>
                                        @else
                                        <p class="proColDetails">---</p>
                                        @endif

                                        <p class="proColDetailsBottom d-flex text-center">
                                            <span style="width: 33%;">{{$oneTPIn->prodSasiaDone}}</span>
                                            <span style="width: 33%;">von</span>
                                            <span style="width: 33%;">{{$oneTPIn->prodSasia}}</span>
                                            <?php
                                                $prodDone = $prodDone + $oneTPIn->prodSasiaDone;
                                                $prodAll = $prodAll + $oneTPIn->prodSasia;
                                            ?>
                                        </p>

                                        <p class="proColDetailsBottom d-flex">
                                            <button onclick="removeOneDone('{{$oneTPIn->id}}','{{$oneTP->prodId}}')" class="btn btn-danger p-1" style="width: 50%;" id="removeOneDoneBtn{{$oneTPIn->id}}">
                                                <strong>-</strong>
                                            </button>

                                            <button class="btn btn-default p-1" style="width: 50%;">
                                            </button>                                
                                        </p>

                                        <p class="proColDetailsBottom">
                                            <button class=" btn btn-block btn-warning p-1" disabled> <i class="far fa-check-circle"></i></i>
                                            </button>
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
            spaceBetween: 5, 
            breakpoints: {
                601: {slidesPerView: 2.05},
                1001: {slidesPerView: 3.05},
                1401: {slidesPerView: 4.05},
                1801: {slidesPerView: 5.05},
            }  
        });

        function addOneDone(taInstanceId,prodId){
            $('#addOneDoneBtn'+taInstanceId).html('<img style="width:23px; height:23px;" class="gifImg" src="storage/gifs/loading2.gif" alt=""> ')
            $('#addOneDoneBtn'+taInstanceId).prop('disabled',true);
            $.ajax({
                url: '{{ route("cookPnl.cookPanelAddOneDoneOrderProdT") }}',
                method: 'post',
                data: {
                    taInstId: taInstanceId,
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
        function removeOneDone(taInstanceId,prodId){
            $('#removeOneDoneBtn'+taInstanceId).html('<img style="width:23px; height:23px;" class="gifImg" src="storage/gifs/loading2.gif" alt=""> ')
            $('#removeOneDoneBtn'+taInstanceId).prop('disabled',true);
            $.ajax({
                url: '{{ route("cookPnl.cookPanelRemoveOneDoneOrderProdT") }}',
                method: 'post',
                data: {
                    taInstId: taInstanceId,
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

        function orderIsFinished(taInstanceId,prodId){
            $('#orderIsFinishedBtn'+taInstanceId).html('<img style="width:23px; height:23px;" class="gifImg" src="storage/gifs/loading2.gif" alt=""> ')
            $('#orderIsFinishedBtn'+taInstanceId).prop('disabled',true);
            $.ajax({
                url: '{{ route("cookPnl.cookPanelOrderProdFinishedT") }}',
                method: 'post',
                data: {
                    taInstId: taInstanceId,
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