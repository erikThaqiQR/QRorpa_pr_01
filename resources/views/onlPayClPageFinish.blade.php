@extends('layouts.appOnlPayCl')
<?php

use Carbon\Carbon;
    use App\Cupon;
    use App\Orders;
    use App\onlinePayQRCStaf;
    use App\Restorant;
    use App\TableQrcode;
    use App\TabOrder;

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

    @if(Cookie::has('trackMO') && Cookie::get('trackMO') != 'not')
        <div class="m-1 p-2" style="background-color: white; border-radius:7px;">
        @php
            $orByTMO = Orders::whereDate('created_at', '=', Carbon::today())->where('shifra',Cookie::get('trackMO'))->first();
        @endphp
            <p class="text-center mb-1" style="color:rgb(39,190,175); font-size:1.1rem;">Sie haben die Zahlung für Bestellung Nr. {{$orByTMO->refId}} erfolgreich abgeschlossen</p>
        
            @if ($orByTMO->nrTable == 500)
            <p class="text-center" style="color:rgb(39,190,175); font-size:1.6rem;">
                <strong>{{explode('|',$orByTMO->shifra)[1]}}</strong>
            </p>
            @endif
            
            <hr style="margin:3px 0 3px 0;">
          
            <div class="d-flex flex-wrap justify-content-between pr-2 pl-2">
                @foreach (explode('---8---',$orByTMO->porosia) as $orInTab)
                    @php
                        $orInTab2D = explode('-8-',$orInTab);
                    @endphp
                    <p style="width:15%; margin:0px;">{{$orInTab2D[3]}} X</p>
                    <div style="width:85%; margin:0px;">
                        {{$orInTab2D[0]}}
                        @if ($orInTab2D[5] != 'empty' && $orInTab2D[5] != '')
                            <span style="opacity:0.7;" class="ml-1">( {{explode('||',$orInTab2D[5])[0]}} )</span>
                        @endif

                        @if ($orInTab2D[2] != 'empty' && $orInTab2D[2] != '')
                            <br>
                            <div class="d-flex flew-wrap">
                            @foreach (explode('--0--',$orInTab2D[2]) as $OneEx)
                                <span style="width:fit-content; opacity:0.7; font-size:0.7rem;" class="mr-1">
                                @if ($loop->first)
                                {{explode('||',$OneEx)[0]}}
                                @else
                                , {{explode('||',$OneEx)[0]}}
                                @endif
                                </span>
                            @endforeach
                            </div>
                        @endif
                    </div>

                @endforeach
                @php
                    if($orByTMO->inCashDiscount > 0){
                        $skontoCHF = number_format($orByTMO->inCashDiscount,2,'.','');
                        $toPay = number_format($orByTMO->shuma - $orByTMO->dicsountGcAmnt - $skontoCHF,2,'.','');
                    }else if($orByTMO->inPercentageDiscount > 0){
                        $preTot = number_format($orByTMO->shuma - $orByTMO->tipPer,2,'.','');
                        $skontoCHF = number_format($preTot*($orByTMO->inPercentageDiscount/100),2,'.','');
                        $toPay = number_format($orByTMO->shuma - $orByTMO->dicsountGcAmnt - $skontoCHF,2,'.','');
                    }else{
                        $toPay = number_format($orByTMO->shuma - $orByTMO->dicsountGcAmnt,2,'.','');
                    }  

                @endphp
                <p class="mb-1" style="color:rgb(39,190,175); font-weight:bold; font-size:1.3rem; width:50%;">Bezahlt:</p>
                <p class="mb-1 text-right" style="color:rgb(39,190,175); font-weight:bold; font-size:1.3rem; width:50%;">
                    <span id="toPayShow">{{number_format($toPay,2,'.','')}} </span>
                    <span style="opacity: 0.7; font-size:1rem;">CHF</span>
                </p>
            </div>
            
            <hr style="margin:3px 0 3px 0;">

            <a style="padding-top:0px ; padding-bottom:0px;" class="btn btn-block btn-outline-dark" 
            href="generatePDF/{{$orByTMO->id}}||{{$orByTMO->digitalReceiptQRKHash}}">
                <strong><i class="fa-regular fa-file-pdf"></i> Rechnung</strong>
            </a> 

        </div>
    @endif


@include('onlPayClPageScript')

@endsection