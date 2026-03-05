<?php

    use App\Restorant;
    use App\RestaurantWH;
    use App\RestaurantRating;
    use App\resdemoalfa;
    use App\RestaurantCover;
    use App\TableQrcode;
    use App\TableChngReq;
    use App\User;
    use App\TabOrder;
    use App\tablesAccessToWaiters;
    use App\tabVerificationPNumbers;
    use App\Orders;
    

    if(Auth::check() && (Auth::user()->email == 'Briefmarketing@qrorpa.ch' || Auth::user()->email == 'mg_ResData@qrorpa.ch' || Auth::user()->email == 'callagent@qrorpa.ch' || Auth::user()->email == 'callagent01@qrorpa.ch' || Auth::user()->email == 'callagent02@qrorpa.ch' 
    || Auth::user()->email == 'callagent03@qrorpa.ch' || Auth::user()->email == 'callagent04@qrorpa.ch' || Auth::user()->email == 'callagent05@qrorpa.ch' || Auth::user()->email == 'callagent06@qrorpa.ch'
    || Auth::user()->email == 'callagent07@qrorpa.ch' || Auth::user()->email == 'callagent08@qrorpa.ch' || Auth::user()->email == 'callagent09@qrorpa.ch' || Auth::user()->email == 'callagent10@qrorpa.ch')){
        header("Location: ".route('resDemo.indexCRM')."");
        exit(); 
    }

    if(Auth::check() && Auth::user()->role == 54439){
        header("Location: ".route('homeConRegUser')."");
        exit(); 
    }

    if(isset($_GET['Res'])){

        $theTable = $_GET['t'];
        $RWHT = RestaurantWH::where('toRes', $_GET['Res'])->first();

        $thisRestaurantRatings = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->orderBy('updated_at','DESC')->get();
        $thisRestaurantRatingsSum = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->sum('stars');
        $thisRestaurantRaringAverage = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->avg('stars');

        $theResId = $_GET['Res'];

        $thisRestaurantCoverImage = RestaurantCover::where([['res_id', '=', $theResId],['status', '=', '1']])->orWhere('res_id', 0)->orderBy('position')->get();
        $thisRestaurantCover = RestaurantCover::where('res_id' , $theResId)->orWhere('res_id' ,0)->first();

    }
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Back from google login 
    if(isset($_SESSION['Res']) && isset($_SESSION['t']) && isset($_GET['Res']) && $_GET['Res'] == 1){
        header("Location: /?Res=".$_SESSION['Res']."&t=".$_SESSION['t']."");
        exit();    
    }else if(isset($_GET['Res']) && $_GET['Res'] == 1){
        header("Location: /?Res=13&t=".$_SESSION['t']."");
        exit();
    }

    
  
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
                   
   
    // use Cart;
?>
<!DOCTYPE html>
<html lang="de" translate="no">
<head>
  <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link href="{{ asset('css/ratings.css') }}" rel="stylesheet" >

    <!-- swiper library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Lazy Load Library  -->
    <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>

  <!-- <link rel="icon" href="http://example.com/favicon.png"> -->


    @if(isset($_GET['Res']))
        <title>{{Restorant::find($_GET['Res'])->emri}} {{__('layouts.qrorpaSystem')}}</title>
        <link rel="icon" href="storage/ResProfilePic/{{Restorant::find($_GET['Res'])->profilePic}}">
    @else
        <title>{{__('layouts.qrorpaSystem')}}</title>
    @endif

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="https://js.pusher.com/6.0/pusher.min.js"></script>

     <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
     @include('fontawesome')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    @yield('extra-css')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/rightModal.css') }}" rel="stylesheet">


    <!-- Global site tag (gtag.js) - Google Analytics -->
    @if(isset($_GET['Res']) && $_GET['Res']==13)
            <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-35PRJMWDZ2"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-35PRJMWDZ2');
        </script>
    @else
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-173569880-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-173569880-1');
        </script>
    @endif


<style>
    .optionsAnchor{
        color:black;
        text-decoration:none;
        opacity:0.65;
        font-weight: bold;
        font-size:17px;
    }
    .optionsAnchor:hover{
        opacity:0.95;
        text-decoration:none;
        color:black;
        
    }
    .optionsAnchorPh{
        color:black;
        text-decoration:none;
        opacity:0.85;
        font-weight: bold;
        font-size:17px;
        width: 100%;
        display: block;

        padding: 15px 0px 15px 50px;
        background-color: white;
        background-size: cover;
        margin-bottom: 10px;
    }
    .optionsAnchorPh:hover{
        opacity:0.100;
        text-decoration:none;
        color:black;
        
    }

    a.disabled {
        
        cursor: not-allowed;
        pointer-events: none;
    }

    body { font-size: 16px; }
    input, select, textarea { font-size: 100%; }


    /* Style the tab */
    .tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
    background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
    background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
    }

    /* Style the close button */
    .topright {
    float: right;
    cursor: pointer;
    font-size: 28px;
    }

    .topright:hover {color: red;}


    /***** CSS Magic to Highlight Stars on Hover *****/
    a, a:hover, a:active, a:visited, a:focus{
    text-decoration: none !important;
    }
    .clickableTabChngON{
        opacity:1;
    }
    .clickableTabChng{
        opacity:0.65;
    }
    .clickableTabChng:hover{
        opacity:1;
    }

    .waiterCallWa{
        cursor: pointer;
    }

</style>

    <!-- incognitoNotAllowed Modal -->
    <div class="modal mt-5" id="incognitoNotAllowed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border:2px solid rgb(39,190,175); border-radius:20px;">
                <div class="modal-body text-center">
                    <p class="text-center"><strong>Entschuldigen Sie die Unterbrechung, aber wir müssen Sie darüber informieren, dass unsere Plattform den Inkognito-Modus (privat) nicht unterstützt oder funktioniert, also verwenden Sie bitte einen normalen Browser für das ultimative Erlebnis!</strong></p>
                    <!-- <p class="text-center" style="color:red;"><strong>BITTE NICHT BESTELLEN</strong></p> -->
                    <br>
                    <i style="color:rgb(39,190,175);" class="text-center fas fa-3x fa-store-alt-slash"></i>
                </div>
            </div>
        </div>
    </div>

    @if (!isset($_SESSION['browserChecked']))
        <!-- check to see if the client is in INCOGNITO using fingerprintJS  -->
        <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        ?>
        @if(isset($_GET['Res']) && isset($_GET['t']))
            <input type="hidden" id="thisRestaurant" value="{{$_GET['Res']}}">
            <input type="hidden" id="thisTable" value="{{$_GET['t']}}">

            <input type="hidden" id="numberOfCartContent" value="{{count(Cart::content())}}">
            <div id="callFCHIfNotInco">
                @include('layouts.layoutParts.appres_firstCheck')
            </div>
        @endif

        @if(isset($_GET['t']))
            <input type="hidden" id="thisTable2" value="{{$_GET['t']}}">
        @endif

        <!--Duhet me shtu edhe pjesen e hashit apo nenshkrimit digjital ne Production-->
        <?php 
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION["change"] = true;
        ?>
        <script>
            // Initialize the agent at application startup.

            // const fpPromise = import('https://fpcdn.io/v3/cdvk2P4Hl1FCrXwYl9Gn')
            // .then(FingerprintJS => FingerprintJS.load({ apiKey: 'cdvk2P4Hl1FCrXwYl9Gn', region: 'eu' })
            // .then(fp => fp.get({extendedResult: true}))
            // .then(result => {
            //     //check if incognito was detected
            //     // document.getElementById('answer').innerText =result.incognito ? 'Yes' : 'No';
            //     // console.log(result.incognito);
            //     if(result.incognito){
            //         // is incognito
            //         $('#incognitoNotAllowed').modal('show');
                    
            //         $.ajax({
            //             url: '{{ route("browser.browserCheckIncognito") }}',
            //             method: 'post',
            //             data: {_token: '{{csrf_token()}}' },
            //             success: () => {
            //                 // $("#browserCheckDiv").load(location.href+" #browserCheckDiv>*","");
                            
            //             },
            //             error: (error) => { console.log(error); }
            //         });
                    
            //     }else{
            //         // is NOT incognito
            //         $.ajax({
            //             url: '{{ route("browser.browserCheckIncognitoIsNot") }}',
            //             method: 'post',
            //             data: {_token: '{{csrf_token()}}' },
            //             success: () => {
            //                 startRouleteGame();
            //             },
            //             error: (error) => { console.log(error); }
            //         });
            //     }
            // })
            // .catch(err => {
            //     console.error(err);
            // }));

            $.ajax({
                url: '{{ route("browser.browserCheckIncognitoIsNot") }}',
                method: 'post',
                data: {_token: '{{csrf_token()}}' },
                success: () => {
                    startRouleteGame();
                },
                error: (error) => { console.log(error); }
            });
        </script>
      
    
    @elseif(isset($_SESSION['browserChecked']) && $_SESSION['browserChecked'] == 'isNotIncognito')
        @if(isset($_GET['Res']) && isset($_GET['t']))
            <input type="hidden" id="thisRestaurant" value="{{$_GET['Res']}}">
            <input type="hidden" id="thisTable" value="{{$_GET['t']}}">

            <input type="hidden" id="numberOfCartContent" value="{{count(Cart::content())}}">
            @include('layouts.layoutParts.appres_firstCheck2')
           
        @endif

        @if(isset($_GET['t']))
            <input type="hidden" id="thisTable2" value="{{$_GET['t']}}">
        @endif

        <!--Duhet me shtu edhe pjesen e hashit apo nenshkrimit digjital ne Production-->
        <?php 
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION["change"] = true;
        ?>
  
    @elseif(isset($_SESSION['browserChecked']) && $_SESSION['browserChecked'] == 'isIncognito')
        <script>
            $('#incognitoNotAllowed').modal('show');
        </script>
        @if(isset($_GET['Res']) && isset($_GET['t']))
            <input type="hidden" id="thisRestaurant" value="{{$_GET['Res']}}">
            <input type="hidden" id="thisTable" value="{{$_GET['t']}}">

            <input type="hidden" id="numberOfCartContent" value="{{count(Cart::content())}}">
        @endif

        @if(isset($_GET['t']))
            <input type="hidden" id="thisTable2" value="{{$_GET['t']}}">
        @endif

        <!--Duhet me shtu edhe pjesen e hashit apo nenshkrimit digjital ne Production-->
        <?php 
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION["change"] = true;
        ?>
    
    @endif



