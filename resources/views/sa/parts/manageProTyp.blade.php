<?php
    use App\kategori;
?>
<div class="container p-2" id="TypeStart">
    <div class="row mt-5 mb-2">
        <div class="col-1 backBtn" onclick="back()">
              <img src="https://img.icons8.com/android/48/000000/back.png"/>
        </div>
        <div class="col-3 text-left">
            <p class="color-qrorpa mt-2" style="font-size:20px;"><strong>Types</strong></p>
        </div>

        <div class="col-8 text-right">
            <p class="color-qrorpa mt-2 mr-4" style="font-size:17px;"><strong>Select a restaurant to see the Types!</strong></p>
        </div>
    </div>

    <div class="row mt-5 ml-3">
        @foreach($restaurant as $res)
            <?php
            echo '<div class="col-2 text-center p-3 ResShow ml-4 mt-3" onclick="openResType('.$res->id.')">';
            ?>
                {{$res->emri}}
            </div>
        @endforeach
    </div>
</div>




















<!-- ADD TYPE MODAL -->
@foreach($restaurant as $res)
<div class="modal  fade" id="addTypeModal{{$res->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color:rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-md">
                <div class="modal-content" style="border-radius:25px;">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add a new Type</h4>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
                                        {{ Form::label('Emri', null, ['class' => 'control-label']) }}
                                        {{ Form::text('emri','', ['class' => 'form-control']) }}
                                    </div>

                                    {{csrf_field()}}
                                    
                                    <div class="form-group">
                                        <label for="sel1">Cilës kategori i përket</label>
                                        <select name="toCat" class="form-control" >
                                        <?php
                                            foreach($kat as $kate){
                                                if($kate->toRes == $res->id){
                                                    echo '<option value="'.$kate->id.'">'.$kate->emri.'</option>';
                                                }
                                            }
                                        ?>
                                            
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('Vlera', null , ['class' => 'control-label']) }}
                                        {{ Form::number('vlera','', ['class' => 'form-control', 'step'=>'0.0000000001', 'min' => '0']) }}
                                    </div>

                                    {{ Form::hidden('restaurant', $res->id , ['class' => 'custom-file-input']) }}
                                    
                                </div>
                                <div class="col-1">
                                
                                </div>
                            </div>
                        </div>
                        {{ Form::hidden('page', $res->id , ['class' => 'custom-file-input']) }}

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{ Form::submit('Ruaj', ['class' => 'form-control btn btn-primary']) }}
                        
                    </div>
                {{Form::close()}}

                </div>
            </div>
        </div>
@endforeach






 <!-- Edit Type modal's -->
 @foreach($types as $llojetPro)

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
                            
                            foreach($kat as $kate){
                                if($llojetPro->toRes == $kate->toRes){
                                    if($kate->id == $llojetPro->kategoria)
                                        echo '<option value="'.$kate->id.'" selected>'.$kate->emri.'</option>';
                                    else
                                        echo '<option value="'.$kate->id.'">'.$kate->emri.'</option>';
                                }
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



















@foreach($restaurant as $res)
    <div class="container TypeAll" id="TypeOne{{$res->id}}" >
        @include('inc.messagesFull')
        <div class="row mt-5 mb-3">
            <div class="col-1 backBtn" onclick="backType()">
                <img src="https://img.icons8.com/android/48/000000/back.png"/>
            </div>
            <div class="col-3 text-left">
                <p class="color-qrorpa mt-2" style="font-size:20px;"><strong>Types</strong></p>
            </div>

            <div class="col-8 text-right">
                <p class="color-qrorpa mt-2 mr-4" style="font-size:17px;"><strong>{{$res->emri}}</strong></p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <button type="button" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addTypeModal{{$res->id}}">
                <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25">
                Add a new type</button>
            </div>
        </div>

        <div class="row">
            <table class="table table-striped" style="background-color:white; border-bottom-left-radius:20px; border-bottom-right-radius:20px;">
                <tr>
                    <th>Id.</th>
                    <th>Emri</th>
                    <th>Kategoria</th>
                    <th>Vlera</th>
                    <th></th>
                    <th></th>
                </tr>

                @foreach($types->sortByDesc('created_at') as $llojetPro)
                    @if($llojetPro->toRes == $res->id)
                        <tr>
                            <td>{{$llojetPro->id}}</td>
                            <td>{{$llojetPro->emri}}</td>
                            <td>{{kategori::find($llojetPro->kategoria)->emri}}</td>
                            <td>{{$llojetPro->vlera}}</td>
                            <td>
                                <button class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#editType{{$llojetPro->id}}">Edit</button>
                            </td>

                            <td>
                                {{Form::open(['action' => ['LlojetProController@destroy', $llojetPro->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure")'])}}

                                {{Form::hidden('_method', 'DELETE')}}
                                {{Form::submit('Delete', ['class' => 'btn btn-outline-danger btn-block' ])}}

                                {{Form::close()}}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
@endforeach



<script>
     $(document).ready(function(){
        $('.TypeAll').hide();
    });//End of document ready 

    function openResType(Id){
        $('#TypeStart').hide('slow');
        $('#TypeOne'+Id).show('slow');
    }

    function backType(){
        $('.TypeAll').hide('slow');
        $('#TypeStart').show('slow');
    }
</script>