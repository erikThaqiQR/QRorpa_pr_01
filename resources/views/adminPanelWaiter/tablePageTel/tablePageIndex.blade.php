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
    <?php $showTablesIns = $allTables; 
    $ri = 0;?>
@endif




<div id="allTablePageDiv" style="width:100%;" class="d-flex flex-wrap justify-content-start mt-3">
    @php
        $sFor = Auth::user()->sFor;

        $svgMapCall = [
            26 => [
                16 => 'res44_room16', 19 => 'res44_room16', 22 => 'res44_room16', 0 => 'res44_room16',
                17 => 'res44_room17', 20 => 'res44_room17', 23 => 'res44_room17',
                18 => 'res44_room18', 21 => 'res44_room18', 24 => 'res44_room18',
            ],
            44 => [], // same as 26 (we'll reuse below)
            49 => [], // same as 26 (we'll reuse below)

            57 => [
                29 => 'res57_room29', 0 => 'res57_room29',
                30 => 'res57_room30',
                31 => 'res57_room31',
            ],

            60 => [
                33 => 'res60_room33', 0 => 'res60_room33',
                34 => 'res60_room34',
                35 => 'res60_room35',
            ],

            68 => [
                8 => 'res68_room8', 0 => 'res68_room8',
                9 => 'res68_room9',
                10 => 'res68_room10',
                14 => 'res68_room14',
                15 => 'res68_room15',
            ],

            69 => [
                11 => 'res69_room11', 0 => 'res69_room11',
                12 => 'res69_room12',
                13 => 'res69_room13',
            ],

            72 => [
                4 => 'res72_room4', 0 => 'res72_room4',
                5 => 'res72_room5',
                6 => 'res72_room6',
                7 => 'res72_room7',
            ],

            73 => [
                25 => 'res73_room25', 0 => 'res73_room25',
                26 => 'res73_room26',
                27 => 'res73_room27',
                28 => 'res73_room28',
            ],
        ];

        // Reuse mapping for 44 and 49 same as 26
        if (in_array($sFor, [44, 49])) { $svgMapCall[$sFor] = $svgMapCall[26]; }


        $view = $svgMapCall[$sFor][$ri] ?? null;
    @endphp



    @if($view)
        @include("adminPanelWaiter.svgMap.$view")
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

<script>
    let POSStarted = false;
    function handleExitPOSPaytec() {
        if (POSStarted) {
            $.ajax({
                url: '{{ route("payTec.AbortTransact") }}',
                method: 'post',
                data: {_token: '{{csrf_token()}}'},
                success: (res) => {
                },error: (error) => {
                }
            });
            registerPayTecErrorDataAll('User reloaded the page','{{ Auth::user()->sFor }}');
            return "Wenn Sie die Seite aktualisieren, wird die POS-Zahlung abgebrochen.";
        }
    }

    window.onbeforeunload = function () {
        if (POSStarted) {
            $.ajax({
                url: '{{ route("payTec.AbortTransact") }}',
                method: 'post',
                data: {_token: '{{csrf_token()}}'},
                success: (res) => {
                    
                },error: (error) => {

                }
            });
            registerPayTecErrorDataAll('User reloaded the page','{{ Auth::user()->sFor }}');
            return "Wenn Sie die Seite aktualisieren, wird die POS-Zahlung abgebrochen.";
            
        }
    };

    // 1. Page is being hidden
    document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "hidden") {
        handleExitPOSPaytec();
    }
    });

    // 2. Page is being unloaded (better on iOS than beforeunload)
    window.addEventListener("pagehide", handleExitPOSPaytec);
</script>

@include('adminPanelWaiter.tablePageTel.newOrderRegEl')
@if (false)
    @include('adminPanelWaiter.tablePageTel.tableIndexActiveOrMd')
@else
    @if(in_array($sFor, [3100, 56, 5000]))
        <!-- display same products together -->
        @include('adminPanelWaiter.tablePageTel.tableIndexActiveOrMdVer_2_podGroup')
    @else
        @include('adminPanelWaiter.tablePageTel.tableIndexActiveOrMdVer_2')
    @endif
@endif
@include('adminPanelWaiter.tablePageTel.tableIndexNotifications')
@include('adminPanelWaiter.tablePageTel.tablePageIndexScript')