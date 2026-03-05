<?php
    use App\BarbershopWorker;
    use App\BarbershopWorkerTerminet;
    use App\BarbershopWorkerDays;
    use App\BarbershopCategory;
    use App\BarbershopService;
    use App\BarbershopType;
    use App\WorkerCategoryDone;

    $barbershopId = Auth::user()->sFor;
?>

<style>
    .btnQrorpa{
        color:rgb(39,190,175);
        border:1px solid rgb(39,190,175);
        font-weight: bold;
    }
    .btnQrorpaRed{
        color:white;
        border:1px solid red;
        background-color: red;
        font-weight: bold;
    }
    .btnQrorpaSelected{
        color:white;
        border:1px solid rgb(39,190,175);
        background-color: rgb(39,190,175);
        font-weight: bold;
    }




    .allDivServices{
        color:rgb(72,81,87);
        border:1px solid rgb(72,81,87,0.2);
        border-radius: 10px;
        font-weight: bold; 
    }
    .allDivServices00{
        color:rgb(72,81,87);
        border:1px solid rgb(72,81,87,0.2);
        border-radius: 10px;
        font-weight: bold; 
    }
    .allDivServices00:hover{
        color:white;
        border:1px solid rgb(39,190,175);
        border-radius: 10px;
        font-weight: bold; 
        background-color: rgb(39,190,175);
        cursor: pointer;
    }
    .allDivServices00Selected{
        color:white;
        border:1px solid rgb(39,190,175);
        border-radius: 10px;
        font-weight: bold; 
        background-color: rgb(39,190,175);
        cursor: pointer;
    }
</style>


