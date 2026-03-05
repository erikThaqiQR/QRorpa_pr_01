<?php
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Covid-19']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\StatusWorker;
    use App\User;
    use Carbon\Carbon;
    use App\Covid;
    use App\Restorant;

    $thisRestaurantId = Auth::user()->sFor;
?>
<style>
        .backQr{
            background-color:white;
            color:rgb(39,190,175);
            border:1px solid rgb(39,190,175);
            font-weight: bold;
            font-size: 18px;
        }
        .backQr:hover{
            background-color:rgb(39,190,175);
            color:white;
            font-weight: bold;
            font-size: 18px;
        }
</style>
<section class="pr-2 pl-2 pb-4">
   

    <div class="d-flex flex-wrap justify-content-between mt-3 mr-4 ml-4">
        <p style="width:50%; border-bottom:1px solid lightgray;"><strong>{{__('adminP.dateTime')}}</strong></p>
        <p style="width:25%; border-bottom:1px solid lightgray;"><strong>{{__('adminP.name')}}</strong></p>
        <p style="width:25%; border-bottom:1px solid lightgray;"><strong>{{__('adminP.numberOfPeople')}}</strong></p>

       @foreach(Covid::where('restaurant_id', '=', $thisRestaurantId)->get() as $covid)
            <div style="width:40%" >
                <p>{{$covid->created_at}}</p>
            </div>
            <div style="width:30%" class="text-center">
             {{$covid->name}}
            </div>
            <div style="width:30%" class="text-center">
              {{$covid->persons}}
            </div>
        @endforeach
    </div>


</section>