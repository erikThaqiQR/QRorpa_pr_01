@extends('layouts.appOrders')

<!-- 2060 kodi -->

<?php
    use App\Orders;
    use App\Restorant;
    use App\Piket;
    use App\Produktet;
    use App\Cupon;
    use App\TableQrcode;
    use App\TakeawaySchedule;
    use App\couponUsedPhoneNr;
    use App\TabOrder;
    use App\tabVerificationPNumbers;
    use Carbon\Carbon;
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }  

    $isGhost = false;
    if(isset($_SESSION["phoneNrVerified"])){
        if(str_contains($_SESSION["phoneNrVerified"], '|')){
            $phNr2D = explode('|',$_SESSION["phoneNrVerified"]);
            if($phNr2D[0]='999999'){
                $isGhost = true;
                $isGhostNr = $phNr2D[1];
            }
        }
    }
?>

@include('words')

   @if(isset($_SESSION['Res']))
        @php
            $theRes = Restorant::find($_SESSION['Res']);
            $theResId = $_SESSION['Res'];
            $theTable = $_SESSION['t'];
        @endphp
        <input type="hidden" value="{{$_SESSION['Res']}}" id="theRestaurant">
        <input type="hidden" value="{{$_SESSION['t']}}" id="theTable">
    @endif

    @include('cartComp.cartRestaurantStyle')


    @include('cartComp.tableChngReqFAdmin2')

    @if(isset($_SESSION["phoneNrVerified"]))
    <input type="hidden" value="{{$_SESSION['phoneNrVerified']}}" id="verifiedNr007">
    @endif

@section('content')


    <?php
        $orderSend = "";
    ?>

<div class="successOrder container" id="successOrder">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                {{__('others.payment_completed')}}
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('successOrder').style.display = "none";
</script>




<div id="cartAllElements">
@if(isset($_SESSION['phoneNrVerified']) && Cart::count() > 0)






