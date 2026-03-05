<?php
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Delivery']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\ekstra;
    use App\Produktet;
    use App\DeliveryProd;
    use App\kategori;
    use App\LlojetPro;
    use App\Restorant;

    use App\RecomendetProd;
    use App\DeliverySchedule;
    use Carbon\Carbon;

    use App\DeliveryPLZ;
use App\deliveryPlzCharge;

?>
<style>

    #searchProdsTA {
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
    button:focus {outline:none !important;}

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
    .rec .teksti{
        margin:0 auto;
        margin-bottom:10px;
    }
    .rec{
        margin-bottom:10px;
    }
    
        @media (max-width: 375px) {
            .emriRec {
                margin-top: -17px;
                margin-bottom: -30px;
            }
            .recProElement {
                height: auto;
                width: 100px;
            }
        }

        @media (max-width:420px) and (min-width:376px) {
            .emriRec {
                margin-top: -16px;
                margin-bottom: -30px;
            }

            .recProElement {
                height: auto;
                width: 100px;

            }
        }

        @media (max-width:600px) and (min-width:421px) {
            .emriRec {
                margin-top: -16px;
                margin-bottom: -30px;
            }

            .recProElement {
                height: auto;
                width: 100px;

            }
        }

        @media (max-width:2400px) and (min-width:601px) {
            .emriRec {
                margin-top: -16px;
            }

            .recProElement {
                height: auto;
                width: 90px;
            }
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

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            float:right; 
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }


        #saveAllPLZTime:hover{
            cursor: pointer;
            opacity: 1;
        }

      
</style>

<script>
    var last="";
</script>


