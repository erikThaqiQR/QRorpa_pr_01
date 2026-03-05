<?php

use App\Restorant;
use App\Orders;
use App\Produktet;
use App\TableQrcode;
use App\kategori;
use App\User;

$res=Restorant::find($_GET['Res']);

?>





<style>
    .contentMngList:hover{
        cursor: pointer;
        color:black;
    }
</style>






<!-- The Modal -->
<div class="modal" id="contentMNG" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title color-qrorpa">{{$res->emri}} / Content manager</h4>
            <button type="button" class="close" data-dismiss="modal">X</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body d-flex flex-wrap justify-content-start" id="contentManagerList">
            @foreach(User::where('role',54439)->get() as $usr)
                @if($res->accessID == $usr->id)
                    <div style="width:19%; color:white; background-color:rgb(39,190,175); border:2px solid rgb(39,190,175); border-radius:10px;" class="mr-1 p-2 text-center">
                @else
                    <div onclick="setAsCM('{{$usr->id}}','{{$res->id}}')" style="width:19%; color:rgb(72,81,87); border:2px solid rgb(39,190,175); border-radius:10px;"
                     class="mr-1 p-2 text-center contentMngList">
                @endif    
                    <span><strong> {{$usr->name}} </strong></span>
                </div>
            @endforeach
        </div>

    </div>
  </div>
</div>





<script>
    function setAsCM(uID,rID){
        $.ajax({
			url: '{{ route("restorantet.SetContentMng") }}',
			method: 'post',
			data: {
				resID: rID,
				userID: uID,
				_token: '{{csrf_token()}}'
			},
			success: () => {
				$("#contentManagerList").load(location.href+" #contentManagerList>*","");
			},
			error: (error) => {
				console.log(error);
				alert('bitte aktualisieren und erneut versuchen!');
			}
		});
    }
</script>


























