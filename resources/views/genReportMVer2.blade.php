<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rechnung</title>
    
    <style>
        .invoice-box {
            max-width: 800px;
            margin: 0px;
            padding: 0.5cm;
            font-size: 14px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
        
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        
        .invoice-box table tr.top table td {
            padding-bottom: 5px;
        }
        
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        
        .invoice-box table tr.information table td {
            padding-bottom: 20px;
        }
        
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
        }
        .invoice-box table tr.heading th {
            background: #eee;
            border-bottom: 1px solid #ddd;
        }

        .invoice-box table tr.heading2 td {
            background: #eee;
            border-bottom: 1px solid #ddd;
        }
        .invoice-box table tr.heading2 th {
            background: #eee;
            border-bottom: 1px solid #ddd;
        }
        
        .invoice-box table tr.details td {
            padding-bottom: 0px;
        }
        
        .invoice-box table tr.items td{
            border-bottom: 1px solid #eee;
        }
        
        .invoice-box table tr.items.last td {
            border-bottom: none;
        }
        
        .total td{
            text-align: right;
        }
        
        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }
        
        .rtl table {
            text-align: right;
        }
        
        .rtl table tr td:nth-child(2) {
            text-align: left;
        }

        .footerP1{
            text-align: left;
            margin-bottom: 30px;
        }

        .pageNr:before {
            content: counter(page);
        }

        footer {
            position: fixed; 
            bottom: -60px; 
            left: 0px; 
            right: 0px;
            height: 50px; 
            font-size: 14px !important;
            justify-content:space-between;
        

            /** Extra personal styles **/
            line-height: 35px;
        }
     
        #pageCounter{
            counter-reset: currentPage;
        }
        #pageCounter .pageNumbers:before { 
            counter-increment: currentPage; 
            content: "Seite " counter(currentPage) " von "; 
        }

        .tdOnDays{
            text-align:center !important; 
            font-size:0.6rem !important; 
            margin:0px !important; 
            padding:0px !important;
            padding-top:-2px !important;
            margin-top:-1px !important;
            border-bottom:1px solid #A0A0A0;
        }

        .tdOnDays2{
            text-align:center !important; 
            font-size:0.6rem !important; 
            margin:-0.2px !important; 
            padding:-1px 0px 0.2px 0px !important;
            border-bottom:1px solid #A0A0A0;
        }
      
    </style>
</head>

<?php

use App\OrdersPassive;
    use App\billsExpensesRecordRes;
    use App\pdfReportIns;
    use Carbon\Carbon;
    use App\Orders;
    use App\DeliveryProd;
    use Illuminate\Support\Facades\Auth;
    use App\emailReceiptFromAdm;
use App\giftCard;
use App\ordersInNotCatInReport2;
    use App\Restorant;
    use App\Produktet;
    use App\Takeaway;
    use App\User;
    use App\pdfResProdCats;
    use App\waiterDaySales;

    $teDh2D = explode('-a-s-',$teDh);

    $nextPage = 1;

    $allRes = explode('-m-m-m-',$teDh2D[5]);

    $dt2d = explode('-',$teDh2D[0]);
    $dateOfCr = date('d').'.'.date('m').'.'.date('Y').' / '.date('H').':'.date('i');

    // time calculate
    $dateBase = Carbon::create($dt2d[1], $dt2d[0], 01, 01, 00, 00);
    $dateMonthEnd = $dateBase->endOfMonth();
    $dateMonthEnd = explode(' ',$dateMonthEnd)[0];
    $dateMonthEnd2D = explode('-',$dateMonthEnd);
    // ---------------------------------------

    $month = new Carbon($dt2d[1].'-'.$dt2d[0].'-01');
    $salesPerProGr = array();

    $allRestorants = '';
    foreach(explode('-m-m-m-',$teDh2D[5]) as $reOn){
        if($allRestorants == ''){ $allRestorants = $reOn; }
        else { $allRestorants .= '_'.$reOn; }
    }
    $pdfname = 'storage/pdfMonthReportSelective/MonatlicherDetaillierterBericht_'.$allRestorants.'_'.$teDh2D[0].'.pdf';
    $pdftext = file_get_contents($pdfname);
    $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);

?>

