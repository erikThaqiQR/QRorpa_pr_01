<?php
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
?>
<script>
    var last="";
</script>






<style>
    /* The switch - the box around the slider */
    .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    float:right;
    }

    /* Hide default HTML checkbox */
    .switch input {display:none;}

    /* The slider */
    .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    }

    .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    input.default:checked + .slider {
    background-color: #444;
    }
    input.primary:checked + .slider {
    background-color: #2196F3;
    }
    input.success:checked + .slider {
    background-color: #8bc34a;
    }
    input.info:checked + .slider {
    background-color: #3de0f5;
    }
    input.warning:checked + .slider {
    background-color: #FFC107;
    }
    input.danger:checked + .slider {
    background-color: #f44336;
    }

    input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
    border-radius: 34px;
    }

    .slider.round:before {
    border-radius: 50%;
    }
</style>


















<div class="container p-2" id="ProductStart">
    <div class="row mt-5 mb-2">
        <div class="col-1 backBtn" onclick="back()">
              <img src="https://img.icons8.com/android/48/000000/back.png"/>
        </div>
        <div class="col-3 text-left">
            <p class="color-qrorpa mt-2" style="font-size:20px;"><strong>Products</strong></p>
        </div>

        <div class="col-8 text-right">
            <p class="color-qrorpa mt-2 mr-4" style="font-size:17px;"><strong>Select a restaurant to see the Products!</strong></p>
        </div>
    </div>

    <div class="row mt-5 ml-3">
        @foreach($restaurant as $res)
            <?php
            echo '<div class="col-2 text-center p-3 ResShow ml-4 mt-3" onclick="openResProd('.$res->id.')">';
            ?>
                {{$res->emri}}
            </div>
        @endforeach
    </div>
</div>










































