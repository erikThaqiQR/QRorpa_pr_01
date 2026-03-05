<?php

use App\billTabletsReg;
use App\User;
use Illuminate\Support\Facades\Auth;
    $allBillTRes = billTabletsReg::where([['tabletBillType','Res'],['toRes',Auth::user()->sFor]])->get();
    $allBillTTa = billTabletsReg::where([['tabletBillType','Ta'],['toRes',Auth::user()->sFor]])->get();
?> 
<div style="padding: 5px 10px 40px 10px;">
    <div class="d-flex flex-wrap justify-content-around">
        <p style="font-size:1.3rem; color:rgb(39,190,175); margin:0px; width:100%;"><strong>Quittungstablett</strong></p>

        <button style="width:49%; margin:0px; color:rgb(72,81,87);" class="btn btn-outline-dark shadow-none" data-toggle="modal" data-target="#RegBillTabletModal">
            <strong><i class="fa-solid fa-plus"></i> Neues Rechnungs-Tablet RESTAURANT</strong>
        </button>

        <button style="width:49%; margin:0px; color:rgb(72,81,87);" class="btn btn-outline-dark shadow-none" data-toggle="modal" data-target="#RegBillTabletModalTA">
            <strong><i class="fa-solid fa-plus"></i> Neues Rechnungs-Tablet TAKEAWAY</strong>
        </button>
    </div>
    
    <hr style="margin: 4px 0px 4px 0px;">

    <div class="d-flex justify-content-around">
        <div id="billTabletList" style="width:49%;" class="d-flex flex-wrap justify-content-start">
            @if (count($allBillTRes) == 0)
                <p style="width: 100%; font-size:1.3rem; color:rgb(72,81,87);" class="text-center"><strong>Es sind noch keine Tablets für die Rechnungen registriert!</strong></p>
            @else
                @foreach ($allBillTRes as $oneBT)
                    <div style="width:33%; height:fit-content; margin:0 0.166% 7px 0.166%; border:1px solid rgb(72,81,87); border-radius:5px;" class="p-2 d-flex flex-wrap justify-content-between">
                        <p style="width: 100%;" class="text-center mb-1"><strong>{{$oneBT->nameTitle}}</strong></p>

                        <button style="width: 33%;" class="btn btn-outline-dark shadow-none" onclick="sendForDelete('{{$oneBT->id}}')"><strong></strong><i class="fa-solid fa-trash"></i></button>
                        <button style="width: 33%;" class="btn btn-outline-dark shadow-none" onclick="openEditTablet('{{$oneBT->id}}','{{$oneBT->nameTitle}}')"><strong></strong><i class="fa-solid fa-pen-to-square"></i></button>
                        <button style="width: 33%;" class="btn btn-outline-dark shadow-none" onclick="showQrCode('{{$oneBT->qrCodeImg}}')"><strong></strong><i class="fa-solid fa-qrcode"></i></button>
                    </div>
                @endforeach
            @endif
        </div>
        <div id="billTabletListTa" style="width:49%;" class="d-flex flex-wrap justify-content-start">
            @if (count($allBillTTa) == 0)
                <p style="width: 100%; font-size:1.3rem; color:rgb(72,81,87);" class="text-center"><strong>Es sind noch keine Tablets für die Rechnungen registriert!</strong></p>
            @else
                @foreach ($allBillTTa as $oneBTTA)
                    <div style="width:33%; height:fit-content; margin:0 0.166% 7px 0.166%; border:1px solid rgb(72,81,87); border-radius:5px;" class="p-2 d-flex flex-wrap justify-content-between">
                        <p style="width: 100%;" class="text-center mb-1"><strong>{{$oneBTTA->nameTitle}}</strong></p>
                        <p style="width: 100%; font-size:0.7rem;" class="text-center mb-1"><strong>{{$oneBTTA->toStaffName}}</strong></p>

                        <button style="width: 33%;" class="btn btn-outline-dark shadow-none" onclick="sendForDelete('{{$oneBTTA->id}}')"><strong></strong><i class="fa-solid fa-trash"></i></button>
                        <button style="width: 33%;" class="btn btn-outline-dark shadow-none" onclick="openEditTabletTA('{{$oneBTTA->id}}','{{$oneBTTA->nameTitle}}','{{$oneBTTA->toStaffId}}')"><strong></strong><i class="fa-solid fa-pen-to-square"></i></button>
                        <button style="width: 33%;" class="btn btn-outline-dark shadow-none" onclick="showQrCode('{{$oneBTTA->qrCodeImg}}')"><strong></strong><i class="fa-solid fa-qrcode"></i></button>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
   
