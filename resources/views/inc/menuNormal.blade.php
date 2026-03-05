@include('words')
<?php
    if (session_status() == PHP_SESSION_NONE) { session_start();}

    // Back from google login 
    if(isset($_SESSION['Res']) && isset($_SESSION['t']) && isset($_GET['Res']) && $_GET['Res'] == 1){
        header("Location: /?Res=".$_SESSION['Res']."&t=".$_SESSION['t']."");
        exit();    
    }else if(isset($_GET['Res']) && $_GET['Res'] < 1){
        header("Location: /?Res=13&t=".$_SESSION['t']."");
        exit(); 
    }else if(isset($_GET['Res']) && $_GET['Res'] == 1 && isset($_GET['t']) && $_GET['t'] == 1){
        header("Location: /?Res=13&t=1");
        exit();  
    }
    use App\Produktet;
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
    use App\RecomendetProd;
    use App\resdemoalfa;
    use Carbon\Carbon;
    use App\Restorant;
    use App\TableQrcode;
    use App\TabOrder;
    use App\tabVerificationPNumbers;
    use App\Takeaway;
    use App\RestaurantWH;
    use App\RestaurantRating;
    use Illuminate\Support\Facades\Auth;

    if(isset($_GET['Res'])){
        $theRes = $_GET['Res'];
        $theRestaurant = Restorant::find($_GET["Res"]);
        $RWHT = RestaurantWH::where('toRes', $_GET['Res'])->first();
        $thisRestaurantRatings = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->orderBy('updated_at','DESC')->get();
        $thisRestaurantRatingsSum = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->sum('stars');
        $thisRestaurantRaringAverage = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->avg('stars');

        $openStat2D = explode('-||-',$theRestaurant->isOpen);
    }
    $RecPro = RecomendetProd::all()->sortBy("pozita");
?>



@include('inc.menuNormalComp.menuNormalStyle')

@include('inc.menuNormalComp.tableChngReqFAdmin')

@if ($openStat2D[0] == '0')
    <!-- Modal -->
    <div class="modal fade mt-5" id="Res313233BlockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border:2px solid rgb(39,190,175); border-radius:20px;">
                <div class="modal-body text-center">
                    <p class="text-center" style="font-size:1.2rem;"><strong>Zurzeit haben wir geschlossen!</strong></p>
                    <p class="text-center" style="color:rgb(39,190,175); font-size:1.2rem;"><strong>Vielen Dank für Ihr Verständnis.</strong></p>
                    <i style="color:rgb(39,190,175);" class="text-center fas fa-3x fa-store-alt-slash"></i>
                </div>
            </div>
        </div>
    </div>

    <script>
        
        $('#Res313233BlockModal').modal('show');
       
    </script>
@endif



<!-- <p>{{Cookie::get('retSessionCK') }}</p> -->
<!-- return session from cookie -->
@if (Cookie::has('retSessionCK') && Cookie::get('retSessionCK') != 'not' && !isset($_SESSION['phoneNrVerified']))

    <!-- Modal -->
    <div class="modal mt-5" id="cartRetFromCookieAlert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel"><strong>Warenkorb wieder hergestellt</strong></h4> 
                </div>
                <div class="modal-body">
                    Webbrowser wurde zwangsweise geschlossen <br> Bitte Online bleiben, damit dein Warenkorb aktiviert ist
                    <button onclick="closeCRFCA()" type="button" class="btn btn-dark btn-block mt-2" style="font-size: 1.4rem;" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times mr-3"></i> Schliessen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $.ajax({
            url: '{{ route("cart.returnCartFromCookie") }}',
            method: 'post',
            data: {
                cDt: '{{Cookie::get("retSessionCK")}}',
                theR: $('#thisRestaurant').val(),
                theT: $('#thisTable').val(),
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $('#cartRetFromCookieAlert').modal('toggle');
            },
            error: (error) => { console.log(error); }
        });

        function closeCRFCA(){
            location.reload();
        }
    </script>

@endif





    @if(Auth::check() && Auth::User()->role == 1 && Auth::User()->phoneNr != 'empty' && !isset($_SESSION['phoneNrVerified']))
        <script>
            $.ajax({
                url: '{{ route("produktet.usrPNrStartSession") }}',
                method: 'post',
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: (respo) => {
                    respo = respo.replace(/\s/g, '');
                    if(respo == 'pNrSeCreateSuccess'){
                        $('#usrPNrUsageOutside').html( '<div class="modal show" style="display: block; background-color:rgba(0, 0, 0, 0.5);" id="usrPNrUsage" role="dialog" aria-hidden="true">'+
                                    '<div class="modal-dialog" >'+
                                        '<div class="modal-content">'+
                                            '<div class="modal-body">'+
                                                '<div class="jumbotron mt-1">'+
                                                    '<h1 class="display-5">Hallo {{Auth::User()->name}}!</h1>'+
                                                    '<p class="lead">Ihre in Ihrem Profil gespeicherte Telefonnummer wird verwendet, wenn Sie anfangen, Produkte aus diesem Menü zu bestellen, dies dient lediglich dazu, Ihre Identität zu bestätigen.</p>'+
                                                '</div>'+ 
                                                '<button style="width:100%; margin:0px;" class="mt-1 btn btn-outline-dark text-center" onclick="closeusrPNrUsage()">schließen</button>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>');
                        $('body').addClass('modal-open');

                    }else if(respo == 'noPNrRegistred'){

                    }else if(respo == 'errorInvalideData'){

                    }
                },
                error: (error) => { console.log(error); }
            });

            function closeusrPNrUsage(){
                $('#usrPNrUsage').remove();
                $('body').removeClass('modal-open');
            }
        </script>
    @endif
    <div id="usrPNrUsageOutside">
    </div>



<script>
    var last = "";
    var lastV = 0;

    var lastRec = "";
    var lastVRec = 0;

    var originalCart = '';

    $.ajax({
		url: '{{ route("cart.checkCartValidity") }}',
		method: 'post',
		data: {res: $('#thisRestaurant').val(), tNr: $('#thisTable').val(), _token: '{{csrf_token()}}'},
		success: (res) => {
            res = res.replace(/\s/g, '');
            res2D = res.split('||');
            if(res2D[0] == 'invalideRes'){ 
                $('#invalideCartToResTable').modal('show');        
                $('#invalideCartToResTableinvalideTable').remove(); 
                setTimeout( function(){ location.reload();}  , 5000 );

            }else if(res2D[0] == 'invalideTable'){
                $('#invalideCartToResTable').modal('show'); 
                $('#invalideCartToResTableXT').html(res2D[1]);
                $('#invalideCartToResTableOldTNr').val(res2D[1]);
            }
		},
		error: (error) => {console.log(error);}
	});

    function invalideCartToResTableNein(){
        $.ajax({
            url: '{{ route("cart.emptyTheCart") }}',
            method: 'post',
            data: {_token: '{{csrf_token()}}'},
            success: () => {location.reload();},
            error: (error) => {console.log(error);}
        });
    }

    function invalideCartToResTableJa(){
        $.ajax({
            url: '{{ route("TabChngCli.store") }}',
            method: 'post',
            data: {
                oldTable: $('#invalideCartToResTableOldTNr').val(),
                newTable: $('#thisTable').val(),
                clPH: $('#verifiedNr007').val(),
                comm: 'invalideCartToResTable@invalideCartToResTable@invalideCartToResTable',
                res: $('#thisRestaurant').val(),
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $('#invalideCartToResTable2').modal('show');
            },
            error: (error) => { console.log(error); }
        });
    }
</script>
<div id="invalideCartToResTable" class="modal pt-5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p class="text-center"><strong> <i style="margin: 0px;" class="fas fa-exclamation-circle"></i> {{__('inc.invalideCartToResTableTxt01')}} </strong>
                </p>
                <div id="invalideCartToResTableinvalideTable" class="d-flex flex-wrap justify-content-around">
                    <p style="width:100%;" class="text-center"><strong>{{__('inc.invalideCartToResTableTxt02')}} <span id="invalideCartToResTableXT"></span> 
                    {{__('inc.invalideCartToResTableTxt03',['tNr' => $_GET['t']])}}</strong></p>
                    <input type="hidden" id="invalideCartToResTableOldTNr">
                    <button class="btn btn-danger" data-dismiss="modal" style="width:49%;" onclick="invalideCartToResTableNein()">{{__('inc.No')}}</button>
                    <button class="btn btn-success" data-dismiss="modal" style="width:49%;" onclick="invalideCartToResTableJa()">{{__('inc.Yes')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="invalideCartToResTable2" class="modal pt-5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p class="text-center"><strong> <i style="margin: 0px;" class="far fa-clock"></i> {{__('inc.invalideCartToResTableTxt04')}} </strong>
                </p>
            </div>
        </div>
    </div>
</div>







@if (isset($_GET['Res']))
            <!-- Pusher Script newOrder -->
            <script>
                var thisRestaurant = $('#thisRestaurant').val();
                var pusher = new Pusher('3d4ee74ebbd6fa856a1f', {
                    cluster: 'eu'
                });
                var channel = pusher.subscribe('OrdersChanel');
                channel.bind('App\\Events\\newOrder', function(data) {
                    var dataJ = JSON.stringify(data);
                    var dataJ2 = JSON.parse(dataJ);
                    if ("recUpdate" + thisRestaurant == dataJ2.text) {
                        location.reload(true);
                    }
                });
            </script>
@endif



<div class="container" style="display:none; margin-top:-35px; margin-bottom:35px;" id="waiterIsComming">
    <div class="row">
        <div class="col-lg-3 col-sm-0 col-0">
        </div>
        <div class="col-lg-6 col-sm-12 col-12 text-center">
            <div class="alert-success p-2" style="border-radius:10px;">
                {{ __('inc.waiterCallSuccess01') }}
            </div>
        </div>
        <div class="col-lg-3 col-sm-0 col-0">
        </div>
    </div>
</div>
<div class="container" style="display:none; margin-top:-35px; margin-bottom:35px;" id="waiterIsNotComming">
    <div class="row">
        <div class="col-lg-3 col-sm-0 col-0">
        </div>
        <div class="col-lg-6 col-sm-12 col-12 text-center">
            <div class="alert-danger p-2" style="border-radius:10px;">
                {{__('inc.waiterCallError01')}}
            </div>
        </div>
        <div class="col-lg-3 col-sm-0 col-0">
        </div>
    </div>
</div>
<div class="alert alert-success text-center" style="display:none; position:fixed; top:5%; z-index:999999; width:100%;" id="orderGettingReady">
   <strong>{{__('inc.orderReadySoon')}}</strong> 
</div>





<!-- orderGettingReadyFirst Modal -->
<div class="modal" id="orderGettingReadyFirst" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
        
            <div class="modal-body modalbody">
                <img src="storage/images/add_to_cart_success.png" class="img-responsive">
                <div class="modal-description">
                        <h2>{{__('inc.productAddedSuccessfully')}}</h2>
                    <div class="info-description">
                        <p><b>{{__('inc.serviceTeamReceivedOrder')}}</b></p>
                        <p>{{__('inc.youWillBeServedSoon')}}
                        </p>
                        <p><i><b>{{__('inc.niceStay')}}</b></i>😊</p>
                    </div>
                </div>
            </div>
            <div class="modal-button">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('inc.close')}}</button>
            </div>
        </div>
    </div>
</div>







<div class="ProdModalBody">


        <script>
            
            $('.katPics').each(function() {
                $(this).hide();
            });
        </script>

        @if (isset($_GET['kat']))
            <script type='text/javascript'>
                $(document).ready(function () {
                    $('html,body').animate({
                        scrollTop: $('.prodss').offset().top
                    },'slow');
                })
            </script>
        @endif

        <!-- Produktet Per Search  -->
        <div class="row">
            <div class="col-12 container" id="produktetUl">
                <!-- <ul class="list-group" >
                  
                </ul> -->
            </div>
        </div>

        <script>
            $('.Prods').each(function() {
                $(this).hide();
            });
        </script>
       
    <div class="col-md-3 left-section">
        @include('inc.menuNormalComp.leftSection')
    </div>
    <div class="col-md-3 right-section">
        @include('inc.menuNormalComp.rightSection')
        
    </div>
    <!-- Recomendet Products -->
    <div class="recommended-mobile">@include('inc.menuNormalComp.menuNormalRecomendet')</div>
        <div class="col-md-6 center-section">
            
            <div class="recommended-desktop">@include('inc.menuNormalComp.menuNormalRecomendet')</div>
            <!-- Show Menu -->
            @include('inc.menuNormalComp.menuNormalShowMenu')
        </div>
    </div>

<script>
    $('.prodsFoto').each(function() {
        $(this).hide();
    });
    function oneVisitToProd(pId){
        $.ajax({
            url: '{{ route("produktet.newClick") }}',
            method: 'post',
            data: {
                id: pId,
                _token: '{{csrf_token()}}'
            },
            success: () => {},
            error: (error) => {console.log(error);}
        });
    }
</script>














<div id="orderprot">


    <div id="orderprotONBTN"> 
        @if(isset($_SESSION['phoneNrVerified']) && count(Cart::content()) > 0)


            <?php $cancelOrderSum = 0;  $cancelOrderCount = 0; ?>
            @foreach(Cart::content() as $item)
                            <?php
                                if($item->options->ekstras == ''){ $theEx = 'empty'; }else{ $theEx = $item->options->ekstras; }
                                if($item->options->type == ''){ $theTy = 'empty'; }else{ $theTy = $item->options->type; }
                                
                                $tabEl = TabOrder::where([['OrderEmri',$item->name],['OrderExtra',$theEx],['OrderType',$theTy]
                                ,['OrderPershkrimi',$item->options->persh],['tabCode','!=',0]])->get();
                                if($tabEl != null){
                                    foreach( $tabEl as $oTO){
                                        if($oTO->status == 9){ $cancelOrderSum += $oTO->OrderQmimi;   $cancelOrderCount += $oTO->OrderSasia;}
                                    }
                                }  
                            ?>
            @endforeach
            <!--  route('cart') -->
            <div class="footerPhone" data-toggle="modal" data-target="#OrdersView" id="footerShowOrdersMobile">
                <a href="{{route('cart')}}">
                    <button id="anchorOrder" class="btn btn-default">
                        <!-- <i class="fas fa-shopping-basket fa-lg"></i> -->

                        <p style="margin-bottom:-6px;"> <img style="width:35px;" src="storage/icons/SAI01OrW.png" alt="">
                            <sup id="CartCountFooter" class="mr-5 pt-1 pl-2 pr-2 pb-1 color-qrorpa" style="width:20px; height:20px; border-radius:50%; font-size:19px; font-weight:bold;
                        background-color:white; color:black;">{{Cart::count() - $cancelOrderCount}}</sup>
                            <span id="CartTotalFooter" class="ml-5"><span id="CartTotalFooter2"
                                    style="font-size:27px;">{{number_format((float)Cart::total() - $cancelOrderSum, 2, '.', '')}}</span> {{__('inc.currencyShow')}}</span> </p>

                                    
                    </button>
                </a>
            </div>
        @endif
    </div>
    <script>
        if ((screen.width > 580)) { $('.footerPhone').hide();} else if ((screen.width <= 580)) { $('.footerPhone').show();}
    </script>






    <style>
    .animate-bottom {
        position: relative;
        animation: animatebottom 0.9s;
        width: 100%;
        height: 100%;
    }

    @keyframes animatebottom {
        from {
            bottom: -600px;
            opacity: 0;
        }

        to {
            bottom: 0;
            opacity: 1;
        }
    }










    .animeOnClick01:after {
        content: "";
        background: red;
        display: block;
        position: absolute;
        padding-top: 10%;
        padding-left: 350%;
        margin-left: -20px !important;
        margin-top: -20%;
        opacity: 0;
        transition: all 0.8s
    }

    .animeOnClick01:active:after {
        padding: 0;
        margin: 0;
        opacity: 1;
        transition: 0s
    }

    #Step2Cart{
        color:white;
        text-decoration:none;
    }
    #Step2Cart:hover{
        color:white;
        text-decoration:none;
    }
    #Step2Cart:active{
        color:white;
        text-decoration:none;
    }
    p.text-left.hover-pointer {
        margin-left: 10%;
    }
    /*Arbnor CSS*/
    @media(min-width: 500px)
    {
        p.text-left.hover-pointer {
            margin-left: 35px;
        }
        .modal-row{
            width: 118% !important;
            margin-left: -5% !important;
        }
        .col-9.d-flex{
            padding-left: 0px;
        }
        .recommended-extra{
            padding-right: 20px;
        }
    }
    /*End Arbnor CSS*/
    
    </style>



    <!-- The OrdersView Modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->



    <script>
        $('.AllExtrasOrder').hide();

        $(document).ready(function() {
            $('.AllExtrasOrder').hide();
            const observer = lozad();
            observer.observe();
        });

        function showOrderExtras(orderExt) {
            $('.orderExtra' + orderExt).show('slow');
            $('#buttonExt' + orderExt).hide('slow');
            $('#divExtra' + orderExt).attr('style', "border-bottom:1px solid lightgray; padding-bottom:15px;");
        }
    </script>
