<?php
    use App\BarbershopWorker;
    use App\BarbershopServiceOrdersRecords;
    use App\BarbershopWorkerTerminet;
?>
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
<h3 style="color:rgb(39,190,175)">Hallo {{ $name }}</h3>
<div style="text-align: center; color:white; font-weight:bold; font-size:20px; background-color:rgba(22,126,251,255);">
    Terminanfrage erhalten
</div>

@foreach (explode('||',$ordersRecordArray) as $oneOrdersRecordArray)
    <?php
        $theBSOR = BarbershopServiceOrdersRecords::findOrFail($oneOrdersRecordArray);
  
        $workerTer01 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin);
        $worTerminDiff = 15;
        
        $workerTermins =  $workerTer01->id;

        if($theBSOR->timeNeed > $worTerminDiff){
            $workerTer02 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 1);
            $workerTermins = $workerTermins.'||'.$workerTer02->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (2 * $worTerminDiff)){
            $workerTer03 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 2);
            $workerTermins = $workerTermins.'||'.$workerTer03->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (3 * $worTerminDiff)){
            $workerTer04 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 3);
            $workerTermins = $workerTermins.'||'.$workerTer04->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (4 * $worTerminDiff)){
            $workerTer05 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 4);
            $workerTermins = $workerTermins.'||'.$workerTer05->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (5 * $worTerminDiff)){
            $workerTer06 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 5);
            $workerTermins = $workerTermins.'||'.$workerTer06->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (6 * $worTerminDiff)){
            $workerTer07 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 6);
            $workerTermins = $workerTermins.'||'.$workerTer07->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (7 * $worTerminDiff)){
            $workerTer08 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 7);
            $workerTermins = $workerTermins.'||'.$workerTer08->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (8 * $worTerminDiff)){
            $workerTer09 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 8);
            $workerTermins = $workerTermins.'||'.$workerTer09->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (9 * $worTerminDiff)){
            $workerTer10 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 9);
            $workerTermins = $workerTermins.'||'.$workerTer10->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (10 * $worTerminDiff)){
            $workerTer11 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 10);
            $workerTermins = $workerTermins.'||'.$workerTer11->id;}
        else{ $workerTermins = $workerTermins.'||0';}

        if($theBSOR->timeNeed > (11 * $worTerminDiff)){
            $workerTer12 = BarbershopWorkerTerminet::find($theBSOR->forWorkerTermin + 11);
            $workerTermins = $workerTermins.'||'.$workerTer12->id;}
        else{ $workerTermins = $workerTermins.'||0';}
    ?>
    <?php  $theBSORDate = explode('-', $theBSOR->forDate); ?>
    <div style="padding:25px;" class="flex-container">
        <a class="AcceptRezBtn" href="https://www.qrorpa.ch/BarServiceAcceptBarSerRecEmail/?bsorId={{$oneOrdersRecordArray}}&workerTermins={{$workerTermins}}"><strong>Termin <br> <ins>annehmen</ins></strong></a>
        <a class="DeclineRezBtn" href="https://www.qrorpa.ch/BarServiceDeclineBarSerRecEmail/?bsorId={{$oneOrdersRecordArray}}"><strong>Termin <br> <ins>ablehnen</ins></strong></a>
    </div>
    <pre> {{$theBSOR->emri}}</pre>
    <pre> {{$theBSOR->pershkrimi}}</pre>
    <pre> {{$theBSOR->qmimi}} CHF</pre>
    <pre> {{$theBSOR->timeNeed}} Minute</pre>
    <pre> <strong>Mitarbeiter:</strong>      {{BarbershopWorker::find($theBSOR->forWorker)->emri}} </pre>
    <pre> <strong>Datum:</strong>            {{$theBSORDate[2]}}.{{$theBSORDate[1]}}.{{$theBSORDate[0]}} </pre>
    <hr>
@endforeach