<?php
    use App\BarbershopServiceOrder;
    use App\BarbershopServiceOrdersRecords;
    use Carbon\Carbon;

    $thisBartId = Auth::user()->sFor;
    $nowDate = date('Y-m-d');
    $nowMonth = date('m');
?>
<style>
    a.nostyle:link {
    text-decoration: inherit;
    color: inherit;
    cursor: auto;
    }
    a.nostyle:visited {
        text-decoration: inherit;
        color: inherit;
        cursor: auto;
    }
    a.nostyle:hover{
        cursor:pointer;
    }
</style>












<?php

    $earningsMonth = 0;
    $rezPending = 0;
    $rezAnswer = 0;
    $rezTotal = 0;
    
    foreach(BarbershopServiceOrdersRecords::where('status','2')->whereMonth('forDate', $nowMonth)->get() as $thisMonthsRec){
        if(BarbershopServiceOrder::find($thisMonthsRec->forSerOrder)->toBar ==  $thisBartId ){ $earningsMonth += $thisMonthsRec->qmimi; }
    }
    foreach(BarbershopServiceOrdersRecords::whereDate('forDate', $nowDate)->get() as $todaysRec){
        if(BarbershopServiceOrder::find($thisMonthsRec->forSerOrder)->toBar ==  $thisBartId ){ 
            if($todaysRec->status == 0){
                $rezPending++;
            }else{
                $rezAnswer++;
            }
            $rezTotal++;
        }   
    }

    if($rezTotal != 0){
        $percentageDone = ($rezAnswer/$rezTotal)*100;
    }else{
        $percentageDone = 100;
    }
   
   $Day00 = date('Y-m-d',strtotime('+4 days',strtotime($nowDate)));
   $Day01 = date('Y-m-d',strtotime('+3 days',strtotime($nowDate)));
   $Day02 = date('Y-m-d',strtotime('+2 days',strtotime($nowDate)));
   $Day03 = date('Y-m-d',strtotime('+1 days',strtotime($nowDate)));
   $Day04 = $nowDate;
   $Day05 = date('Y-m-d',strtotime('-1 days',strtotime($nowDate)));
   $Day06 = date('Y-m-d',strtotime('-2 days',strtotime($nowDate)));
   $Day07 = date('Y-m-d',strtotime('-3 days',strtotime($nowDate)));
   $Day08 = date('Y-m-d',strtotime('-4 days',strtotime($nowDate)));
   $Day09 = date('Y-m-d',strtotime('-5 days',strtotime($nowDate)));

   $earningDay00 = 0;
   $earningDay01 = 0;
   $earningDay02 = 0;
   $earningDay03 = 0;
   $earningDay04 = 0;
   $earningDay05 = 0;
   $earningDay06 = 0;
   $earningDay07 = 0;
   $earningDay08 = 0;
   $earningDay09 = 0;
   
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day00],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay00 += $bSerOrRec->qmimi; }
   }
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day01],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay01 += $bSerOrRec->qmimi; }
   }
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day02],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay02 += $bSerOrRec->qmimi; }
   }
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day03],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay03 += $bSerOrRec->qmimi; }
   }
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day04],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay04 += $bSerOrRec->qmimi; }
   }
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day05],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay05 += $bSerOrRec->qmimi; }
   }
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day06],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay06 += $bSerOrRec->qmimi; }
   }
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day07],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay07 += $bSerOrRec->qmimi; }
   }
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day08],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay08 += $bSerOrRec->qmimi; }
   }
   foreach(BarbershopServiceOrdersRecords::where([['forDate',$Day09],['status','2']])->get() as $bSerOrRec){
       if(BarbershopServiceOrder::find($bSerOrRec->forSerOrder)->toBar ==  $thisBartId ){ $earningDay09 += $bSerOrRec->qmimi; }
   }

   $Day00 = explode('-', $Day00)[2].' / '.getMonthName(explode('-', $Day00)[1]);
   $Day01 = explode('-', $Day01)[2].' / '.getMonthName(explode('-', $Day01)[1]);
   $Day02 = explode('-', $Day02)[2].' / '.getMonthName(explode('-', $Day02)[1]);
   $Day03 = explode('-', $Day03)[2].' / '.getMonthName(explode('-', $Day03)[1]);
   $Day04 = 'Heute " '.explode('-', $Day04)[2].' / '.getMonthName(explode('-', $Day04)[1]).' "';
   $Day05 = explode('-', $Day05)[2].' / '.getMonthName(explode('-', $Day05)[1]);
   $Day06 = explode('-', $Day06)[2].' / '.getMonthName(explode('-', $Day06)[1]);
   $Day07 = explode('-', $Day07)[2].' / '.getMonthName(explode('-', $Day07)[1]);
   $Day08 = explode('-', $Day08)[2].' / '.getMonthName(explode('-', $Day08)[1]);
   $Day09 = explode('-', $Day09)[2].' / '.getMonthName(explode('-', $Day09)[1]);

   function getMonthName($monthNr){
       switch($monthNr){
           case '01': return __('adminP.jan'); break;
           case '02': return __('adminP.feb'); break;
           case '03': return __('adminP.march'); break;
           case '04': return __('adminP.apr'); break;
           case '05': return __('adminP.may'); break;
           case '06': return __('adminP.june'); break;
           case '07': return __('adminP.july'); break;
           case '08': return __('adminP.aug'); break;
           case '09': return __('adminP.sept'); break;
           case '10': return __('adminP.oct'); break;
           case '11': return __('adminP.nov'); break;
           case '12': return __('adminP.dec'); break;
       }
   }
