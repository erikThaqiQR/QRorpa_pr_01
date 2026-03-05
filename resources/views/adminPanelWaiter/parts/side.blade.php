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
    use Illuminate\Support\Facades\Auth;
    
    $theR = Auth::user()->sFor;
?>


<style>
    .sideIconAP{
        padding: 3px;
        width: 24px;
        height: 22px;
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

    .card_sideET {
        position: relative;
        background: transparent;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }

    #_sideET {
    position: absolute;
    border-radius: 100vmax;
    }

    .top_sideET {
    top: 0;
    left: 0;
    width: 0;
    height: 10px;
    background: linear-gradient(
        90deg,
        transparent 50%,
        rgba(255, 49, 49, 0.5),
        rgb(255, 49, 49)
    );
    }
    .bottom_sideET {
        right: 0;
        bottom: 0;
        height: 10px;
        background: linear-gradient(
            90deg,
            rgb(57, 255, 20),
            rgba(57, 255, 20, 0.5),
            transparent 50%
        );
    }

    .right_sideET {
        top: 0;
        right: 0;
        width: 10px;
        height: 0;
        background: linear-gradient(
            180deg,
            transparent 30%,
            rgba(0, 255, 255, 0.5),
            rgb(0, 255, 255)
        );
    }

    .left_sideET {
        left: 0;
        bottom: 0;
        width: 10px;
        height: 0;
        background: linear-gradient(
            180deg,
            rgb(255, 255, 113),
            rgba(255, 255, 113, 0.5),
            transparent 70%
        );
    }

    .top_sideET {
    animation: animateTop 3s ease-in-out infinite;
    }

    .bottom_sideET {
    animation: animateBottom 3s ease-in-out infinite;
    }

    .right_sideET {
    animation: animateRight 3s ease-in-out infinite;
    }

    .left_sideET {
    animation: animateLeft 3s ease-in-out infinite;
    }

    @keyframes animateTop {
    25% {
        width: 100%;
        opacity: 1;
    }

    30%,
    100% {
        opacity: 0;
    }
    }

    @keyframes animateBottom {
    0%,
    50% {
        opacity: 0;
        width: 0;
    }

    75% {
        opacity: 1;
        width: 100%;
    }

    76%,
    100% {
        opacity: 0;
    }
    }

    @keyframes animateRight {
    0%,
    25% {
        opacity: 0;
        height: 0;
    }

    50% {
        opacity: 1;
        height: 100%;
    }

    55%,
    100% {
        height: 100%;
        opacity: 0;
    }
    }

    @keyframes animateLeft {
        0%,
        75% {
            opacity: 0;
            bottom: 0;
            height: 0;
        }

        100% {
            opacity: 1;
            height: 100%;
        }
    }
</style>

<script>
    var closedSideBar = 1;
</script>

<?php
    $countONotDone = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
    ->where('statusi','0')->whereIn('nrTable',$myTablesWaiter)->count();
    $countONotDoneJustRes = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())->where('nrTable', '!=','500')->where('nrTable', '!=','9000')
    ->where('statusi','0')->whereIn('nrTable',$myTablesWaiter)->count();

    $TabNotFinish = TabOrder::where([['toRes',Auth::user()->sFor],['status',0]])->whereIn('tableNr',$myTablesWaiter)->count();

    $countWNotDone = Waiter::whereDate('created_at', Carbon::today())->where([['toRes',Auth::user()->sFor],['toWaiter',Auth::user()->id],['status','=','0']])->count();

    $countTableCHReq = TableChngReq::where([['toRes',Auth::user()->sFor],['status',0]])->count();

    $todayPlus12Date = Carbon::now();
    $todayPlus12Date->addDays(12);

    $countTableReservationReq = TableReservation::where([['toRes', Auth::user()->sFor],['status',0]])->whereDate('dita', '>=', Carbon::today())->whereDate('dita', '<', $todayPlus12Date)->get()->count() ;
  
    $usersAccess = accessControllForAdmins::where([['forRes',Auth::user()->sFor],['userId',Auth::user()->id]])->get();
?>


