<?php

use App\ekstra;
use App\TabOrder;
use App\cookColor;
use App\LlojetPro;

    use App\taDeForCookOr;
    use Illuminate\Support\Facades\Auth;

    use App\cooksProductSelection;
    use App\Restorant;
    use App\Orders;
use App\Produktet;
use App\resPlates;
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

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    input[type=number] {
    -moz-appearance: textfield;
    }
</style>
<input type="hidden" id="theResAdminId" value="{{$thisRestaurantId}}">


<nav id="cookPanelNavBar" style="background-color: rgb(39,190,175);" class="navbar navbar-expand-lg d-flex justify-content-between">
    <a class="navbar-brand" href="#"><img src="storage/images/logo_QRorpa_wh.png" style="width:200px; height:auto;" alt=""></a>

    @if(Request::is('cookPanelIndexCook') || Request::is('cookPanelIndexCookNotConf'))
    <div class="d-flex" style="width:400px; ">
        <a href="{{ route('cookPnl.cookPanelIndexCook') }}" class="text-center pt-2 pb-2 pointerH" style="width:170px; background-color:rgba(191,191,191,255); color:rgba(63,63,63,255); border-radius:5px; font-size:1.3rem; margin:0px;">
            <strong>Offen</strong>
        </a>
        <a href="{{ route('cookPnl.cookPanelIndexCookNotConf') }}" class="text-center pt-2 pb-2 pointerH" style="width:170px; background-color:rgba(4,178,89,255); color:rgba(63,63,63,255); border-radius:5px; font-size:1.3rem; margin:0px;">
            <strong>Bereit</strong>
        </a>
    </div>
    @elseif (Request::is('cookPanelIndexCookT') || Request::is('cookPanelIndexCookTNotConf'))
    <div class="d-flex" style="width:400px; ">
        <a href="{{ route('cookPnl.cookPanelIndexCookT') }}" class="text-center pt-2 pb-2 pointerH" style="width:170px; background-color:rgba(191,191,191,255); color:rgba(63,63,63,255); border-radius:5px; font-size:1.3rem; margin:0px;">
            <strong>Offen</strong>
        </a>
        <a href="{{ route('cookPnl.cookPanelIndexCookTNotConf') }}" class="text-center pt-2 pb-2 pointerH" style="width:170px; background-color:rgba(4,178,89,255); color:rgba(63,63,63,255); border-radius:5px; font-size:1.3rem; margin:0px;">
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
        <a href="{{ route('cookPnl.cookPanelIndexCook') }}" style="width:170px; background-color:red; color:white; font-size:1.3rem;" class="btn btn-danger table-glow-adminAlert">
            <strong>Restaurant</strong>
        </a>
        @else
        <a href="{{ route('cookPnl.cookPanelIndexCook') }}" style="width:170px; background-color:white; color:rgb(39,190,175); font-size:1.3rem;" class="btn btn-default">
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
            <a href="{{ route('cookPnl.cookPanelIndexCookT') }}" style="width:170px; background-color:red; color:white; font-size:1.3rem;" class="btn btn-danger table-glow-adminAlert">
                <strong>Takeaway</strong>
            </a>
        @else
            <a href="{{ route('cookPnl.cookPanelIndexCookT') }}" style="width:170px; background-color:white; color:rgb(39,190,175); font-size:1.3rem;" class="btn btn-default">
                <strong>Takeaway</strong>
            </a>
        @endif
    @endif

    @if(cooksProductSelection::where([['workerId',Auth::user()->id],['contentType','Delivery']])->first() != NULL && (Request::is('cookPanelIndexCookT') || Request::is('cookPanelIndexCookTNotConf') || Request::is('cookPanelIndexCook') || Request::is('cookPanelIndexCookNotConf')))
    <a href="{{ route('cookPnl.cookPanelIndexCookD') }}" style="width:170px; background-color:white; color:rgb(39,190,175); font-size:1.3rem;" class="btn btn-default shadow-none">
        <strong>Delivery</strong>
    </a>
    @endif

    <button style="width:100px; background-color:white; color:rgb(39,190,175); font-size:1.3rem;" class="btn btn-default shadow-none" data-toggle="modal" data-target="#howManyTablesShowModal">
        <strong><i class="fa fa-3x-solid fa-table-cells"></i> <span class="ml-2">: {{Auth::user()->cookPV2BlShow}}</span></strong>
    </button>

     <button style="width:100px; background-color:white; color:rgb(39,190,175); font-size:1.3rem;" class="btn btn-default shadow-none" data-toggle="modal" data-target="#showPlateColorSelectModal">
        <strong><i class="fa fa-3x-solid fa-palette"></i></strong>
    </button>

    <a style="color: white; width:fit-content; font-size:1.6rem;" class="mr-3" href="{{ route('logout') }}"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        <strong> {{ __('adminP.logOut') }}</strong>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</nav>


