
        <?php

            use App\Produktet;
            use App\kategori;
            use Carbon\Carbon;

            if($theRestaurant->sortingType == 1){$showCats = Kategori::where('toRes', '=', $_GET["Res"])->where('acsByClRes','1')->orderByDesc('visits')->get();}
            else{ $showCats = Kategori::where('toRes', '=', $_GET["Res"])->where('acsByClRes','1')->orderBy('position')->get(); }
        ?>
        @foreach($showCats as $kat)

        <input type="hidden" value="{{$kat->emri}}" id="katsForSearch{{$kat->id}}">

        <div class="row allKatFoto" id="KategoriFoto{{$kat->id}}">
            <div class="col-lg-3 col-md-0 col-sm-0 leftSide-kat"></div>
            <div style="cursor: pointer; position:relative; object-fit: cover;" class="col-lg-12 col-md-12 col-sm-12 p-1" onclick="showProKat('{{$kat->id}}')">
            <img style="border-radius:30px; width:100%; height:120px;" src="storage/kategoriaUpload/{{$kat->foto}}" alt="" loading="lazy">

            @if(strlen($kat->emri) > 20)
                <div class="teksti d-flex" style="font-size:20px;  margin-bottom:13px;">          
                    <strong>{{$kat->emri}} </strong>
                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
                </div>
            @else
                <div class="teksti d-flex" >          
                    <strong>{{$kat->emri}} </strong>
                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
                </div>
            @endif
            <input type="hidden" value="0" id="state{{$kat->id}}">
         </div>
        <div class="container prodsFoto" id="prodsKatFoto{{$kat->id}}" style="display:none;">
            @php
                if($kat->sortingType == 1){
                    $prodsToShow = Produktet::where('toRes',$_GET["Res"])->where('kategoria',$kat->id)->where('accessableByClients','1')->orderByDesc('visits')->get();
                }else{
                    $prodsToShow = Produktet::where('toRes',$_GET["Res"])->where('kategoria',$kat->id)->where('accessableByClients','1')->orderBy('position')->get();
                }
            @endphp
            @foreach($prodsToShow as $ketoProd)
                <div class="row p-2" data-toggle="modal" onclick="oneVisitToProd('{{$ketoProd->id}}')" data-target="#Prod{{$ketoProd->id}}" data-backdrop="static" data-keyboard="false">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3 col-sm-0 col-md-0 leftSide-kat"></div>
                            <div class="col-lg-12 col-sm-12 col-md-12 product-section">
                                <div class="row">
                                    
                                    <di class="col-10">
                                        <h4 class="pull-right prod-name prodsFont color-text" style="font-weight:bold; font-size: 1.20rem ">
                                            {{$ketoProd->emri}} 
                                            @if($ketoProd->restrictPro != 0)
                                                @if($ketoProd->restrictPro == 16)
                                                <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px; height: 20px !important;" src="/storage/icons/R16.jpeg" alt="noImg"></span>
                                                @elseif($ketoProd->restrictPro == 18)
                                                <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px; height: 20px !important;" src="/storage/icons/R18.jpeg" alt="noImg"></span>
                                                @endif
                                            @endif
                                        </h4>
                                            <p style=" margin-top:-10px; font-size:13px;">{{substr($ketoProd->pershkrimi,0,35)}} 
                                                @if(strlen($ketoProd->pershkrimi)>35)
                                                    <span onclick="showTypeMenu('{{$ketoProd->id}}')" class="hover-pointer" style="font-size:16px;">{{__('inc.more')}}</span> 
                                                @endif 
                                            </p>
                                        <h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">
                                            {{__('inc.currencyShow')}}
                                            </span> 
                                            @if(Carbon::now()->format('H:i') >= $theRestaurant->secondPriceTime || Carbon::now()->format('H:i') <= '03:00')
                                                @if($ketoProd->qmimi2 != 999999)
                                                    {{sprintf('%01.2f', $ketoProd->qmimi2)}}
                                                @else
                                                    {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                @endif
                                            @else
                                                @if($ketoProd->qmimi2 != 999999)
                                                    {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                    <?php
                                                        $timesPr2 = strtotime($theRestaurant->secondPriceTime);
                                                        $timesPr21 = $timesPr2 - (20 * 60);
                                                        $timesPr22 = date("H:i", $timesPr21);
                                                    ?>
                                                    @if(Carbon::now()->format('H:i') > $timesPr22 && Carbon::now()->format('H:i') < $theRestaurant->secondPriceTime)
                                                        <span class="ml-4" style="font-size:14px;">Ab {{$theRestaurant->secondPriceTime}} uhr <span style="color:gray;">{{__('inc.currencyShow')}}</span>
                                                        {{sprintf('%01.2f', $ketoProd->qmimi2)}} </span>
                                                    @endif
                                                @else
                                                    {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                @endif
                                            @endif
                                            </h5>
                                    </di>
                                    <div class="col-2 add-plus-section">
                                        <button class="btn mt-2 noBorder" type="button" >
                                            <i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i>
                                        </button>
                                    </div>
                                
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-0 col-md-0  rightSide-kat"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-lg-3 col-md-0 col-sm-0 rightSide-kat"></div>
    </div>

    @endforeach