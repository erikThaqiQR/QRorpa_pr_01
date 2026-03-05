<?php
    use Illuminate\Support\Facades\Auth;
    use App\billTabletsReg;
use App\Orders;
use App\Restorant;
use Carbon\Carbon;
use App\ordersTempForTA;
use App\billTabletsCrrStat;

 
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

        <title>Bill-Tablet</title>
        <link rel="icon" href="storage/images/qrorpaIcon.png">

        <style>
            body{
                font-size: 1.8rem;
            }
        </style>
    </head>
    <body style="background-color: rgb(39,190,175);">
    
        @if (isset($_GET['hs']))
            <?php
                $billT = billTabletsReg::where('scrHash',$_GET['hs'])->first();
                $theR = Restorant::find($billT->toRes);

                if ($billT->tabletBillType == 'Ta'){
                    $taOrderForWo = ordersTempForTA::where('fromWo',$billT->toStaffId)->get();
                }
            ?>

            @if ($billT->tabletBillType == 'Res')
                <!-- RESTAURANT Bill Tablet  -->


                <div class="d-flex flex-wrap justify-content-between" style="width:99%; height:100%; background-color:whitesmoke; margin:0.5%; padding:7px; border-radius:5px;">
                    <p style="width:50%; font-size:1.5cm;" class="text-center pt-4">{{$billT->nameTitle}}</p>
                    <span style="width: 17.5%;"></span>
                    <img style="width:15%;" src="storage/ResProfilePic/{{$theR->profilePic}}"" alt="">
                    <span style="width: 17.5%;"></span>

                    <div style="width: 100%;" class="d-flex flex-wrap pl-4 pr-4" id="showOrderListDiv">
                        <p style="width:100%; font-size:1.2cm; color:blue;" class="text-center pt-4">Warten Sie auf die Rechnung...</p>
                        
                    </div>
                </div>

                <input type="hidden" id="billTblIdInp" value="{{$billT->id}}">
                <input type="hidden" id="currTableBillShown" value="0">
            
                <script>
                    var sendActiveStatInt = window.setInterval(function(){
                        $.ajax({
                            url: '{{ route("billTablet.sendNewTabletStatus") }}',
                            method: 'post',
                            data: {
                                btid: $('#billTblIdInp').val(),
                                _token: '{{csrf_token()}}'
                            },
                            success: (respo) => {
                                respo = $.trim(respo);
                                // console.log( $('#billTblIdInp').val());
                                console.log(respo);

                                // show the bill
                                if(respo == 'returnToNull'){
                                    $('#showOrderListDiv').html('<p style="width:100%; font-size:1.2cm; color:blue;" class="text-center pt-4">Warten Sie auf die Rechnung...</p>');
                                }else{
                                    if(respo != 'toOne'){
                                        $.ajax({
                                            url: '{{ route("billTablet.getAndDisplayOrdersInTablet") }}',
                                            method: 'post',
                                            dataType: 'json',
                                            data: {
                                                btid: $('#billTblIdInp').val(),
                                                tableNr: respo,
                                                _token: '{{csrf_token()}}'
                                            },
                                            success: (TabOrs) => {
                                                $('#currTableBillShown').val(respo);
                                                $('#showOrderListDiv').html('<hr style="width:100%;">');
                                                var total = parseFloat(0);
                                                $.each(TabOrs, function(index, value){
                                                    total = parseFloat(parseFloat(total)+parseFloat(value.OrderQmimi)).toFixed(2)

                                                    nexTOrShow =    '<p style="width:50%; font-size:0.75cm; color:rgb(72,81,87);" class="mb-1">'+value.OrderSasia+'X '+value.OrderEmri;
                                                    if(value.OrderType == 'empty'){
                                                        nexTOrShow += '</p>';
                                                    }else{
                                                        var type = value.OrderType;
                                                        type = type.split('||');
                                                        nexTOrShow += '<span style="opacity:0.8;"> ('+type[0]+') </span></p>';
                                                    }
                                                    nexTOrShow +=   '<p style="width:50%; font-size:0.75cm; color:rgb(72,81,87);" class="text-right mb-1">'+parseFloat(value.OrderQmimi).toFixed(2)+' CHF</p>';
                                                    $('#showOrderListDiv').append(nexTOrShow);
                                                });// end foreach

                                                $('#showOrderListDiv').append('<p style="width:50%; font-size:0.95cm; color:rgb(72,81,87);" class="mb-1 pt-2"><strong>Gesamt:</strong></p>');
                                                $('#showOrderListDiv').append('<p style="width:50%; font-size:0.95cm; color:rgb(72,81,87);" class="text-right mb-1 pt-2"><strong>'+parseFloat(total).toFixed(2)+' CHF</strong></p>');

                                            },
                                            error: (error) => { console.log(error); }
                                        });
                                    }
                                }
                            },
                            error: (error) => { console.log(error); }
                        });
                    }, 1000);
                </script>
            @else
                <!-- TAKEAWAY Bill Tablet  -->
                <div id="takeawayBillTabletShowDiv" class="d-flex flex-wrap justify-content-between" style="width:99%; height:100%; background-color:whitesmoke; margin:0.5%; padding:7px; border-radius:5px;">
                    <p style="width:50%; font-size:1.5cm; line-height:1.1; color:rgb(39,190,175);" class="text-center pt-4">
                        {{$theR->emri}}
                        <br>
                        <span style="font-size:1cm;">{{$billT->nameTitle}}</span>
                    </p>
                    <span style="width: 17.5%;"></span>
                    <img style="width:15%; border-radius:50%;" class="pt-1 pb-1" src="storage/ResProfilePic/{{$theR->profilePic}}"" alt="">
                    <span style="width: 17.5%;"></span>
                    <hr style="width:100%;">
                    <?php
                        $orForBill = Orders::where([['nrTable','500'],['servedBy',$billT->toStaffId],['created_at', '>', Carbon::now()->subMinutes(2)->toDateTimeString()]])->orderByDesc('created_at')->first();
                    ?>
                    @if (count($taOrderForWo) > 0)
                        <?php
                            $totalPay = number_format(0, 2, '.', '');
                        ?>
                        <div style="width: 100%;" class="d-flex flex-wrap pl-4 pr-4" id="showOrderListDivTA">
                            <p style="width:50%; font-size:0.85cm; color:rgb(72,81,87);" class="mb-1"><strong>Produkt</strong></p>
                            <p style="width:25%; font-size:0.85cm; color:rgb(72,81,87);" class="text-right mb-1"><strong>Eine</strong></p>
                            <p style="width:25%; font-size:0.85cm; color:rgb(72,81,87);" class="text-right mb-1"><strong>Alle</strong></p>
                            @foreach ($taOrderForWo as $oneTAOrd)
                                <p style="width:50%; font-size:0.75cm; color:rgb(72,81,87);" class="mb-1">{{$oneTAOrd->proSasia}}X {{$oneTAOrd->taProdName}}
                                    @if ($oneTAOrd->proType != 'empty')
                                        <span style="opacity:0.8;"> ({{explode('||',$oneTAOrd->proType)[0]}}) </span>
                                    @endif
                                </p>
                                <p style="width:25%; font-size:0.75cm; color:rgb(72,81,87);" class="text-right mb-1">{{number_format($oneTAOrd->proQmimi, 2, '.', '')}} CHF</p>
                                <p style="width:25%; font-size:0.75cm; color:rgb(72,81,87);" class="text-right mb-1">{{number_format($oneTAOrd->proQmimi * $oneTAOrd->proSasia, 2, '.', '')}} CHF</p>
                                <?php $totalPay += number_format($oneTAOrd->proQmimi * $oneTAOrd->proSasia, 2, '.', ''); ?>
                            @endforeach
                            <hr style="width: 100%;" class="mt-1 mb-1">
                            <p style="width:50%; font-size:0.80cm; color:rgb(72,81,87);" class="mb-1 pt-2"><strong>Zwischensumme:</strong></p>
                            <p style="width:50%; font-size:0.80cm; color:rgb(72,81,87);" class="text-right mb-1 pt-2"><strong>{{number_format($totalPay, 2, '.', '')}} CHF</strong></p>
                            
                            <p style="width:50%; font-size:0.80cm; color:rgb(72,81,87);" class="mb-1 pt-2"><strong>Tipp:</strong></p>
                            <p style="width:50%; font-size:0.80cm; color:rgb(72,81,87);" class="text-right mb-1 pt-2"><strong>+ {{number_format($billT->currTipp, 2, '.', '')}} CHF</strong></p>
                            <p style="width:50%; font-size:0.80cm; color:rgb(72,81,87);" class="mb-1 pt-2"><strong>Rabatt:</strong></p>
                            <p style="width:50%; font-size:0.80cm; color:rgb(72,81,87);" class="text-right mb-1 pt-2"><strong>- {{number_format($billT->currRabatt, 2, '.', '')}} CHF</strong></p>
                            <p style="width:50%; font-size:0.80cm; color:rgb(72,81,87);" class="mb-1 pt-2"><strong>Geschenkkarte:</strong></p>
                            <p style="width:50%; font-size:0.80cm; color:rgb(72,81,87);" class="text-right mb-1 pt-2"><strong>- {{number_format($billT->currGCValue, 2, '.', '')}} CHF</strong></p>

                            <p style="width:50%; font-size:1.15cm; color:rgb(72,81,87);" class="mb-1 pt-2"><strong>Gesamtsumme:</strong></p>
                            <p style="width:50%; font-size:1.15cm; color:rgb(72,81,87);" class="text-right mb-1 pt-2"><strong>{{number_format($totalPay+$billT->currTipp-$billT->currRabatt-$billT->currGCValue, 2, '.', '')}} CHF</strong></p>
                            
                            <hr style="width:100%;">
                            
                        </div>
                    @elseif($orForBill != Null && $billT->showBillQRCode == 1)
                        <div style="width: 100%;" class="d-flex flex-wrap pl-4 pr-4" id="showOrderListDivTA">
                            <p style="width:100%; font-size:1.1cm; font-weight:bold; color:rgb(72,81,87);" class="mb-1 pt-2 text-center">
                                Scannen Sie den untenstehenden QR-Code, um die Rechnung im PDF-Format herunterzuladen.
                            </p>
                            
                            
                            <img id="GetBillQRCode" style="width:60%; height:auto; margin-right:20%; margin-left:20%;" class="mb-5 mt-5"
                            src="storage/digitalReceiptQRK/{{$orForBill->digitalReceiptQRK}}" alt="qrCodeNotFound">

                            <p style="width:100%; font-size:1.1cm; font-weight:bold; color:rgb(72,81,87);" class="mb-1 pt-2 text-center ">
                                CODE: {{explode('|',$orForBill->shifra)[1]}}
                            </p>
                          
                        </div>
    

                    @else
                        <div style="width: 100%;" class="d-flex flex-wrap" id="showOrderListDivTA">
                            <img id="GetBillQRCode" style="width:100%; height:auto;" src="storage/gifs/TakeawayWaitForOrderGif.gif" alt="qrCodeNotFound">
                        </div>
                        
                    @endif
                </div>

                <script>
                    var sendActiveStatInt = window.setInterval(function(){
                        $("#takeawayBillTabletShowDiv").load(location.href+" #takeawayBillTabletShowDiv>*","");
                    }, 1000);
                </script>
            @endif
        @else
            <!-- no hash -->
            <p style="color:white; margin-top:2cm; font-size:2.4rem; width:100%;" class="text-center"><strong>Dies ist kein gültiger Versuch, dieses Gerät für die Rechnung zu aktivieren</strong></p>
        @endif
        
    </body>
</html>