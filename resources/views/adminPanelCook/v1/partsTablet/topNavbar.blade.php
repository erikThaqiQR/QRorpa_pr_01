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
</style>
<input type="hidden" id="theResAdminId" value="{{$thisRestaurantId}}">


<nav style="background-color: rgb(39,190,175);" class="navbar navbar-expand-lg d-flex justify-content-between">
    <a class="navbar-brand" href="#"><img src="storage/images/logo_QRorpa_wh.png" style="width:150px; height:auto;" alt=""></a>
 

    @if(Request::is('cookPanelIndexCookT') || Request::is('cookPanelIndexCookD'))
        <?php
            $allActConfOrders = array();
            $allActConfOrdersrefuse01 = array();
            $allActConfOrdersrefuse02 = array();
            if(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1']])->count() > 0 ){
                $cookHasExtrasCount = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra']])->count();
                $cookHasTypesCount = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type']])->count();
                
                if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category']])->count() > 0){
                    $toFilterByPTE = array();
                    foreach(TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1']])->get()->sortByDesc('created_at') as $onePCatFilter){
                        $theProdreCatF = Produktet::findOrFail($onePCatFilter->prodId);
                        if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$theProdreCatF->kategoria]])->first() != NULL){
                            array_push($allActConfOrders,$onePCatFilter);
                            break;
                        }else{
                            array_push($toFilterByPTE,$onePCatFilter);
                        }
                    }
                }else{
                    $toFilterByPTE = TabOrder::where([['tabCode','!=','0'],['toRes',Auth::User()->sFor],['status','1']])->get()->sortByDesc('created_at');
                }
        
                if(count($toFilterByPTE) > 0){
                    // product access
                    foreach($toFilterByPTE as $toF01){
                        $acsToThisProd = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$toF01->prodId]])->first();
                        if($acsToThisProd != NULL){
                            array_push($allActConfOrders,$toF01);
                            break;
                        }else if($toF01->OrderExtra != 'empty'){
                            array_push($allActConfOrdersrefuse01,$toF01);
                        }else if($toF01->OrderType != 'empty'){
                            array_push($allActConfOrdersrefuse02,$toF01);
                        }
                    }
                    // Extra access
                    if($cookHasExtrasCount > 0 && count($allActConfOrdersrefuse01) > 0){
                        foreach($allActConfOrdersrefuse01 as $oneOrRef01){
                            $theProdref01 = Produktet::findOrFail($oneOrRef01->prodId);
                            $hasAnExtra = 0;
                            foreach(explode('--0--',$oneOrRef01->OrderExtra) as $oneExtraref01){
                                $oEf012D = explode('||',$oneExtraref01);
        
                                $oneExtraref01ID = ekstra::where([['toCat',$theProdref01->kategoria],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                                if($oneExtraref01ID != NULL){
                                    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                        $hasAnExtra = 1;
                                        break;
                                    }
                                }
                            }
                            if( $hasAnExtra == 1){
                                array_push($allActConfOrders,$oneOrRef01);
                                break;
                            }
                        }
                    }
                    // Type access
                    if($cookHasTypesCount > 0 && count($allActConfOrdersrefuse02) > 0){
                        foreach($allActConfOrdersrefuse02 as $oneOrRef02){
                            $theProdref02 = Produktet::findOrFail($oneOrRef02->prodId);
                            $oTf012D = explode('||',$oneOrRef02->OrderType);
                            $oneTyperef01ID = LlojetPro::where([['kategoria',$theProdref02->kategoria],['emri',$oTf012D[0]],['vlera',$oTf012D[1]]])->first();
                            if($oneTyperef01ID != NULL){
                                if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                    array_push($allActConfOrders,$oneOrRef02);
                                    break;
                                }
                            }
                        }
                    }
                }
            }else{
                $noOrdersYet = 1;
            }
        ?>
        @if(count($allActConfOrders) > 0)
        <a href="{{ route('cookPnl.cookPanelIndexCook') }}" style="width:175px; background-color:red; color:white; font-size:1.6rem;" class="btn btn-danger table-glow-adminAlert">
            <strong>Restaurant</strong>
        </a>
        @else
        <a href="{{ route('cookPnl.cookPanelIndexCook') }}" style="width:175px; background-color:white; color:rgb(39,190,175); font-size:1.6rem;" class="btn btn-default">
            <strong>Restaurant</strong>
        </a>
        @endif
    @endif







    @if(cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Takeaway']])->first() != NULL && (Request::is('cookPanelIndexCook') || Request::is('cookPanelIndexCookD')))
        <?php
            $allActConfOrders = array();
            $allActConfOrdersrefuse01 = array();
            $allActConfOrdersrefuse02 = array();
            if(taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1']])->count() > 0 ){
                $cookHasExtrasCount = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra']])->count();
                $cookHasTypesCount = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type']])->count();

                if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category']])->count() > 0){
                    $toFilterByPTE = array();
                    foreach(taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1']])->get()->sortByDesc('created_at') as $onePCatFilter){
                        if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Category'],['contentId',$onePCatFilter->prodCat]])->first() != NULL){
                            array_push($allActConfOrders,$onePCatFilter);
                            break;
                        }else{
                            array_push($toFilterByPTE,$onePCatFilter);
                        }
                    }
                }else{
                    $toFilterByPTE = taDeForCookOr::where([['toRes',Auth::User()->sFor],['serviceType','1']])->get()->sortByDesc('created_at');
                }

                if(count($toFilterByPTE) > 0){
                    // product access
                    foreach($toFilterByPTE as $toF01){
                        $acsToThisProd = cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Product'],['contentId',$toF01->prodId]])->first();
                        if($acsToThisProd != NULL){
                            array_push($allActConfOrders,$toF01);
                            break;
                        }else if($toF01->prodExtra != 'empty'){
                            array_push($allActConfOrdersrefuse01,$toF01);
                        }else if($toF01->prodType != 'empty'){
                            array_push($allActConfOrdersrefuse02,$toF01);
                        }
                    }
                    // Extra access
                    if($cookHasExtrasCount > 0 && count($allActConfOrdersrefuse01) > 0){
                        foreach($allActConfOrdersrefuse01 as $oneOrRef01){
                            if($oneOrRef01->prodId != 0){
                                $theProdref01 = Produktet::findOrFail($oneOrRef01->prodId);
                                $hasAnExtra = 0;
                                foreach(explode('--0--',$oneOrRef01->prodExtra) as $oneExtraref01){
                                    $oEf012D = explode('||',$oneExtraref01);

                                    $oneExtraref01ID = ekstra::where([['toCat',$theProdref01->kategoria],['emri',$oEf012D[0]],['qmimi',$oEf012D[1]]])->first();
                                    if($oneExtraref01ID != NULL){
                                        if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Extra'],['contentId',$oneExtraref01ID->id]])->first() != NULL){
                                            $hasAnExtra = 1;
                                            break;
                                        }
                                    }
                                }
                                if( $hasAnExtra == 1){
                                    array_push($allActConfOrders,$oneOrRef01);
                                    break;
                                }
                            }
                        }
                    }
                    // Type access
                    if($cookHasTypesCount > 0 && count($allActConfOrdersrefuse02) > 0){
                        foreach($allActConfOrdersrefuse02 as $oneOrRef02){
                            if($oneOrRef02->prodId != 0){
                                $theProdref02 = Produktet::findOrFail($oneOrRef02->prodId);
                                $oTf022D = explode('||',$oneOrRef02->prodType);
                                if(isset($oTf022D[1])){
                                    $oneTyperef01ID = LlojetPro::where([['kategoria',$theProdref02->kategoria],['emri',$oTf022D[0]],['vlera',$oTf022D[1]]])->first();
                                }else{
                                    $oneTyperef01ID = LlojetPro::where([['kategoria',$theProdref02->kategoria],['emri',$oTf022D[0]]])->first();
                                }
                                if($oneTyperef01ID != NULL){
                                    if(cooksProductSelection::where([['workerId',Auth::User()->id],['contentType','Type'],['contentId',$oneTyperef01ID->id]])->first() != NULL){
                                        array_push($allActConfOrders,$oneOrRef02);
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                $noOrdersYet = 1;
            }
        ?>
    
        @if(count($allActConfOrders) > 0) 
        <a href="{{ route('cookPnl.cookPanelIndexCookT') }}" style="width:175px; background-color:red; color:white; font-size:1.6rem;" class="btn btn-danger table-glow-adminAlert">
            <strong>Takeaway</strong>
        </a>
        @else
        <a href="{{ route('cookPnl.cookPanelIndexCookT') }}" style="width:175px; background-color:white; color:rgb(39,190,175); font-size:1.6rem;" class="btn btn-default">
            <strong>Takeaway</strong>
        </a>
        @endif
    @endif

    @if(cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Delivery']])->first() != NULL && (Request::is('cookPanelIndexCookT') || Request::is('cookPanelIndexCook')))
    <a href="{{ route('cookPnl.cookPanelIndexCookD') }}" style="width:175px; background-color:white; color:rgb(39,190,175); font-size:1.6rem;" class="btn btn-default">
        <strong>Delivery</strong>
    </a>
    @endif

    <a style="color: white; width:150px; font-size:1.6rem;" class="mr-4" href="{{ route('logout') }}"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        <strong> {{ __('adminP.logOut') }}</strong>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</nav>