<?php

use App\Orders;
use App\waiterActivityLog;

?>


    @if ((isset($_GET['f1']) && ($_GET['f1'] == 'dateL1A' || $_GET['f1'] == 'dateL1Z' || $_GET['f1'] == 'idL1A' || $_GET['f1'] == 'idL1Z' 
    || $_GET['f1'] == 'prodL1A' || $_GET['f1'] == 'prodL1Z' || $_GET['f1'] == 'sasiaL1A' || $_GET['f1'] == 'sasiaL1Z' || $_GET['f1'] == 'priceL1A' || $_GET['f1'] == 'priceL1Z')) 
    || isset($_GET['d1L1']))
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-1 mr-1 mt-3 mb-3" style="display:flex !important;" id="list01">
    @else
    <div class="d-flex flex-wrap justify-content-between shadow h-100 py-2 ml-1 mr-1 mt-3 mb-3" style="display:none !important;" id="list01">
    @endif

        <div class="d-flex flex-wrap justify-content-around" style="width: 100%;">
            <p class="text-center" style="width:100%; color:rgb(39,190,175); font-size:1.2rem; margin:0px;"><strong>Bestellungen Annehmen</strong></p>
           
            @if(isset($_GET['d1L1']))
            <?php $d12D = explode('-',$_GET["d1L1"]); ?>
            <input style="width: 25px; padding:4px;" value="{{$_GET['d1L1']}}" type="date" id="li01DateOne" onchange="li01DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 29% ; padding-top:7px; font-weight:bold;" id="li01DateOneDisplay">{{$d12D[2]}}.{{$d12D[1]}}.{{$d12D[0]}}</p>
            @else
            <input style="width: 25px; padding:4px;" type="date" id="li01DateOne" onchange="li01DateOneShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 29% ; padding-top:7px; font-weight:bold;" id="li01DateOneDisplay">kein Datum</p>
            @endif
            
            @if(isset($_GET['d2L1']))
            <?php $d22D = explode('-',$_GET["d2L1"]); ?>
            <input style="width: 25px; padding:4px;" value="{{$_GET['d2L1']}}" type="date" id="li01DateTwo" onchange="li01DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 29% ; padding-top:7px; font-weight:bold;" id="li01DateTwoDisplay">{{$d22D[2]}}.{{$d22D[1]}}.{{$d22D[0]}}</p>
            @else
            <input style="width: 25px; padding:4px;" type="date" id="li01DateTwo" onchange="li01DateTwoShow()" class="form-control shadow-none">
            <p class="ml-1" style="width: 29% ; padding-top:7px; font-weight:bold;" id="li01DateTwoDisplay">kein Datum</p>
            @endif

            @if(isset($_GET['f']))
            <?php $filterActli01 = $_GET['f']; ?>
            <button class="btn btn-dark" style="width: 8%; padding:0px;" onclick="filterByDateL1('{{$theW->id}}','{{$filterActli01}}')"><i class="fas fa-filter"></i></button>
            @else
            <button class="btn btn-dark" style="width: 8%; padding:0px;" onclick="filterByDateL1('{{$theW->id}}','none')"><i class="fas fa-filter"></i></button>
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
                            window.location = '/admWoMngWaiterStatisticsT?wi='+wId+'&d1L1='+d1L1+'&d2L1='+d2L1;
                            exit();

                        }else{ if($('#filterByDateL1Err02').is(":hidden")){$('#filterByDateL1Err02').show(50).delay(4000).hide(50);} }

                    }else if(d1L1 != ''){
                        window.location = '/admWoMngWaiterStatisticsT?wi='+wId+'&d1L1='+d1L1;
                        exit();

                    }else{ if($('#filterByDateL1Err01').is(":hidden")){$('#filterByDateL1Err01').show(50).delay(4000).hide(50);} }
                    
                }else{
                    if(d1L1 != '' && d2L1 != ''){
                        if(d2L1 > d1L1){
                            window.location = '/admWoMngWaiterStatisticsT?wi='+wId+'&f='+fAct+'&d1L1='+d1L1+'&d2L1='+d2L1;
                            exit();

                        }else{ if($('#filterByDateL1Err02').is(":hidden")){$('#filterByDateL1Err02').show(50).delay(4000).hide(50);} }

                    }else if(d1L1 != ''){
                        window.location = '/admWoMngWaiterStatisticsT?wi='+wId+'&f='+fAct+'&d1L1='+d1L1;
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
        <div class="p-1 d-flex flex-wrap telTabP" style="width:15% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 100%">
                @if(isset($_GET['f1']) && $_GET['f1'] == 'dateL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'dateL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'dateL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'dateL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f1']) && $_GET['f1'] == 'dateL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'dateL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'dateL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'dateL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 100%">Datum</span>
        </div>
        <div class="p-1 d-flex flex-wrap telTabP" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 100%">
                @if(isset($_GET['f1']) && $_GET['f1'] == 'idL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'idL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'idL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'idL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f1']) && $_GET['f1'] == 'idL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'idL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'idL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'idL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 100%">ID</span>
        </div>
        <div class="p-1 d-flex flex-wrap telTabP" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 100%">
                @if(isset($_GET['f1']) && $_GET['f1'] == 'tableL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'tableL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'tableL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'tableL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f1']) && $_GET['f1'] == 'tableL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'tableL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'tableL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'tableL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 100%">Tisch</span>
        </div>
        <div class="p-1 d-flex flex-wrap telTabP" style="width:45% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 100%">
                @if(isset($_GET['f1']) && $_GET['f1'] == 'prodL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'prodL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'prodL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'prodL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f1']) && $_GET['f1'] == 'prodL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'prodL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'prodL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'prodL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 100%">Produktnamen (Typ) / Extras</span>
        </div>
        <div class="p-1 d-flex flex-wrap telTabP" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 100%">
                @if(isset($_GET['f1']) && $_GET['f1'] == 'sasiaL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'sasiaL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'sasiaL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'sasiaL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f1']) && $_GET['f1'] == 'sasiaL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'sasiaL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'sasiaL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'sasiaL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 100%">Anzahl</span>
        </div>
        <div class="p-1 d-flex flex-wrap telTabP" style="width:10% ; border-bottom:1px solid rgb(39,190,175); font-weight:bold;">
            <div class="text-center" style="width: 100%">
                @if(isset($_GET['f1']) && $_GET['f1'] == 'priceL1A')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-up"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'priceL1A', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-up"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'priceL1A', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-up"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'priceL1A'])}}"><i class="fas fa-angle-up"></i></a>
                    @endif
                @endif
                <br>
                @if(isset($_GET['f1']) && $_GET['f1'] == 'priceL1Z')
                <a style="color:rgb(39,190,175);"><i class="fas fa-angle-down"></i></a>
                @else
                    @if(isset($_GET['d1L1']) && !isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'priceL1Z', 'd1L1'=>$d1L1])}}"><i class="fas fa-angle-down"></i></a>
                    @elseif(isset($_GET['d2L1']))
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'priceL1Z', 'd1L1'=>$d1L1, 'd2L1'=>$d2L1])}}"><i class="fas fa-angle-down"></i></a>
                    @else
                    <a style="color:black" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id, 'f1'=>'priceL1Z'])}}"><i class="fas fa-angle-down"></i></a>
                    @endif
                @endif
            </div>
            <span class="ml-2 pt-2" style="width: 100%">Preis</span>
        </div>



        <?php
            if(isset($_GET['f1'])){
                if($_GET['f1'] == 'dateL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'dateL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->orderBy('created_at', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'idL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->orderBy('actId', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'idL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->orderBy('actId', 'ASC')
                                            ->get();
                    }
                        

                }else if($_GET['f1'] == 'tableL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'tableL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.nrTable', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'prodL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'prodL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.porosia', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'sasiaL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->orderBy('sasia', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'sasiaL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->orderBy('sasia', 'ASC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'priceL1A'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'DESC')
                                            ->get();
                    }


                }else if($_GET['f1'] == 'priceL1Z'){
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '=', $_GET['d1L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }else if(isset($_GET['d2L1'])){
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->whereDate('waiter_activity_logs.created_at', '>=', $_GET['d1L1'])
                                            ->whereDate('waiter_activity_logs.created_at', '<=', $_GET['d2L1'])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }else{
                        $li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])
                                            ->join('orders', 'orders.id', '=', 'waiter_activity_logs.actId')
                                            ->select('waiter_activity_logs.*')
                                            ->orderBy('orders.shuma', 'ASC')
                                            ->get();
                    }
                          
                    
                }else{ 
                    if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])->whereDate('created_at', '=', $_GET['d1L1'])->get(); }
                    else if(isset($_GET['d2L1'])){$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])->whereDate('created_at', '>=', $_GET['d1L1'])->whereDate('created_at', '<=', $_GET['d2L1'])->get(); }
                    else{$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])->get(); }
                }
            }else{ 
                if(isset($_GET['d1L1']) && !isset($_GET['d2L1'])){$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])->whereDate('created_at', '=', $_GET['d1L1'])->get(); }
                else if(isset($_GET['d2L1'])){$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])->whereDate('created_at', '>=', $_GET['d1L1'])->whereDate('created_at', '<=', $_GET['d2L1'])->get(); }
                else{$li01 = waiterActivityLog::where([['waiterId',$theW->id],['actType','takeawayOrStatChng0']])->get(); }
                
            }
        ?>





        @if(count($li01) > 0)
            <?php
                $totQty = (int)0;
                $totSum = (float)0;
            ?>
            @foreach ($li01 as $oWAL01)
                <?php $or =Orders::find($oWAL01->actId); ?>
                @if ($or != NULL)
                    <?php
                        $date2D = explode('-',explode(' ',$or->created_at)[0]);
                        $time2D = explode(':',explode(' ',$or->created_at)[1]);
                        $orCount = (int)0;
                    ?>
                    <p class="telTabP text-center" style="width:17%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$time2D[0]}}:{{$time2D[1]}} {{$date2D[2]}}.{{$date2D[1]}}.{{$date2D[0]}}</p>
                    <p class="telTabP text-center" style="width:12%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$or->id}}</p>
                    <p class="telTabP text-center" style="width:47%; margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">
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
                    <p class="telTabP text-center" style="width:12%;  margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$orCount}}</p>
                    <p class="telTabP text-center" style="width:12%;  margin-bottom:0px; border-bottom:1px dotted rgb(39,190,175);">{{$or->shuma}} <span style="opacity: 0.4;">CHF</span></p>
                    <?php
                        $totQty = $totQty + (int)$orCount;
                        $totSum = $totSum + (float)$or->shuma;
                    ?>
                @endif
            @endforeach
            <div style="width: 100%;" class="d-flex justify-content-around mt-2">
                <p style="width: 45%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87);" class="text-center">Gesamtmenge <br> {{$totQty}}</p>
                <p style="width: 45%; font-weight:bold; font-size:1.2rem; color:rgb(72,81,87);" class="text-center">Gesamtpreis <br> {{$totSum}} CHF</p>
            </div>
        @else
            <p class="text-center" style="width:100%; margin-bottom:0px; font-weight:bold; color:red">
                Es gibt keine registrierten Protokolle
            </p>
        @endif


    </div>
