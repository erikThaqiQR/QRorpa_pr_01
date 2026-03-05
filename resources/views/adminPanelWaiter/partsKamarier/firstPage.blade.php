<?php

    use Illuminate\Support\Facades\Auth;
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Kellner']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('admWoMng.ordersStatisticsWaiter01'));
        exit();
    }
    use App\Restorant;
    use App\Waiter;
    use Carbon\Carbon;
    use App\Events\CallWaiter;
    $thisRestaurantId = Auth::user()->sFor;
?>
<style>

    .noBorder:active {
        outline: none;
    }
    .noBorder:focus {
        outline: none;
        box-shadow: none;
    }

</style>
<section class="p-3" id="sectionKamarieri">

    <div class="d-flex">
        <!-- <h4 style="width:50%" class="color-qrorpa text-left pl-5"> Pendding : <span>0</span> </h4> -->
        <h4 style="width:100%" class="color-qrorpa text-right pr-5"> {{Auth::user()->name}} </h4>
    </div>
    <div class="d-flex justify-content-between pt-4">
        <h5 style="width:10%" class="color-qrorpa text-center"></h5>
        <h5 style="width:12%" class="color-qrorpa text-center"><strong>{{__('adminP.table')}}</strong></h5>
        <h5 style="width:55%" class="color-qrorpa text-left"><strong>{{__('adminP.comment')}}</strong></h5>
        <h5 style="width:20%" class="color-qrorpa text-center"><strong>{{__('adminP.status')}}</strong></h5>
    </div>
    <hr style="margin-top:-5px;">
    <div id="allTheCalls">
        @foreach(Waiter::whereDate('created_at', Carbon::today())->where([['toRes', $thisRestaurantId],['toWaiter',Auth::user()->id]])->whereIn('tableNr',$myTablesWaiter)->get()->sortByDesc('created_at') as $callsW)
            <!-- <p style="width:25%" class="color-qrorpa text-center pt-2">{{Restorant::find($callsW->toRes)->emri}}</p> -->
            <div style="border: 1px solid rgba(0,0,0,0.1); border-radius:5px;" class="d-flex justify-content-between flex-wrap mb-1">
                <?php
                    $timeHr = explode(':',explode(' ', $callsW->created_at)[1]);
                ?>

                <p style="width:10%; margin:0px; font-size:22px;" class="text-center pt-2"><strong>{{$timeHr[0]}}:{{$timeHr[1]}}</strong></p>
                <p style="width:12%; margin:0px; font-size:22px;" class="color-qrorpa text-center pt-2"><strong>{{$callsW->tableNr}}</strong></p>
                <p style="width:55%; margin:0px; font-size:18px;" class=" text-left pt-2"><strong>{{$callsW->comment}}</strong></p>
                <p style="width:20%; margin:0px;" class="color-qrorpa text-center">
                    @if($callsW->status == 0)
                        <button class="btn btn-block btn-danger noBorder" id="chThisSHowKam{{$callsW->id}}" onclick="changeStatDesk('{{$callsW->id}}')">
                            <strong>{{__('adminP.wait')}}</strong> 
                        </button>
                    @else
                        <button class="btn btn-block btn-success noBorder" id="chThisSHowKam{{$callsW->id}}" onclick="changeStatDesk('{{$callsW->id}}')">
                            <strong>{{__('adminP.ready')}}</strong> 
                        </button>
                    @endif
                </p>
            </div>
        @endforeach
        
    </div>

    <script>

        function changeStatDesk(caId){
            $.ajax({
                url: '{{ route("waiter.chStatus") }}',
                method: 'post',
                data: {
                    id: caId,
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    $("#allTheCalls").load(location.href+" #allTheCalls>*","");
                    $("#DashSideMenuDesktop").load(location.href+" #DashSideMenuDesktop>*","");
                },
                error: (error) => {console.log(error);}
            });
        }
   
    </script>

</section>