<?php

    use Illuminate\Support\Facades\Auth;
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Gutscheincode']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('admWoMng.ordersStatisticsWaiter01'));
        exit();
    }
    use App\Cupon;
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







<section class="pl-2 pr-2 pt-4 pb-5" id="couponShow">

    <div class="alert alert-danger text-center" style="font-weight:bold; font-size:15px; display:none;" id="valueError01">
        {{__('adminP.valueIsWrong')}}
    </div>

    <div id="addCuponBTNDIV">
       
        <button style="font-size:17px;" class="btn btn-block btnQrorpa shadow-none" data-toggle="modal" data-target="#addCuponModal" >
            <strong>{{__('adminP.addNewCoupon')}}</strong>
        </button>
    </div>
    <br>

 

        <div class="d-flex flex-wrap">
            @foreach(Cupon::where('toRes', Auth::user()->sFor)->get() as $cuponOne)
                <div class="d-flex flex-wrap p-2 mb-2" style="width: 100%; border:1px solid rgb(72,81,87); border-radius:15px;">
                    <p  style="width:49.5%; margin-right:1%; margin-bottom:0px;" class="text-center btn btn-defult" >
                        @if($cuponOne->typeCo == 1)
                            <strong style="color: blue;"> <i class="fas fa-percent"></i>{{__('adminP.percentageOff')}}</strong>
                        @elseif($cuponOne->typeCo == 2)
                            <strong style="color: blue;"> <i class="fas fa-money-bill-wave"></i></i>{{__('adminP.moneyOut')}}</strong>
                        @elseif($cuponOne->typeCo == 3)
                            <strong style="color: blue;"><i class="fas fa-drumstick-bite"></i></i>{{__('adminP.freeProduct')}}</strong>
                        @endif
                    </p>
                    @if($cuponOne->isActive == 1)
                        <div style="width:49.5%;" class="mt-1">
                            <button class="btn btn-block btn-success shadow-none" onclick="changeStatusCupon('{{$cuponOne->id}}')">{{__('adminP.active')}}</button>
                        </div>
                    @else
                        <div style="width:49.5%;" class="mt-1">
                            <button class="btn btn-block btn-danger shadow-none" onclick="changeStatusCupon('{{$cuponOne->id}}')">{{__('adminP.notActive')}} </button>
                        </div>
                    @endif
                    <div style="width:50%;" class="text-center mt-1 pt-2">
                        <h4><strong> <i class="fas fa-barcode"></i> {{$cuponOne->codeName}}</strong></h4>
                    </div>
                    <div style="width:50%;"  class="text-center mt-1 pt-2">
                        @if($cuponOne->typeCo == 1)
                            <p><strong>- {{$cuponOne->valueOff}} %</strong></p>
                        @elseif($cuponOne->typeCo == 2)
                            <p><strong>- {{$cuponOne->valueOffMoney}} {{__('adminP.currencyShow')}}</strong></p>
                        @elseif($cuponOne->typeCo == 3)
                            <p><strong>{{$cuponOne->prodName}}</strong></p>
                        @endif
                    </div>
                    <div style="width:50%;"  class="text-center mt-1 pt-2">
                        <p style="font-weight: bold; margin:4px;">
                            <i class="fas fa-shopping-cart"></i> <i class="fas fa-level-up-alt"></i> {{$cuponOne->cartAllow}} {{__('adminP.currencyShow')}}
                        </p>
                    </div>
                    <div style="width:50%;"  class="text-center mt-1 pt-2">
                        <p style="font-weight: bold; margin:4px;">
                            {{$cuponOne->timesToUse}} verwendet links
                        </p>
                    </div>

                    <div style="width:100%; border-bottom:1px solid lightgray;"  class="text-center mt-1 pt-1">
                        <p style="font-weight: bold; margin:4px;">
                            {{$cuponOne->timesWonByCl}} X Von Kunden gewonnen
                        </p>
                    </div>

                    
                    <div style="width:33%;" class="text-center mt-1 pt-2">
                        <button class="btn btn-block btn-default shadow-none" onclick="deleteCupon('{{$cuponOne->id}}')">
                            <i class="far fa-2x fa-trash-alt"></i>
                            <p style="margin:0px;" class="text-center"><strong>Löschen</strong></p>
                        </button>
                    </div>
                    <div style="width:33%;" class="text-center mt-1 pt-2">
                        <button class="btn btn-block btn-default shadow-none" data-toggle="modal" data-target="#openEditC{{$cuponOne->id}}">
                            <i class="far fa-2x fa-edit"></i>
                            <p style="margin:0px;" class="text-center"><strong>Bearbeiten</strong></p>
                            
                        </button>
                    </div>
                    <div style="width:33%;" class="text-center mt-1 pt-2">
                        @if($cuponOne->forRoulette == 1)
                        <button class="btn btn-block btn-success shadow-none" onclick="deactiveCouponForWheel('{{$cuponOne->id}}')" id="wheelCouponBtn{{$cuponOne->id}}">
                            <i class="fa-regular fa-2x fa-life-ring"></i>
                            <p style="margin:0px;" class="text-center"><strong>für Rad</strong></p>
                        </button>
                        @else
                        <button class="btn btn-block btn-danger shadow-none" onclick="activeCouponForWheel('{{$cuponOne->id}}')" id="wheelCouponBtn{{$cuponOne->id}}">
                            <i class="fa-regular fa-2x fa-life-ring"></i>
                            <p style="margin:0px;" class="text-center"><strong>für Rad</strong></p>
                        </button>
                        @endif
                    </div>
                </div>
            

        


                <!-- The Edit Modal -->
                <div class="modal" id="openEditC{{$cuponOne->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header" style="background-color: rgb(39, 190, 175); color:white;">
                                <h4 class="modal-title">{{__('adminP.editVoucher')}}</h4>
                                <button style="color: white;" type="button" class="close" data-dismiss="modal">X</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                    </div>
                                    <input id="cuponCodeEdit{{$cuponOne->id}}" type="text" value="{{$cuponOne->codeName}}" class="form-control shadow-none">
                                </div>
                                @if($cuponOne->typeCo == 1)
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                    </div>
                                    <input id="cuponValueEdit{{$cuponOne->id}}" type="text" value="{{$cuponOne->valueOff}}" class="form-control shadow-none" max="100" min="1" step="1">
                                </div>
                                @elseif($cuponOne->typeCo == 2)
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                    </div>
                                    <input id="cuponValueMoneyEdit{{$cuponOne->id}}" value="{{$cuponOne->valueOffMoney}}" type="number" class="form-control shadow-none" min="0.01" step="0.01">
                                </div>
                                @elseif($cuponOne->typeCo == 3)
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-drumstick-bite"></i></span>
                                    </div>
                                    <input id="cuponValueProductEdit{{$cuponOne->id}}" value="{{$cuponOne->prodName}}" type="text" class="form-control shadow-none">
                                </div>
                                @endif
                                
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-shopping-cart"></i> <i class="fas fa-level-up-alt"></i></span>
                                    </div>
                                    <input id="cartMinValueEdit{{$cuponOne->id}}" value="{{$cuponOne->cartAllow}}" type="number" class="form-control shadow-none" min="0" step="0.01">
                                </div>

                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Zeiten zu verwenden</span>
                                    </div>
                                    <input id="timesToUseEdit{{$cuponOne->id}}" type="number" value="{{$cuponOne->timesToUse}}" class="form-control shadow-none" min="1" step="1">
                                </div>
            

                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer d-flex justify-content-between">
                                <button style="width:45%;" type="button" class="btn btn-outline-danger shadow-none" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                                <button onclick="EditCupon('{{Auth::user()->sFor}}', '{{$cuponOne->id}}','{{$cuponOne->typeCo}}')" style="width:45%;" type="button" 
                                class="btn btn-success shadow-none">{{__('adminP.save')}}</button>
                            </div>

                            <div class="alert alert-danger text-center" style="font-weight:bold; font-size:1.1rem; display:none;" id="couponError01{{$cuponOne->id}}">
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    
</section>



























