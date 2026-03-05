<?php

use App\Restorant;
use Carbon\Carbon;
use App\TableQrcode;
use App\RestaurantWH;
use App\TableReservation;
use Illuminate\Support\Facades\Auth;

    $theResId = Auth::user()->sFor;
    $theRes = Restorant::find(Auth::user()->sFor);
    $monthCount = Carbon::now()->month;
    $yearCount = Carbon::now()->year;
    $todayDay = Carbon::now()->day;

    $todayDate = new Carbon($yearCount.'-'.$monthCount.'-'.$todayDay);

    if($monthCount == 12){ $yearCountAdd1 = $yearCount + 1; $monthCountAdd1 = 1;}
    else{ $yearCountAdd1 = $yearCount; $monthCountAdd1 = $monthCount + 1; }

    if($monthCountAdd1 == 12){ $yearCountAdd2 = $yearCountAdd1 + 1; $monthCountAdd2 = 1;}
    else{ $yearCountAdd2 = $yearCountAdd1; $monthCountAdd2 = $monthCountAdd1 + 1; }
?>


<style>
    .btnQrorpa{
        color:white;
        background-color: rgb(39,190,175);
        border-radius: 15px;
        font-weight: bold;
    }

    .btn-qrorpa-01-roundSelected,.btn-qrorpa-01-roundSelected:focus{
        background-color: rgb(39,190,175);
        color: white;
        border-radius: 50%;
    }
  
    .btn-qrorpa-02,.btn-qrorpa-02:focus{
        background-color: rgb(39,190,175);
        color: white;
        border-radius: 10px;
    }

    .time-SelectDiv-start{
        background-color:rgba(39,190,175,0.3); 
        border-radius:50% 0 0 50% ; 
    }
    .time-SelectBtn-start-end,.time-SelectBtn-start-end:focus{
        background-color:rgb(39,190,175); 
        border-radius:50%; 
        color:white
    }
    .time-SelectDiv-continue{
        background-color:rgba(39,190,175,0.3); 
        border-radius:0 ; 
    }
    .time-SelectDiv-end{
        background-color:rgba(39,190,175,0.3); 
        border-radius:0 50% 50% 0; 
    }
