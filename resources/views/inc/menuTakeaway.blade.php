@include('words')
<style>
/* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    /* float: right; */
}

/* Hide default HTML checkbox */
.switch input {
    display: none;
}

/* The slider */
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input.default:checked+.slider {
    background-color: #444;
}

input.primary:checked+.slider {
    background-color: #2196F3;
}

input.success:checked+.slider {
    background-color: #8bc34a;
}

input.info:checked+.slider {
    background-color: #3de0f5;
}

input.warning:checked+.slider {
    background-color: #FFC107;
}

input.danger:checked+.slider {
    background-color: #f44336;
}

input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.scrolling {
    /* Note #2 */
    position: fixed;
    top: 0px;
}



.noBorder:active {
    outline: none;
}

.noBorder:focus {
    outline: none;
    box-shadow: none;
}





input[type="number"]:disabled {
    background-color: white;
}

.btn:focus,
.btn:active {
    outline: none !important;
    box-shadow: none;
}


.MultiCarousel {
    float: left;
    overflow: hidden;
    padding: 5px;
    width: 100%;
    position: relative;
}

.MultiCarousel .MultiCarousel-inner {
    transition: 1s ease all;
    float: left;
}

.MultiCarousel .MultiCarousel-inner .item {
    float: left;
}

.MultiCarousel .MultiCarousel-inner .item>div {
    text-align: center;
    padding-left: 50px;
    padding-right: 50px;
    padding: 3px;
    margin: 5px;
    background: #f1f1f1;
    color: #666;
}

.MultiCarousel .leftLst,
.MultiCarousel .rightLst {
    position: absolute;
    border-radius: 50%;
    top: calc(50% - 20px);
}

.MultiCarousel .leftLst {
    left: 0;
}

.MultiCarousel .rightLst {
    right: 0;
}

.MultiCarousel .leftLst.over,
.MultiCarousel .rightLst.over {
    pointer-events: none;
    background: #ccc;
}


.hover-pointer:hover {
    cursor: pointer;
}





#searchBar {
    background-image: url('../storage/icons/search.png');
    /* Add a search icon to input */
    background-size: 25px 25px;
    background-position: 10px 12px;
    /* Position the search icon */
    background-repeat: no-repeat;
    /* Do not repeat the icon image */
    width: 100%;
    /* Full-width */
    font-size: 16px;
    /* Increase font-size */
    padding: 12px 20px 12px 40px;
    /* Add some padding */
    border: 1px solid #000;
    /* Add a grey border */
    margin-bottom: 12px;
    /* Add some space below the input */
    opacity: 0.45;
    border-radius: 20px;
}




select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
}

 /*Arbnor CSS*/
    .text-left{
        padding: 0px;
    }
    p.text-left.hover-pointer {
        padding-left: 0px !important;
    }
    .navbar-brand {
        padding-left: 15px;
    }
    .col-9.d-flex {
        padding: 0;
    }
  
    .extras-displayed{
        margin-left: 0 !important;
    }
    .extras-container{
        padding: 0 !important;
    }
    .container-fluid.mt-2 {
    padding: 0;
}
  /*End Arbnor CSS*/
</style>

<?php
    use App\Produktet;
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
    use App\RecomendetProd;
    use App\resdemoalfa;
    use Carbon\Carbon;
    use App\Restorant;
    use App\Takeaway;    
    use App\RestaurantWH;
    use App\RestaurantRating;
    use Illuminate\Support\Facades\Cookie;
    
    if(isset($_GET['Res'])){
        $theRes = $_GET['Res'];
        $theRestaurant = Restorant::find($_GET["Res"]);
        $RWHT = RestaurantWH::where('toRes', $_GET['Res'])->first();
        $thisRestaurantRatings = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->orderBy('updated_at','DESC')->get();
        $thisRestaurantRatingsSum = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->sum('stars');
        $thisRestaurantRaringAverage = RestaurantRating::where([['restaurant_id', '=', $_GET['Res']], ['verified', '=', '1']])->avg('stars');

        $openStat2D = explode('-||-',$theRestaurant->isOpen);
    }
    // $produktet =Produktet::where('toRes', '=', $_GET["Res"])->get();
    // $produktet =DB::table('produktets')->where('toRes', $_GET["Res"])->get();
    // $produktet =Produktet::all();
    $RecPro = RecomendetProd::all()->sortBy("pozita");
