
@extends('LPages.layouts.appMaster')

@section('body')
<body>

  @include('LPages.comps.header01')
 
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
   

@include('LPages.comps.footer01')
   
     
</body>
@endsection