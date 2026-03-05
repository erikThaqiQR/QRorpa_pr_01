<?php

use Illuminate\Support\Facades\Auth;
    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);

    function getMonthN($theMff,$theYff){
        switch($theMff){
            case 1: return  "Januar.".$theYff.""; break;
            case 2: return  "Februar.".$theYff.""; break;
            case 3: return  "März.".$theYff.""; break;
            case 4: return  "April.".$theYff.""; break;
            case 5: return "Mai.".$theYff.""; break;
            case 6: return "Juni.".$theYff.""; break;
            case 7: return "Juli.".$theYff.""; break;
            case 8: return "August.".$theYff.""; break;
            case 9: return "September.".$theYff.""; break;
            case 10: return "Oktober.".$theYff.""; break;
            case 11: return "November.".$theYff.""; break;
            case 12: return "Dezember.".$theYff.""; break;   
        }
    }
?>

    <div class="card mt-2" style="width: 100%; display:none;" id="VerkaufsstatistikenPH23">
        <div class="card-header">
            <strong>Wählen Sie einen Monat aus</strong>
        </div>

        <div style="width: 100%;" class="d-flex flex-wrap justify-content-start p-1">
            <?php  
                $month = Carbon::now(); 

                $monthCount = Carbon::now()->month;
                $yearCount = Carbon::now()->year;

                $resCreated =explode(' ',Restorant::find(Auth::user()->sFor)->created_at)[0];
                $resCreatedM = explode('-', $resCreated)[1];
                $resCreatedY = explode('-', $resCreated)[0];
            ?>

            <p style="width:100%; font-size:17px;"><strong>{{$yearCount}}</strong></p>
            @while(true)
                @if(($monthCount >= $resCreatedM && $yearCount == $resCreatedY) || $yearCount > $resCreatedY )

                    <button class="btn btn-outline-dark mb-1" onclick="selectPH23('{{$monthCount}}','{{$yearCount}}','{{Auth::user()->sFor}}')" style="width:24.5%; margin-right:0.5%;">
                        <strong> <?php echo getMonthN($monthCount,$yearCount); ?> </strong>
                    </button>

                    <!-- Pjesa per vitin  -->
                    @if($monthCount == 1)
                        <?php $yearCount--; $monthCount=12; ?>
                        <hr>
                        <p style="width:100%;font-size:17px;"><strong>{{$yearCount}}</strong></p>
                    @else
                        <?php $monthCount--; ?>
                    @endif
                @else
                   @break;
               @endif 
            @endwhile
        </div>
    </div>














    <script>
        function selectPH23(ph23Month, ph23Year, resto){
            $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','100');
            $('#VerkaufsstatistikberichtLoading').attr('style','width: 100%');
            $('#VerkaufsstatistikberichtLoading').html('100%');
            $('#VerkaufsstatistikberichtLoading').addClass('bg-success').removeClass('bg-primary');

            $('#VerkaufsstatistikenPH23Month').val(ph23Month);
            $('#VerkaufsstatistikenPH23Year').val(ph23Year);
            // $('#selectPH21'+ph21Var).addClass('btn-dark').removeClass('btn-outline-dark');

            $('#VerkaufsstatistikenPH1').hide(200);
            $('#VerkaufsstatistikenPH1_5').hide(200);
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
            }else if($('#VerkaufsstatistikenPH1Val').val() == 5){
                $('#selectedDataVerkauf15').show(5);
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
            $('#selectedDataVerkauf43').show(5);

            // gen PDF Day 
            $('#daySelectedPDFDay').val($('#VerkaufsstatistikenPH21Val').val());

            $('#selectedDataVerkauf31').html(ph23Month+' . '+ph23Year);
            
            $('#exportToExcelSales').hide(100);
            $('#exportToPDFSales').hide(100);
            $('#exportToPDFSalesWeek').hide(100);
            $('#exportToPDFSalesYear').hide(100);

            $('.monthSelectedPDFMonth').val(ph23Month);
            $('#monthSelectedPDFMonthSelective').val(ph23Month);
            $('#monthSelectedEXCMonthSelective').val(ph23Month);
            $('.monthSelectedPDFYear').val(ph23Year);
            $('#monthSelectedPDFYearSelective').val(ph23Year);
            $('#monthSelectedEXCYearSelective').val(ph23Year);

            if($('#VerkaufsstatistikenPH1Val').val() == 5){ 
                var theResta = $('#VerkaufsstatistikenPH1O5Val').val();
                $('#exportToPDFSalesMonth').hide(100);
                $('#exportToPDFSalesMonthAllSel').show(100);
                $('#exportToEXCSalesMonthAllSel').show(100);
            }else{ 
                var theResta = resto; 
                $('#exportToPDFSalesMonthAllSel').hide(100);
                $('#exportToEXCSalesMonthAllSel').hide(100);
                $('#exportToPDFSalesMonth').show(100);
            }

            if(!$('#VerkaufsstatistikenPH1O6Val').val()){
                // Lista per gjenerim te raportit me restorantin origjinal

                $.ajax({
                    url: '{{ route("dash.salesStatisticsProds3") }}',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        serviceN: $('#VerkaufsstatistikenPH1Val').val(),
                        dateMonth: ph23Month,
                        dateYear: ph23Year,
                        res: theResta,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {

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
                                                                '<td colspan="2" class="prodShowListTD1">'+orders3D[0]+'</td>'+
                                                                '<td class="prodShowListTD2" id="prodShowSasia'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                                // '<td class="prodShowListTD3" id="prodShowQmimi'+orders3D[7]+'">'+parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3])).toFixed(2)+'</td>'+
                                                            '</tr>';
                                        }else{
                                            var listings =  '<tr class="prodShowList" id="prodShow'+orders3D[7]+'">'+
                                                                '<td class="prodShowListTD0"># '+orders3D[7]+'</td>'+
                                                                // '<td class="prodShowListTDRes">'+value.Restaurant+'</td>'+
                                                                '<td colspan="2" class="prodShowListTD1">'+orders3D[0]+'</td>'+
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
                                                '<td colspan="2" style="font-weight:bold;" >Summe</td>'+
                                                '<td style="font-weight:bold;" >'+allSasiaN+'</td>'+
                                                // '<td style="font-weight:bold;" >'+parseFloat(allQmimiN).toFixed(2)+'</td>'+
                                            '</tr>';
                            $('#VerkaufsstatistikenPH4Table').append(listings);

                            $('#exportToPDFSalesMonthBtn').removeAttr('disabled');
                        }else{
                            var listings =  '<tr >'+
                                                '<td colspan="4" style="color:red">Für dieses Datum liegen keine registrierten Bestellungen vor</td>'+
                                            '</tr>';
                            $('#VerkaufsstatistikenPH4Table').append(listings);
                            $('#exportToExcelSales').hide(5);

                            $('#exportToPDFSalesMonthBtn').attr('disabled','disabled');
                        }
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }else{











                // Lista per gjenerim te raportit me restorantet e selektuara
                $('#exportToPDFSalesMonthBtn').removeAttr('disabled');

                var resSelExAc = $('#resSelExAcRaportV1').val();

                var resSelName = $('#resSelNamesExAcRaportV1').val();
                var resSelName2D = resSelName.split('-m-m-m-');
                $.each(resSelExAc.split('-m-m-m-'), function( indexResSel, thisResId ) {
                    $.ajax({
                        url: '{{ route("dash.salesStatisticsProds3") }}',
                        method: 'post',
                        dataType: 'json',
                        data: {
                            serviceN: $('#VerkaufsstatistikenPH1Val').val(),
                            dateMonth: ph23Month,
                            dateYear: ph23Year,
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
                                                                    '<td colspan="2" class="prodShowListTD1">'+orders3D[0]+'</td>'+
                                                                    '<td class="prodShowListTD2" id="prodShowSasia'+thisResId+'Pro'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                                    // '<td class="prodShowListTD3" id="prodShowQmimi'+thisResId+'Pro'+orders3D[7]+'">'+parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3])).toFixed(2)+'</td>'+
                                                                '</tr>';
                                            }else{
                                                var listings =  '<tr class="prodShowList" id="prodShow'+thisResId+'Pro'+orders3D[7]+'">'+
                                                                    '<td class="prodShowListTD0"># '+orders3D[7]+'</td>'+
                                                                    // '<td class="prodShowListTDRes">'+value.Restaurant+'</td>'+
                                                                    '<td colspan="2" class="prodShowListTD1">'+orders3D[0]+'</td>'+
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
                                                    '<td colspan="2" style="font-weight:bold;" >Summe</td>'+
                                                    '<td style="font-weight:bold;" >'+allSasiaN+'</td>'+
                                                    // '<td style="font-weight:bold;" >'+parseFloat(allQmimiN).toFixed(2)+'</td>'+
                                                '</tr>';
                                $('#VerkaufsstatistikenPH4Table').append(listings);
                            }else{
                                var listings =  '<tr >'+
                                                    '<td colspan="4" style="color:red">Für dieses Datum liegen keine registrierten Bestellungen vor</td>'+
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