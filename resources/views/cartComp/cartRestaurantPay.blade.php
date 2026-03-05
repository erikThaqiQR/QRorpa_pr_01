@if(isset($_SESSION['Res']))
    @php
        $theResId = $_SESSION['Res'];
        $theTable = $_SESSION['t'];
    @endphp
    <input type="hidden" value="{{$_SESSION['Res']}}" id="theRestaurant">
    <input type="hidden" value="{{$_SESSION['t']}}" id="theTable">
@endif

<?php
    if(Auth::check()){
        echo '<input type="hidden" value="'.Auth::user()->email.'" id="email">';

        $sendId = Auth::user()->id;
        $userNameS = Auth::user()->name;
        $userEmailS = Auth::user()->email;
    }else{
        
        echo '
        <div class="form-group">
            <input type="hidden" class="form-control noBorderBtn" placeholder="Enter email" value="user@empty.com" id="email">
        </div>
        ';
        $sendId = 0;
        $userNameS = "empty";
        $userEmailS = "empty";
    }

?>



<div class="container">
                  
        <div class="row mt-4 mb-4" id="paymentDivPhase01">
            <div class="col-4 text-center">
                @if ($Restrict != 0)
                    <button class="btn btn-dark text-center noBorderBtn ContinueCashPay" onclick="closeWithCash()">
                        <i style="margin:0px; padding:0px;" class="far fa-2x fa-money-bill-alt"></i>
                    </button>
                @else
                    <button class="btn btn-dark text-center noBorderBtn ContinueCashPay" onclick="closeWithCash()">
                        <i style="margin:0px; padding:0px;" class="far fa-2x fa-money-bill-alt"></i>
                    </button>
                @endif
                <p><strong>{{__('others.cash_payment')}}</strong></p>
            </div>

            <div class="col-4 text-center">
                @if ($Restrict != 0)
                    <button class="btn btn-dark text-center noBorderBtn ContinueCashPay" onclick="closeWithCard()">
                        <i style="margin:0px; padding:0px;" class="fa-2x fa-solid fa-credit-card"></i>
                    </button>
                @else
                    <button class="btn btn-dark text-center noBorderBtn ContinueCashPay" onclick="closeWithCard()">
                        <i style="margin:0px; padding:0px;" class="fa-2x fa-solid fa-credit-card"></i>
                    </button>
                @endif
                <p><strong>Karte</strong></p>
            </div>
     
            <!-- first if ( do not show for ...) -->
            @if ($theResId == 24 || $theResId == 25 || $theResId == 26 || $theResId == 34 || $theResId == 38 || $theResId == 39)
            
                @if ($theResId == 24 || $theResId == 25 || $theResId == 26)
                    <div class="col-4 text-center">
                        <button data-toggle="modal" data-target="#onlinePayP" class="btn btn-dark noBorderBtn ContinueOnlinePay" id="paymentButton">
                            <i style="margin:0px; padding:0px;" class="fa-2x fa-solid fa-earth-europe"></i>
                        </button>
                        <p><strong>{{__('others.payOnline')}}</strong></p>
                    </div>
                @else
                <div class="col-4 text-center">
    
                    <!-- <script src="https://pay.sandbox.datatrans.com/upp/payment/js/datatrans-2.0.0.js"></script> -->
                    <!-- <form id="paymentForm" data-merchant-id="1100004624" data-amount="1000" data-currency="CHF"
                        data-refno="123456789" data-sign="30916165706580013"> -->
                
                    <button onclick="payOnlineTestSafepay('{{$theResId}}','{{$theTable}}')" class="btn btn-dark noBorderBtn ContinueOnlinePay" id="paymentButton">
                        <i style="margin:0px; padding:0px;" class="fa-2x fa-solid fa-earth-europe"></i>
                    </button>
                    <p><strong>{{__('others.payOnline')}}</strong></p>


                    <!-- </form> -->
                    <ul id="payment-errors"></ul>
                    <div id="payment-form"></div>
                    <!-- The online payment script -->

                    <script>
                        function checkProdsReadyFromCook(res,t){
                            $.ajax({
                                url: '{{ route("cart.checkProdsReadyFromCook") }}',
                                method: 'post',
                                data: {
                                    res :res,
                                    t: t,            
                                    payAllOrMine: $('#payAllOrMineF1').val(),
                                    payAllOrMineSelected: $('#payThisTooSelected').val(),
                                    payAllOrMineProSelected: $('#payThisTooProSelected').val(),
                                    ghostPayId: $('#ghostPayId').val(),
                                    clPhNumber: $('#thisClPhoneNr').val(),
                                    _token: '{{csrf_token()}}'
                                },
                                success: (response) => {
                                    console.log(response);
                                    return response;
                                },
                                error: (error) => {
                                    console.log('error'); console.log(error);
                                }
                            }); 
                        }

                        function payOnlineTestSafepay(res, t){
                            $('#paymentButton').prop('disabled',true);
                            if($('#hasUnconfirmedVal').length && $('#hasUnconfirmedVal').val() == 1){

                                // not allowed to pay ORDERS NOT CONFIRMED
                                // Send a notification to the staf
                                $.ajax({
                                    url: '{{ route("admin.alertAdmAWaiterClPay") }}',
                                    method: 'post',
                                    data: {
                                        resId: $('#theRestaurant').val(),
                                        tableNr: $('#theTable').val(),
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: () => {
                                        $('#paymentButton').prop('disabled',false);
                                        if($('#ordersNotConfirmedError01').is(':hidden')){
                                            $('#ordersNotConfirmedError01').show(50).delay(6000).hide(50);
                                        }
                                    },
                                    error: (error) => {console.log(error);}
                                });
                                
                            }else{
                                $.ajax({
                                    url: '{{ route("cart.checkProdsReadyFromCook") }}',
                                    method: 'post',
                                    data: {
                                        res :res,
                                        t: t,            
                                        payAllOrMine: $('#payAllOrMineF1').val(),
                                        payAllOrMineSelected: $('#payThisTooSelected').val(),
                                        payAllOrMineProSelected: $('#payThisTooProSelected').val(),
                                        ghostPayId: $('#ghostPayId').val(),
                                        clPhNumber: $('#thisClPhoneNr').val(),
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: (response) => {
                                        response = $.trim(response);
                                        console.log(response);
                                        if(response == 'notDone'){
                                            if($('#ordersNotConfirmedError02').is(':hidden')){
                                                $('#ordersNotConfirmedError02').show(50).delay(6000).hide(50);
                                            }
                                        }else{
                                            if(res == 31 || res == 32 || res == 33 || res == 34 || res == 39){
                                            // if(1 == 6){
                                                $.ajax({
                                                    url: '{{ route("onlinePay313233.saferPayQrorpa") }}',
                                                    method: 'post',
                                                    data: {
                                                        theTotOnCart:$('.totalOnCart').html(),
                                                        res :res,
                                                        t: t,
                                                        tipVal: $('.tipValueConfirmValueCLA').val(),
                                                        tipTit: $('.tipValueConfirmTitleCLA').val(),
                                                        payAllOrMine: $('#payAllOrMineF1').val(),
                                                        payAllOrMineSelected: $('#payThisTooSelected').val(),
                                                        payAllOrMineProSelected: $('#payThisTooProSelected').val(),
                                                        codeUsed: $('#couponUsedId').val(),
                                                        codeUsedVal: $('#codeUsedValueID').val(),
                                                        freeShotId: $('#freeShotPh1Id').val(),
                                                        ghostCode: $('#hasGhostCodeF1').val(),
                                                        clPhNumber: $('#thisClPhoneNr').val(),
                                                        ghostPayId: $('#ghostPayId').val(),
                                                        _token: '{{csrf_token()}}'
                                                    },
                                                    success: (response) => {
                                                        $('#paymentButton').prop('disabled',false);
                                                        var dataJ = JSON.stringify(response);
                                                        var dataJ2 = JSON.parse(dataJ);
                                                        window.location.href = dataJ2['body']['RedirectUrl'];
                                                    },
                                                    error: (error) => {
                                                        console.log('error');
                                                        console.log(error);
                                                    }
                                                });
                                            }else{
                                                $.ajax({
                                                    url: '{{ route("onlinePay.saferPayQrorpa") }}',
                                                    method: 'post',
                                                    data: {
                                                        theTotOnCart:$('.totalOnCart').html(),
                                                        res :res,
                                                        t: t,
                                                        tipVal: $('.tipValueConfirmValueCLA').val(),
                                                        tipTit: $('.tipValueConfirmTitleCLA').val(),
                                                        payAllOrMine: $('#payAllOrMineF1').val(),
                                                        payAllOrMineSelected: $('#payThisTooSelected').val(),
                                                        payAllOrMineProSelected: $('#payThisTooProSelected').val(),
                                                        codeUsed: $('#couponUsedId').val(),
                                                        codeUsedVal: $('#codeUsedValueID').val(),
                                                        freeShotId: $('#freeShotPh1Id').val(),
                                                        ghostCode: $('#hasGhostCodeF1').val(),
                                                        clPhNumber: $('#thisClPhoneNr').val(),
                                                        ghostPayId: $('#ghostPayId').val(),
                                                        _token: '{{csrf_token()}}'
                                                    },
                                                    success: (response) => {
                                                        $('#paymentButton').prop('disabled',false);
                                                        var dataJ = JSON.stringify(response);
                                                        var dataJ2 = JSON.parse(dataJ);
                                                        window.location.href = dataJ2['body']['RedirectUrl'];
                                                    },
                                                    error: (error) => {
                                                        console.log('error');
                                                        console.log(error);
                                                    }
                                                }); 
                                            }  
                                        }
                                    },
                                    error: (error) => {
                                        console.log('error'); console.log(error);
                                    }
                                }); 
                            }       
                        }

                        
                    </script>
                </div>
                @endif
            @endif
        </div>  

        <div class="row mt-4 mb-4" style="display:none;" id="paymentDivPhase02">
            <div class="col-12 text-center">
                {{__('others.payment_completed')}}
                <img style="width:150px; height:auto;" src="storage/gifs/cashPay01.gif" alt="">
            </div>
        </div>
    <!-- End -->
</div>

    <!-- The Online Payment Modal -->
    <div class="modal" id="onlinePayP">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">{{__('others.online_payment_soon')}}!</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                                    <div class="d-flex flex-wrap">
                                        <img style=" width:33%; height:90px; object-fit: contain;" src="storage/images/postfinanceLogo.png" alt="" >
                                        <img style=" width:33%; height:90px; object-fit: contain;" src="storage/images/twintLogo.png" alt="" >
                                        <img style=" width:33%; height:90px; object-fit: contain;" src="storage/images/paypalLogo.png" alt="" >
                                        <img style=" width:50%; height:90px; object-fit: contain;" src="storage/images/mastercardLogo.png" alt="" >
                                        <img style=" width:50%; height:90px; object-fit: contain;" src="storage/images/visaLogo.png" alt="" >
                                    </div>
                        
                        </div>
                    </div>
                </div>
            </div>




            <div class="alert alert-danger text-center" id="ordersNotConfirmedError01" style="display:none;">
                <strong>Alle bestellten Produkte müssen bestätigt werden, damit die Zahlung abgeschlossen werden kann</strong> 
                <hr>
                <strong>Das Service-Team ist alarmiert, sie werden es bald für Sie möglich machen</strong>       
            </div>

            <div class="alert alert-danger text-center" id="ordersNotConfirmedError02" style="display:none;">
                <strong>Bitte bezahlen Sie erst, wenn Sie das Restaurant verlassen möchten.</strong>       
            </div>

            <div class="alert alert-danger text-center" id="ordersNotConfirmedError03" style="display:none;">
                <strong>Für die Produkte auf Ihrer Karte gilt eine Altersbeschränkung. Bevor Sie fortfahren, müssen Sie dies akzeptieren.</strong>       
            </div>



                        <div id="CashPay" style="display:none;">
                            <div class="container">
                                {{Form::open(['action' => 'ProduktController@confCode', 'method' => 'post', 'id' => 'closeTheOrder']) }}

                                    <div class="form-group">
                                        {{ Form::label('Bitte beachten Sie denn per SMS erhaltenen Code', null , ['class' => 'control-label']) }}
                                        {{ Form::number('codeUser','', ['class' => 'form-control']) }}
                                    </div>

                                    <!-- <div class="form-check-inline mb-2">
                                        <label class="form-check-label">
                                            <input name="marketingSMS" type="checkbox" class="form-check-input" value="">Erhalten Sie super Angebote und Rabatte per SMS.
                                        </label>
                                    </div> -->

                                    {{ csrf_field() }}

                                    @if(isset($_SESSION["phoneNrVerified"]))
                                        {{ Form::hidden('klientPhoneNr', $_SESSION["phoneNrVerified"], ['class' => 'form-control', 'id' => 'thisClPhoneNr']) }}
                                    @else
                                        {{ Form::hidden('klientPhoneNr', 0760000000, ['class' => 'form-control', 'id' => 'thisClPhoneNr']) }}
                                    @endif

                                    <!-- Tip / Bakshishi -->
                                    {{ Form::hidden('tipValueConfirmValue', 0, ['class' => 'form-control tipValueConfirmValueCLA']) }}
                                    {{ Form::hidden('tipValueConfirmTitle', 0, ['class' => 'form-control tipValueConfirmTitleCLA']) }}
                                    {{ Form::hidden('couponUsed', 0, ['class' => 'form-control tipValueConfirmTitleCLA', 'id' => 'couponUsedId']) }}

                                    {{ Form::hidden('userIdCash', $sendId, ['class' => 'form-control']) }}
                                    {{ Form::hidden('userNameCash', $userNameS, ['class' => 'form-control']) }}
                                    {{ Form::hidden('userEmailCash', $userEmailS, ['class' => 'form-control']) }}

                                    {{ Form::hidden('userPorosia', $porosiaSend, ['class' => 'form-control', 'id' => 'userPorosiaOPay']) }}
                                    {{ Form::hidden('userPayM', 'Cash', ['class' => 'form-control']) }}
                                    {{ Form::hidden('userPayMO', 'Online', ['class' => 'form-control']) }}
                                    {{ Form::hidden('Shuma', Cart::total(), ['class' => 'form-control', 'id' => 'ShumaOPay']) }}

                                    <!-- Send points to the server -->
                                    @if(Auth::check())
                                        {{ Form::hidden('points', explode('.',Cart::total())[0] , ['class' => 'form-control']) }}
                                        @if(isset($confirmData['pUsed']))
                                            {{ Form::hidden('pointsUsing', $confirmData['pUsed'] , ['class' => 'form-control']) }}
                                        @endif
                                    @else
                                        {{ Form::hidden('points', 0 , ['class' => 'form-control']) }}
                                    @endif

                                    <!-- Kupon / Free produkt  -->
                                    {{ Form::hidden('codeUsedValue', '' , ['class' => 'form-control', 'id' => 'codeUsedValueID']) }}
                                    {{ Form::hidden('freeShotPh2', 0 , ['class' => 'form-control', 'id' => 'freeShotPh1Id']) }}
                                    

                                    {{ Form::hidden('nameTA02', 'empty' , ['class' => 'form-control', 'id' => 'nameTA01']) }}
                                    {{ Form::hidden('lastnameTA02', 'empty' , ['class' => 'form-control', 'id' => 'lastnameTA01']) }}
                                    {{ Form::hidden('timeTA02', 'empty' , ['class' => 'form-control', 'id' => 'timeTA01']) }}
                                    


                                    {{ Form::hidden('Res', $_SESSION["Res"] , ['class' => 'form-control']) }}
                                    {{ Form::hidden('t', $_SESSION["t"] , ['class' => 'form-control']) }}

                                    @if(isset($_SESSION['adminToClProdsRec']))
                                        {{ Form::hidden('ghostPay', $_SESSION['adminToClProdsRec'] , ['class' => 'form-control', 'id' => 'ghostPayId']) }}
                                    @else
                                        {{ Form::hidden('ghostPay', 0 , ['class' => 'form-control', 'id' => 'ghostPayId']) }}
                                    @endif

                                    {{ Form::hidden('payAllOrMine', 1 , ['class' => 'form-control', 'id' => 'payAllOrMineF1']) }}
                                    {{ Form::hidden('payAllOrMineSelected', '' , ['class' => 'form-control', 'id' => 'payThisTooSelected']) }}
                                    {{ Form::hidden('payAllOrMineProSelected', '' , ['class' => 'form-control', 'id' => 'payThisTooProSelected']) }}

                                    @if(isset($isGhostNr))
                                        {{ Form::hidden('ghostCNr',$isGhostNr, ['class' => 'form-control', 'id' => 'hasGhostCodeF1']) }}
                                    @else
                                        {{ Form::hidden('ghostCNr',0, ['class' => 'form-control', 'id' => 'hasGhostCodeF1']) }}
                                    @endif


                                    <div class="row">
                                        <div class="col-12">
                                            {{ Form::submit(__('others.confirm'), ['class' => 'btn btn-dark rounded-pill py-2 btn-block']) }}
                                        </div>
                                    </div>
                                {{Form::close() }}
                            </div>
                        </div>

                        <div id="CardPay" style="display:none;">
                            <div class="container">
                                {{Form::open(['action' => 'ProduktController@closeOrderByCardResClient', 'method' => 'post', 'id' => 'closeOrderByCardResClient']) }}
                                    {{ csrf_field() }}
                                    @if(isset($_SESSION["phoneNrVerified"]))
                                        {{ Form::hidden('klientPhoneNr', $_SESSION["phoneNrVerified"], ['class' => 'form-control', 'id' => 'thisClPhoneNrCard']) }}
                                    @else
                                        {{ Form::hidden('klientPhoneNr', 0760000000, ['class' => 'form-control', 'id' => 'thisClPhoneNr']) }}
                                    @endif

                                    <!-- Tip / Bakshishi -->
                                    {{ Form::hidden('tipValueConfirmValue', 0, ['class' => 'form-control tipValueConfirmValueCLA']) }}
                                    {{ Form::hidden('tipValueConfirmTitle', 0, ['class' => 'form-control tipValueConfirmTitleCLA']) }}
                                    {{ Form::hidden('couponUsed', 0, ['class' => 'form-control tipValueConfirmTitleCLA', 'id' => 'couponUsedCardId']) }}

                                    {{ Form::hidden('userIdCash', $sendId, ['class' => 'form-control']) }}
                                    {{ Form::hidden('userNameCash', $userNameS, ['class' => 'form-control']) }}
                                    {{ Form::hidden('userEmailCash', $userEmailS, ['class' => 'form-control']) }}

                                    {{ Form::hidden('userPorosia', $porosiaSend, ['class' => 'form-control', 'id' => 'userPorosiaOPayCard']) }}
                                    {{ Form::hidden('userPayM', 'Kartenzahlung', ['class' => 'form-control']) }}
                                    {{ Form::hidden('Shuma', Cart::total(), ['class' => 'form-control', 'id' => 'ShumaOPayCard']) }}

                                    <!-- Send points to the server -->
                                    @if(Auth::check())
                                        {{ Form::hidden('points', explode('.',Cart::total())[0] , ['class' => 'form-control']) }}
                                        @if(isset($confirmData['pUsed']))
                                            {{ Form::hidden('pointsUsing', $confirmData['pUsed'] , ['class' => 'form-control']) }}
                                        @endif
                                    @else
                                        {{ Form::hidden('points', 0 , ['class' => 'form-control']) }}
                                    @endif

                                    <!-- Kupon / Free produkt  -->
                                    {{ Form::hidden('codeUsedValue', '' , ['class' => 'form-control', 'id' => 'codeUsedValueIDCard']) }}
                                    {{ Form::hidden('freeShotPh2', 0 , ['class' => 'form-control', 'id' => 'freeShotPh1IdCard']) }}
                                    
                                    {{ Form::hidden('nameTA02', 'empty' , ['class' => 'form-control', 'id' => 'nameTA01']) }}
                                    {{ Form::hidden('lastnameTA02', 'empty' , ['class' => 'form-control', 'id' => 'lastnameTA01']) }}
                                    {{ Form::hidden('timeTA02', 'empty' , ['class' => 'form-control', 'id' => 'timeTA01']) }}
                                    
                                    {{ Form::hidden('Res', $_SESSION["Res"] , ['class' => 'form-control']) }}
                                    {{ Form::hidden('t', $_SESSION["t"] , ['class' => 'form-control']) }}

                                    @if(isset($_SESSION['adminToClProdsRec']))
                                        {{ Form::hidden('ghostPay', $_SESSION['adminToClProdsRec'] , ['class' => 'form-control', 'id' => 'ghostPayId']) }}
                                    @else
                                        {{ Form::hidden('ghostPay', 0 , ['class' => 'form-control', 'id' => 'ghostPayId']) }}
                                    @endif

                                    {{ Form::hidden('payAllOrMine', 1 , ['class' => 'form-control', 'id' => 'payAllOrMineF1Card']) }}
                                    {{ Form::hidden('payAllOrMineSelected', '' , ['class' => 'form-control', 'id' => 'payThisTooSelectedCard']) }}
                                    {{ Form::hidden('payAllOrMineProSelected', '' , ['class' => 'form-control', 'id' => 'payThisTooProSelected']) }}

                                    @if(isset($isGhostNr))
                                        {{ Form::hidden('ghostCNr',$isGhostNr, ['class' => 'form-control', 'id' => 'hasGhostCodeF1']) }}
                                    @else
                                        {{ Form::hidden('ghostCNr',0, ['class' => 'form-control', 'id' => 'hasGhostCodeF1']) }}
                                    @endif
                                    
                                {{Form::close() }}
                            </div>
                        </div>
</div>

<div id="StripePay" style="display:none;">
                        <div class="container">
                            <div class="row">
                                <!-- <div class="col-md-12"><pre id="token_response"></pre></div> -->
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-dark rounded-pill py-2 btn-block"
                                        onclick="pay( '{{ Cart::total() }}' , '{{$userNameS}}', '{{ $userEmailS }}', '{{$orderSend}}', '{{$sendId}}')">
                                        {{__('others.pay_with_card')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>


<script>
    function closeWithCash(){
        if('{{$Restrict}}' > 0 && !$("#ageRestrictionCheck").is(':checked')){
            if($('#ordersNotConfirmedError03').is(':hidden')){
                $('#ordersNotConfirmedError03').show(50).delay(6000).hide(50);
            }

        }else if($('#hasUnconfirmedVal').length && $('#hasUnconfirmedVal').val() == 1){
            // not allowed to pay

            $.ajax({
                url: '{{ route("admin.alertAdmAWaiterClPay") }}',
                method: 'post',
                data: {
                    resId: $('#theRestaurant').val(),
                    tableNr: $('#theTable').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    if($('#ordersNotConfirmedError01').is(':hidden')){
                        $('#ordersNotConfirmedError01').show(50).delay(6000).hide(50);
                    }
                },
                error: (error) => {console.log(error);}
            });
            
        }else{

            $.ajax({
                url: '{{ route("cart.checkProdsReadyFromCook") }}',
                method: 'post',
                data: {
                    res :$('#theRestaurant').val(),
                    t: $('#theTable').val(),            
                    payAllOrMine: $('#payAllOrMineF1').val(),
                    payAllOrMineSelected: $('#payThisTooSelected').val(),
                    payAllOrMineProSelected: $('#payThisTooProSelected').val(),
                    ghostPayId: $('#ghostPayId').val(),
                    clPhNumber: $('#thisClPhoneNr').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    response = $.trim(response);
                    if(response == 'notDone'){
                        if($('#ordersNotConfirmedError02').is(':hidden')){
                            $('#ordersNotConfirmedError02').show(50).delay(6000).hide(50);
                        }
                    }else{
                        // Pay (its allowed)
                        $('#paymentDivPhase01').hide(300);
                        $('#paymentDivPhase02').show(300);
                        $("#closeTheOrder").submit();
                    }
                },
                error: (error) => {
                    console.log('error'); console.log(error);
                }
            });
        }
    }






    function closeWithCard(){
        if('{{$Restrict}}' > 0 && !$("#ageRestrictionCheck").is(':checked')){
            if($('#ordersNotConfirmedError03').is(':hidden')){
                $('#ordersNotConfirmedError03').show(50).delay(6000).hide(50);
            }

        }else if($('#hasUnconfirmedVal').length && $('#hasUnconfirmedVal').val() == 1){
            // not allowed to pay

            $.ajax({
                url: '{{ route("admin.alertAdmAWaiterClPay") }}',
                method: 'post',
                data: {
                    resId: $('#theRestaurant').val(),
                    tableNr: $('#theTable').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    if($('#ordersNotConfirmedError01').is(':hidden')){
                        $('#ordersNotConfirmedError01').show(50).delay(6000).hide(50);
                    }
                },
                error: (error) => {console.log(error);}
            });

        }else{
            $.ajax({
                url: '{{ route("cart.checkProdsReadyFromCook") }}',
                method: 'post',
                data: {
                    res :$('#theRestaurant').val(),
                    t: $('#theTable').val(),            
                    payAllOrMine: $('#payAllOrMineF1').val(),
                    payAllOrMineSelected: $('#payThisTooSelected').val(),
                    payAllOrMineProSelected: $('#payThisTooProSelected').val(),
                    ghostPayId: $('#ghostPayId').val(),
                    clPhNumber: $('#thisClPhoneNr').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    response = $.trim(response);
                    if(response == 'notDone'){
                        if($('#ordersNotConfirmedError02').is(':hidden')){
                            $('#ordersNotConfirmedError02').show(50).delay(6000).hide(50);
                        }
                    }else{
                        // Pay (its allowed)
                        $('#paymentDivPhase01').hide(300);
                        $('#paymentDivPhase02').show(300);
                        $("#closeOrderByCardResClient").submit();
                    }
                },
                error: (error) => {
                    console.log('error'); console.log(error);
                }
            });
        }
    }
</script>