<div style="padding:25px;">
    <h4>Hallo <strong>{{ $name }}</strong></h4>
    <p></p>
    <p>{{ $theAdmin }} hat Ihnen eine Nachricht geschrieben für <span style="color:rgb(72,81,87); font-weight:bold;">{{$avReason}}</span></p>
    <p>Bitte fahren Sie mit diesem Link fort, um das Gespräch zu beginnen <a style="color:rgb(39,190,175); font-weight:bold;" href="https://qrorpa.ch/SaAdminMSG">QRorpa chat system</a></p>
    <p></p>
    <p><i><strong>Zusätzliche Information</strong></i></p>
    <pre>Administratorname :       {{$theAdmin}}</pre>
    <pre>Admin-E-Mail :            {{$admEmail}}</pre>
    <pre>Restaurant :              {{$resName}}</pre>
    <pre>Adresse des Restaurants : {{$resAdr}}</pre>
</div>