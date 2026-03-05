<?php

    use Illuminate\Support\Facades\Auth;
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Aufträge']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage'));
        exit();
    }
    use App\Restorant;
    use App\TableQrcode;

    $theResId = Auth::user()->sFor;
?>
<style>
    .pointTable{
        cursor:pointer;
    }
    .pointTableNot{
        cursor:not-allowed;
    }
</style>

<section class="pb-5 pr-4 pl-4 pt-2">
    <div class="d-flex justify-content-between">
        <h1 style="width:65%;" class="color-qrorpa"><strong>{{__('adminP.tableStatus')}}</strong></h1>
        <div style="width:3%; height:25px; background-color:red; border-radius:7px;"></div> <p style="width:5%;"><strong>{{__('adminP.occupied')}}</strong></p>
        <div style="width:3%; height:25px; background-color:rgb(39,190,175); border-radius:7px;"></div> <p style="width:5%;"><strong>{{__('adminP.free')}}</strong></p>
        <div style="width:3%; height:25px; background-color:rgb(255,0,0,0.5); border-radius:7px;" disabled></div> <p style="width:10%;"><strong>{{__('adminP.guestAtTheTable')}}</strong></p>
    </div>


    <div class="d-flex justify-content-start flex-wrap" id="tableStatDiv">
        @foreach(TableQrcode::where('Restaurant',$theResId)->whereIn('tableNr',$myTablesWaiter)->get()->sortBy('tableNr') as $tabelOne)
            <div style="width:4%" class="ml-4">
                @if($tabelOne->kaTab == 0)
                    <img onclick="setTabStat('{{$tabelOne->id}}','-1')" style="width:100%;" class="pointTable" src="storage/images/tableSt_qrorpa.PNG" alt="NotFound">
                @elseif($tabelOne->kaTab == -1)
                    <img onclick="setTabStat('{{$tabelOne->id}}','0')" style="width:100%;" class="pointTable" src="storage/images/tableSt_red.PNG" alt="NotFound">
                @else
                    <img style="width:100%;" src="storage/images/tableSt_red05.PNG" class="pointTableNot" alt="NotFound" disabled>
                @endif
                <p class="text-center color-qrorpa" style="margin-top:-5px; font-size:19px;"><strong>#{{$tabelOne->tableNr}}</strong> </p>
            </div>
        @endforeach
    
    </div>

</section>


<script>
    function setTabStat(tId,newVal){
        $.ajax({
			url: '{{ route("table.tableStatusSet") }}',
			method: 'post',
			data: {
				id: tId,
				val: newVal,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#tableStatDiv").load(location.href+" #tableStatDiv>*","");
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }
</script>

