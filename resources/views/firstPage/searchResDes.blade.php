<?php
    use App\DeliveryPLZ;
    use App\RestaurantRating;
    use App\TableQrcode;

    use App\DeliverySchedule;
    use App\TakeawaySchedule;
    use App\RestaurantWH;
?>

 <!-- ***** Header Text Start ***** -->
 <div style="padding-top:60px; margin-top:20px; border:none; z-index:99;" class="d-flex flex-wrap justify-content-between" id="AllSerDivDesktop">

<p style="width:30%"></p>
<div style="width:40%" class="pt-3 mt-2">
                <form class="example" id="searchForm" action="{{ route('searchRestaurants') }}" method="GET"  autocomplete="off" >
                      <!--end of col-->
                
                          <input id="emri" name="emri" type="text" placeholder="Adresse, z.B. Müllheimerstrasse 100" value="{{$searchWor}}"
                          style="border-top-left-radius:20px; border-bottom-left-radius:20px;">                              
                              <button type="submit" style="border-top-right-radius:20px; border-bottom-right-radius:20px;"><i class="fa fa-search"></i></button>
                              <!-- <div id="restaurantsList" type="submit"></div>-->
                     
                    {{ csrf_field() }}
                      <!--end of col-->
                </form>
</div>
<p style="width:30%"></p>


<p style="width:15%;"></p>
<div class="swiper-container2 pt-1 pb-1 mt-1 mb-1" style="width:60%; overflow: hidden; ">
    <div class="swiper-wrapper text-center" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
        <div style="width:19.5%; margin-left:-20px; font-size:28px; color:rgb(72,81,87);"
            class="swiper-slide  restorantFilterElement p-3" data-backdrop="static" data-keyboard="false">
            <form class="example" action="{{ route('searchRestaurantsFilter') }}" method="GET"  autocomplete="off" >
                <input type="hidden" name="filter" value="delivery">
                <input type="hidden" name="wor" value="{{$searchWor}}">
                {{ csrf_field() }}
                <!--end of col-->
                @if(isset($_GET['filter']) && $_GET['filter'] == 'delivery')
                    <button class="btn-default" style="background-color:white; width:100%; border:none;"><p style="color:rgb(39,190,175);"><strong>Delivery</strong></p> </button>
                @else
                    <button class="btn-default" style="background-color:white; width:100%; border:none;"><p><strong>Delivery</strong></p> </button>
                @endif
            </form>
        </div>
        <div style="width:19.5%; margin-left:-20px; font-size:28px; color:rgb(72,81,87);"
            class="swiper-slide  restorantFilterElement p-3" data-backdrop="static" data-keyboard="false">
            <form class="example" action="{{ route('searchRestaurantsFilter') }}" method="GET"  autocomplete="off" >
                <input type="hidden" name="filter" value="takeaway">
                <input type="hidden" name="wor" value="{{$searchWor}}">
                {{ csrf_field() }}
                <!--end of col-->
                @if(isset($_GET['filter']) && $_GET['filter'] == 'takeaway')
                    <button class="btn-default" style="background-color:white; width:100%; border:none;"><p style="color:rgb(39,190,175);"><strong>Takeaway</strong></p> </button>
                @else
                    <button class="btn-default" style="background-color:white; width:100%; border:none;"><p><strong>Takeaway</strong></p> </button>
                @endif
            </form>
        </div>
        <div style="width:19.5%; margin-left:-20px; font-size:28px; color:rgb(72,81,87);" class="swiper-slide  restorantFilterElement p-3" data-backdrop="static" data-keyboard="false">
            <p><strong>Hotel</strong></p>
        </div>
        <div style="width:19.5%; margin-left:-20px; font-size:28px; color:rgb(72,81,87);" class="swiper-slide  restorantFilterElement p-3" data-backdrop="static" data-keyboard="false">
            <p><strong>Am Tisch</strong></p>
        </div>
        <div style="width:19.5%; margin-left:20px;  font-size:28px; color:rgb(72,81,87);"
            class="swiper-slide  restorantFilterElement p-3" data-backdrop="static" data-keyboard="false">
            <form class="example" action="{{ route('searchRestaurantsFilter') }}" method="GET"  autocomplete="off" >
                <input type="hidden" name="filter" value="tableRez">
                <input type="hidden" name="wor" value="{{$searchWor}}">
                {{ csrf_field() }}
                <!--end of col-->
                @if(isset($_GET['filter']) && $_GET['filter'] == 'tableRez')
                    <button class="btn-default" style="background-color:white; width:100%; border:none;"><p style="color:rgb(39,190,175);"><strong>Tischreservierung</strong></p> </button>
                @else
                    <button class="btn-default" style="background-color:white; width:100%; border:none;"><p><strong>Tischreservierung</strong></p> </button>
                @endif
            </form>
        </div>
    </div>
