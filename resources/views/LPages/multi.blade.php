<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;900&display=swap');
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}

#regForm {
  background-image: url("images/bg.jpg");
  background-color: #cccccc;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
    margin: 100px auto;
    padding: 0px;
    width: 100%;
    min-width: 300px;
    position: absolute;
    height: auto;
    
    font-family: 'Montserrat', sans-serif;

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
}

/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
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
  font-family: Raleway;
  cursor: pointer;
  margin-top:40px;
}

button:hover {
  opacity: 0.8;
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
  opacity: 0.5;
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
.col2{
  width: 50%;
  height: auto;
  display: block;
  float: left;
}
.image-area{
  text-align: -webkit-center;
  padding: 0px;
}
.image-area img{
  margin-top:15px;
}
.percentage{
  background-color: #ffcc00;
  padding:10px; 
  color: #303030;
  font-weight: 900;
  font-size: 15px;
}
.text-content{
  text-align: center;
  color: #fff;
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
</style>
<body>

<form id="regForm" action="/action_page.php">
 

  <!-- One "tab" for each step in the form: -->
  <div class="tab">
    <div class="col-md-12 text-content">
      <h3 style="text-align: center;"> We are QR order & pay 4 all QRorpa</h3><br>

      <p class="subtitel">Kontaktlos bestellen und bezahlen</p><br>

<span class="percentage"> 50% Günstiger</span> - Angebot gültig bis 31.12.21<br><br>




<p style="font-size:15px; margin-top:5px;">1 Monat kostenlos und unverbindlich Testen </p>
    </div>
     
    <div class="image-area col-md-12 col-sm-12 col-xs-12 ">
        <img src="images/img-slide3.png" class="img-responsive">
        
      <!-- <img src="foto3.png" class="img-responsive">
      </div>

      <div class="image-area col-md-6 col-sm-6 col-xs-6">
        <img src="foto2.png" class="img-responsive">
        
      <img src="foto.png" class="img-responsive"> -->
      </div>
  </div>
  <div class="tab">

    <div class="col-md-12 text-content">
       <p style="margin-top:40px; font-size: 15px;"><span class="sparen">Sparen</span> Sie bis zu 20 Minuten Pro Gast</p><br><br>

<p style="text-align: left; font-size: 13px;">Ihre Gäste können mit dem Smartphone eine Bestellung abgeben.<br><br> Die Bestellung wird auf Ihrem Tablet/Smartphone oder Kassensystem angezeigt</p>


    </div>
     
    <div class="image-area col-md-12 col-sm-12 col-xs-12 ">
        <img src="images/img-slide1.png" class="img-responsive">
        
      <!-- <img src="foto3.png" class="img-responsive">
      </div>

      <div class="image-area col-md-6 col-sm-6 col-xs-6">
        <img src="foto2.png" class="img-responsive">
        
      <img src="foto.png" class="img-responsive"> -->
      </div>
  </div>
  <div class="tab">
    <div class="col-md-12 text-content"> 
      <p style="margin-top:40px; font-size: 15px;"><span class="sparen">Mehr Umsatz</span> - Weniger Ausgaben</p><br><br>

<p style="text-align: left; font-size: 13px;">Dank QRorpa werden Sie mehr Umsatz generieren. <br><br>Die Menükarte ist individuell anpassbar und die Marketingstrategien auf der Menükarta sind einzigartig.</p><br>
<span class="percentage">Bis zu 30%</span> weniger Ausgaben für Ihr Personal<br><br>
    </div>
     
    <div class="image-area col-md-12 col-sm-12 col-xs-12 ">
        <img src="images/img-slide5.png" class="img-responsive">
        
      <!-- <img src="foto3.png" class="img-responsive">
      </div>

      <div class="image-area col-md-6 col-sm-6 col-xs-6">
        <img src="foto2.png" class="img-responsive">
        
      <img src="foto.png" class="img-responsive"> -->
      </div>
  </div>
  <div class="tab">
    <div class="col-md-12 text-content">
     
  </div>
    
  </div>
  <div>
    <div style="text-align: center; padding-bottom: 20px;">
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Weiter</button>
    </div>
  </div>
  <!-- Circles which indicates the steps of the form: -->
  <div style="text-align:center;margin-top:40px; display: none;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
  </div>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
/*
                   $('#foo').css({ 'left': '0px', 'right': '' }).animate({
                        'left' : '30px'    
                    },2000); 
                    $('#foo2').css({ 'right': '0px', 'left': '' }).animate({
                        'right' : '30px'    
                    },3000);
                    $('#foo3').css({ 'left': '0px', 'right': '' }).animate({
                        'left' : '30px'    
                    },4000)   
                    $('#foo4').css({ 'right': '0px', 'left': '' }).animate({
                        'right' : '30px'    
                    },5000)
                  
*/
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
      // and set the current valid status to false
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
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
