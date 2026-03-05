<?php

use App\Barbershop;
use App\BarbershopServiceOrder;
    use App\BarbershopServiceOrdersRecords;
    use App\BarbershopWorker;
    use App\BarbershopWorkerTerminet;
    use App\BarbershopWorkerTerminBusy;
    use Carbon\Carbon;

    $thisBartId = Auth::user()->sFor;
    $nowDate = date('Y-m-d');
    $nowDay = date('d');
    $nowMonth = date('m');
    $nowYear = date('Y');
?>
<style>
    .clickable:hover{
        cursor: pointer;
    }


    .qrorpaBtn{
        color: rgb(39,190,175);
        border:1px solid rgb(39,190,175);
        font-weight: bold;
        border-radius: 5px;
    }
    .qrorpaBtn:hover{
        color: white;
        background-color: rgb(39,190,175);
        border:1px solid rgb(39,190,175);
        font-weight: bold;
        border-radius: 5px;
    }


    .chngMonthYearAnchor{
        color: rgb(39,190,175);
        border: 1px solid rgb(39,190,175);
        border-radius: 8px;
        font-weight: bold;
        font-size: 23px;
    }
    .chngMonthYearAnchor:hover{
        color: white;
        background-color: rgb(39,190,175);
        border: 1px solid rgb(39,190,175);
        border-radius: 8px;
        font-weight: bold;
        font-size: 23px;
    }
