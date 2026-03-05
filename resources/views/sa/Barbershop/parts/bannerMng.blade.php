<?php
    use App\Barbershop;
    use App\BarbershopBanner;
?>
<style>
    .btnQrorpa{
        border:1px solid rgb(39,190,175);
        color: rgb(39,190,175);
    }
    .btnQrorpa:hover{
        border:1px solid rgb(39,190,175);
        background-color: rgb(39,190,175);
        color:white;
    }
</style>

<section class="pt-3 pl-4 pr-4 pb-5">
   <h3 style="color:rgb(39,190,175);"><strong>Banner-Kontrollseite</strong></h3>

   <button  class="btnQrorpa btn btn-block" data-toggle="modal" data-target="#addABannerBar"><strong>Fügen Sie ein neues Banner hinzu</strong></button>

   <div class="container mt-3" id="allTheBarBanners">
        <div class="row">
            <div class="col-12 d-flex">
                <p style="width:10%;" class="text-center"><strong>Position</strong></p>
                <p style="width:10%;" class="text-center"><strong>Zum ?</strong></p>
                <p style="width:30%;" class="text-center"><strong>Foto</strong></p>
                <p style="width:30%;" class="text-center"><strong>Text / Link</strong></p>
                <p style="width:20%;" class="text-center"></p>
            </div>
            <div class="col-12">
                <hr>
            </div>
            @foreach(BarbershopBanner::all()->sortBy('B_position') as $barB)
                <div class="col-12 d-flex">
                    <p style="width:10%;" class="text-center pt-4"><strong>{{$barB->B_position}}</strong></p>
                    @if($barB->Bar_id != 0)
                        <p style="width:10%;" class="text-center pt-4"><strong>{{$barB->Bar_id}}</strong></p>
                    @else
                        <p style="width:10%;" class="text-center pt-4"><strong>Alle</strong></p>
                    @endif
                    <p style="width:30%;" class="text-center"><img src="storage/BarBackgroundPic/{{$barB->B_pic}}" style="width:100%; height:100px;" alt=""></p>
                    <p style="width:30%;" class="text-center pt-3"><strong>{{$barB->B_text}} <br> {{$barB->B_link}} </strong></p>
                    
                    <div style="width:20%;" class="text-center d-flex justify-content-between">
                        <button style="width:49%;" class="btn btn-outline-danger mt-4 mb-4" onclick="deleteBarBanner('{{$barB->id}}')">Löschen</button>
                        <button style="width:49%;" class="btn btn-outline-info mt-4 mb-4" data-toggle="modal" data-target="#editABannerBar{{$barB->id}}">Redigieren</button>
                    </div>
                </div>
            @endforeach
        </div>
   </div>
</section>



<script>
    function deleteBarBanner(bbId){
        $.ajax({
			url: '{{ route("restaurantCovers.deleteBarbershop") }}',
			method: 'post',
			data: {
				id: bbId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#allTheBarBanners").load(location.href+" #allTheBarBanners>*","");
			},
			error: (error) => {
				console.log(error);
				alert('bitte aktualisieren und erneut versuchen!');
			}
		});
    }
</script>




  <!-- The Modal  Add Cover-->
  <div class="modal pt-2" id="addABannerBar"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style=" margin-top:20px;">
        <div class="modal-dialog" >
            <div class="modal-content" style="border-radius:30px;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title color-qrorpa"><strong>Banner hinzufügen</strong></h4>
                <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
            </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {{Form::open(['action' => 'RestaurantCoversController@storeBarbershop', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}
                        {{csrf_field()}}
                            <div class="form-group">
                                <label for="restaurant">Wählen Sie einen Friseurladen</label>
                                <select name="bar_id" class="form-control" id="res_id">
                                    <option value="0">Alles</option>
                                    @foreach(Barbershop::all() as $bar)
                                        <option value="{{$bar->id}}">{{$bar->emri}}</option>
                                    @endforeach
                                                
                                </select>
                            </div>
                            <div class="custom-file mb-3 mt-3">
                                {{ Form::label('Foto', null , ['class' => 'custom-file-label']) }}
                                {{ Form::file('image', ['class' => 'custom-file-input', 'id'=> 'image' , 'required']) }}
                            </div>
                        <div class="form-group">
                                {{ Form::label('Text', null, ['class' => 'control-label color-black']) }}
                                {{ Form::textarea('text','', ['class' => 'form-control ' , 'rows' => '2' , 'required']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('Link', null, ['class' => 'control-label color-black']) }}
                                {{ Form::text('link','', ['class' => 'form-control ' , 'required']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('Positionen', null, ['class' => 'control-label color-black']) }}
                                {{ Form::text('position','', ['class' => 'form-control ' , 'required']) }}
                            </div>
                        
                            {{ Form::submit('Speichern', ['class' => 'form-control btn btn-success']) }}
                            
                    
                    {{Form::close() }}

                </div>

            </div>
        </div>
    </div>




    @foreach(BarbershopBanner::all()->sortBy('B_position') as $barB)

        <!-- The Modal  Edit Cover-->
        <div class="modal pt-2" id="editABannerBar{{$barB->id}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style=" margin-top:20px;">
                <div class="modal-dialog" >
                    <div class="modal-content" style="border-radius:30px;">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title color-qrorpa"><strong>Banner hinzufügen</strong></h4>
                        <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
                    </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            {{Form::open(['action' => 'RestaurantCoversController@editBarbershop', 'method' => 'post']) }}
                                {{csrf_field()}}
                                    <div class="form-group">
                                        <label for="restaurant">Wählen Sie einen Friseurladen</label>
                                        <select name="bar_id" class="form-control" id="res_id">
                                            <option value="0">Alles</option>
                                            @foreach(Barbershop::all() as $bar)
                                                <option value="{{$bar->id}}">{{$bar->emri}}</option>
                                            @endforeach
                                                        
                                        </select>
                                    </div>
                                <div class="form-group">
                                        {{ Form::label('Text', null, ['class' => 'control-label color-black']) }}
                                        {{ Form::textarea('text', $barB->B_text , ['class' => 'form-control ' , 'rows' => '2' , 'required']) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('Link', null, ['class' => 'control-label color-black']) }}
                                        {{ Form::text('link', $barB->B_link , ['class' => 'form-control ' , 'required']) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('Positionen', null, ['class' => 'control-label color-black']) }}
                                        {{ Form::text('position', $barB->B_position , ['class' => 'form-control ' , 'required']) }}
                                    </div>

                                    {{ Form::hidden('id', $barB->id , ['class' => 'form-control ']) }}
                                
                                    {{ Form::submit('Speichern', ['class' => 'form-control btn btn-success']) }}
                                    
                            
                            {{Form::close() }}

                        </div>

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