<?php

use App\DeliveryPLZ;
use App\deliveryPlzCharge;
   	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Delivery']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
       header("Location: ".route('dash.notAccessToPage')); 
       exit();
    }
    use App\ekstra;
    use App\Produktet;
    use App\Takeaway;
    use App\kategori;
    use App\LlojetPro;
    use App\Restorant;

    use App\RecomendetProd;
    use Carbon\Carbon;

    use App\DeliveryProd;
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
    button:focus {outline:none;}



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



<script>
    var lastTel = '';
</script>





<section class="pl-2 pr-2 pb-5">









        <div class="swiper-container p-0 pb-1" style="border-top-left-radius:50px; border-bottom-left-radius:50px; overflow: hidden; " id="taRecomendetProTel">
            <div class="swiper-wrapper" id="recProdListTel">
                @foreach(DeliveryProd::where([['toRes', '=', Auth::user()->sFor],['recomendet','=', '1']])->get()->sortBy('recomendetNr') as $RePro)
                <div class="swiper-slide recProElement" data-backdrop="static" data-keyboard="false">
                    <img style="width:120px; height:120px; border-radius:50%;" src="storage/DeliveryRecomendet/{{$RePro->recomendetPic}}"
                        alt="image">
                    <p style=" width:100%; font-size:13px;"><strong>
                        {{$RePro->recomendetNr}}# |{{$RePro->emri}}
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
                            <button class="btn btn-outline-dark" onclick="minusOneTARecTel('{{$RePro->id}}')" style="width:33%;"> < </button>
                            @else
                            <button class="btn btn-default" style="width:33%;"></button>
                            @endif

                            <button class="btn btn-outline-dark" style="width:33%;" onclick="taRemoceRecTel('{{$RePro->id}}')">x</button>

                            @if($RePro->recomendetNr < Takeaway::where([['toRes', '=', Auth::user()->sFor],['recomendet','=', '1']])->get()->max('recomendetNr'))
                            <button class="btn btn-outline-dark" onclick="plusOneTARecTel('{{$RePro->id}}')" style="width:33%;" > > </button>
                            @else
                            <button class="btn btn-default" style="width:33%;"></button>
                            @endif
                    </p>
                </div>

                @endforeach
            </div>
        </div>

        <script>
        if(screen.width >= 580){
            $('#taRecomendetProTel').hide();
        }
          
        </script>

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
        function minusOneTARecTel(RecTAId){
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
        function plusOneTARecTel(RecTAId){
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
    </script>






                        @foreach(DeliveryProd::where('toRes', Auth::user()->sFor)->get() as $delAProd)

                        <!-- Edit Takeaway Modal -->
                        <div class="modal" id="editTAProdTel{{$delAProd->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                         style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                             
                                    <h4 class="modal-title">{{$delAProd->emri}} <span style="opacity:0.6;">( {{kategori::find($delAProd->kategoria)->emri}} )</span></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">

                                    {{Form::open(['action' => 'DeliveryController@addToRec', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                                        <div class="d-flex justify-content-between">
                                        
                                            <div style="width:68%;" class="custom-file">
                                                {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                                                {{ Form::file('foto', ['class' => 'custom-file-input', 'id' => 'recTaFoto' , 'required']) }}
                                            </div>
                                            <input type="hidden" value="{{$delAProd->id}}" name="id">
                                            <button style="width:30%;" type="submit" class="btn btn-block btn-outline-primary">{{__('adminP.recommended')}}</button>
                                            
                                        </div>
                                    {{Form::close() }}
                                    <hr>

                                    <div class="form-group">
                                        <label for="name">{{__('adminP.name')}}:</label>
                                        <input type="text" class="form-control shadow-none" id="nameTel{{$delAProd->id}}" value="{{$delAProd->emri}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="pershkrimi">{{__('adminP.description')}}:</label>
                                        <input type="text" class="form-control shadow-none" id="pershkrimiTel{{$delAProd->id}}" value="{{$delAProd->pershkrimi}}">
                                    </div>

                                    <div class="mt-1 mb-2" >
                                        <div class="form-check">
                                            @if($delAProd->accessableByClients == 1)
                                            <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$delAProd->id}}')" id="accessByClients{{$delAProd->id}}" checked>
                                            <label class="form-check-label pl-2" for="accessByClients{{$delAProd->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                            <input type="hidden" id="accessByClientsVal{{$delAProd->id}}" name="accessByClientsVal" value="1">
                                            @else
                                            <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$delAProd->id}}')" id="accessByClients{{$delAProd->id}}">
                                            <label class="form-check-label pl-2" for="accessByClients{{$delAProd->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                            <input type="hidden" id="accessByClientsVal{{$delAProd->id}}" name="accessByClientsVal" value="0">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group d-flex justify-content-between">
                                        <div style="width:45%;">
                                            <label for="qmimi">{{__('adminP.price')}}:</label>
                                            <input type="text" class="form-control shadow-none" id="qmimiTel{{$delAProd->id}}" value="{{$delAProd->qmimi}}">
                                        </div>
                                        <div style="width:45%;">
                                            <label for="qmimi2">{{__('adminP.price')}} (+20:00):</label>
                                            @if($delAProd->qmimi2 != 999999)
                                                <input type="number" class="form-control shadow-none" id="qmimi2Tel{{$delAProd->id}}" value="{{$delAProd->qmimi2}}">
                                            @else
                                                <input type="number" class="form-control shadow-none" id="qmimi2Tel{{$delAProd->id}}">
                                            @endif
                                        </div>
                                    </div>

                                    <input type="hidden" value="{{$delAProd->mwstForPro}}" id="dePMwst{{$delAProd->id}}">

                                    <div class="d-flex justify-content-between">
                                        @if ($delAProd->mwstForPro == 2.60)
                                            <button id="setMwst{{$delAProd->id}}Btn1" onclick="setNewMwst('2.60','{{$delAProd->id}}')" style="width:49%;" class="btn btn-dark"><strong>MwST: 2.60 %</strong></button>
                                            <button id="setMwst{{$delAProd->id}}Btn2" onclick="setNewMwst('8.10','{{$delAProd->id}}')" style="width:49%;" class="btn btn-outline-dark"><strong>MwST: 8.10 %</strong></button>
                                        @elseif ($delAProd->mwstForPro == 8.10)
                                            <button id="setMwst{{$delAProd->id}}Btn1" onclick="setNewMwst('2.60','{{$delAProd->id}}')" style="width:49%;" class="btn btn-outline-dark"><strong>MwST: 2.60 %</strong></button>
                                            <button id="setMwst{{$delAProd->id}}Btn2" onclick="setNewMwst('8.10','{{$delAProd->id}}')" style="width:49%;" class="btn btn-dark"><strong>MwST: 8.10 %</strong></button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer d-flex justify-content-between">
                                    <button style="width:45%;" type="button" class="btn btn-block btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                                    <button onclick="editDEProductTel('{{$delAProd->id}}')" style="width:45%;" type="button" class="btn btn-block btn-outline-success" data-dismiss="modal">{{__('adminP.save')}}</button>
                                </div>

                                </div>
                            </div>
                        </div>
                @endforeach




                <script>
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

































                       <!-- Add Takeaway Modal -->
                       <div class="modal" id="addTAProdTATel" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">{{__('adminP.addDeliveryProduct')}}</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                {{Form::open(['action' => 'DeliveryController@store', 'method' => 'post' ]) }}
                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <div class="form-group">
                                            {{ Form::text('emri','', ['class' => 'form-control shadow-none' , 'placeholder'=>__("adminP.name") , 'required']) }}
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    {{ Form::textarea('pershkrimi','', ['class' => 'form-control shadow-none' , 'placeholder'=>__("adminP.description") , 'rows'=>'2' , 'required']) }}
                                                </div>
                                            </div> 
                                        </div>

                                        <div class="row mt-1 mb-2" >
                                            <div class="col-12 form-check" style="margin-left: 15px;">
                                                <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChngInd()" id="accessByClientsInd" checked>
                                                <label class="form-check-label pl-2" for="accessByClientsInd"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                                <input type="hidden" id="accessByClientsValInd" name="accessByClientsValInd" value="1">
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    {{ Form::label(__('adminP.price'), null , ['class' => 'control-label']) }}
                                                    {{ Form::number('qmimi','', ['class' => 'form-control shadow-none', 'step'=>'0.01' , 'min'=>'0' , 'required']) }}
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    {{ Form::label(__("adminP.price"). '20:01+ (' .__("adminP.optional"). ')', null , ['class' => 'control-label']) }}
                                                    {{ Form::number('qmimi2','', ['class' => 'form-control shadow-none', 'step'=>'0.01' , 'min'=>'0']) }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <!-- <label for="kat">Kategorie</label> -->
                                                    <select name="kategoria" class="form-control shadow-none" 
                                                    onchange="showExtrasTATel(this.value, '{{Auth::user()->sFor}}')">
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
                                                <div id="BoxKatTATel{{$kats->id}}R{{Auth::user()->sFor}}" style="display:none;">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <ul class="list-group list-group-flush">
                                                                @foreach(ekstra::where('toCat', '=', $kats->id)->get() as $ekstras)
                                                                    <li class="list-group-item">
                                                                        {{$ekstras->emri}} 
                                                                        <label class="switch" style="width:22px !important; height:20px !important; margin:0px;">
                                                                        <input type="checkbox" class="success" id="extP{{$ekstras->id}}" 
                                                                                onchange="addThisTATel(this.id,'{{$ekstras->emri}}','{{$ekstras->qmimi}}','{{Auth::user()->sFor}}','{{$ekstras->id}}')">
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
                                                                        <label class="switch" style="width:22px !important; height:20px !important; margin:0px;">
                                                                        <input type="checkbox" class="success" id="LlProTel{{$proLl->id}}" 
                                                                            onchange="addThis2TATel(this.id,'{{$proLl->emri}}','{{$proLl->vlera}}','{{Auth::user()->sFor}}','{{$proLl->id}}')">
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
                                        {{ Form::hidden('extPro', '' , ['id' => 'extProTel'.Auth::user()->sFor]) }}
                                        {{ Form::hidden('typePro', '' , ['id' => 'typeProTel'.Auth::user()->sFor]) }}
                                        {{ Form::hidden('restaurant', Auth::user()->sFor , ['id' => 'restaurant']) }}
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        {{ Form::submit(__('adminP.save'), ['class' => 'form-control btn btn-success']) }}
                                    </div>
                                {{Form::close() }}
                                </div>
                            </div>
                        </div>

























    <div class="d-flex justify-content-between mt-3" id="takeawayProdsElementTel">
        

        <script>
              // Add the following code if you want the name of the file appear on select
                $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                });
        </script>











    <!--  Delivery PLZ Modal -->
    <div class="modal" id="deliveryPLZmodal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:1%;">
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
                        <div style="width:100%; display:none;" class="alert alert-success text-center" id="alertDone">
                            {{__('adminP.zipCodeSuccessful')}}
                        </div>
                        <div style="width:100%; display:none;" class="alert alert-danger text-center" id="alertError">
                            {{__('adminP.smthWentWorng')}}
                        </div>
                        <div style="width:100%; display:none;" class="alert alert-danger text-center" id="alertEmpty">
                            {{__('adminP.fillOutForm')}}
                        </div>
                        <div style="width:100%; display:none;" class="alert alert-danger text-center" id="alertPLZfalse">
                            {{__('adminP.zipCodeIncorrect')}}
                        </div>
                        <div style="width:100%; display:none;" class="alert alert-danger text-center" id="saveDelPlzError01">
                            Überprüfen Sie den Zeitraum für die Lieferung, es scheint falsch zu sein!
                        </div>
                        
                        <!-- PLZ MIN ALL -->

                        <input type="text" style="width:49.5%;" class="form-control shadow-none" placeholder="{{__('adminP.postCode')}}" id="plzVal">
                        <input type="text" style="width:49.5%;" class="form-control shadow-none" placeholder="Mindestbestellwert in CHF" id="plzMinOrderCHF">
                        <input type="text" style="width:49.5%;" class="mt-1 form-control shadow-none" placeholder="zeit 1" id="plzTimeVal">
                        <input type="text" style="width:49.5%;" class="mt-1 form-control shadow-none" placeholder="zeit 2" id="plzTimeVal2">

                        <input type="hidden" value="{{Auth::user()->sFor}}" id="theResId">
                        <button onclick="saveDelPLZ()" style="width:100%; font-weight:bold;" class="mt-2 btn btn-success shadow-none">{{__('adminP.save')}}</button>
                    </div>

                    <hr>
                    
                    <div class="d-flex flex-wrap justify-content-between pr-2 pl-2" id="allPLZDel">

                    @if(DeliveryPLZ::where('toRes',Auth::user()->sFor)->get()->count() > 0)
                            @foreach(DeliveryPLZ::where([['toRes',Auth::user()->sFor],['plz','!=','-99']])->get()->sortByDesc('created_at') as $delPLZ)

                                <div style="width: 100%; height:fit-content; margin:3px 0 3px 0; border:1px solid rgb(72,81,87); border-radius:5px;" class="d-flex flex-wrap justify-content-between p-1">
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
                            time2:  $('#plzTimeVal2').val(),
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
                        error: (error) => { console.log(error); }
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







        <div style="width:100%;">
            <div class="d-flex flex-wrap justify-content-between mb-1">

                <button style="width:49%;" class="btn btn-block mb-2 mt-2 shadow-none workingHBtn" data-toggle="modal" data-target="#deliverySchedule">
                    <strong>{{__('adminP.deliveryTime')}}</strong>
                </button>

                <button style="width:49%;" class="btn btn-block mb-2 mt-2 shadow-none workingHBtn" data-toggle="modal" data-target="#deliveryPLZmodal"> 
                    <strong>{{__('adminP.allowedZIPCode')}}</strong> 
                </button>

                <h4 style="color:rgb(39,195,175); width:50%" class="p-1 text-center">
                  {{Produktet::where('toRes', Auth::user()->sFor)->get()->count()}} X {{__('adminP.all')}} 
                </h4>
                <h4 style="color:rgb(39,195,175); width:50%" class="p-1 text-center">
                   {{DeliveryProd::where('toRes', Auth::user()->sFor)->get()->count()}} X  {{__('adminP.delivery')}} 
                </h4>
                
                <button onclick="removeAllDeliveryTel('{{Auth::user()->sFor}}')"  style="width:49%;" class="btn btn-outline-primary">{{__('adminP.removeAll')}}</button>
                <button onclick="addAllDeliveryTel('{{Auth::user()->sFor}}')" style="width:49%;" class="btn btn-outline-primary">{{__('adminP.addAll')}}</button>

                <button  style="width:100%;" class="btn btn-outline-success mt-1" data-toggle="modal" data-target="#addTAProdTATel"> {{__('adminP.newSnack')}} </button>

                <a href="{{route('delivery.openSortingTel')}}" style="width:100%;" class="btn btn-dark mt-2">{{__('adminP.consignmentSorting')}}</a>
            </div>

            <div id="searchProdsULtakeawayTel" class="ProdLists">
                
                @foreach(kategori::where('toRes',  Auth::user()->sFor)->get()->sortByDesc('visits') as $kat)
                    
                

                    <?php echo ' <div style="cursor: pointer; position:relative; object-fit: cover;" class="col12 p-1 text-center"
                    onclick="showProKatTel('.$kat->id.')">'?>
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
            
                        <ul class="ProdLists" id="prodsOfTATel{{$kat->id}}" style="display:none;">
                            @foreach(Produktet::where('toRes', Auth::user()->sFor)->where('kategoria', $kat->id)->get() as $allPro)
                                @if(DeliveryProd::where('prod_id', $allPro->id)->first() != null)
                                    <?php $theTA = DeliveryProd::where('prod_id', $allPro->id)->first(); ?>

                                    <li style="background-color:rgb(189, 246, 240)" id="ProdLiRight21Tel{{$theTA->id}}"> 
                                        <div class="d-flex justify-content-between" >
                                            <button style="width:7%; padding:0px;" onclick="removeDEProdTel('{{$kat->id}}','{{$theTA->id}}')" class="btn btn-outline-danger shadow-none">X</button> 
                                            <span style="width:84%;" class="ml-3"> 
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
                                                <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )
                                                    @if($allPro->recomendet == 1)
                                                        <span class="ml-3" style="color:red;">{ {{__('adminP.recommended')}} }</span>
                                                    @endif
                                                </span>
                                                <br>
                                            </span>
                                            <button style="width:7%; padding:0px;" data-toggle="modal" data-target="#editTAProdTel{{$theTA->id}}"
                                            class="btn btn-block btn-outline-default shadow-none"><i class="fas fa-pen"></i></button> 
                                        </div>
                                    </li>
                          
                                @else
                                    <li id="ProdLiRight12Tel{{$allPro->id}}">
                                        <button onclick="addDEProTel('{{$kat->id}}','{{$allPro->id}}')" style="font-size:21px;" class="btn btn-outline-danger">+</button> 
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


                            @foreach(DeliveryProd::where([['toRes', Auth::user()->sFor],['prod_id',0],['kategoria', $kat->id]])->get() as $allPro)
                                <li style="background-color:rgb(189, 246, 240)" id="ProdLiRight21Tel{{$theTA->id}}"> 
                                    <div class="d-flex justify-content-between" >
                                        <button style="font-size:19px;" onclick="removeDEProdTel('{{$kat->id}}','{{$theTA->id}}')" class="btn btn-outline-danger">X</button> 
                                        <span style="width:84%;" class="ml-3"> 
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
                                            <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )
                                                @if($allPro->recomendet == 1)
                                                    <span class="ml-3" style="color:red;">{ {{__('adminP.recommended')}} }</span>
                                                @endif
                                            </span>
                                            <br>
                                        </span>
                                        <button style="width:7%;" data-toggle="modal" data-target="#editTAProdTel{{$theTA->id}}"
                                        class="btn btn-block btn-outline-default"><i class="fas fa-pen"></i></button> 
                                    </div>
                                </li>
                            @endforeach
                        </ul>
            
            
                    @endforeach
            </div>

        </div>

    </div>















    <script>
  
                function showProKatTel(kId){
                    // $('.ProdLists').hide();
                    if($('#prodsOfTATel'+kId).is(":visible")){
                        $('#prodsOfTATel'+kId).hide();
                    }else{
                        $('#prodsOfTATel'+kId).show();
                    }
                }
                
                



        function addDEProTel(katId,ProId){
            $.ajax({
                url: '{{ route("delivery.addOne") }}',
                method: 'post',
                data: {
                    id: ProId,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#prodsOfTATel"+katId).load(location.href+" #prodsOfTATel"+katId+">*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#smthWrongAddOneTakeawayProd').val())
                }
            });
        }

        function removeDEProdTel(katId,TaId){
            $.ajax({
                url: '{{ route("delivery.destroy") }}',
                method: 'post',
                data: {id: TaId, _token: '{{csrf_token()}}'},
                success: () => {
                    $("#recProdListTel").load(location.href+" #recProdListTel>*","");
                    $("#prodsOfTATel"+katId).load(location.href+" #prodsOfTATel"+katId+">*","");
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongRemoveOneTakeawayProd').val());
                }
            });
        }


        function addAllDeliveryTel(ResId){
            // console.log(ResId);
            $.ajax({
                url: '{{ route("delivery.addAll") }}',
                method: 'post',
                data: {id: ResId, _token: '{{csrf_token()}}'},
                success: () => {
                    $("#takeawayProdsElementTel").load(location.href+" #takeawayProdsElementTel>*","");
                    $("#recProdListTel").load(location.href+" #recProdListTel>*","");
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongRemoveOneTakeawayProd').val());
                }
            });
        }
        function removeAllDeliveryTel(ResId){
            // console.log(ResId);
            $.ajax({
                url: '{{ route("delivery.destroyAll") }}',
                method: 'post',
                data: {id: ResId, _token: '{{csrf_token()}}'},
                success: () => {
                    $("#takeawayProdsElementTel").load(location.href+" #takeawayProdsElementTel>*","");
                    $("#recProdListTel").load(location.href+" #recProdListTel>*","");
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongRemoveOneTakeawayProd').val());
                }
            });
        }

        function editDEProductTel(DEid){
            $.ajax({
                url: '{{ route("delivery.edit") }}',
                method: 'post',
                data: {
                    id: DEid,
                    emri: $('#nameTel'+DEid).val(),
                    pershkrimi: $('#pershkrimiTel'+DEid).val(),
                    qmimi: $('#qmimiTel'+DEid).val(),
                    qmimi2: $('#qmimi2Tel'+DEid).val(),
                    mwst: $('#dePMwst'+DEid).val(),
                    acsCl: $('#accessByClientsVal'+DEid).val(),
                    _token: '{{csrf_token()}}'},
                success: () => {$("#takeawayProdsElementTel").load(location.href+" #takeawayProdsElementTel>*","");},
                error: (error) => {console.log(error);
                    alert($('a#smthWrongRemoveOneDeliveryProd').val());
                }
            });
        }

        function taRemoceRecTel(recTaId){
            $.ajax({
                url: '{{ route("delivery.removeRec") }}',
                method: 'post',
                data: {id: recTaId, _token: '{{csrf_token()}}'},
                success: () => {
                    location.reload();
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongRemoveOneDeliveryProd').val());
                }
            });
        }

        

        
    </script>







































<script>
     $(document).ready(function(){
        $('.ProductAll').hide();
    });//End of document ready 

    function openResProdTel(Id){
        $('#ProductStart').hide('slow');
        $('#ProductOne'+Id).show('slow');
    }

    function backProTel(){
        $('.ProductAll').hide('slow');
        $('#ProductStart').show('slow');
    }




    function showExtrasTATel(value,resId){
        if(value == 0){
            // alert(last);
            if(lastTel != ''){
                $('#'+lastTel).hide();
                $('#extProTel'+resId).val('');
                $('#typeTel'+resId).val('');
            }
        }else{
            // alert(last);
            if(lastTel != ''){
                $('#'+lastTel).hide();
                $('#extProTel'+resId).val('');
                $('#typeTel'+resId).val('');
            }

            var show = 'BoxKatTATel'+value+'R'+resId;
            lastTel = show;

            document.getElementById(show).style.display="block";
            // alert('yep'+value);
        }
    }










    function addThisTATel(theId,name,price,resId,extId){
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




    
    function addThis2TATel(theId,name,value,resId,llId){
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