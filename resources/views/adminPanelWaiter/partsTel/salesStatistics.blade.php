<?php

    use App\Restorant;
    use Carbon\Carbon;

    $theResTel = Restorant::find(Auth::user()->sFor);
?>
<style>
    #exportToExcelSales:hover{
        cursor: pointer;
    }
</style>
 
<input type="hidden" value="{{Auth::user()->sFor}}" id="theResIdTel">

<input type="hidden" id="VerkaufsstatistikenPH1ValTel">
<input type="hidden" id="VerkaufsstatistikenPH2ValTel">
<input type="hidden" id="VerkaufsstatistikenPH21ValTel">

<div class="p-3 pb-5">
    <div class="d-flex">
        <a href="{{route('admWoMng.ordersStatisticsWaiter01')}}" class="pl-4" style="width: 15%; color:rgb(39,190,175);"><strong><i class="fas fa-2x fa-chevron-left"></i></strong></a>
        <h2 style="width: 85%; color:rgb(39,190,175);"><strong>{{__('adminP.salesStatistics')}}</strong></h2>
    </div>
    <hr>
    <p style="margin-bottom:-4px;"><strong>{{__('adminP.salesStatisticsReport')}}</strong></p>
    <div class="progress">
        <div id="VerkaufsstatistikberichtLoadingTel" class="progress-bar bg-danger" role="progressbar" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
    <br>

        @include('adminPanelWaiter.salesStatisticsTel.desktopph1')
        @include('adminPanelWaiter.salesStatisticsTel.desktopph2')
        @include('adminPanelWaiter.salesStatisticsTel.desktopph21')
        @include('adminPanelWaiter.salesStatisticsTel.desktopph3')


    <div class="card" style="width: 100%; display:none;" id="VerkaufsstatistikenPH4Tel">
        <div class="card-header">
            <strong>{{__('adminP.selectedDates')}}</strong>
        </div>
        <table style="width:100%;" id="VerkaufsstatistikenPH4Table2Tel" class=" table table-hover">
            <thead>
                <tr>
                    <th id="exportToExcelSalesTel" colspan="3" class="text-center text-primary">
                        {{Form::open(['action' => 'AdminPanelController@downloadExcel', 'method' => 'get', 'id' => 'downloadExcelFormTel']) }}
                            <button type="button" class="btn btn-success btn-block" onclick="exportToExcelSalesTel()"><i class="far fa-file-excel"></i> {{__('adminP.exportDataToExcel')}}</button>
                            <input type="hidden" name="productsToExport" id="productsToExportTel">
                        {{Form::close() }}
                    </th>
                </tr>
                <tr>
                    <th>{{__('adminP.product')}}</th>
                    <th>{{__('adminP.crowd')}}</th>
                    <th>{{__('adminP.priceCHF')}}</th>
                </tr>
            </thead>
            <tbody id="VerkaufsstatistikenPH4TableTel"></tbody>
        </table>
    </div>





 

    <script>
        function selectPH1Tel(ph1Var){
            $('#VerkaufsstatistikberichtLoadingTel').attr('aria-valuenow','33');
            $('#VerkaufsstatistikberichtLoadingTel').attr('style','width: 33%');
            $('#VerkaufsstatistikberichtLoadingTel').html('33%');
            $('#VerkaufsstatistikberichtLoadingTel').addClass('bg-primary').removeClass('bg-danger');

            $('#VerkaufsstatistikenPH1ValTel').val(ph1Var);
            $('#selectPH1'+ph1Var+'Tel').addClass('btn-dark').removeClass('btn-outline-dark');

            $('#selectPH11Tel').attr('disabled','disabled'); //Disable Restaurant
            $('#selectPH12Tel').attr('disabled','disabled'); //Disable Takeaway
            $('#selectPH13Tel').attr('disabled','disabled'); //Disable Delivery

            $('#VerkaufsstatistikenPH2Tel').show(200);
        }









        function selectPH2Tel(ph2Var){
            $('#VerkaufsstatistikberichtLoadingTel').attr('aria-valuenow','66');
            $('#VerkaufsstatistikberichtLoadingTel').attr('style','width: 66%');
            $('#VerkaufsstatistikberichtLoadingTel').html('66%');

            $('#VerkaufsstatistikenPH2ValTel').val(ph2Var);
            $('#selectPH2'+ph2Var+'Tel').addClass('btn-dark').removeClass('btn-outline-dark');

            $('#selectPH21Tel').attr('disabled','disabled'); //Disable day
            $('#selectPH22Tel').attr('disabled','disabled'); //Disable week
            $('#selectPH23Tel').attr('disabled','disabled'); //Disable month
            $('#selectPH24Tel').attr('disabled','disabled'); //Disable year

            $('#VerkaufsstatistikenPH21Tel').show(200);
        }














        function selectPH21Tel(ph21Var){
            $('#VerkaufsstatistikberichtLoadingTel').attr('aria-valuenow','100');
            $('#VerkaufsstatistikberichtLoadingTel').attr('style','width: 100%');
            $('#VerkaufsstatistikberichtLoadingTel').html('100%');
            $('#VerkaufsstatistikberichtLoadingTel').addClass('bg-success').removeClass('bg-primary');

            $('#VerkaufsstatistikenPH21ValTel').val(ph21Var);
            $('#selectPH21'+ph21Var+'Tel').addClass('btn-dark').removeClass('btn-outline-dark');

            $('#VerkaufsstatistikenPH1Tel').hide(200);
            $('#VerkaufsstatistikenPH2Tel').hide(200);
            $('#VerkaufsstatistikenPH21Tel').hide(200);



            $('#VerkaufsstatistikenPH3Tel').show(200);

            if($('#VerkaufsstatistikenPH1ValTel').val() == 1){
                $('#selectedDataVerkauf11Tel').show(5);
            }else if($('#VerkaufsstatistikenPH1ValTel').val() == 2){
                $('#selectedDataVerkauf12Tel').show(5);
            }else{
                $('#selectedDataVerkauf13Tel').show(5);
            }

            if($('#VerkaufsstatistikenPH2ValTel').val() == 1){
                $('#selectedDataVerkauf21Tel').show(5);
            }else if($('#VerkaufsstatistikenPH2ValTel').val() == 2){
                $('#selectedDataVerkauf22Tel').show(5);
            }else if($('#VerkaufsstatistikenPH2ValTel').val() == 3){
                $('#selectedDataVerkauf23Tel').show(5);
            }else{
                $('#selectedDataVerkauf24Tel').show(5);
            }

            $('#selectedDataVerkauf31Tel').show(5);

            $('#selectedDataVerkauf41Tel').show(5);


            var date111 = $('#VerkaufsstatistikenPH21ValTel').val();
            var date112 = date111.split(' ')[0];
            var date113 = date112.split('-');
            $('#selectedDataVerkauf31Tel').html(date113[2]+' / '+date113[1]+' / '+date113[0]);

            $.ajax({
				url: '{{ route("dash.salesStatisticsProds1") }}',
				method: 'post',
                dataType: 'json',
				data: {
					serviceN: $('#VerkaufsstatistikenPH1ValTel').val(),
					dateN: $('#VerkaufsstatistikenPH21ValTel').val(),
					res: $('#theResIdTel').val(),
					_token: '{{csrf_token()}}'
				},
				success: (res) => {
                    $('#VerkaufsstatistikenPH4Tel').show(250);
                    if(Object.keys(res).length > 0){
                        $('#exportToExcelSalesTel').show(5);
                        const prods = [];
                        var allSasiaNTel = parseInt(0);
                        var allQmimiNTel = parseFloat(0.00);
                        $.each(res, function(index, value){
                            var orders = value.porosia;
                            var orders2D = orders.split('---8---');
                            $.each(orders2D, function(index2, value2){
                                // Coca Cola-8-Coca Cola-8-empty-8-1-8-6-8-1.5 l-8--8-139
                                var orders3D = value2.split('-8-');

                                allSasiaNTel += parseInt(orders3D[3]);
                                if($('#VerkaufsstatistikenPH1ValTel').val() == 2){
                                    allQmimiNTel += parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3]));
                                }else{
                                    allQmimiNTel += parseFloat(orders3D[4]);
                                }

                                if( $.inArray(orders3D[7], prods) !== -1 ){
                                    var sasNow = $('#prodShowSasiaTel'+orders3D[7]).html();
                                    var sasNew = parseInt(parseInt(sasNow)+parseInt(orders3D[3]));
                                    $('#prodShowSasiaTel'+orders3D[7]).html(sasNew);

                                    var qmiNow = $('#prodShowQmimiTel'+orders3D[7]).html();
                                    if($('#VerkaufsstatistikenPH1ValTel').val() == 2){
                                        var qmiNew = parseFloat(parseFloat(qmiNow)+parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3])));
                                    }else{
                                        var qmiNew = parseFloat(parseFloat(qmiNow)+parseFloat(orders3D[4]));
                                    }
                                    $('#prodShowQmimiTel'+orders3D[7]).html(qmiNew);
                                }else{
                                    // not found
                                    prods.push(orders3D[7]);
                                    if($('#VerkaufsstatistikenPH1Val').val() == 2){
                                        var listings =  '<tr class="prodShowListTel" id="prodShowTel'+orders3D[7]+'">'+
                                                            '<td class="prodShowListTD1Tel">'+orders3D[0]+'</td>'+
                                                            '<td class="prodShowListTD2Tel" id="prodShowSasiaTel'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                            '<td class="prodShowListTD3Tel" id="prodShowQmimiTel'+orders3D[7]+'">'+parseFloat(parseFloat(orders3D[4])*parseInt(orders3D[3]))+'</td>'+
                                                        '</tr>';
                                    }else{
                                        var listings =  '<tr class="prodShowListTel" id="prodShowTel'+orders3D[7]+'">'+
                                                            '<td class="prodShowListTD1Tel">'+orders3D[0]+'</td>'+
                                                            '<td class="prodShowListTD2Tel" id="prodShowSasiaTel'+orders3D[7]+'">'+orders3D[3]+'</td>'+
                                                            '<td class="prodShowListTD3Tel" id="prodShowQmimiTel'+orders3D[7]+'">'+orders3D[4]+'</td>'+
                                                        '</tr>';
                                        $('#VerkaufsstatistikenPH4TableTel').append(listings);
                                    }
                                }

                            });// end foreach orders2D
                        });// end foreach res
                        var listings =  '<tr>'+
                                            '<td style="font-weight:bold;" >'+$("#totalB").val()+'</td>'+
                                            '<td style="font-weight:bold;" >'+allSasiaNTel+'</td>'+
                                            '<td style="font-weight:bold;" >'+allQmimiNTel+'</td>'+
                                        '</tr>';
                        $('#VerkaufsstatistikenPH4TableTel').append(listings);

                    }else{
                        var listings =  '<tr >'+
                                            '<td colspan="3" style="color:red">'+$("#noOrders").val()+'</td>'+
                                        '</tr>';
                        $('#VerkaufsstatistikenPH4TableTel').append(listings);
                        $('#exportToExcelSalesTel').hide(5);
                    }
                   
				},
				error: (error) => {
					console.log(error);
					alert($('#pleaseUpdateAndTryAgain').val());
				}
			});
        }






















    

    

        function exportToExcelSalesTel(){
            var sendProdsLins = ""; 
            // productsToExport
            $("tr.prodShowListTel").each(function() {
                var qu1 = $(this).find(".prodShowListTD1Tel").html(),
                    qu2 = $(this).find(".prodShowListTD2Tel").html(),
                    qu3 = $(this).find(".prodShowListTD3Tel").html()
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
            $('#productsToExportTel').val(sendProdsLins);
            $('#downloadExcelFormTel').submit();
        }



     
    </script>
 























   

</div>