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
    .btnQrorpaTel{
        color:rgb(39,190,175);
        border:1px solid rgb(39,190,175);
        font-weight: bold;
    }
    .btnQrorpaRedTel{
        color:white;
        border:1px solid red;
        background-color: red;
        font-weight: bold;
    }
    .btnQrorpaSelectedTel{
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

<section class="pl-1 pr-1 pb-5" id="allBarRezAdmSetTel">
    <div class="d-flex pt-1">
        <h5 style="width:50%; color:rgb(39,190,175);" class="pt-1"><strong>{{__('adminP.reservation')}}</strong></h5>
        <button style="width:50%;" class="btn btn-outline-danger text-center" onclick="resetFormTel()"><strong><i class="fas fa-redo-alt"></i> {{__('adminP.emptyForm')}}</strong></button>
    </div>
 
    <hr>

    <div class="d-flex flex-wrap justify-content-between">
        <div style="width:100%;">
            <div class="d-flex flex-wrap justify-content-start">
                @foreach(BarbershopCategory::where('toBar',$barbershopId)->get() as $barCat)
                    <button onclick="selectBarCatTel('{{$barCat->id}}')" style="width:49.5%; margin-right:0.5%" id="barCategoryBtnTel{{$barCat->id}}"
                        class="btn btnQrorpaTel allBarCategoryTel mb-1 shadow">{{$barCat->emri}}</button>
                @endforeach

                <input type="hidden" value="0" name="barCatSelectInputTel" id="barCatSelectInputTel">
            </div>
            <input type="date" value="{{date('Y-m-d')}}" min="{{date('Y-m-d')}}"  style="width:100%; display:none;" class="text-center mt-2 p-1" name="dateSelTel" id="dateSelTel"
                placeholder="dd-mm-yyyy" onchange="fetchWorkersTel(this.value,'{{$barbershopId}}')">
            <input type="hidden" value="0" name="barDateSelectInputTel" id="barDateSelectInputTel">

            <div class="d-flex flex-wrap justify-content-start mt-2" id="workersTel">
            </div>
            <div class="alert alert-danger mt-2" style="display:none;" id="selectWorkerErrorTel01">{{__('adminP.selectService')}}</div>

            <input type="hidden" value="0" name="barWorkerSelectInputTel" id="barWorkerSelectInputTel">
        </div>

        <div  class="d-flex flex-wrap justify-content-start mt-1" style="width:100%;" id="workerTerminsTel">
        </div>
    </div>

    <input type="hidden" value="0" name="barServiceSelectInputTel" id="barServiceSelectInputTel">
    <input type="hidden" value="0" name="barTypeSelectInputTel" id="barTypeSelectInputTel">
    <input type="hidden" value="0" name="barShumaFinalSelectInputTel" id="barShumaFinalSelectInputTel">
    <input type="hidden" value="0" name="barTimeNeedSelectInputTel" id="barTimeNeedSelectInputTel">

    <input type="hidden" value="0" name="barTerminStartSelectInputTel" id="barTerminStartSelectInputTel">
    <input type="hidden" value="0" name="barTerminsNeedSelectInputTel" id="barTerminsNeedSelectInputTel">


    <div class="d-flex justify-content-between mt-3" style="background-color:rgb(39,190,175); border-radius:15px;" >
        <div style="width:48%;" class="text-right">
            <p style="font-weight:bold; font-size:18px; color:white;" id="selServiceShowNameTel"></p>
            <p style="font-weight:bold; font-size:15px; color:white; margin-top:-10px;" id="selServiceShowDescTel"></p>
            <p style="font-weight:bold; font-size:15px; color:white; margin-top:-10px;" id="selServiceShowTypeTel"></p>
        </div>
        <div style="width:48%;" class="text-left">
            <p style="font-weight:bold; font-size:18px; color:white;" id="selServiceShowPriceTel"></p>
            <p style="font-weight:bold; font-size:15px; color:white; margin-top:-10px;" id="selServiceShowTimeNeedTel"></p>
            <p style="font-weight:bold; font-size:15px; color:white; margin-top:-10px;" id="selServiceShowXTimesTel"></p>
        </div>
    </div>

    <div class="mt-3 p-2 alert alert-danger" style="display:none;" id="notEnoughTimeTerminTel">
        <h3><strong>{{__('adminP.noEnoughTime')}}</strong></h3>
    </div>
    <div class="mt-3 p-2 d-flex flex-wrap justify-content-between" style="display:none !important;" id="enoughTimeTerminTel">
        <div style="width:49.5%;" class="form-group">
            <label for="usr"><strong>{{__('adminP.name')}}:</strong></label>
            <input id="barNameSelectInputTel" type="text" class="form-control">
        </div>
        <div style="width:49.5%;" class="form-group">
            <label for="usr"><strong>{{__('adminP.lastName')}}:</strong></label>
            <input id="barLastnameSelectInputTel" type="text" class="form-control">
        </div>
        <div style="width:100%;" class="form-group">
            <label for="usr"><strong>{{__('adminP.email')}}:</strong></label>
            <input id="barEmailSelectInputTel" type="text" class="form-control">
        </div>
        <div style="width:100%;" class="form-group">
            <label for="usr"><strong>{{__('adminP.phoneNumber')}}:</strong></label>
            <input id="barNrTelSelectInputTel" type="number" class="form-control">
        </div>
        <button style="width:100%;" class="btn btnQrorpaSelectedTel" onclick="saveReservationTel('{{$barbershopId}}')"><strong>{{__('adminP.saveTheReservation')}}</strong></button>
    </div>

    <div class="mt-3 p-2 text-center alert alert-danger " style="display:none; font-weight:bold; font-size:23px;" id="saveRezErrorTel"></div>



    @foreach(BarbershopCategory::where('toBar',$barbershopId)->get() as $barCategories)
        <div class="d-flex flex-wrap justify-content-start mt-5 allBarCatServicesTel" style="display:none !important;"  id="barCatServicesTel{{$barCategories->id}}">
            @foreach(BarbershopService::where('kategoria',$barCategories->id)->get() as $barService)
                @if($barService->type == '' )
                <div onclick="selectTheServiceTel('{{$barCategories->id}}','{{$barService->id}}','{{$barService->timeNeed}}','0','','','{{$barService->emri}}','{{$barService->pershkrimi}}','{{$barService->qmimi}}')" 
                    style="width:49.5%; margin-right:0.5%;" 
                    class="d-flex flex-wrap justify-content-between p-2 allDivServices00 shadow mb-2" id="barService{{$barService->id}}">
                @else
                <div style="width:49.5%; margin-right:0.5%;" class="d-flex flex-wrap justify-content-between p-2 allDivServices shadow mb-2">
                @endif
                    <div style="width:100%;">
                        <p style="color:rgb(72,81,87); font-size:16px;"><strong>{{$barService->emri}}</strong></p>
                        <p style="color:rgb(72,81,87); margin-top:-15px;">{{$barService->pershkrimi}}</p>
                    </div>
                    <div style="width:100%;" class="text-center">
                        <p style="color:rgb(72,81,87); font-size:18px;"><strong>{{$barService->qmimi}} <sup>{{__('adminP.currencyShow')}}</sup></strong></p>
                    </div>
                    <div style="width:100%;" class="d-flex flex-wrap justify-content-start">
                   
                        @foreach(explode('--0--',$barService->type) as $bSerType)
                            @if($bSerType != '')
                                <?php $bSerType2D = explode('||',$bSerType) ;?>
                                @if($bSerType2D != '')
                                    <button style="width:100%; " class="btn mb-1 allDivServices00"
                                    onclick="selectTheServiceTel('{{$barCategories->id}}','{{$barService->id}}','{{$barService->timeNeed}}','{{$bSerType2D[0]}}','{{$bSerType2D[1]}}','{{$bSerType2D[2]}}',
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
    function resetFormTel(){
        $("#allBarRezAdmSetTel").load(location.href+" #allBarRezAdmSetTel>*","");
    }

    function selectBarCatTel(barCatSelId){
        $('#barCategoryBtnTel'+barCatSelId).removeClass('btnQrorpaTel');
        $('#barCategoryBtnTel'+barCatSelId).addClass('btnQrorpaSelectedTel');
        $('.allBarCategoryTel').attr('disabled','true');

        $('#barCatSelectInputTel').val(barCatSelId);

        $('#barCatServicesTel'+barCatSelId).show(400);

        $('#dateSelTel').show(250);
    }

    function fetchWorkersTel(dateSel, bId){
        $('#barDateSelectInputTel').val(dateSel);

        $.ajax({
			url: '{{ route("barAdmin.setRezFetchWorkers") }}',
			method: 'post',
            dataType: 'json',
			data: {dateSelected: dateSel, barId: bId, catSel: $('#barCatSelectInputTel').val(), _token: '{{csrf_token()}}'},
			success: (res) => {
                if(Object.keys(res).length == 0){
                    $('#workersTel').html('<p style="font-weight:bold; font-size:22px; color:red;">' +$('#noStaffAvailableThisDate').val()+ '</p>');
                }else{
                    $('#dateSelTel').attr('disabled','true');
                    $('#workersTel').html('');
                    var listings = "";
                    $.each(res, function(index, value){
                        listings = '<button class="btn btnQrorpaTel allBtnBarWorkersTel text-center mb-1" id="btnBarWorkersTel'+value.id+'" style="width:49.5%; margin-right:0.5%"'+
                                'onclick="selWorkerGetTerTel(\''+value.id+'\',\''+dateSel+'\')">'+
                                ''+value.emri+''+
                                '</button>';
                        $('#workersTel').append(listings); 
                    });
                }
			},
			error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val());}
		});
    }


    function selectTheServiceTel(serCatId, serId, serTimeNeed, typeId, typeEmri, typeXTimes, serEmri, serPersh, serPrice){
      
      if(typeId == 0){
          $('#barServiceSelectInputTel').val(serId);
          $('#barTypeSelectInputTel').val(typeId);
          $('#barTimeNeedSelectInputTel').val(serTimeNeed);

          $('#barCatServicesTel'+serCatId).html('');

          $('#selServiceShowNameTel').html(serEmri);
          $('#selServiceShowDescTel').html(serPersh);

          $('#selServiceShowPriceTel').html(serPrice+ '' +$('#currencyShow').val());
          $('#selServiceShowTimeNeedTel').html(serTimeNeed+'(' +$('#minutes').val()+ ')');

          $('#barShumaFinalSelectInputTel').val(serPrice);
      }else{
          $('#barServiceSelectInputTel').val(serId);
          $('#barTypeSelectInputTel').val(typeId);
          $('#barTimeNeedSelectInputTel').val(serTimeNeed);

          $('#barCatServicesTel'+serCatId).html('');

          $('#selServiceShowNameTel').html(serEmri);
          $('#selServiceShowDescTel').html(serPersh);
          $('#selServiceShowTypeTel').html('"'+typeEmri+'"');

          var finalPrice = parseFloat(serPrice * typeXTimes).toFixed(2);
          $('#selServiceShowPriceTel').html(finalPrice+''+$('#currencyShow').val());
          $('#selServiceShowTimeNeedTel').html(serTimeNeed+'(' +$('#minutes').val()+')');
          $('#selServiceShowXTimesTel').html(typeXTimes+ ''+$("#xOfOriginalPrice").val()+ '' +serPrice+')');

          $('#barShumaFinalSelectInputTel').val(finalPrice);
      }
  }




  
  function selWorkerGetTerTel(workerID, dateSel){
        if($('#barServiceSelectInputTel').val() == 0){
            $('#selectWorkerErrorTel01').attr('style','display:block;');
            setTimeout(function () {
                $('#selectWorkerErrorTel01').attr('style','display:none;');
            }, 2500);
        }else{
            $('#barWorkerSelectInputTel').val(workerID);
            $('.allBtnBarWorkersTel').attr('class','btn btnQrorpaTel allBtnBarWorkersTel text-center');
            // $('#btnBarWorkers'+workerID).removeClass('btnQrorpa');
            $('#btnBarWorkersTel'+workerID).addClass('btnQrorpaSelectedTel');

            $('#workerTerminsTel').html('<p style="color:red; font-size:25px;" class="pt-3 text-center"><strong>'+$("#loading").val()+ '...</strong></p>');
            $.ajax({
                url: '{{ route("barAdmin.setRezFetchWorkerTers") }}',
                method: 'post',
                dataType: 'json',
                data: {dateSelected: dateSel, workerId: workerID, serviceId: $('#barServiceSelectInputTel').val(), _token: '{{csrf_token()}}'},
                success: (res) => {
                    $('#workerTerminsTel').html('');
                    var listings = "";
                
                    $.each(res, function(index, value){
                        if(value.validity == 0){
                            listings = '<button class="btn btnQrorpaTel allBtnBarWorkerTerminsTel text-center mb-1" id="btnBarWorkerTerminsTel'+value.id+'" style="width:24.5%; margin-right:0.5%"'+
                                    'onclick="selectTheTerminTel(\''+value.id+'\')">'+
                                    ''+value.startT+''+
                                    '</button>'+
                                    '<input type="hidden" id="terminStateTel'+value.id+'" value="0"> ';
                        }else if(value.validity == 1){
                            listings = '<button class="btn btnQrorpaTel allBtnBarWorkerTermins1Tel text-center mb-1" id="btnBarWorkerTerminsTel'+value.id+'" style="width:24.5%; margin-right:0.5%" disabled>'+
                                    ''+value.startT+''+
                                    '</button>'+
                                    '<input type="hidden" id="terminStateTel'+value.id+'" value="1"> ';
                        }else if(value.validity == 2){
                            listings = '<button class="btn btnQrorpaRedTel allBtnBarWorkerTermins2Tel text-center mb-1" id="btnBarWorkerTerminsTel'+value.id+'" style="width:24.5%; margin-right:0.5%" disabled>'+
                                    ''+value.startT+''+
                                    '</button>'+
                                    '<input type="hidden" id="terminStateTel'+value.id+'" value="2"> ';
                        }
                        $('#workerTerminsTel').append(listings); 
                    });
                },
                error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val());}
            });
        }
    }



    function selectTheTerminTel(wTerId){
        var terminesTake = Math.ceil(($('#barTimeNeedSelectInputTel').val() / 15));
        // alert(terminesTake);
        var step = 0;
        var checkThis = 0;
        var notEnoughTime = false;
        while(step++ < (terminesTake-1)){
            checkThis = parseInt(step) + parseInt(wTerId);
            if($('#terminStateTel'+checkThis).val() == 2){
                notEnoughTime = true;
            }
        }

        if(notEnoughTime){
            $('#notEnoughTimeTerminTel').show(200).delay(4000).hide(200);
        }else{
            $('.allBtnBarWorkerTerminsTel').attr('class','btn btnQrorpaTel allBtnBarWorkerTerminsTel text-center mb-1');
            $('#btnBarWorkerTerminsTel'+wTerId).attr('class','btn btnQrorpaSelectedTel allBtnBarWorkerTerminsTel text-center mb-1');

            $('#enoughTimeTerminTel').show(200);

            $('#barTerminStartSelectInputTel').val(wTerId);
            $('#barTerminsNeedSelectInputTel').val(terminesTake);

            $([document.documentElement, document.body]).animate({
                scrollTop: $("#enoughTimeTerminTel").offset().top
            }, 2000);
        }
    }

    function saveReservationTel(barID){
        var Cat = $('#barCatSelectInputTel').val();
        var DateSel = $('#barDateSelectInputTel').val();
        var Worker = $('#barWorkerSelectInputTel').val();
        var Service = $('#barServiceSelectInputTel').val();
        var Type = $('#barTypeSelectInputTel').val();
        var ShumaFinal = $('#barShumaFinalSelectInputTel').val();
        var TimeNeed = $('#barTimeNeedSelectInputTel').val();
        var TerminStart = $('#barTerminStartSelectInputTel').val();
        var TerminsNeed = $('#barTerminsNeedSelectInputTel').val();

        var Name = $('#barNameSelectInputTel').val();
        var Lastname = $('#barLastnameSelectInputTel').val();
        var Email = $('#barEmailSelectInputTel').val();
        var NrTel = $('#barNrTelSelectInputTel').val();
        
        if(Name == '' || Lastname == '' || Email == '' || Email == ''){
            $('#saveRezErrorTel').html($('#plsProvideCustomersData').val());
            $('#saveRezErrorTel').show('200').delay(3500).hide(200);
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
                    $("#allBarRezAdmSetTel").load(location.href+" #allBarRezAdmSetTel>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#pleaseUpdateAndTryAgain').val());
                }
            });
        }
    }
</script>