</div>







<script>
function refreshTheOrders() {
    $('#orderprot').load('/ #orderprot', function() {});
    //  $('#OrdersView').load('/ #OrdersView', function() {
    // });
}













function removeThisOrder(rId, price) {
    // alert(rId);

    $.ajax({
        url: '{{ route("cart.destroy") }}',
        method: 'delete',
        data: {
            rowId: rId,
            _token: '{{csrf_token()}}'
        },
        success: (response) => {
            // console.log(response);
            var current = parseFloat($('#CartTotalFooter2').text());
        var currentCH = parseFloat($('#CartTotalFooter2CH').text());
            $('#CartTotalFooter2').text((current - price).toFixed(2));
        $('#CartTotalFooter2CH').text((currentCH - price).toFixed(2));
            $('#CartCountFooter').text($('#CartCountFooter').text() - 1);
            // $('#orderElement'+rId).hide(1500);
            $('#orderViewModalBody').load('/ #orderViewModalBody', function() {});
        },
        error: (error) => {
            console.log(error);
            // alert($('#oops_wrong').val())
        }
    })
}


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
            $('#CartTotalFooter').text((current - price).toFixed(2) +' '+$("currencyShow").val());

            $('#' + thisId).hide('slow');
            $('#footerShowOrdersMobile').load('/ #footerShowOrdersMobile', function() {});
        },
        error: (error) => {
            console.log(error);
            // alert($('#oops_wrong').val())
        }
    })
}


function updateQTY(id, rId) {
    var element = $('#' + id);

    $.ajax({
        url: '{{route("cart.update")}}',
        method: 'post',
        data: {
            id: rId,
            val: element.val(),
            _token: '{{csrf_token()}}'
        },
        success: (response) => {
            // console.log(response);

            $('#orderViewContent').load('/ #orderViewContent', function() {});
            // $('#orderprot').load('/ #orderprot', function() {
            // });
        },
        error: (error) => {
            console.log(error);
            // alert($('#oops_wrong').val())
        }
    })

}
</script>






































<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!--  -->
<!-- The rest of the script -->





<script>
$(document).ready(function() {


    $('.owl-carousel').owlCarousel({
        loop: false,
        center: false,


        responsiveClass: true,
        responsive: {
            0: {
                items: 3,

            },

            550: {
                items: 4,

            },
            2400: {
                items: 4,

                loop: false
            }
        }
    });



    // $("#searchBar").on("keyup", function() {

    //     $('.Prods').each(function(){
    //         $(this).hide();
    //     });
    // });

    $("#input_search").keyup(function(e) {
        var searchWord = $(this).val();
        var theRestaurant = $('#thisRestaurant').val();

        if(searchWord != ""){
            // console.log(searchWord);
            $.ajax({
                method: 'POST',
                url: '{{route("search.from")}}',
                dataType: 'json',
                data: {
                    '_token': '{{csrf_token()}}',
                    searchWord: searchWord,
                    theRestaurant: theRestaurant
                },
                success: function(res){
                    $('.allKatFoto').hide();
                    $('#produktetUl').show();
                    $('#recProdListAllSection').hide();
                    
               
                    var listings = "";
                    $('#produktetUl').html('');


                    var dt = new Date();
                    var second = false;
                    var qmimiFin = 0;
                    var time = dt.getHours() + ":" + dt.getMinutes();
                    if(time > '20:00'){
                        var second = true;
                    }

                    // console.log(res);


                    $('#produktetUl').append('<h4 class="text-center">'+Object.keys(res).length+' '+$("#INCproductsFound").val()+'</h4>');
                    // var count = 0;
                    $.each(res, function(index, value){
                        // console.log(value);
                        if(second){
                            if(value.qmimi2 != 999999){
                                var qmimiFin = value.qmimi2;   
                            }else{
                                var qmimiFin = value.qmimi; 
                            }                          
                        }else{
                            var qmimiFin = value.qmimi; 
                        }
                        if(value.pershkrimi != null){
                             var persh = (value.pershkrimi).substring(0,35);
                        }else{
                            var persh = ""
                        }
                        listings ='<div class="row p-2" onclick="oneVisitToProd('+value.id+')" data-toggle="modal" data-target="#Prod'+value.id+'">'+
                                        '<div class="container-fluid">'+
                                            '<div class="row">'+
                                                '<div class="col-lg-3 col-sm-0 col-md-0"></div>'+
                                                '<div class="col-lg-6 col-sm-12 col-md-12 product-section">'+
                                                    '<div class="row">'+
                                                        '<div class="col-10">'+
                                                            '<h4 class="pull-right prod-name prodsFont" style="font-weight:bold; font-size: 1.20rem ">'+value.emri+'<span style="opacity:0.5"> ( '+$("#katsForSearch"+value.kategoria).val()+' ) </span> </h4>'+
                                                            '<p style=" margin-top:-10px; font-size:13px;">'+persh+''+
                                                            '<h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">'+$("#currencyShow").val()+'</span><span class="ml-2"> '+qmimiFin+' </span></h5>'+
                                                        '</div>'+
                                                        '<div class="col-2 add-plus-section">'+
                                                            '<button class="btn mt-2 noBorder" type="button"><i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i></button>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-lg-3 col-sm-0 col-md-0"></div>'+
                                            '</div>'+               
                                        '</div>'+               
                                    '</div>';               
                                                           
                        $('#produktetUl').append(listings); 
                        // count++;
                    });// end foreach
                    // $('#foundProdsSer').html(count);
                }
            });
        }else{
            $('.allKatFoto').show();
            $('#produktetUl').hide();
            $('#recProdListAllSection').show();
        }
       
    });




    $(".searchButton").click(function(e) {

        var searchWord = $('#input_search').val();
        var theRestaurant = $('#thisRestaurant').val();

        if(searchWord != ""){
            // console.log(searchWord);
            $.ajax({
                method: 'POST',
                url: '{{route("search.from")}}',
                dataType: 'json',
                data: {
                    '_token': '{{csrf_token()}}',
                    searchWord: searchWord,
                    theRestaurant: theRestaurant
                },
                success: function(res){
                    $('.allKatFoto').hide();
                    $('#produktetUl').show();
                    $('#recProdListAllSection').hide();
                    
               
                    var listings = "";
                    $('#produktetUl').html('');


                    var dt = new Date();
                    var second = false;
                    var qmimiFin = 0;
                    var time = dt.getHours() + ":" + dt.getMinutes();
                    if(time > '20:00'){
                        var second = true;
                    }


                    $('#produktetUl').append('<h4 class="text-center">'+Object.keys(res).length+' '+$('#INCproductsFound').val()+' </h4>');

                    $.each(res, function(index, value){
                        if(second){
                            if(value.qmimi2 != 999999){
                                var qmimiFin = value.qmimi2;   
                            }else{
                                var qmimiFin = value.qmimi; 
                            }                          
                        }else{
                            var qmimiFin = value.qmimi; 
                        }
                        if(value.pershkrimi != null){
                             var persh = (value.pershkrimi).substring(0,35);
                        }else{
                            var persh = ""
                        }
                        listings ='<div class="row p-2" data-toggle="modal" data-target="#Prod'+value.id+'">'+
                                        '<div class="container-fluid">'+
                                            '<div class="row">'+
                                                '<div class="col-lg-3 col-sm-0 col-md-0"></div>'+
                                                '<div class="col-lg-6 col-sm-12 col-md-12 product-section">'+
                                                    '<div class="row">'+
                                                        '<div class="col-10">'+
                                                            '<h4 class="pull-right prod-name prodsFont" style="font-weight:bold; font-size: 1.20rem ">'+value.emri+'</h4>'+
                                                            '<p style=" margin-top:-10px; font-size:13px;">'+persh+''+
                                                            '<h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">'+$("#currencyShow").val()+'</span>'+qmimiFin+'</h5>'+
                                                        '</div>'+
                                                        '<div class="col-2 add-plus-section">'+
                                                            '<button class="btn mt-2 noBorder" type="button"><i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i></button>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-lg-3 col-sm-0 col-md-0"></div>'+
                                            '</div>'+               
                                        '</div>'+               
                                    '</div>';               
                                                           
                        $('#produktetUl').append(listings); 
                    });// end foreach
                }
            });
        }else{
            $('.allKatFoto').show();
            $('#produktetUl').hide();
            $('#recProdListAllSection').show();
        }
    });





}); // End of ready document


function showProKat(kId) {
    if ($('#state' + kId).val() == 0) {
        $('#prodsKatFoto' + kId).show();
        $('#state' + kId).val(1);

        //Funksioni per numrim te clicks   
                    $.ajax({
                        url: '{{ route("kategorite.addVisit") }}',
                        method: 'post',
                        data: {
                            id: kId,
                            _token: '{{csrf_token()}}'
                        },
                        success: (res) => {
                        },
                        error: (error) => {
                            console.error(error);
                        }
                    });




    } else {
        $('#prodsKatFoto' + kId).hide();
        $('#state' + kId).val(0)
    }
}



function katFilter(idKat, kategoriId) {
    if (idKat == "") {
        $('.katPics').each(function() {
            $(this).hide();
        });
    } else {
        $('.katPics').each(function() {
            $(this).hide();
        });
        $('#kPic' + kategoriId).show('slow');
    }
    var value = idKat.toLowerCase();
    $("#produktetUl li").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });

}




















































function addThis2(theId, prodId, name, value, prodQ, nameClick) {
    var checkBox = document.getElementById(theId);
    var LlojetPro = document.getElementById('ProdAddLlojet' + prodId);
    var TotPrice = document.getElementById('TotPrice' + prodId);
    var ExtraCart = document.getElementById('ProdAddExtra' + prodId);
    var ExtraCartV = document.getElementById('ProdAddExtra' + prodId).value;
    var QmimiProd = document.getElementById('ProdAddQmimi' + prodId);

    var QmimiBaze = document.getElementById('ProdAddQmimiBaze' + prodId);
    var QmimiBazeValue = parseFloat(QmimiBaze.value);

    var type = name + '||' + value;

    if (last != '') {
        document.getElementById(last).checked = false;
    }
    if (lastV != '') {
        
        var prices = document.getElementsByClassName("price" + prodId);
        for (var i = 0; i < prices.length; i++) {
            var newV = parseFloat(prices.item(i).innerText);

            prices.item(i).innerText = newV.toFixed(2);
        }

        var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
        var newTot = (extPrice / lastV) + parseFloat(prodQ);
        TotPrice.value = newTot.toFixed(2);

        var extrasToSave = TotPrice.value - QmimiBazeValue;

        TotPrice.value = ((QmimiBazeValue / lastV) + extrasToSave).toFixed(2);
        QmimiProd.value = parseFloat(TotPrice.value).toFixed(2);
    }

    if(nameClick != 'False'){
        if(!$('#'+theId).is(":checked")){
            $('#'+theId).prop('checked', true);
        }else{
            $('#'+theId).prop('checked', false);
        }
    }

    if (checkBox.checked == true) {
        LlojetPro.value = type;

        $('#sendOrderBtn'+prodId).attr("data-dismiss","modal");

        var prices = document.getElementsByClassName("price" + prodId);
        for (var i = 0; i < prices.length; i++) {
            var newV = parseFloat(prices.item(i).innerText);

            prices.item(i).innerText = newV.toFixed(2);
            prices.item(i).disabled = true;
        }

        var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
        var newTot = (extPrice * value) + parseFloat(prodQ);
        TotPrice.value = newTot.toFixed(2);

        var stepRe = 1;
        var plusExt = 0;

        var plusExt = parseFloat(0);
        if (ExtraCartV != '') {
            var extras = ExtraCartV.split('--0--');

            for (var i = 0; i < extras.length; i++) {
                if(extras[i] != ''){
                    var extras2D = extras[i].split('||');

                    var newQ = parseFloat(extras2D[1]).toFixed(2);
                    
                    if (stepRe++ == 1) {
                        ExtraCart.value = extras2D[0] + '||' + newQ;
                    } else {
                        ExtraCart.value = ExtraCart.value + '--0--' + extras2D[0] + '||' + newQ;
                    }
                    plusExt = parseFloat(parseFloat(newQ)+parseFloat(plusExt));
                }

            }
            QmimiProd.value = document.getElementById('TotPrice' + prodId).value;
            // Qikj spo shkon numer 
        }

        TotPrice.value = parseFloat((QmimiBazeValue * value) + plusExt).toFixed(2);
        var tot = TotPrice.value;
        QmimiProd.value = parseFloat(tot).toFixed(2);

        last = theId;
        lastV = value;
    } else {
        $('#sendOrderBtn'+prodId).removeAttr("data-dismiss");

        LlojetPro.value = '';
        lastV = 0;
        last = "";

    }

    // alert($('#ProdAddLlojet'+prodId).val())
}












function addThis(theId, prodId, name, price, nameClick) {

    // 3
    
    var qmimiShow = document.getElementById('TotPrice' + prodId);
    var checkBox = document.getElementById(theId);

    var AddProdQmimi = document.getElementById('ProdAddQmimi' + prodId);
    var AddProdExtra = document.getElementById('ProdAddExtra' + prodId);
    var AddProdExtraValue = document.getElementById('ProdAddExtra' + prodId).value;


    var extras = name + '||' + parseFloat(price).toFixed(2);

    if(nameClick != 'False'){
        if(!$('#'+theId).is(":checked")){
            $('#'+theId).prop('checked', true);
        }else{
            $('#'+theId).prop('checked', false);
        }
    }

    if (checkBox.checked == true) {
        var newValue = parseFloat(qmimiShow.value) + parseFloat(price);
        newValue = newValue.toFixed(2);
        qmimiShow.value = newValue;
        AddProdQmimi.value = newValue;
        if (AddProdExtraValue == "") {
            AddProdExtra.value = extras;
        } else {
            AddProdExtra.value = AddProdExtraValue + '--0--' + extras;
        }


    } else {
        var newValue = parseFloat(qmimiShow.value) - parseFloat(price);
        newValue = newValue.toFixed(2);
        qmimiShow.value = newValue;
        AddProdQmimi.value = newValue;

        var DeletedVal = AddProdExtraValue.replace(extras, '');
        AddProdExtra.value = DeletedVal;

    }
}















































