<?php

    use App\Restorant;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    use App\waiterDaySales;
?>
 <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
 <style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<section class="pl-1 pr-1 pb-5">
    <h6 style="color:rgb(39,195,175);" class="p-1"><strong>Ihre Berichte für den täglichen Umsatz</strong></h6>

    <hr>
    <?php
        $dateCheckCreate = new Carbon(Carbon::now()->year.'-'.Carbon::now()->month.'-'.Carbon::now()->day);
    ?>
    <div id="waTodaySalesP1">
        @if(waiterDaySales::where('forWa',Auth::user()->id)->whereDate('forDay',$dateCheckCreate)->first() == Null)
            <h6 style="color:rgb(39,195,175);" class="p-1"><strong>Registrieren Sie den heutigen Verkaufsbericht</strong></h6>

            <div class="d-flex justify-content-start flex-wrap mt-2">

                <p style="width:100%;"><strong>Münzen</strong></p>
                <div id="calSalesDiv" style="display:flex; width:100%;" class=" justify-content-start flex-wrap">
                    <div style="width:49.5%; margin-right:1%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin5rp" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>0.05 <sup>CHF</sup></strong></span>
                        </div>
                    </div>

                    <div style="width:49.5%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin10rp" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>0.10 <sup>CHF</sup></strong></span>
                        </div>
                    </div>

                    <div style="width:49.5%; margin-right:1%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin20rp" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>0.20 <sup>CHF</sup></strong></span>
                        </div>
                    </div>

                    <div style="width:49.5%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin50rp" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>0.50 <sup>CHF</sup></strong></span>
                        </div>
                    </div>

                    <div style="width:49.5%; margin-right:1%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin1chf" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>1.00 <sup>CHF</sup></strong></span>
                        </div>
                    </div>

                    <div style="width:49.5%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin2chf" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>2.00 <sup>CHF</sup></strong></span>
                        </div>
                    </div>

                    <div style="width:49.5%; margin-right:1%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin5chf" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>5.00 <sup>CHF</sup></strong></span>
                        </div>
                    </div>
                    <div style="width:49.5%;" class="input-group mb-1">
                    </div>


                    <p style="width:100%;"><strong>Banknoten</strong></p>

                    <div style="width:49.5%; margin-right:1%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin10chf" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>10.00 <sup>CHF</sup></strong></span>
                        </div>
                    </div>
                    <div style="width:49.5%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin20chf" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>20.00 <sup>CHF</sup></strong></span>
                        </div>
                    </div>
                    <div style="width:49.5%; margin-right:1%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin50chf" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>50.00 <sup>CHF</sup></strong></span>
                        </div>
                    </div>
                    <div style="width:49.5%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin100chf" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>100.00 <sup>CHF</sup></strong></span>
                        </div>
                    </div>
                    <div style="width:49.5%; margin-right:1%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin200chf" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>200.00 <sup>CHF</sup></strong></span>
                        </div>
                    </div>
                    <div style="width:49.5%;" class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">#</span>
                        </div>
                        <input onkeyup="calcSalesWa()" type="number" step="1" id="nrOfCoin1000chf" class="form-control shadow-none" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text"><strong>1000.00 <sup>CHF</sup></strong></span>
                        </div>
                    </div>



                    <div style="width:100%;" class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Anzahl Münzen</strong></span>
                        </div>
                        <input style="font-weight: bold;" type="number" step="1" id="nrOfCoinsTotal" class="form-control shadow-none" value="0" disabled>
                    </div>
                    <div style="width:100%;" class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Anzahl Banknoten</strong></span>
                        </div>
                        <input style="font-weight: bold;" type="number" step="1" id="nrOfBanknotesTotal" class="form-control shadow-none" value="0" disabled>
                    </div>
                    <div style="width:100%;" class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Total in CHF</strong></span>
                        </div>
                        <input style="font-weight: bold;" type="number" step="1" id="totalInChf" class="form-control shadow-none" value="0" disabled>
                    </div>
                </div>

                <hr style="width:100%;">
                <!-- <p style="color:rgb(72,81,87); width:100%;"><strong>Optionale Möglichkeit, den heutigen Verkaufsbericht zu registrieren</strong></p> -->

                <div style="width:100%;" class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Bar</span>
                    </div>
                    <!-- <input type="number" step="1" id="inCashDirect" onkeyup="optionalSalesReport()" class="form-control shadow-none" value="0"> -->
                    <input type="number" step="1" id="inCashDirect" class="form-control shadow-none" value="0">
                    <div class="input-group-append">
                        <span class="input-group-text"><strong>CHF</strong></span>
                    </div>
                </div>

                <div style="width:100%;" class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">POS/Karte</span>
                    </div>
                    <input type="number" step="1" id="inCardDirect" class="form-control shadow-none" value="0">
                    <div class="input-group-append">
                        <span class="input-group-text"><strong>CHF</strong></span>
                    </div>
                </div>

                <div style="width:100%;" class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Online</span>
                    </div>
                    <input type="number" step="1" id="inOnlineDirect" class="form-control shadow-none" value="0">
                    <div class="input-group-append">
                        <span class="input-group-text"><strong>CHF</strong></span>
                    </div>
                </div>

                <div style="width:100%;" class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Auf Rechnung</span>
                    </div>
                    <input type="number" step="1" id="inRechnungDirect" class="form-control shadow-none" value="0">
                    <div class="input-group-append">
                        <span class="input-group-text"><strong>CHF</strong></span>
                    </div>
                </div>

                <button class="btn btn-success mt-3 shadow-none" onclick="registerDailySale()" style="width:100%; margin:0px"><strong>Registrieren</strong></button>

                <div class="alert alert-danger p-1 mt-1 text-center" id="regSaleErr01" style="display:none; width:100%;">
                    <strong>Bitte geben Sie gültige Daten an!</strong>
                </div>
                <div class="alert alert-danger p-1 mt-1 text-center" id="regSaleErr02" style="display:none; width:100%;">
                    <strong>Stellen Sie sicher, dass Sie jeden Wert schreiben!</strong>
                </div>

                <div class="alert alert-danger p-1 mt-1 text-center" id="regSaleErr03" style="display:none; width:100%;">
                    <strong>Sie haben einige nicht abgeschlossene/unbestätigte Bestellungen für Takeaway. Bitte schließen Sie diese, bevor Sie fortfahren!</strong>
                </div>
                <div class="alert alert-danger p-1 mt-1 text-center" id="regSaleErr04" style="display:none; width:100%;">
                    <strong>Sie haben einige nicht abgeschlossene/unbestätigte Bestellungen auf den Restauranttischen. Bitte schließen Sie diese, bevor Sie fortfahren!</strong>
                </div>

            </div>
        @else
            <div class="alert alert-success p-1 mt-1 text-center">
                <strong>Sie haben den Verkaufsbericht für heute fertiggestellt</strong>
            </div>
        @endif

           
   
    </div>
    <div class="alert alert-success p-1 mt-1 text-center" id="regSaleSucc01" style="display:none; width:100%;">
        <strong>Sie haben den heutigen Verkaufsbericht erfolgreich registriert, vielen Dank</strong>
    </div>
    
    <hr>
    










    <div id="waTodaySalesP2">
        <?php  
            $theRes = Restorant::find(Auth::user()->sFor);

            $month = Carbon::now(); 
      
            $monthCount = Carbon::now()->month;
            $yearCount = Carbon::now()->year;

            $waCreated = explode(' ',Auth::user()->created_at)[0];
            $waCreatedM = explode('-', $waCreated)[1];
            $rwaCreatedY = explode('-', $waCreated)[0];
        ?>
        <div style="width: 100%;" class="d-flex flex-wrap justify-content-start p-1">
            @while(true)
                @if(($monthCount >= $waCreatedM && $yearCount == $rwaCreatedY) || $yearCount > $rwaCreatedY )
                    <div style="width: 100%; margin-right:0.33%" class="d-flex flex-wrap justify-content-between p-1">
                        <p style="width:100%; font-size:1.2rem; margin:0px; border-top:1px solid rgb(72,81,87);" class="text-center pt-1"><strong>
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
                        <div style="width:100%;" class="d-flex flex-wrap justify-content-start mb-2">
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

                                    $waDaySaleIns = waiterDaySales::where('forWa',Auth::user()->id)->whereDate('forDay',$dateCheckCreate)->first();
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
                                
                                    @if($dateCheckCreate >= Auth::user()->created_at && $dateCheckCreate <= Carbon::now())
                                        <button class="btn mb-1 {{$waDaySaleIns == Null ? 'btn-outline-danger' : 'btn-success'}} shadow-none" 
                                        style="width:14%; margin-right:0.28%;" data-toggle="modal" data-target="#salesRepModal" onclick="openRepSale('{{$dateCheckCreate}}')">
                                            <strong>{{$i}}</strong>
                                        </button>
                                    @else
                                        <button class="btn mb-1 btn-outline-dark shadow-none" style="width:14%; margin-right:0.28%;" disabled><s>{{$i}}</s></button>
                                    @endif
                                @else
                                    @if($dateCheckCreate >= Auth::user()->created_at && $dateCheckCreate <= Carbon::now() )
                                        <button class="btn mb-1 {{$waDaySaleIns == Null ? 'btn-outline-danger' : 'btn-success'}} shadow-none" 
                                        style="width:14%; margin-right:0.28%;" data-toggle="modal" data-target="#salesRepModal" onclick="openRepSale('{{$dateCheckCreate}}')">
                                            <strong>{{$i}}</strong>
                                        </button>
                                    @else
                                        <button class="btn mb-1 btn-outline-dark shadow-none" style="width:14%; margin-right:0.28%;" disabled><s>{{$i}}</s></button>
                                    @endif
                                @endif
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
                                <th scope="col" class="text-center" colspan="2"><strong>Münzen</strong></th>
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
                            </tr>
                            <tr>
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
                            </tr>
                            <tr>
                                <td class="p-1 text-center"> 
                                    5.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin5chf">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin5chfTotalCHF">--</span> CHF
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-1 text-center">
                                    <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="totalCoins">-</span></strong> <i class="fa-solid fa-coins"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="totalCoinsTotalCHF">--</span> CHF
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center" colspan="2"><strong>Banknoten</strong></th>
                            </tr>
                            <tr>
                                <td class="p-1 text-center"> 
                                    10.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin10chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin10chfTotalCHF">--</span> CHF
                                </td>
                                <td class="p-1 text-center"> 
                                    20.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin20chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin20chfTotalCHF">--</span> CHF
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1 text-center"> 
                                    50.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin50chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin50chfTotalCHF">--</span> CHF
                                </td>
                                <td class="p-1 text-center"> 
                                    100.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin100chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin100chfTotalCHF">--</span> CHF
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1 text-center"> 
                                    200.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin200chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin200chfTotalCHF">--</span> CHF
                                </td>
                                <td class="p-1 text-center"> 
                                    1000.00 CHF : <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="coin1000chf">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="coin1000chfTotalCHF">--</span> CHF
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1 text-center" colspan="2">
                                    <strong><span class="ml-1 mr-1" style="font-size: 1.2rem;" id="totalBanknotes">-</span></strong> <i class="fa-solid fa-money-bill"></i>
                                    <br>
                                    <span style="font-weight: bold; text-decoration: underline;" id="totalBanknotesTotalCHF">--</span> CHF
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center" style="font-size: 1.2rem;"><strong> Gesamtumsatz : <span class="ml-1 mr-1"  id="totalInCHF">-</span> CHF</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered" id="salesRepModalTbl2">
                        <tbody>
                            <tr>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Bar</span></strong></td>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showCashDirect">-</span>CHF</strong></td>
                            </tr>
                            <tr>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">POS/Karte</span></strong></td>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showCardDirect">-</span>CHF</strong></td>
                            </tr>
                            <tr>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Online</span></strong></td>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showOnlineDirect">-</span>CHF</strong></td>
                            </tr>
                            <tr>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Auf Rechnung</span></strong></td>
                                <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showRechnungDirect">-</span>CHF</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center" style="font-size: 1.2rem;"><strong> Gesamtumsatz : <span class="ml-1 mr-1" id="totalInCHF2">-</span> CHF</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>









