<?php


use App\User;
use App\Orders;
use App\PiketLog;
use App\Takeaway;
use App\Produktet;
use App\Restorant;
use App\OrdersPassive;
use App\emailReceiptFromAdm;
use App\giftCardRechnungPay;
use App\OPSaferpayReference;
use Intervention\Image\ImageManagerStatic as Image;

?>

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

   
    $totShuma = number_format($items->gcSumInChf, 2, '.', '');
    
    $theRes = Restorant::findOrFail($items->toRes);
    $adr2D =  explode(',',$theRes->adresa);
    $adr1 =  $adr2D[0];
    $adr2 = '---' ;
    if(isset($adr2D[1])){
        $adr2 = $adr2D[1] ;
    }
    if(isset($adr2D[2])){
        $adr2 = $adr2D[1].','.$adr2D[2] ;
    }
   
    $exInfo = giftCardRechnungPay::where('gcId',$items->id)->first();
    

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
    $endPayDt = date('d.m.Y', strtotime($startDt. ' + '.$exInfo->clDaysToPay.' day'));

    // GET PAGES ON FINAL
    $pdfname = 'storage/giftCardRechnungBill/rechnungBillFirst'.$theRes->emri.'_'.$items->id.'.pdf';
    $pdftext = file_get_contents($pdfname);
    $pages = preg_match_all("/\/Page\W/", $pdftext, $dummy);
    // ------------------------------ 

    $rechSignature = '';
    if($exInfo != Null){
        $rechSignature = $exInfo->clSignature;
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
                                <!-- <p style="margin:0px; padding:0px; margin-top:-8px;">E-Mail: firstADM->email </p> -->
                                @if (str_contains($theRes->chemwstForRes, 'CHE'))
                                    <p style="margin:0px; padding:0px; margin-top:-8px;">MwST-Nr. {{ $theRes->chemwstForRes }}</p>
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
                                <p style="margin:0px; padding:0px; margin-top:-8px; font-weight:normal !important;">{{$exInfo->clFirma}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px; font-weight:normal;">{{$exInfo->clLastname}} {{$exInfo->clName}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px;">{{$exInfo->clStreetNr}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">{{$exInfo->clPlzOrt}}</p>
                            </td>

                            <td >
                                <p style="margin:0px; padding:0px; margin-top:-12px; font-size:9px;"></p>
                                <p style="margin:0px; padding:0px; margin-top:-8px;">Rechnungsnummer: #{{ $items->refId }}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px;">Datum/Zeit: {{$orDt2d[2]}}.{{$orDt2d[1]}}.{{$orDt2d[0]}} / {{$orTi2d[0]}}:{{$orTi2d[1]}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">Zahlungsfrist bis: {{ $endPayDt }}</p>
                                @if ($exInfo->clComment != 'empty')
                                    <p style="margin:0px; padding:0px; margin-top:-4px;">Kommentar: {{ $exInfo->clComment }}</p>
                                @endif
                            </td>                                                       
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="font-size:12px;" colspan="2">Chur, {{ date("d") }} {{ $nowMonth }} {{ date("Y") }}</td>
                <td style="font-size:12px;" colspan="2">Sie wurden bedient von: 
                    @if ($items->soldByStaff != 0)
                        @if (User::find($items->soldByStaff) != NULL)
                            {{User::find($items->soldByStaff)->name}}
                        @else
                            --- ---
                        @endif
                    @else
                        --- ---
                    @endif
                </td>
            </tr>
            <tr>
                <td style="font-size:12px;" colspan="2">Geschenkkarte</td>
                <td style="font-size:12px;" colspan="2">Verkaufs-ID: {{$items->id}}</td>
            </tr>
        </table>

        <!-- FFFFFFFFFFFFFFFFFFFFFFF -->

        <table style="margin:0px !important; padding:0px !important;">
            <tr class="heading">
                <td colspan="2" style="text-align:center; margin-top:4px; margin-bottom:4px;">
                    <p style="padding:5px 15px 5px 15px;">Geschenkkartendaten</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center; margin-top:4px; margin-bottom:4px;">
                    @if ($theGC->clName != 'empty')
                        <p style="line-height:1.1; padding:5px 15px 5px 15px;">Name: <br>{{$theGC->clName}}</p>
                    @else
                        <p style="line-height:1.1; padding:5px 15px 5px 15px;">Name: <br>---</p>
                    @endif
                </td>
                <td style="text-align:center; margin-top:4px; margin-bottom:4px;">
                    @if ($theGC->clLastname != 'empty')
                        <p style="line-height:1.1; padding:5px 15px 5px 15px;">Nachname: <br>{{$theGC->clLastname}}</p>
                    @else
                        <p style="line-height:1.1; padding:5px 15px 5px 15px;">Nachname: <br>---</p>
                    @endif
                </td>
            </tr>
            <tr>	
                <td style="text-align:center; margin-top:4px; margin-bottom:4px;">
                    @if ($theGC->clEmail != 'empty')
                        <p style="line-height:1.1; padding:5px 15px 5px 15px;">E-Mail: <br>{{$theGC->clEmail}}</p>
                    @else
                        <p style="line-height:1.1; padding:5px 15px 5px 15px;">E-Mail: <br>---</p>
                    @endif
                </td>
                <td style="text-align:center; margin-top:4px; margin-bottom:4px;">
                    @if ($theGC->clPhNr != 'empty')
                        <p style="line-height:1.1; padding:5px 15px 5px 15px;">Telefonnummer: <br>{{$theGC->clPhNr}}</p>
                    @else
                        <p style="line-height:1.1; padding:5px 15px 5px 15px;">Telefonnummer: <br>---</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align:center; margin-top:4px; margin-bottom:4px;">
                    <p style="font-size:22px; font-weight:bold; padding:5px 15px 5px 15px;">CHF</p>
                </td>
                <td style="text-align:center; margin-top:4px; margin-bottom:4px;">
                    <p style="font-size:22px; font-weight:bold; padding:5px 15px 5px 15px;">{{ number_format($theGC->gcSumInChf, 2, '.', '') }} CHF</p>
                </td>
            </tr>
        </table>

        <table style="border-top:1px solid #555;">
    
            <tr class="total" style="margin-bottom:0px;">
                <td id="signatureRowTD" class="signatureRowTDCls" rowspan="3">
                    <img class="signaturePic" src="{{ public_path() . '/storage/rechnungPaySignatures/'.$rechSignature }}" alt="">
                </td>
                <td> Zwischensumme: </td>
                <td style="text-align: right; margin:0px;">
                    {{ number_format($theGC->gcSumInChf, 2, '.', '') }} CHF
                </td>
            </tr>

            <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                <td> MwSt 0.00%: </td>
                <td style="text-align: right; margin:0px;">
                {{ number_format(0, 2, '.', '') }} CHF
            </tr>
      
            <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                <td style="padding-right:0px;"><strong>Gesamtsumme:</strong></td>
                <td style="text-align: right; margin:0px; padding-right:0px;">
                   <strong>{{number_format($theGC->gcSumInChf, 2, '.', '')}} CHF</strong>
                </td>
            </tr>

            <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                <td style="text-align:center;"><strong>Diese Rechnung wurde digital unterzeichnet.</strong></td>
                <td style="padding-right:0px;"><strong>Zahlungsart:</strong></td>
                <td style="text-align: right; margin:0px; padding-right:0px;">
                    <strong>{{$theGC->payM}}</strong>
                </td>
            </tr>
        </table>

        <div style="position:absolute; top:22cm;">
            <div class="footerP1" >
                <p style="margin:0px; padding:0px; margin-top:20px; width:100%;"><strong>Zahlungskondition: {{ $exInfo->clDaysToPay }} Tage / Zahlbar bis {{ $endPayDt }}</strong></p>
                <p style="margin:0px; padding:0px; margin-top:-5px; width:100%;"><strong>Besten Dank für Ihren Besuch!</strong></p>
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
                                    <p style="margin:0px; padding:0px; margin-top:-8px;">MwST-Nr. {{ $theRes->chemwstForRes }}</p>
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
                                <p style="margin:0px; padding:0px; margin-top:-8px; font-weight:normal !important;">{{$exInfo->clFirma}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px; font-weight:normal;">{{$exInfo->clLastname}} {{$exInfo->clName}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-6px;">{{$exInfo->clStreetNr}}</p>
                                <p style="margin:0px; padding:0px; margin-top:-4px;">{{$exInfo->clPlzOrt}}</p>
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
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->clFirma}}</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->clStreetNr}}</p>
                    <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->clPlzOrt}}</p>
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
                                <img style="width: 5cm; margin: 0px; padding:0px; margin-top:15px;" src="{{ public_path() . '/storage/giftCardEbankqrcode/'.$items->ebankqrcode }}" id="logo" />
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
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->clFirma}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->clStreetNr}}</p>
                                <p style="margin: 0px; padding:0px; margin-top:-10px; font-size:11px;">{{$exInfo->clPlzOrt}}</p>
                            </td>
                        </tr>
                    </table>
                
                </td>
            </tr>
        </table>
    </div>
  

  
   
</body>
</html>