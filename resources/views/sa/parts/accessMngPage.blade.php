<?php
    use App\accessControllForAdmins;
    use App\Restorant;
    use App\User;
?>
<style>
    .pointingElement:hover{
        cursor: pointer;
        background-color:rgba(247, 247, 240, 1) !important;
    }
    .pointingElement2:hover{
        cursor: pointer;
    }
    .accPagesStyle1{
        width:33%; 
        margin:0px 0.33% 5px 0px; 
        color:rgb(39,190,175);
        font-weight: bold;
    }
    .accPagesStyle2{
        width:49.5%; 
        margin:0px 0.33% 5px 0px; 
        font-weight: bold;
    }
    .accessAllow{
        background-color: rgb(92, 214, 92);
        padding: 10px;
        border-radius: 3px;
        color: white;
    }
    .accessAllow:hover{
        cursor: pointer;
    }
    .accessDenied{
        background-color: rgba(255, 0, 0, 0.6);
        padding: 10px;
        border-radius: 3px;
        color: white;
    }
    .accessDenied:hover{
        cursor: pointer;
    }
</style>
<section class="pl-3 pr-3 mb-5" id="accessPageSection">
    <h3 class="pl-3" style="color:rgb(39,190,175);"><strong>Zugangskontrolle</strong></h3>
    <hr>
    <button class="btn btn-outline-success mb-2" data-toggle="modal" data-target="#addNewAccessModal"><strong>+ neue Zutrittskontrolle</strong></button>

    @foreach (Restorant::all()->sortByDesc('created_at') as $oneRes)
        @php
            $acFThisRes = accessControllForAdmins::where('forRes',$oneRes->id)->count();
        @endphp
        @if($acFThisRes > 0)
        <div class="card mb-1" style="width: 100%;">
            <div onclick="openAccessForRes('{{$oneRes->id}}')" class="card-header pointingElement2">
                <strong style="color:rgb(72,81,87);">{{ $oneRes->id }} # _ {{ $oneRes->emri }} ( {{$acFThisRes}} )</strong>
            
            </div>
            <ul style="display:none;" class="list-group list-group-flush allAccessUlForRes" id="accessUlForRes{{$oneRes->id}}">

                @foreach (User::where([['role','5'],['sFor',$oneRes->id]])->get() as $adminOfRes)
                    <li class="list-group-item" style="font-weight: bold; padding:4px 4px 4px 20px;">
                        <div class="card mb-1" style="width: 100%;">
                            <div onclick="openAccessForResSpecUser('{{$adminOfRes->id}}')" class="card-header pointingElement2">
                                <strong style="color:rgb(39,190,175);"> 
                                    <span style="color:green" class="mr-2">A: <span id="ativeAccessUser{{$adminOfRes->id}}"></span></span>
                                    <span style="color:red" class="mr-4">P: <span id="passiveAccessUser{{$adminOfRes->id}}"></span></span>
                                     {{ $adminOfRes->name }} 
                                </strong>
                            </div>
                            <ul style="display:none;" class="list-group list-group-flush allAccessUlForResSpecUser" id="accessUlForResSpecUser{{$adminOfRes->id}}">
                                @php
                                    $countA = 0;
                                    $countP = 0;
                                @endphp
                            @foreach (accessControllForAdmins::where('userId',$adminOfRes->id)->get() as $oneAccess)
                                @if ($oneAccess->accessValid == 1)
                                @php
                                    $countA++;
                                @endphp
                                <!-- Active  -->
                                <li class="list-group-item d-flex" style="font-weight: bold; padding:4px;" id="accessListing{{$oneAccess->id}}">
                                    <button onclick="changeAccessValidity('{{$oneAccess->id}}','0','{{$adminOfRes->id}}')" style="width: 15%;" class="mr-3 btn btn-success"><strong>ist aktiv</strong></button>
                                    <p style="width: 20%;">{{$oneAccess->accessDsc}}</p>
                                    <p style="width: 65%;"></p>
                                @else
                                @php
                                    $countP++;
                                @endphp
                                <!-- not Active  -->
                                <li class="list-group-item d-flex" style="font-weight: bold; padding:4px; background-color:rgb(186, 104, 104);" id="accessListing{{$oneAccess->id}}">
                                    <button onclick="changeAccessValidity('{{$oneAccess->id}}','1','{{$adminOfRes->id}}')" style="width: 15%;" class="mr-3 btn btn-danger"><strong>ist nicht aktiv</strong></button>
                                    <p style="width: 20%; color:white">{{$oneAccess->accessDsc}}</p>
                                    <p style="width: 65%;"></p>
                                @endif
                                </li>
                            @endforeach
                            <script>
                                $('#ativeAccessUser{{$adminOfRes->id}}').html('{{$countA}}');
                                $('#passiveAccessUser{{$adminOfRes->id}}').html('{{$countP}}');
                            </script>
                            </ul>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>
        @endif
    @endforeach