<script>
    // function optionalSalesReport(){
    //     if(!$('#inCashDirect').val()){
    //         var cashReport = 0;
    //     }else{
    //         var cashReport = parseInt($('#inCashDirect').val());
    //     }
    //     if(cashReport == 0){
    //         $('#calSalesDiv').attr('style','display:flex; width:100%;');
    //     }else{
    //         $('#calSalesDiv').attr('style','display:none; width:100%;');
    //     }
    // }


    function calcSalesWa(){

        var totalInCHF = 0;

        var c5rp =  parseInt($('#nrOfCoin5rp').val());
        var c10rp =  parseInt($('#nrOfCoin10rp').val());
        var c20rp =  parseInt($('#nrOfCoin20rp').val());
        var c50rp =  parseInt($('#nrOfCoin50rp').val());
        var c1chf =  parseInt($('#nrOfCoin1chf').val());
        var c2chf =  parseInt($('#nrOfCoin2chf').val());
        var c5chf =  parseInt($('#nrOfCoin5chf').val());
        var c10chf =  parseInt($('#nrOfCoin10chf').val());
        var c20chf =  parseInt($('#nrOfCoin20chf').val());
        var c50chf =  parseInt($('#nrOfCoin50chf').val());
        var c100chf =  parseInt($('#nrOfCoin100chf').val());
        var c200chf =  parseInt($('#nrOfCoin200chf').val());
        var c1000chf =  parseInt($('#nrOfCoin1000chf').val());
        
        totalInCHF += parseFloat(c5rp/20);
        totalInCHF += parseFloat(c10rp/10);
        totalInCHF += parseFloat(c20rp/5);
        totalInCHF += parseFloat(c50rp/2);
        totalInCHF += parseFloat(c1chf);
        totalInCHF += parseFloat(c2chf*2);
        totalInCHF += parseFloat(c5chf*5);
        totalInCHF += parseFloat(c10chf*10);
        totalInCHF += parseFloat(c20chf*20);
        totalInCHF += parseFloat(c50chf*50);
        totalInCHF += parseFloat(c100chf*100);
        totalInCHF += parseFloat(c200chf*200);
        totalInCHF += parseFloat(c1000chf*1000);

        var totalCoins = parseInt(c5rp+c10rp+c20rp+c50rp+c1chf+c2chf+c5chf);
        var totalBanknotes = parseInt(c10chf+c20chf+c50chf+c100chf+c200chf+c1000chf);
      
        $('#nrOfCoinsTotal').val(parseInt(totalCoins));
        $('#nrOfBanknotesTotal').val(parseInt(totalBanknotes));
        $('#totalInChf').val(parseFloat(totalInCHF).toFixed(2));
    }

    function registerDailySale(){
        if(!($('#nrOfCoin5rp').val()) || !($('#nrOfCoin10rp').val()) || !($('#nrOfCoin20rp').val()) || !($('#nrOfCoin50rp').val()) || !($('#nrOfCoin1chf').val())
        || !($('#nrOfCoin2chf').val()) || !($('#nrOfCoin5chf').val()) || !($('#nrOfCoin10chf').val()) || !($('#nrOfCoin20chf').val()) || !($('#nrOfCoin50chf').val())
        || !($('#nrOfCoin100chf').val()) || !($('#nrOfCoin200chf').val()) || !($('#nrOfCoin1000chf').val())){
            if($('#regSaleErr01').is(':hidden')){ $('#regSaleErr01').show(100).delay(3500).hide(100); }
        }else if($('#nrOfCoin5rp').val() < 0 || $('#nrOfCoin10rp').val() < 0 || $('#nrOfCoin20rp').val() < 0 || $('#nrOfCoin50rp').val() < 0 || $('#nrOfCoin1chf').val() < 0
        || $('#nrOfCoin2chf').val() < 0 || $('#nrOfCoin5chf').val() < 0 || $('#nrOfCoin10chf').val() < 0 || $('#nrOfCoin20chf').val() < 0 || $('#nrOfCoin50chf').val() < 0
        || $('#nrOfCoin100chf').val() < 0 || $('#nrOfCoin200chf').val() < 0 || $('#nrOfCoin1000chf').val() < 0){
            if($('#regSaleErr01').is(':hidden')){ $('#regSaleErr01').show(100).delay(3500).hide(100); }
        }else{
            if(!($('#inCashDirect').val()) || $('#inCashDirect').val() < 0){  var cashDirect = parseFloat(0);
            }else{  var cashDirect = $('#inCashDirect').val(); }

            if(!($('#inCardDirect').val()) || $('#inCardDirect').val() < 0){ var cardDirect = parseFloat(0); 
            }else{ var cardDirect = $('#inCardDirect').val(); }

            if(!($('#inOnlineDirect').val()) || $('#inOnlineDirect').val() < 0){ var onlineDirect = parseFloat(0); 
            }else{ var onlineDirect = $('#inOnlineDirect').val(); }

            if(!($('#inRechnungDirect').val()) || $('#inRechnungDirect').val() < 0){ var rechnungDirect = parseFloat(0); 
            }else{ var rechnungDirect = $('#inRechnungDirect').val(); }

            $.ajax({
                url: '{{ route("waSalesToday.waSalesTodayRegister") }}',
                method: 'post',
                data: {
                    c5rp : $('#nrOfCoin5rp').val(),
                    c10rp : $('#nrOfCoin10rp').val(),
                    c20rp : $('#nrOfCoin20rp').val(),
                    c50rp : $('#nrOfCoin50rp').val(),
                    c1chf : $('#nrOfCoin1chf').val(),
                    c2chf : $('#nrOfCoin2chf').val(),
                    c5chf : $('#nrOfCoin5chf').val(),
                    c10chf : $('#nrOfCoin10chf').val(),
                    c20chf : $('#nrOfCoin20chf').val(),
                    c50chf : $('#nrOfCoin50chf').val(),
                    c100chf : $('#nrOfCoin100chf').val(),
                    c200chf : $('#nrOfCoin200chf').val(),
                    c1000chf : $('#nrOfCoin1000chf').val(),
                    coinCount : $('#nrOfCoinsTotal').val(),
                    banknotesCount : $('#nrOfBanknotesTotal').val(),
                    totalInChf : $('#totalInChf').val(),
                    cashDirect: cashDirect,
                    cardDirect: cardDirect,
                    onlineirect: onlineDirect,
                    rechnungDirect: rechnungDirect,
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    respo = $.trim(respo);
                    if(respo == 'TAunClosedOrder'){
                        if($('#regSaleErr03').is(':hidden')){ $('#regSaleErr03').show(100).delay(3500).hide(100); }
                    }else if(respo == 'unClosedOrder'){
                        if($('#regSaleErr04').is(':hidden')){ $('#regSaleErr04').show(100).delay(3500).hide(100); }
                    }else{
                        $("#waTodaySalesP1").load(location.href+" #waTodaySalesP1>*","");
                        $("#waTodaySalesP2").load(location.href+" #waTodaySalesP2>*","");

                        if($('#regSaleSucc01').is(':hidden')){ $('#regSaleSucc01').show(100).delay(4500).hide(100); }
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    }




    function openRepSale(dt){
        var dt2d = dt.split(' ')[0];
        var dt3d = dt2d.split('-');
        $('#sRepDate').html(dt3d[2]+'.'+dt3d[1]+'.'+dt3d[0]);

        $.ajax({
	    	url: '{{ route("waSalesToday.waSalesTodayGetData") }}',
            dataType: 'json',
	    	method: 'post',
	    	data: {
	    		theDt: dt,
	    		_token: '{{csrf_token()}}'
	    	},
	    	success: (respo) => {
                if(respo != null && respo.c5rp != null){
                    $('#dateSelected').val(dt);
                    $('#waitersId').val('{{Auth::user()->id}}');
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
                    
                    $('#totalInCHF2').html(tottoShow)
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

        $('#dateSelected').val(0);
        $('#waitersId').val(0);
        $('#printSalesRepBtn').hide(5);
    }
</script>