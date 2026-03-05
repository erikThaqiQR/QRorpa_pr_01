<?php
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
    use App\Restorant;
    use App\Produktet;
    use App\PicLibrary;
?>
<script>
    var lastTel="";
</script>







<!-- Add category modal -->
<div class="modal  fade " id="addCatModalTel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color: rgb(39,190,175);">
                <h4 style="color: white; font-weight:bold;" class="modal-title" style="color:black;">{{__('adminP.addCategoryToRestaurant')}}</h4>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><strong>X</strong></button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>

            {{Form::open(['action' => 'KategoriController@storeAdminP', 'method' => 'post', 'enctype' => 'multipart/form-data' ,'id'=>'addCatFormTel']) }}

            {{csrf_field()}}
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group">
                    {{ Form::label(__('adminP.name'), null, ['class' => 'control-label color-black']) }}
                    {{ Form::text('emri','', ['class' => 'form-control ', 'id'=>'emriCategoryTel']) }}
                </div>

                <div id="addCatModalPhotoSelTel">
                    <div id="selPicPhase01Tel">
                        <div class="custom-file mb-3 mt-3">
                            {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                            {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFileCategoryTel']) }}
                        </div>
                        <button type="button" data-toggle="modal" data-target="#otherPictureTel"
                            style="width:100%; color:rgb(72, 81, 87); background-color:white; border:1px solid rgb(72, 81, 87);" class="p-2" >
                            {{__('adminP.chooseOneFromPlatform')}}
                        </button>
                    </div>
                    <div class="d-flex justify-content-between mt-3 mb-3" style="display:none !important;" id="selPicPhase02Tel">
                        <p style="width:10%;"></p>
                        <p style="width:30%; font-weight:bold;" class="text-center" id="selPicPhase02NameTel"></p>
                        <p style="width:30%; font-weight:bold;" class="text-center" id="selPicPhase02TitleTel"></p>
                        <button type="button" style="width:20%;" class="btn btn-outline-danger" onclick="resetAddCatModalPIcLibTel()">{{__('adminP.resetToDefault')}}</button>
                        <p style="width:10%;"></p>
                    </div>

                    <div class="row mt-3 mb-2" >
                        <div class="col-12 form-check" style="margin-left: 15px;">
                            <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToCatClChngRes()" id="catAccessByClientsRes" checked>
                            <label class="form-check-label pl-2" for="catAccessByClientsRes"><strong>Kundenzugang zur Kategorie (Restaurant)</strong></label>
                            <input type="hidden" id="catAccessByClientsValRes" name="catAccessByClientsValRes" value="1">
                        </div>
                    </div>
                    
                    <div class="row mt-1 mb-2" >
                        <div class="col-12 form-check" style="margin-left: 15px;">
                            <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToCatClChngTA()" id="catAccessByClientsTA" checked>
                            <label class="form-check-label pl-2" for="catAccessByClientsTA"><strong>Kundenzugang zur Kategorie (Takeaway)</strong></label>
                            <input type="hidden" id="catAccessByClientsValTA" name="catAccessByClientsValTA" value="1">
                        </div>
                    </div>

                    <div class="row mt-1 mb-2" >
                        <div class="col-12 form-check" style="margin-left: 15px;">
                            <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToCatClChngDE()" id="catAccessByClientsDE" checked>
                            <label class="form-check-label pl-2" for="catAccessByClientsDE"><strong>Kundenzugang zur Kategorie (Lieferung)</strong></label>
                            <input type="hidden" id="catAccessByClientsValDE" name="catAccessByClientsValDE" value="1">
                        </div>
                    </div>
                    
                    {{ Form::hidden('photoFrom',1, ['class' => 'form-control' , 'id' => 'photoFromIDTel']) }}
                    {{ Form::hidden('photo','', ['class' => 'form-control', 'id' => 'photoIDTel']) }}
                </div>
                           

                <div class="form-group">
                    <input type="hidden" name="restaurant" class="form-control" id="resKatTel" value="{{$theResId}}"></input>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'form-control' ,'style' => 'background-color:rgb(39,190,175); font-weight:bold; color:white;']) }}

            </div>
            {{Form::close() }}

        </div>
    </div>