</head>
<script>
    var waSelected = 0;
</script>




    






    @if (!(Cookie::has('retSessionCK') && Cookie::get('retSessionCK') != 'not' && !isset($_SESSION['phoneNrVerified']) || count(Cart::content()) == 0))

        @if (!isset($_SESSION["cookiesAcpt"]) && Cookie::has('cookiesAcpt') != 1)
            <div style="position:fixed; bottom:0px; left:0px; right:0px; width:100%; z-index:999999; background-color:rgba(212,237,218,255); 
                font-size:0.8rem; display:flex;" class="text-center" id="cookierAcptCl">
        @else
            <div style="position:fixed; bottom:0px; left:0px; right:0px; width:100%; z-index:999999; background-color:rgba(212,237,218,255); 
                font-size:0.8rem; display:none;" class="text-center" id="cookierAcptCl">
        @endif
                <div style="width: 90%; background-color:rgb(72,81,87); color:white;" class="p-1">
                    Um unsere Webseite qrorpa.ch/ für Sie optimal zu gestalten und fortlaufend verbessern zu können, verwenden wir Cookies.
                    Durch die weitere Nutzung der Webseite stimmen Sie der Verwendung von Cookies zu. Weitere Informationen zu Cookies erhalten Sie in unserer 
                    <a href="https://qrorpa.ch/atenschutzbestimmungen">Datenschutzerklärung</a>
                    .
                </div>
                <button class="btn btn-success" style="width: 10%; border-radius:0px;" onclick="clAcptCookieUse()">
                    <i style="padding:0px; margin:0px;" class="fas fa-check"></i>
                </button>
            </div>
            <script>
                function clAcptCookieUse(){
                    $('#cookierAcptCl').attr('style','position:fixed; bottom:0px; left:0px; right:0px; width:100%; z-index:999999; background-color:rgba(212,237,218,255); font-size:0.8rem; display:none;')
                    
                    $.ajax({
                        url: '{{ route("notify.clAcceptsCookie") }}',
                        method: 'post',
                        data: {
                            _token: '{{csrf_token()}}'
                        },
                        success: () => {
                        },
                        error: (error) => { console.log(error); }
                    });
                }
            </script>
    @endif
  














@if(isset($_GET['Res']) && isset($_GET['t']))
    <input type="hidden" value="{{$_GET['Res']}}" id="RestoIdId">
    <input type="hidden" value="{{$_GET['t']}}" id="TableIdId">
@endif


<?php
     if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
<div id="numriNeSession"> 
@if(isset($_SESSION["phoneNrVerified"]))
    <input type="hidden" value="{{$_SESSION['phoneNrVerified']}}" id="verifiedNr007">
@endif
</div>


<!-- Modal -->
<div class="modal" id="adminPayGetReceipt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding-top: 70px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">
                    <strong>{{__('layouts.adminPayGetReceiptTxt01')}} <br></strong>
                </h5>
            </div>
            <div class="modal-body d-flex justify-content-between" >
                <button type="button" style="width: 49%;" class="btn btn-secondary" data-dismiss="modal">{{__('layouts.close')}}</button>
                <form style="width: 49%;" method="POST" action="{{ route('receipt.getReceipt') }}">
                    {{ csrf_field()}}
                    <input type="hidden" value="1" name="orId" id="adminPayGetReceiptOrIdInput">
                    <button type="submit" style="width: 100%;" class="text-center btn btn-outline-success"> {{__('layouts.download')}} .pdf </button>
                </form>
            </div>
        </div>
    </div>
</div>



<div id="soundsMenuDiv">
   
