<?php

    use Illuminate\Support\Facades\Auth;
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Frei']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage'));
        exit();
    }
    use App\Produktet;
    use App\FreeProducts;
    use App\kategori;
    use App\Restorant;
?>
<style>
    #searchProdsTel {
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

    .qrorpaBtn{
        border-radius: 15px;
        border:1px solid rgb(39,190,175);
        color: rgb(39,195,175);
        font-weight: bold;
        padding-bottom: 5px;
        padding-top: 5px ;
    }
    .qrorpaBtn2{
        background-color: rgb(39,195,175);
        color: white;
    }
</style>





<section class="pl-2 pr-2 pb-5">
<div class="d-flex justify-content-between flex-wrap" id="freeProElementsTel">
    
    <div style="width:100%;" class="d-flex justify-content-around mt-2">
        <button style="width:42%" class="btn qrorpaBtn" data-toggle="modal" data-target="#freeProdDescTel"> {{__('adminP.description')}}</button>
        <button style="width:42%" class="btn qrorpaBtn" data-toggle="modal" data-target="#freeProdPriceTel"> {{__('adminP.orderPrice')}} </button>
    </div>

    <div class="alert alert-danger text-center mt-2" style="display:none; width:100%;" id="errorFreeValueTel">
        {{__('adminP.writeValidValue')}}
    </div>
    <div class="alert alert-danger text-center mt-2" style="display:none; width:100%;" id="errorFreeValueTextTel">
        {{__('adminP.writeValidText')}}
    </div>

    <div class="alert alert-success text-center mt-2" style="display:none; width:100%;" id="successFreeValueTel">
        {{__('adminP.newOrderPrice')}} :<span id="successFreeValueTelVal" style="text-decoration:underline;"></span>
    </div>
    <div class="alert alert-success text-center mt-2" style="display:none; width:100%;" id="successFreeValueTextTel">
        {{__('adminP.newDescription')}} <span id="successFreeValueTextTelVal" style="text-decoration:underline;"></span>
    </div>



    <div style="width:100%;">
        <h4 style="color:rgb(39,195,175);" class="p-2">{{__('adminP.freeProducts')}}</h4>
        <input type="text" id="searchProdsTel" onkeyup="searchProdsFuncTel()" placeholder="{{__('adminP.searchForProducts')}}.." title="{{__('adminP.writeProductsName')}}">

        <button style="width:100%" class="btn qrorpaBtn mb-2" data-toggle="modal" data-target="#addNewFreeProTel"> {{__("adminP.freeProduct")}} + </button>


        <ul id="searchProdsULTel" class="ProdLists">
            @foreach(FreeProducts::where([['toRes', Auth::user()->sFor],['nameExt','!=','none']])->get() as $allFreeProExt)
                <li style="background-color:rgb(39,195,175); color:white;">
                    <button onclick="removeFreeProTel('{{$allFreeProExt->id}}')" class="btn btn-outline-danger">X</button>
                    <span class="ml-3"> {{$allFreeProExt->nameExt}}</span>
                </li>
            @endforeach
            @foreach(Produktet::where('toRes', Auth::user()->sFor)->get() as $allPro)
                @if(FreeProducts::where('prod_id', $allPro->id)->first() != null)
                <?php $toRem = FreeProducts::where("prod_id",$allPro->id)->first()->id; ?>
                    <li style="background-color:rgb(39,195,175); color:white;">
                        <button onclick="removeFreeProTel('{{$toRem}}')" class="btn btn-outline-danger">X</button>
                        <span class="ml-3"> {{$allPro->emri}} <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span></span>
                    </li>
                @else
                    <li>
                        <button onclick="addFreeProTel('{{$allPro->id}}')" style="font-weight:bolder;" class="btn btn-outline-danger">✓</button> 
                        <span class="ml-3"> {{$allPro->emri}} <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span></span>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>

</div>










<!-- The Modal -->
<div class="modal" id="freeProdDescTel">
  <div class="modal-dialog">
    <div class="modal-content">

        <!-- Modal body -->
        <div class="modal-body d-flex flex-wrap">
            <div style="width:100%;" class="mt-2 mb-2 d-flex flex-wrap">
                <h5 style="color:rgb(39,195,175); width:100%; font-size:14px;">{{__('adminP.freeProductCartDescription')}} : </h5>
                @if(Restorant::find(Auth::user()->sFor)->textFree != 'empty')
                    <h5 style="color:rgb(39,195,175); width:100%;" class="text-center"> <u> {{Restorant::find(Auth::user()->sFor)->textFree}} </u> </h5>
                @endif
            </div>
            <div style="width:100%;" class="d-flex justify-content-center text-right">
                <input style="width:100%; border:none; border-bottom:1px solid gray; font-size:21px;" class="text-center" type="text" id="newTextFreeTel">
            </div>
        </div>

      <!-- Modal footer -->
      <div class=" d-flex justify-content-around mb-3">
        <button style="width:48%;" type="button" class="btn btn-danger" data-dismiss="modal">{{__('adminP.close')}}</button>
        <button style="width:48%;" type="button" class="btn qrorpaBtn2" data-dismiss="modal"
            onclick="changeTextFreeTel('newTextFreeTel','{{Auth::user()->sFor}}')">{{__('adminP.saveOnComputer')}}</button>
      </div>
    </div>
  </div>
</div>

