@extends('layouts.appTableRez')

@section('content')
<?php
    use App\Restorant;
    use App\RestaurantWH;
    use Illuminate\Support\Facades\Auth;
    use App\TableQrcode;
    use Carbon\Carbon;
    use PhpParser\Node\Stmt\Break_;

    if (session_status() == PHP_SESSION_NONE) {session_start();}
    if(isset($_SESSION['ResRez'])){
        $theResId = $_SESSION['ResRez'];
    }else{ $theResId = $_GET['Res']; }
      
    $theRes = Restorant::find($theResId);

    $monthCount = Carbon::now()->month;
    $yearCount = Carbon::now()->year;
    $todayDay = Carbon::now()->day;

    $todayDate = new Carbon($yearCount.'-'.$monthCount.'-'.$todayDay);

    if($monthCount == 12){ $yearCountAdd1 = $yearCount + 1; $monthCountAdd1 = 1;}
    else{ $yearCountAdd1 = $yearCount; $monthCountAdd1 = $monthCount + 1; }

    if($monthCountAdd1 == 12){ $yearCountAdd2 = $yearCountAdd1 + 1; $monthCountAdd2 = 1;}
    else{ $yearCountAdd2 = $yearCountAdd1; $monthCountAdd2 = $monthCountAdd1 + 1; }

?>



    @if ($theResId == 58)
        <!-- Modal -->
        <div class="modal fade mt-5" id="res58BlockReservationsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="border:2px solid rgb(39,190,175); border-radius:20px;">
                    <div class="modal-body text-center">
                        <p class="text-center" style="font-size:1.2rem;"><strong>Zurzeit haben wir geschlossen!</strong></p>
                        <p class="text-center" style="color:rgb(39,190,175); font-size:1.2rem;"><strong>Vielen Dank für Ihr Verständnis.</strong></p>
                        <i style="color:rgb(39,190,175);" class="text-center fas fa-3x fa-store-alt-slash"></i>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $('#res58BlockReservationsModal').modal('show');
        </script>
    @endif


<input type="hidden" value="{{$theResId}}" id="theResId">

