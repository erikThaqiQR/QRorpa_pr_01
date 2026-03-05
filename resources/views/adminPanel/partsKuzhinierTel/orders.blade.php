<?php
    use App\Restorant;
    use App\Orders;
    use App\Produktet;
    use Carbon\Carbon;
    $thisRestaurantId = Auth::user()->sFor;
?>
<style>
    .simpleAnchor{
        text-decoration:none;
        color:black
    }
    .simpleAnchor:hover{
        text-decoration:none;
        color:black
    }
    .simpleAnchor:active{
        text-decoration:none;
        color:black
    }
</style>
<div class="p-2">
    <div class="d-flex">
        <p class="simpleAnchor" style="width:25%;" href="#"> {{__('adminP.time')}} </p>
        <p class="simpleAnchor" style="width:50%;" href="#"> {{___('adminP.products')}} </p>
        <p class="simpleAnchor" style="width:25%;" href="#"> {{__('adminP.status')}} </p>
    </div>
    <hr style="margin-top:-10px;">
    <div class="d-flex flex-wrap">
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



                        <!-- The Modal -->
                        <div class="modal" id="orderOp{{$ord->id}}">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius:20px;">

                                    <!-- Modal Header -->
                                    <div class="modal-header d-flex">
                                            <span style="width:15%"><strong>T : {{$ord->nrTable}}</strong></span>
                                            <span style="width:20%">{{$ordTime[0]}} : {{$ordTime[1]}}</span>
                                            <span style="width:30%">{{$ord->shuma}} <sup>{{__('adminP.currencyShow')}}</sup> {{$ord->payM}}</span>
                                            <span style="width:25%" class="text-center">{{$ord->statusi}}</span>
                                            <a href="generatePDF/{{$order->id}}">Print</a>
                                        <button style="width:10%" type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="modal-body">
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
                                                        <span style="opacity:0.6">{{__('adminP.comment')}}: {{$seperatedPro[6]}}</span>
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
                                    </div>


                                </div>
                            </div>
                        </div>







                <a class="simpleAnchor mt-2" style="width:20%;" href="#" data-toggle="modal" data-target="#orderOp{{$ord->id}}"> 
                {{$ordTime[0]}} : {{$ordTime[1]}}
                <br>
                <span class="pl-1 " style="font-size:18px;">
                    <strong>T : {{$ord->nrTable}} </strong>
                </span>
                </a>
                <a href="generatePDF/{{$order->id}}"><i class="fa fa-file-pdf-o fa-2x"></i></a>
                <a class="simpleAnchor mt-2" style="width:50%;" href="#" data-toggle="modal" data-target="#orderOp{{$ord->id}}">
                    <?php
                        $count = 1;
                    ?>
                    @foreach(explode('---8---', $ord->porosia) as $thisProOrd)
                        <?php
                          $seperatedPro = explode('-8-', $thisProOrd);
                        ?>
                        @if(count($seperatedPro) == 8)
                            @if(Produktet::find($seperatedPro[7])->doneIn == 6)
                                @if($count++ < 3)
                                    {{$seperatedPro[0]}} <br>
                                @else
                                    ...
                                    @break
                                @endif
                            @endif
                        @endif
                       
                    @endforeach
                </a>
                <a class="simpleAnchor mt-2" style="width:30%;" href="#">
                            @if($ord->statusi == 0)                 
                                <button class="btn btn-warning btn-block">
                                    {{__('adminP.waitingLine')}}
                                </button>           
                            @elseif($ord->statusi == 1)
                                <button class="btn btn-info btn-block">
                                    {{__('adminP.confirmed')}}
                                </button>
                            @elseif($ord->statusi == 2)
                                <button class="btn btn-danger btn-block">
                                    {{__('adminP.canceled')}}
                                </button>
                            @elseif($ord->statusi == 3)
                                <button class="btn btn-success btn-block">
                                    {{__('adminP.finished')}}
                                </button>
                            @endif 
                </a>
            @endif

        @endforeach
    </div>
</div>