<!-- The Modal -->
<div class="modal" id="freeProdPriceTel">
  <div class="modal-dialog">
    <div class="modal-content">
        
        <!-- Modal body -->
        <div class="modal-body d-flex flex-wrap">
            <div style="width:100%;" class="mt-2 mb-2 d-flex flex-wrap">
                <h5 style="color:rgb(39,195,175); width:100%; font-size:14px;">{{__('adminP.freeProductsForOrdersFrom')}} </h5>
                <h5 style="color:rgb(39,195,175); width:100%;" class="text-center"> <u> {{Restorant::find(Auth::user()->sFor)->priceFree }} {{__('adminP.currencyShow')}} </u> </h5>
            </div>
            <div style="width:100%;" class="d-flex justify-content-center text-right">
                <input style="width:100%; border:none; border-bottom:1px solid gray; font-size:21px;" class="text-center" type="text" id="newPriceFreeTel">
            </div>
        </div>

      <!-- Modal footer -->
      <div class=" d-flex justify-content-around mb-3">
        <button style="width:48%;" type="button" class="btn btn-danger" data-dismiss="modal">{{__('adminP.close')}}</button>
        <button style="width:48%;" type="button" class="btn qrorpaBtn2" data-dismiss="modal"
            onclick="changePriceFreeTel('newPriceFreeTel','{{Auth::user()->sFor}}')">{{__('adminP.changeValue')}}</button>
      </div>
    </div>
  </div>
</div>




<!-- The Modal -->
<div class="modal" id="addNewFreeProTel">
  <div class="modal-dialog">
    <div class="modal-content">
        
        <!-- Modal body -->
        <div class="modal-body d-flex flex-wrap">
            <div style="width:100%;" class="mt-2 mb-2 d-flex flex-wrap">
                <h5 style="color:rgb(39,195,175); width:100%; font-size:14px;">{{__('adminP.addFreeProductForOrders')}} +{{Restorant::find(Auth::user()->sFor)->priceFree}} {{__('adminP.addFreeProductForOrders')}} </h5>
            </div>
            <div style="width:100%;" class="d-flex justify-content-center text-right">
                <input style="width:100%; border:none; border-bottom:1px solid gray; font-size:21px;" class="text-center" type="text" id="extraFreeNameTel">
            </div>
        </div>
        
      <!-- Modal footer -->
      <div class=" d-flex justify-content-around mb-3">
        <button style="width:48%;" type="button" class="btn btn-danger" data-dismiss="modal">{{__('adminP.close')}}</button>
        <button style="width:48%;" type="button" class="btn qrorpaBtn2" data-dismiss="modal"
            onclick="saveExtraFreeTel()">{{__('adminP.saveOnComputer')}}</button>
      </div>
    </div>
  </div>
</div>





































    <script>
        function searchProdsFuncTel() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById("searchProdsTel");
            filter = input.value.toUpperCase();
            ul = document.getElementById("searchProdsULTel");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                txtValue = li[i].textContent || li[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }


        function removeFreeProTel(fProId){
            $.ajax({
                url: '{{ route("freeProd.destroy") }}',
                method: 'post',
                data: {
                    id: fProId,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#freeProElementsTel").load(location.href+" #freeProElementsTel>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            });
        }


        function addFreeProTel(ProId){
            $.ajax({
                url: '{{ route("freeProd.store") }}',
                method: 'post',
                data: {
                    id: ProId,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#freeProElementsTel").load(location.href+" #freeProElementsTel>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val());
                }
            });
        }



        function changePriceFreeTel(inId, Res){
            if($('#'+inId).val() != '' && $('#'+inId).val() >= 0){
                $.ajax({
                    url: '{{ route("freeProd.changePriceFree") }}',
                    method: 'post',
                    data: {
                        newVal: $('#'+inId).val(),
                        toRest: Res,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {   
                        res = res.replace(/\s/g, '');                     
                        $("#freeProdPriceTel").load(location.href+" #freeProdPriceTel>*","");

                        $('#successFreeValueTelVal').html(res+" CHF");
                        $('#successFreeValueTel').show(200).delay(2500).hide(200);
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#oopsSomethingWrong').val());
                    }
                });
            }else{
                $('#errorFreeValueTel').show(200).delay(2500).hide(200);
            }
        }


        function changeTextFreeTel(inId, res){
            if($('#'+inId).val() != ''){
                $.ajax({
                    url: '{{ route("freeProd.changeTextFree") }}',
                    method: 'post',
                    data: {
                        newVal: $('#'+inId).val(),
                        toRest: res,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = res.replace(/\s/g, '');
                        $("#freeProdDescTel").load(location.href+" #freeProdDescTel>*","");

                        $('#successFreeValueTextTelVal').html(res);
                        $('#successFreeValueTextTel').show(200).delay(2500).hide(200);
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#oopsSomethingWrong').val());
                    }
                });
            }else{
                $('#errorFreeValueTextTel').show(200).delay(2500).hide(200);
            }
        }





        

        function saveExtraFreeTel(){
            var proName = $('#extraFreeNameTel').val();
            if(proName != ''){
                $.ajax({
                    url: '{{ route("freeProd.storeExtra") }}',
                    method: 'post',
                    data: {
                        name: proName,
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        $("#freeProElementsTel").load(location.href+" #freeProElementsTel>*","");
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#oopsSomethingWrong').val());
                    }
                });
            }else{
                $('#extraFreeAlert01').show(300).delay(3500).hide(300);
            }
        }






</script>
</section>