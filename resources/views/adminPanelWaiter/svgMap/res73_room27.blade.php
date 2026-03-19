<?php
  use App\TabOrder;
  use App\TableQrcode;
  use App\Orders;
  use App\newOrdersAdminAlert;
  use Carbon\Carbon;
  use App\svgTableMapData;
  use Illuminate\Support\Facades\Auth;

  $notiActi = explode('--||--',Auth::user()->notifySet);

  $svgObjAllK = svgTableMapData::where([['toRes','73'],['toRoom','27'],['formShape','k']])->get();
  $svgObjAllRR = svgTableMapData::where([['toRes','73'],['toRoom','27'],['formShape','rr']])->get();

  if(!$agent->isMobile()){
    $svgBoxSize1 = 0;
    $svgBoxSize2 = 0;
    $svgBoxSize3 = 969.198;
    $svgBoxSize4 = 967.435;
  }else{
    $svgBoxSize1 = 0;
    $svgBoxSize2 = 0;
    $svgBoxSize3 = 969.198;
    $svgBoxSize4 = 967.435;
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
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;" 
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @elseif(TabOrder::where([['tabCode',$tabelOne->kaTab],['status',0]])->count() > 0)

            <rect data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')" 
            x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(255, 0, 0);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @else
            <rect data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(227, 232, 71);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @endif
        @elseif($statLess1 == 1 || $statLess2 == 1 )
          <rect data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
          onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(8, 224, 37);"/>

          <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;"
          x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
        @else
          <rect data-toggle="modal" data-target="#newTabOrderModal" 
          onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(39, 190, 175);"/>

          <text data-toggle="modal" data-target="#newTabOrderModal" onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;"
          x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
        @endif 
      </g>
  @endforeach  

  <rect x="-0.3" y="-0.036" width="61.758" height="744.368" style="stroke: rgb(0, 0, 0); fill: rgb(65, 169, 79); stroke-width: 0px;" id="block1"/>
  <rect x="58.522" y="-0.021" width="644.994" height="61.8" style="stroke: rgb(0, 0, 0); fill: rgb(65, 169, 79); stroke-width: 0;" id="block2"/>
  <rect x="658.342" y="-0.337" width="61.758" height="744.368" style="stroke: rgb(0, 0, 0); fill: rgb(65, 169, 79); stroke-width: 0;" id="block3"/>
 
  @foreach($svgObjAllRR as $svgObjOne)
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

            <ellipse onclick="setTabStat('{{$tabelOne->tableNr}}','{{$tabelOne->id}}','0')" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}" 
            cx="{{$xtableObj}}" cy="{{$ytableObj}}" rx="{{$wtableObj}}" ry="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(227, 232, 71);"/>

            <text onclick="setTabStat('{{$tabelOne->tableNr}}','{{$tabelOne->id}}','0')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;" 
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @elseif(TabOrder::where([['tabCode',$tabelOne->kaTab],['status',0]])->count() > 0)

            <ellipse data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')" 
            cx="{{$xtableObj}}" cy="{{$ytableObj}}" rx="{{$wtableObj}}" ry="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(255, 0, 0);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @else
            <ellipse data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            cx="{{$xtableObj}}" cy="{{$ytableObj}}" rx="{{$wtableObj}}" ry="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(227, 232, 71);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @endif
        @elseif($statLess1 == 1 || $statLess2 == 1 )
          <ellipse data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
          onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          cx="{{$xtableObj}}" cy="{{$ytableObj}}" rx="{{$wtableObj}}" ry="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(8, 224, 37);"/>

          <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;"
          x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
        @else
          <ellipse data-toggle="modal" data-target="#newTabOrderModal" 
          onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          cx="{{$xtableObj}}" cy="{{$ytableObj}}" rx="{{$wtableObj}}" ry="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(39, 190, 175);"/>

          <text data-toggle="modal" data-target="#newTabOrderModal" onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 40px;"
          x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
        @endif 
      </g>
  @endforeach 
</svg>

@foreach($svgObjAllK as $svgObjOne)
  <input type="hidden" id="tableModalForReaload{{$svgObjOne->tableNr}}" value="0">
@endforeach
@foreach($svgObjAllRR as $svgObjOne)
  <input type="hidden" id="tableModalForReaload{{$svgObjOne->tableNr}}" value="0">
@endforeach
