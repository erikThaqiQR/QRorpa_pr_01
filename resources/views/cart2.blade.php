@extends('layouts.appOrders')






<?php
    use App\Restorant;
    use App\Piket;
?>


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
                                if (session_status() == PHP_SESSION_NONE) {
                                    session_start();
                                }
                            ?>
                            @if(isset($_SESSION["Res"]) && isset($_SESSION['t']))
                                
                                <span class="color-qrorpa" style="font-size:20px; width:50%; font-weight:bold;">{{Restorant::find($_SESSION["Res"])->emri}}</span>
                                 <span style="font-size:20px; margin-left:30%;  width:50%; font-weight:bold;" class="text-right color-qrorpa"> Tisch: {{$_SESSION['t']}}</span>
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
                            ?>
                        @foreach(Cart::content() as $item)
                        <?php
                            if($step++ == 1){
                                $porosiaSend .= $item->name."-8-".$item->options->persh."-8-".$item->options->ekstras.'-8-'.$item->qty.'-8-'.$item->price.'-8-'.explode('||',$item->options->type)[0].'-8-'.$item->options->koment.'-8-'.$item->id;
                            }else{
                                $porosiaSend .= '---8---'.$item->name."-8-".$item->options->persh."-8-".$item->options->ekstras.'-8-'.$item->qty.'-8-'.$item->price.'-8-'.explode('||',$item->options->type)[0].'-8-'.$item->options->koment.'-8-'.$item->id;
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
                                <strong>{{sprintf('%01.2f', $item->price)}} <sup>CHF</sup><br>
                                    @if($item->qty > 1)
                                        {{ sprintf('%01.2f', $item->price * $item->qty)}} <sup>CHF</sup>
                                    @endif
                                    </strong>
                            </div>
                            <div style="width:15%">
                                <div class="form-group">
                                    <select style="text-align-last:center; border:none; font-size:16px;" class="form-control quantity mt-2"
                                        onchange="updateQTY(this.id,'{{$item->rowId}}')"
                                        id="drop{{$item->id}}{{$item->rowId}}" data-id="{{ $item->rowId }}">
                                        @for($i = 1; $i <= 9; $i++) <option value='{{$i}}'
                                            {{ $item->qty == $i ? 'selected' : '' }}>{{$i}}</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                            <div style="width:15%" class="text-right">
                                <button type="button" onclick="removeOrderCh('{{$item->rowId}}','{{$point++}}')"
                                    class="btn btn-default">
                                    <i class="far fa-trash-alt fa-2x mt-1"></i>
                                </button>
                            </div>
                        </div>
                        <div style="border-bottom:1px solid lightgray; padding-bottom:10px; margin-top:-10px;"
                            id="buttonExt{{$orderExtras}}">
                            @if($item->options->ekstras != "")
                            <button class="btn btn-block btn-outline-default"
                                onclick="showOrderExtras({{$orderExtras}})">
                                Extras anzeigen
                            </button>
                            @endif
                        </div>

                        <div id="divExtra{{$orderExtras}}" class="d-flex flex-row flex-wrap">

                            <?php
                                $ExtraStephh = 0;
                                    if($item->options->ekstras ==""){
                                        echo '<p class="AllExtrasOrder orderExtra{{$orderExtras}}"> No extra ingridients </p>';
                                    }else{
                                        $extProD1 =explode('--0--',$item->options->ekstras);
                                        foreach($extProD1 as $extProOne){
                                            if(!empty($extProOne) || $extProOne == " "){
                                                $extProD2 =explode('||',$extProOne);
                                                    if(!empty($extProD2[0])){
                                                    ?>
                            <div id="ExtraShowRev{{$extProD2[0]}}O{{$ExtraStephh}}" style="width:50%; display:none;"
                                class="AllExtrasOrder orderExtra{{$orderExtras}}">
                                <button
                                    onclick="removeThisExtraFromProd('ExtraShowRev{{$extProD2[0]}}O{{$ExtraStephh}}','{{$item->rowId}}','{{$extProOne}}','{{$item->options->ekstras}}')"
                                    class="btn btn-default btn-sm"><i class="fas fa-times fa-sm"></i> {{$extProD2[0]}} {
                                    {{$extProD2[1]}} }</button>
                            </div>
                            <?php
                                                    }
                                            }
                                        }
                                    }                    
                                ?>

                        </div>
                        <?php $orderExtras++ ?>

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
                    function removeThisExtraFromProd(thisId, rId, extOne, allExt) {

                        $.ajax({
                            url: '{{ route("produktet.CartRe") }}',
                            method: 'post',
                            data: {
                                elementId: rId,
                                extPro: extOne,
                                allExtra: allExt,
                                _token: '{{csrf_token()}}'
                            },
                            success: (response) => {
                                var current = ($('#CartTotalFooter').text()).split(" ")[0];
                                var price = extOne.split("||")[2];
                                $('#CartTotalFooter').text((current - price).toFixed(2) + ' CHF');

                                $('#' + thisId).hide('slow');
                                $('#footerShowOrdersMobile').load('/ #footerShowOrdersMobile',
                                function() {});
                            },
                            error: (error) => {
                                console.log(error);
                                alert('Oops! Something went wrong')
                            }
                        })
                    }
                    </script>




















                    <div>
                        <a href="/?Res={{$_SESSION["Res"]}}&t={{$_SESSION["t"]}}"
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
                        $('.orderExtra' + orderExt).show('slow');
                        $('#buttonExt' + orderExt).hide('slow');
                        $('#divExtra' + orderExt).attr('style',
                            "border-bottom:1px solid lightgray; padding-bottom:15px;");

                    }
                    </script>











                </div>
                <div class="col-lg-4 bg-white rounded shadow-sm mb-5">
                <div class=" rounded-pill px-4 py-3 text-uppercase font-weight-bold">Bestellübersicht 

                    @if(Auth::check())
                        <span style="margin-left:25%;">
                            @if(Piket::where('klienti_u',Auth::user()->id)->first() != null)
                                <?php $hasPo = Piket::where('klienti_u',Auth::user()->id)->first()->piket;?>
                            @else
                                <?php $hasPo = Piket::where('klienti_u',Auth::user()->id)->first()->piket;?>
                            @endif
                            <input type="hidden" id="userPointsB" value="{{$hasPo}}">
                                <span id="userPointsBShow">{{$hasPo}} p</span>
                        </span>
                    @endif
                </div>
                <div>
                    <h5 class="color-qrorpa">Kaffee fur Serviceteam</h5>
                </div>
                <div class="d-flex justify-content-between" style="margin-top:-5px;">
                    <?php
                        if(isset($confirmData)){
                            ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter0" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">0%</button>
                            <?php
                            if($confirmData['tipValue'] == 5){
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter5" style="width:15%;" type="button" class="btn selectedTip waiterTipBtns">5%</button>
                                <?php
                            }else{
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter5" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">5%</button>
                                <?php
                            }

                            if($confirmData['tipValue'] == 10){
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter10" style="width:15%;" type="button" class="btn selectedTip waiterTipBtns">10%</button>
                                <?php
                            }else{
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter10" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">10%</button>
                                <?php
                            }

                            if($confirmData['tipValue'] == 15){
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter15" style="width:15%;" type="button" class="btn selectedTip waiterTipBtns">15%</button>
                                <?php
                            }else{
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter15" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">15%</button>
                                <?php
                            }

                            if($confirmData['tipValue'] == 20){
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter20" style="width:15%;" type="button" class="btn selectedTip waiterTipBtns">20%</button>
                                <?php
                            }else{
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter20" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">20%</button>
                                <?php
                            }

                            if($confirmData['tipValue'] == 25){
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter25" style="width:15%;" type="button" class="btn selectedTip waiterTipBtns">25%</button>
                                <?php
                            }else{
                                ?>
                                <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter25" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">25%</button>
                                <?php
                            }




                        }else{
                            ?>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter0" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">0%</button>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter5" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">5%</button>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter10" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">10%</button>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter15" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">15%</button>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter20" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">20%</button>
                            <button onclick="setTip(this.id,'{{cart::total()}}')" id="tipWaiter25" style="width:15%;" type="button" class="btn btn-outline-dark waiterTipBtns">25%</button>
                            <?php
                        }
                    ?>
                
                    
                </div>
             










                <div class="pr-4 pl-4 pb-2">
                    <p class="font-italic mb-4"></p>
                    <ul class="list-unstyled mb-4" id="totalShowCh">
                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">Zwischensumme </strong>
                            <strong>{{ number_format((float) Cart::subtotal()-(Cart::subtotal()*0.081), 2, '.', '') }} CHF </strong></li>
                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">MwSt 8.10%</strong>
                            <strong>{{ number_format((float)Cart::subtotal()*0.081, 2, '.', '') }} CHF </strong></li>
                        <li class="d-flex justify-content-between py-3 border-bottom" id="bakshishViewLi" >

                            <strong class="text-muted">Kellner Trinkgeld</strong>
                            @if(isset($confirmData))
                                <strong><span id="bakshishView">{{$confirmData['tipCHF']}}</span> CHF </strong>
                            @else
                                <strong><span id="bakshishView">0</span> CHF </strong>
                            @endif
                           
                        </li>


                        <!-- RReshti per piket -->
                        <li class="d-flex justify-content-between py-3 flex-wrap border-bottom" id="bakshishViewLi" >
                            @if(Auth::check())
                                <strong class="text-muted text-left ponitsEarn"  style="width:50%;"> Punkte </strong>
                                <span id="nrOfPoints" class="text-right ponitsEarn" style="font-weight:bold; width:50%;">{{explode('.', Cart::total())[0]}}  p</span> 

                                <strong class="text-muted text-left pointsUse"  style="width:50%; display:none">  Punkte verwenden </strong>
                                <span id="nrOfPoints" class="text-right pointsUse" style="font-weight:bold; width:50%; display:none;"> 
                                    <div class="form-group">
                                        <input class="form-control text-center" type="number" step="1" min="1" id="pointUserAmount" style="border:none; border-bottom:1px solid lightgray;">
                                    </div>
                                </span> 



                                <div class="alert alert-danger text-center" style="width:100%;" id="invalidePointsUse1">
                                    Please write a valid value!
                                </div>
                                <div class="alert alert-danger text-center" style="width:100%; " id="invalidePointsUse2">
                                   You dont have that much points!
                                </div>
                                <button onclick="earnPoints()"  style="width:44%; display:none;" class="btn btn-block btn-outline-dark mt-2 pointsUse">
                                    Zurück <!-- Back -->
                                </button>
                                <button onclick="setUsePoints()"  style="width:44%; display:none" class="btn btn-block btn-outline-dark mt-2 pointsUse">
                                    Einstellen <!-- Use (set points) -->
                                </button>

                                <script>
                                    $('#invalidePointsUse1').hide();
                                    $('#invalidePointsUse2').hide();
                                </script>



                                <button onclick="usePoints()"  style="width:100%;" class="btn btn-block btn-outline-dark mt-2 ponitsEarn">
                                    Gebrauchspunkt zum Verkauf <!-- Use Points -->
                                </button>
                            @else
                            <strong class="text-muted" style="width:100%;"> Login to recive and use points </strong>
                            @endif
                            
                        </li><!-- fund   RReshti per piket -->

                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">Gesamt</strong>
                            @if(isset($confirmData))
                                <?php $dispTot = Cart::total()+$confirmData['tipCHF']; ?>
                            @else
                                <?php $dispTot = Cart::total(); ?>
                            @endif

                            <h5 class="font-weight-bold "> <span class="totalOnCart">{{ $dispTot }}</span>  CHF </h5>
                           
                        </li>
                    </ul>
                    
                </div>



