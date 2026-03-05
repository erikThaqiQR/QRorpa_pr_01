<?php

    use App\cooksProductSelection;
    use App\ekstra;
    use App\Produktet;
    use App\TabOrder;
    use App\kategori;
    use App\LlojetPro;
    use App\resPlates;
    use App\cookColor;
    use App\Restorant;
    use Illuminate\Support\Facades\Auth;

    $hasCateAccess = False;
    $hasProdAccess = False;
    $hasTypeAccess = False;
    $hasExtrAccess = False;
    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category']])->count() > 0){$hasCateAccess = True;}
    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product']])->count() > 0){$hasProdAccess = True;}
    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type']])->count() > 0){$hasTypeAccess = True;}
    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra']])->count() > 0){$hasExtrAccess = True;}

    $theRestorant = Restorant::find(Auth::User()->sFor);
    
    $allTables = array();
    foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1']])->orderBy('created_at')->get() as $oTOr){
        if($oTOr->OrderSasia != $oTOr->OrderSasiaDone){
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

    $totLines = ceil(count($allTables) / 10);

    $plateColorCnt = 0;
?>

@if(count($allTables) > 0 )
    <style>
        :root {
            --blockSize: 7cm;
        }

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

        @keyframes glowing {
            0% { box-shadow: 0 0 -10px rgb(208, 250, 207); }
            40% { box-shadow: 0 0 20px rgb(208, 250, 207); }
            60% { box-shadow: 0 0 20px rgb(208, 250, 207); }
            100% { box-shadow: 0 0 -10px rgb(208, 250, 207); }
        }

        .cookDivGlowCalled {
            animation: glowing 700ms infinite;
        }

        .TableColumnCookTOAll{
            background-color:transparent; 
            border-radius:6px; 
            height:fit-content; 
            width:var(--blockSize); 
            margin-right:2px; 
            flex-direction: row;
        }
      
        .plateColor1{ background-color: var(--plateColor1) !important; }
        .plateColor2{ background-color: var(--plateColor2) !important; }
        .plateColor3{ background-color: var(--plateColor3) !important; }
        .plateColor4{ background-color: var(--plateColor4) !important; }
        .plateColor5{ background-color: var(--plateColor5) !important; }
        .plateColor6{ background-color: var(--plateColor6) !important; }
        .plateColor7{ background-color: var(--plateColor7) !important; }
        .plateColor8{ background-color: var(--plateColor8) !important; }
        .plateColor9{ background-color: var(--plateColor9) !important; }
        .plateColor10{ background-color: var(--plateColor10) !important; }
        .plateColor11{ background-color: var(--plateColor11) !important; }
        .plateColor12{ background-color: var(--plateColor12) !important; }
        .plateColor13{ background-color: var(--plateColor13) !important; }
        .plateColor14{ background-color: var(--plateColor14) !important; }
        .plateColor15{ background-color: var(--plateColor15) !important; }
        .plateColor16{ background-color: var(--plateColor16) !important; }
        .plateColor17{ background-color: var(--plateColor17) !important; }
        .plateColor18{ background-color: var(--plateColor18) !important; }
        .plateColor19{ background-color: var(--plateColor19) !important; }
        .plateColor20{ background-color: var(--plateColor20) !important; }
        .plateColor21{ background-color: var(--plateColor21) !important; }
        .plateColor22{ background-color: var(--plateColor22) !important; }
        .plateColor23{ background-color: var(--plateColor23) !important; }
        .plateColor24{ background-color: var(--plateColor24) !important; }
        .plateColor25{ background-color: var(--plateColor25) !important; }
        .plateColor26{ background-color: var(--plateColor26) !important; }
        .plateColor27{ background-color: var(--plateColor27) !important; }
        .plateColor28{ background-color: var(--plateColor28) !important; }
        .plateColor29{ background-color: var(--plateColor29) !important; }
        .plateColor30{ background-color: var(--plateColor30) !important; }
        .plateColor31{ background-color: var(--plateColor31) !important; }
        .plateColor32{ background-color: var(--plateColor32) !important; }
        .plateColor33{ background-color: var(--plateColor33) !important; }
        .plateColor34{ background-color: var(--plateColor34) !important; }
        .plateColor35{ background-color: var(--plateColor35) !important; }
        .plateColor36{ background-color: var(--plateColor36) !important; }
        .plateColor37{ background-color: var(--plateColor37) !important; }
        .plateColor38{ background-color: var(--plateColor38) !important; }
        .plateColor39{ background-color: var(--plateColor39) !important; }
        .plateColor40{ background-color: var(--plateColor40) !important; }
        .plateColor41{ background-color: var(--plateColor41) !important; }
        .plateColor42{ background-color: var(--plateColor42) !important; }
        .plateColor43{ background-color: var(--plateColor43) !important; }
        .plateColor44{ background-color: var(--plateColor44) !important; }
        .plateColor45{ background-color: var(--plateColor45) !important; }
        .plateColor46{ background-color: var(--plateColor46) !important; }
        .plateColor47{ background-color: var(--plateColor47) !important; }
        .plateColor48{ background-color: var(--plateColor48) !important; }
        .plateColor49{ background-color: var(--plateColor49) !important; }
        .plateColor50{ background-color: var(--plateColor50) !important; }
        .plateColor51{ background-color: var(--plateColor51) !important; }
        .plateColor52{ background-color: var(--plateColor52) !important; }
        .plateColor53{ background-color: var(--plateColor53) !important; }
        .plateColor54{ background-color: var(--plateColor54) !important; }
        .plateColor55{ background-color: var(--plateColor55) !important; }
        .plateColor56{ background-color: var(--plateColor56) !important; }
        .plateColor57{ background-color: var(--plateColor57) !important; }
        .plateColor58{ background-color: var(--plateColor58) !important; }
        .plateColor59{ background-color: var(--plateColor59) !important; }
        .plateColor60{ background-color: var(--plateColor60) !important; }
        .plateColor61{ background-color: var(--plateColor61) !important; }
        .plateColor62{ background-color: var(--plateColor62) !important; }
        .plateColor63{ background-color: var(--plateColor63) !important; }
        .plateColor64{ background-color: var(--plateColor64) !important; }
        .plateColor65{ background-color: var(--plateColor65) !important; }
        .plateColor66{ background-color: var(--plateColor66) !important; }
        .plateColor67{ background-color: var(--plateColor67) !important; }
        .plateColor68{ background-color: var(--plateColor68) !important; }
        .plateColor69{ background-color: var(--plateColor69) !important; }
        .plateColor60{ background-color: var(--plateColor60) !important; }
        .plateColor61{ background-color: var(--plateColor61) !important; }
        .plateColor62{ background-color: var(--plateColor62) !important; }
        .plateColor63{ background-color: var(--plateColor63) !important; }
        .plateColor64{ background-color: var(--plateColor64) !important; }
        .plateColor65{ background-color: var(--plateColor65) !important; }
        .plateColor66{ background-color: var(--plateColor66) !important; }
        .plateColor67{ background-color: var(--plateColor67) !important; }
        .plateColor68{ background-color: var(--plateColor68) !important; }
        .plateColor69{ background-color: var(--plateColor69) !important; }

                   
    </style>

    <script>
        let ScreenWidth = parseInt(screen.width - 30);
        let blockSize = parseFloat((ScreenWidth / parseInt('{{Auth::user()->cookPV2BlShow}}'))-2);

        // function setPlatesDoneAndAll(tblNr, pltId, allSas, doneSas){
        //     var id1 = 'nrAllT'+tblNr+'P'+pltId;
        //     var id2 = 'nrDoneT'+tblNr+'P'+pltId;
        //     $('#'+id1).html(allSas);
        //     $('#'+id2).html(doneSas);   
        // }

        var r = document.querySelector(':root');
        r.style.setProperty('--blockSize', blockSize+'px');

        var plateColorCnt = 1;

        var plateColorR = parseInt(0);
        var plateColorG = parseInt(0);
        var plateColorB = parseInt(0);

    </script>



  

    <div style="background-color: rgb(39,190,175); min-height: 45cm;" class="pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Restaurant ( Offen )</strong></p>
       
            <!-- <div class="swiper-container p-0" style="background-color:rgb(39,190,175); max-height:20cm;"> -->
                <div class="d-flex flex-wrap" style="background-color:rgb(39,190,175) ;" id="cookPanelTablesAll">

                    <?php
                        $countTOrders = 0;
                        foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['toPlate','!=','0'],['abrufenStat','1']])->orderBy('updated_at')->get() as $ctorder){
                            if($ctorder->OrderSasia != $ctorder->OrderSasiaDone){ 
                                $countTOrders++;
                                break;
                            }
                        }
                    ?>

                    @if ( $theRestorant->showAbgerufenFirst == 1 && $countTOrders > 0)
                    <!-- Abgerufen end -->

                    <div class="d-flex flex-wrap TableColumnCookTOAll" id="TableColumnCookTO-1">
                        <h2 class="text-center mb-2 crsPointer" style="width:100%; height:fit-content; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px;"
                        ondblclick="changeStatOrCookYesFoAllTable('-1')">
                            <strong>Abgerufen</strong>
                        </h2>

                                <?php
                                    $allPlates = array();
                                    foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['toPlate','!=','0'],['abrufenStat','1']])->orderBy('updated_at')->get() as $oTOrThisT){
                                        if($oTOrThisT->OrderSasia != $oTOrThisT->OrderSasiaDone){ 
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
                                                            if($tc->forPlate != 0){
                                                                array_push($allPlates,$tc->forPlate);
                                                                $doneForThis = 1;
                                                            }
                                                        }else if($doneForThis == 0){
                                                            $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Product'],['contentId',$oTOrThisT->prodId]])->first();
                                                            if($prodCookAcs != NULL){
                                                                if($tc->forPlate != 0){
                                                                    array_push($allPlates,$tc->forPlate);
                                                                    $doneForThis = 1;
                                                                }
                                                            }
                                                            if($doneForThis == 0 && $oTOrThisT->OrderExtra != 'empty'){
                                                                foreach(explode('--0--',$oTOrThisT->OrderExtra) as $orExtraOne){
                                                                    $orExtraOne2D = explode('||',$orExtraOne);
                                                                    $oneExtraref01ID = ekstra::where([['toCat',$tp->kategoria],['emri',$orExtraOne2D[0]],['qmimi',$orExtraOne2D[1]]])->first();
                                                                    if($oneExtraref01ID != NULL){
                                                                        $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first();
                                                                        if($prodCookAcs != NULL){ 
                                                                            if($tc->forPlate != 0){
                                                                                array_push($allPlates,$tc->forPlate);
                                                                                $doneForThis = 1;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            if($doneForThis == 0 && $oTOrThisT->OrderType != 'empty'){
                                                                $orType2D = explode('||',$oTOrThisT->OrderType);
                                                                $oneTyperef01ID = LlojetPro::where([['kategoria',$tp->kategoria],['emri',$orType2D[0]],['vlera',$orType2D[1]]])->first();
                                                                $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first();
                                                                if($prodCookAcs != NULL){ 
                                                                    if($tc->forPlate != 0){
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
                                    }
                                ?>
                                @foreach ($allPlates as $plateOne)
                                    <?php 
                                        $thePl = resPlates::where([['toRes',Auth::user()->sFor],['desc2C',$plateOne]])->first();
                                        $nrDone = (int)0;
                                        $nrAll = (int)0;
                                        $tabOrCalled = 0;
                                        foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['abrufenStat','1'],['toPlate',$thePl->id]])->orderBy('updated_at')->get() as $toOneCnt){
                                            if($toOneCnt->OrderSasia != $toOneCnt->OrderSasiaDone){
                                                $tabOrCalled++;
                                                break;
                                            }
                                        }

                                    ?>
                                    @if( $tabOrCalled > 0 )
                                    <div class="text-center pt-2 pb-2 mb-2 allPlate-1 cookDivGlowCalled" style="width:100%; height:fit-content; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px; " 
                                        id="plate{{$plateOne}}OnT-1">
                                    @else
                                    <div class="text-center pt-2 pb-2 mb-2 allPlate-1" style="width:100%; height:fit-content; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px; " 
                                        id="plate{{$plateOne}}OnT-1">
                                    @endif
                                    
                                        @if ( $thePl != NULL)
                                            <h3 onclick="openThisPlate('-1','{{$plateOne}}')" class="mb-1 crsPointer"><strong>{{$thePl->nameTitle}}</strong></h3>
                                        @else
                                            <!-- <h3 onclick="openThisPlate('-1','{{$plateOne}}')" class="mb-1 crsPointer"><strong>-Kein Name-</strong></h3> -->
                                        @endif
                                            <!-- <h4 onclick="openThisPlate('-1','{{$plateOne}}')" class="mb-1 crsPointer"><strong><span id="nrDoneT-1P{{$plateOne}}">0</span> von <span id="nrAllT-1P{{$plateOne}}">0</span></strong></h4> -->

                                            <div style="width: 100%;" class="plateShowDiv" id="ordersListT-1P{{$plateOne}}">
                                                @foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['toPlate','!=','0'],['abrufenStat','1']])->orderBy('updated_at')->get() as $oTOrTP)
                                                    @if($oTOrTP->OrderSasia != $oTOrTP->OrderSasiaDone)

                                                        
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
                                                                    @if($oTOrTP->abrufenStat == 1)
                                                                    <div ondblclick="changeStatOrCookYes('-1','{{$plateOne}}','{{$oTOrTP->id}}','{{$sas}}')"
                                                                    class="prodOneT-1P{{$plateOne}} proShowDiv text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgb(208, 250, 207); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @else
                                                                    <div ondblclick="changeStatOrCookYes('-1','{{$plateOne}}','{{$oTOrTP->id}}','{{$sas}}')" 
                                                                    class="prodOneT-1P{{$plateOne}} proShowDiv text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @endif
                                                                        <?php
                                                                            $orTi = explode(':',explode(' ',$oTOrTP->updated_at)[1]);
                                                                        ?>
                                                                        <p style="width:50%"><strong>Abgerufen: {{$orTi[0]}}:{{$orTi[1]}}</strong></p>
                                                                        <p style="width:50%; text-align:right;"><strong>Tisch: {{$oTOrTP->tableNr}}</strong></p>
                                                                        <h4 style="width:84%"><strong>{{$oTOrTP->OrderEmri}}</strong></h4>
                                                                        @if ($oTOrTP->OrderSasia >= 2)
                                                                            <h4 class="text-right" style="width:15%; font-size:1.4rem; color:red"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                        @else
                                                                            <h4 class="text-right" style="width:15%; font-size:1.4rem;"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                        @endif
                                                                        @if($oTOrTP->OrderType != 'empty')
                                                                        <h5 style="width:100%"><strong>Typ: <span style="color:red;">{{explode('||',$oTOrTP->OrderType)[0]}}</span></strong></h5>
                                                                        @endif
                                                                        @if($oTOrTP->OrderExtra != 'empty')
                                                                        <h5 style="width:100%"><strong>Extra:
                                                                            <span style="color:red;">
                                                                                @foreach (explode('--0--',$oTOrTP->OrderExtra) as $exO)
                                                                                    @if ($loop->first)
                                                                                        {{explode('||',$exO)[0]}}
                                                                                    @else
                                                                                        , {{explode('||',$exO)[0]}}
                                                                                    @endif
                                                                                @endforeach
                                                                            </span>
                                                                        </strong></h5>
                                                                        @endif
                                                                        @if($oTOrTP->OrderKomenti != NULL)
                                                                        <h5 style="width:100%"><strong>Kommentar: <span style="color:red;">{{$oTOrTP->OrderKomenti}}</span></strong></h5>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        
                                                    @endif
                                                @endforeach

                                                <!-- <script>
                                                    setPlatesDoneAndAll('-1','{{$plateOne}}','{{$nrAll}}','{{$nrDone}}');
                                                </script> -->
                                                @if ($nrAll == $nrDone)
                                                    <script>
                                                        $('#plate{{$plateOne}}OnT-1').attr('style','width:100%; border-radius:6px; background-color:rgba(4,178,89,255); margin-bottom:0px;');
                                                        $('#plate{{$plateOne}}OnT-1').remove();
                                                    </script>
                                                @endif
                                            </div>
                                    </div>
                                    
                                @endforeach

                                <?php
                                    $toPlateZeroCnt = 0;
                                    foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['abrufenStat','1'],['toPlate',0]])->orderBy('updated_at')->get() as $toOne){
                                        if($toOne->OrderSasia != $toOne->OrderSasiaDone){
                                            $toPlateZeroCnt++;
                                            break;
                                        }
                                    }
                                ?>

                                @if($toPlateZeroCnt > 0)
                                    <?php 
                                        $nrDone = (int)0;
                                        $nrAll = (int)0;
                                    ?>
                                    <div class="text-center pt-2 pb-2 mb-2 allPlate-1" style="width:100%; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px; " 
                                        id="plate0OnT-1">
                                       
                                        <h3 onclick="openThisPlate('-1','0')" class="mb-1 crsPointer"><strong>Kein Teller</strong></h3>
                                        <!-- <h4 onclick="openThisPlate('-1','0')" class="mb-1 crsPointer"><strong><span id="nrDoneT-1P0">0</span> von <span id="nrAllT-1P0">0</span></strong></h4> -->
                                    
                                        <div style="width: 100%;" class="plateShowDiv" id="ordersListT-1P0">
                                                <?php
                                                    $theTabOrders = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['toPlate','0'],['abrufenStat','1']])->orderBy('updated_at')->get();
                                                ?>
                                                @foreach($theTabOrders as $oTOrTP)
                                                    @if($oTOrTP->OrderSasia != $oTOrTP->OrderSasiaDone)
                                                
                                                        <?php  $tp2 = Produktet::find($oTOrTP->prodId); ?>
                                                        @if($tp2 != NULL)
                                                            <?php   $tc2 = kategori::find($tp2->kategoria); 
                                                                    $has2nAccess = 0;    
                                                            ?>
                                                            @if($tc2 != NULL)
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
                                                                
                                                                    @if($oTOrTP->abrufenStat == 1)
                                                                    <div ondblclick="changeStatOrCookYes('-1','0','{{$oTOrTP->id}}','{{$sas}}')" 
                                                                    class="prodOneT-1P0 text-left proShowDiv justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgb(208, 250, 207); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @else
                                                                    <div ondblclick="changeStatOrCookYes('-1','0','{{$oTOrTP->id}}','{{$sas}}')" 
                                                                    class="prodOneT-1P0 text-left proShowDiv justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @endif
                                                                
                                                                        <?php
                                                                            $orTi = explode(':',explode(' ',$oTOrTP->updated_at)[1]);
                                                                        ?>
                                                                        <p style="width:50%"><strong>Abgerufen: {{$orTi[0]}}:{{$orTi[1]}}</strong></p>
                                                                        <p style="width:50%; text-align:right;"><strong>Tisch: {{$oTOrTP->tableNr}}</strong></p>
                                                                        <h4 style="width:84%"><strong>{{$oTOrTP->OrderEmri}}</strong></h4>
                                                                        @if ($oTOrTP->OrderSasia >= 2)
                                                                            <h4 class="text-right" style="width:15%; font-size:1.4rem; color:red"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                        @else
                                                                            <h4 class="text-right" style="width:15%; font-size:1.4rem;"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                        @endif
                                                                        @if($oTOrTP->OrderType != 'empty')
                                                                        <h5 style="width:100%"><strong>Typ: <span style="color:red;">{{explode('||',$oTOrTP->OrderType)[0]}}</span></strong></h5>
                                                                        @endif
                                                                        @if($oTOrTP->OrderExtra != 'empty')
                                                                        <h5 style="width:100%"><strong>Extra:
                                                                            <span style="color:red;">
                                                                                @foreach (explode('--0--',$oTOrTP->OrderExtra) as $exO)
                                                                                    @if ($loop->first)
                                                                                        {{explode('||',$exO)[0]}}
                                                                                    @else
                                                                                        , {{explode('||',$exO)[0]}}
                                                                                    @endif
                                                                                @endforeach
                                                                            </span>
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

                                                <!-- <script> 
                                                    setPlatesDoneAndAll('-1','0','{{$nrAll}}','{{$nrDone}}');  
                                                </script> -->
                                                @if ($nrAll == $nrDone)
                                                    <script>
                                                        $('#plate0OnT-1').attr('style','width:100%; border-radius:6px; background-color:rgba(4,178,89,255); margin-bottom:0px;');
                                                        $('#plate0OnT-1').remove();
                                                    </script>
                                                @endif
                                            </div>
                                    </div>
                                @endif


                    </div>

                    <!-- Abgerufen end -->
                    @endif











































                    @foreach($allTables as $rTable)
                        
                            <?php
                                $tableShow = False;
                                if ( $theRestorant->showAbgerufenFirst == 1){
                                    $theTabOrders = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',$rTable],['abrufenStat','!=','1']])->get();
                                }else{
                                    $theTabOrders = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',$rTable]])->get();
                                }
                                foreach($theTabOrders as $checkTToShowT){
                                    if($checkTToShowT->OrderSasia != $checkTToShowT->OrderSasiaDone){
                                        $tableShow = True;
                                        break;
                                    }
                                }
                            ?>
                        
                            @if($tableShow)
                            <div class="d-flex flex-wrap TableColumnCookTOAll" id="TableColumnCookTO{{$rTable}}">
                                <h2 class="text-center mb-2 crsPointer" style="width:100%; height:fit-content; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px;"
                                ondblclick="changeStatOrCookYesFoAllTable('{{$rTable}}')">
                                    <strong>Tisch {{$rTable}}</strong>
                                </h2>
                                <?php
                                    $allPlates = array();
                                    if ( $theRestorant->showAbgerufenFirst == 1){
                                        $theTabOrders = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',$rTable],['toPlate','!=','0'],['abrufenStat','!=','1']])->get();
                                    }else{
                                        $theTabOrders = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',$rTable],['toPlate','!=','0']])->get();
                                    }
                                    foreach($theTabOrders as $oTOrThisT){
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
                                                            if($tc->forPlate != 0){
                                                                array_push($allPlates,$tc->forPlate);
                                                                $doneForThis = 1;
                                                            }
                                                        }else if($doneForThis == 0){
                                                            $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Product'],['contentId',$oTOrThisT->prodId]])->first();
                                                            if($prodCookAcs != NULL){
                                                                if($tc->forPlate != 0){
                                                                    array_push($allPlates,$tc->forPlate);
                                                                    $doneForThis = 1;
                                                                }
                                                            }
                                                            if($doneForThis == 0 && $oTOrThisT->OrderExtra != 'empty'){
                                                                foreach(explode('--0--',$oTOrThisT->OrderExtra) as $orExtraOne){
                                                                    $orExtraOne2D = explode('||',$orExtraOne);
                                                                    $oneExtraref01ID = ekstra::where([['toCat',$tp->kategoria],['emri',$orExtraOne2D[0]],['qmimi',$orExtraOne2D[1]]])->first();
                                                                    if($oneExtraref01ID != NULL){
                                                                        $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first();
                                                                        if($prodCookAcs != NULL){ 
                                                                            if($tc->forPlate != 0){
                                                                                array_push($allPlates,$tc->forPlate);
                                                                                $doneForThis = 1;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            if($doneForThis == 0 && $oTOrThisT->OrderType != 'empty'){
                                                                $orType2D = explode('||',$oTOrThisT->OrderType);
                                                                $oneTyperef01ID = LlojetPro::where([['kategoria',$tp->kategoria],['emri',$orType2D[0]],['vlera',$orType2D[1]]])->first();
                                                                $prodCookAcs = cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first();
                                                                if($prodCookAcs != NULL){ 
                                                                    if($tc->forPlate != 0){
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
                                    sort($allPlates);
                                ?>
                                @foreach ($allPlates as $plateOne)
                                    <?php 
                                        $thePl = resPlates::where([['toRes',Auth::user()->sFor],['desc2C',$plateOne]])->first();
                                        $nrDone = (int)0;
                                        $nrAll = (int)0;

                                        $tabOrCalled = 0;
                                        foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['tableNr',$rTable],['status','1'],['abrufenStat','1'],['toPlate',$thePl->id]])->orderBy('updated_at')->get() as $toOneCnt){
                                            if($toOneCnt->OrderSasia != $toOneCnt->OrderSasiaDone){
                                                $tabOrCalled++;
                                                break;
                                            }
                                        }
                                        
                                        $cookColoreIns = cookColor::where([['cookId',Auth::user()->id],['plateId',$thePl->id]])->first();
                                        if($cookColoreIns != Null){
                                            $color2D = explode(',',$cookColoreIns->colorRGB);
                                        }
                                    ?>

                                    @if($cookColoreIns != Null)
                                        <script>
                                            console.log(plateColorCnt);
                                            plateColorR = parseInt('{{$color2D[0]}}');
                                            plateColorG = parseInt('{{$color2D[1]}}');
                                            plateColorB = parseInt('{{$color2D[2]}}');

                                            if(parseInt(plateColorCnt) == parseInt(1)){ r.style.setProperty('--plateColor1', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(2)){ r.style.setProperty('--plateColor2', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(3)){ r.style.setProperty('--plateColor3', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(4)){ r.style.setProperty('--plateColor4', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(5)){ r.style.setProperty('--plateColor5', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(6)){ r.style.setProperty('--plateColor6', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(7)){ r.style.setProperty('--plateColor7', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(8)){ r.style.setProperty('--plateColor8', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(9)){ r.style.setProperty('--plateColor9', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(10)){ r.style.setProperty('--plateColor10', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(11)){ r.style.setProperty('--plateColor11', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(12)){ r.style.setProperty('--plateColor12', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(13)){ r.style.setProperty('--plateColor13', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(14)){ r.style.setProperty('--plateColor14', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(15)){ r.style.setProperty('--plateColor15', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(16)){ r.style.setProperty('--plateColor16', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(17)){ r.style.setProperty('--plateColor17', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(18)){ r.style.setProperty('--plateColor18', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(19)){ r.style.setProperty('--plateColor19', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(20)){ r.style.setProperty('--plateColor20', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(21)){ r.style.setProperty('--plateColor21', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(22)){ r.style.setProperty('--plateColor22', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(23)){ r.style.setProperty('--plateColor23', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(24)){ r.style.setProperty('--plateColor24', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(25)){ r.style.setProperty('--plateColor25', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(26)){ r.style.setProperty('--plateColor26', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(27)){ r.style.setProperty('--plateColor27', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(28)){ r.style.setProperty('--plateColor28', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(29)){ r.style.setProperty('--plateColor29', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(30)){ r.style.setProperty('--plateColor30', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(31)){ r.style.setProperty('--plateColor31', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(32)){ r.style.setProperty('--plateColor32', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(33)){ r.style.setProperty('--plateColor33', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(34)){ r.style.setProperty('--plateColor34', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(35)){ r.style.setProperty('--plateColor35', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(36)){ r.style.setProperty('--plateColor36', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(37)){ r.style.setProperty('--plateColor37', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(38)){ r.style.setProperty('--plateColor38', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(39)){ r.style.setProperty('--plateColor39', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(40)){ r.style.setProperty('--plateColor40', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(41)){ r.style.setProperty('--plateColor41', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(42)){ r.style.setProperty('--plateColor42', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(43)){ r.style.setProperty('--plateColor43', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(44)){ r.style.setProperty('--plateColor44', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(45)){ r.style.setProperty('--plateColor45', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(46)){ r.style.setProperty('--plateColor46', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(47)){ r.style.setProperty('--plateColor47', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(48)){ r.style.setProperty('--plateColor48', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(49)){ r.style.setProperty('--plateColor49', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(50)){ r.style.setProperty('--plateColor50', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(51)){ r.style.setProperty('--plateColor51', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(52)){ r.style.setProperty('--plateColor52', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(53)){ r.style.setProperty('--plateColor53', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(54)){ r.style.setProperty('--plateColor54', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(55)){ r.style.setProperty('--plateColor55', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(56)){ r.style.setProperty('--plateColor56', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(57)){ r.style.setProperty('--plateColor57', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(58)){ r.style.setProperty('--plateColor58', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(59)){ r.style.setProperty('--plateColor59', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(60)){ r.style.setProperty('--plateColor60', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(61)){ r.style.setProperty('--plateColor61', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(62)){ r.style.setProperty('--plateColor62', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(63)){ r.style.setProperty('--plateColor63', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(64)){ r.style.setProperty('--plateColor64', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(65)){ r.style.setProperty('--plateColor65', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(66)){ r.style.setProperty('--plateColor66', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(67)){ r.style.setProperty('--plateColor67', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(68)){ r.style.setProperty('--plateColor68', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(69)){ r.style.setProperty('--plateColor69', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(70)){ r.style.setProperty('--plateColor70', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(71)){ r.style.setProperty('--plateColor71', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(72)){ r.style.setProperty('--plateColor72', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(73)){ r.style.setProperty('--plateColor73', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(74)){ r.style.setProperty('--plateColor74', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(75)){ r.style.setProperty('--plateColor75', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(76)){ r.style.setProperty('--plateColor76', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(77)){ r.style.setProperty('--plateColor77', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(78)){ r.style.setProperty('--plateColor78', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(79)){ r.style.setProperty('--plateColor79', 'rgba('+plateColorR+','+plateColorG+','+plateColorB+',255)');}
                                          
                                            plateColorCnt++;
                                        </script>
                                    @else
                                        <script>
                                            console.log(plateColorCnt);
                                            if(parseInt(plateColorCnt) == parseInt(1)){ r.style.setProperty('--plateColor1', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(2)){ r.style.setProperty('--plateColor2', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(3)){ r.style.setProperty('--plateColor3', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(4)){ r.style.setProperty('--plateColor4', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(5)){ r.style.setProperty('--plateColor5', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(6)){ r.style.setProperty('--plateColor6', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(7)){ r.style.setProperty('--plateColor7', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(8)){ r.style.setProperty('--plateColor8', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(9)){ r.style.setProperty('--plateColor9', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(10)){ r.style.setProperty('--plateColor10', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(11)){ r.style.setProperty('--plateColor11', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(12)){ r.style.setProperty('--plateColor12', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(13)){ r.style.setProperty('--plateColor13', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(14)){ r.style.setProperty('--plateColor14', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(15)){ r.style.setProperty('--plateColor15', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(16)){ r.style.setProperty('--plateColor16', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(17)){ r.style.setProperty('--plateColor17', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(18)){ r.style.setProperty('--plateColor18', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(19)){ r.style.setProperty('--plateColor19', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(20)){ r.style.setProperty('--plateColor20', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(21)){ r.style.setProperty('--plateColor21', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(22)){ r.style.setProperty('--plateColor22', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(23)){ r.style.setProperty('--plateColor23', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(24)){ r.style.setProperty('--plateColor24', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(25)){ r.style.setProperty('--plateColor25', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(26)){ r.style.setProperty('--plateColor26', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(27)){ r.style.setProperty('--plateColor27', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(28)){ r.style.setProperty('--plateColor28', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(29)){ r.style.setProperty('--plateColor29', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(30)){ r.style.setProperty('--plateColor30', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(31)){ r.style.setProperty('--plateColor31', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(32)){ r.style.setProperty('--plateColor32', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(33)){ r.style.setProperty('--plateColor33', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(34)){ r.style.setProperty('--plateColor34', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(35)){ r.style.setProperty('--plateColor35', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(36)){ r.style.setProperty('--plateColor36', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(37)){ r.style.setProperty('--plateColor37', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(38)){ r.style.setProperty('--plateColor38', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(39)){ r.style.setProperty('--plateColor39', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(40)){ r.style.setProperty('--plateColor40', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(41)){ r.style.setProperty('--plateColor41', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(42)){ r.style.setProperty('--plateColor42', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(43)){ r.style.setProperty('--plateColor43', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(44)){ r.style.setProperty('--plateColor44', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(45)){ r.style.setProperty('--plateColor45', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(46)){ r.style.setProperty('--plateColor46', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(47)){ r.style.setProperty('--plateColor47', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(48)){ r.style.setProperty('--plateColor48', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(49)){ r.style.setProperty('--plateColor49', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(50)){ r.style.setProperty('--plateColor50', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(51)){ r.style.setProperty('--plateColor51', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(52)){ r.style.setProperty('--plateColor52', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(53)){ r.style.setProperty('--plateColor53', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(54)){ r.style.setProperty('--plateColor54', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(55)){ r.style.setProperty('--plateColor55', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(56)){ r.style.setProperty('--plateColor56', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(57)){ r.style.setProperty('--plateColor57', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(58)){ r.style.setProperty('--plateColor58', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(59)){ r.style.setProperty('--plateColor59', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(60)){ r.style.setProperty('--plateColor60', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(61)){ r.style.setProperty('--plateColor61', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(62)){ r.style.setProperty('--plateColor62', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(63)){ r.style.setProperty('--plateColor63', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(64)){ r.style.setProperty('--plateColor64', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(65)){ r.style.setProperty('--plateColor65', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(66)){ r.style.setProperty('--plateColor66', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(67)){ r.style.setProperty('--plateColor67', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(68)){ r.style.setProperty('--plateColor68', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(69)){ r.style.setProperty('--plateColor69', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(70)){ r.style.setProperty('--plateColor70', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(71)){ r.style.setProperty('--plateColor71', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(72)){ r.style.setProperty('--plateColor72', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(73)){ r.style.setProperty('--plateColor73', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(74)){ r.style.setProperty('--plateColor74', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(75)){ r.style.setProperty('--plateColor75', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(76)){ r.style.setProperty('--plateColor76', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(77)){ r.style.setProperty('--plateColor77', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(78)){ r.style.setProperty('--plateColor78', 'rgba(191,191,191,255)');
                                            }else if(parseInt(plateColorCnt) == parseInt(79)){ r.style.setProperty('--plateColor79', 'rgba(191,191,191,255)');}
                                            plateColorCnt++;

                                            
                                        </script>

                                    @endif
                                    

                                    @if( $tabOrCalled > 0 )
                                        <div class="text-center pt-2 pb-2 mb-2 allPlate-1 plateColor{{++$plateColorCnt}} cookDivGlowCalled" style="width:100%; height:fit-content; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px; " 
                                        id="plate{{$plateOne}}OnT{{$rTable}}">
                                    @else
                                        <div class="text-center pt-2 pb-2 mb-2 allPlate{{$rTable}} plateColor{{++$plateColorCnt}}" style="width:100%; height:fit-content; border-radius:6px; margin-bottom:0px; " 
                                        id="plate{{$plateOne}}OnT{{$rTable}}">
                                    @endif
                                  
                                    
                                    
                                    
                                    
                                        @if ( $thePl != NULL)
                                            <h3 onclick="openThisPlate('{{$rTable}}','{{$plateOne}}')" class="mb-1 crsPointer"><strong>{{$thePl->nameTitle}}</strong></h3>
                                        @else
                                            <!-- <h3 onclick="openThisPlate('{{$rTable}}','{{$plateOne}}')" class="mb-1 crsPointer"><strong>-Kein Name-</strong></h3> -->
                                        @endif
                                            <!-- <h4 onclick="openThisPlate('{{$rTable}}','{{$plateOne}}')" class="mb-1 crsPointer"><strong><span id="nrDoneT{{$rTable}}P{{$plateOne}}">0</span> von <span id="nrAllT{{$rTable}}P{{$plateOne}}">0</span></strong></h4> -->

                                            <div style="width: 100%;" class="plateShowDiv" id="ordersListT{{$rTable}}P{{$plateOne}}">
                                                <?php
                                                    if ( $theRestorant->showAbgerufenFirst == 1){
                                                        $theTabOrders = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',(int)$rTable],['toPlate','!=','0'],['abrufenStat','!=','1']])->get();
                                                    }else{
                                                        $theTabOrders = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',(int)$rTable],['toPlate','!=','0']])->get();
                                                    }
                                                ?>
                                                @foreach($theTabOrders as $oTOrTP)
                                                    @if($oTOrTP->OrderSasia != $oTOrTP->OrderSasiaDone)
                                                        
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
                                                        
                                                                    @if($oTOrTP->abrufenStat == 1)
                                                                    <div ondblclick="changeStatOrCookYes('{{$rTable}}','{{$plateOne}}','{{$oTOrTP->id}}','{{$sas}}')"
                                                                    class="prodOneT{{$rTable}}P{{$plateOne}} proShowDiv  text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgb(208, 250, 207); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @else
                                                                    <div ondblclick="changeStatOrCookYes('{{$rTable}}','{{$plateOne}}','{{$oTOrTP->id}}','{{$sas}}')" 
                                                                    class="prodOneT{{$rTable}}P{{$plateOne}} proShowDiv plateColor{{$plateColorCnt}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @endif
                                                                        <?php
                                                                            if ($oTOrTP->abrufenStat == 1){
                                                                                $orTi = explode(':',explode(' ',$oTOrTP->updated_at)[1]);
                                                                            }else{
                                                                                $orTi = explode(':',explode(' ',$oTOrTP->created_at)[1]);
                                                                            }
                                                                        ?>
                                                                        @if ($oTOrTP->abrufenStat == 1)
                                                                            <p style="width:100%"><strong>Abgerufen: {{$orTi[0]}}:{{$orTi[1]}}</strong></p>
                                                                        @else
                                                                            <p style="width:100%"><strong>Bestellung: {{$orTi[0]}}:{{$orTi[1]}}</strong></p>
                                                                        @endif
                                                                        
                                                                        <h4 style="width:84%"><strong>{{$oTOrTP->OrderEmri}}</strong></h4>
                                                                        @if ($oTOrTP->OrderSasia >= 2)
                                                                            <h4 class="text-right" style="width:15%; font-size:1.4rem; color:red"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                        @else
                                                                            <h4 class="text-right" style="width:15%; font-size:1.4rem;"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                        @endif
                                                                        @if($oTOrTP->OrderType != 'empty')
                                                                        <h5 style="width:100%"><strong>Typ: <span style="color:red;">{{explode('||',$oTOrTP->OrderType)[0]}}</span></strong></h5>
                                                                        @endif
                                                                        @if($oTOrTP->OrderExtra != 'empty')
                                                                        <h5 style="width:100%"><strong>Extra:
                                                                            <span style="color:red;">
                                                                                @foreach (explode('--0--',$oTOrTP->OrderExtra) as $exO)
                                                                                    @if ($loop->first)
                                                                                        {{explode('||',$exO)[0]}}
                                                                                    @else
                                                                                        , {{explode('||',$exO)[0]}}
                                                                                    @endif
                                                                                @endforeach
                                                                            </span>
                                                                        </strong></h5>
                                                                        @endif
                                                                        @if($oTOrTP->OrderKomenti != NULL)
                                                                        <h5 style="width:100%"><strong>Kommentar: <span style="color:red;">{{$oTOrTP->OrderKomenti}}</span></strong></h5>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        
                                                    @endif
                                                @endforeach

                                                <!-- <script>
                                                    setPlatesDoneAndAll('{{$rTable}}','{{$plateOne}}','{{$nrAll}}','{{$nrDone}}');
                                                </script> -->
                                                @if ($nrAll == $nrDone)
                                                    <script>
                                                        $('#plate{{$plateOne}}OnT{{$rTable}}').attr('style','width:100%; border-radius:6px; background-color:rgba(4,178,89,255); margin-bottom:0px;');
                                                        $('#plate{{$plateOne}}OnT{{$rTable}}').remove();
                                                    </script>
                                                @endif
                                            </div>
                                    </div>
                                    
                                @endforeach

                                

                                <!-- show plate 0 orders -->
                                <?php
                                if ($theRestorant->showAbgerufenFirst == 1){
                                    $theTabOrdersCnt = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',(int)$rTable],['toPlate',0],['abrufenStat','!=','1']])->count();
                                }else{
                                    $theTabOrdersCnt = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',(int)$rTable],['toPlate',0]])->count();
                                }
                                ?>
                             
                                @if($theTabOrdersCnt > 0)
                                    <?php 
                                        $nrDone = (int)0;
                                        $nrAll = (int)0;

                                        $tabOrCalledPl0 = 0;
                                        foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['abrufenStat','1'],['toPlate','0']])->orderBy('updated_at')->get() as $toOneCnt){
                                            if($toOneCnt->OrderSasia != $toOneCnt->OrderSasiaDone){
                                                $tabOrCalledPl0++;
                                                break;
                                            }
                                        }
                                    ?>

                                      
                                        <div class="text-center pt-2 pb-2 mb-2 allPlate{{$rTable}}" style="width:100%; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px; " 
                                            id="plate0OnT{{$rTable}}">
                                       
                                            <h3 onclick="openThisPlate('{{$rTable}}','0')" class="mb-1 crsPointer"><strong>Kein Teller</strong></h3>
                                            <!-- <h4 onclick="openThisPlate('{{$rTable}}','0')" class="mb-1 crsPointer"><strong><span id="nrDoneT{{$rTable}}P0">0</span> von <span id="nrAllT{{$rTable}}P0">0</span></strong></h4> -->
                                            
                                            <div style="width: 100%;" class="plateShowDiv" id="ordersListT{{$rTable}}P0">
                                                <?php
                                                    if ( $theRestorant->showAbgerufenFirst == 1){
                                                        $theTabOrders = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',(int)$rTable],['toPlate','0'],['abrufenStat','!=','1']])->get();
                                                    }else{
                                                        $theTabOrders = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1'],['tableNr',(int)$rTable],['toPlate','0']])->get();
                                                    }
                                                ?>
                                                @foreach($theTabOrders as $oTOrTP)
                                                    @if($oTOrTP->OrderSasia != $oTOrTP->OrderSasiaDone)
                                                
                                                        <?php  $tp2 = Produktet::find($oTOrTP->prodId); ?>
                                                        @if($tp2 != NULL)
                                                            <?php   $tc2 = kategori::find($tp2->kategoria); 
                                                                    $has2nAccess = 0;    
                                                            ?>
                                                            @if($tc2 != NULL)
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
                                                                
                                                                    @if($oTOrTP->abrufenStat == 1)
                                                                    <div ondblclick="changeStatOrCookYes('{{$rTable}}','0','{{$oTOrTP->id}}','{{$sas}}')" 
                                                                    class="prodOneT{{$rTable}}P0 text-left proShowDiv justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @else
                                                                    <div ondblclick="changeStatOrCookYes('{{$rTable}}','0','{{$oTOrTP->id}}','{{$sas}}')" 
                                                                    class="prodOneT{{$rTable}}P0 text-left proShowDiv justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                                                                    style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                                                                    id="prodOneT{{$oTOrTP->id}}">
                                                                    @endif
                                                                
                                                                        <?php
                                                                            if ($oTOrTP->abrufenStat == 1){
                                                                                $orTi = explode(':',explode(' ',$oTOrTP->updated_at)[1]);
                                                                            }else{
                                                                                $orTi = explode(':',explode(' ',$oTOrTP->created_at)[1]);
                                                                            }
                                                                        ?>
                                                                        @if ($oTOrTP->abrufenStat == 1)
                                                                            <p style="width:100%"><strong>Abgerufen: {{$orTi[0]}}:{{$orTi[1]}}</strong></p>
                                                                        @else
                                                                            <p style="width:100%"><strong>Bestellung: {{$orTi[0]}}:{{$orTi[1]}}</strong></p>
                                                                        @endif

                                                                        <h4 style="width:84%"><strong>{{$oTOrTP->OrderEmri}}</strong></h4>
                                                                        @if ($oTOrTP->OrderSasia >= 2)
                                                                            <h4 class="text-right" style="width:15%; font-size:1.4rem; color:red"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                        @else
                                                                            <h4 class="text-right" style="width:15%; font-size:1.4rem;"><strong>{{$oTOrTP->OrderSasia}}X</strong></h4>
                                                                        @endif
                                                                        @if($oTOrTP->OrderType != 'empty')
                                                                        <h5 style="width:100%"><strong>Typ: <span style="color:red;">{{explode('||',$oTOrTP->OrderType)[0]}}</span></strong></h5>
                                                                        @endif
                                                                        @if($oTOrTP->OrderExtra != 'empty')
                                                                        <h5 style="width:100%"><strong>Extra:
                                                                            <span style="color:red;">
                                                                                @foreach (explode('--0--',$oTOrTP->OrderExtra) as $exO)
                                                                                    @if ($loop->first)
                                                                                        {{explode('||',$exO)[0]}}
                                                                                    @else
                                                                                        , {{explode('||',$exO)[0]}}
                                                                                    @endif
                                                                                @endforeach
                                                                            </span>
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

                                                <!-- <script> 
                                                    setPlatesDoneAndAll('{{$rTable}}','0','{{$nrAll}}','{{$nrDone}}');  
                                                </script> -->
                                                @if ($nrAll == $nrDone)
                                                    <script>
                                                        $('#plate0OnT{{$rTable}}').attr('style','width:100%; border-radius:6px; background-color:rgba(4,178,89,255); margin-bottom:0px;');
                                                        $('#plate0OnT{{$rTable}}').remove();
                                                    </script>
                                                @endif
                                            </div>
                                        </div>
                                @endif
                            </div>
                            @endif
                    
                    @endforeach
                </div>
            </div>
        

 

  




        


@else
    <div style="background-color: rgb(39,190,175); min-height: 50cm;" class="pr-2 pl-2 pt-1" id="cookPanelAll">
        <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Restaurant ( Offen )</strong></p>
        <div style="background-color: white; border-radius:5px;" class="p-2">
            <p style="font-size:1.8rem; color:rgb(39,190,175);" class="text-center"><strong>Es gibt noch keine ausstehenden Bestellungen für Sie!</strong></p>

        </div>
    </div>
@endif























<script>

                                                
    
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
            url: '{{ route("cookPnl.cookPanelOrderProdFinished") }}',
            method: 'post',
            data: {
                tabOrderId: tOrId,
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {
                respo = $.trim(respo);
                respo2D = respo.split('|||');
                $("#prodOneT"+tOrId).fadeOut(100, function(){ $(this).remove();});
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
                        $('#TableColumnCookTO'+tabNr).fadeOut(800, function(){ $(this).remove();});
                    }else{
                        $("#plate"+plNr+"OnT"+tabNr).fadeOut(800, function(){ $(this).remove();});
                    }
                }

             
                var countProdsOnPlate= $("#ordersListT"+tabNr+"P"+plNr+" .proShowDiv").length;
                console.log(countProdsOnPlate);
                if(countProdsOnPlate == 1){
                    $("#plate"+plNr+"OnT"+tabNr).fadeOut(100, function(){ $(this).remove();});
                }

                var countPlatesShown = $("#TableColumnCookTO"+tabNr+" .plateShowDiv").length;
                console.log(countPlatesShown);
                if(countPlatesShown == 1 && countProdsOnPlate == 1){
                    $('#TableColumnCookTO'+tabNr).fadeOut(100, function(){ $(this).remove();});
                }
                


                // $('#prodOneT'+tOrId).prop('disabled',false);
                if(parseInt(respo2D[1]) == 0){
                    $('#plate'+plNr+'OnT'+tabNr).removeClass('cookDivGlowCalled');
                }
            },
            error: (error) => { console.log(error); }
        });
    }


    function changeStatOrCookYesFoAllTable(tNr){
        $.ajax({
            url: '{{ route("cookPnl.cookPanelOrderProdFinishedAllTable") }}',
            method: 'post',
            data: {
                tableNr: tNr,
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $(".allPlate"+tNr).css('background-color','rgba(4,178,89,255)');
                $('#TableColumnCookTO'+tNr).fadeOut(300, function(){ $(this).remove();});
            },error: (error) => { console.log(error); }
        });
    }
</script>