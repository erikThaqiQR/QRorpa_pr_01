
        <?php

            use App\Produktet;
            use App\kategori;
            use Carbon\Carbon;
            use App\Takeaway;   
            use App\Restorant;
            use App\DeliveryProd;

            $theRestaurant = Restorant::find($_GET["Res"]);
            if($theRestaurant->sortingType == 1){$showCats = Kategori::where('toRes', '=', $_GET["Res"])->get()->sortByDesc('visits');}
            else{ $showCats = Kategori::where('toRes', '=', $_GET["Res"])->get()->sortBy('position'); }
        ?>
        @if(isset($_GET["Res"]))

                <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                  
                ?>

                
                @if (Cookie::has('trackMO') && Cookie::get('trackMO') != 'not')

                    <input type="hidden" value="{{$theRestaurant->id}}" id="checkOrderValidityRES">
                    <input type="hidden" value="{{Cookie::get('trackMO')}}" id="checkOrderValidityTMO">
                    <script>
                        $.when(checkOrderValidity()).done(function(cOVResponse){
                            cOVResponse = cOVResponse.replace(/\s/g, '');
                            if(cOVResponse == 'yesShow'){
                                let divOutNewVal = '<div style="position: fixed; width:70%; left:15%; top:200px; background-color:rgba(39, 190, 175, 0.9); z-index:9999; color:white; border-radius:25px;" class="text-center pt-4 pb-4" id="theProdCodeAlert">'+
                                                        '<h4><strong>{{__("inc.yourOrderCode")}}</strong></h4>'+
                                                        '<h2><strong>{{explode("|",Cookie::get("trackMO"))[1]}}</strong></h2>'+
                                                        '<h2 style="color:white;" class="btn" onclick="hideTheProdCodeAlert()"><strong>X</strong></h2>'+
                                                    '</div>'+
                                                    '<input type="hidden" value="{{Cookie::get("trackMO")}}" id="theProdCodeAlertTrackMOVal">';
                                $('#theProdCodeAlertDivOUT').append(divOutNewVal);
                            }
                        });

                        function checkOrderValidity(){
                            return $.ajax({
                                url: '{{ route("takeaway.checkTakeawayOrderCodeValidation") }}',
                                method: 'post',
                                data: {
                                    res: $('#checkOrderValidityRES').val(),
                                    t: '9000',
                                    shif: $('#checkOrderValidityTMO').val(),
                                    _token: '{{csrf_token()}}'
                                },
                                success: (response) => { return response; },
                                error: (error) => { console.log(error); }
                            });
                        }

                        function hideTheProdCodeAlert(){
                            $('#theProdCodeAlert').hide(500);
                        }
                    </script>
                        

                    <div id="theProdCodeAlertDivOUT"> 

                    </div>
                @endif
           







     @foreach(Kategori::where('toRes', '=', $_GET["Res"])->where('acsByCldDE','1')->orderByDesc('visits')->get() as $kat)
     @if(DeliveryProd::where('kategoria', $kat->id)->get()->count() > 0)
        <input type="hidden" value="{{$kat->emri}}" id="katsForSearch{{$kat->id}}">

        <div class="row allKatFoto" id="KategoriFoto{{$kat->id}}">
            <div class="col-lg-3 col-md-0 col-sm-0 leftSide-kat"></div>
            <div style="cursor: pointer; position:relative; object-fit: cover;" class="col-lg-12 col-md-12 col-sm-12 p-1" onclick="showProKat('{{$kat->id}}')">
            <img style="border-radius:30px; width:100%; height:120px;" src="../storage/kategoriaUpload/{{$kat->foto}}"
                            alt="">

            @if(strlen($kat->emri) > 20)
                <div class="teksti d-flex" style="font-size:20px;  margin-bottom:13px;">          
                    <strong>{{$kat->emri}} </strong>
                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
                </div>
            @else
                <div class="teksti d-flex" >          
                    <strong>{{$kat->emri}} </strong>
                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
                </div>
            @endif
            <input type="hidden" value="0" id="state{{$kat->id}}">
         </div>
        <div class="container prodsFoto" id="prodsKatFoto{{$kat->id}}" style="display:none;">
           
           @foreach(DeliveryProd::where('toRes',$_GET["Res"])->where('kategoria',$kat->id)->where('accessableByClients','1')->orderBy('position')->get() as $ketoProd)
                <div class="row p-2" data-toggle="modal" onclick="oneVisitToProd('{{$ketoProd->id}}')" data-target="#Prod{{$ketoProd->id}}" data-backdrop="static" data-keyboard="false">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3 col-sm-0 col-md-0 leftSide-kat"></div>
                            <div class="col-lg-12 col-sm-12 col-md-12 product-section">
                                <div class="row">
                                    
                                    <di class="col-10">
                                        <h4 class="pull-right prod-name prodsFont color-text" style="font-weight:bold; font-size: 1.20rem ">
                                            {{$ketoProd->emri}} 
                                            @if($ketoProd->restrictPro != 0)
                                                @if($ketoProd->restrictPro == 16)
                                                <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px; height: 20px !important;" src="/storage/icons/R16.jpeg" alt="noImg"></span>
                                                @elseif($ketoProd->restrictPro == 18)
                                                <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px; height: 20px !important;" src="/storage/icons/R18.jpeg" alt="noImg"></span>
                                                @endif
                                            @endif
                                        </h4>
                                            <p style=" margin-top:-10px; font-size:13px;">{{substr($ketoProd->pershkrimi,0,35)}} 
                                                @if(strlen($ketoProd->pershkrimi)>35)
                                                    <span onclick="showTypeMenu('{{$ketoProd->id}}')" class="hover-pointer" style="font-size:16px;">{{__('inc.more')}}</span> 
                                                @endif 
                                            </p>
                                        <h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">
                                            {{__('inc.currencyShow')}}
                                            </span> 
                                            @if(Carbon::now()->format('H:i') >= $theRestaurant->secondPriceTime || Carbon::now()->format('H:i') <= '03:00')
                                                @if($ketoProd->qmimi2 != 999999)
                                                    {{sprintf('%01.2f', $ketoProd->qmimi2)}}
                                                @else
                                                    {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                @endif
                                            @else
                                                @if($ketoProd->qmimi2 != 999999)
                                                    {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                    <?php
                                                        $timesPr2 = strtotime($theRestaurant->secondPriceTime);
                                                        $timesPr21 = $timesPr2 - (20 * 60);
                                                        $timesPr22 = date("H:i", $timesPr21);
                                                    ?>
                                                    @if(Carbon::now()->format('H:i') > $timesPr22 && Carbon::now()->format('H:i') < $theRestaurant->secondPriceTime)
                                                        <span class="ml-4" style="font-size:14px;">Ab {{$theRestaurant->secondPriceTime}} uhr <span style="color:gray;">{{__('inc.currencyShow')}}</span>
                                                        {{sprintf('%01.2f', $ketoProd->qmimi2)}} </span>
                                                    @endif
                                                @else
                                                    {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                @endif
                                            @endif
                                            </h5>
                                    </di>
                                    <div class="col-2 add-plus-section">
                                        <button class="btn mt-2 noBorder" type="button" >
                                            <i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i>
                                        </button>
                                    </div>
                                
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-0 col-md-0  rightSide-kat"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-lg-3 col-md-0 col-sm-0 rightSide-kat"></div>
    </div>
@endif
    @endforeach

 @endif