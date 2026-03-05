@extends('userSA.userSALayout')
@section('content')



<?php
    use App\Restorant;
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

        .newResBtn{
            border:2px solid rgb(39,190,175);
            color:rgb(39,190,175);
            font-size: 20px;
        }
        .newResBtn:hover{
            border:2px solid rgb(39,190,175);
            background-color:rgb(39,190,175);
            color:white;
        }
</style>















<section class="d-flex" >

    <!-- Left Side -->
    <div style="width:10%;"></div>


    <!-- Middle   -->
    <div class="p-4" style="width:80%; border-bottom-left-radius:30px; border-bottom-right-radius:30px; background-color:white;">

        <div class="container-fluid mb-4 " id="SAmanage">
            <div class="row mt-2">
                <div class="col-3"></div>
                <div class="col-6 text-center">
                    <p style="font-size:45px;" class="color-qrorpa">Please select a restaurant to</p>
                    <p style="font-size:45px; margin-top:-40px;" class="color-qrorpa">start editing</p>
                </div>
                <div class="col-3"></div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <button data-toggle="modal" data-target="#addRes" class="btn btn-block text-center newResBtn mb-3 ">Add a new Restaurant</button>
                </div>
            </div>

         

            <div class="row">
                @if(Restorant::where('accessID',Auth::user()->id)->count() > 0)
                    <div class="col-12 d-flex flex-wrap justify-content-between">
                        @foreach(Restorant::where('accessID',Auth::user()->id)->get()->sortByDesc('created_at') as $theRess)
                            <a style="width:16%;" href="{{route('homeConRegUserBox',['Res' => $theRess->id])}}">
                                <div style="background-color:rgb(39,190,175); border-radius:15px; color:white;" class="p-3 text-center mb-2">
                                    <h3>{{$theRess->emri}}</h3>
                                    <p>{{$theRess->adresa}}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="col-12 text-center">
                       <h1 class="color-qrorpa"><strong>You do not have anu restorants for the moment ! </strong></h1> 
                    </div>
                @endif
            </div>
        </div>

    </div>
    <!-- Middle end  -->

    <!-- Right Side -->
    <div style="width:10%;"></div>

</section>










    <!-- The Modal  Add restorant-->
    <div class="modal pt-2" id="addRes"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
        
        <div class="modal-dialog" >
            <div class="modal-content" style="border-radius:30px;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title color-qrorpa"><strong>Restaurant hinzufügen</strong></h4>
                <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
            </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {{Form::open(['action' => 'RestorantController@store', 'method' => 'post']) }}

                        <div class="form-group">
                            {{ Form::label('Name', null, ['class' => 'control-label']) }}
                            {{ Form::text('emri','', ['class' => 'form-control']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Adresse', null, ['class' => 'control-label']) }}
                            {{ Form::text('adresa','', ['class' => 'form-control']) }}
                        </div>

                        {{ Form::hidden('userID',Auth::user()->id, ['class' => 'form-control']) }}

                        <div class="form-group">
                            {{ Form::submit('Registrieren', ['class' => 'form-control btn btn-outline-primary']) }}
                        </div>

                    {{Form::close() }}
                </div>

            </div>
        </div>
    </div>


@endsection('content')