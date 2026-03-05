<?php
    use App\Restorant;
    use App\Orders;
    use App\Produktet;
    use Carbon\Carbon;
    $thisRestaurantId = Auth::user()->sFor;
?>

<div class="pb-5">
    <div class="d-flex p-2">
        <p style="width:10%;"><strong>{{__('adminP.time')}}</strong></p>
        <p class="hideTablet" style="width:10%;"><strong>{{__('adminP.table')}}</strong></p>
        <p class="orderRow" style="width:50%;"><strong>{{__('adminP.order')}}</strong></p>
        <p style="width:10%;"><strong>{{__('adminP.total')}}</strong></p>
        <p class="hideTablet" style="width:10%;"><strong>{{__('adminP.payemntMethod')}}</strong></p>
        <p style="width:10%;"><strong>{{__('adminP.status')}}</strong></p>
    </div>
    <hr style="margin-top:-20px">
    <div class="d-flex flex-wrap pr-2 pl-2">
        @foreach(Orders::where('Restaurant', '=', $thisRestaurantId)->whereDate('created_at', Carbon::today())
            ->get()->sortByDesc('created_at') as $ord)
            <?php
                $ordTime = explode(':', explode(' ',$ord->created_at)[1]);
                $kaPerKuzh = false;
            ?>
            @foreach(explode('---8---', $ord->porosia) as $pors)
                @if(count(explode('-8-', $pors)) == 8)
                    <?php
                        if(Produktet::find(explode('-8-', $pors)[7])->doneIn == 6){
                            $kaPerKuzh = true;
                        }
                    ?>
                   
                @endif
            @endforeach
            @if($kaPerKuzh)
                <p style="width:10%; opacity:0.8;" class="pl-2">
                    {{$ordTime[0]}} : {{$ordTime[1]}}

                    <br><br>
                    <span class="pl-1 showTablet" style="font-size:20px; display:none;">
                        <strong>T : {{$ord->nrTable}} </strong>
                    </span>
                
                </p>
                <p class="hideTablet" style="width:10%;">{{$ord->nrTable}}</p>
                <p class="orderRow" style="width:50%;">
                    @foreach(explode('---8---', $ord->porosia) as $pors)
                        <?php
                            $seperatedPro = explode('-8-', $pors);
                        ?>
                        @if(count($seperatedPro) == 8)
                            @if(Produktet::find($seperatedPro[7])->doneIn == 6)
                                {{$seperatedPro[3]}} X / <strong> {{$seperatedPro[0]}} </strong>
                                @if(!empty($seperatedPro[5]))
                                    <span style="opacity:0.7;">( {{$seperatedPro[5]}} )</span>
                                @endif
                                <br>
                                @if(!empty($seperatedPro[6]))
                                    <span style="opacity:0.6">{{__('adminP.comment')}} : {{$seperatedPro[6]}}</span>
                                    <br>
                                @endif
                                @foreach(explode('--0--', $seperatedPro[2]) as $ext99)
                                    @if(!empty(explode('||', $ext99)[0]))
                                        <span style="border-bottom:1px solid lightgray;">| + {{explode('||', $ext99)[0]}} |</span>
                                    @endif
                                @endforeach
                                <br>
                                
                            @endif
                        @endif
                    @endforeach
                </p>
                <p style="width:10%;">{{$ord->shuma}} <sup>{{__('adminP.currencyShow')}}</sup></p>
                <p class="hideTablet" style="width:10%;">{{$ord->payM}}</p>
                <p style="width:10%;">
                
                </p>
               
                <p style="width:100%; border-top:1px solid lightgray;"></p>
            @endif
        @endforeach
    </div>

</div>


<script>
    if (screen.width <= 1024) {
        $('.hideTablet').hide();
        $('.showTablet').show();
        $('.orderRow').css('width','70%');
    }
</script>