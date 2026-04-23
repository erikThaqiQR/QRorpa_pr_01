<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rechnung</title>
    
    <style>
        *{
            margin:0px; 
            padding:5px;
        }
        .invoice-box {
            max-width: 12cm;
            margin: auto;
            padding: 5px;
       
            box-shadow: 0 0 5px rgba(0, 0, 0, .15);
            font-size: 11px;
            line-height: 5px;
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
            font-size: 20px;
            line-height: 20px;
            color: #333;
        }
        
        .invoice-box table tr.information table td {
            padding-bottom: 10px;
        }
        
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        
        .invoice-box table tr.details td {
            padding-bottom: 7px;
        }
        
        .invoice-box table tr.items td{
            border-bottom: 1px solid #eee;
        }
        
        .invoice-box table tr.items.last td {
            border-bottom: none;
        }
    

        .thanks{
            margin-top:10px;
            border-top: 1px solid #eee;
            text-align: center;
        }
    </style>


</head>

<?php

use App\User;
use App\Takeaway;
use App\Restorant;
use App\PiketLog;
use App\Produktet;
use App\DeliveryProd;
use App\emailReceiptFromAdm;
use App\logOrderPayMChng;
use App\OPSaferpayReference;
use App\payTecTransactionLog;

    $piketLog = PiketLog::where('order_u',$items->id)->first();

    if($piketLog != null && $piketLog->piket < 0){
       $pointsUsed = $piketLog->piket * (-1);
       $moneyOff = $pointsUsed*0.01;

    }elseif($piketLog != null && $piketLog->piket > 0){
        $pointsEr = $piketLog->piket;
    }

    if($items->inCashDiscount > 0){
        $totShuma = number_format($items->shuma-$items->inCashDiscount, 2, '.', '');
        $totZbritja = number_format($items->inCashDiscount, 2, '.', '');
    }else if($items->inPercentageDiscount > 0){
        $totShuma = number_format($items->shuma-(($items->shuma - $items->tipPer)*($items->inPercentageDiscount*0.01)), 2, '.', '');
        $totZbritja = number_format(($items->shuma - $items->tipPer)*($items->inPercentageDiscount*0.01), 2, '.', '');
    }else{
        $totShuma = $items->shuma;
    }

    $orExInfo = emailReceiptFromAdm::where('forOrder',$items->id)->first();

    $totMwst = 0;
    $mwstFor25 = 0;
    $mwstFor77 = 0;

    $totFromProductePrice = number_format(0, 2, '.', '');

    foreach(explode('---8---',$items->porosia) as $produkti){
        $prod = explode('-8-', $produkti);
        $totFromProductePrice += number_format($prod[4], 2, '.', '');
    }

    $date2D = explode('-',explode(' ',$items->created_at)[0]);
    $time2D = explode(':',explode(' ',$items->created_at)[1]);

    $theRes = Restorant::findOrFail($items->Restaurant);

    if($theRes->resTvsh == 0){
        $hiTvsh = number_format( 0 , 9, '.', '');
        $loTvsh = number_format( 0 , 9, '.', '');
    }else{
        if($date2D[0] <= 2023){
            $hiTvsh = number_format( 0.071494893 , 9, '.', '');
            $loTvsh = number_format( 0.024390243 , 9, '.', '');
        }else{
            $hiTvsh = number_format( 0.074930619 , 9, '.', '');
            $loTvsh = number_format( 0.025341130 , 9, '.', '');
        }
    }

    $orderPOSData = payTecTransactionLog::where('orderId',$items->id)->first();

    if($orderPOSData != Null){
        $TrxDate = str_split($orderPOSData->TrxDate);
        $TrxTime = str_split($orderPOSData->TrxTime);
    }
?>