<script>
    function usePoints(){
        $('.ponitsEarn').hide(500);
        $('.pointsUse').show(500);
    }
    function earnPoints(){
        $('.ponitsEarn').show(500);
        $('.pointsUse').hide(500);
    }

    function setUsePoints(){
        var PointsVal = parseInt($('#pointUserAmount').val());

        if(PointsVal <= 0 ){
                // Nder 0 ose 0
            $('#invalidePointsUse1').show().delay(2500).hide();
        }else if( PointsVal > parseInt($('#userPointsB').val())){
                // Nuk ka pike te mjaftueshme
            $('#invalidePointsUse2').show().delay(2500).hide();
        }else{
            var newVal = parseFloat($('.totalOnCart').html()) - (parseFloat(PointsVal)*0.01);
            $('.totalOnCart').html(parseFloat(newVal).toFixed(2));

                // Send variable to cash payment Step 1
            var pointsU = parseInt($('#pointsUsedIdFS').val()) + parseInt((PointsVal));
            $('#pointsUsedIdFS').val(parseInt(pointsU));

                // Alarm the user for its used points
            var actPoints = parseInt( $('#userPointsB').val()) - parseInt(PointsVal);
            $('#userPointsB').val(parseInt(actPoints));
            $('#userPointsBShow').html(actPoints+' p  ( '+parseInt(pointsU)+' p)');




            // Change the subtotal amount

            // Change the tvsh amount

             
        }
        
    }

    
