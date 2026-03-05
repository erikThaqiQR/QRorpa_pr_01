<?php

use App\kategori;
use App\Produktet;
use App\orderServingDevices;
use App\orderServingDevicesAccess;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
    $allOrDevices = orderServingDevices::where('toRes',Auth::user()->sFor)->get();
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
</style>

<div class="p-2 pb-5">
    <hr style="margin:5px 0px 5px 0px;">
    <div style="width:100%;" class="d-flex justify-content-between">
        <h4 style="color:rgb(39,190,175); width:50%;"><strong>Serviergeräte bestellen</strong></h4>
        <button style="width:50%;" class="btn btn-dark shadow-none" data-toggle="modal" data-target="#addOSDevice"><strong><i class="fa-solid fa-plus"></i> Registrieren Sie ein Gerät</strong></button>
    </div>

    <!-- register device Modal -->
    <div class="modal" id="addOSDevice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.6); padding-top:1%; z-index:9999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body d-flex flex-wrap justify-content-between">
                    <p class="pb-2 pt-1" style="color:rgb(39,190,175); font-size:18px; margin:0; width:100%;"><strong>Scannen Sie diesen QR-Code, um die Bestellseite zu öffnen</strong></p>
                    <div class="input-group mb-1 mt-1" style="width:100%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-mobile-screen-button"></i></span>
                        </div>
                        <input type="text" id="addOSDeviceInput" class="form-control shadow-none" placeholder="Gerätename" aria-label="Gerätename">
                    </div>
                    <div class="mt-1 mb-1 text-center alert alert-danger" style="width: 100%; display:none;" id="addOSDeviceError01">
                        <strong>Schreiben Sie zuerst den Gerätenamen!</strong>
                    </div>

                    <button style="width:49%;" class="btn btn-outline-danger shadow-none" onclick="closeAddOSDevice()">Schließen</button>
                    <button style="width:49%;" class="btn btn-outline-success shadow-none" onclick="saveNewDevice()">Sparen</button>

                    <div class="alert alert-success mt-1 text-center" style="width:100%; display:none;" id="addOSDeviceSucc01">
                        Sie haben erfolgreich ein neues Gerät registriert!
                    </div>
                </div>
            </div>
        </div> 
    </div>

    
    <!-- show qr-code Modal -->
    <div class="modal" id="orderServingPageQrCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.6); padding-top:1%; z-index:9999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body text-center">
                    <p class="pb-2 pt-1 text-center" style="color:rgb(39,190,175); font-size:24px;"><strong>Scannen Sie diesen QR-Code, um die Bestellseite zu öffnen</strong></p>
                    
                    <img id="orderServingPageQrCodeImg" style="width:100%; height:auto;" src="" alt="qrCodeNotFound">

                    <button onclick="closeorderServingPageQrCode()" type="button" class="close mt-3 text-center" style="width:100%; margin:0px;" aria-label="Close">
                    <i style="color:red;" class="far fa-2x fa-times-circle"></i>
                    </button>
                </div>
            </div>
        </div>  
    </div>

    <!-- delete device confirmation Modal -->
    <div class="modal" id="deleteDeviceConfModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.6); padding-top:1%; z-index:9999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body text-center d-flex flex-wrap justify-content-between">
                    <p class="pb-1 pt-1 text-center" style="color:rgb(39,190,175); font-size:24px; width:100%; font-weight:bold">Sind Sie sicher, dass Sie dieses Gerät löschen möchten?</p>
                    <p id="deleteDeviceConfName" class="pb-1 pt-1 text-center" style="color:rgb(39,190,175); font-size:24px; width:100%; font-weight:bold">xxxxxx</p>
                
                    <button style="width: 49.5%;" class="btn btn-outline-dark" data-dismiss="modal"><strong>Nein</strong></button>
                    <button style="width: 49.5%;" class="btn btn-danger" onclick="deleteThisDevice()"><strong>Ja</strong></button>

                    <input type="hidden" value="0" id="deleteDeviceId">
                </div>
            </div>
        </div>  
    </div>

    <div style="width:100%;" class="d-flex flex-wrap justify-content-start mt-3" id="showDevicesDiv">
        @if (count($allOrDevices) == 0)
            <p class="text-center" style="width:100%; font-size:1.3rem; color:rgb(72,81,87);">
                <strong> Es sind noch keine Geräte registriert! </strong>
            </p>
        @else
            @foreach ($allOrDevices as $oneOrDevices)
                <div style="width:24.5%; margin:4px 0.25% 4px 0.25%; border:1px solid rgb(72,81,87); border-radius:7px;" class="d-flex flex-wrap justify-content-between p-1" id="showDevicesDivOne{{$oneOrDevices->id}}">
                    <p style="width:100%;" class="text-center"><strong>{{$oneOrDevices->deviceName}}</strong></p>

                    <button style="width: 24.5%;" class="btn btn-danger shadow-none" data-toggle="modal" onclick="deleteThisDevicePrep('{{$oneOrDevices->id}}','{{$oneOrDevices->deviceName}}')" data-target="#deleteDeviceConfModal"> <i class="fa-2x fa-solid fa-trash-can"></i> </button>
                    <button style="width: 24.5%;" class="btn btn-info shadow-none" data-toggle="modal" onclick="showQrCodeOrSer('{{$oneOrDevices->qrCodeName}}')" data-target="#orderServingPageQrCodeModal"><strong><i class="fa-2x fa-solid fa-qrcode"></i></strong></button>
                    <button id="orderServingBtnLink{{$oneOrDevices->id}}" style="width: 24.5%;" class="btn btn-info shadow-none" onclick="CopyTheLink('{{$oneOrDevices->id}}','{{$oneOrDevices->theHash}}');"><strong><i class="fa-2x fa-solid fa-globe"></i></strong></button>
                    <button style="width: 24.5%;" class="btn btn-info shadow-none" data-toggle="modal" data-target="#prodAccessToDevi{{$oneOrDevices->id}}Modal">Zugang</button>
                </div>



                <div class="modal" id="prodAccessToDevi{{$oneOrDevices->id}}Modal" ttabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{$oneOrDevices->deviceName}} Zugang zu Produkten</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex flex-wrap justify-content-start" id="prodAccessToDevi{{$oneOrDevices->id}}AControlls">
                                    @foreach (kategori::where('toRes',Auth::User()->sFor)->get() as $kat)

                                        <div style="width:100%;" class="mb-3">

                                            <div class="allKatFoto" id="KategoriFoto{{$kat->id}}O{{$oneOrDevices->id}}">
                                                <div style="cursor: pointer; position:relative; object-fit: cover;" >
                                                    <img style="border-radius:30px; width:100%; height:120px;" onclick="showProKat('{{$kat->id}}','{{$oneOrDevices->id}}')" src="storage/kategoriaUpload/{{$kat->foto}}"alt="">

                                                    <?php $catAccess = orderServingDevicesAccess::where([['deviceId',$oneOrDevices->id],['accessType','1'],['prodCatId',$kat->id]])->first();?>
                                                    @if(strlen($kat->emri) > 20)
                                                        <div class="teksti d-flex justify-content-between" style="font-size:20px;  margin-bottom:13px;">          
                                                            @if($catAccess != NULL)
                                                                <div style="width: 8%;" class="text-center" id="KategoriPlus{{$kat->id}}O{{$oneOrDevices->id}}">
                                                                    <i style="color:rgb(39,190,175);" class="fas fa-plus-circle ml-2" onclick="removeCategoryForDevice('{{$oneOrDevices->id}}','{{$kat->id}}','{{$catAccess->id}}')"></i> 
                                                                </div>
                                                            @else
                                                                <div style="width: 8%;" class="text-center" id="KategoriPlus{{$kat->id}}O{{$oneOrDevices->id}}">
                                                                <i style="color:white;" class="fas fa-plus-circle ml-2" onclick="addCategoryForDevice('{{$oneOrDevices->id}}','{{$kat->id}}')"></i> 
                                                                </div>
                                                            @endif
                                                            <span style="width: 90%;" class="text-left" onclick="showProKat('{{$kat->id}}','{{$oneOrDevices->id}}')"><strong>{{$kat->emri}}</strong></span> 
                                                        </div>
                                                    @else
                                                        <div class="teksti d-flex justify-content-between" >            
                                                            @if($catAccess != NULL)
                                                                <div style="width: 8%;" class="text-center" id="KategoriPlus{{$kat->id}}O{{$oneOrDevices->id}}">
                                                                <i style="color:rgb(39,190,175);" class="fas fa-plus-circle ml-2" onclick="removeCategoryForDevice('{{$oneOrDevices->id}}','{{$kat->id}}','{{$catAccess->id}}')"></i> 
                                                                </div>
                                                            @else
                                                                <div style="width: 8%;" class="text-center"  id="KategoriPlus{{$kat->id}}O{{$oneOrDevices->id}}">
                                                                <i style="color:white;" class="fas fa-plus-circle ml-2" onclick="addCategoryForDevice('{{$oneOrDevices->id}}','{{$kat->id}}')"></i> 
                                                                </div>
                                                            @endif
                                                            <span style="width: 90%;" class="text-left" onclick="showProKat('{{$kat->id}}','{{$oneOrDevices->id}}')"><strong>{{$kat->emri}}</strong></span> 
                                                        </div>
                                                    @endif
                                                    <input type="hidden" value="0" id="state{{$kat->id}}O{{$oneOrDevices->id}}">
                                                </div>
                                            </div>
                                            <div class="container prodsFoto" id="prodsKatFoto{{$kat->id}}O{{$oneOrDevices->id}}" style="display:none;">
                                                @foreach(Produktet::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$kat->id)->get()->sortByDesc('visits') as $ketoProd)
                                                    <div class="row p-2" id="catProds{{$ketoProd->id}}O{{$oneOrDevices->id}}">
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
                                                                            <!-- <button class="btn mt-2 noBorder" type="button" >
                                                                                <i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i>
                                                                            </button> -->
                                                                            <?php $prodAccess = orderServingDevicesAccess::where([['deviceId',$oneOrDevices->id],['accessType','2'],['prodCatId',$ketoProd->id]])->first();?>
                                                                            @if($prodAccess != NULL)
                                                                            <button onclick="removeProductForDevice('{{$prodAccess->id}}','{{$oneOrDevices->id}}','{{$ketoProd->id}}','{{$kat->id}}')"
                                                                            class="btn-block btn btn-success shadow-none" id="addProductForCookBtn{{$oneOrDevices->id}}O{{$ketoProd->id}}">Registrieren</button>
                                                                            @else
                                                                            <button onclick="addProductForDevice('{{$oneOrDevices->id}}','{{$ketoProd->id}}','{{$kat->id}}')" 
                                                                            class="btn-block btn btn-outline-success shadow-none" id="addProductForCookBtn{{$oneOrDevices->id}}O{{$ketoProd->id}}">Registrieren</button>
                                                                            @endif
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            </div>
                    
                        </div>
                    </div>
                </div>
            @endforeach

        @endif
    </div>

</div>

@include('adminPanel.orderServingDevices.indexJS')