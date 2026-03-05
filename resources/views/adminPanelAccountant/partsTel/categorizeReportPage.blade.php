<?php
use App\Produktet;
use App\pdfResProdCats;
use App\kategori;
use Carbon\Carbon;

    $pdfResPCats = pdfResProdCats::where([['toRes',Auth::user()->sFor],['isActive',1]])->get();
?>
 <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
 @include('fontawesome')
<style>
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



    .phoneNrSelected{
        color: white;
        border: 1px solid rgb(39,190,175);
        border-radius: 10px;
        background-color: rgb(39,190,175);
    }
    .phoneNrNotSelected{
        color: rgb(39,190,175);
        border: 1px solid rgb(39,190,175);
        border-radius: 10px;

    }

</style>

<div class="p-1 pb-5">

    <div class="d-flex flex-wrap justify-content-between mt-1 mb-1">
       
        <h5 style="color:rgb(39,190,175); width:100%;">
            <strong> 
                <a href="{{route('admWoMng.AccountantStatistics')}}" class="pl-1 pr-2" style="width: 10%; color:rgb(39,190,175);"><strong><i class="fas fa-chevron-left"></i></strong></a> 
                Berichtstabellen kategorisieren
            </strong>
        </h5>
        <button class="btn btn-success shadow-none mt-1" style="width:100%;" data-toggle="modal" data-target="#addNewGroupModal">
            <strong><i class="fa-solid fa-plus"></i> Erstellen Sie eine neue Kategorie</strong>
        </button>
    </div>




    <div class="modal" id="addNewGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top: 1%; padding-right: 17px;" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Erstellen Sie eine neue Kategorie</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                    </button>
                </div>
                <div class="modal-body" id="addNewGroupModalBody">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control shadow-none"  id="newGroupInput">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">Kategoriename</span>
                        </div>
                    </div>
                    <button class="btn btn-success shadow-none btn-block" style="margin:0px;" onclick="saveNewGroup('{{Auth::user()->sFor}}')">
                        <strong>Sparen</strong>
                    </button>
                    <div class="alert alert-danger text-center mt-1" style="display: none;" id="saveNewGroupErr01">
                        <strong>Schreiben Sie zuerst den Namen der Kategorie!</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="confGrDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top: 1%; padding-right: 17px;" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Möchten Sie diese Kategorie wirklich löschen?</strong></h5>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-between" id="confGrDeleteBody">
                    <input type="hidden" value="0" id="confGrDeleteGrId">
                    <button class="btn btn-dark shadow-none" style="width: 49%;" data-dismiss="modal"><strong>Nein</strong></button>
                    <button class="btn btn-danger shadow-none" style="width: 49%;" onclick="grDelete()"><strong>Ja</strong></button>

                    <p class="text-center mt-3" style="width:100%; color:red;">
                        <strong>Alle dieser Gruppe zugeordneten Produkte werden auf Null zurückgesetzt (nicht kategorisiert)</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function saveNewGroup(rId){
            if(!$('#newGroupInput').val()){
                if($('#saveNewGroupErr01').is(':hidden')){ $('#saveNewGroupErr01').show(100).delay(3500).hide(100); }
            }else{
                $.ajax({
					url: '{{ route("admin.saveNewGroup") }}',
					method: 'post',
					data: {
						resId: rId,
                        grName: $('#newGroupInput').val(),
						_token: '{{csrf_token()}}'
					},success: () => {
                        $("#repCategories").html('<img src="storage/gifs/loading2.gif" style="width:20%; margin-left:40%;" alt="">');
                        $("#repCategories").load(location.href+" #repCategories>*","");
                        $("#groupModalsAll").load(location.href+" #groupModalsAll>*","");

                        $("#newGroupInput").val('');
                        $("#addNewGroupModal").modal('toggle');

					},error: (error) => { console.log(error); }
				});
					
            }
        }

        function prepGrDelete(grId){
            $('#confGrDeleteGrId').val(grId);
        }

        function grDelete(){
            $.ajax({
				url: '{{ route("admin.deleteReportGroup") }}',
				method: 'post',
				data: {
                    grId: $('#confGrDeleteGrId').val(),
					_token: '{{csrf_token()}}'
				},success: () => {
                    $("#repCategories").html('<img src="storage/gifs/loading2.gif" style="width:20%; margin-left:40%;" alt="">');
                    $("#repCategories").load(location.href+" #repCategories>*","");
                    $("#groupModalsAll").load(location.href+" #groupModalsAll>*","");

                    $('#confGrDeleteGrId').val('0');
                    $("#confGrDelete").modal('toggle');

				},error: (error) => { console.log(error); }
			});
        }
    </script>



















    <hr>

    <div class="p-1 d-flex flex-wrap justify-content-between" id="repCategories">
        @if ($pdfResPCats->count() > 0)
            <p style="width: 7%;" class="text-center"><strong>ID:</strong></p>
            <p style="width: 51%;"><strong>Kategorietitel:</strong></p>
            <p style="width: 15%;" class="text-center"><strong>Nr.</strong></p>
            <p style="width: 13%;" class="text-center"><strong><i class="fa-solid fa-pen-to-square"></i></strong></p>
            <p style="width: 13%;" class="text-center"><strong><i style="color:red;" class="fa-solid fa-trash"></i></strong></p>
            <hr style="width:100%; margin:5px 0px 5px 0px;">

            @foreach ($pdfResPCats as $oneRepCat)
            <p style="width: 7%;" class="text-center">{{$oneRepCat->id}}</p>
            <p style="width: 51%;">{{$oneRepCat->catTitle}}</p>
            <p style="width: 15%;" class="text-center">{{Produktet::where([['toRes',Auth::user()->sFor],['toReportCat',$oneRepCat->id]])->count()}}</p>
            <button class="btn btn-dark shadow-none" style="width: 13%;" data-toggle="modal" data-target="#setProdsToRepCatModal{{$oneRepCat->id}}">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button class="btn btn-outline-danger shadow-none" style="width: 13%;" data-toggle="modal" data-target="#confGrDelete" onclick="prepGrDelete('{{$oneRepCat->id}}')">
                <i style="color:red;" class="fa-solid fa-trash"></i>
            </button>
            <hr style="width:100%; margin:5px 0px 5px 0px;">
            @endforeach

            <p style="width: 7%;" class="text-center">0</p>
            <p style="width: 51%;">nicht kategorisiert</p>
            <p style="width: 15%;" class="text-center">{{Produktet::where([['toRes',Auth::user()->sFor],['toReportCat',0]])->count()}}</p>
            <p style="width: 26%;"></p>
        @else
            <p style="width: 100%; font-size:1.25rem;" class="text-center"><strong>Für dieses Restaurant gibt es derzeit keine registrierten Gruppen</strong></p>
            <hr style="width:100%; margin:5px 0px 5px 0px;">
            <p style="width: 7%;" class="text-center">0</p>
            <p style="width: 51%;">nicht kategorisiert</p>
            <p style="width: 15%;" class="text-center">{{Produktet::where([['toRes',Auth::user()->sFor],['toReportCat',0]])->count()}}</p>
            <p style="width: 26%;"></p>
        @endif
        
    </div>

