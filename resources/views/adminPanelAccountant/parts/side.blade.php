<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    use App\accessControllForAdmins;
    use App\Orders;
    use App\Waiter;
    use App\TabOrder;
    use App\TableChngReq;
    use App\TableReservation;
    use App\admMsgSaavchats;
    use Carbon\Carbon;
    

    $theR = Auth::user()->sFor;

?>

<img style="width:12%;" class="mt-4 pl-4" src="/storage/images/logo_QRorpa_wh.png" alt="">


<style>
    .sideIconAP{
        padding: 5px;
    }
    .newSideElAdmin{
        margin-left:-45px;
    }
    .newSideElAdmin:hover{
        text-decoration: none;
    }



    .glow {
        color: #fff;
        -webkit-animation: glow 1s ease-in-out infinite alternate;
        -moz-animation: glow 1s ease-in-out infinite alternate;
        animation: glow 1s ease-in-out infinite alternate;
    }

    @-webkit-keyframes glow {
        from {
            text-shadow: 0 0 10px #fff, 0 0 12px #fff, 0 0 14px #e60073, 0 0 16px #e60073, 0 0 18px #e60073, 0 0 20px #e60073, 0 0 22px #e60073;
        }
        to {
            text-shadow: 0 0 12px #fff, 0 0 14px #ff4da6, 0 0 16px #ff4da6, 0 0 18px #ff4da6, 0 0 20px #ff4da6, 0 0 22px #ff4da6, 0 0 24px #ff4da6;
        }
    }
</style>

<?php
    $countONotDone = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
    ->where('statusi','0')->whereIn('nrTable',$myTablesWaiter)->count();
    $TabNotFinish = TabOrder::where([['toRes',Auth::user()->sFor],['status',0]])->whereIn('tableNr',$myTablesWaiter)->count();


    $countWNotDone = Waiter::whereDate('created_at', Carbon::today())->where([['toRes','=',Auth::user()->sFor],['status','=','0']])->count();

    $countTableCHReq = TableChngReq::where([['toRes',Auth::user()->sFor],['status',0]])->count();

    $todayPlus12Date = Carbon::now();
    $todayPlus12Date->addDays(12);

    $countTableReservationReq = TableReservation::where([['toRes', Auth::user()->sFor],['status',0]])->whereDate('dita', '>=', Carbon::today())->whereDate('dita', '<', $todayPlus12Date)->get()->count() ;
  
    
    $usersAccess = accessControllForAdmins::where([['forRes',Auth::user()->sFor],['userId',Auth::user()->id]])->get();
?>






<div id="sideExtButtons" style="margin-top:25px;">
    @if(Auth::user()->role == 53 )

        @if($usersAccess->where('accessDsc','Statistiken')->first() != NULL && $usersAccess->where('accessDsc','Statistiken')->first()->accessValid == 1)
        <a href="{{route('admWoMng.AccountantStatistics')}}" class="newSideElAdmin">
            @if(Request::is('AccountantStatistics') || Request::is('AccountantStatisticsDash1') || Request::is('AccountantStatisticsDash2') || Request::is('AccountantStatisticsSales') 
            || Request::is('AccountantStatisticsRepCat') || Request::is('AccountantCanceledOrders') || Request::is('AccountantstatsBillsRecs') || Request::is('AccountantstatsDeletedIns')
            || Request::is('AccountantstatsWaitersSales') || Request::is('AccountantstatsEmailBillsP') || Request::is('AccountantstatsReportCatsPage'))
                <img class="sideIconAP" src="/storage/images/APstatistika02.PNG" alt=""> 
                <span class="glow" style="color:white;"><strong>{{__('adminP.statistics')}}</strong></span>
            @else
                <img class="sideIconAP" src="/storage/images/APstatistika01.PNG" alt=""> 
                <span style="color:white;"><strong>{{__('adminP.statistics')}}</strong></span>
            @endif
        </a>
        <br>
        @endif
      
        @if($usersAccess->where('accessDsc','Products')->first() != NULL && $usersAccess->where('accessDsc','Products')->first()->accessValid == 1)
        <a class="newSideElAdmin" href="{{route('admWoMng.AccountantProducts')}}">
            @if(Request::is('AccountantProducts') || Request::is('AccountantProducts/Order'))
                <img class="sideIconAP " src="/storage/images/productMng02.png" alt="">
                <span class="glow" style="color:white;"><strong>{{__('adminP.products')}}</strong></span>
            @else
                <img class="sideIconAP" src="/storage/images/productMng01.png" alt="">
                <span style="color:white;"><strong>{{__('adminP.products')}}</strong></span>
            @endif
        </a>
        <br>
        @endif
   
        
    
    @endif
</div>