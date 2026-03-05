<?php
    use App\BarbershopCategory;
    use App\BarbershopService;
    use App\BarbershopWorker;
    use App\BarbershopWorkerTerminet;
    use App\WorkerCategoryDone;

    use App\BarbershoServiceRecomendet;





    $theBar = $_GET['Bar'];
    $serWithStPrice = BarbershopService::where([['toBar',$_GET['Bar']],['qmimiSt','!=','0']])->get()->count();
?>

<script>
    var tValue = 0;
</script>

<style>
        .teksti{
            justify-content:space-between;
            margin-top:-50px;
            color:#FFF;
            font-weight:bold;
            font-size:23px;
            margin-bottom:10px;
        }

        
        .prod-name{
            line-height: 2;
        }
        .add-plus-section{
            text-align: right;
            padding: 0px;
        }
        .product-section{
            border-bottom: 1px solid #dcd9d9;
            padding-bottom: 15px;
        }
        .recommended-title{
            margin-left: 0px !important;
        }
        .teksti strong{
            margin-left:20px;
        }
        .teksti i{
            margin-right:20px
        }

        .qrorpaBtn{
            border:1px solid rgb(39,190,175);
            color:rgb(39,190,175);
            font-weight: bold;
        }
        .qrorpaBtn:hover{
            border:1px solid rgb(39,190,175);
            background-color:rgb(39,190,175);
            color:white;
            font-weight: bold;
        }








        .footerPhone {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: rgb(39, 190, 175);
            color: white;
            text-align: center;
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 19px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            z-index: 1000;
        }

</style>





















<div class="container" id="allBarbershopSer01">
    @if(isset($_GET['Bar']))
        @foreach(BarbershopCategory::where('toBar',$theBar)->get() as $barCat)
            <div class="row allKatFoto">
                <div class="col-lg-3 col-md-0 col-sm-0"></div>

                <div style="cursor: pointer; position:relative; object-fit: cover;" class="col-lg-6 col-md-12 col-sm-12 p-1"
                onclick="showBarKat('{{$barCat->id}}')">
                    <img style="border-radius:30px; width:100%; height:120px; object-fit: cover;" src="storage/barbershop/CategoryUpload/{{$barCat->foto}}"
                        alt="notFound">

                        <div class="teksti d-flex" style="font-size:16px;  margin-bottom:20px;">          
                            <strong>{{$barCat->emri}} </strong>
                            <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
                        </div>
                   
                </div>
                <div class="col-lg-3 col-md-0 col-sm-0"></div>
            </div>

            @foreach(BarbershopService::where([['toBar',$theBar],['kategoria',$barCat->id]])->get() as $barSer)
                <div class="row p-2 serviceList{{$barCat->id}}" style="display:none;" data-toggle="modal" data-target="#ServiceModal{{$barSer->id}}" data-backdrop="static" data-keyboard="false">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3 col-sm-0 col-md-0"></div>
                            <div class="col-lg-6 col-sm-12 col-md-12 product-section">
                                <div class="row">
                                    
                                    <div class="col-10">
                                        <h4 class="pull-right prod-name prodsFont" style="font-weight:bold; font-size: 1.20rem ">
                                            {{$barSer->emri}} 
                                        </h4>
                                        <p style=" margin-top:-10px; font-size:13px;">{{substr($barSer->pershkrimi,0,35)}} 
                                            @if(strlen($barSer->pershkrimi)>35)
                                                <span onclick="showTypeMenu('{{$barSer->id}}')" class="hover-pointer" style="font-size:16px;">{{__('inc.more')}}</span> 
                                            @endif 
                                        </p>
                                        <h5 style="margin-top:-10px; margin-bottom:0px;">
                                            <span style="color:gray;">
                                            {{__('inc.currencyShow')}}
                                            </span>
                                            @if(isset($_GET['student']) && $barSer->qmimiSt != 0 )
                                                {{sprintf('%01.2f', $barSer->qmimiSt)}} <span style="font-size:10px;">({{__('inc.studentPrice')}})</span>
                                            @else
                                                {{sprintf('%01.2f', $barSer->qmimi)}}
                                            @endif
                                                
                                        </h5>
                                    </div>
                                    <div class="col-2 add-plus-section">
                                        <button class="btn mt-2 noBorder" type="button" >
                                            <i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i>
                                        </button>
                                    </div>
                                
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-0 col-md-0"></div>
                        </div>
                    </div>

                </div>
            @endforeach





        @endforeach
    @endif