</div>
<script>
    // clNewTab PUSHER

    // + forever loop to check for notifications 
    var thisRestaurant = $('#thisRestaurant').val();
    var thisTable = $('#thisTable2').val();

    var intervalId = window.setInterval(function(){
	
        if($('#verifiedNr007').length){
            var verNrCl = $('#verifiedNr007').val();
        }else{ var verNrCl = 'none'; }
		$.ajax({
			url: '{{ route("notify.checkUnrespondetClient") }}',
			method: 'post',
			data: {
				resId: thisRestaurant,
				tableNr: thisTable,
                phoneNrVerify: verNrCl,
				_token: '{{csrf_token()}}'
			},
			success: (res) => {
                res = $.trim(res);
                if(res != 'none'){
                    var res2D = res.split('||');

                    if(res2D[0] == 'clNewTab'){
                        if(res2D[1] == 'userSuccess'){
                            if(res2D[3] == 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable'){
                                $('#responseTCReqSuccess').html('Der Administrator des Restaurants hat Ihre Übertragung genehmigt. Sie können fortfahren.');
                                $('#responseTCReqSuccess').show(250).delay(3000).hide(250); 
                                $('#invalideCartToResTable2').modal('hide');
                            }else{
                                $('#responseTCReqSuccess').html(res2D[3]);
                                $('#responseTCReqSuccess').show(250).delay(3000).hide(250); 
                                setTimeout(function(){ 
                                    window.location = "/?Res="+thisRestaurant+"&t="+res2D[2];
                                }, 3000);               
                            }
                        }else if(res2D[1] == 'userError'){
                            // navigator.vibrate(1000);
                            if(res2D[3] == 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable'){
                                $('#adminToClientMsg').show(250); 
                                $('#adminToClientMsgText').html('Der Administrator des Restaurants hat Ihre Übertragung nicht genehmigt!');
                                setTimeout(function(){ 
                                    $.ajax({
                                        url: '{{ route("cart.emptyTheCart") }}',
                                        method: 'post',
                                        data: {_token: '{{csrf_token()}}'},
                                        success: () => {location.reload();},
                                        error: (error) => {console.log(error);}
                                    });
                                }, 3000);
                            }else{
                                // navigator.vibrate(1000);
                                const canVibrate = window.navigator.vibrate
                                if (canVibrate) window.navigator.vibrate(1000)
                                $('#adminToClientMsg').show(250); 
                                $('#adminToClientMsgText').html(res2D[3]);
                            }
                        }else if(res2D[1] == 'userMsg'){
                            if($('#verifiedNr007').length && $('#verifiedNr007').val() == res2D[4]){
                                // navigator.vibrate(1000);
                                const canVibrate = window.navigator.vibrate
                                if (canVibrate) window.navigator.vibrate(1000)
                                $('#adminToClientMsg').show(250); 
                                $('#adminToClientMsgText').html(res2D[2]);
                                $('#adminToClientMsgAdmin').val(res2D[3]);
                            }
                        }
                    }else if(res2D[0] == 'addToCartAdmin'){
                        if($('#verifiedNr007').val() == res2D[3]){
                            this.addToCartAdminProcess(res);
                        }
                    }else if(res2D[0] == 'CartMsg'){
                        this.CartMsgProcess(res);

                    }else if(res2D[0] == 'removePaidProduct'){
                        // location.reload();
                        $("#orderprot").load(location.href+" #orderprot>*","");
                        $('#adminPayGetReceipt').modal('show');
                        $('#adminPayGetReceiptOrIdInput').val(res2D[2]);

                    }else if(res2D[0] == 'taOrdStatusChange'){
                        $("#theOrVarDiv").load(location.href+" #theOrVarDiv>*","");
                        console.log('done');

                        $.when(checkOrderValidity2()).done(function(cOVResponse){
                            cOVResponse = $.trim(cOVResponse);
                            var cOVResponse2D = cOVResponse.split('||');

                            if(cOVResponse2D[0] == 'yesShow' && cOVResponse2D[1] < 3){
                                
                                var divOutNewVal2 = '<div style="position: fixed; width:70%; left:15%; top:200px; background-color:rgba(39, 190, 175, 0.9); z-index:9999; color:white; border-radius:25px;" class="text-center pt-4 pb-4" id="theProdCodeAlert">'+
                                                        '<h4><strong>{{__("inc.yourOrderCode")}}</strong></h4>'+
                                                        '<h2><strong>{{  Cookie::has("trackMO") && Cookie::get("trackMO") != "not" ? explode("|",Cookie::get("trackMO"))[1] : "" }}</strong></h2>';
                                                        

                                                    if (cOVResponse2D[1] == 0){
                                divOutNewVal2 +=         '<h1 style="color: rgba(255,193,7,255); font-size: 1.5rem;"><strong>Status: Vorbereiten</strong></h1>'+
                                                        '<p style="font-size:0.75rem;">Ihre Bestellung wurde noch nicht von unserem Servicepersonal vorbereitet. Sobald sich der Status Ihrer Bestellung auf <strong style="color: rgba(23,162,184,255);">< Abholbereit ></strong> ändert, können Sie diese abholen</p>';                          
                                                    }else if(cOVResponse2D[1] == 1){
                                divOutNewVal2 +=         '<h1 style="color: rgba(23,162,184,255); font-size: 1.5rem;"><strong>Status: Abholbereit</strong></h1>'+
                                                        '<p style="font-size:0.75rem;">Ihre Bestellung wurde noch nicht von unserem Servicepersonal vorbereitet. Sobald sich der Status Ihrer Bestellung auf <strong style="color: rgba(23,162,184,255);">< Abholbereit ></strong> ändert, können Sie diese abholen</p>';
                                                    }else if(cOVResponse2D[1] == 2){
                                divOutNewVal2 +=         '<h1 style="color: rgba(220,53,69,255); font-size: 1.5rem;"><strong>Status: Anulliert</strong></h1>'+
                                                        '<p style="font-size:0.85rem; color:rgba(220,53,69,255);"><strong>Grund: '+cOVResponse2D[2]+'</strong></p>';
                                                    }

                                divOutNewVal2 +=      '<h2 style="color:white; font-size:2.2rem;" class="btn" onclick="hideTheProdCodeAlert()"><strong>X</strong></h2>'+
                                                    '</div>'+
                                                    '<input type="hidden" value="{{Cookie::get("trackMO")}}" id="theProdCodeAlertTrackMOVal">';
                                $('#theProdCodeAlertDivOUT').html(divOutNewVal2);
                                console.log(cOVResponse2D[1]);
                            }else{
                                $('#theProdCodeAlertDivOUT').html('');
                            }
                        });
                                

                    }else if(res2D[0] == 'prodStatChange'){    
                        $("#soundsMenuDiv").html('<audio id="soundsMenuAudio" src="storage/sound/swiftBeep.mp3" autoplay="true"></audio>   '); 
                    }else{
                        // $('#theProdCodeAlert').hide(500); 
                    }
                }
			},
			error: (error) => { console.log(error); }
		});
    }, 2000);

    function checkOrderValidity2(){
        return $.ajax({
            url: '{{ route("takeaway.checkTakeawayOrderCodeValidation2") }}',
            method: 'post',
            data: {
                res: $('#checkOrderValidityRES').val(),
                t: '500',
                shif: $('#checkOrderValidityTMO').val(),
                _token: '{{csrf_token()}}'
            },
            success: (response) => { return response; },
            error: (error) => { console.log(error); }
        });
    }

    function addToCartAdminProcess(response){
        var d2d = response.split('||')
        if(thisRestaurant == d2d[1] && thisTable == d2d[2]){
			if($('#verifiedNr007').val() == d2d[3]){
				// add to cart request  +TabOrderId
				$.ajax({
					url: '{{ route("dash.addToCartAdminsNewOrderToMe") }}',
					method: 'post',
					data: {
						tabOId: d2d[4],
						_token: '{{csrf_token()}}'
					},
					success: () => { location.reload();},
					error: (error) => {
						console.log(error);
						// alert($('#pls_refresh_try_again').val());
					}
				});
			}
		}
    }


    function CartMsgProcess(response){
        var FromCart = response.split("||");
        if(thisRestaurant == FromCart[1] && thisTable == FromCart[2]){
            if(FromCart[3] == 1){
                
            }else if(FromCart[3] == 7){
                if($('#verifiedNr007').val() == FromCart[4]){
                    $.ajax({
                        url: '{{ route("Res.DeleteTheCart") }}',
                        method: 'post',
                        data: { _token: '{{csrf_token()}}' },
                        success: () => { location.reload(); },
                        error: (error) => { console.log(error); alert('Oops! Something went wrong') }
                    });
                }else{
                    location.reload();
                }
            }else if(FromCart[3] == 9){
                // alert('we did it');
                $.ajax({
                    url: '{{ route("Res.DeleteTheCart") }}',
                    method: 'post',
                    data: { _token: '{{csrf_token()}}' },
                    success: () => { location.reload(); },
                    error: (error) => { console.log(error); alert('Oops! Something went wrong') }
                });
            }
        }
    }




    //    addToCartAdmin
	
    var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {
    cluster: 'eu'
    });

    
    var channel2 = pusher.subscribe('removeProdsCart');
    channel2.bind('App\\Events\\removePaidProduct', function(data) {
        var dataJ = JSON.stringify(data);
        var dataJ2 = JSON.parse(dataJ);
        var d2d = dataJ2.text.split('||');
        if(d2d[2] == 'a'){
            if($('#verifiedNr007').val() == d2d[0]){
                // remove from cart "Paid" +TabOrderId
                $.ajax({
                    url: '{{ route("dash.removePaidProductCart") }}',
                    method: 'post',
                    data: {
                        tabOId: d2d[1],
                        _token: '{{csrf_token()}}'
                    },
                    success: () => { 
                        location.reload(); 
                    
                    },
                    error: (error) => {
                        console.log(error);
                        alert('bitte aktualisieren und erneut versuchen!');
                    }
                });
            }else if($('#RestoIdId').val() == d2d[3] && $('#TableIdId').val() == d2d[4]){
                location.reload();
            }
        }else if(d2d[2] == 'b'){
            if('0770000000' == d2d[0] && $('#RestoIdId').val() == d2d[3] && $('#TableIdId').val() == d2d[4]){  
                $.ajax({
                    url: '{{ route("dash.removePaidProductCart2") }}',
                    method: 'post',
                    data: {
                        tabOId: d2d[1],
                        _token: '{{csrf_token()}}'
                    },
                    success: () => { location.reload(); },
                    error: (error) => {
                        console.log(error);
                        alert('bitte aktualisieren und erneut versuchen!');
                    }
                });
            }else if($('#verifiedNr007').val() == d2d[0]){
                // remove from cart "Paid" +TabOrderId
                $.ajax({
                    url: '{{ route("dash.removePaidProductCart2") }}',
                    method: 'post',
                    data: {
                        tabOId: d2d[1],
                        _token: '{{csrf_token()}}'
                    },
                    success: () => { location.reload(); },
                    error: (error) => {
                        console.log(error);
                        alert('bitte aktualisieren und erneut versuchen!');
                    }
                });
            }else if($('#RestoIdId').val() == d2d[3] && $('#TableIdId').val() == d2d[4]){
                location.reload();
            }
        }
    });

    var channel = pusher.subscribe('updatingChannel');
    channel.bind('App\\Events\\refreshPage', function(data) {
        var dataJ = JSON.stringify(data);
        var dataJ2 = JSON.parse(dataJ);
        var d2d = dataJ2.text.split('||');
    });

</script>



