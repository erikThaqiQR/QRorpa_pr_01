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
            padding-bottom: 20px;
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
    
        .total td{
            text-align: right;
            margin: 0px !important;
            padding: 0px !important;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }
            
            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
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
        #pageCounter{
            counter-reset: currentPage;
        }
        #pageCounter .pageNumbers:before { 
            counter-increment: currentPage; 
            content: "Seite " counter(currentPage) " von "; 
        }

        footer {
            position: fixed; 
            bottom: -30px; 
            left: 0px; 
            right: 0px;
            height: 50px; 
            font-size: 14px !important;
            justify-content:space-between;
        

            /** Extra personal styles **/
            line-height: 35px;
        }

        .signatureRowTDCls{
            width: 200px;
            height: 100px;
            overflow: hidden;
        }

        .signatureRowTDCls img {
            width: 400px;
            height: 200px;
            margin: 30px -30% -20% 0;
        }

    </style>
</head>


<?php

    use App\emailReceiptFromAdm;
    use App\Restorant;
    use App\PiketLog;
    use App\Produktet;
use App\User;
use App\Takeaway;
use App\rechnungClient;
use App\OPSaferpayReference;
use App\rechnungClientToBills;


    if(PiketLog::where('order_u',$items->id)->first() != null && PiketLog::where('order_u',$items->id)->first()->piket < 0){
       $pointsUsed = PiketLog::where('order_u',$items->id)->first()->piket * (-1);
       $moneyOff = $pointsUsed*0.01;

    }elseif(PiketLog::where('order_u',$items->id)->first() != null && PiketLog::where('order_u',$items->id)->first()->piket > 0){
        $pointsEr = PiketLog::where('order_u',$items->id)->first()->piket;
    }

    if($items->inCashDiscount){
        $totShuma = number_format($items->shuma-$items->inCashDiscount, 2, '.', '');
        $totZbritja = number_format($items->inCashDiscount, 2, '.', '');
    }else if($items->inPercentageDiscount){
        $totShuma = number_format($items->shuma-(($items->shuma - $items->tipPer)*($items->inPercentageDiscount*0.01)), 2, '.', '');
        $totZbritja = number_format(($items->shuma - $items->tipPer)*($items->inPercentageDiscount*0.01), 2, '.', '');
    }else{
        $totShuma = $items->shuma;
    }

    $theRes = Restorant::findOrFail($items->Restaurant);
    $adr2D =  explode(',',$theRes->adresa);
    $adr1 =  $adr2D[0];
    $adr2 = '---' ;
    if(isset($adr2D[1])){
        $adr2 = $adr2D[1] ;
    }
    if(isset($adr2D[2])){
        $adr2 = $adr2D[1].','.$adr2D[2] ;
    }
   
    $exInfo = emailReceiptFromAdm::where('forOrder',$items->id)->first();

    $firstADM = User::where([['role','5'],['sFor',$theRes->id]])->first();

    switch (date("m")){
        case 1: $nowMonth="Januar" ; break;
        case 2: $nowMonth="Februar" ; break;
        case 3: $nowMonth="März" ; break;
        case 4: $nowMonth="April" ; break;
        case 5: $nowMonth="Mai" ; break;
        case 6: $nowMonth="Juni" ; break;
        case 7: $nowMonth="Juli" ; break;
        case 8: $nowMonth="August" ; break;
        case 9: $nowMonth="September" ; break;
        case 10: $nowMonth="Oktober" ; break;
        case 11: $nowMonth="November" ; break;
        case 12: $nowMonth="Dezember" ; break;
    }

    $orDt2d = explode('-',explode(' ',$items->created_at)[0]);
    $orTi2d = explode(':',explode(' ',$items->created_at)[1]);

    $startDt = $orDt2d[2].'.'.$orDt2d[1].'.'.$orDt2d[0];
    $endPayDt = date('d.m.Y', strtotime($startDt. ' + '.$exInfo->exInfoDaysToPay.' day'));

    $pdfname = 'storage/rechnungBillsFirst/rechnungBillFirst'.$theRes->emri.'_'.$items->id.'.pdf';
    $pdftext = file_get_contents($pdfname);
    $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);

    $totFromProductePrice = number_format(0, 2, '.', '');
    foreach(explode('---8---',$items->porosia) as $produkti){
        $prod = explode('-8-', $produkti);
        $totFromProductePrice += number_format($prod[4], 2, '.', '');
    }
    if($theRes->resTvsh == 0){
        $hiTvsh = number_format( 0 , 9, '.', '');
        $loTvsh = number_format( 0 , 9, '.', '');
    }else{
        if($orDt2d[0] <= 2023){
            $hiTvsh = number_format( 0.071494893 , 9, '.', '');
            $loTvsh = number_format( 0.024390243 , 9, '.', '');
        }else{
            $hiTvsh = number_format( 0.074930619 , 9, '.', '');
            $loTvsh = number_format( 0.025341130 , 9, '.', '');
        }
    }

    $rechSignature = '';
    $rechBillToCl = rechnungClientToBills::where('orderId',$items->id)->first();
    if($rechBillToCl != Null){
        $rechCl = rechnungClient::find($rechBillToCl->clientId);
        if($rechCl != Null){
            $rechSignature = $rechCl->signatureFile;
        }
    }else{
        $rechReceipt = emailReceiptFromAdm::where('forOrder',$items->id)->first();
        if($rechReceipt != Null){
            $rechSignature = $rechReceipt->exInfoClSignature;
        }
    }
