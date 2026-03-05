<?php
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Products']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.statistics')); 
        exit();
    }
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
    use App\Restorant;
    use App\Produktet;

    // $kat = kategori::all();
    // $thisKat = ekstra::all();
    // $types = LlojetPro::all();
    // $restaurant = Restorant::all()->sortByDesc('created_at');
    // $produktet = Produktet::all();

    $theResId = Auth::user()->sFor;
?>
<style>
        .direktiveBox{
            color:white;
            border-radius:10px;
            margin-top:35px;
            padding-top:50px;
            padding-bottom:50px;

            background-color:rgb(39,190,175);
        }
        .direktiveBox:hover{
            cursor: pointer;
        }

        .backBtn{
            opacity:0.5;
        }
        .backBtn:hover{
            opacity:0.95;
            cursor: pointer;
        }

        .ResShow{
            background-color:rgb(39,190,175);
            color:white;
            border-radius:20px;
        }
        .ResShow:hover{
            cursor: pointer;
        }

        .anchorMy{
            color: black;
            text-decoration: none;
        }
        .anchorMy:hover{
            color: rgb(39,190,175);
            text-decoration: none;
        }

        .point:hover{
            cursor: pointer;
        }

    </style>
<!-- <div class="container-fluid mb-4 " >
    <div class="row mt-4">
        <div class="col-12 text-center">
            <p style="font-size:45px;" class="color-qrorpa">"{{Restorant::find($theResId)->emri}}"</p>
            <p style="font-size:35px; margin-top:-40px;" class="color-qrorpa">Wählen Sie ein Kästchen aus, um fortzufahren ...</p>
        </div>
    </div>
</div> -->

@if(session('success'))
    <div class="alert alert-success text-center" style="font-weight:bold ;">{{session('success')}}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger text-center" style="font-weight:bold ;">{{session('error')}}</div>
@endif

<div class="container mb-4 mt-3" id="entityButonsTel">
    <div class="row">
        <div class="col-12 d-flex flex-wrap justify-content-between">
      
            <div style="width:49%; background-color:rgb(39,190,175); border-radius:15px; color:white; " class="p-3 text-center mb-2 point" data-toggle="modal" data-target="#addCatModalTel">
                <h5><strong>{{__('adminP.category')}}</strong> ({{kategori::where('toRes',$theResId)->count()}})</h5>
            </div>
          
            <div style=" width:49%; background-color:rgb(39,190,175); border-radius:15px; color:white;" class="p-3 text-center mb-2 point" data-toggle="modal" data-target="#addProduktModalTel">
                <h5><strong>{{__('adminP.products')}}</strong> ({{Produktet::where('toRes',$theResId)->count()}})</h5>
            </div>
      
            <div style="width:49%; background-color:rgb(39,190,175); border-radius:15px; color:white;" class="p-3 text-center mb-2 point" data-toggle="modal" data-target="#addExtModalTel">
                <h5><strong>{{__('adminP.extra')}}</strong> ({{ekstra::where('toRes',$theResId)->count()}})</h5>
            </div>
         
            <div style="width:49%; background-color:rgb(39,190,175); border-radius:15px; color:white;" class="p-3 text-center mb-2 point" data-toggle="modal" data-target="#addTypeModalTel">
                <h5><strong>{{__('adminP.types')}}</strong> ({{LlojetPro::where('toRes',$theResId)->count()}})</h5>
            </div>
        
        </div>
    </div>
 
</div>
<div  class="d-flex flex-wrap justify-content-between pl-2 pr-2">
    <a href="{{route('dash.indexConMngRenditja')}}" style="width:100%;  background-color:rgb(39,190,175); border-radius:15px; color:white; font-size:17px;" class="btn p-2">
        <strong>{{__('adminP.changeOrderForCategoriesProducts')}}</strong>
    </a>
    <!-- <button onclick="chiphersLozing008()" style="width:100%;  background-color:rgb(39,190,175); border-radius:15px; color:white; font-size:17px;" class="btn p-2 mt-3">
        Refresh Sorting By Views! 
    </button> -->
    <div style="width:100%;  border-radius:15px; color:rgb(39,190,175); font-size:17px;" class="btn mt-2 p-2 d-flex flex-wrap" id="newTimeResDivTel">
        <strong style="width:100%"> <i class="far fa-clock"></i> {{__('adminP.secondPriceActivationTime')}} <ins>{{Restorant::find($theResId)->secondPriceTime}}</ins></strong>
        <div style="width:100%" class="d-flex justify-content-between mt-2">
            <input style="width:49%;" type="time" class="form-control text-center" placeholder="--:--" name="newTimeResTel" id="newTimeResTel" value="{{Restorant::find($theResId)->secondPriceTime}}">
            <button style="width:49%;" class="btn btn-outline-success" onclick="saveNewResTimeTel('{{$theResId}}')">{{__('adminP.saveOnComputer')}}</button>
        </div>
    </div>
</div>


<div id="showMenuTel">
    @include('adminPanel.manageContentTel.showMenu')
</div>

<div id="modalsDivTel">
 @include('adminPanel.manageContentTel.addModals')
 @include('adminPanel.manageContentTel.editModals')
 @include('adminPanel.manageContentTel.addETModals')
</div>







<script>
    function saveNewResTimeTel(resId){
        $.ajax({
			url: '{{ route("Res.setNewTimeForTheRes") }}',
			method: 'post',
			data: {
				resId: resId,
				newTime: $('#newTimeResTel').val(),
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#newTimeResDivTel").load(location.href+" #newTimeResDivTel>*","");
			},
			error: (error) => {
				console.log(error);
				alert($('#pleaseUpdateAndTryAgain').val());
			}
		});
    }


</script>