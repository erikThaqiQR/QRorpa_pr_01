@extends('layouts.appLogIn')

@section('content')




<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6" style="margin-top:10%;">
            <div class="card" style=" border-radius:60px;">
            

                <div class="card-body">
                        <div class="mt-2 mb-2 text-center">
                            <h3 style="color:rgb(39,190,175);margin-bottom:30px;"><strong>Anmelden</strong></h3>
                        </div>
                        @if (isset($_GET['taRegScc']))
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
                            <input type="hidden" id="theEVal" value="{{$_GET['taRegScc']}}">
                            <script>
                                var theE = $('#theEVal').val();
                                theE = theE.replace("%40", "@");
                                $('#email').val(theE);
                            </script>
                            <div class="alert alert-success text-center" style="width:100%;">
                                Wenn Sie das Konto erfolgreich aktiviert haben, melden Sie sich mit Ihren Zugangsdaten an.
                            </div>
                        @elseif (isset($_GET['taRegErr']))
                            <div class="alert alert-danger text-center" style="width:100%;">
                                @if ($_GET['taRegErr'] == 'regAlrUsed')
                                    Das Konto für diese Anfrage wurde bereits aktiviert!
                                @elseif ($_GET['taRegErr'] == 'invalidHash')
                                    Diese Kontoaktivierungsanfrage ist ungültig!
                                @elseif ($_GET['taRegErr'] == 'regReqNotFound')
                                    Leider ist diese Anfrage ungültig!
                                @endif
                            </div>
                        @endif
                        <hr>

                        <div class="mt-2 mb-2 text-center">
                            <a style="background-color: #eb4d40; color:white; font-size:17px;" class="buttonLogIn btn btn-block mt-2 p-2 shadow-none"
                            href="{{ url('auth/google')}}">
                                <img style="width:28px; background-color: #fff; float: left;" src="storage/icons/googleLogIn.png" alt="" >
                                <strong>Mit Google einloggen</strong>
                            </a>

                            <a style="background-color: rgba(24,119,242,255); color:white; font-size:17px;" class="buttonLogIn btn btn-block mt-2 p-2 shadow-none"
                            href="{{ url('authFB/facebook') }}">
                                <img style="width:28px; background-color: #fff; float: left;" src="storage/icons/fb_icon_325x325.png" alt="" >
                                <strong>Mit Facebook einloggen</strong>
                            </a>

                            <a style="background-color: rgba(167,169,172,255); color:white; font-size:17px;" class="buttonLogIn btn btn-block mt-2 p-2 shadow-none"
                            href="#"  data-toggle="modal" data-target="#tempAIDInfoM">
                                <img style="width:28px; background-color: #fff; float: left;" src="storage/icons/apple_logo.png" alt="" >
                                <strong>Mit Apple ID einloggen</strong>
                            </a>


                            <!-- APPLE ID login temp info Modal -->
                            <div class="modal fade mt-5" id="tempAIDInfoM" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle"><i class="fab fa-apple"></i> Apple-ID-Authentifizierung</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true"><strong>X</strong></span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="font-size: 1.3rem;">
                                            <strong><i class="fas fa-tools"></i> Bald verfügbar, wir arbeiten daran..</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>



                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Anmeldung') }}">
                        @csrf
                        <div class="seperator" style=" background: #d7d7d7;color: #0a3847; font-size: 19px; height: 1px; margin: 23px 0 40px; position: relative; text-align: center;">
                            <span style="position: relative; top: -12px; padding: 0 20px;  background: #fff;"><strong>oder</strong></span>
                        </div>
                       
                        <div class="form-group">
                            <label for="email" class="ml-3" style="opacity:0.6;">{{ __('E-Mail-Addresse') }}</label>

                            <div class="col-12">
                                <input style="font-size:16px;" id="email" type="email" class="shadow-none form-control{{ $errors->has('email') ? ' is-invalid' : '' }} lineInput" name="email" value="{{ old('email') }}" required autofocus oninvalid="this.setCustomValidity('Bitte füllen Sie dieses Feld aus')">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-form-label ml-3" style="opacity:0.6;">{{ __('Passwort') }}</label>

                            <div class="col-12">
                                <input style="font-size:16px;" id="password" type="password" class="shadow-none form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required oninvalid="this.setCustomValidity('Bitte füllen Sie dieses Feld aus')">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Behalte mich in Erinnerung') }}
                                    </label>
                                </div>
                            </div>
                        </div> -->

                        <?php 
                        session_start();
                            if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
                                echo '
                                <input type="hidden" name="Res" value="'.$_SESSION['Res'].'">
                                <input type="hidden" name="t" value="'.$_SESSION['t'].'">
                                ';
                            }else{
                                echo '
                                <input type="hidden" name="Res" value="13">
                                <input type="hidden" name="t" value="1">
                                ';
                            }
                        ?>

                        

                        <div class="form-group mb-0">
                            <div class="col-12">
                                <button type="submit" class="buttonLogIn btn rounded-pill btn-block"
                                 style="background-color:rgb(39,190,175); color:white; padding:5px;">
                                    {{ __('Einloggen in') }}
                                </button>
                                <br>

                                <div class="row">
                                    <div class="col-8">
                                        <a class="btn btn-link text-left" href="{{ route('password.request') }}">
                                            {{ __('Passwort vergessen?') }}
                                        </a>
                                    </div>
                                    <div class="col-4 text-right">
                                        <a class="btn btn-link pr-2" href="{{ url('register') }}">
                                            {{ __('Anmelden?') }}
                                        </a>
                                    </div>

                                   
                                </div>
                               
                               

                                
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>



























<br><br>
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

