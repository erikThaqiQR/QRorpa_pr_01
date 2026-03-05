<!-- $data = array('res'=>$resName, "tableNr" => $TabRezRecord->tableNr, "theDay" => $TabRezRecord->dita, "time1" => $TabRezRecord->koha01, "time2" => $TabRezRecord->koha02, "komenti" => $TabRezRecord->koment); -->
<?php

    use App\Restorant;

    $theRest = Restorant::find($resId); 

    $date2D = explode('-',$theDay);
 ?>
<p style="color: red; font-size:30px; text-align:center; width:100%;"><strong>Ihre Anfrage wurde abgelehnt!</strong></p>
<p style="width:100%;"></p>
<div style="width:100%; height:200px; text-align:center;">
    <img style="width:200px !important; height:200px !important; border-radius:50% !important;" width="200" height="200" border-radius="50%" src="{{url('storage/ResProfilePic/'.$theRest->profilePic)}}" alt="">
</div>
<p style="width:100%;"></p>
<p style="width:100%; margin-bottom:2px; margin-top:35px; padding:0px;"><strong>Hallo {{ $clName }}</strong></p>
<p style="width:100%; margin:2px; padding:0px;"><strong>{{ $theRest->emri }} hat ihre Reservierung leider abgelehnt.</strong></p>

<p style="width:100%;"></p>

<p style="width:100%; margin-bottom:2px; margin-top:35px; padding:0px;"><strong>Wan: {{$date2D[2]}}.{{$date2D[1]}}.{{$date2D[0]}} / {{$time1}} - {{$time2}}</strong></p>
<p style="width:100%; margin:2px; padding:0px;"><strong>Wo: {{$theRest->adresa}}</strong></p>
<p style="width:100%; margin:2px; padding:0px;"><strong>Anzahl Gäste: {{$persona}}</strong></p>

<p style="width:100%;"></p>

<p style="width:100%; margin-bottom:2px; margin-top:35px; padding:0px;"><strong>Um eine neue Reservierung vorzunehmen, besuchen Sie bitte den folgenden Link <a style="width:fit-content; color:white;" href="https://qrorpa.ch/tableRezIndex?Res=31">Tischreservierung</a></strong></p>
<p style="width:100%; margin:2px; padding:0px;"><strong>Vielen Dank für Ihr Verständnis.</strong></p>
<p style="width:100%; margin:2px; padding:0px;"><strong>Freundliche Grüsse</strong></p>
<p style="width:100%; margin:2px; padding:0px;"><strong>Ihr QRorpa Kassensysteme-Team</strong></p>

<p style="width:100%;"></p>
<p style="width:100%;"></p>
<p style="width:100%;"></p>

<p style="width:100%; text-align:center; margin:2px; padding:0px;"><strong>Benötigen Sie weitere Hilfe?</strong></p>
<div style="width:100%; text-align:center;">
    <div style="width: 100px;">
        <a style="width:fit-content;" href="https://www.facebook.com/qrorpa" width="30" height="30" style="margin-right:5px;"><img src="{{url('storage/icons/facebookQr.png')}}" width="30" height="30" alt=""></a> 
        <a style="width:fit-content;" href="https://www.instagram.com/qrorpa.ch" width="30" height="30" style="margin-right:5px;"><img src="{{url('storage/icons/instagramQr.png')}}" width="30" height="30" alt=""></a> 
        <img src="{{url('storage/icons/googlePlusQr.png')}}" width="30" height="30" alt="">
    </div>
</div>

<hr style="width:100%; margin-top:5px;  margin-bottom:5px; padding:0px;">

<div style="width:100%; text-align:center;">
    <a style="width:fit-content;" href="https://qrorpa.ch">www.qrorpa.ch</a>
    <a style="width:fit-content;" href="mailto:info@qrorpa.ch">info@qrorpa.ch</a>
    <a style="width:fit-content;" href="tel:+41765806543">076 580 65 43</a>
</div>

<hr style="width:100%; margin-top:5px;  margin-bottom:5px; padding:0px;">

<p style="text-align:center; width:100%;"><strong>Copyright © Alle Rechte vorbehalten</strong></p>