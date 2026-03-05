<?php

    use App\Orders;
    use App\TabOrder;
    use App\waiterActivityLog;
    
    $nowDate = date('Y-m-d');
    $nowMonth = date('m');
    $Day_1 = date('Y-m-d',strtotime('-1 days',strtotime($nowDate)));
    $Day_2 = date('Y-m-d',strtotime('-2 days',strtotime($nowDate)));
    $Day_3 = date('Y-m-d',strtotime('-3 days',strtotime($nowDate)));
    // $Day_4 = date('Y-m-d',strtotime('-4 days',strtotime($nowDate)));
    // $Day_5 = date('Y-m-d',strtotime('-5 days',strtotime($nowDate)));
    // $Day_6 = date('Y-m-d',strtotime('-6 days',strtotime($nowDate)));
    // $Day_7 = date('Y-m-d',strtotime('-7 days',strtotime($nowDate)));
    // $Day_8 = date('Y-m-d',strtotime('-8 days',strtotime($nowDate)));
    // $Day_9 = date('Y-m-d',strtotime('-9 days',strtotime($nowDate)));

    $D0Li01Sum = (float)0; 
    $D1Li01Sum = (float)0; 
    $D2Li01Sum = (float)0; 
    $D3Li01Sum = (float)0;  
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$nowDate)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$nowDate)->get() as $oD0){
            if(Orders::find($oD0->actId) != NULL){ $D0Li01Sum = $D0Li01Sum + (float)Orders::find($oD0->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$Day_1)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$Day_1)->get() as $oD1){
            if(Orders::find($oD1->actId) != NULL){ $D1Li01Sum = $D1Li01Sum + (float)Orders::find($oD1->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$Day_2)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$Day_2)->get() as $oD2){
            if(Orders::find($oD2->actId) != NULL){ $D2Li01Sum = $D2Li01Sum + (float)Orders::find($oD2->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$Day_3)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$Day_3)->get() as $oD3){
            if(Orders::find($oD3->actId) != NULL){ $D3Li01Sum = $D3Li01Sum + (float)Orders::find($oD3->actId)->shuma; }
        }
    }



    $D0Li02Sum = (float)0; 
    $D1Li02Sum = (float)0; 
    $D2Li02Sum = (float)0; 
    $D3Li02Sum = (float)0;  
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$nowDate)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$nowDate)->get() as $oD0){
            if(Orders::find($oD0->actId) != NULL){ $D0Li02Sum = $D0Li02Sum + (float)Orders::find($oD0->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$Day_1)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$Day_1)->get() as $oD1){
            if(Orders::find($oD1->actId) != NULL){ $D1Li02Sum = $D1Li02Sum + (float)Orders::find($oD1->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$Day_2)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$Day_2)->get() as $oD2){
            if(Orders::find($oD2->actId) != NULL){ $D2Li02Sum = $D2Li02Sum + (float)Orders::find($oD2->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$Day_3)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$Day_3)->get() as $oD3){
            if(Orders::find($oD3->actId) != NULL){ $D3Li02Sum = $D3Li02Sum + (float)Orders::find($oD3->actId)->shuma; }
        }
    }



    $D0Li03Sum = (float)0; 
    $D1Li03Sum = (float)0; 
    $D2Li03Sum = (float)0; 
    $D3Li03Sum = (float)0;  
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$nowDate)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$nowDate)->get() as $oD0){
            if(Orders::find($oD0->actId) != NULL){ $D0Li03Sum = $D0Li03Sum + (float)Orders::find($oD0->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$Day_1)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$Day_1)->get() as $oD1){
            if(Orders::find($oD1->actId) != NULL){ $D1Li03Sum = $D1Li03Sum + (float)Orders::find($oD1->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$Day_2)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$Day_2)->get() as $oD2){
            if(Orders::find($oD2->actId) != NULL){ $D2Li03Sum = $D2Li03Sum + (float)Orders::find($oD2->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$Day_3)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$Day_3)->get() as $oD3){
            if(Orders::find($oD3->actId) != NULL){ $D3Li03Sum = $D3Li03Sum + (float)Orders::find($oD3->actId)->shuma; }
        }
    }



    $D0Li04Sum = (float)0; 
    $D1Li04Sum = (float)0; 
    $D2Li04Sum = (float)0; 
    $D3Li04Sum = (float)0;  
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$nowDate)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$nowDate)->get() as $oD0){
            if(Orders::find($oD0->actId) != NULL){ $D0Li04Sum = $D0Li04Sum + (float)Orders::find($oD0->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$Day_1)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$Day_1)->get() as $oD1){
            if(Orders::find($oD1->actId) != NULL){ $D1Li04Sum = $D1Li04Sum + (float)Orders::find($oD1->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$Day_2)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$Day_2)->get() as $oD2){
            if(Orders::find($oD2->actId) != NULL){ $D2Li04Sum = $D2Li04Sum + (float)Orders::find($oD2->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$Day_3)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$Day_3)->get() as $oD3){
            if(Orders::find($oD3->actId) != NULL){ $D3Li04Sum = $D3Li04Sum + (float)Orders::find($oD3->actId)->shuma; }
        }
    }

