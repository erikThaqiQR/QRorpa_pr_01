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

<section class="pl-1 pr-1 pt-2 pb-5" id="allBarbershopServicesTel">
    <h4 class="color-qrorpa text-center"><strong> {{__('adminP.pendingReservations')}} </strong></h4>
    <hr>
    <div class="alert alert-danger text-center p-2 mt-2 mb-2" style="font-size:22px; display:none;" id="nukPranohetRezervimiTel">
        <strong>{{__('adminP.canNotReservations')}}</strong>
    </div>

    @if(BarbershopServiceOrder::where([['status','0'],['toBar', $barID]])->get()->count() == 0)
        <h4 style="color:rgb(72,81,87);"><strong>{{__('adminP.noPendingReservationsCheckLater')}}</strong></h4>
    @else
        @foreach(BarbershopServiceOrder::where([['status','0'],['toBar', $barID]])->get()->sortByDesc('created_at') as $barOr)
            <div class="d-flex flex-wrap justify-content-between p-1 mb-2 shadow " style="border:1px solid rgb(72,81,87); border-radius:15px;">
                <h6 class="color-text text-left pl-3" style="width:100%"><strong>{{ $barOr->clName }} {{ $barOr->clLastname }} " {{ $barOr->clEmail }} "</strong></h6>

                <h5 class="color-text text-center" style="width:50%"><strong>+{{ $barOr->clPhoneNr }} </strong></h5>
                @if($barOr->bakshish > 0)
                    <h5 class="color-text text-center" style="width:50%"><strong>
                        {{$barOr->shumaTot - $barOr->bakshish}} ({{$barOr->bakshish}} <i class="fas fa-coins"></i>) > {{ $barOr->shumaTot }} <sup>{{__('adminP.currencyShow')}}</sup>
                    </strong></h5>
                @else
                    <h5 class="color-text text-center" style="width:50%"><strong>
                        {{ $barOr->shumaTot }} <sup>{{__('adminP.currencyShow')}}</sup>
                    </strong></h5>
                @endif

                @foreach(BarbershopServiceOrdersRecords::where('forSerOrder', $barOr->id)->get()->sortByDesc('created_at') as $barOrRecord)
                    @if($barOrRecord->status == 1)
                        <div style="width:100%; border:1px solid rgb(39,190,175,0.6); border-radius:10px; background-color:rgb(255,0,0,0.2);" class="ml-1 mr-1 mt-2 p-2 d-flex flex-wrap justify-content-between">
                    @elseif($barOrRecord->status == 2)
                        <div style="width:100%; border:1px solid rgb(39,190,175,0.6); border-radius:10px; background-color:rgb(0,255,0,0.2);"" class="ml-1 mr-1 mt-2 p-2 d-flex flex-wrap justify-content-between">
                    @else
                        <div style="width:100%; border:1px solid rgb(39,190,175,0.6); border-radius:10px;" class="ml-1 mr-1 mt-2 p-2 d-flex flex-wrap justify-content-between">
                    @endif
                            <div style="width:50%;">
                                <p><strong>{{$barOrRecord->emri}}
                                    @if($barOrRecord->type != NULL)
                                        ( {{explode('||', $barOrRecord->type)[1]}} )
                                    @endif
                                </strong></p>
                                <p style="margin-top:-15px;">{{$barOrRecord->pershkrimi}}</p>
                                <p style="margin-top:-15px; font-size:19px;"><strong>{{$barOrRecord->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong></p>
                            </div>

                            <?php 
                                $workerTer01 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin);
                                $startTermin = $workerTer01->startT;  
                                $endTermin = $workerTer01->endT; 
                                $worTerminDiff = 15;  $workerTermins01 =  $workerTer01->id;
                            ?>
                            @if($barOrRecord->timeNeed > $worTerminDiff)
                                <?php   $workerTer02 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 1); 
                                        $workerTermins02 =  $workerTer02->id; $endTermin = $workerTer02->endT;?>
                            @else
                                <?php  $workerTermins02 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (2 * $worTerminDiff))
                                <?php   $workerTer03 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 2); 
                                        $workerTermins03 =  $workerTer03->id; $endTermin = $workerTer03->endT;?>
                            @else
                                <?php  $workerTermins03 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (3 * $worTerminDiff))
                                <?php   $workerTer04 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 3); 
                                        $workerTermins04 =  $workerTer04->id; $endTermin = $workerTer04->endT;?>
                            @else
                                <?php  $workerTermins04 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (4 * $worTerminDiff))
                                <?php   $workerTer05 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 4); 
                                        $workerTermins05 =  $workerTer05->id; $endTermin = $workerTer05->endT;?>
                            @else
                                <?php  $workerTermins05 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (5 * $worTerminDiff))
                                <?php   $workerTer06 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 5); 
                                        $workerTermins06 =  $workerTer06->id; $endTermin = $workerTer06->endT;?>
                            @else
                                <?php  $workerTermins06 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (6 * $worTerminDiff))
                                <?php   $workerTer07 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 6); 
                                        $workerTermins07 =  $workerTer07->id; $endTermin = $workerTer07->endT;?>
                            @else
                                <?php  $workerTermins07 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (7 * $worTerminDiff))
                                <?php   $workerTer08 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 7); 
                                        $workerTermins08 =  $workerTer08->id; $endTermin = $workerTer08->endT;?>
                            @else
                                <?php  $workerTermins08 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (8 * $worTerminDiff))
                                <?php   $workerTer09 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 8); 
                                        $workerTermins09 =  $workerTer09->id; $endTermin = $workerTer09->endT;?>
                            @else
                                <?php  $workerTermins09 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (9 * $worTerminDiff))
                                <?php   $workerTer10 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 9); 
                                        $workerTermins10 =  $workerTer10->id; $endTermin = $workerTer10->endT;?>
                            @else
                                <?php  $workerTermins10 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (10 * $worTerminDiff))
                                <?php   $workerTer11 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 10); 
                                        $workerTermins11 =  $workerTer11->id; $endTermin = $workerTer11->endT;?>
                            @else
                                <?php  $workerTermins11 = 0; ?>
                            @endif
                            @if($barOrRecord->timeNeed > (11 * $worTerminDiff))
                                <?php   $workerTer12 = BarbershopWorkerTerminet::find($barOrRecord->forWorkerTermin + 11); 
                                        $workerTermins12 =  $workerTer12->id; $endTermin = $workerTer12->endT;?>
                            @else
                                <?php  $workerTermins12 = 0; ?>
                            @endif

                            <div style="width:50%;">
                                <?php $forDate2D = explode('-',$barOrRecord->forDate)?>
                                <p><strong>{{$forDate2D[2]}} / {{$forDate2D[1]}} / {{$forDate2D[0]}}</strong></p>
                                <p style="margin-top:-15px;"><strong>{{$barOrRecord->timeNeed}}</strong> {{__('adminP.minutes')}}</p>
                                <p style="margin-top:-15px;"><strong>{{BarbershopWorker::find($barOrRecord->forWorker)->emri}}</strong></p>
                                <p style="margin-top:-15px;"><strong>{{$startTermin}} => {{$endTermin}}</strong></p>
                            </div>

                            @if($barOrRecord->status == 0)
                                <button style="width:48%;" class="btn btn-outline-success statusChangeBtns{{$barOrRecord->id}}" 
                                    onclick="acceptBarSerRecordTel('{{$barOrRecord->id}}', '{{$barOrRecord->forDate}}', '{{$workerTermins01}}', '{{$workerTermins02}}',
                                    '{{$workerTermins03}}', '{{$workerTermins04}}', '{{$workerTermins05}}', '{{$workerTermins06}}', '{{$workerTermins07}}',
                                    '{{$workerTermins08}}', '{{$workerTermins09}}', '{{$workerTermins10}}', '{{$workerTermins11}}', '{{$workerTermins12}}')">
                                <strong>{{__('adminP.accept')}}</strong></button>

                                <button style="width:48%;" class="btn btn-outline-danger statusChangeBtns{{$barOrRecord->id}}" 
                                    onclick="declineBarSerRecordTel('{{$barOrRecord->id}}')"><strong>{{__('adminP.refused')}}</strong></button>
                            @endif


                        </div>
                @endforeach
            </div>
        @endforeach
    @endif

</section>



<script>

    function acceptBarSerRecordTel(barSerRecID, forDate, worTerID01, worTerID02, worTerID03, worTerID04, worTerID05, worTerID06, worTerID07, worTerID08, worTerID09, worTerID10, worTerID11, worTerID12){
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
                    $('#nukPranohetRezervimiTel').show(200).delay(5000).hide(200);
                    // alert('nuk pranohet');
                }else{
                    $("#allBarbershopServicesTel").load(location.href+" #allBarbershopServicesTel>*","");
                }
            },
            error: (error) => {
                console.log(error);
                alert($('#pleaseUpdateAndTryAgain').val());
            }
        });
    }

    function declineBarSerRecordTel(barSerRecID){
        $('.statusChangeBtns'+barSerRecID).attr('disabled','true');
        $.ajax({
            url: '{{ route("barService.declineBarSerRec") }}',
            method: 'post',
            data: {
                id: barSerRecID,
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $("#allBarbershopServicesTel").load(location.href+" #allBarbershopServicesTel>*","");
            },
            error: (error) => {
                console.log(error);
                alert($('#pleaseUpdateAndTryAgain').val());
            }
        });
    }

</script>