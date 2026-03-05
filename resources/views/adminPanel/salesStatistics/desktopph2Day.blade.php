<?php

    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
?>

    <div class="card mt-2" style="width: 100%; display:none;" id="VerkaufsstatistikenPH21">
        <div class="card-header">
            <strong>{{__('adminP.chooseDate')}}</strong>
        </div>
        <div style="width: 100%;" class="d-flex flex-wrap justify-content-between p-1">
            <?php  
                $month = Carbon::now(); 
                $monthM1 = Carbon::now()->subMonth(); 
                $monthM2 = Carbon::now()->subMonth(2); 
                $monthM3 = Carbon::now()->subMonth(3); 
                //2022-01-08 16:23:26 
            ?>

            <?php
                $monthCount = Carbon::now()->month;
                $yearCount = Carbon::now()->year;

                $resCreated =explode(' ',Restorant::find(Auth::user()->sFor)->created_at)[0];
                $resCreatedM = explode('-', $resCreated)[1];
                $resCreatedY = explode('-', $resCreated)[0];

                // echo ''.$monthCount.' >= '. $resCreatedM.'  '. $yearCount.'>='.$resCreatedY;
            ?>
                @while(true)
                    @if(($monthCount >= $resCreatedM && $yearCount == $resCreatedY) || $yearCount > $resCreatedY )
                        <div style="width: 33%;" class="d-flex flex-wrap justify-content-between p-1">
                            <p style="width:100%; font-size:1.4rem; margin:0px; border-top:1px solid rgb(72,81,87);" class="text-center pt-1"><strong>
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
                                    ?>
                                    @if($i == 1)
                                        <?php
                                            $dayOfWeekNr = date('w', strtotime($dateCheckCreate));
                                            if($dayOfWeekNr == 0){ $dayOfWeekNr = 7; }
                                            $j = 1;
                                        ?>
                                        @while ($j < $dayOfWeekNr)
                                        <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%; color:white;" disabled>.</button>
                                        <?php $j++; ?>
                                        @endwhile
                                     
                                        @if($dateCheckCreate >= $theRes->created_at)
                                            <button class="btn mb-1 btn-outline-dark" onclick="selectPH21('{{$dateCheckCreate}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button class="btn mb-1 btn-outline-dark" style="width:14.1%; margin-right:0.18%; color:white;" disabled>.</button>
                                        @endif
                                    @else
                                        @if($dateCheckCreate >= $theRes->created_at)
                                            <button class="btn mb-1 btn-outline-dark" onclick="selectPH21('{{$dateCheckCreate}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button class="btn mb-1 btn-outline-dark" style="width:14.1%; margin-right:0.18%; color:white;" disabled>.</button>
                                        @endif
                                    @endif
                                @endfor
                                <?php
                                    $addExtra = 42 - $dayOfWeekNr + 1 - $month->daysInMonth;
                                ?>
                                @for($k=1;$k<=$addExtra;$k++)
                                <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%; color:white;" disabled>.</button>
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








    <script>
        function selectPH21(ph21Var){
            $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','100');
            $('#VerkaufsstatistikberichtLoading').attr('style','width: 100%');
            $('#VerkaufsstatistikberichtLoading').html('100%');
            $('#VerkaufsstatistikberichtLoading').addClass('bg-success').removeClass('bg-primary');

            $('#VerkaufsstatistikenPH21Val').val(ph21Var);
            $('#selectPH21'+ph21Var).addClass('btn-dark').removeClass('btn-outline-dark');

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
            $('#selectedDataVerkauf41').show(5);

            if($('#VerkaufsstatistikenPH1Val').val() == 1){
                $('#exportToExcelSales').show(100);
                $('#exportToPDFSales').hide(100);
            }else{
                $('#exportToExcelSales').hide(100);
            }

            $('#exportToPDFSalesWeek').hide(100);
            $('#exportToPDFSalesMonth').hide(100);
            $('#exportToPDFSalesMonthAllSel').hide(100);
            $('#exportToPDFSalesYear').hide(100);

         
            // gen PDF Day 
            $('.daySelectedPDFDay').val(ph21Var);

            var date111 = $('#VerkaufsstatistikenPH21Val').val();
            var date112 = date111.split(' ')[0];
            var date113 = date112.split('-');
            $('#selectedDataVerkauf31').html(date113[2]+' / '+date113[1]+' / '+date113[0]);

            $('#exportToPDFSales').show(5);

            if(!$('#VerkaufsstatistikenPH1O6Val').val()){
                // Lista per gjenerim te raportit me restorantin origjinal
                $.ajax({
                    url: '{{ route("dash.salesStatisticsProds1") }}',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        serviceN: $('#VerkaufsstatistikenPH1Val').val(),
                        dateN: $('#VerkaufsstatistikenPH21Val').val(),
                        res: $('#theResId').val(),
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
                                        // not found, add new
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

                            $('#exportToPDFSalesBtn').removeAttr('disabled');
                        }else{
                            var listings =  '<tr >'+
                                                '<td colspan="3" style="color:red">Für dieses Datum liegen keine registrierten Bestellungen vor</td>'+
                                            '</tr>';
                            $('#VerkaufsstatistikenPH4Table').append(listings);
                            $('#exportToExcelSales').hide(5);

                            // $('#exportToPDFSalesBtn').attr('disabled','disabled');
                        }
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }else{

             



                

                
                // Lista per gjenerim te raportit me restorantet e selektuara
                $('#exportToPDFSalesBtn').removeAttr('disabled');

                var resSelExAc = $('#resSelExAcRaportV1').val();

                var resSelName = $('#resSelNamesExAcRaportV1').val();
                var resSelName2D = resSelName.split('-m-m-m-');
                $.each(resSelExAc.split('-m-m-m-'), function( indexResSel, thisResId ) {
                    $.ajax({
                        url: '{{ route("dash.salesStatisticsProds1") }}',
                        method: 'post',
                        dataType: 'json',
                        data: {
                            serviceN: $('#VerkaufsstatistikenPH1Val').val(),
                            dateN: $('#VerkaufsstatistikenPH21Val').val(),
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