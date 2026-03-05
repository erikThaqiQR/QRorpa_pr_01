<?php

use Illuminate\Support\Facades\Auth;
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Products']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('admWoMng.ordersStatisticsWaiter01')); 
        exit();
    }
    
    use App\kategori;
    use App\Produktet;
    use App\Restorant;

    $theResId = Auth::user()->sFor;
    $theRes = Restorant::find($theResId);
?>
<style>
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






@if(isset($_GET['cat']))
    @php $theCat = $_GET['cat']; @endphp
    <hr>
    <div class="d-flex justify-content-between" style="width:80%; margin-left:10%;">
        <a href="{{route('admWoMng.adminWoContentMngOrderWaiter')}}" style="color: rgb(39,190,175); width:10%; font-size:22px;"><strong> < {{__('adminP.back')}}</strong></a>
        <h2 class="text-center" style="color: rgb(39,190,175); width:45%;"><strong>{{__('adminP.category')}}: {{kategori::find($_GET['cat'])->emri}}</strong></h2>
        @if(kategori::find($_GET['cat'])->sortingType == 1)
            <button style="width:18%; font-weight:bold;" class="btn btn-success"><i class="far fa-eye mr-4"></i>{{__('adminP.sortViews')}}</button>
            <button style="width:18%; font-weight:bold;" class="btn btn-outline-success" onclick="setNewSortCat('{{$theCat}}',2)">
                <i class="fas fa-list-ol mr-4"></i> {{__('adminP.manualSorting')}}
            </button>
        @else
            <button style="width:18%; font-weight:bold;" class="btn btn-outline-success" onclick="setNewSortCat('{{$theCat}}',1)">
                <i class="far fa-eye mr-4"></i>{{__('adminP.sortViews')}}
            </button>
            <button style="width:18%; font-weight:bold;" class="btn btn-success"><i class="fas fa-list-ol mr-4"></i> {{__('adminP.manualSorting')}}</button>
        @endif
    </div>

    <script>
        function setNewSortCat(catId, so){
            $.ajax({
                url: '{{ route("dash.catNewSorting") }}',
                method: 'post',
                data: {
                    catId: catId,
                    newSort: so,
                    _token: '{{csrf_token()}}'
                },
                success: () => {location.reload();},
                error: (error) => { console.log(error); alert($('#pleaseUpdateAndTryAgain').val()); }
            });
        }
    </script>























    @if(kategori::find($_GET['cat'])->sortingType == 2)

        <div class="row mt-5">
            <div class="col-md-10 offset-md-1">

                <h3 class="text-center mb-4">{{__('adminP.dragDropProducts')}} </h3>

                <table id="table" class="table table-bordered">
                <thead>
                    <tr>
                    <th width="30px">#</th>
                    <th>{{__('adminP.positions')}}</th>
                    <th>{{__('adminP.name')}}</th>
                    <th>{{__('adminP.description')}}</th>
                    </tr>
                </thead>
                <tbody id="tablecontentsProductList">
                    <?php
                        $numberOfProductsOfCat = Produktet::where('kategoria',$_GET['cat'])->get()->count();
                    ?>
                    <input type="hidden" id="numberOfProductsOfCat" value="{{$numberOfProductsOfCat}}" >

                    @foreach(Produktet::where('kategoria',$_GET['cat'])->get()->sortBy('position') as $post)
                        <tr class="row1" data-toggle="modal" data-target="#setProdsPosition{{$post->id}}">
                            <td class="pl-3"><i class="fa fa-sort"></i> </td>
                            <td><strong>{{$post->position}} #</strong></td>
                            <td>{{ $post->emri }}</td>
                            <td>{{ $post->pershkrimi }}</td>
                        </tr>


                             <!-- Set possition Modal -->
                             <div class="modal fade" id="setProdsPosition{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:10% ;">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="d-flex flex-wrap">
                                                <h3 style="width: 100%;" class="text-center mb-2"><strong>{{$post->emri}}</strong></h3>
                                                <p style="width: 40%;"></p>
                                                <input style="width: 20%;" type="number" step="1" min="1" class="form-control text-center" id="changePositionProd{{$post->id}}" value="{{$post->position}}">
                                                <p style="width: 40%;"></p>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <button style="width:48%;" type="button" class="btn btn-secondary" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                                                <button style="width:48%;" type="button" class="btn btn-success" onclick="saveNewProdPos('{{$post->id}}','{{$theCat}}')">{{__('adminP.saveOnComputer')}}</button>
                                            </div>

                                            <div id="setProdsPositionError001{{$post->id}}" style="display: none;" class="alert alert-danger text-center p-2 mt-2">
                                                {{__('adminP.newPositionInvalid')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </tbody>                  
                </table>
            </div>
        </div>


        <script>
            function saveNewProdPos(proid,cat){
                var maxProdNr = parseInt($('#numberOfProductsOfCat').val());
                var newProdPozOriginal = $('#changePositionProd'+proid).val();
                var newProdPoz = parseInt($('#changePositionProd'+proid).val());
                if(newProdPoz > maxProdNr ||  newProdPoz <= 0 || !(Math.floor(newProdPozOriginal) == newProdPozOriginal && $.isNumeric(newProdPozOriginal))){
                    $('#setProdsPositionError001'+proid).show(200).delay(4000).hide(200);
                }else{
                    $.ajax({
                        url: '{{ route("dash.prodUpdateTheOrderTel") }}',
                        method: 'post',
                        data: {
                            id: proid,
                            catId: cat,
                            newPoz: newProdPoz,
                            _token: '{{csrf_token()}}'
                        },
                        success: (res) => {
                            res = res.replace(/\s/g, '');
                            if(res != 'yes'){
                                location.reload();
                                // console.log(res);
                            }
                        },
                        error: (error) => {
                            console.log(error);
                            alert($('#pleaseUpdateAndTryAgain').val());
                        }
                    });
                }
            }
        </script>

        <script type="text/javascript">
        $(function () {
            // $("#table").DataTable({
            //     'iDisplayLength': 100
            // });

            // $( "#tablecontentsProductList" ).sortable({
            // items: "tr",
            // cursor: 'move',
            // opacity: 0.6,
            // update: function() {
            //     sendOrderToServer();
            // }
            // });

   

            function sendOrderToServer() {
                var order = [];
                var token = $('meta[name="csrf-token"]').attr('content');
                $('tr.row1').each(function(index,element) {
                var theId = $(this).attr('data-id');
                    order.push({
                        id: theId,
                        position: index+1
                    });
                });

            //   alert(order[1].id);

                $.ajax({
                    type: "POST",  
                    url: '{{ route("dash.proUpdateTheOrder") }}',
                        data: {
                        order: order,
                        _token: token
                    },
                    success: function(response) {
                        // location.reload();
                        $("#tablecontentsProductList").load(location.href+" #tablecontentsProductList>*","");
                    }
                });
            }
        });
        </script>



    @elseif(kategori::find($_GET['cat'])->sortingType == 1)
        <div class="row mt-5">
            <div class="col-md-10 offset-md-1">
                <table id="table2" class="table table-bordered">
                <thead>
                    <tr>
                    <th width="30px">#</th>
                    <th>{{__('adminP.visits')}}</th>
                    <th>{{__('adminP.name')}}</th>
                    <th>{{__('adminP.description')}}</th>
                    </tr>
                </thead>
                <tbody id="tablecontentsProductList2">
                    @foreach(Produktet::where('kategoria',$_GET['cat'])->get()->sortByDesc('visits') as $post)
                        <tr class="row1" data-id="{{ $post->id }}">
                        <td class="pl-3"><i class="fa fa-sort"></i> </td>
                        <td><strong>{{$post->visits}} <i class="fas fa-eye"></i></strong></td>
                        <td>{{ $post->emri }}</td>
                        <td>{{ $post->pershkrimi }}</td>
                        </tr>
                    @endforeach
                </tbody>                  
                </table>
            </div>
        </div>

    @endif
    

































@else


  

    <div class="p-3 d-flex justify-content-between">
        <h5 style="width:60%;">
            <a href="{{route('admWoMng.adminWoContentMngWaiter')}}" style="color: rgb(39,190,175); width:10%; font-size:22px;" class="mr-5"><strong> < {{__('adminP.back')}}</strong></a>
            <i class="far fa-eye"></i> <span style="color:rgb(39,190,175); font-weight:bold;">({{__('adminP.sortByClick')}})</span> / 
            <i class="fas fa-list-ol"></i> <span style="color:rgb(39,190,175); font-weight:bold;">({{__('adminP.manualSorting')}})</span>
        </h5>
        @if($theRes->sortingType == 1)
            <button style="width:18%;" class="btn btn-success">{{__('adminP.sortingByVisits')}}</button>
            <button style="width:18%;" class="btn btn-outline-success" onclick="setResSortingType('{{$theRes->id}}',2)">{{__('adminP.manualSorting')}}</button>
        @elseif($theRes->sortingType == 2)
            <button style="width:18%;" class="btn btn-outline-success" onclick="setResSortingType('{{$theRes->id}}',1)">{{__('adminP.sortingByVisits')}}</button>
            <button style="width:18%;" class="btn btn-success">{{__('adminP.manualSorting')}}</button>
        @endif

      <!-- reset ordering -->
    </div>
    <script>
        function setResSortingType(resId, newSor){
            $.ajax({
                url: '{{ route("dash.resNewSorting") }}',
                method: 'post',
                data: {
                    resId: resId,
                    newSort: newSor,
                    _token: '{{csrf_token()}}'
                },
                success: () => {location.reload();},
                error: (error) => { console.log(error); alert($('#pleaseUpdateAndTryAgain').val()); }
            });
        }
    </script>

    <div class="d-flex flex-wrap justify-content-start p-3" id="kategoriListing" style="width: 50%; margin-left:25%;">
        @php
            if($theRes->sortingType == 1){ $showCatss = kategori::where('toRes',$theResId)->get()->sortByDesc('visits'); }
            else{ $showCatss = kategori::where('toRes',$theResId)->get()->sortBy('position'); }

            $numberOfCategories = kategori::where('toRes',$theResId)->get()->count();
        @endphp
        <input type="hidden" id="numberOfCategories" value="{{$numberOfCategories}}" >

        @foreach ( $showCatss as $kat)

        <div  style="width:100%;" class="mb-3 d-flex flex-wrap categoriesToOrder" data-id="{{ $kat->id }}">
            @if($theRes->sortingType == 1)
                <p style="width: 10%; font-size:23px; font-weight:bold; color:rgb(72,81,87);" class="pt-5">
                    {{ $kat->visits }} <i class="fas fa-eye"></i>
                </p>
            @elseif($theRes->sortingType == 2)
                <p style="width: 10%; font-size:23px; font-weight:bold; color:rgb(72,81,87);" class="pt-2">
                    <span style=" font-size:10px; color:rgb(72,81,87);" class="btn btn-outline-info" data-toggle="modal" data-target="#setCatsPosition{{$kat->id}}">
                        <i class="fas fa-2x fa-sort"></i>
                    </span>
                    <br>{{ $kat->position }} #
                </p>
            @endif
            <a href="{{route('admWoMng.adminWoContentMngOrderWaiter',['cat'=> $kat->id])}}" style="width: 90%;" class="allKatFoto" id="KategoriFoto{{$kat->id}}">
                <div style="cursor: pointer; position:relative; object-fit: cover;" >
                    <img style="border-radius:30px; width:100%; height:120px;" src="../storage/kategoriaUpload/{{$kat->foto}}"alt="">

                    @if(strlen($kat->emri) > 20)
                        <div class="teksti d-flex" style="font-size:20px;  margin-bottom:13px;">  
                            @if($kat->sortingType == 1)        
                                <strong><span> <i class="far fa-eye"></i> {{$kat->emri}} ({{Produktet::where('kategoria',$kat->id)->count()}})</span></strong>
                            @else
                                <strong><span> <i class="fas fa-list-ol"></i> {{$kat->emri}} ({{Produktet::where('kategoria',$kat->id)->count()}})</span></strong>
                            @endif
                        </div>
                    @else
                        <div class="teksti d-flex" >     
                            @if($kat->sortingType == 1)      
                                <strong><span> <i class="far fa-eye"></i> {{$kat->emri}} ({{Produktet::where('kategoria',$kat->id)->count()}})</span> </strong>
                            @else
                                <strong><span> <i class="fas fa-list-ol"></i>{{$kat->emri}} ({{Produktet::where('kategoria',$kat->id)->count()}})</span> </strong>
                            @endif
                        </div>
                    @endif
                    <input type="hidden" value="0" id="state{{$kat->id}}">
                </div>
            </a>
    </div>
        <!-- Category Position Modal -->
        <div class="modal fade" id="setCatsPosition{{$kat->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-top:10% ;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex flex-wrap">
                            <h3 style="width: 100%;" class="text-center mb-2"><strong>{{$kat->emri}}</strong></h3>
                            <p style="width: 40%;"></p>
                            <input style="width: 20%;" type="number" step="1" min="1" class="form-control text-center" id="changePositionCat{{$kat->id}}" value="{{$kat->position}}">
                            <p style="width: 40%;"></p>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-2">
                            <button style="width:48%;" type="button" class="btn btn-secondary" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                            <button style="width:48%;" type="button" class="btn btn-success" onclick="saveNewCatPos('{{$kat->id}}')">{{__('adminP.saveOnComputer')}}</button>
                        </div>

                        <div id="setCatsPositionError001{{$kat->id}}" style="display: none;" class="alert alert-danger text-center p-2 mt-2">
                            {{__('adminP.newPositionInvalid')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <script>
        function saveNewCatPos(catid){
            var maxCatNr = parseInt($('#numberOfCategories').val());
            var newCatPozOriginal = $('#changePositionCat'+catid).val();
            var newCatPoz = parseInt($('#changePositionCat'+catid).val());
            if(newCatPoz > maxCatNr ||  newCatPoz <= 0 || !(Math.floor(newCatPozOriginal) == newCatPozOriginal && $.isNumeric(newCatPozOriginal))){
                $('#setCatsPositionError001'+catid).show(200).delay(4000).hide(200);
            }else{
                $.ajax({
                    url: '{{ route("dash.catUpdateTheOrderTel") }}',
                    method: 'post',
                    data: {
                        id: catid,
                        newPoz: newCatPoz,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                        res = res.replace(/\s/g, '');
                        if(res != 'yes'){
                            location.reload();
                            // console.log(res);
                        }
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }
        }
    </script>

    @if($theRes->sortingType == 2)  
    <script>

        $(function () {
            // $( "#kategoriListing" ).sortable({
            //     items: "a",
            //     cursor: 'move',
            //     opacity: 0.6,
            //     update: function() {
            //         sendOrderToServerCategory();
            //     }
            // });

            function sendOrderToServerCategory() {
                var order = [];
                var token = $('meta[name="csrf-token"]').attr('content');
                $('a.categoriesToOrder').each(function(index,element) {
                    var theId = $(this).attr('data-id');
                    order.push({
                        id: theId,
                        position: index+1
                    });
                    // console.log(theId+'=='+(index+1));
                });

                $.ajax({
                    type: "POST",  
                    url: '{{ route("dash.catUpdateTheOrder") }}',
                        data: {
                        order: order,
                        _token: token
                    },
                    success: function(response) {
                        // location.reload();
                        $("#kategoriListing").load(location.href+" #kategoriListing>*","");
                    }
                });

           
            }
        });
    </script>
    @endif




@endif