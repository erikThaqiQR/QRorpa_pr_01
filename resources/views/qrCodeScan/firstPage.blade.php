<!DOCTYPE html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
     <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
     @include('fontawesome')

    <title>Document</title>


        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <link href="css/FP-font-awesome.css" rel="stylesheet">
        <link href="css/FP-templatemo-lava.css" rel="stylesheet">
        <link href="css/FP-owl-carousel.css" rel="stylesheet">
        <link href="css/FP-bootstrap.min.css" rel="stylesheet">

        <link href="css/FP-style.css" rel="stylesheet">
        <link href="css/FP-responsive-layered-slider.css" rel="stylesheet">

        <!-- swiper library -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</head>
<style>
    .btnQRCode{
        background-color: rgb(39,190,175);
        color: white;
        width:100%;
    }
</style>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-0 col-md-2 col-lg-4"></div>
            <div class="col-sm-12 col-md-8 col-lg-4 text-center">

                <img src="storage/images/logo_QRorpa_1024_512.png" style="width:70%; margin-left:15%; margin-right:15%;" alt="not found">

                <p style="width:100%; margin-top:40px; color:rgb(39,190,175); font-size:1.4rem;" class="text-center mt-5 mb-4">
                    <strong>Klicken Sie auf die Schaltfläche unten, um Ihre Kamera zu öffnen und den QR-Code anzuzeigen</strong>
                </p>
                
                <button id="scan" type="button" class="btn btnQRCode shadow-none mt-3" data-toggle="modal" data-target="#QrCodeScaner">
                    <i class="fa fa-qrcode" aria-hidden="true"></i> QR-Code scannen
                </button>

                <p style="width:100%; margin-top:40px; color:red;" class="text-center mt-5">
                    <strong>*Stellen Sie sicher, dass Sie dem Webbrowser die Erlaubnis erteilen, Ihre Kamera zu verwenden.</strong>
                </p>
               
            </div>
            <div class="col-sm-0 col-md-2 col-lg-4"></div>
        </div>
    </div>




    <div id="QrCodeScaner" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">QR-Code scannen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center"> <strong>Konzentrieren Sie sich auf den QR-Code</strong> </p>
                    <div class="video">
                        <video muted playsinline id="qr-video" width="100%"></video>
                    </div>
                </div> 
            </div>
        </div>
    </div>




     <!-- jQuery -->
     <script src="js/FP-jquery-2.1.0.min.js"></script>

<!-- Bootstrap -->
<script src="js/FP-popper.js"></script>
<script src="js/FP-bootstrap.min.js"></script>

<!-- Plugins -->
<script src="js/FP-owl-carousel.js"></script>
<script src="js/FP-scrollreveal.min.js"></script>
<script src="js/FP-waypoints.min.js"></script>
<script src="js/FP-jquery.counterup.min.js"></script>
<script src="js/FP-imgfix.min.js"></script>

<script type="text/javascript" src="js/FP-jquery-ui-1.10.4.min.js"></script>
    <script type="text/javascript" src="js/FP-responsive-layered-slider.js"></script> 


<!-- Global Init -->
<script src="js/FP-custom.js"></script>

    <script>
        $("#subscribe-form").submit(function(e) {
            var response = grecaptcha.getResponse();
            if(response.length == 0) { 
                //reCaptcha not verified
                alert("Bitte überprüfen Sie, ob Sie ein Mensch sind!"); 
                e.preventDefault();
            }else{
                alert('Ihre Nachricht wurde erfolgreich gesendet');
            }        
        });
    </script>
    <script type="module">


             
import QrScanner from "/js/FP-qr-scanner.min.js";
function sccc(){

    const video = document.getElementById('qr-video');
    const camHasCamera = document.getElementById('cam-has-camera');
    const camQrResult = document.getElementById('cam-qr-result');
    const camQrResultTimestamp = document.getElementById('cam-qr-result-timestamp');
    const fileSelector = document.getElementById('file-selector');
    const fileQrResult = document.getElementById('file-qr-result');

    function setResult(label, result) {
        label.textContent = result;
        camQrResultTimestamp.textContent = new Date().toString();
        label.style.color = 'teal';
        clearTimeout(label.highlightTimeout);
        label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
    }

    // ####### Web Cam Scanning #######

    QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);

    const scanner = 
    new QrScanner(
            video, result => redirect(result)
        );
    scanner.start();
    function redirect(result){
        if (result != null) {
            window.location.href = result;
            scanner.destroy();
        }
    }

}

var btn = document.getElementById("scan");

// Assigning event listeners to the button
btn.addEventListener("click", sccc);


</script>







</body>
</html>