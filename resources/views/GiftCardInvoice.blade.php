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
use App\giftCardOnlinePayReference;
use App\OPSaferpayReference;

    $date2D = explode('-',explode(' ',$theGC->created_at)[0]);
    $time2D = explode(':',explode(' ',$theGC->created_at)[1]);

    $theRes = Restorant::find($theGC->toRes);
?>

<body >
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2" style="margin:0px !important; padding:0px !important;">
                    <table style="margin:0px !important; padding:0px !important;">
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size:18px; text-align:left; margin:0; padding:0px;"><strong>Die Geschenkkarte ist einlösbar bei:</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="font-size:12px; text-align:left; margin:0px; padding:0px;"><strong>{{$theRes->emri}}</strong></td>
                            <td style="font-size:12px; text-align:right; padding:0px; margin:0px;"><strong>Geschenkkarten-ID #: {{$theGC->refId}}</strong></td>
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
                            <td style="font-size:8px; text-align:right;">
                                @if ($theGC->payM == 'Online')
                                    <?php
                                    $theRefIns = giftCardOnlinePayReference::where('giftCardId',$theGC->id)->first();
                                    ?>
                                    @if ($theRefIns != Null)
                                        <p style="margin:0px; padding:0px;">Saferpay Online: {{ $theRefIns->refPh }}</p> 
                                    @else
                                        <p style="margin:0px; padding:0px;">Saferpay Online: ---</p>
                                    @endif
                                    
                                @endif
                            </td>
                            
                        </tr>
                        <tr>
                            @if ($theRes != NULL && $theRes->resPhoneNr != 'empty')
                                <td style="font-size:8px; text-align:left;">Tel. {{$theRes->resPhoneNr}}</td>
                            @else
                                <td style="font-size:8px; text-align:left;">Tel. +41 XX XXX XX XX</td>
                            @endif
                            <td style="font-size:8px; text-align:right;"></td>
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
                            <td style="font-size:8px; text-align:right;"></td>
                        </tr>
                    
                        <tr>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td style="font-size:12px; text-align:left; margin:0px; padding-left:0px;"> 
                                <?php
                                    $GCDate2D = explode('-',explode(' ',$theGC->expirationDate)[0]);
                                ?>
                                <strong>Verfallsdatum: {{$GCDate2D[2]}}.{{$GCDate2D[1]}}.{{$GCDate2D[0]}} </strong>
                            </td>
                            <td  style="font-size:12px; text-align:right; margin:0px; padding-right:0px; margin-right:0px;" >Von:  
                                @if ($theGC->soldByStaff != 0)
                                    @if (User::find($theGC->soldByStaff) != NULL)
                                        <strong>{{User::find($theGC->soldByStaff)->name}}</strong>
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
                            <td style="font-size:13px; margin:0px; padding:0px;" colspan="2"><strong>Restaurant Geschenkkarten Rechnung</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                        </tr>
                    </table>
                </td>
            </tr>

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
                <td></td>
                <td> Zwischensumme: </td>
                <td style="text-align: right; margin:0px;">
                    {{ number_format($theGC->gcSumInChf, 2, '.', '') }} CHF
                </td>
            </tr>

          
            <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                <td></td>
                <td> MwSt 0.00%: </td>
                <td style="text-align: right; margin:0px;">
                {{ number_format(0, 2, '.', '') }} CHF
            </tr>
      
          
            <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                <td></td>
                <td style="padding-right:0px;"><strong>Gesamtsumme:</strong></td>
                <td style="text-align: right; margin:0px; padding-right:0px;">
                   <strong>{{number_format($theGC->gcSumInChf, 2, '.', '')}} CHF</strong>
                </td>
            </tr>

            <tr class="total" style="margin-top:-10px; margin-bottom:0px;">
                <td></td>
                <td style="padding-right:0px;"><strong>Zahlungsart:</strong></td>
                <td style="text-align: right; margin:0px; padding-right:0px;">
                    <strong>{{$theGC->payM}}</strong>
                </td>
            </tr>
        </table>






        <table cellpadding="0" cellspacing="0" class="thanks">
            <tr>
                <td colspan="2" style="margin:0px !important; padding:0px !important;">
                    <table style="margin:0px !important; padding:0px !important;">
                        <tr>
                            <td style="text-align: center;">
                                <br>
                                <br>
                                <p style="text-align: center; line-height:1.1;">QR-Code scannen und Rechnung herunterladen</p>
                                <br>
                                <br>
                                <img style="text-align: center; width:160px; height:160px; margin:0.1cm 0 0 0; padding:0px;" src="storage/giftCardBillQRCode/GCBillQrC{{$theGC->id}}.png" alt="">
                            </td>
                            <td style="text-align: center;">
                                <br>
                                <br>
                                <p style="text-align: center; line-height:1.1;">Scannen Sie diesen QR-Code, um den Kontostand zu überprüfen</p>
                                <br>
                                <br>
                                <img style="text-align: center; width:160px; height:160px; margin:0.1cm 0 0 0; padding:0px;" src="storage/giftCardBalanceQRCode/GCBalanceQrC{{$theGC->id}}.png" alt="">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <br>
                                <br>
                                <p style="text-align: center; line-height:1.1;">QR-Code für die Aktivierung</p>
                                <br>
                                <p style="text-align: center; line-height:1.1; font-size:1.4rem;">{{$theGC->idnShortCode}}</p>
                                <br>
                                <img style="text-align: center; width:160px; height:160px; margin:0.1cm 0 0 0; padding:0px;" src="storage/giftcardQRCode/GC{{$theGC->id}}.png" alt="">
                            </td>
                        </tr>
                 

              
                        <tr style="text-align: center;">
                            <td colspan="2" style="text-align: center;">
                                <br>
                                <br>
                                <br>
                                <p>Kontaktlos bestellen & bezahlen mit QRorpa Systeme</p>
                                <img width="110px" src="{{ public_path() . '/storage/images/logo_QRorpa.png' }}" id="logo" />
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>

    </div>
   
</body>
</html>