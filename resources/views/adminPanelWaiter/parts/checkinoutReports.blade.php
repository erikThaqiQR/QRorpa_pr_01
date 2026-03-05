<?php

use App\checkInOutReg;

    
?>
<div class="pb-4">

    <hr class="mt-1 mb-1">
    <p class="pl-3" style="font-weight: bold; font-size: 1.3rem; color:rgb(39,190,175);">Check-in/out Berichte</p>
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