<?php

use App\Orders;
use App\TabOrder;
use App\User;
use App\waiterActivityLog;
use SebastianBergmann\CodeCoverage\Report\PHP;

    if(!isset($_GET['wi'])){
        header("Location: " .route('admWoMng.indexAdmMngPage'));
        exit();
    }else{
        $theW = User::find($_GET['wi']);
        if($theW == NULL || $theW->role != 55){
            header("Location: " .route('admWoMng.indexAdmMngPage'));
            exit(); 
        }
    }
    $ordsTo0Count = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng0']])->count();
    $ordsTo1Count = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng1']])->count();
    $ordsTo2Count = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng2']])->count();
    $ordsTo3Count = waiterActivityLog::where([['waiterId',$theW->id],['actType','deliveryOrStatChng3']])->count();
?>

<style>
    .telTabP{
        font-size:xx-small;
    }
</style>

<div class="pb-5">
    <div class="d-flex">
        <a class="btn btn-default shadow-none text-left" style="width:33%; color:rgb(39,190,175); font-size: 0.9rem;" href="{{route('admWoMng.indexAdmMngPage')}}">
            <i class="fas fa-angle-left mr-2"></i> Arbeitern
        </a>
  
        <h6 class="text-center" style="width:33%; color:rgb(39,190,175); padding-top:8px;"><strong>für Delivery</strong></h6>

        <h6 class="text-right" style="width:33%; color:rgb(39,190,175); padding-top:8px;"><strong>{{$theW->name}}</strong></h6>
    </div>

    <hr style="margin:6px;">

    <div class="d-flex justify-content-around mb-2">
        <a class="btn btn-outline-dark" style="width:49%" href="{{ route('admWoMng.indexAdmMngOpenWaiterS',['wi'=>$theW->id]) }}"><strong>Restaurant</strong></a>
        <a class="btn btn-outline-dark" style="width:49%" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id]) }}"><strong>Takeaway</strong></a>
    </div>

    <div class="d-flex flex-wrap justify-content-around">
       
        <div style="width:49%;">
            <div class="card border-left-warning shadow h-100 py-1">
                <div class="card-body" style="padding: 4px;">
                    <div class="row no-gutters align-items-center">
                    @if (isset($_GET['f1']) && ($_GET['f1'] == 'dateL1A' || $_GET['f1'] == 'dateL1Z' || $_GET['f1'] == 'idL1A' || $_GET['f1'] == 'idL1Z' 
                    || $_GET['f1'] == 'tableL1A' || $_GET['f1'] == 'tableL1Z' || $_GET['f1'] == 'prodL1A' || $_GET['f1'] == 'prodL1Z' || $_GET['f1'] == 'sasiaL1A' || $_GET['f1'] == 'sasiaL1Z'
                    || $_GET['f1'] == 'priceL1A' || $_GET['f1'] == 'priceL1Z'))
                        <div class="col mr-2" onclick="hideList01()" id="displayList01Div">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Bestellungen Annehmen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center"><span style="opacity:0.45"> </span>{{$ordsTo0Count}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px; font-size:8px;" id="displayList01Alert"> Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @else
                        <div class="col mr-2" onclick="displayList01()" id="displayList01Div">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Bestellungen Annehmen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center"><span style="opacity:0.45"> </span>{{$ordsTo0Count}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px; font-size:8px;" id="displayList01Alert"> klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @endif
                        <div class="col-auto">
                            <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width:49%;">
            <div class="card border-left-primary shadow h-100 py-1">
                <div class="card-body" style="padding: 4px;">
                    <div class="row no-gutters align-items-center">
                    @if (isset($_GET['f2']) && ($_GET['f2'] == 'dateL2A' || $_GET['f2'] == 'dateL2Z' || $_GET['f2'] == 'idL2A' || $_GET['f2'] == 'idL2Z' 
                    || $_GET['f2'] == 'tableL2A' || $_GET['f2'] == 'tableL2Z' || $_GET['f2'] == 'prodL2A' || $_GET['f2'] == 'prodL2Z' || $_GET['f2'] == 'sasiaL2A' || $_GET['f2'] == 'sasiaL2Z'
                    || $_GET['f2'] == 'priceL2A' || $_GET['f2'] == 'priceL2Z'))
                        <div class="col mr-2" onclick="hideList02()" id="displayList02Div">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bestellungen Bestätigt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center"><span style="opacity:0.45"> </span>{{$ordsTo1Count}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px; font-size:8px;" id="displayList02Alert"> Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @else
                        <div class="col mr-2" onclick="displayList02()" id="displayList02Div">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bestellungen Bestätigt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center"><span style="opacity:0.45"> </span>{{$ordsTo1Count}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px; font-size:8px;" id="displayList02Alert"> klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @endif
                        <div class="col-auto">
                            <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width:49%;">
            <div class="card border-left-danger shadow h-100 py-1">
                <div class="card-body" style="padding: 4px;">
                    <div class="row no-gutters align-items-center">
                    @if (isset($_GET['f3']) && ($_GET['f3'] == 'dateL3A' || $_GET['f3'] == 'dateL3Z' || $_GET['f3'] == 'idL3A' || $_GET['f3'] == 'idL3Z' 
                    || $_GET['f3'] == 'tableL3A' || $_GET['f3'] == 'tableL3Z' || $_GET['f3'] == 'prodL3A' || $_GET['f3'] == 'prodL3Z' || $_GET['f3'] == 'sasiaL3A' || $_GET['f3'] == 'sasiaL3Z'
                    || $_GET['f3'] == 'priceL3A' || $_GET['f3'] == 'priceL3Z'))
                        <div class="col mr-2" onclick="hideList03()" id="displayList03Div">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Bestellungen Annulliert</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center"><span style="opacity:0.45"> </span>{{$ordsTo2Count}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px; font-size:8px;" id="displayList03Alert"> Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @else
                        <div class="col mr-2" onclick="displayList03()" id="displayList03Div">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Bestellungen Annulliert</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center"><span style="opacity:0.45"> </span>{{$ordsTo2Count}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px; font-size:8px;" id="displayList03Alert"> klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @endif
                        <div class="col-auto">
                            <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width:49%;">
            <div class="card border-left-success shadow h-100 py-1">
                <div class="card-body" style="padding: 4px;">
                    <div class="row no-gutters align-items-center">
                    @if (isset($_GET['f4']) && ($_GET['f4'] == 'dateL4A' || $_GET['f4'] == 'dateL4Z' || $_GET['f4'] == 'idL4A' || $_GET['f4'] == 'idL4Z' 
                    || $_GET['f4'] == 'tableL4A' || $_GET['f4'] == 'tableL4Z' || $_GET['f4'] == 'prodL4A' || $_GET['f4'] == 'prodL4Z' || $_GET['f4'] == 'sasiaL4A' || $_GET['f4'] == 'sasiaL4Z'
                    || $_GET['f4'] == 'priceL4A' || $_GET['f4'] == 'priceL4Z'))
                        <div class="col mr-2" onclick="hideList04()" id="displayList04Div">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Bestellungen Abgeschlossen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center"><span style="opacity:0.45"> </span>{{$ordsTo3Count}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px; font-size:8px;" id="displayList04Alert"> Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @else
                        <div class="col mr-2" onclick="displayList04()" id="displayList04Div">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Bestellungen Abgeschlossen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center"><span style="opacity:0.45"> </span>{{$ordsTo3Count}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px; font-size:8px;" id="displayList04Alert"> klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @endif
                        <div class="col-auto">
                            <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    @include('adminPanel.mngWorkersTel.waiterStatsDeTables.li01')
    @include('adminPanel.mngWorkersTel.waiterStatsDeTables.li02')
    @include('adminPanel.mngWorkersTel.waiterStatsDeTables.li03')
    @include('adminPanel.mngWorkersTel.waiterStatsDeTables.li04')



    <div class="d-flex flex-wrap justify-content-around mt-2">
        <p class="text-center" style="width:49%; font-size:0.9rem; color:rgb(255,196,27); margin-bottom:0px;"><strong>Annehmen #</strong></p>
        <p class="text-center" style="width:49%; font-size:0.9rem; color:rgb(78,115,223); margin-bottom:0px;"><strong>Bestätigt #</strong></p>
   
        <div style="width:49%;">
            <canvas id="chart01Nr"></canvas>
        </div>
        <div style="width:49%;">
            <canvas id="chart02Nr"></canvas>
        </div>

        <p class="text-center" style="width:49%; font-size:0.9rem; color:rgb(223,79,100); margin-bottom:0px;"><strong>Annulliert #</strong></p>
        <p class="text-center" style="width:49%; font-size:0.9rem; color:rgb(46,167,72); margin-bottom:0px;"><strong>Abgeschlossen #</strong></p>
        <div style="width:49%;">
            <canvas id="chart03Nr"></canvas>
        </div>
        <div style="width:49%;">
            <canvas id="chart04Nr"></canvas>
        </div>
    </div>
    <hr style="margin: 5px;">
    <div class="d-flex flex-wrap justify-content-around">
        <p class="text-center" style="width:49%; font-size:0.9rem; color:rgb(255,196,27); margin-bottom:0px;"><strong>Annehmen CHF</strong></p>
        <p class="text-center" style="width:49%; font-size:0.9rem; color:rgb(78,115,223); margin-bottom:0px;"><strong>Bestätigt CHF</strong></p>
  
        <div style="width:49%;">
            <canvas id="chart01Sum"></canvas>
        </div>
        <div style="width:49%;">
            <canvas id="chart02Sum"></canvas>
        </div>

        <p class="text-center" style="width:49%; font-size:0.9rem; color:rgb(223,79,100); margin-bottom:0px;"><strong>Annulliert CHF</strong></p>
        <p class="text-center" style="width:49%; font-size:0.9rem; color:rgb(46,167,72); margin-bottom:0px;"><strong>Abgeschlossen CHF</strong></p>
        <div style="width:49%;">
            <canvas id="chart03Sum"></canvas>
        </div>
        <div style="width:49%;">
            <canvas id="chart04Sum"></canvas>
        </div>
    </div>

