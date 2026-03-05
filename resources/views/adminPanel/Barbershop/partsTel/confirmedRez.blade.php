<?php
    use App\BarbershopService;
    use App\BarbershopServiceOrder;
    use App\BarbershopServiceOrdersRecords;

    use App\BarbershopWorkerTerminet;
    use App\BarbershopWorker;
    use App\BarbershopWorkerTerminBusy;

    $barID = Auth::user()->sFor;
?>

<!-- $tomorrow_date = date("Y-m-d", strtotime("+ 1 day")); -->

<section class="pl-1 pr-1 pt-2 pb-5">
    <?php   $today = date("d-m-Y") ;
            $startDay = date('d-m-Y', strtotime($today. ' - 2 days'));

            switch(date("m")){
                case '01': $nowMonthName = __('adminP.jan'); break;
                case '02': $nowMonthName = __('adminP.feb'); break;
                case '03': $nowMonthName = __('adminP.march'); break;
                case '04': $nowMonthName = __('adminP.apr'); break;
                case '05': $nowMonthName = __('adminP.may'); break;
                case '06': $nowMonthName = __('adminP.june'); break;
                case '07': $nowMonthName = __('adminP.july'); break;
                case '08': $nowMonthName = __('adminP.aug'); break;
                case '09': $nowMonthName = __('adminP.sept'); break;
                case '10': $nowMonthName = __('adminP.oct'); break;
                case '11': $nowMonthName = __('adminP.nov'); break;
                case '12': $nowMonthName = __('adminP.dec'); break;
            }
    ?>

    @if(!isset($_GET['date']))
        <?php 
            $ditaSet = explode('-',$today);
            $dateToCheck = $ditaSet[2].'-'.$ditaSet[1].'-'.$ditaSet[0];
            $showDate = $today;
        ?>
    @else
        <?php 
            $ditaSet = explode('-',$_GET['date']);
            $dateToCheck = $ditaSet[2].'-'.$ditaSet[1].'-'.$ditaSet[0];
            $showDate = $_GET['date'];
        ?>
    @endif
    <?php
        $dayofweek = date('w', strtotime($dateToCheck));
        switch($dayofweek){
            case '1': $weekDayName = __('adminP.monday'); break;
            case '2': $weekDayName = __('adminP.tuesday'); break;
            case '3': $weekDayName = __('adminP.wednesday'); break;
            case '4': $weekDayName = __('adminP.thursday'); break;
            case '5': $weekDayName = __('adminP.friday'); break;
            case '6': $weekDayName = __('adminP.saturday'); break;
            case '0': $weekDayName = __('adminP.sunday'); break;
        }
    ?>
    <div class="d-flex justify-content-between pl-3 pr-3">
        <h4 style="color:rgb(39,190,175); width:50%;"><strong>{{$nowMonthName}} / {{date("Y")}}</strong></h4>
        <h4 style="color:rgb(39,190,175); width:50%;" class="text-right"><strong>{{$weekDayName}}</strong></h4>
    </div>
    
    <hr>
    <div class="d-flex  justify-content-between">
       
        @for($i = 0; $i <= 8 ; $i++)
            <?php $dd = date('d-m-Y', strtotime($startDay. ' + '.$i.' days'));
                  $dd2D = explode('-',$dd);
            ?>
            @if(!isset($_GET['date']) && $dd == $today)
                <a href="barAdmIndexAllConfirmedRez?date={{$dd}}" style="width:10.7%;" class="btn btn-dark" >{{$dd2D[0]}}</a>
            @elseif(isset($_GET['date']) && $_GET['date'] == $dd)
                <a href="barAdmIndexAllConfirmedRez?date={{$dd}}" style="width:10.7%;" class="btn btn-dark" >{{$dd2D[0]}}</a>
            @else
                <a href="barAdmIndexAllConfirmedRez?date={{$dd}}" style="width:10.7%;" class="btn btn-outline-dark" >{{$dd2D[0]}}</a>
            @endif
        @endfor
    </div>

    <br>
    <?php $barRecToGet = array();?>

    @foreach(BarbershopWorker::where('toBar',$barID)->get() as $worker)
        <div style="border:1px solid rgb(72,81,87,0.45); border-radius:10px;" class="d-flex flex-wrap justify-content-start mb-2 p-1 shadow">
            <?php
                $dayofweek = date('w', strtotime($dateToCheck));
                if($dayofweek == 1){ $allWorkerTer = BarbershopWorkerTerminet::where([['worker',$worker->id],['theDay','d1']])->get()->sortBy('id');}
                else if($dayofweek == 2){ $allWorkerTer = BarbershopWorkerTerminet::where([['worker',$worker->id],['theDay','d2']])->get()->sortBy('id');}
                else if($dayofweek == 3){ $allWorkerTer = BarbershopWorkerTerminet::where([['worker',$worker->id],['theDay','d3']])->get()->sortBy('id');}
                else if($dayofweek == 4){ $allWorkerTer = BarbershopWorkerTerminet::where([['worker',$worker->id],['theDay','d4']])->get()->sortBy('id');}
                else if($dayofweek == 5){ $allWorkerTer = BarbershopWorkerTerminet::where([['worker',$worker->id],['theDay','d5']])->get()->sortBy('id');}
                else if($dayofweek == 6){ $allWorkerTer = BarbershopWorkerTerminet::where([['worker',$worker->id],['theDay','d6']])->get()->sortBy('id');}
                else if($dayofweek == 0){ $allWorkerTer = BarbershopWorkerTerminet::where([['worker',$worker->id],['theDay','d0']])->get()->sortBy('id');}
                else{$allWorkerTer = 'empty';}
            ?>
            <h4 style="width:100%; color: rgb(72,81,87);"><strong>{{$worker->emri}}</strong></h4>
            @if(count($allWorkerTer) > 0)
                @foreach($allWorkerTer as $workerTermin)
                    @if(BarbershopWorkerTerminBusy::where([['workerTerminID',$workerTermin->id],['date',$dateToCheck]])->first() != NULL)
                        <?php $rezB = BarbershopWorkerTerminBusy::where([['workerTerminID',$workerTermin->id],['date',$dateToCheck]])->first(); 
                                array_push($barRecToGet,$rezB->serviceRecord);
                        ?>
                        <button style="width:24.5%; margin-right:0.5%; font-size:11px;" class="btn btn-danger mb-1" 
                            data-toggle="modal" data-target="#openReservationBarTel{{$rezB->serviceRecord}}"><strong>{{$workerTermin->startT}}-{{$workerTermin->endT}}</strong></button>
                    @else
                        <button style="width:24.5%; margin-right:0.5%; font-size:11px;" class="btn btn-outline-success mb-1"><strong>{{$workerTermin->startT}}-{{$workerTermin->endT}}</strong></button>
                    @endif
                @endforeach 
            @else
                <h4 style="color:red; font-weight:bold;">{{__('adminP.notActiveThisDay')}}</h4>
            @endif
        </div>
    @endforeach
       


