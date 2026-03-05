    <?php
        use App\Produktet;

        $totAllBr = number_format(0, 2, '.', ' ');
        $totAllMwst = number_format(0, 2, '.', ' ');
        $totAllNe = number_format(0, 2, '.', ' ');
        $totAllSasia = number_format(0, 0, '.', ' ');

        $totDiscount = number_format(0, 2, '.', ' ');
        $totInCHF = number_format(0, 2, '.', ' ');

        $resBr = number_format(0, 2, '.', ' ');
        $resMwst = number_format(0, 2, '.', ' ');
        $resNe = number_format(0, 2, '.', ' ');
        $resSasia = number_format(0, 0, '.', ' ');
    ?>

    <!-- Restaurant -->
    @foreach ($ordersRes as $orOne)
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
                        $neto = number_format($totOfThOr - $mwst, 2, '.', ' ');

                        $resSasia += number_format($porOne2D[3], 0, '.', ' ');
                        $resBr += number_format($bruto, 2, '.', ' ');
                        $resMwst += number_format($mwst, 2, '.', ' ');
                        $resNe += number_format($neto, 2, '.', ' ');
                        
                        $totInCHF += number_format($totOfThOr, 2, '.', ' ');
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
    @endforeach

    @if ( $totDiscount > 0)
        <?php
            $brutoAfterD = number_format($resBr - $totDiscount, 2, '.', ' ');
            $mwstAfterD = number_format($brutoAfterD *0.081, 2, '.', ' ');
            $netoAfterD = number_format($brutoAfterD - $mwstAfterD, 2, '.', ' ');

            $totAllBr += number_format($brutoAfterD, 2, '.', ' ');
            $totAllMwst += number_format($mwstAfterD, 2, '.', ' ');
            $totAllNe += number_format($netoAfterD, 2, '.', ' ');
            $totAllSasia += number_format($resSasia, 0, '.', ' ');
        ?>
    @else
        <?php
            $totAllBr += number_format($resBr, 2, '.', ' ');
            $totAllMwst += number_format($resMwst, 2, '.', ' ');
            $totAllNe += number_format($resNe, 2, '.', ' ');
            $totAllSasia += number_format($resSasia, 0, '.', ' ');
        ?>
    @endif















    
    
    <tr>
        <td colspan="6" style="color:white;">.</td>
    </tr>
    <tr class="heading">
        <td colspan="2" style="text-align: left; background: black !important; color:white;">
            <strong> Summen für den gesamten Bericht </strong>
        </td>
        <td style="text-align: right; background: black !important; color:white;">
            <strong>CHF {{number_format($totAllBr, 2, '.', ' ')}}</strong>
        </td>
        <td style="text-align: right; background: black !important; color:white;">
            <strong>CHF {{number_format($totAllMwst, 2, '.', ' ')}}</strong>
        </td>
        <td style="text-align: right; background: black !important; color:white;">
            <strong>CHF {{number_format($totAllNe, 2, '.', ' ')}}</strong>
        </td>
        <td style="text-align: right; background: black !important; color:white;">
            <strong>{{number_format($totAllSasia, 0, '.', ' ')}} X</strong>
        </td>
    </tr>