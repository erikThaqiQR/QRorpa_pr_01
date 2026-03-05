<?php

use App\admMsgSaavchats;
use Illuminate\Support\Facades\Auth;
    use App\Orders;
    use Carbon\Carbon;
?>
<style>
    @keyframes glowingRED {
        0% { box-shadow: 0 0 -5px red; }
        40% { box-shadow: 0 0 30px red; }
        60% { box-shadow: 0 0 30px red; }
        100% { box-shadow: 0 0 -5px red; }
    }

    .button-glow-red {
        animation: glowingRED 700ms infinite;
    }
</style>
    <!-- Restorant Smartphone Nav -->
    <nav class="navbar navbar-expand-sm b-white"  width="100%" id="navPhone">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-left">
                    <?php
                        if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
                            echo '<a class="navbar-brand" href="/?Res='.$_SESSION["Res"].'&t='.$_SESSION["t"].'">';
                        }else{
                            echo '<a class="navbar-brand" href="/">';
                        }
                    ?>
                        <img style="width:130px" src="/storage/images/logo_QRorpa.png" alt="">
                    </a>
                </div>
            </div>
            <div class="row">

                <div class="col-12" id="showBtnModalServiceTel">

                    <?php
                        $takeawayOrCntTel = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
                        ->where([['statusi','<',2],['nrTable','500']])->get()->count();
                        $deliveryOrCntTel = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
                        ->where([['statusi','<',2],['nrTable','9000']])->get()->count();
                        $newMsgForMeTel = admMsgSaavchats::where([['msgForId',Auth::User()->id],['readStatus',0]])->count();
                    ?>
                    @if($takeawayOrCntTel > 0 || $deliveryOrCntTel > 0 || $newMsgForMeTel > 0)
                        @if(isset($_GET['tabs']))
                            <button type="button" class="btn button-glow-red" data-toggle="modal" data-target="#optionsModal"><img src="../storage/icons/listDownRed.PNG"/></button> 
                        @else
                            <button type="button" class="btn button-glow-red" data-toggle="modal" data-target="#optionsModal"><img src="/storage/icons/listDownRed.PNG"/></button> 
                        @endif
                    @else
                        @if(isset($_GET['tabs']))
                            <button type="button" class="btn" data-toggle="modal" data-target="#optionsModal"><img src="../storage/icons/listDown.PNG"/></button> 
                        @else
                            <button type="button" class="btn" data-toggle="modal" data-target="#optionsModal"><img src="/storage/icons/listDown.PNG"/></button> 
                        @endif
                    @endif
                </div>
              
            </div>
        </div>
    </nav>