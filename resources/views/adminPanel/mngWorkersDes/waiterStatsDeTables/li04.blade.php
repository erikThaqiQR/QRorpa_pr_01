<?php

use App\Orders;
use App\waiterActivityLog;

?>


    @if ((isset($_GET['f4']) && ($_GET['f4'] == 'dateL4A' || $_GET['f4'] == 'dateL4Z' || $_GET['f4'] == 'idL4A' || $_GET['f4'] == 'idL4Z' 
    || $_GET['f4'] == 'tableL4A' || $_GET['f4'] == 'tableL4Z' || $_GET['f4'] == 'prodL4A' || $_GET['f4'] == 'prodL4Z' || $_GET['f4'] == 'sasiaL4A' || $_GET['f4'] == 'sasiaL4Z'
    || $_GET['f4'] == 'priceL4A' || $_GET['f4'] == 'priceL4Z')) || isset($_GET['d1L4']))
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-3 mr-3 mt-3 mb-3" style="display:flex !important;" id="list04">
    @else
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-3 mr-3 mt-3 mb-3" style="display:none !important;" id="list04">
    @endif

        <div class="d-flex justify-content-around" style="width: 100%;">
            <p style="width:45%; color:rgb(39,190,175); font-size:1.6rem; margin:0px;"><strong>Bestellungen Abgeschlossen</strong></p>

            @if(isset($_GET['d1L4']))
            <?php $d12D = explode('-',$_GET["d1L4"]); ?>
            <input style="width: 45px; font-size:" value="{{$_GET['d1L4']}}" type="date" id="li04DateOne" onchange="li04DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li04DateOneDisplay">{{$d12D[2]}}.{{$d12D[1]}}.{{$d12D[0]}}</p>
            @else
            <input style="width: 45px;" type="date" id="li04DateOne" onchange="li04DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li04DateOneDisplay">kein Datum</p>
            @endif
            
            @if(isset($_GET['d2L4']))
            <?php $d22D = explode('-',$_GET["d2L4"]); ?>
            <input style="width: 45px;" value="{{$_GET['d2L4']}}" type="date" id="li04DateTwo" onchange="li04DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li04DateTwoDisplay">{{$d22D[2]}}.{{$d22D[1]}}.{{$d22D[0]}}</p>
            @else
            <input style="width: 45px;" type="date" id="li04DateTwo" onchange="li04DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 17% ; padding-top:7px; font-weight:bold;" id="li04DateTwoDisplay">kein Datum</p>
            @endif

            @if(isset($_GET['f4']))
            <?php $filterActli04 = $_GET['f4']; ?>
            <button class="btn btn-dark" style="width: 6%;" onclick="filterByDateL4('{{$theW->id}}','{{$filterActli04}}')">Filter</button>
            @else
            <button class="btn btn-dark" style="width: 6%;" onclick="filterByDateL4('{{$theW->id}}','none')">Filter</button>
            @endif

            <div class="alert alert-danger text-center" style="display:none; font-weight:bold;" id="filterByDateL4Err01">Bitte wählen Sie zuerst ein Datum aus!</div>
            <div class="alert alert-danger text-center" style="display:none; font-weight:bold;" id="filterByDateL4Err02">Der Datumszeitraum ist nicht richtig ausgewählt!</div>
        </div>

        <script>
            function li04DateOneShow(){
                var dt = $('#li04DateOne').val();
                var dt2D = dt.split('-');
                $('#li04DateOneDisplay').html(dt2D[2]+'.'+dt2D[1]+'.'+dt2D[0]);
            }
            function li04DateTwoShow(){
                var dt = $('#li04DateTwo').val();
                var dt2D = dt.split('-');
                $('#li04DateTwoDisplay').html(dt2D[2]+'.'+dt2D[1]+'.'+dt2D[0]);
            }
            function filterByDateL4(wId, fAct){
                var d1L4 = $('#li04DateOne').val();
                var d2L4 = $('#li04DateTwo').val();

                if(fAct == 'none'){
                    if(d1L4 != '' && d2L4 != '' && d2L4 > d1L4){
                        if(d2L4 > d1L4){
                            window.location = '/admWoMngWaiterStatisticsD?wi='+wId+'&d1L4='+d1L4+'&d2L4='+d2L4;
                            exit();

                        }else{ if($('#filterByDateL4Err02').is(":hidden")){$('#filterByDateL4Err02').show(50).delay(4000).hide(50);} }

                    }else if(d1L4 != ''){
                        window.location = '/admWoMngWaiterStatisticsD?wi='+wId+'&d1L4='+d1L4;
                        exit();

                    }else{ if($('#filterByDateL4Err01').is(":hidden")){$('#filterByDateL4Err01').show(50).delay(4000).hide(50);} }
                    
                }else{
                    if(d1L4 != '' && d2L4 != ''){
                        if(d2L4 > d1L4){
                            window.location = '/admWoMngWaiterStatisticsD?wi='+wId+'&f4='+fAct+'&d1L4='+d1L4+'&d2L4='+d2L4;
                            exit();

                        }else{ if($('#filterByDateL4Err02').is(":hidden")){$('#filterByDateL4Err02').show(50).delay(4000).hide(50);} }

                    }else if(d1L4 != ''){
                        window.location = '/admWoMngWaiterStatisticsD?wi='+wId+'&f4='+fAct+'&d1L4='+d1L4;
                        exit();

                    }else{ if($('#filterByDateL4Err01').is(":hidden")){$('#filterByDateL4Err01').show(50).delay(4000).hide(50);} }
                }
            }
        </script>
      







        @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
            <?php $d1L4 = $_GET['d1L4']; ?>
        @elseif(isset($_GET['d2L4']))
            <?php $d1L4 = $_GET['d1L4']; $d2L4 = $_GET['d2L4'];?>
        @endif
        <div class="p-1 d-flex" style="width:15% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f4']) && $_GET['f4'] == 'dateL4A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'dateL4A', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'dateL4A', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'dateL4A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f4']) && $_GET['f4'] == 'dateL4Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'dateL4Z', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'dateL4Z', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'dateL4Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Datum</span>
        </div>

        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f4']) && $_GET['f4'] == 'idL4A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'idL4A', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'idL4A', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'idL4A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f4']) && $_GET['f4'] == 'idL4Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'idL4Z', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'idL4Z', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'idL4Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">ID</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f4']) && $_GET['f4'] == 'tableL4A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'tableL4A', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'tableL4A', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'tableL4A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f4']) && $_GET['f4'] == 'tableL4Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'tableL4Z', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'tableL4Z', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'tableL4Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Tisch</span>
        </div>
        <div class="p-1 d-flex" style="width:45% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f4']) && $_GET['f4'] == 'prodL4A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'prodL4A', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'prodL4A', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'prodL4A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f4']) && $_GET['f4'] == 'prodL4Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'prodL4Z', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'prodL4Z', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'prodL4Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Produktnamen (Typ) / Extras</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f4']) && $_GET['f4'] == 'sasiaL4A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'sasiaL4A', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'sasiaL4A', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'sasiaL4A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f4']) && $_GET['f4'] == 'sasiaL4Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'sasiaL4Z', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'sasiaL4Z', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'sasiaL4Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Anzahl</span>
        </div>
        <div class="p-1 d-flex" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 20%">
                @if(isset($_GET['f4']) && $_GET['f4'] == 'priceL4A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'priceL4A', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'priceL4A', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'priceL4A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f4']) && $_GET['f4'] == 'priceL4Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L4']) && !isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'priceL4Z', 'd1L4'=>$d1L4])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L4']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'priceL4Z', 'd1L4'=>$d1L4, 'd2L4'=>$d2L4])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id, 'f4'=>'priceL4Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 80%">Preis</span>
        </div>









        <?php
            if(isset($_GET['f4'])){
                if($_GET['f4'] == 'dateL4A'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'dateL4Z'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'idL4A'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'idL4Z'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }
                        

                }else if($_GET['f4'] == 'tableL4A'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'tableL4Z'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'prodL4A'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'prodL4Z'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'sasiaL4A'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'sasiaL4Z'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'priceL4A'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f4'] == 'priceL4Z'){
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L4'])){
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L4'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L4'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }else{
                        $li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }
                          
                    
                }else{ 
                    if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){$li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at', '=', $_GET['d1L4'])->get(); }
                    else if(isset($_GET['d2L4'])){$li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at', '>=', $_GET['d1L4'])->whereDate('created_at', '<=', $_GET['d2L4'])->get(); }
                    else{$li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->get(); }
                }
            }else{ 
                if(isset($_GET['d1L4']) && !isset($_GET['d2L4'])){$li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at', '=', $_GET['d1L4'])->get(); }
                else if(isset($_GET['d2L4'])){$li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->whereDate('created_at', '>=', $_GET['d1L4'])->whereDate('created_at', '<=', $_GET['d2L4'])->get(); }
                else{$li04 = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->get(); }
                
            }
        ?>


        @if(count($li04) > 0)
            <?php
                $totQty = (int)0;
                $totSum = (float)0;
            ?>
            @foreach ($li04 as $oWAL04)
                <?php $or =Orders::find($oWAL04->actId); ?>
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
