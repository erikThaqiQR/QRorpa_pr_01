<?php

use App\kategori;
use App\LlojetPro;
use App\Produktet;
use App\resPlates;
use App\Restorant;
use App\UserNotiDtlSet;
use App\userSoundsList;
use Illuminate\Support\Facades\Auth;

    $sndNotiActv = explode('--||--',Auth::user()->notifySet)[1];
    $usrNotiReg21 = UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',21]])->first();
    if($usrNotiReg21 != Null){ $usrNotiReg21Sound = userSoundsList::find($usrNotiReg21->setValue);
    }else{ $usrNotiReg21Sound = userSoundsList::find(1); }
    $catShowFirst = 0; 
    $taCats = array();
    if(Restorant::find(Auth::user()->sFor)->sortingType == 1){
        $catsCall = kategori::where('toRes', '=', Auth::user()->sFor)->orderByDesc('visits')->get();
    }else{
        $catsCall = kategori::where('toRes', '=', Auth::user()->sFor)->orderBy('position')->get();
    }
?>
<!-- <img src="storage/gifs/success04.gif" id="success03gif" style="position: fixed; top:100px; width:70px; height:70px; left:50%; margin-left:-70px; z-index:9999; display:none;" alt=""> -->
<script> 
    var openCatTaNewPro = 0;

    var last = "";
    var lastV = 0;
</script>
@if ($usrNotiReg21 != Null) 
    <script> var hasAddSoundSelected= true; </script>
@else
    <script> var hasAddSoundSelected= false; </script>
@endif


<style>
     @keyframes newOrCatAnim {
        0% { box-shadow: 0 0 -10px black; }
        40% { box-shadow: 0 0 20px black; }
        60% { box-shadow: 0 0 20px black; }
        100% { box-shadow: 0 0 -10px black; }
    }
    .newProdCatSelected { animation: newOrCatAnim 800ms infinite; }
</style>


















<input type="hidden" id="newTabOrderModalActiveTableNr" value="0">
<input type="hidden" id="newTabOrderModalTableHasNewOrder" value="0">

<!-- new tab order Modal -->
<div class="modal" id="newTabOrderModal" aria-hidden="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding:0px;">
    <div class="modal-dialog modal-xl" role="document" style="margin-top: 5px;">
        <div class="modal-content">
            <div class="modal-body">

                <div class="pt-2 pb-5 pr-1 pl-1">
                    <h3 class="pl-2" style="color:rgb(39,190,175);">
                        <strong><span style="font-size:1.8rem;">Tisch: <span id="newTabOrderModalTableNrShow">---</span></span> </strong>
                        <button type="button" class="close shadow-none" aria-label="Close" onclick="closeNewTabOrderModal()">
                            <span aria-hidden="false">X</span>
                        </button>
                    </h3>
                    <div id="addOrForTAModalBody2" class="d-flex justify-content-between">
                        <div id="addOrForTAModalBody2Left" style="width:100%">

                            <div class="swiper-container p-0" style="width:100%; border:1px solid rgb(39,190,175); border-radius:6px; overflow: hidden;">
                                <div id="addOrForTAModalBody2LeftCats" class="swiper-wrapper p-2"> 
                                    @foreach($catsCall  as $kat)
                                        <img class="mr-1 swiper-slide {{ $catShowFirst == 0 ? 'newProdCatSelected' : '' }}" id="taNewProdCatImg{{$kat->id}}"
                                        style="border-radius:5px; width:64px; height:40px;" src="storage/kategoriaUpload/{{$kat->foto}}"alt=""
                                        onclick="showCatNewTabOrderModal('{{$kat->id}}')">
                                        <?php 
                                            if($catShowFirst == 0){ $catShowFirst = $kat->id; }
                                            if(!in_array($kat->id,$taCats)){ array_push($taCats,$kat->id); }    
                                        ?>
                                        <script>
                                            if(openCatTaNewPro == 0){ openCatTaNewPro = '{{$kat->id}}'; }
                                        </script>
                                    @endforeach

                                    <img class="mr-1 swiper-slide " id="taNewProdCatImg000"
                                    style="border-radius:5px; width:64px; height:40px;" src="storage/kategoriaUpload/whiteCat.JPG" alt="">
                                    <img class="mr-1 swiper-slide " id="taNewProdCatImg000"
                                    style="border-radius:5px; width:64px; height:40px;" src="storage/kategoriaUpload/whiteCat.JPG" alt="">
                                </div>
                            </div>
                            @foreach ($taCats as $taCatsOneId)
                                @if ($taCatsOneId == $catShowFirst)
                                <div class="flex-wrap justify-content-start" style="width:100%; max-height:534px; overflow-y:scroll; display:flex;" id="taNewProdCat{{$taCatsOneId}}Prods">
                                @else
                                <div class="flex-wrap justify-content-start" style="width:100%; max-height:534px; overflow-y:scroll; display:none;" id="taNewProdCat{{$taCatsOneId}}Prods">
                                @endif
                                    <p style="width:100%; color:rgb(39,190,175); font-size:1.6rem;" class="mt-1 mb-1">
                                        <strong>{{Kategori::find($taCatsOneId)->emri}}</strong>
                                    </p>
                                    <?php
                                       if (Auth::user()->sFor >= 43){
                                            $theProdsArr = Produktet::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$taCatsOneId)->orderBy('created_at')->get();
                                       }else{
                                            $theProdsArr = Produktet::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$taCatsOneId)->orderByDesc('created_at')->get();
                                       }
                                    ?>
                                    @foreach($theProdsArr as $ketoProd) 
                                        <div class="d-flex justify-content-between" style="width:24.4%; margin: 4px 0.05% 4px 0.05%; border:1px solid rgb(39,190,175); border-radius:0.25rem;">
                                            <div style="width:80%;" class="p-1" data-toggle="modal" data-target="#ProdNewDtl" onclick="prodNewDtlFetchDsp('{{$ketoProd->id}}')">
                                                <p style="margin:0px; padding:0px; color:rgb(72,81,87); overflow:hidden; white-space: nowrap;">
                                                    <strong>{{$ketoProd->emri}}</strong>
                                                </p>
                                                <p style="margin:0px; padding:0px; color:rgb(72,81,87); overflow:hidden; white-space: nowrap;">
                                                    <strong>CHF {{number_format($ketoProd->qmimi,2,'.','')}}</strong>
                                                </p>
                                            </div>

                                            @if($ketoProd->type == NULL)
                                            <button onclick="addToCartExpress('{{$ketoProd->id}}','{{$sndNotiActv}}')" id="addToCartExpressBtn{{$ketoProd->id}}" class="btn shadow-none" style="width:20%; height:100%; margin:0px; padding:1px;">
                                            @else
                                            <button data-toggle="modal" data-target="#openTypeSelect{{$ketoProd->id}}" class="btn shadow-none" id="addToCartExpressBtn{{$ketoProd->id}}" style="width:20%; height:100%; margin:0px; padding:1px;">
                                            @endif
                                                <i class="fas fa-2x fa-cart-plus" style="color: green;"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--openTypeSelect Modal EXPRESS with type-->
