<?php

use Illuminate\Support\Facades\Auth;

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
<input type="hidden" value="{{$theRes->emri}}" id="theResName">

<input type="hidden" id="VerkaufsstatistikenPH1Val">
<input type="hidden" id="VerkaufsstatistikenPH1O5Val">
<input type="hidden" id="VerkaufsstatistikenPH1O6Val">
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
        <a href="{{route('dash.statistics')}}" class="pl-4" style="width: 15%; color:rgb(39,190,175);"><strong><i class="fas fa-chevron-left"></i></strong></a>
        <h4 style="width: 85%; color:rgb(39,190,175);"><strong>{{__('adminP.salesStatistics')}}</strong></h4>
    </div>
    <hr>
    <p style="margin-bottom:-4px;"><strong>{{__('adminP.salesStatisticsReport')}}</strong></p>
    <div class="progress">
        <div id="VerkaufsstatistikberichtLoading" class="progress-bar bg-danger" role="progressbar" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
    <br>

    @include('adminPanel.salesStatisticsTel.desktopph1')
    @include('adminPanel.salesStatisticsTel.desktopph1_5')
    @include('adminPanel.salesStatisticsTel.desktopV1SelectRes')

    @include('adminPanel.salesStatisticsTel.desktopph2')

    @include('adminPanel.salesStatisticsTel.desktopph2Day')
    @include('adminPanel.salesStatisticsTel.desktopph2Week')
    @include('adminPanel.salesStatisticsTel.desktopph2Month')
    @include('adminPanel.salesStatisticsTel.desktopph2Year')

    @include('adminPanel.salesStatisticsTel.desktopph3')

    <div class="card" style="width: 100%; display:none;" id="VerkaufsstatistikenPH4">
        <div class="card-header">
            <strong>{{__('adminP.selectedDates')}}</strong>
        </div>
        <table style="width:96%; margin-left:2%;" id="VerkaufsstatistikenPH4Table2" class=" table table-hover">
            <thead>
                <tr>
                    <th id="exportToExcelSales" colspan="4" class="text-center text-primary">
                        {{Form::open(['action' => 'AdminPanelController@downloadExcel', 'method' => 'get', 'id' => 'downloadExcelForm']) }}
                            <button type="button" class="btn btn-success btn-block" onclick="exportToExcelSales()"><i class="far fa-file-excel"></i> Excel-Export </button>
                            <input type="hidden" name="productsToExport" id="productsToExport">
                        {{Form::close() }}
                    </th>


                    <th id="exportToPDFSales" colspan="4" class="text-center text-primary">
                        <button id="exportToPDFSalesBtn" type="button" class="btn btn-danger btn-block shadow-none" onclick="disPDF1D('{{Auth::user()->sFor}}')">
                            <i class="far fa-file-pdf"></i> Export von PDF-Berichten 
                        </button>
                        <input type="hidden" name="resSelectedSendToRaportGenV1" id="resSelectedSendToRaportGenV1Day" value="empty">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFDayR', 'method' => 'post', 'id' => 'downloadPDFDayReport0']) }}
                            <input type="hidden" name="daySelectedPDFDay" class="daySelectedPDFDay">
                            <input type="hidden" name="daySelectedPDFDayForRes" id="daySelectedPDFDayForRes0">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFDayR', 'method' => 'post', 'id' => 'downloadPDFDayReport1']) }}
                            <input type="hidden" name="daySelectedPDFDay" class="daySelectedPDFDay">
                            <input type="hidden" name="daySelectedPDFDayForRes" id="daySelectedPDFDayForRes1">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFDayR', 'method' => 'post', 'id' => 'downloadPDFDayReport2']) }}
                            <input type="hidden" name="daySelectedPDFDay" class="daySelectedPDFDay">
                            <input type="hidden" name="daySelectedPDFDayForRes" id="daySelectedPDFDayForRes2">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFDayR', 'method' => 'post', 'id' => 'downloadPDFDayReport3']) }}
                            <input type="hidden" name="daySelectedPDFDay" class="daySelectedPDFDay">
                            <input type="hidden" name="daySelectedPDFDayForRes" id="daySelectedPDFDayForRes3">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFDayR', 'method' => 'post', 'id' => 'downloadPDFDayReport4']) }}
                            <input type="hidden" name="daySelectedPDFDay" class="daySelectedPDFDay">
                            <input type="hidden" name="daySelectedPDFDayForRes" id="daySelectedPDFDayForRes4">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFDayR', 'method' => 'post', 'id' => 'downloadPDFDayReport5']) }}
                            <input type="hidden" name="daySelectedPDFDay" class="daySelectedPDFDay">
                            <input type="hidden" name="daySelectedPDFDayForRes" id="daySelectedPDFDayForRes5">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFDayR', 'method' => 'post', 'id' => 'downloadPDFDayReport6']) }}
                            <input type="hidden" name="daySelectedPDFDay" class="daySelectedPDFDay">
                            <input type="hidden" name="daySelectedPDFDayForRes" id="daySelectedPDFDayForRes6">
                        {{Form::close() }}
                    </th>




                    <th id="exportToPDFSalesWeek" colspan="4" class="text-center text-primary">
                        <button id="exportToPDFSalesWeekBtn" type="button" class="btn btn-danger btn-block shadow-none" onclick="disPDF1W('{{Auth::user()->sFor}}')">
                            <i class="far fa-file-pdf"></i> Export von PDF-Berichten 
                        </button>
                        <input type="hidden" name="resSelectedSendToRaportGenV1" id="resSelectedSendToRaportGenV1Week" value="empty">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFWeekR', 'method' => 'post', 'id' => 'downloadPDFWeekReport0']) }}
                            <input type="hidden" name="weekSelectedPDFWeekS" class="weekSelectedPDFWeekS">
                            <input type="hidden" name="weekSelectedPDFWeekE" class="weekSelectedPDFWeekE">
                            <input type="hidden" name="weekSelectedPDFWeekNr" class="weekSelectedPDFWeekNr">
                            <input type="hidden" name="weekSelectedPDFWeekYr" class="weekSelectedPDFWeekYr">
                            <input type="hidden" name="daySelectedPDFWeekForRes" id="daySelectedPDFWeekForRes0">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFWeekR', 'method' => 'post', 'id' => 'downloadPDFWeekReport1']) }}
                            <input type="hidden" name="weekSelectedPDFWeekS" class="weekSelectedPDFWeekS">
                            <input type="hidden" name="weekSelectedPDFWeekE" class="weekSelectedPDFWeekE">
                            <input type="hidden" name="weekSelectedPDFWeekNr" class="weekSelectedPDFWeekNr">
                            <input type="hidden" name="weekSelectedPDFWeekYr" class="weekSelectedPDFWeekYr">
                            <input type="hidden" name="daySelectedPDFWeekForRes" id="daySelectedPDFWeekForRes1">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFWeekR', 'method' => 'post', 'id' => 'downloadPDFWeekReport2']) }}
                            <input type="hidden" name="weekSelectedPDFWeekS" class="weekSelectedPDFWeekS">
                            <input type="hidden" name="weekSelectedPDFWeekE" class="weekSelectedPDFWeekE">
                            <input type="hidden" name="weekSelectedPDFWeekNr" class="weekSelectedPDFWeekNr">
                            <input type="hidden" name="weekSelectedPDFWeekYr" class="weekSelectedPDFWeekYr">
                            <input type="hidden" name="daySelectedPDFWeekForRes" id="daySelectedPDFWeekForRes2">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFWeekR', 'method' => 'post', 'id' => 'downloadPDFWeekReport3']) }}
                            <input type="hidden" name="weekSelectedPDFWeekS" class="weekSelectedPDFWeekS">
                            <input type="hidden" name="weekSelectedPDFWeekE" class="weekSelectedPDFWeekE">
                            <input type="hidden" name="weekSelectedPDFWeekNr" class="weekSelectedPDFWeekNr">
                            <input type="hidden" name="weekSelectedPDFWeekYr" class="weekSelectedPDFWeekYr">
                            <input type="hidden" name="daySelectedPDFWeekForRes" id="daySelectedPDFWeekForRes3">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFWeekR', 'method' => 'post', 'id' => 'downloadPDFWeekReport4']) }}
                            <input type="hidden" name="weekSelectedPDFWeekS" class="weekSelectedPDFWeekS">
                            <input type="hidden" name="weekSelectedPDFWeekE" class="weekSelectedPDFWeekE">
                            <input type="hidden" name="weekSelectedPDFWeekNr" class="weekSelectedPDFWeekNr">
                            <input type="hidden" name="weekSelectedPDFWeekYr" class="weekSelectedPDFWeekYr">
                            <input type="hidden" name="daySelectedPDFWeekForRes" id="daySelectedPDFWeekForRes4">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFWeekR', 'method' => 'post', 'id' => 'downloadPDFWeekReport5']) }}
                            <input type="hidden" name="weekSelectedPDFWeekS" class="weekSelectedPDFWeekS">
                            <input type="hidden" name="weekSelectedPDFWeekE" class="weekSelectedPDFWeekE">
                            <input type="hidden" name="weekSelectedPDFWeekNr" class="weekSelectedPDFWeekNr">
                            <input type="hidden" name="weekSelectedPDFWeekYr" class="weekSelectedPDFWeekYr">
                            <input type="hidden" name="daySelectedPDFWeekForRes" id="daySelectedPDFWeekForRes5">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFWeekR', 'method' => 'post', 'id' => 'downloadPDFWeekReport6']) }}
                            <input type="hidden" name="weekSelectedPDFWeekS" class="weekSelectedPDFWeekS">
                            <input type="hidden" name="weekSelectedPDFWeekE" class="weekSelectedPDFWeekE">
                            <input type="hidden" name="weekSelectedPDFWeekNr" class="weekSelectedPDFWeekNr">
                            <input type="hidden" name="weekSelectedPDFWeekYr" class="weekSelectedPDFWeekYr">
                            <input type="hidden" name="daySelectedPDFWeekForRes" id="daySelectedPDFWeekForRes6">
                        {{Form::close() }}
                    </th>




                     <th id="exportToPDFSalesMonth" colspan="4" class="text-center text-primary">
                        <button id="exportToPDFSalesMonthBtn" type="button" class="btn btn-danger btn-block shadow-none" onclick="disPDF1M('{{Auth::user()->sFor}}')">
                            <i class="far fa-file-pdf"></i> Export von PDF-Berichten 
                        </button>
                        <input type="hidden" name="resSelectedSendToRaportGenV1" id="resSelectedSendToRaportGenV1Month" value="empty">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthR', 'method' => 'post', 'id' => 'downloadPDFMonthReport0']) }}
                            <input type="hidden" name="monthSelectedPDFMonth" class="monthSelectedPDFMonth">
                            <input type="hidden" name="monthSelectedPDFYear" class="monthSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFMonthForRes" id="daySelectedPDFMonthForRes0">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthR', 'method' => 'post', 'id' => 'downloadPDFMonthReport1']) }}
                            <input type="hidden" name="monthSelectedPDFMonth" class="monthSelectedPDFMonth">
                            <input type="hidden" name="monthSelectedPDFYear" class="monthSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFMonthForRes" id="daySelectedPDFMonthForRes1">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthR', 'method' => 'post', 'id' => 'downloadPDFMonthReport2']) }}
                            <input type="hidden" name="monthSelectedPDFMonth" class="monthSelectedPDFMonth">
                            <input type="hidden" name="monthSelectedPDFYear" class="monthSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFMonthForRes" id="daySelectedPDFMonthForRes2">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthR', 'method' => 'post', 'id' => 'downloadPDFMonthReport3']) }}
                            <input type="hidden" name="monthSelectedPDFMonth" class="monthSelectedPDFMonth">
                            <input type="hidden" name="monthSelectedPDFYear" class="monthSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFMonthForRes" id="daySelectedPDFMonthForRes3">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthR', 'method' => 'post', 'id' => 'downloadPDFMonthReport4']) }}
                            <input type="hidden" name="monthSelectedPDFMonth" class="monthSelectedPDFMonth">
                            <input type="hidden" name="monthSelectedPDFYear" class="monthSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFMonthForRes" id="daySelectedPDFMonthForRes4">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthR', 'method' => 'post', 'id' => 'downloadPDFMonthReport5']) }}
                            <input type="hidden" name="monthSelectedPDFMonth" class="monthSelectedPDFMonth">
                            <input type="hidden" name="monthSelectedPDFYear" class="monthSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFMonthForRes" id="daySelectedPDFMonthForRes5">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthR', 'method' => 'post', 'id' => 'downloadPDFMonthReport6']) }}
                            <input type="hidden" name="monthSelectedPDFMonth" class="monthSelectedPDFMonth">
                            <input type="hidden" name="monthSelectedPDFYear" class="monthSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFMonthForRes" id="daySelectedPDFMonthForRes6">
                        {{Form::close() }}
                    </th>


                    <th id="exportToPDFSalesMonthAllSel" colspan="2" class="text-center text-primary mb-2">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFMonthSelectiveR', 'method' => 'post', 'id' => 'downloadPDFMonthSelectedReport']) }}
                            <button id="exportToPDFSalesMonthAllSelBtn" type="submit" class="btn btn-danger btn-block shadow-none" onclick="disPDF1M()">
                                <i class="far fa-file-pdf"></i> Export von Selektiv/Alle PDF-Berichten 
                            </button>
                            <input type="hidden" name="monthSelectedPDFMonthSelective" id="monthSelectedPDFMonthSelective">
                            <input type="hidden" name="monthSelectedPDFYearSelective" id="monthSelectedPDFYearSelective">
                            <input type="hidden" name="monthSelectedTheRes" id="monthSelectedTheRes">
                        {{Form::close() }}
                    </th>
                    <th id="exportToEXCSalesMonthAllSel" colspan="2" class="text-center text-primary">
                        {{Form::open(['action' => 'InvoiceController@downloadEXCMonthSelectiveR', 'method' => 'get', 'id' => 'downloadEXCMonthSelectedReport']) }}
                            <button id="exportToEXCSalesMonthAllSelBtn" type="submit" class="btn btn-success btn-block shadow-none" onclick="disEXC1M()">
                                <i class="far fa-file-pdf"></i> Export von Selektiv/Alle EXCEL-Berichten 
                            </button>
                            <input type="hidden" name="monthSelectedEXCMonthSelective" id="monthSelectedEXCMonthSelective">
                            <input type="hidden" name="monthSelectedEXCYearSelective" id="monthSelectedEXCYearSelective">
                            <input type="hidden" name="monthSelectedEXCTheRes" id="monthSelectedEXCTheRes">
                        {{Form::close() }}
                    </th>

                    <th id="exportToPDFSalesYear" colspan="4" class="text-center text-primary">
                        <button id="exportToPDFSalesYearBtn" type="button" class="btn btn-danger btn-block shadow-none" onclick="disPDF1Y('{{Auth::user()->sFor}}')">
                            <i class="far fa-file-pdf"></i> Export von PDF-Berichten 
                        </button>
                        <input type="hidden" name="resSelectedSendToRaportGenV1" id="resSelectedSendToRaportGenV1Year" value="empty">
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFYearR', 'method' => 'post', 'id' => 'downloadPDFYearReport0']) }}
                            <input type="hidden" name="yearSelectedPDFYear" class="yearSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFYearForRes" id="daySelectedPDFYearForRes0">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFYearR', 'method' => 'post', 'id' => 'downloadPDFYearReport1']) }}
                            <input type="hidden" name="yearSelectedPDFYear" class="yearSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFYearForRes" id="daySelectedPDFYearForRes1">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFYearR', 'method' => 'post', 'id' => 'downloadPDFYearReport2']) }}
                            <input type="hidden" name="yearSelectedPDFYear" class="yearSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFYearForRes" id="daySelectedPDFYearForRes2">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFYearR', 'method' => 'post', 'id' => 'downloadPDFYearReport3']) }}
                            <input type="hidden" name="yearSelectedPDFYear" class="yearSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFYearForRes" id="daySelectedPDFYearForRes3">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFYearR', 'method' => 'post', 'id' => 'downloadPDFYearReport4']) }}
                            <input type="hidden" name="yearSelectedPDFYear" class="yearSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFYearForRes" id="daySelectedPDFYearForRes4">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFYearR', 'method' => 'post', 'id' => 'downloadPDFYearReport5']) }}
                            <input type="hidden" name="yearSelectedPDFYear" class="yearSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFYearForRes" id="daySelectedPDFYearForRes5">
                        {{Form::close() }}
                        {{Form::open(['action' => 'AdminPanelController@downloadPDFYearR', 'method' => 'post', 'id' => 'downloadPDFYearReport6']) }}
                            <input type="hidden" name="yearSelectedPDFYear" class="yearSelectedPDFYear">
                            <input type="hidden" name="daySelectedPDFYearForRes" id="daySelectedPDFYearForRes6">
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
        function disPDF1D(originalResID){
            $('#exportToPDFSalesBtn').prop('disabled', true);
            $('#exportToPDFSalesBtn').html('<img src="storage/gifs/loading2.gif" style="width:43px; height:auto;"  alt="">');
            var nrOfReports = 0;
            if($('#resSelectedSendToRaportGenV1Day').val() == 'empty'){
                $('#daySelectedPDFDayForRes0').val(originalResID);
                $('#downloadPDFDayReport0').submit();
                nrOfReports++;
            }else{
                var allResSel = $('#resSelectedSendToRaportGenV1Day').val();
                var allResSel2D = allResSel.split('-m-m-m-');
                $.each(allResSel2D, function( index, resId ) {

                    if(resId != ''){
                        setTimeout( function(){ 
                            $('#daySelectedPDFDayForRes'+index).val(resId);
                            $('#downloadPDFDayReport'+index).submit();
                        }  , index*4000 );
                        nrOfReports++;
                    }
                });
            }
            setTimeout( function(){ 
				$('#exportToPDFSalesBtn').prop('disabled', false);
                $('#exportToPDFSalesBtn').html('<i class="far fa-file-pdf"></i> Export von PDF-Berichten')
			}  , nrOfReports*4000 );
        }


        function disPDF1W(originalResID){
            $('#exportToPDFSalesWeekBtn').prop('disabled', true);
            $('#exportToPDFSalesWeekBtn').html('<img src="storage/gifs/loading2.gif" style="width:43px; height:auto;"  alt="">');
            var nrOfReports = 0;
            if($('#resSelectedSendToRaportGenV1Week').val() == 'empty'){
                $('#daySelectedPDFWeekForRes0').val(originalResID);
                $('#downloadPDFWeekReport0').submit();
                nrOfReports++;
            }else{
                var allResSel = $('#resSelectedSendToRaportGenV1Week').val();
                var allResSel2D = allResSel.split('-m-m-m-');
                $.each(allResSel2D, function( index, resId ) {

                    if(resId != ''){
                        setTimeout( function(){ 
                            $('#daySelectedPDFWeekForRes'+index).val(resId);
                            $('#downloadPDFWeekReport'+index).submit();
                        }  , index*5000 );
                        nrOfReports++;
                    }
                });
            }
            setTimeout( function(){ 
				$('#exportToPDFSalesWeekBtn').prop('disabled', false);
                $('#exportToPDFSalesWeekBtn').html('<i class="far fa-file-pdf"></i> Export von PDF-Berichten')
			}  , nrOfReports*5000 );
        }

        
        function disPDF1M(originalResID){
            $('#exportToPDFSalesMonthBtn').prop('disabled', true);
            $('#exportToPDFSalesMonthBtn').html('<img src="storage/gifs/loading2.gif" style="width:43px; height:auto;"  alt="">');
            var nrOfReports = 0;
            if($('#resSelectedSendToRaportGenV1Month').val() == 'empty'){
                $('#daySelectedPDFMonthForRes0').val(originalResID);
                $('#downloadPDFMonthReport0').submit();
                nrOfReports++;
            }else{
                var allResSel = $('#resSelectedSendToRaportGenV1Month').val();
                var allResSel2D = allResSel.split('-m-m-m-');
                $.each(allResSel2D, function( index, resId ) {

                    if(resId != ''){
                        setTimeout( function(){ 
                            $('#daySelectedPDFMonthForRes'+index).val(resId);
                            $('#downloadPDFMonthReport'+index).submit();
                        }  , index*10000 );
                        nrOfReports++;
                    }
                });
            }
            setTimeout( function(){ 
				$('#exportToPDFSalesMonthBtn').prop('disabled', false);
                $('#exportToPDFSalesMonthBtn').html('<i class="far fa-file-pdf"></i> Export von PDF-Berichten')
			}  , nrOfReports*10000 );
        }


        function disPDF1Y(originalResID){
            $('#exportToPDFSalesYearBtn').prop('disabled', true);
            $('#exportToPDFSalesYearBtn').html('<img src="storage/gifs/loading2.gif" style="width:43px; height:auto;"  alt="">');
            var nrOfReports = 0;
            if($('#resSelectedSendToRaportGenV1Year').val() == 'empty'){
                $('#daySelectedPDFYearForRes0').val(originalResID);
                $('#downloadPDFYearReport0').submit();
                nrOfReports++;
            }else{
                var allResSel = $('#resSelectedSendToRaportGenV1Year').val();
                var allResSel2D = allResSel.split('-m-m-m-');
                $.each(allResSel2D, function( index, resId ) {

                    if(resId != ''){
                        setTimeout( function(){ 
                            $('#daySelectedPDFYearForRes'+index).val(resId);
                            $('#downloadPDFYearReport'+index).submit();
                        }  , index*12000 );
                        nrOfReports++;
                    }
                });
            }
            setTimeout( function(){ 
				$('#exportToPDFSalesYearBtn').prop('disabled', false);
                $('#exportToPDFSalesYearBtn').html('<i class="far fa-file-pdf"></i> Export von PDF-Berichten')
			}  , nrOfReports*12000 );
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