?>

<body id="pageCounter">
    <footer id="footerPC">
        <p style="margin: 0px; padding:0px;  font-size:14px; text-align:right !important;"> <span  class="pageNumbers"></span>{{$pages}}</p>
    </footer>

    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table style="margin-top:-1.5cm ; margin-bottom:1cm ;">
                        <tr>
                            <td>
                                <p style="margin:0px; padding:0px; margin-top:-15px;">{{$theRes->emri}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-15px;">{{ $adr1 }}</p>
                                <p style="margin:0px; padding:0px; margin-top:-10px;">{{ $adr2 }}</p>
                                <p style="margin:0px; padding:0px; margin-top:-10px;">Tel. {{ $theRes->resPhoneNr }}</p>

                                <!-- <p style="margin:0px; padding:0px; margin-top:-8px;">E-Mail: firstADM->email</p> -->

                                @if (str_contains($theRes->chemwstForRes, 'CHE'))
                                    <p style="margin:0px; padding:0px; margin-top:-8px;"> 
                                    @if ($theRes->id == 35)
                                    UID-Nr.
                                    @else
                                    MwST-Nr.  
                                    @endif    
                                    {{ $theRes->chemwstForRes }}</p>
                                @else
                                    <p style="margin:0px; padding:0px; margin-top:-8px;">{{ $theRes->chemwstForRes }}</p>
                                @endif
                                <p style="margin:0px; padding:0px; margin-top:-6px;">IBAN: {{ $theRes->resBankId }}</p>
                            </td>
                            <td>
                                <img width="110px" src="{{ public_path() . '/storage/ResProfilePic/'.$theRes->profilePic }}" id="logo" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <p style="margin:0px; padding:0px; margin-top:-12px; font-size:9px;"><u>{{$theRes->emri}} {{$theRes->adresa}}</u></p>
                                <p style="margin:0px; padding:0px; margin-top:-8px; font-weight:normal !important;">{{$exInfo->exInfoFirma}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px; font-weight:normal;">{{$exInfo->exInfoLastname}} {{$exInfo->exInfoName}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px;">{{$exInfo->exInfoStreet}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">{{$exInfo->exInfoPlzOrt}}</p>
                            </td>

                            <td >
                                <p style="margin:0px; padding:0px; margin-top:-12px; font-size:9px;"></p>
                                <p style="margin:0px; padding:0px; margin-top:-8px;">Rechnungsnummer: #{{ $items->refId }}</p>
                                @if ($theRes->id != 35 && $items->payM == 'Online')
                                    <?php
                                       $theRefIns = OPSaferpayReference::where('orderId',$items->id)->first();
                                    ?>
                                    @if($theRefIns != Null)
                                    <p style="margin:0px; padding:0px; margin-top:-8px;">Saferpay Online: {{ $theRefIns->refPh }}</p>
                                    @endif
                                @endif
                               
                                <p style="margin:0px; padding:0px; margin-top:-6px;">Datum/Zeit: {{$orDt2d[2]}}.{{$orDt2d[1]}}.{{$orDt2d[0]}} / {{$orTi2d[0]}}:{{$orTi2d[1]}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">Zahlungsfrist bis: {{ $endPayDt }}</p>
                                @if ($exInfo->theComm != 'empty')
                                    <p style="margin:0px; padding:0px; margin-top:-4px;">Kommentar: {{ $exInfo->theComm }}</p>
                                @endif
                            </td>                                                       
                        </tr>
                    </table>
                </td>
            </tr>

            @if($items->resComment != 'none' && $items->resComment != Null)
            <tr>
                <td colspan="4" style="text-align:center; margin:0px; padding:0px;">
                    <pre style="font-size:13px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height:1.01; text-align: center !important; margin:0px; padding:0px 15px 0px 15px; white-space: pre-wrap; word-wrap: break-word;">{{ preg_replace('/^\x{FEFF}|\x{200B}|\x{00A0}|\t/u', '',$items->resComment) }}</pre>
                </td>
            </tr>
            @endif

            <tr>
                <td style="font-size:12px;" colspan="2">Chur, {{ date("d") }} {{ $nowMonth }} {{ date("Y") }}</td>
                <td style="font-size:12px;" colspan="2">
                    @if ($theRes->id != 35)
                        Sie wurden bedient von: 
                        @if ($items->servedBy != 0)
                            @if (User::find($items->servedBy) != NULL)
                                {{User::find($items->servedBy)->name}}
                            @else
                                --- ---
                            @endif
                        @else
                            --- ---
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td style="font-size:12px;" colspan="2">
                    @if ($theRes->id != 35)
                        Tisch-Nr.: 
                        @if($items->nrTable == 500)
                            Takeaway
                        @elseif($items->nrTable == 9000)
                            Delivery
                        @else
                            {{$items->nrTable}}
                        @endif
                    @endif
                </td>
                <td style="font-size:12px;" colspan="2">Verkaufs-ID: {{$items->id}}</td>
            </tr>
        </table>

        <table cellpadding="0" cellspacing="0">
            <tr class="heading" style="margin-top: 1.5cm;">
                <td> Menge </td>
                <td text-align: left;>Produkte</td>
                <td style="text-align: center;">Einen</td>
                <td style="text-align: center;">MwSt</td>
                <td style="text-align: center;">MwSt %</td>
                <td style="text-align: right;">Gesamt</td>
            </tr>

            @if($items->freeProdId != 0)
                <tr class="details">
                    <td> 1 </td>   
                    <td colspan="3">{{Produktet::find($items->freeProdId)->emri}}</td>
                    <td style="text-align: center;">Freier</td>
                    <td style="text-align: right;">Freier</td>
                </tr>
            @endif
            <?php
                $countProdsDs = 0;
                $mwst77Tot = number_format(0,9,'.','');
                $mwst25Tot = number_format(0,9,'.','');
            ?>
            @foreach(explode('---8---',$items->porosia) as $produkti)
                <?php
                    $prod = explode('-8-', $produkti);
                    $countProdsDs++;

                    if($items->inCashDiscount > 0){
                        $totZbritja = number_format($items->inCashDiscount, 2, '.', '');
                        if($items->nrTable == 500){
                            $Pr = Produktet::find($prod[7]);
                            $taPr = Takeaway::where('prod_id',$Pr->id)->first();
                            if($taPr != Null){
                                if($taPr->mwstForPro == 7.70 || $taPr->mwstForPro == 8.10){
                                    $cal1 = number_format(($prod[4]* $totZbritja) / $totFromProductePrice , 9, '.', '');
                                    $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                                    $mwstValPrc = number_format($hiTvsh,9,'.','');
                                    $mwstOnChf = number_format($mwstValPrc *  $cal2,9,'.','');
                                    $mwst77Tot += number_format($mwstOnChf,9,'.','');
                                }else if($taPr->mwstForPro == 2.50 || $taPr->mwstForPro == 2.60){
                                    $cal1 = number_format(($prod[4]* $totZbritja) / $totFromProductePrice , 9, '.', '');
                                    $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                                    $mwstValPrc = number_format($loTvsh,9,'.','');
                                    $mwstOnChf = number_format($mwstValPrc * $cal2,9,'.','');
                                    $mwst25Tot += number_format($mwstOnChf,9,'.','');
                                }else{
                                    $mwstOnChf = number_format(0,9,'.',''); 
                                }
                            }else{
                                $cal1 = number_format(($prod[4]* $totZbritja) / $totFromProductePrice , 9, '.', '');
                                $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                                $mwstValPrc = number_format($loTvsh,9,'.','');
                                $mwstOnChf = number_format($mwstValPrc * $cal2,9,'.','');
                                $mwst25Tot += number_format($mwstOnChf,9,'.','');
                            }
                        }else{
                            $cal1 = number_format(($prod[4]* $totZbritja) / $totFromProductePrice , 9, '.', '');
                            $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                            $mwstValPrc = number_format($hiTvsh,9,'.','');
                            $mwstOnChf = number_format($mwstValPrc * $cal2,9,'.','');
                            $mwst77Tot += number_format($mwstOnChf,9,'.','');
                        }
                    }else if($items->inPercentageDiscount > 0){
                        $totZbritjaPrc = number_format($items->inPercentageDiscount / 100, 2, '.', '');
                        if($items->nrTable == 500){
                            $Pr = Produktet::find($prod[7]);
                            $taPr = Takeaway::where('prod_id',$Pr->id)->first();
                            if($taPr != Null){
                                if($taPr->mwstForPro == 7.70 || $taPr->mwstForPro == 8.10){
                                    $cal1 = number_format($prod[4] * $totZbritjaPrc, 9, '.', '');
                                    $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                                    $mwstValPrc = number_format($hiTvsh,9,'.','');
                                    $mwstOnChf = number_format($mwstValPrc * $cal2,9,'.','');
                                    $mwst77Tot += number_format($mwstOnChf,9,'.','');
                                }else if($taPr->mwstForPro == 2.50 || $taPr->mwstForPro == 2.60){
                                    $cal1 = number_format($prod[4] * $totZbritjaPrc, 9, '.', '');
                                    $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                                    $mwstValPrc = number_format($loTvsh,9,'.','');
                                    $mwstOnChf = number_format($mwstValPrc * $cal2,9,'.','');
                                    $mwst25Tot += number_format($mwstOnChf,9,'.','');
                                }else{
                                    $mwstOnChf = number_format(0,9,'.',''); 
                                }
                            }else{
                                $cal1 = number_format($prod[4] * $totZbritjaPrc, 9, '.', '');
                                $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                                $mwstValPrc = number_format($loTvsh,9,'.','');
                                $mwstOnChf = number_format($mwstValPrc * $cal2,9,'.','');
                                $mwst25Tot += number_format($mwstOnChf,9,'.','');
                            }
                        }else{
                            $cal1 = number_format($prod[4] * $totZbritjaPrc, 9, '.', '');
                            $cal2 = number_format($prod[4] - $cal1, 9, '.', '');
                            $mwstValPrc = number_format($hiTvsh,9,'.','');
                            $mwstOnChf = number_format($mwstValPrc * $cal2,9,'.','');
                            $mwst77Tot += number_format($mwstOnChf,9,'.','');
                        }
                    }else{
                        if($items->nrTable == 500){
                            $Pr = Produktet::find($prod[7]);
                            $taPr = Takeaway::where('prod_id',$Pr->id)->first();
                            if($taPr != Null){
                                if($taPr->mwstForPro == 7.70 || $taPr->mwstForPro == 8.10){
                                    $mwstValPrc = number_format($hiTvsh,9,'.','');
                                    $mwstOnChf = number_format($mwstValPrc * $prod[4],9,'.','');
                                    $mwst77Tot += number_format($mwstOnChf,9,'.','');
                                }else if($taPr->mwstForPro == 2.50 || $taPr->mwstForPro == 2.60){
                                    $mwstValPrc = number_format($loTvsh,9,'.','');
                                    $mwstOnChf = number_format($mwstValPrc * $prod[4],9,'.','');
                                    $mwst25Tot += number_format($mwstOnChf,9,'.','');
                                }else{
                                    $mwstOnChf = number_format(0,9,'.',''); 
                                }
                            }else{
                                $mwstValPrc = number_format($loTvsh,9,'.','');
                                $mwstOnChf = number_format($mwstValPrc * $prod[4],9,'.','');
                                $mwst25Tot += number_format($mwstOnChf,9,'.','');
                            }
                        }else{
                            $mwstValPrc = number_format($hiTvsh,9,'.','');
                            $mwstOnChf = number_format($mwstValPrc * $prod[4],9,'.','');
                            $mwst77Tot += number_format($mwstOnChf,9,'.','');
                        }
                    }
                ?>
                @if ($countProdsDs == 21)
                    <tr class="details" style="margin-bottom:0px; margin-top:-3cm;">
                @else
                    <tr class="details" style="margin-bottom:0px;">
                @endif
                    <td>
                        {{$prod[3]}} X
                    </td>   
                    <td style="text-align: left;">
                        {{$prod[0]}}    
                        @if($prod[5] != '' && $prod[5] != 'empty')
                            <span style="opacity:0.6">( {{$prod[5]}} )</span>
                        @endif  
                        @if($prod[2] != '')
                            <br><?php $thE = explode('--0--', $prod[2])?>
                            @foreach ($thE as $ex)
                                @if ($ex != '' && $ex != 'empty')
                                    <span style="margin-right:10px; font-weight:normal; opacity:0.7; font-size:11px;">+ {{ explode('||',$ex)[0]}}</span>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <?php
                        if(str_contains($prod[3],'/')){
                            $prod3_2D = explode('/',$prod[3]);
                            $sasiaProdThis = $prod3_2D[0] / $prod3_2D[1];
                        }else{
                            $sasiaProdThis = $prod[3];
                        } 
                    ?>
                    <td style="text-align: center;">
                        {{number_format($prod[4] / $sasiaProdThis,2,'.','')}} CHF
                    </td>
                    <td style="text-align: center;">
                        {{number_format($mwstOnChf,2,'.','')}} CHF
                    </td>
                    <td style="text-align: center;">
                        @if($theRes->resTvsh == 0)
                            {{number_format(0, 2, '.', '')}} %
                        @else
                            <?php
                                $Pr = Produktet::find($prod[7]);
                                $taPr = Takeaway::where('prod_id',$Pr->id)->first();
                            ?>
                            @if($items->nrTable == 500 && $taPr != Null)

                                @if($orDt2d[0] <= 2023)
                                    @if($taPr->mwstForPro == 7.70 || $taPr->mwstForPro == 8.10)
                                        {{number_format(7.70, 2, '.', '')}} %
                                    @elseif($taPr->mwstForPro == 2.50 || $taPr->mwstForPro == 2.60)
                                        {{number_format(2.50, 2, '.', '')}} %
                                    @elseif($taPr->mwstForPro == 0.00)
                                        {{number_format(0.00, 2, '.', '')}} %
                                    @endif
                                @else
                                    @if($taPr->mwstForPro == 7.70 || $taPr->mwstForPro == 8.10)
                                        {{number_format(8.10, 2, '.', '')}} %
                                    @elseif($taPr->mwstForPro == 2.50 || $taPr->mwstForPro == 2.60)
                                        {{number_format(2.60, 2, '.', '')}} %
                                    @elseif($taPr->mwstForPro == 0.00)
                                        {{number_format(0.00, 2, '.', '')}} %
                                    @endif
                                @endif
                            @elseif($items->nrTable == 500)
                                @if($orDt2d[0] <= 2023)
                                2.50 %
                                @else
                                2.60 %
                                @endif
                            @else
                                @if($orDt2d[0] <= 2023)
                                7.70 %
                                @else
                                8.10 %
                                @endif
                            @endif
                        @endif
                    </td>
                    <td style="text-align: right;">
                        {{number_format($prod[4],2,'.','')}} CHF
                    </td>
                </tr>
             @endforeach
        </table>

        
        <?php
            $rowspanCountSignature = 1;
        ?>

        <table style="border-top:1px solid #555;">
         
            <tr class="total" style="margin-bottom:0px;">
                @if (isset($totZbritja))
                    {{$rowspanCountSignature++}}
                @endif
                @if($theRes->resTvsh == 0)
                    {{$rowspanCountSignature++}}
                @else
                    @if ($mwst77Tot > 0)
                        {{$rowspanCountSignature++}}
                    @endif
                    @if ($mwst25Tot > 0)
                        {{$rowspanCountSignature++}}
                    @endif
                @endif
                @if($items->tipPer != 0)
                    {{$rowspanCountSignature++}}
                @endif
                @if(PiketLog::where('order_u',$items->id)->first() != null && PiketLog::where('order_u',$items->id)->first()->piket < 0)
                    {{$rowspanCountSignature++}}
                @elseif(PiketLog::where('order_u',$items->id)->first() != null && PiketLog::where('order_u',$items->id)->first()->piket > 0  && $items->byId != 0)
                    {{$rowspanCountSignature++}}
                @endif
                @if($items->dicsountGcAmnt > 0)
                    {{$rowspanCountSignature++}}
                @endif
                <td id="signatureRowTD" class="signatureRowTDCls" rowspan="{{$rowspanCountSignature}}">
                    <img class="signaturePic" src="{{ public_path() . '/storage/rechnungPaySignatures/'.$rechSignature }}" alt="">
                </td>
                <td> Zwischensumme: </td>
                <td>
                    @if ($items->mwstVal > 0)
                        <?php
                            $valVal = number_format($items->mwstVal*0.01, 3, '.', ''); 
                        ?>
                        {{ number_format($totShuma-$mwst77Tot-$mwst25Tot, 2, '.', '') }} CHF
                    @else
                        {{ number_format($totShuma-$mwst77Tot-$mwst25Tot, 2, '.', '') }} CHF
                    @endif
                </td>
            </tr>

            @if (isset($totZbritja))
            <tr class="total" style="margin:0px; padding:0px;">
                <td> Rabatt: </td>
                <td> {{$totZbritja}} CHF</td>
            </tr>
            @endif
            
            @if($theRes->resTvsh == 0)
                <tr class="total" style="margin:0px; padding:0px;">
                    <td> MwSt 0.00% </td>
                    <td>{{ number_format(0, 2, '.', '') }} CHF</td>
                </tr>
            @else
                @if ($mwst77Tot > 0)
                <tr class="total" style="margin:0px; padding:0px;">
                    @if($orDt2d[0] <= 2023)
                    <td> MwSt 7.70% </td>
                    @else
                    <td> MwSt 8.10% </td>
                    @endif
                    <td>{{ number_format($mwst77Tot, 2, '.', '') }} CHF</td>
                </tr>
                @endif
                @if ($mwst25Tot > 0)
                <tr class="total" style="margin:0px; padding:0px;">
                    @if($orDt2d[0] <= 2023)
                    <td> MwSt 2.50% </td>
                    @else
                    <td> MwSt 2.60% </td>
                    @endif
                    <td>{{ number_format($mwst25Tot, 2, '.', '') }} CHF</td>
                </tr>
                @endif
            @endif

            @if($items->tipPer != 0)
            <tr class="total" style="margin:0px; padding:0px;">
                <td>Kellner Trinkgeld</td>
                <td>
                  {{number_format($items->tipPer, 2, '.', '') }} CHF
                </td>
            </tr>
            @endif
            @if(PiketLog::where('order_u',$items->id)->first() != null && PiketLog::where('order_u',$items->id)->first()->piket < 0)
                <tr class="total" style="margin:0px; padding:0px;">
                    <td>Punkte</td>
                    <td>
                    - {{ $moneyOff }} CHF
                    </td>
                </tr>
            @elseif(PiketLog::where('order_u',$items->id)->first() != null && PiketLog::where('order_u',$items->id)->first()->piket > 0  && $items->byId != 0)
                <tr class="total" style="margin:0px; padding:0px;">
                    <td>Punkte</td>
                    <td>
                    + {{ $pointsEr }} p
                    </td>
                </tr>
            @endif
            @if($items->dicsountGcAmnt > 0)
                <tr class="total" style="margin:0px; padding:0px;">
                    <td><strong>Geschenkkarte:</strong></td>
                    <td>
                    <strong>- {{number_format($items->dicsountGcAmnt , 2, '.', '')}} CHF</strong>
                    </td>
                </tr>
            @endif
            <tr class="total" style="margin:0px; padding:0px;">
                <td style="text-align:center;"><strong>Diese Rechnung wurde digital unterzeichnet.</strong></td>
                <td><strong>Gesamtsumme:</strong></td>
                <td>
                   <strong> {{number_format($totShuma - $items->dicsountGcAmnt, 2, '.', '')}} CHF</strong>
                </td>
            </tr>
        </table>
        <div style="position:absolute; top:22cm;">
            <div class="footerP1" >
                <p style="margin:0px; padding:0px; margin-top:20px; width:100%;"><strong>Zahlungskondition: {{ $exInfo->exInfoDaysToPay }} Tage / Zahlbar bis {{ $endPayDt }}</strong></p>
                <p style="margin:0px; padding:0px; margin-top:-5px; width:100%;"><strong>
                    @if($theRes->id == 35)
                    Vielen Dank für das entgegengebrachte Vertrauen – wir schätzen die Zusammenarbeit sehr
                    @else
                    Besten Dank für Ihren Besuch!
                    @endif
                </strong></p>
            </div>
        </div>





        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table style="position:relative; top:8.5cm; margin-top:-7.5cm; page-break-before:always; ">
                        <tr>
                            <td>
                                <p style="margin:0px; padding:0px; margin-top:-15px;">{{$theRes->emri}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-15px;">{{ $adr1 }}</p>
                                <p style="margin:0px; padding:0px; margin-top:-10px;">{{ $adr2 }}</p>
                                <p style="margin:0px; padding:0px; margin-top:-10px;">Tel. {{ $theRes->resPhoneNr }}</p>
                                <!-- <p style="margin:0px; padding:0px; margin-top:-8px;">E-Mail: firstADM->email </p> -->
                                @if (str_contains($theRes->chemwstForRes, 'CHE'))
                                    <p style="margin:0px; padding:0px; margin-top:-8px;">
                                    @if ($theRes->id == 35)
                                    UID-Nr.
                                    @else
                                    MwST-Nr.  
                                    @endif
                                    {{ $theRes->chemwstForRes }}</p>
                                @else
                                    <p style="margin:0px; padding:0px; margin-top:-8px;">{{ $theRes->chemwstForRes }}</p>
                                @endif
                                <p style="margin:0px; padding:0px; margin-top:-6px;">IBAN: {{ $theRes->resBankId }}</p>
                            </td>
                            <td>
                                <img width="110px" src="{{ public_path() . '/storage/ResProfilePic/'.$theRes->profilePic }}" id="logo" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="4">
                    <table style="position:relative; top:8.5cm; margin-top:-3.5cm;">
                        <tr>
                            <td>
                                <p style="margin:0px; padding:0px; margin-top:-12px; font-size:9px;"><u>{{$theRes->emri}} {{$theRes->adresa}}</u></p>
                                <p style="margin:0px; padding:0px; margin-top:-8px; font-weight:normal !important;">{{$exInfo->exInfoFirma}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px; font-weight:normal;">{{$exInfo->exInfoLastname}} {{$exInfo->exInfoName}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px;">{{$exInfo->exInfoStreet}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">{{$exInfo->exInfoPlzOrt}}</p>

                            </td>

                            <td >
                                <p style="margin:0px; padding:0px; margin-top:-12px; font-size:9px;"></p>
                                <p style="margin:0px; padding:0px; margin-top:-8px;">Rechnungsnummer: #{{ $items->refId }}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px;">Datum/Zeit: {{$orDt2d[2]}}.{{$orDt2d[1]}}.{{$orDt2d[0]}} / {{$orTi2d[0]}}:{{$orTi2d[1]}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">Zahlungsfrist bis: {{ $endPayDt }}</p>

                            </td>                                                       
                        </tr>
                    </table>
                </td>
            </tr>

        </table>


        <table id="eBankingPart" style="z-index:120; border-top: 1px solid black; border-bottom: 1px solid black;  width:18cm ; margin-left:-1.5cm; padding-left:1cm; margin-top:5cm;  vertical-align: bottom; position:absolute; top:18cm;">
            <tr>
                <td colspan="1"  style="border-right: 1px solid black;">
                    <p style="font-size:1.2rem;"><strong>Rechnung</strong></p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;"></p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:7px;">Konto/Zahlungspflichtig an</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theRes->resBankId}}</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theRes->emri}}</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$adr1}}</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$adr2}}</p>
                    <p></p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:7px;">zahlbar per</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->exInfoFirma}}</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->exInfoStreet}}</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->exInfoPlzOrt}}</p>
                    <p style="color:white">.</p>
                    <p style="color:white">.</p>
            
                    <pre style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">Währung        Summe</pre>
                    <pre style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">CHF               {{number_format($totShuma - $items->dicsountGcAmnt, 2, '.', '')}}</pre>
                    <p></p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:8px; width:100%; text-align:right;"><strong>Akzeptanzstelle</strong></p>

                </td>
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="width:5.5cm;"> 
                                <p style="font-size:1.2rem; z-index:100;"><strong>Zahlungsteil</strong></p>
                                <img style="width: 5cm; margin: 0px; padding:0px; margin-top:15px;" src="{{ public_path() . '/storage/ebankqrcode/'.$items->ebankqrcode }}" id="logo" />
                                <p style="color:white; margin: 0px; padding:0px;">.</p>
                                <pre style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">Währung        Summe</pre>
                                <pre style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">CHF               {{number_format($totShuma - $items->dicsountGcAmnt, 2, '.', '')}}</pre>

                            </td>

                            <td style="text-align:left">
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:7px;"><strong>Konto/Zahlungspflichtig an</strong></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theRes->resBankId}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theRes->emri}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$adr1}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$adr2}}</p>
                                <p></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:7px;"><strong>Zusätzliche Information</strong></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{str_pad($items->refId, 10, '0', STR_PAD_LEFT)}}</p>
                                <p></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:7px;"><strong>zahlbar per</strong></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->exInfoFirma}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->exInfoStreet}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->exInfoPlzOrt}}</p>
                            </td>
                        </tr>
                    </table>
                
                </td>
            </tr>
        </table>
    </div>



 
  

  
   
</body>
</html>