<style>
    *{ font-family: Arial, Helvetica, sans-serif; }
    textarea:focus, input:focus{ outline: none; }

    @media only screen and (max-width: 375px) and (min-width: 0px) {
        .tableSize { width: 21%; }
    }
    @media only screen and (max-width: 460px) and (min-width: 376px) {
        .tableSize { width: 17.7%; }
    }
    @media only screen and (max-width: 770px) and (min-width: 461px) {
        .tableSize { width: 17%; }
    }
    @media only screen and (max-width: 1024px) and (min-width: 771px) {
        .tableSize { width: 9%; }
    }
    @media only screen and (max-width: 10000px) and (min-width: 1025px) {
        .tableSize { width: 4%; }
    }

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













    <div class="d-flex pr-2 pl-2 pb-1" style="border-bottom:1px solid rgb(39,190,175); background-color:rgb(39,190,175);">
        <h3 style="width:100%;" class="color-white text-center pt-2">
        @if($theRes != null && $theRes->profilePic == 'none')
            <img style="width:50px; height:50px; border-radius:50%; background-color:white; border: 1px solid #d7d7d7;" src="storage/images/showcase03.png" alt="">
        @else
            <img style="width:50px; height:50px; border-radius:50%;border: 1px solid #d7d7d7;" src="storage/ResProfilePic/{{$theRes->profilePic}}" alt="">
        @endif
         {{$theRes->emri}}</h3>
    </div>

    <div class="alert alert-success text-center mt-1 p-1" style="width:100%; display:none; font-size:1.2rem;" id="rezSuccRegistered">
        <strong>Sie haben die Reservierungsanfrage erfolgreich gesendet. Das Personal wird diese prüfen und sich in Kürze mit Ihnen in Verbindung setzen</strong>
    </div>



    <div class="container" style="padding-bottom: 10px;">
        <div class="row">
            <div class="col-lg-3 col-sm-0 col-0"></div>
            <div class="col-lg-6 col-sm-12 col-12" id="rezInp">

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

                <div class="d-flex flex-wrap justify-content-start mt-4">
                    <p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-regular fa-comment-dots mr-2 ml-2"></i>Besondere Wünsche</p>
                    <textarea style="width: 100%; background-color:rgba(244,244,244,255); border-radius:15px; border:1px solid lightgray;" class="form-control shadow-none" 
                    id="rezComment" rows="3" placeholder="z.B. Tischwunsch, Stellplatz für Kinderwagen"></textarea>
                </div>

                <div class="d-flex flex-wrap justify-content-start mt-4" id="rezInpDiv05">
                    <p style="width: 100%; margin:0; font-size:21px;"> <i style="color: rgb(39,190,175);" class="fa-solid fa-circle-user mr-2 ml-2"></i>Personalien</p>

                    <input style="width: 100%; background-color:rgba(244,244,244,255); border-radius:15px; border:1px solid lightgray;" type="email" 
                    class="form-control mb-2 shadow-none" id="rezUsrEmail" placeholder="E-Mail">
                    <input style="width: 50%; background-color:rgba(244,244,244,255); border-radius:15px; border:1px solid lightgray;" type="text" 
                    class="form-control mb-2 shadow-none" id="rezUsrLastname" placeholder="Vorname">
                    <input style="width: 50%; background-color:rgba(244,244,244,255); border-radius:15px; border:1px solid lightgray;" type="text" 
                    class="form-control mb-2 shadow-none" id="rezUsrName" placeholder="Name">

                    <div id="sentNrForConfDiv" style="width: 100%;" class="input-group">
                        <input style="background-color:rgba(244,244,244,255); border-radius:15px 0 0 15px; border:1px solid lightgray;" 
                        type="number" class="form-control numeric shadow-none" id="rezUsrPhoneNr" placeholder="Telefon Nr.">
                        <div class="input-group-append">
                            <button style="background-color:rgba(244,244,244,255); border:1px solid lightgray; border-radius:0 15px 15px 0;" 
                            class="btn shadow-none pl-3 pr-3" type="button" onclick="sendPhoneNrForVer()"><strong>Verifizieren</strong></button>
                        </div>
                    </div>
                    <input type="hidden" id="confCodeHashValue" value="0">
                    <input type="hidden" id="confCodePhoneNr" value="0">
                    <input type="hidden" id="phoneNrConfirmed" value="0">
                    <div id="sentCodeForConfDiv" style="width: 100%; display:none;" class="input-group">
                        <input style="background-color:rgba(244,244,244,255); border-radius:15px 0 0 15px; border:1px solid lightgray;" 
                        type="number" class="form-control numeric shadow-none" id="rezUsrConfPhoneNrCode" placeholder="Bestätigungscode aus der SMS!">
                        <div class="input-group-append">
                            <button style="background-color:rgba(244,244,244,255); border:1px solid lightgray; border-radius:0 15px 15px 0;" 
                            class="btn shadow-none pl-3 pr-3" type="button" onclick="sendConfConfCodeForVer()"><strong>Code senden</strong></button>
                        </div>
                    </div>
                    <p id="confPhoneNrParag01" style="color:gray; margin:0; font-size:10px;" class="pl-2">*Die Telefonnummer muss verifiziert werden!</p>
                    <div class="alert alert-success text-center mt-1 p-1" style="width:100%; display:none;" id="rezSuccPhoneNrConf">

                    </div>

                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg04">
                        <strong>Geben Sie eine gültige E-Mail-Adresse ein!</strong>
                    </div>
                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg05">
                        <strong>Geben Sie unbedingt Ihren Vor- und Nachnamen an!</strong>
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
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                        Ich würde gerne individuelle Angebote von QRorpa Systeme erhalten!
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck2">
                        <label class="form-check-label" for="defaultCheck2">
                        Ich würde gerne individuelle Angebote von {{$theRes->emri}} - Chur erhalten!
                        </label>
                    </div>
                    <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="rezRegErrorMsg11">
                        <strong>Sie müssen beide Kontrollkästchen aktivieren, bevor Sie fortfahren!</strong>
                    </div>

                </div>

                <div class="d-flex flex-wrap justify-content-start mt-4">
                    <button class="btn shadow-none" style="background-color:rgb(39,190,175); color:white; font-size:22px; border-radius:15px; width:100%;"
                    id="saveReservationBtn" onclick="saveReservation('{{$theResId}}')">
                        <strong>Reservierung abschliessen</strong>
                    </button>
                    <p style="width:100%; font-size:12px; text-align: justify; text-justify: inter-word;" class="mt-4">Mit deiner Reservierung bestätigst du, dass du die
                        <a style="color: black;" href="{{route('firstPage.impressum')}}"><ins>AGB</ins></a> und die <a style="color: black;" href="{{route('firstPage.datenschutz')}}"><ins>Datenschutzerklärung</ins></a> der Website
                        gelesen und akzeptiert hast.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-0 col-0"></div>
        </div>
    </div>



    <div class="d-flex flex-wrap justify-content-start mt-4 p-2" style="background-color: rgb(39,190,175);">
        <p style="width:100%; color:white; font-size:34px;" class="text-center"><strong>Kontaktieren Sie Uns!</strong></p>

        <!-- the tel and email  -->

        <div style="width:20%"></div>
        <a style="width: 20%;" class="text-center" href="https://facebook.com/qrorpa"><i style="color: white; font-size:29px;" class="fa-brands fa-facebook"></i></a>
        <a style="width: 20%;" class="text-center" href="https://instagram.com/qrorpa"><i style="color: white; font-size:29px;" class="fa-brands fa-instagram"></i></a>
        <a style="width: 20%;" class="text-center" href="#"><i style="color: white; font-size:29px;" class="fa-brands fa-google"></i></a>
        <div style="width:20%"></div>

        <div style="width: 100%; text-align:center; color:white; margin-top:80px; font-size:15px;">
            <a style="color: white;" href="{{route('firstPage.impressum')}}">Impressum</a>  |  <a style="color: white;" href="{{route('firstPage.datenschutz')}}">Datenschutz</a> | <a style="color: white;" href="{{route('firstPage.datenschutz')}}">Geschäftsbedingungen</a>
        </div>
        <div style="width: 100%;" class="text-center">
            <p style="color:#fff">  Copyright ©<script>document.write(new Date().getFullYear());</script> Alle Rechte vorbehalten | mit <i class="fa fa-heart-o" aria-hidden="true"></i> gemacht von <a href="https://kreativeidee.ch" target="_blank" style="color:#fff;">Kreative Idee</a></p>
        </div>
                
        
    </div>

    @include('tableRez.jscript')
@endsection