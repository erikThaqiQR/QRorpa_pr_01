<?php
  use App\TabOrder;
  use App\TableQrcode;
  use App\Orders;
  use App\newOrdersAdminAlert;
  use Carbon\Carbon;
  use App\svgTableMapData;
  use Illuminate\Support\Facades\Auth;

  $notiActi = explode('--||--',Auth::user()->notifySet);

  $svgObjAllK = svgTableMapData::where([['toRes','73'],['toRoom','28'],['formShape','k']])->get();
  $svgObjAllRR = svgTableMapData::where([['toRes','73'],['toRoom','28'],['formShape','rr']])->get();

  if(!$agent->isMobile()){
    $svgBoxSize1 = 0;
    $svgBoxSize2 = 0;
    $svgBoxSize3 = 1241.549;
    $svgBoxSize4 = 822.7;
  }else{
    $svgBoxSize1 = 0;
    $svgBoxSize2 = 0;
    $svgBoxSize3 = 1241.549;
    $svgBoxSize4 = 822.7;
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
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;" 
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @elseif(TabOrder::where([['tabCode',$tabelOne->kaTab],['status',0]])->count() > 0)

            <rect data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')" 
            x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(255, 0, 0);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @else
            <rect data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(227, 232, 71);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @endif
        @elseif($statLess1 == 1 || $statLess2 == 1 )
          <rect data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
          onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(8, 224, 37);"/>

          <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;"
          x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
        @else
          <rect data-toggle="modal" data-target="#newTabOrderModal" 
          onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          x="{{$xtableObj}}" y="{{$ytableObj}}" width="{{$wtableObj}}" height="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(39, 190, 175);"/>

          <text data-toggle="modal" data-target="#newTabOrderModal" onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;"
          x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
        @endif 
      </g>
  @endforeach  

  <rect width="22" height="180.43" style="stroke: rgb(0, 0, 0); stroke-width: 0;" x="161.658" id="block1"/>
  <rect y="532.094" width="164.012" height="22.049" style="stroke: rgb(0, 0, 0); stroke-width: 0px;" id="block2"/>
  <rect y="685.335" width="82.132" height="22.049" style="stroke: rgb(0, 0, 0); stroke-width: 0;" id="block3"/>
  <rect width="29" height="187.069" style="stroke: rgb(0, 0, 0); stroke-width: 0;" x="835.623" class="blockx"/>
  <rect width="138.313" height="87.278" style="stroke: rgb(0, 0, 0); stroke-width: 0;" x="698.479" class="blockx"/>
  <rect x="397.012" y="394.112" width="656" height="41.874" style="fill: rgb(216, 216, 216); stroke: rgb(0, 0, 0);" class="blockx"/>
  <rect x="1015.53" y="393.76" width="37.381" height="118.262" style="fill: rgb(216, 216, 216); stroke: rgb(0, 0, 0);" class="blockx"/>
  <rect x="389.072" y="514.773" width="539.915" height="8" style="stroke: rgb(0, 0, 0);" class="blockx"/>
  <rect x="291.556" y="670.854" width="8" height="151.58" style="stroke: rgb(0, 0, 0);" class="blockx"/>
  <rect x="397.239" y="605.5" width="8" height="183.664" style="stroke: rgb(0, 0, 0); transform-box: fill-box; transform-origin: 50% 50%;" transform="matrix(0.848048, 0.529919, -0.529919, 0.848048, -57.669035, -102.224078)" class="blockx"/>
  <rect x="1419.38" y="103.177" width="153.49" height="28.996" style="stroke: rgb(0, 0, 0); transform-box: fill-box; transform-origin: 50% 50%;" transform="matrix(1, -0.000051, -0.016233, 1.000001, -331.872893, 1.826924)" class="blockx"/>
  <rect x="1075.37" y="105" width="12.264" height="81.584" style="stroke: rgb(0, 0, 0);" class="blockx"/>
  <rect x="1149" y="242.765" width="92.637" height="15.592" style="stroke: rgb(0, 0, 0);" class="blockx"/>
  <rect x="1015.83" y="547.573" width="37.381" height="75.902" style="stroke: rgb(0, 0, 0); stroke-width: 1;" class="blockx"/>
  <rect x="929.793" y="624.164" width="156.801" height="14.393" style="stroke: rgb(0, 0, 0);" class="blockx"/>
  <rect x="928.656" y="514.817" width="8" height="241.151" style="stroke: rgb(0, 0, 0);" class="blockx"/>
  <rect x="928.667" y="748.179" width="312.797" height="8" style="stroke: rgb(0, 0, 0);" class="blockx"/>
  <rect x="1149" y="447.656" width="92.637" height="15.592" style="stroke: rgb(0, 0, 0); stroke-width: 1;" class="blockx"/>
  <rect width="28.639" height="290.806" style="stroke: rgb(0, 0, 0); stroke-width: 0;" x="669.963" class="blockx"/>
  <text style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;" x="691.023" y="426.055">BAR</text>

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
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;" 
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @elseif(TabOrder::where([['tabCode',$tabelOne->kaTab],['status',0]])->count() > 0)

            <ellipse data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')" 
            cx="{{$xtableObj}}" cy="{{$ytableObj}}" rx="{{$wtableObj}}" ry="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(255, 0, 0);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @else
            <ellipse data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
            onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            cx="{{$xtableObj}}" cy="{{$ytableObj}}" rx="{{$wtableObj}}" ry="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}" 
            style="stroke: color(a98-rgb 1 1 1); fill: rgb(227, 232, 71);"/>

            <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
            style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;"
            x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
          @endif
        @elseif($statLess1 == 1 || $statLess2 == 1 )
          <ellipse data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" class="pointTable {{$hasAlert ? 'table-glow-adminAlert' : ''}}"
          onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          cx="{{$xtableObj}}" cy="{{$ytableObj}}" rx="{{$wtableObj}}" ry="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(8, 224, 37);"/>

          <text data-toggle="modal" data-target="#tabOrder{{$tabelOne->tableNr}}" onclick="checkForRebuildTable('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;"
          x="{{$xtableNr}}" y="{{$ytableNr}}">{{$tabelOne->tableNr}}</text>
        @else
          <ellipse data-toggle="modal" data-target="#newTabOrderModal" 
          onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          cx="{{$xtableObj}}" cy="{{$ytableObj}}" rx="{{$wtableObj}}" ry="{{$htableObj}}" id="tableIcon{{$tabelOne->tableNr}}"
          style="stroke: color(a98-rgb 1 1 1); fill: rgb(39, 190, 175);"/>

          <text data-toggle="modal" data-target="#newTabOrderModal" onclick="openNewTabOrderModal('{{$tabelOne->tableNr}}')"
          style="white-space: pre; fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 35px;"
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
