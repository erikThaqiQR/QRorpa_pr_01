<?php

    use Illuminate\Support\Facades\Auth;
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Statusarbeiter']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\StatusWorker;
    use App\User;
    use Carbon\Carbon;

    $thisRestaurantId = Auth::user()->sFor;
?>
<style>
        .backQr{
            background-color:white;
            color:rgb(39,190,175);
            border:1px solid rgb(39,190,175);
            font-weight: bold;
            font-size: 18px;
        }
        .backQr:hover{
            background-color:rgb(39,190,175);
            color:white;
            font-weight: bold;
            font-size: 18px;
        }
</style>
<section class="pr-2 pl-2 pb-4">
    <button class="btn btn-block p-2 backQr" style="border-radius:20px;" data-toggle="modal" data-target="#addStWorkerTel">
        {{__('adminP.addNewEmployee')}}
    </button>



    <div class="d-flex flex-wrap justify-content-between mt-3 mr-4 ml-4">
        <p style="width:50%; border-bottom:1px solid lightgray;"><strong>{{__('adminP.name')}}</strong></p>
        <p style="width:25%; border-bottom:1px solid lightgray;"></p>
        <p style="width:25%; border-bottom:1px solid lightgray;"></p>

        @foreach(StatusWorker::where('toRes', $thisRestaurantId)->get()->sortByDesc('created_at') as $sWor)
            <div style="width:40%" >
                <p class="delSW{{$sWor->id}}">{{$sWor->emri}}</p>
            </div>
            <div style="width:30%" class="text-center">
                <button style="width:90%" class="delSW{{$sWor->id}} btn btn-block btn-outline-info" data-toggle="modal" data-target="#editStWorkerTel{{$sWor->id}}"><strong>{{__('adminP.toEdit')}}</strong></button>
            </div>
            <div style="width:30%" class="text-center">
                <button style="width:90%" onclick="delSW('{{$sWor->id}}')" class="delSW{{$sWor->id}} btn btn-block btn-outline-danger"><strong>{{__('adminP.extinguish')}}</strong></button>
            </div>
        @endforeach
    </div>




    <!-- The Modal -->
    <div class="modal" id="addStWorkerTel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{__('adminP.addNewEmployee')}}</h4>
            </div>

            {{Form::open(['action' => 'StatusWorkerController@store', 'method' => 'post']) }}
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::label(__('adminP.name'), null, ['class' => 'control-label']) }}
                        {{ Form::text('emri','', ['class' => 'form-control']) }}
                    </div>

                    {{ Form::hidden('toRes',$thisRestaurantId, ['class' => 'form-control']) }}
                </div>

                {{ Form::hidden('isWaiter','1', ['class' => 'form-control']) }}

                <!-- Modal footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <button style="width:46%;" type="button" class="btn btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                   
                        {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'btn btn-outline-success', 'style' => 'width:46%;']) }}
                   
                </div> 
            {{Form::close() }}

            </div>
        </div>
    </div>


















    @foreach(StatusWorker::where('toRes', $thisRestaurantId)->get()->sortByDesc('created_at') as $sWor)

        <!-- The Modal -->
        <div class="modal" id="editStWorkerTel{{$sWor->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{__('adminP.editThisWorker')}}</h4>
                </div>

                {{Form::open(['action' => 'StatusWorkerController@update', 'method' => 'post']) }}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            {{ Form::label(__('adminP.name'), null, ['class' => 'control-label']) }}
                            {{ Form::text('emri', $sWor->emri , ['class' => 'form-control']) }}
                        </div>

                        {{ Form::hidden('toRes',$thisRestaurantId, ['class' => 'form-control']) }}
                        {{ Form::hidden('updateWorId',$sWor->id, ['class' => 'form-control']) }}

                        {{ Form::hidden('isWaiter','1', ['class' => 'form-control']) }}
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer d-flex justify-content-between">
                        <button style="width:46%;" type="button" class="btn btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                    
                            {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'btn btn-outline-success', 'style' => 'width:46%;']) }}
                    
                    </div> 
                {{Form::close() }}

                </div>
            </div>
        </div>
    @endforeach












    <script>
        function delSW(swId){
            $.ajax({
                url: '{{ route("statusWorker.del") }}',
                method: 'delete',
                data: {delId: swId, _token: '{{csrf_token()}}'},
                success: (response) => {
                    // $('.tableRec').load('/recomendet .tableRec', function() {
                    // });
                    // location.reload();
                    $('.delSW'+swId).hide();
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            })
        }
    </script>


</section>