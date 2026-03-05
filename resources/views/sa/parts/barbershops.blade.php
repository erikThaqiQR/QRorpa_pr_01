        <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
<div class="container">
        <div class="d-flex justify-content-between">
            <div style="width:20%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Barbershops</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;">{{count($barbershops)}}</p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; opacity:0.65;"> Text test text test ... </p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; margin-bottom:-15px; opacity:0.65;"> Text test text test text test text ... </p>

            </div>

            <div style="width:20%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Today Sales</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;"><span style="opacity:0.7; font-size:20px;">CHF...</p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; opacity:0.65;"> Text test text test ... </p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; margin-bottom:-15px; opacity:0.65;"> Text test text test text test text ... </p>

            </div>

            <div style="width:20%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">New Barbershops</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;">...</p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; opacity:0.65;"> Text test text test ... </p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; margin-bottom:-15px; opacity:0.65;"> Text test text test text test text ... </p>

            </div>

            <div style="width:35%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Activity Sale</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;">---</p>
            

            </div>
        

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 text-left">
                <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>Barbershops</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <button class="b-qrorpa color-white pr-3 pl-3 br-25" data-toggle="modal" data-target="#addBarbershop">Barbershop hinzufügen</button>
            </div>
            <div class="col-3">
               {{--  <a href="{{route('barberservices.index')}}" class="b-qrorpa color-white pr-3 pl-3 br-25">Services</a> --}}
            </div>
        </div>
    </div>

    <br>
<div class="container">
      
       <table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Address</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  	 @foreach($barbershops as $barbershop)
    <tr>
    	
      <td>{{$barbershop->name}}</td>
      <td>{{$barbershop->address}}</td>
      <td> <button class="btn btn-info" data-toggle="modal" data-target="#editBarbershop{{$barbershop->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

      	{{Form::open(['action' => ['BarberShopController@destroy', $barbershop->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure")'])}}

                                {{Form::hidden('_method', 'DELETE')}}
                                {{ Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger', 'type' => 'submit']) }}

                                {{Form::close()}}

      </td>
     
    </tr>
   	@endforeach
  </tbody>
</table>
		
</div>



    <div class="modal pt-2" id="addBarbershop"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog" >
        <div class="modal-content" style="border-radius:30px;">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title color-qrorpa"><strong>Barbershop hinzufügen</strong></h4>
            <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
        </div>

            <!-- Modal body -->
            <div class="modal-body">
                {{Form::open(['action' => 'BarberShopController@store', 'method' => 'post']) }}

                    <div class="form-group">
                        {{ Form::label('Name', null, ['class' => 'control-label']) }}
                        {{ Form::text('name','', ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Adresse', null, ['class' => 'control-label']) }}
                        {{ Form::text('address','', ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::submit('Sparen', ['class' => 'form-control btn btn-outline-primary']) }}
                    </div>

                {{Form::close() }}
            </div>

        </div>
    </div>
    </div>


 @foreach($barbershops as $barbershop)
<div class="modal  fade " id="editBarbershop{{$barbershop->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content color-black">

	        <!-- Modal Header -->
	        <div class="modal-header">
	            <h4 class="modal-title">You are editing "{{$barbershop->name}}"</h4>
	            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
	            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
	        </div>

	        {{Form::open(['action' => ['BarberShopController@update', $barbershop->id], 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
	            {{csrf_field()}}    
	            <!-- Modal body -->
	            <div class="modal-body">
	                <div class="form-group">
	                    {{ Form::label('Name',null , ['class' => 'control-label']) }}
	                    {{ Form::text('name',$barbershop->name, ['class' => 'form-control']) }}
	                </div>
	            </div>
	              <div class="modal-body">
	                <div class="form-group">
	                    {{ Form::label('Address',null , ['class' => 'control-label']) }}
	                    {{ Form::text('address',$barbershop->address, ['class' => 'form-control']) }}
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