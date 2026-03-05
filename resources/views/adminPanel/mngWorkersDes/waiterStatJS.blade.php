<?php

use App\Orders;
use App\TabOrder;
use App\waiterActivityLog;
    
    $nowDate = date('Y-m-d');
    $nowMonth = date('m');
    $Day_1 = date('Y-m-d',strtotime('-1 days',strtotime($nowDate)));
    $Day_2 = date('Y-m-d',strtotime('-2 days',strtotime($nowDate)));
    $Day_3 = date('Y-m-d',strtotime('-3 days',strtotime($nowDate)));
    $Day_4 = date('Y-m-d',strtotime('-4 days',strtotime($nowDate)));
    $Day_5 = date('Y-m-d',strtotime('-5 days',strtotime($nowDate)));
    $Day_6 = date('Y-m-d',strtotime('-6 days',strtotime($nowDate)));
    $Day_7 = date('Y-m-d',strtotime('-7 days',strtotime($nowDate)));
    $Day_8 = date('Y-m-d',strtotime('-8 days',strtotime($nowDate)));
    $Day_9 = date('Y-m-d',strtotime('-9 days',strtotime($nowDate)));

    $D0APSum = (float)0; 
    $D1APSum = (float)0; 
    $D2APSum = (float)0; 
    $D3APSum = (float)0; 
    $D4APSum = (float)0; 
    $D5APSum = (float)0; 
    $D6APSum = (float)0; 
    $D7APSum = (float)0; 
    $D8APSum = (float)0; 
    $D9APSum = (float)0; 

    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$nowDate)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$nowDate)->get() as $oneD0APSum){
            if(TabOrder::find($oneD0APSum->actId) != NULL){ $D0APSum = $D0APSum + (float)TabOrder::find($oneD0APSum->actId)->OrderQmimi; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_1)->count() > 0 ){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_1)->get() as $oneD1APSum){
            if(TabOrder::find($oneD1APSum->actId) != NULL){ $D1APSum = $D1APSum + (float)TabOrder::find($oneD1APSum->actId)->OrderQmimi; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_2)->count() > 0 ){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_2)->get() as $oneD2APSum){
            if(TabOrder::find($oneD2APSum->actId) != NULL){ $D2APSum = $D2APSum + (float)TabOrder::find($oneD2APSum->actId)->OrderQmimi; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_3)->count() > 0 ){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_3)->get() as $oneD3APSum){
            if(TabOrder::find($oneD3APSum->actId) != NULL){ $D3APSum = $D3APSum + (float)TabOrder::find($oneD3APSum->actId)->OrderQmimi; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_4)->count() > 0 ){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_4)->get() as $oneD4APSum){
            if(TabOrder::find($oneD4APSum->actId) != NULL){ $D4APSum = $D4APSum + (float)TabOrder::find($oneD4APSum->actId)->OrderQmimi; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_5)->count() > 0 ){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_5)->get() as $oneD5APSum){
            if(TabOrder::find($oneD5APSum->actId) != NULL){ $D5APSum = $D5APSum + (float)TabOrder::find($oneD5APSum->actId)->OrderQmimi; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_6)->count() > 0 ){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_6)->get() as $oneD6APSum){
            if(TabOrder::find($oneD6APSum->actId) != NULL){ $D6APSum = $D6APSum + (float)TabOrder::find($oneD6APSum->actId)->OrderQmimi; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_7)->count() > 0 ){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_7)->get() as $oneD7APSum){
            if(TabOrder::find($oneD7APSum->actId) != NULL){ $D7APSum = $D7APSum + (float)TabOrder::find($oneD7APSum->actId)->OrderQmimi; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_8)->count() > 0 ){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_8)->get() as $oneD8APSum){
            if(TabOrder::find($oneD8APSum->actId) != NULL){ $D8APSum = $D8APSum + (float)TabOrder::find($oneD8APSum->actId)->OrderQmimi; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_9)->count() > 0 ){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_9)->get() as $oneD9APSum){
            if(TabOrder::find($oneD9APSum->actId) != NULL){ $D9APSum = $D9APSum + (float)TabOrder::find($oneD9APSum->actId)->OrderQmimi; }
        }
    }


    $D0PPSum = (float)0; 
    $D1PPSum = (float)0; 
    $D2PPSum = (float)0; 
    $D3PPSum = (float)0; 
    $D4PPSum = (float)0; 
    $D5PPSum = (float)0; 
    $D6PPSum = (float)0; 
    $D7PPSum = (float)0; 
    $D8PPSum = (float)0; 
    $D9PPSum = (float)0; 

    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$nowDate)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$nowDate)->get() as $oneD0PPSum){
            if(Orders::find($oneD0PPSum->actId) != NULL){ $D0PPSum = $D0PPSum + (float)Orders::find($oneD0PPSum->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_1)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_1)->get() as $oneD1PPSum){
            if(Orders::find($oneD1PPSum->actId) != NULL){ $D1PPSum = $D1PPSum + (float)Orders::find($oneD1PPSum->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_2)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_2)->get() as $oneD2PPSum){
            if(Orders::find($oneD2PPSum->actId) != NULL){ $D2PPSum = $D2PPSum + (float)Orders::find($oneD2PPSum->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_3)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_3)->get() as $oneD3PPSum){
            if(Orders::find($oneD3PPSum->actId) != NULL){ $D3PPSum = $D3PPSum + (float)Orders::find($oneD3PPSum->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_4)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_4)->get() as $oneD4PPSum){
            if(Orders::find($oneD4PPSum->actId) != NULL){ $D4PPSum = $D4PPSum + (float)Orders::find($oneD4PPSum->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_5)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_5)->get() as $oneD5PPSum){
            if(Orders::find($oneD5PPSum->actId) != NULL){ $D5PPSum = $D5PPSum + (float)Orders::find($oneD5PPSum->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_6)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_6)->get() as $oneD6PPSum){
            if(Orders::find($oneD6PPSum->actId) != NULL){ $D6PPSum = $D6PPSum + (float)Orders::find($oneD6PPSum->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_7)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_7)->get() as $oneD7PPSum){
            if(Orders::find($oneD7PPSum->actId) != NULL){ $D7PPSum = $D7PPSum + (float)Orders::find($oneD7PPSum->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_8)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_8)->get() as $oneD8PPSum){
            if(Orders::find($oneD8PPSum->actId) != NULL){ $D8PPSum = $D8PPSum + (float)Orders::find($oneD8PPSum->actId)->shuma; }
        }
    }
    if(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_9)->count() > 0){
        foreach(waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_9)->get() as $oneD9PPSum){
            if(Orders::find($oneD9PPSum->actId) != NULL){ $D9PPSum = $D9PPSum + (float)Orders::find($oneD9PPSum->actId)->shuma; }
        }
    }

