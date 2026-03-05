<link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">


    <div class="container">
        <div class="row">
            <div class="col-12 text-left">
                <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>Barber Services</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <button class="b-qrorpa color-white pr-3 pl-3 br-25" data-toggle="modal" data-target="#addBarberService">Barber Service hinzufügen</button>
            </div>
        </div>
    </div>

    <br>
<div class="container">
      
       <table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Description</th>
      <th scope="col">Minutes</th>
      <th scope="col">Price</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
     @foreach($barberservices as $barberservice)
    <tr>
        
      <td>{{$barberservice->name}}</td>
      <td>{{$barberservice->description}}</td>
      <td>{{$barberservice->minutes}}</td>
      <td>{{$barberservice->price}}</td>
      <td> <button class="btn btn-info" data-toggle="modal" data-target="#editBarbershop{{$barberservice->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

        {{Form::open(['action' => ['BarberserviceController@destroy', $barberservice->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure")'])}}

                                {{Form::hidden('_method', 'DELETE')}}
                                {{ Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger', 'type' => 'submit']) }}

                                {{Form::close()}}

      </td>
     
    </tr>
    @endforeach
  </tbody>
</table>
        
</div>

<div class="modal pt-2" id="addBarberService"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog" >
        <div class="modal-content" style="border-radius:30px;">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title color-qrorpa"><strong>Barber Service hinzufügen</strong></h4>
            <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
        </div>

            <!-- Modal body -->
            <div class="modal-body">
                {{Form::open(['action' => 'BarberserviceController@store', 'method' => 'post']) }}

                    <div class="form-group">
                        {{ Form::label('Name', null, ['class' => 'control-label']) }}
                        {{ Form::text('name','', ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Description', null, ['class' => 'control-label']) }}
                        {{ Form::text('description','', ['class' => 'form-control']) }}
                    </div>
                     <div class="form-group">
                        {{ Form::label('Minutes', null, ['class' => 'control-label']) }}
                        {{ Form::text('minutes','', ['class' => 'form-control']) }}
                    </div>
                     <div class="form-group">
                        {{ Form::label('Price', null, ['class' => 'control-label']) }}
                        {{ Form::text('price','', ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::submit('Sparen', ['class' => 'form-control btn btn-outline-primary']) }}
                    </div>

                {{Form::close() }}
            </div>

        </div>
    </div>
    </div>


    @foreach($barberservices as $barberservice)
<div class="modal  fade " id="editBarbershop{{$barberservice->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content color-black">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">You are editing "{{$barberservice->name}}"</h4>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>

            {{Form::open(['action' => ['BarberserviceController@update', $barberservice->id], 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                {{csrf_field()}}    
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label('Name',null , ['class' => 'control-label']) }}
                        {{ Form::text('name',$barberservice->name, ['class' => 'form-control']) }}
                    </div>
                </div>
                  <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label('Description',null , ['class' => 'control-label']) }}
                        {{ Form::text('description',$barberservice->description, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label('Minutes',null , ['class' => 'control-label']) }}
                        {{ Form::text('minutes',$barberservice->minutes, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label('Price',null , ['class' => 'control-label']) }}
                        {{ Form::text('price',$barberservice->price, ['class' => 'form-control']) }}
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