<div class="card" style="width: 100%; display:none;" id="VerkaufsstatistikenPH3">
        <div class="card-header">
            <strong>{{__('adminP.selectedDates')}}</strong>
        </div>
        <div class="d-flex justify-content-between p-1">
            <button id="selectedDataVerkauf11" style="width:24.9%; display:none;" class="btn btn-dark">{{__('adminP.restaurant')}}</button>
            <button id="selectedDataVerkauf12" style="width:24.9%; display:none;" class="btn btn-dark">{{__('adminP.takeaway')}}</button>
            <button id="selectedDataVerkauf13" style="width:24.9%; display:none;" class="btn btn-dark">{{__('adminP.delivery')}}</button>
    
            <button id="selectedDataVerkauf21" style="width:24.9%; display:none;" class="btn btn-dark">{{__('adminP.day')}}</button>
            <button id="selectedDataVerkauf22" style="width:24.9%; display:none;" class="btn btn-dark">{{__('adminP.week')}}</button>
            <button id="selectedDataVerkauf23" style="width:24.9%; display:none;" class="btn btn-dark">{{__('adminP.month')}}</button>
            <button id="selectedDataVerkauf24" style="width:24.9%; display:none;" class="btn btn-dark">{{__('adminP.year')}}</button>

            <button id="selectedDataVerkauf31" style="width:24.9%; display:none;" class="btn btn-dark"></button>

            <button onclick="reshowDatesToSelect()" id="selectedDataVerkauf41" style="width:24.9%; display:none;" class="btn btn-outline-dark"><strong>{{__('adminP.changeDate')}}</strong></button>
        </div>
    </div>


    <script>
        function reshowDatesToSelect(){
            // hide Table
            $('#VerkaufsstatistikenPH4').hide(200);
            // empty table body
            $('#VerkaufsstatistikenPH4Table').html('');
            // hide selected data card
            $('#VerkaufsstatistikenPH3').hide(200);
            

            $('#VerkaufsstatistikenPH1').show(200);
            $('#VerkaufsstatistikenPH2').show(200);
            $('#VerkaufsstatistikenPH21').show(200);
        }
    </script>