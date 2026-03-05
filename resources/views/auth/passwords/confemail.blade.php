<?php
    if(!session('success')){
        header("Location: ".route('password.request'));
        exit();
    }

    $var2D = explode('||',session('success'));
    $code = $var2D[0];
?>
@extends('layouts.appLogIn')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6" style="margin-top:10%;">
            <div class="card" style=" border-radius:60px;">
                <div class="card-body">
                    <div class="mt-2 mb-2 text-center">
                        <h3 style="color:rgb(39,190,175)"><strong> Vielen Dank für Ihre Einhaltung</strong></h3>
                    </div>

                    <p style="color:rgb(72, 81, 87); font-weight:bold;" class="text-center">
                        Wir haben Ihnen einen Code zur Bestätigung Ihrer E-Mail gesendet. Bitte überprüfen Sie Ihren Posteingang
                    </p>
                    <p style="color:rgb(72, 81, 87); font-weight:bold; font-size:20px;" class="text-center">
                        {{$var2D[1]}}
                    </p>


                    <div id="doneNewPass" class="p-1 mt-1 mb-1 text-center alert alert-success" style="display:none;">
                        Ihr Passwort wurde erfolgreich geändert!
                        <a class=" btn {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:2px solid lightgray; color:black; width:68%;"
                         href="{{ route('login') }}">Einloggen</a>
                    </div>



                    <p style="color:rgb(72, 81, 87); font-weight:bold; margin-bottom:-2px;" class="text-center">
                        Code
                    </p>
                    <input autocomplete="off" type="number" name="codeUser" class="form-control" id="codeU">
                    
                    <hr>
                    <p style="color:rgb(72, 81, 87); font-weight:bold; margin-bottom:-2px;" class="text-center">
                     Schreiben Sie das neue Passwort
                    </p>

                    <input autocomplete="off" placeholder="Passwort" type="password" name="pass1" class="mt-4 form-control" id="pu1">
                    <input autocomplete="off" placeholder="Wiederhole das Passwort" type="password" name="pass2" class="mt-3 form-control" id="pu2">

                    <div id="notMatchPass" class="p-1 mt-1 mb-1 alert alert-danger" style="display:none;">
                        Die Passwörter stimmen nicht überein
                    </div>
                    <div id="emptyPass" class="p-1 mt-1 mb-1 alert alert-danger" style="display:none;">
                        Schreiben Sie zuerst das Passwort
                    </div>
                    <div id="shortPass" class="p-1 mt-1 mb-1 alert alert-danger" style="display:none;">
                        Das Passwort sollte aus mehr als 8 Zeichen bestehen
                    </div>
                    <div id="emptyCode" class="p-1 mt-1 mb-1 alert alert-danger" style="display:none;">
                        Schreiben Sie den Bestätigungscode!
                    </div>
                    <div id="shortCode" class="p-1 mt-1 mb-1 alert alert-danger" style="display:none;">
                        Überprüfen Sie den Code erneut
                    </div>
                    <div id="wrongCode" class="p-1 mt-1 mb-1 alert alert-danger" style="display:none;">
                        Der Code ist nicht korrekt!
                    </div>

                    
                    <button onclick="sendPasChng('{{$var2D[1]}}','{{$var2D[0]}}')" class="buttonLogIn btn rounded-pill btn-block mt-3" style="background-color:rgb(39,190,175); color:white; padding:5px;">
                        {{ __('Bestätigen') }}
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function sendPasChng(em,co){
        var pu1 =$('#pu1').val();
        var pu2 =$('#pu2').val();

        var codeU =$('#codeU').val();

        if(pu1 == "" || pu2 == ""){
            $('#emptyPass').show(200).delay(2500).hide(200);
        }else{
            if(pu1.length < 8){
                $('#shortPass').show(200).delay(2500).hide(200);
            }else{
                if(codeU == ""){
                    $('#emptyCode').show(200).delay(2500).hide(200);
                }else{
                    if(codeU.length != 5){
                    
                    }else{
                        if(pu1 === pu2){
                            
                            $.ajax({
                                url: '{{ route("pasreset.chEmailPass") }}',
                                method: 'post',
                                data: {
                                    email: em,
                                    code: co,
                                    codeUser: codeU,
                                    ps: pu1,
                                    _token: '{{csrf_token()}}'
                                },
                                success: (res) => {
                                    res = res.replace(/\s/g, '');
                                    // $("#freeProElements").load(location.href+" #freeProElements>*","");
                                    if(res == 'done'){
                                        $('#pu1').val(' ');
                                        $('#pu2').val(' ');
                                        $('#codeU').val(' ');
                                        $('#doneNewPass').show(200);
                                    }else{
                                        // alert(res);
                                        $('#codeU').val(' ');
                                        $('#wrongCode').show(200).delay(2500).hide(200);
                                    }  
                                },
                                error: (error) => {
                                    console.log(error);
                                    alert('bitte aktualisieren und erneut versuchen!');
                                }
                            });
                        }else{
                            $('#notMatchPass').show(200).delay(2500).hide(200);
                        }
                    }
                }
            }
        }
    }
</script>


<br><br>
<div class="text-center">
    <a href="{{ url('/')}}">
        <img style="width:350px;" src="/storage/images/logo_QRorpa_wh.png" alt="" id="LogoEndLogIn" class="pb-4">
    </a>
   
</div>

@endsection