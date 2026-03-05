<?php
    use App\Restorant;
    use App\Barbershop;
    use App\Orders;
?>

<style>
    .ButoniAct{
        color:lightgray;
        background-color:white;
        border:none;
        border-radius:20px;
    }
    .ButoniActSel{
        color:white;
        background-color:rgb(39,190,175);
        border:none;
        border-radius:20px;
    }
</style>





















<?php
     $nowDate = date('Y-m-d');
     $nowMonth = date('m');

     $orderNumber = 0;
 
     $Day_1 = date('Y-m-d',strtotime('-1 days',strtotime($nowDate)));
     $Day_2 = date('Y-m-d',strtotime('-2 days',strtotime($nowDate)));
     $Day_3 = date('Y-m-d',strtotime('-3 days',strtotime($nowDate)));
     $Day_4 = date('Y-m-d',strtotime('-4 days',strtotime($nowDate)));
     $Day_5 = date('Y-m-d',strtotime('-5 days',strtotime($nowDate)));
     $Day_6 = date('Y-m-d',strtotime('-6 days',strtotime($nowDate)));
     $Day_7 = date('Y-m-d',strtotime('-7 days',strtotime($nowDate)));
     $Day_8 = date('Y-m-d',strtotime('-8 days',strtotime($nowDate)));
     $Day_9 = date('Y-m-d',strtotime('-9 days',strtotime($nowDate)));
     $Day_10 = date('Y-m-d',strtotime('-10 days',strtotime($nowDate)));
     $Day_11 = date('Y-m-d',strtotime('-11 days',strtotime($nowDate)));
     $Day_12 = date('Y-m-d',strtotime('-12 days',strtotime($nowDate)));
     $Day_13 = date('Y-m-d',strtotime('-13 days',strtotime($nowDate)));
     $Day_14 = date('Y-m-d',strtotime('-14 days',strtotime($nowDate)));
     $Day_15 = date('Y-m-d',strtotime('-15 days',strtotime($nowDate)));
     $Day_16 = date('Y-m-d',strtotime('-16 days',strtotime($nowDate)));
     $Day_17 = date('Y-m-d',strtotime('-17 days',strtotime($nowDate)));
     $Day_18 = date('Y-m-d',strtotime('-18 days',strtotime($nowDate)));
     $Day_19 = date('Y-m-d',strtotime('-19 days',strtotime($nowDate)));
     $Day_20 = date('Y-m-d',strtotime('-20 days',strtotime($nowDate)));
     $Day_21 = date('Y-m-d',strtotime('-21 days',strtotime($nowDate)));
     $Day_22 = date('Y-m-d',strtotime('-22 days',strtotime($nowDate)));
     $Day_23 = date('Y-m-d',strtotime('-23 days',strtotime($nowDate)));
     $Day_24 = date('Y-m-d',strtotime('-24 days',strtotime($nowDate)));
     $Day_25 = date('Y-m-d',strtotime('-25 days',strtotime($nowDate)));
     $Day_26 = date('Y-m-d',strtotime('-26 days',strtotime($nowDate)));
     $Day_27 = date('Y-m-d',strtotime('-27 days',strtotime($nowDate)));
     $Day_28 = date('Y-m-d',strtotime('-28 days',strtotime($nowDate)));
     $Day_29 = date('Y-m-d',strtotime('-29 days',strtotime($nowDate)));


    $earningToday = 0;
    $earningDay_1 = 0;
    $earningDay_2 = 0;
    $earningDay_3 = 0;
    $earningDay_4 = 0;
    $earningDay_5 = 0;
    $earningDay_6 = 0;
    $earningDay_7 = 0;
    $earningDay_8 = 0;
    $earningDay_9 = 0;
    $earningDay_10 = 0;
    $earningDay_11 = 0;
    $earningDay_12 = 0;
    $earningDay_13 = 0;
    $earningDay_14 = 0;
    $earningDay_15 = 0;
    $earningDay_16 = 0;
    $earningDay_17 = 0;
    $earningDay_18 = 0;
    $earningDay_19 = 0;
    $earningDay_20 = 0;
    $earningDay_21 = 0;
    $earningDay_22 = 0;
    $earningDay_23 = 0;
    $earningDay_24 = 0;
    $earningDay_25 = 0;
    $earningDay_26 = 0;
    $earningDay_27 = 0;
    $earningDay_28 = 0;
    $earningDay_29 = 0;


    foreach(Orders::all() as $order){
  
        $orderDate = explode(' ',$order->created_at);

        if($orderDate[0] == $nowDate){
            $earningToday += $order->shuma;
            $orderNumber++;
        }
        if($Day_1 == $orderDate[0]){
            $earningDay_1 += $order->shuma;
        }
        if($Day_2 == $orderDate[0]){
            $earningDay_2 += $order->shuma;
        }
        if($Day_3 == $orderDate[0]){
            $earningDay_3 += $order->shuma;
        }
        if($Day_4 == $orderDate[0]){
            $earningDay_4 += $order->shuma;
        }
        if($Day_5 == $orderDate[0]){
            $earningDay_5 += $order->shuma;
        }
        if($Day_6 == $orderDate[0]){
            $earningDay_6 += $order->shuma;
        }
        if($Day_7 == $orderDate[0]){
            $earningDay_7 += $order->shuma;
        }
        if($Day_8 == $orderDate[0]){
            $earningDay_8 += $order->shuma;
        }
        if($Day_9 == $orderDate[0]){
            $earningDay_9 += $order->shuma;
        }
        if($Day_10 == $orderDate[0]){
            $earningDay_10 += $order->shuma;
        }
        if($Day_11 == $orderDate[0]){
            $earningDay_11 += $order->shuma;
        }
        if($Day_12 == $orderDate[0]){
            $earningDay_12 += $order->shuma;
        }
        if($Day_13 == $orderDate[0]){
            $earningDay_13 += $order->shuma;
        }
        if($Day_14 == $orderDate[0]){
            $earningDay_14 += $order->shuma;
        }
        if($Day_15 == $orderDate[0]){
            $earningDay_15 += $order->shuma;
        }
        if($Day_16 == $orderDate[0]){
            $earningDay_16 += $order->shuma;
        }
        if($Day_17 == $orderDate[0]){
            $earningDay_17 += $order->shuma;
        }
        if($Day_18 == $orderDate[0]){
            $earningDay_18 += $order->shuma;
        }
        if($Day_19 == $orderDate[0]){
            $earningDay_19 += $order->shuma;
        }
        if($Day_20 == $orderDate[0]){
            $earningDay_20 += $order->shuma;
        }
        if($Day_21 == $orderDate[0]){
            $earningDay_21 += $order->shuma;
        }
        if($Day_22 == $orderDate[0]){
            $earningDay_22 += $order->shuma;
        }
        if($Day_23 == $orderDate[0]){
            $earningDay_23 += $order->shuma;
        }
        if($Day_24 == $orderDate[0]){
            $earningDay_24 += $order->shuma;
        }
        if($Day_25 == $orderDate[0]){
            $earningDay_25 += $order->shuma;
        }
        if($Day_26 == $orderDate[0]){
            $earningDay_26 += $order->shuma;
        }
        if($Day_27 == $orderDate[0]){
            $earningDay_27 += $order->shuma;
        }
        if($Day_28 == $orderDate[0]){
            $earningDay_28 += $order->shuma;
        }
        if($Day_29 == $orderDate[0]){
            $earningDay_29 += $order->shuma;
        }
     

    }





    
    $nowDate = date('d-M',strtotime($nowDate));
    $Day_1 = date('d-M',strtotime($Day_1));
    $Day_2 = date('d-M',strtotime($Day_2));
    $Day_3 = date('d-M',strtotime($Day_3));
    $Day_4 = date('d-M',strtotime($Day_4));
    $Day_5 = date('d-M',strtotime($Day_5));
    $Day_6 = date('d-M',strtotime($Day_6));
    $Day_7 = date('d-M',strtotime($Day_7));
    $Day_8 = date('d-M',strtotime($Day_8));
    $Day_9 = date('d-M',strtotime($Day_9));
    $Day_10 = date('d-M',strtotime($Day_10));
    $Day_11 = date('d-M',strtotime($Day_11));
    $Day_12 = date('d-M',strtotime($Day_12));
    $Day_13 = date('d-M',strtotime($Day_13));
    $Day_14 = date('d-M',strtotime($Day_14));
    $Day_15 = date('d-M',strtotime($Day_15));
    $Day_16 = date('d-M',strtotime($Day_16));
    $Day_17 = date('d-M',strtotime($Day_17));
    $Day_18 = date('d-M',strtotime($Day_18));
    $Day_19 = date('d-M',strtotime($Day_19));
    $Day_20 = date('d-M',strtotime($Day_20));
    $Day_21 = date('d-M',strtotime($Day_21));
    $Day_22 = date('d-M',strtotime($Day_22));
    $Day_23 = date('d-M',strtotime($Day_23));
    $Day_24 = date('d-M',strtotime($Day_24));
    $Day_25 = date('d-M',strtotime($Day_25));
    $Day_26 = date('d-M',strtotime($Day_26));
    $Day_27 = date('d-M',strtotime($Day_27));
    $Day_28 = date('d-M',strtotime($Day_28));
    $Day_29 = date('d-M',strtotime($Day_29));
   
