<?php

    use App\User;
    use App\logOrderPayMChng;
?>
<div class="pt-1 pr-2 pl-2 pb-5">
    <p style="font-size: 1.4rem; color:rgb(39,190,175);"><strong>Zahlungsarten geändert</strong></p>

    <hr>

    <div class="d-flex flex-wrap jstify-content-between">

        <p style="width: 14.28%;" class="text-center"><strong>Änderungs-ID</strong></p>
        <p style="width: 14.28%;" class="text-center"><strong>Bestellung</strong></p>
        <p style="width: 14.28%;" class="text-center"><strong>Datum/Zeit</strong></p>
        <p style="width: 14.28%;" class="text-center"><strong>Benutzer</strong></p>
        <p style="width: 14.28%;" class="text-center"><strong>Alte Zahlungsart</strong></p>
        <p style="width: 14.28%;" class="text-center"><strong>Neue Zahlungsart</strong></p>
        <p style="width: 14.28%;" class="text-center"><strong>Grund</strong></p>
        @foreach (logOrderPayMChng::where('toRes',Auth::user()->sFor)->orderBy('created_at','desc')->get() as $onePMCLog)
            <?php
                $dt = explode(' ',$onePMCLog->created_at)[0];
                $hr = explode(' ',$onePMCLog->created_at)[1];

                $dt2D = explode('-',$dt);
                $hr2D = explode(':',$hr);
            ?>
            <p style="width: 14.28%; border-bottom:1px solid rgb(72,81,87)" class="text-center"><strong>{{$onePMCLog->id}}</strong></p>
            <p style="width: 14.28%; border-bottom:1px solid rgb(72,81,87)" class="text-center"><strong># {{$onePMCLog->orderId}}</strong></p>
            <p style="width: 14.28%; border-bottom:1px solid rgb(72,81,87)" class="text-center"><strong>{{$dt2D[2]}}.{{$dt2D[1]}}.{{$dt2D[0]}} {{$hr2D[0]}}:{{$hr2D[1]}}</strong></p>
            @if (User::find($onePMCLog->staffId) == Null)
            <p style="width: 14.28%; border-bottom:1px solid rgb(72,81,87)" class="text-center"><strong>---</strong></p>
            @else
            <p style="width: 14.28%; border-bottom:1px solid rgb(72,81,87)" class="text-center"><strong>{{User::find($onePMCLog->staffId)->name}}</strong></p>
            @endif
            <p style="width: 14.28%; border-bottom:1px solid rgb(72,81,87)" class="text-center"><strong>{{$onePMCLog->payMPrevious}}</strong></p>
            <p style="width: 14.28%; border-bottom:1px solid rgb(72,81,87)" class="text-center"><strong>{{$onePMCLog->payMCurrent}}</strong></p>
            <p style="width: 14.28%; border-bottom:1px solid rgb(72,81,87)" class="text-center"><strong>Falsche Zahlungsart ausgewählt</strong></p>
        @endforeach
    </div>
</div>