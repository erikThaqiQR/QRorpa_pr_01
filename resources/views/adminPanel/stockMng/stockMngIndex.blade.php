<?php

use App\ekstra;
use App\kategori;
use App\stockmng;
use App\LlojetPro;
use App\Produktet;
use App\Restorant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

    $activeKats = array();
    foreach(stockmng::where([['toRes',Auth::user()->sFor],['elementType',1]])->get() as $catRegistered){
        array_push($activeKats,$catRegistered->elementId);
    }
    foreach(kategori::where('toRes',Auth::User()->sFor)->whereNotIn('id', $activeKats)->get() as $catOneFilter){
        $katIsRegAll = 1;
        foreach(Produktet::where([['toRes',Auth::user()->sFor],['kategoria',$catOneFilter->id]])->get() as $prodOneFilter){
            $stIns = stockmng::where([['toRes',Auth::user()->sFor],['elementType',2],['elementId',$prodOneFilter->id]])->first();
            if($stIns == Null){
                $katIsRegAll = 0;
                break;
            }
        }
        if($katIsRegAll == 1){
            foreach(ekstra::where([['toRes',Auth::user()->sFor],['toCat',$catOneFilter->id]])->get() as  $extOneFilter){
                $stIns = stockmng::where([['toRes',Auth::user()->sFor],['elementType',3],['elementId',$extOneFilter->id]])->first();
                if($stIns == Null){
                    $katIsRegAll = 0;
                    break;
                }
            }
        }
        if($katIsRegAll == 1){
            array_push($activeKats,$catOneFilter->id);
        }
    }
?>