<!-- The Modal -->
<div class="modal" id="addCuponModal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header" style="background-color: rgb(39, 190, 175); color:white;">
            <h4 class="modal-title"><strong>{{__('adminP.coupon')}}</strong></h4>
            <button type="button" class="close" data-dismiss="modal" style="color: white;">X</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="d-flex justify-content-between mb-2">
                <button id="couponAddT1" style="width:32%;" class="btn btn-dark" onclick="selectCouponAdd('1')">
                    <strong> <i class="fas fa-percent"></i> {{__('adminP.percentageOff')}}</strong>
                </button>
                <button id="couponAddT2" style="width:32%;" class="btn btn-outline-dark" onclick="selectCouponAdd('2')">
                    <strong> <i class="fas fa-money-bill-wave"></i> {{__('adminP.moneyOut')}} </strong>
                </button>
                <button id="couponAddT3" style="width:32%;" class="btn btn-outline-dark" onclick="selectCouponAdd('3')">
                    <strong> <i class="fas fa-drumstick-bite"></i> {{__('adminP.freeProduct')}} </strong>
                </button>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                </div>
                <input id="cuponCode" type="text" class="form-control shadow-none" placeholder="{{__('adminP.code')}}">
            </div>
            <div class="input-group mb-3" id="cuponValuePercentageDiv">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                </div>
                <input id="cuponValue" type="text" class="form-control shadow-none" placeholder="{{__('adminP.valuePercentage')}}" max="100" min="1" step="1">
            </div>
            <div class="input-group mb-3" style="display:none;" id="cuponValueMoneyDiv">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                </div>
                <input id="cuponValueMoney" type="number" class="form-control shadow-none" placeholder="{{__('adminP.discount')}}(0.01 +)" min="0.01" step="0.01">
            </div>
            <div class="input-group mb-3" style="display:none;" id="cuponValueProductDiv">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-drumstick-bite"></i></span>
                </div>
                <input id="cuponValueProduct" type="text" class="form-control shadow-none" placeholder="{{__('adminP.writeProductsName')}}">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-shopping-cart"></i> <i class="fas fa-level-up-alt"></i></span>
                </div>
                <input id="cartMinValue" type="number" class="form-control shadow-none" placeholder="{{__('adminP.cartMinLimit')}}" min="0" step="0.01">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Zeiten zu verwenden</span>
                </div>
                <input id="timesToUse" type="number" class="form-control shadow-none" min="1" step="1">
            </div>
            
        </div>

        <input type="hidden" id="typeAddCou" value="1">

        <!-- Modal footer -->
        <div class="modal-footer d-flex justify-content-between">
            <button style="width:45%;" type="button" class="btn btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
            <button onclick="saveCupon('{{Auth::user()->sFor}}')" style="width:45%;" type="button" class="btn btn-success">{{__('adminP.save')}}</button>
        </div>

        <div class="alert alert-danger text-center" style="font-weight: bold; display:none;"  id="addCuponModalError01">
        </div>

    </div>
  </div>
