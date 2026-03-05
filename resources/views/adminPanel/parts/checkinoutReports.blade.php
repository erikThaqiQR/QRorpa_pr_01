<?php

use App\checkInOutReg;
use App\User;
  
?>
<style>
    .clickable:hover{
        cursor: pointer;
    }
</style>
<div class="pb-4">

    <hr class="mt-1 mb-1">
    <div class="d-flex justify-content-between">
        <p class="pl-3" style="font-weight: bold; font-size: 1.3rem; color:rgb(39,190,175); width:50%;">Check-in/out Berichte</p>
        <button class="mr-3 ml-3 btn btn-outline-dark shadow-none" style="width:50%;" data-toggle="modal" data-target="#waitersCheckin"><strong>Kellner checken ein/aus</strong></button>
    </div>
   
    <div class="p-3 d-flex flex-wrap justify-content-start" id="checkInOutRepDiv">
        @foreach (checkInOutReg::where([['userId',Auth::user()->id],['theStat',1]])->orderByDesc('updated_at')->get() as $chInOutOne)
            <?php
                 $dtIN2D = explode('-',explode(' ',$chInOutOne->checkIn)[0]);
                 $hrIN2D = explode(':',explode(' ',$chInOutOne->checkIn)[1]);
                 $dtOUT2D = explode('-',explode(' ',$chInOutOne->checkOut)[0]);
                 $hrOUT2D = explode(':',explode(' ',$chInOutOne->checkOut)[1]);
            ?>

            <div style="width:24.5%; border:1px solid black; margin-right:0.25%; margin-left:0.25%;" class="d-flex flex-wrap mb-1" >
                <p class="text-center mb-1" style="width:50%; font-size:1.2rem; font-weight:bold;">{{$dtIN2D[2].'.'.$dtIN2D[1].'.'.$dtIN2D[0]}} </p>
                <p class="text-center mb-1" style="width:50%; font-size:1.2rem; font-weight:bold;">{{$hrIN2D[0].':'.$hrIN2D[1]}} </p>
                <p class="text-center mb-1" style="width:100%; font-size:1.2rem; font-weight:bold;">bis</p>
                <p class="text-center mb-1" style="width:50%; font-size:1.2rem; font-weight:bold;">{{$dtOUT2D[2].'.'.$dtOUT2D[1].'.'.$dtOUT2D[0]}} </p>
                <p class="text-center mb-1" style="width:50%; font-size:1.2rem; font-weight:bold;">{{$hrOUT2D[0].':'.$hrOUT2D[1]}} </p>
                <button class="btn btn-outline-info" style="width:100%;" onclick="openSalesRep('{{Auth::user()->id}}','{{$chInOutOne->id}}')"><strong>Verkaufsbericht</strong></button>
            </div>
        @endforeach
    </div>

</div>

<!-- waiters checkin/out Modal -->
<div class="modal" id="waitersCheckin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Kellner checken ein/aus</strong></h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap justify-content-start">
                    <p style="width: 100%; font-weight:bold; font-size:1.3rem; margin-bottom:10px;"><strong>Klicken Sie auf einen Kellner, um dessen Instanzen anzuzeigen</strong></p>
                    @foreach (User::where([['role',55],['sFor',Auth::user()->sFor]])->get() as $waOne)
                        <div style="width:33%; border:1px solid black; border-radius:10px; margin-right:0.16%; margin-left:0.16%;" class="d-flex flex-wrap mb-1 clickable" onclick="showWaChinOut('{{Auth::user()->id}}','{{$waOne->id}}','{{$waOne->name}}')">
                            <p style="width: 100%; font-weight:bold; font-size:1.1rem; margin-bottom:3px;" class="text-center">{{$waOne->name}}</p>
                            <p style="width: 100%; font-weight:bold; font-size:1.1rem; margin-bottom:3px;" class="text-center">{{$waOne->email}}</p>
                            <p style="width: 100%; font-weight:bold; font-size:1.1rem; margin-bottom:3px;" class="text-center">{{checkInOutReg::where([['userId',$waOne->id],['theStat',1]])->count()}} Ein-/Auschecken</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


