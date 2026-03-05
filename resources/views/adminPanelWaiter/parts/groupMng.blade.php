
<!-- Group management Modal -->
<div class="modal" id="groupMngModal" tabindex="-1" role="dialog" aria-labelledby="groupMngModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupMngModalLabel"><strong>Inhaltsgruppierung verwalten</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body p-1">
                <button class="btn btn-dark" style="width:100%; margin:0px;" id="opnRegGrL1Btn" onclick="openRegGrL1()"><strong>Registriere eine neue Gruppe</strong></button>
                <div id="regGrL1Div" style="display:none;">
                    <div class="input-group mt-1 mb-1">
                        <input type="text" class="form-control shadow-none" placeholder="Gruppenname" aria-label="Gruppenname" aria-describedby="basic-addon2" id="regGrL1InputName">
                        <div class="input-group-append">
                            <button style="margin:0px;" class="btn btn-success shadow-none;" type="button" onclick="saveRegGrL1()"><strong>Sparen</strong></button>
                            <button style="margin:0px;" class="btn btn-dark shadow-none;" type="button" onclick="closeRegGrL1()"><strong>Abbrechen</strong></button>
                        </div>
                    </div>
                    <div class="alert alert-danger text-center" id="regGrL1Err01" style="display:none;">
                        Schreiben Sie zuerst den Namen einer Gruppe
                    </div>
                </div>
                <!-- use App\cntGroupAdmWai; -->
                <!-- use App\cntGroupL2AdmWai; -->
                <div class="pt-2" id="groupMngGropL1List">
                    <?php
                        $allgrL1 = cntGroupAdmWai::where('forUser',Auth::user()->id)->get();
                    ?>
                    @if (count($allgrL1) <= 0)
                        <div class="alert alert-info text-center mt-2">
                            <strong>Es sind noch keine Gruppen registriert</strong>
                        </div>
                    @else 
                        @foreach ($allgrL1 as $oneGL1)
                            @if ($loop->last)
                            <div>
                            @else
                            <div style="border-bottom:2px dotted rgb(72,81,87);">
                            @endif
                                <p class="text-center" style="width: 100%; font-size:1.6rem;"><strong>{{$oneGL1->groupName}}</strong></p>
                                <div style="width:100%; display:flex;" class="justify-content-between" id="grL2DivButtons{{$oneGL1->id}}">
                                    <button style="width: 49%; margin:0px;" class="btn btn-outline-dark shadow-none" onclick="openRegGrL2('{{$oneGL1->id}}')"><strong>neuer Abschnitt</strong></button>
                                    <button style="width: 49%; margin:0px;" class="btn btn-outline-dark shadow-none" onclick="prepDeleteGL1('{{$oneGL1->id}}','{{$oneGL1->groupName}}')" data-toggle="modal" data-target="#deleteGrL1Modal">
                                        <strong>Löschen</strong>
                                    </button>
                                </div>
                                <div style="width: 100%; display:none;" id="regGrL2Div{{$oneGL1->id}}">
                                    <div class="input-group mt-1 mb-1">
                                        <input type="text" class="form-control shadow-none" placeholder="Gruppenname" aria-label="Gruppenname" aria-describedby="basic-addon2" id="regGrL2InputName{{$oneGL1->id}}">
                                        <div class="input-group-append">
                                            <button style="margin:0px;" class="btn btn-success shadow-none;" type="button" onclick="saveRegGrL2('{{$oneGL1->id}}')"><strong>Sparen</strong></button>
                                            <button style="margin:0px;" class="btn btn-dark shadow-none;" type="button" onclick="closeRegGrL2('{{$oneGL1->id}}')"><strong>Abbrechen</strong></button>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger text-center" id="regGrL2Err01{{$oneGL1->id}}" style="display:none;">
                                        Schreiben Sie zuerst den Namen einer Gruppe
                                    </div>
                                </div>
                                <div id="groupMngGropL2List{{$oneGL1->id}}">
                                    <?php
                                        $allgrL2 = cntGroupL2AdmWai::where('toGroup',$oneGL1->id)->get();
                                    ?>
                                    @if (count($allgrL2) <= 0)
                                        <div style="width:100%;" class="alert alert-info text-center mt-2">
                                            <strong>Es sind keine Gruppen der zweiten Ebene registriert</strong>
                                        </div>
                                    @else
                                        @foreach ($allgrL2 as $oneGL2)
                                            <div style="background-color:rgba(0,0,0,0.1) ;" class="d-flex justify-content-between flex-wrap mt-1 mb-1">
                                                <p class="pl-2 mt-1 mb-1" style="width: 55%; font-size:1.2rem;"><strong>{{$oneGL2->groupL2Name}}</strong></p>
                                                <button style="width: 20%; margin:0px; padding:0px;" class="btn btn-info shadow-none" data-toggle="modal" data-target="#GrL2EditModal{{$oneGL1->id}}O{{$oneGL2->id}}">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                                <button style="width: 20%; margin:0px; padding:0px;" class="btn btn-danger shadow-none" onclick="prepDeleteGL2('{{$oneGL2->id}}','{{$oneGL2->groupL2Name}}','{{$oneGL1->id}}')" data-toggle="modal" data-target="#deleteGrL2Modal">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="allGrModals">
    <!-- Delete Groul L1 Modal -->
    <div class="modal" id="deleteGrL1Modal" tabindex="-1" role="dialog" aria-labelledby="deleteGrL1ModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteGrL1ModalLabel"><strong>Möchten Sie diese Gruppe wirklich löschen? <br> <span style="font-size:1.45rem; color:rgb(39,190,175);" id="deleteGrL1GrName"></span></strong></h5>
                    <button type="button" class="close" aria-label="Close" onclick="cancelDeleteGL1()">
                        <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-between flex-wrap">
                    <input type="hidden" id="deleteGrL1GrId" value="0">

                    <button class="btn btn-danger" style="margin:0px; width:49%;" onclick="deleteGL1()">Ja</button>
                    <button class="btn btn-dark" style="margin:0px; width:49%;" onclick="cancelDeleteGL1()">Nein</button>

                    <p class="mt-4" style="width:100%; color:red; font-size:0.75rem;">*Dadurch werden auch alle in dieser Gruppe registrierten Gruppen der zweiten Ebene gelöscht</p>
                </div> 
            </div>
        </div>
    </div>

    <!-- Delete Groul L2 Modal -->
    <div class="modal" id="deleteGrL2Modal" tabindex="-1" role="dialog" aria-labelledby="deleteGrL2ModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteGrL2ModalLabel"><strong>Möchten Sie diese Gruppe wirklich löschen? <br> <span style="font-size:1.45rem; color:rgb(39,190,175);" id="deleteGrL2GrName"></span></strong></h5>
                    <button type="button" class="close" aria-label="Close" onclick="cancelDeleteGL2()">
                        <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-between flex-wrap">
                    <input type="hidden" id="deleteGrL2GrBaseId" value="0">
                    <input type="hidden" id="deleteGrL2GrId" value="0">

                    <button class="btn btn-danger" style="margin:0px; width:49%;" onclick="deleteGL2()">Ja</button>
                    <button class="btn btn-dark" style="margin:0px; width:49%;" onclick="cancelDeleteGL2()">Nein</button>
                </div> 
            </div>
        </div>
    </div>


    <!-- set categories to groups L2 MODALS -->
    @foreach (cntGroupAdmWai::where('forUser',Auth::user()->id)->get() as $GL1)
        @foreach (cntGroupL2AdmWai::where('toGroup',$GL1->id)->get() as $GL2)
            <div class="modal" id="GrL2EditModal{{$GL1->id}}O{{$GL2->id}}" tabindex="-1" role="dialog" aria-labelledby="editGrL2ModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 style="width:100%;" class="d-flex justify-content-between flex-wrap" id="editGrL2ModalLabel">
                                <span style="width:49.5%;"><strong>{{$GL1->groupName}}</strong></span>
                                <span style="width:49.5%;" class="text-right"><strong>{{$GL2->groupL2Name}}</strong></span>
                            </h5>
                        

                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-between flex-wrap">
                                <button style="width:24%; margin:0px;" class="btn btn-dark text-center mb-1"><i style="margin:0px; "class="far fa-circle"></i></button>
                                <p style="width:75%"><strong>noch keine Gruppe</strong></p>
                            
                                <button style="width:24%; margin:0px;" class="btn btn-success text-center mb-1"><i style="margin:0px" class="far fa-check-circle"></i></button>
                                <p style="width:75%"><strong>Ausgewählt</strong></p>
                            
                                <button style="width:24%; margin:0px;" class="btn btn-info text-center mb-1"><i style="margin:0px" class="far fa-stop-circle"></i></button>
                                <p style="width:75%"><strong>In einer anderen Gruppe</strong></p>
                            
                            </div>
                            <div id="GrL2EditModal{{$GL1->id}}O{{$GL2->id}}Cats">
                                @foreach (kategori::where('toRes',Auth::User()->sFor)->get() as $kat)
                                    <div style="width:100%;" class="mb-3">
                                        <div class="allKatFoto" id="KategoriFoto{{$kat->id}}">
                                            <div style="cursor: pointer; position:relative; object-fit: cover;" >
                                                <img style="border-radius:30px; width:100%; height:120px;" src="storage/kategoriaUpload/{{$kat->foto}}" alt="">
                                                <?php
                                                    $catToGR = waAdCatGroups::where([['forUser',Auth::user()->id],['groupL2Id', $GL2->id],['forCat',$kat->id]])->first();
                                                    $catToAnotherGR = waAdCatGroups::where([['forUser',Auth::user()->id],['forCat',$kat->id]])->first();
                                                ?>
                                                @if(strlen($kat->emri) > 20)
                                                    <div class="teksti d-flex justify-content-between" style="font-size:20px;  margin-bottom:13px;">          
                                                        <span style="width: 70%;" class="text-left"><strong>{{$kat->emri}}</strong></span> 
                                                        @if ($catToGR == NULL && $catToAnotherGR == NULL)
                                                            <button style="width:fit-content; margin-top:0px;" class="btn btn-dark mr-3 text-center" id="setGrCatBtn{{$GL2->id}}O{{$kat->id}}"
                                                                onclick="setCatToGroupL2('{{$GL1->id}}','{{$GL2->id}}','{{$kat->id}}')">
                                                                <i style="margin:0px; "class="far fa-circle"></i>
                                                            </button>
                                                        @else
                                                            @if ($catToGR != NULL)
                                                                <button style="width:fit-content; margin-top:0px;" class="btn btn-success mr-3 text-center"><i style="margin:0px" class="far fa-check-circle"></i></button>
                                                            @elseif($catToAnotherGR != NULL)
                                                                <button style="width:fit-content; margin-top:0px;" class="btn btn-info mr-3 text-center" id="setGrCatBtn{{$GL2->id}}O{{$kat->id}}"
                                                                    onclick="changeCatToGroupL2('{{$GL1->id}}','{{$GL2->id}}','{{$kat->id}}','{{$catToAnotherGR->id}}')">
                                                                    <i style="margin:0px" class="far fa-stop-circle"></i>
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="teksti d-flex justify-content-between" >          
                                                        <span style="width: 70%;" class="text-left"><strong>{{$kat->emri}}</strong></span> 
                                                        @if ($catToGR == NULL && $catToAnotherGR == NULL)
                                                            <button style="width:fit-content; margin-top:0px;" class="btn btn-dark mr-3 text-center" id="setGrCatBtn{{$GL2->id}}O{{$kat->id}}"
                                                                onclick="setCatToGroupL2('{{$GL1->id}}','{{$GL2->id}}','{{$kat->id}}')">
                                                                <i style="margin:0px; "class="far fa-circle"></i>
                                                            </button>
                                                        @else
                                                            @if ($catToGR != NULL)
                                                                <button style="width:fit-content; margin-top:0px;" class="btn btn-success mr-3 text-center"><i style="margin:0px" class="far fa-check-circle"></i></button>
                                                            @elseif($catToAnotherGR != NULL)
                                                                <button style="width:fit-content; margin-top:0px;" class="btn btn-info mr-3 text-center" id="setGrCatBtn{{$GL2->id}}O{{$kat->id}}"
                                                                    onclick="changeCatToGroupL2('{{$GL1->id}}','{{$GL2->id}}','{{$kat->id}}','{{$catToAnotherGR->id}}')">
                                                                    <i style="margin:0px" class="far fa-stop-circle"></i>
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button class="btn btn-dark" style="margin:0px; width:100%;" onclick="cancelGrL2Edit('{{$GL1->id}}','{{$GL2->id}}')">schließen</button>
                        </div> 
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
</div>