</div>

<script>
    function selectCouponAdd(type){
        $('#couponAddT1').attr('class','btn btn-outline-dark');
        $('#couponAddT2').attr('class','btn btn-outline-dark');
        $('#couponAddT3').attr('class','btn btn-outline-dark');

        $('#couponAddT'+type).attr('class','btn btn-dark');
        $('#typeAddCou').val(type);

        $('#cuponValuePercentageDiv').hide(1);
        $('#cuponValueMoneyDiv').hide(1);
        $('#cuponValueProductDiv').hide(1);
        switch (type){
            case '1':
                $('#cuponValuePercentageDiv').show(1);
            break;

            case '2':
                $('#cuponValueMoneyDiv').show(1);
            break;

            case '3':
                $('#cuponValueProductDiv').show(1);
            break;
        }
    }




    function saveCupon(resId){
        if(!$('#cuponCode').val()){
            $('#addCuponModalError01').html('Schreiben Sie den Gutscheincode!');
            if($('#addCuponModalError01').is(':hidden')){ $('#addCuponModalError01').show(100).delay(5500).hide(100); }

        }else if($('#typeAddCou').val() == 1 && (!$('#cuponValue').val() || $('#cuponValue').val() <= 0 || $('#cuponValue').val() > 100)){
            $('#addCuponModalError01').html('Schreiben Sie den diskontierten Wert in Prozent zwischen 0,01 und 100!');
            if($('#addCuponModalError01').is(':hidden')){ $('#addCuponModalError01').show(100).delay(5500).hide(100); }

        }else if($('#typeAddCou').val() == 2 && (!$('#cuponValueMoney').val() || $('#cuponValueMoney').val() <= 0)){
            $('#addCuponModalError01').html('Schreiben Sie den rabattierten Wert in Chf größer als 0!');
            if($('#addCuponModalError01').is(':hidden')){ $('#addCuponModalError01').show(100).delay(5500).hide(100); }

        }else if($('#typeAddCou').val() == 3 && !$('#cuponValueProduct').val()){
            $('#addCuponModalError01').html('Schreiben Sie den Namen oder Titel des kostenlosen Produkts!');
            if($('#addCuponModalError01').is(':hidden')){ $('#addCuponModalError01').show(100).delay(5500).hide(100); }

        }else if(!$('#cartMinValue').val() || $('#cartMinValue').val() < 0){
            $('#addCuponModalError01').html('Geben Sie eine gültige Mindestbestellsumme ein, damit der Gutschein gültig ist!');
            if($('#addCuponModalError01').is(':hidden')){ $('#addCuponModalError01').show(100).delay(5500).hide(100); }

        }else if(!$('#timesToUse').val() || $('#timesToUse').val() <= 0){
            $('#addCuponModalError01').html('Geben Sie einen gültigen Wert ein, wie oft dieser Gutschein verwendet werden kann!');
            if($('#addCuponModalError01').is(':hidden')){ $('#addCuponModalError01').show(100).delay(5500).hide(100); }

        }else{
            $.ajax({
                url: '{{ route("cupons.saveCupon") }}',
                method: 'post',
                data: {
                    res: resId,
                    tipi: $('#typeAddCou').val(),
                    code: $('#cuponCode').val(),
                    value: $('#cuponValue').val(),
                    valueMoney: $('#cuponValueMoney').val(),
                    valueProduct: $('#cuponValueProduct').val(),
                    cartMin: $('#cartMinValue').val(),
                    tiToUse : $('#timesToUse').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    res = res.replace(/\s/g, '');
                    if(res == 'done'){
                        $("#addCuponModal").load(location.href+" #addCuponModal>*","");
                        $("#addCuponModal").modal("toggle");
                        $("#couponShow").load(location.href+" #couponShow>*","");
                    }else{
                        $('#addCuponModalError01').html('Die Daten waren nicht gültig, um diesen Gutschein zu registrieren!');
                        $('#addCuponModalError01').show(200).delay(2500).hide(200);
                    }
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            });
        }
    }





    function EditCupon(resId, cId, cuTy){
   
        if(!$('#cuponCodeEdit'+cId).val()){
            $('#couponError01'+cId).html('Schreiben Sie den Gutscheincode!');
            if($('#couponError01'+cId).is(':hidden')){ $('#couponError01'+cId).show(100).delay(5500).hide(100); }

        }else if(cuTy == 1 && (!$('#cuponValueEdit'+cId).val() || $('#cuponValueEdit'+cId).val() <= 0 || $('#cuponValueEdit'+cId).val() > 100)){
            $('#couponError01'+cId).html('Schreiben Sie den diskontierten Wert in Prozent zwischen 0,01 und 100!');
            if($('#couponError01'+cId).is(':hidden')){ $('#couponError01'+cId).show(100).delay(5500).hide(100); }

        }else if(cuTy == 2 && (!$('#cuponValueMoneyEdit'+cId).val() || $('#cuponValueMoneyEdit'+cId).val() <= 0)){
            $('#couponError01'+cId).html('Schreiben Sie den rabattierten Wert in Chf größer als 0!');
            if($('#couponError01'+cId).is(':hidden')){ $('#couponError01'+cId).show(100).delay(5500).hide(100); }

        }else if(cuTy == 3 && !$('#cuponValueProductEdit'+cId).val()){
            $('#couponError01'+cId).html('Schreiben Sie den Namen oder Titel des kostenlosen Produkts!');
            if($('#couponError01'+cId).is(':hidden')){ $('#couponError01'+cId).show(100).delay(5500).hide(100); }

        }else if(!$('#cartMinValueEdit'+cId).val() || $('#cartMinValueEdit'+cId).val() < 0){
            $('#couponError01'+cId).html('Geben Sie eine gültige Mindestbestellsumme ein, damit der Gutschein gültig ist!');
            if($('#couponError01'+cId).is(':hidden')){ $('#couponError01'+cId).show(100).delay(5500).hide(100); }

        }else if(!$('#timesToUseEdit'+cId).val() || $('#timesToUseEdit'+cId).val() < 0){
            $('#couponError01'+cId).html('Geben Sie einen gültigen Wert ein, wie oft dieser Gutschein verwendet werden kann!');
            if($('#couponError01'+cId).is(':hidden')){ $('#couponError01'+cId).show(100).delay(5500).hide(100); }

        }else{
            if(cuTy == 1){ var val = $('#cuponValueEdit'+cId).val();
            }else if(cuTy == 2){ var val = $('#cuponValueMoneyEdit'+cId).val();
            }else{var val = $('#cuponValueProductEdit'+cId).val();}
            $.ajax({
                url: '{{ route("cupons.EditCupon") }}',
                method: 'post',
                data: {
                    cuponId: cId,
                    code: $('#cuponCodeEdit'+cId).val(),
                    value: val,
                    cartMin: $('#cartMinValueEdit'+cId).val(),
                    cuType: cuTy,
                    timToUse: $('#timesToUseEdit'+cId).val(),
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    $("#openEditC"+cId).modal('toggle');
                    $("#couponShow").load(location.href+" #couponShow>*","");
                },
                error: (error) => { console.log(error);}
            });
        }
    }

    function changeStatusCupon(CuponId){
        $.ajax({
		    url: '{{ route("cupons.chCuponS") }}',
			method: 'post',
			data: {
				id: CuponId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#couponShow").load(location.href+" #couponShow>*","");
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }

    function deleteCupon(CuponId){
        $.ajax({
		    url: '{{ route("cupons.deleteCupon") }}',
			method: 'post',
			data: {
				id: CuponId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#couponShow").load(location.href+" #couponShow>*","");
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }


    function activeCouponForWheel(couponId){
        $('#wheelCouponBtn'+couponId).prop('disabled',true);
        $.ajax({
		    url: '{{ route("cupons.activateCouponForWheel") }}',
			method: 'post',
			data: {
				cId: couponId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                if(respo == 'success'){
                    $('#wheelCouponBtn'+couponId).attr('class','btn btn-block btn-success shadow-none');
                    $('#wheelCouponBtn'+couponId).attr('onclick','deactiveCouponForWheel('+couponId+')');
                }
                $('#wheelCouponBtn'+couponId).prop('disabled',false);
                // $('#wheelCouponBtn'+couponId).
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }

    function deactiveCouponForWheel(couponId){
        $('#wheelCouponBtn'+couponId).prop('disabled',true);
        $.ajax({
		    url: '{{ route("cupons.deAactivateCouponForWheel") }}',
			method: 'post',
			data: {
				cId: couponId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                if(respo == 'success'){
                    $('#wheelCouponBtn'+couponId).attr('class','btn btn-block btn-danger shadow-none');
                    $('#wheelCouponBtn'+couponId).attr('onclick','activeCouponForWheel('+couponId+')');
                }
                $('#wheelCouponBtn'+couponId).prop('disabled',false);
			},
			error: (error) => {
                console.log(error);
            	alert($('#oopsSomethingWrong').val())
			}
		});
    }
</script>