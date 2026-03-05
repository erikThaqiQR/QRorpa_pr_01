<?php

use Illuminate\Support\Facades\Auth;

    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Statistiken']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\User;
    use App\Restorant;
    use Carbon\Carbon;
    use App\tabOrderDelete;

    use Jenssegers\Agent\Agent;
    $agent = new Agent();

    $thisRestaurantId = Auth::user()->sFor;

    if(isset($_GET['mo']) && isset($_GET['ye'])){
        $taOrDel = tabOrderDelete::where([['toRes',Auth::user()->sFor],['byId','!=','441'],['byId','!=','442'],['byId','!=','443']])
        ->whereMonth('created_at',sprintf("%02d", $_GET['mo']))
        ->whereYear('created_at',$_GET['ye'])->orderByDesc('created_at')->get();
    }else{
        $taOrDel = tabOrderDelete::where([['toRes',Auth::user()->sFor],['byId','!=','441'],['byId','!=','442'],['byId','!=','443']])
        ->whereMonth('created_at',Carbon::now()->month)
        ->whereYear('created_at',Carbon::now()->year)->orderByDesc('created_at')->get();
    }

?>
 <style>
    .openOrderRow{
        border-bottom:1px solid lightgray;

    }
    .openOrderRow:hover{
       cursor:pointer;
    }

    
    .otherMonthsBtn:hover{
        color:white;
        background-color: rgb(39,190,175);
        font-size: 20px;
    }
    .otherMonthsBtn{
        color:rgb(39,190,175);     
        border:1px solid rgb(39,195,175);
        font-size: 20px;
    }

    .anchorHover:hover{
        color: whitesmoke;
        text-decoration: none;
    }
    .otherMnBtn{
        border: 1px solid rgb(72,81,87);
        border-radius: 7px;
        width: 70%;
        color:rgb(72,81,87);

    }
