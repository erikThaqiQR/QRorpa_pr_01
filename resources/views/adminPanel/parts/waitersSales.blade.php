<?php

    use App\waiterDaySales;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    use App\User;
?>
 <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->

<script>
    var openCalWa = 0;
</script>
<section class="p-3 pb-5">
    <div class="d-flex justify-content-between mt-2 mb-2">
        <h3 style="color:rgb(39,190,175); width:100%;"><strong>Tägliche Umsatzberichte der Kellner</strong></h3>
    </div>
    <hr>
    <div class="d-flex flex-wrap justify-content-start">
        <p style="width: 100%;"><strong>Wählen Sie zuerst einen Kellner aus!</strong></p>
        @foreach (User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $waOne)
            <button class="btn btn-outline-dark shadow-none mb-1" style="width: 24.5%; margin-right:0.5%;" onclick="showCalWa('{{$waOne->id}}')">
                <strong>
                {{ $waOne->name }}
                <br>
                {{ $waOne->email }}
                </strong>
            </button>
        @endforeach
    </div>
    <hr>

    @foreach (User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $waOne)
        <div id="calDaysForWa{{$waOne->id}}" style="display:none;">
            <?php  
                $month = Carbon::now(); 
        
                $monthCount = Carbon::now()->month;
                $yearCount = Carbon::now()->year;

                $waCreated = explode(' ',$waOne->created_at)[0];
                $waCreatedM = explode('-', $waCreated)[1];
                $rwaCreatedY = explode('-', $waCreated)[0];
            ?>
            <div id="calDaysForWa{{$waOne->id}}D2" style="width: 100%;" class="d-flex flex-wrap justify-content-start p-1">
                <p style="width: 100%; font-size:1.5rem;" class="text-center" id="calDaysForWa{{$waOne->id}}P"><strong>{{$waOne->name}}</strong></p>
                @while(true)
                    @if(($monthCount >= $waCreatedM && $yearCount == $rwaCreatedY) || $yearCount > $rwaCreatedY )
                        <div id="calDaysForWa{{$waOne->id}}D2" style="width: 33%; margin-right:0.33%" class="d-flex flex-wrap justify-content-between p-1">
                            <p style="width:100%; font-size:1.3rem; margin:0px; border-top:1px solid rgb(72,81,87);" class="text-center pt-1"><strong>
                                <?php
                                    switch($monthCount){
                                        case 1: echo  __('adminP.jan'). " . ".$yearCount.""; break;
                                        case 2: echo  __('adminP.feb'). " . ".$yearCount.""; break;
                                        case 3: echo  __('adminP.march'). " . ".$yearCount.""; break;
                                        case 4: echo  __('adminP.apr'). " . ".$yearCount.""; break;
                                        case 5: echo __('adminP.May'). " . ".$yearCount.""; break;
                                        case 6: echo __('adminP.june'). " . ".$yearCount.""; break;
                                        case 7: echo __('adminP.july'). " . ".$yearCount.""; break;
                                        case 8: echo __('adminP.aug'). " . ".$yearCount.""; break;
                                        case 9: echo __('adminP.sept'). " . ".$yearCount.""; break;
                                        case 10: echo __('adminP.oct'). " . ".$yearCount.""; break;
                                        case 11: echo __('adminP.nov'). " . ".$yearCount.""; break;
                                        case 12: echo __('adminP.dec'). " . ".$yearCount.""; break;   
                                    }
                                    $month = new Carbon($yearCount.'-'.$monthCount.'-01');
                                ?>
                            </strong></p>
                            <div id="calDaysForWa{{$waOne->id}}D3" style="width:100%;" class="d-flex flex-wrap justify-content-start mb-2">
                                <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Mo</button>
                                <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Di</button>
                                <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Mi</button>
                                <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Do</button>
                                <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Fr</button>
                                <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Sa</button>
                                <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>So</button>
                                @for($i=1;$i<=$month->daysInMonth;$i++)
                                    <?php
                                        if($i < 10){
                                            $d= '0'.$i;
                                        }else{
                                            $d= $i;
                                        }
                                        $dateCheckCreate = new Carbon($yearCount.'-'.$monthCount.'-'.$d);

                                        $waDaySaleIns = waiterDaySales::where('forWa',$waOne->id)->whereDate('forDay',$dateCheckCreate)->first();
                                    ?>
                                    @if($i == 1)
                                        <?php
                                            $dayOfWeekNr = date('w', strtotime($dateCheckCreate));
                                            if($dayOfWeekNr == 0){ $dayOfWeekNr = 7; }
                                            $j = 1;
                                        ?>
                                        @while ($j < $dayOfWeekNr)
                                        <button class="btn mb-1 btn-default shadow-none" style="width:14.1%; margin-right:0.18%;" disabled></button>
                                        <?php $j++; ?>
                                        @endwhile
                                    
                                        @if($dateCheckCreate >= $waOne->created_at && $dateCheckCreate <= Carbon::now())
                                            <button class="btn mb-1 {{$waDaySaleIns == Null ? 'btn-outline-danger' : 'btn-success'}} shadow-none" 
                                            style="width:14.1%; margin-right:0.18%;" data-toggle="modal" data-target="#salesRepModal" onclick="openRepSale('{{$dateCheckCreate}}','{{$waOne->id}}')">
                                                <strong>{{$i}}</strong>
                                            </button>
                                        @else
                                            <button class="btn mb-1 btn-outline-dark shadow-none" style="width:14.1%; margin-right:0.18%;" disabled><s>{{$i}}</s></button>
                                        @endif
                                    @else
                                        @if($dateCheckCreate >= $waOne->created_at && $dateCheckCreate <= Carbon::now() )
                                            <button class="btn mb-1 {{$waDaySaleIns == Null ? 'btn-outline-danger' : 'btn-success'}} shadow-none" 
                                            style="width:14.1%; margin-right:0.18%;" data-toggle="modal" data-target="#salesRepModal" onclick="openRepSale('{{$dateCheckCreate}}','{{$waOne->id}}')">
                                                <strong>{{$i}}</strong>
                                            </button>
                                        @else
                                            <button class="btn mb-1 btn-outline-dark shadow-none" style="width:14.1%; margin-right:0.18%;" disabled><s>{{$i}}</s></button>
                                        @endif
                                    @endif
                                @endfor
                                <?php
                                    $addExtra = 42 - $dayOfWeekNr + 1 - $month->daysInMonth;
                                ?>
                                @for($k=1;$k<=$addExtra;$k++)
                                    <button class="btn mb-1 btn-default shadow-none" style="width:14.1%; margin-right:0.18%; color:white;" disabled>.</button>
                                @endfor

                            </div>
                        </div>
                        <!-- Pjesa per vitin  -->
                        @if($monthCount == 1)
                            <?php
                                $yearCount--;
                                $monthCount=12;
                            ?>
                        @else
                            <?php
                                $monthCount--;
                            ?>
                        @endif
                    @else
                        @break;
                    @endif 
                @endwhile
            </div>
        </div>
    @endforeach
