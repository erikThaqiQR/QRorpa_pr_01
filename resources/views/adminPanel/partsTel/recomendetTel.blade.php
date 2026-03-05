<?php
	use App\accessControllForAdmins;
    $checkAccess = accessControllForAdmins::where([['userId',Auth::user()->id],['accessDsc','Empfohlen']])->first();
    if($checkAccess == NULL || $checkAccess->accessValid == 0){ 
        header("Location: ".route('dash.notAccessToPage')); 
        exit();
    }
    use App\Restorant;
    use App\RecomendetProd;
    use App\Produktet;
    use App\PicLibrary;

    $thisRestaurantId = Auth::user()->sFor;
?>
<style>
img {
    object-fit: cover;
}
</style>
<hr>


@foreach(Produktet::where('toRes', '=', $thisRestaurantId)->get() as $prod)
    <input type="hidden" id="prodPriceTel{{$prod->id}}" value="{{$prod->qmimi}}">
@endforeach




<!-- addRecPro -->
<!-- <div class="modal fade" id="addRecPro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;"> -->
    <div class="modal fade" id="add" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:30px;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title color-qrorpa">{{__('adminP.addNewFeaturedProduct')}}</h4>
                <button type="button" class="close" onclick="resetAddModalRecTel()" data-dismiss="modal"><img width="35px"
                        src="https://img.icons8.com/ios/50/000000/xbox-x.png" /></button>
            </div>

            {{Form::open(['action' => 'RecomendetProdController@store', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}

            <!-- Modal body -->
            <div class="modal-body">




                <div class="form-group">
                    <label for="kat">{{__('adminP.chooseProduct')}}</label>
                    <select name="produkti" class="form-control select2" style="width:100%;" onchange="setNewPriceTel(this.value)">
                        <option class="p-2" value="0">{{__('adminP.choose')}}...</option>
                        <?php
                            foreach(Produktet::where('toRes', '=', $thisRestaurantId)->get() as $prod){
                                echo '  <option value="'.$prod->id.'"> '.$prod->emri.'<strong> >> </strong>'.$prod->pershkrimi.'  </option> ';
                            }
                        ?>
                    </select>
                </div>

                <div class="custom-file mb-3 mt-3 selPicPhase01Tel">
                    {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                    {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                </div>
                <button class="selPicPhase01Tel p-2" type="button" data-toggle="modal" data-target="#otherPictureTel"
                    style="width:100%; color:rgb(72, 81, 87); background-color:white; border:1px solid rgb(72, 81, 87);" >
                    {{__('adminP.chooseOneFromPlatform')}}
                </button>
                <div class="d-flex flex-wrap justify-content-between mt-3 mb-3" style="display:none !important;" id="selPicPhase02Tel">
                    <p style="width:70%; font-weight:bold;" class="text-center" id="selPicPhase02NameTel"></p>
                    <p style="width:30%; font-weight:bold;" class="text-center" id="selPicPhase02TitleTel"></p>
                    <button type="button" style="width:100%;" class="btn btn-outline-danger" onclick="resetAddModalRecTel()">{{__('adminP.resetToDefault')}}</button>             
                </div>
` 
         
                
                                <div class="form-group d-flex flex-wrap justify-content-between">
                                    <div style="width:40%;">
                                        {{ Form::label(__('adminP.priceCHF'), null , ['class' => 'control-label']) }}
                                        {{ Form::number('qmimiN', 0 ,
                                            ['class' => 'form-control', 'id' => 'newRecQmimiTel', 'step'=>'0.01', 'min' => '0']) }}
                                    </div>

                                    <div style="width:55%;">
                                        {{ Form::label(__('adminP.priceOptional'), null , ['class' => 'control-label']) }}
                                        {{ Form::number('qmimiN2', '' ,
                                            ['class' => 'form-control', 'id' => 'newRecQmimi2Tel', 'step'=>'0.01', 'min' => '0']) }}
                                    </div>

                                    <div style="width:100%;" class="mt-2">
                                        {{ Form::label(__('adminP.position'), null , ['class' => 'control-label']) }}
                                        {{ Form::number('pozita',count(RecomendetProd::where('toRes', '=', $thisRestaurantId)->get())+1,
                                            ['class' => 'form-control', 'step'=>'1', 'max' => '10' , 'min' => '1']) }}
                                    </div>
                                    
                                </div>



                {{ Form::hidden('toRes',$thisRestaurantId, ['class' => 'form-control']) }}

                {{ Form::hidden('photoFrom',1, ['class' => 'form-control' , 'id' => 'photoFromIDTel']) }}
                {{ Form::hidden('photo','', ['class' => 'form-control', 'id' => 'photoIDTel']) }}

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <div class="form-group">
                    {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'form-control btn btn-outline-primary']) }}
                </div>
            </div>

            {{Form::close() }}

        </div>
    </div>
</div>











    <!-- The Modal -->
    <div class="modal" id="otherPictureTel" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"
        style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header" style="background-color:rgb(39,190,175);">
                    <h4 class="modal-title" style="color:white; font-weight:bold;">{{__('adminP.chooseOneFromPlatform')}}</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">X</button>
                </div>

                <div class="form-group has-search mt-2" style="width:100%;">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input onkeyup="searchPicTel(this.value)" type="text" class="form-control" placeholder="{{__('adminP.search')}}">
                </div>

                <!-- Modal body -->
                <div class="modal-body d-flex flex-wrap justify-content-start" id="allLibPicsTel">
                    @foreach(PicLibrary::all()->sortByDesc('created_at') as $pl)
                        <div style="width:32%;  border:1px solid lightgray;" class="mr-1 selectPic" data-dismiss="modal" onclick="selectPicForRecTel('{{$pl->picLPhoto}}','{{$pl->picLTitle}}')">
                            <img style="width:100%; height:100px; border-radius:50%;" src="storage/PicLibrary/{{$pl->picLPhoto}}" alt="">
                            <div class="d-flex">
                                <p class="text-center" style="width:80%; font-size:17px;"><strong> {{$pl->picLTitle}} </strong></p>
                                <p class="text-center" style="width:20%; border-left:1px solid lightgray; border-bottom:1px solid lightgray;">{{$pl->picLExt}} </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>








    <script>
        $('.select2').select2();

        function selectPicForRecTel(pic,title){
            $('.selPicPhase01Tel').hide(200);
            $('#selPicPhase02Tel').css("display", "block");
            $('#selPicPhase02NameTel').html(pic);
            $('#selPicPhase02TitleTel').html("\" "+title+" \"");

            $('#photoFromIDTel').val('2');
            $('#photoIDTel').val(pic);
        }

        function resetAddModalRecTel(){
            $("#add").load(location.href+" #add>*","");
        }

        function searchPicTel(input){
            if(input == ''){
                $("#allLibPicsTel").load(location.href+" #allLibPicsTel>*","");
            }else{
                $.ajax({
                    url: '{{ route("PicLibrary.search") }}',
                    method: 'post',
                    data: {
                        searchWord: input,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                    
                        $('#allLibPicsTel').html('');
                        var listing ='';

                        $.each(JSON.parse(res), function(index, value){
                            listing = '<div style="width:32%;;  border:1px solid lightgray;" class="mr-1 selectPic" data-dismiss="modal" onclick="selectPicForRecTel(\''+value.picLPhoto+'\',\''+value.picLTitle+'\')">'+
                                '<img class="p-1" style="width:100%; height:100px; border-radius:50%;" src="storage/PicLibrary/'+value.picLPhoto+'" alt="">'+
                                    '<div class="d-flex">'+
                                        '<p class="text-center" style="width:80%; font-size:17px;"><strong> '+value.picLTitle+' </strong></p>'+
                                        '<p class="text-center" style="width:20%; border-left:1px solid lightgray; border-bottom:1px solid lightgray;">'+value.picLExt+' </p>'+
                                    '</div>'+
                                '</div>';

                            $('#allLibPicsTel').append(listing); 

                        });
                    
                    },
                    error: (error) => {
                        console.log(error);
                        alert($('#pleaseUpdateAndTryAgain').val());
                    }
                });
            }
        }
    </script>
       
   <style>
        .select2-container .select2-selection--single{
            height:34px !important;
        
        }
        .select2-container--default .select2-selection--single{
            border: 1px solid #ccc !important; 
            border-radius: 0px !important; 
        }
    </style> 

<script>

    function setNewPriceTel(selVal){
        $('#newRecQmimiTel').val($('#prodPriceTel'+selVal).val());
    }
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>






























@foreach(RecomendetProd::where('toRes', '=', $thisRestaurantId)->get() as $RProd)
<div class="modal" id="editTel{{ $RProd->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ Produktet::find($RProd->produkti)->emri}}</h4>
                <button type="button" class="close" data-dismiss="modal"><img width="35px"
                        src="https://img.icons8.com/ios/50/000000/xbox-x.png" /></button>
            </div>


            {{Form::open(['action' => ['RecomendetProdController@update', $RProd->id], 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
            <!-- Modal body -->
            <div class="modal-body">
                <div class="custom-file mb-3 mt-3">
                    {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                    {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                </div>

                <div class="form-group d-flex flex-wrap justify-content-between">
                    <div style="width:100%">
                        {{ Form::label(__('adminP.priceCHF'), null , ['class' => 'control-label']) }}
                        {{ Form::number('qmimi',$RProd->qmimi, ['class' => 'form-control', 'step'=>'0.01', 'min' => '0.01']) }}     
                    </div>
                    <div style="width:100%;">
                        {{ Form::label(__('adminP.priceOptional'), null , ['class' => 'control-label']) }}
                        @if($RProd->qmimi2 != 999999)
                            <?php $secondPriceN = $RProd->qmimi2;?>
                        @else
                            <?php $secondPriceN = "";?>
                        @endif
                        {{ Form::number('qmimi2', $secondPriceN ,
                        ['class' => 'form-control', 'step'=>'0.01', 'min' => '0']) }}
                    </div>
                    <div style="width:100%">
                        {{ Form::label(__('adminP.position'), null , ['class' => 'control-label']) }}
                        {{ Form::number('pozitaN',$RProd->pozita, ['class' => 'form-control', 'step'=>'1', 'min' => '1', 'max' => count(RecomendetProd::where('toRes', '=', $thisRestaurantId)->get())]) }}
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <div class="form-group">

                    {{ Form::submit(__('adminP.saveChanges'), ['class' => 'form-control btn btn-success']) }}
                </div>
            </div>
            {{Form::close() }}

        </div>
    </div>
</div>
@endforeach


<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>































<style>
@media (max-width: 800px) {

    .recCardRes,
    .addRecRes {
        width: 33%;
        height: 570px;
    }
}

@media (min-width: 800px) {

    .recCardRes,
    .addRecRes {
        width: 23%;
    }
}
</style>



<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h2 class="color-qrorpa">{{__('adminP.suggestedProducts')}}</h2>
        </div>
    </div>
</div>

@include('inc.messages')

<div class="p-4 d-flex justify-content-between flex-wrap">




    <table class="table table-hover tableRecTel" id="RecomendetTableATel">
        <thead>
            <tr>
                <th></th>

                <th>{{__('adminP.name')}}</th>
                <th>{{__('adminP.description')}} </th>
     
            </tr>
        </thead>
        <tbody>

            <style>
            .arrowCenter {
                display: flex;
                justify-content: center;
            }

            .buttonet {
                margin: 10px 0;
            }
            </style>

            @foreach(RecomendetProd::where('toRes', '=', $thisRestaurantId)->get()->sortBy('pozita') as $RProd)
            <?php
                $prod = Produktet::find($RProd->produkti);
            ?>

                <tr>
                    <td class="mt-3">
                        <!-- Position change arrows -->

                        @if($RProd->pozita != 1)
                        <button type="button" onclick="upOneRecTel('{{$RProd->id}}')"
                            class="text-center form-control btn btn-outline-primary arrowCenter">˄</button>
                        @else
                        <br><br>
                        @endif
                        @if($RProd->pozita != count(RecomendetProd::where('toRes', '=',$thisRestaurantId)->get()->sortBy('pozita')))
                        <button type="button" onclick="downOneRecTel('{{$RProd->id}}')"
                            class="text-center mt-1 form-control btn btn-outline-primary arrowCenter">˅</button>
                        @endif
                            
                    </td>


                    @if($RProd->picFrom == 1)
                    <td class="text-center" style="background-image:url('storage/RecUpload/{{$RProd->foto}}') ; background-position:center;
                        background-size:cover; color:#000;">
                    @else
                    <td class="text-center" style="background-image:url('storage/PicLibrary/{{$RProd->foto}}') ; background-position:center;
                        background-size:cover; color:#000;">
                    @endif
                    </td>
                    <!-- <td><p class="card-text"><?php echo (strlen($prod->pershkrimi) < 35 ? '<p></p>'.$prod->pershkrimi.'<br>': substr($prod->pershkrimi,0,35).'...');  ?><br>              
                </p></td> -->
                    <td>
                        <form action="{{ route('RecR.destroy', $RProd->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE')}}
                            <button type="submit" class="buttonet btn btn-block btn-outline-danger">{{__('adminP.extinguish')}}</button>
                        </form>

                        <button class="buttonet btn btn-block btn-outline-info" data-toggle="modal"
                            data-target="#editTel{{$RProd->id}}">{{__('adminP.toEdit')}}</button>
                    </td>
                </tr>
                <tr style="border-bottom: 2px solid rgb(72,81,87);" class="mb-2">
                    <td colspan="2">
                        <h4>{{$prod->emri}}</h4>
                    </td>
                    <td>
                        <span style="color:#000;"><strong><ins> {{__('adminP.currencyShow')}} {{sprintf('%01.2f', $RProd->qmimi)}}</ins></strong></span>
                        @if($RProd->qmimi2 != 999999)
                        <br>
                        <span style="color:#000;"><strong><ins> {{__('adminP.currencyShow')}} {{sprintf('%01.2f', $RProd->qmimi2)}}</ins></strong></span>
                        @endif
                    </td>
                </tr>
            @endforeach
            @if(count(RecomendetProd::where('toRes', '=', $thisRestaurantId)->get()) < 10) 
            
                <tr>
                    <!-- <td></td> -->
                    <!-- <td><img class="card-img-top"  height="100px" src="storage/images/defaultImg.png" alt="Card image"></td> -->
                    <td colspan="2">
                        <p class="card-text">{{__('adminP.newFeaturedProduct')}}</p>
                    </td>
                    <td ><a href="#" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#add">+</a></td>
                </tr>
                @endif
        </tbody>
    </table>
</div>






<script>
function forwardOneRecTel(rpId) {
    // alert(rpId);
    $.ajax({
        url: '{{ route("RecR.back") }}',
        method: 'post',
        data: {
            recom: rpId,
            _token: '{{csrf_token()}}'
        },
        success: (response) => {

            $('.tableRecTel').load('/recomendet .tableRecTel', function() {

            });
        },
        error: (error) => {
            console.log(error);
            alert($('#oopsSomethingWrong').val())
        }
    })
}

function backOneRecTel(rpId) {
    // alert(rpId);
    $.ajax({
        url: '{{ route("RecR.forward") }}',
        method: 'post',
        data: {
            recom: rpId,
            _token: '{{csrf_token()}}'
        },
        success: (response) => {

            $('.tableRecTel').load('/recomendet .tableRecTel', function() {

            });
        },
        error: (error) => {
            console.log(error);
            alert($('#oopsSomethingWrong').val())
        }
    })
}
</script>







<script>




   
    function upOneRecTel(rpId){
        $('.arrowCenter').attr("disabled", true);
        $.ajax({
                url: '{{ route("RecR.back") }}',
                method: 'post',
                data: {recom: rpId, _token: '{{csrf_token()}}'},
                success: (response) => {
                    // $('.tableRec').load('/recomendet .tableRec', function() {
                    // });
                    // location.reload();
                    $("#RecomendetTableATel").load(location.href+" #RecomendetTableATel>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
        });
    }

    function downOneRecTel(rpId){
        $('.arrowCenter').attr("disabled", true);
        $.ajax({
                url: '{{ route("RecR.forward") }}',
                method: 'post',
                data: {recom: rpId, _token: '{{csrf_token()}}'},
                success: (response) => {
                    // $('.tableRec').load('/recomendet .tableRec', function() {
                    // });
                    // location.reload();
                    $("#RecomendetTableATel").load(location.href+" #RecomendetTableATel>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            })
    }
    

</script>