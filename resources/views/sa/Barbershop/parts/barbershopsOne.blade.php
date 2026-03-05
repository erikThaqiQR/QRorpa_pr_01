<?php
    use App\Restorant;
    use App\Orders;
    use App\Produktet;
    use App\TableQrcode;
    use App\kategori;

    use App\Barbershop;
use App\barbershopWorkingH;

    $thisBarbershop = Barbershop::find($_GET['barbershop']);
    $theBarId= $_GET['barbershop'];
    $orders = Orders::all();

    $dateNow = date('Y-m-d');

   
    $todaySales = 0;
    foreach($orders as $orderSot){
        if(explode(' ',$orderSot->created_at)[0] == $dateNow)
            $todaySales += $orderSot->shuma;
    }
?>
<style>
    .listRes:hover{
        cursor:pointer;
    }
    .noBorderIn{
        outline: none;
        box-shadow:none !important;
    }

    .qrorpaBtn{
        border:1px solid rgb(39,190,175);
        color: rgb(39,190,175);
        font-weight: bold;
    }
    .qrorpaBtn:hover{
        border:1px solid rgb(39,190,175);
        color:white;
        background-color: rgb(39,190,175);
        font-weight: bold;
    }
</style>
<input type="hidden" value="{{$_GET['barbershop']}}" id="theBar">

















<section style="padding:10px;">


    <div class="d-flex justify-content-between">
        <div class="backAllRes" style="width:5%;">
            <a href="{{route('barbershops.indexBarbershops', ['barbershop'])}}">
            <img class="pt-3" src="https://img.icons8.com/android/48/000000/back.png"/>
            </a>
        </div>
        <div style="margin-left:-30px; width:25%;">
            <div class="container" style="width:100%; background-color:rgb(247, 247, 240); border-radius:25px;" >
                <div class="row p-2">
                    <div class="col-4">
                        <img width="100%" src="storage/icons/Logo.png" alt="">
                    </div>
                    <div class="col-8">
                        <p class="color-qrorpa mt-2" style="font-size:21px;"><strong>{{$thisBarbershop->emri}}</strong> </p>
                        <p style="opacity:0.55; margin-top:-10px; font-size:14px;">
                            <strong>{{($thisBarbershop->adresas == 'empty' ? '---' : $thisBarbershop->adresa)}}</strong>
                            <i class="btn ml-3 fas fa-xl fa-edit" data-toggle="modal" data-target="#editBarAddress"></i>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div style="width:7%;">
                </div>

                <div style="width:17%; " class="b-qrorpa br-30">
                    <div class="p-4"> 
                        <p class="color-white"><strong> Cash Payment Clicks : xx </strong></p>
                        <p class="color-white" style="margin-top:-13px;"><strong> Cash Payment Orders : xx</strong></p>
                    </div>
                </div>
                <div style="width:17%;" class="b-qrorpa br-30">
                    <div class="p-4"> 
                        <p class="opacity-65 color-white">Email</p>
                        <p class="color-white" style="margin-top:-18px; margin-bottom:-18px;">Example@gmail.com</p>
                    </div>
                </div>
                <div style="width:17%;" class="b-qrorpa br-30">
                    <div class="p-4"> 
                        <p class="opacity-65 color-white">Social Media</p>
                        <p class="color-white" style="margin-top:-18px; margin-bottom:-18px;">...</p>
                    </div>
                </div>

                <div style="width:2%;">
                </div>
    </div><!-- End first row  -->

    <div class="d-flex justify-content-between mt-3" id="barDesc">
        @if(Barbershop::find($_GET['barbershop'])->barDesc == 'none')
        <input type="text" class="pl-4 noBorderIn" style="width:100%; border:1px solid rgb(39,190,175); border-radius:10px; padding-top:10px; padding-bottom:10px;"
            onkeyup="showSaveBtnBarDesc()" placeholder="Beschreibung des Friseursalons" id="barDescInput">
        @else
        <input type="text" class="pl-4 noBorderIn" style="width:100%; border:1px solid rgb(39,190,175); border-radius:10px; padding-top:10px; padding-bottom:10px;"
            onkeyup="showSaveBtnBarDesc()" placeholder="Beschreibung des Friseursalons" value="{{Barbershop::find($_GET['barbershop'])->barDesc}}" id="barDescInput">
        @endif
        <button class="btn qrorpaBtn" style="width:14%; display:none;" id="barDescSaveBtn" onclick="saveBarDesc('{{$theBarId}}')" >Sparen</button>
    </div>

    <div class="d-flex justify-content-start mt-3"><!-- second row  -->

        <div style="width:19.5%; background-color:rgb(247, 247, 240);  border-radius:25px;" class="p-3 text-center" data-toggle="modal" data-target="#setLogo">
            @if(Barbershop::find($_GET['barbershop'])->bPic == 'none')
                <img src="storage/icons/Asset 28800.png" style="width:50%;" alt="">
            @else
                <img src="storage/barbershopLogo/{{Barbershop::find($_GET['barbershop'])->bPic}}" style="width:50%; border-radius:50%;" alt="">
            @endif
            <h3 class="color-qrorpa mt-2"><strong>das Logo</strong></h3>
        </div>
        <div style="width:19.5%; background-color:rgb(247, 247, 240); border-radius:25px;" class="p-3 text-center ml-2" data-toggle="modal" data-target="#workingHours">
            <img src="storage/icons/restorantetWHicon.png" style="width:50%; border-radius:50%;" alt="">
            
            <h3 class="color-qrorpa mt-2"><strong>Arbeitszeit</strong></h3>
        </div>

    
    </div><!-- End second row  -->


    <br>