?>

<input type="hidden" id="D0L1Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$nowDate)->sum('sasia')}}">
<input type="hidden" id="D1L1Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$Day_1)->sum('sasia')}}">
<input type="hidden" id="D2L1Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$Day_2)->sum('sasia')}}">
<input type="hidden" id="D3L1Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->whereDate('created_at',$Day_3)->sum('sasia')}}">

<input type="hidden" id="D0L2Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$nowDate)->sum('sasia')}}">
<input type="hidden" id="D1L2Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$Day_1)->sum('sasia')}}">
<input type="hidden" id="D2L2Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$Day_2)->sum('sasia')}}">
<input type="hidden" id="D3L2Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->whereDate('created_at',$Day_3)->sum('sasia')}}">

<input type="hidden" id="D0L3Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$nowDate)->sum('sasia')}}">
<input type="hidden" id="D1L3Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$Day_1)->sum('sasia')}}">
<input type="hidden" id="D2L3Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$Day_2)->sum('sasia')}}">
<input type="hidden" id="D3L3Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at',$Day_3)->sum('sasia')}}">

<input type="hidden" id="D0L4Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$nowDate)->sum('sasia')}}">
<input type="hidden" id="D1L4Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$Day_1)->sum('sasia')}}">
<input type="hidden" id="D2L4Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$Day_2)->sum('sasia')}}">
<input type="hidden" id="D3L4Nr" value="{{waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at',$Day_3)->sum('sasia')}}">


<input type="hidden" id="D0L1Sum" value="{{$D0Li01Sum}}">
<input type="hidden" id="D1L1Sum" value="{{$D1Li01Sum}}">
<input type="hidden" id="D2L1Sum" value="{{$D2Li01Sum}}">
<input type="hidden" id="D3L1Sum" value="{{$D3Li01Sum}}">

<input type="hidden" id="D0L2Sum" value="{{$D0Li02Sum}}">
<input type="hidden" id="D1L2Sum" value="{{$D1Li02Sum}}">
<input type="hidden" id="D2L2Sum" value="{{$D2Li02Sum}}">
<input type="hidden" id="D3L2Sum" value="{{$D3Li02Sum}}">

<input type="hidden" id="D0L3Sum" value="{{$D0Li03Sum}}">
<input type="hidden" id="D1L3Sum" value="{{$D1Li03Sum}}">
<input type="hidden" id="D2L3Sum" value="{{$D2Li03Sum}}">
<input type="hidden" id="D3L3Sum" value="{{$D3Li03Sum}}">