@foreach(Produktet::where([['toRes', Auth::user()->sFor],['type','!=',NULL]])->get() as $ketoProd)
    <div class="modal" id="openTypeSelect{{$ketoProd->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top: 7%; z-index:9999;" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    @foreach (explode('--0--',$ketoProd->type) as $tPrTypeOne)
                        @if($tPrTypeOne != '')
                            <?php 
                                $tPrTypeOne2D = explode('||',$tPrTypeOne); 
                                $tPrTheT = LlojetPro::find($tPrTypeOne2D[0]);
                                if($tPrTheT != NULL){ $newPrice009 = sprintf("%01.2f", $ketoProd->qmimi*$tPrTheT->vlera); }     
                            ?>
                            @if($tPrTheT != NULL)
                            <button style="background-color:rgb(39,190,175); color:white; width:100%; margin:0px; font-size: 1.2rem"
                                class="btn mb-2 d-flex" onclick="addToCartExpressWithT('{{$ketoProd->id}}','{{$tPrTypeOne2D[0]}}','{{$newPrice009}}','{{$sndNotiActv}}')">
                                <span style="width:49%;" class="text-center"><strong>{{$tPrTheT->emri}}</strong></span>
                                <span style="width:49%;" class="text-center"><strong>{{number_format($newPrice009,2,'.','')}} <sup>CHF</sup></strong></span>
                            </button>
                            @endif
                        @endif
                    @endforeach
                    <button class="btn btn-outline-danger mt-3" style="width:100%; margin:0px;" onclick="closeTypeSelect('{{$ketoProd->id}}')">
                        <strong>Absagen</strong>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach


<!-- register prod DETAILED to the order -->

