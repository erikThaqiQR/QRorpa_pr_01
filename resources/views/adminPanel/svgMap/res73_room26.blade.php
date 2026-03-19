<?php
  use App\TabOrder;
  use App\TableQrcode;
  use App\Orders;
  use App\newOrdersAdminAlert;
  use Carbon\Carbon;
  use App\svgTableMapData;
  use Illuminate\Support\Facades\Auth;

  $notiActi = explode('--||--',Auth::user()->notifySet);

  $svgObjAllK = svgTableMapData::where([['toRes','73'],['toRoom','26'],['formShape','k']])->get();
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

  <rect y="161.534" width="8.561" height="528.375" style="stroke: rgb(0, 0, 0); transform-box: fill-box; transform-origin: 50% 50%;" x="165.099" transform="matrix(-0.707107, 0.707107, -0.704782, -0.709432, 889.124814, -234.097973)" id="block1"/>
  <rect x="819.434" y="-1.198" width="55.129" height="190.715" style="stroke: rgb(0, 0, 0); stroke-width: 0px;" id="block2"/>
  <rect x="350.84" y="564.99" width="43.209" height="262.233" style="stroke: rgb(0, 0, 0); stroke-width: 0;" id="block3"/>
  <rect x="692.042" y="565" width="43.209" height="262.233" style="stroke: rgb(0, 0, 0); stroke-width: 0;" id="block4"/>

</svg>

@foreach($svgObjAllK as $svgObjOne)
  <input type="hidden" id="tableModalForReaload{{$svgObjOne->tableNr}}" value="0">
@endforeach