?>

@include('inc.menuTakeawayComp.menuTakeawayStyle')


<input type="hidden" id="thisRestaurant" value="{{$_GET['Res']}}">
<input type="hidden" id="thisTable" value="{{$_GET['t']}}">
<script>
    var last = "";
    var lastV = 0;

    var lastRec = "";
    var lastVRec = 0;

    var originalCart = '';
</script>



<!-- Modal -->
<div class="modal fade mt-4" id="ehcChurCanNotServeTodayModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border:2px solid rgb(39,190,175); border-radius:20px;">
            <div class="modal-body text-center">
                <p class="text-center"><strong>Ehc chur kann Sie heute nicht online bedienen!</strong></p>
                <p class="text-center" style="color:red;"><strong>BITTE NICHT BESTELLEN</strong></p>
                <br>
                <i style="color:rgb(39,190,175);" class="text-center fas fa-3x fa-store-alt-slash"></i>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade mt-4" id="ChuroccoBarAccsDeniedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border:2px solid rgb(39,190,175); border-radius:20px;">
            <div class="modal-body text-center">
                <p class="text-center"><strong>Sie sollten am Stand bestellen und mit dem Personal sprechen!</strong></p>
                <p class="text-center" style="color:red;"><strong>BITTE NICHT BESTELLEN</strong></p>
                <br>
                <i style="color:rgb(39,190,175);" class="text-center fas fa-3x fa-store-alt-slash"></i>
            </div>
        </div>
    </div>
</div>

<script>
    // PER EHC CHUR - bllokohet perdorimi i platformes
    // if($('#thisRestaurant').val() == 22){
    //     $('#ehcChurCanNotServeTodayModal').modal('show');
    // }

    // PER Churocco Bar - bllokohet perdorimi i platformes
    // if($('#thisRestaurant').val() == 32){
    //     $('#ChuroccoBarAccsDeniedModal').modal('show');
    // }
</script>

@if ($openStat2D[1] == '0')
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







<style>
.swiper-container{
    background-color:#FFF;
padding-top: 5px !important;
}
.swiper-slide{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}
.swiper-slide img{
    object-fit:cover;

}
.swiper-slide p{
    margin-top:5px;
    margin:0;
}
.rec .teksti{
    margin:0 auto;
    margin-bottom:10px;
}
.rec{
    margin-bottom:10px;
}

</style>

@if(isset($_GET['Res']))

<div class="container" style="display:none;" id="waiterIsComming">
    <div class="row">
        <div class="col-lg-3 col-sm-0 col-0">
        </div>
        <div class="col-lg-6 col-sm-12 col-12 text-center">
            <div class="alert-success p-2" style="border-radius:10px;">
                {{__('inc.waiterCallSuccess01')}}
            </div>
        </div>
        <div class="col-lg-3 col-sm-0 col-0">
        </div>
    </div>
</div>
<div class="container" style="display:none;" id="waiterIsNotComming">
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





@endif



<div class="ProdModalBody">

    <script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 10,
        breakpoints: {
    // when window width is >= 320px
    320: {
      slidesPerView: 3,
      spaceBetween: 10
    },
    // when window width is >= 480px
    480: {
      slidesPerView: 3,
      spaceBetween: 10
    },
    // when window width is >= 640px
    640: {
      slidesPerView: 4,
      spaceBetween: 10
    }
  }
    });
    </script>