</style>

    @if(!isset($_GET['mo']))
        <?php $monthToCheck = $nowMonth; ?>
    @else
        <?php $monthToCheck = $_GET['mo']; ?>
    @endif
    <?php
        switch($monthToCheck){
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

















<section class="p-2 ml-1 mr-1 mt-2 mb-5">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <p style="font-size:21px; color:rgb(39,190,175);" class="pt-1"><strong>{{$nowMonthName}} / {{$nowYear}} </strong></p>
        <button class="btn qrorpaBtn" style="font-size:15px;" data-toggle="modal" data-target="#chngMonthYearBarStTel" >{{__('adminP.changeMonthYear')}}</button>
    </div>








    @if(!isset($_GET['mo']))
        <?php
            $monthDate = $nowYear.'-'.$nowMonth.'-01';
            $monthDateEnd = $nowYear.'-'.$nowMonth.'-' . date('t', strtotime($monthDate)); //get end date of month
        ?>
        @while(strtotime($monthDate) <= strtotime($monthDateEnd))
            <?php
                $day_num = date('d', strtotime($monthDate));
                // $day_name = date('l', strtotime($monthDate));
                $monthDate= date("Y-m-d", strtotime("+1 day", strtotime($monthDate)));
            ?>
            @if($nowDay ==  $day_num)
                <button onclick="changeDayTel('{{$day_num}}')" style="width:10%;" class="btn btn-dark mb-1 allDaysBtnTel" id="daysBtn{{$day_num}}Tel">{{$day_num}}</button>
            @else
                <button onclick="changeDayTel('{{$day_num}}')" style="width:10%;" class="btn btn-outline-dark mb-1 allDaysBtnTel" id="daysBtn{{$day_num}}Tel">{{$day_num}}</button>
            @endif
        @endwhile
        <hr>

        <?php
            $monthDate = $nowYear.'-'.$nowMonth.'-01';
            $monthDateEnd = $nowYear.'-'.$nowMonth.'-' . date('t', strtotime($monthDate)); //get end date of month
        ?>

        @while(strtotime($monthDate) <= strtotime($monthDateEnd))
            <?php
                $day_num = date('d', strtotime($monthDate));
            ?>
            @if($nowDay == $day_num)
                <div id="theReservationsShown{{$day_num}}Tel" class="allTheReservationsShownTel">
            @else
                <div id="theReservationsShown{{$day_num}}Tel" class="allTheReservationsShownTel" style="display:none;">
            @endif
                <?php
                    $allBarSerRecs = array();
                    foreach(BarbershopServiceOrdersRecords::whereDate('forDate',$monthDate)->where('status','2')->get()->sortBy('created_at') as $serOrRecords){
                        if(BarbershopServiceOrder::find($serOrRecords->forSerOrder)->toBar == $thisBartId){ array_push($allBarSerRecs,$serOrRecords); }
                    }
                ?>
                @if(count($allBarSerRecs) == 0)
                    <h3 style="color: rgb(39,190,175); font-weight:bold;">{{__('adminP.noReservartionDoucumentThisDay')}}</h3>
                @else
                    @foreach($allBarSerRecs as $bor)
                        <div style="border:1px solid rgb(39,190,175,0.7); border-radius:10px;" class="d-flex flex-wrap justify-content-between p-1 mb-2">

                            <div style="width:45%;">
                                <h5 style="color:rgb(72,81,87);">{{$bor->emri}}
                                    @if($bor->type != NULL)
                                        ( {{explode('||',$bor->type)[1]}} )
                                    @endif
                                </h5>
                                <h5 style="color:rgb(72,81,87);">{{$bor->pershkrimi}}</h5>
                                <h5 style="color:rgb(72,81,87);" class="text-center" ><strong>{{$bor->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong></h5>
                            </div>
                            <div style="width:45%;">
                                <h5 style="color:rgb(72,81,87);" ><strong>{{BarbershopWorker::find($bor->forWorker)->emri}}</strong></h5>
                                <h5 style="color:rgb(72,81,87); margin-top:-10px;" ><strong>{{$bor->forDate}}</strong></h5>
                                <h5 style="color:rgb(72,81,87); margin-top:-10px;" ><strong>{{$bor->timeNeed}} {{__('adminP.minutes')}}</strong></h5>
                                <?php $stT=''; $enT='';?>
                                @foreach( BarbershopWorkerTerminBusy::where('serviceRecord',$bor->id)->get() as $terminet)
                                    <?php $terminTime = BarbershopWorkerTerminet::find($terminet->workerTerminID); 
                                        if($stT == ''){$stT = $terminTime->startT; }
                                        $enT = $terminTime->endT;?>
                                @endforeach
                                <h5 style="color:rgb(72,81,87); margin-top:-10px;" ><strong>{{$stT}} > {{$enT}}</strong></h5>
                            </div>


                            <div style="width:10%;">
                                {{Form::open(['action' => 'BarbershopAdminController@generateBarSerOrderReceipt', 'method' => 'get']) }}
                                    {{ Form::hidden('id', $bor->id , ['class' => 'form-control ']) }}
                                    <button  class="btn"> <i style="color:red;" class="far fa-2x fa-file-pdf clickable pt-1 pr-1"></i></button>
                                {{Form::close() }}
                            </div>
                        
                            <div style="width:100%; border:1px solid rgb(72,81,87); border-radius:15px;" class="d-flex flex-wrap justify-content-between mt-1">
                                <?php $barSerOr = BarbershopServiceOrder::find($bor->forSerOrder) ?>
                               
                                <p style="color: rgb(72,81,87); width:100%; font-size:14px; margin-bottom:2px;" class="text-center">
                                    <strong><i style="color:rgb(39,190,175);" class="fas fa-at"></i> {{$barSerOr->clEmail}}</strong>
                                </p>
                                <p style="color: rgb(72,81,87); width:50%; font-size:14px; margin-bottom:-5px;" class="text-center">
                                    <strong>{{$barSerOr->clName}} {{$barSerOr->clLastname}}</strong>
                                </p>
                                <p style="color: rgb(72,81,87); width:50%; font-size:14px; margin-bottom:2px;" class="text-center">
                                    <strong><i style="color:rgb(39,190,175);" class="fas fa-phone-alt"></i> {{$barSerOr->clPhoneNr}}</strong>
                                </p>
                            </div>
                        </div>
                        <hr style="color: rgb(39,190,175);">
                    @endforeach
                @endif

            </div>

            <?php
                $monthDate= date("Y-m-d", strtotime("+1 day", strtotime($monthDate)));
            ?> 
        @endwhile






    <!-- [mo] set start -->
    @else
        <?php
            $monthDate = $_GET['ye'].'-'.$_GET['mo'].'-01';
            $monthDateEnd = $_GET['ye'].'-'.$_GET['mo'].'-' . date('t', strtotime($monthDate)); //get end date of month
        ?>
        @while(strtotime($monthDate) <= strtotime($monthDateEnd))
            <?php
                $day_num = date('d', strtotime($monthDate));
                // $day_name = date('l', strtotime($monthDate));
                $monthDate= date("Y-m-d", strtotime("+1 day", strtotime($monthDate)));
            ?>
            <button onclick="changeDayTel('{{$day_num}}')" style="width:10%;" class="btn btn-outline-dark mb-2 allDaysBtnTel" id="daysBtn{{$day_num}}Tel">{{$day_num}}</button>
        @endwhile
        <?php
            $monthDate = $_GET['ye'].'-'.$_GET['mo'].'-01';
            $monthDateEnd = $_GET['ye'].'-'.$_GET['mo'].'-' . date('t', strtotime($monthDate)); //get end date of month
        ?>
        @while(strtotime($monthDate) <= strtotime($monthDateEnd))
            <?php
                $day_num = date('d', strtotime($monthDate));
            ?>
            <div id="theReservationsShown{{$day_num}}Tel" class="allTheReservationsShownTel">
                <?php
                    $allBarSerRecs = array();
                    foreach(BarbershopServiceOrdersRecords::whereDate('forDate',$monthDate)->where('status','2')->get()->sortBy('created_at') as $serOrRecords){
                        if(BarbershopServiceOrder::find($serOrRecords->forSerOrder)->toBar == $thisBartId){ array_push($allBarSerRecs,$serOrRecords); }
                    }
                ?>
                @foreach($allBarSerRecs as $bor)
                        <div style="border:1px solid rgb(39,190,175,0.7); border-radius:10px;" class="d-flex flex-wrap justify-content-between p-1 mb-2">

                            <div style="width:45%;">
                                <h5 style="color:rgb(72,81,87);">{{$bor->emri}}
                                    @if($bor->type != NULL)
                                        ( {{explode('||',$bor->type)[1]}} )
                                    @endif
                                </h5>
                                <h5 style="color:rgb(72,81,87);">{{$bor->pershkrimi}}</h5>
                                <h5 style="color:rgb(72,81,87);" class="text-center" ><strong>{{$bor->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong></h5>
                            </div>
                            <div style="width:45%;">
                                <h5 style="color:rgb(72,81,87);" ><strong>{{BarbershopWorker::find($bor->forWorker)->emri}}</strong></h5>
                                <h5 style="color:rgb(72,81,87); margin-top:-10px;" ><strong>{{$bor->forDate}}</strong></h5>
                                <h5 style="color:rgb(72,81,87); margin-top:-10px;" ><strong>{{$bor->timeNeed}} {{__('adminP.minutes')}}</strong></h5>
                                <?php $stT=''; $enT='';?>
                                @foreach( BarbershopWorkerTerminBusy::where('serviceRecord',$bor->id)->get() as $terminet)
                                    <?php $terminTime = BarbershopWorkerTerminet::find($terminet->workerTerminID); 
                                        if($stT == ''){$stT = $terminTime->startT; }
                                        $enT = $terminTime->endT;?>
                                @endforeach
                                <h5 style="color:rgb(72,81,87); margin-top:-10px;" ><strong>{{$stT}} > {{$enT}}</strong></h5>
                            </div>


                            <div style="width:10%;">
                                {{Form::open(['action' => 'BarbershopAdminController@generateBarSerOrderReceipt', 'method' => 'get']) }}
                                    {{ Form::hidden('id', $bor->id , ['class' => 'form-control ']) }}
                                    <button  class="btn"> <i style="color:red;" class="far fa-2x fa-file-pdf clickable pt-1 pr-1"></i></button>
                                {{Form::close() }}
                            </div>
                        
                            <div style="width:100%; border:1px solid rgb(72,81,87); border-radius:15px;" class="d-flex flex-wrap justify-content-between mt-1">
                                <?php $barSerOr = BarbershopServiceOrder::find($bor->forSerOrder) ?>
                               
                                <p style="color: rgb(72,81,87); width:100%; font-size:14px; margin-bottom:2px;" class="text-center">
                                    <strong><i style="color:rgb(39,190,175);" class="fas fa-at"></i> {{$barSerOr->clEmail}}</strong>
                                </p>
                                <p style="color: rgb(72,81,87); width:50%; font-size:14px; margin-bottom:-5px;" class="text-center">
                                    <strong>{{$barSerOr->clName}} {{$barSerOr->clLastname}}</strong>
                                </p>
                                <p style="color: rgb(72,81,87); width:50%; font-size:14px; margin-bottom:2px;" class="text-center">
                                    <strong><i style="color:rgb(39,190,175);" class="fas fa-phone-alt"></i> {{$barSerOr->clPhoneNr}}</strong>
                                </p>
                            </div>
                        </div>
                        <hr style="color: rgb(39,190,175);">
                    @endforeach
                
            </div>


            <?php
                $monthDate= date("Y-m-d", strtotime("+1 day", strtotime($monthDate)));
            ?> 
        @endwhile

    @endif














    <!-- chnage month/Year Modal -->
    <div class="modal" id="chngMonthYearBarStTel" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header" style="background-color:rgb(39,190,175);">
                    <h4 style="color:white;" class="modal-title"><strong>{{__('adminP.activeMonthYearHairSalon')}}</strong></h4>
                    <button style="color:white;" type="button" class="close" data-dismiss="modal">X</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body d-flex flex-wrap justify-content-start">
                    <?php
                        $monthCount = Carbon::now()->month;
                        $yearCount = Carbon::now()->year;

                        $resCreated =explode(' ',Barbershop::find(Auth::user()->sFor)->created_at)[0];
                        $resCreatedM = explode('-', $resCreated)[1];
                        $resCreatedY = explode('-', $resCreated)[0];

                        while(true){
                            if(($monthCount >= $resCreatedM && $yearCount == $resCreatedY) || $yearCount > $resCreatedY ){
                                echo   "<a class='text-center pt-1 pb-1 mb-2 chngMonthYearAnchor' style='width:100%; margin-right:0.333%;' 
                                        href='barAdmShowReservationsByMonth?mo=".$monthCount."&ye=".$yearCount."'>
                                        ( ".$monthCount." )".getMonthName($monthCount)." ".$yearCount."</a>";
                                        
                                if($monthCount == 1){
                                    $yearCount--;
                                    $monthCount=12;
                                }else{
                                    $monthCount--;
                                }
                            }else{ break; }
                        }
                    ?>
                </div>

            </div>
        </div>
    </div>

</section>








<script>
    function changeDayTel(dayNr){
        $('.allTheReservationsShownTel').hide(200);
        $('#theReservationsShown'+dayNr+'Tel').show(200);

        $('.allDaysBtnTel').attr('class','btn btn-outline-dark mb-2 allDaysBtnTel');
        $('#daysBtn'+dayNr+'Tel').attr('class','btn btn-dark mb-2 allDaysBtnTel');
    }

</script>