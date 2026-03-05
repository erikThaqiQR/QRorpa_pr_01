<?php
    use App\Restorant;
    use App\Orders;
    use App\Produktet;
    use App\TableQrcode;
    use App\kategori;
    use App\User;

    
    $restorantet = Restorant::all()->sortByDesc('created_at');
    $orders = Orders::all();

    $dateNow = date('Y-m-d');

   
    $todaySales = 0;
    foreach($orders as $orderSot){
        if(explode(' ',$orderSot->created_at)[0] == $dateNow)
            $todaySales += $orderSot->shuma;
        
        
    }
?>

<style>
    .listRes:hover{
        cursor:pointer;
    }

    .backAllRes{
        opacity:0.45;
    }
    .backAllRes:hover{
        cursor:pointer;
        opacity:0.85;
    }
    button {
        outline: none;
    }
    textarea:focus, input:focus, button:focus{
        outline: none;
    }

    .b-qrorpa:hover{
        cursor: pointer;
    }

    .contentMngModalUsr:hover{
        cursor: pointer;
    }
</style>

<div style="padding:10px;" id="startRestorant">
 
    <!-- The Modal  Add restorant-->
    <div class="modal pt-2" id="addRes"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
        style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
        
        <div class="modal-dialog" >
            <div class="modal-content" style="border-radius:30px;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title color-qrorpa"><strong>Restaurant hinzufügen</strong></h4>
                <button type="button" class="close noOutline" data-dismiss="modal"><img width="35px" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
            </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {{Form::open(['action' => 'RestorantController@store', 'method' => 'post']) }}

                        <div class="form-group">
                            {{ Form::label('Name', null, ['class' => 'control-label']) }}
                            {{ Form::text('emri','', ['class' => 'form-control shadow-none']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Adresse', null, ['class' => 'control-label']) }}
                            {{ Form::text('adresa','', ['class' => 'form-control shadow-none']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('Telefonnummer', null, ['class' => 'control-label']) }}
                            {{ Form::text('resPNr','', ['class' => 'form-control shadow-none']) }}
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">CHE-</span>
                            </div>
                            <input name="che1" id="che1" type="text" class="form-control shadow-none">
                            <div class="input-group-prepend">
                                <span class="input-group-text">.</span>
                            </div>
                            <input name="che2" id="che2" type="text" class="form-control shadow-none">
                            <div class="input-group-prepend">
                                <span class="input-group-text">.</span>
                            </div>
                            <input name="che3" id="che3" type="text" class="form-control shadow-none">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> MWST</span>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::submit('Registrieren', ['class' => 'form-control btn btn-outline-primary']) }}
                        </div>

                    {{Form::close() }}
                </div>

            </div>
        </div>
    </div>











    <div class="container">
        <div class="d-flex justify-content-between">
            <div style="width:20%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Restaurants</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;">{{count($restorantet)}}</p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; opacity:0.65;"> Text test text test ... </p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; margin-bottom:-15px; opacity:0.65;"> Text test text test text test text ... </p>

            </div>

            <div style="width:20%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Heute Vertrieb</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;"><span style="opacity:0.7; font-size:20px;">CHF </span>{{$todaySales}}</p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; opacity:0.65;"> Text test text test ... </p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; margin-bottom:-15px; opacity:0.65;"> Text test text test text test text ... </p>

            </div>

            <div style="width:20%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Restaurants</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;">{{count($restorantet)}}</p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; opacity:0.65;"> Text test text test ... </p>
                <p class="color-white" style="font-size:10px; margin-top:-15px; margin-bottom:-15px; opacity:0.65;"> Text test text test text test text ... </p>

            </div>

            <div style="width:35%;" class="p-4 b-qrorpa br-25">
                <h4 class="color-white" style="opacity:0.7">Aktivitätsverkauf</h4>
                <p class="color-white" style="font-size:40px; margin-top:-15px; opacity:0.9;">---</p>
            

            </div>
        

        </div>
    </div>




    <br><br>


    <div class="container">
        <div class="row mb-3 ">
            <div class="col-6 text-left">
                <p class="color-qrorpa" style="font-size:25px; margin-bottom:-5px;"><strong>Restaurants</strong>
                <span style="font-size:17px;" class="b-qrorpa color-white pr-3 p-2 pl-3 br-25" data-toggle="modal" data-target="#addRes">Restaurant hinzufügen</span>
                </p>
            </div>
            <div class="col-6 text-right">
                <span style="font-size:19px;" class="b-qrorpa color-white pr-3 p-2 pl-3 br-25" data-toggle="modal" data-target="#contentMngs">Content Managers</span>
            </div>
        </div>
    </div>

    <br>











                <!-- The Modal -->
                <div class="modal" id="contentMngs"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
                 style="background-color: rgba(0, 0, 0, 0.5); padding-top:2%;">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title color-qrorpa"><strong>Content Managers</strong></h4>
                                <button type="button" class="close" data-dismiss="modal">X</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body d-flex flex-wrap justify-content-between" id="contMngLinsUsrs">
                                @foreach(User::all()->sortByDesc('created_at') as $usr)
                                    @if($usr->role == 54439)
                                        <div style="width:19%; color:white; background-color:rgb(39,190,175); border:2px solid rgb(39,190,175); border-radius:10px;"
                                            class="mr-1 p-1 mb-2 text-center contentMngModalUsr" onclick="setUsrAsCM('{{$usr->id}}','1')">
                                            <p><strong>{{$usr->name}} CM</strong></p>
                                            <p style="margin-top:-20px; margin-bottom:-7px; font-size:12px;">{{$usr->email}}</p>
                                        </div>
                                    @else
                                        <div style="width:19%; color:rgb(72,81,87); border:2px solid rgb(39,190,175); border-radius:10px;"
                                            class="mr-1 p-2 mb-2 text-center contentMngModalUsr" onclick="setUsrAsCM('{{$usr->id}}','54439')">
                                            <p><strong>{{$usr->name}}</strong></p>
                                            <p style="margin-top:-20px;font-size:12px;">{{$usr->email}}</p>
                                            @if($usr->role == 5)
                                            <p style="margin-top:-20px; margin-bottom:-7px; font-weight:bold">Admin 
                                                @if(Restorant::find($usr->sFor) != NULL)
                                                    ({{Restorant::find($usr->sFor)->emri}})</p>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>





                <script>
                    function setUsrAsCM(usrID, newR){
                        $.ajax({
                            url: '{{ route("restorantet.CMUsrRoleSet") }}',
                            method: 'post',
                            data: {
                                userID: usrID,
                                role: newR,
                                _token: '{{csrf_token()}}'
                            },
                            success: () => {
                                $("#contMngLinsUsrs").load(location.href+" #contMngLinsUsrs>*","");
                            },
                            error: (error) => {
                                console.log(error);
                                alert('bitte aktualisieren und erneut versuchen!');
                            }
                        });
                    }
                </script>













































    <div class="container">
        <div class="row">
            <?php $step=1; ?>
        @foreach($restorantet as $rest)

            <?php
                if($step >4 ){
                    echo '<div class="col-3 mt-3">';
                    
                }else{
                    echo '<div class="col-3">';
                }
            ?>
             <!-- onclick="showRestaurant('{{$rest->id}}')" -->
                <div class="container listRes" style="width:100%; background-color:rgb(247, 247, 240); border-radius:25px;"
                   onclick="location.href='SuperAdminRestorantOne?Res={{$rest->id}}';" >
                    <div class="row p-2">
                        <div class="col-4">
                                @if($rest->profilePic == 'none')
                                    <img width="100%" src="storage/icons/Logo.png" alt="">
                                @else
                                    <img style="width:120%; border-radius:50%;" src="storage/ResProfilePic/{{$rest->profilePic}}" alt="">
                                @endif
                        </div>
                        <div class="col-8">
                            @if(strlen($rest->emri) >= 13)
                            <p class="color-qrorpa mt-2" style="font-size:16px;"><strong>{{$rest->emri}}</strong> </p>
                            @else
                            <p class="color-qrorpa mt-2" style="font-size:18px;"><strong>{{$rest->emri}}</strong> </p>
                            @endif

                            <p style="opacity:0.55; margin-top:-15px; font-size:12px;">{{($rest->adresas == 'empty' ? '---' : $rest->adresa)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $step++;
            ?>
        @endforeach
        </div>
    </div>
</div>
<!-- End of startRestorant Div           Display blocks of restaurants-->

<br>




































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
        $('.allRestaurants').hide();

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