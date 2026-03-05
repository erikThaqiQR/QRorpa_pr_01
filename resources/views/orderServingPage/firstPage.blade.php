<?php

use App\TabOrder;
use App\orderServingOrderShow;
use Illuminate\Support\Facades\Auth;

  
?>


<style>
    :root {
        --blockSize: 100px;
    }

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
    p,h4,h5{
        margin:0px;
    }
    p{
        font-size: 12px;
    }
    h3{
        font-size: 17px;
    }
    h4{
        font-size: 15px;
    }
    h5{
        font-size: 12px;
    }

    .productName{
        width: 65%;
        background-color: rgba(231,229,230,255);

        border-top:2px solid rgb(72,81,87);
        border-left:2px solid rgb(72,81,87);
    }
    .productDoneAll{
        width: 35%;
        background-color: rgba(231,229,230,255);

        border-top:2px solid rgb(72,81,87);
        border-right:2px solid rgb(72,81,87);
    }
    .proColDetailsHead{
        width: 33.33334%;
        border-bottom:1px solid rgb(72,81,87);
    }
    .proColDetails{
        width: 33.33334%;
    }
    .proColDetailsBottom{
        width: 33.33334%;
        border-bottom:1px dotted rgb(72,81,87);
        margin-bottom: 5px;
        padding-bottom: 5px;
    }

    .prodListDown{
        border-bottom:2px solid rgb(72,81,87);
        border-left:2px solid rgb(72,81,87);
        border-right:2px solid rgb(72,81,87);
    }

    .crsPointer:hover{
        cursor: pointer;
    }

    @keyframes glowing {
        0% { box-shadow: 0 0 -10px rgb(208, 250, 207); }
        40% { box-shadow: 0 0 20px rgb(208, 250, 207); }
        60% { box-shadow: 0 0 20px rgb(208, 250, 207); }
        100% { box-shadow: 0 0 -10px rgb(208, 250, 207); }
    }

    .cookDivGlowCalled {
        animation: glowing 700ms infinite;
    }

    .TableColumnCookTOAll{
        background-color:transparent; 
        border-radius:6px; 
        height:fit-content; 
        width:var(--blockSize); 
        margin-right:2px; 
        flex-direction: row;
    }
</style>

<script>
    let ScreenWidth = parseInt(screen.width - 30);
    let blockSize = parseFloat((ScreenWidth / parseInt('{{$theDevice->showColPerDev}}'))-2);

    var r = document.querySelector(':root');
    r.style.setProperty('--blockSize', blockSize+'px');
</script>


<div style="background-color: rgb(39,190,175); min-height: 45cm;" class="pt-1" id="orderServingDivAll">
    <p class="text-center" style="width:100%; color: white; font-size:2rem;"><strong>Restaurant</strong></p>

    <?php
        $allTables = array();
        $tabsToShow = array();
        foreach(orderServingOrderShow::where('deviceId',$theDevice->id)->orderBy('created_at')->get() as $shOrFilterOne){
            array_push($tabsToShow,$shOrFilterOne->tabOrderId);
            if(!in_array($shOrFilterOne->tableNr,$allTables)){
                array_push($allTables,$shOrFilterOne->tableNr);
            }
        }
    ?>
      
    <div class="d-flex flex-wrap" style="background-color:rgb(39,190,175) ;">
        @foreach($allTables as $rTable)
            <div class="d-flex flex-wrap TableColumnCookTOAll" id="TableColumnCookTO{{$rTable}}">
                <h2 class="text-center mb-2" style="width:100%; height:fit-content; border-radius:6px; background-color:rgba(191,191,191,255); margin-bottom:0px;"><strong>Tisch {{$rTable}}</strong></h2>
          
                    @foreach(TabOrder::where([['tabCode','!=','0'],['toRes',$theDevice->toRes],['tableNr',$rTable]])->get() as $oTOrThisT)
                        @if(in_array($oTOrThisT->id,$tabsToShow))

                            <?php
                                $orShoDevi = orderServingOrderShow::where('tabOrderId',$oTOrThisT->id)->first();
                                $orTi = explode(':',explode(' ',$orShoDevi->created_at)[1]);
                            ?>

                            <div onclick="orderServedYes('{{$rTable}}','{{$oTOrThisT->id}}','{{$orShoDevi->id}}','{{$oTOrThisT->toRes}}')"
                            class="prodOneT{{$rTable}} text-left justify-content-between flex-wrap crsPointer mb-1 p-1 pt-2 pb-2" 
                            style="width: 100%; background-color:rgba(208,206,207,255); display:flex;"
                            id="prodOneT{{$oTOrThisT->id}}">
                                <p style="width:100%"><strong>Gekocht bei: {{$orTi[0]}}:{{$orTi[1]}}</strong></p>
                                <h4 style="width:84%"><strong>{{$oTOrThisT->OrderEmri}}</strong></h4>
                                <h4 class="text-right" style="width:15%"><strong>{{$oTOrThisT->OrderSasia}}X</strong></h4>
                                @if($oTOrThisT->OrderType != 'empty')
                                <h5 style="width:100%"><strong>Typ: {{explode('||',$oTOrThisT->OrderType)[0]}}</strong></h5>
                                @endif
                                @if($oTOrThisT->OrderExtra != 'empty')
                                <h5 style="width:100%"><strong>Extra:
                                    @foreach (explode('--0--',$oTOrThisT->OrderExtra) as $exO)
                                        @if ($loop->first)
                                            {{explode('||',$exO)[0]}}
                                        @else
                                            , {{explode('||',$exO)[0]}}
                                        @endif
                                    @endforeach
                                </strong></h5>
                                @endif
                                @if($oTOrThisT->OrderKomenti != NULL)
                                <h5 style="width:100%"><strong>Kommentar: <span style="color:red;">{{$oTOrThisT->OrderKomenti}}</span></strong></h5>
                                @endif
                            </div>
                        @endif
                    @endforeach
             

                <!-- <script>
                    $('#TableColumnCookTO{{$rTable}}').attr('style','background-color:transparent; border-radius:6px; height:fit-content; width:'+blockSize+'px; margin-right:2px; flex-direction: row');
                </script> -->
                
            </div>
            
        @endforeach
    </div>
</div>

<script>
    function orderServedYes(tableNr, tabOrId, orIdDeviShow, resId){
        $('#prodOneT'+tabOrId).attr('style','width: 100%; background-color:rgba(4,178,89,255); display:flex;')
        $.ajax({
			url: '{{ route("orServing.orderServingDevicesConfServeProd") }}',
			method: 'post',
			data: {
				tabOrderId: tabOrId,
				orShowDeviceId: orIdDeviShow,
                restoId: resId,
                deviceId: $('#deviceIdInput').val(),
				_token: '{{csrf_token()}}'
			},
			success: () => {
                // console.log(parseInt($("#TableColumnCookTO"+tableNr+" .prodOneT"+tableNr).length - 1));
                // console.log(parseInt($("#TableColumnCookTO"+tableNr+" .prodOneT"+tableNr).length - 1) == 0);
                if(parseInt($("#TableColumnCookTO"+tableNr+" .prodOneT"+tableNr).length - 1) == 0){
                    $("#TableColumnCookTO"+tableNr).remove();
                }else{
                    $("#TableColumnCookTO"+tableNr).load(location.href+" #TableColumnCookTO"+tableNr+">*","");
                }
			},
			error: (error) => { console.log(error); }
		});
    }
</script>
