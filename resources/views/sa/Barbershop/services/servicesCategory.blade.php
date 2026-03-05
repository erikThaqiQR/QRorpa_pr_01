<?php   
    use App\Barbershop;
    use App\BarbershopCategory;

    $barbershopId = $_GET['barbershop'];
?>
<style>
    .addCategoryBtn{
        color:rgb(39, 190, 175);
        border:1px solid rgb(39, 190, 175);
        font-weight: bold;
        font-size: 17px;
    }
    .addCategoryBtn:hover{
        background-color:rgb(39, 190, 175);
        color: white;
        border:1px solid rgb(39, 190, 175);
        cursor: pointer;
    }
</style>








<!-- Add Category Modal -->
<div class="modal" id="addCategory" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> Add a Category </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

    
            {{Form::open(['action' => 'BarbershopCategoryController@store', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}

                {{csrf_field()}}
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label('Name', null, ['class' => 'control-label color-black']) }}
                        {{ Form::text('emri','', ['class' => 'form-control ', 'required']) }}
                    </div>

                    <div class="custom-file mb-3 mt-3">
                        {{ Form::label('Picture', null , ['class' => 'custom-file-label']) }}
                        {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile', 'required']) }}
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
        <h3 style="width:45%;" class="text-right color-qrorpa pr-4 pt-4"><strong>Category</strong></h3>
    </div>

    <button class="btn btn-block addCategoryBtn mt-3 mb-3" data-toggle="modal" data-target="#addCategory"> Add new Category </button>

    <hr>

    <div class="d-flex justify-content-between flex-wrap" id="bCatList">
        @foreach(BarbershopCategory::where('toBar', $barbershopId)->get()->sortByDesc('created_at') as $bCat)
            <img style="width:50%; height:60px;" src="storage/barbershop/CategoryUpload/{{$bCat->foto}}" alt="NotFound">
            <p style="width:40%; font-size:18px;" class="text-center pt-2"><strong>{{$bCat->emri}}</strong></p>
            <button style="width:5%;" class="btn btn-outline default" data-toggle="modal" data-target="#editCategory{{$bCat->id}}"><i class="far fa-2x fa-edit"></i></button>
            <button onclick="deleteBC('{{$bCat->id}}')" style="width:5%;" class="btn btn-outline default"><i class="far fa-2x fa-trash-alt"></i></button>
            <hr style="width:100%;">



















            <!-- Add Category Modal -->
            <div class="modal" id="editCategory{{$bCat->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"> Add a Category </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                
                        {{Form::open(['action' => 'BarbershopCategoryController@update', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}

                            {{csrf_field()}}
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="form-group">
                                    {{ Form::label('Name', null, ['class' => 'control-label color-black']) }}
                                    {{ Form::text('emri', $bCat->emri, ['class' => 'form-control ', 'required']) }}
                                </div>

                                <div class="custom-file mb-3 mt-3">
                                    {{ Form::label('Picture', null , ['class' => 'custom-file-label']) }}
                                    {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                                </div>
                            </div>
                            {{ Form::hidden('barbershop', $barbershopId , ['class' => 'form-control ']) }}
                            {{ Form::hidden('barbershopCID', $bCat->id , ['class' => 'form-control ']) }}

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
</section>
        <script>
            // Add the following code if you want the name of the file appear on select
            $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        </script>

<script>
                function deleteBC(bcId){
                    $.ajax({
						url: '{{ route("barCategory.BCDelete") }}',
						method: 'post',
						data: {
							id: bcId,
							_token: '{{csrf_token()}}'
						},
						success: () => {
							$("#bCatList").load(location.href+" #bCatList>*","");
						},
						error: (error) => {
							console.log(error);
							alert('Oops! Something went wrong')
						}
					});
                }
</script>