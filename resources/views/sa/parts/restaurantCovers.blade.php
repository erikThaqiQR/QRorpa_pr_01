<link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
<?php
    use App\Orders;
    use App\Restorant;
                           

?>
<!DOCTYPE html>
<html lang="de" translate="no">
<head>
 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <meta name="csrf-token" content="{{ csrf_token() }}">


      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>



<style>
.checked {
  color: orange !important;
}
span.fa.fa-star{
  color: #464545;
}
.table{
  margin-top:50px;
}

</style>
</head>
<body>

  
<div class="container">
            <div class="col-md-12">
                   <button class="b-qrorpa color-white pr-3 pl-3 br-25" data-toggle="modal" data-target="#addCover">Cover hinzufügen</button>
            </div>
            <div class="col-md-12" style="float: left;">
               
                <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>Covers</strong></p><br>
                 <table class="table">
                    <thead>
                      <tr>
                        <th>Restaurant</th>
                        <th>Foto</th>
                        <th>Text</th>
                        <th>Link</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Aktionen</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($restaurantCovers as $resCovers)
                          <tr>
                            <td>
        
                                    {{$resCovers->res_id}}
                            

                            </td>
                            <td><img src="storage/ResBackgroundPic/{{$resCovers->image}}" style="width: 200px; height: 70px;"></td>
                            <td>{{$resCovers->text}}</td>
                            <td>{{$resCovers->link}}</td>
                            <td>{{$resCovers->position}}</td>
                            <td>
                                   {{Form::open(['action' => ['RestaurantCoversController@activateCover', $resCovers->id], 'method' => 'get']) }}

                                    <div class="form-group text-center">
                                   @if($resCovers->status == 0)
                                  {{--   <button class="btn btn-danger">Unveröffentlicht</button> --}}
                                  {{ Form::submit('Unveröffentlicht', ['class' => 'form-control btn btn-block btn-danger', 'style' => 'margin-top:5px; margin-bottom:-5px;','target'=>'_blank']) }}
                                       
                                    @elseif($resCovers->status == 1)
                                    {{ Form::submit('Veröffentlicht', ['class' => 'form-control btn btn-block btn-success', 'style' => 'margin-top:5px; margin-bottom:-5px;']) }}
                                      
                                  {{--   <button class="btn btn-success">Veröffentlicht</button> --}}
                                     @endif
                                    </div>

                                {{Form::close() }}

                            
                            </td>
                            <td style="display: inline-flex;">  <button class="btn btn-warning btn-sm" style="margin-right: 10px;" data-toggle="modal" data-target="#editCover{{$resCovers->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>



                             

                                    {{Form::open(['action' => ['RestaurantCoversController@destroy', $resCovers->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure")'])}}

                                    {{Form::hidden('_method', 'DELETE')}}
                                   {{ Form::button('<i class="fa fa-trash-o"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm'] )  }}
                                

                                    {{Form::close()}}
                               </td>
                          </tr>
                          @endforeach
                    </tbody>
                  </table>
                      
                 
            </div>

    
</div>
    <!-- The Modal  Add Cover-->
    <div class="modal pt-2" id="addCover"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style=" margin-top:20px;">
        <div class="modal-dialog" >
            <div class="modal-content" style="border-radius:30px;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title color-qrorpa"><strong>Cover hinzufügen</strong></h4>
                <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
            </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {{Form::open(['action' => 'RestaurantCoversController@store', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}
                        {{csrf_field()}}
                            <div class="form-group">
                                <label for="restaurant">Wählen Sie das Restaurant</label>
                                <select name="res_id" class="form-control" id="res_id">
                                    <option value="0">Alles</option>
                                    @foreach($restaurantsList as $restaurant)
                                        <option value="{{$restaurant->id}}">{{$restaurant->emri}}</option>
                                        @endforeach
                                                
                                </select>
                            </div>
                            <div class="custom-file mb-3 mt-3">
                                {{ Form::label('Foto', null , ['class' => 'custom-file-label']) }}
                                {{ Form::file('image', ['class' => 'custom-file-input', 'id'=> 'image']) }}
                            </div>
                        <div class="form-group">
                                {{ Form::label('Text', null, ['class' => 'control-label color-black']) }}
                                {{ Form::textarea('text','', ['class' => 'form-control ']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('Link', null, ['class' => 'control-label color-black']) }}
                                {{ Form::text('link','', ['class' => 'form-control ']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('Positionen', null, ['class' => 'control-label color-black']) }}
                                {{ Form::text('position','', ['class' => 'form-control ']) }}
                            </div>
                        
                            {{ Form::submit('Speichern', ['class' => 'form-control btn btn-success']) }}
                            
                    
                    {{Form::close() }}

                </div>

            </div>
        </div>
    </div>

@foreach($restaurantCovers as $resCovers)
    <!-- The Modal  Edit Cover-->
    <div class="modal pt-2" id="editCover{{$resCovers->id}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style=" margin-top:20px;">
    <div class="modal-dialog" >
        <div class="modal-content" style="border-radius:30px;">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title color-qrorpa"><strong>Cover editing</strong></h4>
            <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
        </div>

            <!-- Modal body -->
            <div class="modal-body">
               {{Form::open(['action' => ['RestaurantCoversController@update',  $resCovers->id], 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                        {{csrf_field()}}
                        <!-- Modal body -->
                        <div class="modal-body">
                              <div class="form-group">
                                    {{ Form::label('Restaurant',null , ['class' => 'control-label']) }}
                                    {{ Form::text('restaurant',$resCovers->res_id, ['class' => 'form-control']) }}
                                </div>
                                  <div class="custom-file mb-3 mt-3">
                                    {{ Form::label('Foto', null , ['class' => 'custom-file-label']) }}
                                    {{ Form::file('image', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                                </div>
                                  <div class="form-group">
                                        {{ Form::label('Text', null, ['class' => 'control-label color-black']) }}
                                        {{ Form::textarea('text',$resCovers->text , ['class' => 'form-control ']) }}
                                    </div>
                                <div class="form-group">
                                    {{ Form::label('Link', null , ['class' => 'control-label']) }}
                                    {{ Form::text('link', $resCovers->link , ['class' => 'form-control']) }}
                                </div>
                                 <div class="form-group">
                                {{ Form::label('Positionen', null, ['class' => 'control-label color-black']) }}
                                {{ Form::text('position',$resCovers->position, ['class' => 'form-control ']) }}
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            {{ Form::submit('Änderungen speichern', ['class' => 'form-control btn btn-success']) }}
                            
                        </div>
                    {{Form::close() }}

            </div>

        </div>
    </div>
    </div>

@endforeach
</body>
</html>