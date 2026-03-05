<?php

    use App\cooksProductSelection;
    use App\ekstra;
    use App\Produktet;
    use App\TabOrder;
    use App\kategori;
    use App\LlojetPro;
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
    
    $allTables = array();
    foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1']])->orderByDesc('updated_at')->get() as $oTOr){
        if($oTOr->OrderSasia == $oTOr->OrderSasiaDone){
            if(!in_array($oTOr->tableNr,$allTables)){
                if($hasProdAccess && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$oTOr->prodId]])->first() != NULL){
                    array_push($allTables,$oTOr->tableNr);
                }else{
                    $tp = Produktet::find($oTOr->prodId);
                    if($hasCateAccess){
                        if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$tp->kategoria]])->first() != NULL){
                            array_push($allTables,$oTOr->tableNr);
                        }
                    }else if($hasTypeAccess){
                        if($oTOr->OrderType != 'empty'){
                            $oTf012D = explode('||',$oTOr->OrderType);
                            $oneTyperef01ID = LlojetPro::where([['kategoria',$tp->kategoria],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                            if($oneTyperef01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                array_push($allTables,$oTOr->tableNr);
                            }
                        }

                    }else if($hasExtrAccess){
                        $hasAnExtra = False;
                        foreach(explode('--0--',$oTOr->OrderExtra) as $oneExtraref01){

                            $oEf012D = explode('||',$oneExtraref01);
                            $oneExtraref01ID = ekstra::where([['toCat',$tp->kategoria],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                            if($oneExtraref01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                $hasAnExtra = True;
                                break;
                            }
                        }
                        if($hasAnExtra){
                            array_push($allTables,$oTOr->tableNr);
                        }
                    }
                }
            }
        }
    }
?>

