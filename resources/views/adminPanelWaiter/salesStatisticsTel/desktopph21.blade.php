<?php

    use App\Restorant;
    use Carbon\Carbon;

    $theRes = Restorant::find(Auth::user()->sFor);
?>

    <div class="card mt-2" style="width: 100%; display:none;" id="VerkaufsstatistikenPH21Tel">
        <div class="card-header">
            <strong>{{__('adminP.chooseDate')}}</strong>
        </div>
        <div style="width: 100%;" class="d-flex flex-wrap justify-content-between p-1">
            <?php  
                $month = Carbon::now(); 
                $monthM1 = Carbon::now()->subMonth(); 
                $monthM2 = Carbon::now()->subMonth(2); 
                $monthM3 = Carbon::now()->subMonth(3); 
                //2022-01-08 16:23:26 
            ?>

            <?php
                $monthCount = Carbon::now()->month;
                $yearCount = Carbon::now()->year;

                $resCreated =explode(' ',Restorant::find(Auth::user()->sFor)->created_at)[0];
                $resCreatedM = explode('-', $resCreated)[1];
                $resCreatedY = explode('-', $resCreated)[0];

                // echo ''.$monthCount.' >= '. $resCreatedM.'  '. $yearCount.'>='.$resCreatedY;
            ?>
                @while(true)
                    @if(($monthCount >= $resCreatedM && $yearCount == $resCreatedY) || $yearCount > $resCreatedY )

                    <p style="width:100%;" class="text-left prl-2"><strong >
                        <?php
                            switch($monthCount){
                                case 1: echo  __('adminP.jan'). "/".$yearCount.""; break;
                                case 2: echo __('adminP.feb'). "/".$yearCount.""; break;
                                case 3: echo __('adminP.march'). "/".$yearCount.""; break;
                                case 4: echo __('adminP.apr'). "/".$yearCount.""; break;
                                case 5: echo __('adminP.May'). "/".$yearCount.""; break;
                                case 6: echo __('adminP.june'). "/".$yearCount.""; break;
                                case 7: echo __('adminP.july'). "/".$yearCount.""; break;
                                case 8: echo __('adminP.aug'). "/".$yearCount.""; break;
                                case 9: echo __('adminP.sept'). "/".$yearCount.""; break;
                                case 10: echo __('adminP.oct'). "/".$yearCount.""; break;
                                case 11: echo __('adminP.nov'). "/".$yearCount.""; break;
                                case 12: echo __('adminP.dec'). "/".$yearCount.""; break;   
                            }
                            $month = new Carbon($yearCount.'-'.$monthCount.'-01');
                        ?>
                    </strong></p>
                    <div style="width:100%;" class="d-flex flex-wrap justify-content-between mb-2">
                        @for($i=1;$i<=$month->daysInMonth;$i++)
                            <?php
                                if($i < 10){
                                    $d= '0'.$i;
                                }else{
                                    $d= $i;
                                }
                                 $dateCheckCreate = new Carbon($yearCount.'-'.$monthCount.'-'.$d);
                            ?>
                            @if($dateCheckCreate >= $theRes->created_at)
                                <button class="btn btn-outline-dark" onclick="selectPH21Tel('{{$dateCheckCreate}}')" style="width:11.1%; padding:0px;">{{$i}}</button>
                            @else
                               <button class="btn btn-outline-dark" style="width:11.1%;"></button>
                            @endif
                        @endfor

                            <?php
                                $empty = 31-$month->daysInMonth;
                                switch($empty){
                                    case 1:
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                    break;
                                    case 2:
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                    break;
                                    case 3:
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                    break;
                                    default:
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> ';
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                        echo '<button class="btn btn-outline-dark" style="width:11.1%;"></button> '; 
                                  
                                }  
                            ?>
                    </div>

                        <!-- Pjesa per vitin  -->
                        @if($monthCount == 1)
                            <?php
                                $yearCount--;
                                $monthCount=12;
                            ?>
                        @else
                            <?php
                                $monthCount--;
                            ?>
                        @endif
                    @else
                        <!-- echo 'nuk po merr asnje'; -->
                        @break;
                    @endif 
                @endwhile


           
            

         
        </div>
    </div>