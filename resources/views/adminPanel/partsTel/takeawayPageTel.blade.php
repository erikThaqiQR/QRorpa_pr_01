<?php
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
    use Carbon\Carbon;
?>
 <!-- <script src="https://kit.fontawesome.com/11d299ad01.js" crossorigin="anonymous"></script>  -->
 @include('fontawesome')
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













        
</style>



<script>
    var lastTel = '';
</script>





<section class="pl-2 pr-2 pb-5">









        <div class="swiper-container p-0 pb-1" style="border-top-left-radius:50px; border-bottom-left-radius:50px; overflow: hidden; " id="taRecomendetProTel">
            <div class="swiper-wrapper" id="recProdListTel">
                @foreach(Takeaway::where([['toRes', '=', Auth::user()->sFor],['recomendet','=', '1']])->get()->sortBy('recomendetNr') as $RePro)
                <div class="swiper-slide recProElement" data-backdrop="static" data-keyboard="false">
                    <img style="width:120px; height:120px; border-radius:50%;" src="storage/TakeawayRecomendet/{{$RePro->recomendetPic}}"
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
        function plusOneTARecTel(RecTAId){
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
     <div class="modal" id="addTAProdTel" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">{{__('adminP.addNewTakeawayProduct')}}</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <p>{{__('adminP.loading')}}...</p>
                                </div>
                                </div>
                            </div>
                        </div>




                        @foreach(Takeaway::where('toRes', Auth::user()->sFor)->get() as $takeAProd)

                        <!-- Edit Takeaway Modal -->
                        <div class="modal" id="editTAProdTel{{$takeAProd->id}}" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                         style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                             
                                    <h5 class="modal-title">{{$takeAProd->emri}} <span style="opacity:0.6;">( {{kategori::find($takeAProd->kategoria)->emri}} )</span></h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                

                                <!-- Modal body -->
                                <div class="modal-body">

                                    {{Form::open(['action' => 'TakeawayController@addToRec', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                                        <div class="d-flex justify-content-between">
                                        
                                            <div style="width:70%;" class="custom-file">
                                                {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                                                {{ Form::file('foto', ['class' => 'custom-file-input', 'id' => 'recTaFoto' , 'required']) }}
                                            </div>
                                            <input type="hidden" value="{{$takeAProd->id}}" name="id">
                                            <button style="width:30%;" type="submit" class="btn btn-outline-primary shadow-none">{{__('adminP.recommended')}}</button>
                                            
                                        </div>
                                    {{Form::close() }}
                                    <hr>

                                    <div class="form-group">
                                        <label for="name">{{__('adminP.name')}}:</label>
                                        <input type="text" class="form-control shadow-none" id="nameTel{{$takeAProd->id}}" value="{{$takeAProd->emri}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="pershkrimi">{{__('adminP.description')}}:</label>
                                        <input type="text" class="form-control shadow-none" id="pershkrimiTel{{$takeAProd->id}}" value="{{$takeAProd->pershkrimi}}">
                                    </div>

                                    <div class="mt-1 mb-2" >
                                        <div class="form-check">
                                            @if($takeAProd->accessableByClients == 1)
                                            <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$takeAProd->id}}')" id="accessByClients{{$takeAProd->id}}" checked>
                                            <label class="form-check-label pl-2" for="accessByClients{{$takeAProd->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                            <input type="hidden" id="accessByClientsVal{{$takeAProd->id}}" name="accessByClientsVal" value="1">
                                            @else
                                            <input type="checkbox" style="width:20px; height:20px;" class="form-check-input" onchange="accessToClChng('{{$takeAProd->id}}')" id="accessByClients{{$takeAProd->id}}">
                                            <label class="form-check-label pl-2" for="accessByClients{{$takeAProd->id}}"><strong>Kunden können dieses Produkt bestellen</strong></label>
                                            <input type="hidden" id="accessByClientsVal{{$takeAProd->id}}" name="accessByClientsVal" value="0">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group d-flex justify-content-between">
                                        <div style="width:45%;">
                                            <label for="qmimi">{{__('adminP.price')}}:</label>
                                            <input type="text" class="form-control shadow-none" id="qmimiTel{{$takeAProd->id}}" value="{{$takeAProd->qmimi}}">
                                        </div>
                                        <div style="width:45%;">
                                            <label for="qmimi2">{{__('adminP.price')}} (+20:00):</label>
                                            @if($takeAProd->qmimi2 != 999999)
                                                <input type="number" class="form-control shadow-none" id="qmimi2Tel{{$takeAProd->id}}" value="{{$takeAProd->qmimi2}}">
                                            @else
                                                <input type="number" class="form-control shadow-none" id="qmimi2Tel{{$takeAProd->id}}">
                                            @endif
                                        </div>
                                    </div>

                                    <input type="hidden" value="{{$takeAProd->mwstForPro}}" id="taPMwst{{$takeAProd->id}}">

                                    <div class="d-flex justify-content-between">
                                        @if ($takeAProd->mwstForPro == 0.00)
                                            <button id="setMwst{{$takeAProd->id}}Btn0" onclick="setNewMwstTel('0.00','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-dark shadow-none"><strong>MwST: 0.00 %</strong></button>
                                            <button id="setMwst{{$takeAProd->id}}Btn1" onclick="setNewMwstTel('2.60','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 2.60 %</strong></button>
                                            <button id="setMwst{{$takeAProd->id}}Btn2" onclick="setNewMwstTel('8.10','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 8.10 %</strong></button>
                                        @elseif ($takeAProd->mwstForPro == 2.60)
                                            <button id="setMwst{{$takeAProd->id}}Btn0" onclick="setNewMwstTel('0.00','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 0.00 %</strong></button>
                                            <button id="setMwst{{$takeAProd->id}}Btn1" onclick="setNewMwstTel('2.60','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-dark shadow-none"><strong>MwST: 2.60 %</strong></button>
                                            <button id="setMwst{{$takeAProd->id}}Btn2" onclick="setNewMwstTel('8.10','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 8.10 %</strong></button>
                                        @elseif ($takeAProd->mwstForPro == 8.10)
                                            <button id="setMwst{{$takeAProd->id}}Btn0" onclick="setNewMwstTel('0.00','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 0.00 %</strong></button>
                                            <button id="setMwst{{$takeAProd->id}}Btn1" onclick="setNewMwstTel('2.60','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-outline-dark shadow-none"><strong>MwST: 2.60 %</strong></button>
                                            <button id="setMwst{{$takeAProd->id}}Btn2" onclick="setNewMwstTel('8.10','{{$takeAProd->id}}')" style="width:33%;" class="btn btn-dark shadow-none"><strong>MwST: 8.10 %</strong></button>
                                        @endif
                                       
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer d-flex justify-content-between">
                                    <button style="width:45%;" type="button" class="btn btn-block btn-outline-danger" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                                    <button onclick="editTAProductTel('{{$takeAProd->id}}')" style="width:45%;" type="button" class="btn btn-block btn-outline-success shadow-none" data-dismiss="modal">{{__('adminP.save')}}</button>
                                </div>

                                </div>
                            </div>
                        </div>
                @endforeach


                <script>

                    function setNewMwstTel(mwstVal,taPId){
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

































                       <!-- Add Takeaway Modal -->
                       <div class="modal" id="addTAProdTATel" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                            style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">{{__('adminP.addNewTakeawayProduct')}}</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                                           
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="kat">{{__('adminP.category')}}</label>
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
                                                                                    <input type="checkbox" class="success" id="extPTel{{$ekstras->id}}" 
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
                                        {{ Form::submit(__('adminP.save'), ['class' => 'form-control btn btn-primary']) }}
                                        <br><br>
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


































































        <div style="width:100%;">
            <div class="d-flex flex-wrap justify-content-between mb-1">
                <h4 style="color:rgb(39,195,175); width:100%" class="p-2">
                    {{__('adminP.bringAway')}} ( {{Produktet::where('toRes', Auth::user()->sFor)->get()->count()}} X / {{Takeaway::where('toRes', Auth::user()->sFor)->get()->count()}} X )</h4>
                <button onclick="removeAllTakeawayTel('{{Auth::user()->sFor}}')"  style="width:49%;" class="btn btn-outline-primary">{{__('adminP.removeAll')}}</button>
                <button onclick="addAllTakeawayTel('{{Auth::user()->sFor}}')" style="width:49%;" class="btn btn-outline-primary">{{__('adminP.addAll')}}</button>
                <hr  style="width:100%;">
                <a href="{{route('takeaway.openSortingTel')}}" style="width:49%;" class="btn btn-dark shadow-none">
                    <strong><i class="fa-solid fa-sort"></i> {{__('adminP.productSorting')}}</strong>
                </a>
                <button style="width:49%;" class="btn btn-dark shadow-none" data-toggle="modal" data-target="#addTAProdTATel">
                    <strong><i class="fa-solid fa-plus"></i> {{__('adminP.newSnack')}}</strong>
                </button>
                <hr  style="width:100%;">
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
                                @if(Takeaway::where('prod_id', $allPro->id)->first() != null)
                                    <?php $theTA = Takeaway::where('prod_id', $allPro->id)->first(); ?>

                                    <li style="background-color:rgb(189, 246, 240)" id="ProdLiRight21Tel{{$theTA->id}}"> 
                                        <div class="d-flex justify-content-between" >
                                            <button style="width:7%; padding:0px;" data-toggle="modal" data-target="#removeTaProdConfirmationTel" onclick="PreRemoveTAProdTel('{{$kat->id}}','{{$theTA->id}}')" class="btn btn-outline-danger">X</button> 
                                            <span style="width:84%;" class="ml-3"> 
                                                <span>
                                                    {{number_format($theTA->qmimi, 2, '.', ' ')}}
                                                    @if($theTA->qmimi2 != 999999)
                                                        /{{number_format($theTA->qmimi2, 2, '.', ' ')}}
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
                                            <button style="width:7%; padding:0px;" data-toggle="modal" data-target="#editTAProdTel{{$theTA->id}}" class="btn btn-block btn-outline-default shadow-none"><i class="fas fa-pen"></i></button> 
                                        </div>
                                    </li>
                          
                                @else
                                    <li id="ProdLiRight12Tel{{$allPro->id}}">
                                        <button onclick="addTAProTel('{{$kat->id}}','{{$allPro->id}}')" style="font-size:21px;" class="btn btn-outline-danger">+</button> 
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
                            @foreach (Takeaway::where([['kategoria', $kat->id],['prod_id',0]])->get() as $allPro )
                                    <?php $theTA = $allPro; ?>

                                    <li style="background-color:rgb(189, 246, 240)" id="ProdLiRight21Tel{{$theTA->id}}"> 
                                        <div class="d-flex justify-content-between" >
                                            <button style="width:7%; padding:0px;" data-toggle="modal" data-target="#removeTaProdConfirmationTel" onclick="PreRemoveTAProdTel('{{$kat->id}}','{{$theTA->id}}')" class="btn btn-outline-danger">X</button> 
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
                                            class="btn btn-block btn-outline-default"><i class="fas fa-pen"></i></button> 
                                        </div>
                                    </li>
                                @endforeach
                        </ul>
            
            
                    @endforeach
            </div>

        </div>

    </div>


        <input type="hidden" id="removeTaProdConfirmationTelId">
        <input type="hidden" id="removeTaProdConfirmationTelCat">
        <!-- Remove the Takeaway product from the List Confirmation Modal -->
        <div class="modal fade" id="removeTaProdConfirmationTel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            data-backdrop="false"style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body d-flex flex-wrap justify-content-between">

                        <h3 class="pt-3" style="width: 85%;"><strong>{{__('adminP.deleteProduct')}}? </strong></h3>
                        <button style="width: 15%; font-size:23px;" type="button" class="btn btn-default text-right" data-dismiss="modal"><strong>X</strong></button>
                        <hr style="width: 100%;">

                        <button style="width:49%" class="btn btn-danger" data-dismiss="modal">{{__('adminP.no')}}</button>
                        <button style="width:49%" class="btn btn-success" data-dismiss="modal" onclick="removeTAProdTel()">{{__('adminP.yes')}}</button>
                    </div>
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
                
                



        function addTAProTel(katId,ProId){
            $.ajax({
                url: '{{ route("takeaway.addOne") }}',
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

        function PreRemoveTAProdTel(katId,TaId){
            $('#removeTaProdConfirmationTelId').val(TaId);
            $('#removeTaProdConfirmationTelCat').val(katId);
        }

        function removeTAProdTel(katId,TaId){
            var katId = $('#removeTaProdConfirmationTelCat').val() ;
            var TaId = $('#removeTaProdConfirmationTelId').val();
            $.ajax({
                url: '{{ route("takeaway.destroy") }}',
                method: 'post',
                data: {id: TaId, _token: '{{csrf_token()}}'},
                success: () => {
                    $("#recProdListTel").load(location.href+" #recProdListTel>*","");
                    $("#prodsOfTATel"+katId).load(location.href+" #prodsOfTATel"+katId+">*","");
                },
                error: (error) => {console.log(error);
                    alert($('#smthWrongAddOneTakeawayProd').val());
                }
            });
        }


        function addAllTakeawayTel(ResId){
            // console.log(ResId);
            $.ajax({
                url: '{{ route("takeaway.addAll") }}',
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
        function removeAllTakeawayTel(ResId){
            // console.log(ResId);
            $.ajax({
                url: '{{ route("takeaway.destroyAll") }}',
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

        function editTAProductTel(TAid){
            $.ajax({
                url: '{{ route("takeaway.edit") }}',
                method: 'post',
                data: {
                    id: TAid,
                    emri: $('#nameTel'+TAid).val(),
                    pershkrimi: $('#pershkrimiTel'+TAid).val(),
                    qmimi: $('#qmimiTel'+TAid).val(),
                    qmimi2: $('#qmimi2Tel'+TAid).val(),
                    mwst: $('#taPMwst'+TAid).val(),
                    acsCl: $('#accessByClientsVal'+TAid).val(),
                    _token: '{{csrf_token()}}'},
                success: () => {$("#takeawayProdsElementTel").load(location.href+" #takeawayProdsElementTel>*","");},
                error: (error) => {console.log(error);
                    alert($('#smthWrongRemoveOneTakeawayProd').val());
                }
            });
        }

        function taRemoceRecTel(recTaId){
            $.ajax({
                url: '{{ route("takeaway.removeRec") }}',
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