@extends('layouts.appOrders')
<!-- 1300 kodi -->

<?php
    use App\Restorant;
    use App\Piket;
    use App\Produktet;
    use App\Cupon;
    use App\Orders;
    use App\Takeaway;
    use Carbon\Carbon;
    use App\TakeawaySchedule;
    use App\couponUsedPhoneNr;
    
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
    <!-- <script src="https://kit.fontawesome.com/4686ab5ef0.js" crossorigin="anonymous"></script> -->

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
    input, select { font-size: 100%; }
</style>



@section('content')


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


<input name="bowlingLineNr" id="bowlingLineNr" value="-1" type="hidden">


<div id="allOrders">

    <div class="pb-5">
        <div class="container mt-1">




            <div class="row">
                <div class="col-lg-8 p-3 bg-white rounded shadow-sm mb-1">
                    <h4 class="text-center"><strong>Bestellübersicht </strong></h4>
                    <!-- Shopping cart table -->
                    <div class="table-responsive">
                        <!-- <div class="col-sm-12 col-md-12 col-lg-12 pb-2 text-center">
                            <h3 class="color-qrorpa" style="font-weight:bold;"><span id="cartCountCh">{{Cart::count()}}</span> Artikel </h3>
                        </div> -->
                        <div class="col-sm-12 col-md-12 col-lg-12 pb-2 d-flex justify-content-between">
                            @if(isset($_SESSION["Res"]) && isset($_SESSION['t']))
                                <span class="color-qrorpa" style="font-size:1em; width:70%; font-weight:bold;">{{Restorant::find($_SESSION["Res"])->emri}}</span>
                                @if ($_SESSION['t'] == 500)
                                    <span style="font-size:1em; width:30%; font-weight:bold;" class="text-center color-qrorpa">Takeaway</span>
                                @else
                                    <span style="font-size:1em; width:30%; font-weight:bold;" class="text-center color-qrorpa">{{__('others.Table')}}: {{$_SESSION['t']}}</span>
                                @endif
                            @else
                                Kein Restaurant
                            @endif
                        </div>








                        <div class="d-flex justify-content-between">
                            <div style="width:100%">
                                <p class="text-center" style="font-size:1.2rem; margin:2px; padding:3px;"><strong>Produkte</strong></p>
                            </div>
                       
                        </div>

                        <?php
                            $porosiaSend = "";
                            $step = 1;
                            $orderExtras = 1;
                            
                            $point = 0;
                            $ExtraStephh = 0;

                            $Restrict = 0;
                            $ageRestThis = 0;
                        ?>    
                        @foreach(Cart::content() as $item)
                        <?php
                            if($step++ == 1){
                                $porosiaSend .= $item->name."-8-".$item->options->persh."-8-".$item->options->ekstras.'-8-'.$item->qty.'-8-'.number_format($item->price*$item->qty,2 ,'.' ,'').'-8-'.explode('||',$item->options->type)[0].'-8-'.$item->options->koment.'-8-'.$item->id;
                            }else{
                                $porosiaSend .= '---8---'.$item->name."-8-".$item->options->persh."-8-".$item->options->ekstras.'-8-'.$item->qty.'-8-'.number_format($item->price*$item->qty,2 ,'.' ,'').'-8-'.explode('||',$item->options->type)[0].'-8-'.$item->options->koment.'-8-'.$item->id;
                            } 
                            
                            if(Takeaway::find($item->id) != Null){
                                $thisProIns = Produktet::find(Takeaway::find($item->id)->prod_id);
                                if($thisProIns != null){
                                    if($thisProIns->restrictPro == 18){
                                        $Restrict = 18;
                                    }else if($thisProIns->restrictPro == 16){
                                        if($Restrict != 18){
                                            $Restrict = 16;
                                        }
                                    }
                                    $ageRestThis = $thisProIns->restrictPro;
                                }
                            }
                        ?>



                        <div class="d-flex justify-content-between pt-2" id="orderRow{{$point}}">
                            <div style="width:60%" class="mt-1">
                                <h6 class="mb-0">
                                    <a href="#" class="text-dark d-inline-block align-middle">
                                        <span style="font-weight: bold;" class="mr-2">{{$item->qty}} X</span>{{ $item->name }}
                                        @if($ageRestThis != 0)
                                            <img style="width:20px; height:auto;" src="storage/icons/{{$ageRestThis}}+.png" alt="">
                                        @endif
                                    </a>
                                </h6>
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
                                <strong> 
                                    <span class="text-center" id="setPrice{{$item->rowId}}">{{sprintf('%01.2f', $item->price)}}</span>  <sup>CHF</sup><br>
                                    @if($item->qty > 1)
                                      <span class="text-center" id="setPriceSasiaAll{{$ExtraStephh}}">{{ sprintf('%01.2f', $item->price * $item->qty)}}</span>   <sup>CHF</sup>
                                    @endif
                                </strong>
                            </div>
                            <!-- <div style="width:20%">
                                <div class="form-group">
                                    <input type="hidden" value="{{$item->qty}}" id="currentQTY{{$ExtraStephh}}">
                                    <select  onchange="updateQTY(this.id,'{{$item->rowId}}','{{$ExtraStephh}}')" style="width:100% !important;" class="form-control" id="drop{{$item->id}}{{$item->rowId}}" data-id="{{ $item->rowId }}">
                                        @for($i = 1; $i <= 99; $i++) 
                                            <option class="pr-4 pl-4" value='{{$i}}'{{ $item->qty == $i ? 'selected' : '' }}> {{$i}} </option>
                                        @endfor
                                    </select>
                                </div>
                            </div> -->
                            <div style="width:15%" class="text-right">
                                <?php $rand = rand(111111111,999999999);?>
                                <input type="hidden" value="{{$item->rowId}}" class="rowIdGo{{$ExtraStephh}}">
                                <button type="button" onclick="removeOrderCh('rowIdGo{{$ExtraStephh}}','{{$point++}}','{{$rand}}','{{$item->options->tabOId}}')"
                                    class="btn btn-default" id="deleteCartIt{{$rand}}Btn">
                                    <i class="far fa-trash-alt fa-xl mt-1"></i>
                                </button>
                                <img id="deleteCartIt{{$rand}}Gif" style="width:75px; height:auto; display:none;" src="storage/gifs/delete01.gif" alt="">
                            </div>
                        </div>

                        @if($loop->last)
                        <div style="padding-bottom:10px; margin-top:-10px;"id="buttonExt{{$orderExtras}}">
                        @else
                        <div style="border-bottom:1px solid lightgray; padding-bottom:10px; margin-top:-10px;"id="buttonExt{{$orderExtras}}">
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
                        <a href="/?Res={{$_SESSION['Res']}}&t={{$_SESSION['t']}}" style="color:rgba(151,153,157,255); border:1px solid rgb(39,190,175);"
                            class="btn btn-block text-center">
                            <img style="color:rgb(39,190,175);" src="https://img.icons8.com/nolan/64/add.png" width="20" />
                            <strong>Weitere Artikel hinzufügen</strong>
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




                <div class=" rounded-pill px-4 py-3 text-uppercase font-weight-bold">

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













































             
                <div class="pr-4 pl-4">
                    <p class="font-italic mb-2"></p>
                    <ul class="list-unstyled" id="totalShowCh">
                        <li class="d-flex justify-content-between py-3 border-bottom">
                            <strong class="text-muted">Zwischensumme </strong>
                            @if(isset($confirmData))
                                <?php $newPrice = Cart::total() - ($confirmData['pUsed']*0.01);?>
                                <strong><span id="subTotalCart">{{ number_format((float) $newPrice, 2, '.', '') }}</span>  CHF </strong>
                            @else
                                <strong><span id="subTotalCart">{{ number_format((float) Cart::total(), 2, '.', '') }}</span>  CHF </strong>
                            @endif
                        </li>

                        <li class="d-flex justify-content-between py-3 border-bottom" id="bakshishViewLi" >
                            <strong class="text-muted">Kellner Trinkgeld</strong>
                            @if(isset($confirmData))
                                <strong><span id="bakshishView">{{$confirmData['tipCHF']}}</span> CHF </strong>
                            @else
                                <strong><span id="bakshishView">0</span> CHF </strong>
                            @endif
                        </li>


                        <!-- RReshti per piket -->
                        <li class="d-flex justify-content-between py-3 flex-wrap border-bottom" id="bakshishViewLi" style="display:none !important;">
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
                                            <input class="form-control text-center" type="number" step="1" min="1" id="pointUserAmount" style="border:none; border-bottom:1px solid lightgray;">
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
                                <h5 class="font-weight-bold "> <span class="totalOnCart">{{ Cart::total()+$confirmData['tipCHF']-($confirmData['pUsed']*0.01)-$confirmData['codeUsed'] }}</span> CHF </h5>
                            @else
                                <h5 class="font-weight-bold "> <span class="totalOnCart">{{ Cart::total() }}</span>  CHF </h5>
                            @endif
                           
                        </li>















                        @if(Cupon::where('toRes', $theResId)->get()->count() > 0 && !isset($confirmData))
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
                                            <i class="fas fa-xl fa-barcode pr-5"></i> Gutschein verwenden
                                        </button>
                                    @endif
                                @else
                                    <button id="cuponInputStarter" style="width:100%" class="btn btn-outline-default" onclick="showCuponInput()">
                                        <i class="fas fa-xl fa-barcode pr-5"></i> Gutschein verwenden
                                    </button>
                                @endif

                                <div style="width:100%; display:none;" class="d-flex flex-wrap justify-content-between" id="cuponInputDiv">
                                    @if(Cookie::has('rouletteCuponId') && $hasUsedIt == Null && $theCoup != Null)
                                        <div class="input-group mb-3" style="width:65%;" id="cuponInputDiv2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            </div>
                                            <input id="cuponTypedCart" type="text" class="form-control shadow-none" value="{{$theCoup->codeName}}" placeholder="Code">
                                        </div>
                                        <?php
                                            if(isset($_SESSION["phoneNrVerified"])){
                                                $clPhoneNr = $_SESSION["phoneNrVerified"];
                                            }else{ $clPhoneNr = 0; }
                                        ?>
                                        <button style="width:34%; height:38px;" class="btn btn-outline-primary" onclick="checkCupon('{{$theResId}}','0','{{Cart::total()}}')"
                                            id="cuponCheckBtn">Aktivieren
                                        </button>
                                        <p id="activateTheCouponText" class="text-center" style="font-size:1.2rem; width:100%; margin-bottom:0;"><strong>Bitte Gutscheincode aktivieren!</strong></p>
                                    @else
                                    <div class="input-group mb-3" style="width:65%; display:none;" id="cuponInputDiv2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input id="cuponTypedCart" type="text" class="form-control shadow-none" placeholder="Code">
                                    </div>
                                    <?php
                                        if(isset($_SESSION["phoneNrVerified"])){
                                            $clPhoneNr = $_SESSION["phoneNrVerified"];
                                        }else{ $clPhoneNr = 0; }
                                    ?>
                                    <button style="width:34%; height:38px; display:none;" class="btn btn-outline-primary" onclick="checkCupon('{{$theResId}}','0','{{Cart::total()}}')"
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

                            <script>
                                function showCuponInput(){
                                    $('#cuponInputStarter').hide(100);
                                    $('#cuponInputDiv').show(100);
                                    $('#cuponInputDiv2').show(100);
                                    $('#cuponCheckBtn').show(100);

                                    $('#couponWon1').hide(100);
                                    $('#couponWon2').hide(100);
                                }
                                function showCuponInputWC(cCode){
                                    $('#cuponInputStarter').hide(100);
                                    $('#cuponInputDiv').show(100);
                                    $('#cuponInputDiv2').show(100);
                                    $('#cuponCheckBtn').show(100);

                                    $('#couponWon1').hide(100);
                                    $('#couponWon2').hide(100);

                                    $('#cuponTypedCart').val(cCode);
                                }
                                function checkCupon(resId,clPhNr, ct){
                                    $.ajax({
                                        url: '{{ route("cupons.checkCupon") }}',
                                        method: 'post',
                                        data: {
                                            res: resId,
                                            code: $('#cuponTypedCart').val(),
                                            clPN: clPhNr,
                                            cartTot: ct,
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (res) => {
                                            res = $.trim(res);
                                            $('#activateTheCouponText').hide(50);
                                            if(res == 'no' ){
                                                $('#cuponOffError').show(200).delay(3000).hide(200);
                                            }else if(res == 'noUsed'){
                                                $('#cuponOffErrorUsed').show(200).delay(3000).hide(200);
                                            }else if(res == 'noETot'){
                                                $('#cuponOffErrorNotETot').show(200).delay(4000).hide(200);
                                            }else if(res == 'noUsesLeft'){
                                                $('#cuponOffErrorNoMLeft').show(200).delay(4000).hide(200);
                                            }else{
                                                switch(res.split('||')[3]){
                                                    case '1':
                                                        this.procesCoupun1(res,1);
                                                    break;
                                                    case '2':
                                                        this.procesCoupun1(res,2);
                                                    break;
                                                    case '3':
                                                        this.procesCoupun1(res,3);
                                                    break;
                                                }
                                            }
                                        },
                                        error: (error) => { console.log(error); }
                                    });
                                }
                                function procesCoupun1(res,typ){
                                    var res2D = res.split('||');
                                    $('#couponUsedId').val(res2D[5]);
                                    $('#couponUsedIdOnConfNrId').val(res2D[5]);
                                    $('#couponUsedIdOnConfNrIdCard').val(res2D[5]);
                                    
                                    if(typ == 1){var coupVal = res2D[0] ;}
                                    else if(typ == 2){var coupVal = res2D[1] ;}
                                    else if(typ == 3){var coupVal = res2D[2] ;}
                                    if(typ == 2 || typ == 1){
                                        if($('#tipValueConfirmValueOPay').val() != 0){
                                            var cart = parseFloat($('.totalOnCart').html()).toFixed(2);
                                            var tip = parseFloat($('#tipValueConfirmValueOPay').val()).toFixed(2);
                                            // var points = parseFloat($('.pointsUsedIdFS').html()).toFixed(2);   +(points *0.01)
                                            var beforeTotal =parseFloat(parseFloat(cart)-parseFloat(tip)).toFixed(2);
                                            if(typ == 1){ var offDeal = parseFloat(parseFloat(coupVal*0.01)*beforeTotal).toFixed(2);}
                                            else{ var offDeal = res2D[1]; }

                                            var newOffDeal = roundNrByFiveCentTwoPP(offDeal);
                                            
                                            var afterTotalPre = parseFloat(beforeTotal-newOffDeal).toFixed(2);
                                            var afterTotal = parseFloat(parseFloat(afterTotalPre)+ parseFloat(tip)).toFixed(2);

                                            var afterSubTotal = afterTotalPre;
                                        }else{       
                                            var beforeTotal =parseFloat($('.totalOnCart').html()).toFixed(2);
                                            if(typ == 1){ var offDeal = parseFloat(parseFloat(coupVal*0.01)*beforeTotal).toFixed(2);}
                                            else{ var offDeal = res2D[1]; }

                                            var newOffDeal = roundNrByFiveCentTwoPP(offDeal);

                                            var afterTotal = parseFloat(beforeTotal-newOffDeal).toFixed(2);
                                            var afterSubTotal = afterTotal;
                                        }
                                        $('.totalOnCart').html(afterTotal);
                                        $('.subTotalCart').html(afterSubTotal);
                                    }

                                    $('#codeUsedValueID').val(newOffDeal);
                                    
                                    // Alert the user SUCCESS
                                    if(typ == 1){
                                        $('#couponsSaved').append('<p style="width:100%; font-weight:bold;" class="text-center alert alert-success mb-1">'+res2D[4]+'-> '+res2D[0]+' % => '+newOffDeal+' CHF </p>');
                                        $('#couponsSaved').append('<button class="btn btn-danger" style="width:100%;" id="cancelUsedCouponBtn" onclick="cancelUsedCoupon()"><i class="fa-solid fa-ban"></i> Gutschein stornieren</button>');
                                    }else if(typ == 2){
                                        $('#couponsSaved').append('<p style="width:100%; font-weight:bold;" class="text-center alert alert-success mb-1">'+res2D[4]+'-> '+res2D[1]+' CHF</p>');
                                        $('#couponsSaved').append('<button class="btn btn-danger" style="width:100%;" id="cancelUsedCouponBtn" onclick="cancelUsedCoupon()"><i class="fa-solid fa-ban"></i> Gutschein stornieren</button>');
                                    }else if(typ == 3){
                                        $('#couponsSaved').append('<p style="width:100%; font-weight:bold;" class="text-center alert alert-success mb-1">'+res2D[4]+'-> kostenloses Produkt : '+res2D[2]+' </p>');
                                        $('#couponsSaved').append('<button class="btn btn-danger" style="width:100%;" id="cancelUsedCouponBtn" onclick="cancelUsedCoupon()"><i class="fa-solid fa-ban"></i> Gutschein stornieren</button>');
                                    }
                                    $( "#cuponCheckBtn" ).prop( "disabled", true );
                                    $( "#cuponTypedCart" ).prop( "disabled", true );
                                }

                                function roundNrByFiveCentTwoPP(offD){
                                    return roundInterval(parseFloat(offD), parseFloat(0.05), parseFloat(2), "up");
                                }

                                function roundInterval(number, interval, round, roundType) {
                                
                                    number = number > 999999999999999 ? 999999999999999 : number;
                                    var isMinus = false;
                                    if (number < 0) {
                                        isMinus = true;
                                        number = number * -1;
                                    }
                                    number = parseFloat(numberToString(number, round));
                                    interval = parseFloat(numberToString(interval, round));
                                    var multiplier = roundType == 'up' ? Math.ceil(number / interval) : Math.floor(number / interval);
                                    
                                    number = multiplier * interval;
                                    
                                    number = multiplier * interval;
                                    if (isMinus) {
                                        number = number * -1;
                                    }
                                    return parseFloat(number.toFixed(round)).toFixed(2);
                                }

                                function numberToString(number, dp) {
                                    var format = '#';
                                
                                    
                                    if (dp > 0) {
                                        format += ".";
                                        format += "0000000000".substr(0, dp);
                                    }
                                    else if (dp > 0) {
                                        format += ".";
                                        format += "##########".substr(0, dp);
                                    }
                                    number = number.toString();
                                    var minus = number.substr(0, 1) == '-' ? '-' : '';
                                    var ln = "";
                                    if (number.lastIndexOf("e+") > -1) {
                                        ln = number.substr(0, number.lastIndexOf("e+"));
                                        for (var i = ln.length - 2; i < parseInt(number.substr(number.lastIndexOf("e+") + 1)); i++) {
                                            ln += "0";
                                        }
                                        ln = ln.replace(/[^0-9]/g, '');
                                        number = ln;
                                    }
                                    var tail = format.lastIndexOf('.'), nail = number.lastIndexOf('.');
                                    if (nail < 0 && tail >= 0) {
                                        number = number + "." + format.substr(tail + 1, 1);
                                        nail = number.lastIndexOf('.');
                                    }
                                    tail = tail > -1 && nail > -1 ? format.substr(tail) : '';
                                    var numtail = number.substr(number.indexOf(".") ) ;
                                    if(tail.length > 0 && dp !== undefined && dp > 0) {
                                        tail = tail.substr(0, dp + 1);
                                        var tails = tail.split(''), ntail = "", canPop = true;
                                        for (var i = 1; i < tails.length; i++) {
                                            if ((tails[i] == '#' || tails[i].match(/([0-9])/g)) && numtail.length > i) {
                                                ntail += numtail.substr(i, 1);                    
                                            }
                                            else if(tails[i] == '#') {
                                                ntail += '0';
                                            }
                                            else {
                                                ntail += tails[i];
                                            }
                                        }
                                        var ttail = ntail.split(''), ptail = tail.substr(1).split('');
                                        for(var i = ttail.length - 1; i > -1; i--){
                                            if (ptail[i] == '#' && canPop && (ttail[i] == '0' || ttail[i] == '#')) {
                                                ntail = ntail.substr(0, ntail.length - 1);
                                            }
                                            else {
                                                canPop = false;
                                            }
                                        }
                                        if (ntail.length > 0) {
                                            tail = "." + ntail;
                                        }
                                        else {
                                            tail = "";   
                                        }
                                    }
                                    number = number.replace(/\..*|[^0-9]/g,'').split('');
                                    format = format.replace(/\..*/g,'').split('');
                                    for(var i = format.length - 1; i > -1; i--){
                                        if(format[i] == '#') {
                                            format[i]=number.pop()
                                        }
                                    }
                                    number = minus + number.join('') + format.join('') + tail;
                                    return number;
                                }


                                function cancelUsedCoupon(){
                                    $('#cancelUsedCouponBtn').prop('disabled', true);
                                    $("#totalShowCh").load(location.href+" #totalShowCh>*","");
                                    $('#codeUsedValueID').val(0);
                                    $('#couponUsedId').val(0);
                                    $('#couponUsedIdOnConfNrId').val(0);
                                    $('#couponUsedIdOnConfNrIdCard').val(0);
                                }
                            </script>
                        @elseif(isset($confirmData) && $confirmData['codeUsed'] != 0)
                        <li class="d-flex flex-wrap justify-content-between py-3 border-bottom">
                                <div style="width:100%;" class="d-flex flex-wrap justify-content-between" id="cuponInputDiv">
                                    <div class="input-group mb-3" style="width:100%;" id="cuponInputDiv2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input id="cuponTypedCartAfter" type="text" class="form-control" placeholder="{{- $confirmData['codeUsed']}} CHF" disabled>
                                    </div>
                                </div>
                            </li>
                        @endif

                        <!--  C:\Users\erikt\Desktop\QRORPA _ INFOMANIAK\shportTA_nameLastnameTime.txt (1)-->

                        @if(isset($_SESSION['Res']) && $_SESSION['Res'] == 45)
                            <li class="d-flex flex-wrap justify-content-between pt-3" id="setBowlingLineNrDiv">
                                <div style="width:100%;" class="form-group d-flex flex-wrap justify-content-between">
                                    <label style="width:59.5%; margin-bottom:3px;" class="pt-1" for="bowlingLineNrInp"><strong>Wo bist du am sitzen? <i class="ml-2 mr-2 fa-solid fa-bowling-ball"></i> </strong> </label>
                                    <input style="width:39.5%;" onkeyup="setBowlingLineNr(this.value)" type="text" class="form-control shadow-none" id="bowlingLineNrInp" value="">
                                </div>
                            </li>


                        @endif

                    </ul>
                    
                </div>

                <div class="alert alert-danger text-center" style="width:100%; display:none;" id="bowlingNrError01">
                    <strong>Füllen Sie das Formular "Wo bist du am sitzen?" aus, damit unsere Mitarbeiter Sie leichter finden können</strong>
                </div>
                <div class="alert alert-danger text-center" id="didNotAcceptDataPPAlert" style="display:none; width:100%;">
                    <strong>Stellen Sie sicher, dass Sie die Datenschutzbestimmungen gelesen und akzeptiert haben, bevor Sie fortfahren</strong>
                </div>




















































                <script>
                    //  C:\Users\erikt\Desktop\QRORPA _ INFOMANIAK\shportTA_nameLastnameTime.txt (2)

                    function setBowlingLineNr(bNr){
                        if(bNr == ''){
                            $('#bowlingLineNr').val('-1');
                        }else{ $('#bowlingLineNr').val(bNr); }
                    }

                    function setFreeShots(checkId, proId){
                        if($('#'+checkId).prop("checked") == true){
                            console.log("Checkbox is checked.");
                            $('.freeShots').prop('checked', false);
                            $('#'+checkId).prop('checked', true);

                            $('#freeShotPh1Id').val(proId);
                            $('#freeShotPh2Id').val(proId);
                            $('#freeShotPh2IdOnlinePay').val(proId);

                        }else if($('#'+checkId).prop("checked") == false){
                            $('.freeShots').prop('checked', false);

                            $('#freeShotPh1Id').val(0);
                            $('#freeShotPh2Id').val(0);
                            $('#freeShotPh2IdOnlinePay').val(0);
                        }
                    }
             
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
                            $('#tipValueSendIdOnlinePay').val(TipExt);
                            $('#tipValueCHFSendId').val(parseFloat(TipValue));
                            $('#tipValueCHFSendIdOnlinePay').val(parseFloat(TipValue));

                            $('.tipValueConfirmValueCLA').val(parseFloat(TipValue));
                            $('.tipValueConfirmTitleCLA').val(parseFloat(TipExt));

                            $('.totalOnCart').text(Shuma);

                        }else{
                            $('#bakshishView').text(parseFloat(0).toFixed(2)); 

                            $('#tipValueSendId').val(0);
                            $('#tipValueSendIdOnlinePay').val(0);
                            $('#tipValueCHFSendId').val(parseFloat(0));
                            $('#tipValueCHFSendIdOnlinePay').val(parseFloat(0));

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
                        $('#tipValueSendIdOnlinePay').val();
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
                            $('#tipValueSendIdOnlinePay').val(TipExt);
                            $('#tipValueCHFSendId').val(parseFloat(TipValue));
                            $('#tipValueCHFSendIdOnlinePay').val(parseFloat(TipValue));

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








                <div id="privacyPolicy" class="pr-4 pl-4 mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="dataPrivacyPolicy">
                        <label style="font-size:0.8rem; font-weight:bold;" class="form-check-label" for="dataPrivacyPolicy">
                            Ich habe die <a href="https://qrorpa.ch/storage/Impressum_AGB_Gast_Datenschutz.pdf" download>AGBs und Datenschutzerklärungen</a> gelesen und akzeptiere sie
                        </label>
                    </div>
                </div>
          
                @if($Restrict != 0 && !isset($confirmData))

                    @if( $Restrict == 18)
                        <div class="form-check-inline mb-4 pl-4 pr-4">
                            <label class="form-check-label">
                                <input name="marketingSMS" onclick="ageConfirm(this.id)" id="age18PlusConf" type="checkbox" class="form-check-input" value="">
                                <strong>  Bestätigen Sie, dass Sie über 18 Jahre alt sind. Es wird eine Ausweiskontrolle durchgeführt.</strong>
                            </label>
                        </div>
                    @elseif( $Restrict == 16)
                        <div class="form-check-inline mb-4 pl-4 pr-4">
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
                            $('.ContinueCardPay').prop("disabled", false);
                        }
                        else if($('#'+checkId).prop("checked") == false){
                            $("#cartPart2").load(location.href+" #cartPart2>*","");
                        }
                    }
                </script>






                <div class="container" style="margin-top:-20px;">
                    <div class="row">
                        <div class="col-12">

                        </div>
                    </div>
                    @php
                        $TheRestaurantId = $_SESSION["Res"];
                        $TheTableId = $_SESSION["t"];
                    @endphp
                    <div class="row mt-4 mb-4" id="onlinePayDiv">

                        @if ($theRes->takeawayCashPosOrders == 1)
                            <!-- pay cash and then have the option to convert it to karte (POS) by the staff -->
                            @if( session('success') || (Cookie::has('phNrConfirmed')) )
                                <?php $clPhNrFromCookie = Cookie::get('phNrConfirmed'); ?>
                                <div class="col-12 text-center" id="CashPaymentDiv">
                                    @if ($Restrict != 0)
                                        @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                        <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')" disabled>
                                        @else
                                        <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="payCashPhNrByCookie('{{$clPhNrFromCookie}}','{{$TheRestaurantId}}','{{$TheTableId}}')" disabled>
                                        @endif
                                            <img src="storage/images/CashPayW.PNG" alt="">
                                        </button>
                                    @else
                                        @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                        <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')">
                                        @else
                                        <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="payCashPhNrByCookie('{{$clPhNrFromCookie}}','{{$TheRestaurantId}}','{{$TheTableId}}')">
                                        @endif
                                            <img src="storage/images/CashPayW.PNG" alt="">
                                        </button>
                                    @endif
                                    <p><strong>Bestellung abschliessen</strong></p>
                                </div>
                            @else
                                <!-- pay cash and then have the option to convert it to karte (POS) by the staff -->
                                <div class="col-12 text-center" id="CashPaymentDiv">
                                    @if (isset($confirmData))
                                        <button class="btn btn-default text-center"><img src="storage/images/CashPayC.PNG" alt=""></button>
                                    @else
                                        @if ($Restrict != 0)
                                            @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                            <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')" disabled>
                                            @else
                                            <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="showCash()" disabled>
                                            @endif
                                                <img src="storage/images/CashPayW.PNG" alt="">
                                            </button>
                                        @else
                                            @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                            <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')">
                                            @else
                                            <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="showCash()">
                                            @endif
                                                <img src="storage/images/CashPayW.PNG" alt="">
                                            </button>
                                        @endif
                                    @endif
                                    <p><strong>Bestellung abschliessen</strong></p>
                                </div>
                            @endif
                        @else
                            @if($TheRestaurantId == 45)
                                <div class="col-12 text-center" id="CashPaymentDiv">
                                    @if (isset($confirmData))
                                        <button class="btn btn-default text-center"><img src="storage/images/CashPayC.PNG" alt=""></button>
                                    @else
                                        @if ($Restrict != 0)
                                            @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                            <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')" disabled>
                                            @else
                                            <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="showCash()" disabled>
                                            @endif
                                                <img src="storage/images/CashPayW.PNG" alt="">
                                            </button>
                                        @else
                                            @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                            <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')">
                                            @else
                                            <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="showCash()">
                                            @endif
                                                <img src="storage/images/CashPayW.PNG" alt="">
                                            </button>
                                        @endif
                                    @endif
                                    <p><strong>Bar</strong></p>
                                </div>

                            @else
                                @if($TheRestaurantId == 22 && $TheRestaurantId == 23)
                                    <div class="col-12">
                                        <button id="payOnlineTestPayrexxBtn" style="color:white; background-color:black; border:none; border-radius:200px; width:100%; position:fixed; bottom:35px; left:0px; z-index: 1000;"
                                        class="p-2 text-center" onclick="payOnlineTestPayrexx('{{$TheRestaurantId}}','{{$TheTableId}}')">
                                            <img src="storage/icons/TwintLogo.png" style="width:auto;height:50px;;" alt="">
                                        </button>
                                    </div>
                                @else

                                    @if ($TheRestaurantId == 33 || $TheRestaurantId == 30 || $TheRestaurantId == 34 || $TheRestaurantId == 38 || $TheRestaurantId == 39)
                                        <!-- Genuss Wagen -->
                                        <div class="col-12 text-center">
                                            <script src="https://pay.sandbox.datatrans.com/upp/payment/js/datatrans-2.0.0.js"></script>
                                            @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                            <div style="width:100%;" class="d-flex flex-wrap justify-content-between" 
                                            onclick="onlinePayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')" id="paymentButton">
                                        
                                                <img style="width:20%; height:auto;" src="storage/images/CardPayW.PNG" alt="">
                                                <p style="width:70%; text-align:left; margin:0px; font-size:1.3rem;"><strong>Online Bezahlen</strong></p>
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods011.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods012.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods013.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods014.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods015.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods016.png" alt="">
                                                <hr style="width: 100%;">
                                            </div>
                                            @else
                                            <div style="width:100%;" class="d-flex flex-wrap justify-content-between" onclick="openPhoneNrForTOnlinePay()" id="paymentButton">
                                        
                                                <img style="width:20%; height:auto;" src="storage/images/CardPayW.PNG" alt="">
                                                <p style="width:70%; text-align:left; margin:0px; font-size:1.3rem;"><strong>Online Bezahlen</strong></p>
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods011.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods012.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods013.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods014.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods015.png" alt="">
                                                <img style="width:16%; height:auto;" src="storage/images/paymentMethods016.png" alt="">
                                                <hr style="width: 100%;">
                                            </div>
                                            @endif
                                        
                                        </div>
                                    @else
                                        @if($TheRestaurantId == 34 || $TheRestaurantId == 38 || $TheRestaurantId == 39)
                                        <div class="col-4 text-center" id="CashPaymentDiv">
                                        @else
                                        <div class="col-6 text-center" id="CashPaymentDiv">
                                        @endif
                                            @if (isset($confirmData))
                                                <button class="btn btn-default text-center"><img src="storage/images/CashPayC.PNG" alt=""></button>
                                            @else
                                                @if ($Restrict != 0)
                                                    @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                                    <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')" disabled>
                                                    @else
                                                    <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="showCash()" disabled>
                                                    @endif
                                                        <img src="storage/images/CashPayW.PNG" alt="">
                                                    </button>
                                                @else
                                                    @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                                    <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="cashPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')">
                                                    @else
                                                    <button class="btn btn-default text-center noBorderBtn ContinueCashPay" onclick="showCash()">
                                                    @endif
                                                        <img src="storage/images/CashPayW.PNG" alt="">
                                                    </button>
                                                @endif
                                            @endif
                                            <p><strong>Bar</strong></p>
                                        </div>

                                        <!-- <div class="col-4 text-center" id="CardPaymentDiv">
                                            @if ($Restrict != 0)
                                                @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                                <button class="btn btn-default text-center noBorderBtn ContinueCardPay" onclick="cardPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')" disabled>
                                                @else
                                                <button class="btn btn-default text-center noBorderBtn ContinueCardPay" onclick="showCard()" disabled>
                                                @endif
                                                    <img src="storage/images/CashPayW.PNG" alt="">
                                                </button>
                                            @else
                                                @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                                <button class="btn btn-default text-center noBorderBtn ContinueCardPay" onclick="cardPayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')">
                                                @else
                                                <button class="btn btn-default text-center noBorderBtn ContinueCardPay" onclick="showCard()">
                                                @endif
                                                    <img src="storage/images/CashPayW.PNG" alt="">
                                                </button>
                                            @endif
                                            <p><strong>Karte</strong></p>
                                        </div> -->



                                        @if($TheRestaurantId == 25 || $TheRestaurantId == 24 || $TheRestaurantId == 26)
                                            <!-- Panizza -->
                                            <div class="col-4 text-center">
                                                <button data-toggle="modal" data-target="#onlinePayP" class="btn btn-default noBorderBtn" id="paymentButton" >
                                                    <img src="storage/images/CardPayW.PNG" alt="">
                                                </button>
                                                <p><strong>Online</strong></p>
                                            </div>
                                        @else
                                            @if($TheRestaurantId == 34 || $TheRestaurantId == 38 || $TheRestaurantId == 39)
                                            <div class="col-4 text-center">
                                
                                                <script src="https://pay.sandbox.datatrans.com/upp/payment/js/datatrans-2.0.0.js"></script>
                                                @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                                                <button  onclick="onlinePayTAUseUsrPNr('{{Auth::User()->phoneNr}}','{{$TheRestaurantId}}','{{$TheTableId}}')" class="btn btn-default noBorderBtn" id="paymentButton" >
                                                    <img src="storage/images/CardPayW.PNG" alt="">
                                                </button>
                                                @else
                                                <button  onclick="openPhoneNrForTOnlinePay()" class="btn btn-default noBorderBtn" id="paymentButton" >
                                                    <img src="storage/images/CardPayW.PNG" alt="">
                                                </button>
                                                @endif
                                                <p><strong>Online</strong></p>
                                            </div>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif




                        @if(Auth::check() && Auth::User()->phoneNr != 'empty')
                        <div class="col-12">
                            <div class="jumbotron text-center" style="padding: 5px; margin-bottom:7px;">
                                <p class="lead" style="font-weight: bold;">Ihre registrierte Telefonnummer wird zur Zahlungsbestätigung verwendet!</p>
                            </div>
                        </div>
                        @endif

                    </div>
                    <div style="display: none;" class="row" id="onlinePaymentAlert">
                        <div class="col-12">
                            <img style="width:100%; height:auto;" src="storage/gifs/loading.gif" alt="">
                            <h5 class="text-center"><strong>You have chosen the Online payment method! Please wait a second for the initialization.</strong></h5>
                        </div>
                    </div>
                <!-- End -->
                </div>

                        <script>
                            // Online Res = 22 & =23
                            function payOnlineTestPayrexx(res, t){
                                $('#payOnlineTestPayrexxBtn').html('<img style="width:auto; height:50px;" src="storage/gifs/loading3.gif" alt="">');
                                // window.location.href = '/onlinePayPayrexxQrorpa';
                                $.ajax({
                                    url: '{{ route("onlinePay.payrexxQrorpa") }}',
                                    method: 'post',
                                    data: {
                                        totalOnCart: $('.totalOnCart').html(),
                                        resId: res,
                                        tabId: t,
                                        tipValueConfirmValueOPay: $('#tipValueConfirmValueOPay').val(),
                                        tipValueConfirmTitleOPay: $('#tipValueConfirmTitleOPay').val(),
                                        userPorosiaOPay: $('#userPorosiaOPay').val(),
                                        userPayMOPay: $('#userPayMOPay').val(),
                                        ShumaOPay: $('#ShumaOPay').val(),
                                        codeUsedValueID2: $('#codeUsedValueID2').val(),
                                        freeShotPh2Id: $('#freeShotPh2Id').val(),
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: (res) => {
                                        var res2D = res.split('"');
                                        var res3D = res2D[1].split('\\');
                                        // console.log(res3D[0]);
                                        window.location.href = res3D[0];
                                    },
                                    error: (error) => {
                                        console.log('error');
                                        console.log(error);
                                        // alert('bitte aktualisieren und erneut versuchen!');
                                    }
                                });
                            }

                            // Cash Payment 
                            function showCash() {
                                if($('#theRestaurant').val() == 45 && $('#bowlingLineNr').val() == -1){
                                    if($('#bowlingNrError01').is(':hidden')){ $('#bowlingNrError01').show(100).delay(5000).hide(100); }
                                }else if(!$("#dataPrivacyPolicy").is(':checked')){
                                    if($('#didNotAcceptDataPPAlert').is(':hidden')){ $('#didNotAcceptDataPPAlert').show(50).delay(4500).hide(50); }
                                }else{
                                    var Cash = document.getElementById('switch1');
                                    var CashPay = document.getElementById('CashPay');
                                    $('#onlinePayDiv').remove();
                                    CashPay.style.display = "block";
                                    $.ajax({
                                        url: '{{ route("restorantet.cashPayClick") }}',
                                        method: 'post',
                                        data: {
                                            id: $('#theRestaurant').val(),
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (res) => {
                                        },
                                        error: (error) => {
                                            console.error(error);
                                        }
                                    });
                                    $('#privacyPolicy').remove();
                                    $('#setBowlingLineNrDiv').remove();
                                }
                            }

                            function cashPayTAUseUsrPNr(UsrPNr, resId, tId){
                                if($('#theRestaurant').val() == 45 && $('#bowlingLineNr').val() == -1){
                                    if($('#bowlingNrError01').is(':hidden')){ $('#bowlingNrError01').show(100).delay(5000).hide(100); }
                                }else if(!$("#dataPrivacyPolicy").is(':checked')){
                                    if($('#didNotAcceptDataPPAlert').is(':hidden')){ $('#didNotAcceptDataPPAlert').show(50).delay(4500).hide(50); }
                                }else{
                                    $.ajax({
                                        url: '{{ route("takeaway.cashPayTAUseUserPNumber") }}',
                                        method: 'post',
                                        data: {
                                            phNr: UsrPNr,
                                            res: resId,
                                            t: tId,
                                            tipval: $('#tipValueConfirmValueOPay').val(),
                                            tiptitle: $('#tipValueConfirmTitleOPay').val(),
                                            userPorosiaOPay: $('#userPorosiaOPay').val(),
                                            userPayMOPay: $('#userPayMOPay').val(),
                                            ShumaOPay: $('#ShumaOPay').val(),
                                            codeUsedValueID2: $('#codeUsedValueID').val(),
                                            codeUsedId: $('#couponUsedId').val(),
                                            freeShotPh2Id: $('#freeShotPh1Id').val(),
                                            clName: 'empty',
                                            clLastname: 'empty',
                                            clTime: 'empty',
                                            bowlingNr: $('#bowlingLineNr').val(),
                                            payMethod : 'Barzahlungen',
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (response) => {
                                            location.reload();
                                        },
                                        error: (error) => { console.log(error); }
                                    });
                                }
                            }

                            function payCashPhNrByCookie(UsrPNr, resId, tId){
                                if($('#theRestaurant').val() == 31 && $('#bowlingLineNr').val() == -1){
                                    if($('#bowlingNrError01').is(':hidden')){ $('#bowlingNrError01').show(100).delay(5000).hide(100); }
                                }else if(!$("#dataPrivacyPolicy").is(':checked')){
                                    if($('#didNotAcceptDataPPAlert').is(':hidden')){ $('#didNotAcceptDataPPAlert').show(50).delay(4500).hide(50); }
                                }else{
                                    $.ajax({
                                        url: '{{ route("takeaway.cashPayTAUseUserPNumber") }}',
                                        method: 'post',
                                        data: {
                                            phNr: UsrPNr,
                                            res: resId,
                                            t: tId,
                                            tipval: $('#tipValueConfirmValueOPay').val(),
                                            tiptitle: $('#tipValueConfirmTitleOPay').val(),
                                            userPorosiaOPay: $('#userPorosiaOPay').val(),
                                            userPayMOPay: $('#userPayMOPay').val(),
                                            ShumaOPay: $('#ShumaOPay').val(),
                                            codeUsedValueID2: $('#codeUsedValueID').val(),
                                            codeUsedId: $('#couponUsedId').val(),
                                            freeShotPh2Id: $('#freeShotPh1Id').val(),
                                            clName: 'empty',
                                            clLastname: 'empty',
                                            clTime: 'empty',
                                            bowlingNr: $('#bowlingLineNr').val(),
                                            payMethod : 'Barzahlungen',
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (response) => {
                                            location.reload();
                                        },
                                        error: (error) => { console.log(error); }
                                    });
                                }
                            }

                            function cardPayTAUseUsrPNr(UsrPNr, resId, tId){
                                if($('#theRestaurant').val() == 45 && $('#bowlingLineNr').val() == -1){
                                    if($('#bowlingNrError01').is(':hidden')){ $('#bowlingNrError01').show(100).delay(5000).hide(100); }
                                }else if(!$("#dataPrivacyPolicy").is(':checked')){
                                    if($('#didNotAcceptDataPPAlert').is(':hidden')){ $('#didNotAcceptDataPPAlert').show(50).delay(4500).hide(50); }
                                }else{
                                    $.ajax({
                                        url: '{{ route("takeaway.cashPayTAUseUserPNumber") }}',
                                        method: 'post',
                                        data: {
                                            phNr: UsrPNr,
                                            res: resId,
                                            t: tId,
                                            tipval: $('#tipValueConfirmValueOPay').val(),
                                            tiptitle: $('#tipValueConfirmTitleOPay').val(),
                                            userPorosiaOPay: $('#userPorosiaOPay').val(),
                                            userPayMOPay: $('#userPayMOPay').val(),
                                            ShumaOPay: $('#ShumaOPay').val(),
                                            codeUsedValueID2: $('#codeUsedValueID').val(),
                                            codeUsedId: $('#couponUsedId').val(),
                                            freeShotPh2Id: $('#freeShotPh1Id').val(),
                                            clName: 'empty',
                                            clLastname: 'empty',
                                            clTime: 'empty',
                                            bowlingNr: $('#bowlingLineNr').val(),
                                            payMethod : 'Kartenzahlung',
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (response) => {
                                            location.reload();
                                        },
                                        error: (error) => { console.log(error); }
                                    });
                                }
                            }

                            function showCard(){
                                if($('#theRestaurant').val() == 45 && $('#bowlingLineNr').val() == -1){
                                    if($('#bowlingNrError01').is(':hidden')){ $('#bowlingNrError01').show(100).delay(5000).hide(100); }
                                }else if(!$("#dataPrivacyPolicy").is(':checked')){
                                    if($('#didNotAcceptDataPPAlert').is(':hidden')){ $('#didNotAcceptDataPPAlert').show(50).delay(4500).hide(50); }
                                }else{
                                    var CardPay = document.getElementById('CardPay');
                                    $('#onlinePayDiv').remove();
                                    CardPay.style.display = "block";
                                    $('#privacyPolicy').remove();
                                    $('#setBowlingLineNrDiv').remove();
                                }
                            }

                            function onlinePayTAUseUsrPNr(UsrPNr, resId, tId){
                                if($('#theRestaurant').val() == 45 && $('#bowlingLineNr').val() == -1){
                                    if($('#bowlingNrError01').is(':hidden')){ $('#bowlingNrError01').show(100).delay(5000).hide(100); }
                                }else if(!$("#dataPrivacyPolicy").is(':checked')){
                                    if($('#didNotAcceptDataPPAlert').is(':hidden')){ $('#didNotAcceptDataPPAlert').show(50).delay(4500).hide(50); }
                                }else{
                                    if(resId == 31 || resId == 32 || resId == 33 || resId == 34 || resId == 39){
                                    // if(1 == 6){
                                        $.ajax({
                                            url: '{{ route("onlinePayTOthers313233.onlineTakeawayUseUsrPNumber") }}',
                                            method: 'post',
                                            data: {
                                                phNr: UsrPNr,
                                                res: resId,
                                                t: tId,
                                                tipval: $('#tipValueConfirmValueOPay').val(),
                                                tiptitle: $('#tipValueConfirmTitleOPay').val(),
                                                userPorosiaOPay: $('#userPorosiaOPay').val(),
                                                userPayMOPay: $('#userPayMOPay').val(),
                                                ShumaOPay: $('#ShumaOPay').val(),
                                                codeUsedValueID2: $('#codeUsedValueID').val(),
                                                codeUsedId: $('#couponUsedId').val(),
                                                freeShotPh2Id: $('#freeShotPh1Id').val(),
                                                clName: 'empty',
                                                clLastname: 'empty',
                                                clTime: 'empty',
                                                bowlingNr: $('#bowlingLineNr').val(),
                                                _token: '{{csrf_token()}}'
                                            },
                                            success: (response) => {
                                                if($.trim(response) == 'couponUsed'){
                                                    if($('#phoneNrForTOnlinePayErr08').is(":hidden")){ $('#phoneNrForTOnlinePayErr08').show(50).delay(7500).hide(50); }
                                                }else{
                                                    var dataJ = JSON.stringify(response);
                                                    var dataJ2 = JSON.parse(dataJ);
                                                    window.location.href = dataJ2['body']['RedirectUrl'];
                                                }
                                            },
                                            error: (error) => { console.log(error); }
                                        });
                                    }else{
                                        $.ajax({
                                            url: '{{ route("onlinePayTOthers.onlineTakeawayUseUsrPNumber") }}',
                                            method: 'post',
                                            data: {
                                                phNr: UsrPNr,
                                                res: resId,
                                                t: tId,
                                                tipval: $('#tipValueConfirmValueOPay').val(),
                                                tiptitle: $('#tipValueConfirmTitleOPay').val(),
                                                userPorosiaOPay: $('#userPorosiaOPay').val(),
                                                userPayMOPay: $('#userPayMOPay').val(),
                                                ShumaOPay: $('#ShumaOPay').val(),
                                                codeUsedValueID2: $('#codeUsedValueID').val(),
                                                codeUsedId: $('#couponUsedId').val(),
                                                freeShotPh2Id: $('#freeShotPh1Id').val(),
                                                clName: 'empty',
                                                clLastname: 'empty',
                                                clTime: 'empty',
                                                bowlingNr: $('#bowlingLineNr').val(),
                                                _token: '{{csrf_token()}}'
                                            },
                                            success: (response) => {
                                                if($.trim(response) == 'couponUsed'){
                                                    if($('#phoneNrForTOnlinePayErr08').is(":hidden")){ $('#phoneNrForTOnlinePayErr08').show(50).delay(7500).hide(50); }
                                                }else{
                                                    var dataJ = JSON.stringify(response);
                                                    var dataJ2 = JSON.parse(dataJ);
                                                    window.location.href = dataJ2['body']['RedirectUrl'];
                                                }
                                            },
                                            error: (error) => { console.log(error); }
                                        });
                                    }
                                }
                            }















                            // Online Pay  
                            function openPhoneNrForTOnlinePay(){
                                if($('#theRestaurant').val() == 45 && $('#bowlingLineNr').val() == -1){
                                    if($('#bowlingNrError01').is(':hidden')){ $('#bowlingNrError01').show(100).delay(5000).hide(100); }
                                }else if(!$("#dataPrivacyPolicy").is(':checked')){
                                    if($('#didNotAcceptDataPPAlert').is(':hidden')){ $('#didNotAcceptDataPPAlert').show(50).delay(4500).hide(50); }
                                }else{
                                    $('#phoneNrForTOnlinePay').show(50);
                                    $('#CashPaymentDiv').remove();
                                    $('#onlinePayDiv').remove();
                                    $('#privacyPolicy').remove();
                                    $('#setBowlingLineNrDiv').remove();
                                }
                            }
                            function phoneNrForTOnlinePaySendNr(){
                                let pNr = $('#onlinePPhoNrInputId').val().replace(/ /g,'');

                                if(pNr != ''){
                                    if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                                        pNr = '0'+pNr.toString().slice(3);
                                    }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                                        pNr = '0'+pNr.toString().slice(2);
                                    }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                                        pNr = '0'+pNr.toString().slice(4);
                                    }
                                }

                                if(!$('#onlinePPhoNrInputId').val() || pNr.length < 9 || pNr.length > 10){
                                    if($('#phoneNrForTOnlinePayErr01').is(":hidden")){ $('#phoneNrForTOnlinePayErr01').show(50).delay(4500).hide(50); }
                                }else{
                                    $.ajax({
                                        url: '{{ route("onlinePayTOthers.onlineTakeawayReceivePhNr") }}',
                                        method: 'post',
                                        data: {
                                            phNr: pNr,
                                            codeUsedId: $('#couponUsedId').val(),
                                            _token: '{{csrf_token()}}'
                                        },
                                        success: (response) => {
                                            response = $.trim(response);
                                            if(response == 'falseNR'){
                                                if($('#phoneNrForTOnlinePayErr03').is(":hidden")){ $('#phoneNrForTOnlinePayErr03').show(50).delay(4500).hide(50); }
                                                $('#onlinePPhoNrInputId').val('');

                                            }else if(response == 'couponUsed'){
                                                if($('#phoneNrForTOnlinePayErr08').is(":hidden")){ $('#phoneNrForTOnlinePayErr08').show(50).delay(7500).hide(50); }
                                            }else{
                                                res2D = response.split('||');
                                                $('#phoneNrForTOnlinePayNumberDiv').hide(25);
                                                $('#phoneNrForTOnlinePayPNrBtn').hide(25);
                                                $('#phoneNrForTOnlinePayCodeDiv').show(25);
                                                $('#phoneNrForTOnlinePayCodeInfoText').show(25);
                                                $('#phoneNrForTOnlinePayCodeBtn').show(25);

                                                $('#TAOnlinePayClCodeCreated').val(res2D[1]);
                                                if(pNr == '763270293' || pNr == '0763270293' || pNr == '763251809' || pNr == '0763251809' || pNr == '763459941' || pNr == '0763459941' || pNr == '763469963' || pNr == '0763469963' || pNr == '760000000' || pNr == '0760000000'){
                                                    $('#phoneNrForTOnlinePayCodeDemoP').html('Demo Code :'+res2D[1]);
                                                }else{
                                                    $('#phoneNrForTOnlinePayCodeDemoP').hide(5);
                                                }
                                                $('#onlinePPhoNrInputId').val(res2D[0]);

                                                $('#regPhNrToAcDiv').show(5);

                                                startNrVerifyTimer();
                                            }
                                        },
                                        error: (error) => { console.log(error); }
                                    });
                                }
                            }


                            function sendCodeForTakeawayOnlinePay(resId, tId){
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
                                    if($('#phoneNrForTOnlinePayErr04').is(":hidden")){ $('#phoneNrForTOnlinePayErr04').show(50).delay(4500).hide(50); }
                                }else{
                                    if(resId == 31 || resId == 32 || resId == 33 || resId == 34 || resId == 39){
                                    // if(1 == 6){
                                        if($('#regPhNrToAc').length && $("#regPhNrToAc").is(':checked')){ var regThisNrToAc = 'Yes';
                                        }else{ var regThisNrToAc = 'No'; }
                                        
                                        $.ajax({
                                            url: '{{ route("onlinePayTOthers313233.onlineTakeawayReceiveCode") }}',
                                            method: 'post',
                                            data: {
                                                phNr: phoneNrFCl2,
                                                codeByCl: codeByCl,
                                                codeCreated: $('#TAOnlinePayClCodeCreated').val(),
                                                res: resId,
                                                t: tId,
                                                tipval: $('#tipValueConfirmValueOPay').val(),
                                                tiptitle: $('#tipValueConfirmTitleOPay').val(),
                                                userPorosiaOPay: $('#userPorosiaOPay').val(),
                                                userPayMOPay: $('#userPayMOPay').val(),
                                                ShumaOPay: $('#ShumaOPay').val(),
                                                codeUsedValueID2: $('#codeUsedValueID').val(),
                                                codeUsedId: $('#couponUsedId').val(),
                                                freeShotPh2Id: $('#freeShotPh1Id').val(),
                                                clName: 'empty',
                                                clLastname: 'empty',
                                                clTime: 'empty',
                                                bowlingNr: $('#bowlingLineNr').val(),
                                                regNrToUsr: regThisNrToAc,
                                                _token: '{{csrf_token()}}'
                                            },
                                            success: (response) => {
                                                var dataJ = JSON.stringify(response);
                                                var dataJ2 = JSON.parse(dataJ);
                                                window.location.href = dataJ2['body']['RedirectUrl'];
                                            },
                                            error: (error) => { console.log(error); }
                                        });
                                    }else{
                                        if($('#regPhNrToAc').length && $("#regPhNrToAc").is(':checked')){ var regThisNrToAc = 'Yes';
                                        }else{ var regThisNrToAc = 'No'; }
                                        $.ajax({
                                            url: '{{ route("onlinePayTOthers.onlineTakeawayReceiveCode") }}',
                                            method: 'post',
                                            data: {
                                                phNr: phoneNrFCl2,
                                                codeByCl: codeByCl,
                                                codeCreated: $('#TAOnlinePayClCodeCreated').val(),
                                                res: resId,
                                                t: tId,
                                                tipval: $('#tipValueConfirmValueOPay').val(),
                                                tiptitle: $('#tipValueConfirmTitleOPay').val(),
                                                userPorosiaOPay: $('#userPorosiaOPay').val(),
                                                userPayMOPay: $('#userPayMOPay').val(),
                                                ShumaOPay: $('#ShumaOPay').val(),
                                                codeUsedValueID2: $('#codeUsedValueID').val(),
                                                codeUsedId: $('#couponUsedId').val(),
                                                freeShotPh2Id: $('#freeShotPh1Id').val(),
                                                clName: 'empty',
                                                clLastname: 'empty',
                                                clTime: 'empty',
                                                bowlingNr: $('#bowlingLineNr').val(),
                                                regNrToUsr: regThisNrToAc,
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
                            }













                            var intervalTimerMenu ;
                            function startNrVerifyTimer(){
                                var timerStart = "3:00";
                                $('#phoneNrForTOnlinePayTime').html(timerStart);

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
                                    $('#phoneNrForTOnlinePayTime').html(minutes + ':' + seconds);
                                    timerStart = minutes + ':' + seconds;
                                    if(minutes == 0 && seconds == 0)location.reload();
                                }, 1000);
                            }

                        </script>

                        <div style="width: 100%; display:none !important;" class="container d-flex flex-wrap mb-3 mt-5" id="phoneNrForTOnlinePay">
                            
                            <div class="input-group mb-1" id="phoneNrForTOnlinePayNumberDiv">
                                <span class="input-group-text" id="onlinePPhoNrInput"><i class="fas fa-phone-alt"></i></span>
                                <input type="text" id="onlinePPhoNrInputId" class="form-control shadow-none" placeholder="Telefonnummer" aria-describedby="onlinePPhoNrInput">
                            </div>
                            <input type="hidden" id="TAOnlinePayClPhoneNr" value="0">
                            <button id="phoneNrForTOnlinePayPNrBtn" onclick="phoneNrForTOnlinePaySendNr()" class="btn btn-block btn-dark">Nummer senden</button>

                            <p style="width:100%; font-weight:bold;" class="text-center" id="phoneNrForTOnlinePayCodeDemoP"></p>
                            <p style="margin:0px; padding:0px; display:none;" id="phoneNrForTOnlinePayCodeInfoText"><strong> Sie erhalten einen Code per SMS</strong></p>
                            <div class="input-group mb-1" style="display: none;" id="phoneNrForTOnlinePayCodeDiv">
                                <span class="input-group-text" id="onlineCodeInput"><i class="fas fa-barcode"></i></span>
                                <input type="text" id="onlineCodeInputId" class="form-control shadow-none" placeholder="Code" aria-describedby="onlineCodeInput">
                                <span style="font-weight:bold;" class="pr-2 text-right input-group-text" id="phoneNrForTOnlinePayTime"></span>
                            </div>
                            <input type="hidden" id="TAOnlinePayClCodeCreated" value="0">
                            <button id="phoneNrForTOnlinePayCodeBtn" style="display:none;" onclick="sendCodeForTakeawayOnlinePay('{{$TheRestaurantId}}','{{$TheTableId}}')" class="btn btn-block btn-dark">Code senden</button>
                        
                            @if (Auth::check() && Auth::user()->phoneNr == 'empty' ) 
                                <div class="form-check mt-1" style="display:none;" id="regPhNrToAcDiv">
                                    <input class="form-check-input" type="checkbox" value="" id="regPhNrToAc">
                                    <label class="form-check-label" for="regPhNrToAc" style="font-weight: bold;">
                                        Register this phone number to my account
                                    </label>
                                </div>
                            @endif
                        </div>
                            <div id="phoneNrForTOnlinePayErr01" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                                Bitte geben Sie zuerst eine gültige Telefonnummer ein!
                            </div>
                            <div id="phoneNrForTOnlinePayErr02" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                                Bitte schreiben Sie zuerst den Namen, Nachnamen und die Uhrzeit!
                            </div>
                            <div id="phoneNrForTOnlinePayErr03" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                                Die von Ihnen gesendete Telefonnummer ist nicht akzeptabel
                            </div>
                            <div id="phoneNrForTOnlinePayErr04" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                                Schreiben Sie bitte einen gültigen Code!
                            </div>
                            <div id="phoneNrForTOnlinePayErr05" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                                Der Code ist nicht richtig, bitte versuchen Sie es erneut!
                            </div>
                            <div id="phoneNrForTOnlinePayErr06" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                                Verwenden Sie bitte eine verfügbare Zeit!
                            </div>
                            <div id="phoneNrForTOnlinePayErr07" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                                Dieser Gutschein wurde von Ihnen einmal verwendet!
                            </div>
                            <div id="phoneNrForTOnlinePayErr08" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                                Wir können Ihnen die Wiederverwendung des Gutscheins nicht gestatten. Bitte entfernen Sie ihn und fahren Sie fort
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
                
                            $randCode = rand(111111,999999);
                        ?>
                        <div id="CashPay" class="mt-5" style="display:none;">
                            <div class="container" id="CashPayInputFr">
                                {{Form::open(['action' => 'ProduktController@confNr', 'method' => 'post', 'id' => 'firstStepValidCodeSend']) }}
                                    <div class="input-group mb-3 input-group-sm" style="margin-top:-15px;">
                                        <label for="demo">Handynummer (Wir benötigen Ihre Handynummer, um die Bestellung zu verifizieren):</label>
                                        <div class="input-group-prepend">
                                            <!-- <span class="input-group-text">+41</span> -->
                                        </div>
                                        <input type="number" name="phoneNr" id="onlinePPhoNrInputIdCashPay" placeholder="07x xxx xx xx" class="form-control noBorderbtn" 
                                        style="font-size:16px;">
                                    </div>

                                    {{ Form::hidden('code', $randCode , ['class' => 'form-control']) }}

                                    {{ Form::hidden('tipValueSend', 0 , ['class' => 'form-control', 'id' => 'tipValueSendId']) }}
                                    {{ Form::hidden('tipValueCHFSend', 0 , ['class' => 'form-control', 'id' => 'tipValueCHFSendId']) }}

                                    {{ Form::hidden('pointsUsed', 0 , ['class' => 'form-control', 'id' => 'pointsUsedIdFS']) }}

                                    {{ Form::hidden('couponUsedIdOnConfNr', 0, ['class' => 'form-control', 'id' => 'couponUsedIdOnConfNrId']) }}

                                    @if(isset($_SESSION['t']) && $_SESSION['t'] == 500)
                                        {{ Form::hidden('nameTA01', '' , ['class' => 'form-control', 'id' => 'nameTA01', 'value' => 'empty']) }}
                                        {{ Form::hidden('lastnameTA01', '' , ['class' => 'form-control', 'id' => 'lastnameTA01', 'value' => 'empty']) }}
                                        {{ Form::hidden('timeTA01', '' , ['class' => 'form-control', 'id' => 'timeTA01', 'value' => 'empty']) }}
                                       
                                    @endif

                                    {{ Form::hidden('codeUsedValue', 0 , ['class' => 'form-control', 'id' => 'codeUsedValueID']) }}

                                    {{ Form::hidden('freeShotPh1', 0 , ['class' => 'form-control', 'id' => 'freeShotPh1Id']) }}

                                    <div class="row">
                                        <div class="col-12">
                                            <!-- <button class=""
                                        onclick="payCash('qrorpa','38345234379','123456')">
                                                Pay with Cash</button> -->
                                            @if(isset($_SESSION['t']) && $_SESSION['t'] == 500)
                                                @if($_SESSION['Res'] == 22 && $_SESSION['Res'] == 23)
                                                    {{ Form::submit('Bezahle mit Bargeld', ['class' => 'btn btn-dark rounded-pill py-2 btn-block']) }}
                                                @else
                                                {{ Form::button('Bezahle mit Bargeld', ['class' => 'btn btn-dark rounded-pill py-2 btn-block', 'onclick' => 'isItReadyOrder()']) }}
                                                @endif
                                            @else
                                                {{ Form::submit('Bezahle mit Bargeld', ['class' => 'btn btn-dark rounded-pill py-2 btn-block']) }}
                                            @endif
                                        </div>
                                    </div>
                                {{Form::close() }}
                            </div>
                        </div>

                        <div id="CardPay" class="mt-5" style="display:none;">
                            <div class="container" id="CardPayInputFr">
                                {{Form::open(['action' => 'ProduktController@confNr', 'method' => 'post', 'id' => 'firstStepValidCodeSend']) }}
                                    <div class="input-group mb-3 input-group-sm" style="margin-top:-15px;">
                                        <label for="demo">Handynummer (Wir benötigen Ihre Handynummer, um die Bestellung zu verifizieren):</label>
                                        <div class="input-group-prepend">
                                            <!-- <span class="input-group-text">+41</span> -->
                                        </div>
                                        <input type="number" name="phoneNr" id="onlinePPhoNrInputIdCardPay" placeholder="07x xxx xx xx" class="form-control noBorderbtn" 
                                        style="font-size:16px;">
                                    </div>
                                    {{ Form::hidden('code', $randCode , ['class' => 'form-control']) }}

                                    {{ Form::hidden('tipValueSend', 0 , ['class' => 'form-control', 'id' => 'tipValueSendId']) }}
                                    {{ Form::hidden('tipValueCHFSend', 0 , ['class' => 'form-control', 'id' => 'tipValueCHFSendId']) }}

                                    {{ Form::hidden('pointsUsed', 0 , ['class' => 'form-control', 'id' => 'pointsUsedIdFS']) }}

                                    {{ Form::hidden('couponUsedIdOnConfNr', 0, ['class' => 'form-control', 'id' => 'couponUsedIdOnConfNrIdCard']) }}

                                    @if(isset($_SESSION['t']) && $_SESSION['t'] == 500)
                                        {{ Form::hidden('nameTA01', '' , ['class' => 'form-control', 'id' => 'nameTA01', 'value' => 'empty']) }}
                                        {{ Form::hidden('lastnameTA01', '' , ['class' => 'form-control', 'id' => 'lastnameTA01', 'value' => 'empty']) }}
                                        {{ Form::hidden('timeTA01', '' , ['class' => 'form-control', 'id' => 'timeTA01', 'value' => 'empty']) }}
                                       
                                    @endif

                                    {{ Form::hidden('codeUsedValue', 0 , ['class' => 'form-control', 'id' => 'codeUsedValueID']) }}

                                    {{ Form::hidden('freeShotPh1', 0 , ['class' => 'form-control', 'id' => 'freeShotPh1Id']) }}

                                    <div class="row">
                                        <div class="col-12">
                                            <!-- <button class=""
                                        onclick="payCash('qrorpa','38345234379','123456')">
                                                Pay with Cash</button> -->
                                            @if(isset($_SESSION['t']) && $_SESSION['t'] == 500)
                                                @if($_SESSION['Res'] == 22 && $_SESSION['Res'] == 23)
                                                    {{ Form::submit('Mit Karte bezahlen', ['class' => 'btn btn-dark rounded-pill py-2 btn-block']) }}
                                                @else
                                                {{ Form::button('Mit Karte bezahlen', ['class' => 'btn btn-dark rounded-pill py-2 btn-block', 'onclick' => 'isItReadyOrderCard()']) }}
                                                @endif
                                            @else
                                                {{ Form::submit('Mit Karte bezahlen', ['class' => 'btn btn-dark rounded-pill py-2 btn-block']) }}
                                            @endif
                                        </div>
                                    </div>
                                {{Form::close() }}
                            </div>
                        </div>


                        <input type="hidden" id="onlinePPhoNrInputIdCP" class="form-control" placeholder="Telefonnummer" aria-describedby="onlinePPhoNrInput">

                        <p style="width:100%; font-weight:bold;" class="text-center" id="phoneNrForTOnlinePayCodeDemoPCP"></p>
                        <p style="margin:0px; padding:0px; display:none;" id="phoneNrForTOnlinePayCodeInfoText2"><strong> Sie erhalten einen Code per SMS</strong></p>
                        <div class="input-group mb-1" style="display: none;" id="phoneNrForTOnlinePayCodeDivCP">
                            <span class="input-group-text" id="onlineCodeInputCP"><i class="fas fa-barcode"></i></span>
                            <input type="text" id="onlineCodeInputIdCP" class="form-control shadow-none" placeholder="Code" aria-describedby="onlineCodeInputCP">
                            <span style="font-weight:bold;" class="pr-2 text-right input-group-text" id="phoneNrForTOnlinePayTimeCP"></span>
                        </div>
                        <input type="hidden" id="TAOnlinePayClCodeCreatedCP" value="0">
                        <button id="phoneNrForTOnlinePayCodeBtnCP" style="display:none;" onclick="sendCodeForTakeawayOnlinePayCP('{{$TheRestaurantId}}','{{$TheTableId}}')"
                            class="btn btn-block btn-dark mb-4 shadow-none">Code senden
                        </button>
                        <button id="phoneNrForTOnlinePayCodeBtnCPCard" style="display:none;" onclick="sendCodeForTakeawayOnlinePayCPCard('{{$TheRestaurantId}}','{{$TheTableId}}')"
                            class="btn btn-block btn-dark mb-4 shadow-none">Code senden
                        </button>
               
                        <div id="phoneNrForTOnlinePayErr01CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Bitte geben Sie zuerst eine gültige Telefonnummer ein!
                        </div>
                        <div id="phoneNrForTOnlinePayErr02CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Bitte schreiben Sie zuerst den Namen, Nachnamen und die Uhrzeit!
                        </div>
                        <div id="phoneNrForTOnlinePayErr03CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Die von Ihnen gesendete Telefonnummer ist nicht akzeptabel
                        </div>
                        <div id="phoneNrForTOnlinePayErr04CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Schreiben Sie bitte einen gültigen Code!
                        </div>
                        <div id="phoneNrForTOnlinePayErr05CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Der Code ist nicht richtig, bitte versuchen Sie es erneut!
                        </div>
                        <div id="phoneNrForTOnlinePayErr06CP" style="width: 100%; display:none;" class="mt-1 alert alert-danger text-center">
                            Verwenden Sie bitte eine verfügbare Zeit!
                        </div>












                    <script>
                        function isItReadyOrder(){
                            var pNr = $('#onlinePPhoNrInputIdCashPay').val().replace(/ /g,'');
                            
                            if(pNr != ''){
                                if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                                    pNr = '0'+pNr.toString().slice(3);
                                }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                                    pNr = '0'+pNr.toString().slice(2);
                                }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                                    pNr = '0'+pNr.toString().slice(4);
                                }
                            }
                                                   
                            if(!$('#onlinePPhoNrInputIdCashPay').val() || pNr.length < 9 || pNr.length > 10){
                                if($('#phoneNrForTOnlinePayErr01CP').is(":hidden")){ $('#phoneNrForTOnlinePayErr01CP').show(50).delay(4500).hide(50); }
                            }else{
                                // $('#firstStepValidCodeSend').submit(); 
                                $.ajax({
                                    url: '{{ route("takeaway.sendPhoneNrTACashPay") }}',
                                    method: 'post',
                                    data: {
                                        phNr: pNr,
                                        cUsed: $('#couponUsedIdOnConfNrId').val(),
                                        res: '{{$_SESSION["Res"]}}',
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: (response) => {
                                        response = $.trim(response);
                                        if(response == 'falseNR'){
                                            if($('#phoneNrForTOnlinePayErr03').is(":hidden")){ $('#phoneNrForTOnlinePayErr03').show(50).delay(4500).hide(50); }
                                        }else if(response == 'couponUsedOnce'){
                                            if($('#phoneNrForTOnlinePayErr07').is(":hidden")){ $('#phoneNrForTOnlinePayErr07').show(50).delay(4500).hide(50); }
                                        }else{
                                            res2D = response.split('||');
                                            $('#CashPayInputFr').hide(25);
                                            $('#phoneNrForTOnlinePayCodeDivCP').show(25);
                                            $('#phoneNrForTOnlinePayCodeInfoText2').show(25);
                                            $('#phoneNrForTOnlinePayCodeBtnCP').show(25);

                                            $('#TAOnlinePayClCodeCreatedCP').val(res2D[1]);
                                            if(pNr == '763270293' || pNr == '0763270293' || pNr == '763251809' || pNr == '0763251809' || pNr == '763459941' || pNr == '0763459941' || pNr == '763469963' || pNr == '0763469963' || pNr == '760000000' || pNr == '0760000000'){
                                                $('#phoneNrForTOnlinePayCodeDemoPCP').html('Demo Code :'+res2D[1]);
                                            }else{
                                                $('#phoneNrForTOnlinePayCodeDemoPCP').hide(5);
                                            }
                                            $('#onlinePPhoNrInputIdCP').val(res2D[0]);

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
                            $('#phoneNrForTOnlinePayTimeCP').html(timerStart);
                            intervalTimerMenuCP = setInterval(function() {
                                var timer = timerStart.split(':');
                                //by parsing integer, I avoid all extra string processing
                                var minutes = parseInt(timer[0], 10);
                                var seconds = parseInt(timer[1], 10);
                                --seconds;
                                minutes = (seconds < 0) ? --minutes : minutes;
                                if (minutes < 0) clearInterval(intervalTimerMenuCP);
                                seconds = (seconds < 0) ? 59 : seconds;
                                seconds = (seconds < 10) ? '0' + seconds : seconds;
                                //minutes = (minutes < 10) ?  minutes : minutes;
                                $('#phoneNrForTOnlinePayTimeCP').html(minutes + ':' + seconds);
                                timerStart = minutes + ':' + seconds;
                                if(minutes == 0 && seconds == 0)location.reload();
                            }, 1000);
                        }

                        function sendCodeForTakeawayOnlinePayCP(resId, tNr){
                            $('#phoneNrForTOnlinePayCodeBtnCP').hide(5);
                            let codeByCl = $('#onlineCodeInputIdCP').val();
                            let phoneNrFCl2 = $('#onlinePPhoNrInputIdCP').val().replace(/ /g,'');
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
                                if($('#phoneNrForTOnlinePayErr04CP').is(":hidden")){ $('#phoneNrForTOnlinePayErr04CP').show(50).delay(4500).hide(50); }
                                $('#phoneNrForTOnlinePayCodeBtnCP').show(5);
                            }else{

                                $.ajax({
                                    url: '{{ route("takeaway.closeTheOrder") }}',
                                    method: 'post',
                                    data: {
                                        phNr: phoneNrFCl2,
                                        codeByCl: codeByCl,
                                        codeCreated: $('#TAOnlinePayClCodeCreatedCP').val(),
                                        res: resId,
                                        t: tNr,
                                        tipval: $('#tipValueConfirmValueOPay').val(),
                                        tiptitle: $('#tipValueConfirmTitleOPay').val(),
                                        userPorosiaOPay: $('#userPorosiaOPay').val(),
                                        userPayMOPay: $('#userPayMOPay').val(),
                                        ShumaOPay: $('#ShumaOPay').val(),
                                        codeUsedValueID2: $('#codeUsedValueID').val(),
                                        freeShotPh2Id: $('#freeShotPh1Id').val(),
                                        clName: 'empty',
                                        clLastname: 'empty',
                                        clTime: 'empty',
                                        bowlingNr: $('#bowlingLineNr').val(),
                                        payMethod : 'Barzahlungen',
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: (response) => {
                                        response = $.trim(response);
                                        if(response == 'falseCode'){
                                            if($('#phoneNrForTOnlinePayErr05CP').is(":hidden")){ $('#phoneNrForTOnlinePayErr05CP').show(50).delay(4500).hide(50); }
                                            $('#phoneNrForTOnlinePayCodeBtnCP').show(5);
                                        }else{
                                            location.reload();
                                        }
                                    },
                                    error: (error) => { console.log(error); }
                                });

                            }
                        }






                        function isItReadyOrderCard(){
                            var pNr = $('#onlinePPhoNrInputIdCardPay').val().replace(/ /g,'');
                            
                            if(pNr != ''){
                                if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                                    pNr = '0'+pNr.toString().slice(3);
                                }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                                    pNr = '0'+pNr.toString().slice(2);
                                }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                                    pNr = '0'+pNr.toString().slice(4);
                                }
                            }
                                                   
                            if(!$('#onlinePPhoNrInputIdCardPay').val() || pNr.length < 9 || pNr.length > 10){
                                if($('#phoneNrForTOnlinePayErr01CP').is(":hidden")){ $('#phoneNrForTOnlinePayErr01CP').show(50).delay(4500).hide(50); }
                            }else{
                                // $('#firstStepValidCodeSend').submit(); 
                                $.ajax({
                                    url: '{{ route("takeaway.sendPhoneNrTACashPay") }}',
                                    method: 'post',
                                    data: {
                                        phNr: pNr,
                                        cUsed: $('#couponUsedIdOnConfNrIdCard').val(),
                                        res: '{{$_SESSION["Res"]}}',
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: (response) => {
                                        response = $.trim(response);
                                        if(response == 'falseNR'){
                                            if($('#phoneNrForTOnlinePayErr03').is(":hidden")){ $('#phoneNrForTOnlinePayErr03').show(50).delay(4500).hide(50); }
                                        }else if(response == 'couponUsedOnce'){
                                            if($('#phoneNrForTOnlinePayErr07').is(":hidden")){ $('#phoneNrForTOnlinePayErr07').show(50).delay(4500).hide(50); }
                                        }else{
                                            res2D = response.split('||');
                                            $('#CardPayInputFr').hide(25);
                                            $('#phoneNrForTOnlinePayCodeDivCP').show(25);
                                            $('#phoneNrForTOnlinePayCodeInfoText2').show(25);
                                            $('#phoneNrForTOnlinePayCodeBtnCPCard').show(25);

                                            $('#TAOnlinePayClCodeCreatedCP').val(res2D[1]);
                                            if(pNr == '763270293' || pNr == '0763270293' || pNr == '763251809' || pNr == '0763251809' || pNr == '763459941' || pNr == '0763459941' || pNr == '763469963' || pNr == '0763469963' || pNr == '760000000' || pNr == '0760000000'){
                                                $('#phoneNrForTOnlinePayCodeDemoPCP').html('Demo Code :'+res2D[1]);
                                            }else{
                                                $('#phoneNrForTOnlinePayCodeDemoPCP').hide(5);
                                            }
                                            $('#onlinePPhoNrInputIdCP').val(res2D[0]);

                                            startNrVerifyTimerCP();
                                        }
                                    },
                                    error: (error) => { console.log(error); }
                                });
                            } 
                        }

                        function sendCodeForTakeawayOnlinePayCPCard(resId, tNr){
                            $('#phoneNrForTOnlinePayCodeBtnCP').hide(5);
                            let codeByCl = $('#onlineCodeInputIdCP').val();
                            let phoneNrFCl2 = $('#onlinePPhoNrInputIdCP').val().replace(/ /g,'');
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
                                if($('#phoneNrForTOnlinePayErr04CP').is(":hidden")){ $('#phoneNrForTOnlinePayErr04CP').show(50).delay(4500).hide(50); }
                                $('#phoneNrForTOnlinePayCodeBtnCP').show(5);
                            }else{

                                $.ajax({
                                    url: '{{ route("takeaway.closeTheOrder") }}',
                                    method: 'post',
                                    data: {
                                        phNr: phoneNrFCl2,
                                        codeByCl: codeByCl,
                                        codeCreated: $('#TAOnlinePayClCodeCreatedCP').val(),
                                        res: resId,
                                        t: tNr,
                                        tipval: $('#tipValueConfirmValueOPay').val(),
                                        tiptitle: $('#tipValueConfirmTitleOPay').val(),
                                        userPorosiaOPay: $('#userPorosiaOPay').val(),
                                        userPayMOPay: $('#userPayMOPay').val(),
                                        ShumaOPay: $('#ShumaOPay').val(),
                                        codeUsedValueID2: $('#codeUsedValueID').val(),
                                        freeShotPh2Id: $('#freeShotPh1Id').val(),
                                        clName: 'empty',
                                        clLastname: 'empty',
                                        clTime: 'empty',
                                        bowlingNr: $('#bowlingLineNr').val(),
                                        payMethod : 'Kartenzahlung',
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: (response) => {
                                        response = $.trim(response);
                                        if(response == 'falseCode'){
                                            if($('#phoneNrForTOnlinePayErr05CP').is(":hidden")){ $('#phoneNrForTOnlinePayErr05CP').show(50).delay(4500).hide(50); }
                                            $('#phoneNrForTOnlinePayCodeBtnCP').show(5);
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

                        <div class="container">
                            {{Form::open(['action' => 'ProduktController@confCodeTakeaway', 'method' => 'post', 'id'=>'confCodeTakeaway']) }}

                            <div class="form-group">
                                {{ Form::label('Bitte beachten Sie denn per SMS erhaltenen Code', null , ['class' => 'control-label']) }}
                                {{ Form::number('codeUser','', ['class' => 'form-control']) }}
                            </div>

                            {{ csrf_field() }}

                            {{ Form::hidden('codeOrigjinal', $kodiSend, ['class' => 'form-control']) }}
                            {{ Form::hidden('dateEnd', $dataSend, ['class' => 'form-control']) }}
                            {{ Form::hidden('klientPhoneNr', $klientNrS, ['class' => 'form-control']) }}

                            {{ Form::hidden('couponUsedId', 0, ['class' => 'form-control','id' => 'couponUsedId']) }}

                            @if(isset($confirmData))
                                {{ Form::hidden('tipValueConfirmValue', $confirmData['tipCHF'], ['class' => 'form-control tipValueConfirmValueCLA','id'=>'tipValueConfirmValueOPay']) }}
                                {{ Form::hidden('tipValueConfirmTitle', $confirmData['tipValue'], ['class' => 'form-control tipValueConfirmTitleCLA','id'=>'tipValueConfirmTitleOPay']) }}
                            @else
                                {{ Form::hidden('tipValueConfirmValue', 0, ['class' => 'form-control tipValueConfirmValueCLA','id'=>'tipValueConfirmValueOPay']) }}
                                {{ Form::hidden('tipValueConfirmTitle', 0, ['class' => 'form-control tipValueConfirmTitleCLA','id'=>'tipValueConfirmTitleOPay']) }}
                            @endif

                            {{ Form::hidden('userIdCash', $sendId, ['class' => 'form-control']) }}
                            {{ Form::hidden('userNameCash', $userNameS, ['class' => 'form-control']) }}
                            {{ Form::hidden('userEmailCash', $userEmailS, ['class' => 'form-control']) }}

                            {{ Form::hidden('userPorosia', $porosiaSend, ['class' => 'form-control','id'=>'userPorosiaOPay']) }}
                            {{ Form::hidden('userPayM', 'Cash', ['class' => 'form-control','id'=>'userPayMOPay']) }}
                            {{ Form::hidden('Shuma', Cart::total(), ['class' => 'form-control','id'=>'ShumaOPay']) }}

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



                            @if(isset($_SESSION['t']) && $_SESSION['t'] == 500)
                                {{ Form::hidden('nameTA02', '' , ['class' => 'form-control', 'id' => 'nameTA02', 'value' => 'empty']) }}
                                {{ Form::hidden('lastnameTA02', '' , ['class' => 'form-control', 'id' => 'lastnameTA02', 'value' => 'empty']) }}
                                {{ Form::hidden('timeTA02', 'empty' , ['class' => 'form-control', 'id' => 'timeTA02' , 'value' => 'empty']) }}
                            @else
                                {{ Form::hidden('nameTA02', 'empty' , ['class' => 'form-control', 'id' => 'nameTA02', 'value' => 'empty']) }}
                                {{ Form::hidden('lastnameTA02', 'empty' , ['class' => 'form-control', 'id' => 'lastnameTA02', 'value' => 'empty']) }}
                            @endif




                            {{ Form::hidden('Res', $_SESSION["Res"] , ['class' => 'form-control','id'=>'ResOPay']) }}
                            {{ Form::hidden('t', $_SESSION["t"] , ['class' => 'form-control','id'=>'tOPay']) }}

                        
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
                    <div style="padding-bottom:0px;" class="col-md-12 thanks-area">
                       <h3>Vielen Dank!</h3>
                       <i class="fa fa-check-circle fa-2x" aria-hidden="true" style="padding: 0px"> <span> Bestellung war erfolgreich!</span></i> 
                    </div>

                    <div style="margin:0px; padding:0px;" class="col-md-12" id="trackOrStatusShowDiv">
                        <?php 
                            $trackmo2D = explode('|',Cookie::get('trackMO'));
                            $theOr = Orders::whereDate('created_at', Carbon::today())->where([['Restaurant',$trackmo2D[0]],['shifra',Cookie::get('trackMO')]])->first(); 
                            $trackMOVar = Cookie::get('trackMO');
                        ?>
                        @if ($theOr != NULL)
                            @if ($theOr->statusi == 0)
                                <h1 style="color: rgba(255,193,7,255); font-size: 2rem;"><strong>Status: Vorbereiten</strong></h1>
                                <p style="font-size:0.85rem;">Ihre Bestellung wurde noch nicht von unserem Servicepersonal vorbereitet. Sobald sich der Status Ihrer Bestellung auf <strong style="color: rgba(23,162,184,255);">< Abholbereit ></strong> ändert, können Sie diese abholen</p>
                            @elseif ($theOr->statusi == 1)
                                <h1 style="color: rgba(23,162,184,255); font-size: 2rem;"><strong>Status: Abholbereit</strong></h1>
                                <p style="font-size:0.85rem;">Ihre Bestellung wurde noch nicht von unserem Servicepersonal vorbereitet. Sobald sich der Status Ihrer Bestellung auf <strong style="color: rgba(23,162,184,255);">< Abholbereit ></strong> ändert, können Sie diese abholen</p>
                            @elseif ($theOr->statusi == 2)
                                <h1 style="color: rgba(220,53,69,255); font-size: 2rem;"><strong>Status: Anulliert</strong></h1>
                                <p style="font-size:0.85rem; color:rgba(220,53,69,255);"><strong>Grund: {{$theOr->cancelComm}}</strong></p>
                            @elseif ($theOr->statusi == 3)
                                <h1 style="color: rgba(38,160,66,255); font-size: 2rem;"><strong>Status: Abgeschlossen</strong></h1>
                                <p style="font-size:0.85rem;">Ihre Bestellung wurde erfolgreich abgeschlossen, vielen Dank, dass Sie QRorpa verwenden</p>
                            @endif
                        @endif
                    </div>

                    <!-- C:\Users\erikt\Desktop\QRORPA _ INFOMANIAK\shport_registerGet20%off.txt -->
                    
                    <div class="col-md-12 code-area" id="theProdCodeAlertCart">
                        <h4><strong>Abholcode:</strong></h4>
                        <h2 style="background: #fff; color: #3a3a3a; padding: 10px; box-shadow: brown; border-radius:15px; box-shadow: 2px 1px 9px 4px #464545;">
                            <strong>{{$codeValNow}}</strong>
                        </h2>
                        
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
                @if(isset($_SESSION['Res']) && isset($_SESSION['t']))
                    <a href="/?Res={{$_SESSION['Res']}}&t={{$_SESSION['t']}}" class="btn btn-outline-info btn-block"
                    style="padding:15px; margin-top:20px; color: #ffffff; border: none;  background: #27beae; font-size: 19px;"> Bestellung fortsetzen </a>
                @else
                    <a href="{{url('/')}}" class="btn btn-outline-info btn-block" style="padding:15px; margin-top:20px;"> Bestellung
                    fortsetzen </a>
                @endif
            </div>
        </div>
        <div class="col-lg-4 col-sm-0">
        </div>
    </div>
</div>

@endif






@endsection













@section('extra-js')


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


    function removeOrderCh(rIdFinder, po, rand, toId) {
        $('#deleteCartIt'+rand+'Btn').hide(350);
        $('#deleteCartIt'+rand+'Gif').show(350);
        $.ajax({
            url: '{{ route("cart.destroyTakeaway") }}',
            method: 'delete',
            data: {
                rowId: $('.'+rIdFinder).val(),
                res: $('#theRestaurant').val(),
                t: $('#theTable').val(),
                taborderId: toId,
                _token: '{{csrf_token()}}'
            },
            success: (res) => {
                $('#orderRow' + po).hide(1200);
                $('#allOrders').load('/order #allOrders', function() {
                    $('.AllExtrasOrder').hide();
                    $('#totalShowCh').load('/order #totalShowCh', function() {});
                });
                if(res['reset'] == '1'){ location.reload(); }
            },
            error: (error) => {
                console.log(error);
                alert('Oops! Something went wrong')
            }
        })
    }

    function openAccFromTACart(orId, trMO){
        $('#openAccFromTACartBtn').prop('disabed',true);
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
                                url: '{{ route("saMngUsr.registerTemUser") }}',
                                method: 'post',
                                data: {
                                    theEm: email, 
                                    pass: $('#createAccPass1').val(), 
                                    orId: orId, 
                                    trM: trMO, 
                                    _token: '{{csrf_token()}}' },
                                success: (respo) => {
                                    respo = $.trim(respo);
                                    if(respo == 'orderNull'){
                                        if($('#regFromTACModalErr06').is(":hidden")){ $('#regFromTACModalErr06').show(50).delay(5000).hide(50); }
                                        $('#openAccFromTACartBtn').prop('disabed',false);
                                    }else if(respo == 'usrPlSuccess'){
                                        if($('#regFromTACModalScc01').is(":hidden")){ $('#regFromTACModalScc01').show(50).delay(10000).hide(50); }
                                        $('#openAccFromTACartBtn').prop('disabed',false);
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
</script>
@endsection