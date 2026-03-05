<?php

    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
?>
    
    
    <div class="card" style="width: 100%;" id="VerkaufsstatistikenPH1">
        <div class="card-header">
            <strong>{{__('adminP.services')}}</strong>
        </div>
        <div class="d-flex justify-content-between p-1">
            @if($theRes->resType == 1 || $theRes->resType == 5)
                <button id="selectPH11" onclick="selectPH1('1')" style="width: 100%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}}</strong> </button>
            @elseif($theRes->resType == 2 || $theRes->resType == 6)
                <button id="selectPH11" onclick="selectPH1('1')" style="width: 49%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}} </strong> </button>
                <button id="selectPH12" onclick="selectPH1('2')" style="width: 49%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.takeaway')}} </strong> </button>
            @elseif($theRes->resType == 3 || $theRes->resType == 8)
                <button id="selectPH11" onclick="selectPH1('1')" style="width: 49%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}} </strong> </button>
                <button id="selectPH13" onclick="selectPH1('3')" style="width: 49%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.delivery')}} </strong> </button>
            @elseif($theRes->resType == 7 || $theRes->resType == 9)
                <button id="selectPH11" onclick="selectPH1('1')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.restaurant')}} </strong> </button>
                <button id="selectPH12" onclick="selectPH1('2')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.takeaway')}} </strong> </button>
                <button id="selectPH13" onclick="selectPH1('3')" style="width: 33%;" class="text-center btn btn-outline-dark"> <strong> {{__('adminP.delivery')}} </strong> </button>
            @endif
    
    
        </div>
    </div>