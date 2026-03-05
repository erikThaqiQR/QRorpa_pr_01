<?php

    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
?>
    
    
    <div class="card mt-2" style="width: 100%; display:none;" id="VerkaufsstatistikenPH2">
        <div class="card-header">
            <strong>{{__('adminP.selectDatesBy')}}...</strong>
        </div>
        <div class="d-flex justify-content-between p-1">
            <button id="selectPH21" style="width: 24%;" onclick="selectPH2('1')" class="text-center btn btn-outline-dark"> <strong> <i class="fas fa-calendar-day"></i> {{__('adminP.day')}} </strong> </button>
            <button disabled id="selectPH22" style="width: 24%;" onclick="selectPH2('2')" class="text-center btn btn-outline-dark"> <strong> <i class="fas fa-calendar-week"></i> {{__('adminP.week')}} </strong> </button>
            <button disabled id="selectPH23" style="width: 24%;" onclick="selectPH2('3')" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.month')}} </strong> </button>
            <button disabled id="selectPH24" style="width: 24%;" onclick="selectPH2('4')" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.year')}} </strong> </button>
        </div>
    </div>