<div class="col-md-3 left-section">
        @include('inc.menuTakeawayComp.leftSection')
    </div>
    <div class="col-md-3 right-section">
        @include('inc.menuTakeawayComp.rightSection')
        
    </div>
    <!-- Recomendet Products -->
    <div class="recommended-mobile">@include('inc.menuTakeawayComp.menuTakeawayRecomendet')</div>
        <div class="col-md-6 center-section">
            
            <div class="recommended-desktop">@include('inc.menuTakeawayComp.menuTakeawayRecomendet')</div>
            <!-- Show Menu -->
            @include('inc.menuTakeawayComp.menuTakeawayShowMenu')
        </div>






    <div class="container">

        <style>
        @media (max-width: 375px) {
            .emriRec {
                margin-top: -17px;
                margin-bottom: -30px;

            }

            .recProElement {
                height: auto;
                width: 100px;

            }
        }

        @media (max-width:420px) and (min-width:376px)) {
            .emriRec {
                margin-top: -16px;
                margin-bottom: -30px;
            }

            .recProElement {
                height: auto;
                width: 100px;

            }
        }

        @media (max-width:600px) and (min-width:421px) {
            .emriRec {
                margin-top: -16px;
                margin-bottom: -30px;
            }

            .recProElement {
                height: auto;
                width: 100px;

            }
        }

        @media (max-width:2400px) and (min-width:601px) {
            .emriRec {
                margin-top: -16px;
            }

            .recProElement {
                height: auto;
                width: 90px;
            }
        }
        </style>

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

        <style>
            .teksti{
                justify-content:space-between;
                margin-top:-50px;
                color:#FFF;
                font-weight:bold;
                font-size:23px;
                margin-bottom:10px;
            }

            
            .prod-name{
                line-height: 2;
            }
            .add-plus-section{
                text-align: right;
                padding: 0px;
            }
            .product-section{
                border-bottom: 1px solid #dcd9d9;
                padding-bottom: 15px;
            }
            .recommended-title{
                margin-left: 0px !important;
            }
            .teksti strong{
                margin-left:20px;
            }
            .teksti i{
                margin-right:20px
            }
        </style>



















        

</div>
</div>

<script>
$('.prodsFoto').each(function() {
    $(this).hide();
});
</script>



<style>
.footerPhoneTA {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    background-color: rgb(39, 190, 175);
    color: white;
    text-align: center;
    padding-top: 10px;
	padding-bottom: 10px;
    font-size: 19px;
    border-top-left-radius: 30px;
    border-top-right-radius: 30px;
    z-index: 1000;
}

#anchorOrder {
    color: white;
    font-size: 19px;
}

a.disabled {

    cursor: not-allowed;
    pointer-events: none;
}


.checkoutBtn {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    background-color: rgb(39, 190, 175);
    color: white;
    text-align: center;
    padding: 10px;
    font-size: 19px;
    border-top-left-radius: 30px;
    border-top-right-radius: 30px;
    z-index: 1000;
}
</style>




























<div id="orderprotTA">



    @if(count(Cart::content()) > 0)
    <!--  route('cart') -->
    <div class="footerPhoneTA" data-toggle="modal" data-target="#OrdersViewTA" id="footerShowOrdersMobileTA">
        <a href="{{route('cart')}}">
            <button id="anchorOrder" class="btn btn-default">
                <!-- <i class="fas fa-shopping-basket fa-lg"></i> -->

                <p style="margin-bottom:-6px;"> <img style="width:35px;" src="storage/icons/SAI01OrW.png" alt="">
                    <sup id="CartCountFooter" class="mr-5 pt-1 pl-2 pr-2 pb-1 color-qrorpa" style="width:20px; height:20px; border-radius:50%; font-size:19px; font-weight:bold;
                background-color:white; color:black;">{{Cart::count()}}</sup>
                    <span id="CartTotalFooter" class="ml-5"><span id="CartTotalFooter2"
                            style="font-size:27px;">{{Cart::total()}}</span> {{__('inc.currencyShow')}}</span> </p>


            </button>
        </a>
    </div>
    @else
    <!--  route('cart') -->
    <!--<div class="footerPhone" data-toggle="modal" data-target="#OrdersView" id="footerShowOrdersMobile">-->
    <!--    <button id="anchorOrder" class="btn btn-default">-->
    <!--        <img style="width:35px;" src="storage/icons/SAI01OrW.png" alt="">-->
    <!--    </button>-->
    <!--</div>-->
    @endif




    <script>
    if ((screen.width > 580)) {
        $('.footerPhoneTA').hide();
    } else if ((screen.width <= 580)) {
        $('.footerPhoneTA').show();
    }
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
    
    
    </style>



    <!-- The OrdersView Modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->
    <!-- Order view modal -->

    </div>

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