<section class="pl-4 pr-4 pb-5" id="allBarRezAdmSet">
    <div class="d-flex">
        <h2 style="width:80%; color:rgb(39,190,175);"><strong>{{__('adminP.registerAReservation')}}</strong></h2>
        <button style="width:20%;" class="btn btn-outline-danger text-center" onclick="resetForm()"><strong><i class="fas fa-redo-alt"></i> {{__('adminP.emptyForm')}}</strong></button>
    </div>
 
    <hr>

    <div class="d-flex flex-wrap justify-content-between">
        <div style="width:40%;">
            <div class="d-flex flex-wrap justify-content-start">
                @foreach(BarbershopCategory::where('toBar',$barbershopId)->get() as $barCat)
                    <button onclick="selectBarCat('{{$barCat->id}}')" style="width:24.5%; margin-right:0.5%" id="barCategoryBtn{{$barCat->id}}"
                        class="btn btnQrorpa allBarCategory mb-1 shadow">{{$barCat->emri}}</button>
                @endforeach

                <input type="hidden" value="0" name="barCatSelectInput" id="barCatSelectInput">
            </div>
            
            <input type="date" value="{{date('Y-m-d')}}" min="{{date('Y-m-d')}}"  style="width:100%; display:none;" class="text-center mt-2 p-1" name="dateSel" id="dateSel"
                placeholder="dd-mm-yyyy" onchange="fetchWorkers(this.value,'{{$barbershopId}}')">
            <input type="hidden" value="0" name="barDateSelectInput" id="barDateSelectInput">

            <div class="d-flex flex-wrap justify-content-start mt-2" id="workers">
            </div>
            <div class="alert alert-danger mt-2" style="display:none;" id="selectWorkerError01">{{_('adminP.selectService')}}</div>

            <input type="hidden" value="0" name="barWorkerSelectInput" id="barWorkerSelectInput">
        </div>

        <div  class="d-flex flex-wrap justify-content-start" style="width:59.5%;" id="workerTermins">
        </div>
    </div>

    <input type="hidden" value="0" name="barServiceSelectInput" id="barServiceSelectInput">
    <input type="hidden" value="0" name="barTypeSelectInput" id="barTypeSelectInput">
    <input type="hidden" value="0" name="barShumaFinalSelectInput" id="barShumaFinalSelectInput">
    <input type="hidden" value="0" name="barTimeNeedSelectInput" id="barTimeNeedSelectInput">

    <input type="hidden" value="0" name="barTerminStartSelectInput" id="barTerminStartSelectInput">
    <input type="hidden" value="0" name="barTerminsNeedSelectInput" id="barTerminsNeedSelectInput">

    <div class="d-flex justify-content-between mt-3" style="background-color:rgb(39,190,175); border-radius:15px;" >
        <div style="width:48%;" class="text-right">
            <p style="font-weight:bold; font-size:26px; color:white;" id="selServiceShowName"></p>
            <p style="font-weight:bold; font-size:22px; color:white; margin-top:-10px;" id="selServiceShowDesc"></p>
            <p style="font-weight:bold; font-size:22px; color:white; margin-top:-10px;" id="selServiceShowType"></p>
        </div>
        <div style="width:48%;" class="text-left">
            <p style="font-weight:bold; font-size:26px; color:white;" id="selServiceShowPrice"></p>
            <p style="font-weight:bold; font-size:22px; color:white; margin-top:-10px;" id="selServiceShowTimeNeed"></p>
            <p style="font-weight:bold; font-size:22px; color:white; margin-top:-10px;" id="selServiceShowXTimes"></p>
        </div>
    </div>

    <div class="mt-3 p-2 alert alert-danger" style="display:none;" id="notEnoughTimeTermin">
        <h3><strong>{{_('adminP.noEnoughTime')}}</strong></h3>
    </div>
    <div class="mt-3 p-2 d-flex flex-wrap justify-content-between" style="display:none !important;" id="enoughTimeTermin">
        <div style="width:15%;" class="form-group">
            <label for="usr"><strong>{{__('adminP.name')}}:</strong></label>
            <input id="barNameSelectInput" type="text" class="form-control">
        </div>
        <div style="width:15%;" class="form-group">
            <label for="usr"><strong>{{__('adminP.lastName')}}:</strong></label>
            <input id="barLastnameSelectInput" type="text" class="form-control">
        </div>
        <div style="width:25%;" class="form-group">
            <label for="usr"><strong>{{__('adminP.email')}}:</strong></label>
            <input id="barEmailSelectInput" type="text" class="form-control">
        </div>
        <div style="width:15%;" class="form-group">
            <label for="usr"><strong>{{__('adminP.phoneNumber')}}:</strong></label>
            <input id="barNrTelSelectInput" type="number" class="form-control">
        </div>
        <button style="width:25%;" class="btn btnQrorpaSelected" onclick="saveReservation('{{$barbershopId}}')"><strong>{{__('adminP.saveTheReservation')}}</strong></button>
    </div>

    <div class="mt-3 p-2 text-center alert alert-danger " style="display:none; font-weight:bold; font-size:23px;" id="saveRezError"></div>

    @foreach(BarbershopCategory::where('toBar',$barbershopId)->get() as $barCategories)
        <div class="d-flex flex-wrap justify-content-start mt-5 allBarCatServices" style="display:none !important;"  id="barCatServices{{$barCategories->id}}">
            @foreach(BarbershopService::where('kategoria',$barCategories->id)->get() as $barService)
                @if($barService->type == '' )
                <div onclick="selectTheService('{{$barCategories->id}}','{{$barService->id}}','{{$barService->timeNeed}}','0','','','{{$barService->emri}}','{{$barService->pershkrimi}}','{{$barService->qmimi}}')" 
                    style="width:49.5%; margin-right:0.5%;" 
                    class="d-flex flex-wrap justify-content-between p-2 allDivServices00 shadow mb-2" id="barService{{$barService->id}}">
                @else
                <div style="width:49.5%; margin-right:0.5%;" class="d-flex flex-wrap justify-content-between p-2 allDivServices shadow mb-2">
                @endif
                    <div style="width:75%;">
                        <p style="color:rgb(72,81,87); font-size:22px;"><strong>{{$barService->emri}}</strong></p>
                        <p style="color:rgb(72,81,87); margin-top:-15px;"><strong>{{$barService->pershkrimi}}</strong></p>
                    </div>
                    <div style="width:25%;" class="text-center">
                        <p style="color:rgb(72,81,87); font-size:25px;" class="pt-2"><strong>{{$barService->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong></p>
                    </div>
                    <div style="width:100%;" class="d-flex flex-wrap justify-content-start">
                   
                        @foreach(explode('--0--',$barService->type) as $bSerType)
                            @if($bSerType != '')
                                <?php $bSerType2D = explode('||',$bSerType) ;?>
                                @if($bSerType2D != '')
                                    <button style="width:33%; margin-right:0.33%" class="btn mb-1 allDivServices00"
                                    onclick="selectTheService('{{$barCategories->id}}','{{$barService->id}}','{{$barService->timeNeed}}','{{$bSerType2D[0]}}','{{$bSerType2D[1]}}','{{$bSerType2D[2]}}',
                                            '{{$barService->emri}}','{{$barService->pershkrimi}}','{{$barService->qmimi}}')">
                                        {{$bSerType2D[1]}} ( {{$bSerType2D[2]}}X )</button>
                                @endif
                            @endif
                        @endforeach
                    </div>
                  
                </div>
            @endforeach
        </div>
    @endforeach
  
</section>























<script>

    function resetForm(){
        $("#allBarRezAdmSet").load(location.href+" #allBarRezAdmSet>*","");
    }

    function selectBarCat(barCatSelId){
        $('#barCategoryBtn'+barCatSelId).removeClass('btnQrorpa');
        $('#barCategoryBtn'+barCatSelId).addClass('btnQrorpaSelected');
        $('.allBarCategory').attr('disabled','true');

        $('#barCatSelectInput').val(barCatSelId);

        $('#barCatServices'+barCatSelId).show(400);

        $('#dateSel').show(250);
    }

    function fetchWorkers(dateSel, bId){
        $('#barDateSelectInput').val(dateSel);

       

        $.ajax({
			url: '{{ route("barAdmin.setRezFetchWorkers") }}',
			method: 'post',
            dataType: 'json',
			data: {dateSelected: dateSel, barId: bId, catSel: $('#barCatSelectInput').val(), _token: '{{csrf_token()}}'},
			success: (res) => {
                if(Object.keys(res).length == 0){
                    $('#workers').html('<p style="font-weight:bold; font-size:22px; color:red;">'+$("#noStaffAvailableThisDate").val()+'</p>');
                }else{
                    $('#dateSel').attr('disabled','true');
                    $('#workers').html('');
                    var listings = "";
                    $.each(res, function(index, value){
                        listings = '<button class="btn btnQrorpa allBtnBarWorkers text-center" id="btnBarWorkers'+value.id+'" style="width:24.5%; margin-right:0.5%"'+
                                'onclick="selWorkerGetTer(\''+value.id+'\',\''+dateSel+'\')">'+
                                ''+value.emri+''+
                                '</button>';
                        $('#workers').append(listings); 
                    });
                }
			},
			error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val());}
		});
    }


    function selWorkerGetTer(workerID, dateSel){
        if($('#barServiceSelectInput').val() == 0){
            $('#selectWorkerError01').attr('style','display:block;');
            setTimeout(function () {
                $('#selectWorkerError01').attr('style','display:none;');
            }, 2500);
        }else{
            $('#barWorkerSelectInput').val(workerID);
            $('.allBtnBarWorkers').attr('class','btn btnQrorpa allBtnBarWorkers text-center');
            // $('#btnBarWorkers'+workerID).removeClass('btnQrorpa');
            $('#btnBarWorkers'+workerID).addClass('btnQrorpaSelected');

            $('#workerTermins').html('<p style="color:red; font-size:25px;" class="pt-3 text-center"><strong>'+$("#loading").val()+'...</strong></p>');
            $.ajax({
                url: '{{ route("barAdmin.setRezFetchWorkerTers") }}',
                method: 'post',
                dataType: 'json',
                data: {dateSelected: dateSel, workerId: workerID, serviceId: $('#barServiceSelectInput').val(), _token: '{{csrf_token()}}'},
                success: (res) => {
                    $('#workerTermins').html('');
                    var listings = "";
                
                    $.each(res, function(index, value){
                        if(value.validity == 0){
                            listings = '<button class="btn btnQrorpa allBtnBarWorkerTermins text-center mb-1" id="btnBarWorkerTermins'+value.id+'" style="width:12.25%; margin-right:0.25%"'+
                                    'onclick="selectTheTermin(\''+value.id+'\')">'+
                                    ''+value.startT+''+
                                    '</button>'+
                                    '<input type="hidden" id="terminState'+value.id+'" value="0"> ';
                        }else if(value.validity == 1){
                            listings = '<button class="btn btnQrorpa allBtnBarWorkerTermins1 text-center mb-1" id="btnBarWorkerTermins'+value.id+'" style="width:12.25%; margin-right:0.25%" disabled>'+
                                    ''+value.startT+''+
                                    '</button>'+
                                    '<input type="hidden" id="terminState'+value.id+'" value="1"> ';
                        }else if(value.validity == 2){
                            listings = '<button class="btn btnQrorpaRed allBtnBarWorkerTermins2 text-center mb-1" id="btnBarWorkerTermins'+value.id+'" style="width:12.25%; margin-right:0.25%" disabled>'+
                                    ''+value.startT+''+
                                    '</button>'+
                                    '<input type="hidden" id="terminState'+value.id+'" value="2"> ';
                        }
                        $('#workerTermins').append(listings); 
                    });
                },
                error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val());}
            });
        }
    }





    function selectTheService(serCatId, serId, serTimeNeed, typeId, typeEmri, typeXTimes, serEmri, serPersh, serPrice){
      
        if(typeId == 0){
            $('#barServiceSelectInput').val(serId);
            $('#barTypeSelectInput').val(typeId);
            $('#barTimeNeedSelectInput').val(serTimeNeed);

            $('#barCatServices'+serCatId).html('');

            $('#selServiceShowName').html(serEmri);
            $('#selServiceShowDesc').html(serPersh);

            $('#selServiceShowPrice').html(serPrice+ '' +$("#currencyShow").val());
            $('#selServiceShowTimeNeed').html(serTimeNeed+'('+$("#minutes").val()+')');

            $('#barShumaFinalSelectInput').val(serPrice);
        }else{
            $('#barServiceSelectInput').val(serId);
            $('#barTypeSelectInput').val(typeId);
            $('#barTimeNeedSelectInput').val(serTimeNeed);

            $('#barCatServices'+serCatId).html('');

            $('#selServiceShowName').html(serEmri);
            $('#selServiceShowDesc').html(serPersh);
            $('#selServiceShowType').html('"'+typeEmri+'"');

            var finalPrice = parseFloat(serPrice * typeXTimes).toFixed(2);
            $('#selServiceShowPrice').html(finalPrice+ '' +$(#currencyShow).val());
            $('#selServiceShowTimeNeed').html(serTimeNeed+'('+$("#minutes").val()+')');
            $('#selServiceShowXTimes').html(typeXTimes+ '' +$('#xOfOriginalPrice').val()+ '' +serPrice+')');

            $('#barShumaFinalSelectInput').val(finalPrice);
        }
    }





    function selectTheTermin(wTerId){
        var terminesTake = Math.ceil(($('#barTimeNeedSelectInput').val() / 15));
        // alert(terminesTake);
        var step = 0;
        var checkThis = 0;
        var notEnoughTime = false;
        while(step++ < (terminesTake-1)){
            checkThis = parseInt(step) + parseInt(wTerId);
            if($('#terminState'+checkThis).val() == 2){
                notEnoughTime = true;
            }
        }

        if(notEnoughTime){
            $('#notEnoughTimeTermin').show(200).delay(4000).hide(200);
        }else{
            $('.allBtnBarWorkerTermins').attr('class','btn btnQrorpa allBtnBarWorkerTermins text-center mb-1');
            $('#btnBarWorkerTermins'+wTerId).attr('class','btn btnQrorpaSelected allBtnBarWorkerTermins text-center mb-1');

            $('#enoughTimeTermin').show(200);

            $('#barTerminStartSelectInput').val(wTerId);
            $('#barTerminsNeedSelectInput').val(terminesTake);
        }


    }









    function saveReservation(barID){
        var Cat = $('#barCatSelectInput').val();
        var DateSel = $('#barDateSelectInput').val();
        var Worker = $('#barWorkerSelectInput').val();
        var Service = $('#barServiceSelectInput').val();
        var Type = $('#barTypeSelectInput').val();
        var ShumaFinal = $('#barShumaFinalSelectInput').val();
        var TimeNeed = $('#barTimeNeedSelectInput').val();
        var TerminStart = $('#barTerminStartSelectInput').val();
        var TerminsNeed = $('#barTerminsNeedSelectInput').val();

        var Name = $('#barNameSelectInput').val();
        var Lastname = $('#barLastnameSelectInput').val();
        var Email = $('#barEmailSelectInput').val();
        var NrTel = $('#barNrTelSelectInput').val();
        
        if(Name == '' || Lastname == '' || Email == '' || Email == ''){
            $('#saveRezError').html($('#plsProvideCustomersData').val());
            $('#saveRezError').show('200').delay(3500).hide(200);
        }else{
            $.ajax({
                url: '{{ route("barAdmin.setRezSaveTheRezervation") }}',
                method: 'post',
                data: {
                    barId: barID,
                    cat: Cat,
                    date: DateSel,
                    worker: Worker,
                    service: Service,
                    type: Type,
                    shumaFinal: ShumaFinal,
                    timeNeed: TimeNeed,
                    terminStart: TerminStart,
                    terminsNeed: TerminsNeed,
                    name: Name,
                    lastname: Lastname,
                    email: Email,
                    nrTel: NrTel,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#allBarRezAdmSet").load(location.href+" #allBarRezAdmSet>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#pleaseUpdateAndTryAgain').val());
                }
            });
        }
    }



</script>

