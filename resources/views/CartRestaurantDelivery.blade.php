@extends('layouts.appOrders')



<!-- 1300 kodi -->


<?php
    use App\Restorant;
    use App\Piket;
    use App\Produktet;
    use App\Cupon;
use App\DeliveryPLZ;
use App\DeliverySchedule;
use App\TakeawaySchedule;
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION['Res'])){
        $theRes = Restorant::find($_SESSION['Res']);
        $theResId = $_SESSION['Res'];
    }
        
    
?>
   @if(isset($_SESSION['Res']))
        <input type="hidden" value="{{$_SESSION['Res']}}" id="theRestaurant">
    @endif

<style>
    .noBorderBtn:focus{
      
        outline:none;
        box-shadow:none !important;
    }

select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
border:none;
}

#pointUserAmount{
    outline: none;
box-shadow:none !important;
}

body { font-size: 16px; }
input, select, { font-size: 100%; }



</style>






@section('content')
@include('inc.messagesFull')

<?php
        $orderSend = "";
    ?>

<div class="successOrder container" id="successOrder">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                Die Zahlung ist vollständig, danke für Ihre Bestellung.
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('successOrder').style.display = "none";
</script>



@if(Cart::count() > 0)






<div id="allOrders">

    <div class="pb-5">
        <div class="container mt-5">




            <div class="row">
                <div class="col-lg-8 p-3 bg-white rounded shadow-sm mb-5">
                    <!-- Shopping cart table -->
                    <div class="table-responsive">
                        <div class="col-sm-12 col-md-12 col-lg-12 pb-2 text-center">
                            <h3 class="color-qrorpa" style="font-weight:bold;"><span id="cartCountCh">{{Cart::count()}}</span> Artikel </h3>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 pb-2 d-flex justify-content-between">
                        
                            <?php
                                
                            ?>
                            @if(isset($_SESSION["Res"]))

                                <span class="color-qrorpa" style="font-size:19px; width:70%; font-weight:bold;">{{Restorant::find($_SESSION["Res"])->emri}}</span>
                                 <span style="font-size:19px; margin-left:30%;  width:35%; font-weight:bold;" class="text-right color-qrorpa"> Delivery </span>
                            @else
                                Kein Restaurant
                            @endif
                        </div>








                        <div class="d-flex justify-content-between">
                            <div style="width:40%">
                                <p><strong>Produkte</strong></p>
                            </div>
                            <div style="width:30%">
                                <p><strong>Preis/Gesamt</strong></p>
                            </div>
                            <div style="width:15%">
                                <p><strong> Menge</strong></p>
                            </div>
                            <div style="width:15%">
                                <p><strong></strong></p>
                            </div>
                        </div>














                        <?php
                                $porosiaSend = "";
                                $step = 1;
                                $orderExtras = 1;
                                
                                $point = 0;
                                $ExtraStephh = 0;

                                $Restrict = 0;
                            ?>
                            
                        @foreach(Cart::content() as $item)
                        <?php
                            if($step++ == 1){
                                $porosiaSend .= $item->name."-8-".$item->options->persh."-8-".$item->options->ekstras.'-8-'.$item->qty.'-8-'.$item->price.'-8-'.explode('||',$item->options->type)[0].'-8-'.$item->options->koment.'-8-'.$item->id;
                            }else{
                                $porosiaSend .= '---8---'.$item->name."-8-".$item->options->persh."-8-".$item->options->ekstras.'-8-'.$item->qty.'-8-'.$item->price.'-8-'.explode('||',$item->options->type)[0].'-8-'.$item->options->koment.'-8-'.$item->id;
                            }   

                           

                            if(Produktet::find($item->id) != null){
                                if(Produktet::find($item->id)->restrictPro == 18){
                                    $Restrict = 18;
                                }else if(Produktet::find($item->id)->restrictPro == 16){
                                    if($Restrict != 18){
                                        $Restrict = 16;
                                    }
                                }
                            }
                            
                            
                        ?>



                        <div class="d-flex justify-content-between" id="orderRow{{$point}}">
                            <div style="width:45%" class="mt-1">
                                <h5 class="mb-0"> <a href="#"
                                        class="text-dark d-inline-block align-middle">{{ $item->name }}</a></h5>
                                <?php
                                    $pershkrimi = substr($item->options->persh, 0, 18);
                                    if(strlen($item->options->persh) >= 19){
                                        echo '<span class="text-muted font-weight-normal font-italic d-block">'.$pershkrimi.'...</span>';
                                    }else{
                                        echo '<span class="text-muted font-weight-normal font-italic d-block">'.$pershkrimi.'</span>';
                                    }
                                ?>
                                {{explode('||',$item->options->type)[0] }}
                                <p>{{$item->options->koment}}</p>

                            </div>
                            <div style="width:25%" class="mt-1">
                                <strong> <span id="setPrice{{$item->rowId}}">{{sprintf('%01.2f', $item->price)}}</span>  <sup>CHF</sup><br>
                                    @if($item->qty > 1)
                                      <span id="setPriceSasiaAll{{$ExtraStephh}}">{{ sprintf('%01.2f', $item->price * $item->qty)}}</span>   <sup>CHF</sup>
                                    @endif
                                    </strong>
                            </div>
                            <div style="width:15%">
                                <div class="form-group">
                                    <input type="hidden" value="{{$item->qty}}" id="currentQTY{{$ExtraStephh}}">
                                    <select style="text-align-last:center; border:none; font-size:16px;" class="form-control shadow-none quantity mt-2"
                                        onchange="updateQTY(this.id,'{{$item->rowId}}','{{$ExtraStephh}}')"
                                        id="drop{{$item->id}}{{$item->rowId}}" data-id="{{ $item->rowId }}">
                                        @for($i = 1; $i <= 9; $i++) <option value='{{$i}}'
                                            {{ $item->qty == $i ? 'selected' : '' }}>{{$i}}</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                            <div style="width:15%" class="text-right">
                                <input type="hidden" value="{{$item->rowId}}" class="rowIdGo{{$ExtraStephh}}">
                                <button type="button" onclick="removeOrderCh('rowIdGo{{$ExtraStephh}}','{{$point++}}')"
                                    class="btn btn-default">
                                    <i class="far fa-trash-alt fa-2x mt-1"></i>
                                </button>
                            </div>
                        </div>
                        <div style="border-bottom:1px solid lightgray; padding-bottom:10px; margin-top:-10px;"
                            id="buttonExt{{$orderExtras}}">
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
                                <button class="btn btn-block btn-outline-default"
                                    onclick="showOrderExtras('{{$orderExtras}}')">
                                    Extras anzeigen
                                </button>
                            @endif
                            @endif
                        </div>

                        <div id="divExtra{{$orderExtras}}" class="d-flex flex-row flex-wrap">

                            <?php
                              
                                    if($item->options->ekstras ==""){
                                        echo '<p class="AllExtrasOrder orderExtra{{$orderExtras}}"> No extra ingridients </p>';
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
                                                        <button
                                                            onclick="removeThisExtraFromProd('ExtraShowRev{{$extraEmriGO}}O{{$ExtraStephh}}O{{$ordExtD21}}','{{$extProOne}}','{{$ExtraStephh}}','{{$extProD2[1]}}','{{$extProD2[0]}}')"
                                                            class="btn btn-default btn-sm"><i class="fas fa-times fa-sm" ></i> {{$extProD2[0]}} {
                                                            {{$extProD2[1]}} }
                                                        </button>
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






                        @endforeach

                    </div>
                    <!--All orders end -->


                    <script>
                    function removeThisExtraFromProd(thisId, extOne, p1, ExtPrice, p2) {

                       
                        var rIdGo = $('.rowIdGo'+p1+'').val();
                        var newP =parseFloat($('#setPrice'+rIdGo+'').html()) - parseFloat(ExtPrice);
                        $('#setPrice'+rIdGo+'').html(parseFloat(newP).toFixed(2));
                        
                        // alert(ExtPrice);
                       
                        // console.log(thisId);
                        // console.log(extOne);
                        // console.log( $('.AllExtraGo'+p1+'').val(),);
                       
                        console.log(rIdGo);
                        console.log('-------------');
        

                        $.ajax({
                            url: '{{ route("produktet.CartReDel") }}',
                            method: 'post',
                            data: {
                                elementId: rIdGo,
                                extPro: extOne,
                                allExtra: $('.AllExtraGo'+p1+'').val(),
                                _token: '{{csrf_token()}}'
                            },
                            success: (response) => {
                                var current = ($('#CartTotalFooter').text()).split(" ")[0];
                                var price = extOne.split("||")[2];
                                $('#CartTotalFooter').text((current - price).toFixed(2) + ' CHF');

                                var data = JSON.parse(response);
                                    // console.log(data.rowId);
                                    // console.log(response);
                                    // console.log(data.options);
                                    
                         
                
                                    $('.rowIdGo'+p1+'').val(data.rowId);
                                    var options = data.options;
                                    $('.AllExtraGo'+p1+'').val(options.ekstras);
                                    
                                

                                    $('#setPrice'+rIdGo+'').attr('id','setPrice'+data.rowId+'')
                                    

                                $('#' + thisId).hide(100);
                                // $('#footerShowOrdersMobile').load('/ #footerShowOrdersMobile',
                                // function() {
                                    $('#cartPart2').load('/order #cartPart2',function() {
                                        $('#setPriceSasiaAll'+p1).html(parseFloat(parseFloat($('#setPrice'+data.rowId).html()) * parseFloat($('#currentQTY'+p1).val())) );
                                    });
                                // });
                            },
                            error: (error) => {
                                console.log(error);
                                alert('Oops! Something went wrong')
                            }
                        })
                    }
                    </script>




















                    <div>
                        <a href="/?Res={{$_SESSION['Res']}}"
                            class="btn btn-outline-primary btn-block text-center">
                            <img src="https://img.icons8.com/nolan/64/add.png" width="20" />
                            Weitere Artikel hinzufügen
                        </a>
                    </div>







                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <script>
                    $(document).ready(function() {
                        $('.AllExtrasOrder').hide();
                    });

                    function showOrderExtras(orderExt) {
                        $('.orderExtra'+orderExt).show('slow');
                        $('#buttonExt' + orderExt).hide('slow');
                        $('#divExtra' + orderExt).attr('style',
                            "border-bottom:1px solid lightgray; padding-bottom:15px;");

                    }
                    </script>





<style>
    .waiterTipBtns{
        font-size:14px;
    }
    .slider:before{
            height: 19px;
            width: 20px;
            left: 3px;
            bottom: 3px;
    }
</style>





                </div>









































                <div class="col-lg-4 bg-white rounded shadow-sm mb-5" id="cartPart2">



                        <!-- Shoots free -->

                 

                        <!-- Shoots free -->



































                <div class=" rounded-pill px-4 py-3 text-uppercase font-weight-bold">Bestellübersicht 

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
                    <h5 class="color-qrorpa">Kaffee fur Serviceteam</h5>
                </div>
                <div class="d-flex justify-content-between flex-wrap" style="margin-top:-5px;">
                    <?php
                        if(isset($confirmData)){
                            
                            if($confirmData['tipValue'] == '0.5'){
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>50</strong> <sup>Rp</sup></button>    <?php
                            }else{
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>50</strong> <sup>Rp</sup></button>   <?php
                            }

                            if($confirmData['tipValue'] == '1'){
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter1" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>1</strong> <sup>CHF</sup></button>    <?php
                            }else{
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter1" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>1</strong> <sup>CHF</sup></button>   <?php
                            }

                            if($confirmData['tipValue'] == '2'){
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter2" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>2</strong> <sup>CHF</sup></button>    <?php
                            }else{
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter2" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>2</strong> <sup>CHF</sup></button>   <?php
                            }

                            if($confirmData['tipValue'] == '5'){
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter5" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>5</strong> <sup>CHF</sup></button>    <?php
                            }else{
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter5" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>5</strong> <sup>CHF</sup></button>   <?php
                            }

                            if($confirmData['tipValue'] == '10'){
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>10</strong> <sup>CHF</sup></button>    <?php
                            }else{
                                ?> <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>10</strong> <sup>CHF</sup></button>   <?php
                            }
                            ?>
                            <button id="tipWaiterCancel" onclick="setTip(this.id,'{{cart::total()}}')" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"> Stornieren </button>
                            <?php
                            if($confirmData['tipValue'] == 'other'){
                                ?> 
                                 <button id="tipWaiterCos" style="width:48%;" type="button" class="btn selectedTip waiterTipBtns mt-2">
                                    <input value="{{$confirmData['tipCHF']}}" step="0.5" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostume('tipWaiterCos', 'tipWaiterCosVal', this.value,'{{cart::total()}}')"style="width:70%; border:none;" placeholder="andere"> <sup>CHF</sup>
                                </button>
                                <?php
                            }else{
                                ?> 
                                <button id="tipWaiterCos" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2">
                                   <input step="0.5" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostume('tipWaiterCos', 'tipWaiterCosVal', this.value,'{{cart::total()}}')"style="width:70%; border:none;" placeholder="andere"> <sup>CHF</sup>
                               </button>
                               <?php
                            }
                           




                        }else{
                            ?>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>50</strong> <sup>Rp</sup></button>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter1" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>1</strong> <sup>CHF</sup></button>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter2" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>2</strong> <sup>CHF</sup></button>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter5" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>5</strong> <sup>CHF</sup></button>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>10</strong> <sup>CHF</sup></button>
                            <button id="tipWaiterCancel" onclick="setTip(this.id,'{{cart::total()}}')" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"> Stornieren </button>
                            <button id="tipWaiterCos" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2">
                                <input step="0.5" min="0" id="tipWaiterCosVal" type="number" onkeyup="setCostume('tipWaiterCos', 'tipWaiterCosVal', this.value,'{{cart::total()}}')"style="width:70%; border:none;" placeholder="andere"> <sup>CHF</sup>
                            </button>
                            <?php
                        }
                    ?>
                
                    
                </div>













































             
                <div class="pr-4 pl-4 pb-2">
                    <p class="font-italic mb-4"></p>
                    <ul class="list-unstyled mb-4" id="totalShowCh">
                        
                      

                        @if ($theRes->resTvsh == 0)
                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">Zwischensumme </strong>
                            @if(isset($confirmData))
                                <?php $newPrice = Cart::total() - ($confirmData['pUsed']*0.01);?>
                                <strong><span id="subTotalCart">{{ number_format((float) $newPrice, 2, '.', '') }}</span>  CHF </strong>
                            @else
                                <strong><span id="subTotalCart">{{ number_format((float) Cart::total(), 2, '.', '') }}</span>  CHF </strong>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">MwSt 0.00%</strong>
                            <strong><span id="tvshSpanCart">{{ number_format(0, 2, '.', '') }}</span> CHF </strong>
                        </li>
                        @else
                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">Zwischensumme </strong>
                            @if(isset($confirmData))
                                <?php $newPrice = Cart::total() - ($confirmData['pUsed']*0.01);?>
                                <strong><span id="subTotalCart">{{ number_format((float) $newPrice-($newPrice*0.081), 2, '.', '') }}</span>  CHF </strong>
                            @else
                                <strong><span id="subTotalCart">{{ number_format((float) Cart::total()-(Cart::total()*0.081), 2, '.', '') }}</span>  CHF </strong>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">MwSt 8.10%</strong>
                            @if(isset($confirmData))
                            <?php $newPrice = Cart::total() - ($confirmData['pUsed']*0.01);?>
                            <strong><span id="tvshSpanCart">{{ number_format((float)$newPrice*0.081, 2, '.', '') }}</span> CHF </strong>
                            @else
                            <strong><span id="tvshSpanCart">{{ number_format((float)Cart::total()*0.081, 2, '.', '') }}</span> CHF </strong>
                            @endif
                        </li>
                        @endif

                        <li class="d-flex justify-content-between py-3 border-bottom" id="bakshishViewLi" >
                            <strong class="text-muted">Kellner Trinkgeld</strong>
                            @if(isset($confirmData))
                                <strong><span id="bakshishView">{{$confirmData['tipCHF']}}</span> CHF </strong>
                            @else
                                <strong><span id="bakshishView">0.00</span> CHF </strong>
                            @endif
                        </li>

                        
                        <li class="d-flex justify-content-between py-3 border-bottom" id="deliveryFeeViewLi" >
                            <strong class="text-muted">Liefergebühr</strong>
                            @if(isset($confirmData))
                                <strong><span id="deliveryFeeView">{{$confirmData['deliveryfee']}}</span> CHF </strong>
                            @else
                                <strong><span id="deliveryFeeView">0.00</span> CHF </strong>
                            @endif
                        </li>





                        <!-- RReshti per piket -->
                        <li class="d-flex justify-content-between py-3 flex-wrap border-bottom" id="piketViewLi" style="display:none !important;">
                            @if(Auth::check())
                                @if(isset($confirmData))
                                    @if(Piket::where('klienti_u',Auth::user()->id)->first() >= $confirmData['pUsed'])
                                        <p class="text-left" style="width:80%;"><strong class="text-muted">Sie sind im Begriff zu verwenden</strong></p>
                                        <p class="text-right" style="width:20%;"><strong><span>{{$confirmData['pUsed']}}</span>  p</strong></p>
                                    @else

                                    @endif
                                @else
                                    <strong class="text-muted text-left ponitsEarn"  style="width:50%;"> Punkte </strong>
                                    <span id="nrOfPoints" class="text-right ponitsEarn" style="font-weight:bold; width:50%;">{{explode('.', Cart::total())[0]}}  p</span> 

                                    <strong class="text-muted text-left pointsUse"  style="width:50%; display:none">  Punkte verwenden </strong>
                                    <span id="nrOfPoints" class="text-right pointsUse" style="font-weight:bold; width:50%; display:none;"> 
                                        <div class="form-group">
                                            <input class="form-control shadow-none text-center" type="number" step="1" min="1" id="pointUserAmount" style="border:none; border-bottom:1px solid lightgray;">
                                        </div>
                                    </span> 



                                    <div class="alert alert-danger text-center" style="width:100%; display:none;" id="invalidePointsUse11">
                                        Bitte schreiben Sie einen gültigen Wert!
                                    </div>
                                    <div class="alert alert-danger text-center" style="width:100%; display:none;" id="invalidePointsUse22">
                                        Sie haben nicht so viele Punkte!
                                    </div>
                                    <button onclick="earnPointsBack()"  style="width:44%; display:none;" class="btn btn-block btn-outline-dark mt-2 pointsUse">
                                        Stornieren <!-- Back/Cancel -->
                                    </button>
                                    <button onclick="setUsePoints()"  style="width:44%; display:none" class="btn btn-block btn-outline-dark mt-2 pointsUse">
                                        Einstellen <!-- Use (set points) -->
                                    </button>
                                



                                    <button onclick="usePoints()"  style="width:100%;" class="btn btn-block btn-outline-dark mt-2 ponitsEarn">
                                        Gebrauchspunkt zum Verkauf <!-- Use Points -->
                                    </button>
                                @endif
                            @else
                            <strong class="text-muted" style="width:100%;">Anmelden, um Punkte zu erhalten und zu verwenden!</strong>
                            @endif
                            
                        </li><!-- fund   RReshti per piket -->



                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">Gesamt</strong>
                            @if(isset($confirmData))
                                <h5 class="font-weight-bold "> <span class="totalOnCart">{{ Cart::total()+$confirmData['tipCHF']-($confirmData['pUsed']*0.01)-$confirmData['codeUsed']+$confirmData['deliveryfee'] }}</span> CHF </h5>
                            @else
                                <h5 class="font-weight-bold "> <span class="totalOnCart">{{ Cart::total() }}</span>  CHF </h5>
                            @endif
                           
                        </li>















                        @if(Cupon::where('toRes', $theResId)->get()->count() > 0 && !isset($confirmData))
                            <li class="d-flex flex-wrap justify-content-between py-3 border-bottom">
                                <button id="cuponInputStarter" style="width:100%" class="btn btn-outline-default" onclick="showCuponInput()">
                                <i class="fas fa-xl fa-barcode pr-5"></i> Gutschein verwenden</button>

                                <div style="width:100%; display:none;" class="d-flex flex-wrap justify-content-between" id="cuponInputDiv">
                                    <div class="input-group mb-3" style="width:75%; display:none;" id="cuponInputDiv2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input id="cuponTypedCart" type="text" class="form-control shadow-none" placeholder="Code">
                                    </div>
                                    <button style="width:22%; height:38px; display:none;" class="btn btn-outline-primary" onclick="checkCupon('{{$theResId}}')"
                                     id="cuponCheckBtn">Check</button>
                                     <p style="display:none; width:100%;" class=" alert alert-danger" id="cuponOffError">Gutschein nicht verfügbar!</p>
                                     <p style="display:none; width:100%;" class=" alert alert-success" id="cuponOffOK">
                                        Gutschein verfügbar! ( <span id="cuponOffOKText"></span>  )</p>
                                    <p style="display:none; width:100%;" class=" alert alert-danger" id="cuponOffError">Gutschein nicht verfügbar!</p>
                                     <p style="display:none; width:100%;" class=" alert alert-danger" id="cuponOffErrorUsed">Sie können diesen Gutschein nicht mehr verwenden!</p>
                                     <p style="display:none; width:100%;" class=" alert alert-danger" id="cuponOffErrorNotETot">Die Gesamtsumme des Warenkorbs reicht nicht aus, damit dieser Gutschein gültig ist!</p>

                                    <div style="width: 100%;" id="couponsSaved"></div>
                                </div>
                               
                            </li>

                            <script>
                                function showCuponInput(){
                                    $('#cuponInputStarter').hide(200);
                                    $('#cuponInputDiv').show(200);
                                    $('#cuponInputDiv2').show(200);
                                    $('#cuponCheckBtn').show(200);
                                }
                                function checkCupon(resId){
                                    $.ajax({
                                        url: '{{ route("cupons.checkCupon") }}',
                                        method: 'post',
                                        data: {
                                            res: resId,
                                            code: $('#cuponTypedCart').val(),
                                            cartTot: parseFloat($('.totalOnCart').html()),
                                            clPN : 'isDelivery',
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (res) => {
                                            res = res.replace(/\s/g, '');
                                            if(res == 'no' ){
                                                $('#cuponOffError').show(200).delay(3000).hide(200);
                                            }else if(res == 'noUsed'){
                                                $('#cuponOffErrorUsed').show(200).delay(3000).hide(200);
                                            }else if(res == 'noETot'){
                                                $('#cuponOffErrorNotETot').show(200).delay(4000).hide(200);
                                            }else{
                                                $( "#cuponCheckBtn" ).prop( "disabled", true );
                                                $( "#cuponTypedCart" ).prop( "disabled", true );

                                                switch(res['typeCo']){
                                                    case 1:
                                                        this.procesCoupun1(res,1);
                                                    break;
                                                    case 2:
                                                        this.procesCoupun1(res,2);
                                                    break;
                                                    case 3:
                                                        this.procesCoupun1(res,3);
                                                    break;
                                                }
                                            }
                                        },
                                        error: (error) => {
                                            console.log(error);
                                            alert('Oops! Something went wrong')
                                        }
                                    });
                                }

                                function procesCoupun1(res,typ){
                                   
                                   if(typ == 1){var coupVal = res['valueOff'] ;}
                                   else if(typ == 2){var coupVal = res['valueOffMoney'] ;}
                                   else if(typ == 3){var coupVal = res['prodName'] ;}
                                   
                                    
                                   console.log('coupVal: '+coupVal);

                                   if(typ == 2 || typ == 1){
                                       if($('#tipValueSendId').val() != 0){

                                           var cart = parseFloat($('.totalOnCart').html()).toFixed(2);
                                           var tip = parseFloat($('#tipValueSendId').val()).toFixed(2);

                                           // var points = parseFloat($('.pointsUsedIdFS').html()).toFixed(2);   +(points *0.01)
                                           var beforeTotal =parseFloat(parseFloat(cart)-parseFloat(tip)).toFixed(2);
                                           if(typ == 1){ var offDeal = parseFloat(parseFloat(coupVal*0.01)*beforeTotal).toFixed(2);}
                                           else{ var offDeal = parseFloat(res['valueOffMoney']).toFixed(2); }
                                           
                                           var afterTotalPre = parseFloat(beforeTotal-offDeal).toFixed(2);
                                           var afterTotal = parseFloat(parseFloat(afterTotalPre)+parseFloat(tip)).toFixed(2);

                                           var afterSubTotal = afterTotalPre;
                                       }else{       
                                           var beforeTotal =parseFloat($('.totalOnCart').html()).toFixed(2);
                                           if(typ == 1){ var offDeal = parseFloat(parseFloat(coupVal*0.01)*beforeTotal).toFixed(2);}
                                           else{ var offDeal = res['valueOffMoney']; }
                                           var afterTotal = parseFloat(beforeTotal-offDeal).toFixed(2);
                                           var afterSubTotal = afterTotal;
                                       }

                                       $('.totalOnCart').html(afterTotal);
                                       $('.subTotalCart').html(afterSubTotal);
                                   }

                                   $('#codeUsedValueID').val(offDeal);

                                   // if( $('#codeUsedValueID').val() == '')
                                   //     $('#codeUsedValueID').val(res['id']);
                                   // else
                                   //     $('#codeUsedValueID').val($('#codeUsedValueID').val()+'||'+res['id']);
                               
                                   // Alert the user SUCCESS
                                   if(typ == 1){
                                       $('#couponsSaved').append(' <p style="width:100%;" class=" alert alert-success mb-1">'+res["codeName"]+'-> '+res["valueOff"]+' % => '+offDeal+' CHF </p>');
                                   }else if(typ == 2){
                                       $('#couponsSaved').append(' <p style="width:100%;" class=" alert alert-success mb-1">'+res["codeName"]+'-> '+res["valueOffMoney"]+' CHF</p>');
                                   }else if(typ == 3){
                                       $('#couponsSaved').append(' <p style="width:100%;" class=" alert alert-success mb-1">'+res["codeName"]+'-> kostenloses Produkt : '+res['prodName']+' </p>');
                                   }

                                   $( "#cuponCheckBtn" ).prop( "disabled", true );
                                   $( "#cuponTypedCart" ).prop( "disabled", true );
                               
                                }
                            </script>
                        @elseif(isset($confirmData) && $confirmData['codeUsed'] != 0)
                            <li class="d-flex flex-wrap justify-content-between py-3 border-bottom">
                                <div style="width:100%;" class="d-flex flex-wrap justify-content-between" id="cuponInputDiv">
                                    <div class="input-group mb-3" style="width:100%;" id="cuponInputDiv2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input id="cuponTypedCartAfter" type="text" class="form-control shadow-none" placeholder="{{- $confirmData['codeUsed']}} CHF" disabled>
                                    </div>
                                </div>
                            </li>
                        @endif

                        

                
                        @if (isset($confirmDataError02))
                        <div class="alert alert-danger text-center" id="inputsEmptyDelRes01">
                            <strong>{{$confirmDataError02['errorMsg']}}</strong>
                        </div>
                        @endif
                        <script>
                            $([document.documentElement, document.body]).animate({
                                scrollTop: $("#inputsEmptyDelRes01").offset().top
                            }, 1500);
                        </script>



                        @if(!isset($confirmData))

                        
                            <li class="d-flex flex-wrap justify-content-between py-3 border-bottom">
                                <strong class="text-muted">Informationen für die Lieferung!</strong>

                                @if(isset($confirmDataError))
                                    <p style="width:100%; color:red; font-weight:bold;" class="mt-2 pt-2">{{$confirmDataError['errorMsg']}}</p>
                                @endif

                                <div style="width:49.5%;" class="form-group">
                                    @if(isset($confirmDataError))
                                        <input onkeyup="setNameDe(this.value)" type="text" class="form-control shadow-none" id="name" value="{{$confirmDataError['nameTA']}}">
                                    @else
                                        @if(!Auth::check())
                                            <input onkeyup="setNameDe(this.value)" type="text" class="form-control shadow-none" id="name" placeholder="Name" required>
                                        @else
                                            @if(isset(explode(' ',Auth::user()->name)[0]))
                                                <input onkeyup="setNameDe(this.value)" type="text" class="form-control shadow-none" id="name" value="{{explode(' ',Auth::user()->name)[0]}}">
                                            @else
                                                <input onkeyup="setNameDe(this.value)" type="text" class="form-control shadow-none" id="name" placeholder="Name" required>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                <div style="width:49.5%;" class="form-group">
                                    @if(isset($confirmDataError))
                                        <input onkeyup="setLastameDe(this.value)" type="text" class="form-control shadow-none" id="lastname" value="{{$confirmDataError['lastnameTA']}}">
                                    @else
                                        @if(!Auth::check())
                                            <input onkeyup="setLastameDe(this.value)" type="text" class="form-control shadow-none" id="lastname" placeholder="Nachname" required>
                                        @else
                                            @if(isset(explode(' ',Auth::user()->name)[1]))
                                            <input onkeyup="setLastameDe(this.value)" type="text" class="form-control shadow-none" id="lastname" value="{{explode(' ',Auth::user()->name)[1]}}">
                                            @else
                                            <input onkeyup="setLastameDe(this.value)" type="text" class="form-control shadow-none" id="lastname" placeholder="Nachname" required>
                                            @endif
                                        @endif
                                    @endif
                                </div>



                                <div style="width:100%;">
                                    <?php
                                     $todayWD = date('w'); //1-mon 2-tue 3 4 5 6 0
                                     $dSch = DeliverySchedule::where('toRes',$theResId)->first();
                                        if($dSch != NULL){
                                            if($todayWD == 1){
                                                $start1 = $dSch->day11S; $end1 = $dSch->day11E; $start2 = $dSch->day21S; $end2 = $dSch->day21E;
                                            }else if($todayWD == 2){
                                                $start1 = $dSch->day12S; $end1 = $dSch->day12E; $start2 = $dSch->day22S; $end2 = $dSch->day22E;
                                            }else if($todayWD == 3){
                                                $start1 = $dSch->day13S; $end1 = $dSch->day13E; $start2 = $dSch->day23S; $end2 = $dSch->day23E;
                                            }else if($todayWD == 4){
                                                $start1 = $dSch->day14S; $end1 = $dSch->day14E; $start2 = $dSch->day24S; $end2 = $dSch->day24E;
                                            }else if($todayWD == 5){
                                                $start1 = $dSch->day15S; $end1 = $dSch->day15E; $start2 = $dSch->day25S; $end2 = $dSch->day25E;
                                            }else if($todayWD == 6){
                                                $start1 = $dSch->day16S; $end1 = $dSch->day16E; $start2 = $dSch->day26S; $end2 = $dSch->day26E;
                                            }else if($todayWD == 0){
                                                $start1 = $dSch->day10S; $end1 = $dSch->day10E; $start2 = $dSch->day20S; $end2 = $dSch->day20E;
                                            }
                                        }
                                    ?>
                                    @if(isset($start1) && $start1 != '00:00')
                                        <p class="color-qrorpa" style="font-weight:bold; font-size:1.2rem;">Lieferzeiten : {{$start1}} -> {{$end1}}
                                            @if(isset($start2) && $start2 != '00:00')
                                                und {{$start2}} -> {{$end2}}
                                            @endif
                                        </p>
                                    @elseif(isset($start2) && $start2 != '00:00')
                                        <p class="color-qrorpa" style="font-weight:bold;">{{$start2}} -> {{$end2}}</p> 
                                    @else
                                        <p class="color-qrorpa" style="font-weight:bold;">Keine Lieferzeiten heute!</p>
                                    @endif
                                </div>
                                <div style="width:69%;" class="form-group ">
                                    @if(isset($confirmDataError))
                                        <input onkeyup="setaddressDe(this.value)" type="text" class="form-control shadow-none" id="address" value="{{$confirmDataError['addressTA']}}">
                                    @else
                                        <input onkeyup="setaddressDe(this.value)" type="text" class="form-control shadow-none" id="address" placeholder="Adresse" required>
                                    @endif
                                </div>
                                <div style="width:30%;" class="form-group">
                                    <input onchange="settimeDe(this.value)" type="time" class="form-control shadow-none" id="time" placeholder="Zeit" required>
                                </div>


                                <div style="width:49.5%;" class="form-group">
                                    <input onkeyup="setplzDe(this.value,'{{Cart::total()}}')" type="text" class="form-control shadow-none" id="plz" placeholder="PLZ" required>
                                </div>
                                <div style="width:49.5%;" class="form-group">
                                    @if(isset($confirmDataError))
                                        <input type="text" class="form-control shadow-none" id="ort" value="{{$confirmDataError['ortTA']}}">
                                    @else
                                        <input type="text" class="form-control shadow-none" id="ort" placeholder="ORT">
                                    @endif
                                </div>
                                <div id="plzDivAlertDanger" class="alert alert-danger mt-1 text-center" style="width:100%; display:none;">
                                    <strong>PLZ derzeit nicht aktiv!</strong>
                                </div>
                                <div id="plzDivAlertSuccess" class="alert alert-success mt-1 flex-wrap justify-content-between" style="width:100%; display:none;">
                                    <p style="width:49.5%; margin:0;"><strong>
                                        <i style="color:rgb(39,190,175);" class="fa-solid fa-motorcycle"></i>
                                        <span class="ml-2" id="plzDivAlertSuccessSpanCost"></span>
                                    </strong></p>
                                    <p style="width:49.5%; margin:0;"><strong>
                                        <i style="color:rgb(39,190,175);" class="fa-regular fa-clock"></i>
                                        <span class="ml-2" id="plzDivAlertSuccessSpanTime"></span>
                                    </strong></p>
                                </div>
                                

                                <div style="width:100%;" class="form-group">
                                    @if(isset($confirmDataError))
                                        <input onkeyup="setEmailDe(this.value)" type="text" class="form-control shadow-none" id="email" value="{{$confirmDataError['emailTA']}}">
                                    @else
                                        <input onkeyup="setEmailDe(this.value)" type="text" class="form-control shadow-none" id="email" placeholder="Email (optional)">
                                    @endif
                                </div>


                                <div style="width:100%;" class="form-group">
                                    @if(isset($confirmDataError))
                                        <textarea style="width:100%;" onkeyup="setkomentDe(this.value)" type="textarea" row="1" class="form-control shadow-none" id="koment" value="{{$confirmDataError['komentTA']}}"></textarea>
                                    @else
                                        <textarea style="width:100%;" onkeyup="setkomentDe(this.value)" type="textarea" row="1" class="form-control shadow-none" id="koment" placeholder="Kommentar"></textarea>
                                    @endif
                                </div>
                             
                           
                            </li>
                        @endif
                    </ul>
                    
                </div>






                <script>
                     function setNameDe(nameValue){
                        $('#nameTA01').val(nameValue);
                        $('#nameTA02').val(nameValue);
                    }
                    function setLastameDe(lastnameValue){
                        $('#lastnameTA01').val(lastnameValue);  
                        $('#lastnameTA02').val(lastnameValue);  
                    }

                    function setaddressDe(addrValue){
                        $('#addressTA01').val(addrValue);
                        $('#addressTA02').val(addrValue);
                    }
                    function setplzDe(plzVal,cartTotal){
                        $('#plzTA01').val(plzVal);
                        $('#plzTA02').val(plzVal);

                        if(plzVal == ''){
                            $('#plzDivAlertSuccess').attr('style','width:100%; display:none;');
                            $('#plzDivAlertDanger').hide(50);
                            $('#deliveryFeeView').html('0.00');
                            realoadTotalToPay();
                        }else{
                            $.ajax({
                                url: '{{ route("delivery.deliveryCheckPLZOnCartFromUser") }}',
                                method: 'post',
                                data: {
                                    plz: plzVal,
                                    theResId: $('#theRestaurant').val(),
                                    cTot: cartTotal,
                                    _token: '{{csrf_token()}}'
                                },
                                success: (respo) => {
                                    respo = $.trim(respo);
                                    respo2D = respo.split('|||');
                                    // $("#freeProElements").load(location.href+" #freeProElements>*","");
                                    if(respo == 'plzNotFound'){
                                        $('#plzDivAlertSuccess').attr('style','width:100%; display:none;');
                                        $('#plzDivAlertDanger').html('<strong>PLZ derzeit nicht aktiv!</strong>');
                                        $('#plzDivAlertDanger').show(50);
                                        $('#deliveryFeeView').html('0.00');
                                        realoadTotalToPay();
                                    }else if(respo2D[0] == 'notEnoughtCTot'){
                                        $('#plzDivAlertSuccess').attr('style','width:100%; display:none;');
                                        $('#plzDivAlertDanger').html('<strong>Mindestbestellwert bei diesem PLZ ist <br>'+respo2D[1]+' CHF!</strong>');
                                        $('#plzDivAlertDanger').show(50);
                                        $('#deliveryFeeView').html('0.00');
                                        realoadTotalToPay();
                                    }else{
                                        $('#plzDivAlertSuccess').attr('style','width:100%; display:flex;');
                                        $('#plzDivAlertDanger').hide(50);
                                        $('#plzDivAlertSuccessSpanCost').html(parseFloat(respo2D[0]).toFixed(2)+' CHF');
                                        $('#plzDivAlertSuccessSpanTime').html(respo2D[1]+'-'+respo2D[2]+' min');
                                        $('#deliveryFeeView').html(parseFloat(respo2D[0]).toFixed(2));
                                        $('#ort').val(respo2D[3]);

                                        realoadTotalToPay();
                                    }
                                },
                                error: (error) => { console.log(error); }
                            });
                        }
                    }

                    function setortDe(ortVal){
                        $('#ortTA01').val(ortVal);
                        $('#ordTA02').val(ortVal);
                    }

                    function setEmailDe(emailValue){
                        $('#emailTA01').val(emailValue);
                        $('#emailTA02').val(emailValue);
                    }
                    function settimeDe(emailValue){
                        $('#timeTA01').val(emailValue);
                        $('#timeTA02').val(emailValue);
                    }
                    function setkomentDe(komVal){
                        $('#komentTA01').val(komVal);
                        $('#komentTA02').val(komVal);
                    }

                    function realoadTotalToPay(){
                        var newTotal = parseFloat(parseFloat($('#subTotalCart').html()) + parseFloat($('#tvshSpanCart').html()) + parseFloat($('#bakshishView').html()) + parseFloat($('#deliveryFeeView').html()));
                        $('.totalOnCart').html(parseFloat(newTotal).toFixed(2));
                    }
                </script>
































                <script>


                        function setNameTA(nameValue){
                            $('#nameTA01').val(nameValue);
                            $('#nameTA02').val(nameValue);
                        }
                        function setLastameTA(lastnameValue){
                            $('#lastnameTA01').val(lastnameValue);  
                            $('#lastnameTA02').val(lastnameValue);  
                        }
                        function setTimeTA(timeValue){
                            $('#timeTA01').val(timeValue);
                            $('#timeTA02').val(timeValue);
                        }


















                    function setFreeShots(checkId, proId){
                        if($('#'+checkId).prop("checked") == true){
                            console.log("Checkbox is checked.");
                            $('.freeShots').prop('checked', false);
                            $('#'+checkId).prop('checked', true);

                            $('#freeShotPh1Id').val(proId);
                            $('#freeShotPh2Id').val(proId);

                        }else if($('#'+checkId).prop("checked") == false){
                            $('.freeShots').prop('checked', false);

                            $('#freeShotPh1Id').val(0);
                            $('#freeShotPh2Id').val(0);
                        }
                    }
                </script>













                
<script>
    function usePoints(){
        $('.ponitsEarn').hide(500);
        $('.pointsUse').show(500);
    }
    function earnPointsBack(){
        $('.ponitsEarn').show(500);
        $('.pointsUse').hide(500);
    }

    function setUsePoints(){
        var PointsVal = parseInt($('#pointUserAmount').val());

        if(parseInt(PointsVal) <= 0 || $('#pointUserAmount').val() == ''){
                // Nder 0 ose 0
            $('#invalidePointsUse11').show(100).delay(2500).hide(100);
        }else if( parseInt(PointsVal) > parseInt($('#userPointsB').val())){
                // Nuk ka pike te mjaftueshme
              
            $('#invalidePointsUse22').show(100).delay(2500).hide(100);
        }else{

                //Cart Total  
            var newVal = parseFloat($('.totalOnCart').html()) - (parseFloat(PointsVal)*0.01);
            $('.totalOnCart').html(parseFloat(newVal).toFixed(2));

                // Send variable to cash payment Step 1
            var pointsU = parseInt($('#pointsUsedIdFS').val()) + parseInt((PointsVal));
            $('#pointsUsedIdFS').val(parseInt(pointsU));

                // Alarm the user for its used points
            var actPoints = parseInt( $('#userPointsB').val()) - parseInt(PointsVal);
            $('#userPointsB').val(parseInt(actPoints));
            $('#userPointsBShow').html(actPoints+' p  ( '+parseInt(pointsU)+' p)');

                // Change the subtotal amount subTotalCart
            var subtot = parseFloat(newVal) - (parseFloat(newVal)*0.081);
            $('#subTotalCart').html(parseFloat(subtot).toFixed(2));

                // Change the tvsh amount tvshSpanCart
            var tvshtot =parseFloat(newVal)*0.081;
            $('#tvshSpanCart').html(parseFloat(tvshtot).toFixed(2));
             
        }
        
    }

    
</script>







@if(isset($confirmData['pUsed']))
  <?php $valOnPointsN =$confirmData['pUsed']; ?>
@else
<?php $valOnPointsN = 0; ?>
@endif
<input type="hidden" value="{{$valOnPointsN}}" id="pUsedFromConfirm">



<style>
.selectedTip{
    background-color:rgb(39,190,175);
    color:white;
}
.selectedTip:hover{
    opacity:0.7;
    color:white;
}
</style>


                <script>
                    function setCostume(btnId, inputId, btnValue, cTot){
                            
                        $('.waiterTipBtns').attr('class', 'btn btn-outline-dark waiterTipBtns mt-2');
                        $('#'+btnId).attr('class', 'btn selectedTip waiterTipBtns mt-2');

                        btnValue = parseFloat($('#'+inputId).val()).toFixed(2);

                        if(btnValue > 0){

                                var Shuma = parseFloat(parseFloat(cTot) + parseFloat(btnValue)).toFixed(2);
                                var TipValue = parseFloat(btnValue);
                                var TipExt = 'other';

                                var dealAm =parseInt($('#pointsUsedIdFS').val());
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }


                            // alert(tipShuma[tipShuma.length-1]);
                            $('#bakshishView').text(parseFloat(TipValue).toFixed(2)); 

                            $('#tipValueSendId').val(TipExt);
                            $('#tipValueCHFSendId').val(parseFloat(TipValue));

                            $('.tipValueConfirmValueCLA').val(parseFloat(TipValue));
                            $('.tipValueConfirmTitleCLA').val(parseFloat(TipExt));

                            $('.totalOnCart').text(Shuma);

                        }else{
                            $('#bakshishView').text(parseFloat(0).toFixed(2)); 

                            $('#tipValueSendId').val(0);
                            $('#tipValueCHFSendId').val(parseFloat(0));

                            $('.tipValueConfirmValueCLA').val(parseFloat(0));
                            $('.tipValueConfirmTitleCLA').val(parseFloat(0));

                            $('.totalOnCart').text(cTot);

                        }
                    }



                    function setTip(btnId, cTot){
                        // bakshishView
                        
                       

                        $('.waiterTipBtns').attr('class', 'btn btn-outline-dark waiterTipBtns mt-2');
                        $('#'+btnId).attr('class', 'btn selectedTip waiterTipBtns mt-2');

                        $('#tipValueSendId').val();
                        $('#tipValueCHFSendId').val();


                        

                        switch(btnId){
                            case 'tipWaiter50':
                                var Shuma = parseFloat(parseFloat(cTot) + parseFloat(0.5)).toFixed(2);
                                var TipValue = parseFloat(0.5);
                                var TipExt = parseFloat(0.5);

                                var dealAm =parseInt($('#pointsUsedIdFS').val());
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                            break;

                            case 'tipWaiter1':
                                var Shuma = parseFloat(parseFloat(cTot) + parseFloat(1)).toFixed(2);
                                var TipValue = parseFloat(1);
                                var TipExt = parseFloat(1);

                                var dealAm =parseInt($('#pointsUsedIdFS').val());
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                            break;

                            case 'tipWaiter2':
                                var Shuma = parseFloat(parseFloat(cTot) + parseFloat(2)).toFixed(2);
                                var TipValue = parseFloat(2);
                                var TipExt = parseFloat(2);

                                var dealAm =parseInt($('#pointsUsedIdFS').val());
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                            break;
                            case 'tipWaiter5':
                                var Shuma = parseFloat(parseFloat(cTot) + parseFloat(5)).toFixed(2);
                                var TipValue = parseFloat(5);
                                var TipExt = parseFloat(5);

                                var dealAm =parseInt($('#pointsUsedIdFS').val());
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                            break;
                            case 'tipWaiter10':
                                var Shuma = parseFloat(parseFloat(cTot) + parseFloat(10)).toFixed(2);
                                var TipValue = parseFloat(10);
                                var TipExt = parseFloat(10);

                                var dealAm =parseInt($('#pointsUsedIdFS').val());
                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                            break;
                            case 'tipWaiterCancel':
                                var Shuma = parseFloat(parseFloat(cTot) + parseFloat(0)).toFixed(2);
                                var TipValue = parseFloat(0);
                                var TipExt = parseFloat(0);

                                var dealAm =parseInt($('#pointsUsedIdFS').val());

                                if(dealAm == 0 && parseInt($('#pUsedFromConfirm').val()) != 0){
                                    dealAm=parseInt($('#pUsedFromConfirm').val());
                                }
                                if(dealAm != 0){
                                    Shuma = parseFloat(Shuma - parseFloat(dealAm)*0.01).toFixed(2);
                                }
                                $('#tipWaiterCosVal').val('');
                                
                            break;

                         
                            
                        }
                            // alert(tipShuma[tipShuma.length-1]);
                            $('#bakshishView').text(parseFloat(TipValue).toFixed(2)); 

                            $('#tipValueSendId').val(TipExt);
                            $('#tipValueCHFSendId').val(parseFloat(TipValue));

                            $('.tipValueConfirmValueCLA').val(parseFloat(TipValue));
                            $('.tipValueConfirmTitleCLA').val(parseFloat(TipExt));

                            
                            if($('#codeUsedValueID').val() != 0){
                                var dealOffIs = parseFloat($('#codeUsedValueID').val());
                                $('.totalOnCart').text(parseFloat(Shuma - dealOffIs).toFixed(2));
                            }else{
                                if($('#codeUsedValueID2').val() != 0){
                                    var dealOffIs = parseFloat($('#codeUsedValueID2').val());
                                    $('.totalOnCart').text(parseFloat(Shuma - dealOffIs).toFixed(2));
                                }else{
                                    $('.totalOnCart').text(Shuma);
                                }
                            }
                        

                    }

                </script>











@if($Restrict != 0 && !isset($confirmData))
                    @if( $Restrict == 18)
                        <div class="form-check-inline mb-4">
                            <label class="form-check-label">
                                <input name="marketingSMS" onclick="ageConfirm(this.id)" id="age18PlusConf" type="checkbox" class="form-check-input" value="">
                                <strong>  Bestätigen Sie, dass Sie über 18 Jahre alt sind. Es wird eine Ausweiskontrolle durchgeführt.</strong>
                            </label>
                        </div>
                    @elseif( $Restrict == 16)
                        <div class="form-check-inline mb-4">
                            <label class="form-check-label">
                                <input name="marketingSMS" onclick="ageConfirm(this.id)" id="age16PlusConf" type="checkbox" class="form-check-input" value="">
                                <strong>Bestätigen Sie, dass Sie über 16 Jahre alt sind. Es wird eine Ausweiskontrolle durchgeführt.</strong>
                            </label>
                        </div>
                    @endif
                   
                @endif

                <script>
                    function ageConfirm(checkId){
                        if($('#'+checkId).prop("checked") == true){
                            $('.ContinueCashPay').prop("disabled", false);
                        }
                        else if($('#'+checkId).prop("checked") == false){
                            $("#cartPart2").load(location.href+" #cartPart2>*","");
                            
                        }
                    }
                </script>


                <?php
                    $TheRestaurantId = $_SESSION["Res"];
                ?>



                <div class="container" style="margin-top:-20px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check mt-2" id="dataAccCartDiv">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" id="dataAccCart" value="">Ich habe die <a href="{{route('firstPage.datenschutz')}}">Datenschutzbestimmungen</a>
                                    zur Kenntnis genommen*
                                </label>
                            </div>
                            <div style="display:none; font-weight:bold;" class="alert alert-danger text-center p-1" id="dataAccCartError">
                                Akzeptieren Sie zuerst die Datenschutzbestimmungen!
                            </div>
                            <div style="display:none; font-weight:bold;" class="alert alert-danger text-center p-1" id="plzDeliverCartError">
                                Dieses PLZ wird derzeit nicht akzeptiert!
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 mb-4" id="paymentMethodsDiv">
                        <div class="col-6 text-center" id="CashPaymentDiv">
                            @if(isset($confirmData))
                                <button class="btn btn-default text-center"><img src="storage/images/CashPayC.PNG" alt=""></button>
                            @else
                                @if($Restrict != 0)
                                    @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                    <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayDEUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{Auth::User()->id}}','{{Auth::User()->name}}','{{Auth::User()->email}}','{{Cart::total()}}','{{$porosiaSend}}')">
                                    @else
                                    <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="showCash()" disabled>
                                    @endif
                                        <img src="storage/images/CashPayW.PNG" alt="">
                                    </button>
                                @else  
                                    @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                    <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayDEUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{Auth::User()->id}}','{{Auth::User()->name}}','{{Auth::User()->email}}','{{Cart::total()}}','{{$porosiaSend}}')">
                                    @else
                                    <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="showCash()">
                                    @endif
                                        <img src="storage/images/CashPayW.PNG" alt="">
                                    </button>
                                @endif
                            @endif
                            <p><strong>Barzahlung</strong></p>

                        </div>
                        @if($_SESSION["Res"] == 25 || $_SESSION["Res"] == 24 || $_SESSION["Res"] == 26)
                        <div class="col-6 text-center" id="onlinePayDiv">
                            <script src="https://pay.sandbox.datatrans.com/upp/payment/js/datatrans-2.0.0.js"></script>
                            <!-- <form id="paymentForm" data-merchant-id="1100004624" data-amount="1000" data-currency="CHF"
                                data-refno="123456789" data-sign="30916165706580013"> -->
                                <!-- data-toggle="modal" data-target="#onlinePayP" -->
                                <button data-toggle="modal" data-target="#onlinePayP" class="btn btn-default noBorderBtn" id="paymentButton" >
                                    <img src="storage/images/CardPayW.PNG" alt="">
                                </button>
                                <p><strong>Online</strong></p>
                            <!-- </form> -->
                        </div>
                        @else
                        @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                        <div class="col-6 text-center" onclick="onlinePayDEUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{Auth::User()->id}}','{{Auth::User()->name}}','{{Auth::User()->email}}','{{Cart::total()}}','{{$porosiaSend}}')" id="onlinePayDiv">
                        @else
                        <div class="col-6 text-center" onclick="openPhoneNrForDOnlinePay()" id="onlinePayDiv">
                        @endif
                            <script src="https://pay.sandbox.datatrans.com/upp/payment/js/datatrans-2.0.0.js"></script>
                            <!-- <form id="paymentForm" data-merchant-id="1100004624" data-amount="1000" data-currency="CHF"
                                data-refno="123456789" data-sign="30916165706580013"> -->
                                <!-- data-toggle="modal" data-target="#onlinePayP" -->
                           
                                
                             
                                <button class="btn btn-default noBorderBtn" id="paymentButton">
                                    <img src="storage/images/CardPayW.PNG" alt="">
                                </button>
                                <p><strong>Online</strong></p>
                            <!-- </form> -->
                        </div>
                        @endif

                        
                        @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                        <div class="col-12">
                            <div class="jumbotron text-center" style="padding: 5px; margin-bottom:7px;">
                                <p class="lead" style="font-weight: bold;">Ihre registrierte Telefonnummer wird zur Zahlungsbestätigung verwendet!</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <!-- End -->
                </div>

                <!-- The Modal onlinePayP -->
                <div class="modal" id="onlinePayP">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Online Bezahlung bald verfügbar!</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="d-flex flex-wrap">
                                    <img style=" width:33%; height:90px; object-fit: contain;" src="storage/images/postfinanceLogo.png" alt="" >
                                    <img style=" width:33%; height:90px; object-fit: contain;" src="storage/images/twintLogo.png" alt="" >
                                    <img style=" width:33%; height:90px; object-fit: contain;" src="storage/images/paypalLogo.png" alt="" >
                                    <img style=" width:50%; height:90px; object-fit: contain;" src="storage/images/mastercardLogo.png" alt="" >
                                    <img style=" width:50%; height:90px; object-fit: contain;" src="storage/images/visaLogo.png" alt="" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    if(Auth::check()){
                        echo '<input type="hidden" value="'.Auth::user()->email.'" id="email">';
                        $sendId = Auth::user()->id;
                        $userNameS = Auth::user()->name;
                        $userEmailS = Auth::user()->email;
                    }else{
                        echo '
                        <div class="form-group">
                            <input type="hidden" class="form-control shadow-none noBorderBtn" placeholder="Enter email" value="user@empty.com" id="email">
                        </div>
                        ';
                        $sendId = 0;
                        $userNameS = "empty";
                        $userEmailS = "empty";
                    }

              
                ?>

                <script>
                    function showCash() {
                        if(!$('#nameTA01').val() || !$('#lastnameTA01').val() || !$('#addressTA01').val() || !$('#timeTA01').val()){
                            if($('#phoneNrForDOnlinePayErr02CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr02CP').show(50).delay(4500).hide(50); }
                        }else if(!$('#plzTA01').val() || $('#plzDivAlertDanger').is(":visible")){
                            if($('#plzDeliverCartError').is(":hidden")){ $('#plzDeliverCartError').show(250).delay(3000).hide(250); }
                        }else{
                            if ($('#dataAccCart').is(':checked')) {
                                var Cash = document.getElementById('switch1');
                                var CashPay = document.getElementById('CashPay');
                                CashPay.style.display = "block";

                                $('#CashPaymentDiv').hide(50);
                                $('#onlinePayDiv').hide(50); 
                                $('#paymentMethodsDiv').hide(50); 
                                $('#dataAccCart').prop('disabled', true);

                                // Register a cash pay click
                                $.ajax({
                                    url: '{{ route("restorantet.cashPayClick") }}',
                                    method: 'post',
                                    data: {id: $('#theRestaurant').val(), _token: '{{csrf_token()}}'},
                                    success: (res) => {},
                                    error: (error) => {console.error(error);}
                                });
                            }else{
                                if($('#dataAccCartError').is(":hidden")){ $('#dataAccCartError').show(250).delay(3000).hide(250); }
                            }
                        }
                    }

                    function cashPayDEUseUsrPNr(UsrPNr, resId,uId,uName,uEmail,shuma,theOrd){
                        if(!$('#nameTA01').val() || !$('#lastnameTA01').val() || !$('#addressTA01').val() || !$('#timeTA01').val()){
                            if($('#phoneNrForDOnlinePayErr02CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr02CP').show(50).delay(4500).hide(50); }
                        }else if(!$('#plzTA01').val() || $('#plzDivAlertDanger').is(":visible")){
                            if($('#plzDeliverCartError').is(":hidden")){ $('#plzDeliverCartError').show(250).delay(3000).hide(250); }
                        }else{
                            $.ajax({
                                url: '{{ route("delivery.cashPayTAUseUserPNumber") }}',
                                method: 'post',
                                data: {
                                    phNr: UsrPNr,
                                    res: resId,
                                    tipVal: $('#tipValueSendId').val(),
                                    tipTitle: $('#tipValueCHFSendId').val(),
                                    pointsUsed: 0,
                                    theName: $('#nameTA01').val(),
                                    theLastname: $('#lastnameTA01').val(),
                                    adresa: $('#addressTA01').val(),
                                    email: $('#emailTA01').val(),
                                    theTime: $('#timeTA01').val(),
                                    plz: $('#plzTA01').val(),
                                    ort: "$('#ort').val()",
                                    theCom: $('#komentTA01').val(),
                                    codeVal: $('#codeUsedValueID').val(),
                                    freeShot: $('#freeShotPh1Id').val(),  
                                    userId: uId,
                                    username: uName,
                                    userEmail: uEmail,
                                    shuma: shuma,
                                    theOrd: theOrd,
                                    _token: '{{csrf_token()}}'
                                },
                                success: (response) => {
                                    location.reload();
                                },
                                error: (error) => { console.log(error); }
                            });
                        }
                    }

                    function onlinePayDEUseUsrPNr(UsrPNr, resId,uId,uName,uEmail,shuma,theOrd){
                        if(!$('#nameTA01').val() || !$('#lastnameTA01').val() || !$('#addressTA01').val() || !$('#timeTA01').val()){
                            if($('#phoneNrForDOnlinePayErr02CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr02CP').show(50).delay(4500).hide(50); }
                        }else if(!$('#plzTA01').val() || $('#plzDivAlertDanger').is(":visible")){
                            if($('#plzDeliverCartError').is(":hidden")){ $('#plzDeliverCartError').show(250).delay(3000).hide(250); }
                        }else{
                            $.ajax({
                                url: '{{ route("onlinePayDOthers.onlineDeliveryUseUsrPNumber") }}',
                                method: 'post',
                                data: {
                                    phNr: phoneNrFCl2,
                                    res: resId,
                                    tipVal: $('#tipValueSendId').val(),
                                    tipTitle: $('#tipValueCHFSendId').val(),
                                    pointsUsed: 0,
                                    theName: $('#nameTA01').val(),
                                    theLastname: $('#lastnameTA01').val(),
                                    adresa: $('#addressTA01').val(),
                                    email: $('#emailTA01').val(),
                                    theTime: $('#timeTA01').val(),
                                    plz: $('#plzTA01').val(),
                                    ort: $('#ort').val(),
                                    theCom: $('#komentTA01').val(),
                                    codeVal: $('#codeUsedValueID').val(),
                                    freeShot: $('#freeShotPh1Id').val(),  
                                    userId: uId,
                                    username: uName,
                                    userEmail: uEmail,
                                    shuma: shuma,
                                    theOrd: theOrd,
                                    _token: '{{csrf_token()}}'
                                },
                                success: (response) => {
                                    var dataJ = JSON.stringify(response);
                                    var dataJ2 = JSON.parse(dataJ);
                                    window.location.href = dataJ2['body']['RedirectUrl'];
                                },
                                error: (error) => { console.log(error); }
                            });
                        }
                    }

                    function openPhoneNrForDOnlinePay(){
                        if(!$('#nameTA01').val() || !$('#lastnameTA01').val() || !$('#addressTA01').val() || !$('#timeTA01').val()){
                            if($('#phoneNrForDOnlinePayErr02CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr02CP').show(50).delay(4500).hide(50); }
                        }else if(!$('#plzTA01').val() || $('#plzDivAlertDanger').is(":visible")){
                            if($('#plzDeliverCartError').is(":hidden")){ $('#plzDeliverCartError').show(250).delay(3000).hide(250); }
                        }else{
                            $('#phoneNrForDOnlinePay').show(50);
                            $('#CashPaymentDiv').remove();
                            $('#onlinePayDiv').remove();
                            $('#paymentMethodsDiv').hide(50); 
                        }
                    }

                    function phoneNrForDOnlinePaySendNr(resId){
                        let phoneNrFCl = $('#onlinePPhoNrInputId').val().replace(/ /g,'');
                        if(phoneNrFCl != ''){
                            if(phoneNrFCl[0] == '+' && phoneNrFCl[1] == 4 && (phoneNrFCl[2] == 1 || phoneNrFCl[2] == 9) && phoneNrFCl[3] == 7 && phoneNrFCl.length == 12){
                                phoneNrFCl = '0'+phoneNrFCl.toString().slice(3);
                            }else if(phoneNrFCl[0] == 4 && (phoneNrFCl[1] == 1 || phoneNrFCl[1] == 9) && phoneNrFCl[2] == 7 && phoneNrFCl.length == 11){
                                phoneNrFCl = '0'+phoneNrFCl.toString().slice(2);
                            }else if(phoneNrFCl[0] == 0 && phoneNrFCl[1] == 0 && phoneNrFCl[2] == 4 && (phoneNrFCl[3] == 1 || phoneNrFCl[3] == 9) && phoneNrFCl[4] == 7 && phoneNrFCl.length == 13){
                                phoneNrFCl = '0'+phoneNrFCl.toString().slice(4);
                            }
                        }
                        if(!$('#onlinePPhoNrInputId').val() || phoneNrFCl.length < 9 || phoneNrFCl.length > 10){
                            if($('#phoneNrForDOnlinePayErr01').is(":hidden")){
                                $('#phoneNrForDOnlinePayErr01').show(50).delay(4500).hide(50);
                            }
                        }else if(!$('#nameTA01').val() || !$('#lastnameTA01').val() || !$('#addressTA01').val() ||  !$('#timeTA01').val() || !$('#plzTA01').val()){
                            if($('#phoneNrForDOnlinePayErr02').is(":hidden")){
                                $('#phoneNrForDOnlinePayErr02').show(50).delay(4500).hide(50);
                            }
                        }else{
                            $.ajax({
                                url: '{{ route("onlinePayDOthers.onlineDeliveryReceivePhNr") }}',
                                method: 'post',
                                data: {
                                    phNr: phoneNrFCl,
                                    res: resId,
                                    clTime: $('#timeTA01').val(),
                                    plz: $('#plzTA01').val(),
                                    _token: '{{csrf_token()}}'
                                },
                                success: (response) => {
                                    response = response.replace(/\s/g, '');
                                    if(response == 'falseNR'){
                                        if($('#phoneNrForDOnlinePayErr03').is(":hidden")){ $('#phoneNrForDOnlinePayErr03').show(50).delay(4500).hide(50); }
                                        $('#onlinePPhoNrInputId').val('');
                                    }else if(response == 'falseDataTime'){
                                        if($('#phoneNrForDOnlinePayErr06').is(":hidden")){ $('#phoneNrForDOnlinePayErr06').show(50).delay(4500).hide(50); }
                                    }else if(response == 'falseDataAddr'){
                                        if($('#phoneNrForDOnlinePayErr07').is(":hidden")){ $('#phoneNrForDOnlinePayErr07').show(50).delay(4500).hide(50); }
                                    }else{
                                        res2D = response.split('||');
                                        $('#phoneNrForDOnlinePayNumberDiv').hide(25);
                                        $('#phoneNrForDOnlinePayPNrBtn').hide(25);
                                        $('#phoneNrForDOnlinePayCodeDiv').show(25);
                                        $('#phoneNrForDOnlinePayCodeBtn').show(25);
                                        $('#DEOnlinePayClCodeCreated').val(res2D[1]);
                                        if(phoneNrFCl == '763270293' || phoneNrFCl == '0763270293' || phoneNrFCl == '763251809' || phoneNrFCl == '0763251809' || phoneNrFCl == '763459941' || phoneNrFCl == '0763459941' || phoneNrFCl == '763469963' || phoneNrFCl == '0763469963' || phoneNrFCl == '760000000' || phoneNrFCl == '0760000000'){
                                            $('#phoneNrForDOnlinePayCodeDemoP').html('Demo Code :'+res2D[1]);
                                        }else{
                                            $('#phoneNrForDOnlinePayCodeDemoP').hide(5); 
                                        }
                                        $('#onlinePPhoNrInputId').val(res2D[0]);
                                        startNrVerifyTimer();
                                    }
                                },
                                error: (error) => { console.log(error); }
                            });
                        }
                    }


                    function sendCodeForDeliveryOnlinePay(resId,uId,uName,uEmail,shuma,theOrd){
                        let codeByCl = $('#onlineCodeInputId').val();
                        let phoneNrFCl2 = $('#onlinePPhoNrInputId').val().replace(/ /g,'');

                        if(phoneNrFCl2 != ''){
                            if(phoneNrFCl2[0] == '+' && phoneNrFCl2[1] == 4 && (phoneNrFCl2[2] == 1 || phoneNrFCl2[2] == 9) && phoneNrFCl2[3] == 7 && phoneNrFCl2.length == 12){
                                phoneNrFCl2 = '0'+phoneNrFCl2.toString().slice(3);
                            }else if(phoneNrFCl2[0] == 4 && (phoneNrFCl2[1] == 1 || phoneNrFCl2[1] == 9) && phoneNrFCl2[2] == 7 && phoneNrFCl2.length == 11){
                                phoneNrFCl2 = '0'+phoneNrFCl2.toString().slice(2);
                            }else if(phoneNrFCl2[0] == 0 && phoneNrFCl2[1] == 0 && phoneNrFCl2[2] == 4 && (phoneNrFCl2[3] == 1 || phoneNrFCl2[3] == 9) && phoneNrFCl2[4] == 7 && phoneNrFCl2.length == 13){
                                phoneNrFCl2 = '0'+phoneNrFCl2.toString().slice(4);
                            }
                        }

                        if(!$('#onlineCodeInputId').val() || codeByCl.length != 6){
                            if($('#phoneNrForDOnlinePayErr04').is(":hidden")){ $('#phoneNrForDOnlinePayErr04').show(50).delay(4500).hide(50); }
                        }else{
                            $.ajax({
                                url: '{{ route("onlinePayDOthers.onlineDeliveryReceiveCode") }}',
                                method: 'post',
                                data: {
                                    phNr: phoneNrFCl2,
                                    codeByCl: codeByCl,
                                    codeCreated: $('#DEOnlinePayClCodeCreated').val(),
                                    res: resId,
                                    tipVal: $('#tipValueSendId').val(),
                                    tipTitle: $('#tipValueCHFSendId').val(),
                                    pointsUsed: 0,
                                    theName: $('#nameTA01').val(),
                                    theLastname: $('#lastnameTA01').val(),
                                    adresa: $('#addressTA01').val(),
                                    email: $('#emailTA01').val(),
                                    theTime: $('#timeTA01').val(),
                                    plz: $('#plzTA01').val(),
                                    ort: $('#ort').val(),
                                    theCom: $('#komentTA01').val(),
                                    codeVal: $('#codeUsedValueID').val(),
                                    freeShot: $('#freeShotPh1Id').val(),  
                                    userId: uId,
                                    username: uName,
                                    userEmail: uEmail,
                                    shuma: shuma,
                                    theOrd: theOrd,
                                    _token: '{{csrf_token()}}'
                                },
                                success: (response) => {
                                    var dataJ = JSON.stringify(response);
                                    var dataJ2 = JSON.parse(dataJ);
                                    window.location.href = dataJ2['body']['RedirectUrl'];
                                },
                                error: (error) => { console.log(error); }
                            });
                        }
                    }

                    var intervalTimerMenu ;
                    function startNrVerifyTimer(){
                        var timerStart = "3:00";
                        $('#phoneNrForDOnlinePayTime').html(timerStart);
                        intervalTimerMenu = setInterval(function() {
                            var timer = timerStart.split(':');
                            //by parsing integer, I avoid all extra string processing
                            var minutes = parseInt(timer[0], 10);
                            var seconds = parseInt(timer[1], 10);
                            --seconds;
                            minutes = (seconds < 0) ? --minutes : minutes;
                            if (minutes < 0) clearInterval(interval);
                            seconds = (seconds < 0) ? 59 : seconds;
                            seconds = (seconds < 10) ? '0' + seconds : seconds;
                            //minutes = (minutes < 10) ?  minutes : minutes;
                            $('#phoneNrForDOnlinePayTime').html(minutes + ':' + seconds);
                            timerStart = minutes + ':' + seconds;
                            if(minutes == 0 && seconds == 0)location.reload();
                        }, 1000);
                    }
                </script>


                <div style="width: 100%; display:none !important; margin-top:-20px;" class="container d-flex flex-wrap mb-3" id="phoneNrForDOnlinePay">
                    <div class="input-group mb-1" id="phoneNrForDOnlinePayNumberDiv">
                        <span class="input-group-text" id="onlinePPhoNrInput"><i class="fas fa-phone-alt"></i></span>
                        <input type="text" id="onlinePPhoNrInputId" class="form-control shadow-none" placeholder="Telefonnummer" aria-describedby="onlinePPhoNrInput">
                    </div>
                    <input type="hidden" id="DEOnlinePayClPhoneNr" value="0">
                    <button id="phoneNrForDOnlinePayPNrBtn" onclick="phoneNrForDOnlinePaySendNr('{{$TheRestaurantId}}')" class="btn btn-block btn-dark">Nummer senden</button>

                    <p style="width:100%; font-weight:bold;" class="text-center" id="phoneNrForDOnlinePayCodeDemoP"></p>
                    <div class="input-group mb-1" style="display: none;" id="phoneNrForDOnlinePayCodeDiv">
                        <span class="input-group-text" id="onlineCodeInput"><i class="fas fa-barcode"></i></span>
                        <input type="text" id="onlineCodeInputId" class="form-control shadow-none" placeholder="Code" aria-describedby="onlineCodeInput">
                        <span style="font-weight:bold;" class="pr-2 text-right input-group-text" id="phoneNrForDOnlinePayTime"></span>
                    </div>
                    <input type="hidden" id="DEOnlinePayClCodeCreated" value="0">
                    <button id="phoneNrForDOnlinePayCodeBtn" style="display:none;" 
                    onclick="sendCodeForDeliveryOnlinePay('{{$TheRestaurantId}}','{{$sendId}}','{{$userNameS}}','{{$userEmailS}}','{{Cart::total()}}','{{$porosiaSend}}')" 
                    class="btn btn-block btn-dark">Code senden</button>
                </div>

                    <div id="phoneNrForDOnlinePayErr01" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                        Bitte geben Sie zuerst eine gültige Telefonnummer ein!
                    </div>
                    <div id="phoneNrForDOnlinePayErr02" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                        Bitte schreiben Sie zuerst den Namen, Nachnamen und die Uhrzeit!
                    </div>
                    <div id="phoneNrForDOnlinePayErr03" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                        Die von Ihnen gesendete Telefonnummer ist nicht akzeptabel
                    </div>
                    <div id="phoneNrForDOnlinePayErr04" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                        Schreiben Sie bitte einen gültigen Code!
                    </div>
                    <div id="phoneNrForDOnlinePayErr05" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                        Der Code ist nicht richtig, bitte versuchen Sie es erneut!
                    </div>
                    <div id="phoneNrForDOnlinePayErr06" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                        Wir liefern zu diesem Zeitpunkt keine Bestellungen aus, es tut uns leid!
                    </div>
                    <div id="phoneNrForDOnlinePayErr07" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                        Wir liefern keine Bestellungen an Ihre Adresse, es tut uns leid!
                    </div>





























                        <?php
                            $randCode = rand(111111,999999);
                        ?>
                        <div id="CashPay" style="display:none;">
                            <div class="container">
                                {{Form::open(['action' => 'ProduktController@confNrDelivery', 'method' => 'post', 'id' => 'firstStepValidCodeSend']) }}
                                    <div id="cashPayPhoneNrInputDiv" class="input-group mb-3 input-group-sm" style="margin-top:-15px;">
                                        <label for="demo">Handynummer (Wir benötigen Ihre Handynummer, um die Bestellung zu verifizieren):</label>
                                        <div class="input-group-prepend">
                                            <!-- <span class="input-group-text">+41</span> -->
                                        </div>
                                        <input type="number" name="phoneNr" id="phoneNrInputDeliveryCP" placeholder="07x xxx xx xx" class="form-control shadow-none noBorderbtn" 
                                        style="font-size:16px;" required>
                                    </div>

                                    {{ Form::hidden('code', $randCode , ['class' => 'form-control']) }}


                                    {{ Form::hidden('tipValueSend', 0 , ['class' => 'form-control', 'id' => 'tipValueSendId']) }}
                                    {{ Form::hidden('tipValueCHFSend', 0 , ['class' => 'form-control', 'id' => 'tipValueCHFSendId']) }}

                                    {{ Form::hidden('pointsUsed', 0 , ['class' => 'form-control', 'id' => 'pointsUsedIdFS']) }}

                                 
                                        @if(isset($confirmDataError))
                                            {{ Form::hidden('nameTA01', $confirmDataError['nameTA'] , ['class' => 'form-control', 'id' => 'nameTA01', 'required']) }}
                                            {{ Form::hidden('lastnameTA01', $confirmDataError['lastnameTA']  , ['class' => 'form-control', 'id' => 'lastnameTA01', 'required']) }}

                                            
                                            {{ Form::hidden('addressTA01', $confirmDataError['addressTA'] , ['class' => 'form-control', 'id' => 'addressTA01', 'required']) }}
                                            {{ Form::hidden('emailTA01', $confirmDataError['emailTA'] , ['class' => 'form-control', 'id' => 'emailTA01', 'required']) }}
                                            {{ Form::hidden('timeTA01', '' , ['class' => 'form-control', 'id' => 'timeTA01', 'required']) }}
                                            {{ Form::hidden('plzTA01', '' , ['class' => 'form-control', 'id' => 'plzTA01', 'required']) }}
                                            {{ Form::hidden('ortTA01', $confirmDataError['ortTA'] , ['class' => 'form-control', 'id' => 'ortTA01', 'required']) }}
                                            {{ Form::hidden('komentTA01', $confirmDataError['komentTA'] , ['class' => 'form-control', 'id' => 'komentTA01']) }}

                                        @else
                                            @if(Auth::check())

                                                @if(isset(explode(' ',Auth::user()->name)[0]))
                                                    {{ Form::hidden('nameTA01', explode(" ",Auth::user()->name)[0] , ['class' => 'form-control', 'id' => 'nameTA01', 'required']) }}
                                                    
                                                    @if(isset(explode(' ',Auth::user()->name)[1]))
                                                    {{ Form::hidden('lastnameTA01', explode(" ",Auth::user()->name)[1] , ['class' => 'form-control', 'id' => 'lastnameTA01', 'required']) }}    
                                                    @else
                                                    {{ Form::hidden('lastnameTA01', '', ['class' => 'form-control', 'id' => 'lastnameTA01', 'required']) }}    
                                                    @endif
                                                @else
                                                    {{ Form::hidden('nameTA01', '' , ['class' => 'form-control', 'id' => 'nameTA01', 'required']) }}
                                                    {{ Form::hidden('lastnameTA01', '' , ['class' => 'form-control', 'id' => 'lastnameTA01', 'required']) }}
                                                @endif

                                            @else
                                                {{ Form::hidden('nameTA01', '' , ['class' => 'form-control', 'id' => 'nameTA01', 'required']) }}
                                                {{ Form::hidden('lastnameTA01', '' , ['class' => 'form-control', 'id' => 'lastnameTA01', 'required']) }}
                                            @endif
                                       

                                            {{ Form::hidden('addressTA01', '' , ['class' => 'form-control', 'id' => 'addressTA01', 'required']) }}
                                            {{ Form::hidden('emailTA01', '' , ['class' => 'form-control', 'id' => 'emailTA01']) }}
                                            {{ Form::hidden('timeTA01', '' , ['class' => 'form-control', 'id' => 'timeTA01', 'required']) }}
                                            {{ Form::hidden('plzTA01', '' , ['class' => 'form-control', 'id' => 'plzTA01', 'required']) }}
                                            {{ Form::hidden('ortTA01', '' , ['class' => 'form-control', 'id' => 'ortTA01', 'required']) }}
                                            {{ Form::hidden('komentTA01', '' , ['class' => 'form-control', 'id' => 'komentTA01']) }}
                                        @endif
                                 

                                    {{ Form::hidden('codeUsedValue', 0 , ['class' => 'form-control', 'id' => 'codeUsedValueID']) }}
                                    {{ Form::hidden('freeShotPh1', 0 , ['class' => 'form-control', 'id' => 'freeShotPh1Id']) }}
                                    {{ Form::hidden('res', $_SESSION["Res"] , ['class' => 'form-control']) }}

                                    <div class="row">
                                        <div class="col-12">
                                            <!-- <button class=""
                                            onclick="payCash('qrorpa','38345234379','123456')">
                                            Pay with Cash</button> -->
                                            {{ Form::button('Bezahle mit Bargeld', ['class' => 'btn btn-dark rounded-pill py-2 btn-block', 'id'=>'checkFirstPInputDelBtn', 'onclick' => 'checkFirstPInputDel("$TheRestaurantId")']) }}
                                        </div>
                                    </div>
                                {{Form::close() }}
                            </div>
                        </div>
                        <p style="width:100%; font-weight:bold;" class="text-center" id="phoneNrForDOnlinePayCodeDemoPCP"></p>
                        <div class="input-group mb-1" style="display: none;" id="phoneNrForDOnlinePayCodeDivCP">
                            <span class="input-group-text" id="onlineCodeInputCP"><i class="fas fa-barcode"></i></span>
                            <input type="text" id="onlineCodeInputIdCP" class="form-control shadow-none" placeholder="Code" aria-describedby="onlineCodeInputCP">
                            <span style="font-weight:bold;" class="pr-2 text-right input-group-text" id="phoneNrForDOnlinePayTimeCP"></span>
                        </div>
                        <input type="hidden" id="DEOnlinePayClCodeCreatedCP" value="0">
                        <button id="phoneNrForDOnlinePayCodeBtnCP" style="display:none;" 
                        onclick="sendCodeForDeliveryOnlinePayCP('{{$TheRestaurantId}}','{{$sendId}}','{{$userNameS}}','{{$userEmailS}}','{{Cart::total()}}','{{$porosiaSend}}')" 
                        class="btn btn-block btn-dark mb-4">Code senden</button>

                        <div id="phoneNrForDOnlinePayErr01CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Bitte geben Sie zuerst eine gültige Telefonnummer ein!
                        </div>
                        <div id="phoneNrForDOnlinePayErr02CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Bitte schreiben Sie zuerst den Namen, Nachnamen und die Uhrzeit!
                        </div>
                        <div id="phoneNrForDOnlinePayErr03CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Die von Ihnen gesendete Telefonnummer ist nicht akzeptabel
                        </div>
                        <div id="phoneNrForDOnlinePayErr04CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Schreiben Sie bitte einen gültigen Code!
                        </div>
                        <div id="phoneNrForDOnlinePayErr05CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Der Code ist nicht richtig, bitte versuchen Sie es erneut!
                        </div>
                        <div id="phoneNrForDOnlinePayErr06CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Wir liefern zu diesem Zeitpunkt keine Bestellungen aus, es tut uns leid!
                        </div>
                        <div id="phoneNrForDOnlinePayErr07CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Wir liefern keine Bestellungen an Ihre Adresse, es tut uns leid!
                        </div>
                        
                        <script> 
                            function checkFirstPInputDel(resId){
                                let pNr = $('#phoneNrInputDeliveryCP').val().replace(/ /g,'');

                                if(pNr != ''){
                                    if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                                        pNr = '0'+pNr.toString().slice(3);
                                    }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                                        pNr = '0'+pNr.toString().slice(2);
                                    }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                                        pNr = '0'+pNr.toString().slice(4);
                                    }
                                }

                                if(!$('#phoneNrInputDeliveryCP').val() || pNr.length < 9 || pNr.length > 10){
                                    if($('#phoneNrForDOnlinePayErr01CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr01CP').show(50).delay(4500).hide(50); }
                                }else if(!$('#nameTA01').val() || !$('#lastnameTA01').val() || !$('#addressTA01').val() ||  !$('#timeTA01').val() || !$('#plzTA01').val()){
                                    if($('#phoneNrForDOnlinePayErr02CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr02CP').show(50).delay(4500).hide(50); }
                                }else{
                                    $.ajax({
                                        url: '{{ route("delivery.sendPhoneNrTACashPay") }}',
                                        method: 'post',
                                        data: {
                                            phNr: pNr,
                                            res: resId,
                                            clTime: $('#timeTA01').val(),
                                            plz: $('#plzTA01').val(),
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (response) => {
                                            response = response.replace(/\s/g, '');
                                            if(response == 'falseNR'){
                                                if($('#phoneNrForDOnlinePayErr03CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr03CP').show(50).delay(4500).hide(50); }
                                                $('#phoneNrInputDeliveryCP').val('');
                                            }else if(response == 'falseDataTime'){
                                                if($('#phoneNrForDOnlinePayErr06CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr06CP').show(50).delay(4500).hide(50); }
                                            }else if(response == 'falseDataAddr'){
                                                if($('#phoneNrForDOnlinePayErr07CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr07CP').show(50).delay(4500).hide(50); }
                                            }else{
                                                res2D = response.split('||');
                                                $('#cashPayPhoneNrInputDiv').hide(25);
                                                $('#checkFirstPInputDelBtn').hide(25);
                                                $('#phoneNrForDOnlinePayCodeDivCP').show(25);
                                                $('#phoneNrForDOnlinePayCodeBtnCP').show(25);
                                                $('#DEOnlinePayClCodeCreatedCP').val(res2D[1]);
                                                if(pNr == '763270293' || pNr == '0763270293' || pNr == '763251809' || pNr == '0763251809' || pNr == '763459941' || pNr == '0763459941' || pNr == '763469963' || pNr == '0763469963' || pNr == '760000000' || pNr == '0760000000'){
                                                    $('#phoneNrForDOnlinePayCodeDemoPCP').html('Demo Code :'+res2D[1]);
                                                }else{
                                                    $('#phoneNrForDOnlinePayCodeDemoPCP').hide(5);
                                                }
                                                $('#phoneNrInputDeliveryCP').val(res2D[0]);
                                                startNrVerifyTimerCP();
                                            }
                                        },
                                        error: (error) => { console.log(error); }
                                    });
                                }
                            }


                            var intervalTimerMenuCP ;
                            function startNrVerifyTimerCP(){
                                var timerStart = "3:00";
                                $('#phoneNrForDOnlinePayTimeCP').html(timerStart);
                                intervalTimerMenuCP = setInterval(function() {
                                    var timer = timerStart.split(':');
                                    //by parsing integer, I avoid all extra string processing
                                    var minutes = parseInt(timer[0], 10);
                                    var seconds = parseInt(timer[1], 10);
                                    --seconds;
                                    minutes = (seconds < 0) ? --minutes : minutes;
                                    if (minutes < 0) clearInterval(interval);
                                    seconds = (seconds < 0) ? 59 : seconds;
                                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                                    //minutes = (minutes < 10) ?  minutes : minutes;
                                    $('#phoneNrForDOnlinePayTimeCP').html(minutes + ':' + seconds);
                                    timerStart = minutes + ':' + seconds;
                                    if(minutes == 0 && seconds == 0)location.reload();
                                }, 1000);
                            }

                            function sendCodeForDeliveryOnlinePayCP(resId,uId,uName,uEmail,shuma,theOrd){
                                let codeByCl = $('#onlineCodeInputIdCP').val();
                                let phoneNrFCl2 = $('#phoneNrInputDeliveryCP').val().replace(/ /g,'');

                                if(phoneNrFCl2 != ''){
                                    if(phoneNrFCl2[0] == '+' && phoneNrFCl2[1] == 4 && (phoneNrFCl2[2] == 1 || phoneNrFCl2[2] == 9) && phoneNrFCl2[3] == 7 && phoneNrFCl2.length == 12){
                                        phoneNrFCl2 = '0'+phoneNrFCl2.toString().slice(3);
                                    }else if(phoneNrFCl2[0] == 4 && (phoneNrFCl2[1] == 1 || phoneNrFCl2[1] == 9) && phoneNrFCl2[2] == 7 && phoneNrFCl2.length == 11){
                                        phoneNrFCl2 = '0'+phoneNrFCl2.toString().slice(2);
                                    }else if(phoneNrFCl2[0] == 0 && phoneNrFCl2[1] == 0 && phoneNrFCl2[2] == 4 && (phoneNrFCl2[3] == 1 || phoneNrFCl2[3] == 9) && phoneNrFCl2[4] == 7 && phoneNrFCl2.length == 13){
                                        phoneNrFCl2 = '0'+phoneNrFCl2.toString().slice(4);
                                    }
                                }

                                if(!$('#onlineCodeInputIdCP').val() || codeByCl.length != 6){
                                    if($('#phoneNrForDOnlinePayErr04CP').is(":hidden")){ $('#phoneNrForDOnlinePayErr04CP').show(50).delay(4500).hide(50); }
                                }else{
                                    $.ajax({
                                        url: '{{ route("delivery.closeTheOrderCash") }}',
                                        method: 'post',
                                        data: {
                                            phNr: phoneNrFCl2,
                                            codeByCl: codeByCl,
                                            codeCreated: $('#DEOnlinePayClCodeCreatedCP').val(),
                                            res: resId,
                                            tipVal: $('#tipValueSendId').val(),
                                            tipTitle: $('#tipValueCHFSendId').val(),
                                            pointsUsed: 0,
                                            theName: $('#nameTA01').val(),
                                            theLastname: $('#lastnameTA01').val(),
                                            adresa: $('#addressTA01').val(),
                                            email: $('#emailTA01').val(),
                                            theTime: $('#timeTA01').val(),
                                            plz: $('#plzTA01').val(),
                                            ort: $('#ort').val(),
                                            theCom: $('#komentTA01').val(),
                                            codeVal: $('#codeUsedValueID').val(),
                                            freeShot: $('#freeShotPh1Id').val(),  
                                            userId: uId,
                                            username: uName,
                                            userEmail: uEmail,
                                            shuma: shuma,
                                            theOrd: theOrd,
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (response) => {
                                            response = response.replace(/\s/g, '');
                                            // var dataJ = JSON.stringify(response);
                                            // var dataJ2 = JSON.parse(dataJ);
                                            // window.location.href = dataJ2['body']['RedirectUrl'];
                                            if(response == 'falseCode'){
                                                if($('#phoneNrForTOnlinePayErr05CP').is(":hidden")){ $('#phoneNrForTOnlinePayErr05CP').show(50).delay(4500).hide(50); }
                                            }else{
                                                location.reload();
                                            }
                                        },
                                        error: (error) => { console.log(error); }
                                    });

                                }
                            }
            
                        </script>











                        <?php
                            if(isset($confirmData)){
                                echo '<div class="mt-3" id="nrVerify" style="display:block;">';
                                echo 'Demo-Bestätigungscode :'.$confirmData['code'];

                                // echo $confirmData['timeStart'];
                                $endD = date('Y-m-d H:i:s',strtotime('+2 minutes',strtotime($confirmData['timeStart'])));
                                // echo $endD;
                            ?>
                            <script>
                                var x = $("#nrVerify").position(); 
                                window.scrollTo(x.left, x.top+300);
                            </script>
                            <?php
                                $kodiSend = $confirmData['code'];
                                $klientNrS = $confirmData['klientPhone'];
                                $dataSend = date('Y-m-d H:i:s',strtotime('+2 minutes',strtotime($confirmData['timeStart'])));

                            }else{
                                echo ' <div id="nrVerify" style="display:none;">';
                                $kodiSend = 1;
                                $dataSend = 1;
                                $klientNrS = 1;
                            }
                        ?>
                      
                        @if(isset($confirmDataError)){
                            <script>
                                var x = $("#nrVerify").position(); 
                                window.scrollTo(x.left, x.top+700);
                            </script>
                        @endif
                        

















                        <div class="container">
                            {{Form::open(['action' => 'ProduktController@confCodeDelivery', 'method' => 'post']) }}

                            <div class="form-group">
                                @if(isset($confirmData))
                                <?php 
                                    $thePLZNow =$confirmData['plzTA'];
                                    $delPLZ = DeliveryPLZ::where([['toRes',$theResId],['plz',$thePLZNow]])->first(); ?>
                                    @if($delPLZ != NULL)
                                        <label class="control-label color-qrorpa" style="font-size:18px;"><strong>Lieferzeit ca. {{$delPLZ->takesTime}} Minuten</strong></label>
                                    @endif
                                @endif
                                {{ Form::label('Bitte beachten Sie denn per SMS erhaltenen Code', null , ['class' => 'control-label', 'style' => 'font-size:13px;']) }}
                                {{ Form::number('codeUser','', ['class' => 'form-control']) }}
                            </div>

                            <!-- <div class="form-check-inline mb-2">
                                <label class="form-check-label">
                                    <input name="marketingSMS" type="checkbox" class="form-check-input" value="">Erhalten Sie super Angebote und Rabatte per SMS.
                                </label>
                            </div> -->

                            {{ csrf_field() }}

                            {{ Form::hidden('codeOrigjinal', $kodiSend, ['class' => 'form-control']) }}
                            {{ Form::hidden('dateEnd', $dataSend, ['class' => 'form-control']) }}
				            {{ Form::hidden('klientPhoneNr', $klientNrS, ['class' => 'form-control']) }}


                            @if(isset($confirmData))
                                {{ Form::hidden('tipValueConfirmValue', $confirmData['tipCHF'], ['class' => 'form-control tipValueConfirmValueCLA']) }}
                                {{ Form::hidden('tipValueConfirmTitle', $confirmData['tipValue'], ['class' => 'form-control tipValueConfirmTitleCLA']) }}
                            @else
                                {{ Form::hidden('tipValueConfirmValue', 0, ['class' => 'form-control tipValueConfirmValueCLA']) }}
                                {{ Form::hidden('tipValueConfirmTitle', 0, ['class' => 'form-control tipValueConfirmTitleCLA']) }}
                            @endif


                            {{ Form::hidden('userIdCash', $sendId, ['class' => 'form-control']) }}
                            {{ Form::hidden('userNameCash', $userNameS, ['class' => 'form-control']) }}
                            {{ Form::hidden('userEmailCash', $userEmailS, ['class' => 'form-control']) }}

                            {{ Form::hidden('userPorosia', $porosiaSend, ['class' => 'form-control']) }}
                            {{ Form::hidden('userPayM', 'Cash', ['class' => 'form-control']) }}
                            {{ Form::hidden('Shuma', Cart::total(), ['class' => 'form-control']) }}

                             <!-- Send points to the server -->
                             @if(Auth::check())
                                {{ Form::hidden('points', explode('.',Cart::total())[0] , ['class' => 'form-control']) }}
                                @if(isset($confirmData['pUsed']))
                                    {{ Form::hidden('pointsUsing', $confirmData['pUsed'] , ['class' => 'form-control']) }}
                                @endif
                            @else
                                {{ Form::hidden('points', 0 , ['class' => 'form-control']) }}
                            @endif

                            @if(isset($confirmData))
                                {{ Form::hidden('freeShotPh2', $confirmData['freeProd'] , ['class' => 'form-control', 'id' => 'freeShotPh2Id']) }} 
                                {{ Form::hidden('codeUsedValue2', $confirmData['codeUsed']  , ['class' => 'form-control', 'id' => 'codeUsedValueID2']) }}
                            @else
                                {{ Form::hidden('codeUsedValue2', 0 , ['class' => 'form-control', 'id' => 'codeUsedValueID2']) }}
                                {{ Form::hidden('freeShotPh2', 0 , ['class' => 'form-control', 'id' => 'freeShotPh2Id']) }}
                            @endif



                       
                    
                            @if(isset($confirmData))
                                {{ Form::hidden('nameTA02', $confirmData['nameTA'] , ['class' => 'form-control', 'id' => 'nameTA02']) }}
                                {{ Form::hidden('lastnameTA02', $confirmData['lastnameTA'] , ['class' => 'form-control', 'id' => 'lastnameTA02']) }}
                                {{ Form::hidden('addressTA02', $confirmData['addressTA'] , ['class' => 'form-control', 'id' => 'addressTA02']) }}
                                {{ Form::hidden('emailTA02', $confirmData['emailTA'] , ['class' => 'form-control', 'id' => 'emailTA02']) }}
                                {{ Form::hidden('timeTA02', $confirmData['timeTA'] , ['class' => 'form-control', 'id' => 'timeTA02']) }}

                                {{ Form::hidden('plzTA02', $confirmData['plzTA'] , ['class' => 'form-control', 'id' => 'plzTA02']) }}
                                {{ Form::hidden('ortTA02', $confirmData['ortTA'] , ['class' => 'form-control', 'id' => 'ortTA02']) }}
                                {{ Form::hidden('komentTA02', $confirmData['komentTA'] , ['class' => 'form-control', 'id' => 'komentTA02']) }}
                            @else
                                {{ Form::hidden('nameTA02', '' , ['class' => 'form-control', 'id' => 'nameTA02']) }}
                                {{ Form::hidden('lastnameTA02', '' , ['class' => 'form-control', 'id' => 'lastnameTA02']) }}
                                {{ Form::hidden('addressTA02', '' , ['class' => 'form-control', 'id' => 'addressTA02']) }}
                                {{ Form::hidden('emailTA02', '' , ['class' => 'form-control', 'id' => 'emailTA02']) }}
                                {{ Form::hidden('timeTA02', '' , ['class' => 'form-control', 'id' => 'timeTA02']) }}

                                {{ Form::hidden('plzTA02', '' , ['class' => 'form-control', 'id' => 'plzTA02']) }}
                                {{ Form::hidden('ortTA02', '' , ['class' => 'form-control', 'id' => 'ortTA02']) }}
                                {{ Form::hidden('komentTA02', '' , ['class' => 'form-control', 'id' => 'komentTA02']) }}
                            @endif
                            
                         
                          


                            {{ Form::hidden('Res', $_SESSION["Res"] , ['class' => 'form-control']) }}
                            {{ Form::hidden('t', 9000 , ['class' => 'form-control']) }}
                        
                            <div class="row">
                                <div class="col-12">
                                    {{ Form::submit('Bestätigen', ['class' => 'btn btn-dark rounded-pill py-2 btn-block']) }}
                                </div>
                            </div>
                       
                 
                            {{Form::close() }}


                        </div>
                    </div>


          













                    <div id="StripePay" style="display:none;">
                        <div class="container">
                            <div class="row">
                                <!-- <div class="col-md-12"><pre id="token_response"></pre></div> -->
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-dark rounded-pill py-2 btn-block"
                                        onclick="pay( '{{ Cart::total() }}' , '{{$userNameS}}', '{{ $userEmailS }}', '{{$orderSend}}', '{{$sendId}}')">
                                        Bezahlen Sie mit Bankkarte</button>
                                </div>
                            </div>
                        </div>
                    </div>




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
        text-align: center;
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
        <div class="col-lg-4 col-sm-0">
        </div>
        <div class="col-lg-4 col-sm-12 bg-light text-center p-3" style="padding: 0px !important;">
            <!-- Arbnor Code -->
            <div class="col-md-12 icon-area">
              <i class="fa fa-thumbs-up fa-4x like-icon-background" aria-hidden="true"></i>
            </div>
            
            
            
           {{--  <img width="75%"src="storage/gifs/shopingCart01.gif" alt=""> --}}
            <h3>
               
                @if(session('success') || (Cookie::has('trackMO') && Cookie::get('trackMO') != 'not'))
                    <?php
                       if(Cookie::has('trackMO') && Cookie::get('trackMO') != 'not'){
                            $codeValNow = explode('|',Cookie::get('trackMO'))[1];
                       }else if(session('success')){
                            $codeValNow = explode('|',session('success') )[1];
                       }
                    ?>
                    <div class="col-md-12 thanks-area">
                       <h3>Vielen Dank!</h3>
                       <i class="fa fa-check-circle fa-2x" aria-hidden="true" style="padding: 0px"> <span> Bestellung war erfolgreich!</span></i> 
                    </div>

                     <div class="col-md-12">
                    <div class="col-md-12 code-area" id="theProdCodeAlertCart">
                        <h4><strong>Abholcode:</strong></h4>
                        {{-- <h2><strong>{{$codeValNow}}</strong></h2> --}}
                        <h2 style="background: #fff; color: #3a3a3a; padding: 10px; box-shadow: brown; border-radius:15px; box-shadow: 2px 1px 9px 4px #464545;">
                            <strong>{{$codeValNow}}</strong>
                        </h2>
                        
                        @php
                        $TheRestaurantId = $_SESSION["Res"];
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
                    </div>  
                    
                    <script>
                        function hideTheProdCodeAlertCart(){
                            $('#theProdCodeAlertCart').hide(500);
                        }
                    </script>
               @else
                    <div class="col-md-12 code-area" id="theProdCodeAlertCart">   
                        Ihr Warenkorb ist leer!   
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
                    
                        <div class="rating-area text-center">

                            {{Form::open(['action' => 'RatingController@store', 'method' => 'post', 'id' => 'RatingControllerStore']) }}
                                <div style="width:100%;" class="rating-stars text-center">
                                    <fieldset style="width:100%;" class="rating d-flex flex-row-reverse justify-content-between" onclick="showTheSendBtnStar()">
                                        <input type="radio" id="star5" name="stars" value="5" /><label class = "full " style="width:15%;" for="star5" title="Awesome - 5 stars"></label>   
                                        <input type="radio" id="star4" name="stars" value="4" /><label class = "full " style="width:15%;" for="star4" title="Pretty good - 4 stars"></label>
                                        <input type="radio" id="star3" name="stars" value="3" /><label class = "full " style="width:15%;" for="star3" title="Meh - 3 stars"></label>
                                        <input type="radio" id="star2" name="stars" value="2" /><label class = "full " style="width:15%;" for="star2" title="Kinda bad - 2 stars"></label>
                                        <input type="radio" id="star1" name="stars" value="1" /><label class = "full " style="width:15%;" for="star1" title="Sucks big time - 1 star"></label>
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
            <div class="col-md-12" style="margin-top: 0; margin-bottom: 1cm;">
            
                <a href="/public/Delivery?Res={{$_SESSION['Res']}}" class="btn btn-outline-info btn-block"
                style="padding:15px; margin-top:20px; color: #ffffff; border: none;  background: #27beae; font-size: 19px;"> Bestellung fortsetzen </a>
              
            </div>
        </div>
        <div class="col-lg-4 col-sm-0">
        </div>
    </div>
</div>


@endif






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
            alert('Oops! Something went wrong')
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
            alert('Oops! Something went wrong')
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
                    alert('Oops! Something went wrong')
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


function removeOrderCh(rIdFinder, po) {
    $.ajax({
        url: '{{ route("cart.destroyTakeaway") }}',
        method: 'delete',
        data: {
            rowId: $('.'+rIdFinder).val(),
            _token: '{{csrf_token()}}'
        },
        success: () => {
            $('#orderRow' + po).hide(1200);
            $('#allOrders').load('/order #allOrders', function() {
                $('.AllExtrasOrder').hide();
                $('#totalShowCh').load('/order #totalShowCh', function() {});
            });
        },
        error: (error) => {
            console.log(error);
            alert('Oops! Something went wrong')
        }
    })
}
</script>
@endsection