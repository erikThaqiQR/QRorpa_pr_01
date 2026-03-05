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
    #searchProds {
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
</style>




<!-- The Modal -->
<div class="modal" id="addExtraFreeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title color-qrorpa">
            {{__('adminP.addAdditionalProduct')}}
        </h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

        <div class="mt-2 mb-2 alert alert-danger" style="display:none;" id="extraFreeAlert01">
            {{__('adminP.writeNameFirst')}}
        </div>

        <div class="form-group">
            <label for="name">{{__('adminP.productName')}}:</label>
            <input type="text" class="form-control" id="extraFreeName">
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer d-flex justify-content-between">

        <button type="button" style="width:48%;" class="btn btn-outline-danger" data-dismiss="modal">{{__('adminP.close')}}</button>
        <button onclick="saveExtraFree()" type="button" style="width:48%;" class="btn btn-outline-success">{{__('adminP.save')}}</button>
      </div>

    </div>
  </div>
</div>


<script>
    function saveExtraFree(){
        var proName = $('#extraFreeName').val();
        if(proName != ''){
            $.ajax({
                url: '{{ route("freeProd.storeExtra") }}',
                method: 'post',
                data: {
                    name: proName,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#freeProElements").load(location.href+" #freeProElements>*","");
                    $('#addExtraFreeModal').modal('hide');
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            });
        }else{
            $('#extraFreeAlert01').show(300).delay(3500).hide(300);
        }
    }
</script>








<section class="pl-2 pr-2 pb-5">

    <div class="d-flex justify-content-between flex-wrap" id="headFreePrice">
        <div style="width:80%;"  class="d-flex">
            <h3 style="color:rgb(39,195,175); width:100%;" class="pl-1 text-left">{{__('adminP.freeProductsForOrders')}} {{Restorant::find(Auth::user()->sFor)->priceFree}} {{__('adminP.currencyShow')}}</h3>
        </div>
        <div style="width:20%;" class="d-flex justify-content-center text-right pr-4">
            <input style="width:50%; border:none; border-bottom:1px solid gray; font-size:21px;" class="text-center" type="number" id="newValueFree" name="newValueFree">
            <button style="width:50%;" onclick="changePriceFree('newValueFree','{{Auth::user()->sFor}}')" class="btn text-left"><strong>{{__('adminP.changeValue')}}</strong></button>
        </div>

        <div style="width:62%;"  class="d-flex mt-2">
            <h5 style="color:rgb(39,195,175); width:100%;" class="pl-1 text-left">
                {{__('adminP.freeProductCartDescription')}} : 
                @if(Restorant::find(Auth::user()->sFor)->textFree != 'empty')
                   <u> {{Restorant::find(Auth::user()->sFor)->textFree}} </u>
                @endif
            </h5>
        </div>
        <div style="width:38%;" class="d-flex justify-content-center text-right pr-4 mt-2">
            <!-- Beschreibung im Warenkorb -->
            <input style="width:75%; border:none; border-bottom:1px solid gray; font-size:21px;" class="text-center" type="text" id="newTextFree">
            <button style="width:25%;" class="btn text-left" onclick="changeTextFree('newTextFree','{{Auth::user()->sFor}}')"><strong> {{__('adminP.saveOnComputer')}}</strong></button>
        </div>
    
        
    </div>

    <div class="alert alert-danger text-center" style="display:none;" id="errorFreeValue">
        {{__('adminP.writeValidValue')}}
    </div>
    <div class="alert alert-danger text-center" style="display:none;" id="errorFreeValueText">
        {{__('adminP.writeValidText')}}
    </div>
  
    <hr>

    <div class="mb-2" id="freeProdsAllStatus">
        @if(Restorant::find(Auth::user()->sFor)->allowFree == 1)
            <button class="btn btn-block btn-outline-success" onclick="activeFree('0', '{{Auth::user()->sFor}}')" > <strong>{{__('adminP.active')}}</strong> </button>
        @else
            <button class="btn btn-block btn-outline-danger" onclick="activeFree('1', '{{Auth::user()->sFor}}')" > <strong>{{__('adminP.notActive')}}</strong> </button>
        @endif
    </div>
    


    <div class="d-flex justify-content-between" id="freeProElements">
        <div style="width:49.5%;">
            <h4 style="color:rgb(39,195,175);" class="p-2">{{__('adminP.freeProductsOfRestaurants')}}</h4>

            <button class="btn btn-block mb-2" style="border:1px solid lightgray; padding:15px;" data-toggle="modal" data-target="#addExtraFreeModal">
                + {{__('adminP.addAdditionalProduct')}}
            </button>

            <ul id="searchProdsFreeUL" class="ProdLists">
                @foreach(FreeProducts::where('toRes', Auth::user()->sFor)->get() as $allProFree)
                    @if($allProFree->prod_id != 0)
                    <li> <button onclick="removeFreePro('{{$allProFree->id}}')" class="btn btn-outline-danger">X</button>  <span class="ml-4">{{ Produktet::find($allProFree->prod_id)->emri}}</span></li>
                    @else
                    <li> <button onclick="removeFreePro('{{$allProFree->id}}')" class="btn btn-outline-danger">X</button>  <span class="ml-4">{{ $allProFree->nameExt}}</span></li>
                    @endif
                @endforeach
            </ul>
        </div>


        <!-- Border -->
        <div style="width:0.2%; border:1px solid gray;"></div>  
        


        <div style="width:49.5%;">
            <h4 style="color:rgb(39,195,175);" class="p-2">{{__('adminP.productsOfRestaurant')}}</h4>
            <input type="text" id="searchProds" onkeyup="searchProdsFunc()" placeholder="{{__('adminP.searchForProducts')}}.." title="{{__('adminP.writeProductsName')}}">

            <ul id="searchProdsUL" class="ProdLists">
                @foreach(Produktet::where('toRes', Auth::user()->sFor)->get() as $allPro)
                    @if(FreeProducts::where('prod_id', $allPro->id)->first() != null)
                    <li style="background-color:rgb(39,195,175); color:white;">
                    <button style="font-size:21px; color:white;" class="btn btn-outline-default">✓</button> 
                        <span class="ml-3"> {{$allPro->emri}} <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span></span></li>
                    @else
                    <li><button onclick="addFreePro('{{$allPro->id}}')" style="font-size:21px;" class="btn btn-outline-danger">+</button> 
                        <span class="ml-3"> {{$allPro->emri}} <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span></span></li>
                    @endif
                @endforeach
            </ul>
        </div>

    </div>









    <script>
        function searchProdsFunc() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById("searchProds");
            filter = input.value.toUpperCase();
            ul = document.getElementById("searchProdsUL");
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


        function removeFreePro(fProId){
            $.ajax({
                url: '{{ route("freeProd.destroy") }}',
                method: 'post',
                data: {
                    id: fProId,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    
       
                    $("#freeProElements").load(location.href+" #freeProElements>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            });
        }


        function addFreePro(ProId){
            $.ajax({
                url: '{{ route("freeProd.store") }}',
                method: 'post',
                data: {
                    id: ProId,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#freeProElements").load(location.href+" #freeProElements>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            });
        }

        function changePriceFree(inId, Res){
            if($('#'+inId).val() != '' && $('#'+inId).val() >= 0){
                $.ajax({
                    url: '{{ route("freeProd.changePriceFree") }}',
                    method: 'post',
                    data: {
                        newVal: $('#'+inId).val(),
                        toRest: Res,
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        $("#headFreePrice").load(location.href+" #headFreePrice>*","");
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#oopsSomethingWrong').val())
                    }
                });
            }else{
                $('#errorFreeValue').show(200).delay(2500).hide(200);
            }
        }
        
        
        function changeTextFree(inId ,res){
            if($('#'+inId).val() != ''){
                $.ajax({
                    url: '{{ route("freeProd.changeTextFree") }}',
                    method: 'post',
                    data: {
                        newVal: $('#'+inId).val(),
                        toRest: res,
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        $("#headFreePrice").load(location.href+" #headFreePrice>*","");
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#oopsSomethingWrong').val())
                    }
                });
            }else{
                $('#errorFreeValueText').show(200).delay(2500).hide(200);
            }
        }














        function activeFree(Val,Res){
            $.ajax({
                url: '{{ route("freeProd.activeFree") }}',
                method: 'post',
                data: {
                    newVal: Val,
                    resId: Res,
                    _token: '{{csrf_token()}}'
                },
                success: () => {
                    $("#freeProdsAllStatus").load(location.href+" #freeProdsAllStatus>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            });
        }
</script>
</section>