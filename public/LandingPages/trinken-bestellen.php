<!DOCTYPE html>
<html class="no-js" lang="de-DE">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="Trinken bestellen 📱 Das QRorpa-System für Gaststätten und Hotels 🍕 ermöglicht kontaktloses Bestellen und Bezahlen auf sehr kundenfreundliche und komfortable Weise">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="author" content="QRorpa">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="icon" href="style/assets/images/icons/favicon.ico" type="image/x-icon"/>

    <title>Trinken bestellen - Kontaktlos bestellen und bezahlen</title>
    <meta property="og:image" content="https://qrorpa.ch/storage/FP-images/logo.png" />
    <meta property="og:image:width" content="300" />
    <meta property="og:image:height" content="91" />
    <meta property="og:image:alt" content="Trinken bestellen | Das QRorpa-System für Gaststätten qrorpa.ch" />
    <meta property="og:locale" content="de_DE" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Trinken bestellen | Delivery &#038; Takeaway bei qrorpa.ch" />
    <meta property="og:description" content="Trinken bestellen 📱 Das QRorpa-System für Gaststätten und Hotels 🍕 ermöglicht kontaktloses Bestellen und Bezahlen auf sehr kundenfreundliche und komfortable Weise" />
    <meta property="og:url" content="https://www.qrorpa.ch" />
    <meta property="og:site_name" content="Kontaktlos bestellen &amp; Delivery &amp; Takeaway qrorpa.ch" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="style/_css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="style/assets/css/font-awesome.css">
    <link rel="stylesheet" href="style/assets/css/templatemo-lava.css">
    <link href="style/_css/custom-style.css" rel="stylesheet" type="text/css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;900&display=swap');


#regForm {
  background-image: url("style/images/bg.jpg");
  background-color: #cccccc;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
  padding: 0px;
  width: 100%;
  min-width: 300px;
  height: 100vh;
  padding-top: 5px;
  font-family: 'Montserrat', sans-serif;
  display: inline-table;

}

h1 {
  text-align: center;  
}

input {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
  background: #26457c;
    color: #fff;
    margin-top: 5px;
}
::placeholder {
  color: #fff;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
 color: #fff;
}

::-ms-input-placeholder { /* Microsoft Edge */
 color: #fff;
}
textarea{
  background: #26457c;
    color: #fff;
    padding: 10px;
    font-family: Raleway;
    font-size: 17px;
    margin-top: 5px;
}

/* Mark input boxes that gets an error on validation: */
input.invalid {
  box-shadow: 0px 0px 9px 0px #ff0303;

}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #fff;
  color: #1c1c1c;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  cursor: pointer;
  margin-top:10px;
  border-radius: 5%;
  background-color: #ffcc00;
}



#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;  
  border-radius: 50%;
  display: inline-block;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #04AA6D;
}
#lefttoright {
    width: 50px;
    height: 50px;
    position: absolute;
    top: 0;
    right: 0;
    background: yellow;
}
.image-area{
  text-align: -webkit-center;
  padding: 0px;
}
.image-area img{
  margin-top:0px !important;
}
.percentage{
  background-color: #ffcc00;
  padding:10px; 
  color: #303030;
  font-weight: 900;
  font-size: 15px;
}
.text-content{
  text-align: left;
  color: #fff;
  margin-top:30px;
  font-size: 14px;
}
.subtitel{
  background-color: #fff;
  padding:10px;
  color:#303030;
  font-size: 16px;
}
.sparen{
  background-color: #ffcc00;
  padding:10px; 
  color: #303030;
  font-weight: 900;
  font-size: 21px;
}
.services{
  background-color: #ffcc00;
  padding:10px; 
  color: #303030;
  font-weight: 900;
  font-size: 14px;
  margin-right:   1%;
  width:49%;
  margin-top:2%;
}