<style>
    .centerImg{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 100px;
        height: 100px;
    }
    .gifImg{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 23px;
        height: 23px;
    }
    .btn-outline-success:hover{
        background-color: rgb(72,81,87);
    }

    .teksti{
        justify-content:space-between;
        margin-top:-50px;
        color:#FFF;
        font-weight:bold;
        font-size:23px;
        margin-bottom:10px;
    }
    .teksti2{
        justify-content:space-between;
        margin-top:-77px;
        color:#FFF;
        font-weight:bold;
        font-size:23px;
        margin-bottom:10px;
    }
 
    .prod-name{
        line-height: 2;
    }
    .add-plus-section{
        text-align: right;
        padding: 0px;
    }
    .product-section{
        border-bottom: 1px solid #dcd9d9;
        padding-bottom: 15px;
    }
    .recommended-title{
        margin-left: 0px !important;
    }
    .teksti strong{
        margin-left:20px;
    }
    .teksti i{
        margin-right:20px
    }

    .noOutlineFocus:focus {
        outline:none!important;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<div class="alert alert-success text-center" style="position:fixed; top:15px; left:10%; width:80%; font-weight:bold; font-size:1.3rem; z-index:99; display:none;" id="prodAddScByKgLtrAlert">
    Die entsprechende Menge wurde erfolgreich im Produkt gespeichert
</div>

<!-- register to stock Modal -->
<div class="modal" id="stockRegModal" tabindex="-1" role="dialog" aria-labelledby="stockRegModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockRegModalLabel"><strong>Öffnen Sie das Fenster zur Aktienregistrierung</strong></h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">

            <div class="alert alert-success text-center" style="position:fixed; top:15px; left:10%; width:80%; font-weight:bold; font-size:1.3rem; z-index:99; display:none;" id="prodAddScAlert">
                Das Produkt „x“ wurde erfolgreich im Bestand registriert
            </div>

                <p style="color:rgb(72,81,87);" class="text-center"><strong>In dieser Liste werden nur die nicht registrierten Produkte/Extras zum Bestand angezeigt!</strong></p>
                <div class="d-flex flex-wrap justify-content-between" id="stockRegModalMenu">
                    @foreach (kategori::where('toRes',Auth::User()->sFor)->whereNotIn('id', $activeKats)->get() as $kat)
                        <div style="width:49.6%;" class="mb-2" id="kategoriaShowAll{{$kat->id}}">
                            <div class="allKatFoto" id="KategoriFoto{{$kat->id}}">
                                <div style="cursor: pointer; position:relative; object-fit: cover;" >
                                    <img style="border-radius:30px; width:100%; height:120px;" onclick="showProKat('{{$kat->id}}')" src="storage/kategoriaUpload/{{$kat->foto}}"alt="">

                                    @if(strlen($kat->emri) > 20)
                                        <div class="teksti d-flex justify-content-between" style="font-size:20px;  margin-bottom:13px;">          
                                        
                                            <div style="width: 8%;" class="text-center" id="KategoriPlus{{$kat->id}}">
                                                <i style="color:white;" class="fas fa-plus-circle ml-2" onclick="addCategoryForStock('{{$kat->id}}','{{$kat->emri}}')"></i> 
                                            </div>
                                         
                                            @if(ekstra::where('toCat',$kat->id)->count() > 0)
                                            <span style="width: 70%; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" class="text-left" onclick="showProKat('{{$kat->id}}')"><strong>{{$kat->emri}}</strong></span> 
                                            <button style="width: 20%;" class="btn btn-dark mr-3 shadow-none" data-toggle="modal" data-target="#categoryTAndE{{$kat->id}}">Extras</button>
                                            @else
                                            <span style="width: 90%; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" class="text-left" onclick="showProKat('{{$kat->id}}')"><strong>{{$kat->emri}}</strong></span> 
                                            @endif
                                        </div>
                                    @else
                                        <div class="teksti d-flex justify-content-between" >          
                                                     
                                            <div style="width: 8%;" class="text-center"  id="KategoriPlus{{$kat->id}}">
                                                <i style="color:white;" class="fas fa-plus-circle ml-2" onclick="addCategoryForStock('{{$kat->id}}','{{$kat->emri}}')"></i> 
                                            </div>
                                       
                                            @if(ekstra::where('toCat',$kat->id)->count() > 0)
                                            <span style="width: 70%; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" class="text-left" onclick="showProKat('{{$kat->id}}')"><strong>{{$kat->emri}}</strong></span> 
                                            <button style="width: 20%;" class="btn btn-dark mr-3 shadow-none" data-toggle="modal" data-target="#categoryTAndE{{$kat->id}}">Extras</button>
                                            @else
                                            <span style="width: 90%; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" class="text-left" onclick="showProKat('{{$kat->id}}')"><strong>{{$kat->emri}}</strong></span> 
                                            @endif
                                        </div>
                                    @endif
                                    <input type="hidden" value="0" id="state{{$kat->id}}">
                                </div>
                            </div>
                            <div class="container prodsFoto" id="prodsKatFoto{{$kat->id}}" style="display:none;">
                                @foreach(Produktet::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$kat->id)->orderByDesc('visits')->get() as $ketoProd)
                                    @if(stockmng::where([['toRes',Auth::user()->sFor],['elementType',2],['elementId',$ketoProd->id]])->first() == Null)
                                        <div class="row p-2" id="catProds{{$ketoProd->id}}">
                                            <div class="container-fluid">
                                                <div class="row">
                                                        
                                                    <div class="col-12 product-section">
                                                        <div class="row">
                                                                
                                                            <diV class="col-9">
                                                                <h4 class="pull-right prod-name prodsFont color-text" style="font-weight:bold; font-size: 1.20rem ">
                                                                    {{$ketoProd->emri}} 
                                                                    @if($ketoProd->restrictPro != 0)
                                                                        @if($ketoProd->restrictPro == 16)
                                                                        <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R16.jpeg" alt="noImg"></span>
                                                                        @elseif($ketoProd->restrictPro == 18)
                                                                        <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R18.jpeg" alt="noImg"></span>
                                                                        @endif
                                                                    @endif
                                                                </h4>
                                                                    <p style=" margin-top:-10px; font-size:13px;">{{substr($ketoProd->pershkrimi,0,35)}} 
                                                                        @if(strlen($ketoProd->pershkrimi)>35)
                                                                            <span onclick="showTypeMenu('{{$ketoProd->id}}')" class="hover-pointer" style="font-size:16px;"> . . .</span> 
                                                                        @endif 
                                                                    </p>
                                                                <h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">
                                                                    CHF
                                                                    </span> 
                                                                        @if(Carbon::now()->format('H:i') >= '20:00' || Carbon::now()->format('H:i') <= '03:00')
                                                                            @if($ketoProd->qmimi2 != 999999)
                                                                                {{sprintf('%01.2f', $ketoProd->qmimi2)}}
                                                                            @else
                                                                                {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                                            @endif
                                                                        @else
                                                                            @if($ketoProd->qmimi2 != 999999)
                                                                                {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                                                @if(Carbon::now()->format('H:i') > '19:40' && Carbon::now()->format('H:i') < '20:00')
                                                                                    <span class="ml-4" style="font-size:14px;">{{__('adminP.from8Pm')}} <span style="color:gray;">{{__('adminP.currencyShow')}}</span>
                                                                                    {{sprintf('%01.2f', $ketoProd->qmimi2)}} </span>
                                                                                @endif
                                                                            @else
                                                                                {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                                            @endif
                                                                        @endif
                                                                </h5>
                                                            </div>
                                                            <div class="col-3 add-plus-section">
                                                                <button onclick="addProductForStock('{{$ketoProd->id}}','{{$kat->id}}','{{$ketoProd->emri}}')" 
                                                                class="btn-block btn btn-success shadow-none" id="addProductForCookBtn{{$ketoProd->id}}">
                                                                    <strong><i class="fa-solid fa-plus"></i></strong>
                                                                </button>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="modal" id="categoryTAndE{{$kat->id}}" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                        style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">( {{$kat->emri}} ) Extras </h5>
                                        <button type="button" class="close shadow-none" onclick="closeCategoryTAndE('{{$kat->id}}')" aria-label="Close">
                                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex flex-wrap justify-content-start" id="categoryTAndE{{$kat->id}}Body">
                                            @if(ekstra::where('toCat',$kat->id)->count() > 0)
                                                <h4 style="color:rgb(39,190,175); width:100%;">Extras</h4>
                                                @foreach (ekstra::where('toCat',$kat->id)->get() as $oneEx)
                                                    @if (stockmng::where([['toRes',Auth::user()->sFor],['elementType',3],['elementId',$oneEx->id]])->first() == Null)
                                                        <button class="btn btn-outline-dark mb-1 shadow-none" onclick="addExtraForStock('{{$oneEx->id}}','{{$kat->id}}','{{$oneEx->emri}}')" 
                                                        style="width:49%; margin-right:1%;" id="addExtraForCookBtn{{$oneEx->id}}">{{$oneEx->emri}}</button>
                                                    @endif
                                                @endforeach

                                                <hr style="width: 100%;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>











<div class="pt-2 pr-2 pl-2 pb-5"> 
    <button class="btn btn-outline-dark shadow-none mb-2" style="width:100%; font-size:1.2rem;" data-toggle="modal" data-target="#stockRegModal">
        <strong>Öffnen Sie das Fenster zur Aktienregistrierung</strong>
    </button>
    <div class="d-flex flex-wrap justify-content-around" id="stockPeriodDiv">
        <?php $theRes = Restorant::find(Auth::user()->sFor); ?>
        <div class="d-flex text-center" style="width:fit-content;">
            <div style="background-color: red; width:29px; height:29px;"></div>
            <p style="width: fit-content; margin:0 0 0 8px; font-size:18px; font-weight:bold; color:rgb(72,81,87);">
                0 -> 
            </p>
            <input type="number" value="{{$theRes->stockP1}}" id="st1" onkeydown="showStockPeriodSaveBtn()" class="noOutlineFocus text-center" value="0" style="width:2cm; border:none; border-bottom:2px solid black; font-size:1.2rem;">
        </div>
        <div class="d-flex text-center" style="width:fit-content;">
            <div style="background-color: yellow; width:29px; height:29px;"></div>
            <p style="width: fit-content; margin:0 0 0 8px; font-size:18px; font-weight:bold; color:rgb(72,81,87);">
                <span>{{$theRes->stockP1 + 1}}</span> -> 
            </p>
            <input type="number" value="{{$theRes->stockP2}}" id="st2" onkeydown="showStockPeriodSaveBtn()" class="noOutlineFocus text-center" value="0" style="width:2cm; border:none; border-bottom:2px solid black; font-size:1.2rem;">
        </div>
        <div class="d-flex text-center" style="width:fit-content;">
            <div style="background-color: green; width:29px; height:29px;"></div>
            <p style="width: fit-content; margin:0 0 0 8px; font-size:18px; font-weight:bold; color:rgb(72,81,87);">
                <span>{{$theRes->stockP2 + 1}}</span>+ 
            </p>
        </div>
        <button id="stockPeriodSaveBtn" class="btn btn-success mt-1" style="width:100%; margin:0px; display:none;" onclick="saveStockPeriodChange()">
            <strong>Änderungen speichern</strong>
        </button>

        <div class="alert alert-danger text-center mt-1" id="saveStockPeriodChangeErr01" style="width: 100%; display:none;">
            <strong>Der erste Wert sollte größer als 0 sein</strong>
        </div>
        <div class="alert alert-danger text-center mt-1" id="saveStockPeriodChangeErr02" style="width: 100%; display:none;">
            <strong>Der zweite Wert sollte größer als der erste Wert sein und eins addieren</strong>
        </div>
        <div class="alert alert-success text-center mt-1" id="saveStockPeriodChangeSucc01" style="width: 100%; display:none;">
            <strong>Sie haben die Änderung an den Gruppen erfolgreich durchgeführt</strong>
        </div>
    </div>
    <hr>

    <div class="d-flex flex-wrap justify-content-between" id="stockMenuShowAll">
        @foreach(kategori::where('toRes',Auth::User()->sFor)->get() as $catOneShow)
            <?php
                $katIsShow = 0;
                $extraIsShow = 0;
                $prodsShown = 0;
                if(stockmng::where([['toRes',Auth::user()->sFor],['elementType',1],['elementId',$catOneShow->id]])->first() != Null){
                    $katIsShow = 1;
                    foreach(ekstra::where([['toRes',Auth::user()->sFor],['toCat',$catOneShow->id]])->get() as  $extOneFilter){
                        $stIns = stockmng::where([['toRes',Auth::user()->sFor],['elementType',3],['elementId',$extOneFilter->id]])->first();
                        if($stIns != Null){
                            $extraIsShow = 1;
                            break;
                        }
                    }
                }else{
                    foreach(Produktet::where([['toRes',Auth::user()->sFor],['kategoria',$catOneShow->id]])->get() as $prodOneFilter){
                        $stIns = stockmng::where([['toRes',Auth::user()->sFor],['elementType',2],['elementId',$prodOneFilter->id]])->first();
                        if($stIns != Null){
                            $katIsShow = 1;
                            break;
                        }
                    }
                    foreach(ekstra::where([['toRes',Auth::user()->sFor],['toCat',$catOneShow->id]])->get() as  $extOneFilter){
                        $stIns = stockmng::where([['toRes',Auth::user()->sFor],['elementType',3],['elementId',$extOneFilter->id]])->first();
                        if($stIns != Null){
                            if($katIsShow == 0){
                                $katIsShow = 1;
                            }
                            $extraIsShow = 1;
                            break;
                        }
                    }
                }
            ?>
            @if ($katIsShow == 1)
                <div style="width:49.6%;" class="mb-2" id="stockKategoriaShowAll{{$catOneShow->id}}">
                    <div class="allKatFoto" id="stockKategoriFoto{{$catOneShow->id}}">
                        <div style="cursor: pointer; position:relative; object-fit: cover;" >
                            <img style="border-radius:30px; width:100%; height:120px;" onclick="showProKatStock('{{$catOneShow->id}}')" src="storage/kategoriaUpload/{{$catOneShow->foto}}"alt="">

                            @if(strlen($catOneShow->emri) > 20)
                                <div class="teksti2 d-flex justify-content-between" style="font-size:20px;  margin-bottom:13px;">          
                                    <!-- <div style="width: 8%;" class="text-center" id="KategoriPlus{{$catOneShow->id}}">
                                        <i style="color:white;" class="fas fa-plus-circle ml-2" onclick="addCategoryForStock('{{$kat->id}}','{{$kat->emri}}')"></i> 
                                    </div> -->

                                    <div class="d-flex stateShowKatAll" style="background-color: whitesmoke; opacity:0.69; width:98%; margin-bottom:5px; margin-left:1%;" id="stateShowKat{{$catOneShow->id}}">
                                        <?php
                                            $stockP1 = (float)$theRes->stockP1;
                                            $stockP2 = (float)$theRes->stockP2;

                                            $redGr = stockmng::where([['toRes',Auth::user()->sFor],['katId',$catOneShow->id],['sasia','<=',$stockP1]])->count();
                                            $yellowGr = stockmng::where([['toRes',Auth::user()->sFor],['katId',$catOneShow->id],['sasia','>',$stockP1],['sasia','<=',$stockP2]])->count();;
                                            $greenGr = stockmng::where([['toRes',Auth::user()->sFor],['katId',$catOneShow->id],['sasia','>',$stockP2]])->count();;
                                        ?>
                                    
                                        <div class="d-flex" style="width:32%;">
                                            <div style="background-color: red; width:29px; height:29px;"></div>
                                            <p style="width: fit-content; margin:0 0 0 8px; font-size:18px; font-weight:bold; color:rgb(72,81,87);">
                                                <span>{{$redGr}}</span> Produkte
                                            </p>
                                        </div>
                                        <div class="d-flex" style="width:32%;">
                                            <div style="background-color: yellow; width:29px; height:29px;"></div>
                                            <p style="width: fit-content; margin:0 0 0 8px; font-size:18px; font-weight:bold; color:rgb(72,81,87);">
                                                <span>{{$yellowGr}}</span> Produkte
                                            </p>
                                        </div>
                                        <div class="d-flex" style="width:32%;">
                                            <div style="background-color: green; width:29px; height:29px;"></div>
                                            <p style="width: fit-content; margin:0 0 0 8px; font-size:18px; font-weight:bold; color:rgb(72,81,87);">
                                                <span>{{$greenGr}}</span> Produkte
                                            </p>
                                        </div>
                                    </div>
                                
                                    @if($extraIsShow == 1)
                                    <span style="width: 76%; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" class="text-left pl-3" onclick="showProKatStock('{{$catOneShow->id}}')"><strong>{{$catOneShow->emri}}</strong></span> 
                                    <button style="width: 20%; margin-right:2%;" class="btn btn-dark shadow-none" data-toggle="modal" data-target="#stockCategoryTAndE{{$catOneShow->id}}">Extras</button>
                                    @else
                                    <span style="width: 100%; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" class="text-left pl-3" onclick="showProKatStock('{{$catOneShow->id}}')"><strong>{{$catOneShow->emri}}</strong></span> 
                                    @endif
                                </div>
                            @else
                                <div class="teksti2 d-flex flex-wrap justify-content-between" >          
                                    <!-- <div style="width: 8%;" class="text-center"  id="stockKategoriPlus{{$catOneShow->id}}">
                                        <i style="color:white;" class="fas fa-plus-circle ml-2" onclick="addCategoryForStock('{{$kat->id}}','{{$kat->emri}}')"></i> 
                                    </div> -->
                                    <div class="d-flex stateShowKatAll" style="background-color: whitesmoke; opacity:0.69; width:98%; margin-bottom:5px; margin-left:1%;" id="stateShowKat{{$catOneShow->id}}">
                                        <?php
                                            $stockP1 = (float)$theRes->stockP1;
                                            $stockP2 = (float)$theRes->stockP2;

                                            $redGr = stockmng::where([['toRes',Auth::user()->sFor],['katId',$catOneShow->id],['sasia','<=',$stockP1]])->count();
                                            $yellowGr = stockmng::where([['toRes',Auth::user()->sFor],['katId',$catOneShow->id],['sasia','>',$stockP1],['sasia','<=',$stockP2]])->count();;
                                            $greenGr = stockmng::where([['toRes',Auth::user()->sFor],['katId',$catOneShow->id],['sasia','>',$stockP2]])->count();;
                                        ?>
                                    
                                        <div class="d-flex" style="width:32%;">
                                            <div style="background-color: red; width:29px; height:29px;"></div>
                                            <p style="width: fit-content; margin:0 0 0 8px; font-size:18px; font-weight:bold; color:rgb(72,81,87);">
                                                <span>{{$redGr}}</span> Produkte
                                            </p>
                                        </div>
                                        <div class="d-flex" style="width:32%;">
                                            <div style="background-color: yellow; width:29px; height:29px;"></div>
                                            <p style="width: fit-content; margin:0 0 0 8px; font-size:18px; font-weight:bold; color:rgb(72,81,87);">
                                                <span>{{$yellowGr}}</span> Produkte
                                            </p>
                                        </div>
                                        <div class="d-flex" style="width:32%;">
                                            <div style="background-color: green; width:29px; height:29px;"></div>
                                            <p style="width: fit-content; margin:0 0 0 8px; font-size:18px; font-weight:bold; color:rgb(72,81,87);">
                                                <span>{{$greenGr}}</span> Produkte
                                            </p>
                                        </div>
                                    </div>
                            
                                    @if($extraIsShow == 1)
                                    <span style="width: 76%; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" class="text-left pl-3" onclick="showProKatStock('{{$catOneShow->id}}')"><strong>{{$catOneShow->emri}}</strong></span> 
                                    <button style="width: 20%; margin-right:2%;" class="btn btn-dark shadow-none" data-toggle="modal" data-target="#stockCategoryTAndE{{$catOneShow->id}}">Extras</button>
                                    @else
                                    <span style="width: 100%; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" class="text-left pl-3" onclick="showProKatStock('{{$catOneShow->id}}')"><strong>{{$catOneShow->emri}}</strong></span> 
                                    @endif
                                </div>
                            @endif
                            <input type="hidden" value="0" id="stockState{{$catOneShow->id}}">
                        </div>
                    </div>
                    <div class="container prodsFoto" id="stockProdsKatFoto{{$catOneShow->id}}" style="display:none;">
                        @foreach(Produktet::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$catOneShow->id)->orderByDesc('visits')->get() as $prodShow)
                        <?php $stInsTD = stockmng::where([['toRes',Auth::user()->sFor],['elementType',2],['elementId',$prodShow->id]])->first(); ?>
                            @if( $stInsTD != Null)
                                <div class="row p-1" id="stockCatProds{{$prodShow->id}}">
                                    <div class="container-fluid">
                                        <div class="row">
                                                
                                            <div class="col-12 product-section">
                                                <div class="row">
                                                        
                                                    <diV class="col-9">
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-danger shadow-none" style="width:10%;" onclick="prepDeleteStockInsProduct('{{$stInsTD->id}}','{{$prodShow->emri}}')">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                            <div style="width:90%;">
                                                                <h4 class="pull-right prod-name prodsFont color-text pl-1" style="font-weight:bold; font-size: 1.20rem; margin:0;">
                                                                    {{$prodShow->emri}} 
                                                                    @if($prodShow->restrictPro != 0)
                                                                        @if($prodShow->restrictPro == 16)
                                                                        <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R16.jpeg" alt="noImg"></span>
                                                                        @elseif($prodShow->restrictPro == 18)
                                                                        <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R18.jpeg" alt="noImg"></span>
                                                                        @endif
                                                                    @endif
                                                                    <span style="font-size: 1.3rem; font-weight:bold;"> | </span>
                                                                    <span  style="margin:0px; color:gray;">
                                                                        CHF {{sprintf('%01.2f', $prodShow->qmimi)}} 
                                                                        @if ($prodShow->qmimi2 != 999999)
                                                                            / CHF {{sprintf('%01.2f', $prodShow->qmimi2)}}
                                                                        @endif
                                                                    </span>
                                                                </h4>
                                                                <p class="pl-1" style="margin:-10px 0 0 0; font-size:13px;">{{substr($prodShow->pershkrimi,0,35)}} 
                                                                    @if(strlen($prodShow->pershkrimi)>35)
                                                                        <span onclick="showTypeMenu('{{$prodShow->id}}')" class="hover-pointer" style="font-size:16px;"> . . .</span> 
                                                                    @endif 
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex flex-wrap justify-content-start" id="selectStockInsToAddAll">
                                                            <?php $allProInSt = stockmng::where([['toRes',Auth::user()->sFor],['elementType',2],['elementId',$prodShow->id]])->get();?>
                                                            @if (count($allProInSt) > 1)
                                                                @foreach($allProInSt as $allStInsPro)
                                                                    <?php $theTy = LlojetPro::find($allStInsPro->theType); ?>
                                                                    <button class="btn btn-outline-dark shadow-none" style="width:33%; padding:0px; margin:3px 0.15% 3px 0.15%;" 
                                                                    id="typeSelBtn{{$prodShow->id}}O{{$allStInsPro->id}}" onclick="selectTypeProdStockAdd('{{$prodShow->id}}','{{$allStInsPro->id}}')">
                                                                        <span id="typeSelBtnSasia{{$prodShow->id}}O{{$allStInsPro->id}}">{{$allStInsPro->sasia}}</span>  x 
                                                                        <span style="font-size: 1.3rem; font-weight:bold;"> | </span> {{$theTy->emri}}
                                                                    </button>
                                                                @endforeach
                                                                <input type="hidden" value="0" id="selectStockInsToAdd{{$prodShow->id}}">
                                                            @elseif (count($allProInSt) == 1)
                                                                @if ($allProInSt[0]->theType == 0)
                                                                <p style="color:rgb(72,81,87); font-size:1.2rem;"><strong>Menge: 
                                                                    <span id="typeSelBtnSasia{{$prodShow->id}}O{{$allProInSt[0]->id}}">{{$allProInSt[0]->sasia}}</span>  x 
                                                                </strong></p>
                                                                <input type="hidden" value="{{$allProInSt[0]->id}}" id="selectStockInsToAdd{{$prodShow->id}}">
                                                                @else
                                                                    <?php $theTy = LlojetPro::find($allProInSt[0]->theType); ?>
                                                                    <button class="btn btn-dark shadow-none" style="width:33%; padding:0px; margin:3px 0.15% 3px 0.15%;" 
                                                                    id="typeSelBtn{{$prodShow->id}}O{{$allProInSt[0]->id}}">
                                                                        <span id="typeSelBtnSasia{{$prodShow->id}}O{{$allProInSt[0]->id}}">{{$allProInSt[0]->sasia}}</span>  x 
                                                                        <span style="font-size: 1.3rem; font-weight:bold;"> | </span> {{$theTy->emri}}
                                                                    </button>
                                                                    <input type="hidden" value="{{$allProInSt[0]->id}}" id="selectStockInsToAdd{{$prodShow->id}}">
                                                                @endif
                                                                
                                                            @endif

                                                            <div class="alert alert-danger text-center mt-1" style="display:none; width:100%; font-weight:bold;" id="typeNotSelected{{$prodShow->id}}">
                                                                Wählen Sie zunächst einen oder mehrere Typen aus
                                                            </div>
                                                        </div>
                                                      
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="d-flex flex-wrap justify-content-between">
                                                            <button style="width:49.8%; font-weight:bold;" onclick="addToStock('1','{{$prodShow->id}}','{{$catOneShow->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none">+1</button>
                                                            <button style="width:49.8%; font-weight:bold;" onclick="addToStock('5','{{$prodShow->id}}','{{$catOneShow->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none">+5</button>
                                                            <button style="width:49.8%; font-weight:bold;" onclick="addToStock('20','{{$prodShow->id}}','{{$catOneShow->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none">+20</button>
                                                            <button style="width:49.8%; font-weight:bold;" onclick="addToStock('50','{{$prodShow->id}}','{{$catOneShow->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none">+50</button>
                                                            <input type="number" class="noOutlineFocus text-center" id="addToStockByNrInput{{$prodShow->id}}" value="0" style="width:49.8%; border:none; border-bottom:2px solid black; font-size:1.2rem;">
                                                            <button style="width:49.8%; font-weight:bold;" id="addToStockByNrBtn{{$prodShow->id}}" onclick="addToStockByNr('{{$prodShow->id}}','{{$catOneShow->id}}')" class="btn btn-outline-dark text-center shadow-none"><i class="fa-solid fa-chevron-right"></i></button>

                                                            <button style="width:100%; font-weight:bold;" id="addToStockByKgLtrBtn{{$prodShow->id}}" onclick="addToStockByKgLtrPrep('{{$prodShow->id}}','{{$catOneShow->id}}')" 
                                                            class="btn btn-outline-dark text-center shadow-none mt-1">
                                                                <strong>Mit kg/ltr</strong>
                                                            </button>

                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $prodsShown++; ?>
                            @endif
                        @endforeach
                        @if ($prodsShown == 0)
                            <p style="color:rgb(72,81,87); font-size:1.2rem;" class="text-center">
                                <strong>Für diese Kategorie sind derzeit keine Produkte registriert</strong>
                            </p> 
                        @endif
                    </div>
                </div>
                <div class="modal" id="stockCategoryTAndE{{$catOneShow->id}}" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel" style="font-size: 1.3rem;"> <strong>{{$catOneShow->emri}}</strong> </h5>
                                <button type="button" class="close shadow-none" onclick="stockCloseCategoryTAndE('{{$catOneShow->id}}')" aria-label="Close">
                                <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex flex-wrap justify-content-start" id="categoryTAndE{{$catOneShow->id}}Body">
                                    @if(ekstra::where('toCat',$catOneShow->id)->count() > 0)
                                        <h4 style="color:rgb(39,190,175); width:100%;">Extras</h4>
                                        @foreach (ekstra::where('toCat',$catOneShow->id)->get() as $oneEx)
                                            <div style="width:100%;" class="d-flex flex-wrap justify-content-start" id="extraInsStOne{{$oneEx->id}}">
                                                <?php $exSIns = stockmng::where([['toRes',Auth::user()->sFor],['elementType',3],['elementId',$oneEx->id]])->first(); ?>
                                                @if ($exSIns != Null)
                                                
                                                    <button class="btn btn-danger shadow-none p-1" style="width:10%;" onclick="prepDeleteStockInsEkstra('{{$exSIns->id}}','{{$oneEx->emri}}')">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                    <p style="color:rgb(72,81,87); width:54%; margin-bottom:6px;" class="pl-1"><strong>{{$oneEx->emri}}</strong></p>
                                                    <p style="color:rgb(72,81,87); width:34%; margin-bottom:6px;" class="text-center"><strong>{{$exSIns->sasia}} X</strong></p>
                                                    <hr style="width: 100%; margin:2px;">
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>


<!-- Kg or Ltr Stock Save Modal -->
<div class="modal" id="addStokWithKgLtrModal" tabindex="-1" role="dialog" aria-labelledby="stockRegModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="addStokWithKgLtrModalLabel"><strong>Brühe in großen Mengen hinzufügen (Kg/Ltr)</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="restoreAddStokWithKgLtrModal()">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body" id="addStokWithKgLtrModalBody">
                <!-- Kontenti i caktuar nga funksioni pergaditor -->
            </div>
            <button class="btn btn-success shadow-none" style="font-size:1.3rem;" onclick="saveStockKgLtr()">
                <strong>Lagerbestände bearbeiten und speichern</strong>
            </button>
            <div class="alert alert-danger shadow-none text-center mt-1" style="display:none; width:100%;" id="addStokWithKgLtrErr01">
                <strong>Wählen Sie zunächst den Registrierungstyp (Kg oder Ltr) für alle Instanzen!</strong>
            </div>
            <div class="alert alert-danger shadow-none text-center mt-1" style="display:none; width:100%;" id="addStokWithKgLtrErr02">
                <strong>Bitte geben Sie eine gültige Menge in die erforderlichen Felder ein!</strong>
            </div>
            <div class="alert alert-danger shadow-none text-center mt-1" style="display:none; width:100%;" id="addStokWithKgLtrErr03">
                <strong>Es gibt Instanzen zum Speichern!</strong>
            </div>

        </div>
    </div>
</div>

<!-- iniciate a stock delete Modal -->
<div class="modal" id="deleteAStockInsModal" tabindex="-1" role="dialog" aria-labelledby="deleteAStockInsModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAStockInsModalLabel"><strong>---</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="restoredeleteAStockInsModal()">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body" id="deleteAStockInsModalBody">
                <div class="d-flex justify-content-between">
                    <button style="width:48%;" class="btn btn-danger shadow-none" data-dismiss="modal" aria-label="Close" onclick="restoredeleteAStockInsModal()"><strong>Nein</strong></button>
                    <button style="width:48%;" class="btn btn-success shadow-none" id="deleteAStockInsModalJaBtn"><strong>Ja</strong></button>
                </div>
                <div class="alert alert-info text-center mt-1" style="width:100%; font-weight:bold;" id="deleteAStockInsModalAlert">
                </div>
                <input type="hidden" value="0" id="deleteAStockInsId" >
            </div>

        </div>
    </div>
</div>

@include('adminPanel.stockMng.stockMngIndexJS')