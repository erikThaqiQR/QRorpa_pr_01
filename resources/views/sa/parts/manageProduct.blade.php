<?php
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
    use App\Restorant;
    use App\Produktet;

    $theResId = $_GET['Res'];

?>
<script>
    var last="";
</script>

<style>
        .direktiveBox{
            color:white;
            border-radius:10px;
            margin-top:35px;
            padding-top:50px;
            padding-bottom:50px;

            background-color:rgb(39,190,175);
        }
        .direktiveBox:hover{
            cursor: pointer;
        }

        .backBtn{
            opacity:0.5;
        }
        .backBtn:hover{
            opacity:0.95;
            cursor: pointer;
        }

        .ResShow{
            background-color:rgb(39,190,175);
            color:white;
            border-radius:20px;
        }
        .ResShow:hover{
            cursor: pointer;
        }

        .anchorMy{
            color: black;
            text-decoration: none;
        }
        .anchorMy:hover{
            color: rgb(39,190,175);
            text-decoration: none;
        }

    </style>
<div class="container-fluid mb-4 " >
    <div class="row mt-4">
        <a href="produktet5Boxes?Res={{$_GET['Res']}}" class="col-2 anchorMy pt-4" style="font-size:25px;"><strong> < Back </strong></a>
        <div class="col-8 text-center">
            <p style="font-size:45px;" class="color-qrorpa">"{{Restorant::find($_GET['Res'])->emri}}"  /  Products</p>
        </div>
         <div class="col-2"></div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addProduktModal">
            <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25">
            Add a new product</button>
        </div>
    </div>
</div>



















    <div class="container p-3 ProductAll">
        <div class="row" style="margin-bottom:-40px;">
            <div class="col-12">
                @if(session('error'))
                    <div class="alert alert-danger">
                        <h4>{{session('error')}}</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div class="d-flex flex-wrap justify-content-between p-1">
    @foreach(Produktet::where('toRes',$theResId)->get()->sortByDesc('created_at') as $prod)
        
            <div style="width:49.7%;" class="d-flex justify-content-around mt-2 border p-1">
                <div style="width:10%;" class="color-black">
                    <h5>{{$prod->id}}</h5>
                </div>
                <div style="width:30%;">
                    <h4 class="pull-right color-black">{{$prod->emri}} <span style="color:lightgray;">({{kategori::find($prod->kategoria)->emri}})</span></h4>
                    <span style="color:gray;">{{$prod->pershkrimi}}</span>
                </div>
                <div style="width:48%;">
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
                <div style="width:10%;" class="mt-2">
                    <button class="btn btn-outline-info btn-block mb-1" data-toggle="modal" data-target="#editProdM{{$prod->id}}">Edit</button>
                         {{Form::open(['action' => ['ProduktController@destroy', $prod->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("A jeni i sigurt")'])}}

                        {{Form::hidden('_method', 'DELETE')}}
                        {{Form::submit('Delete', ['class' => 'btn btn-outline-danger btn-block' ])}}

                        {{Form::close()}}
                </div>
            </div>
   
    @endforeach


    </div>



























































<div class="modal  fade " id="addProduktModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-lg pb-5" >
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Fill the form to add a new product to the restorant</h4>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>

                {{Form::open(['action' => 'ProduktController@store', 'method' => 'post' ]) }}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            {{ Form::label('Name', null, ['class' => 'control-label']) }}
                            {{ Form::text('emri','', ['class' => 'form-control']) }}
                        </div>
                        <div class="row">
                            <div class="col-10">
                                <div class="form-group">
                                    {{ Form::label('Description', null , ['class' => 'control-label']) }}
                                    {{ Form::textarea('pershkrimi','', ['class' => 'form-control', 'rows'=>'3']) }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-check mt-4">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="tipiK" value="6">Kitchen
                                    </label>
                                </div>
                                <div class="form-check mt-5">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="tipiK" value="7">Bar
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    {{ Form::label('Price', null , ['class' => 'control-label']) }}
                                    {{ Form::number('qmimi','', ['class' => 'form-control', 'step'=>'0.01' , 'min'=>'0']) }}
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    {{ Form::label('Price 20:01+ (Opsionale)', null , ['class' => 'control-label']) }}
                                    {{ Form::number('qmimi2','', ['class' => 'form-control', 'step'=>'0.01' , 'min'=>'0']) }}
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="kat">Select a category</label>
                                    <select name="kategoria" class="form-control" 
                                    onchange="showExtras(this.value, '{{$theResId}}')">
                                        <option value="0">Select a category...</option>
                                        <?php
                                            foreach(kategori::where('toRes',$theResId)->get() as $kats){
                                                    echo '  <option value="'.$kats->id.'">'.$kats->emri.'</option>';
                                            }
                                        ?>  
                                            
                                    </select>
                                </div>
                            </div>
                        </div>



                        <?php
                            $step = 1;

                            foreach(kategori::where('toRes',$theResId)->get() as $kats){
                               
                                    echo'
                                    <div id="BoxKat'.$kats->id.'R'.$theResId.'" style="display:none;">
                                        <div class="container">
                                            <div class="row">
                                            
                                                    <div class="col-6">
                                                        <ul class="list-group list-group-flush">
                                    ';
                                
                                

                                    foreach(ekstra::where('toCat', '=', $kats->id)->get() as $ekstras){
                                        echo '
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span style="width:80%;"> '.$ekstras->emri.' </span>
                                                <label style="width:19%;" class="text-right switch ">
                                                  <input type="checkbox" class="success" id="extP'.$ekstras->id.'" 
                                                        onchange="addThis(this.id,\''.$ekstras->emri.'\',\''.$ekstras->qmimi.'\',\''.$theResId.'\',\''.$ekstras->id.'\')">
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
                                                            
                                                                <li class="list-group-item d-flex justify-content-between">
                                                                    <span style="width:80%;"> '.$proLl->emri.' </span>
                                                                    <label  style="width:19%;" class="text-right switch">
                                                                    <input type="checkbox" class="success" id="LlPro'.$proLl->id.'" 
                                                                        onchange="addThis2(this.id,\''.$proLl->emri.'\',\''.$proLl->vlera.'\',\''.$theResId.'\',\''.$proLl->id.'\')">
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
                        {{ Form::hidden('extPro', '' , ['id' => 'extPro'.$theResId]) }}
                        {{ Form::hidden('typePro', '' , ['id' => 'typePro'.$theResId]) }}
                        {{ Form::hidden('restaurant', $theResId , ['id' => 'restaurant']) }}
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{ Form::submit('Save', ['class' => 'form-control btn', 'style' => 'background-color:rgb(39,190,175); font-weight:bold; color:white;']) }}
                        <br><br>
                    </div>
                {{Form::close() }}
                </div>
            </div>
        </div>

























































































        <script>

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