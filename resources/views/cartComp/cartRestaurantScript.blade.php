<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

    $(document).ready(function() {
        $('.AllExtrasOrder').hide();
    });



       function payThisToo(addPayPrice , addPayPhoneNr){

            var storeSel = $('#payThisTooSelected').val();
            if(storeSel == ''){
                $('#payThisTooSelected').val(addPayPhoneNr);
                $('#payThisTooSelectedCard').val(addPayPhoneNr);
            }else{
                $('#payThisTooSelected').val(storeSel+'||'+addPayPhoneNr);
                $('#payThisTooSelectedCard').val(storeSel+'||'+addPayPhoneNr);
            }

            $('#payAllOrMineF1').val('7');
            $('#payAllOrMineF1Card').val('7');
            $('#payAllOrMineDiv').hide(300);

            
            if(addPayPhoneNr.includes('|')){
                var phoneNrGhost = addPayPhoneNr.split('|')[1];
                $('#payThisTooBtn'+phoneNrGhost).attr('class','btn btn-block btnPaySelected allBtnPay');
                $('#payThisTooBtn'+phoneNrGhost).attr('onclick','payThisTooRemove("'+addPayPrice+'","'+addPayPhoneNr+'")');

                $('.allBtnPayProd'+phoneNrGhost).attr('disabled',true);
            }else{
                $('#payThisTooBtn'+addPayPhoneNr).attr('class','btn btn-block btnPaySelected allBtnPay');
                $('#payThisTooBtn'+addPayPhoneNr).attr('onclick','payThisTooRemove("'+addPayPrice+'","'+addPayPhoneNr+'")');

                $('.allBtnPayProd'+addPayPhoneNr).attr('disabled',true);
            }
            // subTotalCart  tvshSpanCart   bakshishiView    totalOnCart
            // Add and calculate the prices
            var percent     = parseFloat("0.081"); //Perqindja 8.10%
            var newTotal    = parseFloat(parseFloat($('.totalOnCart').html()) + parseFloat(addPayPrice));
            // var newTvsh     = parseFloat(newTotal * percent);
            var newSubTotal = parseFloat(newTotal)-parseFloat($('#bakshishView').html());

            $('.totalOnCart').html(newTotal);
            // $('#tvshSpanCart').html(newTvsh);
            $('#subTotalCart').html(newSubTotal);
        }

        function payThisTooRemove(addPayPrice , addPayPhoneNr){
            var phoneNrsToPay =  $('#payThisTooSelected').val();

            var storeSel = '';
            phoneNrsToPay.split('||').forEach(eathNumber);
                               
            function eathNumber(item, index) {
                // console.log( index + ": "+ item);
                if(item != addPayPhoneNr){
                    if(storeSel == ''){
                        storeSel = item;
                    }else{
                        storeSel += storeSel+'||'+item;
                    }
                }
            }
            $('#payThisTooSelected').val(storeSel);
            $('#payThisTooSelectedCard').val(storeSel);
            // console.log($('#payThisTooSelected').val());

            if($('#payThisTooSelected').val() == ''){
                $('#payThisTooSelected').val('');
                $('#payThisTooSelectedCard').val('');
                $('#payAllOrMineF1').val('1');
                $('#payAllOrMineF1Card').val('1');
                $('#payAllOrMineDiv').show(300);
            }
                   
               
            if(addPayPhoneNr.includes('|')){
                var phoneNrGhost = addPayPhoneNr.split('|')[1];
                $('#payThisTooBtn'+phoneNrGhost).attr('class','btn btn-block btnPayNotSelected allBtnPay');
                $('#payThisTooBtn'+phoneNrGhost).attr('onclick','payThisToo("'+addPayPrice+'","'+addPayPhoneNr+'")');
                $('#payThisTooBtn'+phoneNrGhost).focusout();
            }else{
                $('#payThisTooBtn'+addPayPhoneNr).attr('class','btn btn-block btnPayNotSelected allBtnPay');
                $('#payThisTooBtn'+addPayPhoneNr).attr('onclick','payThisToo("'+addPayPrice+'","'+addPayPhoneNr+'")');
                $('#payThisTooBtn'+addPayPhoneNr).focusout();
            }

            var percent     = parseFloat("0.081"); //Perqindja 8.10%
            var newTotal    = parseFloat(parseFloat($('.totalOnCart').html()) - parseFloat(addPayPrice));
            // var newTvsh     = parseFloat(newTotal * percent);
            var newSubTotal = parseFloat(newTotal)-parseFloat($('#bakshishView').html());

            $('.totalOnCart').html(newTotal);
            // $('#tvshSpanCart').html(newTvsh);
            $('#subTotalCart').html(newSubTotal);
        }







                        function payThisTooProd(addPayPrice, tabOrId, addPayPhoneNr){
                            var storeSel = $('#payThisTooProSelected').val();
                            if(storeSel == ''){
                                $('#payThisTooProSelected').val(tabOrId);
                                $('#payThisTooProSelectedCard').val(tabOrId);
                            }else{
                                $('#payThisTooProSelected').val(storeSel+'||'+tabOrId);
                                $('#payThisTooProSelectedCard').val(storeSel+'||'+tabOrId);
                            }
                            $('#payAllOrMineF1').val('7');
                            $('#payAllOrMineF1Card').val('7');
                            $('#payAllOrMineDiv').hide(300);
                            // $('#payThisTooProdBtn'+tabOrId).attr('class','btn btnPaySelectedProd allBtnPayProd');
                            $('#payThisTooProdBtn'+tabOrId).removeClass('btnPayNotSelectedProd');
                            $('#payThisTooProdBtn'+tabOrId).addClass('btnPaySelectedProd');
                            $('#payThisTooProdBtn'+tabOrId).attr('onclick','payThisTooProdRemove("'+addPayPrice+'","'+tabOrId+'","'+addPayPhoneNr+'")');

                            // subTotalCart  tvshSpanCart   bakshishiView    totalOnCart
                            // Add and calculate the prices
                            var percent     = parseFloat("0.081"); //Perqindja 8.10%
                            var newTotal    = parseFloat(parseFloat($('#subTotalCart').html()) + parseFloat(addPayPrice) + parseFloat($('#bakshishView').html()));
                            // var newTvsh     = parseFloat(newTotal * percent);
                            var newSubTotal = parseFloat(newTotal) - parseFloat($('#bakshishView').html());

                            $('.totalOnCart').html(newTotal);
                            // $('#tvshSpanCart').html(newTvsh);
                            $('#subTotalCart').html(newSubTotal);

                            if(addPayPhoneNr.includes('|')){
                                var phoneNrGhost = addPayPhoneNr.split('|')[1];
                                $('#payThisTooBtn'+phoneNrGhost).hide();
                            }else{
                                $('#payThisTooBtn'+addPayPhoneNr).hide(); 
                            }
                        }

                        function payThisTooProdRemove(addPayPrice, tabOrId, addPayPhoneNr){
                            var prodsToPay =  $('#payThisTooProSelected').val();
                            var storeSel = '';
                            prodsToPay.split('||').forEach(eathProd);
                            function eathProd(item, index) {
                                // console.log( index + ": "+ item);
                                if(item != tabOrId){
                                    if(storeSel == ''){ storeSel = item; }else{ storeSel += storeSel+'||'+item; }
                                }
                            }
                            $('#payThisTooProSelected').val(storeSel);

                            if($('#payThisTooProSelected').val() == ''){
                                $('#payThisTooProSelected').val('');
                                $('#payAllOrMineF1').val('1');
                                $('#payAllOrMineF1Card').val('1');
                                $('#payAllOrMineDiv').show(300);
                            }

                            // $('#payThisTooProdBtn'+tabOrId).attr('class','btn btnPayNotSelectedProd allBtnPayProd');
                            $('#payThisTooProdBtn'+tabOrId).removeClass('btnPaySelectedProd');
                            $('#payThisTooProdBtn'+tabOrId).addClass('btnPayNotSelectedProd');
                            $('#payThisTooProdBtn'+tabOrId).attr('onclick','payThisTooProd("'+addPayPrice+'","'+tabOrId+'","'+addPayPhoneNr+'")');
                            $('#payThisTooProdBtn'+tabOrId).focusout();
                            var percent     = parseFloat("0.081"); //Perqindja 8.10%
                            var newTotal    = parseFloat(parseFloat($('.totalOnCart').html()) - parseFloat(addPayPrice) );
                            // var newTvsh     = parseFloat(newTotal * percent);
                            var newSubTotal = parseFloat(newTotal)-parseFloat($('#bakshishView').html());

                            $('.totalOnCart').html(newTotal);
                            // $('#tvshSpanCart').html(newTvsh);
                            $('#subTotalCart').html(newSubTotal);
                        }





















                        function removeThisExtraFromProd(thisId, extOne, p1, ExtPrice, p2) {                       
                            var rIdGo = $('.rowIdGo'+p1+'').val();
                            var newP =parseFloat($('#setPrice'+rIdGo+'').html()) - parseFloat(ExtPrice);
                            // alert(ExtPrice);
                            // console.log(thisId);
                            // console.log(extOne);
                            // console.log( $('.AllExtraGo'+p1+'').val(),);
                            // console.log(rIdGo);
                            // console.log('-------------');

                            $.ajax({
                                url: '{{ route("produktet.CartRe") }}',
                                method: 'post',
                                data: {
                                    elementId: rIdGo,
                                    extPro: extOne,
                                    allExtra: $('.AllExtraGo'+p1+'').val(),
                                    _token: '{{csrf_token()}}'
                                },
                                success: (response) => {

                                    // console.log(response);
                                    if(response == 'no'){
                                    $('#finishOrder').show(300).delay(3500).hide();
                                    }else{
                                        
                                        $('#setPrice'+rIdGo+'').html(parseFloat(newP).toFixed(2));

                                        var current = ($('#CartTotalFooter').text()).split(" ")[0];
                                        var price = extOne.split("||")[2];
                                        $('#CartTotalFooter').text((current - price).toFixed(2) + ' CHF');

                                        var data = JSON.parse(response);
                                        $('.rowIdGo'+p1+'').val(data.rowId);
                                        var options = data.options;
                                        $('.AllExtraGo'+p1+'').val(options.ekstras);  
                                        $('#setPrice'+rIdGo+'').attr('id','setPrice'+data.rowId+'')
                                        $('#' + thisId).hide(100);
                                        // $('#footerShowOrdersMobile').load('/ #footerShowOrdersMobile',
                                        // function() {
                                            $('#cartPart2').load('/order #cartPart2',function() {
                                                $('#setPriceSasiaAll'+p1).html(parseFloat(parseFloat($('#setPrice'+data.rowId).html()) * parseFloat($('#currentQTY'+p1).val())) );
                                            });
                                        // });
                                    }
                                },
                                error: (error) => {
                                    console.log(error);
                                    alert('Oops! Something went wrong')
                                }
                            })
                        }



















                    function showOrderExtras(orderExt,finOr) {
                        $('.orderExtra'+orderExt).show('slow');
                        $('#buttonExt' + orderExt).hide('slow');
                        if(finOr == 1){
                            $('#divExtra' + orderExt).attr('style',"border-bottom:1px solid lightgray; padding-bottom:15px; background-color:rgb(159, 245, 188);");
                        }else if(finOr == 9){
                            $('#divExtra' + orderExt).attr('style',"border-bottom:1px solid lightgray; padding-bottom:15px; background-color:rgb(252, 134, 134);");
                        }else{
                            $('#divExtra' + orderExt).attr('style',"border-bottom:1px solid lightgray; padding-bottom:15px;");
                        }

                    }
































    //---------------------------------------------------------------------------------------------
    // CART PART 2 



    function setPayAllMine(tV,totTa,limFree){
        $('#payAllOrMineF1').val(tV);
        $('#payAllOrMineF1Card').val(tv);

        if(tV == 1){
            $('.allBtnPay').attr('disabled','false');

            $("#btnPay1").attr('class', 'btn btn-primary');
            $("#btnPay2").attr('class', 'btn btn-outline-primary');
            $("#totalShowCh").load(location.href+" #totalShowCh>*","");
            $("#theBakshishDivAll").load(location.href+" #theBakshishDivAll>*","");   
                            
            if(parseFloat(totTa) >= parseFloat(limFree)){
                $('#showFreeForTotPay').show();
            }else{ 
                $('#showFreeForTotPay').attr('style','display:none !important;');
                $('#freeShotPh1Id').val(0);
                $('#freeShotPh1IdCard').val(0);
                $('#freeShotPh2Id').val(0);
            }
        }else if(tV == 9){
            $('.allBtnPay').attr('disabled','true');

            $("#btnPay1").attr('class', 'btn btn-outline-primary');
            $("#btnPay2").attr('class', 'btn btn-primary');

            var newTotal = (parseFloat(totTa) + parseFloat($('#bakshishView').html())).toFixed(2); 
            // var tvsh = parseFloat(newTotal * 0.081).toFixed(2);
            $('.totalOnCart').html(newTotal);
            // $('#tvshSpanCart').html(tvsh);
            $('#subTotalCart').html((parseFloat(newTotal)).toFixed(2));

            if(parseFloat(totTa) >= parseFloat(limFree)){
                $('#showFreeForTotPay').show();
            }else{ 
                $('#showFreeForTotPay').attr('style','display:none !important;');
                $('#freeShotPh1Id').val(0);
                $('#freeShotPh1IdCard').val(0);
                $('#freeShotPh2Id').val(0);
            }
        }
    }









    function showCuponInput(){
        $('#cuponInputStarter').hide(100);
        $('#cuponInputDiv').show(100);
        $('#cuponInputDiv2').show(100);
        $('#cuponCheckBtn').show(100);

        $('#couponWon1').hide(100);
        $('#couponWon2').hide(100);
    }
    function showCuponInputWC(cCode){
        $('#cuponInputStarter').hide(100);
        $('#cuponInputDiv').show(100);
        $('#cuponInputDiv2').show(100);
        $('#cuponCheckBtn').show(100);

        $('#couponWon1').hide(100);
        $('#couponWon2').hide(100);

        $('#cuponTypedCart').val(cCode);
    }

    function checkCupon(resId,clPhNr, ct){
        $.ajax({
            url: '{{ route("cupons.checkCupon") }}',
            method: 'post',
            data: {
                res: resId,
                code: $('#cuponTypedCart').val(),
                clPN: clPhNr,
                cartTot: ct,
                _token: '{{csrf_token()}}'
            },
            success: (res) => {
                res = $.trim(res);
                $('#activateTheCouponText').hide(50);
                if(res == 'no' ){
                    $('#cuponOffError').show(200).delay(3000).hide(200);
                }else if(res == 'noUsed'){
                    $('#cuponOffErrorUsed').show(200).delay(3000).hide(200);
                }else if(res == 'noETot'){
                    $('#cuponOffErrorNotETot').show(200).delay(4000).hide(200);
                }else if(res == 'noUsesLeft'){
                    $('#cuponOffErrorNoMLeft').show(200).delay(4000).hide(200);
                }else{
                    this.procesCoupun1(res,res.split('||')[3],ct);    
                }
            },
            error: (error) => { console.log(error); }
        });
    }
    function procesCoupun1(res,typ,cartTot){

        var res2D = res.split('||');

        if(typ == '1'){var coupVal = res2D[0] ;}
        else if(typ == '2'){var coupVal = res2D[1] ;}
        else if(typ == '3'){var coupVal = res2D[2] ;}

        if(typ == '2' || typ == '1'){
            if($('.tipValueConfirmValueCLA').val() != 0){
                var tip = parseFloat($('.tipValueConfirmValueCLA').val()).toFixed(2);

                if(typ == '1'){ var offDeal = parseFloat(parseFloat(coupVal*0.01)*cartTot).toFixed(2);}
                else{ var offDeal = res2D[1]; }

                var newOffDeal = roundNrByFiveCentTwoPP(offDeal);
          
                var afterTotalPre = parseFloat(cartTot-newOffDeal).toFixed(2);
                var afterTotal = parseFloat(parseFloat(afterTotalPre)+parseFloat(tip)).toFixed(2);
                var afterSubTotal = afterTotalPre;
            }else{       
                if(typ == '1'){ var offDeal = parseFloat(parseFloat(coupVal*0.01)*cartTot).toFixed(2);}
                else{ var offDeal = res2D[1]; }

                var newOffDeal = roundNrByFiveCentTwoPP(offDeal);
                console.log('off val : '+offDeal);
                console.log('off val new: '+newOffDeal);

                var afterTotal = parseFloat(cartTot-newOffDeal).toFixed(2);
                var afterSubTotal = afterTotal;
            }
            $('.totalOnCart').html(afterTotal);
            $('.subTotalCart').html(afterSubTotal);
        }

        $('#codeUsedValueID').val(newOffDeal);
        $('#codeUsedValueIDCard').val(newOffDeal);
        $('#couponUsedId').val(res2D[5]);
        $('#couponUsedCardId').val(res2D[5]);

        // if( $('#codeUsedValueID').val() == '')
        //     $('#codeUsedValueID').val(res['id']);
        // else
        //     $('#codeUsedValueID').val($('#codeUsedValueID').val()+'||'+res['id']);
    
        // Alert the user SUCCESS
        if(typ == 1){
            $('#couponsSaved').append('<p style="width:100%;" class=" alert alert-success mb-1">'+res2D[4]+'-> '+res2D[0]+' % => '+newOffDeal+' CHF </p>');
        }else if(typ == 2){
            $('#couponsSaved').append('<p style="width:100%;" class=" alert alert-success mb-1">'+res2D[4]+'-> '+res2D[1]+' CHF</p>');
        }else if(typ == 3){
            $('#couponsSaved').append('<p style="width:100%;" class=" alert alert-success mb-1">'+res2D[4]+'-> kostenloses Produkt : '+res2D[2]+' </p>');
        }

        $( "#cuponCheckBtn" ).prop( "disabled", true );
        $( "#cuponTypedCart" ).prop( "disabled", true );
                                    
    }

    function roundNrByFiveCentTwoPP(offD){
        return roundInterval(parseFloat(offD), parseFloat('0.05'), parseFloat('2'), "up");
    }

    function roundInterval(number, interval, round, roundType) {
       
        number = number > 999999999999999 ? 999999999999999 : number;
        var isMinus = false;
        if (number < 0) {
            isMinus = true;
            number = number * -1;
        }
        number = parseFloat(numberToString(number, round));
        interval = parseFloat(numberToString(interval, round));
        var multiplier = roundType == 'up' ? Math.ceil(number / interval) : Math.floor(number / interval);
        
        number = multiplier * interval;
        
        number = multiplier * interval;
        if (isMinus) {
            number = number * -1;
        }
        return parseFloat(number.toFixed(round)).toFixed(2);
    }

    function numberToString(number, dp) {
        var format = '#';
       
        
        if (dp > 0) {
            format += ".";
            format += "0000000000".substr(0, dp);
        }
        else if (dp > 0) {
            format += ".";
            format += "##########".substr(0, dp);
        }
        number = number.toString();
        var minus = number.substr(0, 1) == '-' ? '-' : '';
        var ln = "";
        if (number.lastIndexOf("e+") > -1) {
            ln = number.substr(0, number.lastIndexOf("e+"));
            for (var i = ln.length - 2; i < parseInt(number.substr(number.lastIndexOf("e+") + 1)); i++) {
                ln += "0";
            }
            ln = ln.replace(/[^0-9]/g, '');
            number = ln;
        }
        var tail = format.lastIndexOf('.'), nail = number.lastIndexOf('.');
        if (nail < 0 && tail >= 0) {
            number = number + "." + format.substr(tail + 1, 1);
            nail = number.lastIndexOf('.');
        }
        tail = tail > -1 && nail > -1 ? format.substr(tail) : '';
        var numtail = number.substr(number.indexOf(".") ) ;
        if(tail.length > 0 && dp !== undefined && dp > 0) {
            tail = tail.substr(0, dp + 1);
            var tails = tail.split(''), ntail = "", canPop = true;
            for (var i = 1; i < tails.length; i++) {
                if ((tails[i] == '#' || tails[i].match(/([0-9])/g)) && numtail.length > i) {
                    ntail += numtail.substr(i, 1);                    
                }
                else if(tails[i] == '#') {
                    ntail += '0';
                }
                else {
                    ntail += tails[i];
                }
            }
            var ttail = ntail.split(''), ptail = tail.substr(1).split('');
            for(var i = ttail.length - 1; i > -1; i--){
                if (ptail[i] == '#' && canPop && (ttail[i] == '0' || ttail[i] == '#')) {
                    ntail = ntail.substr(0, ntail.length - 1);
                }
                else {
                    canPop = false;
                }
            }
            if (ntail.length > 0) {
                tail = "." + ntail;
            }
            else {
                tail = "";   
            }
        }
        number = number.replace(/\..*|[^0-9]/g,'').split('');
        format = format.replace(/\..*/g,'').split('');
        for(var i = format.length - 1; i > -1; i--){
            if(format[i] == '#') {
                format[i]=number.pop()
            }
        }
        number = minus + number.join('') + format.join('') + tail;
        return number;
    }


















                


                    function setCostume(btnId, inputId, btnValue, cTot){
                        cTot = parseFloat($('#subTotalCart').html());
                            
                        $('.waiterTipBtns').attr('class', 'btn btn-outline-dark waiterTipBtns mt-2');
                        $('.waiterTipBtns').prop( "disabled", false );
                        $('#'+btnId).attr('class', 'btn selectedTip waiterTipBtns mt-2');
                        $('#'+btnId).prop( "disabled", true );

                        btnValue = parseFloat($('#'+inputId).val()).toFixed(2);

                        if(btnValue > 0){

                            if($('#payAllOrMineF1').val() == 9){
                                    var Shuma = (parseFloat($('#totalOfTableCHF').val()) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(btnValue)).toFixed(2);
                                }else{
                                    var Shuma = parseFloat(parseFloat(cTot) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(btnValue)).toFixed(2);
                                }


                            
                                var TipValue = parseFloat(btnValue);
                                var TipExt = 'other';

                                // var dealAm =parseInt($('#pointsUsedIdFS').val());
                                var dealAm =0;
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }


                            // alert(tipShuma[tipShuma.length-1]);
                            $('#bakshishView').text(parseFloat(TipValue).toFixed(2)); 

                            $('#tipValueSendId').val(TipExt);
                            $('#tipValueCHFSendId').val(parseFloat(TipValue));

                            $('.tipValueConfirmValueCLA').val(parseFloat(TipValue));
                            $('.tipValueConfirmTitleCLA').val(parseFloat(TipExt));

                            $('.totalOnCart').text(Shuma);

                        }else{
                            $('#bakshishView').text(parseFloat(0).toFixed(2)); 

                            $('#tipValueSendId').val(0);
                            $('#tipValueCHFSendId').val(parseFloat(0));

                            $('.tipValueConfirmValueCLA').val(parseFloat(0));
                            $('.tipValueConfirmTitleCLA').val(parseFloat(0));

                            $('.totalOnCart').text(cTot);

                        }
                    }


                    function setTip(btnId, cTot){
                        // bakshishView
                        // if(btnId != 'tipWaiterCancel'){
                        //     cTot = parseFloat($('.totalOnCart').html());
                        // }
                        cTot = parseFloat($('#subTotalCart').html());

                        $('.waiterTipBtns').attr('class', 'btn btn-outline-dark waiterTipBtns mt-2');
                        $('.waiterTipBtns').prop( "disabled", false );
                        $('#'+btnId).attr('class', 'btn selectedTip waiterTipBtns mt-2');
                        $('#'+btnId).prop( "disabled", true );
                        

                        $('#tipValueSendId').val();
                        $('#tipValueCHFSendId').val();

                        switch(btnId){
                            case 'tipWaiter50':
                                if($('#payAllOrMineF1').val() == 9){
                                    var Shuma = parseFloat(parseFloat($('#totalOfTableCHF').val()) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(0.5)).toFixed(2);
                                }else{
                                    var Shuma = parseFloat(parseFloat(cTot) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(0.5)).toFixed(2);
                                }
                                
                                var TipValue = parseFloat(0.5);
                                var TipExt = parseFloat(0.5);

                                
                                // var dealAm =parseInt($('#pointsUsedIdFS').val());
                                var dealAm =0;
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                            break;

                            case 'tipWaiter1':

                                if($('#payAllOrMineF1').val() == 9){
                                    var Shuma = parseFloat(parseFloat($('#totalOfTableCHF').val()) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(1)).toFixed(2);
                                }else{
                                    var Shuma = parseFloat(parseFloat(cTot) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(1)).toFixed(2);
                                }

                                var TipValue = parseFloat(1);
                                var TipExt = parseFloat(1);

                                
                                // var dealAm =parseInt($('#pointsUsedIdFS').val());
                                var dealAm = 0;
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                              
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
         
                            break;

                            case 'tipWaiter2':
                                if($('#payAllOrMineF1').val() == 9){
                                    var Shuma = parseFloat(parseFloat($('#totalOfTableCHF').val()) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(2)).toFixed(2);
                                }else{
                                    var Shuma = parseFloat(parseFloat(cTot) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(2)).toFixed(2);
                                }
                                var TipValue = parseFloat(2);
                                var TipExt = parseFloat(2);

                                
                                // var dealAm =parseInt($('#pointsUsedIdFS').val());
                                var dealAm =0;
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                            break;
                            case 'tipWaiter5':
                                if($('#payAllOrMineF1').val() == 9){
                                    var Shuma = parseFloat(parseFloat($('#totalOfTableCHF').val()) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(5)).toFixed(2);
                                }else{
                                    var Shuma = parseFloat(parseFloat(cTot) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(5)).toFixed(2);
                                }
                                var TipValue = parseFloat(5);
                                var TipExt = parseFloat(5);

                                
                                // var dealAm =parseInt($('#pointsUsedIdFS').val());
                                var dealAm =0;
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                            break;
                            case 'tipWaiter10':
                                if($('#payAllOrMineF1').val() == 9){
                                    var Shuma = parseFloat(parseFloat($('#totalOfTableCHF').val()) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(10)).toFixed(2);
                                }else{
                                    var Shuma = parseFloat(parseFloat(cTot) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(10)).toFixed(2);
                                }
                                var TipValue = parseFloat(10);
                                var TipExt = parseFloat(10);

                                
                                // var dealAm =parseInt($('#pointsUsedIdFS').val());
                                var dealAm =0;
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                            break;
                            case 'tipWaiterCancel':
                                if($('#payAllOrMineF1').val() == 9){
                                    var Shuma = parseFloat(parseFloat($('#totalOfTableCHF').val()) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(0)).toFixed(2);
                                }else{
                                    var Shuma = parseFloat(parseFloat(cTot) - parseFloat($('#cancelOrderSumInVal').val()) + parseFloat(0)).toFixed(2);
                                }
                                var TipValue = parseFloat(0);
                                var TipExt = parseFloat(0);

                                
                                // var dealAm =parseInt($('#pointsUsedIdFS').val());
                                var dealAm =0;
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                                
                            break;

                         
                            
                        }
                            // alert(tipShuma[tipShuma.length-1]);
                            $('#bakshishView').text(parseFloat(TipValue).toFixed(2)); 

                            $('#tipValueSendId').val(TipExt);
                            $('#tipValueCHFSendId').val(parseFloat(TipValue));

                            $('.tipValueConfirmValueCLA').val(parseFloat(TipValue));
                            $('.tipValueConfirmTitleCLA').val(parseFloat(TipExt));

                            
                            if($('#codeUsedValueID').val() != 0){
                                var dealOffIs = parseFloat($('#codeUsedValueID').val());
                                $('.totalOnCart').text(parseFloat(Shuma - dealOffIs).toFixed(2));
                            }else{
                                $('.totalOnCart').text(Shuma);
                            }
                        
                    }












                        function setNameTA(nameValue){
                            $('#nameTA01').val(nameValue);
                            $('#nameTA02').val(nameValue);
                        }
                        function setLastameTA(lastnameValue){
                            $('#lastnameTA01').val(lastnameValue);  
                            $('#lastnameTA02').val(lastnameValue);  
                        }
                        function setTimeTA(timeValue){
                            $('#timeTA01').val(timeValue);
                            $('#timeTA02').val(timeValue);
                        }


                        function setFreeShots(checkId, proId){
                            if($('#'+checkId).prop("checked") == true){
                                console.log("Checkbox is checked.");
                                $('.freeShots').prop('checked', false);
                                $('#'+checkId).prop('checked', true);

                                $('#freeShotPh1Id').val(proId);
                                $('#freeShotPh1IdCard').val(proId);
                                $('#freeShotPh2Id').val(proId);

                            }else if($('#'+checkId).prop("checked") == false){
                                $('.freeShots').prop('checked', false);

                                $('#freeShotPh1Id').val(0);
                                $('#freeShotPh1IdCard').val(0);
                                $('#freeShotPh2Id').val(0);
                            }
                        }

                        function usePoints(){
                            $('.ponitsEarn').hide(500);
                            $('.pointsUse').show(500);
                        }
                        function earnPointsBack(){
                            $('.ponitsEarn').show(500);
                            $('.pointsUse').hide(500);
                        }

                        function setUsePoints(){
                            var PointsVal = parseInt($('#pointUserAmount').val());

                            if(parseInt(PointsVal) <= 0 || $('#pointUserAmount').val() == ''){
                                    // Nder 0 ose 0
                                $('#invalidePointsUse11').show(100).delay(2500).hide(100);
                            }else if( parseInt(PointsVal) > parseInt($('#userPointsB').val())){
                                    // Nuk ka pike te mjaftueshme
                                
                                $('#invalidePointsUse22').show(100).delay(2500).hide(100);
                            }else{

                                    //Cart Total  
                                var newVal = parseFloat($('.totalOnCart').html()) - (parseFloat(PointsVal)*0.01);
                                $('.totalOnCart').html(parseFloat(newVal).toFixed(2));

                                    // Send variable to cash payment Step 1
                                var pointsU = parseInt($('#pointsUsedIdFS').val()) + parseInt((PointsVal));
                                $('#pointsUsedIdFS').val(parseInt(pointsU));

                                    // Alarm the user for its used points
                                var actPoints = parseInt( $('#userPointsB').val()) - parseInt(PointsVal);
                                $('#userPointsB').val(parseInt(actPoints));
                                $('#userPointsBShow').html(actPoints+' p  ( '+parseInt(pointsU)+' p)');

                                    // Change the subtotal amount subTotalCart
                                var subtot = parseFloat(newVal);
                                $('#subTotalCart').html(parseFloat(subtot).toFixed(2));
                                
                            }
                            
                        }















                        function payUnSelectThisToPay(tOId){
                            if($('#payUnpaidSelectedPr').val() == ''){
                                $('#payUnpaidSelectedPr').val(tOId);
                            }else{
                                $('#payUnpaidSelectedPr').val( $('#payUnpaidSelectedPr').val()+'||'+tOId);
                            }
                            $('#payUnpaidSelTick'+tOId).attr('class','pt-3 far fa-2x fa-check-circle')
                        }


                        function adminProdsToCart(res,tNr){
                            if($('#payUnpaidSelectedPr').val() == ''){
                                $('#payUnpaidProductsError01').show(200).delay(3000).hide(200);
                            }else{
                                $.ajax({
                                    url: '{{ route("cart.registerAdminToClUn") }}',
                                    method: 'post',
                                    data: {
                                        resId: res,
                                        tableNr: tNr,
                                        selPro: $('#payUnpaidSelectedPr').val(),
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: () => {location.reload();},
                                    error: (error) => {
                                        console.log(error);
                                        alert('bitte aktualisieren und erneut versuchen!');
                                    }
                                });
                            }
                        }

    


                        function sendCodeReturnGhost(){
                            let ghostCode = $('#returnGhostCode').val();
                            if(ghostCode == ''){
                                // empty code
                                $('#returnGhostError').html("Schreiben Sie bitte zuerst den Code!");
                                $('#returnGhostError').show(200).delay(3000).hide(200);
                            }else if(ghostCode.length != 4){
                                // invalid code
                                $('#returnGhostError').html("der Code wird nicht akzeptiert!");
                                $('#returnGhostError').show(200).delay(3000).hide(200);
                            }else{
                                // send for return
                                $.ajax({
                                    url: '{{ route("cart.registerAdminToClUnFCode") }}',
                                    method: 'post',
                                    data: {
                                        gCode: ghostCode,
                                        res:  $('#theRestaurant').val(),
                                        tableNr: $('#theTable').val(),
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: (res) => {
                                        res = res.replace(/\s/g, '');
                                        if(res == 'zerroGhostProds'){
                                            $('#returnGhostError').html("diesen GhostCart gibt es nicht!");
                                            $('#returnGhostError').show(200).delay(3000).hide(200);  
                                        }else{
                                            location.reload();
                                        }
                                        
                                    },
                                    error: (error) => {
                                        console.log(error);
                                        alert('bitte aktualisieren und erneut versuchen!');
                                    }
                                });
                            }
                        }


</script>