function addThisRec(theId, prodId, name, price, nameClick) {

    var qmimiShow = document.getElementById('TotPriceRec' + prodId);
    var checkBox = document.getElementById(theId);

    var AddProdQmimi = document.getElementById('ProdAddQmimiRec' + prodId);
    var AddProdExtra = document.getElementById('ProdAddExtraRec' + prodId);
    var AddProdExtraValue = document.getElementById('ProdAddExtraRec' + prodId).value;

    var extras = name + '||' + parseFloat(price).toFixed(2);

    if(nameClick != 'False'){
        if(!$('#'+theId).is(":checked")){
            $('#'+theId).prop('checked', true);
        }else{
            $('#'+theId).prop('checked', false);
        }
    }
    if (checkBox.checked == true) {
        var newValue = parseFloat(qmimiShow.value) + parseFloat(price);
        newValue = newValue.toFixed(2);
        qmimiShow.value = newValue;
        AddProdQmimi.value = newValue;
        if (AddProdExtraValue == "") {
            AddProdExtra.value = extras;
        } else {
            AddProdExtra.value = AddProdExtraValue + '--0--' + extras;
        }


    } else {
        var newValue = parseFloat(qmimiShow.value) - parseFloat(price);
        newValue = newValue.toFixed(2);
        qmimiShow.value = newValue;
        AddProdQmimi.value = newValue;

        var DeletedVal = AddProdExtraValue.replace(extras, '');
        AddProdExtra.value = DeletedVal;

    }
}







function addThis2Rec(theId, prodId, name, value, prodQ, nameClick) {
    var checkBox = document.getElementById(theId);
    var LlojetPro = document.getElementById('ProdAddLlojetRec' + prodId);
    var TotPrice = document.getElementById('TotPriceRec' + prodId);
    var ExtraCart = document.getElementById('ProdAddExtraRec' + prodId);
    var ExtraCartV = document.getElementById('ProdAddExtraRec' + prodId).value;
    var QmimiProd = document.getElementById('ProdAddQmimiRec' + prodId);

    var QmimiBaze = document.getElementById('ProdAddQmimiRecBaze' + prodId);
    var QmimiBazeValue = parseFloat(QmimiBaze.value);

    var type = name + '||' + value;
    if (lastRec != '') {
        document.getElementById(lastRec).checked = false;
    }
    if (lastVRec != '') {
        var prices = document.getElementsByClassName("priceRec" + prodId);
        for (var i = 0; i < prices.length; i++) {
            var newV = parseFloat(prices.item(i).innerText);

            prices.item(i).innerText = newV.toFixed(2);
        }

        var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
        var newTot = (extPrice / lastVRec) + parseFloat(prodQ);
        TotPrice.value = newTot.toFixed(2);

        var extrasToSave = TotPrice.value - QmimiBazeValue;
        TotPrice.value = ((QmimiBazeValue / lastVRec) + extrasToSave).toFixed(2);
        QmimiProd.value = parseFloat(TotPrice.value).toFixed(2);

    }

    if(nameClick != 'False'){
        if(!$('#'+theId).is(":checked")){
            $('#'+theId).prop('checked', true);
        }else{
            $('#'+theId).prop('checked', false);
        }
    }


    if (checkBox.checked == true) {
        LlojetPro.value = type;

        $('#sendOrderBtnRec'+prodId).attr("data-dismiss","modal");
    
        var prices = document.getElementsByClassName("priceRec" + prodId);
        for (var i = 0; i < prices.length; i++) {
            var newV = parseFloat(prices.item(i).innerText);

            prices.item(i).innerText = newV.toFixed(2);
            prices.item(i).disabled = true;
        }

        var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
        var newTot = (extPrice) + parseFloat(prodQ);
        TotPrice.value = newTot.toFixed(2);

        var stepRe = 1;
        var plusExt = 0;

        var plusExt = parseFloat(0);

        if (ExtraCartV != '') {
            
            var extras = ExtraCartV.split('--0--');
            for (var i = 0; i < extras.length; i++) {
                if(extras[i] != ''){
                    var extras2D = extras[i].split('||');

                    
                    var newQ = parseFloat(extras2D[1]).toFixed(2);
                    
                    if (stepRe++ == 1) {
                        ExtraCart.value = extras2D[0] + '||' + newQ;
                    } else {
                        ExtraCart.value = ExtraCart.value + '--0--' + extras2D[0] + '||' + newQ;
                    }
                    plusExt = parseFloat(parseFloat(newQ)+parseFloat(plusExt));
                }
            }

            
            QmimiProd.value = document.getElementById('TotPriceRec' + prodId).value;
            // Qikj spo shkon numer 
        }

        TotPrice.value = ((QmimiBazeValue * value) + plusExt).toFixed(2);
        var tot = TotPrice.value;
        QmimiProd.value = parseFloat(tot).toFixed(2);

        lastRec = theId;
        lastVRec = value;
    } else {
        $('#sendOrderBtnRec'+prodId).removeAttr("data-dismiss");
        LlojetPro.value = '';
        lastVRec = 0;
        lastRec = "";
    }
}

































$(document).ready(function() {
    var itemsMainDiv = ('.MultiCarousel');
    var itemsDiv = ('.MultiCarousel-inner');
    var itemWidth = "";

    $('.leftLst, .rightLst').click(function() {
        var condition = $(this).hasClass("leftLst");
        if (condition)
            click(0, this);
        else
            click(1, this)
    });

    ResCarouselSize();




    $(window).resize(function() {
        ResCarouselSize();
    });

    //this function define the size of the items
    function ResCarouselSize() {
        var incno = 0;
        var dataItems = ("data-items");
        var itemClass = ('.item');
        var id = 0;
        var btnParentSb = '';
        var itemsSplit = '';
        var sampwidth = $(itemsMainDiv).width();
        var bodyWidth = $('body').width();
        $(itemsDiv).each(function() {
            id = id + 1;
            var itemNumbers = $(this).find(itemClass).length;
            btnParentSb = $(this).parent().attr(dataItems);
            itemsSplit = btnParentSb.split(',');
            $(this).parent().attr("id", "MultiCarousel" + id);


            if (bodyWidth >= 1200) {
                incno = itemsSplit[6];
                itemWidth = sampwidth / incno;
            } else if (bodyWidth >= 992) {
                incno = itemsSplit[3];
                itemWidth = sampwidth / incno;
            } else if (bodyWidth >= 768) {
                incno = itemsSplit[2];
                itemWidth = sampwidth / incno;
            } else {
                incno = itemsSplit[1];
                itemWidth = sampwidth / incno;
            }
            $(this).css({
                'transform': 'translateX(0px)',
                'width': itemWidth * itemNumbers
            });
            $(this).find(itemClass).each(function() {
                $(this).outerWidth(itemWidth);
            });

            $(".leftLst").addClass("over");
            $(".rightLst").removeClass("over");

        });
    }


    //this function used to move the items
    function ResCarousel(e, el, s) {
        var leftBtn = ('.leftLst');
        var rightBtn = ('.rightLst');
        var translateXval = '';
        var divStyle = $(el + ' ' + itemsDiv).css('transform');
        var values = divStyle.match(/-?[\d\.]+/g);
        var xds = Math.abs(values[4]);
        if (e == 0) {
            translateXval = parseInt(xds) - parseInt(itemWidth * s);
            $(el + ' ' + rightBtn).removeClass("over");

            if (translateXval <= itemWidth / 2) {
                translateXval = 0;
                $(el + ' ' + leftBtn).addClass("over");
            }
        } else if (e == 1) {
            var itemsCondition = $(el).find(itemsDiv).width() - $(el).width();
            translateXval = parseInt(xds) + parseInt(itemWidth * s);
            $(el + ' ' + leftBtn).removeClass("over");

            if (translateXval >= itemsCondition - itemWidth / 2) {
                translateXval = itemsCondition;
                $(el + ' ' + rightBtn).addClass("over");
            }
        }
        $(el + ' ' + itemsDiv).css('transform', 'translateX(' + -translateXval + 'px)');
    }

    //It is used to get some elements from btn
    function click(ell, ee) {
        var Parent = "#" + $(ee).parent().attr("id");
        var slide = $(Parent).attr("data-slide");
        ResCarousel(ell, Parent, slide);
    }

});
</script>








































































<style>
.checkbox:checked:before {
    background-color: green;
}
</style>



<script>
    function prodModalCancel(pRecId,prodQ){
        // console.log($('#canceledRec').val());
        $(".allProTypesRecMenu").prop( "checked", false );
        $(".allProExtrasRecMenu").prop( "checked", false );

        var TotPrice = $('#TotPriceRec' + pRecId);
        var QmimiProd = document.getElementById('ProdAddQmimiRec' + pRecId);
        var QmimiBaze = document.getElementById('ProdAddQmimiRecBaze' + pRecId);
        var QmimiBazeValue = parseFloat(QmimiBaze.value);

        if (lastRec != '') {
        document.getElementById(lastRec).checked = false;
        }
        if (lastVRec != 0) {
            var prices = document.getElementsByClassName("priceRec" + pRecId);
            for (var i = 0; i < prices.length; i++) {
                var newV = parseFloat(prices.item(i).innerText) / lastVRec;

                prices.item(i).innerText = newV.toFixed(2);
            }
            var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
            var newTot = (extPrice / lastVRec) + parseFloat(prodQ);
            TotPrice.value = newTot.toFixed(2);

            var extrasToSave = TotPrice.value - QmimiBazeValue;
            TotPrice.value = ((QmimiBazeValue / lastVRec) + extrasToSave).toFixed(2);
            QmimiProd.value = parseFloat(TotPrice.value).toFixed(2);
        }

        $('#TotPriceRec'+pRecId).val((QmimiBazeValue).toFixed(2));
        $('#ProdAddQmimiRec'+pRecId).val((QmimiBazeValue).toFixed(2));

        $('#ProdAddExtraRec'+pRecId).val('');
        $('#ProdAddLlojetRec'+pRecId).val('');
        $('#komentMenuAjaxRec'+pRecId).val('');

        lastRec = "";
        lastVRec = 0;
    }


















    function prodModalCancelMenu(prodId,prodQ){
        // console.log($('#canceled').val());
        $(".allProTypesMenu").prop( "checked", false );
        $(".allProExtrasMenu").prop( "checked", false );

        // var TotPrice = document.getElementById('TotPrice' + prodId);
        var TotPrice = $('#TotPrice' + prodId);
        var QmimiProd = document.getElementById('ProdAddQmimi' + prodId);
        var QmimiBaze = document.getElementById('ProdAddQmimiBaze' + prodId);
        var QmimiBazeValue = parseFloat(QmimiBaze.value);

        if (last != '') {
        document.getElementById(last).checked = false;
        }
        if (lastV != 0) {
            var prices = document.getElementsByClassName("price" + prodId);
            for (var i = 0; i < prices.length; i++) {
                var newV = parseFloat(prices.item(i).innerText) / lastV;
                prices.item(i).innerText = newV.toFixed(2);
            }
            var extPrice = parseFloat(TotPrice.value) - parseFloat(prodQ);
            var newTot = (extPrice / lastV) + parseFloat(prodQ);
            TotPrice.value = newTot.toFixed(2);

            var extrasToSave = TotPrice.value - QmimiBazeValue;

            TotPrice.value = ((QmimiBazeValue / lastV) + extrasToSave).toFixed(2);
            QmimiProd.value = parseFloat(TotPrice.value).toFixed(2);
        }

        $('#TotPrice'+prodId).val((QmimiBazeValue).toFixed(2));
        $('#ProdAddQmimi'+prodId).val((QmimiBazeValue).toFixed(2));

        $('#ProdAddExtra'+prodId).val('');
        $('#ProdAddLlojet'+prodId).val('');
        $('#komentMenuAjax'+prodId).val('');

            last = "";
            lastV = 0;

        $("#Prod"+prodId).load(location.href+" #Prod"+prodId+">*","");
    }
</script>


<style>
    .plusForOrder:hover{
        cursor: pointer;
    }
</style>























