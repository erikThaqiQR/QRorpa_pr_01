<?php

use App\Orders;
use App\waiterActivityLog;

?>


    @if ((isset($_GET['f3']) && ($_GET['f3'] == 'dateL3A' || $_GET['f3'] == 'dateL3Z' || $_GET['f3'] == 'idL3A' || $_GET['f3'] == 'idL3Z' 
    || $_GET['f3'] == 'tableL3A' || $_GET['f3'] == 'tableL3Z' || $_GET['f3'] == 'prodL3A' || $_GET['f3'] == 'prodL3Z' || $_GET['f3'] == 'sasiaL3A' || $_GET['f3'] == 'sasiaL3Z'
    || $_GET['f3'] == 'priceL3A' || $_GET['f3'] == 'priceL3Z')) || isset($_GET['d1L3']))
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-3 mr-3 mt-3 mb-3" style="display:flex !important;" id="list03">
    @else
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-3 mr-3 mt-3 mb-3" style="display:none !important;" id="list03">
    @endif


        <div class="d-flex justify-content-around" style="width: 100%;">
            <p style="width:45%; color:rgb(39,190,175); font-size:1.6rem; margin:0px;"><strong>Bestellungen Annulliert</strong></p>

            @if(isset($_GET['d1L3']))
            <?php $d12D = explode('-',$_GET["d1L3"]); ?>
            <input style="width: 45px; font-size:" value="{{$_GET['d1L3']}}" type="date" id="li03DateOne" onchange="li03DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li03DateOneDisplay">{{$d12D[2]}}.{{$d12D[1]}}.{{$d12D[0]}}</p>
            @else
            <input style="width: 45px;" type="date" id="li03DateOne" onchange="li03DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li03DateOneDisplay">kein Datum</p>
            @endif
            
            @if(isset($_GET['d2L3']))
            <?php $d22D = explode('-',$_GET["d2L3"]); ?>
            <input style="width: 45px;" value="{{$_GET['d2L3']}}" type="date" id="li03DateTwo" onchange="li03DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li03DateTwoDisplay">{{$d22D[2]}}.{{$d22D[1]}}.{{$d22D[0]}}</p>
            @else
            <input style="width: 45px;" type="date" id="li03DateTwo" onchange="li03DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li03DateTwoDisplay">kein Datum</p>
            @endif

            @if(isset($_GET['f3']))
            <?php $filterActli03 = $_GET['f3']; ?>
            <button class="btn btn-dark" style="width: 6%;" onclick="filterByDateL3('{{$theW->id}}','{{$filterActli03}}')">Filter</button>
            @else
            <button class="btn btn-dark" style="width: 6%;" onclick="filterByDateL3('{{$theW->id}}','none')">Filter</button>
            @endif

            <div class="alert alert-danger text-center" style="display:none; font-weight:bold;" id="filterByDateL3Err01">Bitte wählen Sie zuerst ein Datum aus!</div>
            <div class="alert alert-danger text-center" style="display:none; font-weight:bold;" id="filterByDateL3Err02">Der Datumszeitraum ist nicht richtig ausgewählt!</div>
        </div>

        <script>
            function li03DateOneShow(){
                var dt = $('#li03DateOne').val();
                var dt2D = dt.split('-');
                $('#li03DateOneDisplay').html(dt2D[2]+'.'+dt2D[1]+'.'+dt2D[0]);
            }
            function li03DateTwoShow(){
                var dt = $('#li03DateTwo').val();
                var dt2D = dt.split('-');
                $('#li03DateTwoDisplay').html(dt2D[2]+'.'+dt2D[1]+'.'+dt2D[0]);
            }
            function filterByDateL3(wId, fAct){
                var d1L3 = $('#li03DateOne').val();
                var d2L3 = $('#li03DateTwo').val();

                if(fAct == 'none'){
                    if(d1L3 != '' && d2L3 != '' && d2L3 > d1L3){
                        if(d2L3 > d1L3){
                            window.location = '/admWoMngWaiterStatisticsD?wi='+wId+'&d1L3='+d1L3+'&d2L3='+d2L3;
                            exit();

                        }else{ if($('#filterByDateL3Err02').is(":hidden")){$('#filterByDateL3Err02').show(50).delay(4000).hide(50);} }

                    }else if(d1L3 != ''){
                        window.location = '/admWoMngWaiterStatisticsD?wi='+wId+'&d1L3='+d1L3;
                        exit();

                    }else{ if($('#filterByDateL3Err01').is(":hidden")){$('#filterByDateL3Err01').show(50).delay(4000).hide(50);} }
                    
                }else{
                    if(d1L3 != '' && d2L3 != ''){
                        if(d2L3 > d1L3){
                            window.location = '/admWoMngWaiterStatisticsD?wi='+wId+'&f3='+fAct+'&d1L3='+d1L3+'&d2L3='+d2L3;
                            exit();

                        }else{ if($('#filterByDateL3Err02').is(":hidden")){$('#filterByDateL3Err02').show(50).delay(4000).hide(50);} }

                    }else if(d1L3 != ''){
                        window.location = '/admWoMngWaiterStatisticsD?wi='+wId+'&f3='+fAct+'&d1L3='+d1L3;
                        exit();

                    }else{ if($('#filterByDateL3Err01').is(":hidden")){$('#filterByDateL3Err01').show(50).delay(4000).hide(50);} }
                }
            }
        </script>
      








        @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
            <?php $d1L3 = $_GET['d1L3']; ?>
        @elseif(isset($_GET['d2L3']))
            <?php $d1L3 = $_GET['d1L3']; $d2L3 = $_GET['d2L3'];?>
        @endif
        <div class="p-1 d-flex" style="width:15% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f3']) && $_GET['f3'] == 'dateL3A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'dateL3A', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'dateL3A', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'dateL3A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f3']) && $_GET['f3'] == 'dateL3Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'dateL3Z', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'dateL3Z', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'dateL3Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Datum</span>
        </div>

        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f3']) && $_GET['f3'] == 'idL3A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'idL3A', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'idL3A', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'idL3A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f3']) && $_GET['f3'] == 'idL3Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'idL3Z', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'idL3Z', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'idL3Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">ID</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f3']) && $_GET['f3'] == 'tableL3A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'tableL3A', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'tableL3A', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'tableL3A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f3']) && $_GET['f3'] == 'tableL3Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'tableL3Z', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'tableL3Z', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'tableL3Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Tisch</span>
        </div>
        <div class="p-1 d-flex" style="width:45% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f3']) && $_GET['f3'] == 'prodL3A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'prodL3A', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'prodL3A', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'prodL3A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f3']) && $_GET['f3'] == 'prodL3Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'prodL3Z', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'prodL3Z', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'prodL3Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Produktnamen (Typ) / Extras</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f3']) && $_GET['f3'] == 'sasiaL3A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'sasiaL3A', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'sasiaL3A', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'sasiaL3A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f3']) && $_GET['f3'] == 'sasiaL3Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'sasiaL3Z', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'sasiaL3Z', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'sasiaL3Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Anzahl</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f3']) && $_GET['f3'] == 'priceL3A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'priceL3A', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'priceL3A', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'priceL3A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f3']) && $_GET['f3'] == 'priceL3Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L3']) && !isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'priceL3Z', 'd1L3'=>$d1L3])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L3']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'priceL3Z', 'd1L3'=>$d1L3, 'd2L3'=>$d2L3])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f3'=>'priceL3Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Preis</span>
        </div>









        <?php
            if(isset($_GET['f3'])){
                if($_GET['f3'] == 'dateL3A'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'dateL3Z'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'idL3A'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'idL3Z'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }
                        

                }else if($_GET['f3'] == 'tableL3A'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'tableL3Z'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'prodL3A'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'prodL3Z'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'sasiaL3A'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'sasiaL3Z'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'priceL3A'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f3'] == 'priceL3Z'){
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L3'])){
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L3'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L3'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }else{
                        $li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }
                          
                    
                }else{ 
                    if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){$li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at', '=', $_GET['d1L3'])->get(); }
                    else if(isset($_GET['d2L3'])){$li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at', '>=', $_GET['d1L3'])->whereDate('created_at', '<=', $_GET['d2L3'])->get(); }
                    else{$li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->get(); }
                }
            }else{ 
                if(isset($_GET['d1L3']) && !isset($_GET['d2L3'])){$li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at', '=', $_GET['d1L3'])->get(); }
                else if(isset($_GET['d2L3'])){$li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->whereDate('created_at', '>=', $_GET['d1L3'])->whereDate('created_at', '<=', $_GET['d2L3'])->get(); }
                else{$li03 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->get(); }
                
            }
        ?>





        @if(count($li03) > 0)
            <?php
                $totQty = (int)0;
                $totSum = (float)0;
            ?>
            @foreach ($li03 as $oWAL03)
                <?php $or =Orders::find($oWAL03->actId); ?>
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
