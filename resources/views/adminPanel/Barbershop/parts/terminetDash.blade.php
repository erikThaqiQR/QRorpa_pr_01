<?php
    use App\BarbershopService;
    use App\BarbershopServiceOrder;
    use App\BarbershopServiceOrdersRecords;

    use App\BarbershopWorkerTerminet;
    use App\BarbershopWorker;
use App\BarbershopWorkerTerminBusy;

$barID = Auth::user()->sFor;
?>
<style>
    .color-text{
        color: rgb(72,81,87);
    }
    p{
        color: rgb(72,81,87);
    }
</style>

<section class="pl-3 pr-3 pt-2 pb-5" id="allBarbershopServices">
    <h2 class="color-qrorpa"><strong> {{__('adminP.pendingReservations')}} </strong></h2>
    <hr>
    <div class="alert alert-danger text-center p-2 mt-2 mb-2" style="font-size:22px; display:none;" id="nukPranohetRezervimi">
        <strong>{{__('adminP.canNotReservations')}}</strong>
    </div>
    @if(BarbershopServiceOrder::where([['status','0'],['toBar', $barID]])->get()->count() == 0)
        <h4 style="color:rgb(72,81,87);"><strong>{{__('adminP.noPendingReservationsCheckLater')}}</strong></h4>
    @else
       
        @foreach(BarbershopServiceOrder::where([['status','0'],['toBar', $barID]])->get()->sortByDesc('created_at') as $barOr)
            <div class="d-flex flex-wrap justify-content-between p-2 mb-2 shadow " style="border:1px solid rgb(72,81,87); border-radius:25px;">

                <h3 class="color-text" style="width:10%"><strong># {{ $barOr->id }} </strong></h3>
               
                @if($barOr->bakshish > 0)
                    <h3 class="color-text text-center" style="width:40%"><strong>
                        {{ $barOr->shumaTot - $barOr->bakshish }} <sup>{{__('adminP.currencyShow')}}</sup> ( {{$barOr->bakshish}} <sup>{{__('adminP.currencyShow')}}</sup> Trinkgeld ) => {{ $barOr->shumaTot }} <sup>{{__('adminP.currencyShow')}}</sup>
                    </strong></h3>
                @else
                    <h3 class="color-text text-center" style="width:40%"><strong>
                        {{ $barOr->shumaTot }} <sup>{{__('adminP.currencyShow')}}</sup>
                    </strong></h3>
                @endif
                <h3 class="color-text text-center" style="width:25%"><strong> - {{ $barOr->couponOff }} {{__('adminP.currencyShow')}} "{{__('adminP.coupon')}}"</strong></h3>
                <h3 class="color-text text-center" style="width:25%"><strong>{{ $barOr->totalMins }} {{__('adminP.minutes')}} </strong></h3>
                
                
                @if ($barOr->forStudent == 1)
                    <h3 class="color-text text-left pl-3" style="width:60%">
                        <strong><span style="color:red;" class="pr-4"> " {{__('adminP.studentPrice')}} " </span> {{ $barOr->clName }} {{ $barOr->clLastname }} " {{ $barOr->clEmail }} "</strong>
                    </h3>
                @else
                    <h3 class="color-text text-left pl-3" style="width:60%">
                        <strong>{{ $barOr->clName }} {{ $barOr->clLastname }} " {{ $barOr->clEmail }} "</strong>
                    </h3>
                @endif
                <h3 class="color-text text-center" style="width:40%"><strong>+{{ $barOr->clPhoneNr }} </strong></h3>

                @foreach(BarbershopServiceOrdersRecords::where('forSerOrder', $barOr->id)->get()->sortByDesc('created_at') as $barOrRecord)
                    @if($barOrRecord->status == 1)
                        <div style="width:100%; border:1px solid rgb(39,190,175,0.6); border-radius:15px; background-color:rgb(255,0,0,0.2);" class="ml-1 mr-1 mt-2 p-2 d-flex flex-wrap justify-content-between">
                    @elseif($barOrRecord->status == 2)
                        <div style="width:100%; border:1px solid rgb(39,190,175,0.6); border-radius:15px; background-color:rgb(0,255,0,0.2);"" class="ml-1 mr-1 mt-2 p-2 d-flex flex-wrap justify-content-between">
                    @else
                        <div style="width:100%; border:1px solid rgb(39,190,175,0.6); border-radius:15px;" class="ml-1 mr-1 mt-2 p-2 d-flex flex-wrap justify-content-between">
                    @endif
                        <div style="width:25%;">
                            <p><strong>{{$barOrRecord->emri}}
                                @if($barOrRecord->type != NULL)
                                    ( {{explode('||', $barOrRecord->type)[1]}} )
                                @endif
                            </strong></p>
                            <p style="margin-top:-15px;">{{$barOrRecord->pershkrimi}}</p>
                            <p style="margin-top:-15px; font-size:19px;">
                                <strong>{{$barOrRecord->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong>
                                @if ($barOrRecord->forStudent == 1)
                                    ( Studentenpreis )
                                @endif
                            </p>
                        </div>

                      
                        <div style="width:35%;">
                            <?php $workerTer01 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin);
                                $worTerminDiff = 15;
                                $workerTermins01 =  $workerTer01->id;
                            ?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer01->startT}} - {{$workerTer01->endT}}</button>
                            @if($barOrRecord->timeNeed > $worTerminDiff)
                            <?php $workerTer02 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 1); 
                                $workerTermins02 =  $workerTer02->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer02->startT}} - {{$workerTer02->endT}}</button>
                            @else
                                <?php $workerTermins02 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (2 * $worTerminDiff))
                            <?php $workerTer03 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 2); 
                                $workerTermins03 =  $workerTer03->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer03->startT}} - {{$workerTer03->endT}}</button>
                            @else
                                <?php $workerTermins03 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (3 * $worTerminDiff))
                            <?php $workerTer04 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 3); 
                                $workerTermins04 =  $workerTer04->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer04->startT}} - {{$workerTer04->endT}}</button>
                            @else
                                <?php $workerTermins04 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (4 * $worTerminDiff))
                            <?php $workerTer05 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 4); 
                                $workerTermins05 =  $workerTer05->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer05->startT}} - {{$workerTer05->endT}}</button>
                            @else
                                <?php $workerTermins05 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (5 * $worTerminDiff))
                            <?php $workerTer06 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 5); 
                                $workerTermins06 =  $workerTer06->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer06->startT}} - {{$workerTer06->endT}}</button>
                            @else
                                <?php $workerTermins06 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (6 * $worTerminDiff))
                            <?php $workerTer07 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 6); 
                                $workerTermins07 =  $workerTer07->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer07->startT}} - {{$workerTer07->endT}}</button>
                            @else
                                <?php $workerTermins07 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (7 * $worTerminDiff))
                            <?php $workerTer08 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 7); 
                                $workerTermins08 =  $workerTer08->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer08->startT}} - {{$workerTer08->endT}}</button>
                            @else
                                <?php $workerTermins08 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (8 * $worTerminDiff))
                            <?php $workerTer09 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 8); 
                                $workerTermins09 =  $workerTer09->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer09->startT}} - {{$workerTer09->endT}}</button>
                            @else
                                <?php $workerTermins09 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (9 * $worTerminDiff))
                            <?php $workerTer10 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 9); 
                                $workerTermins10 =  $workerTer10->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer10->startT}} - {{$workerTer10->endT}}</button>
                            @else
                                <?php $workerTermins10 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (10 * $worTerminDiff))
                            <?php $workerTer11 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 10); 
                                $workerTermins11 =  $workerTer11->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer11->startT}} - {{$workerTer11->endT}}</button>
                            @else
                                <?php $workerTermins11 =  0 ; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (11 * $worTerminDiff))
                            <?php $workerTer12 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 11); 
                                $workerTermins12 =  $workerTer12->id;?>
                                <button class="btn btn-outline-success mr-1 mb-1">{{$workerTer12->startT}} - {{$workerTer12->endT}}</button>
                            @else
                                <?php $workerTermins12 =  0 ; ?>
                            @endif
                        </div>

                        <div style="width:20%;" class="d-flex justify-content-between">
                            @if($barOrRecord->status == 0)
                                <button style="width:48%;" class="btn btn-outline-success statusChangeBtns{{$barOrRecord->id}}" 
                                    onclick="acceptBarSerRecord('{{$barOrRecord->id}}', '{{$barOrRecord->forDate}}', '{{$workerTermins01}}', '{{$workerTermins02}}',
                                    '{{$workerTermins03}}', '{{$workerTermins04}}', '{{$workerTermins05}}', '{{$workerTermins06}}', '{{$workerTermins07}}',
                                    '{{$workerTermins08}}', '{{$workerTermins09}}', '{{$workerTermins10}}', '{{$workerTermins11}}', '{{$workerTermins12}}')">
                                <strong>{{__('adminP.accept')}}</strong></button>

                                <button style="width:48%;" class="btn btn-outline-danger statusChangeBtns{{$barOrRecord->id}}" 
                                    onclick="declineBarSerRecord('{{$barOrRecord->id}}')"><strong>{{__('adminP.refused')}}</strong></button>
                            @endif
                        </div>

                        <div style="width:100%;" class="mt-1">
                            @if(BarbershopServiceOrdersRecords::where([['status', '!=' , '0'],['forWorker', $barOrRecord->forWorker],['forDate',$barOrRecord->forDate]])->get()->count() == 0)
                            <h3 class="color-qrorpa text-center"><strong>{{__('adminP.noReservationsThisDate')}} {{ BarbershopWorker::find($barOrRecord->forWorker)->emri}}</strong></h3>
                            @elseif($barOrRecord->status == 0)
                                <div class="d-flex flex-wrap justify-content-start mt-1">
                                <h4 style="width:100%;" class="color-qrorpa">{{BarbershopWorker::find($barOrRecord->forWorker)->emri}} {{__('adminP.reservationsForThisDate')}}</h4>

                                <?php 
                                    switch(date('w', strtotime($barOrRecord->forDate))){
                                        case '1' : $forDay='d1'; break; 
                                        case '2' : $forDay='d2'; break; 
                                        case '3' : $forDay='d3'; break; 
                                        case '4' : $forDay='d4'; break; 
                                        case '5' : $forDay='d5'; break; 
                                        case '6' : $forDay='d6'; break; 
                                        case '0' : $forDay='d0'; break; 
                                    }
                                ?>

                                @foreach(BarbershopWorkerTerminet::where([['worker', $barOrRecord->forWorker],['theDay',$forDay]])->get()->sortBy('id') as $wrTers)
                                    @if(BarbershopWorkerTerminBusy::where([['workerTerminID',$wrTers->id],['date',$barOrRecord->forDate]])->first() != NULL)
                                        <button style="width:10%;" class="btn btn-danger mr-1 mb-1">{{$wrTers->startT}} - {{$wrTers->endT}}</button>
                                    @else
                                        <button style="width:10%;" class="btn btn-outline-success mr-1 mb-1">{{$wrTers->startT}} - {{$wrTers->endT}}</button>
                                    @endif
                                @endforeach
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach

            </div>
        @endforeach
    @endif
