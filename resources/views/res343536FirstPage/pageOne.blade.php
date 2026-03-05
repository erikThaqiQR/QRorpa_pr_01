<?php

    // open res 39 takeaway REDIRECT
    header("Location: https://qrorpa.ch/?Res=39&t=500");
    exit();
    // ---------------------------------------------------------------------------

    use App\Produktet;
    use App\TableQrcode;
    use App\Takeaway;
    use Jenssegers\Agent\Agent;

    $agent = new Agent();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sportrestaurant Obere Au Abholstationen</title>
    <link rel="icon" href="storage/images/qrorpaIcon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

     <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
     @include('fontawesome')

    @if($agent->isMobile())
    <style>
        body{
            background-image: url("storage/images/ausflugsziel-freibad-obere-au-252845.jpg");
        }
        html, body {
            height: unset; 
        }
        .layer {
            background-image: linear-gradient(rgba(61,97,159,0.95), rgba(39,190,175,0.95));
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            min-height:100%;
            height: unset !important;
        }
        .resCard{
            width: 47.5%;
        }
        .descText01{
            width:100%; 
            color:white;
            font-size: 1.1rem;
        }
        .resName{
            font-size: 0.8rem;
        }
    </style>
    @else
    <style>
        body{
            background-image: url("storage/images/ausflugsziel-freibad-obere-au-252845.jpg");
            background-size: cover;
        }
        html, body {
            height: 100%; 
        }
        .layer {
            background-image: linear-gradient(rgba(61,97,159,0.95), rgba(39,190,175,0.95));
            width: 100%;
            height:inherit;
        }
        .resCard{
            width: 24.5%;
        }
        .descText01{
            width:100%; 
            color:white;
            font-size: 1.7rem;
        }
    </style>
    @endif

    <style>
        .blackText{
            color: rgb(72,81,87);
        }
        .prodListingMenu{
            border: 1px solid rgba(39,190,175,1);
            border-radius: 10px;
        }
    </style>
</head>
<body>

    @include('res343536FirstPage.appRes343536FP')
    <div class="layer" style="z-index:1; height:100%;">
        <div class="text-center  d-flex flex-wrap justify-content-around" style="padding-top:2.5cm; z-index:1; ">

            <p class="text-center descText01" style="font-size:2.5rem !important; margin-top:-10px;"><strong>KONTAKTLOS</strong></p>
            <p class="text-center descText01" style="font-size:2rem !important; margin-top:-10px;"><strong>bestellen & bezahlen</strong></p>

            <div class="resCard d-flex flex-wrap justify-content-between mt-3">
                <p class="text-center resName" style="width:100%; background-color:white; margin-bottom:0px;"><strong>Sport Restaurant Obere Au<br> "Restaurant"</strong></p>
                <img src="storage/images/swiPoolPic_5.jpg" style="width: 100%; height:auto;" alt="imgNotFound">
                <button class="btn btn-dark shadow-none" style="width:49.5%; margin:0px;" data-bs-toggle="modal" data-bs-target="#res34ResMenuModal">
                    <strong><i class="fa-solid fa-book-open-reader"></i></strong>
                </button>
                <button class="btn btn-dark shadow-none" style="width:49.5%; margin:0px;" data-bs-toggle="modal" data-bs-target="#res34ResTablesModal" >
                    Tisch
                </button>
            </div>

            <div class="resCard d-flex flex-wrap justify-content-between mt-3">
                <p class="text-center resName" style="width:100%; background-color:white; margin-bottom:0px;"><strong>Sport Restaurant Obere Au<br> "Takeaway"</strong></p>
                <img src="storage/images/swiPoolPic_4.jpg" style="width: 100%; height:auto;" alt="imgNotFound">
                <button class="btn btn-dark shadow-none" style="width:49.5%; margin:0px;" data-bs-toggle="modal" data-bs-target="#res34TaMenuModal">
                    <strong><i class="fa-solid fa-book-open-reader"></i></strong>
                </button>
                <a class="btn btn-dark shadow-none" style="width:49.5%; margin:0px;"  href="https://qrorpa.ch/?Res=34&t=500">
                    Bestellen
                </a>
            </div>