?>







































<section class="p-2 ml-2 mr-2 mt-3 mb-5">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h3 style="color:rgb(72,81,87);"><strong>{{__('adminP.statistics')}}</strong></h3>
    </div>


    <div class="d-flex justify-content-between">

        <a style="width:23%;" class="nostyle" href="{{route('barAdmin.showReservationsByMonth')}}">  
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{__('adminP.reservationsThisMonth')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45">{{__('adminP.currencyShow')}} </span>{{$earningsMonth}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <a style="width:23%;" class="nostyle" href="">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{__('adminP.reservationsToday')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45">{{__('adminP.currencyShow')}} </span>{{$earningDay04}}</div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <div style="width:23%;" class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__('adminP.confirmRejectToday')}}</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$percentageDone}}%</div>
                            </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{$percentageDone}}%"
                                        aria-valuenow="{{$percentageDone}}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>

        <div style="width:23%;" class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{__('adminP.waitingForReservations')}}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$rezPending}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>




















   


    <div class="mb-4 ml-2 mr-3 mt-4">
        <div class="text-center">
            <h2 style="color: rgb(72,81,87);"><strong>{{__('adminP.reservationsEarningsTenDay')}}</strong></h2>
        </div>
        <div class="chart-area">
            <canvas id="barEarningsChart"></canvas>
        </div>

        <input type="hidden" id="earningDay00" value="{{$earningDay00}}">
        <input type="hidden" id="earningDay01" value="{{$earningDay01}}">
        <input type="hidden" id="earningDay02" value="{{$earningDay02}}">
        <input type="hidden" id="earningDay03" value="{{$earningDay03}}">
        <input type="hidden" id="earningDay04" value="{{$earningDay04}}">
        <input type="hidden" id="earningDay05" value="{{$earningDay05}}">
        <input type="hidden" id="earningDay06" value="{{$earningDay06}}">
        <input type="hidden" id="earningDay07" value="{{$earningDay07}}">
        <input type="hidden" id="earningDay08" value="{{$earningDay08}}">
        <input type="hidden" id="earningDay09" value="{{$earningDay09}}">

        <input type="hidden" id="Day00" value="{{$Day00}}">
        <input type="hidden" id="Day01" value="{{$Day01}}">
        <input type="hidden" id="Day02" value="{{$Day02}}">
        <input type="hidden" id="Day03" value="{{$Day03}}">
        <input type="hidden" id="Day04" value="{{$Day04}}">
        <input type="hidden" id="Day05" value="{{$Day05}}">
        <input type="hidden" id="Day06" value="{{$Day06}}">
        <input type="hidden" id="Day07" value="{{$Day07}}">
        <input type="hidden" id="Day08" value="{{$Day08}}">
        <input type="hidden" id="Day09" value="{{$Day09}}">
    </div>
</section>









<script>

    // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#858796';

            function number_format(number, decimals, dec_point, thousands_sep) {
                    // *     example: number_format(1234.56, 2, ',', ' ');
                    // *     return: '1 234,56'
                    number = (number + '').replace(',', '').replace(' ', '');
                    var n = !isFinite(+number) ? 0 : +number,
                        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                        s = '',
                        toFixedFix = function(n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                        };
                    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                    if (s[0].length > 3) {
                        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                    }
                    if ((s[1] || '').length < prec) {
                        s[1] = s[1] || '';
                        s[1] += new Array(prec - s[1].length + 1).join('0');
                    }
                    return s.join(dec);
            }

            // Area Chart Example
            var ctx = document.getElementById("barEarningsChart");
            var D0 = document.getElementById("earningDay00").value;
            var D1 = document.getElementById("earningDay01").value;
            var D2 = document.getElementById("earningDay02").value;
            var D3 = document.getElementById("earningDay03").value;
            var D4 = document.getElementById("earningDay04").value;
            var D5 = document.getElementById("earningDay05").value;
            var D6 = document.getElementById("earningDay06").value;
            var D7 = document.getElementById("earningDay07").value;
            var D8 = document.getElementById("earningDay08").value;
            var D9 = document.getElementById("earningDay09").value;

            var Day0 = document.getElementById("Day00").value;
            var Day1 = document.getElementById("Day01").value;
            var Day2 = document.getElementById("Day02").value;
            var Day3 = document.getElementById("Day03").value;
            var Day4 = document.getElementById("Day04").value;
            var Day5 = document.getElementById("Day05").value;
            var Day6 = document.getElementById("Day06").value;
            var Day7 = document.getElementById("Day07").value;
            var Day8 = document.getElementById("Day08").value;
            var Day9 = document.getElementById("Day09").value;


            var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [Day9, Day8, Day7, Day6, Day5, Day4, Day3, Day2, Day1, Day0],
                datasets: [{
                label: " " +$('#merits').val()+ "" ,
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: [D9, D8, D7, D6, D5, D4, D3, D2, D1, D0],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 25
                }
                },
                scales: {
                xAxes: [{
                    time: {
                    unit: 'text'
                    },
                    gridLines: {
                    display: false,
                    drawBorder: false
                    },
                    ticks: {
                    maxTicksLimit: 10
                    }
                }],
                yAxes: [{
                    ticks: {
                    maxTicksLimit: 4,
                    padding: 10,
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return $('#adminP.currencyShow').val()+ " " + number_format(value);
                    }
                    },
                    gridLines: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                    }
                }],
                },
                legend: {
                display: false
                },
                tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                    return datasetLabel + ':' +$('adminP.currencyShow').val()+ " " + number_format(tooltipItem.yLabel);
                    }
                }
                }
            }
            });

</script>