</div>



 <!-- The Pic library Modal -->
 <div class="modal" id="otherPictureTel" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
                    <input onkeyup="searchPicTel(this.value)" type="text" class="form-control" placeholder="{{__('adminP.search')}}" aria-describedby="basic-addon1">
                </div>

                <!-- Modal body -->
                <div class="modal-body d-flex flex-wrap justify-content-start" id="allLibPicsTel">
                    @foreach(PicLibrary::all()->sortByDesc('created_at') as $pl)
                        <div style="width:33%;  border:1px solid lightgray;" class="mb-1 selectPic" data-dismiss="modal" onclick="selectPicForCatLibTel('{{$pl->picLPhoto}}','{{$pl->picLTitle}}')">
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


    <script>
        function selectPicForCatLibTel(pic,title){
            $('#selPicPhase01Tel').html("");
            $('#selPicPhase02Tel').css("display", "block");
            $('#selPicPhase02NameTel').html(pic);
            $('#selPicPhase02TitleTel').html("\" "+title+" \"");

            $('#photoFromIDTel').val('2');
            $('#photoIDTel').val(pic);
        }
        function resetAddCatModalPIcLibTel(){
            $("#addCatModalPhotoSelTel").load(location.href+" #addCatModalPhotoSelTel>*","");
        }

        function accessToCatClChngRes(){
            if($("#catAccessByClientsRes").is(':checked')){ $('#catAccessByClientsValRes').val('1');
            }else{ $('#catAccessByClientsValRes').val('0'); }
        }

        function accessToCatClChngTA(){
            if($("#catAccessByClientsTA").is(':checked')){ $('#catAccessByClientsValTA').val('1');
            }else{ $('#catAccessByClientsValTA').val('0'); }
        }

        function accessToCatClChngDE(){
            if($("#catAccessByClientsDE").is(':checked')){ $('#catAccessByClientsValDE').val('1');
            }else{ $('#catAccessByClientsValDE').val('0'); }
        }

        function searchPicTel(input){
            if(input == ''){
                $("#allLibPicsTel").load(location.href+" #allLibPicsTel>*","");
            }else{
                $.ajax({
                    url: '{{ route("PicLibrary.search") }}',
                    method: 'post',
                    data: {
                        searchWord: input,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                    
                        $('#allLibPicsTel').html('');
                        var listing ='';

                        $.each(JSON.parse(res), function(index, value){
                            listing = '<div style="width:14.1%;  border:1px solid lightgray;" class="mr-1 selectPic" data-dismiss="modal" onclick="selectPicForCatLibTel(\''+value.picLPhoto+'\',\''+value.picLTitle+'\')">'+
                                '<img style="width:100%; height:150px; border-radius:50%;" src="storage/PicLibrary/'+value.picLPhoto+'" alt="">'+
                                    '<div class="d-flex">'+
                                        '<p class="text-center" style="width:80%; font-size:17px;"><strong> '+value.picLTitle+' </strong></p>'+
                                        '<p class="text-center" style="width:20%; border-left:1px solid lightgray; border-bottom:1px solid lightgray;">'+value.picLExt+' </p>'+
                                    '</div>'+
                                '</div>';

                            $('#allLibPicsTel').append(listing); 

                        });
                    
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }
        }

    </script>















































<!-- Add product modal  -->

<div class="modal  fade " id="addProduktModalTel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-lg pb-5">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color: rgb(39,190,175);">
                <h4 style="color: white; font-weight:bold;" class="modal-title">{{__('adminP.addNewProductForRestaurant')}}</h4>
                <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>

            {{Form::open(['action' => 'ProduktController@storeAdminP', 'method' => 'post' ]) }}
            <!-- Modal body -->
            <div class="modal-body" id="addProduktModalBodyTel">
                <div class="form-group">
                    {{ Form::label(__('adminP.name'), null, ['class' => 'control-label']) }}
                    {{ Form::text('emri','', ['class' => 'form-control shadow-none']) }}
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label(__('adminP.description'), null , ['class' => 'control-label']) }}
                            {{ Form::textarea('pershkrimi','', ['class' => 'form-control shadow-none', 'rows'=>'3']) }}
                        </div>
                    </div>
                </div>

                <div class="row mt-1 mb-2" >
                    <div class="col-12 form-check" style="margin-left: 15px;">
                        <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng()" id="accessByClients" checked>
                        <label class="form-check-label pl-2" for="accessByClients"><strong>Kunden können dieses Produkt bestellen</strong></label>
                        <input type="hidden" id="accessByClientsVal" name="accessByClientsVal" value="1">
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            {{ Form::label(__('adminP.price'), null , ['class' => 'control-label']) }}
                            {{ Form::number('qmimi','', ['class' => 'form-control shadow-none', 'step'=>'0.01' , 'min'=>'0']) }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="control-label">{{__('adminP.price')}} {{Restorant::find($theResId)->secondPriceTime}}+ ({{__('adminP.optional')}})</label>
                            <!-- {{ Form::label('Preis 20:01+ (Optional)', null , ['class' => 'control-label']) }} -->
                            {{ Form::number('qmimi2','', ['class' => 'form-control shadow-none', 'step'=>'0.01' , 'min'=>'0']) }}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="kat">{{__('adminP.chooseCategory')}}</label>
                            <select id="kategoriResetOnUpdateTel" name="kategoria" class="form-control" onchange="showExtrasTel(this.value, '{{$theResId}}')" required>
                                <?php
                                foreach (kategori::where('toRes', $theResId)->get() as $kats) {
                                    echo '  <option value="' . $kats->id . '">' . $kats->emri . '</option>';
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" style="width:48%;" id="furTakeawayAddProModalTel" onclick="selFurTakeawayAddProModalTel()" class="btn btn-outline-success">
                        <strong>+{{__('adminP.releaseForTakeawway')}}</strong>
                    </button>
                    <button type="button" style="width:48%;" id="furDeliveryAddProModalTel" onclick="selFurDeliveryAddProModalTel()" class="btn btn-outline-success">
                        <strong>+{{__('adminP.releaseForDelivery')}}</strong>
                    </button>
                </div>

                <div id="addProduktModalBodyExTyTel">
                @php
                    $step = 1;
                @endphp
                @foreach (kategori::where('toRes', $theResId)->get() as $kats)
                    <div id="BoxKat{{$kats->id}}R{{$theResId}}Tel" style="display:none;">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <ul class="list-group list-group-flush">
                                        @foreach (ekstra::where('toCat', '=', $kats->id)->get() as $ekstras)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span style="width:80%;"> <strong>{{$ekstras->emri}}</strong> ({{$ekstras->qmimi}} {{__('adminP.currencyShow')}})</span>
                                                <label style="width:19%;" class="text-right switch ">
                                                  <input type="checkbox" class="success" id="extP{{$ekstras->id}}Tel" 
                                                        onchange="addThisTel(this.id, '{{$ekstras->emri}}','{{$ekstras->qmimi}}','{{$theResId}}','{{$ekstras->id}}')">
                                                        <span class="slider round"></span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="list-group list-group-flush">
                                        @foreach (LlojetPro::where('kategoria', '=', $kats->id)->get() as $proLl)
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span style="width:80%;"><strong>{{$proLl->emri}}</strong> ({{$proLl->vlera}} X)</span>
                                                <label  style="width:19%;" class="text-right switch">
                                                    <input type="checkbox" class="success" id="LlPro{{$proLl->id}}Tel" 
                                                        onchange="addThis2Tel(this.id,'{{$proLl->emri}}','{{$proLl->vlera}}','{{$theResId}}','{{$proLl->id}}')">
                                                    <span class="slider round"></span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>

         

                <!-- <input type="hidden" emri="extPro" id="extPro"> -->
                {{ Form::hidden('extPro', '' , ['id' => 'extPro'.$theResId.'Tel']) }}
                {{ Form::hidden('typePro', '' , ['id' => 'typePro'.$theResId.'Tel']) }}
                {{ Form::hidden('restaurant', $theResId , ['id' => 'restaurantTel']) }}

                {{ Form::hidden('forTakeawayToo', 0 , ['id' => 'forTakeawayTooTel']) }}
                {{ Form::hidden('forDeliveryToo', 0 , ['id' => 'forDeliveryTooTel']) }}
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'form-control btn', 'style' => 'background-color:rgb(39,190,175); font-weight:bold; color:white;']) }}
                <br><br>
            </div>
            {{Form::close() }}

            

            <div class="d-flex justify-content-around mb-3" style="margin-top:-40px ;">
                <button class="btn shadow-none" style='background-color:rgb(39,190,175); font-weight:bold; color:white; width:46%;' data-toggle="modal" data-target="#addExtModalProdTel">
                    <strong>{{__('adminP.addExtra')}}</strong>
                </button>
                <button class="btn shadow-none" style='background-color:rgb(39,190,175); font-weight:bold; color:white; width:46%;' data-toggle="modal" data-target="#addTypeModalProdTel">
                    <strong>{{__('adminP.addType')}}</strong>
                </button>
            </div>

        </div>
      
    </div>
