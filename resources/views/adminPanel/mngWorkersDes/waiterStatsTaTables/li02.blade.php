<?php

use App\Orders;
use App\waiterActivityLog;

?>


    @if ((isset($_GET['f2']) && ($_GET['f2'] == 'dateL2A' || $_GET['f2'] == 'dateL2Z' || $_GET['f2'] == 'idL2A' || $_GET['f2'] == 'idL2Z' 
    || $_GET['f2'] == 'tableL2A' || $_GET['f2'] == 'tableL2Z' || $_GET['f2'] == 'prodL2A' || $_GET['f2'] == 'prodL2Z' || $_GET['f2'] == 'sasiaL2A' || $_GET['f2'] == 'sasiaL2Z'
    || $_GET['f2'] == 'priceL2A' || $_GET['f2'] == 'priceL2Z')) || isset($_GET['d1L2']))
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-3 mr-3 mt-3 mb-3" style="display:flex !important;" id="list02">
    @else
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-3 mr-3 mt-3 mb-3" style="display:none !important;" id="list02">
    @endif

        <div class="d-flex justify-content-around" style="width: 100%;">
            <p style="width:45%; color:rgb(39,190,175); font-size:1.6rem; margin:0px;"><strong>Bestellungen Bestätigt</strong></p>

            @if(isset($_GET['d1L2']))
            <?php $d12D = explode('-',$_GET["d1L2"]); ?>
            <input style="width: 45px; font-size:" value="{{$_GET['d1L2']}}" type="date" id="li02DateOne" onchange="li02DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li02DateOneDisplay">{{$d12D[2]}}.{{$d12D[1]}}.{{$d12D[0]}}</p>
            @else
            <input style="width: 45px;" type="date" id="li02DateOne" onchange="li02DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li02DateOneDisplay">kein Datum</p>
            @endif
            
            @if(isset($_GET['d2L2']))
            <?php $d22D = explode('-',$_GET["d2L2"]); ?>
            <input style="width: 45px;" value="{{$_GET['d2L2']}}" type="date" id="li02DateTwo" onchange="li02DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li02DateTwoDisplay">{{$d22D[2]}}.{{$d22D[1]}}.{{$d22D[0]}}</p>
            @else
            <input style="width: 45px;" type="date" id="li02DateTwo" onchange="li02DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li02DateTwoDisplay">kein Datum</p>
            @endif

            @if(isset($_GET['f2']))
            <?php $filterActli02 = $_GET['f2']; ?>
            <button class="btn btn-dark" style="width: 6%;" onclick="filterByDateL2('{{$theW->id}}','{{$filterActli02}}')">Filter</button>
            @else
            <button class="btn btn-dark" style="width: 6%;" onclick="filterByDateL2('{{$theW->id}}','none')">Filter</button>
            @endif

            <div class="alert alert-danger text-center" style="display:none; font-weight:bold;" id="filterByDateL2Err01">Bitte wählen Sie zuerst ein Datum aus!</div>
            <div class="alert alert-danger text-center" style="display:none; font-weight:bold;" id="filterByDateL2Err02">Der Datumszeitraum ist nicht richtig ausgewählt!</div>
        </div>

        <script>
            function li02DateOneShow(){
                var dt = $('#li02DateOne').val();
                var dt2D = dt.split('-');
                $('#li02DateOneDisplay').html(dt2D[2]+'.'+dt2D[1]+'.'+dt2D[0]);
            }
            function li02DateTwoShow(){
                var dt = $('#li02DateTwo').val();
                var dt2D = dt.split('-');
                $('#li02DateTwoDisplay').html(dt2D[2]+'.'+dt2D[1]+'.'+dt2D[0]);
            }
            function filterByDateL2(wId, fAct){
                var d1L2 = $('#li02DateOne').val();
                var d2L2 = $('#li02DateTwo').val();

                if(fAct == 'none'){
                    if(d1L2 != '' && d2L2 != '' && d2L2 > d1L2){
                        if(d2L2 > d1L2){
                            window.location = '/admWoMngWaiterStatisticsT?wi='+wId+'&d1L2='+d1L2+'&d2L2='+d2L2;
                            exit();
                        }else{ if($('#filterByDateL2Err02').is(":hidden")){$('#filterByDateL2Err02').show(50).delay(4000).hide(50);} }
                    }else if(d1L2 != ''){
                        window.location = '/admWoMngWaiterStatisticsT?wi='+wId+'&d1L2='+d1L2;
                        exit();
                    }else{ if($('#filterByDateL2Err01').is(":hidden")){$('#filterByDateL2Err01').show(50).delay(4000).hide(50);} }
                }else{
                    if(d1L2 != '' && d2L2 != ''){
                        if(d2L2 > d1L2){
                            window.location = '/admWoMngWaiterStatisticsT?wi='+wId+'&f2='+fAct+'&d1L2='+d1L2+'&d2L2='+d2L2;
                            exit();
                        }else{ if($('#filterByDateL2Err02').is(":hidden")){$('#filterByDateL2Err02').show(50).delay(4000).hide(50);} }
                    }else if(d1L2 != ''){
                        window.location = '/admWoMngWaiterStatisticsT?wi='+wId+'&f2='+fAct+'&d1L2='+d1L2;
                        exit();
                    }else{ if($('#filterByDateL2Err01').is(":hidden")){$('#filterByDateL2Err01').show(50).delay(4000).hide(50);} }
                }
            }
        </script>






      
        @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
            <?php $d1L2 = $_GET['d1L2']; ?>
        @elseif(isset($_GET['d2L2']))
            <?php $d1L2 = $_GET['d1L2']; $d2L2 = $_GET['d2L2'];?>
        @endif
        <div class="p-1 d-flex" style="width:15% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f2']) && $_GET['f2'] == 'dateL2A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'dateL2A', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'dateL2A', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'dateL2A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f2']) && $_GET['f2'] == 'dateL2Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'dateL2Z', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'dateL2Z', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'dateL2Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Datum</span>
        </div>

        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f2']) && $_GET['f2'] == 'idL2A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'idL2A', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'idL2A', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'idL2A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f2']) && $_GET['f2'] == 'idL2Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'idL2Z', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'idL2Z', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'idL2Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">ID</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f2']) && $_GET['f2'] == 'tableL2A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'tableL2A', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'tableL2A', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'tableL2A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f2']) && $_GET['f2'] == 'tableL2Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'tableL2Z', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'tableL2Z', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'tableL2Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Tisch</span>
        </div>
        <div class="p-1 d-flex" style="width:45% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f2']) && $_GET['f2'] == 'prodL2A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'prodL2A', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'prodL2A', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'prodL2A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f2']) && $_GET['f2'] == 'prodL2Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'prodL2Z', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'prodL2Z', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'prodL2Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Produktnamen (Typ) / Extras</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f2']) && $_GET['f2'] == 'sasiaL2A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'sasiaL2A', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'sasiaL2A', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'sasiaL2A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f2']) && $_GET['f2'] == 'sasiaL2Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'sasiaL2Z', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'sasiaL2Z', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'sasiaL2Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Anzahl</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f2']) && $_GET['f2'] == 'priceL2A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'priceL2A', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'priceL2A', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'priceL2A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f2']) && $_GET['f2'] == 'priceL2Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L2']) && !isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'priceL2Z', 'd1L2'=>$d1L2])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L2']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'priceL2Z', 'd1L2'=>$d1L2, 'd2L2'=>$d2L2])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f2'=>'priceL2Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Preis</span>
        </div>












        <?php
            if(isset($_GET['f2'])){
                if($_GET['f2'] == 'dateL2A'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'dateL2Z'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'idL2A'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'idL2Z'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }
                        

                }else if($_GET['f2'] == 'tableL2A'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'tableL2Z'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'prodL2A'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'prodL2Z'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'sasiaL2A'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'sasiaL2Z'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'priceL2A'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f2'] == 'priceL2Z'){
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L2'])){
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L2'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L2'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }else{
                        $li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }
                          
                    
                }else{ 
                    if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){$li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])->whereDate('created_at', '=', $_GET['d1L2'])->get(); }
                    else if(isset($_GET['d2L2'])){$li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])->whereDate('created_at', '>=', $_GET['d1L2'])->whereDate('created_at', '<=', $_GET['d2L2'])->get(); }
                    else{$li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])->get(); }
                }
            }else{ 
                if(isset($_GET['d1L2']) && !isset($_GET['d2L2'])){$li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])->whereDate('created_at', '=', $_GET['d1L2'])->get(); }
                else if(isset($_GET['d2L2'])){$li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])->whereDate('created_at', '>=', $_GET['d1L2'])->whereDate('created_at', '<=', $_GET['d2L2'])->get(); }
                else{$li02 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng1']])->get(); }
                
            }
        ?>





        @if(count($li02) > 0)
            <?php
                $totQty = (int)0;
                $totSum = (float)0;
            ?>
            @foreach ($li02 as $oWAL02)
                <?php $or =Orders::find($oWAL02->actId); ?>
                @if ($or != NULL)
                    <?php
                        $date2D = explode('-',explode(' ',$or->created_at)[0]);
                        $time2D = explode(':',explode(' ',$or->created_at)[1]);
                        $orCount = (int)0;
                    ?>
                    <p class="text-center" style="width:15%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$time2D[0]}}:{{$time2D[1]}} {{$date2D[2]}}.{{$date2D[1]}}.{{$date2D[0]}}</p>
                    <p class="text-center" style="width:10%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$or->id}}</p>
                    <p class="text-center" style="width:10%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$or->nrTable}}</p>
                    <p class="text-center" style="width:45%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">
                        @foreach (explode('---8---',$or->porosia) as $onePor)
                            <?php $onePor2D = explode('-8-',$onePor);?>
                            @if($onePor2D[3] > 1)
                                <strong class="mr-1">{{$onePor2D[3]}}x</strong>
                            @endif
                            {{$onePor2D[0]}}
                            @if($onePor2D[5] != 'empty' && $onePor2D[5] != '')
                                ({{$onePor2D[5]}})
                            @endif
                            @if($onePor2D[2] != 'empty' && $onePor2D[2] != '')
                                <br>
                                <span style="opacity: 0.6; margin-top:-5px; margin-bottom: 10px;">
                                    Extras: 
                                    @foreach (explode('--0--',$onePor2D[2]) as $oneEx)
                                        <?php $oneEx2D = explode('||',$oneEx);?>
                                        @if ($loop->first)
                                            {{$oneEx2D[0]}}
                                        @else
                                            , {{$oneEx2D[0]}}
                                        @endif
                                    @endforeach
                                </span>
                            @endif
                            <br>
                            <?php $orCount = $orCount + (int)$onePor2D[3]; ?>
                        @endforeach 
                    </p>
                    <p class="text-center" style="width:10%;  margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$orCount}}</p>
                    <p class="text-center" style="width:10%;  margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$or->shuma}} <span style="opacity: 0.4;">CHF</span></p>
                    <?php
                        $totQty = $totQty + (int)$orCount;
                        $totSum = $totSum + (float)$or->shuma;
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
