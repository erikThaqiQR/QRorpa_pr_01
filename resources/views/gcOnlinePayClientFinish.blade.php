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
    @if (isset($_GET['stat']) && isset($_GET['gcId']))
        @if ($_GET['stat'] == "payed")
            @php
                $onlPayGCIns = giftCard::find($_GET['gcId']);
            @endphp

            <div class="m-1" style="background-color: white; border-radius:7px;">
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.1rem;">{{Restorant::find($onlPayGCIns->toRes)->emri}} Geschenkkarte</p>
                <hr style="margin:3px 0 3px 0;">
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.3rem;">{{$onlPayGCIns->idnShortCode}}</p>
                <p class="text-center mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.5rem;">{{number_format($onlPayGCIns->gcSumInChf,2,'.','')}} CHF</p>
                <hr style="margin:3px 0 3px 0;">
                <p class="text-center mb-1" style="color:green; font-weight:bold; font-size:1.5rem;">Bezahlt</p>
            </div>
            
        @elseif ($_GET['stat'] == "noopr")
            
        @endif
    @endif
@endsection