<!-- Modal -->
<div class="modal" id="howManyTablesShowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: lightgray;">
            <div class="modal-body d-flex flex-wrap">
                <p style="font-size:2.5rem; width:100%; font-weight:bold; color:rgb(72,81,87);" class="text-center">Anzahl Tische auf einer Reihe</p>

                <span style="width: 37%;"></span>
                <input style="width: 26%; font-size:2.5rem; border:none; border-bottom:1px solid rgb(72,81,87); padding:0 !important; line-height:1.1 !important; border-radius:0; height:fit-content; background-color:lightgray; color:rgb(72,81,87);" 
                class="form-control shadow-none text-center" type="number" id="newNrShowInp" value="{{Auth::user()->cookPV2BlShow}}">
                <span style="width: 37%;"></span>

                <button class="btn btn-success shadow-none" style="width:100%; margin:5px 0px 5px 0px;" onclick="chngNrOfBlShownCPV2()">
                    <strong>Sparen</strong>
                </button>

                <div class="mt-1 alert alert-danger text-center" style="font-weight: bold; display:none; width:100%;" id="newNrShownError01">
                    Bitte legen Sie eine gültige Nummer fest!
                </div>

                <button style="width:100%; color:rgb(72,81,87);" type="button" class="btn close shadow-none mt-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                </button>
                
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="showPlateColorSelectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content " style="background-color: lightgray;">
            <div class="modal-body d-flex flex-wrap">
                <p style="font-size:1.3rem; width:100%; font-weight:bold; color:rgb(72,81,87);" class="text-center">
                    Die mit den Farbfeldern verknüpften Farben werden in Ihrem Bedienfeld angezeigt.
                </p>
                <hr style="width:100%; margin:3px 0px 3px 0px; color:rgb(72,81,87);"> 
                <div style="width:100%;" class="d-flex flex-wrap justify-content-between">
                    @foreach (resPlates::where('toRes',Auth::user()->sFor)->orderBy('desc2C')->get() as $onePlate)
                        <div style="width:100%;" class="d-flex justify-content-between mb-1">
                            <p style="width:50%; font-size:1.5rem;"><strong>{{$onePlate->nameTitle}}</strong></p>
                           
                            <?php
                                $cookColoreIns = cookColor::where([['cookId',Auth::user()->id],['plateId',$onePlate->id]])->first();
                            ?>
                            
                            @if ( $cookColoreIns != Null)
                                <div style="width:50%">
                                    <label for="colorPicker"><strong>Aktuelle Farbe:</strong></label>
                                    <input type="color" id="colorPickerPlate{{$onePlate->id}}" onchange="setNewPlateColor('{{$onePlate->id}}')" value="{{$cookColoreIns->colorHEX}}">
                                    <p><strong>Zum Ändern hier klicken</strong></p>
                                </div>
                            @else
                                <div style="width:50%">
                                    <label for="colorPicker"><strong>Aktuelle Farbe:</strong></label>
                                    <input type="color" id="colorPickerPlate{{$onePlate->id}}" onchange="setNewPlateColor('{{$onePlate->id}}')" value="#bfbfbf">
                                    <p><strong>Zum Ändern hier klicken</strong></p>
                                </div>
                            @endif
                        </div>
                        <hr style="width:100%; margin:3px 0px 3px 0px; color:rgb(72,81,87);"> 
                    @endforeach
                </div>

                <div class="alert alert-info text-center mt-1 mb-1" style="width:100%; display:none;" id="showPlateColorSelectModalInfo01">
                    <p><strong>Die Änderungen werden wirksam, sobald Sie dieses Fenster schließen.</strong></p>
                </div>
               
                <button style="width:100%; color:rgb(72,81,87);" type="button" class="btn close shadow-none mt-5" data-dismiss="modal" aria-label="Close" onclick="closeShowPlateColorSelectModal()">
                    <span aria-hidden="true"><i class="fa-solid fa-x"></i></span>
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    function chngNrOfBlShownCPV2(){
        var newNr = parseInt($('#newNrShowInp').val());
        if(newNr <= 0){
            if($('#newNrShownError01').is(':hidden')){ $('#newNrShownError01').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
		    	url: '{{ route("cookPnl.chnGNrBlocksShownCPV232INCH") }}',
		    	method: 'post',
		    	data: {
		    		newNrShown: newNr,
		    		_token: '{{csrf_token()}}'
		    	},
		    	success: () => {
		    		location.reload();  
                   
		    	},
		    	error: (error) => { console.log(error); }
		    });
        }
    }

    function setNewPlateColor(pId){
        $.ajax({
			url: '{{ route("cookPnl.changePlateColorCook") }}',
			method: 'post',
			data: {
                plateId: pId,
                plateColorHex: $('#colorPickerPlate'+pId).val(),
				_token: '{{csrf_token()}}'
			},
			success: () => {      
                $('#showPlateColorSelectModalInfo01').show(50);      
			},
			error: (error) => { console.log(error); }
		});
    }

    function closeShowPlateColorSelectModal(){
        location.reload();  
    }

</script>