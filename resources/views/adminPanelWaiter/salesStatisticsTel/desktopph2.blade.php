<?php

    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
?>
    
    
    <div class="card mt-2" style="width: 100%; display:none;" id="VerkaufsstatistikenPH2Tel">
        <div class="card-header">
            <strong>{{__('adminP.selectDatesBy')}}...</strong>
        </div>
        <div class="d-flex justify-content-between flex-wrap p-1">
            <button id="selectPH21Tel" style="width: 49%;" onclick="selectPH2Tel('1')" class="text-center btn btn-outline-dark"> <strong> <i class="fas fa-calendar-day"></i> {{__('adminP.day')}} </strong> </button>
            <button disabled id="selectPH22Tel" style="width: 49%;" onclick="selectPH2Tel('2')" class="text-center btn btn-outline-dark"> <strong> <i class="fas fa-calendar-week"></i> {{__('adminP.week')}} </strong> </button>
            <button disabled id="selectPH23Tel" style="width: 49%;" onclick="selectPH2Tel('3')" class="text-center btn btn-outline-dark mt-1"> <strong> {{__('adminP.month')}} </strong> </button>
            <button disabled id="selectPH24Tel" style="width: 49%;" onclick="selectPH2Tel('4')" class="text-center btn btn-outline-dark mt-1"> <strong> {{__('adminP.year')}} </strong> </button>
        </div>
    </div>