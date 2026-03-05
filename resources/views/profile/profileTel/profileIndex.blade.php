<?php
    use App\Piket;
    use App\PiketLog;
    use App\Restorant;
    use App\Orders;
?>
<style>
    .clickPointer:hover{
        cursor: pointer;
        opacity: 1; 
    }
</style>
<section class="ml-1 mr-1 mt-1 p-1 b-white" style="border-radius:25px;" id="profileDesktop">
    <div class="text-center">
        <h5 class="color-qrorpa"><strong>{{__('profile.welcome')}} {{explode(' ',Auth::user()->name)[0]}}</strong></h5>

        <div class="alert alert-success text-center mt-1 mb-1" style="display:none; width:100%;" id="profileDesktopSuccess01"> {{__('profile.alertSuccess001')}} </div>
        <div class="alert alert-success text-center mt-1 mb-1" style="display:none; width:100%;" id="profileDesktopSuccess02"> {{__('profile.alertSuccess002')}} </div>
        <div class="alert alert-success text-center mt-1 mb-1" style="display:none; width:100%;" id="profileDesktopSuccess03"> {{__('profile.alertSuccess003')}} </div>
        <!-- <hr style="margin-top: 5px; margin-bottom:5px;"> -->
    </div>
    <div class="mt-2 d-flex flex-wrap justify-content-between">
            <!-- <p style="width:100%; margin-bottom:-23px; z-index:99999;">{{__('profile.clickToChange')}}</p> -->
        @if(Auth::user()->profilePic == 'empty')
            <img style="width:49%; height:200px; border-radius:7px;"  src="/storage/images/ProfileIcon.png" alt="img" data-toggle="modal" data-target="#profilePicAdd">
        @else 
            <img style="width:49%; height:200px; border-radius:7px;"  src="/storage/profilePics/{{Auth::user()->profilePic}}" alt="img" data-toggle="modal" data-target="#profilePicAdd">
        @endif

        <div style="width:49%; height:fit-content;" id="profileDesktopLeft1">
            <h2 style="font-weight:bold; font-size:25px;">{{Auth::user()->name}}</h2> 
            <p style="margin-top:-7px; margin-bottom:0px; font-size:19px;">
                <span>
                    @if(Auth::user()->role == 1)
                        <strong>{{__('profile.client')}}</strong>
                    @elseif(Auth::user()->role == 5)
                        <strong>{{__('profile.administrator')}}</strong>
                        @if(Auth::user()->sFor != 0)
                            <span>( {{Auth::user()->sFor}} # - )</span>
                        @endif
                    @elseif(Auth::user()->role == 9)
                        <strong>{{__('profile.superadmin')}}</strong>
                    @endif
                </span>
            </p>

      
            <!-- Piket -->
            <p style="font-size:20px;"><span style="font-weight:bold;">{{__('profile.points')}} :</span>
                @if(Piket::where('klienti_u',Auth::user()->id)->first() != null)
                    {{Piket::where('klienti_u',Auth::user()->id)->first()->piket}}
                @else
                  0
                @endif
            </p>

            <div style="padding:5px; border-radius:7px; margin-bottom:0px; height:90px;" class="jumbotron jumbotron-fluid">
                <div class="container">
                    <p>---</p>
                </div>
            </div>

            
        </div>


        <div style="width:100%;" class="mt-2" id="profileDesktopLeft2">
            <!-- EMAIL -->
            <div class="input-group mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-at"></i></span>
                </div>
                <input type="text" class="form-control shadow-none" style="font-weight: bold;" id="userEmailInput" onkeyup="showProfileSaveEmail()" value="{{Auth::user()->email}}" autocomplete="false">
                <div class="input-group-append" style="display: none;" id="profileSaveEmail">
                    <button class="btn btn-outline-success" id="saveNewEmailBtn" onclick="saveNewEmail('{{Auth::user()->name}}','{{Auth::user()->id}}')" type="button">{{__('profile.save')}}</button>
                </div>
            </div>
            <input type="hidden" id="saveNewEmailCodeServer" value="0">
            <input type="hidden" id="saveNewEmailEmailServer" value="0">
        
            <div class="input-group mb-1" style="display: none;" id="saveNewEmailConfCodeDiv">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="saveNewEmailCofigCodeTimer">5:00</span>
                </div>
                <input type="text" class="form-control shadow-none" id="userEmailInputCode" placeholder="{{__('profile.confirmation_code')}} xxxxxx" autocomplete="false">
                <div class="input-group-append">
                    <button class="btn btn-outline-success" onclick="saveNewEmailCofigCode('{{Auth::user()->id}}')" type="button">{{__('profile.save')}}</button>
                </div>
            </div>

            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewEmailError01"> {{__('profile.alertNewEmailError001')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewEmailError02"> {{__('profile.alertNewEmailError002')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewEmailError03"> {{__('profile.alertNewEmailError003')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewEmailError04"> {{__('profile.alertNewEmailError004')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewEmailError05"> {{__('profile.alertNewEmailError005')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewEmailError06"> {{__('profile.alertNewEmailError006')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewEmailError07"> {{__('profile.alertNewEmailError007')}} </div>
            <!-- _________________________________________________________________________ -->

            <!-- Phone NR -->
            @if (Auth::user()->phoneNr != 'empty')
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span>
                </div>
                <input style="font-weight: bold;" type="text" id="saveNewPhNr01Input" class="form-control shadow-none" onkeyup="showProfileSavePhoneNr()" value="{{Auth::user()->phoneNr}}" autocomplete="false">
                <div class="input-group-append" style="display: none;" id="profileSavePhoneNr">
                    <button class="btn btn-outline-success" onclick="saveNewPhNr01('{{Auth::user()->id}}')" type="button">{{__('profile.save')}}</button>
                </div>
            </div>
            <input type="hidden" id="saveNewPNCodeServer" value="0">
            <input type="hidden" id="saveNewPNNumberServer" value="0">
            <p style="display: none; margin:0px;" id="saveNewPNumberConfCodeDemo"></p>
            <div class="input-group mb-1" style="display: none;" id="saveNewPNumberConfCodeDiv">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="saveNewPNumberCofigCodeTimer">3:00</span>
                </div>
                <input type="text" class="form-control shadow-none" id="userPNumberInputCode" placeholder="{{__('profile.confirmation_code')}} xxxxxx" autocomplete="false">
                <div class="input-group-append">
                    <button class="btn btn-outline-success" onclick="saveNewPNumberCofigCode('{{Auth::user()->id}}')" type="button">{{__('profile.save')}}</button>
                </div>
            </div>
            @else
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span>
                </div>
                <input type="text" id="saveNewPhNr01Input" class="form-control shadow-none" onkeyup="showProfileSavePhoneNr()" 
                placeholder="Schreiben Sie Ihre Telefonnummer" aria-label="Schreiben Sie Ihre Telefonnummer (Optional)" autocomplete="false">
                <div class="input-group-append" style="display: none;" id="profileSavePhoneNr">
                    <button id="saveNewPhNr01Btn" class="btn btn-outline-success" onclick="saveNewPhNr01('{{Auth::user()->id}}')" type="button">{{__('profile.save')}}</button>
                </div>
            </div>
            <input type="hidden" id="saveNewPNCodeServer" value="0">
            <input type="hidden" id="saveNewPNNumberServer" value="0">
            <p style="display: none; margin:0px;" id="saveNewPNumberConfCodeDemo"></p>
            <div class="input-group mb-1" style="display: none;" id="saveNewPNumberConfCodeDiv">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="saveNewPNumberCofigCodeTimer">3:00</span>
                </div>
                <input type="text" class="form-control shadow-none" id="userPNumberInputCode" placeholder="{{__('profile.confirmation_code')}} xxxxxx" autocomplete="false">
                <div class="input-group-append">
                    <button class="btn btn-outline-success" onclick="saveNewPNumberCofigCode('{{Auth::user()->id}}')" type="button">{{__('profile.save')}}</button>
                </div>
            </div>
            @endif
            
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewPhoneNrError01"> {{__('profile.alertNewPNumberError001')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewPhoneNrError02"> {{__('profile.alertNewPNumberError002')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewPhoneNrError03"> {{__('profile.alertNewPNumberError003')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewPhoneNrError04"> {{__('profile.alertNewPNumberError004')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewPhoneNrError05"> {{__('profile.alertNewPNumberError005')}} </div>
              
            <p class="clickPointer mt-2 text-center" id="changePasswordP" onclick="showChangePass()" style="font-size: 16px; color:red; opacity:0.75; margin-bottom:8px;"><strong><i style="margin:0px" class="fas fa-key"></i> {{__('profile.changeThePassword')}}</strong></p>
            <div id="changePasswordDiv" class="mt-2 mb-2" style="display: none;">
                <div class="input-group mb-1">
                    <div style="width:30%;" class="input-group-prepend">
                        <span style="width:100%;" class="input-group-text" id="basic-addon1"><i style="color:rgb(39,190,175);" class="fas fa-key mr-2"></i> {{__('profile.actual')}}</span>
                    </div>
                    <input type="password" class="form-control shadow-none" id="currentPass" name="currentPass" autocomplete="false">
                    <div class="input-group-append">
                        <button id="seeHideCurrentPass" onclick="seeCurrentPass()" class="btn btn-outline-secondary" type="button"><i class="far fa-eye"></i></button>
                    </div>
                </div> 
                <div class="input-group mb-1">
                    <div style="width:30%;" class="input-group-prepend">
                        <span style="width:100%;" class="input-group-text" id="basic-addon1"><i style="color:rgb(39,190,175);" class="fas fa-key mr-2"></i> {{__('profile.new')}}</span>
                    </div>
                    <input type="password" class="form-control shadow-none" id="newPass" name="newPass" autocomplete="false">
                    <div class="input-group-append">
                        <button id="seeHideNewPass" onclick="seeNewPass()" class="btn btn-outline-secondary" type="button"><i class="far fa-eye"></i></button>
                    </div>
                </div> 
                <div class="input-group mb-1">
                    <div style="width:30%;" class="input-group-prepend">
                        <span style="width:100%;" class="input-group-text" id="basic-addon1"><i style="color:rgb(39,190,175);" class="fas fa-key mr-2"></i> {{__('profile.confirm')}}</span>
                    </div>
                    <input type="password" class="form-control shadow-none" id="confirmPass" name="confirmPass" autocomplete="false">
                    <div class="input-group-append">
                        <button id="seeHideConfirmPass" onclick="seeConfirmPass()" class="btn btn-outline-secondary" type="button"><i class="far fa-eye"></i></button>
                    </div>
                </div> 
                <button onclick="saveNewPass('{{Auth::user()->id}}')" class="btn btn-outline-success" style="width: 100%; font-weight:bold;">{{__('profile.save')}}</button>
            </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewPasswordError01"> {{__('profile.alertNewPassError001')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewPasswordError02"> {{__('profile.alertNewPassError002')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewPasswordError03"> {{__('profile.alertNewPassError003')}} </div>
            <div class="alert alert-danger text-center mt-1 mb-1" style="display:none;" id="saveNewPasswordError04"> {{__('profile.alertNewPassError004')}} </div>
        </div>




        <?php
            $resOrCount = Orders::where([['byId',Auth::user()->id],['nrTable','!=','500'],['nrTable','!=','9000']])->count();
            $takOrCount = Orders::where([['byId',Auth::user()->id],['nrTable','500']])->count();
            $delOrCount = Orders::where([['byId',Auth::user()->id],['nrTable','9000']])->count();

            $cashPaySum = Orders::where([['byId',Auth::user()->id],['payM','Cash']])->sum('shuma');
            $onlinePaySum = Orders::where([['byId',Auth::user()->id],['payM','Online']])->sum('shuma');
        ?>

        <div style="width:100%; height:fit-content;" class="d-flex justify-content-between flex-wrap">
            <div style="width:100%;" class="d-flex flex-wrap justify-content-between">

                <div style="width: 49.5%; padding:3px; border-radius:6px; margin-bottom:10px;" class="jumbotron jumbotron-fluid">
                    <div style="padding-left: 5px; padding-right: 5px;" class="container">
                        <pre style="margin-bottom: 3px; font-weight:bold;">{{__('profile.restaurant')}} : {{$resOrCount}}</pre>
                        <pre style="margin-bottom: 3px; font-weight:bold;">Takeaway   : {{$takOrCount}}</pre>
                        <pre style="margin-bottom: 3px; font-weight:bold;">Delivery   : {{$delOrCount}}</pre>
                    </div>
                </div>
                <div style="width: 49.5%; padding:3px; border-radius:6px; margin-bottom:10px;" class="jumbotron jumbotron-fluid">
                    <div style="padding-left: 5px; padding-right: 5px;" class="container">
                        <pre style="margin-bottom: 3px; font-weight:bold;">{{__('profile.payMethodCash')}}    : {{number_format((float)$cashPaySum, 2, '.', '')}} <span style="font-size: 10px;">{{__('profile.currency')}}</span></pre>
                        <pre style="margin-bottom: 3px; font-weight:bold;">{{__('profile.payMethodOnline')}} : {{number_format((float)$onlinePaySum, 2, '.', '')}} <span style="font-size: 10px;">{{__('profile.currency')}}</span></pre>
                        <pre style="margin-bottom: 3px; font-weight:bold;">{{__('profile.payMethodAll')}} : {{number_format((float)$cashPaySum+$onlinePaySum, 2, '.', '')}} <span style="font-size: 10px;">{{__('profile.currency')}}</span></pre>
                    </div>
                </div>
            </div>

            <h6 style="width:100%; font-weight:bold; color:rgb(72,81,87);"> {{__('profile.urOrdersOnQRorpa')}} </h6>
           
            <p style="width:10%; font-weight:bold; color:rgb(72,81,87); margin-bottom:5px;"> <i class="fas fa-2x fa-list-ol pt-2"></i></p>
            <p style="width:40%; font-weight:bold; color:rgb(72,81,87); margin-bottom:5px;" class="text-center">{{__('profile.restaurant')}} <br> {{__('profile.table')}}</p>
            <p style="width:35%; font-weight:bold; color:rgb(72,81,87); margin-bottom:5px;" class="text-center"><i style="margin: 0px;" class="fas fa-2x fa-money-bill-wave pt-2"></i></p>
            <p style="width:10%; font-weight:bold; color:rgb(72,81,87); margin-bottom:5px;"><i class="far fa-2x fa-file-pdf pt-2"></i></p>
            <!-- <p style="width:5%;"></p> -->
            <div style="width:100%">
                <hr style="margin:-5px 0px 5px 0px; ">
            </div>
            @foreach(Orders::where('byId',Auth::user()->id)->orderByDesc('created_at')->get() as $orOne)
                <p style="width:10%; font-weight:bolder; color:rgb(72,81,87);" class="pt-2">{{$orOne->refId}}</p>

                @if(Restorant::find($orOne->Restaurant) != NULL)
                <p style="width:40%; font-weight:bolder; color:rgb(72,81,87);" class="text-center">{{Restorant::find($orOne->Restaurant)->emri}}
                @else
                <p style="width:40%; font-weight:bolder; color:red;" class="text-center">NULL
                @endif
                <br>
                    @if($orOne->nrTable == 500)
                        Takeaway
                    @elseif ($orOne->nrTable == 9000)
                        Delivery
                    @else
                        T: {{$orOne->nrTable}}
                    @endif
                </p>
             
                <p class="text-center" style="width:35%; font-weight:bolder; color:rgb(72,81,87);">{{$orOne->shuma}} <span style="font-size: 10px;">{{__('profile.currency')}}</span>
                <br>
                    @if($orOne->payM == 'Cash')
                        {{__('profile.cashPayment')}}
                    @else
                        {{__('profile.onlinePayment')}}
                    @endif
                </p>
            
                <form class="pt-1" style="width:10%; font-weight:bold; color:rgb(72,81,87);" method="POST" action="{{ route('receipt.getReceipt') }}">
                    {{ csrf_field()}}
                    <input type="hidden" value="{{$orOne->id}}" name="orId">
                    <button id="receiptDownBtn{{$orOne->id}}" style="padding:0px;" onclick="showDownloadReceipt('{{$orOne->id}}')" type="submit" class="btn"><i style="color:rgb(39,190,175); " class="fas fa-2x fa-file-download"></i></button>
                </form>
                <!-- <p style="width:5%;"></p> -->
            @endforeach

        </div>
    </div>
</section>







    <!-- The Modal -->
    <div class="modal" id="profilePicAdd">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Profilbild ändern</h4>
            </div>

            <!-- Modal body -->
            {{Form::open(['action' => 'ProfileController@setProfilePic', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
            <div class="modal-body">
                
                <div class="custom-file mb-3 mt-3">
                    {{ Form::label('Bild', null , ['class' => 'custom-file-label']) }}
                    {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                </div>
                {{ Form::hidden('client',Auth::user()->id, ['class' => 'form-control']) }}
            </div>

            <!-- Modal footer -->
            <div class="modal-footer d-flex justify-content-between">
                <button style="width:45%;" type="button" class="btn btn-danger" data-dismiss="modal">{{__('profile.close')}}</button>
                {{ Form::submit('Speichern', ['class' => 'form-control btn btn-block btn-outline-primary', 'style' => 'width:45%;']) }}
            </div>
            {{Form::close() }}

            </div>
        </div>
    </div>









<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    function showDownloadReceipt(orId){
        $('#receiptDownBtn'+orId).html('<img src="storage/gifs/download02.gif" style="width:28px; height:28px;" alt="downloading...">');
        setTimeout(function() {
            $('#receiptDownBtn'+orId).html('<i style="color:rgb(39,190,175); " class="fas fa-2x fa-file-download"></i>');
        }, 2260);
    }


    // Ndryshimi i EMAIL-it
    function showProfileSaveEmail(){ 
        $('#profileSaveEmail').show(50); 
    }
    function saveNewEmail(clName, clId){
        if(!$('#userEmailInput').val()){
            if($('#saveNewEmailError01').is(':hidden')){ $('#saveNewEmailError01').show(50).delay(4500).hide(50); }
        }else{
            var email = $('#userEmailInput').val();
            var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(regexEmail.test(email)){

                $.ajax({
					url: '{{ route("profile.sendConfCodeEmail") }}',
					method: 'post',
					data: {
						clN: clName,
						clI: clId,
                        newEm: email,
						_token: '{{csrf_token()}}'
					},
					success: (respo) => {
                        respo = respo.replace(/\s/g, '');
                        if(respo == 'sameEmail'){
                            if($('#saveNewEmailError03').is(':hidden')){ $('#saveNewEmailError03').show(50).delay(4500).hide(50); }
                        }else if(respo == 'emailInUse'){
                            if($('#saveNewEmailError05').is(':hidden')){ $('#saveNewEmailError05').show(50).delay(4500).hide(50); }
                        }else{
                            $('#saveNewEmailConfCodeDiv').show(50);
                            $('#saveNewEmailBtn').attr('disabled',true);
                            $('#userEmailInput').attr('disabled',true);
					        $('#saveNewEmailCodeServer').val(respo);
                            $('#saveNewEmailEmailServer').val(email);
                            startEmailTimer();
                        }
					},
					error: (error) => { console.log(error); }
				});

            }else{
                if($('#saveNewEmailError02').is(':hidden')){ $('#saveNewEmailError02').show(50).delay(4500).hide(50); }
            }
        }
    }   
    var intervalTimerEmail ;
    function startEmailTimer(){
        var timerStart = "5:00";
        $('#saveNewEmailCofigCodeTimer').html(timerStart);
        intervalTimerEmail = setInterval(function() {
            var timer = timerStart.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            if (minutes < 0) clearInterval(interval);
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            $('#saveNewEmailCofigCodeTimer').html(minutes + ':' + seconds);
            timerStart = minutes + ':' + seconds;
            if(minutes == 0 && seconds == 0)location.reload();
        }, 1000);
    }
    function saveNewEmailCofigCode(userId){
        var originalCode = $('#saveNewEmailCodeServer').val();
        var originalEmail = $('#saveNewEmailEmailServer').val();
        var clientCode = $('#userEmailInputCode').val();
        if(!$('#userEmailInputCode').val()){
            if($('#saveNewEmailError06').is(':hidden')){ $('#saveNewEmailError06').show(50).delay(4500).hide(50); }
        }else if(clientCode.length != 6){
            if($('#saveNewEmailError04').is(':hidden')){ $('#saveNewEmailError04').show(50).delay(4500).hide(50); }
        }else{
            $.ajax({
                url: '{{ route("profile.saveNewEmail") }}',
                method: 'post',
                data: {
                    orgCode: originalCode,
                    clCode: clientCode,
                    theEm: originalEmail,
                    uId: userId,
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    respo = respo.replace(/\s/g, '');
                    if(respo == 'codeFail'){
                        if($('#saveNewEmailError07').is(':hidden')){ $('#saveNewEmailError07').show(50).delay(4500).hide(50); }
                    }else{
                        if($('#profileDesktopSuccess01').is(':hidden')){ $('#profileDesktopSuccess01').show(50).delay(10000).hide(50); }
                        $("#profileDesktopLeft1").load(location.href+" #profileDesktopLeft1>*","");
                        $("#profileDesktopLeft2").load(location.href+" #profileDesktopLeft2>*","");
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    }
    // ----------------------------------------------------------------------------------------------------------







    // Ndryshimi / Regjistrimi i numrit te telefonit
    function showProfileSavePhoneNr(){ 
        $('#profileSavePhoneNr').show(50); 
    }

    function saveNewPhNr01(uId){
        var thePN = $('#saveNewPhNr01Input').val().replace(/ /g,'');
        if(!$('#saveNewPhNr01Input').val() || thePN.length < 9 || thePN.length > 10){
            if($('#saveNewPhoneNrError01').is(':hidden')){ $('#saveNewPhoneNrError01').show(50).delay(4500).hide(50); }
        }else{
            
            $.ajax({
				url: '{{ route("profile.sendConfCodePhoneNr") }}',
				method: 'post',
				data: {
                    userId: uId,
					phNr: thePN,
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = respo.replace(/\s/g, '');
                    if(respo == 'falseNR'){
                        if($('#saveNewPhoneNrError02').is(':hidden')){ $('#saveNewPhoneNrError02').show(50).delay(4500).hide(50); }
                    }else{
                        $('#saveNewPNCodeServer').val(respo);
                        $('#saveNewPNNumberServer').val(thePN);
                        startPNumberTimer();
                        $('#saveNewPNumberConfCodeDiv').show(50);

                        $('#saveNewPhNr01Input').attr('disabled',true);
                        $('#saveNewPhNr01Btn').attr('disabled',true);
                        if(thePN == '763270293' || thePN == '0763270293' || thePN == '763251809' || thePN == '0763251809' || thePN == '763459941' || thePN == '0763459941' || thePN == '763469963' || thePN == '0763469963' || thePN == '760000000' || thePN == '0760000000'){
                            $('#saveNewPNumberConfCodeDemo').html('Demo code: '+respo);
                        }else{
                            $('#saveNewPNumberConfCodeDemo').hide(5);
                        }
                        $('#saveNewPNumberConfCodeDemo').show(50);
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    }
    var intervalTimerPNumber ;
    function startPNumberTimer(){
        var timerStart = "3:00";
        $('#saveNewPNumberCofigCodeTimer').html(timerStart);
        intervalTimerPNumber = setInterval(function() {
            var timer = timerStart.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            if (minutes < 0) clearInterval(interval);
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            $('#saveNewPNumberCofigCodeTimer').html(minutes + ':' + seconds);
            timerStart = minutes + ':' + seconds;
            if(minutes == 0 && seconds == 0)location.reload();
        }, 1000);
    }
    function saveNewPNumberCofigCode(usId){
        var originalCode = $('#saveNewPNCodeServer').val();
        var clientCode = $('#userPNumberInputCode').val();
        var phonenr = $('#saveNewPNNumberServer').val();

        if(!$('#userPNumberInputCode').val()){
            if($('#saveNewPhoneNrError03').is(':hidden')){ $('#saveNewPhoneNrError03').show(50).delay(4500).hide(50); }
        }else if(clientCode.length != 6){
            if($('#saveNewPhoneNrError04').is(':hidden')){ $('#saveNewPhoneNrError04').show(50).delay(4500).hide(50); }
        }else{
            $.ajax({
				url: '{{ route("profile.saveNewPhoneNr") }}',
				method: 'post',
				data: {
                    userId: usId,
					orgCode: originalCode,
					clCode: clientCode,
					pnr: phonenr,
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = respo.replace(/\s/g, '');
                    if(respo == 'falseCode'){
                        if($('#saveNewPhoneNrError05').is(':hidden')){ $('#saveNewPhoneNrError05').show(50).delay(4500).hide(50); }
                    }else{
                        if($('#profileDesktopSuccess02').is(':hidden')){ $('#profileDesktopSuccess02').show(50).delay(10000).hide(50); }
                        $("#profileDesktopLeft1").load(location.href+" #profileDesktopLeft1>*","");
                        $("#profileDesktopLeft2").load(location.href+" #profileDesktopLeft2>*","");
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    }
    // ----------------------------------------------------------------------------------------------------------



    // Ndryshimi i fjalekalimit
    function showChangePass(){
        $('#changePasswordP').hide(50);
        $('#changePasswordDiv').show(50);
    }
    function seeCurrentPass(){
        $('#currentPass').attr('type','text');
        $('#seeHideCurrentPass').attr('onclick','hideCurrentPass()');
        $('#seeHideCurrentPass').html('<i class="far fa-eye-slash"></i>');
    }
    function hideCurrentPass(){
        $('#currentPass').attr('type','password');
        $('#seeHideCurrentPass').attr('onclick','seeCurrentPass()');
        $('#seeHideCurrentPass').html('<i class="far fa-eye"></i>');
    }

    function seeNewPass(){
        $('#newPass').attr('type','text');
        $('#seeHideNewPass').attr('onclick','hideNewPass()');
        $('#seeHideNewPass').html('<i class="far fa-eye-slash"></i>');
    }
    function hideNewPass(){
        $('#newPass').attr('type','password');
        $('#seeHideNewPass').attr('onclick','seeNewPass()');
        $('#seeHideNewPass').html('<i class="far fa-eye"></i>');
    }

    function seeConfirmPass(){
        $('#confirmPass').attr('type','text');
        $('#seeHideConfirmPass').attr('onclick','hideConfirmPass()');
        $('#seeHideConfirmPass').html('<i class="far fa-eye-slash"></i>');
    }
    function hideConfirmPass(){
        $('#confirmPass').attr('type','password');
        $('#seeHideConfirmPass').attr('onclick','seeConfirmPass()');
        $('#seeHideConfirmPass').html('<i class="far fa-eye"></i>');
    }

    function saveNewPass(uId){
        var theNewPass = $('#newPass').val();
        if(!$('#currentPass').val() || !$('#newPass').val() || !$('#confirmPass').val()){
            if($('#saveNewPasswordError01').is(':hidden')){ $('#saveNewPasswordError01').show(50).delay(4500).hide(50); }
        }else if($('#newPass').val() != $('#confirmPass').val()){
            if($('#saveNewPasswordError03').is(':hidden')){ $('#saveNewPasswordError03').show(50).delay(4500).hide(50); }
        }else if(theNewPass.length < 8){
            if($('#saveNewPasswordError04').is(':hidden')){ $('#saveNewPasswordError04').show(50).delay(4500).hide(50); }
        }else{
         
            $.ajax({
				url: '{{ route("profile.changePassword") }}',
				method: 'post',
				data: {
                    userId: uId,
					currPass: $('#currentPass').val(),
					newPass: $('#newPass').val(),
					confPass: $('#confirmPass').val(),
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = respo.replace(/\s/g, '');
                    if(respo == 'currentPassFail'){
                        if($('#saveNewPasswordError02').is(':hidden')){ $('#saveNewPasswordError02').show(50).delay(4500).hide(50); }
                    }else if(respo == 'smallPass'){
                        if($('#saveNewPasswordError04').is(':hidden')){ $('#saveNewPasswordError04').show(50).delay(4500).hide(50); }
                    }else if(respo == 'confirmPassFail'){
                        if($('#saveNewPasswordError03').is(':hidden')){ $('#saveNewPasswordError03').show(50).delay(4500).hide(50); }
                    }else if(respo == 'success'){
                        if($('#profileDesktopSuccess03').is(':hidden')){ $('#profileDesktopSuccess03').show(50).delay(10000).hide(50); }
                        $("#profileDesktopLeft1").load(location.href+" #profileDesktopLeft1>*","");
                        $("#profileDesktopLeft2").load(location.href+" #profileDesktopLeft2>*","");
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
        
    }

    // ----------------------------------------------------------------------------------------------------------


</script>