<?php
    use App\kategori;

    $theResId = Auth::user()->sFor;
?>





<div class="modal" id="addExtModalProdTel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color: rgb(39,190,175);">
                <h4 style="color: white; font-weight:bold;" class="modal-title">{{__('adminP.addNewIngredient')}}</h4>
                <button type="button" class="btn btn-danger" onclick="closeAddExtModalProdTel()">X</button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>

        
            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger text-center" style="display: none; font-weight:bold;" id="addExtModalProdError01Tel"></div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-1">
                        </div>
                        <div class="col-10">
                            <div class="form-group">
                                <label for="" class="control-label">{{__('adminP.name')}}</label>
                                <input type="text" name="emriExtProAddTel" id="emriExtProAddTel" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="qmimiExtProAdd" class="control-label">{{__('adminP.price')}}</label>
                                <input type="number" name="qmimiExtProAddTel" id="qmimiExtProAddTel" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label for="sel1">{{__('adminP.chooseCategory')}}</label>
                                <select name="kategoriaExtProAddTel" id="kategoriaExtProAddTel" class="form-control">
                                    @foreach (kategori::where('toRes', $theResId)->get() as $kate)
                                        <option value="{{$kate->id}}">{{$kate->emri}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="hidden" value="{{$theResId}}" name="resExtProAddTel" id="resExtProAddTel">
                            </div>
                        </div>
                        <div class="col-1">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button onclick="saveExtraProdRTTel()" class="form-control btn" style="background-color:rgb(39,190,175); font-weight:bold; color:white;">{{__('adminP.saveOnComputer')}}</button>
            </div>
         

        </div>
    </div>
</div>









<div class="modal" id="addTypeModalProdTel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color:rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius:25px;">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color:rgb(39,190,175);">
                <h4 style="color: white; font-weight:bold;" class="modal-title">{{__('adminP.addNewType')}}</h4>
                <button type="button" class="btn btn-danger" onclick="closeAddTypeModalProdTel()">X</button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger text-center" style="display: none; font-weight:bold;" id="addTypeModalProdError01Tel"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-1">

                        </div>
                        <div class="col-10">
                            <div class="form-group">
                                <label for="" class="control-label">{{__('adminP.name')}}</label>
                                <input type="text" name="emriTypeProAddTel" id="emriTypeProAddTel" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="sel1">{{__('adminP.category')}}</label>
                                <select name="kategoriaTypeProAddTel" id="kategoriaTypeProAddTel" class="form-control">
                                    @foreach (kategori::where('toRes', $theResId)->get() as $kate)
                                        <option value="{{$kate->id}}">{{$kate->emri}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label">{{__('adminP.value')}}</label>
                                <input type="number" name="vleraTypeProAddTel" id="vleraTypeProAddTel" class="form-control" step="0.0000000001" min="0">
                            </div>

                            <input type="hidden" value="{{$theResId}}" name="resTypeProAddTel" id="resTypeProAddTel">
                        </div>
                        <div class="col-1">

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button onclick="saveTypeProdRTTel()" class="form-control btn" style="background-color:rgb(39,190,175); font-weight:bold; color:white;">{{__('adminP.saveOnComputer')}}</button>
            </div>
        </div>
    </div>
</div>














<script>
    function closeAddExtModalProdTel(){
        $('#addExtModalProdTel').modal('hide');
        $('Body').addClass('modal-open');
    }

    function saveExtraProdRTTel(){
        $.ajax({
			url: '{{ route("ekstras.storeAdminPPro") }}',
			method: 'post',
			data: {
				emri: $('#emriExtProAddTel').val(),
				qmimi: $('#qmimiExtProAddTel').val(),
				cate: $('#kategoriaExtProAddTel').val(),
				res: $('#resExtProAddTel').val(),
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = res.replace(/\s/g, '');
                if(res == 'success'){
                    $("#addProduktModalTel").load(location.href+" #addProduktModalTel>*","");
                    $("#kategoriResetOnUpdateTel").val(0);
                    $('#addExtModalProdTel').modal('toggle');
                }else{
                    $('#addExtModalProdError01Tel').html(res);
                    $('#addExtModalProdError01Tel').show(50).delay(3000).hide(50);
                }
				
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }


    function closeAddTypeModalProdTel(){
        $('#addTypeModalProdTel').modal('hide');
        $('Body').addClass('modal-open');
    }
    function saveTypeProdRTTel(){
        $.ajax({
			url: '{{ route("llojetPro.storeAdminPPro") }}',
			method: 'post',
			data: {
				emri: $('#emriTypeProAddTel').val(),
				cate: $('#kategoriaTypeProAddTel').val(),
				vlera: $('#vleraTypeProAddTel').val(),
				res: $('#resTypeProAddTel').val(),
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = res.replace(/\s/g, '');
                if(res == 'success'){
                    $("#addProduktModalTel").load(location.href+" #addProduktModalTel>*","");
                    $("#kategoriResetOnUpdateTel").val(0);
                    $('#addTypeModalProdTel').modal('toggle');
                }else{
                    $('#addTypeModalProdError01Tel').html(res);
                    $('#addTypeModalProdError01Tel').show(50).delay(3000).hide(50);
                }
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }
</script>