article {
  position: relative;
  width: 140px;
  height: 140px;
  margin: 5px;
  float: left;
  border: 2px solid #50bcf2;
  box-sizing: border-box;
  color:#fff;
  text-align: center;
  
}

article div {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  line-height: 25px;
  transition: .5s ease;
  text-align: -webkit-center;
}

article input {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  cursor: pointer;
  height: 100px;
}

input[type=checkbox]:checked ~ div {
  background-color: #50bcf2;
}

.upgrade-btn {
  display: block;
  margin: 30px auto;
  width: 200px;
  padding: 10px 20px;
  border: 2px solid #50bcf2;
  border-radius: 50px;
  color: #f5f5f5;
  font-size: 18px;
  font-weight: 600;
  text-decoration: none;
  transition: .3s ease;
}

.upgrade-btn:hover {
  background-color: #50bcf2;
}

.blue-color {
  color: #50bcf2;
}

.gray-color {
  color: #555;
}

.social i:before {
  width: 14px;
  height: 14px;
  position: fixed;
  color: #fff;
  background: #0077B5;
  padding: 10px;
  border-radius: 50%;
  top:5px;
  right:5px;
}

@keyframes slidein {
  from {
    margin-top: 100%;
    width: 300%;
  }

  to {
    margin: 0%;
    width: 100%;
  }
}
@media(max-width:   450px)
{
  .checkbox-area{
    width: 30%;
    padding: 0px;
  }
}
@media(max-width:   767px) and (min-width: 451px)
{
  .checkbox-area{
    width: 30%;
    padding: 0px;
  }

}
@media(max-width:   1024px) and (min-width: 768px)
{
  .checkbox-area{
    width: 31%;
    padding: 0px;
  }
}
@media(min-width:   1025px)
{
  .checkbox-area{
    width: 31.5%;
    padding: 0px;
  }
  p.content-text.right-text-essen {
    margin-top: -20px;
}
}
.contact-free{
  color:#ffcc00;
}
.long-text{
  line-height: 1.3;
  font-size: 13px;
}
@media(max-width: 600px)
{
#personal:before{
  content: "Personal-\Averwaltung ";
    white-space: pre;

}
#table-reservation:before{
  content: "Tisch-\Areservierung ";
    white-space: pre;

}
#waren:before{
  content: "Waren-\Awirtschaft ";
    white-space: pre;

}
.check-image{
    max-width: 65% !important;
  }
article{
  height: 110px;
}
input{
  padding: 5px;
}
}
@media(min-width: 601px){
  #personal:before{
  content: "Personalverwaltung ";

}
#table-reservation:before{
  content: "Tischreservierung ";

}
#waren:before{
  content: "Warenwirtschaft ";

}
.image-area{
  margin-top:30px;
}

}
#required-fields, #required-fields2{
    color: #ff0000;
    margin: 5px;
}
.header-area .main-nav .nav li a{
  height: 54px !important;
}
.content-text{
  color:#fff;
}
.add-footer-side{
  height: 140px;
  margin-top: 0;
}
.sub-footer{
  bottom: 20px;
}
#system-process{
  margin-bottom: 0;
}
.right-text-essen{
  font-size: 12px;
  line-height: 1.4;
  margin-top: 4px;
  margin-left: -18px;
}
@media(max-width: 375px){
  .right-text-essen{
    margin-left: -10px;
  }
  .content-text{
    line-height: 1.5;
  }

}
</style>

</head>