<!-- check and show the ghostCart cookie return -->
@if (!isset($_SESSION['phoneNrVerified']) && Cookie::has('ghostCartReturn') && Cookie::get('ghostCartReturn') != 'not')

    <!-- The Modal -->
    <div class="modal mt-4" id="ghostCartReturnModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h3 class="modal-title text-center"><strong>{{__('layouts.unpaidGhostCartAlert')}}</strong></h3>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center"><strong>{{__('layouts.youHaveAnUnpaidGhostCart')}} <br> {{Cookie::get('ghostCartReturn')}}<br>,{{__('layouts.wouldYouLikeToComeBack')}}</strong></p>

                    <div class="d-flex flex-wrap justify-content-between">
                        <button style="width:48%; font-weight:bold;" type="button" class="btn btn-outline-dark" data-dismiss="modal"><i class="fas fa-arrow-down"></i> {{__('layouts.shutDown')}}</button>
                        
                        {{Form::open(['action' => 'AdminPanelController@returnGhostCToUser', 'method' => 'post', 'style' => 'width:48%;','id'=>'returnGhostForm']) }}
                            <button style="width:100%; font-weight:bold;" type="submit" class="btn btn-outline-dark"><i class="fas fa-check"></i> {{__('layouts.yes')}}</button>
                            <input type="hidden" name="ghostCartCode" id="ghostCartCode" value="{{Cookie::get('ghostCartReturn')}}">
                        {{Form::close() }}

                        {{Form::open(['action' => 'AdminPanelController@returnGhostCToUserCancel', 'method' => 'post', 'style' => 'width:100%;']) }}
                            <button style="width:100%; font-weight:bold;" type="submit" class="btn btn-outline-dark mt-4"><i class="far fa-times-circle"></i> {{__('layouts.cancelThisOrderPayment')}}</button>
                            <input type="hidden" name="ghostCartCodeCancel" id="ghostCartCodeCancel" value="{{Cookie::get('ghostCartReturn')}}">    
                        {{Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // $('#ghostCartReturnModal').modal('toggle');
        $('#returnGhostForm').submit();
    </script>
@endif













































<body>
    @if(isset($_GET['Res']) && isset($_GET['t']))
        <?php
            $sendResLogin = $_GET['Res'];
            $sendTLogin = $_GET['t'];

            // $GLOBALS["foo"]
            $_SESSION["Res"] = $_GET['Res'];
            $_SESSION["t"] = $_GET['t'];

            unset($_SESSION['Bar']);
        ?>
    @endif





    <!-- The Modal -->
    <div class="modal" id="callWaiter">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:25px;">

                <!-- Modal Header -->
                <div class="d-flex">
                    <h4 style="width:60%;" class="modal-title text-left color-qrorpa pt-2 pl-3">{{__('layouts.callService')}}</h4>
                    @if(isset($_GET['t']))
                        <h4 style="width:40%;" class="modal-title text-right color-qrorpa pr-3 pt-2">{{__('layouts.table')}} {{$_GET['t']}}</h4>
                    @endif
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-start">
                        <p class="text-center" style="width:100%;"><strong>Sie haben die Möglichkeit, einen bestimmten Kellner auszuwählen</strong></p>
                        <?php
                            $theResIdnn = $_GET['Res'];
                            $theTabNrnn = $_GET['t'];                
                        ?>
                        @foreach (User::where([['role','55'],['sFor',$theResIdnn]])->get() as $waOne)
                            @if (tablesAccessToWaiters::where([['waiterId',$waOne->id],['toRes',$theResIdnn],['tableNr',$theTabNrnn],['statusAct','1']])->first() != NULL)
                                <div style="width:49%; margin-right:1%; border:1px solid black; border-radius:4px;" 
                                class="text-center p-1 waiterCallWa" onclick="selWaOnWaCall('{{$waOne->id}}')" id="waiterSelDiv{{$waOne->id}}">
                                    <img src="storage/icons/Asset 24800.png" style="width:20%;" alt="">
                                    <p style="margin:0px;"><strong>{{$waOne->name}}</strong></p>
                                </div>
                            @endif
                        @endforeach

                        <div style="width: 100%;" class="form-group mt-1">
                            <textarea style="font-size:16px;" placeholder="{{__('layouts.comment')}}..." id="commentCW" class="form-control shadow-none" rows="2"></textarea>
                        </div>

                        @if(isset($_GET['Res']) && isset($_GET['t']))
                            <input type="hidden" value="{{$_GET['Res']}}" id="restaurantCW">
                            <input type="hidden" value="{{$_GET['t']}}" id="tableCW">
                        @endif
                            <input type="hidden" value="0" id="waiterSelectedWCall">

                        <div style="width: 49%; margin-right:2%;">
                            <button data-dismiss="modal" class="buttonLogIn btn btn-block shadow-none" 
                            style="background-color: red; color: white; padding: 5px;">
                                <strong>{{__('layouts.cancel')}}</strong>
                            </button>
                        </div>
                        <div style="width: 49%;">
                            <button data-dismiss="modal" onclick="callWaiterF()" class="buttonLogIn btn btn-block shadow-none" 
                            style="background-color: rgb(39, 190, 175); color: white; padding: 5px;">
                                <strong>{{__('layouts.call')}}</strong>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





<script>
    function callWaiterF(){
        $.ajax({
            url: '{{ route("waiter.call") }}',
            method: 'post',
            data: {
                res: $('#restaurantCW').val(),
                table: $('#tableCW').val(),
                comment: $('#commentCW').val(),
                waSel: $('#waiterSelectedWCall').val(),
                _token: '{{csrf_token()}}'
            },
            success: (response) => {
                $('#waiterIsComming').show(500).delay(3500).hide(500);
            },
            error: (error) => {
                console.log(error);
                // alert({{__('adminP.oops_wrong')}});
                $('#waiterIsNotComming').show(500).delay(3500).hide(500);
            }
        })
    }

    function selWaOnWaCall(waId){
        if(waSelected != 0){
            $('#waiterSelDiv'+waSelected).attr('style','width:49%; margin-right:1%; border:1px solid black; border-radius:4px;');
        }
        $('#waiterSelDiv'+waId).attr('style','width:49%; margin-right:1%; border:1px solid black; border-radius:4px; background-color:rgb(210, 247, 220);');
        waSelected = waId

        $('#waiterSelectedWCall').val(waId);
    }
</script>













<!-- The phone options Modal -->
<div class="modal fade" id="optionsModal">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content" style="border-radius:30px;">

            <!-- Modal body -->
            <div  style="background-color:whitesmoke; width:100%;">
               
                <div class="text-center pt-4 pb-4" style="background-color:rgb(39, 190, 175); height:300px; transform: skewY(-6deg); margin-top:-230px; margin-bottom:-20px;">
                    @if(Auth::check())
                    <a onclick="profileOpenClick()" clas="profileLine" href="{{ route('profile.index') }}">
                        <div class="d-flex justify-content-center" style="margin-top:220px; transform: skewY(6deg);">                           
                            @if(Auth::user()->profilePic == 'empty')
                                <i class="far fa-3x fa-user" style="color:white;"></i>
                            @else
                                <img style="width:40px; height:40px; border-radius:50%;"  src="/storage/profilePics/{{Auth::user()->profilePic}}" alt="img">
                            @endif
                            <p style="font-size:24px; font-weight:bolder; color:white; " class="ml-2">
                                {{Auth::user()->name}}
                            </p>
                        </div>
                    </a>
                    @else
                        <div class="d-flex justify-content-center" style="margin-top:220px; transform: skewY(6deg);">
                 
                            <i class="far fa-3x fa-user" style="color:white;"></i>
                            <p style="font-size:24px; font-weight:bolder; color:white; " class="ml-2">
                                {{__('layouts.myAccount')}}
                            </p>
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-between text-center" style="margin-top:40px;">
                    @if(Auth::check())
                        <a class=" btn btn-block {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('logout') }}" 
                            style="border:2px solid lightgray; color:black; width:50%; margin-left:25%;"
                            onclick="logoutUserQRorpa(); event.preventDefault();  document.getElementById('logout-form').submit();">
                            {{ __('layouts.logout') }}
                        </a>
                        <form class="optionsAnchorPh" id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a onclick="countEinloggenClick()" class=" btn {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:2px solid lightgray; color:black; width:48%;"
                         href="{{ route('login') }}">{{__('layouts.login')}}</a>
                                   
                        <a onclick="countRegisterClick()" class="btn  {{(isset($_GET['demo']) ? 'disabled' : '')}}" style="border:4px solid rgb(39, 190, 175); color:black; width:48%;"
                         href="{{ route('register') }}">{{__('layouts.toRegister')}}</a>
                    @endif
                </div>
                <script>
                    function logoutUserQRorpa(){
                        $.ajax({
                            url: '{{ route("produktet.logoutPNrSessionRemove") }}',
                            method: 'post',
                            data: {
                                _token: '{{csrf_token()}}'
                            },
                            success: () => {
                            },
                            error: (error) => { console.log(error); }
                        });
                    }
                </script>







                <div style="background-color:whitesmoke; width:100%;" class="mt-2">
                    @if(isset($_GET["Res"]) && isset($_GET["t"]))
                        @if(Auth::check())<!--Restorant(Yes)  User(Yes) -->
                            @if(auth()->user()->role == 9)
                                <a onclick="SAPageOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('manageProduktet.index') }}"><i class="fas fa-columns"></i> {{__('layouts.restaurantManagement')}}</a>   
                            @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3 || auth()->user()->role == 15)
                                <a onclick="APageOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" 
                                    href="{{route('dash.index')}}"><i class="fas fa-columns"></i> {{__('layouts.adminMenu')}}</a>  
                            @else 
                                <a onclick="profileOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" 
                                    href="{{ route('profile.index') }}"><i class="far fa-user-circle"></i> {{__('layouts.goToProfile')}}</a>      
                            @endif
                            @if(Auth::user()->role != 9)
                                @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                                <a onclick="WaiterCallsOpenClick()" style="width:100%;" class="optionsAnchorPh" data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>   
                                @endif
                                <a onclick="CartOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('cart')}}"><i class="fas fa-shopping-cart"></i> {{__('layouts.cart')}}
                                    @if(Cart::count()  > 0)
                                        <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;">
                                        {{Cart::count() }}</span>
                                    @endif
                                </a>
                                <a onclick="MyOrdersOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ url('/home') }}"><i class="fas fa-utensils"></i> {{__('layouts.orders')}}</a>
                            @endif
                            @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                            <a class="optionsAnchorPh " data-toggle="modal" data-target="#chngTable" href="#" data-dismiss="modal"><i class="fas fa-border-none"></i> {{__('layouts.othersTable')}}</a>
                            <a class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="/tableRezIndex?Res={{$_GET['Res']}}"><i class="fas fa-table"></i> {{__('layouts.tableReservation')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                            @endif
                        @else<!--Restorant(Yes)  User(No) -->
                            @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                                <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh " data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                            @endif
                            <a onclick="CartOpenClick()" class="optionsAnchorPh  {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('cart') }}"><i class="fas fa-shopping-cart"></i> {{__('layouts.cart')}}
                                @if(  Cart::count() > 0)
                                    <sup>  <span class="alert-warning orderCountTop" style="border-radius:50%; padding:5px;"> {{ Cart::count() }}</span></sup>
                                @endif
                            </a>   
                            @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                            <a class="optionsAnchorPh " data-toggle="modal" data-target="#chngTable" href="#" data-dismiss="modal"><i class="fas fa-border-none"></i> {{__('layouts.othersTable')}}</a>
                            @endif
                            <a onclick="TrackOrderOpenClick()" class="optionsAnchorPh  {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('trackOrder.Home') }}"><i class="fas fa-file-contract"></i> {{__('layouts.trackOrder')}}</a>
                            @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                            <a class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="/tableRezIndex?Res={{$_GET['Res']}}"><i class="fas fa-table"></i> {{__('layouts.tableReservation')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                            @endif
                        @endif
                    @else
                        @if(Auth::check())<!--Restorant(No)  User(Yes) -->
                            @if(auth()->user()->role == 9)
                                <a onclick="SAPageOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{ route('manageProduktet.index') }}"><i class="fas fa-columns"></i> {{__('layouts.restaurantManagement')}}</a>
                            @elseif(auth()->user()->role == 5 || auth()->user()->role == 4 || auth()->user()->role == 3 || auth()->user()->role == 15)
                                <a onclick="APageOpenClick()" class="optionsAnchorPh  {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('dash.index')}}"><i class="fas fa-columns"></i> {{__('layouts.adminMenu')}}</a>
                                @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                                <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh" data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                                @endif
                            @elseif(auth()->user()->role == 1)
                                <a onclick="profileOpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" 
                                    href="{{ route('profile.index') }}"><i class="far fa-user-circle"></i> {{__('layouts.goToProfile')}}</a> 
                                @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                                    <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh " data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                                @endif
                            @endif
                            @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                            @endif
                        @else<!--Restorant(No)  User(No) -->
                            @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                            <a onclick="WaiterCallsOpenClick()" class="optionsAnchorPh" data-toggle="modal" data-target="#callWaiter" href="#" data-dismiss="modal"><i class="fas fa-concierge-bell"></i> {{__('layouts.callService')}}</a>
                            <a onclick="Covid19OpenClick()" class="optionsAnchorPh {{(isset($_GET['demo']) ? 'disabled' : '')}}" href="{{route('covid')}}"><i class="fas fa-virus"></i> Covid-19 {{__('layouts.contactForm')}}</a>
                            @endif
                        @endif
                    @endif
                </div>
                
              
                   

                <div class="text-center mt-3" >
                    <div class="text-center">
                        <button type="button" class="close text-center pb-3 pr-4" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
























@if(isset($_GET['t']))
    <!-- The Modal -->
    <div class="modal" id="chngTable">
    <div class="modal-dialog modal-md">
        <div class="modal-content"  style="border-radius:20px;">

        <!-- Modal Header -->
        <div class="modal-header" style="background-color:rgb(39, 190, 175); border-radius:20px;">
            <h3 class="modal-title" style="font-weight:bold; color:white;">{{__('layouts.changeTableRequirements')}}</h3>
            <button type="button" class="close" data-dismiss="modal" style="color:white;"><i class="far fa-lg fa-times-circle"></i></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body" id="chngTableMBody">   
            
                    <p class="text-center" style="font-weight:bold; font-size:18px;" >{{__('layouts.yourCurrentTable')}}: {{$_GET['t']}} <span id="tableCHalertTXT"></span></p> 
            
                <label class="commentTC text-center" style="display:none !important; width:100%;">{{__('layouts.comment')}}({{__('layouts.optional')}})</label>
                <textarea id="comentiCLTCH" placeholder="{{__('layouts.comment')}}({{__('layouts.optional')}})" class="commentTC" style="width:100%; display:none !important;" rows="2"></textarea>
                <input type="hidden" id="newtableTC">
                <input type="hidden" id="crrtableTC" value="{{$_GET['t']}}">
                <input type="hidden" id="resTC" value="{{$_GET['Res']}}">

                <button class="btn btn-block mb-2" style="display:none; color:white; font-weight:bold; background-color:rgb(39, 190, 175);"
                    data-dismiss="modal" id="sendBTNTC" onclick="sendTableCH()">{{__('layouts.send')}}</button>

                <div class="d-flex flex-wrap justify-content-between">
                    @foreach(TableQrcode::where([['Restaurant',$_GET['Res']],['forRez',1]])->get()->sortBy('tableNr') as $table)
                        @if($table->tableNr != $_GET['t'])
                            <?php $resssss = $_GET['Res'];?>
                            @if($table->kaTab != 0)
                                <div class="d-flex flex-wrap "  disabled style="width:15.5%; opacity:0.8; background-image: url('storage/images/tableSt_qrorpa2_red.PNG'); 
                                    background-repeat: no-repeat; background-size:contain;">
                            @elseif(TableChngReq::where([['newTable',$table->tableNr],['status',0],['toRes',$resssss]])->count() > 0)
                                <div class="d-flex flex-wrap "  disabled style="width:15.5%; opacity:0.8; background-image: url('storage/images/tableSt_qrorpa2_yellow.PNG'); 
                                    background-repeat: no-repeat; background-size:contain;">
                            @else
                                <div class="d-flex flex-wrap clickableTabChng" id="tableChng{{$table->id}}" onclick="setTableToChng('{{$table->id}}','{{$table->tableNr}}')" 
                                    style="width:15.5%; background-image: url('storage/images/tableSt_qrorpa2.PNG');  background-repeat: no-repeat; background-size:contain;">
                            @endif
                                <div style="width:100%;" class="clickable text-center" >
                                    <span style=" font-size:20px;" class="text-center"><strong> {{$table->tableNr}}</strong></span> 
                                </div>
                                <div style="margin-top:-10px; margin-bottom:-10px; width:100%; color:black;" class="text-center ">
                                    <i class="fas fa-users"></i>
                                </div>   
                            </div>
                        @endif
                    @endforeach

                
            </div>
    
        </div>

        </div>
    </div>
    </div>




    <script>
        function setTableToChng(tId,tNr){
            if(selTabCh != ''){
                $('#'+selTabCh).removeClass('clickableTabChngON');
                $('#'+selTabCh).addClass('clickableTabChng');            
            }
            $('#tableChng'+tId).removeClass('clickableTabChng');
            $('#tableChng'+tId).addClass('clickableTabChngON');
            selTabCh = 'tableChng'+tId;
            $('.commentTC').val(' ');
            $('.commentTC').show(500);
            $('#newtableTC').val(tNr);
            $('#sendBTNTC').show(500)
            $('#tableCHalertTXT').html('(neue Tabelle '+tNr+')');
        }

        function sendTableCH(){
            $.ajax({
                url: '{{ route("TabChngCli.store") }}',
                method: 'post',
                data: {
                    oldTable: $('#crrtableTC').val(),
                    newTable: $('#newtableTC').val(),
                    clPH: $('#verifiedNr007').val(),
                    comm: $('#comentiCLTCH').val(),
                    res: $('#resTC').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $('#confirmTCReq01').show(300).delay(3000).hide(300);
                    $("#chngTableMBody").load(location.href+" #chngTableMBody>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#pleaseUpdateAndTryAgain').val());
                }
            });
        }
    </script>

@endif


















<nav class="navbar navbar-expand-sm b-white"  width="100%" id="navDesktop" style="display:none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-left">
                <a class="navbar-brand" href="#"><img style="width:120px" src="/storage/images/logo_QRorpa.png" alt=""></a>
            </div>
        </div>

       
        <div class="row">
            <div class="col-12" >
                <a onclick="CartOpenClick()" type="button" href="{{route('cart')}}" class="btn btn-default" ><img src="storage/icons/Cart.PNG"/></a> 
               <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img src="storage/icons/listDown.PNG"/></button> 
            </div>
        </div>
    </div>
</nav>
   


<nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone" style="display:none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-left">
                <a class="navbar-brand" href="#"><img style="width:120px" src="/storage/images/logo_QRorpa.png" alt=""></a>
            </div>
        </div>

        <div class="row">
            <div class="col-12" >
                <a onclick="CartOpenClick()" type="button" href="{{route('cart')}}" class="btn btn-default" ><img src="storage/icons/Cart.PNG"/></a> 
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#optionsModal"><img src="storage/icons/listDown.PNG"/></button> 
            </div>
        </div>
    </div>
</nav>



<script>
    if ((screen.width>580)) {
                    // if screen size is 1025px wide or larger
                    $(".cartBottom").hide(); 
                    $("#menuList").css('margin-left','45%')

                    $('#navPhone').hide();
                    $('#navDesktop').show();

                 
                }
                else if ((screen.width<=580))  {
                    // if screen size width is less than 1024px
                    $(".cartBottom").show();
                    $("#menuList").css('margin-left',':2%');

                    $('#navPhone').show();
                    $('#navDesktop').hide();
                }
</script>
















<style>





.search {
  width: 96%;
  margin-left:2%;
  border-top-left-radius:20px;
  position: relative;
  display: flex;
}

.searchTerm {
  width: 100%;
  border: 1px solid rgb(245, 248, 250);
  border-right: none;
 
  padding: 5px;
  height: 35px;
  border-radius: 20px 0 0 20px;
  outline: none;
  color: #212529;
}



.searchButton {
  width: 40px;
  height: 35px;
  border: 1px solid rgb(245, 248, 250);
  background: rgb(39,190,175);
  text-align: center;
  color: #fff;
  border-radius: 0 20px 20px 0;
  cursor: pointer;
  font-size: 20px;
}

/*Resize the wrap to see the search bar change!*/
.wrap{
  width: 100%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.info-table tr{
    border-bottom: 1px solid #dedede;
}

/*Restaurant Cover Image Style*/
/*.col-lg-12.col-sm-12.text-center.mt-2 {
    background-image: url(storage/images/restaurant-cover-image.png);
    padding: 15px;
    background-size: cover;
    background-position: center;
    margin-top: 0rem !important;
}*/
/*stars style*/


.color-white{
    line-height: 0.2;
}
.color-white span{
    font-size: 14px;
}
#bewer{
    color: orange;
    font-size: 13px;
}
.send-button{
    background-color:rgb(39,190,175);
    color:white;
    border-radius:30px;
    border-color: none !important;
    height:auto;
    width:150px;
}


.star-ratings-css {
  unicode-bidi: bidi-override;
  color: #c5c5c5;
  font-size: 25px;
  margin: 0 auto;
  position: relative;
  /*text-shadow: 0 1px 0 #a2a2a2;*/
  display: inline-table;
} 
.star-ratings-css::before { 
  content: '★★★★★';
  opacity: .3;
  display: flex;
}

[title=".00"]::after {
  width: 0%;
}
[title=".50"]::after {
  width: 10%;
}
[title=".100"]::after {
  width: 20%;
}
[title=".150"]::after {
  width: 30%;
}
[title=".200"]::after {
  width: 40%;
}
[title=".250"]::after {
  width: 50%;
}
[title=".300"]::after {
  width: 60%;
}
[title=".350"]::after {
  width: 70%;
}
[title=".400"]::after {
  width: 80%;
}
[title=".450"]::after {
  width: 90%;
}
[title=".500"]::after {
  width: 100%;
}
.star-ratings-css::after {
  color: #ffb101;
  content: '★★★★★';
  /*text-shadow: 0 1px 0 #ab5414;*/
  position: absolute;
  z-index: 1;
  display: block;
  left: 0;
  top:0;
  width: attr(rating);
  overflow: hidden;
}
.profilepic-area{
    margin-top:-50px;
    z-index: 1;
}



@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,600,700);


.mySlides {
    display: none;
}
img {
    vertical-align: middle;
}


.dot {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 10s ease;
}

.active {
  background-color: #717171;
  border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px
}
.fadeSlide {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 10s;
  animation-name: fade;
  animation-duration: 10s;
}

@-webkit-keyframes fade {
  from {opacity: 1} 
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: 1} 
  to {opacity: 1}
}
.mySlides img{
    width: 100%;
    border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px
}

.link-area{
    font-weight: 400;
    color: #666;
    line-height: 1.71;
    letter-spacing: normal;
    background-color: #f8f5f2;
    padding: 16px 24px;
    font-size: 14px;
    margin-bottom: 20px;
}
.text-info-area{
    font-weight: 400;
    color: #666;
    line-height: 1.71;
    letter-spacing: normal;
    background-color: #f8f5f2;
    padding: 16px 24px;
    font-size: 14px;
    margin-bottom: 20px;
}

</style>




<!-- Extra stuf / on or off depending on the variables -->


<!-- Start Restorant  -->
    @if(isset($_GET['Res']) && isset($_GET['t']) )
    <?php $theR = $_GET['Res']; ?>
      
     
        <div class="container cover-container">
            <div class="row" >
              

       
                @if($thisRestaurantCoverImage )
                    <div class="slideshow-container">
                        @foreach($thisRestaurantCoverImage as $thisResCover)
                        <div class="mySlides fadeSlide" onclick="bannerClickCount('{{$theR}}')">
                            <a style="cursor: pointer;" data-toggle="modal" data-target="#coverInfo{{$thisResCover->id}}">
                            <img style="width:100%; height:180px; object-fit: cover;" src="storage/ResBackgroundPic/{{$thisResCover->image}}" alt="noimg"></a>
                        </div>
                        @endforeach                    
                        </div>
                        <br>
                        <div style="text-align:center">                
                        @foreach($thisRestaurantCoverImage as $thisResCover)
                        <span class="dot" style="display: none;"></span> 
                        @endforeach
                    </div>
                @endif
       
                    <div style="width:36%; z-index: 1;"></div>
                    <div class="profilepic-area" style="width:28%;">
                        @if(Restorant::find($_GET['Res']) != null && Restorant::find($_GET['Res'])->profilePic == 'none')
                            <img style="width:110px; height:110px; border-radius:50%; background-color:white; border: 1px solid #d7d7d7;" src="storage/images/showcase03.png" alt="">
                        @else
                            <img style="width:110px; height:110px; border-radius:50%;border: 1px solid #d7d7d7;" src="storage/ResProfilePic/{{Restorant::find($_GET['Res'])->profilePic}}" alt="">
                        @endif
                    </div>
                    {{-- @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                        <a href="{{route('covid')}}" style="width:36%; z-index:1; font-size:9px; height:29px; margin-top:7px;" class="btn btn-danger">Covid-19 {{__('layouts.contactForm')}}</a>
                    @endif --}}
           

        

      
              
            </div>

            <div class="row">
                <div class="col-md-12 restaurant-title">
                    @if(isset($_GET['Res']) )
                        @if(isset($_GET['demo']))
                            <p style="font-size:19px;margin-bottom: 0rem; color:rgb(72, 81, 87);"><strong>{{(resdemoalfa::where('forThis','=',$_GET['Res'])->first())->emri}}</strong></p>
                        @else
                      
                            <p style="font-size:19px; margin-bottom: 0rem; margin-bottom:-12px; color:rgb(72, 81, 87);"><strong>{{Restorant::find($_GET['Res'])->emri}}</strong> 
                                @if($RWHT != null)
                                    <a style="cursor: pointer;" data-toggle="modal" data-target="#resInfo{{$_GET['Res']}}">
                                    <i class="fa fa-info-circle infoButton"  aria-hidden="true" style="float: right;"></i></a>
                                @endif
                            </p>
                      
                            <style>
                                .yjetMenuRat{
                                    font-size:24px;
                                }
                            </style>
                                
                                @if(isset($_GET['Res']) && $_GET['Res'] != 22 && $_GET['Res'] != 23)
                                    <a style="cursor: pointer; " data-toggle="modal" class="ratingModal" data-target="#ratingModal{{$_GET['Res']}}">
                                    @if($thisRestaurantRatings != null)
                                        @if(number_format($thisRestaurantRaringAverage,1) < 0.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".00"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 0.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".50"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 1)
                                            <div class="star-ratings-css yjetMenuRat" title=".100"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 1.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".150"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) < 2)
                                            <div class="star-ratings-css yjetMenuRat" title=".200"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 2)
                                            <div class="star-ratings-css yjetMenuRat" title=".200"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 2.5 )
                                            <div class="star-ratings-css yjetMenuRat" title=".250"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) < 3 )
                                            <div class="star-ratings-css yjetMenuRat" title=".300"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 3)
                                            <div class="star-ratings-css yjetMenuRat" title=".300"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 3.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".350"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) < 4)
                                            <div class="star-ratings-css yjetMenuRat" title=".400"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 4)
                                            <div class="star-ratings-css yjetMenuRat" title=".400"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) <= 4.5)
                                            <div class="star-ratings-css yjetMenuRat" title=".450"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) < 5)
                                            <div class="star-ratings-css yjetMenuRat" title=".500"></div>
                                        @elseif(number_format($thisRestaurantRaringAverage,1) == 5)
                                            <div class="star-ratings-css yjetMenuRat" title=".500"></div>
                                        @endif
                                        <span id="bewer">( {{count($thisRestaurantRatings)}} {{__('layouts.reviews')}})</span>
                                    @else
                                        <div class="star-ratings-css" title=".00"></div>
                                        <span id="bewer">( 0 {{__('layouts.reviews')}})</span>
                                    @endif
                                    </a>
                                @endif
                                
                        @endif
                    @endif

                    

                    <div class="col-md-12 search-area">
                        <div class="wrap text-center">
                            <div class="search">
                                <input id="input_search" type="text" class="searchTerm" placeholder="{{__('layouts.whatAreYouLookingFor')}}">
                                <button type="submit" class="searchButton ">
                                    @if ($agent->is('iPhone'))
                                        <i class="fa fa-search" style="padding:0; margin-left:-0.25cm;"></i>
                                    @else
                                        <i class="fa fa-search" style="padding:0;"></i>
                                    @endif
                                    
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
                    @if($RWHT != null)
                    <!-- Info Modal -->
                        <div id="resInfo{{$_GET['Res']}}" class="modal fade" role="dialog">
                          <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header" style="position: sticky; z-index: 10; top:0">
                                <h4 class="modal-title">{{__('layouts.aboutRestaurant')}}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">


                            
                                       @if(Restorant::find($_GET['Res'])->resDesc != 'none')
                                  <p> <strong>{{__('layouts.description')}}:</strong>{{Restorant::find($_GET['Res'])->resDesc}}</p> 
                                  @endif
                                <h5> <i class="fa fa-clock-o" aria-hidden="true"></i> <strong>{{__('layouts.openTime')}}:</strong></h5>

                                   
                                    <table class="table info-table" style="width: 100%; background-color: #fafafa;">
                                        <tbody>
                                            <tr>
                                                <td valign="top">{{__('layouts.monday')}} </td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D1Starts1 != "none" && $RWHT->D1End1 != "none" && $RWHT->D1Starts2 != "none" && $RWHT->D1End2 != "none")
                                                        <strong>{{$RWHT->D1Starts1}} - {{$RWHT->D1End1}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->D1Starts2}} - {{$RWHT->D1End2}}</strong>
                                                    @elseif($RWHT->D1Starts1 != "none" && $RWHT->D1End1 != "none")
                                                        <strong>{{$RWHT->D1Starts1}} - {{$RWHT->D1End1}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.tuesday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D2Starts1 != "none" && $RWHT->D2End1 != "none" && $RWHT->D2Starts2 != "none" && $RWHT->D2End2 != "none")
                                                        <strong>{{$RWHT->D2Starts1}} - {{$RWHT->D2End1}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->D2Starts2}} - {{$RWHT->D2End2}}</strong>
                                                    @elseif($RWHT->D2Starts1 != "none" && $RWHT->D2End1 != "none")
                                                        <strong>{{$RWHT->D2Starts1}} - {{$RWHT->D2End1}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.wednesday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D3Starts1 != "none" && $RWHT->D3End1 != "none" && $RWHT->D3Starts2 != "none" && $RWHT->D3End2 != "none")
                                                        <span> <strong>{{$RWHT->D3Starts1}} - {{$RWHT->D3End1}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->D3Starts2}} - {{$RWHT->D3End2}}</strong></span>
                                                    @elseif($RWHT->D3Starts1 != "none" && $RWHT->D3End1 != "none")
                                                        <strong>{{$RWHT->D3Starts1}} - {{$RWHT->D3End1}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.thursday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D4Starts1 != "none" && $RWHT->D4End1 != "none" && $RWHT->D4Starts2 != "none" && $RWHT->D4End2 != "none")
                                                        <strong>{{$RWHT->D4Starts1}} - {{$RWHT->D4End1}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->D4Starts2}} - {{$RWHT->D4End2}}</strong>
                                                    @elseif($RWHT->D4Starts1 != "none" && $RWHT->D4End1 != "none")
                                                        <strong>{{$RWHT->D4Starts1}} - {{$RWHT->D4End1}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.friday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D5Starts1 != "none" && $RWHT->D5End1 != "none" && $RWHT->D5Starts2 != "none" && $RWHT->D5End2 != "none")
                                                        <strong>{{$RWHT->D5Starts1}} - {{$RWHT->D5End1}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->D5Starts2}} - {{$RWHT->D5End2}}</strong>
                                                    @elseif($RWHT->D5Starts1 != "none" && $RWHT->D5End1 != "none")
                                                       <strong> {{$RWHT->D5Starts1}} - {{$RWHT->D5End1}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.saturday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D6Starts1 != "none" && $RWHT->D6End1 != "none" && $RWHT->D6Starts2 != "none" && $RWHT->D6End2 != "none")
                                                        <strong>{{$RWHT->D6Starts1}} - {{$RWHT->D6End1}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->D6Starts2}} - {{$RWHT->D6End2}}</strong>
                                                    @elseif($RWHT->D6Starts1 != "none" && $RWHT->D6End1 != "none")
                                                        <strong>{{$RWHT->D6Starts1}} - {{$RWHT->D6End1}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top">{{__('layouts.sunday')}}</td>
                                                <td valign="center" align="right" style="font-size:13px;">
                                                    @if($RWHT->D7Starts1 != "none" && $RWHT->D7End1 != "none" && $RWHT->D7Starts2 != "none" && $RWHT->D7End2 != "none")
                                                        <strong>{{$RWHT->D7Starts1}} - {{$RWHT->D7End1}}</strong>{{__('layouts.and')}}<strong>{{$RWHT->D7Starts2}} - {{$RWHT->D7End2}}</strong>
                                                    @elseif($RWHT->D7Starts1 != "none" && $RWHT->D7End1 != "none")
                                                       <strong> {{$RWHT->D7Starts1}} - {{$RWHT->D7End1}}</strong>
                                                    @else
                                                        <strong>{{__('layouts.restDay')}}</strong>
                                                    @endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                
                                <h5 style="margin-top:40px;"> <i class="fa fa-location-arrow" aria-hidden="true"></i> <strong>{{__('layouts.address')}}:</strong></h5>
                                
                                <p style=" padding: 5px;">
                                    @if(Restorant::find($_GET['Res'])->map != 'none')
                                    <iframe src="{{Restorant::find($_GET['Res'])->map}}"
                                        width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                    @else
                                     <div id="map"> <iframe width='100%' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'
                                      src='https://maps.google.com/maps?&amp;q="+ {{Restorant::find($_GET['Res'])->adresa}} + "&amp;hl=de&amp;output=embed'></iframe></div>
                                    @endif

                                    {{Restorant::find($_GET['Res'])->emri}}<br>
                                    {{Restorant::find($_GET['Res'])->adresa}}</p>
                  
                         
                                 
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('layouts.close')}}</button>
                              </div>
                            </div>

                          </div>
                        </div>
                    @endif
                  












                      <!-- Ratings Modal -->
                        <div id="ratingModal{{$_GET['Res']}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                <div class="modal-header" style="position: sticky; z-index: 10; top:0">
                                    <h4 class="modal-title"><i style="color:rgb(39, 190, 175);" class="fa fa-star" aria-hidden="true"></i> <strong>{{__('layouts.reviews')}}</strong></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                

                                        <div id="bewertungen">
                                            {{-- Ratings Area --}}
                                                <div class="ratings-area">
                                        
                                                <form id="ajaxform">
                                                    {{csrf_field()}}
                                                    <span class="success" style="color:green; margin-top:10px; margin-bottom: 10px;"></span>
                                                    <div class="form-group text-center" style="display: table-row;">
                                                        <div class="rating-stars text-center">
                                                        
                                                            <fieldset class="rating text-center" >
                                                                <strong>{{__('layouts.overallRating')}}*</strong><br>
                                                                <input type="radio" id="star5" name="stars" value="5" required="" /><label class = "full stars" for="star5" title="{{__('layouts.awesome5Stars')}}"></label>   
                                                                <input type="radio" id="star4" name="stars" value="4" /><label class = "full stars" for="star4" title="{{__('layouts.prettyGood4Stars')}}"></label>
                                                                <input type="radio" id="star3" name="stars" value="3" /><label class = "full stars" for="star3" title="{{__('layouts.meh3Stars')}}"></label>
                                                                <input type="radio" id="star2" name="stars" value="2" /><label class = "full stars" for="star2" title="{{__('layouts.kindaBad2Stars')}}"></label>
                                                                <input type="radio" id="star1" name="stars" value="1" /><label class = "full stars" for="star1" title="{{__('layouts.sucksBigTime1Star')}}"></label>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" placeholder="{{__('layouts.nickname')}}*" name="nickname" class="form-control" placeholder="" required="" id="nickname">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" placeholder="{{__('layouts.title')}}" name="title" class="form-control" placeholder="" id="titel">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="email" placeholder="{{__('layouts.email')}}" name="email" class="form-control" placeholder="" id="email">
                                                    </div>

                                                    @if(Auth::check())
                                                        <input type="hidden" name="klientiRat" value="{{Auth::user()->id}}">
                                                    @else
                                                        <input type="hidden" name="klientiRat" value="0">
                                                    @endif

                                                

                                                    <div class="form-group">
                                                        <label>{{__('layouts.review')}}</label>
                                                        <textarea class="form-control"  name="comment" rows="4" cols="50"></textarea>
                                                        <input type="hidden" name="restaurant_id" class="form-control" value="{{Restorant::find($_SESSION["Res"])->id}}" id="restaurant_id">
                                                    </div>
                                                    <input type="hidden" name="verified" class="form-control" placeholder="" value="0" id="verified">
                                                
                                                    <div class="form-group d-flex justify-content-between">
                                                        <button class="btn btn-success send-button" id="submit" style="margin-top: 20px;margin-bottom: 20px; width:100%;">{{__('layouts.send')}}</button>
                                                    </div>
                                                </form>
                                                    
                                                    <table class="table">
                                                    
                                                    
                                                    
                                                            <tbody>
                                                                @foreach($thisRestaurantRatings as $thisres)
                                                                        <tr>
                                                                            <td style="font-weight:bold;">{{$thisres->nickname}} &nbsp&nbsp&nbsp @if($thisres->stars == 5)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                            @elseif($thisres->stars == 4)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star "></span>
                                                                            @elseif($thisres->stars == 3)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                            @elseif($thisres->stars == 2)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                            @elseif($thisres->stars == 1)
                                                                                <span class="fa fa-star checked"></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                                <span class="fa fa-star "></span>
                                                                            @endif
                                                                            <br>
                                                                            <p style="padding:3px; font-size:13px; font-weight:normal;">{{$thisres->comment}}</p>
                                                                        </td>
                                                                        
                                                                        </tr>
                                                                        @endforeach
                                                            
                                                            </tbody>
                                                            
                                                            
                                                    </table>
                                                    

                                                </div>
                                        {{-- End Ratings Area --}}
                                        </div>                                
                                    </div>
                            
                                </div>

                            </div>
                        </div>























                        <!-- Cover Info Modal -->
                        @foreach($thisRestaurantCoverImage as $thisResCover)
                        <div id="coverInfo{{$thisResCover->id}}" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header" style="position: sticky; z-index: 10; top:0">
                                <h4 class="modal-title"><i class="fa fa-info-circle" aria-hidden="true"></i> {{__('layouts.aboutCover')}}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <div class="link-area">
                                   <a onclick="bannerLinkClick('{{$thisResCover->id}}', '{{$theResId}}')" target="_blank" href="{{ url($thisResCover->link) }}"><i class="fa fa-external-link" aria-hidden="true"></i> {{$thisResCover->link}}</a>
                               </div>
                                <div class="text-info-area">
                                   {{$thisResCover->text}}
                               </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('layouts.close')}}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                       @endforeach

                    


        <!-- <div class="text-center mt-1 mb-4">
            <img style="width:20px;" src="https://img.icons8.com/ultraviolet/40/000000/star.png"/><span style="color:rgb(39,190,175)"><strong>4.5</strong></span>
        </div> -->


        <br>