<!-- ADD PRODUCTS MODAL -->
@foreach($restaurant as $res)


    <div class="modal  fade " id="addProduktModal{{$res->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Plotsoni fushat qe te krijoni nje produkt.</h4>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>

                {{Form::open(['action' => 'ProduktController@store', 'method' => 'post' ]) }}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            {{ Form::label('Emri', null, ['class' => 'control-label']) }}
                            {{ Form::text('emri','', ['class' => 'form-control']) }}
                        </div>
                        <div class="row">
                            <div class="col-10">
                                <div class="form-group">
                                    {{ Form::label('Pershkrimi', null , ['class' => 'control-label']) }}
                                    {{ Form::textarea('pershkrimi','', ['class' => 'form-control', 'rows'=>'3']) }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-check mt-4">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="tipiK" value="6">Kuzhine
                                    </label>
                                </div>
                                <div class="form-check mt-5">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="tipiK" value="7">Shank
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    {{ Form::label('Qmimi', null , ['class' => 'control-label']) }}
                                    {{ Form::number('qmimi','', ['class' => 'form-control', 'step'=>'0.01' , 'min'=>'0']) }}
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    {{ Form::label('Qmimi 20:01+ (Opsionale)', null , ['class' => 'control-label']) }}
                                    {{ Form::number('qmimi2','', ['class' => 'form-control', 'step'=>'0.01' , 'min'=>'0']) }}
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="kat">Selektoni kategorine</label>
                                    <select name="kategoria" class="form-control" 
                                    onchange="showExtras(this.value, '{{$res->id}}')">
                                        <option value="0">Slektoni një kategori...</option>
                                        <?php

                                            foreach($kat as $kats){
                                                if($res->id == $kats->toRes){
                                                    echo '  <option value="'.$kats->id.'">'.$kats->emri.'</option>';
                                                }
                                            }
                                        ?>  
                                            
                                    </select>
                                </div>
                            </div>
                        </div>



                        <?php
                            $step = 1;

                            foreach($kat as $kats){
                                if($kats->toRes == $res->id){
                                    echo'
                                    <div id="BoxKat'.$kats->id.'R'.$res->id.'" style="display:none;">
                                        <div class="container">
                                            <div class="row">
                                            
                                                    <div class="col-6">
                                                        <ul class="list-group list-group-flush">
                                    ';
                                
                                

                                    foreach(ekstra::where('toCat', '=', $kats->id)->get() as $ekstras){
                                        echo '
                                            <li class="list-group-item">
                                                '.$ekstras->emri.' 
                                                <label class="switch ">
                                                  <input type="checkbox" class="success" id="extP'.$ekstras->id.'" 
                                                        onchange="addThis(this.id,\''.$ekstras->emri.'\',\''.$ekstras->qmimi.'\',\''.$res->id.'\',\''.$ekstras->id.'\')">
                                                        <span class="slider round"></span>
                                                </label>
                                            </li>
                                                
                                        ';
                                    }
                                echo '
                                                        </ul>
                                                    </div>
                                                    <div class="col-6">
                                                        <ul class="list-group list-group-flush">
                                                        ';
                                                        foreach(LlojetPro::where('kategoria', '=', $kats->id)->get() as $proLl){
                                                            echo '
                                                            
                                                                <li class="list-group-item">
                                                                    '.$proLl->emri.' 
                                                                    <label class="switch">
                                                                    <input type="checkbox" class="success" id="LlPro'.$proLl->id.'" 
                                                                        onchange="addThis2(this.id,\''.$proLl->emri.'\',\''.$proLl->vlera.'\',\''.$res->id.'\',\''.$proLl->id.'\')">
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </li>
                                                                    
                                                            ';
                                                        }
                                                        echo'

                                                        </ul>
                                                    </div>
                                        </div>
                                    </div>
                                </div>
                                ';
                                }
                            }
                        ?>

                        <!-- <input type="hidden" emri="extPro" id="extPro"> -->
                        {{ Form::hidden('extPro', '' , ['id' => 'extPro'.$res->id]) }}
                        {{ Form::hidden('typePro', '' , ['id' => 'typePro'.$res->id]) }}
                        {{ Form::hidden('restaurant', $res->id , ['id' => 'restaurant']) }}
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{ Form::submit('Ruaj', ['class' => 'form-control btn btn-primary']) }}
                        <br><br>
                    </div>
                {{Form::close() }}
                </div>
            </div>
        </div>
















































    <div class="container p-3 ProductAll" id="ProductOne{{$res->id}}">
        <div class="row" style="margin-bottom:-40px;">
            <div class="col-12">
                @if(session('error'))
                    <div class="alert alert-danger">
                        <h4>{{session('error')}}</h4>
                    </div>
                @endif
            </div>
        </div>
        <div class="row mt-5 mb-3">
            <div class="col-1 backBtn" onclick="backPro()">
                <img src="https://img.icons8.com/android/48/000000/back.png"/>
            </div>
            <div class="col-3 text-left">
                <p class="color-qrorpa mt-2" style="font-size:20px;"><strong>Products</strong></p>
            </div>

            <div class="col-8 text-right">
                <p class="color-qrorpa mt-2 mr-4" style="font-size:17px;"><strong>{{$res->emri}}</strong></p>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <button type="button" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addProduktModal{{$res->id}}">
                <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25">
                Add a new product</button>
            </div>
        </div>
    @foreach($produktet->sortByDesc('created_at') as $prod)
        @if($prod->toRes == $res->id)
            <div class="row mt-2 border ">
                <div class="col-1 color-black">
                    <h5>{{$prod->id}}</h5>
                </div>
                <div class="col-4">
                    <h4 class="pull-right color-black">{{$prod->emri}} <span style="color:lightgray;">({{kategori::find($prod->kategoria)->emri}})</span></h4>
                    <span style="color:gray;">{{$prod->pershkrimi}}</span>
                </div>
                <div class="col-5">
                    <h5 class="color-black">{{sprintf('%01.2f', $prod->qmimi) }}CHF
                            @if($prod->qmimi2 != 999999)
                                ( {{sprintf('%01.2f', $prod->qmimi2) }} CHF)
                            @endif
                    </h5>

                    <?php
                        $step = 1;
                        $extProD1 = explode('--0--',$prod->extPro);
                        foreach($extProD1 as $extProD1one){
                            if(empty($extProD1one)){

                            }else{
                                $extProD2 = explode('||',$extProD1one); 
                                if($step++ == 1){
                                    echo '<span style="color:gray;">'.$extProD2[1].' {'.$extProD2[2].' €} </span>';
                                }else{
                                    echo '<span style="color:gray;"><strong> >> </strong>'.$extProD2[1].' {'.$extProD2[2].' €} </span>';
                                }
                                
                            }
                        }

                    ?>
                    <p style="color:gray;">
                        <?php
                            $step2 = 1;
                            $typeD1 = explode('--0--',$prod->type);
                            foreach($typeD1 as $type){
                                if(empty($type)){

                                }else{
                                    $typeD2 =explode('||',$type); 
                                    if($step2++ == 1){
                                        echo $typeD2[1].'{ '.$typeD2[2].' }';
                                    }else{
                                        echo '<strong> >> </strong>'.$typeD2[1].'{ '.$typeD2[2].' }';
                                    }
                                }
                            }
                        ?>
                    </p>



                </div>
                <div class="col-1 mt-2">
                    <button class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#editProdM{{$prod->id}}">Edit</button>
                </div>
                <div class="col-1 mt-2">
                    {{Form::open(['action' => ['ProduktController@destroy', $prod->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("A jeni i sigurt")'])}}

                    {{Form::hidden('_method', 'DELETE')}}
                    {{Form::submit('Delete', ['class' => 'btn btn-outline-danger btn-block' ])}}

                    {{Form::close()}}
                
                </div>
            </div>
        @endif
    @endforeach


    </div>

@endforeach




















 <!-- The Product edit modal Modal -->
 @foreach($produktet as $prod)
            <div class="modal" id="editProdM{{$prod->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                <div class="modal-dialog  modal-lg"">
                    <div class="modal-content">

                     <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Ndryshoni të dhënat me kujdes</h4>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                        </div>
                    
                    {{Form::open(['action' => ['ProduktController@update', $prod->id], 'method' => 'post' ]) }}
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group">
                                {{ Form::label('Emri', null, ['class' => 'control-label']) }}
                                {{ Form::text('emri',$prod->emri, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('Pershkrimi', null , ['class' => 'control-label']) }}
                                {{ Form::textarea('pershkrimi', $prod->pershkrimi , ['class' => 'form-control', 'rows'=>'3']) }}
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        {{ Form::label('Qmimi', null , ['class' => 'control-label']) }}
                                        {{ Form::number('qmimi', $prod->qmimi , ['class' => 'form-control', 'step'=>'0.01' , 'min'=>'0']) }}
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        {{ Form::label('Qmimi 20:01+ (Opsionale)', null , ['class' => 'control-label']) }}
                                        @if($prod->qmimi2 != 999999)
                                            {{ Form::number('qmimi2',$prod->qmimi2, ['class' => 'form-control', 'step'=>'0.01' , 'min'=>'0']) }}
                                        @else
                                            {{ Form::number('qmimi2','', ['class' => 'form-control', 'step'=>'0.01' , 'min'=>'0']) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="kat">Selektoni kategorine</label>
                                        <select name="kategoria" class="form-control" 
                                        onchange="showExtras(this.value, '{{$res->id}}')">
                                            <option value="0">Slektoni një kategori...</option>
                                            <?php

                                                foreach($kat as $kats){
                                                    if($prod->toRes == $kats->toRes){
                                                        if($kats->id == $prod->kategoria){
                                                            echo '  <option value="'.$kats->id.'" selected>'.$kats->emri.'</option>';
                                                        }else{
                                                            echo '  <option value="'.$kats->id.'">'.$kats->emri.'</option>';
                                                        }
                                                    }
                                                }
                                            ?>  
                                                
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <?php

                                $theseExtras = array();
                                $theseTypes = array();
                                foreach(explode('--0--', $prod->extPro) as $oneExt){
                                    $addThisE = explode('||', $oneExt)[0];
                                    array_push($theseExtras, $addThisE);
                                }
                                foreach(explode('--0--', $prod->type) as $oneTyp){
                                    $addThisT = explode('||', $oneTyp)[0];
                                    array_push($theseTypes, $addThisT);
                                }
                               

                                echo'
                                <div id="EditBoxKat'.$prod->id.'">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-6">
                                                <ul class="list-group list-group-flush">
                                ';
                                foreach(ekstra::where('toCat', '=', $prod->kategoria)->get() as $theseE){
                                    if(in_array($theseE->id, $theseExtras)){
                                        echo '
                                            <li class="list-group-item">
                                                '.$theseE->emri.' 
                                                <label class="switch ">
                                                    <input type="checkbox" class="success checkedE" id="extPEdit'.$theseE->id.'P'.$prod->id.'" 
                                                    onchange="addThisEdit(this.id,\''.$theseE->emri.'\',\''.$theseE->qmimi.'\',\''.$theseE->id.'\',\''.$prod->id.'\')" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </li>
                                        ';
                                    }else{
                                        echo '
                                            <li class="list-group-item">
                                                '.$theseE->emri.' 
                                                <label class="switch ">
                                                    <input type="checkbox" class="success" id="extPEdit'.$theseE->id.'P'.$prod->id.'" 
                                                    onchange="addThisEdit(this.id,\''.$theseE->emri.'\',\''.$theseE->qmimi.'\',\''.$theseE->id.'\',\''.$prod->id.'\')">
                                                    <span class="slider round"></span>
                                                </label>
                                            </li>
                                        ';
                                    }
                                  
                                }
                                echo '          </ul>
                                            </div>
                                            <div class="col-6">
                                                <ul class="list-group list-group-flush">';
                                foreach(LlojetPro::where('kategoria', '=', $prod->kategoria)->get() as $theseT){
                                                    if(in_array($theseT->id, $theseTypes)){
                                                        echo '
                                                            <li class="list-group-item">
                                                                '.$theseT->emri.' 
                                                                <label class="switch ">
                                                                    <input type="checkbox" class="success checkedT" id="typePEdit'.$theseT->id.'P'.$prod->id.'" 
                                                                    onchange="addThis2Edit(this.id,\''.$theseT->emri.'\',\''.$theseT->vlera.'\',\''.$theseT->id.'\',\''.$prod->id.'\')" >
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </li>
                                                        ';
                                                    }else{
                                                        echo '
                                                            <li class="list-group-item">
                                                                '.$theseT->emri.' 
                                                                <label class="switch ">
                                                                    <input type="checkbox" class="success" id="typePEdit'.$theseT->id.'P'.$prod->id.'" 
                                                                    onchange="addThis2Edit(this.id,\''.$theseT->emri.'\',\''.$theseT->vlera.'\',\''.$theseT->id.'\',\''.$prod->id.'\')">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </li>
                                                        ';
                                                    }
                                                  
                                }


                                echo '
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ';
                            ?>

                            <!-- <input type="hidden" emri="extPro" id="extPro"> -->
                            {{ Form::hidden('extProEdit', $prod->extPro , ['id' => 'extProEdit'.$prod->id]) }}
                            {{ Form::hidden('typeProEdit', $prod->type , ['id' => 'typeProEdit'.$prod->id]) }}
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            {{ Form::submit('Ruaj', ['class' => 'form-control btn btn-primary']) }}
                            <br><br>
                        </div>
                    {{Form::close() }}
                    </div>
                </div>
            </div>
        @endforeach



<script>
    $('.checkedE').prop('checked', true);
        $('.checkedT').prop('checked', true);
</script>





















<script>
     $(document).ready(function(){
        $('.ProductAll').hide();
    });//End of document ready 

    function openResProd(Id){
        $('#ProductStart').hide('slow');
        $('#ProductOne'+Id).show('slow');
    }

    function backPro(){
        $('.ProductAll').hide('slow');
        $('#ProductStart').show('slow');
    }




    function showExtras(value,resId){
        if(value == 0){
            // alert(last);
            if(last != ''){
                document.getElementById(last).style.display="none";
            }
        }else{
            // alert(last);
            if(last != ''){
                document.getElementById(last).style.display="none";
            }

            var show = 'BoxKat'+value+'R'+resId;
            last = show;

            document.getElementById(show).style.display="block";
            // alert('yep'+value);
        }
    }










    function addThis(theId,name,price,resId,extId){
        var checkBox = document.getElementById(theId);
        var extPro = document.getElementById('extPro'+resId);
        var extProValue = document.getElementById('extPro'+resId).value;

        if(extProValue == ""){
            var add = extId+'||'+name+'||'+price;
            if(checkBox.checked == true){
                extPro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
            var add = extId+'||'+name+'||'+price;
            if(checkBox.checked == true){
                extPro.value =extProValue+'--0--'+add;
            }else{
               var newVal = extProValue.replace(add,'');
               extPro.value = newVal;
            }
        }  
    }




    
    function addThis2(theId,name,value,resId,llId){
        var checkBox = document.getElementById(theId);
        var typePro = document.getElementById('typePro'+resId);
        var typeProValue = document.getElementById('typePro'+resId).value;

        // alert(typeProValue);

        if(typeProValue == ""){
            var add = llId+'||'+name+'||'+value;
            if(checkBox.checked == true){
                typePro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
            var add =llId+'||'+name+'||'+value;
            if(checkBox.checked == true){
                typePro.value =typeProValue+'--0--'+add;
            }else{
               var newVal = typeProValue.replace(add,'');
               typePro.value = newVal;
            }
        }
    }





    function addThisEdit(theId,name,price,extId,prodId){
        var checkBox = document.getElementById(theId);
        var extPro = document.getElementById('extProEdit'+prodId);
        var extProValue = document.getElementById('extProEdit'+prodId).value;

        var add = extId+'||'+name+'||'+price;

        if(extProValue == ""){
            if(checkBox.checked == true){
                extPro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
           
            if(checkBox.checked == true){
                extPro.value =extProValue+'--0--'+add;
            }else{
               var newVal = extProValue.replace(add,'');
               extPro.value = newVal;
            }
        }  
    }



    function addThis2Edit(theId,name,value,llId,prodId){
        var checkBox = document.getElementById(theId);
        var typePro = document.getElementById('typeProEdit'+prodId);
        var typeProValue = document.getElementById('typeProEdit'+prodId).value;

        var add =llId+'||'+name+'||'+value;

        if(typeProValue == ""){
            if(checkBox.checked == true){
                typePro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
            
            if(checkBox.checked == true){
                typePro.value =typeProValue+'--0--'+add;
            }else{
               var newVal = typeProValue.replace(add,'');
               typePro.value = newVal;
            }
        }
    }


</script>