<body >
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="5" style="margin:0px !important; padding:0px !important;">
                    <table style="margin:0px !important; padding:0px !important;">
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="font-size:12px; text-align:left; margin:0px; padding:0px;"><strong>{{$theRes->emri}}</strong></td>
                            <td style="font-size:12px; text-align:right; padding:0px; margin:0px;"><strong>Rechnung #: {{$items->refId}}</strong></td>
                        </tr>
                       
                        <tr>
                            <?php
                                $sdr2d = explode(',',$theRes->adresa);
                            ?>
                            @if (isset($sdr2d[0]))
                            <td style="font-size:8px; text-align:left;">{{$sdr2d[0]}}</td>
                            @else
                            <td style="font-size:8px; text-align:left;">---</td>
                            @endif

                            <td style="font-size:8px; text-align:right; padding-right:0px; margin-right:0px;"> 
                                Datum/Zeit: <strong>{{$date2D[2]}}.{{$date2D[1]}}.{{$date2D[0]}} / {{$time2D[0]}}:{{$time2D[1]}}</strong>
                            </td>
                        </tr>
                      
                        <tr>
                            @if (isset($sdr2d[1]))
                                <td style="font-size:8px; text-align:left;">{{$sdr2d[1]}}
                                @if (isset($sdr2d[2]))
                                    ,{{$sdr2d[0]}}
                                @endif
                                </td>
                            @else
                                <td style="font-size:8px; text-align:left;">---</td>
                            @endif

                            @if($orExInfo != NULL)
                                <td style="font-size:8px; text-align:right; padding-right:0px; margin-right:0px;"><strong>der Kunde:</strong></td>
                            @else
                                <td style="font-size:8px; text-align:right;">
                                    @if ($items->payM == 'Online')
                                        <?php
                                        $theRefIns = OPSaferpayReference::where('orderId',$items->id)->first();
                                        ?>
                                        <p style="margin:0px; padding:0px;">Saferpay Online: {{ $theRefIns->refPh }}</p>
                                    @endif
                                </td>
                            @endif
                        </tr>
                        <tr>
                            @if ($theRes != NULL && $theRes->resPhoneNr != 'empty')
                                <td style="font-size:8px; text-align:left;">Tel. {{$theRes->resPhoneNr}}</td>
                            @else
                                <td style="font-size:8px; text-align:left;">Tel. +41 XX XXX XX XX</td>
                            @endif
                            @if($orExInfo != NULL)
                                <td style="font-size:8px; text-align:right;">{{$orExInfo->exInfoFirma}}</td>
                            @else
                                <td style="font-size:8px; text-align:right;"></td>
                            @endif
                        </tr>
                        <tr>
                            @if ($theRes != NULL && $theRes->chemwstForRes != 'empty')
                                @if (str_contains($theRes->chemwstForRes, 'CHE'))
                                    <td style="font-size:8px; text-align:left;">{{$theRes->chemwstForRes}} MWST</td>
                                @else
                                    <td style="font-size:8px; text-align:left;">{{$theRes->chemwstForRes}}</td>
                                @endif
                            @else
                                <td style="font-size:8px; text-align:left;">CHE-xxx.xxx.xxx MWST</td>
                            @endif
                            @if($orExInfo != NULL)
                                <td style="font-size:8px; text-align:right;">{{$orExInfo->exInfoName}} {{$orExInfo->exInfoLastname}}</td>
                            @else
                                <td style="font-size:8px; text-align:right;"></td>
                            @endif
                        </tr>
                        @if($orExInfo != NULL)
                        <tr>
                            <td style="font-size:8px; text-align:left;"></td>
                            <td style="font-size:8px; text-align:right;">{{$orExInfo->exInfoEmail}}</td>
                        </tr>
                        <tr>
                            <td style="font-size:8px; text-align:left;"></td>
                            <td style="font-size:8px; text-align:right;">{{$orExInfo->exInfoClPhoneNr}}</td>
                        </tr>
                        <tr>
                            <td style="font-size:8px; text-align:left;"></td>
                            <td style="font-size:8px; text-align:right;">{{$orExInfo->exInfoDaysToPay}} Tage zu zahlen</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            @if($items->Restaurant == 81)
                            <td style="font-size:12px; text-align:left; margin:0px; padding-left:0px; color:white;"> 
                                -
                            </td>
                            @else
                            <td style="font-size:12px; text-align:left; margin:0px; padding-left:0px;"> 
                                @if($items->nrTable == 500)
                                <strong>Takeaway</strong>
                                @elseif($items->nrTable == 9000)
                                <strong>Delivery</strong>
                                @else
                                <strong>Tisch:</strong> {{$items->nrTable}}
                                @endif
                            </td>
                            @endif
                            <td  style="font-size:7px; text-align:right; margin:0px; padding-right:0px; margin-right:0px;" >Es bedient Sie: 
                                @if ($items->servedBy != 0)
                                    @if (User::find($items->servedBy) != NULL)
                                    <strong>{{User::find($items->servedBy)->name}}</strong>
                                    @else
                                    <strong>--- ---</strong>
                                    @endif
                                @else
                                <strong>--- ---</strong>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            @if($items->Restaurant == 81)
                            <td style="font-size:13px; margin:0px; padding:0px; color:white;"><strong></strong></td>
                            @else
                            <td style="font-size:13px; margin:0px; padding:0px;"><strong>Restaurant Rechnung</strong></td>
                            @endif
                            <td style="text-align: right; margin:0px; padding:0px;">Verkaufs-ID: {{$items->id}}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                        </tr>
                        @if($items->resComment != 'none' && $items->resComment != Null)
                        <tr>
                            <td colspan="2" style="text-align:center; margin:0px; padding:0px;">
                                <pre style="font-size:13px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height:1.01; text-align: center !important; margin:0px; padding:0px 15px 0px 15px; white-space: pre-wrap; word-wrap: break-word;">{{ preg_replace('/^\x{FEFF}|\x{200B}|\x{00A0}|\t/u', '',$items->resComment) }}</pre>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                        </tr>
                        @endif
                    </table>
                </td>
            </tr>
        
            <tr class="heading">
                <td style="width:5%; margin-top:4px; margin-bottom:4px;">
                    <p style="padding:5px 15px 5px 15px; text-align:left !important;">Menge</p>
                </td>
                <td style="width:50%; margin-top:4px; margin-bottom:4px; ">
                    <p style="padding:5px 15px 5px 15px; text-align:left !important;">Produkte</p>
                </td>
                <td style="text-align: right; margin-top:4px; margin-bottom:4px;">
                    <p style="padding:5px 15px 5px 15px;">Einen</p> <!-- Bezahlverfahren -->
                </td>
                <td style="text-align: right; margin-top:4px; margin-bottom:4px;">
                    <p style="padding:5px 15px 5px 15px;">Gesamt</p>
                </td>
                <td style="text-align: right; margin-top:4px; margin-bottom:4px;">
                    <p style="padding:5px 15px 5px 15px;">MwSt</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            @if($items->freeProdId != 0)
                <tr class="details">
                    <td style="width:5%;">
                        1
                    </td>   
                    <td style="width:50%;">
                        {{Produktet::find($items->freeProdId)->emri}}
                    </td>
                    <td style="text-align: right;">
                        Freier
                    </td>
                    <td style="text-align: right;">
                        Freier
                    </td>
                    <td></td>
                </tr>
            @endif
            
            @php
                $products = explode('---8---', $items->porosia);
                $mappedProducts = [];

                foreach ($products as $product) {
                    $prod = explode('-8-', $product);

                    if(number_format($prod[4], 2, '.', '') > 0){

                        if(str_contains($prod[3], '/')){
                            $mappedProducts[] = [
                                "productName" => $prod[0] ?? '',
                                "quantity"    => $prod[3] ?? 1,
                                "price"       => $prod[4],
                                "type"        => $prod[5] ?? '',
                                "prod_id"     => $prod[7] ?? '',
                                "ex"          => $prod[2] ?? '',
                                "grouped"     => false
                            ];
                        } else if(isset($mappedProducts[$prod[7]])){
                            $mappedProducts[$prod[7]] = [
                                "productName" => $prod[0] ?? '',
                                "quantity"    => $prod[3] ? $mappedProducts[$prod[7]]['quantity'] + $prod[3] : ++$mappedProducts[$prod[7]]['quantity'],
                                "price"       => $mappedProducts[$prod[7]]['price'] + $prod[4],
                                "type"        => $prod[5] ?? '',
                                "prod_id"     => $prod[7] ?? '',
                                "ex"          => $prod[2] ?? '',
                                "grouped"     => true
                            ];
                        } else {
                            $mappedProducts[$prod[7]] = [
                                "productName" => $prod[0] ?? '',
                                "quantity"    => $prod[3] ?? 1,
                                "price"       => $prod[4],
                                "type"        => $prod[5] ?? '',
                                "prod_id"     => $prod[7] ?? '',
                                "ex"          => $prod[2] ?? '',
                                "grouped"     => false
                            ];
                        }
                    }
                }
            @endphp

            @foreach($mappedProducts as $produkti)
                <?php
                    if($items->nrTable != 500 && $items->nrTable != 9000){
                        $kaTakeaway = False;
                    }else if($items->nrTable == 500){
                        if($items->userEmri == 'admin' || $items->userPhoneNr == '0000000000'){
                            $taProdIns = Takeaway::where('prod_id', $produkti['prod_id'])->first();
                        }else{
                            $taProdIns = Takeaway::find($produkti['prod_id']);
                        }
                        $kaTakeaway = True;
                    }else if($items->nrTable == 9000){
                        $taProdIns = DeliveryProd::find($produkti['prod_id']);
                        $kaTakeaway = True;
                    }
                ?>
                <tr class="details" style="margin-bottom:2px;">
                    <td style="width:5%;">
                        <p style="padding:2px; padding-top:7px;">{{$produkti['quantity']}} X</p>
                    </td>   
                    <td style="width:50%; text-align:left !important; margin:0px !important;">
                        <p style="padding:2px; line-height: 11px !important; margin:0px !important;">
                            {{$produkti['productName']}}
                            @if($produkti['quantity'] <= 1 && $produkti['type'] != '' && $produkti['type'] != 'empty')
                                <span style="opacity:0.6; ">( {{$produkti['type']}} )</span>
                            @endif  
                            @if($produkti['ex'] != '')
                                <br><?php $thE = explode('--0--', $produkti['ex']);?>
                                @foreach ($thE as $ex)
                                    @if ($ex != '' && $ex != 'empty')
                                        <p style="margin-right:10px; font-weight:normal; opacity:0.7; font-size:10px;">+ {{ explode('||',$ex)[0]}}</p>
                                    @endif
                                @endforeach
                            @endif
                        </p>
                    </td>
                    <?php
                        if(str_contains($produkti['quantity'],'/')){
                            $prod3_2D = explode('/',$produkti['quantity']);
                            $sasiaProdThis = $prod3_2D[0] / $prod3_2D[1];
                        }else{
                            $sasiaProdThis = $produkti['quantity'];
                        } 
                    ?>
                    <td style="text-align: right;">
                        @if (!$produkti['grouped'])
                            <p style="padding:2px;">{{number_format($produkti['price'] / $sasiaProdThis, 2, '.', '')}}<sup style="font-size:8px; margin:0px; padding:0px;">CHF</sup></p>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <p style="padding:2px;">{{number_format($produkti['price'], 2, '.', '')}}<sup style="font-size:8px; margin:0px; padding:0px;">CHF</sup></p>
                    </td>
                    <td style="text-align: right;">

                        @if ($theRes->resTvsh == 0)
                            <p style="padding:2px;">{{number_format(0, 2, '.', '')}} %</p>

                        @else
                            @if($items->inCashDiscount > 0)
                                <?php $totZbritja = number_format($items->inCashDiscount, 2, '.', ''); ?>
                                @if($items->nrTable == 500)
                                    @if($kaTakeaway && $taProdIns != NULL) 
                                        <?php
                                            if($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60){
                                                $cal1 = number_format(($produkti['price'] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                $cal2 = number_format($produkti['price'] - $cal1, 9, '.', '');
                                                $mwstFor25 += number_format($cal2*$loTvsh, 9, '.', '');
                                                $totMwst += number_format($cal2*$loTvsh, 9, '.', '');
                                            }else if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10){
                                                $cal1 = number_format(($produkti['price'] * $totZbritja) / $totFromProductePrice , 9, '.', '');
                                                $cal2 = number_format($produkti['price'] - $cal1, 9, '.', '');
                                                $mwstFor77 += number_format($cal2*$hiTvsh, 9, '.', '');
                                                $totMwst += number_format($cal2*$hiTvsh, 9, '.', '');
                                            }
                                        ?>
                                        @if($date2D[0] <= 2023)
                                            @if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10)
                                                <p style="padding:2px;">{{number_format(7.70, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60)
                                                <p style="padding:2px;">{{number_format(2.50, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 0.00)
                                                <p style="padding:2px;">{{number_format(0.00, 2, '.', '')}} %</p>
                                            @endif
                                        @else
                                            @if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10)
                                                <p style="padding:2px;">{{number_format(8.10, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60)
                                                <p style="padding:2px;">{{number_format(2.60, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 0.00)
                                                <p style="padding:2px;">{{number_format(0.00, 2, '.', '')}} %</p>
                                            @endif
                                        @endif
                                    @else
                                        @if($date2D[0] <= 2023)
                                        <p style="padding:2px;">2.50 %</p>
                                        @else
                                        <p style="padding:2px;">2.60 %</p>
                                        @endif
                                        <?php 
                                            $cal1 = number_format(($produkti['price']* $totZbritja) / $totFromProductePrice , 9, '.', '');
                                            $cal2 = number_format($produkti['price'] - $cal1, 9, '.', '');
                                            $totMwst += number_format($cal2*$loTvsh, 9, '.', ''); 
                                            $mwstFor25 += number_format($cal2*$loTvsh, 9, '.', '');
                                        ?>
                                    @endif
                                @else
                                    @if($date2D[0] <= 2023)
                                    <p style="padding:2px;">7.70 %</p>
                                    @else
                                    <p style="padding:2px;">8.10 %</p>
                                    @endif
                                    <?php 
                                        $cal1 = number_format(($produkti['price']* $totZbritja) / $totFromProductePrice , 9, '.', '');
                                        $cal2 = number_format($produkti['price'] - $cal1, 9, '.', '');
                                        $totMwst += number_format($cal2*$hiTvsh, 9, '.', ''); 
                                        $mwstFor77 += number_format($cal2*$hiTvsh, 9, '.', ''); 
                                    ?> 
                                @endif


                            @elseif($items->inPercentageDiscount > 0)
                                <?php $totZbritjaPrc = number_format($items->inPercentageDiscount / 100, 2, '.', ''); ?>
                                @if($items->nrTable == 500)
                                    @if($kaTakeaway && $taProdIns != NULL) 
                                        <?php
                                            if($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60){
                                                $cal1 = number_format($produkti['price'] * $totZbritjaPrc, 9, '.', '');
                                                $cal2 = number_format($produkti['price'] - $cal1, 9, '.', '');
                                                $mwstFor25 += number_format($cal2*$loTvsh, 9, '.', '');
                                                $totMwst += number_format($cal2*$loTvsh, 9, '.', '');
                                            }else if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10){
                                                $cal1 = number_format($produkti['price'] * $totZbritjaPrc, 9, '.', '');
                                                $cal2 = number_format($produkti['price'] - $cal1, 9, '.', '');
                                                $mwstFor77 += number_format($cal2*$hiTvsh, 9, '.', '');
                                                $totMwst += number_format($cal2*$hiTvsh, 9, '.', '');
                                            }
                                        ?>
                                        @if($date2D[0] <= 2023)
                                            @if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10)
                                                <p style="padding:2px;">{{number_format(7.70, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60)
                                                <p style="padding:2px;">{{number_format(2.50, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 0.00)
                                                <p style="padding:2px;">{{number_format(0.00, 2, '.', '')}} %</p>
                                            @endif
                                        @else
                                            @if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10)
                                                <p style="padding:2px;">{{number_format(8.10, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60)
                                                <p style="padding:2px;">{{number_format(2.60, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 0.00)
                                                <p style="padding:2px;">{{number_format(0.00, 2, '.', '')}} %</p>
                                            @endif
                                        @endif
                                    @else
                                        @if($date2D[0] <= 2023)
                                        <p style="padding:2px;">2.50 %</p>
                                        @else
                                        <p style="padding:2px;">2.60 %</p>
                                        @endif
                                        <?php 
                                            $cal1 = number_format($produkti['price'] * $totZbritjaPrc, 9, '.', '');
                                            $cal2 = number_format($produkti['price'] - $cal1, 9, '.', '');
                                            $totMwst += number_format($cal2*$loTvsh, 9, '.', ''); 
                                            $mwstFor25 += number_format($cal2*$loTvsh, 9, '.', '');
                                        ?>
                                    @endif
                                @else
                                    @if($date2D[0] <= 2023)
                                    <p style="padding:2px;">7.70 %</p>
                                    @else
                                    <p style="padding:2px;">8.10 %</p>
                                    @endif
                                    <?php 
                                        $cal1 = number_format($produkti['price'] * $totZbritjaPrc, 9, '.', '');
                                        $cal2 = number_format($produkti['price'] - $cal1, 9, '.', '');
                                        $totMwst += number_format($cal2*$hiTvsh, 9, '.', ''); 
                                        $mwstFor77 += number_format($cal2*$hiTvsh, 9, '.', ''); 
                                    ?> 
                                @endif
                            @else

                                @if($items->nrTable == 500)
                                    @if($kaTakeaway && $taProdIns != NULL) 
                                        <?php
                                            if($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60){
                                                $mwstFor25 += number_format($produkti['price']*$loTvsh, 9, '.', '');
                                                $totMwst += number_format($produkti['price']*$loTvsh, 9, '.', '');
                                            }else if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10){
                                                $mwstFor77 += number_format($produkti['price']*$hiTvsh, 9, '.', '');
                                                $totMwst += number_format($produkti['price']*$hiTvsh, 9, '.', '');
                                            }
                                        ?>
                                        @if($date2D[0] <= 2023)
                                            @if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10)
                                                <p style="padding:2px;">{{number_format(7.70, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60)
                                                <p style="padding:2px;">{{number_format(2.50, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 0.00)
                                                <p style="padding:2px;">{{number_format(0.00, 2, '.', '')}} %</p>
                                            @endif
                                        @else
                                            @if($taProdIns->mwstForPro == 7.70 || $taProdIns->mwstForPro == 8.10)
                                                <p style="padding:2px;">{{number_format(8.10, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 2.50 || $taProdIns->mwstForPro == 2.60)
                                                <p style="padding:2px;">{{number_format(2.60, 2, '.', '')}} %</p>
                                            @elseif($taProdIns->mwstForPro == 0.00)
                                                <p style="padding:2px;">{{number_format(0.00, 2, '.', '')}} %</p>
                                            @endif
                                        @endif
                                    @else
                                        @if($date2D[0] <= 2023)
                                        <p style="padding:2px;">2.50 %</p>
                                        @else
                                        <p style="padding:2px;">2.60 %</p>
                                        @endif
                                        <?php 
                                            $totMwst += number_format($produkti['price']*$loTvsh, 9, '.', ''); 
                                            $mwstFor25 += number_format($produkti['price']*$loTvsh, 9, '.', '');
                                        ?>
                                    @endif
                                @else
                                    @if($date2D[0] <= 2023)
                                    <p style="padding:2px;">7.70 %</p>
                                    @else
                                    <p style="padding:2px;">8.10 %</p>
                                    @endif
                                    <?php 
                                        $totMwst += number_format($produkti['price']*$hiTvsh, 9, '.', ''); 
                                        $mwstFor77 += number_format($produkti['price']*$hiTvsh, 9, '.', ''); 
                                    ?> 
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
             @endforeach
        </table>

        <table style="border-top:1px solid #555;">
            @if (isset($totZbritja))
            <tr class="total" style="margin-bottom:0px;">
                <td></td>
                <td><strong>Rabatt:</strong></td>
                <td style="text-align: right; margin:0px;"><strong> - {{$totZbritja}} CHF</strong></td>
            </tr>
            @endif
            @if ($items->cuponOffVal > 0)
            <tr class="total" style="margin-bottom:0px;">
                <td></td>
                <td style="padding-right:0px;"><strong>Gutscheincode:</strong></td>
                <td style="text-align: right; margin:0px; padding-right:0px;"><strong> - {{ number_format($items->cuponOffVal, 2, '.', '') }} CHF</strong></td>
            </tr>
            @endif
            @if ($items->cuponProduct != 'empty')
            <tr class="total" style="margin-bottom:0px;">
                <td></td>
                <td style="padding-right:0px;"><strong>Gutscheincode:</strong></td>
                <td style="text-align: right; margin:0px; padding-right:0px;"><strong> + {{ $items->cuponProduct }}</strong></td>
            </tr>
            @endif
          
            <tr class="total" style="margin-bottom:0px;">
                <td></td>
                <td> Zwischensumme: </td>
                <td style="text-align: right; margin:0px;">
                    {{ number_format($totShuma-$totMwst, 2, '.', '') }} CHF
                </td>
            </tr>

            @if ($theRes->resTvsh == 0)
                <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                    <td></td>
                    <td> MwSt 0.00%: </td>
                    <td style="text-align: right; margin:0px;">
                    {{ number_format(0, 2, '.', '') }} CHF
                </tr>
            @else
                @if ($mwstFor25 > 0)
                <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                    <td></td>
                    @if($date2D[0] <= 2023)
                    <td> MwSt 2.50%: </td>
                    @else
                    <td> MwSt 2.60%: </td>
                    @endif
                    <td style="text-align: right; margin:0px;">
                    {{ number_format($mwstFor25, 2, '.', '') }} CHF
                </tr>
                @endif
                @if ($mwstFor77 > 0)
                <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                    <td></td>
                    @if($date2D[0] <= 2023)
                    <td> MwSt 7.70%: </td>
                    @else
                    <td> MwSt 8.10%: </td>
                    @endif
                    <td style="text-align: right; margin:0px;">
                    {{ number_format($mwstFor77, 2, '.', '') }} CHF
                </tr>
                @endif
            @endif

            @if($items->tipPer != 0)

            <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                <td></td>
                <td>Kellner Trinkgeld</td>
                <td style="text-align: right; margin:0px;">
                  {{number_format($items->tipPer, 2, '.', '') }} CHF
                </td>
            </tr>
            @endif

            @if($items->nrTable == 9000)
                <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                    <td></td>
                    <td>Liefergebühr</td>
                    <td style="text-align: right; margin:0px;">
                    {{number_format(explode('|||',$items->TAplz)[1], 2, '.', '') }} CHF
                    </td>
                </tr>
            @endif

            @if(PiketLog::where('order_u',$items->id)->first() != null && PiketLog::where('order_u',$items->id)->first()->piket < 0)
                <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                    <td></td>
                    <td>Punkte</td>
                    <td style="text-align: right; margin:0px;">
                    - {{ $moneyOff }} CHF
                    </td>
                </tr>
            @elseif(PiketLog::where('order_u',$items->id)->first() != null && PiketLog::where('order_u',$items->id)->first()->piket > 0  && $items->byId != 0)
                <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                    <td></td>
                    <td>Punkte</td>
                    <td style="text-align: right; margin:0px;">
                    + {{ $pointsEr }} p
                    </td>
                </tr>
            @endif
            @if ($items->dicsountGcAmnt > 0)
            <tr class="total" style="margin-bottom:0px;">
                <td></td>
                <td style="padding-right:0px;"><strong>Geschenkkarte:</strong></td>
                <td style="text-align: right; margin:0px; padding-right:0px;"><strong> - {{ number_format($items->dicsountGcAmnt, 2, '.', '') }} CHF</strong></td>
            </tr>
            @endif

            <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                <td></td>
                <td style="padding-right:0px;"><strong>Gesamtsumme:</strong></td>
                <td style="text-align: right; margin:0px; padding-right:0px;">
                   <strong>{{number_format($totShuma-$items->dicsountGcAmnt, 2, '.', '')}} CHF</strong>
                </td>
            </tr>
            <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                <td></td>
                <td style="padding-right:0px;"><strong>Zahlungsart:</strong></td>
                <td style="text-align: right; margin:0px; padding-right:0px;">
                @if ($items->dicsountGcAmnt > 0 && number_format($totShuma-$items->dicsountGcAmnt, 2, '.', '') == 0)
                    <strong>Geschenkkarte</strong>
                @else
                    @if ($items->payM == 'Rechnung')
                    <strong>Auf Rechnung</strong>
                    @else
                    <strong>{{$items->payM}}</strong>
                    @endif
                @endif
                </td>
            </tr>
            @if($items->cancelComm != 'empty')
                <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                    <td></td>
                    <td style="color:red; padding-right:0px; margin:0px; line-height: 1;"><strong>Annulliert:</strong></td>
                    <td style="text-align: right; margin:0px; padding-right:0px; color:red; line-height: 1;">
                        <strong>{{$items->cancelComm}}</strong>
                    </td>
                </tr>
            @endif
            <?php
                $thePMChngIns = logOrderPayMChng::where('orderId',$items->id)->orderBy('created_at', 'desc')->first();
                if($thePMChngIns != Null){
                    $dt = explode(' ',$thePMChngIns->created_at)[0];
                    $hr = explode(' ',$thePMChngIns->created_at)[1];

                    $dt2D = explode('-',$dt);
                    $hr2D = explode(':',$hr);

                    $theUsr = User::find($thePMChngIns->staffId);
                }
            ?>
            @if($thePMChngIns != Null)
                <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                    <td></td>
                    <td colspan="2" style="color:red; padding-right:0px; margin:0px; line-height: 1; font-weight:bold;">
                        Zahlungsart wurde am {{$dt2D[2]}}.{{$dt2D[1]}}.{{$dt2D[0]}} um {{$hr2D[0]}}:{{$hr2D[1]}} Uhr von 
                        {{$thePMChngIns->payMPrevious}} auf {{$thePMChngIns->payMCurrent}} geändert durch 
                        @if ($theUsr != Null)
                            {{$theUsr->name}}
                        @else
                            ---
                        @endif
                        ( {{$thePMChngIns->staffId}} )
                    </td>
                </tr>
            @endif
        </table>

        @if ($orderPOSData != Null)
            <table style="border-top:1px solid #555;">
                <tr class="total" style="margin-bottom:0px;">
                    <td colspan="2" style="text-align: center; margin:0px;"><strong>{{$orderPOSData->DisplayName}} contactless</strong></td>
                </tr>
                <tr class="total" style="margin-bottom:0px;">
                    <td colspan="2" style="text-align: center; margin:0px;"><strong>{{$orderPOSData->AppPANPrtCardholder}}</strong></td>
                </tr>
                <tr class="total" style="margin-bottom:0px;">
                    <td><strong>{{$TrxDate[4]}}{{$TrxDate[5]}}.{{$TrxDate[2]}}{{$TrxDate[3]}}.{{$TrxDate[1]}}{{$TrxDate[1]}}</strong></td>
                    <td style="text-align: right; margin:0px;"><strong>{{$TrxTime[0]}}{{$TrxTime[1]}}:{{$TrxTime[2]}}{{$TrxTime[3]}}:{{$TrxTime[4]}}{{$TrxTime[5]}}</strong></td>
                </tr>
                <tr class="total" style="margin-bottom:0px;">
                    <td><strong>Trm-Id: </strong></td>
                    <td style="text-align: right; margin:0px;"><strong>{{$orderPOSData->TrmID}}</strong></td>
                </tr>
                <tr class="total" style="margin-bottom:0px;">
                    <td><strong>Akt-Id: </strong></td>
                    <td style="text-align: right; margin:0px;"><strong>x</strong></td>
                </tr>
                <tr class="total" style="margin-bottom:0px;">
                    <td><strong>AID: </strong></td>
                    <td style="text-align: right; margin:0px;"><strong>{{$orderPOSData->aid}}</strong></td>
                </tr>
                <tr class="total" style="margin-bottom:0px;">
                    <td><strong>Trx. Seq-Cnt: </strong></td>
                    <td style="text-align: right; margin:0px;"><strong>{{$orderPOSData->TrxSeqCnt}}</strong></td>
                </tr>
                  <tr class="total" style="margin-bottom:0px;">
                    <td><strong>Trx. Ref-No: </strong></td>
                    <td style="text-align: right; margin:0px;"><strong>{{$orderPOSData->TrxRefNum}}</strong></td>
                </tr>
                  <tr class="total" style="margin-bottom:0px;">
                    <td><strong>Auth. Code: </strong></td>
                    <td style="text-align: right; margin:0px;"><strong>{{$orderPOSData->AuthC}}</strong></td>
                </tr>
                  <tr class="total" style="margin-bottom:0px;">
                    <td><strong>Acq-Id: </strong></td>
                    <td style="text-align: right; margin:0px;"><strong>{{$orderPOSData->AcqID}}</strong></td>
                </tr>
                <tr class="total" style="margin-bottom:0px; margin-top:0.4cm;">
                    <td colspan="2" style="text-align: center; margin:0px;"><strong>{{$orderPOSData->AppPANEnc}}</strong></td>
                </tr>
                <tr class="total" style="margin-bottom:0px; margin-top:0.4cm;">
                    <td style="font-size: 1.2rem;"><strong>Total-EFT CHF: </strong></td>
                    <td style="text-align: right; margin:0px; font-size: 1.2rem;"><strong>{{number_format($orderPOSData->TrxAmt/100, 2, '.', '')}}</strong></td>
                </tr>

            </table>
            
        @endif


        
    <div class="thanks">
        <p><strong>Danke für Ihren Besuch!</strong></p>
        <br>
        <br>
        <p>QR-Code scannen und Rechnung herunterladen</p>
        <br>
        <br>
        @if($items->digitalReceiptQRK != 'empty')
            <img style="width:100px; height:100px; margin:0.2cm 0 0 0; padding:0px;" src="storage/digitalReceiptQRK/{{$items->digitalReceiptQRK}}" alt="">
        @endif
        <br>
        <br>
        <br>
        <p>Kontaktlos bestellen & bezahlen mit QRorpa Systeme</p>
        <img width="110px" src="{{ public_path() . '/storage/images/logo_QRorpa.png' }}" id="logo" />
    </div>
    </div>
   
</body>
</html>