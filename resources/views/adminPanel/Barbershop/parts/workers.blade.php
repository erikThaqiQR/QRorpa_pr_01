<?php
    use App\BarbershopWorker;
    use App\BarbershopWorkerTerminet;
    use App\BarbershopWorkerDays;

    use App\BarbershopCategory;

    use App\WorkerCategoryDone;
    $barbershopId = Auth::user()->sFor;
?>
<style>
  .addWorkerBtn{
        color:rgb(39, 190, 175);
        border:1px solid rgb(39, 190, 175);
        font-weight: bold;
        font-size: 17px;
    }
    .addWorkerBtn:hover{
        background-color:rgb(39, 190, 175);
        color: white;
        border:1px solid rgb(39, 190, 175);
        cursor: pointer;
    }

    .btn-qrorpa{
        background-color: rgb(39, 190, 175);
        color: white;
        font-weight: bold;
    }
    .btn-qrorpa:hover{
        background-color: white;
        color: rgb(39, 190, 175);
        font-weight: bold;
        border:1px solid rgb(39, 190, 175);
    }

    .btn-outline-qrorpa2{
        background-color: white;
        color: rgb(39, 190, 175);
        font-weight: bold;
        border:1px solid rgb(39, 190, 175);
    }
    .btn-qrorpa2{
        background-color: rgb(39, 190, 175);
        color: white;
        font-weight: bold;
    }
</style>






<!-- Add Worker Modal -->
<div class="modal" id="addWorker" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> {{__('adminP.registerEmployee')}}</h4>
        <button type="button" class="close" data-dismiss="modal">X</button>
      </div>

            {{Form::open(['action' => 'BarbershopAdminController@addWorker', 'method' => 'post']) }}

                {{csrf_field()}}
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label(__('adminP.name'), null, ['class' => 'control-label color-black']) }}
                        {{ Form::text('emri','', ['class' => 'form-control ', 'required']) }}
                    </div>

                    <div class="form-group">
                        <label for="sel1">{{__('adminP.weekDay')}} :</label>
                        <select class="form-control" name="dayOfWeek">
                            <option value="d1"><strong>{{__('adminP.monday')}}</strong></option>
                            <option value="d2"><strong>{{__('adminP.tuesday')}}</strong></option>
                            <option value="d3"><strong>{{__('adminP.wednesday')}}</strong></option>
                            <option value="d4"><strong>{{__('adminP.thursday')}}</strong></option>
                            <option value="d5"><strong>{{__('adminP.friday')}}</strong></option>
                            <option value="d6"><strong>{{__('adminP.saturday')}}</strong></option>
                            <option value="d0"><strong>{{__('adminP.sunday')}}</strong></option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div style="width:49%;" class="form-group">
                            {{ Form::label(__('adminP.startOfWork'), null, ['class' => 'control-label color-black']) }}
                            {{ Form::time('startW','', ['class' => 'form-control ', 'min' => '00:01', 'max' => '23:59', 'required']) }}
                        </div>
                        <div style="width:49%;" class="form-group">
                            {{ Form::label({{__('adminP.workStop')}}, null, ['class' => 'control-label color-black']) }}
                            {{ Form::time('stopW','', ['class' => 'form-control ', 'min' => '00:01', 'max' => '23:59', 'required']) }}
                        </div>
                    </div>

                    <h5 class="text-center"><strong>{{__('adminP.afterBreak')}}</strong></h5>

                    <div class="d-flex justify-content-between">
                        <div style="width:49%;" class="form-group">
                            {{ Form::label({{__('adminP.startOfWork')}}, null, ['class' => 'control-label color-black']) }}
                            {{ Form::time('startW2','', ['class' => 'form-control ', 'min' => '00:00', 'max' => '23:59']) }}
                        </div>
                        <div style="width:49%;" class="form-group">
                            {{ Form::label(__('adminP.workStop'), null, ['class' => 'control-label color-black']) }}
                            {{ Form::time('stopW2','', ['class' => 'form-control ', 'min' => '00:00', 'max' => '23:59']) }}
                        </div>
                    </div>
                </div>

                {{ Form::hidden('barbershop', $barbershopId , ['class' => 'form-control ']) }}

                <!-- Modal footer -->
                <div class="modal-footer">
                    {{ Form::submit(__('adminP.save'), ['class' => 'form-control btn btn-qrorpa']) }}
                </div>
            {{Form::close() }}

    </div>
  </div>
</div>















