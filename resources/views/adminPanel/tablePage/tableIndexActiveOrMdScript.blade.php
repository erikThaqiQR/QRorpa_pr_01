<script>
    function openNewTabOrderModalFromATOM(tNr){
        $('#newTabOrderModalActiveTableNr').val(tNr);
		$('#newTabOrderModalTableNrShow').html(tNr);
        $('#tabOrder'+tNr).modal('hide');
        $('body').addClass('modal-open');
    }




    function checkAdminAlertOrder(admId, tNr, tabOrId){
        if($('#tableIcon'+tNr).hasClass('table-glow-adminAlert')){
            $.ajax({
				url: '{{ route("admin.removeNewOrderAlertFA") }}',
				method: 'post',
				data: {
					aId: admId,
                    tableNr: tNr,
                    tabOrderId: tabOrId,
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = respo.replace(/\s/g, '');
                    if(respo == 'tableEmpty'){
                        $('#tableIcon'+tNr).removeClass('table-glow-adminAlert');
                    }
					$('#prodListinOr'+tabOrId).removeClass('table-glow-adminAlert');
				},
				error: (error) => { console.log(error); }
			});
        }
    }

    function confTabOrders(ind,tNr,tabC){
        var theIdBtn = 'confTabOrdersBtn'+ind+'O'+tNr+'O'+tabC;

        $('#'+theIdBtn).html('<img src="storage/gifs/loading2.gif" style="height:23px; width:23px;">');
        $('#'+theIdBtn).prop('disabled',true);

        $.ajax({
			url: '{{ route("admConf.confirmAll") }}',
			method: 'post',
			data: {
				indication: ind,
				tableNr: tNr,
				tabCode: tabC,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");
                
                $('#'+theIdBtn).html('<strong>Bestätigen Sie alle Bestellungen</strong>');
                $('#'+theIdBtn).prop('disabled',false);
			},
			error: (error) => { console.log(error); }
		});
    }
    function chStatusTabO(tNr,toId, clPHNr){
        $('#AnnehmenBtn'+toId).html('<img src="storage/gifs/loading2.gif" style="height:23px; width:23px;">');
        $('#AnnehmenBtn'+toId).prop('disabled',true);
        $.ajax({
			url: '{{ route("cart.chStatTabOrder") }}',
			method: 'post',
			data: {
				tableNr: tNr,
                tabOrId:toId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
			    $("#tabOrderTabInsLi"+toId).load(location.href+" #tabOrderTabInsLi"+toId+">*","");
                $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");
			},
			error: (error) => { console.log(error); }
		});
    }
    function chStatusTabODeConf(tNr,toId, clPHNr){
        $('#AnnehmenBtn'+toId).html('<img src="storage/gifs/loading2.gif" style="height:23px; width:23px;">');
        $('#AnnehmenBtn'+toId).prop('disabled',true);
        $.ajax({
			url: '{{ route("cart.chStatTabOrderDeConfirm") }}',
			method: 'post',
			data: {
				tableNr: tNr,
                tabOrId:toId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
			    $("#tabOrderTabInsLi"+toId).load(location.href+" #tabOrderTabInsLi"+toId+">*","");
                $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");
			},
			error: (error) => { console.log(error); }
		});
    }







    function AbrufenProduct(taOrId,tNr){
        $('#AbrufenProductBtn'+taOrId+'O'+tNr).html('<img src="storage/gifs/loading2.gif" style="height:23px; width:23px;">');
        $('#AbrufenProductBtn'+taOrId+'O'+tNr).prop('disabled',true);
        $.ajax({
			url: '{{ route("abrufen.callProd") }}',
			method: 'post',
			data: {
				tabOrderId: taOrId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#tabOrderTabInsLi"+taOrId).load(location.href+" #tabOrderTabInsLi"+taOrId+">*","");
			},
			error: (error) => { console.log(error); }
		});
    }

    function AbrufenByPlate(plId, plTitle, tableNr){
        $('#AbrufenByPlateBtn'+plId+'O'+tableNr).html('<img src="storage/gifs/loading2.gif" style="height:23px; width:23px;">');
        $('#AbrufenByPlateBtn'+plId+'O'+tableNr).prop('disabled',true);
        $.ajax({
			url: '{{ route("abrufen.callByPlate") }}',
			method: 'post',
			data: {
				plaId: plId,
                taNr: tableNr,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#tabOrderBody"+tableNr).load(location.href+" #tabOrderBody"+tableNr+">*","");

                $('#AbrufenByPlateBtn'+plId+'O'+tableNr).html('<strong>'+plTitle+'</strong>');
                $('#AbrufenByPlateBtn'+plId+'O'+tableNr).prop('disabled',false);
			},
			error: (error) => { console.log(error); }
		});
    }










    function closeOrSelect(tNr,tOId,tOSasia){
        if(tOSasia >= 2){
            // Sasia 2+
            $('#selectToPayProds2UpSasi').modal('toggle');

            var i = 1;
            $('#selectToPayProds2UpSasiBtns').html('');
            while (i <= tOSasia) {
                nextBtn = '<button class="btn btn-outline-dark shadow-none mb-1" onclick="selectToPayProds2UpSasi(\''+tNr+'\',\''+tOId+'\',\''+i+'\')" style="width:24%; margin:0 0.5% 0 0.5%;"><strong>'+i+'</strong></button>';
                $('#selectToPayProds2UpSasiBtns').append(nextBtn);
                i++;
            }
        }else{
            // Sasia 1
            if($('#closeOrSelected'+tNr).val() == ''){
                $('#closeOrSelected'+tNr).val(tOId+'-8-'+tOSasia);
                $('#payAllProd'+tNr).hide(1);
                $('#paySelProd'+tNr).show(1);
            }else{
                var allSel = $('#closeOrSelected'+tNr).val();
                $('#closeOrSelected'+tNr).val(allSel+'||'+tOId+'-8-'+tOSasia);
            }
            $('#closeOrBtn'+tOId).attr('class','mt-2 btn-dark btn btn-block shadow-none');   
            $('#closeOrBtn'+tOId).attr('onclick','closeOrRemove(\''+tNr+'\',\''+tOId+'\',\''+tOSasia+'\')');
        }
    }
    function closeOrRemove(tNr,tOId,tOSasia){
        var allSel = $('#closeOrSelected'+tNr).val();
        var selBuild = '';
        if(allSel.includes('||')){
            $.each(allSel.split('||'), function( index, value ) {
                if(value != tOId+'-8-'+tOSasia){ if(selBuild == ''){ selBuild = value; }else{ selBuild += '||'+value;}}
            });
            $('#closeOrSelected'+tNr).val(selBuild);
        }else{
            $('#closeOrSelected'+tNr).val('');
           
            $('#payAllProd'+tNr).show(1);
            $('#paySelProd'+tNr).hide(1);
        }
        $('#closeOrBtn'+tOId).attr('class','mt-2 btn-outline-dark btn btn-block shadow-none');   
        $('#closeOrBtn'+tOId).attr('onclick','closeOrSelect(\''+tNr+'\',\''+tOId+'\',\''+$("#closeOrMaxSasiaProd"+tOId).val()+'\')');
        $('#closeOrBtn'+tOId).html('Auswählen');
    }

    function selectToPayProds2UpSasiCancel(){
        $('#selectToPayProds2UpSasi').modal('toggle');
        $('body').addClass('modal-open');
    }

    function selectToPayProds2UpSasi(tNr,tOId,tOSasia){
        $('#selectToPayProds2UpSasi').modal('toggle');
        $('body').addClass('modal-open');

        if($('#closeOrSelected'+tNr).val() == ''){
            $('#closeOrSelected'+tNr).val(tOId+'-8-'+tOSasia);
            $('#payAllProd'+tNr).hide(1);
            $('#paySelProd'+tNr).show(1);
        }else{
            var allSel = $('#closeOrSelected'+tNr).val();
            $('#closeOrSelected'+tNr).val(allSel+'||'+tOId+'-8-'+tOSasia);
        }
        $('#closeOrBtn'+tOId).attr('class','mt-2 btn-dark btn btn-block shadow-none');   
        $('#closeOrBtn'+tOId).attr('onclick','closeOrRemove(\''+tNr+'\',\''+tOId+'\',\''+tOSasia+'\')');
        $('#closeOrBtn'+tOId).html('Auswählen '+tOSasia+'X');
    }










    function prepTableChangeModal(tableId,tableNr,tableTab){
        $('#adminChangeTableModalTableFromId').val(tableNr);
        $('#adminChangeTableModalTableNrShow').html(tableNr);
        $('#adminChangeTableModalTableSelectedTabOrders').val($('#closeOrSelected'+tableNr).val());

        $.ajax({
			url: '{{ route("resTablePage.tablePageTableChangeFetchClients") }}',
			method: 'post',
			data: {
				tTab: tableTab,
				tId: tableId,
				_token: '{{csrf_token()}}'
			},
			success: (response) => {
                response = $.trim(response);

                if(response == ''){
                    $('#adminChangeTableModalInputs').html(' <input type="hidden" id="adminChangeTableModalNumberSelected" value="0">');

                    $('#adminChangeTableModalBody').html('<div class="alert alert-danger text-center p-3 ">'+
                                                            '<strong>Es gibt keine aktiven Kunden in dieser Tabelle!</strong>'+
                                                        '</div>');
                }else{
                    $('#adminChangeTableModalInputs').html(' <input type="hidden" id="adminChangeTableModalNumberSelected" value="'+response+'">');

                    $('#adminChangeTableModalBody').html('<div class="d-flex flex-wrap justify-content-start">'+
                                                            '<p class="pl-2" style="width: 100%; color:rgb(39,190,175);">'+
                                                                '<strong><i class="fas mr-2 fa-user-alt"></i>Wählen Sie einen aktiven Client zum Verschieben aus</strong>'+
                                                            '</p>');
                    $.each(response.split('||'), function( index, clPhNr ) {
                        if (clPhNr.indexOf('|') >= 0){ var clPhNr2 = clPhNr.split('|')[1];
                        }else{ var clPhNr2 = clPhNr; }
 
                        if(clPhNr == '0770000000'){
                            $('#adminChangeTableModalBody').append('<button style="width: 32%; margin:5px 0.5% 5px 0.5%;" class="btn btn-dark shadow-none adminChangePhoneNrBtn" id="adminChangePhoneNrBtn'+clPhNr2+'" onclick="selectAdminChPhoneNr(\''+clPhNr+'\')"><strong>Administrator</strong></button>');
                        }else if(clPhNr.indexOf('|') >= 0){
                            $('#adminChangeTableModalBody').append('<button style="width: 32%; margin:5px 0.5% 5px 0.5%;" class="btn btn-dark shadow-none adminChangePhoneNrBtn" id="adminChangePhoneNrBtn'+clPhNr2+'" onclick="selectAdminChPhoneNr(\''+clPhNr+'\')"><strong>Ghost : '+clPhNr2+'</strong></button>');
                        }else{
                            $('#adminChangeTableModalBody').append('<button style="width: 32%; margin:5px 0.5% 5px 0.5%;" class="btn btn-dark shadow-none adminChangePhoneNrBtn" id="adminChangePhoneNrBtn'+clPhNr2+'" onclick="selectAdminChPhoneNr(\''+clPhNr+'\')"><strong>*** *'+ clPhNr.slice(-6)+'</strong></button>');
                        }
                    });

                    $('#adminChangeTableModalBody').append('</div>'+
                                                            '<hr>');

                    $.ajax({
						url: '{{ route("resTablePage.tablePageTableChangeFetchTables") }}',
						method: 'post',
						data: {
							_token: '{{csrf_token()}}'
						},
						success: (allTableDt) => {
                            $('#adminChangeTableModalTablesShow').html('');
                            $.each(allTableDt.split('--||--'), function( index, tblOneDt ) {
                                tblOneDt2D = tblOneDt.split('|||');
                                tblOneDt2D[0] = $.trim(tblOneDt2D[0]);
                                tblOneDt2D[1] = $.trim(tblOneDt2D[1]);
                                tblOneDt2D[2] = $.trim(tblOneDt2D[2]);
                                if(tblOneDt2D[1] == tableNr){
                                    var showTabelForChng =  '<div class="adminChangeTableNotSelect mb-2" id="adminChangeTableDiv'+tblOneDt2D[1]+'" style="width:9.5%; margin-right:0.25%; margin-left:0.25%;" disabled>'+
                                                                '<img style="width:90%; margin:0px 5% 0px 5%;" src="storage/images/tableSt_green.PNG" alt="NotFound">'+
                                                                '<p class="text-center color-qrorpa" style="margin-top:-5px; margin-bottom:-5px; font-size:18px;"><strong>'+tblOneDt2D[1]+'</strong> </p>'+
                                                            '</div>';
                                }else{
                                    if(tblOneDt2D[2] != 0){
                                        var showTabelForChng = '<div class="adminChangeTableNotSelect mb-2 btn" id="adminChangeTableDiv'+tblOneDt2D[1]+'" style="width:9.5%; margin-right:0.25%; margin-left:0.25%; padding:0;"'+
                                                            'onclick="selectAdminChTableNr(\''+tblOneDt2D[1]+'\',\''+tblOneDt2D[0]+'\',\'1\')">'+
                                                                '<img style="width:90%; margin:0px 5% 0px 5%;" src="storage/images/tableSt_yellow.PNG" alt="NotFound">'+
                                                                '<p class="text-center color-qrorpa" style="margin-top:-5px; margin-bottom:-5px; font-size:18px;"><strong>'+tblOneDt2D[1]+'</strong> </p>'+
                                                            '</div>';
                                    }else{
                                        var showTabelForChng = '<div class="adminChangeTableNotSelect mb-2 btn" id="adminChangeTableDiv'+tblOneDt2D[1]+'" style="width:9.5%; margin-right:0.25%; margin-left:0.25%; padding:0;"'+
                                                            'onclick="selectAdminChTableNr(\''+tblOneDt2D[1]+'\',\''+tblOneDt2D[0]+'\',\'0\')">'+
                                                                '<img style="width:90%; margin:0px 5% 0px 5%;" src="storage/images/tableSt_qrorpa.PNG" alt="NotFound">'+
                                                                '<p class="text-center color-qrorpa" style="margin-top:-5px; margin-bottom:-5px; font-size:18px;"><strong>'+tblOneDt2D[1]+'</strong> </p>'+
                                                            '</div>';
                                    }
                                }
                                $('#adminChangeTableModalTablesShow').append(showTabelForChng);
                            });
						},
						error: (error) => { console.log(error); }
					});                                 
                }
            },
			error: (error) => { console.log(error); }
		});
    }

    function resetAdminChangeTableModal(){
        $('#adminChangeTableModal').modal('hide');
        $('body').addClass('modal-open');

        $('#adminChangeTableModalTableFromId').val(0);

        $('#adminChangeTableModalTableNrShow').html('---');
    }

    function selectAdminChTableNr(tNr,tId,isAktive){

        $('#adminChangeTableModalTableSelectedActive').val(isAktive);

        $('.adminChangeTableSelect').addClass('adminChangeTableNotSelect');
        $('.adminChangeTableSelect').removeClass('adminChangeTableSelect');

        $('#adminChangeTableDiv'+tNr).removeClass('adminChangeTableNotSelect');
        $('#adminChangeTableDiv'+tNr).addClass('adminChangeTableSelect');

        $('#adminChangeTableModalTableSelectedId').val(tId);

        if($('#adminChangeTableModalNumberSelected').val() != 0){
            $('#adminChangeTableModalSendBtn').show(200);
        }
    }


    function selectAdminChPhoneNr(phoneNr){
        // $('.adminChangePhoneNrBtn').removeClass('btn-dark');
        // $('.adminChangePhoneNrBtn').addClass('btn-outline-dark');

        // Trajtohen rastet e GHOST CART , nuk pranohet | prandar shfrytzohet vetem kodi si phoneNr
        if(phoneNr.includes('|')){phoneNr2 = phoneNr.split('|')[1];}
        else{phoneNr2 = phoneNr;}

        // Selektohet 
        if($('#adminChangePhoneNrBtn'+phoneNr2).hasClass('btn-outline-dark')){

            $('#adminChangePhoneNrBtn'+phoneNr2).addClass('btn-dark');
            $('#adminChangePhoneNrBtn'+phoneNr2).removeClass('btn-outline-dark');

            if($('#adminChangeTableModalNumberSelected').val() == 0){
                $('#adminChangeTableModalNumberSelected').val(phoneNr);
            }else{
                $('#adminChangeTableModalNumberSelected').val($('#adminChangeTableModalNumberSelected').val()+"||"+phoneNr);
            }
           
            if($('#adminChangeTableModalTableSelectedId').val() != 0){$('#adminChangeTableModalSendBtn').show(1);}
            else{$('#adminChangeTableModalSendBtn').hide(1);}

        // Desektohet
        }else if($('#adminChangePhoneNrBtn'+phoneNr2).hasClass('btn-dark')){

            $('#adminChangePhoneNrBtn'+phoneNr2).addClass('btn-outline-dark');
            $('#adminChangePhoneNrBtn'+phoneNr2).removeClass('btn-dark');

            var prevNrSelected = $('#adminChangeTableModalNumberSelected').val();
            var newNumbers = 'empty';
            if (prevNrSelected.includes("||")){
                var allNumbers = prevNrSelected.split('||');
                $.each( allNumbers, function( index, value ) {
                    if(phoneNr != value){
                        if(newNumbers == 'empty'){ newNumbers = value; }
                        else{ newNumbers += '||'+value; }
                    }
                });
                $('#adminChangeTableModalNumberSelected').val(newNumbers);
            }else{
                $('#adminChangeTableModalNumberSelected').val(0);
            }
            
            if($('#adminChangeTableModalTableSelectedId').val() != 0 && $('#adminChangeTableModalNumberSelected').val() != 0){
                $('#adminChangeTableModalSendBtn').show(1);
            }else{
                $('#adminChangeTableModalSendBtn').hide(1);
            }
        }
    }

    function adminChangeTableModalRegister(res){
        $('#adminChangeTableModalSendBtn').html('<img src="storage/gifs/loading2.gif" style="height:33px; width:auto;" alt="">');
        $('#adminChangeTableModalSendBtn').prop('disabled', true);
        $.ajax({
			url: '{{ route("dash.adminReqClTableChange") }}',
			method: 'post',
			data: {
				tableFromNr: $('#adminChangeTableModalTableFromId').val(),
				tableToId: $('#adminChangeTableModalTableSelectedId').val(),
				tableToActive: $('#adminChangeTableModalTableSelectedActive').val(),
                clPhoneNr: $('#adminChangeTableModalNumberSelected').val(),
                tabOrdersSelected: $('#adminChangeTableModalTableSelectedTabOrders').val(),
                res: res,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = respo.replace(/\s/g, '');
                if(respo != 'reset'){
                    // var res2D = res.split('||');
                    $.ajax({
                        url: '{{ route("dash.adminReqClTableChangeConfirm") }}',
                        method: 'post',
                        data: {
                            tabChngReqId: respo,
                            _token: '{{csrf_token()}}'
                        },
                        success: (respo2) => {location.reload();},
                        error: (error) => {console.log(error);}
                    });
                }else{
                    location.reload();
                }
			},
			error: (error) => { console.log(error); }
		});
    }














    function prepDeleteTabOr(tOrId, tableNr){
        $('#sendOrderForDeleteConfTabOrId').val(tOrId);
        $('#sendOrderForDeleteConfTableNr').val(tableNr);
        $('#sendOrderForDeleteConfTabOrShow').html(tOrId);
        
    }

    function closesendOrderForDeleteConfModal(){
        $('#sendOrderForDeleteConfModal').modal('hide');
        $('body').addClass('modal-open');
    }

    function sendCodeForTabOrderRemove(){
        if(!$('#sendOrderForDeleteConfModalKom').val()){ var commSend = 'empty';
        }else if($('#sendOrderForDeleteConfModalKom').val().length < 5){ var commSend = 'empty';
        }else{ var commSend = $('#sendOrderForDeleteConfModalKom').val(); }
        $.ajax({
			url: '{{ route("cart.chStatTabOrderDelete") }}',
			method: 'post',
			data: {
                id: $('#sendOrderForDeleteConfTabOrId').val(),
                delKom: commSend,
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                var tNr = $('#sendOrderForDeleteConfTableNr').val();

                // reload script
                    // tavolinat - ikonat
                    $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                    $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                    $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                    $("#tabOrderTotPriceDiv"+tNr).load(location.href+" #tabOrderTotPriceDiv"+tNr+">*","");
                // -------------------------------------------------------------

                $('#sendOrderForDeleteConfModal').modal('hide');
                $('body').addClass('modal-open');
			},
			error: (error) => {
				console.log(error);
			}
		});
    }



















    function prepPayAllProds(tNr,resId){
        $('#payAllBtn1').prop('disabled', true);
        $('#payAllBtn2').prop('disabled', true);
        $('#payAllBtn3').prop('disabled', true);
        $('#payAllBtn4').prop('disabled', true);
        $('#tabOrder'+tNr).modal('hide');
        $('body').attr('class','modal-open');
        $('#payAllTableNr').val(tNr);

        $.ajax({
			url: '{{ route("admin.payAllFetchOrders") }}',
            dataType: 'json',
			method: 'post',
			data: {
				tNr: tNr,
				resId: resId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                var totPay = parseFloat(0);
                $.each(respo, function(index, value){
                    $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left">'+value.OrderSasia+'x '+value.OrderEmri+'</p>');
                    $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right">CHF '+parseFloat(value.OrderQmimi).toFixed(2)+'</p>');
                    totPay += parseFloat(value.OrderQmimi);
                });
                var mwst = parseFloat(totPay * 0.074930619);

                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-bottom:8px;" class="text-left"><strong>Total inkl.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-bottom:8px;" class="text-right"><strong>CHF <span id="totAmProds">'+parseFloat(totPay).toFixed(2)+'</span></strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllPhaseOneDiv1TippP11" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>Tipp.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllPhaseOneDiv1TippP12" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="tippAmProds">0.00</span></strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllstaffDiscountShow01" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>Rabatt vom Personal.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllstaffDiscountShow02" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="staffDiscSpan">0.00</span></strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllgiftCardDiscountShow01" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>Rabatt von der Geschenkkarte.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllgiftCardDiscountShow02" style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="giftCardDiscSpan">0.00</span></strong></p>');
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-left"><strong>MwSt.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p style="width: 50%; margin-top:-8px; margin-bottom:8px;" class="text-right"><strong>CHF <span id="tvshAmProds">'+parseFloat(mwst).toFixed(2)+'</span> (<span id="tvshAmProdsPerventage">8.10</span> %)</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllFinalPayShow01" style="width: 50%; margin-top:-8px; margin-bottom:8px; font-size:1.1rem;" class="text-left"><strong>Letzte Bezahlung.</strong></p>');
                $('#payAllPhaseOneDiv1').append('<p id="payAllFinalPayShow02" style="width: 50%; margin-top:-8px; margin-bottom:8px; font-size:1.1rem;" class="text-right"><strong>CHF <span id="finalPaySpan">'+parseFloat(totPay).toFixed(2)+'</span></strong></p>');
                
                $('#payAllBtn1').prop('disabled', false);
                $('#payAllBtn2').prop('disabled', false);
                $('#payAllBtn3').prop('disabled', false);
                $('#payAllBtn4').prop('disabled', false);
            },
			error: (error) => { console.log(error); }
		});
    }





    function ChangeStatusAjaxF(orderStat, orderId, chBy, tNr){
        $('.statusBTNCl'+orderId).prop('disabled', true);
        if($('.countOrdersToConf').length > 0 && $('#tabOrderTotPriceDiv'+tNr+'Value').html() == '0.00'){
            $('#tabOrder'+tNr).modal('hide');
        }
        if(chBy != 999999){ chBy = '{{Auth::user()->id}}'; }
        $.ajax({
			url: '{{ route("order.chStatusAjax") }}',
			method: 'post',
			data: {
				orderStat: orderStat,
				orderId: orderId,
				chBy: chBy,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#tabOrderBody"+tNr+"FinishedOrders").load(location.href+" #tabOrderBody"+tNr+"FinishedOrders>*","");
                
                $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");  

                
			},
			error: (error) => { console.log(error); }
		});
    }
    function showCommOrCancel(orId){
        if($('#cancelCommentDiv'+orId).is(':hidden')){
            $('#cancelCommentDiv'+orId).show(100);
            $('.statusBTNCl'+orId).prop('disabled', true);
        }
    }

    function sendCancelRequest(orderStat, orderId, chBy, tNr){
        if(!$('#cancelCommentInp'+orderId).val()){
            if($('#cancelCommentErr01'+orderId).is(':hidden')){ $('#cancelCommentErr01'+orderId).show(100).delay(3500).hide(100);}
        }else{
            $('.statusBTNCl'+orderId).prop('disabled', true);
            if(chBy != 999999){ chBy = '{{Auth::user()->id}}'; }
            $.ajax({
                url: '{{ route("order.chStatusAjaxCancelOr") }}',
                method: 'post',
                data: {
                    orderStat: orderStat,
                    orderId: orderId,
                    chBy: chBy,
                    theComm : $('#cancelCommentInp'+orderId).val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#tabOrderBody"+tNr+"FinishedOrders").load(location.href+" #tabOrderBody"+tNr+"FinishedOrders>*","");
                
                    $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                    $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");  

                    if($('.countOrdersToConf').length > 0 && $('#tabOrderTotPriceDiv'+tNr+'Value').html() == '0.00'){
                        $('#tabOrder'+tNr).modal('hide');
                    }
                },
                error: (error) => { console.log(error);}
            });
        }
    }













        function printActiveOrdersOnTable(tableNrSend) {

            const address = 'http://192.168.100.198/cgi-bin/epos/service.cgi?devid=local_printer&timeout=60000';

            const epos = new epson.ePOSPrint(address);
            const builder = new epson.ePOSBuilder();

            epos.onreceive = function (res) {
                console.log('Print Result: ' + res.success);
            };

            $.ajax({
                url: '{{ route("print.callDataForPrintReceiptActiveTab") }}',
                method: 'post',
                data: {
                    tableNrSend: tableNrSend,
                    _token: '{{csrf_token()}}'
                },
                success: (printData) => {

                    printData = $.trim(printData);
                    let data = printData.split('---88---');

                    let resName = data[0];
                    let tableNr = data[1];
                    let tableNrShow = (tableNr == 500) ? 'Takeaway' : 'Tisch: ' + tableNr;
                    let timePrint = data[2];
                    let theOrderShowProd = data[3];
                    let totalToPay = parseFloat(data[4]).toFixed(2);
                    let resAdrs = data[5];

                    // ─────────────────────────
                    // HEADER
                    // ─────────────────────────
                    builder.addTextAlign(builder.ALIGN_CENTER);
                    builder.addTextSize(2, 2);
                    builder.addText(resName + '\n');

                    builder.addTextSize(1, 1);
                    builder.addTextStyle(false, false, true);
                    builder.addText('Zwischenrechnung\n');
                    builder.addTextStyle(false, false, false);

                    builder.addText(resAdrs + '\n');
                    builder.addText('--------------------------------\n');

                    // ─────────────────────────
                    // TABLE + TIME (SIDE BY SIDE FEEL)
                    // ─────────────────────────
                    builder.addTextAlign(builder.ALIGN_LEFT);

                    let left = tableNrShow;
                    let right = timePrint;

                    let line =
                        left +
                        ' '.repeat(Math.max(0, 32 - left.length - right.length)) +
                        right;

                    builder.addText(line + '\n');
                    builder.addText('--------------------------------\n');

                    // ─────────────────────────
                    // ITEMS (HTML → CLEAN TEXT)
                    // ─────────────────────────
                    let tempDiv = document.createElement("div");
                    tempDiv.innerHTML = theOrderShowProd;

                    let lines = tempDiv.innerText
                        .replace(/\n\s*\n/g, '\n')
                        .split('\n');

                    lines.forEach(line => {
                        line = line.trim();
                        if (!line) return;

                        // Try structured alignment
                        let parts = line.split(/\s{2,}/);

                        if (parts.length >= 3) {
                            let name = parts[0].substring(0, 18);
                            let qty = parts[1];
                            let price = parts[2];

                            let formatted =
                                name.padEnd(18) +
                                qty.padEnd(6) +
                                price;

                            builder.addText(formatted + '\n');
                        } else {
                            builder.addText(line + '\n');
                        }
                    });

                    builder.addText('--------------------------------\n');

                    // ─────────────────────────
                    // TOTAL (RIGHT LOOK)
                    // ─────────────────────────
                    builder.addTextStyle(false, false, true);

                    let totalLabel = 'Summe:';
                    let totalLine =
                        totalLabel +
                        ' '.repeat(Math.max(0, 32 - totalLabel.length - (totalToPay + ' CHF').length)) +
                        totalToPay + ' CHF';

                    builder.addText(totalLine + '\n');

                    builder.addTextStyle(false, false, false);

                    // ─────────────────────────
                    // FOOTER SPACE
                    // ─────────────────────────
                    builder.addFeedLine(3);
                    builder.addCut(builder.CUT_FEED);

                    // SEND
                    epos.send(builder.toString());
                },
                error: (error) => console.log(error)
            });
        }
    
</script>