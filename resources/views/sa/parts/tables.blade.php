<?php

use App\TableQrcodeTA;
    use App\TableQrcode;
    use App\Restorant;
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
</style>
<div style="padding:10px;">


    <!-- The Modal  Add Table-->
    <div class="modal pt-3" id="addTable"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
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
                            {{ Form::label('Tisch Nummer (x oder x-y,i-j) ', null, ['class' => 'control-label']) }}
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
                        <div class="form-group">
                            {{ Form::submit('Registrieren', ['class' => 'form-control btn btn-outline-primary']) }}
                        </div>
                    {{Form::close() }}
                </div>
            </div>
        </div>
    </div>





    <!-- The Modal  Add Table-->
    <div class="modal pt-3" id="addTableTA"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        <div class="modal-dialog" >
            <div class="modal-content" style="border-radius:30px;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title color-qrorpa"><strong>Neue Tisch hinzu</strong></h4>
                <button type="button" class="close" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
            </div>
                <!-- Modal body -->
                <div class="modal-body">
                    {{Form::open(['action' => 'QRCodeController@storeTA', 'method' => 'post']) }}
                        @if(isset($_GET['Res']))
                            <?php $gotom=$_GET['Res'];?>
                            {{ Form::hidden('redirectTo',$gotom, ['class' => 'form-control']) }}
                        @else
                            {{ Form::hidden('redirectTo','none', ['class' => 'form-control']) }}
                        @endif
                        <div class="form-group">
                            {{ Form::submit('Weitermachen', ['class' => 'form-control shadow-none btn btn-success', 'style' => 'font-weight:bold; height:fit-content;']) }}
                        </div>
                        <p class="pt-2 text-center" style="color:red;">
                            <strong>Ich bin sicher, dass ich den QR-Code des Imbiss-Service für dieses Restaurant erstellen/ändern möchte.</strong>
                        </p>
                    {{Form::close() }}
                </div>
            </div>
        </div>
    </div>






    <?php
        if(session('error')){
            echo '
            <div class="alert alert-danger">
                <strong>'.session('error').'</strong>
            </div>
            ';
        }
    ?>











    @if (!isset($_GET['Res']))
    <div class="d-flex flex-wrap justify-content-between">
            <p class="color-qrorpa" style="width:49.5%; font-size:25px; margin-bottom:-5px;"><strong>Tischen</strong></p>
            <button style="width:49.5%;" class="b-qrorpa color-white br-25" data-toggle="modal" data-target="#addTable"><strong>Neue Tisch hinzu</strong></button>
            @if(session('success'))
                <div class="mt-2 alert alert-success text-center" style="width:100%; font-weight:bold; font-size:19px;">
                    {{session('success')}}
                </div>
            @endif
        </div>
    @else
        <?php
            $theResData = Restorant::findOrFail($_GET['Res']);
        ?>
        <div class="d-flex flex-wrap justify-content-between">
            <p class="color-qrorpa" style="width:24.5%; font-size:25px; margin-bottom:-5px;"><strong>Tischen</strong></p>
            <p class="color-qrorpa text-center" style="width:24.5%; font-size:25px; margin-bottom:-5px;"><strong>{{$theResData->emri}} ({{$theResData->id}})</strong></p>
            <button style="width:24.5%;" class="b-qrorpa color-white br-25" data-toggle="modal" data-target="#addTable"><strong>Neue Tisch hinzu</strong></button>
            <button style="width:24.5%;" class="b-qrorpa color-white br-25" data-toggle="modal" data-target="#addTableTA"><strong>Neuer QR-Code von Takeaway</strong></button>

            @if(session('success'))
                <div class="mt-2 alert alert-success text-center" style="width:100%; font-weight:bold; font-size:19px;">
                    {{session('success')}}
                </div>
            @endif
        </div>
    @endif
  

   
    @if(!empty(Restorant::all()) && !isset($_GET['Res']))
        <div class="container allQRTables" id="QRTable{{$res->id}}">
        <hr>
            <div class="row">
            @foreach(Restorant::all()->sortByDesc('created_at') as $res)
                <div class="col-4" >
                    <a href="tables?Res={{$res->id}}" class="p-3 mb-2 btn btn-block" style="background-color:rgb(39,190,175); color:white; 
                        border-radius:15px;">
                        <span style="font-size:21px; font-weight:bold;">{{$res->emri}}</span>
                        <br>
                        <span style="font-size:12px; margin-top:-15px;"> {{$res->adresa}} </span>
                        <br>
                        <span style="font-size:16px; margin-top:-7px;"> {{TableQrcode::where('Restaurant', '=', $res->id)->get()->count()}} Tables </span>
                    </a>
                </div>
            @endforeach
            </div>
        </div>
    @elseif(isset($_GET['Res']))
    <hr>
    <div class="d-flex">
        <div style="width:5%;"></div>
        <div style="width:5%;">
            <a href="/tables">
                <i class="far fa-2x fa-arrow-alt-circle-left" style="color:rgb(39,190,175);"></i>
            </a>
        </div>
        <div style="width:80%;" class="d-flex flex-wrap justify-content-between" id="QRcodeTableSh">
            <?php $tablesQRC = TableQrcode::where('Restaurant', '=', $_GET['Res'])->orderBy('tableNr')->get(); ?>
            @if ( TableQrcodeTA::where('tableNr',500)->where('Restaurant', '=', $_GET['Res'])->first() != Null)
            <?php $qrcode = TableQrcodeTA::where('tableNr',500)->where('Restaurant', '=', $_GET['Res'])->first(); ?>
                <div class="mb-2 d-flex justify-content-between " style="background-color:rgb(250, 250, 248); width:100%;">
                    <a class="downloadOne" download="QRcode_Res{{$qrcode->Restaurant}}_t{{$qrcode->tableNr}}.png" href="storage/qrcode/{{$qrcode->path}}"
                        data-toggle="tooltip" data-placement="top" title="Download!">
                        <img  style="width:60px;" src="storage/qrcode/{{$qrcode->path}}" alt="123">
                    </a>
                    <span onclick="removeQRcode('{{$qrcode->id}}','{{$qrcode->tableNr}}')" class="pl-2 pr-2 mt-3 delTab" style="width:10%; color:red;"><i class=" fas fa-2x fa-minus-circle"></i></span>
                    <span class="mt-3 color-qrorpa text-center" style="font-size:20px; width:85%;">Takeaway-Service</span> 
                </div>
            @else
                <div class="d-flex justify-content-between " style="background-color:rgb(250, 250, 248); width:100%;">
                    <p style="width:100%; font-size:1.3rem; margin:0px;" class="text-center p-3"><strong>Es wurde noch kein QR-Code für Takeaway erstellt</strong></p>
                </div>
            @endif
            <hr style="width:100%;">
            @foreach($tablesQRC as $qrcode)
                @if ($qrcode->tableNr != 500)
                <div class="mb-2 d-flex justify-content-between " style="background-color:rgb(250, 250, 248); width:19.5%;">
                    <a class="downloadOne" download="QRcode_Res{{$qrcode->Restaurant}}_t{{$qrcode->tableNr}}.png" href="storage/qrcode/{{$qrcode->path}}"
                        data-toggle="tooltip" data-placement="top" title="Download!">
                        <img  style="width:60px;" src="storage/qrcode/{{$qrcode->path}}" alt="123">
                    </a>
                    <span onclick="removeQRcode('{{$qrcode->id}}','{{$qrcode->tableNr}}')" class="pl-2 pr-2 mt-3 delTab" style="width:10%; color:red;"><i class=" fas fa-2x fa-minus-circle"></i></span>
                    <span class="mt-3 color-qrorpa text-center" style="font-size:20px; width:85%;">Table: {{$qrcode->tableNr}}</span> 
                </div>
                @endif
            @endforeach
        </div>
        <div style="width:10%;"></div>
    </div>
    @else
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="color-qrorpa"><strong>Es sind noch keine Restaurants registriert!</strong></h3>
                </div>
            </div>
        </div>
        
    @endif
           
      


</div>


<script>
    function filterQR(resId){
        if(resId == 0){
            $('.allQRTables').show('slow');
        }else{
            $('.allQRTables').hide('slow');
            $('#QRTable'+resId).show('slow');
        }
    }



    function removeQRcode(qId,tNr){
        $.ajax({
			url: '{{ route("table.destroy") }}',
			method: 'post',
			data: {
				id: qId,
				tableN: tNr,
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