<!-- checkIn Sales Report Modal -->
<div class="modal" id="checkInSalesRep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Verkaufsbericht</strong></h5>
                <button type="button" class="close shadow-none" data-dismiss="modal" aria-label="Close" onclick="resetCheckInSalesRep()">
                    <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="salesRepModalTbl2">
                    <tbody>
                        <tr>
                            <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Bar</span></strong></td>
                            <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showCashDirect">-</span>CHF</strong></td>
                        </tr>
                        <tr>
                            <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Abzüglich Trinkgeld</span></strong></td>
                            <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showCashAfterTipDirect">-</span>CHF</strong></td>
                        </tr>
                        <tr>
                            <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">POS/Karte</span></strong></td>
                            <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showCardDirect">-</span>CHF</strong></td>
                        </tr>
                        <tr>
                            <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Online</span></strong></td>
                            <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showOnlineDirect">-</span>CHF</strong></td>
                        </tr>
                        <tr>
                            <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Auf Rechnung</span></strong></td>
                            <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showRechnungDirect">-</span>CHF</strong></td>
                        </tr>
                        <tr>
                            <td><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;">Geschenkkarte</span></strong></td>
                            <td style="text-align:right !important;"><strong><span class="ml-1 mr-1" style="font-size: 1.1rem;" id="showGiftcardDiscAll">-</span>CHF</strong></td>
                        </tr>

                        <tr>
                            <td colspan="4" class="text-center" style="font-size: 1.2rem;"><strong> Gesamtumsatz : <span class="ml-1 mr-1" id="totalInCHF2">-</span> CHF</strong></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td style="font-size:1.2rem;"><strong>Bestellungen bestätigt</strong></td>
                            <td style="font-size:1.2rem;" class="text-center" id="orderClosedNr2"> --- </td>
                        </tr>
                        <tr>
                            <td style="font-size:1.2rem;"><strong>Bestellungen bestätigt</strong></td>
                            <td style="font-size:1.2rem;" class="text-center" id="orderClosedChf2"> --- </td>
                        </tr>
                        <tr>
                            <td style="font-size:1.2rem;"><strong>Geschenkkartenverkauf</strong></td>
                            <td style="font-size:1.2rem;" class="text-center" id="totalGCSalesCHF"> --- </td>
                        </tr>
                        <tr>
                            <td style="font-size:1.2rem;"><strong>Trinkgeld registriert</strong></td>
                            <td style="font-size:1.2rem;" class="text-center" id="bakshish12"> --- </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script>
    function showWaChinOut(admId,waId,waName){
        $('#waitersCheckin').modal('hide');
        var addTo = '';
        $.ajax({
			url: '{{ route("chkInOut.getWaCheckInOutIns") }}',
			method: 'post',
            dataType: 'json',
			data: {
				waiterId: waId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                $('#checkInOutRepDiv').html('');
                if(admId != waId){
                    $('#checkInOutRepDiv').append('<button class="btn shadow-none" style="width:15%; font-size:1.4rem; padding:0px; margin-bottom:10px;" onclick="showWaChinOut(\''+admId+'\',\''+admId+'\',\'empty\')"><i class="fa-regular fa-circle-left"></i> zeig meins</button>');
                    $('#checkInOutRepDiv').append('<p style="width:85%; font-weight:bold; font-size:1.4rem; margin-bottom:10px;" class="text-center">Check-in- und Check-out-Instanzen für: '+waName+'</p>');
                }
                $.each(respo, function(index, value){
                    var dtIn = value.checkIn;
                    var dtOut = value.checkOut;

                    var dtIn2D = dtIn.split(' ')[0];
                    var dtIn2D = dtIn2D.split('-');
                    var hrIn2D = dtIn.split(' ')[1];
                    var hrIn2D = hrIn2D.split(':');

                    var dtOut2D = dtOut.split(' ')[0];
                    var dtOut2D = dtOut2D.split('-');
                    var hrOut2D = dtOut.split(' ')[1];
                    var hrOut2D = hrOut2D.split(':');

                    addTo = '<div style="width:24.5%; border:1px solid black; margin-right:0.25%; margin-left:0.25%;" class="d-flex flex-wrap mb-1" >'+
                                '<p class="text-center mb-1" style="width:50%; font-size:1.2rem; font-weight:bold;">'+dtIn2D[2]+'.'+dtIn2D[1]+'.'+dtIn2D[0]+'</p>'+
                                '<p class="text-center mb-1" style="width:50%; font-size:1.2rem; font-weight:bold;">'+hrIn2D[0]+':'+hrIn2D[1]+'</p>'+
                                '<p class="text-center mb-1" style="width:100%; font-size:1.2rem; font-weight:bold;">bis</p>'+
                                '<p class="text-center mb-1" style="width:50%; font-size:1.2rem; font-weight:bold;">'+dtOut2D[2]+'.'+dtOut2D[1]+'.'+dtOut2D[0]+'</p>'+
                                '<p class="text-center mb-1" style="width:50%; font-size:1.2rem; font-weight:bold;">'+hrOut2D[0]+':'+hrOut2D[1]+'</p>'+
                                '<button class="btn btn-outline-info" style="width:100%;" onclick="openSalesRep(\''+waId+'\',\''+value.id+'\')"><strong>Verkaufsbericht</strong></button>'+
                            '</div>';

                    $('#checkInOutRepDiv').append(addTo);

                });// end foreach
			},
			error: (error) => {
				console.log(error);
				alert('bitte aktualisieren und erneut versuchen!');
			}
		});	
    }

    function openSalesRep(woId, chInId){
        $('#checkInSalesRep').modal('show');

        $.ajax({
			url: '{{ route("chkInOut.getCheckInOutSalesRepo") }}',
			method: 'post',
			data: {
				workerId: woId,
				chInId: chInId,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
                respo2D = respo.split('|||');

                $('#showCashDirect').html(parseFloat(parseFloat(respo2D[0]) + parseFloat(respo2D[9])).toFixed(2));
                $('#showCashAfterTipDirect').html(parseFloat(parseFloat(respo2D[0]) + parseFloat(respo2D[9]) - parseFloat(respo2D[6])).toFixed(2));
                $('#showCardDirect').html(parseFloat(parseFloat(respo2D[1]) + parseFloat(respo2D[10])).toFixed(2));
                $('#showOnlineDirect').html(parseFloat(parseFloat(respo2D[2]) + parseFloat(respo2D[11])).toFixed(2));
                $('#showRechnungDirect').html(parseFloat(parseFloat(respo2D[3]) + parseFloat(respo2D[12])).toFixed(2));
                $('#showGiftcardDiscAll').html(parseFloat(respo2D[8]).toFixed(2));
                $('#totalInCHF2').html( parseFloat(parseFloat(respo2D[5]) + parseFloat(respo2D[7]) + parseFloat(respo2D[8]) - parseFloat(respo2D[6])).toFixed(2) );

                $('#orderClosedNr2').html(respo2D[4]);
                $('#orderClosedChf2').html(parseFloat(parseFloat(respo2D[5]) + parseFloat(respo2D[8]) - parseFloat(respo2D[6])).toFixed(2)+' CHF');
                $('#totalGCSalesCHF').html(parseFloat(respo2D[7]).toFixed(2)+' CHF');
                $('#bakshish12').html(parseFloat(respo2D[6]).toFixed(2)+' CHF');
			},
			error: (error) => { console.log(error); }
		});
    }

    function resetCheckInSalesRep(){
        $('#showCashDirect').html('-');
        $('#showCashAfterTipDirect').html('-');
        $('#showCardDirect').html('-');
        $('#showOnlineDirect').html('-');
        $('#showRechnungDirect').html('-');
        $('#showGiftcardDiscAll').html('-');
        $('#totalInCHF2').html('-');

        $('#orderClosedNr2').html('---');
        $('#orderClosedChf2').html('---');
        $('#totalGCSalesCHF').html('---');
        $('#bakshish12').html('---');	
    }
</script>