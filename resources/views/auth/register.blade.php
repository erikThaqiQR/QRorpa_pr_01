@extends('layouts.appLogIn')

@section('content')


<style>
    
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6" style="margin-top:10%;">
            <div class="card" style=" border-radius:60px;">
        

                <div class="card-body">



                <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}" id="registerForm">
                        @csrf

                        <div class="mt-2 mb-3 text-center">
                            <h3 style="color:rgb(39,190,175)"><strong>Registrieren</strong></h3>
                        </div>

                        <div class="text-center mt-2 mb-2 regPh12" style="display:none;">
                            <p><strong> Senden des Bestätigungscodes</strong> <img style="width:100px;" src="storage/icons/sendingEmail.gif" alt=""></p>
                        </div>

                        <div class="text-center mt-2 mb-2 regPh12EmailTaken" style="display:none;">
                            <p style="color:red; font-size:18px;"> <strong>Diese E-Mail existiert bereits!</strong> </p>
                        </div>

                        <div class="mt-1 alert alert-danger Phase12empty" style="display:none;">
                            Füllen Sie zuerst das Formular aus
                        </div>
                        <div class="mt-1 alert alert-danger Phase12NotMatch" style="display:none;">
                            Die Passwörter stimmen nicht überein
                        </div>

                        <div class="form-group row regPh1">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-sm-12 col-lg-8">
                                <input style="font-size:16px;" id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus oninvalid="this.setCustomValidity('Bitte füllen Sie dieses Feld aus')">

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row regPh1">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail-Addresse') }}</label>

                            <div class="col-sm-12 col-lg-8">
                                <input style="font-size:16px;" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required oninvalid="this.setCustomValidity('Bitte füllen Sie dieses Feld aus')">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row regPh1">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Passwort') }}</label>

                            <div class="col-sm-12 col-lg-8">
                                <input style="font-size:16px;" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required oninvalid="this.setCustomValidity('Bitte füllen Sie dieses Feld aus')">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row regPh1">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Bestätige das Passwort') }}</label>

                            <div class="col-sm-12 col-lg-8">
                                <input style="font-size:16px;" id="password-confirm" type="password" class="form-control" name="password_confirmation" required oninvalid="this.setCustomValidity('Bitte füllen Sie dieses Feld aus')">
                            </div>
                        </div>

                        <div class="form-group row mb-0 mt-2" >
                            <div class="col-md-12">

                                <div class="form-group row regPh2">
                                    <label for="codeConfirm" class="col-md-4 col-form-label text-md-right">E-Mail Bestätigungscode</label>

                                    <div class="col-sm-12 col-lg-8">
                                        <input style="font-size:16px;" id="codeConfirm" type="number" class="form-control" name="codeConfirm">
                                    </div>
                                    <div class="col-12 pl-3">
                                        Schauen Sie auch im Spam Ordner nach!
                                    </div>
                                </div>
                                <div class="regPh2" style="display:none;">
                                    <p id="timeRem"></p>
                                </div>
                                <div class="text-center alert alert-danger regPh2Wrong" style="display:none;">
                                    <p><strong> The confirmation code is wrong!</strong></p>
                                </div>
                                <button type="button" onclick=sendForSubmit() class="buttonLogIn btn rounded-pill btn-block regPh2" style="background-color:rgb(39,190,175); color:white; padding:5px;">
                                    {{ __('Registrieren') }}
                                </button>

                                <button type="button" onclick="sendEmailConfig()" class="buttonLogIn btn rounded-pill btn-block regPh1" style="background-color:rgb(39,190,175); color:white; padding:5px;">
                                    Bestätigungs-E-Mail
                                </button>
                         

                                <div class="row mb-2 mt-2 " >
                                    <div class="col-12 text-right">
                                        <a class="btn btn-link ml-4" href="{{ url('login') }}">
                                            {{ __('Anmeldung?') }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <?php 
                        session_start();
                            if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
                                echo '
                                <input type="hidden" name="Res" value="'.$_SESSION['Res'].'">
                                <input type="hidden" name="t" value="'.$_SESSION['t'].'">
                                ';
                            }
                        ?>
                    </form>





                    <script src=" https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                   
                    <script>
                        $('.regPh2').hide();
                        $('.regPh1').show();

                        var returnCode = 0;

                        function sendEmailConfig(){

                            if($('#name').val() != '' && $('#email').val() != '' && $('#password').val() != '' && $('#password-confirm').val() != ''){
                                if($('#password').val() == $('#password-confirm').val()){
                                    $('.regPh1').hide();
                                    $('.regPh12').show(200);

                                    // console.log($('#name').val());
                                    // console.log($('#email').val());

                                    $.ajax({
                                        url: '{{ route("email.sendConfig") }}',
                                        method: 'post',
                                        data: {
                                            emri: $('#name').val(),
                                            email: $('#email').val(),
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (res) => {
                                            if(res == 9999999){
                                                $('.regPh12').hide();
                                                $('.regPh12EmailTaken').show(200);
                                                setTimeout(location.reload.bind(location), 4000);
                                            }else{
                                                $('.regPh12').hide(200);
                                                $('.regPh2').show(200);

                                                $('#timeRem').html("Verbleibende Zeit: <strong>10:00<strong>");
                                            
                                                returnCode = res;

                                                setInterval(countDown, 1000);
                                            }
                                        
                                        },
                                        error: (error) => {
                                            console.log(error);
                                            alert('Oops! Something went wrong')
                                        }
                                    });
                                }else{
                                    $('.Phase12NotMatch').show(200).delay(2500).hide(200);
                                }
                            }else{
                                $('.Phase12empty').show(200).delay(2500).hide(200);
                            }
                        }





                        function sendForSubmit(){
                          
                            var ClientCode = $('#codeConfirm').val();

                            
                            returnCode = returnCode.replace(/\s/g, '');

                            console.log(returnCode);
                            console.log(ClientCode);

                            if(returnCode == 0 || returnCode != ClientCode){
                                // console.log('Not Submit');
                                $('.regPh2Wrong').show(200).delay(2500).hide(200);
                            }else{
                                $('#registerForm').submit();
                            }
                            
                            // console.log('got code:'+returnCode);
                        }














          
                        var min =9;
                        var sec =59;
                        function countDown(){
                            if(min == 0 && sec == 0){
                                $('#timeRem').html("<strong style='color:red'>Zeit abgelaufen</strong>");
                                setTimeout(location.reload.bind(location), 2000);
                            }else{
                                if(sec == 0){
                                    min--;
                                    sec=59;
                                    $('#timeRem').html("Verbleibende Zeit: <strong>"+min+":"+sec+"<strong>");
                                }else{
                                    sec--;
                                    $('#timeRem').html("Verbleibende Zeit: <strong>"+min+":"+sec+"<strong>");
                                }
                            }
                            
                         
                        }
                    </script>




                    






                </div>
            </div>
        </div>
    </div>
</div>


<br>
<div class="text-center">
    @if(isset($_SESSION['Res']) && isset($_SESSION['t']))
        <?php $theR = $_SESSION['Res']; $theT =$_SESSION['t']; ?>
        <a href="/?Res={{$theR}}&t={{$theT}}">
    @else
        <a href="{{ url('/')}}">
    @endif
        <img src="/storage/images/logo_QRorpa_wh.png" style="width:200px;" alt="" id="LogoEndLogIn" class="pb-4">
    </a>
   
</div>

@endsection