<script>
function refreshTheOrders() {
    $('#orderprotTA').load('/ #orderprotTA', function() {});
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
            $('#CartTotalFooter').text((current - price).toFixed(2) +' '+ $("#price_lang").val());

            $('#' + thisId).hide('slow');
            $('#footerShowOrdersMobileTA').load('/ #footerShowOrdersMobileTA', function() {});
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
                url: '{{route("search.fromTA")}}',
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


                    $('#produktetUl').append('<h4 class="text-center">'+Object.keys(res).length+' '+$("#INCproductsFound").val()+' </h4>');
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
                        listings ='<div class="row p-2" data-toggle="modal" data-target="#Prod'+value.id+'">'+
                                        '<div class="container-fluid">'+
                                            '<div class="row">'+
                                                '<div class="col-lg-3 col-sm-0 col-md-0"></div>'+
                                                '<div class="col-lg-6 col-sm-12 col-md-12 product-section">'+
                                                    '<div class="row">'+
                                                        '<div class="col-10">'+
                                                            '<h4 class="pull-right prod-name prodsFont" style="font-weight:bold; font-size: 1.20rem ">'+value.emri+'<span style="opacity:0.5"> ( '+$("#katsForSearch"+value.kategoria).val()+' ) </span> </h4>'+
                                                            '<p style=" margin-top:-10px; font-size:13px;">'+persh+''+
                                                            '<h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">'+ $("#price_lang").val()+'</span><span class="ml-2"> '+qmimiFin+' </span></h5>'+
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
                url: '{{route("search.fromTA")}}',
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
                                                            '<h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">'+ $("#price_lang").val()+'</span>'+qmimiFin+'</h5>'+
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
        console.log($('#canceledRec').val());
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
        console.log($('#canceled').val());
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
    }
</script>


