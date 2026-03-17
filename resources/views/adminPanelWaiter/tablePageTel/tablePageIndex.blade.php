<?php
use Illuminate\Support\Facades\Auth;
if(Auth::user()->sFor == 45){
    header("Location: ".route('admWoMng.ordersTakeawayWaiter'));
    exit();
}

use App\Orders;
use App\kategori;
use App\Restorant;
use App\TabOrder;
use Carbon\Carbon;
use App\TableQrcode;
use App\UserNotiDtlSet;
use App\newOrdersAdminAlert;
use App\restorantTableRoom;
use App\restorantTablesToRoom;


    $notiActi = explode('--||--',Auth::user()->	notifySet);
    $usrNotiReg = UserNotiDtlSet::where([['UsrId',Auth::user()->id],['setType',1]])->first();

    $allTables = TableQrcode::where('Restaurant',Auth::user()->sFor)->whereIn('tableNr',$myTablesWaiter)->orderBy('tableNr')->get();

    $hasTablesInRooms = restorantTableRoom::where('toRes',Auth::user()->sFor)->count();
?>

@if($usrNotiReg != Null)
<style>  
    @keyframes glowing {
        0% { box-shadow: 0 0 -10px <?php echo $usrNotiReg->setValue; ?>; background-position: 0 0;} 
        40% { box-shadow: 0 0 20px <?php echo $usrNotiReg->setValue; ?>; } 
        60% { box-shadow: 0 0 20px <?php echo $usrNotiReg->setValue; ?>; }
        100% { box-shadow: 0 0 -10px <?php echo $usrNotiReg->setValue; ?>; background-position: 1280px 0;}
    }
    .table-glow-adminAlert {animation: glowing 1000ms linear infinite; border-radius: 6px; cursor: pointer; }
</style>
@else
<style>  
    @keyframes glowing {
        0% { box-shadow: 0 0 -10px red; background-position: 0 0;} 
        40% { box-shadow: 0 0 20px red; } 
        60% { box-shadow: 0 0 20px red; }
        100% { box-shadow: 0 0 -10px red; background-position: 1280px 0;}
    }
    .table-glow-adminAlert {animation: glowing 1000ms linear infinite; border-radius: 6px; cursor: pointer; }
</style>
@endif
<style>
    .pointTable{ cursor:pointer; }
    .pointTableNot{ cursor:not-allowed;}
</style>

<input type="hidden" id="resTvshInput" value="{{Restorant::find(Auth::user()->sFor)->resTvsh}}">

@include('adminPanelWaiter.tablePageTel.tableIndexTopLine')

@if($hasTablesInRooms > 0)
    <?php
        $tableRooms = restorantTableRoom::where('toRes',Auth::user()->sFor)->get();

        $tableIdToShow = array();

        if(isset($_GET['ri'])){
            $ri = $_GET['ri'];
            foreach(restorantTablesToRoom::where('toRoomId',$ri)->whereIn('tableNr',$myTablesWaiter)->get() as $tableRoomOne){ 
                array_push($tableIdToShow,$tableRoomOne->tableId);
            }
        }else{
            $ri = 0;
            foreach(restorantTablesToRoom::where([['toRes',Auth::user()->sFor],['toRoomNr','1']])->whereIn('tableNr',$myTablesWaiter)->get() as $tableRoomOne){ 
                array_push($tableIdToShow,$tableRoomOne->tableId);
            }
        }
    ?>
    <div style="width:100%;" class="d-flex flex-wrap justify-content-between">
        @foreach($tableRooms as $tableRoomOne)
            @if(($ri == 0 && $tableRoomOne->roomNumber == 1) || $ri == $tableRoomOne->id)
                <button type="button" class="btn btn-dark shadow-none mb-1" style="width: 33%;">
                    <strong>{{$tableRoomOne->roomName}}</strong> 
                </button>
            @else
                <a href="{{ route('admWoMng.indexAdmMngPageWaiter', ['ri' => $tableRoomOne->id]) }}" class="btn btn-outline-dark shadow-none mb-1" style="width: 33%;">
                    <strong>{{$tableRoomOne->roomName}}</strong>    
                </a>    
            @endif
        @endforeach
    </div>
    <?php
        $showTablesIns = TableQrcode::whereIn('id',$tableIdToShow)->orderBy('tableNr')->get();
    ?>
@else
    <?php $showTablesIns = $allTables; ?>
@endif




