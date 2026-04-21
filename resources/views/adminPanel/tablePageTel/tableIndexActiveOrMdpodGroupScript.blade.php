<script>
    function openGroupProductSelectionPage(prodID,tCode,clPhoneNr, plateId){
        $('#selectToPayProdsInSelectedGroup').modal('toggle');

        $.ajax({
			url: '{{ route("tablePage.tabOrderShowGroupedOrdersByProduct") }}',
			method: 'get',
            dataType: 'json',
			data: {
                productId: prodID,
                tabCode: tCode,
                clientPHN: clPhoneNr,
                plateId: plateId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => { 
                var showNewOrder ='';
                var time2D;
                $.each(respo, function(index, value){
                    const createdAt = value.created_at; 
                    time2D = createdAt.split(' ')[1];
                    time2D = time2D.split(':');

                    let isSelected = false;
                    var allSel = $('#closeOrSelected'+value.tableNr).val();
                    var selBuild = '';
                    if(allSel.includes('||')){
                        $.each(allSel.split('||'), function( index, allSelOne ) {
                            allSelOne2D = allSelOne.split('-8-');
                            if(allSelOne2D[0] == value.id){ 
                                isSelected = true;
                            }
                        });
                    }else if((allSel.includes('-8-'))){
                        allSel2D = allSel.split('-8-');
                        if(allSel2D[0] == value.id){ 
                            isSelected = true;
                        }
                    }

                    // vendosen waiter data !!!!!!!!!!!!!!!!!!!!!!!!!!!
                    
                    showNewOrder =  '<div style="border: 1px solid rgb(72,81,87); border-radius:2px; margin-bottom:4px; width:100%;" id="tabOrderDiv'+value.id+'"';
                    showNewOrder +=  'class="d-flex flex-wrap justify-content-between';

                    if(value.abrufenStat == 1){
                        showNewOrder +=  ' tabOrderDivCalled';
                    }else if(value.status == 1){
                        showNewOrder +=  ' tabOrderDivConfirmed';
                    }

                    if(isSelected){
                        showNewOrder +=  ' tabOrderDivSelected';
                    }
                    showNewOrder +=  '"';
                  
                    if(isSelected){
                        showNewOrder +=  'onclick="closeOrRemove(\''+value.tableNr+'\',\''+value.id+'\',\''+value.OrderSasia+'\',\''+value.OrderSasia+'\')">';
                    }else{
                        showNewOrder +=  'onclick="closeOrSelect(\''+value.tableNr+'\',\''+value.id+'\',\''+value.OrderSasia+'\')">';
                    }

                    showNewOrder +=     '<p class="pl-1" style="width:60%; margin:0; padding-top:2px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height:0.9;">'+
                                            '<strong>'+
                                            '<span id="tabOrderSasiaSpan'+value.id+'">'+value.OrderSasia+'x</span>'+value.OrderEmri+'';
                    if (value.OrderType != 'empty' && value.OrderExtra != 'empty'){
                        const OrderType = value.OrderType;
                        const theTy2D = OrderType.split('||');
                        showNewOrder +=         '<br>'+
                                            '<span style="font-size:0.6rem;">Type: '+theTy2D[0]+' | '+
                                                'Extra:';
                        const OrderExtra = value.OrderExtra;                      
                        const extras = OrderExtra.split('--0--');
                        $.each(extras, function(index, exOne) {
                            if (exOne !== '') {
                                const theEx2D = exOne.split('||');
                                if (index === 0) {
                        showNewOrder +=         theEx2D[0];
                                }else{
                        showNewOrder +=         ', '+theEx2D[0];
                                }
                            }
                        });
                        showNewOrder +=    '</span>';
                    }else if (value.OrderType != 'empty'){
                        const OrderType = value.OrderType;
                        const theTy2D = OrderType.split('||');
                        showNewOrder +=     '<br>'+
                                            '<span style="font-size:0.6rem;">Type: '+theTy2D[0]+'</span>';
                    }else if (value.OrderExtra != 'empty'){
                        showNewOrder +=     '<span style="font-size:0.6rem;">Extra:';
                        const OrderExtra = value.OrderExtra;                      
                        const extras = OrderExtra.split('--0--');
                        $.each(extras, function(index, exOne) {
                            if (exOne !== '') {
                                const theEx2D = exOne.split('||');
                                if (index === 0) {
                        showNewOrder +=         theEx2D[0];
                                }else{
                        showNewOrder +=         ', '+theEx2D[0];
                                }
                            }
                        });
                        showNewOrder +=    '</span>';
                    }
                    showNewOrder +=         '</strong>';
                    if(value.OrderKomenti){
                        showNewOrder +=     '<br>'+
                                            '<span style="font-size:0.6rem;"><strong>Kommentar: </strong><span style="color:red;">'+value.OrderKomenti+'</span></span>';
                    }      
                    showNewOrder +=     '</p>'+
                                        '<p style="width:20%; margin:0; font-size:0.6rem; line-height:1.1; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">'+
                                            '<strong>'+
                                            time2D[0]+':'+time2D[1]+' Uhr <br>'+
                                            '</strong>'+
                                        '</p>'+
                                        '<p style="width:15%; margin:0; text-align:center;"><strong>'+parseFloat(value.OrderQmimi).toFixed(2)+'.-</strong></p>';
                    if(value.OrderSasia == value.OrderSasiaDone){
                    showNewOrder +=     '<div style="width:5% ; background-color:green;">'+
                                        '</div>';
                    }else{
                    showNewOrder +=     '<div style="width:5%; background-color:red;">'+
                                        '</div>';
                    }
                    showNewOrder +=     '</div>';
                    $('#selectToPayProdsInSelectedGroupBtns').append(showNewOrder);
                });
            },
			error: (error) => { console.log(error); }
		});
    }

    function selectToPayProdsInSelectedGroupCancel(){
        $('#selectToPayProdsInSelectedGroup').modal('toggle');
         $('#selectToPayProdsInSelectedGroupBtns').html('');
        $('body').addClass('modal-open');
    }
</script>