</div>






<!-- Register Bill Tablet Modal -->
<div class="modal" id="RegBillTabletModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrieren Sie ein neues Tablet "RESTAURANT"</h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Titel/Name des Tablets</span>
                    </div>
                    <input type="text" class="form-control shadow-none" id="bTablNameInp" placeholder="Titel/Name">
                </div>
                <button style="width: 100%;" class="btn btn-success shadow-none" onclick="saveBillTablet()"><strong>Spare</strong></button>

                <div class="alert alert-danger text-center mt-1" style="display: none;" id="RegBillTabletErr01">
                    <strong>Schreiben Sie den Titel/Namen des Tablets</strong>
                </div>
                <div class="alert alert-success text-center mt-1" style="display: none;" id="RegBillTabletScc01">
                    <strong>Tablet erfolgreich registriert!</strong>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Register Bill Tablet TAKEAWAY Modal -->
<div class="modal" id="RegBillTabletModalTA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrieren Sie ein neues Tablet "TAKEAWAY"</h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body d-flex flex-wrap justify-content-around">
                <div style="width:100%;" class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Titel/Name des Tablets</span>
                    </div>
                    <input type="text" class="form-control shadow-none" id="bTablNameInpTA" placeholder="Titel/Name">
                </div>
                @foreach (User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $staffOne)
                    <button id="RegBillTabletTAStaffSelBtn{{$staffOne->id}}" style="width: 49%;" class="btn btn-outline-dark shadow-none mb-1" onclick="selectStaffForTabletTA('{{$staffOne->id}}')">
                        <strong>{{$staffOne->id}}# - {{$staffOne->name}}<br>{{$staffOne->email}}</strong>
                    </button>
                @endforeach
                @foreach (User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $staffOne)
                    <button id="RegBillTabletTAStaffSelBtn{{$staffOne->id}}" style="width: 49%;" class="btn btn-outline-dark shadow-none mb-1" onclick="selectStaffForTabletTA('{{$staffOne->id}}')">
                        <strong>{{$staffOne->id}}# - {{$staffOne->name}}<br>{{$staffOne->email}}</strong>
                    </button>
                @endforeach
                <input type="hidden" value="0" id="RegBillTabletTAStaffSel">
                <hr style="width: 100%;">
                <button style="width: 100%;" class="btn btn-success shadow-none" onclick="saveBillTabletTA()"><strong>Spare</strong></button>

                <div class="alert alert-danger text-center mt-1" style="display: none; width: 100%;" id="RegBillTabletErrTA01">
                    <strong>Schreiben Sie den Titel/Namen des Tablets</strong>
                </div>
                <div class="alert alert-danger text-center mt-1" style="display: none; width: 100%;" id="RegBillTabletErrTA02">
                    <strong>Bitte wählen Sie das mit diesem Tablet verknüpfte Konto aus</strong>
                </div>
                <div class="alert alert-success text-center mt-1" style="display: none; width: 100%;" id="RegBillTabletSccTA01">
                    <strong>Tablet erfolgreich registriert!</strong>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- edit tablet Modal -->
<div class="modal" id="editTabletModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Bearbeiten Sie dieses Tablet!</strong></h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close" onclick="resetEditTabletModal()">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body d-flex flex-wrap justify-content-between">
                <div style="width:100%;" class="input-group mb-1">
                    <input type="text" class="form-control shadow-none" id="editTabletCrrName" value="---">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary shadow-none" type="button" onclick="saveTabletEdit()">Spare</button>
                    </div>
                </div>

                <div class="alert alert-danger text-center mt-1" style="display: none;" id="EditTabletErr01">
                    <strong>Schreiben Sie den Titel/Namen des Tablets</strong>
                </div>
            </div>
            <input type="hidden" id="btIdToEdit" value="0">
        </div>
    </div>
