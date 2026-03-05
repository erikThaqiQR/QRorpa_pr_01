<?php

use App\tabReservationReqEmailSent;
use Illuminate\Support\Facades\Auth;
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Tischreservierungen']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    // $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
    $thisDate = date('d-m-Y');

    use App\TableQrcode;
    use App\TableReservation;
    use Carbon\Carbon;

    $allTables = TableQrcode::where('Restaurant',Auth::user()->sFor)->get()->count();
    $sizeTaken = $allTables / 100;
?>

<input type="hidden" value="{{Auth::user()->sFor}}" id="thisResId">

<style>
    .clickable:hover{
        cursor: pointer;
    }
    .anchorMy{
        color:black;
    }
    .anchorMy:hover{
        color:black;
        text-decoration: none;
    }
</style>

<!-- Modal -->
<div class="modal" id="emailNotifyReg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:4%;" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Email Benachrichtigung </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body" id="emailNotifyRegBody">
                <div class="input-group mb-2 mt-2">
                    <input type="text" id="emPerRegjistrim" class="form-control shadow-none" placeholder="E-Mail-Adresse">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success shadow-none" type="button" onclick="saveNewTabResEmail()">Sparen</button>
                    </div>
                </div>
                <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="emRegError01">
                    <strong>Geben Sie eine gültige E-Mail-Adresse ein!</strong>
                </div>
                <div class="alert alert-danger text-center mt-1 p-1" style="width:100%; display:none;" id="emRegError02">
                    <strong>Diese E-Mail ist bereits registriert!</strong>
                </div>
                <hr>

                <div id="emailNotifyRegBodyList">
                    
                    <?php
                        $tabRezEmNoty = tabReservationReqEmailSent::where('toRes',Auth::user()->sFor)->get();
                    ?>
                    @if (count($tabRezEmNoty) == 0)
                        <p style="font-size:1.1rem; color:rgb(39,190,175); margin:2px;" class="text-center">Derzeit sind keine E-Mail-Adressen für Tischreservierungsbenachrichtigungen registriert</p>
                        <p style="font-size:0.8rem; color:red; margin:2px;" class="text-center">In diesem Zustand werden Benachrichtigungen über Tischreservierungen an die E-Mail-Adresse der Administratoren gesendet</p>    
                    @else
                        <div class="d-flex flex-wrap justify-content-between">
                            @foreach ($tabRezEmNoty as $onetrezEm)
                                <button id="delEmailRegBtn{{$onetrezEm->id}}" class="btn btn-outline-danger shadow-none mb-1" style="width:10%;" onclick="deleteEmailTabRezNoti('{{$onetrezEm->id}}')">
                                    <i style="color:red;" class="fa-solid fa-trash-can"></i>
                                </button>
                                <p class="mb-1" style="margin:0px; width:90%; font-size:1.3rem; padding-left:15px;"><strong>{{$onetrezEm->email}}</strong></p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<section class="pl-3 pr-3 mb-5">
    <div class="d-flex justify-content-between">
        <h3 class="color-qrorpa" style="font-weight:bold; width:33%;">{{__('adminP.tableReservations')}}</h3>

        <button class="btn btn-light btn-block shadow-none" style="font-weight:bold; width:33%;" data-toggle="modal" data-target="#emailNotifyReg"> Email Benachrichtigung  </button>

        <a class="anchorMy" style="width:33%;" href="{{route('admWoMng.adminWoTableReservationIndexWaiter')}}"> <button class="btn btn-light btn-block" style=" font-weight:bold;">{{__('adminP.tableReservations')}}</button> </a>\
    </div>



    <div class="d-flex flex-wrap justify-content-between mt-2 p-2" id="RezReqAll">
        <h2 style="color:rgb(39,190,175); width:100%;"><strong>{{__('adminP.reservationSpecialRequests')}}</strong>
            <span style="color:rgb(72,81,87); font-size:15px;">( <i class="far fa-hand-pointer"></i> {{__('adminP.clickToOpen')}} )</span>
        </h2>
        @foreach(TableReservation::where([['toRes', Auth::user()->sFor],['tableNr', 999999]])->whereDate('dita', '>=', Carbon::today())->get()->sortBy('dita') as $allReser)
                @if($allReser->status == 1)
                <div class="d-flex flex-wrap justify-content-between p-2 mb-2"
                    style="width:49.6%; border-radius:10px; border:1px solid rgb(39,190,175); background-color:rgb(208, 245, 218);">
                @elseif($allReser->status == 2)
                <div class="d-flex flex-wrap justify-content-between p-2 mb-2"
                    style="width:49.6%; border-radius:10px; border:1px solid rgb(39,190,175); background-color:rgb(245, 208, 208);">
                @else
                <div class="d-flex flex-wrap justify-content-between p-2 mb-2 clickable" 
                    style="width:49.6%; border-radius:10px; border:1px solid rgb(39,190,175);" data-toggle="modal" data-target="#resReqModal{{$allReser->id}}">
                @endif   
                
                    <h5 style="width:49.6%;" class="text-center"><strong> {{$allReser->emri}} {{$allReser->mbiemri}}</strong></h5>
                    <h5 style="width:49.6%;" class="text-center"><strong> {{$allReser->email}}</strong></h5>

                    <h5 style="width:49.6%;" class="text-center"><strong> {{$allReser->nrTel}}</strong></h5>
                    <h5 style="width:49.6%;" class="text-center"><strong>{{__('adminP.table')}}: {{__('adminP.pending')}}</strong></h5>
                    <?php
                        $resReqDita2d = explode('-',$allReser->dita);
                        switch($resReqDita2d[1]){
                            case 1: $resReqDitaMonth = __('adminP.jan'); break;
                            case 2: $resReqDitaMonth = __('adminP.feb'); break;
                            case 3: $resReqDitaMonth = __('adminP.march'); break;
                            case 4: $resReqDitaMonth = __('adminP.apr'); break;
                            case 5: $resReqDitaMonth = __('adminP.May'); break;
                            case 6: $resReqDitaMonth = __('adminP.june'); break;
                            case 7: $resReqDitaMonth = __('adminP.july'); break;
                            case 8: $resReqDitaMonth = __('adminP.aug'); break;
                            case 9: $resReqDitaMonth = __('adminP.sept'); break;
                            case 10: $resReqDitaMonth = __('adminP.oct'); break;
                            case 11: $resReqDitaMonth = __('adminP.nov'); break;
                            case 12: $resReqDitaMonth = __('adminP.dec'); break;   
                        }
                    ?>

                    <h6 style="width:33%;" class="text-center mt-2"><strong> {{$allReser->persona}} {{__('adminP.persons')}} </strong></h6>
                    <h6 style="width:33%;" class="text-center mt-2"><strong> {{$resReqDita2d[2]}}.{{$resReqDitaMonth}}.{{$resReqDita2d[0]}}</strong></h6>
                    <h6 style="width:33%;" class="text-center mt-2"><strong> {{$allReser->koha01}} -> {{$allReser->koha02}}</strong></h6>

                    @if($allReser->koment != 'empty')
                        <h6 style="width:100%; text-decoration:underline;" class="text-center mt-2"><strong> {{__('adminP.comment')}} : {{$allReser->koment}}</strong></h6>
                    @else
                        <h6 style="width:100%; text-decoration:underline;" class="text-center mt-2"></h6>
                    @endif
                </div>
            @endforeach
    </div>






    <div class="d-flex flex-wrap justify-content-between mt-2 p-2" id="RezReqAllDone" style="border-top:2px solid black;">
            <h2 style="color:rgb(39,190,175); width:100%;"><strong>{{__('adminP.otherTableReservations')}}</strong></h2>
            @foreach(TableReservation::where([['toRes', Auth::user()->sFor],['tableNr','!=', 999999]])->whereDate('dita', '>=', Carbon::today())->get()->sortBy('dita') as $allReser)
                @if($allReser->status == 1)
                <div class="d-flex flex-wrap justify-content-between p-2 mb-2"
                    style="width:49.6%; border-radius:10px; border:1px solid rgb(39,190,175); background-color:rgb(208, 245, 218);">
                @elseif($allReser->status == 2)
                <div class="d-flex flex-wrap justify-content-between p-2 mb-2"
                    style="width:49.6%; border-radius:10px; border:1px solid rgb(39,190,175); background-color:rgb(245, 208, 208);">
                @else
                <div class="d-flex flex-wrap justify-content-between p-2 mb-2 clickable" 
                    style="width:49.6%; border-radius:10px; border:1px solid rgb(39,190,175);" data-toggle="modal" data-target="#resReqModal{{$allReser->id}}">
                @endif   
                
                    <h5 style="width:49.6%;" class="text-center"><strong> {{$allReser->emri}} {{$allReser->mbiemri}}</strong></h5>
                    <h5 style="width:49.6%;" class="text-center"><strong> {{$allReser->email}}</strong></h5>

                    <h5 style="width:49.6%;" class="text-center"><strong> {{$allReser->nrTel}}</strong></h5>
                    <h5 style="width:49.6%;" class="text-center"><strong>{{__('adminP.table')}}: {{$allReser->tableNr}}</strong></h5>
                    <?php
                        $resReqDita2d = explode('-',$allReser->dita);
                        switch($resReqDita2d[1]){
                            case 1: $resReqDitaMonth = __('adminP.jan'); break;
                            case 2: $resReqDitaMonth = __('adminP.feb'); break;
                            case 3: $resReqDitaMonth = __('adminP.march'); break;
                            case 4: $resReqDitaMonth = __('adminP.apr'); break;
                            case 5: $resReqDitaMonth = __('adminP.May'); break;
                            case 6: $resReqDitaMonth = __('adminP.june'); break;
                            case 7: $resReqDitaMonth = __('adminP.july'); break;
                            case 8: $resReqDitaMonth = __('adminP.aug'); break;
                            case 9: $resReqDitaMonth = __('adminP.sept'); break;
                            case 10: $resReqDitaMonth = __('adminP.oct'); break;
                            case 11: $resReqDitaMonth = __('adminP.nov'); break;
                            case 12: $resReqDitaMonth = __('adminP.dec'); break;   
                        }
                    ?>

                    <h6 style="width:33%;" class="text-center mt-2"><strong> {{$allReser->persona}} {{__('adminP.persons')}} </strong></h6>
                    <h6 style="width:33%;" class="text-center mt-2"><strong> {{$resReqDita2d[2]}}.{{$resReqDitaMonth}}.{{$resReqDita2d[0]}}</strong></h6>
                    <h6 style="width:33%;" class="text-center mt-2"><strong> {{$allReser->koha01}} -> {{$allReser->koha02}}</strong></h6>

                    @if($allReser->koment != 'empty')
                        <h6 style="width:100%; text-decoration:underline;" class="text-center mt-2"><strong> {{__('adminP.comment')}} : {{$allReser->koment}}</strong></h6>
                    @else
                        <h6 style="width:100%; text-decoration:underline;" class="text-center mt-2"></h6>
                    @endif
                </div>
            @endforeach
    </div>





























    @foreach(TableReservation::where([['toRes', Auth::user()->sFor],['tableNr', 999999]])
                ->whereDate('dita', '>=', Carbon::today())->get()->sortBy('dita') as $allReser)
    
        <!-- The Modal -->
        <div class="modal" id="resReqModal{{$allReser->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="d-flex flex-wrap" style="border-bottom:1px solid gray;">
                            <h4 style="width:31.5%;" class="text-center"><strong> {{$allReser->emri}} {{$allReser->mbiemri}}</strong></h4>
                            <h4 style="width:31.5%;" class="text-center"><strong> {{$allReser->nrTel}}</strong></h4>
                            <h4 style="width:31.5%;" class="text-center"><strong> {{$allReser->email}}</strong></h4>
                            <button type="button" class="close" data-dismiss="modal" style="width:5%;">X</button>

                            <?php
                                $resReqDita2d = explode('-',$allReser->dita);
                                switch($resReqDita2d[1]){
                                    case 1: $resReqDitaMonth = __('adminP.jan'); break;
                                    case 2: $resReqDitaMonth = __('adminP.feb'); break;
                                    case 3: $resReqDitaMonth = __('adminP.march'); break;
                                    case 4: $resReqDitaMonth = __('adminP.apr'); break;
                                    case 5: $resReqDitaMonth = __('adminP.May'); break;
                                    case 6: $resReqDitaMonth = __('adminP.june'); break;
                                    case 7: $resReqDitaMonth = __('adminP.july'); break;
                                    case 8: $resReqDitaMonth = __('adminP.aug'); break;
                                    case 9: $resReqDitaMonth = __('adminP.sept'); break;
                                    case 10: $resReqDitaMonth = __('adminP.oct'); break;
                                    case 11: $resReqDitaMonth = __('adminP.nov'); break;
                                    case 12: $resReqDitaMonth = __('adminP.dec'); break;   
                                }
                            ?>

                            <h5 style="width:33.1%;" class="text-center mt-2"><strong> {{$resReqDita2d[2]}}.{{$resReqDitaMonth}}.{{$resReqDita2d[0]}}</strong></h5>
                            <h5 style="width:33.1%;" class="text-center mt-2"><strong> {{$allReser->koha01}} -> {{$allReser->koha02}}</strong></h5>
                            <h5 style="width:33.1%;" class="text-center mt-2"><strong> <span id="resReqModalXpersons{{$allReser->id}}">{{$allReser->persona}}</span> {{__('adminP.persons')}} </strong></h5>
                            @if($allReser->koment != 'empty')
                                <h5 style="width:100%; text-decoration:underline;" class="text-center mt-2"><strong> {{__('adminP.comment')}} : {{$allReser->koment}}</strong></h5>
                            @else
                                <h5 style="width:100%; text-decoration:underline;" class="text-center mt-2"></h5>
                            @endif
                        </div>
                        <div class="d-flex jostify-content-between mt-2">
                            <div style="width:9.5%; background-color:red; border-radius:5px;"  class="mr-1 mb-1 p-1 text-center" >
                                # XX / XX <i class="fas fa-user-friends"></i>
                            </div>
                            <p style="width: 90%; font-weight:bold;" class="pt-1">{{__('adminP.reservedCanNotAcceptreservationForMoment')}}</p>
                        </div>

                        <div class="d-flex flex-wrap justify-content-start mt-2">
                            @foreach(TableQrcode::where('Restaurant',Auth::user()->sFor)->get()->sortBy('tableNr') as $tabl)
                                <!-- Calculate if this table on this time and date is taken or not -->
                                <?php
                                    $canBeTaken = True;
                                    $reqKoha1999 = $allReser->koha01; 
                                    $reqKoha2999 = $allReser->koha02; 
                                    $tableRes999 = TableReservation::where([['toRes',Auth::user()->sFor],['tableNr',$tabl->tableNr],['dita',$allReser->dita],['status',1]])->get();
                                    if($tableRes999 != NULL){
                                        foreach($tableRes999 as $tableRes999One){
                                            $thisResTime01 = $tableRes999One->koha01;
                                            $thisResTime02 = $tableRes999One->koha02;

                                            if($reqKoha1999 >= $thisResTime01 && $reqKoha1999 <= $thisResTime02){ $canBeTaken = False;}
                                            else if($reqKoha2999 >= $thisResTime01 && $reqKoha2999 <= $thisResTime02){ $canBeTaken = False;}
                                        }
                                    }
                                ?>
                                @if($canBeTaken)
                                <div style="width:9.5%; background-color:rgb(141, 240, 230); border-radius:5px;" onclick="selectThisTableReq('{{$tabl->tableNr}}','{{$allReser->id}}')"
                                    class="mr-1 mb-1 p-1 text-center clickable" id="oneTableDiv{{$tabl->tableNr}}O{{$allReser->id}}">
                                @else
                                <div style="width:9.5%; background-color:red; border-radius:5px;"  
                                    class="mr-1 mb-1 p-1 text-center clickable" id="oneTableDiv{{$tabl->tableNr}}O{{$allReser->id}}">
                                @endif
                                    # {{$tabl->tableNr}} / {{$tabl->capacity}} <i class="fas fa-user-friends"></i>
                                    <input type="hidden" class="allTables{{$allReser->id}}" value="0" id="oneTable{{$tabl->tableNr}}O{{$allReser->id}}">
                                    <input type="hidden" value="{{$tabl->capacity}}" id="oneTableCa{{$tabl->tableNr}}O{{$allReser->id}}">
                                </div>
                            @endforeach 
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer d-flex justify-content-between">
                        <button style="width:49%; font-size:22px;" type="button" class="btn btn-outline-danger" onclick="cancelTabRezReq('{{$allReser->id}}')"
                            data-dismiss="modal">{{__('adminP.reject')}}</button>
                        <button style="width:49%; font-size:22px;" type="button" class="btn btn-outline-success" onclick="confimTabRezReq('{{$allReser->id}}')"
                            data-dismiss="modal">{{__('adminP.confirm')}} ( <span id="capTot{{$allReser->id}}">0</span> <i class="fas fa-user-friends"></i>)</button>

                        <div id="resReqModalWarning001{{$allReser->id}}" class="alert alert-danger mt-2 p-2 text-center" style="width: 100%; display:none;">
                            <i class="fas fa-exclamation-triangle"></i> {{__('adminP.notEnoguhSpace')}}
                        </div>
                    </div>

                </div>
            </div>
            </div>
    @endforeach

