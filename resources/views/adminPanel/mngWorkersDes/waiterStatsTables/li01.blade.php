<?php
use App\TabOrder;
use App\waiterActivityLog;
?>
    @if ((isset($_GET['f']) && ($_GET['f'] == 'dateL1A' || $_GET['f'] == 'dateL1Z' || $_GET['f'] == 'idL1A' || $_GET['f'] == 'idL1Z' 
    || $_GET['f'] == 'tableL1A' || $_GET['f'] == 'tableL1Z' || $_GET['f'] == 'prodL1A' || $_GET['f'] == 'prodL1Z' || $_GET['f'] == 'sasiaL1A' || $_GET['f'] == 'sasiaL1Z'
    || $_GET['f'] == 'priceL1A' || $_GET['f'] == 'priceL1Z')) || isset($_GET['d1L1']) )
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-3 mr-3 mt-3 mb-3" style="display:flex !important;" id="list01">
    @else
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-3 mr-3 mt-3 mb-3" style="display:none !important;" id="list01">
    @endif

        <div class="d-flex flex-wrap justify-content-around" style="width: 100%;">
            <p style="width:45%; color:rgb(39,190,175); font-size:1.6rem; margin:0px;"><strong>Bestellungen registriert</strong></p>
           
            @if(isset($_GET['d1L1']))
            <?php $d12D = explode('-',$_GET["d1L1"]); ?>
            <input style="width: 45px;" value="{{$_GET['d1L1']}}" type="date" id="li01DateOne" onchange="li01DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li01DateOneDisplay">{{$d12D[2]}}.{{$d12D[1]}}.{{$d12D[0]}}</p>
            @else
            <input style="width: 45px;" type="date" id="li01DateOne" onchange="li01DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li01DateOneDisplay">kein Datum</p>
            @endif
            
            @if(isset($_GET['d2L1']))
            <?php $d22D = explode('-',$_GET["d2L1"]); ?>
            <input style="width: 45px;" value="{{$_GET['d2L1']}}" type="date" id="li01DateTwo" onchange="li01DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li01DateTwoDisplay">{{$d22D[2]}}.{{$d22D[1]}}.{{$d22D[0]}}</p>
            @else
            <input style="width: 45px;" type="date" id="li01DateTwo" onchange="li01DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li01DateTwoDisplay">kein Datum</p>
            @endif

            @if(isset($_GET['f']))
            <?php $filterActLi01 = $_GET['f']; ?>
            <button class="btn btn-dark" style="width: 6%;" onclick="filterByDateL1('{{$theW->id}}','{{$filterActLi01}}')">Filter</button>
            @else
            <button class="btn btn-dark" style="width: 6%;" onclick="filterByDateL1('{{$theW->id}}','none')">Filter</button>
            @endif

            <div class="alert alert-danger text-center" style="display:none; font-weight:bold;" id="filterByDateL1Err01">Bitte wählen Sie zuerst ein Datum aus!</div>
            <div class="alert alert-danger text-center" style="display:none; font-weight:bold;" id="filterByDateL1Err02">Der Datumszeitraum ist nicht richtig ausgewählt!</div>
        </div>

        <script>
            function li01DateOneShow(){
                var dt = $('#li01DateOne').val();
                var dt2D = dt.split('-');
                $('#li01DateOneDisplay').html(dt2D[2]+'.'+dt2D[1]+'.'+dt2D[0]);
            }
            function li01DateTwoShow(){
                var dt = $('#li01DateTwo').val();
                var dt2D = dt.split('-');
                $('#li01DateTwoDisplay').html(dt2D[2]+'.'+dt2D[1]+'.'+dt2D[0]);
            }
            function filterByDateL1(wId, fAct){
                var d1L1 = $('#li01DateOne').val();
                var d2L1 = $('#li01DateTwo').val();

                if(fAct == 'none'){
                    if(d1L1 != '' && d2L1 != '' && d2L1 > d1L1){
                        if(d2L1 > d1L1){
                            window.location = '/admWoMngWaiterStatistics?wi='+wId+'&d1L1='+d1L1+'&d2L1='+d2L1;
                            exit();

                        }else{ if($('#filterByDateL1Err02').is(":hidden")){$('#filterByDateL1Err02').show(50).delay(4000).hide(50);} }

                    }else if(d1L1 != ''){
                        window.location = '/admWoMngWaiterStatistics?wi='+wId+'&d1L1='+d1L1;
                        exit();

                    }else{ if($('#filterByDateL1Err01').is(":hidden")){$('#filterByDateL1Err01').show(50).delay(4000).hide(50);} }
                    
                }else{
                    if(d1L1 != '' && d2L1 != ''){
                        if(d2L1 > d1L1){
                            window.location = '/admWoMngWaiterStatistics?wi='+wId+'&f='+fAct+'&d1L1='+d1L1+'&d2L1='+d2L1;
                            exit();

                        }else{ if($('#filterByDateL1Err02').is(":hidden")){$('#filterByDateL1Err02').show(50).delay(4000).hide(50);} }

                    }else if(d1L1 != ''){
                        window.location = '/admWoMngWaiterStatistics?wi='+wId+'&f='+fAct+'&d1L1='+d1L1;
                        exit();

                    }else{ if($('#filterByDateL1Err01').is(":hidden")){$('#filterByDateL1Err01').show(50).delay(4000).hide(50);} }
                }
            }
        </script>
      









        @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
            <?php $d1L1 = $_GET['d1L1']; ?>
        @elseif(isset($_GET['d2L1']))
            <?php $d1L1 = $_GET['d1L1']; $d2L1 = $_GET['d2L1'];?>
        @endif
        <div class="p-1 d-flex" style="width:15% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f']) && $_GET['f'] == 'dateL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'dateL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'dateL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'dateL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f']) && $_GET['f'] == 'dateL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'dateL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'dateL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'dateL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Datum</span>
        </div>

        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f']) && $_GET['f'] == 'idL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'idL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'idL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'idL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f']) && $_GET['f'] == 'idL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'idL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'idL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'idL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">ID</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f']) && $_GET['f'] == 'tableL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'tableL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'tableL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'tableL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f']) && $_GET['f'] == 'tableL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'tableL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'tableL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'tableL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Tisch</span>
        </div>
        <div class="p-1 d-flex" style="width:25% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f']) && $_GET['f'] == 'prodL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'prodL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'prodL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'prodL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f']) && $_GET['f'] == 'prodL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'prodL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'prodL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'prodL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Name (Typ)</span>
        </div>
        <div class="p-1 d-flex" style="width:20% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f']) && $_GET['f'] == 'extL1A')
                <a style="color:rgb(39,190,175);" disabled>-</a>
                @else
                <a style="color:black" disabled>-</a>
                @endif
                <br>
                @if(isset($_GET['f']) && $_GET['f'] == 'extL1Z')
                <a style="color:rgb(39,190,175);" disabled>-</a>
                @else
                <a style="color:black" disabled>-</a>
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Extras</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f']) && $_GET['f'] == 'sasiaL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'sasiaL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'sasiaL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'sasiaL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f']) && $_GET['f'] == 'sasiaL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'sasiaL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'sasiaL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'sasiaL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Anzahl</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f']) && $_GET['f'] == 'priceL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'priceL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'priceL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'priceL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f']) && $_GET['f'] == 'priceL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'priceL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'priceL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id, 'f'=>'priceL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Preis</span>
        </div>











        <?php
            if(isset($_GET['f'])){
                
                if($_GET['f'] == 'dateL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('waiter_activity_logs.created_at', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('waiter_activity_logs.created_at', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->orderBy('waiter_activity_logs.created_at', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'dateL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('waiter_activity_logs.created_at', 'ASC')
                                            ->get();                    
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('waiter_activity_logs.created_at', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->orderBy('waiter_activity_logs.created_at', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'idL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'idL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'tableL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.tableNr', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.tableNr', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.tableNr', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'tableL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.tableNr', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.tableNr', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.tableNr', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'prodL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderEmri', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderEmri', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderEmri', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'prodL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderEmri', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderEmri', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderEmri', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'sasiaL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderSasia', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderSasia', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderSasia', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'sasiaL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderSasia', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderSasia', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderSasia', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'priceL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderQmimi', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderQmimi', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderQmimi', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f'] == 'priceL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderQmimi', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderQmimi', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])
                                            ->join('tab_orders', 'tab_orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('tab_orders.OrderQmimi', 'ASC')
                                            ->get();
                    }
                        
                    
                }else{ 
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at', '=', $_GET['d1L1'])->get();}
                    else if(isset($_GET['d2L1'])){$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at', '>=', $_GET['d1L1'])->whereDate('created_at', '<=', $_GET['d2L1'])->get();}
                    else{$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->get();}
                }
            }else{ 
                if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at', '=', $_GET['d1L1'])->get();}
                else if(isset($_GET['d2L1'])){$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->whereDate('created_at', '>=', $_GET['d1L1'])->whereDate('created_at', '<=', $_GET['d2L1'])->get();}
                else{$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->get();}
            }
        ?>




        @if(count($li01) > 0)
            <?php
                $totQty = (int)0;
                $totSum = (float)0;
            ?>
            @foreach ($li01 as $oWAL01)
                <?php $to =TabOrder::find($oWAL01->actId); ?>
                @if($to != NULL)
                    <?php
                        $date2D = explode('-',explode(' ',$to->created_at)[0]);
                        $time2D = explode(':',explode(' ',$to->created_at)[1]);
                    ?>
                    <p class="text-center" style="width:15%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$time2D[0]}}:{{$time2D[1]}} {{$date2D[2]}}.{{$date2D[1]}}.{{$date2D[0]}}</p>
                    <p class="text-center" style="width:10%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$to->id}}</p>
                    <p class="text-center" style="width:10%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$to->tableNr}}</p>
                    <p class="text-center" style="width:25%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">
                        @if ($to->OrderType != 'empty')

                        @endif
                        {{$to->OrderEmri}}
                        @if ($to->OrderType != 'empty')
                            <?php $orTy = explode('||',$to->OrderType);?>
                            ({{$orTy[0]}})
                        @endif
                    </p>
                    <p class="text-center" style="width:20%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">
                        @if ($to->OrderExtra != 'empty')
                            @foreach (explode('--0--',$to->OrderExtra) as $oet)
                                <?php $orEx = explode('||',$oet);?>
                                @if ($loop->first)
                                    {{$orEx[0]}}
                                @else
                                    <br> {{$orEx[0]}}
                                @endif
                            @endforeach
                        
                        @endif
                    </p>
                    <p class="text-center" style="width:10%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$to->OrderSasia}}</p>
                    <p class="text-center" style="width:10%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$to->OrderQmimi}} <span style="opacity: 0.4;">CHF</span></p>
                
                    <?php
                        $totQty = $totQty + (int)$to->OrderSasia;
                        $totSum = $totSum + (float)$to->OrderQmimi;
                    ?>
                @endif
            @endforeach

            <div style="width: 100%;" class="d-flex justify-content-around mt-3">
                <p style="width: 45%; font-weight:bold; font-size:1.8rem; color:rgb(72,81,87);" class="text-center">Gesamtmenge: {{$totQty}}</p>
                <p style="width: 45%; font-weight:bold; font-size:1.8rem; color:rgb(72,81,87);" class="text-center">Gesamtpreis: {{$totSum}} CHF</p>
            </div>

        @else
            <p class="text-center" style="width:100%; margin-bottom:0px; font-weight:bold; color:red">
                Es gibt keine registrierten Protokolle
            </p>
        @endif
    </div>