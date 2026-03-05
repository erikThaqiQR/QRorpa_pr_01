<?php
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

<section class="pb-5 pt-2">
    <div class="d-flex flex-wrap justify-content-between">
        <h1 style="width:100%;" class="color-qrorpa"><strong>{{__('adminP.tableStatus')}}</strong></h1>
        <div style="width:10%; height:25px; background-color:red; border-radius:7px;"></div> <p style="width:20%;"><strong>{{__('adminP.occupied')}}</strong></p>
        <div style="width:10%; height:25px; background-color:rgb(39,190,175); border-radius:7px;"></div> <p style="width:16%;"><strong>{{__('adminP.free')}}</strong></p>
        <div style="width:10%; height:25px; background-color:rgb(255,0,0,0.5); border-radius:7px;" disabled></div> <p style="width:33%;"><strong>{{__('adminP.guestAtTheTable')}}</strong></p>
    </div>


    <div class="d-flex justify-content-start flex-wrap" id="tableStatDivTel">
        @foreach(TableQrcode::where('Restaurant',$theResId)->get()->sortBy('tableNr') as $tabelOne)
            <div style="width:17.5%" class="ml-2 allTablesTel">
                @if($tabelOne->kaTab == 0)
                    <img onclick="setTabStatTel('{{$tabelOne->id}}','-1')" style="width:100%;" class="pointTable" src="storage/images/tableSt_qrorpa.PNG" alt="NotFound">
                    <p onclick="setTabStatTel('{{$tabelOne->id}}','-1')" class="text-center color-black" style="margin-top:-55px; margin-bottom:50px; font-size:19px;"><strong>{{$tabelOne->tableNr}}</strong> </p>
                @elseif($tabelOne->kaTab == -1)
                    <img onclick="setTabStatTel('{{$tabelOne->id}}','0')" style="width:100%;" class="pointTable" src="storage/images/tableSt_red.PNG" alt="NotFound">
                    <p onclick="setTabStatTel('{{$tabelOne->id}}','0')" class="text-center color-black" style="margin-top:-55px; margin-bottom:50px; font-size:19px;"><strong>{{$tabelOne->tableNr}}</strong> </p>
                @else
                    <img style="width:100%;" src="storage/images/tableSt_red05.PNG" class="pointTableNot" alt="NotFound" disabled>
                    <p class="text-center color-black" style="margin-top:-55px; margin-bottom:50px; font-size:19px;"><strong>{{$tabelOne->tableNr}}</strong> </p>
                @endif
              
            </div>
        @endforeach
    
    </div>

</section>


<script>
    function setTabStatTel(tId,newVal){
        $.ajax({
			url: '{{ route("table.tableStatusSet") }}',
			method: 'post',
			data: {
				id: tId,
				val: newVal,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#tableStatDivTel").load(location.href+" #tableStatDivTel>*","");
			},
			error: (error) => {
				console.log(error);
				alert($('$pleaseUpdateAndTryAgain').val());
			}
		});
    }
</script>