<!-- End Restorant  -->















































































<!-- Start Barbershop  -->
    @else

        <!-- <div class="container">
            <div class="row b-white">
                
                <div class="col-lg-6 col-sm-12 text-center" style="margin-top:7%;">
                    <p class="color-qrorpa" style="font-size:40px;">Bitte scannen Sie zuerst den QR-Code, um Ihre Bestellung aufzugeben!</p>
                </div>
               
                <div class="col-lg-6 col-sm-12">
                    <img src="storage/gifs/qrScan.gif" alt="" style="width: 100%">
                </div>
            </div>
        </div> -->
        @if(!Auth::check() || !isset($_GET['Res']))
            <?php
                header("Location: ".route('firstPage.index'));
                exit();
            ?>
        @endif
                                            
    @endif


   

    <div class="container-fluid" style="padding:0;">
         @yield('content')
    </div>











     <script>
        function resize() {
              if(window.innerWidth > 760){
                document.getElementsByClassName("ratingModal")[0].removeAttribute("data-toggle");
              }
            }
 
        //Restaurant Rating Post function
            $('#ajaxform').on('submit',function(event){
                event.preventDefault();

                restaurant_id = $("input[name=restaurant_id]").val();
                nickname = $("input[name=nickname]").val();
                stars = $("input[name=stars]:checked").val();
                title = $("input[name=title]").val();
                email = $("input[name=email]").val();
                comment = $("textarea[name=comment]").val();
                verified = $("input[name=verified]").val();
               
                $.ajax({
                    url:"{{route('restaurantRatings.store')}}",
                    type : 'post',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        restaurant_id:restaurant_id,
                        nickname:nickname,
                        title:title,
                        email:email,
                        stars:stars,
                        comment:comment,
                        verified:verified,
                        clientBy:$("input[name=klientiRat]").val()
                    },
                    success:function(data){
                        if(data) {
                            $('.success').text(data.success);
                            $("#ajaxform")[0].reset();
                        }
                    },
                    error: (error) => {
                        console.log(error);
                    }
                 });
            });




        //Tabs script
            function openCity(evt, cityName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(cityName).style.display = "block";
                evt.currentTarget.className += " active";
            }




            $(document).ready(function() {
                $(window).scroll(function(){
                    $('.cartBottom').toggleClass('scrolling', $(window).scrollTop() > $('#header').offset());

                    //long-form
                    var scrollPosition, headerOffset, isScrolling;

                    scrollPosition = $(window).scrollTop();
                    headerOffset = $('#header').offset();
                    isScrolling = scrollPosition > headerOffset;
                    $('.cartBottom').toggleClass('scrolling', isScrolling);
                });

            });









            function countEinloggenClick(){
                    $.ajax({
						url: '{{ route("saStatistics.einloggenClicksOne") }}',
						method: 'post',
						data: {
							id: 1,
							_token: '{{csrf_token()}}'
						},
						success: () => {
						},
						error: (error) => {
							console.log(error);
						}
					});
            }
            function countRegisterClick(){
                    $.ajax({
						url: '{{ route("saStatistics.registerClicksOne") }}',
						method: 'post',
						data: {
							id: 1,
							_token: '{{csrf_token()}}'
						},
						success: () => {
						},
						error: (error) => {
							console.log(error);
						}
					});
            }
            function SAPageOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.SAPageOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function APageOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.APageOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function WaiterCallsOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.WaiterCallsOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function CartOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.CartOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function MyOrdersOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.MyOrdersOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function TrackOrderOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.TrackOrderOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function Covid19OpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.Covid19OpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }
            function ProfileOpenClick(){
                $.ajax({
					url: '{{ route("saStatistics.ProfileOpenOne") }}',
					method: 'post',
                    data: {id: 1, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }

            function bannerClickCount(ResId){
                $.ajax({
					url: '{{ route("saStatistics.BannerClickOne") }}',
					method: 'post',
                    data: {id: ResId, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }

            function bannerLinkClick(ResCoId , ResId){
                $.ajax({
					url: '{{ route("saStatistics.BannerClickLinkOne") }}',
					method: 'post',
                    data: {resId: ResId, resCoId: ResCoId, _token: '{{csrf_token()}}'}
                    ,success: () => {},
					error: (error) => {console.log(error);}
                });
            }



        </script>
        <script>
            var slideIndex = 0;
            showSlides();
            function showSlides() {
              var i;
              var slides = document.getElementsByClassName("mySlides");
              var dots = document.getElementsByClassName("dot");
              for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
              }
              slideIndex++;
              if (slideIndex > slides.length) {slideIndex = 1}    
              for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
              }
              slides[slideIndex-1].style.display = "block";  
              dots[slideIndex-1].className += " active";
              setTimeout(showSlides, 5000); // Change image every 5 seconds
            }
            </script>
















                <script>
              

                    function closeMSGAC(){
                    	$("#adminToClientMsg").load(location.href+" #adminToClientMsg>*","");
                        $('#adminToClientMsg').hide(250); 
                    }
                    function MSGACsendAnswer01(){
                        $('#sendAntwortenToAdmin').show(200);
                    }



                    function MSGACsendAnswer02(res){
                        if($('#MSGACsendAnswerText').val() != '' && $('#MSGACsendAnswerText').val() != ' '){
                                $.ajax({
                                    url: '{{ route("TabChngCli.MsgUserToAdmin") }}',
                                    method: 'post',
                                    data: {
                                        res: res,
                                        table: $('#thisTable2').val(),
                                        msg: $('#MSGACsendAnswerText').val(),
                                        msgAdmin: $('#adminToClientMsgText').html(),
                                        adminId: $('#adminToClientMsgAdmin').val(),
                                        clPhNr: $('#verifiedNr007').val(),
                                        _token: '{{csrf_token()}}'
                                    },
                                    success: () => {
                                        $("#adminToClientMsg").load(location.href+" #adminToClientMsg>*","");
                                        $('#adminToClientMsg').hide(250); 
                                    },
                                    error: (error) => {
                                        console.log(error);
                                        // alert($('#pls_refresh_try_again').val());
                                    }
                                });
                        }else{
                            $('#MSGACsendAnswerTextError01').show(200).delay(3000).hide(200);
                        }
                    }
                </script>

                <div id="confirmTCReq01" class="alert alert-success p-4" style="position:fixed; top:100px; width:100%; font-weight:bold; font-size:large; display:none; z-index:9999999">
                    {{__('layouts.thanksRequestNotifySoon')}}
                </div>
                <div id="responseTCReqError" class="alert alert-danger p-4" style="position:fixed; top:100px; width:100%; font-weight:bold; font-size:large; display:none; z-index:9999999"></div>
                <div id="responseTCReqSuccess" class="alert alert-success p-4" style="position:fixed; top:100px; width:100%; font-weight:bold; font-size:large; display:none; z-index:9999999"></div>
                
                <div id="adminToClientMsg" class="alert alert-info p-4 " 
                    style="position:fixed; top:100px; width:100%; border-radius:15px; font-weight:bold; z-index:9999999;
                         font-size:large; display:none; border:2px solid rgb(72,81,87,0.6)">
                    <p style="font-weight:bold; color:rgb(39, 190, 175);" class="text-center">
                         {{__('layouts.messageFromStaff')}}</p>
                    <p style="color:rgb(72, 81, 87);" id="adminToClientMsgText"></p>
                    <input type="hidden" id="adminToClientMsgAdmin">
                    <div class="d-flex justify-content-between">
                        <button onclick="closeMSGAC()" style="width:48%;" class="btn btn-outline-danger">{{__('layouts.close')}}</button>
                        <button onclick="MSGACsendAnswer01()" style="width:48%;" class="btn btn-outline-info">{{__('layouts.respond')}}</button>
                    </div>
                    <div class="mt-1 mb-1" style="display:none;" id="sendAntwortenToAdmin">
                        <label style="color:rgb(39, 190, 175); width:100%" class="text-center" for="">{{__('layouts.respond')}}</label>
                        <textarea name="" id="MSGACsendAnswerText" style="width:100%;" rows="2"></textarea>
                        <div style="display:none;"  id="MSGACsendAnswerTextError01"  class="alert alert-danger text-center">{{__('layouts.writeAnswer')}}</div>
                        <button onclick="MSGACsendAnswer02('{{$theResId}}')" class="btn btn-block btn-outline-success">{{__('layouts.send')}}</button>
                    </div>
                </div>




































            



</body>
</html>