</div>




<div class="container" id="allBarbershopSer02">
    
</div>


























<!-- Add to cart Service -->

@foreach(BarbershopService::where('toBar',$theBar)->get() as $barSer)
<!-- The Modal -->
<div class="modal" id="ServiceModal{{$barSer->id}}">
  <div class="modal-dialog modal-md">
    <div class="modal-content" style="border-radius:30px;">

      <!-- Modal body -->
      <div class="p-4 d-flex flex-wrap justify-content-between">
        <h4 style="width:90%;" class="modal-title">{{$barSer->emri}} <span style="color:lightgray;">
                                        ({{BarbershopCategory::find($barSer->kategoria)->emri}})</span></h4>
        <button onclick="cancelService('{{$barSer->id}}')" data-dismiss="modal" style="width:9%;" class="btn btn-default"> X </button>

        <p style="width:100%;" >{{$barSer->pershkrimi}}</p>

        <div style="border-bottom:1px solid lightgray; width:100%;" class="d-flex flex-wrap">
            <div class="text-center d-flex"  style="width:50%;">
                <?php 
                    if(isset($_GET['student']) && $barSer->qmimiSt != 0 ){
                        $serPr = sprintf('%01.2f', $barSer->qmimiSt);
                    }else{
                        $serPr = sprintf('%01.2f', $barSer->qmimi);
                    }
                ?>
                <span style="width:30%; font-size:22px;" class="opacity-65 pt-2">{{__('inc.currencyShow')}}</span>
                <input class="form-control color-qrorpa" style="width:70%; border:none; font-size:22px; font-weight:bold;" id="TotPriceBar{{$barSer->id}}" type="number"
                    value="{{$serPr}}" disabled>
            </div>
            <div style="width:50%;" class="text-center pt-2">
                <span style="width:60%; font-size:13px;" >{{__('inc.timeNeed')}}</span>
                <span style="width:30%; font-weight:bold;" >{{$barSer->timeNeed}}</span>
                <span style="width:10%; font-size:13px;" >{{__('inc.min')}}</span>
            </div>

            <button class="qrorpaBtn btn mb-1" style="width:100%;" data-toggle="modal" data-target="#setRezerBarSer{{$barSer->id}}">
                {{__('inc.makeReservation')}}
            </button>
            <div class="alert alert-danger" style="display:none;" id="setRezerBarSerAlertError01{{$barSer->id}}">
                {{__('inc.selectReservation')}}
            </div>

            <div style="width:100%;" class="mt-1 d-flex justify-content-between">
                <p style="font-weight:bold; color:rgb(72,81,87); width:33%;" class="text-center" id="workerDateShow{{$barSer->id}}"></p>
                <p style="font-weight:bold; color:rgb(72,81,87); width:33%;" class="text-center" id="workerShow{{$barSer->id}}"></p>
                <p style="font-weight:bold; color:rgb(72,81,87); width:33%;" class="text-center" id="workerTerminShow{{$barSer->id}}"></p>
            </div>
        </div>


        <?php $kaType = 0; $kaExtra = 0;
            foreach(explode('--0--',$barSer->type) as $serT){ if($serT != ''){$kaType++;}}
            foreach(explode('--0--',$barSer->extra) as $serE){ if($serE != ''){$kaExtra++;}}
        ?>
        @if($kaType > 0)
            <p onclick="showOtherType('{{$barSer->id}}')" class="text-center hover-pointer color-qrorpa mb-2" style=" margin-bottom:-3px; font-size:larger; width:100%;">
                <strong>{{__('inc.type')}}</strong></p>
            @foreach(explode('--0--',$barSer->type) as $serT)
                @if($serT != '')
                    <?php $serT2D = explode('||', $serT); ?>
                    <div style="width:20%;">
                        <label class="switch ">
                            <input style="width:5px;" onchange="setBarType('{{$serT2D[0]}}','{{$serT2D[1]}}','{{$serT2D[2]}}','{{$barSer->id}}')" type="checkbox" 
                            class="primary barTypeCheckBAll{{$barSer->id}}" id="barTypeCheckBOne{{$barSer->id}}O{{$serT2D[0]}}" >
                            <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                        </label>
                    </div>
                    <div class="d-flex" style="width:80%;">
                        <p style="width:70%;" class="text-left"><strong>{{$serT2D[1]}}</strong></p>
                        @if(isset($_GET['student']) && $barSer->qmimiSt != 0 )
                            <p style="width:30%;" class="text-right"> {{sprintf('%01.2f', ($serT2D[2] * $barSer->qmimiSt)) }}<sup>{{__('inc.currencyShow')}}</sup></p>
                        @else
                            <p style="width:30%;" class="text-right"> {{sprintf('%01.2f', ($serT2D[2] * $barSer->qmimi)) }}<sup>{{__('inc.currencyShow')}}</sup></p>    
                        @endif                                 
                    </div>
                @endif
            @endforeach    
        @endif


        @if($kaExtra > 0)
            <hr style="width:100%;">
            <p onclick="showOtherExtra('{{$barSer->id}}')" class="text-center hover-pointer color-qrorpa mb-2" style=" margin-bottom:-3px; font-size:larger; width:100%;">
                <strong>{{__('inc.extra')}}</strong></p>
            @foreach(explode('--0--',$barSer->extra) as $serE)
                @if($serE != '')
                    <?php $serE2D = explode('||', $serE); ?>
                    <div style="width:20%;">
                        <label class="switch "> 
                            <input style="width:5px;" type="checkbox" onchange="setBarExtra('{{$serE2D[0]}}','{{$serE2D[1]}}','{{$serE2D[2]}}','{{$barSer->id}}')"
                            class="primary" id="barExtraCheckBOne{{$barSer->id}}B{{$serE2D[0]}}">
                            <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                        </label>
                    </div>
                    <div class="d-flex" style="width:80%;">
                        <p style="width:70%;" class="text-left"><strong>{{$serE2D[1]}}</strong></p>
                        <p style="width:30%;" class="text-right" > 
                            <span id="SerExtraPrice{{$serE2D[0]}}O{{$barSer->id}}" class="SerExPr{{$barSer->id}}">{{sprintf('%01.2f', $serE2D[2]) }}</span>
                            <sup>{{__('inc.currencyShow')}}</sup>
                        </p>                                  
                    </div>
                @endif
            @endforeach    
        @endif

        @if(isset($_GET['student']) && $barSer->qmimiSt != 0 )
            <input type="hidden" name="QmimiBazeBar" value="{{$barSer->qmimiSt}}" id="QmimiBazeBar{{$barSer->id}}">
            <input type="hidden" name="QmimiSendBar" value="{{$barSer->qmimiSt}}" id="QmimiSendBar{{$barSer->id}}">
        @else
            <input type="hidden" name="QmimiBazeBar" value="{{$barSer->qmimi}}" id="QmimiBazeBar{{$barSer->id}}">
            <input type="hidden" name="QmimiSendBar" value="{{$barSer->qmimi}}" id="QmimiSendBar{{$barSer->id}}">
        @endif
        <input type="hidden" name="Emri" value="{{$barSer->emri}}" id="Emri{{$barSer->id}}">
        <input type="hidden" name="Pershkrimi" value="{{$barSer->pershkrimi}}" id="Pershkrimi{{$barSer->id}}">
        <input type="hidden" name="TimeN" value="{{$barSer->timeNeed}}" id="TimeN{{$barSer->id}}">
        <input type="hidden" name="Extra" value="" id="Extra{{$barSer->id}}">
        <input type="hidden" name="Type" value="" id="Type{{$barSer->id}}">

        <input type="hidden" name="workerDate" value="0" id="workerDate{{$barSer->id}}">
        <input type="hidden" name="worker" value="0" id="worker{{$barSer->id}}">
        <input type="hidden" name="workerTermin" value="0" id="workerTermin{{$barSer->id}}">

        <button type="button" class="btn btn-block"  id="sendToCartBtnBarSer{{$barSer->id}}" 
            style="background-color:rgb(39,190,175); color:white; border-radius:30px; position:fixed; height:70px; width:80%; bottom:50px;
            right:10%; left:10%; font-size:22px;"
            onclick="addToCartService('{{$barSer->id}}')">{{__('inc.addToCart')}}</button>
      </div> <!-- End modal body -->
    </div>
  </div>
