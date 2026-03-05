<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            .table{
                border: 1px solid;
                text-align: center;
            }
            .table {
                width: 100%;
            }
            .table {
                border-collapse: collapse;
            }
            .text-center{
                text-align: center;
                border: 1px solid;
            }
            .p-1{
                padding: 4px;
            }
        
        </style>
    </head>
    <body>
        <main>
            <?php
                use App\Restorant;

                $theRes = Restorant::find($resID);
                $adr2D =  explode(',',$theRes->adresa);
                $adr1 =  $adr2D[0];
                $adr2 = '---' ;
                if(isset($adr2D[1])){ $adr2 = $adr2D[1] ; }
                if(isset($adr2D[2])){ $adr2 = $adr2D[1].','.$adr2D[2]; }

                $dateOfCr = date('d').'.'.date('m').'.'.date('Y').' / '.date('H').':'.date('i');
                
                $totalCoinsCHF = number_format(0,2,'.','');
                $totalBanknotesCHF = number_format(0,2,'.','');
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
                                        <p style="margin:0px; padding:0px; margin-top:-8px;">Tel. {{ $theRes->resPhoneNr }}</p>
                                        <p style="margin:0px; padding:0px; margin-top:-8px;">MwST-Nr. CHE-{{ $theRes->chemwstForRes }}</p>
                                    </td>
                                    <td >
                                        <p style="margin:0px; padding:0px; margin-top:-10px;"><strong>Sales Report ID#: {{str_pad($repData->id, 8, '0', STR_PAD_LEFT)}}</strong></p>
                                        <!-- <p style="margin:0px; padding:0px; margin-top:-8px;"><strong>Bericht#:</strong></p> -->
                                        <p style="margin:0px; padding:0px; margin-top:-8px;">Datum/Zeit: {{$dateOfCr}}</p>
                                    </td> 
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table class="table table-bordered" id="salesRepModalTbl1"> 
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" colspan="4" style="font-size: 1.4rem;">Kellner Tagesumsatz </th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-center" colspan="2" style="font-size: 1.4rem;"><strong>{{$waiterName}}</strong></th>
                            <th scope="col" class="text-center" colspan="2" style="font-size: 1.4rem;"><strong>{{$dateRep}}</strong></th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-center" colspan="4"><strong>Münzen</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-1 text-center"> 
                                <strong><span class="mr-1" id="coin5rp">{{$repData->c5rp}}x</span></strong> 0.05 CHF
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin5rpTotalCHF">{{number_format($repData->c5rp*0.05,2,'.','')}}</span> CHF
                                @php
                                    $totalCoinsCHF += number_format($repData->c5rp*0.05,2,'.','');
                                @endphp
                            </td>
                            <td class="p-1 text-center" style="text-align: center !important;"> 
                                <strong><span class="mr-1" id="coin10rp">{{$repData->c10rp}}x</span></strong> 0.10 CHF
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin10rpTotalCHF">{{number_format($repData->c10rp*0.10,2,'.','')}}</span> CHF
                                @php
                                    $totalCoinsCHF += number_format($repData->c10rp*0.10,2,'.','');;
                                @endphp
                            </td>
                            <td class="p-1 text-center"> 
                                <strong><span class="mr-1" id="coin20rp">{{$repData->c20rp}}x</span></strong> 0.20 CHF
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin20rpTotalCHF">{{number_format($repData->c20rp*0.20,2,'.','')}}</span> CHF
                                @php
                                    $totalCoinsCHF += number_format($repData->c20rp*0.20,2,'.','');
                                @endphp
                            </td>
                            <td class="p-1 text-center"> 
                                <strong><span class="mr-1" id="coin50rp">{{$repData->c50rp}}x</span></strong> 0.50 CHF
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin50rpTotalCHF">{{number_format($repData->c50rp*0.50,2,'.','')}}</span> CHF
                                @php
                                    $totalCoinsCHF += number_format($repData->c50rp*0.50,2,'.','');
                                @endphp
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1 text-center"> 
                                <strong><span class="mr-1" id="coin1chf">{{$repData->c1chf}}x</span></strong> 1.00 CHF 
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin1chfTotalCHF">{{number_format($repData->c1chf*1,2,'.','')}}</span> CHF
                                @php
                                    $totalCoinsCHF += number_format($repData->c1chf*1,2,'.','');
                                @endphp
                            </td>
                            <td class="p-1 text-center" style="text-align: center !important;"> 
                                <strong><span class="mr-1" id="coin2chf">{{$repData->c2chf}}x</span></strong> 2.00 CHF
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin2chfTotalCHF">{{number_format($repData->c2chf*2,2,'.','')}}</span> CHF
                                @php
                                    $totalCoinsCHF += number_format($repData->c2chf*2,2,'.','');
                                @endphp
                            </td>
                            <td class="p-1 text-center"> 
                                <strong><span class="mr-1" id="coin5chf">{{$repData->c5chf}}x</span></strong> 5.00 CHF
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin5chfTotalCHF">{{number_format($repData->c5chf*5,2,'.','')}}</span> CHF
                                @php
                                    $totalCoinsCHF += number_format($repData->c5chf*5,2,'.','');
                                @endphp
                            </td>
                            <td class="p-1 text-center">
                                <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="totalCoins">{{number_format($repData->c5rp+$repData->c10rp+$repData->c20rp+$repData->c50rp+$repData->c1chf+$repData->c2chf+$repData->c5chf,0,'.','')}}</span></strong> 
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="totalCoinsTotalCHF">{{number_format($totalCoinsCHF,2,'.','')}}</span> CHF
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="text-center" colspan="4"><strong>Banknoten</strong></th>
                        </tr>
                        <tr>
                            <td class="p-1 text-center"> 
                                <strong><span class="mr-1" id="coin10chf">{{$repData->c10chf}}x</span></strong> 10.00 CHF
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin10chfTotalCHF">{{number_format($repData->c10chf*10,2,'.','')}}</span> CH
                                @php
                                    $totalBanknotesCHF += number_format($repData->c10chf*10,2,'.','');
                                @endphp
                            </td>
                            <td class="p-1 text-center" style="text-align: center !important;"> 
                                <strong><span class="mr-1" id="coin20chf">{{$repData->c20chf}}x</span></strong> 20.00 CHF
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin20chfTotalCHF">{{number_format($repData->c20chf*20,2,'.','')}}</span> CH
                                @php
                                    $totalBanknotesCHF += number_format($repData->c20chf*20,2,'.','');
                                @endphp
                            </td>
                            <td class="p-1 text-center"> 
                                <strong><span class="mr-1" id="coin50chf">{{$repData->c50chf}}x</span></strong> 50.00 CHF 
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin50chfTotalCHF">{{number_format($repData->c50chf*50,2,'.','')}}</span> CH
                                @php
                                    $totalBanknotesCHF += number_format($repData->c50chf*50,2,'.','');
                                @endphp
                            </td>
                            <td class="p-1 text-center"> 
                                <strong><span class="mr-1" id="coin100chf">{{$repData->c100chf}}x</span></strong> 100.00 CHF 
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin100chfTotalCHF">{{number_format($repData->c100chf*100,2,'.','')}}</span> CH
                                @php
                                    $totalBanknotesCHF += number_format($repData->c100chf*100,2,'.','');
                                @endphp
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1 text-center"> 
                                <strong><span class="mr-1" id="coin200chf">{{$repData->c200chf}}x</span></strong> 200.00 CHF 
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin200chfTotalCHF">{{number_format($repData->c200chf*200,2,'.','')}}</span> CH
                                @php
                                    $totalBanknotesCHF += number_format($repData->c200chf*200,2,'.','');
                                @endphp
                            </td>
                            <td class="p-1 text-center" style="text-align: center !important;"> 
                                <strong><span class="mr-1" id="coin1000chf">{{$repData->c1000chf}}x</span></strong> 1000.00 CHF
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="coin1000chfTotalCHF">{{number_format($repData->c1000chf*1000,2,'.','')}}</span> CH
                                @php
                                    $totalBanknotesCHF += number_format($repData->c1000chf*1000,2,'.','');
                                @endphp
                            </td>
                            <td></td>
                            <td class="p-1 text-center">
                                <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="totalBanknotes">{{number_format($repData->c10chf+$repData->c20chf+$repData->c50chf+$repData->c100chf+$repData->c200chf+$repData->c1000chf,0,'.','')}}</span></strong> 
                                <br>
                                <span style="font-weight: bold; text-decoration: underline;" id="totalBanknotesTotalCHF">{{number_format($totalBanknotesCHF,2,'.','')}}</span> CH
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center" style="font-size: 1.4rem;"><strong> Gesamtumsatz : <span class="ml-1 mr-1"  id="totalInCHF">{{number_format($totalCoinsCHF+$totalBanknotesCHF,2,'.','')}}</span> CHF</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </body>
</html>