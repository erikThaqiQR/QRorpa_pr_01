<script>
    var padUsed = 0;
    var adminCount = 1;
    var contrVer = 1;

    function chngContrVer(verNr){
        if(verNr != contrVer){
            contrVer = verNr;
            $('#addANewContractModalBody').load(location.href+" #addANewContractModalBody>*", function() {
                if(verNr == 1){
                    $('#contrV1Btn').removeClass('btn-outline-success');
                    $('#contrV1Btn').addClass('btn-success');

                    $('#contrV2Btn').removeClass('btn-success');
                    $('#contrV2Btn').addClass('btn-outline-success');

                }else if(verNr == 2){
                    $('#contrV1Btn').removeClass('btn-success');
                    $('#contrV1Btn').addClass('btn-outline-success');

                    $('#contrV2Btn').removeClass('btn-outline-success');
                    $('#contrV2Btn').addClass('btn-success');

                    $('#addNCon_tables1').hide(50);
                    $('#addNCon_tables2').attr('style','width:38.5%');
                    $('#addNCon_tables3').attr('style','width:38.5%');

                    $('#addNCon_Takeaway1').hide(50);
                    $('#addNCon_Takeaway2').attr('style','width:44%');
                    $('#addNCon_Takeaway3').attr('style','width:30%');
                    $('#addNCon_Takeaway3').html('8%');
                    $('#addNCon_Takeaway3').attr('onclick','selectTakeawayPay("0","0","0","8")');

                    $('#addNCon_Delivery1').hide(50);
                    $('#addNCon_Delivery2').attr('style','width:44%');
                    $('#addNCon_Delivery3').attr('style','width:30%');
                    $('#addNCon_Delivery3').html('8%');
                    $('#addNCon_Delivery3').attr('onclick','selectDeliveryPay("0","0","0","8")');

                    $('#addNCon_Einmalige').prop('disabled', false);
                }
            });
        }
    }

     
    // llogarit de shfaq sa tavolina i ka restoranti 
    function countAddNConTablesAmount(val){
        var lastChar = val[val.length -1];
        if(lastChar != '-' && lastChar != ','){
            let istabFoValid = /^[0-9,-]+$/.test(val);
            var val2D = val.split(',');

            if (!istabFoValid) {
                if($('#addNCon_error07').is(":hidden")){
                    $('#addNCon_error07').show(50).delay(4500).hide(50);
                }
                $('#addNCon_tables_amount').html(0);
                $('#addNCon_tablesCope').val(0);
                $('#addNCon_tablesPerMonth').val(0);
                $('#addNCon_tablesProvision').val(0);
                $('#addNCon_tablesFixedPerMonth').val(0);
                $('#addNCon_tablesPercentage').val(0);
                $('#addNCon_tables1').removeClass('serviceBtnSelected'); $('#addNCon_tables1').addClass('serviceBtn');
                $('#addNCon_tables2').removeClass('serviceBtnSelected'); $('#addNCon_tables2').addClass('serviceBtn');
                $('#addNCon_tables3').removeClass('serviceBtnSelected'); $('#addNCon_tables3').addClass('serviceBtn');
                $('#addNCon_tables1').html('#'); $('#addNCon_tables2').html('#'); $('#addNCon_tables3').html('#');
                $('#addNCon_tables1').attr('onclick','selectTablePayment("0","0","0","0")');
                $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","0","0")');
                $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","0")');
                $('#addNCon_Einmalige').val(0);
                $('#flyerCost').val(0);
            }else{
                var countTab = parseInt(0);
                $.each(val2D, function( index, tables ) {
                    if (tables.indexOf('-') == -1){
                        // just one number
                        countTab++;
                    }else if(tables.indexOf('-') >= 0){
                        tables2D = tables.split('-');
                        if(tables2D[1] > tables2D[0]){
                            countTab += parseInt(parseInt(tables2D[1]) - parseInt(tables2D[0]) + parseInt(1));
                        }
                    }
                });

            


                $('#addNCon_tables_amount').html(countTab);
                $('#addNCon_tablesCope').val(countTab);
                
                $('#addNCon_tablesPerMonth').val(0);
                $('#addNCon_tablesProvision').val(0);
                $('#addNCon_tablesFixedPerMonth').val(0);
                $('#addNCon_tablesPercentage').val(0);
                $('#addNCon_tables1').removeClass('serviceBtnSelected'); $('#addNCon_tables1').addClass('serviceBtn');
                $('#addNCon_tables2').removeClass('serviceBtnSelected'); $('#addNCon_tables2').addClass('serviceBtn');
                $('#addNCon_tables3').removeClass('serviceBtnSelected'); $('#addNCon_tables3').addClass('serviceBtn');
                // These are static 
                if(contrVer == 1){
                    if(countTab <= 0){ 
                        $('#addNCon_tables1').html('#'); $('#addNCon_tables2').html('#'); $('#addNCon_tables3').html('#');
                        $('#addNCon_tables1').attr('onclick','selectTablePayment("0","0","0","0")');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","0","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","0")');
                        $('#addNCon_Einmalige').val(0);
                        $('#flyerCost').val(0);
                    }else if(countTab >= 1 && countTab <= 10){ 
                        $('#addNCon_tables1').html('89 <sub>CHF</sub>/monat +1% provision'); $('#addNCon_tables2').html('245 <sub>CHF</sub>/month'); $('#addNCon_tables3').html('8%');
                        $('#addNCon_tables1').attr('onclick','selectTablePayment("89","1","0","0")');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","245","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","8")');
                        $('#addNCon_Einmalige').val(60);
                        $('#flyerCost').val(60);
                    }else if(countTab >= 11 && countTab <= 30){ 
                        $('#addNCon_tables1').html('119 <sub>CHF</sub>/monat +1% provision'); $('#addNCon_tables2').html('345 <sub>CHF</sub>/month'); $('#addNCon_tables3').html('7.5%');
                        $('#addNCon_tables1').attr('onclick','selectTablePayment("119","1","0","0")');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","345","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","7.5")');
                        $('#addNCon_Einmalige').val(99);
                        $('#flyerCost').val(99);
                    }else if(countTab >= 31 && countTab <= 50){ 
                        $('#addNCon_tables1').html('149 <sub>CHF</sub>/monat +1% provision'); $('#addNCon_tables2').html('399 <sub>CHF</sub>/month'); $('#addNCon_tables3').html('7%');
                        $('#addNCon_tables1').attr('onclick','selectTablePayment("149","1","0","0")');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","399","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","7")');
                        $('#addNCon_Einmalige').val(149);
                        $('#flyerCost').val(149);
                    }else if(countTab >= 51 && countTab <= 100){ 
                        $('#addNCon_tables1').html('199 <sub>CHF</sub>/monat +1% provision'); $('#addNCon_tables2').html('445 <sub>CHF</sub>/month'); $('#addNCon_tables3').html('6.5%');
                        $('#addNCon_tables1').attr('onclick','selectTablePayment("199","1","0","0")');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","445","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","6.5")');
                        $('#addNCon_Einmalige').val(199);
                        $('#flyerCost').val(199);
                    }else if(countTab >= 101 && countTab <= 200){ 
                        $('#addNCon_tables1').html('299 <sub>CHF</sub>/monat +1% provision'); $('#addNCon_tables2').html('599 <sub>CHF</sub>/month'); $('#addNCon_tables3').html('6%');
                        $('#addNCon_tables1').attr('onclick','selectTablePayment("299","1","0","0")');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","599","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","6")');
                        $('#addNCon_Einmalige').val(245);
                        $('#flyerCost').val(245);
                    }else if(countTab >= 201 && countTab <= 500){ 
                        $('#addNCon_tables1').html('499 <sub>CHF</sub>/monat +1% provision'); $('#addNCon_tables2').html('899 <sub>CHF</sub>'); $('#addNCon_tables3').html('5.5%');
                        $('#addNCon_tables1').attr('onclick','selectTablePayment("499","1","0","0")');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","899","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","5.5")');
                        $('#addNCon_Einmalige').val(299);
                        $('#flyerCost').val(299);
                    }else if(countTab >= 501 ){ 
                        $('#addNCon_tables1').html('799 <sub>CHF</sub>/monat +1% provision'); $('#addNCon_tables2').html('1299 <sub>CHF</sub>'); $('#addNCon_tables3').html('5%');
                        $('#addNCon_tables1').attr('onclick','selectTablePayment("799","1","0","0")');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","1299","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","5")');
                        $('#addNCon_Einmalige').val(399);
                        $('#flyerCost').val(399);
                    }
                }else if(contrVer == 2){
                    if(countTab <= 0){ 
                        $('#addNCon_tables2').html('#'); 
                        $('#addNCon_tables3').html('#');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","0","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","0")');
                    }else if(countTab >= 1 && countTab <= 200){ 
                        $('#addNCon_tables2').html('750.- <sub>CHF</sub>'); 
                        $('#addNCon_tables3').html('8%');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","750","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","8")');
                    }else if(countTab >= 201 && countTab <= 500){ 
                        $('#addNCon_tables2').html('950.- <sub>CHF</sub>'); 
                        $('#addNCon_tables3').html('8%');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","950","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","8")');
                    }else if(countTab >= 501){ 
                        $('#addNCon_tables2').html('1250.- <sub>CHF</sub>'); 
                        $('#addNCon_tables3').html('8%');
                        $('#addNCon_tables2').attr('onclick','selectTablePayment("0","0","1250","0")');
                        $('#addNCon_tables3').attr('onclick','selectTablePayment("0","0","0","8")');
                    }
                }
                updateTotalPays();
            }
        }
    }



    function selectTablePayment(perMon, provision, fixed, percentage){
        // $("#addNCon_tables").prop('disabled', true);

        if(perMon != 0){
            $('#addNCon_tables1').removeClass('serviceBtn'); 
            $('#addNCon_tables1').addClass('serviceBtnSelected');
            $('#addNCon_tables2').removeClass('serviceBtnSelected'); $('#addNCon_tables2').addClass('serviceBtn');
            $('#addNCon_tables3').removeClass('serviceBtnSelected'); $('#addNCon_tables3').addClass('serviceBtn');
        }else if(fixed != 0){
            $('#addNCon_tables2').removeClass('serviceBtn');
            $('#addNCon_tables2').addClass('serviceBtnSelected');
            $('#addNCon_tables1').removeClass('serviceBtnSelected'); $('#addNCon_tables1').addClass('serviceBtn');
            $('#addNCon_tables3').removeClass('serviceBtnSelected'); $('#addNCon_tables3').addClass('serviceBtn');
        }else if(percentage != 0){
            $('#addNCon_tables3').removeClass('serviceBtn');
            $('#addNCon_tables3').addClass('serviceBtnSelected');
            $('#addNCon_tables2').removeClass('serviceBtnSelected'); $('#addNCon_tables2').addClass('serviceBtn');
            $('#addNCon_tables1').removeClass('serviceBtnSelected'); $('#addNCon_tables1').addClass('serviceBtn');
        }
        $('#addNCon_tablesPerMonth').val(perMon);
        $('#addNCon_tablesProvision').val(provision);
        $('#addNCon_tablesFixedPerMonth').val(fixed);
        $('#addNCon_tablesPercentage').val(percentage);

        updateTotalPays();
    }


    function changeFlyerCost(){
        if(contrVer == 2){
            var newFlyVal = parseFloat($('#addNCon_Einmalige').val()).toFixed(2);
            if(newFlyVal < 0){
                if($('#flyerNewVal_error01').is(":hidden")){$('#flyerNewVal_error01').show(50);}
                $('#flyerCost').val(0);
            }else if($.trim($("#addNCon_Einmalige").val()).length == 0){
                if($('#flyerNewVal_error01').is(":hidden")){$('#flyerNewVal_error01').show(50);}
                $('#flyerCost').val(0);
            }else{
                if($('#flyerNewVal_error01').is(":visible")){$('#flyerNewVal_error01').hide(50);}
                $('#flyerCost').val(newFlyVal);
            }
        }
    }



    function addNewRegAdmins(){

        let userN = $('#newRegAdminsUsername').val();
        let email = $('#newRegAdminsEmail').val();
        let pass = $('#newRegAdminsPassword').val();
       
        $.when(checkEmailUsage(email)).done(function(emailIsInUse){

            if(!$('#newRegAdminsUsername').val() || !$('#newRegAdminsEmail').val() || !$('#newRegAdminsPassword').val()){
                $('#addNCon_error05').show(50).delay(4500).hide(50);
            }else if (email.indexOf('@') == -1 || email.indexOf('.') == -1){
                $('#addNCon_error05').show(50).delay(4500).hide(50);
            }else if(emailIsInUse == 'yes'){
                $('#addNCon_error06').show(50).delay(4500).hide(50);
            }else{
                $('#addANewContractModalAdminsDiv').append('<button style="width: 10%;" class="btn btn-danger mt-1" onclick="removeAdminIns(\''+adminCount+'\')"><i class="fas fa-times"></i></button>');
                $('#addANewContractModalAdminsDiv').append('<p id="adminInc1_'+adminCount+'" class="text-center mt-1 pt-1" style="width:44.5%;"><strong>Administratorin '+adminCount+': </strong></p>');
                $('#addANewContractModalAdminsDiv').append('<p id="adminInc2_'+adminCount+'" class="text-center mt-1 pt-1" style="width:44.5%;"><i class="fas fa-user"></i> <span id="adminInc2_'+adminCount+'Val">'+userN+'</span> </p>');
                $('#addANewContractModalAdminsDiv').append('<p id="adminInc3_'+adminCount+'" class="text-center" style="width:55%;"><i class="fas fa-at"></i> <span id="adminInc3_'+adminCount+'Val">'+email+'</span> </p>');
                $('#addANewContractModalAdminsDiv').append('<p id="adminInc4_'+adminCount+'" class="text-center" style="width:44.5%;"><i class="fas fa-lock"></i> <span id="adminInc4_'+adminCount+'Val">'+pass+'</span> </p>');
                $('#addANewContractModalAdminsDiv').append('<hr style="width:100%; margin-top:-15px;">');
                // UN||email||pass---9---UN||...
                if(!$('#addNCon_newRegAdmins').val()){
                    $('#addNCon_newRegAdmins').val(userN+'||'+email+'||'+pass);
                }else{
                    $('#addNCon_newRegAdmins').val($('#addNCon_newRegAdmins').val()+'--9--'+userN+'||'+email+'||'+pass);
                }
                $('#newRegAdminsUsername').val('');
                $('#newRegAdminsEmail').val('');
                $('#newRegAdminsPassword').val('');
                                    
                adminCount++; 
            }    
        });     
    }

    function checkEmailUsage(theEm){
        return $.ajax({
			url: '{{ route("saContracts.checkEmailUse") }}', method: 'post',
			data: {em: theEm, _token: '{{csrf_token()}}'},
			success: (res) => { return res; },
			error: (error) => { console.log(error); }
		});
    }

    function removeAdminIns(admIncNumber){
        let allOfAdm = $('#adminInc2_'+admIncNumber+'Val').html()+'||'+$('#adminInc3_'+admIncNumber+'Val').html()+'||'+$('#adminInc4_'+admIncNumber+'Val').html();

        let admIncSaved = $('#addNCon_newRegAdmins').val();
        if (admIncSaved.indexOf("--9--") == -1){
            // one instance 
            $('#addNCon_newRegAdmins').val('');
        }else{
            $('#addNCon_newRegAdmins').val('');
            $.each(admIncSaved.split('--9--'), function( index, admInst ) {
                if(admInst != allOfAdm){
                    if(!$('#addNCon_newRegAdmins').val()){
                    $('#addNCon_newRegAdmins').val(admInst);
                    }else{
                        $('#addNCon_newRegAdmins').val($('#addNCon_newRegAdmins').val()+'--9--'+admInst);
                    }
                }
            });
        }
        $('#adminInc1_'+admIncNumber).attr('style','width:44.5%; text-decoration: line-through;');
        $('#adminInc2_'+admIncNumber).attr('style','width:44.5%; text-decoration: line-through;');
        $('#adminInc3_'+admIncNumber).attr('style','width:55%; text-decoration: line-through;');
        $('#adminInc4_'+admIncNumber).attr('style','width:44.5%; text-decoration: line-through;');
    }


  

    function selectTakeawayPay(perMon, provision, fixed, percentage){
        $('#addNCon_Takeaway1').removeClass('serviceBtnSelected'); $('#addNCon_Takeaway1').addClass('serviceBtn');
        $('#addNCon_Takeaway2').removeClass('serviceBtnSelected'); $('#addNCon_Takeaway2').addClass('serviceBtn');
        $('#addNCon_Takeaway3').removeClass('serviceBtnSelected'); $('#addNCon_Takeaway3').addClass('serviceBtn');
        $('#addNCon_Takeaway').removeClass('serviceBtn02Selected'); $('#addNCon_Takeaway').addClass('serviceBtn02');
        if(perMon != 0){
            if($('#addNCon_TakeawayPerMonth').val() == 0){
                // Select
                $('#addNCon_Takeaway').removeClass('serviceBtn02'); $('#addNCon_Takeaway').addClass('serviceBtn02Selected');
                $('#addNCon_Takeaway1').removeClass('serviceBtn'); $('#addNCon_Takeaway1').addClass('serviceBtnSelected');
                $('#addNCon_TakeawayPerMonth').val(perMon);
                $('#addNCon_TakeawayProvision').val(provision);
                $('#addNCon_TakeawayFixedPerMonth').val(fixed);
                $('#addNCon_TakeawayPercentage').val(percentage);
            }else{
                // Deselect
                $('#addNCon_TakeawayPerMonth').val(0);
                $('#addNCon_TakeawayProvision').val(0);
                $('#addNCon_TakeawayFixedPerMonth').val(0);
                $('#addNCon_TakeawayPercentage').val(0);
            }
        }else if(fixed != 0){
            if($('#addNCon_TakeawayFixedPerMonth').val() == 0){
                // Select
                $('#addNCon_Takeaway').removeClass('serviceBtn02'); $('#addNCon_Takeaway').addClass('serviceBtn02Selected');
                $('#addNCon_Takeaway2').removeClass('serviceBtn'); $('#addNCon_Takeaway2').addClass('serviceBtnSelected');
                $('#addNCon_TakeawayPerMonth').val(perMon);
                $('#addNCon_TakeawayProvision').val(provision);
                $('#addNCon_TakeawayFixedPerMonth').val(fixed);
                $('#addNCon_TakeawayPercentage').val(percentage);
            }else{
                // Deselect
                $('#addNCon_TakeawayPerMonth').val(0);
                $('#addNCon_TakeawayProvision').val(0);
                $('#addNCon_TakeawayFixedPerMonth').val(0);
                $('#addNCon_TakeawayPercentage').val(0);
            }
        }else if(percentage != 0){
            if($('#addNCon_TakeawayPercentage').val() == 0){
                // Select
                $('#addNCon_Takeaway').removeClass('serviceBtn02'); $('#addNCon_Takeaway').addClass('serviceBtn02Selected');
                $('#addNCon_Takeaway3').removeClass('serviceBtn'); $('#addNCon_Takeaway3').addClass('serviceBtnSelected');
                $('#addNCon_TakeawayPerMonth').val(perMon);
                $('#addNCon_TakeawayProvision').val(provision);
                $('#addNCon_TakeawayFixedPerMonth').val(fixed);
                $('#addNCon_TakeawayPercentage').val(percentage);
            }else{
                // Deselect
                $('#addNCon_TakeawayPerMonth').val(0);
                $('#addNCon_TakeawayProvision').val(0);
                $('#addNCon_TakeawayFixedPerMonth').val(0);
                $('#addNCon_TakeawayPercentage').val(0);
            }
        }

        updateTotalPays();
    }

    function selectDeliveryPay(perMon, provision, fixed, percentage){
        $('#addNCon_Delivery1').removeClass('serviceBtnSelected'); $('#addNCon_Delivery1').addClass('serviceBtn');
        $('#addNCon_Delivery2').removeClass('serviceBtnSelected'); $('#addNCon_Delivery2').addClass('serviceBtn');
        $('#addNCon_Delivery3').removeClass('serviceBtnSelected'); $('#addNCon_Delivery3').addClass('serviceBtn');
        $('#addNCon_Delivery').removeClass('serviceBtn02Selected'); $('#addNCon_Delivery').addClass('serviceBtn02');
        if(perMon != 0){
            if($('#addNCon_DeliveryPerMonth').val() == 0){
                // Select
                $('#addNCon_Delivery').removeClass('serviceBtn02'); $('#addNCon_Delivery').addClass('serviceBtn02Selected');
                $('#addNCon_Delivery1').removeClass('serviceBtn'); $('#addNCon_Delivery1').addClass('serviceBtnSelected');
                $('#addNCon_DeliveryPerMonth').val(perMon);
                $('#addNCon_DeliveryProvision').val(provision);
                $('#addNCon_DeliveryFixedPerMonth').val(fixed);
                $('#addNCon_DeliveryPercentage').val(percentage);
            }else{
                // Deselect
                $('#addNCon_DeliveryPerMonth').val(0);
                $('#addNCon_DeliveryProvision').val(0);
                $('#addNCon_DeliveryFixedPerMonth').val(0);
                $('#addNCon_DeliveryPercentage').val(0);
            }
        }else if(fixed != 0){
            if($('#addNCon_DeliveryFixedPerMonth').val() == 0){
                // Select
                $('#addNCon_Delivery').removeClass('serviceBtn02'); $('#addNCon_Delivery').addClass('serviceBtn02Selected');
                $('#addNCon_Delivery2').removeClass('serviceBtn'); $('#addNCon_Delivery2').addClass('serviceBtnSelected');
                $('#addNCon_DeliveryPerMonth').val(perMon);
                $('#addNCon_DeliveryProvision').val(provision);
                $('#addNCon_DeliveryFixedPerMonth').val(fixed);
                $('#addNCon_DeliveryPercentage').val(percentage);
            }else{
                // Deselect
                $('#addNCon_DeliveryPerMonth').val(0);
                $('#addNCon_DeliveryProvision').val(0);
                $('#addNCon_DeliveryFixedPerMonth').val(0);
                $('#addNCon_DeliveryPercentage').val(0);
            }
        }else if(percentage != 0){
            if($('#addNCon_DeliveryPercentage').val() == 0){
                // Select
                $('#addNCon_Delivery').removeClass('serviceBtn02'); $('#addNCon_Delivery').addClass('serviceBtn02Selected');
                $('#addNCon_Delivery3').removeClass('serviceBtn'); $('#addNCon_Delivery3').addClass('serviceBtnSelected');
                $('#addNCon_DeliveryPerMonth').val(perMon);
                $('#addNCon_DeliveryProvision').val(provision);
                $('#addNCon_DeliveryFixedPerMonth').val(fixed);
                $('#addNCon_DeliveryPercentage').val(percentage);
            }else{
                // Deselect
                $('#addNCon_DeliveryPerMonth').val(0);
                $('#addNCon_DeliveryProvision').val(0);
                $('#addNCon_DeliveryFixedPerMonth').val(0);
                $('#addNCon_DeliveryPercentage').val(0);
            }
        }
        updateTotalPays();
    }

    function addTischreservierung(perMon){
        if($('#addNCon_TischreservierungPerMonth').val() == 0){
            // select
            $('#addNCon_Tischreservierung').removeClass('serviceBtn'); $('#addNCon_Tischreservierung').addClass('serviceBtnSelected');
            $('#addNCon_TischreservierungPerMonth').val(perMon);
        }else{
            // deselect
            $('#addNCon_Tischreservierung').removeClass('serviceBtnSelected'); $('#addNCon_Tischreservierung').addClass('serviceBtn');
            $('#addNCon_TischreservierungPerMonth').val(0);
        }
        updateTotalPays();
    }
    function addWarenwirtschaft(perMon){
        if($('#addNCon_WarenwirtschaftPerMonth').val() == 0){
            // select
            $('#addNCon_Warenwirtschaft').removeClass('serviceBtn'); $('#addNCon_Warenwirtschaft').addClass('serviceBtnSelected');
            $('#addNCon_WarenwirtschaftPerMonth').val(perMon);
        }else{
            // deselect
            $('#addNCon_Warenwirtschaft').removeClass('serviceBtnSelected'); $('#addNCon_Warenwirtschaft').addClass('serviceBtn');
            $('#addNCon_WarenwirtschaftPerMonth').val(0);
        }
        updateTotalPays();
    }
    function addPersonalvertretung(perMon){
        if($('#addNCon_PersonalvertretungPerMonth').val() == 0){
            // select
            $('#addNCon_Personalvertretung').removeClass('serviceBtn'); $('#addNCon_Personalvertretung').addClass('serviceBtnSelected');
            $('#addNCon_PersonalvertretungPerMonth').val(perMon);
        }else{
            // deselect
            $('#addNCon_Personalvertretung').removeClass('serviceBtnSelected'); $('#addNCon_Personalvertretung').addClass('serviceBtn');
            $('#addNCon_PersonalvertretungPerMonth').val(0);
        }
        updateTotalPays();
    }




    function selectVertragsaufzeitPay(year, perc){
        $('#addNCon_Vertragsaufzeit1').removeClass('serviceBtnSelected'); $('#addNCon_Vertragsaufzeit1').addClass('serviceBtn');
        $('#addNCon_Vertragsaufzeit2').removeClass('serviceBtnSelected'); $('#addNCon_Vertragsaufzeit2').addClass('serviceBtn');
        $('#addNCon_Vertragsaufzeit3').removeClass('serviceBtnSelected'); $('#addNCon_Vertragsaufzeit3').addClass('serviceBtn');
        $('#addNCon_Vertragsaufzeit5').removeClass('serviceBtnSelected'); $('#addNCon_Vertragsaufzeit5').addClass('serviceBtn');

        $('#addNCon_VertragsaufzeitYear').val(year);
        $('#addNCon_VertragsaufzeitPercentage').val(perc);
        if(year == 1){
            $('#addNCon_Vertragsaufzeit1').removeClass('serviceBtn'); $('#addNCon_Vertragsaufzeit1').addClass('serviceBtnSelected');
        }else if(year == 2){
            $('#addNCon_Vertragsaufzeit2').removeClass('serviceBtn'); $('#addNCon_Vertragsaufzeit2').addClass('serviceBtnSelected');
        }else if(year == 3){
            $('#addNCon_Vertragsaufzeit3').removeClass('serviceBtn'); $('#addNCon_Vertragsaufzeit3').addClass('serviceBtnSelected');
        }else if(year == 5){
            $('#addNCon_Vertragsaufzeit5').removeClass('serviceBtn'); $('#addNCon_Vertragsaufzeit5').addClass('serviceBtnSelected');
        }
        updateTotalPays();
    }








    function updateTotalPays(){
        var PerMonth11 = parseInt($('#addNCon_tablesPerMonth').val());
        var PerMonth12 = parseInt($('#addNCon_tablesFixedPerMonth').val());
        var PerMonth21 = parseInt($('#addNCon_TakeawayPerMonth').val());
        var PerMonth22 = parseInt($('#addNCon_TakeawayFixedPerMonth').val());
        var PerMonth31 = parseInt($('#addNCon_DeliveryPerMonth').val());
        var PerMonth32 = parseInt($('#addNCon_DeliveryFixedPerMonth').val());
        var PerMonth4 = parseInt($('#addNCon_TischreservierungPerMonth').val());
        var PerMonth5 = parseInt($('#addNCon_WarenwirtschaftPerMonth').val());
        var PerMonth6 = parseInt($('#addNCon_PersonalvertretungPerMonth').val());
        var PerMonthALL = parseInt(PerMonth11+PerMonth12+PerMonth21+PerMonth22+PerMonth31+PerMonth32+PerMonth4+PerMonth5+PerMonth6);
        var PerMonthDiscount = parseFloat( parseFloat(parseFloat($('#addNCon_VertragsaufzeitPercentage').val())/parseFloat(100)) * parseFloat(PerMonthALL) );
        var PerMonthTot =  parseFloat(parseFloat(PerMonthALL) - parseFloat(PerMonthDiscount));

        var totMonths = parseInt(parseInt($('#addNCon_VertragsaufzeitYear').val()) * parseInt(12));
        var totPerContract = parseFloat(PerMonthTot * parseFloat(totMonths));

        if($('#addNCon_tablesProvision').val() != 0){
            var tablePercentageTot = parseFloat($('#addNCon_tablesProvision').val());
        }else{
            var tablePercentage = parseFloat($('#addNCon_tablesPercentage').val());
            var tablePercentageToRemove = parseFloat(tablePercentage * parseFloat(parseFloat($('#addNCon_VertragsaufzeitPercentage').val()) /parseFloat(100)));
            var tablePercentageTot = parseFloat(tablePercentage - tablePercentageToRemove);
        }
        
        if($('#addNCon_TakeawayProvision').val() != 0){
            var takeawayPercentageTot = parseFloat($('#addNCon_TakeawayProvision').val());
        }else{
            var takeawayPercentage = parseFloat($('#addNCon_TakeawayPercentage').val());
            var takeawayPercentageToRemove = parseFloat(takeawayPercentage * parseFloat(parseFloat($('#addNCon_VertragsaufzeitPercentage').val()) /parseFloat(100)));
            var takeawayPercentageTot = parseFloat(takeawayPercentage - takeawayPercentageToRemove);
        }

        if($('#addNCon_DeliveryProvision').val() != 0){
            var deliveryPercentageTot = parseFloat($('#addNCon_DeliveryProvision').val());
        }else{
            var deliveryPercentage = parseFloat($('#addNCon_DeliveryPercentage').val());
            var deliveryPercentageToRemove = parseFloat(deliveryPercentage * parseFloat(parseFloat($('#addNCon_VertragsaufzeitPercentage').val()) /parseFloat(100)));
            var deliveryPercentageTot = parseFloat(deliveryPercentage - deliveryPercentageToRemove);
        }

        $('#addNCon_totalPerMonth').val(PerMonthTot.toFixed(2));
        $('#totalPerMonth').val(PerMonthTot.toFixed(2));
        $('#addNCon_total').val(totPerContract.toFixed(2));
        $('#total').val(totPerContract.toFixed(2));
        $('#addNCon_tablesPercentageTOT').val(tablePercentageTot.toFixed(2));
        $('#tablesPercentageTOT').val(tablePercentageTot.toFixed(2));
        $('#addNCon_takeawayPercentageTOT').val(takeawayPercentageTot.toFixed(2));
        $('#takeawayPercentageTOT').val(takeawayPercentageTot.toFixed(2));
        $('#addNCon_DeliveryPercentageTOT').val(deliveryPercentageTot.toFixed(2));
        $('#DeliveryPercentageTOT').val(deliveryPercentageTot.toFixed(2));
    }


    function sendTheContractEmail(conID){
        $('#sendTheContractEmailBtn'+conID).html('<i style="color:rgb(39,190,175);" class="far fa-paper-plane"></i>');
        $('#sendTheContractEmailBtn'+conID).attr('disabled',true);
        $.ajax({
			url: '{{ route("saContracts.sendConToEmail") }}',
			method: 'post',
			data: {id: conID, _token: '{{csrf_token()}}' },
			success: () => {
                $('#sendTheContractEmailBtn'+conID).html('<i style="color:rgb(39,190,175);" class="fas fa-envelope"></i>');
                $('#sendTheContractEmailBtn'+conID).attr('disabled',false);
			},
			error: (error) => { console.log(error); }
		});
    }

    function sendTheContractEmailTel(conID){
        $('#sendTheContractEmailBtn'+conID).html('<i style="color:rgb(39,190,175);" class="far fa-2x fa-paper-plane"></i>');
        $('#sendTheContractEmailBtn'+conID).attr('disabled',true);
        $.ajax({
			url: '{{ route("saContracts.sendConToEmail") }}',
			method: 'post',
			data: {id: conID, _token: '{{csrf_token()}}' },
			success: () => {
                $('#sendTheContractEmailBtn'+conID).html('<i style="color:rgb(39,190,175);" class="fas fa-2x fa-envelope"></i>');
                $('#sendTheContractEmailBtn'+conID).attr('disabled',false);
			},
			error: (error) => { console.log(error); }
		});
    }




    function acceptAGBetc(){
        if($('#addNCon_acceptAGBetc').is(":checked")){
            $("#submitAddNewContract").prop('disabled', false);
        }else{
            $("#submitAddNewContract").prop('disabled', true);
        }
    }

    function changeContratPaySt(conId, newS){

    }

    var signaturePad = $('#signaturePad').signature({ syncField: '#signature64', syncFormat: 'PNG', });

    var signaturePad2 = $('#signaturePad2').signature({ syncField: '#signature64', syncFormat: 'PNG', });

    var signaturePad3 = $('#signaturePad3').signature({ syncField: '#signature64', syncFormat: 'PNG', });

    var signaturePad4 = $('#signaturePad4').signature({ syncField: '#signature64', syncFormat: 'PNG', });

    var signaturePad5 = $('#signaturePad5').signature({ syncField: '#signature64', syncFormat: 'PNG', });



    $('#clear').click(function(e) {
        e.preventDefault();
        padUsed++;

        // signaturePad.signature('clear');
        $("#signature64").val('');
        $("#signaturePrew").attr('src','');

        $('#signatureMOdalAttempt').html('Versuch '+parseInt(padUsed + parseInt(1))+'/5')
        if(padUsed == 1){
            $('#signaturePad').hide(100);
            $('#signaturePad2').show(100);
        }else if(padUsed == 2){
            $('#signaturePad2').hide(100);
            $('#signaturePad3').show(100);
        }else if(padUsed == 3){
            $('#signaturePad3').hide(100);
            $('#signaturePad4').show(100);
        }else if(padUsed == 4){
            $('#signaturePad4').hide(100);
            $('#signaturePad5').show(100);
        }
    });

    function clickSaveSignature(){
        padUsed++;

        var image = new Image();
        image = document.getElementById("signature64").value;
        document.getElementById('signaturePrew').src = image;

        $('#signatureMOdalAttempt').html('Versuch '+parseInt(padUsed + parseInt(1))+'/5')
        

        closesignatureMOdal();

        if(padUsed == 1){
            $('#signaturePad').hide(100);
            $('#signaturePad2').show(100);
        }else if(padUsed == 2){
            $('#signaturePad2').hide(100);
            $('#signaturePad3').show(100);
        }else if(padUsed == 3){
            $('#signaturePad3').hide(100);
            $('#signaturePad4').show(100);
        }else if(padUsed == 4){
            $('#signaturePad4').hide(100);
            $('#signaturePad5').show(100);
        }
    }

    function closesignatureMOdal(){
        $('#signatureMOdal').modal('hide');
        $('body').addClass('modal-open');
    }

    $('canvas').attr('width','362');
    $('canvas').attr('height','160');



    $('#addNCon_bankNr').keyup(function(e){
        var bnInp = $('#addNCon_bankNr').val();
        var lastChar = bnInp.substr(bnInp.length - 1);
        if(e.keyCode != 46 ){
            if(lastChar >= 0 && lastChar <= 9){
                if(bnInp.length == 2 || bnInp.length == 7 || bnInp.length == 12 || bnInp.length == 17 || bnInp.length == 22){
                    var bnInpNew = $('#addNCon_bankNr').val()+"-";
                    $('#addNCon_bankNr').val(bnInpNew);
                }
                if(bnInp.length == 25){
                    var bnInpFull = bnInp.slice(0,-1);
                    $('#addNCon_bankNr').val(bnInpFull);
                }
            }else{
                // last input is not a number
                var bnInpFull = bnInp.slice(0,-1);
                $('#addNCon_bankNr').val(bnInpFull);
            }
        }
    });




    function submitAddNewContractFun(){
        var theEmail = $('#addNCon_email').val();
        var doubleTableUse = 0;
        var tabNrToRegisterPer = [];
        var tableFormat = $('#addNCon_tables').val();
        var tableFormat2D = tableFormat.split(',');
        $.each(tableFormat2D, function( index, tables ) {
            if (tables.indexOf('-') == -1){
                if(jQuery.inArray(tables, tabNrToRegisterPer) >= 0){
                    //found
                    doubleTableUse = 1;
                    return false;
                }else{
                    tabNrToRegisterPer.push(tables);
                }
            }else if(tables.indexOf('-') >= 0){
                tables2D = tables.split('-');
                if(tables2D[1] > tables2D[0]){
                    for(let i=tables2D[0]; i<=tables2D[1]; i++){
                        if(jQuery.inArray(i, tabNrToRegisterPer) >= 0){
                            //found
                            doubleTableUse = 1;
                            return false;
                        }else{
                            tabNrToRegisterPer.push(i);
                        }
                    }
                }else{
                    doubleTableUse = 1;
                    return false;
                }
            }
        });

        var bankNr = $('#addNCon_bankNr').val();

        $('#submitAddNewContract').prop('disabled', true);

        if(!$('#addNCon_name').val() || !$('#addNCon_lastname').val() || !$('#addNCon_street').val() || !$('#addNCon_plz').val() || !$('#addNCon_ort').val() || !$('#addNCon_company').val() || !$('#addNCon_phoneNr').val() || !$('#addNCon_email').val()) {
            $('#addNCon_error01').show(50).delay(4500).hide(50);
            $('#addANewContractModal').animate({
                scrollTop: $("#addNCon_name").offset().top
            }, 1000);
            $('#submitAddNewContract').prop('disabled', false);
        }else if (theEmail.indexOf('@') == -1 || theEmail.indexOf('.') == -1){
            $('#addNCon_error02').show(50).delay(4500).hide(50);
            $('#addANewContractModal').animate({
                scrollTop: $("#addNCon_name").offset().top
            }, 1000);
            $('#submitAddNewContract').prop('disabled', false);
        }else if($('#signaturePrew').attr('src') == ''){
            $('#addNCon_error04').show(50).delay(4500).hide(50);
            $('#submitAddNewContract').prop('disabled', false);
        }else if(doubleTableUse == 1){
            $('#addNCon_error08').show(50).delay(4500).hide(50);
            $('#addANewContractModal').animate({
                scrollTop: $("#conDivToAddTables").offset().top
            }, 1000);
            $('#submitAddNewContract').prop('disabled', false);
        }else{
            if(bankNr.length != 24){
                $('#addNCon_bankNr').val(0);
            }
            $('#saContractsAddNew').submit();
        }
    }
</script>

