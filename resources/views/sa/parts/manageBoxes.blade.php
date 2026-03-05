<?php
    // use App\kategori;
    // use App\ekstra;
    // use App\LlojetPro;
    use App\Restorant;
    // use App\Produktet;

    // $kat = kategori::all();
    // $thisKat = ekstra::all();
    // $types = LlojetPro::all();
    // $restaurant = Restorant::all()->sortByDesc('created_at');
    // $produktet = Produktet::all();
?>
<style>
        .direktiveBox{
            color:white;
            border-radius:10px;
            margin-top:35px;
            padding-top:50px;
            padding-bottom:50px;

            background-color:rgb(39,190,175);
        }
        .direktiveBox:hover{
            cursor: pointer;
        }

        .backBtn{
            opacity:0.5;
        }
        .backBtn:hover{
            opacity:0.95;
            cursor: pointer;
        }

        .ResShow{
            background-color:rgb(39,190,175);
            color:white;
            border-radius:20px;
        }
        .ResShow:hover{
            cursor: pointer;
        }

        .anchorMy{
            color: black;
            text-decoration: none;
        }
        .anchorMy:hover{
            color: rgb(39,190,175);
            text-decoration: none;
        }

    </style>
<div class="container-fluid mb-4 " >
    <div class="row mt-4">
        <a href="{{route('SAProduktet.index')}}" class="col-2 anchorMy pt-4" style="font-size:25px;"><strong> < Back </strong></a>
        <div class="col-8 text-center">
            <p style="font-size:45px;" class="color-qrorpa">"{{Restorant::find($_GET['Res'])->emri}}"</p>
            <p style="font-size:45px; margin-top:-40px;" class="color-qrorpa">select a box to continue ...</p>
        </div>
         <div class="col-2"></div>
    </div>
</div>

<div class="container mb-4 ">
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
      
            <a style="width:49%;" href="produktet5Category?Res={{$_GET['Res']}}">
                <div style="background-color:rgb(39,190,175); border-radius:15px; color:white;" class="p-3 text-center mb-2">
                    <h2>Category</h2>
                </div>
            </a>

            <a style="width:49%;" href="produktet5Product?Res={{$_GET['Res']}}">
                <div style="background-color:rgb(39,190,175); border-radius:15px; color:white;" class="p-3 text-center mb-2">
                    <h2>Products</h2>
                </div>
            </a>

            <a style="width:49%;" href="produktet5Extra?Res={{$_GET['Res']}}">
                <div style="background-color:rgb(39,190,175); border-radius:15px; color:white;" class="p-3 text-center mb-2">
                    <h2>Extra</h2>
                </div>
            </a>

            <a style="width:49%;" href="produktet5Type?Res={{$_GET['Res']}}">
                <div style="background-color:rgb(39,190,175); border-radius:15px; color:white;" class="p-3 text-center mb-2">
                    <h2>Types</h2>
                </div>
            </a>

        </div>
    </div>
 
</div>