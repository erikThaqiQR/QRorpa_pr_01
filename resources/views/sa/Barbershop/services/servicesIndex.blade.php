<?php
      use App\Barbershop;
?>
<style>
    .barbershopsSer{
        color: white;
        background-color: rgb(39,190,175);
        font-weight: bold;
        border-radius: 30px;
        font-size: 18px;
    }
    .barbershopsSer:hover{
        color: rgb(39,190,175);
        background-color: white;
        font-weight: bold;
        border:1px solid rgb(39,190,175);
    }
</style>

<section class="pl-4 pr-4 pt-4 pb-5">

    <h3 class="color-qrorpa pt-2"><strong>Select a Barbershop first</strong></h3>
    <hr>
    <div class="d-flex justify-content-between flex-wrap">
        @foreach(Barbershop::all()->sortByDesc('created_at') as $bShop)
            <a style="width:19.7%;" href="{{route('barbershops.servicesSelBar', ['barbershop' => $bShop->id])}}">
                <div class="barbershopsSer p-4 text-center">
                    {{$bShop->emri}}
                </div>
            </a>
        @endforeach
    </div>

</section>