</section>







    <!-- Edit Barbershop Address Modal -->
    <div class="modal" id="editBarAddress" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style=" margin-top:50px; background-color:rgb(0,0,0,0.6);">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header" style="background-color:rgb(39,190,175);">
                    <h4 style="color:white;" class="modal-title"><strong>{{$thisBarbershop->emri}} Adresse</strong></h4>
                    <button style="color:white;" type="button" class="close" data-dismiss="modal">X</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {{Form::open(['action' => 'BarberShopController@updateBarAddress', 'method' => 'post']) }}
                        <div class="form-group" style="width:100%;">
                            {{ Form::label('Adresse', null , ['class' => 'control-label']) }}
                            {{ Form::text('adresaNew', $thisBarbershop->adresa ,['class' => 'form-control noBorderIn', 'style' => 'font-weight:bold;' , 'required']) }}
                        </div>
                        <input type="hidden" name="barId" value="{{$theBarId}}">
                        <div class="form-group" style="width:100%;">
                            {{ Form::submit('Speichern', ['class' => 'form-control btn qrorpaBtn' , 'style' => 'width:100%;']) }}
                        </div>

                        @if($thisBarbershop->map != 'none')
                            <hr>
                            <button type="button" onclick="removeGoogleMap('{{$theBarId}}')" class="btn btn-block btn-danger"><strong>Entfernen Sie die Karte aus maps.google</strong></button>
                        @endif 
                    {{Form::close() }}
                </div>
            
            </div>
        </div>
    </div>


    <script>
        function removeGoogleMap(barId){
            $.ajax({
                url: '{{ route("barbershops.BarRemoveGoogleMap") }}',
                method: 'post',
                data: {
                    barId: barId,
                    _token: '{{csrf_token()}}'
                },
                success: () => { $("#editBarAddress").load(location.href+" #editBarAddress>*",""); },
                error: (error) => {
                    console.log(error);
                    alert('bitte aktualisieren und erneut versuchen!');
                }
            });
        }
    </script>



























<script>
    function showSaveBtnBarDesc(){
        $('#barDescInput').css('width' , '85%');
        $('#barDescSaveBtn').show(1);
    }

    function saveBarDesc(bId){
        $.ajax({
			url: '{{ route("barbershops.setBarDesc") }}',
			method: 'post',
			data: {
                id: bId,
                desc: $('#barDescInput').val(),
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#barDesc").load(location.href+" #barDesc>*","");
			},
			error: (error) => {
				console.log(error);
				alert('bitte aktualisieren und erneut versuchen!');
			}
		});
    }
</script>















