</div>





<script>

    function showExtrasTel(value,resId){
        if(value == 0){
            if(lastTel != ''){
                document.getElementById(lastTel).style.display="none";
            }
        }else{
            if(lastTel != ''){
                document.getElementById(lastTel).style.display="none";
            }
            var show = 'BoxKat'+value+'R'+resId+'Tel';
            lastTel = show;
            document.getElementById(show).style.display="block";
        }
    }

    function selFurTakeawayAddProModalTel(){
        if($('#forTakeawayTooTel').val() == 0){
            $('#furTakeawayAddProModalTel').attr('class','btn btn-success');
            $('#forTakeawayTooTel').val(1);
        }else if($('#forTakeawayTooTel').val() == 1){
            $('#furTakeawayAddProModalTel').attr('class','btn btn-outline-success');
            $('#forTakeawayTooTel').val(0);
        }
    }

    function selFurDeliveryAddProModalTel(){
        if($('#forDeliveryTooTel').val() == 0){
            $('#furDeliveryAddProModalTel').attr('class','btn btn-success');
            $('#forDeliveryTooTel').val(1);
        }else if($('#forDeliveryTooTel').val() == 1){
            $('#furDeliveryAddProModalTel').attr('class','btn btn-outline-success');
            $('#forDeliveryTooTel').val(0);
        }
    }

    function accessToClChng(){
        if($("#accessByClients").is(':checked')){
            $('#accessByClientsVal').val('1');
        }else{
            $('#accessByClientsVal').val('0');
        }
    }

    function addThisTel(theId,name,price,resId,extId){
        var checkBox = document.getElementById(theId);
        var extPro = document.getElementById('extPro'+resId+'Tel');
        var extProValue = document.getElementById('extPro'+resId+'Tel').value;

        if(extProValue == ""){
            var add = extId+'||'+name+'||'+price;
            if(checkBox.checked == true){
                extPro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
            var add = extId+'||'+name+'||'+price;
            if(checkBox.checked == true){
                extPro.value =extProValue+'--0--'+add;
            }else{
            var newVal = extProValue.replace(add,'');
            extPro.value = newVal;
            }
        }  
    }

    function addThis2Tel(theId,name,value,resId,llId){
        var checkBox = document.getElementById(theId);
        var typePro = document.getElementById('typePro'+resId+'Tel');
        var typeProValue = document.getElementById('typePro'+resId+'Tel').value;
        if(typeProValue == ""){
            var add = llId+'||'+name+'||'+value;
            if(checkBox.checked == true){
                typePro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
            var add =llId+'||'+name+'||'+value;
            if(checkBox.checked == true){
                typePro.value =typeProValue+'--0--'+add;
            }else{
            var newVal = typeProValue.replace(add,'');
            typePro.value = newVal;
            }
        }
    }
</script>












































<div class="modal" id="addExtModalTel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{__('adminP.addNewIngredient')}}</h4>
                <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap justify-content-start">
                    <div style="width:49.5%; margin:0 0.25% 0 0.25%;" class="form-group">
                        <label for="exampleFormControlInput1">{{__('adminP.name')}}</label>
                        <input type="text" class="form-control shadow-none" id="extraEmri">
                    </div>
                    <div style="width:49.5%; margin:0 0.25% 0 0.25%;" class="form-group">
                        <label for="exampleFormControlInput1">{{__('adminP.price')}}</label>
                        <input type="number" class="form-control shadow-none" id="extraQmimi">
                    </div>

                    <button onclick="selectAllCatForExtra()" id="selectAllCatForExtraBtn" style="width:99%; margin:3px 0.5% 3px 0.5%;" 
                        class="btn btn-outline-success shadow-none"><strong>Alle Kategorien auswählen</strong>
                    </button>
                    @foreach(kategori::where('toRes',$theResId)->get() as $kate)
                        <button onclick="selectThisCatForExtra('{{$kate->id}}')" id="selectThisCatForExtraBtn{{$kate->id}}"
                            style="width:49%; margin:3px 0.5% 3px 0.5%; font-size:0.7rem;" class="btn btn-outline-success shadow-none selectThisCatForExtraBtnAll"><strong>{{$kate->emri}}</strong>
                        </button>
                    @endforeach
                </div>
            </div>
            <button class="btn btn-dark mt-2 mb-2 shadow-none" onclick="saveExtra()"><strong>Registrieren</strong></button>

            <div class="alert alert-danger text-center mt-2 mb-2" id="addExtModalErr01" style="width:99%; margin:3px 0.5% 3px 0.5%; display:none;">
                <strong>Die von Ihnen angegebenen Daten sind nicht korrekt!</strong>
            </div>

            <div class="alert alert-success text-center mt-2 mb-2" id="addExtModalSucc01" style="width:99%; margin:3px 0.5% 3px 0.5%; display:none;">
                <strong>Sie haben x zusätzliche Produkte erfolgreich registriert!</strong>
            </div>

        </div>
    </div>
</div>
<input type="hidden" value="0" id="selectedCatForExtra">

<script>
    function saveExtra(){
        if(!$('#extraEmri').val() || !$('#extraQmimi').val() || $('#selectedCatForExtra').val() == 0){
            if($('#addExtModalErr01').is(':hidden')){ $('#addExtModalErr01').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
                url: '{{ route("ekstras.store") }}',
                method: 'post',
                data: {
                    extraEmri: $('#extraEmri').val(),
                    extraQmimi: $('#extraQmimi').val(),
                    extraKategoriSel: $('#selectedCatForExtra').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    respo = $.trim(respo);
                    $('#extraEmri').val('');
                    $('#extraQmimi').val('');
                    $('#addExtModalSucc01').html('Sie haben '+respo+' zusätzliche Produkte erfolgreich registriert');
                    if($('#addExtModalSucc01').is(':hidden')){ $('#addExtModalSucc01').show(50).delay(4000).hide(50); }

                    $("#entityButonsTel").load(location.href+" #entityButonsTel>*","");
                    $("#addProduktModalBodyExTyTel").load(location.href+" #addProduktModalBodyExTyTel>*","");
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    // ------------------------------------------------------------------------------------------------------------------------------------
    
    function selectAllCatForExtra(){
        $('#selectedCatForExtra').val('0');
        var SelectedCat = $('#selectedCatForExtra').val();


        $('.selectThisCatForExtraBtnAll').each(function(i, theBtn) {
            var theId = $(theBtn).attr('id');
            var theId2D = theId.split('ExtraBtn');
            
            if(SelectedCat == 0){
                SelectedCat = theId2D[1];
            }else{
                SelectedCat = SelectedCat+'|||'+theId2D[1];
            }
            $(theBtn).removeClass('btn-outline-success');
            $(theBtn).addClass('btn-success');
            $(theBtn).attr('onclick','deselectThisCatForExtra(\''+theId2D[1]+'\')');
            $(theBtn).prop('disabled', true);
        });

        $('#selectedCatForExtra').val(SelectedCat);

        $('#selectAllCatForExtraBtn').html('<strong>Alle Kategorien abwählen</strong>');
        $('#selectAllCatForExtraBtn').attr('onclick','deselectAllCatForExtra()');
        $('#selectAllCatForExtraBtn').removeClass('btn-outline-success');
        $('#selectAllCatForExtraBtn').addClass('btn-success');
    }

    function deselectAllCatForExtra(){
        $('#selectedCatForExtra').val('0');
        $('.selectThisCatForExtraBtnAll').each(function(i, theBtn) {
            var theId = $(theBtn).attr('id');
            var theId2D = theId.split('ExtraBtn');
            $(theBtn).removeClass('btn-success');
            $(theBtn).addClass('btn-outline-success');
            $(theBtn).attr('onclick','selectThisCatForExtra(\''+theId2D[1]+'\')');
            $(theBtn).prop('disabled', false);
        });

        $('#selectAllCatForExtraBtn').html('<strong>Alle Kategorien auswählen</strong>');
        $('#selectAllCatForExtraBtn').attr('onclick','selectAllCatForExtra()');
        $('#selectAllCatForExtraBtn').removeClass('btn-success');
        $('#selectAllCatForExtraBtn').addClass('btn-outline-success');
    }

    // ------------------------------------------------------------------------------------------------------------------------------------

    function selectThisCatForExtra(catId){
        var SelectedCat = $('#selectedCatForExtra').val();
        if(SelectedCat == 0){
            $('#selectedCatForExtra').val(catId);
        }else{
            $('#selectedCatForExtra').val(SelectedCat+'|||'+catId);
        }

        $('#selectThisCatForExtraBtn'+catId).attr('onclick','deselectThisCatForExtra(\''+catId+'\')');
        $('#selectThisCatForExtraBtn'+catId).removeClass('btn-outline-success');
        $('#selectThisCatForExtraBtn'+catId).addClass('btn-success');

    }

    function deselectThisCatForExtra(catId){
        var SelectedCat = $('#selectedCatForExtra').val();
        var NewSelectedCat = "0";
        $.each(SelectedCat.split('|||'), function( index, catIdSel ) {
            if(catIdSel != catId){
                if(NewSelectedCat == "0"){
                    NewSelectedCat = catIdSel;
                }else{
                    NewSelectedCat = NewSelectedCat+'|||'+catIdSel;
                }
            }
        });
        $('#selectedCatForExtra').val(NewSelectedCat);

        $('#selectThisCatForExtraBtn'+catId).attr('onclick','selectThisCatForExtra(\''+catId+'\')');
        $('#selectThisCatForExtraBtn'+catId).removeClass('btn-success');
        $('#selectThisCatForExtraBtn'+catId).addClass('btn-outline-success');

    }
    // ------------------------------------------------------------------------------------------------------------------------------------
</script>

















































<div class="modal  fade" id="addTypeModalTel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color:rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius:25px;">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color:rgb(39,190,175);">
                <h4 style="color: white; font-weight:bold;" class="modal-title">{{__('adminP.addNewType')}}</h4>
                <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>

            {{Form::open(['action' => 'LlojetProController@storeAdminP', 'method' => 'post' ]) }}
            <!-- Modal body -->
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-1">

                        </div>
                        <div class="col-10">
                            <div class="form-group">
                                {{ Form::label(__('adminP.name'), null, ['class' => 'control-label']) }}
                                {{ Form::text('emri','', ['class' => 'form-control']) }}
                            </div>

                            {{csrf_field()}}

                            <div class="form-group">
                                <label for="sel1">{{__('adminP.category')}}</label>
                                <select name="toCat" class="form-control">
                                    <?php
                                    foreach (kategori::where('toRes', $theResId)->get() as $kate) {
                                        echo '<option value="' . $kate->id . '">' . $kate->emri . '</option>';
                                    }
                                    ?>

                                </select>
                            </div>

                            <div class="form-group">
                                {{ Form::label(__('adminP.value'), null , ['class' => 'control-label']) }}
                                {{ Form::number('vlera','', ['class' => 'form-control', 'step'=>'0.0000000001', 'min' => '0']) }}
                            </div>

                            {{ Form::hidden('restaurant', $theResId, ['class' => 'custom-file-input']) }}

                        </div>
                        <div class="col-1">

                        </div>
                    </div>
                </div>
                {{ Form::hidden('page', $theResId, ['class' => 'custom-file-input']) }}

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'form-control btn','style' => 'background-color:rgb(39,190,175); font-weight:bold; color:white;']) }}

            </div>
            {{Form::close()}}

        </div>
    </div>
</div>

























<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>