</style>
<!-- Modal -->
<div class="modal" id="regArezervationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;" aria-modal="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="regArezervationModalLabel"> Registrieren Sie eine neue Reservierung </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            
            <div class="alert alert-success text-center mt-1 p-1" style="width:100%; display:none; font-size:1.2rem;" id="rezSuccRegistered">
                <strong>Sie haben die Reservierungsanfrage erfolgreich gesendet. Das Personal wird diese prüfen und sich in Kürze mit Ihnen in Verbindung setzen</strong>
            </div>
            <div class="modal-body" id="regArezervationModalBody">
                <div class="d-flex flex-wrap justify-content-between mt-3" id="rezInpDiv01">
                    <p style="width: 100%; margin:0; font-size:21px;"> <i style="color:rgb(39,190,175);" class="fa-solid fa-users mr-2 ml-2"></i>Anzahl der Gäste</p>
                    <button id="setPrsNrBtn1" onclick="setPrsNr('1')" style="width: 9%; padding:0; border-radius:50%; font-size:21px;" class="btn shadow-none"><strong>1</strong></button>
                    <button id="setPrsNrBtn2" onclick="setPrsNr('2')" style="width: 9%; padding:0; border-radius:50%; font-size:21px;" class="btn shadow-none btn-qrorpa-01-roundSelected"><strong>2</strong></button>
                    <button id="setPrsNrBtn3" onclick="setPrsNr('3')" style="width: 9%; padding:0; border-radius:50%; font-size:21px;" class="btn shadow-none"><strong>3</strong></button>
                    <button id="setPrsNrBtn4" onclick="setPrsNr('4')" style="width: 9%; padding:0; border-radius:50%; font-size:21px;" class="btn shadow-none"><strong>4</strong></button>
                    <button id="setPrsNrBtn5" onclick="setPrsNr('5')" style="width: 9%; padding:0; border-radius:50%; font-size:21px;" class="btn shadow-none"><strong>5</strong></button>
                    <button id="setPrsNrBtn6" onclick="setPrsNr('6')" style="width: 9%; padding:0; border-radius:50%; font-size:21px;" class="btn shadow-none"><strong>6</strong></button>
                    <button id="setPrsNrBtn7" onclick="setPrsNr('7')" style="width: 9%; padding:0; border-radius:50%; font-size:21px;" class="btn shadow-none"><strong>7</strong></button>
                    <button id="setPrsNrBtn8" onclick="setPrsNr('8')" style="width: 9%; padding:0; border-radius:50%; font-size:21px;" class="btn shadow-none"><strong>8</strong></button>
                    <button id="setPrsNrBtn9" onclick="setPrsNr('9')" style="width: 9%; padding:0; border-radius:50%; font-size:21px;" class="btn shadow-none"><strong>9</strong></button>
                    <input type="number" value="" id="setPrsNrInp" onkeyup="setPrsNrInp()" style="width: 19%; padding:0; border-radius:20px; font-size:21px; background-color:lightgrey;" class="btn shadow-none numeric" placeholder="?">

                     <!-- Number of people selected -->
                    <input type="hidden" id="rezPersonNrInp" value="2">

                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg01">
                        <strong>Legen Sie die Personenanzahl für diese Reservierungsanfrage fest!</strong>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-between mt-5" id="rezInpDiv02">
                    <i style="color: rgb(39,190,175); width:10%; font-size:24px;" class="fa-solid fa-calendar-days"></i>
                    <button id="monthSelBtn01" style="width: 25%; padding:0;" class="btn monthsSel shadow-none btn-qrorpa-02" onclick="showOtherMonth('1')">
                        <?php
                            switch($monthCount){
                                case 1: echo  __('adminP.jan'). " ".$yearCount.""; break; case 2: echo  __('adminP.feb'). " ".$yearCount.""; break; case 3: echo  __('adminP.march'). " ".$yearCount.""; break;
                                case 4: echo  __('adminP.apr'). " ".$yearCount.""; break; case 5: echo __('adminP.May'). " ".$yearCount.""; break; case 6: echo __('adminP.june'). " ".$yearCount.""; break;
                                case 7: echo __('adminP.july'). " ".$yearCount.""; break; case 8: echo __('adminP.aug'). " ".$yearCount.""; break; case 9: echo __('adminP.sept'). " ".$yearCount.""; break;
                                case 10: echo __('adminP.oct'). " ".$yearCount.""; break; case 11: echo __('adminP.nov'). " ".$yearCount.""; break; case 12: echo __('adminP.dec'). " ".$yearCount.""; break;   
                            }
                        ?>
                    </button>
                    <button id="monthSelBtn02" style="width: 25%; padding:0;" class="btn monthsSel shadow-none" onclick="showOtherMonth('2')">
                        <?php
                            switch($monthCountAdd1){
                                case 1: echo  __('adminP.jan'). " ".$yearCountAdd1.""; break; case 2: echo  __('adminP.feb'). " ".$yearCountAdd1.""; break; case 3: echo  __('adminP.march'). " ".$yearCountAdd1.""; break;
                                case 4: echo  __('adminP.apr'). " ".$yearCountAdd1.""; break; case 5: echo __('adminP.May'). " ".$yearCountAdd1.""; break; case 6: echo __('adminP.june'). " ".$yearCountAdd1.""; break;
                                case 7: echo __('adminP.july'). " ".$yearCountAdd1.""; break; case 8: echo __('adminP.aug'). " ".$yearCountAdd1.""; break; case 9: echo __('adminP.sept'). " ".$yearCountAdd1.""; break;
                                case 10: echo __('adminP.oct'). " ".$yearCountAdd1.""; break; case 11: echo __('adminP.nov'). " ".$yearCountAdd1.""; break; case 12: echo __('adminP.dec'). " ".$yearCountAdd1.""; break;   
                            }
                        ?>
                    </button>
                    <button id="monthSelBtn03" style="width: 25%; padding:0;" class="btn monthsSel shadow-none" onclick="showOtherMonth('3')">
                        <?php
                            switch($monthCountAdd2){
                                case 1: echo  __('adminP.jan'). " ".$yearCountAdd2.""; break; case 2: echo  __('adminP.feb'). " ".$yearCountAdd2.""; break; case 3: echo  __('adminP.march'). " ".$yearCountAdd2.""; break;
                                case 4: echo  __('adminP.apr'). " ".$yearCountAdd2.""; break; case 5: echo __('adminP.May'). " ".$yearCountAdd2.""; break; case 6: echo __('adminP.june'). " ".$yearCountAdd2.""; break;
                                case 7: echo __('adminP.july'). " ".$yearCountAdd2.""; break; case 8: echo __('adminP.aug'). " ".$yearCountAdd2.""; break; case 9: echo __('adminP.sept'). " ".$yearCountAdd2.""; break;
                                case 10: echo __('adminP.oct'). " ".$yearCountAdd2.""; break; case 11: echo __('adminP.nov'). " ".$yearCountAdd2.""; break; case 12: echo __('adminP.dec'). " ".$yearCountAdd2.""; break;   
                            }
                        ?>
                    </button>
                    <button style="width: 7.5%; padding:0;" class="btn"><</button>
                    <button style="width: 7.5%; padding:0;" class="btn">></button>

                    <!-- Date selected -->
                    <input type="hidden" id="rezMonthShowSelected" value="1"> 
                    <input type="hidden" id="rezDateSelectDt" value="{{explode(' ',$todayDate)[0]}}"> 

                    <div id="month01" style="width: 100%; display:flex;" class="flex-wrap justify-content-between p-1">
                        <?php $month = new Carbon($yearCount.'-'.$monthCount.'-01'); ?>
                        <div style="width:100%;" class="d-flex flex-wrap justify-content-start mb-2">
                            <div style="width:100%; background-color:rgb(39,190,175);" class="d-flex flex-wrap justify-content-start mb-1">
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Mo</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Di</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Mi</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Do</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Fr</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Sa</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >So</p>
                            </div>
                            @for($i=1;$i<=$month->daysInMonth;$i++)
                                <?php
                                    if($i < 10){ $d= '0'.$i; }else{ $d= $i;}
                                    $dateCheckCreate = new Carbon($yearCount.'-'.$monthCount.'-'.$d);
                                ?>
                                @if($i == 1)
                                    <?php
                                        $dayOfWeekNr = date('w', strtotime($dateCheckCreate));
                                        if($dayOfWeekNr == 0){ $dayOfWeekNr = 7; }
                                        $j = 1;
                                    ?>
                                    @while ($j < $dayOfWeekNr)
                                    <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%; color:white;" disabled>.</button>
                                    <?php $j++; ?>
                                    @endwhile
                                    
                                    @if($dateCheckCreate == $todayDate)
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @else
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @endif
                                @else
                                    @if($dateCheckCreate == $todayDate)
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @else
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @endif
                                @endif
                            @endfor
                        </div>
                    </div>

                    <div id="month02" style="width: 100%; display:none;" class="flex-wrap justify-content-between p-1">
                        <?php $month = new Carbon($yearCountAdd1.'-'.$monthCountAdd1.'-01'); ?>
                        <div style="width:100%;" class="d-flex flex-wrap justify-content-start mb-2">
                            <div style="width:100%; background-color:rgb(39,190,175);" class="d-flex flex-wrap justify-content-start mb-1">
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Mo</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Di</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Mi</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Do</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Fr</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Sa</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >So</p>
                            </div>
                            @for($i=1;$i<=$month->daysInMonth;$i++)
                                <?php
                                    if($i < 10){ $d= '0'.$i; }else{ $d= $i;}
                                    $dateCheckCreate = new Carbon($yearCountAdd1.'-'.$monthCountAdd1.'-'.$d);
                                ?>
                                @if($i == 1)
                                    <?php
                                        $dayOfWeekNr = date('w', strtotime($dateCheckCreate));
                                        if($dayOfWeekNr == 0){ $dayOfWeekNr = 7; }
                                        $j = 1;
                                    ?>
                                    @while ($j < $dayOfWeekNr)
                                    <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%; color:white;" disabled>.</button>
                                    <?php $j++; ?>
                                    @endwhile
                                    
                                    @if($dateCheckCreate == $todayDate)
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @else
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @endif
                                @else
                                    @if($dateCheckCreate == $todayDate)
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @else
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @endif
                                @endif
                            @endfor
                        </div>
                    </div>

                    <div id="month03" style="width: 100%; display:none;" class="flex-wrap justify-content-between p-1">
                        <?php $month = new Carbon($yearCountAdd2.'-'.$monthCountAdd2.'-01'); ?>
                        <div style="width:100%;" class="d-flex flex-wrap justify-content-start mb-2">
                            <div style="width:100%; background-color:rgb(39,190,175);" class="d-flex flex-wrap justify-content-start mb-1">
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Mo</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Di</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Mi</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Do</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Fr</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >Sa</p>
                                <p class="btn" style="width:14.1%; color:white; margin-right:0.18%; margin-bottom:0;" >So</p>
                            </div>
                            @for($i=1;$i<=$month->daysInMonth;$i++)
                                <?php
                                    if($i < 10){ $d= '0'.$i; }else{ $d= $i;}
                                    $dateCheckCreate = new Carbon($yearCountAdd2.'-'.$monthCountAdd2.'-'.$d);
                                ?>
                                @if($i == 1)
                                    <?php
                                        $dayOfWeekNr = date('w', strtotime($dateCheckCreate));
                                        if($dayOfWeekNr == 0){ $dayOfWeekNr = 7; }
                                        $j = 1;
                                    ?>
                                    @while ($j < $dayOfWeekNr)
                                    <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%; color:white;" disabled>.</button>
                                    <?php $j++; ?>
                                    @endwhile
                                    
                                    @if($dateCheckCreate == $todayDate)
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @else
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @endif
                                @else
                                    @if($dateCheckCreate == $todayDate)
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1 btn-qrorpa-01-roundSelected" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @else
                                        @if($dateCheckCreate >= $todayDate)
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" onclick="selectRezDate('{{$dateCheckCreate}}','{{$theResId}}')" style="width:14.1%; margin-right:0.18%;">{{$i}} </button>
                                        @else
                                            <button id="dtSelectBtn{{explode(' ',$dateCheckCreate)[0]}}" class="btn shadow-none pb-1" style="width:14.1%; margin-right:0.18%;" disabled><del>{{$i}}</del></button>
                                        @endif
                                    @endif
                                @endif
                            @endfor
                        </div>
                    </div>                    
                </div>
                <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg02">
                    <strong>Wählen Sie den Wunschtermin für diese Reservierungsanfrage!</strong>
                </div>




                <input type="hidden" id="timeSet1Nr" value="0">
                <input type="hidden" id="timeSet1Place" value="0">
                <input type="hidden" id="timeSet1Val" value="0">
                <input type="hidden" id="timeSet2Nr" value="0">
                <input type="hidden" id="timeSet2Place" value="0">
                <input type="hidden" id="timeSet2Val" value="0">
                <div id="timeSelectDiv" class="d-flex flex-wrap justify-content-start mt-3">
                    <p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-regular fa-clock mr-2 ml-2"></i>Uhrzeit auswählen (von - bis)</p>
                    <?php
                        $dt = Carbon::now();
                        $dt2D = explode(' ',$dt)[0];
                        $daySelectedOfWeekNr = date('w', strtotime($dt2D));

                        $resWH = RestaurantWH::where('toRes',$theResId)->first();
                        if($daySelectedOfWeekNr == 1 ){ $WHStar1 = $resWH->D1Starts1; $WHEnd1 = $resWH->D1End1; $WHStar2 = $resWH->D1Starts2; $WHEnd2 = $resWH->D1End2;}
                        else if($daySelectedOfWeekNr == 2){ $WHStar1 = $resWH->D2Starts1; $WHEnd1 = $resWH->D2End1; $WHStar2 = $resWH->D2Starts2; $WHEnd2 = $resWH->D2End2;}
                        else if($daySelectedOfWeekNr == 3){ $WHStar1 = $resWH->D3Starts1; $WHEnd1 = $resWH->D3End1; $WHStar2 = $resWH->D3Starts2; $WHEnd2 = $resWH->D3End2;}
                        else if($daySelectedOfWeekNr == 4){ $WHStar1 = $resWH->D4Starts1; $WHEnd1 = $resWH->D4End1; $WHStar2 = $resWH->D4Starts2; $WHEnd2 = $resWH->D4End2;}
                        else if($daySelectedOfWeekNr == 5){ $WHStar1 = $resWH->D5Starts1; $WHEnd1 = $resWH->D5End1; $WHStar2 = $resWH->D5Starts2; $WHEnd2 = $resWH->D5End2;}
                        else if($daySelectedOfWeekNr == 6){ $WHStar1 = $resWH->D6Starts1; $WHEnd1 = $resWH->D6End1; $WHStar2 = $resWH->D6Starts2; $WHEnd2 = $resWH->D6End2;}
                        else if($daySelectedOfWeekNr == 0){ $WHStar1 = $resWH->D7Starts1; $WHEnd1 = $resWH->D7End1; $WHStar2 = $resWH->D7Starts2; $WHEnd2 = $resWH->D7End2;}

                        $timeShow = Carbon::createFromFormat('H:i', $WHStar1);
                        $timeE1 = Carbon::createFromFormat('H:i', $WHEnd1);
                        if ($WHStar2 != 'none'){
                            $timeS2 = Carbon::createFromFormat('H:i', $WHStar2);
                            $timeE2 = Carbon::createFromFormat('H:i', $WHEnd2);
                        }
                        $timePeriod = 1;
                        $timeSlotCount = 1;
                    ?>
                
                    @while (true)
                        <div id="timeDiv1O{{$timeSlotCount}}" style="width:16.66666%;">
                            <button id="timeBtn1O{{$timeSlotCount}}" class="btn shadow-none pb-3 pt-3" onclick="selectRezTime('1','{{$timeSlotCount}}')" style="width:100%; height:100%; padding:0;">{{$timeShow->format('H:i')}}</button>
                        </div>
                        <?php
                            $timeSlotCount++; 
                            $timeShow = $timeShow->addMinutes(30);
                            if($timePeriod == 1 && $timeShow > $timeE1){
                                if ($WHStar2 != 'none'){ $timeShow = Carbon::createFromFormat('H:i', $WHStar2); }
                                break; 
                            }
                        ?>
                    @endwhile
                    @if ($WHStar2 != 'none')
                    <button class="btn shadow-none pb-3 pt-3" style="width:16.66666%; padding:0; background-color:palevioletred">Aus</button>
                    @while (true)
                        <div id="timeDiv2O{{$timeSlotCount}}" style="width:16.66666%;">
                            <button id="timeBtn2O{{$timeSlotCount}}" class="btn shadow-none pb-3 pt-3" onclick="selectRezTime('2','{{$timeSlotCount}}')" style="width:100%; height:100%; padding:0;">{{$timeShow->format('H:i')}}</button>
                        </div>
                        <?php
                            $timeSlotCount++; 
                            $timeShow = $timeShow->addMinutes(30);
                            if($timePeriod == 1 && $timeShow > $timeE2){
                                break; 
                            }
                        ?>
                    @endwhile
                    @endif
                </div>
                <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg03">
                    <strong>Wählen Sie den Zeitraum für diese Reservierungsanfrage!</strong>
                </div>

       

                <input type="hidden" id="tableSelectedInp" value="empty">
                <div class="d-flex flex-wrap justify-content-start mt-2" id="tableSelDiv">
                    <p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-solid fa-table mr-2 ml-2"></i>Tischauswahl</p>
                    @foreach(TableQrcode::where('Restaurant',Auth::user()->sFor)->get()->sortBy('tableNr') as $tabl)
                        
                        <div style="width:16.35%; margin-right:0.31%; background-color:rgba( 39, 190, 175, 0.3); border-radius:5px;" onclick="selectThisTableReq('{{$tabl->tableNr}}')"
                        class="mb-1 p-1 text-center clickable" id="oneTableDiv{{$tabl->tableNr}}">
                            <span style="font-size: 1.1rem; font-weight:bold;"># {{$tabl->tableNr}}</span> <br> {{$tabl->capacity}} <i class="fas fa-user-friends"></i>
                        </div>

                        <input type="hidden" class="allTables" value="0" id="oneTable{{$tabl->tableNr}}">
                        <input type="hidden" value="{{$tabl->capacity}}" id="oneTableCa{{$tabl->tableNr}}">
                    @endforeach 
                </div>
                <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg12">
                    <strong>Sie sollten einen oder mehrere Tische auswählen, um diese Reservierung zu registrieren!</strong>
                </div>



                <div class="d-flex flex-wrap justify-content-start mt-4">
                    <p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-regular fa-comment-dots mr-2 ml-2"></i>Besondere Wünsche</p>
                    <textarea style="width: 100%; background-color:rgba(244,244,244,255); border-radius:15px; border:1px solid lightgray;" class="form-control shadow-none" 
                    id="rezComment" rows="3" placeholder="z.B. Tischwunsch, Stellplatz für Kinderwagen"></textarea>
                </div>




                <div class="d-flex flex-wrap justify-content-start mt-4" id="rezInpDiv05">
                    <p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-solid fa-circle-user mr-2 ml-2"></i>Persönliche Daten des Kunden</p>

                    <input style="width: 100%; background-color:rgba(244,244,244,255); border-radius:15px; border:1px solid lightgray;" type="email" 
                    class="form-control mb-2 shadow-none" id="rezUsrEmail" placeholder="E-Mail">
                    <input style="width: 50%; background-color:rgba(244,244,244,255); border-radius:15px; border:1px solid lightgray;" type="text" 
                    class="form-control mb-2 shadow-none" id="rezUsrLastname" placeholder="Vorname">
                    <input style="width: 50%; background-color:rgba(244,244,244,255); border-radius:15px; border:1px solid lightgray;" type="text" 
                    class="form-control mb-2 shadow-none" id="rezUsrName" placeholder="Name">

                    <div id="sentNrForConfDiv" style="width: 100%;" class="input-group">
                        <input style="background-color:rgba(244,244,244,255); border-radius:15px; border:1px solid lightgray;" 
                        type="number" class="form-control numeric shadow-none" id="rezUsrPhoneNr" placeholder="Telefon Nr.">
                    </div>
                    <input type="hidden" id="phoneNrConfirmed" value="0">
                  
                    <div class="alert alert-success text-center mt-1 p-1" style="width:100%; display:none;" id="rezSuccPhoneNrConf">

                    </div>

                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg04">
                        <strong>Geben Sie eine gültige E-Mail-Adresse ein!</strong>
                    </div>
                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg05">
                        <strong>Geben Sie unbedingt Ihren Vor- und Nachnamen sowie Ihre Telefonnummer an!</strong>
                    </div>
                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg06">
                        <strong>Geben Sie eine gültige Telefonnummer ein!</strong>
                    </div>
                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg07">
                        <strong>Sie haben keine aktive Telefonnummernaktivierungssitzung!</strong>
                    </div>
                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg08">
                        <strong>Diese telefonische Bestätigungssitzung ist ungültig. Aktualisieren Sie sie und versuchen Sie es erneut!</strong>
                    </div>
                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg09">
                        <strong>Der von Ihnen eingegebene Code ist falsch. Versuchen Sie es erneut!</strong>
                    </div>
                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg10">
                        <strong>Ihre Telefonnummer muss bestätigt werden!</strong>
                    </div>
                </div>

                <div class="d-flex flex-wrap justify-content-start mt-4">
                    <button id="saveReservationBtn" class="btn shadow-none" style="background-color:rgb(39,190,175); color:white; font-size:22px; border-radius:15px; width:100%;"
                    onclick="saveReservation()">
                        <strong>Reservierung abschliessen</strong>
                    </button>
                </div>




                

            </div>
        </div>
    </div>
