





@extends('layouts.appLogIn')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6" style="margin-top:10%;">
            <div class="card" style=" border-radius:60px;">
        

                <div class="card-body">
                    <form method="POST" action="{{ route('password.request') }}" aria-label="{{ __('Passwort zurücksetzen') }}">
                        @csrf

                        <div class="mt-2 mb-2 text-center">
                            <h3 style="color:rgb(39,190,175)"><strong>Schreiben Sie Ihr neues Passwort</strong></h3>
                        </div>

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-lg-4 col-md-4 col-sm-12 col-12 col-form-label text-md-right">{{ __('E-Mail-Addresse') }}</label>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                 name="email" value="{{ $email ?? old('email') }}" required autofocu oninvalid="this.setCustomValidity('Bitte füllen Sie dieses Feld aus')">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-lg-4 col-md-4 col-sm-12 col-12 col-form-label text-md-right">{{ __('Passwort') }}</label>

                            <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                 name="password" required oninvalid="this.setCustomValidity('Bitte füllen Sie dieses Feld aus')">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Bestätige das Passwort') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required oninvalid="this.setCustomValidity('Bitte füllen Sie dieses Feld aus')">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                           
                            <div class="col-12">
                                <button type="submit" class="buttonLogIn btn rounded-pill btn-block"
                                 style="background-color:rgb(39,190,175); color:white; padding:5px;">
                                    {{ __('Passwort zurücksetzen') }}
                                </button>
                                <br>

                                <div class="row">
                                    <div class="col-12 text-right">
                                        <a class="btn btn-link ml-4" href="{{ url('login') }}">
                                            {{ __('Anmeldung ?') }}
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


<br><br><br>
<div class="text-center">
    <a href="{{ url('/')}}">
        <img src="/storage/images/logo_QRorpa_wh.png" alt="" id="LogoEndLogIn" class="pb-4">
    </a>
</div>

@endsection