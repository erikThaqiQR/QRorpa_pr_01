<?php

use App\BarbershopService;
use App\BarbershoServiceRecomendet;

    $thisBarId = Auth::user()->sFor;
?>

<style>
    .newRecomendetServiceDiv:hover{
        cursor: pointer;
        background-color: rgb(0,0,0,0.025);
    }

    .btn-qrorpa{
        font-weight: bold;
        border: 1px solid rgb(39,190,175);
        border-radius: 10px;
        color: rgb(39,190,175);
        background-color: white;
    }
    .btn-qrorpa:hover{
        font-weight: bold;
        border: 1px solid rgb(39,190,175);
        border-radius: 10px;
        color: white;
        background-color: rgb(39,190,175);
    }

    .select2-container .select2-selection--single{
        height:34px !important;
    
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important; 
        border-radius: 0px !important; 
    }
</style>

<section class="pl-3 pr-3 pt-2 pb-5">
    <h3 class="color-qrorpa "><strong>{{__('adminP.recommendedServices')}}</strong></h3>
    <hr>


<div id="allBarRecServices" style="width:80%; margin-left:10%;">
    @if(BarbershoServiceRecomendet::where('toBar', $thisBarId)->get()->count() > 0)
        @foreach(BarbershoServiceRecomendet::where('toBar', $thisBarId)->get()->sortBy('position') as $barRecSer)
            <?php $barSer = BarbershopService::find($barRecSer->serviceid); ?>

            <div class="p-2 mb-2 d-flex justify-content-between shadow" style="border:1px solid rgb(72,81,87,0.5); border-radius:20px;">
                <div style="width:10%;">
                    @if($barRecSer->position != 1)
                        <button onclick="recSerUpOne('{{$barRecSer->id}}','{{$thisBarId}}')" style="width:100%; height:46px; color:rgb(39,190,175);" class="btn">
                            <i class="fas fa-2x fa-arrow-alt-circle-up"></i>
                        </button>
                    @else
                        <button style="width:100%; height:46px" class="btn"></button>
                    @endif
                        <p  style="width:100%; height:46px; font-size:21px; color:rgb(72,81,87);" class="text-center pt-2"><strong># {{$barRecSer->position}}</strong></p>
                    @if($barRecSer->position <  BarbershoServiceRecomendet::where('toBar', $thisBarId)->get()->count())
                        <button onclick="recSerDownOne('{{$barRecSer->id}}','{{$thisBarId}}')" style="width:100%; height:46px; color:rgb(39,190,175);" class="btn"><i class="fas fa-2x fa-arrow-alt-circle-down"></i></button>
                    @else
                        <button style="width:100%; height:46px; color:rgb(39,190,175);" class="btn"></button>
                    @endif
                </div>
                <img style="width:140px; height:140px; border-radius:50%;" class="sideIconAP" src="storage/recomendetServices/{{$barRecSer->servicePic}}" alt="">
                <div style="width:40%;" class="ml-2">
                    <p style="color:rgb(72,81,87);"><strong>{{$barSer->emri}}</strong></p>
                    <p style="color:rgb(72,81,87); margin-top:-10px;">{{$barSer->pershkrimi}}</p>
                    <p style="color:rgb(72,81,87);">{{$barRecSer->newPrice}} <sup>{{__('adminP.currencyShow')}}</sup> 
                        @if($barRecSer->newPriceSt != 0)
                            ( Studentenpreis {{$barRecSer->newPriceSt}} <sup>{{__('adminP.currencyShow')}}</sup>  )
                        @endif
                    </p>
                </div>
                <div style="width:10%;">
                    <button style="width:100%;" class="btn btn-outline-info" data-toggle="modal" data-target="#editSerRec{{$barRecSer->id}}"><i class="fas fa-pen"></i></button>
                    <button style="width:100%;" class="btn btn-outline-danger mt-3" onclick="deleteRecSer('{{$barRecSer->id}}')"><i class="far fa-trash-alt"></i></button>
                </div>
               
         
            </div>
        @endforeach
    @else
        <h3 class="text-center p-3"><strong>{{__('adminP.noRecServicesRegisteredYet')}}</strong></h3>
    @endif
