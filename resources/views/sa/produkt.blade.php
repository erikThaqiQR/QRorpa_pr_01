@extends('layouts.appSAPanel')



@section('extra-css')



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
@endsection































@section('content')

        <div class="col-12 BRef">
             <a href="/" class="btn btn-outline-default qrorpaColor">Restaurant</a><span>/</span>
             <a href="/produktet1" class="btn btn-outline-default qrorpaColor">Menaxhimi i produkteve</a><span>/</span>
        </div>

        @include('inc.messages')


<script>
    var last = "";
</script>








        <div class="modal  fade " id="addProduktModal" style="color:black">
            <div class="modal-dialog modal-lg">
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
                        <div class="form-group">
                            {{ Form::label('Pershkrimi', null , ['class' => 'control-label']) }}
                            {{ Form::textarea('pershkrimi','', ['class' => 'form-control', 'rows'=>'3']) }}
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    {{ Form::label('Qmimi', null , ['class' => 'control-label']) }}
                                    {{ Form::number('qmimi','', ['class' => 'form-control', 'step'=>'0.01']) }}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="kat">Selektoni kategorine</label>
                                    <select name="kategoria" class="form-control" onchange="showExtras(this.value)">
                                        <option value="0">Slektoni një kategori...</option>
                                        <?php
                                            use App\Kategori;

                                            foreach(kategori::all() as $kats){
                                                echo '  <option value="'.$kats->id.'">'.$kats->emri.'</option> ';
                                            }
                                        ?>  
                                            
                                    </select>
                                </div>
                            </div>
                        </div>



                        <?php
                            use App\ekstra;
                            use App\LlojetPro;
                            $step = 1;

                            foreach(kategori::all() as $kats){
                                
                                    echo'
                                    <div id="BoxKat'.$kats->id.'" style="display:none;">
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
                                                                    onchange="addThis(this.id,\''.$ekstras->emri.'\',\''.$ekstras->qmimi.'\')">
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
                                                                    <label class="switch ">
                                                                    <input type="checkbox" class="success" id="LlPro'.$proLl->id.'" 
                                                                        onchange="addThis2(this.id,\''.$proLl->emri.'\',\''.$proLl->vlera.'\')">
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

                           



                        ?>
                        

                        <!-- <input type="hidden" emri="extPro" id="extPro"> -->
                        {{ Form::hidden('extPro', '' , ['id' => 'extPro']) }}
                        {{ Form::hidden('typePro', '' , ['id' => 'typePro']) }}
                       


                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{ Form::submit('Ruaj', ['class' => 'form-control btn btn-primary']) }}
                        
                    </div>
                {{Form::close() }}

                </div>
            </div>
        </div>










        






































    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-outline-light btn-block" data-toggle="modal" data-target="#addProduktModal">
                <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25">
                Shto nje Produkt</button>
            </div>
        </div>
    </div>





        <div class="container pt-2 pb-3" style="background-color:white; border-radius:20px;">

            @foreach($prods as $prod)
                <div class="row mt-2 border ">
                    <div class="col-1 color-black">
                        <h5>{{$prod->id}}</h5>
                    </div>
                    <div class="col-4">
                        <h4 class="pull-right color-black">{{$prod->emri}} <span style="color:lightgray;">({{kategori::find($prod->kategoria)->emri}})</span></h4>
                        <span style="color:gray;">{{$prod->pershkrimi}}</span>
                    </div>
                    <div class="col-5">
                        <h5 class="color-black">{{sprintf('%01.2f', $prod->qmimi) }}€</h5>

                        <?php
                            $step = 1;
                            $extProD1 = explode('--0--',$prod->extPro);
                            foreach($extProD1 as $extProD1one){
                                if(empty($extProD1one)){

                                }else{
                                    $extProD2 = explode('||',$extProD1one); 
                                    if($step++ == 1){
                                        echo '<span style="color:gray;">'.$extProD2[0].' {'.$extProD2[1].' €} </span>';
                                    }else{
                                        echo '<span style="color:gray;"><strong> >> </strong>'.$extProD2[0].' {'.$extProD2[1].' €} </span>';
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
                                            echo $typeD2[0].'{ '.$typeD2[1].' }';
                                        }else{
                                            echo '<strong> >> </strong>'.$typeD2[0].'{ '.$typeD2[1].' }';
                                        }
                                    }
                                }
                            ?>
                        </p>



                    </div>
                    <div class="col-1 mt-2">
                        <!-- <button class="btn btn-outline-info btn-block">Edit</button> -->
                    </div>
                    <div class="col-1 mt-2">
                        {{Form::open(['action' => ['ProduktController@destroy', $prod->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("A jeni i sigurt")'])}}

                        {{Form::hidden('_method', 'DELETE')}}
                        {{Form::submit('Delete', ['class' => 'btn btn-outline-danger btn-block' ])}}

                        {{Form::close()}}
                     
                    </div>
                </div>
            @endforeach
            
        
        </div>

<br>
@include('inc.footer')

@endsection






























@section('extra-js')
<script>

    function showExtras(value){
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

            var show = 'BoxKat'+value;
            last = show;

            document.getElementById(show).style.display="block";
            // alert('yep'+value);
        }
    }







    function addThis(theId,name,price){
        var checkBox = document.getElementById(theId);
        var extPro = document.getElementById('extPro');
        var extProValue = document.getElementById('extPro').value;

        // alert(extProValue);

        if(extProValue == ""){
            var add = name+'||'+price;
            if(checkBox.checked == true){
                extPro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
            var add = name+'||'+price;
            if(checkBox.checked == true){
                extPro.value =extProValue+'--0--'+add;
            }else{
               var newVal = extProValue.replace(add,'');
               extPro.value = newVal;
            }
        }



        
        
    }




    
    function addThis2(theId,name,value){
        var checkBox = document.getElementById(theId);
        var typePro = document.getElementById('typePro');
        var typeProValue = document.getElementById('typePro').value;

        // alert(typeProValue);

        if(typeProValue == ""){
            var add = name+'||'+value;
            if(checkBox.checked == true){
                typePro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
            var add = name+'||'+value;
            if(checkBox.checked == true){
                typePro.value =typeProValue+'--0--'+add;
            }else{
               var newVal = typeProValue.replace(add,'');
               typePro.value = newVal;
            }
        }
    }


</script>
@endsection