</section>








<script>


    function acceptBarSerRecord(barSerRecID, forDate, worTerID01, worTerID02, worTerID03, worTerID04, worTerID05, worTerID06, worTerID07, worTerID08, worTerID09, worTerID10, worTerID11, worTerID12){
        $('.statusChangeBtns'+barSerRecID).attr('disabled','true');
        $.ajax({
			url: '{{ route("barService.acceptBarSerRec") }}',
			method: 'post',
			data: {
                id: barSerRecID,
                terForDate: forDate,
                worTer01: worTerID01,
                worTer02: worTerID02,
                worTer03: worTerID03,
                worTer04: worTerID04,
                worTer05: worTerID05,
                worTer06: worTerID06,
                worTer07: worTerID07,
                worTer08: worTerID08,
                worTer09: worTerID09,
                worTer10: worTerID10,
                worTer11: worTerID11,
                worTer12: worTerID12,
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = res.replace(/\s/g, '');
                if(res == 'error'){
                    $('#nukPranohetRezervimi').show(200).delay(5000).hide(200);
                    // alert('nuk pranohet');
                }else{
                    $("#allBarbershopServices").load(location.href+" #allBarbershopServices>*","");
                }
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }

    function declineBarSerRecord(barSerRecID){
        $('.statusChangeBtns'+barSerRecID).attr('disabled','true');
        $.ajax({
			url: '{{ route("barService.declineBarSerRec") }}',
			method: 'post',
			data: {
				id: barSerRecID,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#allBarbershopServices").load(location.href+" #allBarbershopServices>*","");
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }




</script>