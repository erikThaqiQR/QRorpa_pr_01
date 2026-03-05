<?php
   	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Takeaway']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
       header("Location: ".route('dash.notAccessToPage')); 
       exit();
    }
    use App\kategori;
    use App\Takeaway;
    use App\Produktet;
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

</style>
                <div id="takeawaySortingAll">
                    <a href="{{route('takeaway.index')}}" style="width:100%;" class="btn btn-dark"><strong>{{__('adminP.bringAway')}}</strong></a>
    
                    @foreach(kategori::where('toRes',  Auth::user()->sFor)->get()->sortBy('positionTakeaway') as $kat)
                        @if(Takeaway::where('toRes', Auth::user()->sFor)->where('kategoria', $kat->id)->get()->count() > 0)
                            <div style="cursor: pointer; position:relative; object-fit: cover;" class="col12 p-1 text-center">
                                <img style="border-radius:30px; width:100%; height:120px;" src="storage/kategoriaUpload/{{$kat->foto}}" alt="" onclick="showProKatTel('{{$kat->id}}')">
                    
                                @if(strlen($kat->emri) > 20)
                                    <div class="teksti d-flex" style="font-size:20px;  margin-bottom:13px;">          
                                        <strong onclick="showProKatTel('{{$kat->id}}')">{{$kat->emri}} </strong>
                                        <!-- <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> -->
                                        <p data-toggle="modal" data-target="#changeTakeawayCatPositionTel{{$kat->id}}" class="pr-3"><i class="fas fa-sort"></i>{{$kat->positionTakeaway}}</p>
                                    </div>
                                @else
                                    <div class="teksti d-flex" >          
                                        <strong onclick="showProKatTel('{{$kat->id}}')">{{$kat->emri}} </strong>
                                        <!-- <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> -->
                                        <p data-toggle="modal" data-target="#changeTakeawayCatPositionTel{{$kat->id}}" class="pr-3"><i class="fas fa-sort"></i>{{$kat->positionTakeaway}} #</p>
                                    </div>
                                @endif
                                <input type="hidden" value="0" id="state{{$kat->id}}">
                            </div>


                                 <!-- Change Takeaway Category Position Modal -->
                                <div class="modal fade" id="changeTakeawayCatPositionTel{{$kat->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
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
                                                <button style="width:49%" class="btn btn-success" onclick="changeTakeawayCatPositionSaveTel('{{$kat->id}}')">{{__('adminP.saveOnComputer')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            








                
                            <ul class="ProdLists" id="prodsOfTATel{{$kat->id}}" style="display:none;">
                                @foreach(Takeaway::where('kategoria', $kat->id)->get()->sortBy('position') as $allTa)
                                    <li style="background-color:rgb(189, 246, 240)" class="d-flex">
                                        <p class="text-center" style="width: 12.5%;" data-toggle="modal" data-target="#changeTakeawayProdPositionTel{{$allTa->id}}"><strong><i class="fas fa-sort"></i></strong></p>
                                        <p class="text-center" style="width: 12.5%;"><strong>{{$allTa->position}} #</strong></p>
                                        <p style="width: 75%;"><strong>{{$allTa->emri}}</strong></p>
                                    </li>

                                    <!-- Change Takeaway Category Position Modal -->
                                    <div class="modal fade" id="changeTakeawayProdPositionTel{{$allTa->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                        data-backdrop="false"style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body d-flex flex-wrap justify-content-between">
                                                    <h3 class="pt-3" style="width: 85%;"><strong>{{$allTa->emri}}</strong></h3>
                                                    <button style="width: 15%; font-size:23px;" type="button" class="btn btn-default text-right" data-dismiss="modal"><strong>X</strong></button>

                                                    <hr style="width: 100%;">
                                                    <p style="width:49%; font-size:18px;" class="text-right pt-2"><strong>{{__('adminP.position')}} : </strong></p>        
                                                    <input class="form-control text-center mb-2" type="number" style="width:49%; border:none; border-bottom: 1px solid black; font-size:22px; font-weight:bold;" 
                                                        value="{{$allTa->position}}" id="changeTakeawayProdPositionVal{{$allTa->id}}">
                                                    
                                                    <button style="width:49%" class="btn btn-danger" data-dismiss="modal">{{__('adminP.abort')}}</button>
                                                    <button style="width:49%" class="btn btn-success" onclick="changeTakeawayProdPositionSaveTel('{{$allTa->id}}','{{$kat->id}}')">{{__('adminP.saveOnComputer')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                    <br><br>
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




    function changeTakeawayProdPositionSaveTel(prodId,catId){
        $.ajax({
            url: '{{ route("takeaway.changeProductOrder") }}',
            method: 'post',
            data: {
                prodId: prodId,
                newPoz: $('#changeTakeawayProdPositionVal'+prodId).val(),
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $('#changeTakeawayProdPositionTel'+prodId).modal('toggle')
                $("#prodsOfTATel"+catId).load(location.href+" #prodsOfTATel"+catId+">*","");
            },
            error: (error) => {
                console.log(error);
                alert($('#pleaseUpdateAndTryAgain').val());
            }
        });
    }



    function changeTakeawayCatPositionSaveTel(catId){
        $.ajax({
            url: '{{ route("takeaway.changeCategoryOrder") }}',
            method: 'post',
            data: {
                catId: catId,
                newPoz: $('#changeTakeawayCatPositionVal'+catId).val(),
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $('#changeTakeawayCatPositionTel'+catId).modal('toggle')
                $("#takeawaySortingAll").load(location.href+" #takeawaySortingAll>*","");
            },
            error: (error) => {
                console.log(error);
                alert($('#pleaseUpdateAndTryAgain').val());
            }
        });
    }

</script>