</div>

<!-- edit tablet Takeaway Modal -->
<div class="modal" id="editTabletModalTA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Bearbeiten Sie dieses Tablet!</strong></h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close" onclick="resetEditTabletModalTA()">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body d-flex flex-wrap justify-content-between">
                <div style="width:100%;" class="input-group mb-1">
                    <input type="text" class="form-control shadow-none" id="editTabletCrrNameTA" value="---">
                </div>
                @foreach (User::where([['sFor',Auth::user()->sFor],['role','5']])->get() as $staffOne)
                    <button id="RegBillTabletTAStaffSelBtnTA{{$staffOne->id}}" style="width: 49%;" class="btn btn-outline-dark shadow-none mb-1" onclick="selectStaffForEditTabletTA('{{$staffOne->id}}')">
                        <strong>{{$staffOne->id}}# - {{$staffOne->name}}<br>{{$staffOne->email}}</strong>
                    </button>
                @endforeach
                @foreach (User::where([['sFor',Auth::user()->sFor],['role','55']])->get() as $staffOne)
                    <button id="RegBillTabletTAStaffSelBtnTA{{$staffOne->id}}" style="width: 49%;" class="btn btn-outline-dark shadow-none mb-1" onclick="selectStaffForEditTabletTA('{{$staffOne->id}}')">
                        <strong>{{$staffOne->id}}# - {{$staffOne->name}}<br>{{$staffOne->email}}</strong>
                    </button>
                @endforeach
                <button style="width:100%;" class="btn btn-success shadow-none" type="button" onclick="saveTabletEditTA()">Spare</button>

                <div class="alert alert-danger text-center mt-1" style="display: none;" id="EditTabletErrTA01">
                    <strong>Schreiben Sie den Titel/Namen des Tablets</strong>
                </div>
                <div class="alert alert-danger text-center mt-1" style="display: none; width: 100%;" id="EditTabletErrTA02">
                    <strong>Bitte wählen Sie das mit diesem Tablet verknüpfte Konto aus</strong>
                </div>
            </div>
            <input type="hidden" id="btIdToEditTA" value="0">
            <input type="hidden" id="btStaffIdToEditTA" value="0">
        </div>
    </div>
</div>


<!-- delete confirmation Modal -->
<div class="modal" id="deleteConfModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Sind Sie sicher, dass Sie dieses Tablet löschen möchten?</strong></h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close" onclick="resetDeleteConfModal()">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body d-flex flex-wrap justify-content-between">
                <button style="width:49.5%" class="btn btn-outline-dark shadow-none" data-dismiss="modal"><strong>Nein</strong></button>
                <button style="width:49.5%" class="btn btn-danger shadow-none" onclick="deleteTB()"><strong>Ja</strong></button>

                <div class="alert alert-danger text-center mt-1" style="display: none;" id="deleteConfErr01">
                    <strong>Etwas stimmt nicht, schließen Sie dieses Fenster und versuchen Sie es erneut!</strong>
                </div>
            </div>
            <input type="hidden" id="btIdTodel" value="0">
        </div>
    </div>
</div>


<!-- show QR Code Modal -->
<div class="modal" id="TablQrCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
           
            <div class="modal-body d-flex flex-wrap justify-content-between">
                <img id="tablQrCodeSrc" style="width: 100%; height:auto;" src="storage/gifs/loading2.gif" alt="">

                <p style="width:100%;" class="text-center"><strong>Scannen Sie diesen QR-Code, um das Tablet für die Rechnungen zu aktivieren</strong></p>
                <button style="width:100%" class="btn btn-outline-dark text-center shadow-none" onclick="resetTablQrCodeModal()" data-dismiss="modal"><strong><i class="fa-solid fa-xmark"></i></strong></button>
            </div>
   
        </div>
    </div>
</div>











