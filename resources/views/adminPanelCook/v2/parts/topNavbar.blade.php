<?php

use App\ekstra;
use App\TabOrder;
use App\LlojetPro;
use App\Produktet;

    use App\taDeForCookOr;
    use Illuminate\Support\Facades\Auth;

    use App\cooksProductSelection;
    use App\Restorant;
    use App\Orders;
    use Carbon\Carbon;

    $thisRestaurantId = Auth::user()->sFor;

    echo '<input type="hidden" id="thisResId" value="'.Auth::user()->sFor.'">';

    $nowDate = date('Y-m-d');
?>

<style>  
    @keyframes glowing {
        0% { box-shadow: 0 0 -10px red; background-position: 0 0;}
        40% { box-shadow: 0 0 20px red; }
        60% { box-shadow: 0 0 20px red; }
        100% { box-shadow: 0 0 -10px red; background-position: 1280px 0;}
    }

    .table-glow-adminAlert {
        animation: glowing 1000ms linear infinite;
        border-radius: 6px;
        cursor: pointer;
    }

    .pointerH:hover{
        cursor: pointer;
    }
</style>
<input type="hidden" id="theResAdminId" value="{{$thisRestaurantId}}">


<nav id="cookPanelNavBar" style="background-color: rgb(39,190,175);" class="navbar navbar-expand-lg d-flex justify-content-between">
    <a class="navbar-brand" href="#"><img src="storage/images/logo_QRorpa_wh.png" style="width:200px; height:auto;" alt=""></a>

    @if(Request::is('cookPanelIndexCook') || Request::is('cookPanelIndexCookNotConf'))
    <div class="d-flex" style="width:400px; ">
        <a href="{{ route('cookPnl.cookPanelIndexCook') }}" class="text-center pt-2 pb-2 pointerH" style="width:200px; background-color:rgba(191,191,191,255); color:rgba(63,63,63,255); border-radius:5px; font-size:1.3rem; margin:0px;">
            <strong>Offen</strong>
        </a>
        <a href="{{ route('cookPnl.cookPanelIndexCookNotConf') }}" class="text-center pt-2 pb-2 pointerH" style="width:200px; background-color:rgba(4,178,89,255); color:rgba(63,63,63,255); border-radius:5px; font-size:1.3rem; margin:0px;">
            <strong>Bereit</strong>
        </a>
    </div>
    @elseif (Request::is('cookPanelIndexCookT') || Request::is('cookPanelIndexCookTNotConf'))
    <div class="d-flex" style="width:400px; ">
        <a href="{{ route('cookPnl.cookPanelIndexCookT') }}" class="text-center pt-2 pb-2 pointerH" style="width:200px; background-color:rgba(191,191,191,255); color:rgba(63,63,63,255); border-radius:5px; font-size:1.3rem; margin:0px;">
            <strong>Offen</strong>
        </a>
        <a href="{{ route('cookPnl.cookPanelIndexCookTNotConf') }}" class="text-center pt-2 pb-2 pointerH" style="width:200px; background-color:rgba(4,178,89,255); color:rgba(63,63,63,255); border-radius:5px; font-size:1.3rem; margin:0px;">
            <strong>Bereit</strong>
        </a>
    </div>
    @endif
 
    @if(Request::is('cookPanelIndexCookT') || Request::is('cookPanelIndexCookTNotConf') || Request::is('cookPanelIndexCookD'))
        <?php
            $hasCateAccess = False;
            $hasProdAccess = False;
            $hasTypeAccess = False;
            $hasExtrAccess = False;
            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category']])->count() > 0){$hasCateAccess = True;}
            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product']])->count() > 0){$hasProdAccess = True;}
            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type']])->count() > 0){$hasTypeAccess = True;}
            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra']])->count() > 0){$hasExtrAccess = True;}
            
            $allTables = array();
            foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1']])->orderBy('created_at')->get() as $oTOr){
                if($oTOr->OrderSasia != $oTOr->OrderSasiaDone){
                    if(!in_array($oTOr->tableNr,$allTables)){
                        if($hasProdAccess && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$oTOr->prodId]])->first() != NULL){
                            array_push($allTables,$oTOr->tableNr);
                            break;
                        }else{
                            $tp = Produktet::find($oTOr->prodId);
                            if($hasCateAccess){
                                if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$tp->kategoria]])->first() != NULL){
                                    array_push($allTables,$oTOr->tableNr);
                                    break;
                                }
                            }else if($hasTypeAccess){
                                if($oTOr->OrderType != 'empty'){
                                    $oTf012D = explode('||',$oTOr->OrderType);
                                    $oneTyperef01ID = LlojetPro::where([['kategoria',$tp->kategoria],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                                    if($oneTyperef01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                        array_push($allTables,$oTOr->tableNr);
                                        break;
                                    }
                                }

                            }else if($hasExtrAccess){
                                $hasAnExtra = False;
                                foreach(explode('--0--',$oTOr->OrderExtra) as $oneExtraref01){

                                    $oEf012D = explode('||',$oneExtraref01);
                                    $oneExtraref01ID = ekstra::where([['toCat',$tp->kategoria],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                                    if($oneExtraref01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                        $hasAnExtra = True;
                                        break;
                                    }
                                }
                                if($hasAnExtra){
                                    array_push($allTables,$oTOr->tableNr);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        ?>
        @if(count($allTables) > 0)
        <a href="{{ route('cookPnl.cookPanelIndexCook') }}" style="width:200px; background-color:red; color:white; font-size:1.3rem;" class="btn btn-danger table-glow-adminAlert">
            <strong>Restaurant</strong>
        </a>
        @else
        <a href="{{ route('cookPnl.cookPanelIndexCook') }}" style="width:200px; background-color:white; color:rgb(39,190,175); font-size:1.3rem;" class="btn btn-default">
            <strong>Restaurant</strong>
        </a>
        @endif
    @endif

    @if(cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Takeaway']])->first() != NULL && (Request::is('cookPanelIndexCook') || Request::is('cookPanelIndexCookNotConf') || Request::is('cookPanelIndexCookD')))
        
        <?php
            $hasCateAccess = False;
            $hasProdAccess = False;
            $hasTypeAccess = False;
            $hasExtrAccess = False;
            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category']])->count() > 0){$hasCateAccess = True;}
            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product']])->count() > 0){$hasProdAccess = True;}
            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type']])->count() > 0){$hasTypeAccess = True;}
            if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra']])->count() > 0){$hasExtrAccess = True;}
            
            $allOrdersTA = array();
            foreach(taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1']])->get()->sortByDesc('created_at') as $taOr){
                if(!in_array($taOr->orderId,$allOrdersTA)){
                    if($hasCateAccess && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$taOr->prodCat]])->first() != NULL){
                        array_push($allOrdersTA,$taOr->orderId);
                        break;
                    }else if($hasProdAccess && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$taOr->prodId]])->first() != NULL){
                        array_push($allOrdersTA,$taOr->orderId);
                        break;
                    }else if($hasTypeAccess){
                        if($taOr->prodType != 'empty'){
                            $oTf012D = explode('||',$taOr->prodType);
                            $oneTyperef01ID = LlojetPro::where([['kategoria',$taOr->prodCat],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                            if($oneTyperef01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                array_push($allOrdersTA,$taOr->orderId);
                                break;
                            }
                        }
                    }else if($hasExtrAccess){
                        $hasAnExtra = False;
                        foreach(explode('--0--',$taOr->prodExtra) as $oneExtraref01){
        
                            $oEf012D = explode('||',$oneExtraref01);
                            $oneExtraref01ID = ekstra::where([['toCat',$taOr->prodCat],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                            if($oneExtraref01ID != NULL && cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                $hasAnExtra = True;
                                break;
                            }
                        }
                        if($hasAnExtra){
                            array_push($allOrdersTA,$taOr->orderId);
                            break;
                        }
                    }
                }
            }
        ?>
    
        @if(count($allOrdersTA) > 0)
            <a href="{{ route('cookPnl.cookPanelIndexCookT') }}" style="width:200px; background-color:red; color:white; font-size:1.3rem;" class="btn btn-danger table-glow-adminAlert">
                <strong>Takeaway</strong>
            </a>
        @else
            <a href="{{ route('cookPnl.cookPanelIndexCookT') }}" style="width:200px; background-color:white; color:rgb(39,190,175); font-size:1.3rem;" class="btn btn-default">
                <strong>Takeaway</strong>
            </a>
        @endif
    @endif

    @if(cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Delivery']])->first() != NULL && (Request::is('cookPanelIndexCookT') || Request::is('cookPanelIndexCookTNotConf') || Request::is('cookPanelIndexCook') || Request::is('cookPanelIndexCookNotConf')))
    <a href="{{ route('cookPnl.cookPanelIndexCookD') }}" style="width:200px; background-color:white; color:rgb(39,190,175); font-size:1.3rem;" class="btn btn-default">
        <strong>Delivery</strong>
    </a>
    @endif

    <a style="color: white; width:250px; font-size:1.6rem;" class="mr-4" href="{{ route('logout') }}"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        <strong> {{ __('adminP.logOut') }}</strong>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</nav>