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
        
        .invoice-box table tr.details td {
            padding-bottom: 0px;
        }
        
        .invoice-box table tr.items td{
            border-bottom: 1px solid #eee;
        }
        
        .invoice-box table tr.items.last td {
            border-bottom: none;
        }
        
    /* .invoice-box table tr.total td:nth-child(4) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }*/
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
      
    </style>
</head>

<?php

use App\giftCard;
use Carbon\Carbon;
use App\OrdersPassive;
use App\billsExpensesRecordRes;

    use App\DeliveryProd;
    use App\Orders;
    use Illuminate\Support\Facades\Auth;
    use App\emailReceiptFromAdm;
    use App\Restorant;
    use App\Produktet;
    use App\Takeaway;
    use App\User;
    use App\pdfResProdCats;

    $date = explode('--77--',$teDh)[0];

    $theRes = Restorant::findOrFail(explode('--77--',$teDh)[4]);
    $dateStart = '';
    $dateEnd = '';
    if( explode('--77--',$teDh)[3] == 'type1'){
        $dateDay = explode(' ',$date)[0];
        $resClock = explode('->',$theRes->reportTimeArc);

        $dateDay2D = explode('-',$dateDay);
        $resClock1_2D = explode(':',$resClock[0]);
        $resClock2_2D = explode(':',$resClock[1]);
        $dateStart = Carbon::create($dateDay2D[0], $dateDay2D[1], $dateDay2D[2], $resClock1_2D[0], $resClock1_2D[1], 00);
        $dateEnd = Carbon::create($dateDay2D[0], $dateDay2D[1], $dateDay2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
        if($theRes->reportTimeOtherDay == 1){
            // diff day
            $dateEnd->addDays(1); 
        }

        $ordersRes = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','!=','500'],['nrTable','!=','9000']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $ordersTakeaway = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','500']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $ordersDelivery = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','9000']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        
        $ordersforWa = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $gcSalesCash = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Cash'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesCard = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Card'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesOnline = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Online'],['onlinePayStat','1'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesRechnung = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Rechnung'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $dateStartForBills = Carbon::create($dateDay2D[0], $dateDay2D[1], $dateDay2D[2], 00, 00, 00);
        $dailyExpIns = billsExpensesRecordRes::where([['forRes',explode('--77--',$teDh)[4]],['forDate', $dateStartForBills]])->get();

    }else if(explode('--77--',$teDh)[3] == 'type2'){
        $date2D = explode('-5-',$date);
        $date1Day = explode(' ',$date2D[0])[0];
        $date2Day = explode(' ',$date2D[1])[0];
        $resClock = explode('->',$theRes->reportTimeArc);

        $date1Day2D = explode('-',$date1Day);
        $date2Day2D = explode('-',$date2Day);
        $resClock1_2D = explode(':',$resClock[0]);
        $resClock2_2D = explode(':',$resClock[1]);
        $dateStart = Carbon::create($date1Day2D[0], $date1Day2D[1], $date1Day2D[2], $resClock1_2D[0], $resClock1_2D[1], 00);
        $dateEnd = Carbon::create($date2Day2D[0], $date2Day2D[1], $date2Day2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
        if($theRes->reportTimeOtherDay == 1){
            // diff day
            $dateEnd->addDays(1); 
        }

        $ordersRes = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','!=','500'],['nrTable','!=','9000']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $ordersTakeaway = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','500']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $ordersDelivery = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','9000']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $ordersforWa = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $gcSalesCash = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Cash'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesCard = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Card'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesOnline = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Online'],['onlinePayStat','1'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesRechnung = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Rechnung'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $dateStartForBills = Carbon::create($date1Day2D[0], $date1Day2D[1], $date1Day2D[2], 00, 00, 00);
        $dateEndForBills = Carbon::create($date2Day2D[0], $date2Day2D[1], $date2Day2D[2], 00, 00, 00);

        $dailyExpIns = billsExpensesRecordRes::where('forRes',explode('--77--',$teDh)[4])->whereBetween('forDate', [$dateStartForBills, $dateEndForBills])->get();

    }else if( explode('--77--',$teDh)[3] == 'type3'){
        $date2D = explode('-',$date);
        $dateBase = Carbon::create($date2D[1], $date2D[0], 01, 01, 00, 00);
        $dateMonthEnd = $dateBase->endOfMonth();
        $dateMonthEnd = explode(' ',$dateMonthEnd)[0];
        $dateMonthEnd2D = explode('-',$dateMonthEnd);

        $resClock = explode('->',$theRes->reportTimeArc);
        $resClock1_2D = explode(':',$resClock[0]);
        $resClock2_2D = explode(':',$resClock[1]);
        $dateStart = Carbon::create($date2D[1], $date2D[0], 01, $resClock1_2D[0], $resClock1_2D[1], 00);
        $dateEnd = Carbon::create($dateMonthEnd2D[0], $dateMonthEnd2D[1], $dateMonthEnd2D[2], $resClock2_2D[0], $resClock2_2D[1], 59);
        if($theRes->reportTimeOtherDay == 1){
            // diff day
            $dateEnd->addDays(1); 
        }

        $ordersRes = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','!=','500'],['nrTable','!=','9000']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $ordersTakeaway = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','500']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $ordersDelivery = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','9000']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $ordersforWa = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $gcSalesCash = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Cash'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesCard = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Card'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesOnline = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Online'],['onlinePayStat','1'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesRechnung = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Rechnung'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $dateStartForBills = Carbon::create($date2D[1], $date2D[0], 01, 00, 00, 00);
        $dateEndForBills = Carbon::create($dateMonthEnd2D[0], $dateMonthEnd2D[1], $dateMonthEnd2D[2], 00, 00, 00);

        $dailyExpIns = billsExpensesRecordRes::where('forRes',explode('--77--',$teDh)[4])->whereBetween('forDate', [$dateStartForBills, $dateEndForBills])->get();

    }else  if(explode('--77--',$teDh)[3] == 'type4'){
        $resClock = explode('->',$theRes->reportTimeArc);
        $resClock1_2D = explode(':',$resClock[0]);
        $resClock2_2D = explode(':',$resClock[1]);
        $dateStart = Carbon::create($date, 01, 01, $resClock1_2D[0], $resClock1_2D[1], 00);
        $dateEnd = Carbon::create($date, 12, 31, $resClock2_2D[0], $resClock2_2D[1], 59);
        if($theRes->reportTimeOtherDay == 1){
            // diff day
            $dateEnd->addDays(1); 
        }

        $ordersRes = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','!=','500'],['nrTable','!=','9000']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $ordersTakeaway = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','500']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $ordersDelivery = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2'],['nrTable','9000']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $ordersforWa = OrdersPassive::where([['Restaurant',explode('--77--',$teDh)[4]],['statusi','!=','2']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();

        $gcSalesCash = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Cash'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesCard = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Card'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesOnline = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Online'],['onlinePayStat','1'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        $gcSalesRechnung = giftCard::where([['toRes',explode('--77--',$teDh)[4]],['payM','Rechnung'],['oldGCtransfer','0']])->whereBetween('created_at', [$dateStart, $dateEnd])->get();
        
        $dateStartForBills = Carbon::create($date, 01, 01, 00, 00, 00);
        $dateEndForBills = Carbon::create($date, 12, 31, 00, 00, 00);

        $dailyExpIns = billsExpensesRecordRes::where('forRes',explode('--77--',$teDh)[4])->whereBetween('forDate', [$dateStartForBills, $dateEndForBills])->get();
    }


    
    $workersIDWithOr = array();
    $workersIDWithOrShowNormal = array();
    $nextPage = 1;

   
    $adr2D =  explode(',',$theRes->adresa);
    $adr1 =  $adr2D[0];
    $adr2 = '---' ;
    if(isset($adr2D[1])){
        $adr2 = $adr2D[1] ;
    }
    if(isset($adr2D[2])){
        $adr2 = $adr2D[1].','.$adr2D[2] ;
    }

    if($theRes->resTvsh == 0){
        $hiTvsh = number_format( 0 , 9, '.', '');
        $loTvsh = number_format( 0 , 9, '.', '');

        if(explode('--77--',$teDh)[3] == 'type1'){
            $dt2d = explode('-',$date);
            $theYr = $dt2d[0];
            // get the number of total pages
            $pdfname = 'storage/pdfDayReport/'.$theRes->emri.'_'.$date.'.pdf';
            $pdftext = file_get_contents($pdfname);
            $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);

        }else if( explode('--77--',$teDh)[3] == 'type2'){
            $dt2d = explode('-5-',$date);
            $theYr = $dt2d[0];
            // get the number of total pages
            $pdfname = 'storage/pdfWeekReport/'.$theRes->emri.'_w'.$dt2d[2].'of'.$dt2d[3].'.pdf';
            $pdftext = file_get_contents($pdfname);
            $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);

        }else if(explode('--77--',$teDh)[3] == 'type3'){
            $dt2d = explode('-',$date);
            $theYr = $dt2d[1];
            // get the number of total pages
            $pdfname = 'storage/pdfMonthReport/'.$theRes->emri.'_'.$date.'.pdf';
            $pdftext = file_get_contents($pdfname);
            $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);

        }else if(explode('--77--',$teDh)[3] == 'type4'){
            $dt2d = explode('-',$date);
            $theYr = $date;
            // get the number of total pages
            $pdfname = 'storage/pdfYearReport/'.$theRes->emri.'_'.$date.'.pdf';
            $pdftext = file_get_contents($pdfname);
            $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        }

    }else{
        if(explode('--77--',$teDh)[3] == 'type1'){
            $dt2d = explode('-',$date);
            // get the number of total pages
            $pdfname = 'storage/pdfDayReport/'.$theRes->emri.'_'.$date.'.pdf';
            $pdftext = file_get_contents($pdfname);
            $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);

            if($dt2d[0] <= 2023){
                $hiTvsh = number_format( 0.071494893 , 9, '.', '');
                $loTvsh = number_format( 0.024390243 , 9, '.', '');
            }else{
                $hiTvsh = number_format( 0.074930619 , 9, '.', '');
                $loTvsh = number_format( 0.025341130 , 9, '.', '');
            }
            $theYr = $dt2d[0];

        }else if(explode('--77--',$teDh)[3] == 'type2'){
            $dt2d = explode('-5-',$date);
            // get the number of total pages
            $pdfname = 'storage/pdfWeekReport/'.$theRes->emri.'_w'.$dt2d[2].'of'.$dt2d[3].'.pdf';
            $pdftext = file_get_contents($pdfname);
            $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);

            if($dt2d[0] <= 2023){
                $hiTvsh = number_format( 0.071494893 , 9, '.', '');
                $loTvsh = number_format( 0.024390243 , 9, '.', '');
            }else{
                $hiTvsh = number_format( 0.074930619 , 9, '.', '');
                $loTvsh = number_format( 0.025341130 , 9, '.', '');
            }
            $theYr = $dt2d[0];

        }else if(explode('--77--',$teDh)[3] == 'type3'){
            $dt2d = explode('-',$date);
            // get the number of total pages
            $pdfname = 'storage/pdfMonthReport/'.$theRes->emri.'_'.$date.'.pdf';
            $pdftext = file_get_contents($pdfname);
            $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);

            if($dt2d[1] <= 2023){
                $hiTvsh = number_format( 0.071494893 , 9, '.', '');
                $loTvsh = number_format( 0.024390243 , 9, '.', '');
            }else{
                $hiTvsh = number_format( 0.074930619 , 9, '.', '');
                $loTvsh = number_format( 0.025341130 , 9, '.', '');
            }
            $theYr = $dt2d[1];
            
        }else if(explode('--77--',$teDh)[3] == 'type4'){
            $dt2d = explode('-',$date);
            // get the number of total pages
            $pdfname = 'storage/pdfYearReport/'.$theRes->emri.'_'.$date.'.pdf';
            $pdftext = file_get_contents($pdfname);
            $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);
            
            if($date <= 2023){
                $hiTvsh = number_format( 0.071494893 , 9, '.', '');
                $loTvsh = number_format( 0.024390243 , 9, '.', '');
            }else{
                $hiTvsh = number_format( 0.074930619 , 9, '.', '');
                $loTvsh = number_format( 0.025341130 , 9, '.', '');
            }
            $theYr = $date;
        }
    }

   
    $dateOfCr = date('d').'.'.date('m').'.'.date('Y').' / '.date('H').':'.date('i');

    $gcPayOrdersRes = array();
    $gcPayOrdersTa = array();
?>

@foreach ($ordersforWa as $orOne)
    @if (!in_array($orOne->servedBy,$workersIDWithOr))
        <?php array_push($workersIDWithOr,$orOne->servedBy); ?>
    @endif
@endforeach

                    

