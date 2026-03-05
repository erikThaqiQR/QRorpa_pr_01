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

<section class="pl-3 pr-3 pt-2 pb-5">
    <?php $today = date("d-m-Y") ;
            $startDay = date('d-m-Y', strtotime($today. '- 2  days'));
    ?>

    <div class="d-flex justify-content-between">
        @for($i = 0; $i <= 14 ; $i++)
            <?php $dd = date('d-m-Y', strtotime($startDay. ' + '.$i. ' days '));
                  $dd2D = explode('-',$dd);
            ?>
            @if(!isset($_GET['date']) && $dd == $today)
                <a href="barAdmIndexAllConfirmedRez?date={{$dd}}" style="width:6.5%;" class="btn btn-dark" >{{$dd2D[0]}} / {{$dd2D[1]}}</a>
            @elseif(isset($_GET['date']) && $_GET['date'] == $dd)
                <a href="barAdmIndexAllConfirmedRez?date={{$dd}}" style="width:6.5%;" class="btn btn-dark" >{{$dd2D[0]}} / {{$dd2D[1]}}</a>
            @else
                <a href="barAdmIndexAllConfirmedRez?date={{$dd}}" style="width:6.5%;" class="btn btn-outline-dark" >{{$dd2D[0]}} / {{$dd2D[1]}}</a>
            @endif
        @endfor
    </div>

   

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
        <h2 class="color-qrorpa mt-4 mb-4"><strong>{{$showDate}}  " {{$weekDayName}} "</strong></h4>

    <?php $barRecToGet = array();?>

    @foreach(BarbershopWorker::where('toBar',$barID)->get() as $worker)
        <div style="border:1px solid rgb(72,81,87,0.45); border-radius:20px;" class="d-flex flex-wrap justify-content-start mb-2 p-2">
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
                        <button style="width:12%;" class="btn btn-danger mr-1 mb-1" 
                            data-toggle="modal" data-target="#openReservationBar{{$rezB->serviceRecord}}">{{$workerTermin->startT}} - {{$workerTermin->endT}}</button>
                    @else
                        <button style="width:12%;" class="btn btn-outline-success mr-1 mb-1">{{$workerTermin->startT}} - {{$workerTermin->endT}}</button>
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
    <div class="modal" id="openReservationBar{{$barSerRecords->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
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
                        <div style="width:5%;" >
                            {{Form::open(['action' => 'BarbershopAdminController@generateBarSerOrderReceipt', 'method' => 'get']) }}
                                {{ Form::hidden('id', $barSerRecords->id , ['class' => 'form-control ']) }}
                                <button  class="btn"> <i style="color:red;" class="far fa-2x fa-file-pdf pl-1 pt-2 clickable"></i></button>
                            {{Form::close() }}
                        </div>
                        <div style="width:34%;">
                            <h4 style="color:rgb(72,81,87);">{{$barSerRecords->emri}}
                                @if($barSerRecords->type != NULL)
                                    ( {{explode('||',$barSerRecords->type)[1]}} )
                                @endif
                            </h4>
                            <h5 style="color:rgb(72,81,87);">{{$barSerRecords->pershkrimi}}</h5>
                        </div>

                        <div style="width:59%;" class="d-flex flex-wrap justify-content-start">
                            @foreach( BarbershopWorkerTerminBusy::where('serviceRecord',$barSerRecords->id)->get() as $terminet)
                                <?php $terminTime = BarbershopWorkerTerminet::find($terminet->workerTerminID); ?>
                                <button style="width:24%;" class="btn btn-outline-success mr-1 mb-1"><strong>{{$terminTime->startT}} - {{$terminTime->endT}}</strong></button>
                            @endforeach
                        </div>
                        <?php $barSerOr = BarbershopServiceOrder::find($barSerRecords->forSerOrder); ?>

                        <div style="width:39%;">
                            @if($barSerOr->bakshish != 0)
                                <h3 style="color:rgb(72,81,87);" class="text-center" ><strong>
                                    {{$barSerRecords->qmimi}} <sup style="font-size:18px; font-weight:normal;">( + {{$barSerOr->bakshish}} <sup>{{__('adminP.currencyShow')}}</sup> {{__('adminP.tip')}} ) </sup> <sup>{{__('adminP.currencyShow')}}</sup>
                                    </strong>
                                </h3>
                            @else
                                <h3 style="color:rgb(72,81,87);" class="text-center" ><strong>{{$barSerRecords->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong></h3>
                            @endif
                        </div>
                        <div style="width:59%;" class="d-flex flex-wrap justify-content-start">
                            <div style="width:100%;" class="d-flex flex-wrap justify-content-between mt-2">
                                <h3 style="width:33%; color:rgb(72,81,87);" ><strong>{{BarbershopWorker::find($barSerRecords->forWorker)->emri}}</strong></h3>
                                <h3 style="width:33%; color:rgb(72,81,87);" ><strong>{{$barSerRecords->forDate}}</strong></h3>
                                <h3 style="width:33%; color:rgb(72,81,87);" ><strong>{{$barSerRecords->timeNeed}} {{__('adminP.minutes')}}</strong></h3>
                            </div>
                        </div>
                        @if ($barSerRecords->forStudent == 1)
                            <h4 style="color:red; width:100%;" class="text-center p-2"><strong>{{__('adminP.studentPrice')}}</strong></h4>
                        @endif
                    
                        <div style="width:100%; border:1px solid rgb(72,81,87); border-radius:15px;" class="d-flex flex-wrap justify-content-between mt-4 p-2">
                       
                            <p style="color: rgb(72,81,87); width:30%; font-size:22px;" class="text-center"><strong>{{$barSerOr->clName}} {{$barSerOr->clLastname}}</strong></p>
                            <p style="color: rgb(72,81,87); width:35%; font-size:22px;" class="text-center">
                                <strong><i style="color:rgb(39,190,175);" class="fas fa-at"></i> {{$barSerOr->clEmail}}</strong>
                            </p>
                            <p style="color: rgb(72,81,87); width:30%; font-size:22px;" class="text-center">
                                <strong><i style="color:rgb(39,190,175);" class="fas fa-phone-alt"></i> {{$barSerOr->clPhoneNr}}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endforeach

