@extends('userSA.userSALayout')
@section('content')



<?php
    use App\Restorant;
    use App\TableQrcode;
    use Endroid\QrCode\QrCode;
?>



<style>
     .downloadOne{
        color:rgb(39,190,175);
    }
    .downloadOne:hover{
        text-decoration:none;
    }
    .delTab{
        opacity:0.3;
    }
    .delTab:hover{
        opacity:1;
        cursor: pointer;
    }
    .anchorMy{
        color: black;
        text-decoration: none;
    }
    .anchorMy:hover{
        color: rgb(39,190,175);
        text-decoration: none;
    }
</style>















<section class="d-flex" >

    <!-- Left Side -->
    <div style="width:10%;"></div>





    <!-- Middle   -->
    <div class="p-4" style="width:80%; border-bottom-left-radius:30px; border-bottom-right-radius:30px; background-color:white;">

        @if(session('error'))
            <div class="alert alert-danger">
                <strong>{{session('error')}}</strong>
            </div>
        @endif

        <div class="container-fluid">
            <div class="row">
                <div class="col-2">
                    <a href="{{route('homeConRegUserBox',['Res' => $_GET['Res']])}}" class="col-2 anchorMy pt-4" style="font-size:25px;"><strong> < Back </strong></a> 
                </div>
                <div class="col-6 text-left">
                    <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>"{{Restorant::find($_GET['Res'])->emri}}" / Tables</strong></p>
                </div>
                <div class="col-4 text-right">
                    <button style="width:100%;" class="b-qrorpa color-white pr-4 pl-4 pt-2 pb-2 br-25" data-toggle="modal" data-target="#addTable">
                        <strong>Register new Tables</strong>
                    </button>
                </div>
            </div>
            @if(session('success'))
                <div class="row mt-2">
                    <div class="col-12 alert alert-success text-center" style="font-weight:bold; font-size:19px;">
                        {{session('success')}}
                    </div>
                </div>
            @endif

        </div>

        <hr>
        <div class="d-flex">
            <div style="width:5%;"></div>
          
            <div style="width:90%;" class="d-flex flex-wrap justify-content-start" id="QRcodeTableSh">
            
                @foreach(TableQrcode::where('Restaurant', '=', $_GET['Res'])->get()->sortBy('tableNr') as $qrcode)
                    <div class="mb-2 mr-2 d-flex justify-content-between " style="background-color:rgb(250, 250, 248); width:19%;">
                        <a class="downloadOne" download="QRcode_Res{{$qrcode->Restaurant}}_t{{$qrcode->tableNr}}.png" href="storage/qrcode/{{$qrcode->path}}"
                            data-toggle="tooltip" data-placement="top" title="Download!" style="width:30%;">
                            <img  style="width:60px;" src="storage/qrcode/{{$qrcode->path}}" alt="123">
                        </a>
                        <span onclick="removeQRcode('{{$qrcode->id}}')" class="pl-2 pr-2 mt-3 delTab" style="width:15%; color:red;"><i class=" fas fa-2x fa-minus-circle"></i></span>
                        <span class="mt-3 color-qrorpa text-center" style="font-size:20px; width:55%;">Table: {{$qrcode->tableNr}}</span> 
                    </div>
                @endforeach
            </div>
            <div style="width:5%;"></div>
        </div>


    </div>
    <!-- Middle end  -->




    <!-- Right Side -->
    <div style="width:10%;"></div>

</section>




















    <!-- The Modal  Add Table-->
    <div class="modal pt-2" id="addTable"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
        <div class="modal-dialog" >
            <div class="modal-content" style="border-radius:30px;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title color-qrorpa"><strong>Neue Tisch hinzu</strong></h4>
                <button type="button" class="close" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
            </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {{Form::open(['action' => 'QRCodeController@store', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}

                        <div class="form-group">
                            {{ Form::label('Tisch Nummer (x or x-y,i,i-j or x-y,i-j) ', null, ['class' => 'control-label']) }}
                            {{ Form::text('tableNr','', ['class' => 'form-control', 'step' => '1', 'min' => '1', 'max' => '500']) }}
                        </div>

                        <div class="form-group">
                            <label for="kat">Wählen Sie das Restaurant</label>
                            <select name="restaurant" class="form-control">
                                <option value="0">Wählen Sie ein Restaurant...</option>
                                <?php
                                    if(!empty(Restorant::all())){
                                        foreach(Restorant::all() as $res){
                                            if(isset($_GET['Res']) && $_GET['Res'] == $res->id){
                                                echo '  <option selected value="'.$res->id.'">'.$res->emri.'</option> ';
                                            }else{
                                                echo '  <option value="'.$res->id.'">'.$res->emri.'</option> ';
                                            }
                                        }
                                    }
                                ?>  
                                            
                            </select>
                        </div>
                        @if(isset($_GET['Res']))
                            <?php $gotom=$_GET['Res'];?>
                            {{ Form::hidden('redirectTo',$gotom, ['class' => 'form-control']) }}
                        @else
                            {{ Form::hidden('redirectTo','none', ['class' => 'form-control']) }}
                        @endif

                        {{ Form::hidden('userSA','1', ['class' => 'form-control']) }}
                        <div class="form-group">
                            {{ Form::submit('Registrieren', ['class' => 'form-control btn btn-outline-primary']) }}
                        </div>

                    {{Form::close() }}
                </div>

            </div>
        </div>
    </div>








    <script>
         function removeQRcode(qId){
            $.ajax({
                url: '{{ route("table.destroy") }}',
                method: 'post',
                data: {
                    id: qId,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#QRcodeTableSh").load(location.href+" #QRcodeTableSh>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert('bitte aktualisieren und erneut versuchen!');
                }
            });
        }
    </script>

@endsection('content')
