<?php
    use App\Produktet;
?>
<script>
    function showProKat(kId) {
        if ($('#state'+kId).val() == 0) {
            $('#prodsKatFoto'+kId).show(50);
            $('#state'+kId).val(1);
        } else {
            $('#prodsKatFoto'+kId).hide(50);
            $('#state'+kId).val(0)
        }
    }

    function showProKatStock(kId) {
        if ($('#stockState'+kId).val() == 0) {
            $('#stockProdsKatFoto'+kId).show(50);
            $('#stockState'+kId).val(1);
        } else {
            $('#stockProdsKatFoto'+kId).hide(50);
            $('#stockState'+kId).val(0)
        }
    }

    function closeCategoryTAndE(kId){
        $('#categoryTAndE'+kId).modal('hide');
        $('body').addClass('modal-open');
    }

    function stockCloseCategoryTAndE(kId){
        $('#stockCategoryTAndE'+kId).modal('hide');
    }

    function addCategoryForStock(katId,katEmri){
        $.ajax({
			url: '{{ route("stock.StockMngRegAllCategory") }}',
			method: 'post',
			data: {
				kId: katId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				// $("#freeProElements").load(location.href+" #freeProElements>*","");
                $("#kategoriaShowAll"+katId).fadeOut(200, function(){ $(this).remove();});

                $('#prodAddScAlert').html('Alle Produkte von  „'+katEmri+'“ sind jetzt im Lager registriert');
                if($('#prodAddScAlert').is(':hidden')){ $('#prodAddScAlert').show(30).delay(4500).hide(30); }

                $("#stockMenuShowAll").load(location.href+" #stockMenuShowAll>*","");
			},
			error: (error) => { console.log(error); }
		});
    }

    function addProductForStock(prodId,katId,prodEmri){
        $.ajax({
			url: '{{ route("stock.StockMngRegProduct") }}',
			method: 'post',
			data: {
				pId: prodId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#catProds"+prodId).fadeOut(200, function(){ $(this).remove();});

                $('#prodAddScAlert').html('Das Produkt „'+prodEmri+'“ wurde erfolgreich im Bestand registriert');
                if($('#prodAddScAlert').is(':hidden')){ $('#prodAddScAlert').show(30).delay(4500).hide(30); }

                $("#stockMenuShowAll").load(location.href+" #stockMenuShowAll>*","");
			},
			error: (error) => { console.log(error); }
		});
    }

    function addExtraForStock(exId,katId,exEmri){
        $.ajax({
			url: '{{ route("stock.StockMngRegExtra") }}',
			method: 'post',
			data: {
				eId: exId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				// $("#freeProElements").load(location.href+" #freeProElements>*","");
                $("#addExtraForCookBtn"+exId).fadeOut(200, function(){ $(this).remove();});

                $('#prodAddScAlert').html('Die zusätzliche Zutat „'+exEmri+'“ wurde erfolgreich im Bestand registriert');
                if($('#prodAddScAlert').is(':hidden')){ $('#prodAddScAlert').show(30).delay(4500).hide(30); }

                $("#stockMenuShowAll").load(location.href+" #stockMenuShowAll>*","");
			},
			error: (error) => { console.log(error); }
		});
    }

    function selectTypeProdStockAdd(prodId, stInsId){
        if($('#selectStockInsToAdd'+prodId).val() == 0){
            $('#selectStockInsToAdd'+prodId).val(stInsId);
        }else{
            $('#selectStockInsToAdd'+prodId).val($('#selectStockInsToAdd'+prodId).val()+'||'+stInsId);
        }

        $('#typeSelBtn'+prodId+'O'+stInsId).removeClass('btn-outline-dark');
        $('#typeSelBtn'+prodId+'O'+stInsId).addClass('btn-dark');

        $('#typeSelBtn'+prodId+'O'+stInsId).attr('onclick','deselectTypeProdStockAdd('+prodId+','+stInsId+')');

    }

    function deselectTypeProdStockAdd(prodId, stInsId){
        var allSIns = $('#selectStockInsToAdd'+prodId).val();
        var allSIns2D = allSIns.split('||');
        var newSelList = 0;
        $.each( allSIns2D, function( key, value ) {
            if(value != stInsId){
                if(newSelList == 0){
                    newSelList = value;
                }else{
                    newSelList = newSelList+'||'+value;
                }
            }
        });
        $('#selectStockInsToAdd'+prodId).val(newSelList);
        $('#typeSelBtn'+prodId+'O'+stInsId).removeClass('btn-dark');
        $('#typeSelBtn'+prodId+'O'+stInsId).addClass('btn-outline-dark');

        $('#typeSelBtn'+prodId+'O'+stInsId).attr('onclick','selectTypeProdStockAdd('+prodId+','+stInsId+')');
    }

    function addToStock(sas, prodId, catId){
        if($('#selectStockInsToAdd'+prodId).val() == 0){
            if($('#typeNotSelected'+prodId).is(':hidden')){ $('#typeNotSelected'+prodId).show(50).delay(3500).hide(50); }
        }else{
            $.ajax({
                url: '{{ route("stock.StockMngAddSasiTo") }}',
                method: 'post',
                data: {
                    sasiaAdd: sas,
                    stockInsIds: $('#selectStockInsToAdd'+prodId).val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    var allSIns = $('#selectStockInsToAdd'+prodId).val();
                    var allSIns2D = allSIns.split('||');
                    var currSasia = 0;
                    $("#stateShowKat"+catId).load(location.href+" #stateShowKat"+catId+">*","");
                    $.each( allSIns2D, function( key, value ) {
                        currSasia = parseInt($('#typeSelBtnSasia'+prodId+'O'+value).html());
                        newSasia = parseInt(parseInt(currSasia)+parseInt(sas));
                        $('#typeSelBtnSasia'+prodId+'O'+value).html(newSasia)
                    });
                    
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    function addToStockByNr(prodId, catId){
        if($('#selectStockInsToAdd'+prodId).val() == 0){
            if($('#typeNotSelected'+prodId).is(':hidden')){ $('#typeNotSelected'+prodId).show(50).delay(3500).hide(50); }
        }else{
            var sasia = $('#addToStockByNrInput'+prodId).val();
            $.ajax({
                url: '{{ route("stock.StockMngAddSasiByNrTo") }}',
                method: 'post',
                data: {
                    sasiaChng: sasia,
                    stockInsIds: $('#selectStockInsToAdd'+prodId).val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    var allSIns = $('#selectStockInsToAdd'+prodId).val();
                    var allSIns2D = allSIns.split('||');
                    var currSasia = 0;
                    $("#stateShowKat"+catId).load(location.href+" #stateShowKat"+catId+">*","");
                    $.each( allSIns2D, function( key, value ) {
                        currSasia = parseInt($('#typeSelBtnSasia'+prodId+'O'+value).html());
                        if(currSasia < (sasia*(-1))){
                            newSasia = parseInt(0);
                        }else{
                            newSasia = parseInt(parseInt(currSasia)+parseInt(sasia));
                        }
                        $('#typeSelBtnSasia'+prodId+'O'+value).html(newSasia)
                    });
                },
                error: (error) => { console.log(error); }
            });
        }
    }



    function showStockPeriodSaveBtn(){
        $('#stockPeriodSaveBtn').show();
    }
    function saveStockPeriodChange(){
        $('#stockPeriodSaveBtn').prop('disabled', true);
        var st1 = parseInt($('#st1').val());
        var st2 = parseInt($('#st2').val());

        if(st1 <= 0){
            if( $('#saveStockPeriodChangeErr01').is(':hidden')){ $('#saveStockPeriodChangeErr01').show(10).delay(4500).hide(10); }
            $('#st1').val("");
            $('#stockPeriodSaveBtn').prop('disabled', false);
        }else if(st2 <= 0 || st2 <= (st1 + 1)){
            if( $('#saveStockPeriodChangeErr02').is(':hidden')){ $('#saveStockPeriodChangeErr02').show(10).delay(4500).hide(10); }
            $('#st2').val("");
            $('#stockPeriodSaveBtn').prop('disabled', false);
        }else{
            $.ajax({
                url: '{{ route("stock.StockMngSavePeriodGrChngs") }}',
                method: 'post',
                data: {
                    firstVal: st1,
                    secondVal: st2,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    var id = 0;
                    $('#stockPeriodSaveBtn').hide();
                    $("#stockPeriodDiv").load(location.href+" #stockPeriodDiv>*","");
                    $('.stateShowKatAll').each(function(key, val) {
                        id = $(this).attr("id");
                        $("#"+id).load(location.href+" #"+id+">*","");
                    });
                    
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    function addToStockByKgLtrPrep(prodId, catId){
        if($('#selectStockInsToAdd'+prodId).val() == 0){
            if($('#typeNotSelected'+prodId).is(':hidden')){ $('#typeNotSelected'+prodId).show(50).delay(3500).hide(50); }
        }else{
            $('#addStokWithKgLtrModal').modal('show');
            $.ajax({
				url: '{{ route("stock.StockMngGetStockInsFromIds") }}',
				method: 'post',
                dataType: 'json',
				data: {
					StInsIds: $('#selectStockInsToAdd'+prodId).val(),
					_token: '{{csrf_token()}}'
				},
				success: (res) => {
                    var showEl = '';
                    var prodName = '';
					$('#addStokWithKgLtrModalBody').append('<h4 class="text-center">'+Object.keys(res).length+' Instanzen</h4>');
                    $.each(res, function(index, stockIns){
                        if(stockIns.KgLtr == 'none'){
                            
                            $.ajax({
                                url: '{{ route("stock.StockMngGetProdNameAndType") }}',
                                method: 'post',
                                data: {
                                    StId: stockIns.id,
                                    _token: '{{csrf_token()}}'
                                },
                                success: (respo) => { 
                                    respo = $.trim(respo);
                                    respo2D = respo.split('||');
                                    showEl =    '<div style="width:100%;" class="d-flex flex-wrap justify-content-between mb-2" id="stoKgLtrDiv'+stockIns.id+'">'+
                                            '<p style="margin:0; width:50%; font-weight:bold;">'+stockIns.sasia+'x - '+respo2D[0]+'</p>'+
                                            '<button class="btn btn-outline-dark shadow-none" style="width:24%;" onclick="slcKgLtrFStoIns(\''+stockIns.id+'\',\'Kg\',\''+respo+'\',\''+stockIns.sasia+'\')">Kg</button>'+
                                            '<button class="btn btn-outline-dark shadow-none" style="width:24%;" onclick="slcKgLtrFStoIns(\''+stockIns.id+'\',\'Ltr\',\''+respo+'\',\''+stockIns.sasia+'\')">Ltr</button>'+
                                            '<input type="hidden" value="none" id="kgltrSelected'+stockIns.id+'">'+
                                            '<input type="hidden" value="'+stockIns.id+'" class="allStoInsToSaveKgLtr">'+
                                        '</div>';
                                    $('#addStokWithKgLtrModalBody').append(showEl);
                                },
                                error: (error) => { console.log(error);}
                            });                           
                        }else{



                            $.ajax({
                                url: '{{ route("stock.StockMngGetProdNameAndType") }}',
                                method: 'post',
                                data: {
                                    StId: stockIns.id,
                                    _token: '{{csrf_token()}}'
                                }, 
                                success: (respo) => { 
                                    respo = $.trim(respo);
                                    respo2D = respo.split('||');
                                    var peshFO = parseFloat(respo2D[1]).toFixed(2);
                                    if(stockIns.KgLtr == 'Kg'){
                                        showEl =    '<div style="width:100%;" class="d-flex flex-wrap justify-content-between mb-2" id="stoKgLtrDiv'+stockIns.id+'">'+
                                                        '<p style="margin:0; width:40%; font-weight:bold;">'+stockIns.sasia+'x - '+respo2D[0]+'</p>'+
                                                        '<div class="input-group" style="width:24%;">'+
                                                            '<input type="number" class="form-control shadow-none" placeholder="Gesamtmenge" id="kgAll'+stockIns.id+'">'+
                                                            '<div class="input-group-append">'+
                                                                '<span class="input-group-text" id="basic-addon2">Kg</span>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '<div class="input-group" style="width:24%;">';
                                        if(peshFO > 0){
                                            showEl +=   '<input type="number" value="'+peshFO+'" class="form-control shadow-none" id="kgmgFoProd'+stockIns.id+'">';

                                        }else{
                                            showEl +=   '<input type="number" class="form-control shadow-none" placeholder="Menge für ein Produkt" id="kgmgFoProd'+stockIns.id+'">';
                                        }
                                        showEl +=           '<div class="input-group-append">'+
                                                                '<span class="input-group-text" id="basic-addon2">mg</span>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '<button class="btn btn-outline-danger shadow-none" style="width:10%;" onclick="removeKgLtrSelection(\''+stockIns.id+'\',\''+stockIns.sasia+'\')"><i style="color:red;" class="fa-solid fa-rectangle-xmark"></i></button>'+
                                                        '<input type="hidden" value="Kg" id="kgltrSelected'+stockIns.id+'">'+
                                                        '<input type="hidden" value="'+stockIns.id+'" class="allStoInsToSaveKgLtr">'+
                                                    '</div>';
                                    }else if (stockIns.KgLtr == 'Ltr'){
                                        showEl =    '<div style="width:100%;" class="d-flex flex-wrap justify-content-between mb-2" id="stoKgLtrDiv'+stockIns.id+'">'+
                                                        '<p style="margin:0; width:40%; font-weight:bold;">'+stockIns.sasia+'x - '+respo2D[0]+'</p>'+
                                                        '<div class="input-group" style="width:24%;">'+
                                                            '<input type="number" class="form-control shadow-none" placeholder="Gesamtmenge" id="ltrAll'+stockIns.id+'">'+
                                                            '<div class="input-group-append">'+
                                                                '<span class="input-group-text" id="basic-addon2">Ltr</span>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '<div class="input-group" style="width:24%;">';
                                        if(peshFO > 0){
                                            showEl +=   '<input type="number" value="'+peshFO+'" class="form-control shadow-none"  id="ltrmlFoProd'+stockIns.id+'">';
                                        }else{
                                            showEl +=   '<input type="number" class="form-control shadow-none" placeholder="Menge für ein Produkt" id="ltrmlFoProd'+stockIns.id+'">';
                                        }                                                        
                                        showEl +=           '<div class="input-group-append">'+
                                                                '<span class="input-group-text" id="basic-addon2">ml</span>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '<button class="btn btn-outline-danger shadow-none" style="width:10%;" onclick="removeKgLtrSelection(\''+stockIns.id+'\',\''+stockIns.sasia+'\')"><i style="color:red;" class="fa-solid fa-rectangle-xmark"></i></button>'+
                                                        '<input type="hidden" value="Ltr" id="kgltrSelected'+stockIns.id+'">'+
                                                        '<input type="hidden" value="'+stockIns.id+'" class="allStoInsToSaveKgLtr">'+
                                                    '</div>';
                                    }
                                    $('#addStokWithKgLtrModalBody').append(showEl);
                                },
                                error: (error) => { console.log(error);}
                            });  
                        }

                        
                    });
				},
				error: (error) => {
					console.log(error);
				}
			});
        }
    }

    function restoreAddStokWithKgLtrModal(){
        $('#addStokWithKgLtrModalBody').html('');
    }

    function slcKgLtrFStoIns(StInId, kgltr, prodName, stockSas){
        $('#stoKgLtrDiv'+StInId).html('');
        var newHtml = '';
        if(kgltr == 'Kg'){
            newHtml = '<p style="margin:0; width:40%; font-weight:bold;">'+stockSas+'x - '+prodName+'</p>'+
                        '<div class="input-group" style="width:24%;">'+
                            '<input type="number" class="form-control shadow-none" placeholder="Gesamtmenge" id="kgAll'+StInId+'">'+
                            '<div class="input-group-append">'+
                                '<span class="input-group-text" id="basic-addon2">Kg</span>'+
                            '</div>'+
                        '</div>'+
                        '<div class="input-group" style="width:24%;">'+
                            '<input type="number" class="form-control shadow-none" placeholder="Menge für ein Produkt" id="kgmgFoProd'+StInId+'">'+
                            '<div class="input-group-append">'+
                                '<span class="input-group-text" id="basic-addon2">mg</span>'+
                            '</div>'+
                        '</div>'+
                        '<button class="btn btn-outline-danger shadow-none" style="width:10%;" onclick="removeKgLtrSelection(\''+StInId+'\',\''+stockSas+'\')"><i style="color:red;" class="fa-solid fa-rectangle-xmark"></i></button>'+
                        '<input type="hidden" value="Kg" id="kgltrSelected'+StInId+'">'+
                        '<input type="hidden" value="'+StInId+'" class="allStoInsToSaveKgLtr">';
            $('#stoKgLtrDiv'+StInId).html(newHtml);

            $.ajax({
				url: '{{ route("stock.StockMngSetKgLtrToStIns") }}',
				method: 'post',
				data: {
					stId: StInId,
                    kgLtr: 'Kg',
					_token: '{{csrf_token()}}'
				},
				success: () => {},
				error: (error) => { console.log(error); }
			});
        }else if(kgltr == 'Ltr'){
            newHtml =   '<p style="margin:0; width:40%; font-weight:bold;">'+stockSas+'x - '+prodName+'</p>'+
                        '<div class="input-group" style="width:24%;">'+
                            '<input type="number" class="form-control shadow-none" placeholder="Gesamtmenge" id="ltrAll'+StInId+'">'+
                            '<div class="input-group-append">'+
                                '<span class="input-group-text" id="basic-addon2">Ltr</span>'+
                            '</div>'+
                        '</div>'+
                        '<div class="input-group" style="width:24%;">'+
                            '<input type="number" class="form-control shadow-none" placeholder="Menge für ein Produkt" id="ltrmlFoProd'+StInId+'">'+
                            '<div class="input-group-append">'+
                                '<span class="input-group-text" id="basic-addon2">ml</span>'+
                            '</div>'+
                        '</div>'+
                        '<button class="btn btn-outline-danger shadow-none" style="width:10%;" onclick="removeKgLtrSelection(\''+StInId+'\',\''+stockSas+'\')"><i style="color:red;" class="fa-solid fa-rectangle-xmark"></i></button>'+
                        '<input type="hidden" value="Ltr" id="kgltrSelected'+StInId+'">'+
                        '<input type="hidden" value="'+StInId+'" class="allStoInsToSaveKgLtr">';
            $('#stoKgLtrDiv'+StInId).html(newHtml);
            $.ajax({
				url: '{{ route("stock.StockMngSetKgLtrToStIns") }}',
				method: 'post',
				data: {
					stId: StInId,
                    kgLtr: 'Ltr',
					_token: '{{csrf_token()}}'
				},
				success: () => {},
				error: (error) => { console.log(error); }
			});
        }
    }

    function removeKgLtrSelection(stoInsId, stockSasi){
        $.ajax({
			url: '{{ route("stock.StockMngRemoveKgLtrToStIns") }}',
			method: 'post',
			data: {
				stId: stoInsId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                respo2D = respo.split('||');
                newHtml =    '<p style="margin:0; width:50%; font-weight:bold;">'+stockSasi+'x - '+respo2D[0]+'</p>'+
                            '<button class="btn btn-outline-dark shadow-none" style="width:24%;" onclick="slcKgLtrFStoIns(\''+stoInsId+'\',\'Kg\',\''+respo+'\',\''+stockSasi+'\')">Kg</button>'+
                            '<button class="btn btn-outline-dark shadow-none" style="width:24%;" onclick="slcKgLtrFStoIns(\''+stoInsId+'\',\'Ltr\',\''+respo+'\',\''+stockSasi+'\')">Ltr</button>'+
                            '<input type="hidden" value="none" id="kgltrSelected'+StInId+'">'+
                            '<input type="hidden" value="'+StInId+'" class="allStoInsToSaveKgLtr">';
                $('#stoKgLtrDiv'+stoInsId).html(newHtml);
            },
			error: (error) => { console.log(error); }
		});
    }
    
    function saveStockKgLtr(){
        var hasOneNone = false;
        var hasNonValidQty = false;
        var toSaveStock = "";
        $('.allStoInsToSaveKgLtr').each(function(key, val) {
            StInId = $(this).val();
            if($("#kgltrSelected"+StInId).val() == 'none'){
                hasOneNone = true;
                return false
            }else{
                if($("#kgltrSelected"+StInId).val() == 'Kg'){
                    if($("#kgAll"+StInId).val() <= 0 || $("#kgmgFoProd"+StInId).val() <= 0){
                        hasNonValidQty = true;
                        return false
                    }else{
                        if(toSaveStock == ""){ toSaveStock = StInId+'||'+$("#kgAll"+StInId).val()+'||'+$("#kgmgFoProd"+StInId).val();
                        }else{ toSaveStock += '--||--'+StInId+'||'+$("#kgAll"+StInId).val()+'||'+$("#kgmgFoProd"+StInId).val();}
                    }
                }else if($("#kgltrSelected"+StInId).val() == 'Ltr'){
                    if($("#ltrAll"+StInId).val() <= 0 || $("#ltrmlFoProd"+StInId).val() <= 0){
                        hasNonValidQty = true;
                        return false
                    }else{
                        if(toSaveStock == ""){ toSaveStock = StInId+'||'+$("#ltrAll"+StInId).val()+'||'+$("#ltrmlFoProd"+StInId).val();
                        }else{ toSaveStock += '--||--'+StInId+'||'+$("#ltrAll"+StInId).val()+'||'+$("#ltrmlFoProd"+StInId).val();}
                    }
                }
            }
        });

        if(hasOneNone){
            if( $('#addStokWithKgLtrErr01').is(':hidden') ){ $('#addStokWithKgLtrErr01').show(10).delay(4500).hide(10); }
        }else if(hasNonValidQty){
            if( $('#addStokWithKgLtrErr02').is(':hidden') ){ $('#addStokWithKgLtrErr02').show(10).delay(4500).hide(10); }
        }else if(toSaveStock == ""){
            if( $('#addStokWithKgLtrErr03').is(':hidden') ){ $('#addStokWithKgLtrErr03').show(10).delay(4500).hide(10); }
        }else{
            $.ajax({
			url: '{{ route("stock.StockMngSaveStockFromKgLtr") }}',
			method: 'post',
			data: {
				stSaveCh: toSaveStock,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                respo2D = respo.split('|||');
                if(respo2D[0] == 'Fail'){

                }else if(respo2D[0] == 'Success'){
                    $('#addStokWithKgLtrModal').modal('hide');
                    if( $('#prodAddScByKgLtrAlert').is(':hidden') ){ $('#prodAddScByKgLtrAlert').show(50).delay(4500).hide(50); }
                    var insChng = respo2D[1];
                    
                    var allToUpdate = insChng.split('-|-');
                    $.each( allToUpdate, function( key, toUpdateOne ) {
                        toUpdateOne2D = toUpdateOne.split('|');
                        var allSIns = $('#selectStockInsToAdd'+toUpdateOne2D[1]).val();
                        var allSIns2D = allSIns.split('||');
                        var currSasia = 0;
                        $("#stateShowKat"+toUpdateOne2D[2]).load(location.href+" #stateShowKat"+toUpdateOne2D[2]+">*","");
                        
                        $.each( allSIns2D, function( key, value ) {
                            if(value == toUpdateOne2D[0]){
                                currSasia = parseInt($('#typeSelBtnSasia'+toUpdateOne2D[1]+'O'+value).html());
                                newSasia = parseInt(parseInt(currSasia)+parseInt(toUpdateOne2D[3]));
                                $('#typeSelBtnSasia'+toUpdateOne2D[1]+'O'+value).html(newSasia);
                            }
                        });
                    });
                    $('#addStokWithKgLtrModalBody').html('');
                }
            },error: (error) => { console.log(error); }
		    });
        }
    }




    function prepDeleteStockInsProduct(stInsId, prodName){
        $('#deleteAStockInsModalLabel').html('<strong>Sind Sie sicher, dass Sie dieses Produkt (" '+prodName+' ") aus dem Lagerbestand löschen möchten?</strong>');
        $('#deleteAStockInsModalAlert').html('Diese Instanz wird einschließlich ihres aktuellen Status gelöscht und nicht mehr aus dem Bestand verwaltet, bis Sie sich entscheiden, sie erneut zu registrieren');
        $('#deleteAStockInsModalJaBtn').attr('onclick','deleteStockInsProduct(\''+stInsId+'\',\''+prodName+'\')');
        $('#deleteAStockInsId').val(stInsId);
        $('#deleteAStockInsModal').modal('show');
    }
    function restoredeleteAStockInsModal(){
        $('#deleteAStockInsModalLabel').html('<strong>---</strong>');
        $('#deleteAStockInsModalAlert').html('---');
        $('#deleteAStockInsModalJaBtn').removeAttr('onclick');
        $('#deleteAStockInsId').val("0");
    }
    function deleteStockInsProduct(stInsId, prodName){
        if($('#deleteAStockInsId').val() == 0){

        }else{
            $.ajax({
                url: '{{ route("stock.StockMngDeleteSockInsProduct") }}',
                method: 'post',
                data: {
                    stId: stInsId,
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    respo = $.trim(respo);
                    respo2D = respo.split('||');
                    $('#deleteAStockInsModal').modal('hide');
                    $("#stateShowKat"+respo2D[1]).load(location.href+" #stateShowKat"+respo2D[1]+">*","");
                    restoredeleteAStockInsModal();
                    $('#stockCatProds'+respo2D[0]).fadeOut(200, function(){ $(this).remove();});
                    $("#stockRegModalMenu").load(location.href+" #stockRegModalMenu>*","");
                    
                },error: (error) => { console.log(error); }
            });
        }
    }



    function prepDeleteStockInsEkstra(stInsId, extName){
        $('#deleteAStockInsModalLabel').html('<strong>Sind Sie sicher, dass Sie dieses Extra (" '+extName+' ") aus dem Lagerbestand löschen möchten?</strong>');
        $('#deleteAStockInsModalAlert').html('Diese Instanz wird einschließlich ihres aktuellen Status gelöscht und nicht mehr aus dem Bestand verwaltet, bis Sie sich entscheiden, sie erneut zu registrieren');
        $('#deleteAStockInsModalJaBtn').attr('onclick','deleteStockInsEkstra(\''+stInsId+'\',\''+extName+'\')');
        $('#deleteAStockInsId').val(stInsId);
        $('#deleteAStockInsModal').modal('show');
    }
    function deleteStockInsEkstra(stInsId, prodName){
        if($('#deleteAStockInsId').val() == 0){

        }else{
            $.ajax({
                url: '{{ route("stock.StockMngDeleteSockInsEkstra") }}',
                method: 'post',
                data: {
                    stId: stInsId,
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    respo = $.trim(respo);
                    respo2D = respo.split('||');
                    $('#deleteAStockInsModal').modal('hide');
                    restoredeleteAStockInsModal();
                    $('#extraInsStOne'+respo2D[0]).fadeOut(200, function(){ $(this).remove();});
                    $("#stockRegModalMenu").load(location.href+" #stockRegModalMenu>*","");
                },error: (error) => { console.log(error); }
            });
        }
    }

</script>