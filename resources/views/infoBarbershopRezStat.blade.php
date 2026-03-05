@extends('layouts.appInfoTableRez')
<?php
    use App\BarbershopServiceOrder;
    use App\BarbershopServiceOrdersRecords;
use App\BarbershopWorker;
use App\BarbershopWorkerTerminBusy;
use App\BarbershopWorkerTerminet;

?>
<style>
    table, th, td {
        border: 1px solid rgb(72,81,87);
        padding:5px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
</style>
@section('content')
    <div style="background-color: white; border-radius:20px; margin-top:20px; padding:25px;">
        @if(isset($_GET['bsorId']) && isset($_GET['error']))

            <h3 style="color:red;"><strong>Diese Reservierungsanfrage wurde schon einmal bearbeitet, es ist nicht erlaubt, den Status erneut zu ändern!</strong></h3>

        @elseif(isset($_GET['bsorId']))
            <?php
                $barSerOrRec = BarbershopServiceOrdersRecords::findOrFail($_GET['bsorId']);
            ?>
            @if($barSerOrRec != NULL)
                @if($barSerOrRec->status == 2)
                <h3 style="color:rgb(39,190,175);">Diese Reservierung ist für Ihr Unternehmen bestätigt</h3>
                @elseif($barSerOrRec->status == 1)
                <h3 style="color:red;">Diese Reservierung wurde für Ihr Unternehmen abgelehnt</h3>
                @endif
                <table>
                    <tr>
                        <td colspan="4"><strong>{{$barSerOrRec->emri}} 
                            @if($barSerOrRec->pershkrimi != '...')
                                ( {{$barSerOrRec->pershkrimi}} )
                            @endif
                            </strong>
                        </td>
                        <th>{{$barSerOrRec->qmimi}} CHF 
                            @if($barSerOrRec->forStudent == 1)
                                ( Abonnentenpreis )
                            @endif
                        </th>
                        <th>{{$barSerOrRec->timeNeed}} Minute</th>
                    </tr>
                    <tr style="text-align:center;">
                        <?php 
                            $barSerOrRecDate2D = explode('-',$barSerOrRec->forDate);
                            $barSerOrRecWorker = BarbershopWorker::findOrFail($barSerOrRec->forWorker);
                        ?>
                        @foreach(BarbershopWorkerTerminBusy::where('serviceRecord',$barSerOrRec->id)->get() as $worTermin)
                            @if ($loop->first)
                                @php
                                    $terminStart = BarbershopWorkerTerminet::findOrFail($worTermin->workerTerminID)->startT;
                                @endphp
                            @elseif($loop->last)
                                @php
                                    $terminEnd = BarbershopWorkerTerminet::findOrFail($worTermin->workerTerminID)->endT;
                                @endphp
                            @endif
                        @endforeach
                        <td>Arbeiter: {{ $barSerOrRecWorker->emri }}</td>
                        <td>Datum: {{ $barSerOrRecDate2D[2] }}.{{ $barSerOrRecDate2D[1] }}.{{ $barSerOrRecDate2D[0] }}</td>
                        <td colspan="2"> Wo {{ $terminStart }} bis {{ $terminEnd }} </td>
                        <td colspan="2"></td>
                    </tr>
                </table>
            @endif    
        @endif
    </div>
@endsection