</div>
<p style="width:15%;"></p>

<script>
       
        var swiper = new Swiper('.swiper-container2', {
            slidesPerView: 3,
            spaceBetween: 10,
            direction: 'horizontal',
            nested: true,
            breakpoints: {
                 // when window width is >= 120px
                120: {
                slidesPerView: 2,
                spaceBetween: 10
                },
                // when window width is >= 480px
                480: {
                slidesPerView: 3,
                spaceBetween: 10
                },
                // when window width is >= 640px
                640: {
                slidesPerView: 4,
                spaceBetween: 10
                },
                900: {
                slidesPerView: 5,
                spaceBetween: 10
                },
                1200: {
                slidesPerView: 6,
                spaceBetween: 10
                }
            }
        });
        swiper.init();
</script>













   
                    @foreach ($restorants->sortByDesc('payAmSer') as $res)
                            <p style="width:25%;"></p>
                            <div 
                             style="text-decoration: none; color: #1c1c1c; width:50%; border:1px solid rgb(39,190,175,0.3); border-radius:5px; "
                             id="resBoxO{{$res->id}}" class="resBoxAll p-1 d-flex justify-content-between mb-2">
                                      
                                <div class="text-center" style="width: 20%;  border-right:1px solid rgb(39,190,175,0.3); z-index:2;">
                                    <img src="storage/ResProfilePic/{{$res->profilePic}}" style="width:130px; height:130px; border-radius:50%;" alt="">
                                </div>
                                
                                <div class="restaurant-data pl-2" style="z-index:2; width:50%;">
                                    <h4 class="color-text">{{ $res->emri }}</h4>
                                    <p class="color-text">{{ $res->adresa }}</p>

                                                <?php
                                                  $thisRestaurantRatings = RestaurantRating::where([['restaurant_id', '=', $res->id], ['verified', '=', '1']])->orderBy('updated_at','DESC')->get();
                                                  $thisRestaurantRatingsSum = RestaurantRating::where([['restaurant_id', '=', $res->id], ['verified', '=', '1']])->sum('stars');
                                                  $thisRestaurantRaringAverage = RestaurantRating::where([['restaurant_id', '=', $res->id], ['verified', '=', '1']])->avg('stars');
                                                ?>
                                                    <a style="cursor: pointer;">
                                                        @if($thisRestaurantRatings != null)
                                                    @if(number_format($thisRestaurantRaringAverage,1) < 0.5)
                                                    <div class="star-ratings-css yjetMenuRat" title=".00"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) <= 0.5)
                                                    <div class="star-ratings-css yjetMenuRat" title=".50"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) == 1)
                                                    <div class="star-ratings-css yjetMenuRat" title=".100"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) <= 1.5)
                                                    <div class="star-ratings-css yjetMenuRat" title=".150"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) < 2)
                                                    <div class="star-ratings-css yjetMenuRat" title=".200"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) == 2)
                                                    <div class="star-ratings-css yjetMenuRat" title=".200"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) <= 2.5 )
                                                    <div class="star-ratings-css yjetMenuRat" title=".250"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) < 3 )
                                                    <div class="star-ratings-css yjetMenuRat" title=".300"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) == 3)
                                                    <div class="star-ratings-css yjetMenuRat" title=".300"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) <= 3.5)
                                                    <div class="star-ratings-css yjetMenuRat" title=".350"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) < 4)
                                                    <div class="star-ratings-css yjetMenuRat" title=".400"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) == 4)
                                                    <div class="star-ratings-css yjetMenuRat" title=".400"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) <= 4.5)
                                                    <div class="star-ratings-css yjetMenuRat" title=".450"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) < 5)
                                                    <div class="star-ratings-css yjetMenuRat" title=".500"></div>
                                                    @elseif(number_format($thisRestaurantRaringAverage,1) == 5)
                                                    <div class="star-ratings-css yjetMenuRat" title=".500"></div>
                                                    @endif
                                                    <span style="font-size:12px;" id="bewer">( {{count($thisRestaurantRatings)}})</span>
                                                    @else
                                                    <div class="star-ratings-css" title=".00"></div>
                                                    <span id="bewer">( 0 Bewertungen)</span>
                                                    @endif
                                                    </a>
                                    <br>

                                   
                                    <span style="font-size:14px; "><i class="fa fa-clock-o" aria-hidden="true"></i> 
                                    
                                    <?php
                                       $oHourRes = "00:00";
                                       $oHourTak = "00:00";
                                       $oHourDel = "00:00";

                                        if(DeliverySchedule::where('toRes',$res->id)->first() != NULL){
                                            $dWH = DeliverySchedule::where('toRes',$res->id)->first();
                                            if($weekDayNr==1){if($dWH->day11S != '00:00'){$oHourDel=$dWH->day11S;}else if($dWH->day21S != '00:00'){$oHourDel=$dWH->day11S;}}
                                            else if($weekDayNr==2){if($dWH->day12S != '00:00'){$oHourDel=$dWH->day12S;}else if($dWH->day22S != '00:00'){$oHourDel=$dWH->day12S;}}
                                            else if($weekDayNr==3){if($dWH->day13S != '00:00'){$oHourDel=$dWH->day13S;}else if($dWH->day23S != '00:00'){$oHourDel=$dWH->day13S;}}
                                            else if($weekDayNr==4){if($dWH->day14S != '00:00'){$oHourDel=$dWH->day14S;}else if($dWH->day24S != '00:00'){$oHourDel=$dWH->day14S;}}
                                            else if($weekDayNr==5){if($dWH->day15S != '00:00'){$oHourDel=$dWH->day15S;}else if($dWH->day25S != '00:00'){$oHourDel=$dWH->day15S;}}
                                            else if($weekDayNr==6){if($dWH->day16S != '00:00'){$oHourDel=$dWH->day16S;}else if($dWH->day26S != '00:00'){$oHourDel=$dWH->day16S;}}
                                            else if($weekDayNr==0){if($dWH->day10S != '00:00'){$oHourDel=$dWH->day10S;}else if($dWH->day20S != '00:00'){$oHourDel=$dWH->day10S;}}
                                        }
                                        if(TakeawaySchedule::where('toRes',$res->id)->first() != NULL){
                                            $dWH = TakeawaySchedule::where('toRes',$res->id)->first();
                                            if($weekDayNr==1){if($dWH->day11S != '00:00'){$oHourTak=$dWH->day11S;}else if($dWH->day21S != '00:00'){$oHourTak=$dWH->day11S;}}
                                            else if($weekDayNr==2){if($dWH->day12S != '00:00'){$oHourTak=$dWH->day12S;}else if($dWH->day22S != '00:00'){$oHourTak=$dWH->day12S;}}
                                            else if($weekDayNr==3){if($dWH->day13S != '00:00'){$oHourTak=$dWH->day13S;}else if($dWH->day23S != '00:00'){$oHourTak=$dWH->day13S;}}
                                            else if($weekDayNr==4){if($dWH->day14S != '00:00'){$oHourTak=$dWH->day14S;}else if($dWH->day24S != '00:00'){$oHourTak=$dWH->day14S;}}
                                            else if($weekDayNr==5){if($dWH->day15S != '00:00'){$oHourTak=$dWH->day15S;}else if($dWH->day25S != '00:00'){$oHourTak=$dWH->day15S;}}
                                            else if($weekDayNr==6){if($dWH->day16S != '00:00'){$oHourTak=$dWH->day16S;}else if($dWH->day26S != '00:00'){$oHourTak=$dWH->day16S;}}
                                            else if($weekDayNr==0){if($dWH->day10S != '00:00'){$oHourTak=$dWH->day10S;}else if($dWH->day20S != '00:00'){$oHourTak=$dWH->day10S;}}
                                        }
                                        if(RestaurantWH::where('toRes',$res->id)->first() != NULL){
                                            $dWH = RestaurantWH::where('toRes',$res->id)->first();
                                            if($weekDayNr==1){if($dWH->D1Starts1 != 'none'){$oHourRes=$dWH->D1Starts1;}else if($dWH->D1Starts2 != 'none'){$oHourRes=$dWH->D1Starts2;}}
                                            else if($weekDayNr==2){if($dWH->D2Starts1 != 'none'){$oHourRes=$dWH->D2Starts1;}else if($dWH->D2Starts2 != 'none'){$oHourRes=$dWH->D2Starts2;}}
                                            else if($weekDayNr==3){if($dWH->D3Starts1 != 'none'){$oHourRes=$dWH->D3Starts1;}else if($dWH->D3Starts2 != 'none'){$oHourRes=$dWH->D3Starts2;}}
                                            else if($weekDayNr==4){if($dWH->D4Starts1 != 'none'){$oHourRes=$dWH->D4Starts1;}else if($dWH->D4Starts2 != 'none'){$oHourRes=$dWH->D4Starts2;}}
                                            else if($weekDayNr==5){if($dWH->D5Starts1 != 'none'){$oHourRes=$dWH->D5Starts1;}else if($dWH->D5Starts2 != 'none'){$oHourRes=$dWH->D5Starts2;}}
                                            else if($weekDayNr==6){if($dWH->D6Starts1 != 'none'){$oHourRes=$dWH->D6Starts1;}else if($dWH->D6Starts2 != 'none'){$oHourRes=$dWH->D6Starts2;}}
                                            else if($weekDayNr==0){if($dWH->D7Starts1 != 'none'){$oHourRes=$dWH->D7Starts1;}else if($dWH->D7Starts2 != 'none'){$oHourRes=$dWH->D7Starts2;}}
                                        }

                                        
                                    ?>
                                    
                                    @if($oHourRes != '00:00')
                                        {{$oHourRes}}
                                    @else   
                                        Ruhetag
                                    @endif
                                    
                                        @if($res->resType == 3 || $res->resType == 7 || $res->resType == 8 || $res->resType == 9)
                                            <i class="ml-3 fa fa-motorcycle" aria-hidden="true"></i> Gratis 
                                            <?php $resPlzAllT = DeliveryPLZ::where([['toRes',$res->id],['plz',$searchWord]])->first(); ?>
                                            @if($resPlzAllT != NULL)
                                                <span class="ml-4"> {{$resPlzAllT->takesTime}} min </span>
                                            @endif
                                        @endif
                                    </span>           
                                </div>






                                <div style="z-index:2; width:25%;" class="pt-1 pb-1" >
                
                                    @if($res->resType == 2 || $res->resType == 6 || $res->resType == 7 || $res->resType == 9)
                                        <button onclick="location.href = '/?Res={{$res->id}}&t=500';" class="btn btn-default btn-block typeBtnRes"><strong>Takeaway</strong>
                                        @if($oHourTak != '00:00')
                                            <i class="ml-2 fa fa-clock-o" aria-hidden="true"></i>  {{$oHourTak}}
                                        @else
                                            <i class="ml-2 fa fa-clock-o" aria-hidden="true"></i>  Ruhetag
                                        @endif
                                        </button>
                                        <input type="hidden" id="hasTakeaway{{$res->id}}" value="1">
                                    @else
                                        <button class="btn btn-default btn-block typeBtnResNot" disabled><strong>Takeaway</strong></button>
                                        <input type="hidden" id="hasTakeaway{{$res->id}}" value="0"> 
                                    @endif

                                    @if($res->resType == 3 || $res->resType == 7 || $res->resType == 8 || $res->resType == 9)
                                        <button  onclick="location.href = '/Delivery/?Res={{$res->id}}';" class="btn btn-default btn-block typeBtnRes" ><strong>Delivery</strong>
                                        @if($oHourDel != '00:00' )
                                            <i class="ml-2 fa fa-clock-o" aria-hidden="true"></i>  {{$oHourDel}}
                                        @else
                                            <i class="ml-2 fa fa-clock-o" aria-hidden="true"></i>  Ruhetag
                                        @endif
                                        </button>
                                        <input type="hidden" id="hasDelivery{{$res->id}}" value="1"> 
                                    @else
                                        <button class="btn btn-default btn-block typeBtnResNot" disabled><strong>Delivery</strong></button>
                                        <input type="hidden" id="hasDelivery{{$res->id}}" value="0">  
                                    @endif

                                    @if($res->resType == 5 || $res->resType == 6 || $res->resType == 8 || $res->resType == 9)
                                        <button onclick="location.href = '/tableRezIndex?Res={{$res->id}}';" class="btn btn-default btn-block typeBtnRes"><strong>Tischreservierung</strong>
                                            <i class="ml-2 fa fa-clock-o" aria-hidden="true"></i>  24 h
                                        </button>
                                        <input type="hidden" id="hasTableRez{{$res->id}}" value="1"> 
                                    @else
                                        <button class="btn btn-default btn-block typeBtnResNot" disabled><strong>Tischreservierung</strong></button>
                                        <input type="hidden" id="hasTableRez{{$res->id}}" value="0"> 
                                    @endif
                                   
                                </div>




                                    <?php 
                                        $allTabFRDes = TableQrcode::where([['Restaurant',$res->id]])->get()->count(); 
                                        $freeTabFRDes = TableQrcode::where([['Restaurant',$res->id],['kaTab',0]])->get()->count(); 
                                    ?>

                                    <!-- The Modal -->
                                    <div class="modal" id="modalTableStatDes{{$res->id}}">
                                        <div class="modal-dialog" >
                                            <div class="modal-content" style="border-radius:30px;">

                                                <!-- Modal Header -->
                                                <div class="modal-header" style="background-color:rgb(39,190,175);">
                                                    <h4 style="color:white; margin-bottom:-15px;" class="modal-title">{{ $res->emri }} <br> 
                                                        <p style="font-size:15px; margin-top:-10px; color:white;">{{ $res->adresa }}</p> 
                                                    </h4>
                                                    <button style="color:white;" type="button" class="close color-qrorpa" data-dismiss="modal">X</button>
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    <p class="color-qrorpa " style="font-size:18px; margin-bottom:4px;"><strong>Freie Tische</strong></p>
                                                    <p class="color-qrorpa " style="font-size:26px;"><strong>{{$freeTabFRDes}} von {{$allTabFRDes}}</strong></p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>



                                    <div style="width:5%; height:40px; border-left:1px solid rgb(39,190,175,0.35);  border-bottom:1px solid rgb(39,190,175,0.35);"
                                        class="text-center pt-1" data-toggle="modal" data-target="#modalTableStatDes{{$res->id}}">
                                    
                                        @if($freeTabFRDes == 0)
                                            <img  style="width:30px; height:30px;" src="../storage/images/tableSt_red.PNG" alt="">
                                        @elseif($freeTabFRDes <= 5)
                                            <img  style="width:30px; height:30px;" src="../storage/images/tableSt_yellow.PNG" alt="">
                                        @else
                                            <img  style="width:30px; height:30px;" src="../storage/images/tableSt_qrorpa.PNG" alt="">
                                        @endif
                                    </div>

                                 
                            </div>
                            <p style="width:25%;"></p>

                           
                    
                    @endforeach
</div>



