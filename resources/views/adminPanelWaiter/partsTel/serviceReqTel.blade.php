<?php

    use Illuminate\Support\Facades\Auth;
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Dienstleistungen']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage'));
        exit();
    }
    use App\ServiceReqCli;
    use App\Restorant;

    $resId = auth::user()->sFor;
    $res = Restorant::find($resId);

    $takeawayNr = ServiceReqCli::where([['tores',$resId],['reqType',1]])->get()->count();
    $deliveryNr = ServiceReqCli::where([['tores',$resId],['reqType',2]])->get()->count();
    $tableRezNr = ServiceReqCli::where([['tores',$resId],['reqType',3]])->get()->count();

?>
<section class="pb-5 pt-1 pl-3 pr-3">
    <h2 class="color-qrorpa"><strong> {{__('adminP.services')}} </strong></h2>

    <div class="d-flex flex-wrap justify-content-between pt-2">

        <div style="width:100%; background-color:rgb(39,190,175,0.9); border-radius:30px;" class="text-center p-1 mb-1">
            @if($res->resType == 2 || $res->resType == 6)
                <h3 style="color:white;"><strong>
                    {{__('adminP.takeaway')}} <span style="color:green; font-size:14px;">({{__('adminP.active')}})</span></strong>
                </h3>
                <p style="color:rgb(277,277,277,0.9); font-size:18px;">{{$takeawayNr}} {{__('adminP.requestsForTakeaway')}}</p>
               
            @else
                <h3 style="color:white;"><strong>
                {{__('adminP.takeaway')}} 
                <span style="color:red; font-size:14px;">({{__('adminP.notActive')}})</span></strong></h3>
                <p style="color:rgb(277,277,277,0.9); font-size:18px;">{{$takeawayNr}} {{__('adminP.requestsForTakeaway')}}</p>
            @endif
        </div>
        <div style="width:100%; background-color:rgb(39,190,175,0.9); border-radius:30px;" class="text-center p-1 mb-1">
            <h3 style="color:white;"><strong>
                {{__('adminP.delivery')}} 
            <span style="color:red; font-size:14px;">({{__('adminP.notActive')}})</span> </strong></h3>

            <p style="color:rgb(277,277,277,0.9); font-size:18px;">{{$deliveryNr}} {{__('adminP.requestsForDelivery')}}</p>
        </div>
        <div style="width:100%; background-color:rgb(39,190,175,0.9); border-radius:30px;" class="text-center p-1 mb-1">
            @if($res->resType == 5 || $res->resType == 6)
                <h3 style="color:white;"><strong>
                    {{__('adminP.tableResrvation')}}
                 <span style="color:green; font-size:14px;">({{__('adminP.active')}})</span></strong></h3>
                 <p style="color:rgb(277,277,277,0.9); font-size:18px;">{{$tableRezNr}} {{__('adminP.tableReservationRequests')}}</p>
            @else
                <h3 style="color:white;"><strong>
                    {{__('adminP.tableResrvation')}} 
                <span style="color:red; font-size:14px;">({{__('adminP.notActive')}})</span></strong></h3>
                <p style="color:rgb(277,277,277,0.9); font-size:18px;">{{$tableRezNr}} {{__('adminP.tableReservationRequests')}}</p>
            @endif
        </div>

    </div>
    <hr>



    <div class="d-flex flex-wrap justify-content-between" >
        @foreach(ServiceReqCli::where('tores',$resId)->get()->sortByDesc('created_at') as $sReq)
            <div style="width:100%; border:1px solid rgb(39,190,175,0.5); border-radius:5px; font-size:19px;"
             class="p-2 pl-1 pr-1 mb-2 d-flex flex-wrap justify-content-between">
                <span style="width:49%;">#{{$sReq->id}}</span>
                <span style="width:49%;" class="text-right"><strong>
                    @if($sReq->reqType == 1)
                        {{__('adminP.takeaway')}}
                    @elseif($sReq->reqType == 2)
                        {{__('adminP.delivery')}}
                    @elseif($sReq->reqType == 3)
                        {{__('adminP.tableResrvation')}}
                    @endif
                    </strong>
                </span>

                <p> - {{$sReq->comm}} - </p>
            </div>
        @endforeach
    </div>
</section>