</div>

<div id="groupModalsAll">
    @if ($pdfResPCats != NULL)
        @foreach ($pdfResPCats as $oneRepCat)
            
            <!-- Modal -->
            <div class="modal" id="setProdsToRepCatModal{{$oneRepCat->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top: 1%; padding-right: 17px;" aria-modal="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Produkte im Bericht kategorisieren <br> <strong><span id="setProdsToRepCatCName">{{$oneRepCat->catTitle}}</span></strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closesetProdsToRepCatModal()">
                                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                            </button>
                        </div>
                        <div class="modal-body" id="setProdsToRepCatModalBody{{$oneRepCat->id}}">
                            @foreach(Kategori::where('toRes', '=', Auth::user()->sFor)->get()->sortByDesc('visits') as $kat)
                                <div style="width:100%;" class="mb-3">

                                    <div class="allKatFoto" id="KategoriFoto{{$kat->id}}">
                                        <div style="cursor: pointer; position:relative; object-fit: cover;" onclick="showProKat('{{$kat->id}}','{{$oneRepCat->id}}')">
                                            <img style="border-radius:30px; width:100%; height:120px;" src="storage/kategoriaUpload/{{$kat->foto}}"alt="">

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
                                            <input type="hidden" value="0" id="state{{$kat->id}}O{{$oneRepCat->id}}">
                                        </div>
                                    </div>

                                    <div class="container prodsFoto" id="prodsKatFoto{{$kat->id}}O{{$oneRepCat->id}}" style="display:none;">
                                        <div class="row p-2" id="prodListingAddAllCat{{$kat->id}}">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-12 product-section">
                                                        <button style="font-size: 10px; margin:0px; width:100%;" class="btn btn-dark" id="addAllCatBtn{{$kat->id}}O{{$oneRepCat->id}}"
                                                        onclick="setAllCatToRepCat('{{$kat->id}}','{{$oneRepCat->id}}','{{$oneRepCat->catTitle}}')"">
                                                            fügen Sie alle Kategorieprodukte hinzu
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach(Produktet::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$kat->id)->get()->sortByDesc('visits') as $ketoProd)
                                            <div class="row p-2" id="prodListing{{$ketoProd->id}}O{{$oneRepCat->catTitle}}">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-12 product-section">
                                                            <div class="row">
                                                                
                                                                <div class="col-10" data-toggle="modal" data-target="#ProdNew{{$ketoProd->id}}">
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
                                                                        {{__('adminP.currencyShow')}}
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
                                                                <div class="col-2 add-plus-section">
                                                                    <button style="margin:0px ;" class="btn mt-2 noBorder setProdToRepCatTitle{{$kat->id}}O{{$oneRepCat->id}}" type="button" 
                                                                    id="setProdToRepCatTitle{{$ketoProd->id}}O{{$oneRepCat->id}}">
                                                                        @if ($ketoProd->toReportCat != 0)
                                                                            <strong>{{pdfResProdCats::findOrFail($ketoProd->toReportCat)->catTitle}}</strong>
                                                                        @else
                                                                            <strong>Keiner</strong>
                                                                        @endif
                                                                    </button>
                                                                    @if ($ketoProd->toReportCat == $oneRepCat->id)
                                                                        <button style="margin:0px; width:100%;" class="btn btn-success noBorder shadow-none setProdToRepCatBtn{{$kat->id}}O{{$oneRepCat->id}}" 
                                                                        type="button" id="setProdToRepCatBtn{{$ketoProd->id}}O{{$oneRepCat->id}}" 
                                                                        onclick="setProdToRepCat('{{$ketoProd->id}}','{{$oneRepCat->id}}','{{$oneRepCat->catTitle}}')">
                                                                        <i class="fas fa-minus-square"></i>
                                                                    @else
                                                                        <button style="margin:0px; width:100%;" class="btn btn-dark noBorder shadow-none setProdToRepCatBtn{{$kat->id}}O{{$oneRepCat->id}}" 
                                                                        type="button" id="setProdToRepCatBtn{{$ketoProd->id}}O{{$oneRepCat->id}}" 
                                                                        onclick="setProdToRepCat('{{$ketoProd->id}}','{{$oneRepCat->id}}','{{$oneRepCat->catTitle}}')">

                                                                        <i class="fas fa-plus-square"></i>
                                                                    @endif
                                                                        
                                                                    </button>
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
        @endforeach
    @endif
