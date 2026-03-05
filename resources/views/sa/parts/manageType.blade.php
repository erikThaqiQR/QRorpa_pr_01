<?php
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
    use App\Restorant;
    // use App\Produktet;

    $theResId = $_GET['Res'];

?>
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
            <p style="font-size:45px;" class="color-qrorpa">"{{Restorant::find($_GET['Res'])->emri}}"  /  Types</p>
        </div>
         <div class="col-2"></div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addTypeModal">
            <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25">
            Add a new type</button>
        </div>
    </div>
</div>



<div class="d-flex flex-wrap justify-content-between p-1 mb-5">
    @foreach(LlojetPro::where('toRes',$theResId)->get()->sortByDesc('created_at') as $typ)
        <div style="width:49%; border:1px solid lightgray; border-radius:5px; font-size:20px;" class="text-center mb-1 d-flex justify-content-between">
            <span style="opacity:0.75; width:30%;">({{kategori::find($typ->kategoria)->emri}})</span>
            <span style="width:34%;" class="text-left"> {{$typ->emri}} </span>
            <span style="width:15%;" class="text-left"> {{$typ->vlera}} X </span>

            <button style="width:10%;" class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#editType{{$typ->id}}">Edit</button>
            

            <div style="width:10%;">
                {{Form::open(['action' => ['LlojetProController@destroy', $typ->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure")'])}}

                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-outline-danger btn-block' ])}}

                {{Form::close()}}
            </div>
        </div>
    @endforeach
</div>


























<div class="modal  fade" id="addTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color:rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-md">
                <div class="modal-content" style="border-radius:25px;">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add a new Type</h4>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>

                {{Form::open(['action' => 'LlojetProController@store', 'method' => 'post' ]) }}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-1">
                                
                                </div>
                                <div class="col-10">
                                    <div class="form-group">
                                        {{ Form::label('Name', null, ['class' => 'control-label']) }}
                                        {{ Form::text('emri','', ['class' => 'form-control']) }}
                                    </div>

                                    {{csrf_field()}}
                                    
                                    <div class="form-group">
                                        <label for="sel1">Category</label>
                                        <select name="toCat" class="form-control" >
                                        <?php
                                            foreach(kategori::where('toRes',$theResId)->get() as $kate){
                                                echo '<option value="'.$kate->id.'">'.$kate->emri.'</option>';
                                            }
                                        ?>
                                            
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('Value', null , ['class' => 'control-label']) }}
                                        {{ Form::number('vlera','', ['class' => 'form-control', 'step'=>'0.0000000001', 'min' => '0']) }}
                                    </div>

                                    {{ Form::hidden('restaurant', $theResId, ['class' => 'custom-file-input']) }}
                                    
                                </div>
                                <div class="col-1">
                                
                                </div>
                            </div>
                        </div>
                        {{ Form::hidden('page', $theResId, ['class' => 'custom-file-input']) }}

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{ Form::submit('Save', ['class' => 'form-control btn btn-primary']) }}
                        
                    </div>
                {{Form::close()}}

                </div>
            </div>
        </div>










        

 <!-- Edit Type modal's -->
 @foreach(LlojetPro::where('toRes',$theResId)->get() as $llojetPro)

<div class="modal  fade color-black" id="editType{{$llojetPro->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Jeni duke edituar produktin shtesë ___"{{$llojetPro->emri}}"-{{ sprintf('%01.2f', $llojetPro->vlera)}} </h4>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>

        {{Form::open(['action' => ['LlojetProController@update', $llojetPro->id], 'method' => 'post' ]) }}
            {{csrf_field()}}
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group">
                    {{ Form::label('Emri',null , ['class' => 'control-label']) }}
                    {{ Form::text('emri',$llojetPro->emri, ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('Vlera', null , ['class' => 'control-label']) }}
                    {{ Form::number('vlera', $llojetPro->vlera , ['class' => 'form-control', 'step'=>'0.0000000001', 'min' => '0']) }}
                </div>
                <div class="form-group">
                        <label for="sel1">Cilës kategori i përket</label>
                        <select name="toCat" class="form-control" >
                        <?php
                            
                            foreach(kategori::where('toRes',$theResId)->get() as $kate){
                                    if($kate->id == $llojetPro->kategoria)
                                        echo '<option value="'.$kate->id.'" selected>'.$kate->emri.'</option>';
                                    else
                                        echo '<option value="'.$kate->id.'">'.$kate->emri.'</option>';
                            }
                        ?>
                            
                        </select>
                    </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                {{ Form::submit('Ruaj', ['class' => 'form-control btn btn-primary']) }}
                
            </div>
        {{Form::close() }}

        </div>
    </div>
</div>
    
@endforeach