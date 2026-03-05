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
<div class="modal" id="addCuponModal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
            <input id="cuponCode" type="text" class="form-control" placeholder="{{__('adminP.code')}}" requeired>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-percent"></i></span>
            </div>
            <input id="cuponValue" type="text" class="form-control" placeholder="{{__('adminP.valuePercentage')}}" max="100" min="1" step="1" required>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer d-flex justify-content-between">
        <button style="width:45%;" type="button" class="btn btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
        <button onclick="saveBarCupon('{{Auth::user()->sFor}}')" style="width:45%;" type="button" class="btn btn-outline-success" data-dismiss="modal">{{__('adminP.save')}}</button>
      </div>

    </div>
  </div>
</div>



























<section class="pl-2 pr-2 pt-4 pb-5">

    <div class="alert alert-danger text-center" style="font-weight:bold; font-size:18px; display:none;" id="valueError01">
       {{__('adminP.valueIsWrong')}}
    </div>

    <div id="addCuponBTNDIV">
        @if(barbershopCupons::where('toBar', Auth::user()->sFor)->get()->count() > 0)
            <button style="font-size:19px;" class="btn btn-block btnQrorpa" data-toggle="modal" data-target="#addCuponModal" disabled>
            <strong>{{__('adminP.addNewCouponLimitOne')}}</strong></button>
        @else
            <button style="font-size:19px;" class="btn btn-block btnQrorpa" data-toggle="modal" data-target="#addCuponModal" >
            <strong>{{__('adminP.addNewCoupon')}}</strong></button>
        @endif
    </div>

    <div class="d-flex justify-content-between flex-wrap mt-4" id="CuponList">
            <div style="width:20%">
            </div>
            <div style="width:30%" class="text-center">
                <p><strong>{{__('adminP.codeName')}}</strong></p>
            </div>
            <div style="width:30%"  class="text-center">
                <p><strong>{{__('adminP.valueOff')}}</strong> </p>
            </div>
            <div style="width:10%">
            </div>
            <div style="width:10%">
            </div>

            <hr style="width:100%">

        @foreach(barbershopCupons::where('toBar', Auth::user()->sFor)->get() as $cuponOne)
            @if($cuponOne->isActive == 1)
                <div style="width:20%; border-bottom:1px solid lightgray;" class="mt-1">
                    <button class="btn btn-block btn-success" onclick="changeStatusCupon('{{$cuponOne->id}}')">{{__('adminP.active')}}</button>
                </div>
            @else
                <div style="width:20%; border-bottom:1px solid lightgray;" class="mt-1">
                    <button class="btn btn-block btn-danger" onclick="changeStatusCupon('{{$cuponOne->id}}')">{{__('adminP.notActive')}}</button>
                </div>
            @endif
            <div style="width:30%; border-bottom:1px solid lightgray;" class="text-center mt-1 pt-2">
                <p> {{$cuponOne->codeName}}</p>
            </div>
            <div style="width:30%; border-bottom:1px solid lightgray;"  class="text-center mt-1 pt-2">
                <p>- {{$cuponOne->valueOff}} %</p>
            </div>
            <div style="width:10%; border-bottom:1px solid lightgray;"  class="text-center mt-1 pt-2">
               <button class="btn btn-block btn-default" onclick="deleteCupon('{{$cuponOne->id}}')"><i class="far fa-2x fa-trash-alt"></i></button>
            </div>
            <div style="width:10%; border-bottom:1px solid lightgray;"  class="text-center mt-1 pt-2">
               <button class="btn btn-block btn-default" data-toggle="modal" data-target="#openEditC{{$cuponOne->id}}"><i class="far fa-2x fa-edit"></i></button>
            </div>

           









                                <!-- The Modal -->
                                <div class="modal" id="openEditC{{$cuponOne->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
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
                                            <input id="cuponCodeEdit{{$cuponOne->id}}" type="text" value="{{$cuponOne->codeName}}" class="form-control" placeholder="{{__('adminP.code')}}">
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                            </div>
                                            <input id="cuponValueEdit{{$cuponOne->id}}" type="text" value="{{$cuponOne->valueOff}}" class="form-control" placeholder="{{__('adminP.valuePercentage')}}" max="100" min="1" step="1">
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer d-flex justify-content-between">
                                        <button style="width:45%;" type="button" class="btn btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                                        <button onclick="EditCupon('{{Auth::user()->sFor}}', '{{$cuponOne->id}}')" style="width:45%;" type="button" class="btn btn-outline-success" data-dismiss="modal">{{__('adminP.save')}}</button>
                                    </div>

                                    </div>
                                </div>
                                </div>






        @endforeach
    </div>
</section>

<script>
    function saveBarCupon(barId){
        $.ajax({
		    url: '{{ route("cupons.saveBarCupon") }}',
			method: 'post',
			data: {
				bar: barId,
				code: $('#cuponCode').val(),
				value: $('#cuponValue').val(),
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = res.replace(/\s/g, '');
                if(res != 'done'){
                    $('#valueError01').show(200).delay(2500).hide(200);
                }else{
                    $("#CuponList").load(location.href+" #CuponList>*","");
                    $("#addCuponBTNDIV").load(location.href+" #addCuponBTNDIV>*","");
                    $('#cuponCode').val('');
                    $('#cuponValue').val('');
                }
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }


    function EditCupon(resId, cId){
        $.ajax({
		    url: '{{ route("cupons.editCuponBar") }}',
			method: 'post',
			data: {
				cuponId: cId,
				code: $('#cuponCodeEdit'+cId).val(),
				value: $('#cuponValueEdit'+cId).val(),
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = res.replace(/\s/g, '');
                if(res != 'done'){
                    $('#valueError01').show(200).delay(2500).hide(200);
                }else{
                    $("#CuponList").load(location.href+" #CuponList>*","");
                }
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }

    function changeStatusCupon(CuponId){
        $.ajax({
		    url: '{{ route("cupons.chCuponSBar") }}',
			method: 'post',
			data: {
				id: CuponId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#CuponList").load(location.href+" #CuponList>*","");
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }

    function deleteCupon(CuponId){
        $.ajax({
		    url: '{{ route("cupons.deleteCuponBar") }}',
			method: 'post',
			data: {
				id: CuponId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#CuponList").load(location.href+" #CuponList>*","");
                $("#addCuponBTNDIV").load(location.href+" #addCuponBTNDIV>*","");
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }
</script>