<!-- 
            <div class="resCard d-flex flex-wrap justify-content-between mt-3">
                <p class="text-center resName" style="width:100%; background-color:white; margin-bottom:0px;"><strong>Churoco Bar <br> "Takeaway"</strong></p>
                <img src="storage/images/swiPoolPic_3.jpg" style="width: 100%; height:auto;" alt="imgNotFound">
                <button class="btn btn-dark shadow-none" style="width:49.5%; margin:0px;" data-bs-toggle="modal" data-bs-target="#res35TaMenuModal">
                    <strong><i class="fa-solid fa-book-open-reader"></i></strong>
                </button>
                <a class="btn btn-dark shadow-none" style="width:49.5%; margin:0px;" href="https://qrorpa.ch/?Res=35&t=500">
                    Bestellen
                </a>
            </div>


            <div class="resCard d-flex flex-wrap justify-content-between mt-3">
                <p class="text-center resName" style="width:100%; background-color:white; margin-bottom:0px;"><strong>Genuss Wagen <br> "Takeaway"</strong></p>
                <img src="storage/images/swiPoolPic_1.jpg" style="width: 100%; height:auto;" alt="imgNotFound">
                <button class="btn btn-dark shadow-none" style="width:49.5%; margin:0px;" data-bs-toggle="modal" data-bs-target="#res36TaMenuModal">
                    <strong><i class="fa-solid fa-book-open-reader"></i></strong>
                </button>
                <a class="btn btn-dark shadow-none" style="width:49.5%; margin:0px;" href="https://qrorpa.ch/?Res=36&t=500">
                    Bestellen
                </a>
            </div> -->

            <p class="text-center descText01 mt-4"><strong>Lesen Sie zuerst die Speisekarte oder gehen Sie direkt zur Seite des Restaurants, um direkt von Ihrem Gerät aus zu bestellen und zu bezahlen</strong></p>

        </div>
    </div>










    <!-- Restaurant 34 Tables -->
    <div class="modal fade" id="res34ResTablesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Sport Restaurant Obere Au<br> "Restaurant"</strong></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start">
                    <div class="mb-1" style="width:19.5%; margin-right:0.5%; border:1px solid rgb(39,190,175); border-radius:7px; background-color:rgb(39,190,175);">
                        <p style="margin:0px; width:100%" class="text-center">Tisch</p>
                        <p style="margin:0px; width:100%" class="text-center">XX</p>
                    </div>
                    <p style="width:29.5%; margin-left:0.5%;">Frei</p>

                    <div class="mb-1" style="width:19.5%; margin-right:0.5%; border:1px solid rgb(220, 20, 60); border-radius:7px; background-color:rgb(220, 20, 60)"> 
                        <p style="margin:0px; width:100%" class="text-center">Tisch</p>
                        <p style="margin:0px; width:100%" class="text-center">XX</p>
                    </div>
                    <p style="width:29.5%; margin-left:0.5%;">Besetzt</p>

                    <hr style="width:100%;">

                    @foreach (TableQrcode::where('Restaurant','34')->orderBy('tableNr')->get() as $tbl )
                        @if ($tbl->kaTab == 0)
                        <div class="mb-1" style="width:19.5%; margin-right:0.5%; border:1px solid rgb(39,190,175); border-radius:7px; background-color:rgb(39,190,175);">
                        @else
                        <div class="mb-1" style="width:19.5%; margin-right:0.5%; border:1px solid rgb(220, 20, 60); border-radius:7px; background-color:rgb(220, 20, 60)"> 
                        @endif
                            <p style="margin:0px; width:100%" class="text-center">Tisch</p>
                            <p style="margin:0px; width:100%" class="text-center">{{$tbl->tableNr}}</p>
                        </div>
                    @endforeach
                </div>
           
            </div>
        </div>
    </div>









    <!-- Restaurant 34 Menu -->
    <div class="modal fade" id="res34ResMenuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Sport Restaurant Obere Au<br> "Restaurant"</strong></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach (Produktet::where([['toRes','34'],['accessableByClients','1']])->get() as $orOne )
                        <div class="prodListingMenu d-flex mb-1">
                            <div class="p-2" style="width:80%;">
                                <p class="blackText" style="font-size: 1.3rem; margin-bottom:0px;">{{$orOne->emri}}</p>
                                <p class="blackText" style="font-size: 0.8rem;">{{$orOne->pershkrimi}}</p>
                            </div>
                            <div class="p-2 text-center" style="width:20%;">
                                <p class="blackText" style="font-size: 1.3rem;">{{$orOne->qmimi}} CHF</p>
                            </div>
                        </div>
                    @endforeach
                </div>
           
            </div>
        </div>
    </div>








    <!-- Takeaway 34 Menu -->
    <div class="modal fade" id="res34TaMenuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Sport Restaurant Obere Au<br> "Takeaway"</strong></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach (Takeaway::where([['toRes','34'],['accessableByClients','1']])->get() as $orOne )
                        <div class="prodListingMenu d-flex mb-1">
                            <div class="p-2" style="width:80%;">
                                <p class="blackText" style="font-size: 1.3rem; margin-bottom:0px;">{{$orOne->emri}}</p>
                                <p class="blackText" style="font-size: 0.8rem;">{{$orOne->pershkrimi}}</p>
                            </div>
                            <div class="p-2 text-center" style="width:20%;">
                                <p class="blackText" style="font-size: 1.3rem;">{{$orOne->qmimi}} CHF</p>
                            </div>
                        </div>
                    @endforeach
                </div>
           
            </div>
        </div>
    </div>






    <!-- Takeaway 35 Menu -->
    <div class="modal fade" id="res35TaMenuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Churoco Bar <br> "Takeaway"</strong></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach (Takeaway::where([['toRes','35'],['accessableByClients','1']])->get() as $orOne )
                        <div class="prodListingMenu d-flex mb-1">
                            <div class="p-2" style="width:80%;">
                                <p class="blackText" style="font-size: 1.3rem; margin-bottom:0px;">{{$orOne->emri}}</p>
                                <p class="blackText" style="font-size: 0.8rem;">{{$orOne->pershkrimi}}</p>
                            </div>
                            <div class="p-2 text-center" style="width:20%;">
                                <p class="blackText" style="font-size: 1.3rem;">{{$orOne->qmimi}} CHF</p>
                            </div>
                        </div>
                    @endforeach
                </div>
           
            </div>
        </div>
    </div>





    <!-- Takeaway 36 Menu -->
    <div class="modal fade" id="res36TaMenuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Genuss Wagen<br> "Takeaway"</strong></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach (Takeaway::where([['toRes','36'],['accessableByClients','1']])->get() as $orOne )
                        <div class="prodListingMenu d-flex mb-1">
                            <div class="p-2" style="width:80%;">
                                <p class="blackText" style="font-size: 1.3rem; margin-bottom:0px;">{{$orOne->emri}}</p>
                                <p class="blackText" style="font-size: 0.8rem;">{{$orOne->pershkrimi}}</p>
                            </div>
                            <div class="p-2 text-center" style="width:20%;">
                                <p class="blackText" style="font-size: 1.3rem;">{{$orOne->qmimi}} CHF</p>
                            </div>
                        </div>
                    @endforeach
                </div>
           
            </div>
        </div>
    </div>

</body>
</html>