<div class="modal" id="ProdNewDtl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius:30px;">
            <div class="modal-body d-flex flex-wrap justify-cntent-between" id="ProdNewDtlBody">

                <h4 style="width:83.33%" class="modal-title"><span id="PNDtlProdName">---</span> <span style="color:lightgray;"><span id="PNDtlCategoryName">---</span></span></h4>
                <button type="button" style="width:16.66%" class="btn shadow-none">
                    <i onclick="prodModalCancelMenu()" style="width:6px;" class="far fa-2x fa-times-circle"></i>
                </button>
                <p style="width:100%" id="PNDtlProdDesc">---</p>

                <div class="d-flex flex-wrap justify-cntent-between" style="width:100%; border-bottom:1px solid lightgray;">
                    <div class="text-right mt-3" style="width:16.66%"><span class="opacity-65">{{__('adminP.currencyShow')}}</span></div>
                    <div class="text-left" style="width:33.33%">
                        <input class="form-control color-qrorpa shadow-none" style="border:none; font-size:20px;" onkeyup="setNewPriceProd(this.value)"
                            id="TotPrice" type="number" min="0" step="0.01" value="0.00">
                    </div>
                    <div class="text-left mt-2" style="width:16.66%; font-size:9px; color:rgb(39,190,175); padding:0px 0px 0px 7px" id="changePriceInfo">
                         < Sie können den Preis ändern
                    </div>
                    <div class="text-right d-flex pl-4" style="width:33.33%;">
                        <span id="minusSasiaPerProd" style="font-size:30px; display:none; padding:0;" class="pr-3 plusForOrder btn" onclick="removeOneToSasiaPro()" >-</span>
                        <span id="placeholderSasiaPerProd" style="font-size:30px; color:white; padding:0;" class="pr-3 plusForOrder btn">-</span>
                        <input type="number" min="1" step="1" value="1" class="text-center" id="sasiaPerProd"style="border:none;  width:40px; font-size:28px; height:fit-content;" disabled>
                        <span style="font-size:30px; padding:0;" class="pl-3 plusForOrder btn" onclick="addOneToSasiaPro()">+</span>
                    </div>

                    <div class="alert alert-danger text-center mt-1" style="width:100%; display:none;" id="setNewPriceProdError01">
                        Dieser Betrag ist nicht gültig!
                    </div>
                </div>

                <!-- Types -->
                <input type="hidden" value="0" id="hasTypeThisPro">
                <div class="text-center" id="ProdNewDtlTypesDiv" style="width:100%;">
                  
                </div>

                <!-- Extras --> 
                <div class="text-center" id="ProdNewDtlExtrasDiv" style="width:100%;">

                </div>



                <div style="width: 100%;" class="form-group mt-2 mb-2">
                    <textarea placeholder="{{__('adminP.comment')}}..." name="koment" style="font-size:16px;"
				    id="komentMenuAjax" class="form-control shadow-none" rows="3"></textarea>
                </div>
                
                <div class="d-flex flex-wrap justify-content-between" style="width: 100%; margin-bottom:6rem;" id="allPlates">
                    <h4 style="width:100%;" id="ProdNewDtlDefPlate"><strong>Standardgericht: <span id="ProdNewDtlDefPlateTitle">---</span></strong></h4>
                    @foreach (resPlates::where('toRes',Auth::user()->sFor)->get() as $plOne)
                        <button style="width:49%; margin:0px;" class="btn btn-outline-dark shadow-none mb-1 setNewPlateBtnAll" 
                        id="setNewPlateBtn{{$plOne->id}}" onclick="setNewPlate('{{$plOne->id}}','2')">
                            <strong><span class="mr-1">P.{{$plOne->desc2C}}</span> {{$plOne->nameTitle}}</strong>
                        </button>
                    @endforeach
                </div>

                <!-- if($hasOneTVal == 0){ echo ' data-dismiss="modal" '  };  -->    

                <button type="button" class="btn btn-block" style="background-color:rgb(39,190,175); color:white; border-radius:30px; position:fixed; height:70px;
                width:50%; bottom:50px; right:25%; left:25%; font-size:22px;" id="sendOrderBtn"
                onclick="saveNewOrder('{{$sndNotiActv}}')">{{__("adminP.addToOrder")}}</button>

            </div> <!-- modal-body End -->
            <div id="ProdNewDtlFooterData">
                <input type="hidden" name="qmimiBaze" id="ProdAddQmimiBaze" value="0.00">
                <input type="hidden" name="id" id="ProdNewDtlProdId" value="0">
                <input type="hidden" name="emri" id="ProdAddEmri" value="0">
                <input type="hidden" name="qmimi" id="ProdAddQmimi" value="0">
                <input type="hidden" name="pershkrimi" id="ProdAddPershk" value="0">
                <input type="hidden" name="extra" id="ProdAddExtra" value="">
                <input type="hidden" name="llojet" id="ProdAddLlojet" value="">
                <input type="hidden" name="kategoria" id="ProdAddKategoria" value="0">
                <input type="hidden" name="sasia" id="sasiaProd" value="1">
                <input type="hidden" name="plateFor" id="plateFor" value="0">
                <input type="hidden" class="resVal" id="res" name="res" value="{{Auth::user()->sFor}}">
                <input type="hidden" class="tVal" id="t" name="t" value="0">

                <input type="hidden" id="showOtherTypes" value="0">
                <input type="hidden" id="showOtherExtras" value="0">
            </div>
        </div>
    </div>
</div>

@include('adminPanel.tablePage.newOrderRegElScript')