<!-- Krijimi i porosive nga menu -->
<div class="ProdModalBody">
    @if(isset($_GET["Res"]))

    @foreach(Produktet::where('toRes',$_GET["Res"])->where('accessableByClients','1')->get() as $prod)
    <div class="modal modalProd" id="Prod{{$prod->id}}" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="border-radius:30px;">
                <div class="modal-body">

                    @if(Carbon::now()->format('H:i') >= $theRestaurant->secondPriceTime|| Carbon::now()->format('H:i') <= '03:00')
                        @if($prod->qmimi2 != 999999)
                            <?php $starterPrice = sprintf('%01.2f', $prod->qmimi2); ?>
                        @else
                            <?php $starterPrice = sprintf('%01.2f', $prod->qmimi); ?>
                        @endif
                    @else
                        <?php $starterPrice = sprintf('%01.2f', $prod->qmimi); ?>
                    @endif

                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-10">
                                <h4 class="modal-title">{{$prod->emri}} <span style="color:lightgray;">({{kategori::findOrFail($prod->kategoria)->emri}})</span></h4>
                            </div>
                            <div class="col-2 text-right">
                                <button type="button" class="btn" data-dismiss="modal">
                                    <i onclick="prodModalCancelMenu('{{$prod->id}}','{{$starterPrice}}')" style="width:6px;" class="far fa-times-circle"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12"><p>{{$prod->pershkrimi}}</p></div>
                        </div>
                        @if($prod->restrictPro != 0)
                            @if($prod->restrictPro == 16)
                                <div class="row">
                                    <span class="col-12 ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px; height: 20px !important;" src="/storage/icons/R16.jpeg" alt="noImg"></span>
                                </div>
                            @else
                                <div class="row">
                                    <span class="col-12 ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px; height: 20px !important;" src="/storage/icons/R18.jpeg" alt="noImg"></span>
                                </div>
                            @endif
                        @endif

                        <div class="row" style="border-bottom:1px solid lightgray;">
                            <div class="col-2 text-right mt-3">
                                <span class=" opacity-65">{{__('inc.currencyShow')}}</span>
                            </div>
                            <div class="col-4 text-left">
                                <input class="form-control color-qrorpa" style="border:none; font-size:20px;"
                                    id="TotPrice{{$prod->id}}" type="number" step="0.01"
                                    value="{{$starterPrice }}" disabled>
                            </div>
                            <div class="col-6 text-right" >
                                <div class="text-right d-flex">
                                    <span id="minusSasiaPerProd{{$prod->id}}" style="font-size:30px; display:none; width:20%;" class="pr-3 plusForOrder"
                                     onclick="removeOneToSasiaPro('{{$prod->id}}')" >-</span>
                                     <span id="placeholderSasiaPerProd{{$prod->id}}" style="font-size:30px; color:white; width:20%;" class="pr-3 plusForOrder">-</span>
                                    <input type="number" min="1" step="1" value="1" class="text-center" id="sasiaPerProd{{$prod->id}}"
                                        style="border:none;  width:60%; font-size:28px; height:fit-content;" disabled>

                                    <span style="font-size:30px; width:20%;" class="pl-3 plusForOrder" onclick="addOneToSasiaPro('{{$prod->id}}')">+</span>

                                </div>
                            </div>
                        </div>


                        <div class="row modal-row" style="width:140%; margin-left:-20%;">


                                @if(count(explode('--0--', $prod->type)) > 0 && $prod->type != NULL)
                                    @foreach(explode('--0--', $prod->type) as $alll)
                                        @if($alll != '' && $alll != NULL)
                                            <?php $hasOneT = true; ?>
                                        @endif
                                    @endforeach

                                    @if(isset($hasOneT) && $hasOneT)
                                        <?php $hasOneTVal = 1; ?>
                                    @else
                                        <?php $hasOneTVal = 0; ?>
                                    @endif
                                @else
                                    <?php $hasOneTVal = 0; ?>
                                @endif
                                <input type="hidden" value="{{$hasOneTVal}}" id="hasTypeThisPro{{$prod->id}}">

                            <div class="col-12 text-center" style="width:140%;">

                                @if($prod->type != NULL && count(explode('--0--', $prod->type)) > 0)
                                            @if(count(explode('--0--', $prod->type)) > 5)
                                                @if(isset($_GET['Res']) && $_GET['Res'] == 16)
                                                        <p onclick="showTypeMenu('{{$prod->id}}')" class="hover-pointer"><strong>{{__('inc.taste')}}</strong></p>
                                                        <hr>
                                                @else
                                                        <p onclick="showTypeMenu('{{$prod->id}}')" class="hover-pointer color-qrorpa mt-2"
                                                        style="margin-top:-10px; margin-bottom:-3px; font-size:larger;">
                                                        <strong>{{__('inc.type')}}</strong></p>
                                                @endif
                                            @else
                                            <p  class="hover-pointer color-qrorpa mt-2"
                                                style="margin-top:-10px; margin-bottom:-3px; font-size:larger;">
                                                <strong>{{__('inc.type')}}</strong></p>
                                            @endif
                                    <?php $priType = 1?>
                                    @foreach(explode('--0--', $prod->type) as $alll)
                                        @if($alll != '')
                                            
                                            <?php $thisType = LlojetPro::find(explode('||',$alll)[0]); ?>
                                                @if($thisType != null)
                                                <div class="color-text container-fluid mt-2 firstTwoTypes{{$prod->id}}">
                                                    <div class="row ml-1" style="margin-bottom:-15px;">
                                                                    
                                                        <div class="col-3 text-left">
                                                            <label  class="switch ">
                                                                    <?php
                                                                        if(Carbon::now()->format('H:i') > $theRestaurant->secondPriceTime){
                                                                            if($thisType->id == 106 && ($prod->id == 512 || $prod->id == 514)){
                                                                                $vleraType = number_format(1.4, 2, '.', '') ;
                                                                            }else  if($thisType->id == 107 && ($prod->id == 512 || $prod->id == 514)){
                                                                                $vleraType = number_format(2.6, 2, '.', '') ;
                                                                            }else{
                                                                                $vleraType = $thisType->vlera;
                                                                            }
                                                                        }else{
                                                                            $vleraType = $thisType->vlera;
                                                                        }
                                                                    ?>
                                                                    <input style="width:5px;" type="checkbox" class="primary allProTypesMenu allProTypes{{$prod->id}}" id="llojetPE{{$thisType->id}}O{{$prod->id}}"  name="llojetPE{{$thisType->id}}.{{$prod->id}}" 
                                                                                onchange="addThis2('llojetPE{{$thisType->id}}O{{$prod->id}}','{{$prod->id}}','{{$thisType->emri}}','{{$vleraType}}','{{$starterPrice}}','False')">
                                                                        <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                </label>
                                                        </div>
                                                        <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThis2('llojetPE{{$thisType->id}}O{{$prod->id}}','{{$prod->id}}','{{$thisType->emri}}','{{$vleraType}}','{{$starterPrice}}','True')">
                                                            <lable style="width:70%;" class="text-left"><strong>{{$thisType->emri}}</strong></lable>
                                                            <p style="width:30%;" class="text-right"> {{sprintf('%01.2f', ($vleraType * $starterPrice)) }}<sup>{{__('inc.currencyShow')}}</sup></p>       
                                                        </div>
                                                                    
                                                    </div> 
                                                </div>
                                                @endif
                                            @if($priType++ == 5)
                                                @break
                                            @endif
                                        @endif
                                    @endforeach
                                    @if(count(explode('--0--', $prod->type)) > 5)
                                        <p onclick="showTypeMenu('{{$prod->id}}')" class="text-left pl-5 hover-pointer threeDotsType{{$prod->id}}"
                                          style="font-size:25px; margin-top:-15px;"><span class="color-qrorpa" style="font-size:16px;">{{__('inc.more')}}</span> </p>
                                    @endif
                     
                                @endif
                            </div>
                            <div class="col-12 mt-2">
                                <?php
                                    $countNewTypes = 1;
                                       if($prod->type != NULL && count(explode('--0--', $prod->type)) > 0){
                                            foreach(explode('--0--', $prod->type) as $llP){
                                                if($countNewTypes++ > 5){
                                                    if(!empty($llP)){
                                                        $thisType = LlojetPro::find(explode('||',$llP)[0]);
                                                        if(!empty($thisType)){

                                                          
                                                            if(Carbon::now()->format('H:i') > $theRestaurant->secondPriceTime){
                                                                if($thisType->id == 106 && ($prod->id == 512 || $prod->id == 514)){
                                                                    $vleraType = number_format(1.4, 2, '.', '') ;;
                                                                }else  if($thisType->id == 107 && ($prod->id == 512 || $prod->id == 514)){
                                                                    $vleraType = number_format(2.6, 2, '.', '') ;;
                                                                }else{
                                                                    $vleraType = $thisType->vlera;
                                                                }
                                                            }else{
                                                                $vleraType = $thisType->vlera;
                                                            }
                                                     
                                                            $theIdOfThisType = "llojetP".$thisType->id."O".$prod->id;
                                                            echo '
                                                                <div class="color-text container AllTypesToHide IDType'.$prod->id.'">
                                                                    <div class="row ml-1">
                                                                        <div class="col-3 text-left">
                                                                            <label class="switch ">
                                                                                <input style="width:5px;" type="checkbox" class="primary allProTypesMenu allProTypes'.$prod->id.'" id="llojetP'.$thisType->id.'O'.$prod->id.'"
                                                                                    onchange="addThis2(\''.$theIdOfThisType.'\',\''.$prod->id.'\',\''.$thisType->emri.'\',\''.$vleraType.'\',\''.$starterPrice.'\',\'False\')">
                                                                                <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThis2(\''.$theIdOfThisType.'\',\''.$prod->id.'\',\''.$thisType->emri.'\',\''.$vleraType.'\',\''.$starterPrice.'\',\'True\')">
                                                                            <p style="width:70%;" class="text-left"><strong>'.$thisType->emri.'</strong></p>
                                                                            <p style="width:30%;" class="text-right"> '.sprintf('%01.2f', ($vleraType * $starterPrice)) .'<sup>'.__("inc.currencyShow").'</sup></p>
                                                                        </div>
                                                                    </div> 
                                                                </div>
                                                            ';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    ?>
                            </div>

                            <!-- Productet Extras -->
                            <div class="col-12 text-center">
                                @if(count(explode('--0--', $prod->extPro)) > 0)
                                    <?php $priExt = 1; ?>
                                    @foreach(explode('--0--', $prod->extPro) as $alll)
                                        @if($alll != '' || !empty($alll))
                                            @if(count(explode('--0--', $prod->extPro)) > 5)
                                                @if($priExt == 1)
                                                <hr>
                                                <p onclick="showExtraMenu('{{$prod->id}}')" class="hover-pointer color-qrorpa" 
                                                    style="margin-top:-10px; margin-bottom:-3px; font-size:larger;" >
                                                        <strong>{{__('inc.extras')}}</strong>
                                                </p>
                                                @endif
                                            @else
                                            @if($priExt == 1)
                                            <hr>
                                            <p class="hover-pointer color-qrorpa" style="margin-top:-10px; margin-bottom:-3px; font-size:larger;" >
                                                    <strong>{{__('inc.extras')}}</strong>
                                            </p>
                                            @endif
                                            @endif
                                            <?php $thisExtra = ekstra::find(explode('||',$alll)[0]); ?>
                                            @if(!empty($thisExtra))
                                                <div class="container">
                                                    <div class="row ml-1">
                                                        <div class="col-3 text-left">
                                                            <label class="switch">
                                                                <input style="width:5px;" type="checkbox" class="primary allProExtrasMenu allProExtras{{$prod->id}}" id="extPE{{$thisExtra->id}}O{{$prod->id}}" 
                                                                    onchange="addThis(this.id,'{{$prod->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','False')">
                                                                <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                            </label>
                                                        </div>
                                                        <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThis('extPE{{$thisExtra->id}}O{{$prod->id}}','{{$prod->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','True')">
                                                            <p style="width:70%;" class="text-left"><strong>{{$thisExtra->emri}}</strong> </p>
                                                            <p style="width:30%;" class="text-right">
                                                                <span class="price{{$prod->id}}"> {{sprintf('%01.2f', $thisExtra->qmimi)}}</span> <sup>{{__('inc.currencyShow')}}</sup>  
                                                            </p> 
                                                        </div>
                                                    </div> 
                                                </div>
                                            @endif
                                            @if($priExt++ == 5)
                                                @break
                                            @endif
                                        @endif
                                    @endforeach
                                    @if(count(explode('--0--', $prod->extPro)) > 5)
                                        <p onclick="showExtraMenu('{{$prod->id}}')" class="text-left hover-pointer threeDotsExt{{$prod->id}}"  style="font-size:25px; margin-top:-15px;"><span style="font-size:16px;">{{__('inc.more')}} . . .</span> </p>
                                    @endif
                                @endif
                            </div>
                            <div class="col-12">
                                @php
                                    $extras = explode('--0--', $prod->extPro);
                                    $countNewExt = 1;
                                @endphp
                                @foreach($extras as $extP)
                                    @if(!empty($extP))
                                        @if($countNewExt++ > 5)
                                            @php
                                            $thisExtra = ekstra::find(explode('||',$extP)[0]);
                                            @endphp
                                            @if(!empty($thisExtra))
                                                <div class="container AllExtrasToHide IDExtra{{$prod->id}}">
                                                    <div class="row ml-1">
                                                        <div class="col-3 text-left">
                                                            <label class="switch">
                                                                <input style="width:5px;" type="checkbox" class="primary allProExtrasMenu allProExtras{{$prod->id}}" id="extP{{$thisExtra->id}}O{{$prod->id}}" 
                                                                    onchange="addThis(this.id,'{{$prod->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','False')">
                                                                <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                            </label>
                                                        </div>
                                                        <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThis('extP{{$thisExtra->id}}O{{$prod->id}}','{{$prod->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','True')">
                                                            <p style="width:70%;" class="text-left"><strong>{{$thisExtra->emri}}</strong> </p>
                                                            <p style="width:30%;" class="text-right">
                                                                <span class="price{{$prod->id}}"> {{sprintf('%01.2f', $thisExtra->qmimi)}}</span> <sup>{{__('inc.currencyShow')}}</sup>  
                                                            </p> 
                                                        </div>
                                                    </div> 
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach

                            </div>
                        </div>



                        <div class="row">
                            <div class="col-12">
                                <?php

                                if(isset($_GET["Res"]) && isset($_GET['t'])){          
                                    $sendRes = $_GET["Res"];
                                    $sendT = $_GET["t"];
                                }
                                echo '
                              


                                <div class="form-group mt-4" style="margin-bottom: 6rem" >
                                    <textarea placeholder="'.__("inc.comment").'" name="koment" style="font-size:16px;"
                                    id="komentMenuAjax'.$prod->id.'" class="form-control" rows="3"></textarea>
                                </div>
                                   

                                    <input type="hidden" name="qmimiBaze" id="ProdAddQmimiBaze'.$prod->id.'" value="'.$starterPrice.'">

                                    <input type="hidden" name="id"  value="'.$prod->id.'">
                                    <input type="hidden" name="emri" id="ProdAddEmri'.$prod->id.'" value="'.$prod->emri.'">
                                    <input type="hidden" name="qmimi" id="ProdAddQmimi'.$prod->id.'" value="'.$starterPrice.'">
                                    <input type="hidden" name="pershkrimi" id="ProdAddPershk'.$prod->id.'" value="'.$prod->pershkrimi.'">
                                    <input type="hidden" name="extra" id="ProdAddExtra'.$prod->id.'" value="">
                                    <input type="hidden" name="llojet" id="ProdAddLlojet'.$prod->id.'" value="">
                                    <input type="hidden" name="kategoria" id="ProdAddKategoria'.$prod->id.'" value="'.$prod->kategoria.'">
                                    <input type="hidden" name="sasia" id="sasiaProd'.$prod->id.'" value="1">
                                    ';
                                    if(isset($_GET["Res"]) && isset($_GET['t'])){
                                        echo '<input type="hidden" name="Res" value="'.$sendRes.'">';
                                        echo '<input type="hidden" name="t" value="'.$sendT.'">';
                                    }
                                    echo '
                                    <button type="button" class="btn btn-block" 
                                    ';
                                    if($hasOneTVal == 0){
                                        echo ' data-dismiss="modal" '; 
                                    }
                                    echo '
                                        style="background-color:rgb(39,190,175); color:white; border-radius:30px; position:fixed; height:70px; width:80%; bottom:50px;
                                        right:10%; left:10%; font-size:22px;" id="sendOrderBtn'.$prod->id.'"
                                        onclick="sendNewOrder('.$prod->id.')">'.__("inc.addToCart").'</button>
                                    ';
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">


                </div>

            </div>
        </div>
    </div>
    @endforeach

    @endif
</div>
<script>
    var intervalTimerMenu ;
    function startNrVerifyTimer(){
        var timerStart = "5:00";
        $('#numberVerificationTimerVal').html(timerStart);

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
            $('#numberVerificationTimerVal').html(minutes + ':' + seconds);
            timerStart = minutes + ':' + seconds;
            if(minutes == 0 && seconds == 0)location.reload();
        }, 1000);
    }
    function refreshNumberVerModalResMenu(){
        $("#numberVerModalResMenu").load(location.href+" #numberVerModalResMenu>*","");
        clearInterval(intervalTimerMenu);
    }
</script>
<!-- Number verification modal -->
<div id="numberVerModalResMenu" class="modal mt-5">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 25px;">
            <!-- Modal Header -->
            <div class="modal-header" style="border-radius:25px 25px 0px 0px; background-color: rgb(39, 190, 175);">
                <h4 class="modal-title" style="color: white;"><strong>{{__('inc.text001')}}</strong></h4>
                <button type="button" class="close" data-dismiss="modal" style="color: white;" onclick="refreshNumberVerModalResMenu()"><strong>X</strong></button>
            </div>
            <input type="hidden" id="cartStoresaveTheProductId">
            <!-- Modal body -->
            <div class="modal-body">

                <div class="form-check" id="DtPrivatcyAcptDiv">
                    <label style="font-size: 12px;" class="form-check-label" onclick="clickDtPrivatcyAcpt()">
                        <input id="DtPrivatcyAcpt" type="checkbox" class="form-check-input">
                        Ich habe die <a href="{{route('firstPage.datenschutz')}}"><strong>Datenschutzbestimmungen</strong></a> zur Kenntnis genommen*
                    </label>
                </div>
                

                <p id="cartStoreP1PhoneNrCodeShowDemo"></p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="cartStoreP1PhoneNr" placeholder="{{__('inc.yourPhoneNumber')}}" style="box-shadow: inset 0 -1px 0 #ddd;">
                    <div class="input-group-append">
                        <button class="btn btn-danger" id="cartStoreP1getVerificationcodeBtn" onclick="cartStoreP1getVerificationcode()" type="submit" disabled>
                            {{__('inc.send')}}
                        </button>
                    </div>
                </div>

                <div class="mb-2 mt-2 p-2" id="cartStoreP1PhoneNrProductsImport" style="display: none; border:1px solid rgb(39,190,175); border-radius:10px;">
                    <p style="color: rgb(39,190,175); width:100%;" class="text-center"><strong>{{__('inc.unpaidProducts')}}</strong></p>
                    <div id="cartStoreP1PhoneNrProductsImportInfo" style="width:100%;" class="d-flex flex-wrap"></div>
                    <input type="hidden" id="cartStoreP1PhoneNrProductsUnpaid" value="empty">

                    <p style="color: red; width:100%; font-size:9px; display:none;" class="text-center" id="cartStoreP1PhoneNrProductsImportInfoMsg">
                        <i class="fas fa-exclamation-triangle" style="padding: 0px !important; margin-bottom:0px !important;"></i>
                          Wenn dies (T:?) vor dem Namen des Produkts angezeigt wird, bedeutet dies,
                        dass dieses Produkt in einer anderen Tabelle registriert ist und in diese Tabelle übertragen wird!
                    </p>
                </div>

                <div class="input-group mb-3" id="cartStoreP1PhoneNrCodeWrite" style="display: none;">
                    <input type="hidden" class="form-control" id="cartStoreP1PhoneNrCode">
                    <input type="hidden" class="form-control" id="cartStoreP1TimeStarted">
                    <input type="text" class="form-control" id="cartStoreP1PhoneNrCodeUser" placeholder="{{__('inc.verificationKey')}}" style="box-shadow: inset 0 -1px 0 #ddd;">
                    <div class="input-group-append">
                        <?php 
                            if(isset($_SESSION['adminToClProdsRec'])){ $sgv = $_SESSION['adminToClProdsRec']; }
                            else{ $sgv = '0';}
                        ?>
                        <button class="btn btn-success" onclick="cartStoreP2VerifyTheCode('{{$sgv}}')" type="submit">{{__('inc.check')}}</button>
                    </div>
                    @if (Auth::check() && Auth::user()->phoneNr == 'empty' ) 
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" value="" id="regPhNrToAc">
                            <label class="form-check-label" for="regPhNrToAc" style="font-weight: bold;">
                                Register this phone number to my account
                            </label>
                        </div>
                    @endif
                </div>
                <p style="display:none;" id="numberVerificationTimer">{{__('inc.timer')}}<span id="numberVerificationTimerVal"></span></p>
    
                <div id="cartStoreP1getVerificationcodeError" class="alert alert-danger" style="font-weight: bold; display:none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Number verification modal Product2+ -->
<div id="numberVerModalResMenu2" class="modal mt-5">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color: rgb(39, 190, 175);">
                <h4 class="modal-title" style="color: white;"><strong>{{__('inc.text002')}}</strong></h4>
                <button type="button" class="close" data-dismiss="modal" style="color: white;"><strong>X</strong></button>
            </div>
            <input type="hidden" id="cartStoresaveTheProductId2">
            <div class="modal-body">
                <?php $tabCodeNrVer = TableQrcode::where([['Restaurant',$_GET['Res']],['tableNr',$_GET['t']]])->first()->kaTab; ?>
                @if($tabCodeNrVer != 0)
                    <?php $PNShown = array(); ?>
                    @foreach (tabVerificationPNumbers::where([['tabCode',$tabCodeNrVer],['status','1']])->get() as $actNrs )
                        @if(!in_array($actNrs->phoneNr,$PNShown))
                            <button class="btn btn-block btn-outline-dark mb-2 text-center" style="font-size: 23px;"
                                onclick="sendSecondOrderToProcess('{{$actNrs->phoneNr}}')">+41 *** *{{substr($actNrs->phoneNr, -6)}}
                            </button>
                            <?php array_push($PNShown,$actNrs->phoneNr);?>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function clickDtPrivatcyAcpt(){
        if($('#DtPrivatcyAcpt').is(":checked")){
            $('#cartStoreP1getVerificationcodeBtn').attr('class','btn btn-success');
            $('#cartStoreP1getVerificationcodeBtn').prop("disabled", false );
        }else{
            $('#cartStoreP1getVerificationcodeBtn').attr('class','btn btn-danger');
            $('#cartStoreP1getVerificationcodeBtn').prop("disabled", true );
        }
    }

    function sendNewOrder(pi) {
        if( $('#hasTypeThisPro'+pi).val() == 1 && $('#ProdAddLlojet' + pi).val() == ''){
            // Nuk eshte selektuar tipi , nese ka 
            $('#addTypePlease').show(200).delay(2500).hide(200);
        }else if($('#hasTypeThisPro'+pi).val() == 0 || $('#ProdAddLlojet' + pi).val() != ''){
            $.ajax({
                url: '{{ route("cart.storeP1") }}',
                method: 'post',
                data: {
                    id: pi,
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    if(res['typeAns'] == 'first'){
                        $('#numberVerModalResMenu').modal('toggle');
                        $('#cartStoresaveTheProductId').val(res['pId']);
                    }else{
                        //porosia 2+  
                        // $('#numberVerModalResMenu2').modal('toggle');
                        $('#cartStoresaveTheProductId2').val(res['pId']); 
                        this.sendSecondOrderToProcess(res['phoneNr']);
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    }


    // Send the phone number and get the CODE back 
    function cartStoreP1getVerificationcode(){
        var pNr = $('#cartStoreP1PhoneNr').val().replace(/ /g,'');
        
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
            $('#cartStoreP1getVerificationcodeError').html($('#INCwritePhoneNr').val()); $('#cartStoreP1getVerificationcodeError').show(200).delay(3000).hide(200);
        }else if(pNr.length < 9 || pNr.length > 10){
            $('#cartStoreP1getVerificationcodeError').html($('#INCphoneNrNotAccepted').val()); $('#cartStoreP1getVerificationcodeError').show(200).delay(3000).hide(200);
        }else{
            $.ajax({
                url: '{{ route("cart.storeP2") }}',
                method: 'post',
                data: { phoneNr: pNr, pID: $('#cartStoresaveTheProductId').val(), _token: '{{csrf_token()}}' },
                success: (res) => {
                    if(res['status'] == 'success'){

                        $('#DtPrivatcyAcptDiv').hide(250);

                        // cartStoreP1getVerificationcodeBtn
                        $('#cartStoreP1getVerificationcodeBtn').attr('disabled',true);

                        $('#cartStoreP1PhoneNrCode').val(res['code']);
                        $('#cartStoreP1TimeStarted').val(res['timeStart']);
                        $('#cartStoreP1PhoneNrCodeWrite').show(500);
                        $('#numberVerificationTimer').show(500);
                        this.startNrVerifyTimer();
                        $('#cartStoresaveTheProductId').val(res['pID']);
                        if(pNr == '763270293' || pNr == '0763270293' || pNr == '763251809' || pNr == '0763251809' || pNr == '763459941' || pNr == '0763459941' || pNr == '763469963' || pNr == '0763469963' || pNr == '760000000' || pNr == '0760000000'){
                            $('#cartStoreP1PhoneNrCodeShowDemo').html('Demo-Code: '+res['code']);
                        }else{
                            $('#cartStoreP1PhoneNrCodeShowDemo').hide(5);
                        }

                        var hasOtherTableOrders = false;
                        if(res['unpaid'] != 'empty'){
                            var unpaid =(res['unpaid']).split('--8--');
                            $('#cartStoreP1PhoneNrProductsImport').show();
                            var unpaidShow = unpaid[1].split('-8-');
                            var thisTableNr = $('#thisTable').val();
                            $.each( unpaidShow, function( index, value ) {
                                var unpaindOnePrShow = value.split('||');
                                if(unpaindOnePrShow[2] != thisTableNr){
                                    hasOtherTableOrders = true;
                                    $('#cartStoreP1PhoneNrProductsImportInfo').append("<p style='width:75%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;'><span style='color:red;'>T:"+unpaindOnePrShow[2]+"</span> "+unpaindOnePrShow[0]+"</p>");
                                    $('#cartStoreP1PhoneNrProductsImportInfo').append("<p style='width:25%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;' class='text-right'>"+unpaindOnePrShow[1]+" "+$('#price_lang').val()+"</p>");
                                }else{
                                    $('#cartStoreP1PhoneNrProductsImportInfo').append("<p style='width:75%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;'>"+unpaindOnePrShow[0]+"</p>");
                                    $('#cartStoreP1PhoneNrProductsImportInfo').append("<p style='width:25%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;' class='text-right'>"+unpaindOnePrShow[1]+" "+$('#price_lang').val()+"</p>");
                                }                            
                            });
                            $('#cartStoreP1PhoneNrProductsUnpaid').val(unpaid[0]);
                        }
                        if(hasOtherTableOrders){ $('#cartStoreP1PhoneNrProductsImportInfoMsg').show(300); }
                        else{ $('#cartStoreP1PhoneNrProductsImportInfoMsg').hide(1); }
                    }else{
                        $('#cartStoreP1getVerificationcodeError').html($('#INCphoneNrNotAccepted').val());
                        $('#cartStoreP1getVerificationcodeError').show(200).delay(3000).hide(200);
                    }
                },
                error: (error) => { console.log(error);}
            });
        }
    }

    // Send the code for verification 
    function cartStoreP2VerifyTheCode(gv){
        var pNr = $('#cartStoreP1PhoneNr').val().replace(/ /g,'');
       

        if(pNr != ''){
            if(pNr[0] == '+' && pNr[1] == 4 && (pNr[2] == 1 || pNr[2] == 9) && pNr[3] == 7 && pNr.length == 12){
                pNr = '0'+pNr.toString().slice(3);
            }else if(pNr[0] == 4 && (pNr[1] == 1 || pNr[1] == 9) && pNr[2] == 7 && pNr.length == 11){
                pNr = '0'+pNr.toString().slice(2);
            }else if(pNr[0] == 0 && pNr[1] == 0 && pNr[2] == 4 && (pNr[3] == 1 || pNr[3] == 9) && pNr[4] == 7 && pNr.length == 13){
                pNr = '0'+pNr.toString().slice(4);
            }
        }
        var pi = $('#cartStoresaveTheProductId').val();
        if( $('#cartStoreP1PhoneNrCodeUser').val() == ''){
            $('#cartStoreP1getVerificationcodeError').html($('#INCwriteTheCode').val());
            $('#cartStoreP1getVerificationcodeError').show(200).delay(3000).hide(200); 
        }else if($('#cartStoreP1PhoneNrCodeUser').val().length != 6){
            $('#cartStoreP1getVerificationcodeError').html($('#INCcodeNotAccepted').val());
            $('#cartStoreP1getVerificationcodeError').show(200).delay(3000).hide(200);
        }else{
            if($('#regPhNrToAc').length && $("#regPhNrToAc").is(':checked')){ var regThisNrToAc = 'Yes';
            }else{ var regThisNrToAc = 'No'; }

            $.ajax({
                url: '{{ route("cart.store") }}',
                method: 'post',
                data: { code:  $('#cartStoreP1PhoneNrCode').val(),
                        codeUser: $('#cartStoreP1PhoneNrCodeUser').val(),
                        timeStart: $('#cartStoreP1TimeStarted').val(),
                        id: pi,
                        emri: $('#ProdAddEmri' + pi).val(),
                        qmimi: parseFloat($('#ProdAddQmimi' + pi).val()),
                        pershkrimi: $('#ProdAddPershk' + pi).val(),
                        extra: $('#ProdAddExtra' + pi).val(),
                        llojet: $('#ProdAddLlojet' + pi).val(),
                        koment: $('#komentMenuAjax' + pi).val(),
                        kategoria: $('#ProdAddKategoria' + pi).val(),
                        res: $('#thisRestaurant').val(),
                        t: $('#thisTable').val(),
                        sas: $('#sasiaProd'+pi).val(),
                        phoneNr: pNr,
                        unpaid: $('#cartStoreP1PhoneNrProductsUnpaid').val(),
                        ghostPay: gv,
                        regNrToUsr: regThisNrToAc,
                        _token: '{{csrf_token()}}' },
                success: (res) => {
                    // console.log(res);
                    if(typeof res['status'] !== 'undefined' || res['status'] == 'success'){

                        $("#orderprotONBTN").load(location.href+" #orderprotONBTN>*","");
                        // $("#orderprot").load(location.href+" #orderprot>*","");
                        
                        $('#CartCountFooter').text(parseInt($('#CartCountFooter').text()) + 1);
                        var pricess = $('#CartTotalFooter2').text();
                        var pricessCH = $('#CartTotalFooter2CH').text();
                        var pricessT = parseFloat(pricess) + parseFloat($('#ProdAddQmimi' + pi).val());
                        var pricessTCH = parseFloat(pricessCH) + parseFloat($('#ProdAddQmimi' + pi).val());
                        
                        $('#CartTotalFooter2').text(pricessT.toFixed(2));
                        $('#CartTotalFooter2CH').text(pricessTCH.toFixed(2));
                        $('.AllExtrasOrder').hide();
                        // price'.$prod->id.'
                        // Deselect extras,types
                        $(".allProExtras" + pi).prop("checked", false);
                        $(".allProTypes" + pi).prop("checked", false);

                        // Hide extras,types
                        $(".AllExtrasToHide").hide();
                        $(".AllTypesToHide").hide();

                        // reset price
                        var firstPr = parseFloat($('#ProdAddQmimiBaze' + pi).val());
                        $('#TotPrice' + pi).val(firstPr.toFixed(2));

                        var prices = document.getElementsByClassName("price" + pi);
                        for (var i = 0; i < prices.length; i++) {
                            if (lastV != 0) {
                                var newV = parseFloat(prices.item(i).innerText) / lastV;

                                prices.item(i).innerText = newV.toFixed(2);
                            }
                        }
                        $('#ProdAddExtra' + pi).val("");
                        $('#ProdAddLlojet' + pi).val("");
                        if (lastV != 0) {
                            $('#ProdAddQmimi' + pi).val(parseFloat($('#ProdAddQmimi' + pi).val()) / lastV);
                        }
                    
                        $('.orderCountTop').text($('#CartCountFooter').text());

                        last = "";
                        lastV = 0;
                        // console.log($('#CartCountFooter').text());
                        if($('#CartCountFooter').text() == 1 || $('#CartCountFooter').text() == ''){
                            $('#orderGettingReadyFirst').modal('toggle');
                        }else{
                            $('#orderGettingReady').show(300).delay(4000).hide(300);
                        }

                       

                        // reset the PNumber verifyer 
                        $('#numberVerModalResMenu').modal('toggle');
                        $("#numberVerModalResMenu").load(location.href+" #numberVerModalResMenu>*","");
                        // $("#numberVerModalResMenuRec2").load(location.href+" #numberVerModalResMenuRec2>*","");
                        // $("#numberVerModalResMenu2").load(location.href+" #numberVerModalResMenu2>*","");

                        $("#numriNeSession").load(location.href+" #numriNeSession>*","");

                    }else{
                        if(res['status'] == 'failCode'){
                            $('#cartStoreP1PhoneNrCode').val(res['code']);
                            $('#cartStoreP1TimeStarted').val(res['timeStart']);
                            $('#cartStoresaveTheProductId').val(res['pID']);

                            $('#cartStoreP1PhoneNrCodeUser').val(''),

                            $('#cartStoreP1getVerificationcodeError').html($('#INCtext003').val());
                            $('#cartStoreP1getVerificationcodeError').show(200).delay(3000).hide(200);
                        }else if(res['status'] == 'failTime'){

                            $('#cartStoresaveTheProductId').val(res['pID']);
                            
                            $("#cartStoreP1PhoneNrCodeWrite").load(location.href+" #cartStoreP1PhoneNrCodeWrite>*","");
                            $('#cartStoreP1PhoneNrCodeWrite').hide();

                            $('#cartStoreP1getVerificationcodeError').html($('#INCcodeFalse').val());
                            $('#cartStoreP1getVerificationcodeError').show(200).delay(3000).hide(200);
                        }
                    }
                },
                error: (error) => { console.log(error);}
            });
        }
    }

    function sendSecondOrderToProcess(phoneN){
        var pi = $('#cartStoresaveTheProductId2').val();
            $.ajax({
                url: '{{ route("cart.store2Plus") }}',
                method: 'post',
                data: {
                        id: pi,
                        emri: $('#ProdAddEmri' + pi).val(),
                        qmimi: parseFloat($('#ProdAddQmimi' + pi).val()),
                        pershkrimi: $('#ProdAddPershk' + pi).val(),
                        extra: $('#ProdAddExtra' + pi).val(),
                        llojet: $('#ProdAddLlojet' + pi).val(),
                        koment: $('#komentMenuAjax' + pi).val(),
                        kategoria: $('#ProdAddKategoria' + pi).val(),
                        res: $('#thisRestaurant').val(),
                        t: $('#thisTable').val(),
                        sas: $('#sasiaProd'+pi).val(),
                        phoneNr: phoneN,
                        _token: '{{csrf_token()}}' },
                success: (res) => {
                    $("#orderprotONBTN").load(location.href+" #orderprotONBTN>*","");
                        // $("#orderprot").load(location.href+" #orderprot>*","");
                        
                        $('#CartCountFooter').text(parseInt($('#CartCountFooter').text()) + 1);
                        var pricess = $('#CartTotalFooter2').text();
                        var pricessCH = $('#CartTotalFooter2CH').text();
                        var pricessT = parseFloat(pricess) + parseFloat($('#ProdAddQmimi' + pi).val());
                        var pricessTCH = parseFloat(pricessCH) + parseFloat($('#ProdAddQmimi' + pi).val());
                        
                        $('#CartTotalFooter2').text(pricessT.toFixed(2));
                        $('#CartTotalFooter2CH').text(pricessTCH.toFixed(2));
                        $('.AllExtrasOrder').hide();
                        // price'.$prod->id.'
                        // Deselect extras,types
                        $(".allProExtras" + pi).prop("checked", false);
                        $(".allProTypes" + pi).prop("checked", false);

                        // Hide extras,types
                        $(".AllExtrasToHide").hide();
                        $(".AllTypesToHide").hide();

                        // reset price
                        var firstPr = parseFloat($('#ProdAddQmimiBaze' + pi).val());
                        $('#TotPrice' + pi).val(firstPr.toFixed(2));

                        var prices = document.getElementsByClassName("price" + pi);
                        for (var i = 0; i < prices.length; i++) {
                            if (lastV != 0) {
                                var newV = parseFloat(prices.item(i).innerText) / lastV;

                                prices.item(i).innerText = newV.toFixed(2);
                            }
                        }
                        $('#ProdAddExtra' + pi).val("");
                        $('#ProdAddLlojet' + pi).val("");
                        if (lastV != 0) {
                            $('#ProdAddQmimi' + pi).val(parseFloat($('#ProdAddQmimi' + pi).val()) / lastV);
                        }
                    
                        $('.orderCountTop').text($('#CartCountFooter').text());

                        last = "";
                        lastV = 0;

                        console.log($('#CartCountFooter').text());
                        if($('#CartCountFooter').text() == 1 || $('#CartCountFooter').text() == ''){
                            $('#orderGettingReadyFirst').modal('toggle');
                        }else{
                            $('#orderGettingReady').show(300).delay(4000).hide(300);
                        }

                        // reset the PNumber verifyer 
                        // $('#numberVerModalResMenu2').modal('toggle');
                        $("#numberVerModalResMenu").load(location.href+" #numberVerModalResMenu>*","");
                        // $("#numberVerModalResMenu2").load(location.href+" #numberVerModalResMenu2>*","");

                       
                },
                error: (error) => { console.log(error);}
            });
    }
</script>





<div id="addTypePlease" style="top:12%; width:100%; position:fixed; z-index:100000; display:none;">
    <div class="text-center" style="background-color:rgb(39, 190, 175); color:white;  padding-top:15px; padding-bottom:15px; border-radius:9px;">
        {{__('inc.selectType')}}
    </div>
</div>



<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<script>


    function addOneToSasiaPro(pId){
        var cVal = parseInt($('#sasiaPerProd'+pId).val());
        var newVal = cVal + 1;
        $('#sasiaPerProd'+pId).val(newVal);
        $('#sasiaProd'+pId).val(newVal);
        $('#minusSasiaPerProd'+pId).show();
        $('#placeholderSasiaPerProd'+pId).hide();
        
    }
    function removeOneToSasiaPro(pId){
        var cVal = parseInt($('#sasiaPerProd'+pId).val());
        var newVal = cVal - 1;
        $('#sasiaPerProd'+pId).val(newVal);
        $('#sasiaProd'+pId).val(newVal);
        if(newVal == 1){
            $('#minusSasiaPerProd'+pId).hide();
            $('#placeholderSasiaPerProd'+pId).show();
        }else{
            $('#minusSasiaPerProd'+pId).show();
            $('#placeholderSasiaPerProd'+pId).hide();
        }
        
    }




$(document).ready(function() {

    $('.AllExtrasToHide').hide();
    $('.AllTypesToHide').hide();
});



function showExtraMenu(prodId) {
    if ($('.IDExtra' + prodId).is(":visible")) {
        $('.IDExtra' + prodId).hide();
        $('.threeDotsExt' + prodId).show();
    } else {
        $('.IDExtra' + prodId).show();
        $('.threeDotsExt' + prodId).hide();
    }
}

function showTypeMenu(prodId) {
    if ($('.IDType' + prodId).is(":visible")) {
        $('.IDType' + prodId).hide();
        $('.threeDotsType'+prodId).show();
    } else {
        $('.IDType' + prodId).show();
        $('.threeDotsType'+prodId).hide();
    }
}
</script>































































<!-- The Modal Empfohlene products -->
@foreach(RecomendetProd::all() as $RePro)
<div class="modal" id="RecProd{{$RePro->id}}">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:30px;">
            <?php
                $ProduktiRec = Produktet::find($RePro->produkti);  
            ?>
                @if(Carbon::now()->format('H:i') >= $theRestaurant->secondPriceTime || Carbon::now()->format('H:i') <= '03:00')
                    @if($RePro->qmimi2 != 999999)
                    <?php
                        $starterPricePro = sprintf('%01.2f', $RePro->qmimi2);
                    ?>
                    @else
                    <?php
                        $starterPricePro = sprintf('%01.2f', $RePro->qmimi);
                    ?>
                    @endif
                @else
                    <?php
                        $starterPricePro = sprintf('%01.2f', $RePro->qmimi);
                    ?>
                @endif
            <!-- Modal body -->
            <div class="modal-body">

                <div class="container">
                    <div class="row">
            
                        <div class="col-10">
                                <h4 class="modal-title">{{$ProduktiRec->emri}} <span style="color:lightgray;">
                                        ({{kategori::find($ProduktiRec->kategoria)->emri}})</span></h4>
                            </div>
                        <div class="col-2 text-right">
                            <button type="button" class="btn" data-dismiss="modal">
                                <i onclick="prodModalCancel('{{$ProduktiRec->id}}','{{$starterPricePro}}')" style="width:6px;" class="far fa-times-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 ">
                            <p>{{$ProduktiRec->pershkrimi}}</p>
                        </div>
                    </div>

                                    
                    <div class="row">
                        <div class="col-2 text-right mt-3">
                            <span class=" opacity-65">{{__('inc.currencyShow')}}</span>
                        </div>
                        <div class="col-4 text-left">
                            <input class="form-control color-qrorpa" style="border:none; font-size:20px;"
                                id="TotPriceRec{{$ProduktiRec->id}}" type="number" step="0.01"
                                value="{{$starterPricePro}}" disabled>
                        </div>
                        <div class="col-6 text-right">
                                <div class="text-right d-flex">
                                    <span id="minusSasiaPerProdRec{{$ProduktiRec->id}}" style="font-size:30px; display:none; width:20%;" class="pr-3 plusForOrder"
                                     onclick="removeOneToSasiaProRec('{{$ProduktiRec->id}}')" >-</span>
                                     <span id="placeholderSasiaPerProdRec{{$ProduktiRec->id}}" style="font-size:30px; color:white; width:20%;" class="pr-3 plusForOrder"
                                      >-</span>
                                    <input type="number" min="1" step="1" value="1" class="text-center" id="sasiaPerProdRec{{$ProduktiRec->id}}"
                                        style="border:none; width:60%; font-size:28px; height:fit-content;" disabled>
                                    <span style="font-size:30px; width:20%;" class="pl-3 plusForOrder" onclick="addOneToSasiaProRec('{{$ProduktiRec->id}}')">+</span>
                                </div>
                        </div>
                    </div>




                                @if(count(explode('--0--', $ProduktiRec->type)) > 0 && $ProduktiRec->type != NULL)
                                    @foreach(explode('--0--', $ProduktiRec->type) as $alll)
                                        @if($alll != '' && $alll != NULL)
                                            <?php $hasOneTRec = true; ?>
                                        @endif
                                    @endforeach


                                    @if(isset($hasOneTRec) && $hasOneTRec)
                                        <?php $hasOneTValRec = 1; ?>
                                    @else
                                        <?php $hasOneTValRec = 0; ?>
                                    @endif
                                @else
                                    <?php $hasOneTValRec = 0; ?>
                                @endif
                                <input type="hidden" value="{{$hasOneTValRec}}" id="hasTypeThisProRec{{$ProduktiRec->id}}">


                    <div class="row modal-row" style=" width:130%; margin-left:-15%;">
                    <div class="col-12 text-center" >
                        @if($ProduktiRec->type != NULL && count(explode('--0--', $ProduktiRec->type)) > 0)
                                    @if(count(explode('--0--', $ProduktiRec->type)) > 5)
                                            @if(isset($_GET['Res']) && $_GET['Res'] == 16)
                                                <p onclick="showTypeMenuRec('{{$RePro->id}}')" class="hover-pointer"><strong>{{__('inc.taste')}}</strong></p> 
                                            @else
                                                <p onclick="showTypeMenuRec('{{$RePro->id}}')" class="hover-pointer color-qrorpa mt-2"
                                                style="margin-top:-10px; margin-bottom:-3px; font-size:larger;">
                                                <strong>{{__('inc.type')}}</strong></p>
                                            @endif
                                    @elseif(count(explode('--0--', $ProduktiRec->type)) > 0)
                                        <p class="hover-pointer color-qrorpa mt-2" style="margin-top:-10px; margin-bottom:-3px; font-size:larger;">
                                        <strong>{{__('inc.type')}}</strong></p>
                                        <hr>
                                    @endif
                        <?php $priTypeRec = 1; ?>
                            @foreach(explode('--0--', $ProduktiRec->type) as $alll)
                                @if($alll != '')

                                    <?php $thisType = LlojetPro::find(explode('||',$alll)[0]); ?>
                                                        <div class="container-fluid">
                                                            <div class="row">
                                                                <div class="col-3 text-left">
                                                                    <label class="switch ">
                                                                        <input style="width:5px;" type="checkbox" class="primary allProTypesRecMenu allProTypesRec{{$ProduktiRec->id}}" id="llojetPRecE{{$thisType->id}}O{{$ProduktiRec->id}}"
                                                                            onchange="addThis2Rec(this.id,'{{$ProduktiRec->id}}','{{$thisType->emri}}','{{$thisType->vlera}}','{{$starterPricePro}}','False')" >
                                                                        <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 d-flex pl-1" style="margin-left:-35px;" onclick="addThis2Rec('llojetPRecE{{$thisType->id}}O{{$ProduktiRec->id}}','{{$ProduktiRec->id}}','{{$thisType->emri}}','{{$thisType->vlera}}','{{$starterPricePro}}','True')">
                                                                    <p style="width:70%;" class="text-left"><strong>{{$thisType->emri}}</strong></p>
                                                                    <p style="width:30%;" class="text-right"> 
                                                                    {{sprintf('%01.2f', ($thisType->vlera * $starterPricePro)) }} <sup>{{__('inc.currencyShow')}}</sup> </p>
                                                                </div>
                                                            </div> 
                                                        </div>
                                    @if($priTypeRec++ == 5)
                                        @break
                                    @endif
                                @endif
                            @endforeach
                            @if(count(explode('--0--', $ProduktiRec->type)) > 5)
                                <p onclick="showTypeMenu('{{$RePro->id}}')" class="text-left pl-5 hover-pointer threeDotsTypeRec{{$RePro->id}}"  style="font-size:25px; margin-top:-15px;"><span style="font-size:16px;">{{__('inc.more')}}</span> </p>
                            @endif

                        @endif
                    </div>
                   
                    <div class="col-12 ">
                        <?php
                                        $llProR = explode('--0--', $ProduktiRec->type);
                                        $countNewTypesRec = 1;
                                        foreach($llProR as $llP){
                                            if(!empty($llP)){
                                                if($countNewTypesRec++ > 5){
                                                    $thisType = LlojetPro::find(explode('||',$llP)[0]);

                                                 
                                                    if(!empty($thisType)){
                                                        $theIdOfThisTypeRec = "llojetP".$thisType->id."O".$RePro->id;
                                                        echo '
                                                            <div class="container-fluid RecAllTypesToHide RecIDType'.$RePro->id.'">
                                                                <div class="row">
                                                                    <div class="col-3 text-left">
                                                                        <label class="switch ">
                                                                            <input style="width:5px;" type="checkbox" class="primary allProTypesRecMenu allProTypesRec'.$ProduktiRec->id.'" id="llojetPRec'.$thisType->id.'O'.$ProduktiRec->id.'"
                                                                                onchange="addThis2Rec(this.id,\''.$ProduktiRec->id.'\',\''.$thisType->emri.'\',\''.$thisType->vlera.'\',\''.$starterPricePro.'\',\'False\')">
                                                                            <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThis2Rec(\''.$theIdOfThisTypeRec.'\',\''.$ProduktiRec->id.'\',\''.$thisType->emri.'\',\''.$thisType->vlera.'\',\''.$starterPricePro.'\',\'True\')">
                                                                        <p style="width:70%;" class="text-left"><strong>'.$thisType->emri.'</strong></p>
                                                                        <p style="width:30%;" class="text-right">  '.sprintf('%01.2f', ($thisType->vlera * $starterPricePro)) .' <sup>'.__("inc.currencyShow").'</sup> </p>
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                        ';
                                                    }
                                                }
                                            }
                                        }
                                        // foreach(LlojetPro::where('kategoria', '=', $ProduktiRec->kategoria)->get() as $llojPro){
                                           
                                        // }
                                    ?>
                    </div>
                    </div>
                </div>
                <div class="row modal-row" style=" width:130%; margin-left:-15%;">
                    <div class="col-12 text-center">
                        @if(count(explode('--0--', $ProduktiRec->extPro)) > 0)
                            <?php $priExtRec = 1; ?>
                            @foreach(explode('--0--', $ProduktiRec->extPro) as $alll)
                                @if($alll != '' || !empty($alll))
                                    @if(count(explode('--0--', $ProduktiRec->extPro)) > 5)
                                        @if($priExtRec == 1)
                                            <hr>
                                            <p onclick="showExtraMenuRec('{{$RePro->id}}')" class="hover-pointer color-qrorpa" 
                                                style="margin-top:-10px; margin-bottom:-3px; font-size:larger;" >
                                                    <strong>{{__('inc.extras')}}</strong>
                                            </p>
                                        @endif
                                    @else
                                        <hr>
                                        <p onclick="showExtraMenuRec('{{$RePro->id}}')" class="hover-pointer color-qrorpa" 
                                            style="margin-top:-10px; margin-bottom:-3px; font-size:larger;" >
                                                <strong>{{__('inc.extras')}}</strong>
                                        </p>
                                    @endif
                                       
                                    <?php $thisExtra = ekstra::find(explode('||',$alll)[0]); ?>
                                    @if(!empty($thisExtra))
                                        <div class="container">
                                            <div class="row ml-1">
                                                <div class="col-3 text-left">
                                                    <label class="switch">
                                                        <input style="width:5px;" type="checkbox" class="primary allProExtrasRecMenu allProExtrasRec{{$ProduktiRec->id}}" id="extPRecE{{$thisExtra->id}}O{{$ProduktiRec->id}}" 
                                                             onchange="addThisRec(this.id,'{{$ProduktiRec->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','False')">
                                                        <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                    </label>
                                                </div>
                                                <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThisRec('extPRecE{{$thisExtra->id}}O{{$ProduktiRec->id}}','{{$ProduktiRec->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','True')">
                                                    <p style="width:70%;" class="text-left"><strong>{{$thisExtra->emri}}</strong> </p>
                                                    <p style="width:30%;" class="text-right recommended-extra">
                                                        <span class="priceRec{{$ProduktiRec->id}}"> {{sprintf('%01.2f', $thisExtra->qmimi)}}</span> <sup>{{__('inc.currencyShow')}}</sup>  
                                                    </p> 
                                                </div>
                                            </div> 
                                        </div>
                                    @endif
                                    @if($priExtRec++ == 5)
                                        @break
                                    @endif
                                @endif
                            @endforeach
                            @if(count(explode('--0--', $ProduktiRec->extPro)) > 5)
                                <p onclick="showExtraMenuRec('{{$RePro->id}}')" class="text-left hover-pointer threeDotsExtRec{{$RePro->id}}"  style="font-size:25px; margin-top:-15px;"><span style="font-size:16px;">{{__('inc.more')}}</span> </p>
                            @endif
                        @endif
                    </div>
                    <div class="col-12">
                        <?php
                                        $extrasR = explode('--0--', $ProduktiRec->extPro);
                                        $countNewExtRec = 1;
                                        foreach($extrasR as $extP){
                                            if(!empty($extP)){
                                                if($countNewExtRec++ > 5){
                                                    $thisExtra = ekstra::find(explode('||',$extP)[0]);

                                                    
                                                    if(!empty($thisExtra)){
                                                        $theIdOfThisExtraRec = "extPRec".$thisExtra->id."O".$ProduktiRec->id;
                                                        echo '
                                                            <div class="container RecAllExtrasToHide RecIDExtra'.$RePro->id.'">
                                                                <div class="row ml-1">
                                                                    <div class="col-3 text-left">
                                                                        <label class="switch">
                                                                            <input style="width:5px;" type="checkbox" class="primary allProExtrasRecMenu allProExtrasRec'.$ProduktiRec->id.'" id="extPRec'.$thisExtra->id.'O'.$ProduktiRec->id.'" 
                                                                                onchange="addThisRec(this.id,\''.$ProduktiRec->id.'\',\''.$thisExtra->emri.'\',\''.$thisExtra->qmimi.'\',\'False\' )">
                                                                            <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThisRec(\''.$theIdOfThisExtraRec.'\',\''.$ProduktiRec->id.'\',\''.$thisExtra->emri.'\',\''.$thisExtra->qmimi.'\',\'True\' )">
                                                                        <p style="width:70%;" class="text-left"><strong>'.$thisExtra->emri.'</strong> </p>
                                                                        <p style="width:30%;" class="text-right recommended-extra">
                                                                            <span class="priceRec'.$ProduktiRec->id.'"> '.sprintf('%01.2f', $thisExtra->qmimi).'</span> <sup>'.__("inc.currencyShow").'</sup>  
                                                                        </p> 
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                        ';
                                                    }
                                                }
                                            }
                                        }
                                    ?>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <?php
                                        if(isset($_GET["Res"]) && isset($_GET['t'])){          
                                            $sendRes = $_GET["Res"];
                                            $sendT = $_GET["t"];
                                        }
                                        echo '
                                        <form action="'.route('cart.store').'" method="POST">
                                      
                                            <div class="form-group"  style="margin-bottom: 6rem">
                                                <textarea placeholder="'.__("inc.comment").'" name="komentRec" style="font-size:16px;"
                                                id="komentMenuAjaxRec'.$ProduktiRec->id.'" class="form-control" rows="3"></textarea>
                                            </div>
                                            '.
                                            csrf_field()
                                            .'

                                            <input type="hidden" name="qmimiBazeRec" id="ProdAddQmimiRecBaze'.$ProduktiRec->id.'" value="'.$starterPricePro.'">

                                            <input type="hidden" name="id"  value="'.$ProduktiRec->id.'">
                                            <input type="hidden" name="emri" id="ProdAddEmriRec'.$ProduktiRec->id.'" value="'.$ProduktiRec->emri.'">
                                            <input type="hidden" name="qmimi" id="ProdAddQmimiRec'.$ProduktiRec->id.'" value="'.$starterPricePro.'">
                                            <input type="hidden" name="pershkrimi" id="ProdAddPershkRec'.$ProduktiRec->id.'" value="'.$ProduktiRec->pershkrimi.'">
                                            <input type="hidden" name="extra" id="ProdAddExtraRec'.$ProduktiRec->id.'" value="">
                                            <input type="hidden" name="llojet" id="ProdAddLlojetRec'.$ProduktiRec->id.'" value="">
                                            <input type="hidden" name="kategoria" id="ProdAddKategoriaRec'.$ProduktiRec->id.'" value="'.$ProduktiRec->kategoria.'">
                                            <input type="hidden" name="sasia" id="sasiaProdRec'.$ProduktiRec->id.'" value="1">
                                            ';
                                            if(isset($_GET["Res"]) && isset($_GET['t'])){
                                                echo '<input type="hidden" name="Res" value="'.$sendRes.'">';
                                                echo '<input type="hidden" name="t" value="'.$sendT.'">';
                                            }
                                            echo '
                                        
                                            <button type="button" onclick="sendNewOrderRec('.$ProduktiRec->id.')"
                                            ';
                                            if($hasOneTValRec == 0){
                                                echo ' data-dismiss="modal" '; 
                                            }
                                            echo '
                                            class="btn btn-block" id="sendOrderBtnRec'.$ProduktiRec->id.'"
                                            style="background-color:rgb(39,190,175); color:white; border-radius:30px; position:fixed; height:70px; width:80%; bottom:50px;
                                             right:10%; left:10%; font-size:22px;"
                                            >'.__("inc.addToCart").'</button>

                                        </form>
                                        ';
                                    ?>
                    </div>
                </div>

                
            </div>


        </div>
    </div>
</div>

@endforeach




<script>
    var intervalTimerMenuRec ;
    function startNrVerifyTimerRec(){
        var timerStartRec = "5:00";
        $('#numberVerificationTimerValRec').html(timerStartRec);

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
            $('#numberVerificationTimerValRec').html(minutes + ':' + seconds);
            timerStartRec = minutes + ':' + seconds;
            if(minutes == 0 && seconds == 0)location.reload();
        }, 1000);
    }
    function refreshNumberVerModalResMenuRec(){
        $("#numberVerModalResMenuRec").load(location.href+" #numberVerModalResMenuRec>*","");
        clearInterval(intervalTimerMenuRec);
    }
</script>
<!-- Number verification modal Rec -->
<div id="numberVerModalResMenuRec" class="modal mt-5">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:25px;">

            <!-- Modal Header -->
            <div class="modal-header" style="border-radius:25px 25px 0px 0px; background-color: rgb(39, 190, 175);">
                <h4 class="modal-title" style="color: white;"><strong>{{__('inc.text001')}}</strong></h4>
                <button type="button" class="close" data-dismiss="modal" style="color: white;" onclick="refreshNumberVerModalResMenuRec()"><strong>X</strong></button>
            </div>

            <input type="hidden" id="cartStoresaveTheProductIdRec">

            <!-- Modal body -->
            <div class="modal-body">

                <div class="form-check" id="DtPrivatcyAcptDivRec">
                    <label style="font-size: 12px;" class="form-check-label" onclick="clickDtPrivatcyAcptRec()">
                        <input id="DtPrivatcyAcptRec" type="checkbox" class="form-check-input">
                        Ich habe die <a href="{{route('firstPage.datenschutz')}}"><strong>Datenschutzbestimmungen</strong></a> zur Kenntnis genommen*
                    </label>
                </div>

                <p id="cartStoreP1PhoneNrCodeShowDemoRec"></p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="cartStoreP1PhoneNrRec" placeholder="{{__('inc.yourPhoneNumber')}}" style="box-shadow: inset 0 -1px 0 #ddd;">
                    <div class="input-group-append">
                        <button class="btn btn-danger" id="cartStoreP1getVerificationcodeRecBtn" onclick="cartStoreP1getVerificationcodeRec()" type="submit" disabled>
                            {{__('inc.send')}}
                        </button>
                    </div>
                </div>

                <div class="mb-2 mt-2 p-2" id="cartStoreP1PhoneNrProductsImportRec" style="display: none; border:1px solid rgb(39,190,175); border-radius:10px;">
                    <p style="color: rgb(39,190,175); width:100%;" class="text-center"><strong>{{__('inc.unpaidProducts')}}</strong></p>
                    <div id="cartStoreP1PhoneNrProductsImportInfoRec" style="width:100%;" class="d-flex flex-wrap"></div>
                    <input type="hidden" id="cartStoreP1PhoneNrProductsUnpaidRec" value="empty">

                    <p style="color: red; width:100%; font-size:9px; display:none;" class="text-center" id="cartStoreP1PhoneNrProductsImportInfoMsgRec">
                        <i class="fas fa-exclamation-triangle" style="padding: 0px !important; margin-bottom:0px !important;">
                        </i>  Wenn dies (T:?) vor dem Namen des Produkts angezeigt wird, bedeutet dies,
                        dass dieses Produkt in einer anderen Tabelle registriert ist und in diese Tabelle übertragen wird!
                    </p>
                </div>

                <div class="input-group mb-3" id="cartStoreP1PhoneNrCodeWriteRec" style="display: none;">
                    <input type="hidden" class="form-control" id="cartStoreP1PhoneNrCodeRec">
                    <input type="hidden" class="form-control" id="cartStoreP1TimeStartedRec">
                    <input type="text" class="form-control" id="cartStoreP1PhoneNrCodeUserRec" placeholder="{{__('inc.verificationKey')}}" style="box-shadow: inset 0 -1px 0 #ddd;">
                    <div class="input-group-append">
                        <button class="btn btn-success" onclick="cartStoreP2VerifyTheCodeRec()" type="submit">{{__('inc.check')}}</button>
                    </div>
                    @if (Auth::check() && Auth::user()->phoneNr == 'empty' ) 
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" value="" id="regPhNrToAcRec">
                            <label class="form-check-label" for="regPhNrToAcRec" style="font-weight: bold;">
                                Register this phone number to my account
                            </label>
                        </div>
                    @endif
                </div>
                <p style="display:none;" id="numberVerificationTimerRec">{{__('inc.timer')}}<span id="numberVerificationTimerValRec"></span></p>
    

                <div id="cartStoreP1getVerificationcodeErrorRec" class="alert alert-danger" style="font-weight: bold; display:none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Number verification modal Product2+ -->
<div id="numberVerModalResMenuRec2" class="modal mt-5">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color: rgb(39, 190, 175);">
                <h4 class="modal-title" style="color: white;"><strong>{{__('inc.text002')}}</strong></h4>
                <button type="button" class="close" data-dismiss="modal" style="color: white;"><strong>X</strong></button>
            </div>
            <input type="hidden" id="cartStoresaveTheProductIdRec2">
            <div class="modal-body">
                <?php $tabCodeNrVer = TableQrcode::where([['Restaurant',$_GET['Res']],['tableNr',$_GET['t']]])->first()->kaTab; ?>
                @if($tabCodeNrVer != 0)
                    <?php $PNShown = array(); ?>
                    @foreach (tabVerificationPNumbers::where([['tabCode',$tabCodeNrVer],['status','1']])->get() as $actNrs )
                        @if(!in_array($actNrs->phoneNr,$PNShown))
                            <button class="btn btn-block btn-outline-dark mb-2 text-center" style="font-size: 23px;"
                                onclick="sendSecondOrderToProcessRec('{{$actNrs->phoneNr}}')">+41 *** *{{substr($actNrs->phoneNr, -6)}}
                            </button>
                            <?php array_push($PNShown,$actNrs->phoneNr);?>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>


<script>

    function clickDtPrivatcyAcptRec(){
        if($('#DtPrivatcyAcptRec').is(":checked")){
            $('#cartStoreP1getVerificationcodeRecBtn').attr('class','btn btn-success');
            $('#cartStoreP1getVerificationcodeRecBtn').prop("disabled", false );
        }else{
            $('#cartStoreP1getVerificationcodeRecBtn').attr('class','btn btn-danger');
            $('#cartStoreP1getVerificationcodeRecBtn').prop("disabled", true );
        }
    }

    function sendNewOrderRec(pi) {
        if( $('#hasTypeThisProRec'+pi).val() == 1 && $('#ProdAddLlojetRec' + pi).val() == ''){
            // Nuk eshte selektuar tipi , nese ka  ( REC )
            $('#addTypePlease').show(200).delay(2500).hide(200);
        }else if($('#hasTypeThisProRec'+pi).val() == 0 || $('#ProdAddLlojetRec' + pi).val() != ''){
            $.ajax({
                url: '{{ route("cart.storeP1") }}',
                method: 'post',
                data: {
                    id: pi,
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    if(response['typeAns'] == 'first'){
                        $('#numberVerModalResMenuRec').modal('toggle');
                        $('#cartStoresaveTheProductIdRec').val(response['pId']);
                    }else{
                        //porosia 2+   
                        // $('#numberVerModalResMenuRec2').modal('toggle');
                        $('#cartStoresaveTheProductIdRec2').val(response['pId']);
                        this.sendSecondOrderToProcessRec(response['phoneNr'])
                    }
                },
                error: (error) => { console.log(error); // alert($('#oops_wrong').val())
                }
            });
        }
    }

    function cartStoreP1getVerificationcodeRec(){
        var pNrRec = $('#cartStoreP1PhoneNrRec').val().replace(/ /g,'');

        if(pNrRec != ''){
            if(pNrRec[0] == '+' && pNrRec[1] == 4 && (pNrRec[2] == 1 || pNrRec[2] == 9) && pNrRec[3] == 7 && pNrRec.length == 12){
                pNrRec = '0'+pNrRec.toString().slice(3);
            }else if(pNrRec[0] == 4 && (pNrRec[1] == 1 || pNrRec[1] == 9) && pNrRec[2] == 7 && pNrRec.length == 11){
                pNrRec = '0'+pNrRec.toString().slice(2);
            }else if(pNrRec[0] == 0 && pNrRec[1] == 0 && pNrRec[2] == 4 && (pNrRec[3] == 1 || pNrRec[3] == 9) && pNrRec[4] == 7 && pNrRec.length == 13){
                pNrRec = '0'+pNrRec.toString().slice(4);
            }
        }

        if(pNrRec == ''){
        $('#cartStoreP1getVerificationcodeErrorRec').html($('#INCwritePhoneNr').val());
        $('#cartStoreP1getVerificationcodeErrorRec').show(200).delay(3000).hide(200);
        }else if(pNrRec.length < 9 || pNrRec.length > 10){
            $('#cartStoreP1getVerificationcodeErrorRec').html($('#INCphoneNrNotAccepted').val());
            $('#cartStoreP1getVerificationcodeErrorRec').show(200).delay(3000).hide(200);
        }else{
            $.ajax({
                url: '{{ route("cart.storeP2") }}',
                method: 'post',
                data: { phoneNr: pNrRec, pID: $('#cartStoresaveTheProductIdRec').val(), _token: '{{csrf_token()}}' },
                success: (res) => {

                    if(res['status'] == 'success'){
                        $('#cartStoreP1getVerificationcodeRecBtn').attr('disabled',true);

                        $('#cartStoreP1PhoneNrCodeRec').val(res['code']);
                        $('#cartStoreP1TimeStartedRec').val(res['timeStart']);
                        $('#cartStoreP1PhoneNrCodeWriteRec').show(500);
                        $('#numberVerificationTimerRec').show(500);
                        this.startNrVerifyTimerRec();
                        $('#cartStoresaveTheProductIdRec').val(res['pID']);
                        if(pNrRec == '763270293' || pNrRec == '0763270293' || pNrRec == '763251809' || pNrRec == '0763251809' || pNrRec == '763459941' || pNrRec == '0763459941' || pNrRec == '763469963' || pNrRec == '0763469963' || pNrRec == '760000000' || pNrRec == '0760000000'){
                            $('#cartStoreP1PhoneNrCodeShowDemoRec').html('Demo-Code: '+res['code']);
                        }else{
                            $('#cartStoreP1PhoneNrCodeShowDemoRec').hide(5);
                        }

                        var hasOtherTableOrdersRec = false;
                        if(res['unpaid'] != 'empty'){
                            var unpaid =(res['unpaid']).split('--8--');
                            $('#cartStoreP1PhoneNrProductsImportRec').show();
                            var unpaidShow = unpaid[1].split('-8-');
                            var thisTableNr = $('#thisTable').val();
                            $.each( unpaidShow, function( index, value ) {
                                var unpaindOnePrShow = value.split('||');
                                if(unpaindOnePrShow[2] != thisTableNr){
                                    hasOtherTableOrdersRec = true;
                                    $('#cartStoreP1PhoneNrProductsImportInfoRec').append("<p style='width:75%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;'><span style='color:red;'>T:"+unpaindOnePrShow[2]+"</span> "+unpaindOnePrShow[0]+"</p>");
                                    $('#cartStoreP1PhoneNrProductsImportInfoRec').append("<p style='width:25%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;' class='text-right'>"+unpaindOnePrShow[1]+" "+$('#price_lang').val()+"</p>");
                                }else{
                                    $('#cartStoreP1PhoneNrProductsImportInfoRec').append("<p style='width:75%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;'>"+unpaindOnePrShow[0]+"</p>");
                                    $('#cartStoreP1PhoneNrProductsImportInfoRec').append("<p style='width:25%; color:rgb(72,81,87); font-weight:bold; margin-top:-15px;' class='text-right'>"+unpaindOnePrShow[1]+" "+$('#price_lang').val()+"</p>");
                                }                        });
                            $('#cartStoreP1PhoneNrProductsUnpaidRec').val(unpaid[0]);    
                        }
                        if(hasOtherTableOrdersRec){ $('#cartStoreP1PhoneNrProductsImportInfoMsgRec').show(300); }
                        else{$('#cartStoreP1PhoneNrProductsImportInfoMsgRec').hide(1); }
                    }else{
                        $('#cartStoreP1getVerificationcodeErrorRec').html($('#INCphoneNrNotAccepted').val());
                        $('#cartStoreP1getVerificationcodeErrorRec').show(200).delay(3000).hide(200);
                    }
                },
                error: (error) => { console.log(error); }
            });
        }
    }

    function cartStoreP2VerifyTheCodeRec(){
        var pNrRec = $('#cartStoreP1PhoneNrRec').val().replace(/ /g,'');
        if(pNrRec != ''){
            if(pNrRec[0] == '+' && pNrRec[1] == 4 && (pNrRec[2] == 1 || pNrRec[2] == 9) && pNrRec[3] == 7 && pNrRec.length == 12){
                pNrRec = '0'+pNrRec.toString().slice(3);
            }else if(pNrRec[0] == 4 && (pNrRec[1] == 1 || pNrRec[1] == 9) && pNrRec[2] == 7 && pNrRec.length == 11){
                pNrRec = '0'+pNrRec.toString().slice(2);
            }else if(pNrRec[0] == 0 && pNrRec[1] == 0 && pNrRec[2] == 4 && (pNrRec[3] == 1 || pNrRec[3] == 9) && pNrRec[4] == 7 && pNrRec.length == 13){
                pNrRec = '0'+pNrRec.toString().slice(4);
            }
        }
        var pi = $('#cartStoresaveTheProductIdRec').val();
        if( $('#cartStoreP1PhoneNrCodeUserRec').val() == ''){
            $('#cartStoreP1getVerificationcodeErrorRec').html($('#INCwriteTheCode').val());
            $('#cartStoreP1getVerificationcodeErrorRec').show(200).delay(3000).hide(200); 
        }else if($('#cartStoreP1PhoneNrCodeUserRec').val().length != 6){
            $('#cartStoreP1getVerificationcodeErrorRec').html($('#INCcodeNotAccepted').val());
            $('#cartStoreP1getVerificationcodeErrorRec').show(200).delay(3000).hide(200);
        }else{
            if($('#regPhNrToAcRec').length && $("#regPhNrToAcRec").is(':checked')){ var regThisNrToAc = 'Yes';
            }else{ var regThisNrToAc = 'No'; }

            // alert(Aemri+' // '+Aqmimi+' // '+Apershkrimi+' // '+Aextra+' // '+Allojet+' // '+Akoment);

            $.ajax({
                url: '{{ route("cart.store") }}',
                method: 'post',
                data: {
                    code:  $('#cartStoreP1PhoneNrCodeRec').val(),
                    codeUser: $('#cartStoreP1PhoneNrCodeUserRec').val(),
                    timeStart: $('#cartStoreP1TimeStartedRec').val(),
                    id: pi,
                    emri: $('#ProdAddEmriRec' + pi).val(),
                    qmimi: parseFloat($('#ProdAddQmimiRec' + pi).val()),
                    pershkrimi: $('#ProdAddPershkRec' + pi).val(),
                    extra: $('#ProdAddExtraRec' + pi).val(),
                    llojet: $('#ProdAddLlojetRec' + pi).val(),
                    koment: $('#komentMenuAjaxRec' + pi).val(),
                    kategoria: $('#ProdAddKategoriaRec' + pi).val(),
                    res: $('#thisRestaurant').val(),
                    t: $('#thisTable').val(),
                    sas:$('#sasiaProdRec'+pi).val(),
                    phoneNr: pNrRec,
                    unpaid: $('#cartStoreP1PhoneNrProductsUnpaidRec').val(),
                    regNrToUsr: regThisNrToAc,
                    _token: '{{csrf_token()}}'
                },
                success: (response) => {
                    if(response['status'] == 'success'){
                        $("#orderprotONBTN").load(location.href+" #orderprotONBTN>*","");
            
                        // price'.$prod->id.'
                        // Deselect extras,types
                        $(".allProExtrasRec" + pi).prop("checked", false);
                        $(".allProTypesRec" + pi).prop("checked", false);

                        // Hide extras,types
                        $(".RecAllExtrasToHide").hide();
                        $(".RecAllTypesToHide").hide();

                        // reset price
                        var firstPr = parseFloat($('#ProdAddQmimiRecBaze' + pi).val());
                        $('#TotPriceRec' + pi).val(firstPr.toFixed(2));

                        var prices = document.getElementsByClassName("priceRec" + pi);
                        for (var i = 0; i < prices.length; i++) {
                            if (lastVRec != 0) {
                                var newV = parseFloat(prices.item(i).innerText) / lastVRec;
                                prices.item(i).innerText = newV.toFixed(2);
                            }
                        }
                        $('#ProdAddExtraRec' + pi).val("");
                        $('#ProdAddLlojetRec' + pi).val("");
                        if (lastVRec != 0) {
                            $('#ProdAddQmimiRec' + pi).val(parseFloat($('#ProdAddQmimiRec' + pi).val()) / lastVRec);
                        }

                        $('.AllExtrasOrder').hide();
                    
                            lastRec = "";
                            lastVRec = 0;
                            $("#orderprot").load(location.href+" #orderprot>*","");


                            console.log($('#CartCountFooter').text());
                            if($('#CartCountFooter').text() == 1 || $('#CartCountFooter').text() == ''){
                                $('#orderGettingReadyFirst').modal('toggle');
                            }else{
                                $('#orderGettingReady').show(300).delay(4000).hide(300);
                            }

                        // reset the PNumber verifyer 
                        $('#numberVerModalResMenuRec').modal('toggle');
                        $("#numberVerModalResMenuRec").load(location.href+" #numberVerModalResMenuRec>*","");
                        // $("#numberVerModalResMenuRec2").load(location.href+" #numberVerModalResMenuRec2>*","");
                        // $("#numberVerModalResMenu2").load(location.href+" #numberVerModalResMenu2>*","");

                        $("#numriNeSession").load(location.href+" #numriNeSession>*","");

                    }else{
                            if(response['status'] == 'failCode'){
                                $('#cartStoreP1PhoneNrCodeRec').val(response['code']);
                                $('#cartStoreP1TimeStartedRec').val(response['timeStart']);
                                $('#cartStoresaveTheProductIdRec').val(response['pID']);

                                $('#cartStoreP1PhoneNrCodeUserRec').val(''),

                                $('#cartStoreP1getVerificationcodeErrorRec').html($('#INCtext003').val());
                                $('#cartStoreP1getVerificationcodeErrorRec').show(200).delay(3000).hide(200);
                            }else if(response['status'] == 'failTime'){

                                $('#cartStoresaveTheProductIdRec').val(response['pID']);
                                
                                $("#cartStoreP1PhoneNrCodeWriteRec").load(location.href+" #cartStoreP1PhoneNrCodeWrite>*","");
                                $('#cartStoreP1PhoneNrCodeWriteRec').hide();

                                $('#cartStoreP1getVerificationcodeErrorRec').html($('#INCcodeFalse').val());
                                $('#cartStoreP1getVerificationcodeErrorRec').show(200).delay(3000).hide(200);
                            }
                        }
                },
                error: (error) => {
                    console.log(error);
                    // alert($('#oops_wrong').val())
                }
            });
        }
    }

function sendSecondOrderToProcessRec(phoneN){
        var pi = $('#cartStoresaveTheProductIdRec2').val();
            $.ajax({
                url: '{{ route("cart.store2Plus") }}',
                method: 'post',
                data: {
                        id: pi,
                        emri: $('#ProdAddEmriRec' + pi).val(),
                        qmimi: parseFloat($('#ProdAddQmimiRec' + pi).val()),
                        pershkrimi: $('#ProdAddPershkRec' + pi).val(),
                        extra: $('#ProdAddExtraRec' + pi).val(),
                        llojet: $('#ProdAddLlojetRec' + pi).val(),
                        koment: $('#komentMenuAjaxRec' + pi).val(),
                        kategoria: $('#ProdAddKategoriaRec' + pi).val(),
                        res: $('#thisRestaurant').val(),
                        t: $('#thisTable').val(),
                        sas:$('#sasiaProdRec'+pi).val(),
                        phoneNr: phoneN,
                        _token: '{{csrf_token()}}' },
                success: (res) => {
                    $("#orderprotONBTN").load(location.href+" #orderprotONBTN>*","");
                        // $("#orderprot").load(location.href+" #orderprot>*","");
                        
                        $('#CartCountFooter').text(parseInt($('#CartCountFooter').text()) + 1);
                        var pricess = $('#CartTotalFooter2').text();
                        var pricessCH = $('#CartTotalFooter2CH').text();
                        var pricessT = parseFloat(pricess) + parseFloat($('#ProdAddQmimi' + pi).val());
                        var pricessTCH = parseFloat(pricessCH) + parseFloat($('#ProdAddQmimi' + pi).val());
                        
                        $('#CartTotalFooter2').text(pricessT.toFixed(2));
                        $('#CartTotalFooter2CH').text(pricessTCH.toFixed(2));
                        $('.AllExtrasOrder').hide();
                        // price'.$prod->id.'
                        // Deselect extras,types
                        $(".allProExtras" + pi).prop("checked", false);
                        $(".allProTypes" + pi).prop("checked", false);

                        // Hide extras,types
                        $(".AllExtrasToHide").hide();
                        $(".AllTypesToHide").hide();

                        // reset price
                        var firstPr = parseFloat($('#ProdAddQmimiBaze' + pi).val());
                        $('#TotPrice' + pi).val(firstPr.toFixed(2));

                        var prices = document.getElementsByClassName("price" + pi);
                        for (var i = 0; i < prices.length; i++) {
                            if (lastV != 0) {
                                var newV = parseFloat(prices.item(i).innerText) / lastV;

                                prices.item(i).innerText = newV.toFixed(2);
                            }
                        }
                        $('#ProdAddExtra' + pi).val("");
                        $('#ProdAddLlojet' + pi).val("");
                        if (lastV != 0) {
                            $('#ProdAddQmimi' + pi).val(parseFloat($('#ProdAddQmimi' + pi).val()) / lastV);
                        }
                    
                        $('.orderCountTop').text($('#CartCountFooter').text());

                        last = "";
                        lastV = 0;

                        console.log($('#CartCountFooter').text());
                        if($('#CartCountFooter').text() == 1 || $('#CartCountFooter').text() == ''){
                            $('#orderGettingReadyFirst').modal('toggle');
                        }else{
                            $('#orderGettingReady').show(300).delay(4000).hide(300);
                        }

                        // reset the PNumber verifyer 
                        // $('#numberVerModalResMenuRec2').modal('toggle');
                        // $("#numberVerModalResMenuRec2").load(location.href+" #numberVerModalResMenuRec2>*","");

                        $("#numriNeSession").load(location.href+" #numriNeSession>*","");
                },
                error: (error) => { console.log(error); }
            });
    }





    function addOneToSasiaProRec(pId){
        var cVal = parseInt($('#sasiaPerProdRec'+pId).val());
        var newVal = cVal + 1;
        $('#sasiaPerProdRec'+pId).val(newVal);
        $('#sasiaProdRec'+pId).val(newVal);
        $('#minusSasiaPerProdRec'+pId).show();
        $('#placeholderSasiaPerProdRec'+pId).hide();
    }
    function removeOneToSasiaProRec(pId){
        var cVal = parseInt($('#sasiaPerProdRec'+pId).val());
        var newVal = cVal - 1;
        $('#sasiaPerProdRec'+pId).val(newVal);
        $('#sasiaProdRec'+pId).val(newVal);
        if(newVal == 1){
            $('#minusSasiaPerProdRec'+pId).hide();
            $('#placeholderSasiaPerProdRec'+pId).show();
        }else{
            $('#minusSasiaPerProdRec'+pId).show();
            $('#placeholderSasiaPerProdRec'+pId).hide();
        }
        
    }











$(document).ready(function() {

    $('.RecAllExtrasToHide').hide();
    $('.RecAllTypesToHide').hide();
});

function showExtraMenuRec(prodId) {
    if ($('.RecIDExtra' + prodId).is(":visible")) {
        $('.RecIDExtra' + prodId).hide();
        $('.threeDotsExtRec' + prodId).show();
    } else {
        $('.RecIDExtra' + prodId).show();
        $('.threeDotsExtRec' + prodId).hide();
    }
}

function showTypeMenuRec(prodId) {
    if ($('.RecIDType' + prodId).is(":visible")) {
        $('.RecIDType' + prodId).hide();
        $('.threeDotsTypeRec' + prodId).show();
        
    } else {
        $('.RecIDType' + prodId).show();
        $('.threeDotsTypeRec' + prodId).hide();
    }
}

</script>








<!-- The Modal -->
<div class="modal" id="priceChangeMenuAlert" style="margin-top:70%;">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:20px;">


      <!-- Modal body -->
      <div class="modal-body text-center" >
       <strong id="priceChangeMenuAlertMB">{{__('inc.priceChange10m')}}</strong> 
       <button type="button" class="btn btn-block btn-outline-primary mt-2" data-dismiss="modal">{{__('inc.ok')}}</button>
      </div>


    </div>
  </div>
</div>




<script>

    function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }
    function timeReset(){
        var dat = new Date();
        if((dat.getSeconds()))
        var time = pad(dat.getHours(),2) + ":" + pad(dat.getMinutes(),2)+":"+pad(dat.getSeconds(),2);

        if((time >= '20:00:00' && time <= '20:00:05') || (time >= '23:59:55' && time <= '00:00:01') ){
            window.setTimeout( location.reload(), 5000 );
        }else if(time >= '19:40:00' && time <= '19:40:05'){
            $('#priceChangeMenuAlert').modal('show');
            $('#priceChangeMenuAlertMB').html($('#INCpriceChange20m').val());
        }else if(time >= '19:50:00' && time <= '19:50:05'){
            $('#priceChangeMenuAlert').modal('show');
            $('#priceChangeMenuAlertMB').html($('#INCpriceChange10m').val());
        }else if(time >= '19:55:00' && time <= '19:55:05'){
            $('#priceChangeMenuAlert').modal('show');
            $('#priceChangeMenuAlertMB').html($('#INCpriceChange5m').val());
        }else if(time >= '19:59:00' && time <= '19:59:05'){
            $('#priceChangeMenuAlert').modal('show');
            $('#priceChangeMenuAlertMB').html($('#INCpriceChange1m').val());
        }
        // console.log(time);
    }

    setInterval(timeReset, 5000);


    $(document).ready(function () {

        if($("#thisRestaurant").length){
            $.ajax({
                url: '{{ route("restorantet.ResOpenCount") }}',
                method: 'post',
                data: {
                    id: $('#thisRestaurant').val(),
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                },
                error: (error) => {
                    console.log(error);
                }
            });
        }
        // $('#popUpAd01').modal('show');
        // $('#popUpAd01').modal('hide');
    }); // End of doc.ready 

                    
</script>
