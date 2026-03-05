<?php
    use App\Restorant;
    use App\admMsgSaavchats;
    use Illuminate\Support\Facades\Auth;
    use App\Orders;
use App\TableReservation;
use Carbon\Carbon;

    $thisRestaurant = Restorant::find(Auth::user()->sFor);
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
                <div class="col-7 d-flex" >
                    <button style="width:50%;" class="btn shadow-none" data-toggle="modal" data-target="#openCloseResModal">
                        <strong> <i class="fa-2x fa-solid fa-door-open"></i> </strong>  
                    </button>

                    <button type="button" style="width:50%;" class="btn shadow-none" data-toggle="modal" data-target="#orderQRCodeTel" onclick="reloadOrderQRCodeTel()">
                        <i style="color:rgb(39,190,175);" class="fas fa-2x fa-qrcode"></i>
                    </button> 
                </div>

                <div class="col-5" id="showBtnModalServiceTel">

                    <?php
                        $takeawayOrCntTel = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
                        ->where([['statusi','<',2],['nrTable','500']])->get()->count();
                        $deliveryOrCntTel = Orders::where('Restaurant',Auth::user()->sFor)->where('created_at', '>',Carbon::now()->subDay())
                        ->where([['statusi','<',2],['nrTable','9000']])->get()->count();
                        $newMsgForMeTel = admMsgSaavchats::where([['msgForId',Auth::User()->id],['readStatus',0]])->count();
                        $newRezRequestsTel = TableReservation::where([['toRes', Auth::user()->sFor],['tableNr', 999999],['status',0]])->whereDate('dita', '>=', Carbon::today())->count();
                    ?>
                    @if($takeawayOrCntTel > 0 || $deliveryOrCntTel > 0 || $newMsgForMeTel > 0 || $newRezRequestsTel > 0)
                        @if(isset($_GET['tabs']))
                            <button type="button" class="btn button-glow-red shadow-none" data-toggle="modal" data-target="#optionsModal"><img src="../storage/icons/listDownRed.PNG"/></button> 
                        @else
                            <button type="button" class="btn button-glow-red shadow-none" data-toggle="modal" data-target="#optionsModal"><img src="/storage/icons/listDownRed.PNG"/></button> 
                        @endif
                    @else
                        @if(isset($_GET['tabs']))
                            <button type="button" class="btn shadow-none" data-toggle="modal" data-target="#optionsModal"><img src="../storage/icons/listDown.PNG"/></button> 
                        @else
                            <button type="button" class="btn shadow-none" data-toggle="modal" data-target="#optionsModal"><img src="/storage/icons/listDown.PNG"/></button> 
                        @endif
                    @endif
                </div>
              
            </div>
        </div>
    </nav>






    <!-- Modal -->
    <div class="modal" id="openCloseResModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
    data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding: 0px 0px 0px 0px;" aria-modal="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="openCloseResModalLabel"><strong>Restaurant, Takeaway, Lieferung (Geöffnet / Geschlossen)</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body" id="openCloseResModalBody">
                    @php
                        $isOp = explode('-||-',$thisRestaurant->isOpen);
                    @endphp
                    <div class="d-flex flex-wrap justify-content between" id="openCloseResModalBodyDiv1">
                        <button class="btn btn-success shadow-none mb-1" style="width:20%; margin:0px;"></button>
                        <p style="width:80%; margin:0px;" class="pl-1 pt-1 mb-1"><strong>Der Service ist geöffnet</strong></p>
                        <button class="btn btn-danger shadow-none" style="width:20%; margin:0px;"></button>
                        <p style="width:80%; margin:0px;" class="pl-1 pt-1"><strong>Der Service ist geschlossen</strong></p>

                        <hr style="width:100%" class="mt-2 mb-2">

                        <p style="width:100%; font-size:1.4rem; color:rgb(39,190,175); margin:10px 0px 10px 0px;" class="text-center">
                            <strong>Klicken Sie darauf, um den Status zu ändern</strong>
                        </p>

                        @if ( $isOp[0] == 1)
                            <button class="btn btn-success shadow-none mb-1" style="width:100%; font-weight:bold;" onclick="chngResOpenStatus('Res','0')">Restaurant</button>
                        @elseif ( $isOp[0] == 0)
                            <button class="btn btn-danger shadow-none mb-1" style="width:100%; font-weight:bold;" onclick="chngResOpenStatus('Res','1')">Restaurant</button>
                        @endif

                        @if ( $isOp[1] == 1)
                            <button class="btn btn-success shadow-none mb-1" style="width:100%; font-weight:bold;" onclick="chngResOpenStatus('TA','0')">Takeaway</button>
                        @elseif ( $isOp[1] == 0)
                            <button class="btn btn-danger shadow-none mb-1" style="width:100%; font-weight:bold;" onclick="chngResOpenStatus('TA','1')">Takeaway</button>
                        @endif

                        @if ( $isOp[2] == 1)
                            <button class="btn btn-success shadow-none mb-1" style="width:100%; font-weight:bold;" onclick="chngResOpenStatus('DE','0')">Delivery</button>
                        @elseif ( $isOp[2] == 0)
                            <button class="btn btn-danger shadow-none mb-1" style="width:100%; font-weight:bold;" onclick="chngResOpenStatus('DE','1')">Delivery</button>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function chngResOpenStatus(service,newStat){
            $('#openCloseResModalBodyDiv1').html('<img src="storage/gifs/loading2.gif" style="width: 30%; margin-left:35%;" alt="">');
            $.ajax({
                url: '{{ route("dash.changeResOpenStatusTH") }}',
                method: 'post',
                data: {
                theS: service,
                newSt: newStat,
                _token: '{{csrf_token()}}'
                },
                success: () => {
                $("#openCloseResModalBody").load(location.href+" #openCloseResModalBody>*","");
                },
                error: (error) => { console.log(error); }
            });
        }
    </script>