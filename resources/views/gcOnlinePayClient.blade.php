@extends('layouts.appOnlPayCl')
<?php

use App\giftCard;
use App\Restorant;

?>

<style>
    .selectedTip{
        background-color:rgb(39,190,175) !important;
        color:white !important;
    }
    .selectedTip:hover{
        opacity:0.7 !important;
        color:white !important;
    }
</style>
@section('content')
    @if (!isset($_GET['s']) || !isset($_GET['hs']))
        <p class="mt-3 p-2 text-center" style="color:white; font-size:1.3rem;"> 
            <strong>Scannen Sie den QR-Code, um diese Seite richtig zu nutzen!</strong>
        </p>
    @else
        @php
            $onlPayGCIns = giftCard::where([['id',$_GET["s"]],['gcHash',$_GET["hs"]]])->first();
        @endphp
        @if ($onlPayGCIns == Null)
            <p class="mt-3 p-2 text-center" style="color:white; font-size:1.3rem;"> 
                <strong>Die Geschenkkarte, für die Sie bezahlen möchten, wird in unserem System nicht gefunden. Wenden Sie sich bitte an die Administratoren!</strong>
            </p>
        @elseif ($onlPayGCIns->onlinePayStat == 1)
        <p class="mt-3 p-2 text-center" style="color:white; font-size:1.3rem;"> 
            <strong>Die Geschenkkarte, die Sie bezahlen möchten, ist bereits bezahlt!</strong>
        </p>
        @else
            <div class="m-1" style="background-color: white; border-radius:7px;">
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">{{Restorant::find($onlPayGCIns->toRes)->emri}} Geschenkkarte</p>
                <hr style="margin:3px 0 3px 0;">
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.3rem;">{{$onlPayGCIns->idnShortCode}}</p>
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.5rem;">{{number_format($onlPayGCIns->gcSumInChf,2,'.','')}} CHF</p>
                <hr style="margin:3px 0 3px 0;">
                <button class="btn btn-success" style="width:100%; margin:16px 0 0 0;" id="payOnlClFSBtn1" onclick="payOnlClFS('{{$onlPayGCIns->id}}','{{$onlPayGCIns->gcHash}}','{{$onlPayGCIns->toRes}}')">
                    Online-Bezahlung
                </button>
            </div>

        @endif
    @endif

    <script>
        function payOnlClFS(onPayGCId, onPayGCHash, onPayGCRes){
            // Test Account
            $.ajax({
                url: '{{ route("giftCard.giftCardOnlinePayInitiatePay") }}',
                method: 'post',
                data: {
                    onlPayGiftCardId: onPayGCId,
                    onlPayGiftCardHash: onPayGCHash,
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    // console.log(response);
                    $('#payOnlClFSBtn1').prop('disabled',true);
                    var dataJ = JSON.stringify(response);
                    var dataJ2 = JSON.parse(dataJ);
                    window.location.href = dataJ2['body']['RedirectUrl'];
                },
                error: (error) => { console.log(error); }
            });
        }
    </script>
@endsection