<section class="pl-4 pr-4 pb-5" id="allWorkersPage">
    <button class="btn btn-block addWorkerBtn mt-3 mb-3" data-toggle="modal" data-target="#addWorker">{{__('adminP.addNewEmployee')}}</button>


    
    @foreach(BarbershopWorker::where('toBar',$barbershopId)->get()->sortByDesc('created_at') as $worker)
        <div class="d-flex flex-wrap justify-content-between p-3 mb-2" style="border:1px solid lightgray; border-radius:15px;" id="OneWorkersPage{{$worker->id}}">
            <div class="d-flex flex-wrap justify-content-between" style="width:15%; font-size:20px;">
                <p  style="width:100%;" class="text-center"><strong>{{$worker->emri}}</strong></p>
                <p style="margin-top:-20px; width:100%;" id="workingHours{{$worker->id}}" class="text-cenetr"></p>
                <button style="width:49%;" class="btn btn-default fa-lg" disabled><i class="far fa-edit"></i></button>
                <button style="width:49%;" class="btn btn-default fa-lg" onclick="deleteOneWorkerBar('{{$worker->id}}')"><i class="far fa-trash-alt"></i></button>
            </div>
            <div style="width:85%;">
                <div style="width:100%;" class="d-flex justify-content-start mb-1" >
                    @foreach(BarbershopWorkerDays::where('workerId',$worker->id)->get()->sortBy('workerDay') as $wDayActive)
                        <button style="width:12.3%;" class=" btn btn-outline-info mr-1 workerDaySelecterALL{{$worker->id}}" id="workerDaySelecter{{$worker->id}}O{{$wDayActive->workerDay}}"
                            onclick="showWorkersDayTermins('{{$worker->id}}','{{$wDayActive->workerDay}}')"><strong>
                            <?php switch($wDayActive->workerDay){
                                case 'd1': echo __('adminP.monday'); break;
                                case 'd2': echo __('adminP.tuesday'); break;
                                case 'd3': echo __('adminP.wednesday'); break;
                                case 'd4': echo __('adminP.thursday'); break;
                                case 'd5': echo __('adminP.friday'); break;
                                case 'd6': echo __('adminP.saturday'); break;
                                case 'd0': echo __('adminP.sunday'); break;
                            }?>
                        </strong></button>
                    @endforeach
                    <button style="width:12.3%;" class="btn btn-outline-dark" data-toggle="modal" data-target="#addNewWorkingDay{{$worker->id}}">+ {{__('adminP.activeDay')}}</button>
                </div>
                <div style="width:100%;" class="d-flex flex-wrap" id="workingTermins{{$worker->id}}">
                    <!-- Show the termins -->
                    <p style="color: rgb(72,81,87);" class="pt-4"><strong>{{__('adminP.selectWorkDay')}}</strong></p>
                </div>
            </div>           

            <hr style="width:100%;">
            <div style="width:100%;" id="workerCategories{{$worker->id}}">
                @foreach(BarbershopCategory::where('toBar',$barbershopId )->get() as $bCat)
                    @if(WorkerCategoryDone::where([['workerID',$worker->id],['categoryID',$bCat->id]])->first() != NULL)
                        <button style="width:15%;" class="btn btn-qrorpa2 mr-1 mb-1" onclick="setWorkerCategory('{{$worker->id}}','{{$bCat->id}}','{{$barbershopId}}')">
                    @else
                        <button style="width:15%;" class="btn btn-outline-qrorpa2 mr-1 mb-1" onclick="setWorkerCategory('{{$worker->id}}','{{$bCat->id}}','{{$barbershopId}}')">
                    @endif
                        {{$bCat->emri}}
                    </button>
                @endforeach
            </div>

   
        </div>









        <!-- The Modal -->
        <div class="modal" id="addNewWorkingDay{{$worker->id}}"  role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color:rgb(39, 190, 175);">
                        <h4 style="color:white;" class="modal-title"><strong>{{__('adminP.newWorkingDayFor')}}" {{$worker->emri}} "</strong></h4>
                        <button style="color:white;" type="button" class="close" data-dismiss="modal"><strong>X</strong></button>
                    </div>

                    {{Form::open(['action' => 'BarbershopAdminController@addWorker', 'method' => 'post']) }}

                        {{csrf_field()}}
                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="sel1">{{__('adminP.weekDay')}} :</label>
                                <select class="form-control" name="dayOfWeek">
                                    @if(BarbershopWorkerDays::where([['workerId',$worker->id],['workerDay','d1']])->first() == NULL )
                                        <option value="d1"><strong>{{__('adminP.monday')}}</strong></option>
                                    @endif
                                    @if(BarbershopWorkerDays::where([['workerId',$worker->id],['workerDay','d2']])->first() == NULL )
                                        <option value="d2"><strong>{{__('adminP.tuesday')}}</strong></option>
                                    @endif
                                    @if(BarbershopWorkerDays::where([['workerId',$worker->id],['workerDay','d3']])->first() == NULL )
                                        <option value="d3"><strong>{{__('adminP.wednesday')}}</strong></option>
                                    @endif
                                    @if(BarbershopWorkerDays::where([['workerId',$worker->id],['workerDay','d4']])->first() == NULL )
                                        <option value="d4"><strong>{{__('adminP.thursday')}}</strong></option>
                                    @endif 
                                    @if(BarbershopWorkerDays::where([['workerId',$worker->id],['workerDay','d5']])->first() == NULL )
                                        <option value="d5"><strong>{{__('adminP.friday')}}</strong></option>
                                    @endif 
                                    @if(BarbershopWorkerDays::where([['workerId',$worker->id],['workerDay','d6']])->first() == NULL )
                                        <option value="d6"><strong>{{__('adminP.saturday')}}</strong></option>
                                    @endif 
                                    @if(BarbershopWorkerDays::where([['workerId',$worker->id],['workerDay','d0']])->first() == NULL )
                                        <option value="d0"><strong>{{__('adminP.sunday')}}</strong></option>
                                    @endif
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div style="width:49%;" class="form-group">
                                    {{ Form::label(__('adminP.startOfWork'), null, ['class' => 'control-label color-black']) }}
                                    {{ Form::time('startW','', ['class' => 'form-control ', 'min' => '00:01', 'max' => '23:59', 'required']) }}
                                </div>
                                <div style="width:49%;" class="form-group">
                                    {{ Form::label(__('adminP.workStop'), null, ['class' => 'control-label color-black']) }}
                                    {{ Form::time('stopW','', ['class' => 'form-control ', 'min' => '00:01', 'max' => '23:59', 'required']) }}
                                </div>
                            </div>

                            <h5 class="text-center"><strong>{{__('adminP.afterBreak')}}</strong></h5>

                            <div class="d-flex justify-content-between">
                                <div style="width:49%;" class="form-group">
                                    {{ Form::label(__('adminP.startOfWork'), null, ['class' => 'control-label color-black']) }}
                                    {{ Form::time('startW2','', ['class' => 'form-control ', 'min' => '00:00', 'max' => '23:59']) }}
                                </div>
                                <div style="width:49%;" class="form-group">
                                    {{ Form::label(__('adminP.workStop'), null, ['class' => 'control-label color-black']) }}
                                    {{ Form::time('stopW2','', ['class' => 'form-control ', 'min' => '00:00', 'max' => '23:59']) }}
                                </div>
                            </div>
                        </div>

                        {{ Form::hidden('barbershop', $barbershopId , ['class' => 'form-control ']) }}
                        {{ Form::hidden('thisWorkerID', $worker->id , ['class' => 'form-control ']) }}

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            {{ Form::submit(__('adminP.save'), ['class' => 'form-control btn btn-qrorpa']) }}
                        </div>
                    {{Form::close() }}
                </div>
            </div>
        </div>
    @endforeach<!-- End loop for WORKERS -->