<script>
    var d = new Date();
    var n = d.getDay();

    function openRegGrL1(){
        $('#opnRegGrL1Btn').hide(200);
        $('#regGrL1Div').show(200);
    }
    function closeRegGrL1(){
        $('#opnRegGrL1Btn').show(200);
        $('#regGrL1Div').hide(200);
    }
    function saveRegGrL1(){
        if(!$('#regGrL1InputName').val()){
            if($('#regGrL1Err01').is(':hidden')){ $('#regGrL1Err01').show(50).delay(4500).hide(50); }
        }else{
            $("#groupMngGropL1List").append('<div class="text-center" style="border-top:2px dotted rgb(72,81,87); width:100%;">'+
                                                '<img src="storage/gifs/loading2.gif" style="width:45px; height:auto;" alt="">'+
                                            '</div>');
            $.ajax({
				url: '{{ route("adminGroup.waiSaveGrL1F") }}',
				method: 'post',
				data: {
					groupN: $('#regGrL1InputName').val(),
					_token: '{{csrf_token()}}'
				},
				success: () => {
                    $("#allGrModals").load(location.href+" #allGrModals>*","");
					$("#groupMngGropL1List").load(location.href+" #groupMngGropL1List>*","");
                    $('#regGrL1InputName').val('');
				},
				error: (error) => { console.log(error); }
			});
        }
    }
    function prepDeleteGL1(grId, grNa){
        $('#deleteGrL1GrName').html(grNa);
        $('#deleteGrL1GrId').val(grId);
    }
    function cancelDeleteGL1(){
        $('#deleteGrL1Modal').modal('toggle');
        $('#deleteGrL1Modal').removeClass('show');
        $('body').attr('class','modal-open');
    }
    function deleteGL1(){
        $.ajax({
			url: '{{ route("adminGroup.deleteGrL1") }}',
			method: 'post',
			data: {
				grL1Id: $('#deleteGrL1GrId').val(),
				_token: '{{csrf_token()}}'
			},
			success: () => {
                cancelDeleteGL1();
                $("#groupMngGropL1List").load(location.href+" #groupMngGropL1List>*","");
			},
			error: (error) => { console.log(error); }
		});
    }
    




    function openRegGrL2(grId){
        $('#grL2DivButtons'+grId).css('display','none');
        $('#regGrL2Div'+grId).show(200);
    }
    function closeRegGrL2(grId){
        $('#regGrL2Div'+grId).hide(200);
        $('#grL2DivButtons'+grId).css('display','flex');
    }
    function saveRegGrL2(grId){
        if(!$('#regGrL2InputName'+grId).val()){
            if($('#regGrL2Err01'+grId).is(':hidden')){ $('#regGrL2Err01'+grId).show(50).delay(4500).hide(50); }
        }else{
            $("#groupMngGropL2List"+grId).append('<div style="background-color:rgba(0,0,0,0.1); width:100%;" class="text-center mt-1 mb-1">'+
                                                    '<img src="storage/gifs/loading2.gif" style="width:25px; height:auto;" alt="">'+
                                                '</div>');
            $.ajax({
				url: '{{ route("adminGroup.waiSaveGrL2F") }}',
				method: 'post',
				data: {
					groupL2N: $('#regGrL2InputName'+grId).val(),
					groupId: grId,
					_token: '{{csrf_token()}}'
				},
				success: () => {
                    $("#allGrModals").load(location.href+" #allGrModals>*","");
					$("#groupMngGropL2List"+grId).load(location.href+" #groupMngGropL2List"+grId+">*","");
                    $('#regGrL2InputName'+grId).val('');
				},
				error: (error) => { console.log(error); }
			});
        }
    }
    function cancelGrL2Edit(gl1Id, gl2Id){
        $('#GrL2EditModal'+gl1Id+'O'+gl2Id).modal('toggle');
        $('#GrL2EditModal'+gl1Id+'O'+gl2Id).removeClass('show');
        $('body').attr('class','modal-open');
    }

    function setCatToGroupL2(grl1Id, grl2Id, katId){
        $('#setGrCatBtn'+grl2Id+'O'+katId).prop('disabled', true);
        $('#setGrCatBtn'+grl2Id+'O'+katId).html('<img src="storage/gifs/loading2.gif" style="width:14.4px; height:14.4px;" alt="">');
        $.ajax({
	    	url: '{{ route("adminGroup.setCateToGroup") }}',
	    	method: 'post',
	    	data: {
	    		grL1Id: grl1Id,
	    		grL2Id: grl2Id,
                katId: katId,
	    		_token: '{{csrf_token()}}'
	    	},
	    	success: () => {
                $("#newProductOrPage2ModalBody2").load(location.href+" #newProductOrPage2ModalBody2>*","");
	    		$("#GrL2EditModal"+grl1Id+"O"+grl2Id+"Cats").load(location.href+" #GrL2EditModal"+grl1Id+"O"+grl2Id+"Cats>*","");
	    	},
	    	error: (error) => { console.log(error); }
	    });
    }

    function changeCatToGroupL2(grl1Id, grl2Id, katId, catToGRId){
        $('#setGrCatBtn'+grl2Id+'O'+katId).prop('disabled', true);
        $('#setGrCatBtn'+grl2Id+'O'+katId).html('<img src="storage/gifs/loading2.gif" style="width:14.4px; height:14.4px;" alt="">');
        $.ajax({
	    	url: '{{ route("adminGroup.changeCateToGroup") }}',
	    	method: 'post',
	    	data: {
	    		grL1Id: grl1Id,
	    		grL2Id: grl2Id,
                catToGRId: catToGRId,
	    		_token: '{{csrf_token()}}'
	    	},
	    	success: () => {
	    		$("#GrL2EditModal"+grl1Id+"O"+grl2Id+"Cats").load(location.href+" #GrL2EditModal"+grl1Id+"O"+grl2Id+"Cats>*","");
	    	},
	    	error: (error) => { console.log(error); }
	    });
    }


    function prepDeleteGL2(grId, grNa, grBase){
        $('#deleteGrL2GrName').html(grNa);
        $('#deleteGrL2GrId').val(grId);
        $('#deleteGrL2GrBaseId').val(grBase);
    }
    function cancelDeleteGL2(){
        $('#deleteGrL2Modal').modal('toggle');
        $('#deleteGrL2Modal').removeClass('show');
        $('body').attr('class','modal-open');
    }
    function deleteGL2(){
        $.ajax({
			url: '{{ route("adminGroup.deleteGrL2") }}',
			method: 'post',
			data: {
				grL2Id: $('#deleteGrL2GrId').val(),
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#groupMngGropL2List"+$('#deleteGrL2GrBaseId').val()).load(location.href+" #groupMngGropL2List"+$('#deleteGrL2GrBaseId').val()+">*","");
                cancelDeleteGL2();
			},
			error: (error) => { console.log(error); }
		});
    }
</script>