</script>


















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
                    function setTip(btnId, cTot){
                        // bakshishView

                        $('.waiterTipBtns').attr('class', 'btn btn-outline-dark waiterTipBtns');
                        $('#'+btnId).attr('class', 'btn selectedTip waiterTipBtns');

                        $('#tipValueSendId').val();
                        $('#tipValueCHFSendId').val();

                     

                        switch(btnId){
                            case 'tipWaiter0':
                                $('#bakshishView').text(0); 
                                $('#tipValueCHF').val(0);

                                $('#tipValueSendId').val(0);
                                $('#tipValueCHFSendId').val(0);

                                $('.tipValueConfirmPer').val(0);
                                $('.totalOnCart').text(cTot);
                            break;


                            case 'tipWaiter5':
                                var tipShuma = parseFloat((cTot/100)*5).toFixed(2);
                                
                                if(tipShuma[tipShuma.length-1] == 1){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 2){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 3){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 4){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 6){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 7){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 8){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 9){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }
                                var totShuma = (parseFloat(cTot)+parseFloat(tipShuma)).toFixed(2);

                                // alert(tipShuma[tipShuma.length-1]);
                                $('#bakshishView').text(tipShuma); 

                                $('#tipValueSendId').val(5);
                                $('#tipValueCHFSendId').val(tipShuma);

                                $('.tipValueConfirmPer').val(5);
                                $('.totalOnCart').text(totShuma);
                            break;


                            case 'tipWaiter10':
                                var tipShuma = parseFloat((cTot/100)*10).toFixed(2);
                                
                                if(tipShuma[tipShuma.length-1] == 1){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 2){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 3){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 4){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 6){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 7){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 8){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 9){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }
                                var totShuma = (parseFloat(cTot)+parseFloat(tipShuma)).toFixed(2);

                                $('#bakshishView').text(tipShuma); 

                                $('#tipValueSendId').val(10);
                                $('#tipValueCHFSendId').val(tipShuma);

                                $('.tipValueConfirmPer').val(10);
                                $('.totalOnCart').text(totShuma);
                            break;


                            case 'tipWaiter15':
                                var tipShuma = parseFloat((cTot/100)*15).toFixed(2);
                                
                                if(tipShuma[tipShuma.length-1] == 1){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 2){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 3){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 4){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 6){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 7){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 8){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 9){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }
                                var totShuma = (parseFloat(cTot)+parseFloat(tipShuma)).toFixed(2);
                                $('#bakshishView').text(tipShuma); 

                                $('#tipValueSendId').val(15);
                                $('#tipValueCHFSendId').val(tipShuma);

                                $('.tipValueConfirmPer').val(15);
                                $('.totalOnCart').text(totShuma);
                            break;


                            case 'tipWaiter20':
                                var tipShuma = parseFloat((cTot/100)*20).toFixed(2);
                                
                                if(tipShuma[tipShuma.length-1] == 1){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 2){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 3){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 4){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 6){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 7){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 8){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 9){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }
                                var totShuma = (parseFloat(cTot)+parseFloat(tipShuma)).toFixed(2);

                                $('#bakshishView').text(tipShuma); 

                                $('#tipValueSendId').val(20);
                                $('#tipValueCHFSendId').val(tipShuma);

                                $('.tipValueConfirmPer').val(20);
                                $('.totalOnCart').text(totShuma);
                            break;


                            case 'tipWaiter25':
                                var tipShuma = parseFloat((cTot/100)*25).toFixed(2);
                                
                                if(tipShuma[tipShuma.length-1] == 1){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 2){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 3){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 4){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 6){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.01)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 7){
                                    tipShuma =(parseFloat(tipShuma)- parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 8){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.02)).toFixed(2); 
                                }else if(tipShuma[tipShuma.length-1] == 9){
                                    tipShuma =(parseFloat(tipShuma)+ parseFloat(0.01)).toFixed(2); 
                                }
                                var totShuma = (parseFloat(cTot)+parseFloat(tipShuma)).toFixed(2);

                                $('#bakshishView').text(tipShuma); 

                                $('#tipValueSendId').val(25);
                                $('#tipValueCHFSendId').val(tipShuma);

                                $('.tipValueConfirmPer').val(25);
                                $('.totalOnCart').text(totShuma);
                            break;
                        }
                        

                    }

                </script>


















                <div class="container">
                    <div class="row">
                        <div class="col-12">

                        </div>
                    </div>
                    <div class="row mt-4 mb-4">
                        <div class="col-6 text-center">
				
                            <?php
                                if(isset($confirmData)){
                                    echo '
                                        <button class="btn btn-default text-center">
                                            <img src="storage/images/CashPayC.PNG" alt="">
                                        </button>
                                    ';
                                }else{
                                    echo '
                                        <button class="btn btn-default text-center noBorderBtn" onclick="showCash()">
                                            <img src="storage/images/CashPayW.PNG" alt="">
                                        </button>
                                    ';
                                }
                            ?>

				<p><strong>Barzahlung</strong></p>
                </div>

                        <div class="col-6 text-center">
			
                            <script src="https://pay.sandbox.datatrans.com/upp/payment/js/datatrans-2.0.0.js"></script>
                            <!-- <form id="paymentForm" data-merchant-id="1100004624" data-amount="1000" data-currency="CHF"
                                data-refno="123456789" data-sign="30916165706580013"> -->
                                <button class="btn btn-default noBorderBtn" id="paymentButton" data-toggle="modal" data-target="#onlinePayP"><img
                                        src="storage/images/CardPayW.PNG" alt=""></button>
				                        <p><strong>Online</strong></p>
                            <!-- </form> -->

                            <script type="text/javascript">
                            //document.getElementById("paymentButton").onclick = function() {
                             //   Datatrans.startPayment({
                             //       'form': '#paymentForm'
                              //  });
                            //};
                            </script>
				
                        </div>

                </div>
                <!-- End -->
            </div>

          <!-- The Modal -->
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
            <input type="hidden" class="form-control noBorderBtn" placeholder="Enter email" value="user@empty.com" id="email">
        </div>
        ';

        $sendId = 0;
        $userNameS = "empty";
        $userEmailS = "empty";
     }
  