<div id="allOrders">

    <div class="pb-5">
        <div class="container mt-5">




            <div class="row">
                <div class="col-lg-8 p-3 bg-white rounded shadow-sm mb-5">
                    <!-- Shopping cart table -->





                    <div id="showMyOrdersDiv" class="table-responsive">
                        <div class="col-sm-12 col-md-12 col-lg-12 pb-2 text-center">
                            <h5 style="font-weight:bold; color:rgb(72,81,87);">
                                
                                @if(isset($_SESSION["phoneNrVerified"]) && $isGhost)
                                    {{__('others.verified_nr2')}} <br> " {{$isGhostNr}} "
                                @elseif(isset($_SESSION["phoneNrVerified"]))
                                    {{__('others.verified_nr')}} {{$_SESSION["phoneNrVerified"]}}
                                @else
                                    {{__('others.verified_nr')}} {{__('others.temporary')}} keine Nummer 
                                @endif
                            </h5>
                            <!-- <h3 class="color-qrorpa" style="font-weight:bold;">
                                <span id="cartCountCh">{{Cart::count()}}</span> {{__('others.Article')}}
                            </h3> -->
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 pb-2 d-flex flex-wrap justify-content-between">
                            @if(isset($_SESSION["Res"]) && isset($_SESSION['t']))
                                <span class="color-qrorpa" style="font-size:1em; width:70%; font-weight:bold;">{{Restorant::find($_SESSION["Res"])->emri}}</span>
                                <span style="font-size:1em; width:30%; font-weight:bold;" class="text-center color-qrorpa"> {{__('others.Table')}}: {{$_SESSION['t']}}</span>
                            @else
                                {{__('others.no_restaurant')}}
                            @endif
                        </div>

                        <!-- ALERT Alert-danger  -->
                        <div class="alert alert-danger text-center" id="finishOrder" style="display:none !important;">
                           {{__('others.order_over')}}
                        </div>





                        <div class="d-flex justify-content-between">
                            <div style="width:100%">
                                <p class="text-center" style="font-size:1.2rem; margin:2px; padding:3px;"><strong>Produkte</strong></p>
                            </div>
                        </div>

                        <?php
                            $porosiaSend = ""; $step = 1; $orderExtras = 1;
                            
                            $point = 0; $ExtraStephh = 0;

                            $Restrict = 0;
                            $ageRestThis = 0;
                            $allMyOr = array();
                            $cancelOrderSum = 0;

                            $hasUnconfirmed = 0;
                        ?>


                        <!-- cart Content  -->
                        @foreach(Cart::content() as $item)
                            @if((isset($_SESSION['phoneNrVerified']) && !$isGhost) || (isset($_SESSION['adminToClProdsRec']) && TabOrder::find($item->options->tabOId)->specStat == $_SESSION['adminToClProdsRec']))
                            <?php
                                if(Produktet::find($item->id) != null){
                                    if(Produktet::find($item->id)->restrictPro == 18){
                                        $Restrict = 18;
                                    }else if(Produktet::find($item->id)->restrictPro == 16){
                                        if($Restrict != 18){
                                            $Restrict = 16;
                                        }
                                    }
                                    $ageRestThis = Produktet::find($item->id)->restrictPro;
                                }
                                if($item->options->ekstras == ''){ $theEx = 'empty'; }else{ $theEx = $item->options->ekstras; }
                                if($item->options->type == ''){ $theTy = 'empty'; }else{ $theTy = $item->options->type; }

                               

                                // $tabEl = TabOrder::where([['OrderEmri',$item->name],['OrderExtra',$theEx],['OrderType',$theTy]
                                // ,['OrderPershkrimi',$item->options->persh],['tabCode','!=',0]])->get();
                                $oTO = TabOrder::find($item->options->tabOId);
                                
                                if($oTO != null){
                                    
                                    $totQtyAdd = 0;
                                    // foreach($tabEl as $oTO){
                                        if($oTO->OrderSasia <= $item->qty){
                                            if(($totQtyAdd+$oTO->OrderSasia)<= $item->qty){
                                                
                                                array_push($allMyOr,$oTO->id);
                                                if($oTO->status == 1){ $finishOr = 1; }else if($oTO->status == 9){ $finishOr = 9;}else{ $finishOr = 0;}
                                                if($oTO->status == 9){ $cancelOrderSum += $oTO->OrderQmimi; }

                                                $totQtyAdd += $oTO->OrderSasia;
                                                // if($totQtyAdd == $item->qty){
                                                //     break;
                                                // }
                                            }
                                        }

                                        if($oTO->status == 0){
                                            $hasUnconfirmed = 1;
                                        }
                                    // }
                                }  
                            ?>


                            @if(isset($finishOr) && $finishOr == 1)
                            <!-- confirmed -->
                            <div class="d-flex justify-content-between p-1" id="orderRow{{$point}}" style="background-color:rgb(159, 245, 188)">
                            @elseif(isset($finishOr) && $finishOr == 9)
                            <div class="d-flex justify-content-between p-1" id="orderRow{{$point}}" style="background-color:rgb(252, 134, 134)">
                            @else
                            <div class="d-flex justify-content-between p-1" id="orderRow{{$point}}">
                            @endif
                                <div style="width:57%" class="mt-1">
                                    <h5 class="mb-0"> 
                                        <a href="#" class="text-dark d-inline-block align-middle">
                                            <span style="font-weight: bold;" class="mr-2">{{$item->qty}} X</span>{{ $item->name }}
                                            @if($ageRestThis != 0)
                                                <img style="width:20px; height:auto;" src="storage/icons/{{$ageRestThis}}+.png" alt="">
                                            @endif
                                        </a>
                                    </h5>
                                    <?php
                                        $pershkrimi = substr($item->options->persh, 0, 18);
                                        if(strlen($item->options->persh) >= 19){
                                            echo '<span class="text-muted font-weight-normal font-italic d-block">'.$pershkrimi.'...</span>';
                                        }else{
                                            echo '<span class="text-muted font-weight-normal font-italic d-block">'.$pershkrimi.'</span>';
                                        }
                                    ?>
                                    @if($item->options->type != '')
                                    <span style="font-weight: bold;">Typ: {{explode('||',$item->options->type)[0] }}</span>
                                    @endif
                                    <p style="margin:1px; padding:3px; color:red; font-weight:bold;">{{$item->options->koment}}</p>

                                </div>
                                <div style="width:25%" class="mt-1 text-center">
                                    <strong> <span id="setPrice{{$item->rowId}}">{{sprintf('%01.2f', $item->price)}}</span>  <sup>{{__('global.currencyShow')}}</sup><br>
                                        @if($item->qty > 1)
                                        <span id="setPriceSasiaAll{{$ExtraStephh}}">{{ sprintf('%01.2f', $item->price * $item->qty)}}</span>   <sup>{{__('global.currencyShow')}}</sup>
                                        @endif
                                    </strong>
                                </div>
                                <!-- <div style="width:12%">
                                    <div class="form-group">
                                        <input value="{{$item->qty}}" id="currentQTY{{$ExtraStephh}}" class="form-control text-center" disabled="" style="font-weight:bold;">
                                    </div>
                                </div> -->

                                <div style="width:15%" class="text-right">
                                
                                    @if(!$isGhost && !(isset($finishOr) && $finishOr == 1))
                                        <input type="hidden" value="{{$item->rowId}}" class="rowIdGo{{$ExtraStephh}}">
                                        @if(isset($finishOr) && $finishOr == 9)
                                            <button type="button" onclick="removeOrderChCartOnly('rowIdGo{{$ExtraStephh}}','{{$point++}}')"
                                                class="btn btn-default">
                                                <i class="far fa-trash-alt fa-2x mt-1"></i>
                                            </button>
                                        @else
                                        <?php $rand = rand(111111111,999999999);?>
                                        <button id="deleteCartIt{{$rand}}Btn" type="button" onclick="removeOrderCh('rowIdGo{{$ExtraStephh}}','{{$point++}}','{{$rand}}','{{$item->options->tabOId}}')"
                                            class="btn btn-default">
                                            <i class="far fa-trash-alt fa-2x mt-1"></i>
                                        </button>
                                        <img id="deleteCartIt{{$rand}}Gif" style="width:75px; height:auto; display:none;" src="storage/gifs/delete01.gif" alt="">
                                        @endif
                                    @else
                                        @if($oTO->OrderSasia == $oTO->OrderSasiaDone)
                                            <!-- Order Finished -->
                                            <button class="btn btn-block btn-success shadow-none" style="padding:2px;">
                                                <strong>Bereit</strong>
                                            </button>
                                        @else
                                            <!-- Order Not Finished -->
                                            <button class="btn btn-block btn-danger shadow-none" style="padding:2px;">
                                                <strong>Nicht bereit</strong>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @if(isset($finishOr) && $finishOr == 1)
                            <div style="border-bottom:1px solid lightgray; padding-bottom:10px; margin-top:-10px; background-color:rgb(159, 245, 188);" id="buttonExt{{$orderExtras}}">
                            @elseif(isset($finishOr) && $finishOr == 9)
                            <div style="border-bottom:1px solid lightgray; padding-bottom:10px; margin-top:-10px; background-color:rgb(252, 134, 134);" id="buttonExt{{$orderExtras}}">
                            @else
                            <div style="border-bottom:1px solid lightgray; padding-bottom:10px; margin-top:-10px;" id="buttonExt{{$orderExtras}}">
                            @endif
                                @if($item->options->ekstras != "")
                                    <?php
                                        $countEEE = 0;
                                        foreach(explode('--0--',$item->options->ekstras) as $oneEGo){
                                            if($oneEGo != ''){
                                                $countEEE++;
                                            }
                                        }
                                    ?>
                                    @if($countEEE > 0)
                                        @if(isset($finishOr))
                                        <button class="btn btn-block btn-outline-default" onclick="showOrderExtras('{{$orderExtras}}','{{$finishOr}}')">
                                        @else
                                        <button class="btn btn-block btn-outline-default" onclick="showOrderExtras('{{$orderExtras}}','0')">
                                        @endif
                                            {{__('others.showExtras')}}
                                        </button>
                                    @endif
                                @endif
                            </div>

                            @if(isset($finishOr) && $finishOr == 1)
                            <div id="divExtra{{$orderExtras}}" class="d-flex flex-row flex-wrap">  
                            @elseif(isset($finishOr) && $finishOr == 9)
                            <div id="divExtra{{$orderExtras}}" class="d-flex flex-row flex-wrap">
                            @else
                            <div id="divExtra{{$orderExtras}}" class="d-flex flex-row flex-wrap">
                            @endif

                                <?php
                                
                                        if($item->options->ekstras == ""){
                                            // echo '<p class="AllExtrasOrder orderExtra{{$orderExtras}}"> '.__('others.no_extra_ingredients').' </p>';
                                        }else{
                                            $extProD1 =explode('--0--',$item->options->ekstras);
                                            foreach($extProD1 as $extProOne){
                                                if(!empty($extProOne) || $extProOne == ""){
                                                    $extProD2 =explode('||',$extProOne);
                                                        if(!empty($extProD2[0])){

                                                            $extraEmriGO =str_replace(' ', '', $extProD2[0]);

                                                            $ordExtD21 = explode('.',$extProD2[1])[0];
                                                        ?>
                                                        <div id="ExtraShowRev{{$extraEmriGO}}O{{$ExtraStephh}}O{{$ordExtD21}}" style="width:50%; display:none;"
                                                            class="AllExtrasOrder orderExtra{{$orderExtras}}">
                                                            <input type="hidden" value="{{$item->rowId}}" class="rowIdGo{{$ExtraStephh}}">
                                                            <input type="hidden" value="{{$item->options->ekstras}}" class="AllExtraGo{{$ExtraStephh}}">
                                                            @if(isset($finishOr) && $finishOr != 1 && $finishOr != 9)
                                                            <button
                                                                onclick="removeThisExtraFromProd('ExtraShowRev{{$extraEmriGO}}O{{$ExtraStephh}}O{{$ordExtD21}}','{{$extProOne}}','{{$ExtraStephh}}','{{$extProD2[1]}}','{{$extProD2[0]}}')"
                                                                class="btn btn-default btn-sm"><i class="fas fa-times fa-sm" ></i> {{$extProD2[0]}} {
                                                                {{$extProD2[1]}} }
                                                            </button>
                                                            @else
                                                                <button class="btn btn-default btn-sm"> {{$extProD2[0]}} {
                                                                    {{$extProD2[1]}} }
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <?php
                                                        }
                                                }
                                            }
                                        }  
                                        $ExtraStephh++;       
                                        $orderExtras++;           
                                    ?>

                            </div>
                        
                            <?php
                                if(empty($orderSend)){
                                    $orderSend .= $item->name.'||'.$item->options->persh.'||'.$item->qty.'||'.$item->price.'||'.$item->options->type;
                                }else{
                                    $orderSend .= '--+--'.$item->name.'||'.$item->options->persh.'||'.$item->qty.'||'.$item->price.'||'.$item->options->type;
                                }
                            ?>
                            @endif
                            
                        @endforeach<!-- Perfundoj paraqitja e produkteve ne shport  -->
                        
                        <input type="hidden" id="cancelOrderSumInVal" value="{{$cancelOrderSum}}">

                        <input type="hidden" id="hasUnconfirmedVal" value="{{$hasUnconfirmed}}">



































                        <!-- Paraqitja e porosive tjera ne TAB -->
                        
                        @if(TabOrder::where([['toRes',$theResId],['tableNr',$theTable],['tabCode','!=',0],['status','!=','9']])->whereNotIn('id',$allMyOr)->count() > 0 && isset($_SESSION["phoneNrVerified"]) && !$isGhost)
                            <div id="otherOrdersOnTab">
                                <h5 class="color-qrorpa mt-3"><strong>{{__('others.other_orders_table')}}</strong></h5>
                                    
                                <?php 
                                    // $tabCodePNVer = tabVerificationPNumbers::where([['status','1'],['phoneNr',$_SESSION['phoneNrVerified']]])->first()->tabCode;
                                    $tabCodePNVer = TableQrcode::where([['tableNr',$theTable],['Restaurant',$theResId]])->first()->kaTab;

                                    // in_array(id,$allMyOr);
                                    $phoneNrActive = array();
                            
                                    if(isset($_SESSION['adminToClProdsRec'])){
                                        $tvOGoo = tabVerificationPNumbers::where([['tabCode',$tabCodePNVer],['status','1'],['specStat',$_SESSION['adminToClProdsRec']]])->get();
                                    }
                                ?>
                                @foreach(tabVerificationPNumbers::where([['tabCode',$tabCodePNVer],['status','1']])->get() as $nrVers)
                                    <?php 
                                        if(!in_array($nrVers->phoneNr,$phoneNrActive) && $nrVers->phoneNr != $_SESSION['phoneNrVerified'] ){
                                            array_push($phoneNrActive,$nrVers->phoneNr);
                                        }
                                        // && !str_contains($nrVers->phoneNr, '|')
                                    ?>
                                @endforeach
                                @foreach ($phoneNrActive as $pnOnTab )
                                    <div class="p-1 mb-2" style="border:1px solid rgb(72,81,87); border-radius:10px; ">
                                        @if($pnOnTab == "0770000000")
                                            <p class="text-center"><strong>{{__('others.adminOrders')}}</strong></p>
                                        @elseif(str_contains($pnOnTab, '|'))
                                            <p class="text-center"><strong>{{__('others.ghostCart')}} : {{explode('|',$pnOnTab)[1]}}</strong></p>
                                        @else
                                            <p class="text-center"><strong>+41 *** *{{substr($pnOnTab, -6)}}</strong></p>
                                        @endif
                                        <hr>
                                        <?php 
                                            $payThisTooPrice = 0; 
                                            $payThisTooPhoneNr = $pnOnTab;
                                            if(str_contains($pnOnTab, '|')){
                                                $payThisTooPhoneNrPaLine = explode('|',$pnOnTab)[1];
                                            }
                                        ?>
                                        @foreach(tabVerificationPNumbers::where([['status','1'],['tabCode',$tabCodePNVer],['phoneNr',$pnOnTab]])->get() as $printTabOrder)
                                        <!-- ,['specStat','0'] -->
                                            <?php $otherTONow = TabOrder::findOrFail($printTabOrder->tabOrderId);?>

                                            @if($otherTONow->status == 1)
                                                <div class="d-flex flex-wrap justify-content-between p-1 pb-1 mb-1" style="background-color:rgb(159, 245, 188); border: 1px solid black;">
                                            @else
                                                <div class="d-flex flex-wrap justify-content-between p-1 pb-1 mb-1" style="border: 1px solid black;">
                                            @endif
                                                    <div style="width:45%" class="mt-1">
                                                        <h5 class="mb-0"> <a href="#"
                                                                class="text-dark d-inline-block align-middle">{{ $otherTONow->OrderEmri }}</a></h5>
                                                        <?php
                                                            $pershkrimi = substr($otherTONow->OrderPershkrimi, 0, 18);
                                                            if(strlen($otherTONow->OrderPershkrimi) >= 19){
                                                                echo '<span class="text-muted font-weight-normal font-italic d-block">'.$pershkrimi.'...</span>';
                                                            }else{
                                                                echo '<span class="text-muted font-weight-normal font-italic d-block">'.$pershkrimi.'</span>';
                                                            }
                                                        ?>
                                                        @if(explode('||',$otherTONow->OrderType)[0] != 'empty')
                                                            {{explode('||',$otherTONow->OrderType)[0] }}
                                                        @endif
                                                        <p>{{$otherTONow->OrderKomenti}}</p>
                                                    </div>
                                                    <div style="width:25%" class="mt-1">
                                                        <strong> <span >{{sprintf('%01.2f', $otherTONow->OrderQmimi / $otherTONow->OrderSasia )}}</span>  <sup>{{__('global.currencyShow')}}</sup><br>
                                                            @if($otherTONow->OrderSasia > 1)
                                                                <span>{{ sprintf('%01.2f', $otherTONow->OrderQmimi)}}</span>   <sup>{{__('global.currencyShow')}}</sup>
                                                            @endif
                                                        </strong>
                                                    </div>
                                                    <div style="width:15%">
                                                        <div class="form-group">
                                                            <input type="hidden" value="{{$otherTONow->OrderSasia}}">
                                                            <select style="text-align-last:center; border:none; font-size:16px;" class="form-control quantity mt-2"
                                                                disabled>
                                                                @for($i = 1; $i <= 9; $i++) <option value='{{$i}}'
                                                                    {{ $otherTONow->OrderSasia == $i ? 'selected' : '' }}>{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if(str_contains($payThisTooPhoneNr, '|'))
                                                        <button style="width:15%; padding:0px;" id="payThisTooProdBtn{{$otherTONow->id}}" class="btn btnPayNotSelectedProd allBtnPayProd{{$payThisTooPhoneNrPaLine}}"
                                                            onclick="payThisTooProd('{{$otherTONow->OrderQmimi}}','{{$otherTONow->id}}','{{$payThisTooPhoneNr}}')" >
                                                            <i class="fas fa-2x fa-cart-plus"></i>
                                                        </button>
                                                    @else
                                                        <button style="width:15%; padding:0px;" id="payThisTooProdBtn{{$otherTONow->id}}" class="btn btnPayNotSelectedProd allBtnPayProd{{$payThisTooPhoneNr}}"
                                                            onclick="payThisTooProd('{{$otherTONow->OrderQmimi}}','{{$otherTONow->id}}','{{$payThisTooPhoneNr}}')" >
                                                            <i class="fas fa-2x fa-cart-plus"></i>
                                                        </button>
                                                    @endif
                                                    @if($otherTONow->OrderSasia == $otherTONow->OrderSasiaDone)
                                                        <!-- Order Finished -->
                                                        <button class="btn btn-block btn-success shadow-none" style="width:100%;">
                                                            <strong>Bereit</strong>
                                                        </button>
                                                    @else
                                                        <!-- Order Not Finished -->
                                                        <button class="btn btn-block btn-danger shadow-none" style="width:100%;">
                                                            <strong>Nicht bereit</strong>
                                                        </button>
                                                    @endif
                                              

                                                </div>
                                            <?php $payThisTooPrice += $otherTONow->OrderQmimi; ?>
                                        @endforeach
                                       
                                        @if(str_contains($pnOnTab, '|'))
                                            <button id="payThisTooBtn{{$payThisTooPhoneNrPaLine}}" onclick="payThisToo('{{$payThisTooPrice}}','{{$payThisTooPhoneNr}}')"
                                                class="btn btn-block btnPayNotSelected allBtnPay">{{__('others.pay_that_too')}}
                                            </button>
                                        @else
                                            <button id="payThisTooBtn{{$payThisTooPhoneNr}}" onclick="payThisToo('{{$payThisTooPrice}}','{{$payThisTooPhoneNr}}')" 
                                                class="btn btn-block btnPayNotSelected allBtnPay">{{__('others.pay_that_too')}}
                                            </button>
                                        @endif
                                          
                                      
                                    </div><!-- End one client  -->
                                @endforeach
                                
                            </div>   
                        @endif
                        <br>
                    </div>
                    <!--All orders end -->



                   




                    


                    <div class="mt-3">
                        <a href="/?Res={{$_SESSION['Res']}}&t={{$_SESSION['t']}}" class="btn btn-outline-primary btn-block text-center">
                            <img src="https://img.icons8.com/nolan/64/add.png" width="20" />
                            {{__('others.add_more_items')}}
                        </a>
                    </div>


                </div>









































                <div class="col-lg-4 bg-white rounded shadow-sm mb-5" id="cartPart2">


                

                <!-- Button for paying my/all -->
                <div id="payAllOrMineDiv">
                @if(TabOrder::where([['toRes',$theResId],['tableNr',$theTable],['tabCode','!=',0],['status','!=', '9']])->whereNotIn('id',$allMyOr)->get()->count() > 0)
        
                    @if(isset($_SESSION["phoneNrVerified"]) && !$isGhost)
                        <?php
                            $totTabCHF =TabOrder::where([['toRes',$theResId],['tableNr',$theTable],['tabCode','!=',0]])->get()->sum('OrderQmimi');
                        ?>
                  
                        <input type="hidden" id="totalOfTableCHF" value="{{$totTabCHF}}">
                        <div class="d-flex justify-content-between mt-2" id="buttonsForPay">
                            <button id="btnPay1" style="width:48%;" class="btn btn-primary"
                                onclick="setPayAllMine(1,'{{Cart::total()}}','{{$theRes->priceFree}}')"> {{__('others.myOrders')}} </button>
                            <button id="btnPay2" style="width:48%;" class="btn btn-outline-primary"
                                onclick="setPayAllMine(9,'{{$totTabCHF}}','{{$theRes->priceFree}}')">{{__('others.allOrders')}} {{$totTabCHF}} CHF</button>
                        </div>
                    @endif
                @endif
                </div>

                <div class=" rounded-pill px-4 py-3 text-uppercase font-weight-bold">{{__('others.orderReview')}} 

                    @if(Auth::check())

                        <span style="margin-left:25%;">
                            @if(Piket::where('klienti_u',Auth::user()->id)->first() != null)
                                <?php $hasPo = Piket::where('klienti_u',Auth::user()->id)->first()->piket;?>
                                @if(isset($confirmData))
                                    <?php $hasPo -= $confirmData['pUsed'];?>
                                @endif
                            @else
                                <?php $hasPo = 0;?>
                            @endif
                                @if(isset($confirmData))
                                    <input type="hidden" id="userPointsB" value="{{$hasPo }}">
                                    <span id="userPointsBShow">{{$hasPo}} p ( {{$confirmData['pUsed']}} p )</span>
                                @else
                                    <input type="hidden" id="userPointsB" value="{{$hasPo}}">
                                    <span id="userPointsBShow">{{$hasPo}} p</span>
                                @endif
                        </span>
                    @endif
                </div>
                <div>
                    <h5 class="color-qrorpa">{{__('others.tipForServiceTeam')}}</h5>
                </div>
                <div class="d-flex justify-content-between flex-wrap" style="margin-top:-5px;" id="theBakshishDivAll">
                    <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>50</strong> <sup>{{__('global.currencyShowCent')}}</sup></button>
                    <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter1" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>1</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter2" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>2</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter5" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>5</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>10</strong> <sup>{{__('global.currencyShow')}}</sup></button>
                    <button id="tipWaiterCancel" onclick="setTip(this.id,'{{cart::total()}}')" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"> Stornieren </button>
                    <button id="tipWaiterCos" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2">
                        <input step="0.5" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostume('tipWaiterCos', 'tipWaiterCosVal', this.value,'{{cart::total()}}')"style="width:70%; border:none;" placeholder="andere"> <sup>{{__('global.currencyShow')}}</sup>
                    </button>
                </div>




             
                <div class="pr-4 pl-4 pb-2">
                    <p class="font-italic mb-4"></p>
                    <ul class="list-unstyled mb-4" id="totalShowCh">
                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">{{__('others.subtotal')}} </strong>
                            @if(isset($confirmData))
                                @if($confirmData['payWhat'] == 9)
                                    <?php $newPrice = $totTabCHF -$cancelOrderSum - ($confirmData['pUsed']*0.01);?>
                                    <strong><span id="subTotalCart">{{ number_format((float) $newPrice-((float)$newPrice*0.081), 2, '.', '') }}</span>  {{__('global.currencyShow')}} </strong>
                                @else
                                <?php $newPrice = Cart::total() -$cancelOrderSum - ($confirmData['pUsed']*0.01);?>
                                <strong><span id="subTotalCart">{{ number_format((float) $newPrice-((float)$newPrice*0.081), 2, '.', '') }}</span>  {{__('global.currencyShow')}} </strong>
                                @endif
                            @else
                                <strong><span id="subTotalCart">{{ number_format((float) (Cart::total()-$cancelOrderSum), 2, '.', '') }}</span>  {{__('global.currencyShow')}} </strong>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-3 border-bottom" id="bakshishViewLi" >
                            <strong class="text-muted">{{__('others.tipForServiceTeam2')}}</strong>
                            <strong><span id="bakshishView">0</span> {{__('global.currencyShow')}} </strong>                       
                        </li>




                        <!-- RReshti per piket -->
                        <li class="d-flex justify-content-between py-3 flex-wrap border-bottom" id="bakshishViewLi" style="display:none !important;">
                            @if(Auth::check())
                                @if(isset($confirmData))
                                    @if(Piket::where('klienti_u',Auth::user()->id)->first() >= $confirmData['pUsed'])
                                        <p class="text-left" style="width:80%;"><strong class="text-muted">{{__('others.ready_to_use')}}</strong></p>
                                        <p class="text-right" style="width:20%;"><strong><span>{{$confirmData['pUsed']}}</span>  p</strong></p>
                                    @else

                                    @endif
                                @else
                                    <strong class="text-muted text-left ponitsEarn"  style="width:50%;"> {{__('others.Points')}} </strong>
                                    <span id="nrOfPoints" class="text-right ponitsEarn" style="font-weight:bold; width:50%;">{{explode('.', Cart::total())[0]}}  p</span> 

                                    <strong class="text-muted text-left pointsUse"  style="width:50%; display:none"> {{__('others.PointsOnUse')}} </strong>
                                    <span id="nrOfPoints" class="text-right pointsUse" style="font-weight:bold; width:50%; display:none;"> 
                                        <div class="form-group">
                                            <input class="form-control text-center" type="number" step="1" min="1" id="pointUserAmount" style="border:none; border-bottom:1px solid lightgray;">
                                        </div>
                                    </span> 



                                    <div class="alert alert-danger text-center" style="width:100%; display:none;" id="invalidePointsUse11">
                                       {{__('others.writeAValidValuePlease')}}
                                    </div>
                                    <div class="alert alert-danger text-center" style="width:100%; display:none;" id="invalidePointsUse22">
                                        {{__('others.not_much_points')}}
                                    </div>
                                    <button onclick="earnPointsBack()"  style="width:44%; display:none;" class="btn btn-block btn-outline-dark mt-2 pointsUse">
                                        {{__('others.cancel')}} <!-- Back/Cancel -->
                                    </button>
                                    <button onclick="setUsePoints()"  style="width:44%; display:none" class="btn btn-block btn-outline-dark mt-2 pointsUse">
                                        {{__('others.confirm')}} <!-- Use (set points) -->
                                    </button>
                                



                                    <button onclick="usePoints()"  style="width:100%;" class="btn btn-block btn-outline-dark mt-2 ponitsEarn">
                                        {{__('others.usePoints')}} <!-- Use Points -->
                                    </button>
                                @endif
                            @else
                            <strong class="text-muted" style="width:100%;">{{__('others.signup_earn_points')}}</strong>
                            @endif
                            
                        </li><!-- fund   RReshti per piket -->



                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">{{__('others.TotalCost')}}</strong>
                            <h5 class="font-weight-bold "> <span class="totalOnCart">{{ number_format(Cart::total() - $cancelOrderSum, 2, '.', '') }}</span>{{__('global.currencyShow')}}</h5>
                        </li>




















                        @if(Cupon::where('toRes', $theResId)->get()->count() > 0)
                            <li class="d-flex flex-wrap justify-content-between py-3 border-bottom">
                                @if(Cookie::has('rouletteCuponId'))
                                    <?php
                                        $theCoup = Cupon::find(Cookie::get('rouletteCuponId'));
                                        if(isset($_SESSION["phoneNrVerified"]) && $theCoup != Null){
                                            $hasUsedIt = couponUsedPhoneNr::where([['phoneNr',$_SESSION["phoneNrVerified"]],['couponId',$theCoup->id]])->first();
                                        }else{
                                            $hasUsedIt = Null;
                                        }
                                    ?>
                                    @if($hasUsedIt == Null && $theCoup != Null)
                                  
                                    @else
                                        <button id="cuponInputStarter" style="width:100%" class="btn btn-outline-default" onclick="showCuponInput()">
                                            <i class="fas fa-xl fa-barcode pr-5"></i> {{__('others.useCoupon')}}
                                        </button>
                                    @endif
                                @else
                                    <button id="cuponInputStarter" style="width:100%" class="btn btn-outline-default" onclick="showCuponInput()">
                                        <i class="fas fa-xl fa-barcode pr-5"></i> {{__('others.useCoupon')}}
                                    </button>
                                @endif

                                <div style="width:100%; display:none;" class="d-flex flex-wrap justify-content-between" id="cuponInputDiv">
                                    @if(Cookie::has('rouletteCuponId') && $hasUsedIt == Null && $theCoup != Null)
                                        <div class="input-group mb-3" style="width:65%;" id="cuponInputDiv2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            </div>
                                            <input id="cuponTypedCart" type="text" class="form-control" value="{{$theCoup->codeName}}" placeholder="Code">
                                        </div>
                                        <?php
                                            if(isset($_SESSION["phoneNrVerified"])){
                                                $clPhoneNr = $_SESSION["phoneNrVerified"];
                                            }else{ $clPhoneNr = 0; }
                                        ?>
                                        <button style="width:34%; height:38px;" class="btn btn-outline-primary" onclick="checkCupon('{{$theResId}}','{{$clPhoneNr}}','{{Cart::total()}}')"
                                            id="cuponCheckBtn">Aktivieren
                                        </button>
                                        <p id="activateTheCouponText" class="text-center" style="font-size:1.2rem; width:100%;"><strong>Bitte Gutscheincode aktivieren!</strong></p>
                                    @else
                                        <div class="input-group mb-3" style="width:65%; display:none;" id="cuponInputDiv2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            </div>
                                            <input id="cuponTypedCart" type="text" class="form-control" placeholder="Code">
                                        </div>
                                        <?php
                                            if(isset($_SESSION["phoneNrVerified"])){
                                                $clPhoneNr = $_SESSION["phoneNrVerified"];
                                            }else{ $clPhoneNr = 0; }
                                        ?>
                                        <button style="width:34%; height:38px; display:none;" class="btn btn-outline-primary" onclick="checkCupon('{{$theResId}}','{{$clPhoneNr}}','{{Cart::total()}}')"
                                            id="cuponCheckBtn">Aktivieren
                                        </button>
                                    @endif
                                        <p style="display:none; width:100%;" class=" alert alert-danger" id="cuponOffError">
                                            Gutschein nicht verfügbar!
                                        </p>
                                        <p style="display:none; width:100%;" class=" alert alert-danger" id="cuponOffErrorUsed">
                                            Sie können diesen Gutschein nicht mehr verwenden!
                                        </p>
                                        <p style="display:none; width:100%;" class=" alert alert-danger" id="cuponOffErrorNotETot">
                                            Die Gesamtsumme des Warenkorbs reicht nicht aus, damit dieser Gutschein gültig ist!
                                        </p>
                                        <p style="display:none; width:100%;" class=" alert alert-danger" id="cuponOffErrorNoMLeft">
                                            Alle Coupons mit diesem Code wurden verwendet!
                                        </p>
                                     <div style="width: 100%;" id="couponsSaved"></div>
                                </div>
                            </li>
                        @endif








































                        @if($_SESSION['t'] == 500 && !isset($confirmData))
                            <li class="d-flex flex-wrap justify-content-between py-3 border-bottom">
                                <strong class="text-muted">{{__('others.extraInformation')}}</strong>
                                <div style="width:49.5%;" class="form-group">
                                    @if(isset($confirmData))
                                        <input onkeyup="setNameTA(this.value)" type="text" class="form-control" id="name" value="{{$confirmData['nameTA']}}">
                                    @else
                                        @if(!Auth::check())
                                            <input onkeyup="setNameTA(this.value)" type="text" class="form-control" id="name" placeholder="Name" rquired>
                                        @else
                                            @if(isset(explode(' ',Auth::user()->name)[0]))
                                                <input onkeyup="setNameTA(this.value)" type="text" class="form-control" id="name" value="{{explode(' ',Auth::user()->name)[0]}}">
                                            @else
                                                <input onkeyup="setNameTA(this.value)" type="text" class="form-control" id="name" placeholder="Name" rquired>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                <div style="width:49.5%;" class="form-group">
                                    @if(isset($confirmData))
                                        <input onkeyup="setLastameTA(this.value)" type="text" class="form-control" id="lastname" value="{{$confirmData['lastnameTA']}}">
                                    @else
                                        @if(!Auth::check())
                                            <input onkeyup="setLastameTA(this.value)" type="text" class="form-control" id="lastname" placeholder="Nachname" rquired>
                                        @else
                                            @if(isset(explode(' ',Auth::user()->name)[1]))
                                            <input onkeyup="setLastameTA(this.value)" type="text" class="form-control" id="lastname" value="{{explode(' ',Auth::user()->name)[1]}}">
                                            @else
                                            <input onkeyup="setLastameTA(this.value)" type="text" class="form-control" id="lastname" placeholder="Nachname" rquired>
                                            @endif
                                        @endif
                                    @endif
                                </div>

                                <p style="width:100%; opacity:0.7; margin-top:-10px;  margin-bottom:0px; ">
                                    <?php
                                        switch(date('w')){
                                            case 0: 
                                                echo __('others.Sunday'); 
                                                $TAschedule=TakeawaySchedule::where('toRes', $theResId)->first();
                                                if($TAschedule != null){
                                                    if($TAschedule->day10S != '00:00'){
                                                        $period = $TAschedule->day10S.'-'.$TAschedule->day10E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day10S.'" id="Period1S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day10E.'" id="Period1E">';
                                                    }
                                                    if($TAschedule->day20S != '00:00'){
                                                        $period2 = $TAschedule->day20S.'-'.$TAschedule->day20E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day20S.'" id="Period2S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day20E.'" id="Period2E">';
                                                    }
                                                }
                                            break;
                                            case 1: 
                                                echo __('others.Monday');
                                                $TAschedule=TakeawaySchedule::where('toRes', $theResId)->first();
                                                if($TAschedule != null){
                                                    if($TAschedule->day11S != '00:00'){
                                                        $period = $TAschedule->day11S.'-'.$TAschedule->day11E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day11S.'" id="Period1S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day11E.'" id="Period1E">';
                                                    }
                                                    if($TAschedule->day21S != '00:00'){
                                                        $period2 = $TAschedule->day21S.'-'.$TAschedule->day21E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day21S.'" id="Period2S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day21E.'" id="Period2E">';
                                                    }
                                                }
                                            break;
                                            case 2: 
                                                echo __('others.Tuesday'); 
                                                $TAschedule=TakeawaySchedule::where('toRes', $theResId)->first();
                                                if($TAschedule != null){
                                                    if($TAschedule->day12S != '00:00'){
                                                        $period = $TAschedule->day12S.'-'.$TAschedule->day12E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day12S.'" id="Period1S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day12E.'" id="Period1E">';
                                                    }
                                                    if($TAschedule->day22S != '00:00'){
                                                        $period2 = $TAschedule->day22S.'-'.$TAschedule->day22E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day22S.'" id="Period2S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day22E.'" id="Period2E">';
                                                    }
                                                }
                                            break;
                                            case 3: 
                                                echo __('others.Wednesday'); 
                                                $TAschedule=TakeawaySchedule::where('toRes', $theResId)->first();
                                                if($TAschedule != null){
                                                    if($TAschedule->day13S != '00:00'){
                                                        $period = $TAschedule->day13S.'-'.$TAschedule->day13E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day13S.'" id="Period1S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day13E.'" id="Period1E">';
                                                    }
                                                    if($TAschedule->day23S != '00:00'){
                                                        $period2 = $TAschedule->day23S.'-'.$TAschedule->day23E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day23S.'" id="Period2S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day23E.'" id="Period2E">';
                                                    }
                                                }
                                            break;
                                            case 4: 
                                                echo __('others.Thursday'); 
                                                $TAschedule=TakeawaySchedule::where('toRes', $theResId)->first();
                                                if($TAschedule != null){
                                                    if($TAschedule->day14S != '00:00'){
                                                        $period = $TAschedule->day14S.'-'.$TAschedule->day14E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day14S.'" id="Period1S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day14E.'" id="Period1E">';
                                                    }
                                                    if($TAschedule->day24S != '00:00'){
                                                        $period2 = $TAschedule->day24S.'-'.$TAschedule->day24E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day24S.'" id="Period2S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day24E.'" id="Period2E">';
                                                    }
                                                }
                                            break;
                                            case 5: 
                                                echo __('others.Friday'); 
                                                $TAschedule=TakeawaySchedule::where('toRes', $theResId)->first();
                                                if($TAschedule != null){
                                                    if($TAschedule->day15S != '00:00'){
                                                        $period = $TAschedule->day15S.'-'.$TAschedule->day15E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day15S.'" id="Period1S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day15E.'" id="Period1E">';
                                                    }
                                                    if($TAschedule->day25S != '00:00'){
                                                        $period2 = $TAschedule->day25S.'-'.$TAschedule->day25E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day25S.'" id="Period2S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day25E.'" id="Period2E">';
                                                    }
                                                }
                                            break;
                                            case 6: 
                                                echo __('others.Saturday'); 
                                                $TAschedule=TakeawaySchedule::where('toRes', $theResId)->first();
                                                if($TAschedule != null){
                                                    if($TAschedule->day16S != '00:00'){
                                                        $period = $TAschedule->day16S.'-'.$TAschedule->day16E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day16S.'" id="Period1S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day16E.'" id="Period1E">';
                                                    }
                                                    if($TAschedule->day26S != '00:00'){
                                                        $period2 = $TAschedule->day26S.'-'.$TAschedule->day26E;
                                                        echo'<input type="hidden" value="'.$TAschedule->day26S.'" id="Period2S">';
                                                        echo'<input type="hidden" value="'.$TAschedule->day26E.'" id="Period2E">';
                                                    }
                                                }
                                            break;
                                        }
                                       
                                    ?>
                                   
                                </p>
                                <div style="width:100%;" class="form-group">
                                    @if(isset($confirmData))
                                        <input onkeyup="setTimeTA(this.value)" type="text" class="form-control" id="lastname" value="{{$confirmData['timeTA']}}">
                                    @else
                                        <?php
                                            $dPl009 = '';
                                            if(isset($period)){ $dPl009 = $period; }else{$dPl009 = '';}
                                            if(isset($period2)){ $dPl009 .= ' und '.$period2; }else{$dPl009 = '';}
                                        ?>
                                        <input onkeyup="setTimeTA(this.value)" type="text" class="form-control" id="lastname" placeholder="Abholzeit ( {{$dPl009}} )">
                                    @endif
                                </div>

                                <div class="text-center alert alert-danger mt-1 p-1" id="alertExtraInfoTA" style="width:100%; display:none">
                                    <p>{{__('others.add_required_info')}}</p>
                                </div>
                                <div class="text-center alert alert-danger mt-1 p-1" id="alertExtraInfoTATime" style="width:100%; display:none">
                                    <p>{{__('others.not_work_this_point')}}</p>
                                </div>

                                
                            </li>
                        @endif
                    </ul>
                    
                </div>





                <input type="hidden" value="0" id="pUsedFromConfirm">

                <div id="privacyPolicy" class="pr-4 pl-4 mb-4">
                    <!-- <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="dataPrivacyPolicy">
                        <label style="font-size:0.8rem; font-weight:bold;" class="form-check-label" for="dataPrivacyPolicy">
                            Ich habe die <a href="{{ route('firstPage.agbdatenschutz')}}">Datenschutzerklärung</a> gelesen und stimme ihr zu 
                        </label>
                    </div> -->
                    @if ($Restrict > 0)
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="ageRestrictionCheck">
                            <label style="font-size:0.8rem; font-weight:bold;" class="form-check-label" for="ageRestrictionCheck">
                                Ich bestätige, dass ich {{$Restrict}} Jahre oder älter bin
                            </label>
                        </div>
                    @endif
                </div>



                    @include('cartComp.cartRestaurantPay')
                

                    
                    <!-- <a href="{{ route('checkout')}}" class=""></a> -->
                </div>
            </div>
        </div>
    </div>
</div>
</div>





























































































@else


<style>
    #succTrack:hover{
        color:black;
        text-decoration:none;
    }
    #succTrack{
        color:black;
        text-decoration:none;
        font-size:18px;
    }
    .rating-area{
        margin-top:10px;
        text-align: left;
    }
    label.control-label{
        font-size: 1rem;
    }
    input.form-control.btn.btn-primary{
        width: 30%;
        background: #27beae;
        color: #fff;
        border: none;
    }
    .cont{
        margin-top:0px !important;
    }
    .like-icon-background{
        background: #fff;
        padding:40px !important;
        border-radius: 50%;
        margin-top: 40px;
        margin-bottom:40px !important;
    }
    .icon-area{
        padding: 0px;
        background: rgb(123,12,179);
        background: linear-gradient(0deg, rgba(123,12,179,1) 4%, rgba(39,190,174,1) 100%, rgba(29,63,193,1) 100%);
    }
    .icon-area i{
        color: #27beae;
    }
    .thanks-area{
        padding: 20px;    
        margin-top:15px;    
    }
    .thanks-area h3{
        font-weight: bold;
        color: #27beae;
    }  
    .thanks-area span{
        font-size: 21px;
        vertical-align: middle;
        color:#484848;
        font-family: "Raleway", sans-serif;

    }  
    .thanks-area i{
        color: #13a71f;
    }
    .code-area{
        background: #27beae;
            padding: 15px;
    color: #fff;
    border-radius: 40px;
    margin-top:20px;
    }
    .ratings-area{
    margin-top: 30px;
    border: 1px solid #ffc107;
    display: inline-block;
    }
    .ml-3 {
        margin-left: 10px !important;
    }
    a#succTrackCart {
        display: inline-flex;
        text-align: left;
        color: white;
        font-size: 15px;
    }
    .rating > label:before {
        margin: 0px;
        font-size: 53px;
        font-family: FontAwesome;
        display: inline-block;
        content: "\2605";
    }
    @media (max-width: 347px) {
        .rating > label:before {
            margin: 0px;
            font-size: 45px;
            font-family: FontAwesome;
            display: inline-block;
            content: "\2605";
        }
    }
    @media (max-width: 314px) {
        .rating > label:before {
            margin: 0px;
            font-size: 35px;
            font-family: FontAwesome;
            display: inline-block;
            content: "\2605";
        }
    }
    @media (max-width: 272px) {
        .rating > label:before {
            margin: 0px;
            font-size: 25px;
            font-family: FontAwesome;
            display: inline-block;
            content: "\2605";
        }
    }
    .rating > input:checked ~ label:hover ~ label {
        color: #f9d400;
    }
    .rating > input:checked + label:hover, .rating > input:checked ~ label:hover{
        color: #f9d400;
    }

    
