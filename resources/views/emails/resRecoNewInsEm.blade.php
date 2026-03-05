<?php

    use App\resContRecommended;
    $newResRec = resContRecommended::find($resRecId);
?>
<div style="padding:25px;">
    <h4>Hallo <strong>{{ $name }}</strong></h4>
    <p></p>
    <p><strong>Ein Kunde hat eine neue Empfehlung für ein Unternehmen für QRorpa registriert</strong></p>
    <p></p>
    <p></p>
    <p><i><strong>Kundendaten</strong></i></p>
    <pre>Vorname :  {{$newResRec->konPerVorname}}</pre>
    <pre>Name :     {{$newResRec->konPerName}}</pre>
    <pre>Adresse :  {{$newResRec->konPerAdresse}}</pre>
    <pre>PLZ/Ort :  {{$newResRec->konPerPLZ}} / {{$newResRec->konPerOrt}}</pre>
    <pre>Nr.Tel. :  {{$newResRec->konPerTel}}</pre>
    <pre>Email :    {{$newResRec->konPerEmail}}</pre>
    <p></p>
    <p><i><strong>Empfehlungsdaten</strong></i></p>
    <pre>Betrieb :  {{$newResRec->betBetrieb}}</pre>
    <pre>Inhaber :  {{$newResRec->betInhaber}}</pre>
    <pre>Adresse :  {{$newResRec->betAdresse}}</pre>
    <pre>PLZ/Ort :  {{$newResRec->betPLZ}}/{{$newResRec->betOrt}}</pre>
    <pre>Nr.Tel. :  {{$newResRec->betTel}}</pre>
    <pre>Email :    {{$newResRec->betEmail}}</pre>

</div>