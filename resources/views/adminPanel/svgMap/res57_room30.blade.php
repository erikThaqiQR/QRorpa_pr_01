<?php
  use App\TabOrder;
  use App\TableQrcode;
  use App\Orders;
  use App\newOrdersAdminAlert;
  use Carbon\Carbon;
  use App\svgTableMapData;
  use Illuminate\Support\Facades\Auth;

  $notiActi = explode('--||--',Auth::user()->notifySet);

  $svgObjAllK = svgTableMapData::where([['toRes','57'],['toRoom','30'],['formShape','k']])->get();

  if(!$agent->isMobile()){
    $svgBoxSize1 = 0;
    $svgBoxSize2 = 0;
    $svgBoxSize3 = 658.87;
    $svgBoxSize4 = 500;
  }else{
    $svgBoxSize1 = 0;
    $svgBoxSize2 = 0;
    $svgBoxSize3 = 658.87;
    $svgBoxSize4 = 500;
  }
?>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="{{$svgBoxSize1}} {{$svgBoxSize2}} {{$svgBoxSize3}} {{$svgBoxSize4}}">

  @foreach($svgObjAllK as $svgObjOne)
      <g id="tableIconDiv{{$svgObjOne->tableNr}}">
        <?php 
          $tabelOne = TableQrcode::where([['Restaurant',Auth::user()->sFor],['tableNr',$svgObjOne->tableNr]])->first(); 
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
          $xtableObj = $svgObjOne->xtableObj;
          $ytableObj = $svgObjOne->ytableObj;
          $wtableObj = $svgObjOne->wtableObj;
          $htableObj = $svgObjOne->htableObj;
          $xtableNr = $svgObjOne->xtableNr;
          $ytableNr = $svgObjOne->ytableNr;
        ?>
        @if($tabelOne->kaTab != 0 )
          @if($tabelOne->kaTab == -1)

            <rect onclick="setTabStat('{{$tabelOne->tableNr}}','{{$tabelOne->id}}','0')" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}" 
            x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(227, 232, 71);"/>

            <text onclick="setTabStat('{{$tabelOne->tableNr}}','{{$tabelOne->id}}','0')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 28px" 
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @elseif(TabOrder::where([['tabCode',$tabelOne->kaTab],['status',0]])->count() > 0)

            <rect data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')" 
            x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(255, 0, 0);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 28px"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @else
            <rect data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(227, 232, 71);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 28px"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @endif
        @elseif($statLess1 == 1 || $statLess2 == 1 )
          <rect data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
          onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(8, 224, 37);"/>

          <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 28px"
          x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
        @else
          <rect data-toggle="modal" data-target="#newTabOrderModal" 
          onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(39, 190, 175);"/>

          <text data-toggle="modal" data-target="#newTabOrderModal" onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 28px"
          x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
        @endif 
      </g>
  @endforeach  

  <rect x="3.141" y="272.739" width="64.05" height="121.193" style="fill: rgb(216, 216, 216); stroke: rgb(0, 0, 0);" id="block4"/>
  <text style="fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 20px; white-space: pre; transform-box: fill-box; transform-origin: 69.9934% 70.2701%;" transform="matrix(0, -1, 1, 0, 525.557745, 565.258985)" x="-555.68" y="-249.24">Restaurant</text>
  <rect x="1.09" y="135.734" width="266.736" height="5.572" style="stroke: rgb(0, 0, 0); transform-box: fill-box; transform-origin: 50% 50%;" transform="matrix(0.707107, -0.707107, 0.707107, 0.707107, -41.089889, -46.482685)" id="blockline1"/>
  <rect x="1.09" y="135.734" width="266.736" height="5.572" style="stroke: rgb(0, 0, 0); stroke-width: 1; transform-origin: 134.458px 138.52px;" transform="matrix(0.707107, 0.707107, -0.707107, 0.707107, 432.265209, -45.393288)" id="blockline2"/>

</svg>

@foreach($svgObjAllK as $svgObjOne)
  <input type="hidden" id="tableModalForReaload{{$svgObjOne->tableNr}}" value="0">
@endforeach