<body id="pageCounter" >
  
    <footer id="footerPC">
        <p style="width: 20%; display: inline; "> <span  class="pageNumbers"></span>{{$pages}}</p>
        <p style="width: 60%; display: inline; margin-right:2.75cm; margin-left:2.75cm;">Kontaktlos bestellen & bezahlen mit QRorpa Systeme</p>
        <img width="100px" style="display: inline;" src="storage/images/logo_QRorpa.png" class="logo" />
    </footer>

    <main >
        <div class="invoice-box" >
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                    <td colspan="6"> 
                        <table style=" margin-bottom:0.1cm;">
                            <tr>
                                <td>
                                    <p style="margin: 0px; padding:0px; margin-top:-10px;"><strong>{{$theRes->emri}}</strong></p>
                                    <p style="margin: 0px; padding:0px; margin-top:-10px;">{{$adr1}}</p>
                                    @if($adr2 != '---')
                                    <p style="margin: 0px; padding:0px; margin-top:-10px;">{{$adr2}}</p>
                                    @endif
                                    <p style="margin:0px; padding:0px; margin-top:-10px;">Tel. {{ $theRes->resPhoneNr }}</p>
                                    @if (str_contains($theRes->chemwstForRes, 'CHE'))
                                        <p style="margin:0px; padding:0px; margin-top:-8px;">MwST-Nr. {{ $theRes->chemwstForRes }}</p>
                                    @else
                                        <p style="margin:0px; padding:0px; margin-top:-8px;">{{ $theRes->chemwstForRes }}</p>
                                    @endif
                                </td>
                                <td >
                                    <p style="margin:0px; padding:0px; margin-top:-10px;"><strong>Bericht ID#: {{str_pad(explode('--77--',$teDh)[2], 8, '0', STR_PAD_LEFT)}}</strong></p>
                                    <p style="margin:0px; padding:0px; margin-top:-8px;"><strong>Bericht#: {{explode('--77--',$teDh)[1]}}</strong></p>
                                    <p style="margin:0px; padding:0px; margin-top:-8px;">Datum/Zeit: {{$dateOfCr}}</p>
                                </td> 
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    @if( explode('--77--',$teDh)[3] == 'type1'){
                        <td colspan="6"><strong>Tagesbericht: {{$dt2d[2]}}.{{$dt2d[1]}}.{{$dt2d[0]}}</strong></td>
                    @elseif (explode('--77--',$teDh)[3] == 'type2')
                        <?php
                            $dtStart = explode('-',$dt2d[0]);
                            $dtEnd = explode('-',$dt2d[1]);
                        ?>
                        <td colspan="6"><strong>Wöchentlicher Report: Woche {{$dt2d[2]}} des Jahres {{$dt2d[3]}} ({{$dtStart[2]}}.{{$dtStart[1]}}.{{$dtStart[0]}} -> {{$dtEnd[2]}}.{{$dtEnd[1]}}.{{$dtEnd[0]}})</strong></td>
                    @elseif (explode('--77--',$teDh)[3] == 'type3')
                        <td colspan="6"><strong>Monatlicher Bericht: {{$dt2d[0]}}.{{$dt2d[1]}}</strong></td>
                    @elseif (explode('--77--',$teDh)[3] == 'type4')
                        <td colspan="6"><strong>Jahresbericht: {{$date}}</strong></td>
                    @endif
                </tr>

             
































                <?php
                    $totResBr = number_format(0, 9, '.', '');
                    $totResMwst = number_format(0, 9, '.', '');
                    $totResNe = number_format(0, 9, '.', '');
                    $totResSasia = number_format(0, 0, '.', '');
                ?>

                <?php $showTable = False; ?>
                @foreach ($ordersRes as $orOne)
                    @if ($orOne->payM == "Barzahlungen" || $orOne->payM == "Cash")
                        @if($orOne->shuma != $orOne->dicsountGcAmnt)
                            <?php $showTable = True; ?>
                            @break
                        @else if($orOne->shuma == $orOne->dicsountGcAmnt)
                            @if (!in_array($orOne->id,$gcPayOrdersRes))
                                <?php array_push($gcPayOrdersRes,$orOne->id); ?>
                            @endif
                        @endif
                    @endif
                @endforeach

                @if ($showTable)
                    <tr> <td colspan="6"><strong>Restaurant (Bar)</strong></td> </tr>

                    <tr class="heading" style="margin-top: 0.25cm;">
                        <td style="text-align: left;"><strong>Stk.</strong></td>
                        <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                        <td style="text-align: right;"><strong>Brutto</strong></td>
                        @if($theRes->resTvsh == 0)
                            <td style="text-align: right;"><strong>MWST 0%</strong></td>
                        @else
                            @if($theYr <= 2023)
                            <td style="text-align: right;"><strong>MWST 7.70%</strong></td>
                            @else
                            <td style="text-align: right;"><strong>MWST 8.10%</strong></td>
                            @endif
                        @endif
                        <td style="text-align: right;"><strong>Netto</strong></td>
                        <td style="text-align: right;"><strong>%-Anteil </strong></td>
                    </tr>
                    
                    <?php
                        $prodGroups = array();
                        $grSasia = array();
                        $grBruto = array();
                        $grMwst = array();
                        $grNeto = array();
                        $grAnteil = array();
                        $totInCHF = number_format(0, 9, '.', '');
                        $totDiscount = number_format(0, 9, '.', '');
                        $totDiscountGC = number_format(0, 9, '.', '');
                    ?>
                    <!-- Grupimi i produkteve -->
                    @foreach ($ordersRes as $orOne)
                        @if ($orOne->payM == "Barzahlungen" || $orOne->payM == "Cash")
                            @if($orOne->shuma == $orOne->dicsountGcAmnt)
                                @if (!in_array($orOne->id,$gcPayOrdersRes))
                                    <?php array_push($gcPayOrdersRes,$orOne->id); ?>
                                @endif
                                
                            @else
                                <!-- loop through products array -->
                                @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                    <?php 
                                        if($porOne != NULL){
                                            $porOne2D = explode('-8-',$porOne); 
                                            $totOfThOr = number_format((float)$porOne2D[4], 9, '.', '');
                                            $theP = Produktet::find($porOne2D[7]);
                                            
                                                $mwstPRC = number_format($hiTvsh, 9, '.', '');
                                                $mwst = number_format(($totOfThOr* $mwstPRC), 9, '.', '');
                                                $bruto = number_format($totOfThOr, 9, '.', '');
                                                $neto = number_format(($totOfThOr-$mwst), 9, '.', '');

                                                if(isset($porOne2D[8])){
                                                    $repGr = $porOne2D[8];
                                                }else{
                                                    if($theP == NULL){$repGr = 0;}else{ $repGr = $theP->toReportCat; }
                                                }

                                                if(in_array($repGr,$prodGroups)){
                                                    // gr already registred
                                                    $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                                    $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                    $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                    $grNeto[$repGr] += number_format($neto, 9, '.', '');
                                                }else{
                                                    // new gr
                                                    array_push($prodGroups,$repGr);
                                                    $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                                    $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                    $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                    $grNeto[$repGr] = number_format($neto, 9, '.', '');
                                                }
                                                $totInCHF += number_format($totOfThOr, 9, '.', '');
                                            
                                        }
                                    ?>
                                @endforeach
                                <?php
                                    if($orOne->inCashDiscount > 0){ $totDiscount += number_format($orOne->inCashDiscount, 9, '.', '');
                                    }else if($orOne->inPercentageDiscount > 0){ $totDiscount += number_format(($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01), 9, '.', '');}
                                    
                                    if($orOne->cuponOffVal > 0){
                                        $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                    }
                                    $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                ?>
                            @endif
                        @endif
                    @endforeach

                    <!-- llogariten perqindjet ANTEIL per grupacione -->
                    @foreach ($prodGroups as $key) 
                        <?php
                            $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                            if($totInCHF > 0){
                                $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                $grAnteil[$key] = $percVal;
                            }else{
                                $grAnteil[$key] = number_format(100, 9, '.', '');
                            }
                        ?>
                    @endforeach

                    <!-- Shfaq produktet e grupuara -->
                    <?php
                        $sumSasia = number_format(0, 9, '.', '');
                        $sumBruto = number_format(0, 9, '.', '');
                        $sumMwst = number_format(0, 9, '.', '');
                        $sumNeto = number_format(0, 9, '.', '');
                    ?>
                    @foreach ($prodGroups as $key) 
                        <tr>
                            <?php
                                $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                $sumBruto += number_format($grBruto[$key], 9, '.', '');
                                $sumMwst += number_format($grMwst[$key], 9, '.', '');
                                $sumNeto += number_format($grNeto[$key], 9, '.', '');
                            ?>
                            <td style="text-align: left;">
                                {{number_format($grSasia[$key], 0, '.', '')}} X
                            </td>
                            <td style="text-align: left;">
                                @if ($key == 0)
                                    nicht kategorisiert
                                @else
                                    {{pdfResProdCats::findOrFail($key)->catTitle}}
                                @endif
                            </td>
                            <td style="text-align: right;">
                            CHF {{number_format($grBruto[$key], 2, '.', '')}}
                            </td>
                            <td style="text-align: right;">
                            CHF {{number_format($grMwst[$key], 2, '.', '')}}
                            </td>
                            <td style="text-align: right;">
                            CHF {{number_format($grNeto[$key], 2, '.', '')}}
                            </td>
                            <td style="text-align: right;">
                                {{number_format($grAnteil[$key], 2, '.', '')}} %
                            </td>
                            
                        </tr>
                    @endforeach
                    <?php
                        $totResSasia += number_format($sumSasia, 0, '.', '');
                    ?>
                        <tr class="heading">
                            <td style="text-align: left;">
                                <strong>{{number_format($sumSasia, 0, '.', '')}} X</strong>
                            </td>
                            
                            <td style="text-align: left;">
                                <strong></strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>100 % </strong>
                            </td>
                        </tr>
                    @if ( $totDiscount > 0 )
                        <tr>
                            <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                        </tr>
                    @endif
                    @if ( $totDiscountGC > 0 )
                        <tr>
                            <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                        </tr>
                    @endif
                    @if ( $totDiscount > 0)
                        <?php
                            $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                            $mwstAfterD = number_format(($sumBruto - $totDiscount)*$hiTvsh, 9, '.', '');
                            $netoAfterD = number_format($brutoAfterD - $mwstAfterD, 9, '.', '');

                            $totResBr += number_format($brutoAfterD, 9, '.', '');
                            $totResMwst += number_format($mwstAfterD, 9, '.', '');
                            $totResNe += number_format($netoAfterD, 9, '.', '');
                        ?>
                        <tr class="heading">
                            <td colspan="2" style="text-align: left;">
                                <strong> Nach Abzug von Rabatten </strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>CHF {{number_format($mwstAfterD, 2, '.', '')}}</strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>100 % </strong>
                            </td>
                        </tr>
                       
                    @else
                        <?php
                            $totResBr += number_format($sumBruto, 9, '.', '');
                            $totResMwst += number_format($sumMwst, 9, '.', '');
                            $totResNe += number_format($sumNeto, 9, '.', '');
                        ?>
                    @endif
                @endif



















                <?php $showTable = False; ?>
                @foreach ($ordersRes as $orOne)
                    @if ($orOne->payM == "Kartenzahlung")
                        @if($orOne->shuma != $orOne->dicsountGcAmnt)
                            <?php $showTable = True; ?>
                            @break
                        @else if($orOne->shuma == $orOne->dicsountGcAmnt)
                            @if (!in_array($orOne->id,$gcPayOrdersRes))
                                <?php array_push($gcPayOrdersRes,$orOne->id); ?>
                            @endif
                        @endif
                    @endif
                @endforeach

                @if ($showTable)
                            <tr> <td colspan="6" style="color:white">.</td></tr>
                            <tr><td colspan="6"><strong>Restaurant (Karte)</strong></td></tr>

                            <tr class="heading" style="margin-top: 0.25cm;">
                                <td style="text-align: left;"><strong>Stk.</strong></td>
                                <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                                <td style="text-align: right;"><strong>Brutto</strong></td>
                                @if($theRes->resTvsh == 0)
                                    <td style="text-align: right;"><strong>MWST 0%</strong></td>
                                @else
                                    @if($theYr <= 2023)
                                    <td style="text-align: right;"><strong>MWST 7.70%</strong></td>
                                    @else
                                    <td style="text-align: right;"><strong>MWST 8.10%</strong></td>
                                    @endif
                                @endif
                                <td style="text-align: right;"><strong>Netto</strong></td>
                                <td style="text-align: right;"><strong>%-Anteil </strong></td>
                            </tr>
                            
                            <?php
                                $prodGroups = array();
                                $grSasia = array();
                                $grBruto = array();
                                $grMwst = array();
                                $grNeto = array();
                                $grAnteil = array();
                                $totInCHF = number_format(0, 9, '.', '');
                                $totDiscount = number_format(0, 9, '.', '');
                                $totDiscountGC = number_format(0, 9, '.', '');
                            ?>
                            <!-- Grupimi i produkteve -->
                            @foreach ($ordersRes as $orOne)
                                @if ($orOne->payM == "Kartenzahlung")

                                    @if($orOne->shuma == $orOne->dicsountGcAmnt)
                                        @if (!in_array($orOne->id,$gcPayOrdersRes))
                                            <?php array_push($gcPayOrdersRes,$orOne->id); ?>
                                        @endif

                                    @else
                                        <!-- loop through products array -->
                                        @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                            <?php 
                                                $porOne2D = explode('-8-',$porOne); 
                                                $theP = Produktet::find($porOne2D[7]);
                                            
                                                    $mwstPRC = number_format($hiTvsh, 9, '.', '');
                                                    $mwst = number_format((float)$porOne2D[4]*$mwstPRC, 9, '.', '');
                                                    $bruto = number_format((float)$porOne2D[4], 9, '.', '');
                                                    $neto = number_format((float)$porOne2D[4]-$mwst, 9, '.', '');

                                                    if(isset($porOne2D[8])){
                                                        $repGr = $porOne2D[8];
                                                    }else{
                                                        if($theP == NULL){$repGr = 0;}else{ $repGr = $theP->toReportCat; }
                                                    }
                                                
                                                    if(in_array($repGr,$prodGroups)){
                                                        // gr already registred
                                                        $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');
                                                    }else{
                                                        // new gr
                                                        array_push($prodGroups,$repGr);
                                                        $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');
                                                    }
                                                    $addToTot = number_format((float)$porOne2D[4], 9, '.', '');
                                                    $totInCHF += number_format($addToTot, 9, '.', '');
                                                
                                            ?>
                                        @endforeach
                                        <?php
                                            if($orOne->inCashDiscount > 0){$totDiscount = number_format($totDiscount + $orOne->inCashDiscount, 9, '.', '');
                                            }else if($orOne->inPercentageDiscount > 0){$totDiscount = number_format($totDiscount + (($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01)), 9, '.', '');}
                                        
                                            if($orOne->cuponOffVal > 0){
                                                $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                            }
                                            $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                        ?>
                                    @endif
                                @endif
                            @endforeach

                            <!-- llogariten perqindjet ANTEIL per grupacione -->
                            @foreach ($prodGroups as $key) 
                                <?php
                                    $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                    if($totInCHF > 0){
                                        $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                        $grAnteil[$key] = $percVal;
                                    }else{
                                        $grAnteil[$key] = number_format(100, 9, '.', '');
                                    }
                                ?>
                            @endforeach


                            <!-- Shfaq produktet e grupuara -->
                            <?php
                                $sumSasia = number_format(0, 9, '.', '');
                                $sumBruto = number_format(0, 9, '.', '');
                                $sumMwst = number_format(0, 9, '.', '');
                                $sumNeto = number_format(0, 9, '.', '');
                            ?>
                            @foreach ($prodGroups as $key) 
                                <tr>
                                    <td style="text-align: left;">
                                        {{number_format($grSasia[$key], 0, '.', '')}} X
                                    </td>
                                    <td style="text-align: left;">
                                    @if ($key == 0)
                                        nicht kategorisiert
                                    @else
                                        {{pdfResProdCats::findOrFail($key)->catTitle}}
                                    @endif
                                    </td>
                                    <td style="text-align: right;">
                                        CHF {{number_format($grBruto[$key], 2, '.', '')}}
                                    </td>
                                    <td style="text-align: right;">
                                        CHF {{number_format($grMwst[$key], 2, '.', '')}}
                                    </td>
                                    <td style="text-align: right;">
                                        CHF {{number_format($grNeto[$key], 2, '.', '')}}
                                    </td>
                                    <td style="text-align: right;">
                                        {{number_format($grAnteil[$key], 2, '.', '')}} %
                                    </td>
                                    <?php
                                        $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                        $sumBruto += number_format($grBruto[$key], 9, '.', '');
                                        $sumMwst += number_format($grMwst[$key], 9, '.', '');
                                        $sumNeto += number_format($grNeto[$key], 9, '.', '');
                                    ?>
                                </tr>
                            @endforeach
                            <?php $totResSasia += number_format($sumSasia, 0, '.', ''); ?>
                                <tr class="heading">
                                    <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                    <td style="text-align: left;"><strong></strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>100 % </strong></td>
                                </tr>
                            @if ( $totDiscount > 0 )
                                <tr>
                                    <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                                </tr>
                            @endif
                            @if ( $totDiscountGC > 0 )
                                <tr>
                                    <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                                </tr>
                            @endif
                            @if ( $totDiscount > 0)
                                <?php
                                    $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                    $mwstAfterD = number_format(($sumBruto - $totDiscount)*$hiTvsh, 9, '.', '');
                                    $netoAfterD = number_format($brutoAfterD - $mwstAfterD, 9, '.', '');

                                    $totResBr += number_format($brutoAfterD, 9, '.', '');
                                    $totResMwst += number_format($mwstAfterD, 9, '.', '');
                                    $totResNe += number_format($netoAfterD, 9, '.', '');
                                ?>
                                <tr class="heading">
                                    <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($mwstAfterD, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>100 % </strong></td>
                                </tr>
                            @else
                                <?php
                                    $totResBr += number_format($sumBruto, 9, '.', '');
                                    $totResMwst += number_format($sumMwst, 9, '.', '');
                                    $totResNe += number_format($sumNeto, 9, '.', '');
                                ?>
                            @endif
                @endif



















                <?php $showTable = False; ?>
                @foreach ($ordersRes as $orOne)
                    @if ($orOne->payM == "Onlinezahlung" || $orOne->payM == "Online")
                        @if($orOne->shuma != $orOne->dicsountGcAmnt)
                            <?php $showTable = True; ?>
                            @break
                        @else if($orOne->shuma == $orOne->dicsountGcAmnt)
                            @if (!in_array($orOne->id,$gcPayOrdersRes))
                                <?php array_push($gcPayOrdersRes,$orOne->id); ?>
                            @endif
                        @endif
                    @endif
                @endforeach

                @if ($showTable)
                            <tr> <td colspan="6" style="color:white">.</td></tr>
                            <tr><td colspan="6"><strong>Restaurant (Online)</strong></td></tr>

                            <tr class="heading" style="margin-top: 0.25cm;">
                                <td style="text-align: left;"><strong>Stk.</strong></td>
                                <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                                <td style="text-align: right;"><strong>Brutto</strong></td>
                                @if($theRes->resTvsh == 0)
                                    <td style="text-align: right;"><strong>MWST 0%</strong></td>
                                @else
                                    @if($theYr <= 2023)
                                    <td style="text-align: right;"><strong>MWST 7.70%</strong></td>
                                    @else
                                    <td style="text-align: right;"><strong>MWST 8.10%</strong></td>
                                    @endif
                                @endif
                                <td style="text-align: right;"><strong>Netto</strong></td>
                                <td style="text-align: right;"><strong>%-Anteil </strong></td>
                            </tr>
                            <?php
                                $prodGroups = array();
                                $grSasia = array();
                                $grBruto = array();
                                $grMwst = array();
                                $grNeto = array();
                                $grAnteil = array();
                                $totInCHF = number_format(0, 9, '.', '');
                                $totDiscount = number_format(0, 9, '.', '');
                                $totDiscountGC = number_format(0, 9, '.', '');
                            ?>
                            <!-- Grupimi i produkteve -->
                            @foreach ($ordersRes as $orOne)
                                @if ($orOne->payM == "Onlinezahlung" || $orOne->payM == "Online")

                                    @if($orOne->shuma == $orOne->dicsountGcAmnt)
                                        @if (!in_array($orOne->id,$gcPayOrdersRes))
                                            <?php array_push($gcPayOrdersRes,$orOne->id); ?>
                                        @endif
                                        
                                    @else
                                        <!-- loop through products array -->
                                        @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                            <?php 
                                                $porOne2D = explode('-8-',$porOne); 
                                                $theP = Produktet::find($porOne2D[7]);
                                            
                                                    $mwstPRC = number_format($hiTvsh, 9, '.', '');
                                                    $mwst = number_format((float)$porOne2D[4]*$mwstPRC, 9, '.', '');
                                                    $bruto = number_format((float)$porOne2D[4], 9, '.', '');
                                                    $neto = number_format((float)$porOne2D[4]-$mwst, 9, '.', '');

                                                    if(isset($porOne2D[8])){
                                                        $repGr = $porOne2D[8];
                                                    }else{
                                                        if($theP == NULL){$repGr = 0;}else{ $repGr = $theP->toReportCat; }
                                                    }
                                                
                                                    if(in_array($repGr,$prodGroups)){
                                                        // gr already registred
                                                        $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');
                                                    }else{
                                                        // new gr
                                                        array_push($prodGroups,$repGr);
                                                        $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');
                                                    }
                                                    $totInCHF += number_format((float)$porOne2D[4], 9, '.', '');
                                                
                                            ?>
                                        @endforeach
                                        <?php
                                            if($orOne->inCashDiscount > 0){$totDiscount = number_format($totDiscount + $orOne->inCashDiscount, 9, '.', '');
                                            }else if($orOne->inPercentageDiscount > 0){$totDiscount = number_format($totDiscount + (($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01)), 9, '.', '');}
                                        
                                            if($orOne->cuponOffVal > 0){
                                                $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                            }
                                            $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                        ?>
                                    @endif
                                @endif
                            @endforeach

                            <!-- llogariten perqindjet ANTEIL per grupacione -->
                            @foreach ($prodGroups as $key) 
                                <?php
                                    $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                    if($totInCHF > 0){
                                        $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                        $grAnteil[$key] = $percVal;
                                    }else{
                                        $grAnteil[$key] = number_format(100, 9, '.', '');
                                    }
                                ?>
                            @endforeach

                            <!-- Shfaq produktet e grupuara -->
                            <?php
                                $sumSasia = number_format(0, 9, '.', '');
                                $sumBruto = number_format(0, 9, '.', '');
                                $sumMwst = number_format(0, 9, '.', '');
                                $sumNeto = number_format(0, 9, '.', '');
                            ?>
                            @foreach ($prodGroups as $key) 
                                <tr>
                                    <td style="text-align: left;">
                                        {{number_format($grSasia[$key], 0, '.', '')}} X
                                    </td>
                                    <td style="text-align: left;">
                                    @if ($key == 0)
                                        nicht kategorisiert
                                    @else
                                        {{pdfResProdCats::findOrFail($key)->catTitle}}
                                    @endif
                                    </td>
                                    <td style="text-align: right;">
                                        CHF {{number_format($grBruto[$key], 2, '.', '')}}
                                    </td>
                                    <td style="text-align: right;">
                                        CHF {{number_format($grMwst[$key], 2, '.', '')}}
                                    </td>
                                    <td style="text-align: right;">
                                        CHF {{number_format($grNeto[$key], 2, '.', '')}}
                                    </td>
                                    <td style="text-align: right;">
                                        {{number_format($grAnteil[$key], 2, '.', '')}} %
                                    </td>
                                    <?php
                                        $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                        $sumBruto += number_format($grBruto[$key], 9, '.', '');
                                        $sumMwst += number_format($grMwst[$key], 9, '.', '');
                                        $sumNeto += number_format($grNeto[$key], 9, '.', '');
                                    ?>
                                </tr>
                            @endforeach
                            <?php $totResSasia += number_format($sumSasia, 0, '.', ''); ?>
                                <tr class="heading">
                                    <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                    <td style="text-align: left;"><strong></strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>100 % </strong></td>
                                </tr>
                            @if ( $totDiscount > 0 )
                                <tr>
                                    <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                                </tr>
                            @endif
                            @if ( $totDiscountGC > 0 )
                                <tr>
                                    <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                                </tr>
                            @endif
                            @if ( $totDiscount > 0)
                                <?php
                                    $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                    $mwstAfterD = number_format(($sumBruto - $totDiscount)*$hiTvsh, 9, '.', '');
                                    $netoAfterD = number_format($brutoAfterD - $mwstAfterD, 9, '.', '');

                                    $totResBr += number_format($brutoAfterD, 9, '.', '');
                                    $totResMwst += number_format($mwstAfterD, 9, '.', '');
                                    $totResNe += number_format($netoAfterD, 9, '.', '');
                                ?>
                                <tr class="heading">
                                    <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($mwstAfterD, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>100 % </strong></td>
                                </tr>
                            @else
                                <?php
                                    $totResBr += number_format($sumBruto, 9, '.', '');
                                    $totResMwst += number_format($sumMwst, 9, '.', '');
                                    $totResNe += number_format($sumNeto, 9, '.', '');
                                ?>
                            @endif
                @endif

















                <?php $showTable = False; ?>
                @foreach ($ordersRes as $orOne)
                    @if ($orOne->payM == "Rechnung")
                        @if($orOne->shuma != $orOne->dicsountGcAmnt)
                            <?php $showTable = True; ?>
                            @break
                        @else if($orOne->shuma == $orOne->dicsountGcAmnt)
                            @if (!in_array($orOne->id,$gcPayOrdersRes))
                                <?php array_push($gcPayOrdersRes,$orOne->id); ?>
                            @endif
                        @endif
                    @endif
                @endforeach

                @if ($showTable)
                            <tr> <td colspan="6" style="color:white">.</td></tr>
                            <tr><td colspan="6"><strong>Restaurant (Auf Rechnung)</strong></td></tr>

                            <tr class="heading" style="margin-top: 0.25cm;">
                                <td style="text-align: left;"><strong>Stk.</strong></td>
                                <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                                <td style="text-align: right;"><strong>Brutto</strong></td>
                                @if($theRes->resTvsh == 0)
                                    <td style="text-align: right;"><strong>MWST 0%</strong></td>
                                @else
                                    @if($theYr <= 2023)
                                    <td style="text-align: right;"><strong>MWST 7.70%</strong></td>
                                    @else
                                    <td style="text-align: right;"><strong>MWST 8.10%</strong></td>
                                    @endif
                                @endif
                                <td style="text-align: right;"><strong>Netto</strong></td>
                                <td style="text-align: right;"><strong>%-Anteil </strong></td>
                            </tr>
                            <?php
                                $prodGroups = array();
                                $grSasia = array();
                                $grBruto = array();
                                $grMwst = array();
                                $grNeto = array();
                                $grAnteil = array();
                                $totInCHF = number_format(0, 9, '.', '');
                                $totDiscount = number_format(0, 9, '.', '');
                                $totDiscountGC = number_format(0, 9, '.', '');
                            ?>
                            <!-- Grupimi i produkteve -->
                            @foreach ($ordersRes as $orOne)
                                @if ($orOne->payM == "Rechnung")

                                    @if($orOne->shuma == $orOne->dicsountGcAmnt)
                                        @if (!in_array($orOne->id,$gcPayOrdersRes))
                                            <?php array_push($gcPayOrdersRes,$orOne->id); ?>
                                        @endif
                                        
                                    @else
                                        <!-- loop through products array -->
                                        @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                            <?php 
                                                $porOne2D = explode('-8-',$porOne); 
                                                $theP = Produktet::find($porOne2D[7]);
                                            
                                                    $mwstPRC = number_format($hiTvsh, 9, '.', '');
                                                    $mwst = number_format((float)$porOne2D[4]*$mwstPRC, 9, '.', '');
                                                    $bruto = number_format((float)$porOne2D[4], 9, '.', '');
                                                    $neto = number_format((float)$porOne2D[4]-$mwst, 9, '.', '');

                                                    if(isset($porOne2D[8])){
                                                        $repGr = $porOne2D[8];
                                                    }else{
                                                        if($theP == NULL){$repGr = 0;}else{ $repGr = $theP->toReportCat; }
                                                    }

                                                    if(in_array($repGr,$prodGroups)){
                                                        // gr already registred
                                                        $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');
                                                    }else{
                                                        // new gr
                                                        array_push($prodGroups,$repGr);
                                                        $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');
                                                    }
                                                    $totInCHF += number_format((float)$porOne2D[4], 9, '.', '');
                                                
                                            ?>
                                        @endforeach
                                        <?php
                                            if($orOne->inCashDiscount > 0){$totDiscount = number_format($totDiscount + $orOne->inCashDiscount, 9, '.', '');
                                            }else if($orOne->inPercentageDiscount > 0){$totDiscount = number_format($totDiscount + (($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01)), 9, '.', '');}
                                        
                                            if($orOne->cuponOffVal > 0){
                                                $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                            }
                                            $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                        ?>
                                    @endif
                                @endif
                            @endforeach

                            <!-- llogariten perqindjet ANTEIL per grupacione -->
                            @foreach ($prodGroups as $key) 
                                <?php
                                    $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                    if($totInCHF > 0){
                                        $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                        $grAnteil[$key] = $percVal;
                                    }else{
                                        $grAnteil[$key] = number_format(100, 9, '.', '');
                                    }
                                ?>
                            @endforeach

                            <!-- Shfaq produktet e grupuara -->
                            <?php
                                $sumSasia = number_format(0, 9, '.', '');
                                $sumBruto = number_format(0, 9, '.', '');
                                $sumMwst = number_format(0, 9, '.', '');
                                $sumNeto = number_format(0, 9, '.', '');
                            ?>
                            @foreach ($prodGroups as $key) 
                                <tr>
                                    <td style="text-align: left;">
                                        {{number_format($grSasia[$key], 0, '.', '')}} X
                                    </td>
                                    <td style="text-align: left;">
                                    @if ($key == 0)
                                        nicht kategorisiert
                                    @else
                                        {{pdfResProdCats::findOrFail($key)->catTitle}}
                                    @endif
                                    </td>
                                    <td style="text-align: right;">
                                        CHF {{number_format($grBruto[$key], 2, '.', '')}}
                                    </td>
                                    <td style="text-align: right;">
                                        CHF {{number_format($grMwst[$key], 2, '.', '')}}
                                    </td>
                                    <td style="text-align: right;">
                                        CHF {{number_format($grNeto[$key], 2, '.', '')}}
                                    </td>
                                    <td style="text-align: right;">
                                        {{number_format($grAnteil[$key], 2, '.', '')}} %
                                    </td>
                                    <?php
                                        $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                        $sumBruto += number_format($grBruto[$key], 9, '.', '');
                                        $sumMwst += number_format($grMwst[$key], 9, '.', '');
                                        $sumNeto += number_format($grNeto[$key], 9, '.', '');
                                    ?>
                                </tr>
                            @endforeach
                            <?php $totResSasia += number_format($sumSasia, 0, '.', ''); ?>
                                <tr class="heading">
                                    <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                    <td style="text-align: left;"><strong></strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>100 % </strong></td>
                                </tr>
                            @if ( $totDiscount > 0 )
                                <tr>
                                    <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                                </tr>
                            @endif
                            @if ( $totDiscountGC > 0 )
                                <tr>
                                    <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                                </tr>
                            @endif
                            @if ( $totDiscount > 0)
                                <?php
                                    $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                    $mwstAfterD = number_format(($sumBruto - $totDiscount)*$hiTvsh, 9, '.', '');
                                    $netoAfterD = number_format($brutoAfterD - $mwstAfterD, 9, '.', '');

                                    $totResBr += number_format($brutoAfterD, 9, '.', '');
                                    $totResMwst += number_format($mwstAfterD, 9, '.', '');
                                    $totResNe += number_format($netoAfterD, 9, '.', '');
                                ?>
                                <tr class="heading">
                                    <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($mwstAfterD, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                    <td style="text-align: right;"><strong>100 % </strong></td>
                                </tr>
                            @else
                                <?php
                                    $totResBr += number_format($sumBruto, 9, '.', '');
                                    $totResMwst += number_format($sumMwst, 9, '.', '');
                                    $totResNe += number_format($sumNeto, 9, '.', '');
                                ?>
                            @endif
                @endif










                @if (count($gcPayOrdersRes) > 0)
                    <tr> <td colspan="6" style="color:white">.</td></tr>
                    <tr><td colspan="6"><strong>Restaurant (Geschenkkarte)</strong></td></tr>
                    <tr class="heading" style="margin-top: 0.25cm;">
                        <td style="text-align: left;"><strong>Stk.</strong></td>
                        <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                        <td style="text-align: right;"><strong>Brutto</strong></td>
                        @if($theRes->resTvsh == 0)
                            <td style="text-align: right;"><strong>MWST 0%</strong></td>
                        @else
                            @if($theYr <= 2023)
                            <td style="text-align: right;"><strong>MWST 7.70%</strong></td>
                            @else
                            <td style="text-align: right;"><strong>MWST 8.10%</strong></td>
                            @endif
                        @endif
                        <td style="text-align: right;"><strong>Netto</strong></td>
                        <td style="text-align: right;"><strong>%-Anteil </strong></td>
                    </tr>
                    <?php
                        $prodGroups = array();
                        $grSasia = array();
                        $grBruto = array();
                        $grMwst = array();
                        $grNeto = array();
                        $grAnteil = array();
                        $totInCHF = number_format(0, 9, '.', '');
                        $totDiscount = number_format(0, 9, '.', '');
                        $totDiscountGC = number_format(0, 9, '.', '');
                    ?>
                    <!-- Grupimi i produkteve -->
                    @foreach (OrdersPassive::whereIn('id',$gcPayOrdersRes)->get() as $orOne)
                        <!-- loop through products array -->
                        @foreach (explode('---8---',$orOne->porosia) as $porOne)
                            <?php 
                                $porOne2D = explode('-8-',$porOne); 
                                $theP = Produktet::find($porOne2D[7]);
                            
                                    $mwstPRC = number_format($hiTvsh, 9, '.', '');
                                    $mwst = number_format((float)$porOne2D[4]*$mwstPRC, 9, '.', '');
                                    $bruto = number_format((float)$porOne2D[4], 9, '.', '');
                                    $neto = number_format((float)$porOne2D[4]-$mwst, 9, '.', '');

                                    if(isset($porOne2D[8])){
                                        $repGr = $porOne2D[8];
                                    }else{
                                        if($theP == NULL){$repGr = 0;}else{ $repGr = $theP->toReportCat; }
                                    }

                                    if(in_array($repGr,$prodGroups)){
                                        // gr already registred
                                        $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');
                                    }else{
                                        // new gr
                                        array_push($prodGroups,$repGr);
                                        $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');
                                    }
                                    $totInCHF += number_format((float)$porOne2D[4], 9, '.', '');
                                
                            ?>
                        @endforeach
                        <?php
                            if($orOne->inCashDiscount > 0){$totDiscount = number_format($totDiscount + $orOne->inCashDiscount, 9, '.', '');
                            }else if($orOne->inPercentageDiscount > 0){$totDiscount = number_format($totDiscount + (($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01)), 9, '.', '');}
                        
                            if($orOne->cuponOffVal > 0){
                                $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                            }
                            $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                        ?>
                         
                    @endforeach

                    <!-- llogariten perqindjet ANTEIL per grupacione -->
                    @foreach ($prodGroups as $key) 
                        <?php
                            $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                            if($totInCHF > 0){
                                $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                $grAnteil[$key] = $percVal;
                            }else{
                                $grAnteil[$key] = number_format(100, 9, '.', '');
                            }
                        ?>
                    @endforeach

                    <!-- Shfaq produktet e grupuara -->
                    <?php
                        $sumSasia = number_format(0, 9, '.', '');
                        $sumBruto = number_format(0, 9, '.', '');
                        $sumMwst = number_format(0, 9, '.', '');
                        $sumNeto = number_format(0, 9, '.', '');
                    ?>
                    @foreach ($prodGroups as $key) 
                        <tr>
                            <td style="text-align: left;">
                                {{number_format($grSasia[$key], 0, '.', '')}} X
                            </td>
                            <td style="text-align: left;">
                            @if ($key == 0)
                                nicht kategorisiert
                            @else
                                {{pdfResProdCats::findOrFail($key)->catTitle}}
                            @endif
                            </td>
                            <td style="text-align: right;">
                                CHF {{number_format($grBruto[$key], 2, '.', '')}}
                            </td>
                            <td style="text-align: right;">
                                CHF {{number_format($grMwst[$key], 2, '.', '')}}
                            </td>
                            <td style="text-align: right;">
                                CHF {{number_format($grNeto[$key], 2, '.', '')}}
                            </td>
                            <td style="text-align: right;">
                                {{number_format($grAnteil[$key], 2, '.', '')}} %
                            </td>
                            <?php
                                $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                $sumBruto += number_format($grBruto[$key], 9, '.', '');
                                $sumMwst += number_format($grMwst[$key], 9, '.', '');
                                $sumNeto += number_format($grNeto[$key], 9, '.', '');
                            ?>
                        </tr>
                    @endforeach
                    <?php $totResSasia += number_format($sumSasia, 0, '.', ''); ?>
                        <tr class="heading">
                            <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                            <td style="text-align: left;"><strong></strong></td>
                            <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                            <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                            <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                            <td style="text-align: right;"><strong>100 % </strong></td>
                        </tr>
                    @if ( $totDiscount > 0 )
                        <tr>
                            <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                        </tr>
                    @endif
                    @if ( $totDiscount > 0)
                        <?php
                            $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                            $mwstAfterD = number_format(($sumBruto - $totDiscount)*$hiTvsh, 9, '.', '');
                            $netoAfterD = number_format($brutoAfterD - $mwstAfterD, 9, '.', '');

                            $totResBr += number_format($brutoAfterD, 9, '.', '');
                            $totResMwst += number_format($mwstAfterD, 9, '.', '');
                            $totResNe += number_format($netoAfterD, 9, '.', '');
                        ?>
                        <tr class="heading">
                            <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                            <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                            <td style="text-align: right;"><strong>CHF {{number_format($mwstAfterD, 2, '.', '')}}</strong></td>
                            <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                            <td style="text-align: right;"><strong>100 % </strong></td>
                        </tr>
                    @else
                        <?php
                            $totResBr += number_format($sumBruto, 9, '.', '');
                            $totResMwst += number_format($sumMwst, 9, '.', '');
                            $totResNe += number_format($sumNeto, 9, '.', '');
                        ?>
                    @endif

                @endif
















                @if($totResSasia > 0)
                <tr> <td colspan="6" style="color:white;">.</td></tr>
                <tr class="heading">
                    <td colspan="2" style="text-align: left; background: #6e6e6e !important; color:white;">
                        <strong> Summen für Restaurant </strong>
                    </td>
                    <td style="text-align: right; background: #6e6e6e !important; color:white;">
                        <strong>CHF {{number_format($totResBr, 2, '.', '')}}</strong>
                    </td>
                    <td style="text-align: right; background: #6e6e6e !important; color:white;">
                        <strong>CHF {{number_format($totResMwst, 2, '.', '')}}</strong>
                    </td>
                    <td style="text-align: right; background: #6e6e6e !important; color:white;">
                        <strong>CHF {{number_format($totResNe, 2, '.', '')}}</strong>
                    </td>
                    <td style="text-align: right; background: #6e6e6e !important; color:white;">
                        <strong>{{number_format($totResSasia, 0, '.', '')}} X</strong>
                    </td>
                </tr>
                @endif




































































                    <?php
                        $totTakBr = number_format(0, 9, '.', '');
                        $totTakMwst = number_format(0, 9, '.', '');
                        $totTakNe = number_format(0, 9, '.', '');
                        $totTakSasia = number_format(0, 0, '.', '');
                    ?>
                    <?php $showTable = False; ?>              
                    @foreach ($ordersTakeaway as $orOne)
                        @if ($orOne->payM == "Barzahlungen" || $orOne->payM == "Cash")
                            @if($orOne->shuma != $orOne->dicsountGcAmnt)
                                <?php $showTable = True; ?>
                                @break
                            @else if($orOne->shuma == $orOne->dicsountGcAmnt)
                                @if (!in_array($orOne->id,$gcPayOrdersTa))
                                    <?php array_push($gcPayOrdersTa,$orOne->id); ?>
                                @endif
                            @endif
                        @endif
                    @endforeach

                    @if ($showTable)
                                <tr> <td colspan="6" style="color:white">.</td></tr>
                                <tr><td colspan="6"><strong>Takeaway (Bar)</strong></td></tr>

                                <tr class="heading" style="margin-top: 0.25cm;">
                                    <td style="text-align: left;"><strong>Stk.</strong></td>
                                    <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                                    <td style="text-align: right;"><strong>Brutto</strong></td>
                                    <td style="text-align: right;"><strong>MWST</strong></td>
                                    <td style="text-align: right;"><strong>Netto</strong></td>
                                    <td style="text-align: right;"><strong>%-Anteil </strong></td>
                                </tr>
                                <?php
                                    $prodGroups = array();
                                    $grSasia = array();
                                    $grBruto = array();
                                    $grMwst = array();
                                    $grNeto = array();
                                    $grAnteil = array();
                                    $grMstPerc= array();
                                    $totInCHF = number_format(0, 9, '.', '');
                                    $totDiscount = number_format(0, 9, '.', '');
                                    $totDiscountGC = number_format(0, 9, '.', '');
                                    $hasMwSt0Prods1 = array();
                                    $hasMwStLowProds1 = array();
                                    $hasMwStHigProds1 = array();

                                    $mwstMitRabatBar = number_format(0, 9, '.', '');
                                ?>

                                <!-- Grupimi i produkteve -->
                                @foreach ($ordersTakeaway as $orOne)
                                    @if ($orOne->payM == "Barzahlungen" || $orOne->payM == "Cash")

                                        @if($orOne->shuma == $orOne->dicsountGcAmnt)
                                            @if (!in_array($orOne->id,$gcPayOrdersTa))
                                                <?php array_push($gcPayOrdersTa,$orOne->id); ?>
                                            @endif
                                            
                                        @else
                                            <!-- loop through products array -->
                                            <?php
                                                $totFromProductePrice = number_format(0, 2, '.', '');
                                                foreach(explode('---8---',$orOne) as $produkti){
                                                    $prod = explode('-8-', $produkti);
                                                    $totFromProductePrice += number_format($prod[4], 2, '.', '');
                                                }
                                            ?>

                                            @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                                <?php 
                                                    $porOne2D = explode('-8-',$porOne); 
                                                    
                                                    if($orOne->userPhoneNr != '0770000000'){
                                                        $theP = Takeaway::find($porOne2D[7]);
                                                        $theTP = Takeaway::find($porOne2D[7]);
                                                    }else{
                                                        $theP = Produktet::find($porOne2D[7]);
                                                        $theTP = Takeaway::where('prod_id',$porOne2D[7])->first();
                                                    }

                                                    if($theTP != NULL){ 
                                                        if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                            $mwstPRC = number_format($hiTvsh, 9, '.', ''); 
                                                        }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                            $mwstPRC = number_format($loTvsh, 9, '.', ''); 
                                                        }else{
                                                            $mwstPRC = number_format(0, 9, '.', '');
                                                        }
                                                    }else{ $mwstPRC = number_format((float)$loTvsh, 9, '.', ''); }

                                                    if($orOne->inCashDiscount > 0){
                                                        $totZbritja = number_format($orOne->inCashDiscount, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatBar += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->inPercentageDiscount > 0){
                                                        $totZbritjaPrc = number_format($orOne->inPercentageDiscount / 100, 2, '.', '');
                                                        $cal1 = number_format($porOne2D[4] * $totZbritjaPrc, 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatBar += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->cuponOffVal > 0){
                                                        $totZbritja = number_format($orOne->cuponOffVal, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatBar += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else{
                                                        $mwstMitRabatBar += number_format((float)$porOne2D[4] * $mwstPRC,9,'.','');
                                                    }

                                                    $mwst = number_format((float)$porOne2D[4]*$mwstPRC, 9, '.', '');
                                                    $bruto = number_format((float)$porOne2D[4], 9, '.', '');
                                                    $neto = number_format((float)$porOne2D[4]-$mwst, 9, '.', '');

                                                    if(isset($porOne2D[8])){
                                                        $repGr = $porOne2D[8];
                                                    }else{
                                                        if($theTP == NULL){$repGr = 0;}else{ $repGr = $theTP->toReportCat; }
                                                    }

                                                    if(in_array($repGr,$prodGroups)){
                                                        // gr already registred
                                                        $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');

                                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                        }else{
                                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                                $hasMwStHigProds1[$repGr] = 1;
                                                            }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                                $hasMwStLowProds1[$repGr] = 1;
                                                            }else{
                                                                $ThisMwStVal = number_format((float)0, 9, '.', ''); 
                                                                $hasMwSt0Prods1[$repGr] = 1;
                                                            }
                                                        }
                                                        if($grMstPerc[$repGr] != '2.50 / 7.70'){
                                                            if($grMstPerc[$repGr] == 0){
                                                                $grMstPerc[$repGr] = $ThisMwStVal ;
                                                            }else{
                                                                if($grMstPerc[$repGr] != $ThisMwStVal ){
                                                                    $grMstPerc[$repGr] = '2.50 / 7.70';
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        // new gr
                                                        $hasMwSt0Prods1[$repGr] = 0;
                                                        $hasMwStLowProds1[$repGr] = 0;
                                                        $hasMwStHigProds1[$repGr] = 0;
                                                        array_push($prodGroups,$repGr);
                                                        $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');

                                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                        }else{
                                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                                $hasMwStHigProds1[$repGr] = 1;
                                                            }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                                $hasMwStLowProds1[$repGr] = 1;
                                                            }else{
                                                                $ThisMwStVal = number_format((float)0, 9, '.', ''); 
                                                                $hasMwSt0Prods1[$repGr] = 1;
                                                            }
                                                        }
                                                        $grMstPerc[$repGr] = $ThisMwStVal ;
                                                    }
                                                    $totInCHF += number_format((float)$porOne2D[4], 9, '.', '');
                                                ?>
                                            @endforeach
                                            <?php
                                                if($orOne->inCashDiscount > 0){
                                                    $discountInChf = number_format($orOne->inCashDiscount, 9, '.', '');
                                                    $totDiscount += $discountInChf;
                                                }else if($orOne->inPercentageDiscount > 0){
                                                    $discountInChf = number_format(($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01), 9, '.', '');
                                                    $totDiscount += $discountInChf ;
                                                }
                                                if($orOne->cuponOffVal > 0){
                                                    $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                                }
                                                $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                            ?>
                                        @endif
                                    @endif
                                @endforeach

                                <!-- llogariten perqindjet ANTEIL per grupacione -->
                                @foreach ($prodGroups as $key) 
                                    <?php
                                        $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                        if($totInCHF > 0){
                                            $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                            $grAnteil[$key] = $percVal;
                                        }else{
                                            $grAnteil[$key] = number_format(100, 9, '.', '');
                                        }
                                    ?>
                                @endforeach
                                <!-- Shfaq produktet e grupuara -->
                                <?php
                                    $sumSasia = number_format(0, 0, '.', '');
                                    $sumBruto = number_format(0, 9, '.', '');
                                    $sumMwst = number_format(0, 9, '.', '');
                                    $sumNeto = number_format(0, 9, '.', '');
                                ?>
                                @foreach ($prodGroups as $key) 
                                    <tr>
                                        <td style="text-align: left;">{{number_format($grSasia[$key], 0, '.', '')}} X</td>
                                        <td style="text-align: left;">
                                        <?php
                                            // if($grMstPerc[$key] == number_format((float)$hiTvsh, 2, '.', '')){
                                            //     if($theYr <= 2023){ $showMwstPerc = 7.70; }else{ $showMwstPerc = 8.10; }
                                            // }else if($grMstPerc[$key] == number_format((float)$loTvsh, 2, '.', '')){ 
                                            //     if($theYr <= 2023){ $showMwstPerc = 2.50; }else{ $showMwstPerc = 2.60; }
                                            // }else{
                                            //     if($theYr <= 2023){ $showMwstPerc = '2.50 / 7.70'; }else{ $showMwstPerc = '2.60 / 8.10'; }
                                            // }
                                        ?>
                                        @if ($key == 0)
                                            nicht kategorisiert ( 
                                        @else
                                            {{pdfResProdCats::findOrFail($key)->catTitle}} ( 
                                        @endif
                                            @if ($hasMwSt0Prods1[$key] == 1)
                                                 0.00 
                                            @endif

                                            @if ($hasMwStLowProds1[$key] == 1)
                                                @if ($hasMwSt0Prods1[$key] == 1)
                                                     / 
                                                @endif
                                                @if($theYr <= 2023) 
                                                    2.50 
                                                @else
                                                    2.60
                                                @endif
                                            @endif

                                            @if ($hasMwStHigProds1[$key] == 1)
                                                @if ($hasMwSt0Prods1[$key] == 1 || $hasMwStLowProds1[$key] == 1)
                                                     / 
                                                @endif
                                                @if($theYr <= 2023) 
                                                    7.70 
                                                @else
                                                    8.10
                                                @endif
                                            @endif
                                        %) 
                                        </td>
                                        <td style="text-align: right;">CHF {{number_format($grBruto[$key], 2, '.', '')}}</td>
                                        <td style="text-align: right;">CHF {{number_format($grMwst[$key], 2, '.', '')}}</td>
                                        <td style="text-align: right;">CHF {{number_format($grNeto[$key], 2, '.', '')}}</td>

                                        <td style="text-align: right;">
                                            {{number_format($grAnteil[$key], 2, '.', '')}} %
                                        </td>
                                        <?php
                                            $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                            $sumBruto += number_format($grBruto[$key], 2, '.', '');
                                            $sumMwst += number_format($grMwst[$key], 2, '.', '');
                                            $sumNeto += number_format($grNeto[$key], 2, '.', '');
                                        ?>
                                    </tr>
                                @endforeach
                                <?php
                                    $totTakSasia += number_format($sumSasia, 0, '.', '');
                                ?>
                                    <tr class="heading">
                                        <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                        <td style="text-align: left;"><strong></strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>100 % </strong></td>
                                    </tr>
                                @if ( $totDiscount > 0 )
                                    <tr>
                                        <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                                    </tr>
                                @endif
                                @if ( $totDiscountGC > 0 )
                                    <tr>
                                        <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                                    </tr>
                                @endif
                                @if ( $totDiscount > 0)
                                    <?php
                                        $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                        // $mwstAfterD = number_format($brutoAfterD *0.025341130, 9, '.', '');
                                        $netoAfterD = number_format($brutoAfterD - $mwstMitRabatBar, 9, '.', '');

                                        $totTakBr += number_format($brutoAfterD, 9, '.', '');
                                        $totTakMwst += number_format($mwstMitRabatBar, 9, '.', '');
                                        $totTakNe += number_format($netoAfterD, 9, '.', '');
                                    ?>
                                    <tr class="heading">   
                                        <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($mwstMitRabatBar, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong></strong></td>
                                    </tr>
                                @else
                                    <?php
                                        $totTakBr += number_format($sumBruto, 9, '.', '');
                                        $totTakMwst += number_format($sumMwst, 9, '.', '');
                                        $totTakNe += number_format($sumNeto, 9, '.', '');
                                    ?>
                                @endif
                    @endif













                    <?php $showTable = False; ?>              
                    @foreach ($ordersTakeaway as $orOne)
                        @if ($orOne->payM == "Kartenzahlung")
                            @if($orOne->shuma != $orOne->dicsountGcAmnt)
                                <?php $showTable = True; ?>
                                @break
                            @else if($orOne->shuma == $orOne->dicsountGcAmnt)
                                @if (!in_array($orOne->id,$gcPayOrdersTa))
                                    <?php array_push($gcPayOrdersTa,$orOne->id); ?>
                                @endif
                            @endif
                        @endif
                    @endforeach

                    @if ($showTable)
                                <tr> <td colspan="6" style="color:white">.</td></tr>
                                <tr><td colspan="6"><strong>Takeaway (Karte)</strong></td></tr>
                                <tr class="heading" style="margin-top: 0.25cm;">
                                    <td style="text-align: left;"><strong>Stk.</strong></td>
                                    <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                                    <td style="text-align: right;"><strong>Brutto</strong></td>
                                    <td style="text-align: right;"><strong>MWST</strong></td>
                                    <td style="text-align: right;"><strong>Netto</strong></td>
                                    <td style="text-align: right;"><strong>%-Anteil </strong></td>
                                </tr>
                                <?php
                                    $prodGroups = array();
                                    $grSasia = array();
                                    $grBruto = array();
                                    $grMwst = array();
                                    $grNeto = array();
                                    $grAnteil = array();
                                    $grMstPerc= array();
                                    $totInCHF = number_format(0, 9, '.', '');
                                    $totDiscount = number_format(0, 9, '.', '');
                                    $totDiscountGC = number_format(0, 9, '.', '');
                                    $hasMwSt0Prods2 = array();
                                    $hasMwStLowProds2 = array();
                                    $hasMwStHigProds2 = array();

                                    $mwstMitRabatKarte = number_format(0, 9, '.', '');
                                ?>
                                <!-- Grupimi i produkteve -->
                                @foreach ($ordersTakeaway as $orOne)
                                    @if ($orOne->payM == "Kartenzahlung")
                                        @if($orOne->shuma == $orOne->dicsountGcAmnt)
                                            @if (!in_array($orOne->id,$gcPayOrdersTa))
                                                <?php array_push($gcPayOrdersTa,$orOne->id); ?>
                                            @endif
                                            
                                        @else
                                            <!-- loop through products array -->
                                            <?php
                                                $totFromProductePrice = number_format(0, 2, '.', '');
                                                foreach(explode('---8---',$orOne) as $produkti){
                                                    $prod = explode('-8-', $produkti);
                                                    $totFromProductePrice += number_format($prod[4], 2, '.', '');
                                                }
                                            ?>
                                            @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                                <?php 
                                                    $porOne2D = explode('-8-',$porOne); 
                                                    
                                                    if($orOne->userPhoneNr != '0770000000'){
                                                        $theP = Takeaway::find($porOne2D[7]);
                                                        $theTP = Takeaway::find($porOne2D[7]);
                                                    }else{
                                                        $theP = Produktet::find($porOne2D[7]);
                                                        $theTP = Takeaway::where('prod_id',$porOne2D[7])->first();
                                                    }

                                                    if($theTP != NULL){ 
                                                        if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                            $mwstPRC = number_format($hiTvsh, 9, '.', ''); 
                                                        }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                            $mwstPRC = number_format($loTvsh, 9, '.', ''); 
                                                        }else{
                                                            $mwstPRC = number_format(0, 9, '.', '');
                                                        }
                                                    }else{
                                                        $mwstPRC = number_format((float)$loTvsh, 9, '.', ''); 
                                                    }


                                                    if($orOne->inCashDiscount > 0){
                                                        $totZbritja = number_format($orOne->inCashDiscount, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatKarte += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->inPercentageDiscount > 0){
                                                        $totZbritjaPrc = number_format($orOne->inPercentageDiscount / 100, 2, '.', '');
                                                        $cal1 = number_format($porOne2D[4] * $totZbritjaPrc, 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatKarte += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->cuponOffVal > 0){
                                                        $totZbritja = number_format($orOne->cuponOffVal, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatKarte += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else{
                                                        $mwstMitRabatKarte += number_format((float)$porOne2D[4] * $mwstPRC,9,'.','');
                                                    }

                                                    $mwst = number_format((float)$porOne2D[4]*$mwstPRC, 9, '.', '');
                                                    $bruto = number_format((float)$porOne2D[4], 9, '.', '');
                                                    $neto = number_format((float)$porOne2D[4]-$mwst, 9, '.', '');

                                                    if(isset($porOne2D[8])){
                                                        $repGr = $porOne2D[8];
                                                    }else{
                                                        if($theTP == NULL){$repGr = 0;}else{ $repGr = $theTP->toReportCat; }
                                                    }

                                                    if(in_array($repGr,$prodGroups)){
                                                        // gr already registred
                                                        $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');

                                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                        }else{
                                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                                $hasMwStHigProds2[$repGr] = 1;
                                                            }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                                $hasMwStLowProds2[$repGr] = 1;
                                                            }else{
                                                                $ThisMwStVal = number_format((float)0, 9, '.', ''); 
                                                                $hasMwSt0Prods2[$repGr] = 1;
                                                            }
                                                        }
                                                        if($grMstPerc[$repGr] != '2.50 / 7.70'){
                                                            if($grMstPerc[$repGr] == 0){
                                                                $grMstPerc[$repGr] = $ThisMwStVal ;
                                                            }else{
                                                                if($grMstPerc[$repGr] != $ThisMwStVal ){
                                                                    $grMstPerc[$repGr] = '2.50 / 7.70';
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        // new gr
                                                        $hasMwSt0Prods2[$repGr] = 0;
                                                        $hasMwStLowProds2[$repGr] = 0;
                                                        $hasMwStHigProds2[$repGr] = 0;
                                                        array_push($prodGroups,$repGr);
                                                        $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');

                                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                        }else{
                                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                                $hasMwStHigProds2[$repGr] = 1;
                                                            }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                                $hasMwStLowProds2[$repGr] = 1;
                                                            }else{
                                                                $ThisMwStVal = number_format((float)0, 9, '.', ''); 
                                                                $hasMwSt0Prods2[$repGr] = 1;
                                                            }
                                                        }
                                                        $grMstPerc[$repGr] = $ThisMwStVal ;
                                                    }
                                                    $totInCHF += number_format((float)$porOne2D[4], 9, '.', '');
                                                ?>
                                            @endforeach
                                            <?php
                                                if($orOne->inCashDiscount > 0){$totDiscount += number_format($orOne->inCashDiscount, 9, '.', '');
                                                }else if($orOne->inPercentageDiscount > 0){
                                                    $totDiscount += number_format(($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01), 9, '.', '');
                                                }

                                                if($orOne->cuponOffVal > 0){
                                                    $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                                }

                                                $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                            ?>
                                        @endif
                                    @endif
                                @endforeach

                                <!-- llogariten perqindjet ANTEIL per grupacione -->
                                @foreach ($prodGroups as $key) 
                                    <?php
                                        $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                        if($totInCHF > 0){
                                            $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                            $grAnteil[$key] = $percVal;
                                        }else{
                                            $grAnteil[$key] = number_format(100, 9, '.', '');
                                        }
                                    ?>
                                @endforeach
                                <!-- Shfaq produktet e grupuara -->
                                <?php
                                    $sumSasia = number_format(0, 0, '.', '');
                                    $sumBruto = number_format(0, 9, '.', '');
                                    $sumMwst = number_format(0, 9, '.', '');
                                    $sumNeto = number_format(0, 9, '.', '');
                                ?>
                                @foreach ($prodGroups as $key) 
                                    <tr>
                                        <td style="text-align: left;">{{number_format($grSasia[$key], 0, '.', '')}} X</td>
                                        <td style="text-align: left;">
                                        <?php
                                            // if($grMstPerc[$key] == number_format((float)$hiTvsh, 2, '.', '')){
                                            //     if($theYr <= 2023){ $showMwstPerc = 7.70; }else{ $showMwstPerc = 8.10; }
                                            // }else if($grMstPerc[$key] == number_format((float)$loTvsh, 2, '.', '')){ 
                                            //     if($theYr <= 2023){ $showMwstPerc = 2.50; }else{ $showMwstPerc = 2.60; }
                                            // }else{
                                            //     if($theYr <= 2023){ $showMwstPerc = '2.50 / 7.70'; }else{ $showMwstPerc = '2.60 / 8.10'; }
                                            // }
                                        ?>
                                        @if ($key == 0)
                                            nicht kategorisiert ( 
                                        @else
                                            {{pdfResProdCats::findOrFail($key)->catTitle}} ( 
                                        @endif
                                            @if ($hasMwSt0Prods2[$key] == 1)
                                                 0.00 
                                            @endif

                                            @if ($hasMwStLowProds2[$key] == 1)
                                                @if ($hasMwSt0Prods2[$key] == 1)
                                                     / 
                                                @endif
                                                @if($theYr <= 2023) 
                                                    2.50 
                                                @else
                                                    2.60
                                                @endif
                                            @endif

                                            @if ($hasMwStHigProds2[$key] == 1)
                                                @if ($hasMwSt0Prods2[$key] == 1 || $hasMwStLowProds2[$key] == 1)
                                                     / 
                                                @endif
                                                @if($theYr <= 2023) 
                                                    7.70 
                                                @else
                                                    8.10
                                                @endif
                                            @endif
                                        %) 
                                      
                                        </td>
                                        <td style="text-align: right;">CHF {{number_format($grBruto[$key], 2, '.', '')}}</td>
                                        <td style="text-align: right;">CHF {{number_format($grMwst[$key], 2, '.', '')}}</td>
                                        <td style="text-align: right;">CHF {{number_format($grNeto[$key], 2, '.', '')}}</td>

                                        <td style="text-align: right;">
                                            {{number_format($grAnteil[$key], 2, '.', '')}} %
                                        </td>
                                        <?php
                                            $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                            $sumBruto += number_format($grBruto[$key], 2, '.', '');
                                            $sumMwst += number_format($grMwst[$key], 2, '.', '');
                                            $sumNeto += number_format($grNeto[$key], 2, '.', '');
                                        ?>
                                    </tr>
                                @endforeach
                                <?php
                                    $totTakSasia += number_format($sumSasia, 0, '.', '');
                                ?>
                                    <tr class="heading">
                                        <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                        <td style="text-align: left;"><strong></strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>100 % </strong></td>
                                    </tr>
                                @if ( $totDiscount > 0 )
                                    <tr>
                                        <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                                    </tr>
                                @endif
                                @if ( $totDiscountGC > 0 )
                                    <tr>
                                        <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                                    </tr>
                                @endif
                                @if ( $totDiscount > 0)
                                    <?php
                                        $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                        // $mwstAfterD = number_format($brutoAfterD *0.025341130, 9, '.', '');
                                        $netoAfterD = number_format($brutoAfterD - $mwstMitRabatKarte, 9, '.', '');

                                        $totTakBr += number_format($brutoAfterD, 9, '.', '');
                                        $totTakMwst += number_format($mwstMitRabatKarte, 9, '.', '');
                                        $totTakNe += number_format($netoAfterD, 9, '.', '');
                                    ?>
                                    <tr class="heading">   
                                        <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($mwstMitRabatKarte, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong></strong></td>
                                    </tr>
                                @else
                                    <?php
                                        $totTakBr += number_format($sumBruto, 9, '.', '');
                                        $totTakMwst += number_format($sumMwst, 9, '.', '');
                                        $totTakNe += number_format($sumNeto, 9, '.', '');
                                    ?>
                                @endif
                    @endif












                    <?php $showTable = False; ?>              
                    @foreach ($ordersTakeaway as $orOne)
                        @if ($orOne->payM == "Onlinezahlung" || $orOne->payM == "Online")
                            @if($orOne->shuma != $orOne->dicsountGcAmnt)
                                <?php $showTable = True; ?>
                                @break
                            @else if($orOne->shuma == $orOne->dicsountGcAmnt)
                                @if (!in_array($orOne->id,$gcPayOrdersTa))
                                    <?php array_push($gcPayOrdersTa,$orOne->id); ?>
                                @endif
                            @endif
                        @endif
                    @endforeach

                    @if ($showTable)
                                <tr> <td colspan="6" style="color:white">.</td></tr>
                                <tr><td colspan="6"><strong>Takeaway (Online)</strong></td></tr>
                                <tr class="heading" style="margin-top: 0.25cm;">
                                    <td style="text-align: left;"><strong>Stk.</strong></td>
                                    <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                                    <td style="text-align: right;"><strong>Brutto</strong></td>
                                    <td style="text-align: right;"><strong>MWST</strong></td>
                                    <td style="text-align: right;"><strong>Netto</strong></td>
                                    <td style="text-align: right;"><strong>%-Anteil </strong></td>
                                </tr>
                                <?php
                                    $prodGroups = array();
                                    $grSasia = array();
                                    $grBruto = array();
                                    $grMwst = array();
                                    $grNeto = array();
                                    $grAnteil = array();
                                    $grMstPerc= array();
                                    $totInCHF = number_format(0, 9, '.', '');
                                    $totDiscount = number_format(0, 9, '.', '');
                                    $totDiscountGC = number_format(0, 9, '.', '');
                                    $hasMwSt0Prods3 = array();
                                    $hasMwStLowProds3 = array();
                                    $hasMwStHigProds3 = array();

                                    $mwstMitRabatOnline = number_format(0, 9, '.', '');
                                ?>
                                <!-- Grupimi i produkteve -->
                                @foreach ($ordersTakeaway as $orOne)
                                    @if ($orOne->payM == "Onlinezahlung" || $orOne->payM == "Online")

                                        @if($orOne->shuma == $orOne->dicsountGcAmnt)
                                            @if (!in_array($orOne->id,$gcPayOrdersTa))
                                                <?php array_push($gcPayOrdersTa,$orOne->id); ?>
                                            @endif
                                            
                                        @else
                                            <!-- loop through products array -->
                                            <?php
                                                $totFromProductePrice = number_format(0, 2, '.', '');
                                                foreach(explode('---8---',$orOne) as $produkti){
                                                    $prod = explode('-8-', $produkti);
                                                    $totFromProductePrice += number_format($prod[4], 2, '.', '');
                                                }
                                            ?>
                                            @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                                <?php 
                                                    $porOne2D = explode('-8-',$porOne); 
                                                    
                                                    if($orOne->userPhoneNr != '0770000000'){
                                                        if($orOne->userPhoneNr == '0000000000'){
                                                            $theTP = Takeaway::where('prod_id',$porOne2D[7])->first();
                                                        }else{
                                                            $theTP = Takeaway::find($porOne2D[7]);
                                                        }
                                                    }else{
                                                        // $theP = Produktet::find($porOne2D[7]);
                                                        $theTP = Takeaway::where('prod_id',$porOne2D[7])->first();
                                                    }
                                                    if($theTP != NULL){ 
                                                        if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                            $mwstPRC = number_format($hiTvsh, 9, '.', ''); 
                                                        }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                            $mwstPRC = number_format($loTvsh, 9, '.', ''); 
                                                        }else{
                                                            $mwstPRC = number_format(0, 9, '.', '');
                                                        }
                                                    }else{ $mwstPRC = number_format((float)0.01111, 9, '.', ''); }

                                                    if($orOne->inCashDiscount > 0){
                                                        $totZbritja = number_format($orOne->inCashDiscount, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatOnline += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->inPercentageDiscount > 0){
                                                        $totZbritjaPrc = number_format($orOne->inPercentageDiscount / 100, 2, '.', '');
                                                        $cal1 = number_format($porOne2D[4] * $totZbritjaPrc, 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatOnline += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->cuponOffVal > 0){
                                                        $totZbritja = number_format($orOne->cuponOffVal, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatOnline += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else{
                                                        $mwstMitRabatOnline += number_format((float)$porOne2D[4] * $mwstPRC,9,'.','');
                                                    }

                                                    $mwst = number_format((float)$porOne2D[4]*$mwstPRC, 9, '.', '');
                                                    $bruto = number_format((float)$porOne2D[4], 9, '.', '');
                                                    $neto = number_format((float)$porOne2D[4]-$mwst, 9, '.', '');

                                                    if(isset($porOne2D[8])){
                                                        $repGr = $porOne2D[8];
                                                    }else{
                                                        if($theTP == NULL){$repGr = 0;}else{ $repGr = $theTP->toReportCat; }
                                                    }

                                                    if(in_array($repGr,$prodGroups)){
                                                        // gr already registred
                                                        $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');

                                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                        }else{
                                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                                $hasMwStHigProds3[$repGr] = 1;
                                                            }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                                $hasMwStLowProds3[$repGr] = 1;
                                                            }else{
                                                                $ThisMwStVal = number_format((float)0, 9, '.', ''); 
                                                                $hasMwSt0Prods3[$repGr] = 1;
                                                            }
                                                        }
                                                        if($grMstPerc[$repGr] != '2.50 / 7.70'){
                                                            if($grMstPerc[$repGr] == 0){
                                                                $grMstPerc[$repGr] = $ThisMwStVal ;
                                                            }else{
                                                                if($grMstPerc[$repGr] != $ThisMwStVal ){
                                                                    $grMstPerc[$repGr] = '2.50 / 7.70';
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        // new gr
                                                        $hasMwSt0Prods3[$repGr] = 0;
                                                        $hasMwStLowProds3[$repGr] = 0;
                                                        $hasMwStHigProds3[$repGr] = 0;
                                                        array_push($prodGroups,$repGr);
                                                        $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');

                                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                        }else{
                                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                                $hasMwStHigProds3[$repGr] = 1;
                                                            }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                                $hasMwStLowProds3[$repGr] = 1;
                                                            }else{
                                                                $ThisMwStVal = number_format((float)0, 9, '.', ''); 
                                                                $hasMwSt0Prods3[$repGr] = 1;
                                                            }
                                                        }
                                                        $grMstPerc[$repGr] = $ThisMwStVal ;
                                                    }
                                                    $totInCHF += number_format((float)$porOne2D[4], 9, '.', '');
                                                ?>
                                            @endforeach
                                            <?php
                                                if($orOne->inCashDiscount > 0){$totDiscount += number_format($orOne->inCashDiscount, 9, '.', '');
                                                }else if($orOne->inPercentageDiscount > 0){
                                                    $totDiscount += number_format(($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01), 9, '.', '');
                                                }

                                                if($orOne->cuponOffVal > 0){
                                                    $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                                }
                                                $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                            ?>
                                        @endif
                                    @endif
                                @endforeach

                                <!-- llogariten perqindjet ANTEIL per grupacione -->
                                @foreach ($prodGroups as $key) 
                                    <?php
                                        $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                        if($totInCHF > 0){
                                            $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                            $grAnteil[$key] = $percVal;
                                        }else{
                                            $grAnteil[$key] = number_format(100, 9, '.', '');
                                        }
                                    ?>
                                @endforeach
                                <!-- Shfaq produktet e grupuara -->
                                <?php
                                    $sumSasia = number_format(0, 0, '.', '');
                                    $sumBruto = number_format(0, 9, '.', '');
                                    $sumMwst = number_format(0, 9, '.', '');
                                    $sumNeto = number_format(0, 9, '.', '');
                                ?>
                                @foreach ($prodGroups as $key) 
                                    <tr>
                                        <td style="text-align: left;">{{number_format($grSasia[$key], 0, '.', '')}} X</td>
                                        <td style="text-align: left;">
                                        <?php
                                            // if($grMstPerc[$key] == number_format((float)$hiTvsh, 2, '.', '')){
                                            //     if($theYr <= 2023){ $showMwstPerc = 7.70; }else{ $showMwstPerc = 8.10; }
                                            // }else if($grMstPerc[$key] == number_format((float)$loTvsh, 2, '.', '')){ 
                                            //     if($theYr <= 2023){ $showMwstPerc = 2.50; }else{ $showMwstPerc = 2.60; }
                                            // }else{
                                            //     if($theYr <= 2023){ $showMwstPerc = '2.50 / 7.70'; }else{ $showMwstPerc = '2.60 / 8.10'; }
                                            // }
                                        ?>
                                        @if ($key == 0)
                                            nicht kategorisiert ( 
                                        @else
                                            {{pdfResProdCats::findOrFail($key)->catTitle}} ( 
                                        @endif
                                            @if ($hasMwSt0Prods3[$key] == 1)
                                                 0.00 
                                            @endif

                                            @if ($hasMwStLowProds3[$key] == 1)
                                                @if ($hasMwSt0Prods3[$key] == 1)
                                                     / 
                                                @endif
                                                @if($theYr <= 2023) 
                                                    2.50 
                                                @else
                                                    2.60
                                                @endif
                                            @endif

                                            @if ($hasMwStHigProds3[$key] == 1)
                                                @if ($hasMwSt0Prods3[$key] == 1 || $hasMwStLowProds3[$key] == 1)
                                                     / 
                                                @endif
                                                @if($theYr <= 2023) 
                                                    7.70 
                                                @else
                                                    8.10
                                                @endif
                                            @endif
                                        %) 
                                        </td>
                                        <td style="text-align: right;">CHF {{number_format($grBruto[$key], 2, '.', '')}}</td>
                                        <td style="text-align: right;">CHF {{number_format($grMwst[$key], 2, '.', '')}}</td>
                                        <td style="text-align: right;">CHF {{number_format($grNeto[$key], 2, '.', '')}}</td>

                                        <td style="text-align: right;">
                                            {{number_format($grAnteil[$key], 2, '.', '')}} %
                                        </td>
                                        <?php
                                            $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                            $sumBruto += number_format($grBruto[$key], 2, '.', '');
                                            $sumMwst += number_format($grMwst[$key], 2, '.', '');
                                            $sumNeto += number_format($grNeto[$key], 2, '.', '');
                                        ?>
                                    </tr>
                                @endforeach
                                <?php
                                    $totTakSasia += number_format($sumSasia, 0, '.', '');
                                ?>
                                    <tr class="heading">
                                        <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                        <td style="text-align: left;"><strong></strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>100 % </strong></td>
                                    </tr>
                                @if ( $totDiscount > 0 )
                                    <tr>
                                        <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                                    </tr>
                                @endif
                                @if ( $totDiscountGC > 0 )
                                    <tr>
                                        <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                                    </tr>
                                @endif
                                @if ( $totDiscount > 0)
                                    <?php
                                        $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                        // $mwstAfterD = number_format($brutoAfterD *0.025341130, 9, '.', '');
                                        $netoAfterD = number_format($brutoAfterD - $mwstMitRabatOnline, 9, '.', '');

                                        $totTakBr += number_format($brutoAfterD, 9, '.', '');
                                        $totTakMwst += number_format($mwstMitRabatOnline, 9, '.', '');
                                        $totTakNe += number_format($netoAfterD, 9, '.', '');
                                    ?>
                                    <tr class="heading">   
                                        <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($mwstMitRabatOnline, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong></strong></td>
                                    </tr>
                                @else
                                    <?php
                                        $totTakBr += number_format($sumBruto, 9, '.', '');
                                        $totTakMwst += number_format($sumMwst, 9, '.', '');
                                        $totTakNe += number_format($sumNeto, 9, '.', '');
                                    ?>
                                @endif
                    @endif















                    <?php $showTable = False; ?>              
                    @foreach ($ordersTakeaway as $orOne)
                        @if ($orOne->payM == "Rechnung")
                            @if($orOne->shuma != $orOne->dicsountGcAmnt)
                                <?php $showTable = True; ?>
                                @break
                            @else if($orOne->shuma == $orOne->dicsountGcAmnt)
                                @if (!in_array($orOne->id,$gcPayOrdersTa))
                                    <?php array_push($gcPayOrdersTa,$orOne->id); ?>
                                @endif
                            @endif
                        @endif
                    @endforeach

                    @if ($showTable)
                                <tr> <td colspan="6" style="color:white">.</td></tr>
                                <tr><td colspan="6"><strong>Takeaway (Auf Rechnung)</strong></td></tr>
                                <tr class="heading" style="margin-top: 0.25cm;">
                                    <td style="text-align: left;"><strong>Stk.</strong></td>
                                    <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                                    <td style="text-align: right;"><strong>Brutto</strong></td>
                                    <td style="text-align: right;"><strong>MWST</strong></td>
                                    <td style="text-align: right;"><strong>Netto</strong></td>
                                    <td style="text-align: right;"><strong>%-Anteil </strong></td>
                                </tr>
                                <?php
                                    $prodGroups = array();
                                    $grSasia = array();
                                    $grBruto = array();
                                    $grMwst = array();
                                    $grNeto = array();
                                    $grAnteil = array();
                                    $grMstPerc= array();
                                    $totInCHF = number_format(0, 9, '.', '');
                                    $totDiscount = number_format(0, 9, '.', '');
                                    $totDiscountGC = number_format(0, 9, '.', '');
                                    $hasMwSt0Prods4 = array();
                                    $hasMwStLowProds4 = array();
                                    $hasMwStHigProds4 = array();

                                    $mwstMitRabatRechnung = number_format(0, 9, '.', '');
                                ?>
                                <!-- Grupimi i produkteve -->
                                @foreach ($ordersTakeaway as $orOne)
                                    @if ($orOne->payM == "Rechnung")

                                        @if($orOne->shuma == $orOne->dicsountGcAmnt)
                                            @if (!in_array($orOne->id,$gcPayOrdersTa))
                                                <?php array_push($gcPayOrdersTa,$orOne->id); ?>
                                            @endif
                                            
                                        @else
                                            <!-- loop through products array -->
                                            <?php
                                                $totFromProductePrice = number_format(0, 2, '.', '');
                                                foreach(explode('---8---',$orOne) as $produkti){
                                                    $prod = explode('-8-', $produkti);
                                                    $totFromProductePrice += number_format($prod[4], 2, '.', '');
                                                }
                                            ?>
                                            @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                                <?php 
                                                    $porOne2D = explode('-8-',$porOne); 
                                                    
                                                    if($orOne->userPhoneNr != '0770000000'){
                                                        $theP = Takeaway::find($porOne2D[7]);
                                                        $theTP = Takeaway::find($porOne2D[7]);
                                                    }else{
                                                        $theP = Produktet::find($porOne2D[7]);
                                                        $theTP = Takeaway::where('prod_id',$porOne2D[7])->first();
                                                    }

                                                    if($theTP != NULL){ 
                                                        if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                            $mwstPRC = number_format($hiTvsh, 9, '.', ''); 
                                                        }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                            $mwstPRC = number_format($loTvsh, 9, '.', ''); 
                                                        }else{
                                                            $mwstPRC = number_format(0, 9, '.', '');
                                                        }
                                                    }else{ $mwstPRC = number_format((float)$loTvsh, 9, '.', ''); }

                                                    if($orOne->inCashDiscount > 0){
                                                        $totZbritja = number_format($orOne->inCashDiscount, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatRechnung += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->inPercentageDiscount > 0){
                                                        $totZbritjaPrc = number_format($orOne->inPercentageDiscount / 100, 2, '.', '');
                                                        $cal1 = number_format($porOne2D[4] * $totZbritjaPrc, 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatRechnung += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->cuponOffVal > 0){
                                                        $totZbritja = number_format($orOne->cuponOffVal, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatRechnung += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else{
                                                        $mwstMitRabatRechnung += number_format((float)$porOne2D[4] * $mwstPRC,9,'.','');
                                                    }

                                                    $mwst = number_format((float)$porOne2D[4]*$mwstPRC, 9, '.', '');
                                                    $bruto = number_format((float)$porOne2D[4], 9, '.', '');
                                                    $neto = number_format((float)$porOne2D[4]-$mwst, 9, '.', '');

                                                    if(isset($porOne2D[8])){
                                                        $repGr = $porOne2D[8];
                                                    }else{
                                                        if($theTP == NULL){$repGr = 0;}else{ $repGr = $theTP->toReportCat; }
                                                    }

                                                    if(in_array($repGr,$prodGroups)){
                                                        // gr already registred
                                                        $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');

                                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                        }else{
                                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                                $hasMwStHigProds4[$repGr] = 1;
                                                            }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                                $hasMwStLowProds4[$repGr] = 1;
                                                            }else{
                                                                $ThisMwStVal = number_format((float)0, 9, '.', ''); 
                                                                $hasMwSt0Prods4[$repGr] = 1;
                                                            }
                                                        }
                                                        if($grMstPerc[$repGr] != '2.50 / 7.70'){
                                                            if($grMstPerc[$repGr] == 0){
                                                                $grMstPerc[$repGr] = $ThisMwStVal ;
                                                            }else{
                                                                if($grMstPerc[$repGr] != $ThisMwStVal ){
                                                                    $grMstPerc[$repGr] = '2.50 / 7.70';
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        // new gr
                                                        $hasMwSt0Prods4[$repGr] = 0;
                                                        $hasMwStLowProds4[$repGr] = 0;
                                                        $hasMwStHigProds4[$repGr] = 0;
                                                        array_push($prodGroups,$repGr);
                                                        $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');

                                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                        }else{
                                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                                $hasMwStHigProds4[$repGr] = 1;
                                                            }else if($theTP->mwstForPro == 2.50 || $theTP->mwstForPro == 2.60){
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                                $hasMwStLowProds4[$repGr] = 1;
                                                            }else{
                                                                $ThisMwStVal = number_format((float)0, 9, '.', ''); 
                                                                $hasMwSt0Prods4[$repGr] = 1;
                                                            }
                                                        }
                                                        $grMstPerc[$repGr] = $ThisMwStVal ;
                                                    }
                                                    $totInCHF += number_format((float)$porOne2D[4], 9, '.', '');
                                                ?>
                                            @endforeach
                                            <?php
                                                if($orOne->inCashDiscount > 0){$totDiscount += number_format($orOne->inCashDiscount, 9, '.', '');
                                                }else if($orOne->inPercentageDiscount > 0){
                                                    $totDiscount += number_format(($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01), 9, '.', '');
                                                }

                                                if($orOne->cuponOffVal > 0){
                                                    $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                                }

                                                $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                            ?>
                                        @endif
                                    @endif
                                @endforeach

                                <!-- llogariten perqindjet ANTEIL per grupacione -->
                                @foreach ($prodGroups as $key) 
                                    <?php
                                        $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                        if($totInCHF > 0){
                                            $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                            $grAnteil[$key] = $percVal;
                                        }else{
                                            $grAnteil[$key] = number_format(100, 9, '.', '');
                                        }
                                    ?>
                                @endforeach
                                <!-- Shfaq produktet e grupuara -->
                                <?php
                                    $sumSasia = number_format(0, 0, '.', '');
                                    $sumBruto = number_format(0, 9, '.', '');
                                    $sumMwst = number_format(0, 9, '.', '');
                                    $sumNeto = number_format(0, 9, '.', '');
                                ?>
                                @foreach ($prodGroups as $key) 
                                    <tr>
                                        <td style="text-align: left;">{{number_format($grSasia[$key], 0, '.', '')}} X</td>
                                        <td style="text-align: left;">
                                        <?php
                                            // if($grMstPerc[$key] == number_format((float)$hiTvsh, 2, '.', '')){
                                            //     if($theYr <= 2023){ $showMwstPerc = 7.70; }else{ $showMwstPerc = 8.10; }
                                            // }else if($grMstPerc[$key] == number_format((float)$loTvsh, 2, '.', '')){ 
                                            //     if($theYr <= 2023){ $showMwstPerc = 2.50; }else{ $showMwstPerc = 2.60; }
                                            // }else{
                                            //     if($theYr <= 2023){ $showMwstPerc = '2.50 / 7.70'; }else{ $showMwstPerc = '2.60 / 8.10'; }
                                            // }
                                        ?>
                                        @if ($key == 0)
                                            nicht kategorisiert ( 
                                        @else
                                            {{pdfResProdCats::findOrFail($key)->catTitle}} ( 
                                        @endif
                                            @if ($hasMwSt0Prods4[$key] == 1)
                                                 0.00 
                                            @endif

                                            @if ($hasMwStLowProds4[$key] == 1)
                                                @if ($hasMwSt0Prods4[$key] == 1)
                                                     / 
                                                @endif
                                                @if($theYr <= 2023) 
                                                    2.50 
                                                @else
                                                    2.60
                                                @endif
                                            @endif

                                            @if ($hasMwStHigProds4[$key] == 1)
                                                @if ($hasMwSt0Prods4[$key] == 1 || $hasMwStLowProds4[$key] == 1)
                                                     / 
                                                @endif
                                                @if($theYr <= 2023) 
                                                    7.70 
                                                @else
                                                    8.10
                                                @endif
                                            @endif
                                        %)
                                        </td>
                                        <td style="text-align: right;">CHF {{number_format($grBruto[$key], 2, '.', '')}}</td>
                                        <td style="text-align: right;">CHF {{number_format($grMwst[$key], 2, '.', '')}}</td>
                                        <td style="text-align: right;">CHF {{number_format($grNeto[$key], 2, '.', '')}}</td>

                                        <td style="text-align: right;">
                                            {{number_format($grAnteil[$key], 2, '.', '')}} %
                                        </td>
                                        <?php
                                            $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                            $sumBruto += number_format($grBruto[$key], 2, '.', '');
                                            $sumMwst += number_format($grMwst[$key], 2, '.', '');
                                            $sumNeto += number_format($grNeto[$key], 2, '.', '');
                                        ?>
                                    </tr>
                                @endforeach
                                <?php
                                    $totTakSasia += number_format($sumSasia, 0, '.', '');
                                ?>
                                    <tr class="heading">
                                        <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                        <td style="text-align: left;"><strong></strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>100 % </strong></td>
                                    </tr>
                                @if ( $totDiscount > 0 )
                                    <tr>
                                        <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                                    </tr>
                                @endif
                                @if ( $totDiscountGC > 0 )
                                    <tr>
                                        <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                                    </tr>
                                @endif
                                @if ( $totDiscount > 0)
                                    <?php
                                        $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                        // $mwstAfterD = number_format($brutoAfterD *0.025341130, 9, '.', '');
                                        $netoAfterD = number_format($brutoAfterD - $mwstMitRabatRechnung, 9, '.', '');

                                        $totTakBr += number_format($brutoAfterD, 9, '.', '');
                                        $totTakMwst += number_format($mwstMitRabatRechnung, 9, '.', '');
                                        $totTakNe += number_format($netoAfterD, 9, '.', '');
                                    ?>
                                    <tr class="heading">   
                                        <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($mwstMitRabatRechnung, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                        <td style="text-align: right;"><strong></strong></td>
                                    </tr>
                                @else
                                    <?php
                                        $totTakBr += number_format($sumBruto, 9, '.', '');
                                        $totTakMwst += number_format($sumMwst, 9, '.', '');
                                        $totTakNe += number_format($sumNeto, 9, '.', '');
                                    ?>
                                @endif
                    @endif







                    @if (count($gcPayOrdersTa) > 0)
                        <tr> <td colspan="6" style="color:white">.</td></tr>
                        <tr><td colspan="6"><strong>Takeaway (Geschenkkarte)</strong></td></tr>
                        <tr class="heading" style="margin-top: 0.25cm;">
                            <td style="text-align: left;"><strong>Stk.</strong></td>
                            <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                            <td style="text-align: right;"><strong>Brutto</strong></td>
                            <td style="text-align: right;"><strong>MWST</strong></td>
                            <td style="text-align: right;"><strong>Netto</strong></td>
                            <td style="text-align: right;"><strong>%-Anteil </strong></td>
                        </tr>
                        <?php
                            $prodGroups = array();
                            $grSasia = array();
                            $grBruto = array();
                            $grMwst = array();
                            $grNeto = array();
                            $grAnteil = array();
                            $grMstPerc= array();
                            $totInCHF = number_format(0, 9, '.', '');
                            $totDiscount = number_format(0, 9, '.', '');
                            $totDiscountGC = number_format(0, 9, '.', '');

                            $mwstMitRabatRechnung = number_format(0, 9, '.', '');
                        ?>
                        <!-- Grupimi i produkteve -->
                        @foreach (OrdersPassive::whereIn('id',$gcPayOrdersTa)->get() as $orOne)
                            <!-- loop through products array -->
                            <?php
                                $totFromProductePrice = number_format(0, 2, '.', '');
                                foreach(explode('---8---',$orOne) as $produkti){
                                    $prod = explode('-8-', $produkti);
                                    $totFromProductePrice += number_format($prod[4], 2, '.', '');
                                }
                            ?>
                            @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                <?php 
                                    $porOne2D = explode('-8-',$porOne); 
                                    if($orOne->userPhoneNr != '0770000000'){
                                        $theP = Takeaway::find($porOne2D[7]);
                                        $theTP = Takeaway::find($porOne2D[7]);
                                    }else{
                                        $theP = Produktet::find($porOne2D[7]);
                                        $theTP = Takeaway::where('prod_id',$porOne2D[7])->first();
                                    }
                                    if($theTP != NULL){ 
                                        if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                            $mwstPRC = number_format($hiTvsh, 9, '.', ''); 
                                        }else{
                                            $mwstPRC = number_format($loTvsh, 9, '.', ''); 
                                        }
                                    }else{ $mwstPRC = number_format((float)$loTvsh, 9, '.', ''); }
                                    if($orOne->inCashDiscount > 0){
                                        $totZbritja = number_format($orOne->inCashDiscount, 2, '.', '');
                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                        $mwstMitRabatRechnung += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                    }else if($orOne->inPercentageDiscount > 0){
                                        $totZbritjaPrc = number_format($orOne->inPercentageDiscount / 100, 2, '.', '');
                                        $cal1 = number_format($porOne2D[4] * $totZbritjaPrc, 9, '.', '');
                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                        $mwstMitRabatRechnung += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                    }else if($orOne->cuponOffVal > 0){
                                        $totZbritja = number_format($orOne->cuponOffVal, 2, '.', '');
                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                        $mwstMitRabatRechnung += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                    }else{
                                        $mwstMitRabatRechnung += number_format((float)$porOne2D[4] * $mwstPRC,9,'.','');
                                    }
                                    $mwst = number_format((float)$porOne2D[4]*$mwstPRC, 9, '.', '');
                                    $bruto = number_format((float)$porOne2D[4], 9, '.', '');
                                    $neto = number_format((float)$porOne2D[4]-$mwst, 9, '.', '');

                                    if(isset($porOne2D[8])){
                                        $repGr = $porOne2D[8];
                                    }else{
                                        if($theTP == NULL){$repGr = 0;}else{ $repGr = $theTP->toReportCat; }
                                    }
                                    if(in_array($repGr,$prodGroups)){
                                        // gr already registred
                                        $grSasia[$repGr] += number_format((int)$porOne2D[3], 0, '.', '');
                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');
                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                        }else{
                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                            }else{
                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                            }
                                        }
                                        if($grMstPerc[$repGr] != '2.50 / 7.70'){
                                            if($grMstPerc[$repGr] == 0){
                                                $grMstPerc[$repGr] = $ThisMwStVal ;
                                            }else{
                                                if($grMstPerc[$repGr] != $ThisMwStVal ){
                                                    $grMstPerc[$repGr] = '2.50 / 7.70';
                                                }
                                            }
                                        }
                                    }else{
                                        // new gr
                                        array_push($prodGroups,$repGr);
                                        $grSasia[$repGr] = number_format((int)$porOne2D[3], 0, '.', '');
                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');

                                        if($theTP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                        }else{
                                            if($theTP->mwstForPro == 7.70 || $theTP->mwstForPro == 8.10){
                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                            }else{
                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                            }
                                        }
                                        $grMstPerc[$repGr] = $ThisMwStVal ;
                                    }
                                    $totInCHF += number_format((float)$porOne2D[4], 9, '.', '');
                                ?>
                            @endforeach
                            <?php
                                if($orOne->inCashDiscount > 0){$totDiscount += number_format($orOne->inCashDiscount, 9, '.', '');
                                }else if($orOne->inPercentageDiscount > 0){
                                    $totDiscount += number_format(($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01), 9, '.', '');
                                }
                                if($orOne->cuponOffVal > 0){
                                    $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                }
                                $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                            ?>
                        @endforeach
                        <!-- llogariten perqindjet ANTEIL per grupacione -->
                        @foreach ($prodGroups as $key) 
                            <?php
                                $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                if($totInCHF > 0){
                                    $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                    $grAnteil[$key] = $percVal;
                                }else{
                                    $grAnteil[$key] = number_format(100, 9, '.', '');
                                }
                            ?>
                        @endforeach
                        <!-- Shfaq produktet e grupuara -->
                        <?php
                            $sumSasia = number_format(0, 0, '.', '');
                            $sumBruto = number_format(0, 9, '.', '');
                            $sumMwst = number_format(0, 9, '.', '');
                            $sumNeto = number_format(0, 9, '.', '');
                        ?>
                        @foreach ($prodGroups as $key) 
                            <tr>
                                <td style="text-align: left;">{{number_format($grSasia[$key], 0, '.', '')}} X</td>
                                <td style="text-align: left;">
                                <?php
                                    if($grMstPerc[$key] == number_format((float)$hiTvsh, 2, '.', '')){
                                        if($theYr <= 2023){ $showMwstPerc = 7.70; }else{ $showMwstPerc = 8.10; }
                                    }else if($grMstPerc[$key] == number_format((float)$loTvsh, 2, '.', '')){ 
                                        if($theYr <= 2023){ $showMwstPerc = 2.50; }else{ $showMwstPerc = 2.60; }
                                    }else{
                                        if($theYr <= 2023){ $showMwstPerc = '2.50 / 7.70'; }else{ $showMwstPerc = '2.60 / 8.10'; }
                                    }
                                ?>
                                @if ($key == 0)
                                    nicht kategorisiert (MWST {{$showMwstPerc}} %)
                                @else
                                    {{pdfResProdCats::findOrFail($key)->catTitle}} (MWST {{$showMwstPerc}} %)
                                @endif
                                </td>
                                <td style="text-align: right;">CHF {{number_format($grBruto[$key], 2, '.', '')}}</td>
                                <td style="text-align: right;">CHF {{number_format($grMwst[$key], 2, '.', '')}}</td>
                                <td style="text-align: right;">CHF {{number_format($grNeto[$key], 2, '.', '')}}</td>

                                <td style="text-align: right;">
                                    {{number_format($grAnteil[$key], 2, '.', '')}} %
                                </td>
                                <?php
                                    $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                    $sumBruto += number_format($grBruto[$key], 2, '.', '');
                                    $sumMwst += number_format($grMwst[$key], 2, '.', '');
                                    $sumNeto += number_format($grNeto[$key], 2, '.', '');
                                ?>
                            </tr>
                        @endforeach
                        <?php
                            $totTakSasia += number_format($sumSasia, 0, '.', '');
                        ?>
                            <tr class="heading">
                                <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                <td style="text-align: left;"><strong></strong></td>
                                <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                <td style="text-align: right;"><strong>100 % </strong></td>
                            </tr>
                        @if ( $totDiscount > 0 )
                            <tr>
                                <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                            </tr>
                        @endif
                        @if ( $totDiscount > 0)
                            <?php
                                $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                // $mwstAfterD = number_format($brutoAfterD *0.025341130, 9, '.', '');
                                $netoAfterD = number_format($brutoAfterD - $mwstMitRabatRechnung, 9, '.', '');

                                $totTakBr += number_format($brutoAfterD, 9, '.', '');
                                $totTakMwst += number_format($mwstMitRabatRechnung, 9, '.', '');
                                $totTakNe += number_format($netoAfterD, 9, '.', '');
                            ?>
                            <tr class="heading">   
                                <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                <td style="text-align: right;"><strong>CHF {{number_format($mwstMitRabatRechnung, 2, '.', '')}}</strong></td>
                                <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                <td style="text-align: right;"><strong></strong></td>
                            </tr>
                        @else
                            <?php
                                $totTakBr += number_format($sumBruto, 9, '.', '');
                                $totTakMwst += number_format($sumMwst, 9, '.', '');
                                $totTakNe += number_format($sumNeto, 9, '.', '');
                            ?>
                        @endif
                    @endif






                    @if($totTakSasia > 0)
                    <tr><td colspan="6" style="color:white;">.</td></tr>
                    <tr class="heading">
                        <td colspan="2" style="text-align: left; background: #6e6e6e !important; color:white;"><strong> Summen für Takeaway </strong></td>
                        <td style="text-align: right; background: #6e6e6e !important; color:white;"><strong>CHF {{number_format($totTakBr, 2, '.', '')}}</strong></td>
                        <td style="text-align: right; background: #6e6e6e !important; color:white;"><strong>CHF {{number_format($totTakMwst, 2, '.', '')}}</strong></td>
                        <td style="text-align: right; background: #6e6e6e !important; color:white;"><strong>CHF {{number_format($totTakNe, 2, '.', '')}}</strong></td>
                        <td style="text-align: right; background: #6e6e6e !important; color:white;"><strong>{{number_format($totTakSasia, 0, '.', '')}} X</strong></td>
                    </tr>
                    @endif












































                    <?php
                        $totDelBr = number_format(0, 9, '.', '');
                        $totDelMwst = number_format(0, 9, '.', '');
                        $totDelNe = number_format(0, 9, '.', '');
                        $totDelSasia = number_format(0, 0, '.', '');
                    ?>
                    <?php $showTable = False; ?>              
                        @foreach ($ordersDelivery as $orOne)
                            @if ($orOne->payM == "Barzahlungen" || $orOne->payM == "Cash")
                                <?php $showTable = True; ?>
                                @break
                            @endif
                        @endforeach
                        @if ($showTable)
                                    <tr><td colspan="6" style="color:white;">.</td></tr>
                                    <tr><td colspan="6"><strong>Lieferung (Bar)</strong></td></tr>
                                    <tr class="heading" style="margin-top: 0.25cm;">
                                        <td style="text-align: left;"><strong>Stk.</strong></td>
                                        <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                                        <td style="text-align: right;"><strong>Brutto</strong></td>
                                        <td style="text-align: right;"><strong>MWST</strong></td>
                                        <td style="text-align: right;"><strong>Netto</strong></td>
                                        <td style="text-align: right;"><strong>%-Anteil </strong></td>
                                    </tr>
                                    <?php
                                        $prodGroups = array();
                                        $grSasia = array();
                                        $grBruto = array();
                                        $grMwst = array();
                                        $grNeto = array();
                                        $grAnteil = array();
                                        $grMstPerc= array();
                                        $totInCHF = number_format(0, 9, '.', '');
                                        $totDiscount = number_format(0, 9, '.', '');
                                        $totDiscountGC = number_format(0, 9, '.', '');
                                    ?>
                                    <!-- Grupimi i produkteve -->
                                    @foreach ($ordersDelivery as $orOne)
                                        @if ($orOne->payM == "Barzahlungen" || $orOne->payM == "Cash")
                                            <!-- loop through products array -->
                                            <?php
                                                $totFromProductePrice = number_format(0, 2, '.', '');
                                                foreach(explode('---8---',$orOne) as $produkti){
                                                    $prod = explode('-8-', $produkti);
                                                    $totFromProductePrice += number_format($prod[4], 2, '.', '');
                                                }
                                            ?>
                                            @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                                <?php 

                                                    $porOne2D = explode('-8-',$porOne); 
                                                    if($orOne->userPhoneNr != '0770000000'){
                                                        $theP = DeliveryProd::find($porOne2D[7]);
                                                        $theDP = DeliveryProd::find($porOne2D[7]);
                                                    }else{
                                                        $theP = Produktet::find($porOne2D[7]);
                                                        $theDP = DeliveryProd::where('prod_id',$porOne2D[7])->first();
                                                    }

                                                    if($theDP != NULL){ 
                                                        if($theDP->mwstForPro == 7.70 || $theDP->mwstForPro == 8.10){
                                                            $mwstPRC = number_format($hiTvsh, 9, '.', ''); 
                                                        }else{
                                                            $mwstPRC = number_format($loTvsh, 9, '.', ''); 
                                                        }
                                                    }else{ $mwstPRC = number_format((float)$loTvsh, 9, '.', ''); }

                                                    if($orOne->inCashDiscount > 0){
                                                        $totZbritja = number_format($orOne->inCashDiscount, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatDeCash += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->inPercentageDiscount > 0){
                                                        $totZbritjaPrc = number_format($orOne->inPercentageDiscount / 100, 2, '.', '');
                                                        $cal1 = number_format($porOne2D[4] * $totZbritjaPrc, 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatDeCash += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else{
                                                        $mwstMitRabatDeCash += number_format((float)$porOne2D[4] * $mwstPRC,9,'.','');
                                                    }

                                                    $mwst = number_format($porOne2D[4]*$mwstPRC, 9, '.', '');
                                                    $bruto = number_format($porOne2D[4], 9, '.', '');
                                                    $neto = number_format($porOne2D[4]-$mwst, 9, '.', '');

                                                    if(isset($porOne2D[8])){
                                                        $repGr = $porOne2D[8];
                                                    }else{
                                                        if($theDP == NULL){$repGr = 0;}else{ $repGr = $theDP->toReportCat; }
                                                    }
                                                    
                                                    if(in_array($repGr,$prodGroups)){
                                                        // gr already registred
                                                        $grSasia[$repGr] += number_format($porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');

                                                        if($theDP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 9, '.', '');
                                                        }else{
                                                            if($theDP->mwstForPro == 7.70 || $theDP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                            }else{
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                            }
                                                        }
                                                        if($grMstPerc[$repGr] != '2.50 / 7.70'){
                                                            if($grMstPerc[$repGr] == 0){
                                                                $grMstPerc[$repGr] = $ThisMwStVal ;
                                                            }else{
                                                                if($grMstPerc[$repGr] != $ThisMwStVal ){
                                                                    $grMstPerc[$repGr] = '2.50 / 7.70';
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        // new gr
                                                        array_push($prodGroups,$repGr);
                                                        $grSasia[$repGr] = number_format($porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');

                                                        if($theDP == NULL){$ThisMwStVal = number_format($loTvsh, 9, '.', '');
                                                        }else{
                                                            if($theDP->mwstForPro == 7.70 || $theDP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format($hiTvsh, 9, '.', '');
                                                            }else{
                                                                $ThisMwStVal = number_format($loTvsh, 9, '.', '');
                                                            }   
                                                        }
                                                        $grMstPerc[$repGr] = $ThisMwStVal ;
                                                    }
                                                    $totInCHF += number_format($porOne2D[4], 9, '.', '');
                                                ?>
                                            @endforeach
                                            <?php
                                                if($orOne->inCashDiscount > 0){
                                                    $totDiscount += number_format($orOne->inCashDiscount, 9, '.', '');
                                                }else if($orOne->inPercentageDiscount > 0){
                                                    $totDiscount += number_format(($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01), 9, '.', '');
                                                }

                                                if($orOne->cuponOffVal > 0){
                                                    $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                                }
                                                $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                            ?>
                                        @endif
                                    @endforeach

                                    <!-- llogariten perqindjet ANTEIL per grupacione -->
                                    @foreach ($prodGroups as $key) 
                                        <?php
                                            $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                            if($totInCHF > 0){
                                                $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                                $grAnteil[$key] = $percVal;
                                            }else{
                                                $grAnteil[$key] = number_format(100, 9, '.', '');
                                            }
                                        ?>
                                    @endforeach
                                    <!-- Shfaq produktet e grupuara -->
                                    <?php
                                        $sumSasia = number_format(0, 0, '.', '');
                                        $sumBruto = number_format(0, 9, '.', '');
                                        $sumMwst = number_format(0, 9, '.', '');
                                        $sumNeto = number_format(0, 9, '.', '');
                                    ?>
                                    @foreach ($prodGroups as $key) 
                                        <tr>
                                            <td style="text-align: left;">
                                                {{ number_format($grSasia[$key], 0, '.', '')}} X
                                            </td>
                                            <td style="text-align: left;">
                                            <?php
                                                if($grMstPerc[$key] == number_format((float)$hiTvsh, 2, '.', '')){
                                                    if($theYr <= 2023){ $showMwstPerc = 7.70; }else{ $showMwstPerc = 8.10; }
                                                }else if($grMstPerc[$key] == number_format((float)$loTvsh, 2, '.', '')){ 
                                                    if($theYr <= 2023){ $showMwstPerc = 2.50; }else{ $showMwstPerc = 2.60; }
                                                }else{
                                                    if($theYr <= 2023){ $showMwstPerc = '2.50 / 7.70'; }else{ $showMwstPerc = '2.60 / 8.10'; }
                                                }
                                            ?>
                                            @if ($key == 0)
                                                nicht kategorisiert (MWST {{$showMwstPerc}} %)
                                            @else
                                                {{pdfResProdCats::findOrFail($key)->catTitle}} (MWST {{$showMwstPerc}} %)
                                            @endif
                                            </td>
                                            <td style="text-align: right;">CHF {{ number_format($grBruto[$key], 2, '.', '')}}</td>
                                            <td style="text-align: right;">CHF {{ number_format($grMwst[$key], 2, '.', '')}}</td>
                                            <td style="text-align: right;">CHF {{ number_format($grNeto[$key], 2, '.', '')}}</td>
                                            <td style="text-align: right;">{{ number_format($grAnteil[$key], 2, '.', '')}} %</td>
                                            <?php
                                                $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                                $sumBruto += number_format($grBruto[$key], 9, '.', '');
                                                $sumMwst += number_format($grMwst[$key], 9, '.', '');
                                                $sumNeto += number_format($grNeto[$key], 9, '.', '');
                                            ?>
                                        </tr>
                                    @endforeach
                                    <?php $totDelSasia += number_format($sumSasia, 0, '.', ''); ?>
                                        <tr class="heading">
                                            <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                            <td style="text-align: left;"><strong></strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>100 % </strong></td>
                                        </tr>
                                    @if ( $totDiscount > 0 )
                                        <tr>
                                            <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                                        </tr>
                                    @endif
                                    @if ( $totDiscountGC > 0 )
                                        <tr>
                                            <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                                        </tr>
                                    @endif
                                    @if ( $totDiscount > 0)
                                        <?php
                                            $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                            // $mwstAfterD = number_format($brutoAfterD *0.025341130, 9, '.', '');
                                            $netoAfterD = number_format($brutoAfterD - $mwstMitRabatDeCash, 9, '.', '');

                                            $totDelBr += number_format($brutoAfterD, 9, '.', '');
                                            $totDelMwst += number_format($mwstMitRabatDeCash, 9, '.', '');
                                            $totDelNe += number_format($netoAfterD, 9, '.', '');
                                        ?>
                                        <tr class="heading">
                                            <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($mwstMitRabatDeCash, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong></strong></td>
                                        </tr>
                                    @else
                                        <?php
                                            $totDelBr += number_format($sumBruto, 9, '.', '4');
                                            $totDelMwst += number_format($sumMwst, 9, '.', '4');
                                            $totDelNe += number_format($sumNeto, 9, '.', '4');
                                        ?>
                                    @endif
                        @endif


















                        <?php $showTable = False; ?>              
                        @foreach ($ordersDelivery as $orOne)
                            @if ($orOne->payM == "Kartenzahlung")
                                <?php $showTable = True; ?>
                                @break
                            @endif
                        @endforeach
                        @if ($showTable)
                                    <tr><td colspan="6" style="color:white;">.</td></tr>
                                    <tr><td colspan="6"><strong>Lieferung (Karte)</strong></td></tr>
                                    <tr class="heading" style="margin-top: 0.25cm;">
                                        <td style="text-align: left;"><strong>Stk.</strong></td>
                                        <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                                        <td style="text-align: right;"><strong>Brutto</strong></td>
                                        <td style="text-align: right;"><strong>MWST</strong></td>
                                        <td style="text-align: right;"><strong>Netto</strong></td>
                                        <td style="text-align: right;"><strong>%-Anteil </strong></td>
                                    </tr>
                                    <?php
                                        $prodGroups = array();
                                        $grSasia = array();
                                        $grBruto = array();
                                        $grMwst = array();
                                        $grNeto = array();
                                        $grAnteil = array();
                                        $grMstPerc= array();
                                        $totInCHF = number_format(0, 9, '.', '');
                                        $totDiscount = number_format(0, 9, '.', '');
                                        $mwstMitRabatDeCard = number_format(0, 9, '.', '');
                                        $totDiscountGC = number_format(0, 9, '.', '');
                                    ?>
                                    <!-- Grupimi i produkteve -->
                                    @foreach ($ordersDelivery as $orOne)
                                        @if ($orOne->payM == "Kartenzahlung" || $orOne->payM == "Online")
                                            <!-- loop through products array -->
                                            <?php
                                                $totFromProductePrice = number_format(0, 2, '.', '');
                                                foreach(explode('---8---',$orOne) as $produkti){
                                                    $prod = explode('-8-', $produkti);
                                                    $totFromProductePrice += number_format($prod[4], 2, '.', '');
                                                }
                                            ?>
                                            @foreach (explode('---8---',$orOne->porosia) as $porOne)
                                                <?php 

                                                    $porOne2D = explode('-8-',$porOne); 
                                                    if($orOne->userPhoneNr != '0770000000'){
                                                        $theP = DeliveryProd::find($porOne2D[7]);
                                                        $theDP = DeliveryProd::find($porOne2D[7]);
                                                    }else{
                                                        $theP = Produktet::find($porOne2D[7]);
                                                        $theDP = DeliveryProd::where('prod_id',$porOne2D[7])->first();
                                                    }

                                                    if($theDP != NULL){ 
                                                        if($theDP->mwstForPro == 7.70 || $theDP->mwstForPro == 8.10){
                                                            $mwstPRC = number_format($hiTvsh, 9, '.', ''); 
                                                        }else{
                                                            $mwstPRC = number_format($loTvsh, 9, '.', ''); 
                                                        }
                                                    }else{ $mwstPRC = number_format((float)$loTvsh, 9, '.', ''); }

                                                    if($orOne->inCashDiscount > 0){
                                                        $totZbritja = number_format($orOne->inCashDiscount, 2, '.', '');
                                                        $cal1 = number_format(($porOne2D[4] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatDeCard += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else if($orOne->inPercentageDiscount > 0){
                                                        $totZbritjaPrc = number_format($orOne->inPercentageDiscount / 100, 2, '.', '');
                                                        $cal1 = number_format($porOne2D[4] * $totZbritjaPrc, 9, '.', '');
                                                        $cal2 = number_format($porOne2D[4] - $cal1, 9, '.', '');
                                                        $mwstMitRabatDeCard += number_format((float)$cal2 * $mwstPRC,9,'.','');
                                                    }else{
                                                        $mwstMitRabatDeCard += number_format((float)$porOne2D[4] * $mwstPRC,9,'.','');
                                                    }

                                                    $mwst = number_format($porOne2D[4]*$mwstPRC, 9, '.', '');
                                                    $bruto = number_format($porOne2D[4], 9, '.', '');
                                                    $neto = number_format($porOne2D[4]-$mwst, 9, '.', '');

                                                    if(isset($porOne2D[8])){
                                                        $repGr = $porOne2D[8];
                                                    }else{
                                                        if($theDP == NULL){$repGr = 0;}else{ $repGr = $theDP->toReportCat; }
                                                    }

                                                    if(in_array($repGr,$prodGroups)){
                                                        // gr already registred
                                                        $grSasia[$repGr] += number_format($porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] += number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] += number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] += number_format($neto, 9, '.', '');

                                                        if($theDP == NULL){$ThisMwStVal = number_format((float)$loTvsh, 9, '.', '');
                                                        }else{
                                                            if($theDP->mwstForPro == 7.70 || $theDP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format((float)$hiTvsh, 2, '.', '');
                                                            }else{
                                                                $ThisMwStVal = number_format((float)$loTvsh, 2, '.', '');
                                                            }
                                                        }
                                                        if($grMstPerc[$repGr] != '2.50 / 7.70'){
                                                            if($grMstPerc[$repGr] == 0){
                                                                $grMstPerc[$repGr] = $ThisMwStVal ;
                                                            }else{
                                                                if($grMstPerc[$repGr] != $ThisMwStVal ){
                                                                    $grMstPerc[$repGr] = '2.50 / 7.70';
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        // new gr
                                                        array_push($prodGroups,$repGr);
                                                        $grSasia[$repGr] = number_format($porOne2D[3], 0, '.', '');
                                                        $grBruto[$repGr] = number_format($bruto, 9, '.', '');
                                                        $grMwst[$repGr] = number_format($mwst, 9, '.', '');
                                                        $grNeto[$repGr] = number_format($neto, 9, '.', '');

                                                        if($theDP == NULL){$ThisMwStVal = number_format($loTvsh, 9, '.', '');
                                                        }else{
                                                            if($theDP->mwstForPro == 7.70 || $theDP->mwstForPro == 8.10){
                                                                $ThisMwStVal = number_format($hiTvsh, 9, '.', '');
                                                            }else{
                                                                $ThisMwStVal = number_format($loTvsh, 9, '.', '');
                                                            }   
                                                        }
                                                        $grMstPerc[$repGr] = $ThisMwStVal ;
                                                    }
                                                    $totInCHF += number_format($porOne2D[4], 9, '.', '');
                                                ?>
                                            @endforeach
                                            <?php
                                                if($orOne->inCashDiscount > 0){
                                                    $totDiscount += number_format($orOne->inCashDiscount, 9, '.', '');
                                                }else if($orOne->inPercentageDiscount > 0){
                                                    $totDiscount += number_format(($orOne->shuma - $orOne->tipPer)*($orOne->inPercentageDiscount * 0.01), 9, '.', '');
                                                }

                                                if($orOne->cuponOffVal > 0){
                                                    $totDiscount += number_format($orOne->cuponOffVal, 9, '.', '');
                                                }

                                                $totDiscountGC += number_format($orOne->dicsountGcAmnt, 9, '.', '');
                                            ?>
                                        @endif
                                    @endforeach

                                    <!-- llogariten perqindjet ANTEIL per grupacione -->
                                    @foreach ($prodGroups as $key) 
                                        <?php
                                            $theBrutoVal = number_format($grBruto[$key], 9, '.', '');
                                            if($totInCHF > 0){
                                                $percVal = number_format((($theBrutoVal / $totInCHF) * 100), 9, '.', '');
                                                $grAnteil[$key] = $percVal;
                                            }else{
                                                $grAnteil[$key] = number_format(100, 9, '.', '');
                                            }
                                        ?>
                                    @endforeach
                                    <!-- Shfaq produktet e grupuara -->
                                    <?php
                                        $sumSasia = number_format(0, 0, '.', '');
                                        $sumBruto = number_format(0, 9, '.', '');
                                        $sumMwst = number_format(0, 9, '.', '');
                                        $sumNeto = number_format(0, 9, '.', '');
                                    ?>
                                    @foreach ($prodGroups as $key) 
                                        <tr>
                                            <td style="text-align: left;">
                                                {{ number_format($grSasia[$key], 0, '.', '')}} X
                                            </td>
                                            <td style="text-align: left;">
                                            <?php
                                                if($grMstPerc[$key] == number_format((float)$hiTvsh, 2, '.', '')){
                                                    if($theYr <= 2023){ $showMwstPerc = 7.70; }else{ $showMwstPerc = 8.10; }
                                                }else if($grMstPerc[$key] == number_format((float)$loTvsh, 2, '.', '')){ 
                                                    if($theYr <= 2023){ $showMwstPerc = 2.50; }else{ $showMwstPerc = 2.60; }
                                                }else{
                                                    if($theYr <= 2023){ $showMwstPerc = '2.50 / 7.70'; }else{ $showMwstPerc = '2.60 / 8.10'; }
                                                }
                                            ?>
                                            @if ($key == 0)
                                                nicht kategorisiert (MWST {{$showMwstPerc}} %)
                                            @else
                                                {{pdfResProdCats::findOrFail($key)->catTitle}} (MWST {{$showMwstPerc}} %)
                                            @endif
                                            </td>
                                            <td style="text-align: right;">CHF {{ number_format($grBruto[$key], 2, '.', '')}}</td>
                                            <td style="text-align: right;">CHF {{ number_format($grMwst[$key], 2, '.', '')}}</td>
                                            <td style="text-align: right;">CHF {{ number_format($grNeto[$key], 2, '.', '')}}</td>
                                            <td style="text-align: right;">{{ number_format($grAnteil[$key], 2, '.', '')}} %</td>
                                            <?php
                                                $sumSasia += number_format($grSasia[$key], 0, '.', '');
                                                $sumBruto += number_format($grBruto[$key], 9, '.', '');
                                                $sumMwst += number_format($grMwst[$key], 9, '.', '');
                                                $sumNeto += number_format($grNeto[$key], 9, '.', '');
                                            ?>
                                        </tr>
                                    @endforeach
                                    <?php $totDelSasia += number_format($sumSasia, 0, '.', ''); ?>
                                        <tr class="heading">
                                            <td style="text-align: left;"><strong>{{number_format($sumSasia, 0, '.', '')}} X</strong></td>
                                            <td style="text-align: left;"><strong></strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($sumBruto, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($sumMwst, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($sumNeto, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>100 % </strong></td>
                                        </tr>
                                    @if ( $totDiscount > 0 )
                                        <tr>
                                            <td colspan="6"><strong>Rabatte und Gutscheine: {{number_format($totDiscount, 2, '.', '')}} CHF</strong></td>
                                        </tr>
                                    @endif
                                    @if ( $totDiscountGC > 0 )
                                        <tr>
                                            <td colspan="6"><strong>Geschenkkarte: {{number_format($totDiscountGC, 2, '.', '')}} CHF</strong></td>
                                        </tr>
                                    @endif
                                    @if ( $totDiscount > 0)
                                        <?php
                                            $brutoAfterD = number_format($sumBruto - $totDiscount, 9, '.', '');
                                            // $mwstAfterD = number_format($brutoAfterD *0.025341130, 9, '.', '');
                                            $netoAfterD = number_format($brutoAfterD - $mwstMitRabatDeCard, 9, '.', '');

                                            $totDelBr += number_format($brutoAfterD, 9, '.', '');
                                            $totDelMwst += number_format($mwstMitRabatDeCard, 9, '.', '');
                                            $totDelNe += number_format($netoAfterD, 9, '.', '');
                                        ?>
                                        <tr class="heading">
                                            <td colspan="2" style="text-align: left;"><strong> Nach Abzug von Rabatten </strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($brutoAfterD, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($mwstMitRabatDeCard, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong>CHF {{number_format($netoAfterD, 2, '.', '')}}</strong></td>
                                            <td style="text-align: right;"><strong></strong></td>
                                        </tr>
                                    @else
                                        <?php
                                            $totDelBr += number_format($sumBruto, 9, '.', '4');
                                            $totDelMwst += number_format($sumMwst, 9, '.', '4');
                                            $totDelNe += number_format($sumNeto, 9, '.', '4');
                                        ?>
                                    @endif
                        @endif














                    <?php
                        $gcTotInCHFAll = number_format(0, 9, '.', '');
                        $gcSasiaAll = number_format(0, 0, '.', '');
                    ?>

                    @if (count($gcSalesCash) > 0)
                        <tr> <td colspan="6" style="color:white">.</td></tr>
                        <tr><td colspan="6"><strong>Verkaufte Geschenkkarten (Bar)</strong></td></tr>

                        <tr class="heading" style="margin-top: 0.25cm;">
                            <td style="text-align: left;"><strong>Stk.</strong></td>
                            <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                            <td style="text-align: right;"><strong>Brutto</strong></td>
                            <td style="text-align: right;"><strong>MWST 0%</strong></td>
                            <td style="text-align: right;"><strong>Netto</strong></td>
                            <td></td>
                        </tr>
                        <?php
                            $gcSasia = number_format(0, 0, '.', '');
                            $gcBrutoNeto = number_format(0, 9, '.', '');
                            $gcTotInCHF = number_format(0, 9, '.', '');
                        ?>
                        <!-- Grupimi i produkteve -->
                        @foreach ($gcSalesCash as $gcCashOne)
                            <?php
                                $gcSasia += number_format(1, 0, '.', '');
                                $gcBrutoNeto += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                                $gcTotInCHF += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                                $gcSasiaAll += number_format(1, 0, '.', '');
                                $gcTotInCHFAll += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                            ?>
                        @endforeach
                        <tr>
                            <td style="text-align: left;">{{number_format($gcSasia, 0, '.', '')}} X</td>
                            <td style="text-align: left;">Geschenkkarten</td>
                            <td style="text-align: right;">CHF {{number_format($gcBrutoNeto, 2, '.', '')}}</td>
                            <td style="text-align: right;">CHF {{number_format(0, 2, '.', '')}}</td>
                            <td style="text-align: right;">CHF {{number_format($gcBrutoNeto, 2, '.', '')}}</td>
                            <td></td>
                        </tr>
                    @endif


                    @if (count($gcSalesCard) > 0)
                        <tr> <td colspan="6" style="color:white">.</td></tr>
                        <tr><td colspan="6"><strong>Verkaufte Geschenkkarten (Karte)</strong></td></tr>

                        <tr class="heading" style="margin-top: 0.25cm;">
                            <td style="text-align: left;"><strong>Stk.</strong></td>
                            <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                            <td style="text-align: right;"><strong>Brutto</strong></td>
                            <td style="text-align: right;"><strong>MWST 0%</strong></td>
                            <td style="text-align: right;"><strong>Netto</strong></td>
                            <td></td>
                        </tr>
                        <?php
                            $gcSasia = number_format(0, 0, '.', '');
                            $gcBrutoNeto = number_format(0, 9, '.', '');
                            $gcTotInCHF = number_format(0, 9, '.', '');
                        ?>
                        <!-- Grupimi i produkteve -->
                        @foreach ($gcSalesCard as $gcCashOne)
                            <?php
                                $gcSasia += number_format(1, 0, '.', '');
                                $gcBrutoNeto += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                                $gcTotInCHF += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                                $gcSasiaAll += number_format(1, 0, '.', '');
                                $gcTotInCHFAll += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                            ?>
                        @endforeach
                        <tr>
                            <td style="text-align: left;">{{number_format($gcSasia, 0, '.', '')}} X</td>
                            <td style="text-align: left;">Geschenkkarten</td>
                            <td style="text-align: right;">CHF {{number_format($gcBrutoNeto, 2, '.', '')}}</td>
                            <td style="text-align: right;">CHF {{number_format(0, 2, '.', '')}}</td>
                            <td style="text-align: right;">CHF {{number_format($gcBrutoNeto, 2, '.', '')}}</td>
                            <td></td>
                        </tr>
                    @endif


                    @if (count($gcSalesOnline) > 0)
                        <tr> <td colspan="6" style="color:white">.</td></tr>
                        <tr><td colspan="6"><strong>Verkaufte Geschenkkarten (Online)</strong></td></tr>

                        <tr class="heading" style="margin-top: 0.25cm;">
                            <td style="text-align: left;"><strong>Stk.</strong></td>
                            <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                            <td style="text-align: right;"><strong>Brutto</strong></td>
                            <td style="text-align: right;"><strong>MWST 0%</strong></td>
                            <td style="text-align: right;"><strong>Netto</strong></td>
                            <td></td>
                        </tr>
                        <?php
                            $gcSasia = number_format(0, 0, '.', '');
                            $gcBrutoNeto = number_format(0, 9, '.', '');
                            $gcTotInCHF = number_format(0, 9, '.', '');
                        ?>
                        <!-- Grupimi i produkteve -->
                        @foreach ($gcSalesOnline as $gcCashOne)
                            <?php
                                $gcSasia += number_format(1, 0, '.', '');
                                $gcBrutoNeto += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                                $gcTotInCHF += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                                $gcSasiaAll += number_format(1, 0, '.', '');
                                $gcTotInCHFAll += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                            ?>
                        @endforeach
                        <tr>
                            <td style="text-align: left;">{{number_format($gcSasia, 0, '.', '')}} X</td>
                            <td style="text-align: left;">Geschenkkarten</td>
                            <td style="text-align: right;">CHF {{number_format($gcBrutoNeto, 2, '.', '')}}</td>
                            <td style="text-align: right;">CHF {{number_format(0, 2, '.', '')}}</td>
                            <td style="text-align: right;">CHF {{number_format($gcBrutoNeto, 2, '.', '')}}</td>
                            <td></td>
                        </tr>
                    @endif



                    @if (count($gcSalesRechnung) > 0)
                        <tr> <td colspan="6" style="color:white">.</td></tr>
                        <tr><td colspan="6"><strong>Verkaufte Geschenkkarten (Auf rechnung)</strong></td></tr>

                        <tr class="heading" style="margin-top: 0.25cm;">
                            <td style="text-align: left;"><strong>Stk.</strong></td>
                            <td style="text-align: left;"><strong>Hauptkategorien</strong></td>
                            <td style="text-align: right;"><strong>Brutto</strong></td>
                            <td style="text-align: right;"><strong>MWST 0%</strong></td>
                            <td style="text-align: right;"><strong>Netto</strong></td>
                            <td></td>
                        </tr>
                        <?php
                            $gcSasia = number_format(0, 0, '.', '');
                            $gcBrutoNeto = number_format(0, 9, '.', '');
                            $gcTotInCHF = number_format(0, 9, '.', '');
                        ?>
                        <!-- Grupimi i produkteve -->
                        @foreach ($gcSalesRechnung as $gcCashOne)
                            <?php
                                $gcSasia += number_format(1, 0, '.', '');
                                $gcBrutoNeto += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                                $gcTotInCHF += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                                $gcSasiaAll += number_format(1, 0, '.', '');
                                $gcTotInCHFAll += number_format($gcCashOne->gcSumInChf, 9, '.', '');
                            ?>
                        @endforeach
                        <tr>
                            <td style="text-align: left;">{{number_format($gcSasia, 0, '.', '')}} X</td>
                            <td style="text-align: left;">Geschenkkarten</td>
                            <td style="text-align: right;">CHF {{number_format($gcBrutoNeto, 2, '.', '')}}</td>
                            <td style="text-align: right;">CHF {{number_format(0, 2, '.', '')}}</td>
                            <td style="text-align: right;">CHF {{number_format($gcBrutoNeto, 2, '.', '')}}</td>
                            <td></td>
                        </tr>
                    @endif



                    @if($gcSasiaAll > 0)
                        <tr> <td colspan="6" style="color:white;">.</td></tr>
                        <tr class="heading">
                            <td colspan="2" style="text-align: left; background: #6e6e6e !important; color:white;">
                                <strong> Summen für Geschenkkarten </strong>
                            </td>
                            <td style="text-align: right; background: #6e6e6e !important; color:white;">
                                <strong>CHF {{number_format($gcTotInCHFAll, 2, '.', '')}}</strong>
                            </td>
                            <td style="text-align: right; background: #6e6e6e !important; color:white;">
                                <strong>CHF {{number_format(0, 2, '.', '')}}</strong>
                            </td>
                            <td style="text-align: right; background: #6e6e6e !important; color:white;">
                                <strong>CHF {{number_format($gcTotInCHFAll, 2, '.', '')}}</strong>
                            </td>
                            <td style="text-align: right; background: #6e6e6e !important; color:white;">
                                <strong>{{number_format($gcSasiaAll, 0, '.', '')}} X</strong>
                            </td>
                        </tr>
                    @endif





















                    <tr>
                        <td colspan="6" style="color:white;">.</td>
                    </tr>
                    <tr class="heading">
                        <td colspan="2" style="text-align: left; background: Black !important; color:white;">
                            <strong> Summen für den gesamten Bericht </strong>
                        </td>
                        <td style="text-align: right; background: Black !important; color:white;">
                            <strong>CHF {{number_format($totResBr + $totTakBr + $totDelBr + $gcTotInCHFAll, 2, '.', '')}}</strong>
                        </td>
                        <td style="text-align: right; background: Black !important; color:white;">
                            <strong>CHF {{number_format($totResMwst + $totTakMwst + $totDelMwst, 2, '.', '')}}</strong>
                        </td>
                        <td style="text-align: right; background: Black !important; color:white;">
                            <strong>CHF {{number_format($totResNe + $totTakNe + $totDelNe + $gcTotInCHFAll, 2, '.', '')}}</strong>
                        </td>
                        <td style="text-align: right; background: Black !important; color:white;">
                            <strong>{{number_format($totResSasia + $totTakSasia + $totDelSasia + $gcSasiaAll, 0, '.', '')}} X</strong>
                        </td>
                    </tr>
            </table>




























            <table cellpadding="0" cellspacing="0" style="margin-top:3cm;">
                <tr> <td colspan="6"><strong>Kellnerverkäufe, unterteilt in Verkaufsarten</strong></td> </tr>

                <tr class="heading">
                    <td style="text-align: center; font-size:0.7rem;"><strong>Kellner.</strong></td>
                    <td style="text-align: center; font-size:0.7rem;"><strong>Barzahlung</strong></td>
                    <td style="text-align: center; font-size:0.7rem;"><strong>Kartenzahlung</strong></td>
                    <td style="text-align: center; font-size:0.7rem;"><strong>Onlinebezahlung</strong></td>
                    <td style="text-align: center; font-size:0.7rem;"><strong>Auf Rechnung</strong></td>
                    <td style="text-align: center; font-size:0.7rem;"><strong>Geschenkkarten</strong></td>
                    <td style="text-align: center; font-size:0.7rem;"><strong>Gesamt</strong></td>

                </tr>
                <?php
                    $cashTotALL = number_format(0 , 9,'.' ,'' );
                    $cashTipsALL = number_format(0 , 9,'.' ,'' );
                    $cardTotALL = number_format(0 , 9,'.' ,'' );
                    $cardTipsALL = number_format(0 , 9,'.' ,'' );
                    $onlineTotALL = number_format(0 , 9,'.' ,'' );
                    $onlineTipsALL = number_format(0 , 9,'.' ,'' );
                    $billsTotALL = number_format(0 , 9,'.' ,'' );
                    $billsTipsALL = number_format(0 , 9,'.' ,'' );
                    $giftcardTotAll= number_format(0 , 9,'.' ,'' );
                    $gcWorkerSalesCashALL = number_format(0 , 9,'.' ,'' );
                    $gcWorkerSalesCardALL = number_format(0 , 9,'.' ,'' );
                    $gcWorkerSalesOnlineALL = number_format(0 , 9,'.' ,'' );
                    $gcWorkerSalesRechnungALL = number_format(0 , 9,'.' ,'' );

                    $allOrid = array();
                ?>
                @foreach (User::where([['sFor',explode('--77--',$teDh)[4]],['role','55']])->orWhere([['sFor',explode('--77--',$teDh)[4]],['role','5']])->get() as $waOne)
                    <?php
                        $cashTot = number_format(0 , 9,'.' ,'' );
                        $cashTips = number_format(0 , 9,'.' ,'' );
                        $cardTot = number_format(0 , 9,'.' ,'' );
                        $cardTips = number_format(0 , 9,'.' ,'' );
                        $onlineTot = number_format(0 , 9,'.' ,'' );
                        $onlineTips = number_format(0 , 9,'.' ,'' );
                        $billsTot = number_format(0 , 9,'.' ,'' );
                        $billsTips = number_format(0 , 9,'.' ,'' );
                        $giftcardTot = number_format(0 , 9,'.' ,'' );

                        $gcWorkerSalesCash = number_format(0 , 9,'.' ,'' );
                        $gcWorkerSalesCard = number_format(0 , 9,'.' ,'' );
                        $gcWorkerSalesOnline = number_format(0 , 9,'.' ,'' );
                        $gcWorkerSalesRechnung = number_format(0 , 9,'.' ,'' );

                        if (!in_array($waOne->id,$workersIDWithOrShowNormal)){
                            array_push($workersIDWithOrShowNormal,$waOne->id);
                        }
                    ?>
                    @if (count($gcSalesCash) > 0)
                        @foreach ($gcSalesCash as $gcCashOne)
                            @if ($waOne->id == $gcCashOne->soldByStaff)
                                <?php 
                                    $gcWorkerSalesCash += number_format($gcCashOne->gcSumInChf, 9,'.' ,'' ); 
                                    $gcWorkerSalesCashALL += number_format($gcCashOne->gcSumInChf, 9,'.' ,'' ); 
                                ?>
                            @endif
                        @endforeach
                    @endif
                    @if (count($gcSalesCard) > 0)
                        @foreach ($gcSalesCard as $gcCardOne)
                            @if ($waOne->id == $gcCardOne->soldByStaff)
                                <?php 
                                    $gcWorkerSalesCard += number_format($gcCardOne->gcSumInChf, 9,'.' ,'' ); 
                                    $gcWorkerSalesCardALL += number_format($gcCardOne->gcSumInChf, 9,'.' ,'' ); 
                                ?>
                            @endif
                        @endforeach
                    @endif
                    @if (count($gcSalesOnline) > 0)
                        @foreach ($gcSalesOnline as $gcOnlineOne)
                            @if ($waOne->id == $gcOnlineOne->soldByStaff)
                                <?php 
                                    $gcWorkerSalesOnline += number_format($gcOnlineOne->gcSumInChf, 9,'.' ,'' ); 
                                    $gcWorkerSalesOnlineALL += number_format($gcOnlineOne->gcSumInChf, 9,'.' ,'' ); 
                                ?>
                            @endif
                        @endforeach
                    @endif

                    @if (count($gcSalesRechnung) > 0)
                        @foreach ($gcSalesRechnung as $gcRechnungOne)
                            @if ($waOne->id == $gcRechnungOne->soldByStaff)
                                <?php 
                                    $gcWorkerSalesRechnung += number_format($gcRechnungOne->gcSumInChf, 9,'.' ,'' ); 
                                    $gcWorkerSalesRechnungALL += number_format($gcRechnungOne->gcSumInChf, 9,'.' ,'' ); 
                                ?>
                            @endif
                        @endforeach
                    @endif

                    @foreach ($ordersforWa as $orderOne)
                    {{$orderOne->servedBy}}
                        @if ($waOne->id == $orderOne->servedBy)
                            <?php
                                if($orderOne->inCashDiscount > 0){
                                    $payShuma = number_format($orderOne->shuma - $orderOne->inCashDiscount, 9,'.' ,'' );
                                }else if($orderOne->inPercentageDiscount > 0){
                                    $preShuma =  number_format($orderOne->shuma - $orderOne->tipPer, 9,'.' ,'' );
                                    $offVlera =  number_format($preShuma * ($orderOne->inPercentageDiscount/100), 9,'.' ,'' );
                                    $payShuma = number_format($preShuma - $offVlera + $orderOne->tipPer, 9,'.' ,'' );
                                }else{
                                    $payShuma = number_format($orderOne->shuma , 9,'.' ,'' );
                                }
                                $payShuma = number_format($payShuma - $orderOne->dicsountGcAmnt , 9,'.' ,'' );

                                $giftcardTot += number_format($orderOne->dicsountGcAmnt , 9,'.' ,'' );
                                $giftcardTotAll += number_format($orderOne->dicsountGcAmnt , 9,'.' ,'' );
                            ?>
                            
                            @if ($orderOne->payM == 'Barzahlungen' || $orderOne->payM == 'Cash')
                                <?php 
                                    $cashTot += number_format($payShuma , 9,'.' ,'' );
                                    $cashTotALL += number_format($payShuma , 9,'.' ,'' );
                                    $cashTips += number_format($orderOne->tipPer , 9,'.' ,'' );
                                    $cashTipsALL += number_format($orderOne->tipPer , 9,'.' ,'' );
                                ?>
                            @elseif ($orderOne->payM == 'Kartenzahlung')
                                <?php 
                                    $cardTot += number_format($payShuma , 9,'.' ,'' ); 
                                    $cardTotALL += number_format($payShuma , 9,'.' ,'' ); 
                                    $cardTips += number_format($orderOne->tipPer , 9,'.' ,'' );
                                    $cardTipsALL += number_format($orderOne->tipPer , 9,'.' ,'' );
                                ?>
                            @elseif ($orderOne->payM == 'Online')
                                <?php  
                                    $onlineTot += number_format($payShuma , 9,'.' ,'' ); 
                                    $onlineTotALL += number_format($payShuma , 9,'.' ,'' ); 
                                    $onlineTips += number_format($orderOne->tipPer , 9,'.' ,'' );
                                    $onlineTipsALL += number_format($orderOne->tipPer , 9,'.' ,'' );
                                ?>
                            @elseif ($orderOne->payM == 'Rechnung')
                                <?php 
                                    $billsTot += number_format($payShuma , 9,'.' ,'' );
                                    $billsTotALL += number_format($payShuma , 9,'.' ,'' );
                                    $billsTips += number_format($orderOne->tipPer , 9,'.' ,'' );
                                    $billsTipsALL += number_format($orderOne->tipPer , 9,'.' ,'' );
                                ?>
                            @endif
                        @endif
                    @endforeach

                    
                    @if($cashTot > 0 || $cardTot > 0 || $onlineTot > 0 || $billsTot > 0  || $gcWorkerSalesCash > 0 || $gcWorkerSalesCard > 0 || $gcWorkerSalesOnline > 0 || $gcWorkerSalesRechnung > 0)
                    <tr>
                        <td style="text-align: center; font-size:0.55rem;">{{$waOne->name}} (#{{$waOne->id}})</td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($cashTot + $gcWorkerSalesCash,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($cashTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($cardTot + $gcWorkerSalesCard,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($cardTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($onlineTot + $gcWorkerSalesOnline ,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($onlineTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($billsTot + $gcWorkerSalesRechnung,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($billsTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($giftcardTot,2 ,'.' ,'' )}} CHF <br>
                            ---
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($cashTot+$cardTot+$billsTot+$onlineTot+$gcWorkerSalesCash+$gcWorkerSalesCard+$gcWorkerSalesOnline+$gcWorkerSalesRechnung+$giftcardTot-$cashTips-$cardTips-$onlineTips-$billsTips ,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($cashTips+$cardTips+$onlineTips+$billsTips ,2 ,'.' ,'' )}} CHF
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">Abzüglich Trinkgeld</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">
                            {{number_format($cashTot + $gcWorkerSalesCash - $cashTips - $cardTips- $onlineTips- $billsTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>

                    </tr>
                    @endif
                @endforeach

                <?php $workersIDWithOr = array_diff($workersIDWithOr,$workersIDWithOrShowNormal);?>
                @foreach ($workersIDWithOr as $waOneIdExtra)
                    <?php
                        $cashTot = number_format(0 , 9,'.' ,'' );
                        $cashTips = number_format(0 , 9,'.' ,'' );
                        $cardTot = number_format(0 , 9,'.' ,'' );
                        $cardTips = number_format(0 , 9,'.' ,'' );
                        $onlineTot = number_format(0 , 9,'.' ,'' );
                        $onlineTips = number_format(0 , 9,'.' ,'' );
                        $billsTot = number_format(0 , 9,'.' ,'' );
                        $billsTips = number_format(0 , 9,'.' ,'' );
                        $giftcardTot = number_format(0 , 9,'.' ,'' );

                        $gcWorkerSalesCash = number_format(0 , 9,'.' ,'' );
                        $gcWorkerSalesCard = number_format(0 , 9,'.' ,'' );
                        $gcWorkerSalesOnline = number_format(0 , 9,'.' ,'' );
                        $gcWorkerSalesRechnung = number_format(0 , 9,'.' ,'' );
                    ?>
                    @if (count($gcSalesCash) > 0)
                        @foreach ($gcSalesCash as $gcCashOne)
                            @if ($waOneIdExtra == $gcCashOne->soldByStaff)
                                <?php 
                                    $gcWorkerSalesCash += number_format($gcCashOne->gcSumInChf, 9,'.' ,'' ); 
                                    $gcWorkerSalesCashALL += number_format($gcCashOne->gcSumInChf, 9,'.' ,'' ); 
                                ?>
                            @endif
                        @endforeach
                    @endif
                    @if (count($gcSalesCard) > 0)
                        @foreach ($gcSalesCard as $gcCardOne)
                            @if ($waOneIdExtra == $gcCardOne->soldByStaff)
                                <?php 
                                    $gcWorkerSalesCard += number_format($gcCardOne->gcSumInChf, 9,'.' ,'' ); 
                                    $gcWorkerSalesCardALL += number_format($gcCardOne->gcSumInChf, 9,'.' ,'' ); 
                                ?>
                            @endif
                        @endforeach
                    @endif
                    @if (count($gcSalesOnline) > 0)
                        @foreach ($gcSalesOnline as $gcOnlineOne)
                            @if ($waOneIdExtra == $gcOnlineOne->soldByStaff)
                                <?php 
                                    $gcWorkerSalesOnline += number_format($gcOnlineOne->gcSumInChf, 9,'.' ,'' ); 
                                    $gcWorkerSalesOnlineALL += number_format($gcOnlineOne->gcSumInChf, 9,'.' ,'' ); 
                                ?>
                            @endif
                        @endforeach
                    @endif

                    @if (count($gcSalesRechnung) > 0)
                        @foreach ($gcSalesRechnung as $gcRechnungOne)
                            @if ($waOneIdExtra == $gcRechnungOne->soldByStaff)
                                <?php 
                                    $gcWorkerSalesRechnung += number_format($gcRechnungOne->gcSumInChf, 9,'.' ,'' ); 
                                    $gcWorkerSalesRechnungALL += number_format($gcRechnungOne->gcSumInChf, 9,'.' ,'' ); 
                                ?>
                            @endif
                        @endforeach
                    @endif

                    @foreach ($ordersforWa as $orderOne)
                    {{$orderOne->servedBy}}
                        @if ($waOneIdExtra == $orderOne->servedBy)
                            <?php
                                if($orderOne->inCashDiscount > 0){
                                    $payShuma = number_format($orderOne->shuma - $orderOne->inCashDiscount, 9,'.' ,'' );
                                }else if($orderOne->inPercentageDiscount > 0){
                                    $preShuma =  number_format($orderOne->shuma - $orderOne->tipPer, 9,'.' ,'' );
                                    $offVlera =  number_format($preShuma * ($orderOne->inPercentageDiscount/100), 9,'.' ,'' );
                                    $payShuma = number_format($preShuma - $offVlera + $orderOne->tipPer, 9,'.' ,'' );
                                }else{
                                    $payShuma = number_format($orderOne->shuma , 9,'.' ,'' );
                                }
                                $payShuma = number_format($payShuma - $orderOne->dicsountGcAmnt , 9,'.' ,'' );

                                $giftcardTot += number_format($orderOne->dicsountGcAmnt , 9,'.' ,'' );
                                $giftcardTotAll += number_format($orderOne->dicsountGcAmnt , 9,'.' ,'' );
                            ?>
                            
                            @if ($orderOne->payM == 'Barzahlungen' || $orderOne->payM == 'Cash')
                                <?php 
                                    $cashTot += number_format($payShuma , 9,'.' ,'' );
                                    $cashTotALL += number_format($payShuma , 9,'.' ,'' );
                                    $cashTips += number_format($orderOne->tipPer , 9,'.' ,'' );
                                    $cashTipsALL += number_format($orderOne->tipPer , 9,'.' ,'' );
                                ?>
                            @elseif ($orderOne->payM == 'Kartenzahlung')
                                <?php 
                                    $cardTot += number_format($payShuma , 9,'.' ,'' ); 
                                    $cardTotALL += number_format($payShuma , 9,'.' ,'' ); 
                                    $cardTips += number_format($orderOne->tipPer , 9,'.' ,'' );
                                    $cardTipsALL += number_format($orderOne->tipPer , 9,'.' ,'' );
                                ?>
                            @elseif ($orderOne->payM == 'Online')
                                <?php  
                                    $onlineTot += number_format($payShuma , 9,'.' ,'' ); 
                                    $onlineTotALL += number_format($payShuma , 9,'.' ,'' ); 
                                    $onlineTips += number_format($orderOne->tipPer , 9,'.' ,'' );
                                    $onlineTipsALL += number_format($orderOne->tipPer , 9,'.' ,'' );
                                ?>
                            @elseif ($orderOne->payM == 'Rechnung')
                                <?php 
                                    $billsTot += number_format($payShuma , 9,'.' ,'' );
                                    $billsTotALL += number_format($payShuma , 9,'.' ,'' );
                                    $billsTips += number_format($orderOne->tipPer , 9,'.' ,'' );
                                    $billsTipsALL += number_format($orderOne->tipPer , 9,'.' ,'' );
                                ?>
                            @endif
                        @endif
                    @endforeach

                    
                    @if($cashTot > 0 || $cardTot > 0 || $onlineTot > 0 || $billsTot > 0  || $gcWorkerSalesCash > 0 || $gcWorkerSalesCard > 0 || $gcWorkerSalesOnline > 0 || $gcWorkerSalesRechnung > 0)
                    <tr>
                        <td style="text-align: center; font-size:0.55rem;">#{{$waOneIdExtra}}</td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($cashTot + $gcWorkerSalesCash,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($cashTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($cardTot + $gcWorkerSalesCard,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($cardTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($onlineTot + $gcWorkerSalesOnline ,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($onlineTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($billsTot + $gcWorkerSalesRechnung,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($billsTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($giftcardTot,2 ,'.' ,'' )}} CHF <br>
                            ---
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            {{number_format($cashTot+$cardTot+$billsTot+$onlineTot+$gcWorkerSalesCash+$gcWorkerSalesCard+$gcWorkerSalesOnline+$gcWorkerSalesRechnung+$giftcardTot-$cashTips-$cardTips-$onlineTips-$billsTips ,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($cashTips+$cardTips+$onlineTips+$billsTips ,2 ,'.' ,'' )}} CHF
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">Abzüglich Trinkgeld</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">
                            {{number_format($cashTot + $gcWorkerSalesCash - $cashTips - $cardTips- $onlineTips- $billsTips ,2 ,'.' ,'' )}} CHF
                        </td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">---</td>

                    </tr>
                   
                    @endif
                @endforeach

                <?php
                    $dayExpTotal = number_format(0 , 9,'.' ,'' );

                    foreach($dailyExpIns as $dailyExpInsOne){
                        $dayExpTotal += number_format($dailyExpInsOne->expCash , 9,'.' ,'' );
                    }
                ?>





























                    <tr>
                        <td style="text-align: center; font-size:0.55rem;"><strong>Gesamt</strong></td>
                        <td style="text-align: center; font-size:0.55rem;">
                            <strong>{{number_format($cashTotALL + $gcWorkerSalesCashALL,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($cashTipsALL ,2 ,'.' ,'' )}} CHF </strong>
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            <strong>{{number_format($cardTotALL + $gcWorkerSalesCardALL,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($cardTipsALL ,2 ,'.' ,'' )}} CHF </strong>
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            <strong>{{number_format($onlineTotALL + $gcWorkerSalesOnlineALL,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($onlineTipsALL ,2 ,'.' ,'' )}} CHF </strong>
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            <strong>{{number_format($billsTotALL + $gcWorkerSalesRechnungALL,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($billsTipsALL ,2 ,'.' ,'' )}} CHF </strong>
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                            <strong>{{number_format($giftcardTotAll,2 ,'.' ,'' )}} CHF <br>
                            ---</strong>
                        </td>
                        <td style="text-align: center; font-size:0.55rem;">
                        <strong>{{number_format($cashTotALL+$cardTotALL+$billsTotALL+$onlineTotALL + $gcWorkerSalesCashALL + $gcWorkerSalesCardALL + $gcWorkerSalesOnlineALL + $gcWorkerSalesRechnungALL + $giftcardTotAll - $cashTipsALL - $cardTipsALL - $onlineTipsALL - $billsTipsALL,2 ,'.' ,'' )}} CHF <br>
                            {{number_format($cashTipsALL+$cardTipsALL+$onlineTipsALL+$billsTipsALL,2 ,'.' ,'' )}} CHF </strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>Abzüglich Trinkgeld</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">
                            <strong>{{number_format($cashTotALL + $gcWorkerSalesCashALL - $cashTipsALL - $cardTipsALL- $onlineTipsALL- $billsTipsALL ,2 ,'.' ,'' )}} CHF</strong>
                        </td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>Abzüglich Bar Ausgaben</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;">
                            <strong>{{number_format($cashTotALL + $gcWorkerSalesCashALL - $cashTipsALL - $cardTipsALL- $onlineTipsALL- $billsTipsALL - $dayExpTotal,2 ,'.' ,'' )}} CHF</strong>
                        </td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                        <td style="text-align: center; border-bottom:1px solid black; font-size:0.55rem;"><strong>---</strong></td>
                    </tr>



            </table>
        </div>
    </main>   
   
</body>
</html>