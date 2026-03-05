<?php

use App\kategori;
use App\RecomendetProd;
    use App\Produktet;
    use Carbon\Carbon;

    $theResIdReco = $_GET['Res'];
?>
<section class="rec" id="recProdListAllSection">
    <div class="container p-0">
        <div class="col-lg-12 col-sm-12 teksti">
            <p class="color-qrorpa pb-2" style="margin-bottom:-10px; margin-top:-35px; font-size:20px; color:#6b6b6d;"><strong class="recommended-title">{{__('inc.recProducts')}}</strong></p>
        </div>
        <div class="swiper-container col-lg-6 " style="border-top-left-radius:50px; border-bottom-left-radius:50px; overflow: hidden; ">
            <div class="swiper-wrapper" id="recProdList">
                @foreach(RecomendetProd::where('recomendet_prods.toRes', '=', $theResIdReco)
                ->join('produktets', 'recomendet_prods.produkti','=','produktets.id')
                ->select('recomendet_prods.*', 'produktets.emri as prodEmri', 'produktets.accessableByClients as prodACS', 'produktets.kategoria as catId')
                ->orderBy("recomendet_prods.pozita")->get() as $RePro)

                    @if(kategori::findOrFail($RePro->catId)->acsByClRes == 1 && $RePro->prodACS == 1)
                        <div class="swiper-slide recProElement" data-toggle="modal" data-target="#RecProd{{$RePro->id}}" data-backdrop="static" data-keyboard="false">
                        @if($RePro->picFrom == 1)
                            <img lazy="loading" style="width:120px; height:120px; border-radius:50%;" src="storage/RecUpload/{{ $RePro->foto }}" alt="image">
                        @else
                            <img lazy="loading" style="width:120px; height:120px; border-radius:50%;" src="storage/PicLibrary/{{ $RePro->foto }}" alt="image">
                        @endif
                            <p class="color-text" style="width:100%; font-size:13px;"><strong>
                                {{$RePro->prodEmri}}
                                </strong>
                            </p>
                            <p style="font-size:14px;"><span style="opacity:0.6; ">{{__('inc.currencyShow')}} </span>
                                @if(Carbon::now()->format('H:i') >= $theRestaurant->secondPriceTime || Carbon::now()->format('H:i') <= '03:00')
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