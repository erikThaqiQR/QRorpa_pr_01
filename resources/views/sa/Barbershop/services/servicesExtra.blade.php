<?php   
    use App\Barbershop;
    use App\BarbershopCategory;
    use App\BarbershopType;
    use App\BarbershopExtra;

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
</style>

















<!-- Add Type Modal -->
<div class="modal" id="addExtra" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> Add a new Extra </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

    
            {{Form::open(['action' => 'BarbershopExtraController@store', 'method' => 'post']) }}

                {{csrf_field()}}
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label('Name', null, ['class' => 'control-label color-black']) }}
                        {{ Form::text('emri','', ['class' => 'form-control ', 'required']) }}
                    </div>

                    <div class="form-group">
                        <label for="sel1">Select a category</label>
                            <select name="toCat" class="form-control" required>
                                @foreach(BarbershopCategory::where('toBar', $barbershopId)->get()->sortByDesc('created_at') as $bCat){
                                    <option value="{{$bCat->id}}">{{$bCat->emri}}</option>
                                @endforeach
                            </select>
                    </div>

                    <div class="form-group">
                        {{ Form::label('Preis', null, ['class' => 'control-label color-black']) }}
                        {{ Form::number('price','', ['class' => 'form-control ', 'min' => '0', 'step'=> '0.01', 'required',]) }}
                    </div>
                </div>

                {{ Form::hidden('barbershop', $barbershopId , ['class' => 'form-control ']) }}

                <!-- Modal footer -->
                <div class="modal-footer">
                    {{ Form::submit('Save', ['class' => 'form-control btn btn-primary']) }}
                    
                </div>
            {{Form::close() }}

    </div>
  </div>
</div>



























<section class="pl-4 pr-4 pt-4 pb-5">
    <div class="d-flex justify-content-between">
        <a style="width:10%;" href="{{route('barbershops.servicesSelBar', ['barbershop' => $barbershopId])}}">
            <img class="pt-3" src="https://img.icons8.com/android/48/000000/back.png"/>
        </a>
        <h3 style="width:45%;" class="color-qrorpa pt-4"><strong>{{Barbershop::find($barbershopId)->emri}}</strong></h3>
        <h3 style="width:45%;" class="text-right color-qrorpa pr-4 pt-4"><strong>Extra</strong></h3>
    </div>

    <button class="btn btn-block addTypeBtn mt-3 mb-3" data-toggle="modal" data-target="#addExtra"> Add a new Extra </button>

    <hr>

    <div class="d-flex justify-content-between flex-wrap">
        @foreach(BarbershopCategory::where('toBar', $barbershopId)->get()->sortByDesc('created_at') as $bCat)
            <div style="width:32%;" class="catTypeBlock p-3 d-flex flex-wrap justify-content-between" id="catDiv{{$bCat->id}}">
                <h3  style="width:100%;" class="text-center"><strong>{{$bCat->emri}}</strong></h3>
                <br>
                <p style="width:20%" class="text-right pr-3"><strong>Preis</strong></p>
                <p style="border:1px solid lightgray; width:1%;"></p>
                <p style="width:79%" class="pl-2"><strong>Name</strong></p>
                <hr style="width:100%; margin-top:-17px;">
                @foreach(BarbershopExtra::where([['toBar', $barbershopId],['kategoria', $bCat->id]])->get()->sortByDesc('created_at') as $ext)
                    <p style="width:20%" class="text-right pr-3">{{$ext->qmimi}} <sup>CHF</sup></p>
                    <p style="border:1px solid lightgray; width:1%;"></p>
                    <p style="width:64%"  class="pl-2">{{$ext->emri}}</p>
                    <button style="width:5%;" class="btn btn-outline default" data-toggle="modal" data-target="#editType{{$ext->id}}">
                        <i class="far fa-xl fa-edit"></i>
                    </button>
                    <button onclick="deleteBE('{{$ext->id}}','{{$bCat->id}}')" style="width:5%;" class="btn btn-outline default">
                        <i class="far fa-xl fa-trash-alt"></i>
                    </button>







                    



                    
                        <!-- Edit Type Modal -->
                        <div class="modal" id="editType{{$ext->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title"> Edit Type </h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                                    {{Form::open(['action' => 'BarbershopExtraController@update', 'method' => 'post']) }}

                                        {{csrf_field()}}
                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <div class="form-group">
                                                {{ Form::label('Name', null, ['class' => 'control-label color-black']) }}
                                                {{ Form::text('emri', $ext->emri, ['class' => 'form-control ', 'required']) }}
                                            </div>

                                            <div class="form-group">
                                                <label for="sel1">Select a category</label>
                                                    <select name="toCat" class="form-control" required>
                                                        @foreach(BarbershopCategory::where('toBar', $barbershopId)->get()->sortByDesc('created_at') as $bCatList)
                                                            @if($ext->kategoria == $bCatList->id)
                                                                <option value="{{$bCatList->id}}" selected>{{$bCatList->emri}}</option>
                                                            @else
                                                                <option value="{{$bCatList->id}}">{{$bCatList->emri}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                            </div>

                                            <div class="form-group">
                                                {{ Form::label('Preis', null, ['class' => 'control-label color-black']) }}
                                                {{ Form::number('price', $ext->qmimi, ['class' => 'form-control ', 'min' => '0', 'step'=> '0.01', 'required',]) }}
                                            </div>
                                        </div>

                                        {{ Form::hidden('barbershop', $barbershopId , ['class' => 'form-control ']) }}
                                        {{ Form::hidden('extraId', $ext->id , ['class' => 'form-control ']) }}

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            {{ Form::submit('Save', ['class' => 'form-control btn btn-primary']) }}
                                            
                                        </div>
                                    {{Form::close() }}

                            </div>
                        </div>
                        </div>




























                @endforeach
            </div> 
        @endforeach
    </div>


</section>





<script>
    function deleteBE(eId,bId){
        $.ajax({
						url: '{{ route("barExtra.BEDelete") }}',
						method: 'post',
						data: {
							id: eId,
							_token: '{{csrf_token()}}'
						},
						success: () => {
							$("#catDiv"+bId).load(location.href+" #catDiv"+bId+">*","");
						},
						error: (error) => {
							console.log(error);
							alert('Oops! Something went wrong')
						}
					});
    }
</script>