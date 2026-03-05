<?php

    use App\User;
    use App\giftCard;
    use App\Takeaway;
    use App\LlojetPro;
    use App\Produktet;
    use App\Restorant;
    use Carbon\Carbon;
    use App\OrdersPassive;
    use Illuminate\Support\Facades\Auth;

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Rechnung</title>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

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



            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                border: 1px solid #dddddd;
                text-align: center;
                padding: 7px;
            }
        
        </style>

        @include('fontawesome')
    </head>

    <?php
        $theRes = Restorant::find(Auth::user()->sFor);
        $adr2D =  explode(',',$theRes->adresa);
        $adr1 =  $adr2D[0];
        $adr2 = '---' ;
        if(isset($adr2D[1])){ $adr2 = $adr2D[1] ; }
        if(isset($adr2D[2])){ $adr2 = $adr2D[1].','.$adr2D[2]; }

        $dateOfCr = date('d').'.'.date('m').'.'.date('Y').' / '.date('H').':'.date('i');
        $teDh = $_GET['dtS'].'--77--'.$_GET['dtE'].'--77--MostSales_TestRes_'.$_GET['dtS'].'->'.$_GET['dtE'];
        $teDh2D = explode('--77--',$teDh);

        $totSaleChf = number_format(0, 6, '.', '');

        $totSaleChfCash = number_format(0, 6, '.', '');
        $totSaleChfCard = number_format(0, 6, '.', '');
        $totSaleChfOnline = number_format(0, 6, '.', '');
        $totSaleChfRechnung = number_format(0, 6, '.', '');
        $totSaleChfGiftCard = number_format(0, 6, '.', '');

        $tipsPerWorker = array();

        $totProdsSasia = number_format(0, 0, '.', '');
        $totProdsUnique = number_format(0, 0, '.', '');
        $totGCSasia = number_format(0, 0, '.', '');



        $prodsId = array();
        $prodsSaleId = array();
        $prodsSaleSasia = array();
        $prodsSaleCHF = array();
        $prodsSaleCHFTotal = number_format(0, 6, '.', '');
        $prodsSaleCHFPerHrTotal = number_format(0, 6, '.', '');

        $prodsWith0SalesSasia = number_format(0, 0, '.', '');

        $totGiftCardSoldCHF = number_format(0, 6, '.', '');
        $totGiftCardLeftCHF = number_format(0, 6, '.', '');
        $totGCCashChf = number_format(0, 6, '.', '');
        $totGCCardChf = number_format(0, 6, '.', '');
        $totGCOnlineChf = number_format(0, 6, '.', '');
        $totGCRechnungChf = number_format(0, 6, '.', '');
        $totGCCashSasi = number_format(0, 0, '.', '');
        $totGCCardSasi = number_format(0, 0, '.', '');
        $totGCOnlineSasi = number_format(0, 0, '.', '');
        $totGCRechnungSasi = number_format(0, 0, '.', '');

        $totOrdersSasia = number_format(0, 0, '.', '');
        $totOrdersCardSasia = number_format(0, 0, '.', '');
        $totOrdersCashSasia = number_format(0, 0, '.', '');
        $totOrdersOnlineSasia = number_format(0, 0, '.', '');
        $totOrdersRechnungSasia = number_format(0, 0, '.', '');
        $totOrdersGCSasia = number_format(0, 0, '.', '');

        $workersIdServed = array();
        $workersSasiaServed = number_format(0, 0, '.', '');
        $workersSaleInCHF = array();
        $workersDiscountInCHF = array();

        $prodsNameByOr = array();

        $discountTotalCHF = number_format(0, 6, '.', '');

        // queries
        $ordersAll = OrdersPassive::where([['Restaurant',$theRes->id],['statusi','3']])->whereDate('created_at','>=',$teDh2D[0])->whereDate('created_at','<=',$teDh2D[1])->get();
        $productsAll = Produktet::where('toRes',$theRes->id)->pluck('id');
        $giftCardAll = giftCard::where('toRes',$theRes->id)->whereDate('created_at','>=',$teDh2D[0])->whereDate('created_at','<=',$teDh2D[1])->get();

        
        foreach($ordersAll as $orderOne){

            // totali i shitjes 
            $totOrderPayChf = number_format(0, 6, '.', '');
            if ($orderOne->inCashDiscount > 0){
                $totOrderPayChf += number_format($orderOne->shuma - $orderOne->inCashDiscount - $orderOne->tipPer , 6, '.', '');
                $discountTotalCHF += number_format($orderOne->inCashDiscount, 6, '.', '');
                if ($orderOne->servedBy != 0){
                    if(isset($workersDiscountInCHF[$orderOne->servedBy])){ 
                        $workersDiscountInCHF[$orderOne->servedBy] += number_format($orderOne->inCashDiscount, 6, '.', '');
                    }else{ 
                        $workersDiscountInCHF[$orderOne->servedBy] = number_format($orderOne->inCashDiscount, 6, '.', ''); 
                    }
                }
            }else if($orderOne->inPercentageDiscount > 0){
                $shumaBef = number_format($orderOne->shuma - $orderOne->tipPer, 6, '.', '');
                $theDi = number_format($shumaBef * ($orderOne->inPercentageDiscount * 0.01), 6, '.', '');
                $totOrderPayChf += number_format($orderOne->shuma - $theDi - $orderOne->tipPer, 6, '.', '');
                $discountTotalCHF += number_format($theDi, 6, '.', '');
                if ($orderOne->servedBy != 0){
                    if(isset($workersDiscountInCHF[$orderOne->servedBy])){ 
                        $workersDiscountInCHF[$orderOne->servedBy] += number_format($theDi, 6, '.', '');
                    }else{ 
                        $workersDiscountInCHF[$orderOne->servedBy] = number_format($theDi, 6, '.', ''); 
                    }
                }
            }else{
                $totOrderPayChf += number_format($orderOne->shuma - $orderOne->tipPer, 6, '.', '');
            }

            // count tips
            if(isset($tipsPerWorker[$orderOne->servedBy])){
                $tipsPerWorker[$orderOne->servedBy] += number_format($orderOne->tipPer, 6, '.', '');
            }else{
                $tipsPerWorker[$orderOne->servedBy] = number_format($orderOne->tipPer, 6, '.', '');
            }

            $totSaleChf += number_format($totOrderPayChf, 6, '.', '');
            
            $totOrdersSasia++;
            // LLojet e pageses
            $totSaleChfGiftCard += number_format($orderOne->dicsountGcAmnt, 2, '.', '');
            if($orderOne->dicsountGcAmnt == 0 || $orderOne->dicsountGcAmnt != $totOrderPayChf){
                if($orderOne->payM == 'Barzahlungen' || $orderOne->payM == 'Cash'){
                    $totSaleChfCash += number_format((float)$totOrderPayChf - $orderOne->dicsountGcAmnt, 2, '.', '');
                    $totOrdersCashSasia++;
                }else if($orderOne->payM == 'Kartenzahlung'){
                    $totSaleChfCard += number_format((float)$totOrderPayChf - $orderOne->dicsountGcAmnt, 2, '.', '');
                    $totOrdersCardSasia++;
                }else if($orderOne->payM == 'Online' || $orderOne->payM == 'Onlinezahlung'){
                    $totSaleChfOnline += number_format((float)$totOrderPayChf - $orderOne->dicsountGcAmnt, 2, '.', '');
                    $totOrdersOnlineSasia++;
                }else if($orderOne->payM == 'Rechnung'){
                    $totSaleChfRechnung += number_format((float)$totOrderPayChf - $orderOne->dicsountGcAmnt, 2, '.', '');
                    $totOrdersRechnungSasia++;
                }
            }else{
                $totOrdersGCSasia++;
            }

            // Iterim neper produkte 

            foreach(explode('---8---',$orderOne->porosia) as $oneProdOnOrd){
                $oneProdOnOrd2D = explode('-8-',$oneProdOnOrd);
                // emri i produktit
                if(!isset($prodsNameByOr[$oneProdOnOrd2D[7]])){
                    $prodsNameByOr[$oneProdOnOrd2D[7]] = $oneProdOnOrd2D[0];
                }
               
                if(!in_array($oneProdOnOrd2D[7],$prodsSaleId)){
                    $totProdsUnique++;
                    array_push($prodsSaleId,$oneProdOnOrd2D[7]);
                }
                $totProdsSasia += number_format((int)$oneProdOnOrd2D[3], 0, '.', '');

                if(in_array($oneProdOnOrd2D[7],$prodsSaleId)){
                    if(isset($prodsSaleSasia[$oneProdOnOrd2D[7]])){
                        $prodsSaleSasia[$oneProdOnOrd2D[7]] += number_format((int)$oneProdOnOrd2D[3], 0, '.', '');
                    }else{
                        $prodsSaleSasia[$oneProdOnOrd2D[7]] = number_format((int)$oneProdOnOrd2D[3], 0, '.', '');
                    }
                }

                // cal the dis
                $totOrderPayChfForRabattDis = number_format($orderOne->shuma - $orderOne->tipPer , 10, '.', '');
                $thisOrDiscChf = number_format(0 , 10, '.', '');
                if ($orderOne->inCashDiscount > 0){
                    $thisOrDiscChf = number_format($orderOne->inCashDiscount, 10, '.', '');
                }else if($orderOne->inPercentageDiscount > 0){
                    $shumaBef = number_format($orderOne->shuma - $orderOne->tipPer, 10, '.', '');
                    $theDi = number_format($shumaBef * ($orderOne->inPercentageDiscount * 0.01), 10, '.', '');
                    $thisOrDiscChf = number_format($theDi , 10, '.', '');
                }

                $thisProdPercInOrder = number_format($oneProdOnOrd2D[4] / 100 , 10, '.', '');
                $thisProdDiscAmntChf = number_format($thisProdPercInOrder * $thisOrDiscChf , 10, '.', '');

                if(isset($prodsSaleCHF[$oneProdOnOrd2D[7]])){
                    $prodsSaleCHF[$oneProdOnOrd2D[7]] += number_format($oneProdOnOrd2D[4] - $thisProdDiscAmntChf , 10, '.', '');
                }else{
                    $prodsSaleCHF[$oneProdOnOrd2D[7]] = number_format($oneProdOnOrd2D[4] - $thisProdDiscAmntChf , 10, '.', '');
                }
                $prodsSaleCHFTotal += number_format($oneProdOnOrd2D[4] - $thisProdDiscAmntChf , 10, '.', '');
            }

            if(!in_array($orderOne->servedBy,$workersIdServed)){
                array_push($workersIdServed,$orderOne->servedBy);
                $workersSasiaServed++;
            }
            if ($orderOne->servedBy != 0){
                if(isset($workersSaleInCHF[$orderOne->servedBy])){ 
                    $workersSaleInCHF[$orderOne->servedBy] += number_format($totOrderPayChf, 6, '.', '');
                }else{ 
                    $workersSaleInCHF[$orderOne->servedBy] = number_format($totOrderPayChf, 6, '.', ''); 
                }
            }


        }
        arsort($prodsSaleSasia);
        arsort($prodsSaleCHF);
        arsort($workersSaleInCHF);
        arsort($workersDiscountInCHF);
        


        foreach($productsAll as $prodAllOne){
            if(!in_array($prodAllOne,$prodsSaleId))
                $prodsWith0SalesSasia++;
        }


        foreach($giftCardAll as $gcOne){
            $totGiftCardSoldCHF += number_format($gcOne->gcSumInChf, 6, '.', '');
            $totGiftCardLeftCHF += number_format($gcOne->gcSumInChf - $gcOne->gcSumInChfUsed, 6, '.', '');
            $totGCSasia++;
            if($gcOne->payM == 'Cash'){
                $totGCCashChf += number_format($gcOne->gcSumInChf, 6, '.', '');
                $totGCCashSasi++; 
            }else  if($gcOne->payM == 'Card'){
                $totGCCardChf += number_format($gcOne->gcSumInChf, 6, '.', '');
                $totGCCardSasi++;
            }else  if($gcOne->payM == 'Online'){
                $totGCOnlineChf += number_format($gcOne->gcSumInChf, 6, '.', '');
                $totGCOnlineSasi++;
            }else  if($gcOne->payM == 'Rechnung'){
                $totGCRechnungChf += number_format($gcOne->gcSumInChf, 6, '.', '');
                $totGCRechnungSasi++;
            }
        }


        $salesPerDayCHF = array();
        $salesPerHourCHF = array();
        $checkDateSales = Carbon::createFromFormat('Y-m-d', $teDh2D[0]);
        $checkDateSales = $checkDateSales->subDays(1);
        while( $checkDateSales <= $teDh2D[1]){
            $checkDateSales = $checkDateSales->addDays(1);
            $dateToSaveInArray = explode(' ',$checkDateSales)[0];

            $startDt = $dateToSaveInArray.' 00:00:00';
            $endDt = $dateToSaveInArray.' 23:59:59';

            $totInChfThisDate = number_format(0, 6, '.', '');
            $ordersAllThisDate = OrdersPassive::where([['Restaurant',$theRes->id],['statusi','3']])->whereDate('created_at','>=',$startDt)->whereDate('created_at','<=',$endDt)->get();
            foreach($ordersAllThisDate as $ordersOneThisDate){

                $totInChfThisOrder = number_format(0, 6, '.', '');
                if ($ordersOneThisDate->inCashDiscount > 0){
                    $totInChfThisDate += number_format($ordersOneThisDate->shuma - $ordersOneThisDate->inCashDiscount - $ordersOneThisDate->tipPer, 6, '.', '');
                    $totInChfThisOrder = number_format($ordersOneThisDate->shuma - $ordersOneThisDate->inCashDiscount - $ordersOneThisDate->tipPer, 6, '.', '');
                }else if($ordersOneThisDate->inPercentageDiscount > 0){
                    $shumaBef = number_format($ordersOneThisDate->shuma - $ordersOneThisDate->tipPer, 6, '.', '');
                    $theDi = number_format($shumaBef * ($ordersOneThisDate->inPercentageDiscount * 0.01), 6, '.', '');
                    $totInChfThisDate += number_format($ordersOneThisDate->shuma - $theDi - $ordersOneThisDate->tipPer, 6, '.', '');
                    $totInChfThisOrder = number_format($ordersOneThisDate->shuma - $theDi - $ordersOneThisDate->tipPer, 6, '.', '');
                }else{
                    $totInChfThisDate += number_format($ordersOneThisDate->shuma - $ordersOneThisDate->tipPer, 6, '.', '');
                    $totInChfThisOrder = number_format($ordersOneThisDate->shuma - $ordersOneThisDate->tipPer, 6, '.', '');
                }  
                
         

                $hour = 1;
                $startDtHour = $dateToSaveInArray.' 00:00:00';
                $endDtHour = $dateToSaveInArray.' 00:59:59';
               
                while( $hour <= 24){
                    if($ordersOneThisDate->created_at >= $startDtHour && $ordersOneThisDate->created_at <= $endDtHour){
                        // Hour 1
                        if(isset($salesPerHourCHF[$hour])){
                            $salesPerHourCHF[$hour] += number_format($totInChfThisOrder, 6, '.', '');
                        }else{
                            $salesPerHourCHF[ $hour] = number_format($totInChfThisOrder, 6, '.', '');
                        }
                        $prodsSaleCHFPerHrTotal += number_format($totInChfThisOrder, 6, '.', '');
                    }
                    
                    $startDtHour = $dateToSaveInArray.' '.$hour.':00:00';
                    $endDtHour = $dateToSaveInArray.' '.$hour.':59:59';
                    $hour++;
                }
            }
            if(isset($salesPerDayCHF[$dateToSaveInArray])){
                $salesPerDayCHF[$dateToSaveInArray] += number_format($totInChfThisDate, 6, '.', '');
            }else{
                $salesPerDayCHF[$dateToSaveInArray] = number_format($totInChfThisDate, 6, '.', '');
            }

            
            
        }
        foreach($salesPerDayCHF as $key => $value){
            if(number_format($value, 2, '.', '') == 0){
                unset($salesPerDayCHF[$key]);
            }
        }
        foreach($salesPerHourCHF as $key => $value){
            if(number_format($value, 2, '.', '') == 0){
                unset($salesPerHourCHF[$key]);
            }
        }
        arsort($salesPerDayCHF);
        arsort($salesPerHourCHF);
       
       
    ?>



















    <button style="width: 4cm; border:1px solid rgb(72,81,87); border-radius:6px; padding:12px; background-color:black; color:white; font-size:1.4rem;"
        id="backBtn" onclick="history.back()">
        Zurück
    </button>
    <button style="width: 19cm; border:1px solid rgb(72,81,87); border-radius:6px; padding:12px; background-color:#FF0000; color:white; font-size:1.4rem;" 
        id="downloadPDFBtn" onclick="downloadPDF('{{$teDh2D[2]}}')">
        <strong>PDF generieren / herunterladen</strong>
    </button>


    <body id="pageCounter">
    
        <main>
            <div class="invoice-box">
                
                <p style="font-size: 1.1rem; display:flex; justify-content: between;">
                    <?php
                        $dt1_2D = explode('-',$teDh2D[0]);
                        $dt2_2D = explode('-',$teDh2D[1]);
                    ?>
                    <span style="width:50%;"><strong>{{$theRes->emri}}</strong></span>
                    <span style="width:50%; text-align:right;">Generiert: {{$dateOfCr}}</span>
                </p>    
                <p style="font-size: 1.3rem;">
                    <?php
                        $dt1_2D = explode('-',$teDh2D[0]);
                        $dt2_2D = explode('-',$teDh2D[1]);
                    ?>
                    <strong>Bericht vom: {{$dt1_2D[2]}}.{{$dt1_2D[1]}}.{{$dt1_2D[0]}} bis {{$dt2_2D[2]}}.{{$dt2_2D[1]}}.{{$dt2_2D[0]}}</strong>
                </p>    
                <p style="color: white;">.</p>   
               
                
                <!-- TEXT -->

                <p style="margin-bottom:2px; font-size:1.2rem;">Im analysierten Zeitraum wurde ein Gesamtumsatz von CHF {{number_format($totSaleChf + $totGiftCardSoldCHF, 2, '.', '')}} erzielt. Die Zahlungsarten verteilen sich wie folgt:</p>
                <ul style="margin-top:2px; font-size:1.2rem;"">
                    <li>CHF {{number_format($totSaleChfCard + $totGCCardChf, 2, '.', '')}} per Kartenzahlung</li>
                    <li>CHF {{number_format($totSaleChfCash + $totGCCashChf, 2, '.', '')}} in bar</li>
                    <li>CHF {{number_format($totSaleChfRechnung + $totGCRechnungChf, 2, '.', '')}} auf Rechnung</li>
                    <li>CHF {{number_format($totSaleChfOnline + $totGCOnlineChf, 2, '.', '')}} über Online-Zahlungen</li>
                    <li>CHF {{number_format($totSaleChfGiftCard, 2, '.', '')}} mit Geschenkkarten</li>
                </ul>  
                
                <p style="font-size:1.2rem;">Insgesamt wurden {{$totProdsUnique}} verschiedene Produkte verkauft - in Summe {{number_format($totProdsSasia, 0, '.', '')}} Einheiten - sowie {{$totGCSasia}} Geschenkkarten.</p>
                
                <?php
                    $prodsSaleSasiaRemoved_0 = array();
                    foreach($prodsSaleSasia as $key => $value){
                        if(number_format($value, 2, '.', '') > 0){
                            $prodsSaleSasiaRemoved_0[$key] = $value;
                        }
                    }

                    $prodIdMostSalesSasi = array_key_first($prodsSaleSasiaRemoved_0);
                    $prodIdLeastSalesSasi = array_key_last($prodsSaleSasiaRemoved_0);

                    $mostSalesMaxSasia = $prodsSaleSasiaRemoved_0[$prodIdMostSalesSasi];
                    $mostSalesMinSasia = $prodsSaleSasiaRemoved_0[$prodIdLeastSalesSasi];

                    $theProMost = Produktet::find($prodIdMostSalesSasi);
                    $theProLeast = Produktet::find($prodIdLeastSalesSasi);
                    
                ?>

                <p style="font-size:1.2rem;">Das meistverkaufte Produkt war 
                    @if ($theProMost == Null)
                        "Nicht gefunden"
                    @else
                        {{$theProMost->emri}} 
                    @endif
                    mit {{$prodsSaleSasia[$prodIdMostSalesSasi]}} 
                    verkauften Einheiten und einem Umsatz von CHF {{number_format($prodsSaleCHF[$prodIdMostSalesSasi], 2, '.', '')}}.
                     Das am wenigsten verkaufte Produkt war 
                     @if ($theProLeast == Null)
                     "Nicht gefunden"
                     @else
                     {{$theProLeast->emri}}
                     @endif
                      mit 
                     {{$prodsSaleSasia[$prodIdLeastSalesSasi]}} Einheiten und einem Umsatz von CHF {{number_format($prodsSaleCHF[$prodIdLeastSalesSasi], 2, '.', '')}}.
                </p>

                <p style="font-size:1.2rem;">{{$prodsWith0SalesSasia}} registrierte Produkte wurden im gesamten Zeitraum nicht verkauft.</p>
       
                <p style="font-size:1.2rem;">Von den verkauften Geschenkkarten im Gesamtwert von CHF {{number_format($totGiftCardSoldCHF, 2, '.', '')}} sind derzeit noch CHF {{number_format($totGiftCardLeftCHF, 2, '.', '')}} im Umlauf.</p>


                <p style="margin-bottom:2px; font-size:1.2rem;">Insgesamt wurden {{$totOrdersSasia}} Belege erstellt, verteilt auf folgende Zahlungsarten:</p>
                <ul style="margin-top:2px; font-size:1.2rem;">
                    <li>{{$totOrdersCardSasia + $totGCCardSasi}} bei Kartenzahlungen</li>
                    <li>{{$totOrdersCashSasia + $totGCCashSasi}} bei Barzahlungen</li>
                    <li>{{$totOrdersRechnungSasia + $totGCRechnungSasi}} bei Zahlungen auf Rechnung</li>
                    <li>{{$totOrdersOnlineSasia +  $totGCOnlineSasi}} bei Online-Zahlungen</li>
                    <li>{{$totOrdersGCSasia}} bei Zahlungen mit Geschenkkarten</li>
                </ul>

                <?php

                    $dateMostSalesChf = array_key_first($salesPerDayCHF);
                    $dateLeastSalesChf = array_key_last($salesPerDayCHF);

                    $mostSalesMaxCHFDate = $salesPerDayCHF[$dateMostSalesChf];
                    $mostSalesMinCHFDate = $salesPerDayCHF[$dateLeastSalesChf];

                    $dateMostSalesChf2D = explode('-',$dateMostSalesChf);
                    $dateLeastSalesChf2D = explode('-',$dateLeastSalesChf);
                ?>
                
                <p style="font-size:1.2rem;">Der umsatzstärkste Tag war der {{$dateMostSalesChf2D[2]}}.{{$dateMostSalesChf2D[1]}}.{{$dateMostSalesChf2D[0]}} mit einem Umsatz von CHF {{number_format($mostSalesMaxCHFDate, 2, '.', '')}}, während der
                     umsatzschwächste Tag auf den {{$dateLeastSalesChf2D[2]}}.{{$dateLeastSalesChf2D[1]}}.{{$dateLeastSalesChf2D[0]}} mit CHF {{number_format($mostSalesMinCHFDate, 2, '.', '')}} fiel.
                </p>

                <?php

                    $hourMostSalesChf = array_key_first($salesPerHourCHF);
                    $hourLeastSalesChf = array_key_last($salesPerHourCHF);

                    $mostSalesMaxCHFHour = $salesPerHourCHF[$hourMostSalesChf];
                    $mostSalesMinCHFHour = $salesPerHourCHF[$hourLeastSalesChf];

                    $calc = number_format(0, 2, '.', '');
                ?>
                <p style="font-size:1.2rem;">Die umsatzstärkste Stunde lag zwischen {{$hourMostSalesChf-1}}:00 und {{$hourMostSalesChf-1}}:59 Uhr mit einem Umsatz von CHF {{number_format($mostSalesMaxCHFHour, 2, '.', '')}},
                     die umsatzschwächste Stunde zwischen {{$hourLeastSalesChf-1}}:00 und {{$hourLeastSalesChf-1}}:59 Uhr mit CHF {{number_format($mostSalesMinCHFHour, 2, '.', '')}}.
                </p>

                <p style="font-size:1.2rem;">
                    Diese Umsätze wurden von {{$workersSasiaServed}} Mitarbeitenden erwirtschaftet. Den Gästen wurden im gesamten Zeitraum 
                    Rabatte im Wert von CHF {{number_format($discountTotalCHF, 2, '.', '')}} gewährt.
                </p>
                    
                <p style="font-size:1.2rem; margin-bottom:1.3cm;">
                    Anbei finden Sie eine detaillierte Auswertung zu Umsätzen, Produkten, Zeitpunkten sowie weiteren relevanten Kennzahlen.
                </p>

                <!-- TEXT END -->

                <!-- DIAGRAM -->

                <div style="width:90%; margin-right:5%;" class="chart-area">
                    <canvas id="chart01">1</canvas>
                </div>

                <p style="font-size: 1.1rem; margin-bottom: 0px;"><strong>Verkaufte Produkte nach Umsatz</strong></p>
                <table>
                    <tr>
                        <th style="width:50% !important;">Produkt</th>
                        <th style="width:16.6% !important;">Menge verkauft</th>
                        <th style="width:16.6% !important;">Umsatz (CHF)</th>
                        <th style="width:16.6% !important;">Anteil (%)</th>
                    </tr>
            
                    @foreach ($prodsSaleCHF as $key => $value) 
                    <tr>
                        <?php 
                            $thePro = Produktet::find($key); 
                            if($thePro != Null && $thePro->toRes != Auth::user()->sFor){
                                $thePro = Takeaway::find($key); 

                                if($thePro != Null && $thePro->toRes != Auth::user()->sFor){
                                    $thePro = Null;
                                }
                            }
                            $theAnteil = number_format(($value/$prodsSaleCHFTotal)*100, 2, '.', '');
                        ?>
                        @if ($thePro == Null)
                            @if (isset($prodsNameByOr[$key]))
                                <td style="text-align:left !important; width:50% !important; padding-left:1cm;">
                                    <span style="font-weight: bold; font-size:1.1rem;">({{$key}}) {{$prodsNameByOr[$key]}}</span>
                                </td>
                            @else
                                <td style="text-align:left !important; width:50% !important; padding-left:1cm;">
                                    <span style="font-weight: bold; font-size:1.1rem;">({{$key}}) ---</span>
                                </td>
                            @endif
                        @else
                            
                            <?php
                                $prodTypeSaleCHF = array();
                                $prodTypeSaleSasi = array();
                                foreach($ordersAll as $orderOne){
                                    foreach(explode('---8---',$orderOne->porosia) as $oneProdOnOrd){
                                        $oneProdOnOrd2D = explode('-8-',$oneProdOnOrd);
                                        if(isset($oneProdOnOrd2D[7]) && $oneProdOnOrd2D[7] == $thePro->id){
                                            if($oneProdOnOrd2D[5] != ''){

                                                $theTypeId = 0;
                                                if ($thePro->type != Null && $thePro->type != ''){
                                                    foreach(explode('--0--',$thePro->type) as $typeOne){
                                                        $typeOne2D = explode('||',$typeOne);
                                                        if($typeOne2D[1] == $oneProdOnOrd2D[5]){
                                                            $theTypeId = $typeOne2D[0];
                                                            break;
                                                        }
                                                    }
                                                }
                                                if($theTypeId != 0){

                                                    // cal the dis
                                                    $totOrderPayChfForRabattDis = number_format($orderOne->shuma - $orderOne->tipPer , 10, '.', '');
                                                    $thisOrDiscChf = number_format(0 , 10, '.', '');
                                                    if ($orderOne->inCashDiscount > 0){
                                                        $thisOrDiscChf = number_format($orderOne->inCashDiscount, 10, '.', '');
                                                    }else if($orderOne->inPercentageDiscount > 0){
                                                        $shumaBef = number_format($orderOne->shuma - $orderOne->tipPer, 10, '.', '');
                                                        $theDi = number_format($shumaBef * ($orderOne->inPercentageDiscount * 0.01), 10, '.', '');
                                                        $thisOrDiscChf = number_format($theDi , 10, '.', '');
                                                    }

                                                    $thisProdPercInOrder = number_format($oneProdOnOrd2D[4] / 100 , 10, '.', '');
                                                    $thisProdDiscAmntChf = number_format($thisProdPercInOrder * $thisOrDiscChf , 10, '.', '');


                                                    if(isset($prodTypeSaleCHF[$theTypeId])){
                                                        $prodTypeSaleCHF[$theTypeId] += number_format($oneProdOnOrd2D[4] - $thisProdDiscAmntChf , 10, '.', '');
                                                        $prodTypeSaleSasi[$theTypeId] += number_format($oneProdOnOrd2D[3], 0, '.', '');
                                                    }else{
                                                        $prodTypeSaleCHF[$theTypeId] = number_format($oneProdOnOrd2D[4] - $thisProdDiscAmntChf , 10, '.', '');
                                                        $prodTypeSaleSasi[$theTypeId] = number_format($oneProdOnOrd2D[3], 0, '.', '');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                arsort($prodTypeSaleCHF);
                            ?>

                            <td style="text-align:left !important; width:50% !important; padding-left:1cm;">
                                <span style="font-weight: bold; font-size:1.1rem;">({{$key}}) {{$thePro->emri}}</span>
                                @if ($thePro->type != Null && $thePro->type != '')
                                    @foreach ($prodTypeSaleCHF as $keyType => $valueType)
                                        <?php $thisType = LlojetPro::find($keyType); ?>
                                            <span style="display:flex; justify-content:between;">
                                        @if ($thisType != Null)
                                                <span style="width:50%; "><i class="fa-solid fa-xs fa-circle"></i> {{$thisType->emri}} </span>
                                        @else
                                                <span style="width:50%; "><i class="fa-solid fa-xs fa-circle"></i> {{$keyType}} </span>
                                        @endif
                                                <span style="width:20%; margin-left: 0.7cm;"> {{$prodTypeSaleSasi[$keyType]}} x </span>
                                                <span style="width:30%; margin-left: 0.7cm;"> {{number_format($valueType, 2, '.', '')}} CHF </span>                             
                                            </span>
                                    @endforeach
                                @endif

                            </td>
                        @endif
                        <td style="text-align:center; width:16.6% !important;">{{$prodsSaleSasia[$key]}}</td>
                        <td style="width:16.6% !important;">{{number_format($value, 2, '.', '')}} CHF</td>
                        <td style="width:16.6% !important;">{{$theAnteil}} %</td>
                    </tr>
                    @endforeach

                    @foreach ($productsAll as $allProOne) 
                        @if (!in_array($allProOne,$prodsSaleId))
                        <tr>
                            <?php $thePro = Produktet::find($allProOne); 
                                if($thePro != Null && $thePro->toRes != Auth::user()->sFor){
                                    $thePro = Takeaway::find($allProOne); 

                                    if($thePro != Null && $thePro->toRes != Auth::user()->sFor){
                                        $thePro = Null;
                                    }
                                }
                            ?>
                            @if ($thePro == Null)
                                @if (isset($prodsNameByOr[$allProOne]))
                                    <td style="text-align:left !important; width:50% !important; padding-left:1cm;">
                                        <span style="font-weight: bold; font-size:1.1rem;">({{$allProOne}}) {{$prodsNameByOr[$allProOne]}}</span>
                                    </td>
                                @else
                                    <td style="text-align:left !important; width:50% !important; padding-left:1cm;">
                                        <span style="font-weight: bold; font-size:1.1rem;">({{$allProOne}}) ---</span>
                                    </td>
                                @endif
                            @else
                            <td style="text-align:left !important; width:50% !important; padding-left:1cm;">
                                <span style="font-weight: bold; font-size:1.1rem;">({{$allProOne}}) {{$thePro->emri}}</span>
                                @if ($thePro->type != Null && $thePro->type != '')
                                    @foreach (explode('--0--',$thePro->type) as $typeOne)
                                        <?php
                                            $typeOne2D = explode('||',$typeOne);
                                        ?>
                                        @if (isset($typeOne2D[1]))
                                            <span style="display:flex; justify-content:between;">
                                                <span style="width:60%; "><i class="fa-solid fa-xs fa-circle"></i> {{$typeOne2D[1]}} </span>
                                                <span style="width:20%; margin-left: 0.7cm;"> 0 x </span>
                                                <span style="width:20%; margin-left: 0.7cm;"> {{number_format(0, 2, '.', '')}} CHF </span>
                                            </span>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            @endif
                            <td style="text-align:center; width:16.6% !important;">0</td>
                            <td style="width:16.6% !important;">0.00 CHF</td>
                            <td style="width:16.6% !important;">0.00 %</td>
                        </tr>
                        @endif

                    @endforeach
                    <!-- $productsAll -->
                         
                </table>


                <div style="width:90%; margin-right:5%;" class="chart-area">
                    <canvas id="chart02">1</canvas>
                </div>

                <div style="width:90%; margin-right:5%;" class="chart-area">
                    <canvas id="chart03">1</canvas>
                </div>

                <div style="width:90%; margin-right:5%;" class="chart-area">
                    <canvas id="chart04">1</canvas>
                </div>

                <div style="width:90%; margin-right:5%;" class="chart-area">
                    <canvas id="chart05">1</canvas>
                </div>

                <p style="font-size: 1.1rem; margin-bottom: 0px;"><strong>Stündlicher Umsatz (sortiert nach Umsatz)</strong></p>
                <table>
                    <tr>
                        <th>Uhrzeit</th>
                        <th>Umsatz (CHF)</th>
                        <th>Anteil (%)</th>
                    </tr>
                    @foreach ($salesPerHourCHF as $key => $value) 
                    <tr>
                        <?php $theAnteil = number_format(($value/$prodsSaleCHFPerHrTotal)*100, 2, '.', ''); ?>
                        <td>{{$key-1}}:00 -> {{$key-1}}:59</td>
                        <td style="text-align: center;">{{number_format($value, 2, '.', '')}} CHF</td>
                        <td>{{$theAnteil}} %</td>
                    </tr>
                    @endforeach
                </table>
                <p style="font-size: 0.8rem; margin-top: 0px;"><strong>Ergebnisse mit CHF 0.– werden in dieser Tabelle nicht angezeig</strong></p>

                <div style="width:90%; margin-right:5%;" class="chart-area">
                    <canvas id="chart06">1</canvas>
                </div>

                <div style="width:90%; margin-right:5%;" class="chart-area">
                    <canvas id="chart07">1</canvas>
                </div>

                <div style="width:90%; margin-right:5%;" class="chart-area">
                    <canvas id="chart08">1</canvas>
                </div>

                <!-- DIAGRAM END -->

                <input type="hidden" value="{{$totSaleChfCash + $totGCCashChf}}" id="totSaleChfCash">
                <input type="hidden" value="{{$totSaleChfCard + $totGCCardChf}}" id="totSaleChfCard">
                <input type="hidden" value="{{$totSaleChfOnline + $totGCOnlineChf}}" id="totSaleChfOnline">
                <input type="hidden" value="{{$totSaleChfRechnung + $totGCRechnungChf}}" id="totSaleChfRechnung">
                <input type="hidden" value="{{$totSaleChfGiftCard}}" id="totSaleChfGiftCard">

                <input type="hidden" value="{{number_format($totGiftCardSoldCHF, 2, '.', '')}}" id="totGiftCardSoldCHF">
                <input type="hidden" value="{{number_format($totGiftCardLeftCHF, 2, '.', '')}}" id="totGiftCardLeftCHF">
                <input type="hidden" value="{{number_format($totGiftCardSoldCHF-$totGiftCardLeftCHF, 2, '.', '')}}" id="totGiftCardUsedCHF">


                <input type="hidden" value="{{$totOrdersCardSasia + $totGCCardSasi}}" id="totOrdersCardSasia">
                <input type="hidden" value="{{$totOrdersCashSasia + $totGCCashSasi}}" id="totOrdersCashSasia">
                <input type="hidden" value="{{$totOrdersOnlineSasia + $totGCOnlineSasi}}" id="totOrdersOnlineSasia">
                <input type="hidden" value="{{$totOrdersRechnungSasia + $totGCRechnungSasi}}" id="totOrdersRechnungSasia">
                <input type="hidden" value="{{$totOrdersGCSasia}}" id="totOrdersGCSasia">

                <?php $countDataChart4 = 1; ?>
                @foreach ($salesPerDayCHF as $key => $value)
                    @if (number_format($value, 2, '.', '') > 0)
                        <?php $key2D = explode('-',$key); ?>
                        <input type="hidden" value="{{$key2D[2]}}.{{$key2D[1]}}.{{$key2D[0]}}" id="dataChart4_{{$countDataChart4}}" class="dataChart4_label">
                        <input type="hidden" value="{{number_format($value, 2, '.', '')}}" id="dataSalesChart4_{{$countDataChart4}}" class="dataSalesChart4_data">
                    @endif
                    <?php $countDataChart4++; ?>
                    @if ($countDataChart4 == 11)
                        @break
                    @endif
                @endforeach

                
                <?php 
                    asort($salesPerDayCHF);
                    $countDataChart5 = 1; 
                ?>
                @foreach ($salesPerDayCHF as $key => $value)
                    @if (number_format($value, 2, '.', '') > 0)
                        <?php $key2D = explode('-',$key); ?>
                        <input type="hidden" value="{{$key2D[2]}}.{{$key2D[1]}}.{{$key2D[0]}}" id="dataChart5_{{$countDataChart5}}" class="dataChart5_label">
                        <input type="hidden" value="{{number_format($value, 2, '.', '')}}" id="dataSalesChart5_{{$countDataChart5}}" class="dataSalesChart5_data">
                    @endif
                    <?php $countDataChart5++; ?>
                    @if ($countDataChart5 == 11)
                        @break
                    @endif
                @endforeach


                @foreach ($workersSaleInCHF as $key => $value)
                    <?php $theWrk = User::find($key); ?>
                    @if ($theWrk != Null)
                        <input type="hidden" value="({{$key}}) {{$theWrk->name}}" id="chart06_label_{{$key}}" class="chart06_label">
                    @else
                        <input type="hidden" value="({{$key}})" id="chart06_label_{{$key}}" class="chart06_label">
                    @endif
                    <input type="hidden" value="{{number_format($value, 2, '.', '')}}" id="chart06_data_{{$value}}" class="chart06_data">
                @endforeach

                @foreach ($workersDiscountInCHF as $key => $value)
                    <?php $theWrk = User::find($key); ?>
                    @if ($theWrk != Null)
                        <input type="hidden" value="({{$key}}) {{$theWrk->name}}" id="chart07_label_{{$key}}" class="chart07_label">
                    @else
                        <input type="hidden" value="({{$key}})" id="chart07_label_{{$key}}" class="chart07_label">
                    @endif
                    <input type="hidden" value="{{number_format($value, 2, '.', '')}}" id="chart07_data_{{$value}}" class="chart07_data">
                @endforeach


                <input type="hidden" value="{{$totSaleChf}}" id="totOrdersSalesChf">
                <input type="hidden" value="{{$discountTotalCHF}}" id="totOrdersDiscountCHF">
        

            </div>
        </main> 
   
   </body>


  


















   <script>
        function downloadPDF(fileName){
            $('#downloadPDFBtn').remove();
            $('#backBtn').remove();
            var element = document.getElementById('pageCounter');
            html2pdf().from(element).save(fileName)
        }

        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', '');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        var chart01 = document.getElementById("chart01");
        var chart02 = document.getElementById("chart02");
        var chart03 = document.getElementById("chart03");
        var chart04 = document.getElementById("chart04");
        var chart05 = document.getElementById("chart05");
        var chart06 = document.getElementById("chart06");
        var chart07 = document.getElementById("chart07");
        var chart08 = document.getElementById("chart08");

        var myLineChart1 = new Chart(chart01, {
            type: "bar",
            data: {
                labels: ['Karte', 'Bar', 'Rechnung Zahlungsart', 'Onlinezahlung', 'Geschenkkarte'],
         
                datasets: [{
                    lineTension: 0.5,
                    backgroundColor: "rgba(255,175,0,0.85)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHitRadius: 1,
                    pointBorderWidth: 2,
                    data: [$('#totSaleChfCard').val(), $('#totSaleChfCash').val(), $('#totSaleChfRechnung').val(), $('#totSaleChfOnline').val(), $('#totSaleChfGiftCard').val()],
                }],
            },
            options: {
                title: {
                    display: true,
                    text: "Umsatz nach Zahlungsart",
                    fontSize: 20,
                },
                maintainAspectRatio: true,
                layout: {
                    padding: { left: 10, right: 10, top: 10, bottom: 10}
                },
                scales: {
                    xAxes: [{
                        time: { unit: 'text' },
                        gridLines: { display: false, drawBorder: false },
                    }],
                    yAxes: [{
                        ticks: { maxTicksLimit: 12, padding: 10, callback: function(value, index, values) { return 'CHF ' + number_format(value);}},
                        gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                    }],
                },
                legend: {
                    display: false
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = parseFloat(dataset.data[index]).toFixed(2);
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                }
            }
        });




        var xValues = ["Noch im Umlauf ("+$('#totGiftCardLeftCHF').val()+" CHF)","Eingelöste Geschenkkarten ("+$('#totGiftCardUsedCHF').val()+" CHF)"];
        var yValues = [$('#totGiftCardLeftCHF').val(),$('#totGiftCardUsedCHF').val()];
        var barColors = ["#f46920", "#ffaf00",];
        new Chart(chart02, {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                backgroundColor: barColors,
                data: yValues
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Status der Geschenkkarten (Total CHF "+$('#totGiftCardSoldCHF').val()+")",
                    fontSize: 20,
                },
            }
        });






        new Chart(chart03, {
            type: "bar",
            data: {
                labels: ['Karte', 'Bar', 'Rechnung Zahlungsart', 'Onlinezahlung', 'Geschenkkarte'],
         
                datasets: [{
                    lineTension: 0.5,
                    backgroundColor: "rgba(255,175,0,0.85)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHitRadius: 1,
                    pointBorderWidth: 2,
                    data: [$('#totOrdersCardSasia').val(), $('#totOrdersCashSasia').val(), $('#totOrdersRechnungSasia').val(), $('#totOrdersOnlineSasia').val(), $('#totOrdersGCSasia').val()],
                }],
            },
            options: {
                title: {
                    display: true,
                    text: "Anzahl Belege nach Zahlungsart",
                    fontSize: 20,
                },
                maintainAspectRatio: true,
                layout: {
                    padding: { left: 10, right: 10, top: 10, bottom: 10}
                },
                scales: {
                    xAxes: [{
                        time: { unit: 'text' },
                        gridLines: { display: false, drawBorder: false },
                    }],
                    yAxes: [{
                        ticks: { maxTicksLimit: 12, padding: 10, callback: function(value, index, values) { return number_format(value);}},
                        gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                    }],
                },
                legend: {
                    display: false
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                }
            }
        });



        var chart04_label = [];
        var chart04_data = [];
        $('.dataChart4_label').each(function(i, obj) {
            chart04_label.push($(this).val());
        });

        $('.dataSalesChart4_data').each(function(i, obj) {
            chart04_data.push($(this).val());
        });
        new Chart(chart04, {
            type: "bar",
            data: {
                labels: chart04_label,
                datasets: [{
                    lineTension: 0.5,
                    backgroundColor: "rgba(0,128,0,1)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHitRadius: 1,
                    pointBorderWidth: 2,
                    data: chart04_data,
                }],
            },
            options: {
                title: {
                    display: true,
                    text: "Top 10 umsatzstärkste Tage",
                    fontSize: 20,
                },
                maintainAspectRatio: true,
                layout: {
                    padding: { left: 10, right: 10, top: 10, bottom: 10}
                },
                scales: {
                    xAxes: [{
                        time: { unit: 'text' },
                        gridLines: { display: false, drawBorder: false },
                    }],
                    yAxes: [{
                        ticks: { maxTicksLimit: 12, padding: 10, callback: function(value, index, values) { return 'CHF ' + number_format(value);}},
                        gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                    }],
                },
                legend: {
                    display: false
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = parseFloat(dataset.data[index]).toFixed(2);
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                }
            }
        });



        var chart05_label = [];
        var chart05_data = [];
        $('.dataChart5_label').each(function(i, obj) {
            chart05_label.push($(this).val());
        });

        $('.dataSalesChart5_data').each(function(i, obj) {
            chart05_data.push($(this).val());
        });
        new Chart(chart05, {
            type: "bar",
            data: {
                labels: chart05_label,
         
                datasets: [{
                    lineTension: 0.5,
                    backgroundColor: "rgba(254,0,0,1)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHitRadius: 1,
                    pointBorderWidth: 2,
                    data: chart05_data,
                }],
            },
            options: {
                title: {
                    display: true,
                    text: "Top 10 umsatzschwächste Tage",
                    fontSize: 20,
                },
                maintainAspectRatio: true,
                layout: {
                    padding: { left: 10, right: 10, top: 10, bottom: 10}
                },
                scales: {
                    xAxes: [{
                        time: { unit: 'text' },
                        gridLines: { display: false, drawBorder: false },
                    }],
                    yAxes: [{
                        ticks: { maxTicksLimit: 12, padding: 10, callback: function(value, index, values) { return 'CHF ' + number_format(value);}},
                        gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                    }],
                },
                legend: {
                    display: false
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = parseFloat(dataset.data[index]).toFixed(2);
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                }
            }
        });



        var chart06_label = [];
        var chart06_data = [];
        $('.chart06_label').each(function(i, obj) {
            chart06_label.push($(this).val());
        });

        $('.chart06_data').each(function(i, obj) {
            chart06_data.push($(this).val());
        });

        new Chart(chart06, {
            type: "bar",
            data: {
                labels: chart06_label,
         
                datasets: [{
                    lineTension: 0.5,
                    backgroundColor: "rgba(255,175,0,0.85)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHitRadius: 1,
                    pointBorderWidth: 2,
                    data: chart06_data,
                }],
            },
            options: {
                title: {
                    display: true,
                    text: "Umsatz pro Mitarbeiter",
                    fontSize: 20,
                },
                maintainAspectRatio: true,
                layout: {
                    padding: { left: 10, right: 10, top: 10, bottom: 10}
                },
                scales: {
                    xAxes: [{
                        time: { unit: 'text' },
                        gridLines: { display: false, drawBorder: false },
                    }],
                    yAxes: [{
                        ticks: { maxTicksLimit: 12, padding: 10, callback: function(value, index, values) { return 'CHF ' + number_format(value);}},
                        gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                    }],
                },
                legend: {
                    display: false
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                }
            }
        });









        var chart07_label = [];
        var chart07_data = [];
        $('.chart07_label').each(function(i, obj) {
            chart07_label.push($(this).val());
        });

        $('.chart07_data').each(function(i, obj) {
            chart07_data.push($(this).val());
        });

        new Chart(chart07, {
            type: "bar",
            data: {
                labels: chart07_label,
         
                datasets: [{
                    lineTension: 0.5,
                    backgroundColor: "rgba(255,175,0,0.85)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHitRadius: 1,
                    pointBorderWidth: 2,
                    data: chart07_data,
                }],
            },
            options: {
                title: {
                    display: true,
                    text: "Vergebene Rabatte pro Mitarbeiter",
                    fontSize: 20,
                },
                maintainAspectRatio: true,
                layout: {
                    padding: { left: 10, right: 10, top: 10, bottom: 10}
                },
                scales: {
                    xAxes: [{
                        time: { unit: 'text' },
                        gridLines: { display: false, drawBorder: false },
                    }],
                    yAxes: [{
                        ticks: { maxTicksLimit: 12, padding: 10, callback: function(value, index, values) { return 'CHF ' + number_format(value);}},
                        gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                    }],
                },
                legend: {
                    display: false
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                        ctx = chartInstance.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                }
            }
        });




        var xValues2 = ["Umsatz nach Rabatt ("+parseFloat($('#totOrdersSalesChf').val()).toFixed(2)+" CHF)","Rabatt ("+parseFloat($('#totOrdersDiscountCHF').val()).toFixed(2)+" CHF)"];
        var yValues2 = [parseFloat($('#totOrdersSalesChf').val()).toFixed(2),parseFloat($('#totOrdersDiscountCHF').val()).toFixed(2)];
        var barColors2 = ["#f46920", "#ffaf00",];
        new Chart(chart08, {
            type: "pie",
            data: {
                labels: xValues2,
                datasets: [{
                backgroundColor: barColors2,
                data: yValues2
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Anteil des Rabatts am Gesamtumsatz",
                    fontSize: 20,
                }
            }
        });




      

       




        
      


        
   </script>
</html>



