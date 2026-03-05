<?php
if(!isset($_GET['Res'])){
    header("Location: ".route('saStatistics.index'));
    exit();
}

use App\kategori;
use App\Orders;
use App\Produktet;
use App\Restorant;
use App\RestaurantCover;


$theResId = $_GET['Res'];
    $theRes = Restorant::find($theResId);

    $kategoriClicks = kategori::where('toRes', $theResId)->get()->sum('visits');
    $produktetClicks = Produktet::where('toRes', $theResId)->get()->sum('visits');
    $OrdersComplete = Orders::where('Restaurant', $theResId)->get()->count();

    $Orders0 = Orders::where([['Restaurant','=', $theResId],['statusi','=',0]])->get()->count();
    $Orders1 = Orders::where([['Restaurant','=', $theResId],['statusi','=',1]])->get()->count();
    $Orders2 = Orders::where([['Restaurant','=', $theResId],['statusi','=',2]])->get()->count();
    $Orders3 = Orders::where([['Restaurant','=', $theResId],['statusi','=',3]])->get()->count();
?>

<style>
    .backA{
        color:rgb(39, 195, 175);
    }
    .backA:hover{
        color:black;
        text-decoration: none; 
    }

    .ResBoxSt{
        background-color: rgb(39, 195, 175);
        color:white;
        padding: 10px;
        border-radius:25px;
        font-size: 22px;
    }
    .ResBoxSt2{
        background-color: rgb(39, 190, 175);
        color:white;
        padding: 5px;
        border-radius:12px;
        font-size: 19px;
        width:24%;

    }
    #searchProdsREC {
        background-image: url('/css/searchicon.png');
        background-position: 10px 12px;
        background-repeat: no-repeat;
        width: 100%;
        font-size: 16px;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
    }

    .ProdLists {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .ProdLists li {
        border: 1px solid #ddd;
        margin-top: -1px; /* Prevent double borders */
        background-color: #f6f6f6;
        padding: 12px;
        text-decoration: none;
        font-size: 18px;
        color: black;
        display: block;
    }

    .ProdLists li:hover:not(.header) {
        background-color: #eee;
        cursor:pointer;
    }

    textarea:focus, input:focus{
        outline: none;
    }




    .teksti{
            justify-content:space-between;
            margin-top:-60px;
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

        .BannerLinkRes:hover{
            cursor: pointer;
        }
</style>




<section class="pt-4 pb-4 pl-3 pr-3">
    <h3 class="p-2 color-qrorpa"> 
        <span style="font-size:30px;" class="mr-5"><a class="backA" href="{{route('saStatistics.index')}}"> < </a></span>
        <strong>{{$theRes->emri}}</strong> 
        <span class="color-qrorpa ml-5" >{{$theRes->openResTimes}} <i class="far fa-eye"></i> </span>
    </h3>
    <hr>

    <div class="d-flex justify-content-between">
        <div style="width:24.5%;" class="ResBoxSt text-center">
            <p class="mt-2"><strong> Category Clicks: {{$kategoriClicks}} </strong></p>
        </div>
        <div style="width:24.5%;" class="ResBoxSt text-center">
            <p class="mt-2"><strong> Product Clicks: {{$produktetClicks}} </strong></p>
        </div>
        <div style="width:24.5%;" class="ResBoxSt text-center">
            <p class="mt-2"><strong> Orders Placed: {{$OrdersComplete}} </strong></p>
        </div>
        <div style="width:24.5%;" class="ResBoxSt text-center">
            <p class="mt-2"><strong> Banner Clicks: {{$theRes->BannerClick}} </strong></p>
            <p style="margin-top:-15px;" class="BannerLinkRes"><strong> Banner Link Clicks: {{$theRes->BannerLinkClick}} </strong></p>
        </div>
    </div>




    <div id="theLinksCover" class="d-flex justify-content-between flex-wrap mt-3">
        @foreach(RestaurantCover::where('res_id' , $theResId)->get() as $resCo)
            <div style="width:24.8%; border:1px solid lightgray; border-radius:15px;" class="p-2 d-flex">
                <div style="width:20%;" >
                    <p style="font-size:18px;" class="mt-2 pl-2">{{$resCo->linkClick}} <i class="far fa-eye"></i> </p>
                </div>
      
                <div style="width:80%;">    
                    <span class="ml-3"><strong>Link :</strong> {{$resCo->link}}</span> 
                    <br>
                    <span class="ml-3"> <strong>Text :</strong> {{$resCo->text}}</span> 
                </div>
            </div>
        @endforeach
    </div>
        
    

    <h3 class=" mt-4 color-qrorpa">Orders :</h3>
    <div class="d-flex justify-content-between">
        <div class="ResBoxSt2 text-center mt-2">
            <p class="mt-2"><strong> Waiting: {{$Orders0}} </strong></p>
        </div>
        <div class="ResBoxSt2 text-center mt-2">
            <p class="mt-2"><strong> Preparing: {{$Orders1}} </strong></p>
        </div>
        <div class="ResBoxSt2 text-center mt-2">
            <p class="mt-2"><strong> canceled: {{$Orders2}} </strong></p>
        </div>
        <div class="ResBoxSt2 text-center mt-2">
            <p class="mt-2"><strong> Complete: {{$Orders3}} </strong></p>
        </div>
    </div>




    <hr>


    <div style="width:100%;" id="allRestriktProds">
   
        <!-- <input type="text" id="searchProdsREC" onkeyup="searchProdsFuncREC()" placeholder="Search for products.." title="write a products name"> -->

        @foreach(kategori::where('toRes',  $theResId)->get()->sortByDesc('visits') as $kat)
            
        

        <?php echo ' <div style="cursor: pointer; position:relative; object-fit: cover;" class="col12 p-1 text-center"
          onclick="showProKat('.$kat->id.')">'?>
            <img style="border-radius:30px; width:100%; height:120px;" src="storage/kategoriaUpload/{{$kat->foto}}"
                alt="">

            @if(strlen($kat->emri) > 20)
                <div class="teksti d-flex" style="font-size:20px;  margin-bottom:13px;">          
                    <strong>{{$kat->emri}} </strong>
                    <!-- <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> -->
                    <p>{{$kat->visits}} <i class="far fa-eye"></i></p>
                </div>
            @else
                <div class="teksti d-flex" >          
                    <strong>{{$kat->emri}} </strong>
                    <!-- <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> -->
                    <p>{{$kat->visits}} <i class="far fa-eye"></i></p>
                </div>
            @endif
            <input type="hidden" value="0" id="state{{$kat->id}}">
         </div>

            <ul class="ProdLists" id="prodsOf{{$kat->id}}" style="display:none;">
                @foreach(Produktet::where('toRes', $theResId)->where('kategoria', $kat->id)->get()->sortByDesc('visits') as $allPro)
                    <li id="prodListing{{$allPro->id}}">
                        <div class="d-flex">
                            <span style="width:49%;" class="ml-3 col-6 elementViewRest"> <span class="mr-3">{{$allPro->visits}} <i class="far fa-eye"></i></span>
                                 {{$allPro->emri}} 
                                <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span></span>
                            <span style="width:49%;" class=" col-6">
                        
                            </span>
                        </div>
                        
                    </li>
                        
                @endforeach
            </ul>


        @endforeach


    </div>







    <script>
            function showProKat(kId){
                    // $('.ProdLists').hide();
                    if($('#prodsOf'+kId).is(":visible")){
                        $('#prodsOf'+kId).hide();
                    }else{
                        $('#prodsOf'+kId).show();
                    }
                

                }
    </script>


</section>