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

    </style>
<div class="container-fluid mb-4 " id="SAmanage">
    <div class="row mt-4">
        <div class="col-3"></div>
        <div class="col-6 text-center">
            <p style="font-size:45px;" class="color-qrorpa">Please select a restaurant to</p>
            <p style="font-size:45px; margin-top:-40px;" class="color-qrorpa">start editing</p>
        </div>
         <div class="col-3"></div>
    </div>

    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
            @foreach(Restorant::all()->sortByDesc('created_at') as $theRess)
                <a style="width:16%;" href="produktet5Boxes?Res={{$theRess->id}}">
                    <div style="background-color:rgb(39,190,175); border-radius:15px; color:white;" class="p-3 text-center mb-2">
                        <h3>{{$theRess->emri}}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>