?>





















    <script>




      





        function showCash() {
            var Cash = document.getElementById('switch1');
            var CashPay = document.getElementById('CashPay');
            CashPay.style.display = "block";
        }
    </script>































                        <?php
    $randCode = rand(111111,999999);
?>



                        <div id="CashPay" style="display:none;">
                            <div class="container">
                                {{Form::open(['action' => 'ProduktController@confNr', 'method' => 'post']) }}
                                <div class="input-group mb-3 input-group-sm" style="margin-top:-15px;">
                                <label for="demo">Handynummer (Wir benötigen Ihre Handynummer, um die Bestellung zu verifizieren):</label>
                                <div class="input-group-prepend">
                                    <!-- <span class="input-group-text">+41</span> -->
                                </div>
                                <input type="number" name="phoneNr" placeholder="07x xxx xx xx" class="form-control noBorderbtn" 
                                style="font-size:16px;">
                        </div>

                                {{ Form::hidden('code', $randCode , ['class' => 'form-control']) }}


                                {{ Form::hidden('tipValueSend', 0 , ['class' => 'form-control', 'id' => 'tipValueSendId']) }}
                                {{ Form::hidden('tipValueCHFSend', 0 , ['class' => 'form-control', 'id' => 'tipValueCHFSendId']) }}

                                {{ Form::hidden('pointsUsed', 0 , ['class' => 'form-control', 'id' => 'pointsUsedIdFS']) }}


                                <div class="row">
                                    <div class="col-12">
                                        <!-- <button class=""
                        			onclick="payCash('qrorpa','38345234379','123456')">
                        					Pay with Cash</button> -->
                                        {{ Form::submit('Bezahle mit Bargeld', ['class' => 'btn btn-dark rounded-pill py-2 btn-block']) }}
                                    </div>
                                </div>
                                {{Form::close() }}



                            </div>
                        </div>











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

                        <div class="container">
            


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