<!-- set the logo Modal -->
<div class="modal" id="setLogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style=" margin-top:50px; background-color:rgb(0,0,0,0.6);">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color:rgb(39,190,175);"> 
                <h4 class="modal-title color-white"><strong>Barbershop-Logo</strong></h4>
                <button type="button" class="close color-white" data-dismiss="modal">X</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body text-center">
                @if(Barbershop::find($_GET['barbershop'])->bPic == 'none')
                    <img src="storage/icons/Asset 28800.png" style="width:40%;" alt="">
                @else
                    <img src="storage/barbershopLogo/{{Barbershop::find($_GET['barbershop'])->bPic}}" style="width:50%; border-radius:50%;" alt="">
                @endif
                {{Form::open(['action' => 'BarberShopController@setBarLogo', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}
                    {{csrf_field()}}
                    <div class="custom-file mb-3 mt-3">
                        {{ Form::label('Foto', null , ['class' => 'custom-file-label']) }}
                        {{ Form::file('foto', ['class' => 'custom-file-input', 'required']) }}
                    </div>

                    {{ Form::hidden('id', $_GET['barbershop'] , ['class' => 'form-control ']) }}

                    <button type="submit" class="btn btn-qrorpa" >Sparen</button>
                {{Form::close() }}
            </div>
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

























              



            <!-- working Hour Modal -->
            <div class="modal" id="workingHours" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style=" margin-top:50px; background-color:rgb(0,0,0,0.6);">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header" style="background-color:rgb(39,190,175);">
                    <h4 class="modal-title color-white"><strong>Arbeitszeit</strong></h4>
                    <button type="button" class="close" data-dismiss="modal">X</button>
                </div>

                <?php $RWH = barbershopWorkingH::where('toBar',$_GET['barbershop'])->first(); ?>

                <!-- Modal body -->
                <div class="modal-body">
                        
                        <div class="d-flex flex-wrap justify-content-between">
                            <div style="width:64%;" >
                                <div style="width:100%" class="d-flex flex-wrap justify-content-between" id="ResWorkingHList">

                                    <p style="width:30%" class="text-center"><strong>Montag</strong></p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D1Starts1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D1In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D1Starts1}}" type="text" maxlength="5" minlength="5" id="D1In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D1End1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D1Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D1End1}}" type="text" maxlength="5" minlength="5" id="D1Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <p style="width:4%" class="text-center"> und </p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D1Starts2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D1In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D1Starts2}}" type="text" maxlength="5" minlength="5" id="D1In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D1End2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D1Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D1End2}}" type="text" maxlength="5" minlength="5" id="D1Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <!-- <button style="width:20%;" onclick="updateWH('D1')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button> -->
                                    <p style="width:20%;"></p>




                                    <p style="width:30%" class="text-center"><strong>Dienstag</strong></p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D2Starts1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D2In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D2Starts1}}" type="text" maxlength="5" minlength="5" id="D2In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D2End1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D2Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D2End1}}" type="text" maxlength="5" minlength="5" id="D2Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <p style="width:4%" class="text-center"> und </p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D2Starts2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D2In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D2Starts2}}" type="text" maxlength="5" minlength="5" id="D2In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D2End2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D2Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D2End2}}" type="text" maxlength="5" minlength="5" id="D2Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <!-- <button style="width:20%;" onclick="updateWH('D2')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button> -->
                                    <p style="width:20%;"></p>





                                    <p style="width:30%" class="text-center"><strong>Mittwoch</strong></p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D3Starts1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D3In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D3Starts1}}" type="text" maxlength="5" minlength="5" id="D3In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D3End1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D3Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D3End1}}" type="text" maxlength="5" minlength="5" id="D3Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <p style="width:4%" class="text-center"> und </p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D3Starts2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D3In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D3Starts2}}" type="text" maxlength="5" minlength="5" id="D3In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D3End2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D3Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D3End2}}" type="text" maxlength="5" minlength="5" id="D3Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <!-- <button style="width:20%;" onclick="updateWH('D3')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button> -->
                                    <p style="width:20%;"></p>




                                    <p style="width:30%" class="text-center"><strong>Donnerstag</strong></p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D4Starts1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D4In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D4Starts1}}" type="text" maxlength="5" minlength="5" id="D4In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D4End1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D4Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D4End1}}" type="text" maxlength="5" minlength="5" id="D4Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <p style="width:4%" class="text-center"> und </p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D4Starts2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D4In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D4Starts2}}" type="text" maxlength="5" minlength="5" id="D4In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D4End2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D4Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D4End2}}" type="text" maxlength="5" minlength="5" id="D4Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <!-- <button style="width:20%;" onclick="updateWH('D4')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button> -->
                                    <p style="width:20%;"></p>





                                    <p style="width:30%" class="text-center"><strong>Freitag</strong></p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D5Starts1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D5In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D5Starts1}}" type="text" maxlength="5" minlength="5" id="D5In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D5End1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D5Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D5End1}}" type="text" maxlength="5" minlength="5" id="D5Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <p style="width:4%" class="text-center"> und </p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D5Starts2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D5In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D5Starts2}}" type="text" maxlength="5" minlength="5" id="D5In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D5End2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D5Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D5End2}}" type="text" maxlength="5" minlength="5" id="D5Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <!-- <button style="width:20%;" onclick="updateWH('D5')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button> -->
                                    <p style="width:20%;"></p>




                                    <p style="width:30%" class="text-center"><strong>Samstag</strong></p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D6Starts1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D6In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D6Starts1}}" type="text" maxlength="5" minlength="5" id="D6In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D6End1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D6Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D6End1}}" type="text" maxlength="5" minlength="5" id="D6Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <p style="width:4%" class="text-center"> und </p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D6Starts2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D6In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D6Starts2}}" type="text" maxlength="5" minlength="5" id="D6In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D6End2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D6Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D6End2}}" type="text" maxlength="5" minlength="5" id="D6Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <!-- <button style="width:20%;" onclick="updateWH('D6')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button> -->
                                    <p style="width:20%;"></p>




                                    <p style="width:30%" class="text-center"><strong>Sonntag</strong></p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D7Starts1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D7In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D7Starts1}}" type="text" maxlength="5" minlength="5" id="D7In1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D7End1 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D7Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D7End1}}" type="text" maxlength="5" minlength="5" id="D7Out1" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <p style="width:4%" class="text-center"> und </p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D7Starts2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D7In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D7Starts2}}" type="text" maxlength="5" minlength="5" id="D7In2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                        <p style="width:3%" class="text-center">-</p>
                                        <div style="width:10%" class="form-group">
                                            @if($RWH == null || $RWH->D7End2 == 'none')
                                            <input class="form-control text-center noBorderIn" type="text" maxlength="5" minlength="5" id="D7Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @else
                                            <input class="form-control text-center noBorderIn" value="{{$RWH->D7End2}}" type="text" maxlength="5" minlength="5" id="D7Out2" style="border:none; border-bottom:1px solid lightgray;">
                                            @endif
                                        </div>
                                    <!-- <button style="width:20%;" onclick="updateWH('D7')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button> -->
                                    <p style="width:20%;"></p>

                                



                        


                                    <button style="width:100%; " onclick="updateWHAll()"  class="mb-3 mt-3 btn btn-block btn-outline-dark">Alle aktualisieren</button>

                                </div>
                            </div>




                            <!-- map -->
                            <?php $theBar = $_GET['barbershop']; ?>
                            <div style="width:35%;" id="barbershopMapDiv">
                                @if(Barbershop::find($theBar)->map != 'none')
                                <iframe src="{{Barbershop::find($theBar)->map}}"
                                    width="100%" height="83%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                @else
                                <iframe width='100%' height="83%" frameborder='0' scrolling='no' marginheight='0' marginwidth='0'
                                    src='https://maps.google.com/maps?&amp;q="+ {{Barbershop::find($theBar)->adresa}} + "&amp;hl=de&amp;output=embed'></iframe>
                                @endif
                                    <button style="width:100%;" data-toggle="modal" data-target="#EditMapModalBar" class="mb-3 mt-3 btn btn-block btn-outline-dark">
                                        Aktualisieren/Einstellen Karte
                                    </button>
                            </div>

                            <div style="width:100%; display:none;" class="alert alert-success text-center" id="barWorkingHsuccess">
                                Die neuen Arbeitsstunden wurden erfolgreich gespeichert
                            </div>
                        </div>
                </div>

                </div>
            </div>
            </div>




            

                <!-- The Modal -->
                <div class="modal" id="EditMapModalBar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                    style=" margin-top:150px; background-color:rgb(0,0,0,0.6);">

                    <div class="modal-dialog">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header" style="background-color:rgb(39,190,175);">
                                <h4 class="modal-title">Aktualisieren/Einstellen Karte</h4>
                                <button type="button" class="close" data-dismiss="modal">X</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <textarea name="" placeholder="neue Karte" id="newBarMap" style="width:100%"  rows="10"></textarea>
                                <button class="btn btn-block btn-outline-success mt-2" data-dismiss="modal" onclick="saveNewMap('{{$theBarId}}')">Sparen</button>
                            </div>

                        </div>
                    </div>
                </div>

            <script>

                function saveNewMap(barId){
                    $.ajax({
                        url: '{{ route("barbershops.setBarMap") }}',
                        method: 'post',
                        data: {
                            id: barId,
                            map: $('#newBarMap').val(),
                            _token: '{{csrf_token()}}'
                        },
                        success: () => {
                            $("#barbershopMapDiv").load(location.href+" #barbershopMapDiv>*","");
                        },
                        error: (error) => {
                            console.log(error);
                            alert('bitte aktualisieren und erneut versuchen!');
                        }
                    });
                }



                function updateWHAll(){
                    var D1iin1 = $('#D1In1').val(); var D1iin2 = $('#D1In2').val(); var D1oout1 = $('#D1Out1').val(); var D1oout2 = $('#D1Out2').val();
                    var D2iin1 = $('#D2In1').val(); var D2iin2 = $('#D2In2').val(); var D2oout1 = $('#D2Out1').val(); var D2oout2 = $('#D2Out2').val();
                    var D3iin1 = $('#D3In1').val(); var D3iin2 = $('#D3In2').val(); var D3oout1 = $('#D3Out1').val(); var D3oout2 = $('#D3Out2').val();
                    var D4iin1 = $('#D4In1').val(); var D4iin2 = $('#D4In2').val(); var D4oout1 = $('#D4Out1').val(); var D4oout2 = $('#D4Out2').val();
                    var D5iin1 = $('#D5In1').val(); var D5iin2 = $('#D5In2').val(); var D5oout1 = $('#D5Out1').val(); var D5oout2 = $('#D5Out2').val();
                    var D6iin1 = $('#D6In1').val(); var D6iin2 = $('#D6In2').val(); var D6oout1 = $('#D6Out1').val(); var D6oout2 = $('#D6Out2').val();
                    var D7iin1 = $('#D7In1').val(); var D7iin2 = $('#D7In2').val(); var D7oout1 = $('#D7Out1').val(); var D7oout2 = $('#D7Out2').val();

                    if(!D1iin1){D1iin1 = 'none';}
                    if(!D1iin2){D1iin2 = 'none';}
                    if(!D1oout1){D1oout1 = 'none';}
                    if(!D1oout2){D1oout2 = 'none';}

                    if(!D2iin1){D2iin1 = 'none';}
                    if(!D2iin2){D2iin2 = 'none';}
                    if(!D2oout1){D2oout1 = 'none';}
                    if(!D2oout2){D2oout2 = 'none';}

                    if(!D3iin1){D3iin1 = 'none';}
                    if(!D3iin2){D3iin2 = 'none';}
                    if(!D3oout1){D3oout1 = 'none';}
                    if(!D3oout2){D3oout2 = 'none';}

                    if(!D4iin1){D4iin1 = 'none';}
                    if(!D4iin2){D4iin2 = 'none';}
                    if(!D4oout1){D4oout1 = 'none';}
                    if(!D4oout2){D4oout2 = 'none';}

                    if(!D5iin1){D5iin1 = 'none';}
                    if(!D5iin2){D5iin2 = 'none';}
                    if(!D5oout1){D5oout1 = 'none';}
                    if(!D5oout2){D5oout2 = 'none';}

                    if(!D6iin1){D6iin1 = 'none';}
                    if(!D6iin2){D6iin2 = 'none';}
                    if(!D6oout1){D6oout1 = 'none';}
                    if(!D6oout2){D6oout2 = 'none';}

                    if(!D7iin1){D7iin1 = 'none';}
                    if(!D7iin2){D7iin2 = 'none';}
                    if(!D7oout1){D7oout1 = 'none';}
                    if(!D7oout2){D7oout2 = 'none';}

                    $.ajax({
                        url: '{{ route("barbershops.setWorkingH") }}',
                        method: 'post',
                        data: {
                            bar: $('#theBar').val(),
                            D1in1: D1iin1,
                            D1out1: D1oout1,
                            D1in2: D1iin2,
                            D1out2: D1oout2,
                            D2in1: D2iin1,
                            D2out1:D2oout1,
                            D2in2: D2iin2,
                            D2out2:D2oout2,
                            D3in1: D3iin1,
                            D3out1:D3oout1,
                            D3in2: D3iin2,
                            D3out2:D3oout2,
                            D4in1: D4iin1,
                            D4out1:D4oout1,
                            D4in2: D4iin2,
                            D4out2:D4oout2,
                            D5in1: D5iin1,
                            D5out1:D5oout1,
                            D5in2: D5iin2,
                            D5out2:D5oout2,
                            D6in1: D6iin1,
                            D6out1:D6oout1,
                            D6in2: D6iin2,
                            D6out2:D6oout2,
                            D7in1: D7iin1,
                            D7out1:D7oout1,
                            D7in2: D7iin2,
                            D7out2:D7oout2,
                            _token: '{{csrf_token()}}'
                        },
                        success: () => {
                            // $('#ResWorkingHList').load('/RestorantetWH #ResWorkingHList', function() {
                            // });
                            // location.reload();
                            $('#barWorkingHsuccess').show(200).delay(3500).hide(200);
                        },
                        error: (error) => {
                            console.log(error);
                            alert('Schreiben Sie zuerst die Arbeitszeit')
                        }
                    });
                }
            </script>