<body>

    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="/" class="logo" title="Essen Abholen">
                            <img src="style/images/logo_QRorpa.png" alt="Essen Abholen" style="width: 140px; height: auto;">
                        </a>                     
                        <ul class="nav">
                            <li class="scroll-to-section" id="login-button"><a href="https://qrorpa.ch">Home</a></li>
                            <li class="scroll-to-section" id="login-button"><a href="https://qrorpa.ch/#about">Eigenschaften</a></li>
                            <li class="scroll-to-section" id="login-button"><a href="https://qrorpa.ch/#kontakt">Kontakt</a></li>
                            <li class="scroll-to-section" id="login-button"><a href="https://qrorpa.ch/login"><i class="fa fa-user" aria-hidden="true"></i> Einloggen</a></li>       
                            <li class="scroll-to-section" id="register-button"><a href="https://qrorpa.ch/register"><i class="fa fa-user" aria-hidden="true"></i> Registrieren</a></li>     
                            
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>
 
    <section class="section about" id="system-process">
      <form id="regForm" method="POST" action="scripts/contact-process.php">
 

  <!-- One "tab" for each step in the form: -->
  <div class="tab">
    <div class="col-md-6 col-md-offset-3 text-content">
      <h3 style="text-align: center;"> We are <span style="color:#ffcc00">QR</span> order & pay 4 all</h3><br>

      <p class="subtitel">Kontaktlos bestellen und bezahlen</p><br>

<span class="percentage"> 50% Günstiger</span> - Bis zu 2000.- sparen<br><br>

<span class="percentage"> Weiterempfehlen</span> - Bis zu 2000.- erhalten<br><br>


<p class="content-text" style="font-size:15px; margin-top:5px;">1 Monat kostenlos und unverbindlich Testen </p>
    </div>
     
    <div class="image-area col-md-12 col-sm-12 col-xs-12 " style="margin-top:0px;">
        <img src="style/images/img-slide3.png" class="img-responsive">
      </div>
  </div>
  <div class="tab">

    <div class="col-md-6 col-md-offset-3 text-content">
       <p class="content-text" style="margin-top:40px; font-size: 15px;"><span class="sparen">Sparen</span> Sie bis zu 20 Minuten Pro Gast</p><br><br>

<p class="content-text" style="text-align: left; font-size: 13px;">Ihre Gäste können mit dem Smartphone eine Bestellung abgeben.<br><br> Die Bestellung wird auf Ihrem Tablet/Smartphone oder Kassensystem angezeigt</p>


    </div>
     
    <div class="image-area col-md-12 col-sm-12 col-xs-12 ">
        <img src="style/images/img-slide1.png" class="img-responsive">
      </div>
  </div>
  <div class="tab">
    <div class="col-md-6 col-md-offset-3 text-content" style="margin-top:20px;"> 
      <p class="content-text" style="font-size: 15px; text-align: l"><span class="sparen">Mehr Umsatz</span> - weniger Ausgaben</p><br>