<div class="d-flex" style="width:20%; height:100vmax;">
    <div style="width:15%; height:inherit; background-color:rgb(29, 145, 134);" id="sidePart01">
        <img style="width:100%; height:auto;" class="mt-3 mb-5" src="/storage/images/qrorpaIconWh.png" alt="">
        
        @if(($usersAccess->where('accessDsc','Statistiken')->first() != NULL && $usersAccess->where('accessDsc','Statistiken')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Dienstleistungen')->first() != NULL && $usersAccess->where('accessDsc','Dienstleistungen')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','RechnungMngAcce')->first() != NULL && $usersAccess->where('accessDsc','RechnungMngAcce')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Heute_Verkaufe')->first() != NULL && $usersAccess->where('accessDsc','Heute_Verkaufe')->first()->accessValid == 1))
            @if(Request::is('admWoMngStatistics01Waiter') || Request::is('admWoMngStatistics02Waiter') || Request::is('admWoMngStatistics03Waiter') || Request::is('admWoMngStatistics04Waiter') || Request::is('adminWoRechnungPageWaiter')
            || Request::is('categorizeReportWaiter') || Request::is('admWoMngCanceledOrdersWaiter') || Request::is('waitersSalesToday') || Request::is('adminWoServiceRequestWaiter') || Request::is('WaiterstatsBillsRecs') 
            || Request::is('WaiterstatsDeletedTAProdsPage') || Request::is('openCheckInOutReportsWa') || Request::is('AdminchngPayMethodForOrdersPageWa'))
            <button onclick="openSerCat('1')" id="serCatBtn1" class="btn mb-2 shadow-none" style="margin: 4px; padding:2px; border:1px solid white; border-radius:3px;">
            @else
            <button onclick="openSerCat('1')" id="serCatBtn1" class="btn mb-2 shadow-none" style="margin: 4px; padding:2px;">
            @endif
                <img style="width:100%; height:auto;" src="/storage/images/resSerCat03_Stats.png" alt="">
            </button>
        @endif

        @if(($usersAccess->where('accessDsc','Aufträge')->first() != NULL && $usersAccess->where('accessDsc','Aufträge')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Empfohlen')->first() != NULL && $usersAccess->where('accessDsc','Empfohlen')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Tabellenwechsel')->first() != NULL && $usersAccess->where('accessDsc','Tabellenwechsel')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Takeaway')->first() != NULL && $usersAccess->where('accessDsc','Takeaway')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Trinkgeld')->first() != NULL && $usersAccess->where('accessDsc','Trinkgeld')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Tischreservierungen')->first() != NULL && $usersAccess->where('accessDsc','Tischreservierungen')->first()->accessValid == 1))
            @if (Request::is('admWoMngIndexWaiter') || Request::is('dashboard2') || Request::is('dashboard3') || Request::is('admWoMngOrdersListWaiter')|| Request::is('admWoMngTakeawayWaiter') 
            || Request::is('admWoMngDeliveryWaiter') || Request::is('admWoMngOrdersFreeTables') || Request::is('adminWoRecomendetProdWaiter') 
            || Request::is('adminWoTableChngReqWaiter') || Request::is('adminWoTipsWaiter') || Request::is('adminWoTipsMonthWaiter') || Request::is('adminWoTableReservationIndexWaiter') 
            || Request::is('adminWoTableReservationListWaiter') || Request::is('giftCardMngAdminWa'))
            <button onclick="openSerCat('2')" id="serCatBtn2" class="btn mb-2 shadow-none card_sideET" style="margin: 4px; padding:2px; border:1px solid white; border-radius:3px;">
            @else
            <button onclick="openSerCat('2')" id="serCatBtn2" class="btn mb-2 shadow-none card_sideET"  style="margin: 4px; padding:2px;">
            @endif
                @if($countONotDone > 0 || $TabNotFinish > 0 || $countTableCHReq > 0 || $countTableReservationReq > 0)
                    <span id="sideET" class="top_sideET"></span>
                    <span id="sideET" class="right_sideET"></span>
                    <span id="sideET" class="bottom_sideET"></span>
                    <span id="sideET" class="left_sideET"></span>
                @endif
                <img style="width:100%; height:auto;" src="/storage/images/resSerCat01_food.png" alt="">
            </button>
        @endif

        @if (($usersAccess->where('accessDsc','Products')->first() != NULL && $usersAccess->where('accessDsc','Products')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Frei')->first() != NULL && $usersAccess->where('accessDsc','Frei')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','16+/18+')->first() != NULL && $usersAccess->where('accessDsc','16+/18+')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Gutscheincode')->first() != NULL && $usersAccess->where('accessDsc','Gutscheincode')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Takeaway')->first() != NULL && $usersAccess->where('accessDsc','Takeaway')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Delivery')->first() != NULL && $usersAccess->where('accessDsc','Delivery')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Tischkapazität')->first() != NULL && $usersAccess->where('accessDsc','Tischkapazität')->first()->accessValid == 1))
            @if (Request::is('adminWoContentMngWaiter') || Request::is('adminWoContentMngWaiter/Order') || Request::is('adminWoFreeProductsWaiter') || Request::is('adminWoRestrictProductsWaiter')
            || Request::is('adminWoCouponsWaiter') || Request::is('adminWoTakeawayWaiter') || Request::is('adminWoDeliveryWaiter') || Request::is('adminWoTableCapacityWaiter'))
            <button onclick="openSerCat('3')" id="serCatBtn3" class="btn mb-2 shadow-none" style="margin: 4px; padding:2px; border:1px solid white; border-radius:3px;">
            @else
            <button onclick="openSerCat('3')" id="serCatBtn3" class="btn mb-2 shadow-none" style="margin: 4px; padding:2px;">
            @endif
                <img style="width:100%; height:auto;" src="/storage/images/resSerCat02_mng.png" alt="">
            </button>
        @endif

        @if (($usersAccess->where('accessDsc','talkToQrorpaSA')->first() != NULL && $usersAccess->where('accessDsc','talkToQrorpaSA')->first()->accessValid == 1)
        || ($usersAccess->where('accessDsc','Covid-19')->first() != NULL && $usersAccess->where('accessDsc','Covid-19')->first()->accessValid == 1))
            @if (Request::is('adminWoSaMsgWaiter') || Request::is('adminWoCovid19Waiter'))
            <button onclick="openSerCat('4')" id="serCatBtn4" class="btn mb-2 shadow-none" style="margin: 4px; padding:2px; border:1px solid white; border-radius:3px;">
            @else
            <button onclick="openSerCat('4')" id="serCatBtn4" class="btn mb-2 shadow-none" style="margin: 4px; padding:2px; ">
            @endif
                <img style="width:100%; height:auto;" src="/storage/images/resSerCat04_others.png" alt="">
            </button>
        @endif

    </div>




    <div id="sideExtButtons" style="width: 85%; height:inherit; margin-left:5px;">
        <img style="width:70%; height:auto;" class="mt-1 mb-5" src="/storage/images/logo_QRorpa_wh.png" alt="">
        @if(Auth::user()->role == 55 )
            
            <div id="list_statistics_show" class="listsOne" style="display: none;">
                @if(($usersAccess->where('accessDsc','Statistiken')->first() != NULL && $usersAccess->where('accessDsc','Statistiken')->first()->accessValid == 1)
                || ($usersAccess->where('accessDsc','Heute_Verkaufe')->first() != NULL && $usersAccess->where('accessDsc','Heute_Verkaufe')->first()->accessValid == 1))
                    <a href="{{route('admWoMng.ordersStatisticsWaiter01')}}" class="newSideElAdmin">
                        @if(Request::is('admWoMngStatistics01Waiter') || Request::is('admWoMngStatistics02Waiter') || Request::is('admWoMngStatistics03Waiter') || Request::is('adminWoRechnungPageWaiter')
                        || Request::is('admWoMngStatistics04Waiter') || Request::is('categorizeReportWaiter') || Request::is('admWoMngCanceledOrdersWaiter') || Request::is('WaiterstatsBillsRecs') || Request::is('waitersSalesToday'))
                            <img class="sideIconAP" src="/storage/images/APstatistika02.PNG" alt=""> 
                            <span class="glow" style="color:white;"><strong>Verkäufe</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/APstatistika01.PNG" alt=""> 
                            <span style="color:white;"><strong>Verkäufe</strong></span>
                        @endif
                    </a>
                    <br>
                @endif

                @if($usersAccess->where('accessDsc','Dienstleistungen')->first() != NULL && $usersAccess->where('accessDsc','Dienstleistungen')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoServiceRequestWaiter')}}">
                        @if(Request::is('adminWoServiceRequestWaiter'))
                            <img class="sideIconAP" src="/storage/images/serviceReq02.png" alt="">
                            <span class="glow" style="color:white;"><strong>{{__('adminP.services')}}</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/serviceReq01.png" alt="">
                            <span style="color:white;"><strong>{{__('adminP.services')}}</strong></span>
                        @endif
                    </a>
                    <br>
                @endif

                <br>
                <a class="newSideElAdmin" href="{{route('waiter.notificationsActPage')}}">
                    @if(Request::is('NotificationsAct'))
                        <img class="sideIconAP" src="/storage/images/notifyMng02.png" alt="">
                        <span class="glow" style="color:white;"><strong>Aktive Benachrichtigungen</strong></span>
                    @else
                        <img class="sideIconAP" src="/storage/images/notifyMng01.png" alt="">
                        <span style="color:white;"><strong>Aktive Benachrichtigungen</strong></span>
                    @endif
                </a>
                <br>
            </div>


            <div id="list_foods_show" class="listsOne" style="display: none;">
                @if(Auth::user()->sFor != 32 && Auth::user()->sFor != 33)
                @if($usersAccess->where('accessDsc','Aufträge')->first() != NULL && $usersAccess->where('accessDsc','Aufträge')->first()->accessValid == 1)
                    <a class="allowNotiOrders newSideElAdmin" href="{{route('admWoMng.indexAdmMngPageWaiter')}}">
                        @if($countONotDoneJustRes > 0 || $TabNotFinish > 0)
                            <img class="sideIconAP alertSideIconApp" src="/storage/images/APporosit03.PNG" alt="">
                            @if(Request::is('admWoMngIndexWaiter') || Request::is('dashboard2') || Request::is('dashboard3') || Request::is('admWoMngOrdersListWaiter')
                            || Request::is('admWoMngOrdersFreeTables'))
                            <span class="glow" style="color:white;"><strong>{{__('adminP.assignments')}}</strong></span>
                            @else
                            <span style="color:white;"><strong>{{__('adminP.assignments')}}</strong></span>
                            @endif
                        @else
                            @if(Request::is('admWoMngIndexWaiter') || Request::is('dashboard2') || Request::is('dashboard3') || Request::is('admWoMngOrdersListWaiter') 
                            || Request::is('admWoMngOrdersFreeTables'))
                            <img class="sideIconAP" src="/storage/images/APporosit02.PNG" alt="">
                            <span class="glow" style="color:white;"><strong>{{__('adminP.assignments')}}</strong></span>
                            @else
                            <img class="sideIconAP" src="/storage/images/APporosit01.PNG" alt="">
                            <span style="color:white;"><strong>{{__('adminP.assignments')}}</strong></span>
                            @endif
                        @endif
                    </a>
                    <br>
                @endif
                @endif

                @if($usersAccess->where('accessDsc','Takeaway')->first() != NULL && $usersAccess->where('accessDsc','Takeaway')->first()->accessValid == 1)
                <br>
                    <?php  $takeawayOrCnt = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
                            ->where([['statusi','<',2],['nrTable','500']])->get()->count();
                    ?>
                    <a class="newSideElAdmin" href="{{route('admWoMng.ordersTakeawayWaiter')}}">
                        @if($takeawayOrCnt > 0)
                            <img class="sideIconAP alertSideIconApp" src="/storage/images/TakeawayAdmin03.png" alt="">
                            @if(Request::is('adminWoTakeawayWaiter'))
                            <span class="glow" style="color:white;"><strong>{{__('adminP.takeaway')}} {{__('adminP.assignments')}}</strong></span>
                            @else
                            <span style="color:white;"><strong>{{__('adminP.takeaway')}} {{__('adminP.assignments')}}</strong></span>
                            @endif
                        @else
                            @if(Request::is('adminWoTakeawayWaiter'))
                                <img class="sideIconAP" src="/storage/images/TakeawayAdmin02.png" alt="">
                                <span class="glow" style="color:white;"><strong>{{__('adminP.takeaway')}} {{__('adminP.assignments')}}</strong></span>
                            @else
                                <img class="sideIconAP" src="/storage/images/TakeawayAdmin01.png" alt="">
                                <span style="color:white;"><strong>{{__('adminP.takeaway')}} {{__('adminP.assignments')}}</strong></span>
                            @endif
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','Delivery')->first() != NULL && $usersAccess->where('accessDsc','Delivery')->first()->accessValid == 1)
                <br>
                <?php
                    $deliveryOrCnt = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
                    ->where([['statusi','<',2],['nrTable','9000']])->get()->count();
                ?>
                    <a class="newSideElAdmin" href="{{route('admWoMng.ordersDeliveryWaiter')}}">
                        @if($deliveryOrCnt > 0)
                            <img class="sideIconAP alertSideIconApp" src="/storage/images/deliveryIcon03.png" alt="">
                            @if(Request::is('adminWoDeliveryWaiter'))
                            <span class="glow" style="color:white;"><strong>{{__('adminP.delivery')}} {{__('adminP.assignments')}}</strong></span>
                            @else
                            <span style="color:white;"><strong>{{__('adminP.delivery')}} {{__('adminP.assignments')}}</strong></span>
                            @endif
                        @else
                            @if(Request::is('adminWoDeliveryWaiter'))
                                <img class="sideIconAP" src="/storage/images/deliveryIcon02.png" alt="">
                                <span class="glow" style="color:white;"><strong>{{__('adminP.delivery')}} {{__('adminP.assignments')}}</strong></span>
                            @else
                                <img class="sideIconAP" src="/storage/images/deliveryIcon01.png" alt="">
                                <span style="color:white;"><strong>{{__('adminP.delivery')}} {{__('adminP.assignments')}}</strong></span>
                            @endif
                        @endif
                    </a>
                    <br>
                @endif

                @if(1 == 1)
                <br>  
                    <a class="newSideElAdmin" href="{{route('giftCardWa.giftCardMngAdminWa')}}">
                        @if(Request::is('giftCardMngAdminWa'))
                            <img class="sideIconAP" src="/storage/images/giftCardIcon02.png" alt="">
                            <span class="glow" style="color:white;"><strong>Geschenkkarte</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/giftCardIcon01.png" alt="">
                            <span style="color:white;"><strong>Geschenkkarte</strong></span>
                        @endif
                    </a>
                    <br>
                @endif
                
            

                
                @if($usersAccess->where('accessDsc','Empfohlen')->first() != NULL && $usersAccess->where('accessDsc','Empfohlen')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoRecomendetProdWaiter')}}">
                        @if(Request::is('adminWoRecomendetProdWaiter'))
                            <img class="sideIconAP" src="/storage/images/RecomendetIcon02.png" alt="">
                            <span class="glow" style="color:white;"><strong>{{__('adminP.recommended')}}</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/RecomendetIcon01.png" alt="">
                            <span style="color:white;"><strong>{{__('adminP.recommended')}}</strong></span>
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','Tabellenwechsel')->first() != NULL && $usersAccess->where('accessDsc','Tabellenwechsel')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoTableChngReqWaiter')}}">
                        @if($countTableCHReq > 0)
                            <img class="sideIconAP alertSideIconApp" src="storage/images/IconTableCH03.png" alt="">
                            @if(Request::is('adminWoTableChngReqWaiter'))
                                <span class="glow" style="color:white;"><strong>{{__('adminP.changeTable')}}</strong></span>
                            @else
                                <span style="color:white;"><strong>{{__('adminP.changeTable')}}</strong></span>
                            @endif
                        @else
                            @if(Request::is('adminWoTableChngReqWaiter'))
                                <img class="sideIconAP " src="/storage/images/IconTableCH02.png" alt="">
                                <span class="glow" style="color:white;"><strong>{{__('adminP.changeTable')}}</strong></span>
                            @else
                                <img class="sideIconAP" src="/storage/images/IconTableCH01.png" alt="">
                                <span style="color:white;"><strong>{{__('adminP.changeTable')}}</strong></span>
                            @endif
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','Trinkgeld')->first() != NULL && $usersAccess->where('accessDsc','Trinkgeld')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoTipsWaiter')}}">
                        @if(Request::is('adminWoTipsWaiter') || Request::is('adminWoTipsMonthWaiter'))
                            <img class="sideIconAP" src="/storage/images/TipsIcon02.png" alt="">
                            <span class="glow" style="color:white;"><strong>{{__('adminP.tip')}}</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/TipsIcon01.png" alt="">
                            <span style="color:white;"><strong>{{__('adminP.tip')}}</strong></span>
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','Tischreservierungen')->first() != NULL && $usersAccess->where('accessDsc','Tischreservierungen')->first()->accessValid == 1)
                <br>
                    <!-- Rezervimi i tavolines -->
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoTableReservationIndexWaiter')}}">
                        @if($countTableReservationReq > 0)
                            <img class="sideIconAP alertSideIconApp" src="/storage/images/TableReservation03.png" alt="">
                            @if(Request::is('adminWoTableReservationIndexWaiter') || Request::is('adminWoTableReservationListWaiter'))
                                <span class="glow" style="color:white;"><strong>{{__('adminP.tableReservations')}}</strong></span>
                            @else
                                <span style="color:white;"><strong>{{__('adminP.tableReservations')}}</strong></span>
                            @endif
                        @else
                            @if(Request::is('adminWoTableReservationIndexWaiter') || Request::is('adminWoTableReservationListWaiter'))
                                <img class="sideIconAP" src="/storage/images/TableReservation02.png" alt="">
                                <span class="glow" style="color:white;"><strong>{{__('adminP.tableReservations')}}</strong></span>
                            @else
                                <img class="sideIconAP" src="/storage/images/TableReservation01.png" alt="">
                                <span style="color:white;"><strong>{{__('adminP.tableReservations')}}</strong></span>
                            @endif
                        @endif
                    </a>
                    <br>
                @endif
            </div>



            <div id="list_management_show" class="listsOne" style="display: none;">
                @if($usersAccess->where('accessDsc','Products')->first() != NULL && $usersAccess->where('accessDsc','Products')->first()->accessValid == 1)
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoContentMngWaiter')}}">
                        @if(Request::is('adminWoContentMngWaiter') || Request::is('adminWoContentMngWaiter/Order'))
                            <img class="sideIconAP " src="/storage/images/productMng02.png" alt="">
                            <span class="glow" style="color:white;"><strong>{{__('adminP.products')}}</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/productMng01.png" alt="">
                            <span style="color:white;"><strong>{{__('adminP.products')}}</strong></span>
                        @endif
                    </a>
                    <br>
                @endif

                @if(1 == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('orServing.orderServingDevicesPageWaiter')}}">
                        @if(Request::is('orderServingDevicesPageWaiter'))
                            <img class="sideIconAP " src="/storage/images/orderServingIc02.png" alt="">
                            <span class="glow" style="color:white;"><strong>Servieren bestellen</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/orderServingIc01.png" alt="">
                            <span style="color:white;"><strong>Servieren bestellen</strong></span>
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','16+/18+')->first() != NULL && $usersAccess->where('accessDsc','16+/18+')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoRestrictProductsWaiter')}}">
                        @if(Request::is('adminWoRestrictProductsWaiter'))
                            <img class="sideIconAP" src="/storage/images/RestrictProAP02.png" alt="">
                            <span class="glow" style="color:white;"><strong>16+/18+</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/RestrictProAP01.png" alt="">
                            <span style="color:white;"><strong>16+/18+</strong></span>
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','Gutscheincode')->first() != NULL && $usersAccess->where('accessDsc','Gutscheincode')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoCouponsWaiter')}}">
                        @if(Request::is('adminWoCouponsWaiter'))
                            <img class="sideIconAP" src="/storage/images/CuponsC.PNG" alt="">
                            <span class="glow" style="color:white;"><strong>{{__('adminP.couponCode')}}</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/CuponsW.PNG" alt="">
                            <span style="color:white;"><strong>{{__('adminP.couponCode')}}</strong></span>
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','Takeaway')->first() != NULL && $usersAccess->where('accessDsc','Takeaway')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoTakeawayWaiter')}}">
                        @if(Request::is('adminWoTakeawayWaiter'))
                            <img class="sideIconAP" src="/storage/images/TakeawayAdmin02.png" alt="">
                            <span class="glow" style="color:white;"><strong>{{__('adminP.takeaway')}}</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/TakeawayAdmin01.png" alt="">
                            <span style="color:white;"><strong>{{__('adminP.takeaway')}}</strong></span>
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','Delivery')->first() != NULL && $usersAccess->where('accessDsc','Delivery')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoDeliveryWaiter')}}">
                        @if(Request::is('adminWoDeliveryWaiter'))
                            <img class="sideIconAP" src="/storage/images/deliveryIcon02.png" alt="">
                            <span class="glow" style="color:white;"><strong>{{__('adminP.delivery')}}</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/deliveryIcon01.png" alt="">
                            <span style="color:white;"><strong>{{__('adminP.delivery')}}</strong></span>
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','Tischkapazität')->first() != NULL && $usersAccess->where('accessDsc','Tischkapazität')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin" href="{{route('admWoMng.adminWoTableCapacityWaiter')}}">
                        @if(Request::is('adminWoTableCapacityWaiter'))
                            <img class="sideIconAP" src="/storage/images/tableRez02.png" alt="">
                            <span class="glow" style="color:white;"><strong>{{__('adminP.tableCapacity')}}</strong></span>
                        @else
                            <img class="sideIconAP" src="/storage/images/tableRez01.png" alt="">
                            <span style="color:white;"><strong>{{__('adminP.tableCapacity')}}</strong></span>
                        @endif
                    </a>
                    <br>
                @endif

                <br>
                <a class="newSideElAdmin" href="{{route('billTabletWaiter.index')}}">
                    @if(Request::is('BillTablets'))
                        <img class="sideIconAP" src="/storage/images/billTabletIco02.png" alt="">
                        <span class="glow" style="color:white;"><strong>Quittungstablett</strong></span>
                    @else
                        <img class="sideIconAP" src="/storage/images/billTabletIco01.png" alt="">
                        <span style="color:white;"><strong>Quittungstablett</strong></span>
                    @endif
                </a>
                <br>
            </div>



            <div id="list_others_show" class="listsOne" style="display: none;">
                @if($usersAccess->where('accessDsc','talkToQrorpaSA')->first() != NULL && $usersAccess->where('accessDsc','talkToQrorpaSA')->first()->accessValid == 1)
                    <a href="{{route('admWoMng.adminSaMsgWaiter')}}" id="atsMsgOpenPageAdminPanel" class="newSideElAdmin">
                        @if(Request::is('adminWoSaMsgWaiter'))
                            <img class="sideIconAP alertSideIconApp" src="/storage/images/talkToQRorpa02.PNG" alt=""> 
                                <span class="glow" style="color:white;"><strong>{{__('adminP.talkToQRorpa')}}</strong></span>
                        @else
                            @if (admMsgSaavchats::where([['msgForId',Auth::User()->id],['readStatus',0]])->count() > 0)
                            <img class="sideIconAP" src="/storage/images/talkToQRorpa03.PNG" alt=""> 
                                <span style="color:white;"><strong>{{__('adminP.talkToQRorpa')}}</strong></span>
                            @else
                            <img class="sideIconAP" src="/storage/images/talkToQRorpa01.PNG" alt=""> 
                                <span style="color:white;"><strong>{{__('adminP.talkToQRorpa')}}</strong></span>
                            @endif
                        @endif
                    </a>
                    <br>
                @endif
                
                @if($usersAccess->where('accessDsc','Covid-19')->first() != NULL && $usersAccess->where('accessDsc','Covid-19')->first()->accessValid == 1)
                <br>
                    <a class="newSideElAdmin pl-1" href="{{route('admWoMng.adminWoCovid19Waiter')}}">
                        @if(Request::is('adminWoCovid19Waiter'))
                            <img style="width:40px; height:40px; background-color:white; border-radius:50%;" src="/storage/icons/covid-green.png" alt="">
                            <span class="glow" style="color:white;"><strong>Covid-19</strong></span>      
                        @else
                            <img style="width:36px; height:36px; padding:10px;" src="/storage/icons/covid-white.png" alt="">
                            <span style="color:white;"><strong>Covid-19</strong></span>                
                        @endif
                    </a>
                @endif
            </div>

        @endif
    </div>
