<?php

    use App\Restorant;
    use App\TabOrder;
    use App\tabVerificationPNumbers;
?>

<div class="modal" id="hasForGhostModal" style="background-color: rgba(39,190,175.75);" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div style="margin-top:120px; border-radius:20px;" class="modal-content">
            <div class="modal-body text-center d-flex flex-wrap justify-content-between" style="border:none; width:100%; color:white; font-weight:bold;" id="hasForGhostBodyInfo">
                <img src="storage/images/logo_QRorpa.png" style="width: 50%; height:auto; margin-left:25%; margin-right:25%;" alt="">
                <?php
                    $resName = Restorant::findOrFail($_GET['Res'])->emri;
                ?>
                <h3 class="text-center" style="color:rgb(39,190,175); width:100%;"><strong>{{$resName}}</strong></h3>
                <hr style="width: 100%;">
                <p style="color:rgb(72,81,87); width:100%;" class="text-center">
                    <strong>An diesem Tisch befinden sich <br>aktiv Bestellungen</strong>
                </p>
                <!-- Pay -->
                <button style="width: 49%; font-size:12px;" class="btn btn-outline-dark shadow-none" onclick="hasForGhPay()">
                    <strong><i style="margin-bottom: 0px;" class="fas fa-shopping-cart"></i> Warenkorb öffnen</strong>
                </button> 

                 <!-- Order -->
                <button style="width: 49%; font-size:12px;" class="btn btn-outline-dark shadow-none" onclick="hasForGhOrder()" data-dismiss="modal">
                    <strong><i style="margin-bottom: 0px;" class="fas fa-book-open"></i> Menükarte offnen</strong>
                </button>
            </div>
        </div>
    </div>
</div>


    <!-- admin to Client product transfer modal -->
    <div id="payUnpaidProducts" class="modal" tabindex="-1" aria-modal="true" role="dialog">
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
                    
                    <div id="returnGhostError" style="width:90%; margin-left:5%; display:none;" class="p-1 mt-2 alert alert-danger text-center">
                        {{__('others.hello')}}
                    </div>
                    
                    <hr>

                    <p class="text-center" style="color:rgb(39, 190, 175);"><strong>Haben Sie bereits beim Kellner bestellt? Wählen Sie Ihre Produkte aus und fügen Sie sie zum Warenkorb hinzu. Falls nicht, schliessen Sie das Fenster.</strong></p>
                    
                    <?php
                        $hasOnePlusAdPr = False;
                    ?>
                    @foreach (TabOrder::where([['toRes',$theResId],['tableNr',$theTable],['tabCode','!=','0']])->get() as $tabO)
                        @if ( tabVerificationPNumbers::where('tabOrderId',$tabO->id)->first()->phoneNr == '0770000000')
                            <div class="d-flex justify-content-between" style="border-bottom:1px solid rgb(72,81,87); color:rgb(72,81,87);" onclick="payUnSelectThisToPay('{{$tabO->id}}','{{$tabO->OrderQmimi}}')">
                                <i id="payUnpaidSelTick{{$tabO->id}}" style="width:10%; color:rgb(39,190,175);" class="pt-2 far fa-2x fa-circle"></i>
                                <div  style="width:50%;">
                                    <p><strong>{{$tabO->OrderSasia}}X {{$tabO->OrderEmri}}</strong></p>
                                    <p style="margin-top: -15px; font-size:9px;">{{$tabO->OrderPershkrimi}}</p>
                                </div>
                                <div  style="width:20%; font-size:11px;">
                                    @if ($tabO->OrderType != 'empty')
                                        {{explode('||',$tabO->OrderType)[0]}}
                                    @endif
                                </div>
                                <div  style="width:15%;">
                                <p class="pt-2"><strong>{{$tabO->OrderQmimi}} {{__('global.currencyShow')}}</strong></p>
                                </div>
                            </div>
                            <?php
                                $hasOnePlusAdPr = True;
                            ?>
                        @endif
                    @endforeach

                    @if($hasOnePlusAdPr)
                        <div class="pt-2 d-flex justify-content-between" id="payUPProBtnDiv">
                            <button class="btn btn-danger" style="width: 49.5%;" data-dismiss="modal">{{__('others.cancel')}}</button>
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






    <!-- PopUp Ad "Product" Modal  -->
    <div class="modal" id="popUpAd01" style="background-color: rgba(0, 0, 0, 0.65);">
        <div class="modal-dialog">
            <div style="background-color: transparent; margin-top:120px;" class="modal-content">
                <div class="modal-body text-center" style="border:none; width:100%; height:500px;" id="popUpAdBody01">
                   <!-- Content By Server -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="popUpAd02" style="background-color: rgba(0, 0, 0, 0.65);">
        <div class="modal-dialog">
            <div style="background-color: transparent; margin-top:120px;" class="modal-content">
                <div class="modal-body text-center" style="border:none; width:100%; height:500px;" id="popUpAdBody02">
                  
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="popUpAd03" style="background-color: rgba(0, 0, 0, 0.65);">
        <div class="modal-dialog">
            <div style="background-color: transparent; margin-top:120px;" class="modal-content">
                <div class="modal-body text-center" style="border:none; width:100%; height:500px;" id="popUpAdBody03">
                   
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="popUpAd04" style="background-color: rgba(0, 0, 0, 0.65);">
        <div class="modal-dialog">
            <div style="background-color: transparent; margin-top:120px;" class="modal-content">
                <div class="modal-body text-center" style="border:none; width:100%; height:500px;" id="popUpAdBody04">
                   
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="popUpAdInfo" style="background-color: rgba(0, 0, 0, 0.65);">
        <div class="modal-dialog">
            <div style="background-color: transparent; margin-top:120px;" class="modal-content">
                <div class="modal-body text-center" style="border:none; width:100%; height:500px; color:white; font-weight:bold;" id="popUpAdBodyInfo">
                </div>
            </div>
        </div>
    </div>



