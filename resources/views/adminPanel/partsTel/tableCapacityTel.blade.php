<?php
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Tischkapazität']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }

    use App\TableQrcode;
    $theResId = Auth::user()->sFor;
?>
@include('words')
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

textarea:focus, input:focus{
    outline: none;
}

.clickable:hover{
    cursor: pointer;
}
</style>
<section class="ml-1 mr-1 mt-1 mb-5">
    <div class="d-flex flex-wrap">
        <h3 style="width:100%;" class="color-qrorpa mb-1 text-left"><strong> {{__('adminP.tableCapacity')}}</strong> </h3>
        <div  style="width:100%; font-weight:bold; font-size:16px; display:none; padding-top:5px; padding-bottom:0px;"
            class="text-center alert alert-success" id="successTableCapacityTel">
        </div>
    </div>
  
    <div class="container-fluid pr-1 pl-1">
        <div id="tableCapRezTel" class="d-flex flex-wrap justify-content-between">
            @foreach(TableQrcode::where('Restaurant',Auth::user()->sFor)->get()->sortBy('tableNr') as $table)
                @if($table->forRez == 0)
                    <div class="p-2 mb-4 d-flex flex-wrap" style="width:32%; border-radius:5px; background-color:rgb(255, 128, 128);">
                @else
                    <div class="p-2 mb-4 d-flex flex-wrap" style="width:32%; border-radius:5px; background-color:lightgray;">
                @endif
                    <div style="width:40%;" class="clickable" >
                    @if($table->tableNr >= 100)
                        <span style="font-size:14px;" class="text-center"><strong># {{$table->tableNr}}</strong></span> 
                    @else
                        <span style="font-size:17px;" class="text-center"><strong># {{$table->tableNr}}</strong></span> 
                    @endif
                    </div>
                    <div style="width:60%;" class="pl-1">
                        <input onkeyup="SaveCapacityTel('{{$theResId}}','{{$table->tableNr}}',this.value)" type="number" step="1" min="0"
                         style="width:60%; border:none; border-radius:10px; font-weight:bold;" class="text-center" value="{{$table->capacity}}" >
                        <i class="fas fa-users"></i>
                    </div>
                    @if($table->forRez == 1)
                        <button onclick="deactiveTableTel('{{$theResId}}','{{$table->tableNr}}')" style="width:100%; border:none; border-radius:5px; font-size:13px;"
                        class="btn-danger p-1 mt-3">{{__('adminP.deactivate')}}</button>
                    @else
                        <button onclick="deactiveTableTel('{{$theResId}}','{{$table->tableNr}}')" style="width:100%; border:none; border-radius:5px; font-size:13px;"
                        class="btn-success p-1 mt-3">{{__('adminP.activate')}}</button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
   


    <script>
        function SaveCapacityTel(res,ta,val){
            // alert(res+'/'+ta+'/'+val);
            if(val == ''){
                val = 0;
            }
            $.ajax({
				url: '{{ route("table.capacitySave") }}',
				method: 'post',
				data: {
                    res: res,
                    table: ta,
                    newVal : val,
					_token: '{{csrf_token()}}'
				},
				success: (res) => {
                    res = res.replace(/\s/g, '');
                    var response = res.split("||");
                    if(response[1] != 0){
                        $('#successTableCapacityTel').html('{{__("adminP.tables")}} '+response[0]+' {{__("adminP.isNowFor")}} '+response[1]+' {{__("adminP.suitableForPeople")}}');
                        $('#successTableCapacityTel').show(200).delay(2500).hide(200);
                    }
				},
				error: (error) => { console.log(error); }
			});
        }

        function deactiveTableTel(res,ta){
            $.ajax({
				url: '{{ route("table.rezStatus") }}',
				method: 'post',
				data: {
                    res: res,
                    table: ta,
					_token: '{{csrf_token()}}'
				},
				success: (res) => {
                    res = res.replace(/\s/g, '');
                    $("#tableCapRezTel").load(location.href+" #tableCapRezTel>*","");

                    var response = res.split("||");
                    if(response[1] == 0){
                        $('#successTableCapacityTel').html('{{__("adminP.tables")}} '+response[0]+' {{__("adminP.notActiveForReservationNow")}}');
                        $('#successTableCapacityTel').show(200).delay(2500).hide(200);
                    }else{
                        $('#successTableCapacityTel').html('{{__("adminP.tables")}}  '+response[0]+' {{__("adminP.activeForReservationNow")}}');
                        $('#successTableCapacityTel').show(200).delay(2500).hide(200);
                    }
				},
				error: (error) => { console.log(error); }
			});
        }
    </script>
</section>