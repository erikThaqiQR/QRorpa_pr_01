<?php

use Illuminate\Support\Facades\Auth;
    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);

?>

    <div class="card mt-2" style="width: 100%; display:none;" id="VerkaufsstatistikenPH22">
        <div class="card-header">
            <strong>Wählen Sie einen Monat aus</strong>
        </div>

        <div style="width: 100%;" class="d-flex flex-wrap justify-content-start p-1">
            <?php  
                $dateSW = Carbon::now()->startOfWeek();
                $dateEW = Carbon::now()->startOfWeek();
                $dateEW->addDays(6);
                
                $monthCount = Carbon::now()->month;
                $yearCount = Carbon::now()->year;

                $resCreated =explode(' ',Restorant::find(Auth::user()->sFor)->created_at)[0];
            
            ?>


            <p style="width:100%; font-size:19px; margin-bottom:2px; margin-top:10px;"><strong>{{$yearCount}}</strong></p>

            @while(true)
                @if($dateSW >= $resCreated)
                    <?php
                         $dateSW2D =explode('-',explode(' ',$dateSW)[0]);
                         $dateEW2D =explode('-',explode(' ',$dateEW)[0]);

                         $showWStart = $dateSW2D[2].'.'.$dateSW2D[1].'.'.$dateSW2D[0];
                         $sendWStart = $dateSW2D[0].'-'.$dateSW2D[1].'-'.$dateSW2D[2];

                         $showWEnd = $dateEW2D[2].'.'.$dateEW2D[1].'.'.$dateEW2D[0];
                         $sendWEnd = $dateEW2D[0].'-'.$dateEW2D[1].'-'.$dateEW2D[2];
                    ?>

                    @if($yearCount > $dateSW2D[0])
                        <p style="width:100%; font-size:19px; margin-bottom:2px; margin-top:10px; border-top:1px solid rgb(72,81,87);"><strong>{{--$yearCount}}</strong></p>
                    @endif

                    <div class="btn btn-outline-dark mb-1 d-flex" onclick="selectPH22('{{$sendWStart}}','{{$sendWEnd}}','{{Auth::user()->sFor}}','{{$dateSW->weekOfYear}}','{{$dateSW2D[0]}}')" 
                    style="width:100%; padding:2px;">
                        <p style="width:18%; margin:0px; border-right:1px solid black" class="text-center">w:{{$dateSW->weekOfYear}}</p>
                        <p style="width:82%; margin:0px;"><strong>{{$showWStart}} > {{$showWEnd}}</strong></p>
                    </div>

                    <?php
                        $dateSW->subDays(7);
                        $dateEW->subDays(7);
                    ?>
                @else
                    @break
                @endif
            @endwhile
        </div>
    </div>










    <script>
        function selectPH22(weekS, weekE, resto, weekNr, weekYear){
            $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','100');
            $('#VerkaufsstatistikberichtLoading').attr('style','width: 100%');
            $('#VerkaufsstatistikberichtLoading').html('100%');
            $('#VerkaufsstatistikberichtLoading').addClass('bg-success').removeClass('bg-primary');

            $('#VerkaufsstatistikenPH22WeekS').val(weekS);
            $('#VerkaufsstatistikenPH22WeekE').val(weekE);
            $('#VerkaufsstatistikenPH22WeekNr').val(weekNr);
            $('#VerkaufsstatistikenPH22WeekYr').val(weekYear);
            // $('#selectPH21'+ph21Var).addClass('btn-dark').removeClass('btn-outline-dark');

            $('#VerkaufsstatistikenPH1').hide(200);
            $('#VerkaufsstatistikenPH2').hide(200);
            $('#VerkaufsstatistikenPH21').hide(200);
            $('#VerkaufsstatistikenPH22').hide(200);
            $('#VerkaufsstatistikenPH23').hide(200);
            $('#VerkaufsstatistikenPH24').hide(200);

            $('#VerkaufsstatistikenPH3').show(200);

            if($('#VerkaufsstatistikenPH1Val').val() == 1){
                $('#selectedDataVerkauf11').show(5);
            }else if($('#VerkaufsstatistikenPH1Val').val() == 2){
                $('#selectedDataVerkauf12').show(5);
            }else if($('#VerkaufsstatistikenPH1Val').val() == 3){
                $('#selectedDataVerkauf13').show(5);
            }else if($('#VerkaufsstatistikenPH1Val').val() == 4){
                $('#selectedDataVerkauf14').show(5);
            }

            if($('#VerkaufsstatistikenPH2Val').val() == 1){
                $('#selectedDataVerkauf21').show(5);
            }else if($('#VerkaufsstatistikenPH2Val').val() == 2){
                $('#selectedDataVerkauf22').show(5);
            }else if($('#VerkaufsstatistikenPH2Val').val() == 3){
                $('#selectedDataVerkauf23').show(5);
            }else{
                $('#selectedDataVerkauf24').show(5);
            }

            $('#selectedDataVerkauf31').show(5);

            $('#selectedDataVerkauf42').show(5);

            // gen PDF Day 
            $('#daySelectedPDFDay').val($('#VerkaufsstatistikenPH21Val').val());

            $('#selectedDataVerkauf31').html('Woche :'+weekNr+' of '+weekYear);

            
            $('#exportToExcelSales').hide(10);
            $('#exportToPDFSales').hide(10);
            $('#exportToPDFSalesMonth').hide(10);
            $('#exportToPDFSalesMonthAllSel').hide(10);
            $('#exportToPDFSalesYear').hide(10);

            $('.weekSelectedPDFWeekS').val(weekS);
            $('.weekSelectedPDFWeekE').val(weekE);
            $('.weekSelectedPDFWeekNr').val(weekNr);
            $('.weekSelectedPDFWeekYr').val(weekYear);

            $('#exportToPDFSalesWeek').show(5);

            if(!$('#VerkaufsstatistikenPH1O6Val').val()){
                // Lista per gjenerim te raportit me restorantin origjinal

                $.ajax({
                    url: '{{ route("dash.salesStatisticsProds2") }}',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        serviceN: $('#VerkaufsstatistikenPH1Val').val(),
                        weekS: weekS,
                        weekE: weekE,
                        res: resto,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {

                        var listings =  '<tr >'+
                                            '<td colspan="3" style="text-align:center; font-size:1.7rem;"><strong>'+$("#theResName").val()+'</strong></td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<th>Produkt-ID</th>'+
                                            '<th>Produkt</th>'+
                                            '<th>{{__("adminP.crowd")}}</th>'+
                                        '</tr>';

                        $('#VerkaufsstatistikenPH4').show(250);
                        if(Object.keys(res).length > 0){
                            var prods = [];
                            var allSasiaN = parseInt(0);
                            var allQmimiN = parseFloat(0.00);
                            $.each(res, function(index, value){
                                var orders = value.porosia;
                                var orders2D = orders.split('---8---');
                                $.each(orders2D, function(index2, value2){
                                    
                                    var orders3D = value2.split('-8-');

                                    allSasiaN += parseInt(orders3D[3]);
                                    if($('#VerkaufsstatistikenPH1Val').val() == 2){
                                        allQmimiN += parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3]));
                                    }else{
                                        allQmimiN += parseFloat(orders3D[4]);
                                    }

                                    if( $.inArray(orders3D[7], prods) !== -1 ){
                                        var sasNow = $('#prodShowSasia'+orders3D[7]).html();
                                        var sasNew = parseInt(parseInt(sasNow)+parseInt(orders3D[3]));
                                        $('#prodShowSasia'+orders3D[7]).html(sasNew);

                                        var qmiNow = $('#prodShowQmimi'+orders3D[7]).html();
                                        if($('#VerkaufsstatistikenPH1Val').val() == 2){
                                            var qmiNew = parseFloat(parseFloat(qmiNow)+parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3])));
                                        }else{
                                            var qmiNew = parseFloat(parseFloat(qmiNow)+parseFloat(orders3D[4]));
                                        }
                                    
                                        $('#prodShowQmimi'+orders3D[7]).html(parseFloat(qmiNew).toFixed(2));


                                    }else{
                                        // not found
                                        prods.push(orders3D[7]);
                                        if($('#VerkaufsstatistikenPH1Val').val() == 2){
                                            var listings =  '<tr class="prodShowList" id="prodShow'+orders3D[7]+'">'+
                                                                '<td class="prodShowListTD0"># '+orders3D[7]+'</td>'+
                                                                // '<td class="prodShowListTDRes">'+value.Restaurant+'</td>'+
                                                                '<td class="prodShowListTD1">'+orders3D[0]+'</td>'+
                                                                '<td class="prodShowListTD2" id="prodShowSasia'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                                // '<td class="prodShowListTD3" id="prodShowQmimi'+orders3D[7]+'">'+parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3])).toFixed(2)+'</td>'+
                                                            '</tr>';
                                        }else{
                                            var listings =  '<tr class="prodShowList" id="prodShow'+orders3D[7]+'">'+
                                                                '<td class="prodShowListTD0"># '+orders3D[7]+'</td>'+
                                                                // '<td class="prodShowListTDRes">'+value.Restaurant+'</td>'+
                                                                '<td class="prodShowListTD1">'+orders3D[0]+'</td>'+
                                                                '<td class="prodShowListTD2" id="prodShowSasia'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                                // '<td class="prodShowListTD3" id="prodShowQmimi'+orders3D[7]+'">'+parseFloat(orders3D[4]).toFixed(2)+'</td>'+
                                                            '</tr>';
                                        }
                                        $('#VerkaufsstatistikenPH4Table').append(listings);
                                    }

                                });// end foreach orders2D
                            });// end foreach res
                            var listings =  '<tr>'+
                                                '<td style="font-weight:bold;" ></td>'+
                                                // '<td class="prodShowListTDRes"></td>'+
                                                '<td style="font-weight:bold;" >Summe</td>'+
                                                '<td style="font-weight:bold;" >'+allSasiaN+'</td>'+
                                                // '<td style="font-weight:bold;" >'+parseFloat(allQmimiN).toFixed(2)+'</td>'+
                                            '</tr>';
                            $('#VerkaufsstatistikenPH4Table').append(listings);

                            $('#exportToPDFSalesWeekBtn').removeAttr('disabled');
                        }else{
                            var listings =  '<tr >'+
                                                '<td colspan="5" style="color:red">Für dieses Datum liegen keine registrierten Bestellungen vor</td>'+
                                            '</tr>';
                            $('#VerkaufsstatistikenPH4Table').append(listings);
                            $('#exportToExcelSales').hide(5);

                            $('#exportToPDFSalesWeekBtn').attr('disabled','disabled');
                        }
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }else{

                




                // Lista per gjenerim te raportit me restorantet e selektuara
                $('#exportToPDFSalesWeekBtn').removeAttr('disabled');

                var resSelExAc = $('#resSelExAcRaportV1').val();

                var resSelName = $('#resSelNamesExAcRaportV1').val();
                var resSelName2D = resSelName.split('-m-m-m-');
                $.each(resSelExAc.split('-m-m-m-'), function( indexResSel, thisResId ) {
                    $.ajax({
                        url: '{{ route("dash.salesStatisticsProds2") }}',
                        method: 'post',
                        dataType: 'json',
                        data: {
                            serviceN: $('#VerkaufsstatistikenPH1Val').val(),
                            weekS: weekS,
                            weekE: weekE,
                            res: thisResId,
                            _token: '{{csrf_token()}}'
                        },
                        success: (res) => {
                             
                            var listings =  '<tr >'+
                                                '<td colspan="3" style="text-align:center; font-size:1.7rem;"><strong>'+resSelName2D[indexResSel]+'</strong></td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<th>Produkt-ID</th>'+
                                                '<th>Produkt</th>'+
                                                '<th>{{__("adminP.crowd")}}</th>'+
                                            '</tr>';
                                        
                            $('#VerkaufsstatistikenPH4Table').append(listings);

                            $('#VerkaufsstatistikenPH4').show(250);
                            if(Object.keys(res).length > 0){
                                var prods = [];
                                var allSasiaN = parseInt(0);
                                var allQmimiN = parseFloat(0.00);
                                $.each(res, function(index, value){
                                    var orders = value.porosia;
                                    var orders2D = orders.split('---8---');
                                    $.each(orders2D, function(index2, value2){
                                        
                                        var orders3D = value2.split('-8-');

                                        allSasiaN += parseInt(orders3D[3]);
                                        if($('#VerkaufsstatistikenPH1Val').val() == 2){ allQmimiN += parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3]));
                                        }else{ allQmimiN += parseFloat(orders3D[4]); }

                                        if( $.inArray(orders3D[7], prods) !== -1 ){
                                            // add to existing tr
                                            var sasNow = $('#prodShowSasia'+thisResId+'Pro'+orders3D[7]).html();
                                            var sasNew = parseInt(parseInt(sasNow)+parseInt(orders3D[3]));
                                            $('#prodShowSasia'+thisResId+'Pro'+orders3D[7]).html(sasNew);

                                            var qmiNow = $('#prodShowQmimi'+thisResId+'Pro'+orders3D[7]).html();
                                            if($('#VerkaufsstatistikenPH1Val').val() == 2){
                                                var qmiNew = parseFloat(parseFloat(qmiNow)+parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3])));
                                            }else{
                                                var qmiNew = parseFloat(parseFloat(qmiNow)+parseFloat(orders3D[4]));
                                            }
                                        
                                            $('#prodShowQmimi'+thisResId+'Pro'+orders3D[7]).html(parseFloat(qmiNew).toFixed(2));


                                        }else{
                                            // not found, add new
                                            prods.push(orders3D[7]);
                                            if($('#VerkaufsstatistikenPH1Val').val() == 2){
                                                var listings =  '<tr class="prodShowList" id="prodShow'+thisResId+'Pro'+orders3D[7]+'">'+
                                                                    '<td class="prodShowListTD0"># '+orders3D[7]+'</td>'+
                                                                    // '<td class="prodShowListTDRes">'+value.Restaurant+'</td>'+
                                                                    '<td class="prodShowListTD1">'+orders3D[0]+'</td>'+
                                                                    '<td class="prodShowListTD2" id="prodShowSasia'+thisResId+'Pro'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                                    // '<td class="prodShowListTD3" id="prodShowQmimi'+thisResId+'Pro'+orders3D[7]+'">'+parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3])).toFixed(2)+'</td>'+
                                                                '</tr>';
                                            }else{
                                                var listings =  '<tr class="prodShowList" id="prodShow'+thisResId+'Pro'+orders3D[7]+'">'+
                                                                    '<td class="prodShowListTD0"># '+orders3D[7]+'</td>'+
                                                                    // '<td class="prodShowListTDRes">'+value.Restaurant+'</td>'+
                                                                    '<td class="prodShowListTD1">'+orders3D[0]+'</td>'+
                                                                    '<td class="prodShowListTD2" id="prodShowSasia'+thisResId+'Pro'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                                    // '<td class="prodShowListTD3" id="prodShowQmimi'+thisResId+'Pro'+orders3D[7]+'">'+parseFloat(orders3D[4]).toFixed(2)+'</td>'+
                                                                '</tr>';
                                            }
                                            $('#VerkaufsstatistikenPH4Table').append(listings);
                                        }

                                    });// end foreach orders2D
                                });// end foreach res
                                var listings =  '<tr>'+
                                                    '<td style="font-weight:bold;" ></td>'+
                                                    // '<td class="prodShowListTDRes"></td>'+
                                                    '<td style="font-weight:bold;" >Summe</td>'+
                                                    '<td style="font-weight:bold;" >'+allSasiaN+'</td>'+
                                                    // '<td style="font-weight:bold;" >'+parseFloat(allQmimiN).toFixed(2)+'</td>'+
                                                '</tr>';
                                $('#VerkaufsstatistikenPH4Table').append(listings);
                            }else{
                                var listings =  '<tr >'+
                                                    '<td colspan="3" style="color:red">Für dieses Datum liegen keine registrierten Bestellungen vor</td>'+
                                                '</tr>';
                                $('#VerkaufsstatistikenPH4Table').append(listings);
                                $('#exportToExcelSales').hide(5);
                            }
                        },
                        error: (error) => {
                            console.log(error);
                            alert($('#pleaseUpdateAndTryAgain').val());
                        }
                    });
                });

            }
        }
    </script>