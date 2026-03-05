<?php   
    use App\Barbershop;
    use App\BarbershopCategory;
    use App\BarbershopType;
    use App\BarbershopExtra;
    use App\BarbershopService;

    $barbershopId = $_GET['barbershop'];
?>

<style>
    .functionBlock{
        background-color: rgb(39,190,175);
        color:white;
        font-size: 19px;
        border-radius: 30px;
        padding-top:40px ;
        padding-bottom:40px ;
    }
    .functionBlock:hover{
        background-color: white;
        color:rgb(39,190,175);
        border:1px solid rgb(39,190,175);
    }
</style>

<section class="pl-4 pr-4 pt-4 pb-5">
    <div class="d-flex justify-content-between">
        <a style="width:10%;" href="{{route('barbershops.servicesIndex', ['barbershop'])}}">
            <img class="pt-3" src="https://img.icons8.com/android/48/000000/back.png"/>
        </a>
        <h3 style="width:90%;" class="color-qrorpa pt-4"><strong>{{Barbershop::find($barbershopId)->emri}}</strong></h3>
    </div>

    <div class="d-flex justify-content-between flex-wrap mt-4">
    
            <a href="{{route('barbershops.servicesCategory', ['barbershop' => $barbershopId])}}" style="width:48%; font-size:29px;" class="text-center functionBlock">
                Category <span class="ml-4">( {{BarbershopCategory::where('toBar',$barbershopId)->get()->count()}} X )</span> 
            </a>
            <a href="{{route('barbershops.servicesType', ['barbershop' => $barbershopId])}}" style="width:48%; font-size:29px;" class="text-center functionBlock">
                Type <span class="ml-4">( {{BarbershopType::where('toBar',$barbershopId)->get()->count()}} X )</span> 
            </a>
            <a href="{{route('barbershops.servicesExtra', ['barbershop' => $barbershopId])}}" style="width:48%; font-size:29px;" class="text-center functionBlock mt-2">
                Extra <span class="ml-4">( {{BarbershopExtra::where('toBar',$barbershopId)->get()->count()}} X )</span> 
            </a>
            <a href="{{route('barbershops.servicesService', ['barbershop' => $barbershopId])}}" style="width:48%; font-size:29px;" class="text-center functionBlock mt-2">
                Service <span class="ml-4">( {{BarbershopService::where('toBar',$barbershopId)->get()->count()}} X )</span>
            </a>

    </div>
    
   
</section>