?>






<br><br>
<div class="container">
    <div class="row">
        <div class="col-8 ">
            <div class="container b-qrorpa ml-4 mb-4" style="border-radius:40px; height:29%;">
                <div class="row pl-4 pr-4 pt-5">
                    <div class="col-8 text-left">
                        <p class="color-white fsize-35" > Hallo {{Auth::user()->name}}</p>
                    </div>
                    <div class="col-4 text-right">
                        <p class="color-white fsize-35" id="firstPageTime">.</p>
                    </div>
                </div>
                <div class="row pl-4 pr-4 pb-5" style="margin-top:-20px;">
                    <div class="col-8 text-left">
                        <p class="color-white opacity-65" > Sie haben eine neue Benachrichtigung</p>
                    </div>
                    <div class="col-4 text-right">
                        <p class="color-white opacity-65" id="firstPageDate">.</p>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between ml-4">
                <div style="width:24%; background-color:rgb(249, 249, 249);" class="br-25 p-3">
                    <p class="opacity-85"><strong>Online-Barbershops</strong> </p>
                    <p class="fsize-35 color-qrorpa" style="margin-top:-30px;">{{count(Barbershop::all())}}</p>
                </div>
                <div style="width:24%; background-color:rgb(249, 249, 249);" class="br-25 p-3">
                    <p class="opacity-85"><strong>Neue Kunden</strong> </p>
                    <p class="fsize-35 color-qrorpa" style="margin-top:-30px;">123</p>
                </div>
                <div style="width:24%; background-color:rgb(249, 249, 249);" class="br-25 p-3">
                    <p class="opacity-85"><strong>Heute Bestellungen</strong> </p>
                    <p class="fsize-35 color-qrorpa" style="margin-top:-30px;">{{$orderNumber}}</p>
                </div>
                <div style="width:24%; background-color:rgb(249, 249, 249);" class="br-25 p-3">
                    <p class="opacity-85"><strong>Heute Vertrieb</strong> </p>
                    <p class="fsize-35 color-qrorpa" style="margin-top:-30px;">
                    <span style="font-size:15px;">CHF </span>{{$earningToday}}</p>
                </div>
            </div>
            
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-8 text-left">
                        <p class="color-qrorpa"><strong>Aktivitätsverkauf</strong></p>
                    </div>
                    <div class="col-4 text-right">
                        <button id="act7Days" class="ButoniActSel" style="width:48%">7 Tage</button>
                        <button id="act30Days" class="ButoniAct" style="width:48%">30 Tage</button>
                    </div>
                </div>
            </div>
            <div class="container" id="weekChartDiv">
                <div class="chart-area">
                    <canvas id="weekChart"></canvas>
                </div>
            </div>
            <div class="container" id="monthChartDiv">
                <div class="chart-area">
                    <canvas id="monthChart"></canvas>
                </div>
            </div>
           

        </div>

        <div class="col-3 ml-5">
            <div class="container-fluid">
                <div class="row pt-3">
                    <div class="col-12">
                        <p class="color-qrorpa"><strong>Online-Barbershops </strong></p>
                    </div>
                </div>

                @foreach(Barbershop::all()->sortByDesc('created_at') as $res)
                    <div class="row">
                        <div class="col-12 mt-2" style="border-bottom:1px solid lightgray;">
                            <div class="container">
                                <div class="row mb-3">
                                    <div class="col-2">
                                 
                                        <img src="storage/icons/Logo.png" alt="" style="width:550%;">
                                   
                                    </div>
                                    <div class="col-10">
                                        <span class="color-qrorpa ml-3"><strong>{{$res->emri}} </strong></span>
                                        <br>
                                        <span class="color-qrorpa ml-3 opacity-65" style="font-size:12px;"><strong>{{$res->adresa}} </strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            

        </div>
    </div>
