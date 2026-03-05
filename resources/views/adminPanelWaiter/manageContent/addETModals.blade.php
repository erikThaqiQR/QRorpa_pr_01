<?php
    use App\kategori;

    $theResId = Auth::user()->sFor;
?>





<div class="modal" id="addExtModalProd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color: rgb(39,190,175);">
                <h4 style="color: white; font-weight:bold;" class="modal-title">{{__('adminP.addNewIngredient')}}</h4>
                <button type="button" class="btn btn-danger" onclick="closeAddExtModalProd()">X</button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>

        
            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger text-center" style="display: none; font-weight:bold;" id="addExtModalProdError01"></div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-1">
                        </div>
                        <div class="col-10">
                            <div class="form-group">
                                <label for="" class="control-label">{{__('adminP.name')}}</label>
                                <input type="text" name="emriExtProAdd" id="emriExtProAdd" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="qmimiExtProAdd" class="control-label">{{__('adminP.price')}}</label>
                                <input type="number" name="qmimiExtProAdd" id="qmimiExtProAdd" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label for="sel1">{{__('adminP.chooseCategory')}}</label>
                                <select name="kategoriaExtProAdd" id="kategoriaExtProAdd" class="form-control">
                                    @foreach (kategori::where('toRes', $theResId)->get() as $kate)
                                        <option value="{{$kate->id}}">{{$kate->emri}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="hidden" value="{{$theResId}}" name="resExtProAdd" id="resExtProAdd">
                            </div>
                        </div>
                        <div class="col-1">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button onclick="saveExtraProdRT()" class="form-control btn" style="background-color:rgb(39,190,175); font-weight:bold; color:white;">{{__('adminP.saveOnComputer')}}</button>
            </div>
         

        </div>
    </div>
</div>









<div class="modal" id="addTypeModalProd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" style="background-color:rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius:25px;">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color:rgb(39,190,175);">
                <h4 style="color: white; font-weight:bold;" class="modal-title">{{__('adminP.addNewType')}}</h4>
                <button type="button" class="btn btn-danger" onclick="closeAddTypeModalProd()">X</button>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger text-center" style="display: none; font-weight:bold;" id="addTypeModalProdError01"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-1">

                        </div>
                        <div class="col-10">
                            <div class="form-group">
                                <label for="" class="control-label">{{__('adminP.name')}}</label>
                                <input type="text" name="emriTypeProAdd" id="emriTypeProAdd" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="sel1">{{__('adminP.category')}}</label>
                                <select name="kategoriaTypeProAdd" id="kategoriaTypeProAdd" class="form-control">
                                    @foreach (kategori::where('toRes', $theResId)->get() as $kate)
                                        <option value="{{$kate->id}}">{{$kate->emri}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label">{{__('adminP.value')}}</label>
                                <input type="number" name="vleraTypeProAdd" id="vleraTypeProAdd" class="form-control" step="0.0000000001" min="0">
                            </div>

                            <input type="hidden" value="{{$theResId}}" name="resTypeProAdd" id="resTypeProAdd">
                        </div>
                        <div class="col-1">

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button onclick="saveTypeProdRT()" class="form-control btn" style="background-color:rgb(39,190,175); font-weight:bold; color:white;">{{__('adminP.saveOnComputer')}}</button>
            </div>
        </div>
    </div>
</div>














<script>
    function closeAddExtModalProd(){
        $('#addExtModalProd').modal('hide');
        $('Body').addClass('modal-open');
    }

    function saveExtraProdRT(){
        $.ajax({
			url: '{{ route("ekstras.storeAdminPPro") }}',
			method: 'post',
			data: {
				emri: $('#emriExtProAdd').val(),
				qmimi: $('#qmimiExtProAdd').val(),
				cate: $('#kategoriaExtProAdd').val(),
				res: $('#resExtProAdd').val(),
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = res.replace(/\s/g, '');
                if(res == 'success'){
                    $("#addProduktModalBodyExTy").load(location.href+" #addProduktModalBodyExTy>*","");
                    $("#kategoriResetOnUpdate").val(0);
                    // $("#addProduktModal").load(location.href+" #addProduktModal>*","");
                    $('#addExtModalProd').modal('toggle');
                }else{
                    $('#addExtModalProdError01').html(res);
                    $('#addExtModalProdError01').show(50).delay(3000).hide(50);
                }
				
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }





    function closeAddTypeModalProd(){
        $('#addTypeModalProd').modal('hide');
        $('Body').addClass('modal-open');
    }
    function saveTypeProdRT(){
        $.ajax({
			url: '{{ route("llojetPro.storeAdminPPro") }}',
			method: 'post',
			data: {
				emri: $('#emriTypeProAdd').val(),
				cate: $('#kategoriaTypeProAdd').val(),
				vlera: $('#vleraTypeProAdd').val(),
				res: $('#resTypeProAdd').val(),
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = res.replace(/\s/g, '');
                if(res == 'success'){
                    $("#addProduktModalBodyExTy").load(location.href+" #addProduktModalBodyExTy>*","");
                    $("#kategoriResetOnUpdate").val(0);
                    $('#addTypeModalProd').modal('toggle');
                }else{
                    $('#addTypeModalProdError01').html(res);
                    $('#addTypeModalProdError01').show(50).delay(3000).hide(50);
                }
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }
</script>