</style>
<div class="container mt-5 cont">
    <div class="row">
        <div class="col-lg-4 col-sm-0"></div>
        <div class="col-lg-4 col-sm-12 bg-light text-center p-3" style="padding: 0px !important;">
            <div class="col-md-12 icon-area">
                <i class="fa fa-thumbs-up fa-4x like-icon-background" aria-hidden="true"></i>
            </div>
            {{--  <img width="75%"src="storage/gifs/shopingCart01.gif" alt=""> --}}
            <h3>
               
                @if(session('success') || (Cookie::has('trackMO') && Cookie::get('trackMO') != 'not'))
                <?php
                    $order = Orders::whereDate('created_at', Carbon::today())->where([['Restaurant', $_SESSION["Res"]],['shifra',Cookie::get('trackMO')]])->first(); 
                ?>
                    @if($order != Null)
                        <div class="col-md-12 thanks-area">
                        <h3>Vielen Dank!</h3>
                        <i class="fa fa-check-circle fa-2x" aria-hidden="true" style="padding: 0px"> <span> Bestellung war erfolgreich!</span></i> 
                        </div>

                        <div class="col-md-12 code-area" id="theProdCodeAlertCart">
                            <h4><strong>Verfolgungscode:</strong></h4>
                            {{-- <h2><strong>{{session('success')}}</strong></h2> --}}
                            <h4 style="background: #fff; color: #3a3a3a; padding: 10px; box-shadow: brown; border-radius:15px; box-shadow: 2px 1px 9px 4px #464545;">
                                @if(session('success')) 
                                <strong>{{explode('|',session('success'))[1]}}</strong>
                                @else 
                                <strong>{{explode('|',Cookie::get('trackMO'))[1]}}</strong>
                                @endif
                            </h4>
                            @php
                                $TheRestaurantId = $_SESSION["Res"];
                                $TheTableId = $_SESSION["t"];
                            @endphp
                            @if($TheRestaurantId != 22 && $TheRestaurantId != 23)
                            <a  id="succTrackCart" class="mt-1" style="font-size: 15px;  color:white;" href="{{ route('trackOrder.Home') }}">                            
                                <i class="fa fa-road" aria-hidden="true"></i> Verwenden Sie diesen Code, um Ihre Bestellung zu verfolgen
                                <br>
                                <br>
                                Rechnung herunterladen
                            </a>
                            @else
                            <a  id="succTrackCart" class="mt-1" style="font-size: 15px;  color:white;" href="{{ route('trackOrder.Home') }}">                            
                                <p style="text-align: center;"><i class="fa fa-map-pin" aria-hidden="true" style="margin-bottom: 0px;"></i> ABHOLSTATION </p>
                            </a>
                                @if($TheRestaurantId == 22)
                                    <img src="storage/images/ehc-chur-check-in.jpg" alt="" style="max-width: 100%; min-height: auto; border-radius: 40px; margin-top:20px">
                                @elseif($TheRestaurantId == 23)
                                    <img src="storage/images/EHCChur2_checkout2.jpeg" alt="" style="max-width: 100%; min-height: auto; border-radius: 40px; margin-top:20px">
                                @endif
                            @endif
                        </div>  
                        
                        <script>
                            function hideTheProdCodeAlertCart(){
                                $('#theProdCodeAlertCart').hide(500);
                            }
                        </script>

                        <!-- C:\Users\erikt\Desktop\QRORPA _ INFOMANIAK\shport_registerGet20%off.txt -->
                    @else
                        <div class="col-md-12">
                            <div class="col-md-12 code-area" id="theProdCodeAlertCart">
                                Ihr Warenkorb ist leer!   
                            </div> 
                        </div> 
                    @endif
                @else
                    <div class="col-md-12">
                        <div class="col-md-12 code-area" id="theProdCodeAlertCart">
                            Ihr Warenkorb ist leer!   
                        </div> 
                    </div> 
                @endif 

                <div class="col-md-12 ">
                    <div class="col-md-12 ratings-area">
                        <div class="alert alert-success text-center p-2 mb-2 mt-2" style="font-size: 15px; display: none;" id="RatingControllerStoreThankYouMsg">
                            Vielen Dank, dass Sie QRorpa Systeme bewertet haben. So können wir uns ständig verbessern!
                        </div>
                        <p style="font-size: 15px;" class="text-center mt-3">Wie finden Sie QRorpa System?<br>
                            Bewerten Sie uns und hinterlassen Sie eine Nachricht, damit wir uns verbessern können!
                        </p>

                        <div class="rating-area" id="ratingDiv01">

                            {{Form::open(['action' => 'RatingController@store', 'method' => 'post', 'id' => 'RatingControllerStore']) }}
                             
                                <div class="rating-stars">
                                    <fieldset style="width:100%;" class="rating d-flex flex-row-reverse justify-content-between" onclick="showTheSendBtnStar()">
                                        <input type="radio" id="star5" name="stars" value="5" /><label class = "full" for="star5" style="width:15%;" title="Awesome - 5 stars"></label>   
                                        <input type="radio" id="star4" name="stars" value="4" /><label class = "full" for="star4" style="width:15%;" title="Pretty good - 4 stars"></label>
                                        <input type="radio" id="star3" name="stars" value="3" /><label class = "full" for="star3" style="width:15%;" title="Meh - 3 stars"></label>
                                        <input type="radio" id="star2" name="stars" value="2" /><label class = "full" for="star2" style="width:15%;" title="Kinda bad - 2 stars"></label>
                                        <input type="radio" id="star1" name="stars" value="1" /><label class = "full" for="star1" style="width:15%;" title="Sucks big time - 1 star"></label>
                                    </fieldset>
                                </div>
                                <br>
                                <div class="form-group" id="theSendBtnStar" style="display:none;">
                                    {{ Form::label('Kommentar', null, ['class' => 'control-label']) }}
                                    {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => 2,  'style' => 'width:100%;', 'id' => 'theSendBtnStarComment', 'required' => 'required']) !!}
                                    
                                    <p class="alert alert-danger text-center p-1 mt-2" id="theSendBtnStarError01" style="display: none;">Bitte ausfüllen!</p>

                                    {{ Form::button('Senden', ['class' => 'form-control btn btn-primary mt-2', 'style' => 'width:100%;background-color: #13a71f !important;
                                        border: none !important;', 'onclick'=>'sendBtnStar()']) }}
                                </div>
                            {{Form::close() }}

                            <script>
                                function showTheSendBtnStar(){
                                    $('#theSendBtnStar').show(1);
                                }
                                function sendBtnStar(){
                                    if($('#theSendBtnStarComment').val() == ''){
                                        $('#theSendBtnStarError01').show(400);
                                    }else{
                                        // $('#RatingControllerStore').submit();
                                        // $('input[name="stars"]:checked').val();
                                        $.ajax({
                                            url: '{{ route("ratings.store") }}',
                                            method: 'post',
                                            data: {
                                                theKom: $('#theSendBtnStarComment').val(),
                                                stars: $('input[name="stars"]:checked').val(),
                                                fromAjax: 'yes',
                                                _token: '{{csrf_token()}}'
                                            },
                                            success: (response) => {
                                                $('#RatingControllerStoreThankYouMsg').show(50);
                                                $("#ratingDiv01").load(location.href+" #ratingDiv01>*","");
                                            },
                                            error: (error) => { console.log(error); }
                                        });
                                    }
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </h3>
            <div class="col-md-12" style="margin-top: 0;">
                @if(isset($_SESSION['Res']) && isset($_SESSION['t']))
                    <a href="/?Res={{$_SESSION['Res']}}&t={{$_SESSION['t']}}" class="btn btn-outline-info btn-block"
                    style="padding:15px; margin-top:20px; color: #ffffff; border: none;  background: #27beae; font-size: 19px;"> Bestellung fortsetzen </a>
                @else
                    <a href="{{url('/')}}" class="btn btn-outline-info btn-block" style="padding:10px; margin-top:15px;"> Bestellung
                    fortsetzen </a>
                @endif
            </div>
            <div class="col-md-12" style="margin-top: 0;">
                <button class="btn btn-outline-info btn-block" style="padding:10px; margin-top:15px; color: #ffffff; border: none;  background: #27beae; font-size: 19px;" data-toggle="modal" data-target="#returnUnpaidProducts">
                    {{__('others.restore_order')}}
                </button>
            </div>

            <div class="col-md-12" style="margin-top: 0; margin-bottom: 1cm;">
                <button class="btn btn-outline-info btn-block" style="padding:10px; margin-top:15px; color: #ffffff; border: none;  background: #27beae; font-size: 19px;" data-toggle="modal" data-target="#payUnpaidProducts">
                    {{__('others.pay_my_products_online')}}
                </button>
            </div>
        </div>
        <div class="col-lg-4 col-sm-0">
        </div>
    </div>
</div>
@endif
</div>


<!-- admin to Client product transfer modal -->
<div id="payUnpaidProducts" class="modal" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color: rgb(39, 190, 175);">
                <h4 class="modal-title" style="color: white;"><strong>"{{__('others.Table')}} : {{$theTable}}"</strong></h4>
                <button type="button" class="close" data-dismiss="modal" style="color: white;"><strong>X</strong></button>
                <!-- onclick="payUnpaidProductsRefresh()" -->
            </div>

            <input type="hidden" value="" id="payUnpaidSelectedPr" >
            <!-- Modal body -->
            <div class="modal-body">
                <p class="text-center" style="color:rgb(39, 190, 175);"><strong> - {{__('others.ghostCartCode')}} - </strong></p>
                <div style="width:90%; margin-left:5%;" class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">{{__('others.ghostCartCode2')}}</span>
                    </div>
                    <input id="returnGhostCode" type="number" class="form-control" placeholder="Code" aria-label="Code" aria-describedby="basic-addon1">
                </div>
                <button style="width:90%; margin-left:5%;" class="btn btn-outline-dark" onclick="sendCodeReturnGhost()"> {{__('global.send')}} </button>
                
                <div id="returnGhostError" style="width:90%; margin-left:5%; display:none;" class="p-1 mt-2 alert alert-danger text-center">{{__('others.hello')}}</div>
                <hr>
                <?php
                    $hasOnePlusAdPr = False;
                ?>
                @foreach (TabOrder::where([['toRes',$theResId],['tableNr',$theTable],['tabCode','!=','0']])->get() as $tabO)
                    @if ( tabVerificationPNumbers::where('tabOrderId',$tabO->id)->first()->phoneNr == '0770000000')
                        <div class="d-flex" style="border-bottom:1px solid rgb(72,81,87); color:rgb(72,81,87);" onclick="payUnSelectThisToPay('{{$tabO->id}}','{{$tabO->OrderQmimi}}')">
                            <i id="payUnpaidSelTick{{$tabO->id}}" style="width:10%; color:rgb(39,190,175);" class="pt-3 far fa-2x fa-circle"></i>
                            <div  style="width:40%;">
                                <p><strong>{{$tabO->OrderSasia}}X {{$tabO->OrderEmri}}</strong></p>
                                <p style="margin-top: -15px;">{{$tabO->OrderPershkrimi}}</p>
                            </div>
                            <div  style="width:20%; font-size:11px;">
                                @if ($tabO->OrderType != 'empty')
                                    {{explode('||',$tabO->OrderType)[0]}}
                                @endif
                            </div>
                            <div  style="width:20%;">
                               <h5 class="pt-3"><strong>{{$tabO->OrderQmimi}} {{__('global.currencyShow')}}</strong></h5>
                            </div>
                        </div>
                        <?php
                            $hasOnePlusAdPr = True;
                        ?>
                    @endif
                @endforeach

                @if($hasOnePlusAdPr)
                    <div class="pt-2 d-flex justify-content-between">
                        <button class="btn btn-danger" style="width: 49.5%;">{{__('others.cancel')}}</button>
                        <button class="btn btn-success" style="width: 49.5%;" onclick="adminProdsToCart('{{$theResId}}','{{$theTable}}')">
                            <i class="fas fa-cart-arrow-down"></i> {{__('others.choose_prod_to_pay')}}
                        </button>
                    </div>
                    <div id="payUnpaidProductsError01" class="alert alert-danger text-center mt-2" style="width: 100%; display:none;">
                        <strong>{{__('others.select_one_product')}}!</strong>
                    </div>
                @else 
                    <p class="text-center"><strong>{{__('others.noOrdersFromAdmin')}}</strong></p>
                @endif
            </div>
        </div>
    </div>
</div>



<!-- Number verification modal -->
<div id="returnUnpaidProducts" class="modal mt-5">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color: rgb(39, 190, 175);">
                <h5 class="modal-title" style="color: white;"><strong>{{__('others.restore_order_tel_send2')}}</strong></h5>
                <button type="button" class="close" data-dismiss="modal" style="color: white;" onclick="returnUnpaidProductsRefresh()"><strong>X</strong></button>
            </div>
            <input type="hidden" id="cartStoresaveTheProductId">
            <!-- Modal body -->
            <div class="modal-body">
                <p id="returnUnpaidProductsCodeShowDemo"></p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="phoneNrSend" placeholder="deine Telefonnummer" style="box-shadow: inset 0 -1px 0 #ddd;">
                    <div class="input-group-append">
                        <button class="btn btn-success" onclick="returnUnpaidProductsSendNumber()" type="submit">{{__('global.send')}}</button>
                    </div>
                </div>

                <div class="mb-2 mt-2 p-2" id="returnUnpaidProductsImport" style="display: none; border:1px solid rgb(39,190,175); border-radius:10px;">
                    <p style="color: rgb(39,190,175); width:100%;" class="text-center"><strong>{{__('others.unpaidProducts')}}</strong></p>
                    
                    <div id="returnUnpaidProductsImportInfo" style="width:100%;" class="d-flex flex-wrap"></div>
                    <input type="hidden" id="returnUnpaidProductsUnpaid" value="empty">

                    <p style="color: red; width:100%; font-size:9px; display:none !important;" class="text-center" id="cartStoreP1PhoneNrProductsImportInfoMsg">
                        <i class="fas fa-exclamation-triangle" style="padding: 0px !important; margin-bottom:0px !important;">
                        </i>  Wenn dies (T:?) vor dem Namen des Produkts angezeigt wird, bedeutet dies,
                        dass dieses Produkt in einer anderen Tabelle registriert ist und in diese Tabelle übertragen wird!
                    </p>
                </div>

                <div class="input-group mb-3" id="returnUnpaidProductsCodeWrite" style="display: none;">
                    <input type="hidden" class="form-control" id="returnUnpaidProductsCode">
                    <input type="hidden" class="form-control" id="returnUnpaidProductsTimeStarted">
                    <input type="text" class="form-control" id="returnUnpaidProductsCodeUser" placeholder="Verifizierungs-Schlüssel" style="box-shadow: inset 0 -1px 0 #ddd;">
                    <div class="input-group-append">
                        <button class="btn btn-success" onclick="returnUnpaidProductsVerifyTheCode()" type="submit">{{__('others.check2')}}</button>
                    </div>
                </div>
                <p style="display:none;" id="numberVerificationTimer">{{__('others.timer')}} : <span id="numberVerificationTimerVal"></span></p>
    
                <div id="returnUnpaidProductsError" class="alert alert-danger" style="font-weight: bold; display:none;"></div>
            </div>
        </div>
    </div>
</div>

<script>

    function returnUnpaidProductsSendNumber(){
        var pNr = $('#phoneNrSend').val().replace(/ /g,'');

        if(pNr != ''){
            if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                pNr = '0'+pNr.toString().slice(3);
            }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                pNr = '0'+pNr.toString().slice(2);
            }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                pNr = '0'+pNr.toString().slice(4);
            }
        }

        if(pNr == ''){
           $('#returnUnpaidProductsError').html($('#INCwritePhoneNr').val());
           $('#returnUnpaidProductsError').show(200).delay(3000).hide(200);
        }else if(pNr.length < 9 || pNr.length > 10){
            $('#returnUnpaidProductsError').html($('#INCphoneNrNotAccepted').val());
            $('#returnUnpaidProductsError').show(200).delay(3000).hide(200);
        }else{
            $.ajax({
                url: '{{ route("cart.returnUnpaid01") }}',
                method: 'post',
                data: { 
                    phoneNr: pNr,
                    myTableNr: $('#theTable').val(),
                    myResId: $('#theRestaurant').val(),
                     _token: '{{csrf_token()}}' 
                },
                success: (res) => {
                    if(res['status'] == 'success'){
                        if(res['unpaid'] != 'empty'){
                            $('#returnUnpaidProductsCode').val(res['code']);
                            $('#returnUnpaidProductsTimeStarted').val(res['timeStart']);
                            $('#returnUnpaidProductsCodeWrite').show(500);
                            $('#numberVerificationTimer').show(500);
                            this.startNrVerifyTimer();
                            $('#returnUnpaidProductsCodeShowDemo').html('Demo-Code: '+res['code']);

                            // display pending orders 
                            var unpaid =(res['unpaid']).split('--8--');
                            $('#returnUnpaidProductsImport').show();
                            $('#cartStoreP1PhoneNrProductsImportInfoMsg').hide();
                            var unpaidShow = unpaid[1].split('-8-');
                            var thisTableNr = $('#theTable').val();
                            var hasOtherTableOrders = false;
                            $.each( unpaidShow, function( index, value ) {
                                var unpaindOnePrShow = value.split('||');
                                if(unpaindOnePrShow[2] != thisTableNr){
                                    hasOtherTableOrders = true;
                                    $('#returnUnpaidProductsImportInfo').append("<p style='width:75%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;'><span style='color:red;'>T:"+unpaindOnePrShow[2]+"</span> "+unpaindOnePrShow[0]+"</p>");
                                    $('#returnUnpaidProductsImportInfo').append("<p style='width:25%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;' class='text-right'>"+unpaindOnePrShow[1]+" CHF</p>");
                                }else{
                                    $('#returnUnpaidProductsImportInfo').append("<p style='width:75%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;'>"+unpaindOnePrShow[0]+"</p>");
                                    $('#returnUnpaidProductsImportInfo').append("<p style='width:25%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;' class='text-right'>"+unpaindOnePrShow[1]+" CHF</p>");
                                }
                            });
                            $('#returnUnpaidProductsUnpaid').val(unpaid[0]);
                            if(hasOtherTableOrders){ $('#cartStoreP1PhoneNrProductsImportInfoMsg').show(300); }
                        }else{
                            $('#returnUnpaidProductsError').html($('#OthersNoUnpaidForNr').val());
                            $('#returnUnpaidProductsError').show(200).delay(3000).hide(200);
                        }
                    }else{
                        $('#returnUnpaidProductsError').html($('#INCphoneNrNotAccepted').val());
                        $('#returnUnpaidProductsError').show(200).delay(3000).hide(200);
                    }
                },
                error: (error) => { console.log(error); alert($('#GlobalTryAgain').val()); }
            });
        }
    }

    var intervalTimerMenuRec ;
    function startNrVerifyTimer(){
        var timerStartRec = "5:00";
        $('#numberVerificationTimerVal').html(timerStartRec);

        intervalTimerMenuRec = setInterval(function() {
            var timerRec = timerStartRec.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timerRec[0], 10);
            var seconds = parseInt(timerRec[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            if (minutes < 0) clearInterval(interval);
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            $('#numberVerificationTimerVal').html(minutes + ':' + seconds);
            timerStartRec = minutes + ':' + seconds;
            if(minutes == 0 && seconds == 0)location.reload();
        }, 1000);
    }

    function returnUnpaidProductsRefresh(){
        $("#returnUnpaidProducts").load(location.href+" #returnUnpaidProducts>*","");
        clearInterval(intervalTimerMenuRec);
    }
   


    function returnUnpaidProductsVerifyTheCode(){
        if( $('#returnUnpaidProductsCodeUser').val() == ''){
            $('#returnUnpaidProductsError').html($('#INCwriteTheCode').val());
            $('#returnUnpaidProductsError').show(200).delay(3000).hide(200); 
        }else if($('#returnUnpaidProductsCodeUser').val().length != 6){
            $('#returnUnpaidProductsError').html($('#OthersCodeNotAccepted').val());
            $('#returnUnpaidProductsError').show(200).delay(3000).hide(200);
        }else{
            $.ajax({
                url: '{{ route("cart.returnUnpaid02") }}',
                method: 'post',
                data: { code:  $('#returnUnpaidProductsCode').val(),
                        codeUser: $('#returnUnpaidProductsCodeUser').val(),
                        timeStart: $('#returnUnpaidProductsTimeStarted').val(),
                        unpaid: $('#returnUnpaidProductsUnpaid').val(),
                        phoneNr: $('#phoneNrSend').val(),
                        myTableNr: $('#theTable').val(),
                        res: $('#theRestaurant').val(),
                        _token: '{{csrf_token()}}' },
                success: (res) => {
                    if(res['status'] == 'success'){
                        location.reload();
                    }else{
                        if(res['status'] == 'failCode'){
                            $('#returnUnpaidProductsCodeUser').val(''),

                            $('#returnUnpaidProductsError').html($('#OthersCodeWrong').val());
                            $('#returnUnpaidProductsError').show(200).delay(3000).hide(200);
                        }else if(res['status'] == 'failTime'){
                            $("#returnUnpaidProductsCodeWrite").load(location.href+" #cartStoreP1PhoneNrCodeWrite>*","");
                            $('#returnUnpaidProductsCodeWrite').hide();

                            $('#returnUnpaidProductsError').html($('#OthersCodeExpired').val());
                            $('#returnUnpaidProductsError').show(200).delay(3000).hide(200);
                        }
                    }
                },
                error: (error) => { console.log(error); alert($('#GlobalTryAgain').val()); }
            });
        }
    }
</script>














@endsection













@section('extra-js')
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>

<script>
    function updateQTY(id, rId,fRID) {
        var element = $('#' + id);
    

        $.ajax({
            url: '{{ route("cart.update") }}',
            method: 'post',
            data: {
                id: $('.rowIdGo'+fRID).val(),
                val: element.val(),
                _token: '{{csrf_token()}}'
            },
            success: () => {

                $('#allOrders').load('/order #allOrders', function() {
                    $('.AllExtrasOrder').hide();
                    $('#totalShowCh').load('/order #totalShowCh', function() {
                        $('#currentQTY'+fRID).val(element.val());
                    });
                });
            },
            error: (error) => {
                console.log(error);
                alert($('#GlobalTryAgain').val())
            }
        })

    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    function payCash(from, to, code) {
        alert(from + '/' + to + '/' + code);
        $.ajax({
            url: '{{ url("confirmNumber") }}',
            method: 'post',
            data: {
                fromUs: from,
                toUs: to,
                codeUs: code
            },
            success: (response) => {
                var showVer = document.getElementById('nrVerify');
                showVer.style.display = "block";
            },
            error: (error) => {
                console.log(error);
                alert($('#GlobalTryAgain').val())
            }
        })
    }

    function pay(amount, usName, usEmail, usOrder, usId) {
        var handler = StripeCheckout.configure({
            key: 'pk_test_eW8PfhPlz9rssxWWPpmT0e2100fefSuCwu', // publisher key id
            locale: 'auto',
            image: '/storage/images/stripe_logo.png',
            allowRememberMe: false,

            token: function(token) {
                // You can access the token ID with `token.id`.
                // Get the token ID to your server-side code for use.
                console.log('Token Created!!');
                console.log(token)
                $('#token_response').html(JSON.stringify(token));

                $.ajax({
                    url: '{{ url("storeOrder") }}',
                    method: 'post',
                    data: {
                        tokenId: token.id,
                        amount: amount,
                        userName: usName,
                        userEmail: usEmail,
                        userOrder: usOrder,
                        userId: usId
                    },
                    success: (response) => {
                        $(".successOrder").show("fast", function() {});
                        $(".successOrder").fadeOut(7000, function() {
                            $(this).remove();
                        });
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#GlobalTryAgain').val())
                    }
                })
            }

        });

        handler.open({
            name: 'Your data is secure with us',
            description: '',
            amount: amount * 100,
            email: $('#email').val()
        });
    }

    function removeOrderCh(rIdFinder, po, rand, toId) {
        // alert(toId);
        $('#deleteCartIt'+rand+'Btn').hide(350);
        $('#deleteCartIt'+rand+'Gif').show(350);
        $.ajax({
            url: '{{ route("cart.destroy") }}',
            method: 'delete',
            data: {
                rowId: $('.'+rIdFinder).val(),
                res: $('#theRestaurant').val(),
                t: $('#theTable').val(),
                taborderId: toId,
                _token: '{{csrf_token()}}'
            },
            success: (res) => {
                // console.log(res);
                if(res['ans'] == 'no'){
                    $('#finishOrder').show(300).delay(3500).hide();
                }else{
                    $('#orderRow' + po).hide(1200);
                    $('#allOrders').load('/order #allOrders', function() {
                        $('.AllExtrasOrder').hide();
                        $('#totalShowCh').load('/order #totalShowCh', function() {});
                    });
                    if(res['reset'] == '1'){ location.reload(); }
                }
            },
            error: (error) => {
                console.log(error);
                alert($('#GlobalTryAgain').val())
            }
        });
    }

    function removeOrderChCartOnly(rIdFinder, po,){
        // alert('yes02');
        
        $.ajax({
            url: '{{ route("cart.destroyOnlyCart") }}',
            method: 'post',
            data: {
                rowId: $('.'+rIdFinder).val(),
                _token: '{{csrf_token()}}'
            },
            success: (res) => {
            location.reload();
            },
            error: (error) => {
                console.log(error);
                alert($('#GlobalTryAgain').val())
            }
        });
    }

    function openAccFromTACart(trMO){
        $('#openAccFromTACartBtn').prop('disabed',true);
        if(!$('#createAccName').val() ||!$('#createAccLastname').val()){
            if($('#regFromTACModalErr07').is(":hidden")){ $('#regFromTACModalErr07').show(50).delay(5000).hide(50); }
            $('#openAccFromTACartBtn').prop('disabed',false);
        }else{
            email = $('#createAccEmail').val();
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(!$('#createAccEmail').val() || !regex.test(email)){
                if($('#regFromTACModalErr01').is(":hidden")){ $('#regFromTACModalErr01').show(50).delay(5000).hide(50); }
                $('#openAccFromTACartBtn').prop('disabed',false);
            }else {
                $.ajax({
                    url: '{{ route("saMngUsr.checkIfAUserExistsWithEmail") }}',
                    method: 'post',
                    data: {theEm: email, _token: '{{csrf_token()}}' },
                    success: (respo) => {
                        respo = $.trim(respo);
                        if(respo == 'usrEx'){
                            if($('#regFromTACModalErr02').is(":hidden")){ $('#regFromTACModalErr02').show(50).delay(5000).hide(50); }
                            $('#openAccFromTACartBtn').prop('disabed',false);
                        }else{
                            if(!$('#createAccPass1').val() || $('#createAccPass1').val().length < 8){
                                if($('#regFromTACModalErr03').is(":hidden")){ $('#regFromTACModalErr03').show(50).delay(5000).hide(50); }
                                $('#openAccFromTACartBtn').prop('disabed',false);
                            }else if(!$('#createAccPass2').val()){
                                if($('#regFromTACModalErr04').is(":hidden")){ $('#regFromTACModalErr04').show(50).delay(5000).hide(50); }
                                $('#openAccFromTACartBtn').prop('disabed',false);
                            }else if($('#createAccPass1').val() != $('#createAccPass2').val()){
                                if($('#regFromTACModalErr05').is(":hidden")){ $('#regFromTACModalErr05').show(50).delay(5000).hide(50); }
                                $('#openAccFromTACartBtn').prop('disabed',false);
                            }else{
                                $.ajax({
                                    url: '{{ route("saMngUsr.registerTemUserRes") }}',
                                    method: 'post',
                                    data: {
                                        theEm: email, 
                                        pass: $('#createAccPass1').val(), 
                                        trM: trMO, 
                                        res:  $('#theRestaurant').val(),
                                        name: $('#createAccName').val(),
                                        lastname: $('#createAccLastname').val(),
                                        _token: '{{csrf_token()}}' },
                                    success: (respo) => {
                                        respo = $.trim(respo);
                                        if(respo == 'orderNull'){
                                            if($('#regFromTACModalErr06').is(":hidden")){ $('#regFromTACModalErr06').show(50).delay(5000).hide(50); }
                                            $('#openAccFromTACartBtn').prop('disabed',false);
                                        }else if(respo == 'usrPlSuccess'){
                                            if($('#regFromTACModalScc01').is(":hidden")){ $('#regFromTACModalScc01').show(50).delay(10000).hide(50); }
                                            $('#openAccFromTACartBtn').prop('disabed',false);
                                            $('#createAccName').val('');
                                            $('#createAccLastname').val('');
                                            $('#createAccEmail').val('');
                                            $('#createAccPass1').val('');
                                            $('#createAccPass2').val('');
                                        }
                                        
                                    },
                                    error: (error) => { console.log(error); }
                                });
                            }
                        }
                    },
                    error: (error) => { console.log(error); }
                });
                
            }
        }
    }
</script>

@include('cartComp.cartRestaurantScript')

@endsection