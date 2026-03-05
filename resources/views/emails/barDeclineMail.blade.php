<style>
    .topBar{
        text-align: center;
        color: white !important;
        background-color: rgb(214,28,28) !important;
        font-size: 25px !important;
        font-weight: bold !important;
        padding-top: 25px !important;
        padding-bottom: 25px !important;
    }
</style>
<div class="topBar">
    Reservierung bei {{ $bar->emri }} wurde abgelehnt
</div>
<div style="padding-left:40px; margin-top:30px;">
    <p>Hallo {{ $name }}</p>
    <p></p>
    <p>Ihre Reservierung wurde leider abgelehnt</p>
    <p></p>
    <?php $dt=explode('-',$body->forDate); ?>
    <pre><strong>Datum:</strong>        {{$dt[2]}}.{{$dt[1]}}.{{$dt[0]}}</pre>
    <pre><strong>Uhrzeit:</strong>      <span style="color:blue;">{{$time}}</span></pre>
    <pre><strong>Leistung(en):</strong> {{$body->emri}}</pre>
    <pre><strong>Mitarbeiter:</strong>  {{$worker}}</pre>
    <pre><strong>Ort:</strong>          <span style="color:blue;">{{$address}}</span></pre>
    <p></p>
    <p></p>
    <p>Mit freundlichen Grüssen</p>
    <p>{{ $bar->emri }}</p>
</div>

