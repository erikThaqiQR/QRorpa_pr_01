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
        <a style="width:5%;" class="anchorsRem test-left" href="{{route('piket.index')}}" expand="true">
            <button class="btn btn-block" style="color:rgb(39, 190, 175); font-weight:bold; font-size:30px; outline: none;">
                <
            </button>
        </a>
         <h3 style="font-weight:bolder; width:45%;" class="color-qrorpa text-left mt-3">Select a restaurant first!</h3>
         <h3 style="font-weight:bolder; width:45%;" class="color-qrorpa text-right mt-3 pr-4">Filter: Restaurants</h3>
    </div>
    <hr>
    <div class="d-flex justify-content-between">
        @foreach(Restorant::all()->sortByDesc('created_at') as $rest)
        <a style="width:25%; outline: 0;" class="anchorsRem mt-2" href="PiketResOne?Res={{$rest->id}}" expand="true">
            <button class="btn btn-block p-3" style="color:rgb(39, 190, 175); background-color:white; border-radius:30px; border:2px solid rgb(39, 190, 175); font-weight:bold; outline: 0;">
                {{$rest->emri}}
            </button>
        </a>
        @endforeach
    </div>

</section>