</div>












<script>
    $('.numeric').on('input', function (event) { 
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    function setPrsNr(prsNr){
        if($('#rezPersonNrInp').val() > 0 && $('#rezPersonNrInp').val() < 10){
            $('#setPrsNrBtn'+$('#rezPersonNrInp').val()).removeClass('btn-qrorpa-01-roundSelected');   
        }
        $('#setPrsNrInp').attr('style','width: 19%; padding:0; border-radius:20px; font-size:21px; background-color:lightgray;');
        $('#setPrsNrInp').val('');
        
        $('#setPrsNrBtn'+prsNr).addClass('btn-qrorpa-01-roundSelected');
        $('#rezPersonNrInp').val(prsNr);

        if($('#rezRegErrorMsg01').is(':visible')){ $('#rezRegErrorMsg01').hide(100); }
    }

    function setPrsNrInp(){
        if($('#rezPersonNrInp').val() > 0 && $('#rezPersonNrInp').val() < 10){
            $('#setPrsNrBtn'+$('#rezPersonNrInp').val()).removeClass('btn-qrorpa-01-roundSelected');
        }
        if($('#setPrsNrInp').val() != ''){
            $('#setPrsNrInp').attr('style','width: 19%; padding:0; border-radius:20px; font-size:21px; color:white; background-color:rgb(39,190,175);');
        
            $('#rezPersonNrInp').val($('#setPrsNrInp').val());

            if($('#rezRegErrorMsg01').is(':visible')){ $('#rezRegErrorMsg01').hide(100); }
        }else{
            $('#setPrsNrInp').attr('style','width: 19%; padding:0; border-radius:20px; font-size:21px; background-color:lightgray;');
            $('#rezPersonNrInp').val('0');
        }
    }






    function showOtherMonth(mnthNr){
        $('#month0'+$('#rezMonthShowSelected').val()).attr('style','width: 100%; display:none;');
        $('#monthSelBtn0'+$('#rezMonthShowSelected').val()).removeClass('shadow-none btn-qrorpa-02');

        $('#month0'+mnthNr).attr('style','width: 100%; display:flex;');
        $('#monthSelBtn0'+mnthNr).addClass('shadow-none btn-qrorpa-02');

        $('#rezMonthShowSelected').val(mnthNr);

        $('#dtSelectBtn'+$('#rezDateSelectDt').val()).removeClass('btn-qrorpa-01-roundSelected');
        $('#rezDateSelectDt').val('none');

        $('#timeSelectDiv').html('<p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-regular fa-clock mr-2 ml-2"></i>Uhrzeit auswählen (von - bis)</p>'+
        '<div class="alert alert-info text-center mt-1" style="width:100%;"><strong>Wählen Sie ein Datum aus, um die möglichen Reservierungszeiten anzuzeigen</strong></div>');

        $('#timeSet1Nr').val(0);
        $('#timeSet1Place').val(0);
        $('#timeSet1Val').val(0);
        $('#timeSet2Nr').val(0);
        $('#timeSet2Place').val(0);
        $('#timeSet2Val').val(0);
    }

    function selectRezDate(theDt,resId){
        var theDt = theDt.split(' ');
        if($('#rezDateSelectDt').val() != 'none'){
            $('#dtSelectBtn'+$('#rezDateSelectDt').val()).removeClass('btn-qrorpa-01-roundSelected');
        }

        $('#dtSelectBtn'+theDt[0]).addClass('btn-qrorpa-01-roundSelected');
        $('#rezDateSelectDt').val(theDt[0]);


        $('#timeSelectDiv').html('<p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-regular fa-clock mr-2 ml-2"></i>Uhrzeit auswählen (von - bis)</p>');
        
        $('#timeSet1Nr').val(0);
        $('#timeSet1Place').val(0);
        $('#timeSet1Val').val(0);
        $('#timeSet2Nr').val(0);
        $('#timeSet2Place').val(0);
        $('#timeSet2Val').val(0);
        
        $.ajax({
			url: '{{ route("tableRez.getWorkingHrsForRes") }}',
			method: 'post',
			data: {
				dt: theDt,
                resId: resId,
				_token: '{{csrf_token()}}'
			},
			success: (gwhRes) => {
                gwhRes = $.trim(gwhRes);
                gwhRes2D = gwhRes.split('|||');

                var timeSlotCount = 1;

                if(gwhRes2D[0] != 'none'){
                    var prep1 = gwhRes2D[0].split(' ')[1];
                    prep1 = prep1.split(':');

                    var prep2 = gwhRes2D[1].split(' ')[1];
                    prep2 = prep2.split(':');
                }

                if(gwhRes2D[2] != 'none'){
                    var prep3 = gwhRes2D[2].split(' ')[1];
                    prep3 = prep3.split(':');

                    var prep4 = gwhRes2D[3].split(' ')[1];
                    prep4 = prep4.split(':');
                }

                if(gwhRes2D[0] == 'none' && gwhRes2D[2] == 'none'){
                    $('#timeSelectDiv').append('<div class="alert alert-danger text-center mt-1" style="width:100%;"><strong>Für dieses Datum sind keine Arbeitszeiten verfügbar</strong></div>');
                }else{
                    
                    if($('#rezRegErrorMsg02').is(':visible')){ $('#rezRegErrorMsg02').hide(100); }

                    var showTimeHr;
                    var showTimeMn;
                    if(gwhRes2D[0] != 'none'){
                        showTimeHr = prep1[0];
                        showTimeMn = prep1[1];

                        do {
                            var cont = true;
                            $('#timeSelectDiv').append( '<div id="timeDiv1O'+timeSlotCount+'" style="width:16.66666%;">'+
                                                            '<button id="timeBtn1O'+timeSlotCount+'"  class="btn shadow-none pb-3 pt-3" onclick="selectRezTime(\'1\',\''+timeSlotCount+'\')" style="width:100%; height:100%; padding:0;">'+showTimeHr+':'+showTimeMn+'</button>'+
                                                        '</div>');
                            if(showTimeMn == '00'){
                                showTimeMn = '30';
                            }else{
                                showTimeHr++;
                                showTimeHr = pad(showTimeHr,2);
                                showTimeMn = '00';
                            }
                            timeSlotCount++;

                            if(showTimeHr > prep2[0] || (showTimeHr == prep2[0] && showTimeMn > prep2[1])){
                                var cont = false;
                            }
                        }while (cont);
                    }

                    if(gwhRes2D[2] != 'none'){
                        $('#timeSelectDiv').append('<button class="btn shadow-none pb-3 pt-3" style="width:16.66666%; padding:0; background-color:palevioletred">Aus</button>');
                    
                        showTimeHr = prep3[0];
                        showTimeMn = prep3[1];

                        do {
                            var cont = true;
                            $('#timeSelectDiv').append( '<div id="timeDiv2O'+timeSlotCount+'" style="width:16.66666%;">'+
                                                            '<button id="timeBtn2O'+timeSlotCount+'"  class="btn shadow-none pb-3 pt-3" onclick="selectRezTime(\'2\',\''+timeSlotCount+'\')" style="width:100%; height:100%; padding:0;">'+showTimeHr+':'+showTimeMn+'</button>'+
                                                        '</div>');
                            if(showTimeMn == '00'){
                                showTimeMn = '30';
                            }else{
                                showTimeHr++;
                                showTimeHr = pad(showTimeHr,2);
                                showTimeMn = '00';
                            }
                            timeSlotCount++;

                            if(showTimeHr > prep4[0] || (showTimeHr == prep4[0] && showTimeMn > prep4[1])){
                                var cont = false;
                            }
                        }while (cont);
                    }
                }
                
                
			},
			error: (error) => { console.log(error); }
		});
    }

    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    } 

    function selectRezTime(timePlace, timeNr){
      
        if($('#timeSet1Nr').val() != 0 && $('#timeSet2Nr').val() != 0){
        
            // ri-fillohet periudha 
            $('#timeDiv'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).removeClass('time-SelectDiv-start');
            $('#timeBtn'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).removeClass('time-SelectBtn-start-end');
            $('#timeDiv'+ $('#timeSet2Place').val()+'O'+ $('#timeSet2Nr').val()).removeClass('time-SelectDiv-end');
            $('#timeBtn'+ $('#timeSet2Place').val()+'O'+ $('#timeSet2Nr').val()).removeClass('time-SelectBtn-start-end');
            var start = parseInt(parseInt($('#timeSet1Nr').val()) + parseInt(1));
            do {
                $('#timeDiv'+ $('#timeSet1Place').val()+'O'+start).removeClass('time-SelectDiv-continue');
                start++;
            }while(start < $('#timeSet2Nr').val());
            $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
            $('#timeSet1Nr').val(timeNr);
            $('#timeSet1Place').val(timePlace);
            $('#timeSet1Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());
            $('#timeSet2Nr').val(0);
            $('#timeSet2Place').val(0);
            $('#timeSet2Val').val(0);
        }else{
            
            if($('#timeSet1Nr').val() == 0){

                // selektohet e para
                $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
                $('#timeSet1Nr').val(timeNr);
                $('#timeSet1Place').val(timePlace);
                $('#timeSet1Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());
            }else{
                if(parseInt(timePlace) != parseInt($('#timeSet1Place').val())){

                    // e dyta eshte ne zone tjeter
                    $('#timeBtn'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).removeClass('time-SelectBtn-start-end');
                    $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
                    $('#timeSet1Nr').val(timeNr);
                    $('#timeSet1Place').val(timePlace);
                    $('#timeSet1Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());
                }else{
                    if(parseInt(timeNr) > parseInt($('#timeSet1Nr').val())){

                        // vlen per periudh
                        $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
                        $('#timeSet2Nr').val(timeNr);
                        $('#timeSet2Place').val(timePlace);
                        $('#timeSet2Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());

                        // Dizajni i selektimit
                        $('#timeDiv'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).addClass('time-SelectDiv-start');
                        var start = parseInt(parseInt($('#timeSet1Nr').val()) + parseInt(1));
                        do {
                            $('#timeDiv'+ $('#timeSet1Place').val()+'O'+start).addClass('time-SelectDiv-continue');
                            start++;
                        }while(start < $('#timeSet2Nr').val());
                        $('#timeDiv'+ $('#timeSet2Place').val()+'O'+ $('#timeSet2Nr').val()).addClass('time-SelectDiv-end');
                    }else{

                        // selektimi i dyte ma i vogel
                        $('#timeBtn'+ $('#timeSet1Place').val()+'O'+ $('#timeSet1Nr').val()).removeClass('time-SelectBtn-start-end');
                        $('#timeBtn'+timePlace+'O'+timeNr).addClass('time-SelectBtn-start-end');
                        $('#timeSet1Nr').val(timeNr);
                        $('#timeSet1Place').val(timePlace);
                        $('#timeSet1Val').val($('#timeBtn'+timePlace+'O'+timeNr).html());
                    }
                }
            }
        }
    }





    function selectThisTableReq(tbNr){
        var tableSel = parseInt($('#oneTable'+tbNr).val());
        var allTab = $('#tableSelectedInp').val();
        var newAllTab = '';
        if(tableSel == 0){
            // Not Selected , Select it
            $('#oneTable'+tbNr).val('1');
            $('#oneTableDiv'+tbNr).attr('style','width:16.35%; margin-right:0.31%; background-color:rgba(39, 190, 175, 1); border-radius:5px;');

            if(allTab == 'empty'){ newAllTab = tbNr;
            }else{ newAllTab = allTab+'|||'+tbNr; }

            $('#tableSelectedInp').val(newAllTab);

        }else{
            // Selected , de-Select it
            $('#oneTable'+tbNr).val('0');
            $('#oneTableDiv'+tbNr).attr('style','width:16.35%; margin-right:0.31%; background-color:rgba(39, 190, 175, 0.3); border-radius:5px;');

            if(allTab == tbNr){
                $('#tableSelectedInp').val('empty');
            }else{
                var allTab2D = allTab.split('|||');
                $.each(allTab2D, function(index, tabOne) {
                    if(parseInt(tbNr) != parseInt(tabOne)){
                        if(newAllTab == ''){ newAllTab = tabOne;
                        }else{ newAllTab += '|||'+tabOne; }
                    }
                });
                $('#tableSelectedInp').val(newAllTab);
            }
        }
    }




    function saveReservation(){
        $('#saveReservationBtn').prop('disabled', true);
        $('#saveReservationBtn').html('<img src="storage/gifs/loading2.gif" style="width:23px;" alt="">');
        if($('#rezPersonNrInp').val() == 0){
            // no number of persons 
            if($('#rezRegErrorMsg01').is(':hidden')){ $('#rezRegErrorMsg01').show(100); }
            $('#regArezervationModal').animate({ scrollTop: $("#rezInpDiv01").offset().top }, 500);
            $('#saveReservationBtn').prop('disabled', false);
            $('#saveReservationBtn').html('<strong>Reservierung abschliessen</strong>');

        }else if($('#rezDateSelectDt').val() == 'none'){
            // no date selected
            if($('#rezRegErrorMsg02').is(':hidden')){ $('#rezRegErrorMsg02').show(100); }
            $('#regArezervationModal').animate({ scrollTop: $("#rezInpDiv02").offset().top }, 500);
            $('#saveReservationBtn').prop('disabled', false);
            $('#saveReservationBtn').html('<strong>Reservierung abschliessen</strong>');

        }else if($('#timeSet1Val').val() == 0 || $('#timeSet2Val').val() == 0 ){
            // no time selected
            if($('#rezRegErrorMsg03').is(':hidden')){ $('#rezRegErrorMsg03').show(100).delay(4500).hide(100); }
            $('#regArezervationModal').animate({ scrollTop: $("#timeSelectDiv").offset().top }, 500);
            $('#saveReservationBtn').prop('disabled', false);
            $('#saveReservationBtn').html('<strong>Reservierung abschliessen</strong>');

        }else if($('#tableSelectedInp').val() == 'empty'){
            // no table selected
            if($('#rezRegErrorMsg12').is(':hidden')){ $('#rezRegErrorMsg12').show(100).delay(4500).hide(100); }
            $('#saveReservationBtn').prop('disabled', false);
            $('#saveReservationBtn').html('<strong>Reservierung abschliessen</strong>');
  
        }else{
            var userEmail = $('#rezUsrEmail').val();
            var patternEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

            if($("#rezUsrLastname").val() == ''){ var lastname = 'empty'; }else{ var lastname = $("#rezUsrLastname").val(); }
            if($("#rezUsrName").val() == ''){ var name = 'empty'; }else{ var name = $("#rezUsrName").val(); }
            if($("#rezUsrPhoneNr").val() == ''){ var phNr = 'empty'; }else{ var phNr = $("#rezUsrPhoneNr").val(); }
            if($('#rezUsrEmail').val() == '' || !patternEmail.test(userEmail)){ var email = 'empty'; }else{ var email = $("#rezUsrEmail").val(); }

            var qrorpaMarketingUse = 0;
            var resMarketingUse = 0;
            $.ajax({
				url: '{{ route("TableReservation.saveReservationFromStaf") }}',
				method: 'post',
				data: {
                    res: '{{Auth::user()->sFor}}',
                    dita: $('#rezDateSelectDt').val(),
                    koha1: $('#timeSet1Val').val(),
                    koha2: $('#timeSet2Val').val(),
                    persona: $('#rezPersonNrInp').val(),
                    tablesAll: $('#tableSelectedInp').val(),
                    emri: name,
                    mbiemri: lastname,
                    tel: phNr,
                    email: email,
                    koment: $('#rezComment').val(),
                    qroMar: qrorpaMarketingUse,
                    resMar: resMarketingUse,
					_token: '{{csrf_token()}}'
				},
				success: () => {
                    if($('#rezSuccRegistered').is(':hidden')){ $('#rezSuccRegistered').show(100).delay(12000).hide(100); }
                    $('#regArezervationModal').animate({ scrollTop: $("#rezSuccRegistered").offset().top }, 500);
                    $("#regArezervationModalBody").load(location.href+" #regArezervationModalBody>*","");
                    $("#RezTableTel").load(location.href+" #RezTableTel>*","");
                    $("#allRezModalsTel").load(location.href+" #allRezModalsTel>*","");
                    
				},
				error: (error) => { console.log(error); }
			});
            
        }
    }
</script>