<?php
    use App\barbershopCupons;
?>
<style>
    .btnQrorpa{
        color:rgb(39, 190, 175);
        border:1px solid rgb(39, 190, 175);

    }
    .btnQrorpa:hover{
        color:white;
        background-color:rgb(39, 190, 175);
    }


</style>


<!-- The Modal -->
<div class="modal" id="addCuponModalTel" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">{{__('adminP.coupon')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
            </div>
            <input id="cuponCodeTel" type="text" class="form-control" placeholder="{{__('adminP.Code')}}">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-percent"></i></span>
            </div>
            <input id="cuponValueTel" type="text" class="form-control" placeholder="{{__('adminP.valuePercentage')}}" max="100" min="1" step="1">
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer d-flex justify-content-between">
        <button style="width:45%;" type="button" class="btn btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
        <button onclick="saveCuponBarTel('{{Auth::user()->sFor}}')" style="width:45%;" type="button" class="btn btn-outline-success" data-dismiss="modal">{{__('adminP.save')}}</button>
      </div>

    </div>
  </div>
</div>






<section class="pl-2 pr-2 pt-4 pb-5">

    <div class="alert alert-danger text-center" style="font-weight:bold; font-size:18px; display:none;" id="valueError01Tel">
        {{__('adminP.valueIsWrong')}}
    </div>

    <div id="addCuponBTNDIVTel">
        @if(barbershopCupons::where('toBar', Auth::user()->sFor)->get()->count() > 0)
            <button style="font-size:15px;" class="btn btn-block btnQrorpa" data-toggle="modal" data-target="#addCuponModalTel" disabled>
            <strong>{{__('adminP.addNewCouponLimitOne')}}</strong></button>
        @else
            <button style="font-size:15px;" class="btn btn-block btnQrorpa" data-toggle="modal" data-target="#addCuponModalTel" >
            <strong>{{__('adminP.addNewCoupon')}}</strong></button>
        @endif
    </div>
    <div class="d-flex justify-content-between flex-wrap mt-1" id="CuponListTel">
            
            <hr style="width:100%">

        @foreach(barbershopCupons::where('toBar', Auth::user()->sFor)->get() as $cuponOne)
            @if($cuponOne->isActive == 1)
                <div style="width:50%; border-bottom:1px solid lightgray;" class="mt-1">
                    <button class="btn btn-block btn-success" onclick="changeStatusCuponBarTel('{{$cuponOne->id}}')">{{__('adminP.active')}}</button>
                </div>
            @else
                <div style="width:50%; border-bottom:1px solid lightgray;" class="mt-1">
                    <button class="btn btn-block btn-danger" onclick="changeStatusCuponBarTel('{{$cuponOne->id}}')">{{__('adminP.notActive')}}</button>
                </div>
            @endif
            <div style="width:25%; border-bottom:1px solid lightgray;"  class="text-center mt-1 ">
               <button class="btn btn-block btn-default" onclick="deleteCuponBarTel('{{$cuponOne->id}}')"><i class="fa-2x far fa-trash-alt"></i></button>
            </div>
            <div style="width:25%; border-bottom:1px solid lightgray;"  class="text-center mt-1 ">
               <button class="btn btn-block btn-default" data-toggle="modal" data-target="#openEditCTel{{$cuponOne->id}}"><i class="fa-2x far fa-edit"></i></button>
            </div>
            <div style="width:50%; border-bottom:1px solid lightgray;" class="text-center mt-1 pt-2">
                <p> {{$cuponOne->codeName}}</p>
            </div>
            <div style="width:50%; border-bottom:1px solid lightgray;"  class="text-center mt-1 pt-2">
                <p>- {{$cuponOne->valueOff}} %</p>
            </div>
          

           














                                <!-- The Modal -->
                                <div class="modal" id="openEditCTel{{$cuponOne->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                                    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">{{__('adminP.editVoucher')}}</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            </div>
                                            <input id="cuponCodeEditTel{{$cuponOne->id}}" type="text" value="{{$cuponOne->codeName}}" class="form-control" placeholder="{{__('adminP.code')}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                            </div>
                                            <input id="cuponValueEditTel{{$cuponOne->id}}" type="text" value="{{$cuponOne->valueOff}}" class="form-control" placeholder="{{__('adminP.valuePercentage')}}" max="100" min="1" step="1">
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer d-flex justify-content-between">
                                        <button style="width:45%;" type="button" class="btn btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                                        <button onclick="EditCuponBarTel('{{Auth::user()->sFor}}', '{{$cuponOne->id}}')" style="width:45%;" type="button" class="btn btn-outline-success" data-dismiss="modal">{{__('adminP.save')}}</button>
                                    </div>

                                    </div>
                                </div>
                                </div>
        @endforeach
    </div>
</section>




<script>

    function saveCuponBarTel(resId){
        $.ajax({
		    url: '{{ route("cupons.saveBarCupon") }}',
			method: 'post',
			data: {
				res: resId,
				code: $('#cuponCodeTel').val(),
				value: $('#cuponValueTel').val(),
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = res.replace(/\s/g, '');
                if(res != 'done'){
                    $('#valueError01Tel').show(200).delay(2500).hide(200);
                }else{
                    $("#CuponListTel").load(location.href+" #CuponListTel>*","");
                    $("#addCuponBTNDIVTel").load(location.href+" #addCuponBTNDIVTel>*","");
                    $('#cuponCodeTel').val('');
                    $('#cuponValueTel').val('');
                }
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }

    function EditCuponBarTel(resId, cId){
        $.ajax({
		    url: '{{ route("cupons.editCuponBar") }}',
			method: 'post',
			data: {
				cuponId: cId,
				code: $('#cuponCodeEditTel'+cId).val(),
				value: $('#cuponValueEditTel'+cId).val(),
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = res.replace(/\s/g, '');
                if(res != 'done'){
                    $('#valueError01Tel').show(200).delay(2500).hide(200);
                }else{
                    $("#CuponListTel").load(location.href+" #CuponListTel>*","");
                }
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }


    function changeStatusCuponBarTel(CuponId){
        $.ajax({
		    url: '{{ route("cupons.chCuponSBar") }}',
			method: 'post',
			data: {
				id: CuponId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#CuponListTel").load(location.href+" #CuponListTel>*","");
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }

    function deleteCuponBarTel(CuponId){
        $.ajax({
		    url: '{{ route("cupons.deleteCuponBar") }}',
			method: 'post',
			data: {
				id: CuponId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#CuponListTel").load(location.href+" #CuponListTel>*","");
                $("#addCuponBTNDIVTel").load(location.href+" #addCuponBTNDIVTel>*","");
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }
    
</script>