</section>









@foreach(BarbershopServiceOrdersRecords::whereIn('id',$barRecToGet)->get() as $barSerRecords)
    <!-- The Modal -->
    <div class="modal" id="openReservationBarTel{{$barSerRecords->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
            data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        <div class="modal-dialog modal-xl" >
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header" style="background-color:rgb(39,190,175);">
                    <h4 style="color:white;" class="modal-title"><strong>{{$barSerRecords->emri}}</strong></h4>
                    <button style="color:white;" type="button" class="close" data-dismiss="modal">X</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <div style="width:15%;" >
                            {{Form::open(['action' => 'BarbershopAdminController@generateBarSerOrderReceipt', 'method' => 'get']) }}
                                {{ Form::hidden('id', $barSerRecords->id , ['class' => 'form-control ']) }}
                                <button  class="btn"> <i style="color:red;" class="far fa-2x fa-file-pdf pt-1 clickable"></i></button>
                            {{Form::close() }}
                        </div>
                        <div style="width:45%;">
                            <h5 style="color:rgb(72,81,87);">{{$barSerRecords->emri}}
                                @if($barSerRecords->type != NULL)
                                    ( {{explode('||',$barSerRecords->type)[1]}} )
                                @endif
                            </h5>
                            <h6 style="color:rgb(72,81,87);">{{$barSerRecords->pershkrimi}}</h6>
                            <h4 style="color:rgb(72,81,87);" class="text-center" ><strong>{{$barSerRecords->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong></h4>
                        </div>


                            <?php $startTer = '';?>
                            @foreach( BarbershopWorkerTerminBusy::where('serviceRecord',$barSerRecords->id)->get() as $terminet)
                                <?php   $terminTime = BarbershopWorkerTerminet::find($terminet->workerTerminID);
                                        if($startTer == ''){$startTer = $terminTime->startT; }
                                        $endTer = $terminTime->endT;
                                ?>
                            @endforeach

                        <div style="width:40%;" class="d-flex flex-wrap justify-content-start">
                            <div style="width:100%;" class="d-flex flex-wrap justify-content-between mt-2">
                                <h5 style="color:rgb(72,81,87);" ><strong>{{BarbershopWorker::find($barSerRecords->forWorker)->emri}}</strong></h5>
                                <h5 style="color:rgb(72,81,87); margin-top:-5px;" ><strong>{{$barSerRecords->forDate}}</strong></h5>
                                <h5 style="color:rgb(72,81,87); margin-top:-5px;" ><strong>{{$barSerRecords->timeNeed}} {{__('adminP.minutes')}}</strong></h5>
                                <h5 style="color:rgb(72,81,87); margin-top:-5px;" ><strong>{{$startTer}} > {{$endTer}}</strong></h5>
                            </div>
                        </div>
                    
                        <div style="width:100%; border:1px solid rgb(72,81,87); border-radius:15px;" class="d-flex flex-wrap justify-content-between mt-4 p-2">
                        <?php $barSerOr = BarbershopServiceOrder::find($barSerRecords->forSerOrder) ?>
                            <p style="color: rgb(72,81,87); width:100%; font-size:15px; margin-bottom:-5px; " class="text-center">
                                <strong><i style="color:rgb(39,190,175);" class="fas fa-at"></i> {{$barSerOr->clEmail}}</strong>
                            </p>
                            <p style="color: rgb(72,81,87); width:50%; font-size:15px; margin-bottom:-5px;" class="text-center"><strong>{{$barSerOr->clName}} {{$barSerOr->clLastname}}</strong></p>                            
                            <p style="color: rgb(72,81,87); width:50%; font-size:15px; margin-bottom:-5px;" class="text-center">
                                <strong><i style="color:rgb(39,190,175);" class="fas fa-phone-alt"></i> {{$barSerOr->clPhoneNr}}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endforeach