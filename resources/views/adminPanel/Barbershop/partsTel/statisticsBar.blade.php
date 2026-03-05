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

   $Day00 = explode('-', $Day00)[2];
   $Day01 = explode('-', $Day01)[2];
   $Day02 = explode('-', $Day02)[2];
   $Day03 = explode('-', $Day03)[2];
   $Day04 = 'Heute';
   $Day05 = explode('-', $Day05)[2];
   $Day06 = explode('-', $Day06)[2];
   $Day07 = explode('-', $Day07)[2];
   $Day08 = explode('-', $Day08)[2];
   $Day09 = explode('-', $Day09)[2];
?>




<section class="p-2 ml-1 mr-1 mt-2 mb-2">
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h3 style="color:rgb(39,190,175);" class="text-center"><strong>{{__('adminP.statistics')}}</strong></h3>
    </div>


    <div>
        
        <a style="width:100%;" class="nostyle" href="{{route('barAdmin.showReservationsByMonth')}}">  
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{__('adminP.reservationsThisMonth')}}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45">{{__('adminP.currencyShow')}}</span>{{$earningsMonth}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <a style="width:100%;" class="nostyle" href="">
            <div class="card border-left-success shadow h-100 py-2 mt-2">
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

        <div style="width:100%;" class="card border-left-info shadow h-100 py-2 mt-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__('adminP.confirmRejectToday')}}</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{number_format($percentageDone,2,",",".")}}%</div>
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

        <div style="width:100%;" class="card border-left-warning shadow h-100 py-2 mt-2">
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
            <h4 style="color: rgb(72,81,87);"><strong>{{__('adminP.reservationsEarningsTenDay')}}</strong></h4>
        </div>
        <div class="chart-area">
            <canvas id="barEarningsChartTel"></canvas>
        </div>

        <input type="hidden" id="earningDay00Tel" value="{{$earningDay00}}">
        <input type="hidden" id="earningDay01Tel" value="{{$earningDay01}}">
        <input type="hidden" id="earningDay02Tel" value="{{$earningDay02}}">
        <input type="hidden" id="earningDay03Tel" value="{{$earningDay03}}">
        <input type="hidden" id="earningDay04Tel" value="{{$earningDay04}}">
        <input type="hidden" id="earningDay05Tel" value="{{$earningDay05}}">
        <input type="hidden" id="earningDay06Tel" value="{{$earningDay06}}">
        <input type="hidden" id="earningDay07Tel" value="{{$earningDay07}}">
        <input type="hidden" id="earningDay08Tel" value="{{$earningDay08}}">
        <input type="hidden" id="earningDay09Tel" value="{{$earningDay09}}">

        <input type="hidden" id="Day00Tel" value="{{$Day00}}">
        <input type="hidden" id="Day01Tel" value="{{$Day01}}">
        <input type="hidden" id="Day02Tel" value="{{$Day02}}">
        <input type="hidden" id="Day03Tel" value="{{$Day03}}">
        <input type="hidden" id="Day04Tel" value="{{$Day04}}">
        <input type="hidden" id="Day05Tel" value="{{$Day05}}">
        <input type="hidden" id="Day06Tel" value="{{$Day06}}">
        <input type="hidden" id="Day07Tel" value="{{$Day07}}">
        <input type="hidden" id="Day08Tel" value="{{$Day08}}">
        <input type="hidden" id="Day09Tel" value="{{$Day09}}">
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
            var ctx = document.getElementById("barEarningsChartTel");
            var D0 = document.getElementById("earningDay00Tel").value;
            var D1 = document.getElementById("earningDay01Tel").value;
            var D2 = document.getElementById("earningDay02Tel").value;
            var D3 = document.getElementById("earningDay03Tel").value;
            var D4 = document.getElementById("earningDay04Tel").value;
            var D5 = document.getElementById("earningDay05Tel").value;
            var D6 = document.getElementById("earningDay06Tel").value;
            var D7 = document.getElementById("earningDay07Tel").value;
            var D8 = document.getElementById("earningDay08Tel").value;
            var D9 = document.getElementById("earningDay09Tel").value;

            var Day0 = document.getElementById("Day00Tel").value;
            var Day1 = document.getElementById("Day01Tel").value;
            var Day2 = document.getElementById("Day02Tel").value;
            var Day3 = document.getElementById("Day03Tel").value;
            var Day4 = document.getElementById("Day04Tel").value;
            var Day5 = document.getElementById("Day05Tel").value;
            var Day6 = document.getElementById("Day06Tel").value;
            var Day7 = document.getElementById("Day07Tel").value;
            var Day8 = document.getElementById("Day08Tel").value;
            var Day9 = document.getElementById("Day09Tel").value;


            var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [Day9, Day8, Day7, Day6, Day5, Day4, Day3, Day2, Day1, Day0],
                datasets: [{
                label: ""+$('#merits').val()+ "",
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
                        return ''+$('#currencyShow').val()+'' + number_format(value);
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
                    return datasetLabel + ':'+$('#currencyShow').val()+ '' + number_format(tooltipItem.yLabel);
                    }
                }
                }
            }
            });

</script>