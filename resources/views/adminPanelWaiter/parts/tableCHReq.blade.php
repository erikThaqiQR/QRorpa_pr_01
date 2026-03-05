<?php

    use Illuminate\Support\Facades\Auth;
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Tabellenwechsel']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\TableChngReq;
?>

<section class="pr-4 pl-4 pt-2 pb-5">
    <p style="font-size:20px; color:rgb(39,190,175);" ><strong>{{__('adminP.changeRequestTable')}}</strong></p>
    <hr>

    <div class="d-flex flex-wrap justify-content-between" id="allTCRRecords">
        <p style="width:15%;" class="text-center"><strong>{{__('adminP.time')}}</strong></p>
        <p style="width:25%;" class="text-center"><strong>{{__('adminP.tableTransfer')}}</strong></p>
        <p style="width:35%;" class="text-center"><strong>{{__('adminP.comment')}}</strong></p>
        <p style="width:25%;" class="text-center"><strong>{{__('adminP.status')}}</strong></p>
        @foreach(TableChngReq::where('toRes',Auth::user()->sFor)->get()->sortByDesc('created_at') as $tRe)
            <?php 
                $da2d = explode(' ',$tRe->created_at); 
                $da2dDay = explode('-', $da2d[0]); 
                $da2dTime = explode(':', $da2d[1]); 
            
            ?>

            <p style="width:15%; color:rgb(72,81,87); border-top:1px solid rgb(72,81,87); font-size:18px;" class="text-center">
                {{$da2dTime[0]}} : {{$da2dTime[1]}} <br>
                {{$da2dDay[2]}} / {{$da2dDay[1]}} / {{$da2dDay[0]}}
            </p>
            <p style="width:25%; color:rgb(72,81,87); border-top:1px solid rgb(72,81,87); font-size:24px; font-weight:bold;" class="text-center pt-2">
                T: {{$tRe->crrTable}} => {{$tRe->newTable}}
            </p>

            <p style="width:35%; color:rgb(72,81,87); border-top:1px solid rgb(72,81,87); font-size:18px;" class="text-center">
                @if($tRe->komenti != 'empty')
                    @if($tRe->komenti == 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable')
                        erzwungener Tabellenwechsel
                    @else
                        {{$tRe->komenti}}
                    @endif
                @endif
            </p>
           
            <div style="width:25%; border-top:1px solid rgb(72,81,87);">
                @if($tRe->status == 0)
                    <button style="width:47.5%; font-weight:bold;" class="btn btn-danger mt-2" onclick="setTCRStatus('{{$tRe->id}}','1')">{{__('adminP.refused')}}</button>
                    <button style="width:47.5%; font-weight:bold;" class="btn btn-success mt-2" onclick="setTCRStatus('{{$tRe->id}}','5')">{{__('adminP.authorize')}}</button>
                @elseif($tRe->status == 1)
                    <button style="width:95%; font-weight:bold;" class="btn btn-danger mt-2" disabled>{{__('adminP.refusedS')}}</button>
                @elseif($tRe->status == 5)
                    <button style="width:95%; font-weight:bold;" class="btn btn-success mt-2" disabled>{{__('adminP.authorize')}}</button>
                @endif
            </div>
        @endforeach
    </div>
</section>


<script>
    function setTCRStatus(trID,newV){
        $.ajax({
			url: '{{ route("TabChngCli.stat01") }}',
			method: 'post',
			data: {
                id: trID,
                val: newV,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                location.reload();
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }
</script>