<div id="allTablePageDiv" style="width:100%;" class="d-flex flex-wrap justify-content-start mt-3">
    @if(Auth::user()->sFor == 26 || Auth::user()->sFor == 44 || Auth::user()->sFor == 49)
        @if($ri == 16 || $ri == 19 || $ri == 22 || $ri == 0)
            @include('adminPanelWaiter.svgMap.res44_room16')
        @elseif($ri == 17 || $ri == 20 || $ri == 23)
            @include('adminPanelWaiter.svgMap.res44_room17')
        @elseif($ri == 18 || $ri == 21 || $ri == 24)
            @include('adminPanelWaiter.svgMap.res44_room18')
        @endif
    @elseif(Auth::user()->sFor == 68)
        @if($ri == 8 || $ri == 0)
            @include('adminPanelWaiter.svgMap.res68_room8')
        @elseif($ri == 9)
            @include('adminPanelWaiter.svgMap.res68_room9')
        @elseif($ri == 10)
            @include('adminPanelWaiter.svgMap.res68_room10')
        @elseif($ri == 14)
            @include('adminPanelWaiter.svgMap.res68_room14')
        @elseif($ri == 15)
            @include('adminPanelWaiter.svgMap.res68_room15')
        @endif
    @elseif(Auth::user()->sFor == 69)
        @if($ri == 11 || $ri == 0)
            @include('adminPanelWaiter.svgMap.res69_room11')
        @elseif($ri == 12)
            @include('adminPanelWaiter.svgMap.res69_room12')
        @elseif($ri == 13)  
            @include('adminPanelWaiter.svgMap.res69_room13')
        @endif
    @elseif(Auth::user()->sFor == 72)
        @if($ri == 4 || $ri == 0)
            @include('adminPanelWaiter.svgMap.res72_room4')
        @elseif($ri == 5)
            @include('adminPanelWaiter.svgMap.res72_room5')
        @elseif($ri == 6)
            @include('adminPanelWaiter.svgMap.res72_room6')
        @elseif($ri == 7)
            @include('adminPanelWaiter.svgMap.res72_room7')
        @endif
    @else
        @foreach($showTablesIns as $tabelOne)
            <div id="tableIconDiv{{$tabelOne->tableNr}}" class="allTablesDes mb-2"  style="width:15%; margin-right:0.83%; margin-left:0.83%;">
                <?php
                    if($notiActi[0] == 1){
                        if(newOrdersAdminAlert::where([['adminId',Auth::User()->id],['tableNr',$tabelOne->tableNr],['statActive','1']])->first() != NULL){ 
                            $hasAlert = True; 
                        }else{  $hasAlert = False; }
                    }else{ $hasAlert = False; }

                    $ordsTdy = Orders::where([['Restaurant',Auth::user()->sFor],['statusi','<',2],['nrTable',$tabelOne->tableNr]])->whereDate('created_at', Carbon::today())->orderByDesc('created_at')->get();

                    $statLess1 = 0; $statLess2 = 0;
                    foreach($ordsTdy as $orOne){ if($orOne->statusi < 2){$statLess2 = 1; $statLess1 = 1; break;} }
                    if($statLess1 == 0){
                        foreach($ordsTdy as $orOne){if($orOne->statusi < 1){$statLess1 = 1; break;}}
                    }
                ?>
                @if($tabelOne->kaTab != 0 )
                    @if($tabelOne->kaTab == -1)
                        <img style="width:100%;" onclick="setTabStat('{{$tabelOne->tableNr}}','{{$tabelOne->id}}','0')" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}" 
                        src="storage/images/tableSt_qrorpa4_yellow.png" id="tableIcon{{$tabelOne->tableNr}}">
                        <p class="text-center" style="color:black; margin-top:-50px; font-size:22px;" onclick="setTabStat('{{$tabelOne->tableNr}}','{{$tabelOne->id}}','0')">
                            <strong>{{$tabelOne->tableNr}}</strong>
                        </p>

                    @elseif(TabOrder::where([['tabCode',$tabelOne->kaTab],['status',0]])->count() > 0)
                        <img data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" style="width:100%;" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
                        src="storage/images/tableSt_qrorpa4_red.png" id="tableIcon{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')">
                        <p data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="text-center" style="color:black; margin-top:-50px; font-size:22px;"
                        onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')">
                            <strong>{{$tabelOne->tableNr}}</strong>
                        </p>
                    @else
                        <img data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}"  style="width:100%;" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
                        src="storage/images/tableSt_qrorpa4_yellow.png" id="tableIcon{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')">
                        <p data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="text-center" style="color:black; margin-top:-50px; font-size:22px;"
                        onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')">
                            <strong>{{$tabelOne->tableNr}}</strong>
                        </p>
                    @endif

                @elseif($statLess1 == 1 || $statLess2 == 1 ) 
                    <img data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" style="width:100%;" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}" 
                    src="storage/images/tableSt_qrorpa4_green.png" alt="NotFound" id="tableIconGreen{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')">
                    <p data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="text-center" style="color:black; margin-top:-50px; font-size:22px;"
                    onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')">
                        <strong>{{$tabelOne->tableNr}}</strong>
                    </p>
                @else
                    <img data-toggle="modal" data-target="#newTabOrderModal" style="width:100%;" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}" 
                    src="storage/images/tableSt_qrorpa4.PNG" id="tableIcon{{$tabelOne->tableNr}}" onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')">
                    <p data-toggle="modal" data-target="#newTabOrderModal" class="text-center" style="color:black; margin-top:-50px; font-size:22px;"
                    onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')">
                        <strong>{{$tabelOne->tableNr}}</strong>
                    </p>
                @endif
            </div>
            <input type="hidden" id="tableNeedsToReset{{$tabelOne->tableNr}}" value="0">
        @endforeach
    @endif
</div>


@include('adminPanelWaiter.tablePageTel.newOrderRegEl')
@if (1 == 0)
    @include('adminPanelWaiter.tablePageTel.tableIndexActiveOrMd')
@else
    @include('adminPanelWaiter.tablePageTel.tableIndexActiveOrMdVer_2')
@endif
@include('adminPanelWaiter.tablePageTel.tableIndexNotifications')
@include('adminPanelWaiter.tablePageTel.tablePageIndexScript')