<?php

use App\billTabletsReg;
use App\Restorant;

    
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <title>quittungen-bowling</title>
    <link rel="icon" href="storage/ResProfilePic/{{Restorant::find(45)->profilePic}}">

    <!-- Lazy Load Library  -->
    <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>

    @include('fontawesome')

    <style>
        .billTabletDiz{
            width: 200px;
            height: 200px;
            margin-bottom: 10px;
            aspect-ratio: 1/1;
            border-radius: 50%;
            font-size: 1.4rem;
            background-color: rgb(39,190,175);
            color: rgb(72,81,87);
            text-align: center;
            text-decoration: none;
        }
        .billTabletDizTxt{
            vertical-align: middle;
            line-height: 200px;
        }
    </style>
</head>
<body>
    <div style="width:90%; margin-left:5%; margin-top:25px;" class="d-flex flex-wrap justify-content-between">

        @foreach (billTabletsReg::where('toRes',45)->get() as $oneTbl)
            <a class="billTabletDiz" href="https://qrorpa.ch/BillTabletsActive?hs={{$oneTbl->scrHash}}">
                <strong class="billTabletDizTxt">{{$oneTbl->nameTitle}}</strong>
            </a>
        @endforeach
     


        
    </div>


    
</body>
</html>