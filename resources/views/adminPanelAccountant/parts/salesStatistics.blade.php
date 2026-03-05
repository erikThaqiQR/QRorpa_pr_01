<?php

    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
?>
<style>
    #exportToExcelSales:hover{
        cursor: pointer;
    }
    #exportToPDFSales:hover{
        cursor: pointer;
    }
</style>

<input type="hidden" value="{{Auth::user()->sFor}}" id="theResId">

<input type="hidden" id="VerkaufsstatistikenPH1Val">
<input type="hidden" id="VerkaufsstatistikenPH1O5Val">
<input type="hidden" id="VerkaufsstatistikenPH2Val">
<input type="hidden" id="VerkaufsstatistikenPH21Val">
<input type="hidden" id="VerkaufsstatistikenPH22WeekS">
<input type="hidden" id="VerkaufsstatistikenPH22WeekE">
<input type="hidden" id="VerkaufsstatistikenPH22WeekNr">
<input type="hidden" id="VerkaufsstatistikenPH22WeekYr">
<input type="hidden" id="VerkaufsstatistikenPH23Month">
<input type="hidden" id="VerkaufsstatistikenPH23Year">
<input type="hidden" id="VerkaufsstatistikenPH24Year">

<div class="p-3 pb-5">
    <div class="d-flex">
        <a href="{{route('admWoMng.AccountantStatistics')}}" class="pl-4" style="width: 10%; color:rgb(39,190,175);"><strong><i class="fas fa-2x fa-chevron-left"></i></strong></a>
        <h2 style="width: 90%; color:rgb(39,190,175);"><strong>{{__('adminP.salesStatistics')}}</strong></h2>
    </div>
    <hr>
    <p style="margin-bottom:-4px;"><strong>{{__('adminP.salesStatisticsReport')}}</strong></p>
    <div class="progress">
        <div id="VerkaufsstatistikberichtLoading" class="progress-bar bg-danger" role="progressbar" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>

    <br>

    @include('adminPanelAccountant.salesStatistics.desktopph1')
    @include('adminPanelAccountant.salesStatistics.desktopph1_5')

    @include('adminPanelAccountant.salesStatistics.desktopph2')

    @include('adminPanelAccountant.salesStatistics.desktopph2Day')
    @include('adminPanelAccountant.salesStatistics.desktopph2Week')
    @include('adminPanelAccountant.salesStatistics.desktopph2Month')
    @include('adminPanelAccountant.salesStatistics.desktopph2Year')

    @include('adminPanelAccountant.salesStatistics.desktopph3')


    <div class="card" style="width: 100%; display:none;" id="VerkaufsstatistikenPH4">
        <div class="card-header">
            <strong>{{__('adminP.selectedDates')}}</strong>
        </div>
        <table style="width:80%; margin-left:10%;" id="VerkaufsstatistikenPH4Table2" class=" table table-hover">
            <thead>
                <tr>
                    <th id="exportToExcelSales" colspan="5" class="text-center text-primary">
                        {{Form::open(['action' => 'AdminPanelController@downloadExcel', 'method' => 'get', 'id' => 'downloadExcelForm']) }}
                            <button type="button" class="btn btn-success btn-block" onclick="exportToExcelSales()"><i class="far fa-file-excel"></i> Excel-Export </button>
                            <input type="hidden" name="productsToExport" id="productsToExport">
                        {{Form::close() }}
                    </th>
                    <th id="exportToPDFSales" colspan="5" class="text-center text-primary">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFDayR', 'method' => 'post', 'id' => 'downloadPDFDayReport']) }}
                            <button id="exportToPDFSalesBtn" type="submit" class="btn btn-danger btn-block shadow-none" onclick="disPDF1D()">
                                <i class="far fa-file-pdf"></i> Export von PDF-Berichten 
                            </button>
                            <input type="hidden" name="daySelectedPDFDay" id="daySelectedPDFDay">
                        {{Form::close() }}
                    </th>

                    <th id="exportToPDFSalesWeek" colspan="5" class="text-center text-primary">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFWeekR', 'method' => 'post', 'id' => 'downloadPDFWeekReport']) }}
                            <button id="exportToPDFSalesWeekBtn" type="submit" class="btn btn-danger btn-block shadow-none" onclick="disPDF1W()">
                                <i class="far fa-file-pdf"></i> Export von PDF-Berichten 
                            </button>
                            <input type="hidden" name="weekSelectedPDFWeekS" id="weekSelectedPDFWeekS">
                            <input type="hidden" name="weekSelectedPDFWeekE" id="weekSelectedPDFWeekE">
                            <input type="hidden" name="weekSelectedPDFWeekNr" id="weekSelectedPDFWeekNr">
                            <input type="hidden" name="weekSelectedPDFWeekYr" id="weekSelectedPDFWeekYr">
                        {{Form::close() }}
                    </th>

                    <th id="exportToPDFSalesMonth" colspan="5" class="text-center text-primary">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthR', 'method' => 'post', 'id' => 'downloadPDFMonthReport']) }}
                            <button id="exportToPDFSalesMonthBtn" type="submit" class="btn btn-danger btn-block shadow-none" onclick="disPDF1M()">
                                <i class="far fa-file-pdf"></i> Export von PDF-Berichten 
                            </button>
                            <input type="hidden" name="monthSelectedPDFMonth" id="monthSelectedPDFMonth">
                            <input type="hidden" name="monthSelectedPDFYear" id="monthSelectedPDFYear">
                        {{Form::close() }}
                    </th>

                    <th id="exportToPDFSalesMonthAllSel" colspan="5" class="text-center text-primary">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthSelectiveR', 'method' => 'post', 'id' => 'downloadPDFMonthSelectedReport']) }}
                            <button id="exportToPDFSalesMonthAllSelBtn" type="submit" class="btn btn-danger btn-block shadow-none" onclick="disPDF1M()">
                                <i class="far fa-file-pdf"></i> Export von Selektiv/Alle PDF-Berichten 
                            </button>
                            <input type="hidden" name="monthSelectedPDFMonthSelective" id="monthSelectedPDFMonthSelective">
                            <input type="hidden" name="monthSelectedPDFYearSelective" id="monthSelectedPDFYearSelective">
                            <input type="hidden" name="monthSelectedTheRes" id="monthSelectedTheRes">
                        {{Form::close() }}
                    </th>

                    <th id="exportToPDFSalesYear" colspan="5" class="text-center text-primary">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFYearR', 'method' => 'post', 'id' => 'downloadPDFYearReport']) }}
                            <button id="exportToPDFSalesYearBtn" type="submit" class="btn btn-danger btn-block shadow-none" onclick="disPDF1Y()">
                                <i class="far fa-file-pdf"></i> Export von PDF-Berichten 
                            </button>
                            <input type="hidden" name="yearSelectedPDFYear" id="yearSelectedPDFYear">
                        {{Form::close() }}
                    </th>

                 
                </tr>
                <tr>
                    <th>Produkt-ID</th>
                    <th>Restaurant</th>
                    <th>{{__('adminP.product')}}</th>
                    <th>{{__('adminP.crowd')}}</th>
                    <th>{{__('adminP.priceCHF')}}</th>
                </tr>
            </thead>
            <tbody id="VerkaufsstatistikenPH4Table">
            </tbody>
        </table>
    </div>



    <script>
        function disPDF1D(){
            $('#exportToPDFSalesBtn').prop('disabled', true);
            $('#exportToPDFSalesBtn').html('<img src="storage/gifs/loading2.gif" style="width:43px; height:auto;"  alt="">');
            $('#downloadPDFDayReport').submit();
            setTimeout( function(){ 
				$('#exportToPDFSalesBtn').prop('disabled', false);
                $('#exportToPDFSalesBtn').html('<i class="far fa-file-pdf"></i> Export von PDF-Berichten')
			}  , 7000 );
        }

        function disPDF1M(){
            $('#exportToPDFSalesMonthBtn').prop('disabled', true);
            $('#exportToPDFSalesMonthBtn').html('<img src="storage/gifs/loading2.gif" style="width:43px; height:auto;"  alt="">');
            $('#downloadPDFMonthReport').submit();
            setTimeout( function(){ 
				$('#exportToPDFSalesMonthBtn').prop('disabled', false);
                $('#exportToPDFSalesMonthBtn').html('<i class="far fa-file-pdf"></i> Export von PDF-Berichten')
			}  , 10000 );
        }

        function disPDF1W(){
            $('#exportToPDFSalesWeekBtn').prop('disabled', true);
            $('#exportToPDFSalesWeekBtn').html('<img src="storage/gifs/loading2.gif" style="width:43px; height:auto;"  alt="">');
            $('#downloadPDFWeekReport').submit();
            setTimeout( function(){ 
				$('#exportToPDFSalesWeekBtn').prop('disabled', false);
                $('#exportToPDFSalesWeekBtn').html('<i class="far fa-file-pdf"></i> Export von PDF-Berichten')
			}  , 7000 );
        }

        function disPDF1Y(){
            $('#exportToPDFSalesYearBtn').prop('disabled', true);
            $('#exportToPDFSalesYearBtn').html('<img src="storage/gifs/loading2.gif" style="width:43px; height:auto;"  alt="">');
            $('#downloadPDFYearReport').submit();
            setTimeout( function(){ 
				$('#exportToPDFSalesYearBtn').prop('disabled', false);
                $('#exportToPDFSalesYearBtn').html('<i class="far fa-file-pdf"></i> Export von PDF-Berichten')
			}  , 20000 );
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