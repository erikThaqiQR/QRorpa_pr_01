<script>
    function selectWorkerType(woTy){
        if($('#addWorkerWorkerType').val() != 0){
            $('#woType'+$('#addWorkerWorkerType').val()).removeClass('btn-dark');
            $('#woType'+$('#addWorkerWorkerType').val()).addClass('btn-outline-dark');
        }
        $('#addWorkerWorkerType').val(woTy);
        $('#woType'+woTy).removeClass('btn-outline-dark');
        $('#woType'+woTy).addClass('btn-dark');
    }

    function saveNewWorker(res){
        if(!$('#addWorkerName').val()){
            if($('#addWorkerError01').is(":hidden")){ $('#addWorkerError01').show(50).delay(4500).hide(50); }
        }else if(!$('#addWorkerEmail').val()){
            if($('#addWorkerError02').is(":hidden")){ $('#addWorkerError02').show(50).delay(4500).hide(50); }
        }else if(!$('#addWorkerPassword').val()){
            if($('#addWorkerError03').is(":hidden")){ $('#addWorkerError03').show(50).delay(4500).hide(50); }
        }else if($('#addWorkerWorkerType').val() == 0){
            if($('#addWorkerError04').is(":hidden")){ $('#addWorkerError04').show(50).delay(4500).hide(50); }
        }else{

            var emailInput = $('#addWorkerEmail').val();
            var patternEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            if(!patternEmail.test(emailInput)){
                if($('#addWorkerError05').is(":hidden")){ $('#addWorkerError05').show(50).delay(4500).hide(50); }
            }else{
                $.ajax({
                    url: '{{ route("admWoMng.saveNewWorker") }}',
                    method: 'post',
                    data: {
                        resId: res,
                        woName: $('#addWorkerName').val(),
                        woEmail: $('#addWorkerEmail').val(),
                        woPassword: $('#addWorkerPassword').val(),
                        woType: $('#addWorkerWorkerType').val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: (respo) => {
                        respo = respo.replace(/\s/g, '');
                        if(respo == 'success'){
                            $('#addWorker').modal('hide');
                            $('#addWorkerName').val('');
                            $('#addWorkerEmail').val('');
                            $('#addWorkerPassword').val('');

                            if($('#addWorkerWorkerType').val() == 55){
                                $("#allWorkersWaiter").load(location.href+" #allWorkersWaiter>*","");
                                $("#AccessAndTableModalsForWaiters").load(location.href+" #AccessAndTableModalsForWaiters>*","");
                            }else if($('#addWorkerWorkerType').val() == 54){
                                $("#allWorkersCook").load(location.href+" #allWorkersCook>*","");
                                $("#KPETModalsForCooks").load(location.href+" #KPETModalsForCooks>*","");
                            }else if($('#addWorkerWorkerType').val() == 53){
                                $("#allWorkersAccountant").load(location.href+" #allWorkersAccountant>*","");
                            }

                            $('#woType'+$('#addWorkerWorkerType').val()).removeClass('btn-dark');
                            $('#woType'+$('#addWorkerWorkerType').val()).addClass('btn-outline-dark');
                            $('#addWorkerWorkerType').val(0);
                            $("#freeProElements").load(location.href+" #freeProElements>*","");
                        }else if(respo == 'emailOnlyQrorpa'){
                            if($('#addWorkerError06').is(":hidden")){ $('#addWorkerError06').show(50).delay(4500).hide(50); }
                            $('#addWorkerEmail').val('');
                        }else if(respo == 'emailIsUsed'){
                            if($('#addWorkerError07').is(":hidden")){ $('#addWorkerError07').show(50).delay(4500).hide(50); }
                            $('#addWorkerEmail').val('');
                        }else if(respo == 'emailNotAllowed'){
                            if($('#addWorkerError08').is(":hidden")){ $('#addWorkerError08').show(50).delay(4500).hide(50); }
                            $('#addWorkerEmail').val('');
                        }
                    },
                    error: (error) => { console.log(error); }
                });
            }
        }
    }



    function addThisTable(woId,tableNr,res){
        $('#waiterTable'+woId+'O'+tableNr).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#waiterTable'+woId+'O'+tableNr).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.registerTableForWaiter") }}',
			method: 'post',
			data: {
				workerId: woId,
				tNr: tableNr,
                resId: res,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#setTablesForWo"+woId+"AControlls").load(location.href+" #setTablesForWo"+woId+"AControlls>*","");
			},
			error: (error) => { console.log(error); }
		});
    }
    function removeThisTable(tatwaiterId,woId,tableNr){
        $('#waiterTable'+woId+'O'+tableNr).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#waiterTable'+woId+'O'+tableNr).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.removeTableForWaiter") }}',
			method: 'post',
			data: {
				tatwaiterId: tatwaiterId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#setTablesForWo"+woId+"AControlls").load(location.href+" #setTablesForWo"+woId+"AControlls>*","");
			},
			error: (error) => { console.log(error); }
		});
    }





    function addThisAccess(adminId,waiterId,accessId){
        $('#waiterAccess'+waiterId+'O'+accessId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#waiterAccess'+waiterId+'O'+accessId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.registerAccessForWaiter") }}',
			method: 'post',
			data: {
				admId: adminId,
                waId: waiterId,
                accContId: accessId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = respo.replace(/\s/g, '');
                if(respo == 'Success'){
				    $("#setAccessForWo"+waiterId+"AControlls").load(location.href+" #setAccessForWo"+waiterId+"AControlls>*","");
                }
			},
			error: (error) => { console.log(error); }
		});
    }
    function removeThisAccess(waiterACCId,waiterId,accessId){
        $('#waiterAccess'+waiterId+'O'+accessId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#waiterAccess'+waiterId+'O'+accessId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.removeAccessForWaiter") }}',
			method: 'post',
			data: {
				waiterACCId: waiterACCId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				$("#setAccessForWo"+waiterId+"AControlls").load(location.href+" #setAccessForWo"+waiterId+"AControlls>*","");
			},
			error: (error) => { console.log(error); }
		});
    }









    function showProKat(kId,woId) {
        if ($('#state'+kId+'O'+woId).val() == 0) {
            $('#prodsKatFoto'+kId+'O'+woId).show(50);
            $('#state'+kId+'O'+woId).val(1);

        } else {
            $('#prodsKatFoto'+kId+'O'+woId).hide(50);
            $('#state'+kId+'O'+woId).val(0)
        }
    }

    function closeCategoryTAndE(kId,woId){
        $('#categoryTAndE'+kId+'O'+woId).modal('hide');
        $('body').addClass('modal-open');
    }



    function addCategoryForCook(woId, katId){
        $('#KategoriPlus'+katId+'O'+woId).html('<img class="gifImg ml-2 pt-1" src="storage/gifs/loading2.gif" alt=""> ')
        $('#KategoriPlus'+katId+'O'+woId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.registerCategoryForCook") }}',
			method: 'post',
			data: {
				categoryId: katId,
				workerId: woId,
                toRes: '{{Auth::User()->sFor}}',
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#KategoriFoto"+katId+"O"+woId).load(location.href+" #KategoriFoto"+katId+"O"+woId+">*","");
			},
			error: (error) => { console.log(error); }
		});

    }
    function removeCategoryForCook(woId, katId, prId){
        $('#KategoriPlus'+katId+'O'+woId).html('<img class="gifImg ml-2 pt-1" src="storage/gifs/loading2.gif" alt=""> ')
        $('#KategoriPlus'+katId+'O'+woId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.removeCategoryForCook") }}',
			method: 'post',
			data: {
                productRecordId: prId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#KategoriFoto"+katId+"O"+woId).load(location.href+" #KategoriFoto"+katId+"O"+woId+">*","");
			},
			error: (error) => { console.log(error); }
		});
    }





    function addExtraForCook(exId,woId,katId){
        $('#addExtraForCookBtn'+exId+'O'+woId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#addExtraForCookBtn'+exId+'O'+woId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.registerExtraForCook") }}',
			method: 'post',
			data: {
				extraId: exId,
				workerId: woId,
                toRes: '{{Auth::User()->sFor}}',
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				$("#categoryTAndE"+katId+"O"+woId+"Body").load(location.href+" #categoryTAndE"+katId+"O"+woId+"Body>*","");
			},
			error: (error) => { console.log(error); }
		});
    }
    function removeExtraForCook(prId,exId,woId,katId){
        $('#addExtraForCookBtn'+exId+'O'+woId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#addExtraForCookBtn'+exId+'O'+woId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.removeExtraForCook") }}',
			method: 'post',
			data: {
                productRecordId: prId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				$("#categoryTAndE"+katId+"O"+woId+"Body").load(location.href+" #categoryTAndE"+katId+"O"+woId+"Body>*","");
			},
			error: (error) => { console.log(error); }
		});
    }


    function addTypeForCook(tyId,woId,katId){
        $('#addTypeForCookBtn'+tyId+'O'+woId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#addTypeForCookBtn'+tyId+'O'+woId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.registerTypeForCook") }}',
			method: 'post',
			data: {
				typeId: tyId,
				workerId: woId,
                toRes: '{{Auth::User()->sFor}}',
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				$("#categoryTAndE"+katId+"O"+woId+"Body").load(location.href+" #categoryTAndE"+katId+"O"+woId+"Body>*","");
			},
			error: (error) => { console.log(error); }
		});
    }
    function removeTypeForCook(prId,tyId,woId,katId){
        $('#addTypeForCookBtn'+tyId+'O'+woId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#addTypeForCookBtn'+tyId+'O'+woId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.removeTypeForCook") }}',
			method: 'post',
			data: {
                productRecordId: prId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				$("#categoryTAndE"+katId+"O"+woId+"Body").load(location.href+" #categoryTAndE"+katId+"O"+woId+"Body>*","");
			},
			error: (error) => { console.log(error); }
		});
    }



    function addProductForCook(woId, prodId,katId){
        $('#addProductForCookBtn'+woId+'O'+prodId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#addProductForCookBtn'+woId+'O'+prodId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.registerProductForCook") }}',
			method: 'post',
			data: {
                workerId: woId,
                prodId: prodId,
                toRes: '{{Auth::User()->sFor}}',
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				$("#catProds"+prodId+"O"+woId).load(location.href+" #catProds"+prodId+"O"+woId+">*","");
			},
			error: (error) => { console.log(error); }
		});
    }
    function removeProductForCook(prId,woId,prodId,katId){
        $('#addProductForCookBtn'+woId+'O'+prodId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#addProductForCookBtn'+woId+'O'+prodId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.removeProductForCook") }}',
			method: 'post',
			data: {
                productRecordId: prId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				$("#catProds"+prodId+"O"+woId).load(location.href+" #catProds"+prodId+"O"+woId+">*","");
			},
			error: (error) => { console.log(error); }
		});
    }


    function cookTakeawayAccess(coId,res){
        $('#cookTakeawayAccessBtn'+coId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#cookTakeawayAccessBtn'+coId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.takeawayAccessForCook") }}',
			method: 'post',
			data: {
                workerId: coId,
                resId: res,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#oneUsers"+coId).load(location.href+" #oneUsers"+coId+">*","");
			},
			error: (error) => { console.log(error); }
		});
    }
    function cookDeliveryAccess(coId,res){
        $('#cookDeliveryAccessBtn'+coId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#cookDeliveryAccessBtn'+coId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.deliveryAccessForCook") }}',
			method: 'post',
			data: {
                workerId: coId,
                resId: res,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#oneUsers"+coId).load(location.href+" #oneUsers"+coId+">*","");
			},
			error: (error) => { console.log(error); }
		});
    }


    function cookPanVerChng(usrId, newPV){
        $('#cookPanV1Btn'+usrId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#cookPanV1Btn'+usrId).prop('disabled',true);
        $('#cookPanV2Btn'+usrId).html('<img class="gifImg" src="storage/gifs/loading2.gif" alt="">')
        $('#cookPanV2Btn'+usrId).prop('disabled',true);
        $.ajax({
			url: '{{ route("admWoMng.chngCooksPVer") }}',
			method: 'post',
			data: {
                workerId: usrId,
                newVer: newPV,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*","");
			},
			error: (error) => { console.log(error); }
		})
    }




    function deleteWorker(woId){
        $.ajax({
			url: '{{ route("admWoMng.deleteWorker") }}',
			method: 'post',
			data: {
                workerId: woId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#allWorkersWaiter").load(location.href+" #allWorkersWaiter>*","");
				$("#allWorkersCook").load(location.href+" #allWorkersCook>*","");
				$("#allWorkersAccountant").load(location.href+" #allWorkersAccountant>*","");
			},
			error: (error) => { console.log(error); }
		});
    }








    function selCatPlate(plate, catId){
        if(!$('#'+plate+'Btn'+catId).hasClass('btn-success')){
            $('.pAllBtn'+catId).removeClass('btn-success');
            $('.pAllBtn'+catId).addClass('btn-dark');

            $('#p'+plate+'Btn'+catId).removeClass('btn-dark');
            $('#p'+plate+'Btn'+catId).addClass('btn-success');

            $.ajax({
				url: '{{ route("admWoMng.setPlateForCat") }}',
				method: 'post',
				data: {
					plateNr: plate,
					catId: catId,
					_token: '{{csrf_token()}}'
				},
				success: () => {
					// $("#freeProElements").load(location.href+" #freeProElements>*","");
				},
				error: (error) => { console.log(error); }
			});

        }
    }


    function saveNewPlate(){
        if(!$('#newPlateName').val()){
            if($('#savePlateError01').is(":hidden")){ $('#savePlateError01').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
				url: '{{ route("admWoMng.saveNewPlate") }}',
				method: 'post',
				data: {
					newPlName: $('#newPlateName').val(),
					resId: "{{Auth::user()->sFor}}",
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = $.trim(respo);
                    if(respo == 'duplicateIns'){
                        $('#newPlateName').val('');
                        if($('#savePlateError02').is(":hidden")){ $('#savePlateError02').show(100).delay(4500).hide(100); }
                    }else if(respo == 'success'){
                        $("#catToPlateShowPlates").load(location.href+" #catToPlateShowPlates>*","");
                        $("#catToPlateShowCats").load(location.href+" #catToPlateShowCats>*","");
                        $('#newPlateName').val('');
                        var nePlNr = parseInt(parseInt($('#catToPlateNextPlSp').html())+parseInt(1));
                        $('#catToPlateNextPlSp').html(nePlNr);
                        if($('#savePlateSuccess01').is(":hidden")){ $('#savePlateSuccess01').show(100).delay(4500).hide(100); }
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    }
    function deleteResPlate(plId){
        $.ajax({
			url: '{{ route("admWoMng.deleteThisPlate") }}',
			method: 'post',
			data: {
				pId: plId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                if(respo == 'success'){
                    $("#catToPlateShowPlates").load(location.href+" #catToPlateShowPlates>*","");
                    $("#catToPlateShowCats").load(location.href+" #catToPlateShowCats>*","");
                    var nePlNr = parseInt(parseInt($('#catToPlateNextPlSp').html())-parseInt(1));
                    $('#catToPlateNextPlSp').html(nePlNr);
                    if($('#delEditScs01').is(":hidden")){ $('#delEditScs01').show(100).delay(4500).hide(100); }
                }
			},
			error: (error) => { console.log(error); }
		});
    }

    function editResPlate(pId, pNT, pNr){
        $('#editPlateNr').html(pNr);
        $('#editPlateName').val(pNT);
        $('#editPlateId').val(pId);

        $('#newPlateDiv').hide(50);
        $('#editPlateDiv').show(50);
    }
    function cancelChangesPlate(){
        $('#editPlateDiv').hide(50);
        $('#newPlateDiv').show(50);
    }
    function saveChangesPlate(){
        if(!$('#editPlateName').val()){
            if($('#savePlateError01').is(":hidden")){ $('#savePlateError01').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
                url: '{{ route("admWoMng.saveChangesFPlate") }}',
                method: 'post',
                data: {
                    pId: $('#editPlateId').val(),
                    pNewName: $('#editPlateName').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    respo = $.trim(respo);
                    if(respo == 'success'){
                        $("#catToPlateShowPlates").load(location.href+" #catToPlateShowPlates>*","");
                        $("#catToPlateShowCats").load(location.href+" #catToPlateShowCats>*","");
                        $('#editPlateDiv').hide(50);
                        $('#newPlateDiv').show(50);
                        if($('#delEditScs02').is(":hidden")){ $('#delEditScs02').show(100).delay(4500).hide(100); }
                    }else if(respo == 'rpNotFound'){
                        if($('#editPlateError01').is(":hidden")){ $('#editPlateError01').show(100).delay(4500).hide(100); }
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    }






    function setAcceStatTo53(usID){
		$.ajax({
			url: '{{ route("admWoMng.setAcceToStatPgFA") }}',
			method: 'post',
			data: {
				usrId: usID,
				_token: '{{csrf_token()}}'
			},
			success: () => { $("#allWorkersAccountant").load(location.href+" #allWorkersAccountant>*",""); },
			error: (error) => { console.log(error); }
		});
    }

    function setAcceProdsTo53(usID){
        $.ajax({
			url: '{{ route("admWoMng.setAcceToProdPgFA") }}',
			method: 'post',
			data: {
				usrId: usID,
				_token: '{{csrf_token()}}'
			},
			success: () => { $("#allWorkersAccountant").load(location.href+" #allWorkersAccountant>*",""); },
			error: (error) => { console.log(error); }
		});
    }










    function changePassForWoPrep(usrId,usrName){
        $('#changePassForWoId').val(usrId);
        $('#changePassForWoModalName').html(usrName);
        $('#newPassInp1').val('');
        $('#newPassInp2').val('');
    }
    function showPassInp(inpNr){
        $('#newPassInp'+inpNr).attr('type','text');
        $('#newPassBtn'+inpNr).attr('onclick','hidePassInp("'+inpNr+'")');
        $('#newPassBtn'+inpNr).html('<i class="fa-solid fa-eye"></i>');
    }
    function hidePassInp(inpNr){
        $('#newPassInp'+inpNr).attr('type','password');
        $('#newPassBtn'+inpNr).attr('onclick','showPassInp("'+inpNr+'")');
        $('#newPassBtn'+inpNr).html('<i class="fa-solid fa-eye-slash"></i>');
    }

    function changePassForWo(){
        if(!$('#newPassInp1').val()){
            if($('#changePassForWoModalErr01').is(':hidden')){ $('#changePassForWoModalErr01').show(100).delay(4500).hide(100); }
        }else if($('#newPassInp1').val().length < 8){
            if($('#changePassForWoModalErr02').is(':hidden')){ $('#changePassForWoModalErr02').show(100).delay(4500).hide(100); }
        }else if(!$('#newPassInp2').val()){
            if($('#changePassForWoModalErr03').is(':hidden')){ $('#changePassForWoModalErr03').show(100).delay(4500).hide(100); }
        }else if($('#newPassInp1').val() != $('#newPassInp2').val()){
            if($('#changePassForWoModalErr04').is(':hidden')){ $('#changePassForWoModalErr04').show(100).delay(4500).hide(100); }
        }else{
            $.ajax({
				url: '{{ route("admWoMng.setNewPassToAWorker") }}',
				method: 'post',
				data: {
					uId: $('#changePassForWoId').val(),
                    newPas: $('#newPassInp1').val(),
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = $.trim(respo);
                    if(respo == 'usrNotFound'){
                        if($('#changePassForWoModalErr05').is(':hidden')){ $('#changePassForWoModalErr05').show(100).delay(4500).hide(100); }
                    }else{
                        if($('#changePassForWoModalScc01').is(':hidden')){ $('#changePassForWoModalScc01').show(100).delay(4500).hide(100); }
                        $('#newPassInp1').val('');
                        $('#newPassInp2').val('');
                    }
				},
				error: (error) => { console.log(error); }
			});
					
        }
    }
</script>