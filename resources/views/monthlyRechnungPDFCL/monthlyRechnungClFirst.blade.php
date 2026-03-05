<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monatliche Rechnung</title>
    
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

        
        footer {
            position: fixed; 
            bottom: -20px; 
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

    use App\emailReceiptFromAdm;
use App\Orders;
use App\Takeaway;
    use App\PiketLog;
    use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use App\OrdersPassive;
use App\rechnungClient;
    use App\User;
    use Illuminate\Support\Facades\Auth;

    $theRes = Restorant::findOrFail($resID);
    $adr2D =  explode(',',$theRes->adresa);
    $adr1 =  $adr2D[0];
    $adr2 = '---' ;
    if(isset($adr2D[1])){ $adr2 = $adr2D[1]; }
    if(isset($adr2D[2])){ $adr2 = $adr2D[1].','.$adr2D[2] ; }

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

    $orDt2d = explode('-',explode(' ',Carbon::now())[0]);
    $orTi2d = explode(':',explode(' ',Carbon::now())[1]);

    $startDt = $orDt2d[2].'.'.$orDt2d[1].'.'.$orDt2d[0];
    $endPayDtPre = Carbon::createFromFormat('d.m.Y', $theClient->daysToPay.'.'.$orDt2d[1].'.'.$orDt2d[0]);
    $endPayDtPre2DDate = explode('-',explode(' ',$endPayDtPre)[0]);
    $endPayDtPre2DTime = explode(':',explode(' ',$endPayDtPre)[1]);
    $endPayDt = $endPayDtPre2DDate[2].'.'.$endPayDtPre2DDate[1].'.'.$endPayDtPre2DDate[0].' / 23:59';


    // grupohen Produktet & nxirret totali
    $totShuma = number_format(0, 4, '.', '');
    $totZbritje = number_format(0, 4, '.', '');
    $mwst77Tot = number_format(0, 4, '.', '');
    $mwst25Tot = number_format(0, 4, '.', '');

    $prodsAll = array();
    $prodSasia = array();
    $prodEmri = array();
    $prodAllPrice = array();
    $prodMwstVal = array();
    $prodMwstPrc = array();

    $prodsTAAll = array();
    $prodTASasia = array();
    $prodTAEmri = array();
    $prodTAAllPrice = array();
    $prodTAMwstVal = array();
    $prodTAMwstPrc = array();
    
    foreach($ordersId as $orOne){
        $or = OrdersPassive::find($orOne);
        if($or != Null){
            foreach(explode('---8---',$or->porosia) as $porOne){
                $prdDetail = explode('-8-',$porOne);
                if($or->nrTable == 500 ){
                // TA Orders
                    $taP = Takeaway::where('prod_id',$prdDetail[7])->first();
                    if($taP != Null){
                        if($taP->mwstForPro == 2.60){
                            if(in_array($prdDetail[7],$prodsTAAll)){
                                $prodTASasia[$prdDetail[7]] += number_format($prdDetail[3], 4, '.', '');
                                $prodTAAllPrice[$prdDetail[7]] += number_format($prdDetail[4], 4, '.', '');
                                $mwsValtta = number_format($prdDetail[4] * (float)0.026, 4, '.', '');
                                $prodTAMwstVal[$prdDetail[7]] += number_format($mwsValtta, 4, '.', '');
                            }else{
                                array_push($prodsTAAll,$prdDetail[7]);
                                $prodTASasia[$prdDetail[7]] = number_format($prdDetail[3], 4, '.', '');
                                $prodTAAllPrice[$prdDetail[7]] = number_format($prdDetail[4], 4, '.', '');
                                $mwsValtta = number_format($prdDetail[4] * (float)0.026, 4, '.', '');
                                $prodTAMwstVal[$prdDetail[7]] = number_format($mwsValtta, 4, '.', '');
                                $prodTAMwstPrc[$prdDetail[7]] = number_format((float)2.60, 4, '.', '');
                                $prodTAEmri[$prdDetail[7]] = $prdDetail[0];
                            }
                            $mwst25Tot += number_format($mwsValtta, 4, '.', '');
                        }else{
                            if(in_array($prdDetail[7],$prodsAll)){
                                $prodSasia[$prdDetail[7]] += number_format($prdDetail[3], 4, '.', '');
                                $prodAllPrice[$prdDetail[7]] += number_format($prdDetail[4], 4, '.', '');
                                $mwsValtt = number_format($prdDetail[4] * (float)0.081, 4, '.', '');
                                $prodMwstVal[$prdDetail[7]] += number_format($mwsValtt, 4, '.', '');
                            }else{
                                array_push($prodsAll,$prdDetail[7]);
                                $prodSasia[$prdDetail[7]] = number_format($prdDetail[3], 4, '.', '');
                                $prodAllPrice[$prdDetail[7]] = number_format($prdDetail[4], 4, '.', '');
                                $mwsValtt = number_format($prdDetail[4] * (float)0.081, 4, '.', '');
                                $prodMwstVal[$prdDetail[7]] = number_format($mwsValtt, 4, '.', '');
                                $prodMwstPrc[$prdDetail[7]] = number_format((float)8.10, 4, '.', '');
                                $prodEmri[$prdDetail[7]] = $prdDetail[0];
                            }
                            $mwst77Tot += number_format($mwsValtt, 4, '.', '');
                        }
                    }
                

                }else{
                // Restaurant Orders
                    if(in_array($prdDetail[7],$prodsAll)){
                        $prodSasia[$prdDetail[7]] += number_format($prdDetail[3], 4, '.', '');
                        $prodAllPrice[$prdDetail[7]] += number_format($prdDetail[4], 4, '.', '');
                        $mwsValtt = number_format($prdDetail[4] * (float)0.081, 4, '.', '');
                        $prodMwstVal[$prdDetail[7]] += number_format($mwsValtt, 4, '.', '');
                    
                    }else{
                        array_push($prodsAll,$prdDetail[7]);
                        $prodSasia[$prdDetail[7]] = number_format($prdDetail[3], 4, '.', '');
                        $prodAllPrice[$prdDetail[7]] = number_format($prdDetail[4], 4, '.', '');
                        $mwsValtt = number_format($prdDetail[4] * (float)0.081, 4, '.', '');
                        $prodMwstVal[$prdDetail[7]] = number_format($mwsValtt, 4, '.', '');
                        $prodMwstPrc[$prdDetail[7]] = number_format((float)8.10, 4, '.', '');
                        $prodEmri[$prdDetail[7]] = $prdDetail[0];
                    }
                    $mwst77Tot += number_format($mwsValtt, 4, '.', '');
                }
            }
            // shuma totale (pa zbritje)
            $totShuma += number_format( $or->shuma , 4, '.', '');
            // zbritjet totale
            $totZbritje += number_format( $or->cuponOffVal , 4, '.', '');
            $totZbritje += number_format( $or->inCashDiscount , 4, '.', '');
            $zbritjaPrejPrc = number_format( $or->shuma * ($or->inPercentageDiscount/100), 4, '.', '');
            $totZbritje += number_format( $zbritjaPrejPrc , 4, '.', '');
        } 
    }

   
?>





<body id="pageCounter">

    <footer id="footerPC">
        <p style="width: 100%; text-align:right; "> <span  class="pageNumbers"></span>xxx</p>
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
                                <p style="margin:0px; padding:0px; margin-top:-8px;">E-Mail: {{ $firstADM->email }}</p>
                                <p style="margin:0px; padding:0px; margin-top:-8px;">MwST-Nr. CHE-{{ $theRes->chemwstForRes }}</p>
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
                                <p style="margin:0px; padding:0px; margin-top:-8px; font-weight:normal !important;">{{$theClient->firmaName}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px; font-weight:normal;">{{$theClient->lastname}} {{$theClient->name}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px;">{{$theClient->street}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">{{$theClient->plzort}}</p>
                            </td>

                            <td >
                                <p style="margin:0px; padding:0px; margin-top:-12px; font-size:9px;"></p>
                                <p style="margin:0px; padding:0px; margin-top:-8px;">Rechnungsnummer: #{{$rechMnthId}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px;">Datum/Zeit: {{$orDt2d[2]}}.{{$orDt2d[1]}}.{{$orDt2d[0]}} / {{$orTi2d[0]}}:{{$orTi2d[1]}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">Zahlungsfrist bis: {{ $endPayDt }}</p>
                            </td>                                                       
                        </tr>
                    </table>
                </td>
            </tr>
       
        </table>

        <table cellpadding="0" cellspacing="0">
            <tr class="heading" style="margin-top: 1.5cm;">
                <td> Menge </td>
                <td style="text-align: left;">Produkte</td>
                <td style="text-align: center;">MwSt</td>
                <td style="text-align: center;">MwSt %</td>
                <td style="text-align: right;">Gesamt</td>
            </tr>
            @foreach ($prodsAll as $prO)
            <tr class="details" style="margin-bottom:0px;">
                <td> {{number_format($prodSasia[$prO], 0, '.', '')}} X </td> 
                <td style="text-align: left;"> {{$prodEmri[$prO]}}</td>
                <td style="text-align: center;"> {{number_format($prodMwstVal[$prO], 2, '.', '')}} CHF</td>
                <td style="text-align: center;"> {{number_format($prodMwstPrc[$prO], 2, '.', '')}} %</td>
                <td style="text-align: right;"> {{number_format($prodAllPrice[$prO], 2, '.', '')}} CHF</td>
            </tr>
                @if (in_array($prO,$prodsTAAll))
                <tr class="details" style="margin-bottom:0px;">
                    <td> {{number_format($prodTASasia[$prO], 0, '.', '')}} X </td> 
                    <td style="text-align: left;"> {{$prodTAEmri[$prO]}}</td>
                    <td style="text-align: center;"> {{number_format($prodTAMwstVal[$prO], 2, '.', '')}} CHF</td>
                    <td style="text-align: center;"> {{number_format($prodTAMwstPrc[$prO], 2, '.', '')}} %</td>
                    <td style="text-align: right;"> {{number_format($prodTAAllPrice[$prO], 2, '.', '')}} CHF</td>
                </tr>
                @endif
    
            @endforeach
          
        </table>

        <table style="border-top:1px solid #555;">
         
            <tr class="total" style="margin-bottom:0px;">
                <td style="margin:0px; padding:0px;"></td>
                <td style="margin:0px; padding:0px;"> Zwischensumme: </td>
                <td style="margin:0px; padding:0px;">{{ number_format($totShuma-$mwst77Tot-$mwst25Tot, 2, '.', '') }} CHF</td>
            </tr>
            @if ($mwst77Tot > 0)
            <tr class="total" style="margin:0px; padding:0px;">
                <td style="margin:0px; padding:0px;"></td>
                <td style="margin:0px; padding:0px;"> MwSt 8.10% </td>
                <td style="margin:0px; padding:0px;">{{ number_format($mwst77Tot, 2, '.', '') }} CHF</td>
            </tr>
            @endif
            @if ($mwst25Tot > 0)
            <tr class="total" style="margin:0px; padding:0px;">
                <td style="margin:0px; padding:0px;"></td>
                <td style="margin:0px; padding:0px;"> MwSt 2.60% </td>
                <td style="margin:0px; padding:0px;">{{ number_format($mwst25Tot, 2, '.', '') }} CHF</td>
            </tr>
            @endif

            @if (isset($totZbritje))
            <tr class="total" style="margin:0px; padding:0px;">
                <td style="margin:0px; padding:0px; font-weight:bold;"></td>
                <td style="margin:0px; padding:0px; font-weight:bold;"> Rabatt: </td>
                <td style="margin:0px; padding:0px; font-weight:bold;"> {{ number_format($totZbritje, 2, '.', '') }} CHF</td>
            </tr>
            @endif
            <tr class="total" style="margin-bottom:0px;">
                <td style="margin:0px; padding:0px; font-weight:bolder;"></td>
                <td style="margin:0px; padding:0px; font-weight:bolder;"> Gesamt: </td>
                <td style="margin:0px; padding:0px; font-weight:bolder;">{{ number_format($totShuma-$totZbritje, 2, '.', '') }} CHF</td>
            </tr>
        </table>








       
        <div>
            <div class="footerP1" >
                <p style="margin:0px; padding:0px; margin-top:20px; width:100%;"><strong>Zahlungskondition: {{ $theClient->daysToPay }} Tage / Zahlbar bis {{ $endPayDt }}</strong></p>
                <p style="margin:0px; padding:0px; margin-top:-5px; width:100%;"><strong>Besten Dank für Ihren Besuch!</strong></p>
            </div>
        
            <table> 
                <tr>
                    <td><img width="110px" src="{{ public_path() . '/storage/images/logo_QRorpa.png' }}" id="logo" /></td>
                    <td style=" text-align:right !important;"><p style=" text-align:right !important; margin-top:5px;">Kontaktlos bestellen & bezahlen</p></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </table>
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
                                <p style="margin:0px; padding:0px; margin-top:-8px;">E-Mail: {{ $firstADM->email }}</p>
                                <p style="margin:0px; padding:0px; margin-top:-8px;">MwST-Nr. CHE-{{ $theRes->chemwstForRes }}</p>
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
                                <p style="margin:0px; padding:0px; margin-top:-8px; font-weight:normal !important;">{{$theClient->firmaName}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px; font-weight:normal;">{{$theClient->lastname}} {{$theClient->name}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px;">{{$theClient->street}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">{{$theClient->plzort}}</p>
                            </td>

                            <td >
                                <p style="margin:0px; padding:0px; margin-top:-12px; font-size:9px;"></p>
                                <p style="margin:0px; padding:0px; margin-top:-8px;">Rechnungsnummer: #{{$rechMnthId}}</p>
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
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theClient->firmaName}}</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theClient->street}}</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theClient->plzort}}</p>
                    <p style="color:white">.</p>
                    <p style="color:white">.</p>
            
                    <pre style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">Währung        Summe</pre>
                    <pre style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">CHF               {{ number_format($totShuma-$totZbritje, 2, '.', '') }}</pre>
                    <p></p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:8px; width:100%; text-align:right;"><strong>Akzeptanzstelle</strong></p>

                </td>

                <td colspan="2">
                    <table>
                        <tr>
                            <td style="width:5.5cm;"> 
                                <p style="font-size:1.2rem; z-index:100;"><strong>Zahlungsteil</strong></p>
                                <img style="width: 5cm; margin: 0px; padding:0px; margin-top:15px;" src="{{ public_path() . '/storage/ebankqrcodeMnth/'.$eBankQRCode}}" id="logo" />
                                <p style="color:white; margin: 0px; padding:0px;">.</p>
                                <pre style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">Währung        Summe</pre>
                                <pre style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">CHF               {{ number_format($totShuma-$totZbritje, 2, '.', '') }}</pre>

                            </td>

                            <td style="text-align:left">
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:7px;"><strong>Konto/Zahlungspflichtig an</strong></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theRes->resBankId}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theRes->emri}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$adr1}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$adr2}}</p>
                                <p></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:7px;"><strong>Zusätzliche Information</strong></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{str_pad($rechMnthId, 10, '0', STR_PAD_LEFT)}}</p>
                                <p></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:7px;"><strong>zahlbar per</strong></p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theClient->firmaName}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theClient->street}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$theClient->plzort}}</p>

                                <p style="margin: 0px; padding:0px; margin-top:3cm; font-size:14px; text-align:right !important;"></p>
                            </td>
                        </tr>
                    </table>
                
                </td>
            </tr>
        </table>
    </div>



 
  

  
   
</body>
</html>