@if(count($allTables) > 0 )
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

        .crsPointer:hover{
            cursor: pointer;
        }

        @keyframes glowing {
            0% { box-shadow: 0 0 -20px red; background-position: 0 0;}
            40% { box-shadow: 0 0 30px red; }
            60% { box-shadow: 0 0 30px red; }
            100% { box-shadow: 0 0 -20px red; background-position: 1280px 0;}
        }

        .glowAlert{
            animation: glowing 1000ms linear infinite;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>

    <div style="background-color: rgb(39,190,175); min-height: 50cm;" class="pr-1 pl-1 pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Restaurant ( Bereit )</strong></p>
        <div class="alert alert-info text-center mt-2 mb-2 glowAlert" id="newNotificationAlert" style="display: none; font-size:1.5rem; width:100%;">
            <strong>Es gab einige Änderungen an den derzeit aktiven Bestellungen. Gehen Sie zur Seite Offen, um sie zu überprüfen!</strong>
        </div>
        <div class="swiper-container p-0" style="background-color:rgb(39,190,175) ;">
            <div class="swiper-wrapper p-2" style="background-color:rgb(39,190,175) ;">

                @foreach($allTables as $rTable)
                    <?php
                        $tableShow = False;
                        foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',$rTable]])->orderByDesc('updated_at')->get() as $checkTToShowT){
                            if($checkTToShowT->OrderSasia == $checkTToShowT->OrderSasiaDone){
                                $tableShow = True;
                                break;
                            }
                        }
                    ?>
                    @if($tableShow)
                    <div class="swiper-slide d-flex flex-wrap" style="background-color:transparent; border-radius:6px; width:100%; flex-direction: row" id="TableColumnCookTO{{$rTable}}">
                        <h2 class="text-center mb-2" style="width:100%; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px;"><strong>Tisch {{$rTable}}</strong></h2>
                        <?php
                            $allPlates = array();
                            foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',$rTable]])->orderByDesc('updated_at')->get() as $oTOrThisT){
                                if($oTOrThisT->OrderSasia == $oTOrThisT->OrderSasiaDone){
                                    if($oTOrThisT->toPlate != 0){
                                        $thePla = resPlates::find($oTOrThisT->toPlate);
                                        if($thePla != NULL){
                                            if(!in_array($thePla->desc2C,$allPlates)){
                                                $doneForThis = 0;
                                                $tp = Produktet::find($oTOrThisT->prodId);
                                                if($tp != NULL){ 
                                                    $tc = kategori::find($tp->kategoria);
                                                    if($tc != NULL){ 
                                                        $catCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Category'],['contentId',$tp->kategoria]])->first();
                                                        if($catCookAcs != NULL){
                                                            array_push($allPlates,$thePla->desc2C);
                                                            $doneForThis = 1;
                                                        }else if($doneForThis == 0){
                                                            $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Product'],['contentId',$oTOrThisT->prodId]])->first();
                                                            if($prodCookAcs != NULL){
                                                                array_push($allPlates,$thePla->desc2C);
                                                                $doneForThis = 1;
                                                            }
                                                            if($doneForThis == 0 && $oTOrThisT->OrderExtra != 'empty'){
                                                                foreach(explode('--0--',$oTOrThisT->OrderExtra) as $orExtraOne){
                                                                    $orExtraOne2D = explode('||',$orExtraOne);
                                                                    $oneExtraref01ID = ekstra::where([['toCat',$tp->kategoria],['emri',$orExtraOne2D[0]],['qmimi',$orExtraOne2D[1]]])->first();
                                                                    if($oneExtraref01ID != NULL){
                                                                        $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first();
                                                                        if($prodCookAcs != NULL){ 
                                                                            array_push($allPlates,$thePla->desc2C);
                                                                            $doneForThis = 1;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            if($doneForThis == 0 && $oTOrThisT->OrderType != 'empty'){
                                                                $orType2D = explode('||',$oTOrThisT->OrderType);
                                                                $oneTyperef01ID = LlojetPro::where([['kategoria',$tp->kategoria],['emri',$orType2D[0]],['vlera',$orType2D[1]]])->first();
                                                                $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first();
                                                                if($prodCookAcs != NULL){ 
                                                                    array_push($allPlates,$thePla->desc2C);
                                                                    $doneForThis = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }else{
                                        
                                        $tp = Produktet::find($oTOrThisT->prodId);
                                        if($tp != NULL){ 
                                            $tc = kategori::find($tp->kategoria);
                                            if($tc != NULL){ 
                                                if(!in_array($tc->forPlate,$allPlates)){
                                                    $doneForThis = 0;
                                                    $catCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Category'],['contentId',$tp->kategoria]])->first();
                                                    if($catCookAcs != NULL){
                                                        array_push($allPlates,$tc->forPlate);
                                                        $doneForThis = 1;
                                                    }else if($doneForThis == 0){
                                                        $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Product'],['contentId',$oTOrThisT->prodId]])->first();
                                                        if($prodCookAcs != NULL){
                                                            array_push($allPlates,$tc->forPlate);
                                                            $doneForThis = 1;
                                                        }
                                                        if($doneForThis == 0 && $oTOrThisT->OrderExtra != 'empty'){
                                                            foreach(explode('--0--',$oTOrThisT->OrderExtra) as $orExtraOne){
                                                                $orExtraOne2D = explode('||',$orExtraOne);
                                                                $oneExtraref01ID = ekstra::where([['toCat',$tp->kategoria],['emri',$orExtraOne2D[0]],['qmimi',$orExtraOne2D[1]]])->first();
                                                                if($oneExtraref01ID != NULL){
                                                                    $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first();
                                                                    if($prodCookAcs != NULL){ 
                                                                        array_push($allPlates,$tc->forPlate);
                                                                        $doneForThis = 1;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        if($doneForThis == 0 && $oTOrThisT->OrderType != 'empty'){
                                                            $orType2D = explode('||',$oTOrThisT->OrderType);
                                                            $oneTyperef01ID = LlojetPro::where([['kategoria',$tp->kategoria],['emri',$orType2D[0]],['vlera',$orType2D[1]]])->first();
                                                            $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first();
                                                            if($prodCookAcs != NULL){ 
                                                                array_push($allPlates,$tc->forPlate);
                                                                $doneForThis = 1;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            // sort($allPlates);
                        ?>
                        @foreach ($allPlates as $plateOne)
                            <div class="text-center pt-2 pb-2 mb-2 allPlate{{$rTable}}" style="width:100%; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px; " 
                                id="plate{{$plateOne}}OnT{{$rTable}}">
                                <?php 
                                    $thePl = resPlates::where([['toRes',Auth::user()->sFor],['desc2C',$plateOne]])->first();
                                    $nrDone = (int)0;
                                    $nrAll = (int)0;
                                ?>
                                @if ( $thePl != NULL)
                                    <h3 onclick="openThisPlate('{{$rTable}}','{{$plateOne}}')" class="mb-1 crsPointer"><strong>{{$thePl->nameTitle}}</strong></h3>
                                @else
                                    <!-- <h3 onclick="openThisPlate('{{$rTable}}','{{$plateOne}}')" class="mb-1 crsPointer"><strong>-Kein Name-</strong></h3> -->
                                @endif
                                    <h4 onclick="openThisPlate('{{$rTable}}','{{$plateOne}}')" class="mb-1 crsPointer"><strong><span id="nrDoneT{{$rTable}}P{{$plateOne}}">0</span> von <span id="nrAllT{{$rTable}}P{{$plateOne}}">0</span></strong></h4>

                                    <div style="width: 100%;" id="ordersListT{{$rTable}}P{{$plateOne}}">
                                        @foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',(int)$rTable]])->orderByDesc('updated_at')->get() as $oTOrTP)
                                            @if($oTOrTP->OrderSasia == $oTOrTP->OrderSasiaDone)
                                                @if ($oTOrTP->toPlate == 0)
                                                    <?php  $tp2 = Produktet::find($oTOrTP->prodId); ?>
                                                    @if($tp2 != NULL)
                                                        <?php   $tc2 = kategori::find($tp2->kategoria); 
                                                                $has2nAccess = 0;    
                                                        ?>
                                                        @if($tc2 != NULL && $tc2->forPlate == $plateOne)
                                                            <?php
                                                            // access second check (direct on products)
                                                            $catCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Category'],['contentId',$tp2->kategoria]])->first();
                                                            if($catCookAcs != NULL){
                                                                $has2nAccess = 1; 
                                                            }else if($has2nAccess == 0){
                                                                $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Product'],['contentId',$oTOrTP->prodId]])->first();
                                                                if($prodCookAcs != NULL){
                                                                    $has2nAccess = 1; 
                                                                }
                                                                if($has2nAccess == 0 && $oTOrTP->OrderExtra != 'empty'){
                                                                    foreach(explode('--0--',$oTOrTP->OrderExtra) as $orExtraOne){
                                                                        $orExtraOne2D = explode('||',$orExtraOne);
                                                                        $oneExtraref01ID = ekstra::where([['toCat',$tp2->kategoria],['emri',$orExtraOne2D[0]],['qmimi',$orExtraOne2D[1]]])->first();
                                                                        if($oneExtraref01ID != NULL){
                                                                            $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first();
                                                                            if($prodCookAcs != NULL){ 
                                                                                $has2nAccess = 1; 
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                if($has2nAccess == 0 && $oTOrTP->OrderType != 'empty'){
                                                                    $orType2D = explode('||',$oTOrTP->OrderType);
                                                                    $oneTyperef01ID = LlojetPro::where([['kategoria',$tp2->kategoria],['emri',$orType2D[0]],['vlera',$orType2D[1]]])->first();
                                                                    $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first();
                                                                    if($prodCookAcs != NULL){ 
                                                                        $has2nAccess = 1; 
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            @if ($has2nAccess == 1)
                                                                <?php 
                                                                    $nrAll += (int)$oTOrTP->OrderSasia; 
                                                                    $sas = (int)$oTOrTP->OrderSasia;
                                                                    $sasD = (int)$oTOrTP->OrderSasiaDone;
                                                                    if($sas == $sasD){ $nrDone += (int)$oTOrTP->OrderSasia; }
                                                                ?>
                                                                @if ( $thePl != NULL)
                                                                    @if ($oTOrTP->orderServed == 1)
                                                                    <div class="prodOneT{{$rTable}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgba(4,178,89,255); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @else
                                                                    <div class="prodOneT{{$rTable}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @endif
                                                                @else
                                                                    @if ($oTOrTP->orderServed == 1)
                                                                    <div class="prodOneT{{$rTable}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgba(4,178,89,255); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @else
                                                                    <div class="prodOneT{{$rTable}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @endif
                                                                @endif
                                                                    <?php
                                                                        $orTi = explode(':',explode(' ',$oTOrTP->created_at)[1]);
                                                                        $orUpTi = explode(':',explode(' ',$oTOrTP->updated_at)[1]);
                                                                    ?>
                                                                    <p style="width:100%"><strong>Bestellzeitpunkt: {{$orTi[0]}}:{{$orTi[1]}}</strong></p>
                                                                    <p style="width:100%"><strong>gekocht bestätigt bei: {{$orUpTi[0]}}:{{$orUpTi[1]}}</strong></p>
                                                                    <h4 style="width:84%"><strong>{{$oTOrTP->OrderEmri}}</strong></h4>
                                                                    <h4 class="text-right" style="width:15%"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                    @if($oTOrTP->OrderType != 'empty')
                                                                    <h5 style="width:100%"><strong>Typ: {{explode('||',$oTOrTP->OrderType)[0]}}</strong></h5>
                                                                    @endif
                                                                    @if($oTOrTP->OrderExtra != 'empty')
                                                                    <h5 style="width:100%"><strong>Extra:
                                                                        @foreach (explode('--0--',$oTOrTP->OrderExtra) as $exO)
                                                                            @if ($loop->first)
                                                                                {{explode('||',$exO)[0]}}
                                                                            @else
                                                                                , {{explode('||',$exO)[0]}}
                                                                            @endif
                                                                        @endforeach
                                                                    </strong></h5>
                                                                    @endif
                                                                    @if($oTOrTP->OrderKomenti != NULL)
                                                                    <h5 style="width:100%"><strong>Kommentar: <span style="color:red;">{{$oTOrTP->OrderKomenti}}</span></strong></h5>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    <?php $thePlaOr = resPlates::find($oTOrTP->toPlate);  
                                                    $tp2 = Produktet::find($oTOrTP->prodId);
                                                    $has2nAccess = 0; ?>
                                                    @if ( $thePlaOr->desc2C == $plateOne && $tp2 != Null)
                                                        <?php $tc2 = kategori::find($tp2->kategoria); ?>
                                                        @if ($tc2 != Null)  
                                                        <?php
                                                        // access second check (direct on products)
                                                        $catCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Category'],['contentId',$tp2->kategoria]])->first();
                                                        if($catCookAcs != NULL){
                                                            $has2nAccess = 1; 
                                                        }else if($has2nAccess == 0){
                                                            $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Product'],['contentId',$oTOrTP->prodId]])->first();
                                                            if($prodCookAcs != NULL){
                                                                $has2nAccess = 1; 
                                                            }
                                                            if($has2nAccess == 0 && $oTOrTP->OrderExtra != 'empty'){
                                                                foreach(explode('--0--',$oTOrTP->OrderExtra) as $orExtraOne){
                                                                    $orExtraOne2D = explode('||',$orExtraOne);
                                                                    $oneExtraref01ID = ekstra::where([['toCat',$tp2->kategoria],['emri',$orExtraOne2D[0]],['qmimi',$orExtraOne2D[1]]])->first();
                                                                    if($oneExtraref01ID != NULL){
                                                                        $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first();
                                                                        if($prodCookAcs != NULL){ 
                                                                            $has2nAccess = 1; 
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            if($has2nAccess == 0 && $oTOrTP->OrderType != 'empty'){
                                                                $orType2D = explode('||',$oTOrTP->OrderType);
                                                                $oneTyperef01ID = LlojetPro::where([['kategoria',$tp2->kategoria],['emri',$orType2D[0]],['vlera',$orType2D[1]]])->first();
                                                                $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first();
                                                                if($prodCookAcs != NULL){ 
                                                                    $has2nAccess = 1; 
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        @endif

                                                        @if ($has2nAccess == 1)
                                                            <?php 
                                                                $nrAll += (int)$oTOrTP->OrderSasia; 
                                                                $sas = (int)$oTOrTP->OrderSasia;
                                                                $sasD = (int)$oTOrTP->OrderSasiaDone;
                                                                if($sas == $sasD){ $nrDone += (int)$oTOrTP->OrderSasia; }
                                                            ?>
                                                            @if ($oTOrTP->orderServed == 1)
                                                            <div class="prodOneT{{$rTable}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                            style="width: 100%; background-color:rgba(4,178,89,255); display:flex;"
                                                            id="prodOneT{{$oTOrTP->id}}">
                                                            @else
                                                            <div class="prodOneT{{$rTable}}P{{$plateOne}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                            style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                                                            id="prodOneT{{$oTOrTP->id}}">
                                                            @endif
                                                                <?php
                                                                    $orTi = explode(':',explode(' ',$oTOrTP->created_at)[1]);
                                                                    $orUpTi = explode(':',explode(' ',$oTOrTP->updated_at)[1]);
                                                                ?>
                                                                <p style="width:100%"><strong>Bestellzeitpunkt: {{$orTi[0]}}:{{$orTi[1]}}</strong></p>
                                                                <p style="width:100%"><strong>gekocht bestätigt bei: {{$orUpTi[0]}}:{{$orUpTi[1]}}</strong></p>
                                                                <h4 style="width:84%"><strong>{{$oTOrTP->OrderEmri}}</strong></h4>
                                                                <h4 class="text-right" style="width:15%"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                @if($oTOrTP->OrderType != 'empty')
                                                                <h5 style="width:100%"><strong>Typ: {{explode('||',$oTOrTP->OrderType)[0]}}</strong></h5>
                                                                @endif
                                                                @if($oTOrTP->OrderExtra != 'empty')
                                                                <h5 style="width:100%"><strong>Extra:
                                                                    @foreach (explode('--0--',$oTOrTP->OrderExtra) as $exO)
                                                                        @if ($loop->first)
                                                                            {{explode('||',$exO)[0]}}
                                                                        @else
                                                                            , {{explode('||',$exO)[0]}}
                                                                        @endif
                                                                    @endforeach
                                                                </strong></h5>
                                                                @endif
                                                                @if($oTOrTP->OrderKomenti != NULL)
                                                                <h5 style="width:100%"><strong>Kommentar: <span style="color:red;">{{$oTOrTP->OrderKomenti}}</span></strong></h5>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach

                                        <script>
                                            $('#nrAllT{{$rTable}}P{{$plateOne}}').html('{{$nrAll}}');    
                                            $('#nrDoneT{{$rTable}}P{{$plateOne}}').html('{{$nrDone}}');    
                                        </script>
                                        @if ($nrAll == $nrDone)
                                            <script>
                                                $('#plate{{$plateOne}}OnT{{$rTable}}').attr('style','width:100%; border-radius:6px; background-color:rgba(4,178,89,255); margin-bottom:0px;');
                                                // $('#plate{{$plateOne}}OnT{{$rTable}}').remove();
                                            </script>
                                        @endif
                                    </div>
                                
                            </div>
                            
                        @endforeach
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>



@else
    <div style="background-color: rgb(39,190,175); min-height: 50cm;" class="pr-2 pl-2 pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Restaurant ( Bereit )</strong></p>
        <div class="alert alert-info text-center mt-2 mb-2 glowAlert" id="newNotificationAlert" style="display: none; font-size:1.5rem; width:100%;">
            <strong>Es gab einige Änderungen an den derzeit aktiven Bestellungen. Gehen Sie zur Seite Offen, um sie zu überprüfen!</strong>
        </div>
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
        $.ajax({
            url: '{{ route("cookPnl.cookPanelOrderProdFinished") }}',
            method: 'post',
            data: {
                tabOrderId: tOrId,
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {
                $("#prodOneT"+tOrId).css('background-color','rgba(4,178,89,255)');

                var addSas = parseInt($('#nrDoneT'+tabNr+'P'+plNr).html());
                var newDoneSas = parseInt(parseInt(addSas)+parseInt(sas));
                $('#nrDoneT'+tabNr+'P'+plNr).html(newDoneSas);

                $("#prodOneT"+tOrId).attr('onclick','changeStatOrCookNo("'+tabNr+'","'+plNr+'","'+tOrId+'")');

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
                        $('#TableColumnCookTO'+tabNr).remove();
                    }else{
                        $("#plate"+plNr+"OnT"+tabNr).remove();
                    }
                }
                $('#prodOneT'+tOrId).prop('disabled',false);
            },
            error: (error) => { console.log(error); }
        });
    }
</script>