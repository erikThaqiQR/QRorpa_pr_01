<section style="background-color:white;" id="sectionKamarieriTel">
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

    <input type="hidden" value="{{$thisRestaurantId}}" id="thisRestorantWaiterTel">

    <div class="color-white b-qrorpa text-center mt-1 mb-2 d-flex justify-content-space-between">
        <h5 style="width:45%;" class="pt-2">{{Auth::user()->name}}</h5>
        <h5 style="width:45%;" class="pt-2 text-right" id="waiterTelTime">.</h5>
        <hr>
    </div>
    
    <hr style="margin-top:-5px;">
    <div id="allTheCallsTel" class="p-1">
        @foreach(Waiter::whereDate('created_at', Carbon::today())->where([['toRes', $thisRestaurantId],['toWaiter',Auth::user()->id]])->whereIn('tableNr',$myTablesWaiter)->get()->sortByDesc('created_at') as $callsW)
            <div style="border: 1px solid rgba(0,0,0,0.4); border-radius:5px;" class="d-flex justify-content-between flex-wrap mb-1 p-1">
                <?php
                    $timeHr = explode(':',explode(' ', $callsW->created_at)[1]);
                ?>
                <p style="width:39%; font-size:20px;" class=" text-center"><strong>{{$timeHr[0]}}:{{$timeHr[1]}}</strong></p>
                <p style="width:59%; font-size:20px;" class="color-qrorpa text-center "><u>Tisch:</u> <strong>{{$callsW->tableNr}}</strong></p>
                
                @if(!empty($callsW->comment))
                    <p style="width:100%; opacity:0.85; margin-top:-20px;" class=" text-left pt-2"><u>{{__('adminP.comment')}}:</u> {{$callsW->comment}}</p>
                @endif
                <p style="width:100%; margin:0px;" class="color-qrorpa text-center mb-1">
                    @if($callsW->status == 0)
                        <button class="btn btn-block btn-danger noBorder" id="chThisSHowKam{{$callsW->id}}" onclick="changeStat('{{$callsW->id}}')">
                            <strong>{{__('adminP.wait')}}</strong> 
                        </button>
                    @else
                        <button class="btn btn-block btn-success noBorder" id="chThisSHowKam{{$callsW->id}}" onclick="changeStat('{{$callsW->id}}')">
                            <strong>{{__('adminP.ready')}}</strong> 
                        </button>
                    @endif
                </p>
                <hr>
            </div>
        @endforeach
      
    </div>

</section>


                <script>
                    function pad(d) {
                        return (d < 10) ? '0' + d.toString() : d.toString();
                    }

                    function showTime() {
                        var today = new Date()
                        var time = pad(today.getHours())+':'+pad(today.getMinutes())+':'+pad(today.getSeconds());
                        $("#waiterTelTime").html(time);
                    }

                    $( document ).ready(function() {
                        setInterval(showTime, 1000);
                    });
                
                    function changeStat(caId,stat){
                        // alert('i got this :'+caId);
                        $.ajax({
                            url: '{{ route("waiter.chStatus") }}',
                            method: 'post',
                            data: {
                                id: caId,
                                _token: '{{csrf_token()}}'
                            },
                            success: () => {
                                $("#allTheCallsTel").load(location.href+" #allTheCallsTel>*","");
                            },
                            error: (error) => {
                                console.log(error);
                                alert($('#oopsSomethingWrong').val())
                            }
                        })
                    }

                    var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {
                        cluster: 'eu'
                    });
                    var channel = pusher.subscribe('WaiterChanel');
                    channel.bind('App\\Events\\CallWaiter', function(data) {

                        var dataJ =  JSON.stringify(data);
                        var dataJ2 =  JSON.parse(dataJ);

                        if($('#thisRestorantWaiterTel').val() == dataJ2.text){
                            $('#sectionKamarieriTel').load('/callWaiterIndex #sectionKamarieriTel', function() {
                            });
                        }
                    });
                </script>
