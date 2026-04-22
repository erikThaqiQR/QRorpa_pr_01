<script>
    function openNewTabOrderModalFromATOM(tNr){
        if($('#closeOrSelected'+tNr).val() == 0 || $('#closeOrSelected'+tNr).val() == ''){
            // open newTabOrderModal
            $('#newTabOrderModalActiveTableNr').val(tNr);
            $('#newTabOrderModalTableNrShow').html(tNr);
            $('#tabOrder'+tNr).modal('hide');
            $('#newTabOrderModal').modal('show');
            $('body').addClass('modal-open');
        }else{
            // re-register the selected products

            $.ajax({
				url: '{{ route("dash.reRegisterProdsToTab") }}',
				method: 'post',
				data: {
                    tableNr: tNr,
                    tabOrderSelected: $('#closeOrSelected'+tNr).val(),
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = $.trim(respo);
                    respo2D = respo.split('--|||--');
                    $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                    $.each(respo2D, function(index, value) {
                        // Code to be executed for each element
                        orDt2D = value.split('-||-');
                        buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14], orDt2D[15], orDt2D[16], orDt2D[17], orDt2D[18], orDt2D[19]);
                    });
                    $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                    // cancel orders selected -----------------------------------------------------
                    var selectedTOrs = $('#closeOrSelected'+tNr).val();
                    $('#closeOrSelected'+tNr).val('0');
                    $('#payAllProd'+tNr).show(1);
                    $('#paySelProd'+tNr).hide(1);
                    $.each(selectedTOrs.split('||'), function( index, value ) {
                        if(value != ''){
                            var tOId = value.split('-')[0];

                            // (tNr,tOId,tOSasia,tOSasisaTotal)
                            var onClAttr = $('#tabOrderDiv'+tOId).attr('onclick');
                            var tOSasisaTotal = onClAttr.split('\'')[7];
                            $('#tabOrderDiv'+tOId).removeClass('tabOrderDivSelected');
                            $('#tabOrderDiv'+tOId).attr('onclick','closeOrSelect(\''+tNr+'\',\''+tOId+'\',\''+tOSasisaTotal+'\')');
                            $('#tabOrderSasiaSpan'+tOId).html(tOSasisaTotal+'x');

                            $('#deleteAllPrompt'+tNr).attr('style','width:100%; display:none;');
                            $('#deleteSomePrompt'+tNr).attr('style','width:100%; display:none;');
                            
                        }
                    });
                    
                    // ----------------------------------------------------------------------------

				},
				error: (error) => { console.log(error); }
			});
        }
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
                tabOrSelected: $('#closeOrSelected'+tNr).val(),
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");
                
                $('#'+theIdBtn).html('<strong>Bestätigen</strong>');
                $('#'+theIdBtn).prop('disabled',false);

                $('#closeOrSelected'+tNr).val('0');
			},
			error: (error) => { console.log(error); }
		});
    }
    function chStatusTabO(tNr,toId){
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
                nextBtn = '<button class="btn btn-outline-dark shadow-none mb-1" onclick="selectToPayProds2UpSasi(\''+tNr+'\',\''+tOId+'\',\''+i+'\',\''+tOSasia+'\')" style="width:24%; margin:0 0.5% 0 0.5%;"><strong>'+i+'</strong></button>';
                $('#selectToPayProds2UpSasiBtns').append(nextBtn);
                i++;
            }
        }else{
            // Sasia 1
            if($('#closeOrSelected'+tNr).val() == '0'){
                $('#closeOrSelected'+tNr).val(tOId+'-8-'+tOSasia);
                $('#payAllProd'+tNr).hide(1);
                $('#paySelProd'+tNr).show(1);
            }else{
                var allSel = $('#closeOrSelected'+tNr).val();
                $('#closeOrSelected'+tNr).val(allSel+'||'+tOId+'-8-'+tOSasia);
            }
            $('#tabOrderDiv'+tOId).addClass('tabOrderDivSelected');      
            $('#tabOrderDiv'+tOId).attr('onclick','closeOrRemove(\''+tNr+'\',\''+tOId+'\',\''+tOSasia+'\',\''+tOSasia+'\')');
        }

        $('#deleteAllPrompt'+tNr).attr('style','width:100%; display:none;');
        $('#deleteSomePrompt'+tNr).attr('style','width:100%; display:none;');

        updateShownPriceForTabOrders(tNr);
    }

    function closeOrRemove(tNr,tOId,tOSasia,tOSasisaTotal){
        var allSel = $('#closeOrSelected'+tNr).val();
        var selBuild = '';
        if(allSel.includes('||')){
            $.each(allSel.split('||'), function( index, value ) {
                if(value != tOId+'-8-'+tOSasia){ if(selBuild == ''){ selBuild = value; }else{ selBuild += '||'+value;}}
            });
            $('#closeOrSelected'+tNr).val(selBuild);
        }else{
            $('#closeOrSelected'+tNr).val('0');
           
            $('#payAllProd'+tNr).show(1);
            $('#paySelProd'+tNr).hide(1);
        }
        $('#tabOrderDiv'+tOId).removeClass('tabOrderDivSelected');
        $('#tabOrderDiv'+tOId).attr('onclick','closeOrSelect(\''+tNr+'\',\''+tOId+'\',\''+tOSasisaTotal+'\')');
        $('#tabOrderSasiaSpan'+tOId).html(tOSasisaTotal+'x');

        $('#deleteAllPrompt'+tNr).attr('style','width:100%; display:none;');
        $('#deleteSomePrompt'+tNr).attr('style','width:100%; display:none;');
        
        updateShownPriceForTabOrders(tNr);
    }

    function selectToPayProds2UpSasiCancel(){
        $('#selectToPayProds2UpSasi').modal('toggle');
        $('body').addClass('modal-open');
    }

    function selectToPayProds2UpSasi(tNr,tOId,tOSasia,tOSasisaTotal){
        $('#selectToPayProds2UpSasi').modal('toggle');
        $('body').addClass('modal-open');

        if($('#closeOrSelected'+tNr).val() == '0'){
            $('#closeOrSelected'+tNr).val(tOId+'-8-'+tOSasia);
            $('#payAllProd'+tNr).hide(1);
            $('#paySelProd'+tNr).show(1);
        }else{
            var allSel = $('#closeOrSelected'+tNr).val();
            $('#closeOrSelected'+tNr).val(allSel+'||'+tOId+'-8-'+tOSasia);
        }
        $('#tabOrderDiv'+tOId).addClass('tabOrderDivSelected');  
        $('#tabOrderDiv'+tOId).attr('onclick','closeOrRemove(\''+tNr+'\',\''+tOId+'\',\''+tOSasia+'\',\''+tOSasisaTotal+'\')');
        if(parseInt(tOSasia) != parseInt(tOSasisaTotal)){
            $('#tabOrderSasiaSpan'+tOId).html(tOSasia+'/'+tOSasisaTotal+'x');
        }
        updateShownPriceForTabOrders(tNr);
    }


    function updateShownPriceForTabOrders(tNr){
        $.ajax({
			url: '{{ route("tablePage.tabOrderModalCheckTotalPriceShow") }}',
			method: 'post',
			data: {
                tabOrSel: $('#closeOrSelected'+tNr).val(),
                tableNr: tNr,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => { 
                respo = $.trim(respo); 
                $('#tabOrderTotPriceDiv'+tNr+'Value').html(parseFloat(respo).toFixed(2));
            },
			error: (error) => { console.log(error); }
		});
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

                    $('#adminChangeTableModalBody').html('<div style="width:100%;" class="d-flex flex-wrap justify-content-start">'+
                                                            '<p style="width: 100%; font-size:0.6rem; color:rgb(39,190,175);">'+
                                                                '<strong><i class="fas mr-2 fa-user-alt"></i>Wählen Sie einen aktiven Client zum Verschieben aus</strong>'+
                                                            '</p>');
                    $.each(response.split('||'), function( index, clPhNr ) {
                        if (clPhNr.indexOf('|') >= 0){ var clPhNr2 = clPhNr.split('|')[1];
                        }else{ var clPhNr2 = clPhNr; }
 
                        if(clPhNr == '0770000000'){
                            $('#adminChangeTableModalBody').append('<button style="width: 49%; margin:5px 0.5% 5px 0.5%;" class="btn btn-dark shadow-none adminChangePhoneNrBtn" id="adminChangePhoneNrBtn'+clPhNr2+'" onclick="selectAdminChPhoneNr(\''+clPhNr+'\')"><strong>Administrator</strong></button>');
                        }else if(clPhNr.indexOf('|') >= 0){
                            $('#adminChangeTableModalBody').append('<button style="width: 49%; margin:5px 0.5% 5px 0.5%;" class="btn btn-dark shadow-none adminChangePhoneNrBtn" id="adminChangePhoneNrBtn'+clPhNr2+'" onclick="selectAdminChPhoneNr(\''+clPhNr+'\')"><strong>Ghost : '+clPhNr2+'</strong></button>');
                        }else{
                            $('#adminChangeTableModalBody').append('<button style="width: 49%; margin:5px 0.5% 5px 0.5%;" class="btn btn-dark shadow-none adminChangePhoneNrBtn" id="adminChangePhoneNrBtn'+clPhNr2+'" onclick="selectAdminChPhoneNr(\''+clPhNr+'\')"><strong>*** *'+ clPhNr.slice(-6)+'</strong></button>');
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
                                    $('#adminChangeTableModalTablesShow').append('<div class="adminChangeTableNotSelect mb-2" id="adminChangeTableDiv'+tblOneDt2D[1]+'" style="width:13.5%; margin-right:0.39%; margin-left:0.39%;" disabled>'+
                                                                '<img style="width:90%; margin:0px 5% 0px 5%;" src="storage/images/tableSt_green.PNG" alt="NotFound">'+
                                                                '<p class="text-center color-qrorpa" style="margin-top:-5px; margin-bottom:-5px; font-size:18px;"><strong>'+tblOneDt2D[1]+'</strong> </p>'+
                                                            '</div>');
                                }else{
                                    if(tblOneDt2D[2] != 0){
                                        $('#adminChangeTableModalTablesShow').append('<div class="adminChangeTableNotSelect mb-2 btn" id="adminChangeTableDiv'+tblOneDt2D[1]+'" style="width:13.5%; margin-right:0.39%; margin-left:0.39%; padding:0;"'+
                                                            'onclick="selectAdminChTableNr(\''+tblOneDt2D[1]+'\',\''+tblOneDt2D[0]+'\',\'1\')">'+
                                                                '<img style="width:90%; margin:0px 5% 0px 5%;" src="storage/images/tableSt_yellow.PNG" alt="NotFound">'+
                                                                '<p class="text-center color-qrorpa" style="margin-top:-5px; margin-bottom:-5px; font-size:18px;"><strong>'+tblOneDt2D[1]+'</strong> </p>'+
                                                            '</div>');
                                    }else{
                                        $('#adminChangeTableModalTablesShow').append('<div class="adminChangeTableNotSelect mb-2 btn" id="adminChangeTableDiv'+tblOneDt2D[1]+'" style="width:13.5%; margin-right:0.39%; margin-left:0.39%; padding:0;"'+
                                                            'onclick="selectAdminChTableNr(\''+tblOneDt2D[1]+'\',\''+tblOneDt2D[0]+'\',\'0\')">'+
                                                                '<img style="width:90%; margin:0px 5% 0px 5%;" src="storage/images/tableSt_qrorpa.PNG" alt="NotFound">'+
                                                                '<p class="text-center color-qrorpa" style="margin-top:-5px; margin-bottom:-5px; font-size:18px;"><strong>'+tblOneDt2D[1]+'</strong> </p>'+
                                                            '</div>');
                                    }
                                }

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



    function initiateTabOrDelete(tNr){
        var tabOrSel = $('#closeOrSelected'+tNr).val();
        $.ajax({
			url: '{{ route("tablePage.deleteTabOrderCheckForConfirmed") }}',
			method: 'post',
			data: {
				tabOrSel: tabOrSel,
                tableNr: tNr,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                if(tabOrSel == '0'){
                    $('#deleteAllPrompt'+tNr).attr('style','width:100%; display:flex;');
                    if(respo == 'hasSomeConfirmed'){
                        $('#sendOrderForDeleteAllConfModalKomRequired'+tNr).val('hasSomeConfirmed');
                    }
                }else{
                    $('#deleteSomePrompt'+tNr).attr('style','width:100%; display:flex;');
                    if(respo == 'hasSomeConfirmed'){
                        $('#sendOrderForDeleteSomeConfModalKomRequired'+tNr).val('hasSomeConfirmed');
                    }
                }
			},
			error: (error) => { console.log(error); }
		});
					
        
    }

    function cancelTabOrDeleteSome(tNr){
        $('#deleteSomePrompt'+tNr).attr('style','width:100%; display:none;');
        $('#sendOrderForDeleteSomeConfModalKomRequired'+tNr).val('none');
        $('#sendOrderForDeleteSomeConfModalKomRequiredMsg'+tNr).hide(100);
    }
    function confirmTabOrDeleteSome(tNr){
        if(!$('#sendOrderForDeleteSomeConfModalKom'+tNr).val()){ var commSend = 'empty';
        }else if($('#sendOrderForDeleteSomeConfModalKom'+tNr).val().length < 5){ var commSend = 'empty';
        }else{ var commSend = $('#sendOrderForDeleteSomeConfModalKom'+tNr).val(); }
        if($('#sendOrderForDeleteSomeConfModalKomRequired'+tNr).val() == 'hasSomeConfirmed' && commSend == 'empty'){
            $('#sendOrderForDeleteSomeConfModalKomRequiredMsg'+tNr).show(100);
        }else{
            $('#sendOrderForDeleteSomeConfModalKomRequiredMsg'+tNr).hide(100);
            $('#deleteSomePrompt'+tNr).attr('style','width:100%; display:none;');
            $.ajax({
                url: '{{ route("tablePage.deleteSelectedTabOrders") }}',
                method: 'post',
                data: {
                    tabOrSelected: $('#closeOrSelected'+tNr).val(),
                    delKom: commSend,
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    res = $.trim(res);
                    // reload script
                        // tavolinat - ikonat
                        $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                        $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                        $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                        $("#tabOrderTopBar1"+tNr).load(location.href+" #tabOrderTopBar1"+tNr+">*","");
                    // -------------------------------------------------------------
                    if(res == 'true'){
                        $('#tabOrder'+tNr).modal('toggle');
                    }else{
                        $('body').addClass('modal-open');
                    }
                    $('#sendOrderForDeleteSomeConfModalKom'+tNr).val('');
                    $('#closeOrSelected'+tNr).val('0');
                },
                error: (error) => { console.log(error); }
            });
        }
    }


    function cancelTabOrDeleteAll(tNr){
        $('#deleteAllPrompt'+tNr).attr('style','width:100%; display:none;');
        $('#sendOrderForDeleteAllConfModalKomRequired'+tNr).val('none');
        $('#sendOrderForDeleteAllConfModalKomRequiredMsg'+tNr).hide(100);
    }
    function confirmTabOrDeleteAll(tNr){
        if(!$('#sendOrderForDeleteAllConfModalKom'+tNr).val()){ var commSend = 'empty';
        }else if($('#sendOrderForDeleteAllConfModalKom'+tNr).val().length < 5){ var commSend = 'empty';
        }else{ var commSend = $('#sendOrderForDeleteAllConfModalKom'+tNr).val(); }
        if($('#sendOrderForDeleteAllConfModalKomRequired'+tNr).val() == 'hasSomeConfirmed' && commSend == 'empty'){
            $('#sendOrderForDeleteAllConfModalKomRequiredMsg'+tNr).show(100);
        }else{
            $('#sendOrderForDeleteAllConfModalKomRequiredMsg'+tNr).hide(100);
            $('#deleteAllPrompt'+tNr).attr('style','width:100%; display:none;');
            $.ajax({
                url: '{{ route("tablePage.deleteAllTabOrders") }}',
                method: 'post',
                data: {
                    tableNr: tNr,
                    delKom: commSend,
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    res = $.trim(res);
                    // reload script
                        // tavolinat - ikonat
                        $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                        $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                        $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                        $("#tabOrderTopBar1"+tNr).load(location.href+" #tabOrderTopBar1"+tNr+">*","");
                    // -------------------------------------------------------------
                    $('#tabOrder'+tNr).modal('toggle');
                    $('#sendOrderForDeleteAllConfModalKom'+tNr).val('');
                    $('#closeOrSelected'+tNr).val('0');
                },
                error: (error) => { console.log(error); }
            });
        }
    }
















    


    function ChangeStatusAjaxF(orderStat, orderId, chBy, tNr){
        $('.statusBTNCl'+orderId).prop('disabled', true);
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

                if($('.countOrdersToConf').length > 0 && $('#tabOrderTotPriceDiv'+tNr+'Value').html() == '0.00'){
                    $('#tabOrder'+tNr).modal('hide');
                }
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
            if($('.countOrdersToConf').length > 0 && $('#tabOrderTotPriceDiv'+tNr+'Value').html() == '0.00'){
                $('#tabOrder'+tNr).modal('hide');
            }
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

                    
                },
                error: (error) => { console.log(error);}
            });
        }
    }








    function printActiveOrdersOnTable(tableNrSend) {
            const orId = $('#orderQRCodePicDownloadOI').val();
            let resName = '';
            let tableNr = '';
            let tableNrShow = '';
            let timePrint = '';
            let theOrderShowProd = '';
            let resAdrs = '';
         
            $.ajax({
                url: '{{ route("print.callDataForPrintReceiptActiveTab") }}',
                method: 'post',
                data: {
                    tableNrSend: tableNrSend,
                    _token: '{{csrf_token()}}'
                },
                success: (printData) => {
                printData = $.trim(printData);
                printData2D = printData.split('---88---');
                        
                resName = printData2D[0];
                tableNr = printData2D[1];
                if( tableNr == 500){ tableNrShow = 'Takeaway';      
                }else{ tableNrShow = 'Tisch: '+printData2D[1]; }
                timePrint = printData2D[2];
                theOrderShowProd = printData2D[3];
                totalToPay = parseFloat(printData2D[4]).toFixed(2);
                resAdrs = printData2D[5];
                resAdrs = printData2D[5];

                let printWindow = window.open('', '', 'height=500, width=1000');
                
                    printWindow.document.write(`
                        <html>
                        <head>
                            <style>
                                body { font-family: Arial, sans-serif; }
                                h2 { color: #333;}
                            </style>
                        </head>
                        <body>
                            <h2 style="width:100%; text-align:center; margin-bottom:0px; margin-top:0;">`+resName+`<br>Zwischenrechnung</h2>

                            `+resAdrs+`

                            <div style="width:100%; display:flex; flex-wrap: wrap; justify-content: space-between;">
                            <p style="width:40%; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+tableNrShow+`</p>
                            <p style="width:60%; text-align:center; margin-bottom:0px; margin-top:6px; line-height:1.1;">`+timePrint+`</p>
                            </div>

                            <hr style="width:100%; margin:4px 0 4px 0;">

                            `+theOrderShowProd+`

                            <hr style="width:100%; margin:4px 0 4px 0;">

                            <div style="width:100%; display:flex; flex-wrap: wrap; justify-content: space-between;">
                            
                                <p style="width:50%; text-align:left; margin-bottom:0px; margin-top:0; line-height:1;"><strong>Summe: </strong></p>
                                <p style="width:50%; text-align:RIGHT; margin-bottom:0px; margin-top:0; line-height:1;">`+totalToPay+` CHF</p>

                            </div>
                            <p style="color:white; width:100%;">-</p>
                            <p style="color:white; width:100%;">-</p>
                        </body>
                        </html>
                    `);
                
                                printWindow.document.close();
                                printWindow.print();
                },error: (error) => { console.log(error); }
			});
          // printWindow.window.close();
        }



        function initiateTabOrAbrufen(tNr){
            var tabOrSel = $('#closeOrSelected'+tNr).val();
            if(tabOrSel == '0'){
                // abrufen plates or all
                $('#selectPlateForAbrufen').modal('toggle');
                $('#selectPlateForAbrufenBtns').html('');
                $.ajax({
					url: '{{ route("tablePage.plateForAbrufenFetchPlatesForAll") }}',
					method: 'post',
					data: {
						tableNr: tNr,
						_token: '{{csrf_token()}}'
					},
					success: (respo) => {
                        respo = $.trim(respo);
                        var showPlate = '';
						$.each(respo.split('|||'), function( index, plate ) {
                            plate2D = plate.split('-8-');
                            if(parseInt(plate2D[2]) == parseInt(0)){
                                showPlate = '<button style="width:100%; font-size:1.2rem;" class="btn btn-info shadow-none mb-1" disabled><strong>'+plate2D[1]+'</strong></button>';
                            }else{
                                showPlate = '<button style="width:100%; font-size:1.2rem;" class="btn btn-outline-info shadow-none mb-1" onclick="selectPlateForAbrufenAbruenPlateSelect(\''+tNr+'\',\''+plate2D[0]+'\')"><strong>'+plate2D[1]+'</strong></button>';
                            }
                            $('#selectPlateForAbrufenBtns').append(showPlate);
                        });
					},
					error: (error) => { console.log(error); }
				});

            }else{
                // abrufen the selected 

                $.ajax({
					url: '{{ route("tablePage.executeAbrufenOnSelectedTabOr") }}',
					method: 'post',
					data: {
						tableNr: tNr,
                        selectedTOr: tabOrSel,
						_token: '{{csrf_token()}}'
					},
					success: (respo) => {
                        // reload script
                            // tavolinat - ikonat
                            $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                            $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                            $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                            $("#tabOrderTopBar1"+tNr).load(location.href+" #tabOrderTopBar1"+tNr+">*","");

                            $('#closeOrSelected'+tNr).val('0');
                        // -------------------------------------------------------------
                    },
					error: (error) => { console.log(error); }
				});
            }
        }

        function selectPlateForAbrufenCancel(){
            $('#selectPlateForAbrufen').modal('toggle');
            $('#selectPlateForAbrufenBtns').html('');
            $('body').addClass('modal-open');
        }

        function selectPlateForAbrufenAbruenPlateSelect(tNr,plId){
            $('#selectPlateForAbrufen').modal('toggle');
            $('#selectPlateForAbrufenBtns').html('');
            $.ajax({
				url: '{{ route("tablePage.plateForAbrufenExecuteAbrufen") }}',
				method: 'post',
				data: {
					tableNr: tNr,
					plateId: plId,
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    // reload script
                        // tavolinat - ikonat
                        $('#tableIcon'+tNr).attr('src','storage/gifs/loading2.gif');
                        $("#tableIconDiv"+tNr).load(location.href+" #tableIconDiv"+tNr+">*","");

                        $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
                        $("#tabOrderTopBar1"+tNr).load(location.href+" #tabOrderTopBar1"+tNr+">*","");
                    // -------------------------------------------------------------
                    
                    $('body').addClass('modal-open');
                    $('#closeOrSelected'+tNr).val('0');
				},
				error: (error) => { console.log(error); }
			});
        }

    
</script>