<?php
    session_start();
?>
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
    
</style>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-4 col-sm-0">
        </div>
        <div class="col-lg-4 col-sm-12 bg-light text-center p-3" style="border-radius:40px;">
            <img width="75%" src="storage/gifs/box01.gif" alt="">
            <h3>
                <p style="text-align: left; font-size: 14px;">Wie finden Sie QRorpa System?<br>

                    Bewerten Sie uns und hinterlassen Sie eine Nachricht, damit wir uns verbessern können!</p>
                <div class="rating-area">


                                           {{Form::open(['action' => 'RatingController@store', 'method' => 'post']) }}

                                                           
                                                            <div class="rating-stars">
                                                            <fieldset class="rating">
                                                                <input type="radio" id="star5" name="stars" value="5" /><label class = "full" for="star5" title="Awesome - 5 stars"></label>   
                                                                <input type="radio" id="star4" name="stars" value="4" /><label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                                                                <input type="radio" id="star3" name="stars" value="3" /><label class = "full" for="star3" title="Meh - 3 stars"></label>
                                                                <input type="radio" id="star2" name="stars" value="2" /><label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                                                                <input type="radio" id="star1" name="stars" value="1" /><label class = "full" for="star1" title="Sucks big time - 1 star"></label>
                                                            </fieldset>
                                                        </div>
                                                    <br>

                                                        <div class="form-group">

                                                            {{ Form::label('Kommentar', null, ['class' => 'control-label']) }}
                                                            {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => 4, 'cols' => 54]) !!}
                                                          {{--   {{ Form::text('comment','', ['class' => 'form-control']) }} --}}
                                                        </div>

                                                        <div class="form-group">
                                                            {{ Form::submit('Senden', ['class' => 'form-control btn btn-primary']) }}
                                                        </div>

                                                    {{Form::close() }}
                                                </div>
            @if(session('success'))
                @if(strpos(session('success'), 'Bestellung') !== false)  
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success">
                                    <a  id="succTrack" href="{{ route('trackOrder.Home') }}">
                                        {{session('success')}}

                                    </a>
                                </div>

                                
                                
                            </div>
                        </div>
                    </div>

                @endif
            @else
                Ihr Warenkorb ist leer!            
            @endif 

		    
            </h3>
            @if(isset($_SESSION['Res']) && isset($_SESSION['t']))
                <a href="/?Res={{$_SESSION['Res']}}&t={{$_SESSION['t']}}" class="btn btn-outline-info"
                style="padding:15px; margin-top:20px;"> Bestellung fortsetzen </a>
            @else
                <a href="{{url('/')}}" class="btn btn-outline-info" style="padding:15px; margin-top:20px;"> Bestellung
                fortsetzen </a>
            @endif
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
function updateQTY(id, rId) {
    var element = $('#' + id);

    $.ajax({
        url: '{{ route("cart.update") }}',
        method: 'post',
        data: {
            id: rId,
            val: element.val(),
            _token: '{{csrf_token()}}'
        },
        success: () => {

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


function removeOrderCh(rId, po) {
    $.ajax({
        url: '{{ route("cart.destroy") }}',
        method: 'delete',
        data: {
            rowId: rId,
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