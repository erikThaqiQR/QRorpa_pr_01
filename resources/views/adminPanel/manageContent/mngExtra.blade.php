<?php

use Illuminate\Support\Facades\Auth;
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
    use App\Restorant;
    // use App\Produktet;

    $theResId = Auth::user()->sFor;

?>
<style>
        .direktiveBox{
            color:white;
            border-radius:10px;
            margin-top:35px;
            padding-top:50px;
            padding-bottom:50px;

            background-color:rgb(39,190,175);
        }
        .direktiveBox:hover{
            cursor: pointer;
        }

        .backBtn{
            opacity:0.5;
        }
        .backBtn:hover{
            opacity:0.95;
            cursor: pointer;
        }

        .ResShow{
            background-color:rgb(39,190,175);
            color:white;
            border-radius:20px;
        }
        .ResShow:hover{
            cursor: pointer;
        }
        .anchorMy{
            color: black;
            text-decoration: none;
        }
        .anchorMy:hover{
            color: rgb(39,190,175);
            text-decoration: none;
        }

    </style>
<div class="container-fluid mb-4 " >
    <div class="row mt-4">
        <a href="dashboardContentMng" class="col-2 anchorMy pt-4" style="font-size:25px;"><strong> < {{__('adminP.back')}} </strong></a>
        <div class="col-8 text-center">
            <p style="font-size:45px;" class="color-qrorpa">"{{Restorant::find($theResId)->emri}}"  /  {{__('adminP.extras')}}</p>
        </div>
         <div class="col-2"></div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addExtModal">
            <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25"><strong>{{__('adminP.addNewPerk')}}</strong></button>
        </div>
    </div>
</div>



<div class="d-flex flex-wrap justify-content-between p-1 mb-5">
    @foreach(ekstra::where('toRes',$theResId)->get()->sortByDesc('created_at') as $extraa)
        <div style="width:49%; border:1px solid lightgray; border-radius:5px; font-size:20px;" class="text-center mb-1 d-flex justify-content-between">
            <span style="opacity:0.75; width:30%;">({{kategori::find($extraa->toCat)->emri}})</span>
            <span style="width:34%;" class="text-left"> {{$extraa->emri}} </span>
            <span style="width:12%;" class="text-left"> {{$extraa->qmimi}} {{__('adminP.currencyShow')}} </span>

            
            <button style="width:13%;" class="btn btn-outline-info btn-block" data-toggle="modal" data-target="#editExt{{$extraa->id}}">{{__('adminP.toEdit')}}</button>
                              

            <div style="width:10%;">
                {{Form::open(['action' => ['EkstraController@destroy', $extraa->id ], 'method' => 'POST', 'onsubmit' => 'Bist du sicher'])}}

                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit(__('adminP.delete'), ['class' => 'btn btn-outline-danger btn-block' ])}}

                {{ Form::hidden('page', $theResId , ['class' => 'custom-file-input']) }}

                {{Form::close()}}
            </div>
        </div>
    @endforeach
</div>

















<div class="modal" id="addExtModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
                            style="width:24%; margin:3px 0.5% 3px 0.5%;" class="btn btn-outline-success shadow-none selectThisCatForExtraBtnAll"><strong>{{$kate->emri}}</strong>
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

                    $("#entityButons").load(location.href+" #entityButons>*","");
                    $("#addProduktModalBodyExTy").load(location.href+" #addProduktModalBodyExTy>*","");
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
 



















          <!-- Edit modal's -->
          @foreach(ekstra::where('toRes',$theResId)->get() as $extr)

            <div class="modal  fade " id="editExt{{$extr->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('adminP.youEditThisExtra')}} ___"{{$extr->emri}}"-{{ sprintf('%01.2f', $extr->qmimi)}} €</h4>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
                        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    </div>

                    {{Form::open(['action' => ['EkstraController@update', $extr->id], 'method' => 'post' ]) }}
                    
                        {{csrf_field()}}

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group">
                                {{ Form::label(__('adminP.name'),null , ['class' => 'control-label']) }}
                                {{ Form::text('emri',$extr->emri, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label(__('adminP.price'), null , ['class' => 'control-label']) }}
                                {{ Form::number('qmimi', $extr->qmimi , ['class' => 'form-control', 'step'=>'0.01', 'min' => '0']) }}
                            </div>
                            <div class="form-group">
                                    <label for="sel1">{{__('adminP.whichCategoryBelong')}}</label>
                                    <select name="toCat" class="form-control" >
                                    <?php
                                        foreach(kategori::where('toRes',$theResId)->get() as $kate){
                                            if($kate->id == $extr->toCat)
                                                echo '<option value="'.$kate->id.'" selected>'.$kate->emri.'</option>';
                                            else
                                                echo '<option value="'.$kate->id.'">'.$kate->emri.'</option>';
                                        }
                                    ?>
                                        
                                    </select>
                                </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            {{ Form::submit(__('adminP.save'), ['class' => 'form-control btn btn-primary']) }}
                            
                        </div>
                    {{Form::close() }}

                    </div>
                </div>
            </div>
                
        @endforeach