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


    $ordsAddCount = waiterActivityLog::where([['waiterId',$theW->id],['actType','newProdWa']])->count();
    $ordsPayCount = waiterActivityLog::where([['waiterId',$theW->id],['actType','orderCloseWA']])->count();

 
?>

<div class="pb-5">
    <div class="d-flex">
        <a class="btn btn-default shadow-none text-left pl-3" style="width:33%; color:rgb(39,190,175); font-size: 1.575rem;" href="{{route('admWoMng.indexAdmMngPage')}}">Zurück zu den Arbeitern</a>
        
        <h3 class="text-center" style="width:33%; color:rgb(39,190,175);"><strong>für Restaurant</strong></h3>
     
        <h3 class="text-right pr-4" style="width:33%; color:rgb(39,190,175);"><strong>{{$theW->name}}</strong></h3>
    </div>

    <hr>

    <div class="d-flex justify-content-around mb-2">
        <a class="btn btn-outline-dark" style="width:49%" href="{{ route('admWoMng.indexAdmMngOpenWaiterST',['wi'=>$theW->id]) }}"><strong>Takeaway</strong></a>
        <a class="btn btn-outline-dark" style="width:49%" href="{{ route('admWoMng.indexAdmMngOpenWaiterSD',['wi'=>$theW->id]) }}"><strong>Delivery</strong></a>
    </div>

    <div class="d-flex justify-content-around">
       
        <div style="width:45%;">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                    @if (isset($_GET['f']) && ($_GET['f'] == 'dateL1A' || $_GET['f'] == 'dateL1Z' || $_GET['f'] == 'idL1A' || $_GET['f'] == 'idL1Z' 
                    || $_GET['f'] == 'tableL1A' || $_GET['f'] == 'tableL1Z' || $_GET['f'] == 'prodL1A' || $_GET['f'] == 'prodL1Z' || $_GET['f'] == 'sasiaL1A' || $_GET['f'] == 'sasiaL1Z'
                    || $_GET['f'] == 'priceL1A' || $_GET['f'] == 'priceL1Z'))
                        <div class="col mr-2" onclick="hideList01()" id="displayList01Div">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bestellungen registriert</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45"> </span>{{$ordsAddCount}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px;" id="displayList01Alert"> Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @else
                        <div class="col mr-2" onclick="displayList01()" id="displayList01Div">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bestellungen registriert</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45"> </span>{{$ordsAddCount}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px;" id="displayList01Alert"> klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @endif
                        <div class="col-auto">
                            <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="width:45%;">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
               
                    @if (isset($_GET['f2']) && ($_GET['f2'] == 'dateL2A' || $_GET['f2'] == 'dateL2Z' || $_GET['f2'] == 'idL2A' || $_GET['f2'] == 'idL2Z' 
                    || $_GET['f2'] == 'tableL2A' || $_GET['f2'] == 'tableL2Z' || $_GET['f2'] == 'prodL2A' || $_GET['f2'] == 'prodL2Z' || $_GET['f2'] == 'sasiaL2A' || $_GET['f2'] == 'sasiaL2Z'
                    || $_GET['f2'] == 'priceL2A' || $_GET['f2'] == 'priceL2Z'))
                        <div class="col mr-2" onclick="hideList02()" id="displayList02Div">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bestellungen bezahlt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45"> </span>{{$ordsPayCount}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px;" id="displayList02Alert"> Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i></p>
                        </div>
                    @else
                        <div class="col mr-2" onclick="displayList02()" id="displayList02Div">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bestellungen bezahlt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span style="opacity:0.45"> </span>{{$ordsPayCount}}</div>
                            <p class="pt-2" style="opacity: 0.4; margin-bottom:0px;" id="displayList02Alert"> klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i></p>
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



    @include('adminPanel.mngWorkersDes.waiterStatsTables.li01')
    @include('adminPanel.mngWorkersDes.waiterStatsTables.li02')

    















    <div class="d-flex flex-wrap justify-content-around">
        <p class="text-center" style="width:100%; font-size:1.6rem; color:rgb(39,190,175); margin-bottom:0px;"><strong>Bestellungen</strong></p>
        <div style="width:45%;">
            <canvas id="chartAddProdNr"></canvas>
        </div>
        <div style="width:45%;">
            <canvas id="chartPayProdNr"></canvas>
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-around">
        <p class="text-center" style="width:100%; font-size:1.6rem; color:rgb(39,190,175); margin-bottom:0px;"><strong>Umsatz</strong></p>
        <div style="width:45%;">
            <canvas id="chartAddProdSum"></canvas>
        </div>
        <div style="width:45%;">
            <canvas id="chartPayProdSum"></canvas>
        </div>
    </div>
</div>


<script>
    function displayList01(){
        $('#list01').attr('style','display:flex !important;');
        $('#displayList01Div').attr('onclick','hideList01()');
        $('#displayList01Alert').html('Klicken Sie auf , um die Liste auszublenden <i class="far fa-hand-pointer"></i>');

        hideList02();
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
    }
    function hideList02(){
        $('#list02').attr('style','display:none !important;');
        $('#displayList02Div').attr('onclick','displayList02()');
        $('#displayList02Alert').html('klicken, um die Liste anzuzeigen <i class="far fa-hand-pointer"></i>');
    }
</script>
@include('adminPanel.mngWorkersDes.waiterStatJS')