<body id="pageCounter">
  
    <footer id="footerPC">
        <p style="width: 20%; display: inline; "> <span  class="pageNumbers"></span>{{$pages}}</p>
        <p style="width: 60%; display: inline; margin-right:2.5cm; margin-left:2.75cm;">Kontaktlos bestellen & bezahlen mit QRorpa Systeme</p>
        <img width="110px" style="display: inline;" src="storage/images/logo_QRorpa.png" class="logo" alt="logo">
    </footer>

    <main>
        @foreach ($allRes as $oneRes)
            <?php
                $theRes = Restorant::findOrFail($oneRes);
                $adr2D =  explode(',',$theRes->adresa);
                $adr1 =  $adr2D[0];
                $adr2 = '---' ;
                if(isset($adr2D[1])){ $adr2 = $adr2D[1] ; }
                if(isset($adr2D[2])){ $adr2 = $adr2D[1].','.$adr2D[2]; }
                
                if($theRes->resTvsh == 0){
                    $hiTvsh = number_format( 0 , 9, '.', '');
                    $loTvsh = number_format( 0 , 9, '.', '');
                }else{
                    if($dt2d[1] <= 2023){
                        $hiTvsh = number_format( 0.071494893 , 9, '.', '');
                        $loTvsh = number_format( 0.024390243 , 9, '.', '');
                    }else{
                        $hiTvsh = number_format( 0.074930619 , 9, '.', '');
                        $loTvsh = number_format( 0.025341130 , 9, '.', '');
                    }
                }

                // $proGrs = pdfResProdCats::where('toRes',$oneRes)->get();
                $proGrsIDS = array();
                $proGrs = array();

                $resClock = explode('->',$theRes->reportTimeArc);
                $resClock1_2D = explode(':',$resClock[0]);
                $resClock2_2D = explode(':',$resClock[1]);
                $dateStart = Carbon::create($dt2d[1], $dt2d[0], 01, $resClock1_2D[0], $resClock1_2D[1], 00);
                $dateEnd = Carbon::create($dateMonthEnd2D[0], $dateMonthEnd2D[1], $dateMonthEnd2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
                if($theRes->reportTimeOtherDay == 1){
                    // diff day
                    $dateEnd->addDays(1); 
                }

                foreach(OrdersPassive::where('Restaurant',$oneRes)->whereBetween('created_at', [$dateStart, $dateEnd])->get() as $orderOnePG){
                    foreach(explode('---8---',$orderOnePG->porosia) as $prodOnePG){
                        $prodOnePG2D = explode('-8-',$prodOnePG);
                        if(isset($prodOnePG2D[8]) && $prodOnePG2D[8] != 0){
                            
                                if(!in_array($prodOnePG2D[8],$proGrsIDS)){
                                    array_push($proGrsIDS,$prodOnePG2D[8]);
                                    $theProGr = pdfResProdCats::find($prodOnePG2D[8]);
                                    array_push($proGrs,$theProGr);
                                }
                            
                        }else{
                            if($orderOnePG->nrTable == 500){
                                if($orderOnePG->userPhoneNr != '0770000000'){
                                    if($orderOnePG->userPhoneNr == '0000000000'){
                                        $theP = Takeaway::where('prod_id',$prodOnePG2D[7])->first();
                                    }else{
                                        $theP = Takeaway::find($prodOnePG2D[7]);  
                                    }                             
                                }else{
                                    $theP = Takeaway::where('prod_id',$prodOnePG2D[7])->first();
                                }
                                if($theP != NULL && $theP->toReportCat != 0){
                                    $repGr = $theP->toReportCat;
                                    if(!in_array($repGr,$proGrsIDS)){
                                        array_push($proGrsIDS,$repGr);
                                        $theProGr = pdfResProdCats::findOrFail($repGr);
                                        array_push($proGrs,$theProGr);
                                    }
                                }
                            }else{
                                $theP = Produktet::find($prodOnePG2D[7]);
                                if($theP != NULL && $theP->toReportCat != 0){
                                    $repGr = $theP->toReportCat;
                                    if(!in_array($repGr,$proGrsIDS)){
                                        array_push($proGrsIDS,$repGr);
                                        $theProGr = pdfResProdCats::findOrFail($repGr);
                                        array_push($proGrs,$theProGr);
                                    }
                                }
                            }
                        }
                    }
                }

    

                $pdfRepot = pdfReportIns::where([['forRes',$oneRes],['reportType','3'],['forDate1',$teDh2D[0]]])->first();
                if($pdfRepot != NULL){
                    $repoNr = $pdfRepot->billNumber; 
                    $repoID = $pdfRepot->billId; 
                }else{
                    $repoNr = strval(hexdec(uniqid()));
                    if(pdfReportIns::where('forRes',$oneRes)->count() > 0){ $repoID = pdfReportIns::where('forRes',$oneRes)->max('billId') + 1; }
                    else{ $repoID = 1; }
                    $newRep = new pdfReportIns();
                    $newRep->forRes = $oneRes;
                    $newRep->forDate1 = $teDh2D[0];
                    $newRep->reportType = 3;
                    $newRep->billId = $repoID;
                    $newRep->billNumber = $repoNr;
                    $newRep->save();
                }

                $totBruto = array();
                $totmwst77p = array();
                $totmwst77pSales = array();
                $totmwst25p = array();
                $totmwst25pSales = array();
            ?>

            <div class="invoice-box">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="6"> 
                            <table style="margin-top:-1cm; margin-bottom:0.1cm;">
                                <tr>
                                    <td>
                                        <p style="margin: 0px; padding:0px; margin-top:-10px;"><strong>{{$theRes->emri}}</strong></p>
                                        <p style="margin: 0px; padding:0px; margin-top:-10px;">{{$adr1}}</p>
                                        <p style="margin: 0px; padding:0px; margin-top:-10px;">{{$adr2}}</p>
                                        <p style="margin:0px; padding:0px; margin-top:-10px;">Tel. {{ $theRes->resPhoneNr }}</p>
                                        @if (str_contains($theRes->chemwstForRes, 'CHE'))
                                            <p style="margin:0px; padding:0px; margin-top:-8px;">MwST-Nr. {{ $theRes->chemwstForRes }}</p>
                                        @else
                                            <p style="margin:0px; padding:0px; margin-top:-8px;">{{ $theRes->chemwstForRes }}</p>
                                        @endif
                                    </td>
                                    <td >
                                        <p style="margin:0px; padding:0px; margin-top:-10px;"><strong>Bericht ID#: {{str_pad($repoID, 8, '0', STR_PAD_LEFT)}}</strong></p>
                                        <p style="margin:0px; padding:0px; margin-top:-8px;"><strong>Bericht#: {{$repoNr}}</strong></p>
                                        <p style="margin:0px; padding:0px; margin-top:-8px;">Datum/Zeit: {{$dateOfCr}}</p>

                                        <p style="margin:0px; padding:0px; margin-top:-8px; color:red;">{{$dateStart}}</p>
                                        <p style="margin:0px; padding:0px; margin-top:-8px; color:red;">{{$dateEnd}}</p>
                                    </td> 
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <strong>Monatlicher Bericht: 
                            @switch($dt2d[0])
                                @case(1) ( Januar ) @break
                                @case(2) ( Februar ) @break
                                @case(3) ( März ) @break
                                @case(4) ( April ) @break
                                @case(5) ( Mai ) @break
                                @case(6) ( Juni ) @break
                                @case(7) ( Juli ) @break
                                @case(8) ( August ) @break
                                @case(9) ( September ) @break
                                @case(10) ( Oktober ) @break
                                @case(11) ( November ) @break
                                @case(12) ( Dezember ) @break
                            @endswitch 
                            {{str_pad($dt2d[0], 2, '0', STR_PAD_LEFT)}}.{{$dt2d[1]}}</strong>
                        </td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0">
                
                    <tr class="heading">
                            <th style="font-size: 0.5rem; margin:0px !important; padding:0px !important; line-height: 1;">Datum</th>
                            <th style="font-size: 0.5rem; margin:0px !important; padding:0px !important; line-height: 1;">Tag</th>
                        @foreach ($proGrs as $onePrGr)
                            <th style="font-size: 0.5rem; margin:0px !important; padding:0px !important; line-height: 1;">{{ $onePrGr->catTitle }}</th>
                            <?php
                                $salesPerProGr[$onePrGr->id] = number_format(0,9,'.','');

                                $totBruto[$onePrGr->id] = number_format(0,9,'.','');
                                $totmwst77p[$onePrGr->id] = number_format(0,9,'.','');
                                $totmwst77pSales[$onePrGr->id] = number_format(0,9,'.','');
                                $totmwst25p[$onePrGr->id] = number_format(0,9,'.','');
                                $totmwst25pSales[$onePrGr->id] = number_format(0,9,'.','');
                            ?>

                        @endforeach
                        
                        <th style="font-size: 0.5rem; margin:0px !important; padding:0px !important; line-height: 1;">Nicht<br>kategorisiert</th>
                        <!-- <th style="font-size: 0.5rem; margin:0px !important; padding:0px !important; line-height: 1;">Rabatt</th> -->
                        <th style="font-size: 0.5rem; margin:0px !important; padding:0px !important; line-height: 1;">Verkaufte<br>Guscheine</th>
                        <?php
                
                            $salesPerProGr[0] = number_format(0,9,'.','');
                            $totBruto[0] = number_format(0,9,'.','');
                            $totmwst77p[0] = number_format(0,9,'.','');
                            $totmwst77pSales[0] = number_format(0,9,'.','');
                            $totmwst25p[0] = number_format(0,9,'.','');
                            $totmwst25pSales[0] = number_format(0,9,'.','');
                            $totalRebat = number_format(0,9,'.','');
                            $gcSalesAll = number_format(0,9,'.','');
                            $daysBrutoTot = array();
                            
                        ?>
                        <th style="font-size: 0.5rem; margin:0px !important; padding:0px !important; padding-top:-7px !important;">Gesamt</th>
                    </tr>

                    @for($i=1;$i<=$month->daysInMonth;$i++)
                    <tr style="border-bottom:1px solid #333;">
                        <td class="tdOnDays"><strong>{{$i}}</strong></td>
                        <?php
                            $dateCheckCreate = new Carbon($dt2d[1].'-'.sprintf('%02d', $dt2d[0]).'-'.sprintf('%02d', $i));
                            $dayOfWeekNr = date('w', strtotime($dateCheckCreate));

                            $resClock = explode('->',$theRes->reportTimeArc);
                            $resClock1_2D = explode(':',$resClock[0]);
                            $resClock2_2D = explode(':',$resClock[1]);
                            $dateStart = Carbon::create($dt2d[1], $dt2d[0], $i, $resClock1_2D[0], $resClock1_2D[1], 00);
                            $dateEnd = Carbon::create($dt2d[1], $dt2d[0], $i, $resClock2_2D[0], $resClock2_2D[1], 59);
                            if($theRes->reportTimeOtherDay == 1){
                                // diff day
                                $dateEnd->addDays(1); 
                            }

                            $daysTotal = number_format(0,9,'.','');
                            $daysRabat = number_format(0,9,'.','');
                            $thisDaysOrds = OrdersPassive::where([['Restaurant',$oneRes],['statusi','!=','2']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

                            $gcSalesThisDay = giftCard::where([['toRes',$oneRes],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->sum('gcSumInChf');
                            $gcSalesAll += number_format($gcSalesThisDay ,9,'.','');

                            // iterim neper qdo porosi per kete date
                            foreach($thisDaysOrds as $thOrOne){

                                $totFromProductePrice = number_format(0, 2, '.', '');
                                foreach(explode('---8---',$thOrOne) as $produkti){
                                    $prod = explode('-8-', $produkti);
                                    $totFromProductePrice += number_format($prod[4], 2, '.', '');
                                }

                                // porosit RESTAURANT
                                if($thOrOne->nrTable != 500 && $thOrOne->nrTable != 9000){
                                    $poroAll = explode('---8---',$thOrOne->porosia);
                                    foreach( $poroAll as $poroOne ){
                                        $poroOne2D = explode('-8-',$poroOne);
                                        
                                        $theP = Produktet::find($poroOne2D[7]);
                                        if(isset($poroOne2D[8]) && $poroOne2D[8] != 0){
                                            $repGr = (int)$poroOne2D[8];
                                        }else{
                                            if($theP == NULL){
                                                $repGr = (int)0;

                                                $notCatLog = new ordersInNotCatInReport2();
                                                $notCatLog->prodId = 0;
                                                $notCatLog->prodIdTA = 0;
                                                $notCatLog->orderId = $thOrOne->id;
                                                $notCatLog->dayNr = $i;
                                                $notCatLog->save();
                                            }else{ $repGr = (int)$theP->toReportCat; }
                                        }

                                        if($thOrOne->inCashDiscount > 0){
                                            $totZbritja = number_format($thOrOne->inCashDiscount, 2, '.', '');
                                            $cal1 = number_format(($poroOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                            $cal2 = number_format($poroOne2D[4] - $cal1, 9, '.', '');
                                            $totmwst77p[$repGr] += number_format((float)$cal2 * $hiTvsh,9,'.','');

                                            $totBruto[$repGr] += number_format((float)$cal2,9,'.','');
                                            $totmwst77pSales[$repGr] += number_format((float)$cal2,9,'.','');
                                            $salesPerProGr[$repGr] += number_format((float)$cal2,9,'.','');
                                        }else if($thOrOne->inPercentageDiscount > 0){
                                            $totZbritjaPrc = number_format($thOrOne->inPercentageDiscount / 100, 2, '.', '');
                                            $cal1 = number_format($poroOne2D[4] * $totZbritjaPrc, 9, '.', '');
                                            $cal2 = number_format($poroOne2D[4] - $cal1, 9, '.', '');
                                            $totmwst77p[$repGr] += number_format((float)$cal2 * $hiTvsh,9,'.','');

                                            $totBruto[$repGr] += number_format((float)$cal2,9,'.','');
                                            $totmwst77pSales[$repGr] += number_format((float)$cal2,9,'.','');
                                            $salesPerProGr[$repGr] += number_format((float)$cal2,9,'.','');
                                        }else{
                                            $totmwst77p[$repGr] += number_format((float)$poroOne2D[4] * $hiTvsh,9,'.','');

                                            $totBruto[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                                            $totmwst77pSales[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                                            $salesPerProGr[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                                        }
                                        
                                    }

                                // porosit TAKEAWAY 
                                }else if($thOrOne->nrTable == 500){
                                    $poroAll = explode('---8---',$thOrOne->porosia);
                                    foreach( $poroAll as $poroOne ){
                                        $poroOne2D = explode('-8-',$poroOne);

                                        if($thOrOne->userPhoneNr != '0770000000'){ 
                                            if($thOrOne->userPhoneNr == '0000000000'){ 
                                                $theP = Takeaway::where('prod_id',$poroOne2D[7])->first();
                                            }else{
                                                $theP = Takeaway::find($poroOne2D[7]); 
                                            }
                                        }else{ 
                                            $theP = Takeaway::find($poroOne2D[7]);
                                            if($theP == NULL){ 
                                                $theP = Takeaway::where('prod_id',$poroOne2D[7])->first(); 
                                            }else{
                                                if($theP->emri != $poroOne2D[0] || $theP->qmimi != $poroOne2D[4]){
                                                    $theP = Takeaway::where('prod_id',$poroOne2D[7])->first(); 
                                                }
                                            }
                                        }

                                        if(isset($poroOne2D[8]) && $poroOne2D[8] != 0){
                                            $repGr = (int)$poroOne2D[8];
                                        }else{
                                            if($theP == NULL){
                                                $repGr = (int)0;
                                                $notCatLog = new ordersInNotCatInReport2();
                                                $notCatLog->prodId = 0;
                                                $notCatLog->prodIdTA = 500;
                                                $notCatLog->orderId = $thOrOne->id;
                                                $notCatLog->dayNr = $i;
                                                $notCatLog->save();                                        
                                            }else{ $repGr = (int)$theP->toReportCat; }
                                        }
                                    
                                        

                                        if($thOrOne->inCashDiscount > 0){
                                            $totZbritja = number_format($thOrOne->inCashDiscount, 2, '.', '');
                                            $cal1 = number_format(($poroOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                            $cal2 = number_format($poroOne2D[4] - $cal1, 9, '.', '');
                                            if($theP == NULL){ 
                                                $totmwst25p[$repGr] += number_format((float)$cal2 * $loTvsh,9,'.','');
                                                $totmwst25pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                                            }else if($theP->mwstForPro == 7.70 || $theP->mwstForPro == 8.10){
                                                $totmwst77p[$repGr] += number_format((float)$cal2 * $hiTvsh,9,'.','');
                                                $totmwst77pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                                            }else if($theP->mwstForPro == 2.50 || $theP->mwstForPro == 2.60){
                                                $totmwst25p[$repGr] += number_format((float)$cal2 * $loTvsh,9,'.','');
                                                $totmwst25pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                                            }
                                            $totBruto[$repGr] += number_format((float)$cal2,9,'.','');
                                            $salesPerProGr[$repGr] += number_format((float)$cal2,9,'.','');
                                        }else if($thOrOne->inPercentageDiscount > 0){
                                            $totZbritjaPrc = number_format($thOrOne->inPercentageDiscount / 100, 2, '.', '');
                                            $cal1 = number_format($poroOne2D[4] * $totZbritjaPrc, 9, '.', '');
                                            $cal2 = number_format($poroOne2D[4] - $cal1, 9, '.', '');
                                            if($theP == NULL){ 
                                                $totmwst25p[$repGr] += number_format((float)$cal2 * $loTvsh,9,'.','');
                                                $totmwst25pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                                            }else if($theP->mwstForPro == 7.70 || $theP->mwstForPro == 8.10){
                                                $totmwst77p[$repGr] += number_format((float)$cal2 * $hiTvsh,9,'.','');
                                                $totmwst77pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                                            }else if($theP->mwstForPro == 2.50 || $theP->mwstForPro == 2.60){
                                                $totmwst25p[$repGr] += number_format((float)$cal2 * $loTvsh,9,'.','');
                                                $totmwst25pSales[$repGr] += number_format((float)$cal2 ,9,'.','');
                                            }
                                            $totBruto[$repGr] += number_format((float)$cal2,9,'.','');
                                            $salesPerProGr[$repGr] += number_format((float)$cal2,9,'.','');
                                        }else{
                                            if($theP == NULL){ 
                                                $totmwst25p[$repGr] += number_format((float)$poroOne2D[4] * $loTvsh,9,'.','');
                                                $totmwst25pSales[$repGr] += number_format((float)$poroOne2D[4] ,9,'.','');
                                            }else if($theP->mwstForPro == 7.70 || $theP->mwstForPro == 8.10){
                                                $totmwst77p[$repGr] += number_format((float)$poroOne2D[4] * $hiTvsh,9,'.','');
                                                $totmwst77pSales[$repGr] += number_format((float)$poroOne2D[4] ,9,'.','');
                                            }else if($theP->mwstForPro == 2.50 || $theP->mwstForPro == 2.60){
                                                $totmwst25p[$repGr] += number_format((float)$poroOne2D[4] * $loTvsh,9,'.','');
                                                $totmwst25pSales[$repGr] += number_format((float)$poroOne2D[4] ,9,'.','');
                                            }
                                            $totBruto[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                                            $salesPerProGr[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                                        }
                                    }

                                // porosit DELIVERY
                                }else if($thOrOne->nrTable == 9000){
                                    $poroAll = explode('---8---',$thOrOne->porosia);
                                    foreach( $poroAll as $poroOne ){
                                        $poroOne2D = explode('-8-',$poroOne);
                                        $theP = Produktet::find($poroOne2D[7]);

                                        if($theP == NULL){
                                            $repGr = (int)0;
                                            $notCatLog = new ordersInNotCatInReport2();
                                            $notCatLog->prodId = 0;
                                            $notCatLog->prodIdTA = 9000;
                                            $notCatLog->orderId = $thOrOne->id;
                                            $notCatLog->dayNr = $i;
                                            $notCatLog->save(); 
                                        }else{ $repGr = (int)$theP->toReportCat; }

                                        $salesPerProGr[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                                        $totBruto[$repGr] += number_format((float)$poroOne2D[4],9,'.','');

                                        if($theP == Null || $theP->mwstForPro == 7.70 || $theP->mwstForPro == 8.10){
                                            $totmwst77p[$repGr] += number_format((float)$poroOne2D[4] * $hiTvsh,9,'.','');
                                            $totmwst77pSales[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                                        }else if($theP->mwstForPro == 2.50 || $theP->mwstForPro == 2.60){
                                            $totmwst25p[$repGr] += number_format((float)$poroOne2D[4] * $loTvsh,9,'.','');
                                            $totmwst25pSales[$repGr] += number_format((float)$poroOne2D[4],9,'.','');
                                        }
                                    }
                                }

                                if((float)$thOrOne->inCashDiscount > 0){
                                    $daysRabat += number_format((float)$thOrOne->inCashDiscount,9,'.','');
                                }else if((float)$thOrOne->inPercentageDiscount > 0){
                                    $rebatVal = number_format((float)$thOrOne->inPercentageDiscount / 100 ,9,'.','');
                                    $daysRabat += number_format((float)$rebatVal * ((float)$thOrOne->shuma - (float)$thOrOne->tipPer),9,'.','');
                                }
                                // $daysRabat += number_format((float)$thOrOne->dicsountGcAmnt,9,'.','');
                            }
                        ?>

                        <td class="tdOnDays"><strong>
                            @switch($dayOfWeekNr)
                                @case(1) Mo. @break
                                @case(2) Di. @break
                                @case(3) Mi. @break
                                @case(4) Do. @break
                                @case(5) Fr. @break
                                @case(6) Sa. @break
                                @case(0) So. @break
                            @endswitch    
                        </strong></td>
                        @foreach ($proGrs as $onePrGr)
                        <td class="tdOnDays">{{number_format($salesPerProGr[$onePrGr->id],2,'.','')}}</td>
                        <?php $daysTotal += number_format($salesPerProGr[$onePrGr->id],9,'.','');
                                $salesPerProGr[$onePrGr->id] = number_format(0,9,'.',''); ?>
                        @endforeach
                        <?php $daysTotal += number_format($salesPerProGr[0],9,'.',''); ?>
                        <td class="tdOnDays">{{number_format($salesPerProGr[0],2,'.','')}}</td>
                        <!-- down - $daysRabat -->
                        <!-- <td class="tdOnDays">{{number_format($daysRabat ,2,'.','')}}</td> -->

                        <td class="tdOnDays">{{number_format($gcSalesThisDay ,2,'.','')}}</td>

                        <td class="tdOnDays">{{number_format($daysTotal + $gcSalesThisDay,2,'.','')}}</td>
                        <?php
                            $totalRebat += number_format($daysRabat,9,'.','');
                            $salesPerProGr[0] = number_format(0,9,'.',''); 
                            $daysBrutoTot[$i] = number_format($daysTotal,9,'.','');
                            $daysTotal = number_format(0,9,'.','');
                            $daysRabat = number_format(0,9,'.',''); ?>
                    </tr>
                    @endfor
                    <?php
                        $totBrutoAll = number_format(0,9,'.','');
                        $totmwst77All = number_format(0,9,'.','');
                        $totmwst77AllSales = number_format(0,9,'.','');
                        $totmwst25All = number_format(0,9,'.','');
                        $totmwst25AllSales = number_format(0,9,'.','');
                    ?>

                    <tr class="heading2">
                        <th colspan="2" style="font-size: 0.6rem;">Total Brutto</th>
                        @foreach ($proGrs as $onePrGr)
                        <th style="font-size: 0.6rem;">{{ number_format($totBruto[$onePrGr->id],2,'.','') }}</th>
                        <?php $totBrutoAll += number_format($totBruto[$onePrGr->id],2,'.',''); ?>
                        @endforeach
                        <th style="font-size: 0.6rem;">{{ number_format($totBruto[0],2,'.','') }}</th>
                        <?php $totBrutoAll += number_format($totBruto[0],2,'.',''); ?>

                        <!-- down - $totalRebat -->
                        <!-- <th style="font-size: 0.6rem;">{{ number_format($totalRebat,2,'.','') }}</th> -->
                        <th style="font-size: 0.6rem;">{{ number_format($gcSalesAll,2,'.','') }}</th>
                        <th style="font-size: 0.6rem;">{{ number_format($totBrutoAll + $gcSalesAll,2,'.','') }}</th>
                    </tr>

                    <tr class="heading2">
                        @if($theRes->resTvsh == 0)
                            <th colspan="2" style="font-size: 0.6rem;">MwSt. 0.00%</th>
                        @else
                            @if($dt2d[1] <= 2023)
                            <th colspan="2" style="font-size: 0.6rem;">MwSt. 7.70%</th>
                            @else
                            <th colspan="2" style="font-size: 0.6rem;">MwSt. 8.10%</th>
                            @endif
                        @endif
                        @foreach ($proGrs as $onePrGr)
                        <th style="font-size: 0.6rem; line-height:1;">{{ number_format($totmwst77pSales[$onePrGr->id],2,'.','') }}<br>{{ number_format($totmwst77p[$onePrGr->id],2,'.','') }}</th>
                        <?php 
                            $totmwst77All += number_format($totmwst77p[$onePrGr->id],2,'.',''); 
                            $totmwst77AllSales += number_format($totmwst77pSales[$onePrGr->id],2,'.',''); 
                        ?>
                        @endforeach
                        <th style="font-size: 0.6rem; line-height:1;">{{ number_format($totmwst77pSales[0],2,'.','') }}<br>{{ number_format($totmwst77p[0],2,'.','') }}</th>
                        <?php 
                            $totmwst77All += number_format($totmwst77p[0],2,'.',''); 
                            $totmwst77AllSales += number_format($totmwst77pSales[0],2,'.',''); 
                        ?>
                        <!-- <th style="font-size: 0.6rem;">--</th> -->
                        <th style="font-size: 0.6rem;">{{ number_format(0,2,'.','') }}</th>
                        <th style="font-size: 0.6rem; line-height:1;">{{ number_format($totmwst77AllSales,2,'.','') }}<br>{{ number_format($totmwst77All,2,'.','') }}</th>
                    </tr>

                    <tr class="heading2">
                        @if($theRes->resTvsh == 0)
                            <th colspan="2" style="font-size: 0.6rem;">MwSt. 0.00%</th>
                        @else
                            @if($dt2d[1] <= 2023)
                            <th colspan="2" style="font-size: 0.6rem;">MwSt. 2.50%</th>
                            @else
                            <th colspan="2" style="font-size: 0.6rem;">MwSt. 2.60%</th>
                            @endif
                        @endif
                        
                        @foreach ($proGrs as $onePrGr)
                        <th style="font-size: 0.6rem; line-height:1;">{{ number_format($totmwst25pSales[$onePrGr->id],2,'.','') }}<br>{{ number_format($totmwst25p[$onePrGr->id],2,'.','') }}</th>
                        <?php 
                            $totmwst25All += number_format($totmwst25p[$onePrGr->id],2,'.',''); 
                            $totmwst25AllSales += number_format($totmwst25pSales[$onePrGr->id],2,'.',''); 
                        ?>
                        @endforeach
                        <th style="font-size: 0.6rem; line-height:1;">{{ number_format($totmwst25pSales[0],2,'.','') }}<br>{{ number_format($totmwst25p[0],2,'.','') }}</th>
                        <?php 
                            $totmwst25All += number_format($totmwst25p[0],2,'.',''); 
                            $totmwst25AllSales += number_format($totmwst25pSales[0],2,'.',''); 
                        ?>
                        <!-- <th style="font-size: 0.6rem;">--</th> -->
                        <th style="font-size: 0.6rem;">{{ number_format(0,2,'.','') }}</th>
                        <th style="font-size: 0.6rem; line-height:1;">{{ number_format($totmwst25AllSales,2,'.','') }}<br>{{ number_format($totmwst25All,2,'.','') }}</th>
                    </tr>

                    <tr class="heading2">
                        <th colspan="2" style="font-size: 0.6rem;">Total MwSt.</th>
                        @foreach ($proGrs as $onePrGr)
                        <th style="font-size: 0.6rem;">{{ number_format($totmwst77p[$onePrGr->id] + $totmwst25p[$onePrGr->id],2,'.','') }}</th>
                        @endforeach
                        <th style="font-size: 0.6rem;">{{ number_format($totmwst77p[0] + $totmwst25p[0],2,'.','') }}</th>
                        <!-- <th style="font-size: 0.6rem;">--</th> -->
                        <th style="font-size: 0.6rem;">{{ number_format(0,2,'.','') }}</th>
                        <th style="font-size: 0.6rem;">{{ number_format($totmwst77All + $totmwst25All,2,'.','') }}</th>
                    </tr>

                    <tr class="heading2">
                        <th colspan="2" style="font-size: 0.6rem;">Total Netto</th>
                        @foreach ($proGrs as $onePrGr)
                        <th style="font-size: 0.6rem;">{{ number_format($totBruto[$onePrGr->id] - $totmwst77p[$onePrGr->id] - $totmwst25p[$onePrGr->id],2,'.','') }}</th>
                        @endforeach
                        <th style="font-size: 0.6rem;">{{ number_format($totBruto[0] - $totmwst77p[0] - $totmwst25p[0],2,'.','') }}</th>
                        <!-- <th style="font-size: 0.6rem;">{{ number_format($totalRebat,2,'.','') }}</th> -->
                        <th style="font-size: 0.6rem;">{{ number_format($gcSalesAll,2,'.','') }}</th>
                        <th style="font-size: 0.6rem;">{{ number_format($totBrutoAll - $totmwst77All - $totmwst25All + $gcSalesAll,2,'.','') }}</th>
                    </tr>
                </table>
























                        
                <table style="padding-top: 1cm; page-break-before: always;" cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="6"> 
                            <table style="margin-top:-1cm; margin-bottom:0.1cm;">
                                <tr>
                                    <td>
                                        <p style="margin: 0px; padding:0px; margin-top:-10px;"><strong>{{$theRes->emri}}</strong></p>
                                        <p style="margin: 0px; padding:0px; margin-top:-10px;">{{$adr1}}</p>
                                        <p style="margin: 0px; padding:0px; margin-top:-10px;">{{$adr2}}</p>
                                        <p style="margin:0px; padding:0px; margin-top:-10px;">Tel. {{ $theRes->resPhoneNr }}</p>
                                        @if (str_contains($theRes->chemwstForRes, 'CHE'))
                                            <p style="margin:0px; padding:0px; margin-top:-8px;">MwST-Nr. {{ $theRes->chemwstForRes }}</p>
                                        @else
                                            <p style="margin:0px; padding:0px; margin-top:-8px;">{{ $theRes->chemwstForRes }}</p>
                                        @endif
                                    </td>
                                    <td >
                                        <p style="margin:0px; padding:0px; margin-top:-10px;"><strong>Bericht ID#: {{str_pad($repoID, 8, '0', STR_PAD_LEFT)}}</strong></p>
                                        <p style="margin:0px; padding:0px; margin-top:-8px;"><strong>Bericht#: {{$repoNr}}</strong></p>
                                        <p style="margin:0px; padding:0px; margin-top:-8px;">Datum/Zeit: {{$dateOfCr}}</p>
                                    </td> 
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <strong>Monatlicher Bericht: 
                            @switch($dt2d[0])
                                @case(1) ( Januar ) @break
                                @case(2) ( Februar ) @break
                                @case(3) ( März ) @break
                                @case(4) ( April ) @break
                                @case(5) ( Mai ) @break
                                @case(6) ( Juni ) @break
                                @case(7) ( Juli ) @break
                                @case(8) ( August ) @break
                                @case(9) ( September ) @break
                                @case(10) ( Oktober ) @break
                                @case(11) ( November ) @break
                                @case(12) ( Dezember ) @break
                            @endswitch 
                            {{str_pad($dt2d[0], 2, '0', STR_PAD_LEFT)}}.{{$dt2d[1]}}</strong>
                        </td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0">
                
                    <tr class="heading">
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;"></th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Brutto Umsatz</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Bargeld</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Kreditkarte</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Online</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Rechnung</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Geschenkkarten</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Trinkgeld</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Rabatte / Gutscheine</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Ausgaben</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Ausgaben Bar</th>
                        <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; line-height: 1.2;">Bar Endbestand</th>
                        <!-- <th style="font-size: 0.6rem; margin:0px !important; padding:0px !important; padding-top:-7px !important; line-height: 1.2;">Kellner Tagesumsatz</th> -->
                    </tr>

                    <?php
                        $acuBrutto = number_format(0,9,'.','');
                        $couponSalesTotal = number_format(0,9,'.','');
                        $rechnungPagesaTotal = number_format(0,9,'.','');
                        $cardPagesaTotal = number_format(0,9,'.','');
                        $onlinePagesaTotal = number_format(0,9,'.','');
                        $cashPagesaTotal = number_format(0,9,'.','');
                        $zbritjaTotal = number_format(0,9,'.','');
                        $thisDaysWaSalesTotal = number_format(0,9,'.','');
                        $thisDaysExpensesTotal = number_format(0,9,'.','');
                        $cashPagesaGCTotal = number_format(0,9,'.','');
                        $cardPagesaGCTotal = number_format(0,9,'.','');
                        $onlinePagesaGCTotal = number_format(0,9,'.','');
                        $rechnungPagesaGCTotal = number_format(0,9,'.','');
                        $tipAmntTotal = number_format(0,9,'.','');
                        $billRecordsExpCashTotal = number_format(0,9,'.','');
                    ?>

                    @for($j=1;$j<=$month->daysInMonth;$j++)
                    <?php
                        $acuBrutto += number_format($daysBrutoTot[$j],9,'.','');
                        $couponSales = number_format(0,9,'.','');
                        $rechnungPagesa = number_format(0,9,'.','');
                        $cardPagesa = number_format(0,9,'.','');
                        $onlinePagesa = number_format(0,9,'.','');
                        $cashPagesa = number_format(0,9,'.','');
                        $zbritja = number_format(0,9,'.','');
                        $netoTot = number_format(0,9,'.','');
                        $barazimiKamarier = number_format(0,9,'.','');
                        $cashPagesaGC = number_format(0,9,'.','');
                        $cardPagesaGC = number_format(0,9,'.','');
                        $onlinePagesaGC = number_format(0,9,'.','');
                        $rechnungPagesaGC = number_format(0,9,'.','');
                        $tipAmnt = number_format(0,9,'.','');
                        $billRecordsExpCash = number_format(0,9,'.','');
                        
                        $dateCheckCreate = new Carbon($dt2d[1].'-'.sprintf('%02d', $dt2d[0]).'-'.sprintf('%02d', $j));
                        $dayOfWeekNr = date('w', strtotime($dateCheckCreate));

                        $billRecordsExp = billsExpensesRecordRes::where([['forRes',Auth::user()->sFor],['forDate',$dateCheckCreate]])->first();
                        if($billRecordsExp != Null){
                            $billRecordsExpCash = number_format($billRecordsExp->expCash,9,'.','');
                        }else{
                            $billRecordsExpCash = number_format(0,9,'.','');
                        }
                        $billRecordsExpCashTotal += $billRecordsExpCash;


                        $resClock = explode('->',$theRes->reportTimeArc);
                        $resClock1_2D = explode(':',$resClock[0]);
                        $resClock2_2D = explode(':',$resClock[1]);
                        $dateStart = Carbon::create($dt2d[1], $dt2d[0], $j, $resClock1_2D[0], $resClock1_2D[1], 00);
                        $dateEnd = Carbon::create($dt2d[1], $dt2d[0], $j, $resClock2_2D[0], $resClock2_2D[1], 59);
                        if($theRes->reportTimeOtherDay == 1){
                            // diff day
                            $dateEnd->addDays(1); 
                        }

                        $thisDaysOrds = OrdersPassive::where([['Restaurant',$oneRes],['statusi','!=','2']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
                        $thisDaysGCSells = giftCard::where([['toRes',$oneRes],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

                        // iterim neper qdo porosi per kete date
                        foreach($thisDaysOrds as $thOrOne){
                            // + $thOrOne->cuponOffVal
                            $zbritjaThisOrd = number_format(0,9,'.','');

                            if($thOrOne->inPercentageDiscount > 0){
                                $prcOff = number_format($thOrOne->inPercentageDiscount / 100,9,'.','');
                                $prcOffCHF = number_format(($thOrOne->shuma + $thOrOne->cuponOffVal)* $prcOff,9,'.','');
                            }else{
                                $prcOffCHF = number_format(0,9,'.','');
                            }
                            
                            $zbritjaThisOrd += number_format($thOrOne->cuponOffVal,9,'.','');
                            $zbritjaThisOrd += number_format($thOrOne->inCashDiscount,9,'.','');
                            $zbritjaThisOrd += number_format($prcOffCHF,9,'.','');

                            if($thOrOne->payM == 'Rechnung'){
                                $rechnungPagesa += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                                $rechnungPagesaTotal += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');

                            }else if($thOrOne->payM == 'Kartenzahlung'){
                                $cardPagesa += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                                $cardPagesaTotal += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');

                            }else if($thOrOne->payM == 'Onlinezahlung' || $thOrOne->payM == 'Online'){
                                $onlinePagesa += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                                $onlinePagesaTotal += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');

                            }else if($thOrOne->payM == 'Barzahlungen' || $thOrOne->payM == 'Cash'){
                                $cashPagesa += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                                $cashPagesaTotal += number_format($thOrOne->shuma - $thOrOne->dicsountGcAmnt - $zbritjaThisOrd,9,'.','');
                            }

                            $tipAmnt += number_format($thOrOne->tipPer,9,'.','');

                            $zbritja += number_format($thOrOne->cuponOffVal,9,'.','');
                            $zbritja += number_format($thOrOne->inCashDiscount,9,'.','');
                            $zbritja += number_format($prcOffCHF,9,'.','');

                            $zbritjaTotal += number_format($thOrOne->cuponOffVal,9,'.','');
                            $zbritjaTotal += number_format($thOrOne->inCashDiscount,9,'.','');
                            $zbritjaTotal += number_format($prcOffCHF,9,'.',''); 

                            $couponSales += number_format($thOrOne->dicsountGcAmnt,9,'.','');
                            $couponSalesTotal += number_format($thOrOne->dicsountGcAmnt,9,'.','');
                        }

                        $tipAmntTotal += number_format($tipAmnt,9,'.','');

                        // $thisDaysWaSales = waiterDaySales::where([['forRes',$oneRes],['forDay',$dt2d[1].'-'.sprintf('%02d', $dt2d[0]).'-'.sprintf('%02d', $j)]])->sum('totalInChf');
                        // $thisDaysWaSalesTotal += number_format($thisDaysWaSales,9,'.','');

                        $thisDaysExpenses = billsExpensesRecordRes::where([['forRes',$oneRes],['forDate',$dt2d[1].'-'.sprintf('%02d', $dt2d[0]).'-'.sprintf('%02d', $j).' 00:00:00']])->first();
                        if($thisDaysExpenses != Null){
                            $thisDaysExpensesVal = number_format($thisDaysExpenses->expValue,9,'.','');
                            $thisDaysExpensesTotal += number_format($thisDaysExpenses->expValue,9,'.','');
                        }else{
                            $thisDaysExpensesVal = number_format(0,9,'.','');
                        }


                        foreach($thisDaysGCSells as $thGCSellOne){
                            if($thGCSellOne->payM == 'Cash'){
                                $cashPagesaGC += number_format($thGCSellOne->gcSumInChf,9,'.','');
                                $cashPagesaGCTotal += number_format($thGCSellOne->gcSumInChf,9,'.','');
                            }else if($thGCSellOne->payM == 'Card'){
                                $cardPagesaGC += number_format($thGCSellOne->gcSumInChf,9,'.','');
                                $cardPagesaGCTotal += number_format($thGCSellOne->gcSumInChf,9,'.','');
                            }else if($thGCSellOne->payM == 'Online'){
                                $onlinePagesaGC += number_format($thGCSellOne->gcSumInChf,9,'.','');
                                $onlinePagesaGCTotal += number_format($thGCSellOne->gcSumInChf,9,'.','');
                            }else if($thGCSellOne->payM == 'Rechnung'){
                                $rechnungPagesaGC += number_format($thGCSellOne->gcSumInChf,9,'.','');
                                $rechnungPagesaGCTotal += number_format($thGCSellOne->gcSumInChf,9,'.','');
                            }

                        }
                        
                    ?>

                    <tr style="border-bottom:1px solid #333; border-left:1px solid #333;">
                        <td class="tdOnDays2"><strong>{{$j}}</strong></td>
                        <td class="tdOnDays2"><strong>{{number_format($cashPagesa + $cardPagesa + $onlinePagesa + $rechnungPagesa + $couponSales + $cashPagesaGC + $cardPagesaGC + $onlinePagesaGC + $rechnungPagesaGC - $tipAmnt,2,'.','')}}</strong></td>
                        <td class="tdOnDays2">{{number_format($cashPagesa + $cashPagesaGC - $tipAmnt ,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($cardPagesa + $cardPagesaGC,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($onlinePagesa + $onlinePagesaGC,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($rechnungPagesa + $rechnungPagesaGC,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($couponSales,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($tipAmnt ,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($zbritja,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($thisDaysExpensesVal,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($billRecordsExpCash,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format(($cashPagesa + $cashPagesaGC - $tipAmnt) - $billRecordsExpCash,2,'.','')}}</td>
                    </tr>

                    @endfor
                    <tr style="border-bottom:1px solid #333; border-left:1px solid #333;" class="heading">
                        <td class="tdOnDays2"><strong>T:</strong></td>
                        <td class="tdOnDays2"><strong>{{number_format($cashPagesaTotal + $cardPagesaTotal + $onlinePagesaTotal + $rechnungPagesaTotal + $couponSalesTotal + $cashPagesaGCTotal + $cardPagesaGCTotal + $onlinePagesaGCTotal + $rechnungPagesaGCTotal - $tipAmntTotal,2,'.','')}}</strong></td>
                        <td class="tdOnDays2">{{number_format($cashPagesaTotal + $cashPagesaGCTotal - $tipAmntTotal,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($cardPagesaTotal + $cardPagesaGCTotal,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($onlinePagesaTotal + $onlinePagesaGCTotal,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($rechnungPagesaTotal + $rechnungPagesaGCTotal,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($couponSalesTotal,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($tipAmntTotal ,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($zbritjaTotal,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($thisDaysExpensesTotal,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format($billRecordsExpCashTotal,2,'.','')}}</td>
                        <td class="tdOnDays2">{{number_format(($cashPagesaTotal + $cashPagesaGCTotal - $tipAmntTotal) - $billRecordsExpCashTotal,2,'.','')}}</td>
                    </tr>

                </table>

                
            </div>
        @endforeach
    </main>
</body>

</html>