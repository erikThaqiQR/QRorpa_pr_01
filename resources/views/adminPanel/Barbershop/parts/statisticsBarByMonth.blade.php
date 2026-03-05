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


<section class="p-2 ml-2 mr-2 mt-3 mb-5">

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


    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 style="width:80%; color:rgb(72,81,87);"><strong>{{__('adminP.reservationsPerMonth')}} 
            <span style="font-size:28px; color:rgb(39,190,175);" class="ml-4">" {{$nowMonthName}} / {{$nowYear}} "</span></strong>
        </h3>
        <button class="btn qrorpaBtn" style="font-size:25px;" data-toggle="modal" data-target="#chngMonthYearBarSt" >{{__('adminP.changeMonthYear')}}</button>
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
                <button onclick="changeDay('{{$day_num}}')" style="width:8%;" class="btn btn-dark mb-2 allDaysBtn" id="daysBtn{{$day_num}}">{{$day_num}} / {{$nowMonthName}}</button>
            @else
                <button onclick="changeDay('{{$day_num}}')" style="width:8%;" class="btn btn-outline-dark mb-2 allDaysBtn" id="daysBtn{{$day_num}}">{{$day_num}} / {{$nowMonthName}}</button>
            @endif
        @endwhile


        <?php
            $monthDate = $nowYear.'-'.$nowMonth.'-01';
            $monthDateEnd = $nowYear.'-'.$nowMonth.'-' . date('t', strtotime($monthDate)); //get end date of month
        ?>
        @while(strtotime($monthDate) <= strtotime($monthDateEnd))
            <?php
                $day_num = date('d', strtotime($monthDate));
            ?>
         
            @if($nowDay == $day_num)
                <div id="theReservationsShown{{$day_num}}" class="allTheReservationsShown">
            @else
                <div id="theReservationsShown{{$day_num}}" class="allTheReservationsShown" style="display:none;">
            @endif
                <?php
                    $allBarSerRecs = array();
                    foreach(BarbershopServiceOrdersRecords::whereDate('forDate',$monthDate)->where('status','2')->get()->sortBy('created_at') as $serOrRecords){
                        if(BarbershopServiceOrder::find($serOrRecords->forSerOrder)->toBar == $thisBartId){ array_push($allBarSerRecs,$serOrRecords); }
                    }
                ?>
                @foreach($allBarSerRecs as $bor)
                    <div style="border:2px solid rgb(39,190,175,0.7); border-radius:15px;" class="d-flex flex-wrap justify-content-between p-2 mb-2">
                        <div style="width:3%;">
                           
                            {{Form::open(['action' => 'BarbershopAdminController@generateBarSerOrderReceipt', 'method' => 'get']) }}
                                {{ Form::hidden('id', $bor->id , ['class' => 'form-control ']) }}
                                <button  class="btn"> <i style="color:red;" class="far fa-2x fa-file-pdf pl-2 pt-3 clickable"></i></button>
                            {{Form::close() }}
                        </div>
                        <div style="width:25%;">
                            <h4 style="color:rgb(72,81,87);">{{$bor->emri}}
                                @if($bor->type != NULL)
                                    ( {{explode('||',$bor->type)[1]}} )
                                @endif
                            </h4>
                            <h5 style="color:rgb(72,81,87);">{{$bor->pershkrimi}}</h5>
                            <h3 style="color:rgb(72,81,87);" class="text-center" ><strong>{{$bor->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong></h3>
                        </div>
                        <div style="width:22%;">
                            <h3 style="color:rgb(72,81,87);" ><strong>{{BarbershopWorker::find($bor->forWorker)->emri}}</strong></h3>
                            <h3 style="color:rgb(72,81,87);" ><strong>{{$bor->forDate}}</strong></h3>
                            <h3 style="color:rgb(72,81,87);" ><strong>{{$bor->timeNeed}} {{__('adminP.minutes')}}</strong></h3>
                        </div>
                        <div style="width:45%;" class="d-flex flex-wrap justify-content-start">
                            @foreach( BarbershopWorkerTerminBusy::where('serviceRecord',$bor->id)->get() as $terminet)
                                <?php $terminTime = BarbershopWorkerTerminet::find($terminet->workerTerminID); ?>
                                <button style="width:24%;" class="btn btn-outline-success mr-1 mb-1"><strong>{{$terminTime->startT}} - {{$terminTime->endT}}</strong></button>
                            @endforeach
                        </div>

                        @if ($bor->forStudent == 1)
                            <h4 style="color:red; width:100%;" class="text-center p-2"><strong>{{__('adminP.studentPrice')}}</strong></h4>
                        @endif
                    
                        <div style="width:100%; border:1px solid rgb(72,81,87); border-radius:15px;" class="d-flex flex-wrap justify-content-between mt-1">
                            <?php $barSerOr = BarbershopServiceOrder::find($bor->forSerOrder) ?>
                            <p style="color: rgb(72,81,87); width:30%; font-size:22px;" class="text-center"><strong>{{$barSerOr->clName}} {{$barSerOr->clLastname}}</strong></p>
                            <p style="color: rgb(72,81,87); width:35%; font-size:22px;" class="text-center">
                                <strong><i style="color:rgb(39,190,175);" class="fas fa-at"></i> {{$barSerOr->clEmail}}</strong>
                            </p>
                            <p style="color: rgb(72,81,87); width:30%; font-size:22px;" class="text-center">
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

    <!-- End of this Month / this Day  -->
    <!-- ...................................................................................................................................................................... -->
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
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
            <button onclick="changeDay('{{$day_num}}')" style="width:8%;" class="btn btn-outline-dark mb-2 allDaysBtn" id="daysBtn{{$day_num}}">{{$day_num}} / {{$nowMonthName}}</button>
        @endwhile

        <?php
            $monthDate = $_GET['ye'].'-'.$_GET['mo'].'-01';
            $monthDateEnd = $_GET['ye'].'-'.$_GET['mo'].'-' . date('t', strtotime($monthDate)); //get end date of month
        ?>
        @while(strtotime($monthDate) <= strtotime($monthDateEnd))
            <?php
                $day_num = date('d', strtotime($monthDate));
            ?>


            <div id="theReservationsShown{{$day_num}}" class="allTheReservationsShown">
                <?php
                    $allBarSerRecs = array();
                    foreach(BarbershopServiceOrdersRecords::whereDate('forDate',$monthDate)->where('status','2')->get()->sortBy('created_at') as $serOrRecords){
                        if(BarbershopServiceOrder::find($serOrRecords->forSerOrder)->toBar == $thisBartId){ array_push($allBarSerRecs,$serOrRecords); }
                    }
                ?>
                @foreach($allBarSerRecs as $bor)
                    <div style="border:2px solid rgb(39,190,175,0.7); border-radius:15px;" class="d-flex flex-wrap justify-content-between p-2 mb-2">
                        <div style="width:3%;">
                           
                            {{Form::open(['action' => 'BarbershopAdminController@generateBarSerOrderReceipt', 'method' => 'get']) }}
                                {{ Form::hidden('id', $bor->id , ['class' => 'form-control ']) }}
                                <button  class="btn"> <i style="color:red;" class="far fa-2x fa-file-pdf pl-2 pt-3 clickable"></i></button>
                            {{Form::close() }}
                        </div>
                        <div style="width:25%;">
                            <h4 style="color:rgb(72,81,87);">{{$bor->emri}}
                                @if($bor->type != NULL)
                                    ( {{explode('||',$bor->type)[1]}} )
                                @endif
                            </h4>
                            <h5 style="color:rgb(72,81,87);">{{$bor->pershkrimi}}</h5>
                            <h3 style="color:rgb(72,81,87);" class="text-center" ><strong>{{$bor->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong></h3>
                        </div>
                        <div style="width:22%;">
                            <h3 style="color:rgb(72,81,87);" ><strong>{{BarbershopWorker::find($bor->forWorker)->emri}}</strong></h3>
                            <h3 style="color:rgb(72,81,87);" ><strong>{{$bor->forDate}}</strong></h3>
                            <h3 style="color:rgb(72,81,87);" ><strong>{{$bor->timeNeed}} {{__('adminP.minutes')}}</strong></h3>
                        </div>
                        <div style="width:45%;" class="d-flex flex-wrap justify-content-start">
                            @foreach( BarbershopWorkerTerminBusy::where('serviceRecord',$bor->id)->get() as $terminet)
                                <?php $terminTime = BarbershopWorkerTerminet::find($terminet->workerTerminID); ?>
                                <button style="width:24%;" class="btn btn-outline-success mr-1 mb-1"><strong>{{$terminTime->startT}} - {{$terminTime->endT}}</strong></button>
                            @endforeach
                        </div>
                    
                        <div style="width:100%; border:1px solid rgb(72,81,87); border-radius:15px;" class="d-flex flex-wrap justify-content-between mt-1">
                            <?php $barSerOr = BarbershopServiceOrder::find($bor->forSerOrder) ?>
                            <p style="color: rgb(72,81,87); width:30%; font-size:22px;" class="text-center"><strong>{{$barSerOr->clName}} {{$barSerOr->clLastname}}</strong></p>
                            <p style="color: rgb(72,81,87); width:35%; font-size:22px;" class="text-center">
                                <strong><i style="color:rgb(39,190,175);" class="fas fa-at"></i> {{$barSerOr->clEmail}}</strong>
                            </p>
                            <p style="color: rgb(72,81,87); width:30%; font-size:22px;" class="text-center">
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




   





</section>












    


    <!-- chnage month/Year Modal -->
    <div class="modal" id="chngMonthYearBarSt" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
                                echo   "<a class='text-center pt-3 pb-3 chngMonthYearAnchor' style='width:33%; margin-right:0.333%;' 
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

                <!-- Modal footer -->
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> -->

            </div>
        </div>
    </div>




    <?php
        function getMonthName($monthNr){
            switch($monthNr){
                case 1: return __('adminP.jan'); break;
                case 2: return __('adminP.feb'); break;
                case 3: return __('adminP.march'); break;
                case 4: return __('adminP.apr'); break;
                case 5: return __('adminP.may'); break;
                case 6: return __('adminP.june'); break;
                case 7: return __('adminP.july'); break;
                case 8: return __('adminP.aug'); break;
                case 9: return __('adminP.sept'); break;
                case 10: return __('adminP.oct'); break;
                case 11: return __('adminP.nov'); break;
                case 12: return __('adminP.dec'); break;   
            }
        }
    ?>





<script>
    function changeDay(dayNr){
        $('.allTheReservationsShown').hide(200);
        $('#theReservationsShown'+dayNr).show(200);

        $('.allDaysBtn').attr('class','btn btn-outline-dark mb-2 allDaysBtn');
        $('#daysBtn'+dayNr).attr('class','btn btn-dark mb-2 allDaysBtn');
    }

</script>
