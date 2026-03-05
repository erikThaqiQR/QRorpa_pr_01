<!-- $data = array('res'=>$res->emri, "tableNr" => $req->table, "xPersona" => $req->persona, "theDay" => $req->dita, "time1" => $req->koha1, "time2" => $req->koha2, 
"emri" => $req->emri, "mbiemri" => $req->mbiemri, "phoneNr" => $sendTo2, "email" => $req->email, "komenti" => $req->koment); -->
<!-- "tabRezId" => $newTabReser->id, -->
<!-- "tabRezHash" => $randHash, -->

<style>
    .flex-container {
        display: flex;
        justify-content: space-between;
    }

    .AcceptRezBtn{
        width:49%;
        background-color: green;
        color:white;
        padding: 15px;
        text-align: center;
        font-weight: bold;
        font-size: 17px;

    }
    .DeclineRezBtn{
        width:49%;
        background-color: red;
        color:white;
        padding: 15px;
        text-align: center;
        font-weight: bold;
        font-size: 17px;
    }
</style>

<div style="text-align: center; color:white; font-weight:bold; font-size:20px; background-color:rgba(22,126,251,255);">
    Sie haben eine neue <br> Tischreservierung erhalten
</div>

<!-- <div style="padding:25px;" class="flex-container">
    <a class="AcceptRezBtn" href="https://www.qrorpa.ch/tableRezProcesFromEmailA/?var1={{$tabRezId}}&var2={{$tabRezHash}}&var3=1">Reservierung annehmen</a>
    <a class="DeclineRezBtn" href="https://www.qrorpa.ch/tableRezProcesFromEmailA/?var1={{$tabRezId}}&var2={{$tabRezHash}}&var3=2">Reservierung ablehnen</a>
</div> -->
<div style="padding-left:25px; padding-right:25px; font-weight:bold;">
    <?php
        $theDay2D = explode('-',$theDay);
        switch($theDay2D[1]){
            case 1: $month = "Januar"; break;
            case 2: $month = "Februar"; break;
            case 3: $month = "März"; break;
            case 4: $month = "April"; break;
            case 5: $month = "Mai"; break;
            case 6: $month = "Juni"; break;
            case 7: $month = "Juli"; break;
            case 8: $month = "August"; break;
            case 9: $month = "September"; break;
            case 10: $month = "Oktober"; break;
            case 11: $month = "November"; break;
            case 12: $month = "Dezember"; break;
        }
    ?>


    <pre>Name:              {{$emri}} {{$mbiemri}}</pre>
    <pre>Datum:             {{$theDay2D[2]}}.{{$month}}.{{$theDay2D[0]}} </pre>
    <pre>Uhrzeit:           von {{$time1}} Uhr bis {{$time2}}</pre>
    <pre>Anzahl Personen:   {{$xPersona}}</pre>
    <pre>Tichnummer:        Noch nicht festgelegt</pre>
    <pre>Tel. Nr.:          {{$phoneNr}}</pre>
    <pre>E-Mail:            {{$email}}</pre>
    <pre>Kommentar:         {{$komenti}}</pre>
</div>


<!-- var1 = tab Re Id -->
<!-- var2 = tab Re Hash -->
<!-- var3 = tab Re Confirm=1 Decline=2 -->



