<?php
    use App\Barbershop;
use App\BarbershopServiceOrder;
use App\BarbershopWorker;
use App\BarbershopWorkerTerminBusy;
use App\BarbershopWorkerTerminet;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Barbershop Reservierungsbeleg</title>

        <style>
            .invoice-box {
                max-width: 800px;
                margin: auto;
                padding: 30px;
                border: 1px solid #eee;
                box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                font-size: 16px;
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
                padding-bottom: 40px;
            }
            
            .invoice-box table tr.heading td {
                background: #eee;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
            }
            
            .invoice-box table tr.details td {
                padding-bottom: 20px;
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

            .thanks{
                margin-top:100px;
                border-top: 1px solid #eee;
                text-align: center;
            }
        </style>
    </head>

    <body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <img width="110px" src="{{ public_path() . '/storage/images/qrorpa-logo-jpg.jpg' }}" id="logo" />
                            </td>
                            <td>
                                Rechnung #: {{$items->id}}<br>
                                Datum/Zeit: <strong>{{date("d-m-Y H:i")}}</strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>


            <?php
                $BarSerOrder = BarbershopServiceOrder::find($items->forSerOrder);
                $barEmri = Barbershop::find($BarSerOrder->toBar)->emri;
                $workerName = BarbershopWorker::find($items->forWorker)->emri;

                $startTime = '';
                foreach(BarbershopWorkerTerminBusy::where('serviceRecord',$items->id)->get()->sortBy('workerTerminID') as $ters){
                    $ter = BarbershopWorkerTerminet::find($ters->workerTerminID);
                    if($startTime == ''){
                        $startTime = $ter->startT;
                    }
                    $endTime = $ter->endT;
                }

                $dt = explode('-',$items->forDate); 
            ?>
            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                             <td>
                                <strong>Friseurladen:</strong> {{$barEmri}} <br>
                                <strong>Benötigte Zeit:</strong> {{$items->timeNeed}} minuten <br>
                                <strong>Arbeiter:</strong> {{$workerName}}<br>
                                <strong>Zeitspanne:</strong> {{$startTime}} => {{$endTime}} <br>
                                <strong>für Datum:</strong> {{$dt[2]}} / {{$dt[1]}} / {{$dt[0]}} <br>
                            </td>
                            <td>
                                QR Orpa<br>
                                Chur,<br>
                                Switzerland, 7000 <br>
                            </td>                            
                           
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td style="width:25%;">
                    Name 
                </td>
                
                <td style="width:30%; text-align:center;">
                    Beschreibung
                </td>
                <td style="width:20%; text-align:center;">
                    Typ
                </td>
                <td style="text-align: right;">
                    Preis (CHF)
                </td>
            </tr>
            <tr class="details">
                <td style="width:25%;">
                    {{$items->emri}}
                </td>   
                <td style="width:30%; text-align:center;">
                    {{$items->pershkrimi}}
                </td>
                <td style="width:20%; text-align:center;">
                    @if($items->type != NULL)
                        {{explode('||',$items->type)[1]}}
                    @endif
                </td>
                <td style="text-align: right;">
                    {{ number_format($items->qmimi, 2, '.', '') }}
                </td>
            </tr>

            <tr class="total">
                <td></td>
                <td></td>
                <td> Zwischensumme: </td>
                <td>
                  {{ number_format($items->qmimi-($items->qmimi*0.074930619), 2, '.', '') }} CHF
                </td>
            </tr>
            <tr class="total" style="margin-top:-5px;">
                <td></td>
                <td></td>
                <td> MwSt 8.1% </td>
                <td>
                  {{ number_format($items->qmimi *0.074930619, 2, '.', '') }} CHF </li>
                </td>
            </tr>
            @if($BarSerOrder->bakshish != 0)
            <tr class="total" style="margin-top:-5px;">
                <td></td>
                <td></td>
                <td>Trinkgeld</td>
                <td>
                  {{number_format($BarSerOrder->bakshish, 2, '.', '') }} CHF </li>
                </td>
            </tr>
            @endif
            <tr class="total" style="margin-top:-5px;">
                <td></td>
                <td></td>
                <td><strong>Gesamtsumme:</strong></td>
                <td>
                   <strong> {{number_format($items->qmimi + $BarSerOrder->bakshish, 2, '.', '')}} CHF</strong>
                </td>
            </tr>

        </table>
        <div class="thanks">
            <p>Vielen Dank, dass Sie das QR Orpa System verwenden!</p>
        </div>
    </div>
    </body>
</html>