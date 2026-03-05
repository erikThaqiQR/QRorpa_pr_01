<?php

use App\orderServingDevices;
use Jenssegers\Agent\Agent;
      $agent = new Agent();
?>


@if(isset($_GET['hs']))
  <?php
    $theDevice = orderServingDevices::where('theHash',$_GET['hs'])->first();
    if($theDevice == Null){
        header("Location: ".route('login'));
        exit();
    }
  ?>
@endif

<!DOCTYPE html>
<html lang="de" translate="no">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="description" content="">
        <meta name="author" content="">

        <title> {{__('adminP.dashboard')}}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Custom fonts for this template-->
        
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

        <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
        @include('fontawesome')

        <script src="https://js.pusher.com/6.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <?php session_start();?>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
        <!-- swiper library -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

        <style>
            .optionsAnchorPh{
                color:black;
                text-decoration:none;
                opacity:0.85;
                font-weight: bold;
                font-size:17px;
                width: 100%;
                display: block;

                padding: 10px 0px 10px 50px;
                background-color: white;
                background-size: cover;
                margin-bottom: 10px;
            }
            .optionsAnchorPh:hover{
                opacity:0.100;
                text-decoration:none;
                color:black;
                
            }
        </style>

        <!-- Matomo -->
      
        <!-- End Matomo Code -->
    </head>

    <body style="overflow-x:hidden;">
        
    <input type="hidden" value="{{$theDevice->id}}" id="deviceIdInput">

            <div id="allTelCook">
                @include('orderServingPage.topNavbar')
                @include('orderServingPage.njoftimet')
                <div id="contentCook">
                    @include('orderServingPage.firstPage')
                </div>
            </div>
   
        <script src="{{ asset('js/app.js') }}" defer></script>
    </body>
</html>