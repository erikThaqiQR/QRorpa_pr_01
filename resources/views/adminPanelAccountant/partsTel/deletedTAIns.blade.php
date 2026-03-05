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
</style>
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">





 @if(!$agent->isTablet())
    <table class="table table-hover" id="desktopTable">
        <thead>
        <tr>
            <th style="opacity:50%;">{{__('adminP.time')}}</th>
            <th style="opacity:50%;">Produktname<br>Produktbeschreibung</th>
            <th style="opacity:50%;">Mitarbeiter<br>Löschgrund</th>
        </tr>
        </thead>
        <tbody>
            @foreach(tabOrderDelete::where('toRes',Auth::user()->sFor)->orderByDesc('created_at')->get() as $delInsOne)
                <?php
                    $delInsOneDate2D= explode('-',explode(' ',$delInsOne->created_at)[0]); 
                    $delInsOneTime2D= explode(':',explode(' ',$delInsOne->created_at)[1]); 
                ?>
                    <tr class="openOrderRow">
                        <td>
                            <p>
                            {{$delInsOneTime2D[0]}}:{{$delInsOneTime2D[1]}}<br>
                            {{$delInsOneDate2D[2]}}.{{$delInsOneDate2D[1]}}.{{$delInsOneDate2D[0]}} <br>
                            T : 
                            @if ($delInsOne->tableNr == 500 )
                                <strong class="pl-2">Takeaway</strong>
                            @elseif ($delInsOne->tableNr == 9000 )
                                <strong class="pl-2">Delivery</strong>
                            @else
                                <strong class="pl-2">{{$delInsOne->tableNr}}</strong>
                            @endif
                            </p>
                        </td>

                        <td>
                            <p>
                            <strong>{{$delInsOne->prodName}}</strong> <br>
                            {{$delInsOne->prodPershkrimi}}
                            </p>
                            <p>
                            @if ($delInsOne->prodTipi != Null && $delInsOne->prodTipi != 'empty' && $delInsOne->prodTipi != '')
                                {{explode('||',$delInsOne->prodTipi)[0]}}
                            @endif  
                            <br>
                            @if ($delInsOne->prodEkstra != Null && $delInsOne->prodEkstra != 'empty' && $delInsOne->prodEkstra != '')
                                @foreach (explode('--0--',$delInsOne->prodEkstra) as $oneEx)
                                    <span class="mr-2">{{explode('||',$oneEx)[0]}}</span>
                                @endforeach
                            @endif
                            </p>
                            @if ($delInsOne->prodKomenti != Null)
                            <p>Kommentar: {{$delInsOne->prodKomenti}}</p>
                            @endif
                        </td>
                        <td>
                            <?php $theWoo = User::find($delInsOne->byId);?>
                            @if ($theWoo != NULL)
                                <p>
                                    #: {{$theWoo->id}} <br>
                                    N: {{$theWoo->name}}
                                </p>
                            @endif
                           <p style="color:red;"><strong>{{$delInsOne->deleteKomenti}}</strong></p>
                        </td>
                  
                    </tr>

            @endforeach
            
        </tbody>
    </table>
    @endif