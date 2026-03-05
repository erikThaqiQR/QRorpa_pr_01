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
        $.ajax({
            url: '{{ route("dash.waiterCheckForCheckin") }}',
            method: 'post',
            data: {_token: '{{csrf_token()}}'},
            success: (checkCHINRespo) => {
                if($.trim(checkCHINRespo) == 'CheckInValide'){

                    $('#addToCartExpressBtn'+pId).html('<i class="fa-solid fa-2x fa-circle-check" style="color: green;"></i>');
                    if(notiAct == 1){ 
                        if(hasAddSoundSelected){$("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg21Sound->soundTitle}}.{{$usrNotiReg21Sound->soundExt}}" autoplay="true"></audio>'); 
                        }else{ $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>'); }
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
                            if($('#adminOrdersShowUl'+orDt2D[0]).length){
                                buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14]);
                            }else{
                                $("#tabOrderTotPriceDiv"+orDt2D[0]).load(location.href+" #tabOrderTotPriceDiv"+orDt2D[0]+">*","");
                                var firstOrderOnTable = '<div class="card mb-2" style="width: 100%;">'+
                                                            '<div class="card-header d-flex">'+
                                                                '<span style="width: 50%;"><strong>Administrator</strong></span>'+
                                                                '<button style="width: 50%; margin:0px;" class="btn btn-dark shadow-none" id="confTabOrdersBtn0770000000O'+orDt2D[0]+'O'+orDt2D[12]+'" onclick="confTabOrders(\'0770000000\',\''+orDt2D[0]+'\',\''+orDt2D[12]+'\')">Bestätigen Sie alles für diesen Kunden</button>'+
                                                            '</div>'+
                                                            '<ul class="list-group list-group-flush" id="adminOrdersShowUl'+orDt2D[0]+'">'+
                                                            '</ul>'+
                                                        '</div>';
                                $("#tabOrderBody"+orDt2D[0]+'ActiveOrders').append(firstOrderOnTable);
                                buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14]);
                            }
                        },
                        error: (error) => { console.log(error); }
                    });
                }else{
                    $('#CheckInFirstToAddOrdersPopUp').show(50);
                }
            },
            error: (error) => { console.log(error); }
        });
    }




    function checkInOpenTypeSelect(pId){
        $.ajax({
            url: '{{ route("dash.waiterCheckForCheckin") }}',
            method: 'post',
            data: {_token: '{{csrf_token()}}'},
            success: (checkCHINRespo) => {
                if($.trim(checkCHINRespo) == 'CheckInValide'){
                    $('#openTypeSelect'+pId).modal('show');
                }else{
                    $('#CheckInFirstToAddOrdersPopUp').show(50);
                }
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
                $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg21Sound->soundTitle}}.{{$usrNotiReg21Sound->soundExt}}" autoplay="true"></audio>'); 
            }else{
                $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>'); 
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
                if($('#adminOrdersShowUl'+orDt2D[0]).length){
                    buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14]);
                }else{
                    $("#tabOrderTotPriceDiv"+orDt2D[0]).load(location.href+" #tabOrderTotPriceDiv"+orDt2D[0]+">*","");
                    var firstOrderOnTable = '<div class="card mb-2" style="width: 100%;">'+
                                                '<div class="card-header d-flex">'+
                                                    '<span style="width: 50%;"><strong>Administrator</strong></span>'+
                                                    '<button style="width: 50%; margin:0px;" class="btn btn-dark shadow-none" id="confTabOrdersBtn0770000000O'+orDt2D[0]+'O'+orDt2D[12]+'" onclick="confTabOrders(\'0770000000\',\''+orDt2D[0]+'\',\''+orDt2D[12]+'\')">Bestätigen Sie alles für diesen Kunden</button>'+
                                                '</div>'+
                                                '<ul class="list-group list-group-flush" id="adminOrdersShowUl'+orDt2D[0]+'">'+
                                                '</ul>'+
                                            '</div>';
                    $("#tabOrderBody"+orDt2D[0]+'ActiveOrders').append(firstOrderOnTable);
                    buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14]);
                }
			},
			error: (error) => { console.log(error); }
		});
    }













    
    function prodNewDtlFetchDsp(prodId){
        $.ajax({
            url: '{{ route("dash.waiterCheckForCheckin") }}',
            method: 'post',
            data: {_token: '{{csrf_token()}}'},
            success: (checkCHINRespo) => {
                if($.trim(checkCHINRespo) == 'CheckInValide'){
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
                }else{
                    $('#ProdNewDtl').modal('hide');
                    $('#CheckInFirstToAddOrdersPopUp').show(50);
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
                    $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/{{$usrNotiReg21Sound->soundTitle}}.{{$usrNotiReg21Sound->soundExt}}" autoplay="true"></audio>'); 
                }else{
                    $("#soundsAllNWP").html('<audio id="soundsAllNBAPAudio" src="storage/sound/addOrCloseByStaf.mp3" autoplay="true"></audio>'); 
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
                    if($('#adminOrdersShowUl'+orDt2D[0]).length){
                        buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14]);
                    }else{
                        $("#tabOrderTotPriceDiv"+orDt2D[0]).load(location.href+" #tabOrderTotPriceDiv"+orDt2D[0]+">*","");
                        var firstOrderOnTable = '<div class="card mb-2" style="width: 100%;">'+
                                                    '<div class="card-header d-flex">'+
                                                        '<span style="width: 50%;"><strong>Administrator</strong></span>'+
                                                        '<button style="width: 50%; margin:0px;" class="btn btn-dark shadow-none" id="confTabOrdersBtn0770000000O'+orDt2D[0]+'O'+orDt2D[12]+'" onclick="confTabOrders(\'0770000000\',\''+orDt2D[0]+'\',\''+orDt2D[12]+'\')">Bestätigen Sie alles für diesen Kunden</button>'+
                                                    '</div>'+
                                                    '<ul class="list-group list-group-flush" id="adminOrdersShowUl'+orDt2D[0]+'">'+
                                                    '</ul>'+
                                                '</div>';
                        $("#tabOrderBody"+orDt2D[0]+'ActiveOrders').append(firstOrderOnTable);
                        buildTheNewProdInOrderTable(orDt2D[0], orDt2D[1], orDt2D[2], orDt2D[3], orDt2D[4], orDt2D[5], orDt2D[6], orDt2D[7], orDt2D[8], orDt2D[9], orDt2D[10], orDt2D[11], orDt2D[13], orDt2D[14]);
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    }










    function buildTheNewProdInOrderTable(tNr, tOrId, tOrStatus, tOrQmimi, tOrCreAt, tOrSasia, tOrEmri, tOrPershkrimi, tOrType, tOrKoment, staffName, plateName, extrasAll, tOrSasiaDone){
        tOrQmimi = parseFloat(tOrQmimi).toFixed(2);
        var newTotPrice = parseFloat(parseFloat($('#tabOrderTotPriceDiv'+tNr+'Value').html()) + parseFloat(tOrQmimi));
        $('#tabOrderTotPriceDiv'+tNr+'Value').html(parseFloat(newTotPrice).toFixed(2));

        var newProdRegShow ='<li class="list-group-item" style="padding:2px;" id="tabOrderTabInsLi'+tOrId+'"'+
                            'onclick="checkAdminAlertOrder(\'{{Auth::User()->id}}\', \''+tNr+'\', \''+tOrId+'\')">';
        if(tOrStatus == 0){
            newProdRegShow += '<div class="d-flex flex-wrap justify-content-between p-2 mb-1"'+
                               'style="border:1px solid gray; border-radius:15px; background-color:rgb(196, 245, 243)" id="prodListinOr'+tOrId+'">';
        }else if(tOrStatus == 1){
            newProdRegShow += '<div class="d-flex flex-wrap justify-content-between p-2 mb-1"'+
                                'style="border:1px solid gray; border-radius:15px; background-color:rgb(159, 245, 188)" id="prodListinOr'+tOrId+'">';
        }else if(tOrStatus == 9){
            newProdRegShow += '<div class="d-flex flex-wrap justify-content-between p-2 mb-1"'+
                                'style="border:1px solid gray; border-radius:15px; background-color:rgb(252, 134, 134)" id="prodListinOr'+tOrId+'">';
        }
        var tOrCreTime = tOrCreAt.split(' ')[1];
        newProdRegShow +='<div style="width:15%; font-size:18px;">';

        if(tOrSasia == tOrSasiaDone){
        newProdRegShow +=   '<p style="margin:0px; font-size:1.6rem; color:darkgreen;"><strong>Bereit</strong></p>';
        }else{
        newProdRegShow +=   '<p style="margin:0px; font-size:1.4rem; color:red;"><strong>Nicht bereit</strong></p>'+
                            '<button id="AbrufenProductBtn'+tOrId+'O'+tNr+'" style="width:100%; margin:0px;" class="btn btn-info" onclick="AbrufenProduct(\''+tOrId+'\',\''+tNr+'\')">'+
                                '<strong>Abrufen</strong>'+
                            '</button>';
        }
                            

        newProdRegShow +=   '<p class="pb-1 pt-1">Zeit: <strong>'+tOrCreTime.substring(0,5)+'</strong></p>'+
                            '<p style="margin-top:-15px;">Summe: <strong>'+tOrQmimi+' <sup>CHF</sup></strong> </p>'+
                            '<p style="margin-top:-15px;"><strong>'+staffName+'</strong></p>'+
                        '</div>'+
                        '<div style="width:45%;" class="pl-3">';
        if (plateName != 'none'){
        newProdRegShow +=   '<p style="margin:0px; font-size:1.4rem; color:rgb(72,81,87);"><strong><i class="fa-solid fa-utensils"></i> '+plateName+'</strong></p>';
        }
        newProdRegShow +=   '<h3><strong><ins>'+tOrSasia+'X</ins> '+tOrEmri+'</strong></h3>'+
                            '<p>'+tOrPershkrimi+'</p>';
        if(tOrType != 'empty'){
        newProdRegShow +=   '<p style="font-size:18px; margin-top:-10px;"><strong>Type:</strong> '+tOrType.split('||')[0]+' </p>';
        }                                   
        if(tOrKoment != 'empty'){
        newProdRegShow +=   '<p style="font-size:15px; margin-top:-10px;"><strong>Kommentar:</strong> '+tOrKoment+' </p>';
        }
        newProdRegShow += '</div>'+
                        '<div style="width:25%;" class="d-flex flex-wrap">';
        if(extrasAll != 'empty'){
            $.each(extrasAll.split('--0--'), function( index, extraOne ) {
                extraOne2D = extraOne.split('||');
                if(index == 0){
                    newProdRegShow +=   '<p style="width:70%;">'+extraOne2D[0]+'</p>'+
                                        '<p style="width:30%;">'+extraOne2D[1]+' <sup>CHF</sup></p>';
                }else{
                    newProdRegShow +=   '<p style="width:70%; margin-top:-20px;">'+extraOne2D[0]+'</p>'+
                                        '<p style="width:30%; margin-top:-20px;">'+extraOne2D[1]+' <sup>CHF</sup></p>';
                }
            });
        }

        newProdRegShow +='</div>'+
                        '<div style="width:15%">';
        if(tOrStatus == 0){
        newProdRegShow +=   '<button style="margin: 0px;" class="mt-2 btn btn-block btn-success shadow-none" id="AnnehmenBtn'+tOrId+'" onclick="chStatusTabO(\''+tNr+'\',\''+tOrId+'\',\'0770000000\')">Bestätigen </button>'+
                            '<button style="margin: 0px;" class="mt-2 btn btn-outline-danger btn-block shadow-none" data-toggle="modal" data-target="#sendOrderForDeleteConfModal" onclick="prepDeleteTabOr(\''+tOrId+'\',\''+tNr+'\')"> <i class="fas fa-trash-alt"></i> <strong>Löschen</strong> </button>';
        }else if(tOrStatus == 1){
        newProdRegShow +=   '<button class="mt-2 btn btn-dark btn-block shadow-none" style="margin-top: 10px; margin-right:0px; margin-left:0px;" id="AnnehmenBtn'+tOrId+'" onclick="chStatusTabODeConf(\''+tNr+'\',\''+tOrId+'\',\'0770000000\')"> Unbestätigt </button>'+
                            '<button class="mt-2 btn btn-outline-danger btn-block shadow-none" data-toggle="modal" data-target="#sendOrderForDeleteConfModal" onclick="prepDeleteTabOr(\''+tOrId+'\',\''+tNr+'\')" style="margin-top:10px; margin-right:0px; margin-left:0px;"> <i class="fas fa-trash-alt"></i> <strong>Löschen</strong> </button>';
        }else if(tOrStatus == 9){
        newProdRegShow +=   '<button class="mt-2 btn btn-block" style="margin-top: 10px; margin-right:0px; margin-left:0px;">Bestellung storniert</button>';
        }
        newProdRegShow +=   '<button class="mt-2 btn-outline-dark btn btn-block shadow-none" id="closeOrBtn'+tOrId+'" style="width:100%; margin:0px;" onclick="closeOrSelect(\''+tNr+'\',\''+tOrId+'\',\''+tOrSasia+'\')">Auswählen</button> ';
           
        newProdRegShow +=   '<input type="hidden" id="closeOrMaxSasiaProd'+tOrId+'" value="'+tOrSasia+'">';
        
        newProdRegShow +='</div>'+
                        '</div>'+
                         '</li>';




        $('#adminOrdersShowUl'+tNr).prepend(newProdRegShow);
    }
</script>
