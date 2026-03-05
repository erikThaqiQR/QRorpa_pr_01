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
    <div class="d-flex flex-wrap justify-content-between p-3" style="width:100%;">

        <a href="{{route('admWoMng.adminWoContentMngOrderWaiter')}}" style="color: rgb(39,190,175); width:55%; font-size:17px;"><strong> < {{__('adminP.back')}}</strong></a>
        <h3 class="text-center" style="color: rgb(39,190,175); width:45%;"><strong>{{kategori::find($_GET['cat'])->emri}}</strong></h3>

        @if(kategori::find($_GET['cat'])->sortingType == 1)
            <button style="width:49%; font-weight:bold;" class="btn btn-success mt-2"><i class="far fa-eye mr-4"></i>{{__('adminP.afterClicks')}}</button>
            <button style="width:49%; font-weight:bold;" class="btn btn-outline-success mt-2" onclick="setNewSortCatTel('{{$theCat}}',2)">
                <i class="fas fa-list-ol mr-4"></i> {{__('adminP.manual')}}
            </button>
        @else
            <button style="width:49%; font-weight:bold;" class="btn btn-outline-success mt-2" onclick="setNewSortCatTel('{{$theCat}}',1)">
                <i class="far fa-eye mr-4"></i>{{__('adminP.afterClicks')}}
            </button>
            <button style="width:49%; font-weight:bold;" class="btn btn-success mt-2"><i class="fas fa-list-ol mr-4"></i> {{__('adminP.manual')}} </button>
        @endif
    </div>

    <script>
        function setNewSortCatTel(catId, so){
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

        <div class="row mt-2" style="width:100%">
            <div class="col-md-10 offset-md-1">
                <!-- <h3 class="text-center mb-4">Ziehen Sie Produkte per Drag-and-Drop, um ihre Sortiernummer zu ändern </h3> -->
                <table id="table" class="table table-bordered">
                <thead>
                    <tr>
                    <th width="30px">#</th>
                    <th>{{__('adminP.position')}}</th>
                    <th>{{__('adminP.name')}}</th>
                    <th>{{__('adminP.description')}}</th>
                    </tr>
                </thead>
                <tbody id="tablecontentsProductListTel">
                    <?php
                        $numberOfProductsOfCatTel = Produktet::where('kategoria',$_GET['cat'])->get()->count();
                    ?>
                    <input type="hidden" id="numberOfProductsOfCatTel" value="{{$numberOfProductsOfCatTel}}" >


                    @foreach(Produktet::where('kategoria',$_GET['cat'])->get()->sortBy('position') as $post)
                        <tr class="row1Tel" data-toggle="modal" data-target="#setProdsPositionTel{{$post->id}}">
                            <td class="pl-3"><i class="fa fa-sort"></i> </td>
                            <td><strong>{{$post->position}} #</strong></td>
                            <td>{{ $post->emri }}</td>
                            <td>{{ $post->pershkrimi }}</td>
                        </tr>


                            <!-- Set possition Modal -->
                            <div class="modal fade" id="setProdsPositionTel{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding-top:30% ;">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="d-flex flex-wrap">
                                                <h3 style="width: 100%;" class="text-center mb-2"><strong>{{$post->emri}}</strong></h3>
                                                <p style="width: 40%;"></p>
                                                <input style="width: 20%;" type="number" step="1" min="1" class="form-control text-center" id="changePositionProdTel{{$post->id}}" value="{{$post->position}}">
                                                <p style="width: 40%;"></p>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <button style="width:48%;" type="button" class="btn btn-secondary" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                                                <button style="width:48%;" type="button" class="btn btn-success" onclick="saveNewProdPosTel('{{$post->id}}')">{{__('adminP.saveOnComputer')}}</button>
                                            </div>

                                            <div id="setProdsPositionError001Tel{{$post->id}}" style="display: none;" class="alert alert-danger text-center p-2 mt-2">
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

    


        <script type="text/javascript">
            function saveNewProdPosTel(proid){
                var maxProdNrTel = parseInt($('#numberOfProductsOfCatTel').val());
                var newProdPozOriginalTel = $('#changePositionProdTel'+proid).val();
                var newProdPozTel = parseInt($('#changePositionProdTel'+proid).val());
                if(newProdPozTel > maxProdNrTel ||  newProdPozTel <= 0 || !(Math.floor(newProdPozOriginalTel) == newProdPozOriginalTel && $.isNumeric(newProdPozOriginalTel))){
                    $('#setProdsPositionError001'+proid).show(200).delay(4000).hide(200);
                }else{
                    $.ajax({
                        url: '{{ route("dash.prodUpdateTheOrderTel") }}',
                        method: 'post',
                        data: {
                            id: proid,
                            newPoz: $('#changePositionProdTel'+proid).val(),
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



    @elseif(kategori::find($_GET['cat'])->sortingType == 1)
        <div class="row mt-5" style="width:100%">
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
    
































<!-- No category selected -->
@else


    <div class="p-3 d-flex flex-wrap justify-content-between">
        <h3 style="width:100%;">
            <a href="{{route('admWoMng.adminWoContentMngWaiter')}}" style="color: rgb(39,190,175); width:10%; font-size:19px;" class="mr-5"><strong> < {{__('adminP.back')}}</strong></a>
       
            <i class="far fa-eye"></i> <span style="color:rgb(39,190,175); font-weight:bold; font-size:12px;">({{__('adminP.afterClicks')}})</span> / 
            <i class="fas fa-list-ol"></i> <span style="color:rgb(39,190,175); font-weight:bold; font-size:12px;">({{__('adminP.manual')}})</span>
        </h3>
        @if($theRes->sortingType == 1)
            <button style="width:49%;" class="btn btn-success mt-2"><i class="far fa-eye mr-4"></i>{{__('adminP.afterClicks')}}</button>
            <button style="width:49%;" class="btn btn-outline-success mt-2" onclick="setResSortingTypeTel('{{$theRes->id}}',2)"><i class="fas fa-list-ol mr-4"></i>{{__('adminP.manual')}}</button>
        @elseif($theRes->sortingType == 2)
            <button style="width:49%;" class="btn btn-outline-success mt-2" onclick="setResSortingTypeTel('{{$theRes->id}}',1)"><i class="far fa-eye mr-4"></i>{{__('adminP.afterClicks')}}</button>
            <button style="width:49%;" class="btn btn-success mt-2"><i class="fas fa-list-ol mr-4"></i>{{__('adminP.manual')}}</button>
        @endif
    </div>
    <script>
        function setResSortingTypeTel(resId, newSor){
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

    <div class="d-flex flex-wrap justify-content-start p-3" id="kategoriListingTel" style="width: 100%;">
        @php
            if($theRes->sortingType == 1){ $showCatss = kategori::where('toRes',$theResId)->get()->sortByDesc('visits'); }
            else{ $showCatss = kategori::where('toRes',$theResId)->get()->sortBy('position'); }

            $numberOfCategoriesTel = kategori::where('toRes',$theResId)->get()->count();
        @endphp
        <input type="hidden" id="numberOfCategoriesTel" value="{{$numberOfCategoriesTel}}" >

        @foreach ( $showCatss as $kat)

        <div style="width:100%;" class="mb-3 d-flex flex-wrap justify-content-between categoriesToOrderTel" data-id="{{ $kat->id }}">
            @if($theRes->sortingType == 1)
                <p style="width: 10%; font-size:23px; font-weight:bold; color:rgb(72,81,87);" class="pt-5">
                    {{ $kat->visits }} <i class="fas fa-eye"></i>
                </p>
            @elseif($theRes->sortingType == 2)
           
                <p style="width: 10%; font-size:23px; font-weight:bold; color:rgb(72,81,87);" class="pt-2">
                    <span style=" font-size:10px; color:rgb(72,81,87);" class="btn btn-outline-info" data-toggle="modal" data-target="#setCatsPositionTel{{$kat->id}}">
                        <i class="fas fa-2x fa-sort"></i>
                    </span>
                    <br> {{ $kat->position }} #
                </p>
            @endif
            <a href="{{route('admWoMng.adminWoContentMngOrderWaiter',['cat'=> $kat->id])}}" style="width: 85%;" class="allKatFoto" id="KategoriFoto{{$kat->id}}Tel">
                <div style="cursor: pointer; position:relative; object-fit: cover;" >
                    <img style="border-radius:30px; width:100%; height:120px;" src="../storage/kategoriaUpload/{{$kat->foto}}"alt="">

                    @if(strlen($kat->emri) > 20)
                        <div class="teksti d-flex" style="font-size:13px;  margin-bottom:19px;">  
                            @if($kat->sortingType == 1)        
                                <strong><span> <i class="far fa-eye"></i> {{$kat->emri}} ({{Produktet::where('kategoria',$kat->id)->count()}})</span></strong>
                            @else
                                <strong><span> <i class="fas fa-list-ol"></i> {{$kat->emri}} ({{Produktet::where('kategoria',$kat->id)->count()}})</span></strong>
                            @endif
                        </div>
                    @else
                        <div class="teksti d-flex" style="font-size:16px;  margin-bottom:16px;" >     
                            @if($kat->sortingType == 1)      
                                <strong><span> <i class="far fa-eye"></i> {{$kat->emri}} ({{Produktet::where('kategoria',$kat->id)->count()}})</span> </strong>
                            @else
                                <strong><span> <i class="fas fa-list-ol"></i>{{$kat->emri}} ({{Produktet::where('kategoria',$kat->id)->count()}})</span> </strong>
                            @endif
                        </div>
                    @endif
                    <input type="hidden" value="0" id="state{{$kat->id}}Tel">
                </div>
            </a>
        </div>

        <!-- Category Position Modal -->
        <div class="modal fade" id="setCatsPositionTel{{$kat->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding-top:30% ;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex flex-wrap">
                            <h3 style="width: 100%;" class="text-center mb-2"><strong>{{$kat->emri}}</strong></h3>
                            <p style="width: 40%;"></p>
                            <input style="width: 20%;" type="number" step="1" min="1" class="form-control text-center" id="changePositionCatTel{{$kat->id}}" value="{{$kat->position}}">
                            <p style="width: 40%;"></p>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button style="width:48%;" type="button" class="btn btn-secondary" data-dismiss="modal">{{__('adminP.conclude')}}</button>
                            <button style="width:48%;" type="button" class="btn btn-success" onclick="saveNewCatPosTel('{{$kat->id}}')">{{__('adminP.saveOnComputer')}}</button>
                        </div>
                        <div id="setCatsPositionError001Tel{{$kat->id}}" style="display: none;" class="alert alert-danger text-center p-2 mt-2">
                            {{__('adminP.newPositionInvalid')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>






    @if($theRes->sortingType == 2)  
    <script>
        function saveNewCatPosTel(catid){
            var maxCatNrTel = parseInt($('#numberOfCategoriesTel').val());
            var newCatPozOriginalTel = $('#changePositionCatTel'+catid).val();
            var newCatPozTel = parseInt($('#changePositionCatTel'+catid).val());
            if(newCatPozTel > maxCatNrTel ||  newCatPozTel <= 0 || !(Math.floor(newCatPozOriginalTel) == newCatPozOriginalTel && $.isNumeric(newCatPozOriginalTel))){
                $('#setCatsPositionError001Tel'+catid).show(200).delay(4000).hide(200);
            }else{
                $.ajax({
                    url: '{{ route("dash.catUpdateTheOrderTel") }}',
                    method: 'post',
                    data: {
                        id: catid,
                        newPoz: newCatPozTel,
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
    @endif




@endif