</div>


<script>
    function displayList01(){
        $('#list01').attr('style','display:flex !important;');
        $('#displayList01Div').attr('onclick','hideList01()');
        $('#displayList01Alert').html('Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i>');
        hideList02();
        hideList03();
        hideList04();
    }
    function hideList01(){
        $('#list01').attr('style','display:none !important;');
        $('#displayList01Div').attr('onclick','displayList01()');
        $('#displayList01Alert').html('klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i>');
    }

    function displayList02(){
        $('#list02').attr('style','display:flex !important;');
        $('#displayList02Div').attr('onclick','hideList02()');
        $('#displayList02Alert').html('Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i>');
        hideList01();
        hideList03();
        hideList04();
    }
    function hideList02(){
        $('#list02').attr('style','display:none !important;');
        $('#displayList02Div').attr('onclick','displayList02()');
        $('#displayList02Alert').html('klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i>');
    }

    function displayList03(){
        $('#list03').attr('style','display:flex !important;');
        $('#displayList03Div').attr('onclick','hideList03()');
        $('#displayList03Alert').html('Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i>');
        hideList01();
        hideList02();
        hideList04();
    }
    function hideList03(){
        $('#list03').attr('style','display:none !important;');
        $('#displayList03Div').attr('onclick','displayList03()');
        $('#displayList03Alert').html('klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i>');
    }

    function displayList04(){
        $('#list04').attr('style','display:flex !important;');
        $('#displayList04Div').attr('onclick','hideList04()');
        $('#displayList04Alert').html('Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i>');
        hideList01();
        hideList02();
        hideList03();
    }
    function hideList04(){
        $('#list04').attr('style','display:none !important;');
        $('#displayList04Div').attr('onclick','displayList04()');
        $('#displayList04Alert').html('klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i>');
    }
</script>
@include('adminPanel.mngWorkersTel.waiterStatJSD')