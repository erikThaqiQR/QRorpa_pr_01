<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

        <!-- <script src="https://kit.fontawesome.com/d40577511e.js" crossorigin="anonymous"></script> -->
@include('fontawesome')
        <title>Empfehlen Sie ein Restaurant</title>
        <link rel="icon" href="storage/images/qrorpaIcon.png">

        <style>
            .footerAn{
                color:white;
            }
            .footerAn::hover{
                color:whitesmoke;
            }
            .socialAn{
                color:rgb(72,81,87);
            }
            .socialAn::hover{
                color:rgb(62,71,77) !important;
                text-decoration: none;
            }

           
        </style>
    </head>
    <body>
        <div style="width:100%; padding:2cm 0px 2cm 0px; background-image: linear-gradient(to top right, rgb(39,190,175) ,rgba(9,40,79,255));">
            <div class="container">
                <div class="d-flex">
                    <div style="width: 50%;"> 
                        <img src="storage/images/logo_QRorpa_1024_512.png" style="width: 6cm; height:auto; margin-bottom:1.5cm;" alt="">
                        <p style="color: white; font-size:1.4cm; font-weight:bold; white-space: wrap;">Empfehlen und bis zu CHF 1000.- erhalten!</p>
                        <p style="color: white; font-size:1.3cm; font-weight:bold; white-space: wrap; margin:0px;">QRorpa Kassensysteme</p>
                        <p style="color: white; font-size:0.9cm; font-weight:bold; white-space: wrap;">kontaktlos bestellen & bezahlen</p>
                    </div>
                    <div style="width: 50%;">
                        <img src="storage/ResRecommendation/Design ohne Titel-min2.png" style="width: 100%; height:auto;" alt="">
                    </div>
                </div>
                <div class="flex justify-content-between" style="margin-top: -1cm;">
                    <img src="storage/ResRecommendation/qrIc_7.png" style="width: 10.5%; height:auto;" alt="">
                    <img src="storage/ResRecommendation/qrIc_01.png" style="width:10.5%; height:auto;" alt="">
                    <img src="storage/ResRecommendation/qrIc_2.png" style="width: 10.5%; height:auto;" alt="">
                    <img src="storage/ResRecommendation/qrIc_3.png" style="width: 10.5%; height:auto;" alt="">
                    <img src="storage/ResRecommendation/qrIc_4.png" style="width: 10.5%; height:auto;" alt="">
                    <img src="storage/ResRecommendation/qrIc_5.png" style="width: 10.5%; height:auto;" alt="">
                    <img src="storage/ResRecommendation/qrIc_6.png" style="width: 10.5%; height:auto;" alt="">
                    <img src="storage/ResRecommendation/qrIc_9.png" style="width: 10.5%; height:auto;" alt="">
                    <img src="storage/ResRecommendation/qrIc_8.png" style="width: 10.5%; height:auto;" alt="">
                </div>
            </div>
        </div>
        <div style="width:100%; padding:2cm 0px 1cm 0px; background-image: linear-gradient(to top, rgb(39,190,175) ,rgba(9,40,79,255));">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between">
                    <div style="width: 47%;"> 
                        <p style="font-size:1.5cm; color:white; background-color:rgba(7,19,50,255); padding:0.9cm;" class="text-center mb-2">Kontaktperson</p>

                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon1" style="width:100%;">Vorname</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="konPer_Vorname" placeholder="Vorname">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon2" style="width:100%;">Name</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="konPer_Name" placeholder="Name">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon3" style="width:100%;">Adresse</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="konPer_Adresse" placeholder="Adresse">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon3" style="width:100%;">PLZ/Ort</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="konPer_PLZ" placeholder="PLZ">
                            <input type="text" class="form-control shadow-none" id="konPer_Ort" placeholder="Ort">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon3" style="width:100%;">Tel. Nr.</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="konPer_Tel" placeholder="Tel">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon3" style="width:100%;">Email Adresse</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="konPer_Email" placeholder="Email Adresse">
                        </div>
                        <div class="aler alert-danger text-center mt-1 p-2" style="display:none; width:100%;" id="errMsg02">
                            <strong>Bitte geben Sie eine gültige E-Mail Adresse an</strong>
                        </div>
                    </div>
                    <div style="width: 47%;"> 
                        <p style="font-size:1.5cm; color:white; background-color:rgba(7,19,50,255); padding:0.9cm;" class="text-center mb-2">Betrieb</p>

                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon1" style="width:100%;">Name Betrieb</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="bet_Betrieb" placeholder="Name Betrieb">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon2" style="width:100%;">Name Inhaber</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="bet_Inhaber" placeholder="Name Inhaber">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon3" style="width:100%;">Adresse</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="bet_Adresse" placeholder="Adresse">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon3" style="width:100%;">PLZ/Ort</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="bet_PLZ" placeholder="PLZ">
                            <input type="text" class="form-control shadow-none" id="bet_Ort" placeholder="Ort">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon3" style="width:100%;">Tel. Nr.</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="bet_Tel" placeholder="Tel">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend" style="width:30%;">
                                <span class="input-group-text" id="basic-addon3" style="width:100%;">Email Adresse</span>
                            </div>
                            <input type="text" class="form-control shadow-none" id="bet_Email" placeholder="Email Adresse">
                        </div>
                        <div class="aler alert-danger text-center mt-1 p-2" style="display:none; width:100%;" id="errMsg03">
                            <strong>Bitte geben Sie eine gültige E-Mail Adresse an</strong>
                        </div>
                    </div>
                    <div class="form-check" style="width: 100%;">
                        <input type="checkbox" class="form-check-input" id="dataPrChBox01" style="width:24px; height:24px;">
                        <label class="form-check-label pl-3" for="dataPrChBox01" style="font-size:24px; color:white;">Ich habe die Datenschutzbestimmungen zur Kenntnis genommen*</label>
                    </div>
                    <button style="color:white; background-color:rgba(7,19,50,255); padding:0.2cm; border-radius:4px; width:100%; font-size:1.3cm;" id="saveResRecKontBtn"
                    class="text-center shadow-none" onclick="saveResRecKont()">
                        Senden
                    </button>

                    <div class="aler alert-danger text-center mt-1 p-2" style="display:none; width:100%;" id="errMsg01">
                        <strong>Füllen Sie alle Felder aus, bevor wir die Registrierung dieser Empfehlung bearbeiten</strong>
                    </div>
                    <div class="aler alert-danger text-center mt-1 p-2" style="display:none; width:100%;" id="errMsg04">
                        <strong>Bevor Sie diese Instanz registrieren, müssen Sie die Datenschutzerklärung akzeptieren</strong>
                    </div>
                    <div class="aler alert-danger text-center mt-1 p-2" style="display:none; width:100%;" id="errMsg05">
                        <strong>Dieses Restaurant wurde bereits registriert. Versuchen Sie es mit einem anderen.</strong>
                    </div>
                    <div class="aler alert-success text-center mt-1 p-2" style="display:none; width:100%; font-size:1.3rem;" id="succMsg01">
                        <strong>Sie haben diese Empfehlung erfolgreich registriert, wir werden Sie mit weiteren Informationen kontaktieren</strong>
                    </div>

                    <div class="d-flex justify-content-between mt-4" style="width: 100%; padding:0.7cm; background-color:rgba(87,228,255,255);">
                        <p style="margin:0px; font-size:1.4rem; color:rgb(72,81,87);"><strong>Social</strong></p>
                        <div class="d-flex justify-content-between" style="width:50%;">
                            <a class="socialAn" style="width:16.5%" href="https://www.linkedin.com/company/scan-order-pay/?originalSubdomain=ch"><i class="fa-2x fa-brands fa-linkedin-in"></i></a>
                            <a class="socialAn" style="width:16.5%" href="https://www.instagram.com/qrorpa.ch"><i class="fa-2x fa-brands fa-instagram"></i></a>
                            <a class="socialAn" style="width:16.5%" href="https://www.google.com/search?kgmid=/g/11qzk6qb2y&hl=de-CH&q=QRorpa+-+Kontaktlos+bestellen+und+bezahlen&kgs=634384ad84d8a192&shndl=17&source=sh/x/kp/osrp/m5/5"><i class="fa-2x fa-brands fa-google"></i></a>
                            <a class="socialAn" style="width:16.5%" href="https://www.facebook.com/qrorpa"><i class="fa-2x fa-brands fa-facebook-f"></i></a>
                            <a class="socialAn" style="width:16.5%" href="https://qrorpa.ch/firstPIndex"><i class="fa-2x fa-solid fa-globe"></i></a>
                            <a class="socialAn" style="width:16.5%" href="mailto:info@qrorpa.ch"><i class="fa-2x fa-regular fa-envelope"></i></a>
                        </div>
                    </div>

                    
                </div>
            </div>
            <hr style="margin-top: 1cm; margin-bottom:1cm; color:white; border-top:1px solid white;">
            <div class="add-footer-side">
                <div class="sub-footer">
                    <div class="container text-center">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 links" style="color:white;">
                                <a class="footerAn" href="{{route('firstPage.impressum')}}">Impressum</a>  |  <a class="footerAn" href="{{route('firstPage.datenschutz')}}">Datenschutz</a> | <a class="footerAn" href="{{route('firstPage.datenschutz')}}">Geschäftsbedingungen</a>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <p style="color:#fff">  Copyright ©<script>document.write(new Date().getFullYear());</script> Alle Rechte vorbehalten</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
    function saveResRecKont(){
        $('#saveResRecKontBtn').prop('disabed',true);
        if(!$('#dataPrChBox01').is(":checked")){
            if($('#errMsg04').is(':hidden')){ $('#errMsg04').show(25).delay(5000).hide(25); }
        }else if(!$('#konPer_Vorname').val() || !$('#konPer_Name').val() || !$('#konPer_Adresse').val() || !$('#konPer_PLZ').val() || !$('#konPer_Ort').val() 
        || !$('#konPer_Tel').val() || !$('#konPer_Email').val() || !$('#bet_Betrieb').val() || !$('#bet_Inhaber').val() || !$('#bet_Adresse').val() 
        || !$('#bet_PLZ').val() || !$('#bet_Ort').val() || !$('#bet_Tel').val() || !$('#bet_Email').val()){
            if($('#errMsg01').is(':hidden')){ $('#errMsg01').show(25).delay(5000).hide(25); }
        }else{
            email1 = $('#konPer_Email').val();
            email2 = $('#bet_Email').val();
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(!$('#konPer_Email').val() || !regex.test(email1)){
                if($('#errMsg02').is(':hidden')){ $('#errMsg02').show(25).delay(5000).hide(25); }
            }else if(!$('#bet_Email').val() || !regex.test(email2)){
                if($('#errMsg03').is(':hidden')){ $('#errMsg03').show(25).delay(5000).hide(25); }
            }else{

                $.ajax({
                    url: '{{ route("resCRecom.saveInstance") }}',
                    method: 'post',
                    data: {
                        konPer_Vorname: $('#konPer_Vorname').val(),
                        konPer_Name: $('#konPer_Name').val(),
                        konPer_Adresse: $('#konPer_Adresse').val(),
                        konPer_PLZ: $('#konPer_PLZ').val(),
                        konPer_Ort: $('#konPer_Ort').val(),
                        konPer_Tel: $('#konPer_Tel').val(),
                        konPer_Email: $('#konPer_Email').val(),
                        bet_Betrieb: $('#bet_Betrieb').val(),
                        bet_Inhaber: $('#bet_Inhaber').val(),
                        bet_Adresse: $('#bet_Adresse').val(),
                        bet_PLZ: $('#bet_PLZ').val(),
                        bet_Ort: $('#bet_Ort').val(),
                        bet_Tel: $('#bet_Tel').val(),
                        bet_Email: $('#bet_Email').val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: (respo) => {
                        respo = $.trim(respo);
                        if(respo == 'resDuplicate'){
                            $('#bet_Betrieb').val('');
                            $('#bet_Inhaber').val('');
                            $('#bet_Adresse').val('');
                            $('#bet_PLZ').val('');
                            $('#bet_Ort').val('');
                            $('#bet_Tel').val('');
                            $('#bet_Email').val('');
                            $('#saveResRecKontBtn').prop('disabed',false);
                            if($('#errMsg05').is(':hidden')){ $('#errMsg05').show(25).delay(5000).hide(25); }
                        }else{
                        
                            // $("#freeProElements").load(location.href+" #freeProElements>*","");
                            $('#konPer_Vorname').val('');
                            $('#konPer_Name').val('');
                            $('#konPer_Adresse').val('');
                            $('#konPer_PLZ').val('');
                            $('#konPer_Ort').val('');
                            $('#konPer_Tel').val('');
                            $('#konPer_Email').val('');
                            $('#bet_Betrieb').val('');
                            $('#bet_Inhaber').val('');
                            $('#bet_Adresse').val('');
                            $('#bet_PLZ').val('');
                            $('#bet_Ort').val('');
                            $('#bet_Tel').val('');
                            $('#bet_Email').val('');
                            $('#dataPrChBox01').prop('checked', false); // Unchecks it
                            $('#saveResRecKontBtn').prop('disabed',false);
                            if($('#succMsg01').is(':hidden')){ $('#succMsg01').show(25).delay(5000).hide(25); }
                        }

                    },
                    error: (error) => { console.log(error); }
                });
            }
        }
    }
</script>