</div>







<input type="hidden" id="M_D0" value="{{$earningToday}}">
<input type="hidden" id="M_D1" value="{{$earningDay_1}}">
<input type="hidden" id="M_D2" value="{{$earningDay_2}}">
<input type="hidden" id="M_D3" value="{{$earningDay_3}}">
<input type="hidden" id="M_D4" value="{{$earningDay_4}}">
<input type="hidden" id="M_D5" value="{{$earningDay_5}}">
<input type="hidden" id="M_D6" value="{{$earningDay_6}}">
<input type="hidden" id="M_D7" value="{{$earningDay_7}}">
<input type="hidden" id="M_D8" value="{{$earningDay_8}}">
<input type="hidden" id="M_D9" value="{{$earningDay_9}}">
<input type="hidden" id="M_D10" value="{{$earningDay_10}}">
<input type="hidden" id="M_D11" value="{{$earningDay_11}}">
<input type="hidden" id="M_D12" value="{{$earningDay_12}}">
<input type="hidden" id="M_D13" value="{{$earningDay_13}}">
<input type="hidden" id="M_D14" value="{{$earningDay_14}}">
<input type="hidden" id="M_D15" value="{{$earningDay_15}}">
<input type="hidden" id="M_D16" value="{{$earningDay_16}}">
<input type="hidden" id="M_D17" value="{{$earningDay_17}}">
<input type="hidden" id="M_D18" value="{{$earningDay_18}}">
<input type="hidden" id="M_D19" value="{{$earningDay_19}}">
<input type="hidden" id="M_D20" value="{{$earningDay_20}}">
<input type="hidden" id="M_D21" value="{{$earningDay_21}}">
<input type="hidden" id="M_D22" value="{{$earningDay_22}}">
<input type="hidden" id="M_D23" value="{{$earningDay_23}}">
<input type="hidden" id="M_D24" value="{{$earningDay_24}}">
<input type="hidden" id="M_D25" value="{{$earningDay_25}}">
<input type="hidden" id="M_D26" value="{{$earningDay_26}}">
<input type="hidden" id="M_D27" value="{{$earningDay_27}}">
<input type="hidden" id="M_D28" value="{{$earningDay_28}}">
<input type="hidden" id="M_D29" value="{{$earningDay_29}}">


        <input type="hidden" id="Day0" value="{{$nowDate}}">
        <input type="hidden" id="Day1" value="{{$Day_1}}">
        <input type="hidden" id="Day2" value="{{$Day_2}}">
        <input type="hidden" id="Day3" value="{{$Day_3}}">
        <input type="hidden" id="Day4" value="{{$Day_4}}">
        <input type="hidden" id="Day5" value="{{$Day_5}}">
        <input type="hidden" id="Day6" value="{{$Day_6}}">
        <input type="hidden" id="Day7" value="{{$Day_7}}">
        <input type="hidden" id="Day8" value="{{$Day_8}}">
        <input type="hidden" id="Day9" value="{{$Day_9}}">
        <input type="hidden" id="Day10" value="{{$Day_10}}">
        <input type="hidden" id="Day11" value="{{$Day_11}}">
        <input type="hidden" id="Day12" value="{{$Day_12}}">
        <input type="hidden" id="Day13" value="{{$Day_13}}">
        <input type="hidden" id="Day14" value="{{$Day_14}}">
        <input type="hidden" id="Day15" value="{{$Day_15}}">
        <input type="hidden" id="Day16" value="{{$Day_16}}">
        <input type="hidden" id="Day17" value="{{$Day_17}}">
        <input type="hidden" id="Day18" value="{{$Day_18}}">
        <input type="hidden" id="Day19" value="{{$Day_19}}">
        <input type="hidden" id="Day20" value="{{$Day_20}}">
        <input type="hidden" id="Day21" value="{{$Day_21}}">
        <input type="hidden" id="Day22" value="{{$Day_22}}">
        <input type="hidden" id="Day23" value="{{$Day_23}}">
        <input type="hidden" id="Day24" value="{{$Day_24}}">
        <input type="hidden" id="Day25" value="{{$Day_25}}">
        <input type="hidden" id="Day26" value="{{$Day_26}}">
        <input type="hidden" id="Day27" value="{{$Day_27}}">
        <input type="hidden" id="Day28" value="{{$Day_28}}">
        <input type="hidden" id="Day29" value="{{$Day_29}}">



