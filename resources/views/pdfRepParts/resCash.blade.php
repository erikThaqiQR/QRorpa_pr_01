   <?php 
        use App\Orders;
        use App\Produktet;
        use App\pdfResProdCats;

        $showTable = False;

        global $totResBr;
    ?>
    @foreach ($ordersRes as $orOne)
        @if ($orOne->payM == "Barzahlungen" || $orOne->payM == "Cash")
            <?php $showTable = True; ?>
            @break
        @endif
    @endforeach

    @if ($showTable)
                <tr>
                    <td colspan="6"><strong>Restaurant (Bar)</strong></td>
                </tr>

                <tr class="heading" style="margin-top: 0.5cm;">
                    <td style="text-align: left;">
                        <strong>Stk.</strong>
                    </td>
                    
                    <td style="text-align: left;">
                        <strong>Hauptkategorien</strong>
                    </td>
                    <td style="text-align: right;">
                        <strong>Gesamt Brutto</strong>
                    </td>
                    <td style="text-align: right;">
                        <strong>MWST 8.10%</strong>
                    </td>
                    <td style="text-align: right;">
                        <strong>Gesamt Netto</strong>
                    </td>
                    <td style="text-align: right;">
                        <strong>%-Anteil </strong>
                    </td>
                </tr>
                
                <?php
                    $prodGroups = array();
                    $grSasia = array();
                    $grBruto = array();
                    $grMwst = array();
                    $grNeto = array();
                    $grAnteil = array();
                    $totInCHF = number_format(0.0, 2, '', ' ');
                    $totDiscount = 0.00;
                ?>



                <!-- Grupimi i produkteve -->
                @foreach ($ordersRes as $orOne)
                    @if ($orOne->payM == "Barzahlungen" || $orOne->payM == "Cash")
                        <!-- loop through products array -->
                        @foreach (explode('---8---',$orOne->porosia) as $porOne)
                            <?php 
                                if($porOne != NULL){
                                    $porOne2D = explode('-8-',$porOne); 
                                    $totOfThOr = number_format($porOne2D[4], 2, '.', ' ');

                                    $theP = Produktet::find($porOne2D[7]);
                                    if($theP != NULL){
                                        $mwstPRC = number_format(8.10, 2, '.', ' ');
                                        $mwst = number_format($totOfThOr*($mwstPRC*0.01), 2, '.', ' ');
                                        $bruto = number_format($totOfThOr, 2, '.', ' ');
                                        $neto = number_format($totOfThOr-$mwst, 2, '.', ' ');

                                        if(in_array($theP->toReportCat,$prodGroups)){
                                            // gr already registred
                                            $grSasia[$theP->toReportCat] = number_format($grSasia[$theP->toReportCat] + $porOne2D[3], 0, '.', ' ');
                                            $grBruto[$theP->toReportCat] = number_format($grBruto[$theP->toReportCat] + $bruto, 2, '.', ' ');
                                            $grMwst[$theP->toReportCat] = number_format($grMwst[$theP->toReportCat] + $mwst, 2, '.', ' ');
                                            $grNeto[$theP->toReportCat] = number_format($grNeto[$theP->toReportCat] + $neto, 2, '.', ' ');
                                        }else{
                                            // new gr
                                            array_push($prodGroups,$theP->toReportCat);

                                            $grSasia[$theP->toReportCat] = number_format($porOne2D[3], 0, '.', ' ');
                                            $grBruto[$theP->toReportCat] = number_format($bruto, 2, '.', ' ');
                                            $grMwst[$theP->toReportCat] = number_format($mwst, 2, '.', ' ');
                                            $grNeto[$theP->toReportCat] = number_format($neto, 2, '.', ' ');
                                        }
                                        $totInCHF = number_format($totInCHF + $totOfThOr, 2, '.', ' ');
                                    }
                                }
                            ?>
                        @endforeach
                        <?php
                            if($orOne->inCashDiscount > 0){
                                $totDiscount = number_format($totDiscount + $orOne->inCashDiscount, 2, '.', ' ');
                            }else if($orOne->inPercentageDiscount > 0){
                                $totDiscount = number_format($totDiscount + ($orOne->shuma*($orOne->inPercentageDiscount * 0.01)), 2, '.', ' ');
                            }
                        ?>
                    @endif
                @endforeach

                <!-- llogariten perqindjet ANTEIL per grupacione -->
                @foreach ($prodGroups as $key) 
                    <?php
                        $percVal = number_format((($grBruto[$key] / $totInCHF) * 100), 2, '.', ' ');
                        $grAnteil[$key] = $percVal;
                    ?>
                @endforeach

                <!-- Shfaq produktet e grupuara -->
                <?php
                    $sumSasia = 0.00;
                    $sumBruto = 0.00;
                    $sumMwst = 0.00;
                    $sumNeto = 0.00;
                ?>
                @foreach ($prodGroups as $key) 
                    <tr>
                        <td style="text-align: left;">
                            {{$grSasia[$key]}} X
                        </td>
                        <td style="text-align: left;">
                            @if ($key == 0)
                                nicht kategorisiert
                            @else
                                {{pdfResProdCats::findOrFail($key)->catTitle}}
                            @endif
                        </td>
                        <td style="text-align: right;">
                           CHF {{$grBruto[$key]}}
                        </td>
                        <td style="text-align: right;">
                           CHF {{$grMwst[$key]}}
                        </td>
                        <td style="text-align: right;">
                           CHF {{$grNeto[$key]}}
                        </td>
                        <td style="text-align: right;">
                            {{$grAnteil[$key]}} %
                        </td>
                        <?php
                            $sumSasia = $sumSasia + $grSasia[$key];
                            $sumBruto = $sumBruto + number_format($grBruto[$key], 2, '.', ' ');
                            $sumMwst = $sumMwst + number_format($grMwst[$key], 2, '.', ' ');
                            $sumNeto = $sumNeto + number_format($grNeto[$key], 2, '.', ' ');
                        ?>
                    </tr>
                @endforeach
                    <tr class="heading">
                        <td style="text-align: left;">
                            <strong>{{$sumSasia}} X</strong>
                        </td>
                        
                        <td style="text-align: left;">
                            <strong></strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>CHF {{number_format($sumBruto, 2, '.', ' ')}}</strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>CHF {{number_format($sumMwst, 2, '.', ' ')}}</strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>CHF {{number_format($sumNeto, 2, '.', ' ')}}</strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>100 % </strong>
                        </td>
                    </tr>
                @if ( $totDiscount > 0)
                    <tr>
                        <td colspan="6"><strong>Rabatte und Gutscheine: {{$totDiscount}} CHF</strong></td>
                    </tr>
                    <?php
                        $brutoAfterD = number_format($sumBruto - $totDiscount, 2, '.', ' ');
                        $mwstAfterD = number_format($brutoAfterD *0.081, 2, '.', ' ');
                        $netoAfterD = number_format($brutoAfterD - $mwstAfterD, 2, '.', ' ');

                        $totResBr += number_format($brutoAfterD, 2, '.', ' ');
                        $totResMwst += number_format($mwstAfterD, 2, '.', ' ');
                        $totResNe += number_format($netoAfterD, 2, '.', ' ');
                    ?>
                    <tr class="heading">
                        <td colspan="2" style="text-align: left;">
                            <strong> Nach Abzug von Rabatten </strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>CHF {{number_format($brutoAfterD, 2, '.', ' ')}}</strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>CHF {{number_format($mwstAfterD, 2, '.', ' ')}}</strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>CHF {{number_format($netoAfterD, 2, '.', ' ')}}</strong>
                        </td>
                        <td style="text-align: right;">
                            <strong>100 % </strong>
                        </td>
                    </tr>
                @else
                    <?php
                        $totResBr += number_format($sumBruto, 2, '.', ' ');
                        $totResMwst += number_format($sumMwst, 2, '.', ' ');
                        $totResNe += number_format($sumNeto, 2, '.', ' ');
                    ?>
                @endif
    @endif