</div>



<script>
    // list_others_show

    function openSerCat(serCat){
        if(closedSideBar == 1){
            $('#content').attr('style','margin-left:15%; width:85%;');
            $('#DashNavbar').attr('style','margin-left:15%; width:85%;');
        }
        closedSideBar = 0;
        if(serCat == 1){
            $('#serCatBtn2').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn2").attr("onclick","openSerCat('2')");
            $('#serCatBtn3').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn3").attr("onclick","openSerCat('3')");
            $('#serCatBtn4').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn4").attr("onclick","openSerCat('4')");

            $('#serCatBtn1').attr('style','margin: 4px; padding:2px; border:1px solid white; border-radius:3px;');
            $("#serCatBtn1").attr("onclick","closeSerCat('1')");

            $('#list_foods_show').hide(50);
            $('#list_management_show').hide(50);
            $('#list_others_show').hide(50);

            $('#list_statistics_show').show(50);

        }else if(serCat == 2){
            $('#serCatBtn1').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn1").attr("onclick","openSerCat('1')");
            $('#serCatBtn3').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn3").attr("onclick","openSerCat('3')");
            $('#serCatBtn4').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn4").attr("onclick","openSerCat('4')");

            $('#serCatBtn2').attr('style','margin: 4px; padding:2px; border:1px solid white; border-radius:3px;');
            $("#serCatBtn2").attr("onclick","closeSerCat('2')");

            $('#list_statistics_show').hide(50);
            $('#list_management_show').hide(50);
            $('#list_others_show').hide(50);

            $('#list_foods_show').show(50);
        }else if(serCat == 3){
            $('#serCatBtn1').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn1").attr("onclick","openSerCat('1')");
            $('#serCatBtn2').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn2").attr("onclick","openSerCat('2')");
            $('#serCatBtn4').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn4").attr("onclick","openSerCat('4')");

            $('#serCatBtn3').attr('style','margin: 4px; padding:2px; border:1px solid white; border-radius:3px;');
            $("#serCatBtn3").attr("onclick","closeSerCat('3')");

            $('#list_foods_show').hide(50);
            $('#list_statistics_show').hide(50);
            $('#list_others_show').hide(50);

            $('#list_management_show').show(50);
        }else if(serCat == 4){
            $('#serCatBtn1').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn1").attr("onclick","openSerCat('1')");
            $('#serCatBtn2').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn2").attr("onclick","openSerCat('2')");
            $('#serCatBtn3').attr('style','margin: 4px; padding:2px;');
            $("#serCatBtn3").attr("onclick","openSerCat('3')");

            $('#serCatBtn4').attr('style','margin: 4px; padding:2px; border:1px solid white; border-radius:3px;');
            $("#serCatBtn4").attr("onclick","closeSerCat('4')");

            $('#list_foods_show').hide(50);
            $('#list_statistics_show').hide(50);
            $('#list_management_show').hide(50);

            $('#list_others_show').show(50);
        }
    }

    function closeSerCat(serCat){
        closedSideBar = 1;

        $('#serCatBtn'+serCat).attr('style','margin: 4px; padding:2px;');

        $('#list_statistics_show').hide(50)
        $('#list_foods_show').hide(50);
        $('#list_management_show').hide(50);
        $('#list_others_show').hide(50);

        $('#content').attr('style','margin-left:3%; width:97%;');
        $('#DashNavbar').attr('style','margin-left:3%; width:97%;');

        $('#serCatBtn'+serCat).attr('onclick','openSerCat("'+serCat+'")');
    }
</script>
