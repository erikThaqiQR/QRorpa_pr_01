<?php
    use App\kategori;
    use App\Produktet;
    use Carbon\Carbon;
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



    .phoneNrSelected{
        color: white;
        border: 1px solid rgb(39,190,175);
        border-radius: 10px;
        background-color: rgb(39,190,175);
    }
    .phoneNrNotSelected{
        color: rgb(39,190,175);
        border: 1px solid rgb(39,190,175);
        border-radius: 10px;

    }

</style>
<div class="mt-2 p-1 d-flex flex-wrap" >        
    
    @foreach (kategori::where('toRes',$theResId)->get() as $kat)

        <div style="width:100%;" class="mb-3">

            <div class="allKatFoto" id="KategoriFoto{{$kat->id}}Tel">
                <div style="cursor: pointer; position:relative; object-fit: cover;" >
                    <img style="border-radius:30px; width:100%; height:120px;" onclick="showProKatTel('{{$kat->id}}')" src="storage/kategoriaUpload/{{$kat->foto}}"alt="">

                    @if(strlen($kat->emri) > 20)
                        <div class="teksti d-flex" style="font-size:13px;  margin-bottom:19px;">          
                            <strong> <i style="color:white;" class="far fa-trash-alt" data-toggle="modal" data-target="#deleteCategoryConfirm{{$kat->id}}Tel"></i> 
                            <i style="color:white;" class="far fa-edit ml-2" data-toggle="modal" data-target="#editThisCategory{{$kat->id}}Tel"></i> 
                            <span onclick="showProKatTel('{{$kat->id}}')">{{$kat->emri}} 1</span> </strong>
                            <i class="fa fa-chevron-circle-down" aria-hidden="true" onclick="showProKatTel('{{$kat->id}}')"></i>
                        </div>
                    @else
                        <div class="teksti d-flex" style="font-size:16px; margin-bottom:16px;">          
                            <strong><i style="color:white; " class="far fa-trash-alt" data-toggle="modal" data-target="#deleteCategoryConfirm{{$kat->id}}Tel"></i> 
                            <i style="color:white;" class="far fa-edit ml-2" data-toggle="modal" data-target="#editThisCategory{{$kat->id}}Tel"></i>
                            <span onclick="showProKatTel('{{$kat->id}}')">{{$kat->emri}}</span> </strong>
                            <i class="fa fa-chevron-circle-down" aria-hidden="true" onclick="showProKatTel('{{$kat->id}}')"></i>
                        </div>
                    @endif
                    <input type="hidden" value="0" id="state{{$kat->id}}Tel">
                </div>
            </div>
            <div class="container prodsFoto" id="prodsKatFoto{{$kat->id}}Tel" style="display:none;">
                @foreach(Produktet::where('toRes', '=', Auth::user()->sFor)->where('kategoria','=',$kat->id)->get()->sortByDesc('visits') as $ketoProd)
                    <div class="row p-2" id="catProds{{$ketoProd->id}}Tel">
                        <div class="container-fluid">
                            <div class="row">
                                    
                                <div class="col-12 product-section">
                                    <div class="row">
                                            
                                        <di class="col-9">
                                            <h4 class="pull-right prod-name prodsFont color-text" style="font-weight:bold; font-size: 1.20rem ">
                                                {{$ketoProd->emri}} 
                                                @if($ketoProd->restrictPro != 0)
                                                    @if($ketoProd->restrictPro == 16)
                                                    <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R16.jpeg" alt="noImg"></span>
                                                    @elseif($ketoProd->restrictPro == 18)
                                                    <span class="ml-1" style="font-weight:normal; font-size:13px;"> <img style="width:20px;" src="/storage/icons/R18.jpeg" alt="noImg"></span>
                                                    @endif
                                                @endif
                                            </h4>
                                                <p style=" margin-top:-10px; font-size:13px;">{{substr($ketoProd->pershkrimi,0,35)}} 
                                                    @if(strlen($ketoProd->pershkrimi)>35)
                                                        <span onclick="showTypeMenuTel('{{$ketoProd->id}}')" class="hover-pointer" style="font-size:16px;"> . . .</span> 
                                                    @endif 
                                                </p>
                                            <h5 style="margin-top:-10px; margin-bottom:0px;"><span style="color:gray;">
                                                CHF
                                                </span> 
                                                    @if(Carbon::now()->format('H:i') >= '20:00' || Carbon::now()->format('H:i') <= '03:00')
                                                        @if($ketoProd->qmimi2 != 999999)
                                                            {{sprintf('%01.2f', $ketoProd->qmimi2)}}
                                                        @else
                                                            {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                        @endif
                                                    @else
                                                        @if($ketoProd->qmimi2 != 999999)
                                                            {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                            @if(Carbon::now()->format('H:i') > '19:40' && Carbon::now()->format('H:i') < '20:00')
                                                                <span class="ml-4" style="font-size:14px;">{{__('adminP.from8Pm')}} <span style="color:gray;">{{__('adminP.currencyShow')}}</span>
                                                                {{sprintf('%01.2f', $ketoProd->qmimi2)}} </span>
                                                            @endif
                                                        @else
                                                            {{sprintf('%01.2f', $ketoProd->qmimi)}} 
                                                        @endif
                                                    @endif
                                                    </h5>
                                            </di>
                                            <div class="col-3 add-plus-section">
                                                <!-- <button class="btn mt-2 noBorder" type="button" >
                                                    <i class="fa fa-plus fa-2x" aria-hidden="true" style="color:#27beaf"></i>
                                                </button> -->
                                                <button class="btn-block btn btn-outline-primary" data-toggle="modal" data-target="#editProduktModal{{$ketoProd->id}}Tel">{{__('adminP.toEdit')}}</button>
                                                <button class="btn-block btn btn-outline-danger" data-toggle="modal" data-target="#deleteProductConfirm{{$ketoProd->id}}Tel">{{__('adminP.extinguish')}}</button>
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteProductConfirm{{$ketoProd->id}}Tel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                            style="padding-top: 40%;">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body d-flex flex-wrap justify-content-between">
                                        <h3 style="color:rgb(39,190,175); width:100%;" class="text-center"><strong>{{__('adminP.deleteProduct')}}?</strong></h3>
                                        <button style="width:48%;" type="button" class="btn btn-success" data-dismiss="modal" onclick="deleteThisProductTel('{{$ketoProd->id}}')">{{__('adminP.yes')}}</button>
                                        <button style="width:48%;" type="button" class="btn btn-secondary" data-dismiss="modal">{{__('adminP.no')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
        </div>
        <div class="modal fade" id="deleteCategoryConfirm{{$kat->id}}Tel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="padding-top: 40%;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body d-flex flex-wrap justify-content-between">
                        <h3 style="color:rgb(39,190,175); width:100%;" class="text-center"><strong>{{__('adminP.deleteCategory')}}?</strong></h3>
                        <button style="width:48%;" type="button" class="btn btn-success" data-dismiss="modal" onclick="deleteThisCategoryTel('{{$kat->id}}')">{{__('adminP.yes')}}</button>
                        <button style="width:48%;" type="button" class="btn btn-secondary" data-dismiss="modal">{{__('adminP.no')}}</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>









































<script>
    function showProKatTel(kId) {
        if ($('#state'+kId+'Tel').val() == 0) {
            $('#prodsKatFoto'+kId+'Tel').show(100);
            $('#state'+kId+'Tel').val(1);

        } else {
            $('#prodsKatFoto'+kId+'Tel').hide(100);
            $('#state'+kId+'Tel').val(0)
        }
    }


    function deleteThisCategoryTel(catId){
        $.ajax({
			url: '{{ route("kategorite.destroyAdminP") }}',
			method: 'post',
			data: {
				id: catId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#showMenuTel").load(location.href+" #showMenuTel>*","");
				$("#entityButonsTel").load(location.href+" #entityButonsTel>*","");  
				$("#modalsDivTel").load(location.href+" #modalsDivTel>*","");  
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }


    function deleteThisProductTel(ProId){
        $.ajax({
            url: '{{ route("produktet.destroyAdminP") }}',
            method: 'post',
            data: {
                id: ProId,
                _token: '{{csrf_token()}}'
            },
            success: () => {
                $("#catProds"+ProId+'Tel').load(location.href+" #catProds"+ProId+"Tel>*","");  
                $("#entityButonsTel").load(location.href+" #entityButonsTel>*","");  
                $("#modalsDivTel").load(location.href+" #modalsDivTel>*","");  
            },
            error: (error) => {
                console.log(error);
                alert($('#pleaseUpdateAndTryAgain').val());
            }
        });
    }
</script>