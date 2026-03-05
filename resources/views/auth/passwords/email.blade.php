


@extends('layouts.appLogIn')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6" style="margin-top:10%;">
            <div class="card" style=" border-radius:60px;">
        

            <div class="card-body">
                    <form method="POST" action="{{ route('pasreset.chEmail') }}" aria-label="{{ __('Passwort zurücksetzen') }}">
                        @csrf

                        <div class="mt-2 mb-2 text-center">
                            <h3 style="color:rgb(39,190,175)"><strong>Schreiben Sie Ihre E-Mail</strong></h3>
                        </div>

                        <div class="form-group">
                            <input placeholder="E-Mail-Addresse" type="text" name="emailUser" class="form-control" id="usr">
                        </div>

                        @if(session('error'))
                            <div class="p-2 mt-2 mb-2 alert alert-danger">
                                    {{session('error')}}
                            </div>
                        @endif

                        <button type="submit" class="buttonLogIn btn rounded-pill btn-block"
                                 style="background-color:rgb(39,190,175); color:white; padding:5px;">
                                    {{ __('E-Mail senden') }}
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<br><br><br><br><br>
<div class="text-center">
    <a href="{{ url('/')}}">
        <img style="width:350px;" src="/storage/images/logo_QRorpa_wh.png" alt="" id="LogoEndLogIn" class="pb-4">
    </a>
   
</div>

@endsection