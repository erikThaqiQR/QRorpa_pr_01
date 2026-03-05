<div class="container p-2" id="KategoritStart">
    <div class="row">
        <div class="col-1 backBtn" onclick="back()">
              <img src="https://img.icons8.com/android/48/000000/back.png"/>
        </div>
        <div class="col-3 text-left">
            <p class="color-qrorpa mt-2" style="font-size:20px;"><strong>Category</strong></p>
        </div>

        <div class="col-8 text-right">
            <p class="color-qrorpa mt-2 mr-4" style="font-size:17px;"><strong>Select a restaurant to see the categories!</strong></p>
        </div>
    </div>

    <div class="row mt-5 ml-3">
        @foreach($restaurant as $res)
            <?php
            echo '<div class="col-2 text-center p-3 ResShow ml-4 mt-3" onclick="openResKat(\''.$res->id.'\')">';
            ?>
                {{$res->emri}}
            </div>
        @endforeach
    </div>
</div>





















































@foreach($restaurant as $res)
 <!-- addCatModal  -->

        <div class="modal  fade " id="addCatModal{{$res->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color:black;">Shkruni emrin e kategorise qe doni te shtoni.</h4>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>


                {{Form::open(['action' => 'KategoriController@store', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}

                    {{csrf_field()}}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            {{ Form::label('Emri', null, ['class' => 'control-label color-black']) }}
                            {{ Form::text('emri','', ['class' => 'form-control ']) }}
                        </div>

                        <div class="custom-file mb-3 mt-3">
                            {{ Form::label('Picture', null , ['class' => 'custom-file-label']) }}
                            {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
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

                        {{ Form::hidden('page', $res->id , ['class' => 'custom-file-input']) }}
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{ Form::submit('Ruaj', ['class' => 'form-control btn btn-primary']) }}
                        
                    </div>
                {{Form::close() }}

                </div>
            </div>
        </div>


        <script>
            // Add the following code if you want the name of the file appear on select
            $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        </script>

@endforeach












<!-- Edit modal's -->
@foreach($kat as $kategorit)

<div class="modal  fade " id="editKat{{$kategorit->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content color-black">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Jeni duke edituar kategorin "{{$kategorit->emri}}"</h4>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>

        {{Form::open(['action' => ['KategoriController@update', $kategorit->id], 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
            {{csrf_field()}}    
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group">
                    {{ Form::label('Emri i ri',null , ['class' => 'control-label']) }}
                    {{ Form::text('emri',$kategorit->emri, ['class' => 'form-control']) }}
                </div>
                <div class="custom-file mb-3 mt-3">
                    {{ Form::label('Picture', null , ['class' => 'custom-file-label']) }}
                    {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
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

<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>







































@foreach($restaurant as $res)
    <div class="container p-2 kategoriteAll" id="KategoriOne{{$res->id}}">
        <div class="row">
            <div class="col-12">
                @if(session('error'))
                    <div class="alert alert-danger">
                        <h4>{{session('error')}}</h4>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-1 backBtn" onclick="backKat()">
                <img src="https://img.icons8.com/android/48/000000/back.png"/>
            </div>
            <div class="col-3 text-left">
                <p class="color-qrorpa mt-2" style="font-size:20px;"><strong>Category</strong></p>
            </div>

            <div class="col-8 text-right">
                <p class="color-qrorpa mt-2 mr-4" style="font-size:17px;"><strong>{{$res->emri}}</strong></p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addCatModal{{$res->id}}">
                <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25">
                Shto nje Kategori</button>
            </div>
        </div>

        <div class="row mt-3" >
            <div class="col-12">
                <table class="table table-striped" style="background-color:white; border-bottom-left-radius:20px; border-bottom-right-radius:20px;">
                    @foreach($kat as $kategorit)
                        @if($kategorit->toRes == $res->id)
                        <tr>
                            @if($kategorit->foto == 'empty')
                            <td colspan="5"><img style="width:100%; height:100px; " src="storage/kategoriaUpload/bannerDef.png" alt=""></td>
                            @else
                            <td colspan="5"><img style="width:100%; height:100px; " src="storage/kategoriaUpload/{{$kategorit->foto}}" alt=""></td>
                            @endif
                            
                        </tr>
                        <tr>
                        
                            <td>{{$kategorit->id}}</td>
                            <td><h4>{{$kategorit->emri}}</h4></td>
                            <td>

                            <?php $step = 1 ;?>
                              
                            @foreach($thisKat as $Kat)
                                @if($Kat->toCat == $kategorit->id)
                                    @if($step++ == 1)
                                        <span><ins><strong>Extras :</strong> {{$Kat->emri}} <span style="opacity:0.65;">({{$Kat->qmimi}})</span></ins></span>
                                    @else
                                        <span><strong> >> </strong><ins>{{$Kat->emri}} <span style="opacity:0.65;">({{$Kat->qmimi}})</span></ins> </span>
                                    @endif
                                @endif
                            @endforeach
                            <br>
                            <?php $step = 1 ;?>
                            @foreach($types as $type)
                                @if($type->kategoria == $kategorit->id)
                                    @if($step++ == 1)
                                        <span><ins><strong>Types :</strong> {{$type->emri}} <span style="opacity:0.65;">({{$type->vlera}})</span></ins></span>
                                    @else
                                        <span><strong> >> </strong><ins>{{$type->emri}} <span style="opacity:0.65;">({{$type->vlera}})</span></ins> </span>
                                    @endif
                                @endif
                            @endforeach
                            
                            </td>

                            <td>
                                <button class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#editKat{{$kategorit->id}}">Edit</button>
                            </td>

                            <td>
                                {{Form::open(['action' => ['KategoriController@destroy', $kategorit->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure")'])}}

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
        $('.kategoriteAll').hide();
    });//End of document ready 

    function backKat(){
        $('.kategoriteAll').hide('slow');
        $('#KategoritStart').show('slow');
    }

    function openResKat(Id){
        $('#KategoritStart').hide('slow');
        $('#KategoriOne'+Id).show('slow');
    }
 
</script>




 
