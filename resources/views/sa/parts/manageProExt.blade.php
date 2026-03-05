<?php
    use App\kategori;
?>
<div class="container p-2" id="ExtraStart">
    <div class="row mt-5 mb-2">
        <div class="col-1 backBtn" onclick="back()">
              <img src="https://img.icons8.com/android/48/000000/back.png"/>
        </div>
        <div class="col-3 text-left">
            <p class="color-qrorpa mt-2" style="font-size:20px;"><strong>Extras</strong></p>
        </div>

        <div class="col-8 text-right">
            <p class="color-qrorpa mt-2 mr-4" style="font-size:17px;"><strong>Select a restaurant to see the Extras!</strong></p>
        </div>
    </div>

    <div class="row mt-5 ml-3">
        @foreach($restaurant as $res)
            <?php
            echo '<div class="col-2 text-center p-3 ResShow ml-4 mt-3" onclick="openResExt('.$res->id.')">';
            ?>
                {{$res->emri}}
            </div>
        @endforeach
    </div>

    

</div>


















































<!-- ADD EXTRAS MODAL -->
@foreach($restaurant as $res)
        <div class="modal  fade " id="addExtModal{{$res->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Shkruni të dhënat dhe shtoni një komponent ekstra</h4>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>

                {{Form::open(['action' => 'EkstraController@store', 'method' => 'post' ]) }}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-3">
                                {{csrf_field()}}
                                </div>
                                <div class="col-6">
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
                                            foreach($kat as $kate){
                                                if($kate->toRes == $res->id){
                                                    echo '<option value="'.$kate->id.'">'.$kate->emri.'</option>';
                                                }
                                                
                                            }
                                        ?>
                                            
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="kat">Select the restaurant</label>
                                        <select name="restaurant" class="form-control" id="resKat">
                                            <option value="0">Select a restaurant...</option>
                                            <?php
                                                if(!empty($restaurant)){
                                                    foreach($restaurant as $res2){
                                                        if($res2->id == $res->id){
                                                            echo '  <option selected value="'.$res2->id.'">'.$res2->emri.'</option> ';
                                                        }else{
                                                            echo '  <option value="'.$res2->id.'">'.$res2->emri.'</option> ';
                                                        }
                                                    }
                                                }
                                            ?>  
                                                        
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                
                                </div>
                            </div>
                        </div>
                        

                    </div>
                    {{ Form::hidden('page', $res->id , ['class' => 'custom-file-input']) }}
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{ Form::submit('Ruaj', ['class' => 'form-control btn btn-primary']) }}
                        
                    </div>
                {{Form::close() }}

                </div>
            </div>
        </div>
@endforeach















                <!-- Edit modal's -->
                @foreach($thisKat as $extr)

                <div class="modal  fade " id="editExt{{$extr->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Jeni duke edituar produktin shtesë ___"{{$extr->emri}}"-{{ sprintf('%01.2f', $extr->qmimi)}} €</h4>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
                                            foreach($kat as $kate){
                                                if($extr->toRes == $kate->toRes){
                                                    if($kate->id == $extr->toCat)
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



    <div class="container ExtraAll" id="ExtraOne{{$res->id}}">
        <?php
            if(session('error') == 'None' || session('success') == 'None'){
                echo '
                <script>
                    $("#message").hide("slow");
                </script>
                ';
            }else{
                echo '
                <script>
                    $("#message").show("slow");
                </script>
                ';
            }
        ?>
        <div id="message">
            @include('inc.messagesFull')  
        </div>
                                   

        <div class="row mt-5 mb-3">
            <div class="col-1 backBtn" onclick="backExt()">
                <img src="https://img.icons8.com/android/48/000000/back.png"/>
            </div>
            <div class="col-3 text-left">
                <p class="color-qrorpa mt-2" style="font-size:20px;"><strong>Extras</strong></p>
            </div>

            <div class="col-8 text-right">
                <p class="color-qrorpa mt-2 mr-4" style="font-size:17px;"><strong>{{$res->emri}}</strong></p>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <button type="button" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addExtModal{{$res->id}}">
                <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25">
                Shto nje Komponent ekstra</button>
            </div>
        </div>

        <div class="row mt-4" >
            <div class="col-12">
                <table class="table table-striped" style="background-color:white; border-bottom-left-radius:20px; border-bottom-right-radius:20px;">
                    <tr>
                        <th>Nr.</th>
                        <th>Emri</th>
                        <th>Qmimi</th>
                        <th>Për</th>
                        <th></th>
                        <th></th>
                    </tr>

                    @foreach($thisKat as $ext)
                        @if($ext->toRes == $res->id)
                            <tr>
                                
                                <td>{{$ext->id}}</td>
                                <td>{{$ext->emri}}</td>
                                <td>{{$ext->qmimi}}</td>
                                <td>{{kategori::find($ext->toCat)->emri}}</td>

                                <td>
                                    <button class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#editExt{{$ext->id}}">Edit</button>
                                </td>

                                <td>
                                    {{Form::open(['action' => ['EkstraController@destroy', $ext->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure")'])}}

                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-outline-danger btn-block' ])}}

                                    {{ Form::hidden('page', $res->id , ['class' => 'custom-file-input']) }}

                                    {{Form::close()}}
                                </td>
                            
                            </tr>

                      
                       @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endforeach




<script>
    $(document).ready(function(){
        $('.ExtraAll').hide();
    });//End of document ready 

    function openResExt(Id){
        $('#ExtraStart').hide('slow');
        $('#ExtraOne'+Id).show('slow');
    }

    function backExt(){
        $('.ExtraAll').hide('slow');
        $('#ExtraStart').show('slow');
    }
</script>