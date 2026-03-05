<?php

    use Illuminate\Support\Facades\Auth;
    use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Takeaway']])->first();
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
    use App\TakeawaySchedule;
    use Carbon\Carbon;
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

        @media (max-width:420px) and (min-width:376px)) {
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





        
</style>



<script>
    var last="";
</script>






<section class="pl-2 pr-2 pb-5">








      
            <div class="swiper-container p-0" style="border-top-left-radius:50px; border-bottom-left-radius:50px; overflow: hidden; " id="taRecomendetPro">
                <div class="swiper-wrapper" id="recProdList">

                    @foreach(Takeaway::where([['toRes', '=', Auth::user()->sFor],['recomendet','=', '1']])->get()->sortBy('recomendetNr') as $RePro)
                    <div class="swiper-slide recProElement" data-backdrop="static" data-keyboard="false">
                        <img style="width:120px; height:120px; border-radius:50%;" src="storage/TakeawayRecomendet/{{$RePro->recomendetPic}}"
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


                                <button class="btn btn-outline-dark" style="width:33%;" onclick="taRemoceRec('{{$RePro->id}}')">x</button>


                            @if($RePro->recomendetNr < Takeaway::where([['toRes', '=', Auth::user()->sFor],['recomendet','=', '1']])->get()->max('recomendetNr'))
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
                url: '{{ route("takeaway.minusOneRec") }}',
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
                url: '{{ route("takeaway.plusOneRec") }}',
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

  





























                        <!-- Add Takeaway Modal -->
                        <div class="modal" id="addTAProdTA" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">{{__('adminP.addNewTakeawayProduct')}}</h4>
                                    <button type="button" class="close" data-dismiss="modal"><strong>X</strong></button>
                                </div>

                                {{Form::open(['action' => 'TakeawayController@store', 'method' => 'post' ]) }}
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
                                        {{ Form::hidden('isWaiter', 1, ['id' => 'isWaiter'])}}

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
                        @foreach(Takeaway::where('toRes', Auth::user()->sFor)->get() as $takeAProd)

                            <!-- Edit Takeaway Modal -->
                            <div class="modal" id="editTAProd{{$takeAProd->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                
                                        <h4 class="modal-title">{{$takeAProd->emri}} <span style="opacity:0.6;">( {{kategori::find($takeAProd->kategoria)->emri}} )</span></h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    

                                    <!-- Modal body -->
                                    <div class="modal-body">

                                        {{Form::open(['action' => 'TakeawayController@addToRec', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                                            <div class="d-flex justify-content-between">
                                            
                                                <div style="width:70%;" class="custom-file">
                                                    {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                                                    {{ Form::file('foto', ['class' => 'custom-file-input' , 'required']) }}
                                                </div>
                                                <input type="hidden" value="{{$takeAProd->id}}" name="id">
                                                <input type="hidden" value="1" name="isWaiter">
                                                <button style="width:30%;" type="submit" class="btn btn-outline-primary">+ {{__('adminP.recommended')}}</button>
                                                
                                            </div>
                                        {{Form::close() }}
                                        <hr>


                                        <div class="form-group">
                                            <label for="name">{{__('adminP.name')}}:</label>
                                            <input type="text" class="form-control shadow-none" id="name{{$takeAProd->id}}" value="{{$takeAProd->emri}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="pershkrimi">{{__('adminP.description')}}:</label>
                                            <input type="text" class="form-control shadow-none" id="pershkrimi{{$takeAProd->id}}" value="{{$takeAProd->pershkrimi}}">
                                        </div>

                                        <div class="mt-1 mb-2" >
                                            <div class="form-check">
                                                @if($takeAProd->accessableByClients == 1)
                                                <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$takeAProd->id}}')" id="accessByClients{{$takeAProd->id}}" checked>
                                                <label class="form-check-label pl-2" style="font-size:1.3rem;" for="accessByClients{{$takeAProd->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                                <input type="hidden" id="accessByClientsVal{{$takeAProd->id}}" name="accessByClientsVal" value="1">
                                                @else
                                                <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$takeAProd->id}}')" id="accessByClients{{$takeAProd->id}}">
                                                <label class="form-check-label pl-2" style="font-size:1.3rem;" for="accessByClients{{$takeAProd->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                                <input type="hidden" id="accessByClientsVal{{$takeAProd->id}}" name="accessByClientsVal" value="0">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-between">
                                            <div style="width:45%;">
                                                <label for="qmimi">{{__('adminP.price')}}:</label>
                                                <input type="text" class="form-control shadow-none" id="qmimi{{$takeAProd->id}}" value="{{$takeAProd->qmimi}}">
                                            </div>
                                            <div style="width:45%;">
                                                <label for="qmimi2">{{__('adminP.price')}} (+20:00):</label>
                                                @if($takeAProd->qmimi2 != 999999)
                                                    <input type="number" class="form-control shadow-none" id="qmimi2{{$takeAProd->id}}" value="{{$takeAProd->qmimi2}}">
                                                @else
                                                    <input type="number" class="form-control shadow-none" id="qmimi2{{$takeAProd->id}}">
                                                @endif
                                            </div>
                                        
                                        </div>

                                        <input type="hidden" value="{{$takeAProd->mwstForPro}}" id="taPMwst{{$takeAProd->id}}">

                                        <div class="d-flex justify-content-between">
                                            @if ($takeAProd->mwstForPro == 0.00)
                                                <button id="setMwst{{$takeAProd->id}}Btn0" onclick="setNewMwst('0.00','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-dark shadow-none"><strong>MwST: 0.00 %</strong></button>
                                                <button id="setMwst{{$takeAProd->id}}Btn1" onclick="setNewMwst('2.60','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 2.60 %</strong></button>
                                                <button id="setMwst{{$takeAProd->id}}Btn2" onclick="setNewMwst('8.10','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 8.10 %</strong></button>
                                            @if ($takeAProd->mwstForPro == 2.60)
                                                <button id="setMwst{{$takeAProd->id}}Btn0" onclick="setNewMwst('0.00','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 0.00 %</strong></button>
                                                <button id="setMwst{{$takeAProd->id}}Btn1" onclick="setNewMwst('2.60','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-dark shadow-none"><strong>MwST: 2.60 %</strong></button>
                                                <button id="setMwst{{$takeAProd->id}}Btn2" onclick="setNewMwst('8.10','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 8.10 %</strong></button>
                                            @elseif ($takeAProd->mwstForPro == 8.10)
                                                <button id="setMwst{{$takeAProd->id}}Btn0" onclick="setNewMwst('0.00','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 0.00 %</strong></button>
                                                <button id="setMwst{{$takeAProd->id}}Btn1" onclick="setNewMwst('2.60','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 2.60 %</strong></button>
                                                <button id="setMwst{{$takeAProd->id}}Btn2" onclick="setNewMwst('8.10','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-dark shadow-none"><strong>MwST: 8.10 %</strong></button>
                                            @endif
                                           
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer d-flex justify-content-between">
                                        <button style="width:45%;" type="button" class="btn btn-block btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                                        <button onclick="editTAProduct('{{$takeAProd->id}}')" style="width:45%;" type="button" class="btn btn-block btn-outline-success" data-dismiss="modal">{{__('adminP.save')}}</button>
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

                            function setNewMwst(mwstVal,taPId){

                                if(mwstVal == 0.00){
                                    if($('#setMwst'+taPId+'Btn0').hasClass('btn-outline-dark')){
                                        $('#setMwst'+taPId+'Btn0').removeClass('btn-outline-dark');
                                        $('#setMwst'+taPId+'Btn0').addClass('btn-dark');

                                        $('#setMwst'+taPId+'Btn1').removeClass('btn-dark');
                                        $('#setMwst'+taPId+'Btn1').addClass('btn-outline-dark');
                                        $('#setMwst'+taPId+'Btn2').removeClass('btn-dark');
                                        $('#setMwst'+taPId+'Btn2').addClass('btn-outline-dark');
                                       
                                        $('#taPMwst'+taPId).val(parseFloat(mwstVal).toFixed(2))
                                    }
                                }else if(mwstVal == 2.60){
                                    if($('#setMwst'+taPId+'Btn1').hasClass('btn-outline-dark')){
                                        $('#setMwst'+taPId+'Btn1').removeClass('btn-outline-dark');
                                        $('#setMwst'+taPId+'Btn1').addClass('btn-dark');

                                        $('#setMwst'+taPId+'Btn0').removeClass('btn-dark');
                                        $('#setMwst'+taPId+'Btn0').addClass('btn-outline-dark');
                                        $('#setMwst'+taPId+'Btn2').removeClass('btn-dark');
                                        $('#setMwst'+taPId+'Btn2').addClass('btn-outline-dark');
                                       
                                        $('#taPMwst'+taPId).val(parseFloat(mwstVal).toFixed(2))
                                    }
                                }else if(mwstVal == 8.10){
                                    if($('#setMwst'+taPId+'Btn2').hasClass('btn-outline-dark')){
                                        $('#setMwst'+taPId+'Btn2').removeClass('btn-outline-dark');
                                        $('#setMwst'+taPId+'Btn2').addClass('btn-dark');

                                        $('#setMwst'+taPId+'Btn0').removeClass('btn-dark');
                                        $('#setMwst'+taPId+'Btn0').addClass('btn-outline-dark');
                                        $('#setMwst'+taPId+'Btn1').removeClass('btn-dark');
                                        $('#setMwst'+taPId+'Btn1').addClass('btn-outline-dark');
                                       
                                        $('#taPMwst'+taPId).val(parseFloat(mwstVal).toFixed(2))
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
    <button class="btn btn-block mb-2 mt-2 workingHBtn" data-toggle="modal" data-target="#takeawaySchedule"> <strong>{{__('adminP.openingTimeForTakeaway')}}</strong> </button>


    <!-- The Modal -->
    <div class="modal" id="takeawaySchedule" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">{{__('adminP.openingTimeForTakeaway')}}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="d-flex justify-content-between flex-wrap">

                <?php $TAschedule = TakeawaySchedule::where('toRes', Auth::user()->sFor)->first(); ?>
                @if($TAschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.monday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day11In" value="{{$TAschedule->day11S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day11Out" value="{{$TAschedule->day11E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day21In" value="{{$TAschedule->day21S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day21Out" value="{{$TAschedule->day21E}}">
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

                @if($TAschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.tuesday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day12In" value="{{$TAschedule->day12S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day12Out" value="{{$TAschedule->day12E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day22In" value="{{$TAschedule->day22S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day22Out" value="{{$TAschedule->day22E}}">
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

                @if($TAschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.wednesday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day13In" value="{{$TAschedule->day13S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day13Out" value="{{$TAschedule->day13E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day23In" value="{{$TAschedule->day23S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day23Out" value="{{$TAschedule->day23E}}">
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

                @if($TAschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.thursday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day14In" value="{{$TAschedule->day14S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day14Out" value="{{$TAschedule->day14E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day24In" value="{{$TAschedule->day24S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day24Out" value="{{$TAschedule->day24E}}">
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

                @if($TAschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.friday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day15In" value="{{$TAschedule->day15S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day15Out" value="{{$TAschedule->day15E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day25In" value="{{$TAschedule->day25S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day25Out" value="{{$TAschedule->day25E}}">
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

                @if($TAschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.saturday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day16In" value="{{$TAschedule->day16S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day16Out" value="{{$TAschedule->day16E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day26In" value="{{$TAschedule->day26S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day26Out" value="{{$TAschedule->day26E}}">
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

                @if($TAschedule != null)
                    <p class="text-right pr-4" style="width:20%"> <strong>{{__('adminP.sunday')}}</strong></p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day10In" value="{{$TAschedule->day10S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day10Out" value="{{$TAschedule->day10E}}">
                    <p  class="text-center" style="width:5%;">{{__('adminP.and')}}</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day20In" value="{{$TAschedule->day20S}}">
                    <p class="text-center" style="width:5%;">:</p>
                    <input class="text-center" style="height:22px; width:16.25%; border:none; border-bottom:1px solid gray;" type="text" id="day20Out" value="{{$TAschedule->day20E}}">
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
            <button onclick="saveTakeawaySchedule('{{Auth::user()->sFor}}')" style="width:45%" type="button" class="btn btn-outline-success" data-dismiss="modal">{{__('adminP.saveOnComputer')}}</button>
        </div>

        </div>
    </div>
    </div>


    <script>
        function saveTakeawaySchedule(ResId){
            $.ajax({
				url: '{{ route("takeaway.scheduleSet") }}',
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












































    <div class="d-flex justify-content-between mt-3" id="takeawayProdsElement">



        <div style="width:49.5%;" id="leftSideTA">
            <div class="d-flex mb-1">
                <h4 style="color:rgb(39,195,175); width:70%" class="p-2 mb-2">{{__('adminP.productsForTakeaway')}} ( {{ Takeaway::where('toRes', Auth::user()->sFor)->get()->count()}} X )</h4>
                <button onclick="removeAllTakeaway('{{Auth::user()->sFor}}')"  style="width:30%;" class="btn btn-block btn-outline-danger">{{__('adminP.removeAll')}}</button>
            </div>
       

            <button class="btn btn-block mb-2" style="border:1px solid lightgray; padding:15px;" data-toggle="modal" data-target="#addTAProdTA">
                {{__('adminP.addNewTakeawayProduct')}}
            </button>



            @foreach(kategori::where('toRes',  Auth::user()->sFor)->get()->sortBy('positionTakeaway') as $kat)
                    
                @if(Takeaway::where('toRes', Auth::user()->sFor)->where('kategoria', $kat->id)->get()->count() > 0)

                    <div style="cursor: pointer; position:relative; object-fit: cover;" class="col12 p-1 text-center">
                        <div style="width:15%; display:inline;" data-toggle="modal" data-target="#changeTakeawayCatPosition{{$kat->id}}">
                            <span class="text-center pr-3" style="font-size: 22px; color:rgb(72,81,87);"><strong>{{$kat->positionTakeaway}} #</strong></span>
                        </div>
                        <img onclick="showProKatLeft('{{$kat->id}}')" style="border-radius:30px; width:85%; height:120px; display:inline;" 
                            src="storage/kategoriaUpload/{{$kat->foto}}" alt="">
            
                        @if(strlen($kat->emri) > 20)
                            <div class="teksti d-flex" style="font-size:20px;  margin-bottom:13px; margin-left:15%;">          
                                <strong style="margin-left:15%;" onclick="showProKatLeft('{{$kat->id}}')">{{$kat->emri}} </strong>
                                <!-- <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> -->
                                <p class="pr-5">{{$kat->visits}} <i class="far fa-eye"></i></p>
                            </div>
                        @else
                            <div class="teksti d-flex" >          
                                <strong onclick="showProKatLeft('{{$kat->id}}')" style="margin-left:15%;">{{$kat->emri}} </strong>
                                <!-- <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> -->
                                <p class="pr-5">{{$kat->visits}} <i class="far fa-eye"></i></p>
                            </div>
                        @endif
                        <input type="hidden" value="0" id="state{{$kat->id}}">
                    </div>

                    <ul id="prodsOfTALeft{{$kat->id}}" class="ProdLists" style="display:none;">
                        @foreach(Takeaway::where('toRes', Auth::user()->sFor)->where('kategoria', $kat->id)->get()->sortBy('position') as $takeAProd)
                            <li id="hideThisTAElDel{{$takeAProd->id}}"> 
                                <div class="d-flex justify-content-between">
                                    <div style="width:10%;" data-toggle="modal" data-target="#changeTakeawayProdPosition{{$takeAProd->id}}">
                                        <span class="text-center pr-3" style="font-size: 22px; color:rgb(72,81,87);"><strong>{{$takeAProd->position}} #</strong></span>
                                    </div>
                                    @if($takeAProd ->recomendet == 0)
                                    <button style="width:7%;" onclick="preRemoveTAProd('0', '{{$takeAProd->id}}','{{$kat->id}}')" class="btn btn-outline-danger"
                                        data-toggle="modal" data-target="#removeTaProdConfirmation">X</button> 
                                    @else
                                    <button style="width:7%;" onclick="preRemoveTAProd('1', '{{$takeAProd->id}}','{{$kat->id}}')" class="btn btn-outline-danger"
                                        data-toggle="modal" data-target="#removeTaProdConfirmation">X</button> 
                                    @endif


                                    <span style="width:74%;" class="ml-3"> 
                                        <span>
                                            {{number_format($takeAProd->qmimi, 2, '.', ' ')}}
                                            @if($takeAProd->qmimi2 != 999999)
                                                /{{number_format($takeAProd->qmimi2, 2, '.', ' ')}}
                                            @endif
                                            <sub>{{__('adminP.currencyShow')}}</sub>
                                        </span>
                                        <?php
                                            if($takeAProd->restrictPro != 0){
                                                $ageRest = $takeAProd->restrictPro;
                                            }else{
                                                $prRest = Produktet::find($takeAProd->prod_id);
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
                                            {{$takeAProd->emri}}
                                        </span> 
                                        <span style="opacity:0.6;">( {{kategori::find($takeAProd->kategoria)->emri}} )
                                            @if($takeAProd->recomendet == 1)
                                                <span class="ml-3" style="color:red;">{ {{__('adminP.recommended')}} }</span>
                                            @endif
                                        </span>
                                        <br>
                                    </span>
                                    <button style="width:7%;" data-toggle="modal" data-target="#editTAProd{{$takeAProd->id}}" class="btn btn-block btn-outline-default shadow-none"><i class="fas fa-pen"></i></button> 
                                </div>
                            </li>

                                 <!-- Change Takeaway Category Position Modal -->
                                <div class="modal fade" id="changeTakeawayProdPosition{{$takeAProd->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                    data-backdrop="false"style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body d-flex flex-wrap justify-content-between">
                                                <h3 class="pt-3" style="width: 85%;"><strong>{{$takeAProd->emri}}</strong></h3>
                                                <button style="width: 15%; font-size:23px;" type="button" class="btn btn-default text-right" data-dismiss="modal"><strong>X</strong></button>

                                                <hr style="width: 100%;">
                                                <p style="width:49%; font-size:18px;" class="text-right pt-2"><strong>{{__('adminP.position')}}: </strong></p>        
                                                <input class="form-control text-center mb-2" type="number" style="width:49%; border:none; border-bottom: 1px solid black; font-size:22px; font-weight:bold;" 
                                                    value="{{$takeAProd->position}}" id="changeTakeawayProdPositionVal{{$takeAProd->id}}">
                                                
                                                <button style="width:49%" class="btn btn-danger" data-dismiss="modal">{{__('adminP.abort')}}</button>
                                                <button style="width:49%" class="btn btn-success" onclick="changeTakeawayProdPositionSave('{{$takeAProd->id}}','{{$kat->id}}')">{{__('adminP.saveOnComputer')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        @endforeach
                    </ul>


                    <!-- Change Takeaway Category Position Modal -->
                    <div class="modal fade" id="changeTakeawayCatPosition{{$kat->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                        data-backdrop="false"style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body d-flex flex-wrap justify-content-between">
                                    <h3 class="pt-3" style="width: 85%;"><strong>{{$kat->emri}}</strong></h3>
                                    <button style="width: 15%; font-size:23px;" type="button" class="btn btn-default text-right" data-dismiss="modal"><strong>X</strong></button>

                                    <hr style="width: 100%;">
                                    <p style="width:49%; font-size:18px;" class="text-right pt-2"><strong>{{__('adminP.position')}} : </strong></p>        
                                    <input class="form-control text-center mb-2" type="number" style="width:49%; border:none; border-bottom: 1px solid black; font-size:22px; font-weight:bold;" 
                                        value="{{$kat->positionTakeaway}}" id="changeTakeawayCatPositionVal{{$kat->id}}">
                                    
                                    <button style="width:49%" class="btn btn-danger" data-dismiss="modal">{{__('adminP.abort')}}</button>
                                    <button style="width:49%" class="btn btn-success" onclick="changeTakeawayCatPositionSave('{{$kat->id}}')">{{__('adminP.saveOnComputer')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <input type="hidden" id="removeTaProdConfirmationRec">
        <input type="hidden" id="removeTaProdConfirmationId">
        <input type="hidden" id="removeTaProdConfirmationCat">
        <!-- Remove the Takeaway product from the List Confirmation Modal -->
        <div class="modal fade" id="removeTaProdConfirmation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            data-backdrop="false"style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body d-flex flex-wrap justify-content-between">

                        <h3 class="pt-3" style="width: 85%;"><strong>{{__('adminP.deleteProduct')}}? </strong></h3>
                        <button style="width: 15%; font-size:23px;" type="button" class="btn btn-default text-right" data-dismiss="modal"><strong>X</strong></button>
                        <hr style="width: 100%;">

                        <button style="width:49%" class="btn btn-danger" data-dismiss="modal">{{__('adminP.no')}}</button>
                        <button style="width:49%" class="btn btn-success" data-dismiss="modal" onclick="removeTAProd()">{{__('adminP.yes')}}</button>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function changeTakeawayCatPositionSave(catId){
                $.ajax({
                    url: '{{ route("takeaway.changeCategoryOrder") }}',
                    method: 'post',
                    data: {
                        catId: catId,
                        newPoz: $('#changeTakeawayCatPositionVal'+catId).val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        $('#changeTakeawayCatPosition'+catId).modal('toggle');
                        $("#leftSideTA").load(location.href+" #leftSideTA>*","");
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }

            function changeTakeawayProdPositionSave(prodId,catId){
                $.ajax({
                    url: '{{ route("takeaway.changeProductOrder") }}',
                    method: 'post',
                    data: {
                        prodId: prodId,
                        newPoz: $('#changeTakeawayProdPositionVal'+prodId).val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        $('#changeTakeawayProdPosition'+prodId).modal('toggle');
                        $("#prodsOfTALeft"+catId).load(location.href+" #prodsOfTALeft"+catId+">*","");
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }

            function preRemoveTAProd(rec,TaId,catId){
                $('#removeTaProdConfirmationRec').val(rec);
                $('#removeTaProdConfirmationId').val(TaId);
                $('#removeTaProdConfirmationCat').val(catId);
            }

            function removeTAProd(){
                var catId =  $('#removeTaProdConfirmationCat').val();
                $.ajax({
                    url: '{{ route("takeaway.destroy") }}',
                    method: 'post',
                    data: {id: $('#removeTaProdConfirmationId').val(), _token: '{{csrf_token()}}'},
                    success: (res) => {
                        res = res.replace(/\s/g, '');
                        if($('#removeTaProdConfirmationRec').val() == 1){
                            location.reload();
                        }else{
                            $("#prodsOfTA"+catId).load(location.href+" #prodsOfTA"+catId+">*","");
                            if(res == 'no'){
                                $("#leftSideTA").load(location.href+" #leftSideTA>*","");
                            }else{
                                $("#prodsOfTALeft"+catId).load(location.href+" #prodsOfTALeft"+catId+">*","");
                            }
                        }
                    },
                    error: (error) => {console.log(error);
                        alert($('#smthWrongRemoveOneTakeawayProd').val());
                    }
                });
            }
        </script>

     




































        <!-- Border -->
        <div style="width:0.2%; border:2px solid gray;"></div>  
        






























        <div style="width:49.5%;">
            <div class="d-flex mb-1">
                <h4 style="color:rgb(39,195,175); width:70%" class="p-2">{{__('adminP.allproducts')}} ( {{Produktet::where('toRes', Auth::user()->sFor)->get()->count()}} X )</h4>
                <button onclick="addAllTakeaway('{{Auth::user()->sFor}}')" style="width:30%;" class="btn btn-block btn-outline-primary">{{__('adminP.addAll')}}</button>
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
                                @if(Takeaway::where('prod_id', $allPro->id)->first() != null)
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
                                        <button onclick="addTAPro('{{$allPro->id}}','{{$kat->id}}')" style="font-size:21px;" class="btn btn-outline-danger">+</button> 
                                            <span class="ml-3"> 
                                                <span>{{$allPro->qmimi}}
                                                    @if($allPro->qmimi2 != 999999)
                                                        /{{$allPro->qmimi2}}
                                                    @endif
                                                    <sup>{{__('adminP.currencyShow')}}</sup>
                                                </span>    
                                                <span class="ml-3">{{$allPro->emri}} </span> 
                                                <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span>
                                            </span>
                                    </li>
                                    <li id="ProdLiRight01{{$allPro->id}}" style="background-color:rgb(39,195,175); color:white; display:none;">
                                        <button style="font-size:19px; color:white;" class="btn btn-outline-default">✓</button> 
                                        <span class="ml-3"> 
                                            <span>{{$allPro->qmimi}}
                                                @if($allPro->qmimi2 != 999999)
                                                    /{{$allPro->qmimi2}}
                                                @endif
                                                    <sup>{{__('adminP.currencyShow')}}</sup>
                                            </span>    
                                            <span class="ml-3">{{$allPro->emri}} </span> 
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


        function addTAPro(ProId,catId){
            $.ajax({
                url: '{{ route("takeaway.addOne") }}',
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

   


        function addAllTakeaway(ResId){
            // console.log(ResId);
            $.ajax({
                url: '{{ route("takeaway.addAll") }}',
                method: 'post',
                data: {id: ResId, _token: '{{csrf_token()}}'},
                success: () => {
                    $("#takeawayProdsElement").load(location.href+" #takeawayProdsElement>*","");
                    $("#recProdList").load(location.href+" #recProdList>*","");
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongAddOneTakeawayProd').val());
                }
            });
        }
        function removeAllTakeaway(ResId){
            // console.log(ResId);
            $.ajax({
                url: '{{ route("takeaway.destroyAll") }}',
                method: 'post',
                data: {id: ResId, _token: '{{csrf_token()}}'},
                success: () => {
                    $("#takeawayProdsElement").load(location.href+" #takeawayProdsElement>*","");
                    $("#recProdList").load(location.href+" #recProdList>*","");
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongAddOneTakeawayProd').val());
                }
            });
        }

        function editTAProduct(TAid){
            $.ajax({
                url: '{{ route("takeaway.edit") }}',
                method: 'post',
                data: {
                    id: TAid,
                    emri: $('#name'+TAid).val(),
                    pershkrimi: $('#pershkrimi'+TAid).val(),
                    qmimi: $('#qmimi'+TAid).val(),
                    qmimi2: $('#qmimi2'+TAid).val(),
                    mwst: $('#taPMwst'+TAid).val(),
                    acsCl: $('#accessByClientsVal'+TAid).val(),
                    _token: '{{csrf_token()}}'},
                success: () => {$("#takeawayProdsElement").load(location.href+" #takeawayProdsElement>*","");},
                error: (error) => {console.log(error);
                    alert($('#smthWrongAddOneTakeawayProd').val());
                }
            });
        }

        function taRemoceRec(recTaId){
            $.ajax({
                url: '{{ route("takeaway.removeRec") }}',
                method: 'post',
                data: {id: recTaId, _token: '{{csrf_token()}}'},
                success: () => {
                    location.reload();
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongAddOneTakeawayProd').val());
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