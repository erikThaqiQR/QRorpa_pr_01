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
    img{
        object-fit:cover;
    }

    .selectPic:hover{
        cursor: pointer;
        opacity: 0.8;
    }

    .has-search .form-control {
        padding-left: 2.375rem;
    }

    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
    }
</style>
<hr>


@foreach(Produktet::where('toRes', '=', $thisRestaurantId)->get() as $prod)
    <input type="hidden" id="prodPrice{{$prod->id}}" value="{{$prod->qmimi}}">
    <input type="hidden" id="prodPrice2{{$prod->id}}" value="{{$prod->qmimi2}}">
@endforeach


<!-- addRecPro -->
<div class="modal" id="addRecPro" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:30px;">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title color-qrorpa">{{__('adminP.addNewFeaturedProduct')}}</h4>
        <button type="button" class="close" onclick="resetAddModalRec()" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
      </div>

      {{Form::open(['action' => 'RecomendetProdController@store', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}

                <!-- Modal body -->
                <div class="modal-body">

        


                                <div class="form-group d-flex">
                                    <label for="kat" style="width:30%;">{{__('adminP.chooseProduct')}}</label>
                                    <select name="produkti" class="form-control select2" style="width:70%;" onchange="setNewPrice(this.value)">
                                        <option class="p-2 " value="0">{{__('adminP.choose')}}...</option>
                                        <?php
                                            foreach(Produktet::where('toRes', '=', $thisRestaurantId)->get() as $prod){
                                                echo '  <option value="'.$prod->id.'"> '.$prod->emri.'<strong> >> </strong>'.$prod->pershkrimi.'  </option> ';
                                            }
                                        ?>  
                                            
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between mt-3 mb-3" id="selPicPhase01">
                                    <div style="width:48%" class="custom-file">
                                        {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                                        {{ Form::file('foto', ['class' => 'custom-file-input']) }}
                                    </div>
                                    <button type="button" data-toggle="modal" data-target="#otherPicture"
                                        style="width:48%; color:rgb(72, 81, 87); background-color:white; border:1px solid rgb(72, 81, 87);" class="p-2" >
                                        {{__('adminP.chooseOneFromPlatform')}}
                                    </button>
                                </div>
                                <div class="d-flex justify-content-between mt-3 mb-3" style="display:none !important;" id="selPicPhase02">
                                    <p style="width:10%;"></p>
                                    <p style="width:30%; font-weight:bold;" class="text-center" id="selPicPhase02Name"></p>
                                    <p style="width:30%; font-weight:bold;" class="text-center" id="selPicPhase02Title"></p>
                                    <button type="button" style="width:20%;" class="btn btn-outline-danger" onclick="resetAddModalRec()">{{__('adminP.resetToDefault')}}</button>
                                    <p style="width:10%;"></p>
                                </div>
                           
                                <div class="form-group d-flex justify-content-between">
                                    <div style="width:32%;">
                                        {{ Form::label(__('adminP.priceCHF'), null , ['class' => 'control-label']) }}
                                        {{ Form::number('qmimiN', 0 ,
                                         ['class' => 'form-control', 'id' => 'newRecQmimi', 'step'=>'0.01', 'min' => '0']) }}
                                    </div>
                                    <div style="width:48%;">
                                        {{ Form::label(__('adminP.priceOptional'), null , ['class' => 'control-label']) }}
                                        {{ Form::number('qmimiN2', '' ,
                                         ['class' => 'form-control', 'id' => 'newRecQmimi2', 'step'=>'0.01', 'min' => '0']) }}
                                    </div>
                                    <div style="width:16%;">
                                        {{ Form::label(__('adminP.positions'), null , ['class' => 'control-label']) }}
                                        {{ Form::number('pozita',count(RecomendetProd::where('toRes', '=', $thisRestaurantId)->get())+1,
                                         ['class' => 'form-control' ,'step'=>'1', 'max' => '10' , 'min' => '1']) }}
                                    </div>
                                    
                                </div>

                                {{ Form::hidden('toRes',$thisRestaurantId, ['class' => 'form-control']) }}

                                {{ Form::hidden('photoFrom',1, ['class' => 'form-control' , 'id' => 'photoFromID']) }}
                                {{ Form::hidden('photo','', ['class' => 'form-control', 'id' => 'photoID']) }}

                    
       
                </div>

                <!-- Modal footer -->
                <div class="modal-footer" style="width:100%;">
                    <div class="form-group" style="width:100%;">
                        {{ Form::submit(__('adminP.saveOnComputer'), ['class' => 'form-control btn btn-block btn-outline-primary' , 'style' => 'width:100%;']) }}
                    </div>
                </div>

      {{Form::close() }}

    </div>
  </div>
</div>


    <!-- The Modal -->
    <div class="modal" id="otherPicture" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header" style="background-color:rgb(39,190,175);">
                    <h4 class="modal-title" style="color:white; font-weight:bold;">{{__('adminP.chooseOneFromPlatform')}}</h4>
                    <button type="button" class="close" style="color:white;" data-dismiss="modal">X</button>
                </div>

                <div class="form-group has-search mt-2" style="width:50%; margin-left:25%;">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input onkeyup="searchPic(this.value)" type="text" class="form-control" placeholder="{{__('adminP.search')}}">
                </div>

                <!-- Modal body -->
                <div class="modal-body d-flex flex-wrap justify-content-start" id="allLibPics">
                    @foreach(PicLibrary::all()->sortByDesc('created_at') as $pl)
                        <div style="width:14.1%;  border:1px solid lightgray;" class="mr-1 selectPic" data-dismiss="modal" onclick="selectPicForRec('{{$pl->picLPhoto}}','{{$pl->picLTitle}}')">
                            <img style="width:100%; height:150px; border-radius:50%;" src="storage/PicLibrary/{{$pl->picLPhoto}}" alt="">
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

        function selectPicForRec(pic,title){
            $('#selPicPhase01').html("");
            $('#selPicPhase02').css("display", "block");
            $('#selPicPhase02Name').html(pic);
            $('#selPicPhase02Title').html("\" "+title+" \"");

            $('#photoFromID').val('2');
            $('#photoID').val(pic);
        }

        function resetAddModalRec(){
            $("#addRecPro").load(location.href+" #addRecPro>*","");
        }

        function searchPic(input){
            if(input == ''){
                $("#allLibPics").load(location.href+" #allLibPics>*","");
            }else{
                $.ajax({
                    url: '{{ route("PicLibrary.search") }}',
                    method: 'post',
                    data: {
                        searchWord: input,
                        _token: '{{csrf_token()}}'
                    },
                    success: (res) => {
                    
                        $('#allLibPics').html('');
                        var listing ='';

                        $.each(JSON.parse(res), function(index, value){
                            listing = '<div style="width:14.1%;  border:1px solid lightgray;" class="mr-1 selectPic" data-dismiss="modal" onclick="selectPicForRec(\''+value.picLPhoto+'\',\''+value.picLTitle+'\')">'+
                                '<img style="width:100%; height:150px; border-radius:50%;" src="storage/PicLibrary/'+value.picLPhoto+'" alt="">'+
                                    '<div class="d-flex">'+
                                        '<p class="text-center" style="width:80%; font-size:17px;"><strong> '+value.picLTitle+' </strong></p>'+
                                        '<p class="text-center" style="width:20%; border-left:1px solid lightgray; border-bottom:1px solid lightgray;">'+value.picLExt+' </p>'+
                                    '</div>'+
                                '</div>';

                            $('#allLibPics').append(listing); 

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

    function setNewPrice(selVal){
        $('#newRecQmimi').val($('#prodPrice'+selVal).val());
        if($('#prodPrice2'+selVal).val() != 999999){
            $('#newRecQmimi2').val($('#prodPrice2'+selVal).val());
        }else{
            $('#newRecQmimi2').val("");
        }
    }
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });


  
</script>













































<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>































<style>
    @media (max-width: 800px) {
       .recCardRes ,.addRecRes{
           width:33%;
           height:570px;
       }
    }
    @media (min-width: 800px) {
       .recCardRes ,.addRecRes{
           width:23%;
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

<div class="p-4 d-flex justify-content-between flex-wrap" id="relThisNow" >
    



<table class="table table-hover tableRec" id="RecomendetTableA">
    <thead>
      <tr>
        <th></th>
        <th></th>
        <th>{{__('adminP.name')}}</th>
        <th>{{__('adminP.description')}} </th>
        <th> </th>
        <th> </th>

      </tr>
    </thead>
    <tbody>

    <style>
        .arrowCenter{
            display: flex;
            justify-content: center;
        }
    </style>

















    @foreach(RecomendetProd::where('toRes', '=', $thisRestaurantId)->get()->sortBy('pozita') as $RProd)

        <div class="modal" id="Edit{{$RProd->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
            <div class="modal-dialog">
                <div class="modal-content">

                    
                
                    <!-- Modal Header -->
                    <div class="modal-header">
                    <h4 class="modal-title">{{ Produktet::find($RProd->produkti)->emri}}</h4>
                        <button type="button" class="close" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
                    </div>
                    

                    {{Form::open(['action' => ['RecomendetProdController@update', $RProd->id], 'method' => 'post' , 'enctype' => 'multipart/form-data', 'id' => 'RecomendetProdControllerupdate']) }}
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="custom-file mb-3 mt-3">
                                {{ Form::label(__('adminP.image'), null , ['class' => 'custom-file-label']) }}
                                {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                            </div>

                            
                            <div class="form-group d-flex flex-wrap justify-content-between">
                                <div style="width:49%">
                                    {{ Form::label(__('adminP.priceCHF'), null , ['class' => 'control-label']) }}
                                    {{ Form::number('qmimi',$RProd->qmimi, ['class' => 'form-control', 'step'=>'0.01', 'min' => '0.01']) }}
                                </div>
                                <div style="width:49%;">
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
                                    {{ Form::label(__('adminP.positions'), null , ['class' => 'control-label']) }}
                                    {{ Form::number('pozitaN', '', ['class' => 'form-control', 'step'=>'1', 'min' => '1', 'max' => count(RecomendetProd::where('toRes', '=', $thisRestaurantId)->get())]) }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <div class="form-group">
                
                                {{ Form::submit(__('adminP.saveChanges'), ['class' => 'form-control btn btn-success', 'style' => 'width:100%;']) }}
                            </div>
                        </div>
                    {{Form::close() }}   
                    
                </div>
            </div>
        </div>



















      <?php
         $prod = Produktet::find($RProd->produkti);
      ?>
     
        <tr>
            <td>
                @if($RProd->pozita != 1)
                    <button type="button" onclick="upOneRec('{{$RProd->id}}')"
                        class="mt-2 text-center form-control btn btn-outline-primary arrowCenter">˄</button>
                @else
                    <br><br>
                @endif
                @if($RProd->pozita != count(RecomendetProd::where('toRes', '=',$thisRestaurantId)->get()->sortBy('pozita')))
                    <button type="button" onclick="downOneRec('{{$RProd->id}}')"
                        class="text-center mt-1 form-control btn btn-outline-primary arrowCenter">˅</button>
                @endif
                            
                
            </td>

            <td>
                @if($RProd->picFrom == 1)
                    <img class="card-img-top" style="height:100px" src="storage/RecUpload/{{$RProd->foto}}" alt="Card image">
                @else
                    <img class="card-img-top" style="height:100px" src="storage/PicLibrary/{{$RProd->foto}}" alt="Card image">
                @endif
                <p style="font-weight:900; font-size:26px; margin-top:-40px; padding-left:15px; text-shadow: 2px 2px 3px white, 0 0 1em white, 0 0 0.1em white;" 
                class="color-qrorpa"># {{$RProd->pozita}}</p>
            </td>
            <td class="text-center"><h4>{{$prod->emri}}</h4> <br>
                <span style="color:gray;"><strong><ins> {{__('adminP.currencyShow')}} {{sprintf('%01.2f', $RProd->qmimi)}}</ins></strong>
                    @if( $RProd->qmimi2 != 999999)
                        / <strong><ins> {{__('adminP.currencyShow')}} {{sprintf('%01.2f', $RProd->qmimi2)}}</ins></strong>
                    @endif
                </span> 
            </td>
            <td><p class="card-text"><?php echo (strlen($prod->pershkrimi) < 35 ? '<p></p>'.$prod->pershkrimi.'<br>': substr($prod->pershkrimi,0,35).'...');  ?><br>              
                </p></td>
            <td>
                <form action="{{ route('RecR.destroy', $RProd->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE')}}
                    <button type="submit" class="mt-4 btn btn-block btn-outline-danger">{{__('adminP.extinguish')}}</button>
                </form> 
            </td>
            <td>
                <button class="mt-4 btn btn-block btn-outline-info" data-toggle="modal" data-target="#Edit{{$RProd->id}}">{{__('adminP.toEdit')}}</button>                  
            </td>
 
        </tr>
    @endforeach


    @if(count(RecomendetProd::where('toRes', '=', $thisRestaurantId)->get()) < 10)
        <tr>
            <td></td>
            <td><img class="card-img-top"  height="100px" src="storage/images/defaultImg.png" alt="Card image"></td>
            <td colspan="2"><p class="card-text">{{__('adminP.newFeaturedProduct')}}</p></td>
            <td colspan="2"><a href="#" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#addRecPro">+</a></td>
        </tr>
    @endif
    </tbody>
  </table>
</div>



















<script>




   
    function upOneRec(rpId){
        $('.arrowCenter').attr("disabled", true);
        $.ajax({
                url: '{{ route("RecR.back") }}',
                method: 'post',
                data: {recom: rpId, _token: '{{csrf_token()}}'},
                success: (response) => {
                    // $('.tableRec').load('/recomendet .tableRec', function() {
                    // });
                    // location.reload();
                    $("#RecomendetTableA").load(location.href+" #RecomendetTableA>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
        });
    }

    function downOneRec(rpId){
        $('.arrowCenter').attr("disabled", true);
        $.ajax({
                url: '{{ route("RecR.forward") }}',
                method: 'post',
                data: {recom: rpId, _token: '{{csrf_token()}}'},
                success: (response) => {
                    // $('.tableRec').load('/recomendet .tableRec', function() {
                    // });
                    // location.reload();
                    $("#RecomendetTableA").load(location.href+" #RecomendetTableA>*","");
                },
                error: (error) => {
                    console.log(error);
                    alert($('#oopsSomethingWrong').val())
                }
            })
    }
    

</script>

