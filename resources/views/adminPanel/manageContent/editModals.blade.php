<?php
    use App\kategori;
    use App\Produktet;
    use App\ekstra;
    use App\LlojetPro;  
    use App\PicLibrary;
    use App\Restorant;    
?>



<!-- Edit modal's -->
@foreach(kategori::where('toRes',Auth::user()->sFor)->get(); as $kategorit)

    <div class="modal  fade " id="editThisCategory{{$kategorit->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content color-black">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color: rgb(39,190,175);;">
                <h4 style="color: white;" class="modal-title">{{__('adminP.youEdit')}} "{{$kategorit->emri}}"</h4>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><strong>X</strong></button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>

            {{Form::open(['action' => ['KategoriController@updateAdminP', $kategorit->id], 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                {{csrf_field()}}    
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label(__('adminP.newName'),null , ['class' => 'control-label']) }}
                        {{ Form::text('emri',$kategorit->emri, ['class' => 'form-control']) }}
                    </div>
                    <div id="addCatModalPhotoSel{{$kategorit->id}}">
                        <div id="selPic{{$kategorit->id}}Phase01">
                            <div class="custom-file mb-3 mt-3">
                                {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                                {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFileCategory'.$kategorit->id]) }}
                            </div>
                            <button type="button" data-toggle="modal" data-target="#otherPicture{{$kategorit->id}}"
                                style="width:100%; color:rgb(72, 81, 87); background-color:white; border:1px solid rgb(72, 81, 87);" class="p-2" >
                                {{__('adminP.chooseOneFromPlatform')}}
                            </button>
                        </div>
                        <div class="d-flex justify-content-between mt-3 mb-3" style="display:none !important;" id="selPic{{$kategorit->id}}Phase02">
                            <p style="width:10%;"></p>
                            <p style="width:30%; font-weight:bold;" class="text-center" id="selPic{{$kategorit->id}}Phase02Name"></p>
                            <p style="width:30%; font-weight:bold;" class="text-center" id="selPic{{$kategorit->id}}Phase02Title"></p>
                            <button type="button" style="width:20%;" class="btn btn-outline-danger" onclick="resetAddCatModalPIcLib('{{$kategorit->id}}')">{{__('adminP.resetToDefault')}}</button>
                            <p style="width:10%;"></p>
                        </div>

                        <div class="row mt-3 mb-2" >
                            <div class="col-12 form-check" style="margin-left: 15px;">
                                <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToCatClChngEditRes('{{$kategorit->id}}')" id="catAccessByClientsEditRes{{$kategorit->id}}" checked>
                                <label class="form-check-label pl-2" style="font-size:1.3rem;" for="catAccessByClientsEditRes{{$kategorit->id}}"><strong>Kundenzugang zur Kategorie (Restaurant)</strong></label>
                                <input type="hidden" id="catAccessByClientsValEditRes{{$kategorit->id}}" name="catAccessByClientsValEditRes" value="1">
                            </div>
                        </div>
                        <div class="row mt-1 mb-2" >
                            <div class="col-12 form-check" style="margin-left: 15px;">
                                <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToCatClChngEditTA('{{$kategorit->id}}')" id="catAccessByClientsEditTA{{$kategorit->id}}" checked>
                                <label class="form-check-label pl-2" style="font-size:1.3rem;" for="catAccessByClientsEditTA{{$kategorit->id}}"><strong>Kundenzugang zur Kategorie (Takeaway)</strong></label>
                                <input type="hidden" id="catAccessByClientsValEditTA{{$kategorit->id}}" name="catAccessByClientsValEditTA" value="1">
                            </div>
                        </div>
                        <div class="row mt-1 mb-2" >
                            <div class="col-12 form-check" style="margin-left: 15px;">
                                <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToCatClChngEditDE('{{$kategorit->id}}')" id="catAccessByClientsEditDE{{$kategorit->id}}" checked>
                                <label class="form-check-label pl-2" style="font-size:1.3rem;" for="catAccessByClientsEditDE{{$kategorit->id}}"><strong>Kundenzugang zur Kategorie (Lieferung)</strong></label>
                                <input type="hidden" id="catAccessByClientsValEditDE{{$kategorit->id}}" name="catAccessByClientsValEditDE" value="1">
                            </div>
                        </div>
                    
                        
                        {{ Form::hidden('photoFrom',1, ['class' => 'form-control' , 'id' => 'photoFromID'.$kategorit->id]) }}
                        {{ Form::hidden('photo','', ['class' => 'form-control', 'id' => 'photoID'.$kategorit->id]) }}
                    </div>
                </div>

                {{ Form::hidden('page', $theResId , ['class' => 'custom-file-input']) }}

                <!-- Modal footer -->
                <div class="modal-footer">
                    {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'form-control btn', 'style' => 'background-color:rgb(39,190,175); font-weight:bold; color:white;']) }}
                </div>
            {{Form::close() }}

            </div>
        </div>
    </div>




     <!-- The Pic library Modal -->
    <div class="modal" id="otherPicture{{$kategorit->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color:rgb(39,190,175);">
                        <h4 class="modal-title" style="color:white; font-weight:bold;">{{__('adminP.chooseOneFromPlatform')}}</h4>
                        <button type="button" class="close" style="color:white;" data-dismiss="modal">X</button>
                    </div>

                    <div class="input-group mb-2 mt-2" style="width: 60%; margin-left:20%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                        </div>
                        <input onkeyup="searchPic(this.value)" type="text" class="form-control" placeholder="{{__('adminP.search')}}" aria-describedby="basic-addon1">
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body d-flex flex-wrap justify-content-start" id="allLibPics{{$kategorit->id}}">
                        @foreach(PicLibrary::all()->sortByDesc('created_at') as $pl)
                            <div style="width:14.1%;  border:1px solid lightgray;" class="mr-1 selectPic" data-dismiss="modal" onclick="selectPicForCatLib('{{$pl->picLPhoto}}','{{$pl->picLTitle}}','{{$kategorit->id}}')">
                                <img style="width:100%; height:150px; border-radius:50%;" src="storage/PicLibrary/{{$pl->picLPhoto}}" alt="">
                                <div class="d-flex">
                                    <p class="text-center" style="width:80%; font-size:17px;"><strong> {{$pl->picLTitle}} </strong></p>
                                    <p class="text-center" style="width:20%; border-left:1px solid lightgray; border-bottom:1px solid lightgray;">{{$pl->picLExt}} </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    
@endforeach





<script>
        function selectPicForCatLib(pic,title, catId){
            $('#selPic'+catId+'Phase01').html("");
            $('#selPic'+catId+'Phase02').css("display", "block");
            $('#selPic'+catId+'Phase02Name').html(pic);
            $('#selPic'+catId+'Phase02Title').html("\" "+title+" \"");

            $('#photoFromID'+catId).val('2');
            $('#photoID'+catId).val(pic);
        }

        function resetAddCatModalPIcLib(catId){
            $("#addCatModalPhotoSel"+catId).load(location.href+" #addCatModalPhotoSel"+catId+">*","");
        }

        
        function accessToCatClChngEditRes(catId){
            if($("#catAccessByClientsEditRes"+catId).is(':checked')){ $('#catAccessByClientsValEditRes'+catId).val('1');
            }else{ $('#catAccessByClientsValEditRes'+catId).val('0'); }
        }

        function accessToCatClChngEditTA(catId){
            if($("#catAccessByClientsEditTA"+catId).is(':checked')){ $('#catAccessByClientsValEditTA'+catId).val('1');
            }else{ $('#catAccessByClientsValEditTA'+catId).val('0'); }
        }

        function accessToCatClChngEditDE(catId){
            if($("#catAccessByClientsEditDE"+catId).is(':checked')){ $('#catAccessByClientsValEditDE'+catId).val('1');
            }else{ $('#catAccessByClientsValEditDE'+catId).val('0'); }
        }

</script>





























<!-- Edit modals -->

@foreach (Produktet::Where('toRes',Auth::user()->sFor)->get() as $pro)
    <div class="modal  fade " id="editProduktModal{{$pro->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        <div class="modal-dialog modal-lg pb-5">
            <div class="modal-content pb-5">
                <!-- Modal Header -->

                <div class="modal-header" style="background-color: rgb(39,190,175);">
                    <h4 style="color: white; font-weight:bold;" class="modal-title">{{__('adminP.youAreEditingProduct')}}: <strong>{{$pro->emri}}</strong></h4>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>

                {{Form::open(['action' => 'ProduktController@editAdminP', 'method' => 'post' ]) }}
                <!-- Modal body -->
                <div class="modal-body" >

                    <input type="hidden" value="{{$pro->id}}" name="editProId">
                    <div class="form-group">
                        {{ Form::label(__('adminP.name'), null, ['class' => 'control-label']) }}
                        {{ Form::text('emri',$pro->emri, ['class' => 'form-control shadow-none']) }}
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {{ Form::label(__('adminP.description'), null , ['class' => 'control-label']) }}
                                {{ Form::textarea('pershkrimi',$pro->pershkrimi, ['class' => 'form-control shadow-none', 'rows'=>'3']) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-1 mb-2" >
                        <div class="col-12 form-check" style="margin-left: 15px;">
                            @if($pro->accessableByClients == 1)
                            <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$pro->id}}')" id="accessByClients{{$pro->id}}" checked>
                            <label class="form-check-label pl-2" style="font-size:1.3rem;" for="accessByClients{{$pro->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                            <input type="hidden" id="accessByClientsVal{{$pro->id}}" name="accessByClientsVal" value="1">
                            @else
                            <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$pro->id}}')" id="accessByClients{{$pro->id}}">
                            <label class="form-check-label pl-2" style="font-size:1.3rem;" for="accessByClients{{$pro->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                            <input type="hidden" id="accessByClientsVal{{$pro->id}}" name="accessByClientsVal" value="0">
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                {{ Form::label(__('adminP.price'), null , ['class' => 'control-label']) }}
                                {{ Form::number('qmimi',$pro->qmimi, ['class' => 'form-control shadow-none', 'step'=>'0.01' , 'min'=>'0']) }}
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                @php
                                    if($pro->qmimi2 != 999999){$price2 = $pro->qmimi2;}else{$price2 = '';}
                                @endphp
                                <label for="" class="control-label">Preis {{Restorant::find($theResId)->secondPriceTime}}+ (Optional)</label>
                                <!-- {{ Form::label('Preis 20:01+ (Optional)', null , ['class' => 'control-label']) }} -->
                                {{ Form::number('qmimi2',$price2, ['class' => 'form-control shadow-none', 'step'=>'0.01' , 'min'=>'0']) }}
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="kat">{{__('adminP.chooseCategory')}}</label>
                                <select name="kategoriaProdEdit" class="form-control" onchange="resetExtraTypes(this.value, '{{$theResId}}','{{$pro->id}}')">
                                    <option value="0">{{__('adminP.chooseCategory')}}...</option>
                                    @foreach (kategori::where('toRes', $theResId)->get() as $kats)
                                        @if($kats->id == $pro->kategoria)
                                            <option value="{{$kats->id}}" selected>{{$kats->emri}}</option>
                                        @else
                                            <option value="{{$kats->id}}">{{$kats->emri}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="editProduktModalBodyExTy">
                        <?php
                            $step = 1;
                            $thisProdsExtras = array();
                            $thisProdsExtrasIn = '';
                            $thisProdsTypes = array();
                            $thisProdsTypesIn = '';
                            foreach(explode('--0--',$pro->extPro) as $prEx){
                                $prEx2D = explode('||',$prEx);
                                array_push( $thisProdsExtras,$prEx2D[0]);
                                if($thisProdsExtrasIn == ''){$thisProdsExtrasIn = $prEx2D[0];
                                }else{$thisProdsExtrasIn .= '||'.$prEx2D[0];}
                            }
                            foreach(explode('--0--',$pro->type) as $prTy){
                                $prTy2D = explode('||',$prTy);
                                array_push( $thisProdsTypes,$prTy2D[0]);
                                if($thisProdsTypesIn == ''){$thisProdsTypesIn = $prTy2D[0];
                                }else{$thisProdsTypesIn .= '||'.$prTy2D[0];}
                            }
                        ?>

                        {{ Form::hidden('thisProdsExtrasIn',$thisProdsExtrasIn, ['class' => 'form-control', 'id' => 'thisProdsExtrasIn'.$pro->id]) }}
                        {{ Form::hidden('thisProdsTypesIn',$thisProdsTypesIn, ['class' => 'form-control', 'id' => 'thisProdsTypesIn'.$pro->id]) }}
                   

                        <div id="BoxKatEdit{{$theResId}}P{{$pro->id}}">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list-group list-group-flush">
                                            @foreach (ekstra::where('toCat', '=', $pro->kategoria)->get() as $ekstras)
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span style="width:80%;"> <strong>{{$ekstras->emri}}</strong> ({{$ekstras->qmimi}} {{__('adminP.currencyShow')}})</span>
                                                    <label style="width:19%;" class="text-right switch " >
                                                        @if(in_array($ekstras->id,$thisProdsExtras))
                                                            <input type="checkbox" class="success" id="activeExtraPro{{$ekstras->id}}P{{$pro->id}}" checked 
                                                            onclick="removeThisExtraToProd('{{$ekstras->id}}','{{$pro->id}}','{{$kats->id}}','{{$theResId}}')">
                                                            <span class="slider round"></span>
                                                        @else
                                                            <input type="checkbox" class="success" id="activeExtraPro{{$ekstras->id}}P{{$pro->id}}" 
                                                            onclick="addThisExtraToProd('{{$ekstras->id}}','{{$pro->id}}','{{$kats->id}}','{{$theResId}}')">
                                                            <span class="slider round"></span>
                                                        @endif
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list-group list-group-flush">
                                            @foreach (LlojetPro::where('kategoria', '=', $pro->kategoria)->get() as $proLl)
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span style="width:80%;"><strong>{{$proLl->emri}}</strong> ({{$proLl->vlera}} X)</span>
                                                    <label  style="width:19%;" class="text-right switch">
                                                        @if(in_array($proLl->id,$thisProdsTypes))
                                                            <input type="checkbox" class="success" id="activeTypePro{{$proLl->id}}P{{$pro->id}}" checked
                                                            onclick="removeThisTypeToProd('{{$proLl->id}}','{{$pro->id}}','{{$kats->id}}','{{$theResId}}')">
                                                            <span class="slider round"></span>
                                                        @else
                                                            <input type="checkbox" class="success" id="activeTypePro{{$proLl->id}}P{{$pro->id}}"
                                                            onclick="addThisTypeToProd('{{$proLl->id}}','{{$pro->id}}','{{$kats->id}}','{{$theResId}}')">
                                                            <span class="slider round"></span>
                                                        @endif
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button class="btn btn-block btn-success">{{__('adminP.saveOnComputer')}}</button>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
@endforeach




<script>
    function addThisExtraToProd(exId , proId, catId, resId){
        var newthisProdsExtrasIn = $('#thisProdsExtrasIn'+proId).val();
       
        if(newthisProdsExtrasIn == ''){ newthisProdsExtrasIn = exId; }
        else{ newthisProdsExtrasIn += '||'+exId; }
       
        $('#thisProdsExtrasIn'+proId).val(newthisProdsExtrasIn);

        $('#activeExtraPro'+exId+'P'+proId).attr('onclick','removeThisExtraToProd(\''+exId+'\',\''+proId+'\',\''+catId+'\',\''+resId+'\')');
    }
    function removeThisExtraToProd(exId , proId, catId, resId){
        var newthisProdsExtrasIn = '';
        var data = $('#thisProdsExtrasIn'+proId).val().split('||');
        $.each( data, function( index, value ) {
            if(value != exId){
                if(newthisProdsExtrasIn == ''){ newthisProdsExtrasIn = value; }
                else{ newthisProdsExtrasIn += '||'+value; }
            }
        });
        $('#thisProdsExtrasIn'+proId).val(newthisProdsExtrasIn);

        $('#activeExtraPro'+exId+'P'+proId).attr('onclick','addThisExtraToProd(\''+exId+'\',\''+proId+'\',\''+catId+'\',\''+resId+'\')');
    }

    function addThisTypeToProd(tyId , proId, catId, resId){
        var newthisProdsTypesIn = $('#thisProdsTypesIn'+proId).val();
       
        if(newthisProdsTypesIn == ''){ newthisProdsTypesIn = tyId; }
        else{ newthisProdsTypesIn += '||'+tyId; }
       
        $('#thisProdsTypesIn'+proId).val(newthisProdsTypesIn);

        $('#activeTypePro'+tyId+'P'+proId).attr('onclick','removeThisTypeToProd(\''+tyId+'\',\''+proId+'\',\''+catId+'\',\''+resId+'\')');
    }
    function removeThisTypeToProd(tyId , proId, catId, resId){
        var newthisProdsTypeIn = '';
        var data = $('#thisProdsTypesIn'+proId).val().split('||');
        $.each( data, function( index, value ) {
            if(value != tyId){
                if(newthisProdsTypeIn == ''){ newthisProdsTypeIn = value; }
                else{ newthisProdsTypeIn += '||'+value; }
            }
        });
        $('#thisProdsTypesIn'+proId).val(newthisProdsTypeIn);

        $('#activeTypePro'+tyId+'P'+proId).attr('onclick','addThisTypeToProd(\''+tyId+'\',\''+proId+'\',\''+catId+'\',\''+resId+'\')');
    }

    function accessToClChng(pId){
        if($("#accessByClients"+pId).is(':checked')){
            $('#accessByClientsVal'+pId).val('1');
        }else{
            $('#accessByClientsVal'+pId).val('0');
        }
    }

    function resetExtraTypes(katId, resId, proId){

        $('#BoxKatEdit'+resId+'P'+proId).html('<img style="width:40%; margin-left:30%;" src="storage/gifs/loading.gif" alt="">');
        
        var newExTyShow = '';
        newExTyShow += '<div class="container">'
                            +'<div class="row">'
                                +'<div class="col-6">'
                                    +'<ul class="list-group list-group-flush">';
        
        $.ajax({
            method: 'POST',
            url: '{{route("dash.proEditGetEkstras")}}',
            dataType: 'json',
            data: {
                '_token': '{{csrf_token()}}',
                catId: katId,
                resId: resId
            },
            success: function(res){
                $.each(res, function(index, value){
                    newExTyShow += '<li class="list-group-item d-flex justify-content-between">'+
                                        '<span style="width:80%;"> <strong>'+value.emri+'</strong> ('+value.qmimi+ " " +$('#currencyShow').val()+ ')</span>'+
                                        '<label style="width:19%;" class="text-right switch " >'+
                                            '<input type="checkbox" class="success" id="activeExtraPro'+value.id+'P'+proId+'" onclick="addThisExtraToProd(\''+value.id+'\',\''+proId+'\',\''+katId+'\',\''+resId+'\')"><span class="slider round"></span>'+
                                        '</label>'+
                                    '</li>';
                });// end foreach extra

                newExTyShow += '    </ul>'+
                                '</div>'+
                                '<div class="col-6">'+
                                    '<ul class="list-group list-group-flush">';

                $.ajax({
                    method: 'POST',
                    url: '{{route("dash.proEditGetTypes")}}',
                    dataType: 'json',
                    data: {
                        '_token': '{{csrf_token()}}',
                        catId: katId,
                        resId: resId
                    },
                    success: function(res){
                        $.each(res, function(index, value){
                            newExTyShow += '<li class="list-group-item d-flex justify-content-between">'+
                                                '<span style="width:80%;"><strong>'+value.emri+'</strong> ('+value.vlera+' X)</span>'+
                                                '<label  style="width:19%;" class="text-right switch">'+
                                                    '<input type="checkbox" class="success" id="activeTypePro'+value.id+'P'+proId+'" onclick="addThisTypeToProd(\''+value.id+'\',\''+proId+'\',\''+katId+'\',\''+resId+'\')">'+
                                                    '<span class="slider round"></span>'+
                                                '</label>'+
                                            '</li>';
                        });// end foreach tipe
                        newExTyShow +=  '</ul>'+
                                '</div>'+
                            '</div>'+  
                        '</div>';
                        $('#BoxKatEdit'+resId+'P'+proId).html('');
                        $('#BoxKatEdit'+resId+'P'+proId).append(newExTyShow);
                    }
                });
            }
        });
        $('#thisProdsTypesIn'+proId).val('');
        $('#thisProdsExtrasIn'+proId).val('');
    }

</script>