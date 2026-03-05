<?php
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','16+/18+']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Produktet;
    use App\kategori;
?>

<style>
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
            margin-top:-50px;
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

<section class="pl-2 pr-2 pb-5">

    <div style="width:100%;" id="allRestriktProdsTel">
        <h4 style="color:rgb(39,195,175);" class="p-2">{{__('adminP.ageRestrictionOnProducts')}}</h4>
        <!-- <input type="text" id="searchProdsREC" onkeyup="searchProdsFuncREC()" placeholder="Search for products.." title="write a products name"> -->

        @foreach(kategori::where('toRes',  Auth::user()->sFor)->get() as $kat)
            
        

        <?php echo ' <div style="cursor: pointer; position:relative; object-fit: cover;" class="col12 p-1 text-center"
          onclick="showProKatTel('.$kat->id.')">'?>
            <img style="border-radius:30px; width:100%; height:120px;" src="storage/kategoriaUpload/{{$kat->foto}}"
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

            <ul class="ProdLists" id="prodsOfTel{{$kat->id}}" style="display:none;">
                @foreach(Produktet::where('toRes', Auth::user()->sFor)->where('kategoria', $kat->id)->get() as $allPro)
                    <li id="prodListingTel{{$allPro->id}}">
                        <div class="d-flex flex-wrap ">
                            <span style="width:100%;" class="ml-3 elementViewRest"> {{$allPro->emri}} <span style="opacity:0.6;">( {{kategori::find($allPro->kategoria)->emri}} )</span></span>
                            <span style="width:100%;">
                                @if($allPro->restrictPro == 16)
                                    <button class="btn btn-dark" style="width:32%;"> <strong>16</strong> </button>
                                @else
                                    <button onclick="setRestrict16Tel('{{$allPro->id}}')" class="btn btn-outline-dark" style="width:32%;"> <strong>16</strong> </button>
                                @endif
                                @if($allPro->restrictPro == 18)
                                    <button class="btn btn-dark" style="width:32%;"> <strong>18</strong> </button>
                                @else
                                    <button onclick="setRestrict18Tel('{{$allPro->id}}')" class="btn btn-outline-dark" style="width:32%;"> <strong>18</strong> </button>
                                @endif
                                @if($allPro->restrictPro == 0)
                                    <button class="btn btn-dark" style="width:32%;"> {{__('adminP.none')}} </button>
                                @else
                                    <button onclick="setRestrict0Tel('{{$allPro->id}}')" class="btn btn-outline-dark" style="width:32%;">{{__('adminP.none')}}  </button>
                                @endif
                            
                            </span>
                        </div>
                        
                    </li>
                        
                @endforeach
            </ul>


        @endforeach


    </div>










        <script>
               function searchProdsFuncREC() {
                    var input, filter, ul, li, a, i, txtValue;
                    input = document.getElementById("searchProdsREC");
                    filter = input.value.toUpperCase();
                    ul = document.getElementById("searchProdsULREC");
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

                function showProKatTel(kId){
                    // $('.ProdLists').hide();
                    if($('#prodsOfTel'+kId).is(":visible")){
                        $('#prodsOfTel'+kId).hide();
                    }else{
                        $('#prodsOfTel'+kId).show();
                    }
                

                }

                function setRestrict16Tel(proId){
                    $.ajax({
                        url: '{{ route("restrictProd.T16") }}',
                        method: 'post',
                        data: {
                            id: proId,
                            _token: '{{csrf_token()}}'
                        },
                        success: () => {
                            $("#prodListingTel"+proId).load(location.href+" #prodListingTel"+proId+">*","");
                        },
                        error: (error) => {
                            console.log(error);
                            alert($('#oopsSomethingWrong').val())
                        }
                    });
                }
                function setRestrict18Tel(proId){
                    $.ajax({
                        url: '{{ route("restrictProd.T18") }}',
                        method: 'post',
                        data: {
                            id: proId,
                            _token: '{{csrf_token()}}'
                        },
                        success: () => {
                            $("#prodListingTel"+proId).load(location.href+" #prodListingTel"+proId+">*","");
                        },
                        error: (error) => {
                            console.log(error);
                            alert($('#oopsSomethingWrong').val())
                        }
                    });
                }
                function setRestrict0Tel(proId){
                    $.ajax({
                        url: '{{ route("restrictProd.T0") }}',
                        method: 'post',
                        data: {
                            id: proId,
                            _token: '{{csrf_token()}}'
                        },
                        success: () => {
                            $("#prodListingTel"+proId).load(location.href+" #prodListingTel"+proId+">*","");
                        },
                        error: (error) => {
                            console.log(error);
                            alert($('#oopsSomethingWrong').val())
                        }
                    });
                }
        </script>
</section>