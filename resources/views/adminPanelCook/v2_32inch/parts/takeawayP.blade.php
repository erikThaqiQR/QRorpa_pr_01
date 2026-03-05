<?php

    use App\taDeForCookOr;
    use App\cooksProductSelection;
    use App\ekstra;
    use App\Produktet;
    use App\TabOrder;
    use App\kategori;
    use App\LlojetPro;
use App\Orders;
use App\resPlates;
    use Illuminate\Support\Facades\Auth;

    $hasCateAccess = False;
    $hasProdAccess = False;
    $hasTypeAccess = False;
    $hasExtrAccess = False;
    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category']])->count() > 0){$hasCateAccess = True;}
    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product']])->count() > 0){$hasProdAccess = True;}
    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type']])->count() > 0){$hasTypeAccess = True;}
    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra']])->count() > 0){$hasExtrAccess = True;}
    
    $allOrders = array();
    foreach(taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1']])->orderBy('created_at')->get() as $taOr){
        if($taOr->prodSasia != $taOr->prodSasiaDone){
            if(!in_array($taOr->orderId,$allOrders)){
                if($hasCateAccess && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$taOr->prodCat]])->first() != NULL){
                    array_push($allOrders,$taOr->orderId);
                }else if($hasProdAccess && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$taOr->prodId]])->first() != NULL){
                    array_push($allOrders,$taOr->orderId);
                }else if($hasTypeAccess){
                    if($taOr->prodType != 'empty'){
                        $oTf012D = explode('||',$taOr->prodType);
                        $oneTyperef01ID = LlojetPro::where([['kategoria',$taOr->prodCat],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                        if($oneTyperef01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                            array_push($allOrders,$taOr->orderId);
                        }
                    }
                }else if($hasExtrAccess){
                    $hasAnExtra = False;
                    foreach(explode('--0--',$taOr->prodExtra) as $oneExtraref01){

                        $oEf012D = explode('||',$oneExtraref01);
                        $oneExtraref01ID = ekstra::where([['toCat',$taOr->prodCat],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                        if($oneExtraref01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                            $hasAnExtra = True;
                            break;
                        }
                    }
                    if($hasAnExtra){
                        array_push($allOrders,$taOr->orderId);
                    }
                }
            }
        }
    }
?>

@if(count($allOrders) > 0 )
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
        p{
            font-size: 12px;
        }
        h3{
            font-size: 17px;
        }
        h4{
            font-size: 15px;
        }
        h5{
            font-size: 12px;
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

        .crsPointer:hover{
            cursor: pointer;
        }
    </style>

    <script>
        let ScreenWidth = parseInt(screen.width - 30);
        let blockSize = parseFloat((ScreenWidth / parseInt('{{Auth::user()->cookPV2BlShow}}'))-2);
    </script>

    <div style="background-color: rgb(39,190,175); min-height: 50cm;" class="pr-1 pl-1 pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Takeaway ( Offen ) </strong></p>

        <!-- <div class="swiper-container p-0" style="background-color:rgb(39,190,175) ;"> -->
            <div class="d-flex flex-wrap" style="background-color:rgb(39,190,175) ;">
                @foreach($allOrders as $orOne)
                    <?php
                        $tableShow = False;
                        foreach(taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1'],['orderId',$orOne]])->get() as $checkTToShowT){
                            if($checkTToShowT->prodSasia != $checkTToShowT->prodSasiaDone){
                                $tableShow = True;
                                break;
                            }
                        }
                    ?>
                    @if($tableShow)
                    <div class="d-flex flex-wrap" style="background-color:transparent; border-radius:6px; width:100%; flex-direction: row" id="orderColumnCookTO{{$orOne}}">
                        <?php
                            $theOrOf = Orders::find($orOne);
                            $addedToPlate = 0;
                            $orTi = explode(':',explode(' ',$theOrOf->created_at)[1]);
                        ?>
                        <h2 class="text-center mb-2" style="width:100%; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px;">
                            @if($theOrOf->payM == 'Online')
                            <strong><span class="mr-1" style="font-size:1.2rem;">( {{$orTi[0]}}:{{$orTi[1]}} )</span> {{explode('|',$theOrOf->shifra)[1]}} <span style="font-size:1.2rem;">Online</span></strong>
                            @else
                            <strong><span class="mr-1" style="font-size:1.2rem;">( {{$orTi[0]}}:{{$orTi[1]}} )</span> {{explode('|',$theOrOf->shifra)[1]}}</strong>
                            @endif
                        </h2>
                        <?php
                            $allPlates = array();
                            foreach(taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1'],['orderId',$orOne]])->get() as $taOr2){
                                if($taOr2->prodSasia != $taOr2->prodSasiaDone){
                                    $tc = kategori::find($taOr2->prodCat);
                                    if($tc != NULL){ 
                                        if(!in_array($tc->forPlate,$allPlates)){
                                            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$taOr2->prodCat]])->first() != NULL){
                                                array_push($allPlates,$tc->forPlate);
                                                $addedToPlate = 1;
                                            }else if($addedToPlate == 0){
                                                if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$taOr2->prodId]])->first() != NULL){
                                                    array_push($allPlates,$tc->forPlate);
                                                    $addedToPlate = 1;
                                                }
                                                if($addedToPlate == 0 && $taOr2->prodType != 'empty'){
                                                    $oTf012D = explode('||',$taOr2->prodType);
                                                    $oneTyperef01ID = LlojetPro::where([['kategoria',$taOr2->prodCat],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                                                    if($oneTyperef01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                                        array_push($allPlates,$tc->forPlate);
                                                        $addedToPlate = 1;
                                                    }
                                                }
                                                if($addedToPlate == 0 && $taOr2->prodExtra != 'empty'){
                                                    foreach(explode('--0--',$taOr2->prodExtra) as $oneExtraref01){
                                                        $oEf012D = explode('||',$oneExtraref01);
                                                        $oneExtraref01ID = ekstra::where([['toCat',$taOr2->prodCat],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                                                        if($oneExtraref01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                                            array_push($allPlates,$tc->forPlate);
                                                            $addedToPlate = 1;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            sort($allPlates);
                        ?>  
                    
                        @foreach ($allPlates as $plateOne)
                      
                            <div class="text-center pt-2 pb-2 mb-2 allPlate{{$orOne}}" style="width:100%; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px; " 
                                id="plate{{$plateOne}}OnT{{$orOne}}">
                                <?php 
                                    $thePl = resPlates::where([['toRes',Auth::user()->sFor],['desc2C',$plateOne]])->first();
                                    $nrDone = (int)0;
                                    $nrAll = (int)0;
                                ?>
                                @if ( $thePl != NULL)
                                    <h3 onclick="openThisPlate('{{$orOne}}','{{$plateOne}}')" class="mb-1 crsPointer"><strong>{{$thePl->nameTitle}}</strong></h3>
                                @else
                                    <h3 onclick="openThisPlate('{{$orOne}}','{{$plateOne}}')" class="mb-1 crsPointer"><strong>Kein Teller</strong></h3>
                                @endif
                                    <!-- <h4 onclick="openThisPlate('{{$orOne}}','{{$plateOne}}')" class="mb-1 crsPointer"><strong><span id="nrDoneT{{$orOne}}P{{$plateOne}}">0</span> von <span id="nrAllT{{$orOne}}P{{$plateOne}}">0</span></strong></h4> -->
                                    <h4 onclick="openThisPlate('{{$orOne}}','{{$plateOne}}')" class="mb-1 crsPointer"></h4>

                                    <div style="width: 100%;" id="ordersListT{{$orOne}}P{{$plateOne}}">
                                        @foreach(taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1'],['orderId',$orOne]])->orderBy('created_at')->get() as $taOr3)
                                            @if($taOr3->prodSasia != $taOr3->prodSasiaDone)
                                                <?php $tc2 = kategori::find($taOr3->prodCat); 
                                                    $has2nAccess = False;  
                                                ?>
                                                @if($tc2 != NULL && $tc2->forPlate == $plateOne)
                                                    <?php
                                                        if($hasCateAccess && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$taOr3->prodCat]])->first() != NULL){
                                                            $has2nAccess = True;
                                                        }else if($hasProdAccess  && !$has2nAccess && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$taOr3->prodId]])->first() != NULL){
                                                            $has2nAccess = True;
                                                        }else if($hasTypeAccess  && !$has2nAccess){
                                                            if($taOr3->prodType != 'empty'){
                                                                $oTf012D = explode('||',$taOr3->prodType);
                                                                $oneTyperef01ID = LlojetPro::where([['kategoria',$taOr3->prodCat],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                                                                if($oneTyperef01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                                                    $has2nAccess = True;
                                                                }
                                                            }
                                                        }else if($hasExtrAccess && !$has2nAccess){
                                                            $hasAnExtra = False;
                                                            foreach(explode('--0--',$taOr3->prodExtra) as $oneExtraref01){
                                                                $oEf012D = explode('||',$oneExtraref01);
                                                                $oneExtraref01ID = ekstra::where([['toCat',$taOr3->prodCat],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                                                                if($oneExtraref01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                                                    $hasAnExtra = True;
                                                                    break;
                                                                }
                                                            }
                                                            if($hasAnExtra){
                                                                $has2nAccess = True;
                                                            }
                                                        }
                                                    ?>
                                                    @if ($has2nAccess)
                                                        <?php 
                                                            $nrAll += (int)$taOr3->prodSasia; 
                                                            $sas = (int)$taOr3->prodSasia;
                                                            $sasD = (int)$taOr3->prodSasiaDone;
                                                            if($sas == $sasD){ $nrDone += (int)$taOr3->prodSasia; }
                                                        ?>
                                                        @if ( $thePl != NULL)
                                                            @if($sas == $sasD)
                                                            <div onclick="changeStatOrCookNo('{{$orOne}}','{{$plateOne}}','{{$taOr3->id}}')" 
                                                            class="prodOneT{{$orOne}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                            style="width: 100%; background-color:rgba(4,178,89,255); display:flex;"
                                                            id="prodOneT{{$taOr3->id}}">
                                                            @else
                                                            <div onclick="changeStatOrCookYes('{{$orOne}}','{{$plateOne}}','{{$taOr3->id}}','{{$sas}}')" 
                                                            class="prodOneT{{$orOne}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                            style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                                                            id="prodOneT{{$taOr3->id}}">
                                                            @endif
                                                        @else
                                                            @if($sas == $sasD)
                                                            <div onclick="changeStatOrCookNo('{{$orOne}}','{{$plateOne}}','{{$taOr3->id}}')" 
                                                            class="prodOneT{{$orOne}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                            style="width: 100%; background-color:rgba(4,178,89,255); display:flex;"
                                                            id="prodOneT{{$taOr3->id}}">
                                                            @else
                                                            <div onclick="changeStatOrCookYes('{{$orOne}}','{{$plateOne}}','{{$taOr3->id}}','{{$sas}}')" 
                                                            class="prodOneT{{$orOne}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                            style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                                                            id="prodOneT{{$taOr3->id}}">
                                                            @endif
                                                        @endif
                                                            <h4 style="width:84%"><strong>{{$taOr3->prodName}}</strong></h4>
                                                            @if ($taOr3->prodSasia >= 2)
                                                                <h4 class="text-right" style="width:15%; font-size:1.4rem; color:red"><strong>{{$taOr3->prodSasia}}X</strong></h4>
                                                            @else
                                                                <h4 class="text-right" style="width:15%; font-size:1.4rem;"><strong>{{$taOr3->prodSasia}}X</strong></h4>
                                                            @endif
                                                            @if($taOr3->prodType != 'empty')
                                                            <h5 style="width:100%"><strong>Typ: <span style="color:red;">{{explode('||',$taOr3->prodType)[0]}}</span></strong></h5>
                                                            @endif
                                                            @if($taOr3->prodExtra != 'empty')
                                                            <h5 style="width:100%"><strong>Extra:
                                                                <span style="color:red;">
                                                                    @foreach (explode('--0--',$taOr3->prodExtra) as $exO)
                                                                        @if ($loop->first)
                                                                            {{explode('||',$exO)[0]}}
                                                                        @else
                                                                            , {{explode('||',$exO)[0]}}
                                                                        @endif
                                                                    @endforeach
                                                                </span>
                                                            </strong></h5>
                                                            @endif
                                                            @if($taOr3->prodComm != NULL && $taOr3->prodComm != "empty")
                                                            <h5 style="width:100%"><strong>Kommentar: <span style="color:red;">{{$taOr3->prodComm}}</span></strong></h5>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                        <script>
                                            $('#nrAllT{{$orOne}}P{{$plateOne}}').html('{{$nrAll}}');    
                                            $('#nrDoneT{{$orOne}}P{{$plateOne}}').html('{{$nrDone}}');    
                                        </script>
                                        @if ($nrAll == $nrDone)
                                            <script>
                                                $('#plate{{$plateOne}}OnT{{$orOne}}').attr('style','width:100%; border-radius:6px; background-color:rgba(4,178,89,255); margin-bottom:0px;');
                                            </script>
                                        @endif
                                    </div>
                                
                            </div>
                        @endforeach
                    </div>
                    @endif
                    <script>
                        $('#orderColumnCookTO{{$orOne}}').attr('style','background-color:transparent; border-radius:6px; height:fit-content; width:'+blockSize+'px; margin-right:2px; flex-direction: row');
                    </script>
                @endforeach
            </div>
        </div>
    </div>




@else
    <div style="background-color: rgb(39,190,175); min-height: 50cm;" class="pr-2 pl-2 pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Takeaway ( Offen )</strong></p>
        <div style="background-color: white; border-radius:5px;" class="p-2">
            <p style="font-size:1.8rem; color:rgb(39,190,175);" class="text-center"><strong>Es gibt noch keine ausstehenden Bestellungen für Sie!</strong></p>
        </div>
    </div>
@endif



<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 1.05,
        spaceBetween: 5,  
        breakpoints: {
            601: {slidesPerView: 2.05},
            1001: {slidesPerView: 3.05},
            1401: {slidesPerView: 4.05},
            1801: {slidesPerView: 5.1},
            2201: {slidesPerView: 6.1},
            2601: {slidesPerView: 7.1},
            3001: {slidesPerView: 8.1},
        }   
    });

    function openThisPlate(tabNr, plNr){
        if($('.prodOneT'+tabNr+'P'+plNr).is(':hidden')){
            // show
            $('.prodOneT'+tabNr+'P'+plNr).css('display','flex');
        }else{
            // hide
            $('.prodOneT'+tabNr+'P'+plNr).css('display','none');
        }
    }

    function changeStatOrCookYes(tabNr, plNr, tOrId, sas){
        // $('#prodOneT'+tOrId).html('<img style="width:23px; height:23px;" class="gifImg" src="storage/gifs/loading2.gif" alt=""> ')
        $('#prodOneT'+tOrId).prop('disabled',true);
        $("#prodOneT"+tOrId).css('background-color','rgba(4,178,89,255)');
        $.ajax({
            url: '{{ route("cookPnl.cookPanelOrderProdFinishedT") }}',
            method: 'post',
            data: {
                taInstId: tOrId,
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {
                $("#prodOneT"+tOrId).fadeOut(800, function(){ $(this).remove();});

                var addSas = parseInt($('#nrDoneT'+tabNr+'P'+plNr).html());
                var newDoneSas = parseInt(parseInt(addSas)+parseInt(sas));
                $('#nrDoneT'+tabNr+'P'+plNr).html(newDoneSas);

                // $("#prodOneT"+tOrId).attr('onclick','changeStatOrCookNo("'+tabNr+'","'+plNr+'","'+tOrId+'")');


                var allSas = parseInt($('#nrAllT'+tabNr+'P'+plNr).html());
                var doneSas = parseInt($('#nrDoneT'+tabNr+'P'+plNr).html());
                if(allSas == doneSas){
                    $("#plate"+plNr+"OnT"+tabNr).css('background-color','rgba(4,178,89,255)');
                    var notDone = 0;
                    $('.allPlate'+tabNr).each(function(i, obj) {
                        if($( this ).css( "background-color" ) == 'rgb(191, 191, 191)'){
                            notDone++;
                        }
                    });
                    if(notDone == 0){
                        $('#orderColumnCookTO'+tabNr).fadeOut(800, function(){ $(this).remove();});
                    }else{
                        $("#plate"+plNr+"OnT"+tabNr).fadeOut(800, function(){ $(this).remove();});
                    }
                }
                // $('#prodOneT'+tOrId).prop('disabled',false);
            },
            error: (error) => { console.log(error); }
        });
    }
</script>