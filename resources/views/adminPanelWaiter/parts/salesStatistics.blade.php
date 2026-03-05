<?php

    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
?>
<style>
    #exportToExcelSales:hover{
        cursor: pointer;
    }
</style>
<input type="hidden" value="{{Auth::user()->sFor}}" id="theResId">

<input type="hidden" id="VerkaufsstatistikenPH1Val">
<input type="hidden" id="VerkaufsstatistikenPH2Val">
<input type="hidden" id="VerkaufsstatistikenPH21Val">

<div class="p-3 pb-5">
    <div class="d-flex">
        <a href="{{route('admWoMng.ordersStatisticsWaiter01')}}" class="pl-4" style="width: 10%; color:rgb(39,190,175);"><strong><i class="fas fa-2x fa-chevron-left"></i></strong></a>
        <h2 style="width: 90%; color:rgb(39,190,175);"><strong>{{__('adminP.salesStatistics')}}</strong></h2>
    </div>
    <hr>
    <p style="margin-bottom:-4px;"><strong>{{__('adminP.salesStatisticsReport')}}</strong></p>
    <div class="progress">
        <div id="VerkaufsstatistikberichtLoading" class="progress-bar bg-danger" role="progressbar" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>

    <br>

    @include('adminPanelWaiter.salesStatistics.desktopph1')

    @include('adminPanelWaiter.salesStatistics.desktopph2')

    @include('adminPanelWaiter.salesStatistics.desktopph21')

    @include('adminPanelWaiter.salesStatistics.desktopph3')


    <div class="card" style="width: 100%; display:none;" id="VerkaufsstatistikenPH4">
        <div class="card-header">
            <strong>{{__('adminP.selectedDates')}}</strong>
        </div>
        <table style="width:60%; margin-left:20%;" id="VerkaufsstatistikenPH4Table2" class=" table table-hover">
            <thead>
                <tr>
                    <th id="exportToExcelSales" colspan="3" class="text-center text-primary">
                        {{Form::open(['action' => 'AdminPanelController@downloadExcel', 'method' => 'get', 'id' => 'downloadExcelForm']) }}
                            <button type="button" class="btn btn-success btn-block" onclick="exportToExcelSales()"><i class="far fa-file-excel"></i> {{__('adminP.exportDataToExcel')}}</button>
                            <input type="hidden" name="productsToExport" id="productsToExport">
                        {{Form::close() }}
                    </th>
                </tr>
                <tr>
                    <th>{{__('adminP.product')}}</th>
                    <th>{{__('adminP.crowd')}}</th>
                    <!-- <th>{{__('adminP.priceCHF')}}</th> -->
                </tr>
            </thead>
            <tbody id="VerkaufsstatistikenPH4Table">
            </tbody>
        </table>
    </div>





 

    <script>
        function selectPH1(ph1Var){
            $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','33');
            $('#VerkaufsstatistikberichtLoading').attr('style','width: 33%');
            $('#VerkaufsstatistikberichtLoading').html('33%');
            $('#VerkaufsstatistikberichtLoading').addClass('bg-primary').removeClass('bg-danger');

            $('#VerkaufsstatistikenPH1Val').val(ph1Var);
            $('#selectPH1'+ph1Var).addClass('btn-dark').removeClass('btn-outline-dark');

            $('#selectPH11').attr('disabled','disabled'); //Disable Restaurant
            $('#selectPH12').attr('disabled','disabled'); //Disable Takeaway
            $('#selectPH13').attr('disabled','disabled'); //Disable Delivery

            $('#VerkaufsstatistikenPH2').show(200);
        }

        function selectPH2(ph2Var){
            $('#VerkaufsstatistikberichtLoading').attr('aria-valuenow','66');
            $('#VerkaufsstatistikberichtLoading').attr('style','width: 66%');
            $('#VerkaufsstatistikberichtLoading').html('66%');

            $('#VerkaufsstatistikenPH2Val').val(ph2Var);
            $('#selectPH2'+ph2Var).addClass('btn-dark').removeClass('btn-outline-dark');

            $('#selectPH21').attr('disabled','disabled'); //Disable day
            $('#selectPH22').attr('disabled','disabled'); //Disable week
            $('#selectPH23').attr('disabled','disabled'); //Disable month
            $('#selectPH24').attr('disabled','disabled'); //Disable year

            $('#VerkaufsstatistikenPH21').show(200);
        }


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



            $('#VerkaufsstatistikenPH3').show(200);

            if($('#VerkaufsstatistikenPH1Val').val() == 1){
                $('#selectedDataVerkauf11').show(5);
            }else if($('#VerkaufsstatistikenPH1Val').val() == 2){
                $('#selectedDataVerkauf12').show(5);
            }else{
                $('#selectedDataVerkauf13').show(5);
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

            var date111 = $('#VerkaufsstatistikenPH21Val').val();
            var date112 = date111.split(' ')[0];
            var date113 = date112.split('-');
            $('#selectedDataVerkauf31').html(date113[2]+' / '+date113[1]+' / '+date113[0]);



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
                    $('#VerkaufsstatistikenPH4').show(250);
                    if(Object.keys(res).length > 0){
                        $('#exportToExcelSales').show(5);
                        const prods = [];
                        var allSasiaN = parseInt(0);
                        var allQmimiN = parseFloat(0.00);
                        $.each(res, function(index, value){
                            var orders = value.porosia;
                            var orders2D = orders.split('---8---');
                            $.each(orders2D, function(index2, value2){
                                // Coca Cola-8-Coca Cola-8-empty-8-1-8-6-8-1.5 l-8--8-139
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
                                  
                                    $('#prodShowQmimi'+orders3D[7]).html(qmiNew);


                                }else{
                                    // not found
                                    prods.push(orders3D[7]);
                                    if($('#VerkaufsstatistikenPH1Val').val() == 2){
                                        var listings =  '<tr class="prodShowList" id="prodShow'+orders3D[7]+'">'+
                                                            '<td class="prodShowListTD1">'+orders3D[0]+'</td>'+
                                                            '<td class="prodShowListTD2" id="prodShowSasia'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                            '<td class="prodShowListTD3" id="prodShowQmimi'+orders3D[7]+'">'+parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3]))+'</td>'+
                                                        '</tr>';
                                    }else{
                                        var listings =  '<tr class="prodShowList" id="prodShow'+orders3D[7]+'">'+
                                                            '<td class="prodShowListTD1">'+orders3D[0]+'</td>'+
                                                            '<td class="prodShowListTD2" id="prodShowSasia'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                            '<td class="prodShowListTD3" id="prodShowQmimi'+orders3D[7]+'">'+orders3D[4]+'</td>'+
                                                        '</tr>';
                                    }
                                    $('#VerkaufsstatistikenPH4Table').append(listings);
                                }

                            });// end foreach orders2D
                        });// end foreach res
                        var listings =  '<tr>'+
                                            '<td style="font-weight:bold;" >'+$("#total").val()+'</td>'+
                                            '<td style="font-weight:bold;" >'+allSasiaN+'</td>'+
                                            '<td style="font-weight:bold;" >'+allQmimiN+'</td>'+
                                        '</tr>';
                        $('#VerkaufsstatistikenPH4Table').append(listings);
                    }else{
                        var listings =  '<tr >'+
                                            '<td colspan="3" style="color:red">'+$("#noOrders").val()+'</td>'+
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
        }






















    

    

        function exportToExcelSales(){
            var sendProdsLins = ""; 
            // productsToExport
            $("tr.prodShowList").each(function() {
                var qu1 = $(this).find(".prodShowListTD1").html(),
                    qu2 = $(this).find(".prodShowListTD2").html(),
                    qu3 = $(this).find(".prodShowListTD3").html()
                if(sendProdsLins == ""){
                    sendProdsLins = qu1+'-7-'+qu2+'-7-'+qu3;
                }else{
                    sendProdsLins += '--77--'+qu1+'-7-'+qu2+'-7-'+qu3;
                }    
                
                    // console.log( qu1);
                    // console.log( qu2);
                    // console.log( qu3);
                    // console.log('---------------------------------------');
            });

            $('#productsToExport').val(sendProdsLins);
            $('#downloadExcelForm').submit();
        }



     
    </script>
 























   

</div>