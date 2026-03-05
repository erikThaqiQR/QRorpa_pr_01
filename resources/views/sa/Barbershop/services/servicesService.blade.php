<?php   
    use App\Barbershop;
    use App\BarbershopCategory;
    use App\BarbershopType;
    use App\BarbershopExtra;
    use App\BarbershopService;

    $barbershopId = $_GET['barbershop'];
?>
<style>
    .addTypeBtn{
        color:rgb(39, 190, 175);
        border:1px solid rgb(39, 190, 175);
        font-weight: bold;
        font-size: 17px;
    }
    .addTypeBtn:hover{
        background-color:rgb(39, 190, 175);
        color: white;
        border:1px solid rgb(39, 190, 175);
        cursor: pointer;
    }

    .catTypeBlock{
        border:1px solid lightgray;
    }


    .checkTypeExt:hover{
        cursor: pointer;
    }
</style>






<!-- Add Service Modal -->
<div class="modal" id="addService" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> Add a new Service </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

    
            {{Form::open(['action' => 'BarbershopServiceController@store', 'method' => 'post']) }}

                {{csrf_field()}}
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <div style="width:32%;" class="form-group">
                            {{ Form::label('Name', null, ['class' => 'control-label color-black']) }}
                            {{ Form::text('emri','', ['class' => 'form-control ', 'required']) }}
                        </div>
                        <div style="width:32%;" class="form-group">
                            {{ Form::label('Preis', null, ['class' => 'control-label color-black']) }}
                            {{ Form::number('qmimi',0, ['class' => 'form-control ', 'min' => '0', 'step'=> '0.01', 'required']) }}
                        </div>
                        <div style="width:32%;" class="form-group">
                            {{ Form::label('Preis (Student)', null, ['class' => 'control-label color-black']) }}
                            {{ Form::number('qmimi2',0, ['class' => 'form-control ', 'min' => '0', 'step'=> '0.01']) }}
                        </div>
                        <div style="width:100%;" class="form-group mt-2">
                            <label for="sel1">Select a category</label>
                                <select name="toCat" class="form-control" onchange="showCatEXTTYPE(this.value)" required>
                                    @foreach(BarbershopCategory::where('toBar', $barbershopId)->get()->sortByDesc('created_at') as $bCat){
                                        <option value="{{$bCat->id}}">{{$bCat->emri}}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <div  class="d-flex justify-content-between">
                        <div style="width:80%;" class="form-group">
                            {{ Form::label('Description', null, ['class' => 'control-label color-black']) }}
                            {{ Form::textarea('pershkirmi','', ['class' => 'form-control ', 'rows' => '1', 'required']) }}
                        </div>
                        <div style="width:19%;" class="form-group">
                            {{ Form::label('Zeit (min)', null, ['class' => 'control-label color-black']) }}
                            {{ Form::number('koha','', ['class' => 'form-control ', 'min' => '0', 'step'=> '1', 'required']) }}
                        </div>
                    </div>

                    @foreach(BarbershopCategory::where('toBar',$barbershopId)->get() as $bCat)
                    <div style="display:none !important; border-top:1px solid lightgray; border-bottom:1px solid lightgray;" class="p-3 d-flex justify-content-between allKatExt"
                     id="addServiceKat{{$bCat->id}}">
                        <div style="width:49%;">
                            <h3 class="color-qrorpa">Type</h3>
                            @foreach(BarbershopType::where([['toBar',$barbershopId],['kategoria',$bCat->id]])->get() as $theseType)
                                <div class="form-check p-1">
                                    <label class="form-check-label checkTypeExt">
                                        <input onclick="addTypeTo(this.id ,'{{$theseType->id}}','{{$theseType->emri}}','{{$theseType->vlera}}')"  
                                        style="width:40px;" type="checkbox" class="form-check-input" id="checkType{{$theseType->id}}"><span class="pl-4">{{$theseType->emri}}</span> 
                                    </label>
                                </div>
                               
                            @endforeach
                        </div>
                        <div style="width:49%;">
                        <h3 class="color-qrorpa">Extra</h3>
                            @foreach(BarbershopExtra::where([['toBar',$barbershopId],['kategoria',$bCat->id]])->get() as $theseExtra)

                           
                                <div class="form-check p-1">
                                    <label class="form-check-label checkTypeExt">
                                        <input onclick="addExtraTo(this.id ,'{{$theseExtra->id}}','{{$theseExtra->emri}}','{{$theseExtra->qmimi}}')" 
                                        style="width:40px;" type="checkbox" class="form-check-input " id="checkExtra{{$theseExtra->id}}"><span class="pl-4">{{$theseExtra->emri}} /  {{$theseExtra->qmimi}} <sup>CHF</sup></span> 
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                  






                </div>

                {{ Form::hidden('barbershop', $barbershopId , ['class' => 'form-control ']) }}

                {{ Form::hidden('extra', '' , ['class' => 'form-control ','id' => 'sendExtra']) }}
                {{ Form::hidden('type', '' , ['class' => 'form-control ','id' => 'sendType']) }}

                <!-- Modal footer -->
                <div class="modal-footer">
                    {{ Form::submit('Save', ['class' => 'form-control btn btn-dark']) }}
                    
                </div>
            {{Form::close() }}

    </div>
  </div>
</div>




<script>
    function showCatEXTTYPE(catSel){
        $('.allKatExt').attr('style','display:none !important; border-top:1px solid lightgray; border-bottom:1px solid lightgray;');
        $('#addServiceKat'+catSel).show();
        $('#sendExtra').val('');
        $('#sendType').val('');
    }

    function addTypeTo(chId ,tId, tEmri, tVlera){
        if($('#'+chId).prop('checked')) { 
            if($('#sendType').val() == ''){
                $('#sendType').val(tId+'||'+tEmri+'||'+tVlera);
            }else{
                $('#sendType').val($('#sendType').val()+'--0--'+tId+'||'+tEmri+'||'+tVlera);
            }
        } else {
            var newVal = $('#sendType').val().replace(tId+'||'+tEmri+'||'+tVlera, "");
            $('#sendType').val(newVal);
        }
    }

    function addExtraTo(chId ,eId, eEmri, eQmimi){
        if($('#'+chId).prop('checked')) { 
            if($('#sendExtra').val() == ''){
                $('#sendExtra').val(eId+'||'+eEmri+'||'+eQmimi);
            }else{
                $('#sendExtra').val($('#sendExtra').val()+'--0--'+eId+'||'+eEmri+'||'+eQmimi);
            }
        } else {
            var newVal = $('#sendExtra').val().replace(eId+'||'+eEmri+'||'+eQmimi, "");
            $('#sendExtra').val(newVal);
        }
    }
</script>






































<section class="pl-4 pr-4 pt-4 pb-5">
    <div class="d-flex justify-content-between">
        <a style="width:10%;" href="{{route('barbershops.servicesSelBar', ['barbershop' => $barbershopId])}}">
            <img class="pt-3" src="https://img.icons8.com/android/48/000000/back.png"/>
        </a>
        <h3 style="width:45%;" class="color-qrorpa pt-4"><strong>{{Barbershop::find($barbershopId)->emri}}</strong></h3>
        <h3 style="width:45%;" class="text-right color-qrorpa pr-4 pt-4"><strong>Service</strong></h3>
    </div>

    <button class="btn btn-block addTypeBtn mt-3 mb-3" data-toggle="modal" data-target="#addService"> Add a new Service </button>

    <hr>


    <div class="d-flex justify-content-between flex-wrap">
        @foreach(BarbershopService::where('toBar',$barbershopId)->get()->sortByDesc('created_at') as $bSer)
            <div style="width:100%; border:1px solid lightgray; border-radius:15px;" class="shadow mb-2 p-2 d-flex">
                <div style="width:39%; color:rgb(72,81,87);">
                    <span>
                        <i data-toggle="modal" data-target="#editSer{{$bSer->id}}" style="color:rgb(72,81,87);" class="pr-3 far fa-2x fa-edit btn"></i> 
                        <strong style="font-size:25px;">{{$bSer->emri}} ( {{$bSer->timeNeed}} min )</strong>
                    </span>
                    <p style="margin-top:-5px;">{{$bSer->pershkrimi}}</p>
                    <h4 style="margin-top:-10px; ">{{$bSer->qmimi}} <sup>CHF</sup> ( {{$bSer->qmimiSt}} <sup>CHF</sup> für Studierende)</h4>
                </div>
                <div style="width:30%;">
                    <?php $typeCount = 1; ?>
                    @foreach(explode('--0--',$bSer->type) as $NowType)
                        @if($NowType != '')
                            <?php $typeVar = explode('||',$NowType); ?>
                            @if($typeCount++ > 1)
                                <p style="margin-top:-15px;">#{{$typeVar[0]}} / {{$typeVar[1]}}  / {{$typeVar[2]}} X </p>
                            @else
                                <p>#{{$typeVar[0]}} / {{$typeVar[1]}}  / {{$typeVar[2]}} X </p>
                            @endif
                        @endif
                    @endforeach
                </div>
                <div style="width:30%;">
                    <?php $extraCount = 1; ?>
                    @foreach(explode('--0--',$bSer->extra) as $NowExtra)
                        @if($NowExtra != '')
                            <?php $extraVar = explode('||',$NowExtra); ?>
                            @if($extraCount++ > 1)
                                <p style="margin-top:-15px;">#{{$extraVar[0]}} / {{$extraVar[1]}}  / {{$extraVar[2]}} X </p>
                            @else
                                <p>#{{$extraVar[0]}} / {{$extraVar[1]}}  / {{$extraVar[2]}} X </p>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>







            <!-- The Modal -->
            <div class="modal" id="editSer{{$bSer->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">

                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color:rgb(39, 190, 175);">
                        <h4 style="color:white;" class="modal-title"><strong>{{$bSer->emri}}</strong></h4>
                        <button style="color:white; opacity:1;" type="button" class="close" data-dismiss="modal"><strong>X</strong></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        {{Form::open(['action' => 'BarbershopServiceController@edit', 'method' => 'post']) }}

                            {{csrf_field()}}
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div style="width:32%;" class="form-group">
                                        {{ Form::label('Name', null, ['class' => 'control-label color-black']) }}
                                        {{ Form::text('emri', $bSer->emri , ['class' => 'form-control ', 'required']) }}
                                    </div>
                                    <div style="width:32%;" class="form-group">
                                        {{ Form::label('Preis', null, ['class' => 'control-label color-black']) }}
                                        {{ Form::number('qmimi', $bSer->qmimi , ['class' => 'form-control ', 'min' => '0', 'step'=> '0.01', 'required']) }}
                                    </div>
                                    <div style="width:32%;" class="form-group">
                                        {{ Form::label('Preis (Student)', null, ['class' => 'control-label color-black']) }}
                                        {{ Form::number('qmimi2', $bSer->qmimiSt , ['class' => 'form-control ', 'min' => '0', 'step'=> '0.01']) }}
                                    </div>
                                </div>
                                <div  class="d-flex justify-content-between">
                                    <div style="width:80%;" class="form-group">
                                        {{ Form::label('Description', null, ['class' => 'control-label color-black']) }}
                                        {{ Form::textarea('pershkirmi', $bSer->pershkrimi , ['class' => 'form-control ', 'rows' => '1', 'required']) }}
                                    </div>
                                    <div style="width:19%;" class="form-group">
                                        {{ Form::label('Zeit (min)', null, ['class' => 'control-label color-black']) }}
                                        {{ Form::number('koha', $bSer->timeNeed , ['class' => 'form-control ', 'min' => '0', 'step'=> '1', 'required']) }}
                                    </div>
                                </div>
                            </div>
                        {{ Form::hidden('barSerId', $bSer->id , ['class' => 'form-control ']) }}

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            {{ Form::submit('Save', ['class' => 'form-control btn btn-dark']) }}
                        </div>
                        {{Form::close() }}
                    </div>


                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>




























<script>
    $( document ).ready(function() {
        $('.addServiceKatAll').hide();
    });
   
</script>