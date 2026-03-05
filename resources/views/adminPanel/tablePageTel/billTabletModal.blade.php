<?php

use App\billTabletsReg;
use Illuminate\Support\Facades\Auth;
    use App\billTabletsCrrStat;

    $allBillT = billTabletsReg::where([['toRes',Auth::user()->sFor],['tabletBillType','Res']])->get();
?>
<style>
    .clickable:hover{
        cursor: pointer;
    }
</style>
<!-- bill tablets Modal -->
<div class="modal" id="billTabletsChooseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Wählen Sie ein Wartetablett, um die Rechnung anzuzeigen</strong></h5>
                <button type="button" class="close" onclick="closeBillTabletsChooseModal()">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body d-flex flex-wrap justify-content-start" id="BillTabletStatDiv">
                @if (count($allBillT) == 0)
                    <p style="width: 100%; font-size:1.3rem; color:rgb(72,81,87);" class="text-center"><strong>Es sind noch keine Tablets registriert!</strong></p>
                @else
                    @foreach ($allBillT as $oneBT)
                        <?php
                            $billStat = billTabletsCrrStat::where([['toRes',Auth::user()->sFor],['billTbltId',$oneBT->id]])->first();
                        ?>
                        @if( $billStat == Null || $billStat->statOfTbl == 0)
                            <div style="width:33%; margin:0 0.15% 7px 0.15%; border:1px solid rgb(72,81,87); border-radius:5px;" class="p-2 d-flex flex-wrap justify-content-between">
                                <p style="font-size:1.1rem; width: 100%;" class="text-center mb-1"><strong>{{$oneBT->nameTitle}}</strong></p>
                                <p style="font-size:1.1rem; width: 100%; color:red;" class="text-center "><strong>Nicht aktiv</strong></p>
                            </div>        
                        @elseif( $billStat->statOfTbl == 1 )
                            <div style="width:33%; margin:0 0.15% 7px 0.15%; border:1px solid rgb(72,81,87); border-radius:5px;" class="p-2 d-flex flex-wrap justify-content-between clickable" onclick="sendBillToTabletWaiting('{{$billStat->id}}')">
                                <p style="font-size:1.1rem; width: 100%;" class="text-center mb-1"><strong>{{$oneBT->nameTitle}}</strong></p>
                                <p style="font-size:1.1rem; width: 100%; color:blue;" class="text-center "><strong>Warten...</strong></p>
                            </div>
                        @elseif( $billStat->statOfTbl == 2 )
                            <div style="width:33%; margin:0 0.15% 7px 0.15%; border:1px solid rgb(72,81,87); border-radius:5px;" class="p-2 d-flex flex-wrap justify-content-between clickable"  onclick="sendBillToTabletRechAct('{{$billStat->id}}')">
                                <p style="font-size:1.1rem; width: 100%;" class="text-center mb-1"><strong>{{$oneBT->nameTitle}}</strong></p>
                                <p style="font-size:1.1rem; width: 100%; color:green;" class="text-center "><strong>Rechnung T.Nr:{{$billStat->forTableNr}}</strong></p>
                            </div>
                        @endif
                           
                    
                    @endforeach
                @endif

            
            </div>
            <div class="flex-wrap justify-content-between" id="overwriteBillDiv" style="display:none;">
                <p style="font-size:1.1rem; width: 100%;" class="text-center mb-1"><strong>Möchten Sie die auf diesem Tablet angezeigte aktuelle Rechnung überschreiben?</strong></p>
                <button style="width:49%; margin:5px 0.5% 5px 0.5%;" onclick="closeOverwriteBillDiv()" class="btn btn-danger shadow-none">Nein</button>
                <button style="width:49%; margin:5px 0.5% 5px 0.5%;" id="overwriteBillDivJa" class="btn btn-dark shadow-none">Ja</button>
            </div>


          
            <input type="hidden" id="bTablBillType" value="0">
            <input type="hidden" id="bTablTableNr" value="0">
        </div>
    </div>
</div>

<script>
    function openBillTabletsChoose(tableNr){
        $('#tabOrder'+tableNr).modal('hide');

        $('#bTablBillType').val('all');
        $('#bTablTableNr').val(tableNr);

        $("#BillTabletStatDiv").load(location.href+" #BillTabletStatDiv>*","");	
    }

    function sendBillToTabletWaiting(btStatId){

        $.ajax({
			url: '{{ route("billTablet.sendBillToTabletWaiting") }}',
			method: 'post',
			data: {
				btStatid: btStatId,
                tblNr: $('#bTablTableNr').val(),
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
                respo = $.trim(respo);
				// $("#freeProElements").load(location.href+" #freeProElements>*","");
                // console.log(respo);
                $('#billTabletsChooseModal').modal('hide');
                $('#tabOrder'+$('#bTablTableNr').val()).modal('show');

                $('#overwriteBillDiv').attr('style','display:none;');
                $('#overwriteBillDivJa').removeAttr('onclick');
			},
			error: (error) => { console.log(error); }
		});
    }

    function closeOverwriteBillDiv(){
        $('#overwriteBillDiv').attr('style','display:none;');
        $('#overwriteBillDivJa').removeAttr('onclick');
    }

    function sendBillToTabletRechAct(btStatId){
        $('#overwriteBillDiv').attr('style','display:flex;');
        $('#overwriteBillDivJa').attr('onclick',' sendBillToTabletWaiting('+btStatId+')');
    }

    function closeBillTabletsChooseModal(){
        $('#billTabletsChooseModal').modal('hide');
        $('body').addClass('modal-open');
        $('#tabOrder'+$('#bTablTableNr').val()).modal('show');

    }

    var intervalId = window.setInterval(function(){
        if($('#billTabletsChooseModal').hasClass('show')){
            $("#BillTabletStatDiv").load(location.href+" #BillTabletStatDiv>*","");
            // console.log('refresh');
        }
	}, 1500);
</script>