</div>











            <!-- Modal per Termine -->
            <div class="modal" id="setRezerBarSer{{$barSer->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">

                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header" style="background-color:rgb(39,190,175);">
                            <h4 class="modal-title" style="color:white;">{{__('inc.reservationFor')}} {{$barSer->emri}} </h4>
                            <button type="button" style="color:white;" class="close" data-dismiss="modal" onclick="resetRezerBarSer('{{$barSer->id}}')">X</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body ">
                            <?php $heute = date('Y-m-d'); ?>
                            <div class="d-flex justify-content-between mb-3" >
                                <button class="btn btn-outline-dark mb-2 mt-1" style="width:49.6%;"
                                    onclick="fetchWorkersForSer('{{$heute}}', '{{$barSer->id}}', '{{$theBar}}', '{{$barSer->timeNeed}}','{{$barSer->kategoria}}')">{{__('inc.today')}}</button>

                                <input type="date" value="{{date('Y-m-d')}}" min="{{date('Y-m-d')}}"  style="width:49.6%;" class="text-center mb-2 mt-1 p-1" 
                                    name="dateTer{{$barSer->id}}"  placeholder="dd-mm-yyyy"
                                    onchange="fetchWorkersForSer(this.value, '{{$barSer->id}}', '{{$theBar}}', '{{$barSer->timeNeed}}','{{$barSer->kategoria}}')" >

                            </div>
                            <div class="d-flex flex-wrap justify-content-between"  id="workerTable{{$barSer->id}}">
                            </div>

                            <!-- Paraqitja e termineve per puntorin ne fjale  -->
                            <div class="alert alert-danger p-2 mt-1 mb-1" style="display:none;" id="errorWorkerTerminSel{{$barSer->id}}">
                                {{__('inc.text004')}}
                            </div>
                            <div id="workerTerTable{{$barSer->id}}">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endforeach







