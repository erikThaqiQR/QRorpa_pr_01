<?php

    use App\User;
    use App\logOrderPayMChng;
?>
<style>
    .btn-outline-dark:focus, .btn-outline-dark:hover {
        color: #212529 !important;
        background-color: transparent !important; /* new color goes her */
        border-color: #212529 !important;
    }

</style>
<div class="pt-1 pr-2 pl-2 pb-5">
    <p style="font-size: 1.4rem; color:rgb(39,190,175);"><strong>Zahlungsarten geändert</strong></p>

    <hr>

    <div class="d-flex flex-wrap jstify-content-between">


        @foreach (logOrderPayMChng::where('toRes',Auth::user()->sFor)->orderBy('created_at','desc')->get() as $onePMCLog)
            <?php
                $dt = explode(' ',$onePMCLog->created_at)[0];
                $hr = explode(' ',$onePMCLog->created_at)[1];

                $dt2D = explode('-',$dt);
                $hr2D = explode(':',$hr);
            ?>
            <p style="width: 35%; line-height:1.4; margin-bottom:4px;" class="text-center"><strong>Bestellung: #{{$onePMCLog->orderId}}</strong></p>
            <p style="width: 65%; line-height:1.4; margin-bottom:4px;" class="text-center"><strong>{{$onePMCLog->payMPrevious}} -> {{$onePMCLog->payMCurrent}}</strong></p>

            <p id="timeFor{{$onePMCLog->id}}" style="width: 100%; line-height:1.4; margin-bottom:4px; display:none;" class="text-center">Datum/Uhrzeit: <strong>{{$dt2D[2]}}.{{$dt2D[1]}}.{{$dt2D[0]}} {{$hr2D[0]}}:{{$hr2D[1]}}</strong></p>
            @if (User::find($onePMCLog->staffId) == Null)
            <p id="nameFor{{$onePMCLog->id}}" style="width: 100%; line-height:1.4; margin-bottom:4px; display:none;" class="text-center">Benutzer: <strong>---</strong></p>
            @else
            <p id="nameFor{{$onePMCLog->id}}" style="width: 100%; line-height:1.4; margin-bottom:4px; display:none;" class="text-center">Benutzer: <strong>{{User::find($onePMCLog->staffId)->name}}</strong></p>
            @endif
            <p id="reasonFor{{$onePMCLog->id}}" style="width: 100%; line-height:1.4; margin-bottom:4px; display:none;" class="text-center">Grund: <strong>Falsche Zahlungsart ausgewählt</strong></p>

            <button id="chngBtnFor{{$onePMCLog->id}}" onclick="ShowHideExInfo('{{$onePMCLog->id}}')" style="width:100%; padding-top:3px; padding-bottom:3px;" class="btn btn-outline-dark shadow-none">
                Mehr anzeigen <i class="fa-regular fa-square-caret-down"></i>
            </button>
            <hr style="width: 100%;">
        @endforeach
    </div>
</div>

<script>
    function ShowHideExInfo(pmcId){
        if($('#timeFor'+pmcId).is(":hidden")){
            $('#timeFor'+pmcId).show(100);
            $('#nameFor'+pmcId).show(100);
            $('#reasonFor'+pmcId).show(100);

            $('#chngBtnFor'+pmcId).html('Weniger anzeigen <i class="fa-regular fa-square-caret-up"></i>');
        }else{
            $('#timeFor'+pmcId).hide(100);
            $('#nameFor'+pmcId).hide(100);
            $('#reasonFor'+pmcId).hide(100);

            $('#chngBtnFor'+pmcId).html('Mehr anzeigen <i class="fa-regular fa-square-caret-down"></i>');
        }
    }
</script>