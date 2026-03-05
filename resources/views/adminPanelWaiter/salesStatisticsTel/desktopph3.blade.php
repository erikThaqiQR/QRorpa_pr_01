    <div class="card" style="width: 100%; display:none;" id="VerkaufsstatistikenPH3Tel">
        <div class="card-header">
            <strong>{{__('adminP.selectedDates')}}</strong>
        </div>
        <div class="d-flex flex-wrap justify-content-between p-1">
            <button id="selectedDataVerkauf11Tel" style="width:49.7%; display:none;" class="btn btn-dark">{{__('adminP.restaurant')}}</button>
            <button id="selectedDataVerkauf12Tel" style="width:49.7%; display:none;" class="btn btn-dark">{{__('adminP.takeaway')}}</button>
            <button id="selectedDataVerkauf13Tel" style="width:49.7%; display:none;" class="btn btn-dark">{{__('admonP.delivery')}}</button>
    
            <button id="selectedDataVerkauf21Tel" style="width:49.7%; display:none;" class="btn btn-dark">{{__('adminP.day')}}</button>
            <button id="selectedDataVerkauf22Tel" style="width:49.7%; display:none;" class="btn btn-dark">{{__('adminP.week')}}</button>
            <button id="selectedDataVerkauf23Tel" style="width:49.7%; display:none;" class="btn btn-dark">{{__('adminP.month')}}</button>
            <button id="selectedDataVerkauf24Tel" style="width:49.7%; display:none;" class="btn btn-dark">{{__('adminP.year')}}</button>

            <button id="selectedDataVerkauf31Tel" style="width:49.7%; display:none;" class="btn btn-dark mt-1"></button>

            <button onclick="reshowDatesToSelectTel()" id="selectedDataVerkauf41Tel" style="width:49.7%; display:none;" class="btn btn-outline-dark mt-1"><strong>{{__('adminP.changeDate')}}</strong></button>
        </div>
    </div>




    <script>
        function reshowDatesToSelectTel(){
            // hide Table
            $('#VerkaufsstatistikenPH4Tel').hide(200);
            // empty table body
            $('#VerkaufsstatistikenPH4TableTel').html('');
            // hide selected data card
            $('#VerkaufsstatistikenPH3Tel').hide(200);
            

            $('#VerkaufsstatistikenPH1Tel').show(200);
            $('#VerkaufsstatistikenPH2Tel').show(200);
            $('#VerkaufsstatistikenPH21Tel').show(200);
            
            
        }
    </script>