<section class="pl-2 pr-2 pb-5">
      
            <div class="swiper-container p-0" style="border-top-left-radius:50px; border-bottom-left-radius:50px; overflow: hidden; " id="taRecomendetPro">
                <div class="swiper-wrapper" id="recProdList">

                    @foreach(DeliveryProd::where([['toRes', '=', Auth::user()->sFor],['recomendet','=', '1']])->get()->sortBy('recomendetNr') as $RePro)
                    <div class="swiper-slide recProElement" data-backdrop="static" data-keyboard="false">
                        <img style="width:120px; height:120px; border-radius:50%;" src="storage/DeliveryRecomendet/{{$RePro->recomendetPic}}"
                            alt="image">
                        <p style=" width:100%; font-size:13px;"><strong>
                            {{$RePro->recomendetNr}}# | {{$RePro->emri}}

                            </strong></p>
                        <p style="font-size:14px;"><span style="opacity:0.6; ">{{__('adminP.currencyShow')}} </span>

                            @if(Carbon::now()->format('H:i') >= '20:00' || Carbon::now()->format('H:i') <= '03:00')
                                @if($RePro->qmimi2 != 999999)
                                    {{sprintf('%01.2f', $RePro->qmimi2)}}
                                @else
                                    {{sprintf('%01.2f', $RePro->qmimi)}} 
                                @endif
                            @else
                                {{sprintf('%01.2f', $RePro->qmimi)}} 
                            @endif
                        </p>
                        <p class="d-flex">
                            @if($RePro->recomendetNr != 1)
                            <button class="btn btn-outline-dark" onclick="minusOneTARec('{{$RePro->id}}')" style="width:33%;"> < </button>
                            @else
                            <button class="btn btn-default" style="width:33%;"></button>
                            @endif


                                <button class="btn btn-outline-dark" style="width:33%;" onclick="deRemoceRec('{{$RePro->id}}')">x</button>


                            @if($RePro->recomendetNr < DeliveryProd::where([['toRes', '=', Auth::user()->sFor],['recomendet','=', '1']])->get()->max('recomendetNr'))
                            <button class="btn btn-outline-dark" onclick="plusOneTARec('{{$RePro->id}}')" style="width:33%;" > > </button>
                            @else
                            <button class="btn btn-default" style="width:33%;"></button>
                            @endif

                            
                        </p>
                    </div>

                    @endforeach
                  
                </div>
            </div>

        <script>
                var swiper = new Swiper('.swiper-container', {
                    slidesPerView: 3,
                    spaceBetween: 10,
                    breakpoints: {
                // when window width is >= 320px
                320: {
                slidesPerView: 3,
                spaceBetween: 10
                },
                // when window width is >= 480px
                480: {
                slidesPerView: 3,
                spaceBetween: 10
                },
                // when window width is >= 640px
                640: {
                slidesPerView: 4,
                spaceBetween: 10
                },
                900: {
                slidesPerView: 5,
                spaceBetween: 10
                },
                1200: {
                slidesPerView: 6,
                spaceBetween: 10
                }
            }
                });
    </script>






    <script>
        function minusOneTARec(RecTAId){
            $.ajax({
                url: '{{ route("delivery.minusOneRec") }}',
                method: 'post',
                data: {id: RecTAId, _token: '{{csrf_token()}}'},
                success: () => {
                    location.reload();
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongRemoveOneTakeawayProd').val());
                }
            });
        }
        function plusOneTARec(RecTAId){
            $.ajax({
                url: '{{ route("delivery.plusOneRec") }}',
                method: 'post',
                data: {id: RecTAId, _token: '{{csrf_token()}}'},
                success: () => {
                    location.reload();
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongRemoveOneTakeawayProd').val());
                }
            });
        }

        function deRemoceRec(recTaId){
            $.ajax({
                url: '{{ route("delivery.removeRec") }}',
                method: 'post',
                data: {id: recTaId, _token: '{{csrf_token()}}'},
                success: () => {
                    location.reload();
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongRemoveOneTakeawayProd').val());
                }
            });
        }
    </script>

  





























                        <!-- Add Takeaway Modal -->
                        <div class="modal" id="addDeProdDe" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">{{__('adminP.addNewTakeawayProduct')}}</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                {{Form::open(['action' => 'DeliveryController@store', 'method' => 'post' ]) }}
                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <div class="form-group">
                                            {{ Form::label(__('adminP.name'), null, ['class' => 'control-label']) }}
                                            {{ Form::text('emri','', ['class' => 'form-control shadow-none' , 'required']) }}
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {{ Form::label(__('adminP.description'), null , ['class' => 'control-label']) }}
                                                    {{ Form::textarea('pershkrimi','', ['class' => 'form-control shadow-none', 'rows'=>'3' , 'required']) }}
                                                </div>
                                            </div> 
                                        </div>

                                        <div class="row mt-1 mb-2" >
                                            <div class="col-12 form-check" style="margin-left: 15px;">
                                                <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChngInd()" id="accessByClientsInd" checked>
                                                <label class="form-check-label pl-2" style="font-size:1.3rem;" for="accessByClientsInd"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                                <input type="hidden" id="accessByClientsValInd" name="accessByClientsValInd" value="1">
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    {{ Form::label(__('adminP.price'), null , ['class' => 'control-label']) }}
                                                    {{ Form::number('qmimi','', ['class' => 'form-control shadow-none', 'step'=>'0.01' , 'min'=>'0' , 'required']) }}
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    {{ Form::label(__("adminP.price"). '20:01+ (' .__("adminP.optional"). ')', null , ['class' => 'control-label']) }}
                                                    {{ Form::number('qmimi2','', ['class' => 'form-control shadow-none', 'step'=>'0.01' , 'min'=>'0']) }}
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="kat">{{__('adminP.category')}}</label>
                                                    <select name="kategoria" class="form-control shadow-none" 
                                                    onchange="showExtrasTA(this.value, '{{Auth::user()->sFor}}')">
                                                        <option value="0">{{__('adminP.chooseCategory')}}</option>
                                                    
                                                            @foreach(kategori::where('toRes', Auth::user()->sFor)->get() as $kats)
                                                               <option value="{{$kats->id}}">{{$kats->emri}}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <?php $step = 1;  ?>

                                            @foreach(kategori::where('toRes', Auth::user()->sFor)->get() as $kats)
                                                <div id="BoxKatTA{{$kats->id}}R{{Auth::user()->sFor}}" style="display:none;">
                                                        <div class="container">
                                                            <div class="row">
                                                                    <div class="col-6">
                                                                        <ul class="list-group list-group-flush">
                                                                        @foreach(ekstra::where('toCat', '=', $kats->id)->get() as $ekstras)
                                                                                <li class="list-group-item">
                                                                                    {{$ekstras->emri}} 
                                                                                    <label class="switch ">
                                                                                    <input type="checkbox" class="success" id="extP{{$ekstras->id}}" 
                                                                                            onchange="addThisTA(this.id,'{{$ekstras->emri}}','{{$ekstras->qmimi}}','{{Auth::user()->sFor}}','{{$ekstras->id}}')">
                                                                                            <span class="slider round"></span>
                                                                                    </label>
                                                                                </li>
                                                                        @endforeach
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <ul class="list-group list-group-flush">
                                                                        @foreach(LlojetPro::where('kategoria', '=', $kats->id)->get() as $proLl)
                                                                                <li class="list-group-item">
                                                                                    {{$proLl->emri}} 
                                                                                    <label class="switch">
                                                                                    <input type="checkbox" class="success" id="LlPro{{$proLl->id}}" 
                                                                                        onchange="addThis2TA(this.id,'{{$proLl->emri}}','{{$proLl->vlera}}','{{Auth::user()->sFor}}','{{$proLl->id}}')">
                                                                                        <span class="slider round"></span>
                                                                                    </label>
                                                                                </li>
                                                                                    
                                                                        @endforeach
                                                                        </ul>
                                                                    </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                                        
                                            @endforeach

                                        <!-- <input type="hidden" emri="extPro" id="extPro"> -->
                                        {{ Form::hidden('extPro', '' , ['id' => 'extPro'.Auth::user()->sFor]) }}
                                        {{ Form::hidden('typePro', '' , ['id' => 'typePro'.Auth::user()->sFor]) }}
                                        {{ Form::hidden('restaurant', Auth::user()->sFor , ['id' => 'restaurant']) }}
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'form-control btn btn-success btn-block','style'=>'font-weight:bold;']) }}
                                    </div>
                                {{Form::close() }}
                                </div>
                            </div>
                        </div>


























                        <div id="editModalsTA">
                        @foreach(DeliveryProd::where('toRes', Auth::user()->sFor)->get() as $delvAProd)

                            <!-- Edit Delivery Modal -->
                            <div class="modal" id="editDeProd{{$delvAProd->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                
                                        <h4 class="modal-title">{{$delvAProd->emri}} <span style="opacity:0.6;">( {{kategori::find($delvAProd->kategoria)->emri}} )</span></h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">

                                        {{Form::open(['action' => 'DeliveryController@addToRec', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                                            <div class="d-flex justify-content-between">
                                            
                                                <div style="width:70%;" class="custom-file">
                                                    {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                                                    {{ Form::file('foto', ['class' => 'custom-file-input' , 'required']) }}
                                                </div>
                                                <input type="hidden" value="{{$delvAProd->id}}" name="id">
                                                <button style="width:30%;" type="submit" class="btn btn-outline-primary">+ {{__('adminP.recommended')}}</button>

                                            </div>
                                        {{Form::close() }}
                                        <hr>

                                        <div class="form-group">
                                            <label for="name">{{__('adminP.name')}}:</label>
                                            <input type="text" class="form-control shadow-none" id="name{{$delvAProd->id}}" value="{{$delvAProd->emri}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="pershkrimi">{{__('adminP.description')}}:</label>
                                            <input type="text" class="form-control shadow-none" id="pershkrimi{{$delvAProd->id}}" value="{{$delvAProd->pershkrimi}}">
                                        </div>

                                        <div class="mt-1 mb-2" >
                                            <div class="form-check">
                                                @if($delvAProd->accessableByClients == 1)
                                                <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$delvAProd->id}}')" id="accessByClients{{$delvAProd->id}}" checked>
                                                <label class="form-check-label pl-2" style="font-size:1.3rem;" for="accessByClients{{$delvAProd->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                                <input type="hidden" id="accessByClientsVal{{$delvAProd->id}}" name="accessByClientsVal" value="1">
                                                @else
                                                <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$delvAProd->id}}')" id="accessByClients{{$delvAProd->id}}">
                                                <label class="form-check-label pl-2" style="font-size:1.3rem;" for="accessByClients{{$delvAProd->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                                <input type="hidden" id="accessByClientsVal{{$delvAProd->id}}" name="accessByClientsVal" value="0">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-between">
                                            <div style="width:45%;">
                                                <label for="qmimi">{{__('adminP.price')}}:</label>
                                                <input type="text" class="form-control shadow-none" id="qmimi{{$delvAProd->id}}" value="{{$delvAProd->qmimi}}">
                                            </div>
                                            <div style="width:45%;">
                                                <label for="qmimi2">{{__('adminP.price')}} (+20:00):</label>
                                                @if($delvAProd->qmimi2 != 999999)
                                                    <input type="number" class="form-control shadow-none" id="qmimi2{{$delvAProd->id}}" value="{{$delvAProd->qmimi2}}">
                                                @else
                                                    <input type="number" class="form-control shadow-none" id="qmimi2{{$delvAProd->id}}">
                                                @endif
                                            </div>
                                        </div>

                                        <input type="hidden" value="{{$delvAProd->mwstForPro}}" id="dePMwst{{$delvAProd->id}}">

                                        <div class="d-flex justify-content-between">
                                            @if ($delvAProd->mwstForPro == 2.60)
                                                <button id="setMwst{{$delvAProd->id}}Btn1" onclick="setNewMwst('2.60','{{$delvAProd->id}}')" style="width:49%;" class="btn btn-dark"><strong>MwST: 2.60 %</strong></button>
                                                <button id="setMwst{{$delvAProd->id}}Btn2" onclick="setNewMwst('8.10','{{$delvAProd->id}}')" style="width:49%;" class="btn btn-outline-dark"><strong>MwST: 8.10 %</strong></button>
                                            @elseif ($delvAProd->mwstForPro == 8.10)
                                                <button id="setMwst{{$delvAProd->id}}Btn1" onclick="setNewMwst('2.60','{{$delvAProd->id}}')" style="width:49%;" class="btn btn-outline-dark"><strong>MwST: 2.60 %</strong></button>
                                                <button id="setMwst{{$delvAProd->id}}Btn2" onclick="setNewMwst('8.10','{{$delvAProd->id}}')" style="width:49%;" class="btn btn-dark"><strong>MwST: 8.10 %</strong></button>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer d-flex justify-content-between">
                                        <button style="width:45%;" type="button" class="btn btn-block btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                                        <button onclick="editDEProduct('{{$delvAProd->id}}')" style="width:45%;" type="button" class="btn btn-block btn-outline-success" data-dismiss="modal">{{__('adminP.save')}}</button>
                                    </div>

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <script>
                            // Add the following code if you want the name of the file appear on select
                            $(".custom-file-input").on("change", function() {
                                var fileName = $(this).val().split("\\").pop();
                                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                            });

                            function setNewMwst(mwstVal,dePId){
                                if(mwstVal == 2.60){
                                    if($('#setMwst'+dePId+'Btn1').hasClass('btn-outline-dark')){
                                        $('#setMwst'+dePId+'Btn1').removeClass('btn-outline-dark');
                                        $('#setMwst'+dePId+'Btn1').addClass('btn-dark');

                                        $('#setMwst'+dePId+'Btn2').removeClass('btn-dark');
                                        $('#setMwst'+dePId+'Btn2').addClass('btn-outline-dark');
                                       
                                        $('#dePMwst'+dePId).val(parseFloat(mwstVal).toFixed(2))
                                    }
                                }else if(mwstVal == 8.10){
                                    if($('#setMwst'+dePId+'Btn2').hasClass('btn-outline-dark')){
                                        $('#setMwst'+dePId+'Btn2').removeClass('btn-outline-dark');
                                        $('#setMwst'+dePId+'Btn2').addClass('btn-dark');

                                        $('#setMwst'+dePId+'Btn1').removeClass('btn-dark');
                                        $('#setMwst'+dePId+'Btn1').addClass('btn-outline-dark');
                                       
                                        $('#dePMwst'+dePId).val(parseFloat(mwstVal).toFixed(2))
                                    }
                                }
                            }

                            function accessToClChng(pId){
                                if($("#accessByClients"+pId).is(':checked')){
                                    $('#accessByClientsVal'+pId).val('1');
                                }else{
                                    $('#accessByClientsVal'+pId).val('0');
                                }
                            }

                            function accessToClChngInd(){
                                if($("#accessByClientsInd").is(':checked')){
                                    $('#accessByClientsValInd').val('1');
                                }else{
                                    $('#accessByClientsValInd').val('0');
                                }
                            }
                        </script>












































    <style>
        .workingHBtn:hover{
            color:white;
            background-color: rgb(39,190,175);
            font-size: 20px;
        }
        .workingHBtn{
            color:rgb(39,190,175);
            border:1px solid rgb(39,195,175);
            font-size: 20px;
        }
    </style>

    <div class="d-flex justify-content-between">
        <button style="width:49%;" class="btn btn-block mb-2 mt-2 shadow-none workingHBtn" data-toggle="modal" data-target="#deliverySchedule"> <strong>{{__('adminP.deliveryTime')}}</strong> </button>
        <button style="width:49%;" class="btn btn-block mb-2 mt-2 shadow-none workingHBtn" data-toggle="modal" data-target="#deliveryPLZmodal"> <strong>{{__('adminP.allowedZIPCode')}}</strong> </button>
    </div>


    <!--  Delivery PLZ Modal -->
    <div class="modal" id="deliveryPLZmodal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header" style="background-color:rgb(39,190,175);">
                    <h4 class="modal-title" style="font-weight:bold; color:white;">{{__('adminP.deliveryAllowedZIPCode')}}</h4>
                    <button style="font-weight:bold; color:white;" type="button" class="close" data-dismiss="modal">X</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <!-- PLZ MIN ALL -->

                        <input type="text" style="width:18.99%;" class="form-control shadow-none" placeholder="{{__('adminP.postCode')}}" id="plzVal">
                        <input type="text" style="width:18.99%;" class="form-control shadow-none" placeholder="zeit 1" id="plzTimeVal">
                        <input type="text" style="width:18.99%;" class="form-control shadow-none" placeholder="zeit 2" id="plzTimeVal2">
                        <input type="text" style="width:18.99%;" class="form-control shadow-none" placeholder="Mindestbestellwert in CHF" id="plzMinOrderCHF">
                        

                        <input type="hidden" value="{{Auth::user()->sFor}}" id="theResId">
                        <button onclick="saveDelPLZ()" style="width:23.5%; font-weight:bold;" class="btn btn-success shadow-none">{{__('adminP.save')}}</button>

                        <div style="width:100%; display:none;" class="alert alert-success text-center mt-1" id="alertDone">
                            {{__('adminP.zipCodeSuccessful')}}
                        </div>
                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-1" id="alertError">
                            {{__('adminP.smthWentWorng')}}
                        </div>
                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-1" id="alertEmpty">
                            {{__('adminP.fillOutForm')}}
                        </div>
                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-1" id="alertPLZfalse">
                            {{__('adminP.zipCodeIncorrect')}}
                        </div>
                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-1" id="saveDelPlzError01">
                            Überprüfen Sie den Zeitraum für die Lieferung, es scheint falsch zu sein!
                        </div>
                    </div>

                    <hr>
                    
                    <div class="d-flex flex-wrap justify-content-start" id="allPLZDel">

                        @if(DeliveryPLZ::where('toRes',Auth::user()->sFor)->get()->count() > 0)
                            @foreach(DeliveryPLZ::where([['toRes',Auth::user()->sFor],['plz','!=','-99']])->get()->sortByDesc('created_at') as $delPLZ)

                                <div style="width: 33%; height:fit-content; margin:3px 0.16% 3px 0.16%; border:1px solid rgb(72,81,87); border-radius:5px;" class="d-flex flex-wrap justify-content-between p-1">
                                    @if($delPLZ->isAct == 1)
                                        <button onclick="chngAcDelPLZ('{{$delPLZ->id}}','0')" style="width:49.5%; font-weight:bold;" class="mb-2 btn btn-success shadow-none">{{__('adminP.active')}}</button>
                                    @else
                                        <button onclick="chngAcDelPLZ('{{$delPLZ->id}}','1')" style="width:49.5%; font-weight:bold;" class="mb-2 btn btn-danger shadow-none">{{__('adminP.notActive')}}</button>
                                    @endif
                                    <button onclick="deleteDelPLZ('{{$delPLZ->id}}')" style="width:49.5%;" class="text-center btn shadow-none">
                                        <i style="color:red;" class="fas fa-2x fa-trash"></i>
                                    </button>

                                    <p style="width:49.5%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87); font-size:1.4rem;" class="text-center">{{$delPLZ->plz}}</p>
                                    <p style="width:49.5%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87); font-size:1.4rem;" class="text-center">
                                    <?php $count=1 ;?>
                                    @foreach(DB::table('plzort')->where('plz', $delPLZ->plz)->get() as $allOr)
                                        @if($count++ == 1)
                                            {{$allOr->ort}}
                                        @else
                                            , {{$allOr->ort}}
                                        @endif
                                    @endforeach
                                    </p>

                                    <hr style="width: 100%; margin: 5px 0 5px 0;">
                                        <p class="text-center" style="width:100%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87); font-size:1.1rem;">
                                            Kosten für die Lieferung
                                        </p>
                                        <?php
                                            $lastPlzCarge = $delPLZ->minimumOrder; 
                                            $allPlzCHF = deliveryPlzCharge::where('plzId',$delPLZ->id)->get();

                                            foreach($allPlzCHF as $plzChfOne){
                                                if($plzChfOne->priceTo > $lastPlzCarge){
                                                    $lastPlzCarge = $plzChfOne->priceTo;
                                                }
                                            }
                                        ?>
                                        <input type="text" style="width:24.5%;" class="form-control shadow-none" value="{{number_format($lastPlzCarge + 0.01, 2, '.', '')}}" id="plzChargeVal1{{$delPLZ->id}}" disabled>
                                        <input type="text" style="width:24.5%;" class="form-control shadow-none" placeholder="Preis zu" id="plzChargeVal2{{$delPLZ->id}}">
                                        <input type="text" style="width:24.5%;" class="form-control shadow-none" placeholder="+CHF" id="plzChargeInCHF{{$delPLZ->id}}">
                            
                                        <button onclick="saveDelPLZCharge('{{$delPLZ->id}}')" style="width:24.5%; font-weight:bold;" class="btn btn-success shadow-none">{{__('adminP.save')}}</button>
                                        
                                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-1" id="plzExtChrgError01{{$delPLZ->id}}">
                                            Füllen Sie zuerst das Formular aus!
                                        </div>
                                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-1" id="plzExtChrgError02{{$delPLZ->id}}">
                                            Der "Preis zu" sollte größer sein als der "Preis von"!
                                        </div>
                                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-1" id="plzExtChrgError03{{$delPLZ->id}}">
                                            Geben Sie für den Gebührenbetrag eine positive Zahl ein!
                                        </div>
                                        <div style="width:100%; display:none;" class="alert alert-danger text-center mt-1" id="plzExtChrgError04{{$delPLZ->id}}">
                                        Wir konnten Ihre Anfrage nicht abschließen!
                                        </div>


                                        <div style="width: 100%;" class="d-flex flex-wrap justify-content-between" id="plzExtCharge{{$delPLZ->id}}">

                                            <p class="text-center" style="width:100%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87);">
                                                Einschreiben Preise für Lieferung
                                            </p>
                                            @foreach ($allPlzCHF as $plzChfOne)
                                                <p style="width:49.5%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87)" ><i class="fa-solid fa-xs fa-circle mr-2"></i> {{number_format($plzChfOne->priceFrom, 2, '.', '')}} -> {{number_format($plzChfOne->priceTo, 2, '.', '')}}</p>
                                                <p style="width:49.5%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87)" >{{number_format($plzChfOne->extraCharge, 2, '.', '')}} CHF</p>
                                            @endforeach
                                            <p style="width:49.5%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87)" ><i class="fa-solid fa-xs fa-circle mr-2"></i> {{number_format($lastPlzCarge, 2, '.', '')}} CHF +</p>
                                            <p style="width:49.5%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87)" >Kostenlose Lieferung</p>

                                        </div>
                                    <hr style="width: 100%; margin: 3px 0 3px 0;">

                                    <p style="width:49.5%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87)" class="text-center">Lieferzeit <br> {{$delPLZ->takesTime}}-{{$delPLZ->takesTimeEnd}} {{__('adminP.min')}}</p>
                                    <p style="width:49.5%; margin-bottom:6px; font-weight:bold; color:rgb(72,81,87)" class="text-center">Mindestbestellwert <br> {{$delPLZ->minimumOrder}} CHF</p>

                                </div>

                            @endforeach
                        @else
                            <p style="width:100%;" class="text-center color-qrorpa"><strong>{{__('adminP.noRecordsYet')}}</strong></p>
                        @endif
                    </div>
                
                </div>

            </div>
        </div>
    </div>

              

    <script>
        function saveDelPLZ(){
            if(!$('#plzVal').val() || !$('#plzTimeVal').val() || !$('#plzTimeVal2').val() || !$('#plzMinOrderCHF').val() ){
                $('#alertEmpty').show(1).delay(3000).hide(1);
            }else if(parseFloat($('#plzTimeVal').val()) > parseFloat($('#plzTimeVal2').val())){
                $('#saveDelPlzError01').show(1).delay(3000).hide(1);
            }else{
                if(($('#plzVal').val()).length != 4){
                    $('#alertPLZfalse').show(1).delay(3000).hide(1);
                }else{
                    $.ajax({
                        url: '{{ route("delivery.addPlzDel") }}',
                        method: 'post',
                        data: {
                            res: $('#theResId').val(),
                            plz: $('#plzVal').val(),
                            time:  $('#plzTimeVal').val(),
                            time2: $('#plzTimeVal2').val(),
                            minOrder:  $('#plzMinOrderCHF').val(),
                            _token: '{{csrf_token()}}'
                        },
                        success: () => {
                            $("#allPLZDel").load(location.href+" #allPLZDel>*","");
                            $('#alertDone').show(1).delay(3000).hide(1);

                            $('#plzVal').val('');
                            $('#plzTimeVal').val('');
                            $('#plzTimeVal2').val('');
                            $('#plzMinOrderCHF').val('');
                        },
                        error: (error) => {
                            console.log(error);
                            $('#alertError').show(1).delay(3000).hide(1);
                        }
                    });
                }
            }
        }   

        function saveDelPLZCharge(delPLZId){
            if( !$('#plzChargeVal1'+delPLZId).val() || !$('#plzChargeVal2'+delPLZId).val() || !$('#plzChargeInCHF'+delPLZId).val() ){
                $('#plzExtChrgError01'+delPLZId).show(1).delay(3000).hide(1);
            }else if(parseFloat($('#plzChargeVal1'+delPLZId).val()) >= parseFloat($('#plzChargeVal2'+delPLZId).val() )){
                $('#plzExtChrgError02'+delPLZId).show(1).delay(3000).hide(1);
                $('#plzChargeVal2'+delPLZId).val("");
            }else if(parseFloat($('#plzChargeInCHF'+delPLZId).val()) < 0){
                $('#plzExtChrgError03'+delPLZId).show(1).delay(3000).hide(1);
                $('#plzChargeInCHF'+delPLZId).val("");
            }else{
                $.ajax({
                    url: '{{ route("delivery.addPlzChargePerPriceRange") }}',
                    method: 'post',
                    data: {
                        plzId: delPLZId,
                        chfVal1: $('#plzChargeVal1'+delPLZId).val(),
                        chfVal2:  $('#plzChargeVal2'+delPLZId).val(),
                        chfCharge:  $('#plzChargeInCHF'+delPLZId).val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: (respo) => {
                        respo = $.trim(respo);
                        if(respo == ''){
                            $('#plzExtChrgError04'+delPLZId).show(1).delay(3000).hide(1);
                        }else{
                            $("#plzExtCharge"+delPLZId).load(location.href+" #plzExtCharge"+delPLZId+">*","");
                            $('#plzChargeVal1'+delPLZId).val(parseFloat(parseFloat(respo)+parseFloat(0.01)).toFixed(2));
                            $('#plzChargeVal2'+delPLZId).val('');
                            $('#plzChargeInCHF'+delPLZId).val('');
                        }
                        // $('#alertDone').show(1).delay(3000).hide(1);
                    },
                    error: (error) => {
                        console.log(error);
                        $('#alertError').show(1).delay(3000).hide(1);
                    }
                });
            }
        } 

        function deleteDelPLZ(plzID){
            $.ajax({
                url: '{{ route("delivery.deletePlzDel") }}',
                method: 'post',
                data: {
                    id: plzID,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#allPLZDel").load(location.href+" #allPLZDel>*","");
                },
                error: (error) => {
                    console.log(error);
                    $('#alertError').show(1).delay(3000).hide(1);
                }
            });
        }

        function chngAcDelPLZ(plzID,newVal){
            $.ajax({
                url: '{{ route("delivery.chngAcPlzDel") }}',
                method: 'post',
                data: {
                    id: plzID,
                    val: newVal,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#allPLZDel").load(location.href+" #allPLZDel>*","");
                },
                error: (error) => {
                    console.log(error);
                    $('#alertError').show(1).delay(3000).hide(1);
                }
            });
        }

        function saveDelPLZTimeAll(resId){
            if($('#allTimePLZ').val() != ''){

                $.ajax({
                    url: '{{ route("delivery.allTimePlzDel") }}',
                    method: 'post',
                    data: {
                        id: resId,
                        val: $('#allTimePLZ').val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        // $("#allPLZDel").load(location.href+" #allPLZDel>*","");
                        $('#saveAllPLZTime').hide(300);
                    },
                    error: (error) => {
                        console.log(error);
                        $('#alertError').show(1).delay(3000).hide(1);
                    }
                });
                
            }else{
                $('#alertEmpty').show(1).delay(3000).hide(1);
            }
        }
        function showSavePlzTimeAll(){
            if($('#saveAllPLZTime').is(":hidden")){
                $('#saveAllPLZTime').show(300);
            }
        }
    </script>
         




















    <!-- The Modal -->
    <div class="modal" id="deliverySchedule" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">{{__('adminP.deliveryWorkingHours')}}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="d-flex justify-content-between flex-wrap">

                <?php $DEschedule = DeliverySchedule::where('toRes', Auth::user()->sFor)->first(); ?>
                @if($DEschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.monday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day11In" value="{{$DEschedule->day11S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day11Out" value="{{$DEschedule->day11E}}">
                    <p  class="text-center" style="width:5%;">{{__(('adminP.and'))}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day21In" value="{{$DEschedule->day21S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day21Out" value="{{$DEschedule->day21E}}">
                @else
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.monday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day11In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day11Out" value="00:00">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day21In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day21Out" value="00:00">
                @endif

                @if($DEschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.tuesday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day12In" value="{{$DEschedule->day12S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day12Out" value="{{$DEschedule->day12E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day22In" value="{{$DEschedule->day22S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day22Out" value="{{$DEschedule->day22E}}">
                @else
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.tuesday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day12In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day12Out" value="00:00">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day22In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day22Out" value="00:00">
                @endif

                @if($DEschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.wednesday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day13In" value="{{$DEschedule->day13S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day13Out" value="{{$DEschedule->day13E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day23In" value="{{$DEschedule->day23S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day23Out" value="{{$DEschedule->day23E}}">
                @else
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.wednesday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day13In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day13Out" value="00:00">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day23In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day23Out" value="00:00">
                @endif

                @if($DEschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.thursday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day14In" value="{{$DEschedule->day14S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day14Out" value="{{$DEschedule->day14E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day24In" value="{{$DEschedule->day24S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day24Out" value="{{$DEschedule->day24E}}">
                @else
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.thursday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day14In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day14Out" value="00:00">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day24In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day24Out" value="00:00">
                @endif

                @if($DEschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.friday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day15In" value="{{$DEschedule->day15S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day15Out" value="{{$DEschedule->day15E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day25In" value="{{$DEschedule->day25S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day25Out" value="{{$DEschedule->day25E}}">
                @else
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.friday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day15In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day15Out" value="00:00">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day25In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day25Out" value="00:00">
                @endif

                @if($DEschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.saturday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day16In" value="{{$DEschedule->day16S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day16Out" value="{{$DEschedule->day16E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day26In" value="{{$DEschedule->day26S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day26Out" value="{{$DEschedule->day26E}}">
                @else
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.saturday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day16In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day16Out" value="00:00">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day26In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day26Out" value="00:00">
                @endif

                @if($DEschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.sunday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day10In" value="{{$DEschedule->day10S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day10Out" value="{{$DEschedule->day10E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day20In" value="{{$DEschedule->day20S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day20Out" value="{{$DEschedule->day20E}}">
                @else
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.sunday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day10In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day10Out" value="00:00">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day20In" value="00:00">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day20Out" value="00:00">
                @endif
            </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer d-flex justify-content-around">
            <button style="width:45%" type="button" class="btn btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
            <button onclick="saveDeliverySchedule('{{Auth::user()->sFor}}')" style="width:45%" type="button" class="btn btn-outline-success" data-dismiss="modal">{{__('adminP.saveOnComputer')}}</button>
        </div>

        </div>
    </div>
    </div>


    <script>
        function saveDeliverySchedule(ResId){
            $.ajax({
				url: '{{ route("delivery.scheduleSet") }}',
				method: 'post',
				data: {
                    res: ResId,
                    day11In : $('#day11In').val(),
                    day11Out : $('#day11Out').val(),
                    day21In : $('#day21In').val(),
                    day21Out : $('#day21Out').val(),
                    day12In : $('#day12In').val(),
                    day12Out : $('#day12Out').val(),
                    day22In : $('#day22In').val(),
                    day22Out : $('#day22Out').val(),
                    day13In : $('#day13In').val(),
                    day13Out : $('#day13Out').val(),
                    day23In : $('#day23In').val(),
                    day23Out : $('#day23Out').val(),
                    day14In : $('#day14In').val(),
                    day14Out : $('#day14Out').val(),
                    day24In : $('#day24In').val(),
                    day24Out : $('#day24Out').val(),
                    day15In : $('#day15In').val(),
                    day15Out : $('#day15Out').val(),
                    day25In : $('#day25In').val(),
                    day25Out : $('#day25Out').val(),
                    day16In : $('#day16In').val(),
                    day16Out : $('#day16Out').val(),
                    day26In : $('#day26In').val(),
                    day26Out : $('#day26Out').val(),
                    day10In : $('#day10In').val(),
                    day10Out : $('#day10Out').val(),
                    day20In : $('#day20In').val(),
                    day20Out : $('#day20Out').val(),
					_token: '{{csrf_token()}}'
				},
				success: () => {
                    location.reload();
				},
				error: (error) => {
					console.log(error);
					alert($('#oopsSomethingWrong').val())
				}
		    });
        }
    </script>












































    <div class="d-flex justify-content-between mt-3" id="deliveryProdsElement">



        <div style="width:49.5%;" id="leftSideTA">
            <div class="d-flex mb-1">
                <h4 style="color:rgb(39,195,175); width:70%" class="p-2 mb-2">{{__('adminP.productsForDelivery')}} ( {{ DeliveryProd::where('toRes', Auth::user()->sFor)->get()->count()}} X )</h4>
                <button onclick="removeAllDelivery('{{Auth::user()->sFor}}')"  style="width:30%;" class="btn btn-block btn-outline-danger">{{__('adminP.removeAll')}}</button>
            </div>
       

            <button class="btn btn-block mb-2" style="border:1px solid lightgray; padding:15px;" data-toggle="modal" data-target="#addDeProdDe">
                {{__('adminP.addNewTakeawayProduct')}}
            </button>



            @foreach(kategori::where('toRes',  Auth::user()->sFor)->get()->sortBy('positionDelivery') as $kat)
                    
                @if(DeliveryProd::where('toRes', Auth::user()->sFor)->where('kategoria', $kat->id)->get()->count() > 0)

                    <div style="cursor: pointer; position:relative; object-fit: cover;" class="col12 p-1 text-center">
                        <div style="width:15%; display:inline;" data-toggle="modal" data-target="#changeDeliveryCatPosition{{$kat->id}}">
                            <span class="text-center pr-3" style="font-size: 22px; color:rgb(72,81,87);"><strong>{{$kat->positionDelivery}} #</strong></span>
                        </div>
                        <img onclick="showProKatLeft('{{$kat->id}}')" style="border-radius:30px; width:85%; height:120px;" src="storage/kategoriaUpload/{{$kat->foto}}"
                            alt="">
            
                        @if(strlen($kat->emri) > 20)
                            <div class="teksti d-flex" style="font-size:20px;  margin-bottom:13px;">          
                                <strong style="margin-left:15%;" onclick="showProKatLeft('{{$kat->id}}')">{{$kat->emri}} </strong>
                                <!-- <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> -->
                                <p class="pr-5">{{$kat->visits}} <i class="far fa-eye"></i></p>
                            </div>
                        @else
                            <div class="teksti d-flex" >          
                                <strong style="margin-left:15%;" onclick="showProKatLeft('{{$kat->id}}')">{{$kat->emri}} </strong>
                                <!-- <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> -->
                                <p class="pr-5">{{$kat->visits}} <i class="far fa-eye"></i></p>
                            </div>
                        @endif
                        <input type="hidden" value="0" id="state{{$kat->id}}">
                    </div>

                    <ul id="prodsOfTALeft{{$kat->id}}" class="ProdLists" style="display:none;">
                        @foreach(DeliveryProd::where('toRes', Auth::user()->sFor)->where('kategoria', $kat->id)->get()->sortBy('position') as $delvAProd)
                            <li id="hideThisTAElDel{{$delvAProd->id}}"> 
                                <div class="d-flex justify-content-between">
                                    <div style="width:10%;" data-toggle="modal" data-target="#changeDeliveryProdPosition{{$delvAProd->id}}">
                                        <span class="text-center pr-3" style="font-size: 22px; color:rgb(72,81,87);"><strong>{{$delvAProd->position}} #</strong></span>
                                    </div>
                                    @if($delvAProd ->recomendet == 0)
                                    <button style="width:7%;" onclick="preRemoveDeProd('0', '{{$delvAProd->id}}','{{$kat->id}}')" class="btn btn-outline-danger"
                                        data-toggle="modal" data-target="#removeDeProdConfirmation">X</button> 
                                    @else
                                    <button style="width:7%;" onclick="preRemoveDeProd('1', '{{$delvAProd->id}}','{{$kat->id}}')" class="btn btn-outline-danger"
                                        data-toggle="modal" data-target="#removeDeProdConfirmation">X</button> 
                                    @endif
                                    <span style="width:74%;" class="ml-3"> 
                                        
	                                    <span>
                                            {{number_format($delvAProd->qmimi, 2, '.', ' ')}}
                                            @if($delvAProd->qmimi2 != 999999)
                                                /{{number_format($delvAProd->qmimi2, 2, '.', ' ')}}
                                            @endif
                                            <sub>{{__('adminP.currencyShow')}}</sub>
                                        </span>
										<?php
                                            if($delvAProd->restrictPro != 0){
                                                $ageRest = $delvAProd->restrictPro;
                                            }else{
                                                $prRest = Produktet::find($delvAProd->prod_id);
                                                if($prRest != NULL){
                                                    $ageRest = $prRest->restrictPro;
                                                }else{
                                                    $ageRest = 0;
                                                }
                                            }
                                        ?> 
                                        <span class="ml-3">
                                            @if ($ageRest != 0)
                                                <img src="storage/icons/{{$ageRest}}+.png" style="width:25px; height:auto;" alt="">
                                            @endif
                                            {{$delvAProd->emri}}
                                        </span> 
										
                                        <span style="opacity:0.6;">( {{kategori::find($delvAProd->kategoria)->emri}} )
                                            @if($delvAProd->recomendet == 1)
                                                <span class="ml-3" style="color:red;">{ {{__('adminP.recommended')}} }</span>
                                            @endif
                                        </span>
                                        <br>
                                    </span>
                                    <button style="width:7%;" data-toggle="modal" data-target="#editDeProd{{$delvAProd->id}}"
                                    class="btn btn-block btn-outline-default"><i class="fas fa-pen"></i></button> 
                                </div> 

                            </li>
                                <!-- Change Delivery Category Position Modal -->
                                <div class="modal fade" id="changeDeliveryProdPosition{{$delvAProd->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                    data-backdrop="false"style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body d-flex flex-wrap justify-content-between">
                                                <h3 class="pt-3" style="width: 85%;"><strong>{{$delvAProd->emri}}</strong></h3>
                                                <button style="width: 15%; font-size:23px;" type="button" class="btn btn-default text-right" data-dismiss="modal"><strong>X</strong></button>

                                                <hr style="width: 100%;">
                                                <p style="width:49%; font-size:18px;" class="text-right pt-2"><strong>{{__('adminP.position')}}: </strong></p>        
                                                <input class="form-control text-center mb-2" type="number" style="width:49%; border:none; border-bottom: 1px solid black; font-size:22px; font-weight:bold;" 
                                                    value="{{$delvAProd->position}}" id="changeDeliveryProdPositionVal{{$delvAProd->id}}">
                                                
                                                <button style="width:49%" class="btn btn-danger" data-dismiss="modal">{{__('adminP.abort')}}</button>
                                                <button style="width:49%" class="btn btn-success" onclick="changeDeliveryProdPositionSave('{{$delvAProd->id}}','{{$kat->id}}')">{{__('adminP.saveOnComputer')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </ul>
                    
                    <!-- Change Delivery Category Position Modal -->
                    <div class="modal fade" id="changeDeliveryCatPosition{{$kat->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                        data-backdrop="false"style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body d-flex flex-wrap justify-content-between">
                                    <h3 class="pt-3" style="width: 85%;"><strong>{{$kat->emri}}</strong></h3>
                                    <button style="width: 15%; font-size:23px;" type="button" class="btn btn-default text-right" data-dismiss="modal"><strong>X</strong></button>

                                    <hr style="width: 100%;">
                                    <p style="width:49%; font-size:18px;" class="text-right pt-2"><strong>{{__('adminP.position')}}: </strong></p>        
                                    <input class="form-control text-center mb-2" type="number" style="width:49%; border:none; border-bottom: 1px solid black; font-size:22px; font-weight:bold;" 
                                        value="{{$kat->positionDelivery}}" id="changeDeliveryCatPositionVal{{$kat->id}}">
                                    
                                    <button style="width:49%" class="btn btn-danger" data-dismiss="modal">{{__('adminP.abort')}}</button>
                                    <button style="width:49%" class="btn btn-success" onclick="changeDeliveryCatPositionSave('{{$kat->id}}')">{{__('adminP.saveOnComputer')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>



        <input type="hidden" id="removeDeProdConfirmationRec">
        <input type="hidden" id="removeDeProdConfirmationId">
        <input type="hidden" id="removeDeProdConfirmationCat">
        <!-- Remove the Takeaway product from the List Confirmation Modal -->
        <div class="modal fade" id="removeDeProdConfirmation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            data-backdrop="false"style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body d-flex flex-wrap justify-content-between">

                        <h3 class="pt-3" style="width: 85%;"><strong>{{__('adminP.deleteProduct')}}? </strong></h3>
                        <button style="width: 15%; font-size:23px;" type="button" class="btn btn-default text-right" data-dismiss="modal"><strong>X</strong></button>
                        <hr style="width: 100%;">

                        <button style="width:49%" class="btn btn-danger" data-dismiss="modal">{{__('adminP.no')}}</button>
                        <button style="width:49%" class="btn btn-success" data-dismiss="modal" onclick="removeDeProd()">{{__('adminP.yes')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function changeDeliveryCatPositionSave(catId){
                $.ajax({
                    url: '{{ route("delivery.changeCategoryOrder") }}',
                    method: 'post',
                    data: {
                        catId: catId,
                        newPoz: $('#changeDeliveryCatPositionVal'+catId).val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        $('#changeDeliveryCatPosition'+catId).modal('toggle');
                        $("#leftSideTA").load(location.href+" #leftSideTA>*","");
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }

            function changeDeliveryProdPositionSave(prodId,catId){
                $.ajax({
                    url: '{{ route("delivery.changeProductOrder") }}',
                    method: 'post',
                    data: {
                        prodId: prodId,
                        newPoz: $('#changeDeliveryProdPositionVal'+prodId).val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        $('#changeDeliveryProdPosition'+prodId).modal('toggle');
                        $("#prodsOfTALeft"+catId).load(location.href+" #prodsOfTALeft"+catId+">*","");
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }



            function preRemoveDeProd(rec,TaId,catId){
                $('#removeDeProdConfirmationRec').val(rec);
                $('#removeDeProdConfirmationId').val(TaId);
                $('#removeDeProdConfirmationCat').val(catId);
            }

            function removeDeProd(){
                var rec = $('#removeDeProdConfirmationRec').val();
                var TaId = $('#removeDeProdConfirmationId').val();
                var catId = $('#removeDeProdConfirmationCat').val();
                
                $.ajax({
                    url: '{{ route("delivery.destroy") }}',
                    method: 'post',
                    data: {id: TaId, _token: '{{csrf_token()}}'},
                    success: (res) => {
                        res = res.replace(/\s/g, '');
                        if(rec == 1){
                            location.reload();
                        }else{
                            $('#hideThisTAElDel'+TaId).hide();
                            $("#prodsOfTA"+catId).load(location.href+" #prodsOfTA"+catId+">*","");
                            if(res == 'no'){
                                $("#leftSideTA").load(location.href+" #leftSideTA>*","");
                            }else{
                                $("#prodsOfTALeft"+catId).load(location.href+" #prodsOfTALeft"+catId+">*","");
                            }
                        }
                    },
                    error: (error) => {console.log(error);
                        alert($('#oopsSomethingWrong').val());
                    }
                });
            }

        </script>
     




































        <!-- Border -->
        <div style="width:0.2%; border:2px solid gray;"></div>  
        






























        <div style="width:49.5%;">
            <div class="d-flex mb-1">
                <h4 style="color:rgb(39,195,175); width:70%" class="p-2">{{__('adminP.allproducts')}} ( {{Produktet::where('toRes', Auth::user()->sFor)->get()->count()}} X )</h4>
                <button onclick="addAllDelivery('{{Auth::user()->sFor}}')" style="width:30%;" class="btn btn-block btn-outline-primary">{{__('adminP.addAll')}}</button>
            </div>

            <div id="searchProdsULtakeaway" class="ProdLists">
                
                @foreach(kategori::where('toRes',  Auth::user()->sFor)->get()->sortByDesc('visits') as $kat)
                    
                

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
            
                        <ul class="ProdLists" id="prodsOfTA{{$kat->id}}" style="display:none;">
                            @foreach(Produktet::where('toRes', Auth::user()->sFor)->where('kategoria', $kat->id)->get() as $allPro)
                                @if(DeliveryProd::where('prod_id', $allPro->id)->first() != null)
                                    <li style="background-color:rgb(39,195,175); color:white;">
                                        <button style="font-size:19px; color:white;" class="btn btn-outline-default">✓</button> 
                                        <span class="ml-3"> 
                                            <span>
                                                {{number_format($allPro->qmimi, 2, '.', ' ')}}
                                                @if($allPro->qmimi2 != 999999)
                                                    /{{number_format($allPro->qmimi2, 2, '.', ' ')}}
                                                @endif
                                                <sub>{{__('adminP.currencyShow')}}</sub>
                                            </span>     
                                            <?php
                                                $ageRest = $allPro->restrictPro;
                                            ?> 
                                            <span class="ml-3">
                                                @if ($ageRest != 0)
                                                    <img src="storage/icons/{{$ageRest}}+.png" style="width:25px; height:auto;" alt="">
                                                @endif
                                                {{$allPro->emri}}
                                            </span>  
                                            <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span>
                                        </span>
                                    </li>
                                @else
                                    <li id="ProdLiRight02{{$allPro->id}}">
                                        <button onclick="addDePro('{{$allPro->id}}','{{$kat->id}}')" style="font-size:21px;" class="btn btn-outline-danger">+</button> 
                                            <span class="ml-3"> 
                                                <span>
                                                    {{number_format($allPro->qmimi, 2, '.', ' ')}}
                                                    @if($allPro->qmimi2 != 999999)
                                                        /{{number_format($allPro->qmimi2, 2, '.', ' ')}}
                                                    @endif
                                                    <sub>{{__('adminP.currencyShow')}}</sub>
                                                </span>     
                                                <?php
                                                    $ageRest = $allPro->restrictPro;
                                                ?> 
                                                <span class="ml-3">
                                                    @if ($ageRest != 0)
                                                        <img src="storage/icons/{{$ageRest}}+.png" style="width:25px; height:auto;" alt="">
                                                    @endif
                                                    {{$allPro->emri}}
                                                </span> 
                                                <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span>
                                            </span>
                                    </li>
                                    <li id="ProdLiRight01{{$allPro->id}}" style="background-color:rgb(39,195,175); color:white; display:none;">
                                        <button style="font-size:19px; color:white;" class="btn btn-outline-default">✓</button> 
                                        <span class="ml-3"> 
                                            <span>
                                                {{number_format($allPro->qmimi, 2, '.', ' ')}}
                                                @if($allPro->qmimi2 != 999999)
                                                    /{{number_format($allPro->qmimi2, 2, '.', ' ')}}
                                                @endif
                                                <sub>{{__('adminP.currencyShow')}}</sub>
                                            </span>     
                                            <?php
                                                $ageRest = $allPro->restrictPro;
                                            ?> 
                                            <span class="ml-3">
                                                @if ($ageRest != 0)
                                                    <img src="storage/icons/{{$ageRest}}+.png" style="width:25px; height:auto;" alt="">
                                                @endif
                                                {{$allPro->emri}}
                                            </span>  
                                            <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span>
                                        </span>
                                    </li>
                                @endif
                                    
                            @endforeach
                        </ul>
            
            
                    @endforeach
            </div>

        </div>

    </div>















    <script>
  
                function showProKat(kId){
                    // $('.ProdLists').hide();
                    if($('#prodsOfTA'+kId).is(":visible")){
                        $('#prodsOfTA'+kId).hide();
                    }else{
                        $('#prodsOfTA'+kId).show();
                    }
                }
                
                function showProKatLeft(kId){
                    // $('.ProdLists').hide();
                    if($('#prodsOfTALeft'+kId).is(":visible")){
                        $('#prodsOfTALeft'+kId).hide();
                    }else{
                        $('#prodsOfTALeft'+kId).show();
                    }
                }



        function addDePro(ProId,catId){
            $.ajax({
                url: '{{ route("delivery.addOne") }}',
                method: 'post',
                data: {
                    id: ProId,
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                    res = res.replace(/\s/g, '');
                    if(res == 'yes'){
                        $("#leftSideTA").load(location.href+" #leftSideTA>*","");
                    }else{
                        $("#prodsOfTALeft"+catId).load(location.href+" #prodsOfTALeft"+catId+">*","");
                    }
                    $("#editModalsTA").load(location.href+" #editModalsTA>*","");
                    $("#ProdLiRight02"+ProId).hide();
                    $("#ProdLiRight01"+ProId).show();
                },
                error: (error) => {
                    console.log(error);
                    alert($('#smthWrongAddOneTakeawayProd').val())
                }
            });
        }




      

        function addAllDelivery(ResId){
            // console.log(ResId);
            $.ajax({
                url: '{{ route("delivery.addAll") }}',
                method: 'post',
                data: {id: ResId, _token: '{{csrf_token()}}'},
                success: () => {
                    $("#deliveryProdsElement").load(location.href+" #deliveryProdsElement>*","");
                    $("#recProdList").load(location.href+" #recProdList>*","");
                },
                error: (error) => {console.log(error);
                    alert($('#oopsSomethingWrong').val());
                }
            });
        }
        function removeAllDelivery(ResId){
            // console.log(ResId);
            $.ajax({
                url: '{{ route("delivery.destroyAll") }}',
                method: 'post',
                data: {id: ResId, _token: '{{csrf_token()}}'},
                success: () => {
                    $("#deliveryProdsElement").load(location.href+" #deliveryProdsElement>*","");
                    $("#recProdList").load(location.href+" #recProdList>*","");
                },
                error: (error) => {console.log(error);
                    alert($('#oopsSomethingWrong').val());
                }
            });
        }

        function editDEProduct(TAid){
            $.ajax({
                url: '{{ route("delivery.edit") }}',
                method: 'post',
                data: {
                    id: TAid,
                    emri: $('#name'+TAid).val(),
                    pershkrimi: $('#pershkrimi'+TAid).val(),
                    qmimi: $('#qmimi'+TAid).val(),
                    qmimi2: $('#qmimi2'+TAid).val(),
                    mwst: $('#dePMwst'+TAid).val(),
                    acsCl: $('#accessByClientsVal'+TAid).val(),
                    _token: '{{csrf_token()}}'},
                success: () => {$("#deliveryProdsElement").load(location.href+" #deliveryProdsElement>*","");},
                error: (error) => {console.log(error);
                    alert($('#smthWrongRemoveOneTakeawayProd').val());
                }
            });
        }

      

        

        
    </script>







































<script>
     $(document).ready(function(){
        $('.ProductAll').hide();
    });//End of document ready 

    function openResProd(Id){
        $('#ProductStart').hide('slow');
        $('#ProductOne'+Id).show('slow');
    }

    function backPro(){
        $('.ProductAll').hide('slow');
        $('#ProductStart').show('slow');
    }




    function showExtrasTA(value,resId){
        if(value == 0){
            // alert(last);
            if(last != ''){
                $('#'+last).hide();
                $('#extPro'+resId).val('');
                $('#type'+resId).val('');
            }
        }else{
            // alert(last);
            if(last != ''){
                $('#'+last).hide();
                $('#extPro'+resId).val('');
                $('#type'+resId).val('');
            }
            var show = 'BoxKatTA'+value+'R'+resId;
            last = show;
            $('#'+show).show(200);
        }
    }










    function addThisTA(theId,name,price,resId,extId){
        var checkBox = document.getElementById(theId);
        var extPro = document.getElementById('extPro'+resId);
        var extProValue = document.getElementById('extPro'+resId).value;

        if(extProValue == ""){
            var add = extId+'||'+name+'||'+price;
            if(checkBox.checked == true){
                extPro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
            var add = extId+'||'+name+'||'+price;
            if(checkBox.checked == true){
                extPro.value =extProValue+'--0--'+add;
            }else{
               var newVal = extProValue.replace(add,'');
               extPro.value = newVal;
            }
        }  
    }




    
    function addThis2TA(theId,name,value,resId,llId){
        var checkBox = document.getElementById(theId);
        var typePro = document.getElementById('typePro'+resId);
        var typeProValue = document.getElementById('typePro'+resId).value;

        // alert(typeProValue);

        if(typeProValue == ""){
            var add = llId+'||'+name+'||'+value;
            if(checkBox.checked == true){
                typePro.value = add;
            }else{
                // alert('Remove '+theId);
            }
        }else{
            var add =llId+'||'+name+'||'+value;
            if(checkBox.checked == true){
                typePro.value =typeProValue+'--0--'+add;
            }else{
               var newVal = typeProValue.replace(add,'');
               typePro.value = newVal;
            }
        }
    }







</script>
</section>