<p class="content-text" style="text-align: left; font-size: 13px;">Dank QRorpa werden Sie mehr Umsatz generieren. <br>Die Menükarte ist individuell anpassbar und die Marketingstrategien sind einzigartig.</p><br>
<p class="content-text" style="font-size: 13px;"><span class="percentage">Bis zu 30%</span> weniger Ausgaben für Ihr Personal</p><br>
    </div>

  
      <div class="col-xs-5 col-md-offset-3" style="margin-top:10px;">
        <span class="percentage">Essen retten</span>
      </div>
     <div class="col-xs-7 col-md-offset-5 col-lg-offset-4" style="padding: 0;">
        <p class="content-text right-text-essen"> Produkt fotografieren und in Echtzeit den Gästen präsentieren</p>
      </div> 
   
    
    <div class="image-area col-md-12 col-sm-12 col-xs-12 ">
        <img src="style/images/img-slide5.png" class="img-responsive">
      </div>
  </div>
  <div class="tab">
    <div class="col-md-6 col-md-offset-3 text-content"> 
      <p class="content-text" style="margin-top:40px; font-size: 15px; text-align: center;">Unsere <span class="sparen">Dienstleistungen</span></p><br><br>
      <p class="content-text sparen" style=" font-size: 14px; text-align: center; font-weight: normal; color: #303030; margin-right: 4px; font-weight: 900;">Am Tisch kontaktlos bestellen & bezahlen</p>
      <div class="col-sm-6 col-xs-6 services">Takeaway</div>
      <div class="col-sm-6 col-xs-6 services">Delivery</div>
      <div class="col-sm-6 col-xs-6 services">Tischreservierung</div>
      <div class="col-sm-6 col-xs-6 services">Covid-19 Formular</div>
      <div class="col-sm-6 col-xs-6 services">Warenwirtschaft</div> 
      <div class="col-sm-6 col-xs-6 services">Personalverwaltung</div>
      
    </div>
     
    <div class="image-area col-md-12 col-sm-12 col-xs-12 ">
        <img src="style/images/img-slide6.png" class="img-responsive">
      </div>
  </div>
  <div class="tab">
    <div class="col-md-6 col-md-offset-3">
      <p class="content-text" style="text-align: left; font-size: 15px;padding: 5px; margin-top: 20px; color: #fff;"> Füllen Sie bitte diese Felder aus, damit wir Ihnen eine <span class="contact-free">unverbindliche</span> und <span class="contact-free">kostenlose</span> Offerte zusenden können.</p>
      <p class="content-text" style="text-align: left; font-size: 15px;padding: 5px; margin-top: 20px;"> Am Tisch kontaktlos bestellen & bezahlen</p>
      <p id="required-fields"></p>
     
    <div class="col-xs-6" style=" padding:5px">
        <p style="font-size: 15px; color:#fff;">Anzahl Tische?</p>
        <p><input  oninput="this.className = ''" name="tableQty" type="number"></p>
    </div>
    <div class="col-xs-6" style=" padding:5px">
      <p style="font-size: 15px;  color:#fff;">Anzahl Mitarbeiter?</p>
        <p><input oninput="this.className = ''" name="workersQty" type="number"></p>
    </div>
  </div>
  <div class="col-xs-12 col-md-offset-3"><p style="color:#fff; padding: 5px;"> Weitere Systeme:</p></div>
    <div class="col-md-6 col-md-offset-3">
      
    <article class="col-xs-6 col-sm-4 col-md-4 checkbox-area feature1">
    <input type="checkbox" name="Services[]" id="feature1" value="Takeaway" />
    <div>
      <span>Takeaway
        <img src="style/images/takeaway.png" class="img-responsive check-image" >
      </span>
    </div>
  </article>
  <article class="col-xs-6 col-sm-4 col-md-4 checkbox-area feature2">
    <input type="checkbox" name="Services[]" id="feature2" value="Delivery" />
    <div>
    <span>Delivery
         <img src="style/images/delivery.png" class="img-responsive check-image">
      </span>
    </div>
  </article>
  
  <article class="col-xs-6 col-sm-4 col-md-4 checkbox-area feature3">
    <input type="checkbox" name="Services[]" id="feature3"  value="Tischreservierung"/>
    <div>
      <span class="long-text" id="table-reservation">
       <img src="style/images/table-reservation.png" class="img-responsive check-image">
     </span>
    </div>
  </article>
  
  <article class="col-xs-6 col-sm-4 col-md-4 checkbox-area feature4">
    <input type="checkbox" name="Services[]" id="feature4"  value="Covid-19 Formular"/>
    <div>
      <span class="long-text" style=" font-size: 13.5px;">Covid-19 Formular
       <img src="style/images/covid.png" class="img-responsive check-image">
     </span>
    </div>
  </article>

  <article class="col-xs-6 col-sm-4 col-md-4 checkbox-area feature5">
    <input type="checkbox" name="Services[]" id="feature5"  value="Warenwirtschaft" />
    <div>
      <span class="long-text" id="waren">
       <img src="style/images/stock-management.png" class="img-responsive check-image">
     </span>
    </div>
  </article>

  <article class="col-xs-6 col-sm-4 col-md-4 checkbox-area feature6">
    <input type="checkbox" name="Services[]" id="feature6" value="Personalverwaltung" />
    <div>
      <span class="long-text" id="personal">
       <img src="style/images/human-resources.png" class="img-responsive check-image">
     </span>
    </div>
  </article>
    </div>
    
  </div>
   <div class="tab" id="last-tab">
    <div class="col-md-6 col-md-offset-3">
      <div class="col-md-12" style="margin-top: 20px; padding: 5px; color:#fff; font-size: 14px;">
        <span class="percentage"> 50% Günstiger</span> - Bis zu 2000.- sparen<br><br>