<input type="hidden" id="D0L4Sum" value="{{$D0Li04Sum}}">
<input type="hidden" id="D1L4Sum" value="{{$D1Li04Sum}}">
<input type="hidden" id="D2L4Sum" value="{{$D2Li04Sum}}">
<input type="hidden" id="D3L4Sum" value="{{$D3Li04Sum}}">


<input type="hidden" id="Day0" value="{{explode('-',$nowDate)[2]}}.{{explode('-',$nowDate)[1]}}">
<input type="hidden" id="Day1" value="{{explode('-',$Day_1)[2]}}.{{explode('-',$Day_1)[1]}}">
<input type="hidden" id="Day2" value="{{explode('-',$Day_2)[2]}}.{{explode('-',$Day_2)[1]}}">
<input type="hidden" id="Day3" value="{{explode('-',$Day_3)[2]}}.{{explode('-',$Day_3)[1]}}">








    <script>
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        var Day0 = $('#Day0').val();
        var Day1 = $('#Day1').val();
        var Day2 = $('#Day2').val();
        var Day3 = $('#Day3').val();

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





        //-------------------------------------------------------------------------------------------------------------------------------------------------------------- 



        var ctxLi01Nr = document.getElementById("chart01Nr");
        var D0Li01Nr = $('#D0L1Nr').val();
        var D1Li01Nr = $('#D1L1Nr').val();
        var D2Li01Nr = $('#D2L1Nr').val();
        var D3Li01Nr = $('#D3L1Nr').val();
 
        var chLi01Nr = new Chart(ctxLi01Nr, {
            type: 'line',
            data: {
                labels: [Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(255,196,27,255)", pointRadius: 3,
                    pointBackgroundColor: "rgba(255,196,27,255)", pointBorderColor: "rgba(255,196,27,255)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(255,196,27,255)", pointHoverBorderColor: "rgba(255,196,27,255)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D3Li01Nr, D2Li01Nr, D1Li01Nr, D0Li01Nr],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return  number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + '#: '+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });



        var ctxLi02Nr = document.getElementById("chart02Nr");
        var D0Li02Nr = $('#D0L2Nr').val();
        var D1Li02Nr = $('#D1L2Nr').val();
        var D2Li02Nr = $('#D2L2Nr').val();
        var D3Li02Nr = $('#D3L2Nr').val();
 
        var chLi02Nr = new Chart(ctxLi02Nr, {
            type: 'line',
            data: {
                labels: [Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(78, 115, 223, 1)", pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)", pointBorderColor: "rgba(78, 115, 223, 1)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)", pointHoverBorderColor: "rgba(78, 115, 223, 1)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D3Li02Nr, D2Li02Nr, D1Li02Nr, D0Li02Nr],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return  number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + '#: '+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });


        
        var ctxLi03Nr = document.getElementById("chart03Nr");
        var D0Li03Nr = $('#D0L3Nr').val();
        var D1Li03Nr = $('#D1L3Nr').val();
        var D2Li03Nr = $('#D2L3Nr').val();
        var D3Li03Nr = $('#D3L3Nr').val();
 
        var chLi03Nr = new Chart(ctxLi03Nr, {
            type: 'line',
            data: {
                labels: [Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(223,79,100,255)", pointRadius: 3,
                    pointBackgroundColor: "rgba(223,79,100,255)", pointBorderColor: "rgba(223,79,100,255)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(223,79,100,255)", pointHoverBorderColor: "rgba(223,79,100,255)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D3Li03Nr, D2Li03Nr, D1Li03Nr, D0Li03Nr],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return  number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + '#: '+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });



        var ctxLi04Nr = document.getElementById("chart04Nr");
        var D0Li04Nr = $('#D0L4Nr').val();
        var D1Li04Nr = $('#D1L4Nr').val();
        var D2Li04Nr = $('#D2L4Nr').val();
        var D3Li04Nr = $('#D3L4Nr').val();
 
        var chLi04Nr = new Chart(ctxLi04Nr, {
            type: 'line',
            data: {
                labels: [Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(46,167,72,255)", pointRadius: 3,
                    pointBackgroundColor: "rgba(46,167,72,255)", pointBorderColor: "rgba(46,167,72,255)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(46,167,72,255)", pointHoverBorderColor: "rgba(46,167,72,255)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D3Li04Nr, D2Li04Nr, D1Li04Nr, D0Li04Nr],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return  number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + '#: '+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });



        //-------------------------------------------------------------------------------------------------------------------------------------------------------------- 



        var ctxLi01Sum = document.getElementById("chart01Sum");
        var D0Li01Sum = $('#D0L1Sum').val();
        var D1Li01Sum = $('#D1L1Sum').val();
        var D2Li01Sum = $('#D2L1Sum').val();
        var D3Li01Sum = $('#D3L1Sum').val();
 
        var chLi01Sum = new Chart(ctxLi01Sum, {
            type: 'line',
            data: {
                labels: [Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(255,196,27,255)", pointRadius: 3,
                    pointBackgroundColor: "rgba(255,196,27,255)", pointBorderColor: "rgba(255,196,27,255)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(255,196,27,255)", pointHoverBorderColor: "rgba(255,196,27,255)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D3Li01Sum, D2Li01Sum, D1Li01Sum, D0Li01Sum],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + 'CHF:'+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });



        var ctxLi02Sum = document.getElementById("chart02Sum");
        var D0Li02Sum = $('#D0L2Sum').val();
        var D1Li02Sum = $('#D1L2Sum').val();
        var D2Li02Sum = $('#D2L2Sum').val();
        var D3Li02Sum = $('#D3L2Sum').val();
 
        var chLi02Sum = new Chart(ctxLi02Sum, {
            type: 'line',
            data: {
                labels: [Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(78, 115, 223, 1)", pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)", pointBorderColor: "rgba(78, 115, 223, 1)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)", pointHoverBorderColor: "rgba(78, 115, 223, 1)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D3Li02Sum, D2Li02Sum, D1Li02Sum, D0Li02Sum],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return  number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + 'CHF:'+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });



        var ctxLi03Sum = document.getElementById("chart03Sum");
        var D0Li03Sum = $('#D0L3Sum').val();
        var D1Li03Sum = $('#D1L3Sum').val();
        var D2Li03Sum = $('#D2L3Sum').val();
        var D3Li03Sum = $('#D3L3Sum').val();
 
        var chLi03Sum = new Chart(ctxLi03Sum, {
            type: 'line',
            data: {
                labels: [Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(223,79,100,255)", pointRadius: 3,
                    pointBackgroundColor: "rgba(223,79,100,255)", pointBorderColor: "rgba(223,79,100,255)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(223,79,100,255)", pointHoverBorderColor: "rgba(223,79,100,255)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D3Li03Sum, D2Li03Sum, D1Li03Sum, D0Li03Sum],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return  number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + 'CHF:'+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });



        var ctxLi04Sum = document.getElementById("chart04Sum");
        var D0Li04Sum = $('#D0L4Sum').val();
        var D1Li04Sum = $('#D1L4Sum').val();
        var D2Li04Sum = $('#D2L4Sum').val();
        var D3Li04Sum = $('#D3L4Sum').val();
 
        var chLi04Sum = new Chart(ctxLi04Sum, {
            type: 'line',
            data: {
                labels: [Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(46,167,72,255)", pointRadius: 3,
                    pointBackgroundColor: "rgba(46,167,72,255)", pointBorderColor: "rgba(46,167,72,255)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(46,167,72,255)", pointHoverBorderColor: "rgba(46,167,72,255)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D3Li04Sum, D2Li04Sum, D1Li04Sum, D0Li04Sum],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return  number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + 'CHF:'+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });

        //-------------------------------------------------------------------------------------------------------------------------------------------------------------- 


    </script>