<div class="allRestaurants" id="oneRestaurant{{$res->id}}">


  
        <div class="d-flex justify-content-between" id="firstRowRes{{$res->id}}">
            <div style="width:5%;">
                <a href="{{route('restorantet.index')}}">
                    <img class="pt-3" src="https://img.icons8.com/android/48/000000/back.png"/>
                </a>
            </div>
            <div style="margin-left:-30px; width:30%;">
                <div class="container" style="width:100%; background-color:rgb(247, 247, 240); border-radius:25px;" >
                    <div class="row p-2">
                        <div class="col-4">
                            @if($res->profilePic == 'none')
                                <img width="100%" src="storage/icons/Logo.png" alt="">
                            @else
                                <img style="width:100%; border-radius:50%;" src="storage/ResProfilePic/{{$res->profilePic}}" alt="">
                            @endif
                        </div>
                        <div class="col-8">
                            <p class="color-qrorpa mt-2" style="font-size:18px;"><strong>{{$res->emri}}</strong> </p>
                            <p style="opacity:0.55; margin-top:-15px; font-size:12px;">{{($res->adresas == 'empty' ? '---' : $res->adresa)}}</p>

                            <button style="margin-top:-15px; font-weight:bold; color:rgb(72,81,87);" data-toggle="modal" data-target="#contentMNG"
                                class="btn btn-block text-center mb-2">Content manager</button>

                            <div class="form-group">
                                    <select onchange="setResType(this.value,'{{$res->id}}')" class="form-control" data-role="select-dropdown" data-profile="minimal">
                                        @if($res->resType == 1)
                                            <option value="1" selected>Restaurant</option>
                                        @else
                                            <option value="1">Restaurant</option>
                                        @endif

                                        @if($res->resType == 2)
                                            <option value="2" selected>+Takeaway</option>
                                        @else
                                            <option value="2">+Takeaway</option>
                                        @endif

                                        @if($res->resType == 3)
                                            <option value="3" selected>+Delivery </option>
                                        @else
                                            <option value="3">+Delivery</option>
                                        @endif

                                        @if($res->resType == 5)
                                            <option value="5" selected>+Table Rezervation</option>
                                        @else
                                            <option value="5">+Table Rezervation</option>
                                        @endif

                                        @if($res->resType == 6)
                                            <option value="6" selected>+Takeaway +Table Rezervation</option>
                                        @else
                                            <option value="6">+Takeaway +Table Rezervation</option>
                                        @endif

                                        @if($res->resType == 7)
                                            <option value="7" selected>+Takeaway +Delivery</option>
                                        @else
                                            <option value="7">+Takeaway +Delivery</option>
                                        @endif

                                        
                                        @if($res->resType == 8)
                                            <option value="8" selected>+Delivery +Table Rezervation</option>
                                        @else
                                            <option value="8">+Delivery +Table Rezervation</option>
                                        @endif
                                        
                                        @if($res->resType == 9)
                                            <option value="9" selected>ALL</option>
                                        @else
                                            <option value="9">ALL</option>
                                        @endif
                                       
                                       
                                    </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="width:2%;">
            </div>

            <div style="width:17%; " class="b-qrorpa br-30">
                <div class="p-4"> 
                    <p class="color-white"><strong> Cash Payment Clicks : {{$res->cashPayClicks}} </strong></p>
                    <p class="color-white" style="margin-top:-13px;"><strong> Cash Payment Orders : {{$res->cashPayOrders}} </strong></p>
                    @if( $res->cashPayClicks != 0)
                        <p class="color-white text-center" style="margin-top:-13px; font-size:19px;"><strong> {{number_format(($res->cashPayOrders / $res->cashPayClicks) *100, 2, '.', '')}} % </strong></p>
                    @endif
                  
                   

                 
                </div>
            </div>
            <div style="width:17%;" class="b-qrorpa br-30">
                <div class="p-4" id="srRankingPayDiv{{$res->id}}"> 
                    <p class="opacity-65 color-white">Search Ranking Pay</p>
                    <input id="srRankingPayVal{{$res->id}}" value="{{$res->payAmSer}}" class="text-center" type="number" step="0.01" min="0.00" onkeypress="showSaveSrRankingPay('{{$res->id}}')" 
                    style="background-color:inherit; color:white; width:100%; border:none; border-bottom:1px solid white; margin-top:-20px;" >
                    <button onclick="saveSrRankingPay('{{$res->id}}')" id="btnSaveSerPay{{$res->id}}" class="btn btn-block mt-2"
                         style="border:1px solid white; color:white; display:none;"><strong>Save</strong></button>
                </div>
            </div>
            <div style="width:17%;" class="b-qrorpa br-30">
                <div class="p-4"> 
                    <p class="opacity-65 color-white">Social Media</p>
                    <p class="color-white" style="margin-top:-18px; margin-bottom:-18px;">...</p>
                </div>
            </div>
            <div style="width:2%;">
            </div>
        </div><!-- End first row  -->





        <script>
            function setResType(val,res){
                $.ajax({
                    url: '{{ route("Res.setResType") }}',
                    method: 'post',
                    data: {
                        id: res,
                        newVal: val,
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        // $("#freeProElements").load(location.href+" #freeProElements>*","");
                    },
                    error: (error) => {
                        console.log(error);
                        alert('bitte aktualisieren und erneut versuchen!');
                    }
                });
            }

            function showSaveSrRankingPay(resId){
                $('#btnSaveSerPay'+resId).show(200);
            }

            function saveSrRankingPay(resId){
                $.ajax({
                    url: '{{ route("Res.setSerPayAm") }}',
                    method: 'post',
                    data: {
                        id: resId,
                        newVal: $('#srRankingPayVal'+resId).val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: () => {
                        $("#srRankingPayDiv"+resId).load(location.href+" #srRankingPayDiv"+resId+">*","");
                    },
                    error: (error) => {
                        console.log(error);
                        alert('bitte aktualisieren und erneut versuchen!');
                    }
                });
            }
        </script>















        <div class="d-flex justify-content-between mt-3" style="width:90%; margin-left:5%; background-color:rgb(247, 247, 240); border-radius:25px;"> 
            @if($res->bPic == 'none')
                <img style="width:85%;" src="" alt="">
                <button class="btn" style="width:15%; height:100%; background-color:rgb(39, 190, 175); color:white;" data-toggle="modal" data-target="#backgroundRes{{$res->id}}">
                    <strong>Add background</strong>
                </button>
            @else
                <img style="width:85%; height:300px;" src="storage/ResBackgroundPic/{{$res->bPic}}" alt="">
                <div style="width:15%;">
                <button class="btn" style="width:100%; height:200px; background-color:rgb(39, 190, 175); color:white;" data-toggle="modal" data-target="#backgroundRes{{$res->id}}">
                    <strong>Change background</strong>
                </button><br>
                {{Form::open(['action' => 'RestorantController@removeBackgroundPic', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}

                    {{ Form::hidden('toRes',$res->id, ['class' => 'form-control']) }}

                    <div class="form-group">
                        {{ Form::submit('Remove background ', ['class' => 'form-control btn', 'style' => 'height:100px; width:100%; color:white; background-color:red;']) }}
                    </div>


                {{Form::close() }}
                </div>
            @endif
            
        </div>

        <div class="modal" id="backgroundRes{{$res->id}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal body -->
                <div class="modal-body text-center">

                    {{Form::open(['action' => 'RestorantController@setBackgroundPic', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}

                        <div class="custom-file mb-3 mt-3 text-left">
                            {{ Form::label('Background Pic', null , ['class' => 'custom-file-label']) }}
                            {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                        </div>

                        {{ Form::hidden('toRes',$res->id, ['class' => 'form-control']) }}

                        <div class="form-group">
                            {{ Form::submit('Save', ['class' => 'form-control btn btn-block btn-outline-primary']) }}
                        </div>


                    {{Form::close() }}
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">X</button>
                </div>

                </div>
            </div>
        </div>




















































        <?php
            $today = Date('Y-m-d');
            $todayOrd = 0;

            foreach(Orders::where('Restaurant', '=', $res->id)->get() as $ord){
                if($today == explode(' ',$ord->created_at)[0]){
                    $todayOrd++;
                }
            }
        ?>

        <div class="d-flex justify-content-between mt-4 ml-5 mr-5" id="secondRowRes{{$res->id}}">
            <div style="width:16%; background-color:rgb(247, 247, 240); border-radius:25px;" class="p-4 ml-1" onclick="openThisOrders('{{$res->id}}')">
                <img src="storage/icons/SAI01OrC.png" alt="notFound.png" style="width:19%;">
                <p class="color-qrorpa mt-4" style="font-size:20px;"><strong>Bestellungen</strong></p>
                <p class="color-qrorpa" style="font-size:14px; opacity:0.75; margin-top:-20px;" >
                    {{$todayOrd}} heute
                </p>
                <p class="color-qrorpa" style="font-size:12px; opacity:0.75; margin-top:-16px;" >
                    text text text text text text text text
                </p>
            </div>
            <div style="width:16%; background-color:rgb(247, 247, 240); border-radius:25px;" class="p-4 ml-1" onclick="openThisProducts('{{$res->id}}')">
                <img src="storage/icons/SAI01ProC.png" alt="notFound.png" style="width:19%;">
                <p class="color-qrorpa mt-4" style="font-size:20px;"><strong>Produkte</strong></p>
                <p class="color-qrorpa" style="font-size:14px; opacity:0.75; margin-top:-20px;" >
                    {{count(Produktet::where('toRes', '=', $res->id)->get())}} Produkte
                </p>
                <p class="color-qrorpa" style="font-size:12px; opacity:0.75; margin-top:-16px;" >
                    text text text text text text text text
                </p>
            </div>
            <div style="width:16%; background-color:rgb(247, 247, 240); border-radius:25px;" class="p-4 ml-1" onclick="openThisTables('{{$res->id}}')">
                <img src="storage/icons/SAI01OrC.png" alt="notFound.png" style="width:19%;">
                <p class="color-qrorpa mt-4" style="font-size:20px;"><strong>Tischen</strong></p>
                <p class="color-qrorpa" style="font-size:14px; opacity:0.75; margin-top:-20px;" >
                    {{count(TableQrcode::where('Restaurant', '=', $res->id)->get())}} tische
                </p>
                <p class="color-qrorpa" style="font-size:12px; opacity:0.75; margin-top:-16px;" >
                    text text text text text text text text
                </p>
                
            </div>
            <div style="width:16%; background-color:rgb(247, 247, 240); border-radius:25px;" class="p-4 ml-1">
                <img src="storage/icons/usersC.png" alt="notFound.png" style="width:19%;">
                <p class="color-qrorpa mt-4" style="font-size:20px;"><strong>Kunden</strong></p>
                <p class="color-qrorpa" style="font-size:14px; opacity:0.75; margin-top:-20px;" >
                    xxx Kunden
                </p>
                <p class="color-qrorpa" style="font-size:12px; opacity:0.75; margin-top:-16px;" >
                    text text text text text text text text
                </p>
            </div>
            <div style="width:15%; background-color:rgb(247, 247, 240); border-radius:25px;" class="p-4 mr-1">
                <a href="/RestorantetWH?Res={{$res->id}}">
                    <img src="storage/icons/restorantetWHIcon.png" alt="notFound.png" style="width:19%;">
                    <p class="color-qrorpa mt-4" style="font-size:20px;"><strong> Öffnungszeiten</strong></p>
                </a>
            </div>
            <div style="width:15%; background-color:rgb(247, 247, 240); border-radius:25px;" class="p-4 mr-1" data-toggle="modal" data-target="#profilePic{{$res->id}}">
                <img src="storage/icons/restorantetProfileIcon.png" alt="notFound.png" style="width:19%;">
                <p class="color-qrorpa mt-4" style="font-size:20px;"><strong>Profilfoto</strong></p>
                
            </div>

        </div><!-- End second row  -->
    </div>





    <!-- The Modal -->
        <div class="modal" id="profilePic{{$res->id}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
            style="background-color: rgba(0, 0, 0, 0.5); margin-top:5%;">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal body -->
                <div class="modal-body text-center">
                    @if($res->profilePic != 'none')
                        <img src="" alt="">
                    @else
                        <img width="80%" src="storage/icons/Logo.png" alt="">
                    @endif

                    {{Form::open(['action' => 'RestorantController@setProfilePic', 'method' => 'post' , 'enctype' => 'multipart/form-data']) }}

                        <div class="custom-file mb-3 mt-3 text-left">
                            {{ Form::label('Bild', null , ['class' => 'custom-file-label']) }}
                            {{ Form::file('foto', ['class' => 'custom-file-input', 'id'=> 'customFile']) }}
                        </div>

                        {{ Form::hidden('toRes',$res->id, ['class' => 'form-control']) }}

                        <div class="form-group">
                            {{ Form::submit('Speichern', ['class' => 'form-control btn btn-block btn-outline-primary']) }}
                        </div>


                    {{Form::close() }}
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">X</button>
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
        </script>












    <!-- All orders  -->

    <div class="allOrdersRes p-3" id="OneOrderRes{{$res->id}}">
        <div class="text-cente">
            <p style="font-size:19px;" class="color-qrorpa p-3"><strong>Bestellungen</strong></p>
        </div>
        <div class="d-flex justify-content-between flex-wrap p-3" >
            @foreach(Orders::where('Restaurant', '=', $res->id)->get()->sortByDesc('created_at') as $thisOrder)
                <div style="width:10%;" class="mb-3">
                    <?php
                        $orderTime = explode(' ',$thisOrder->created_at)[1];
                        $orderDate = explode(' ',$thisOrder->created_at)[0];
                        $orderDate2D = explode('-',$orderDate);
                        $orderTime2D = explode(':',$orderTime);

                        echo '<p class="color-qrorpa">'.$orderTime2D[0].':'.$orderTime2D[1].'</p>
                            <p class="color-qrorpa" style="margin-top:-13px; font-size:10px;">'.$orderDate2D[2].'/'.$orderDate2D[1].'/'.$orderDate2D[0].'</p>
                            <p class="color-qrorpa" style="margin-top:-13px; margin-bottom:-10px; font-size:10px;">'. date("l", strtotime($orderDate)).'</p>
                        ';
                    ?>
                </div>
                <div  style="width:10%; border-bottom:1px solid lightgray;">
                    <p>{{$thisOrder->nrTable}}</p>
                </div>
                <div  style="width:15%; border-bottom:1px solid lightgray;">
                    <p>{{$thisOrder->userEmri}}</p>
                </div>
                <div  style="width:15%; border-bottom:1px solid lightgray;">
                    <p>{{$thisOrder->userEmail}}</p>
                </div>
                <div  style="width:25%; border-bottom:1px solid lightgray;">
                    @foreach(explode('---8---',$thisOrder->porosia) as $thisPro)
                        <?php $pro = explode('-8-', $thisPro);  $step=0;?>
                        <p><strong>{{$pro[0]}}</strong> <span class="ml-2" style="opacity:0.75">( {{$pro[5]}} )</span></p>
                        <p>
                            @if(!empty($pro[2]))
                                @foreach(explode('--0--',$pro[2]) as $ext)
                                    @if($step++ == 0)
                                    + {{explode('||', $ext)[0]}}
                                    @else
                                    <strong> >> </strong> + {{explode('||', $ext)[0]}}
                                    @endif
                                @endforeach
                            @endif
                        </p>
                    @endforeach
                </div>
                <div  style="width:5%; border-bottom:1px solid lightgray;">
                    <p>{{$thisOrder->payM}}</p>
                </div>
                <div  style="width:10%; border-bottom:1px solid lightgray;">
                    <p>{{$thisOrder->shuma}}</p>
                </div>
                <div  style="width:10%; border-bottom:1px solid lightgray;">
                    <p>
                        @if($thisOrder->statusi == 0)
                            Warten... 
                        @elseif($thisOrder->statusi == 1)
                            Kochen
                        @elseif($thisOrder->statusi == 2)
                            Abgelehnt
                        @else
                            Abgeschlossen
                        @endif
                    </p>
                </div>
            @endforeach
        </div> 
    </div>








    <div class="allProductsRes p-3" id="OneProductRes{{$res->id}}">
        <div class="text-cente">
            <p style="font-size:19px;" class="color-qrorpa p-3"><strong>Produkte</strong></p>
        </div>
        <div class="d-flex justify-content-between flex-wrap p-3" >
            @foreach(Produktet::where('toRes', '=', $res->id)->get() as $thisProduct)
              
                    <div style="width:5%; border-bottom:1px solid lightgray;" class="pb-3">
                        <p><strong>{{$thisProduct->id}}</strong></p>
                    </div>
                    <div  style="width:10%; border-bottom:1px solid lightgray;">
                        <p>{{$thisProduct->emri}}</p>
                    </div>
                    <div  style="width:30%; border-bottom:1px solid lightgray;">
                        <p>{{$thisProduct->pershkrimi}}</p>
                    </div>
                    <div  style="width:15%; border-bottom:1px solid lightgray;">
                        <p>{{$thisProduct->qmimi}} CHF</p>
                    </div>
                    <div  style="width:15%; border-bottom:1px solid lightgray;" >
                        <p>{{kategori::find($thisProduct->kategoria)->emri}}</p>
                    </div>
                    <div  style="width:15%; border-bottom:1px solid lightgray;" >
                        @foreach(explode('--0--', $thisProduct->extPro) as $thisExtra)
            @if(!empty($thisExtra))
                             <p>{{explode('||', $thisExtra)[1]}} {{explode('||', $thisExtra)[2]}}</p>
            @endif
                        @endforeach
                        
                    </div>
                    <div  style="width:10%; border-bottom:1px solid lightgray;" >
                        @if($thisProduct->type != null )
                            @foreach(explode('--0--', $thisProduct->type) as $thisType)
                @if(!empty($thisType))
                                        <p>{{explode('||', $thisType)[1]}} {{explode('||', $thisType)[2]}}</p>
                @endif
                            @endforeach
                        @endif
                    </div>
            @endforeach
        </div> 
    </div>






    <div class="allTablesRes p-3" id="OneTableRes{{$res->id}}">
        <div class="text-cente">
            <p style="font-size:19px;" class="color-qrorpa p-3"><strong>Tichen</strong></p>
        </div>
        <div class="d-flex justify-content-between flex-wrap p-3" >
            @foreach(TableQrcode::where('Restaurant', '=', $res->id)->get() as $thisTable)
              
                    <div style="width:10%; border-bottom:1px solid lightgray;" class="pb-3">
                        <p><strong> Nr. {{$thisTable->tableNr}}</strong></p>
                    </div>
                    <div  style="width:10%; border-bottom:1px solid lightgray;" class="mr-3">
                        <img style="width:40px;" src="storage/qrcode/{{$thisTable->path}}" alt="notFound.png">
                    </div>
               
                    
            @endforeach
        </div> 
    </div>


















<br><br>



<script>
$('.backAllRes').click(function() {
    $('.allRestaurants').hide('slow');
    $('#startRestorant').show('slow');

    $('.allOrdersRes').hide('slow'); 
    $('.allProductsRes').hide('slow'); 
    $('.allTablesRes').hide('slow');
    
});

$(document).ready(function() {
  
    $('.allOrdersRes').hide(); 
    $('.allProductsRes').hide(); 
    $('.allTablesRes').hide(); 

    
});

function showRestaurant(resId){
    $('#startRestorant').hide('slow');

    $('#oneRestaurant'+resId).show('slow');
}

function openThisOrders(rId){
    $('.allProductsRes').hide('slow'); 
    $('.allTablesRes').hide('slow');

    if($("#OneOrderRes"+rId).is(":visible")){
        $('#OneOrderRes'+rId).hide('slow');
    }else{
        $('#OneOrderRes'+rId).show('slow');
    }
}

function openThisProducts(rId){
    $('.allOrdersRes').hide('slow'); 
    $('.allTablesRes').hide('slow');

    if($("#OneProductRes"+rId).is(":visible")){
        $('#OneProductRes'+rId).hide('slow');
    }else{
        $('#OneProductRes'+rId).show('slow');
    }
}

function openThisTables(rId){
    $('.allOrdersRes').hide('slow'); 
    $('.allProductsRes').hide('slow');

    if($("#OneTableRes"+rId).is(":visible")){
        $('#OneTableRes'+rId).hide('slow');
    }else{
        $('#OneTableRes'+rId).show('slow');
    }
}
</script>