?>
    <input type="hidden" id="Day0" value="{{explode('-',$nowDate)[2]}}.{{explode('-',$nowDate)[1]}}">
    <input type="hidden" id="Day1" value="{{explode('-',$Day_1)[2]}}.{{explode('-',$Day_1)[1]}}">
    <input type="hidden" id="Day2" value="{{explode('-',$Day_2)[2]}}.{{explode('-',$Day_2)[1]}}">
    <input type="hidden" id="Day3" value="{{explode('-',$Day_3)[2]}}.{{explode('-',$Day_3)[1]}}">
    <input type="hidden" id="Day4" value="{{explode('-',$Day_4)[2]}}.{{explode('-',$Day_4)[1]}}">
    <input type="hidden" id="Day5" value="{{explode('-',$Day_5)[2]}}.{{explode('-',$Day_5)[1]}}">
    <input type="hidden" id="Day6" value="{{explode('-',$Day_6)[2]}}.{{explode('-',$Day_6)[1]}}">
    <input type="hidden" id="Day7" value="{{explode('-',$Day_7)[2]}}.{{explode('-',$Day_7)[1]}}">
    <input type="hidden" id="Day8" value="{{explode('-',$Day_8)[2]}}.{{explode('-',$Day_8)[1]}}">
    <input type="hidden" id="Day9" value="{{explode('-',$Day_9)[2]}}.{{explode('-',$Day_9)[1]}}">

