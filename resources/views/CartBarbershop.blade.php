@extends('layouts.appOrdersBar')



<!-- 1300 kodi -->


<?php

use App\Barbershop;

    use App\Piket;
    use App\FreeProducts;
    use App\Produktet;
    use App\barbershopCupons;
    use App\TakeawaySchedule;
    use App\BarbershopWorker;
    use App\BarbershopWorkerTerminet;
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION['Bar'])){
        $theBar = Barbershop::find($_SESSION['Bar']);
        $theBarId = $_SESSION['Bar'];
    }
        
    
?>
   @if(isset($_SESSION['Bar']))
        <input type="hidden" value="{{$_SESSION['Bar']}}" id="theBar">
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
        input, select { font-size: 100%; }
    </style>


@section('content')
<div id="allOrders">
    @if(Cart::count() > 0)

        <div class="pb-5">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-lg-8 p-3 bg-white rounded shadow-sm mb-5"> <!-- Cart Pjesa 01  -->
                        <!-- Shopping cart table -->
                        <div class="table-responsive">
                            <div class="col-sm-12 col-md-12 col-lg-12 pb-2 text-center d-flex">
                                <h5 class="color-qrorpa pt-2" style="font-weight:bold; width:50%;"><span id="cartCountCh">{{Cart::count()}}</span> {{__('others.services')}} </h5>
                                @if(isset($_SESSION["Bar"]))
                                    <span class="color-qrorpa text-center" style="font-size:22px; width:50%; font-weight:bold;">{{Barbershop::find($_SESSION["Bar"])->emri}}</span>
                                @else
                                    <span class="color-qrorpa text-center" style="font-size:22px; width:50%; font-weight:bold;">Kein Restaurant</span>
                                @endif
                            </div>
                            @if(isset($_GET['student']))
                                <div class="col-sm-12 col-md-12 col-lg-12 pb-2 text-center " style="color: red; font-size:17px;">
                                    <strong>Diese Reservierungen müssen für den Studentenrabatt im Friseursalon überprüft werden!</strong>
                                </div>
                            @elseif(isset($confirmData) && $confirmData['studentStatus'] == 1)
                                <div class="col-sm-12 col-md-12 col-lg-12 pb-2 text-center " style="color: red; font-size:17px;">
                                    <strong>Diese Reservierungen müssen für den Studentenrabatt im Friseursalon überprüft werden!</strong>
                                </div>
                            @endif
                            <hr>

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
                            <div class="d-flex justify-content-between flex-wrap" id="terminetAllCart">
                                @foreach(Cart::content() as $item)
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
                                        @if($item->options->type != '')
                                            {{explode('||',$item->options->type)[1] }} 
                                        @endif
                                        <p style="color:rgb(72,81,87); margin-top:-20px;">{{$item->options->koment}}</p>
                                        <p style="color:rgb(72,81,87); margin-top:-20px;" ><strong>{{$item->options->timeNeed}}</strong> minuten</p>
                                    </div>

                                    <div style="width:25%" class="mt-1">
                                        <strong> 
                                            <span id="setPrice{{$item->rowId}}">{{sprintf('%01.2f', $item->price)}}</span>  <sup>CHF</sup><br>
                                            @if($item->qty > 1)
                                                <span id="setPriceSasiaAll{{$ExtraStephh}}">{{ sprintf('%01.2f', $item->price * $item->qty)}}</span>   <sup>CHF</sup>
                                            @endif
                                        </strong>
                                    </div>

                                    <div style="width:15%">
                                        <div class="form-group">
                                            <input type="hidden" value="{{$item->qty}}">
                                            <select style="text-align-last:center; border:none; font-size:16px;" class="form-control quantity mt-2"
                                                onchange="updateQTY(this.id,'{{$item->rowId}}')"
                                                id="drop{{$item->id}}{{$item->rowId}}" data-id="{{ $item->rowId }}">
                                                @for($i = 1; $i <= 9; $i++) 
                                                    <option value='{{$i}}'{{ $item->qty == $i ? 'selected' : '' }}>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div style="width:15%" class="text-right">
                                        <button type="button" onclick="removeTermin('{{$item->rowId}}')"
                                            class="btn btn-default">
                                            <i class="far fa-trash-alt fa-2x mt-1"></i>
                                        </button>
                                    </div>

                                    <div style="width:100%; border:1px solid rgb(39,190,175,0.3); border-radius:20px; margin-top:-10px;" class="d-flex ">
                                        <p style="width:33%; color:rgb(72,81,87); font-weight:bold; padding-bottom:-10px;" class="text-center pt-1">
                                            {{$item->options->workerDate}}
                                        </p>
                                        <p style="width:33%; color:rgb(72,81,87); font-weight:bold; padding-bottom:-10px;" class="text-center pt-1">
                                            {{BarbershopWorker::find($item->options->worker)->emri}}
                                        </p>
                                        <p style="width:33%; color:rgb(72,81,87); font-weight:bold; padding-bottom:-10px;" class="text-center pt-1">
                                            Von: {{BarbershopWorkerTerminet::find($item->options->workerTer)->startT}}
                                        </p>
                                    </div>
        
                                    @if($item->options->extra != '' && count(explode('--0--',$item->options->extra)) > 0)
                                        <button class="btn btn-outline-dark mb-2 mt-1" style="width:100%;" onclick="showExtraCart()" >Extras zeigen</button>
                                    @endif
                                    <hr style="width:100%;">

                                @endforeach
                            </div>

                        </div>
                    </div> <!-- End Cart Pjesa 01  -->


















                    
                    <div class="col-lg-4 p-3 bg-white rounded shadow-sm mb-5"> <!-- Cart Pjesa 01  -->
                        <div>
                            <h5 class="color-qrorpa">Kaffee fur Serviceteam</h5>
                        </div>

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

                        <div class="d-flex justify-content-between flex-wrap" style="margin-top:-5px;" id="theBakshishDivAll">
                            <?php
                                if(isset($confirmData)){
                                  
                                    
                                    if($confirmData['tipValue'] == '0.5'){
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','0.5')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>50</strong> <sup>Rp</sup></button>    <?php
                                    }else{
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','0.5')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>50</strong> <sup>Rp</sup></button>   <?php
                                    }

                                    if($confirmData['tipValue'] == '1'){
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','1')" id="tipWaiter1" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>1</strong> <sup>CHF</sup></button>    <?php
                                    }else{
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','1')" id="tipWaiter1" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>1</strong> <sup>CHF</sup></button>   <?php
                                    }

                                    if($confirmData['tipValue'] == '2'){
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','2')" id="tipWaiter2" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>2</strong> <sup>CHF</sup></button>    <?php
                                    }else{
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','2')" id="tipWaiter2" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>2</strong> <sup>CHF</sup></button>   <?php
                                    }

                                    if($confirmData['tipValue'] == '5'){
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','5')" id="tipWaiter5" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>5</strong> <sup>CHF</sup></button>    <?php
                                    }else{
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','5')" id="tipWaiter5" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>5</strong> <sup>CHF</sup></button>   <?php
                                    }

                                    if($confirmData['tipValue'] == '10'){
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','10')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn selectedTip waiterTipBtns mt-2"><strong>10</strong> <sup>CHF</sup></button>    <?php
                                    }else{
                                        ?> <button onclick="setTip(this.id,'{{cart::total()}}','10')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>10</strong> <sup>CHF</sup></button>   <?php
                                    }
                                    ?>
                                    <button id="tipWaiterCancel" onclick="setTip(this.id,'{{cart::total()}}','0')" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"> Stornieren </button>
                                    <?php
                                    if($confirmData['tipValue'] == 'other'){
                                        ?> 
                                        <button id="tipWaiterCos" style="width:48%;" type="button" class="btn selectedTip waiterTipBtns mt-2">
                                            <input value="{{$confirmData['tipCHF']}}" step="0.5" min="0" id="tipWaiterCosVal" type="number" onkeyup="setTip(this.id,'{{cart::total()}}',this.value)"style="width:70%; border:none;" placeholder="andere"> <sup>CHF</sup>
                                        </button>
                                        <?php
                                    }else{
                                        ?> 
                                        <button id="tipWaiterCos" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2">
                                        <input step="0.5" min="0" id="tipWaiterCosVal" type="number" onkeyup="setTip(this.id,'{{cart::total()}}',this.value)"style="width:70%; border:none;" placeholder="andere"> <sup>CHF</sup>
                                    </button>
                                    <?php
                                    }
                                

                                }else{
                                    ?>
                                    <button onclick="setTip(this.id,'{{cart::total()}}','0.5')" id="tipWaiter50" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>50</strong> <sup>Rp</sup></button>
                                    <button onclick="setTip(this.id,'{{cart::total()}}','1')" id="tipWaiter1" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>1</strong> <sup>CHF</sup></button>
                                    <button onclick="setTip(this.id,'{{cart::total()}}','2')" id="tipWaiter2" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>2</strong> <sup>CHF</sup></button>
                                    <button onclick="setTip(this.id,'{{cart::total()}}','5')" id="tipWaiter5" style="width:19%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>5</strong> <sup>CHF</sup></button>
                                    <button onclick="setTip(this.id,'{{cart::total()}}','10')" id="tipWaiter10" style="width:19.2%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"><strong>10</strong> <sup>CHF</sup></button>
                                    <button id="tipWaiterCancel" onclick="setTip(this.id,'{{cart::total()}}','0')" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2"> Stornieren </button>
                                    <button id="tipWaiterCos" style="width:48%;" type="button" class="btn btn-outline-dark waiterTipBtns mt-2">
                                        <input step="0.5" min="0" id="tipWaiterCosVal" type="number" onkeyup="setTip(this.id,'{{cart::total()}}',this.value)"style="width:70%; border:none;" placeholder="andere"> <sup>CHF</sup>
                                    </button>
                                    <?php
                                }
                            ?>
                        </div>

                        






































                        <div class="pr-4 pl-4 pb-2">
                            <ul class="list-unstyled mb-4" id="totalShowCh">
                                <?php
                                    $cartTot = number_format((float)Cart::total(), 2, '.', '');
                                    $TVSH =  number_format( $cartTot *0.081 , 2, '.', '');
                                    if(isset($confirmData)){
                                        $newTot = $cartTot+$confirmData['tipValue']-$confirmData['codeUsed'];
                                        $TVSH =  number_format( ($cartTot -$confirmData['codeUsed'])*0.081 , 2, '.', '');
                                    }
                                ?>
                                <li class="d-flex justify-content-between py-3 border-bottom">
                                    <strong class="text-muted">Zwischensumme </strong>
                                    <strong><span id="subTotalCart">
                                        @if(isset($confirmData)) 
                                            {{ $newTot - $TVSH }} 
                                        @else
                                            {{ $cartTot - $TVSH }}
                                        @endif
                                    </span>  CHF </strong>
                                </li>
                                <li class="d-flex justify-content-between py-3 border-bottom">
                                    <strong class="text-muted">MwSt 8.10% </strong>
                                    <strong><span id="tvshCart">
                                        {{ $TVSH }}
                                    </span>  CHF </strong>
                                </li>
                                <li class="d-flex justify-content-between py-3 border-bottom">
                                    <strong class="text-muted">Kellner Trinkgeld</strong>
                                    <strong><span id="bakshish"> 
                                        <?php
                                        if(!isset($confirmData)){ echo 0; }else{ echo $confirmData['tipValue']; }
                                        ?>
                                    </span>  CHF </strong>
                                </li>
                                <li class="d-flex justify-content-between py-3 border-bottom">
                                    <strong class="text-muted">Gesamt </strong>
                                    <strong style="font-size:20px;"><span id="Total">
                                            @if(!isset($confirmData)) 
                                                {{$cartTot}} 
                                            @else
                                                {{$newTot}}
                                            @endif
                                    </span>  CHF </strong>
                                </li>

                                

                                @if(barbershopCupons::where('toBar', $theBarId)->get()->count() > 0 && !isset($confirmData))
                                    <li class="d-flex flex-wrap justify-content-between py-3 border-bottom">
                                        <button id="cuponInputStarter" style="width:100%" class="btn btn-outline-default" onclick="showCuponInput()">
                                        <i class="fas fa-xl fa-barcode pr-5"></i> Gutschein verwenden</button>

                                        <div style="width:100%; display:none;" class="d-flex flex-wrap justify-content-between" id="cuponInputDiv">
                                            <div class="input-group mb-3" style="width:75%; display:none;" id="cuponInputDiv2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                </div>
                                                <input id="cuponTypedCart" type="text" class="form-control" placeholder="Code">
                                            </div>
                                            <button style="width:22%; height:38px; display:none;" class="btn btn-outline-primary" onclick="checkCupon('{{$theBarId}}')"
                                            id="cuponCheckBtn">Check</button>
                                            <p style="display:none; width:100%;" class=" alert alert-danger" id="cuponOffError">Gutschein nicht verfügbar!</p>
                                            <p style="display:none; width:100%;" class=" alert alert-success" id="cuponOffOK">
                                                Gutschein verfügbar! ( <span id="cuponOffOKText"></span>  )</p>
                                        </div>
                                    
                                    </li>

                                    <script>
                                        function showCuponInput(){
                                            $('#cuponInputStarter').hide(200);
                                            $('#cuponInputDiv').show(200);
                                            $('#cuponInputDiv2').show(200);
                                            $('#cuponCheckBtn').show(200);
                                        }
                                        function checkCupon(barId){
                                            $.ajax({
                                                url: '{{ route("cupons.checkCuponBar") }}',
                                                method: 'post',
                                                data: {
                                                    bar: barId,
                                                    code: $('#cuponTypedCart').val(),
                                                    _token: '{{csrf_token()}}'
                                                },
                                                success: (res) => {
                                                    res = res.replace(/\s/g, '');
                                                    // alert(parseFloat($('.totalOnCart').html()));
                                                    // $("#freeProElements").load(location.href+" #freeProElements>*","");
                                                    if(res != 'no'){
                                                        $( "#cuponCheckBtn" ).prop( "disabled", true );
                                                        $( "#cuponTypedCart" ).prop( "disabled", true );
                                                        if($('#bakshish').html() != 0){
                                                            var beforeTotal =parseFloat( parseFloat($('#Total').html()) - parseFloat($('#bakshish').html()));
                                                            var offDeal = parseFloat(parseFloat(res*0.01)*beforeTotal);
                                                            var afterTotalPre = parseFloat(beforeTotal-offDeal).toFixed(2);
                                                            var afterTotal = parseFloat(parseFloat(afterTotalPre)+ parseFloat($('#bakshish').html()));

                                                            var tvsh = parseFloat(afterTotalPre * 0.081).toFixed(2);
                                                            var afterSubTotal = afterTotalPre - tvsh;
                                                        }else{
                                                            var beforeTotal =parseFloat($('#Total').html());
                                                            var offDeal = parseFloat(parseFloat(res*0.01)*beforeTotal);
                                                            var afterTotal = parseFloat(beforeTotal-offDeal).toFixed(2);

                                                            var tvsh = parseFloat(afterTotal * 0.081).toFixed(2);
                                                            var afterSubTotal = afterTotal - tvsh;
                                                        }


                                                        $('#Total').html(afterTotal);
                                                        $('#tvshSpanCart').html(tvsh);
                                                        $('#subTotalCart').html(afterSubTotal);
                                                        $('#tvshCart').html((parseFloat(afterTotal) * parseFloat(0.081)).toFixed(2));

                                                        $('#codeUsedValueID').val(offDeal);
                                                        $('#codeUsedPerceID').val(res);
                                                        $('#codeUsedValueID2').val(offDeal);
                                                        $('#codeUsedPerceID2').val(res);

                                                        $('#cuponOffOKText').html('-'+res+' % ( -'+offDeal+' CHF )');
                                                        $('#cuponOffOK').show(200);
                                                    
                                                    }else{
                                                        $('#cuponOffError').show(200).delay(3000).hide(200);
                                                    }
                                                    // console.log(res);
                                                },
                                                error: (error) => {
                                                    console.log(error);
                                                    alert('Oops! Something went wrong')
                                                }
                                            });
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

                                @if(!isset($confirmData))
                                    <div class="alert alert-danger" style="display:none;" id="emptyNaLaEm">
                                        <strong> Schreiben Sie bitte Ihren Namen, Nachnamen und E-Mail!</strong>
                                    </div>
                                    <li class="d-flex flex-wrap justify-content-between py-3 border-bottom">
                                        <div class="form-group" style="width:49%">
                                            <label for="usr">Name: </label>
                                            <input type="text" onkeyup="setName(this.value)" class="form-control noBorderBtn" id="usrName">
                                        </div>
                                        <div class="form-group" style="width:49%">
                                            <label for="usr">Nachname: </label>
                                            <input type="text" onkeyup="setLastname(this.value)" class="form-control noBorderBtn" id="usrLastname">
                                        </div>
                                        <div class="form-group" style="width:100%">
                                            <label for="usr">E-Mail: </label>
                                            <input type="text" onkeyup="setEmail(this.value)" class="form-control noBorderBtn" id="usrEmail">
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <script>
                            function setName(sN){ $('#sendName').val(sN);}
                            function setLastname(sLN){ $('#sendLastname').val(sLN);}
                            function setEmail(sE){ $('#sendEmail').val(sE);}
                        </script>

                        @if(session('error'))
                            <div id="errorMsg" class="alert alert-danger text-center">
                                <p><strong>{{session('error')}}</strong></p>
                            </div>
                        @endif





































                        <div class="pr-4 pl-4 pb-2 d-flex flex-wrap">

                            <input type="hidden" id="cartTotalVAR" value="$cartTot">
                        

                            @if(!isset($confirmData))
                                <div class="form-check mt-2" onclick="setdataAccCart()">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" id="dataAccCart" value="">Ich habe die <a href="{{route('firstPage.datenschutz')}}">Datenschutzbestimmungen</a>
                                        zur Kenntnis genommen*
                                    </label>
                                </div>
                           

                                <button style="width:49%;" id="BarzahlungPayment" class="btn text-center" onclick="showCashPay()" disabled>
                                    <i style="color:rgb(39,190,175);" class="fas fa-3x fa-money-bill-wave"></i>
                                    <p style="color:rgb(72,81,87); margin-top:-15px;"><strong>Barzahlung</strong></p>
                                </button>
                                <button style="width:49%;" id="OnlinePayment" class="btn text-center" data-toggle="modal" data-target="#onlinePayP" disabled>
                                    <i style="color:rgb(39,190,175);" class="fab fa-3x fa-cc-visa"></i>
                                    <p style="color:rgb(72,81,87); margin-top:-15px;"><strong>Online</strong></p>
                                </button>

                            @endif
                        </div>

                        <?php
                            $randCode = rand(111111,999999);
                        ?>
                         @if(!isset($confirmData))

                            <!-- Dergimi i numrit per verifikim  -->
                            <div id="cashPayBar" style="display:none;">
                       
                                {{Form::open(['action' => 'BarbershopServiceController@confNrBar', 'method' => 'post', 'id' => 'confNrBarForm']) }}

                                    <input type="hidden" name="sendCode" value="{{$randCode}}">
                                    <input type="hidden" name="bakshishVAR01" id="bakshishVAR01" value="0">
                                    <input type="hidden" name="sendName" id="sendName" value="0">
                                    <input type="hidden" name="sendLastname" id="sendLastname" value="0">
                                    <input type="hidden" name="sendEmail" id="sendEmail" value="0">
                                    @if(isset($_GET['student']))
                                        <input type="hidden" name="studentStat01" id="studentStat01" value="1">
                                    @else
                                        <input type="hidden" name="studentStat01" id="studentStat01" value="0">
                                    @endif
                                    <input type="hidden" name="codeUsedValueID" id="codeUsedValueID" value="0">
                                    <input type="hidden" name="codeUsedPerceID" id="codeUsedPerceID" value="0">

                                    <div class="form-group">
                                        <label for="usr">Handynummer (Wir benötigen Ihre Handynummer, um die Bestellung zu verifizieren):</label>
                                        <input type="text" class="form-control noBorderBtn" name="phoneNr" id="phoneNrForConf" min="9" max="10"
                                            placeholder="07x xxx xx xx" id="usr" required>
                                    </div>
                                    <div class="alert alert-danger" style="display:none;" id="emptyphonenumber">
                                        Bitte schreiben Sie zuerst Ihre Telefonnummer
                                    </div>
                                    <button type="button" onclick="checkBeforeSubConfNrBar()" class="btn btn-dark rounded-pill py-2 btn-block" >Bezahle mit Bargeld</button>

                                {{Form::close() }}
                        
                            </div>
                        @else
                            <!-- Dergoet kodi , pret per me shkru -->
                            <div id="nrVerify">
                                <?php
                                    $dataSend = date('Y-m-d H:i:s',strtotime('+5 minutes',strtotime($confirmData['timeStart'])));
                                ?>
                                {{Form::open(['action' => 'BarbershopServiceController@confCodeBar', 'method' => 'post']) }}
                                    <input type="hidden" name="bakshishVAR02" id="bakshishVAR02" value="{{$confirmData['tipCHF']}}">
                                    <input type="hidden" name="timeCode" id="timeCode" value="{{$confirmData['timeStart'] }}">
                                    <input type="hidden" name="timeCodeEnd" id="timeCodeEnd" value="{{$dataSend }}">
                                    <input type="hidden" name="codeReg" id="codeReg" value="{{$confirmData['code']}}">
                                    <input type="hidden" name="phoneNrCl" id="phoneNrCl" value="{{$confirmData['klientPhone']}}">
                                    <input type="hidden" name="barI" id="barI" value="{{$theBarId}}">

                                    <input type="hidden" name="codeUsedValueID2" id="codeUsedValueID2" value="{{$confirmData['codeUsed']}}">
                                    <input type="hidden" name="codeUsedPerceID2" id="codeUsedPerceID2" value="{{$confirmData['codeUsedPerce']}}">

                                    <input type="hidden" name="clName" id="clName" value="{{$confirmData['userName']}}">
                                    <input type="hidden" name="clLastname" id="clLastname" value="{{$confirmData['userLastname']}}">
                                    <input type="hidden" name="clEmail" id="clEmail" value="{{$confirmData['userEmail']}}">

                                    <input type="hidden" name="studentStat02" id="studentStat02" value="{{$confirmData['studentStatus']}}">

                                    <div class="form-group">
                                        <?php $strClientPhNr = strval($confirmData['klientPhone']) ?>
                                        @if($strClientPhNr == '41763270293')
                                        <label for="usr">Bitte Beachlen Sie Denn Per SMS Erhaltenen Code "5 Minuten" (Code für Demo-Nummern: <strong>{{$confirmData['code']}}</strong>)</label>
                                        @else
                                        <label for="usr">Bitte Beachlen Sie Denn Per SMS Erhaltenen Code "5 Minuten" </label>
                                        @endif
                                        <input type="text" class="form-control noBorderBtn" name="userCode" id="userCode" min="111111" max="999999"
                                            placeholder="xxxxxx" id="usrcode" required>
                                    </div>
                                    <button type="submit" class="btn btn-dark rounded-pill py-2 btn-block" >Bezahle mit Bargeld</button>
                                {{Form::close() }}
                            </div>
                        @endif

                        <script>
                            function checkBeforeSubConfNrBar(){
                                if($('#sendName').val() == '0' || $('#sendLastname').val() == '0' || $('#sendEmail').val() == '0'){
                                    $('#emptyNaLaEm').show(250).delay(3500).hide(250);
                                }else{
                                    $('#confNrBarForm').submit();
                                }
                            }
                        </script>


                    </div>


                 



                        <!-- The Online payment Modal -->
                        <div class="modal" id="onlinePayP">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Online Bezahlung bald verfügbar!</h4>
                                        <button type="button" class="close" data-dismiss="modal"> X </button>
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






















                </div>
            </div>
        </div>
   



    <!-- Else if cart == 0 -->
    @else
        @if(session('success'))
            <div class="alert alert-success text-center p-2 mt-4 mb-4">
                <p><strong>{{session('success')}}</strong></p>
            </div>
        @endif
    @endif<!-- End if Cart > 0  -->
    </div>
















        







                         


    <script>

        // Fshije terminin nga shporta
        function removeTermin(roId){
            $.ajax({
				url: '{{ route("cart.deleteBar") }}',
				method: 'post',
				data: {
					id: roId,
					_token: '{{csrf_token()}}'
				},
				success: () => {
					$("#allOrders").load(location.href+" #allOrders>*","");
				},
				error: (error) => {
					console.log(error);
					alert('Oops! Something went wrong')
				}
			});
        }


        // Percakto Bakshishin / +0 0 ...
        function setTip(btnID, cTot, bakVal){
            if(bakVal >= 0){
                if(bakVal == ''){ bakVal = 0; }
                if($('#codeUsedValueID2').val()){
                    cTot= parseFloat(cTot)- parseFloat($('#codeUsedValueID2').val());
                }else{
                    cTot= parseFloat(cTot)- parseFloat($('#codeUsedValueID').val());
                }

                var newTot = parseFloat(parseFloat(cTot)+parseFloat(bakVal)).toFixed(2);
                $('#bakshish').html(bakVal);
                $('#Total').html(newTot);

                $('#bakshishVAR01').val(bakVal);
                $('#bakshishVAR02').val(bakVal);
            }
        }

        // Pranon data policy 
        function setdataAccCart(){
            $('#BarzahlungPayment').removeAttr('disabled');
            $('#OnlinePayment').removeAttr('disabled');
        }

        // Show cash payment 
        function showCashPay(){
            $('#cashPayBar').show(300);
        }







        function sendConfirmNrBar(code){
            if($('#phoneNrForConf').val() != ''){

                $.ajax({
                    url: '{{ route("barService.confNumberBar") }}',
                    method: 'post',
                    data: {
                        phoneNr: $('#phoneNrForConf').val(),
                        sendCode: code,
                        bakshish: $('#bakshishVAR01').val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {

                    },
                    error: (error) => {
                        console.log(error);
                        alert('bitte aktualisieren und erneut versuchen!');
                    }
                });

            }else{
                $('#emptyphonenumber').show(300).delay(3500).hide(300);
            }
        }
    </script>


        @if(isset($confirmData))
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                    // var x = document.getElementById('nrVerify').position();
                    var x = $("#nrVerify").position(); 
                    window.scrollTo(x.left, x.top+50);
            </script>
        @elseif(session('error'))
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                    // var x = document.getElementById('nrVerify').position();
                    var x = $("#errorMsg").position(); 
                    window.scrollTo(x.left, x.top+50);
            </script>
        @endif


@endsection