</section>











<script>
    function deleteOneWorkerBar(wId){
        $.ajax({
			url: '{{ route("barAdmin.deleteWorker") }}',
			method: 'post',
			data: {
				id: wId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#allWorkersPage").load(location.href+" #allWorkersPage>*","");
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }


    function deleteWorkingTermin(wId,wtId){
        $.ajax({
			url: '{{ route("barAdmin.deleteWorkerTermin") }}',
			method: 'post',
			data: {
				worderID: wId,
				worderTID: wtId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#OneWorkersPage"+wId).load(location.href+" #OneWorkersPage"+wId+">*","");
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }

    function setWorkerCategory(wId, catId, barId){
        $.ajax({
			url: '{{ route("barAdmin.workerCategorySetDel") }}',
			method: 'post',
			data: {
				worderID: wId,
				categoryID: catId,
				barbershopID: barId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#workerCategories"+wId).load(location.href+" #workerCategories"+wId+">*","");
			},
			error: (error) => {
				console.log(error);
				alert($('adminP.pleaseUpdateAndTryAgain').val());
			}
		});
    }


    function chngWorkerDayActivity(wdID, day, wID){
        $.ajax({
			url: '{{ route("barAdmin.workerDayChngStatus") }}',
			method: 'post',
			data: {
				worderDayID: wdID,
				dayNumber: day,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#OneWorkersPage"+wID).load(location.href+" #OneWorkersPage"+wID+">*","");
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }



    function showWorkersDayTermins(wID, wDay){
        $(".workerDaySelecterALL"+wID).removeClass('btn-info');
        $(".workerDaySelecterALL"+wID).addClass('btn-outline-info');
        $("#workerDaySelecter"+wID+"O"+wDay).removeClass('btn-outline-info');
        $("#workerDaySelecter"+wID+"O"+wDay).addClass('btn-info');
        $.ajax({
			url: '{{ route("barAdmin.getWorkerDayTermins") }}',
			method: 'post',
            dataType: 'json',
			data: {
				workerId: wID,
				workerDay: wDay,
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                $('#workingTermins'+wID).html('');
                var listings = "";
                $.each(res, function(index, value){
                    listings = '<button style="width:10.5%;" class="btn btn-outline-success mr-1 mb-1">'+
                                    '<i class="far fa-trash-alt mr-2" onclick="deleteWorkingTermin('+wID+','+value.id+')"></i>'+
                                    '<strong>'+value.startT+'-'+value.endT+'</strong>'+
                                ' </button>';
                    $('#workingTermins'+wID).append(listings); 
                });
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }
</script>