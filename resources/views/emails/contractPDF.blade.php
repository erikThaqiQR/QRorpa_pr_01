<!DOCTYPE html>
<html lang="en">
  <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vertrag-{{$data["id"]}}-QRorpa-{{date('d-m-Y', strtotime($data["dateSignature"]))}}</title>
    <style>


a {
  color: #0087C3;
  text-decoration: none;
}

            /** Define now the real margins of every page in the PDF **/

body {
  position: relative;
  width: 100%;  
  height: auto; 
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-size: 14px; 
  font-family: Calibri, sans-serif;
}

@page { margin: 150px 50px; }
      .header { position: fixed; left: 0px; top: -150px; right: 0px; height: 80px; margin-top: 20px;font-size: 10px;}
        .footer { position: fixed; left: 0px; bottom: -20px; right: 0px; height: 50px; text-align: right;}
        .footer .pagenum:before { content: counter(page); }

.companyInfo1 {
  float: left;
  width: 25%;
}

.companyInfo1 img {
  height: 30px;
  margin-top:5px;
}

.companyInfo2 {
  width: 30%;
  float: left;
  border-left: 1px solid #c1c1c1;
  padding-left: 5px;
}
.companyInfo3 {
  width: 20%;
  float: left;
  border-left: 1px solid #c1c1c1;
  padding-left: 5px;
}
.companyInfo4 {
  width: 25%;
  float: left;
  border-left: 1px solid #c1c1c1;
  padding-left: 5px;
}



#client {
  min-width: 50%;
  float: left;
}

#client .to {
  color: #777777;
}

h2.name {
  font-size: 1.4em;
  font-weight: normal;
  margin: 0;
}

#invoice {
  min-width: 50%;
  float: right;
}

#invoice h1 {
  color: #0087C3;
  font-size: 2.4em;
  line-height: 1em;
  font-weight: normal;
  margin: 0  0 10px 0;
}

#invoice .date {
  font-size: 1.1em;
  color: #777777;
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 10px;
}

table th,
table td {
  text-align: left;
  border-bottom: 1px solid #FFFFFF;
}

table th {
  white-space: nowrap;        
  font-weight: normal;
  font-weight: bold;
}

table td {
  text-align: left;
}

table td h3{
  color: #57B223;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .no {
  color: #FFFFFF;
  font-size: 1.6em;
  background: #57B223;
}

table .desc {
  text-align: left;
}

table .unit {
  background: #DDDDDD;
}

table .qty {
}

table .total {
  background: #57B223;
  color: #FFFFFF;
}

table td.unit,
table td.qty,




table tfoot td {
  padding: 10px 20px;
  background: #FFFFFF;
  border-bottom: none;
  font-size: 1.2em;
  white-space: nowrap; 
  border-top: 1px solid #AAAAAA; 
  text-align: right;
}

table tfoot tr:first-child td {
  border-top: none; 
  text-align: right;
}

table tfoot tr:last-child td {
  color: #57B223;
  font-size: 1.4em;
  border-top: 1px solid #57B223; 
  text-align: right;
}

table tfoot tr td:first-child {
  border: none;
}
#notices{
  padding-left: 6px;
  border-left: 6px solid #0087C3;  
  margin-top:50px;
}

#notices .notice {
  font-size: 1.2em;
}

.signatures{
    position: absolute;
    bottom: 15%;
}
#signature1{
    width:20%;
    float: left;
    border-top: 1px solid #c1c1c1;
    text-align: center;
}
#signature2{
    width:20%;
    float: right;
    border-top: 1px solid #c1c1c1;
    text-align: center;
}

.note{
  border:1px solid #e6e6e6;
  padding-left:10px;
}
.note h3{
    margin: 0;
}
td.sum, th.sum{
  text-align: right;
}

td.price, td.sum {
    border: 1px solid #1c1c1c;
}
table thead {
    display: table-row-group;
  }

  /*Long text always in a new page*/
 .page-break {
    page-break-after: always;
}


.cliendDataText {
    min-width: 50%;
    float: left;
}
.clientDataValue {
    border-bottom: 1px solid;
    float: left;
    text-align: right;
}
#invoice .clientDataValue {
    width: 50%;
}
#invoice .cliendDataText {
    text-align: right;
}
.tableTitle {
    font-size: 18px;
    font-weight: bold;
    background-color: #dad6d6;
    padding: 5px;
}
.tableArea {
    border: 2px solid #1c1c1c;
    padding: 10px;
    font-weight: bold;
}
span.checkIcon {
    color: #8bf98f;
    font-family: DejaVu Sans, sans-serif;
}

.description p, .description p{
  border-bottom: 1px solid #1c1c1c;
}
.sum, .price, .description{
  padding:10px;
}


main {
    margin-top: 110px;
}
.longText{
  text-align: justify;
  font-size: 12.5px;
}
.longText b {
    line-height: 2;
}
#tableData td{
  width: 50%;
}
    </style>
  
  </head>
  <body>
