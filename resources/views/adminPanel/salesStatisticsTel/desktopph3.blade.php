<div class="card" style="width: 100%; display:none;" id="VerkaufsstatistikenPH3">
        <div class="card-header">
            <strong>{{__('adminP.selectedDates')}}</strong>
        </div>
        <div class="d-flex flex-wrap justify-content-between p-1">
            <button id="selectedDataVerkauf11" style="width:49.9%; display:none;" class="btn btn-dark">{{__('adminP.restaurant')}}</button>
            <button id="selectedDataVerkauf12" style="width:49.9%; display:none;" class="btn btn-dark">{{__('adminP.takeaway')}}</button>
            <button id="selectedDataVerkauf13" style="width:49.9%; display:none;" class="btn btn-dark">{{__('adminP.delivery')}}</button>
            <button id="selectedDataVerkauf14" style="width:49.9%; display:none;" class="btn btn-dark">PDF-Bericht</button>
            <button id="selectedDataVerkauf15" style="width:49.9%; display:none;" class="btn btn-dark">Selektiv/Alle PDF-Bericht</button>
    
            <button id="selectedDataVerkauf21" style="width:49.9%; display:none;" class="btn btn-dark">{{__('adminP.day')}}</button>
            <button id="selectedDataVerkauf22" style="width:49.9%; display:none;" class="btn btn-dark">{{__('adminP.week')}}</button>
            <button id="selectedDataVerkauf23" style="width:49.9%; display:none;" class="btn btn-dark">{{__('adminP.month')}}</button>
            <button id="selectedDataVerkauf24" style="width:49.9%; display:none;" class="btn btn-dark">{{__('adminP.year')}}</button>

            <button id="selectedDataVerkauf31" style="width:49.9%; display:none;" class="btn btn-dark"></button>

            <button onclick="reshowDatesToSelect('day')" id="selectedDataVerkauf41" style="width:49.9%; display:none;" class="btn btn-outline-dark">
                <strong>Tag ändern</strong>
            </button>
            <button onclick="reshowDatesToSelect('week')" id="selectedDataVerkauf42" style="width:49.9%; display:none;" class="btn btn-outline-dark">
                <strong>Woche wechseln</strong>
            </button>
            <button onclick="reshowDatesToSelect('month')" id="selectedDataVerkauf43" style="width:49.9%; display:none;" class="btn btn-outline-dark">
                <strong>Monat ändern</strong>
            </button>
            <button onclick="reshowDatesToSelect('year')" id="selectedDataVerkauf44" style="width:49.9%; display:none;" class="btn btn-outline-dark">
                <strong>Jahr ändern</strong>
            </button>
        </div>
    </div>


    <script>
        function reshowDatesToSelect(dateCat){
            // hide Table
            $('#VerkaufsstatistikenPH4').hide(100);
            // empty table body
            $('#VerkaufsstatistikenPH4Table').html('');
            // hide selected data card
            $('#VerkaufsstatistikenPH3').hide(100);
            
            $('#VerkaufsstatistikenPH1').show(100);
            $('#VerkaufsstatistikenPH2').show(100);

            if(dateCat == 'day'){
                $('#VerkaufsstatistikenPH21').show(100);
            }else if(dateCat == 'week'){
                $('#VerkaufsstatistikenPH22').show(100);
            }else if(dateCat == 'month'){
                $('#VerkaufsstatistikenPH23').show(100);
            }else if(dateCat == 'year'){
                $('#VerkaufsstatistikenPH24').show(100);
            }
        }
    </script>