<script>
    function selectThisTableReq(tNr,RezId){
        var cTot = parseInt($('#capTot'+RezId).html());
        var xPersonsNeed = parseInt($('#resReqModalXpersons'+RezId).html());

        if($('#oneTable'+tNr+'O'+RezId).val() == 0){ 

            $('#oneTable'+tNr+'O'+RezId).val(tNr);
            $('#oneTableDiv'+tNr+'O'+RezId).css({"background-color":"rgb(39, 190, 175)"});
            $('#capTot'+RezId).html(parseInt(cTot)+parseInt($('#oneTableCa'+tNr+'O'+RezId).val()));

            if(parseInt($('#capTot'+RezId).html()) < xPersonsNeed){
                $('#resReqModalWarning001'+RezId).show('slow');
            }else{
                $('#resReqModalWarning001'+RezId).hide('slow');
            }

        }else if($('#oneTable'+tNr+'O'+RezId).val() != 0){

            $('#oneTable'+tNr+'O'+RezId).val(0);
            $('#oneTableDiv'+tNr+'O'+RezId).css({"background-color":"rgb(141, 240, 230)"});
            $('#capTot'+RezId).html(parseInt(cTot)-parseInt($('#oneTableCa'+tNr+'O'+RezId).val()));

            if(parseInt($('#capTot'+RezId).html()) < xPersonsNeed){
                $('#resReqModalWarning001'+RezId).show('slow');
            }else{
                $('#resReqModalWarning001'+RezId).hide('slow');
            }
        }
    }


    function cancelTabRezReq(reqId){
        $.ajax({
			url: '{{ route("TableReservation.RezReqCancel") }}',
			method: 'post',
			data: {
				id: reqId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#RezReqAll").load(location.href+" #RezReqAll>*","");
                $("#RezReqAllDone").load(location.href+" #RezReqAllDone>*","");
                $("#resReqModal"+reqId).load(location.href+" #resReqModal"+reqId+">*","");
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }
    function confimTabRezReq(reqId){
        var selTabs = [];
        $('.allTables'+reqId).each(function(i, obj) {
            if($(this).val() != 0){
                console.log($(this).val());
                selTabs.push($(this).val());
            }
        });

        $.ajax({
			url: '{{ route("TableReservation.RezReqConfirm") }}',
			method: 'post',
			data: {
                id: reqId,
                tableS: selTabs,
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
			    $("#RezReqAll").load(location.href+" #RezReqAll>*","");
                $("#RezReqAllDone").load(location.href+" #RezReqAllDone>*","");
                $("#resReqModal"+reqId).load(location.href+" #resReqModal"+reqId+">*","");
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }

    function saveNewTabResEmail(){
        var emPerReg = $('#emPerRegjistrim').val();
        var patternEmail = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

        if(!patternEmail.test(emPerReg)){
            // invalid email
            if($('#emRegError01').is(':hidden')){ $('#emRegError01').show(100).delay(4500).hide(100); }
        }else{
            $('#emPerRegjistrim').val('');
            $('#emailNotifyRegBodyList').addClass('text-center');
            $('#emailNotifyRegBodyList').html('<img src="storage/gifs/loading2.gif" style="width: 200px; height:200px;" alt="">');
            $.ajax({
                url: '{{ route("TableReservation.regEmForTabRezNotify") }}',
                method: 'post',
                data: {
                    theEm: emPerReg,
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    respo = $.trim(respo);
                    if(respo == 'emailExists'){
                        if($('#emRegError02').is(':hidden')){ $('#emRegError02').show(100).delay(4500).hide(100); }
                    }else{
                        $("#emailNotifyRegBody").load(location.href+" #emailNotifyRegBody>*","");
                    }
                },
                error: (error) => { console.log(error);}
            });
        }
    }

    function deleteEmailTabRezNoti(insId){
        $('#delEmailRegBtn'+insId).html('<img src="storage/gifs/loading2.gif" style="width:100%;" alt="">');
        $('#delEmailRegBtn'+insId).prop('disabled', true);
        $.ajax({
            url: '{{ route("TableReservation.delEmForTabRezNotify") }}',
            method: 'post',
            data: {
                theIns: insId,
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $("#emailNotifyRegBody").load(location.href+" #emailNotifyRegBody>*","");
            },
            error: (error) => { console.log(error);}
        });
    }
</script>
</section>