</section>







<!-- Modal -->
<div class="modal" id="addNewAccessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top: 5%;" aria-modal="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: bold; color:rgb(39,190,175);" id="exampleModalLongTitle">Administratorzugang registrieren / löschen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetAddNewAccessModal()">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap" id="addNewAccessModalBodyP">
                    <div style="width: 100%; border-left:1px solid lightgray;" class="d-flex justify-content-between flex-wrap p-2" id="addNewAccessModalBodyP1"> 
                        <p style="width: 100%;"><strong>Wählen Sie zuerst ein Restaurant aus</strong></p>
                        @foreach(Restorant::all()->sortByDesc('created_at') as $resOne)
                            <div class="pointingElement d-flex mb-1 p-3" onclick="addNewAccessModalSelRes('{{$resOne->id}}')" style="width:19.7%; background-color:rgba(247, 247, 240, 0.65); border-radius:10px;">
                                <img style="width:25%; height:auto; border-radius:50%;" src="storage/ResProfilePic/{{$resOne->profilePic}}" alt="">
                                <p style="width:75%;" class="pl-3"><strong> {{$resOne->emri}} </strong></p>
                            </div>
                        @endforeach
                    </div>
                    <hr id="addNewAccessModalBodyP1hr" style="width: 100%;">
                    <div style="width: 80%;" class="d-flex flex-wrap" id="addNewAccessModalBodyP2">

                    </div>
                    <div style="width: 20%; border-left:1px solid lightgray;" class="d-flex flex-wrap p-2" id="addNewAccessModalBodyP3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>


    function openAccessForRes(resId){
        if($('#accessUlForRes'+resId).is(":hidden")){
            $('.allAccessUlForRes').hide(400);
            $('#accessUlForRes'+resId).show(400);
        }else{
            $('.allAccessUlForRes').hide(400);
        }
    }

    function openAccessForResSpecUser(userId){
        if($('#accessUlForResSpecUser'+userId).is(":hidden")){
            $('.allAccessUlForResSpecUser').hide(400);
            $('#accessUlForResSpecUser'+userId).show(400);
        }else{
            $('.allAccessUlForResSpecUser').hide(400);
        }
    }

    function changeAccessValidity(accessId, newV, userId){
        $.ajax({
			url: '{{ route("SAaccessMng.changeValidity") }}',
			method: 'post',
			data: {
				id: accessId,
                validity: newV,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#accessUlForResSpecUser"+userId).load(location.href+" #accessUlForResSpecUser"+userId+">*","");
                if(newV == '0'){
                    // deactivating
                    $('#ativeAccessUser'+userId).html(parseInt(parseInt($('#ativeAccessUser'+userId).html()) - parseInt(1)));
                    $('#passiveAccessUser'+userId).html(parseInt(parseInt($('#passiveAccessUser'+userId).html()) + parseInt(1)));
                }else{
                    // activation 
                    $('#ativeAccessUser'+userId).html(parseInt(parseInt($('#ativeAccessUser'+userId).html()) + parseInt(1)));
                    $('#passiveAccessUser'+userId).html(parseInt(parseInt($('#passiveAccessUser'+userId).html()) - parseInt(1)));
                }
			},
			error: (error) => { console.log(error); }
		});
    }


    function addNewAccessModalSelRes(resId){
        $('#addNewAccessModalBodyP1').remove();
        $('#addNewAccessModalBodyP1hr').remove();

        $('#addNewAccessModalBodyP2').html('');
        $('#addNewAccessModalBodyP3').html('');
        $.ajax({
			url: '{{ route("SAaccessMng.fetchAdmins") }}',
			method: 'post',
            dataType: 'json',
			data: {
                resId : resId,
				_token: '{{csrf_token()}}'
			},
			success: (response) => {
                $('#addNewAccessModalBodyP3').append('<p style="width:100%; color:rgb(39,190,175)" class="text-center"><strong> Administratoren </strong></p>'); 
                $.each(response, function(index, value){
                    var listings =  '<div class="mb-1 p-3" style="width:100%; background-color:rgba(247, 247, 240, 0.65); border-radius:10px;">'+
                                        '<p class="text-center"><strong> '+value.name+' </strong></p>'+
                                    '</div>';
                    $('#addNewAccessModalBodyP3').append(listings); 
                });
			},
			error: (error) => { console.log(error); }
		});

       
        $('#addNewAccessModalBodyP2').append('<p style="width:100%; color:rgb(39,190,175)" class="text-center d-flex"><span style="width:10%;" class="pointingElement2" onclick="backAddNewAccessModal()"><i class="fas fa-2x fa-arrow-left"></i></span><strong style="width:90%">Dienstleistungen</strong></p>'); 

        $.ajax({
			url: '{{ route("SAaccessMng.fetchAccess") }}',
			method: 'post',
			data: {
                resId : resId,
				_token: '{{csrf_token()}}'
			},
			success: (response) => {
                response = response.replace(/\s/g, '');
                res2D = response.split('||');

                if(res2D[0] == 1){ var v1= 'accessAllow'; var va1 = 1;}else{ var v1= 'accessDenied'; var va1 = 0;}
                if(res2D[1] == 1){ var v2= 'accessAllow'; var va2 = 1;}else{ var v2= 'accessDenied'; var va2 = 0;}
                if(res2D[2] == 1){ var v3= 'accessAllow'; var va3 = 1;}else{ var v3= 'accessDenied'; var va3 = 0;}
                if(res2D[3] == 1){ var v4= 'accessAllow'; var va4 = 1;}else{ var v4= 'accessDenied'; var va4 = 0;}
                if(res2D[4] == 1){ var v5= 'accessAllow'; var va5 = 1;}else{ var v5= 'accessDenied'; var va5 = 0;}
                if(res2D[5] == 1){ var v6= 'accessAllow'; var va6 = 1;}else{ var v6= 'accessDenied'; var va6 = 0;}
                if(res2D[6] == 1){ var v7= 'accessAllow'; var va7 = 1;}else{ var v7= 'accessDenied'; var va7 = 0;}
                if(res2D[7] == 1){ var v8= 'accessAllow'; var va8 = 1;}else{ var v8= 'accessDenied'; var va8 = 0;}
                if(res2D[8] == 1){ var v9= 'accessAllow'; var va9 = 1;}else{ var v9= 'accessDenied'; var va9 = 0;}
                if(res2D[9] == 1){ var v10= 'accessAllow'; var va10 = 1;}else{ var v10= 'accessDenied'; var va10 = 0;}
                if(res2D[10] == 1){ var v11= 'accessAllow'; var va11 = 1;}else{ var v11= 'accessDenied'; var va11 = 0;}
                if(res2D[11] == 1){ var v12= 'accessAllow'; var va12 = 1;}else{ var v12= 'accessDenied'; var va12 = 0;}
                if(res2D[12] == 1){ var v13= 'accessAllow'; var va13 = 1;}else{ var v13= 'accessDenied'; var va13 = 0;}
                if(res2D[13] == 1){ var v14= 'accessAllow'; var va14 = 1;}else{ var v14= 'accessDenied'; var va14 = 0;}
                if(res2D[14] == 1){ var v15= 'accessAllow'; var va15 = 1;}else{ var v15= 'accessDenied'; var va15 = 0;}
                if(res2D[15] == 1){ var v16= 'accessAllow'; var va16 = 1;}else{ var v16= 'accessDenied'; var va16 = 0;}
                if(res2D[16] == 1){ var v17= 'accessAllow'; var va17 = 1;}else{ var v17= 'accessDenied'; var va17 = 0;}
                if(res2D[17] == 1){ var v18= 'accessAllow'; var va18 = 1;}else{ var v18= 'accessDenied'; var va18 = 0;}
                if(res2D[18] == 1){ var v19= 'accessAllow'; var va19 = 1;}else{ var v19= 'accessDenied'; var va19 = 0;}
                if(res2D[19] == 1){ var v20= 'accessAllow'; var va20 = 1;}else{ var v20= 'accessDenied'; var va20 = 0;}
                if(res2D[20] == 1){ var v21= 'accessAllow'; var va21 = 1;}else{ var v21= 'accessDenied'; var va21 = 0;}
                
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v1+'" onclick="regDelAccess(\''+resId+'\',\''+va1+'\',\'Statistiken\')"><strong> Statistiken </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v2+'" onclick="regDelAccess(\''+resId+'\',\''+va2+'\',\'Aufträge\')"><strong> Aufträge </strong></div>');
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v3+'" onclick="regDelAccess(\''+resId+'\',\''+va3+'\',\'Empfohlen\')"><strong> Empfohlen </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v4+'" onclick="regDelAccess(\''+resId+'\',\''+va4+'\',\'Kellner\')"><strong> Kellner </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v5+'" onclick="regDelAccess(\''+resId+'\',\''+va5+'\',\'Products\')"><strong> Products </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v6+'" onclick="regDelAccess(\''+resId+'\',\''+va6+'\',\'Tabellenwechsel\')"><strong> Tabellenwechsel </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v7+'" onclick="regDelAccess(\''+resId+'\',\''+va7+'\',\'Trinkgeld\')"><strong> Trinkgeld </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v8+'" onclick="regDelAccess(\''+resId+'\',\''+va8+'\',\'Frei\')"><strong> Frei </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v9+'" onclick="regDelAccess(\''+resId+'\',\''+va9+'\',\'16+/18+\')"><strong> 16+/18+ </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v10+'" onclick="regDelAccess(\''+resId+'\',\''+va10+'\',\'Gutscheincode\')"><strong> Gutscheincode </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v11+'" onclick="regDelAccess(\''+resId+'\',\''+va11+'\',\'Takeaway\')"><strong> Takeaway </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v12+'" onclick="regDelAccess(\''+resId+'\',\''+va12+'\',\'Delivery\')"><strong> Delivery </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v13+'" onclick="regDelAccess(\''+resId+'\',\''+va13+'\',\'Tischkapazität\')"><strong> Tischkapazität </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v14+'" onclick="regDelAccess(\''+resId+'\',\''+va14+'\',\'Tischreservierungen\')"><strong> Tischreservierungen </strong></div>'); 
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v15+'" onclick="regDelAccess(\''+resId+'\',\''+va15+'\',\'Dienstleistungen\')"><strong> Dienstleistungen </strong></div>');  
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v16+'" onclick="regDelAccess(\''+resId+'\',\''+va16+'\',\'Covid-19\')"><strong> Covid-19 </strong></div>');  
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v17+'" onclick="regDelAccess(\''+resId+'\',\''+va17+'\',\'talkToQrorpaSA\')"><strong> talkToQrorpaSA </strong></div>');  
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v18+'" onclick="regDelAccess(\''+resId+'\',\''+va18+'\',\'workersManagement\')"><strong> workersManagement </strong></div>');  
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v19+'" onclick="regDelAccess(\''+resId+'\',\''+va19+'\',\'RechnungMngAcce\')"><strong> RechnungMngAcce </strong></div>');  
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v20+'" onclick="regDelAccess(\''+resId+'\',\''+va20+'\',\'RechnungMngAcce\')"><strong> Tabellenstatus/Tische </strong></div>');  
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle1 text-center '+v21+'" onclick="regDelAccess(\''+resId+'\',\''+va21+'\',\'RechnungMngAcce\')"><strong> Heute/Verkaufe </strong></div>');  
                
                $('#addNewAccessModalBodyP2').append('<p class="accPagesStyle1"></p>');
                $('#addNewAccessModalBodyP2').append('<hr style="width:100%">');

                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle2 btn btn-danger text-center " onclick="regDelAccessNone(\''+resId+'\')"><strong> alles entfernen </strong></div>');  
                $('#addNewAccessModalBodyP2').append('<div class="accPagesStyle2 btn btn-success text-center " onclick="regDelAccessAll(\''+resId+'\')"><strong> füge alle Hinzu </strong></div>');  
            },
			error: (error) => { console.log(error); }
		});
       
        
    }


    


    function regDelAccess(resId, active, pName){
        $.ajax({
			url: '{{ route("SAaccessMng.regDelAccess") }}',
			method: 'post',
			data: {
                type: active,
                res: resId,
                pageN: pName,
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
				this.addNewAccessModalSelRes(res);
                $("#accessPageSection").load(location.href+" #accessPageSection>*","");
			},
			error: (error) => { console.log(error); }
		});
    }

    function regDelAccessAll(resId){
        $.ajax({
			url: '{{ route("SAaccessMng.regAllAccess") }}',
			method: 'post',
			data: {
                res: resId,
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
				this.addNewAccessModalSelRes(resId);
                $("#accessPageSection").load(location.href+" #accessPageSection>*","");
			},
			error: (error) => { console.log(error); }
		});
    }

    function regDelAccessNone(resId){
        $.ajax({
			url: '{{ route("SAaccessMng.delAllAccess") }}',
			method: 'post',
			data: {
                res: resId,
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
				this.addNewAccessModalSelRes(resId);
                $("#accessPageSection").load(location.href+" #accessPageSection>*","");
			},
			error: (error) => { console.log(error); }
		});
    }

    function resetAddNewAccessModal(){ $("#addNewAccessModal").load(location.href+" #addNewAccessModal>*",""); }
    function backAddNewAccessModal(){ $("#addNewAccessModalBodyP").load(location.href+" #addNewAccessModalBodyP>*","");}
</script>