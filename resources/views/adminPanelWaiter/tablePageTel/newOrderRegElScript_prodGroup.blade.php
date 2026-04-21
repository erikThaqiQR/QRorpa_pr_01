<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 10.5,
        breakpoints: {
            // when window width is >= 320px
            320: { slidesPerView: 5.5,},
            480: { slidesPerView: 7.5,},
            640: { slidesPerView: 10.5,},
            900: { slidesPerView: 14.5,},
            1200: { slidesPerView: 18.5,}
        }
    });

    function closeNewTabOrderModal(){
        var tNr = $('#newTabOrderModalActiveTableNr').val();
        // $("#tabOrderBody"+tNr).load(location.href+" #tabOrderBody"+tNr+">*","");
        // $("#tabOrderTotPriceDiv"+tNr).load(location.href+" #tabOrderTotPriceDiv"+tNr+">*","");
        $("#extraServicesOnTable"+tNr).load(location.href+" #extraServicesOnTable"+tNr+">*","");

        $('#newTabOrderModal').modal('hide');
        $('#newTabOrderModalTableNrShow').html('---');

        if($('#newTabOrderModalTableHasNewOrder').val() == 1){
            $('body').addClass('modal-open');
            $('#tabOrder'+$('#newTabOrderModalActiveTableNr').val()).modal('show');
            $('#newTabOrderModalTableHasNewOrder').val(0);
        }else{
            $.ajax({
				url: '{{ route("tablePage.tabOrderModalCheckActiveOrToReopen") }}',
				method: 'post',
				data: {
					tableNr: tNr,
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = $.trim(respo);
                    if(respo == 'Yes'){
                        $('body').addClass('modal-open');
                        $('#tabOrder'+tNr).modal('show');
                        $('#newTabOrderModalTableHasNewOrder').val(0);
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
        $('#newTabOrderModalActiveTableNr').val(0);
    }

    function showCatNewTabOrderModal(kId){
        if(openCatTaNewPro != 0){
            $('#taNewProdCat'+openCatTaNewPro+'Prods').attr('style','width:100%; max-height:534px; overflow-y:scroll; display:none;');
            $('#taNewProdCatImg'+openCatTaNewPro).removeClass('newProdCatSelected');
        }
        openCatTaNewPro = kId;
        $('#taNewProdCat'+kId+'Prods').attr('style','width:100%; max-height:534px; overflow-y:scroll; display:flex;');
        $('#taNewProdCatImg'+kId).addClass('newProdCatSelected');
    }



    function addToCartExpress(pId,notiAct){

        $('#addToCartExpressBtn'+pId).html('<i class="fa-solid fa-2x fa-circle-check" style="color: green;"></i>');
        if(notiAct == 1){ 
            if(hasAddSoundSelected){$("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg21Sound->soundTitle}}.{{$usrNotiReg21Sound->soundExt}}" autoplay="true"></audio>'); 
            }else{ $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>'); }
        }
        $.ajax({
            url: '{{ route("admExprs.addToCartExpresWOT") }}',
            method: 'post',
            data: {
                pid: pId,
                tNr: $('#newTabOrderModalActiveTableNr').val(),
                res: '{{Auth::user()->sFor}}',
                phoneN: $('#newOrPhNrSelected'+$("#newTabOrderModalActiveTableNr").val()).val(),
                _token: '{{csrf_token()}}'
            },
            success: (orBuildDt) => {
                // $('#success03gif').show(1).delay(1880).hide(1);
                var tNrWOT = $('#newTabOrderModalActiveTableNr').val();
                hasNewOrders = 1;
               
                $('.AllExtrasToHide').hide();
                $('.AllTypesToHide').hide();
                $('#addToCartExpressBtn'+pId).html('<i class="fas fa-2x fa-cart-plus" style="color: green;"></i>');

                // reload script
                    $('#tableIcon'+tNrWOT).attr('src','storage/gifs/loading2.gif');
                    // tavolinat - ikonat
                    $("#tableIconDiv"+tNrWOT).load(location.href+" #tableIconDiv"+tNrWOT+">*","");
                // -------------------------------------------------------------
                $('#tableNeedsToReset'+tNrWOT).val(0);
                $('#newTabOrderModalTableHasNewOrder').val(1);

                orBuildDt = $.trim(orBuildDt);
                var orDt2D = orBuildDt.split('-||-');
                var newClientDiv = '';

                buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14], orDt2D[15], orDt2D[16], orDt2D[17], orDt2D[18], orDt2D[19]);

            },
            error: (error) => { console.log(error); }
        });
    }





    function closeTypeSelect(pId){
        $('#openTypeSelect'+pId).modal('hide');
        $('body').addClass('modal-open');
    }
    function addToCartExpressWithT(pId,tyId,priceByType,notiAct){
        $('#openTypeSelect'+pId).modal('hide');
        $('body').addClass('modal-open');

        $('#addToCartExpressBtn'+pId).html('<i class="fa-solid fa-2x fa-circle-check" style="color: green;"></i>');
        if(notiAct == 1){ 
            if(hasAddSoundSelected){
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg21Sound->soundTitle}}.{{$usrNotiReg21Sound->soundExt}}" autoplay="true"></audio>'); 
            }else{
                $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>'); 
            }
        }
        $.ajax({
			url: '{{ route("admExprs.addToCartExpresWT") }}',
			method: 'post',
			data: {
				pid: pId,
                typeId: tyId,
                newPrice: priceByType,
				tNr: $('#newTabOrderModalActiveTableNr').val(),
                res: '{{Auth::user()->sFor}}',
                phoneN: $('#newOrPhNrSelected'+$("#newTabOrderModalActiveTableNr").val()).val(),
				_token: '{{csrf_token()}}'
			},
			success: (orBuildDt) => {
                hasNewOrders = 1;
                var tNrWT = $('#newTabOrderModalActiveTableNr').val();
                // $('#success03gif').show(1).delay(1880).hide(1);
                $('.AllExtrasToHide').hide();
                $('.AllTypesToHide').hide();

                $('#addToCartExpressBtn'+pId).html('<i class="fas fa-2x fa-cart-plus" style="color: green;"></i>');

                // reload script
                    $('#tableIcon'+tNrWT).attr('src','storage/gifs/loading2.gif');
                    // tavolinat - ikonat
                    $("#tableIconDiv"+tNrWT).load(location.href+" #tableIconDiv"+tNrWT+">*","");
                // -------------------------------------------------------------
                $('#tableNeedsToReset'+tNrWT).val(0);
                $('#newTabOrderModalTableHasNewOrder').val(1);

                orBuildDt = $.trim(orBuildDt);
                var orDt2D = orBuildDt.split('-||-');

                buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14], orDt2D[15], orDt2D[16], orDt2D[17], orDt2D[18], orDt2D[19]);
			
			},
			error: (error) => { console.log(error); }
		});
    }













    
    function prodNewDtlFetchDsp(prodId){
        $.ajax({
			url: '{{ route("resTablePage.tablePageNewOrDetailedFetch") }}',
		    method: 'post',
			data: {
			    pId: prodId,
			    _token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                respo2D = respo.split('--||--');
                last = "";
                lastV = 0;
            
                $('#ProdNewDtlProdId').val(respo2D[0]);
                $('#sasiaProd').val(1);
                $('#ProdAddLlojet').val("");
                $('#ProdAddExtra').val("");
                $('#plateFor').val(0);
                $('#sendOrderBtn').removeAttr("data-dismiss");
                $('#sasiaPerProd').val(1);

                $('#showOtherTypes').val(0);
                $('#showOtherExtras').val(0);
                
                $('#PNDtlProdName').html(respo2D[1]);
                $('#PNDtlProdDesc').html(respo2D[2]);
                $('#PNDtlCategoryName').html("("+respo2D[3]+")");
                $('#TotPrice').val(respo2D[4]);
                $('#ProdAddQmimiBaze').val(respo2D[4]);
                
                $('#ProdAddEmri').val(respo2D[1]);
                $('#ProdAddQmimi').val(respo2D[4]);
                $('#ProdAddPershk').val(respo2D[2]);
                $('#ProdAddKategoria').val(respo2D[8]);
              
                if(respo2D[5] != 0){
                    $('#setNewPlateBtn'+respo2D[7]).removeClass('btn-outline-dark');
                    $('#setNewPlateBtn'+respo2D[7]).addClass('btn-dark');
                    $('#setNewPlateBtn'+respo2D[7]).attr('onclick','setNewPlate("'+respo2D[7]+'","1")');

                    if(respo2D[6] != 'empty'){
                        $('#ProdNewDtlDefPlate').show(100);
                        $('#ProdNewDtlDefPlateTitle').html(respo2D[6]);
                        $('#plateFor').val(respo2D[7]);
                    }
                }

                // check Types
                if(respo2D[9] != 'empty'){
                    $('#hasTypeThisPro').val(1);
                    $('#ProdNewDtlTypesDiv').html('');
                    $('#ProdNewDtlTypesDiv').append('<p id="typesHeader" class="hover-pointer d-flex justify-content-between color-qrorpa mt-2" style="margin-top:-10px; margin-bottom:-3px; font-size:larger;">'+
                                                        '<span style="width:100%;"><strong>Type</strong></span>'+
                                                    '</p>');
                    var typesShown = 0;
                    var typesP4checked = 0;
                    var theTypeClass = '';
                    $.each(respo2D[9].split('--0--'), function( index, typeOne) {
                        if(typeOne != ''){
                            typesShown++;
                            var typeOne2D = typeOne.split('||');
                            var thisTypeId = 'llojetPE'+typeOne2D[0];
                            if(typesP4checked == 1 ||typesShown >= 4){
                                if(typesP4checked == 0){
                                    typesP4checked = 1;
                                    $('#typesHeader').html('<span style="width:49%;"><strong>Type</strong></span>'+
                                                            '<span style="width:49%" id="showHideOtherTypesBtn" class="btn" onclick="showHideOtherTypes()">'+
                                                                '<i class="fa-regular fa-eye mr-3"></i><strong>Sonstiges</strong>'+
                                                            '</span> ');
                                }
                                theTypeClass = 'restOfTheTypes';
                            }else{
                                theTypeClass = 'firstThreeTypes';
                            }
                            $('#ProdNewDtlTypesDiv').append('<div style="display:flex;" class="color-text justify-content-between mt-2 '+theTypeClass+'">'+
                                                                '<div style="width:25%;" class="text-left">'+
                                                                    '<label class="switch" style="margin:0;">'+
                                                                        '<input style="width:5px;" type="checkbox" class="primary allProTypesMenu allProTypes" id="llojetPE'+typeOne2D[0]+'"'+ 
                                                                        'onchange="addThisTypeToProd(\''+thisTypeId+'\',\''+typeOne2D[1]+'\',\''+typeOne2D[2]+'\',\'False\')">'+
                                                                        '<span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>'+
                                                                    '</label>'+
                                                                '</div>'+
                                                                '<div style="width:75%;" class="d-flex" style="margin-left:-35px;" onclick="addThisTypeToProd(\''+thisTypeId+'\',\''+typeOne2D[1]+'\',\''+typeOne2D[2]+'\',\'True\')">'+
                                                                    '<p style="width:70%; margin:0;" class="text-left"><strong>'+typeOne2D[1]+'</strong></p>'+
                                                                    '<p style="width:30%; margin:0;" class="text-right"> '+parseFloat(parseFloat(typeOne2D[2])*parseFloat(respo2D[4])).toFixed(2)+'<sup>CHF</sup></p>'+       
                                                                '</div>'+
                                                            '</div>');
                            $('.restOfTheTypes').attr('style','display:none;');
                        }

                        // $('#ProdNewDtlTypesDiv').append();
                    });
                }else{
                    $('#hasTypeThisPro').val(0);
                    $('#ProdNewDtlTypesDiv').html('');
                }

                // check Extras
                if(respo2D[10] != 'empty'){
                    $('#ProdNewDtlExtrasDiv').html('');
                    $('#ProdNewDtlExtrasDiv').append('<hr>'+
                                                    '<p id="extrasHeader" class="hover-pointer d-flex color-qrorpa" style="margin-top:-10px; margin-bottom:-3px; font-size:larger;" >'+
                                                        '<span style="width:100%;"><strong>Extras</strong></span>'+
                                                    '</p>');
                    var extrasShown = 0;
                    var extrasP4checked = 0;
                    var theExtraClass = '';
                    $.each(respo2D[10].split('--0--'), function( index, extraOne) {
                        if(extraOne != ''){
                            extrasShown++;
                            var extraOne2D = extraOne.split('||');
                            var thisExtraId = 'extPE'+extraOne2D[0];
                            if(extrasP4checked == 1 ||extrasShown >= 4){
                                if(extrasP4checked == 0){
                                    extrasP4checked = 1;
                                    $('#extrasHeader').html('<span style="width:49%;"><strong>Extra</strong></span>'+
                                                            '<span style="width:49%" id="showHideOtherExtrasBtn" class="btn" onclick="showHideOtherExtras()">'+
                                                                '<i class="fa-regular fa-eye mr-3"></i><strong>Sonstiges</strong>'+
                                                            '</span> ');
                                }
                                theExtraClass = 'restOfTheExtras';
                            }else{
                                theExtraClass = 'firstThreeExtras';
                            }
                            $('#ProdNewDtlExtrasDiv').append('<div style="display:flex;" class="color-text justify-content-between mt-2 '+theExtraClass+'">'+
                                                                '<div style="width:25%;" class="text-left">'+
                                                                    '<label class="switch" style="margin:0;">'+
                                                                        '<input style="width:5px;" type="checkbox" class="primary allProExtrasMenu allProExtras" id="extPE'+extraOne2D[0]+'"'+ 
                                                                        'onchange="addThisExtraToProd(\''+thisExtraId+'\',\''+extraOne2D[1]+'\',\''+extraOne2D[2]+'\',\'False\')">'+
                                                                        '<span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>'+
                                                                    '</label>'+
                                                                '</div>'+
                                                                '<div class="col-9 text-left d-flex" style="margin-left:-35px;" onclick="addThisExtraToProd(\''+thisExtraId+'\',\''+extraOne2D[1]+'\',\''+extraOne2D[2]+'\',\'True\')">'+
                                                                    '<p style="width:70%;" class="text-left"><strong>'+extraOne2D[1]+'</strong></p>'+
                                                                    '<p style="width:30%;" class="text-right"><span class="priceExtras">'+parseFloat(extraOne2D[2]).toFixed(2)+'</span><sup>CHF</sup></p>'+ 
                                                                '</div>'+
                                                            '</div>');
                            $('.restOfTheExtras').attr('style','display:none;');
                        }
                    });
                    
                    
                }else{
                    $('#ProdNewDtlExtrasDiv').html('');
                }

			},
			error: (error) => { console.log(error); }
		});
    }

    function showHideOtherTypes(){
        if($('#showOtherTypes').val() == 0){
            // show other types
            $('.restOfTheTypes').attr('style','display:flex;');
            $('#showOtherTypes').val(1);
            $('#showHideOtherTypesBtn').html('<i class="fa-regular fa-eye-slash mr-3"></i><strong>Sonstiges</strong>');
        }else{
            // hide other types
            $('.restOfTheTypes').attr('style','display:none;');
            $('#showOtherTypes').val(0);
            $('#showHideOtherTypesBtn').html('<i class="fa-regular fa-eye mr-3"></i><strong>Sonstiges</strong>');
        }
    }

    function showHideOtherExtras(){
        if($('#showOtherExtras').val() == 0){
            // show other types
            $('.restOfTheExtras').attr('style','display:flex;');
            $('#showOtherExtras').val(1);
            $('#showHideOtherExtrasBtn').html('<i class="fa-regular fa-eye-slash mr-3"></i><strong>Sonstiges</strong>');
        }else{
            // hide other types
            $('.restOfTheExtras').attr('style','display:none;');
            $('#showOtherExtras').val(0);
            $('#showHideOtherExtrasBtn').html('<i class="fa-regular fa-eye mr-3"></i><strong>Sonstiges</strong>');
        }
    }















    function addThisTypeToProd(theId, name, value, nameClick) {
        var prodQ = parseFloat($('#ProdAddQmimiBaze').val()).toFixed(2);
        var checkBox = document.getElementById(theId);
        var LlojetPro = document.getElementById('ProdAddLlojet');
        var TotPrice = document.getElementById('TotPrice');
        var ExtraCart = document.getElementById('ProdAddExtra');
        var ExtraCartV = document.getElementById('ProdAddExtra').value;
        var QmimiProd = document.getElementById('ProdAddQmimi');

        var QmimiBaze = document.getElementById('ProdAddQmimiBaze');
        var QmimiBazeValue = parseFloat(QmimiBaze.value);
        var type = name + '||' + value;
        // deselect last Type
        if (last != '') { document.getElementById(last).checked = false; }
        // remove changes from last Type
        if (lastV != '') {
            var priceExtras = document.getElementsByClassName("priceExtras");
            for (var i = 0; i < priceExtras.length; i++) {
                var newV = parseFloat(priceExtras.item(i).innerText);
                priceExtras.item(i).innerText = newV.toFixed(2);
            }
            var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
            var newTot = (extPrice / lastV) + parseFloat(prodQ);
            TotPrice.value = newTot.toFixed(2);
            var extrasToSave = TotPrice.value - QmimiBazeValue;
            TotPrice.value = ((QmimiBazeValue / lastV) + extrasToSave).toFixed(2);
            QmimiProd.value = parseFloat(TotPrice.value).toFixed(2);
        }
        // uncheck the type checkbox
        if(nameClick != 'False'){
            if(!$('#'+theId).is(":checked")){ $('#'+theId).prop('checked', true);
            }else{ $('#'+theId).prop('checked', false); }
        }

        if (checkBox.checked == true) {
            LlojetPro.value = type;

            $('#sendOrderBtn').attr("data-dismiss","modal");

            var prices = document.getElementsByClassName("priceExtras");
            for (var i = 0; i < prices.length; i++) {
                var newV = parseFloat(prices.item(i).innerText);
                prices.item(i).innerText = newV.toFixed(2);
                prices.item(i).disabled = true;
            }

            var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
            var newTot = (extPrice * value) + parseFloat(prodQ);
            TotPrice.value = newTot.toFixed(2);
            var stepRe = 1;
            var plusExt = 0;
            var plusExt = parseFloat(0);
            if (ExtraCartV != '') {
                var extras = ExtraCartV.split('--0--');
                for (var i = 0; i < extras.length; i++) {
                    if(extras[i] != ''){
                        var extras2D = extras[i].split('||');
                        var newQ = parseFloat(extras2D[1]).toFixed(2);
                        if (stepRe++ == 1) {
                            ExtraCart.value = extras2D[0] + '||' + newQ;
                        } else {
                            ExtraCart.value = ExtraCart.value + '--0--' + extras2D[0] + '||' + newQ;
                        }
                        plusExt = parseFloat(parseFloat(newQ)+parseFloat(plusExt));
                    }
                }
                QmimiProd.value = document.getElementById('TotPrice').value;
            }
            TotPrice.value = parseFloat((QmimiBazeValue * value) + plusExt).toFixed(2);
            var tot = TotPrice.value;
            QmimiProd.value = parseFloat(tot).toFixed(2);

            last = theId;
            lastV = value;
        }else {
            $('#sendOrderBtn').removeAttr("data-dismiss");
            LlojetPro.value = '';
            lastV = 0;
            last = "";
        }
    }



    function addThisExtraToProd(theId, name, price, nameClick) {
        var qmimiShow = document.getElementById('TotPrice');
        var checkBox = document.getElementById(theId);

        var AddProdQmimi = document.getElementById('ProdAddQmimi');
        var AddProdExtra = document.getElementById('ProdAddExtra');
        var AddProdExtraValue = document.getElementById('ProdAddExtra').value;

        var extras = name + '||' + parseFloat(price).toFixed(2);

        if(nameClick != 'False'){
            if(!$('#'+theId).is(":checked")){ $('#'+theId).prop('checked', true);
            }else{ $('#'+theId).prop('checked', false); }
        }

        if (checkBox.checked == true) {
            var newValue = parseFloat(qmimiShow.value) + parseFloat(price);
            newValue = newValue.toFixed(2);
            qmimiShow.value = newValue;
            AddProdQmimi.value = newValue;
            if (AddProdExtraValue == "") {
                AddProdExtra.value = extras;
            } else {
                AddProdExtra.value = AddProdExtraValue + '--0--' + extras;
            }

        }else {
            var newValue = parseFloat(qmimiShow.value) - parseFloat(price);
            newValue = newValue.toFixed(2);
            qmimiShow.value = newValue;
            AddProdQmimi.value = newValue;

            var DeletedVal = AddProdExtraValue.replace(extras, '');
            AddProdExtra.value = DeletedVal;
        }
    }



























    function prodModalCancelMenu(){
        $('#ProdNewDtl').modal('hide');
        $('#newTabOrderModal').modal('show');
        $('body').addClass('modal-open');
        $('#changePriceInfo').html('< Sie können den Preis ändern');
        $('#minusSasiaPerProd').hide();
        $('#placeholderSasiaPerProd').show();
        $('#komentMenuAjax').val('');

        $('#ProdNewDtlProdId').val(0);
        $('#sasiaProd').val(1);
        $('#sasiaPerProd').val(1);
        $('#PNDtlProdName').html('---');
        $('#PNDtlProdDesc').html('---');
        $('#PNDtlCategoryName').html("(---)");
        $('#TotPrice').val(0.00);
        $('#ProdAddQmimiBaze').val(0.00);
        $('#ProdAddQmimi').val(0.00);

        $('#ProdNewDtlDefPlate').hide(100);
        $('#plateFor').val(0);

        $('.setNewPlateBtnAll').removeClass('btn-dark');
        $('.setNewPlateBtnAll').addClass('btn-outline-dark');
    }

    function setNewPriceProd(newP){
        if(newP != '' && newP != ' '){
            thePr = parseFloat(newP);
            if(thePr > 0){
                if($('#setNewPriceProdError01').is(':visible')){ $('#setNewPriceProdError01').hide(50); }
                $('#changePriceInfo').html('<i class="fas fa-2x fa-undo btn" onclick="returnOriginalPrice()"></i>');
                $('#ProdAddQmimi').val(thePr);
                $('#sendOrderBtn').attr('disabled',false);
            }else{
                if($('#setNewPriceProdError01').is(':hidden')){ $('#setNewPriceProdError01').show(50); }
                $('#sendOrderBtn').attr('disabled',true);
            }
        }else{
            if($('#setNewPriceProdError01').is(':hidden')){ $('#setNewPriceProdError01').show(50); }
            $('#sendOrderBtn').attr('disabled',true);
        }
    }
    function returnOriginalPrice(){
        if(!$('#ProdAddExtra').val() && !$('#ProdAddLlojet').val()){
            $('#ProdAddQmimi').val(parseFloat($('#ProdAddQmimiBaze').val()).toFixed(2));
            $('#TotPrice').val(parseFloat($('#ProdAddQmimiBaze').val()).toFixed(2));
            $('#sendOrderBtn').attr('disabled',false);

            $('#changePriceInfo').html('< Sie können den Preis ändern');
            if($('#setNewPriceProdError01').is(':visible')){ $('#setNewPriceProdError01').hide(50); }
        }else{
            // alert('needs more work...');
            var qBazeReset = parseFloat($('#ProdAddQmimiBaze').val()).toFixed(2);
            var tipiReset = $('#ProdAddLlojet').val();
            var extraReset = $('#ProdAddExtra').val();
            var qNewReset = qBazeReset;
            if(tipiReset != '' && tipiReset != ' '){
                var tipiResetVlera = tipiReset.split('||')[1];
                qNewReset = parseFloat(parseFloat(tipiResetVlera).toFixed(2) * parseFloat(qBazeReset).toFixed(2)).toFixed(2);
            }
            if(extraReset != '' && extraReset != ' '){
                var extraResetArrayOne = extraReset.split('--0--');
                $.each(extraResetArrayOne, function (key, val) {
                    extraResetPrice = val.split('||')[1];
                    qNewReset = parseFloat(parseFloat(qNewReset) + parseFloat(extraResetPrice)).toFixed(2);
                });
            }

            $('#ProdAddQmimi').val(parseFloat(qNewReset).toFixed(2));
            $('#TotPrice').val(parseFloat(qNewReset).toFixed(2));
            $('#sendOrderBtn').attr('disabled',false);

            $('#changePriceInfo').html('< Sie können den Preis ändern');
            if($('#setNewPriceProdError01').is(':visible')){ $('#setNewPriceProdError01').hide(50); }
        }
    }

    function addOneToSasiaPro(){
        var cVal = parseInt($('#sasiaPerProd').val());
        var newVal = cVal + 1;
        $('#sasiaPerProd').val(newVal);
        $('#sasiaProd').val(newVal);
        $('#minusSasiaPerProd').show();
        $('#placeholderSasiaPerProd').hide();
    }
    function removeOneToSasiaPro(){
        var cVal = parseInt($('#sasiaPerProd').val());
        var newVal = cVal - 1;
        $('#sasiaPerProd').val(newVal);
        $('#sasiaProd').val(newVal);
        if(newVal == 1){
            $('#minusSasiaPerProd').hide();
            $('#placeholderSasiaPerProd').show();
        }else{
            $('#minusSasiaPerProd').show();
            $('#placeholderSasiaPerProd').hide();
        }
    }

    function setNewPlate(plId, ind ){
        $('.setNewPlateBtnAll').removeClass('btn-dark');
        $('.setNewPlateBtnAll').addClass('btn-outline-dark');

        $('#setNewPlateBtn'+plId).removeClass('btn-outline-dark');
        $('#setNewPlateBtn'+plId).addClass('btn-dark');
        // if(ind == 1){ $('#plateFor').val(0);
        // }else if(ind == 2){ $('#plateFor').val(plId); }
        $('#plateFor').val(plId);
    }

    function saveNewOrder(notiAct){
        if($('#hasTypeThisPro').val() == 1 && !$('#ProdAddLlojet').val()){
            $('#addTypePlease').show(200).delay(2500).hide(200);
        }else{
            if(notiAct == 1){ 
                if(hasAddSoundSelected){
                    $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg21Sound->soundTitle}}.{{$usrNotiReg21Sound->soundExt}}" autoplay="true"></audio>'); 
                }else{
                    $("#soundsAllNBAP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>'); 
                }
            }
            var tableNr = $('#newTabOrderModalActiveTableNr').val();
            $.ajax({
                url: '{{ route("dash.addNewProductOrPageStore") }}',
                method: 'post',
                data: {
                    prodId: $('#ProdNewDtlProdId').val(),
                    phoneN: $('#newOrPhNrSelected'+tableNr).val(),
                    tableN: tableNr,
                    resN: '{{Auth::user()->sFor}}',
                    name: $('#ProdAddEmri').val(),
                    persh: $('#ProdAddPershk').val(),
                    sasia: $('#sasiaProd').val(),
                    qmimi: $('#ProdAddQmimi').val(),
                    ekstra: $('#ProdAddExtra').val(),
                    types: $('#ProdAddLlojet').val(),
                    komm: $('#komentMenuAjax').val(),
                    plate: $('#plateFor').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (orBuildDt) => {
                    hasNewOrders = 1;
                    var tNrDetailed = $('#newTabOrderModalActiveTableNr').val();

                    $('#ProdNewDtl').modal('hide');
                    // $('#success03gif').show(1).delay(1880).hide(1);

                    $('.AllExtrasToHide').hide();
                    $('.AllTypesToHide').hide();

                    // reload script
                        // tavolinat - ikonat
                        $('#tableIcon'+tNrDetailed).attr('src','storage/gifs/loading2.gif');
                        $("#tableIconDiv"+tNrDetailed).load(location.href+" #tableIconDiv"+tNrDetailed+">*","");

                        $('.setNewPlateBtnAll').removeClass('btn-dark');
                        $('.setNewPlateBtnAll').addClass('btn-outline-dark');
                        $('#plateFor').val(0);
                        $('#komentMenuAjax').val('');
                    // -------------------------------------------------------------
                    $('#tableNeedsToReset'+tNrDetailed).val(0);
                    $('#newTabOrderModalTableHasNewOrder').val(1);

                    orBuildDt = $.trim(orBuildDt);
                    var orDt2D = orBuildDt.split('-||-');
                    
                    buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14], orDt2D[15], orDt2D[16], orDt2D[17], orDt2D[18], orDt2D[19]);
                
                },
                error: (error) => { console.log(error); }
            });
        }
    }






    function buildTheNewProdInOrderTable(tNr, tOrId, tOrStatus, tOrQmimi, tOrCreAt, tOrSasia, tOrEmri, tOrPershkrimi, tOrType, tOrKoment, staffName, plateName, extrasAll, tOrSasiaDone, clPhNr, plateId, abrufenStat, prodId, tabCode){
  
        // console.log('buildTheNewProdInOrderTable function called with: ', {tNr, tOrId, tOrStatus, tOrQmimi, tOrCreAt, tOrSasia, tOrEmri, tOrPershkrimi, tOrType, tOrKoment, staffName, plateName, extrasAll, tOrSasiaDone, clPhNr, plateId, abrufenStat, prodId, tabCode});
        if ($('#tabOrderDivGroup'+prodId+'tnr'+tNr+'plateId'+plateId).length) {
            var oldSasia = parseInt($('.tabOrderSasiaSpan'+prodId+'tnr'+tNr+'plateId'+plateId).text());
            var newSasia = oldSasia + parseInt(tOrSasia);
            $('.tabOrderSasiaSpan'+prodId+'tnr'+tNr+'plateId'+plateId).text(newSasia);

            var oldQmimi = parseFloat($('.tabOrderQmimiSpan'+prodId+'tnr'+tNr+'plateId'+plateId).text());
            var newQmimi = oldQmimi + parseFloat(tOrQmimi);
            $('.tabOrderQmimiSpan'+prodId+'tnr'+tNr+'plateId'+plateId).text(newQmimi.toFixed(2));

        }else if ($('.tabOrderDivGroup'+prodId+'tnr'+tNr+'plateId'+plateId).length){

            var preSasia = parseInt($('.tabOrderSasiaSpan'+prodId+'tnr'+tNr+'plateId'+plateId).text());
            var preQmimi = parseFloat($('.tabOrderQmimiSpan'+prodId+'tnr'+tNr+'plateId'+plateId).text());

            $('.tabOrderDivGroup'+prodId+'tnr'+tNr+'plateId'+plateId).remove();

            var newSasia = preSasia + parseInt(tOrSasia);
            var newQmimi = preQmimi + parseFloat(tOrQmimi);

            var newGroupOrderDiv;

            newGroupOrderDiv =  '<div style="border: 1px solid rgb(72,81,87); border-radius:2px; margin-bottom:2px;" class="d-flex flex-wrap justify-content-between" id="tabOrderDivGroup'+prodId+'tnr'+tNr+'plateId'+plateId+'"'+
                                'onclick="openGroupProductSelectionPage(\''+prodId+'\',\''+tabCode+'\',\''+clPhNr+'\',\''+plateId+'\')">'+
                                    '<p class="pl-1" style="width:80%; margin:0; padding-top:2px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height:0.9; font-weight:bold;">'+
                                        '<span class="tabOrderSasiaSpan'+prodId+'tnr'+tNr+'plateId'+plateId+'">'+newSasia+'x</span> '+tOrEmri+
                                    '</p>'+
                                    '<p style="width:20%; margin:0; text-align:center;"><strong><span class="tabOrderQmimiSpan'+prodId+'tnr'+tNr+'plateId'+plateId+'">'+parseFloat(newQmimi).toFixed(2)+'</span>.-</strong></p>'+
                                '</div>';
            $('#tab'+tNr+'OrClDiv'+clPhNr+'Plate'+plateId+'').append(newGroupOrderDiv);


        }else{

            var newClientDiv = '';
            var newClientPlateDiv = '';
            var tabOrDiv = '';

            if(!$('#tab'+tNr+'OrClDiv'+clPhNr).length){
                // client not active - show it
                newClientDiv =  '<div class="card mb-1 pr-1 pl-1" style="width: 100%;" id="tab'+tNr+'OrClDiv'+clPhNr+'">';
                                if(clPhNr == '0770000000'){
                                    // staff - admin / waiter
                                }else{
                newClientDiv += '<div class="card-header">'+
                                    '<span style="width: 100%;"><strong>+41 *** *'+clPhNr.slice(-6)+'</strong></span>'+
                                '</div>';
                                }
                newClientDiv += '</div>';
                $('#tabOrderBody'+tNr+'ActiveOrders').append(newClientDiv);
            }

            if(!$('#tab'+tNr+'OrClDiv'+clPhNr+'Plate'+plateId).length){
                // plate at client not active - show it

                newClientPlateDiv = '<div class="card mb-1 mt-2 pl-1 pr-1" style="width: 100%; border:1px solid rgb(72,81,87);" id="tab'+tNr+'OrClDiv'+clPhNr+'Plate'+plateId+'">'+
                                        '<div class="card-header" style="width:fit-content; padding:0px 15px 0px 15px; margin:-10px 0px 5px 15px ; background-color:white; border:none; font-size:1.15rem;">'+ 
                                            '<span style="width: 100%;"><strong>'+plateName+'</strong></span>'+
                                        '</div>'+
                                    '</div>';
                $('#tab'+tNr+'OrClDiv'+clPhNr).append(newClientPlateDiv);
            }
            
            tabOrDiv =  '<div style="border: 1px solid rgb(72,81,87); border-radius:2px; margin-bottom:2px;" id="tabOrderDiv'+tOrId+'"';
                        if(abrufenStat == 1){
            tabOrDiv += 'class="d-flex flex-wrap justify-content-between tabOrderDivGroup'+prodId+'tnr'+tNr+'plateId'+plateId+' tabOrderDivCalled"';
                        }else if(tOrStatus == 1){
            tabOrDiv += 'class="d-flex flex-wrap justify-content-between tabOrderDivGroup'+prodId+'tnr'+tNr+'plateId'+plateId+' tabOrderDivConfirmed"';                
                        }else{
            tabOrDiv += 'class="d-flex flex-wrap justify-content-between tabOrderDivGroup'+prodId+'tnr'+tNr+'plateId'+plateId+'"';                 
                        }
            tabOrDiv += 'onclick="closeOrSelect(\''+tNr+'\',\''+tOrId+'\',\''+tOrSasia+'\')">'+
                            '<p class="pl-1" style="width:60%; margin:0; padding-top:2px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height:0.9;">'+
                                '<strong>'+
                                '<span id="tabOrderSasiaSpan'+tOrId+'" class="tabOrderSasiaSpan'+prodId+'tnr'+tNr+'plateId'+plateId+'">'+tOrSasia+'x</span> '+tOrEmri;
                                if (tOrType != 'empty' && extrasAll != 'empty'){
                                    tOrType2D = tOrType.split('||');
            tabOrDiv +=             '<br>'+
                                    '<span style="font-size:0.6rem;">Type: '+tOrType2D[0]+' | '+
                                    'Extra:';
                                    var countExt = 1;
                                    $.each(extrasAll.split('--0--'), function( index, exOne ) {
                                        exOne2D = exOne.split('||');
                                        if(countExt == 1){
            tabOrDiv +=                 exOne2D[0];
                                        }else{
            tabOrDiv +=                 ', '+exOne2D[0];
                                        }
                                        countExt++;
                                    });
            tabOrDiv +=             '</span>';
                                }else if(tOrType != 'empty'){
                                    tOrType2D = tOrType.split('||');
            tabOrDiv +=             '<br>'+
                                    '<span style="font-size:0.6rem;">Type: '+tOrType2D[0];
                                }else if(extrasAll != 'empty'){
            tabOrDiv +=             '<br>'+
                                    '<span style="font-size:0.6rem;">'+
                                    'Extra:';
                                    var countExt = 1;
                                    $.each(extrasAll.split('--0--'), function( index, exOne ) {
                                        exOne2D = exOne.split('||');
                                        if(countExt == 1){
            tabOrDiv +=                 exOne2D[0];
                                        }else{
            tabOrDiv +=                 ', '+exOne2D[0];
                                        }
                                        countExt++;
                                    });
            tabOrDiv +=             '</span>';
                                }
            tabOrDiv +=         '</strong>';
                            if(tOrKoment != 'empty'){
            tabOrDiv +=         '<br>'+
                                '<span style="font-size:0.6rem;"><strong>Kommentar: </strong><span style="color:red;">'+tOrKoment+'</span></span>';                    
                            }
            tabOrDiv +=     '</p>';
                            time2D = tOrCreAt.split(' ');
                            time3D = time2D[1].split(':');
            tabOrDiv +=     '<p style="width:20%; margin:0; font-size:0.6rem; line-height:1.1; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">'+
                                '<strong>'+
                                time3D[0]+':'+time3D[1]+' Uhr <br>'+
                                staffName+
                                '</strong>'+
                            '</p>'+
                            '<p style="width:15%; margin:0; text-align:center;"><strong><span id="tabOrderQmimiSpan'+tOrId+'" class="tabOrderQmimiSpan'+prodId+'tnr'+tNr+'plateId'+plateId+'">'+parseFloat(tOrQmimi).toFixed(2)+'</span>.-</strong></p>';
                            if(parseInt(tOrSasia) == parseInt(tOrSasiaDone)){
            tabOrDiv +=     '<div style="width:5%; background-color:green;"></div>';
                            }else{
            tabOrDiv +=     '<div style="width:5%; background-color:red;"></div>';
                            }
            tabOrDiv += '</div>';

            $('#tab'+tNr+'OrClDiv'+clPhNr+'Plate'+plateId).append(tabOrDiv);
        }
        $("#tabOrderTopBar1"+tNr).load(location.href+" #tabOrderTopBar1"+tNr+">*","");
    }
</script>
