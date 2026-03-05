

<div class="add-footer-side">
  <div class="sub-footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 links">
          <a href="{{route('firstPage.impressum')}}">Impressum</a>  |  <a href="{{route('firstPage.datenschutz')}}">Datenschutz</a> | <a href="{{route('firstPage.datenschutz')}}">Geschäftsbedingungen</a>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <p style="color:#fff">  Copyright ©<script>document.write(new Date().getFullYear());</script> Alle Rechte vorbehalten | mit <i class="fa fa-heart-o" aria-hidden="true"></i> gemacht von <a href="https://kreativeidee.ch" target="_blank" style="color:#fff;">Kreative Idee</a></p>
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