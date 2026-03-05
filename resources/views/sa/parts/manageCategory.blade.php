<?php
    use App\kategori;
    use App\ekstra;
    use App\LlojetPro;
    use App\Restorant;
    // use App\Produktet;

    $theResId = $_GET['Res'];

    $kat = kategori::where('toRes',$theResId)->get();
    // $restaurant = Restorant::all()->sortByDesc('created_at');
    // $produktet = Produktet::all();
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

    </style>
<div class="container-fluid mb-4 " >
    <div class="row mt-4">
        <a href="produktet5Boxes?Res={{$_GET['Res']}}" class="col-2 anchorMy pt-4" style="font-size:25px;"><strong> < Back </strong></a>
        <div class="col-8 text-center">
            <p style="font-size:45px;" class="color-qrorpa">"{{Restorant::find($_GET['Res'])->emri}}"  /  Categories</p>
        </div>
         <div class="col-2"></div>
    </div>
</div>






    <div class="container p-2 kategoriteAll" id="KategoriOne{{$theResId}}">
        <div class="row">
            <div class="col-12">
                @if(session('error'))
                    <div class="alert alert-danger">
                        <h4>{{session('error')}}</h4>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addCatModal">
                    <img src="https://img.icons8.com/dusk/64/000000/add-property.png" width="25">
                    Add a category
                </button>
            </div>
        </div>    
    </div>

    <div class="pr-5 pl-5 pb-5">
        @foreach($kat as $kategorit)
            <div class="d-flex justify-content-between flex-wrap mb-1 p-2" >
                <div style="width:12%;" class="text-center mt-3">
                    <h4><strong># {{$kategorit->id}}</strong></h4>
                    <h4><strong>{{$kategorit->emri}}</strong></h4>
                </div>
                @if($kategorit->foto == 'empty')
                    <img style="width:68%; height:100px; border-radius:10px; " src="storage/kategoriaUpload/bannerDef.png" alt="">
                @else
                    <img style="width:68%; height:100px; border-radius:10px;" src="storage/kategoriaUpload/{{$kategorit->foto}}" alt="">
                @endif
                <div style="width:20%;" class="text-center">
                
                    <button class="btn btn-block btn-block mb-3" style="color:blue; font-size:19px;" data-toggle="modal" data-target="#editKat{{$kategorit->id}}"><strong>Edit</strong></button>
                            
                        {{Form::open(['action' => ['KategoriController@destroy', $kategorit->id ], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure")'])}}

                        {{Form::hidden('_method', 'DELETE')}}
                        {{Form::submit('Delete', ['class' => 'btn btn-block', 'style' => 'font-weight:bold; color:red; font-size:19px;' ])}}

                        {{ Form::hidden('res', $theResId, ['class' => 'custom-file-input']) }}

                        {{Form::close()}}
                            
                </div>

                @if(LlojetPro::where([['toRes',$theResId],['kategoria',$kategorit->id]])->get()->count() > 0)
                <div style="border:1px solid rgb(39,190,175,0.6); width:100%; border-radius:10px;" class="p-1 mt-3 d-flex flex-wrap justify-content-start">
                    <p style="text-align: center; width:100%;  margin-top:-20px; font-size:20px; z-index:99;"><strong>Type</strong></p>
                    @foreach(LlojetPro::where([['toRes',$theResId],['kategoria',$kategorit->id]])->get() as $type)
                        
                        <span style="width:14.25%; border:1px solid rgb(39,190,175,0.4);" class="p-1 mb-1 text-center">
                            {{$type->emri}} <span style="opacity:0.7;">({{$type->vlera}})</span>
                         </span>
                          
                    @endforeach
                </div>
                @endif
                @if(ekstra::where([['toRes',$theResId],['toCat',$kategorit->id]])->get()->count() > 0)
                <div style="width:100%; border:1px solid rgb(39,190,175,0.6); border-radius:10px;" class="p-1 mt-3 d-flex flex-wrap justify-content-start">
                    <p class="text-center" style="width:100%; margin-top:-20px; font-size:20px;"><strong>Extra</strong></p>
                    @foreach(ekstra::where([['toRes',$theResId],['toCat',$kategorit->id]])->get() as $ext)
                        <span style="width:14.25%; border:1px solid rgb(39,190,175,0.4);" class="p-1 mb-1 text-center">
                            {{$ext->emri}} <span style="opacity:0.7;">({{$ext->qmimi}} CHF)</span>
                         </span>
                    @endforeach
                </div>
                @endif
                
            </div>
            <hr>
        @endforeach
    </div>



























    <div class="modal  fade " id="addCatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="color:black;">Shkruni emrin e kategorise qe doni te shtoni.</h4>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                </div>


                {{Form::open(['action' => 'KategoriController@store', 'method' => 'post', 'enctype' => 'multipart/form-data' ]) }}

                    {{csrf_field()}}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            {{ Form::label('Emri', null, ['class' => 'control-label color-black']) }}
                            {{ Form::text('emri','', ['class' => 'form-control ']) }}
                        </div>

                        <div class="custom-file mb-3 mt-3">
                            {{ Form::label('Picture', null , ['class' => 'custom-file-label']) }}
                            {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="restaurant" class="form-control" id="resKat" value="{{$theResId}}">
                            </input>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{ Form::submit('Ruaj', ['class' => 'form-control btn btn-primary']) }}
                        
                    </div>
                {{Form::close() }}

                </div>
            </div>
        </div>


        <script>
            // Add the following code if you want the name of the file appear on select
            $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        </script>













<!-- Edit modal's -->
@foreach($kat as $kategorit)

<div class="modal  fade " id="editKat{{$kategorit->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content color-black">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">You are editing "{{$kategorit->emri}}" for {{Restorant::find($_GET['Res'])->emri}}</h4>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>

        {{Form::open(['action' => ['KategoriController@update', $kategorit->id], 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}
            {{csrf_field()}}    
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group">
                    {{ Form::label('Emri i ri',null , ['class' => 'control-label']) }}
                    {{ Form::text('emri',$kategorit->emri, ['class' => 'form-control']) }}
                </div>
                <div class="custom-file mb-3 mt-3">
                    {{ Form::label('Picture', null , ['class' => 'custom-file-label']) }}
                    {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                </div>
            </div>

            {{ Form::hidden('page', $theResId , ['class' => 'custom-file-input']) }}

            <!-- Modal footer -->
            <div class="modal-footer">
                {{ Form::submit('Save', ['class' => 'form-control btn btn-primary']) }}
                
            </div>
        {{Form::close() }}

        </div>
    </div>
</div>
    
@endforeach