</div>


    <script>
        function deleteRecSer(recSerID){
            $.ajax({
                url: '{{ route("barService.recomendetDelete") }}',
                method: 'post',
                data: {
                    id: recSerID,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#allBarRecServices").load(location.href+" #allBarRecServices>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#pleaseUpdateAndTryAgain').val());
                }
            });
        }


        function recSerUpOne(recSerID, bID){
            $.ajax({
                url: '{{ route("barService.recomenderUpOne") }}',
                method: 'post',
                data: {
                    id: recSerID,
                    barID: bID,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#allBarRecServices").load(location.href+" #allBarRecServices>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#pleaseUpdateAndTryAgain').val());
                }
            });
        }
        function recSerDownOne(recSerID, bID){
            $.ajax({
                url: '{{ route("barService.recomenderDownOne") }}',
                method: 'post',
                data: {
                    id: recSerID,
                    barID: bID,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#allBarRecServices").load(location.href+" #allBarRecServices>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#pleaseUpdateAndTryAgain').val());
                }
            });
        }
    </script>



















        <!-- Barbershop Recomendet Service Edit Modal -->
        @foreach(BarbershoServiceRecomendet::where('toBar',$thisBarId)->get() as $barRecServices)

            <div class="modal" id="editSerRec{{$barRecServices->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">

                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header" style="background-color:rgb(39,190,175);">
                            <h4 style="color:white;" class="modal-title"><strong>{{__('adminP.editRecommendedServices')}}</strong></h4>
                            <button style="color:white;" type="button" class="close" data-dismiss="modal" >X</button>
                        </div>
                        <?php $barSerForEdit = BarbershopService::find($barRecSer->serviceid); ?>
                        <!-- Modal body -->
                        <div class="modal-body">
                            {{Form::open(['action' => 'BarbershopServiceController@recomenderSerUpdate', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                                <div class="d-flex flex-wrap justify-content-between">
                                    <hr style="width:100%;">

                                    <div class="custom-file" style="width:100%;">
                                        {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                                        {{ Form::file('foto', ['class' => 'custom-file-input']) }}
                                    </div>

                                    <div class="form-group d-flex mt-2" style="width:49%;">
                                        {{ Form::label(__('adminP.priceCHF'), null , ['class' => 'control-label pt-2 pr-2 text-right' , 'style' => 'width:40%; font-weight:bold;']) }}
                                        {{ Form::number('qmimi', $barRecServices->newPrice ,['class' => 'form-control', 'step'=>'0.01', 'min' => '0' , 'style' => 'width:60%;', 'required']) }}
                                    </div>
                                    <div class="form-group d-flex mt-2" style="width:49%;">
                                        {{ Form::label(__('adminP.studentPrice'), null , ['class' => 'control-label pt-2 pr-2 text-right' , 'style' => 'width:40%; font-weight:bold;']) }}
                                        {{ Form::number('qmimi2', $barRecServices->newPriceSt ,['class' => 'form-control', 'step'=>'0.01', 'min' => '0' , 'style' => 'width:60%;', 'required']) }}
                                    </div>

                                    <div class="pt-2" style="width:69%;">
                                        <p style="color:rgb(72,81,87); font-weight:bold;">{{$barSerForEdit->emri}}</p>
                                        <p style="color:rgb(72,81,87); font-weight:bold; margin-top:-10px;">{{$barSerForEdit->pershkrimi}}</p>
                                    </div>

                                    <div class="form-group" style="width:29%;">
                                        {{ Form::label(__('adminP.positions'), null , ['class' => 'control-label']) }}
                                        {{ Form::number('pozita', $barRecServices->position, ['class' => 'form-control' ,'step'=>'1', 'max' => '10' , 'min' => '1', 'required']) }}
                                    </div>

                                    <input type="hidden" name="barId" value="{{$thisBarId}}">
                                    <input type="hidden" name="barSerRecId" value="{{$barRecServices->id}}">

                                    <div class="form-group" style="width:100%;">
                                        {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'form-control btn btn-qrorpa' , 'style' => 'width:100%;']) }}
                                    </div>
                                </div>
                            {{Form::close() }}
                        </div>
                    </div>
                </div>  
            </div>
        @endforeach
























































    @if(BarbershoServiceRecomendet::where('toBar', $thisBarId)->get()->count() < 10)
        <div class="p-2 d-flex justify-content-between newRecomendetServiceDiv shadow" style="border:1px solid rgb(72,81,87,0.5); border-radius:20px; width:80%; margin-left:10%;" 
            data-toggle="modal" data-target="#newRecomendetService">
    
            <img style="width:25%;" class="sideIconAP" src="storage/images/logo_QRorpa.png" alt="">
            <h4 style="width:75%; padding-top:32px; " class="color-qrorpa text-center"><strong>
                {{__('adminP.addRecServicesHair10')}}
            </strong></h4>
        </div>

        <!-- The Modal -->
        <div class="modal" id="newRecomendetService" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color:rgb(39,190,175);">
                        <h4 style="color:white;" class="modal-title"><strong>{{__('adminP.newRecService')}}</strong></h4>
                        <button style="color:white;" type="button" class="close" data-dismiss="modal" onclick="resetNewRecSerModal()">X</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        {{Form::open(['action' => 'BarbershopServiceController@recomenderSerStore', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}

                            <div class="form-group d-flex">
                                <label for="kat" style="width:30%;">{{__('adminP.chooseProduct')}}</label>
                                <select name="sherbimi" class="form-control select2" style="width:70%;" onchange="selectNewService(this.value)">
                                    <option class="p-2 " value="0">{{__('adminP.choose')}}...</option>
                                    <?php
                                        foreach(BarbershopService::where('toBar', '=', $thisBarId)->get() as $service){
                                            echo '  <option value="'.$service->id.'"> '.$service->emri.'<strong> >> </strong>'.$service->pershkrimi.'  </option> ';
                                        }
                                    ?>      
                                </select>
                            </div>
                            

                            <div style="display:none !important;" id="setNewRecService" class="d-flex flex-wrap justify-content-between">
                                <hr style="width:100%;">

                                <div class="custom-file" style="width:100%;">
                                    {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                                    {{ Form::file('foto', ['class' => 'custom-file-input', 'required']) }}
                                </div>
                                <div class="form-group d-flex mt-2" style="width:49%;">
                                    {{ Form::label(__('adminP.priceCHF'), null , ['class' => 'control-label pt-2 pr-2 text-right' , 'style' => 'width:40%; font-weight:bold;']) }}
                                    {{ Form::number('qmimi', 0 ,['class' => 'form-control', 'id' => 'newRecQmimi', 'step'=>'0.01', 'min' => '0' , 'style' => 'width:60%;', 'required']) }}
                                </div>
                                <div class="form-group d-flex mt-2" style="width:49%;">
                                    {{ Form::label(__('adminP.studentPrice'), null , ['class' => 'control-label pt-2 pr-2 text-right' , 'style' => 'width:40%; font-weight:bold;']) }}
                                    {{ Form::number('qmimi2', 0 ,['class' => 'form-control', 'id' => 'newRecQmimi2', 'step'=>'0.01', 'min' => '0' , 'style' => 'width:60%;']) }}
                                </div>
                                <div class="pt-2" style="width:69%;">
                                    <p style="color:rgb(72,81,87); font-weight:bold;" id="newRecSerEmri"></p>
                                    <p style="color:rgb(72,81,87); font-weight:bold; margin-top:-10px;" id="newRecSerPershkrimi"></p>
                                </div>

                                <div class="form-group" style="width:29%;">
                                    {{ Form::label(__('adminP.positions'), null , ['class' => 'control-label']) }}
                                    {{ Form::number('pozita',(BarbershoServiceRecomendet::where('toBar', $thisBarId)->get()->count())+1,
                                    ['class' => 'form-control' ,'step'=>'1', 'max' => '10' , 'min' => '1', 'required']) }}
                                </div>

                                <input type="hidden" name="barId" value="{{$thisBarId}}">

                                <div class="form-group" style="width:100%;">
                                    {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'form-control btn btn-qrorpa' , 'style' => 'width:100%;']) }}
                                </div>
                            </div>
                        {{Form::close() }}
                    </div>

                </div>
            </div>
        </div>
    @endif




























    <script>
        function selectNewService(id){
            if(id == 0){
                $('#setNewRecService').hide('300');
            }else{
                $('#setNewRecService').show('300');
                $.ajax({
                    url: '{{ route("barService.recomenderGetSerPrice") }}',
                    method: 'post',
                    data: {id: id, _token: '{{csrf_token()}}' },
                    success: (res) => { 
                        $('#newRecQmimi').val(parseFloat(res.qmimi).toFixed(2)); 
                        $('#newRecQmimi2').val(parseFloat(res.qmimiSt).toFixed(2)); 
                        $('#newRecSerEmri').html(res.emri);
                        $('#newRecSerPershkrimi').html(res.pershkrimi);
                    },
                    error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val());}
                }); 
            }
        }

        function resetNewRecSerModal(){
            $("#newRecomendetService").load(location.href+" #newRecomendetService>*","");
        }

        $('.select2').select2();

        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>






</section>