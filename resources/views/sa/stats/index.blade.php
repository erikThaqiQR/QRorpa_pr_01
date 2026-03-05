<?php
    use App\kategori;
    use App\Produktet;
    use App\Restorant;
    use App\User;
    use App\SAStatistics;

     $saStat = SAStatistics::find(1);

    $kategoriClicks = kategori::all()->sum('visits');
    $produktetClicks = Produktet::all()->sum('visits');

    $cashPayClicks = Restorant::all()->sum('cashPayClicks');
    $cashPayOrderd = Restorant::all()->sum('cashPayOrders');

    $usersCount = User::all()->count();
?>


<style>
    .statIndexBox{
        width:19.7%;
        padding: 15px;
        background-color:rgb(39,190,175);
        color:white;
        border-radius: 25px;
        font-weight: bold;
        font-size: 21px;
    }
    .restorantBOX{
        opacity:0.85;
    }
    .restorantBOX:hover{
        opacity:1;
        cursor: pointer;
    }
    .anchorNoLine:hover{
        text-decoration: none;
    }

    .menuOptionsBox{
        padding: 5px;
        background-color: rgb(39,190,175);
        color:white;
        border-radius: 10px;
        width:24.5%;
        font-weight: bold;
        font-size:18px;
        padding-top:12px;

    }

    .activeRes{
        border:9px solid green;
    }

</style>
<section class="pt-4 pb-4 pl-3 pr-3">

    <h3 class="p-2 color-qrorpa"><strong>Overall Statistics</strong></h3>
    <div class="d-flex justify-content-around">
        <div class="statIndexBox text-center">
            <p>Cash Pay Clicks : {{$cashPayClicks}}</p>
            <p style="margin-top:-20px;">Cash Pay Orders : {{$cashPayOrderd}}</p>
            @if($cashPayClicks != 0)
                <p style="margin-top:-20px;">Orders completed : {{number_format(($cashPayOrderd / $cashPayClicks) *100, 2, '.', '')}} %</p>
            @endif

            
        </div>
        <div class="statIndexBox text-center">
            <p>Category Clicks : {{$kategoriClicks}}</p>
            <p>Product Clicks : {{$produktetClicks}}</p>
        </div>
        <div class="statIndexBox text-center">
            <p>Users : {{$usersCount}}</p>
            <p style="margin-top:-20px;">Login Clicks : {{$saStat->EinloggenClicks}}</p>
            <p style="margin-top:-20px;">Register Clicks : {{$saStat->RegisterClicks}}</p>
        </div>
       
        <div class="statIndexBox text-center">
            <p>Banner Clicks : {{$saStat->BannerClick}}</p>
            <p>Banner Link Clicks : {{$saStat->BannerLinkClick}}</p>
        </div>
        <div class="statIndexBox text-center">

        </div>
    </div>



    <h3 class="p-2 color-qrorpa"><strong>Menu Options Clicks</strong></h3>
    <div class="d-flex justify-content-around flex-wrap">
        <div class="menuOptionsBox text-center">
            <p>SA Page : {{$saStat->SAPageOpen}}</p>
        </div>
        <div class="menuOptionsBox text-center">
            <p>Admin Page : {{$saStat->APageOpen}}</p>
        </div>
        <div class="menuOptionsBox text-center">
            <p>Waiter Calls : {{$saStat->WaiterCallsOpen}}</p>
        </div>
        <div class="menuOptionsBox text-center">
            <p>Cart : {{$saStat->CartOpen}}</p>
        </div>
        <div class="menuOptionsBox text-center mt-3">
            <p>My Orders : {{$saStat->MyOrdersOpen}}</p>
        </div>
        <div class="menuOptionsBox text-center mt-3">
            <p>Track My order : {{$saStat->TrackOrderOpen}}</p>
        </div>
        <div class="menuOptionsBox text-center mt-3">
            <p>Covi-19 : {{$saStat->Covid19Open}}</p>
        </div>
        <div class="menuOptionsBox text-center mt-3">
            <p>Profile : {{$saStat->ProfileOpen}}</p>
        </div>
      
     
    </div>







    <hr>
    <h4 class="p-2 color-qrorpa"><strong>Restaurants</strong></h4>

    <div class="d-flex justify-content-between flex-wrap"> 
        @foreach(Restorant::all()->sortByDesc('openResTimes') as $rest)
            @if($rest->bPic != 'none')
                <div id="theActiveResBlockDiv{{$rest->id}}" class=" text-center p-4 mt-2 restorantBOX" style="width:24%; background-image: url('storage/ResBackgroundPic/{{$rest->bPic}}');
                    background-size: cover; background-position: inherit; border-radius:25px;" >
                    <a href="/SAStatisticsRes?Res={{$rest->id}}" class="anchorNoLine">
                        <p style="color:white; font-size:24px;"> <strong>{{$rest->emri}}</strong>
                            <br>
                            <span class="color-white">{{$rest->openResTimes}} <i class="far fa-eye"></i></span>
                        </p>
                        <p style="color:white; font-weight:bold; font-size:24px; margin-top:-15px;" id="theActiveResBlock{{$rest->id}}">Not Active</p>
            @else
                <div id="theActiveResBlockDiv{{$rest->id}}" class="text-center p-4 mt-2 restorantBOX" style="width:24%; border:1px solid gray; border-radius:25px;">
                    <a href="/SAStatisticsRes?Res={{$rest->id}}" class="anchorNoLine">
                        <p style="color:black; font-size:24px;"> <strong>{{$rest->emri}}</strong>
                            <br>
                            <span class="color-qrorpa" >{{$rest->openResTimes}} <i class="far fa-eye"></i> </span>
                        </p>
                        <p style="color:rgb(39,190,175); font-weight:bold; font-size:24px; margin-top:-15px;" id="theActiveResBlock{{$rest->id}}">Not Active</p>
            @endif
                    </a>
                </div>
        @endforeach
    </div>
</section>