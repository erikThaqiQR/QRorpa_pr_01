<?php
    use App\PiketLog;
    use App\User;
    use App\Restorant;
?>
<style>
    .anchorsRem{
        color:white;
    }
    .anchorsRem:hover{
        color:white;
        text-decoration: none;
        font-size: 19px;
    }
</style>

<section class="pl-3 pr-3 pt-5 pb-5">
    <div class="d-flex justify-content-between">
         <h3 style="font-weight:bolder; width:55%;" class="color-qrorpa">Points used and earned from users</h3>
         <a style="width:20%; outline: 0;" class="anchorsRem" href="{{route('piket.indexRes')}}" expand="true">
            <button class="btn btn-block p-2" style="color:white; background-color:rgb(39, 190, 175); border-radius:30px; font-weight:bold; outline: 0;">
                Restaurant
            </button>
        </a>
        <a style="width:20%; outline: 0;" class="anchorsRem" href="{{route('piket.indexCli')}}" expand="true">
            <button class="btn btn-block p-2" style="color:white; background-color:rgb(39, 190, 175); border-radius:30px; font-weight:bold; outline: 0;">
                Klient
            </button>
        </a>

    </div>
    <hr>

    <div class="d-flex justify-content-between flex-wrap mt-2" style="font-size:19px;">
        <p style="font-weight:bold; width:16.5%;"></p>
        <p style="font-weight:bold; width:16.5%;">Restorant</p>
        <p style="font-weight:bold; width:16.5%;">Klient</p>
        <p style="font-weight:bold; width:16.5%;">Porosi Nr.</p>
        <p style="font-weight:bold; width:16.5%;">Porosi Shuma.</p>
        <p style="font-weight:bold; width:16.5%;">Pikët</p>


        @foreach(PiketLog::all()->sortByDesc('created_at')->take(1000); as $pointTr)

            <p style=" width:16.5%; margin-top:-5px; border-bottom:1px solid lightgray;">{{explode(' ',$pointTr->created_at)[0]}} <br> <span>{{explode(' ',$pointTr->created_at)[1]}}</span> </p>
            @if(Restorant::find($pointTr->toRes) != null)
                <p style=" width:16.5%; border-bottom:1px solid lightgray; padding-top:5px;">{{Restorant::find($pointTr->toRes)->emri}}</p>
            @else
                <p style=" width:16.5%; color:red; border-bottom:1px solid lightgray; padding-top:5px;">Removed</p>
            @endif
            @if(User::find($pointTr->klienti_u) != null)
            <p style=" width:16.5%; border-bottom:1px solid lightgray; padding-top:5px;">{{User::find($pointTr->klienti_u)->name}}</p>
            @else
                 <p style=" width:16.5%; color:red; border-bottom:1px solid lightgray; padding-top:5px;">Removed</p>
            @endif
            <p style=" width:16.5%; border-bottom:1px solid lightgray; padding-top:5px;">{{$pointTr->order_u}}</p>
            <p style=" width:16.5%; border-bottom:1px solid lightgray; padding-top:5px;">{{$pointTr->shumaPor}} <sup class="ml-2"> CHF</sup> </p>
            <p style=" width:16.5%; font-weight:bold; border-bottom:1px solid lightgray; padding-top:5px;">
            @if($pointTr->piket > 0)+@endif
                {{$pointTr->piket}}
            </p>
  
        @endforeach
    </div>
   
</section>