<script>
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        var Day0 = $('#Day0').val();
        var Day1 = $('#Day1').val();
        var Day2 = $('#Day2').val();
        var Day3 = $('#Day3').val();
        var Day4 = $('#Day4').val();
        var Day5 = $('#Day5').val();
        var Day6 = $('#Day6').val();
        var Day7 = $('#Day7').val();
        var Day8 = $('#Day8').val();
        var Day9 = $('#Day9').val();

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










        var ctxAddProdNr = document.getElementById("chartAddProdNr");
        var D0APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$nowDate)->count()}}";
        var D1APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_1)->count()}}";
        var D2APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_2)->count()}}";
        var D3APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_3)->count()}}";
        var D4APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_4)->count()}}";
        var D5APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_5)->count()}}";
        var D6APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_6)->count()}}";
        var D7APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_7)->count()}}";
        var D8APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_8)->count()}}";
        var D9APNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at',$Day_9)->count()}}";

        var liChAddProdNr = new Chart(ctxAddProdNr, {
            type: 'line',
            data: {
                labels: [Day9, Day8, Day7, Day6, Day5, Day4, Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(78, 115, 223, 1)", pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)", pointBorderColor: "rgba(78, 115, 223, 1)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)", pointHoverBorderColor: "rgba(78, 115, 223, 1)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D9APNr, D8APNr, D7APNr, D6APNr, D5APNr, D4APNr, D3APNr, D2APNr, D1APNr, D0APNr],
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
                        return datasetLabel + ': '+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });









        

        var ctxPayProdNr = document.getElementById("chartPayProdNr");
        var D0PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$nowDate)->count()}}";
        var D1PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_1)->count()}}";
        var D2PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_2)->count()}}";
        var D3PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_3)->count()}}";
        var D4PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_4)->count()}}";
        var D5PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_5)->count()}}";
        var D6PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_6)->count()}}";
        var D7PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_7)->count()}}";
        var D8PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_8)->count()}}";
        var D9PPNr = "{{waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->whereDate('created_at',$Day_9)->count()}}";

        var liChPayProdNr = new Chart(ctxPayProdNr, {
            type: 'line',
            data: {
                labels: [Day9, Day8, Day7, Day6, Day5, Day4, Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(78, 115, 223, 1)", pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)", pointBorderColor: "rgba(78, 115, 223, 1)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)", pointHoverBorderColor: "rgba(78, 115, 223, 1)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D9PPNr, D8PPNr, D7PPNr, D6PPNr, D5PPNr, D4PPNr, D3PPNr, D2PPNr, D1PPNr, D0PPNr],
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
                        return datasetLabel + ': '+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });








        

        

        var ctxAddProdSum = document.getElementById("chartAddProdSum");
        var D0APSu = "{{$D0APSum}}";
        var D1APSu = "{{$D1APSum}}";
        var D2APSu = "{{$D2APSum}}";
        var D3APSu = "{{$D3APSum}}";
        var D4APSu = "{{$D4APSum}}";
        var D5APSu = "{{$D5APSum}}";
        var D6APSu = "{{$D6APSum}}";
        var D7APSu = "{{$D7APSum}}";
        var D8APSu = "{{$D8APSum}}";
        var D9APSu = "{{$D9APSum}}";

        var liChAddProdSum = new Chart(ctxAddProdSum, {
            type: 'line',
            data: {
                labels: [Day9, Day8, Day7, Day6, Day5, Day4, Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(78, 115, 223, 1)", pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)", pointBorderColor: "rgba(78, 115, 223, 1)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)", pointHoverBorderColor: "rgba(78, 115, 223, 1)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D9APSu, D8APSu, D7APSu, D6APSu, D5APSu, D4APSu, D3APSu, D2APSu, D1APSu, D0APSu],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return  'CHF '+number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': CHF '+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });


        var ctxPayProdSum = document.getElementById("chartPayProdSum");
        var D0PPSu = "{{$D0PPSum}}";
        var D1PPSu = "{{$D1PPSum}}";
        var D2PPSu = "{{$D2PPSum}}";
        var D3PPSu = "{{$D3PPSum}}";
        var D4PPSu = "{{$D4PPSum}}";
        var D5PPSu = "{{$D5PPSum}}";
        var D6PPSu = "{{$D6PPSum}}";
        var D7PPSu = "{{$D7PPSum}}";
        var D8PPSu = "{{$D8PPSum}}";
        var D9PPSu = "{{$D9PPSum}}";

        var liChPayProdSum = new Chart(ctxPayProdSum, {
            type: 'line',
            data: {
                labels: [Day9, Day8, Day7, Day6, Day5, Day4, Day3, Day2, Day1, Day0],
                datasets: [{
                    label: '',
                    lineTension: 0.3, backgroundColor: "rgba(78, 115, 223, 0.05)", borderColor: "rgba(78, 115, 223, 1)", pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)", pointBorderColor: "rgba(78, 115, 223, 1)", pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)", pointHoverBorderColor: "rgba(78, 115, 223, 1)", pointHitRadius: 10, pointBorderWidth: 2,
                    data: [D9PPSu, D8PPSu, D7PPSu, D6PPSu, D5PPSu, D4PPSu, D3PPSu, D2PPSu, D1PPSu, D0PPSu],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {padding: {left: 10, right: 25, top: 25, bottom: 25}},
                scales: {
                    xAxes: [{time: {unit: 'text'}, gridLines: {display: false, drawBorder: false}, ticks: { maxTicksLimit: 10 }}],
                    yAxes: [{
                        ticks: { maxTicksLimit: 4, padding: 10, callback: function(value, index, values) { return  'CHF '+number_format(value);} },
                        gridLines: {color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2]}
                    }],
                },
                legend: {display: false},
                tooltips: {backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14,borderColor: '#dddfeb',
                    borderWidth: 1,xPadding: 15,yPadding: 15,displayColors: false,intersect: false,mode: 'index',caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': CHF '+ number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });

      
</script>