</section>









<!-- Modal -->
<div class="modal fade" id="salesRepModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-size: 1.5rem;"><strong>Umsatzbericht für <ins><span id="sRepDate"></span></ins></strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetSalesRepModal()">
                        <span aria-hidden="true"><i style="color:black;" class="fa-regular fa-2x fa-circle-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body ">
                    
                    {{Form::open(['action' => 'waiterDaySalesController@waitersSalesPrintPDFRep', 'method' => 'post']) }}
                        {{ csrf_field() }}
                        <button class="btn btn-outline-dark mb-1 shadow-none" type="submit" id="printSalesRepBtn" style="width:100%; display:none;"> 
                            <i class="fa-2x fa-solid fa-file-pdf" style="color:red;"></i>
                        </button>
                        <input type="hidden" name="dateSelected" id="dateSelected" value="0">
                        <input type="hidden" name="waitersId" id="waitersId" value="0">
                    {{Form::close() }}
                    <table class="table table-bordered" id="salesRepModalTbl1"> 
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" colspan="4"><strong>Münzen</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-1 text-center"> 
                                    0.05 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin5rp">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin5rpTotalCHF">--</span> CHF
                                </td>
                                <td class="p-1 text-center"> 
                                    0.10 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin10rp">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin10rpTotalCHF">--</span> CHF
                                </td>
                                <td class="p-1 text-center"> 
                                    0.20 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin20rp">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin20rpTotalCHF">--</span> CHF
                                </td>
                                <td class="p-1 text-center"> 
                                    0.50 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin50rp">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin50rpTotalCHF">--</span> CHF
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1 text-center"> 
                                    1.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin1chf">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin1chfTotalCHF">--</span> CHF
                                </td>
                                <td class="p-1 text-center"> 
                                    2.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin2chf">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin2chfTotalCHF">--</span> CHF
                                </td>
                                <td class="p-1 text-center"> 
                                    5.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin5chf">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin5chfTotalCHF">--</span> CHF
                                </td>
                                <td class="p-1 text-center">
                                    <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="totalCoins">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="totalCoinsTotalCHF">--</span> CHF
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center" colspan="4"><strong>Banknoten</strong></th>
                            </tr>
                            <tr>
                                <td class="p-1 text-center"> 
                                    10.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin10chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin10chfTotalCHF">--</span> CH
                                </td>
                                <td class="p-1 text-center"> 
                                    20.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin20chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin20chfTotalCHF">--</span> CH
                                </td>
                                <td class="p-1 text-center"> 
                                    50.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin50chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin50chfTotalCHF">--</span> CH
                                </td>
                                <td class="p-1 text-center"> 
                                    100.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin100chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin100chfTotalCHF">--</span> CH
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1 text-center"> 
                                    200.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin200chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin200chfTotalCHF">--</span> CH
                                </td>
                                <td class="p-1 text-center"> 
                                    1000.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin1000chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin1000chfTotalCHF">--</span> CH
                                </td>
                                <td></td>
                                <td class="p-1 text-center">
                                    <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="totalBanknotes">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="totalBanknotesTotalCHF">--</span> CH
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center" style="font-size: 1.4rem;"><strong> Gesamtumsatz : <span class="ml-1 mr-1"  id="totalInCHF">-</span> CHF</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td style="font-size:1.4rem;"><strong>Bestellungen bestätigt</strong></td>
                                <td style="font-size:1.4rem;" class="text-center" id="orderClosedNr"> --- </td>
                            </tr>
                            <tr>
                                <td style="font-size:1.4rem;"><strong>Bestellungen bestätigt</strong></td>
                                <td style="font-size:1.4rem;" class="text-center" id="orderClosedChf"> --- </td>
                            </tr>
                            <tr>
                                <td style="font-size:1.4rem;"><strong>Trinkgeld registriert</strong></td>
                                <td style="font-size:1.4rem;" class="text-center" id="bakshish1"> --- </td>
                            </tr>
                            <tr>
                                <td style="font-size:1.4rem;"><strong>Trinkgeld aus Bericht</strong></td>
                                <td style="font-size:1.4rem;" class="text-center" id="bakshish2"> --- </td>
                            </tr>
                           
                        </tbody>
                    </table>
                    <hr>
                    <table class="table table-bordered" id="salesRepModalTbl2">
                        <tbody>
                            <tr>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Bar</span></strong></td>
                                <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showCashDirect">-</span>CHF</strong></td>
                            </tr>
                            <tr>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">POS/Karte</span></strong></td>
                                <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showCardDirect">-</span>CHF</strong></td>
                            </tr>
                            <tr>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Online</span></strong></td>
                                <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showOnlineDirect">-</span>CHF</strong></td>
                            </tr>
                            <tr>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Auf Rechnung</span></strong></td>
                                <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showRechnungDirect">-</span>CHF</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center" style="font-size: 1.2rem;"><strong> Gesamtumsatz : <span class="ml-1 mr-1" id="totalInCHF2">-</span> CHF</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td style="font-size:1.4rem;"><strong>Bestellungen bestätigt</strong></td>
                                <td style="font-size:1.4rem;" class="text-center" id="orderClosedNr2"> --- </td>
                            </tr>
                            <tr>
                                <td style="font-size:1.4rem;"><strong>Bestellungen bestätigt</strong></td>
                                <td style="font-size:1.4rem;" class="text-center" id="orderClosedChf2"> --- </td>
                            </tr>
                            <tr>
                                <td style="font-size:1.4rem;"><strong>Trinkgeld registriert</strong></td>
                                <td style="font-size:1.4rem;" class="text-center" id="bakshish12"> --- </td>
                            </tr>
                            <tr>
                                <td style="font-size:1.4rem;"><strong>Trinkgeld aus Bericht</strong></td>
                                <td style="font-size:1.4rem;" class="text-center" id="bakshish22"> --- </td>
                            </tr>
                           
                        </tbody>
                    </table>

                    

                </div>
            </div>
        </div>
    </div>





    <script>
        function showCalWa(waId){
            if(openCalWa != 0){
                $("#calDaysForWa"+openCalWa).hide(100);
            }
            $("#calDaysForWa"+waId).show(100);
            openCalWa = waId;
        }

        function openRepSale(dt, waId){
            var dt2d = dt.split(' ')[0];
            var dt3d = dt2d.split('-');
            $('#sRepDate').html(dt3d[2]+'.'+dt3d[1]+'.'+dt3d[0]);

            $.ajax({
                url: '{{ route("admin.waDailySalesPageGetData") }}',
                dataType: 'json',
                method: 'post',
                data: {
                    theDt: dt,
                    waiterId: waId,
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    
                    if(respo != null && respo.c5rp != null){
                        $('#dateSelected').val(dt);
                        $('#waitersId').val(waId);
                        $('#printSalesRepBtn').show(5);

                        // part 1
                        $('#salesRepModalTbl1').show(100);
                        $('#salesRepModalTbl2').show(100);
                        let totCHFP1 = parseFloat(0).toFixed(2);
                        let totCHFP2 = parseFloat(0).toFixed(2);

                        $('#coin5rp').html(respo.c5rp);
                        let totC5rp = parseFloat(respo.c5rp * 0.05).toFixed(2);
                        $('#coin5rpTotalCHF').html(totC5rp);

                        $('#coin10rp').html(respo.c10rp);
                        let totC10rp = parseFloat(respo.c10rp * 0.10).toFixed(2);
                        $('#coin10rpTotalCHF').html(totC10rp);

                        $('#coin20rp').html(respo.c20rp);
                        let totC20rp = parseFloat(respo.c20rp * 0.20).toFixed(2);
                        $('#coin20rpTotalCHF').html(totC20rp);

                        $('#coin50rp').html(respo.c50rp);
                        let totC50rp = parseFloat(respo.c50rp * 0.50).toFixed(2);
                        $('#coin50rpTotalCHF').html(totC50rp);

                        $('#coin1chf').html(respo.c1chf);
                        let totC1chf = parseFloat(respo.c1chf * 1).toFixed(2);
                        $('#coin1chfTotalCHF').html(totC1chf);

                        $('#coin2chf').html(respo.c2chf);
                        let totC2chf = parseFloat(respo.c2chf * 2).toFixed(2);
                        $('#coin2chfTotalCHF').html(totC2chf);

                        $('#coin5chf').html(respo.c5chf);
                        let totC5chf = parseFloat(respo.c5chf * 5).toFixed(2);
                        $('#coin5chfTotalCHF').html(totC5chf);

                        $('#totalCoins').html(respo.countCoins);
                        totCHFP1 = parseFloat(parseFloat(totC5rp)+parseFloat(totC10rp)+parseFloat(totC20rp)+parseFloat(totC50rp)+parseFloat(totC1chf)+parseFloat(totC2chf)+parseFloat(totC5chf)).toFixed(2);
                        $('#totalCoinsTotalCHF').html(totCHFP1);

                        $('#coin10chf').html(respo.c10chf);
                        let totC10chf = parseFloat(respo.c10chf * 10).toFixed(2);
                        $('#coin10chfTotalCHF').html(totC10chf);

                        $('#coin20chf').html(respo.c20chf);
                        let totC20chf = parseFloat(respo.c20chf * 20).toFixed(2);
                        $('#coin20chfTotalCHF').html(totC20chf);

                        $('#coin50chf').html(respo.c50chf);
                        let totC50chf = parseFloat(respo.c50chf * 50).toFixed(2);
                        $('#coin50chfTotalCHF').html(totC50chf);

                        $('#coin100chf').html(respo.c100chf);
                        let totC100chf = parseFloat(respo.c100chf * 100).toFixed(2);
                        $('#coin100chfTotalCHF').html(totC100chf);

                        $('#coin200chf').html(respo.c200chf);
                        let totC200chf = parseFloat(respo.c200chf * 200).toFixed(2);
                        $('#coin200chfTotalCHF').html(totC200chf);

                        $('#coin1000chf').html(respo.c1000chf);
                        let totC1000chf = parseFloat(respo.c1000chf * 1000).toFixed(2);
                        $('#coin1000chfTotalCHF').html(totC1000chf);

                        $('#totalBanknotes').html(respo.countBanknotes);
                        totCHFP2 = parseFloat(parseFloat(totC10chf)+parseFloat(totC20chf)+parseFloat(totC50chf)+parseFloat(totC100chf)+parseFloat(totC200chf)+parseFloat(totC1000chf)).toFixed(2);
                        $('#totalBanknotesTotalCHF').html(totCHFP2);

                        $('#totalInCHF').html(respo.totalInChf);

                        // part 2
                        $('#showCashDirect').html(parseFloat(respo.inCashSalesDirect).toFixed(2));
                        $('#showCardDirect').html(parseFloat(respo.inCardSalesDirect).toFixed(2));
                        $('#showOnlineDirect').html(parseFloat(respo.inOnlineSalesDirect).toFixed(2));
                        $('#showRechnungDirect').html(parseFloat(respo.inRechnungSalesDirect).toFixed(2));

                        var tottoShow = parseFloat(parseFloat(respo.inCashSalesDirect)+parseFloat(respo.inCardSalesDirect)+parseFloat(respo.inOnlineSalesDirect)+parseFloat(respo.inRechnungSalesDirect)).toFixed(2);
                        
                        $('#totalInCHF2').html(tottoShow);
                        

                        $.ajax({
                            url: '{{ route("admin.waDailySalesPageGetDataOrders") }}',
                            method: 'post',
                            data: {
                                theDt2: dt,
                                waiterId2: waId,
                                _token: '{{csrf_token()}}'
                            },
                            success: (respo2nd) => {
                                respo2nd = $.trim(respo2nd);
                                respo2nd2D = respo2nd.split('-8-8-');

                                $('#orderClosedNr').html('<strong>'+respo2nd2D[0]+' Aufträge</strong>');
                                $('#orderClosedChf').html('<strong>'+parseFloat(respo2nd2D[1]).toFixed(2)+' CHF</strong>');
                                $('#bakshish1').html('<strong>'+parseFloat(respo2nd2D[2]).toFixed(2)+' CHF</strong>');
                                $('#bakshish2').html('<strong>'+parseFloat(respo.totalInChf-respo2nd2D[1]).toFixed(2)+' CHF</strong>');
                                
                                $('#orderClosedNr2').html('<strong>'+respo2nd2D[0]+' Aufträge</strong>');
                                $('#orderClosedChf2').html('<strong>'+parseFloat(respo2nd2D[1]).toFixed(2)+' CHF</strong>');
                                $('#bakshish12').html('<strong>'+parseFloat(respo2nd2D[2]).toFixed(2)+' CHF</strong>');
                                $('#bakshish22').html('<strong>'+parseFloat(tottoShow-respo2nd2D[1]).toFixed(2)+' CHF</strong>');
                            },
                            error: (error) => { console.log(error); }
                        });
                    }
                },
                error: (error) => { console.log(error); }
            });
        }


        function resetSalesRepModal(){
            $('#coin5rp').html('-');
            $('#coin10rp').html('-');
            $('#coin20rp').html('-');
            $('#coin50rp').html('-');
            $('#coin1chf').html('-');
            $('#coin2chf').html('-');
            $('#coin5chf').html('-');
            $('#totalCoins').html('-');
            $('#coin5rpTotalCHF').html('-');
            $('#coin10rpTotalCHF').html('-');
            $('#coin20rpTotalCHF').html('-');
            $('#coin50rpTotalCHF').html('-');
            $('#coin1chfTotalCHF').html('-');
            $('#coin2chfTotalCHF').html('-');
            $('#coin5chfTotalCHF').html('-');
            $('#totalCoinsTotalCHF').html('-');

            $('#coin10chf').html('-');
            $('#coin20chf').html('-');
            $('#coin50chf').html('-');
            $('#coin100chf').html('-');
            $('#coin200chf').html('-');
            $('#coin1000chf').html('-');
            $('#totalBanknotes').html('-');
            $('#coin10chfTotalCHF').html('-');
            $('#coin20chfTotalCHF').html('-');
            $('#coin50chfTotalCHF').html('-');
            $('#coin100chfTotalCHF').html('-');
            $('#coin200chfTotalCHF').html('-');
            $('#coin1000chfTotalCHF').html('-');
            $('#totalBanknotesTotalCHF').html('-');

            $('#totalInCHF').html('-');

            $('#showCashDirect').html('-');
            $('#showCardDirect').html('-');
            $('#showOnlineDirect').html('-');
            $('#showRechnungDirect').html('-');
            $('#totalInCHF2').html('-');

            $('#orderClosedNr').html('---');
            $('#orderClosedChf').html('---');
            $('#bakshish1').html('---');
            $('#bakshish2').html('---');

            $('#orderClosedNr2').html('---');
            $('#orderClosedChf2').html('---');
            $('#bakshish12').html('---');
            $('#bakshish22').html('---');

            $('#dateSelected').val(0);
            $('#waitersId').val(0);
            $('#printSalesRepBtn').hide(5);
        }
    </script>