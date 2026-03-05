<?php
    use App\Restorant;
    use App\RestaurantWH;

    
?>

@if(!isset($_GET['Res']) || Restorant::find($_GET['Res']) == null) 
<?php 
    header("Location: ".route('restorantet.index'));
    exit();
  ?>
@endif

<style>
    .noBorderIn{
        outline: none;
        box-shadow:none !important;
    }
</style>
<section class="pb-5 pr-3 pl-3" >
    <hr>
    <div class="d-flex">
        <h2 style="width:50%;" class="color-qrorpa pb-3 pl-3 "><strong>{{Restorant::find($_GET['Res'])->emri}}</strong></h2>
        <h2 style="width:50%;" class="color-qrorpa pb-3 pr-3 text-right"><strong>Arbeitszeiten im Restaurant</strong></h2>

        <input type="hidden" value="{{$_GET['Res']}}" id="thisRes">
        <?php
            $RWH = RestaurantWH::where('toRes',$_GET['Res'])->first();
        ?>
    </div>
    <hr>

    @if(Restorant::find($_GET['Res'])->resDesc != 'none')
     <p style="width:100%;"> <strong>Beschreibung </strong>{{Restorant::find($_GET['Res'])->resDesc}}</p> 
    @endif
    <button class="btn btn-outline-dark mb-4" style="width:100%;"  data-toggle="modal" data-target="#resDescModal">Beschreibung einstellen</button>









                <!-- Edit map modal -->
                <div class="modal" id="resDescModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                    <div class="modal-dialog">
                        <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Restaurantbeschreibung schreiben / bearbeiten</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        
                        {{Form::open(['action' => 'RestorantController@setDesc', 'method' => 'post' ]) }}
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="form-group">
                                    {{ Form::label('Beschreibung', null , ['class' => 'control-label']) }}
                                    {{ Form::textarea('desc','', ['class' => 'form-control', 'rows'=>'3']) }}
                                </div>
                            </div>
                            {{ Form::hidden('res', $_GET['Res'] , ['id' => 'restaurant']) }}
                            <!-- Modal footer -->
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" style="width:45%;">Schließen</button>
                                {{ Form::submit('Sparen', ['class' => 'form-control btn btn-primary', 'style' => 'width:45%;']) }}
                            </div>
                        {{Form::close() }}

                        </div>
                    </div>
                </div>



























    <div class="d-flex justify-content-between">
        <div style="width:40%;" >
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
                <button style="width:20%;" onclick="updateWH('D1')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button>




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
                <button style="width:20%;" onclick="updateWH('D2')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button>






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
                <button style="width:20%;" onclick="updateWH('D3')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button>





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
                <button style="width:20%;" onclick="updateWH('D4')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button>






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
                <button style="width:20%;" onclick="updateWH('D5')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button>





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
                <button style="width:20%;" onclick="updateWH('D6')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button>





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
                <button style="width:20%;" onclick="updateWH('D7')" class="mb-3 btn btn-block btn-outline-dark">Aktualisieren</button>


            



       


                <button style="width:100%; " onclick="updateWHAll()"  class="mb-3 mt-3 btn btn-block btn-outline-dark">Alle aktualisieren</button>

            </div>
        </div>




        <!-- map -->
        <div style="width:55%;">
            @if(Restorant::find($_GET['Res'])->map != 'none')
            <iframe src="{{Restorant::find($_GET['Res'])->map}}"
                 width="100%" height="83%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            @else
            <iframe width='100%' height="83%" frameborder='0' scrolling='no' marginheight='0' marginwidth='0'
                src='https://maps.google.com/maps?&amp;q="+ {{Restorant::find($_GET['Res'])->adresa}} + "&amp;hl=de&amp;output=embed'></iframe>
            @endif
                <button style="width:100%;" data-toggle="modal" data-target="#EditMapModal" class="mb-3 mt-3 btn btn-block btn-outline-dark">Aktualisieren/Einstellen Karte</button>
        </div>
    </div>













                <!-- Edit map modal -->
                <div class="modal" id="EditMapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                    <div class="modal-dialog">
                        <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Lage des Restaurants</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        
                        {{Form::open(['action' => 'RestorantController@setResMap', 'method' => 'post' ]) }}
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="form-group">
                                    {{ Form::label('Quelle', null , ['class' => 'control-label']) }}
                                    {{ Form::textarea('src','', ['class' => 'form-control', 'rows'=>'3']) }}
                                </div>
                            </div>
                            {{ Form::hidden('res', $_GET['Res'] , ['id' => 'restaurant']) }}
                            <!-- Modal footer -->
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" style="width:45%;">Schließen</button>
                                {{ Form::submit('Sparen', ['class' => 'form-control btn btn-primary', 'style' => 'width:45%;']) }}
                            </div>
                        {{Form::close() }}

                        </div>
                    </div>
                </div>





























    
    <script>
        function updateWH(Day){
           
                var iin1 = $('#'+Day+'In1').val();
                var iin2 = $('#'+Day+'In2').val();
                var oout1 = $('#'+Day+'Out1').val();
                var oout2 = $('#'+Day+'Out2').val();

                if(!iin1){
                    iin1 = 'none';
                }
                if(!iin2){
                    iin2 = 'none';
                }
                if(!oout1){
                    oout1 = 'none';
                }
                if(!oout2){
                    oout2 = 'none';
                }
            


            $.ajax({
                url: '{{ route("restorantet.OneDayWH") }}',
                method: 'post',
                data: {
                    day: Day,
                    res: $('#thisRes').val(),
                    in1: iin1,
                    out1: oout1,
                    in2: iin2,
                    out2: oout2,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                  
                    // $('#ResWorkingHList').load('/RestorantetWH #ResWorkingHList', function() {
                    // });
                    location.reload();
                },
                error: (error) => {
                    console.log(error);
                    alert('Schreiben Sie zuerst die Arbeitszeit')
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
                url: '{{ route("restorantet.allWH") }}',
                method: 'post',
                data: {
                    res: $('#thisRes').val(),
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
                    location.reload();
                },
                error: (error) => {
                    console.log(error);
                    alert('Schreiben Sie zuerst die Arbeitszeit')
                }
            });
           
            
        }

    
    </script>

</section>