<script>
    function fetchWorkersForSer(dateSel, bSerId, bId, timeNeed, bSerCat){
        $('#workerTerTable'+bSerId).html('');
        $.ajax({
			url: '{{ route("barbershops.fetchtheWorkers") }}',
			method: 'post',
            dataType: 'json',
			data: {dateSelected: dateSel, barServiceId: bSerId, barId: bId, bSerCat: bSerCat, _token: '{{csrf_token()}}'},
			success: (res) => {
                $('#workerTable'+bSerId).html('');
                var listings = "";
                if(Object.keys(res).length == 0){
                    $('#workerTable'+bSerId).html('<p style="color:red; font-weight:bold;">'+$("#noWorkingStaffThisDay").val()+'</p>');
                }else{
                    $.each(res, function(index, value){
                        listings = '<div style="width:48%; border:1px solid rgb(39,190,175); border-radius:15px; font-weight:bold; color:rgb(39,190,175);"'+
                                    'class="p-2 ml-1 mb-2 text-center selectWorkerAll"'+
                                    'onclick="fetchWorkerTermins(\''+ bSerId+'\',\''+value.id+'\',\''+value.emri+'\',\''+dateSel+'\', \''+timeNeed+'\')"'+
                                    'id="selectWorker'+ bSerId+'O'+value.id+'">'+
                                    ''+value.emri+''+
                                    '</div>';
                        $('#workerTable'+bSerId).append(listings); 
                    });
                    $('#barServiceDateSet'+bSerId).hide(300);

                    $('#workerDate'+bSerId).val(dateSel);
                    $('#workerDateShow'+bSerId).html(dateSel);

                    $('#worker'+bSerId).val(0);
                    $('#workerShow'+bSerId).html('');

                    $('#workerTermin'+bSerId).val(0);
                    $('#workerTerminShow'+bSerId).html('');
                }
			},
			error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val());}
		});
    }





    function fetchWorkerTermins(barSerId, workerId, workerEmri, dateSelected, timeNeed){
        $('.selectWorkerAll').attr('style','width:48%; border:1px solid rgb(39,190,175); border-radius:15px; font-weight:bold; color:rgb(39,190,175);');
        $('#selectWorker'+barSerId+'O'+workerId).attr('style','width:48%; border:1px solid rgb(39,190,175); border-radius:15px; font-weight:bold; background-color:rgb(39,190,175); color:white;');
        $.ajax({
			url: '{{ route("barbershops.fetchtheWorkerTermins") }}',
			method: 'post',
            dataType: 'json',
			data: {dateSelected: dateSelected, barServiceId: barSerId, workerId: workerId, timeNeed: timeNeed, _token: '{{csrf_token()}}'},
			success: (res) => {
                $('#workerTerTable'+barSerId).html('');
                var listings = "";
                $.each(res, function(index, value){
                    if(value.statT == 1){
                        listings =  '<button class="btn btn-outline-success mb-1 ml-1" style="width:23.6%;"'+
                                        'onclick="selectWorkerTermin(\''+barSerId+'\',\''+workerId+'\',\''+value.id+'\',\''+value.startT+'\',\''+timeNeed+'\')" '+
                                        'id="selectWorker'+barSerId+'O'+workerId+'">'+
                                        ''+value.startT+''+
                                    '</button>'+
                                    '<input type="hidden" value="1" id="selectTerValSer'+barSerId+'O'+value.id+'">';
                    }else if(value.statT == 2){
                        listings =  '<button class="btn btn-danger mb-1 ml-1" style="width:23.6%;"'+
                                        'id="selectWorker'+barSerId+'O'+workerId+'" disabled>'+
                                        ''+value.startT+''+ 
                                    '</button>'+
                                    '<input type="hidden" value="2" id="selectTerValSer'+barSerId+'O'+value.id+'">';

                    }else{
                        // the disabled ones
                        listings =  '<button class="btn btn-outline-success mb-1 ml-1" style="width:23.6%;"'+
                                        'id="selectWorker'+barSerId+'O'+workerId+'" disabled>'+
                                        ''+value.startT+''+ 
                                    '</button>'+
                                    '<input type="hidden" value="1" id="selectTerValSer'+barSerId+'O'+value.id+'">';

                    }
                    $('#workerTerTable'+barSerId).append(listings); 
                });
                
                $('#worker'+barSerId).val(workerId);
                $('#workerShow'+barSerId).html(workerEmri);
			},
			error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val());}
		});
    }



    function selectWorkerTermin(bSerId, wId, wTerId, wTerStart, timeNeed){
        
        var slotsNeed = Math.ceil(parseInt(timeNeed) / 15);
        var slotsTaken = parseInt(1);

        // ?need 2
        if(slotsNeed >= ++slotsTaken){
            var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
            if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                // ?need 3
                if(slotsNeed >= ++slotsTaken){
                    var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                    if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                        // ?need 4
                        if(slotsNeed >= ++slotsTaken){
                            var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                            if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                                // ?need 5
                                if(slotsNeed >= ++slotsTaken){
                                    var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                                    if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                                        // ?need 6
                                        if(slotsNeed >= ++slotsTaken){
                                            var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                                            if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                                                // ?need 7
                                                if(slotsNeed >= ++slotsTaken){
                                                    var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                                                    if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                                                        // ?need 8
                                                        if(slotsNeed >= ++slotsTaken){
                                                            var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                                                            if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                                                                // ?need 9
                                                                if(slotsNeed >= ++slotsTaken){
                                                                    var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                                                                    if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                                                                        // ?need 10
                                                                        if(slotsNeed >= ++slotsTaken){
                                                                            var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                                                                            if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                                                                                // ?need 11
                                                                                if(slotsNeed >= ++slotsTaken){
                                                                                    var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                                                                                    if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                                                                                        // ?need 12
                                                                                        if(slotsNeed >= ++slotsTaken){
                                                                                            var idToCheck = parseInt(wTerId) + parseInt(slotsTaken - parseInt(1));
                                                                                            if( $('#selectTerValSer'+bSerId+'O'+idToCheck).val() == 1){
                                                                                                
                                                                                                $('#workerTermin'+bSerId).val(wTerId);
                                                                                                $('#workerTerminShow'+bSerId).html(wTerStart);
                                                                                                $('#sendToCartBtnBarSer'+bSerId).attr('data-dismiss', 'modal');
                                                                                                $('#setRezerBarSer'+bSerId).modal('toggle');
                                                                                    
                                                                                            }else{/* termin BUSY +11 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                                                                                         }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
                                                                                    }else{/* termin BUSY +10 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                                                                                }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
                                                                            }else{/* termin BUSY +9 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                                                                        }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
                                                                    }else{/* termin BUSY +8 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                                                                }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
                                                            }else{/* termin BUSY +7 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                                                        }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
                                                    }else{/* termin BUSY +6 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                                                }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
                                            }else{/* termin BUSY +5 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                                        }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
                                    }else{/* termin BUSY +4 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                                }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
                            }else{/* termin BUSY +3 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                        }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
                    }else{/* termin BUSY +2 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
                }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
            }else{/* termin BUSY +1 */ $('#errorWorkerTerminSel'+bSerId).show(200).delay(3500).hide(200);}
        }else{ /* nuk ka nevor per tjera */ selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed);}
    }


    function selectWorkerTerminSuccess(bSerId, wId, wTerId, wTerStart, timeNeed){
        $('#workerTermin'+bSerId).val(wTerId);
        $('#workerTerminShow'+bSerId).html(wTerStart);
        $('#sendToCartBtnBarSer'+bSerId).attr('data-dismiss', 'modal');
        $('#setRezerBarSer'+bSerId).modal('toggle');
    }




    function resetRezerBarSer(barSerId){
        $("#setRezerBarSer"+barSerId).load(location.href+" #setRezerBarSer"+barSerId+">*","");
    }
</script>




















<div id="bottomCartBtn">
    @if(count(Cart::content()) > 0)
    <!--  route('cart') -->
    <div class="footerPhone" data-toggle="modal" data-target="#OrdersView" id="footerShowOrdersMobile">
        @if(isset($_GET['student']) && $serWithStPrice > 0 )
            <a href="/order?student">
        @else
            <a href="{{route('cart')}}">
        @endif
            <button id="anchorOrder" class="btn btn-default">
                <!-- <i class="fas fa-shopping-basket fa-lg"></i> -->

                <p style="margin-bottom:-6px;"> <img style="width:35px;" src="storage/icons/SAI01OrW.png" alt="">
                    <sup id="CartCountFooter" class="mr-5 pt-1 pl-2 pr-2 pb-1 color-qrorpa" style="width:20px; height:20px; border-radius:50%; font-size:19px; font-weight:bold;
                background-color:white; color:black;">{{Cart::count()}}</sup>
                    <span id="CartTotalFooter" class="ml-5" style="font-size:27px; color:white; font-weight:bold;">
                        <span id="CartTotalFooter2"
                            >{{Cart::total()}}</span> <sup>{{__('inc.currencyShow')}}</sup> </span> </p>


            </button>
        </a>
    </div>
    @endif
</div>




















<script>
    function showBarKat(kId){
        if($('.serviceList'+kId).is(":hidden")){
            $('.serviceList'+kId).show(200);
        }else{
            $('.serviceList'+kId).hide(200);
        }
    }

    function cancelService(sId){
        $("#ServiceModal"+sId).load(location.href+" #ServiceModal"+sId+">*","");
        tValue = 0;
    }












    function setBarType(tId, tEmri, tVal, sId){
       
        if($('#barTypeCheckBOne'+sId+'O'+tId).is(":checked")){
            $('.barTypeCheckBAll'+sId).each(function(i, obj) {
                $(this).prop( "checked", false );
            });
            $('#barTypeCheckBOne'+sId+'O'+tId).prop( "checked", true );

            var QBaz =parseFloat($('#QmimiBazeBar'+sId).val()).toFixed(2);

            if(tValue != 0){
                $('.SerExPr'+sId).each(function(i, obj) {
                    var newEXVa01 = parseFloat($(this).text()).toFixed(2);
                    var newEXVa02 = parseFloat(newEXVa01 / parseFloat(tValue)).toFixed(2) ;
                    var newEXVa03 = parseFloat(newEXVa02 * parseFloat(tVal)).toFixed(2) ;
                    $(this).html(newEXVa03);
                });
                var plusFromEx = parseFloat(0).toFixed(2);

                if($('#Extra'+sId).val() != ''){
                    var extras = $('#Extra'+sId).val().split('--0--');

                    $('#Extra'+sId).val('')
                    for (var i = 0; i < extras.length; i++) {
                        if(extras[i] != ''){
                        var extras2D = extras[i].split('||');
                        var NewPriceEx01 = parseFloat(parseFloat(extras2D[2]).toFixed(2) / parseFloat(tValue).toFixed(2)).toFixed(2);
                        var NewPriceEx02 = parseFloat(parseFloat(NewPriceEx01).toFixed(2) * parseFloat(tVal).toFixed(2)).toFixed(2);
                     
                            if(plusFromEx == 0){
                                plusFromEx = parseFloat(NewPriceEx02).toFixed(2);
                            }else{
                                plusFromEx = (parseFloat(plusFromEx) + parseFloat(NewPriceEx02)).toFixed(2);
                            }
                            // console.log(NewPriceEx02);
                            // console.log('Tot :'+plusFromEx);

                            if($('#Extra'+sId).val() == ''){
                                $('#Extra'+sId).val(extras2D[0]+"||"+extras2D[1]+"||"+NewPriceEx02);   
                            }else{
                                $('#Extra'+sId).val($('#Extra'+sId).val()+"--0--"+extras2D[0]+"||"+extras2D[1]+"||"+NewPriceEx02); 
                            } 
                        }
                    }
                }

                var QmimiNewPost = (parseFloat(QBaz)* parseFloat(tVal)).toFixed(2);
                if(parseFloat(plusFromEx) != 0){
                    var QmimiNew = (parseFloat(QmimiNewPost) + parseFloat(plusFromEx)).toFixed(2);
                }else{
                    var QmimiNew = (parseFloat(QmimiNewPost)).toFixed(2);
                }
              

                $('#TotPriceBar'+sId).val(QmimiNew);
                $('#QmimiSendBar'+sId).val(QmimiNew);
                $('#Type'+sId).val(tId+"||"+tEmri+"||"+tVal);

            }else{
                $('.SerExPr'+sId).each(function(i, obj) {
                    var newEXVa = parseFloat(parseFloat($(this).text()) * parseFloat(tVal)).toFixed(2);
                    $(this).html(newEXVa);
                });

                var plusFromEx = parseFloat(0).toFixed(2);
                if($('#Extra'+sId).val() != ''){
                    var extras = $('#Extra'+sId).val().split('--0--');
                    $('#Extra'+sId).val('');

                    for (var i = 0; i < extras.length; i++) {
                        if(extras[i] != ''){
                        var extras2D = extras[i].split('||');
                        var NewPriceEx02 = parseFloat(parseFloat(extras2D[2]).toFixed(2) * parseFloat(tVal).toFixed(2)).toFixed(2);
                        
                            if(plusFromEx == 0){
                                plusFromEx = parseFloat(NewPriceEx02).toFixed(2);
                            }else{
                                plusFromEx = (parseFloat(plusFromEx) + parseFloat(NewPriceEx02)).toFixed(2);
                            }

                            if($('#Extra'+sId).val() == ''){
                                $('#Extra'+sId).val(extras2D[0]+"||"+extras2D[1]+"||"+NewPriceEx02);   
                            }else{
                                $('#Extra'+sId).val($('#Extra'+sId).val()+"--0--"+extras2D[0]+"||"+extras2D[1]+"||"+NewPriceEx02); 
                            } 
                        }
                    }
                }
                var QmimiNewPost = (parseFloat(QBaz) * parseFloat(tVal)).toFixed(2);
                if(parseFloat(plusFromEx) > 0){
                    var QmimiNew = (parseFloat(QmimiNewPost) + parseFloat(plusFromEx)).toFixed(2);
                }else{
                    var QmimiNew = (parseFloat(QmimiNewPost)).toFixed(2);
                }

                $('#TotPriceBar'+sId).val(QmimiNew);
                $('#QmimiSendBar'+sId).val(QmimiNew);
                $('#Type'+sId).val(tId+"||"+tEmri+"||"+tVal);

            }
            
            tValue = tVal;

        }else{

            $('.SerExPr'+sId).each(function(i, obj) {
                var newEXVa01 = parseFloat($(this).text()).toFixed(2);
                var newEXVa02 = parseFloat(newEXVa01 / parseFloat(tValue)).toFixed(2) ;
                $(this).html(newEXVa02);
            });
            $('#Type'+sId).val('');

            var QBaz =parseFloat($('#QmimiBazeBar'+sId).val()).toFixed(2);

            var plusFromEx = parseFloat(0).toFixed(2);
            
                if($('#Extra'+sId).val() != ''){
                    var extras = $('#Extra'+sId).val().split('--0--');
                    $('#Extra'+sId).val('');

                    for (var i = 0; i < extras.length; i++) {
                        if(extras[i] != ''){
                            var extras2D = extras[i].split('||');
                            var NewPriceEx02 = parseFloat(parseFloat(extras2D[2]).toFixed(2) / parseFloat(tValue).toFixed(2)).toFixed(2);
                                if(plusFromEx == 0){
                                    plusFromEx = parseFloat(NewPriceEx02).toFixed(2);
                                }else{
                                    plusFromEx = (parseFloat(plusFromEx) + parseFloat(NewPriceEx02)).toFixed(2);
                                }
                                if($('#Extra'+sId).val() == ''){
                                    $('#Extra'+sId).val(extras2D[0]+"||"+extras2D[1]+"||"+NewPriceEx02);   
                                }else{
                                    $('#Extra'+sId).val($('#Extra'+sId).val()+"--0--"+extras2D[0]+"||"+extras2D[1]+"||"+NewPriceEx02); 
                                } 
                        }
                    }
                }
                var QmimiNewPost = (parseFloat(QBaz)).toFixed(2);

                if(parseFloat(plusFromEx) > 0){
                    var QmimiNew = (parseFloat(QmimiNewPost) + parseFloat(plusFromEx)).toFixed(2);
                }else{
                    var QmimiNew = (parseFloat(QmimiNewPost)).toFixed(2);
                }

                $('#TotPriceBar'+sId).val(QmimiNew);
                $('#QmimiSendBar'+sId).val(QmimiNew);

            tValue = 0;
       
        }
        
    }// End SetBarType

















    function setBarExtra(eId, eEmri, eQmimi, sId){
        var TotPrice = parseFloat($('#TotPriceBar'+sId).val()).toFixed(2);
        if(tValue != 0){
            var FinalPrice = parseFloat(parseFloat(eQmimi) * parseFloat(tValue)).toFixed(2);
        }else{
            var FinalPrice =parseFloat(eQmimi).toFixed(2);
        }
        if($('#barExtraCheckBOne'+sId+'B'+eId).is(":checked")){
            if($('#Extra'+sId).val() == ''){
                $('#Extra'+sId).val(eId+"||"+eEmri+"||"+FinalPrice);   
            }else{
                $('#Extra'+sId).val($('#Extra'+sId).val()+"--0--"+eId+"||"+eEmri+"||"+FinalPrice); 
            }
            var QmimiFinal = (parseFloat(TotPrice) + parseFloat(FinalPrice)).toFixed(2);
            $('#TotPriceBar'+sId).val(QmimiFinal);
            $('#QmimiSendBar'+sId).val(QmimiFinal);

        }else{
            var newValDel = $('#Extra'+sId).val().replace(eId+"||"+eEmri+"||"+FinalPrice, "");
            $('#Extra'+sId).val(newValDel);
            
            var QmimiFinal = (parseFloat(TotPrice) - parseFloat(FinalPrice)).toFixed(2);
            $('#TotPriceBar'+sId).val(QmimiFinal);
            $('#QmimiSendBar'+sId).val(QmimiFinal);
        }
    }











    function addToCartService(sId){
        if($('#worker'+sId).val() == 0 || $('#workerTermin'+sId).val() == 0 || $('#workerDate'+sId).val() == 0){
            $('#setRezerBarSerAlertError01'+sId).show(200).delay(4500).hide(200);
        }else{
            $.ajax({
                url: '{{ route("cart.storeBar") }}',
                method: 'post',
                data: {
                    id: sId,
                    qmimi: $('#QmimiSendBar'+sId).val(),
                    emri: $('#Emri'+sId).val(),
                    pershkrimi: $('#Pershkrimi'+sId).val(),
                    extra: $('#Extra'+sId).val(),
                    type: $('#Type'+sId).val(),
                    timeN: $('#TimeN'+sId).val(),
                    worker: $('#worker'+sId).val(),
                    workerDate: $('#workerDate'+sId).val(),
                    workerTer: $('#workerTermin'+sId).val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#bottomCartBtn").load(location.href+" #bottomCartBtn>*","");
                    $("#ServiceModal"+sId).load(location.href+" #ServiceModal"+sId+">*","");
                    $("#setRezerBarSer"+sId).load(location.href+" #setRezerBarSer"+sId+">*","");  
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            });
        }
    }
</script>