<script>
    function saveBillTablet(){
        if(!$('#bTablNameInp').val()){
            if($('#RegBillTabletErr01').is(':hidden')){ $('#RegBillTabletErr01').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
				url: '{{ route("billTablet.saveTablet") }}',
				method: 'post',
				data: {
					bTName: $('#bTablNameInp').val(),
                    bTType: 'Res',
					_token: '{{csrf_token()}}'
				},
				success: () => {
					$("#billTabletList").load(location.href+" #billTabletList>*","");
                    $('#bTablNameInp').val('');
                    if($('#RegBillTabletScc01').is(':hidden')){ $('#RegBillTabletScc01').show(50).delay(4000).hide(50); }
				},
				error: (error) => { console.log(error); }
			});	
        }
    }



    function resetDeleteConfModal(){
        $('#btIdTodel').val(0);
    }
    function sendForDelete(btId){
        $('#deleteConfModal').modal('show');
        $('#btIdTodel').val(btId);
    }
    function deleteTB(){
        if($('#btIdTodel').val() == 0){
            if($('#deleteConfErr01').is(':hidden')){ $('#deleteConfErr01').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
				url: '{{ route("billTablet.deleteTablet") }}',
				method: 'post',
				data: {
					bTId: $('#btIdTodel').val(),
					_token: '{{csrf_token()}}'
				},
				success: (respo) => {
                    respo = $.trim(respo);
                    if(respo == 'Success'){
                        $("#billTabletList").load(location.href+" #billTabletList>*","");
                        $("#billTabletListTa").load(location.href+" #billTabletListTa>*","");
                        $('#deleteConfModal').modal('hide');
                    }else{
                        if($('#deleteConfErr01').is(':hidden')){ $('#deleteConfErr01').show(50).delay(4000).hide(50); }
                    }
				},
				error: (error) => { console.log(error); }
			});	
        }
    }


    function openEditTablet(btId, btName){
        $("#editTabletModal").modal('show');
        $('#btIdToEdit').val(btId);
        $('#editTabletCrrName').val(btName);
    }
    function saveTabletEdit(){
        if(!$('#editTabletCrrName').val()){
            if($('#EditTabletErr01').is(':hidden')){ $('#EditTabletErr01').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
                url: '{{ route("billTablet.editTablet") }}',
                method: 'post',
                data: {
                    btId: $('#btIdToEdit').val(),
                    btNewName: $('#editTabletCrrName').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#editTabletModal").modal('hide');
                    $("#billTabletList").load(location.href+" #billTabletList>*","");
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    function showQrCode(btQCPic){
        $('#tablQrCodeSrc').attr('src','storage/billTabletQrCode/'+btQCPic),
        $('#TablQrCodeModal').modal('show');
    }
    function resetTablQrCodeModal(){
        $('#TablQrCodeModal').modal('hide');
        $('#tablQrCodeSrc').attr('src','storage/gifs/loading2.gif');
    }








    function selectStaffForTabletTA(staffId){
        if(staffId == $('#RegBillTabletTAStaffSel').val()){
            $('#RegBillTabletTAStaffSelBtn'+$('#RegBillTabletTAStaffSel').val()).removeClass('btn-dark');
            $('#RegBillTabletTAStaffSelBtn'+$('#RegBillTabletTAStaffSel').val()).addClass('btn-outline-dark');
        }else{
            if($('#RegBillTabletTAStaffSel').val() != 0){
                $('#RegBillTabletTAStaffSelBtn'+$('#RegBillTabletTAStaffSel').val()).removeClass('btn-dark');
                $('#RegBillTabletTAStaffSelBtn'+$('#RegBillTabletTAStaffSel').val()).addClass('btn-outline-dark');
            }
            $('#RegBillTabletTAStaffSelBtn'+staffId).removeClass('btn-outline-dark');
            $('#RegBillTabletTAStaffSelBtn'+staffId).addClass('btn-dark');
            $('#RegBillTabletTAStaffSel').val(staffId);
        }
    }
    function saveBillTabletTA(){
        if(!$('#bTablNameInpTA').val()){
            if($('#RegBillTabletErrTA01').is(':hidden')){ $('#RegBillTabletErrTA01').show(50).delay(4000).hide(50); }
        }else if($('#RegBillTabletTAStaffSel').val() == 0){
            if($('#RegBillTabletErrTA02').is(':hidden')){ $('#RegBillTabletErrTA02').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
				url: '{{ route("billTablet.saveTablet") }}',
				method: 'post',
				data: {
					bTName: $('#bTablNameInpTA').val(),
                    stafSelId: $('#RegBillTabletTAStaffSel').val(),
                    bTType: 'Ta',
					_token: '{{csrf_token()}}'
				},
				success: () => {
					$("#billTabletListTa").load(location.href+" #billTabletListTa>*","");
                    $('#bTablNameInpTA').val('');
                    $('#RegBillTabletTAStaffSelBtn'+$('#RegBillTabletTAStaffSel').val()).removeClass('btn-dark');
                    $('#RegBillTabletTAStaffSelBtn'+$('#RegBillTabletTAStaffSel').val()).addClass('btn-outline-dark');
                    $('#RegBillTabletTAStaffSel').val(0); 
                    if($('#RegBillTabletSccTA01').is(':hidden')){ $('#RegBillTabletSccTA01').show(50).delay(4000).hide(50); }
				},
				error: (error) => { console.log(error); }
			});	
        }
    }

    function openEditTabletTA(btId, btName, btStaffId){
        $("#editTabletModalTA").modal('show');
        $('#btIdToEditTA').val(btId);
        $('#editTabletCrrNameTA').val(btName);
        $('#RegBillTabletTAStaffSelBtnTA'+btStaffId).removeClass('btn-outline-dark');
        $('#RegBillTabletTAStaffSelBtnTA'+btStaffId).addClass('btn-dark');
        $('#btStaffIdToEditTA').val(btStaffId);
    }

    function resetEditTabletModalTA(){
        $('#btIdToEditTA').val(0);
        $('#editTabletCrrNameTA').val('');
        $('#RegBillTabletTAStaffSelBtnTA'+$('#btStaffIdToEditTA').val()).removeClass('btn-dark');
        $('#RegBillTabletTAStaffSelBtnTA'+$('#btStaffIdToEditTA').val()).addClass('btn-outline-dark');
        $('#btStaffIdToEditTA').val(0);
    }

    function selectStaffForEditTabletTA(staffId){
        if(staffId == $('#btStaffIdToEditTA').val()){
            $('#RegBillTabletTAStaffSelBtnTA'+$('#btStaffIdToEditTA').val()).removeClass('btn-dark');
            $('#RegBillTabletTAStaffSelBtnTA'+$('#btStaffIdToEditTA').val()).addClass('btn-outline-dark');
        }else{
            if($('#btStaffIdToEditTA').val() != 0){
                $('#RegBillTabletTAStaffSelBtnTA'+$('#btStaffIdToEditTA').val()).removeClass('btn-dark');
                $('#RegBillTabletTAStaffSelBtnTA'+$('#btStaffIdToEditTA').val()).addClass('btn-outline-dark');
            }
            $('#RegBillTabletTAStaffSelBtnTA'+staffId).removeClass('btn-outline-dark');
            $('#RegBillTabletTAStaffSelBtnTA'+staffId).addClass('btn-dark');
            $('#btStaffIdToEditTA').val(staffId);
        }
    }



    function saveTabletEditTA(){
        if(!$('#editTabletCrrNameTA').val()){
            if($('#EditTabletErrTA01').is(':hidden')){ $('#EditTabletErrTA01').show(50).delay(4000).hide(50); }
        }else if($('#btStaffIdToEditTA').val() == 0){
            if($('#EditTabletErrTA02').is(':hidden')){ $('#EditTabletErrTA02').show(50).delay(4000).hide(50); }
        }else{
            $.ajax({
                url: '{{ route("billTablet.editTabletTA") }}',
                method: 'post',
                data: {
                    btId: $('#btIdToEditTA').val(),
                    btNewName: $('#editTabletCrrNameTA').val(),
                    btNewStaffId: $('#btStaffIdToEditTA').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#editTabletModalTA").modal('hide');
                    $("#billTabletListTa").load(location.href+" #billTabletListTa>*","");

                    resetEditTabletModalTA();
                },
                error: (error) => { console.log(error); }
            });
        }
    }
</script>