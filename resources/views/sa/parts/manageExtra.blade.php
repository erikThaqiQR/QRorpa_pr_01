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
            <p style="font-size:45px;" class="color-qrorpa">"{{Restorant::find($_GET['Res'])->emri}}"  /  Extras</p>
        </div>
         <div class="col-2"></div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addExtModal">
            <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25">
            Add a new extra</button>
        </div>
    </div>
</div>



<div class="d-flex flex-wrap justify-content-between p-1 mb-5">
    @foreach(ekstra::where('toRes',$theResId)->get()->sortByDesc('created_at') as $extraa)
        <div style="width:49%; border:1px solid lightgray; border-radius:5px; font-size:20px;" class="text-center mb-1 d-flex justify-content-between">
            <span style="opacity:0.75; width:30%;">({{kategori::find($extraa->toCat)->emri}})</span>
            <span style="width:34%;" class="text-left"> {{$extraa->emri}} </span>
            <span style="width:15%;" class="text-left"> {{$extraa->qmimi}} CHF </span>

            
            <button style="width:10%;" class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#editExt{{$extraa->id}}">Edit</button>
                              

            <div style="width:10%;">
                {{Form::open(['action' => ['EkstraController@destroy', $extraa->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure")'])}}

                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-outline-danger btn-block' ])}}

                {{ Form::hidden('page', $theResId , ['class' => 'custom-file-input']) }}

                {{Form::close()}}
            </div>
        </div>
    @endforeach
</div>

















<div class="modal  fade " id="addExtModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Shkruni të dhënat dhe shtoni një komponent ekstra</h4>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>

                {{Form::open(['action' => 'EkstraController@store', 'method' => 'post' ]) }}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-1">
                                {{csrf_field()}}
                                </div>
                                <div class="col-10">
                                    <div class="form-group">
                                        {{ Form::label('Emri', null, ['class' => 'control-label']) }}
                                        {{ Form::text('emri','', ['class' => 'form-control']) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('Qmimi', null , ['class' => 'control-label']) }}
                                        {{ Form::number('qmimi','', ['class' => 'form-control', 'step'=>'0.01', 'min' => '0']) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="sel1">Cilës kategori i përket</label>
                                        <select name="toCat" class="form-control" >
                                        <?php
                                            foreach(kategori::where('toRes',$theResId)->get() as $kate){
                                                echo '<option value="'.$kate->id.'">'.$kate->emri.'</option>';
                                            }
                                        ?>
                                            
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" value="{{$theResId}}" name="restaurant">
                                    </div>
                                </div>
                                <div class="col-1">
                                
                                </div>
                            </div>
                        </div>
                        

                    </div>
                    {{ Form::hidden('page', $theResId, ['class' => 'custom-file-input']) }}
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{ Form::submit('Save', ['class' => 'form-control btn btn-primary']) }}
                        
                    </div>
                {{Form::close() }}

                </div>
            </div>
        </div>



















          <!-- Edit modal's -->
          @foreach(ekstra::where('toRes',$theResId)->get() as $extr)

            <div class="modal  fade " id="editExt{{$extr->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Jeni duke edituar produktin shtesë ___"{{$extr->emri}}"-{{ sprintf('%01.2f', $extr->qmimi)}} €</h4>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
                        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    </div>

                    {{Form::open(['action' => ['EkstraController@update', $extr->id], 'method' => 'post' ]) }}
                    
                        {{csrf_field()}}

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group">
                                {{ Form::label('Emri',null , ['class' => 'control-label']) }}
                                {{ Form::text('emri',$extr->emri, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('Qmimi', null , ['class' => 'control-label']) }}
                                {{ Form::number('qmimi', $extr->qmimi , ['class' => 'form-control', 'step'=>'0.01', 'min' => '0']) }}
                            </div>
                            <div class="form-group">
                                    <label for="sel1">Cilës kategori i përket</label>
                                    <select name="toCat" class="form-control" >
                                    <?php
                                        foreach(kategori::where('toRes',$theResId)->get() as $kate){
                                            if($kate->id == $extr->toCat)
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
                            {{ Form::submit('Save', ['class' => 'form-control btn btn-primary']) }}
                            
                        </div>
                    {{Form::close() }}

                    </div>
                </div>
            </div>
                
        @endforeach