</style>
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <div class="d-flex justify-content-between mb-2">
        @if(isset($_GET['mo']) && isset($_GET['ye'])){
            <p style="color:rgb(72,81,87); width:29%; font-weight:bold; font-size:1.4rem; margin:0;" class="text-center">{{sprintf("%02d", $_GET['mo'])}}.{{$_GET['ye']}}</p>
        @else
        <p style="color:rgb(72,81,87); width:29%; font-weight:bold; font-size:1.4rem; margin:0;" class="text-center">{{sprintf("%02d", Carbon::now()->month)}}.{{Carbon::now()->year}}</p>
        @endif
        <button class="btn otherMnBtn shadow-none" data-toggle="modal" data-target="#selectOtherMonth">
            <strong>Ändern Sie den Monat.Jahr</strong>
        </button>
    </div>

    
    <!-- The Modal -->
    <div class="modal" id="selectOtherMonth" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header" style="background-color:rgb(39,190,175);">
                    <h4 style="color:white;" class="modal-title">{{__('adminP.recoverMonths')}}</h4>
                    <button style="color:white;" type="button" class="close" data-dismiss="modal"> X </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div  class="d-flex flex-wrap justify-content-between pr-2 pl-2 pb-4">
                        <?php
                            $monthCount = Carbon::now()->month;
                            $yearCount = Carbon::now()->year;

                            $resCreated =explode(' ',Restorant::find(Auth::user()->sFor)->created_at)[0];
                            $resCreatedM = explode('-', $resCreated)[1];
                            $resCreatedY = explode('-', $resCreated)[0];

                            // echo ''.$monthCount.' >= '. $resCreatedM.'  '. $yearCount.'>='.$resCreatedY;
                            while(true){
                                if(($monthCount >= $resCreatedM && $yearCount == $resCreatedY) || $yearCount > $resCreatedY ){
                                    echo "
                                        <div class='color-white text-center p-3 mb-2' style='border-radius:7px; width:30%; font-size:19px; font-weight:bold; background-color:rgb(72,81,87);'>
                                            <a class='color-white anchorHover' href='AdminStatsDeletedTAProdsPage?mo=".$monthCount."&ye=".$yearCount."'>
                                                ( ".$monthCount." )";
                                                switch($monthCount){
                                                    case 1: echo   __('adminP.jan')."/".$yearCount.""; break;
                                                    case 2: echo __('adminP.feb')."/".$yearCount.""; break;
                                                    case 3: echo __('adminP.march')."/".$yearCount.""; break;
                                                    case 4: echo __('adminP.apr')."/".$yearCount.""; break;
                                                    case 5: echo __('adminP.May')."/".$yearCount.""; break;
                                                    case 6: echo __('adminP.june')."/".$yearCount.""; break;
                                                    case 7: echo __('adminP.july')."/".$yearCount.""; break;
                                                    case 8: echo __('adminP.aug')."/".$yearCount.""; break;
                                                    case 9: echo __('adminP.sept')."/".$yearCount.""; break;
                                                    case 10: echo __('adminP.oct')."/".$yearCount.""; break;
                                                    case 11: echo __('adminP.nov')."/".$yearCount.""; break;
                                                    case 12: echo __('adminP.dec')."/".$yearCount.""; break;   
                                                }
                                    echo "    
                                            </a>
                                        </div>
                                    ";
                                    // Pjesa per vitin 
                                    if($monthCount == 1){
                                        $yearCount--;
                                        $monthCount=12;
                                    }else{
                                        $monthCount--;
                                    }

                                }else{
                                    // echo 'nuk po merr asnje';
                                    break;
                                }

                               }
                           ?>
                    </div>
                </div>
            </div>
        </div>
    </div>




  



    @if (count($taOrDel) == 0)
        <p style="color:rgb(72,81,87); font-size:1.4rem; margin:0; padding:15px;" class="text-center"><strong>Es sind keine Beispiele zum Anzeigen vorhanden</strong></p>
    
    @else
        <table class="table table-hover" id="desktopTable">
            <thead>
            <tr>
                <th style="opacity:50%;">{{__('adminP.time')}}</th>
                <th style="opacity:50%;">{{__('adminP.table')}}</th>
                <th style="opacity:50%;">Produktname<br>Produktbeschreibung</th>
                <th style="opacity:50%;">Typ<br>Extra</th>
                <th style="opacity:50%;">Bestellkommentar</th>
                <th style="opacity:50%;">Menge <br>Preis</th>
                <th style="opacity:50%;">Mitarbeiter</th>
                <th style="opacity:50%;">Löschgrund</th>

                <!-- <th style="opacity:50%;">e-bank</th> -->
            </tr>
            </thead>
            <tbody>
                @foreach($taOrDel as $delInsOne)
                    <?php
                        $delInsOneDate2D= explode('-',explode(' ',$delInsOne->created_at)[0]); 
                        $delInsOneTime2D= explode(':',explode(' ',$delInsOne->created_at)[1]); 
                    ?>
                        <tr class="openOrderRow">
                            <td>
                                <p>
                                {{$delInsOneTime2D[0]}}:{{$delInsOneTime2D[1]}}<br>
                                {{$delInsOneDate2D[2]}}.{{$delInsOneDate2D[1]}}.{{$delInsOneDate2D[0]}}
                                </p>
                            </td>
                            <td>
                                @if ($delInsOne->tableNr == 500 )
                                    <p><strong class="pl-2">Takeaway</strong></p>
                                @elseif ($delInsOne->tableNr == 9000 )
                                    <p><strong class="pl-2">Delivery</strong></p>
                                @else
                                    <p><strong class="pl-2">{{$delInsOne->tableNr}}</strong></p>
                                @endif
                            </td>

                            <td>
                                <p>
                                <strong>{{$delInsOne->prodName}}</strong> <br>
                                {{$delInsOne->prodPershkrimi}}
                                </p>
                            </td>
                            <td>  
                                <p>
                                @if ($delInsOne->prodTipi != Null && $delInsOne->prodTipi != 'empty' && $delInsOne->prodTipi != '')
                                    {{explode('||',$delInsOne->prodTipi)[0]}}
                                @else
                                ---
                                @endif  
                                <br>
                                @if ($delInsOne->prodEkstra != Null && $delInsOne->prodEkstra != 'empty' && $delInsOne->prodEkstra != '')
                                    @foreach (explode('--0--',$delInsOne->prodEkstra) as $oneEx)
                                        <span class="mr-2">{{explode('||',$oneEx)[0]}}</span>
                                    @endforeach
                                @else
                                ---
                                @endif
                                </p>
                            </td>
                            <td>
                                @if ($delInsOne->prodKomenti != Null)
                                <p>{{$delInsOne->prodKomenti}}</p>
                                @else
                                <p>---</p>
                                @endif
                                
                            </td>
                            <td>
                                <p>
                                    {{$delInsOne->prodSasia}} X
                                    <br>{{number_format($delInsOne->prodQmimi,2,'.','')}} CHF
                                </p>
                            </td>
                            <td>
                                <?php $theWoo = User::find($delInsOne->byId);?>
                                @if ($theWoo != NULL)
                                    <p>
                                        ID: {{$theWoo->id}} <br>
                                        Name: {{$theWoo->name}}
                                    </p>
                                @endif
                            </td>
                            <td>
                            <p style="color:red;"><strong>{{$delInsOne->deleteKomenti}}</strong></p>
                            </td>
                    
                        </tr>

                @endforeach
                
            </tbody>
        </table>
    @endif