<script type="text/php">
    if (isset($pdf)) {
        $x = 485;
        $y = 810;
        $text = " Seite {PAGE_NUM} von {PAGE_COUNT}";
        $font = null;
        $size = 11;
        $color =   array(0.565, 0.565, 0.565);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
   <div class="header">
      <div class="companyInfo1">       
        <!-- <img src="storage/offerPic/Kreative-Idee-Logo-Slogan.jpg'"> -->
        <img src="storage/offerPic/qrorpa-logo.jpg" style="padding-left: 5px;">
      </div>
      <div class="companyInfo2">
        <div><strong>QRorpa Kassensysteme GmbH</strong></div>
        <div>Giacomettistrasse 27</div>        
        <div>7000 Chur</div>
        <div>UID/MWST CHE-447.312.897</div>
      </div>
      <div class="companyInfo3">
        <div><a href="https://qrorpa.ch">www.qrorpa.ch/</a></div>
        <div><a href="mailto:info@qrorpa.ch">info@qrorpa.ch/</a></div>
        <div>Tel. +41 (0) 76 580 65 43</div>
      </div>
      <div class="companyInfo4">
        <div>Zahlungsverbindung:</div>
        <div>UBS Bank AG</div>
        <div>QRorpa Kassensysteme GmbH</div>       
        <div>IBAN: CH 24 0020 8208 1437 0801 M</div>
      </div>
        </div>

      </main>
        <div class="title" style="margin-top:-40px;">
            <h2>Bestellung  QRorpa  Systeme Bestell- und  Bezahlsystem</h2>
        </div>

          <table border="0" cellspacing="0" cellpadding="0" >
        <tbody>            
          <tr>
            <td class="description" style="padding: 0px;">
              <p style="border-bottom: none;"><b>Geschlecht </b></p>
              <p style="border-bottom: none;"><b>Strasse  und Nr. </b></p>
              <p style="border-bottom: none;"><b>PLZ / Ort</b></p>
              <p style="border-bottom: none;"><b>Tel. </b>
            </td>                     
            <td class="description">
              <p>{{$data["gender"]}}</p>
              <p>{{$data["street"]}} </p>
              <p>{{$data["plz"]}} / {{$data["ort"]}}</p>
              <p>{{$data["phoneNr"]}}</p>
            </td>  
            <td class="description" id="" >
              <p style="border-bottom: none; text-align: right""><b>Name </b></p>
              <p style="border-bottom: none; text-align: right"><b>Vorname </b></p>
              <p style="border-bottom: none; text-align: right"><b>Firma </b></p>
              <p style="border-bottom: none; text-align: right"><b>E-Mail </b>
            </td>
            <td class="description" style="padding-right: 0px;">
              <p>{{$data["name"]}}</p>
              <p>{{$data["lastname"]}}</p>
              <p>{{$data["company"]}}  </p>
              <p>{{$data["email"]}}</p>
            </td>          
          </tr>    
        </tbody>
       
      </table>

      <div class="top-text"><p><b>Der Kunde bestellt  bei Kreative  Idee die  folgende/n Dienstleistung/en:</b></p></div>
      <div class="tableTitle">
        Monatliche Kosten
      </div>
      <div class="tableArea">
        <span class="checkIcon">✔</span> Kontaktlos  bestellen und bezahlen  <span class="checkIcon"> ✔</span> Serviceteam über  System  rufen<br>
        <span class="checkIcon"> ✔</span> Covid-19  Kontaktformular <span class="checkIcon"> ✔</span> Empfohlene  Produkte  <span class="checkIcon"> ✔</span> Produkt Management<br>
        <span class="checkIcon"> ✔</span> Tischwechsel  <span class="checkIcon"> ✔</span> Trinkgeld <span class="checkIcon"> ✔</span> Gratis  Produkte  anbieten <span class="checkIcon"> ✔</span> Gutscheincode  <span class="checkIcon"> ✔</span> Kundenbindung

      </div>
       <table border="0" cellspacing="0" cellpadding="0" id="tableData">
        <tbody>            
          <tr>  
          <td class="price">
           <b>{{$data["tablesCope"]}}</b> - <i>Tische</i> ({{$data["tavolinatFormat"]}})
          </td>                     
            <td class="sum">  
              @if ($data["tablesPerMonth"] != 0)
                <b>CHF {{$data["tablesPerMonth"]}}.-/Monat + 1% provision.-</b>  
              @elseif ($data["tablesFixedPerMonth"] != 0)
                <b>CHF {{$data["tablesFixedPerMonth"]}}.-/Monat</b> 
              @elseif ($data["tablesPercentage"] != 0)
                <b>{{$data["tablesPercentage"]}}% provision.-</b> 
              @endif
            </td> 
          </tr>    
        </tbody>
      </table>

     <div class="tableTitle" style="margin-top:20px;">
        Zusatzoptionen
      </div>

      <table border="0" cellspacing="0" cellpadding="0"  id="tableData">
        <tbody>            
          <tr>
            <td class="price" style="font-size: 13px;  margin-top:20px;">
              @if ($data["TakeawayPerMonth"] == 0 && $data["TakeawayProvision"] == 0 && $data["TakeawayFixedPerMonth"] == 0 && $data["TakeawayPercentage"] == 0)
                <b style="margin-top:4px; text-decoration: line-through;">Takeaway</b> <br>
              @else
                <b style="margin-top:4px;">Takeaway</b> <br>
              @endif

              @if ($data["DeliveryPerMonth"] == 0 && $data["DeliveryProvision"] == 0 && $data["DeliveryFixedPerMonth"] == 0 && $data["DeliveryPercentage"] == 0)
                <b style="margin-top:4px; text-decoration: line-through;">Delivery</b> <br>
              @else
                <b style="margin-top:4px;">Delivery</b> <br>
              @endif

              @if ($data["TischreservierungPerMonth"] == 0)
                <b style="margin-top:4px; text-decoration: line-through;">Tischreservierung</b> <br>
              @else
                <b style="margin-top:4px;">Tischreservierung</b> <br>
              @endif

              @if ($data["WarenwirtschaftPerMonth"] == 0)
                <b style="margin-top:4px; text-decoration: line-through;">Warenwirtschaft & automatische Nachbestellungen</b> <br>
              @else
                <b style="margin-top:4px;">Warenwirtschaft & automatische Nachbestellungen</b> <br>
              @endif

              @if ($data["PersonalvertretungPerMonth"] == 0)
                <b style="margin-top:4px; text-decoration: line-through;">Personalverwaltung</b> <br>
              @else
                <b style="margin-top:4px;">Personalverwaltung</b> <br>
              @endif
            </td>                  
            <td class="price" style="text-align: right;font-size: 13px;">

              @if ($data["TakeawayPerMonth"] != 0)
                <b>CHF {{$data["TakeawayPerMonth"]}}.-/Monat + 1% provision.-</b><br>
              @elseif ($data["TakeawayFixedPerMonth"] != 0)
                <b>CHF {{$data["TakeawayFixedPerMonth"]}}.-/Monat</b><br>
              @elseif ($data["TakeawayPercentage"] != 0)
                <b>{{$data["TakeawayPercentage"]}}% provision.-</b><br>
              @else
                <b>CHF 0</b><br>
              @endif

              @if ($data["DeliveryPerMonth"] != 0)
                <b>CHF {{$data["DeliveryPerMonth"]}}.-/Monat + 1% provision.-</b><br>
              @elseif ($data["DeliveryFixedPerMonth"] != 0)
                <b>CHF {{$data["DeliveryFixedPerMonth"]}}.-/Monat</b><br>
              @elseif ($data["DeliveryPercentage"] != 0)
                <b>{{$data["DeliveryPercentage"]}}% provision.-</b><br>
              @else
                <b>CHF 0</b><br>
              @endif

              @if ($data["TischreservierungPerMonth"] != 0)
                <b>CHF {{$data["TischreservierungPerMonth"]}}.-/Monat</b><br>
              @else
                <b>CHF 0</b><br>
              @endif

              @if ($data["WarenwirtschaftPerMonth"] != 0)
                <b>CHF {{$data["WarenwirtschaftPerMonth"]}}.-/Monat</b><br>
              @else
                <b>CHF 0</b><br>
              @endif

              @if ($data["PersonalvertretungPerMonth"] != 0)
                <b>CHF {{$data["PersonalvertretungPerMonth"]}}.-/Monat</b><br>
              @else
                <b>CHF 0</b><br>
              @endif
            </td>            
          </tr>    
        </tbody>
      </table>


      <div class="tableTitle" style="margin-top:15px;">
        Vertragslaufzeit
      </div>

      <table border="0" cellspacing="0" cellpadding="0"  id="tableData">
        <tbody>            
          <tr>
       
          <td class="price">
           <b>{{$data["VertragsaufzeitYear"]}} Jahresvertrag</b>
          </td>                     
            <td class="sum"><b>{{$data["VertragsaufzeitPercentage"]}}%  günstiger</b></td>            
          </tr>    
        </tbody>
       
      </table>
      <table border="0" cellspacing="0" cellpadding="0"  id="tableData">
        <tbody>            
          <tr>
       
          <td class="price">
           <b>Preis gesamt pro Monat</b>
          </td>                     
            <td class="sum"><b>CHF {{$data["totalPerMonth"]}}.-</b></td>            
          </tr>
          <!-- <tr>
            <td class="price"><b>Gesamtsumme</b></td>                     
            <td class="sum"><b>CHF {{$data["total"]}}.-</b></td>            
          </tr>     -->
        </tbody>
       
      </table>

 <div class="page-break"></div>

      <div class="tableTitle" style="margin-top:25px;">
        Kosten  pro Transaktion
      </div>
      <table border="0" cellspacing="0" cellpadding="0" id="tableData" >
        <tbody>            
          <tr>
            <td class="price"><b>SMS-Gateway Bestellungen  verifizieren</b></td>                     
            <td class="sum"><b>CHF  0.10.- pro  SMS</b></td>            
          </tr>   
          <!-- <tr>
            <td class="price"><b>Online-Zahlungen: Twint</b></td>                     
            <td class="sum"><b>1.4% + CHF 0.28.- pro  Transaktion</b></td>            
          </tr>    
          <tr>
            <td class="price"><b>Online-Zahlungen: PostFinance Card</b></td>                     
            <td class="sum"><b>1.9% + CHF 0.28.- pro  Transaktion</b></td>            
          </tr>    
          <tr>
            <td class="price"><b>Online-Zahlungen: PostFinance E-Service</b></td>                     
            <td class="sum"><b>1.9% + CHF 0.28.- pro  Transaktion</b></td>            
          </tr>    
          <tr>
            <td class="price"><b>Online-Zahlungen: MasterCard</b></td>                     
            <td class="sum"><b>1.9% + CHF 0.28.- pro  Transaktion</b></td>            
          </tr>    
          <tr>
            <td class="price"><b>Online-Zahlungen: Visa </b></td>                     
            <td class="sum"><b>1.9% + CHF 0.28.- pro  Transaktion</b></td>            
          </tr>      -->
        </tbody>
       
      </table>

      <div class="tableTitle" style="margin-top:30px;">
        Einmalige Kosten  
      </div>
      <table border="0" cellspacing="0" cellpadding="0"  id="tableData">
        <tbody>            
          <tr>
       
          <td class="price">
           <b>Einrichtung,  Flyer,  Tischkartenhalter und Schulung</b>
          </td>                     
            <td class="sum"><b>CHF  {{$data["flyerCost"]}}.-</b></td>            
          </tr>    
        </tbody>
       
      </table>
      <p>Die  Vertragsparteien  anerkennen  mit ihrer Unterschrift  auch  die AGB „Allgemeinen Geschäftsbedingungen“ von Kreative Idee, welche  integrierender  Bestandteil dieses  Vertrages sind  und welche  ausdrücklich  zur Kenntnis  genommen  wurden.</p>
      <p><b>Bemerkungen:</b></p>
      <p>{{$data["theComment"]}}</p>

      <table border="0" cellspacing="0" cellpadding="0"  id="tableData">
        <tbody>            
          <tr>
       
          <td class="price" style="background: none;">
          <?php
            $dateOnTheEnd2D = explode('-',$data["dateOnTheEnd"]);
          ?>
           <p><b>Ort/Datum:</b> {{$data["ortOnTheEnd"]}} / {{$dateOnTheEnd2D[2]}}.{{$dateOnTheEnd2D[1]}}.{{$dateOnTheEnd2D[0]}}</p>
          </td>                     
            <td class="sum" style="background: none; text-align: center;" >

               <p style="border-bottom: 1px solid #c1c1c1"><img src="storage/contractFiles/{{$data['clSignature'] }}" style="width: 200px; height: 40px;">
               </p><p style="text-align: center;">{{$data["name"]}} {{$data["lastname"]}}</p>
              <p style="border-bottom: 1px solid #c1c1c1"><img src="storage/contractFiles/B_Haziri_nenshkrim.png" style="width: 200px; height: 40px;"></p>
              <p style="text-align: center;">QRorpa CEO Besart Haziri</p>
            </td>            
          </tr>    
        </tbody>
       
      </table>
      <div class="page-break"></div>
      







      <div style="margin-top:1.5cm;">
        <h2 style="color:rgb(39,190,175); text-align:center;"><strong>QRorpa Kassensysteme GmbH</strong></h2>
        <h4 style="color:rgb(72,81,87); text-align:center;"><strong>Kontaktlos bestellen und bezahlen</strong></h4>
        <h4 style="color:rgb(72,81,87); text-align:center;"><strong>QR-Code scann, Order & Pay</strong></h4>

        <h2 style="color:rgb(72,81,87); margin-top:0.7cm; text-align:center;"><strong>Impressum, AGB und Datenschutz</strong></h2>

        <img src="storage/images/Datenschutz_pic_01.JPG" style="width: 40%; margin-left:30%;" alt="">

        <div style="display:flex; flex-wrap: nowrap; justify-content: space-between;">
            <div style="width:100%;">
                <p style="color: rgb(39,190,175); line-height:19px; margin:0px; padding:2px;"><strong>QRorpa Kassensysteme GmbH</strong></p>
                <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px; font-size:1.2rem;"><strong>7000 Chur, CH</strong></p>
                <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px; font-size:1.2rem;"><strong>0765806543</strong></p>
                <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px; font-size:1.2rem;"><strong>www.qrorpa.ch/</strong></p>
                <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px; font-size:1.2rem;"><strong>Info@qrorpa.ch/</strong></p>
            </div>
            <div style="width:100%; text-align:right;">
                <img src="storage/images/Datenschutz_pic_02.JPG" alt="">
            </div>
        </div>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0.7cm 0 0.7cm 0; padding:2px; font-size:1.1rem;"><strong>Impressum</strong></p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;"><strong>QRorpa Kassensysteme GmbH</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Giacomettistrasse 27</p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">7000 Chur</p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Schweiz</p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Kontakt: info@qrorpa.ch/</p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Verantwortliche Person: Besart Haziri, Fatlum Haziri</p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Firmennummer: CHE-350.4.007.063-6 (gem. Handelsregister)</p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">UID/MWST. CHE-447.312.897</p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;"><strong>Haftungsausschluss</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">QRorpa Kassensysteme GmbH übernimmt keinerlei Gewähr hinsichtlich der inhaltlichen Richtigkeit, Genauigkeit, Aktualität, Zuverlässigkeit und Vollständigkeit der Informationen. Haftungsansprüche wegen Schäden materieller oder immaterieller Art, welche aus dem Zugriff oder der Nutzung bzw. Nichtnutzung der veröffentlichten Informationen, durch Missbrauch der Verbindung oder durch technische Störungen entstanden sind, werden ausgeschlossen. Alle Angebote sind unverbindlich. QRorpa Kassensysteme GmbH behält es sich ausdrücklich vor, Teile der Seiten oder das gesamte Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig einzustellen.</p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;"><strong>Haftung für Links</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Verweise und Links auf Webseiten Dritter liegen ausserhalb unseres Verantwortungsbereichs. Es wird jegliche Verantwortung für solche Webseiten abgelehnt. Der Zugriff und die Nutzung solcher Webseiten erfolgen auf eigene Gefahr des Nutzers oder der Nutzerin</p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;"><strong>Urheberrechte</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Die Urheber- und alle anderen Rechte an Inhalten, Bildern, Fotos oder anderen Dateien auf der Website gehören ausschliesslich QRorpa Kassensysteme GmbH oder den speziell genannten Rechtsinhabern. Für die Reproduktion jeglicher Elemente ist die schriftliche Zustimmung der Urheberrechtsträger im Voraus einzuholen.</p>
        
        <p style="color:rgb(72,81,87); line-height:19px; margin:0.7cm 0 0.7cm 0; padding:2px; font-size:1.2rem;"><strong>Allgemeine Geschäfts- und Datenschutzbestimmungen</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;"><strong>QRorpa Kassensysteme GmbH</strong>, Giacomettistrasse 27, CH-7000 Chur (CHE-447.312.897), vertreten durch Besart Haziri und Fatlum Haziri, ist Betreiberin der Website www.qrorpa.ch/ und somit verantwortlich für die Erhebung, Verarbeitung und Nutzung Ihrer persönlichen Daten und die Vereinbarkeit der Datenbearbeitung mit dem geltenden Recht. Ihr Vertrauen ist uns wichtig. Wir nehmen das Thema Datenschutz ernst und achten auf entsprechende Sicherheit. Selbstverständlich beachten wir die gesetzlichen Bestimmungen des Bundesgesetzes über den Datenschutz (DSG), der Verordnung zum Bundesgesetz über den Datenschutz (VDSG), des Fernmeldegesetztes (FMG) und, soweit anwendbar, andere datenschutzrechtliche Bestimmungen, insbesondere die Datenschutzgrundverordnung der Europäischen Union (DSGVO). Damit Sie wissen, welche personenbezogenen Daten wir von Ihnen erheben und für welche Zwecke wir sie verwenden, nehmen Sie bitte die nachstehenden Informationen zur Kenntnis.</p>





        <p style="color:rgb(72,81,87); line-height:19px; margin:0.7cm 0 0.7cm 0; padding:2px; font-size:1.2rem;"><strong>Allgemeine Geschäftsbedingungen der QRorpa Kassensysteme GmbH</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>1. Geltungsbereich</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Diese Allgemeinen Geschäftsbedingungen („AGB“) der QRorpa Kassensysteme GmbH, Giacomettistrasse 27, CH-7000 Chur (nachfolgend „QRorpa Systeme“) gelten für sämtliche Geschäftsbeziehungen der Kunden mit QRorpa Kassensysteme GmbH. 
            <br>QRorpa Kassensysteme GmbH vertreibt das Bestell- und Bezahlsystem QRorpa Systeme für Restaurants, Bars, Hotels, Discos, Lounge, Stadions und vieles Mehr. Mit diesem System können Gäste am Tisch oder an einem bestimmten Platz über einen QR-Code auf ihrem Mobiltelefon die Menükarte des Restaurants einsehen und diese direkt online mit den gängigen Zahlungsmitteln bezahlen. 
            <br>Dazu besitzt und betreibt QRorpa Kassensysteme GmbH die Website www.qrorpa.ch/.  
            <br>Als Kunde wird jede natürliche und juristische Person bezeichnet, welche mit QRorpa Kassensysteme GmbH geschäftliche Beziehungen pflegt. 
            <br>Diese AGB gelten ausschliesslich. Entgegenstehende, ergänzende oder von diesen AGB abweichende Bedingungen bedürfen zu ihrer Geltung der ausdrücklichen schriftlichen Bestätigung durch QRorpa Kassensysteme GmbH. 
            <br>Der Kunde bestätigt bei der Inanspruchnahme von Dienstleistungen von QRorpa Kassensysteme GmbH und bei der Nutzung von www.qrorpa.ch/ bzw. bei Vertragsschluss, diese AGB umfassend anzuerkennen.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>2. Informationen auf www.qrorpa.ch/</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">www.qrorpa.ch/ beinhaltet Informationen über Dienstleistungen. Preisänderungen sowie sonstige Änderungen bleiben vorbehalten. Alle Angaben auf www.qrorpa.ch/ (Dienstleistungsbeschreibungen, Abbildungen, Filme, technische Spezifikationen und sonstige Angaben) sind nur als Näherungswerte zu verstehen und stellen insbesondere keine Zusicherung von Eigenschaften oder Garantien dar, ausser es ist explizit anders vermerkt. QRorpa Kassensysteme GmbH bemüht sich, sämtliche Angaben und Informationen auf www.qrorpa.ch/ korrekt, vollständig, aktuell und übersichtlich bereitzustellen, jedoch kann QRorpa Kassensysteme GmbH weder ausdrücklich noch stillschweigend dafür Gewähr leisten.
            <br><br>Sämtliche Angebote auf www.qrorpa.ch/ gelten als freibleibend und sind nicht als verbindliche Offerte zu verstehen. 
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>3. Vertragsabschluss</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Der Vertragsabschluss kommt in der Regel durch schriftliche Akzeptanz der Offerte von QRorpa Kassensysteme GmbH zustande. 
            <br>Der Vertrag kommt ferner zustande, wenn der Kunde die von der QRorpa Kassensysteme GmbH angebotenen Dienst-leistungen in Anspruch nimmt.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>4. Gebühren und Bezahlung</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Alle Offerten verstehen sich sämtliche Gebühren in Schweizer Franken (CHF), exkl. Mehrwertsteuer (MwSt).
            <br>Der Kunde ist verpflichtet, die von QRorpa Kassensysteme GmbH in Rechnung gestellten Gebühren innert 15 Tagen ab Rechnungsdatum zu bezahlen. 
            <br>Wird die Rechnung nicht binnen vorgenannter Zahlungsfrist beglichen, wird der Kunde abgemahnt. Begleicht der Kunde die Rechnung nicht binnen der angesetzten Mahnfrist fällt er automatisch in Verzug und schuldet eine Mahngebühr von CHF 20.—. Ab Zeitpunkt des Verzugs schuldet der Kunde zudem Verzugszinsen in der Höhe der gesetzlichen 5 %.
            <br>Verrechnung des in Rechnung gestellten Betrages mit einer allfälligen Forderung des Kunden gegen QRorpa Kassensysteme GmbH ist nicht zulässig.
            <br>QRorpa Kassensysteme GmbH steht das Recht zu, bei Zahlungsverzug des Kunden eine weitere Dienstleistungserbringung zu verweigern.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>5. Pflichten von QRorpa Kassensysteme GmbH</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Vorbehaltlich anderslautender Vereinbarung, erfüllt QRorpa Kassensysteme GmbH ihre Verpflichtung durch Erbringung der vereinbarten Dienstleistung. QRorpa Kassensysteme GmbH ist insbesondere verpflichtet, das QRorpa System zu betreiben und ohne Unterbrüche zur Verfügung zu stellen. Kurze Ausfälle, insbesondere zur Wartung oder für Updates sind möglich und entsprechend hinzunehmen.  
            <br>Der Kunde erhält sämtliche benötigen Flyer mit QR-Codes zum Austeilen an seine Tische. Der Kunde hat die Möglichkeit, von QRorpa Kassensysteme GmbH leihweise ein Tablet zu erhalten, mit welchem er die Gäste Bestellungen einsehen kann. Der Kunde kann individuelle Produkte und Menüs in seine Menükarte einstellen und deren Preise individuell anpassen.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>6. Pflichten des Kunden</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Der Kunde ist verpflichtet, sämtliche Vorkehrungen, welche zur Erbringung der Dienstleistung durch QRorpa Kassensysteme GmbH erforderlich sind, umgehend vorzunehmen. Er verpflichtet sich, das System QRorpa in seinem Betrieb exklusiv zu benutzen und für sämtliche Gäste verfügbar zu halten. Die Flyer mit den QR-Codes müssen bei den Tischen einsehbar sein und für die Gäste jederzeit zur Verfügung stehen. Andere eigene Systeme oder Systeme von Mitbewerbern zugleich zu benutzen, ist nicht zulässig. Der Kunde verpflichtet sich, das System QRorpa für sämtliche Bestellungen und Zahlungen in seinem Betrieb einzusetzen.  
            <br>Weiter ist der Kunde zur umfassenden und prompten Mitwirkung verpflichtet. QRorpa Kassensysteme GmbH geht davon aus, dass die vom Kunden gelieferten Informationen und Daten richtig und vollständig sind sowie den gesetzlichen Mitwirkungs- und Auskunftspflichten entsprechen. 
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>7. Vertragslaufzeit </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Die Vertragslaufzeit beträgt mindestens 1 Jahr und verlängert sich jeweils um ein weiteres Jahr, wenn der Kunde nicht drei Monate vor Vertragsablauf schriftlich (per E-Mail) kündigt. Bei Vertragsverlängerungen gelten die jeweils aktuellen Gebühren respektive die zu diesem Zeitpunkt aktuellen AGB von QRorpa Kassensysteme GmbH als vom Kunden akzeptiert.  </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>8. Gebühren</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">QRorpa Kassensysteme GmbH verrechnet für ihre Dienstleistung Gebühren gemäss der verschiedenen angebotenen Varianten.
            <br>Falls der Kunde von QRorpa Kassensysteme GmbH ein Tablet zur Benützung leiht, zahlt er dafür ein Depot von CHF 200.—, welches er bei der Rückgabe des Tablets wieder zurückbekommt. Allfällige Beschädigungen des Tablets werden vom Depot abgezogen.  
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong> 9. Gewährleistung</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">QRorpa Kassensysteme GmbH haftet im Sinne von Art. 398 Abs. 2 OR für getreue und sorgfältige Ausführung der bei ihr bestellten Dienstleistung. </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>10. Haftung</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">QRorpa Kassensysteme GmbH schliesst jede Haftung, unabhängig von ihrem Rechtsgrund, sowie Schadenersatzansprüche gegen QRorpa Kassensysteme GmbH und allfällige Hilfspersonen und Erfüllungsgehilfen, aus. QRorpa Kassensysteme GmbH haftet insbesondere nicht für indirekte Schäden und Mangelfolgeschäden, entgangenen Gewinn oder sonstige Personen-, Sach- und reine Vermögensschäden des Kunden. Vorbehalten bleibt eine weitergehende zwingende gesetzliche Haftung, beispielsweise für grobe Fahrlässigkeit oder rechtswidrige Absicht. </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>11. Datenschutz</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Die Datenschutzbestimmungen der QRorpa Kassensysteme GmbH für die Website www.qrorpa.ch/ sind integraler Bestandteil dieser AGB.  </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>12. Immaterialgüterrecht</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Sämtliche Rechte an den Dienstleistungen, Produkten und allfälligen Marken stehen QRorpa Kassensysteme GmbH zu oder sie ist zu deren Benutzung vom Inhaber berechtigt.
            <br>Weder diese AGB noch dazugehörige Individual-vereinbarungen haben die Übertragung von Immaterialgüterrechten zum Inhalt, es sei denn dies werde explizit erwähnt.
            <br>Zudem ist jegliche Weiterverwendung, Veröffentlichung und das Zugänglichmachen von Informationen, Bildern, Texten oder Sonstigem, was der Kunde Zusammenhang mit der Dienstleitung von QRorpa Kassensysteme GmbH erhält, untersagt, es sei denn es werde von QRorpa Kassensysteme GmbH explizit genehmigt.
            <br>Verwendet der Kunde im Zusammenhang mit QRorpa Kassensysteme GmbH Inhalte, Texte oder bildliches Material an welche Dritte ein Schutzrecht haben, hat der Kunde sicherzustellen, dass keine Schutzrechte Dritter verletzt werden.
            <br>Diese Allgemeinen Geschäftsbedingungen können von QRorpa Kassensysteme GmbH jederzeit geändert werden. Die neue Version tritt durch Publikation auf der Website von QRorpa Kassensysteme GmbH in Kraft.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>13. Höhere Gewalt</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Wird die fristgerechte Erfüllung durch QRorpa Kassensysteme GmbH, deren Lieferanten oder beigezogenen Dritten infolge höherer Gewalt wie beispielsweise Naturkatastrophen, Erdbeben, Vulkanausbrüche, Lawinen, Unwetter, Gewitter, Stürme, Kriege, Unruhen, Bürgerkriege, Revolutionen und Auf-stände, Terrorismus, Sabotage, Streiks, Atomunfälle resp. Reaktorschäden, Pandemien oder Epidemien verunmöglicht, so ist QRorpa Kassensysteme GmbH während der Dauer der höheren Gewalt sowie einer angemessenen Anlaufzeit nach deren Ende von der Erfüllung der betroffenen Pflichten befreit. Dauert die höhere Gewalt länger als 30 Tage kann QRorpa Kassensysteme GmbH vom Vertrag zurücktreten. QRorpa Kassensysteme GmbH hat dem Kunden bereits geleistetes Entgelt für noch nicht geleistete Dienstleistungen vollumfänglich zurückzuerstatten.
            <br>Jegliche weiteren Ansprüche, insbesondere Schaden-ersatzansprüche infolge via Major sind ausge-schlossen.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>14. Weitere Bestimmungen</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Sollten einzelne Bestimmungen dieser AGB ganz oder teilweise nichtig und/oder unwirksam sein, bleibt die Gültigkeit und/oder Wirksamkeit der übrigen Bestimmungen oder Teile solcher Bestimmungen unberührt. Die ungültigen und/oder unwirksamen Bestimmungen werden durch solche ersetzt, die dem Sinn und Zweck der ungültigen und/oder unwirksamen Bestimmungen in rechtwirksamer Weise wirtschaftlich am nächsten kommt. Das gleiche gilt bei eventuellen Lücken der Regelung.
            <br> Im Falle von Streitigkeiten kommt ausschliesslich materielles Schweizer Recht unter Ausschluss von kollisionsrechtlichen Normen zur Anwendung. 
            <br> Der Gerichtsstand ist Chur, soweit das Gesetz keine zwingenden Gerichtsstände vorsieht. 
            <br> Chur, 10. Juni 2021
        </p>




        <p style="color:rgb(72,81,87); line-height:19px; margin:0.7cm 0 0.7cm 0; padding:2px; font-size:1.2rem;"><strong>Datenschutzbestimmungen</strong></p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>1) Grundsätze der Datenverarbeitung auf www.qrorpa.ch/ </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Grundsätze der Datenverarbeitung auf www.qrorpa.ch/
            <br>a) werden auf rechtmässige Weise, nach Treu und Glauben und in einer für die betroffene Person nachvollziehbaren Weise verarbeitet.
            <br>b) werden einzig für die Durchführung und Abwicklung der auf der Website angebotenen Dienstleistungen und Angebote verwendet und werden unter keinen Umständen in für mit diesem Zweck nicht zu vereinbarende Weise weiterverarbeitet. 
            <br>c) welche im Hinblick auf die Zwecke ihrer Verarbeitung unrichtig sind, werden unverzüglich gelöscht oder berichtigt.
            <br>d) Werden nur so lange gespeichert, wie es für die Zwecke, für die sie verarbeitet werden, erforderlich ist. 
            <br>e) werden auf eine Weise verarbeitet, die eine angemessene Sicherheit gewährleistet, einschliesslich Schutz vor unbefugter oder unrechtmässiger Verarbeitung und vor unabsichtlichem Verlust, unbeabsichtigte Zerstörung oder unbeabsichtigte Schädigung.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>2) Rechtmässigkeit der Datenverarbeitung </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Indem der Nutzer die Website www.qrorpa.ch/ einsieht, gibt er seine Einwilligung zur Verarbeitung der ihn betreffenden personenbezogenen Daten, zumal damit auch ein überwiegendes Interesse des Nutzers angenommen werden kann. Der Nutzer hat das Recht, seine Einwilligung jederzeit zu widerrufen (per E-Mail auf info@qrorpa.ch/). Durch den Widerruf der Einwilligung wird die Rechtmässigkeit, der aufgrund der Einwilligung bis zum Widerruf erfolgten Verarbeitung nicht berührt. </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>3) Umfang und Zweck der Erhebung, Verarbeitung und Nutzung personenbezogener Daten</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>a) beim Besuch von www.qrorpa.ch/</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Beim Besuch von www.qrorpa.ch/ speichern unsere Server temporär jeden Zugriff in einer Protokolldatei. Folgende Daten werden dabei ohne Ihr Zutun erfasst und bis zur automatisierten Löschung nach spätestens zwölf Monaten von uns gespeichert:
            <br>- die IP-Adresse des anfragenden Rechners,
            <br>- das Datum und die Uhrzeit des Zugriffs,
            <br>- der Name und die URL der abgerufenen Datei,
            <br>- die Website, von der aus der Zugriff erfolgte,
            <br>- das Betriebssystem Ihres Rechners und der von Ihnen verwendete Browser,
            <br>- das Land von welchem sie zugegriffen haben und die Spracheinstellungen bei Ihrem Browser,
            <br>- der Name Ihres Internet-Access-Providers,
            <br>- der Browser, von welchem aus, der Zugriff erfolgte,
            <br>- die Spracheinstellungen des Browsers und
            <br>- wie lange der Benutzer auf der Website aktiv gewesen ist.
            <br>Die Erhebung und Verarbeitung dieser Daten erfolgt zu dem Zweck, die Nutzung unserer Website zu ermöglichen (Verbindungsaufbau), die Systemsicherheit und -stabilität zu gewährleisten und die Optimierung unseres Internetangebots zu ermöglichen sowie zu internen statistischen Zwecken. Die IP-Adresse wird insbesondere dazu verwendet, um das Aufenthaltsland des Website-Besuchers zu erfassen und eine darauf abgestimmte Voreinstellung der Sprache der Website vorzunehmen. Ferner wird die IP-Adresse bei Angriffen auf die Netzinfrastruktur von www.qrorpa.ch/ und zu statistischen Zwecken ausgewertet. Darüber hinaus verwenden wir beim Besuch unserer Website sogenannte Pixel und Cookies zur Anzeige von personalisierter Werbung sowie zur Nutzung von Webanalyse-Diensten. 
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>b) bei der Nutzung unseres Kontaktformulars </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Sie haben die Möglichkeit, ein Kontaktformular zu verwenden, um mit uns in Kontakt zu treten. Die Eingabe folgender personenbezogenen Daten hat zu erfolgen: 
            <br>- Anrede,
            <br>- Firma (optional),
            <br>- Vor- und Nachname,
            <br>- Adresse (Strasse, Hausnummer, Postleitzahl, Ort),
            <br>- Telefonnummer und
            <br>- E-Mail-Adresse.
            <br>Wenn diese Informationen nicht zur Verfügung gestellt werden, kann dies die Erbringung unserer Dienstleistungen behindern. Die Angabe anderer Informationen ist optional und hat keinen Einfluss auf Nutzung unserer Website. Wir verwenden diese Daten nur, um Ihre Kontaktanfrage bestmöglich und personalisiert beantworten zu können. Sie können dieser Datenverarbeitung jederzeit widersprechen und verlangen, dass wir Ihre Daten löschen (per E-Mail auf info@qrorpa.ch/). 
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>c) bei der Anmeldung für unseren Newsletter </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Sie haben auf unserer Website die Möglichkeit, unseren Newsletter zu abonnieren. Hierfür ist eine Registrierung erforderlich. Im Rahmen der Registrierung sind folgende Daten abzugeben:
            <br>- Firma (optional),
            <br>- Anrede (optional),
            <br>- Vor- und Nachname,
            <br>- Adresse (Strasse, Hausnummer, Postleitzahl, Ort; optional),
            <br>- Telefonnummer (optional) und
            <br>- E-Mail-Adresse.
            <br>Diese Daten bearbeiten wir ausschliesslich, um die Ihnen zugesendeten Informationen und Angebote zu personalisieren und besser auf Ihre Interessen auszurichten. Mit der Registrierung erteilen Sie uns Ihre Einwilligung zur Bearbeitung der angegebenen Daten für den regelmässigen Versand des Newsletters an die von Ihnen angegebene Adresse und für die statistische Auswertung des Nutzungsverhaltens und die Optimierung des Newsletters. Diese Einwilligung stellt im Sinne von Art. 6 Abs. 1 lit. a DSGVO unsere Rechtsgrundlage für die Verarbeitung Ihrer E-Mail-Adresse dar. Wir sind berechtigt, Dritte mit der technischen Abwicklung von Werbemassnahmen zu beauftragen und sind berechtigt, Ihre Daten zu diesem Zweck weiterzugeben. Am Ende jedes Newsletters findet sich ein Link, über den Sie den Newsletter jederzeit abbestellen können. Im Rahmen der Abmeldung können Sie uns freiwillig den Grund für die Abmeldung mitteilen. Nach der Abbestellung werden Ihre personenbezogenen Daten gelöscht. Eine Weiterbearbeitung erfolgt lediglich in anonymisierter Form zur Optimierung unseres Newsletters. Der Versand der Newsletter erfolgt z.T. mittels des Versanddienstleisters „MailChimp“, einer Newsletterversandplattform des US-Anbieters Rocket Science Group, LLC, 675 Ponce De Leon Ave NE #5000, Atlanta, GA 30308, USA. Die Datenschutzbestimmungen des Versanddienstleisters können Sie hier einsehen: https://mailchimp.com/legal/privacy/. The Rocket Science Group LLC d/b/a MailChimp ist unter dem Privacy-Shield-Abkommen zertifiziert und bietet hierdurch eine Garantie, das europäisches Datenschutzniveau einzuhalten (https://www.privacyshield.gov/participant?id=a2zt0000000TO6hAAG&status=Active). Der Versanddienstleister wird auf Grundlage unserer berechtigten Interessen gemäss Art. 6 Abs. 1 lit. f DSGVO und eines Auftragsverarbeitungsvertrages gemäss Art. 28 Abs. 3 S. 1 DSGVO eingesetzt. Der Versand der Newsletter erfolgt z.T. auch mittels des Versanddienstleisters „sendinblue“, einer E-Mail- und Newsletterversandplattform des Anbieters Sendinblue GmbH, Köpernicker Strasse 126, D-10179 Berlin. Die Datenschutzbestimmungen von Sendinblue GmbH können Sie hier einsehen: https://de.sendinblue.com/legal/privacypolicy/. Der Versanddienstleister wird auf Grundlage unserer berechtigten Interessen gemäss Art. 6 Abs. 1 lit. f DSGVO und eines Auftragsverarbeitungsvertrages gemäss Art. 28 Abs. 3 S. 1 DSGVO eingesetzt. Der Versanddienstleister kann die Daten der Empfänger in pseudonymer Form, d.h. ohne Zuordnung zu einem Nutzer, zur Optimierung oder Verbesserung der eigenen Services nutzen, z.B. zur technischen Optimierung des Versandes und der Darstellung der Newsletter oder für statistische Zwecke verwenden. Der Versanddienstleister nutzt die Daten unserer Newsletterempfänger jedoch nicht, um diese selbst anzuschreiben oder um die Daten an Dritte weiterzugeben. Die von Ihnen zum Zwecke des Newsletter-Bezugs bei uns hinterlegten Daten werden von uns bis zu Ihrer Austragung aus dem Newsletter gespeichert und nach der Abbestellung des Newsletters sowohl von unseren Servern als auch von den Servern des entsprechenden Newsletter-Dienstleisters gelöscht. Daten, die zu anderen Zwecken bei uns gespeichert wurden (z.B. E-Mail-Adressen für den Mitgliederbereich) bleiben hiervon unberührt.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>d) Registrierung und Kundenkonto</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Sie haben auf unserer Website die Möglichkeit, sich zu registrieren und damit ein Kundenkonto zu eröffnen. Dazu sind folgende Daten abzugeben:
            <br>- Firma (optional),
            <br>- Anrede,
            <br>- Vor- und Nachname,
            <br>- Adresse (Strasse, Hausnummer, Postleitzahl, Ort),
            <br>- Telefonnummer und
            <br>- E-Mail-Adresse,
            <br>- Login/Registrierung über Google
            <br>- Login/Registrierung über Facebook und
            <br>- Login/Registrierung über Apple.
            <br>Die obigen Daten sind für die Datenverarbeitung notwendig. Diese Daten bearbeiten wir ausschliesslich, um die Ihnen zugesendeten Informationen und Angebote zu personalisieren und besser auf Ihre Interessen auszurichten. 
            <br>Mit der Registrierung erteilen Sie uns Ihre Einwilligung zur Bearbeitung der angegebenen Daten für den regelmässigen Versand von Werbeaktionen (online oder print) an die von Ihnen angegebene Adresse und für die statistische Auswertung des Nutzungsverhaltens. Diese Einwilligung stellt im Sinne von Art. 6 Abs. 1 lit. a DSGVO unsere Rechtsgrundlage für die Verarbeitung Ihrer Angaben dar. Wir sind berechtigt, Dritte mit der technischen Abwicklung von Werbemassnahmen zu beauftragen und sind berechtigt, Ihre Daten zu diesem Zweck weiterzugeben. 
            <br>Sie können jederzeit Ihre Einwilligung zur Verarbeitung Ihrer Daten widerrufen (auf info@qrorpa.ch/). Nach erfolgtem Widerruf werden Ihre personenbezogenen Daten gelöscht. 
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>e) Dateneingabe zur Verifizierung von Zahlungen </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Bei Zahlungen müssen Gäste ihre Bestellung mit Eingabe ihrer Handy-Nummer verifizieren. QRorpa Kassensysteme GmbH speichert diese Daten während der maximalen Dauer von einer Woche. 
            <br>Möchte der Gast von speziellen Angeboten und Bonusprogrammen profitieren, kann er im Zuge der Verifikationsprozesses seine Handy-Nummer für SMS-Marketing freischalten. Ist dies der Fall, speichert QRorpa Kassensysteme GmbH die Handy-Nummer so lange, bis der Gast den Dienst kündigt und sämtliche Daten dieses Gasts umgehend gelöscht werden. 
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>f) Dateneingabe im Zuge der Massnahmen gegen Covid-19 </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Restaurantgäste in Gruppe ab vier Personen können sich über QRorpa im Kontaktformular für Covid-19 eintragen mit den folgenden Daten:
            <br>- Vor- und Nachname, 
            <br>- Adresse (Strasse, Hausnummer, Postleitzahl, Ort),
            <br>- Telefonnummer und
            <br>- E-Mail-Adresse. 
            <br>Diese Daten sind allein vom Betreiber des Restaurants und von seinem Personal einsehbar, werden allein für Covid-19-Rückverfolgungszwecke verwendet und im Übrigen nach einer Dauer von 30 Tagen unwiderruflich gelöscht. 
        </p>
        
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>g) Leistungen von Dritten </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Auf unserer Website werden Leistungen von Dritten erbracht (insbesondere PSP Saferpay – Worldline, Payrexx, Datatrans, Chat tawk.to, SMS-Gateway). Je nach Leistung werden in diesem Zusammenhang verschiedene Daten erhoben. Dabei handelt es sich beispielsweise um folgende Daten: 
            <br>- Firma (optional),
            <br>- Anrede,
            <br>- Vor- und Nachname,
            <br>- Adresse (Strasse, Hausnummer, Postleitzahl, Ort; optional),
            <br>- Telefonnummer (optional) und
            <br>- E-Mail-Adresse.
            <br>Wenn diese Informationen nicht zur Verfügung gestellt werden, kann dies den Bestellungsablauf behindern. Die Angabe anderer Informationen ist optional und hat keinen Einfluss auf Nutzung unserer Website.
            <br>Die von Ihnen eingegeben Daten werden dabei in der Regel direkt durch den betreffenden Anbieter erhoben oder bei bestimmten Angeboten durch uns an diese weitergeleitet. Für eine solche weitere Bearbeitung der Daten gelten in diesen Fällen die Datenschutzbestimmungen des jeweiligen Anbieters. Die Rechtsgrundlage für die Verarbeitung der vorangehend erwähnten Daten liegt in der Erfüllung eines Vertrages im Sinne von Art. 6 Abs. 1 lit. b DSGVO.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>4) Nutzung Ihrer Daten zu Werbezwecken</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>a) Im Allgemeinen</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">QRorpa Kassensysteme GmbH kann personenbezogene Daten der Nutzer von www.qrorpa.ch/ verwenden, um ihre Dienstleistungen zu bewerben (Online- und/oder Printwerbung etc.). Es ist QRorpa Kassensysteme GmbH erlaubt, zu diesem Zweck personenbezogene Daten an Dritte weiterzugeben. Der Nutzer erklärt sich ausdrücklich mit dieser Verwendung seiner Daten einverstanden. </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>b) Erstellen von pseudonomysierten Nutzungsprofilen </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Um Ihnen auf unserer Website personalisierte Dienstleistungen und Informationen zur Verfügung zu stellen (On-Site-Targeting), nutzen und analysieren wir die Daten, welche wir über Sie sammeln, wenn Sie die Website besuchen. Bei der entsprechenden Bearbeitung können gegebenenfalls auch sog. Cookies zum Einsatz kommen. Die Analyse Ihres Nutzerverhaltens kann zur Erstellung eines sogenannten Nutzungsprofiles führen. Eine Zusammenführung der Nutzungsdaten erfolgt lediglich mit pseudonymen, nie jedoch mit nicht-pseudonymisierten personenbezogenen Daten.
            <br>Um personalisiertes Marketing in sozialen Netzwerken zu ermöglichen, binden wir auf der Website so genannte Remarketing Pixel von Facebook und Twitter ein. Sofern Sie einen Account bei einem hierüber einbezogenen sozialen Netzwerk besitzen und zum Zeitpunkt des Seitenbesuchs dort angemeldet sind, verknüpft dieser Pixel den Seitenbesuch mit Ihrem Account. Loggen Sie sich vor Seitenbesuch aus ihrem jeweiligen Account aus, um eine Verknüpfung zu unterbinden. Weitere Einstellungen zur Werbung können Sie in den jeweiligen Sozialen Netzwerken in Ihrem Nutzerprofil vornehmen. Die Erstellung von pseudonymisierten Nutzerprofilen zu Werbe- und Analysezwecken stützen wir auf ein berechtigtes Interesse im Sinne von Art. 6 Abs. 1 lit. f DSGVO. Das berechtigte Interesse besteht im Direktmarketing und der Analyse der Nutzung unserer Website.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>c) Re-Targeting </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Wir setzen auf der Website Re-Targeting-Technologien ein. Dabei wird Ihr Nutzerverhalten auf unserer Website analysiert, um Ihnen anschliessend auch auf Partnerwebsites individuell auf Sie zugeschnittene Werbung anbieten zu können. Ihr Nutzerverhalten wird dabei pseudonym erfasst. Die meisten Re-Targeting Technologien arbeiten mit Cookies.
            <br>Diese Website benutzt Google AdWords Remarketing und Doubleclick by Google, Dienste der Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA („Google“), zur Schaltung von Anzeigen, die auf der Nutzung zuvor besuchter Websites basieren. Google verwendet hierzu das sogenannte DoubleClick-Cookie, das eine Wiedererkennung Ihres Browsers beim Besuch anderer Websites ermöglicht. Die durch das Cookie erzeugten Informationen über den Besuch dieser Websites (einschliesslich Ihrer IP-Adresse) werden an einen Server von Google in den USA übertragen und dort gespeichert (zu Transfers von Personendaten in die USA finden Sie weiter unten in Ziff. 12 zusätzliche Informationen).
            <br>Google wird diese Informationen benutzen, um Ihre Nutzung der Website im Hinblick auf die zu schaltenden Anzeigen auszuwerten, um Reports über die Websiteaktivitäten und Anzeigen für die Websitebetreiber zusammenzustellen und um weitere mit der Websitenutzung und der Internetnutzung verbundene Dienstleistungen zu erbringen. Auch wird Google diese Informationen gegebenenfalls an Dritte übertragen, sofern dies gesetzlich vorgeschrieben oder soweit Dritte diese Daten im Auftrag von Google verarbeiten. Google wird jedoch in keinem Fall Ihre IP-Adresse mit anderen Daten von Google in Verbindung bringen.
            <br>Zur Verwaltung der Dienste zur nutzungsbasierten Werbung verwenden wir ausserdem den Google Tag Manager. Das Tool Tag Manager selbst ist eine cookielose Domain und erfasst keine personenbezogenen Daten. Das Tool sorgt vielmehr für die Auslösung anderer Tags, die ihrerseits unter Umständen Daten erfassen. Wenn Sie auf Domain- oder Cookie-Ebene eine Deaktivierung vorgenommen haben, bleibt diese für alle Tracking-Tags bestehen, die mit dem Google Tag Manager implementiert sind
            <br>Sie können das Re-Targeting jederzeit verhindern, indem Sie in der Menuleiste Ihres Webbrowsers die betreffenden Cookies abweisen bzw. ausschalten. Zudem können Sie über die Website der Digital Advertising Alliance unter optout.aboutads.info ein Opt-Out für die genannten weiteren Werbe- und Re-Targeting-Tools beantragen. 
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>d) Nutzung von Google Adsense </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Diese Website benutzt Google AdSense, einen Dienst zum Einbinden von Werbeanzeigen der Google Inc. ("Google"). Google AdSense verwendet sog. "Cookies", Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website ermöglicht. Google AdSense verwendet auch so genannte Web Beacons (unsichtbare Grafiken). Durch diese Web Beacons können Informationen wie der Besucherverkehr auf diesen Seiten ausgewertet werden. 
            <br>Die durch Cookies und Web Beacons erzeugten Informationen über die Benutzung dieser Website (einschließlich Ihrer IP-Adresse) und Auslieferung von Werbeformaten werden an einen Server von Google in den USA übertragen und dort gespeichert. Diese Informationen können von Google an Vertragspartner von Google weitergegeben werden. Google wird Ihre IP-Adresse jedoch nicht mit anderen von Ihnen gespeicherten Daten zusammenführen. 
            <br>Sie können die Installation der Cookies durch eine entsprechende Einstellung Ihrer Browser Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website voll umfänglich nutzen können. Durch die Nutzung dieser Website erklären Sie sich mit der Bearbeitung der über Sie erhobenen Daten durch Google in der zuvor beschriebenen Art und Weise und zu dem zuvor benannten Zweck einverstanden.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>5) Einbindung von Diensten und Inhalten Dritter</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Es kann vorkommen, dass innerhalb unseres Onlineangebotes Inhalte oder Dienste von Dritt-Anbietern, wie zum Beispiel Stadtpläne oder Schriftarten von anderen Webseiten eingebunden werden. Die Einbindung von Inhalten der Dritt-Anbieter setzt immer voraus, dass die Dritt-Anbieter die IP-Adresse der Nutzer wahrnehmen, da sie ohne die IP-Adresse die Inhalte nicht an den Browser der Nutzer senden könnten. Die IP-Adresse ist damit für die Darstellung dieser Inhalte erforderlich. Des Weiteren können die Anbieter der Dritt-Inhalte eigene Cookies setzen und die Daten der Nutzer für eigene Zwecke verarbeiten. Dabei können aus den verarbeiteten Daten Nutzungsprofile der Nutzer erstellt werden. Wir werden diese Inhalte möglichst datensparsam und datenvermeidend einsetzen sowie im Hinblick auf die Datensicherheit zuverlässige Dritt-Anbieter wählen.
            Die nachfolgende Darstellung bietet eine Übersicht von Dritt-Anbietern sowie ihrer Inhalte, nebst Links zu deren Datenschutzerklärungen, welche weitere Hinweise zur Verarbeitung von Daten und, z.T. bereits hier genannt, Widerspruchsmöglichkeiten (sog. Opt-Out) enthalten:
            Externe Schriftarten von Google, Inc., https://www.google.com/fonts („Google Fonts“). Die Einbindung der Google Fonts erfolgt durch einen Serveraufruf bei Google (in der Regel in den USA). Datenschutzerklärung: https://www.google.com/policies/privacy/, Opt-Out: https://www.google.com/settings/ads/.
            Landkarten des Dienstes „Google Maps“ des Dritt-Anbieters Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA, gestellt. Datenschutzerklärung: https://www.google.com/policies/privacy/, Opt-Out: https://www.google.com/settings/ads/.
            Videos der Plattform „YouTube“ des Dritt-Anbieters Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA. Datenschutzerklärung: https://www.google.com/policies/privacy/, Opt-Out: https://www.google.com/settings/ads/.
            Diese WebSite verwendet den Dienst reCAPTCHA der Google Inc. (1600 Amphitheatre Parkway, Mountain View, CA 94043, USA; „Google“). Die Abfrage dient dem Zweck der Unterscheidung, ob die Eingabe durch einen Menschen oder durch automatisierte, maschinelle Verarbeitung erfolgt. Die Abfrage schliesst den Versand der IP-Adresse und ggf. weiterer von Google für den Dienst reCAPTCHA benötigter Daten an Google ein. Zu diesem Zweck wird Ihre Eingabe an Google übermittelt und dort weiterverwendet. Im Auftrag des Betreibers dieser Website wird Google diese Informationen benutzen, um Ihre Nutzung dieses Dienstes auszuwerten. Die im Rahmen von reCaptcha von Ihrem Browser übermittelte IP-Adresse wird nicht mit anderen Daten von Google zusammengeführt. Ihre Daten werden dabei gegebenenfalls auch in die USA übermittelt. Die Verarbeitung erfolgt auf Grundlage des Art. 6 (1) lit. a DSGVO mit Ihrer Einwilligung. Sie können Ihre Einwilligung jederzeit widerrufen, ohne dass die Rechtmässigkeit, der aufgrund der Einwilligung bis zum Widerruf erfolgten Verarbeitung berührt wird. Nähere Informationen zu Google reCAPTCHA sowie die dazugehörige Datenschutzerklärung finden Sie unter: https://www.google.com/privacy/ads/.
            Live-Chat-Software von tawk, tawk.to inc., 187 East Warm Springs Rd, SB298, Las Vegas, NV, 89119, USA. Datenschutzerklärung: https://www.tawk.to/data-protection/.
            Zahlungsdienstleistungen des Zahlungsdienstleisters Datatrans, Datatrans AG, Kreuzbühlstrasse 26, CH-8008 Zürich. Datenschutzerklärung: https://www.datatrans.ch/de/datenschutzbestimmungen/. Der Nutzer erklärt sich damit einverstanden, dass seine eingegebenen Daten zum Zwecke der Zahlungsverarbeitung an Datatrans weitergeleitet werden.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>6) Weitergabe der Daten an Dritte</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Wir geben Ihre personenbezogenen Daten nur weiter, wenn Sie ausdrücklich eingewilligt haben, hierfür eine gesetzliche Verpflichtung besteht oder dies zur Durchsetzung unserer Rechte, insbesondere zur Durchsetzung von Ansprüchen aus dem Verhältnis zwischen Ihnen und uns, erforderlich ist. 
            <br>Darüber hinaus geben wir Ihre Daten an Dritte weiter, soweit dies im Rahmen der Nutzung der Website für die Bereitstellung der von Ihnen gewünschten Dienstleistungen sowie der Analyse Ihres Nutzerverhaltens erforderlich ist. Soweit dies für die in Satz 1 genannten Zwecke erforderlich ist, kann die Weitergabe auch ins Ausland erfolgen. Sofern die Website Links zu Websites Dritter enthält, haben wir nach dem Anklicken dieser Links keinen Einfluss mehr auf die Erhebung, Verarbeitung, Speicherung oder Nutzung personenbezogener Daten durch den Dritten und übernimmt dafür keine Verantwortung.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>7) Übermittlung personenbezogener Daten ins Ausland </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Wir sind berechtigt, Ihre persönlichen Daten auch an dritte Unternehmen (beauftragte Dienstleister) im Ausland zu übertragen, sofern dies für die in dieser Datenschutzerklärung beschriebenen Datenbearbeitungen erforderlich ist. Diese sind im gleichen Umfang wie wir selbst zum Datenschutz verpflichtet. Wenn das Datenschutzniveau in einem Land nicht dem schweizerischen bzw. dem europäischen entspricht, stellen wir vertraglich sicher, dass der Schutz Ihrer personenbezogenen Daten demjenigen in der Schweiz bzw. in der EU jederzeit entspricht.</p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>8) Datensicherheit</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Wir bedienen uns geeigneter technischer und organisatorischer Sicherheitsmassnahmen, um Ihre bei uns gespeicherten persönlichen Daten gegen Manipulation, teilweisen oder vollständigen Verlust und gegen unbefugten Zugriff Dritter zu schützen. Unsere Sicherheitsmassnahmen werden entsprechend der technologischen Entwicklung fortlaufend verbessert. 
            <br>Sie sollten Ihre Zahlungsinformationen stets vertraulich behandeln und das Browserfenster schliessen, wenn Sie die Kommunikation mit uns beendet haben, insbesondere, wenn Sie den Computer gemeinsam mit anderen nutzen.
            <br>Auch den unternehmensinternen Datenschutz nehmen wir sehr ernst. Unsere Mitarbeitenden und die von uns beauftragten Dienstleistungsunternehmen sind von uns zur Verschwiegenheit und zur Einhaltung der datenschutzrechtlichen Bestimmungen verpflichtet worden.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>9) Cookies</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Cookies helfen unter vielen Aspekten, Ihren Besuch auf unserer Website einfacher, angenehmer und sinnvoller zu gestalten. Cookies sind Informationsdateien, die Ihr Webbrowser automatisch auf der Festplatte Ihres Computers speichert, wenn Sie unsere Website besuchen. Cookies beschädigen weder die Festplatte Ihres Rechners noch werden von diesen Cookies persönliche Daten der Anwender an uns übermittelt.
            <br>Wir setzen Cookies beispielsweise ein, um die Ihnen angezeigten Informationen, Angebote und Werbung besser auf Ihre individuellen Interessen auszurichten. Die Verwendung führt nicht dazu, dass wir neue personenbezogene Daten über Sie als Onlinebesucher erhalten. Die meisten Internet-Browser akzeptieren Cookies automatisch. Sie können Ihren Browser jedoch so konfigurieren, dass keine Cookies auf Ihrem Computer gespeichert werden oder stets ein Hinweis erscheint, wenn Sie ein neues Cookie erhalten.
            <br>Die Deaktivierung von Cookies kann dazu führen, dass Sie nicht alle Funktionen unserer Website nutzen können. 
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>10) Tracking-Tools</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Auf unserer Website verwenden wir sogenannte Tracking-Tools. Mit diesen Tracking-Tools wird Ihr Surfverhalten auf unserer Website beobachtet. Diese Beobachtung erfolgt zum Zwecke der bedarfsgerechten Gestaltung und fortlaufenden Optimierung unserer Website. In diesem Zusammenhang werden pseudonymisierte Nutzungsprofile erstellt und kleine Textdateien, die auf Ihrem Computer gespeichert sind („Cookies“), verwendet.</p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>a) Google Analytics</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Diese Website benutzt Google Analytics, einen Webanalysedienst der Google Inc. („Google“). Google Analytics verwendet sog. „Cookies“, Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Webseite durch Sie ermöglichen. Die durch das Cookie erzeugten Informationen über Ihre Benutzung der Webseite werden in der Regel an einen Server von Google in den USA übertragen und dort gespeichert. Die im Rahmen von Google Analytics von Ihrem Browser übermittelte IP-Adresse wird nicht mit anderen Daten von Google zusammengeführt. Wir haben zudem auf dieser Webseite Google Analytics um den Code „anonymizeIP“ erweitert. Dies garantiert die Maskierung Ihrer IP-Adresse, sodass alle Daten anonym erhoben werden. Nur in Ausnahmefällen wird die volle IP-Adresse an einen Server von Google in den USA übertragen und dort gekürzt.
            <br>Im Auftrag des Betreibers dieser Website wird Google diese Information benutzen, um Ihre Nutzung der Webseite auszuwerten, um Reports über die Webseiten-Aktivitäten zusammenzustellen und um weitere mit der Webseiten-Nutzung und der Internetnutzung verbundene Dienstleistungen gegenüber dem Webseitenbetreiber zu erbringen. Sie können die Speicherung der Cookies durch eine entsprechende Einstellung Ihrer Browser-Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall möglicherweise nicht sämtliche Funktionen dieser Webseite vollumfänglich werden nutzen können.
            <br>Sie können darüber hinaus die Erfassung der durch das Cookie erzeugten und auf Ihre Nutzung der Webseite bezogenen Daten (inkl. Ihrer IP-Adresse) an Google sowie die Verarbeitung dieser Daten durch Google verhindern, indem sie das unter dem folgenden Link verfügbare Browser-Plugin herunterladen und installieren: http://tools.google.com/dlpage/gaoptout?hl=de.  Es wird ein Opt-Out-Cookie gesetzt, das die zukünftige Erfassung Ihrer Daten beim Besuch dieser Website verhindert. Der Opt-Out-Cookie gilt nur in diesem Browser und nur für unsere Website und wird auf Ihrem Gerät abgelegt. Löschen Sie die Cookies in diesem Browser, müssen Sie das Opt-Out-Cookie erneut setzen. [Anm. Hinweise zur Einbindung des Opt-Out-Cookie finden Sie unter: https://developers.google.com/analytics/devguides/collection/gajs/?hl=de#disable. Wir nutzen Google Analytics weiterhin dazu, Daten aus Double-Click-Cookies und auch AdWords zu statistischen Zwecken auszuwerten. Sollten Sie dies nicht wünschen, können Sie dies über den Anzeigenvorgaben-Manager (http://www.google.com/settings/ads/onweb/?hl=de) deaktivieren
            <br>Wir verwenden Google Analytics einschliesslich der Funktionen von Universal Analytics. Universal Analytics erlaubt es uns, die Aktivitäten auf unseren Seiten geräteübergreifend zu analysieren (z.B. bei Zugriffen mittels Laptops und später über ein Tablet). Dies wird durch die pseudonyme Zuweisung einer User-ID zu einem Nutzer ermöglicht. Eine solche Zuweisung erfolgt etwa, wenn Sie sich für ein Kundenkonto registrieren bzw. sich bei Ihrem Kundenkonto anmelden. Es werden jedoch keine personenbezogenen Daten an Google weitergeleitet. Auch wenn mit Universal Analytics zusätzliche Funktionen zu Google Analytics hinzukommen, bedeutet dies nicht, dass damit eine Einschränkung von Massnahmen zum Datenschutz wie IP-Masking oder das Browser-Add-on verbunden ist.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>b) Google AdWords Conversion-Tracking</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Wir nutzen das Online-Werbeprogramm „Google AdWords“ und im Rahmen von Google AdWords das Conversion-Tracking. Das Google Conversion Tracking ist ein Analysedienst der Google Inc. (1600 Amphitheatre Parkway, Mountain View, CA 94043, USA; „Google“). Wenn Sie auf eine von Google geschaltete Anzeige klicken, wird ein Cookie für das Conversion-Tracking auf Ihrem Rechner abgelegt. Diese Cookies verlieren nach 30 Tagen ihre Gültigkeit, enthalten keine personenbezogenen Daten und dienen somit nicht der persönlichen Identifizierung. Wenn Sie bestimmte Internetseiten unserer Website besuchen und das Cookie noch nicht abgelaufen ist, können Google und wir erkennen, dass Sie auf die Anzeige geklickt haben und zu dieser Seite weitergeleitet wurden. Jeder Google AdWords-Kunde erhält ein anderes Cookie. Somit besteht keine Möglichkeit, dass Cookies über die Websites von AdWords-Kunden nachverfolgt werden können. 
            <br>Die Informationen, die mithilfe des Conversion-Cookies eingeholt werden, dienen dazu, Conversion-Statistiken für AdWords-Kunden zu erstellen, die sich für Conversion-Tracking entschieden haben. Hierbei erfahren die Kunden die Gesamtanzahl der Nutzer, die auf ihre Anzeige geklickt haben und zu einer mit einem Conversion-Tracking-Tag versehenen Seite weitergeleitet wurden. Sie erhalten jedoch keine Informationen, mit denen sich Nutzer persönlich identifizieren lassen. 
            <br>Wenn Sie nicht am Tracking teilnehmen möchten, können Sie dieser Nutzung widersprechen, indem Sie die Installation der Cookies durch eine entsprechende Einstellung Ihrer Browser Software verhindern (Deaktivierungsmöglichkeit). Sie werden sodann nicht in die Conversion-Tracking Statistiken aufgenommen. Weiterführende Informationen sowie die Datenschutzerklärung von Google finden Sie unter: http://www.google.com/policies/technologies/ads/ und http://www.google.de/policies/privacy/.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>c) AppNexus</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Auf unserer Website verwenden wir Tracking-Technologie der AppNexus Inc., 28 W 23rd Street, 4th floor, New York, NY 10010, USA. Damit werden Informationen über das Surfverhalten der Websitenbesucher zu Marketingzwecken in rein anonymisierter Form gesammelt und hierfür Cookies gesetzt. Hierbei werden keine personenbezogenen Daten erhoben oder gespeichert.
            <br>Sie können jederzeit der Erhebung, Verarbeitung und Erfassung der durch AppNexus erzeugten Daten widersprechen, indem Sie unter https://www.appnexus.com/en/company/platform-privacy-policy-de#choicesein Opt-Out-bzw. Deaktivierungs-Cookie setzen.
            <br>Weitere Informationen zum Datenschutz bei AppNexus finden Sie unter https://www.appnexus.com/en/company/platform-privacy-policy.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>d) Twitter Analytics </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Auf unserer Website verwenden wir zu statistischen Zwecken die Besucheraktions-Pixel der Twitter Inc., 795 Folsom St., Suite 600, San Francisco, CA 94107, USA (“Twitter”). Das Pixel ermöglicht uns, das Verhalten von Nutzern nachzuverfolgen, nachdem diese durch den Klick auf eine Twitter-Werbeanzeige auf unsere Website weitergeleitet wurden. Dieses Verfahren dient dazu, die Wirksamkeit von Twitter-Werbeanzeigen für statistische und Marktforschungszwecke auszuwerten und kann so dazu beitragen, zukünftige Werbemassnahmen zu optimieren. 
            <br>Die dabei erhobenen Daten ermöglichen uns keine Rückschlüsse auf die Identität der Nutzer. Allerdings werden die Daten von Twitter gespeichert und verarbeitet, sodass eine Verbindung zum Profil des jeweiligen Nutzers möglich ist und Twitter die Daten für eigene Werbezwecke verwenden kann. Diese Daten können Twitter sowie dessen Partnern das Schalten von Werbeanzeigen auf und ausserhalb von Twitter ermöglichen. Es kann ferner zu diesen Zwecken ein Cookie auf den Rechnern von Nutzern gespeichert und ausgelesen werden.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>11) Social Media Plug-Ins </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Auf der Website kommen die nachfolgend beschriebenen Social Plugins zum Einsatz. Die Plugins sind auf unserer Website standardmässig deaktiviert und senden daher keine Daten. Durch einen Klick auf den entsprechenden Social Media-Button können Sie die Plugins aktivieren.
            <br>Wenn diese Plugins aktiviert sind, baut Ihr Browser mit den Servern des jeweiligen sozialen Netzwerks eine direkte Verbindung auf, sobald Sie eine unserer Websiten aufrufen. Der Inhalt des Plugins wird vom sozialen Netzwerk direkt an Ihren Browser übermittelt und von diesem in die Website eingebunden. Die Plugins lassen sich mit einem Klick wieder deaktivieren.
            <br>Weitere Informationen finden Sie in den jeweiligen Datenschutzerklärungen von Facebook, Twitter und Google.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>a) Plugins von Facebook</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Auf dieser Website kommen Social Plugins von Facebook zum Einsatz, um unseren Webauftritt persönlicher zu machen. Hierfür nutzen wir den „LIKE“ oder "TEILEN"-Button. Es handelt sich dabei um ein Angebot des US-amerikanischen Unternehmens Facebook Inc., 1601 S. California Ave, Palo Alto, CA 94304, USA. 
            <br>Durch die Einbindung der Plugins erhält Facebook die Information, dass Ihr Browser die entsprechende Seite unseres Webauftritts aufgerufen hat, auch wenn Sie kein Facebook-Konto besitzen oder gerade nicht bei Facebook eingeloggt sind. Diese Information (einschliesslich Ihrer IP-Adresse) wird von Ihrem Browser direkt an einen Server von Facebook in den USA übermittelt und dort gespeichert.
            <br>Sind Sie bei Facebook eingeloggt, kann Facebook den Besuch unserer Website Ihrem Facebook-Konto direkt zuordnen. Wenn Sie mit den Plugins interagieren, zum Beispiel den „LIKE“ oder "TEILEN"-Button betätigen, wird die entsprechende Information ebenfalls direkt an einen Server von Facebook übermittelt und dort gespeichert. Die Informationen werden zudem auf Facebook veröffentlicht und Ihren Facebook-Freunden angezeigt.
            <br>Facebook kann diese Informationen zum Zwecke der Werbung, Marktforschung und bedarfsgerechten Gestaltung der Facebook-Seiten benutzen. Hierzu werden von Facebook Nutzungs-, Interessen- und Beziehungsprofile erstellt, z.B. um Ihre Nutzung unserer Website im Hinblick auf die Ihnen bei Facebook eingeblendeten Werbeanzeigen auszuwerten, andere Facebook-Nutzer über Ihre Aktivitäten auf unserer Website zu informieren und um weitere mit der Nutzung von Facebook verbundene Dienstleistungen zu erbringen.
            <br>Wenn Sie nicht möchten, dass Facebook die über unseren Webauftritt gesammelten Daten Ihrem Facebook-Konto zuordnet, müssen Sie sich vor Ihrem Besuch unserer Website bei Facebook ausloggen.
            <br>Zweck und Umfang der Datenerhebung und die weitere Verarbeitung und Nutzung der Daten durch Facebook sowie Ihre diesbezüglichen Rechte und Einstellungsmöglichkeiten zum Schutz Ihrer Privatsphäre entnehmen Sie bitte den Datenschutzhinweisen von Facebook.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>b) Plugins von Twitter </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Auf unserer Website sind Plugins des Kurznachrichtennetzwerks Twitter Inc., 795 Folsom St., Suite 600, San Francisco, CA 94107, USA integriert. Die Twitter-Plugins (tweet-Button) erkennen Sie an dem Twitter-Logo auf unserer Seite. Sofern Sie Social Plugins aktiviert haben, wird eine direkte Verbindung zwischen Ihrem Browser und dem Twitter-Server hergestellt. Twitter erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den Twitter „tweet-Button“ anklicken, während Sie in Ihrem Twitter-Account eingeloggt sind, können Sie die Inhalte unserer Seiten auf Ihrem Twitter-Profil verlinken. Dadurch kann Twitter den Besuch unserer Seiten Ihrem Benutzerkonto zuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Twitter erhalten. Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von Twitter.
            <br>Wenn Sie nicht wünschen, dass Twitter den Besuch unserer Seiten zuordnen kann, loggen Sie sich bitte aus Ihrem Twitter-Benutzerkonto aus.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>c) Plugins von Google Plus </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Unsere Website verwendet die “+1″-Schaltfläche des sozialen Netzwerkes Google Plus, welches von der Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA betrieben wird. Der Button ist an dem Zeichen “+1″ auf weissem oder farbigem Hintergrund erkennbar.
            <br>Wenn Sie eine Seite unseres Webauftritts aufrufen, die eine solche Schaltfläche enthält, baut Ihr Browser, sofern Sie Social Plugins aktiviert haben, eine direkte Verbindung mit den Servern von Google auf. Der Inhalt der “+1″-Schaltfläche wird von Google direkt an Ihren Browser übermittelt und von diesem in die Website eingebunden. Wir haben daher keinen Einfluss auf den Umfang der Daten, die Google mit der Schaltfläche erhebt. Laut Google werden ohne einen Klick auf die Schaltfläche keine personenbezogenen Daten erhoben. Nur bei eingeloggten Mitgliedern werden solche Daten, unter anderem die IP-Adresse, erhoben und verarbeitet.
            <br>Zweck und Umfang der Datenerhebung und die weitere Verarbeitung und Nutzung der Daten durch Google sowie Ihre diesbezüglichen Rechte und Einstellungsmöglichkeiten zum Schutz Ihrer Privatsphäre entnehmen Sie bitte den Google-Datenschutzhinweisen.
            <br>Wenn Sie Google Plus-Mitglied sind und nicht möchten, dass Google über unseren Webauftritt Daten über Sie sammelt und mit Ihren bei Google gespeicherten Mitgliedsdaten verknüpft, müssen Sie sich vor Ihrem Besuch unserer Website bei Google Plus ausloggen.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>d) Plugins von LinkedIn</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Unsere Website verwendet Plugins des sozialen Netzwerks LinkedIn respektive der LinkedIn Corporation, 2029 Stierlin Court, Mountain View, CA 94043, USA (im Folgenden „LinkedIn“). Die Plugins von LinkedIn können Sie am entsprechenden Logo oder dem „Recommend-Button“ („Empfehlen“) erkennen. Bitte beachten Sie, dass das Plugin beim Besuch unserer Internetseite eine Verbindung zwischen Ihrem jeweiligen Internetbrowser und dem Server von LinkedIn aufbaut. LinkedIn wird somit darüber informiert, dass unsere Internetseite mit Ihrer IP-Adresse besucht wurde. Wenn Sie den „Recommend-Button“ von LinkedIn anklicken und dabei zugleich in Ihrem Account bei LinkedIn eingeloggt sind, haben Sie die Möglichkeit, einen Inhalt von unserer Internetseite auf Ihrer Profilseite bei LinkedIn-Profil zu verlinken. Dabei ermöglichen Sie es LinkedIn, Ihren Besuch auf unserer Internetseite Ihnen beziehungsweise Ihrem Benutzerkonto zuordnen zu können. Sie müssen wissen, dass wir keinerlei Kenntnis vom Inhalt der übermittelten Daten und deren Nutzung durch LinkedIn erlangen. Weitere Einzelheiten zur Erhebung der Daten und zu Ihren rechtlichen Möglichkeiten sowie Einstellungsoptionen erfahren Sie bei LinkedIn. Diese werden Ihnen unter http://www.linkedin.com/static?key=privacy_policy&trk=hb_ft_priv zur Verfügung gestellt.</p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>e) Plugins von XING</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Unsere Website verwendet Plugins von XING. Beim Aufruf dieser Internetseite wird über Ihren Browser kurzfristig eine Verbindung zu Servern der XING SE („XING“) aufgebaut, mit denen die „XING Share-Button“-Funktionen (insbesondere die Berechnung/Anzeige des Zählerwerts) erbracht werden. XING speichert keine personenbezogenen Daten von Ihnen über den Aufruf dieser Internetseite. XING speichert insbesondere keine IP-Adressen. Es findet auch keine Auswertung Ihres Nutzungsverhaltens über die Verwendung von Cookies im Zusammenhang mit dem „XING Share-Button“ statt. Die jeweils aktuellen Datenschutzinformationen zum „XING Share-Button“ und ergänzende Informationen können Sie auf dieser Internetseite abrufen: https://www.xing.com/app/share?op=data_protection.</p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>f) Plugins von YouTube</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Unsere Website verwendet Plugins von YouTube, gehörig zur Google Inc., ansässig in San Bruno/Kalifornien, USA. Sobald Sie mit einem YouTube-Plugin ausgestattete Seiten unserer Internetpräsenz besuchen, wird eine Verbindung zu den Servern von YouTube aufgebaut. Dabei wird dem Youtube-Server mitgeteilt, welche spezielle Seite unserer Internetpräsenz von Ihnen besucht wurde. Sollten Sie obendrein in Ihrem YouTube-Account eingeloggt sein, würden Sie es YouTube ermöglichen, Ihr Surfverhalten direkt Ihrem persönlichen Profil zuzuordnen. Sie können diese Möglichkeit der Zuordnung vermeiden, wenn Sie sich vorher aus Ihrem Account ausloggen. Weitere Informationen zur Erhebung und Nutzung Ihrer Daten durch YouTube erhalten Sie in den dortigen Hinweisen zum Datenschutz unter www.youtube.com.</p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>g) Plugins von WhatsApp</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Durch einen Klick auf die WhatsApp-Social-Media-Schaltflächen auf unserer Internetseite werden Plugins des Unternehmens WhatsApp Inc., 650 Castro Street, Suite 120-219 Mountain View, Kalifornien, 94041, Vereinigte Staaten von Amerika (nachfolgend: WhatsApp) nachgeladen oder ein in Ihrem Browser installiertes WhatsApp-Plugin aufgerufen. Wenn Sie in Ihrem Browser Java-Script aktiviert und keinen Java-Script-Blocker installiert haben, wird Ihr Browser ggf. personenbezogene Daten an WhatsApp übermitteln. Uns ist nicht bekannt, welche Daten WhatsApp mit den erhaltenen Daten verknüpft und zu welchen Zwecken WhatsApp diese Daten verwendet. WhatsApp gehört zu der Firma Facebook Inc., 1601 Willow Road, Menlo Park, CA 94025, USA (nachfogend: Facebook), so dass nicht auszuschließen ist, dass Ihre personenbezogenen Daten auch an Facebook bzw. dessen Tochterfirma Facebook Ireland Ltd., Hanover Reach, 5-7 Hanover Quay, Dublin 2, Ireland weiter übertragen werden. Um die Ausführung von Java-Script Code von WhatsApp insgesamt zu verhindern, können Sie einen Java-Script-Blocker installieren (z.B. www.www.noscript.net oder www.ghostery.com). Hinweise zum Datenschutz seitens des Anbieters des WhatsApp stehen unter folgendem Link zur Verfügung: http://www.whatsapp.com/legal/.</p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>h) Plugins von Instragram</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Auf unseren Seiten sind Funktionen des Dienstes Instagram eingebunden. Diese Funktionen werden angeboten durch die Instagram Inc., 1601 Willow Road, Menlo Park, CA, 94025, USA integriert. Wenn Sie in Ihrem Instagram-Account eingeloggt sind, können Sie durch Anklicken des Instagram-Buttons die Inhalte unserer Seiten mit Ihrem Instagram-Profil verlinken. Dadurch kann Instagram den Besuch unserer Seiten Ihrem Benutzerkonto zuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Instagram erhalten.
            <br>Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von Instagram: http://instagram.com/about/legal/privacy/
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>i) Plugins von Pinterest</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Auf unserer Seite verwenden wir Social Plugins des sozialen Netzwerkes Pinterest, das von der Pinterest Inc., 808 Brannan Street San Francisco, CA 94103-490, USA („Pinterest“) betrieben wird. Wenn Sie eine Seite aufrufen, die ein solches Plugin enthält, stellt Ihr Browser eine direkte Verbindung zu den Servern von Pinterest her. Das Plugin übermittelt dabei Protokolldaten an den Server von Pinterest in die USA. Diese Protokolldaten enthalten möglicherweise Ihre IP-Adresse, die Adresse der besuchten Websites, die ebenfalls Pinterest-Funktionen enthalten, Art und Einstellungen des Browsers, Datum und Zeitpunkt der Anfrage, Ihre Verwendungsweise von Pinterest sowie Cookies.
            <br>Weitere Informationen zu Zweck, Umfang und weiterer Verarbeitung und Nutzung der Daten durch Pinterest sowie Ihre diesbezüglichen Rechte und Möglichkeiten zum Schutz Ihrer Privatsphäre finden Sie in den Datenschutzhinweisen von Pinterest: https://about.pinterest.com/de/privacy-policy.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>12) Auswertung der Newsletter-Nutzung</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Für den Versand unseres Newsletters nutzen wir E-Mail-Marketing-Dienstleistungen von Dritten. Unser Newsletter kann deshalb einen sog. Web Beacon (Zählpixel) oder ähnliche technische Mittel enthalten. Bei einem Web-Beacons handelt es sich um eine 1x1 Pixel grosse, nicht sichtbare Grafik, die mit der Benutzer-ID des jeweiligen Newsletter-Abonnenten im Zusammenhang steht. 
            <br>Der Rückgriff auf entsprechende Dienstleistungen ermöglicht die Auswertung, ob die E-Mails mit unserem Newsletter geöffnet wurden. Darüber hinaus kann damit auch das Klickverhalten der Newsletter-Empfänger erfasst und ausgewertet werden. Wir nutzen diese Daten zu statistischen Zwecken und zur Optimierung des Newsletters in Bezug auf Inhalt und Struktur. Dies ermöglicht uns, die Informationen und Angebote in unserem Newsletter besser auf die individuellen Interessen des jeweiligen Empfängers auszurichten. Der Zählpixel wird gelöscht, wenn Sie den Newsletter löschen.
            <br>Um Tracking-Pixel in unserem Newsletter zu unterbinden, stellen Sie bitte Ihr Mailprogramm so ein, dass in Nachrichten kein HTML angezeigt wird.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>13) Hinweis zu Datenübermittlungen in die USA</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Aus Gründen der Vollständigkeit weisen wir für Nutzer mit Wohnsitz oder Sitz in der Schweiz darauf hin, dass in den USA Überwachungsmassnahmen von US-Behörden bestehen, die generell die Speicherung aller personenbezogenen Daten sämtlicher Personen, deren Daten aus der Schweiz in die USA übermittelt wurden, ermöglicht. Dies geschieht ohne Differenzierung, Einschränkung oder Ausnahme anhand des verfolgten Ziels und ohne ein objektives Kriterium, das es ermöglicht, den Zugang der US-Behörden zu den Daten und deren spätere Nutzung auf ganz bestimmte, strikt begrenzte Zwecke zu beschränken, die den sowohl mit dem Zugang zu diesen Daten als auch mit deren Nutzung verbundenen Eingriff zu rechtfertigen vermögen. Ausserdem weisen wir darauf hin, dass in den USA für die betroffenen Personen aus der Schweiz keine Rechtsbehelfe vorliegen, die es ihnen erlauben, Zugang zu den sie betreffenden Daten zu erhalten und deren Berichtigung oder Löschung zu erwirken, bzw. kein wirksamer gerichtlicher Rechtsschutz gegen generelle Zugriffsrechte von US-Behörden vorliegt. Wir weisen den Betroffenen explizit auf diese Rechts- und Sachlage hin, um eine entsprechend informierte Entscheidung zur Einwilligung in die Verwendung seiner Daten zu treffen.
            Nutzer mit Wohnsitz in einem Mitgliedstaat der EU weisen wir darauf hin, dass die USA aus Sicht der Europäischen Union nicht über ein ausreichendes Datenschutzniveau verfügt. 
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>14) Recht auf Auskunft, Berichtigung, Löschung und Einschränkung der Verarbeitung; Recht auf Datenübertragbarkeit</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Sie haben das Recht, über die personenbezogenen Daten, die von uns über Sie gespeichert werden, auf Antrag unentgeltlich Auskunft zu erhalten. Zusätzlich haben Sie das Recht auf Berichtigung unrichtiger Daten und das Recht auf Löschung Ihrer personenbezogenen Daten, soweit dem keine gesetzliche Aufbewahrungspflicht oder ein Erlaubnistatbestand, der uns die Verarbeitung der Daten gestattet, entgegensteht. Sie haben gemäss den Artikeln 18 und 21 DSGVO zudem das Recht, eine Einschränkung der Datenverarbeitung zu verlangen sowie der Datenverarbeitung zu widersprechen.
            <br>Sie haben auch das Recht, von uns die Herausgabe der Daten zu verlangen, die Sie uns übermittelt haben. Auf Anfrage geben wir die Daten auch an einen Dritten Ihrer Wahl weiter. Sie haben das Recht, die Daten in einem gängigen Dateiformat zu erhalten.
            <br>Sie können uns für die vorgenannten Zwecke über die E-Mail-Adresse info@qrorpa.ch/ erreichen. Für die Bearbeitung Ihrer Gesuche können wir, nach eigenem Ermessen, einen Identitätsnachweis verlangen.
            <br>Sie können uns auch mitteilen, was mit Ihren Daten nach Ihrem Tod geschehen soll, indem Sie uns entsprechende Anweisungen geben.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>15) Aufbewahrung von Daten </strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Wir speichern personenbezogene Daten nur so lange, wie es erforderlich ist, um die oben genannten Tracking-, Werbe- und Analysedienste im Rahmen unseres berechtigten Interesses zu verwenden, um in dem oben genannten Umfang Dienstleistungen auszuführen, die Sie gewünscht oder zu denen Sie Ihre Einwilligung erteilt haben sowie um unsere gesetzlichen Verpflichtungen nachzukommen.
            <br>Vertragsdaten werden von uns länger aufbewahrt, da dies durch gesetzliche Aufbewahrungspflichten vorgeschrieben ist. Aufbewahrungspflichten, die uns zur Aufbewahrung von Daten verpflichten, ergeben sich aus Vorschriften der Rechnungslegung und aus steuerrechtlichen Vorschriften. Gemäss diesen Vorschriften sind geschäftliche Kommunikation, geschlossene Verträge und Buchungsbelege bis zu 10 Jahren bzw. in Bezug auf Nutzer mit Wohnsitz in Frankreich bis zu 5 Jahren aufzubewahren. Soweit wir diese Daten nicht mehr zur Durchführung der Dienstleistungen für Sie benötigen, werden die Daten gesperrt. Dies bedeutet, dass die Daten dann nur noch für Zwecke der Rechnungslegung und für Steuerzwecke verwendet werden dürfen.
        </p>

        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:12px 0 12px 0;"><strong>16) Recht auf Beschwerde bei einer Datenschutzaufsichtsbehörde</strong></p>
        <p style="color:rgb(72,81,87); line-height:19px; margin:0px; padding:2px;">Falls Sie Wohnsitz in einem EU-Staat haben, steht Ihnen das Recht zu, sich jederzeit bei einer Datenschutzaufsichtsbehörde zu beschweren.</p>


        <p style="color:rgb(72,81,87); line-height:19px; margin:1cm 0 0 0; padding:2px;">Chur, 17. Oktober 2023</p>



        
    </div>
  </main>

  </body>
 
</html> 