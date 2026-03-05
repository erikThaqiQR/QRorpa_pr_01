<?php
    use App\PicLibrary;
    use App\RecomendetProd;
?>

<style>
    .newPicBtn{
        border:1px solid rgb(39,190,175);
        color:rgb(39,190,175);
        font-size: 21px;
    }
    .newPicBtn:hover{
        background-color:rgb(39,190,175);
        color:white;
    }

    .delBtnPic{
        border:1px solid red; 
        color:red; 
        font-weight:bold; 
        background-color:white;
    }
    .delBtnPic:hover{
        border:1px solid red; 
        color:white; 
        font-weight:bold; 
        background-color:red;
    }
    .inuseBtnPic{
        border:1px solid green; 
        color:green; 
        font-weight:bold; 
        background-color:white;
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

<section class="pt-4 pl-4 pr-4 pb-5">

    <div class="d-flex">
        <h3 class="color-qrorpa mt-3" style="width:50%;" id="picLibHeader001"><strong> Empfohlene Produktbilder ( {{PicLibrary::all()->count()}} X ) </strong></h3>
         <!-- Actual search box -->
        <div class="form-group has-search" style="width:50%;">
            <span class="fa fa-search form-control-feedback"></span>
            <input onkeyup="searchPic(this.value)" type="text" class="form-control" placeholder="Suche">
        </div>
        
    </div>
  

    <button class="btn btn-block newPicBtn"  data-toggle="modal" data-target="#newPicRec"><strong>Füge ein neues Bild hinzu</strong></button>

    @if(session('success'))
    <div class=" alert alert-success text-center mt-2 mb-2">
        {{session('success')}}
    </div>
    @endif

    <div class="d-flex flex-wrap justify-content-start mt-3" id="allPicLicRec">
        @foreach(PicLibrary::all()->sortByDesc('created_at') as $pl)
            <div style="width:11%;  border:1px solid lightgray;" class="mr-1">
                <img style="width:100%; height:150px; border-radius:50%;" src="storage/PicLibrary/{{$pl->picLPhoto}}" alt="">
                <div class="d-flex">
                    <p class="text-center" style="width:80%; font-size:17px;"><strong> {{$pl->picLTitle}} </strong></p>
                    <p class="text-center" style="width:20%; border-left:1px solid lightgray; border-bottom:1px solid lightgray;">{{$pl->picLExt}} </p>
                </div>
                @if(RecomendetProd::where([['picFrom','2'],['foto',$pl->picLPhoto]])->count() <= 0)
                    <button onclick="deletePicRec('{{$pl->id}}');" class="p-1 delBtnPic" style="width:100%;">Löschen</button>
                @else
                    <button class="p-1 inuseBtnPic" style="width:100%;">in Benutzung ( {{RecomendetProd::where([['picFrom','2'],['foto',$pl->picLPhoto]])->count()}} X ) </button>
                @endif
            </div>
        @endforeach
    </div>

    <div class="d-flex flex-wrap justify-content-start mt-3" style="display:none;" id="searchPicLicRec">
    </div>

</section>



<!-- The Modal -->
<div class="modal" id="newPicRec" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color:rgb(39,190,175);">
                <h4 class="modal-title" style="color:white;">Füge ein neues Bild hinzu</h4>
                <button type="button" class="close" data-dismiss="modal" style="color:white;">X</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                {{Form::open(['action' => 'PicLibController@store', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
                    <div class="custom-file mb-3 mt-3">
                        {{ Form::label('Bild', null , ['class' => 'custom-file-label']) }}
                        {{ Form::file('foto', ['class' => 'custom-file-input', 'required']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::text('title','', ['class' => 'form-control' , 'placeholder' => 'Titel' , 'required']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::submit('Senden', ['class' => 'form-control btn newPicBtn']) }}
                    </div>
                {{Form::close() }}
            </div>

        </div>
    </div>
</div>
<script>

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });


    function deletePicRec(pId){
        $.ajax({
			url: '{{ route("PicLibrary.destroy") }}',
			method: 'post',
			data: {
				id: pId,
				_token: '{{csrf_token()}}'
			},
			success: () => {
                $("#allPicLicRec").load(location.href+" #allPicLicRec>*","");
                $("#picLibHeader001").load(location.href+" #picLibHeader001>*","");
                
                $('#searchPicLicRec').html('');
			},
			error: (error) => {
				console.log(error);
				alert('bitte aktualisieren und erneut versuchen!');
			}
		});
    }

    function searchPic(input){
     
        if(input == ''){
            $('#searchPicLicRec').html('');
            $("#allPicLicRec").load(location.href+" #allPicLicRec>*","");
        }else{

            $.ajax({
                url: '{{ route("PicLibrary.search") }}',
                method: 'post',
                data: {
                    searchWord: input,
                    _token: '{{csrf_token()}}'
                },
                success: (res) => {
                   
                    $('#allPicLicRec').html('');
                    $('#searchPicLicRec').html('');

                    var listing ='';
                    $.each(JSON.parse(res), function(index, value){
                        listing = '<div style="width:11%;  border:1px solid lightgray;" class="mr-1">'+
                            '<img style="width:100%; height:150px; border-radius:50%;" src="storage/PicLibrary/'+value.picLPhoto+'" alt="">'+
                                '<div class="d-flex">'+
                                    '<p class="text-center" style="width:80%; font-size:17px;"><strong> '+value.picLTitle+' </strong></p>'+
                                    '<p class="text-center" style="width:20%; border-left:1px solid lightgray; border-bottom:1px solid lightgray;">'+value.picLExt+' </p>'+
                                '</div>'+
                                '<button onclick="deletePicRec('+value.id+');" class="p-1 delBtnPic" style="width:100%;">Löschen</button>'+
                            '</div>';

                        $('#searchPicLicRec').append(listing); 

                    });
                   
                },
                error: (error) => {
                    console.log(error);
                    alert('bitte aktualisieren und erneut versuchen!');
                }
            });
        }


      
     
    }

</script>