<div style="padding:25px;">

    <p style="line-height: 1.1;"><strong>Hallo {{$name}},</strong></p>
    <p></p>
    <p style="line-height: 1.1;">vielen Dank, dass Sie eine Geschenkkarte bei <strong>{{$resName}}</strong> erworben haben.</p>
    <p></p>
    <p style="line-height: 1.1;">Wir möchten Sie freundlich daran erinnern, dass die Rechnung für Ihre Geschenkkarte im Wert von <strong><span style="font-size:18px; color:red;">{{number_format($gcValue,2,'.','')}} CHF</span></strong> noch aussteht.</p>
    <p></p>

    <?php $gcDueDt2D = explode('-',$gcDueDate); ?>
    <p style="line-height: 1.1;"><strong>Bitte beachten Sie, dass die Zahlung bis spätestens <span style="font-size:18px; color:red;">{{$gcDueDt2D[2]}}.{{$gcDueDt2D[1]}}.{{$gcDueDt2D[0]}}</span> erfolgen muss.</strong></p>
    <p></p>

    <p style="line-height: 1.1;">Sie können die Zahlung bequem über den folgenden Link vornehmen:</p>
    <a href="https://qrorpa.ch/giftCardOnlinePay?s={{$gcId}}&hs={{$gcHash}}">Jetzt online bezahlen</a>
    <p>______________________________</p>

    <p style="line-height: 1.1;">Den aktuellen Saldo und weitere Informationen zu Ihrer Geschenkkarte finden Sie hier:</p>
    <a href="https://qrorpa.ch/giftCardcheckBalance?gcid={{$gcId}}&hs={{$gcHash}}">Guthaben und Verlauf der Geschenkkarte prüfen</a>
    <p>______________________________</p>

    <p style="line-height: 1.1;">Bei Fragen stehen wir Ihnen gerne zur Verfügung.</p>
    <p></p>
    <p style="line-height: 1.1;"><strong>Wir freuen uns auf Ihren Besuch bei {{$resName}}!</strong></p>

</div>