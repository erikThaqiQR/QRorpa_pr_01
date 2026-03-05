<?php
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Trinkgeld']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\TipLog;
    use App\User;
    use App\Restorant;
    use Carbon\Carbon;

    $thisRestaurantId = Auth::user()->sFor;
?>
<style>
    .anchorHover:hover{
        color: whitesmoke;
        text-decoration: none;
    }
</style>
<section>
    <div  class="d-flex flex-wrap justify-content-between pr-2 pl-2 pb-4">
        <?php
            $monthCount = Carbon::now()->month;
            $yearCount = Carbon::now()->year;

            $resCreated =explode(' ' ,Restorant::find($thisRestaurantId)->created_at)[0];
            $resCreatedM = explode('-', $resCreated)[1];
            $resCreatedY = explode('-', $resCreated)[0];

             while(true){
               
                if($monthCount >= $resCreatedM && $yearCount >= $resCreatedY){
                    echo "
                        <div class='b-qrorpa color-white text-center p-2 m-2' style='border-radius:20px; width:25%; font-size:19px; font-weight:bold;'>
                            <a class='color-white anchorHover' href='showTips?mo=".$monthCount."&ye=".$yearCount."'>
                                ( ".$monthCount." )";
                                switch($monthCount){
                                    case 1: echo __('adminP.jan')."/".$resCreatedY.""; break;
                                    case 2: echo __('adminP.feb')."/".$resCreatedY.""; break;
                                    case 3: echo __('adminP.march')."/".$resCreatedY.""; break;
                                    case 4: echo __('adminP.apr')."/".$resCreatedY.""; break;
                                    case 5: echo __('adminP.May')."/".$resCreatedY.""; break;
                                    case 6: echo __('adminP.june')."/".$resCreatedY.""; break;
                                    case 7: echo __('adminP.july')."/".$resCreatedY.""; break;
                                    case 8: echo __('adminP.aug')."/".$resCreatedY.""; break;
                                    case 9: echo __('adminP.sept')."/".$resCreatedY.""; break;
                                    case 10: echo __('adminP.oct')."/".$resCreatedY.""; break;
                                    case 11: echo __('adminP.nov')."/".$resCreatedY.""; break;
                                    case 12: echo __('adminP.dec')."/".$resCreatedY.""; break;   
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

</section>