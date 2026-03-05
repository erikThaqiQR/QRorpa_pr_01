<?php
    use App\PiketLog;
    use App\User;
    use App\Restorant;
    use Carbon\Carbon;

    if(isset($_GET['Res'])){
        $theRes = $_GET['Res'];
    }else{
        header("Location: ".route('piket.index'));
        exit();
    }

    $resD = explode(' ' ,Restorant::find($theRes)->created_at)[0];

    $resCreatedM = explode('-',$resD)[1];
    $resCreatedY = explode('-',$resD)[0];


    $monthCount = Carbon::now()->month;
    $yearCount = Carbon::now()->year;


    $pPlus = PiketLog::where('toRes', $theRes)->where('piket', '>' , '0')->whereYear('created_at',$yearCount)->whereMonth('created_at',$monthCount)->get()->sum('piket');
    $pMinus = PiketLog::where('toRes', $theRes)->where('piket', '<' , '0')->whereYear('created_at',$yearCount)->whereMonth('created_at',$monthCount)->get()->sum('piket') * (-1);

    
?>
<style>
    .anchorsRem{
        color:white;
    }
    .anchorsRem:hover{
        color:white;
        text-decoration: none;
        font-size: 19px;
    }
    .otherMonths:hover{
        cursor: pointer;
        color: rgb(39, 190, 175);
    }
</style>
<section class="pl-3 pr-3 pt-5 pb-5">
    <div class="d-flex justify-content-between">
        <a style="width:5%;" class="anchorsRem test-left" href="{{route('piket.indexRes')}}" expand="true">
            <button class="btn btn-block" style="color:rgb(39, 190, 175); font-weight:bold; font-size:30px; outline: none;">
                <
            </button>
        </a>
         <h3 style="font-weight:bolder; width:45%;" class="color-qrorpa text-left mt-3">{{Restorant::find($theRes)->emri}}</h3>
         <h3 style="font-weight:bolder; width:45%;" class="color-qrorpa text-right mt-3 pr-4">Points</h3>
    </div>
    <div class="d-flex justify-content-between">
        <div style="width:24%; font-weight:bold; font-size:21px;" class="p-3 text-center otherMonths" data-toggle="modal" data-target="#otherMonths">
            <?php
                 switch($monthCount){
                    case 1: echo " Jnuary - ".$yearCount; break;
                    case 2: echo " Febuary - ".$yearCount; break;
                    case 3: echo " March - ".$yearCount; break;
                    case 4: echo " April - ".$yearCount; break;
                    case 5: echo " May - ".$yearCount; break;
                    case 6: echo " June - ".$yearCount; break;
                    case 7: echo " July - ".$yearCount; break;
                    case 8: echo " August - ".$yearCount; break;
                    case 9: echo " September - ".$yearCount; break;
                    case 10: echo " October - ".$yearCount; break;
                    case 11: echo " November - ".$yearCount; break;
                    case 12: echo " December - ".$yearCount; break;   
                }
            ?>
        
        </div>
        <div style="width:24%; background-color:rgb(39, 190, 175); color:white; font-weight:bold; border-radius:30px; font-size:21px;" class="text-center p-3">
           Points Earned : {{$pPlus}}
        </div>
        <div style="width:24%; background-color:rgb(39, 190, 175); color:white; font-weight:bold; border-radius:30px; font-size:21px;" class="text-center p-3">
            Points Used : {{$pMinus}}
        </div>
        <div style="width:24%; background-color:rgb(39, 190, 175); color:white; font-weight:bold; border-radius:30px; font-size:21px;" class="text-center p-3">
            Discount :  {{$pMinus * 0.01}} CHF
        </div>
     

    </div>
    <hr>













<!-- The Modal -->
<div class="modal" id="otherMonths" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Select a month to proceed</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
            <div class="d-flex justify">
                <?php

                    while(true){
                        
                        if($monthCount >= $resCreatedM && $yearCount >= $resCreatedY){
                            echo "
                                <div class='b-qrorpa color-white text-center p-2 m-2' style='border-radius:20px; width:25%; font-size:19px; font-weight:bold;'>
                                    <a class='color-white anchorsRem' href='PiketResOneMY?Res=".$theRes."&mo=".$monthCount."&ye=".$yearCount."'>
                                        ( ".$monthCount." )";
                                        switch($monthCount){
                                            case 1: echo " Januar/".$resCreatedY.""; break;
                                            case 2: echo " Februar/".$resCreatedY.""; break;
                                            case 3: echo " März/".$resCreatedY.""; break;
                                            case 4: echo " April/".$resCreatedY.""; break;
                                            case 5: echo " Mai/".$resCreatedY.""; break;
                                            case 6: echo " Juni/".$resCreatedY.""; break;
                                            case 7: echo " Juli/".$resCreatedY.""; break;
                                            case 8: echo " August/".$resCreatedY.""; break;
                                            case 9: echo " September/".$resCreatedY.""; break;
                                            case 10: echo " Oktober/".$resCreatedY.""; break;
                                            case 11: echo " November/".$resCreatedY.""; break;
                                            case 12: echo " Dezember/".$resCreatedY.""; break;   
                                        }
                            echo "    
                                    </a>
                                </div>
                            ";
                            // Pjesa per vitin 
                            if($monthCount == 1){
                                $resCreatedY--;
                                $monthCount=12;
                            }else{
                                $monthCount--;
                            }
                            
                        }else{
                            break;
                        }
                        
                    }
                ?>
            </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


































    <div class="d-flex justify-content-between flex-wrap mt-2" style="font-size:19px;">
        <p style="font-weight:bold; width:20%;"></p>
        <p style="font-weight:bold; width:20%;">Klient</p>
        <p style="font-weight:bold; width:20%;">Porosi Nr.</p>
        <p style="font-weight:bold; width:20%;">Porosi Shuma.</p>
        <p style="font-weight:bold; width:20%;">Pikët</p>
   
        @foreach(PiketLog::where('toRes', $theRes)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', $yearCount)->get()->sortByDesc('created_at') as $resPo)
            <p style=" width:20%; margin-top:-5px; border-bottom:1px solid lightgray;">{{explode(' ',$resPo->created_at)[0]}} <br> <span>{{explode(' ',$resPo->created_at)[1]}}</span> </p>
    
            @if(User::find($resPo->klienti_u) != null)
            <p style=" width:20%; border-bottom:1px solid lightgray; padding-top:5px;">{{User::find($resPo->klienti_u)->name}}</p>
            @else
                 <p style=" width:20%; color:red; border-bottom:1px solid lightgray; padding-top:5px;">Removed</p>
            @endif
            <p style=" width:20%; border-bottom:1px solid lightgray; padding-top:5px;">{{$resPo->order_u}}</p>
            <p style=" width:20%; border-bottom:1px solid lightgray; padding-top:5px;">{{$resPo->shumaPor}} <sup class="ml-2"> CHF</sup> </p>
            <p style=" width:20%; font-weight:bold; border-bottom:1px solid lightgray; padding-top:5px;">
            @if($resPo->piket > 0)+@endif
                {{$resPo->piket}}
            </p>
        @endforeach

</section>