<?php

use App\admExtraAccessToRes;
use App\Barbershop;
use App\User;
    use App\Restorant;
?>

<style>
    .c-pointer:hover{
        cursor:pointer;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">



@foreach(User::all() as $user)
    <!-- The Modal -->
    <div class="modal" id="SuperSet{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false" 
    style="background-color: rgba(0, 0, 0, 0.5); padding-top:5%;">
    <div class="modal-dialog" >
        <div class="modal-content" style="border-radius:35px;">

        <!-- Modal body -->
        <div class="modal-body m-3" >
            <button type="button" class="close" data-dismiss="modal" onclick="location.reload()">
                <img style="width:30px;" src="https://img.icons8.com/ios/50/000000/xbox-x.png"/></button>
            <strong>{{$user->name}}</strong> 
            <hr>
            @if($user->sFor == 0)
                <p class="setResOut{{$user->id}}">None</p>
            @else
                <?php $res = Restorant::find($user->sFor); ?>
                @if($res != NULL)
                    <p class="setResOut{{$user->id}}">{{$res->emri}}</p>
                @else
                    <p style="color:red;" class="setResOut{{$user->id}}">Entfernt</p>
                @endif
            @endif

            <br>

                <div class="form-group">
                    <label for="sel1">Select a restaurant</label>
                    <select name="toCat" class="form-control" onchange="setResAdmin(this.value, '{{$user->id}}')">';
                    
                                        
                    <?php
                        foreach(Restorant::all() as $res){
                            echo '<option class="emriRes'.$res->id.'u'.$user->id.'" value="'.$res->id.'">'.$res->emri.'</option>';        
                        }
                    ?>
                                            
                        </select>
                </div>
        </div>

        

        </div>
    </div>
    </div>
@endforeach







<script>
        
        function setResAdmin(value,uId){
            // alert(value);
            // alert(uId);
            $.ajax({
                url: '{{ url("/userSetRes") }}',
                method:'post',
                data: {sendValue: value, userId: uId, _token: '{{csrf_token()}}'},
                success: (response) => {
                    // alert('success');
                    $('.setResOut'+uId).text($('.emriRes'+value+'u'+uId).text());

                    
                },
                error: (error) => {
                    alert(error.responseText);
                }
            })
        }
</script>











<style>
    .resAdminData{
        font-size:16px;
        font-weight:bold;
        text-align: center;
    }
    .noBorder:active {
        outline: none;
    }

    .noBorder:focus {
        outline: none;
        box-shadow: none;
    }

    a:hover{
        text-decoration:none;
        align-items: center;
    }

    .centerImg{
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 100px;
        height: 100px;
    }
</style>








<div class="text-center">
    <h3 class="color-qrorpa"><strong>Users</strong></h3>
    <hr>
</div>
<div class="container mt-3 mb-3">

    <div class="row">
        <div class="col-12">
            <!-- Actual search box -->
            <div class="form-group has-search">
                <span class="fa fa-search form-control-feedback"></span>
                <input type="text" id="searchAdmin" class="form-control noBorder" placeholder="Search Users">
            </div>
        </div>
    </div>
</div>

    <div id="allUsers" class="p-1 d-flex flex-wrap justify-content-between">

        @foreach(User::all()->sortByDesc('created_at') as $user)
            <div class="card mb-1" style="width: 14.15%;" id="oneUsers{{$user->id}}">
                @if($user->profilePic != 'empty')
                <img class="card-img-top centerImg" src="storage/profilePics/{{$user->profilePic}}" alt="Card image cap">
                @else
                <img class="card-img-top centerImg" src="storage/icons/Asset 24800.png" alt="Card image cap">
                @endif
                <div class="card-body p-1">
                    <h5 class="card-title" style="margin-bottom:2px; font-size:0.8rem;">{{$user->name}}</h5>
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;">{{$user->email}}</p>
                    @if($user->phoneNr != 'empty')
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;"><strong><i class="fas fa-phone"></i> {{$user->phoneNr}}</strong></p>
                    @else
                    <p class="card-text" style="margin-bottom:2px; font-size:0.65rem;"><strong><i class="fas fa-phone-slash"></i></strong></p>
                    @endif
                    <div class="d-flex flex-wrap justify-content-between">

                        @if($user->role == 1)
                        <button class="mb-1 btn btn-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="User">
                            <strong>U</strong>
                        </button>
                        @else
                        <button onclick="setToUserFront('{{$user->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="User">
                            <strong>U</strong>
                        </button>
                        @endif

                        @if($user->role == 9)
                        <button class="mb-1 btn btn-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Super Administrator" disabled>
                            <strong>SA</strong>
                        </button>
                        @else
                        <button onclick="setToSuperadminFront('{{$user->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Super Administrator" disabled>
                            <strong>SA</strong>
                        </button>
                        @endif

                        @if($user->role == 5)
                        <button class="mb-1 btn btn-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Administrator">
                            <strong>AD</strong>
                        </button>
                        @else
                        <button onclick="setToAdminFront('{{$user->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Administrator">
                            <strong>AD</strong>
                        </button>
                        @endif

                        @if($user->role == 55)
                        <button class="mb-1 btn btn-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Waiter">
                            <strong>WA</strong>
                        </button>
                        @else
                        <button onclick="setToWaiterFront('{{$user->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Waiter">
                            <strong>WA</strong>
                        </button>
                        @endif

                        @if($user->role == 54)
                        <button class="mb-1 btn btn-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Cooker/Chef">
                            <strong>CO</strong>
                        </button>
                        @else
                        <button onclick="setToCookerFront('{{$user->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Cooker/Chef">
                            <strong>CO</strong>
                        </button>
                        @endif

                        @if($user->role == 53)
                        <button class="mb-1 btn btn-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Accountant">
                            <strong>AC</strong>
                        </button>
                        @else
                        <button onclick="setToAccountantFront('{{$user->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Accountant">
                            <strong>AC</strong>
                        </button>
                        @endif

                        @if($user->role == 33)
                        <button class="mb-1 btn btn-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Contract Agent">
                            <strong>CA</strong>
                        </button>
                        @else
                        <button onclick="setToContractAgentFront('{{$user->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Contract Agent">
                            <strong>CA</strong>
                        </button>
                        @endif
                        
                        @if($user->role == 15)
                        <button class="mb-1 btn btn-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Barbershop Administrator">
                            <strong>BA</strong>
                        </button>
                        @else
                        <button onclick="setToBarbershopAdminFront('{{$user->id}}')" class="mb-1 btn btn-outline-dark text-center shadow-none" style="width:24%; font-size:0.7rem; padding:0px;" data-toggle="tooltip" data-placement="top" title="Barbershop Administrator">
                            <strong>BA</strong>
                        </button>
                        @endif


                        @if($user->role == 5 || $user->role == 55 || $user->role == 54 || $user->role == 53)
                            @if($user->sFor == 0)
                                <button onclick="showResToSelect('{{$user->id}}','{{$user->name}}')" class="mt-1 pt-1 pb-1 btn btn-outline-dark text-center" style="width:100%; font-size:0.7rem; padding:0px;"><strong>Not Set</strong></button>
                            @else
                                <?php $theRes = Restorant::find($user->sFor);?>
                                @if($theRes != NULL)
                                <button onclick="showResToSelect('{{$user->id}}','{{$user->name}}')" class="mt-1 pt-1 pb-1 btn btn-outline-dark text-center" style="width:100%; font-size:0.7rem; padding:0px;"><strong>({{$theRes->id}} #) {{$theRes->emri}}</strong></button>
                                @else
                                <button onclick="showResToSelect('{{$user->id}}','{{$user->name}}')" class="mt-1 pt-1 pb-1 btn btn-outline-dark text-center" style="width:100%; font-size:0.7rem; padding:0px;"><strong>Not found</strong></button>
                                @endif 
                            @endif
                        @elseif($user->role == 15)   
                            @if($user->sFor == 0)
                                <button onclick="showBarToSelect('{{$user->id}}','{{$user->name}}')" class="mt-1 pt-1 pb-1 btn btn-outline-dark text-center" style="width:100%; font-size:0.7rem; padding:0px;"><strong>Not Set</strong></button>
                            @else
                                <?php $theBar = Barbershop::find($user->sFor);?>
                                @if($theBar != NULL)
                                <button onclick="showBarToSelect('{{$user->id}}','{{$user->name}}')" class="mt-1 pt-1 pb-1 btn btn-outline-dark text-center" style="width:100%; font-size:0.7rem; padding:0px;"><strong>({{$theBar->id}} #) {{$theBar->emri}}</strong></button>
                                @else
                                <button onclick="showBarToSelect('{{$user->id}}','{{$user->name}}')" class="mt-1 pt-1 pb-1 btn btn-outline-dark text-center" style="width:100%; font-size:0.7rem; padding:0px;"><strong>Not found</strong></button>
                                @endif 
                            @endif
                        @else
                        <button class="mt-1 pt-1 pb-1 btn btn-outline-dark text-center" style="width:100%; font-size:0.7rem; padding:0px;"><strong> --- </strong></button>
                        @endif

                        @if($user->role == 5 || $user->role == 53)
                            <button data-toggle="modal" data-target="#exResAcsFor{{$user->id}}" id="exResAcsFor{{$user->id}}Btn"
                            class="mt-1 pt-1 pb-1 btn btn-outline-dark text-center shadow-none" style="width:100%; font-size:0.7rem; padding:0px;">
                                <strong>Extra Access ( {{admExtraAccessToRes::where('admId',$user->id)->get()->count()}} )</strong>
                            </button>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach

    </div>




        <!-- select RESTAURANT Modal -->
        <div class="modal" id="restaurantSelection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"
        style="background-color: rgba(0, 0, 0, 0.5); padding-top: 1%;" aria-modal="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><strong>Select a restaurant For <span id="restaurantSelectionUserName"></span></strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ">
                        <input type="hidden" id="restaurantSelectionUsrIdFor" value="0">
                        <div class="d-flex flex-wrap justify-content-start">
                            @foreach (Restorant::all()->sortByDesc('created_at') as $oneRes)
                                <button onclick="selectResFormAdm('{{$oneRes->id}}')" style="width: 19.7%; margin-right:0.3%;" class="btn btn-outline-dark mb-2"><strong>{{$oneRes->emri}}</strong></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- select Barbershop Modal -->
        <div class="modal" id="barbershopSelection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"
        style="background-color: rgba(0, 0, 0, 0.5); padding-top: 1%;" aria-modal="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><strong>Select a barbershop For <span id="barbershopSelectionUserName"></span></strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ">
                        <input type="hidden" id="barbershopSelectionUsrIdFor" value="0">
                        <div class="d-flex flex-wrap justify-content-start">
                            @foreach (Barbershop::all()->sortByDesc('created_at') as $oneBar)
                                <button onclick="selectBarFormAdm('{{$oneBar->id}}')" style="width: 19.7%; margin-right:0.3%;" class="btn btn-outline-dark mb-2"><strong>{{$oneBar->emri}}</strong></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @foreach (User::whereIn('role',['5','53'])->get()->sortByDesc('created_at') as $admOne)
            <div class="modal" id="exResAcsFor{{$admOne->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"
            style="background-color: rgba(0, 0, 0, 0.5); padding-top: 1%;" aria-modal="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><strong>Konfigurieren Sie den zusätzlichen Zugriff auf andere Restaurants für die Berichte</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">X</span>
                            </button>
                        </div>
                        <div class="modal-body " >
                            <div class="d-flex flex-wrap justify-content-start" id="exResAcsFor{{$admOne->id}}Body">
                                @foreach (Restorant::all()->sortByDesc('created_at') as $theRes)
                                    <?php $exAc = admExtraAccessToRes::where([['admId',$admOne->id],['toRes',$theRes->id]])->first(); ?>
                                    @if ($theRes->id == $admOne->sFor)
                                        <button style="width: 19.7%; margin-right:0.3%;" class="btn btn-success mb-2 shadow-none" disabled>                                
                                    @elseif ($exAc == null)
                                        <button onclick="setResForAdmExt('{{$admOne->id}}','{{$theRes->id}}')" style="width: 19.7%; margin-right:0.3%;" 
                                        class="btn btn-outline-dark mb-2 shadow-none" id="setRevExResBtn{{$admOne->id}}--{{$theRes->id}}">
                                    @else
                                        <button onclick="removeResForAdmExt('{{$admOne->id}}','{{$theRes->id}}')" style="width: 19.7%; margin-right:0.3%;" 
                                        class="btn btn-dark mb-2 shadow-none" id="setRevExResBtn{{$admOne->id}}--{{$theRes->id}}">
                                    @endif
                                        <strong>{{$theRes->emri}}</strong>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach



















    <br><br>

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>

        $(document).ready(function() {

            $("#searchAdmin").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#userAdmin tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });

        function setToUserFront(usrId){
            $.ajax({
				url: '{{ route("saMngUsr.setToUser") }}',
				method: 'post',
				data: { userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*",""); },
				error: (error) => { console.log(error); }
			});
        }

        function setToAdminFront(usrId){
            $.ajax({
				url: '{{ route("saMngUsr.setToAdmin") }}',
				method: 'post',
				data: { userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*",""); },
				error: (error) => { console.log(error); }
			});
        }

        function setToWaiterFront(usrId){
            $.ajax({
				url: '{{ route("saMngUsr.setToWaiter") }}',
				method: 'post',
				data: { userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*",""); },
				error: (error) => { console.log(error); }
			});
        }

        function setToCookerFront(usrId){
            $.ajax({
				url: '{{ route("saMngUsr.setToCook") }}',
				method: 'post',
				data: { userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*",""); },
				error: (error) => { console.log(error); }
			});
        }

        function setToAccountantFront(usrId){
            $.ajax({
				url: '{{ route("saMngUsr.setToAccountant") }}',
				method: 'post',
				data: { userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*",""); },
				error: (error) => { console.log(error); }
			});
        }

        function setToSuperadminFront(usrId){
            $.ajax({
				url: '{{ route("saMngUsr.setToSuperadmin") }}',
				method: 'post',
				data: { userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*",""); },
				error: (error) => { console.log(error); }
			});
        }

        function setToContractAgentFront(usrId){
            $.ajax({
				url: '{{ route("saMngUsr.setToContractAgent") }}',
				method: 'post',
				data: { userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*",""); },
				error: (error) => { console.log(error); }
			});
        }

        function setToBarbershopAdminFront(usrId){
            $.ajax({
				url: '{{ route("saMngUsr.setToBarbershopAdminAgent") }}',
				method: 'post',
				data: { userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*",""); },
				error: (error) => { console.log(error); }
			});
        }



        function showResToSelect(uId, uName){
            $('#restaurantSelectionUserName').html(uName);
            $('#restaurantSelectionUsrIdFor').val(uId);
            $('#restaurantSelection').modal('show');
        }

        function selectResFormAdm(resId){
            var usrId = $('#restaurantSelectionUsrIdFor').val();
            $.ajax({
				url: '{{ route("saMngUsr.selectThisResForThisAdm") }}',
				method: 'post',
				data: { rId: resId, userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { 
                    $("#oneUsers"+usrId).load(location.href+"#oneUsers"+usrId+">*","");
                    $('#restaurantSelection').modal('hide');
                    $('#restaurantSelectionUserName').html('');
                    $('#restaurantSelectionUsrIdFor').val('');
                },
				error: (error) => { console.log(error); }
			});
        }






        function setResForAdmExt(admId,resId){
            $('#setRevExResBtn'+admId+'--'+resId).html('<img src="storage/gifs/loading2.gif" style="width: 25px ; height: 25px;" alt="">');
            $.ajax({
				url: '{{ route("saMngUsr.setResToAdmExtraAcs") }}',
				method: 'post',
				data: { rId: resId, aId: admId, _token: '{{csrf_token()}}' },
				success: () => { 
                    $("#oneUsers"+admId).load(location.href+" #oneUsers"+admId+">*","");
                    $("#exResAcsFor"+admId+"Body").load(location.href+" #exResAcsFor"+admId+"Body>*","");
                },
				error: (error) => { console.log(error); }
			});
        }
        function removeResForAdmExt(admId,resId){
            $('#setRevExResBtn'+admId+'--'+resId).html('<img src="storage/gifs/loading2.gif" style="width: 25px ; height: 25px;" alt="">');
            $.ajax({
				url: '{{ route("saMngUsr.removeResToAdmExtraAcs") }}',
				method: 'post',
				data: { rId: resId, aId: admId, _token: '{{csrf_token()}}' },
				success: () => { 
                    $("#oneUsers"+admId).load(location.href+" #oneUsers"+admId+">*","");
                    $("#exResAcsFor"+admId+"Body").load(location.href+" #exResAcsFor"+admId+"Body>*","");
                },
				error: (error) => { console.log(error); }
			});
        }








        function showBarToSelect(uId, uName){
            $('#barbershopSelectionUserName').html(uName);
            $('#barbershopSelectionUsrIdFor').val(uId);
            $('#barbershopSelection').modal('show');
        }

        function selectBarFormAdm(barId){
            var usrId = $('#barbershopSelectionUsrIdFor').val();
            $.ajax({
				url: '{{ route("saMngUsr.selectThisBarForThisAdm") }}',
				method: 'post',
				data: { bId: barId, userId: usrId, _token: '{{csrf_token()}}' },
				success: () => { 
                    $("#oneUsers"+usrId).load(location.href+" #oneUsers"+usrId+">*","");
                    $('#barbershopSelection').modal('hide');
                    $('#barbershopSelectionUserName').html('');
                    $('#barbershopSelectionUsrIdFor').val('');
                },
				error: (error) => { console.log(error); }
			});
        }


        
    </script>