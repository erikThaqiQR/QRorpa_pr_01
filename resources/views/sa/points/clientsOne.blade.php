<?php
if(isset($_GET['Cli'])){
    $theCli = $_GET['Cli'];
}else{
    header("Location: ".route('piket.index'));
    exit();
}
    use App\PiketLog;
    use App\Piket;
    use App\User;
    use App\Restorant;
?>
<style>
    .anchorsRem{
        color:rgb(39, 190, 175);
        border-radius:25px;
        border:1px solid rgb(39, 190, 175);
        font-weight: bold;
    }
    .anchorsRem:hover{
        color:white;
        background-color: rgb(39, 190, 175);
        text-decoration: none;
        font-weight: bold;
    }
    .anchorsRem2{
        color:white;
    }
    .anchorsRem2:hover{
        color:white;
        text-decoration: none;
        font-size: 19px;
    }
    .backBtn{
        color:rgb(39, 190, 175);
    }
    .backBtn:hover{
        color:white;
    }
</style>
<section class="pl-3 pr-3 pt-5 pb-5">
    <div class="d-flex justify-content-between">
        <a style="width:5%;" class="anchorsRem2 text-left" href="{{route('piket.indexCli')}}" expand="true">
            <button class="btn btn-block backBtn" style="color:rgb(39, 190, 175); font-weight:bold; font-size:30px; outline: none;">
                <
            </button>
        </a>
         <h3 style="font-weight:bolder; width:45%;" class="color-qrorpa text-left mt-3">{{User::find($theCli)->name}}</h3>
         <h3 style="font-weight:bolder; width:45%;" class="color-qrorpa text-right mt-3 pr-4">transactions</h3>
    </div>
    <hr>

    <div class="d-flex justify-content-between flex-wrap mt-2" style="font-size:19px;">
        <p style="font-weight:bold; width:20%;"></p>
        <p style="font-weight:bold; width:20%;">Restorant</p>
        <p style="font-weight:bold; width:20%;">Porosi Nr.</p>
        <p style="font-weight:bold; width:20%;">Porosi Shuma.</p>
        <p style="font-weight:bold; width:20%;">Pikët</p>
        @foreach(PiketLog::Where('klienti_u', $theCli)->get()->sortByDesc('created_at') as $cTab)
            <p style=" width:20%; margin-top:-5px; border-bottom:1px solid lightgray;">{{explode(' ',$cTab->created_at)[0]}} <br> <span>{{explode(' ',$cTab->created_at)[1]}}</span> </p>
            @if(Restorant::find($cTab->toRes) != null)
                <p style=" width:20%; border-bottom:1px solid lightgray; padding-top:5px;">{{Restorant::find($cTab->toRes)->emri}}</p>
            @else
                <p style=" width:20%; color:red; border-bottom:1px solid lightgray; padding-top:5px;">Removed</p>
            @endif
      
            <p style=" width:20%; border-bottom:1px solid lightgray; padding-top:5px;">{{$cTab->order_u}}</p>
            <p style=" width:20%; border-bottom:1px solid lightgray; padding-top:5px;">{{$cTab->shumaPor}} <sup class="ml-2"> CHF</sup> </p>
            <p style=" width:20%; font-weight:bold; border-bottom:1px solid lightgray; padding-top:5px;">
            @if($cTab->piket > 0)+@endif
                {{$cTab->piket}}
            </p>      
        @endforeach
    </div>
</section>