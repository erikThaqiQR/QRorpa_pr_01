<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="author" content="KreativeIdee">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>QR Orpa</title>
<!--

Lava Landing Page

https://templatemo.com/tm-540-lava-landing-page

-->

    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/templatemo-lava.css">

    <link rel="stylesheet" href="assets/css/owl-carousel.css">
</head>

<body>

<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5f32635ef87ad20c6d7cd560/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>

    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="/" class="logo">
                            <img src="assets/images/logo.png" alt="" style="width: 140px; height: auto;">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class=""><a href="https://www.qrorpa.ch" class="">Home</a></li>
                            <li class=""><a href="https://www.qrorpa.ch/#about" class="">Eigenschaften</a></li>
                            <li class="scroll-to-section"><a href="https://qrorpa.ch/.ch/register">Registrieren</a></li>                    
                            <li class=""><a href="#kontakt" class="">Kontakt</a></li>
                            <li class="scroll-to-section"><button id="scan" type="button" class="main-button" data-toggle="modal" data-target="#myModal" ><i class="fa fa-qrcode" aria-hidden="true"></i> QR-Code scannen</button></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
        </div>
    </div>
</div>
   


    <div class="right-image-decor"></div>

    <!-- ***** Footer Start ***** -->
    <footer id="kontakt">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <!-- ***** Contact Form Start ***** -->
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="contact-form">
                            <?php
                                // Checks if form has been submitted
                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    function post_captcha($user_response) {
                                        $fields_string = '';
                                        $fields = array(
                                            'secret' => '6Lcre88ZAAAAALuTaizViR_nBe62oxJFOlpOof9T',
                                            'response' => $user_response
                                        );
                                        foreach($fields as $key=>$value)
                                        $fields_string .= $key . '=' . $value . '&';
                                        $fields_string = rtrim($fields_string, '&');

                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                                        curl_setopt($ch, CURLOPT_POST, count($fields));
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

                                        $result = curl_exec($ch);
                                        curl_close($ch);

                                        return json_decode($result, true);
                                    }

                                    // Call the function post_captcha
                                    $res = post_captcha($_POST['g-recaptcha-response']);

                                    if (!$res['success']) {
                                        // What happens when the CAPTCHA wasn't checked
                                        echo '<p>Please go back and make sure you check the security CAPTCHA box.</p><br>';
                                    } else {
                                        // If CAPTCHA is successfully completed...

                                        // Paste mail function or whatever else you want to happen here!
                                        echo '<br><p>CAPTCHA was completed successfully!</p><br>';
                                    }
                                } else { ?>
                            <form class="form-contact contact_form validate-form" method="POST" action="contact-process.php" id="subscribe-form" onsubmit="sentNotification()">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <fieldset>
                                            <input name="Name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name/Vorname'" placeholder="Name/Vorname" required=""
                                                style="background-color: rgba(250,250,250,0.3);">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <fieldset>
                                            <input name="Email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Adresse'" placeholder="Email Adresse" required="" style="background-color: rgba(250,250,250,0.3);">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <fieldset>
                                            <input name="Subject" id="subject" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Betreff'" placeholder="Betreff"
                                                style="background-color: rgba(250,250,250,0.3);">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-12">
                                        <fieldset>
                                            <textarea name="Message" id="message" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Kommentar'" placeholder=" Kommentar" style="background-color: rgba(250,250,250,0.3);"></textarea>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-12">
                                        <fieldset>
                                            <button  type="submit" value="Submit" id="submit" name="Submit" class="main-button">Senden
                                                Now</button>
                                        </fieldset>
                                    </div>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- ***** Contact Form End ***** -->
                    <div class="right-content col-lg-6 col-md-12 col-sm-12">
                        <h2>In Kontakt kommen</h2>
                        <p style="font-size: 18px;"><i class="fa fa-mobile fa-2x" aria-hidden="true" style="margin-right: 10px;"></i> <strong style="display: contents;">076 580 65 43</strong><br>
                            Mo bis Fr 9:00 bis 18:00 Uhr
                           </p><br>
                        <p style="font-size: 18px; margin-top:30px;"><i class="fa fa-envelope" aria-hidden="true" style="margin-right: 10px;"></i><strong style="display: contents;">info@qrorpa.ch/</strong><br>
                            Senden Sie uns Ihre Anfrage jederzeit!
                           </p>
                        <ul class="social">
                            <li><a href="https://facebook.com/qrorpa"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://instagram.com/qrorpa"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="sub-footer">
                        <p>  Copyright ©<script>document.write(new Date().getFullYear());</script>2020 Alle Rechte vorbehalten | mit <i class="fa fa-heart-o" aria-hidden="true"></i> gemacht von <a href="https://kreativeidee.ch" target="_blank">Kreative Idee</a></p>
                    </div>
                </div>
            </div>
        </div>
               <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">QR-Code scannen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <button class="main-button" id="scanbutton">Scan</button>
                              <div class="video">
                                <video muted playsinline id="qr-video" width="100%"></video>
                              </div>
                  </div>
                 
                </div>
              </div>
            </div>
    </footer>



    <!-- jQuery -->
    <script src="assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

     <!-- Plugins -->
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/scrollreveal.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/imgfix.min.js"></script>


    <!-- Global Init -->
    <script src="assets/js/custom.js"></script>

        <script>
            function sentNotification()
            {
                alert('Ihre Nachricht wurde erfolgreich gesendet');
            }
        </script>
             <script type="module">


             
                import QrScanner from "/qr-scanner.min.js";
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