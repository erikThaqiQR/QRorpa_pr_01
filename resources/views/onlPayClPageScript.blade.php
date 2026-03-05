<script>

    function payOnlClFS(opcfsId,totPay,resId){
        if(resId == 31 || resId ==32 || resId == 33 || resId == 34 || resId == 39){
        // if(1 == 6){
            $.ajax({
                url: '{{ route("oPayFStaf.oPayFStafPayAllRes313233") }}',
                method: 'post',
                data: {
                    onlPayRecId: opcfsId,
                    totalPay: totPay,
                    skontoCHF: $('#skontoCHF').val(),
                    skontoPer: $('#skontoPer').val(),
                    bakshishi: $('#tipValueCHF').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    $('#payOnlClFSBtn1').prop('disabled',true);
                    var dataJ = JSON.stringify(response);
                    var dataJ2 = JSON.parse(dataJ);
                    window.location.href = dataJ2['body']['RedirectUrl'];
                },
                error: (error) => { console.log(error); }
            });
        }else{
            // Test Account
            $.ajax({
                url: '{{ route("oPayFStaf.oPayFStafPayAllRes") }}',
                method: 'post',
                data: {
                    onlPayRecId: opcfsId,
                    totalPay: totPay,
                    skontoCHF: $('#skontoCHF').val(),
                    skontoPer: $('#skontoPer').val(),
                    bakshishi: $('#tipValueCHF').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    $('#payOnlClFSBtn1').prop('disabled',true);
                    var dataJ = JSON.stringify(response);
                    var dataJ2 = JSON.parse(dataJ);
                    window.location.href = dataJ2['body']['RedirectUrl'];
                },
                error: (error) => { console.log(error); }
            });
        }
    }




    function payOnlClFSSelected(opcfsId,totPay,resId){
        if(resId == 31 || resId ==32 || resId == 33 || resId == 34 || resId == 39){
        // if(1 == 6){
            $.ajax({
                url: '{{ route("oPayFStaf.oPayFStafPaySelectedRes313233") }}',
                method: 'post',
                data: {
                    onlPayRecId: opcfsId,
                    totalPay: totPay,
                    skontoCHF: $('#skontoCHF').val(),
                    skontoPer: $('#skontoPer').val(),
                    bakshishi: $('#tipValueCHF').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    $('#payOnlClFSBtn2').prop('disabled',true);
                    var dataJ = JSON.stringify(response);
                    var dataJ2 = JSON.parse(dataJ);
                    window.location.href = dataJ2['body']['RedirectUrl'];
                },
                error: (error) => { console.log(error); }
            });
        }else{
            $.ajax({
                url: '{{ route("oPayFStaf.oPayFStafPaySelectedRes") }}',
                method: 'post',
                data: {
                    onlPayRecId: opcfsId,
                    totalPay: totPay,
                    skontoCHF: $('#skontoCHF').val(),
                    skontoPer: $('#skontoPer').val(),
                    bakshishi: $('#tipValueCHF').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    $('#payOnlClFSBtn2').prop('disabled',true);
                    var dataJ = JSON.stringify(response);
                    var dataJ2 = JSON.parse(dataJ);
                    window.location.href = dataJ2['body']['RedirectUrl'];
                },
                error: (error) => { console.log(error); }
            });
        }
    }



    function payOnlClFSTakeaway(opcfsId,totPay,resId){
        if(resId == 31 || resId ==32 || resId == 33 || resId == 34 || resId == 39){
        // if(1 == 6){
            $.ajax({
                url: '{{ route("oPayFStaf.oPayFStafPayTakeaway313233") }}',
                method: 'post',
                data: {
                    onlPayRecId: opcfsId,
                    totalPay: totPay,
                    skontoCHF: $('#skontoCHF').val(),
                    skontoPer: $('#skontoPer').val(),
                    bakshishi: $('#tipValueCHF').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    $('#payOnlClFSBtn2').prop('disabled',true);
                    var dataJ = JSON.stringify(response);
                    var dataJ2 = JSON.parse(dataJ);
                    window.location.href = dataJ2['body']['RedirectUrl'];
                },
                error: (error) => { console.log(error); }
            });
        }else{
            $.ajax({
                url: '{{ route("oPayFStaf.oPayFStafPayTakeaway") }}',
                method: 'post',
                data: {
                    onlPayRecId: opcfsId,
                    totalPay: totPay,
                    skontoCHF: $('#skontoCHF').val(),
                    skontoPer: $('#skontoPer').val(),
                    bakshishi: $('#tipValueCHF').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    $('#payOnlClFSBtn2').prop('disabled',true);
                    var dataJ = JSON.stringify(response);
                    var dataJ2 = JSON.parse(dataJ);
                    window.location.href = dataJ2['body']['RedirectUrl'];
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    







    function setTip(btnId,tot){
        // bakshishView
        // if(btnId != 'tipWaiterCancel'){
        //     cTot = parseFloat($('.totalOnCart').html());
        // }
        if($('#skontoCHF').length){
            var skonto = parseFloat($('#skontoCHF').val()).toFixed(2);
            totToPay = parseFloat(tot - skonto).toFixed(2);
        }else{
            totToPay = parseFloat(tot).toFixed(2);
        }
        
        $('.waiterTipBtns').attr('class', 'btn btn-outline-dark waiterTipBtns mt-2');
        $('.waiterTipBtns').prop( "disabled", false );
        $('#'+btnId).attr('class', 'btn selectedTip waiterTipBtns mt-2');
        $('#'+btnId).prop( "disabled", true );
        
        $('#tipValueCHF').val(parseFloat(0.00).toFixed(2));

        if($('#giftCardDiscountShow').length){ var gcDiscount = parseFloat($('#giftCardDiscountShow').html()).toFixed(2);
        }else{ var gcDiscount = parseFloat(0).toFixed(2); }

        switch(btnId){
            case 'tipWaiter50':
                var shumaNew = parseFloat(parseFloat(totToPay) - parseFloat(gcDiscount) + parseFloat(0.50)).toFixed(2);
                var TipValue = parseFloat(0.50);
                var TipExt = parseFloat(0.50);

                $('#tipValueCHF').val(parseFloat(0.50).toFixed(2));
                $('#waiterTipShow').html(parseFloat(0.50).toFixed(2));
                $('#toPayShow').html(shumaNew );

                $('#tipWaiterCosVal').val(0);
            break;

            case 'tipWaiter1':
                var shumaNew = parseFloat(parseFloat(totToPay) - parseFloat(gcDiscount) + parseFloat(1)).toFixed(2);
                var TipValue = parseFloat(1);
                var TipExt = parseFloat(1);

                $('#tipValueCHF').val(parseFloat(1).toFixed(2));
                $('#waiterTipShow').html(parseFloat(1).toFixed(2));
                $('#toPayShow').html(shumaNew );

                $('#tipWaiterCosVal').val(0);
            break;

            case 'tipWaiter2':
                var shumaNew = parseFloat(parseFloat(totToPay) - parseFloat(gcDiscount) + parseFloat(2)).toFixed(2);
                var TipValue = parseFloat(2);
                var TipExt = parseFloat(2);

                $('#tipValueCHF').val(parseFloat(2).toFixed(2));
                $('#waiterTipShow').html(parseFloat(2).toFixed(2));
                $('#toPayShow').html(shumaNew );

                $('#tipWaiterCosVal').val(0);
            break;

            case 'tipWaiter5':
                var shumaNew = parseFloat(parseFloat(totToPay) - parseFloat(gcDiscount) + parseFloat(5)).toFixed(2);
                var TipValue = parseFloat(5);
                var TipExt = parseFloat(5);

                $('#tipValueCHF').val(parseFloat(5).toFixed(2));
                $('#waiterTipShow').html(parseFloat(5).toFixed(2));
                $('#toPayShow').html(shumaNew );

                $('#tipWaiterCosVal').val(0);
            break;

            case 'tipWaiter10':
                var shumaNew = parseFloat(parseFloat(totToPay) - parseFloat(gcDiscount) + parseFloat(10)).toFixed(2);
                var TipValue = parseFloat(10);
                var TipExt = parseFloat(10);

                $('#tipValueCHF').val(parseFloat(10).toFixed(2));
                $('#waiterTipShow').html(parseFloat(10).toFixed(2));
                $('#toPayShow').html(shumaNew );

                $('#tipWaiterCosVal').val(0);
            break;
            
            case 'tipWaiterCancel':
                var shumaNew = parseFloat(parseFloat(totToPay) - parseFloat(gcDiscount) + parseFloat(0)).toFixed(2);
                var TipValue = parseFloat(0);
                var TipExt = parseFloat(0);

                $('#tipValueCHF').val(parseFloat(0).toFixed(2));
                $('#waiterTipShow').html(parseFloat(0).toFixed(2));
                $('#toPayShow').html(shumaNew );

                $('#tipWaiterCosVal').val(0);
            break;
        }
    }

    function setCostume(btnId, inputId, btnValue, tot){
        btnValue = parseFloat(btnValue).toFixed(2);

        if($('#giftCardDiscountShow').length){ var gcDiscount = parseFloat($('#giftCardDiscountShow').html()).toFixed(2);
        }else{ var gcDiscount = parseFloat(0).toFixed(2); }

        if(btnValue < 0 || !$('#tipWaiterCosVal').val()){
            if($('#skontoCHF').length){
                var skonto = parseFloat($('#skontoCHF').val()).toFixed(2);
                tot = parseFloat(tot - skonto).toFixed(2);
            }else{
                tot = parseFloat(tot).toFixed(2);
            }
            var shumaNew = parseFloat(parseFloat(tot) - parseFloat(gcDiscount) + parseFloat(0)).toFixed(2);
            var TipValue = parseFloat(0);
            var TipExt = parseFloat(0);

            $('#tipValueCHF').val(parseFloat(0).toFixed(2));
            $('#waiterTipShow').html(parseFloat(0).toFixed(2));
            $('#toPayShow').html(shumaNew );
            if(btnValue < 0){ if($('#tipError01').is(':hidden')){ $('#tipError01').show(50).delay(5000).hide(50); } }
        }else{


            if($('#skontoCHF').length){
                var skonto = parseFloat($('#skontoCHF').val()).toFixed(2);
                totToPay = parseFloat(tot - skonto).toFixed(2);
            }else{
                totToPay = parseFloat(tot).toFixed(2);
            }
            
            $('.waiterTipBtns').attr('class', 'btn btn-outline-dark waiterTipBtns mt-2');
            $('.waiterTipBtns').prop( "disabled", false );
            $('#'+btnId).attr('class', 'btn selectedTip waiterTipBtns mt-2');
            $('#'+btnId).prop( "disabled", true );
            
            $('#tipValueCHF').val(parseFloat(btnValue).toFixed(2));

            var shumaNew = parseFloat(parseFloat(totToPay) - parseFloat(gcDiscount) + parseFloat(btnValue)).toFixed(2);
            var TipValue = parseFloat(btnValue);
            var TipExt = parseFloat(btnValue);

            $('#tipValueCHF').val(parseFloat(btnValue).toFixed(2));
            $('#waiterTipShow').html(parseFloat(btnValue).toFixed(2));
            $('#toPayShow').html(shumaNew );



        }

        
    }

</script>