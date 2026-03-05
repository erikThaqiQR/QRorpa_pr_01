<?php

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
    use App\tabReservationReqEmailSent;
    use Carbon\Carbon;

    $allTables = TableQrcode::where('Restaurant',Auth::user()->sFor)->get()->count();
    $sizeTaken = $allTables / 100;

    if(isset($_GET['dt'])){ $dtMove = $_GET['dt'];
    }else{ $dtMove = 0; }
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

@include('adminPanel.parts.tableReservationNew')

<section class="pl-3 pr-3 mb-5">
    <div class="d-flex justify-content-between">
        <h3 class="color-qrorpa" style="font-weight:bold; width:24.7%;">{{__('adminP.tableReservations')}}</h3>

        <button class="btn btn-light btn-block shadow-none" style="font-weight:bold; width:24.7%;" data-toggle="modal" data-target="#emailNotifyReg"> Email Benachrichtigung  </button>

        <?php $countUnAnsTableReser = TableReservation::where([['toRes', Auth::user()->sFor],['tableNr', 999999],['status',0]])->whereDate('dita', '>=', Carbon::today())->count(); ?>
        @if($countUnAnsTableReser > 0)
        <a class="anchorMy" style="width:24.7%;" href="{{route('TableReservation.ADIndexRezList')}}"> <button class="btn btn-danger btn-block shadow-none" style=" font-weight:bold;"> Tischreservierungsanfragen ({{$countUnAnsTableReser}} <i class="far fa-bell"></i> ) </button></a>
        @else
        <a class="anchorMy" style="width:24.7%;" href="{{route('TableReservation.ADIndexRezList')}}"> <button class="btn btn-light btn-block shadow-none" style=" font-weight:bold;"> Tischreservierungsanfragen  </button></a>
        @endif

        <button class="btn btn-light btn-block shadow-none" style="font-weight:bold; width:24.7%;" data-toggle="modal" data-target="#regArezervationModal"> <i style="color:rgb(39,190,175);" class="fa-solid fa-circle-plus"></i> Neue Reservierung </button>
    </div>

    <hr>

    <input type="hidden" value="{{date('m')}}" id="currMonth">


    <div class="d-flex justify-content-between flex-wrap" id="RezTable">
            @if($dtMove > 0)
            <div style="width:6%; font-size:19px; font-weight:bold;" class="text-center p-1 clickable" onclick="backDateByOne('{{$dtMove}}')">
                <i class="fa-solid fa-2x fa-circle-arrow-left" style="color:rgb(39,190,175);"></i>
            </div>
            @else
            <div style="width:6%; font-size:19px; font-weight:bold;" class="text-center p-1">
            </div>
            @endif
            <div style="width:94%;" class="d-flex justify-content-between">
                @for($i = 0+($dtMove*12) ; $i <= 11+($dtMove*12) ;$i++)
                    <div style="width:7.67%; font-size:17px; font-weight:bold;" class="text-center">
                        <?php 
                            $thisDate = date('d-m-Y', strtotime("+".$i." day"));
                            $thisDate2D = explode('-',$thisDate);
                        ?>
                        {{$thisDate2D[0]}}.{{$thisDate2D[1]}}<br>{{$thisDate2D[2]}}
                    </div>
                @endfor
                <div style="width:7.67%; font-size:17px; font-weight:bold;" class="text-center p-1 clickable" onclick="frontDateByOne('{{$dtMove}}')">
                    <i class="fa-solid fa-2x fa-circle-arrow-right" style="color:rgb(39,190,175);"></i>
                </div>
            </div>
            <hr style="margin-top:2px; margin-bottom:2px; width:100%;">

            @foreach(TableQrcode::where('Restaurant',Auth::user()->sFor)->get()->sortBy('tableNr') as $table)
                <div style="width:6%; font-size:19px; " class="text-left p-1 d-flex">
                    <span style="width:40%;">T: </span> <span style="width:60%;" class="text-right;"><strong>{{$table->tableNr}}</strong> </span> 
                </div>
                <div style="width:94%;"  class="d-flex justify-content-between">
                    @for($i = 0+($dtMove*12) ; $i <= 11+($dtMove*12) ;$i++)
                        <?php $thisDate = date('d-m-Y', strtotime("+".$i." day")) ;
                            $thisDate2D = explode('-',$thisDate);
                            $dateForRez = $thisDate2D[2].'-'.$thisDate2D[1].'-'.$thisDate2D[0];

                            $tabRezCn = TableReservation::where([['toRes',Auth::user()->sFor],['tableNr', $table->tableNr],['dita',$dateForRez]])->whereDate('dita', '>=', Carbon::today())->get()->count();
                            $tabRezCnStat0 = TableReservation::where([['toRes',Auth::user()->sFor],['tableNr', $table->tableNr],['dita',$dateForRez],['status',0]])->whereDate('dita', '>=', Carbon::today())->get()->count();
                        ?>
                        @if($tabRezCn > 0)
                            @if($tabRezCnStat0 > 0)
                            <div style="width:7.67%; font-size:17px; font-weight:bold; background-color:red; border-radius:10px; margin-bottom:2px;" class="text-center clickable"
                                data-toggle="modal" data-target="#table{{$table->tableNr}}Date{{$dateForRez}}">
                            @else
                            <div style="width:7.67%; font-size:17px; font-weight:bold; background-color:rgba(39,190,175,0.7); border-radius:10px; margin-bottom:2px;" class="text-center clickable"
                                data-toggle="modal" data-target="#table{{$table->tableNr}}Date{{$dateForRez}}">
                            @endif
                        @else
                            <div style="width:7.67%; font-size:17px; font-weight:bold; border-bottom:1px solid lightgray;" class="text-center">
                        @endif
                            </div>
                    @endfor
                    <div style="width:7.67%;" class="text-center clickable">
                    </div>
                </div>
            @endforeach
        


    </div>









    <div id="allRezModals">
    @foreach(TableQrcode::where('Restaurant',Auth::user()->sFor)->get()->sortBy('tableNr') as $table)
        @for($i = 0+($dtMove*12) ; $i <= 11+($dtMove*12) ;$i++)
                            <?php $thisDate = date('d-m-Y', strtotime("+".$i." day")) ;
                                $thisDate2D = explode('-',$thisDate);
                                $dateForRez = $thisDate2D[2].'-'.$thisDate2D[1].'-'.$thisDate2D[0];
                            ?>
            @if(TableReservation::where([['toRes', Auth::user()->sFor],['tableNr', $table->tableNr],['dita',$dateForRez]])
                    ->whereDate('dita', '>=', Carbon::today())->get()->count() > 0)

                    <!-- The Modal -->
                    <div class="modal" id="table{{$table->tableNr}}Date{{$dateForRez}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">{{__('adminP.table')}} : <strong>{{$table->tableNr}}</strong> <span class="ml-5">{{$dateForRez}}</span></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                @foreach(TableReservation::where([['toRes', Auth::user()->sFor],['tableNr', $table->tableNr],['dita',$dateForRez]])
                                        ->whereDate('dita', '>=', Carbon::today())->get()->sortBy('dita')->sortBy('koha01') as $allReser)
                                    @if($allReser->status == 1)
                                        <div class="d-flex flex-wrap justify-content-between p-2 mb-2" style="border-radius:10px; border:1px solid rgb(39,190,175); background-color:rgb(215, 245, 219);">
                                    @elseif($allReser->status == 2)
                                        <div class="d-flex flex-wrap justify-content-between p-2 mb-2" style="border-radius:10px; border:1px solid rgb(39,190,175); background-color:rgb(242, 211, 211);">
                                    @else
                                        <div class="d-flex flex-wrap justify-content-between p-2 mb-2" style="border-radius:10px; border:1px solid rgb(39,190,175);">
                                    @endif
                                        <h4 style="width:24.5%;" class="text-center"><strong> {{$allReser->emri}} {{$allReser->mbiemri}}</strong></h4>
                                        <h4 style="width:24.5%;" class="text-center"><strong> {{$allReser->nrTel}}</strong></h4>
                                        <h4 style="width:24.5%;" class="text-center"><strong> {{$allReser->email}}</strong></h4>
                                        
                                        @if($allReser->status == 1)
                                            <h3 style="width:24.5%; color:green; font-weight:bold;" class="btn btn-default"> {{__('adminP.authorized')}} </h3>
                                        @elseif($allReser->status == 2)
                                            <h3 style="width:24.5%; color:red; font-weight:bold;" class="btn btn-default"> {{__('adminP.cancel')}} </h3>
                                        @else
                                            <button onclick="chngStatusTabReser('{{$allReser->id}}','{{$table->tableNr}}','{{$dateForRez}}','1')"
                                             style="width:12.25%;" id="tabResApprove" class="btn btn-success"> {{__('adminP.authorize')}} </button>

                                             <button onclick="chngStatusTabReser('{{$allReser->id}}','{{$table->tableNr}}','{{$dateForRez}} ','2')"
                                             style="width:12.25%;" id="tabResDisapprove" class="btn btn-danger"> {{__('adminP.notConfirmed')}} </button>
                                        @endif

                                        <h5 style="width:24.5%;" class="text-center mt-2"><strong> {{$allReser->persona}} {{__('adminP.persons')}} </strong></h5>
                                        <h5 style="width:24.5%;" class="text-center mt-2"><strong> {{$allReser->koha01}} -> {{$allReser->koha02}}</strong></h5>
                                        @if($allReser->koment != 'empty')
                                            <h5 style="width:50%; text-decoration:underline;" class="text-center mt-2"><strong> {{__('adminP.comment')}} : {{$allReser->koment}}</strong></h5>
                                        @else
                                            <h5 style="width:50%; text-decoration:underline;" class="text-center mt-2"></h5>
                                        @endif

                                        

                                    </div>
                                @endforeach
                            </div>

                            </div>
                        </div>
                    </div>
              

            @endif
        @endfor
    @endforeach
    </div>




    <script>
        function chngStatusTabReser(tRezId,tabNr,dateRez,val){
            if(val == '1'){
                $('#tabResApprove').prop('disabled', true);
                $('#tabResApprove').attr('style','width:100%;');
                $('#tabResApprove').html('<img style="height: 25px; width:auto;" src="storage/gifs/loading2.gif" alt="">');
                $('#tabResDisapprove').hide(100);
            }else{
                $('#tabResDisapprove').prop('disabled', true);
                $('#tabResDisapprove').attr('style','width:100%;');
                $('#tabResDisapprove').html('<img style="height: 25px; width:auto;" src="storage/gifs/loading2.gif" alt="">');
                $('#tabResApproveTel').hide(100);
            }
            $.ajax({
				url: '{{ route("TableReservation.chgStatus") }}',
				method: 'post',
				data: {
					id: tRezId,
                    newVal: val,
					_token: '{{csrf_token()}}'
				},
				success: () => {
					$("#table"+tabNr+"Date"+dateRez).load(location.href+" #table"+tabNr+"Date"+dateRez+">*","");
					$("#RezTable").load(location.href+" #RezTable>*","");
				},
				error: (error) => {
					console.log(error);
					alert($('#oopsSomethingWrong').val())
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

        function backDateByOne(dtm){
            var newDTM = parseInt(parseInt(dtm)-parseInt(1));
            window.location.replace("https://qrorpa.ch/tabReserAdminIndex?dt="+newDTM);
        }
        function frontDateByOne(dtm){
            var newDTM = parseInt(parseInt(dtm)+parseInt(1));
            window.location.replace("https://qrorpa.ch/tabReserAdminIndex?dt="+newDTM);
        }
    </script>






<script>

// var pusher = new Pusher('3d4ee74ebbd6fa856a1f', { cluster: 'eu' });
//     var channel = pusher.subscribe('TabRez');
//     channel.bind('App\\Events\\newTabRez', function(data) {

//         var dataJ =  JSON.stringify(data);
//         var dataJ2 =  JSON.parse(dataJ);

//         if($('#thisResId').val() == dataJ2.text){
//             window.location = window.location
//         }
//     });
</script>





</section>