<script>
    function openProductAd(nr){
        $('#popUpAd01').modal('toggle');
        $('#Prod'+nr).modal('show');
    }

    function openInfoAd(infoTxt){
        $('#popUpAd03').modal('toggle');
        $('#popUpAdBodyInfo').html(infoTxt +  '<button class="pt-5" style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;" type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>');
        $('#popUpAdInfo').modal('show');
    }

    function openCategoryAd(categoryId){
        $('#popUpAd04').modal('toggle');
        showProKat(categoryId);
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#KategoriFoto"+categoryId).offset().top
        }, 2000);
    }

    
</script>

<script>
    var selTabCh = '';

    if($('#numberOfCartContent').val() == 0){
        $.ajax({
            url: '{{ route("restorantet.checkGhostForTable") }}',
            method: 'post',
            data: {
                resId: '{{$_GET["Res"]}}',
                tableNr: '{{$_GET["t"]}}',
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {
                respo = $.trim(respo);
                if(respo == 'hasGhostTb'){
                    // ka produkte per GHOST
                    $('#payUnpaidProducts').modal('toggle');
                    // $('body').removeClass('modal-open');
                
                }else if(respo == 'ghostNoneTb'){
                    // NUK ka produkte per GHOST
                    checkForAds();
                }else{
                    checkForAds();
                }
                // fnErrorFail
                // pnErrorFail
            },
            error: (error) => { console.log(error); }
        });
    }

    function hasForGhPay(){
        $('#hasForGhostModal').modal('toggle');
        $('#payUnpaidProducts').modal('toggle');
        
		$('body').attr('class','modal-open');     
    }

    function payUnSelectThisToPay(tOId){
        if($('#payUnpaidSelectedPr').val() == ''){
            $('#payUnpaidSelectedPr').val(tOId);
        }else{
            $('#payUnpaidSelectedPr').val( $('#payUnpaidSelectedPr').val()+'||'+tOId);
        }
        $('#payUnpaidSelTick'+tOId).attr('class','pt-2 far fa-2x fa-check-circle')
    }

    function adminProdsToCart(res,tNr){
        if($('#payUnpaidSelectedPr').val() == ''){
            $('#payUnpaidProductsError01').show(200).delay(3000).hide(200);
        }else{
            $('#payUPProBtnDiv').html('<img src="storage/gifs/loading2.gif" style="width: 20%; height: auto; margin-left: 40%; margin-right: 40%;" alt="">');
            $.ajax({
                url: '{{ route("cart.registerAdminToClUn") }}',
                method: 'post',
                data: {
                    resId: res,
                    tableNr: tNr,
                    selPro: $('#payUnpaidSelectedPr').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {location.reload();},
                error: (error) => {
                    console.log(error);
                    alert('bitte aktualisieren und erneut versuchen!');
                }
            });
        }
    }

    function sendCodeReturnGhost(){
        let ghostCode = $('#returnGhostCode').val();
        if(ghostCode == ''){
            // empty code
            $('#returnGhostError').html("Schreiben Sie bitte zuerst den Code!");
            $('#returnGhostError').show(200).delay(3000).hide(200);
        }else if(ghostCode.length != 4){
            // invalid code
            $('#returnGhostError').html("der Code wird nicht akzeptiert!");
            $('#returnGhostError').show(200).delay(3000).hide(200);
        }else{
            // send for return
            $.ajax({
                url: '{{ route("cart.registerAdminToClUnFCode") }}',
                method: 'post',
                data: {
                    gCode: ghostCode,
                    res:  $('#theRestaurant').val(),
                    tableNr: $('#theTable').val(),
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    res = res.replace(/\s/g, '');
                    if(res == 'zerroGhostProds'){
                        $('#returnGhostError').html("diesen GhostCart gibt es nicht!");
                        $('#returnGhostError').show(200).delay(3000).hide(200);  
                    }else{
                        location.reload();
                    }
                },
                error: (error) => {
                    console.log(error);
                    alert('bitte aktualisieren und erneut versuchen!');
                }
            });
        }
    }

    function hasForGhOrder(){
        checkForAds();
    }

    function checkForAds(){
        $.ajax({
            url: '{{ route("adsModuleSa.getAdsForMenu") }}',
            method: 'post',
            dataType: 'json',
            data: {
                resI: '{{$_GET["Res"]}}',
                _token: '{{csrf_token()}}'
            },
            success: (respo) => {
                if(respo != 'none' && !$('body').hasClass('modal-open')){
                    var cont = '';
                    $('#popUpAdBody01').html('');
                    $('#popUpAdBody02').html('');
                    $('#popUpAdBody03').html('');
                    $('#popUpAdBody04').html('');

                    if(respo.tipi == 1){
                        cont =  '<img onclick="openProductAd(\''+respo.prodId+'\')" src="storage/restaurantADS/'+respo.foto+'" style="max-width:100%; max-height:100%;" alt="">'+
                                '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                        $('#popUpAdBody01').append(cont);
                        $('#popUpAd01').modal('toggle'); 
                    }else if(respo.tipi == 2){
                        cont = ' <a href="https://'+respo.linku+'" target="_blank">'+
                                    '<img src="storage/restaurantADS/'+respo.foto+'" style="max-width:100%; max-height:100%;" alt="">'+
                                    '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                    'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>'+
                                '</a>';
                        $('#popUpAdBody02').append(cont);
                        $('#popUpAd02').modal('toggle');
                    }else if(respo.tipi == 3){
                        cont = '<img onclick="openInfoAd(\''+respo.informata+'\')" src="storage/restaurantADS/'+respo.foto+'" style=max-width:100%; max-height:100%;" alt="">'+
                                '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                        $('#popUpAdBody03').append(cont);
                        $('#popUpAd03').modal('toggle');
                    }else if(respo.tipi == 4){
                        cont = '<img onclick="openCategoryAd(\''+respo.catId+'\')" src="storage/restaurantADS/'+respo.foto+'" style="max-width:100%; max-height:100%;" alt="">'+
                                '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                        $('#popUpAdBody04').append(cont);
                        $('#popUpAd04').modal('toggle');
                    }
                                         
                }
            },
            error: (error) => {
                console.log(error);
            }
        });

        $.ajax({
            url: '{{ route("adsModuleSa.checkIfResHasRepeat") }}',
            method: 'post',
            dataType: 'json',
            data: {
                resI: '{{$_GET["Res"]}}',
                _token: '{{csrf_token()}}'
            },
            success: (response) => {
                if(response != 'empty'){
                    // Ka repeat per Ad
                    
                    setInterval(function () {
                        // if(!$('#numberVerModalResMenu').hasClass('show') && !$('#numberVerModalResMenuRec').hasClass('show') && !$('#optionsModal').hasClass('show')
                        //     && !$('#callWaiter').hasClass('show')){
                        if(!$('body').hasClass('modal-open') && !$('#callWaiter').hasClass('show') && !$('#chngTable').hasClass('show')){
                            $.ajax({
                                url: '{{ route("adsModuleSa.getAdsForMenuRepeatable") }}',
                                method: 'post',
                                dataType: 'json',
                                data: {
                                    resI: '{{$_GET["Res"]}}',
                                    _token: '{{csrf_token()}}'
                                },
                                success: (respo) => {
                                    if(respo != 'none'){
                                        var cont = '';
                                        $('#popUpAdBody01').html('');
                                        $('#popUpAdBody02').html('');
                                        $('#popUpAdBody03').html('');
                                        $('#popUpAdBody04').html('');

                                        if(respo.tipi == 1){
                                            cont =  '<img onclick="openProductAd(\''+respo.prodId+'\')" src="storage/restaurantADS/'+respo.foto+'" style="width:auto; height:100%;" alt="">'+
                                                    '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                    'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                                            $('#popUpAdBody01').append(cont);
                                            $('#popUpAd01').modal('toggle'); 
                                        }else if(respo.tipi == 2){
                                            cont = ' <a href="https://'+respo.linku+'" target="_blank">'+
                                                        '<img src="storage/restaurantADS/'+respo.foto+'" style="width:auto; height:100%;" alt="">'+
                                                        '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                        'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>'+
                                                    '</a>';
                                            $('#popUpAdBody02').append(cont);
                                            $('#popUpAd02').modal('toggle');
                                        }else if(respo.tipi == 3){
                                            cont = '<img onclick="openInfoAd(\''+respo.informata+'\')" src="storage/restaurantADS/'+respo.foto+'" style="width:auto; height:100%;" alt="">'+
                                                    '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                    'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                                            $('#popUpAdBody03').append(cont);
                                            $('#popUpAd03').modal('toggle');
                                        }else if(respo.tipi == 4){
                                            cont = '<img onclick="openCategoryAd(\''+respo.catId+'\')" src="storage/restaurantADS/'+respo.foto+'" style="max-width:100%; max-height:100%;" alt="">'+
                                                    '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                    'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                                            $('#popUpAdBody04').append(cont);
                                            $('#popUpAd04').modal('toggle');
                                        }
                                                            
                                    }
                                    // $("#freeProElements").load(location.href+" #freeProElements>*","");
                                },
                                error: (error) => {
                                    console.log(error);
                                }
                            });
                        }

                    }, parseInt(parseInt(response.forSec) * parseInt(1000)))

                }else{
                    // console.log('ska');
                }
            },
            error: (error) => {console.log(error); }
        });
    }
</script>