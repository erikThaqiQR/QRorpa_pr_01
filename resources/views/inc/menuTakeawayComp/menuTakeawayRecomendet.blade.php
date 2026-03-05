<?php

use App\kategori;
    use App\RecomendetProd;
    use App\Produktet;
    use Carbon\Carbon;
    use App\Takeaway;
?>
<input type="hidden" id="thisRestaurant" value="{{$_GET['Res']}}">
<section class="rec" id="recProdListAllSection">
    <div class="container p-0">
    <div class="col-lg-6 col-sm-12 teksti">
        <p class="color-qrorpa pb-2" style="margin-bottom:-10px; font-size:20px; color:#6b6b6d;"><strong class="recommended-title">{{__('inc.recProducts')}}</strong></p>
            </div>
        <div class="swiper-container col-lg-6 p-0" style="border-top-left-radius:50px; border-bottom-left-radius:50px; overflow: hidden; ">
            <div class="swiper-wrapper" id="recProdList">
                @foreach(Takeaway::where([['toRes',$theRes],['recomendet','1'],['accessableByClients','1']])->orderBy('recomendetNr')->get() as $RePro)
                    @if(kategori::findOrFail($RePro->kategoria)->acsByClTA == 1)
                        <div class="swiper-slide recProElement" data-toggle="modal" data-target="#Prod{{$RePro->id}}" data-backdrop="static" data-keyboard="false">
                            <img style="width:120px; height:120px; border-radius:50%;" src="storage/TakeawayRecomendet/{{$RePro->recomendetPic}}"
                                alt="image">
                            <p style=" width:100%; font-size:13px;"><strong>
                                    {{$RePro->emri}}
                                </strong></p>
                            <p style="font-size:14px;"><span style="opacity:0.6; ">{{__('inc.currencyShow')}}</span>
                                @if(Carbon::now()->format('H:i') >= '20:00' || Carbon::now()->format('H:i') <= '03:00')
                                    @if($RePro->qmimi2 != 999999)
                                        {{sprintf('%01.2f', $RePro->qmimi2)}}
                                    @else
                                        {{sprintf('%01.2f', $RePro->qmimi)}} 
                                    @endif
                                @else
                                    {{sprintf('%01.2f', $RePro->qmimi)}} 
                                @endif
                            </p>
                        </div>
                    @endif
                @endforeach
            <script>
                // Enable pusher logging - don't include this in production
                // Pusher.logToConsole = true;
                var thisRestaurant = $('#thisRestaurant').val();
                var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {
                    cluster: 'eu'
                });
                var channel = pusher.subscribe('OrdersChanel');
                channel.bind('App\\Events\\newOrder', function(data) {
                    // alert(JSON.stringify(data));
                    // console.log(data);
                    var dataJ = JSON.stringify(data);
                    var dataJ2 = JSON.parse(dataJ);
                    // console.log(dataJ);
                    // console.log(dataJ2);
                    // alert(dataJ2.text);
                    if ("recUpdate" + thisRestaurant == dataJ2.text) {
                        // alert("got it");
                        location.reload(true);
                        // $('#recProdList').load('/ #recProdList', function() {  

                        // });
                    }


                });
            </script>

            </div>
        </div>
    </div>
</section>

<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        // spaceBetween: 10,
        stagePadding: 70,
        breakpoints: {

            // when window width is >= 320px
            320: {
            slidesPerView: 3,
            spaceBetween: 5,
            },
            // when window width is >= 480px
            480: {
            slidesPerView: 3,
            spaceBetween: 5,
            },
            // when window width is >= 640px
            640: {
            slidesPerView: 3,
            spaceBetween: 5,
            },
             // when window width is >= 768px
            768: {
            slidesPerView: 2,
            spaceBetween: 5,
            },
             // when window width is >= 1024px
            1024: {
            slidesPerView: 3,
            spaceBetween: 5,
            },
             // when window width is >= 1400px
            1400: {
            slidesPerView: 4,
            spaceBetween: 5,
            },
        }
    });
</script>