<span class="percentage"> Weiterempfehlen</span> - Bis zu 2000.- erhalten<br><br>
        <p class="content-text" style="font-size:14px; margin-top:10px;">1 Monat kostenlos und unverbindlich Testen </p>
      </div>
      
      <p style="text-align: left; font-size: 15px;padding: 5px; margin-top: 20px; color: #fff;"> Bitte füllen Sie die Felder mit Ihren persönlichen Daten aus.</p>

      <p id="required-fields2"></p>
      <p><input  oninput="this.className = ''" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name/Vorname*'" placeholder="Name/Vorname*" name="Name" type="text"></p>
      <p><input  oninput="this.className = ''" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Adresse*'" placeholder="Email Adresse*" name="Email" type="email"></p>
      <p><input  oninput="this.className = ''" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Telefonnummer*'" placeholder="Telefonnummer*" name="Tel" type="tel"></p>
      <p><textarea  oninput="this.className = ''" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Kommentar'" placeholder=" Kommentar" name="Message" style="width:100%; height: 150px;"></textarea></p>

  
    </div>
    
  </div>
    <div style="text-align: center; padding-bottom: 20px; float:  left; width:  100%;">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)"><<</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Weiter</button>
    </div>
  <!-- Circles which indicates the steps of the form: -->
  <div style="text-align:center;margin-top:40px; display: none;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
  </div>
</form>
</section>
   


<div class="add-footer-side">
  <div class="sub-footer">
    <div class="container">
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 links">
            <a href="https://qrorpa.ch/datenschutz">Datenschutz</a> | <a href="#">Geschäftsbedingungen</a>
         </div>
         <div class="col-lg-12 col-md-12 col-sm-12">
            <p style="color:#fff">  Copyright ©2021 Alle Rechte vorbehalten | mit <i class="fa fa-heart-o" aria-hidden="true"></i> gemacht von <a href="https://kreativeidee.ch" target="_blank" style="color:#fff;">Kreative Idee</a></p>
         </div>

      </div>
    </div>
  </div>
</div>
<!-- <div class="col-lg-12 col-md-12 col-sm-12 call-button">
          <a class="call-text" href="tel:+41 76 580 65 43"><i class="fa fa-phone fa-lg"></i> Jetzt anrufen</a>
         </div> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="style/assets/js/bootstrap.min.js"></script>   
    <script src="style/assets/js/owl-carousel.js"></script>
    <script src="style/assets/js/scrollreveal.min.js"></script>
    <script src="style/assets/js/custom.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?hl=de-DE" async defer></script>
    <script>
    
      $("#subscribe-form").submit(function(e){0==grecaptcha.getResponse().length?(alert("Bitte überprüfen Sie, ob Sie ein Mensch sind!"),e.preventDefault()):alert("Ihre Nachricht wurde erfolgreich gesendet")});
    </script>

    <script>

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Senden";
  } else {
    document.getElementById("nextBtn").innerHTML = "Weiter";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      document.getElementById("required-fields").innerHTML = 'Bitte füllen Sie die unten stehenden Felder aus!';
      document.getElementById("required-fields2").innerHTML = 'Bitte füllen Sie die unten stehenden Felder aus!';
      // and set the current valid status to false
      valid = false;
    }
  }

  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
    document.getElementById("required-fields").innerHTML = '';
     document.getElementById("required-fields2").innerHTML = '';
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>
   
     
</body>
</html>