<script type="text/javascript">

    $(document).ready(function() {
        $("#monthChartDiv").hide();
    });

    $("#act30Days").click(function(){
        $("#act30Days").attr('class', 'ButoniActSel');
        $("#act7Days").attr('class', 'ButoniAct');

        $("#monthChartDiv").show();

        $("#monthChart").show('slow');
        $("#weekChart").hide('slow');

       
    });

    $("#act7Days").click(function(){
        $("#act7Days").attr('class', 'ButoniActSel');
        $("#act30Days").attr('class', 'ButoniAct');

        $("#monthChart").hide('slow');
        $("#weekChart").show('slow');

        $("#monthChartDiv").hide();
    });

    function pad(d) {
        return (d < 10) ? '0' + d.toString() : d.toString();
    }



  function showTime() {
    var today = new Date()
    var time = pad(today.getHours())+':'+pad(today.getMinutes())+':'+pad(today.getSeconds());
    var date = pad(today.getDate())+'/'+pad((today.getMonth()+1))+'/'+today.getFullYear();
    document.getElementById('firstPageTime').innerHTML = time;
    document.getElementById('firstPageDate').innerHTML = date;
  }
  setInterval(showTime, 1000);





















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
            var ctx = document.getElementById("weekChart");

            var D0 = document.getElementById("M_D0").value;
            var D1 = document.getElementById("M_D1").value;
            var D2 = document.getElementById("M_D2").value;
            var D3 = document.getElementById("M_D3").value;
            var D4 = document.getElementById("M_D4").value;
            var D5 = document.getElementById("M_D5").value;
            var D6 = document.getElementById("M_D6").value;
            var D7 = document.getElementById("M_D7").value;
            var D8 = document.getElementById("M_D8").value;
            var D9 = document.getElementById("M_D9").value;
            var D10 = document.getElementById("M_D10").value;
            var D11 = document.getElementById("M_D11").value;
            var D12 = document.getElementById("M_D12").value;
            var D13 = document.getElementById("M_D13").value;
            var D14 = document.getElementById("M_D14").value;
            var D15 = document.getElementById("M_D15").value;
            var D16 = document.getElementById("M_D16").value;
            var D17 = document.getElementById("M_D17").value;
            var D18 = document.getElementById("M_D18").value;
            var D19 = document.getElementById("M_D19").value;
            var D20 = document.getElementById("M_D20").value;
            var D21 = document.getElementById("M_D21").value;
            var D22 = document.getElementById("M_D22").value;
            var D23 = document.getElementById("M_D23").value;
            var D24 = document.getElementById("M_D24").value;
            var D25 = document.getElementById("M_D25").value;
            var D26 = document.getElementById("M_D26").value;
            var D27 = document.getElementById("M_D27").value;
            var D28 = document.getElementById("M_D28").value;
            var D29 = document.getElementById("M_D29").value;

            var Day0 = document.getElementById("Day0").value;
            var Day1 = document.getElementById("Day1").value;
            var Day2 = document.getElementById("Day2").value;
            var Day3 = document.getElementById("Day3").value;
            var Day4 = document.getElementById("Day4").value;
            var Day5 = document.getElementById("Day5").value;
            var Day6 = document.getElementById("Day6").value;
            var Day7 = document.getElementById("Day7").value;
            var Day8 = document.getElementById("Day8").value;
            var Day9 = document.getElementById("Day9").value;
            var Day10 = document.getElementById("Day10").value;
            var Day11 = document.getElementById("Day11").value;
            var Day12 = document.getElementById("Day12").value;
            var Day13 = document.getElementById("Day13").value;
            var Day14 = document.getElementById("Day14").value;
            var Day15 = document.getElementById("Day15").value;
            var Day16 = document.getElementById("Day16").value;
            var Day17 = document.getElementById("Day17").value;
            var Day18 = document.getElementById("Day18").value;
            var Day19 = document.getElementById("Day19").value;
            var Day20 = document.getElementById("Day20").value;
            var Day21 = document.getElementById("Day21").value;
            var Day22 = document.getElementById("Day22").value;
            var Day23 = document.getElementById("Day23").value;
            var Day24 = document.getElementById("Day24").value;
            var Day25 = document.getElementById("Day25").value;
            var Day26 = document.getElementById("Day26").value;
            var Day27 = document.getElementById("Day27").value;
            var Day28 = document.getElementById("Day28").value;
            var Day29 = document.getElementById("Day29").value;
           


                var myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [Day6, Day5, Day4, Day3, Day2, Day1, Day0],
                    datasets: [{
                    label: "Earnings",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(39,190,175,0.6)",
                    pointBorderColor: "rgb(39,190,175)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: [D6, D5, D4, D3, D2, D1, D0],
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
                            return 'CHF ' + number_format(value);
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
                        return datasetLabel + ': CHF ' + number_format(tooltipItem.yLabel);
                        }
                    }
                    }
                }
            });









            // Area Chart Example
            var ctx2 = document.getElementById("monthChart");
           


                var myLineChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: [Day29, Day28, Day27, Day26, Day25, Day24, Day23, Day22, Day21, Day20, Day19, Day18, Day17, Day16, Day15, Day14, Day13, Day12, Day11, Day10, Day9, Day8, Day7, Day6, Day5, Day4, Day3, Day2, Day1, Day0],
                    datasets: [{
                    label: "Earnings",
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
                    data: [D29, D28, D27, D26, D25, D24, D23, D22, D21, D20, D19, D18, D17, D16, D15, D14, D13, D12, D11, D10, D9, D8, D7, D6, D5, D4, D3, D2, D1, D0],
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
                            return 'CHF ' + number_format(value);
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
                        return datasetLabel + ': CHF ' + number_format(tooltipItem.yLabel);
                        }
                    }
                    }
                }
            });













</script>