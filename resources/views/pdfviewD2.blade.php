<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF</title>
    
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding-top: 15px;
        padding-left: 0.5cm;
        padding-right: 0.5cm;
        padding-bottom: 0;
        line-height: 24px;
        font-family: Arial, Helvetica, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {

        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: center;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 5px;
    }
    
   
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 5px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    .information{
        margin-top: 1cm;
    }
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Arial, Helvetica, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    .qr-code-text{
        margin-top: 0px;
    }
    .qr-code-text li{
        font-size: 11px;
        list-style: none;
        line-height: 1.5;
    }
    .footer-text{
        font-size: 11px;
        text-align: center;
    }
    </style>
</head>
<?php
    use App\resdemoalfa;

    $countLine= 1;
?>
<body>
    <div class="invoice-box" style="margin-top:-14px;">
    @foreach(explode('|-|', $items) as $OneItem)

<?php
    $item = resdemoalfa::find($OneItem);
?>
        <table cellpadding="0" cellspacing="0">
       
            <tr class="d-flex justify-content-between mt-1">
                <td class="text-left" style="width:40%;">
                    <img width="190px" src="{{ public_path() . '/storage/images/kreativeIdeeLogo.png' }}" id="logo" />
                </td>
                <td style="width:24%; padding-top:-20px; text-align:left; padding-left:30px;">
                    <p style="font-size:11px; width:100%" >QRorpa Systeme</p>
                    <p style=" font-size:11px; margin-top:-30px; width:100%">Kreative Idee bei Haziri</p>
                    <p style=" font-size:11px; margin-top:-30px; width:100%">Giacomettistrasse 27, Chur</p>
                    <p style=" font-size:11px; margin-top:-30px; width:100%">+41 76 580 65 43</p>
                    <p style=" font-size:11px; margin-top:-30px; width:100%">info@kreativeidee.ch</p>
                    <p style=" font-size:11px; margin-top:-30px; margin-bottom:-20px; width:100%">info@qrorpa.ch/</p>
                </td>

                <td class="text-right" style="width:36%; text-align:right;">
                   <img style="width:130px; margin-top:5px; margin-left:60px;" src="{{ public_path() . '/storage/images/qrorpa-logo-edited.jpg' }}" id="logo" />
                </td>
            </tr>
      
            
            <tr class="information">
                <td style="padding-left:8mm;">
                    <table style="margin-top:1.4cm;">
                        <tr>
                            <td colspan="2">
                                <p style="font-size:8px; margin-bottom:-10px; margin-top:-18px;">Kreative Idee bei Haziri | Giacomettistrasse 27, 7000 Chur</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 60%;">
                                <h4 style="border:1px solid #000; margin-top:5px;  padding: 2px; font-size: 13pt;">P.P.<span style="display: inline; font-size: 10pt; font-weight: normal; "> CH-7000 Chur</span></h4>
                            </td>
                            <td style="text-align: left; margin-top:15px;">
                                <p style=" display: inline; font-size: 11pt; ">Post CH AG <span style="font-size: 14px;font-weight: bold;display: block;    line-height: 0.7;">B-ECONOMY</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 11pt; line-height: 1.5;">
                               {{$item->emri}}<br>
                                {{$item->adresa}}<br>
                                {{$item->plz}} {{$item->ort}}
                                
                            </td>
                            <!-- <td style="text-align: left;"> -->
                                <!-- <span style="display: inline; font-size: 11pt;">   -->
                                <!-- <img width="50px;" src="{{ public_path() . '/storage/qrcode/qrcode.jpeg' }}"> -->
                            <!-- </span> -->
                            <!-- </td> -->
                        </tr>  
                    </table>
                </td>
                <td colspan='3' style="text-align:right;"><br>
                    <soan style="font-size: 11px;">qrorpa.ch/admin-benutzung</soan><br>
                    <img style="width:90px; " src="{{ public_path() . '/storage/images/qr-code-admin-benutzung-2.png' }}" />
                    <ul class="qr-code-text">
                        <li><span style="font-family: DejaVu Sans, sans-serif;">✓</span> Online bestellen & bezahlen</li>
                        <li style="margin-top:-5px;"><span style="font-family: DejaVu Sans, sans-serif;">✓</span> Serviceteam rufen</li>
                        <li style="margin-top:-5px;"><span style="font-family: DejaVu Sans, sans-serif;">✓</span> Tisch reservieren</li>
                        <li style="margin-top:-5px;"><span style="font-family: DejaVu Sans, sans-serif;">✓</span> Takeaway</li>
                        <li style="margin-top:-5px;"><span style="font-family: DejaVu Sans, sans-serif;">✓</span> Lieferung</li>
                        <li style="margin-top:-5px;"><span style="font-family: DejaVu Sans, sans-serif;">✓</span> Covid-19 Kontaktformular</li>
                    </ul>

                </td>
            </tr>
             <tr>
                <td colspan="3">
                    <p style="margin-top:10px; font-size: 11pt;margin-bottom:40px;">Chur, {{date('d.m.Y')}}</p>
                </td>
               
            </tr>
            <tr>
                <td colspan="3">
                    <p style="font-size: 11pt; line-height: 1.1; margin-top:15px; width:100%;">
                        <b style="font-size:12pt;">Sicherheit für Ihre Gäste und Ihr Personal</b><br><br><br>
                        Corona stellt die Gastronomie vor besondere Herausforderungen. Bester Service für Ihre Gäste und Social Distancing sind nicht leicht miteinander zu vereinbaren. Wir möchten Ihnen unsere innovative Lösung dieses Problems vorstellen: Minimaler Kontakt zwischen Ihrem Personal und Ihren Gästen bei maximalem Service.<br><br>

                        <strong>Kontaktlose Bestellung und Bezahlung per Smartphone</strong><br><br>

                        Unsere Lösung für die Gastronomie und die Hotellerie ist einfach und schnell zu implementieren. An jedem Tisch befindet sich ein QR Code. 
                        Wenn Ihre Gäste diesen Code scannen, öffnet sich die Menükarte und die Bestellung kann sofort per Smartphone vorgenommen werden. 
                        Auch die Bezahlung per Smartphone ist möglich. Zum Lieferumfang gehören ebenfalls die Flyer, die Sie auf den Tischen auslegen können, 
                        um die Gäste auf diese Bestellmöglichkeit hinzuweisen. Darüber hinaus ist im System Tischreservierung, Takeaway, Lieferung, Covid-19 Kontaktformular und vieles mehr integriert.<br><br>

                        <strong>Mit QRorpa sind sie auf der sicheren Seite</strong><br><br>

                        Niemand kann exakt vorhersagen, mit welchen Auflagen die Gastronomie in der Virensaison im Herbst und Winter zu rechnen haben wird. Mit unserer innovativen Lösung sind Sie in jedem Fall 
                        auf der sicheren Seite – mehr Kontaktreduzierung ist nicht möglich. Irgendwann wird aber auch diese Krise hinter uns liegen und es bleibt selbstverständlich Ihnen überlassen, 
                        ob Sie dann weiterhin auf das QRorpa System setzen oder zur traditionellen Methode zurückkehren möchten. Durch ihre hohe Effizienz kann unsere Lösung Ihr Personal auch dauerhaft entlasten. <br><br>

                        Testen Sie QRorpa Systeme einen Monat lang <strong>kostenlos und unverbindlich!</strong> Alles, was Sie dazu benötigen, stellen wir Ihnen zur Verfügung. Gerne würden wir mit Ihnen auch darüber sprechen, wie unser System Ihr Marketing unterstützen kann. Wir freuen uns darauf, bald von Ihnen zu hören!<br><br>

                        Freundliche Grüsse<br>
                        <strong>QRorpa Systeme & Kreative Idee Team</strong><br><br>


                       </p>
          
                </td>
                <tr style="text-align: center; width: 100%;">
                    <span class="footer-text">Giacomettistrasse 27, 7000 Chur, GR | +41 76 580 65 43 | info@kreativeidee.ch | info@qrorpa.ch/ | UID-Nr. CHE-241.120.163</span>
                </tr>
               
            </tr>
            
        </table>
        <?php
                $countLine++;
            ?>
            @endforeach
        
    </div>
</body>
</html>