<div style="padding:25px;">

    <p style="line-height: 1.1;"><strong>Hallo und vielen Dank, dass Sie eine Geschenkkarte bei {{$resName}} gekauft haben!</strong></p>
    <p></p>
    <?php $gcExpDt2D = explode('-',$gcExpDt); ?>
    <p style="line-height: 1.1;"><strong>Der Wert Ihrer Geschenkkarte beträgt <span style="font-size:18px; color:red;">{{number_format($gcValue,2,'.','')}} CHF</span> und ist bis zum {{$gcExpDt2D[2]}}.{{$gcExpDt2D[1]}}.{{$gcExpDt2D[0]}} gültig.</strong></p>
    <p></p>
    <p style="line-height: 1.1;"><strong>Sie können Ihre Geschenkkarte ganz einfach beim Kellner einlösen - geben Sie einfach den Code <span style="font-size:18px; color:red;">„{{$gcCode}}“</span> an oder lassen Sie den QR-Code vom Kellner scannen.</strong></p>
    <img style="width:200px !important; height:200px !important;" width="200" height="200" src="{{url('storage/giftcardQRCode/'.$gcQrCode)}}" alt="">
    <p>______________________________</p>
    <p style="line-height: 1.1;"><strong>Den aktuellen Saldo dieser Karte und weitere Informationen können Sie über den untenstehenden Link einsehen.</strong></p>
    <a href="https://qrorpa.ch/giftCardcheckBalance?gcid={{$gcId}}&hs={{$gcHash}}">Überprüfen Sie das Guthaben und den Verlauf dieser Geschenkkarte</a>
    <p>______________________________</p>
    <p style="line-height: 1.1;"><strong>Diese Geschenkkarte kann online bezahlt werden. Wenn Sie sie noch nicht bezahlt haben, folgen Sie dazu bitte dem untenstehenden Link.</strong></p>
    <a href="https://qrorpa.ch/giftCardOnlinePay?s={{$gcId}}&hs={{$gcHash}}">Online-Zahlungsseite für Geschenkkarten</a>
    <p>______________________________</p>
    <p style="line-height: 1.1;"><strong>Wir wünschen Ihnen viel Freude beim Einlösen Ihrer Geschenkkarte und einen wunderbaren Besuch bei {{$resName}}!</strong></p>
</div>