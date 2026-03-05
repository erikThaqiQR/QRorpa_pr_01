<?php

use App\waiterDaySales;
    use App\accessControllForAdmins;
use App\billsRecordRes;
use App\Restorant;
use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Statistiken']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('home')); 
        exit();
    }

    $thisRestaurantId = Restorant::find(Auth::user()->sFor);
?>
 <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
 @include('fontawesome')

<div class="p-1 pb-4">

    <?php  
        $month = Carbon::now(); 
        
        $monthCount = Carbon::now()->month;
        $yearCount = Carbon::now()->year;

        $waCreated = explode(' ',$thisRestaurantId->created_at)[0];
        $waCreatedM = explode('-', $waCreated)[1];
        $rwaCreatedY = explode('-', $waCreated)[0];

        $billRecords = billsRecordRes::where('forRes',Auth::user()->sFor)->get();
    ?>
    <div style="width: 100%;" class="d-flex flex-wrap justify-content-start p-1">
        @while(true)
            @if(($monthCount >= $waCreatedM && $yearCount == $rwaCreatedY) || $yearCount > $rwaCreatedY )
                <div style="width: 33%; margin-right:0.33%" class="d-flex flex-wrap justify-content-between p-1">
                    <p style="width:100%; font-size:1.3rem; margin:0px; border-top:1px solid rgb(72,81,87);" class="text-center pt-1"><strong>
                        <?php
                            switch($monthCount){
                                case 1: echo  __('adminP.jan'). " . ".$yearCount.""; break;
                                case 2: echo  __('adminP.feb'). " . ".$yearCount.""; break;
                                case 3: echo  __('adminP.march'). " . ".$yearCount.""; break;
                                case 4: echo  __('adminP.apr'). " . ".$yearCount.""; break;
                                case 5: echo __('adminP.May'). " . ".$yearCount.""; break;
                                case 6: echo __('adminP.june'). " . ".$yearCount.""; break;
                                case 7: echo __('adminP.july'). " . ".$yearCount.""; break;
                                case 8: echo __('adminP.aug'). " . ".$yearCount.""; break;
                                case 9: echo __('adminP.sept'). " . ".$yearCount.""; break;
                                case 10: echo __('adminP.oct'). " . ".$yearCount.""; break;
                                case 11: echo __('adminP.nov'). " . ".$yearCount.""; break;
                                case 12: echo __('adminP.dec'). " . ".$yearCount.""; break;   
                            }
                            $month = new Carbon($yearCount.'-'.$monthCount.'-01');
                        ?>
                    </strong></p>
                    <div style="width:100%;" class="d-flex flex-wrap justify-content-start mb-2">
                        <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Mo</button>
                        <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Di</button>
                        <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Mi</button>
                        <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Do</button>
                        <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Fr</button>
                        <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>Sa</button>
                        <button class="btn mb-1 btn-default" style="width:14.1%; margin-right:0.18%;" disabled>So</button>
                        @for($i=1;$i<=$month->daysInMonth;$i++)
                            <?php
                                if($i < 10){ $d= '0'.$i; }else{ $d= $i; }
                                $dateCheckCreate = new Carbon($yearCount.'-'.$monthCount.'-'.$d);

                                $billRecord = $billRecords->where('docForDate',$dateCheckCreate)->first();
                            ?>
                            @if($i == 1)
                                <?php
                                    $dayOfWeekNr = date('w', strtotime($dateCheckCreate));
                                    if($dayOfWeekNr == 0){ $dayOfWeekNr = 7; }
                                    $j = 1;
                                ?>
                                @while ($j < $dayOfWeekNr)
                                <button class="btn mb-1 btn-default shadow-none" style="width:14.1%; margin-right:0.18%;" disabled></button>
                                <?php $j++; ?>
                                @endwhile
                            
                                @if($dateCheckCreate >= $thisRestaurantId->created_at && $dateCheckCreate <= Carbon::now())
                                    <button class="btn mb-1 {{$billRecord == Null ? 'btn-outline-danger' : 'btn-success'}} shadow-none" 
                                    style="width:14.1%; margin-right:0.18%;" data-toggle="modal" data-target="#billsModal" onclick="openBillModal('{{$dateCheckCreate}}','{{Auth::user()->sFor}}')">
                                        <strong>{{$i}}</strong>
                                    </button>
                                @else
                                    <button class="btn mb-1 btn-outline-dark shadow-none" style="width:14.1%; margin-right:0.18%;" disabled><s>{{$i}}</s></button>
                                @endif
                            @else
                                @if($dateCheckCreate >= $thisRestaurantId->created_at && $dateCheckCreate <= Carbon::now() )
                                    <button class="btn mb-1 {{$billRecord == Null ? 'btn-outline-danger' : 'btn-success'}} shadow-none" 
                                    style="width:14.1%; margin-right:0.18%;" data-toggle="modal" data-target="#billsModal" onclick="openBillModal('{{$dateCheckCreate}}','{{Auth::user()->sFor}}')">
                                        <strong>{{$i}}</strong>
                                    </button>
                                @else
                                    <button class="btn mb-1 btn-outline-dark shadow-none" style="width:14.1%; margin-right:0.18%;" disabled><s>{{$i}}</s></button>
                                @endif
                            @endif
                        @endfor
                        <?php
                            $addExtra = 42 - $dayOfWeekNr + 1 - $month->daysInMonth;
                        ?>
                        @for($k=1;$k<=$addExtra;$k++)
                            <button class="btn mb-1 btn-default shadow-none" style="width:14.1%; margin-right:0.18%; color:white;" disabled>.</button>
                        @endfor

                    </div>
                </div>
                <!-- Pjesa per vitin  -->
                @if($monthCount == 1)
                    <?php
                        $yearCount--;
                        $monthCount=12;
                    ?>
                @else
                    <?php
                        $monthCount--;
                    ?>
                @endif
            @else
                @break;
            @endif 
        @endwhile
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="billsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="billsModalHeader" style="font-size: 1.5rem;">---</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetbillsModal()">
                    <span aria-hidden="true"><i style="color:black;" class="fa-regular fa-2x fa-circle-xmark"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admWoMng.statBillsSave') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="saveBillRes" id="saveBillRes" value="{{Auth::user()->sFor}}">
                    <input type="hidden" name="saveBillStaf" id="saveBillStaf" value="{{Auth::user()->id}}">
                    <input type="hidden" name="saveBillDate" id="saveBillDate" value="0">
                    <div class="form-group">
                        <label for="exampleFormControlFile1"><strong>Rechnungen hochladen</strong></label>
                        <input name="billsToSave[]" type="file" multiple="multiple" class="form-control-file" id="exampleFormControlFile1" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" style="margin:0px;"><strong>Rechnungen speichern</strong></button>

                </form>
                <hr>
                <p style="font-size: 1.4rem;"><strong>Registrierte Rechnungen</strong></p>
                <div class="d-flex flex-wrap justify-content-start" id="billsModalDocs">
                   
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function resetbillsModal(){
        $('#billsModalDocs').html('');
    }

    function openBillModal(dt,res){
        $('#billsModalDocs').html('<img src="storage/gifs/loading.gif" style="width: 70%; height:auto; margin-left:15%;" alt="">');
        var dt2 = dt.split(' ')[0];
        var dt2d = dt2.split('-');
        $('#billsModalHeader').html('<strong>Rechnungen für: '+dt2d[2]+'.'+dt2d[1]+'.'+dt2d[0]+'</strong>');
        $('#saveBillDate').val(dt);

        $.ajax({
			url: '{{ route("admWoMng.statBillsGetDocs") }}',
			method: 'post',
			data: {
				resId: res,
                theDt: dt,
				_token: '{{csrf_token()}}'
			},
			success: (respo) => {
				respo = $.trim(respo);
                if(respo != 'zero'){
                    $('#billsModalDocs').html('');
                    var respo2D = respo.split('-4-4-');
                    $.each(respo2D, function(i, obj){
                        obj2D = obj.split('-3-3-');
                        var name2d = obj2D[0].split('.');
                        var docExt = name2d[name2d.length-1];
                        docExt = docExt.toLowerCase();

                        if(docExt == 'png' || docExt == 'jpeg' || docExt == 'jpg' || docExt == 'gif'){
                            $('#billsModalDocs').append('<div style="width: 33%; margin-right:0.33%; border:1px solid rgb(72,81,87); border-radius:6px; padding:3px;" class="d-flex flex-wrap mb-3">'+
                                                            '<div class="text-center" style="width: 100%; height:150px;">'+
                                                                '<img src="storage/billsAusgabenFiles/'+obj2D[0]+'" style="max-width: 100%; max-height:100%;" alt="">'+
                                                            '</div>'+
                                                            '<div style="width:80%;">'+
                                                                '<p style="margin-bottom:0px;">Von : <strong>'+obj2D[1]+'</strong></p>'+
                                                                '<p style="margin-bottom:0px;">Datum : <strong>'+obj2D[2]+'</strong></p>'+
                                                            '</div>'+
                                                            '<a style="width: 20%;" href="storage/billsAusgabenFiles/'+obj2D[0]+'" download><i class="fa-solid fa-file-arrow-down fa-2x pt-2"></i></a>'+
                                                        '</div>');
                        }else if(docExt == 'pdf'){
                            $('#billsModalDocs').append('<div style="width: 33%; margin-right:0.33%; border:1px solid rgb(72,81,87); border-radius:6px; padding:3px;" class="d-flex flex-wrap mb-3">'+
                                                            '<div class="text-center" style="width: 100%; height:150px;">'+
                                                                '<i style="color:red;" class="fa-regular fa-file-pdf fa-6x pt-3"></i>'+
                                                            '</div>'+
                                                            '<div style="width:80%;">'+
                                                                '<p style="margin-bottom:0px;">Von : <strong>'+obj2D[1]+'</strong></p>'+
                                                                '<p style="margin-bottom:0px;">Datum : <strong>'+obj2D[2]+'</strong></p>'+
                                                            '</div>'+
                                                            '<a style="width: 20%; right:5px;" href="storage/billsAusgabenFiles/'+obj2D[0]+'" download><i class="fa-solid fa-file-arrow-down fa-2x pt-2"></i></a>'+
                                                        '</div>');
                        }else{
                            $('#billsModalDocs').append('<div style="width: 33%; margin-right:0.33%; border:1px solid rgb(72,81,87); border-radius:6px; padding:3px;" class="d-flex flex-wrap mb-3">'+
                                                            '<div class="text-center" style="width: 100%; height:150px;">'+
                                                                '<i class="fa-regular fa-file fa-6x pt-3"></i>'+
                                                            '</div>'+
                                                            '<div style="width:80%;">'+
                                                                '<p style="margin-bottom:0px;">Von : <strong>'+obj2D[1]+'</strong></p>'+
                                                                '<p style="margin-bottom:0px;">Datum : <strong>'+obj2D[2]+'</strong></p>'+
                                                            '</div>'+
                                                            '<a style="width: 20%; right:5px;" href="storage/billsAusgabenFiles/'+obj2D[0]+'" download><i class="fa-solid fa-file-arrow-down fa-2x pt-2"></i></a>'+
                                                        '</div>');
                        }
                        
                    });
                }else{
                    $('#billsModalDocs').html('<p style="font-size: 1.1rem;"><strong>Für dieses Datum gibt es keine registrierten Rechnungen.</strong></p>');
                }
			},
			error: (error) => { console.log(error); }
		});
    }
</script>