<!-- Krijimi i porosive nga menu -->
<div class="ProdModalBody">
    @if(isset($_GET["Res"]))

    @foreach(Takeaway::where('toRes',$_GET["Res"])->where('accessableByClients','1')->get() as $prod)
    <div class="modal modalProd" id="Prod{{$prod->id}}">
        <div class="modal-dialog modal-md">
            <div class="modal-content" style="border-radius:30px;">
                <div class="modal-body">

                    @if(Carbon::now()->format('H:i') >= '20:00' || Carbon::now()->format('H:i') <= '03:00')
                        @if($prod->qmimi2 != 999999)
                            <?php $starterPrice = sprintf('%01.2f', $prod->qmimi2); ?>
                        @else
                            <?php $starterPrice = sprintf('%01.2f', $prod->qmimi); ?>
                        @endif
                    @else
                        <?php $starterPrice = sprintf('%01.2f', $prod->qmimi); ?>
                    @endif

                    <div class="container">
                        <div class="row">
                            <div class="col-10">
                                <h4 class="modal-title">{{$prod->emri}} <span style="color:lightgray;">
                                    ({{kategori::find($prod->kategoria)->emri}})</span></h4>
                            </div>
                            <div class="col-2 text-right">
                                <button type="button" class="btn" data-dismiss="modal">
                                    <i onclick="prodModalCancelMenu('{{$prod->id}}','{{$starterPrice}}')" style="width:6px;" class="far fa-times-circle"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <p>{{$prod->pershkrimi}}</p>
                            </div>
                        </div>
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
                                     <span id="placeholderSasiaPerProd{{$prod->id}}" style="font-size:30px; color:white; width:20%;" class="pr-3 plusForOrder"
                                      >-</span>
                                    <input type="number" min="1" step="1" value="1" class="text-center" id="sasiaPerProd{{$prod->id}}"
                                        style="border:none; width:60%; font-size:28px; height:fit-content;" disabled>
                                    <span style="font-size:30px; width:20%;" class="pl-3 plusForOrder" onclick="addOneToSasiaPro('{{$prod->id}}')">+</span>
                                </div>
                            </div>
                        </div>

            


                        <div class="row">

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




                            <div class="col-12 text-center">

                                @if(count(explode('--0--', $prod->type)) > 0)
                                    <?php $priType = 1?>
                                    @foreach(explode('--0--', $prod->type) as $alll)
                                        @if($alll != '')
                                            @if(count(explode('--0--', $prod->type)) > 5)
                                             
                                                @if($priType == 1)
                                                    <p onclick="showTypeMenu('{{$prod->id}}')" class="hover-pointer color-qrorpa mt-2"
                                                    style="margin-top:-10px; margin-bottom:-3px; font-size:larger;">
                                                        <strong>{{__('inc.type')}}/strong>
                                                    </p>
                                                 @endif
                                             
                                            @endif
                                            <?php $thisType = LlojetPro::find(explode('||',$alll)[0]); ?>
                                                @if($thisType != null)
                                                <div class="container-fluid mt-2 firstTwoTypes{{$prod->id}}">
                                                    <div class="row ml-1" style="margin-bottom:-15px;">
                                                                    
                                                        <div class="col-3 text-left">
                                                            <label class="switch ">
                                                                    <?php
                                                                        if(Carbon::now()->format('H:i') > '20:00'){
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
                                                                    <input style="width:5px;" type="checkbox" class="primary allProTypesMenu allProTypes{{$prod->id}}" id="llojetPE{{$thisType->id}}O{{$prod->id}}" 
                                                                                onchange="addThis2(this.id,'{{$prod->id}}','{{$thisType->emri}}','{{$vleraType}}','{{$starterPrice}}','False')">
                                                                            <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThis2('llojetPE{{$thisType->id}}O{{$prod->id}}','{{$prod->id}}','{{$thisType->emri}}','{{$vleraType}}','{{$starterPrice}}','True')">
                                                                        <p style="width:70%;" class="text-left"><strong>{{$thisType->emri}}</strong></p>
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
                                       if(count(explode('--0--', $prod->type)) > 0){
                                            foreach(explode('--0--', $prod->type) as $llP){
                                                if($countNewTypes++ > 5){
                                                    if(!empty($llP)){
                                                        $thisType = LlojetPro::find(explode('||',$llP)[0]);
                                                        if(!empty($thisType)){

                                                          
                                                            if(Carbon::now()->format('H:i') > '20:00'){
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

                                                            $theIdOfThisType = "llojetP'.$thisType->id.'O'.$prod->id.'";
                                                            echo '
                                                                <div class="container extras-container AllTypesToHide IDType'.$prod->id.'">
                                                                    <div class="row ml-1 extras-displayed">
                                                                        
                                                                        <div class="col-3 text-left">
                                                                            <label class="switch ">
                                                                                <input style="width:5px;" type="checkbox" class="primary allProTypesMenu allProTypes'.$prod->id.'" id="llojetP'.$thisType->id.'O'.$prod->id.'"
                                                                                    onchange="addThis2(this.id,\''.$prod->id.'\',\''.$thisType->emri.'\',\''.$vleraType.'\',\''.$starterPrice.'\',\'False\')">
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
                                        }else{
                                            echo '<p class="text-center opacity-65">Empty!</p>';
                                        }
                                    ?>
                            </div>
                            <div class="col-12 text-center">
                                @if(count(explode('--0--', $prod->extPro)) > 0)
                                    <?php $priExt = 1?>
                                    @foreach(explode('--0--', $prod->extPro) as $alll)
                                        @if($alll != '')
                                            @if(count(explode('--0--', $prod->extPro)) > 5)
                                                @if($priExt == 1)
                                                <hr>
                                                    <p onclick="showExtraMenu('{{$prod->id}}')" class="hover-pointer color-qrorpa"
                                                     style="margin-top:-10px; margin-bottom:-3px; font-size:larger;" >
                                                        <strong>{{__('inc.extras')}}</strong></p>

                                                @endif
                                            @endif
                                            <?php $thisExtra = ekstra::find(explode('||',$alll)[0]);?>
                                                @if($thisExtra != null)
                                                    <div class="container extras-container">
                                                        <div class="row ml-1 extras-displayed">
                                                            <div class="col-3 text-left">
                                                                <label class="switch ">
                                                                    <input style="width:5px;" type="checkbox" class="primary allProExtrasMenu allProExtras{{$prod->id}}" id="extPE{{$thisExtra->id}}O{{$prod->id}}" 
                                                                        onchange="addThis(this.id,'{{$prod->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','False')">
                                                                    <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-9 text-left d-flex" style="margin-left:-35px;" onclick="addThis('extPE{{$thisExtra->id}}O{{$prod->id}}','{{$prod->id}}','{{$thisExtra->emri}}','{{$thisExtra->qmimi}}','True')">
                                                                <p style="width:70%;" class="text-left"><strong>{{$thisExtra->emri}}</strong></p>
                                                              
                                                                <p style="width:30%;" class="text-right"><span class="price{{$prod->id}}"> {{sprintf('%01.2f', $thisExtra->qmimi)}}</span><sup>{{__('inc.currencyShow')}}</sup></p> 
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
                                        <p onclick="showExtraMenu('{{$prod->id}}')" class="text-left pl-5 hover-pointer threeDotsExt{{$prod->id}}"
                                          style="font-size:25px; margin-top:-15px; font-weight:bold;"><span class="color-qrorpa" style="font-size:16px;">{{__('inc.more')}}</span> </p>
                                    @endif
                                @endif
                            </div>
                            <!-- Extras -->
                            <div class="col-12">
                                <?php
                                    $countNewEtx = 1;
                                        $extras = explode('--0--', $prod->extPro);
                                        foreach($extras as $extP){
                                            if($countNewEtx++ > 5){
                                                if(!empty($extP)){
                                                    $thisExtra = ekstra::find(explode('||',$extP)[0]);
                                                        if($thisExtra != null){
                                                        $theIdOfThisExtra = "extP'.$thisExtra->id.'O'.$prod->id.'";

                                                        echo '
                                                        <div class="container extras-container AllExtrasToHide IDExtra'.$prod->id.'">
                                                            <div class="row ml-1 extras-displayed">
                                                                <div class="col-3 text-left">
                                                                    <label class="switch ">
                                                                        <input style="width:5px;" type="checkbox" class="primary allProExtrasMenu allProExtras'.$prod->id.'" id="extP'.$thisExtra->id.'O'.$prod->id.'" 
                                                                            onchange="addThis(this.id,\''.$prod->id.'\',\''.$thisExtra->emri.'\',\''.$thisExtra->qmimi.'\',\'False\')">
                                                                        <span class="slider round" style="width:25px; height:25px; background-color:rgb(39,190,175);"></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-9 d-flex" style="margin-left:-35px;" onclick="addThis(\''.$theIdOfThisExtra .'\',\''.$prod->id.'\',\''.$thisExtra->emri.'\',\''.$thisExtra->qmimi.'\',\'True\')">
                                                                    <p style="width:70%;" class="text-left color-text"><strong>'.$thisExtra->emri.'</strong></p>  <p style="width:30%;" class="text-right"><span class="price'.$prod->id.'"> '.sprintf('%01.2f', $thisExtra->qmimi).'</span> <sup>'.__("inc.currencyShow").' </sup>
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






                            <!-- Tipet -->


                        </div>









                        <?php
    // session_start();
?>

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
                                     onclick="sendNewOrderTA('.$prod->id.')">'.__("inc.addToCart").'</button>
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


<div id="addTypePlease" style="top:12%; width:100%; position:fixed; z-index:100000; display:none;">
    <div class="text-center" style="background-color:rgb(39, 190, 175); color:white;  padding-top:15px; padding-bottom:15px; border-radius:9px;">
        {{__('inc.selectType')}}
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
function sendNewOrderTA(pi) {
    // alert('yep');

    // alert(Aemri+' // '+Aqmimi+' // '+Apershkrimi+' // '+Aextra+' // '+Allojet+' // '+Akoment);
    
    if( $('#hasTypeThisPro'+pi).val() == 1 && $('#ProdAddLlojet' + pi).val() == ''){

        $('#addTypePlease').show(200).delay(2500).hide(200);

    }else if($('#hasTypeThisPro'+pi).val() == 0 || $('#ProdAddLlojet' + pi).val() != ''){

        $.ajax({
            url: '{{ route("cart.storeTakeaway") }}',
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
                sas: $('#sasiaProd'+pi).val(),
                _token: '{{csrf_token()}}'
            },
            success: () => {
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
                    $("#orderprotTA").load(location.href+" #orderprotTA>*","");

            },
            error: (error) => {
                console.log(error);
                // alert($('#oops_wrong').val());
            }
        });
    }

}

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



























<script>

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
</script>

@if (!Cookie::has('trackMO') || Cookie::get('trackMO') == 'not')


<script>


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
                                    alert($('#oopsSomethingWrong').val());
                                }
                            });
                        }
                   
                        $.ajax({
                            url: '{{ route("adsModuleSa.getAdsForMenuTakeaway") }}',
                            method: 'post',
                            dataType: 'json',
                            data: {
                                resI: $('#thisRestaurant').val(),
                                _token: '{{csrf_token()}}'
                            },
                            success: (rez) => {
                                if(rez != 'none'){
                                    var cont = '';
                                    $('#popUpAdBody01').html('');
                                    $('#popUpAdBody02').html('');
                                    $('#popUpAdBody03').html('');
                                    $('#popUpAdBody04').html('');

                                    if(rez.tipi == 1){
                                        cont =  '<img onclick="openProductAd(\''+rez.prodId+'\')" src="storage/restaurantADS/'+rez.foto+'" style="max-width:100%; max-height:100%;" alt="">'+
                                                '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                                        $('#popUpAdBody01').append(cont);
                                        $('#popUpAd01').modal('toggle'); 
                                    }else if(rez.tipi == 2){
                                        cont = ' <a href="https://'+rez.linku+'" target="_blank">'+
                                                    '<img src="storage/restaurantADS/'+rez.foto+'" style="max-width:100%; max-height:100%;" alt="">'+
                                                    '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                    'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>'+
                                                '</a>';
                                        $('#popUpAdBody02').append(cont);
                                        $('#popUpAd02').modal('toggle');
                                    }else if(rez.tipi == 3){
                                        cont = '<img onclick="openInfoAd(\''+rez.informata+'\')" src="storage/restaurantADS/'+rez.foto+'" style=max-width:100%; max-height:100%;" alt="">'+
                                                '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                                        $('#popUpAdBody03').append(cont);
                                        $('#popUpAd03').modal('toggle');
                                    }else if(rez.tipi == 4){
                                        cont = '<img onclick="openCategoryAd(\''+rez.catId+'\')" src="storage/restaurantADS/'+rez.foto+'" style="max-width:100%; max-height:100%;" alt="">'+
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
                                alert($('#pleaseUpdateAndTryAgain').val());
                            }
                        });

                        $.ajax({
                            url: '{{ route("adsModuleSa.checkIfResHasRepeat") }}',
                            method: 'post',
                            dataType: 'json',
                            data: {
                                resI: $('#thisRestaurant').val(),
                                _token: '{{csrf_token()}}'
                            },
                            success: (response) => {
                                if(response != 'empty'){
                                    // Ka repeat per Ad

                                    setInterval(function () {
                                        $.ajax({
                                            url: '{{ route("adsModuleSa.getAdsForMenuTakeawayRepeatable") }}',
                                            method: 'post',
                                            dataType: 'json',
                                            data: {
                                                resI: $('#thisRestaurant').val(),
                                                _token: '{{csrf_token()}}'
                                            },
                                            success: (rez) => {
                                                if(rez != 'none'){
                                                    var cont = '';
                                                    $('#popUpAdBody01').html('');
                                                    $('#popUpAdBody02').html('');
                                                    $('#popUpAdBody03').html('');
                                                    $('#popUpAdBody04').html('');

                                                    if(rez.tipi == 1){
                                                        cont =  '<img onclick="openProductAd(\''+rez.prodId+'\')" src="storage/restaurantADS/'+rez.foto+'" style="width:auto; height:100%;" alt="">'+
                                                                '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                                'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                                                        $('#popUpAdBody01').append(cont);
                                                        $('#popUpAd01').modal('toggle'); 
                                                    }else if(rez.tipi == 2){
                                                        cont = ' <a href="https://'+rez.linku+'" target="_blank">'+
                                                                    '<img src="storage/restaurantADS/'+rez.foto+'" style="width:auto; height:100%;" alt="">'+
                                                                    '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                                    'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>'+
                                                                '</a>';
                                                        $('#popUpAdBody02').append(cont);
                                                        $('#popUpAd02').modal('toggle');
                                                    }else if(rez.tipi == 3){
                                                        cont = '<img onclick="openInfoAd(\''+rez.informata+'\')" src="storage/restaurantADS/'+rez.foto+'" style="width:auto; height:100%;" alt="">'+
                                                                '<button style="color: white; width:100%; background-color:transparent; font-size:30px; opacity:1; border:none;"'+
                                                                'type="button" class=" text-center" data-dismiss="modal"><strong>X</strong></button>';
                                                        $('#popUpAdBody03').append(cont);
                                                        $('#popUpAd03').modal('toggle');
                                                    }else if(rez.tipi == 4){
                                                        cont = '<img onclick="openCategoryAd(\''+rez.catId+'\')" src="storage/restaurantADS/'+rez.foto+'" style="max-width:100%; max-height:100%;" alt="">'+
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
                                                alert($('#pleaseUpdateAndTryAgain').val());
                                            }
                                        });

                                    }, parseInt(parseInt(response.forSec) * parseInt(1000)))
                                }else{
                                    // console.log($('#none').val());
                                }
                            },
                            error: (error) => {console.log(error); alert($('#pleaseUpdateAndTryAgain').val()); }
                        });

                    })
                    
</script>


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
    @endif