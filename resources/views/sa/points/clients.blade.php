<?php
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
        <a style="width:5%;" class="anchorsRem2 text-left" href="{{route('piket.index')}}" expand="true">
            <button class="btn btn-block backBtn" style="color:rgb(39, 190, 175); font-weight:bold; font-size:30px; outline: none;">
                <
            </button>
        </a>
         <h3 style="font-weight:bolder; width:45%;" class="color-qrorpa text-left mt-3">Select a client first!</h3>
         <h3 style="font-weight:bolder; width:45%;" class="color-qrorpa text-right mt-3 pr-4">Filter: Clients</h3>
    </div>
    <hr>

    <div class="d-flex flex-wrap justify-content-between">
        @foreach(Piket::all()->sortByDesc('updated_at') as $pTab)
        <?php $theU = User::find($pTab->klienti_u); ?>  
            @if($theU != null)
                <a style="width:25%;" class="anchorsRem text-center p-2 mb-3" href="PiketCliOne?Cli={{$theU->id}}" expand="true">
                
                        <p style="font-size:20px;"><strong> {{$theU->name}} </strong></p>
                        <p style="margin-top:-7px; margin-bottom:-3px;">Points : {{$pTab->piket}}</p>

                  
                </a>    
            @endif
        @endforeach
    </div>
</section>