</div>

<script>
    function closesetProdsToRepCatModal(){
        $("#setProdsToRepCatModal").load(location.href+" #setProdsToRepCatModal>*","");
    }

    function setProdToRepCat(prodId,rcId,rcTitle){
        $("#setProdToRepCatBtn"+prodId+'O'+rcId).html('<img src="storage/gifs/loading2.gif" style="width:23px; height:auto;"  alt="">');
        $.ajax({
			url: '{{ route("admin.catRepSetProdToCat") }}',
			method: 'post',
			data: {
				prId: prodId,
				repCat: rcId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                $("#repCategories").load(location.href+" #repCategories>*","");
                if(respo == 'added' || respo == 'addedPlus'){
                    $('#setProdToRepCatBtn'+prodId+'O'+rcId).removeClass('btn-dark');
                    $('#setProdToRepCatBtn'+prodId+'O'+rcId).addClass('btn-success');
                    $('#setProdToRepCatBtn'+prodId+'O'+rcId).html('<i class="fas fa-minus-square"></i>');

                    $('#setProdToRepCatTitle'+prodId+'O'+rcId).html('<strong>'+rcTitle+'</strong>');
                    
                    if(respo == 'addedPlus'){
                        // reset other modals
                        for (var n = 1; n <= 5; ++ n){
                            if(n != rcId){
                                $("#setProdsToRepCatModalBody"+n).load(location.href+" #setProdsToRepCatModalBody"+n+">*","");
                            }
                        }
                    }

                }else if(respo == 'removed'){
                    $('#setProdToRepCatBtn'+prodId+'O'+rcId).removeClass('btn-success');
                    $('#setProdToRepCatBtn'+prodId+'O'+rcId).addClass('btn-dark');
                    $('#setProdToRepCatBtn'+prodId+'O'+rcId).html('<i class="fas fa-plus-square"></i>');

                    $('#setProdToRepCatTitle'+prodId+'O'+rcId).html('<strong>Keiner</strong>');
                }
            
			},
			error: (error) => { console.log(error); }
		});			
    }

    function setAllCatToRepCat(catId,rcId,rcTitle){
        $("#addAllCatBtn"+catId+'O'+rcId).html('<img src="storage/gifs/loading2.gif" style="width:23px; height:auto;"  alt="">');
        $.ajax({
			url: '{{ route("admin.catRepSetAllCatToCat") }}',
			method: 'post',
			data: {
				catId: catId,
				repCat: rcId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                $("#repCategories").load(location.href+" #repCategories>*","");

                $('.setProdToRepCatBtn'+catId+'O'+rcId).each(function(i, theBtn) {
                    $(theBtn).removeClass('btn-dark');
                    $(theBtn).addClass('btn-success');
                    $(theBtn).html('<i class="fas fa-minus-square"></i>');
                });

                $('.setProdToRepCatTitle'+catId+'O'+rcId).each(function(i, theTitle) {
                    $(theTitle).html('<strong>'+rcTitle+'</strong>');
                });

                // reset other modals
                for (var n = 1; n <= 5; ++ n){
                    if(n != rcId){
                        $("#setProdsToRepCatModalBody"+n).load(location.href+" #setProdsToRepCatModalBody"+n+">*","");
                    }
                }
                $("#addAllCatBtn"+catId+'O'+rcId).html('fügen Sie alle Kategorieprodukte hinzu');
            },
			error: (error) => { console.log(error); }
		});
    }

    function showProKat(kId,rcId) {
        if ($('#state'+kId+"O"+rcId).val() == 0) {
            $('#prodsKatFoto'+kId+"O"+rcId).show(100);
            $('#state'+kId+"O"+rcId).val(1);

        } else {
            $('#prodsKatFoto'+kId+"O"+rcId).hide(100);
            $('#state'+kId+"O"+rcId).val(0)
        }
    }
</script>